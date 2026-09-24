<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rekap Inventory Lengkap</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 7px; color: #333; padding: 10px; }
        
        .header { text-align: center; margin-bottom: 10px; padding-bottom: 5px; border-bottom: 2px solid #333; }
        .header h1 { font-size: 14px; margin-bottom: 2px; }
        .header h2 { font-size: 12px; margin-bottom: 5px; }
        
        .summary { display: table; width: 100%; margin-bottom: 8px; border-collapse: collapse; }
        .summary-item { display: table-cell; width: 25%; padding: 5px; text-align: center; border: 1px solid #ddd; background: #f9f9f9; }
        .summary-label { font-size: 6px; color: #666; }
        .summary-value { font-size: 14px; font-weight: bold; }
        
        .section { margin-bottom: 10px; page-break-inside: avoid; }
        .section-title { font-size: 9px; font-weight: bold; background: #4472C4; color: white; padding: 3px 5px; margin-bottom: 3px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        th { background: #4472C4; color: white; padding: 3px 2px; font-size: 7px; border: 1px solid #3461af; text-align: center; }
        td { padding: 2px; font-size: 6px; border: 1px solid #ddd; text-align: center; }
        td:nth-child(2) { text-align: left; }
        tr:nth-child(even) { background: #f9f9f9; }
        
        .badge { display: inline-block; padding: 1px 4px; border-radius: 2px; font-weight: bold; font-size: 6px; }
        .badge-green { background: #d4edda; color: #155724; }
        .badge-yellow { background: #fff3cd; color: #856404; }
        .badge-red { background: #f8d7da; color: #721c24; }
        .badge-blue { background: #d1ecf1; color: #0c5460; }
        .badge-purple { background: #e2d9f3; color: #5a2a82; }
        .badge-cyan { background: #d1f2eb; color: #0c5460; }
        
        .footer { margin-top: 8px; padding-top: 5px; border-top: 1px solid #ddd; text-align: right; font-size: 6px; color: #666; }
        .info { font-size: 6px; margin-bottom: 5px; }
    </style>
</head>
<body>
    <!-- HEADER -->
    <div class="header">
        <h1>LAPORAN INVENTORY ASET TI</h1>
        <h2>BPS PROVINSI RIAU</h2>
        <div class="info">
            <?php if($kondisi): ?>Filter: <?= ucwords(str_replace('_', ' ', $kondisi)) ?> | <?php endif; ?>
            <?php if($tahun): ?>Tahun: <?= $tahun ?> | <?php endif; ?>
            Export: <?= $tanggal ?>
        </div>
    </div>

    <!-- SUMMARY -->
    <div class="summary">
        <div class="summary-item">
            <div class="summary-label">TOTAL ASET</div>
            <div class="summary-value"><?= $summary['total'] ?></div>
        </div>
        <div class="summary-item">
            <div class="summary-label">BAIK</div>
            <div class="summary-value" style="color: #28a745;"><?= $summary['baik'] ?></div>
        </div>
        <div class="summary-item">
            <div class="summary-label">RUSAK RINGAN</div>
            <div class="summary-value" style="color: #ffc107;"><?= $summary['rusak_ringan'] ?></div>
        </div>
        <div class="summary-item">
            <div class="summary-label">RUSAK BERAT</div>
            <div class="summary-value" style="color: #dc3545;"><?= $summary['rusak_berat'] ?></div>
        </div>
    </div>

    <!-- 1. JENIS BMN -->
    <div class="section">
        <div class="section-title">Jumlah Aset TI Berdasarkan Jenis dan Kondisi</div>
        <table>
            <thead>
                <tr>
                    <th style="width:5%;">No</th>
                    <th style="width:45%;">Jenis BMN</th>
                    <th style="width:12.5%;">Baik</th>
                    <th style="width:12.5%;">Rusak Ringan</th>
                    <th style="width:12.5%;">Rusak Berat</th>
                    <th style="width:12.5%;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach($data_jenis as $item): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td style="text-align: left;"><?= $item['jenis_bmn'] ?></td>
                    <td><span class="badge badge-green"><?= $item['baik'] ?? 0 ?></span></td>
                    <td><span class="badge badge-yellow"><?= $item['rusak_ringan'] ?? 0 ?></span></td>
                    <td><span class="badge badge-red"><?= $item['rusak_berat'] ?? 0 ?></span></td>
                    <td><span class="badge badge-cyan"><?= $item['total'] ?? 0 ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- 2. KONDISI BARANG -->
    <div class="section">
        <div class="section-title">Jumlah Aset TI Berdasarkan Nama Barang dan Kondisi</div>
        <table>
            <thead>
                <tr>
                    <th style="width:5%;">No</th>
                    <th style="width:45%;">Nama Barang</th>
                    <th style="width:12.5%;">Baik</th>
                    <th style="width:12.5%;">Rusak Ringan</th>
                    <th style="width:12.5%;">Rusak Berat</th>
                    <th style="width:12.5%;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach($data_kondisi as $item): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td style="text-align: left;"><?= $item['nama_barang'] ?></td>
                    <td><span class="badge badge-green"><?= $item['baik'] ?? 0 ?></span></td>
                    <td><span class="badge badge-yellow"><?= $item['rusak_ringan'] ?? 0 ?></span></td>
                    <td><span class="badge badge-red"><?= $item['rusak_berat'] ?? 0 ?></span></td>
                    <td><span class="badge badge-cyan"><?= $item['total'] ?? 0 ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- PAGE BREAK -->
    <div style="page-break-after: always;"></div>

    <!-- 3. RUANGAN -->
    <div class="section">
        <div class="section-title">Jumlah Aset TI Berdasarkan Ruangan dan Kondisi</div>
        <table>
            <thead>
                <tr>
                    <th style="width:5%;">No</th>
                    <th style="width:45%;">Nama Ruangan</th>
                    <th style="width:12.5%;">Baik</th>
                    <th style="width:12.5%;">Rusak Ringan</th>
                    <th style="width:12.5%;">Rusak Berat</th>
                    <th style="width:12.5%;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach($data_ruangan as $item): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td style="text-align: left;"><?= $item['nama_ruangan'] ?></td>
                    <td><span class="badge badge-green"><?= $item['baik'] ?? 0 ?></span></td>
                    <td><span class="badge badge-yellow"><?= $item['rusak_ringan'] ?? 0 ?></span></td>
                    <td><span class="badge badge-red"><?= $item['rusak_berat'] ?? 0 ?></span></td>
                    <td><span class="badge badge-cyan"><?= $item['total'] ?? 0 ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- 4. TAHUN PEROLEHAN -->
    <div class="section">
        <div class="section-title">Jumlah Aset TI Berdasarkan Kondisi dan Tahun Perolehan</div>
        <table>
            <thead>
                <tr>
                    <th style="width:4%;">No</th>
                    <th style="width:35%;">Nama Barang</th>
                    <th style="width:8%;">Tahun</th>
                    <th style="width:13%;">Baik</th>
                    <th style="width:13%;">Rusak Ringan</th>
                    <th style="width:13%;">Rusak Berat</th>
                    <th style="width:14%;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach($data_tahun as $item): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td style="text-align: left;"><?= $item['nama_barang'] ?></td>
                    <td><?= $item['tahun'] ?? '-' ?></td>
                    <td><span class="badge badge-green"><?= $item['baik'] ?? 0 ?></span></td>
                    <td><span class="badge badge-yellow"><?= $item['rusak_ringan'] ?? 0 ?></span></td>
                    <td><span class="badge badge-red"><?= $item['rusak_berat'] ?? 0 ?></span></td>
                    <td><span class="badge badge-cyan"><?= $item['total'] ?? 0 ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- PAGE BREAK -->
    <div style="page-break-after: always;"></div>

    <!-- 5. PEMELIHARAAN -->
    <div class="section">
        <div class="section-title">Jumlah Pemeliharaan Aset TI</div>
        <table>
            <thead>
                <tr>
                    <th style="width:5%;">No</th>
                    <th style="width:45%;">Nama Barang</th>
                    <th style="width:16.67%;">Jumlah Aset</th>
                    <th style="width:16.67%;">Jumlah Pemeliharaan</th>
                    <th style="width:16.66%;">Persentase</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach($data_pemeliharaan as $item): 
                    $persen = $item['jumlah_aset'] > 0 ? round(($item['jumlah_pemeliharaan'] / $item['jumlah_aset']) * 100, 1) : 0;
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td style="text-align: left;"><?= $item['nama_barang'] ?></td>
                    <td><span class="badge badge-blue"><?= $item['jumlah_aset'] ?? 0 ?></span></td>
                    <td><span class="badge badge-purple"><?= $item['jumlah_pemeliharaan'] ?? 0 ?></span></td>
                    <td><span class="badge badge-cyan"><?= $persen ?>%</span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        IT Service System - BPS Provinsi Riau | Dicetak: <?= $tanggal ?>
    </div>
</body>
</html>