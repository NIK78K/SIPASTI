<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: Arial, sans-serif; font-size: 9px; color: #111; }
h2   { text-align: center; font-size: 13px; margin-bottom: 2px; }
p.periode { text-align: center; font-size: 9px; color: #555; margin-bottom: 10px; }
table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
th, td { border: 1px solid #444; padding: 3px 5px; vertical-align: top; }
th { background: #4472C4; color: #fff; text-align: center; font-size: 9px; }
.center { text-align: center; }
.right  { text-align: right; }
.sum td { background: #f0f0f0; font-weight: bold; }
.badge-terbaik { background:#dcfce7; color:#166534; font-weight:bold; }
.badge-bagus   { background:#dbeafe; color:#1e40af; font-weight:bold; }
.badge-cukup   { background:#fef9c3; color:#854d0e; font-weight:bold; }
.puas   { background:#dcfce7; }
.cukup  { background:#fef9c3; }
.kurang { background:#fee2e2; }
</style>
</head>
<body>

<h2><?= htmlspecialchars($judul) ?></h2>
<p class="periode">Periode: <?= htmlspecialchars($periode) ?></p>

<table>

<?php /* ================================================================
   TIKET
================================================================ */
if ($view === 'tiket'): ?>
<thead>
<tr>
    <th>No</th><th>Kode Tiket</th><th>Kategori</th><th>Sub Kategori</th>
    <th>Pelapor</th><th>Lokasi</th><th>Dibuat</th><th>Selesai</th><th>Rating</th>
</tr>
</thead>
<tbody>
<?php if (!empty($rows)): $no=1; foreach ($rows as $r): ?>
<tr>
    <td class="center"><?= $no++ ?></td>
    <td><?= htmlspecialchars($r['kode_tiket'] ?? '') ?></td>
    <td><?= htmlspecialchars($r['kategori'] ?? '-') ?></td>
    <td><?= htmlspecialchars($r['sub_kategori'] ?? '-') ?></td>
    <td><?= htmlspecialchars($r['pelapor'] ?? '-') ?></td>
    <td><?= htmlspecialchars($r['lokasi'] ?? '-') ?></td>
    <td><?= $r['created_at'] ?? '-' ?></td>
    <td><?= $r['selesai_at'] ?? '-' ?></td>
    <td class="center"><?= $r['rating'] ?? '-' ?></td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="9" class="center">Tidak ada data</td></tr>
<?php endif; ?>
</tbody>

<?php /* ================================================================
   PERANGKAT IT
================================================================ */
elseif ($view === 'perangkat_it'): ?>
<thead>
<tr>
    <th>No</th><th>Kode Barang</th><th>NUP</th><th>Nama Barang</th><th>Merk</th>
    <th>Kondisi</th><th>Lokasi</th><th>Ruang</th><th>Pengguna</th>
    <th>Barcode</th><th>Serial</th><th>MAC</th>
    <th>Tgl Perolehan</th><th>Nilai</th><th>Update Terakhir</th>
</tr>
</thead>
<tbody>
<?php if (!empty($rows)): $no=1; foreach ($rows as $r): ?>
<tr>
    <td class="center"><?= $no++ ?></td>
    <td><?= htmlspecialchars($r['kode_barang'] ?? '') ?></td>
    <td><?= htmlspecialchars($r['nup'] ?? '') ?></td>
    <td><?= htmlspecialchars($r['nama_barang'] ?? '') ?></td>
    <td><?= htmlspecialchars($r['merk'] ?? '') ?></td>
    <td><?= htmlspecialchars($r['kondisi'] ?? '') ?></td>
    <td><?= htmlspecialchars($r['lokasi'] ?? '') ?></td>
    <td><?= htmlspecialchars($r['ruang'] ?? '') ?></td>
    <td><?= htmlspecialchars($r['pengguna'] ?? '-') ?></td>
    <td><?= htmlspecialchars($r['barcode'] ?? '') ?></td>
    <td><?= htmlspecialchars($r['serial_number'] ?? '') ?></td>
    <td><?= htmlspecialchars($r['mac_address'] ?? '') ?></td>
    <td><?= $r['tanggal_perolehan'] ?? '' ?></td>
    <td class="right"><?= number_format($r['nilai_perolehan'] ?? 0) ?></td>
    <td><?= (!empty($r['updated_at']) && $r['updated_at'] !== '0000-00-00 00:00:00') ? $r['updated_at'] : 'Belum diupdate' ?></td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="15" class="center">Tidak ada data</td></tr>
<?php endif; ?>
</tbody>

<?php /* ================================================================
   PERANGKAT UMUM
================================================================ */
elseif ($view === 'perangkat_umum'): ?>
<thead>
<tr>
    <th>No</th><th>Kode Barang</th><th>NUP</th><th>Nama Barang</th><th>Merk</th>
    <th>Kondisi</th><th>Lokasi</th><th>Ruang</th><th>Pengguna</th>
    <th>Tgl Perolehan</th><th>Nilai</th>
</tr>
</thead>
<tbody>
<?php if (!empty($rows)): $no=1; foreach ($rows as $r): ?>
<tr>
    <td class="center"><?= $no++ ?></td>
    <td><?= htmlspecialchars($r['kode_barang'] ?? '') ?></td>
    <td><?= htmlspecialchars($r['nup'] ?? '') ?></td>
    <td><?= htmlspecialchars($r['nama_barang'] ?? '') ?></td>
    <td><?= htmlspecialchars($r['merk'] ?? '') ?></td>
    <td><?= htmlspecialchars($r['kondisi'] ?? '') ?></td>
    <td><?= htmlspecialchars($r['lokasi'] ?? '') ?></td>
    <td><?= htmlspecialchars($r['ruang'] ?? '') ?></td>
    <td><?= htmlspecialchars($r['pengguna'] ?? '-') ?></td>
    <td><?= $r['tanggal_perolehan'] ?? '' ?></td>
    <td class="right"><?= number_format($r['nilai_perolehan'] ?? 0) ?></td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="11" class="center">Tidak ada data</td></tr>
<?php endif; ?>
</tbody>

<?php /* ================================================================
   PERFORMA TEKNISI — periode & judul sudah di-set oleh controller
   berdasarkan filter bulan & user_id yang aktif
================================================================ */
elseif ($view === 'teknisi'): ?>
<thead>
<tr>
    <th style="width:8%;">Rank</th>
    <th style="width:34%;">Nama Teknisi</th>
    <th style="width:16%;">Total Tiket</th>
    <th style="width:16%;">Rating</th>
    <th style="width:26%;">Performa</th>
</tr>
</thead>
<tbody>
<?php if (!empty($rows)):
    $rank = 1;
    foreach ($rows as $t):
        $rating = floatval($t['rating']);
        $label  = $rating >= 4.5 ? 'Terbaik' : ($rating >= 4.0 ? 'Bagus' : 'Cukup');
        $cls    = $label === 'Terbaik' ? 'badge-terbaik' : ($label === 'Bagus' ? 'badge-bagus' : 'badge-cukup');
        $medal  = $rank === 1 ? '🥇 ' : ($rank === 2 ? '🥈 ' : ($rank === 3 ? '🥉 ' : ''));
?>
<tr>
    <td class="center"><?= $medal . $rank++ ?></td>
    <td><?= htmlspecialchars($t['name']) ?></td>
    <td class="center"><?= $t['total'] ?> tiket</td>
    <td class="center"><?= number_format($rating, 1) ?>/5.0</td>
    <td class="center <?= $cls ?>"><?= $label ?></td>
</tr>
<?php endforeach;

    // Summary
    $totalTiket = array_sum(array_column($rows, 'total'));
    $rated      = array_filter($rows, fn($t) => floatval($t['rating']) > 0);
    $avgRating  = !empty($rated) ? array_sum(array_column($rated, 'rating')) / count($rated) : 0;
?>
<tr><td colspan="5" style="height:6px; border:none;"></td></tr>
<tr class="sum">
    <td colspan="2" style="text-align:right;">TOTAL TIKET DISELESAIKAN</td>
    <td colspan="3" class="center"><?= number_format($totalTiket) ?> tiket</td>
</tr>
<tr class="sum">
    <td colspan="2" style="text-align:right;">RATING RATA-RATA</td>
    <td colspan="3" class="center"><?= number_format($avgRating, 2) ?>/5.0</td>
</tr>
<?php
    // Baris top performer hanya muncul kalau tidak filter 1 orang
    // Deteksi: kalau ada lebih dari 1 baris, berarti bukan filter single user
    if (count($rows) > 1):
?>
<tr class="sum">
    <td colspan="2" style="text-align:right;">TOP PERFORMER</td>
    <td colspan="3" class="center">
        <?= htmlspecialchars($rows[0]['name']) ?> (<?= number_format(floatval($rows[0]['rating']), 1) ?>)
    </td>
</tr>
<?php endif; ?>
<?php else: ?>
<tr><td colspan="5" class="center">Tidak ada data</td></tr>
<?php endif; ?>
</tbody>

<?php /* ================================================================
   SARAN & MASUKAN
================================================================ */
elseif ($view === 'saran'): ?>
<thead>
<tr>
    <th>No</th><th>Kode Tiket</th><th>Pelapor</th><th>Kategori</th>
    <th>Lokasi</th><th>Rating</th><th>Komentar / Saran</th><th>Tanggal Selesai</th>
</tr>
</thead>
<tbody>
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
    <td class="center <?= $cls ?>"><?= $stars ?> (<?= $rating ?>/5)<br><?= $label ?></td>
    <td style="font-style:italic;"><?= htmlspecialchars($r['komentar'] ?? '-') ?></td>
    <td class="center"><?= $r['selesai_at'] ?? '-' ?></td>
</tr>
<?php endforeach;
    $total = count($rows);
    $avg   = $total > 0 ? round(array_sum(array_column($rows,'rating'))/$total,1) : 0;
    $puas  = count(array_filter($rows, fn($r) => intval($r['rating']) >= 4));
?>
<tr><td colspan="8" style="height:6px; border:none;"></td></tr>
<tr class="sum"><td colspan="4" style="text-align:right;">TOTAL MASUKAN</td><td colspan="4" class="center"><?= $total ?> masukan</td></tr>
<tr class="sum"><td colspan="4" style="text-align:right;">RATA-RATA RATING</td><td colspan="4" class="center"><?= $avg ?> / 5.0</td></tr>
<tr class="sum">
    <td colspan="4" style="text-align:right;">RESPONDEN PUAS (Rating ≥ 4)</td>
    <td colspan="4" class="center"><?= $puas ?> dari <?= $total ?> (<?= $total>0?round($puas/$total*100):0 ?>%)</td>
</tr>
<?php else: ?>
<tr><td colspan="8" class="center">Tidak ada data</td></tr>
<?php endif; ?>
</tbody>

<?php endif; ?>
</table>
</body>
</html>