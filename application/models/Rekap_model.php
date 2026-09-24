<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap_model extends CI_Model {

    /**
     * Get Rekap — breakdown status per sub kategori
     * Return: { total, periode, kategori: { NamaKat: { total, selesai, progress, menunggu, sub: [...] } } }
     */
    public function get_rekap($bulan = 'all', $tahun_start = null, $tahun_end = null)
    {
        $tahun_start = $tahun_start ?? date('Y');
        $tahun_end   = $tahun_end   ?? $tahun_start;

        if ($bulan === 'all') {
            $start   = "$tahun_start-01-01 00:00:00";
            $end     = "$tahun_end-12-31 23:59:59";
            $periode = ($tahun_start == $tahun_end)
                ? "Tahun $tahun_start"
                : "Tahun $tahun_start - $tahun_end";
        } else {
            $start   = "$tahun_start-$bulan-01 00:00:00";
            $end     = date('Y-m-t 23:59:59', strtotime($start));
            $periode = date('F Y', strtotime($start));
        }

        // ── Query utama: semua status, breakdown per sub kategori ──
        $rows = $this->db->query("
            SELECT
                COALESCE(mk.nama_kategori,  'Tanpa Kategori')     AS nama_kategori,
                COALESCE(msk.nama_sub_kategori, 'Tanpa Sub Kategori') AS nama_sub_kategori,
                COUNT(t.id)                                         AS total,
                SUM(t.status = 'Selesai')                          AS selesai,
                SUM(t.status = 'On Progress')                      AS progress,
                SUM(t.status = 'Menunggu')                         AS menunggu
            FROM tickets t
            LEFT JOIN master_kategori     mk  ON mk.id  = t.kategori_id
            LEFT JOIN master_sub_kategori msk ON msk.id = t.sub_kategori_id
            WHERE t.created_at >= ? AND t.created_at <= ?
            GROUP BY t.kategori_id, t.sub_kategori_id
            ORDER BY nama_kategori ASC, nama_sub_kategori ASC
        ", [$start, $end])->result_array();

        $kategori = [];
        $total    = 0;

        foreach ($rows as $r) {
            $kat = $r['nama_kategori'];
            $sub = $r['nama_sub_kategori'];

            if (!isset($kategori[$kat])) {
                $kategori[$kat] = [
                    'total'    => 0,
                    'selesai'  => 0,
                    'progress' => 0,
                    'menunggu' => 0,
                    'sub'      => []
                ];
            }

            $kategori[$kat]['total']    += (int) $r['total'];
            $kategori[$kat]['selesai']  += (int) $r['selesai'];
            $kategori[$kat]['progress'] += (int) $r['progress'];
            $kategori[$kat]['menunggu'] += (int) $r['menunggu'];
            $total                      += (int) $r['total'];

            $kategori[$kat]['sub'][] = [
                'nama'     => $sub,
                'total'    => (int) $r['total'],
                'selesai'  => (int) $r['selesai'],
                'progress' => (int) $r['progress'],
                'menunggu' => (int) $r['menunggu'],
            ];
        }

        return [
            'total'    => $total,
            'kategori' => $kategori,
            'periode'  => $periode,
        ];
    }

    /**
     * Get Rekap PER BULAN (untuk export Excel/PDF)
     */
    public function get_rekap_per_bulan($tahun_start, $tahun_end)
    {
        $monthly_data = [];

        for ($tahun = $tahun_start; $tahun <= $tahun_end; $tahun++) {
            for ($bulan = 1; $bulan <= 12; $bulan++) {
                $bulan_str = sprintf('%02d', $bulan);
                $start     = "$tahun-$bulan_str-01 00:00:00";
                $end       = date('Y-m-t 23:59:59', strtotime($start));

                $rows = $this->db->query("
                    SELECT
                        COALESCE(mk.nama_kategori,      'Tanpa Kategori')     AS nama_kategori,
                        COALESCE(msk.nama_sub_kategori, 'Tanpa Sub Kategori') AS nama_sub_kategori,
                        COUNT(t.id)               AS total,
                        SUM(t.status = 'Selesai') AS selesai,
                        SUM(t.status = 'On Progress') AS progress,
                        SUM(t.status = 'Menunggu')    AS menunggu
                    FROM tickets t
                    LEFT JOIN master_kategori     mk  ON mk.id  = t.kategori_id
                    LEFT JOIN master_sub_kategori msk ON msk.id = t.sub_kategori_id
                    WHERE t.created_at >= ? AND t.created_at <= ?
                    GROUP BY t.kategori_id, t.sub_kategori_id
                    ORDER BY nama_kategori ASC, nama_sub_kategori ASC
                ", [$start, $end])->result_array();

                $kategori    = [];
                $total_bulan = 0;

                foreach ($rows as $r) {
                    $kat = $r['nama_kategori'];
                    $sub = $r['nama_sub_kategori'];

                    if (!isset($kategori[$kat])) {
                        $kategori[$kat] = ['total' => 0, 'selesai' => 0, 'progress' => 0, 'menunggu' => 0, 'sub' => []];
                    }

                    $kategori[$kat]['total']    += (int) $r['total'];
                    $kategori[$kat]['selesai']  += (int) $r['selesai'];
                    $kategori[$kat]['progress'] += (int) $r['progress'];
                    $kategori[$kat]['menunggu'] += (int) $r['menunggu'];
                    $total_bulan               += (int) $r['total'];

                    $kategori[$kat]['sub'][] = [
                        'nama'     => $sub,
                        'total'    => (int) $r['total'],
                        'selesai'  => (int) $r['selesai'],
                        'progress' => (int) $r['progress'],
                        'menunggu' => (int) $r['menunggu'],
                    ];
                }

                if ($total_bulan > 0) {
                    $monthly_data[] = [
                        'bulan_nama' => $this->_bulan_indonesia($bulan) . ' ' . $tahun,
                        'data'       => [
                            'total'    => $total_bulan,
                            'kategori' => $kategori,
                        ],
                    ];
                }
            }
        }

        return $monthly_data;
    }

    /**
     * Get Rekap Pemeliharaan Perangkat
     */
    public function get_rekap_pemeliharaan($bulan = 'all', $tahun_start = null, $tahun_end = null)
    {
        $tahun_start = $tahun_start ?? date('Y');
        $tahun_end   = $tahun_end   ?? $tahun_start;

        if ($bulan === 'all') {
            $start = "$tahun_start-01-01 00:00:00";
            $end   = "$tahun_end-12-31 23:59:59";
        } else {
            $start = "$tahun_start-$bulan-01 00:00:00";
            $end   = date('Y-m-t 23:59:59', strtotime($start));
        }

        $rows = $this->db
            ->select('p.nama_barang, COUNT(DISTINCT t.id) AS jumlah_perbaikan')
            ->from('tickets t')
            ->join('perangkat p', 'p.id = t.perangkat_id', 'inner')
            ->where('t.status', 'Selesai')
            ->where('t.created_at >=', $start)
            ->where('t.created_at <=', $end)
            ->where('t.perangkat_id IS NOT NULL')
            ->where('t.perangkat_id !=', 0)
            ->group_by('p.nama_barang')
            ->order_by('jumlah_perbaikan', 'DESC')
            ->order_by('p.nama_barang', 'ASC')
            ->get()->result_array();

        $query_lain = $this->db
            ->select('COUNT(t.id) AS total_lainlain')
            ->from('tickets t')
            ->where('t.status', 'Selesai')
            ->where('t.created_at >=', $start)
            ->where('t.created_at <=', $end)
            ->group_start()
                ->where('t.perangkat_id IS NULL')
                ->or_where('t.perangkat_id', 0)
            ->group_end()
            ->get()->row();

        $total_lain = $query_lain ? (int) $query_lain->total_lainlain : 0;
        $total      = array_sum(array_column($rows, 'jumlah_perbaikan')) + $total_lain;

        return [
            'data'             => $rows,
            'total_tiket'      => $total,
            'total_perangkat'  => count($rows),
            'total_lainlain'   => $total_lain,
        ];
    }

    /**
     * Get Rekap Pemeliharaan PER BULAN (untuk export)
     */
    public function get_rekap_pemeliharaan_per_bulan($tahun_start, $tahun_end)
    {
        $monthly_data = [];

        for ($tahun = $tahun_start; $tahun <= $tahun_end; $tahun++) {
            for ($bulan = 1; $bulan <= 12; $bulan++) {
                $bulan_str = sprintf('%02d', $bulan);
                $start     = "$tahun-$bulan_str-01 00:00:00";
                $end       = date('Y-m-t 23:59:59', strtotime($start));

                $rows = $this->db
                    ->select('p.nama_barang, COUNT(DISTINCT t.id) AS jumlah_perbaikan')
                    ->from('tickets t')
                    ->join('perangkat p', 'p.id = t.perangkat_id', 'inner')
                    ->where('t.status', 'Selesai')
                    ->where('t.created_at >=', $start)
                    ->where('t.created_at <=', $end)
                    ->where('t.perangkat_id IS NOT NULL')
                    ->where('t.perangkat_id !=', 0)
                    ->group_by('p.nama_barang')
                    ->order_by('jumlah_perbaikan', 'DESC')
                    ->get()->result_array();

                $query_lain = $this->db
                    ->select('COUNT(t.id) AS total_lainlain')
                    ->from('tickets t')
                    ->where('t.status', 'Selesai')
                    ->where('t.created_at >=', $start)
                    ->where('t.created_at <=', $end)
                    ->group_start()
                        ->where('t.perangkat_id IS NULL')
                        ->or_where('t.perangkat_id', 0)
                    ->group_end()
                    ->get()->row();

                $total_lain  = $query_lain ? (int) $query_lain->total_lainlain : 0;
                $total_tiket = array_sum(array_column($rows, 'jumlah_perbaikan')) + $total_lain;

                if ($total_tiket > 0) {
                    $monthly_data[] = [
                        'bulan_nama' => $this->_bulan_indonesia($bulan) . ' ' . $tahun,
                        'data'       => [
                            'data'            => $rows,
                            'total_tiket'     => $total_tiket,
                            'total_perangkat' => count($rows),
                            'total_lainlain'  => $total_lain,
                        ],
                    ];
                }
            }
        }

        return $monthly_data;
    }

    // ── Helper ──────────────────────────────────────────────────────
    private function _bulan_indonesia($bulan)
    {
        $nama = [
            1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',
            5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',
            9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
        ];
        return $nama[$bulan] ?? '';
    }
}