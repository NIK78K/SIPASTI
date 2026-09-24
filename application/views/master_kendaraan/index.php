<!-- application/views/master_kendaraan/index.php -->

<!-- Flash Message -->
<?php if ($this->session->flashdata('success')): ?>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        Swal.fire({ icon: 'success', title: 'Berhasil', text: '<?= $this->session->flashdata('success') ?>', timer: 2500, showConfirmButton: false });
    });
</script>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        Swal.fire({ icon: 'error', title: 'Gagal', text: '<?= $this->session->flashdata('error') ?>' });
    });
</script>
<?php endif; ?>

<!-- ===== THEME VARIABLES ===== -->
<style>
/* --- Dark mode (default) --- */
:root,
html:not(.light) {
    --mk-bg-card:           #1e293b;
    --mk-bg-input:          #0f172a;
    --mk-bg-thead:          rgba(55,65,81,0.60);
    --mk-bg-row-hover:      rgba(55,65,81,0.30);
    --mk-border:            #374151;
    --mk-border-light:      #374151;
    --mk-text-primary:      #ffffff;
    --mk-text-secondary:    #d1d5db;
    --mk-text-muted:        #9ca3af;
    --mk-text-label:        #9ca3af;
    --mk-placeholder:       #6b7280;
    --mk-th-color:          #9ca3af;
    --mk-divider:           #374151;
    /* Stat card icon bg */
    --mk-icon-blue-bg:      rgba(37,99,235,0.40);
    --mk-icon-green-bg:     rgba(22,163,74,0.40);
    --mk-icon-mobil-bg:     rgba(37,99,235,0.20);
    --mk-icon-motor-bg:     rgba(234,179,8,0.20);
    /* NIP / polisi */
    --mk-nopol-color:       #60a5fa;
    /* Badge jenis */
    --mk-badge-mobil-bg:    rgba(37,99,235,0.30);
    --mk-badge-mobil-text:  #60a5fa;
    --mk-badge-motor-bg:    rgba(234,88,12,0.30);
    --mk-badge-motor-text:  #fb923c;
    /* Badge status */
    --mk-st-tersedia-bg:    rgba(22,163,74,0.30);
    --mk-st-tersedia-text:  #4ade80;
    --mk-st-tidak-bg:       rgba(220,38,38,0.30);
    --mk-st-tidak-text:     #f87171;
    --mk-st-perbaikan-bg:   rgba(202,138,4,0.30);
    --mk-st-perbaikan-text: #fbbf24;
    /* Fasilitas pill */
    --mk-fas-bg:            #374151;
    --mk-fas-text:          #9ca3af;
    --mk-fas-more-bg:       #4b5563;
    --mk-fas-more-text:     #6b7280;
    /* Tombol aksi */
    --mk-btn-edit-bg:       rgba(234,179,8,0.20);
    --mk-btn-edit-text:     #fbbf24;
    --mk-btn-edit-hover:    rgba(234,179,8,0.35);
    --mk-btn-del-bg:        rgba(220,38,38,0.20);
    --mk-btn-del-text:      #f87171;
    --mk-btn-del-hover:     rgba(220,38,38,0.35);
    /* Input / modal */
    --mk-input-border:      #4b5563;
    --mk-input-bg:          #374151;
    --mk-input-text:        #ffffff;
    --mk-modal-bg:          #1e293b;
    --mk-modal-border:      #374151;
    --mk-modal-title:       #ffffff;
    --mk-modal-close:       #9ca3af;
    --mk-modal-close-hover: #e5e7eb;
    --mk-btn-batal-border:  #4b5563;
    --mk-btn-batal-text:    #d1d5db;
    --mk-btn-batal-hover:   #374151;
    /* Search */
    --mk-search-border:     #4b5563;
    --mk-search-bg:         #374151;
    --mk-search-text:       #e5e7eb;
    --mk-search-icon:       #9ca3af;
    /* Table header */
    --mk-tbl-header-bg:     #1e293b;
    --mk-tbl-header-border: #374151;
    --mk-tbl-header-text:   #e5e7eb;
}

/* --- Light mode --- */
html.light {
    --mk-bg-card:           #ffffff;
    --mk-bg-input:          #f9fafb;
    --mk-bg-thead:          rgba(249,250,251,1);
    --mk-bg-row-hover:      rgba(249,250,251,1);
    --mk-border:            #e5e7eb;
    --mk-border-light:      #f3f4f6;
    --mk-text-primary:      #111827;
    --mk-text-secondary:    #374151;
    --mk-text-muted:        #6b7280;
    --mk-text-label:        #6b7280;
    --mk-placeholder:       #9ca3af;
    --mk-th-color:          #6b7280;
    --mk-divider:           #f3f4f6;
    /* Stat card icon bg */
    --mk-icon-blue-bg:      #dbeafe;
    --mk-icon-green-bg:     #dcfce7;
    --mk-icon-mobil-bg:     #eff6ff;
    --mk-icon-motor-bg:     #fefce8;
    /* NIP / polisi */
    --mk-nopol-color:       #2563eb;
    /* Badge jenis */
    --mk-badge-mobil-bg:    #dbeafe;
    --mk-badge-mobil-text:  #1d4ed8;
    --mk-badge-motor-bg:    #ffedd5;
    --mk-badge-motor-text:  #c2410c;
    /* Badge status */
    --mk-st-tersedia-bg:    #dcfce7;
    --mk-st-tersedia-text:  #15803d;
    --mk-st-tidak-bg:       #fee2e2;
    --mk-st-tidak-text:     #b91c1c;
    --mk-st-perbaikan-bg:   #fef9c3;
    --mk-st-perbaikan-text: #a16207;
    /* Fasilitas pill */
    --mk-fas-bg:            #f3f4f6;
    --mk-fas-text:          #4b5563;
    --mk-fas-more-bg:       #e5e7eb;
    --mk-fas-more-text:     #6b7280;
    /* Tombol aksi */
    --mk-btn-edit-bg:       #fefce8;
    --mk-btn-edit-text:     #a16207;
    --mk-btn-edit-hover:    #fef08a;
    --mk-btn-del-bg:        #fef2f2;
    --mk-btn-del-text:      #b91c1c;
    --mk-btn-del-hover:     #fecaca;
    /* Input / modal */
    --mk-input-border:      #e5e7eb;
    --mk-input-bg:          #f9fafb;
    --mk-input-text:        #111827;
    --mk-modal-bg:          #ffffff;
    --mk-modal-border:      #f3f4f6;
    --mk-modal-title:       #111827;
    --mk-modal-close:       #9ca3af;
    --mk-modal-close-hover: #374151;
    --mk-btn-batal-border:  #e5e7eb;
    --mk-btn-batal-text:    #374151;
    --mk-btn-batal-hover:   #f9fafb;
    /* Search */
    --mk-search-border:     #e5e7eb;
    --mk-search-bg:         #f9fafb;
    --mk-search-text:       #374151;
    --mk-search-icon:       #9ca3af;
    /* Table header */
    --mk-tbl-header-bg:     #ffffff;
    --mk-tbl-header-border: #f3f4f6;
    --mk-tbl-header-text:   #374151;
}

/* ── Apply ke elemen ── */
.mk-card            { background-color: var(--mk-bg-card); border-color: var(--mk-border); }
.mk-stat-num        { color: var(--mk-text-primary); }
.mk-stat-sub        { color: var(--mk-text-muted); }
.mk-icon-blue       { background-color: var(--mk-icon-blue-bg); }
.mk-icon-green      { background-color: var(--mk-icon-green-bg); }
.mk-icon-mobil      { background-color: var(--mk-icon-mobil-bg); }
.mk-icon-motor      { background-color: var(--mk-icon-motor-bg); }

.mk-tbl-header      { background-color: var(--mk-tbl-header-bg); border-color: var(--mk-tbl-header-border); }
.mk-tbl-header-text { color: var(--mk-tbl-header-text); }
.mk-search          { background-color: var(--mk-search-bg); border-color: var(--mk-search-border); color: var(--mk-search-text); }
.mk-search::placeholder { color: var(--mk-placeholder); }
.mk-search-icon     { color: var(--mk-search-icon); stroke: var(--mk-search-icon); }
.mk-thead           { background-color: var(--mk-bg-thead); }
.mk-th              { color: var(--mk-th-color); }
.mk-tr-divider      { border-color: var(--mk-divider); }
.mk-tr:hover        { background-color: var(--mk-bg-row-hover); }
.mk-td-muted        { color: var(--mk-text-muted); }
.mk-td-primary      { color: var(--mk-text-primary); }
.mk-td-secondary    { color: var(--mk-text-secondary); }
.mk-nopol           { color: var(--mk-nopol-color); }

.mk-badge-mobil     { background-color: var(--mk-badge-mobil-bg); color: var(--mk-badge-mobil-text); }
.mk-badge-motor     { background-color: var(--mk-badge-motor-bg); color: var(--mk-badge-motor-text); }

.mk-st-tersedia     { background-color: var(--mk-st-tersedia-bg); color: var(--mk-st-tersedia-text); }
.mk-st-tidak        { background-color: var(--mk-st-tidak-bg);    color: var(--mk-st-tidak-text); }
.mk-st-perbaikan    { background-color: var(--mk-st-perbaikan-bg); color: var(--mk-st-perbaikan-text); }

.mk-fas-pill        { background-color: var(--mk-fas-bg);      color: var(--mk-fas-text); }
.mk-fas-more        { background-color: var(--mk-fas-more-bg); color: var(--mk-fas-more-text); }

.mk-btn-edit        { background-color: var(--mk-btn-edit-bg); color: var(--mk-btn-edit-text); }
.mk-btn-edit:hover  { background-color: var(--mk-btn-edit-hover); }
.mk-btn-edit svg    { stroke: var(--mk-btn-edit-text); }
.mk-btn-del         { background-color: var(--mk-btn-del-bg);  color: var(--mk-btn-del-text); }
.mk-btn-del:hover   { background-color: var(--mk-btn-del-hover); }
.mk-btn-del svg     { stroke: var(--mk-btn-del-text); }

/* Input di modal (via JS buildFields) */
.mk-input           { background-color: var(--mk-input-bg) !important; border-color: var(--mk-input-border) !important; color: var(--mk-input-text) !important; }
.mk-input::placeholder { color: var(--mk-placeholder) !important; }
.mk-input option    { background-color: var(--mk-input-bg); color: var(--mk-input-text); }
.mk-label           { color: var(--mk-text-label) !important; }
.mk-modal-wrap      { background-color: var(--mk-modal-bg); border-color: var(--mk-modal-border); }
.mk-modal-header    { border-color: var(--mk-modal-border); }
.mk-modal-title     { color: var(--mk-modal-title); }
.mk-modal-close     { color: var(--mk-modal-close); }
.mk-modal-close:hover { color: var(--mk-modal-close-hover); }
.mk-btn-batal       { border-color: var(--mk-btn-batal-border); color: var(--mk-btn-batal-text); }
.mk-btn-batal:hover { background-color: var(--mk-btn-batal-hover); }

/* SweetAlert2 ikut theme */
.swal2-popup          { background-color: var(--mk-modal-bg)   !important; border: 1.5px solid var(--mk-border) !important; color: var(--mk-text-primary) !important; }
.swal2-title          { color: var(--mk-text-primary)   !important; }
.swal2-html-container { color: var(--mk-text-secondary) !important; }
</style>

<!-- PAGE HEADER -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
    <div>
        <h1 class="text-2xl font-bold mk-td-primary">Master Kendaraan</h1>
        <p class="text-sm mk-stat-sub mt-0.5">Kelola data kendaraan dinas yang dapat dipinjam</p>
    </div>
    <button id="btnTambahKendaraan"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700
                   text-white font-semibold rounded-xl shadow transition-all duration-200 active:scale-95">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Kendaraan
    </button>
</div>

<!-- STAT CARDS -->
<?php
$total      = count($kendaraan);
$tersedia   = count(array_filter($kendaraan, fn($k) => $k['status'] === 'Tersedia'));
$perbaikan  = count(array_filter($kendaraan, fn($k) => $k['status'] === 'Dalam Perbaikan'));
$tidakAda   = count(array_filter($kendaraan, fn($k) => $k['status'] === 'Tidak Tersedia'));
$mobil      = count(array_filter($kendaraan, fn($k) => $k['jenis'] === 'Mobil'));
$motor      = count(array_filter($kendaraan, fn($k) => $k['jenis'] === 'Motor'));
?>
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="mk-card rounded-xl p-4 shadow-sm border flex items-center gap-4">
        <div class="w-11 h-11 rounded-full mk-icon-blue flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 17l-1.5-3H4a2 2 0 01-2-2V7a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2.5L14 17H8z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold mk-stat-num"><?= $total ?></p>
            <p class="text-xs mk-stat-sub">Total Kendaraan</p>
        </div>
    </div>
    <div class="mk-card rounded-xl p-4 shadow-sm border flex items-center gap-4">
        <div class="w-11 h-11 rounded-full mk-icon-green flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold mk-stat-num"><?= $tersedia ?></p>
            <p class="text-xs mk-stat-sub">Tersedia</p>
        </div>
    </div>
    <div class="mk-card rounded-xl p-4 shadow-sm border flex items-center gap-4">
        <div class="w-11 h-11 rounded-full mk-icon-mobil flex items-center justify-center flex-shrink-0">
            <span class="text-lg">🚗</span>
        </div>
        <div>
            <p class="text-2xl font-bold mk-stat-num"><?= $mobil ?></p>
            <p class="text-xs mk-stat-sub">Mobil</p>
        </div>
    </div>
    <div class="mk-card rounded-xl p-4 shadow-sm border flex items-center gap-4">
        <div class="w-11 h-11 rounded-full mk-icon-motor flex items-center justify-center flex-shrink-0">
            <span class="text-lg">🏍️</span>
        </div>
        <div>
            <p class="text-2xl font-bold mk-stat-num"><?= $motor ?></p>
            <p class="text-xs mk-stat-sub">Motor</p>
        </div>
    </div>
</div>

<!-- TABEL -->
<div class="mk-card rounded-2xl shadow-sm border overflow-hidden">
    <div class="mk-tbl-header flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4 border-b">
        <h2 class="font-semibold mk-tbl-header-text">Daftar Kendaraan</h2>
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 mk-search-icon pointer-events-none"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
            </svg>
            <input type="text" id="searchInput" placeholder="Cari kendaraan..."
                   class="mk-search pl-9 pr-4 py-2 text-sm rounded-xl border
                          focus:outline-none focus:ring-2 focus:ring-blue-500 w-56">
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="tabelKendaraan">
            <thead>
                <tr class="mk-thead mk-th text-xs uppercase tracking-wider">
                    <th class="px-4 py-3 text-left w-10">#</th>
                    <th class="px-4 py-3 text-left">Kendaraan</th>
                    <th class="px-4 py-3 text-left">No. Polisi</th>
                    <th class="px-4 py-3 text-left">Jenis</th>
                    <th class="px-4 py-3 text-left">Merek / Tahun</th>
                    <th class="px-4 py-3 text-left">Warna</th>
                    <th class="px-4 py-3 text-left">BBM (L)</th>
                    <th class="px-4 py-3 text-left">Fasilitas</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y mk-tr-divider">
            <?php if (!empty($kendaraan)): ?>
            <?php foreach ($kendaraan as $i => $k): ?>
            <?php
                $fas_list = !empty($k['fasilitas']) ? explode(',', $k['fasilitas']) : [];
                $statusCls = match($k['status']) {
                    'Tersedia'        => 'mk-st-tersedia',
                    'Tidak Tersedia'  => 'mk-st-tidak',
                    'Dalam Perbaikan' => 'mk-st-perbaikan',
                    default           => 'mk-st-tidak',
                };
                $jenisCls  = $k['jenis'] === 'Mobil' ? 'mk-badge-mobil' : 'mk-badge-motor';
                $jensIcon  = $k['jenis'] === 'Mobil' ? '🚗' : '🏍️';
                $warnaHex  = match(strtolower($k['warna'])) {
                    'putih'  => '#f3f4f6',
                    'hitam'  => '#111827',
                    'merah'  => '#ef4444',
                    'biru'   => '#3b82f6',
                    'silver' => '#9ca3af',
                    default  => '#d1d5db',
                };
            ?>
            <tr class="mk-tr transition">
                <td class="px-4 py-3 mk-td-muted"><?= $i + 1 ?></td>
                <td class="px-4 py-3">
                    <div class="font-semibold mk-td-primary"><?= htmlspecialchars($k['nama_kendaraan']) ?></div>
                    <?php if (!empty($k['keterangan'])): ?>
                    <div class="text-xs mk-td-muted mt-0.5 max-w-[200px] truncate"><?= htmlspecialchars($k['keterangan']) ?></div>
                    <?php endif; ?>
                </td>
                <td class="px-4 py-3">
                    <span class="font-mono font-semibold mk-nopol"><?= htmlspecialchars($k['nomor_polisi']) ?></span>
                </td>
                <td class="px-4 py-3">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold <?= $jenisCls ?>">
                        <?= $jensIcon ?> <?= $k['jenis'] ?>
                    </span>
                </td>
                <td class="px-4 py-3 mk-td-secondary">
                    <?= htmlspecialchars($k['merek']) ?> <span class="mk-td-muted">(<?= $k['tahun'] ?>)</span>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-1.5">
                        <span class="w-3.5 h-3.5 rounded-full border border-gray-300 flex-shrink-0"
                              style="background-color:<?= $warnaHex ?>"></span>
                        <span class="mk-td-secondary text-xs"><?= htmlspecialchars($k['warna']) ?></span>
                    </div>
                </td>
                <td class="px-4 py-3 mk-td-secondary"><?= $k['kapasitas_bahan_bakar'] ?> L</td>
                <td class="px-4 py-3">
                    <?php if (!empty($fas_list)): ?>
                    <div class="flex flex-wrap gap-1 max-w-[180px]">
                        <?php foreach (array_slice($fas_list, 0, 3) as $fas): ?>
                        <span class="mk-fas-pill px-1.5 py-0.5 rounded text-[10px]"><?= trim($fas) ?></span>
                        <?php endforeach; ?>
                        <?php if (count($fas_list) > 3): ?>
                        <span class="mk-fas-more px-1.5 py-0.5 rounded text-[10px]">+<?= count($fas_list) - 3 ?></span>
                        <?php endif; ?>
                    </div>
                    <?php else: ?>
                    <span class="mk-td-muted text-xs">-</span>
                    <?php endif; ?>
                </td>
                <td class="px-4 py-3">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold <?= $statusCls ?>">
                        <?= $k['status'] ?>
                    </span>
                </td>
                <td class="px-4 py-3 text-center">
                    <div class="flex items-center justify-center gap-1.5">
                        <button data-kendaraan='<?= htmlspecialchars(json_encode($k), ENT_QUOTES) ?>'
                                class="btnEditKendaraan mk-btn-edit p-1.5 rounded-lg transition" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button data-id="<?= $k['id'] ?>" data-nama="<?= htmlspecialchars($k['nama_kendaraan']) ?>"
                                class="btnHapusKendaraan mk-btn-del p-1.5 rounded-lg transition" title="Hapus">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
                <td colspan="10" class="px-4 py-12 text-center mk-td-muted">
                    <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 17l-1.5-3H4a2 2 0 01-2-2V7a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2.5L14 17H8z"/>
                    </svg>
                    <p class="font-medium">Belum ada data kendaraan</p>
                    <p class="text-sm">Klik tombol "Tambah Kendaraan" untuk menambahkan</p>
                </td>
            </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
(function () {
    'use strict';

    const BASE_URL      = '<?= base_url('master_kendaraan') ?>';
    const FASILITAS_OPT = ['AC','Audio System','GPS','Kamera Mundur','Airbag','Bagasi','Helm','Jas Hujan','P3K','Dongkrak'];
    const MODAL_ID      = '__kendaraan_modals__';

    // ── Helper: CSS variable untuk input di modal ──────
    // Kita inject class mk-input & mk-label ke field yang dibuat via JS
    function buildFields(prefix) {
        const p = prefix;
        const cbBoxes = FASILITAS_OPT.map(f =>
            `<label class="inline-flex items-center gap-1.5 cursor-pointer group">
                <input type="checkbox" name="fasilitas[]" value="${f}"
                       class="${p}fas_cb rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <span class="text-xs mk-label group-hover:text-blue-600 transition">${f}</span>
             </label>`
        ).join('');

        const inputCls = 'w-full rounded-xl border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 mk-input';
        const labelCls = 'block text-xs font-semibold mk-label mb-1';

        return `
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="sm:col-span-2">
                <label class="${labelCls}">Nama Kendaraan <span class="text-red-500">*</span></label>
                <input type="text" name="nama_kendaraan" id="${p}nama_kendaraan"
                       class="${inputCls}" placeholder="cth. Toyota Kijang Innova" required>
            </div>
            <div>
                <label class="${labelCls}">Jenis Kendaraan <span class="text-red-500">*</span></label>
                <select name="jenis" id="${p}jenis" class="${inputCls}">
                    <option value="Mobil">🚗 Mobil</option>
                    <option value="Motor">🏍️ Motor</option>
                </select>
            </div>
            <div>
                <label class="${labelCls}">Nomor Polisi <span class="text-red-500">*</span></label>
                <input type="text" name="nomor_polisi" id="${p}nomor_polisi"
                       class="${inputCls} font-mono" placeholder="cth. BM 1234 AB" required>
            </div>
            <div>
                <label class="${labelCls}">Merek</label>
                <input type="text" name="merek" id="${p}merek"
                       class="${inputCls}" placeholder="cth. Toyota, Honda">
            </div>
            <div>
                <label class="${labelCls}">Tahun</label>
                <input type="number" name="tahun" id="${p}tahun" min="1990" max="2030"
                       class="${inputCls}" placeholder="${new Date().getFullYear()}">
            </div>
            <div>
                <label class="${labelCls}">Warna</label>
                <input type="text" name="warna" id="${p}warna"
                       class="${inputCls}" placeholder="cth. Putih, Hitam">
            </div>
            <div>
                <label class="${labelCls}">Kapasitas BBM (Liter)</label>
                <input type="number" name="kapasitas_bahan_bakar" id="${p}kapasitas_bahan_bakar" min="0"
                       class="${inputCls}" placeholder="cth. 65">
            </div>
            <div>
                <label class="${labelCls}">Status</label>
                <select name="status" id="${p}status" class="${inputCls}">
                    <option value="Tersedia">✅ Tersedia</option>
                    <option value="Tidak Tersedia">❌ Tidak Tersedia</option>
                    <option value="Dalam Perbaikan">🔧 Dalam Perbaikan</option>
                </select>
            </div>
            <div class="sm:col-span-2">
                <label class="${labelCls} mb-2">Fasilitas</label>
                <div class="flex flex-wrap gap-2">${cbBoxes}</div>
            </div>
            <div class="sm:col-span-2">
                <label class="${labelCls}">Keterangan</label>
                <textarea name="keterangan" id="${p}keterangan" rows="2"
                          class="${inputCls} resize-none"
                          placeholder="Keterangan tambahan..."></textarea>
            </div>
        </div>`;
    }

    // ── Build & inject modals ──────────────────────────
    function buildModals() {
        const old = document.getElementById(MODAL_ID);
        if (old) old.remove();

        const wrapper = document.createElement('div');
        wrapper.id = MODAL_ID;
        wrapper.innerHTML = `
        <!-- MODAL TAMBAH -->
        <div id="mk_modalTambah"
             class="fixed inset-0 z-[9999] items-center justify-center bg-black/50 backdrop-blur-sm p-4"
             style="display:none">
            <div class="mk-modal-wrap rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="mk-modal-header flex items-center justify-between px-6 py-4 border-b">
                    <h3 class="font-bold mk-modal-title text-lg">Tambah Kendaraan</h3>
                    <button id="mk_closeTambah" class="mk-modal-close transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <form method="post" action="${BASE_URL}/simpan" class="px-6 py-5 space-y-4">
                    ${buildFields('')}
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" id="mk_batalTambah"
                                class="mk-btn-batal px-4 py-2 rounded-xl border text-sm transition">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm transition active:scale-95">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL EDIT -->
        <div id="mk_modalEdit"
             class="fixed inset-0 z-[9999] items-center justify-center bg-black/50 backdrop-blur-sm p-4"
             style="display:none">
            <div class="mk-modal-wrap rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="mk-modal-header flex items-center justify-between px-6 py-4 border-b">
                    <h3 class="font-bold mk-modal-title text-lg">Edit Kendaraan</h3>
                    <button id="mk_closeEdit" class="mk-modal-close transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <form method="post" action="${BASE_URL}/update" class="px-6 py-5 space-y-4">
                    <input type="hidden" name="id" id="edit_id">
                    ${buildFields('edit_')}
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" id="mk_batalEdit"
                                class="mk-btn-batal px-4 py-2 rounded-xl border text-sm transition">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-5 py-2 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-white font-semibold text-sm transition active:scale-95">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>`;

        document.body.appendChild(wrapper);
    }

    // ── Modal open / close ─────────────────────────────
    function mkOpen(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.style.display = 'flex';
        document.body.classList.add('overflow-hidden');
    }
    function mkClose(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.style.display = 'none';
        document.body.classList.remove('overflow-hidden');
    }

    // ── Bind events ────────────────────────────────────
    function bindEvents() {
        document.getElementById('mk_closeTambah').addEventListener('click', () => mkClose('mk_modalTambah'));
        document.getElementById('mk_batalTambah').addEventListener('click', () => mkClose('mk_modalTambah'));
        document.getElementById('mk_closeEdit').addEventListener('click',   () => mkClose('mk_modalEdit'));
        document.getElementById('mk_batalEdit').addEventListener('click',   () => mkClose('mk_modalEdit'));

        document.getElementById('mk_modalTambah').addEventListener('click', function(e) {
            if (e.target === this) mkClose('mk_modalTambah');
        });
        document.getElementById('mk_modalEdit').addEventListener('click', function(e) {
            if (e.target === this) mkClose('mk_modalEdit');
        });

        document.addEventListener('keydown', function(e) {
            if (e.key !== 'Escape') return;
            mkClose('mk_modalTambah');
            mkClose('mk_modalEdit');
        });

        document.getElementById('btnTambahKendaraan').addEventListener('click', () => mkOpen('mk_modalTambah'));

        // Event delegation tabel
        document.getElementById('tabelKendaraan').addEventListener('click', function(e) {
            const btnEdit = e.target.closest('.btnEditKendaraan');
            if (btnEdit) {
                const k = JSON.parse(btnEdit.dataset.kendaraan);
                document.getElementById('edit_id').value                    = k.id;
                document.getElementById('edit_nama_kendaraan').value        = k.nama_kendaraan;
                document.getElementById('edit_jenis').value                 = k.jenis;
                document.getElementById('edit_nomor_polisi').value          = k.nomor_polisi;
                document.getElementById('edit_merek').value                 = k.merek  || '';
                document.getElementById('edit_tahun').value                 = k.tahun  || '';
                document.getElementById('edit_warna').value                 = k.warna  || '';
                document.getElementById('edit_kapasitas_bahan_bakar').value = k.kapasitas_bahan_bakar || '';
                document.getElementById('edit_status').value                = k.status;
                document.getElementById('edit_keterangan').value            = k.keterangan || '';
                document.querySelectorAll('.edit_fas_cb').forEach(cb => {
                    cb.checked = k.fasilitas
                        ? k.fasilitas.split(',').map(f => f.trim()).includes(cb.value)
                        : false;
                });
                mkOpen('mk_modalEdit');
            }

            const btnHapus = e.target.closest('.btnHapusKendaraan');
            if (btnHapus) {
                const { id, nama } = btnHapus.dataset;
                Swal.fire({
                    title: 'Hapus Kendaraan?',
                    html: `Kendaraan <strong>${nama}</strong> akan dihapus permanen.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#374151',
                    reverseButtons: true
                }).then(r => {
                    if (r.isConfirmed) window.location.href = `${BASE_URL}/hapus/${id}`;
                });
            }
        });

        // Search
        document.getElementById('searchInput').addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('#tabelKendaraan tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    }

    // ── Init ───────────────────────────────────────────
    buildModals();
    bindEvents();

})();
</script>