<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Zoom_model extends CI_Model {

    private $zoom_sub_kategori_id = null;

    public function __construct()
    {
        parent::__construct();
        $row = $this->db
            ->where_in('slug', ['permintaan-zoom-meeting', 'permintaan-zoom-metting'])
            ->get('master_sub_kategori')
            ->row();
        $this->zoom_sub_kategori_id = $row ? (int)$row->id : 12;
    }

    /* ===============================
       AMBIL SEMUA TIKET ZOOM BERDASARKAN TANGGAL
       — termasuk tiket MULTI-DAY yang mencakup tanggal ini
       — EXCLUDE status Dibatalkan
    =============================== */
    public function get_zoom_by_date($tanggal)
    {
        return $this->db
            ->select('
                t.id,
                t.kode_tiket,
                t.pelapor,
                t.jenis_kerusakan,
                t.tanggal,
                t.tanggal_selesai,
                t.jam,
                t.durasi,
                t.status,
                t.akun_zoom,
                t.zoom_group_id,
                t.zoom_breakout_slot,
                t.layanan_link,
                t.layanan_catatan,
                t.created_at,
                t.selesai_at,
                u.name AS nama_user,
                u.email,
                tek_user.name AS nama_teknisi,
                mk.nama_kategori,
                msk.nama_sub_kategori
            ')
            ->from('tickets t')
            ->join('users u',           'u.id = t.user_id',         'left')
            ->join('teknisi tek',       'tek.id = t.teknisi_id',     'left')
            ->join('users tek_user',    'tek_user.id = tek.user_id', 'left')
            ->join('master_kategori mk','mk.id = t.kategori_id',     'left')
            ->join('master_sub_kategori msk', 'msk.id = t.sub_kategori_id', 'left')
            ->where('t.sub_kategori_id', $this->zoom_sub_kategori_id)
            ->where('t.status !=', 'Dibatalkan') // 🔥 EXCLUDE DIBATALKAN
            ->group_start()
                ->group_start()
                    ->where('t.tanggal', $tanggal)
                    ->where('t.tanggal_selesai IS NULL', null, false)
                ->group_end()
                ->or_group_start()
                    ->where('t.tanggal <=', $tanggal)
                    ->where('t.tanggal_selesai >=', $tanggal)
                ->group_end()
            ->group_end()
            ->order_by('t.jam', 'ASC')
            ->get()
            ->result_array();
    }

    /* ===============================
       ASSIGN TIKET KE AKUN ZOOM + SIMPAN KE DB
    =============================== */
    public function assign_zoom_groups($tanggal)
    {
        $tikets = $this->get_zoom_by_date($tanggal);

        $this->db->query(
            "UPDATE tickets SET zoom_group_id = NULL, zoom_breakout_slot = NULL
             WHERE sub_kategori_id = ?
               AND status != 'Dibatalkan'
               AND (
                     (tanggal = ? AND tanggal_selesai IS NULL)
                     OR (tanggal <= ? AND tanggal_selesai >= ?)
                   )
               AND akun_zoom IS NULL",
            [$this->zoom_sub_kategori_id, $tanggal, $tanggal, $tanggal]
        );

        if (empty($tikets)) return;

        foreach ($tikets as $t) {
            if (!empty($t['akun_zoom'])) {
                if (empty($t['zoom_group_id'])) {
                    $this->db->where('id', $t['id'])->update('tickets', [
                        'zoom_group_id'      => $t['id'],
                        'zoom_breakout_slot' => 1,
                    ]);
                }
                continue;
            }

            if (empty($t['jam']) || empty($t['durasi'])) {
                $this->db->where('id', $t['id'])->update('tickets', [
                    'akun_zoom'          => 1,
                    'zoom_group_id'      => $t['id'],
                    'zoom_breakout_slot' => 1,
                ]);
                continue;
            }

            $slot_baru = ['tanggal' => $tanggal, 'jam' => $t['jam'], 'durasi' => $t['durasi']];
            $akun = $this->_pilih_akun_bebas($slot_baru, $tanggal);

            $this->db->where('id', $t['id'])->update('tickets', [
                'akun_zoom'          => $akun,
                'zoom_group_id'      => $t['id'],
                'zoom_breakout_slot' => 1,
            ]);
        }
    }

    /* Helper: pilih akun zoom yang tidak bentrok di slot waktu ini */
    private function _pilih_akun_bebas($slot, $tanggal)
    {
        $existing = $this->db
            ->select('jam, durasi, akun_zoom')
            ->from('tickets')
            ->where('sub_kategori_id', $this->zoom_sub_kategori_id)
            ->where('status !=', 'Dibatalkan') // 🔥 EXCLUDE DIBATALKAN
            ->group_start()
                ->group_start()
                    ->where('tanggal', $tanggal)
                    ->where('tanggal_selesai IS NULL', null, false)
                ->group_end()
                ->or_group_start()
                    ->where('tanggal <=', $tanggal)
                    ->where('tanggal_selesai >=', $tanggal)
                ->group_end()
            ->group_end()
            ->where('akun_zoom IS NOT NULL', null, false)
            ->where('jam IS NOT NULL',       null, false)
            ->where('durasi IS NOT NULL',    null, false)
            ->get()
            ->result_array();

        $zoom1_bentrok = false;
        $zoom2_bentrok = false;

        foreach ($existing as $e) {
            $s = ['tanggal' => $tanggal, 'jam' => $e['jam'], 'durasi' => $e['durasi']];
            if ($this->is_overlap($slot, $s)) {
                if ($e['akun_zoom'] == 1) $zoom1_bentrok = true;
                if ($e['akun_zoom'] == 2) $zoom2_bentrok = true;
            }
        }

        if (!$zoom1_bentrok) return 1;
        if (!$zoom2_bentrok) return 2;
        return 1;
    }

    /* ===============================
       RESET SEMUA GRUP
    =============================== */
    public function reset_zoom_groups($tanggal)
    {
        $this->db->query(
            "UPDATE tickets
             SET zoom_group_id = NULL, zoom_breakout_slot = NULL, akun_zoom = NULL
             WHERE sub_kategori_id = ?
               AND (
                     (tanggal = ? AND tanggal_selesai IS NULL)
                     OR (tanggal <= ? AND tanggal_selesai >= ?)
                   )",
            [$this->zoom_sub_kategori_id, $tanggal, $tanggal, $tanggal]
        );
    }

    /* ===============================
       AMBIL DATA TERKELOMPOK DARI DB
    =============================== */
    public function get_zoom_grouped_from_db($tanggal)
    {
        $tikets = $this->get_zoom_by_date($tanggal);
        if (empty($tikets)) return ['zoom1' => [], 'zoom2' => []];

        $zoom1 = [];
        $zoom2 = [];

        foreach ($tikets as $t) {
            $akun  = (int)($t['akun_zoom'] ?? 1);
            $entry = [
                'jam'         => $t['jam'],
                'durasi'      => $t['durasi'],
                'akun'        => $akun,
                'is_breakout' => false,
                'tikets'      => [$t],
            ];
            if ($akun == 2) $zoom2[] = $entry;
            else            $zoom1[] = $entry;
        }

        usort($zoom1, fn($a, $b) => strcmp($a['jam'] ?? '', $b['jam'] ?? ''));
        usort($zoom2, fn($a, $b) => strcmp($a['jam'] ?? '', $b['jam'] ?? ''));

        return ['zoom1' => $zoom1, 'zoom2' => $zoom2];
    }

    /* ===============================
       CEK KONFLIK JADWAL ZOOM
    =============================== */
    public function get_konflik_jadwal($tanggal)
    {
        $tikets  = $this->get_zoom_by_date($tanggal); // sudah exclude Dibatalkan
        $konflik = [];

        $zoom1_slots = [];
        $zoom2_slots = [];

        foreach ($tikets as $t) {
            if (empty($t['jam']) || empty($t['durasi'])) continue;
            $slot = ['tanggal' => $tanggal, 'jam' => $t['jam'], 'durasi' => $t['durasi'], 'tiket' => $t];
            if (($t['akun_zoom'] ?? 0) == 1) $zoom1_slots[] = $slot;
            if (($t['akun_zoom'] ?? 0) == 2) $zoom2_slots[] = $slot;
        }

        foreach ($zoom1_slots as $s1) {
            $bentrok_zoom2 = false;
            foreach ($zoom2_slots as $s2) {
                if ($this->is_overlap($s1, $s2)) { $bentrok_zoom2 = true; break; }
            }
            if (!$bentrok_zoom2) continue;

            $seen = [];
            foreach ($zoom1_slots as $s1b) {
                if ($s1b['tiket']['id'] === $s1['tiket']['id']) continue;
                if ($this->is_overlap($s1, $s1b)) {
                    $key = min($s1['tiket']['id'], $s1b['tiket']['id'])
                         . '_' . max($s1['tiket']['id'], $s1b['tiket']['id']);
                    if (!isset($seen[$key])) {
                        $seen[$key] = true;
                        $konflik[]  = ['akun' => 1, 'tiket1' => $s1['tiket'], 'tiket2' => $s1b['tiket']];
                    }
                }
            }
        }

        $seen2 = [];
        foreach ($zoom2_slots as $s2) {
            $bentrok_zoom1 = false;
            foreach ($zoom1_slots as $s1) {
                if ($this->is_overlap($s2, $s1)) { $bentrok_zoom1 = true; break; }
            }
            if (!$bentrok_zoom1) continue;

            foreach ($zoom2_slots as $s2b) {
                if ($s2b['tiket']['id'] === $s2['tiket']['id']) continue;
                if ($this->is_overlap($s2, $s2b)) {
                    $key = min($s2['tiket']['id'], $s2b['tiket']['id'])
                         . '_' . max($s2['tiket']['id'], $s2b['tiket']['id']);
                    if (!isset($seen2[$key])) {
                        $seen2[$key] = true;
                        $konflik[]   = ['akun' => 2, 'tiket1' => $s2['tiket'], 'tiket2' => $s2b['tiket']];
                    }
                }
            }
        }

        return $konflik;
    }

    /* ===============================
       STATISTIK HARIAN ZOOM
    =============================== */
    public function get_stat_by_date($tanggal)
    {
        $tikets = $this->get_zoom_by_date($tanggal); // sudah exclude Dibatalkan

        $total = $menunggu = $on_progress = $selesai = 0;
        $total_peserta = $total_breakout = $total_multiday = 0;

        foreach ($tikets as $t) {
            $total++;
            switch ($t['status']) {
                case 'Menunggu':    $menunggu++;    break;
                case 'On Progress': $on_progress++; break;
                case 'Selesai':     $selesai++;     break;
            }
            $parsed          = $this->parse_zoom_detail($t['jenis_kerusakan']);
            $total_peserta  += (int)($parsed['jumlah_peserta']  ?? 0);
            $total_breakout += (int)($parsed['jumlah_breakout'] ?? 0);

            if (!empty($t['tanggal_selesai'])) $total_multiday++;
        }

        $zoom1_count = count(array_filter($tikets, fn($t) => ($t['akun_zoom'] ?? 0) == 1));
        $zoom2_count = count(array_filter($tikets, fn($t) => ($t['akun_zoom'] ?? 0) == 2));

        return [
            'total'          => $total,
            'menunggu'       => $menunggu,
            'on_progress'    => $on_progress,
            'selesai'        => $selesai,
            'total_peserta'  => $total_peserta,
            'total_breakout' => $total_breakout,
            'total_multiday' => $total_multiday,
            'zoom1_terpakai' => $zoom1_count,
            'zoom2_terpakai' => $zoom2_count,
            'zoom1_sisa'     => $zoom1_count > 0 ? 'Terpakai' : 'Tersedia',
            'zoom2_sisa'     => $zoom2_count > 0 ? 'Terpakai' : 'Tersedia',
        ];
    }

    /* ===============================
       AMBIL SEMUA TIKET ZOOM (untuk filter tanggal lebar)
       — EXCLUDE status Dibatalkan
    =============================== */
    public function get_all_zoom_today($tanggal)
    {
        return $this->db
           ->select('
    t.id, t.kode_tiket, t.pelapor, t.jenis_kerusakan,
    t.tanggal, t.tanggal_selesai, t.jam, t.durasi,
    t.status, t.layanan_link,
    t.zoom_group_id, t.zoom_breakout_slot, t.akun_zoom, t.created_at,
    t.dokumen_peserta,                          
    u.name AS nama_user,
    tek_user.name AS nama_teknisi
')
            ->from('tickets t')
            ->join('users u',        'u.id = t.user_id',         'left')
            ->join('teknisi tek',    'tek.id = t.teknisi_id',     'left')
            ->join('users tek_user', 'tek_user.id = tek.user_id', 'left')
            ->where('t.sub_kategori_id', $this->zoom_sub_kategori_id)
            ->where('t.status !=', 'Dibatalkan') // 🔥 EXCLUDE DIBATALKAN
            ->group_start()
                ->group_start()
                    ->where('t.tanggal', $tanggal)
                    ->where('t.tanggal_selesai IS NULL', null, false)
                ->group_end()
                ->or_group_start()
                    ->where('t.tanggal <=', $tanggal)
                    ->where('t.tanggal_selesai >=', $tanggal)
                ->group_end()
            ->group_end()
            ->order_by('t.tanggal',  'ASC')
            ->order_by('t.jam',      'ASC')
            ->get()
            ->result_array();
    }

    /* ===============================
       DETAIL TIKET ZOOM
    =============================== */
    public function get_detail($ticket_id)
    {
        $ticket = $this->db
            ->select('t.*, u.name AS nama_user, u.email,
                      tek_user.name AS nama_teknisi,
                      mk.nama_kategori, msk.nama_sub_kategori')
            ->from('tickets t')
            ->join('users u',           'u.id = t.user_id',         'left')
            ->join('teknisi tek',       'tek.id = t.teknisi_id',     'left')
            ->join('users tek_user',    'tek_user.id = tek.user_id', 'left')
            ->join('master_kategori mk','mk.id = t.kategori_id',     'left')
            ->join('master_sub_kategori msk', 'msk.id = t.sub_kategori_id', 'left')
            ->where('t.id', $ticket_id)
            ->where('t.sub_kategori_id', $this->zoom_sub_kategori_id)
            ->get()
            ->row_array();

        if ($ticket) {
            $ticket['detail_parsed'] = $this->parse_zoom_detail($ticket['jenis_kerusakan']);
            $ticket['is_multiday']   = !empty($ticket['tanggal_selesai']);
            $ticket['grup_tikets']   = [];

            if ($ticket['is_multiday']) {
                $tM = new DateTime($ticket['tanggal']);
                $tS = new DateTime($ticket['tanggal_selesai']);
                $ticket['jumlah_hari'] = $tS->diff($tM)->days + 1;
            } else {
                $ticket['jumlah_hari'] = 1;
            }
        }

        return $ticket;
    }

    /* ===============================
       PARSE DETAIL ZOOM DARI jenis_kerusakan
       — mendukung format lama (1 hari) & baru (multi-day)
    =============================== */
    public function parse_zoom_detail($teks)
    {
        $result = [
            'detail_permintaan' => '',
            'jumlah_peserta'    => '',
            'jumlah_breakout'   => '',
            'penanggung_jawab'  => '',
            'host_cohost'       => '',
            'permintaan_lain'   => '',
            'periode'           => '',
            'tanggal_teks'      => '',
            'jam_per_hari'      => '',
            'durasi_per_hari'   => '',
            'total_durasi'      => '',
            'lokasi_unit'       => '',
        ];

        if (empty($teks)) return $result;

        $lines = explode("\n", $teks);
        $i     = 0;
        $max   = count($lines);

        while ($i < $max) {
            $line = trim($lines[$i]);

            if      (strpos($line, 'Detail Permintaan:')    === 0) $result['detail_permintaan'] = trim(substr($line, strlen('Detail Permintaan:')));
            elseif  (strpos($line, 'Periode:')              === 0) $result['periode']           = trim(substr($line, strlen('Periode:')));
            elseif  (strpos($line, 'Tanggal:')              === 0) $result['tanggal_teks']      = trim(substr($line, strlen('Tanggal:')));
            elseif  (strpos($line, 'Jam Mulai (per hari):') === 0) $result['jam_per_hari']      = trim(substr($line, strlen('Jam Mulai (per hari):')));
            elseif  (strpos($line, 'Durasi (per hari):')    === 0) $result['durasi_per_hari']   = trim(substr($line, strlen('Durasi (per hari):')));
            elseif  (strpos($line, 'Total Durasi:')         === 0) $result['total_durasi']      = trim(substr($line, strlen('Total Durasi:')));
            elseif  (strpos($line, 'Jumlah Peserta:')       === 0) $result['jumlah_peserta']    = trim(str_replace([' orang', 'Jumlah Peserta:'], '', $line));
            elseif  (strpos($line, 'Jumlah Breakout Room:') === 0) $result['jumlah_breakout']   = trim(substr($line, strlen('Jumlah Breakout Room:')));
            elseif  (strpos($line, 'Penanggung Jawab:')     === 0) $result['penanggung_jawab']  = trim(substr($line, strlen('Penanggung Jawab:')));
            elseif  (strpos($line, 'Host/Co-Host:')         === 0) $result['host_cohost']       = trim(substr($line, strlen('Host/Co-Host:')));
            elseif  (strpos($line, 'Lokasi/Unit:')          === 0) $result['lokasi_unit']       = trim(substr($line, strlen('Lokasi/Unit:')));
            elseif  (strpos($line, 'Permintaan Lainnya:')   === 0) {
                $buf = trim(substr($line, strlen('Permintaan Lainnya:')));
                $i++;
                while ($i < $max) {
                    $next = trim($lines[$i]);
                    if (preg_match('/^(Detail Permintaan|Periode|Tanggal|Jam Mulai|Durasi|Total Durasi|Jumlah Peserta|Jumlah Breakout|Penanggung Jawab|Host\/Co-Host|Lokasi\/Unit):/', $next)) break;
                    $buf .= ($buf ? "\n" : '') . $next;
                    $i++;
                }
                $result['permintaan_lain'] = $buf;
                continue;
            }

            $i++;
        }

        return $result;
    }

    /* ===============================
       HITUNG PEMAKAIAN AKUN ZOOM PER HARI
    =============================== */
    public function hitung_pemakaian_akun($tanggal)
    {
        $hasil = $this->db
            ->select('akun_zoom, COUNT(*) as jumlah')
            ->from('tickets')
            ->where('sub_kategori_id', $this->zoom_sub_kategori_id)
            ->where('status !=', 'Dibatalkan') // 🔥 EXCLUDE DIBATALKAN
            ->group_start()
                ->group_start()
                    ->where('tanggal', $tanggal)
                    ->where('tanggal_selesai IS NULL', null, false)
                ->group_end()
                ->or_group_start()
                    ->where('tanggal <=', $tanggal)
                    ->where('tanggal_selesai >=', $tanggal)
                ->group_end()
            ->group_end()
            ->where('akun_zoom IS NOT NULL', null, false)
            ->group_by('akun_zoom')
            ->get()
            ->result_array();

        $pemakaian = [1 => 0, 2 => 0];
        foreach ($hasil as $h) {
            $pemakaian[(int)$h['akun_zoom']] = (int)$h['jumlah'];
        }

        return $pemakaian;
    }

    /* ===============================
       CEK OVERLAP DUA SLOT WAKTU
    =============================== */
    public function is_overlap($t1, $t2)
    {
        if (empty($t1['jam']) || empty($t2['jam']))       return false;
        if (empty($t1['durasi']) || empty($t2['durasi'])) return false;

        $tgl1   = $t1['tanggal'] ?? date('Y-m-d');
        $tgl2   = $t2['tanggal'] ?? date('Y-m-d');
        $start1 = strtotime($tgl1 . ' ' . $t1['jam']);
        $end1   = $start1 + ((float)$t1['durasi'] * 3600);
        $start2 = strtotime($tgl2 . ' ' . $t2['jam']);
        $end2   = $start2 + ((float)$t2['durasi'] * 3600);

        return ($start1 < $end2 && $start2 < $end1);
    }

    /* ===============================
       UPDATE DETAIL PERMINTAAN di jenis_kerusakan
    =============================== */
    public function update_detail_permintaan($ticket_id, $detail_baru)
    {
        $ticket = $this->db->select('jenis_kerusakan')->where('id', $ticket_id)->get('tickets')->row_array();
        if (!$ticket) return false;

        $teks  = $ticket['jenis_kerusakan'];
        $lines = explode("\n", $teks);
        $found = false;

        foreach ($lines as &$line) {
            if (strpos(trim($line), 'Detail Permintaan:') === 0) {
                $line  = 'Detail Permintaan: ' . $detail_baru;
                $found = true;
                break;
            }
        }
        unset($line);

        if (!$found) {
            array_unshift($lines, 'Detail Permintaan: ' . $detail_baru);
        }

        $teks_baru = implode("\n", $lines);

        return $this->db->where('id', $ticket_id)->update('tickets', ['jenis_kerusakan' => $teks_baru]);
    }
}