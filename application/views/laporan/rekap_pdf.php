<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rekap Perbaikan Tiket IT</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .page-break { page-break-after: always; }
        table { border-collapse: collapse; }
    </style>
</head>
<body>

<?php
// Tentukan apakah mode "all months"
$is_all_months = isset($data_monthly);
?>

<!-- ============ PAGE 1: REKAP PERBAIKAN ============ -->
<table border="1" cellpadding="14" cellspacing="0" width="100%">
    <tr>
        <th colspan="4"
            style="font-size:22px;
                   font-weight:bold;
                   text-align:center;
                   background:#0891b2;
                   color:#ffffff;">
            REKAP PERBAIKAN TIKET IT
        </th>
    </tr>
    <tr>
        <th colspan="4"
            style="font-size:15px;
                   text-align:center;
                   background:#e0f2fe;">
            Periode: <?= $periode ?>
        </th>
    </tr>
</table>

<br><br>

<?php if ($is_all_months): ?>
    <!-- MODE: RINGKASAN PER BULAN (TABEL SIMPLE) -->
    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <tr style="background:#e6e6e6;">
            <th align="center" width="5%" style="font-size:14px;">No</th>
            <th align="left" width="25%" style="font-size:14px;">Periode</th>
            <?php
            // Ambil semua kategori yang ada
            $all_categories = [];
            foreach ($data_monthly as $bulan_info) {
                foreach ($bulan_info['data']['kategori'] as $kat_name => $kat_data) {
                    if (!in_array($kat_name, $all_categories)) {
                        $all_categories[] = $kat_name;
                    }
                }
            }
            foreach ($all_categories as $cat):
            ?>
            <th align="center" style="font-size:14px;"><?= strtoupper($cat) ?></th>
            <?php endforeach; ?>
            <th align="center" width="12%" style="font-size:14px; background:#ffd700;">TOTAL</th>
        </tr>

        <?php 
        $no = 1;
        $grand_total = 0;
        $category_totals = array_fill_keys($all_categories, 0);
        
        foreach ($data_monthly as $bulan_info): 
            $bulan_nama = $bulan_info['bulan_nama'];
            $data = $bulan_info['data'];
            $grand_total += $data['total'];
        ?>
        <tr>
            <td align="center" style="padding:8px;"><?= $no++ ?></td>
            <td style="padding:8px; font-weight:bold;"><?= $bulan_nama ?></td>
            <?php foreach ($all_categories as $cat): ?>
                <?php 
                $cat_total = isset($data['kategori'][$cat]) ? $data['kategori'][$cat]['total'] : 0;
                $category_totals[$cat] += $cat_total;
                ?>
                <td align="center" style="padding:8px;"><?= $cat_total > 0 ? $cat_total : '-' ?></td>
            <?php endforeach; ?>
            <td align="center" style="padding:8px; font-weight:bold; background:#fff8dc;">
                <?= $data['total'] ?>
            </td>
        </tr>
        <?php endforeach; ?>

        <!-- TOTAL ROW -->
        <tr style="background:#0891b2; color:#ffffff;">
            <td colspan="2" align="center" style="padding:12px; font-weight:bold; font-size:15px;">
                TOTAL KESELURUHAN
            </td>
            <?php foreach ($all_categories as $cat): ?>
                <td align="center" style="padding:12px; font-weight:bold; font-size:15px;">
                    <?= $category_totals[$cat] ?>
                </td>
            <?php endforeach; ?>
            <td align="center" style="padding:12px; font-weight:bold; font-size:16px;">
                <?= $grand_total ?>
            </td>
        </tr>
    </table>

<?php else: ?>
    <!-- MODE: DETAIL BULAN TERTENTU -->
    <table border="1" cellpadding="12" cellspacing="0" width="100%">
        <tr style="background:#e6e6e6;">
            <th align="left" width="35%" style="font-size:15px;">Kategori</th>
            <th align="left" width="45%" style="font-size:15px;">Sub Kategori</th>
            <th align="right" width="20%" style="font-size:15px;">Jumlah Tiket</th>
        </tr>

        <?php if (!empty($kategori)): ?>
        <?php foreach ($kategori as $namaKategori => $k): ?>

            <!-- BARIS KATEGORI -->
            <tr style="background:#fafafa;">
                <td colspan="3"
                    style="font-weight:bold;
                           font-size:16px;
                           padding:12px;">
                    <?= strtoupper($namaKategori) ?>
                </td>
            </tr>

            <!-- SUB KATEGORI -->
            <?php foreach ($k['sub'] as $s): ?>
            <tr>
                <td style="padding:10px;"></td>
                <td style="font-size:14px; padding:10px;">
                    <?= $s['nama'] ?>
                </td>
                <td align="right" style="font-size:14px; padding:10px;">
                    <?= $s['total'] ?>
                </td>
            </tr>
            <?php endforeach ?>

            <!-- TOTAL PER KATEGORI -->
            <tr style="background:#f0f0f0;">
                <td style="padding:10px;"></td>
                <td style="font-weight:bold;
                           font-size:15px;
                           padding:10px;">
                    Total <?= ucfirst(strtolower($namaKategori)) ?>
                </td>
                <td align="right"
                    style="font-weight:bold;
                           font-size:15px;
                           padding:10px;">
                    <?= $k['total'] ?>
                </td>
            </tr>

        <?php endforeach ?>
        <?php else: ?>

            <tr>
                <td colspan="3"
                    align="center"
                    style="font-size:14px; padding:16px; color:#666;">
                    Tidak ada data
                </td>
            </tr>

        <?php endif ?>
    </table>

    <br><br>

    <!-- TOTAL KESELURUHAN -->
    <table border="1" cellpadding="16" cellspacing="0" width="60%">
        <tr style="background:#0891b2;">
            <th align="left"
                style="font-size:18px;
                       color:#ffffff;">
                TOTAL TIKET SELESAI
            </th>
            <th align="right"
                style="font-size:24px;
                       font-weight:bold;
                       color:#ffffff;">
                <?= $total ?>
            </th>
        </tr>
    </table>

<?php endif; ?>

<div class="page-break"></div>

<!-- ============ PAGE 2: PEMELIHARAAN PERANGKAT ============ -->
<table border="1" cellpadding="14" cellspacing="0" width="100%">
    <tr>
        <th colspan="4"
            style="font-size:22px;
                   font-weight:bold;
                   text-align:center;
                   background:#0891b2;
                   color:#ffffff;">
            REKAP PEMELIHARAAN PERANGKAT
        </th>
    </tr>
    <tr>
        <th colspan="4"
            style="font-size:15px;
                   text-align:center;
                   background:#e0f2fe;">
            Periode: <?= $periode ?>
        </th>
    </tr>
</table>

<br><br>

<?php if ($is_all_months): ?>
    <!-- TABEL 1: RINGKASAN PER BULAN -->
    <h3 style="color:#0891b2; margin-bottom:10px;">Ringkasan Per Bulan</h3>
    
    <?php
    $grand_total_perbaikan = 0;
    ?>
    
    <table border="1" cellpadding="10" cellspacing="0" width="60%">
        <tr style="background:#e6e6e6;">
            <th align="center" width="10%" style="font-size:14px;">No</th>
            <th align="left" width="60%" style="font-size:14px;">Periode</th>
            <th align="center" width="30%" style="font-size:14px;">Jumlah Perbaikan</th>
        </tr>
        
        <?php 
        $no = 1;
        foreach ($data_pemeliharaan_monthly as $bulan_info): 
            $bulan_nama = $bulan_info['bulan_nama'];
            $data = $bulan_info['data'];
            $grand_total_perbaikan += $data['total_tiket'];
        ?>
        <tr>
            <td align="center" style="padding:8px;"><?= $no++ ?></td>
            <td style="padding:8px; font-weight:bold;"><?= $bulan_nama ?></td>
            <td align="center" style="padding:8px; font-weight:bold; font-size:15px;">
                <?= $data['total_tiket'] ?>
            </td>
        </tr>
        <?php endforeach; ?>
        
        <!-- TOTAL ROW -->
        <tr style="background:#0891b2; color:#ffffff;">
            <td colspan="2" align="center" style="padding:12px; font-weight:bold; font-size:15px;">
                TOTAL KESELURUHAN
            </td>
            <td align="center" style="padding:12px; font-weight:bold; font-size:16px;">
                <?= $grand_total_perbaikan ?>
            </td>
        </tr>
    </table>

    <br><br>

    <!-- TABEL 2: DETAIL PERANGKAT PER BULAN -->
    <h3 style="color:#0891b2; margin-bottom:10px;">Detail Perangkat Per Bulan</h3>
    
    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <tr style="background:#e6e6e6;">
            <th align="center" width="5%" style="font-size:13px;">No</th>
            <th align="left" width="50%" style="font-size:13px;">Nama Perangkat</th>
            <?php 
            // Ambil semua bulan untuk header
            foreach ($data_pemeliharaan_monthly as $bulan_info): 
                $bulan_nama = $bulan_info['bulan_nama'];
                // Ambil hanya nama bulan (tanpa tahun)
                $bulan_parts = explode(' ', $bulan_nama);
                $bulan_short = $bulan_parts[0];
            ?>
            <th align="center" style="font-size:12px;"><?= $bulan_short ?></th>
            <?php endforeach; ?>
            <th align="center" width="10%" style="font-size:13px; background:#ffd700;">TOTAL</th>
        </tr>
        
        <?php
        // Kumpulkan semua perangkat unik
        $all_devices = [];
        foreach ($data_pemeliharaan_monthly as $bulan_info) {
            foreach ($bulan_info['data']['data'] as $item) {
                $nama = $item['nama_barang'];
                if (!isset($all_devices[$nama])) {
                    $all_devices[$nama] = [];
                }
                $all_devices[$nama][$bulan_info['bulan_nama']] = $item['jumlah_perbaikan'];
            }
        }
        
        // Sort by total descending
        $device_totals = [];
        foreach ($all_devices as $nama => $months) {
            $device_totals[$nama] = array_sum($months);
        }
        arsort($device_totals);
        
        $no = 1;
        foreach ($device_totals as $nama => $total):
        ?>
        <tr>
            <td align="center" style="padding:6px;"><?= $no++ ?></td>
            <td style="padding:6px;"><?= $nama ?></td>
            <?php foreach ($data_pemeliharaan_monthly as $bulan_info): ?>
                <?php 
                $bulan_nama = $bulan_info['bulan_nama'];
                $jumlah = isset($all_devices[$nama][$bulan_nama]) ? $all_devices[$nama][$bulan_nama] : 0;
                ?>
                <td align="center" style="padding:6px;">
                    <?= $jumlah > 0 ? $jumlah : '-' ?>
                </td>
            <?php endforeach; ?>
            <td align="center" style="padding:6px; font-weight:bold; background:#fff8dc;">
                <?= $total ?>
            </td>
        </tr>
        <?php endforeach; ?>
        
        <!-- TOTAL ROW -->
        <tr style="background:#0891b2; color:#ffffff;">
            <td colspan="2" align="center" style="padding:10px; font-weight:bold; font-size:14px;">
                TOTAL PER BULAN
            </td>
            <?php foreach ($data_pemeliharaan_monthly as $bulan_info): ?>
                <td align="center" style="padding:10px; font-weight:bold; font-size:14px;">
                    <?= $bulan_info['data']['total_tiket'] ?>
                </td>
            <?php endforeach; ?>
            <td align="center" style="padding:10px; font-weight:bold; font-size:15px;">
                <?= $grand_total_perbaikan ?>
            </td>
        </tr>
    </table>

<?php endif; ?>

<br><br>

<!-- FOOTER -->
<table width="100%">
    <tr>
        <td style="font-size:12px;color:#666;">
            Dicetak pada: <?= date('d M Y H:i') ?> WIB
        </td>
    </tr>
    <tr>
        <td style="font-size:11px;color:#999; padding-top:5px;">
            SIPASTI - Sistem Informasi Pengelolaan Aset dan Layanan TI<br>
            BPS Provinsi Riau
        </td>
    </tr>
</table>

</body>
</html>