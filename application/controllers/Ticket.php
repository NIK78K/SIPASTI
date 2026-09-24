<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ticket extends CI_Controller {

public function __construct()
{
    parent::__construct();

    $this->load->model('Ticket_model');
    $this->load->model('Inventory_model');
    $this->load->model('Teknisi_model');
    $this->load->library('pagination');

    // 🔒 KUNCI: teknisi hanya boleh LIHAT
    if ($this->session->userdata('role') === 'teknisi') {
        $allowed = ['index', 'detail'];
        if (!in_array($this->router->fetch_method(), $allowed)) {

            // kalau request dari fetch / ajax
            if ($this->input->is_ajax_request()) {
                echo json_encode([
                    'status' => 'error',
                    'title'  => 'Akses Ditolak',
                    'text'   => 'Anda tidak memiliki izin untuk mengakses fitur ini.'
                ]);
                exit;
            }

            // kalau request biasa (link)
            $this->session->set_flashdata('swal', [
                'icon'  => 'error',
                'title' => 'Akses Ditolak',
                'text'  => 'Anda tidak memiliki izin untuk mengakses fitur ini.'
            ]);
            redirect('index.php/ticket');
            exit;
        }
    }
}


public function index()
{
    /* ===============================
       PAGINATION CONFIG (OFFSET MODE)
    =============================== */
    $limit  = 10;
    $offset = (int) $this->input->get('page');
    $offset = ($offset >= 0) ? $offset : 0;

    /* ===============================
       FILTER (SEARCH / STATUS / KATEGORI / SUB KATEGORI)
    =============================== */
    $keyword         = trim((string) $this->input->get('q'));
    $status          = trim((string) $this->input->get('status'));
    $kategori_id     = (int) $this->input->get('kategori_id');
    $sub_kategori_id = (int) $this->input->get('sub_kategori_id');

    $keyword         = $keyword !== '' ? $keyword : null;
    $status          = $status !== '' ? $status : null;
    $kategori_id     = $kategori_id > 0 ? $kategori_id : null;
    $sub_kategori_id = $sub_kategori_id > 0 ? $sub_kategori_id : null;

    /* ===============================
       TOTAL ROWS (FILTERED)
    =============================== */
    $total = $this->Ticket_model
        ->count_all_filtered_v2(
            $keyword,
            $status,
            $kategori_id,
            $sub_kategori_id
        );

    /* ===============================
       PAGINATION SETUP (FINAL)
    =============================== */
    $config['base_url']            = base_url('ticket/index');
    $config['total_rows']          = $total;
    $config['per_page']            = $limit;
    $config['page_query_string']   = true;
    $config['query_string_segment']= 'page';
    $config['reuse_query_string']  = true;

    /* ===== WRAPPER ===== */
    $config['full_tag_open']  = '<ul class="flex justify-center items-center gap-1 mt-6">';
    $config['full_tag_close'] = '</ul>';

    /* ===== NUMBER ===== */
    $config['num_tag_open']  = '<li>';
    $config['num_tag_close'] = '</li>';

    /* ===== CURRENT PAGE ===== */
    $config['cur_tag_open']  = '<li><span class="px-3 py-1 text-sm rounded-md bg-cyan-600 text-white font-semibold">';
    $config['cur_tag_close'] = '</span></li>';

    /* ===== LINK ATTR ===== */
    $config['attributes'] = [
        'class' => 'px-3 py-1 text-sm rounded-md bg-gray-800 text-gray-300 hover:bg-gray-700 transition'
    ];

    /* ===== PREV / NEXT ===== */
    $config['prev_link']     = '‹';
    $config['next_link']     = '›';
    $config['prev_tag_open'] = '<li>';
    $config['prev_tag_close']= '</li>';
    $config['next_tag_open'] = '<li>';
    $config['next_tag_close']= '</li>';

    /* ===== FIRST / LAST (OPTIONAL) ===== */
    $config['first_link']    = '«';
    $config['last_link']     = '»';
    $config['first_tag_open']= '<li>';
    $config['first_tag_close']= '</li>';
    $config['last_tag_open'] = '<li>';
    $config['last_tag_close']= '</li>';

    $this->pagination->initialize($config);

    /* ===============================
       DATA LIST
    =============================== */
    $data['tickets'] = $this->Ticket_model
        ->get_paginated_v2(
            $limit,
            $offset,
            $keyword,
            $status,
            $kategori_id,
            $sub_kategori_id
        );

    $data['pagination'] = $this->pagination->create_links();
    $data['page'] = $offset;

    /* ===============================
       MASTER DATA (FILTER DROPDOWN)
    =============================== */
    $data['master_kategori'] = $this->db
        ->order_by('nama_kategori','ASC')
        ->get('master_kategori')
        ->result();

    $data['master_sub_kategori'] = $this->db
        ->order_by('nama_sub_kategori','ASC')
        ->get('master_sub_kategori')
        ->result();

    /* ===============================
       DASHBOARD COUNTER
    =============================== */
    $data['teknisi_available'] = $this->Teknisi_model->get_by_status('available');
    $data['menunggu'] = $this->Ticket_model->count_status('Menunggu');
    $data['progress'] = $this->Ticket_model->count_status('On Progress');
    $data['selesai']  = $this->Ticket_model->count_status('Selesai');
    $data['batal']    = $this->Ticket_model->count_status('Dibatalkan');

    /* ===============================
       VIEW
    =============================== */
    $this->load->view('layout/header');

    if ($this->session->userdata('role') === 'teknisi') {
        $this->load->view('layout/sidebar_teknisi');
    } else {
        $this->load->view('layout/sidebar');
    }

    // 🔥 HAPUS WRAPPER ECHO INI
    // echo '<div class="flex-1 overflow-auto p-6">';
    
    $this->load->view('ticket/index', $data);
    
    // 🔥 HAPUS PENUTUP WRAPPER INI
    // echo '</div>';

    $this->load->view('layout/footer');
}


    /* =====================
       ASSIGN TEKNISI
    ===================== */
    public function assign_teknisi($ticket_id)
    {
        $teknisi_id = $this->input->post('teknisi_id');
        if (!$teknisi_id) redirect('index.php/ticket');

        $this->Ticket_model->update($ticket_id, [
            'teknisi_id' => $teknisi_id,
            'status'     => 'On Progress'
        ]);

        $this->Teknisi_model->update($teknisi_id, ['status' => 'busy']);

        redirect('index.php/ticket');
    }

    public function selesai($id)
    {
        // ambil tiket
        $ticket = $this->Ticket_model->get_by_id($id);

        // update tiket
        $this->Ticket_model->update($id, [
            'status'     => 'Selesai',
            'selesai_at' => date('Y-m-d H:i:s')
        ]);

        // 🔥 BALIKIN TEKNISI JADI AVAILABLE
        if (!empty($ticket['teknisi_id'])) {
            $this->load->model('Teknisi_model');
            $this->Teknisi_model->update(
                $ticket['teknisi_id'],
                ['status' => 'available']
            );
        }

        redirect('index.php/ticket');
    }

    public function detail($id)
    {
        $data['ticket'] = $this->Ticket_model->get_by_id($id);
        if (!$data['ticket']) show_404();

        $this->load->view('ticket/detail_modal', $data);
    }

    public function delete($id)
    {
        $this->Ticket_model->delete($id);
        redirect('index.php/ticket');
    }

    public function create()
    {
        $data['master_kategori'] = $this->db
            ->order_by('nama_kategori','ASC')
            ->get('master_kategori')
            ->result();

        $data['master_sub_kategori'] = $this->db
            ->order_by('nama_sub_kategori','ASC')
            ->get('master_sub_kategori')
            ->result();

        $this->load->view('ticket/create', $data);
    }

   public function store()
    {
        // ===== ambil input =====
        $kategori_id     = $this->input->post('kategori_id');
        $sub_kategori_id = $this->input->post('sub_kategori_id');

        $perangkat_id   = $this->input->post('perangkat_id');
        $perangkat_text = trim((string) $this->input->post('perangkat'));

        $kerusakan = trim((string) $this->input->post('jenis_kerusakan'));
        
        // 🔥 AMBIL NAMA PELAPOR DARI INPUT FORM (BUKAN SESSION)
        $pelapor = trim((string) $this->input->post('pelapor'));

        $tanggal = $this->input->post('tanggal');
        $jam     = $this->input->post('jam');

        // ===== gabung tanggal + jam =====
        $created_at = date('Y-m-d H:i:s');
        if (!empty($tanggal) && !empty($jam)) {
            $created_at = $tanggal . ' ' . $jam;
        }

        // ===== kalau perangkat manual diisi =====
        if (empty($perangkat_id) && $perangkat_text !== '') {
            $kerusakan = "[Perangkat: {$perangkat_text}] " . $kerusakan;
            $perangkat_id = null;
        }

        // ===== 🔥 GENERATE KODE TIKET (ANTI NABRAK) =====
        $kode_tiket = $this->generateKodeTiket();

        // ===== simpan =====
        $data = [
            'kode_tiket'      => $kode_tiket,
            'user_id'         => $this->session->userdata('user_id'),
            'pelapor'         => $pelapor ?: $this->session->userdata('name'), // gunakan input form, fallback ke session
            'kategori_id'     => $kategori_id ?: null,
            'sub_kategori_id' => $sub_kategori_id ?: null,
            'perangkat_id'    => $perangkat_id ?: null,
            'jenis_kerusakan' => $kerusakan,
            'status'          => 'Menunggu',
            'created_at'      => $created_at
        ];

        $this->Ticket_model->insert($data);

        // ===== respon ajax =====
        if ($this->input->is_ajax_request()) {
            echo json_encode(['status' => 'ok']);
            return;
        }

        redirect('index.php/ticket');
    }

    private function generateKodeTiket()
    {
        $base = 'TKT-' . date('YmdHis');

        // cek apakah sudah ada
        $exists = $this->db
            ->where('kode_tiket', $base)
            ->count_all_results('tickets');

        // kalau belum ada → langsung pakai
        if ($exists === 0) {
            return $base;
        }

        // kalau ADA → tambahin counter
        $i = 1;
        do {
            $kode = $base . '-' . $i;
            $exists = $this->db
                ->where('kode_tiket', $kode)
                ->count_all_results('tickets');
            $i++;
        } while ($exists > 0);

        return $kode;
    }

public function edit($id)
    {
        $data['ticket'] = $this->Ticket_model->get_by_id($id);
        
        if (!$data['ticket']) {
            show_404();
        }

        // 🔥 LOAD SEBAGAI STANDALONE (TANPA HEADER/FOOTER)
        $this->load->view('ticket/edit', $data);
    }

   public function update($id)
{
    $this->load->model('Ticket_model');
 
    $ticket = $this->Ticket_model->get_by_id($id);
    if (!$ticket) {
        if ($this->input->is_ajax_request()) {
            echo json_encode(['status' => 'error', 'message' => 'Tiket tidak ditemukan']);
            return;
        }
        redirect('index.php/ticket');
        return;
    }
 
    /* ── Deteksi tipe tiket ── */
    $jk = $ticket['jenis_kerusakan'] ?? '';
 
    // Ambil slug kategori
    $katSlug = '';
    if (!empty($ticket['kategori_id'])) {
        $kat = $this->db->select('slug')->where('id', $ticket['kategori_id'])
                        ->get('master_kategori')->row_array();
        $katSlug = strtolower($kat['slug'] ?? '');
    }
 
    $isPermintaan = $katSlug === 'permintaan';
    $isRuangRapat = $isPermintaan && strpos($jk, '[ref:peminjaman_id=') !== false;
    $isKendaraan  = $isPermintaan && strpos($jk, '[ref:peminjaman_kendaraan_id=') !== false;
    $isBmn        = $isPermintaan && strpos($jk, '[ref:peminjaman_bmn_id=') !== false;
 
    $perluJadwal = 0;
    if (!empty($ticket['sub_kategori_id'])) {
        $sub = $this->db->select('perlu_jadwal')
                        ->where('id', $ticket['sub_kategori_id'])
                        ->get('master_sub_kategori')->row_array();
        $perluJadwal = (int)($sub['perlu_jadwal'] ?? 0);
    }
    $isZoom = $isPermintaan && !$isRuangRapat && !$isKendaraan && !$isBmn && $perluJadwal == 1;
 
    /* ── Data umum (status, teknisi, selesai_at) ── */
    $statusInput = $this->input->post('status');
    $data = [
        'teknisi_id'      => $this->input->post('teknisi_id') ?: null,
        'status'          => $statusInput ?: $ticket['status'],
        'layanan_catatan' => $this->input->post('layanan_catatan') ?: null,
    ];
 
    // Auto-set selesai_at
    $selesaiInput = $this->input->post('selesai_at');
    if (!empty($selesaiInput)) {
        $data['selesai_at'] = date('Y-m-d H:i:s', strtotime($selesaiInput));
    } elseif ($statusInput === 'Selesai' && empty($ticket['selesai_at'])) {
        $data['selesai_at'] = date('Y-m-d H:i:s');
    }
 
    /* ── Sinkron status teknisi saat ditugaskan ── */
    $oldTeknisiId = (int)($ticket['teknisi_id'] ?? 0);
    $newTeknisiId = (int)($data['teknisi_id'] ?? 0);
 
    if ($newTeknisiId && $newTeknisiId !== $oldTeknisiId) {
        // Bebas teknisi lama
        if ($oldTeknisiId) {
            $this->db->where('id', $oldTeknisiId)->update('teknisi', ['status' => 'available']);
        }
        // Sibukkan teknisi baru
        if ($statusInput === 'On Progress') {
            $this->db->where('id', $newTeknisiId)->update('teknisi', ['status' => 'busy']);
        }
    }
 
    // Bebaskan teknisi jika tiket selesai / dibatalkan
    if (in_array($statusInput, ['Selesai', 'Dibatalkan'])) {
        $tidTeknisi = $newTeknisiId ?: $oldTeknisiId;
        if ($tidTeknisi) {
            $this->db->where('id', $tidTeknisi)->update('teknisi', ['status' => 'available']);
        }
    }
 
    /* ═══════════════════════════════════════════════════════
       FIELD KONTEKSTUAL PER TIPE TIKET
    ═══════════════════════════════════════════════════════ */
 
    if (!$isPermintaan) {
        /* ── GANGGUAN ── */
        $lokasi = trim($this->input->post('lokasi'));
        $jkBaru = trim($this->input->post('jenis_kerusakan'));
        if ($lokasi) $data['lokasi']          = $lokasi;
        if ($jkBaru) $data['jenis_kerusakan'] = $jkBaru;
 
    } elseif ($isZoom) {
        /* ── ZOOM / MEET ── */
        $jkBaru      = trim($this->input->post('jenis_kerusakan'));
        $tanggal     = $this->input->post('tanggal');
        $tanggalSel  = $this->input->post('tanggal_selesai');
        $jam         = $this->input->post('jam');
        $durasi      = $this->input->post('durasi');
        $layananLink = trim($this->input->post('layanan_link'));
        $undanganLink= $this->input->post('undangan_link');
        $akunZoom    = (int)$this->input->post('akun_zoom');
 
        if ($jkBaru)           $data['jenis_kerusakan'] = $jkBaru;
        if ($tanggal)          $data['tanggal']          = $tanggal;
        $data['tanggal_selesai'] = $tanggalSel ?: null;
        if ($jam)              $data['jam']              = $jam;
        if ($durasi)           $data['durasi']           = $durasi;
        if ($layananLink)      $data['layanan_link']     = $layananLink;
        if ($undanganLink !== false) $data['undangan_link'] = $undanganLink ?: null;
        if (in_array($akunZoom, [1, 2])) $data['akun_zoom'] = $akunZoom;
 
    } elseif ($isRuangRapat) {
        /* ── RUANG RAPAT ── */
        $peminjaman_id = null;
        if (preg_match('/\[ref:peminjaman_id=(\d+)\]/', $jk, $m)) {
            $peminjaman_id = (int)$m[1];
        }
 
        $ruanganId      = (int)$this->input->post('ruangan_id');
        $unitKerja      = trim($this->input->post('unit_kerja'));
        $tanggalRapat   = $this->input->post('tanggal_rapat');
        $jamMulai       = $this->input->post('jam_mulai');
        $jamSelesai     = $this->input->post('jam_selesai');
        $keperluan      = trim($this->input->post('keperluan'));
        $jumlahPeserta  = (int)$this->input->post('jumlah_peserta') ?: null;
        $snackCorner    = $this->input->post('snack_corner') ?: 'Tidak';
        $jumlahSnack    = ($snackCorner === 'Ya') ? (int)$this->input->post('jumlah_snack') : null;
        $catatanPeminjam= trim($this->input->post('catatan_peminjam'));
        $catatanAdmin   = trim($this->input->post('catatan_admin'));
 
        if ($peminjaman_id) {
            $pemData = [];
            if ($ruanganId)       $pemData['ruangan_id']       = $ruanganId;
            if ($unitKerja)       $pemData['unit_kerja']        = $unitKerja;
            if ($tanggalRapat)    $pemData['tanggal']           = $tanggalRapat;
            if ($jamMulai)        $pemData['jam_mulai']         = $jamMulai;
            if ($jamSelesai)      $pemData['jam_selesai']       = $jamSelesai;
            if ($keperluan)       $pemData['keperluan']         = $keperluan;
            if ($jumlahPeserta)   $pemData['jumlah_peserta']    = $jumlahPeserta;
            $pemData['snack_corner'] = $snackCorner;
            $pemData['jumlah_snack'] = $jumlahSnack;
            if ($catatanPeminjam !== '') $pemData['catatan_peminjam'] = $catatanPeminjam;
            if ($catatanAdmin !== '')    $pemData['catatan_admin']    = $catatanAdmin;
            if (!empty($pemData)) {
                $this->db->where('id', $peminjaman_id)->update('peminjaman_ruang_rapat', $pemData);
            }
        }
 
        // Rebuild jenis_kerusakan jika detail berubah
        if ($ruanganId && $tanggalRapat && $jamMulai && $jamSelesai) {
            $ruangan = $this->db->select('nama_ruangan, lokasi')
                ->where('id', $ruanganId)->get('master_ruang_rapat')->row_array();
            $baris   = [];
            $baris[] = 'Ruangan: ' . ($ruangan['nama_ruangan'] ?? '-');
            $baris[] = 'Lokasi: '  . ($ruangan['lokasi']       ?? '-');
            $baris[] = 'Tanggal: ' . date('d F Y', strtotime($tanggalRapat));
            $baris[] = 'Jam: '     . $jamMulai . ' s.d. ' . $jamSelesai;
            if ($keperluan)            $baris[] = 'Keperluan: '         . $keperluan;
            if ($jumlahPeserta)        $baris[] = 'Jumlah Peserta: '    . $jumlahPeserta . ' orang';
            if ($snackCorner === 'Ya') $baris[] = 'Snack Corner: Ya (' . $jumlahSnack . ' porsi)';
            if ($catatanPeminjam)      $baris[] = 'Catatan: '           . $catatanPeminjam;
            if ($catatanAdmin)         $baris[] = 'Catatan Admin: '     . $catatanAdmin;
            if ($peminjaman_id)        $baris[] = '[ref:peminjaman_id=' . $peminjaman_id . ']';
            $data['jenis_kerusakan'] = implode("\n", $baris);
            $data['tanggal']         = $tanggalRapat;
            $data['jam']             = $jamMulai;
        } elseif ($catatanAdmin !== '') {
            $jkBaru = preg_replace('/\nCatatan Admin:.*$/s', '', $jk);
            $data['jenis_kerusakan'] = $jkBaru . "\nCatatan Admin: " . $catatanAdmin;
        }
 
        // Catatan layanan untuk kolom tickets
        if ($catatanAdmin) $data['layanan_catatan'] = $catatanAdmin;
 
    } elseif ($isKendaraan) {
        /* ── KENDARAAN ── */
        $peminjaman_id = null;
        if (preg_match('/\[ref:peminjaman_kendaraan_id=(\d+)\]/', $jk, $m)) {
            $peminjaman_id = (int)$m[1];
        }
 
        $kKendaraanId   = (int)$this->input->post('kend_kendaraan_id') ?: null;
        $kUnitKerja     = trim($this->input->post('kend_unit_kerja'));
        $kKeperluan     = trim($this->input->post('kend_keperluan'));
        $kTujuan        = trim($this->input->post('kend_tujuan'));
        $kTglPinjam     = $this->input->post('kend_tanggal_pinjam');
        $kTglKembali    = $this->input->post('kend_tanggal_kembali');
        $kJamPinjam     = $this->input->post('kend_jam_pinjam');
        $kPengemudi     = trim($this->input->post('kend_pengemudi'));
        $kNoHp          = trim($this->input->post('kend_no_hp'));
        $kCatatan       = trim($this->input->post('kend_catatan'));
 
        if ($peminjaman_id) {
            $kendData = [];
            if ($kKendaraanId)  $kendData['kendaraan_id']   = $kKendaraanId;
            if ($kUnitKerja)    $kendData['unit_kerja']      = $kUnitKerja;
            if ($kKeperluan)    $kendData['keperluan']       = $kKeperluan;
            if ($kTujuan)       $kendData['tujuan']          = $kTujuan;
            if ($kTglPinjam)    $kendData['tanggal_pinjam']  = $kTglPinjam;
            if ($kTglKembali)   $kendData['tanggal_kembali'] = $kTglKembali;
            if ($kJamPinjam)    $kendData['jam_pinjam']      = $kJamPinjam;
            if ($kPengemudi)    $kendData['pengemudi']       = $kPengemudi;
            if ($kNoHp)         $kendData['no_hp']           = $kNoHp;
            if ($kCatatan)      $kendData['catatan']         = $kCatatan;
            if (!empty($kendData)) {
                $this->db->where('id', $peminjaman_id)->update('peminjaman_kendaraan', $kendData);
            }
        }
 
        // Rebuild jenis_kerusakan
        if ($kKendaraanId || $kKeperluan || $kTglPinjam) {
            $kend = $this->db->where('id', $kKendaraanId ?: null)
                             ->get('kendaraan')->row_array();
            $namaKend = isset($kend['nama_kendaraan'])
                ? $kend['nama_kendaraan'] . ' (' . $kend['nomor_polisi'] . ')'
                : parseJkFieldHelper($jk, 'Kendaraan:');
 
            $baris   = [];
            $baris[] = 'Kendaraan: '     . $namaKend;
            if ($kUnitKerja)  $baris[]   = 'Unit Kerja: '    . $kUnitKerja;
            if ($kKeperluan)  $baris[]   = 'Keperluan: '     . $kKeperluan;
            if ($kTujuan)     $baris[]   = 'Tujuan: '        . $kTujuan;
            if ($kTglPinjam)  $baris[]   = 'Tanggal Pinjam: '. date('d F Y', strtotime($kTglPinjam));
            if ($kTglKembali) $baris[]   = 'Est. Kembali: '  . date('d F Y', strtotime($kTglKembali));
            if ($kJamPinjam)  $baris[]   = 'Jam Mulai: '     . $kJamPinjam;
            if ($kPengemudi)  $baris[]   = 'Pengemudi: '     . $kPengemudi;
            if ($kNoHp)       $baris[]   = 'No. HP: '        . $kNoHp;
            if ($kCatatan)    $baris[]   = 'Catatan: '       . $kCatatan;
            if ($peminjaman_id) $baris[] = '[ref:peminjaman_kendaraan_id=' . $peminjaman_id . ']';
            $data['jenis_kerusakan'] = implode("\n", $baris);
        }
 
        if ($kCatatan) $data['layanan_catatan'] = $kCatatan;
 
    } elseif ($isBmn) {
        /* ── BMN ── */
        $peminjaman_id = null;
        if (preg_match('/\[ref:peminjaman_bmn_id=(\d+)\]/', $jk, $m)) {
            $peminjaman_id = (int)$m[1];
        }
 
        $bmnId         = (int)$this->input->post('bmn_id') ?: null;
        $bmnUnit       = trim($this->input->post('bmn_unit_kerja'));
        $bmnTujuan     = trim($this->input->post('bmn_tujuan'));
        $bmnTglPinjam  = $this->input->post('bmn_tanggal_pinjam');
        $bmnTglKembali = $this->input->post('bmn_tanggal_kembali');
        $bmnCatatan    = trim($this->input->post('bmn_catatan'));
 
        if ($peminjaman_id) {
            $bmnData = [];
            if ($bmnId)          $bmnData['bmn_id']           = $bmnId;
            if ($bmnUnit)        $bmnData['unit_kerja']        = $bmnUnit;
            if ($bmnTujuan)      $bmnData['tujuan']            = $bmnTujuan;
            if ($bmnTglPinjam)   $bmnData['tanggal_pinjam']   = $bmnTglPinjam;
            if ($bmnTglKembali)  $bmnData['tanggal_kembali']  = $bmnTglKembali;
            if ($bmnCatatan)     $bmnData['catatan']           = $bmnCatatan;
            if (!empty($bmnData)) {
                $this->db->where('id', $peminjaman_id)->update('peminjaman_bmn', $bmnData);
            }
        }
 
        // Rebuild jenis_kerusakan
        if ($bmnId || $bmnTujuan || $bmnTglPinjam) {
            $bmn = $this->db->where('id', $bmnId ?: null)->get('bmn')->row_array();
            $namaBmn = isset($bmn['nama_barang'])
                ? $bmn['nama_barang'] . ' (' . $bmn['kode_barang'] . '/' . $bmn['nup'] . ')'
                : parseJkFieldHelper($jk, 'Barang:');
 
            $baris   = [];
            $baris[] = 'Barang: '           . $namaBmn;
            if ($bmnUnit)        $baris[] = 'Unit Kerja: '      . $bmnUnit;
            if ($bmnTujuan)      $baris[] = 'Tujuan: '          . $bmnTujuan;
            if ($bmnTglPinjam)   $baris[] = 'Tgl Pinjam: '      . date('d F Y', strtotime($bmnTglPinjam));
            if ($bmnTglKembali)  $baris[] = 'Rencana Kembali: ' . date('d F Y', strtotime($bmnTglKembali));
            if ($bmnCatatan)     $baris[] = 'Catatan: '         . $bmnCatatan;
            if ($peminjaman_id)  $baris[] = '[ref:peminjaman_bmn_id=' . $peminjaman_id . ']';
            $data['jenis_kerusakan'] = implode("\n", $baris);
        }
 
        if ($bmnCatatan) $data['layanan_catatan'] = $bmnCatatan;
 
    } else {
        /* ── PERMINTAAN IT BIASA ── */
        $jkBaru = trim($this->input->post('jenis_kerusakan'));
        $lokasi = trim($this->input->post('lokasi'));
        if ($jkBaru) $data['jenis_kerusakan'] = $jkBaru;
        if ($lokasi) $data['lokasi']           = $lokasi;
    }
 /* ── Sinkron status ke tabel peminjaman terkait ── */
if (in_array($statusInput, ['Dibatalkan', 'Ditolak'])) {
    
    // Ruang Rapat
    if (preg_match('/\[ref:peminjaman_id=(\d+)\]/', $jk, $m)) {
        $this->db->where('id', (int)$m[1])
                 ->update('peminjaman_ruang_rapat', [
                     'status' => 'Dibatalkan',
                     'selesai' => 'Ya',
                 ]);
    }

    // Kendaraan
    if (preg_match('/\[ref:peminjaman_kendaraan_id=(\d+)\]/', $jk, $m)) {
        $this->db->where('id', (int)$m[1])
                 ->update('peminjaman_kendaraan', [
                     'status' => 'Dibatalkan',
                     'tanggal_kembali_aktual' => date('Y-m-d'),
                 ]);
    }

    // BMN
    if (preg_match('/\[ref:peminjaman_bmn_id=(\d+)\]/', $jk, $m)) {
        $this->db->where('id', (int)$m[1])
                 ->update('peminjaman_bmn', [
                     'status' => 'Dibatalkan',
                     'selesai' => 'Ya',
                     'tanggal_kembali_aktual' => date('Y-m-d'),
                 ]);
    }
}

/* ── Simpan ke DB (gunakan Ticket_model->update agar trigger email) ── */
$updated = $this->Ticket_model->update($id, $data);

 
    /* ── Response ── */
    if ($this->input->is_ajax_request()) {
        echo json_encode($updated
            ? ['status' => 'success', 'message' => 'Tiket berhasil diperbarui']
            : ['status' => 'error',   'message' => 'Gagal menyimpan tiket']
        );
        return;
    }
 
    redirect('index.php/ticket');
}
 


    public function set_layanan($id)
    {
        $this->Ticket_model->update($id, [
            'layanan_link'     => $this->input->post('layanan_link'),
            'layanan_catatan' => $this->input->post('layanan_catatan'),
            'status'           => 'On Progress'
        ]);

        redirect('index.php/ticket');
    }
    
}
    /* ── Helper statis untuk rebuild jenis_kerusakan ── */
if (!function_exists('parseJkFieldHelper')) {
    function parseJkFieldHelper($jk, $label) {
        preg_match('/' . preg_quote($label, '/') . '\s*(.+?)(?:\n|$)/u', $jk, $m);
        return trim($m[1] ?? '');
    }

    
}
