<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang_persediaan_model extends CI_Model {

    private $table = 'master_barang_persediaan';

    public function __construct() {
        parent::__construct();
        if (!$this->db->field_exists('stok_minimum', $this->table)) {
            $this->load->dbforge();
            $this->dbforge->add_column($this->table, [
                'stok_minimum' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'default' => 0,
                    'after' => 'satuan'
                ]
            ]);
        }
        if (!$this->db->field_exists('gambar', $this->table)) {
            $this->load->dbforge();
            $this->dbforge->add_column($this->table, [
                'gambar' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => TRUE,
                    'after' => 'satuan'
                ]
            ]);
        }
    }

    public function get_all_with_kategori() {
        $this->db->select('b.*, k.kode AS kategori_kode, k.nama AS kategori_nama, k.deskripsi AS kategori_deskripsi');
        $this->db->from($this->table . ' b');
        $this->db->join('master_kategori_persediaan k', 'k.id = b.kategori_id', 'left');
        $this->db->order_by('k.kode', 'ASC');
        $this->db->order_by('b.nama_barang', 'ASC');
        return $this->db->get()->result();
    }

    public function get_base_saldo_barang($barang_id, $bulan, $tahun) {
        $barang_id   = (int) $barang_id;
        $bulan       = (int) $bulan;
        $tahun       = (int) $tahun;
        $cur_bulan   = (int) date('n');
        $cur_tahun   = (int) date('Y');
        $target_date = sprintf('%04d-%02d-01', $tahun, $bulan);

        // Jika bulan di masa depan, belum ada catatan (0)
        $is_future = ($tahun > $cur_tahun) || ($tahun === $cur_tahun && $bulan > $cur_bulan);
        if ($is_future) {
            return 0;
        }

        // 1. Cek apakah ada input manual tepat di bulan & tahun ini
        $sa_now = $this->db->where('barang_id', $barang_id)
                           ->where('bulan', $bulan)
                           ->where('tahun', $tahun)
                           ->get('stok_awal_persediaan')
                           ->row();
        if ($sa_now) {
            return (int) $sa_now->jumlah;
        }

        // 2. Cari input stok awal manual terakhir sebelum periode ini
        $sa_prev = $this->db->where('barang_id', $barang_id)
                            ->where("(tahun < {$tahun} OR (tahun = {$tahun} AND bulan < {$bulan}))")
                            ->order_by('tahun', 'DESC')
                            ->order_by('bulan', 'DESC')
                            ->limit(1)
                            ->get('stok_awal_persediaan')
                            ->row();

        if ($sa_prev) {
            $base_sa   = (int) $sa_prev->jumlah;
            $base_date = sprintf('%04d-%02d-01', (int) $sa_prev->tahun, (int) $sa_prev->bulan);

            $ag = $this->db->select("
                COALESCE(SUM(CASE WHEN jenis = 'masuk' THEN jumlah ELSE 0 END), 0) AS masuk,
                COALESCE(SUM(CASE WHEN jenis = 'keluar' THEN jumlah ELSE 0 END), 0) AS keluar
            ", false)
            ->from('transaksi_persediaan')
            ->where('barang_id', $barang_id)
            ->where('tanggal >=', $base_date)
            ->where('tanggal <', $target_date)
            ->get()->row();

            return $base_sa + ((int) ($ag->masuk ?? 0)) - ((int) ($ag->keluar ?? 0));
        }

        // 3. Jika belum pernah ada input stok awal, hitung dari seluruh transaksi sebelum periode ini
        $ag = $this->db->select("
            COALESCE(SUM(CASE WHEN jenis = 'masuk' THEN jumlah ELSE 0 END), 0) AS masuk,
            COALESCE(SUM(CASE WHEN jenis = 'keluar' THEN jumlah ELSE 0 END), 0) AS keluar
        ", false)
        ->from('transaksi_persediaan')
        ->where('barang_id', $barang_id)
        ->where('tanggal <', $target_date)
        ->get()->row();

        return ((int) ($ag->masuk ?? 0)) - ((int) ($ag->keluar ?? 0));
    }

    public function get_stok_awal_barang($barang_id, $bulan, $tahun) {
        $barang_id   = (int) $barang_id;
        $bulan       = (int) $bulan;
        $tahun       = (int) $tahun;
        $cur_bulan   = (int) date('n');
        $cur_tahun   = (int) date('Y');

        $is_future = ($tahun > $cur_tahun) || ($tahun === $cur_tahun && $bulan > $cur_bulan);
        if ($is_future) {
            return 0;
        }

        // Cek apakah ada input manual tepat di bulan & tahun ini
        $sa_now = $this->db->where('barang_id', $barang_id)
                           ->where('bulan', $bulan)
                           ->where('tahun', $tahun)
                           ->get('stok_awal_persediaan')
                           ->row();
        if ($sa_now) {
            return (int) $sa_now->jumlah;
        }

        // Belum diinput manual di bulan baru -> kosongkan (null)
        return null;
    }

    public function get_stok_awal_all_map($bulan, $tahun) {
        $bulan       = (int) $bulan;
        $tahun       = (int) $tahun;
        $cur_bulan   = (int) date('n');
        $cur_tahun   = (int) date('Y');
        $target_date = sprintf('%04d-%02d-01', $tahun, $bulan);

        $is_future = ($tahun > $cur_tahun) || ($tahun === $cur_tahun && $bulan > $cur_bulan);

        $map = [];

        // Ambil daftar semua id barang
        $all_barang = $this->db->select('id')->from($this->table)->get()->result();

        if ($is_future) {
            foreach ($all_barang as $b) {
                $map[(int) $b->id] = [
                    'stok_awal'  => null,
                    'base_saldo' => 0,
                    'is_manual'  => false,
                    'is_future'  => true,
                ];
            }
            return $map;
        }

        // 1. Ambil stok awal yang ada tepat di bulan & tahun ini
        $sa_now = $this->db->where('bulan', $bulan)
                           ->where('tahun', $tahun)
                           ->get('stok_awal_persediaan')
                           ->result();
        foreach ($sa_now as $s) {
            $map[(int) $s->barang_id] = [
                'stok_awal'  => (int) $s->jumlah,
                'base_saldo' => (int) $s->jumlah,
                'is_manual'  => true,
                'is_future'  => false,
            ];
        }

        $missing_ids = [];
        foreach ($all_barang as $b) {
            if (!isset($map[(int) $b->id])) {
                $missing_ids[] = (int) $b->id;
            }
        }

        if (empty($missing_ids)) {
            return $map;
        }

        // 2. Untuk barang yang belum ada input di bulan ini, cari stok awal terakhir sebelum bulan ini
        $sub_sql = "
            SELECT s1.barang_id, s1.jumlah, s1.bulan, s1.tahun
            FROM stok_awal_persediaan s1
            INNER JOIN (
                SELECT barang_id, MAX(tahun * 100 + bulan) AS max_period
                FROM stok_awal_persediaan
                WHERE (tahun < {$tahun} OR (tahun = {$tahun} AND bulan < {$bulan}))
                GROUP BY barang_id
            ) s2 ON s1.barang_id = s2.barang_id AND (s1.tahun * 100 + s1.bulan) = s2.max_period
            WHERE s1.barang_id IN (" . implode(',', $missing_ids) . ")
        ";
        $prev_rows = $this->db->query($sub_sql)->result();
        $prev_map = [];
        foreach ($prev_rows as $p) {
            $prev_map[(int) $p->barang_id] = $p;
        }

        // 3. Hitung transaksi sebelum target_date per barang untuk mendapatkan carry-over saldo akhir sebelumnya
        foreach ($missing_ids as $bid) {
            if (isset($prev_map[$bid])) {
                $p = $prev_map[$bid];
                $base_sa   = (int) $p->jumlah;
                $base_date = sprintf('%04d-%02d-01', (int) $p->tahun, (int) $p->bulan);

                $ag = $this->db->select("
                    COALESCE(SUM(CASE WHEN jenis = 'masuk' THEN jumlah ELSE 0 END), 0) AS masuk,
                    COALESCE(SUM(CASE WHEN jenis = 'keluar' THEN jumlah ELSE 0 END), 0) AS keluar
                ", false)
                ->from('transaksi_persediaan')
                ->where('barang_id', $bid)
                ->where('tanggal >=', $base_date)
                ->where('tanggal <', $target_date)
                ->get()->row();

                $carryover = $base_sa + ((int) ($ag->masuk ?? 0)) - ((int) ($ag->keluar ?? 0));
            } else {
                $ag = $this->db->select("
                    COALESCE(SUM(CASE WHEN jenis = 'masuk' THEN jumlah ELSE 0 END), 0) AS masuk,
                    COALESCE(SUM(CASE WHEN jenis = 'keluar' THEN jumlah ELSE 0 END), 0) AS keluar
                ", false)
                ->from('transaksi_persediaan')
                ->where('barang_id', $bid)
                ->where('tanggal <', $target_date)
                ->get()->row();

                $carryover = ((int) ($ag->masuk ?? 0)) - ((int) ($ag->keluar ?? 0));
            }

            $map[$bid] = [
                'stok_awal'  => null, // Belum diinput manual untuk bulan ini -> dikosongkan (tampil '-')
                'base_saldo' => $carryover, // Tapi stok akhir tetap menghitung dari akhir stok sebelumnya
                'is_manual'  => false,
                'is_future'  => false,
            ];
        }

        return $map;
    }

    public function get_dashboard_stok($bulan, $tahun, $keyword = null, $kategori_id = null) {
        $bulan         = (int) $bulan;
        $tahun         = (int) $tahun;
        $cur_bulan     = (int) date('n');
        $cur_tahun     = (int) date('Y');
        $stok_awal_map = $this->get_stok_awal_all_map($bulan, $tahun);
        $is_future     = ($tahun > $cur_tahun) || ($tahun === $cur_tahun && $bulan > $cur_bulan);

        $this->db->select("
            b.id,
            b.kode_barang,
            b.nama_barang,
            b.satuan,
            b.gambar,
            b.stok_minimum,
            k.id AS kategori_id,
            k.kode AS kategori_kode,
            k.nama AS kategori_nama,
            0 AS stok_awal,
            COALESCE(tx.masuk, 0) AS masuk,
            COALESCE(tx.keluar, 0) AS keluar,
            0 AS stok_akhir
        ", false);
        $this->db->from($this->table . ' b');
        $this->db->join('master_kategori_persediaan k', 'k.id = b.kategori_id', 'left');
        $this->db->join(
            "(SELECT barang_id,
                     SUM(CASE WHEN jenis = 'masuk' THEN jumlah ELSE 0 END) AS masuk,
                     SUM(CASE WHEN jenis = 'keluar' THEN jumlah ELSE 0 END) AS keluar
              FROM transaksi_persediaan
              WHERE MONTH(tanggal) = " . (int) $bulan . " AND YEAR(tanggal) = " . (int) $tahun . "
              GROUP BY barang_id) tx",
            'tx.barang_id = b.id',
            'left',
            false
        );
        $this->db->where('b.status', 'Aktif');
        if ($keyword !== null && $keyword !== '') {
            $this->db->group_start();
            $this->db->like('b.kode_barang', $keyword);
            $this->db->or_like('b.nama_barang', $keyword);
            $this->db->or_like('k.nama', $keyword);
            $this->db->group_end();
        }
        if ($kategori_id !== null && $kategori_id !== '') {
            $this->db->where('b.kategori_id', (int) $kategori_id);
        }
        $this->db->order_by('k.kode', 'ASC');
        $this->db->order_by('b.nama_barang', 'ASC');
        $rows = $this->db->get()->result();

        foreach ($rows as $row) {
            $info = $stok_awal_map[$row->id] ?? null;
            if ($is_future) {
                $row->stok_awal  = null;
                $row->masuk      = 0;
                $row->keluar     = 0;
                $row->stok_akhir = 0;
            } else {
                $base = $info ? (int) $info['base_saldo'] : 0;
                $row->stok_awal  = $info ? $info['stok_awal'] : null;
                $row->stok_akhir = $base + (int) $row->masuk - (int) $row->keluar;
            }
        }

        return $rows;
    }

    public function get_by_id($id) {
        $this->db->select('b.*, k.kode AS kategori_kode, k.nama AS kategori_nama, k.deskripsi AS kategori_deskripsi');
        $this->db->from($this->table . ' b');
        $this->db->join('master_kategori_persediaan k', 'k.id = b.kategori_id', 'left');
        $this->db->where('b.id', $id);
        return $this->db->get()->row();
    }

    public function get_kategori_grouped() {
        $this->db->select('k.id, k.kode, k.nama, k.deskripsi, COUNT(b.id) AS jumlah_barang');
        $this->db->from('master_kategori_persediaan k');
        $this->db->join('master_barang_persediaan b', 'b.kategori_id = k.id', 'left');
        $this->db->group_by('k.id');
        $this->db->order_by('k.kode', 'ASC');
        return $this->db->get()->result();
    }

    public function get_by_kategori($kategori_id) {
        $this->db->select('b.*, k.kode AS kategori_kode, k.nama AS kategori_nama, k.deskripsi AS kategori_deskripsi');
        $this->db->from($this->table . ' b');
        $this->db->join('master_kategori_persediaan k', 'k.id = b.kategori_id', 'left');
        $this->db->where('b.kategori_id', $kategori_id);
        $this->db->order_by('b.nama_barang', 'ASC');
        return $this->db->get()->result();
    }

    public function get_related_by_kategori($kategori_id, $exclude_id, $limit = 4) {
        $this->db->select('b.*, k.kode AS kategori_kode, k.nama AS kategori_nama, k.deskripsi AS kategori_deskripsi');
        $this->db->from($this->table . ' b');
        $this->db->join('master_kategori_persediaan k', 'k.id = b.kategori_id', 'left');
        $this->db->where('b.kategori_id', (int) $kategori_id);
        $this->db->where('b.id !=', (int) $exclude_id);
        $this->db->where('b.status', 'Aktif');
        $this->db->order_by('b.nama_barang', 'ASC');
        $this->db->limit(max(1, (int) $limit));
        return $this->db->get()->result();
    }

    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data) {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete($id) {
        return $this->db->where('id', $id)->delete($this->table);
    }

    public function check_duplicate($kategori_id, $nama_barang) {
        $this->db->where('kategori_id', $kategori_id);
        $this->db->where('nama_barang', $nama_barang);
        return $this->db->get($this->table)->num_rows() > 0;
    }

    public function check_duplicate_except($kategori_id, $nama_barang, $id) {
        $this->db->where('kategori_id', $kategori_id);
        $this->db->where('nama_barang', $nama_barang);
        $this->db->where('id !=', $id);
        return $this->db->get($this->table)->num_rows() > 0;
    }

    public function is_kode_used($kode, $except_id = NULL) {
        $this->db->where('kode_barang', $kode);
        if ($except_id) $this->db->where('id !=', $except_id);
        return $this->db->get($this->table)->num_rows() > 0;
    }

    public function next_kode($kategori_id, $except_id = NULL) {
        $this->db->select('k.kode AS kategori_kode');
        $this->db->from('master_kategori_persediaan k');
        $this->db->where('k.id', $kategori_id);
        $kat = $this->db->get()->row();
        if (!$kat || empty($kat->kategori_kode)) return NULL;
        $prefix = trim($kat->kategori_kode) . '-';

        $this->db->select('b.kode_barang');
        $this->db->from($this->table . ' b');
        $this->db->join('master_kategori_persediaan k', 'k.id = b.kategori_id', 'left');
        $this->db->where('b.kategori_id', $kategori_id);
        $this->db->like('b.kode_barang', $prefix, 'after');
        if ($except_id) $this->db->where('b.id !=', $except_id);
        $rows = $this->db->get()->result();

        $max = 0;
        $lengths = [];
        foreach ($rows as $r) {
            $kb = isset($r->kode_barang) ? $r->kode_barang : '';
            if (strpos($kb, $prefix) !== 0) continue;
            $suffix = substr($kb, strlen($prefix));
            if ($suffix === '' || !ctype_digit($suffix)) continue;
            $n = (int) $suffix;
            if ($n > $max) $max = $n;
            $lengths[] = strlen($suffix);
        }

        $next = $max + 1;
        if (!empty($lengths) && count(array_unique($lengths)) === 1) {
            $len = $lengths[0];
            $num = str_pad((string) $next, $len, '0', STR_PAD_LEFT);
        } else {
            $num = (string) $next;
        }
        return $prefix . $num;
    }

    /* =========================================================
       PROSES PENGAJUAN BARANG PERSEDIAAN KETIKA DISETUJUI / SELESAI
       Memasukkan barang pengajuan ke master_barang_persediaan (stok = 0)
    ========================================================= */
    public function proses_pengajuan_persediaan_selesai($ticket_id) {
        $ticket = $this->db->where('id', (int) $ticket_id)->get('tickets')->row_array();
        if (!$ticket) return ['status' => false, 'count' => 0];

        $is_pengajuan = false;
        if (!empty($ticket['sub_kategori_id'])) {
            $sub = $this->db->select('slug, nama_sub_kategori')
                            ->where('id', (int) $ticket['sub_kategori_id'])
                            ->get('master_sub_kategori')->row();
            if ($sub && (in_array($sub->slug, ['pengajuan-persediaan', 'pengajuan-barang-persediaan'], true)
                || stripos($sub->nama_sub_kategori, 'pengajuan') !== false)) {
                $is_pengajuan = true;
            }
        }
        if (!$is_pengajuan && strpos($ticket['jenis_kerusakan'] ?? '', '[PENGAJUAN BARANG PERSEDIAAN]') !== false) {
            $is_pengajuan = true;
        }

        if (!$is_pengajuan) return ['status' => false, 'count' => 0];

        $jk = (string) ($ticket['jenis_kerusakan'] ?? '');
        $items = [];

        // 1. Cek JSON jika ada
        if (preg_match('/\[pengajuan_items_json:\s*(.+?)\]/s', $jk, $mj)) {
            $parsed = json_decode($mj[1], true);
            if (is_array($parsed) && !empty($parsed)) {
                $items = $parsed;
            }
        }

        $all_kat = $this->db->get('master_kategori_persediaan')->result_array();
        $kat_map = [];
        $valid_kat_ids = [];
        foreach ($all_kat as $k) {
            $kat_map[$k['kode']] = (int) $k['id'];
            $valid_kat_ids[(int) $k['id']] = true;
        }
        $default_kat_id = $kat_map['ATK-10'] ?? ($all_kat[0]['id'] ?? 1);

        // 2. Fallback: Parse baris '- Nama [Kategori: KODE - Nama] (ket)'
        if (empty($items)) {
            $lines = explode("\n", $jk);
            $in_section = false;
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line === '') continue;
                if (strpos($line, '[PENGAJUAN BARANG PERSEDIAAN]') !== false) {
                    $in_section = true;
                    continue;
                }
                if ($in_section) {
                    if (preg_match('/^(Keperluan|Unit Kerja|Tanggal permintaan|Tanggal dibutuhkan|Total:|Catatan:)/i', $line)) {
                        break;
                    }
                    if (strpos($line, '-') === 0) {
                        $raw = trim(substr($line, 1));
                        if ($raw === '') continue;
                        $parsed_kat_id = null;
                        if (preg_match('/\[Kategori:\s*([A-Za-z0-9\-]+)/i', $raw, $km)) {
                            $kat_code = trim($km[1]);
                            if (isset($kat_map[$kat_code])) {
                                $parsed_kat_id = $kat_map[$kat_code];
                            }
                            $raw = trim(preg_replace('/\[Kategori:[^\]]+\]/i', '', $raw));
                        }
                        $parsed_img = null;
                        if (preg_match('/\[img:\s*([^\]]+)\]/i', $raw, $img_m)) {
                            $parsed_img = trim($img_m[1]);
                            $raw = trim(preg_replace('/\[img:[^\]]+\]/i', '', $raw));
                        }
                        if (preg_match('/^(.*?)(?:\s*\((.*?)\))?$/', $raw, $m)) {
                            $nm = trim($m[1]);
                            $kt = isset($m[2]) ? trim($m[2]) : '';
                            if ($nm !== '') {
                                $item_entry = ['nama' => $nm, 'ket' => $kt];
                                if ($parsed_kat_id) $item_entry['kategori_id'] = $parsed_kat_id;
                                if ($parsed_img) $item_entry['gambar'] = $parsed_img;
                                $items[] = $item_entry;
                            }
                        }
                    }
                }
            }
        }

        if (empty($items)) return ['status' => false, 'count' => 0];

        $this->db->trans_begin();
        $added_count = 0;
        $bulan_ini   = (int) date('n');
        $tahun_ini   = (int) date('Y');

        foreach ($items as $it) {
            $raw_name = trim($it['nama'] ?? '');
            if ($raw_name === '') continue;

            $clean_name = preg_replace('/\s*x\s*\d+\s*(?:pcs|buah|rim|box|kotak|botol|pack|bungkus|lembar|gulung|lusin|meter|set|unit|roll|bks)?\b/i', '', $raw_name);
            $clean_name = trim($clean_name);
            if ($clean_name === '') $clean_name = $raw_name;

            // Deteksi satuan
            $satuan = 'pcs';
            if (preg_match('/\b(pcs|buah|rim|box|kotak|botol|pack|bungkus|lembar|gulung|lusin|meter|set|unit|roll|bks)\b/i', $raw_name . ' ' . ($it['ket'] ?? ''), $sat_m)) {
                $satuan = ucfirst(strtolower($sat_m[1]));
            }

            // Kategori: prioritas dari pengajuan user
            $target_kat_id = null;
            if (!empty($it['kategori_id']) && isset($valid_kat_ids[(int) $it['kategori_id']])) {
                $target_kat_id = (int) $it['kategori_id'];
            }

            if (!$target_kat_id) {
                // Klasifikasi kategori otomatis dari keyword
                $target_kat_id = $default_kat_id;
                $haystack = strtolower($clean_name . ' ' . ($it['ket'] ?? ''));

                $keyword_map = [
                    'ATK-14' => ['tinta', 'toner', 'cartridge', 'ribbon'],
                    'ATK-11' => ['hvs'],
                    'ATK-12' => ['concord', 'glossy', 'padi', 'karton', 'buffalo'],
                    'ATK-13' => ['amplop'],
                    'ATK-01' => ['pena', 'pulpen', 'pensil', 'spidol', 'stabilo', 'penggaris', 'rautan'],
                    'ATK-02' => ['stempel', 'bak stempel'],
                    'ATK-03' => ['binder', 'klip', 'clip', 'trigonal', 'pelubang'],
                    'ATK-04' => ['tipp-ex', 'tippex', 'tipex', 'penghapus'],
                    'ATK-05' => ['buku tulis', 'buku folio', 'buku ekspedisi', 'buku'],
                    'ATK-06' => ['map', 'ordner', 'spring file', 'box file', 'snelhecter'],
                    'ATK-07' => ['cutter', 'pisau'],
                    'ATK-08' => ['lakban', 'tape', 'lem', 'perekat'],
                    'ATK-09' => ['staples', 'hekter', 'stapler'],
                    'ARK-01' => ['usb', 'flashdisk', 'flash disk', 'otg'],
                    'ARK-02' => ['mouse', 'keyboard', 'webcam', 'headset', 'headphone', 'earphone'],
                    'ARK-03' => ['kabel jaringan', 'rj45', 'lan', 'hdmi', 'vga', 'kabel data'],
                    'ARK-04' => ['sapu', 'sikat'],
                    'ARK-05' => ['kain pel', 'wiper', 'pel'],
                    'ARK-06' => ['ember', 'gayung', 'slang', 'selang'],
                    'ARK-07' => ['kunci', 'gembok', 'kran', 'keran'],
                    'ARK-08' => ['sabun', 'karbol', 'pembersih', 'wipol', 'porstex', 'cling', 'so klin'],
                    'ARK-09' => ['pengharum', 'dahlia', 'bayfresh', 'stella', 'glade'],
                    'ARK-10' => ['tisu', 'tissue', 'spons', 'sarung tangan'],
                    'ARK-11' => ['kabel listrik', 'lampu', 'bohlam', 'led', 'fitting', 'starter'],
                    'ARK-12' => ['stop kontak', 'saklar', 'cok', 'sambung', 'colokan', 'steker'],
                    'ARK-13' => ['baterai', 'battery'],
                    'ARK-14' => ['tespen', 'tang', 'obeng', 'isolasi'],
                ];

                foreach ($keyword_map as $kode_kat => $words) {
                    foreach ($words as $w) {
                        if (strpos($haystack, $w) !== false && isset($kat_map[$kode_kat])) {
                            $target_kat_id = $kat_map[$kode_kat];
                            break 2;
                        }
                    }
                }
            }

            // Cek apakah barang sudah ada di master (case-insensitive)
            $existing = $this->db->where('LOWER(TRIM(nama_barang))', strtolower($clean_name))
                                 ->get($this->table)->row();

            if ($existing) {
                $barang_id = $existing->id;
                $upd_existing = [];
                if ($existing->status !== 'Aktif') {
                    $upd_existing['status'] = 'Aktif';
                }
                if (empty($existing->gambar) && !empty($it['gambar'])) {
                    $upd_existing['gambar'] = $it['gambar'];
                }
                if (!empty($upd_existing)) {
                    $this->db->where('id', $existing->id)->update($this->table, $upd_existing);
                }
            } else {
                $kode = $this->next_kode($target_kat_id);
                if (!$kode) {
                    $kode = 'ATK-' . strtoupper(substr(uniqid(), -6));
                }
                $tries = 0;
                while ($this->is_kode_used($kode) && $tries < 20) {
                    $pos = strrpos($kode, '-');
                    $suf = ($pos === FALSE) ? '' : substr($kode, $pos + 1);
                    if ($suf === '' || !ctype_digit($suf)) break;
                    $kode = substr($kode, 0, $pos + 1) . str_pad((string) ((int) $suf + 1), strlen($suf), '0', STR_PAD_LEFT);
                    $tries++;
                }

                $this->db->insert($this->table, [
                    'kategori_id'  => $target_kat_id,
                    'kode_barang'  => $kode,
                    'nama_barang'  => $clean_name,
                    'satuan'       => $satuan,
                    'gambar'       => !empty($it['gambar']) ? $it['gambar'] : null,
                    'stok_minimum' => 0,
                    'status'       => 'Aktif',
                    'created_at'   => date('Y-m-d H:i:s'),
                ]);
                if (!$this->db->affected_rows()) {
                    $this->db->trans_rollback();
                    return ['status' => false, 'count' => 0];
                }
                $barang_id = $this->db->insert_id();
                $added_count++;
            }

            // Pastikan stok awal periode saat ini = 0 jika belum ada
            if ($barang_id) {
                $cek_sa = $this->db->where('barang_id', $barang_id)
                                   ->where('bulan', $bulan_ini)
                                   ->where('tahun', $tahun_ini)
                                   ->get('stok_awal_persediaan')->num_rows();
                if ($cek_sa == 0) {
                    $this->db->insert('stok_awal_persediaan', [
                        'barang_id'  => $barang_id,
                        'bulan'      => $bulan_ini,
                        'tahun'      => $tahun_ini,
                        'jumlah'     => 0,
                        'keterangan' => 'Pengajuan disetujui (' . ($ticket['kode_tiket'] ?? '') . ')',
                    ]);
                    if (!$this->db->affected_rows()) {
                        $this->db->trans_rollback();
                        return ['status' => false, 'count' => 0];
                    }
                }
            }
        }

        if (!$this->db->trans_status()) {
            $this->db->trans_rollback();
            return ['status' => false, 'count' => 0];
        }
        $this->db->trans_commit();
        return ['status' => true, 'count' => $added_count];
    }
}
