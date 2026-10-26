<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teknisi_panel extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }

        if ($this->session->userdata('role') !== 'teknisi') {
            show_error('Akses ditolak', 403);
        }

        $this->load->model('Teknisi_model');
    }

    /* ===============================
       DASHBOARD INDEX
    =============================== */
    public function index()
    {
        $user_id = $this->session->userdata('user_id');

        $data['profil']            = $this->Teknisi_model->get_by_user($user_id);
        $data['tickets_my']        = $this->Teknisi_model->get_ticket_teknisi($user_id);
        $data['tickets_available'] = $this->Teknisi_model->get_ticket_available();
        $data['tickets_assigned']  = $this->Teknisi_model->get_ticket_assigned($user_id);
        $data['maxDisplay']        = 5;

        $this->load->view('layout/header');
        $this->load->view('layout/sidebar_teknisi');
        $this->load->view('teknisi_panel/index', $data);
        $this->load->view('layout/footer');
    }

    /* ===============================
       AJAX: LIVE DATA DASHBOARD
    =============================== */
    public function get_live_data()
    {
        header('Content-Type: application/json');

        $user_id = $this->session->userdata('user_id');

        $profil            = $this->Teknisi_model->get_by_user($user_id);
        $tickets_my        = $this->Teknisi_model->get_ticket_teknisi($user_id);
        $tickets_available = $this->Teknisi_model->get_ticket_available();
        $tickets_assigned  = $this->Teknisi_model->get_ticket_assigned($user_id);

        $total_tiket   = count($tickets_my);
        $belum_diambil = count($tickets_available);
        $on_progress   = count(array_filter($tickets_my, fn($t) => $t['status'] === 'On Progress'));
        $selesai       = count(array_filter($tickets_my, fn($t) => $t['status'] === 'Selesai'));

        $data = [
            'stats' => [
                'total_tiket'   => $total_tiket,
                'belum_diambil' => $belum_diambil,
                'on_progress'   => $on_progress,
                'selesai'       => $selesai,
            ],
            'profil' => $profil ? [
                'name'           => $profil['name'],
                'keahlian'       => $profil['keahlian'],
                'status'         => $profil['status'],
                'alasan_offline' => $profil['alasan_offline'] ?? null,
            ] : null,
            'tickets_available' => array_map(fn($t) => [
                'id'         => $t['id'],
                'kode_tiket' => $t['kode_tiket'],
                'pelapor'    => $t['pelapor'],
                'lokasi'     => $t['lokasi'],
            ], $tickets_available),
            'tickets_assigned' => array_map(fn($t) => [
                'id'         => $t['id'],
                'kode_tiket' => $t['kode_tiket'],
                'pelapor'    => $t['pelapor'],
                'lokasi'     => $t['lokasi'],
            ], $tickets_assigned),
            'tickets_on_progress' => array_map(fn($t) => [
                'id'              => $t['id'],
                'kode_tiket'      => $t['kode_tiket'],
                'pelapor'         => $t['pelapor'],
                'lokasi'          => $t['lokasi'],
                'status'          => $t['status'],
                'nama_kategori'   => $t['nama_kategori'] ?? '',
                'layanan_link'    => $t['layanan_link']    ?? null,
                'layanan_catatan' => $t['layanan_catatan'] ?? null,
                'layanan_file'    => $t['layanan_file']    ?? null,
            ], array_filter($tickets_my, fn($t) => $t['status'] === 'On Progress')),
            'tickets_selesai' => array_map(fn($t) => [
                'id'         => $t['id'],
                'kode_tiket' => $t['kode_tiket'],
                'pelapor'    => $t['pelapor'],
                'lokasi'     => $t['lokasi'],
            ], array_slice(array_filter($tickets_my, fn($t) => $t['status'] === 'Selesai'), 0, 5)),
            'last_update' => date('H:i:s'),
            'timestamp'   => time(),
            'status'      => 'success',
        ];

        echo json_encode($data);
    }

    /* ===============================
       UPDATE STATUS TEKNISI
    =============================== */
    public function update_status()
    {
        if (!$this->input->is_ajax_request()) show_404();

        $status = strtolower($this->input->post('status'));
        $alasan = $this->input->post('alasan_offline');

        if (!in_array($status, ['available', 'busy', 'offline'])) {
            echo json_encode(['status' => 'error', 'message' => 'Status tidak valid']);
            return;
        }

        if ($status === 'offline' && empty($alasan)) {
            echo json_encode(['status' => 'error', 'message' => 'Alasan harus diisi saat mengubah status menjadi Tidak Tersedia']);
            return;
        }

        $data_update = ['status' => $status];
        if ($status === 'offline') {
            $data_update['alasan_offline'] = $alasan;
        }

        $this->Teknisi_model->update_by_user($this->session->userdata('user_id'), $data_update);

        echo json_encode(['status' => 'success', 'message' => 'Status teknisi berhasil diperbarui']);
    }

    /* ===============================
       AMBIL TIKET
    =============================== */
public function take_ticket($ticket_id)
{
    if (!$this->input->is_ajax_request()) show_404();

    $user_id = $this->session->userdata('user_id');
    $teknisi = $this->Teknisi_model->get_by_user($user_id);

    // ── Admin yang switch role ke teknisi ──
    if (!$teknisi) {
        $original_role = $this->session->userdata('original_role');
        if (!in_array($original_role, ['admin', 'superadmin'])) {
            echo json_encode(['status' => 'error', 'message' => 'Data teknisi tidak ditemukan']);
            return;
        }

        $ticket = $this->db
            ->where('id', $ticket_id)
            ->where('status', 'Menunggu')
            ->group_start()
                ->where('teknisi_id IS NULL', null, false)
                ->or_where('teknisi_id IN (SELECT id FROM teknisi WHERE user_id = ' . (int) $user_id . ')', null, false)
            ->group_end()
            ->get('tickets')
            ->row_array();

        if (!$ticket) {
            echo json_encode(['status' => 'error', 'message' => 'Tiket sudah diambil teknisi lain']);
            return;
        }

        // INFO stok: non-blocking, lanjut take. deficient hanya warning.
        $deficient = $this->Teknisi_model->check_stok_ticket($ticket_id);
        $stok_warning = !empty($deficient) ? $this->Teknisi_model->stok_message($deficient) : null;

        $this->db->insert('teknisi', [
            'user_id'  => $user_id,
            'keahlian' => 'Admin',
            'status'   => 'busy',
        ]);
        $teknisi_id = $this->db->insert_id();

 $this->load->model('Ticket_model');
        $this->Ticket_model->update($ticket_id, [
            'teknisi_id' => $teknisi_id,
            'status'     => 'On Progress',
        ]);

        $this->_sync_bmn_status($ticket_id, 'Disetujui');
        $this->_sync_peminjaman_status($ticket_id, 'Disetujui');
        $this->_sync_kendaraan_status($ticket_id, 'Disetujui');

        // Selalu success; warning stok sebagai info.
        $resp = ['status' => 'success', 'message' => 'Tiket berhasil diambil'];
        if ($stok_warning) {
            $resp['warning'] = $stok_warning;
            $resp['deficient_items'] = $deficient;
        }
        echo json_encode($resp);
        return;
    }

    // ── Alur normal teknisi ──
    if ($teknisi['status'] !== 'available') {
        echo json_encode(['status' => 'error', 'message' => 'Anda harus mengubah status menjadi "Tersedia" untuk mengambil tiket']);
        return;
    }

    // INFO stok: non-blocking, lanjut take. deficient hanya warning.
    $info_deficient = $this->Teknisi_model->check_stok_ticket($ticket_id);
    $info_warning = !empty($info_deficient) ? $this->Teknisi_model->stok_message($info_deficient) : null;

    $result = $this->Teknisi_model->take_ticket($ticket_id, $user_id);

    // Model baru return array ok+warning; model lama return bool true.
    $warning = $info_warning;
    $warn_items = $info_deficient;
    if (is_array($result)) {
        if (empty($result['ok'])) {
            echo json_encode(['status' => 'error', 'message' => 'Tiket sudah diambil teknisi lain']);
            return;
        }
        if (!empty($result['warning'])) { $warning = $result['warning']; $warn_items = $result['deficient'] ?? $warn_items; }
        $result = true;
    }

    if ($result) {
        $this->_sync_bmn_status($ticket_id, 'Disetujui');
        $this->_sync_peminjaman_status($ticket_id, 'Disetujui');
        $this->_sync_kendaraan_status($ticket_id, 'Disetujui');
    }

    // Selalu lanjut take; warning stok sebagai info.
    if ($result) {
        $resp = ['status' => 'success', 'message' => 'Tiket berhasil diambil'];
        if ($warning) { $resp['warning'] = $warning; $resp['deficient_items'] = $warn_items; }
        echo json_encode($resp);
        return;
    }
    echo json_encode(['status' => 'error', 'message' => 'Tiket sudah diambil teknisi lain']);
}

/* ===============================
   ACC JUMLAH PERSEDIAAN (AJAX POST items[barang_id]=disetujui)
   Guard milik + status Menunggu/On Progress, panggil save_acc_ticket.
   Admin-switch ikut path sama (resolve teknisi_id milik tiket/user).
=============================== */
public function acc_persediaan($ticket_id)
{
    header('Content-Type: application/json');
    if (!$this->input->is_ajax_request()) { echo json_encode(['status' => 'error', 'message' => 'Akses tidak diizinkan']); return; }

    $ticket_id = (int) $ticket_id;
    $user_id = $this->session->userdata('user_id');
    $teknisi = $this->Teknisi_model->get_by_user($user_id);
    $teknisi_id = $teknisi ? (int) $teknisi['id'] : 0;

    $ticket = $this->db->where('id', $ticket_id)->get('tickets')->row_array();
    if (!$ticket) { echo json_encode(['status' => 'error', 'message' => 'Tiket tidak ditemukan']); return; }

    // Admin-switch: tanpa row teknisi, pakai teknisi_id milik tiket.
    // Tiket unassigned (owner 0) + status Menunggu: izinkan teknisi_id 0 lanjut.
    $owner = (int) ($ticket['teknisi_id'] ?? 0);
    if (!$teknisi_id) {
        if (!in_array($this->session->userdata('original_role'), ['admin', 'superadmin'])) {
            echo json_encode(['status' => 'error', 'message' => 'Data teknisi tidak ditemukan']); return;
        }
        if ($owner === 0 && ($ticket['status'] ?? '') === 'Menunggu') {
            $teknisi_id = 0;
        } else {
            $teknisi_id = $owner;
        }
    }
    if ($owner !== 0 && $owner !== $teknisi_id) {
        echo json_encode(['status' => 'error', 'message' => 'Tiket ini bukan milik Anda']); return;
    }
    if (!in_array($ticket['status'] ?? '', ['Menunggu', 'On Progress'])) {
        echo json_encode(['status' => 'error', 'message' => 'Tiket sudah diproses']); return;
    }

    $items = $this->input->post('items');
    $keterangan = $this->input->post('keterangan');
    if (!is_array($items) || empty($items)) { echo json_encode(['status' => 'error', 'message' => 'Items kosong']); return; }
    if (!is_array($keterangan)) $keterangan = [];

    $this->load->model('Transaksi_persediaan_model');
    $res = $this->Transaksi_persediaan_model->save_acc_ticket($ticket_id, $teknisi_id, $items, $keterangan);
    echo json_encode($res['ok'] ? ['status' => 'success', 'message' => $res['msg']] : ['status' => 'error', 'message' => $res['msg']]);
}

/* ===============================
   SELESAIKAN TIKET
=============================== */
public function selesai($id)
{
    $user_id = $this->session->userdata('user_id');
    $teknisi = $this->Teknisi_model->get_by_user($user_id);

    if (!$teknisi) {
        $this->session->set_flashdata('error', 'Data teknisi tidak ditemukan.');
        redirect('teknisi_panel');
        exit;
    }

    $teknisi_id = $teknisi['id'];

    $ticket = $this->db
        ->where('id', $id)
        ->where('teknisi_id', $teknisi_id)
        ->where('status', 'On Progress')
        ->get('tickets')
        ->row_array();

    if (!$ticket) {
        $this->session->set_flashdata('error', 'Tiket tidak ditemukan.');
        redirect('teknisi_panel');
        exit;
    }

    // Jalur persediaan: stock-out atomik via model, email via Ticket_model->update.
    $this->load->model('Transaksi_persediaan_model');
    if ($this->Transaksi_persediaan_model->has_items((int) $id)) {
        $res = $this->Transaksi_persediaan_model->selesaikan_persediaan((int) $id);
        $this->session->set_flashdata($res['ok'] ? 'success' : 'error', $res['msg']);
        redirect('teknisi_panel');
        exit;
    }

   $this->db->where('id', $id)->update('tickets', [
        'status'     => 'Selesai',
        'selesai_at' => date('Y-m-d H:i:s'),
    ]);

    $this->load->model('Barang_persediaan_model');
    $this->Barang_persediaan_model->proses_pengajuan_persediaan_selesai($id);

    $this->db->where('id', $teknisi_id)->update('teknisi', [
        'status' => 'available'
    ]);

    $this->_sync_bmn_status($id, 'Selesai');
    $this->_sync_peminjaman_status($id, 'Selesai');
    $this->_sync_kendaraan_status($id, 'Selesai');

    $this->session->set_flashdata('success', 'Tiket berhasil diselesaikan.');
    redirect('teknisi_panel');
    exit;
}


    /* ===============================
       SET LAYANAN ZOOM (SIMPAN PERTAMA KALI)
       Hanya untuk tiket yang BELUM punya layanan_link
    =============================== */
    public function set_layanan($id)
    {
        $this->load->model('Ticket_model');
        $this->load->model('Zoom_model');

        /* ── Ambil tiket lama ── */
        $ticket = $this->Ticket_model->get_by_id($id);
        if (!$ticket) {
            redirect('teknisi_panel');
            return;
        }

        $akun_zoom       = (int)$this->input->post('akun_zoom');
        $layanan_link    = trim($this->input->post('layanan_link'));
        $layanan_catatan = trim($this->input->post('layanan_catatan'));

        /* ── Validasi akun_zoom ── */
        if (!in_array($akun_zoom, [1, 2])) {
            $this->session->set_flashdata('error_zoom_' . $id, 'Pilih akun Zoom terlebih dahulu');
            redirect('teknisi_panel');
            return;
        }

        /* ── Simpan ke DB ── */
       $update_data = [
    'layanan_link'    => $layanan_link,
    'layanan_catatan' => $layanan_catatan ?: null,
    'akun_zoom'       => $akun_zoom,
    'undangan_link'   => trim($this->input->post('undangan_link')) ?: null, // ← TAMBAH
    'status'          => 'On Progress',
];

        $this->db->where('id', $id)->update('tickets', $update_data);

        /* ── Re-assign zoom groups ── */
        $tanggal = $ticket['tanggal'] ?? date('Y-m-d');
        if ($tanggal) {
            $this->db->where('id', $id)->update('tickets', [
                'zoom_group_id'      => null,
                'zoom_breakout_slot' => null,
            ]);
            $this->Zoom_model->assign_zoom_groups($tanggal);
        }

        /* ── Kirim email via Ticket_model->update ── */
        // Ticket_model->update akan membaca layanan_link yang baru disimpan
        // dan trigger email On Progress + Layanan jika kategori = permintaan
        $this->Ticket_model->update($id, ['status' => 'On Progress']);

        redirect('teknisi_panel');
    }

    /* ===============================
       UPDATE LAYANAN ZOOM (EDIT LINK / AKUN / CATATAN)
       Untuk tiket yang SUDAH punya layanan_link
       Method: AJAX POST
    =============================== */
  public function update_layanan_zoom($id)
{
    header('Content-Type: application/json');

    if (!$this->input->is_ajax_request()) {
        echo json_encode(['status' => 'error', 'message' => 'Akses tidak diizinkan']);
        return;
    }

    $this->load->model('Ticket_model');
    $this->load->model('Zoom_model');

    $ticket = $this->Ticket_model->get_by_id($id);
    if (!$ticket) {
        echo json_encode(['status' => 'error', 'message' => 'Tiket tidak ditemukan']);
        return;
    }

    $akun_zoom       = (int)$this->input->post('akun_zoom');
    $layanan_link    = trim($this->input->post('layanan_link'));
    $layanan_catatan = trim($this->input->post('layanan_catatan'));
    $nama_meeting    = trim($this->input->post('nama_meeting')); // ← BARU

    if (!in_array($akun_zoom, [1, 2])) {
        echo json_encode(['status' => 'error', 'message' => 'Akun Zoom tidak valid']);
        return;
    }

    if (empty($layanan_link)) {
        echo json_encode(['status' => 'error', 'message' => 'Link Zoom tidak boleh kosong']);
        return;
    }

$undangan_link = trim($this->input->post('undangan_link'));  // ← TAMBAH

/* ── Simpan perubahan link/akun/catatan ── */
$this->db->where('id', $id)->update('tickets', [
    'layanan_link'    => $layanan_link,
    'layanan_catatan' => $layanan_catatan ?: null,
    'akun_zoom'       => $akun_zoom,
    'undangan_link'   => $undangan_link ?: null,  // ← TAMBAH
]);
    /* ── Update nama/judul meeting jika diisi ── */  // ← BARU
    if (!empty($nama_meeting)) {
        $this->Zoom_model->update_detail_permintaan($id, $nama_meeting);
    }

    /* ── Re-assign zoom groups ── */
    $tanggal = $ticket['tanggal'] ?? date('Y-m-d');
    if ($tanggal) {
        $this->db->where('id', $id)->update('tickets', [
            'zoom_group_id'      => null,
            'zoom_breakout_slot' => null,
        ]);
        $this->Zoom_model->assign_zoom_groups($tanggal);
    }

    echo json_encode(['status' => 'success', 'message' => 'Detail Zoom berhasil diperbarui']);
}
    /* ===============================
       AJAX: CEK SISA SLOT ZOOM PER HARI
    =============================== */
    public function cek_slot_zoom()
    {
        header('Content-Type: application/json');

        $tanggal = $this->input->get('tanggal');
        if (!$tanggal) {
            echo json_encode(['status' => 'error', 'message' => 'Tanggal tidak ditemukan']);
            return;
        }

        $this->load->model('Zoom_model');
        $pemakaian = $this->Zoom_model->hitung_pemakaian_akun($tanggal);

        echo json_encode([
            'status' => 'success',
            'zoom1'  => [
                'terpakai' => $pemakaian[1],
                'sisa'     => max(0, 5 - $pemakaian[1]),
            ],
            'zoom2'  => [
                'terpakai' => $pemakaian[2],
                'sisa'     => max(0, 5 - $pemakaian[2]),
            ],
        ]);
    }


    /* ===============================
       UPDATE CATATAN & LAMPIRAN (AJAX)
       Khusus tiket non-permintaan
    =============================== */
    public function update_catatan($ticket_id)
    {
        header('Content-Type: application/json');

        $this->load->model('Ticket_model');

        $ticket = $this->Ticket_model->get_by_id($ticket_id);

        if (!$ticket || $ticket['status'] !== 'On Progress') {
            echo json_encode(['status' => 'error', 'message' => 'Tiket tidak valid atau bukan milik Anda']);
            return;
        }

        $catatan = $this->input->post('layanan_catatan');
        $data    = [];

        if (!empty($catatan)) {
            $data['layanan_catatan'] = $catatan;
        }

        if (!empty($_FILES['layanan_file']['name'])) {
            $config = [
                'upload_path'   => './uploads/layanan/',
                'allowed_types' => 'jpg|jpeg|png|pdf|doc|docx',
                'max_size'      => 5120,
                'encrypt_name'  => true,
            ];

            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0755, true);
            }

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('layanan_file')) {
                $upload_data          = $this->upload->data();
                $data['layanan_file'] = $upload_data['file_name'];

                if (!empty($ticket['layanan_file'])) {
                    $old = './uploads/layanan/' . $ticket['layanan_file'];
                    if (file_exists($old)) unlink($old);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal upload file: ' . $this->upload->display_errors('', '')]);
                return;
            }
        }

        if (empty($data)) {
            echo json_encode(['status' => 'error', 'message' => 'Tidak ada data untuk disimpan']);
            return;
        }

        $this->Teknisi_model->update_layanan_catatan($ticket_id, $data);

        $this->load->helper('email');
        $user = $this->db->where('id', $ticket['user_id'])->get('users')->row_array();
        if ($user && !empty($user['email'])) {
            $nama_teknisi = $ticket['nama_teknisi'] ?? $this->session->userdata('name') ?? 'Teknisi';
            $catatan_baru = $data['layanan_catatan'] ?? $ticket['layanan_catatan'] ?? '';
            $file_baru    = $data['layanan_file'] ?? $ticket['layanan_file'] ?? '';
            $subject = "[SIPASTI] {$ticket['kode_tiket']} - Catatan baru dari teknisi";
            $body = "<p>Yth. <strong>" . htmlspecialchars($user['name'] ?? 'Pengguna') . "</strong>,</p>"
                . "<p>Teknisi <strong>" . htmlspecialchars($nama_teknisi) . "</strong> menambahkan catatan pada tiket "
                . "<strong>" . htmlspecialchars($ticket['kode_tiket']) . "</strong>:</p>"
                . "<div>" . nl2br(htmlspecialchars($catatan_baru)) . "</div>"
                . (!empty($file_baru) ? "<p>File: " . htmlspecialchars($file_baru) . "</p>" : "")
                . "<p><a href='" . base_url('user_ticket/history') . "'>Lihat riwayat tiket</a></p>";
            send_ticket_email($user['email'], $subject, $body);
        }

        echo json_encode(['status' => 'success', 'message' => 'Catatan berhasil disimpan']);
    }

    /* ===============================
   GET DATA TIKET UNTUK EDIT MODAL (AJAX)
=============================== */
public function get_ticket_edit($id)
{
    header('Content-Type: application/json');

    $this->load->model('Ticket_model');
    $ticket = $this->Ticket_model->get_by_id($id);

    if (!$ticket) {
        echo json_encode(['status' => 'error', 'message' => 'Tiket tidak ditemukan']);
        return;
    }

    if (!in_array($ticket['status'], ['Menunggu', 'On Progress', 'Selesai'])) {
        echo json_encode(['status' => 'error', 'message' => 'Tiket tidak dapat diedit']);
        return;
    }

    // ← TAMBAHKAN INI: ambil perlu_jadwal & nama_kategori langsung dari DB
    if (!empty($ticket['sub_kategori_id'])) {
        $sub = $this->db
            ->select('perlu_jadwal')
            ->where('id', $ticket['sub_kategori_id'])
            ->get('master_sub_kategori')
            ->row_array();
        $ticket['perlu_jadwal'] = $sub ? (int)$sub['perlu_jadwal'] : 0;
    } else {
        $ticket['perlu_jadwal'] = 0;
    }
// Deteksi apakah ini tiket ruang rapat
$ticket['is_ruang_rapat'] = (
    strpos($ticket['jenis_kerusakan'] ?? '', '[ref:peminjaman_id=') !== false
) ? 1 : 0;

// Ambil data peminjaman jika ruang rapat
if ($ticket['is_ruang_rapat']) {
    if (preg_match('/\[ref:peminjaman_id=(\d+)\]/', $ticket['jenis_kerusakan'], $m)) {
        $peminjaman = $this->db
            ->select('p.*, r.nama_ruangan, r.lokasi, r.kapasitas')
            ->from('peminjaman_ruang_rapat p')
            ->join('master_ruang_rapat r', 'r.id = p.ruangan_id', 'left')
            ->where('p.id', (int)$m[1])
            ->get()->row_array();
        $ticket['peminjaman'] = $peminjaman ?: [];
    }
}
    // Pastikan nama_kategori tersedia (fallback query jika JOIN tidak ada)
// Selalu fetch nama_kategori DAN slug
if (!empty($ticket['kategori_id'])) {
    $kat = $this->db
        ->select('nama_kategori, slug')
        ->where('id', $ticket['kategori_id'])
        ->get('master_kategori')
        ->row_array();
    $ticket['nama_kategori']  = $kat['nama_kategori'] ?? '';
    $ticket['kategori_slug']  = $kat['slug'] ?? '';
}

    echo json_encode(['status' => 'success', 'ticket' => $ticket]);
}

/* ===============================
   UPDATE TIKET OLEH TEKNISI (AJAX)
=============================== */
public function update_ticket($id)
{
    header('Content-Type: application/json');

    $this->load->model('Ticket_model');
    $ticket = $this->Ticket_model->get_by_id($id);

    if (!$ticket || !in_array($ticket['status'], ['Menunggu', 'On Progress', 'Selesai'])) {
        echo json_encode(['status' => 'error', 'message' => 'Tiket tidak dapat diedit']);
        return;
    }

    $update_data   = [];
    $kategori_slug = strtolower($ticket['kategori_slug'] ?? $ticket['nama_kategori'] ?? '');

    if (empty($kategori_slug) && !empty($ticket['kategori_id'])) {
        $kat = $this->db->select('slug, nama_kategori')
            ->where('id', $ticket['kategori_id'])
            ->get('master_kategori')->row_array();
        if ($kat) $kategori_slug = strtolower($kat['slug'] ?? $kat['nama_kategori'] ?? '');
    }

    $perlu_jadwal = 0;
    if (!empty($ticket['sub_kategori_id'])) {
        $sub = $this->db->select('perlu_jadwal')
            ->where('id', $ticket['sub_kategori_id'])
            ->get('master_sub_kategori')->row_array();
        $perlu_jadwal = $sub ? (int)$sub['perlu_jadwal'] : 0;
    }

    $isGangguan   = in_array($kategori_slug, ['gangguan', 'kerusakan']);
    $isPermintaan = $kategori_slug === 'permintaan';

    $isRuangRapat = $isPermintaan &&
        strpos($ticket['jenis_kerusakan'] ?? '', '[ref:peminjaman_id=') !== false;

    $isKendaraan = $isPermintaan &&
        strpos($ticket['jenis_kerusakan'] ?? '', '[ref:peminjaman_kendaraan_id=') !== false;

    $isBmn = $isPermintaan &&
        strpos($ticket['jenis_kerusakan'] ?? '', '[ref:peminjaman_bmn_id=') !== false;

    if ($isGangguan) {

        $lokasi          = trim($this->input->post('lokasi'));
        $jenis_kerusakan = trim($this->input->post('jenis_kerusakan'));
        if ($lokasi)          $update_data['lokasi']          = $lokasi;
        if ($jenis_kerusakan) $update_data['jenis_kerusakan'] = $jenis_kerusakan;

    } elseif ($isKendaraan) {

        $jenis_kerusakan = trim($this->input->post('jenis_kerusakan'));

        if ($jenis_kerusakan) {
            $ref_tag = '';
            if (preg_match('/(\[ref:peminjaman_kendaraan_id=\d+\])/',
                $ticket['jenis_kerusakan'], $ref_m)) {
                $ref_tag = $ref_m[1];
            }
            $bersih = preg_replace('/\[ref:peminjaman_kendaraan_id=\d+\]/', '',
                $jenis_kerusakan);
            $update_data['jenis_kerusakan'] = trim($bersih)
                . ($ref_tag ? "\n" . $ref_tag : '');

            if (preg_match('/\[ref:peminjaman_kendaraan_id=(\d+)\]/',
                $ticket['jenis_kerusakan'], $m)) {
                $this->db->where('id', (int)$m[1])
                         ->update('peminjaman_kendaraan', [
                             'catatan' => trim($bersih),
                         ]);
            }
        }

    } elseif ($isBmn) {

        $jenis_kerusakan = trim($this->input->post('jenis_kerusakan'));

        if ($jenis_kerusakan) {
            $ref_tag = '';
            if (preg_match('/(\[ref:peminjaman_bmn_id=\d+\])/',
                $ticket['jenis_kerusakan'], $ref_m)) {
                $ref_tag = $ref_m[1];
            }
            $bersih = preg_replace('/\[ref:peminjaman_bmn_id=\d+\]/', '',
                $jenis_kerusakan);
            $update_data['jenis_kerusakan'] = trim($bersih)
                . ($ref_tag ? "\n" . $ref_tag : '');

            if (preg_match('/\[ref:peminjaman_bmn_id=(\d+)\]/',
                $ticket['jenis_kerusakan'], $m)) {
                $this->db->where('id', (int)$m[1])
                         ->update('peminjaman_bmn', [
                             'catatan' => trim($bersih),
                         ]);
            }
        }

    } elseif ($isRuangRapat) {

        $peminjaman_id    = $this->_get_peminjaman_id($ticket['jenis_kerusakan']);
        $catatan_admin    = trim($this->input->post('catatan_admin'));
        $ruangan_id       = (int)  $this->input->post('ruangan_id');
        $unit_kerja       = trim(  $this->input->post('unit_kerja'));
        $tanggal_rapat    =        $this->input->post('tanggal_rapat');
        $jam_mulai        =        $this->input->post('jam_mulai');
        $jam_selesai      =        $this->input->post('jam_selesai');
        $keperluan        = trim(  $this->input->post('keperluan'));
        $jumlah_peserta   = (int)  $this->input->post('jumlah_peserta') ?: null;
        $snack_corner     =        $this->input->post('snack_corner') ?: 'Tidak';
        $jumlah_snack     = ($snack_corner === 'Ya')
                            ? (int) $this->input->post('jumlah_snack')
                            : null;
        $catatan_peminjam = trim($this->input->post('catatan_peminjam'));

        if ($peminjaman_id) {
            $pem_data = [];
            if ($ruangan_id)      $pem_data['ruangan_id']       = $ruangan_id;
            if ($unit_kerja)      $pem_data['unit_kerja']        = $unit_kerja;
            if ($tanggal_rapat)   $pem_data['tanggal']           = $tanggal_rapat;
            if ($jam_mulai)       $pem_data['jam_mulai']         = $jam_mulai;
            if ($jam_selesai)     $pem_data['jam_selesai']       = $jam_selesai;
            if ($keperluan)       $pem_data['keperluan']         = $keperluan;
            if ($jumlah_peserta)  $pem_data['jumlah_peserta']    = $jumlah_peserta;
            $pem_data['snack_corner'] = $snack_corner;
            $pem_data['jumlah_snack'] = $jumlah_snack;
            if ($catatan_peminjam !== '') $pem_data['catatan_peminjam'] = $catatan_peminjam;
            if ($catatan_admin !== '')    $pem_data['catatan_admin']    = $catatan_admin;
            if (!empty($pem_data)) {
                $this->db->where('id', $peminjaman_id)
                         ->update('peminjaman_ruang_rapat', $pem_data);
            }
        }

        if ($ruangan_id && $tanggal_rapat && $jam_mulai && $jam_selesai) {
            $ruangan = $this->db->select('nama_ruangan, lokasi, kapasitas')
                ->where('id', $ruangan_id)->get('master_ruang_rapat')->row_array();
            $catatan_arr   = [];
            $catatan_arr[] = 'Ruangan: ' . ($ruangan['nama_ruangan'] ?? '-');
            $catatan_arr[] = 'Lokasi: '  . ($ruangan['lokasi']       ?? '-');
            $catatan_arr[] = 'Tanggal: ' . date('d F Y', strtotime($tanggal_rapat));
            $catatan_arr[] = 'Jam: '     . $jam_mulai . ' s.d. ' . $jam_selesai;
            if ($keperluan)             $catatan_arr[] = 'Keperluan: '      . $keperluan;
            if ($jumlah_peserta)        $catatan_arr[] = 'Jumlah Peserta: ' . $jumlah_peserta . ' orang';
            if ($snack_corner === 'Ya') $catatan_arr[] = 'Snack Corner: Ya (' . $jumlah_snack . ' porsi)';
            if ($catatan_peminjam)      $catatan_arr[] = 'Catatan: '        . $catatan_peminjam;
            if ($catatan_admin)         $catatan_arr[] = 'Catatan Admin: '  . $catatan_admin;
            $catatan_arr[] = '[ref:peminjaman_id=' . $peminjaman_id . ']';
            $update_data['jenis_kerusakan'] = implode("\n", $catatan_arr);
            $update_data['tanggal']         = $tanggal_rapat;
            $update_data['jam']             = $jam_mulai;
        } elseif ($catatan_admin !== '') {
            $jk = preg_replace('/\nCatatan Admin:.*$/s', '', $ticket['jenis_kerusakan']);
            $update_data['jenis_kerusakan'] = $jk . "\nCatatan Admin: " . $catatan_admin;
        }

    } elseif ($isPermintaan || !empty($ticket['sub_kategori_id'])) {

        $jenis_kerusakan = trim($this->input->post('jenis_kerusakan'));
        $tanggal         = $this->input->post('tanggal');
        $tanggal_selesai = $this->input->post('tanggal_selesai');
        $jam             = $this->input->post('jam');
        $durasi          = $this->input->post('durasi');

        if ($jenis_kerusakan) $update_data['jenis_kerusakan'] = $jenis_kerusakan;

        // ponytail: catatan persediaan (perlu_jadwal=0) ikut tersimpan; jadwal/zoom tetap di gate bawah.
        $layanan_cat = trim((string) $this->input->post('layanan_catatan'));
        if (!empty($layanan_cat)) $update_data['layanan_catatan'] = $layanan_cat;

        if ($perlu_jadwal == 1) {
            if ($tanggal) $update_data['tanggal'] = $tanggal;
            if ($jam)     $update_data['jam']     = $jam;
            if ($durasi)  $update_data['durasi']  = $durasi;
            $update_data['tanggal_selesai'] = $tanggal_selesai ?: null;

            $layanan_link  = trim((string) $this->input->post('layanan_link'));
            $undangan_link = $this->input->post('undangan_link');
            $akun_zoom     = (int) $this->input->post('akun_zoom');

            if (!empty($layanan_link))        $update_data['layanan_link']    = $layanan_link;
            if ($undangan_link !== false)      $update_data['undangan_link']   = $undangan_link ?: null;
            if (in_array($akun_zoom, [1, 2])) {
                $update_data['akun_zoom'] = $akun_zoom;
                $this->load->model('Zoom_model');
                $this->db->where('id', $id)->update('tickets', [
                    'zoom_group_id'      => null,
                    'zoom_breakout_slot' => null,
                ]);
                $this->Zoom_model->assign_zoom_groups($tanggal);
            }
        }

    } else {

        $lokasi          = trim($this->input->post('lokasi'));
        $jenis_kerusakan = trim($this->input->post('jenis_kerusakan'));
        if ($lokasi)          $update_data['lokasi']          = $lokasi;
        if ($jenis_kerusakan) $update_data['jenis_kerusakan'] = $jenis_kerusakan;

    }

    if (empty($update_data)) {
        if ($isRuangRapat) {
            echo json_encode(['status' => 'success', 'message' => 'Catatan berhasil disimpan']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Tidak ada data yang diubah']);
        }
        return;
    }

    $this->db->where('id', $id)->update('tickets', $update_data);
    echo json_encode(['status' => 'success', 'message' => 'Tiket berhasil diperbarui']);
}

private function _get_peminjaman_id($jenis_kerusakan)
{
    if (preg_match('/\[ref:peminjaman_id=(\d+)\]/', $jenis_kerusakan, $m)) {
        return (int)$m[1];
    }
    return null;
}

private function _sync_peminjaman_status($ticket_id, $status_peminjaman)
{
    $ticket = $this->db->select('jenis_kerusakan')
        ->where('id', $ticket_id)
        ->get('tickets')->row_array();
    if (!$ticket) return;

    $peminjaman_id = $this->_get_peminjaman_id($ticket['jenis_kerusakan']);
    if (!$peminjaman_id) return;

    $pem_data = ['status' => $status_peminjaman];

    if ($status_peminjaman === 'Selesai') {
        $pem_data['selesai']     = 'Ya';
        $pem_data['updated_at']  = date('Y-m-d H:i:s');
    } elseif ($status_peminjaman === 'Disetujui') {
        $pem_data['updated_at']  = date('Y-m-d H:i:s');
    } elseif ($status_peminjaman === 'Menunggu') {
        $pem_data['selesai']     = 'Belum';
        $pem_data['updated_at']  = date('Y-m-d H:i:s');
    }

    $this->db->where('id', $peminjaman_id)
             ->update('peminjaman_ruang_rapat', $pem_data);
}
/* ===============================
   BATAL AMBIL TIKET (AJAX)
=============================== */
public function cancel_ticket($ticket_id)
{
    if (!$this->input->is_ajax_request()) show_404();

    $user_id = $this->session->userdata('user_id');
    $this->load->model('Ticket_model');

    $teknisi    = $this->Teknisi_model->get_by_user($user_id);
    $teknisi_id = $teknisi ? $teknisi['id'] : null;

    if (!$teknisi_id) {
        $row = $this->db->where('user_id', $user_id)->get('teknisi')->row_array();
        if ($row) $teknisi_id = $row['id'];
    }

    $ticket = $this->Ticket_model->get_by_id($ticket_id);

    if (!$ticket || $ticket['status'] !== 'On Progress') {
        echo json_encode(['status' => 'error', 'message' => 'Tiket tidak dapat dibatalkan']);
        return;
    }

    if ((int)$ticket['teknisi_id'] !== (int)$teknisi_id) {
        echo json_encode(['status' => 'error', 'message' => 'Tiket ini bukan milik Anda']);
        return;
    }

    $this->db->where('id', $ticket_id)->update('tickets', [
        'teknisi_id'         => null,
        'status'             => 'Menunggu',
        'zoom_group_id'      => null,
        'zoom_breakout_slot' => null,
    ]);

    if ($teknisi_id) {
        $this->db->where('id', $teknisi_id)->update('teknisi', ['status' => 'available']);
    }

    // ✅ Sync status BMN ke Menunggu
    $this->_sync_bmn_status($ticket_id, 'Menunggu');
    $this->_sync_kendaraan_status($ticket_id, 'Menunggu');

 // ── Khusus Ruang Rapat: restore peminjaman + konflik ──
$isRuangRapat = strpos($ticket['jenis_kerusakan'] ?? '', '[ref:peminjaman_id=') !== false;

if ($isRuangRapat) {
    if (preg_match('/\[ref:peminjaman_id=(\d+)\]/', $ticket['jenis_kerusakan'], $m)) {
        $peminjaman_id = (int)$m[1];

        $this->db->where('id', $peminjaman_id)->update('peminjaman_ruang_rapat', [
            'status' => 'Menunggu',
        ]);

        $this->_restore_conflicting_peminjaman($peminjaman_id);
    }
}

// ── Khusus BMN: kembalikan status ke Menunggu ──
if (preg_match('/\[ref:peminjaman_bmn_id=(\d+)\]/',
    $ticket['jenis_kerusakan'] ?? '', $m)) {
    $this->db->where('id', (int)$m[1])
             ->update('peminjaman_bmn', [
                 'status'     => 'Menunggu',
                 'selesai'    => 'Belum',
                 'updated_at' => date('Y-m-d H:i:s'),
             ]);
}

echo json_encode(['status' => 'success', 'message' => 'Tiket berhasil dikembalikan ke antrian']);
}

/* ===============================
   HELPER: Restore peminjaman yang
   di-cancel otomatis karena konflik
=============================== */
private function _restore_conflicting_peminjaman($peminjaman_id)
{
    $pem = $this->db
        ->where('id', $peminjaman_id)
        ->get('peminjaman_ruang_rapat')
        ->row_array();
    if (!$pem) return;

    $ruangan_id  = $pem['ruangan_id'];
    $tanggal     = $pem['tanggal'];
    $jam_mulai   = $pem['jam_mulai'];
    $jam_selesai = $pem['jam_selesai'];

    $cancelled = $this->db
        ->where('ruangan_id', $ruangan_id)
        ->where('tanggal', $tanggal)
        ->where('id !=', $peminjaman_id)
        ->where('status', 'Ditolak')
        ->like('keterangan', 'Dibatalkan otomatis:')
        ->group_start()
            ->where('jam_mulai <', $jam_selesai)
            ->where('jam_selesai >', $jam_mulai)
        ->group_end()
        ->get('peminjaman_ruang_rapat')
        ->result_array();

    if (empty($cancelled)) return;

    foreach ($cancelled as $c) {
        $this->db->where('id', $c['id'])->update('peminjaman_ruang_rapat', [
            'status'     => 'Menunggu',
            'keterangan' => null,
        ]);

        $tiket_ref = $this->db
            ->where('jenis_kerusakan LIKE', "%[ref:peminjaman_id={$c['id']}]%")
            ->where('status', 'Dibatalkan')
            ->like('layanan_catatan', 'Dibatalkan otomatis:')
            ->get('tickets')
            ->result_array();

        foreach ($tiket_ref as $tk) {
            $this->db->where('id', $tk['id'])->update('tickets', [
                'status'          => 'Menunggu',
                'teknisi_id'      => null,
                'layanan_catatan' => null,
            ]);
        }
    }
}

/* ===============================
   AJAX: GET DATA PEMINJAMAN UNTUK EDIT
=============================== */
public function get_peminjaman($id)
{
    header('Content-Type: application/json');

    $pem = $this->db
        ->where('id', $id)
        ->get('peminjaman_ruang_rapat')
        ->row_array();

    if (!$pem) {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        return;
    }

    echo json_encode(['status' => 'success', 'peminjaman' => $pem]);
}
/* ===============================
   AJAX: GET RUANGAN AKTIF (untuk edit ruang rapat)
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

            if ($jam_mulai && $jam_selesai) {
                $conflict = $this->db
                    ->where('ruangan_id', $r['id'])
                    ->where('tanggal', $tanggal)
                    ->where('status', 'Disetujui')
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
}
public function upload_dokumen_teknisi($ticket_id)
{
    header('Content-Type: application/json');

    $this->load->model('Ticket_model');
    $ticket = $this->Ticket_model->get_by_id($ticket_id);

    if (!$ticket) {
        echo json_encode(['status' => 'error', 'message' => 'Tiket tidak ditemukan']);
        return;
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
        echo json_encode(['status' => 'error', 'message' => $this->upload->display_errors('', '')]);
        return;
    }

    // Hapus file lama
    if (!empty($ticket['dokumen_peserta'])) {
        $old = $upload_path . $ticket['dokumen_peserta'];
        if (file_exists($old)) @unlink($old);
    }

    $file_name = $this->upload->data('file_name');

    // Simpan ke tickets
    $this->db->where('id', $ticket_id)
             ->update('tickets', ['dokumen_peserta' => $file_name]);

    // Sinkron ke peminjaman_ruang_rapat jika tiket ruang rapat
    if (preg_match('/\[ref:peminjaman_id=(\d+)\]/', $ticket['jenis_kerusakan'], $m)) {
        $this->db->where('id', (int)$m[1])
                 ->update('peminjaman_ruang_rapat', [
                     'dokumen_peserta' => $file_name,
                     'updated_at'      => date('Y-m-d H:i:s'),
                 ]);
    }

    echo json_encode([
        'status'      => 'success',
        'message'     => 'Dokumen berhasil diunggah',
        'dokumen_url' => base_url('uploads/dokumen_peserta/' . $file_name),
    ]);
}

public function hapus_dokumen_teknisi($ticket_id)
{
    header('Content-Type: application/json');

    $this->load->model('Ticket_model');
    $ticket = $this->Ticket_model->get_by_id($ticket_id);

    if (!$ticket || empty($ticket['dokumen_peserta'])) {
        echo json_encode(['status' => 'error', 'message' => 'Dokumen tidak ditemukan']);
        return;
    }

    $path = FCPATH . 'uploads/dokumen_peserta/' . $ticket['dokumen_peserta'];
    if (file_exists($path)) @unlink($path);

    $this->db->where('id', $ticket_id)
             ->update('tickets', ['dokumen_peserta' => null]);

    // Sinkron ke peminjaman_ruang_rapat
    if (preg_match('/\[ref:peminjaman_id=(\d+)\]/', $ticket['jenis_kerusakan'], $m)) {
        $this->db->where('id', (int)$m[1])
                 ->update('peminjaman_ruang_rapat', [
                     'dokumen_peserta' => null,
                     'updated_at'      => date('Y-m-d H:i:s'),
                 ]);
    }

    echo json_encode(['status' => 'success', 'message' => 'Dokumen berhasil dihapus']);
}
private function _sync_kendaraan_status($ticket_id, $status_peminjaman)
{
    $ticket = $this->db
        ->select('jenis_kerusakan')
        ->where('id', $ticket_id)
        ->get('tickets')
        ->row_array();

    if (!$ticket) return;

    if (preg_match('/\[ref:peminjaman_kendaraan_id=(\d+)\]/',
        $ticket['jenis_kerusakan'], $m)) {

        $data = ['status' => $status_peminjaman];

        if ($status_peminjaman === 'Selesai') {
            $data['tanggal_kembali_aktual'] = date('Y-m-d');
        } elseif ($status_peminjaman === 'Menunggu') {
            // Dibatalkan teknisi, kembalikan ke antrian
        }

        $this->db->where('id', (int)$m[1])
                 ->update('peminjaman_kendaraan', $data);
    }
}

private function _sync_bmn_status($ticket_id, $status)
{
    $ticket = $this->db
        ->select('jenis_kerusakan')
        ->where('id', $ticket_id)
        ->get('tickets')
        ->row_array();

    if (!$ticket) return;

    if (preg_match('/\[ref:peminjaman_bmn_id=(\d+)\]/',
        $ticket['jenis_kerusakan'], $m)) {

        $data = ['status' => $status];

        if ($status === 'Selesai') {
            $data['selesai']    = 'Ya';
            $data['updated_at'] = date('Y-m-d H:i:s');
        } elseif ($status === 'Disetujui') {
            $data['updated_at'] = date('Y-m-d H:i:s');
        } elseif ($status === 'Menunggu') {
            $data['selesai']    = 'Belum';
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        $this->db->where('id', (int)$m[1])
                 ->update('peminjaman_bmn', $data);
    }
}
/* ===============================
   HELPER: Ambil judul tiket dari
   jenis_kerusakan (non-gangguan)
=============================== */
private function _get_ticket_title($jenis_kerusakan, $kategori_slug)
{
    // Gangguan/kerusakan tidak perlu judul
    if (in_array(strtolower($kategori_slug), ['gangguan', 'kerusakan'])) {
        return null;
    }

    if (empty($jenis_kerusakan)) return null;

    // Urutan prioritas label yang dicari
    $labels = [
        'Keperluan / Agenda:',
        'Keperluan:',
        'Tujuan Perjalanan:',
        'Tujuan Peminjaman:',
        'Tujuan:',
        'Detail Permintaan:',
    ];

    foreach (explode("\n", $jenis_kerusakan) as $line) {
        $line = trim($line);
        foreach ($labels as $label) {
            if (stripos($line, $label) === 0) {
                $judul = trim(substr($line, strlen($label)));
                if ($judul !== '') return $judul;
            }
        }
    }

    return null;
}
public function force_cancel_menunggu($ticket_id)
{
    if (!$this->input->is_ajax_request()) show_404();

    $role = $this->session->userdata('role');
    // Teknisi biasa tidak boleh, hanya admin/superadmin atau via panel teknisi dengan konfirmasi
    // Untuk fleksibilitas, izinkan teknisi juga (karena dia yang lihat antrian)
    
    $this->load->model('Ticket_model');
    $ticket = $this->Ticket_model->get_by_id($ticket_id);

    if (!$ticket || $ticket['status'] !== 'Menunggu') {
        echo json_encode(['status' => 'error', 'message' => 'Tiket tidak dapat dibatalkan (bukan status Menunggu)']);
        return;
    }

    $alasan = trim($this->input->post('alasan') ?: 'Dibatalkan oleh operator karena ada permintaan yang lebih prioritas.');
    $jk     = $ticket['jenis_kerusakan'] ?? '';

    $this->db->where('id', $ticket_id)->update('tickets', [
        'status'          => 'Dibatalkan',
        'layanan_catatan' => $alasan,
    ]);

    // Sync ke peminjaman_ruang_rapat
    if (preg_match('/\[ref:peminjaman_id=(\d+)\]/', $jk, $m)) {
        $this->db->where('id', (int)$m[1])
                 ->update('peminjaman_ruang_rapat', [
                     'status'  => 'Dibatalkan',
                     'selesai' => 'Ya',
                 ]);
    }

    // Sync ke peminjaman_kendaraan
    if (preg_match('/\[ref:peminjaman_kendaraan_id=(\d+)\]/', $jk, $m)) {
        $this->db->where('id', (int)$m[1])
                 ->update('peminjaman_kendaraan', ['status' => 'Dibatalkan']);
    }

    // Sync ke peminjaman_bmn
    if (preg_match('/\[ref:peminjaman_bmn_id=(\d+)\]/', $jk, $m)) {
        $this->db->where('id', (int)$m[1])
                 ->update('peminjaman_bmn', [
                     'status'  => 'Dibatalkan',
                     'selesai' => 'Ya',
                 ]);
    }

    // Kirim email notifikasi ke user
    $this->load->helper('email');
    $user = $this->db->where('id', $ticket['user_id'])->get('users')->row_array();
    if ($user && !empty($user['email'])) {
        $subject = "[SIPASTI] {$ticket['kode_tiket']} - Permintaan Dibatalkan";
        $body    = $this->_email_cancel_menunggu($ticket, $user, $alasan);
        send_ticket_email($user['email'], $subject, $body);
    }

    echo json_encode(['status' => 'success', 'message' => 'Tiket berhasil dibatalkan']);
}

private function _email_cancel_menunggu($ticket, $user, $alasan)
{
    $nama      = $user['name'] ?? 'Pengguna';
    $kode      = $ticket['kode_tiket'];
    $deskripsi = nl2br(htmlspecialchars(
        preg_replace('/\[ref:[^\]]+\]/', '', $ticket['jenis_kerusakan'] ?? '')
    ));
    $alasanHtml = htmlspecialchars($alasan);

    return "<!DOCTYPE html>
<html><head><meta charset='UTF-8'></head>
<body style='margin:0;padding:0;background:#f3f4f6;font-family:sans-serif;'>
<table width='100%' cellpadding='0' cellspacing='0' style='padding:40px 20px;background:#f3f4f6;'>
<tr><td align='center'>
<table width='100%' style='max-width:580px;' cellpadding='0' cellspacing='0'>
  <tr>
    <td style='background:linear-gradient(135deg,#dc2626,#b91c1c);padding:36px 30px;border-radius:12px 12px 0 0;'>
      <div style='font-size:44px;margin-bottom:8px;'>🚫</div>
      <h1 style='margin:0;color:#fff;font-size:24px;font-weight:bold;'>Permintaan Dibatalkan</h1>
      <p style='margin:10px 0 0;color:#fecaca;font-size:14px;'>Permintaan Anda tidak dapat diproses saat ini</p>
    </td>
  </tr>
  <tr>
    <td style='background:#fff;padding:30px;'>
      <p style='color:#1f2937;font-size:15px;'>Yth. <strong>{$nama}</strong>,</p>
      <p style='color:#1f2937;font-size:14px;line-height:1.7;'>
        Permintaan Anda dengan kode <strong style='color:#dc2626;'>{$kode}</strong>
        telah <strong>dibatalkan oleh operator</strong>.
      </p>
      <div style='margin:20px 0;padding:16px;background:#fef2f2;border-left:4px solid #dc2626;border-radius:6px;'>
        <p style='margin:0 0 6px;color:#991b1b;font-size:13px;font-weight:700;'>⚠️ Alasan:</p>
        <p style='margin:0;color:#1f2937;font-size:14px;line-height:1.6;'>{$alasanHtml}</p>
      </div>
      <div style='background:#fef2f2;border-left:4px solid #6b7280;border-radius:6px;padding:14px;margin:16px 0;'>
        <p style='margin:0 0 8px;color:#6b7280;font-size:12px;font-weight:600;'>📝 DETAIL PERMINTAAN:</p>
        <div style='color:#374151;font-size:13px;line-height:1.7;'>{$deskripsi}</div>
      </div>
      <div style='background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:14px;'>
        <p style='margin:0;color:#1e40af;font-size:13px;line-height:1.7;'>
          💡 Silakan ajukan permintaan baru dengan waktu atau pilihan yang berbeda,
          atau hubungi tim IT untuk informasi lebih lanjut.
        </p>
      </div>
    </td>
  </tr>
  <tr>
    <td style='background:#1f2937;padding:20px 30px;border-radius:0 0 12px 12px;text-align:center;'>
      <p style='margin:0;color:#fff;font-size:13px;font-weight:bold;'>SIPASTI — BPS Provinsi Riau</p>
      <p style='margin:4px 0 0;color:#9ca3af;font-size:11px;'>Email ini dikirim otomatis. Mohon tidak membalas.</p>
    </td>
  </tr>
</table>
</td></tr>
</table>
</body></html>";
}
}