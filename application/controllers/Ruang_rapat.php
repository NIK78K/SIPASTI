<?php
// application/controllers/Ruang_rapat.php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ruang_rapat extends CI_Controller
{
  public function __construct()
{
    parent::__construct();
    if (!$this->session->userdata('user_id')) {
        redirect('auth/login');
    }
    $this->load->model([
        'Peminjaman_model',
        'Ruang_rapat_model',
        'Peminjaman_kendaraan_model',
        'Kendaraan_model',
        'Peminjaman_bmn_model',   // ← tambah ini
        'Master_bmn_model',       // ← tambah ini
    ]);
}

    // ══════════════════════════════════════════════════════
    //  INDEX — render view dengan data ruangan & kendaraan
    // ══════════════════════════════════════════════════════

    public function index()
{
    $today = date('Y-m-d');
    $now   = date('H:i:s');

    // ── RUANG RAPAT ───────────────────────────────────
    $jadwal_hari_ini = $this->Peminjaman_model->get_by_tanggal($today);
    foreach ($jadwal_hari_ini as &$j) {
        $now_hi = date('H:i');
        $j['sedang_dipakai'] = (
            $now_hi >= substr($j['jam_mulai'], 0, 5) &&
            $now_hi <= substr($j['jam_selesai'], 0, 5) &&
            $j['status'] === 'Disetujui'
        );
    }
    unset($j);

    $semua_ruangan  = $this->Ruang_rapat_model->get_aktif();
    $status_ruangan = [];
    foreach ($semua_ruangan as $r) {
        $sedang = $this->db
            ->where('ruangan_id', $r['id'])
            ->where('tanggal', $today)
            ->where('status', 'Disetujui')
            ->where('jam_mulai <=', $now)
            ->where('jam_selesai >=', $now)
            ->count_all_results('peminjaman_ruang_rapat');
        $r['sedang_dipakai'] = $sedang > 0;
        $status_ruangan[]    = $r;
    }

    // ── KENDARAAN ─────────────────────────────────────
    $semua_kendaraan      = $this->Kendaraan_model->get_all();
    $kendaraan_peminjaman = $this->Peminjaman_kendaraan_model->get_all();

    $data = [
        'title'                => 'Manajemen Peminjaman',
        // Ruangan
        'peminjaman'           => $this->Peminjaman_model->get_all(),
        'ruangan'              => $semua_ruangan,
        'jadwal_hari_ini'      => $jadwal_hari_ini,
        'status_ruangan'       => $status_ruangan,
        'total'                => $this->Peminjaman_model->count_total(),
        'menunggu'             => $this->Peminjaman_model->count_by_status('Menunggu'),
        'disetujui'            => $this->Peminjaman_model->count_by_status('Disetujui'),
        'ditolak'              => $this->Peminjaman_model->count_by_status('Ditolak'),
        'selesai'              => $this->Peminjaman_model->count_by_status('Selesai'),
        // Kendaraan
        'kendaraan_peminjaman' => $kendaraan_peminjaman,
        'status_kendaraan'     => $semua_kendaraan,
        'daftar_kendaraan'     => $semua_kendaraan,
        'peminjaman_bmn'       => $this->Peminjaman_bmn_model->get_all(),
'daftar_bmn'           => $this->Master_bmn_model->get_aktif(),
'bmn_total'            => $this->Peminjaman_bmn_model->count_total(),
'bmn_menunggu'         => $this->Peminjaman_bmn_model->count_by_status('Menunggu'),
'bmn_disetujui'        => $this->Peminjaman_bmn_model->count_by_status('Disetujui'),
'bmn_selesai'          => $this->Peminjaman_bmn_model->count_by_status('Selesai'),
    ];

  $role    = $this->session->userdata('role');
$sidebar = ($role === 'teknisi') ? 'layout/sidebar_teknisi' : 'layout/sidebar';

$this->load->view('layout/header', $data);
$this->load->view($sidebar);
$this->load->view('ruang_rapat/index', $data);
$this->load->view('layout/footer');
}

// ── AJAX: jadwal kendaraan untuk dashboard user ──
public function jadwal_kendaraan_ajax()
{
    header('Content-Type: application/json');

    if (!$this->session->userdata('user_id')) {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
        return;
    }

    $tanggal = $this->input->get('tanggal') ?: date('Y-m-d');
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
        $tanggal = date('Y-m-d');
    }

    try {
        $raw = $this->db
            ->select('pk.*, mk.nama_kendaraan, mk.nomor_polisi, mk.jenis')
            ->from('peminjaman_kendaraan pk')
            ->join('master_kendaraan mk', 'mk.id = pk.kendaraan_id', 'left')
            ->where('pk.tanggal_pinjam <=', $tanggal)
            ->where('pk.tanggal_kembali >=', $tanggal)
            ->where_in('pk.status', ['Menunggu', 'Disetujui', 'Selesai'])
            ->order_by('pk.tanggal_pinjam', 'ASC')
            ->get()
            ->result_array();

        $rows = [];
        foreach ($raw as $r) {
            $rows[] = [
                'id'              => $r['id'],
                'nama_kendaraan'  => $r['nama_kendaraan'],
                'nomor_polisi'    => $r['nomor_polisi'],
                'jenis'           => $r['jenis'],
                'nama_peminjam'   => $r['nama_peminjam'],
                'unit_kerja'      => $r['unit_kerja'],
                'keperluan'       => $r['keperluan'],
                'tujuan'          => $r['tujuan'] ?? '',
                'tanggal_pinjam'  => $r['tanggal_pinjam'],
                'tanggal_kembali' => $r['tanggal_kembali'],
                'tipe_pinjam'     => $r['tipe_pinjam'] ?? 'harian',
                'jam_pinjam'      => $r['jam_pinjam'] ?? null,
                'jam_kembali'     => $r['jam_kembali'] ?? null,
                'pengemudi'       => $r['pengemudi'] ?? 'Sendiri',
                'status'          => $r['status'],
            ];
        }

        echo json_encode(['status' => 'success', 'rows' => $rows]);

    } catch (Exception $e) {
        log_message('error', 'jadwal_kendaraan_ajax error: ' . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

    // ══════════════════════════════════════════════════════
    //  RUANG RAPAT — CRUD
    // ══════════════════════════════════════════════════════

    public function simpan()
    {
        $ruangan_id  = (int) $this->input->post('ruangan_id');
        $tanggal     = $this->input->post('tanggal', true);
        $jam_mulai   = $this->input->post('jam_mulai', true);
        $jam_selesai = $this->input->post('jam_selesai', true);

        if ($jam_mulai >= $jam_selesai) {
            $this->session->set_flashdata('error', 'Jam selesai harus lebih besar dari jam mulai.');
            redirect('ruang_rapat');
        }

        if ($this->Peminjaman_model->is_conflict($ruangan_id, $tanggal, $jam_mulai, $jam_selesai)) {
            $this->session->set_flashdata('error', 'Ruangan sudah dipesan pada waktu tersebut. Pilih waktu lain.');
            redirect('ruang_rapat');
        }

        $snack_corner = $this->input->post('snack_corner', true) ?: 'Tidak';
        $jumlah_snack = ($snack_corner === 'Ya') ? (int) $this->input->post('jumlah_snack') : null;

        $data = [
            'ruangan_id'       => $ruangan_id,
            'nama_peminjam'    => $this->input->post('nama_peminjam', true),
            'unit_kerja'       => $this->input->post('unit_kerja', true),
            'tanggal'          => $tanggal,
            'jam_mulai'        => $jam_mulai,
            'jam_selesai'      => $jam_selesai,
            'keperluan'        => $this->input->post('keperluan', true),
            'jumlah_peserta'   => (int) $this->input->post('jumlah_peserta') ?: null,
            'snack_corner'     => $snack_corner,
            'jumlah_snack'     => $jumlah_snack,
            'catatan_peminjam' => $this->input->post('catatan_peminjam', true),
            'status'           => 'Menunggu',
            'selesai'          => 'Belum',
            'created_by'       => $this->session->userdata('user_id'),
        ];

        $this->Peminjaman_model->insert($data);
        $this->session->set_flashdata('success', 'Peminjaman ruangan berhasil diajukan.');
        redirect('ruang_rapat');
    }

    public function update()
    {
        $id          = (int) $this->input->post('id');
        $jam_mulai   = $this->input->post('jam_mulai', true);
        $jam_selesai = $this->input->post('jam_selesai', true);

        if ($jam_mulai >= $jam_selesai) {
            $this->session->set_flashdata('error', 'Jam selesai harus lebih besar dari jam mulai.');
            redirect('ruang_rapat');
        }

        $status       = $this->input->post('status', true);
        $snack_corner = $this->input->post('snack_corner', true) ?: 'Tidak';
        $jumlah_snack = ($snack_corner === 'Ya') ? (int) $this->input->post('jumlah_snack') : null;

        $selesai = in_array($status, ['Ditolak', 'Selesai']) ? 'Ya' : 'Belum';
        if ($this->input->post('selesai') === 'Ya') $selesai = 'Ya';

        $data = [
            'nama_peminjam'    => $this->input->post('nama_peminjam', true),
            'unit_kerja'       => $this->input->post('unit_kerja', true),
            'tanggal'          => $this->input->post('tanggal', true),
            'jam_mulai'        => $jam_mulai,
            'jam_selesai'      => $jam_selesai,
            'keperluan'        => $this->input->post('keperluan', true),
            'jumlah_peserta'   => (int) $this->input->post('jumlah_peserta') ?: null,
            'snack_corner'     => $snack_corner,
            'jumlah_snack'     => $jumlah_snack,
            'catatan_peminjam' => $this->input->post('catatan_peminjam', true),
            'catatan_admin'    => $this->input->post('catatan_admin', true),
            'status'           => $status,
            'selesai'          => $selesai,
        ];

        $this->Peminjaman_model->update($id, $data);
        $this->session->set_flashdata('success', 'Data peminjaman ruangan berhasil diperbarui.');
        redirect('ruang_rapat');
    }

    public function setujui($id)
    {
        $this->Peminjaman_model->update_status($id, 'Disetujui');
        $this->session->set_flashdata('success', 'Peminjaman ruangan disetujui.');
        redirect('ruang_rapat');
    }

    public function tolak($id)
    {
        $catatan = $this->input->get('catatan', true);
        $this->Peminjaman_model->update_status($id, 'Ditolak');
        if ($catatan) {
            $this->Peminjaman_model->update($id, ['catatan_admin' => $catatan]);
        }
        $this->session->set_flashdata('success', 'Peminjaman ruangan ditolak.');
        redirect('ruang_rapat');
    }

    public function selesai($id)
    {
        $this->Peminjaman_model->mark_selesai($id);
        $this->session->set_flashdata('success', 'Kegiatan ruangan ditandai selesai.');
        redirect('ruang_rapat');
    }

    public function hapus($id)
    {
        $this->Peminjaman_model->delete($id);
        $this->session->set_flashdata('success', 'Data peminjaman ruangan dihapus.');
        redirect('ruang_rapat');
    }


    // ══════════════════════════════════════════════════════
    //  KENDARAAN — CRUD
    // ══════════════════════════════════════════════════════

    public function simpan_kendaraan()
    {
        $kendaraan_id     = (int) $this->input->post('kendaraan_id');
        $tanggal_pinjam   = $this->input->post('tanggal_pinjam', true);
        $tanggal_kembali  = $this->input->post('tanggal_kembali', true);

        // Validasi tanggal kembali >= tanggal pinjam
        if ($tanggal_kembali < $tanggal_pinjam) {
            $this->session->set_flashdata('error', 'Tanggal kembali tidak boleh sebelum tanggal pinjam.');
            redirect('ruang_rapat?tab=kendaraan');
        }

        // Cek konflik kendaraan di rentang tanggal yang sama
        if ($this->Peminjaman_kendaraan_model->is_conflict($kendaraan_id, $tanggal_pinjam, $tanggal_kembali)) {
            $this->session->set_flashdata('error', 'Kendaraan sudah dipinjam pada rentang tanggal tersebut.');
            redirect('ruang_rapat?tab=kendaraan');
        }

        $data = [
            'kendaraan_id'   => $kendaraan_id,
            'nama_peminjam'  => $this->input->post('nama_peminjam', true),
            'unit_kerja'     => $this->input->post('unit_kerja', true),
            'keperluan'      => $this->input->post('keperluan', true),
            'tujuan'         => $this->input->post('tujuan', true),
            'tanggal_pinjam' => $tanggal_pinjam,
            'tanggal_kembali'=> $tanggal_kembali,
            'pengemudi'      => $this->input->post('pengemudi', true) ?: 'Sendiri',
            'no_hp'          => $this->input->post('no_hp', true),
            'catatan'        => $this->input->post('catatan', true),
            'status'         => 'Menunggu',
            'created_by'     => $this->session->userdata('user_id'),
        ];

        $this->Peminjaman_kendaraan_model->insert($data);
        $this->session->set_flashdata('success', 'Peminjaman kendaraan berhasil diajukan.');
        redirect('ruang_rapat?tab=kendaraan');
    }

    public function update_kendaraan()
    {
        $id              = (int) $this->input->post('id');
        $tanggal_pinjam  = $this->input->post('tanggal_pinjam', true);
        $tanggal_kembali = $this->input->post('tanggal_kembali', true);

        if ($tanggal_kembali < $tanggal_pinjam) {
            $this->session->set_flashdata('error', 'Tanggal kembali tidak boleh sebelum tanggal pinjam.');
            redirect('ruang_rapat?tab=kendaraan');
        }

        $status = $this->input->post('status', true);

        $data = [
            'nama_peminjam'  => $this->input->post('nama_peminjam', true),
            'unit_kerja'     => $this->input->post('unit_kerja', true),
            'keperluan'      => $this->input->post('keperluan', true),
            'tujuan'         => $this->input->post('tujuan', true),
            'tanggal_pinjam' => $tanggal_pinjam,
            'tanggal_kembali'=> $tanggal_kembali,
            'pengemudi'      => $this->input->post('pengemudi', true) ?: 'Sendiri',
            'status'         => $status,
            'catatan_admin'  => $this->input->post('catatan_admin', true),
        ];

        // Jika ditolak atau selesai, isi tanggal kembali aktual
        if (in_array($status, ['Ditolak', 'Selesai'])) {
            $data['tanggal_kembali_aktual'] = date('Y-m-d');
        }

        $this->Peminjaman_kendaraan_model->update($id, $data);
        $this->session->set_flashdata('success', 'Data peminjaman kendaraan berhasil diperbarui.');
        redirect('ruang_rapat?tab=kendaraan');
    }

    public function setujui_kendaraan($id)
    {
        $this->Peminjaman_kendaraan_model->update_status($id, 'Disetujui');
        $this->session->set_flashdata('success', 'Peminjaman kendaraan disetujui.');
        redirect('ruang_rapat?tab=kendaraan');
    }

    public function tolak_kendaraan($id)
    {
        $catatan = $this->input->get('catatan', true);
        $this->Peminjaman_kendaraan_model->update_status($id, 'Ditolak');
        if ($catatan) {
            $this->Peminjaman_kendaraan_model->update($id, ['catatan_admin' => $catatan]);
        }
        $this->session->set_flashdata('success', 'Peminjaman kendaraan ditolak.');
        redirect('ruang_rapat?tab=kendaraan');
    }

    public function selesai_kendaraan($id)
    {
        $this->Peminjaman_kendaraan_model->mark_selesai($id);
        $this->session->set_flashdata('success', 'Kendaraan ditandai sudah kembali.');
        redirect('ruang_rapat?tab=kendaraan');
    }

    public function hapus_kendaraan($id)
    {
        $result = $this->Peminjaman_kendaraan_model->delete($id);
        if ($result === false) {
            $this->session->set_flashdata('error', 'Tidak dapat menghapus peminjaman yang masih aktif.');
        } else {
            $this->session->set_flashdata('success', 'Data peminjaman kendaraan dihapus.');
        }
        redirect('ruang_rapat?tab=kendaraan');
    }


    // ══════════════════════════════════════════════════════
    //  AJAX — Jadwal ruangan (untuk kalender / widget lain)
    // ══════════════════════════════════════════════════════
    public function jadwal_ajax()
{
    header('Content-Type: application/json');
    if (!$this->session->userdata('user_id')) {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
        return;
    }

    $tanggal = $this->input->get('tanggal') ?: date('Y-m-d');
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
        $tanggal = date('Y-m-d');
    }

    // ✅ AUTO-SELESAI: jika tanggal & jam sudah lewat, update status jadi Selesai
    if ($tanggal <= date('Y-m-d')) {
        $now = date('H:i:s');
        $this->db
            ->where('tanggal', $tanggal)
            ->where('status', 'Disetujui')
            ->where('jam_selesai <=', ($tanggal < date('Y-m-d') ? '23:59:59' : $now))
            ->update('peminjaman_ruang_rapat', [
                'status'  => 'Selesai',
                'selesai' => 'Ya',
            ]);
    }

    $raw = $this->db
        ->select('p.*, r.nama_ruangan, r.lokasi as lokasi_ruangan, r.kapasitas')
        ->from('peminjaman_ruang_rapat p')
        ->join('master_ruang_rapat r', 'r.id = p.ruangan_id', 'left')
        ->where('p.tanggal', $tanggal)
        ->where_in('p.status', ['Menunggu', 'Disetujui', 'Selesai'])
        ->order_by('p.jam_mulai', 'ASC')
        ->get()->result_array();

   $rows = [];
foreach ($raw as $r) {
    // Multi-dokumen rapat
    $r['dokumen_list'] = [];
    $docs = $this->db
        ->select('jenis_dokumen, nama_file')
        ->where('peminjaman_id', $r['id'])
        ->order_by('created_at', 'ASC')
        ->get('dokumen_peminjaman_rapat')
        ->result_array();
    foreach ($docs as $d) {
        $r['dokumen_list'][] = [
            'jenis' => $d['jenis_dokumen'],
            'url'   => base_url('uploads/dokumen_peserta/' . $d['nama_file']),
        ];
    }

    // Backward compat: kolom lama tetap diisi
    $r['dokumen_url'] = !empty($r['dokumen_peserta'])
        ? base_url('uploads/dokumen_peserta/' . $r['dokumen_peserta'])
        : null;

    $rows[] = $r;
}

    echo json_encode(['status' => 'success', 'rows' => $rows]);
}
 public function simpan_bmn()
    {
        $bmn_id          = (int) $this->input->post('bmn_id');
        $tanggal_pinjam  = $this->input->post('tanggal_pinjam', true);
        $tanggal_kembali = $this->input->post('tanggal_kembali', true);
 
        // Validasi tanggal
        if ($tanggal_kembali < $tanggal_pinjam) {
            $this->session->set_flashdata('error', 'Tanggal kembali tidak boleh sebelum tanggal pinjam.');
            redirect('ruang_rapat?tab=bmn');
        }
 
        // Cek konflik
        if ($this->Master_bmn_model->is_conflict($bmn_id, $tanggal_pinjam, $tanggal_kembali)) {
            $this->session->set_flashdata('error', 'Barang sudah dipinjam pada rentang tanggal tersebut. Pilih tanggal lain.');
            redirect('ruang_rapat?tab=bmn');
        }
 
        $data = [
            'bmn_id'              => $bmn_id,
            'nama_peminjam'       => $this->input->post('nama_peminjam', true),
            'unit_kerja'          => $this->input->post('unit_kerja', true),
            'tujuan_peminjaman'   => $this->input->post('tujuan_peminjaman', true),
            'tanggal_pinjam'      => $tanggal_pinjam,
            'tanggal_kembali'     => $tanggal_kembali,
            'catatan'             => $this->input->post('catatan', true),
            'status'              => 'Menunggu',
            'selesai'             => 'Belum',
            'created_by'          => $this->session->userdata('user_id'),
        ];
 
        $this->Peminjaman_bmn_model->insert($data);
        $this->session->set_flashdata('success', 'Peminjaman BMN berhasil diajukan.');
        redirect('ruang_rapat?tab=bmn');
    }
 
    public function update_bmn()
    {
        $id              = (int) $this->input->post('id');
        $tanggal_pinjam  = $this->input->post('tanggal_pinjam', true);
        $tanggal_kembali = $this->input->post('tanggal_kembali', true);
 
        if ($tanggal_kembali < $tanggal_pinjam) {
            $this->session->set_flashdata('error', 'Tanggal kembali tidak boleh sebelum tanggal pinjam.');
            redirect('ruang_rapat?tab=bmn');
        }
 
        $status  = $this->input->post('status', true);
        $selesai = in_array($status, ['Ditolak', 'Selesai']) ? 'Ya' : 'Belum';
        if ($this->input->post('selesai') === 'Ya') $selesai = 'Ya';
 
        $data = [
            'nama_peminjam'      => $this->input->post('nama_peminjam', true),
            'unit_kerja'         => $this->input->post('unit_kerja', true),
            'tujuan_peminjaman'  => $this->input->post('tujuan_peminjaman', true),
            'tanggal_pinjam'     => $tanggal_pinjam,
            'tanggal_kembali'    => $tanggal_kembali,
            'catatan'            => $this->input->post('catatan', true),
            'catatan_admin'      => $this->input->post('catatan_admin', true),
            'status'             => $status,
            'selesai'            => $selesai,
        ];
 
        if (in_array($status, ['Ditolak', 'Selesai'])) {
            $data['tanggal_kembali_aktual'] = date('Y-m-d');
        }
 
        $this->Peminjaman_bmn_model->update($id, $data);
        $this->session->set_flashdata('success', 'Data peminjaman BMN berhasil diperbarui.');
        redirect('ruang_rapat?tab=bmn');
    }
 
    public function setujui_bmn($id)
    {
        $this->Peminjaman_bmn_model->update_status($id, 'Disetujui');
        $this->session->set_flashdata('success', 'Peminjaman BMN disetujui.');
        redirect('ruang_rapat?tab=bmn');
    }
 
    public function tolak_bmn($id)
    {
        $this->Peminjaman_bmn_model->update_status($id, 'Ditolak');
        $catatan = $this->input->get('catatan', true);
        if ($catatan) {
            $this->Peminjaman_bmn_model->update($id, ['catatan_admin' => $catatan]);
        }
        $this->session->set_flashdata('success', 'Peminjaman BMN ditolak.');
        redirect('ruang_rapat?tab=bmn');
    }
 
    public function selesai_bmn($id)
    {
        $this->Peminjaman_bmn_model->mark_selesai($id);
        $this->session->set_flashdata('success', 'BMN ditandai sudah dikembalikan.');
        redirect('ruang_rapat?tab=bmn');
    }
 
    public function hapus_bmn($id)
    {
        $result = $this->Peminjaman_bmn_model->delete($id);
        if ($result === false) {
            $this->session->set_flashdata('error', 'Tidak dapat menghapus peminjaman yang masih aktif.');
        } else {
            $this->session->set_flashdata('success', 'Data peminjaman BMN dihapus.');
        }
        redirect('ruang_rapat?tab=bmn');
    }
 public function jadwal_bmn_ajax()
{
    header('Content-Type: application/json');
    $tanggal = $this->input->get('tanggal');
    if (!$tanggal) {
        echo json_encode(['status' => 'error', 'message' => 'Tanggal tidak ditemukan']);
        return;
    }

    $rows = $this->db
        ->select('pb.*, mb.nama_barang, mb.merek, mb.kode_barang, mb.nup, mb.kondisi, mb.lokasi')
        ->from('peminjaman_bmn pb')
        ->join('master_bmn mb', 'mb.id = pb.bmn_id', 'left')
        ->where('pb.tanggal_pinjam <=', $tanggal)
        ->where('pb.tanggal_kembali >=', $tanggal)
        ->where_not_in('pb.status', ['Ditolak', 'Dibatalkan'])
        ->order_by('pb.tanggal_pinjam', 'ASC')
        ->get()
        ->result_array();

    echo json_encode(['status' => 'success', 'rows' => $rows]);
}

public function upload_dok_multi()
{
    $peminjaman_id = (int) $this->input->post('peminjaman_id');
    $jenis_valid   = ['Undangan', 'Notulensi', 'Dokumentasi', 'Daftar Hadir', 'Lampiran Lain'];
    $jenis         = $this->input->post('jenis_dokumen');

    if (!$peminjaman_id || !in_array($jenis, $jenis_valid)) {
        $this->session->set_flashdata('error', 'Data tidak valid.');
        redirect('ruang_rapat');
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
        $this->session->set_flashdata('error', 'Gagal upload: ' . $this->upload->display_errors('', ''));
        redirect('ruang_rapat');
        exit;
    }

    $file_name = $this->upload->data('file_name');
    $nama_asli = $this->upload->data('orig_name');

    // Hapus yang sama jenis-nya kalau sudah ada
    $existing = $this->db
        ->where('peminjaman_id', $peminjaman_id)
        ->where('jenis_dokumen', $jenis)
        ->get('dokumen_peminjaman_rapat')
        ->row_array();

    if ($existing) {
        $old = $upload_path . $existing['nama_file'];
        if (file_exists($old)) @unlink($old);
        $this->db->where('id', $existing['id'])->delete('dokumen_peminjaman_rapat');
    }

    $this->db->insert('dokumen_peminjaman_rapat', [
        'peminjaman_id' => $peminjaman_id,
        'ticket_id'     => null,
        'jenis_dokumen' => $jenis,
        'nama_file'     => $file_name,
        'nama_asli'     => $nama_asli,
        'created_at'    => date('Y-m-d H:i:s'),
    ]);

    $this->session->set_flashdata('success', 'Dokumen "' . $jenis . '" berhasil diunggah.');
    redirect('ruang_rapat');
    exit;
}

public function hapus_dok_item($doc_id)
{
    $doc = $this->db->where('id', $doc_id)->get('dokumen_peminjaman_rapat')->row_array();
    if ($doc) {
        $path = FCPATH . 'uploads/dokumen_peserta/' . $doc['nama_file'];
        if (file_exists($path)) @unlink($path);
        $this->db->where('id', $doc_id)->delete('dokumen_peminjaman_rapat');
    }
    $this->session->set_flashdata('success', 'Dokumen berhasil dihapus.');
    redirect('ruang_rapat');
    exit;
}

}