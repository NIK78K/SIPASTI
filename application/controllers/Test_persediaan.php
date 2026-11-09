<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_persediaan extends CI_Controller {

    private $passed = 0;
    private $failed = 0;
    private $tests = 0;

    public function __construct() {
        parent::__construct();
        if (!is_cli()) {
            show_404();
            return;
        }
        $this->load->database();
        $this->load->model([
            'Barang_persediaan_model',
            'Kategori_persediaan_model',
            'Stok_awal_persediaan_model',
            'Transaksi_persediaan_model',
            'User_ticket_model'
        ]);
    }

    private function assert($condition, $message) {
        $this->tests++;
        if ($condition) {
            $this->passed++;
            echo "  \033[32m✔ PASS\033[0m: {$message}\n";
        } else {
            $this->failed++;
            echo "  \033[31m✘ FAIL\033[0m: {$message}\n";
        }
    }

    public function index() {
        echo "\n=======================================================\n";
        echo "   SUITE TEST: SISTEM PERSEDIAAN BARANG (UNIT & FEATURE)\n";
        echo "=======================================================\n\n";

        $startTime = microtime(true);

        $this->run_unit_tests();
        $this->run_feature_tests();

        $elapsed = round((microtime(true) - $startTime) * 1000, 2);

        echo "\n-------------------------------------------------------\n";
        echo "HASIL PENGUJIAN:\n";
        echo "Total Test : {$this->tests}\n";
        echo "Berhasil   : \033[32m{$this->passed}\033[0m\n";
        echo "Gagal      : " . ($this->failed > 0 ? "\033[31m{$this->failed}\033[0m" : "\033[32m0\033[0m") . "\n";
        echo "Waktu      : {$elapsed} ms\n";
        echo "-------------------------------------------------------\n\n";

        if ($this->failed > 0) {
            exit(1);
        }
    }

    private function run_unit_tests() {
        echo "\033[34m[UNIT TESTS: MODEL & LOGIKA BISNIS]\033[0m\n";

        // 1. Test next_kode
        $allKat = $this->db->get('master_kategori_persediaan')->result_array();
        $this->assert(!empty($allKat), 'Kategori persediaan master tersedia di database');
        
        $kat = $allKat[0];
        $katId = (int) $kat['id'];
        $katKode = trim($kat['kode']);

        $nextKode = $this->Barang_persediaan_model->next_kode($katId);
        $this->assert(!empty($nextKode), "Barang_persediaan_model::next_kode menghasilkan kode untuk kategori {$katKode}: {$nextKode}");
        $this->assert(strpos($nextKode, $katKode . '-') === 0, "Format next_kode diawali dengan prefix kategori '{$katKode}-'");
        $this->assert(!$this->Barang_persediaan_model->is_kode_used($nextKode), "next_kode yang dihasilkan belum terpakai di database");

        // 2. Test is_kode_used
        $existingBarang = $this->db->get('master_barang_persediaan', 1)->row();
        if ($existingBarang) {
            $this->assert(
                $this->Barang_persediaan_model->is_kode_used($existingBarang->kode_barang),
                "is_kode_used mengenali kode barang yang ada ({$existingBarang->kode_barang})"
            );
            $this->assert(
                !$this->Barang_persediaan_model->is_kode_used($existingBarang->kode_barang, $existingBarang->id),
                "is_kode_used mengabaikan ID yang dikecualikan (except_id)"
            );
        }
        $this->assert(
            !$this->Barang_persediaan_model->is_kode_used('KODE-IMPOSSIBLE-999999'),
            "is_kode_used bernilai false untuk kode acak fiktif"
        );

        // 3. Test check_duplicate
        if ($existingBarang) {
            $isDup = $this->Barang_persediaan_model->check_duplicate($existingBarang->kategori_id, $existingBarang->nama_barang);
            $this->assert($isDup, "check_duplicate mendeteksi nama barang yang sama dalam satu kategori");

            $isNotDup = $this->Barang_persediaan_model->check_duplicate_except($existingBarang->kategori_id, $existingBarang->nama_barang, $existingBarang->id);
            $this->assert(!$isNotDup, "check_duplicate_except mengabaikan ID barang yang sedang diedit");
        }

        // 4. Test saldo dan perhitungan sisa persediaan
        $curBulan = (int) date('n');
        $curTahun = (int) date('Y');
        if ($existingBarang) {
            $sisaCur = $this->User_ticket_model->get_sisa_persediaan($existingBarang->id, $curBulan, $curTahun);
            $this->assert(is_numeric($sisaCur), "get_sisa_persediaan periode berjalan menghasilkan angka numerik: {$sisaCur}");

            $sisaFuture = $this->User_ticket_model->get_sisa_persediaan($existingBarang->id, $curBulan, $curTahun + 2);
            $this->assert($sisaFuture === 0, "get_sisa_persediaan periode masa depan otomatis reset ke 0");
        }

        // 5. Test katalog persediaan agregasi disetujui (metrik disetujui)
        $katalog = $this->User_ticket_model->get_katalog_persediaan_with_sisa($curBulan, $curTahun);
        $this->assert(is_array($katalog) && !empty($katalog), "get_katalog_persediaan_with_sisa memuat daftar katalog persediaan");

        $item0 = $katalog[0];
        $this->assert(array_key_exists('sisa', $item0), "Item katalog memiliki field 'sisa'");
        $this->assert(array_key_exists('total_disetujui', $item0), "Item katalog memiliki field 'total_disetujui'");
        $this->assert(array_key_exists('total_terlaris', $item0), "Item katalog memiliki field 'total_terlaris'");
        $this->assert($item0['total_disetujui'] === $item0['total_terlaris'], "total_disetujui tersinkron dengan metrik terlaris");

        // 6. Test ulasan barang model
        if ($existingBarang) {
            $ulasan = $this->User_ticket_model->get_ulasan_barang($existingBarang->id);
            $this->assert(is_array($ulasan), "get_ulasan_barang mengembalikan struktur array ulasan");
            $this->assert(isset($ulasan['rating_avg']), "Data ulasan memiliki 'rating_avg'");
            $this->assert(isset($ulasan['star_counts']) && is_array($ulasan['star_counts']), "Data ulasan memiliki rincian bintang 1-5");
        }

        echo "\n";
    }

    private function run_feature_tests() {
        echo "\033[34m[FEATURE TESTS: ALUR PENGAJUAN & PERMINTAAN PERSEDIAAN]\033[0m\n";

        $this->db->trans_start(); // Mulai transaksi isolasi test

        try {
            // Ambil user tester atau user pertama
            $user = $this->db->get('users', 1)->row();
            $userId = $user ? (int) $user->id : 1;
            $userName = $user ? $user->name : 'Tester';

            $allKat = $this->db->get('master_kategori_persediaan')->result_array();
            $kat = $allKat[0];
            $katId = (int) $kat['id'];
            $katNama = $kat['kode'] . ' - ' . $kat['nama'];

            $subPengajuan = $this->User_ticket_model->ensure_pengajuan_persediaan_sub();
            $this->assert(!empty($subPengajuan), "ensure_pengajuan_persediaan_sub memastikan subkategori pengajuan tersedia");

            $subPermintaan = $this->User_ticket_model->ensure_persediaan_sub();
            $this->assert(!empty($subPermintaan), "ensure_persediaan_sub memastikan subkategori permintaan tersedia");

            // ================================================================
            // FITUR 1: ALUR PENGAJUAN BARANG PERSEDIAAN (AUTO-GENERATE KODE)
            // ================================================================
            echo "  \033[33m--- Skenario 1: Pengajuan Barang Persediaan Baru ---\033[0m\n";

            $kodeTest = $this->Barang_persediaan_model->next_kode($katId);
            $namaBarangTest = 'TEST BARANG UNIK ' . time() . ' ' . mt_rand(100, 999);

            // Simulasikan pembuatan pengajuan seperti di User_ticket::pengajuan_persediaan
            $this->db->insert('master_barang_persediaan', [
                'kategori_id'  => $katId,
                'kode_barang'  => $kodeTest,
                'nama_barang'  => $namaBarangTest,
                'satuan'       => 'Pcs',
                'gambar'       => null,
                'stok_minimum' => 0,
                'status'       => 'Nonaktif',
                'created_at'   => date('Y-m-d H:i:s'),
            ]);
            $insertedBarangId = $this->db->insert_id();

            $this->assert($insertedBarangId > 0, "Barang pengajuan berhasil terdaftar sementara di master_barang_persediaan");

            $cekMaster = $this->db->where('id', $insertedBarangId)->get('master_barang_persediaan')->row();
            $this->assert($cekMaster->status === 'Nonaktif', "Status awal barang saat diajukan adalah 'Nonaktif' (belum tampil di katalog belanja user)");
            $this->assert($cekMaster->kode_barang === $kodeTest, "Kode barang terkunci di master: {$kodeTest}");

            // Buat tiket pengajuan
            $testKodeTiket = 'TEST-TKT-' . date('YmdHis');
            $itemsJson = [
                [
                    'barang_id'     => $insertedBarangId,
                    'kode'          => $kodeTest,
                    'nama'          => $namaBarangTest,
                    'kategori_id'   => $katId,
                    'kategori_nama' => $katNama,
                    'ket'           => 'Spesifikasi pengujian unit',
                    'gambar'        => null,
                ]
            ];

            $ringkasPengajuan = [
                '[PENGAJUAN BARANG PERSEDIAAN]',
                "- [{$kodeTest}] {$namaBarangTest} [Kategori: {$katNama}] (Spesifikasi pengujian unit)",
                '',
                'Keperluan: Pengujian unit test persediaan',
                'Unit Kerja: Bagian Pengujian TI',
                'Tanggal permintaan: ' . date('Y-m-d'),
                'Total: 1 jenis diajukan',
                'Catatan: barang belum ada di daftar persediaan, menunggu verifikasi admin/teknisi.',
                '[pengajuan_items_json:' . json_encode($itemsJson) . ']'
            ];

            $katPermintaanId = $this->User_ticket_model->get_kategori_id_by_slug('permintaan');

            $this->db->insert('tickets', [
                'user_id'         => $userId,
                'pelapor'         => $userName,
                'kode_tiket'      => $testKodeTiket,
                'kategori_id'     => $katPermintaanId,
                'sub_kategori_id' => $subPengajuan->id,
                'jenis_kerusakan' => implode("\n", $ringkasPengajuan),
                'kebutuhan'       => 'Pengujian unit test persediaan',
                'status'          => 'Menunggu',
                'created_at'      => date('Y-m-d H:i:s'),
            ]);
            $ticketPengajuanId = $this->db->insert_id();
            $this->assert($ticketPengajuanId > 0, "Tiket pengajuan tersimpan dengan kode tiket: {$testKodeTiket}");

            // Verifikasi metadata pengajuan diekstrak dengan kode barang
            $savedTicket = $this->db->where('id', $ticketPengajuanId)->get('tickets')->row_array();
            $this->assert(strpos($savedTicket['jenis_kerusakan'], "[{$kodeTest}]") !== false, "Ringkasan tiket menyimpan kode barang yang di-generate");

            // Skenario ACC / Disetujui: proses_pengajuan_persediaan_selesai
            $resAcc = $this->Barang_persediaan_model->proses_pengajuan_persediaan_selesai($ticketPengajuanId);
            $this->assert($resAcc['status'] === true && $resAcc['count'] > 0, "proses_pengajuan_persediaan_selesai berhasil memproses tiket pengajuan");

            $barangAktif = $this->db->where('id', $insertedBarangId)->get('master_barang_persediaan')->row();
            $this->assert($barangAktif->status === 'Aktif', "Status barang di master berubah menjadi 'Aktif' setelah pengajuan disetujui");

            $stokAwal = $this->db->where('barang_id', $insertedBarangId)
                                 ->where('bulan', (int) date('n'))
                                 ->where('tahun', (int) date('Y'))
                                 ->get('stok_awal_persediaan')->row();
            $this->assert(!empty($stokAwal) && (int) $stokAwal->jumlah === 0, "Stok awal periode saat ini diinisialisasi otomatis dengan kuantitas 0");

            // ================================================================
            // FITUR 2: ALUR PERMINTAAN PERSEDIAAN & METRIK DISETUJUI
            // ================================================================
            echo "\n  \033[33m--- Skenario 2: Permintaan Barang Persediaan & Metrik Disetujui ---\033[0m\n";

            $barangKatalog = $this->db->where('status', 'Aktif')->get('master_barang_persediaan', 1)->row();
            $this->assert(!empty($barangKatalog), "Tersedia barang aktif di katalog: {$barangKatalog->nama_barang}");
            $bKatalogId = (int) $barangKatalog->id;

            // Hitung metrik disetujui awal untuk barang ini
            $hitungDisetujui = function($bId) {
                $q = $this->db->query("
                    SELECT COALESCE(SUM(
                        CASE 
                            WHEN t.status = 'Selesai' THEN 
                                CASE 
                                    WHEN i.jumlah_disetujui IS NOT NULL AND i.jumlah_disetujui != '' THEN CAST(i.jumlah_disetujui AS SIGNED)
                                    ELSE i.jumlah 
                                END
                            WHEN t.status = 'On Progress' AND i.jumlah_disetujui IS NOT NULL AND i.jumlah_disetujui != '' THEN 
                                CAST(i.jumlah_disetujui AS SIGNED)
                            ELSE 0 
                        END
                    ), 0) AS total_disetujui
                    FROM ticket_persediaan_items i
                    JOIN tickets t ON t.id = i.ticket_id
                    WHERE i.barang_id = ? AND t.status NOT IN ('Menunggu', 'Dibatalkan')
                ", [$bId])->row();
                return $q ? (int) $q->total_disetujui : 0;
            };

            $disetujuiAwal = $hitungDisetujui($bKatalogId);

            // User membuat tiket permintaan persediaan (Status: Menunggu)
            $testKodePermintaan = 'TEST-REQ-' . date('YmdHis');
            $this->db->insert('tickets', [
                'user_id'         => $userId,
                'pelapor'         => $userName,
                'kode_tiket'      => $testKodePermintaan,
                'kategori_id'     => $katPermintaanId,
                'sub_kategori_id' => $subPermintaan->id,
                'jenis_kerusakan' => "[PERMINTAAN BARANG PERSEDIAAN]\n- {$barangKatalog->kode_barang} - {$barangKatalog->nama_barang} x 5 {$barangKatalog->satuan}",
                'kebutuhan'       => 'Pengujian permintaan barang',
                'status'          => 'Menunggu',
                'created_at'      => date('Y-m-d H:i:s'),
            ]);
            $ticketPermintaanId = $this->db->insert_id();

            $this->db->insert('ticket_persediaan_items', [
                'ticket_id'            => $ticketPermintaanId,
                'barang_id'            => $bKatalogId,
                'jumlah'               => 5,
                'jumlah_disetujui'     => null,
                'nama_barang_snapshot' => $barangKatalog->nama_barang,
                'satuan_snapshot'      => $barangKatalog->satuan,
                'created_at'           => date('Y-m-d H:i:s')
            ]);
            $itemId = $this->db->insert_id();

            // Verifikasi metrik saat tiket masih Menunggu
            $disetujuiSaatMenunggu = $hitungDisetujui($bKatalogId);
            $this->assert(
                $disetujuiSaatMenunggu === $disetujuiAwal,
                "Tiket status 'Menunggu' TIDAK menambah angka total disetujui (Awal: {$disetujuiAwal}, Saat Menunggu: {$disetujuiSaatMenunggu})"
            );

            // Teknisi ACC permintaan: set disetujui = 3 (dari 5 diminta) dan ubah status ke Selesai
            $this->db->where('id', $itemId)->update('ticket_persediaan_items', [
                'jumlah_disetujui' => 3,
                'keterangan'       => 'Disetujui 3 unit oleh tim'
            ]);
            $this->db->where('id', $ticketPermintaanId)->update('tickets', [
                'status'     => 'Selesai',
                'selesai_at' => date('Y-m-d H:i:s')
            ]);

            $disetujuiSetelahAcc = $hitungDisetujui($bKatalogId);
            $this->assert(
                $disetujuiSetelahAcc === ($disetujuiAwal + 3),
                "Setelah tiket disetujui/selesai, total disetujui bertambah tepat sejumlah kuantitas ACC (Sebelum: {$disetujuiAwal}, Sesudah: {$disetujuiSetelahAcc})"
            );

            // ================================================================
            // FITUR 3: PENGUJIAN HELPER LOGIKA VIEW (HISTORY & DETAIL)
            // ================================================================
            echo "\n  \033[33m--- Skenario 3: Helper View & Parsing Metadata ---\033[0m\n";

            // Test parsing ringkasan pengajuan
            $rawJk = implode("\n", $ringkasPengajuan);
            $parsedJson = null;
            if (preg_match('/\[pengajuan_items_json:\s*(.+?)\s*\](?:\r?\n|$)/s', $rawJk, $mj)) {
                $parsedJson = json_decode($mj[1], true);
            }
            $this->assert(is_array($parsedJson) && count($parsedJson) === 1, "Metadata [pengajuan_items_json:...] berhasil di-decode ke array item");
            $this->assert($parsedJson[0]['kode'] === $kodeTest, "Kode barang pada metadata JSON cocok: {$kodeTest}");
            $this->assert($parsedJson[0]['nama'] === $namaBarangTest, "Nama barang pada metadata JSON cocok: {$namaBarangTest}");

            // ================================================================
            // FITUR 4: SKENARIO PENGAJUAN DITOLAK / DIBATALKAN (NOMOR LOMPAT)
            // ================================================================
            echo "\n  \033[33m--- Skenario 4: Pengajuan Dibatalkan & Nomor Melompat ---\033[0m\n";

            $kodeBatal = $this->Barang_persediaan_model->next_kode($katId);
            $this->db->insert('master_barang_persediaan', [
                'kategori_id'  => $katId,
                'kode_barang'  => $kodeBatal,
                'nama_barang'  => 'BARANG YANG AKAN DIBATALKAN ' . time(),
                'satuan'       => 'Pcs',
                'gambar'       => null,
                'stok_minimum' => 0,
                'status'       => 'Nonaktif',
                'created_at'   => date('Y-m-d H:i:s'),
            ]);
            $batalBarangId = $this->db->insert_id();

            // Tiket dibatalkan
            $this->db->insert('tickets', [
                'user_id'         => $userId,
                'pelapor'         => $userName,
                'kode_tiket'      => 'TEST-BATAL-' . date('YmdHis'),
                'kategori_id'     => $katPermintaanId,
                'sub_kategori_id' => $subPengajuan->id,
                'jenis_kerusakan' => "[PENGAJUAN BARANG PERSEDIAAN]\n- [{$kodeBatal}] BARANG YANG AKAN DIBATALKAN",
                'kebutuhan'       => 'Kebutuhan dibatalkan',
                'status'          => 'Dibatalkan',
                'created_at'      => date('Y-m-d H:i:s'),
            ]);

            $cekBatal = $this->db->where('id', $batalBarangId)->get('master_barang_persediaan')->row();
            $this->assert($cekBatal->status === 'Nonaktif', "Barang dari pengajuan yang dibatalkan tetap berstatus 'Nonaktif'");

            // Kode berikutnya tidak boleh sama dengan kode yang sudah dibatalkan (nomor urut lompat)
            $kodeBerikutnya = $this->Barang_persediaan_model->next_kode($katId);
            $this->assert($kodeBerikutnya !== $kodeBatal, "Kode pengajuan berikutnya ({$kodeBerikutnya}) tidak memakai ulang kode yang dibatalkan ({$kodeBatal})");

            // ================================================================
            // FITUR 5: MUTASI PENGELUARAN BARANG & PERHITUNGAN SISA STOK
            // ================================================================
            echo "\n  \033[33m--- Skenario 5: Mutasi Transaksi Pengeluaran Barang ---\033[0m\n";

            $curBulan = (int) date('n');
            $curTahun = (int) date('Y');
            $sisaSebelum = (int) $this->User_ticket_model->get_sisa_persediaan($bKatalogId, $curBulan, $curTahun);

            // Masukkan transaksi pengeluaran 2 unit
            $this->db->insert('transaksi_persediaan', [
                'barang_id'       => $bKatalogId,
                'jenis'           => 'keluar',
                'tanggal'         => date('Y-m-d'),
                'jumlah'          => 2,
                'uraian'          => 'Unit test transaksi keluar',
                'penerima_sumber' => $userName,
                'no_dokumen'      => 'TEST-DOC-' . time(),
            ]);

            $sisaSesudah = (int) $this->User_ticket_model->get_sisa_persediaan($bKatalogId, $curBulan, $curTahun);
            $this->assert($sisaSesudah === ($sisaSebelum - 2), "Sisa stok berkurang tepat 2 unit setelah transaksi keluar dicatat (Awal: {$sisaSebelum}, Akhir: {$sisaSesudah})");

        } catch (Exception $e) {
            $this->assert(false, "Exception error saat pengujian: " . $e->getMessage());
        } finally {
            // Rollback semua data test agar database tetap bersih seperti semula
            $this->db->trans_rollback();
            echo "  \033[36mℹ INFO\033[0m: Transaksi database test di-rollback. Database produksi bersih.\n";
        }
    }
}
