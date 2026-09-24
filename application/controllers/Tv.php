<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// ✅ Extend CI_Controller BUKAN MY_Controller — biar ga kena auth check
class Tv extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Zoom_model');
        $this->load->helper('url');
    }

    // ── Halaman utama TV display ──────────────────────────────
    public function index()
    {
        $this->load->view('tv/display');
    }

    // ── AJAX: Zoom jadwal (tanpa cek sesi) ───────────────────
    public function zoom_ajax()
    {
        header('Content-Type: application/json');

        $tanggal = $this->input->get('tanggal') ?: date('Y-m-d');
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
            $tanggal = date('Y-m-d');
        }

        $raw  = $this->Zoom_model->get_all_zoom_today($tanggal);
        $rows = [];

        foreach ($raw as $r) {
            $parsed = $this->Zoom_model->parse_zoom_detail($r['jenis_kerusakan'] ?? '');

            $dokumen_url = null;
            if (!empty($r['dokumen_peserta'])) {
                $dokumen_url = base_url('uploads/dokumen_peserta/' . $r['dokumen_peserta']);
            }

            $rows[] = [
                'id'                => $r['id'],
                'kode_tiket'        => $r['kode_tiket'],
                'pelapor'           => $r['pelapor'],
                'tanggal'           => $r['tanggal'],
                'tanggal_selesai'   => $r['tanggal_selesai'],
                'jam'               => $r['jam'],
                'durasi'            => $r['durasi'],
                'status'            => $r['status'],
                'akun_zoom'         => $r['akun_zoom'],
                'zoom_group_id'     => $r['zoom_group_id'],
                'layanan_link'      => $r['layanan_link'] ?? '',
                'detail_permintaan' => $parsed['detail_permintaan'],
                'jumlah_peserta'    => $parsed['jumlah_peserta'],
                'jumlah_breakout'   => $parsed['jumlah_breakout'],
                'dokumen_peserta'   => $r['dokumen_peserta'] ?? null,
                'dokumen_url'       => $dokumen_url,
            ];
        }

        echo json_encode(['status' => 'success', 'rows' => $rows]);
    }

    // ── AJAX: Ruang rapat (tanpa cek sesi) ───────────────────
    public function room_ajax()
    {
        header('Content-Type: application/json');

        $tanggal = $this->input->get('tanggal') ?: date('Y-m-d');
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
            $tanggal = date('Y-m-d');
        }

        $tiket_batal = $this->db
            ->select('id, jenis_kerusakan')
            ->where_in('status', ['Dibatalkan', 'Ditolak'])
            ->like('jenis_kerusakan', '[ref:peminjaman_id=')
            ->get('tickets')
            ->result_array();

        $batal_ids = [];
        foreach ($tiket_batal as $t) {
            if (preg_match('/\[ref:peminjaman_id=(\d+)\]/', $t['jenis_kerusakan'], $m)) {
                $batal_ids[] = (int)$m[1];
            }
        }

        $query = $this->db
            ->select('p.*, m.nama_ruangan, m.lokasi AS lokasi_ruangan, m.kapasitas')
            ->from('peminjaman_ruang_rapat p')
            ->join('master_ruang_rapat m', 'm.id = p.ruangan_id', 'left')
            ->where('p.tanggal', $tanggal)
            ->where_not_in('p.status', ['Ditolak', 'Dibatalkan']);

        if (!empty($batal_ids)) {
            $query->where_not_in('p.id', $batal_ids);
        }

        $result = $query->order_by('p.jam_mulai', 'ASC')->get()->result_array();

        echo json_encode(['status' => 'success', 'rows' => $result]);
    }

    // ── AJAX: Kendaraan Dinas (tanpa cek sesi) ───────────────
    public function kendaraan_ajax()
    {
        header('Content-Type: application/json');

        $tanggal = $this->input->get('tanggal') ?: date('Y-m-d');
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
            $tanggal = date('Y-m-d');
        }

        // Exclude peminjaman yang tiketnya dibatalkan/ditolak
        $tiket_batal = $this->db
            ->select('id, jenis_kerusakan')
            ->where_in('status', ['Dibatalkan', 'Ditolak'])
            ->like('jenis_kerusakan', '[ref:peminjaman_kendaraan_id=')
            ->get('tickets')
            ->result_array();

        $batal_ids = [];
        foreach ($tiket_batal as $t) {
            if (preg_match('/\[ref:peminjaman_kendaraan_id=(\d+)\]/', $t['jenis_kerusakan'], $m)) {
                $batal_ids[] = (int)$m[1];
            }
        }

        $query = $this->db
            ->select('pk.*, mk.nama_kendaraan, mk.nomor_polisi, mk.jenis AS jenis_kendaraan')
            ->from('peminjaman_kendaraan pk')
            ->join('master_kendaraan mk', 'mk.id = pk.kendaraan_id', 'left')
            ->where('pk.tanggal_pinjam <=', $tanggal)
            ->where('pk.tanggal_kembali >=', $tanggal)
            ->where_not_in('pk.status', ['Ditolak', 'Dibatalkan']);

        if (!empty($batal_ids)) {
            $query->where_not_in('pk.id', $batal_ids);
        }

        $result = $query->order_by('pk.tanggal_pinjam', 'ASC')->get()->result_array();

        echo json_encode(['status' => 'success', 'rows' => $result]);
    }
}