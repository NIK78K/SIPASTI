<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;
use App\Models\Kategori;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        // Helper: ambil ID kategori berdasarkan kode
        $kat = fn(string $kode) => Kategori::where('kode', $kode)->value('id');

        $barangs = [
            // === ATK-01: ALAT TULIS ===
            ['kode_barang' => 'ATK-01-002',    'nama_barang' => 'Stabilo',                     'satuan' => 'Buah',     'kategori_id' => $kat('ATK-01')],
            ['kode_barang' => 'ATK-01-100128', 'nama_barang' => 'Pena Unibal signo-biru',      'satuan' => 'Buah',     'kategori_id' => $kat('ATK-01')],
            ['kode_barang' => 'ATK-01-100129', 'nama_barang' => 'Spidol Permanen',             'satuan' => 'Buah',     'kategori_id' => $kat('ATK-01')],
            ['kode_barang' => 'ATK-01-100141', 'nama_barang' => 'Pena Meja',                   'satuan' => 'Buah',     'kategori_id' => $kat('ATK-01')],
            ['kode_barang' => 'ATK-01-100146', 'nama_barang' => 'Penggaris Besi 30 cm',        'satuan' => 'Buah',     'kategori_id' => $kat('ATK-01')],
            ['kode_barang' => 'ATK-01-100150', 'nama_barang' => 'Pena uniball signo-hitam',    'satuan' => 'Buah',     'kategori_id' => $kat('ATK-01')],
            ['kode_barang' => 'ATK-01-100163', 'nama_barang' => 'Pena merah',                  'satuan' => 'Kotak',    'kategori_id' => $kat('ATK-01')],
            ['kode_barang' => 'ATK-01-100165', 'nama_barang' => 'Pena Kenko Easy Gell',        'satuan' => 'Kotak',    'kategori_id' => $kat('ATK-01')],
            ['kode_barang' => 'ATK-01-100171', 'nama_barang' => 'Spidol Non Permanent',        'satuan' => 'Buah',     'kategori_id' => $kat('ATK-01')],
            ['kode_barang' => 'ATK-01-100172', 'nama_barang' => 'Pena 4 warna',                'satuan' => 'Buah',     'kategori_id' => $kat('ATK-01')],
            ['kode_barang' => 'ATK-01-100173', 'nama_barang' => 'Pena Kenko 2',                'satuan' => 'Buah',     'kategori_id' => $kat('ATK-01')],
            ['kode_barang' => 'ATK-01-100174', 'nama_barang' => 'Pensil 2B',                   'satuan' => 'Buah',     'kategori_id' => $kat('ATK-01')],
            ['kode_barang' => 'ATK-01-100175', 'nama_barang' => 'Pena Standar',                'satuan' => 'Buah',     'kategori_id' => $kat('ATK-01')],
            ['kode_barang' => 'ATK-01-100181', 'nama_barang' => 'Pena Kenko K1',               'satuan' => 'Pcs',      'kategori_id' => $kat('ATK-01')],
            ['kode_barang' => 'ATK-01-100182', 'nama_barang' => 'Pena Kenko K-1',              'satuan' => 'Lusin',    'kategori_id' => $kat('ATK-01')],

            // === ATK-02: TINTA & STEMPEL ===
            ['kode_barang' => 'ATK-02-001',    'nama_barang' => 'Tinta Stempel',               'satuan' => 'Botol',    'kategori_id' => $kat('ATK-02')],

            // === ATK-03: PENJEPIT KERTAS ===
            ['kode_barang' => 'ATK-03-002',    'nama_barang' => 'BINDER KLIP NO.155',          'satuan' => 'Kotak',    'kategori_id' => $kat('ATK-03')],
            ['kode_barang' => 'ATK-03-003',    'nama_barang' => 'BINDER KLIP NO.107',          'satuan' => 'Kotak',    'kategori_id' => $kat('ATK-03')],
            ['kode_barang' => 'ATK-03-005',    'nama_barang' => 'Pelubang Kertas',             'satuan' => 'Buah',     'kategori_id' => $kat('ATK-03')],
            ['kode_barang' => 'ATK-03-3011',   'nama_barang' => 'Binder Klip no 260',          'satuan' => 'Kotak',    'kategori_id' => $kat('ATK-03')],
            ['kode_barang' => 'ATK-03-3016',   'nama_barang' => 'Trigonal Clip',               'satuan' => 'Kotak',    'kategori_id' => $kat('ATK-03')],
            ['kode_barang' => 'ATK-03-3021',   'nama_barang' => 'BINDER KLIP 111',             'satuan' => 'Kotak',    'kategori_id' => $kat('ATK-03')],

            // === ATK-04: PENGHAPUS & KOREKTOR ===
            ['kode_barang' => 'ATK-04-002',    'nama_barang' => 'TIPP EX',                     'satuan' => 'Buah',     'kategori_id' => $kat('ATK-04')],
            ['kode_barang' => 'ATK-04-003',    'nama_barang' => 'Penghapus',                   'satuan' => 'Buah',     'kategori_id' => $kat('ATK-04')],

            // === ATK-05: BUKU TULIS ===
            ['kode_barang' => 'ATK-05-100510', 'nama_barang' => 'Buku Folio',                  'satuan' => 'Buah',     'kategori_id' => $kat('ATK-05')],
            ['kode_barang' => 'ATK-05-100514', 'nama_barang' => 'Buku Expedisi Surat',         'satuan' => 'Buah',     'kategori_id' => $kat('ATK-05')],

            // === ATK-06: ORDNER & MAP ===
            ['kode_barang' => 'ATK-06-005',    'nama_barang' => 'MAP LOGO BPS',                'satuan' => 'Buah',     'kategori_id' => $kat('ATK-06')],
            ['kode_barang' => 'ATK-06-100607', 'nama_barang' => 'Spring File',                 'satuan' => 'Buah',     'kategori_id' => $kat('ATK-06')],
            ['kode_barang' => 'ATK-06-100618', 'nama_barang' => 'Box file',                    'satuan' => 'Buah',     'kategori_id' => $kat('ATK-06')],
            ['kode_barang' => 'ATK-06-100621', 'nama_barang' => 'Map Biola',                   'satuan' => 'Buah',     'kategori_id' => $kat('ATK-06')],
            ['kode_barang' => 'ATK-06-100637', 'nama_barang' => 'Map Bantex Real',             'satuan' => 'Buah',     'kategori_id' => $kat('ATK-06')],
            ['kode_barang' => 'ATK-06-100638', 'nama_barang' => 'Map Combo 401',               'satuan' => 'Buah',     'kategori_id' => $kat('ATK-06')],
            ['kode_barang' => 'ATK-06-100643', 'nama_barang' => 'Map Lidah',                   'satuan' => 'Buah',     'kategori_id' => $kat('ATK-06')],
            ['kode_barang' => 'ATK-06-100647', 'nama_barang' => 'MAP COMBO 402',               'satuan' => 'Pcs',      'kategori_id' => $kat('ATK-06')],
            ['kode_barang' => 'ATK-06-100649', 'nama_barang' => 'Business File Merah',         'satuan' => 'Pcs',      'kategori_id' => $kat('ATK-06')],

            // === ATK-07: CUTTER ===
            ['kode_barang' => 'ATK-07-001',    'nama_barang' => 'PISAU CUTTER',                'satuan' => 'Buah',     'kategori_id' => $kat('ATK-07')],
            ['kode_barang' => 'ATK-07-002',    'nama_barang' => 'Anak Pisau Cutter',           'satuan' => 'Kotak',    'kategori_id' => $kat('ATK-07')],

            // === ATK-08: ALAT PEREKAT ===
            ['kode_barang' => 'ATK-08-003',    'nama_barang' => "Lakban Bening 2''",           'satuan' => 'Buah',     'kategori_id' => $kat('ATK-08')],
            ['kode_barang' => 'ATK-08-004',    'nama_barang' => "Lakban Bening Kecil 1''",     'satuan' => 'Buah',     'kategori_id' => $kat('ATK-08')],
            ['kode_barang' => 'ATK-08-10008',  'nama_barang' => 'Lakban Cokelat',              'satuan' => 'Buah',     'kategori_id' => $kat('ATK-08')],
            ['kode_barang' => 'ATK-08-10014',  'nama_barang' => "Lakban Hitam 2''",            'satuan' => 'Buah',     'kategori_id' => $kat('ATK-08')],
            ['kode_barang' => 'ATK-08-10027',  'nama_barang' => 'Double Tape 3M',              'satuan' => 'Buah',     'kategori_id' => $kat('ATK-08')],
            ['kode_barang' => 'ATK-08-10028',  'nama_barang' => 'Double Tape 3M (Merah)',      'satuan' => 'Buah',     'kategori_id' => $kat('ATK-08')],
            ['kode_barang' => 'ATK-08-10029',  'nama_barang' => 'Lem Povinal',                 'satuan' => 'Pcs',      'kategori_id' => $kat('ATK-08')],

            // === ATK-09: STAPLES ===
            ['kode_barang' => 'ATK-09-003',    'nama_barang' => 'Staple Remover',              'satuan' => 'Buah',     'kategori_id' => $kat('ATK-09')],
            ['kode_barang' => 'ATK-09-H001',   'nama_barang' => 'Hekter Tembak kangguru',      'satuan' => 'Pcs',      'kategori_id' => $kat('ATK-09')],
            ['kode_barang' => 'ATK-09-H002',   'nama_barang' => 'Hekter no 10',                'satuan' => 'Buah',     'kategori_id' => $kat('ATK-09')],
            ['kode_barang' => 'ATK-09-H003',   'nama_barang' => 'HEKTER BESAR',                'satuan' => 'Buah',     'kategori_id' => $kat('ATK-09')],
            ['kode_barang' => 'ATK-09-I002',   'nama_barang' => 'Anak Hekter no 10',           'satuan' => 'Kotak',    'kategori_id' => $kat('ATK-09')],
            ['kode_barang' => 'ATK-09-I003',   'nama_barang' => 'ANAK HEKTER NO.03',           'satuan' => 'Kotak',    'kategori_id' => $kat('ATK-09')],

            // === ATK-10: ATK LAINNYA ===
            ['kode_barang' => 'ATK-10-999011', 'nama_barang' => 'Gunting sedang',              'satuan' => 'Buah',     'kategori_id' => $kat('ATK-10')],
            ['kode_barang' => 'ATK-10-999035', 'nama_barang' => 'Push pin',                    'satuan' => 'Kotak',    'kategori_id' => $kat('ATK-10')],
            ['kode_barang' => 'ATK-10-999056', 'nama_barang' => 'Sign Hire Combo',             'satuan' => 'Buah',     'kategori_id' => $kat('ATK-10')],
            ['kode_barang' => 'ATK-10-999065', 'nama_barang' => 'Mark & Notes',                'satuan' => 'Pcs',      'kategori_id' => $kat('ATK-10')],
            ['kode_barang' => 'ATK-10-999066', 'nama_barang' => 'Sticky Notes',                'satuan' => 'Bungkus',  'kategori_id' => $kat('ATK-10')],

            // === ATK-11: KERTAS HVS ===
            ['kode_barang' => 'ATK-11-019',    'nama_barang' => 'Kertas HVS A4 Sidu, 70 Gram','satuan' => 'Rim',      'kategori_id' => $kat('ATK-11')],
            ['kode_barang' => 'ATK-11-020',    'nama_barang' => 'HVS KUNING',                  'satuan' => 'Rim',      'kategori_id' => $kat('ATK-11')],
            ['kode_barang' => 'ATK-11-022',    'nama_barang' => 'Kertas HVS A4 100 gr',        'satuan' => 'Rim',      'kategori_id' => $kat('ATK-11')],

            // === ATK-12: BERBAGAI KERTAS ===
            ['kode_barang' => 'ATK-12-2005',   'nama_barang' => 'Kertas Padi',                 'satuan' => 'Lembar',   'kategori_id' => $kat('ATK-12')],
            ['kode_barang' => 'ATK-12-2007',   'nama_barang' => 'Kertas Glossy',               'satuan' => 'Buah',     'kategori_id' => $kat('ATK-12')],
            ['kode_barang' => 'ATK-12-001',    'nama_barang' => 'Kertas Concord 90gr',         'satuan' => 'Buah',     'kategori_id' => $kat('ATK-12')],

            // === ATK-13: AMPLOP ===
            ['kode_barang' => 'ATK-13-012',    'nama_barang' => 'Amplop putih pendek',         'satuan' => 'Kotak',    'kategori_id' => $kat('ATK-13')],
            ['kode_barang' => 'ATK-13-016',    'nama_barang' => 'Map Plastik ukuran F4',       'satuan' => 'Buah',     'kategori_id' => $kat('ATK-13')],
            ['kode_barang' => 'ATK-13-018',    'nama_barang' => 'AMPLOP PUTIH PANJANG LOGO',   'satuan' => 'Lbr',      'kategori_id' => $kat('ATK-13')],
            ['kode_barang' => 'ATK-13-019',    'nama_barang' => 'AMPLOP COKLAT LOGO',          'satuan' => 'Lbr',      'kategori_id' => $kat('ATK-13')],

            // === ATK-14: TINTA & TONER PRINTER ===
            ['kode_barang' => 'ATK-14-4043',   'nama_barang' => 'Toner HP Laserjet 83 A',      'satuan' => 'Buah',     'kategori_id' => $kat('ATK-14')],
            ['kode_barang' => 'ATK-14-4137',   'nama_barang' => 'Tinta Epson L3110 003 Black', 'satuan' => 'Botol',    'kategori_id' => $kat('ATK-14')],
            ['kode_barang' => 'ATK-14-4138',   'nama_barang' => 'Tinta Epson L3110 003 Cyan',  'satuan' => 'Botol',    'kategori_id' => $kat('ATK-14')],
            ['kode_barang' => 'ATK-14-4139',   'nama_barang' => 'Tinta Epson L3110 003 Magenta','satuan' => 'Botol',   'kategori_id' => $kat('ATK-14')],
            ['kode_barang' => 'ATK-14-4140',   'nama_barang' => 'Tinta Epson L3110 003 Yellow','satuan' => 'Botol',    'kategori_id' => $kat('ATK-14')],
            ['kode_barang' => 'ATK-14-4162',   'nama_barang' => 'Tinta Refill Epson 008 Cyan', 'satuan' => 'Pcs',      'kategori_id' => $kat('ATK-14')],
            ['kode_barang' => 'ATK-14-4185',   'nama_barang' => 'Epson Ink 008 Magenta',       'satuan' => 'Botol',    'kategori_id' => $kat('ATK-14')],
            ['kode_barang' => 'ATK-14-4189',   'nama_barang' => 'Epson Ink 008 hitam',         'satuan' => 'Botol',    'kategori_id' => $kat('ATK-14')],
            ['kode_barang' => 'ATK-14-4213',   'nama_barang' => 'Tinta Epson 008 Yellow',      'satuan' => 'Buah',     'kategori_id' => $kat('ATK-14')],

            // === ARK-01: USB & FLASH DISK ===
            ['kode_barang' => 'ARK-01-002',    'nama_barang' => 'Flasdisk',                    'satuan' => 'Pcs',      'kategori_id' => $kat('ARK-01')],
            ['kode_barang' => 'ARK-01-003',    'nama_barang' => 'Flashdisk 16 GB',             'satuan' => 'Pcs',      'kategori_id' => $kat('ARK-01')],
            ['kode_barang' => 'ARK-01-013',    'nama_barang' => 'USB Hub',                     'satuan' => 'Pcs',      'kategori_id' => $kat('ARK-01')],

            // === ARK-02: MOUSE & KEYBOARD ===
            ['kode_barang' => 'ARK-02-004',    'nama_barang' => 'Mouse Logiteck Warless',      'satuan' => 'Buah',     'kategori_id' => $kat('ARK-02')],
            ['kode_barang' => 'ARK-02-010',    'nama_barang' => 'Keyboard Warless',            'satuan' => 'Buah',     'kategori_id' => $kat('ARK-02')],

            // === ARK-08: BAHAN KIMIA PEMBERSIH ===
            ['kode_barang' => 'ARK-08-086',    'nama_barang' => 'PORSTEX FC BRU BTL 1',        'satuan' => 'Bh/Btl',   'kategori_id' => $kat('ARK-08')],
            ['kode_barang' => 'ARK-08-101',    'nama_barang' => 'Cling GC LMN RF',             'satuan' => 'Buah/Pcs', 'kategori_id' => $kat('ARK-08')],
            ['kode_barang' => 'ARK-08-126',    'nama_barang' => 'Superpell YL RF 770',         'satuan' => 'Bungkus',  'kategori_id' => $kat('ARK-08')],
            ['kode_barang' => 'ARK-08-136',    'nama_barang' => 'WIPOL SEREH & JERUK 750ML',   'satuan' => 'Buah',     'kategori_id' => $kat('ARK-08')],
            ['kode_barang' => 'ARK-08-141',    'nama_barang' => 'Mama Lemon Pouch 680ML',      'satuan' => 'Bungkus',  'kategori_id' => $kat('ARK-08')],

            // === ARK-09: PENGHARUM RUANGAN ===
            ['kode_barang' => 'ARK-09-040',    'nama_barang' => 'Dahlia Toilet Ball',          'satuan' => 'Pcs',      'kategori_id' => $kat('ARK-09')],
            ['kode_barang' => 'ARK-09-046',    'nama_barang' => 'Bayfresh Diff VB Reffil',     'satuan' => 'Pcs',      'kategori_id' => $kat('ARK-09')],
            ['kode_barang' => 'ARK-09-048',    'nama_barang' => 'Glade Matic Spray',           'satuan' => 'Pcs',      'kategori_id' => $kat('ARK-09')],

            // === ARK-10: PERABOT KANTOR LAINNYA ===
            ['kode_barang' => 'ARK-10-028',    'nama_barang' => 'NICE FACIAL TISSUE NP 900 G', 'satuan' => 'Buah',     'kategori_id' => $kat('ARK-10')],
            ['kode_barang' => 'ARK-10-093',    'nama_barang' => 'Nice Tisu Gulung Toilet',     'satuan' => 'Bks',      'kategori_id' => $kat('ARK-10')],
            ['kode_barang' => 'ARK-10-096',    'nama_barang' => 'MULTIFOLD TISSUE',            'satuan' => 'Bks',      'kategori_id' => $kat('ARK-10')],

            // === ARK-11: KABEL & LAMPU LISTRIK ===
            ['kode_barang' => 'ARK-11-010',    'nama_barang' => 'Fitting Keramik',             'satuan' => 'Buah',     'kategori_id' => $kat('ARK-11')],
            ['kode_barang' => 'ARK-11-026',    'nama_barang' => 'Lampu Hanoc 9 w Kuning',      'satuan' => 'Buah',     'kategori_id' => $kat('ARK-11')],
            ['kode_barang' => 'ARK-11-029',    'nama_barang' => 'TL Philip 36 W',              'satuan' => 'Pcs',      'kategori_id' => $kat('ARK-11')],

            // === ARK-12: STOP KONTAK & SAKLAR ===
            ['kode_barang' => 'ARK-12-002',    'nama_barang' => 'Stop Kontak AC',              'satuan' => 'Buah',     'kategori_id' => $kat('ARK-12')],
            ['kode_barang' => 'ARK-12-007',    'nama_barang' => 'STOP KONTAK UTICON 5 LUBANG', 'satuan' => 'Pcs',      'kategori_id' => $kat('ARK-12')],
            ['kode_barang' => 'ARK-12-S001',   'nama_barang' => 'SAKLAR',                      'satuan' => 'Pcs',      'kategori_id' => $kat('ARK-12')],
            ['kode_barang' => 'ARK-12-ST001',  'nama_barang' => 'STARTER',                     'satuan' => 'Buah',     'kategori_id' => $kat('ARK-12')],
            ['kode_barang' => 'ARK-12-VT001',  'nama_barang' => 'Fitting TL Kap RM T8 YLI',   'satuan' => 'Set',      'kategori_id' => $kat('ARK-12')],

            // === ARK-13: BATERAI ===
            ['kode_barang' => 'ARK-13-003',    'nama_barang' => 'Bateray Alkaline AA',         'satuan' => 'Set',      'kategori_id' => $kat('ARK-13')],
            ['kode_barang' => 'ARK-13-004',    'nama_barang' => 'Bateray Alkaline AAA',        'satuan' => 'Set',      'kategori_id' => $kat('ARK-13')],

            // === ARK-15: PERLENGKAPAN KANTOR LAINNYA ===
            ['kode_barang' => 'ARK-15-431',    'nama_barang' => 'Tempat Dokumen Meja',         'satuan' => 'Buah',     'kategori_id' => $kat('ARK-15')],
            ['kode_barang' => 'ARK-15-638',    'nama_barang' => 'Tatakan Mouse',               'satuan' => 'Buah',     'kategori_id' => $kat('ARK-15')],
            ['kode_barang' => 'ARK-15-858',    'nama_barang' => 'Lem Tikus',                   'satuan' => 'Buah',     'kategori_id' => $kat('ARK-15')],
            ['kode_barang' => 'ARK-15-859',    'nama_barang' => 'PLASTIK MATAHARI JUMBO',      'satuan' => 'Bungkus',  'kategori_id' => $kat('ARK-15')],
        ];

        foreach ($barangs as $b) {
            Barang::firstOrCreate(['kode_barang' => $b['kode_barang']], $b);
        }
    }
}