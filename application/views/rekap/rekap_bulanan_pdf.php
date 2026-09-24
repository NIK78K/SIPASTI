<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'Times New Roman', Times, serif;
        font-size: 8pt;
        color: #000;
        background: #fff;
    }

    .page { padding: 4px 10px 2px 12px; }

    /* KOP */
    .kop-wrap { border-bottom: 2.5px double #000; padding-bottom: 3px; margin-bottom: 4px; overflow: hidden; }
    .kop-logo { float: left; width: 40px; }
    .kop-logo img { width: 36px; height: 36px; }
    .kop-text { margin-left: 44px; }
    .kop-text .instansi { font-size: 10pt; font-weight: bold; text-transform: uppercase; line-height: 1.15; }
    .kop-text .alamat { font-size: 6.5pt; margin-top: 1px; line-height: 1.3; }

    /* JUDUL */
    .judul { text-align: center; margin: 4px 0 3px; }
    .judul h2 { font-size: 10pt; font-weight: bold; text-transform: uppercase; text-decoration: underline; }
    .judul p  { font-size: 8pt; font-weight: bold; text-transform: uppercase; }

    /* INFO SATKER */
    .info-satker { margin-bottom: 4px; font-size: 8pt; }
    .info-satker p { line-height: 1.45; }

    /* SECTION TITLE */
    .section-title { font-size: 8.5pt; font-weight: bold; margin-bottom: 2px; margin-top: 5px; }

    /* TABLE */
    table { width: 100%; border-collapse: collapse; font-size: 7.5pt; }
    table th, table td { border: 1px solid #000; padding: 2px 3px; vertical-align: middle; }
    table th { background-color: #d9d9d9; font-weight: bold; text-align: center; }
    table td { text-align: center; }
    table td.left { text-align: left; }
    table tr.total-row { font-weight: bold; background: #f0f0f0; }

    /* TTD */
    .ttd-area { margin-top: 28px; overflow: hidden; }
    .ttd-kanan { float: right; text-align: center; width: 180px; padding-right: 4px; }
    .ttd-kanan p { font-size: 8pt; line-height: 1.6; }
    .ttd-kanan .ruang-ttd { height: 42px; }
    .ttd-kanan .garis { border-top: 1px solid #000; width: 140px; margin: 0 auto; }
</style>
</head>
<body>
<div class="page">

    <!-- KOP -->
    <div class="kop-wrap">
        <div class="kop-logo"><img src="<?= base_url('assets/img/bps.png') ?>" alt="BPS"></div>
        <div class="kop-text">
            <div class="instansi">BADAN PUSAT STATISTIK<br>PROVINSI RIAU</div>
            <div class="alamat">
                Jl. Pattimura No. 12, Pekanbaru &nbsp;|&nbsp; Telepon: (0761) 23042 &nbsp;|&nbsp; Faksimile: (0761) 23042<br>
                Laman: www.riau.bps.go.id &nbsp;|&nbsp; Pos-el: bps1400@bps.go.id
            </div>
        </div>
        <div style="clear:both"></div>
    </div>

    <!-- JUDUL -->
    <div class="judul">
        <h2>Laporan Bulanan</h2>
        <p>Aset dan Layanan Teknologi Informasi</p>
    </div>

    <!-- INFO SATKER -->
    <div class="info-satker">
        <p>BPS Kabupaten/Kota &nbsp;&nbsp;&nbsp;: <?= htmlspecialchars($satker ?? 'BPS Provinsi Riau') ?></p>
        <p>Periode &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: Bulan <strong><?= $nama_bulan ?? '...' ?></strong> Tahun <strong><?= $tahun ?? '...' ?></strong></p>
    </div>

    <!-- TABEL I — ASET TI PER KATEGORI -->
    <div class="section-title">I. Aset TI</div>
    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width:5%">No</th>
                <th rowspan="2" style="width:37%">Kategori Aset</th>
                <th rowspan="2" style="width:9%">Jumlah</th>
                <th colspan="3">Kondisi Aset</th>
                <th rowspan="2" style="width:9%">% Baik</th>
                <th rowspan="2" style="width:12%">Keterangan</th>
            </tr>
            <tr>
                <th style="width:8%">Baik</th>
                <th style="width:8%">Rusak Ringan</th>
                <th style="width:8%">Rusak Berat</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $tj = $tb = $trr = $trb = 0;
            foreach ($aset_kategori ?? [] as $no => $row):
                $j  = (int)($row['jumlah'] ?? 0);
                $b  = (int)($row['baik'] ?? 0);
                $rr = (int)($row['rusak_ringan'] ?? 0);
                $rb = (int)($row['rusak_berat'] ?? 0);
                $tj += $j; $tb += $b; $trr += $rr; $trb += $rb;
                $pct = $j > 0 ? number_format(($b/$j)*100, 1) . '%' : '-';
            ?>
            <tr>
                <td><?= $no + 1 ?></td>
                <td class="left"><?= htmlspecialchars($row['kategori']) ?></td>
                <td><?= $j ?: '-' ?></td>
                <td><?= $b ?: '-' ?></td>
                <td><?= $rr ?: '-' ?></td>
                <td><?= $rb ?: '-' ?></td>
                <td><?= $pct ?></td>
                <td></td>
            </tr>
            <?php endforeach; ?>
            <?php
            $overallPct = $tj > 0 ? number_format(($tb/$tj)*100, 1) . '%' : '-';
            ?>
            <tr class="total-row">
                <td colspan="2" class="left">TOTAL</td>
                <td><?= $tj ?></td>
                <td><?= $tb ?></td>
                <td><?= $trr ?></td>
                <td><?= $trb ?></td>
                <td><?= $overallPct ?></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <!-- TABEL II — LAYANAN TI -->
    <div class="section-title">II. Layanan TI</div>
    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width:5%">No</th>
                <th rowspan="2" style="width:42%">Jenis Layanan</th>
                <th colspan="3">Jumlah Laporan</th>
                <th rowspan="2" style="width:9%">% Selesai</th>
                <th rowspan="2" style="width:12%">Keterangan</th>
            </tr>
            <tr>
                <th style="width:9%">Masuk</th>
                <th style="width:9%">Ditanggapi</th>
                <th style="width:9%">Diselesaikan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $lay_rows = [
                'permintaan' => 'Permintaan (upgrade, pengembangan aplikasi/tools, zoom, penambahan jaringan, dll)',
                'gangguan'   => 'Gangguan (perangkat mati, koneksi jaringan putus, UPS rusak, dll)',
            ];
            $totMasuk = $totTanggap = $totSelesai = 0;
            $no2 = 0;
            foreach ($lay_rows as $key => $label):
                $no2++;
                $m  = (int)($layanan[$key]['masuk']      ?? 0);
                $t  = (int)($layanan[$key]['ditanggapi']  ?? 0);
                $s  = (int)($layanan[$key]['selesai']     ?? 0);
                $totMasuk += $m; $totTanggap += $t; $totSelesai += $s;
                $selesaiPct = $m > 0 ? number_format(($s/$m)*100, 1) . '%' : '-';
            ?>
            <tr>
                <td><?= $no2 ?></td>
                <td class="left" style="font-style:italic"><?= htmlspecialchars($label) ?></td>
                <td><?= $m ?: '-' ?></td>
                <td><?= $t ?: '-' ?></td>
                <td><?= $s ?: '-' ?></td>
                <td><?= $selesaiPct ?></td>
                <td></td>
            </tr>
            <?php endforeach; ?>
            <?php $totPct = $totMasuk > 0 ? number_format(($totSelesai/$totMasuk)*100, 1) . '%' : '-'; ?>
            <tr class="total-row">
                <td colspan="2" class="left">TOTAL</td>
                <td><?= $totMasuk ?></td>
                <td><?= $totTanggap ?></td>
                <td><?= $totSelesai ?></td>
                <td><?= $totPct ?></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <!-- TTD -->
    <div class="ttd-area">
        <div class="ttd-kanan">
            <p>Pekanbaru, <?= $tanggal_cetak ?? date('d F Y') ?></p>
            <p>Kepala BPS Provinsi Riau,</p>
            <div class="ruang-ttd"></div>
            <div class="garis"></div>
        </div>
        <div style="clear:both"></div>
    </div>

</div>
</body>
</html>