<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Berita Acara Penghapusan - <?= $pengajuan['nomor_sk'] ?></title>
    <style>
        @page {
            margin: 2cm;
        }
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #000;
        }
        .kop-surat {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #000;
            padding-bottom: 15px;
        }
        .kop-surat h2 {
            margin: 5px 0;
            font-size: 16pt;
            font-weight: bold;
        }
        .kop-surat p {
            margin: 3px 0;
            font-size: 11pt;
        }
        .nomor-surat {
            text-align: center;
            margin: 30px 0;
        }
        .nomor-surat h3 {
            margin: 5px 0;
            font-size: 14pt;
            text-decoration: underline;
            font-weight: bold;
        }
        .isi-surat {
            text-align: justify;
            margin: 20px 0;
        }
        .isi-surat p {
            margin: 10px 0;
            text-indent: 50px;
        }
        
        /* ✅ TAMBAHAN: PREVENT PAGE BREAK */
        h4 {
            page-break-after: avoid; /* Judul tabel tidak boleh terpisah dari tabel */
            margin-top: 30px;
            margin-bottom: 10px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0; /* ✅ Ubah dari 20px jadi 0 */
            page-break-inside: auto; /* ✅ Izinkan break di dalam tabel jika terlalu panjang */
        }
        
        thead {
            display: table-header-group; /* ✅ Header akan repeat di setiap halaman */
        }
        
        tfoot {
            display: table-footer-group; /* ✅ Footer akan repeat di setiap halaman */
        }
        
        /* ✅ PREVENT HEADER ORPHAN (Header tidak boleh sendirian di akhir halaman) */
        thead tr {
            page-break-after: avoid;
            page-break-inside: avoid;
        }
        
        tbody tr {
            page-break-inside: avoid; /* ✅ Satu baris tidak boleh terpotong */
        }
        
        table, th, td {
            border: 1px solid #000;
        }
        th {
            background-color: #f0f0f0;
            padding: 8px;
            text-align: center;
            font-weight: bold;
        }
        td {
            padding: 6px;
            vertical-align: top;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        .ttd-section {
            margin-top: 40px;
            page-break-inside: avoid; /* ✅ TTD tidak boleh terpotong */
        }
        .ttd-box {
            width: 45%;
            display: inline-block;
            vertical-align: top;
        }
        .ttd-left {
            float: left;
        }
        .ttd-right {
            float: right;
            text-align: center;
        }
        .ttd-space {
            height: 80px;
        }
        .footer-note {
            margin-top: 30px;
            font-size: 10pt;
            font-style: italic;
            page-break-inside: avoid; /* ✅ Footer tidak boleh terpotong */
        }
        
        /* ✅ FORCE PAGE BREAK JIKA DIPERLUKAN */
        .page-break {
            page-break-before: always;
        }
        
        /* ✅ KEEP DASAR PENGHAPUSAN DAN TABEL BERSAMA */
        .dasar-penghapusan {
            page-break-after: avoid;
        }
    </style>
</head>
<body>
    <!-- KOP SURAT -->
    <div class="kop-surat">
        <h2>BADAN PUSAT STATISTIK</h2>
        <h2>PROVINSI RIAU</h2>
        <p>Jl. Pattimura No. 12, Pekanbaru 28131</p>
        <p>Telepon: (0761) 21836, Faksimile: (0761) 21837</p>
        <p>Email: bps1400@bps.go.id | Website: riau.bps.go.id</p>
    </div>

    <!-- NOMOR SURAT -->
    <div class="nomor-surat">
        <h3>BERITA ACARA PENGHAPUSAN</h3>
        <h3>BARANG MILIK NEGARA</h3>
        <p>Nomor: <?= $pengajuan['nomor_sk'] ?></p>
    </div>

    <!-- ISI SURAT -->
    <div class="isi-surat">
        <p>
            Pada hari ini, <?= $hari ?> tanggal <?= $tanggal_lengkap ?>, 
            bertempat di Kantor Badan Pusat Statistik Provinsi Riau, yang bertanda tangan di bawah ini:
        </p>
        
        <table style="border: none; margin: 20px 0;">
            <tr style="border: none;">
                <td style="border: none; width: 30%;">Nama</td>
                <td style="border: none; width: 5%;">:</td>
                <td style="border: none;"><?= $pengajuan['approved_by'] ?? 'Kepala BPS Provinsi Riau' ?></td>
            </tr>
            <tr style="border: none;">
                <td style="border: none;">Jabatan</td>
                <td style="border: none;">:</td>
                <td style="border: none;">Kepala BPS Provinsi Riau</td>
            </tr>
            <tr style="border: none;">
                <td style="border: none;">NIP</td>
                <td style="border: none;">:</td>
                <td style="border: none;">___________________</td>
            </tr>
        </table>

        <p>
            Selaku Pengguna Barang pada Satuan Kerja BPS Provinsi Riau, dengan ini menerangkan bahwa 
            telah dilakukan penghapusan terhadap Barang Milik Negara sebagaimana tercantum dalam 
            daftar terlampir, dengan alasan barang rusak berat dan tidak ekonomis untuk diperbaiki.
        </p>

        <!-- ✅ TAMBAHKAN CLASS dasar-penghapusan -->
        <p class="dasar-penghapusan">
            <strong>Dasar Penghapusan:</strong><br>
            1. Peraturan Menteri Keuangan tentang Pengelolaan Barang Milik Negara<br>
            2. Keputusan Kepala BPS tentang Penghapusan Barang Milik Negara<br>
            3. Hasil Pemeriksaan Tim Teknis tanggal <?= date('d-m-Y', strtotime($pengajuan['tanggal_approve'])) ?>
        </p>
    </div>

    <!-- ✅ JUDUL TABEL - TIDAK BOLEH TERPISAH DARI TABEL -->
    <h4>DAFTAR BARANG YANG DIHAPUSKAN</h4>
    
    <!-- DAFTAR BARANG -->
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Kode Barang</th>
                <th width="8%">NUP</th>
                <th width="25%">Nama Barang</th>
                <th width="15%">Merk/Type</th>
                <th width="12%">Kondisi</th>
                <th width="20%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1; 
            foreach ($perangkat as $item): 
            ?>
            <tr>
                <td style="text-align: center;"><?= $no++ ?></td>
                <td><?= htmlspecialchars($item['kode_barang']) ?></td>
                <td style="text-align: center;"><?= htmlspecialchars($item['nup']) ?></td>
                <td><?= htmlspecialchars($item['nama_barang']) ?></td>
                <td><?= htmlspecialchars($item['merk'] ?? '-') ?></td>
                <td style="text-align: center;"><?= htmlspecialchars($item['kondisi']) ?></td>
                <td><?= htmlspecialchars($item['keterangan'] ?? 'Rusak berat, tidak dapat diperbaiki') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right;"><strong>TOTAL</strong></td>
                <td colspan="4"><strong><?= count($perangkat) ?> Unit Perangkat</strong></td>
            </tr>
        </tfoot>
    </table>

    <!-- NILAI PEROLEHAN -->
    <?php 
    $total_nilai = array_sum(array_column($perangkat, 'nilai_perolehan'));
    if ($total_nilai > 0): 
    ?>
    <p style="margin-top: 20px;">
        <strong>Total Nilai Perolehan:</strong> Rp <?= number_format($total_nilai, 0, ',', '.') ?>,-
    </p>
    <?php endif; ?>

    <!-- PENUTUP -->
    <div class="isi-surat">
        <p>
            Demikian Berita Acara ini dibuat dengan sebenarnya untuk dipergunakan sebagaimana mestinya.
        </p>
    </div>

    <!-- TANDA TANGAN -->
    <div class="ttd-section">
        <div class="ttd-box ttd-right">
            <p>Pekanbaru, <?= $tanggal_ttd ?></p>
            <p><strong>Kepala BPS Provinsi Riau</strong></p>
            <div class="ttd-space"></div>
            <p><strong><u><?= $pengajuan['approved_by'] ?? '___________________' ?></u></strong></p>
            <p>NIP. ___________________</p>
        </div>
    </div>

    <div style="clear: both;"></div>

    <!-- FOOTER -->
    <div class="footer-note">
        <p>Catatan:</p>
        <ul style="margin: 5px 0; padding-left: 20px;">
            <li>Penghapusan dilakukan sesuai ketentuan yang berlaku</li>
            <li>Barang yang dihapus akan dimusnahkan atau dihibahkan sesuai peraturan</li>
            <li>Dokumen ini sah dengan tanda tangan basah</li>
        </ul>
        <p style="margin-top: 20px; font-size: 9pt; text-align: center;">
            Dicetak pada: <?= date('d-m-Y H:i:s') ?> | Nomor SK: <?= $pengajuan['nomor_sk'] ?>
        </p>
    </div>
</body>
</html>