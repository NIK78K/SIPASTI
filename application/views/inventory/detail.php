<!-- ================= HEADER ================= -->
<div class="flex items-center justify-between mb-3">
    <h2 class="text-lg font-semibold dtl-title">Detail Perangkat</h2>

    <?php if (!empty($spesifikasi)): ?>
    <span class="dtl-badge px-2.5 py-0.5 text-xs rounded-full">
        <?= $spesifikasi['kategori'] ?>
    </span>
    <?php endif; ?>
</div>

<!-- ================= FOTO PERANGKAT ================= -->
<?php if (!empty($p['foto'])): ?>
<div class="mb-4">
    <div class="relative group">
        <img src="<?= base_url('uploads/perangkat/'.$p['foto']) ?>"
             alt="Foto <?= $p['nama_barang'] ?>"
             class="dtl-foto w-full max-h-80 object-contain rounded-lg cursor-pointer transition"
             onclick="openFotoModal(this.src)">
        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition rounded-lg flex items-center justify-center">
            <span class="text-white text-sm font-medium opacity-0 group-hover:opacity-100 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                </svg>
                Klik untuk memperbesar
            </span>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- ================= DETAIL UTAMA ================= -->
<div class="dtl-card border rounded-lg p-4 text-sm">
    <div class="grid grid-cols-2 gap-x-6 gap-y-3">

        <?php
        function field($label, $value, $mono = false) {
            if ($value === '' || $value === null) return;
            ?>
            <div>
                <div class="dtl-field-label text-xs"><?= $label ?></div>
                <div class="dtl-field-value <?= $mono ? 'font-mono' : '' ?>"><?= $value ?></div>
            </div>
        <?php } ?>

        <?php
        field('Tanggal Perolehan', $p['tanggal_perolehan']);
        field('Nilai Perolehan', 'Rp '.number_format($p['nilai_perolehan'],0,',','.'));
        field('Lokasi', $p['lokasi']);
        field('Serial Number', $p['serial_number'], true);
        field('MAC Address', $p['mac_address'], true);
        field('Pengguna', $p['pengguna']);
        field('Ruangan', $p['ruangan']);
        field('Transaksi SAKTI', $p['transaksi_sakti']);
        ?>

        <?php if (!empty($p['keterangan'])): ?>
        <div class="col-span-2 pt-2 dtl-keterangan-wrap border-t">
            <div class="dtl-field-label text-xs mb-1">Keterangan</div>
            <div class="dtl-field-value"><?= $p['keterangan'] ?></div>
        </div>
        <?php endif; ?>

    </div>
</div>

<!-- ================= SPESIFIKASI ================= -->
<?php if (!empty($spesifikasi)): ?>
<div class="mt-4">
    <div class="dtl-spek-title text-sm font-semibold mb-2">Spesifikasi Perangkat</div>
    <div class="flex flex-wrap gap-2">

        <?php if (!empty($spesifikasi['processor'])): ?>
        <span class="dtl-chip px-3 py-1 text-xs rounded-full">CPU: <?= $spesifikasi['processor'] ?></span>
        <?php endif; ?>

        <?php if (!empty($spesifikasi['ram_gb'])): ?>
        <span class="<?= $spesifikasi['ram_gb'] < 4 ? 'dtl-chip-warn' : 'dtl-chip' ?> px-3 py-1 text-xs rounded-full">
            RAM: <?= $spesifikasi['ram_gb'] ?> GB
        </span>
        <?php endif; ?>

        <?php if (!empty($spesifikasi['storage_gb'])): ?>
        <span class="dtl-chip px-3 py-1 text-xs rounded-full">
            Storage: <?= $spesifikasi['storage_gb'] ?> GB <?= $spesifikasi['storage_type'] ?>
        </span>
        <?php endif; ?>

        <?php if (!empty($spesifikasi['gpu'])): ?>
        <span class="dtl-chip px-3 py-1 text-xs rounded-full">GPU: <?= $spesifikasi['gpu'] ?></span>
        <?php endif; ?>

        <?php if (!empty($spesifikasi['os'])): ?>
        <span class="dtl-chip px-3 py-1 text-xs rounded-full">OS: <?= $spesifikasi['os'] ?></span>
        <?php endif; ?>

    </div>
</div>
<?php endif; ?>

<button type="button"
        data-row-id="detail-row-<?= $p['id'] ?>"
        class="dtl-back inline-flex items-center gap-2 mt-4 text-sm closeDetail">
    ← Kembali
</button>

<!-- MODAL FOTO -->
<div id="fotoModal" class="hidden fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-4" onclick="closeFotoModal()">
    <div class="relative max-w-6xl max-h-full">
        <button onclick="closeFotoModal()" class="absolute -top-12 right-0 text-white hover:text-gray-300 flex items-center gap-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Tutup
        </button>
        <img id="fotoModalImg" src="" alt="Foto Perangkat" class="max-w-full max-h-screen rounded-lg shadow-2xl">
    </div>
</div>

<script>
function openFotoModal(src) {
    document.getElementById('fotoModalImg').src = src;
    document.getElementById('fotoModal').classList.remove('hidden');
}
function closeFotoModal() {
    document.getElementById('fotoModal').classList.add('hidden');
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeFotoModal();
});
</script>

<style>
/* ── dark default ── */
.dtl-title        { color: #e2e8f0; }
.dtl-badge        { background: #374151; color: #d1d5db; }
.dtl-foto         { background: #111827; border: 2px solid #374151; }
.dtl-foto:hover   { border-color: #06b6d4; }
.dtl-card         { background: #0e141b; border-color: #374151; }
.dtl-field-label  { color: #9ca3af; }
.dtl-field-value  { color: #e2e8f0; }
.dtl-keterangan-wrap { border-color: #374151; }
.dtl-spek-title   { color: #d1d5db; }
.dtl-chip         { background: #1f2937; border: 1px solid #374151; color: #d1d5db; }
.dtl-chip-warn    { background: rgba(127,29,29,0.4); border: 1px solid #b91c1c; color: #fca5a5; }
.dtl-back         { color: #9ca3af; }
.dtl-back:hover   { color: #f1f5f9; }

/* ── light overrides ── */
html.light .dtl-title        { color: #0f172a !important; }
html.light .dtl-badge        { background: #f1f5f9 !important; color: #475569 !important; }
html.light .dtl-foto         { background: #f8fafc !important; border-color: #cbd5e1 !important; }
html.light .dtl-foto:hover   { border-color: #0891b2 !important; }
html.light .dtl-card         { background: #f8fafc !important; border-color: #e2e8f0 !important; }
html.light .dtl-field-label  { color: #64748b !important; }
html.light .dtl-field-value  { color: #0f172a !important; }
html.light .dtl-keterangan-wrap { border-color: #e2e8f0 !important; }
html.light .dtl-spek-title   { color: #1e293b !important; }
html.light .dtl-chip         { background: #f1f5f9 !important; border-color: #cbd5e1 !important; color: #334155 !important; }
html.light .dtl-chip-warn    { background: rgba(254,226,226,0.8) !important; border-color: #f87171 !important; color: #b91c1c !important; }
html.light .dtl-back         { color: #64748b !important; }
html.light .dtl-back:hover   { color: #0f172a !important; }
</style>