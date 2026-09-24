<?php
// application/views/master_bmn/index.php
?>

<!-- Tom Select CSS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<!-- ===== THEME VARIABLES ===== -->
<style>
/* --- Dark mode (default) --- */
:root,
html:not(.light) {
    --bmn-bg-card:           #1a2233;
    --bmn-bg-input:          #0f1623;
    --bmn-bg-table:          #1e2a3a;
    --bmn-bg-table-hover:    #263548;
    --bmn-bg-row-hover:      rgba(255,255,255,0.05);
    --bmn-border:            rgba(255,255,255,0.10);
    --bmn-border-5:          rgba(255,255,255,0.05);
    --bmn-text-primary:      #ffffff;
    --bmn-text-secondary:    #d1d5db;
    --bmn-text-muted:        #6b7280;
    --bmn-text-label:        #9ca3af;
    --bmn-th-color:          #9ca3af;
    --bmn-placeholder:       #4b5563;
    /* Stat cards */
    --bmn-stat-tersedia-bg:  rgba(16,185,129,0.15);
    --bmn-stat-tersedia-ic:  #34d399;
    --bmn-stat-dipinjam-bg:  rgba(59,130,246,0.15);
    --bmn-stat-dipinjam-ic:  #60a5fa;
    --bmn-stat-perbaikan-bg: rgba(245,158,11,0.15);
    --bmn-stat-perbaikan-ic: #fbbf24;
    /* Badge status */
    --bmn-st-tersedia-bg:    rgba(16,185,129,0.15);
    --bmn-st-tersedia-text:  #34d399;
    --bmn-st-tersedia-ring:  rgba(16,185,129,0.30);
    --bmn-st-dipinjam-bg:    rgba(59,130,246,0.15);
    --bmn-st-dipinjam-text:  #60a5fa;
    --bmn-st-dipinjam-ring:  rgba(59,130,246,0.30);
    --bmn-st-perbaikan-bg:   rgba(245,158,11,0.15);
    --bmn-st-perbaikan-text: #fbbf24;
    --bmn-st-perbaikan-ring: rgba(245,158,11,0.30);
    /* Badge kondisi */
    --bmn-kd-baik-bg:        rgba(16,185,129,0.10);
    --bmn-kd-baik-text:      #34d399;
    --bmn-kd-ringan-bg:      rgba(245,158,11,0.10);
    --bmn-kd-ringan-text:    #fbbf24;
    --bmn-kd-berat-bg:       rgba(239,68,68,0.10);
    --bmn-kd-berat-text:     #f87171;
    /* Kode barang chip */
    --bmn-kode-bg:           #0f1623;
    --bmn-kode-text:         #9ca3af;
    --bmn-kode-border:       rgba(255,255,255,0.05);
    --bmn-nup-text:          #4b5563;
    /* Tombol edit */
    --bmn-btn-edit-bg:       rgba(245,158,11,0.10);
    --bmn-btn-edit-text:     #fbbf24;
    --bmn-btn-edit-border:   rgba(245,158,11,0.20);
    --bmn-btn-edit-hover:    rgba(245,158,11,0.20);
    /* Tombol hapus */
    --bmn-btn-del-bg:        rgba(239,68,68,0.10);
    --bmn-btn-del-text:      #f87171;
    --bmn-btn-del-border:    rgba(239,68,68,0.20);
    --bmn-btn-del-hover:     rgba(239,68,68,0.20);
    /* Disabled hapus */
    --bmn-btn-dis-text:      #374151;
    /* Tom Select */
    --bmn-ts-bg:             #0f1623;
    --bmn-ts-dropdown:       #1a2233;
    --bmn-ts-option:         #d1d5db;
    --bmn-ts-hover:          rgba(168,85,247,0.15);
    --bmn-ts-border:         rgba(255,255,255,0.10);
    --bmn-ts-focus:          rgb(168,85,247);
    --bmn-ts-focus-shadow:   rgba(168,85,247,0.25);
    --bmn-ts-input-text:     #ffffff;
    --bmn-ts-placeholder:    #4b5563;
    /* Preview box */
    --bmn-preview-bg:        #0f1623;
    --bmn-preview-border:    rgba(168,85,247,0.20);
    --bmn-preview-label:     rgba(168,85,247,0.25);
    --bmn-preview-label-text:#c084fc;
    --bmn-preview-icon-bg:   rgba(168,85,247,0.20);
    --bmn-preview-icon:      #c084fc;
    --bmn-hint-bg:           rgba(168,85,247,0.10);
    --bmn-hint-border:       rgba(168,85,247,0.20);
    --bmn-hint-text:         #c084fc;
    --bmn-hint-icon:         #c084fc;
    /* SweetAlert override */
    --bmn-swal-bg:           #1a2233;
    --bmn-swal-border:       rgba(255,255,255,0.10);
}

/* --- Light mode --- */
html.light {
    --bmn-bg-card:           #ffffff;
    --bmn-bg-input:          #f8fafc;
    --bmn-bg-table:          #ffffff;
    --bmn-bg-table-hover:    #f1f5f9;
    --bmn-bg-row-hover:      rgba(0,0,0,0.025);
    --bmn-border:            rgba(0,0,0,0.10);
    --bmn-border-5:          rgba(0,0,0,0.05);
    --bmn-text-primary:      #0f172a;
    --bmn-text-secondary:    #334155;
    --bmn-text-muted:        #94a3b8;
    --bmn-text-label:        #64748b;
    --bmn-th-color:          #64748b;
    --bmn-placeholder:       #94a3b8;
    /* Stat cards */
    --bmn-stat-tersedia-bg:  rgba(16,185,129,0.10);
    --bmn-stat-tersedia-ic:  #059669;
    --bmn-stat-dipinjam-bg:  rgba(37,99,235,0.10);
    --bmn-stat-dipinjam-ic:  #2563eb;
    --bmn-stat-perbaikan-bg: rgba(217,119,6,0.10);
    --bmn-stat-perbaikan-ic: #d97706;
    /* Badge status */
    --bmn-st-tersedia-bg:    rgba(16,185,129,0.12);
    --bmn-st-tersedia-text:  #059669;
    --bmn-st-tersedia-ring:  rgba(16,185,129,0.25);
    --bmn-st-dipinjam-bg:    rgba(37,99,235,0.10);
    --bmn-st-dipinjam-text:  #2563eb;
    --bmn-st-dipinjam-ring:  rgba(37,99,235,0.20);
    --bmn-st-perbaikan-bg:   rgba(217,119,6,0.12);
    --bmn-st-perbaikan-text: #d97706;
    --bmn-st-perbaikan-ring: rgba(217,119,6,0.25);
    /* Badge kondisi */
    --bmn-kd-baik-bg:        rgba(16,185,129,0.10);
    --bmn-kd-baik-text:      #059669;
    --bmn-kd-ringan-bg:      rgba(217,119,6,0.10);
    --bmn-kd-ringan-text:    #d97706;
    --bmn-kd-berat-bg:       rgba(220,38,38,0.10);
    --bmn-kd-berat-text:     #dc2626;
    /* Kode barang chip */
    --bmn-kode-bg:           #f1f5f9;
    --bmn-kode-text:         #64748b;
    --bmn-kode-border:       rgba(0,0,0,0.07);
    --bmn-nup-text:          #94a3b8;
    /* Tombol edit */
    --bmn-btn-edit-bg:       rgba(245,158,11,0.08);
    --bmn-btn-edit-text:     #b45309;
    --bmn-btn-edit-border:   rgba(245,158,11,0.20);
    --bmn-btn-edit-hover:    rgba(245,158,11,0.15);
    /* Tombol hapus */
    --bmn-btn-del-bg:        rgba(239,68,68,0.08);
    --bmn-btn-del-text:      #dc2626;
    --bmn-btn-del-border:    rgba(239,68,68,0.20);
    --bmn-btn-del-hover:     rgba(239,68,68,0.15);
    /* Disabled hapus */
    --bmn-btn-dis-text:      #cbd5e1;
    /* Tom Select */
    --bmn-ts-bg:             #f8fafc;
    --bmn-ts-dropdown:       #ffffff;
    --bmn-ts-option:         #334155;
    --bmn-ts-hover:          rgba(124,58,237,0.10);
    --bmn-ts-border:         rgba(0,0,0,0.10);
    --bmn-ts-focus:          #7c3aed;
    --bmn-ts-focus-shadow:   rgba(124,58,237,0.20);
    --bmn-ts-input-text:     #0f172a;
    --bmn-ts-placeholder:    #94a3b8;
    /* Preview box */
    --bmn-preview-bg:        #f8fafc;
    --bmn-preview-border:    rgba(124,58,237,0.20);
    --bmn-preview-label:     rgba(124,58,237,0.15);
    --bmn-preview-label-text:#7c3aed;
    --bmn-preview-icon-bg:   rgba(124,58,237,0.12);
    --bmn-preview-icon:      #7c3aed;
    --bmn-hint-bg:           rgba(124,58,237,0.07);
    --bmn-hint-border:       rgba(124,58,237,0.15);
    --bmn-hint-text:         #7c3aed;
    --bmn-hint-icon:         #7c3aed;
    /* SweetAlert override */
    --bmn-swal-bg:           #ffffff;
    --bmn-swal-border:       rgba(0,0,0,0.08);
}

/* ── Apply ke elemen ── */
.bmn-page-title       { color: var(--bmn-text-primary); }
.bmn-page-sub         { color: var(--bmn-text-label); }

.bmn-stat-card        { background-color: var(--bmn-bg-table); border-color: var(--bmn-border-5); }
.bmn-stat-num         { color: var(--bmn-text-primary); }
.bmn-stat-sub-lbl     { color: var(--bmn-text-muted); }
.bmn-stat-icon-t      { background-color: var(--bmn-stat-tersedia-bg); color: var(--bmn-stat-tersedia-ic); }
.bmn-stat-icon-d      { background-color: var(--bmn-stat-dipinjam-bg); color: var(--bmn-stat-dipinjam-ic); }
.bmn-stat-icon-p      { background-color: var(--bmn-stat-perbaikan-bg); color: var(--bmn-stat-perbaikan-ic); }

.bmn-table-wrap       { background-color: var(--bmn-bg-table); border-color: var(--bmn-border-5); }
.bmn-table-head       { background-color: var(--bmn-bg-table); border-color: var(--bmn-border-5); }
.bmn-table-head-title { color: var(--bmn-text-primary); }
.bmn-table-head-count { color: var(--bmn-text-muted); }
.bmn-th               { color: var(--bmn-th-color); border-color: var(--bmn-border); }
.bmn-tr               { border-color: var(--bmn-border-5); }
.bmn-tr:hover         { background-color: var(--bmn-bg-row-hover); }
.bmn-td-no            { color: var(--bmn-text-muted); }
.bmn-td-name          { color: var(--bmn-text-primary); }
.bmn-td-sub           { color: var(--bmn-text-muted); }
.bmn-td-spek          { color: var(--bmn-text-secondary); }
.bmn-td-lokasi        { color: var(--bmn-text-secondary); }

.bmn-kode-chip        { background-color: var(--bmn-kode-bg); color: var(--bmn-kode-text); border-color: var(--bmn-kode-border); }
.bmn-nup-text         { color: var(--bmn-nup-text); }

.bmn-badge-tersedia   { background-color: var(--bmn-st-tersedia-bg); color: var(--bmn-st-tersedia-text); box-shadow: 0 0 0 1px var(--bmn-st-tersedia-ring); }
.bmn-badge-dipinjam   { background-color: var(--bmn-st-dipinjam-bg); color: var(--bmn-st-dipinjam-text); box-shadow: 0 0 0 1px var(--bmn-st-dipinjam-ring); }
.bmn-badge-perbaikan  { background-color: var(--bmn-st-perbaikan-bg); color: var(--bmn-st-perbaikan-text); box-shadow: 0 0 0 1px var(--bmn-st-perbaikan-ring); }

.bmn-badge-baik       { background-color: var(--bmn-kd-baik-bg); color: var(--bmn-kd-baik-text); }
.bmn-badge-ringan     { background-color: var(--bmn-kd-ringan-bg); color: var(--bmn-kd-ringan-text); }
.bmn-badge-berat      { background-color: var(--bmn-kd-berat-bg); color: var(--bmn-kd-berat-text); }

.bmn-btn-edit         { background-color: var(--bmn-btn-edit-bg); color: var(--bmn-btn-edit-text); border-color: var(--bmn-btn-edit-border); }
.bmn-btn-edit:hover   { background-color: var(--bmn-btn-edit-hover); }
.bmn-btn-del          { background-color: var(--bmn-btn-del-bg); color: var(--bmn-btn-del-text); border-color: var(--bmn-btn-del-border); }
.bmn-btn-del:hover    { background-color: var(--bmn-btn-del-hover); }
.bmn-btn-dis          { color: var(--bmn-btn-dis-text); cursor: not-allowed; }

.bmn-input            { background-color: var(--bmn-bg-input); border-color: var(--bmn-border); color: var(--bmn-text-primary); }
.bmn-input::placeholder { color: var(--bmn-placeholder); }
.bmn-input option     { background-color: var(--bmn-bg-input); color: var(--bmn-text-primary); }
.bmn-label            { color: var(--bmn-text-label); }

.bmn-modal-header     { background-color: var(--bmn-bg-card); border-color: var(--bmn-border); }
.bmn-modal-title      { color: var(--bmn-text-primary); }
.bmn-modal-sub        { color: var(--bmn-text-muted); }
.bmn-modal-close      { color: var(--bmn-text-label); }
.bmn-modal-close:hover { color: var(--bmn-text-primary); }
.bmn-modal-body       { background-color: var(--bmn-bg-card); border-color: var(--bmn-border); }
.bmn-modal-kode-box   { background-color: var(--bmn-bg-input); border-color: var(--bmn-border-5); }
.bmn-modal-kode-lbl   { color: var(--bmn-text-muted); }
.bmn-modal-kode-val   { color: var(--bmn-text-secondary); }
.bmn-btn-batal        { border-color: var(--bmn-border); color: var(--bmn-text-label); }
.bmn-btn-batal:hover  { background-color: var(--bmn-bg-row-hover); }

.bmn-preview-box      { background-color: var(--bmn-preview-bg); border-color: var(--bmn-preview-border); }
.bmn-preview-header   { border-color: rgba(168,85,247,0.15); }
.bmn-preview-icon-wrap { background-color: var(--bmn-preview-icon-bg); }
.bmn-preview-icon     { color: var(--bmn-preview-icon); }
.bmn-preview-label-text { color: var(--bmn-preview-label-text); }
.bmn-preview-field-lbl  { color: var(--bmn-text-muted); }
.bmn-preview-field-val  { color: var(--bmn-text-primary); }
.bmn-preview-kode-chip  { background-color: rgba(0,0,0,0.15); color: var(--bmn-text-primary); }
.bmn-preview-spek     { color: var(--bmn-text-secondary); }
.bmn-preview-divider  { border-color: var(--bmn-border-5); }

.bmn-hint-box         { background-color: var(--bmn-hint-bg); border-color: var(--bmn-hint-border); }
.bmn-hint-icon        { color: var(--bmn-hint-icon); }
.bmn-hint-text        { color: var(--bmn-hint-text); }

/* Tom Select custom theme via CSS variables */
.ts-wrapper.single .ts-control {
    background: var(--bmn-ts-bg) !important;
    border: 1px solid var(--bmn-ts-border) !important;
    border-radius: 8px !important;
    color: var(--bmn-ts-input-text) !important;
    font-size: 12px !important;
    padding: 7px 10px !important;
    min-height: 36px !important;
    box-shadow: none !important;
    cursor: text !important;
}
.ts-wrapper.single.focus .ts-control,
.ts-wrapper.single .ts-control:focus {
    border-color: var(--bmn-ts-focus) !important;
    box-shadow: 0 0 0 2px var(--bmn-ts-focus-shadow) !important;
    outline: none !important;
}
.ts-control input { color: var(--bmn-ts-input-text) !important; font-size: 12px !important; }
.ts-control input::placeholder { color: var(--bmn-ts-placeholder) !important; }
.ts-dropdown {
    background: var(--bmn-ts-dropdown) !important;
    border: 1px solid var(--bmn-ts-border) !important;
    border-radius: 10px !important;
    box-shadow: 0 20px 40px rgba(0,0,0,0.3) !important;
    margin-top: 4px !important;
    overflow: hidden !important;
}
.ts-dropdown .ts-dropdown-content { max-height: 260px !important; }
.ts-dropdown .option {
    color: var(--bmn-ts-option) !important;
    font-size: 11px !important;
    padding: 7px 12px !important;
    border-bottom: 1px solid var(--bmn-border-5) !important;
    cursor: pointer !important;
    line-height: 1.5 !important;
}
.ts-dropdown .option:hover,
.ts-dropdown .option.active { background: var(--bmn-ts-hover) !important; color: var(--bmn-text-primary) !important; }
.ts-dropdown .option[data-disabled] {
    color: var(--bmn-placeholder) !important; cursor: not-allowed !important; background: transparent !important;
}
.ts-dropdown .no-results {
    color: var(--bmn-text-muted) !important; font-size: 12px !important;
    padding: 14px !important; text-align: center !important; background: transparent !important;
}
.ts-dropdown-header { display: none !important; }
.opt-sudah {
    display: inline-block; font-size: 9px; font-weight: 700;
    background: rgba(239,68,68,0.15); color: #f87171;
    border: 1px solid rgba(239,68,68,0.3); border-radius: 20px;
    padding: 1px 6px; margin-left: 4px; vertical-align: middle;
}
html.light .opt-sudah { background: rgba(220,38,38,0.10); color: #dc2626; border-color: rgba(220,38,38,0.25); }
.opt-kode { font-family: monospace; font-size: 10px; color: var(--bmn-text-muted); margin-left: 4px; }
.ts-wrapper.single .ts-control .item { color: var(--bmn-ts-input-text) !important; font-size: 12px !important; }
.ts-wrapper .clear-button { color: var(--bmn-text-muted) !important; font-size: 16px !important; }
.ts-wrapper .clear-button:hover { color: var(--bmn-text-primary) !important; }

/* SweetAlert2 ikut theme */
.swal2-popup          { background-color: var(--bmn-swal-bg)    !important; border: 1.5px solid var(--bmn-swal-border) !important; color: var(--bmn-text-primary) !important; }
.swal2-title          { color: var(--bmn-text-primary)           !important; }
.swal2-html-container { color: var(--bmn-text-secondary)         !important; }
</style>

<?php if ($this->session->flashdata('success')): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({ icon:'success', title:'Berhasil!', text:'<?= $this->session->flashdata('success') ?>', confirmButtonColor:'#2563eb', timer:2500, timerProgressBar:true });
});
</script>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({ icon:'error', title:'Gagal!', text:'<?= $this->session->flashdata('error') ?>', confirmButtonColor:'#ef4444' });
});
</script>
<?php endif; ?>


<!-- PAGE HEADER -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold bmn-page-title">Master Peminjaman BMN</h1>
        <p class="text-sm bmn-page-sub mt-0.5">BPS Provinsi Riau – Daftar Barang Milik Negara yang dapat dipinjam</p>
    </div>
    <button onclick="bmnOpenModal('modalTambah')"
            class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-500
                   text-white text-sm font-semibold rounded-lg transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah dari Perangkat IT
    </button>
</div>

<!-- STAT CARDS -->
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bmn-stat-card rounded-xl p-5 border flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bmn-stat-icon-t flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <div class="text-3xl font-bold bmn-stat-num"><?= $stat_tersedia ?></div>
            <div class="text-xs bmn-stat-sub-lbl uppercase tracking-wider font-semibold mt-0.5">Tersedia</div>
        </div>
    </div>
    <div class="bmn-stat-card rounded-xl p-5 border flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bmn-stat-icon-d flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        </div>
        <div>
            <div class="text-3xl font-bold bmn-stat-num"><?= $stat_dipinjam ?></div>
            <div class="text-xs bmn-stat-sub-lbl uppercase tracking-wider font-semibold mt-0.5">Dipinjam</div>
        </div>
    </div>
    <div class="bmn-stat-card rounded-xl p-5 border flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bmn-stat-icon-p flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div>
            <div class="text-3xl font-bold bmn-stat-num"><?= $stat_perbaikan ?></div>
            <div class="text-xs bmn-stat-sub-lbl uppercase tracking-wider font-semibold mt-0.5">Dalam Perbaikan</div>
        </div>
    </div>
</div>

<!-- FILTER BAR -->
<div class="flex flex-wrap items-center gap-3 mb-4">
    <div class="relative flex-1 min-w-[220px]">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 pointer-events-none bmn-page-sub"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
        </svg>
        <input type="text" id="searchInput" oninput="filterTabel()"
               placeholder="Cari nama, merek, kode barang, atau lokasi..."
               class="w-full pl-9 pr-3 py-2 rounded-lg border text-sm bmn-input
                      focus:ring-2 focus:ring-purple-500 focus:outline-none">
    </div>
    <select id="filterStatus" onchange="filterTabel()"
            class="px-3 py-2 rounded-lg border text-sm bmn-input
                   focus:ring-2 focus:ring-purple-500 focus:outline-none">
        <option value="">Semua Status</option>
        <option value="Tersedia">Tersedia</option>
        <option value="Dipinjam">Dipinjam</option>
        <option value="Dalam Perbaikan">Dalam Perbaikan</option>
    </select>
    <select id="filterKondisi" onchange="filterTabel()"
            class="px-3 py-2 rounded-lg border text-sm bmn-input
                   focus:ring-2 focus:ring-purple-500 focus:outline-none">
        <option value="">Semua Kondisi</option>
        <option value="Baik">Baik</option>
        <option value="Rusak Ringan">Rusak Ringan</option>
        <option value="Rusak Berat">Rusak Berat</option>
    </select>
    <button onclick="resetFilter()"
            class="px-4 py-2 rounded-lg border text-sm bmn-input bmn-btn-batal transition">
        Reset
    </button>
</div>

<!-- TABLE CARD -->
<div class="bmn-table-wrap rounded-xl border overflow-hidden">
    <div class="px-4 py-3 border-b bmn-table-head flex items-center justify-between">
        <h2 class="text-sm font-bold bmn-table-head-title">Daftar Barang BMN yang Dapat Dipinjam</h2>
        <span id="jumlahTampil" class="text-xs bmn-table-head-count"></span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm" style="min-width:860px">
            <thead>
                <tr class="border-b bmn-th text-xs uppercase tracking-wider">
                    <th class="px-4 py-3 text-left font-semibold w-10">No</th>
                    <th class="px-4 py-3 text-left font-semibold">Nama Barang</th>
                    <th class="px-4 py-3 text-left font-semibold">Kode / NUP</th>
                    <th class="px-4 py-3 text-left font-semibold">Spesifikasi</th>
                    <th class="px-4 py-3 text-left font-semibold">Kondisi</th>
                    <th class="px-4 py-3 text-left font-semibold">Lokasi</th>
                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                    <th class="px-4 py-3 text-center font-semibold w-24">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y bmn-tr">
            <?php if (!empty($daftar_bmn)):
                foreach ($daftar_bmn as $i => $b):

                $badgeStatus = [
                    'Tersedia'        => 'bmn-badge-tersedia',
                    'Dipinjam'        => 'bmn-badge-dipinjam',
                    'Dalam Perbaikan' => 'bmn-badge-perbaikan',
                ];
                $badgeKondisi = [
                    'Baik'         => 'bmn-badge-baik',
                    'Rusak Ringan' => 'bmn-badge-ringan',
                    'Rusak Berat'  => 'bmn-badge-berat',
                ];
                $stCls = $badgeStatus[$b['status']]   ?? 'bmn-badge-tersedia';
                $kdCls = $badgeKondisi[$b['kondisi']] ?? 'bmn-badge-baik';
            ?>
            <tr class="bmn-tr transition bmn-row"
                data-search="<?= strtolower(($b['nama_barang']??'').' '.($b['merek']??'').' '.($b['kode_barang']??'').' '.($b['lokasi']??'')) ?>"
                data-status="<?= htmlspecialchars($b['status']) ?>"
                data-kondisi="<?= htmlspecialchars($b['kondisi']) ?>">
                <td class="px-4 py-3.5 bmn-td-no text-xs bmn-row-no"><?= $i + 1 ?></td>
                <td class="px-4 py-3.5">
                    <div class="font-semibold bmn-td-name text-sm"><?= htmlspecialchars($b['nama_barang']) ?></div>
                    <div class="text-xs bmn-td-sub mt-0.5"><?= htmlspecialchars($b['merek'] ?? '–') ?></div>
                </td>
                <td class="px-4 py-3.5">
                    <span class="bmn-kode-chip font-mono text-xs px-1.5 py-0.5 rounded border block w-fit">
                        <?= htmlspecialchars($b['kode_barang'] ?? '–') ?>
                    </span>
                    <?php if (!empty($b['nup'])): ?>
                    <span class="font-mono text-xs bmn-nup-text mt-0.5 block">NUP: <?= $b['nup'] ?></span>
                    <?php endif; ?>
                </td>
                <td class="px-4 py-3.5 max-w-xs">
                    <div class="bmn-td-spek text-xs truncate" title="<?= htmlspecialchars($b['spesifikasi'] ?? '') ?>">
                        <?= htmlspecialchars($b['spesifikasi'] ?? '–') ?>
                    </div>
                </td>
                <td class="px-4 py-3.5">
                    <span class="px-2.5 py-1 rounded text-xs font-semibold <?= $kdCls ?>">
                        <?= htmlspecialchars($b['kondisi']) ?>
                    </span>
                </td>
                <td class="px-4 py-3.5 bmn-td-lokasi text-xs"><?= htmlspecialchars($b['lokasi'] ?? '–') ?></td>
                <td class="px-4 py-3.5">
                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full <?= $stCls ?>">
                        <?= htmlspecialchars($b['status']) ?>
                    </span>
                </td>
                <td class="px-4 py-3.5">
                    <div class="flex items-center justify-center gap-1.5">
                        <button onclick='bmnOpenEdit(<?= json_encode($b) ?>)'
                                class="bmn-btn-edit p-1.5 rounded-lg border transition" title="Edit">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <?php if ($b['status'] === 'Tersedia'): ?>
                        <button onclick="bmnHapus(<?= $b['id'] ?>, '<?= addslashes($b['nama_barang']) ?>')"
                                class="bmn-btn-del p-1.5 rounded-lg border transition" title="Hapus dari Daftar">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                        <?php else: ?>
                        <span class="p-1.5 bmn-btn-dis" title="Tidak bisa dihapus saat dipinjam">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 15v2m0 0v2m0-2h2m-2 0H10m2-10a4 4 0 100 8 4 4 0 000-8z"/>
                            </svg>
                        </span>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php endforeach;
            else: ?>
            <tr>
                <td colspan="8" class="px-5 py-16 text-center">
                    <svg class="w-12 h-12 mx-auto mb-3 bmn-page-sub opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <p class="bmn-page-sub text-sm font-medium">Belum ada data barang BMN</p>
                    <p class="bmn-table-head-count text-xs mt-1">Klik tombol "Tambah dari Perangkat IT" untuk menambahkan</p>
                </td>
            </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<!-- ===== MODAL TAMBAH DARI PERANGKAT IT ===== -->
<div id="modalTambah" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/60">
    <div class="bmn-modal-body rounded-xl shadow-2xl w-full max-w-xl border
                max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between px-5 py-4 border-b bmn-modal-header
                    sticky top-0 rounded-t-xl z-10">
            <div>
                <h3 class="text-base font-bold bmn-modal-title">Tambah ke Master BMN</h3>
                <p class="text-xs bmn-modal-sub mt-0.5">Pilih perangkat dari inventaris IT</p>
            </div>
            <button onclick="bmnCloseModal('modalTambah')" class="bmn-modal-close transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form action="<?= base_url('master_bmn/simpan') ?>" method="POST">
            <div class="p-5 space-y-4">

                <!-- Tom Select Perangkat -->
                <div>
                    <label class="block text-xs font-semibold bmn-label mb-1.5">
                        Cari &amp; Pilih Perangkat IT <span class="text-red-400">*</span>
                    </label>
                    <div class="flex items-center gap-2 mb-2 px-2.5 py-1.5 bmn-hint-box border rounded-lg">
                        <svg class="w-3 h-3 bmn-hint-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <span class="text-xs bmn-hint-text">Ketik nama, merek, atau kode barang untuk mencari dari ribuan item</span>
                    </div>
                    <select name="perangkat_id" id="selectPerangkat" required>
                        <option value="">── Ketik untuk mencari perangkat ──</option>
                        <?php foreach ($daftar_perangkat as $p):
                            if (empty($p['nama_barang'])) continue;
                            $sudah = in_array(($p['kode_barang'] ?? '') . '|' . ($p['nup'] ?? ''), $sudah_terdaftar);
                            $label = htmlspecialchars($p['nama_barang']);
                            if (!empty($p['merk'])) $label .= ' – ' . htmlspecialchars($p['merk']);
                            $kode  = (!empty($p['kode_barang']) ? $p['kode_barang'] : '–') . '/' . (!empty($p['nup']) ? $p['nup'] : '–');
                        ?>
                        <option value="<?= $p['id'] ?>"
                                data-nama="<?= htmlspecialchars($p['nama_barang']) ?>"
                                data-merek="<?= htmlspecialchars($p['merk'] ?? '') ?>"
                                data-kode="<?= htmlspecialchars($p['kode_barang'] ?? '') ?>"
                                data-nup="<?= htmlspecialchars($p['nup'] ?? '') ?>"
                                data-search="<?= strtolower(($p['nama_barang']??'').' '.($p['merk']??'').' '.($p['kode_barang']??'').' '.($p['nup']??'')) ?>"
                                data-sudah="<?= $sudah ? '1' : '0' ?>"
                                <?= $sudah ? 'disabled' : '' ?>>
                            <?= $label ?> (<?= htmlspecialchars($kode) ?>)<?= $sudah ? ' [sudah terdaftar]' : '' ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Preview Perangkat -->
                <div id="previewBox" class="hidden bmn-preview-box rounded-lg border p-3 space-y-1.5">
                    <div class="flex items-center gap-2 mb-2 pb-2 bmn-preview-header border-b">
                        <div class="w-6 h-6 rounded-md bmn-preview-icon-wrap flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 bmn-preview-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
                            </svg>
                        </div>
                        <div class="text-xs font-semibold bmn-preview-label-text uppercase tracking-wider">Detail Perangkat Terpilih</div>
                    </div>
                    <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                        <div>
                            <div class="text-xs bmn-preview-field-lbl mb-0.5">Nama Barang</div>
                            <div id="prev_nama" class="text-xs font-semibold bmn-preview-field-val"></div>
                        </div>
                        <div>
                            <div class="text-xs bmn-preview-field-lbl mb-0.5">Merek</div>
                            <div id="prev_merek" class="text-xs bmn-preview-field-val"></div>
                        </div>
                        <div>
                            <div class="text-xs bmn-preview-field-lbl mb-0.5">Kode / NUP</div>
                            <div id="prev_kode" class="text-xs font-mono bmn-preview-kode-chip px-1.5 py-0.5 rounded w-fit"></div>
                        </div>
                        <div>
                            <div class="text-xs bmn-preview-field-lbl mb-0.5">Kondisi</div>
                            <div id="prev_kondisi" class="text-xs bmn-preview-field-val"></div>
                        </div>
                        <div>
                            <div class="text-xs bmn-preview-field-lbl mb-0.5">Lokasi</div>
                            <div id="prev_lokasi" class="text-xs bmn-preview-field-val"></div>
                        </div>
                        <div>
                            <div class="text-xs bmn-preview-field-lbl mb-0.5">Serial</div>
                            <div id="prev_serial" class="text-xs font-mono bmn-preview-field-val"></div>
                        </div>
                    </div>
                    <div class="pt-1.5 bmn-preview-divider border-t">
                        <div class="text-xs bmn-preview-field-lbl mb-0.5">Spesifikasi</div>
                        <div id="prev_spek" class="text-xs bmn-preview-spek leading-relaxed"></div>
                    </div>
                </div>

                <!-- Spesifikasi override -->
                <div>
                    <label class="block text-xs font-semibold bmn-label mb-1.5">
                        Spesifikasi Tambahan
                        <span class="bmn-modal-sub font-normal">(opsional, akan otomatis dari data perangkat)</span>
                    </label>
                    <input type="text" name="spesifikasi" id="inp_spesifikasi"
                           placeholder="Kosongkan untuk otomatis dari data IT..."
                           class="w-full px-3 py-2 rounded-lg border text-sm bmn-input
                                  focus:ring-2 focus:ring-purple-500 focus:outline-none">
                </div>

                <!-- Keterangan -->
                <div>
                    <label class="block text-xs font-semibold bmn-label mb-1.5">Keterangan</label>
                    <textarea name="keterangan" rows="2" placeholder="Catatan tambahan..."
                              class="w-full px-3 py-2 rounded-lg border text-sm bmn-input
                                     focus:ring-2 focus:ring-purple-500 focus:outline-none resize-none"></textarea>
                </div>

            </div>
            <div class="flex gap-3 px-5 pb-5">
                <button type="button" onclick="bmnCloseModal('modalTambah')"
                        class="flex-1 px-4 py-2.5 border rounded-lg bmn-btn-batal transition text-sm">Batal</button>
                <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-purple-600 hover:bg-purple-500 text-white
                               rounded-lg transition font-semibold text-sm">
                    Tambahkan ke Master BMN
                </button>
            </div>
        </form>
    </div>
</div>


<!-- ===== MODAL EDIT ===== -->
<div id="modalEdit" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/60">
    <div class="bmn-modal-body rounded-xl shadow-2xl w-full max-w-md border
                max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between px-5 py-4 border-b bmn-modal-header
                    sticky top-0 rounded-t-xl z-10">
            <h3 class="text-base font-bold bmn-modal-title">Edit Data BMN</h3>
            <button onclick="bmnCloseModal('modalEdit')" class="bmn-modal-close transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form action="<?= base_url('master_bmn/update') ?>" method="POST">
            <input type="hidden" name="id" id="edit_id">
            <div class="p-5 space-y-3">
                <div class="bmn-modal-kode-box p-2.5 rounded-lg border">
                    <div class="text-xs bmn-modal-kode-lbl mb-0.5">Kode Barang / NUP</div>
                    <div id="edit_kode_display" class="text-xs font-mono bmn-modal-kode-val"></div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold bmn-label mb-1">Nama Barang</label>
                        <input type="text" name="nama_barang" id="edit_nama"
                               class="w-full px-3 py-2 rounded-lg border text-sm bmn-input
                                      focus:ring-2 focus:ring-purple-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold bmn-label mb-1">Merek</label>
                        <input type="text" name="merek" id="edit_merek"
                               class="w-full px-3 py-2 rounded-lg border text-sm bmn-input
                                      focus:ring-2 focus:ring-purple-500 focus:outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold bmn-label mb-1">Spesifikasi</label>
                    <input type="text" name="spesifikasi" id="edit_spek"
                           class="w-full px-3 py-2 rounded-lg border text-sm bmn-input
                                  focus:ring-2 focus:ring-purple-500 focus:outline-none">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold bmn-label mb-1">Kondisi</label>
                        <select name="kondisi" id="edit_kondisi"
                                class="w-full px-3 py-2 rounded-lg border text-sm bmn-input
                                       focus:ring-2 focus:ring-purple-500 focus:outline-none">
                            <option value="Baik">Baik</option>
                            <option value="Rusak Ringan">Rusak Ringan</option>
                            <option value="Rusak Berat">Rusak Berat</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold bmn-label mb-1">Status</label>
                        <select name="status" id="edit_status"
                                class="w-full px-3 py-2 rounded-lg border text-sm bmn-input
                                       focus:ring-2 focus:ring-purple-500 focus:outline-none">
                            <option value="Tersedia">Tersedia</option>
                            <option value="Dipinjam">Dipinjam</option>
                            <option value="Dalam Perbaikan">Dalam Perbaikan</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold bmn-label mb-1">Lokasi</label>
                    <input type="text" name="lokasi" id="edit_lokasi"
                           class="w-full px-3 py-2 rounded-lg border text-sm bmn-input
                                  focus:ring-2 focus:ring-purple-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold bmn-label mb-1">Keterangan</label>
                    <textarea name="keterangan" id="edit_keterangan" rows="2"
                              class="w-full px-3 py-2 rounded-lg border text-sm bmn-input
                                     focus:ring-2 focus:ring-purple-500 focus:outline-none resize-none"></textarea>
                </div>
            </div>
            <div class="flex gap-3 px-5 pb-5">
                <button type="button" onclick="bmnCloseModal('modalEdit')"
                        class="flex-1 px-4 py-2.5 border rounded-lg bmn-btn-batal transition text-sm">Batal</button>
                <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-amber-600 hover:bg-amber-500 text-white
                               rounded-lg transition font-semibold text-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>


<!-- ===== SCRIPTS ===== -->
<script>
/* ─────────────────────────────────────────────────────────────
   MODAL helpers
   ───────────────────────────────────────────────────────────── */
function bmnOpenModal(id) {
    var el = document.getElementById(id);
    if (el) el.classList.remove('hidden');
}

function bmnCloseModal(id) {
    var el = document.getElementById(id);
    if (el) el.classList.add('hidden');

    if (id === 'modalTambah') {
        if (tomPerangkat) tomPerangkat.clear();
        var previewBox = document.getElementById('previewBox');
        if (previewBox) previewBox.classList.add('hidden');
        var inpSpek = document.getElementById('inp_spesifikasi');
        if (inpSpek) inpSpek.value = '';
    }
}

/* Tutup modal saat klik backdrop */
['modalTambah', 'modalEdit'].forEach(function(id) {
    var el = document.getElementById(id);
    if (el) {
        el.addEventListener('click', function(e) {
            if (e.target === el) bmnCloseModal(id);
        });
    }
});

/* Tutup modal dengan Escape */
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        ['modalTambah', 'modalEdit'].forEach(function(id) { bmnCloseModal(id); });
    }
});


/* ─────────────────────────────────────────────────────────────
   TOM SELECT
   ───────────────────────────────────────────────────────────── */
var tomPerangkat = null;

window.addEventListener('DOMContentLoaded', function() {

    tomPerangkat = new TomSelect('#selectPerangkat', {
        placeholder: 'Ketik nama, merek, atau kode barang...',
        allowEmptyOption: true,
        maxOptions: 80,
        searchField: ['text'],
        sortField: { field: 'text', direction: 'asc' },
        render: {
            option: function(data, escape) {
                if (!data.value) {
                    return '<div class="py-2 px-3 text-xs italic" style="color:var(--bmn-placeholder)">── Ketik untuk mencari perangkat ──</div>';
                }
                var el    = document.querySelector('#selectPerangkat option[value="' + data.value + '"]');
                var sudah = el ? el.getAttribute('data-sudah') === '1' : false;
                var nama  = el ? (el.getAttribute('data-nama')  || '') : data.text;
                var merek = el ? (el.getAttribute('data-merek') || '') : '';
                var kode  = el
                    ? ((el.getAttribute('data-kode') || '–') + '/' + (el.getAttribute('data-nup') || '–'))
                    : '';
                return '<div class="flex items-start justify-between gap-2 py-1">' +
                    '<div class="min-w-0">' +
                        '<div style="font-weight:600;font-size:11px;line-height:1.4;color:var(--bmn-text-primary)">' +
                            escape(nama) +
                            (merek ? ' <span style="color:var(--bmn-text-muted);font-weight:400;">– ' + escape(merek) + '</span>' : '') +
                        '</div>' +
                        (kode ? '<div style="font-family:monospace;font-size:10px;color:var(--bmn-text-muted);margin-top:2px">' + escape(kode) + '</div>' : '') +
                    '</div>' +
                    (sudah ? '<span class="opt-sudah flex-shrink-0 mt-0.5">✓ terdaftar</span>' : '') +
                '</div>';
            },
            item: function(data, escape) {
                var el    = document.querySelector('#selectPerangkat option[value="' + data.value + '"]');
                var nama  = el ? (el.getAttribute('data-nama')  || data.text) : data.text;
                var merek = el ? (el.getAttribute('data-merek') || '') : '';
                var kode  = el
                    ? ((el.getAttribute('data-kode') || '–') + '/' + (el.getAttribute('data-nup') || '–'))
                    : '';
                return '<div>' +
                    '<span style="font-weight:600;color:var(--bmn-text-primary)">' + escape(nama) + '</span>' +
                    (merek ? '<span style="color:var(--bmn-text-muted);margin-left:4px">– ' + escape(merek) + '</span>' : '') +
                    (kode  ? '<span class="opt-kode">(' + escape(kode) + ')</span>' : '') +
                '</div>';
            },
            no_results: function(data, escape) {
                return '<div class="no-results">Tidak ada perangkat yang cocok dengan "<strong>' +
                    escape(data.input) + '</strong>"</div>';
            }
        },
        onChange: function(value) {
            bmnLoadPreview(value);
        }
    });

    /* Hitung jumlah baris tabel */
    var total = document.querySelectorAll('.bmn-row').length;
    var elJml = document.getElementById('jumlahTampil');
    if (elJml) elJml.textContent = total + ' data';
});


/* ─────────────────────────────────────────────────────────────
   LOAD PREVIEW via AJAX
   ───────────────────────────────────────────────────────────── */
async function bmnLoadPreview(id) {
    var box = document.getElementById('previewBox');
    if (!id) { if (box) box.classList.add('hidden'); return; }
    try {
        var res  = await fetch('<?= base_url('master_bmn/get_perangkat_detail') ?>?id=' + id);
        var data = await res.json();
        if (data.status !== 'success') { if (box) box.classList.add('hidden'); return; }

        document.getElementById('prev_nama').textContent    = data.nama_barang   || '–';
        document.getElementById('prev_merek').textContent   = data.merek         || '–';
        document.getElementById('prev_kode').textContent    = (data.kode_barang  || '–') + ' / NUP ' + (data.nup || '–');
        document.getElementById('prev_kondisi').textContent = data.kondisi       || '–';
        document.getElementById('prev_lokasi').textContent  = data.lokasi        || '–';
        document.getElementById('prev_serial').textContent  = data.serial_number || '–';
        document.getElementById('prev_spek').textContent    = data.spesifikasi   || '–';

        var spekInp = document.getElementById('inp_spesifikasi');
        if (spekInp && !spekInp.value) spekInp.value = data.spesifikasi || '';

        if (box) box.classList.remove('hidden');
    } catch (e) {
        if (box) box.classList.add('hidden');
    }
}


/* ─────────────────────────────────────────────────────────────
   OPEN EDIT MODAL
   ───────────────────────────────────────────────────────────── */
function bmnOpenEdit(b) {
    var sv = function(id, v) {
        var e = document.getElementById(id);
        if (e) e.value = (v !== null && v !== undefined) ? v : '';
    };
    sv('edit_id',         b.id);
    sv('edit_nama',       b.nama_barang);
    sv('edit_merek',      b.merek);
    sv('edit_spek',       b.spesifikasi);
    sv('edit_kondisi',    b.kondisi);
    sv('edit_status',     b.status);
    sv('edit_lokasi',     b.lokasi);
    sv('edit_keterangan', b.keterangan);

    var kodeEl = document.getElementById('edit_kode_display');
    if (kodeEl) kodeEl.textContent = (b.kode_barang || '–') + ' / NUP ' + (b.nup || '–');

    bmnOpenModal('modalEdit');
}


/* ─────────────────────────────────────────────────────────────
   HAPUS
   ───────────────────────────────────────────────────────────── */
function bmnHapus(id, nama) {
    Swal.fire({
        title: 'Hapus dari Daftar BMN?',
        text: '"' + nama + '" akan dihapus dari daftar peminjaman.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        reverseButtons: true
    }).then(function(r) {
        if (r.isConfirmed) {
            window.location.href = '<?= base_url('master_bmn/hapus/') ?>' + id;
        }
    });
}


/* ─────────────────────────────────────────────────────────────
   FILTER TABEL
   ───────────────────────────────────────────────────────────── */
function filterTabel() {
    var kw = (document.getElementById('searchInput').value || '').toLowerCase();
    var st = document.getElementById('filterStatus').value;
    var kd = document.getElementById('filterKondisi').value;
    var v  = 0;

    document.querySelectorAll('.bmn-row').forEach(function(row, idx) {
        var ok = row.dataset.search.includes(kw)
              && (!st || row.dataset.status  === st)
              && (!kd || row.dataset.kondisi === kd);
        row.style.display = ok ? '' : 'none';
        if (ok) {
            v++;
            var noCell = row.querySelector('.bmn-row-no');
            if (noCell) noCell.textContent = v;
        }
    });

    var total = document.querySelectorAll('.bmn-row').length;
    var elJml = document.getElementById('jumlahTampil');
    if (elJml) elJml.textContent = v < total
        ? 'Menampilkan ' + v + ' dari ' + total + ' data'
        : total + ' data';

    /* Tampilkan baris "tidak ada hasil" jika perlu */
    var emptyRow = document.getElementById('bmnNoResult');
    if (v === 0) {
        if (!emptyRow) {
            var tbody = document.querySelector('#tabelBmn tbody') || document.querySelector('tbody');
            var tr = document.createElement('tr');
            tr.id = 'bmnNoResult';
            tr.innerHTML = '<td colspan="8" class="px-5 py-12 text-center">' +
                '<svg class="w-10 h-10 mx-auto mb-3 bmn-page-sub opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>' +
                '</svg><p class="bmn-page-sub text-sm">Tidak ada data yang cocok</p></td>';
            if (tbody) tbody.appendChild(tr);
        }
    } else {
        if (emptyRow) emptyRow.remove();
    }
}

function resetFilter() {
    ['searchInput', 'filterStatus', 'filterKondisi'].forEach(function(id) {
        var e = document.getElementById(id);
        if (e) e.value = '';
    });
    filterTabel();
}

document.getElementById('searchInput')  ?.addEventListener('input',  filterTabel);
document.getElementById('filterStatus') ?.addEventListener('change', filterTabel);
document.getElementById('filterKondisi')?.addEventListener('change', filterTabel);
</script>