<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'Times New Roman', Times, serif;
        font-size: 8.5pt;
        color: #000;
        background: #fff;
    }

    .page { padding: 16px 20px 12px 20px; }

    /* ── KOP SURAT ── */
    .kop-wrap {
        border-bottom: 3px double #000;
        padding-bottom: 8px;
        margin-bottom: 8px;
    }
    .kop-table {
        width: 100%;
        border-collapse: collapse;
    }
    .kop-table td {
        border: none;
        padding: 0;
        vertical-align: middle;
    }
    .kop-table .td-logo {
        width: 52px;
        text-align: center;
        vertical-align: middle;
    }
    .kop-table .td-logo img {
        width: 44px;
        height: 44px;
        display: block;
        margin: 0 auto;
    }
    .kop-table .td-text {
        text-align: center;
        vertical-align: middle;
        padding: 0 8px;
    }
    .kop-text-instansi {
        font-size: 12pt;
        font-weight: bold;
        text-transform: uppercase;
        line-height: 1.35;
        letter-spacing: 0.3px;
    }
    .kop-text-alamat {
        font-size: 7pt;
        margin-top: 3px;
        line-height: 1.6;
        color: #222;
    }

    /* ── JUDUL ── */
    .judul {
        text-align: center;
        margin: 10px 0 8px;
    }
    .judul h2 {
        font-size: 11pt;
        font-weight: bold;
        text-transform: uppercase;
        text-decoration: underline;
        line-height: 1.4;
    }

    /* ── INFO SATKER ── */
    .info-satker {
        margin-bottom: 10px;
        font-size: 8.5pt;
    }
    .info-satker table {
        border-collapse: collapse;
        width: auto;
    }
    .info-satker td {
        border: none;
        padding: 2px 4px 2px 0;
        vertical-align: top;
    }
    .info-satker .col-label { white-space: nowrap; min-width: 90px; }
    .info-satker .col-sep   { width: 10px; text-align: center; }

    /* ── SECTION TITLE ── */
    .section-title {
        font-size: 9pt;
        font-weight: bold;
        margin: 10px 0 5px;
        background: #e0e0e0;
        padding: 3px 8px;
        border-left: 4px solid #444;
    }

    /* ── TABEL RINGKASAN ── */
    table.ringkasan {
        width: 60%;
        border-collapse: collapse;
        font-size: 8pt;
        margin-bottom: 4px;
    }
    table.ringkasan th,
    table.ringkasan td {
        border: 0.75px solid #555;
        padding: 3px 6px;
        vertical-align: middle;
    }
    table.ringkasan th {
        background: #d0d0d0;
        font-weight: bold;
        text-align: center;
    }
    table.ringkasan td          { text-align: center; }
    table.ringkasan td.left     { text-align: left; }
    table.ringkasan tr.alt      { background: #f5f5f5; }
    table.ringkasan tr.bold-row { font-weight: bold; background: #e4e4e4; }
    table.ringkasan tr.sub-row  { background: #efefef; }

    /* ── TABEL DETAIL ── */
    table.data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 7pt;
    }
    table.data-table th,
    table.data-table td {
        border: 0.75px solid #555;
        padding: 3px 3px;
        vertical-align: middle;
    }
    table.data-table th {
        background: #d0d0d0;
        font-weight: bold;
        text-align: center;
        line-height: 1.3;
    }
    table.data-table td           { text-align: center; line-height: 1.4; }
    table.data-table td.left      { text-align: left; }
    table.data-table tr.alt       { background: #f5f5f5; }
    table.data-table tr.total-row { font-weight: bold; background: #e0e0e0; }

    /* ── TTD ── */
    .ttd-area { margin-top: 36px; overflow: hidden; }
    .ttd-kanan {
        float: right;
        text-align: center;
        width: 200px;
        padding-right: 8px;
    }
    .ttd-kanan p          { font-size: 8.5pt; line-height: 1.8; }
    .ttd-kanan .ruang-ttd { height: 52px; }
    .ttd-kanan .garis {
        border-top: 1px solid #000;
        width: 160px;
        margin: 0 auto 2px;
    }
</style>
</head>
<body>
<div class="page">

    <!-- KOP -->
    <div class="kop-wrap">
        <table class="kop-table">
            <tr>
                <td class="td-logo">
                    <img src="<?= base_url('assets/img/bps.png') ?>" alt="BPS">
                </td>
                <td class="td-text">
                    <div class="kop-text-instansi">
                        BADAN PUSAT STATISTIK<br>PROVINSI RIAU
                    </div>
                    <div class="kop-text-alamat">
                        Jl. Pattimura No. 12, Pekanbaru 28116 &nbsp;|&nbsp; Telepon: (0761) 23042 &nbsp;|&nbsp; Faksimile: (0761) 23042<br>
                        Laman: www.riau.bps.go.id &nbsp;|&nbsp; Pos-el: bps1400@bps.go.id
                    </div>
                </td>
                <td style="width:52px;"></td>
            </tr>
        </table>
    </div>

    <!-- JUDUL -->
    <div class="judul">
        <h2>Laporan <?= $jenis_periode === 'tahunan' ? 'Tahunan' : 'Bulanan' ?> Layanan Zoom Meeting</h2>
    </div>

    <!-- INFO SATKER -->
    <div class="info-satker">
        <table>
            <tr>
                <td class="col-label">Instansi</td>
                <td class="col-sep">:</td>
                <td>BPS Provinsi Riau</td>
            </tr>
            <tr>
                <td class="col-label">Periode</td>
                <td class="col-sep">:</td>
                <td><strong><?= htmlspecialchars($periode) ?></strong></td>
            </tr>
            <tr>
                <td class="col-label">Tanggal Cetak</td>
                <td class="col-sep">:</td>
                <td><?= $tanggal_cetak ?></td>
            </tr>
        </table>
    </div>

    <!-- I. RINGKASAN -->
    <div class="section-title">I.&nbsp; Ringkasan Layanan Zoom</div>
    <table class="ringkasan">
        <thead>
            <tr>
                <th style="width:65%">Keterangan</th>
                <th style="width:35%">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="left">Total Sesi Zoom</td>
                <td><strong><?= $stat['total'] ?></strong> sesi</td>
            </tr>
            <tr class="alt">
                <td class="left">&nbsp;&nbsp;Selesai</td>
                <td><?= $stat['selesai'] ?> sesi</td>
            </tr>
            <tr>
                <td class="left">&nbsp;&nbsp;On Progress</td>
                <td><?= $stat['progress'] ?> sesi</td>
            </tr>
            <tr class="alt">
                <td class="left">&nbsp;&nbsp;Menunggu</td>
                <td><?= $stat['menunggu'] ?> sesi</td>
            </tr>
            <tr>
                <td class="left">Total Peserta (kumulatif)</td>
                <td><strong><?= $stat['total_peserta'] ?></strong> orang</td>
            </tr>
            <tr class="bold-row">
                <td class="left">Zoom 1 &mdash; Digunakan</td>
                <td><?= $stat['zoom1'] ?> sesi</td>
            </tr>
            <tr class="sub-row">
                <td class="left">&nbsp;&nbsp;Total Breakout Room</td>
                <td><?= $stat['breakout_zoom1'] ?> room</td>
            </tr>
            <tr class="bold-row">
                <td class="left">Zoom 2 &mdash; Digunakan</td>
                <td><?= $stat['zoom2'] ?> sesi</td>
            </tr>
            <tr class="sub-row">
                <td class="left">&nbsp;&nbsp;Total Breakout Room</td>
                <td><?= $stat['breakout_zoom2'] ?> room</td>
            </tr>
        </tbody>
    </table>

    <!-- II. DETAIL SESI -->
    <div class="section-title">II.&nbsp; Detail Sesi Zoom</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:3%">No</th>
                <th style="width:11%">Kode Tiket</th>
                <th style="width:22%">Detail Kegiatan</th>
                <th style="width:10%">Pelapor</th>
                <th style="width:11%">Tanggal</th>
                <th style="width:6%">Jam</th>
                <th style="width:6%">Durasi</th>
                <th style="width:6%">Peserta</th>
                <th style="width:7%">Breakout Room</th>
                <th style="width:7%">Akun Zoom</th>
                <th style="width:11%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($rows)): ?>
            <tr>
                <td colspan="11" style="text-align:center; padding:10px;">
                    Tidak ada data pada periode ini
                </td>
            </tr>
            <?php else: ?>
            <?php foreach ($rows as $no => $r):
                $alt = ($no % 2 === 1) ? 'alt' : '';
            ?>
            <tr class="<?= $alt ?>">
                <td><?= $no + 1 ?></td>
                <td style="font-size:6.5pt; word-break:break-all; text-align:left;">
                    <?= htmlspecialchars($r['kode_tiket']) ?>
                </td>
                <td class="left"><?= htmlspecialchars($r['detail_permintaan'] ?: '-') ?></td>
                <td class="left"><?= htmlspecialchars($r['pelapor'] ?: '-') ?></td>
                <td style="font-size:6.5pt;"><?= htmlspecialchars($r['tanggal_label'] ?: '-') ?></td>
                <td><?= htmlspecialchars($r['jam'] ?: '-') ?></td>
                <td><?= $r['durasi'] ? $r['durasi'] . ' jam' : '-' ?></td>
                <td><?= $r['jumlah_peserta'] ? $r['jumlah_peserta'] . ' org' : '-' ?></td>
                <td><?= $r['jumlah_breakout'] ? $r['jumlah_breakout'] . ' room' : '-' ?></td>
                <td>Zoom <?= $r['akun_zoom'] ?: '-' ?></td>
                <td><?= htmlspecialchars($r['status']) ?></td>
            </tr>
            <?php endforeach; ?>
            <tr class="total-row">
                <td colspan="7" class="left" style="padding-left:6px;">TOTAL</td>
                <td><?= $stat['total_peserta'] ?> org</td>
                <td><?= ($stat['breakout_zoom1'] + $stat['breakout_zoom2']) ?> room</td>
                <td colspan="2"></td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- TTD -->
    <div class="ttd-area">
        <div class="ttd-kanan">
            <p>Pekanbaru, <?= $tanggal_cetak ?></p>
            <p>Kepala BPS Provinsi Riau,</p>
            <div class="ruang-ttd"></div>
            <div class="garis"></div>
            <p style="font-weight:bold;">&nbsp;</p>
            <p style="font-size:7.5pt;">NIP.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
        </div>
        <div style="clear:both"></div>
    </div>

</div>
</body>
</html>