<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StokAwal;
use App\Models\Barang;

class StokAwalSeeder extends Seeder
{
    public function run(): void
    {
        $barang = fn(string $kode) => Barang::where('kode_barang', $kode)->value('id');

        $stoks = [
            // ATK-01: ALAT TULIS
            ['kode' => 'ATK-01-002',    'jumlah' => 17],
            ['kode' => 'ATK-01-100128', 'jumlah' => 98],
            ['kode' => 'ATK-01-100129', 'jumlah' => 50],
            ['kode' => 'ATK-01-100141', 'jumlah' => 6],
            ['kode' => 'ATK-01-100146', 'jumlah' => 2],
            ['kode' => 'ATK-01-100150', 'jumlah' => 151],
            ['kode' => 'ATK-01-100163', 'jumlah' => 10],
            ['kode' => 'ATK-01-100165', 'jumlah' => 180],
            ['kode' => 'ATK-01-100171', 'jumlah' => 68],
            ['kode' => 'ATK-01-100172', 'jumlah' => 11],
            ['kode' => 'ATK-01-100173', 'jumlah' => 102],
            ['kode' => 'ATK-01-100174', 'jumlah' => 20],
            ['kode' => 'ATK-01-100175', 'jumlah' => 255],
            ['kode' => 'ATK-01-100181', 'jumlah' => 79],
            ['kode' => 'ATK-01-100182', 'jumlah' => 240],

            // ATK-02: TINTA & STEMPEL
            ['kode' => 'ATK-02-001',    'jumlah' => 3],

            // ATK-03: PENJEPIT KERTAS
            ['kode' => 'ATK-03-002',    'jumlah' => 86],
            ['kode' => 'ATK-03-003',    'jumlah' => 113],
            ['kode' => 'ATK-03-005',    'jumlah' => 5],
            ['kode' => 'ATK-03-3011',   'jumlah' => 6],
            ['kode' => 'ATK-03-3016',   'jumlah' => 168],
            ['kode' => 'ATK-03-3021',   'jumlah' => 119],

            // ATK-04: PENGHAPUS & KOREKTOR
            ['kode' => 'ATK-04-002',    'jumlah' => 8],
            ['kode' => 'ATK-04-003',    'jumlah' => 76],

            // ATK-05: BUKU TULIS
            ['kode' => 'ATK-05-100510', 'jumlah' => 5],
            ['kode' => 'ATK-05-100514', 'jumlah' => 2],

            // ATK-06: ORDNER & MAP
            ['kode' => 'ATK-06-005',    'jumlah' => 1668],
            ['kode' => 'ATK-06-100607', 'jumlah' => 9],
            ['kode' => 'ATK-06-100618', 'jumlah' => 6],
            ['kode' => 'ATK-06-100621', 'jumlah' => 600],
            ['kode' => 'ATK-06-100637', 'jumlah' => 5],
            ['kode' => 'ATK-06-100638', 'jumlah' => 24],
            ['kode' => 'ATK-06-100643', 'jumlah' => 491],
            ['kode' => 'ATK-06-100647', 'jumlah' => 5],
            ['kode' => 'ATK-06-100649', 'jumlah' => 35],

            // ATK-07: CUTTER
            ['kode' => 'ATK-07-001',    'jumlah' => 42],
            ['kode' => 'ATK-07-002',    'jumlah' => 24],

            // ATK-08: ALAT PEREKAT
            ['kode' => 'ATK-08-003',    'jumlah' => 23],
            ['kode' => 'ATK-08-004',    'jumlah' => 32],
            ['kode' => 'ATK-08-10008',  'jumlah' => 38],
            ['kode' => 'ATK-08-10014',  'jumlah' => 12],
            ['kode' => 'ATK-08-10027',  'jumlah' => 25],
            ['kode' => 'ATK-08-10028',  'jumlah' => 8],
            ['kode' => 'ATK-08-10029',  'jumlah' => 9],

            // ATK-09: STAPLES
            ['kode' => 'ATK-09-003',    'jumlah' => 7],
            ['kode' => 'ATK-09-H001',   'jumlah' => 1],
            ['kode' => 'ATK-09-H002',   'jumlah' => 19],
            ['kode' => 'ATK-09-H003',   'jumlah' => 5],
            ['kode' => 'ATK-09-I002',   'jumlah' => 13],
            ['kode' => 'ATK-09-I003',   'jumlah' => 9],

            // ATK-10: ATK LAINNYA
            ['kode' => 'ATK-10-999011', 'jumlah' => 49],
            ['kode' => 'ATK-10-999035', 'jumlah' => 3],
            ['kode' => 'ATK-10-999056', 'jumlah' => 57],
            ['kode' => 'ATK-10-999065', 'jumlah' => 24],
            ['kode' => 'ATK-10-999066', 'jumlah' => 39],

            // ATK-11: KERTAS HVS
            ['kode' => 'ATK-11-019',    'jumlah' => 414],
            ['kode' => 'ATK-11-020',    'jumlah' => 2],
            ['kode' => 'ATK-11-022',    'jumlah' => 12],

            // ATK-12: BERBAGAI KERTAS
            ['kode' => 'ATK-12-2005',   'jumlah' => 112],
            ['kode' => 'ATK-12-2007',   'jumlah' => 7],
            ['kode' => 'ATK-12-001',    'jumlah' => 905],

            // ATK-13: AMPLOP
            ['kode' => 'ATK-13-012',    'jumlah' => 9],
            ['kode' => 'ATK-13-016',    'jumlah' => 512],
            ['kode' => 'ATK-13-018',    'jumlah' => 1300],
            ['kode' => 'ATK-13-019',    'jumlah' => 1175],

            // ATK-14: TINTA & TONER PRINTER
            ['kode' => 'ATK-14-4043',   'jumlah' => 10],
            ['kode' => 'ATK-14-4137',   'jumlah' => 9],
            ['kode' => 'ATK-14-4138',   'jumlah' => 11],
            ['kode' => 'ATK-14-4139',   'jumlah' => 11],
            ['kode' => 'ATK-14-4140',   'jumlah' => 10],
            ['kode' => 'ATK-14-4162',   'jumlah' => 7],
            ['kode' => 'ATK-14-4185',   'jumlah' => 5],
            ['kode' => 'ATK-14-4189',   'jumlah' => 8],
            ['kode' => 'ATK-14-4213',   'jumlah' => 5],

            // ARK-01: USB & FLASH DISK
            ['kode' => 'ARK-01-002',    'jumlah' => 3],
            ['kode' => 'ARK-01-003',    'jumlah' => 5],
            ['kode' => 'ARK-01-013',    'jumlah' => 9],

            // ARK-02: MOUSE & KEYBOARD
            ['kode' => 'ARK-02-004',    'jumlah' => 30],
            ['kode' => 'ARK-02-010',    'jumlah' => 12],

            // ARK-08: BAHAN KIMIA PEMBERSIH
            ['kode' => 'ARK-08-086',    'jumlah' => 6],
            ['kode' => 'ARK-08-101',    'jumlah' => 15],
            ['kode' => 'ARK-08-126',    'jumlah' => 21],
            ['kode' => 'ARK-08-136',    'jumlah' => 14],
            ['kode' => 'ARK-08-141',    'jumlah' => 19],

            // ARK-09: PENGHARUM RUANGAN
            ['kode' => 'ARK-09-040',    'jumlah' => 34],
            ['kode' => 'ARK-09-046',    'jumlah' => 33],
            ['kode' => 'ARK-09-048',    'jumlah' => 18],

            // ARK-10: PERABOT KANTOR LAINNYA
            ['kode' => 'ARK-10-028',    'jumlah' => 12],
            ['kode' => 'ARK-10-093',    'jumlah' => 6],
            ['kode' => 'ARK-10-096',    'jumlah' => 0],

            // ARK-11: KABEL & LAMPU LISTRIK
            ['kode' => 'ARK-11-010',    'jumlah' => 0],
            ['kode' => 'ARK-11-026',    'jumlah' => 0],
            ['kode' => 'ARK-11-029',    'jumlah' => 0],

            // ARK-12: STOP KONTAK & SAKLAR
            ['kode' => 'ARK-12-002',    'jumlah' => 0],
            ['kode' => 'ARK-12-007',    'jumlah' => 0],
            ['kode' => 'ARK-12-S001',   'jumlah' => 0],
            ['kode' => 'ARK-12-ST001',  'jumlah' => 0],
            ['kode' => 'ARK-12-VT001',  'jumlah' => 0],

            // ARK-13: BATERAI
            ['kode' => 'ARK-13-003',    'jumlah' => 0],
            ['kode' => 'ARK-13-004',    'jumlah' => 0],

            // ARK-15: PERLENGKAPAN KANTOR LAINNYA
            ['kode' => 'ARK-15-431',    'jumlah' => 0],
            ['kode' => 'ARK-15-638',    'jumlah' => 0],
            ['kode' => 'ARK-15-858',    'jumlah' => 0],
            ['kode' => 'ARK-15-859',    'jumlah' => 0],
        ];

        foreach ($stoks as $s) {
            $barangId = $barang($s['kode']);
            if (!$barangId) continue;

            StokAwal::firstOrCreate(
                ['barang_id' => $barangId, 'bulan' => 1, 'tahun' => 2026],
                ['jumlah' => $s['jumlah'], 'keterangan' => 'Stok awal Januari 2026']
            );
        }
    }
}