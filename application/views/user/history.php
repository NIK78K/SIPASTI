<?php
/*
 * VIEW: user/history.php
 * Multi-dokumen rapat: Undangan, Notulensi, Dokumentasi, Daftar Hadir, Lampiran Lain
 * Khusus tiket ruang_rapat (kategori 'umum' dengan ref:peminjaman_id)
 */

$statusLabel = [
    'Menunggu'    => 'Menunggu',
    'On Progress' => 'Diproses',
    'Selesai'     => 'Selesai',
    'Dibatalkan'  => 'Dibatalkan'
];

$statusClass = [
    'Menunggu'    => 'bg-orange-100 text-orange-700',
    'On Progress' => 'bg-blue-100 text-blue-700',
    'Selesai'     => 'bg-emerald-100 text-emerald-700',
    'Dibatalkan'  => 'bg-red-100 text-red-700'
];

$kategoriBadge = [
    'permintaan' => 'bg-blue-100 text-blue-700',
    'kerusakan'  => 'bg-red-100 text-red-700',
    'umum'       => 'bg-purple-100 text-purple-700'
];

$subKategoriBadge = 'bg-slate-100 text-slate-700';

$no = isset($page) ? $page + 1 : 1;

$current_page = $current_page ?? 1;
$total_pages  = $total_pages ?? 1;
$base_url     = $base_url ?? current_url();

// Helper: ambil peminjaman_id dari jenis_kerusakan
function getPeminjamanId($jenis_kerusakan) {
    if (preg_match('/\[ref:peminjaman_id=(\d+)\]/', $jenis_kerusakan ?? '', $m)) {
        return (int)$m[1];
    }
    return 0;
}

// Helper: cek apakah tiket adalah tiket ruang rapat
function isRuangRapat($t) {
    return !empty($t['jenis_kerusakan']) &&
           strpos($t['jenis_kerusakan'], '[ref:peminjaman_id=') !== false;
}

// Cek apakah ada tiket ruang rapat di halaman ini (untuk tampilkan kolom dokumen)
$ada_rapat_ticket = false;
foreach (($tickets ?? []) as $t) {
    if (isRuangRapat($t)) {
        $ada_rapat_ticket = true;
        break;
    }
}
?>

<h1 class="mt-8 md:mt-16 text-xl md:text-2xl font-bold text-[#0B2E6B] mb-6 md:mb-8 text-center px-4">
    Riwayat Tiket Saya
</h1>

<div class="max-w-7xl mx-auto mb-4 md:mb-6 px-4">
    <a href="<?= base_url('user/dashboard') ?>"
       class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-700 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Dashboard
    </a>
</div>

<!-- Flash message -->
<?php if ($this->session->flashdata('success')): ?>
<div class="max-w-7xl mx-auto px-4 mb-4">
    <div class="flex items-center gap-3 px-5 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <?= $this->session->flashdata('success') ?>
    </div>
</div>
<?php endif ?>

<?php if ($this->session->flashdata('error')): ?>
<div class="max-w-7xl mx-auto px-4 mb-4">
    <div class="flex items-center gap-3 px-5 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm font-medium">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <?= $this->session->flashdata('error') ?>
    </div>
</div>
<?php endif ?>

<div class="max-w-7xl mx-auto px-4">

<!-- ============================================================
     DESKTOP TABLE VIEW
============================================================ -->
<div class="hidden md:block overflow-x-auto rounded-2xl bg-white border border-blue-100 shadow-[0_20px_40px_rgba(13,110,253,0.12)]">
    <table class="w-full text-xs">
        <thead class="bg-blue-50 text-blue-700 text-[10px] uppercase tracking-wider">
        <tr>
            <th class="px-3 py-3 text-center w-10">No</th>
            <th class="px-3 py-3 text-left">Kode</th>
            <th class="px-3 py-3 text-left">Kategori</th>
            <th class="px-3 py-3 text-left">Sub Kategori</th>
            <th class="px-3 py-3 text-left">Perangkat</th>
            <th class="px-3 py-3 text-left">Tanggal</th>
            <th class="px-3 py-3 text-left">Status</th>
            <th class="px-3 py-3 text-left">Teknisi</th>
            <th class="px-3 py-3 text-center">Foto</th>
            <?php if ($ada_rapat_ticket): ?>
            <th class="px-3 py-3 text-center" title="Dokumen rapat (Undangan, Notulensi, dll)">
                📄 Dokumen Rapat
            </th>
            <?php endif ?>
            <th class="px-3 py-3 text-center">Catatan</th>
            <th class="px-3 py-3 text-center">Rating</th>
            <th class="px-3 py-3 text-center">Aksi</th>
        </tr>
        </thead>

        <tbody class="divide-y divide-blue-100">

        <?php if (empty($tickets)): ?>
        <tr>
            <td colspan="<?= $ada_rapat_ticket ? 13 : 12 ?>" class="py-16 text-center text-slate-400">
                Belum ada tiket yang dibuat
            </td>
        </tr>

        <?php else: foreach ($tickets as $t): ?>
        <?php
            $status        = $t['status'] ?? 'Menunggu';
            $badge         = $statusClass[$status] ?? 'bg-slate-100 text-slate-600';
            $label         = $statusLabel[$status] ?? $status;
            $slugKategori  = $t['kategori_slug'] ?? null;
            $namaKategori  = $t['nama_kategori'] ?? '-';
            $kategoriClass = $kategoriBadge[$slugKategori] ?? 'bg-slate-100 text-slate-600';

            // Apakah tiket ruang rapat?
            $is_rapat = isRuangRapat($t);
            $peminjaman_id_rapat = $is_rapat ? getPeminjamanId($t['jenis_kerusakan']) : 0;

            // Ambil dokumen rapat jika ada
            $dokumen_rapat = [];
            if ($is_rapat && $peminjaman_id_rapat) {
                $dokumen_rapat = $this->db
                    ->where('peminjaman_id', $peminjaman_id_rapat)
                    ->order_by('created_at', 'ASC')
                    ->get('dokumen_peminjaman_rapat')
                    ->result_array();
            }

            $ada_layanan = in_array($status, ['On Progress', 'Selesai']) && !empty($t['layanan_link']);

            $layanan_data = $ada_layanan ? htmlspecialchars(json_encode([
                'sub_kategori'    => $t['nama_sub_kategori'] ?? '-',
                'kode_tiket'      => $t['kode_tiket'],
                'layanan_link'    => $t['layanan_link'],
                'undangan_link'   => $t['undangan_link'] ?? '',
                'layanan_catatan' => $t['layanan_catatan'] ?? '',
                'tanggal'         => !empty($t['tanggal']) ? date('d M Y', strtotime($t['tanggal'])) : '',
                'jam'             => $t['jam'] ?? '',
                'durasi'          => $t['durasi'] ?? '',
            ]), ENT_QUOTES) : '';

            $ada_catatan  = !empty($t['layanan_catatan']) || !empty($t['layanan_file']);
            $catatan_data = $ada_catatan ? htmlspecialchars(json_encode([
                'kode_tiket'      => $t['kode_tiket'],
                'nama_teknisi'    => $t['nama_teknisi'] ?? '-',
                'layanan_catatan' => $t['layanan_catatan'] ?? '',
                'layanan_file'    => $t['layanan_file'] ?? '',
            ]), ENT_QUOTES) : '';
        ?>
        <tr class="hover:bg-blue-50/60 transition">
            <td class="px-3 py-3 text-center text-slate-500"><?= $no++ ?></td>
            <td class="px-3 py-3 font-mono text-[10px] text-slate-700"><?= $t['kode_tiket'] ?></td>

            <td class="px-3 py-3">
                <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold <?= $kategoriClass ?>">
                    <?= $namaKategori ?>
                </span>
            </td>

            <td class="px-3 py-3">
                <?php if (!empty($t['nama_sub_kategori'])): ?>
                    <?php if ($ada_layanan): ?>
                        <button onclick="openLayananModal(<?= $layanan_data ?>)"
                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold
                                       bg-teal-100 text-teal-700 hover:bg-teal-200 border border-teal-300 transition cursor-pointer">
                            <?= $t['nama_sub_kategori'] ?>
                            <svg class="w-2.5 h-2.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                            </svg>
                        </button>
                    <?php else: ?>
                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] <?= $subKategoriBadge ?>">
                            <?= $t['nama_sub_kategori'] ?>
                        </span>
                    <?php endif ?>
                <?php else: ?>
                    <span class="text-slate-400 text-[10px]">-</span>
                <?php endif ?>
            </td>

            <td class="px-3 py-3 text-slate-700 text-[11px]">
                <?= !empty($t['nama_barang']) ? (strlen($t['nama_barang']) > 20 ? substr($t['nama_barang'], 0, 20) . '...' : $t['nama_barang']) : '-' ?>
            </td>

            <td class="px-3 py-3 text-slate-500 text-[10px]">
                <?= !empty($t['created_at']) ? date('d M Y', strtotime($t['created_at'])) : '-' ?>
            </td>

            <td class="px-3 py-3">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold <?= $badge ?>">
                    <?= $label ?>
                </span>
            </td>

            <td class="px-3 py-3">
                <?php if (in_array($status, ['On Progress', 'Selesai', 'Dibatalkan']) && !empty($t['nama_teknisi'])): ?>
                    <div class="flex items-center gap-1.5">
                        <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-blue-600 font-semibold text-[10px]"><?= strtoupper(substr($t['nama_teknisi'], 0, 1)) ?></span>
                        </div>
                        <span class="text-slate-700 text-[11px]"><?= $t['nama_teknisi'] ?></span>
                    </div>
                <?php else: ?>
                    <span class="text-slate-400 text-[10px]">-</span>
                <?php endif ?>
            </td>

            <!-- FOTO -->
            <td class="px-3 py-3 text-center text-[10px]">
                <?php if (!empty($t['foto'])): ?>
                    <a href="<?= base_url('uploads/tiket/'.$t['foto']) ?>" target="_blank" class="text-blue-600 hover:underline">Lihat</a>
                <?php else: ?>
                    <span class="text-slate-400">-</span>
                <?php endif ?>
            </td>

            <!-- ============================
                 KOLOM DOKUMEN RAPAT
            ============================ -->
            <?php if ($ada_rapat_ticket): ?>
            <td class="px-3 py-3 text-center">
                <?php if ($is_rapat): ?>
                    <div class="flex flex-col items-center gap-1 min-w-[80px]">
                        <?php foreach ($dokumen_rapat as $dok): ?>
                        <div class="flex items-center gap-1">
                            <a href="<?= base_url('uploads/dokumen_peserta/'.$dok['nama_file']) ?>"
                               target="_blank"
                               title="<?= htmlspecialchars($dok['nama_asli'] ?? $dok['nama_file']) ?>"
                               class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full
                                      bg-violet-100 text-violet-700 hover:bg-violet-200
                                      border border-violet-300 text-[9px] font-semibold transition whitespace-nowrap">
                                📄 <?= htmlspecialchars($dok['jenis_dokumen']) ?>
                            </a>
                            <a href="<?= base_url('user_ticket/hapus_dokumen_item/'.$dok['id']) ?>"
                               onclick="return confirm('Hapus dokumen <?= htmlspecialchars($dok['jenis_dokumen']) ?>?')"
                               class="text-red-300 hover:text-red-600 text-[9px] transition" title="Hapus">✕</a>
                        </div>
                        <?php endforeach ?>
                        <button onclick="openUploadDokumenRapat(<?= $t['id'] ?>, '<?= $t['kode_tiket'] ?>')"
                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full
                                       bg-slate-100 text-slate-600 hover:bg-violet-100 hover:text-violet-700
                                       border border-slate-200 hover:border-violet-300
                                       text-[9px] font-semibold transition mt-0.5">
                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            Unggah
                        </button>
                    </div>
                <?php else: ?>
                    <span class="text-slate-300 text-[10px]">—</span>
                <?php endif ?>
            </td>
            <?php endif ?>

            <!-- CATATAN TEKNISI -->
            <td class="px-3 py-3 text-center text-[10px]">
                <?php if ($ada_catatan): ?>
                    <button onclick="openCatatanModal(<?= $catatan_data ?>)"
                            class="inline-flex items-center gap-1 px-2 py-1 rounded-full
                                   bg-purple-100 text-purple-700 hover:bg-purple-200
                                   border border-purple-300 text-[10px] font-semibold transition">
                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                                     m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Lihat
                    </button>
                <?php else: ?>
                    <span class="text-slate-400">-</span>
                <?php endif ?>
            </td>

            <!-- RATING -->
            <td class="px-3 py-3 text-center">
                <?php if ($status === 'Selesai'): ?>
                    <?php if (empty($t['rating'])): ?>
                        <button onclick="openRatingModal(<?= $t['id'] ?>)"
                                class="px-3 py-1 rounded-full bg-gradient-to-r from-orange-400 to-orange-500
                                       text-white text-[10px] font-semibold shadow hover:brightness-110 whitespace-nowrap">
                            Beri Rating
                        </button>
                    <?php else: ?>
                        <div class="flex justify-center gap-0.5 text-yellow-400 text-sm">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <span class="<?= $i <= $t['rating'] ? '' : 'text-slate-300' ?>">★</span>
                            <?php endfor ?>
                        </div>
                    <?php endif ?>
                <?php else: ?>
                    <span class="text-slate-400 text-[10px]">-</span>
                <?php endif ?>
            </td>

            <!-- AKSI -->
            <td class="px-3 py-3 text-center">
                <div class="flex flex-col gap-1 items-center">
                    <?php if (in_array($status, ['Menunggu', 'On Progress'])): ?>
                        <a href="<?= base_url('user_ticket/edit_ticket/'.$t['id']) ?>"
                           class="inline-flex items-center gap-1 px-3 py-1 rounded-full
                                  bg-blue-100 text-blue-700 hover:bg-blue-200
                                  border border-blue-300 text-[10px] font-semibold transition">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M16.862 3.487a2.25 2.25 0 113.182 3.182L7.5 19.213l-4 1 1-4 12.362-12.726z"/>
                            </svg>
                            Edit
                        </a>
                        <button onclick="konfirmasiBatal(<?= $t['id'] ?>, '<?= $t['kode_tiket'] ?>')"
                                class="inline-flex items-center gap-1 px-3 py-1 rounded-full
                                       bg-red-100 text-red-700 hover:bg-red-200
                                       border border-red-300 text-[10px] font-semibold transition">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Batalkan
                        </button>
                    <?php else: ?>
                        <span class="text-slate-400 text-[10px]">-</span>
                    <?php endif ?>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>

        <tr class="bg-slate-50">
            <td colspan="<?= $ada_rapat_ticket ? 13 : 12 ?>" class="px-3 py-3">
                <div class="flex justify-between items-center text-xs">
                    <span class="text-slate-500">Hal <?= $current_page ?> dari <?= $total_pages ?></span>
                    <div class="flex gap-2">
                        <?php if ($current_page > 1): ?>
                            <a href="<?= $base_url.'?page='.($current_page-1) ?>" class="px-3 py-1 rounded-full border text-blue-600 hover:bg-blue-50 text-[10px]">Sebelumnya</a>
                        <?php endif ?>
                        <?php if ($current_page < $total_pages): ?>
                            <a href="<?= $base_url.'?page='.($current_page+1) ?>" class="px-3 py-1 rounded-full bg-blue-600 text-white hover:bg-blue-700 text-[10px]">Berikutnya</a>
                        <?php endif ?>
                    </div>
                </div>
            </td>
        </tr>

        <?php endif ?>
        </tbody>
    </table>
</div>

<!-- ============================================================
     MOBILE CARD VIEW
============================================================ -->
<div class="md:hidden space-y-4">
    <?php if (empty($tickets)): ?>
        <div class="py-16 text-center text-slate-400 bg-white rounded-2xl border border-blue-100">
            Belum ada tiket yang dibuat
        </div>
    <?php else: ?>
    <?php
    $mobileNo = isset($page) ? $page + 1 : 1;
    foreach ($tickets as $t):
        $status        = $t['status'] ?? 'Menunggu';
        $badge         = $statusClass[$status] ?? 'bg-slate-100 text-slate-600';
        $label         = $statusLabel[$status] ?? $status;
        $slugKategori  = $t['kategori_slug'] ?? null;
        $namaKategori  = $t['nama_kategori'] ?? '-';
        $kategoriClass = $kategoriBadge[$slugKategori] ?? 'bg-slate-100 text-slate-600';

        $is_rapat           = isRuangRapat($t);
        $peminjaman_id_mob  = $is_rapat ? getPeminjamanId($t['jenis_kerusakan']) : 0;

        $dokumen_rapat_mob = [];
        if ($is_rapat && $peminjaman_id_mob) {
            $dokumen_rapat_mob = $this->db
                ->where('peminjaman_id', $peminjaman_id_mob)
                ->order_by('created_at', 'ASC')
                ->get('dokumen_peminjaman_rapat')
                ->result_array();
        }

        $ada_layanan_mob = in_array($status, ['On Progress', 'Selesai']) && !empty($t['layanan_link']);
        $layanan_data_mob = $ada_layanan_mob ? htmlspecialchars(json_encode([
            'sub_kategori'    => $t['nama_sub_kategori'] ?? '-',
            'kode_tiket'      => $t['kode_tiket'],
            'layanan_link'    => $t['layanan_link'],
            'undangan_link'   => $t['undangan_link'] ?? '',
            'layanan_catatan' => $t['layanan_catatan'] ?? '',
            'tanggal'         => !empty($t['tanggal']) ? date('d M Y', strtotime($t['tanggal'])) : '',
            'jam'             => $t['jam'] ?? '',
            'durasi'          => $t['durasi'] ?? '',
        ]), ENT_QUOTES) : '';

        $ada_catatan_mob  = !empty($t['layanan_catatan']) || !empty($t['layanan_file']);
        $catatan_data_mob = $ada_catatan_mob ? htmlspecialchars(json_encode([
            'kode_tiket'      => $t['kode_tiket'],
            'nama_teknisi'    => $t['nama_teknisi'] ?? '-',
            'layanan_catatan' => $t['layanan_catatan'] ?? '',
            'layanan_file'    => $t['layanan_file'] ?? '',
        ]), ENT_QUOTES) : '';
    ?>

    <div class="bg-white rounded-2xl border border-blue-100 shadow-lg p-4">

        <div class="flex items-start justify-between mb-3">
            <div>
                <div class="text-xs text-slate-400 mb-1">Tiket #<?= $mobileNo++ ?></div>
                <div class="font-mono text-sm font-semibold text-slate-700"><?= $t['kode_tiket'] ?></div>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold <?= $badge ?>">
                <?= $label ?>
            </span>
        </div>

        <div class="space-y-2 mb-3">
            <div class="flex items-start">
                <span class="text-xs text-slate-500 w-24 flex-shrink-0">Kategori:</span>
                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold <?= $kategoriClass ?>">
                    <?= $namaKategori ?>
                </span>
            </div>

            <?php if (!empty($t['nama_sub_kategori'])): ?>
            <div class="flex items-start">
                <span class="text-xs text-slate-500 w-24 flex-shrink-0">Sub Kategori:</span>
                <?php if ($ada_layanan_mob): ?>
                    <button onclick="openLayananModal(<?= $layanan_data_mob ?>)"
                            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold
                                   bg-teal-100 text-teal-700 hover:bg-teal-200 border border-teal-300 transition">
                        <?= $t['nama_sub_kategori'] ?>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                        </svg>
                    </button>
                <?php else: ?>
                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs <?= $subKategoriBadge ?>">
                        <?= $t['nama_sub_kategori'] ?>
                    </span>
                <?php endif ?>
            </div>
            <?php endif ?>

            <div class="flex items-start">
                <span class="text-xs text-slate-500 w-24 flex-shrink-0">Perangkat:</span>
                <span class="text-xs text-slate-700"><?= $t['nama_barang'] ?? '-' ?></span>
            </div>

            <div class="flex items-start">
                <span class="text-xs text-slate-500 w-24 flex-shrink-0">Tanggal:</span>
                <span class="text-xs text-slate-700">
                    <?= !empty($t['created_at']) ? date('d M Y', strtotime($t['created_at'])) : '-' ?>
                </span>
            </div>

            <?php if (in_array($status, ['On Progress', 'Selesai', 'Dibatalkan']) && !empty($t['nama_teknisi'])): ?>
            <div class="flex items-start">
                <span class="text-xs text-slate-500 w-24 flex-shrink-0">Teknisi:</span>
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <span class="text-blue-600 font-semibold text-xs"><?= strtoupper(substr($t['nama_teknisi'], 0, 1)) ?></span>
                    </div>
                    <span class="text-xs text-slate-700"><?= $t['nama_teknisi'] ?></span>
                </div>
            </div>
            <?php endif ?>

            <?php if (!empty($t['foto'])): ?>
            <div class="flex items-start">
                <span class="text-xs text-slate-500 w-24 flex-shrink-0">Foto:</span>
                <a href="<?= base_url('uploads/tiket/'.$t['foto']) ?>" target="_blank"
                   class="text-xs text-blue-600 hover:underline">Lihat Foto</a>
            </div>
            <?php endif ?>

            <!-- Dokumen Rapat (mobile) -->
            <?php if ($is_rapat): ?>
            <div class="flex items-start gap-2 pt-1">
                <span class="text-xs text-slate-500 w-24 flex-shrink-0 pt-0.5">Dokumen:</span>
                <div class="flex flex-col gap-1.5">
                    <?php foreach ($dokumen_rapat_mob as $dok): ?>
                    <div class="flex items-center gap-2">
                        <a href="<?= base_url('uploads/dokumen_peserta/'.$dok['nama_file']) ?>"
                           target="_blank"
                           class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold
                                  bg-violet-100 text-violet-700 border border-violet-300 hover:bg-violet-200 transition">
                            📄 <?= htmlspecialchars($dok['jenis_dokumen']) ?>
                        </a>
                        <a href="<?= base_url('user_ticket/hapus_dokumen_item/'.$dok['id']) ?>"
                           onclick="return confirm('Hapus dokumen <?= htmlspecialchars($dok['jenis_dokumen']) ?>?')"
                           class="text-red-300 hover:text-red-600 text-xs transition">✕</a>
                    </div>
                    <?php endforeach ?>
                    <button onclick="openUploadDokumenRapat(<?= $t['id'] ?>, '<?= $t['kode_tiket'] ?>')"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold
                                   bg-violet-50 text-violet-700 border border-violet-200 hover:bg-violet-100 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Unggah Dokumen
                    </button>
                </div>
            </div>
            <?php endif ?>

            <?php if ($ada_catatan_mob): ?>
            <div class="flex items-start">
                <span class="text-xs text-slate-500 w-24 flex-shrink-0">Catatan:</span>
                <button onclick="openCatatanModal(<?= $catatan_data_mob ?>)"
                        class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold
                               bg-purple-100 text-purple-700 hover:bg-purple-200 border border-purple-300 transition">
                    Lihat Catatan
                </button>
            </div>
            <?php endif ?>
        </div>

        <?php if ($status === 'Selesai'): ?>
            <div class="pt-3 border-t border-slate-100">
                <?php if (empty($t['rating'])): ?>
                    <button onclick="openRatingModal(<?= $t['id'] ?>)"
                            class="w-full px-4 py-2 rounded-xl bg-gradient-to-r from-orange-400 to-orange-500
                                   text-white text-sm font-semibold shadow hover:brightness-110">
                        Beri Rating
                    </button>
                <?php else: ?>
                    <div class="text-center">
                        <div class="text-xs text-slate-500 mb-1">Rating Anda</div>
                        <div class="flex justify-center gap-1 text-yellow-400 text-lg">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <span class="<?= $i <= $t['rating'] ? '' : 'text-slate-300' ?>">★</span>
                            <?php endfor ?>
                        </div>
                    </div>
                <?php endif ?>
            </div>
        <?php endif ?>

        <?php if (in_array($status, ['Menunggu', 'On Progress'])): ?>
            <div class="pt-3 border-t border-slate-100 flex flex-col gap-2">
                <a href="<?= base_url('user_ticket/edit_ticket/'.$t['id']) ?>"
                   class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-xl
                          bg-blue-50 border border-blue-200 text-blue-700 text-sm font-semibold hover:bg-blue-100 transition">
                    Edit Tiket
                </a>
                <button onclick="konfirmasiBatal(<?= $t['id'] ?>, '<?= $t['kode_tiket'] ?>')"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-xl
                               bg-red-50 border border-red-200 text-red-700 text-sm font-semibold hover:bg-red-100 transition">
                    Batalkan Tiket
                </button>
            </div>
        <?php endif ?>

    </div>
    <?php endforeach; ?>

    <div class="bg-white rounded-2xl border border-blue-100 shadow-lg p-4">
        <div class="flex justify-between items-center text-sm">
            <span class="text-slate-500">Hal <?= $current_page ?> dari <?= $total_pages ?></span>
            <div class="flex gap-2">
                <?php if ($current_page > 1): ?>
                    <a href="<?= $base_url.'?page='.($current_page-1) ?>" class="px-3 py-1.5 rounded-lg border text-blue-600 hover:bg-blue-50 text-xs">Sebelum</a>
                <?php endif ?>
                <?php if ($current_page < $total_pages): ?>
                    <a href="<?= $base_url.'?page='.($current_page+1) ?>" class="px-3 py-1.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-xs">Berikutnya</a>
                <?php endif ?>
            </div>
        </div>
    </div>
    <?php endif ?>
</div>
</div>


<!-- ============================================================
     MODAL UNGGAH DOKUMEN RAPAT (multi-jenis)
============================================================ -->
<div id="uploadDokumenModal"
     class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 px-4">
    <div class="w-full max-w-sm rounded-2xl bg-white border border-violet-200 overflow-hidden
                shadow-[0_25px_60px_rgba(0,0,0,0.25)]">

        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4"
             style="background:linear-gradient(135deg,#7c3aed 0%,#a855f7 100%)">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white font-bold text-sm leading-tight">Unggah Dokumen Rapat</p>
                    <p class="text-white/60 text-[11px]" id="udm_kode"></p>
                </div>
            </div>
            <button onclick="closeUploadDokumen()"
                    class="w-7 h-7 rounded-full bg-white/15 flex items-center justify-center
                           text-white hover:bg-white/25 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Body -->
        <form id="uploadDokumenForm" method="post" enctype="multipart/form-data" class="p-6 space-y-4">

            <!-- Pilih Jenis Dokumen -->
            <div>
                <label class="text-xs font-semibold text-slate-600 mb-1.5 block">
                    Jenis Dokumen <span class="text-red-500">*</span>
                </label>
                <select name="jenis_dokumen" id="udm_jenis"
                        class="w-full h-11 rounded-xl px-4 border-2 border-gray-200
                               focus:border-violet-500 focus:ring-4 focus:ring-violet-100
                               text-gray-800 text-sm transition bg-white">
                    <option value="">── Pilih jenis dokumen ──</option>
                    <option value="Undangan">📩 Undangan</option>
                    <option value="Notulensi">📝 Notulensi</option>
                    <option value="Dokumentasi">📸 Dokumentasi</option>
                    <option value="Daftar Hadir">✅ Daftar Hadir</option>
                    <option value="Lampiran Lain">📎 Lampiran Lain</option>
                </select>
            </div>

            <!-- Upload File -->
            <div>
                <label class="text-xs font-semibold text-slate-600 mb-1.5 block">
                    File PDF <span class="text-red-500">*</span>
                    <span class="text-slate-400 font-normal">(maks. 2 MB)</span>
                </label>
                <label for="udm_file"
                       class="flex flex-col items-center justify-center gap-2 p-5 rounded-xl
                              border-2 border-dashed border-violet-200 bg-violet-50/50
                              hover:border-violet-400 hover:bg-violet-50 cursor-pointer transition">
                    <svg class="w-8 h-8 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-sm font-semibold text-violet-700" id="udm_label">Pilih file PDF</p>
                    <input type="file" id="udm_file" name="dokumen_peserta"
                           accept=".pdf,application/pdf" class="sr-only"
                           onchange="udmFileChange(this)">
                </label>
                <p id="udm_error" class="hidden mt-1.5 text-xs text-red-600 font-medium"></p>
            </div>

            <p class="text-[10px] text-slate-400 leading-relaxed">
                Jika sudah ada dokumen dengan jenis yang sama, file lama akan digantikan otomatis.
            </p>

            <div class="flex gap-3 pt-1">
                <button type="button" onclick="closeUploadDokumen()"
                        class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200
                               text-slate-600 text-sm font-medium hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit" id="udm_submit"
                        class="flex-1 px-4 py-2.5 rounded-xl bg-violet-600 text-white
                               text-sm font-semibold hover:bg-violet-700 shadow
                               disabled:opacity-50 disabled:cursor-not-allowed transition"
                        disabled>
                    Unggah
                </button>
            </div>
        </form>
    </div>
</div>


<!-- ============================================================
     MODAL RATING
============================================================ -->
<div id="ratingModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50 px-4">
    <div class="w-full max-w-md rounded-2xl bg-white border border-orange-200 p-6 shadow-[0_25px_60px_rgba(0,0,0,0.25)]">
        <h2 class="text-lg font-bold text-slate-800 mb-4">Beri Rating Layanan</h2>
        <form method="post" id="ratingForm">
            <input type="hidden" name="rating" id="ratingValue">
            <div class="flex justify-center gap-2 mb-6 text-3xl">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <span onclick="setRating(<?= $i ?>)" id="star<?= $i ?>"
                          class="cursor-pointer text-slate-300 hover:text-yellow-400 transition select-none">★</span>
                <?php endfor ?>
            </div>
            <textarea name="komentar" rows="3" placeholder="Saran / masukan (opsional)"
                class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm mb-5
                       focus:ring-2 focus:ring-orange-400/30 focus:outline-none"></textarea>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeRatingModal()"
                    class="px-4 py-2 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-100 transition">Batal</button>
                <button type="submit" id="submitRatingBtn"
                    class="px-5 py-2 rounded-xl bg-gradient-to-r from-orange-400 to-orange-500
                           hover:brightness-110 text-white font-semibold shadow transition
                           disabled:opacity-50 disabled:cursor-not-allowed">
                    Kirim Rating
                </button>
            </div>
        </form>
    </div>
</div>


<!-- ============================================================
     MODAL LAYANAN / ZOOM
============================================================ -->
<div id="layananModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 px-4 py-6">
    <div style="width:100%;max-width:440px;background:#fff;border-radius:16px;overflow:hidden;
                border:0.5px solid rgba(0,0,0,0.08);box-shadow:0 20px 60px rgba(0,0,0,0.2);
                display:flex;flex-direction:column;max-height:calc(100vh - 48px);">
        <div style="background:#0f766e;padding:18px 22px;display:flex;align-items:center;justify-content:space-between;flex-shrink:0;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:36px;height:36px;border-radius:8px;background:rgba(255,255,255,0.15);
                            display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                    </svg>
                </div>
                <div>
                    <p style="margin:0;font-size:14px;font-weight:600;color:#fff;" id="lm_subkategori">Detail Layanan</p>
                    <p style="margin:2px 0 0;font-size:11px;color:rgba(255,255,255,0.55);" id="lm_kode"></p>
                </div>
            </div>
            <button onclick="closeLayananModal()"
                    style="width:28px;height:28px;border-radius:50%;background:rgba(255,255,255,0.15);
                           border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="12" height="12" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div style="padding:20px 22px;display:flex;flex-direction:column;gap:14px;overflow-y:auto;flex:1;">
            <div id="lm_jadwal_wrap" style="display:none;">
                <p style="margin:0 0 8px;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.07em;">Jadwal</p>
                <div style="background:#f8fafc;border-radius:10px;padding:10px 14px;display:flex;flex-direction:column;gap:6px;border:0.5px solid #e2e8f0;">
                    <div id="lm_tanggal_row" style="display:none;align-items:center;gap:10px;">
                        <svg width="14" height="14" fill="none" stroke="#0f766e" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span style="font-size:12px;color:#64748b;width:50px;flex-shrink:0;">Tanggal</span>
                        <span style="font-size:12px;font-weight:600;color:#1e293b;" id="lm_tanggal"></span>
                    </div>
                    <div id="lm_jam_row" style="display:none;align-items:center;gap:10px;">
                        <svg width="14" height="14" fill="none" stroke="#0f766e" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span style="font-size:12px;color:#64748b;width:50px;flex-shrink:0;">Jam</span>
                        <span style="font-size:12px;font-weight:600;color:#1e293b;" id="lm_jam"></span>
                    </div>
                    <div id="lm_durasi_row" style="display:none;align-items:center;gap:10px;">
                        <svg width="14" height="14" fill="none" stroke="#0f766e" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span style="font-size:12px;color:#64748b;width:50px;flex-shrink:0;">Durasi</span>
                        <span style="font-size:12px;font-weight:600;color:#1e293b;" id="lm_durasi"></span>
                    </div>
                </div>
            </div>
            <div>
                <p style="margin:0 0 8px;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.07em;">Link Layanan</p>
                <a id="lm_link" href="#" target="_blank"
                   style="display:flex;align-items:center;gap:12px;background:#f0fdf9;border:0.5px solid #99f6e4;border-radius:10px;padding:12px 14px;text-decoration:none;">
                    <div style="width:36px;height:36px;border-radius:8px;background:#0f766e;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="16" height="16" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/></svg>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <p style="margin:0;font-size:12px;font-weight:600;color:#0f766e;">Klik untuk bergabung</p>
                        <p style="margin:3px 0 0;font-size:11px;color:#64748b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" id="lm_link_text"></p>
                    </div>
                </a>
            </div>
            <div id="lm_undangan_wrap" style="display:none;">
                <p style="margin:0 0 8px;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.07em;">Link Kirim Undangan</p>
                <div style="background:#f8fafc;border-radius:10px;padding:10px 14px;border:0.5px solid #e2e8f0;display:flex;align-items:center;gap:10px;">
                    <p style="margin:0;font-size:11px;color:#475569;word-break:break-all;line-height:1.5;flex:1;min-width:0;" id="lm_undangan_text"></p>
                    <button onclick="copyUndangan()" id="lm_copy_btn"
                            style="flex-shrink:0;display:flex;align-items:center;gap:5px;padding:5px 12px;
                                   border-radius:8px;border:0.5px solid #0f766e;background:#f0fdf9;
                                   color:#0f766e;font-size:11px;font-weight:600;cursor:pointer;font-family:inherit;">
                        <span id="lm_copy_label">Salin</span>
                    </button>
                </div>
            </div>
            <div id="lm_catatan_wrap" style="display:none;">
                <p style="margin:0 0 8px;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.07em;">Catatan Teknisi</p>
                <div style="background:#fffbeb;border-radius:10px;border-left:3px solid #f59e0b;border:0.5px solid #fde68a;padding:12px 14px;">
                    <p style="margin:0;font-size:13px;color:#78350f;line-height:1.7;white-space:pre-line;" id="lm_catatan"></p>
                </div>
            </div>
        </div>
        <div style="padding:14px 22px 20px;flex-shrink:0;border-top:0.5px solid #f1f5f9;">
            <button onclick="closeLayananModal()"
                    style="width:100%;padding:10px;border-radius:10px;border:0.5px solid #e2e8f0;
                           background:transparent;font-size:13px;color:#64748b;cursor:pointer;font-family:inherit;">
                Tutup
            </button>
        </div>
    </div>
</div>


<!-- ============================================================
     MODAL CATATAN TEKNISI
============================================================ -->
<div id="catatanModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 px-4 py-6">
    <div style="width:100%;max-width:440px;background:#fff;border-radius:16px;overflow:hidden;
                border:0.5px solid rgba(0,0,0,0.08);box-shadow:0 20px 60px rgba(0,0,0,0.2);
                display:flex;flex-direction:column;max-height:calc(100vh - 48px);">
        <div style="background:#7c3aed;padding:18px 22px;display:flex;align-items:center;justify-content:space-between;flex-shrink:0;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:36px;height:36px;border-radius:8px;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;">
                    <svg width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <p style="margin:0;font-size:14px;font-weight:600;color:#fff;">Catatan Teknisi</p>
                    <p style="margin:2px 0 0;font-size:11px;color:rgba(255,255,255,0.6);" id="cm_kode"></p>
                </div>
            </div>
            <button onclick="closeCatatanModal()"
                    style="width:28px;height:28px;border-radius:50%;background:rgba(255,255,255,0.15);border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;">
                <svg width="12" height="12" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div style="padding:20px 22px;display:flex;flex-direction:column;gap:14px;overflow-y:auto;flex:1;">
            <div style="display:flex;align-items:center;gap:10px;background:#f8fafc;border-radius:10px;padding:10px 14px;border:0.5px solid #e2e8f0;">
                <svg width="14" height="14" fill="none" stroke="#7c3aed" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span style="font-size:12px;color:#64748b;width:60px;flex-shrink:0;">Teknisi</span>
                <span style="font-size:12px;font-weight:600;color:#1e293b;" id="cm_teknisi"></span>
            </div>
            <div id="cm_catatan_wrap" style="display:none;">
                <p style="margin:0 0 8px;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;">Catatan</p>
                <div style="background:#fdf4ff;border-radius:10px;border-left:3px solid #7c3aed;border:0.5px solid #e9d5ff;padding:12px 14px;">
                    <p style="margin:0;font-size:13px;color:#6b21a8;line-height:1.7;white-space:pre-line;" id="cm_catatan_text"></p>
                </div>
            </div>
            <div id="cm_file_wrap" style="display:none;">
                <p style="margin:0 0 8px;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;">Lampiran</p>
                <a id="cm_file_link" href="#" target="_blank"
                   style="display:flex;align-items:center;gap:12px;background:#f5f3ff;border:0.5px solid #c4b5fd;border-radius:10px;padding:12px 14px;text-decoration:none;">
                    <div style="width:36px;height:36px;border-radius:8px;background:#7c3aed;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="16" height="16" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <p style="margin:0;font-size:12px;font-weight:600;color:#6d28d9;">Unduh / Lihat File</p>
                        <p style="margin:3px 0 0;font-size:11px;color:#64748b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" id="cm_file_name"></p>
                    </div>
                </a>
            </div>
        </div>
        <div style="padding:14px 22px 20px;flex-shrink:0;border-top:0.5px solid #f1f5f9;">
            <button onclick="closeCatatanModal()"
                    style="width:100%;padding:10px;border-radius:10px;border:0.5px solid #e2e8f0;background:transparent;font-size:13px;color:#64748b;cursor:pointer;font-family:inherit;">
                Tutup
            </button>
        </div>
    </div>
</div>


<!-- ============================================================
     MODAL KONFIRMASI BATALKAN
============================================================ -->
<div id="batalModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50 px-4">
    <div class="w-full max-w-sm rounded-2xl bg-white border border-red-200 p-6 shadow-[0_25px_60px_rgba(0,0,0,0.25)]">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-800">Batalkan Tiket?</h3>
                <p class="text-xs text-slate-500 mt-0.5">Tindakan ini tidak dapat diurungkan</p>
            </div>
        </div>
        <p class="text-sm text-slate-600 mb-5">
            Yakin ingin membatalkan tiket <strong id="batalKode" class="text-slate-800 font-mono"></strong>?
        </p>
        <div class="flex justify-end gap-3">
            <button onclick="closeBatalModal()"
                    class="px-4 py-2 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-100 transition text-sm font-medium">
                Tidak
            </button>
            <a id="batalConfirmBtn" href="#"
               class="px-5 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm shadow transition">
                Ya, Batalkan
            </a>
        </div>
    </div>
</div>


<script>
(function(){
    'use strict';

    /* ============ UPLOAD DOKUMEN RAPAT ============ */
    const UDM_UPLOAD_URL = '<?= base_url("user_ticket/upload_dokumen/") ?>';

    window.openUploadDokumenRapat = function(ticketId, kode) {
        document.getElementById('udm_kode').textContent     = kode;
        document.getElementById('uploadDokumenForm').action = UDM_UPLOAD_URL + ticketId;
        document.getElementById('udm_label').textContent    = 'Pilih file PDF';
        document.getElementById('udm_submit').disabled      = true;
        document.getElementById('udm_jenis').value          = '';
        var err = document.getElementById('udm_error');
        err.textContent = ''; err.classList.add('hidden');
        document.getElementById('udm_file').value = '';
        var m = document.getElementById('uploadDokumenModal');
        m.classList.remove('hidden'); m.classList.add('flex');
    };

    // Alias agar kode lama yang masih pakai nama lama tidak error
    window.openUploadDokumen = window.openUploadDokumenRapat;
    window.openGantiDokumen  = window.openUploadDokumenRapat;

    window.closeUploadDokumen = function() {
        var m = document.getElementById('uploadDokumenModal');
        m.classList.add('hidden'); m.classList.remove('flex');
    };

    window.udmFileChange = function(input) {
        var err = document.getElementById('udm_error');
        var btn = document.getElementById('udm_submit');
        err.textContent = ''; err.classList.add('hidden');

        if (!input.files || !input.files[0]) {
            btn.disabled = true; return;
        }

        var file = input.files[0];

        if (file.type !== 'application/pdf' && !file.name.toLowerCase().endsWith('.pdf')) {
            err.textContent = 'Hanya file PDF yang diperbolehkan.';
            err.classList.remove('hidden');
            btn.disabled = true; return;
        }

        if (file.size > 2 * 1024 * 1024) {
            err.textContent = 'Ukuran melebihi 2 MB (' + (file.size / 1048576).toFixed(1) + ' MB).';
            err.classList.remove('hidden');
            btn.disabled = true; return;
        }

        document.getElementById('udm_label').textContent = '📄 ' + file.name;

        // Submit hanya aktif kalau jenis JUGA sudah dipilih
        var jenis = document.getElementById('udm_jenis').value;
        btn.disabled = !jenis;
    };

    // Submit: tampilkan loading
    document.getElementById('uploadDokumenForm').addEventListener('submit', function() {
        var btn = document.getElementById('udm_submit');
        btn.disabled  = true;
        btn.innerHTML = '<span style="display:inline-block;animation:spin 1s linear infinite">⏳</span> Mengunggah...';
    });

    /* ============ RATING ============ */
    window.openRatingModal = function(ticketId) {
        var form = document.getElementById('ratingForm');
        if (form) form.action = '<?= base_url("user_ticket/rating/") ?>' + ticketId;
        resetStars();
        var m = document.getElementById('ratingModal');
        m.classList.remove('hidden'); m.classList.add('flex');
    };

    window.closeRatingModal = function() {
        var m = document.getElementById('ratingModal');
        m.classList.add('hidden'); m.classList.remove('flex');
        var btn = document.getElementById('submitRatingBtn');
        if (btn) { btn.disabled = false; btn.textContent = 'Kirim Rating'; }
    };

    window.setRating = function(val) {
        document.getElementById('ratingValue').value = val;
        for (var i = 1; i <= 5; i++) {
            var s = document.getElementById('star' + i);
            if (s) {
                s.classList.remove('text-slate-300', 'text-yellow-400');
                s.classList.add(i <= val ? 'text-yellow-400' : 'text-slate-300');
            }
        }
    };

    function resetStars() {
        document.getElementById('ratingValue').value = '';
        for (var i = 1; i <= 5; i++) {
            var s = document.getElementById('star' + i);
            if (s) s.className = 'cursor-pointer text-slate-300 hover:text-yellow-400 transition select-none';
        }
    }

    /* ============ LAYANAN ZOOM ============ */
    window.openLayananModal = function(data) {
        document.getElementById('lm_subkategori').textContent = data.sub_kategori || 'Detail Layanan';
        document.getElementById('lm_kode').textContent        = data.kode_tiket   || '';
        document.getElementById('lm_link').href               = data.layanan_link || '#';
        document.getElementById('lm_link_text').textContent   = data.layanan_link || '';

        var adaTanggal = !!(data.tanggal && data.tanggal.trim());
        var adaJam     = !!(data.jam     && data.jam.trim());
        var adaDurasi  = !!(data.durasi  && data.durasi.trim());

        _show('lm_jadwal_wrap',  adaTanggal || adaJam || adaDurasi, 'block');
        _show('lm_tanggal_row', adaTanggal, 'flex');
        _show('lm_jam_row',     adaJam,     'flex');
        _show('lm_durasi_row',  adaDurasi,  'flex');
        if (adaTanggal) document.getElementById('lm_tanggal').textContent = data.tanggal;
        if (adaJam)     document.getElementById('lm_jam').textContent     = data.jam + ' WIB';
        if (adaDurasi)  document.getElementById('lm_durasi').textContent  = data.durasi + ' jam';

        var adaCatatan = !!(data.layanan_catatan && data.layanan_catatan.trim());
        _show('lm_catatan_wrap', adaCatatan, 'block');
        if (adaCatatan) document.getElementById('lm_catatan').textContent = data.layanan_catatan;

        var adaUndangan = !!(data.undangan_link && data.undangan_link.trim());
        _show('lm_undangan_wrap', adaUndangan, 'block');
        if (adaUndangan) document.getElementById('lm_undangan_text').textContent = data.undangan_link;

        var m = document.getElementById('layananModal');
        m.classList.remove('hidden'); m.classList.add('flex');
    };

    window.closeLayananModal = function() {
        var m = document.getElementById('layananModal');
        m.classList.add('hidden'); m.classList.remove('flex');
    };

    window.copyUndangan = function() {
        var teks  = document.getElementById('lm_undangan_text').textContent.trim();
        var label = document.getElementById('lm_copy_label');
        if (!teks) return;
        function onOk() {
            if (label) label.textContent = 'Tersalin!';
            setTimeout(function() { if (label) label.textContent = 'Salin'; }, 2200);
        }
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(teks).then(onOk).catch(fallback);
        } else { fallback(); }
        function fallback() {
            var t = document.createElement('textarea');
            t.value = teks; t.style.cssText = 'position:fixed;top:0;left:0;opacity:0;';
            document.body.appendChild(t); t.focus(); t.select();
            try { document.execCommand('copy'); onOk(); } catch(e) {}
            document.body.removeChild(t);
        }
    };

    /* ============ CATATAN TEKNISI ============ */
    window.openCatatanModal = function(data) {
        document.getElementById('cm_kode').textContent    = data.kode_tiket   || '';
        document.getElementById('cm_teknisi').textContent = data.nama_teknisi || '-';

        var adaCatatan = !!(data.layanan_catatan && data.layanan_catatan.trim());
        var adaFile    = !!(data.layanan_file    && data.layanan_file.trim());

        var cw = document.getElementById('cm_catatan_wrap');
        if (cw) cw.style.display = adaCatatan ? 'block' : 'none';
        if (adaCatatan) document.getElementById('cm_catatan_text').textContent = data.layanan_catatan;

        var fw = document.getElementById('cm_file_wrap');
        if (fw) fw.style.display = adaFile ? 'block' : 'none';
        if (adaFile) {
            document.getElementById('cm_file_link').href        = '<?= base_url("uploads/layanan/") ?>' + data.layanan_file;
            document.getElementById('cm_file_name').textContent = data.layanan_file;
        }

        var m = document.getElementById('catatanModal');
        m.classList.remove('hidden'); m.classList.add('flex');
    };

    window.closeCatatanModal = function() {
        var m = document.getElementById('catatanModal');
        m.classList.add('hidden'); m.classList.remove('flex');
    };

    /* ============ BATALKAN ============ */
    window.konfirmasiBatal = function(ticketId, kode) {
        document.getElementById('batalKode').textContent = kode;
        document.getElementById('batalConfirmBtn').href  = '<?= base_url("user_ticket/batalkan/") ?>' + ticketId;
        var m = document.getElementById('batalModal');
        m.classList.remove('hidden'); m.classList.add('flex');
    };

    window.closeBatalModal = function() {
        var m = document.getElementById('batalModal');
        m.classList.add('hidden'); m.classList.remove('flex');
    };

    /* ============ HELPER ============ */
    function _show(id, visible, d) {
        var el = document.getElementById(id);
        if (el) el.style.display = visible ? (d || 'block') : 'none';
    }

    /* ============ DOMContentLoaded ============ */
    document.addEventListener('DOMContentLoaded', function() {

        // Enable/disable submit saat jenis berubah
        var jenisEl = document.getElementById('udm_jenis');
        if (jenisEl) {
            jenisEl.addEventListener('change', function() {
                var fileEl = document.getElementById('udm_file');
                var btn    = document.getElementById('udm_submit');
                btn.disabled = !(this.value && fileEl.files && fileEl.files[0]);
            });
        }

        // Close on backdrop click
        ['ratingModal','layananModal','catatanModal','batalModal','uploadDokumenModal'].forEach(function(id) {
            var m = document.getElementById(id);
            if (!m) return;
            m.addEventListener('click', function(e) {
                if (e.target !== this) return;
                if (id === 'ratingModal')             window.closeRatingModal();
                else if (id === 'layananModal')        window.closeLayananModal();
                else if (id === 'catatanModal')        window.closeCatatanModal();
                else if (id === 'batalModal')          window.closeBatalModal();
                else if (id === 'uploadDokumenModal')  window.closeUploadDokumen();
            });
        });

        // Rating form loading state
        var ratingForm = document.getElementById('ratingForm');
        if (ratingForm) {
            ratingForm.addEventListener('submit', function() {
                var btn = document.getElementById('submitRatingBtn');
                if (btn) { btn.disabled = true; btn.innerHTML = '⏳ Mengirim...'; }
            });
        }
    });

    /* Animasi spin */
    var style = document.createElement('style');
    style.textContent = '@keyframes spin { to { transform: rotate(360deg); } }';
    document.head.appendChild(style);
})();
</script> 