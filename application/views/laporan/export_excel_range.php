<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=laporan_" . $view . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body  { font-family: Arial, sans-serif; font-size: 11px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #333; padding: 4px 6px; vertical-align: top; }
        th { background-color: #4472C4; color: #fff; text-align: center; font-weight: bold; }
        .center { text-align: center; }
        .right  { text-align: right; }
        .sum td { background-color: #f0f0f0; font-weight: bold; }
        .puas   { background-color: #dcfce7; }
        .cukup  { background-color: #fef9c3; }
        .kurang { background-color: #fee2e2; }
    </style>
</head>
<body>

<?php /* ================================================================
   TIKET
================================================================ */
if ($view === 'tiket'): ?>
<table>
    <tr><th colspan="9" style="font-size:13px;">LAPORAN TIKET<br>Periode <?= htmlspecialchars($start) ?> s/d <?= htmlspecialchars($end) ?></th></tr>
    <tr>
        <th>No</th><th>Kode Tiket</th><th>Kategori</th><th>Sub Kategori</th>
        <th>Pelapor</th><th>Lokasi</th><th>Dibuat</th><th>Selesai</th><th>Rating</th>
    </tr>
    <?php if (!empty($tickets)): $no=1; foreach ($tickets as $t): ?>
    <tr>
        <td class="center"><?= $no++ ?></td>
        <td><?= htmlspecialchars($t['kode_tiket'] ?? '') ?></td>
        <td><?= htmlspecialchars($t['kategori'] ?? '-') ?></td>
        <td><?= htmlspecialchars($t['sub_kategori'] ?? '-') ?></td>
        <td><?= htmlspecialchars($t['pelapor'] ?? '-') ?></td>
        <td><?= htmlspecialchars($t['lokasi'] ?? '-') ?></td>
        <td><?= $t['created_at'] ?? '-' ?></td>
        <td><?= $t['selesai_at'] ?? '-' ?></td>
        <td class="center"><?= $t['rating'] ?? '-' ?></td>
    </tr>
    <?php endforeach; else: ?>
    <tr><td colspan="9" class="center">Tidak ada data</td></tr>
    <?php endif; ?>
</table>

<?php /* ================================================================
   PERANGKAT IT
================================================================ */
elseif ($view === 'perangkat_it'): ?>
<table>
    <tr><th colspan="15" style="font-size:13px;">LAPORAN INVENTORY PERANGKAT IT</th></tr>
    <tr>
        <th>No</th><th>Kode Barang</th><th>NUP</th><th>Barcode</th><th>Nama Barang</th>
        <th>Merk</th><th>Kondisi</th><th>Lokasi</th><th>Ruang</th>
        <th>Serial Number</th><th>MAC Address</th><th>Pengguna</th>
        <th>Tgl Perolehan</th><th>Nilai Perolehan</th><th>Update Terakhir</th>
    </tr>
    <?php if (!empty($perangkat)): $no=1; foreach ($perangkat as $p): ?>
    <tr>
        <td class="center"><?= $no++ ?></td>
        <td><?= htmlspecialchars($p['kode_barang'] ?? '') ?></td>
        <td><?= htmlspecialchars($p['nup'] ?? '') ?></td>
        <td><?= htmlspecialchars($p['barcode'] ?? '') ?></td>
        <td><?= htmlspecialchars($p['nama_barang'] ?? '') ?></td>
        <td><?= htmlspecialchars($p['merk'] ?? '') ?></td>
        <td><?= htmlspecialchars($p['kondisi'] ?? '') ?></td>
        <td><?= htmlspecialchars($p['lokasi'] ?? '') ?></td>
        <td><?= htmlspecialchars($p['ruang'] ?? '') ?></td>
        <td><?= htmlspecialchars($p['serial_number'] ?? '') ?></td>
        <td><?= htmlspecialchars($p['mac_address'] ?? '') ?></td>
        <td><?= htmlspecialchars($p['pengguna'] ?? '-') ?></td>
        <td><?= $p['tanggal_perolehan'] ?? '' ?></td>
        <td class="right"><?= number_format($p['nilai_perolehan'] ?? 0) ?></td>
        <td><?= (!empty($p['updated_at']) && $p['updated_at'] !== '0000-00-00 00:00:00') ? $p['updated_at'] : 'Belum diupdate' ?></td>
    </tr>
    <?php endforeach; else: ?>
    <tr><td colspan="15" class="center">Tidak ada data</td></tr>
    <?php endif; ?>
</table>

<?php /* ================================================================
   PERANGKAT UMUM
================================================================ */
elseif ($view === 'perangkat_umum'): ?>
<table>
    <tr><th colspan="11" style="font-size:13px;">LAPORAN INVENTORY PERANGKAT UMUM</th></tr>
    <tr>
        <th>No</th><th>Kode Barang</th><th>NUP</th><th>Nama Barang</th><th>Merk</th>
        <th>Kondisi</th><th>Lokasi</th><th>Ruang</th><th>Pengguna</th>
        <th>Tgl Perolehan</th><th>Nilai Perolehan</th>
    </tr>
    <?php if (!empty($umum)): $no=1; foreach ($umum as $u): ?>
    <tr>
        <td class="center"><?= $no++ ?></td>
        <td><?= htmlspecialchars($u['kode_barang'] ?? '') ?></td>
        <td><?= htmlspecialchars($u['nup'] ?? '') ?></td>
        <td><?= htmlspecialchars($u['nama_barang'] ?? '') ?></td>
        <td><?= htmlspecialchars($u['merk'] ?? '') ?></td>
        <td><?= htmlspecialchars($u['kondisi'] ?? '') ?></td>
        <td><?= htmlspecialchars($u['lokasi'] ?? '') ?></td>
        <td><?= htmlspecialchars($u['ruang'] ?? '') ?></td>
        <td><?= htmlspecialchars($u['pengguna'] ?? '-') ?></td>
        <td><?= $u['tanggal_perolehan'] ?? '' ?></td>
        <td class="right"><?= number_format($u['nilai_perolehan'] ?? 0) ?></td>
    </tr>
    <?php endforeach; else: ?>
    <tr><td colspan="11" class="center">Tidak ada data</td></tr>
    <?php endif; ?>
</table>

<?php /* ================================================================
   PERFORMA TEKNISI — filter-aware
   Variabel dari controller: $teknisi, $tahun, $bulan, $user_id
================================================================ */
elseif ($view === 'teknisi'):
    $nb = ['','Januari','Februari','Maret','April','Mei','Juni',
           'Juli','Agustus','September','Oktober','November','Desember'];

    // Judul dinamis
    if (!empty($user_id) && !empty($teknisi)) {
        $judulRow = 'LAPORAN PERFORMA: ' . strtoupper($teknisi[0]['name']);
    } elseif (!empty($user_id)) {
        $judulRow = 'LAPORAN PERFORMA TEKNISI #' . $user_id;
    } else {
        $judulRow = 'LAPORAN PERFORMA SELURUH TEKNISI';
    }
    $periodeRow = 'Tahun ' . $tahun . (!empty($bulan) ? ' / ' . $nb[(int)$bulan] : ' — Semua Bulan');
?>
<table>
    <tr><th colspan="5" style="font-size:13px;"><?= $judulRow ?><br><?= $periodeRow ?></th></tr>
    <tr>
        <th style="width:8%;">Rank</th>
        <th style="width:32%;">Nama Teknisi</th>
        <th style="width:15%;">Total Tiket</th>
        <th style="width:15%;">Rating</th>
        <th style="width:30%;">Performa</th>
    </tr>
    <?php if (!empty($teknisi)):
        $rank = 1;
        foreach ($teknisi as $t):
            $rating = floatval($t['rating']);
            $label  = $rating >= 4.5 ? 'Terbaik' : ($rating >= 4.0 ? 'Bagus' : 'Cukup');
            $medal  = $rank === 1 ? '🥇 ' : ($rank === 2 ? '🥈 ' : ($rank === 3 ? '🥉 ' : ''));
    ?>
    <tr>
        <td class="center"><?= $medal . $rank++ ?></td>
        <td><?= htmlspecialchars($t['name']) ?></td>
        <td class="center"><?= $t['total'] ?> tiket</td>
        <td class="center"><?= number_format($rating, 1) ?>/5.0</td>
        <td class="center"><?= $label ?></td>
    </tr>
    <?php endforeach;

        $totalTiket = array_sum(array_column($teknisi, 'total'));
        $rated      = array_filter($teknisi, fn($t) => floatval($t['rating']) > 0);
        $avgRating  = !empty($rated) ? array_sum(array_column($rated, 'rating')) / count($rated) : 0;
    ?>
    <tr><td colspan="5" style="height:8px; border:none;"></td></tr>
    <tr class="sum">
        <td colspan="2" style="text-align:right;">TOTAL TIKET DISELESAIKAN</td>
        <td colspan="3" class="center"><?= number_format($totalTiket) ?> tiket</td>
    </tr>
    <tr class="sum">
        <td colspan="2" style="text-align:right;">RATING RATA-RATA</td>
        <td colspan="3" class="center"><?= number_format($avgRating, 2) ?>/5.0</td>
    </tr>
    <?php if (empty($user_id)): ?>
    <tr class="sum">
        <td colspan="2" style="text-align:right;">TOP PERFORMER</td>
        <td colspan="3" class="center">
            <?= htmlspecialchars($teknisi[0]['name']) ?> (<?= number_format(floatval($teknisi[0]['rating']), 1) ?>)
        </td>
    </tr>
    <?php endif; ?>
    <?php else: ?>
    <tr><td colspan="5" class="center">Tidak ada data</td></tr>
    <?php endif; ?>
</table>

<?php /* ================================================================
   SARAN & MASUKAN
================================================================ */
elseif ($view === 'saran'): ?>
<table>
    <tr><th colspan="8" style="font-size:13px;">DAFTAR SARAN &amp; MASUKAN<br>Periode <?= htmlspecialchars($start) ?> s/d <?= htmlspecialchars($end) ?></th></tr>
    <tr>
        <th>No</th><th>Kode Tiket</th><th>Pelapor</th><th>Kategori</th>
        <th>Lokasi</th><th>Rating</th><th>Komentar / Saran</th><th>Tanggal Selesai</th>
    </tr>
    <?php if (!empty($rows)):
        $no=1;
        foreach ($rows as $r):
            $rating = intval($r['rating']);
            $stars  = str_repeat('★',$rating).str_repeat('☆',5-$rating);
            $label  = $rating>=4 ? 'Puas' : ($rating>=3 ? 'Cukup' : 'Kurang');
            $cls    = $rating>=4 ? 'puas' : ($rating>=3 ? 'cukup' : 'kurang');
    ?>
    <tr>
        <td class="center"><?= $no++ ?></td>
        <td><?= htmlspecialchars($r['kode_tiket'] ?? '') ?></td>
        <td><?= htmlspecialchars($r['pelapor'] ?? '-') ?></td>
        <td><?= htmlspecialchars($r['kategori'] ?? '-') ?></td>
        <td><?= htmlspecialchars($r['lokasi'] ?? '-') ?></td>
        <td class="center <?= $cls ?>"><?= $stars ?> (<?= $rating ?>/5) — <?= $label ?></td>
        <td><?= htmlspecialchars($r['komentar'] ?? '-') ?></td>
        <td class="center"><?= $r['selesai_at'] ?? '-' ?></td>
    </tr>
    <?php endforeach;
        $total = count($rows);
        $avg   = $total > 0 ? round(array_sum(array_column($rows,'rating'))/$total,1) : 0;
        $puas  = count(array_filter($rows, fn($r) => intval($r['rating']) >= 4));
    ?>
    <tr><td colspan="8" style="height:8px; border:none;"></td></tr>
    <tr class="sum"><td colspan="4" style="text-align:right;">TOTAL MASUKAN</td><td colspan="4" class="center"><?= $total ?> masukan</td></tr>
    <tr class="sum"><td colspan="4" style="text-align:right;">RATA-RATA RATING</td><td colspan="4" class="center"><?= $avg ?> / 5.0</td></tr>
    <tr class="sum">
        <td colspan="4" style="text-align:right;">RESPONDEN PUAS (Rating ≥ 4)</td>
        <td colspan="4" class="center"><?= $puas ?> dari <?= $total ?> (<?= $total>0?round($puas/$total*100):0 ?>%)</td>
    </tr>
    <?php else: ?>
    <tr><td colspan="8" class="center">Tidak ada data</td></tr>
    <?php endif; ?>
</table>

<?php endif; ?>
</body>
</html>