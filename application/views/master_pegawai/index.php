<?php
// application/views/master_pegawai/index.php
?>

<?php if ($this->session->flashdata('success')): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        icon: 'success', title: 'Berhasil!',
        text: '<?= $this->session->flashdata('success') ?>',
        confirmButtonColor: '#2563eb', timer: 2500, timerProgressBar: true,
    });
});
</script>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        icon: 'error', title: 'Gagal!',
        text: '<?= $this->session->flashdata('error') ?>',
        confirmButtonColor: '#ef4444',
    });
});
</script>
<?php endif; ?>

<!-- ===== THEME VARIABLES ===== -->
<style>
/* --- Dark mode (default) --- */
:root,
html:not(.light) {
    --mp-bg-card:           #1a2233;
    --mp-bg-input:          #0f1623;
    --mp-bg-table:          #1e2a3a;
    --mp-bg-table-hover:    #263548;
    --mp-bg-row-hover:      rgba(255,255,255,0.05);
    --mp-border:            rgba(255,255,255,0.10);
    --mp-border-5:          rgba(255,255,255,0.05);
    --mp-text-primary:      #ffffff;
    --mp-text-secondary:    #d1d5db;
    --mp-text-muted:        #6b7280;
    --mp-text-label:        #9ca3af;
    --mp-th-color:          #9ca3af;
    --mp-placeholder:       #4b5563;
    /* Stat card labels */
    --mp-stat-total:        #60a5fa;
    --mp-stat-pns:          #34d399;
    --mp-stat-pppk:         #c084fc;
    --mp-stat-cpns:         #fbbf24;
    /* Avatar */
    --mp-avatar-bg:         rgba(59,130,246,0.20);
    --mp-avatar-text:       #60a5fa;
    /* NIP */
    --mp-nip-lama:          #9ca3af;
    --mp-nip-baru:          #60a5fa;
    /* Badge PNS */
    --mp-pns-bg:            rgba(16,185,129,0.15);
    --mp-pns-text:          #34d399;
    --mp-pns-ring:          rgba(16,185,129,0.30);
    --mp-pns-dot:           #34d399;
    /* Badge PPPK */
    --mp-pppk-bg:           rgba(168,85,247,0.15);
    --mp-pppk-text:         #c084fc;
    --mp-pppk-ring:         rgba(168,85,247,0.30);
    /* Badge CPNS */
    --mp-cpns-bg:           rgba(245,158,11,0.15);
    --mp-cpns-text:         #fbbf24;
    --mp-cpns-ring:         rgba(245,158,11,0.30);
    /* Badge TB */
    --mp-tb-bg:             rgba(14,165,233,0.15);
    --mp-tb-text:           #38bdf8;
    --mp-tb-ring:           rgba(14,165,233,0.30);
    /* Badge default */
    --mp-def-bg:            rgba(100,116,139,0.15);
    --mp-def-text:          #94a3b8;
    --mp-def-ring:          rgba(100,116,139,0.30);
    /* Tombol edit */
    --mp-btn-edit-bg:       rgba(245,158,11,0.10);
    --mp-btn-edit-text:     #fbbf24;
    --mp-btn-edit-border:   rgba(245,158,11,0.20);
    --mp-btn-edit-hover:    rgba(245,158,11,0.20);
    /* Tombol hapus */
    --mp-btn-del-bg:        rgba(239,68,68,0.10);
    --mp-btn-del-text:      #f87171;
    --mp-btn-del-border:    rgba(239,68,68,0.20);
    --mp-btn-del-hover:     rgba(239,68,68,0.20);
    /* Pagination */
    --mp-pag-bg:            #1e2a3a;
    --mp-pag-text:          #d1d5db;
    --mp-pag-hover:         #263548;
    --mp-pag-disabled:      #374151;
    /* Upload dropzone */
    --mp-drop-border:       rgba(255,255,255,0.10);
    --mp-drop-hover:        rgba(16,185,129,0.40);
    --mp-drop-bg:           #0f1623;
    --mp-drop-icon:         #6b7280;
    --mp-drop-text:         #9ca3af;
    --mp-drop-sub:          #4b5563;
}

/* --- Light mode --- */
html.light {
    --mp-bg-card:           #ffffff;
    --mp-bg-input:          #f8fafc;
    --mp-bg-table:          #ffffff;
    --mp-bg-table-hover:    #f1f5f9;
    --mp-bg-row-hover:      rgba(0,0,0,0.025);
    --mp-border:            rgba(0,0,0,0.10);
    --mp-border-5:          rgba(0,0,0,0.05);
    --mp-text-primary:      #0f172a;
    --mp-text-secondary:    #334155;
    --mp-text-muted:        #94a3b8;
    --mp-text-label:        #64748b;
    --mp-th-color:          #64748b;
    --mp-placeholder:       #94a3b8;
    /* Stat card labels */
    --mp-stat-total:        #2563eb;
    --mp-stat-pns:          #059669;
    --mp-stat-pppk:         #7c3aed;
    --mp-stat-cpns:         #d97706;
    /* Avatar */
    --mp-avatar-bg:         rgba(59,130,246,0.12);
    --mp-avatar-text:       #2563eb;
    /* NIP */
    --mp-nip-lama:          #64748b;
    --mp-nip-baru:          #2563eb;
    /* Badge PNS */
    --mp-pns-bg:            rgba(16,185,129,0.12);
    --mp-pns-text:          #059669;
    --mp-pns-ring:          rgba(16,185,129,0.25);
    --mp-pns-dot:           #059669;
    /* Badge PPPK */
    --mp-pppk-bg:           rgba(124,58,237,0.10);
    --mp-pppk-text:         #7c3aed;
    --mp-pppk-ring:         rgba(124,58,237,0.20);
    /* Badge CPNS */
    --mp-cpns-bg:           rgba(217,119,6,0.12);
    --mp-cpns-text:         #d97706;
    --mp-cpns-ring:         rgba(217,119,6,0.25);
    /* Badge TB */
    --mp-tb-bg:             rgba(2,132,199,0.10);
    --mp-tb-text:           #0284c7;
    --mp-tb-ring:           rgba(2,132,199,0.20);
    /* Badge default */
    --mp-def-bg:            rgba(100,116,139,0.10);
    --mp-def-text:          #475569;
    --mp-def-ring:          rgba(100,116,139,0.20);
    /* Tombol edit */
    --mp-btn-edit-bg:       rgba(245,158,11,0.08);
    --mp-btn-edit-text:     #b45309;
    --mp-btn-edit-border:   rgba(245,158,11,0.20);
    --mp-btn-edit-hover:    rgba(245,158,11,0.15);
    /* Tombol hapus */
    --mp-btn-del-bg:        rgba(239,68,68,0.08);
    --mp-btn-del-text:      #dc2626;
    --mp-btn-del-border:    rgba(239,68,68,0.20);
    --mp-btn-del-hover:     rgba(239,68,68,0.15);
    /* Pagination */
    --mp-pag-bg:            #ffffff;
    --mp-pag-text:          #475569;
    --mp-pag-hover:         #f1f5f9;
    --mp-pag-disabled:      #e2e8f0;
    /* Upload dropzone */
    --mp-drop-border:       rgba(0,0,0,0.10);
    --mp-drop-hover:        rgba(16,185,129,0.30);
    --mp-drop-bg:           #f8fafc;
    --mp-drop-icon:         #94a3b8;
    --mp-drop-text:         #64748b;
    --mp-drop-sub:          #94a3b8;
}

/* ── Apply ke elemen ── */
.mp-page-title        { color: var(--mp-text-primary); }
.mp-page-sub          { color: var(--mp-text-label); }

.mp-stat-card         { background-color: var(--mp-bg-table); border-color: var(--mp-border-5); }
.mp-stat-num          { color: var(--mp-text-primary); }
.mp-stat-sub          { color: var(--mp-text-muted); }
.mp-stat-label-total  { color: var(--mp-stat-total); }
.mp-stat-label-pns    { color: var(--mp-stat-pns); }
.mp-stat-label-pppk   { color: var(--mp-stat-pppk); }
.mp-stat-label-cpns   { color: var(--mp-stat-cpns); }

.mp-table-wrap        { background-color: var(--mp-bg-table); border-color: var(--mp-border-5); }
.mp-th                { color: var(--mp-th-color); border-color: var(--mp-border); }
.mp-tr                { border-color: var(--mp-border-5); }
.mp-tr:hover          { background-color: var(--mp-bg-row-hover); }
.mp-td-no             { color: var(--mp-text-muted); }
.mp-td-jabatan        { color: var(--mp-text-secondary); }
.mp-name-text         { color: var(--mp-text-primary); }

.mp-nip-lama          { color: var(--mp-nip-lama); }
.mp-nip-baru          { color: var(--mp-nip-baru); }

.mp-avatar            { background-color: var(--mp-avatar-bg); color: var(--mp-avatar-text); }

.mp-badge-pns         { background-color: var(--mp-pns-bg);  color: var(--mp-pns-text);  box-shadow: 0 0 0 1px var(--mp-pns-ring); }
.mp-badge-pns .mp-dot { background-color: var(--mp-pns-dot); }
.mp-badge-pppk        { background-color: var(--mp-pppk-bg); color: var(--mp-pppk-text); box-shadow: 0 0 0 1px var(--mp-pppk-ring); }
.mp-badge-cpns        { background-color: var(--mp-cpns-bg); color: var(--mp-cpns-text); box-shadow: 0 0 0 1px var(--mp-cpns-ring); }
.mp-badge-tb          { background-color: var(--mp-tb-bg);   color: var(--mp-tb-text);   box-shadow: 0 0 0 1px var(--mp-tb-ring); }
.mp-badge-def         { background-color: var(--mp-def-bg);  color: var(--mp-def-text);  box-shadow: 0 0 0 1px var(--mp-def-ring); }

.mp-btn-edit          { background-color: var(--mp-btn-edit-bg); color: var(--mp-btn-edit-text); border-color: var(--mp-btn-edit-border); }
.mp-btn-edit:hover    { background-color: var(--mp-btn-edit-hover); }
.mp-btn-edit svg      { stroke: var(--mp-btn-edit-text); }
.mp-btn-del           { background-color: var(--mp-btn-del-bg); color: var(--mp-btn-del-text); border-color: var(--mp-btn-del-border); }
.mp-btn-del:hover     { background-color: var(--mp-btn-del-hover); }
.mp-btn-del svg       { stroke: var(--mp-btn-del-text); }

.mp-input             { background-color: var(--mp-bg-input); border-color: var(--mp-border); color: var(--mp-text-primary); }
.mp-input::placeholder { color: var(--mp-placeholder); }
.mp-input option      { background-color: var(--mp-bg-input); color: var(--mp-text-primary); }
.mp-label             { color: var(--mp-text-label); }
.mp-modal-header      { background-color: var(--mp-bg-card); border-color: var(--mp-border); }
.mp-modal-title       { color: var(--mp-text-primary); }
.mp-modal-close       { color: var(--mp-text-label); }
.mp-modal-close:hover { color: var(--mp-text-primary); }
.mp-modal-body        { background-color: var(--mp-bg-card); border-color: var(--mp-border); }
.mp-btn-batal         { border-color: var(--mp-border); color: var(--mp-text-label); }
.mp-btn-batal:hover   { background-color: var(--mp-bg-row-hover); }
.mp-export-btn        { background-color: var(--mp-bg-table); border-color: var(--mp-border); color: var(--mp-text-secondary); }
.mp-export-btn:hover  { background-color: var(--mp-bg-table-hover); }

.mp-dropzone          { background-color: var(--mp-drop-bg); border-color: var(--mp-drop-border); }
.mp-dropzone:hover    { border-color: var(--mp-drop-hover); }
.mp-drop-icon         { color: var(--mp-drop-icon); stroke: var(--mp-drop-icon); }
.mp-drop-text         { color: var(--mp-drop-text); }
.mp-drop-sub          { color: var(--mp-drop-sub); }
.mp-file-label        { color: var(--mp-text-muted); }

.mp-pag-info          { color: var(--mp-text-muted); }
.mp-pag-btn           { background-color: var(--mp-pag-bg); border-color: var(--mp-border); color: var(--mp-pag-text); }
.mp-pag-btn:hover:not(:disabled) { background-color: var(--mp-pag-hover); }
.mp-pag-btn:disabled  { color: var(--mp-pag-disabled); opacity: .4; cursor: not-allowed; }
.mp-pag-active        { background-color: #2563eb !important; border-color: #2563eb !important; color: #ffffff !important; }

/* SweetAlert2 ikut theme */
.swal2-popup          { background-color: var(--mp-bg-card)     !important; border: 1.5px solid var(--mp-border) !important; color: var(--mp-text-primary) !important; }
.swal2-title          { color: var(--mp-text-primary)     !important; }
.swal2-html-container { color: var(--mp-text-secondary)   !important; }
</style>

<!-- PAGE HEADER -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold mp-page-title">Master Pegawai</h1>
        <p class="text-sm mp-page-sub mt-0.5">Kelola data pegawai BPS Provinsi Riau</p>
    </div>
    <div class="flex gap-2 flex-wrap">
        <button type="button" onclick="mpOpenModal('mp_modalImport')"
                class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-500
                       text-white text-sm font-semibold rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            Import
        </button>
        <button type="button" onclick="mpOpenModal('mp_modalTambah')"
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500
                       text-white text-sm font-semibold rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Pegawai
        </button>
    </div>
</div>

<!-- STAT CARDS -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <div class="mp-stat-card rounded-xl p-5 border">
        <div class="mp-stat-label-total text-xs font-semibold uppercase tracking-wider mb-2">Total Pegawai</div>
        <div class="text-3xl font-bold mp-stat-num"><?= $total_pegawai ?? 0 ?></div>
        <div class="text-xs mp-stat-sub mt-1">Terdaftar di sistem</div>
    </div>
    <div class="mp-stat-card rounded-xl p-5 border">
        <div class="mp-stat-label-pns text-xs font-semibold uppercase tracking-wider mb-2">PNS</div>
        <div class="text-3xl font-bold mp-stat-num"><?= $total_pns ?? 0 ?></div>
        <div class="text-xs mp-stat-sub mt-1">Pegawai Negeri Sipil</div>
    </div>
    <div class="mp-stat-card rounded-xl p-5 border">
        <div class="mp-stat-label-pppk text-xs font-semibold uppercase tracking-wider mb-2">PPPK</div>
        <div class="text-3xl font-bold mp-stat-num"><?= $total_pppk ?? 0 ?></div>
        <div class="text-xs mp-stat-sub mt-1">Peg. Pemerintah Kontrak</div>
    </div>
    <div class="mp-stat-card rounded-xl p-5 border">
        <div class="mp-stat-label-cpns text-xs font-semibold uppercase tracking-wider mb-2">CPNS</div>
        <div class="text-3xl font-bold mp-stat-num"><?= $total_cpns ?? 0 ?></div>
        <div class="text-xs mp-stat-sub mt-1">Calon Pegawai Negeri</div>
    </div>
</div>

<!-- TOOLBAR -->
<div class="flex flex-wrap items-center gap-3 mb-4">
    <div class="relative flex-1 min-w-[220px]">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 pointer-events-none mp-page-sub"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
        </svg>
        <input type="text" id="searchInput" oninput="filterTable()"
               placeholder="Cari nama, NIP, atau jabatan..."
               class="w-full pl-9 pr-3 py-2 rounded-lg border text-sm mp-input
                      focus:ring-2 focus:ring-blue-500 focus:outline-none">
    </div>
    <select id="filterStatus" onchange="filterTable()"
            class="px-3 py-2 rounded-lg border text-sm mp-input
                   focus:ring-2 focus:ring-blue-500 focus:outline-none">
        <option value="">Semua Status</option>
        <?php foreach ($status_list as $s): ?>
        <option value="<?= htmlspecialchars($s) ?>"><?= htmlspecialchars($s) ?></option>
        <?php endforeach; ?>
    </select>
    <a href="<?= base_url('master_pegawai/export') ?>"
       class="mp-export-btn inline-flex items-center gap-2 px-4 py-2 rounded-lg border text-sm transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586
                     a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Export CSV
    </a>
</div>

<!-- TABLE CARD -->
<div class="mp-table-wrap rounded-xl border overflow-hidden">
    <div class="overflow-x-auto">
        <table id="tabelPegawai" class="w-full text-sm">
            <thead>
                <tr class="border-b mp-th text-xs uppercase tracking-wider">
                    <th class="px-4 py-3 text-left font-semibold w-10">No</th>
                    <th class="px-4 py-3 text-left font-semibold">NIP Lama</th>
                    <th class="px-4 py-3 text-left font-semibold">NIP Baru</th>
                    <th class="px-4 py-3 text-left font-semibold">Nama Pegawai</th>
                    <th class="px-4 py-3 text-left font-semibold">Jabatan</th>
                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                    <th class="px-4 py-3 text-center font-semibold w-24">Aksi</th>
                </tr>
            </thead>
            <tbody id="tabelBody" class="divide-y mp-tr">
                <?php if (!empty($pegawai)): ?>
                <?php foreach ($pegawai as $i => $p): ?>
                <?php
                    $isTB   = strpos($p['status'], 'TB') === 0;
                    $badgeCls = [
                        'PNS'  => 'mp-badge-pns',
                        'PPPK' => 'mp-badge-pppk',
                        'CPNS' => 'mp-badge-cpns',
                    ];
                    $cls   = $isTB ? 'mp-badge-tb' : ($badgeCls[$p['status']] ?? 'mp-badge-def');
                    $label = $isTB ? 'TB' : $p['status'];
                    $title = $isTB ? $p['status'] : '';
                ?>
                <tr class="mp-tr transition row-data"
                    data-search="<?= strtolower($p['nama'].' '.$p['nip_lama'].' '.$p['nip_baru'].' '.($p['jabatan']??'')) ?>"
                    data-status="<?= htmlspecialchars($p['status']) ?>">
                    <td class="px-4 py-3.5 mp-td-no text-xs row-no"><?= $i + 1 ?></td>
                    <td class="px-4 py-3.5 font-mono text-xs mp-nip-lama"><?= htmlspecialchars($p['nip_lama']) ?></td>
                    <td class="px-4 py-3.5 font-mono text-xs mp-nip-baru"><?= htmlspecialchars($p['nip_baru']) ?></td>
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full mp-avatar flex items-center justify-center
                                        text-sm font-bold flex-shrink-0">
                                <?= strtoupper(substr($p['nama'], 0, 1)) ?>
                            </div>
                            <span class="font-medium mp-name-text text-sm leading-tight">
                                <?= htmlspecialchars($p['nama']) ?>
                            </span>
                        </div>
                    </td>
                    <td class="px-4 py-3.5 mp-td-jabatan text-xs max-w-xs truncate"
                        title="<?= htmlspecialchars($p['jabatan'] ?? '') ?>">
                        <?= htmlspecialchars($p['jabatan'] ?? '-') ?>
                    </td>
                    <td class="px-4 py-3.5">
                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full <?= $cls ?>"
                              <?= $title ? 'title="'.htmlspecialchars($title).'"' : '' ?>>
                            <?php if ($p['status'] === 'PNS'): ?>
                            <span class="mp-dot w-1.5 h-1.5 rounded-full mr-1.5"></span>
                            <?php endif; ?>
                            <?= htmlspecialchars($label) ?>
                        </span>
                    </td>
                    <td class="px-4 py-3.5">
                        <div class="flex items-center justify-center gap-1.5">
                            <button type="button" onclick='openEditPegawai(<?= json_encode($p) ?>)'
                                    class="mp-btn-edit p-1.5 rounded-lg border transition" title="Edit">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                                             m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button type="button" onclick="hapusPegawai(<?= $p['id'] ?>, '<?= addslashes($p['nama']) ?>')"
                                    class="mp-btn-del p-1.5 rounded-lg border transition" title="Hapus">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858
                                             L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr id="emptyRow">
                    <td colspan="7" class="px-5 py-16 text-center">
                        <svg class="w-12 h-12 mx-auto mb-3 mp-page-sub opacity-40" fill="none"
                             stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283
                                     -.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283
                                     .356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <p class="mp-page-sub text-sm">Belum ada data pegawai</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- PAGINATION BAR -->
<div class="flex flex-wrap items-center justify-between gap-3 mt-4 px-1">
    <p id="paginationInfo" class="text-xs mp-pag-info"></p>
    <div id="paginationButtons" class="flex items-center gap-1 flex-wrap"></div>
</div>


<!-- ===== MODAL TAMBAH ===== -->
<div id="mp_modalTambah" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/60">
    <div class="mp-modal-body rounded-xl shadow-2xl w-full max-w-lg border
                max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between px-5 py-4 border-b mp-modal-header
                    sticky top-0 rounded-t-xl">
            <h3 class="text-base font-bold mp-modal-title">Tambah Pegawai</h3>
            <button type="button" onclick="mpCloseModal('mp_modalTambah')" class="mp-modal-close transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form action="<?= base_url('master_pegawai/simpan') ?>" method="POST">
            <div class="p-5 grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold mp-label mb-1.5">NIP Lama</label>
                    <input type="text" name="nip_lama" maxlength="20" placeholder="contoh: 340012026"
                           class="w-full px-3 py-2 rounded-lg border text-sm mp-input focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold mp-label mb-1.5">
                        NIP Baru <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="nip_baru" required maxlength="25" placeholder="18 digit"
                           class="w-full px-3 py-2 rounded-lg border text-sm mp-input focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-semibold mp-label mb-1.5">
                        Nama Lengkap <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="nama" required
                           class="w-full px-3 py-2 rounded-lg border text-sm mp-input focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold mp-label mb-1.5">Kode Satker</label>
                    <input type="text" name="kode_satker" value="1400" maxlength="10"
                           class="w-full px-3 py-2 rounded-lg border text-sm mp-input focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold mp-label mb-1.5">Status</label>
                    <select name="status" class="w-full px-3 py-2 rounded-lg border text-sm mp-input focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <?php foreach ($status_list as $s): ?>
                        <option value="<?= htmlspecialchars($s) ?>"><?= htmlspecialchars($s) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-semibold mp-label mb-1.5">Jabatan</label>
                    <input type="text" name="jabatan"
                           class="w-full px-3 py-2 rounded-lg border text-sm mp-input focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>
            <div class="flex gap-3 px-5 pb-5">
                <button type="button" onclick="mpCloseModal('mp_modalTambah')"
                        class="flex-1 px-4 py-2.5 border rounded-lg mp-btn-batal transition text-sm">Batal</button>
                <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-lg
                               transition font-semibold text-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== MODAL EDIT ===== -->
<div id="mp_modalEdit" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/60">
    <div class="mp-modal-body rounded-xl shadow-2xl w-full max-w-lg border
                max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between px-5 py-4 border-b mp-modal-header
                    sticky top-0 rounded-t-xl">
            <h3 class="text-base font-bold mp-modal-title">Edit Pegawai</h3>
            <button type="button" onclick="mpCloseModal('mp_modalEdit')" class="mp-modal-close transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form action="<?= base_url('master_pegawai/update') ?>" method="POST">
            <input type="hidden" name="id" id="ep_id">
            <div class="p-5 grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold mp-label mb-1.5">NIP Lama</label>
                    <input type="text" name="nip_lama" id="ep_nip_lama" maxlength="20"
                           class="w-full px-3 py-2 rounded-lg border text-sm mp-input focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold mp-label mb-1.5">NIP Baru</label>
                    <input type="text" name="nip_baru" id="ep_nip_baru" maxlength="25"
                           class="w-full px-3 py-2 rounded-lg border text-sm mp-input focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-semibold mp-label mb-1.5">Nama Lengkap</label>
                    <input type="text" name="nama" id="ep_nama"
                           class="w-full px-3 py-2 rounded-lg border text-sm mp-input focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold mp-label mb-1.5">Kode Satker</label>
                    <input type="text" name="kode_satker" id="ep_satker" maxlength="10"
                           class="w-full px-3 py-2 rounded-lg border text-sm mp-input focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold mp-label mb-1.5">Status</label>
                    <select name="status" id="ep_status"
                            class="w-full px-3 py-2 rounded-lg border text-sm mp-input focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <?php foreach ($status_list as $s): ?>
                        <option value="<?= htmlspecialchars($s) ?>"><?= htmlspecialchars($s) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-semibold mp-label mb-1.5">Jabatan</label>
                    <input type="text" name="jabatan" id="ep_jabatan"
                           class="w-full px-3 py-2 rounded-lg border text-sm mp-input focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>
            <div class="flex gap-3 px-5 pb-5">
                <button type="button" onclick="mpCloseModal('mp_modalEdit')"
                        class="flex-1 px-4 py-2.5 border rounded-lg mp-btn-batal transition text-sm">Batal</button>
                <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-amber-600 hover:bg-amber-500 text-white rounded-lg
                               transition font-semibold text-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== MODAL IMPORT ===== -->
<div id="mp_modalImport" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/60">
    <div class="mp-modal-body rounded-xl shadow-2xl w-full max-w-md border"
         onclick="event.stopPropagation()">
        <div class="flex items-center justify-between px-5 py-4 border-b mp-modal-header rounded-t-xl">
            <h3 class="text-base font-bold mp-modal-title">Import Data Pegawai</h3>
            <button type="button" onclick="mpCloseModal('mp_modalImport')" class="mp-modal-close transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form action="<?= base_url('master_pegawai/import') ?>" method="POST"
              enctype="multipart/form-data">
            <div class="p-5">
                <div class="mp-dropzone border-2 border-dashed rounded-xl p-8 text-center mb-4 transition-colors cursor-pointer"
                     onclick="document.getElementById('file_excel_input').click()">
                    <svg class="w-10 h-10 mx-auto mp-drop-icon mb-3" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    <p class="text-sm mp-drop-text mb-1">Klik untuk pilih file</p>
                    <p class="text-xs mp-drop-sub">Format CSV (kolom: NIP Lama, NIP Baru, Nama,
                        Kode Satker, Jabatan, Status)</p>
                    <input type="file" id="file_excel_input" name="file_excel"
                           accept=".xlsx,.xls,.csv" required class="hidden"
                           onchange="document.getElementById('file_label').textContent =
                               this.files[0]?.name || 'Belum ada file'">
                </div>
                <p id="file_label" class="text-xs text-center mp-file-label mb-3">Belum ada file dipilih</p>
                <a href="<?= base_url('master_pegawai/template') ?>"
                   class="text-xs text-blue-400 hover:text-blue-300 flex items-center gap-1 justify-center">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 10v6m0 0l-3-3m3 3l3-3"/>
                    </svg>
                    Download Template CSV
                </a>
            </div>
            <div class="flex gap-3 px-5 pb-5">
                <button type="button" onclick="mpCloseModal('mp_modalImport')"
                        class="flex-1 px-4 py-2.5 border rounded-lg mp-btn-batal transition text-sm">Batal</button>
                <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg
                               transition font-semibold text-sm">Import</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== SCRIPTS ===== -->
<script>
// ── Modal ────────────────────────────────────────────────
function mpOpenModal(id) { var el = document.getElementById(id); if (el) el.classList.remove('hidden'); }
function mpCloseModal(id){ var el = document.getElementById(id); if (el) el.classList.add('hidden'); }

// ── Pagination state ─────────────────────────────────────
var PAGE_SIZE   = 10;
var currentPage = 1;
var visibleRows = [];

// ── Filter + Search ──────────────────────────────────────
function filterTable() {
    var keyword = (document.getElementById('searchInput').value || '').toLowerCase().trim();
    var status  = document.getElementById('filterStatus').value;
    var rows    = Array.from(document.querySelectorAll('.row-data'));
    visibleRows = rows.filter(function (row) {
        var ok = (!keyword || row.dataset.search.indexOf(keyword) !== -1)
              && (!status  || row.dataset.status === status);
        row.style.display = 'none';
        return ok;
    });
    currentPage = 1;
    renderPage();
}

// ── Render halaman aktif ─────────────────────────────────
function renderPage() {
    var total     = visibleRows.length;
    var totalPage = Math.max(1, Math.ceil(total / PAGE_SIZE));
    if (currentPage > totalPage) currentPage = totalPage;
    var start = (currentPage - 1) * PAGE_SIZE;
    var end   = start + PAGE_SIZE;
    visibleRows.forEach(function (row, idx) {
        var show = idx >= start && idx < end;
        row.style.display = show ? '' : 'none';
        if (show) { var noCell = row.querySelector('.row-no'); if (noCell) noCell.textContent = start + idx + 1; }
    });
    var emptyRow = document.getElementById('noResultRow');
    if (total === 0) {
        if (!emptyRow) {
            var tbody = document.getElementById('tabelBody');
            var tr = document.createElement('tr');
            tr.id = 'noResultRow';
            tr.innerHTML = '<td colspan="7" class="px-5 py-12 text-center">'
                + '<svg class="w-10 h-10 mx-auto mb-3 mp-page-sub opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">'
                + '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>'
                + '</svg><p class="mp-page-sub text-sm">Tidak ada data yang cocok</p></td>';
            tbody.appendChild(tr);
        }
    } else { if (emptyRow) emptyRow.remove(); }
    renderInfo(total, start, Math.min(end, total));
    renderPagination(totalPage);
}

function renderInfo(total, start, end) {
    var el = document.getElementById('paginationInfo');
    if (el) el.textContent = total === 0 ? '' : 'Menampilkan ' + (start+1) + '\u2013' + end + ' dari ' + total + ' data';
}

function renderPagination(totalPage) {
    var container = document.getElementById('paginationButtons');
    if (!container) return;
    container.innerHTML = '';
    if (totalPage <= 1) return;
    container.appendChild(makePagBtn('\u2039 Prev', currentPage === 1, function () { if (currentPage > 1) { currentPage--; renderPage(); } }));
    pageRange(currentPage, totalPage).forEach(function (p) {
        if (p === '...') {
            var dots = document.createElement('span');
            dots.textContent = '\u2026';
            dots.className = 'px-2 py-1 mp-pag-info text-sm select-none';
            container.appendChild(dots);
        } else {
            var btn = makePagBtn(p, false, function (pg) { return function () { currentPage = pg; renderPage(); }; }(p));
            if (p === currentPage) btn.classList.add('mp-pag-active');
            container.appendChild(btn);
        }
    });
    container.appendChild(makePagBtn('Next \u203a', currentPage === totalPage, function () { if (currentPage < totalPage) { currentPage++; renderPage(); } }));
}

function makePagBtn(label, disabled, onClick) {
    var btn = document.createElement('button');
    btn.type = 'button';
    btn.textContent = label;
    btn.disabled = disabled;
    btn.className = 'mp-pag-btn px-3 py-1.5 rounded-lg border text-sm transition';
    if (!disabled) btn.addEventListener('click', onClick);
    return btn;
}

function pageRange(current, total) {
    if (total <= 7) return Array.from({ length: total }, function (_, i) { return i + 1; });
    if (current <= 4)         return [1, 2, 3, 4, 5, '...', total];
    if (current >= total - 3) return [1, '...', total-4, total-3, total-2, total-1, total];
    return [1, '...', current-1, current, current+1, '...', total];
}

// ── Edit ─────────────────────────────────────────────────
function openEditPegawai(p) {
    document.getElementById('ep_id').value       = p.id          || '';
    document.getElementById('ep_nip_lama').value = p.nip_lama    || '';
    document.getElementById('ep_nip_baru').value = p.nip_baru    || '';
    document.getElementById('ep_nama').value     = p.nama        || '';
    document.getElementById('ep_satker').value   = p.kode_satker || '1400';
    document.getElementById('ep_jabatan').value  = p.jabatan     || '';
    document.getElementById('ep_status').value   = p.status      || 'PNS';
    mpOpenModal('mp_modalEdit');
}

// ── Hapus ─────────────────────────────────────────────────
function hapusPegawai(id, nama) {
    Swal.fire({
        title: 'Hapus Pegawai?',
        text: 'Yakin ingin menghapus data "' + nama + '"?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        reverseButtons: true,
    }).then(function (r) {
        if (r.isConfirmed)
            window.location.href = '<?= base_url('master_pegawai/hapus/') ?>' + id;
    });
}

// ── Init ──────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    ['mp_modalTambah', 'mp_modalEdit', 'mp_modalImport'].forEach(function (id) {
        var el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('click', function (e) { if (e.target === el) mpCloseModal(id); });
    });
    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        ['mp_modalTambah', 'mp_modalEdit', 'mp_modalImport'].forEach(function (id) {
            var el = document.getElementById(id);
            if (el && !el.classList.contains('hidden')) mpCloseModal(id);
        });
    });
    filterTable();
});
</script>