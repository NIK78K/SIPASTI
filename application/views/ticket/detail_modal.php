<?php
$ticket = $ticket ?? [];

$statusLabel = [
    'Menunggu'    => 'Menunggu',
    'On Progress' => 'Diproses',
    'Selesai'     => 'Selesai',
    'Dibatalkan'  => 'Dibatalkan'
];

$statusClass = [
    'Menunggu'    => 'bg-yellow-500/15 text-yellow-400 ring-1 ring-yellow-500/30',
    'On Progress' => 'bg-cyan-500/15 text-cyan-400 ring-1 ring-cyan-500/30',
    'Selesai'     => 'bg-green-500/15 text-green-400 ring-1 ring-green-500/30',
    'Dibatalkan'  => 'bg-red-500/15 text-red-400 ring-1 ring-red-500/30'
];

$status = $ticket['status'] ?? 'Menunggu';
?>

<!-- JUDUL -->
<h2 class="text-lg font-semibold dtck-text mb-1">
    Detail Tiket
</h2>
<p class="text-sm font-mono dtck-muted mb-5">
    <?= $ticket['kode_tiket'] ?? '-' ?>
</p>

<!-- GRID INFO -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">

    <div>
        <div class="text-xs dtck-label mb-1">Pelapor</div>
        <div class="dtck-text"><?= $ticket['pelapor'] ?? '-' ?></div>
    </div>

    <div>
        <div class="text-xs dtck-label mb-1">Teknisi</div>
        <div class="dtck-text"><?= $ticket['nama_teknisi'] ?? '-' ?></div>
    </div>

    <div>
        <div class="text-xs dtck-label mb-1">Perangkat</div>
        <div class="dtck-text"><?= $ticket['nama_perangkat'] ?? '-' ?></div>
    </div>

    <div>
        <div class="text-xs dtck-label mb-1">Status</div>
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                     <?= $statusClass[$status] ?? 'bg-gray-500/20 text-gray-400' ?>">
            <?= $statusLabel[$status] ?? $status ?>
        </span>
    </div>

    <div>
        <div class="text-xs dtck-label mb-1">Tanggal Dibuat</div>
        <div class="dtck-secondary">
            <?= !empty($ticket['created_at'])
                ? date('d M Y • H:i', strtotime($ticket['created_at']))
                : '-' ?>
        </div>
    </div>

</div>

<hr class="my-5 dtck-divider">

<!-- DESKRIPSI -->
<div class="mb-5">
    <div class="text-xs dtck-label mb-2">Deskripsi / Kebutuhan</div>
    <div class="dtck-text text-sm leading-relaxed">
        <?= nl2br($ticket['jenis_kerusakan'] ?? '-') ?>
    </div>
</div>

<!-- FOTO -->
<div>
    <div class="text-xs dtck-label mb-2">Foto Pendukung</div>
    <?php if (!empty($ticket['foto'])): ?>
        <div class="dtck-photo-wrap rounded-lg border p-3">
            <img src="<?= base_url('uploads/tiket/'.$ticket['foto']) ?>"
                 class="rounded-md max-h-72 w-full object-contain"
                 alt="Foto Tiket">
        </div>
    <?php else: ?>
        <div class="text-sm italic dtck-muted">Tidak ada foto yang diunggah</div>
    <?php endif ?>
</div>

<!-- ================= STYLES ================= -->
<style>
/* ── DARK default ── */
.dtck-text      { color: #e2e8f0; }
.dtck-secondary { color: #cbd5e1; }
.dtck-label     { color: #64748b; }
.dtck-muted     { color: #475569; }
.dtck-divider   { border-color: rgba(255,255,255,0.08); }
.dtck-photo-wrap{ background: rgba(0,0,0,0.3); border-color: rgba(255,255,255,0.08); }

/* ── LIGHT overrides ── */
html.light .dtck-text      { color: #0f172a !important; }
html.light .dtck-secondary { color: #334155 !important; }
html.light .dtck-label     { color: #64748b !important; }
html.light .dtck-muted     { color: #94a3b8 !important; }
html.light .dtck-divider   { border-color: #e2e8f0 !important; }
html.light .dtck-photo-wrap{ background: #f8fafc !important; border-color: #e2e8f0 !important; }
</style>