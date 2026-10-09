<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap_bulanan extends MY_Controller {

    // Urutan tampil kategori di tabel (sesuaikan dengan nilai di kolom kategori_aset)
    private $kategori_urutan = [
        'Notebook / Laptop',
        'Komputer Desktop / PC',
        'Komputer Server',
        'Perangkat Jaringan',
        'Printer / Scanner',
        'Perangkat Lain',
    ];

    public function __construct()
    {
        parent::__construct();
       if (!in_array($this->session->userdata('role'), ['admin', 'superadmin', 'teknisi'])) {
    $this->session->set_flashdata('error', 'Akses ditolak');
    redirect('dashboard');
}
    }

    /* =======================
       INDEX
    ======================== */
    public function index()
{
    $data['title']      = 'Rekap Bulanan';
    $data['bulan_list'] = $this->_get_bulan_list();
    $data['tahun_list'] = range(date('Y'), 2026);

    $role = $this->session->userdata('role');
    $sidebar = in_array($role, ['admin', 'superadmin']) 
               ? 'layout/sidebar' 
               : 'layout/sidebar_teknisi';  // ← pakai sidebar_teknisi

    $this->load->view('layout/header');
    $this->load->view($sidebar);            // ← bukan 'layout/sidebar'
    $this->load->view('rekap/rekap_bulanan_form', $data);
    $this->load->view('layout/footer');
}

    /* =======================
       AJAX REKAP — dipakai oleh tabel di form
    ======================== */
    public function ajax_rekap()
    {
        header('Content-Type: application/json');

        $bulan = (int) ($this->input->post('bulan') ?: date('n'));
        $tahun = (int) ($this->input->post('tahun') ?: date('Y'));

        if ($bulan < 1 || $bulan > 12) $bulan = (int) date('n');
        if ($tahun < 2000 || $tahun > 2099) $tahun = (int) date('Y');

        // Definisikan tgl_awal dan tgl_akhir DULU sebelum dipakai
        $tgl_awal  = "{$tahun}-" . str_pad($bulan, 2, '0', STR_PAD_LEFT) . "-01";
        $tgl_akhir = date('Y-m-t', strtotime($tgl_awal));

        // Baru hitung per kategori dengan filter tanggal
        $aset_kategori = $this->_hitung_per_kategori($tgl_akhir);

        // Summary total
        $total = 0; $baik = 0; $rr = 0; $rb = 0;
        foreach ($aset_kategori as $row) {
            $total += $row['jumlah'];
            $baik  += $row['baik'];
            $rr    += $row['rusak_ringan'];
            $rb    += $row['rusak_berat'];
        }

        // Layanan (tiket) periode bulan berjalan
        $layanan = $this->_get_layanan($tgl_awal, $tgl_akhir);

        echo json_encode([
            'summary_aset' => [
                'total'        => $total,
                'baik'         => $baik,
                'rusak_ringan' => $rr,
                'rusak_berat'  => $rb,
            ],
            'aset_kategori' => $aset_kategori,
            'layanan'       => $layanan,
        ]);
    }

    /* =======================
       GENERATE PDF
    ======================== */
    public function generate_pdf()
    {
        error_reporting(0);
        ini_set('display_errors', 0);

        $bulan = (int) ($this->input->get('bulan') ?: date('n'));
        $tahun = (int) ($this->input->get('tahun') ?: date('Y'));

        if ($bulan < 1 || $bulan > 12) $bulan = (int) date('n');
        if ($tahun < 2000 || $tahun > 2099) $tahun = (int) date('Y');

        $data = $this->_get_data_rekap($bulan, $tahun);

        require_once APPPATH . '../vendor/autoload.php';

        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Times New Roman');
        $options->set('chroot', FCPATH);

        $dompdf = new \Dompdf\Dompdf($options);

        $html = $this->load->view('rekap/rekap_bulanan_pdf', $data, true);
        $dompdf->loadHtml($html);
        $dompdf->setPaper([0, 0, 595.28, 841.89], 'portrait');
        $dompdf->render();

        while (ob_get_level()) ob_end_clean();

        $nama_bulan_arr = $this->_get_bulan_list();
        $nama_bulan     = $nama_bulan_arr[$bulan] ?? $bulan;
        $filename       = "Rekap_Bulanan_TI_{$nama_bulan}_{$tahun}.pdf";

        $dompdf->stream($filename, ['Attachment' => 0]);
        exit;
    }

    /* =======================
       PRIVATE HELPERS
    ======================== */

    /**
     * Hitung jumlah aset per kategori dengan filter tanggal_perolehan <= tgl_akhir
     * sehingga angka berubah sesuai periode yang dipilih
     */
    private function _hitung_per_kategori(string $tgl_akhir = null): array
{
    // Ambil semua perangkat yang sudah ada pada tgl_akhir
    $where_perolehan = $tgl_akhir ? "AND tanggal_perolehan <= '$tgl_akhir'" : '';

    // Untuk setiap perangkat, tentukan kondisi di tgl_akhir:
    // ambil dari history jika ada perubahan kondisi sebelum/pada tgl_akhir,
    // kalau tidak ada history → pakai kondisi saat ini
    $rows = $this->db->query("
        SELECT
            COALESCE(NULLIF(TRIM(p.kategori_aset), ''), 'Perangkat Lain') AS kategori,
            COUNT(*) AS jumlah,
            SUM(
                CASE
                    WHEN h_latest.nilai_baru IS NOT NULL THEN h_latest.nilai_baru = 'Baik'
                    ELSE p.kondisi = 'Baik'
                END
            ) AS baik,
            SUM(
                CASE
                    WHEN h_latest.nilai_baru IS NOT NULL THEN h_latest.nilai_baru = 'Rusak Ringan'
                    ELSE p.kondisi = 'Rusak Ringan'
                END
            ) AS rusak_ringan,
            SUM(
                CASE
                    WHEN h_latest.nilai_baru IS NOT NULL THEN h_latest.nilai_baru = 'Rusak Berat'
                    ELSE p.kondisi = 'Rusak Berat'
                END
            ) AS rusak_berat
        FROM perangkat p
        LEFT JOIN (
            SELECT h1.perangkat_id, h1.nilai_baru
            FROM perangkat_history h1
            INNER JOIN (
                SELECT perangkat_id, MAX(diubah_pada) AS max_tgl
                FROM perangkat_history
                WHERE field_name = 'kondisi'
                " . ($tgl_akhir ? "AND diubah_pada <= '$tgl_akhir 23:59:59'" : "") . "
                GROUP BY perangkat_id
            ) h2 ON h1.perangkat_id = h2.perangkat_id AND h1.diubah_pada = h2.max_tgl
            WHERE h1.field_name = 'kondisi'
        ) h_latest ON h_latest.perangkat_id = p.id
        WHERE 1=1 $where_perolehan
        GROUP BY COALESCE(NULLIF(TRIM(p.kategori_aset), ''), 'Perangkat Lain')
    ")->result_array();

    // Susun sesuai urutan (kode ini sama persis seperti sebelumnya)
    $indexed = [];
    foreach ($rows as $r) {
        $indexed[$r['kategori']] = $r;
    }

    $result = [];
    foreach ($this->kategori_urutan as $label) {
        if (isset($indexed[$label])) {
            $result[] = [
                'kategori'     => $label,
                'jumlah'       => (int) $indexed[$label]['jumlah'],
                'baik'         => (int) $indexed[$label]['baik'],
                'rusak_ringan' => (int) $indexed[$label]['rusak_ringan'],
                'rusak_berat'  => (int) $indexed[$label]['rusak_berat'],
            ];
            unset($indexed[$label]);
        } else {
            $result[] = [
                'kategori'     => $label,
                'jumlah'       => 0,
                'baik'         => 0,
                'rusak_ringan' => 0,
                'rusak_berat'  => 0,
            ];
        }
    }

    foreach ($indexed as $label => $r) {
        $result[] = [
            'kategori'     => $label,
            'jumlah'       => (int) $r['jumlah'],
            'baik'         => (int) $r['baik'],
            'rusak_ringan' => (int) $r['rusak_ringan'],
            'rusak_berat'  => (int) $r['rusak_berat'],
        ];
    }

    return $result;
}

    private function _get_layanan(string $tgl_awal, string $tgl_akhir): array
    {
        $cat_permintaan = $this->db
            ->where('LOWER(nama_kategori) LIKE', '%permintaan%')
            ->or_where('LOWER(nama_kategori) LIKE', '%request%')
            ->get('master_kategori')->row_array();

        $cat_gangguan = $this->db
            ->where('LOWER(nama_kategori) LIKE', '%gangguan%')
            ->or_where('LOWER(nama_kategori) LIKE', '%kerusakan%')
            ->get('master_kategori')->row_array();

        $id_permintaan = $cat_permintaan['id'] ?? null;
        $id_gangguan   = $cat_gangguan['id'] ?? null;

        return [
            'permintaan' => [
                'masuk'      => $this->_count_ticket($id_permintaan, null, $tgl_awal, $tgl_akhir),
                'ditanggapi' => $this->_count_ticket($id_permintaan, ['On Progress', 'Selesai'], $tgl_awal, $tgl_akhir),
                'selesai'    => $this->_count_ticket($id_permintaan, 'Selesai', $tgl_awal, $tgl_akhir),
            ],
            'gangguan' => [
                'masuk'      => $this->_count_ticket($id_gangguan, null, $tgl_awal, $tgl_akhir),
                'ditanggapi' => $this->_count_ticket($id_gangguan, ['On Progress', 'Selesai'], $tgl_awal, $tgl_akhir),
                'selesai'    => $this->_count_ticket($id_gangguan, 'Selesai', $tgl_awal, $tgl_akhir),
            ],
        ];
    }

    private function _get_data_rekap(int $bulan, int $tahun): array
    {
        $nama_bulan_arr = $this->_get_bulan_list();
        $nama_bulan     = $nama_bulan_arr[$bulan] ?? $bulan;

        $tgl_awal  = "{$tahun}-" . str_pad($bulan, 2, '0', STR_PAD_LEFT) . "-01";
        $tgl_akhir = date('Y-m-t', strtotime($tgl_awal));

        // Teruskan $tgl_akhir agar filter periode berjalan
        $aset_kategori = $this->_hitung_per_kategori($tgl_akhir);
        $layanan       = $this->_get_layanan($tgl_awal, $tgl_akhir);

        return [
            'satker'        => 'BPS Provinsi Riau',
            'nama_bulan'    => $nama_bulan,
            'tahun'         => $tahun,
            'aset_kategori' => $aset_kategori,
            'layanan'       => $layanan,
            'tanggal_cetak' => $this->_format_tanggal(date('Y-m-d')),
        ];
    }

    private function _count_ticket($kategori_id, $status = null, $tgl_awal = null, $tgl_akhir = null): int
    {
        $this->db->from('tickets');
        if ($kategori_id) $this->db->where('kategori_id', $kategori_id);
        if ($status) {
            is_array($status)
                ? $this->db->where_in('status', $status)
                : $this->db->where('status', $status);
        }
        if ($tgl_awal && $tgl_akhir) {
            $this->db->where('DATE(created_at) >=', $tgl_awal);
            $this->db->where('DATE(created_at) <=', $tgl_akhir);
        }
        return (int) $this->db->count_all_results();
    }

    private function _format_tanggal(string $date_str): string
    {
        $bulan_id = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        $d = new DateTime($date_str);
        return $d->format('d') . ' ' . $bulan_id[(int) $d->format('n')] . ' ' . $d->format('Y');
    }

    private function _get_bulan_list(): array
    {
        return [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
    }
}