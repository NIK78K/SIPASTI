<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_ticket extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        if (
            !$this->session->userdata('logged_in') ||
            $this->session->userdata('role') !== 'user'
        ) {
            redirect('auth/login');
        }

        $this->load->model('User_ticket_model');
    }
/* ===============================
   FORM KATEGORI LAINNYA
=============================== */
public function store()
{
    // hanya boleh POST
    if (!$this->input->post()) {
        show_404();
    }

    // 🔥 PAKSA kategori = lainnya
    $_POST['kategori'] = 'lainnya';

    $this->User_ticket_model->create_ticket();

    redirect('user_ticket/history');
    exit;
}

/* ===============================
   FORM GANGGUAN / KERUSAKAN
=============================== */
public function create_gangguan()
{
    // ================= POST =================
    if ($this->input->post()) {
 
        $_POST['kategori'] = 'gangguan';
 
        // Handle multiple perangkat (hanya untuk gangguan IT)
        $perangkat_ids_json = $this->input->post('perangkat_ids');
        $perangkat_ids      = json_decode($perangkat_ids_json, true);
 
        if (!empty($perangkat_ids) && is_array($perangkat_ids)) {
            $_POST['perangkat_id'] = $perangkat_ids[0];
 
            // Jika lebih dari 1 perangkat, sisipkan daftar ke deskripsi
            if (count($perangkat_ids) > 1) {
                $perangkat_info      = $this->get_perangkat_info($perangkat_ids);
                $_POST['jenis_kerusakan'] = "[PERANGKAT TERKAIT]\n"
                    . implode("\n", $perangkat_info)
                    . "\n\n[DESKRIPSI]\n"
                    . $_POST['jenis_kerusakan'];
            }
        } else {
            // Tidak ada perangkat (layanan umum / opsional tidak diisi)
            $_POST['perangkat_id'] = null;
        }
 
        $this->User_ticket_model->create_ticket();
 
        $this->session->set_flashdata('success', 'Laporan gangguan berhasil dikirim');
        redirect('user_ticket/history');
        exit;
    }
 
    // ================= GET =================
    $kategori_id_gangguan = $this->User_ticket_model->get_kategori_id_by_slug('gangguan');
 
    if (!$kategori_id_gangguan) {
        show_error('Kategori gangguan tidak ditemukan di database');
    }
 
    // Sub kategori IT (hardware, software, jaringan, dll)
    $sub_kategori_it = $this->User_ticket_model->get_sub_kategori($kategori_id_gangguan);
 
    // Sub kategori Rumah Tangga (layanan umum) — ambil dari DB
    $sub_kategori_umum = $this->db
        ->get_where('master_sub_kategori', ['slug' => 'rumah-tangga'])
        ->row();
 
    if (!$sub_kategori_umum) {
        show_error('Sub kategori Rumah Tangga tidak ditemukan. Pastikan sudah di-insert ke database.');
    }
 
    $data = [
        'kategori_id'      => $kategori_id_gangguan,
        'sub_kategori_it'  => $sub_kategori_it,
        'sub_kategori_umum'=> $sub_kategori_umum,
        'perangkat'        => $this->User_ticket_model->get_perangkat(),
    ];
 
    $this->load->view('layout/header_user');
    $this->load->view('user/create_gangguan', $data);
    $this->load->view('layout/footer_user');
}
 

/* ===============================
   HELPER - GET PERANGKAT INFO
=============================== */
private function get_perangkat_info($ids)
{
    $result = [];
 
    foreach ($ids as $id) {
        $perangkat = $this->db
            ->select('kode_barang, nup, nama_barang')
            ->where('id', $id)
            ->get('perangkat')
            ->row_array();
 
        if ($perangkat) {
            $result[] = "- {$perangkat['kode_barang']} | {$perangkat['nup']} - {$perangkat['nama_barang']}";
        }
    }
 
    return $result;
}

    /* ===============================
       FORM PERMINTAAN
    =============================== */
public function create_permintaan()
{
  if ($this->input->post()) {
    $_POST['kategori'] = 'permintaan';

    // ── Conflict check ruang rapat ──
    if ($this->input->post('is_ruang_rapat') == '1') {
        $ruangan_id  = (int) $this->input->post('ruangan_id');
        $tanggal     = $this->input->post('tanggal_rapat');
        $jam_mulai   = $this->input->post('jam_mulai');
        $jam_selesai = $this->input->post('jam_selesai');

        if ($ruangan_id && $tanggal && $jam_mulai && $jam_selesai) {
            $konflik = $this->db
                ->where('ruangan_id', $ruangan_id)
                ->where('tanggal', $tanggal)
                ->where_in('status', ['Menunggu', 'Disetujui'])
                ->group_start()
                    ->where('jam_mulai <', $jam_selesai)
                    ->where('jam_selesai >', $jam_mulai)
                ->group_end()
                ->count_all_results('peminjaman_ruang_rapat');

            if ($konflik > 0) {
                $this->session->set_flashdata('error',
                    'Ruangan sudah dipesan pada waktu tersebut. Pilih ruangan atau waktu lain.');
                redirect('user_ticket/create_permintaan');
                exit;
            }
        }
    }

    // ── Conflict check kendaraan ──
    if ($this->input->post('is_kendaraan') == '1') {
        $kendaraan_id  = (int) $this->input->post('kend_kendaraan_id');
        $tipe          = $this->input->post('kend_tipe_pinjam') ?: 'harian';          
        $tgl_pinjam    = $this->input->post('kend_tanggal_pinjam');
        $tgl_kembali   = $this->input->post('kend_tanggal_kembali');
        $jam_pinjam    = $this->input->post('kend_jam_pinjam')  ?: null;
        $jam_kembali   = $this->input->post('kend_jam_kembali') ?: null;

        if ($kendaraan_id && $tgl_pinjam && $tgl_kembali) {
            $this->load->model('Peminjaman_kendaraan_model');
            $konflik = $this->Peminjaman_kendaraan_model->is_conflict(
                $kendaraan_id, $tgl_pinjam, $tgl_kembali,
                ($tipe === 'per_jam' ? $jam_pinjam  : null),
                ($tipe === 'per_jam' ? $jam_kembali : null)
            );

            if ($konflik) {
                $this->session->set_flashdata('error',
                    'Kendaraan sudah dipinjam pada rentang waktu tersebut. Pilih kendaraan atau waktu lain.');
                redirect('user_ticket/create_permintaan');
                exit;
            }
        }
    }

    // ── Conflict check BMN ──
    if ($this->input->post('is_bmn') == '1') {
        $bmn_id      = (int) $this->input->post('bmn_id');
        $tgl_pinjam  = $this->input->post('bmn_tanggal_pinjam');
        $tgl_kembali = $this->input->post('bmn_tanggal_kembali');

        if ($bmn_id && $tgl_pinjam && $tgl_kembali) {
            $konflik = $this->db
                ->where('bmn_id', $bmn_id)
                ->where_in('status', ['Menunggu', 'Disetujui'])
                ->where('tanggal_pinjam <=', $tgl_kembali)
                ->where('tanggal_kembali >=', $tgl_pinjam)
                ->count_all_results('peminjaman_bmn');

            if ($konflik > 0) {
                $this->session->set_flashdata('error',
                    'Barang BMN sudah dipinjam pada rentang tanggal tersebut. Pilih barang atau tanggal lain.');
                redirect('user_ticket/create_permintaan');
                exit;
            }
        }
    }

    $result = $this->User_ticket_model->create_ticket();

    if ($result === false) {
        redirect('user_ticket/create_permintaan');
    } else {
        redirect('user_ticket/history');
    }
    exit;
}
    $kategori_id = $this->User_ticket_model->get_kategori_id_by_slug('permintaan');

    if (!$kategori_id) {
        show_error('Kategori permintaan tidak ditemukan di database');
    }

   $this->load->model('Kendaraan_model');
$this->load->model('Master_bmn_model');

$data = [
    'kategori_id'      => $kategori_id,
    'sub_kategori'     => $this->User_ticket_model->get_sub_kategori($kategori_id),
    'daftar_kendaraan' => $this->Kendaraan_model->get_all(),
    'daftar_bmn'       => $this->Master_bmn_model->get_aktif(),
];

    $this->load->view('layout/header_user');
    $this->load->view('user/create_permintaan', $data);
    $this->load->view('layout/footer_user');
}

    /* ===============================
       PERMINTAAN BARANG PERSEDIAAN (katalog + keranjang, tanpa bayar)
       Keranjang = menandai barang dibutuhkan. Stok berkurang
       hanya saat teknisi approve manual. Tanpa insert transaksi.
    =============================== */
/* ===============================
   OPSI UNIT KERJA (dipakai form persediaan)
================================ */
private function _unit_kerja_options()
{
    $unit_kerja_list = $this->db
        ->select('unit_kerja')
        ->where('unit_kerja IS NOT NULL', null, false)
        ->where("TRIM(unit_kerja) <> ''", null, false)
        ->group_by('unit_kerja')
        ->order_by('unit_kerja', 'ASC')
        ->get('user_profiles')
        ->result_array();
    $unit_kerja_list = array_values(array_filter(array_map(function ($r) {
        return trim($r['unit_kerja'] ?? '');
    }, $unit_kerja_list)));
    $unit_kerja_default = trim($this->db
        ->select('unit_kerja')
        ->where('user_id', $this->session->userdata('user_id'))
        ->get('user_profiles')
        ->row('unit_kerja') ?? '');

    return [$unit_kerja_list, $unit_kerja_default];
}

/* ===============================
   PEMILIH JENIS LAYANAN BARANG PERSEDIAAN
================================ */
public function create_persediaan()
{
    $this->load->view('layout/header_user');
    $this->load->view('user/create_persediaan');
    $this->load->view('layout/footer_user');
}

/* ===============================
   PERMINTAAN BARANG PERSEDIAAN (katalog + keranjang, tanpa bayar)
   Keranjang = menandai barang dibutuhkan. Stok berkurang
   hanya saat teknisi approve manual. Tanpa insert transaksi.
=============================== */
public function permintaan_persediaan()
{
    $this->load->model('Barang_persediaan_model');

    $bulan = (int) date('n');
    $tahun = (int) date('Y');

    list($unit_kerja_list, $unit_kerja_default) = $this->_unit_kerja_options();

    $data = [
        'kategori_list' => $this->Barang_persediaan_model->get_kategori_grouped(),
        'barang_list'   => $this->User_ticket_model->get_katalog_persediaan_with_sisa($bulan, $tahun),
        'bulan'         => $bulan,
        'tahun'         => $tahun,
        'unit_kerja_list'    => $unit_kerja_list,
        'unit_kerja_default' => $unit_kerja_default,
    ];

    $this->load->view('layout/header_user');
    $this->load->view('user/permintaan_persediaan', $data);
    $this->load->view('layout/footer_user');
}

/* ---------- session cart helpers ---------- */
private function _cart_persediaan_get()
{
    $c = $this->session->userdata('cart_persediaan');
    return is_array($c) ? $c : [];
}

private function _cart_persediaan_save($cart)
{
    $this->session->set_userdata('cart_persediaan', $cart);
}

private function _cart_persediaan_json($data)
{
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

private function _barang_persediaan_aktif($barang_id)
{
    return $this->db
        ->select('b.id, b.kode_barang, b.nama_barang, b.satuan, b.status, b.kategori_id, k.nama AS kategori_nama')
        ->from('master_barang_persediaan b')
        ->join('master_kategori_persediaan k', 'k.id = b.kategori_id', 'left')
        ->where('b.id', (int) $barang_id)
        ->where('b.status', 'Aktif')
        ->get()
        ->row_array();
}

private function _cart_persediaan_summary()
{
    $cart   = $this->_cart_persediaan_get();
    $items  = [];
    $total  = 0;
    $pruned = false;

    foreach ($cart as $bid => $qty) {
        $b = $this->db
            ->select('id, kode_barang, nama_barang, satuan, status')
            ->where('id', (int) $bid)
            ->get('master_barang_persediaan')
            ->row_array();
        if (!$b) { unset($cart[$bid]); $pruned = true; continue; }
        $sisa = $this->User_ticket_model->get_sisa_persediaan((int) $bid, (int) date('n'), (int) date('Y'));
        $items[] = [
            'barang_id' => (int) $bid,
            'kode'      => $b['kode_barang'],
            'nama'      => $b['nama_barang'],
            'satuan'    => $b['satuan'],
            'status'    => $b['status'],
            'sisa'      => (int) $sisa,
            'qty'       => (int) $qty,
        ];
        $total += (int) $qty;
    }

    if ($pruned) $this->_cart_persediaan_save($cart);

    return ['count' => count($items), 'total_qty' => $total, 'items' => $items];
}

/* ---------- cart endpoints (AJAX JSON) ---------- */
public function cart_add()
{
    $barang_id = (int) $this->input->post('barang_id');
    $jumlah    = (int) $this->input->post('jumlah');

    if ($barang_id <= 0) $this->_cart_persediaan_json(['success' => false, 'message' => 'Barang tidak valid.']);
    if ($jumlah < 1) $this->_cart_persediaan_json(['success' => false, 'message' => 'Jumlah minimal 1.']);

    $b = $this->_barang_persediaan_aktif($barang_id);
    if (!$b) $this->_cart_persediaan_json(['success' => false, 'message' => 'Barang tidak tersedia.']);

    $sisa = (int) $this->User_ticket_model->get_sisa_persediaan($barang_id, (int) date('n'), (int) date('Y'));
    if ($sisa <= 0) $this->_cart_persediaan_json(['success' => false, 'message' => 'Stok barang habis, tidak bisa ditambahkan ke keranjang.']);

    $cart     = $this->_cart_persediaan_get();
    $existing = isset($cart[$barang_id]) ? (int) $cart[$barang_id] : 0;
    $final    = $existing + $jumlah;
    if ($final < 1) $this->_cart_persediaan_json(['success' => false, 'message' => 'Jumlah minimal 1.']);
    if ($final > $sisa) $this->_cart_persediaan_json(['success' => false, 'message' => 'Stok tersisa ' . $sisa . ', kurangi jumlahnya.']);
    $cart[$barang_id] = $final;
    $this->_cart_persediaan_save($cart);

    $sum = $this->_cart_persediaan_summary();

    $this->_cart_persediaan_json([
        'success' => true, 'message' => 'Ditambahkan ke keranjang.',
        'qty' => $final, 'sisa' => $sisa,
        'count' => $sum['count'], 'total_qty' => $sum['total_qty'],
    ]);
}

public function cart_update()
{
    $barang_id = (int) $this->input->post('barang_id');
    $jumlah    = (int) $this->input->post('jumlah');
    $cart      = $this->_cart_persediaan_get();

    if ($barang_id <= 0 || !isset($cart[$barang_id])) {
        $this->_cart_persediaan_json(['success' => false, 'message' => 'Item tidak ada di keranjang.']);
    }

    if ($jumlah <= 0) {
        unset($cart[$barang_id]);
        $this->_cart_persediaan_save($cart);
        $sum = $this->_cart_persediaan_summary();
        $this->_cart_persediaan_json([
            'success' => true, 'message' => 'Item dihapus.',
            'count' => $sum['count'], 'total_qty' => $sum['total_qty'],
        ]);
    }

    $b = $this->_barang_persediaan_aktif($barang_id);
    if (!$b) $this->_cart_persediaan_json(['success' => false, 'message' => 'Barang tidak tersedia.']);

    $sisa = (int) $this->User_ticket_model->get_sisa_persediaan($barang_id, (int) date('n'), (int) date('Y'));
    if ($sisa <= 0) $this->_cart_persediaan_json(['success' => false, 'message' => 'Stok barang habis, tidak bisa ditambahkan ke keranjang.']);

    if ($jumlah > $sisa) $this->_cart_persediaan_json(['success' => false, 'message' => 'Stok tersisa ' . $sisa . ', kurangi jumlahnya.']);

    $cart[$barang_id] = $jumlah;
    $this->_cart_persediaan_save($cart);

    $sum = $this->_cart_persediaan_summary();
    $this->_cart_persediaan_json([
        'success' => true,
        'message' => 'Jumlah diperbarui.',
        'qty' => $jumlah, 'sisa' => $sisa,
        'count' => $sum['count'], 'total_qty' => $sum['total_qty'],
    ]);
}

public function cart_remove()
{
    $barang_id = (int) $this->input->post('barang_id');
    $cart      = $this->_cart_persediaan_get();

    if ($barang_id <= 0 || !isset($cart[$barang_id])) {
        $this->_cart_persediaan_json(['success' => false, 'message' => 'Item tidak ada di keranjang.']);
    }

    unset($cart[$barang_id]);
    $this->_cart_persediaan_save($cart);
    $sum = $this->_cart_persediaan_summary();
    $this->_cart_persediaan_json([
        'success' => true, 'message' => 'Item dihapus.',
        'count' => $sum['count'], 'total_qty' => $sum['total_qty'],
    ]);
}

public function cart_clear()
{
    $this->session->unset_userdata('cart_persediaan');
    $this->_cart_persediaan_json(['success' => true, 'message' => 'Keranjang dikosongkan.', 'count' => 0, 'total_qty' => 0]);
}

public function cart_count()
{
    $sum = $this->_cart_persediaan_summary();
    $this->_cart_persediaan_json($sum);
}

/* ---------- checkout: 1 tiket Menunggu, tanpa insert transaksi ---------- */
public function checkout_persediaan()
{
    if (!$this->input->post()) {
        redirect('user_ticket/permintaan_persediaan');
        exit;
    }

    $keperluan          = trim($this->input->post('keperluan', true) ?? '');
    $judul              = trim($this->input->post('judul', true) ?? '');
    $deskripsi          = trim($this->input->post('deskripsi', true) ?? '');
    $unit_kerja         = trim($this->input->post('unit_kerja', true) ?? '');
    $tanggal_permintaan = trim($this->input->post('tanggal_permintaan', true) ?: ($this->input->post('tanggal_dibutuhkan', true) ?? ''));

    if ($judul === '' && $keperluan === '') {
        $this->session->set_flashdata('error', 'Judul/Keperluan wajib diisi.');
        redirect('user_ticket/permintaan_persediaan');
        exit;
    }
    $judul_final = $judul !== '' ? $judul : $keperluan;

    $cart = $this->_cart_persediaan_get();
    if (empty($cart)) {
        $this->session->set_flashdata('error', 'Keranjang masih kosong. Pilih dulu barang yang diminta.');
        redirect('user_ticket/permintaan_persediaan');
        exit;
    }

    if ($tanggal_permintaan !== '') {
        $d = DateTime::createFromFormat('Y-m-d', $tanggal_permintaan);
        if (!$d || $d->format('Y-m-d') !== $tanggal_permintaan || $tanggal_permintaan < date('Y-m-d')) {
            $this->session->set_flashdata('error', 'Tanggal permintaan tidak valid. Pilih hari ini atau tanggal ke depan.');
            redirect('user_ticket/permintaan_persediaan');
            exit;
        }
    }

    $lines = [];
    $errors = [];
    $total_qty = 0;
    $snap = [];

    foreach ($cart as $bid => $qty) {
        $qty = (int) $qty;
        $b = $this->_barang_persediaan_aktif((int) $bid);
        if (!$b) { $errors[] = 'Barang ID ' . (int) $bid . ' tidak tersedia.'; continue; }
        if ($qty < 1) { $errors[] = $b['nama_barang'] . ': jumlah tidak valid.'; continue; }
        $sisa_co = (int) $this->User_ticket_model->get_sisa_persediaan((int) $bid, (int) date('n'), (int) date('Y'));
        if ($sisa_co <= 0) { $errors[] = $b['nama_barang'] . ': stok habis, tidak bisa diminta.'; continue; }
        if ($qty > $sisa_co) { $errors[] = $b['nama_barang'] . ': stok tersisa ' . $sisa_co . ', kurangi jumlahnya.'; continue; }
        $lines[] = '- ' . ($b['kode_barang'] ?? '-') . ' - ' . $b['nama_barang'] . ' x ' . $qty . ' ' . ($b['satuan'] ?? '');
        $total_qty += $qty;
        $snap[] = [
            'barang_id' => (int) $bid,
            'jumlah'    => $qty,
            'nama'      => $b['nama_barang'],
            'satuan'    => $b['satuan'] ?? 'pcs',
        ];
    }

    if (!empty($errors)) {
        $this->session->set_flashdata('error', implode(' ', $errors));
        redirect('user_ticket/permintaan_persediaan');
        exit;
    }

    $kategori_id = $this->User_ticket_model->get_kategori_id_by_slug('permintaan');
    $sub = $this->User_ticket_model->ensure_persediaan_sub();
    if (!$kategori_id || !$sub) {
        $this->session->set_flashdata('error', 'Kategori permintaan barang belum tersedia.');
        redirect('user_ticket/permintaan_persediaan');
        exit;
    }

    $ringkas   = [];
    $ringkas[] = '[PERMINTAAN BARANG PERSEDIAAN]';
    $ringkas   = array_merge($ringkas, $lines);
    $ringkas[] = '';
    $ringkas[] = 'Keperluan: ' . $keperluan;
    if ($unit_kerja !== '') $ringkas[] = 'Unit Kerja: ' . $unit_kerja;
    if ($tanggal_permintaan !== '') $ringkas[] = 'Tanggal permintaan: ' . $tanggal_permintaan;
    $ringkas[] = 'Total: ' . count($lines) . ' jenis, ' . $total_qty . ' item';
    $ringkas[] = 'Catatan: keranjang hanya menandai kebutuhan, stok berkurang saat teknisi menyetujui.';

    $this->db->insert('tickets', [
        'user_id'         => $this->session->userdata('user_id'),
        'pelapor'         => $this->session->userdata('name'),
        'kode_tiket'      => 'TKT-' . date('YmdHis'),
        'kategori_id'     => $kategori_id,
        'sub_kategori_id' => $sub->id,
        'jenis_kerusakan' => implode("\n", $ringkas),
        'kebutuhan'       => substr($judul_final, 0, 255),
        'status'          => 'Menunggu',
        'created_at'      => date('Y-m-d H:i:s'),
    ]);
    $ticket_id = $this->db->insert_id();

    // Tanpa insert transaksi_persediaan: hanya detail item.
    foreach ($snap as $s) {
        $this->db->insert('ticket_persediaan_items', [
            'ticket_id'            => $ticket_id,
            'barang_id'            => $s['barang_id'],
            'jumlah'               => $s['jumlah'],
            'nama_barang_snapshot' => substr($s['nama'], 0, 150),
            'satuan_snapshot'      => substr($s['satuan'], 0, 50),
        ]);
    }

    $this->session->unset_userdata('cart_persediaan');
    $this->session->set_flashdata('success', 'Permintaan ' . count($lines) . ' jenis barang berhasil dibuat. Pantau di riwayat tiket.');
    redirect('user_ticket/history');
    exit;
}

/* ---------- pengajuan: barang BELUM ada di daftar/katalog persediaan ---------- */
public function pengajuan_persediaan()
{
    /* ---------- GET: tampilkan form pengajuan ---------- */
    if (!$this->input->post()) {
        list($unit_kerja_list, $unit_kerja_default) = $this->_unit_kerja_options();

        $kategori_persediaan = $this->db
            ->order_by('kode', 'ASC')
            ->get('master_kategori_persediaan')
            ->result_array();

        $data = [
            'unit_kerja_list'     => $unit_kerja_list,
            'unit_kerja_default'  => $unit_kerja_default,
            'kategori_persediaan' => $kategori_persediaan,
        ];

        $this->load->view('layout/header_user');
        $this->load->view('user/pengajuan_persediaan', $data);
        $this->load->view('layout/footer_user');
        return;
    }

    /* ---------- POST: simpan pengajuan ---------- */
    $back = 'user_ticket/pengajuan_persediaan';

    $nama_arr     = $this->input->post('nama_barang');
    $kategori_arr = $this->input->post('kategori_id');
    $ket_arr      = $this->input->post('keterangan');

    $keperluan          = trim($this->input->post('keperluan', true) ?? '');
    $unit_kerja         = trim($this->input->post('unit_kerja', true) ?? '');
    $tanggal_permintaan = trim($this->input->post('tanggal_permintaan', true) ?: ($this->input->post('tanggal_dibutuhkan', true) ?? ''));

    if ($keperluan === '') {
        $this->session->set_flashdata('error', 'Keperluan wajib diisi.');
        redirect($back);
        exit;
    }

    // Mapping kategori persediaan
    $all_kat = $this->db->get('master_kategori_persediaan')->result_array();
    $kat_map = [];
    foreach ($all_kat as $k) {
        $kat_map[(int)$k['id']] = $k['kode'] . ' - ' . $k['nama'];
    }

    $items = [];
    if (is_array($nama_arr)) {
        foreach ($nama_arr as $i => $n) {
            $n = trim(strip_tags((string) $n));
            if ($n === '') continue;
            $k = trim(strip_tags((string) ($ket_arr[$i] ?? '')));
            $kid = !empty($kategori_arr[$i]) ? (int) $kategori_arr[$i] : null;
            $knama = ($kid && isset($kat_map[$kid])) ? $kat_map[$kid] : '';
            $items[] = [
                'nama'          => substr($n, 0, 150),
                'kategori_id'   => $kid,
                'kategori_nama' => $knama,
                'ket'           => substr($k, 0, 255),
            ];
        }
    }

    if (empty($items)) {
        $this->session->set_flashdata('error', 'Isi minimal 1 barang yang diajukan.');
        redirect($back);
        exit;
    }

    if ($tanggal_permintaan !== '') {
        $d = DateTime::createFromFormat('Y-m-d', $tanggal_permintaan);
        if (!$d || $d->format('Y-m-d') !== $tanggal_permintaan || $tanggal_permintaan < date('Y-m-d')) {
            $this->session->set_flashdata('error', 'Tanggal permintaan tidak valid. Pilih hari ini atau tanggal ke depan.');
            redirect($back);
            exit;
        }
    }

    $kategori_id = $this->User_ticket_model->get_kategori_id_by_slug('permintaan');
    $sub = $this->User_ticket_model->ensure_pengajuan_persediaan_sub();
    if (!$kategori_id || !$sub) {
        $this->session->set_flashdata('error', 'Kategori pengajuan barang belum tersedia.');
        redirect($back);
        exit;
    }

    $lines = [];
    foreach ($items as $it) {
        $ln = '- ' . $it['nama'];
        if (!empty($it['kategori_nama'])) {
            $ln .= ' [Kategori: ' . $it['kategori_nama'] . ']';
        }
        if ($it['ket'] !== '') $ln .= ' (' . $it['ket'] . ')';
        $lines[] = $ln;
    }

    $ringkas   = [];
    $ringkas[] = '[PENGAJUAN BARANG PERSEDIAAN]';
    $ringkas   = array_merge($ringkas, $lines);
    $ringkas[] = '';
    $ringkas[] = 'Keperluan: ' . $keperluan;
    if ($unit_kerja !== '') $ringkas[] = 'Unit Kerja: ' . $unit_kerja;
    if ($tanggal_permintaan !== '') $ringkas[] = 'Tanggal permintaan: ' . $tanggal_permintaan;
    $ringkas[] = 'Total: ' . count($items) . ' jenis diajukan';
    $ringkas[] = 'Catatan: barang belum ada di daftar persediaan, menunggu verifikasi admin/teknisi.';
    $ringkas[] = '[pengajuan_items_json:' . json_encode($items) . ']';

    $this->db->insert('tickets', [
        'user_id'         => $this->session->userdata('user_id'),
        'pelapor'         => $this->session->userdata('name'),
        'kode_tiket'      => 'TKT-' . date('YmdHis'),
        'kategori_id'     => $kategori_id,
        'sub_kategori_id' => $sub->id,
        'jenis_kerusakan' => implode("\n", $ringkas),
        'kebutuhan'       => substr($keperluan, 0, 255),
        'status'          => 'Menunggu',
        'created_at'      => date('Y-m-d H:i:s'),
    ]);

    $this->session->set_flashdata('success', 'Pengajuan ' . count($items) . ' jenis barang berhasil dikirim. Pantau di riwayat tiket.');
    redirect('user_ticket/history');
    exit;
}

    /* ===============================
       HISTORY USER
    =============================== */
public function history()
{
    $limit   = 10;
    $user_id = $this->session->userdata('user_id');

    // 🔥 TAMBAHKAN INI — auto-rate tiket yang sudah 24 jam tanpa rating
    $this->User_ticket_model->auto_rate_expired_tickets($user_id);

    $page   = (int) $this->input->get('page');
    $page   = $page < 1 ? 1 : $page;
    $offset = ($page - 1) * $limit;

        $total_rows  = $this->User_ticket_model->count_history($user_id);
        $total_pages = ceil($total_rows / $limit);

        $data = [
            'tickets'       => $this->User_ticket_model
                                    ->get_history_limit($user_id, $limit, $offset),
            'current_page'  => $page,
            'total_pages'   => $total_pages,
            'base_url'      => base_url('user_ticket/history')
        ];

        $this->load->view('layout/header_user');
        $this->load->view('user/history', $data);
        $this->load->view('layout/footer_user');
    }

    /* ===============================
       RATING
    =============================== */
    public function rating($ticket_id)
    {
        if (!$this->input->post('rating')) {
            redirect('user_ticket/history');
            exit;
        }

        $this->User_ticket_model->update_rating(
            $ticket_id,
            $this->session->userdata('user_id'),
            $this->input->post('rating'),
            $this->input->post('komentar')
        );

        redirect('user_ticket/history');
        exit;
    }

    /* ===============================
       RATING BARANG PERSEDIAAN (per barang_id)
       POST: rating[<barang_id>] = 1-5, ulasan_barang[<barang_id>] opsional, komentar umum opsional.
       Endpoint rating tiket existing tetap untuk non-persediaan.
    =============================== */
    public function rating_barang($ticket_id)
    {
        $ratings = $this->input->post('rating');
        $ratings = is_array($ratings) ? $ratings : [];
        $rating_layanan = $this->input->post('rating_layanan');
        $ulasan_barang  = $this->input->post('ulasan_barang');
        $ulasan_barang  = is_array($ulasan_barang) ? $ulasan_barang : [];

        if (empty($ratings) && ($rating_layanan === null || $rating_layanan === '')) {
            redirect('user_ticket/history');
            exit;
        }
        $this->User_ticket_model->save_rating_barang(
            $ticket_id,
            $this->session->userdata('user_id'),
            $ratings,
            $this->input->post('komentar'),
            $rating_layanan,
            $ulasan_barang
        );
        redirect('user_ticket/history');
        exit;
    }

    /* ===============================
       HALAMAN PENILAIAN & ULASAN BARANG
    =============================== */
    public function ulasan_barang($barang_id)
    {
        $barang_id = (int) $barang_id;
        $this->load->model(['Barang_persediaan_model', 'User_ticket_model']);

        $barang = $this->Barang_persediaan_model->get_by_id($barang_id);
        if (!$barang) {
            $this->session->set_flashdata('error', 'Barang persediaan tidak ditemukan.');
            redirect('user_ticket/permintaan_persediaan');
            exit;
        }

        $bulan = (int) date('n');
        $tahun = (int) date('Y');
        $sisa  = (int) $this->User_ticket_model->get_sisa_persediaan($barang_id, $bulan, $tahun);

        $ulasan_data = $this->User_ticket_model->get_ulasan_barang($barang_id);

        $data = [
            'barang'       => $barang,
            'sisa'         => $sisa,
            'rating_avg'   => $ulasan_data['rating_avg'],
            'rating_count' => $ulasan_data['rating_count'],
            'ulasan_count' => $ulasan_data['ulasan_count'],
            'star_counts'  => $ulasan_data['star_counts'],
            'ulasan_list'  => $ulasan_data['ulasan_list'],
        ];

        $this->load->view('layout/header_user');
        $this->load->view('user/ulasan_barang', $data);
        $this->load->view('layout/footer_user');
    }

public function search_perangkat()
{
    $q = $this->input->get('q');

    if (!$q || strlen($q) < 3) {
        echo json_encode([]);
        return;
    }

    $data = $this->User_ticket_model->search_perangkat($q);

    header('Content-Type: application/json');
    echo json_encode($data);
}

/* ===============================
   EDIT TIKET (USER)
=============================== */
public function edit_ticket($ticket_id)
{
    $user_id = $this->session->userdata('user_id');
    $ticket  = $this->User_ticket_model->get_ticket_for_edit($ticket_id, $user_id);

    if (!$ticket) {
        $this->session->set_flashdata('error', 'Tiket tidak ditemukan atau tidak dapat diedit.');
        redirect('user_ticket/history');
        exit;
    }

    /* ── Persediaan punya form sendiri agar jenis_kerusakan tak tertimpa form generik ── */
    if ($this->_is_persediaan_ticket($ticket)) {
        redirect('user_ticket/edit_persediaan/' . $ticket_id);
        exit;
    }

    $data['ticket']    = $ticket;
    $data['perangkat'] = $this->User_ticket_model->get_perangkat();
    $data['sub_kategori'] = $this->User_ticket_model
                                ->get_sub_kategori($ticket['kategori_id']);

    // Kirim semua master sub_kategori untuk semua kategori
    $data['sub_kategori_gangguan']   = $this->User_ticket_model
        ->get_sub_kategori($this->User_ticket_model->get_kategori_id_by_slug('gangguan'));
    $data['sub_kategori_permintaan'] = $this->User_ticket_model
        ->get_sub_kategori($this->User_ticket_model->get_kategori_id_by_slug('permintaan'));

    $this->load->view('layout/header_user');
    $this->load->view('user/edit_ticket', $data);
    $this->load->view('layout/footer_user');
}

/* ===============================
   UPDATE TIKET (USER)
=============================== */
public function update_ticket($ticket_id)
{
    if (!$this->input->post()) { show_404(); }

    $user_id = $this->session->userdata('user_id');
    $ticket  = $this->User_ticket_model->get_ticket_for_edit($ticket_id, $user_id);

    if (!$ticket) {
        $this->session->set_flashdata('error', 'Tiket tidak ditemukan atau tidak dapat diedit.');
        redirect('user_ticket/history');
        exit;
    }

    $kategori_slug = $ticket['kategori_slug'];
    $update_data   = [];

    /* ── Reset ke Menunggu + lepas teknisi jika sebelumnya On Progress ── */
    if ($ticket['status'] === 'On Progress') {
        $update_data['status']     = 'Menunggu';
        $update_data['teknisi_id'] = null;

        if (!empty($ticket['teknisi_id'])) {
            $this->db
                ->where('id', $ticket['teknisi_id'])
                ->update('teknisi', ['status' => 'available']);
        }
    }

    /* ── GANGGUAN ── */
    if (in_array($kategori_slug, ['gangguan', 'kerusakan'])) {

        $perangkat_ids = json_decode($this->input->post('perangkat_ids'), true);
        $perangkat_id  = (!empty($perangkat_ids) && is_array($perangkat_ids)) ? $perangkat_ids[0] : null;

        $deskripsi = $this->input->post('jenis_kerusakan');
        if (!empty($perangkat_ids) && count($perangkat_ids) > 1) {
            $info = [];
            foreach ($perangkat_ids as $pid) {
                $p = $this->db->select('kode_barang,nup,nama_barang')->where('id',$pid)->get('perangkat')->row_array();
                if ($p) $info[] = "- {$p['kode_barang']} | {$p['nup']} - {$p['nama_barang']}";
            }
            $deskripsi = "[PERANGKAT TERKAIT]\n" . implode("\n", $info) . "\n\n[DESKRIPSI]\n" . $deskripsi;
        }

        $update_data['lokasi']          = $this->input->post('lokasi');
        $update_data['perangkat_id']    = $perangkat_id;
        $update_data['jenis_kerusakan'] = $deskripsi;

    /* ── PERMINTAAN ── */
    } elseif ($kategori_slug === 'permintaan') {

        $sub_kategori_id   = $ticket['sub_kategori_id'];
        $sub_kategori_data = $this->db->get_where('master_sub_kategori', ['id' => $sub_kategori_id])->row();
        $perlu_jadwal      = $sub_kategori_data ? $sub_kategori_data->perlu_jadwal : 0;
        $jenis_layanan     = $this->input->post('jenis_layanan');

        if ($perlu_jadwal == 1) {
            $tanggal_mulai   = $this->input->post('tanggal');
            $tanggal_selesai = $this->input->post('tanggal_selesai') ?: null;
            $jam             = $this->input->post('jam');
            $durasi          = $this->input->post('durasi');

            $jumlah_hari = 1;
            if ($tanggal_selesai) {
                $tM = new DateTime($tanggal_mulai);
                $tS = new DateTime($tanggal_selesai);
                $jumlah_hari = $tS->diff($tM)->days + 1;
            }

            $fmt_mulai = date('d F Y', strtotime($tanggal_mulai));
            $catatan   = ["Detail Permintaan: " . $jenis_layanan];

            if ($jumlah_hari > 1) {
                $fmt_selesai = date('d F Y', strtotime($tanggal_selesai));
                $catatan[]   = "Periode: {$fmt_mulai} s.d. {$fmt_selesai} ({$jumlah_hari} hari)";
            } else {
                $catatan[] = "Tanggal: {$fmt_mulai}";
            }
            $catatan[] = "Jam Mulai (per hari): {$jam} WIB";
            $catatan[] = "Durasi (per hari): {$durasi} jam";

            if ($v = $this->input->post('jumlah_peserta'))        $catatan[] = "Jumlah Peserta: {$v} orang";
            if ($v = $this->input->post('jumlah_breakout'))       $catatan[] = "Jumlah Breakout Room: {$v}";
            if ($v = $this->input->post('penanggung_jawab'))      $catatan[] = "Penanggung Jawab: {$v}";
            if ($v = $this->input->post('host_cohost'))           $catatan[] = "Host/Co-Host: {$v}";
            if ($v = $this->input->post('permintaan_lain_jadwal')) $catatan[] = "Permintaan Lainnya:\n{$v}";

            $update_data['jenis_kerusakan'] = implode("\n", $catatan);
            $update_data['tanggal']         = $tanggal_mulai;
            $update_data['tanggal_selesai'] = $tanggal_selesai;
            $update_data['jam']             = $jam;
            $update_data['durasi']          = $durasi;

        } else {
            $catatan = ["Detail Permintaan: " . $jenis_layanan];
            if ($v = $this->input->post('lokasi_tanpa_jadwal')) {
                $catatan[]                  = "Lokasi/Unit: {$v}";
                $update_data['lokasi']      = $v;
            }
            if ($v = $this->input->post('permintaan_lain_tanpa_jadwal')) {
                $catatan[] = "Catatan Tambahan:\n{$v}";
            }
            $update_data['jenis_kerusakan'] = implode("\n", $catatan);
        }
    }

    /* ── UPLOAD FOTO ── */
    if (!empty($_FILES['foto']['name'])) {
        $config = [
            'upload_path'   => FCPATH . 'uploads/tiket/',
            'allowed_types' => 'jpg|jpeg|png',
            'encrypt_name'  => true,
            'max_size'      => 2048
        ];
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('foto')) {
            if (!empty($ticket['foto'])) {
                $old = FCPATH . 'uploads/tiket/' . $ticket['foto'];
                if (file_exists($old)) unlink($old);
            }
            $update_data['foto'] = $this->upload->data('file_name');
        }
    }

    $updated = $this->User_ticket_model->update_ticket_by_user($ticket_id, $user_id, $update_data);

    $this->session->set_flashdata(
        $updated ? 'success' : 'error',
        $updated ? 'Tiket berhasil diperbarui.' : 'Tidak ada perubahan yang disimpan.'
    );
    redirect('user_ticket/history');
    exit;
}

/* ===============================
   EDIT PERSEDIAAN (USER) — form sendiri
=============================== */
public function edit_persediaan($ticket_id)
{
    $user_id = $this->session->userdata('user_id');
    $ticket  = $this->User_ticket_model->get_ticket_for_edit($ticket_id, $user_id);

    if (!$ticket) {
        $this->session->set_flashdata('error', 'Tiket tidak ditemukan atau tidak dapat diedit.');
        redirect('user_ticket/history');
        exit;
    }

    if (!$this->_is_persediaan_ticket($ticket)) {
        redirect('user_ticket/edit_ticket/' . $ticket_id);
        exit;
    }

    $bulan = (int) date('n');
    $tahun = (int) date('Y');

    $this->load->model('Barang_persediaan_model');

    $data = [
        'ticket'        => $ticket,
        'items'         => $this->User_ticket_model->get_persediaan_items_for_edit($ticket_id, $user_id),
        'barang_list'   => $this->User_ticket_model->get_katalog_persediaan_with_sisa($bulan, $tahun),
        'kategori_list' => $this->Barang_persediaan_model->get_kategori_grouped(),
        'parsed'        => $this->_parse_persediaan_ringkas($ticket['jenis_kerusakan'] ?? '', $ticket['kebutuhan'] ?? ''),
        'bulan'         => $bulan,
        'tahun'         => $tahun,
    ];

    $this->load->view('layout/header_user');
    $this->load->view('user/edit_persediaan', $data);
    $this->load->view('layout/footer_user');
}

/* ===============================
   UPDATE PERSEDIAAN (USER)
=============================== */
public function update_persediaan($ticket_id)
{
    if (!$this->input->post()) { show_404(); }

    $user_id = $this->session->userdata('user_id');
    $ticket  = $this->User_ticket_model->get_ticket_for_edit($ticket_id, $user_id);

    if (!$ticket) {
        $this->session->set_flashdata('error', 'Tiket tidak ditemukan atau tidak dapat diedit.');
        redirect('user_ticket/history');
        exit;
    }

    if (!$this->_is_persediaan_ticket($ticket)) {
        redirect('user_ticket/edit_ticket/' . $ticket_id);
        exit;
    }

    $back = 'user_ticket/edit_persediaan/' . $ticket_id;

    $keperluan          = trim($this->input->post('keperluan', true) ?? '');
    $judul              = trim($this->input->post('judul', true) ?? '');
    $unit_kerja         = trim($this->input->post('unit_kerja', true) ?? '');
    $tanggal_permintaan = trim($this->input->post('tanggal_permintaan', true) ?: ($this->input->post('tanggal_dibutuhkan', true) ?? ''));
    $items_post         = $this->input->post('items');

    if ($judul === '' && $keperluan === '') {
        $this->session->set_flashdata('error', 'Judul/Keperluan wajib diisi.');
        redirect($back);
        exit;
    }
    $judul_final = $judul !== '' ? $judul : $keperluan;

    if ($tanggal_permintaan !== '') {
        $d = DateTime::createFromFormat('Y-m-d', $tanggal_permintaan);
        if (!$d || $d->format('Y-m-d') !== $tanggal_permintaan || $tanggal_permintaan < date('Y-m-d')) {
            $this->session->set_flashdata('error', 'Tanggal permintaan tidak valid. Pilih hari ini atau tanggal ke depan.');
            redirect($back);
            exit;
        }
    }

    if (empty($items_post) || !is_array($items_post)) {
        $this->session->set_flashdata('error', 'Pilih minimal 1 barang.');
        redirect($back);
        exit;
    }

    $errors   = [];
    $warnings = [];
    $snap     = [];

    foreach ($items_post as $bid => $qty) {
        $qty = (int) $qty;
        $b = $this->_barang_persediaan_aktif((int) $bid);
        if (!$b) { $errors[] = 'Barang ID ' . (int) $bid . ' tidak tersedia.'; continue; }
        if ($qty < 1) { $errors[] = $b['nama_barang'] . ': jumlah tidak valid.'; continue; }
        $sisa = (int) $this->User_ticket_model->get_sisa_persediaan((int) $bid, (int) date('n'), (int) date('Y'));
        if ($qty > $sisa) $warnings[] = $b['nama_barang'] . ': minta ' . $qty . ', sisa ' . $sisa;
        $snap[] = [
            'barang_id' => (int) $bid,
            'jumlah'    => $qty,
            'kode'      => $b['kode_barang'] ?? '-',
            'nama'      => $b['nama_barang'],
            'satuan'    => $b['satuan'] ?? 'pcs',
        ];
    }

    if (!empty($errors)) {
        $this->session->set_flashdata('error', implode(' ', $errors));
        redirect($back);
        exit;
    }

    if (empty($snap)) {
        $this->session->set_flashdata('error', 'Pilih minimal 1 barang.');
        redirect($back);
        exit;
    }

    $ticket_data = ['kebutuhan' => substr($judul_final, 0, 255)];

    /* ── Reset ke Menunggu + lepas teknisi jika sebelumnya On Progress ── */
    if ($ticket['status'] === 'On Progress') {
        $ticket_data['status']     = 'Menunggu';
        $ticket_data['teknisi_id'] = null;

        if (!empty($ticket['teknisi_id'])) {
            $this->db
                ->where('id', $ticket['teknisi_id'])
                ->update('teknisi', ['status' => 'available']);
        }
    }

    /* ── FOTO opsional (pola update_ticket) ── */
    if (!empty($_FILES['foto']['name'])) {
        $config = [
            'upload_path'   => FCPATH . 'uploads/tiket/',
            'allowed_types' => 'jpg|jpeg|png',
            'encrypt_name'  => true,
            'max_size'      => 2048
        ];
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('foto')) {
            if (!empty($ticket['foto'])) {
                $old = FCPATH . 'uploads/tiket/' . $ticket['foto'];
                if (file_exists($old)) unlink($old);
            }
            $ticket_data['foto'] = $this->upload->data('file_name');
        }
    }

    $ok = $this->User_ticket_model->update_persediaan_by_user(
        $ticket_id, $user_id, $ticket_data, $snap,
        ['keperluan' => $keperluan, 'unit_kerja' => $unit_kerja, 'tanggal_permintaan' => $tanggal_permintaan, 'tanggal_dibutuhkan' => $tanggal_permintaan]
    );

    if (!$ok) {
        $this->session->set_flashdata('error', 'Gagal menyimpan perubahan.');
        redirect($back);
        exit;
    }

    $msg = 'Tiket berhasil diperbarui (' . count($snap) . ' jenis barang).';
    if (!empty($warnings)) $msg .= ' Peringatan stok: ' . implode('; ', $warnings) . '.';
    $this->session->set_flashdata('success', $msg);
    redirect('user_ticket/history');
    exit;
}

/* ── Tiket persediaan? sub slug persediaan ATAU punya items ── */
private function _is_persediaan_ticket($ticket)
{
    if (!is_array($ticket) || empty($ticket['id'])) return false;

    if (!empty($ticket['sub_kategori_id'])) {
        $row = $this->db
            ->select('slug')
            ->where('id', (int) $ticket['sub_kategori_id'])
            ->get('master_sub_kategori')
            ->row();
        $slug = $row ? $row->slug : null;
        if (in_array($slug, ['permintaan-persediaan', 'permintaan-barang-persediaan'], true)) return true;
    }

    if ($this->db->table_exists('ticket_persediaan_items')) {
        return $this->db
            ->where('ticket_id', (int) $ticket['id'])
            ->count_all_results('ticket_persediaan_items') > 0;
    }

    return strpos($ticket['jenis_kerusakan'] ?? '', '[PERMINTAAN BARANG PERSEDIAAN]') !== false;
}

/* ── Parse ringkasan checkout → field form edit ── */
private function _parse_persediaan_ringkas($jk, $kebutuhan)
{
    $out = [
        'judul'              => trim((string) $kebutuhan),
        'keperluan'          => '',
        'unit_kerja'         => '',
        'tanggal_permintaan' => '',
        'tanggal_dibutuhkan' => '',
        'deskripsi'          => '',
    ];

    foreach (preg_split("/\r\n|\n/", (string) $jk) as $ln) {
        if (preg_match('/^Judul:\s*(.*)$/', $ln, $m)) $out['judul'] = trim($m[1]);
        elseif (preg_match('/^Keperluan:\s*(.*)$/', $ln, $m)) $out['keperluan'] = trim($m[1]);
        elseif (preg_match('/^Unit Kerja:\s*(.*)$/', $ln, $m)) $out['unit_kerja'] = trim($m[1]);
        elseif (preg_match('/^Tanggal (?:permintaan|dibutuhkan):\s*(.*)$/i', $ln, $m)) {
            $out['tanggal_permintaan'] = trim($m[1]);
            $out['tanggal_dibutuhkan'] = trim($m[1]);
        }
        elseif (preg_match('/^Deskripsi:\s*(.*)$/', $ln, $m)) $out['deskripsi'] = trim($m[1]);
    }

    if ($out['keperluan'] === '') $out['keperluan'] = $out['judul'];
    if ($out['judul'] === '') $out['judul'] = $out['keperluan'];

    return $out;
}
public function batalkan($ticket_id)
{
    $user_id = $this->session->userdata('user_id');
    $ticket  = $this->db
        ->where('id', $ticket_id)
        ->where('user_id', $user_id)
        ->where_in('status', ['Menunggu', 'On Progress'])
        ->get('tickets')
        ->row_array();

    if (!$ticket) {
        $this->session->set_flashdata('error', 'Tiket tidak dapat dibatalkan.');
        redirect('user_ticket/history');
        exit;
    }

    $this->db->where('id', $ticket_id)->where('user_id', $user_id)
             ->update('tickets', ['status' => 'Dibatalkan']);

    // Persediaan: hapus items agar tak orphan (has_items jadi false).
    if ($this->db->table_exists('ticket_persediaan_items')
        && strpos($ticket['jenis_kerusakan'] ?? '', '[PERMINTAAN BARANG PERSEDIAAN]') !== false) {
        $this->db->where('ticket_id', (int) $ticket_id)->delete('ticket_persediaan_items');
    }

    // ✅ SYNC ke tabel peminjaman terkait
    $jk = $ticket['jenis_kerusakan'] ?? '';

    if (preg_match('/\[ref:peminjaman_id=(\d+)\]/', $jk, $m)) {
        $this->db->where('id', (int)$m[1])
                 ->update('peminjaman_ruang_rapat', [
                     'status' => 'Dibatalkan',
                     'selesai' => 'Ya',
                 ]);
    }

    if (preg_match('/\[ref:peminjaman_kendaraan_id=(\d+)\]/', $jk, $m)) {
        $this->db->where('id', (int)$m[1])
                 ->update('peminjaman_kendaraan', [
                     'status' => 'Dibatalkan',
                 ]);
    }

    if (preg_match('/\[ref:peminjaman_bmn_id=(\d+)\]/', $jk, $m)) {
        $this->db->where('id', (int)$m[1])
                 ->update('peminjaman_bmn', [
                     'status' => 'Dibatalkan',
                     'selesai' => 'Ya',
                 ]);
    }

    $this->session->set_flashdata('success', 'Tiket berhasil dibatalkan.');
    redirect('user_ticket/history');
    exit;
}

 
/* ===============================
   HAPUS DOKUMEN PESERTA ZOOM
=============================== */
public function hapus_dokumen($ticket_id)
{
    $user_id = $this->session->userdata('user_id');

    $ticket = $this->db
        ->select('t.id, t.dokumen_peserta, t.jenis_kerusakan, mk.slug as kategori_slug')
        ->from('tickets t')
        ->join('master_kategori mk', 'mk.id = t.kategori_id', 'left')
        ->where('t.id',      $ticket_id)
        ->where('t.user_id', $user_id)
        ->get()
        ->row_array();

    if (!$ticket || empty($ticket['dokumen_peserta'])) {
        $this->session->set_flashdata('error', 'Dokumen tidak ditemukan.');
        redirect('user_ticket/history');
        exit;
    }

    $path = FCPATH . 'uploads/dokumen_peserta/' . $ticket['dokumen_peserta'];
    if (file_exists($path)) @unlink($path);

    // Hapus dari tickets
    $this->db->where('id', $ticket_id)
             ->where('user_id', $user_id)
             ->update('tickets', ['dokumen_peserta' => null]);

    // ★ Sinkron ke peminjaman_ruang_rapat
    if (preg_match('/\[ref:peminjaman_id=(\d+)\]/', $ticket['jenis_kerusakan'], $m)) {
        $this->db->where('id', (int)$m[1])
                 ->update('peminjaman_ruang_rapat', [
                     'dokumen_peserta' => null,
                     'updated_at'      => date('Y-m-d H:i:s'),
                 ]);
    }

    $this->session->set_flashdata('success', 'Dokumen peserta berhasil dihapus.');
    redirect('user_ticket/history');
    exit;
}
/* ===============================
   GET RUANGAN AKTIF (AJAX untuk form permintaan)
=============================== */
public function get_ruangan_aktif()
{
    $tanggal     = $this->input->get('tanggal');
    $jam_mulai   = $this->input->get('jam_mulai');
    $jam_selesai = $this->input->get('jam_selesai');

    $this->load->model('Ruang_rapat_model');
    $ruangan = $this->Ruang_rapat_model->get_aktif();

    foreach ($ruangan as &$r) {
        $r['tersedia']        = true;
        $r['jadwal_terpakai'] = [];

        if ($tanggal) {
            // Info jadwal (semua status, untuk ditampilkan)
            $jadwal = $this->db
                ->select('jam_mulai, jam_selesai, keperluan, status')
                ->where('ruangan_id', $r['id'])
                ->where('tanggal', $tanggal)
                ->where_in('status', ['Menunggu', 'Disetujui'])
                ->order_by('jam_mulai', 'ASC')
                ->get('peminjaman_ruang_rapat')
                ->result_array();

            $r['jadwal_terpakai'] = array_map(function ($j) {
                return [
                    'jam_mulai'   => substr($j['jam_mulai'], 0, 5),
                    'jam_selesai' => substr($j['jam_selesai'], 0, 5),
                    'keperluan'   => $j['keperluan'],
                    'status'      => $j['status'],
                ];
            }, $jadwal);

            // Cek konflik HANYA untuk yg sudah dikonfirmasi teknisi (Disetujui)
            if ($jam_mulai && $jam_selesai) {
               $conflict = $this->db
    ->where('ruangan_id', $r['id'])
    ->where('tanggal', $tanggal)
    ->where_in('status', ['Menunggu', 'Disetujui']) // ← Menunggu juga ngeblok
    ->group_start()
        ->where('jam_mulai <', $jam_selesai)
        ->where('jam_selesai >', $jam_mulai)
    ->group_end()
    ->count_all_results('peminjaman_ruang_rapat');

$r['tersedia'] = ($conflict === 0);
            }
        }
    }
    unset($r);

    header('Content-Type: application/json');
    echo json_encode($ruangan);
    exit;
}
public function create_gangguan_umum()
{
    // Redirect ke form gabungan
    redirect('user_ticket/create_gangguan');
    exit;
}
public function get_kendaraan_tersedia()
{
    $tgl_pinjam  = $this->input->get('tgl_pinjam');
    $tgl_kembali = $this->input->get('tgl_kembali') ?: $tgl_pinjam;
    $jam_pinjam  = $this->input->get('jam_pinjam')  ?: null;
    $jam_kembali = $this->input->get('jam_kembali') ?: null;

    $this->load->model('Kendaraan_model');
    $kendaraan = $this->Kendaraan_model->get_all();

    foreach ($kendaraan as &$k) {
        
        // Untuk per_jam (1 hari sama), cek overlap jam
        if ($jam_pinjam && $jam_kembali && $tgl_pinjam === $tgl_kembali) {
            $konflik = $this->db
                ->where('kendaraan_id', $k['id'])
                ->where_in('status', ['Menunggu', 'Disetujui'])
                ->where('tanggal_pinjam <=', $tgl_kembali)
                ->where('tanggal_kembali >=', $tgl_pinjam)
                ->group_start()
                    // Konflik harian (bukan per_jam) selalu block
                    ->where('tipe_pinjam !=', 'per_jam')
                    ->or_group_start()
                        // Konflik per_jam: cek overlap jam
                        ->where('tipe_pinjam', 'per_jam')
                        ->where('jam_pinjam <', $jam_kembali)
                        ->where('jam_kembali >', $jam_pinjam)
                    ->group_end()
                ->group_end()
                ->count_all_results('peminjaman_kendaraan');
        } else {
            // Harian: cek overlap tanggal saja
            $konflik = $this->db
                ->where('kendaraan_id', $k['id'])
                ->where_in('status', ['Menunggu', 'Disetujui'])
                ->where('tanggal_pinjam <=', $tgl_kembali)
                ->where('tanggal_kembali >=', $tgl_pinjam)
                ->count_all_results('peminjaman_kendaraan');
        }

        $k['tersedia'] = ($konflik === 0);
    }
    unset($k);

    header('Content-Type: application/json');
    echo json_encode($kendaraan);
}

public function get_bmn_tersedia()
{
    $tgl_pinjam  = $this->input->get('tgl_pinjam');
    $tgl_kembali = $this->input->get('tgl_kembali') ?: $tgl_pinjam;

    $this->load->model('Master_bmn_model');
    $bmn = $this->Master_bmn_model->get_aktif();

    foreach ($bmn as &$b) {
        $konflik = $this->db
            ->where('bmn_id', $b['id'])
            ->where_in('status', ['Menunggu', 'Disetujui'])
            ->where('tanggal_pinjam <=', $tgl_kembali)
            ->where('tanggal_kembali >=', $tgl_pinjam)
            ->count_all_results('peminjaman_bmn');

        // HAPUS syarat $b['status'] === 'Tersedia'
        $b['tersedia'] = ($konflik === 0);
    }
    unset($b);

    header('Content-Type: application/json');
    echo json_encode($bmn);
}
public function upload_dokumen($ticket_id)
{
    $user_id = $this->session->userdata('user_id');

    $ticket = $this->db
        ->select('t.id, t.jenis_kerusakan, msk.perlu_jadwal, mk.slug as kategori_slug')
        ->from('tickets t')
        ->join('master_kategori mk',      'mk.id = t.kategori_id',      'left')
        ->join('master_sub_kategori msk', 'msk.id = t.sub_kategori_id', 'left')
        ->where('t.id',      $ticket_id)
        ->where('t.user_id', $user_id)
        ->get()
        ->row_array();

    // Khusus ruang rapat (ada ref:peminjaman_id)
    if (!$ticket || strpos($ticket['jenis_kerusakan'] ?? '', '[ref:peminjaman_id=') === false) {
        $this->session->set_flashdata('error', 'Tiket tidak valid untuk unggah dokumen.');
        redirect('user_ticket/history');
        exit;
    }

    // Ambil peminjaman_id
    preg_match('/\[ref:peminjaman_id=(\d+)\]/', $ticket['jenis_kerusakan'], $m);
    $peminjaman_id = (int)($m[1] ?? 0);

    if (!$peminjaman_id) {
        $this->session->set_flashdata('error', 'Referensi peminjaman tidak ditemukan.');
        redirect('user_ticket/history');
        exit;
    }

    // Validasi jenis dokumen
    $jenis_valid = ['Undangan', 'Notulensi', 'Dokumentasi', 'Daftar Hadir', 'Lampiran Lain'];
    $jenis = $this->input->post('jenis_dokumen');
    if (!in_array($jenis, $jenis_valid)) {
        $this->session->set_flashdata('error', 'Jenis dokumen tidak valid.');
        redirect('user_ticket/history');
        exit;
    }

    $upload_path = FCPATH . 'uploads/dokumen_peserta/';
    if (!is_dir($upload_path)) mkdir($upload_path, 0755, true);

    $config = [
        'upload_path'   => $upload_path,
        'allowed_types' => 'pdf',
        'encrypt_name'  => true,
        'max_size'      => 2048,
    ];

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('dokumen_peserta')) {
        $this->session->set_flashdata('error', 'Gagal mengunggah: ' . $this->upload->display_errors('', ''));
        redirect('user_ticket/history');
        exit;
    }

    $file_name  = $this->upload->data('file_name');
    $nama_asli  = $this->upload->data('orig_name');

    // Cek apakah jenis ini sudah ada — kalau ada, hapus yang lama dulu
    $existing = $this->db
        ->where('peminjaman_id', $peminjaman_id)
        ->where('jenis_dokumen', $jenis)
        ->get('dokumen_peminjaman_rapat')
        ->row_array();

    if ($existing) {
        $old_path = $upload_path . $existing['nama_file'];
        if (file_exists($old_path)) @unlink($old_path);
        $this->db->where('id', $existing['id'])->delete('dokumen_peminjaman_rapat');
    }

    // Insert dokumen baru
    $this->db->insert('dokumen_peminjaman_rapat', [
        'peminjaman_id' => $peminjaman_id,
        'ticket_id'     => $ticket_id,
        'jenis_dokumen' => $jenis,
        'nama_file'     => $file_name,
        'nama_asli'     => $nama_asli,
        'created_at'    => date('Y-m-d H:i:s'),
    ]);

    // Update kolom dokumen_peserta di tickets (untuk backward compat, simpan yang terakhir)
    $this->db->where('id', $ticket_id)->where('user_id', $user_id)
             ->update('tickets', ['dokumen_peserta' => $file_name]);

    $this->session->set_flashdata('success', 'Dokumen "' . $jenis . '" berhasil diunggah.');
    redirect('user_ticket/history');
    exit;
}

public function hapus_dokumen_item($doc_id)
{
    $user_id = $this->session->userdata('user_id');

    // Ambil dokumen + verifikasi ownership via ticket
    $doc = $this->db
        ->select('d.*, t.user_id')
        ->from('dokumen_peminjaman_rapat d')
        ->join('tickets t', 't.id = d.ticket_id', 'left')
        ->where('d.id', $doc_id)
        ->where('t.user_id', $user_id)
        ->get()
        ->row_array();

    if (!$doc) {
        $this->session->set_flashdata('error', 'Dokumen tidak ditemukan.');
        redirect('user_ticket/history');
        exit;
    }

    $path = FCPATH . 'uploads/dokumen_peserta/' . $doc['nama_file'];
    if (file_exists($path)) @unlink($path);

    $this->db->where('id', $doc_id)->delete('dokumen_peminjaman_rapat');

    $this->session->set_flashdata('success', 'Dokumen berhasil dihapus.');
    redirect('user_ticket/history');
    exit;
}

// Helper untuk ambil semua dokumen tiket ruang rapat
public function get_dokumen_rapat($peminjaman_id)
{
    return $this->db
        ->where('peminjaman_id', $peminjaman_id)
        ->order_by('created_at', 'ASC')
        ->get('dokumen_peminjaman_rapat')
        ->result_array();
}
}
