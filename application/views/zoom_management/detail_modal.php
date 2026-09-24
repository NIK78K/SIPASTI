<?php
$t  = $ticket;
$dp = $t['detail_parsed'];
$is_multiday  = $t['is_multiday']   ?? false;
$jumlah_hari  = $t['jumlah_hari']   ?? 1;
$grup_tikets  = $t['grup_tikets']   ?? [];
$is_breakout  = count($grup_tikets) > 1;

$status_color = match($t['status']) {
    'Menunggu'    => 'bg-gray-500/20 text-gray-300 border-gray-500/30',
    'On Progress' => 'bg-yellow-500/20 text-yellow-300 border-yellow-500/30',
    'Selesai'     => 'bg-green-500/20 text-green-300 border-green-500/30',
    default       => 'bg-blue-500/20 text-blue-300 border-blue-500/30',
};
?>

<script>
document.getElementById('zoom-modal-kode').textContent = '<?= $t['kode_tiket'] ?>';
</script>

<!-- Nama kegiatan sebagai judul utama -->
<?php if (!empty($dp['detail_permintaan'])): ?>
<div class="text-center mb-3">
    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl
                bg-gradient-to-r from-cyan-500/10 to-blue-500/10
                border border-cyan-500/20">
        <svg class="w-4 h-4 text-cyan-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        <span class="text-cyan-300 font-semibold text-sm"><?= htmlspecialchars($dp['detail_permintaan']) ?></span>
    </div>
</div>
<?php endif; ?>

<!-- Badge multi-hari (jika ada) -->
<?php if ($is_multiday): ?>
<div class="flex justify-center mb-2">
    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold
                 bg-violet-500/15 text-violet-300 border border-violet-500/30">
        📅 Multi-hari ·
        <?= date('d M Y', strtotime($t['tanggal'])) ?>
        s.d.
        <?= date('d M Y', strtotime($t['tanggal_selesai'])) ?>
        · <strong><?= $jumlah_hari ?> hari</strong>
    </span>
</div>
<?php endif; ?>

<!-- Status Badge -->
<div class="flex justify-center mb-4">
    <span class="px-5 py-2 rounded-full text-sm font-semibold border <?= $status_color ?>">
        <?= $t['status'] === 'Menunggu' ? '⏳' : ($t['status'] === 'On Progress' ? '⚡' : '✅') ?>
        <?= $t['status'] ?>
    </span>
</div>

<!-- Banner breakout -->
<?php if ($is_breakout): ?>
<div class="bg-pink-500/10 border border-pink-500/30 rounded-xl p-3 mb-4 flex items-center gap-3">
    <div class="w-8 h-8 bg-pink-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
        <svg class="w-4 h-4 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h8M4 18h8"/>
        </svg>
    </div>
    <div class="flex-1 min-w-0">
        <div class="text-xs font-bold text-pink-400 mb-0.5">
            Breakout Room <?= $t['zoom_breakout_slot'] ?? '-' ?>
            <span class="font-normal text-pink-300/70">· dari <?= count($grup_tikets) ?> room dalam sesi ini</span>
        </div>
        <div class="flex flex-wrap gap-1.5">
            <?php foreach ($grup_tikets as $gt):
                $is_current = $gt['id'] == $t['id'];
            ?>
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-semibold
                         <?= $is_current
                            ? 'bg-pink-500/30 text-pink-300 ring-1 ring-pink-500/50'
                            : 'bg-slate-700/50 text-slate-400 cursor-pointer hover:bg-slate-600/50' ?>"
                  <?= !$is_current ? "onclick=\"openZoomDetail({$gt['id']})\"" : '' ?>>
                BR-<?= $gt['zoom_breakout_slot'] ?>
                <?= $is_current ? '← ini' : '' ?>
            </span>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Info Pelapor + Jadwal -->
<div class="grid grid-cols-2 gap-4 mb-4">

    <div class="bg-gradient-to-br from-blue-500/5 to-transparent border border-blue-500/20 rounded-2xl p-4">
        <div class="text-xs font-bold text-blue-400 mb-3 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Info Pelapor
        </div>
        <div class="space-y-2 text-sm">
            <div>
                <div class="text-xs header-subtitle">Nama</div>
                <div class="header-title font-semibold"><?= htmlspecialchars($t['pelapor']) ?></div>
            </div>
            <div>
                <div class="text-xs header-subtitle">Email</div>
                <div class="header-title text-xs"><?= htmlspecialchars($t['email'] ?? '-') ?></div>
            </div>
            <div>
                <div class="text-xs header-subtitle">Teknisi</div>
                <div class="header-title font-medium"><?= htmlspecialchars($t['nama_teknisi'] ?? 'Belum assign') ?></div>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-indigo-500/5 to-transparent border border-indigo-500/20 rounded-2xl p-4">
        <div class="text-xs font-bold text-indigo-400 mb-3 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Jadwal
        </div>
        <div class="space-y-2 text-sm">

            <?php if ($is_multiday): ?>
            <!-- MULTI-DAY: tampilkan periode -->
            <div>
                <div class="text-xs header-subtitle">Tanggal Mulai</div>
                <div class="header-title font-semibold"><?= date('d F Y', strtotime($t['tanggal'])) ?></div>
            </div>
            <div>
                <div class="text-xs header-subtitle">Tanggal Selesai</div>
                <div class="header-title font-semibold text-violet-400"><?= date('d F Y', strtotime($t['tanggal_selesai'])) ?></div>
            </div>
            <div>
                <div class="text-xs header-subtitle">Total Hari</div>
                <div class="header-title font-bold text-violet-300"><?= $jumlah_hari ?> hari</div>
            </div>
            <?php else: ?>
            <!-- SINGLE DAY -->
            <div>
                <div class="text-xs header-subtitle">Tanggal</div>
                <div class="header-title font-semibold"><?= $t['tanggal'] ? date('d F Y', strtotime($t['tanggal'])) : '-' ?></div>
            </div>
            <?php endif; ?>

            <div>
                <div class="text-xs header-subtitle">Jam Mulai<?= $is_multiday ? ' (per hari)' : '' ?></div>
                <div class="header-title font-semibold text-blue-400"><?= $t['jam'] ? $t['jam'] . ' WIB' : '-' ?></div>
            </div>
            <div>
                <div class="text-xs header-subtitle">Durasi<?= $is_multiday ? ' (per hari)' : '' ?></div>
                <div class="header-title font-semibold"><?= $t['durasi'] ? $t['durasi'] . ' jam' : '-' ?></div>
            </div>

        </div>
    </div>

</div>

<!-- Detail Permintaan -->
<div class="bg-gradient-to-br from-purple-500/5 to-transparent border border-purple-500/20 rounded-2xl p-4 mb-4">
    <div class="text-xs font-bold text-purple-400 mb-3">📋 Detail Permintaan</div>
    <div class="grid grid-cols-2 gap-3 text-sm">
        <div>
            <div class="text-xs header-subtitle">Jumlah Peserta</div>
            <div class="header-title font-bold text-lg text-purple-400">
                <?= $dp['jumlah_peserta'] ? $dp['jumlah_peserta'] . ' orang' : '-' ?>
            </div>
        </div>
        <div>
            <div class="text-xs header-subtitle">Jumlah Breakout Room</div>
            <div class="header-title font-bold text-lg text-pink-400">
                <?= $dp['jumlah_breakout'] ?: '-' ?>
            </div>
        </div>
        <div>
            <div class="text-xs header-subtitle">Penanggung Jawab</div>
            <div class="header-title font-semibold"><?= htmlspecialchars($dp['penanggung_jawab'] ?: '-') ?></div>
        </div>
        <div>
            <div class="text-xs header-subtitle">Host/Co-Host</div>
            <div class="header-title font-semibold"><?= htmlspecialchars($dp['host_cohost'] ?: '-') ?></div>
        </div>
    </div>

    <?php if (!empty($dp['lokasi_unit'])): ?>
    <div class="mt-3 pt-3 border-t border-purple-500/20">
        <div class="text-xs header-subtitle mb-1">Lokasi / Unit Kerja</div>
        <div class="header-title text-sm font-medium"><?= htmlspecialchars($dp['lokasi_unit']) ?></div>
    </div>
    <?php endif; ?>

    <?php if (!empty($dp['permintaan_lain'])): ?>
    <div class="mt-3 pt-3 border-t border-purple-500/20">
        <div class="text-xs header-subtitle mb-1">Permintaan Lainnya</div>
        <div class="header-title text-sm"><?= nl2br(htmlspecialchars($dp['permintaan_lain'])) ?></div>
    </div>
    <?php endif; ?>
</div>

<!-- Link Zoom -->
<?php if (!empty($t['layanan_link'])): ?>
<div class="bg-gradient-to-br from-emerald-500/5 to-transparent border border-emerald-500/20 rounded-2xl p-4 mb-4">
    <div class="text-xs font-bold text-emerald-400 mb-2">🔗 Link Zoom</div>
    <a href="<?= htmlspecialchars($t['layanan_link']) ?>" target="_blank"
       class="text-emerald-400 text-sm underline break-all hover:text-emerald-300 transition-colors">
        <?= htmlspecialchars($t['layanan_link']) ?>
    </a>
</div>
<?php endif; ?>

<!-- Catatan Teknisi -->
<?php if (!empty($t['layanan_catatan'])): ?>
<div class="bg-gradient-to-br from-amber-500/5 to-transparent border border-amber-500/20 rounded-2xl p-4 mb-4">
    <div class="text-xs font-bold text-amber-400 mb-2">💬 Catatan Teknisi</div>
    <div class="header-title text-sm leading-relaxed"><?= nl2br(htmlspecialchars($t['layanan_catatan'])) ?></div>
</div>
<?php endif; ?>

<!-- Timestamps -->
<div class="grid grid-cols-2 gap-3 text-xs header-subtitle">
    <div class="bg-slate-800/30 rounded-xl p-3">
        <div class="mb-0.5">📥 Dibuat</div>
        <div class="header-title font-medium"><?= date('d F Y H:i', strtotime($t['created_at'])) ?></div>
    </div>
    <?php if (!empty($t['selesai_at'])): ?>
    <div class="bg-slate-800/30 rounded-xl p-3">
        <div class="mb-0.5">✅ Selesai</div>
        <div class="header-title font-medium"><?= date('d F Y H:i', strtotime($t['selesai_at'])) ?></div>
    </div>
    <?php endif; ?>
</div>