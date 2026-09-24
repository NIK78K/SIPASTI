<?php
// application/views/ruang_rapat/index.php
$active_tab = $this->input->get('tab') ?: 'ruangan';

// Pagination vars (passed from controller)
$per_page    = (int)($per_page    ?? 10);
$current_page= (int)($current_page ?? 1);
$total_rows  = (int)($total_rows  ?? 0);
$total_pages = max(1, ceil($total_rows / $per_page));
?>

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

<!-- ===== THEME VARIABLES ===== -->
<style>
/* --- Dark mode (default) --- */
:root,
html:not(.light) {
    --rr-bg-page:           #0f1623;
    --rr-bg-card:           #1a2233;
    --rr-bg-table:          #1e2a3a;
    --rr-bg-input:          #0f1623;
    --rr-bg-row-hover:      rgba(255,255,255,0.04);
    --rr-border:            rgba(255,255,255,0.10);
    --rr-border-5:          rgba(255,255,255,0.05);
    --rr-text-primary:      #ffffff;
    --rr-text-secondary:    #d1d5db;
    --rr-text-muted:        #6b7280;
    --rr-text-label:        #9ca3af;
    --rr-text-sub:          #4b5563;
    --rr-th-color:          #6b7280;
    --rr-placeholder:       #4b5563;
    --rr-stat-bg:           #1e2a3a;
    --rr-badge-tunggu-bg:   rgba(234,179,8,0.15);
    --rr-badge-tunggu-text: #fbbf24;
    --rr-badge-tunggu-ring: rgba(234,179,8,0.30);
    --rr-badge-diset-bg:    rgba(16,185,129,0.15);
    --rr-badge-diset-text:  #34d399;
    --rr-badge-diset-ring:  rgba(16,185,129,0.30);
    --rr-badge-tolak-bg:    rgba(239,68,68,0.15);
    --rr-badge-tolak-text:  #f87171;
    --rr-badge-tolak-ring:  rgba(239,68,68,0.30);
    --rr-badge-seles-bg:    rgba(100,116,139,0.15);
    --rr-badge-seles-text:  #94a3b8;
    --rr-badge-seles-ring:  rgba(100,116,139,0.30);
    --rr-badge-live-bg:     rgba(59,130,246,0.20);
    --rr-badge-live-text:   #93c5fd;
    --rr-done-bg:           rgba(20,184,166,0.15);
    --rr-done-text:         #2dd4bf;
    --rr-done-ring:         rgba(20,184,166,0.30);
    --rr-belum-text:        #4b5563;
    --rr-snack-bg:          rgba(249,115,22,0.15);
    --rr-snack-text:        #fb923c;
    --rr-snack-ring:        rgba(249,115,22,0.30);
    --rr-jadwal-bg:         rgba(255,255,255,0.03);
    --rr-jadwal-border:     rgba(255,255,255,0.05);
    --rr-jadwal-live-bg:    rgba(59,130,246,0.10);
    --rr-jadwal-live-border:rgba(59,130,246,0.20);
    --rr-tersedia-dot:      #34d399;
    --rr-terpakai-dot:      #f87171;
    --rr-input-bg:          #1e2a3a;
    --rr-input-border:      rgba(255,255,255,0.10);
    --rr-input-text:        #d1d5db;
    --rr-btn-setujui-bg:    rgba(16,185,129,0.10);
    --rr-btn-setujui-text:  #34d399;
    --rr-btn-setujui-border:rgba(16,185,129,0.20);
    --rr-btn-tolak-bg:      rgba(239,68,68,0.10);
    --rr-btn-tolak-text:    #f87171;
    --rr-btn-tolak-border:  rgba(239,68,68,0.20);
    --rr-btn-done-bg:       rgba(20,184,166,0.10);
    --rr-btn-done-text:     #2dd4bf;
    --rr-btn-done-border:   rgba(20,184,166,0.20);
    --rr-btn-edit-bg:       rgba(245,158,11,0.10);
    --rr-btn-edit-text:     #fbbf24;
    --rr-btn-edit-border:   rgba(245,158,11,0.20);
    --rr-btn-del-bg:        rgba(239,68,68,0.10);
    --rr-btn-del-text:      #f87171;
    --rr-btn-del-border:    rgba(239,68,68,0.20);
    --rr-modal-bg:          #1a2233;
    --rr-modal-input-bg:    #0f1623;
    --rr-modal-border:      rgba(255,255,255,0.10);
    --rr-modal-title:       #ffffff;
    --rr-modal-label:       #9ca3af;
    --rr-modal-close:       #6b7280;
    --rr-swal-bg:           #1a2233;
}

/* --- Light mode --- */
html.light {
    --rr-bg-page:           #f1f5f9;
    --rr-bg-card:           #ffffff;
    --rr-bg-table:          #ffffff;
    --rr-bg-input:          #f8fafc;
    --rr-bg-row-hover:      rgba(0,0,0,0.025);
    --rr-border:            rgba(0,0,0,0.10);
    --rr-border-5:          rgba(0,0,0,0.05);
    --rr-text-primary:      #0f172a;
    --rr-text-secondary:    #334155;
    --rr-text-muted:        #94a3b8;
    --rr-text-label:        #64748b;
    --rr-text-sub:          #94a3b8;
    --rr-th-color:          #64748b;
    --rr-placeholder:       #94a3b8;
    --rr-stat-bg:           #f8fafc;
    --rr-badge-tunggu-bg:   rgba(217,119,6,0.10);
    --rr-badge-tunggu-text: #d97706;
    --rr-badge-tunggu-ring: rgba(217,119,6,0.25);
    --rr-badge-diset-bg:    rgba(5,150,105,0.10);
    --rr-badge-diset-text:  #059669;
    --rr-badge-diset-ring:  rgba(5,150,105,0.25);
    --rr-badge-tolak-bg:    rgba(220,38,38,0.10);
    --rr-badge-tolak-text:  #dc2626;
    --rr-badge-tolak-ring:  rgba(220,38,38,0.25);
    --rr-badge-seles-bg:    rgba(100,116,139,0.10);
    --rr-badge-seles-text:  #475569;
    --rr-badge-seles-ring:  rgba(100,116,139,0.20);
    --rr-badge-live-bg:     rgba(37,99,235,0.10);
    --rr-badge-live-text:   #2563eb;
    --rr-done-bg:           rgba(13,148,136,0.10);
    --rr-done-text:         #0d9488;
    --rr-done-ring:         rgba(13,148,136,0.25);
    --rr-belum-text:        #94a3b8;
    --rr-snack-bg:          rgba(234,88,12,0.10);
    --rr-snack-text:        #ea580c;
    --rr-snack-ring:        rgba(234,88,12,0.25);
    --rr-jadwal-bg:         rgba(0,0,0,0.02);
    --rr-jadwal-border:     rgba(0,0,0,0.06);
    --rr-jadwal-live-bg:    rgba(37,99,235,0.06);
    --rr-jadwal-live-border:rgba(37,99,235,0.15);
    --rr-tersedia-dot:      #059669;
    --rr-terpakai-dot:      #dc2626;
    --rr-input-bg:          #f8fafc;
    --rr-input-border:      rgba(0,0,0,0.10);
    --rr-input-text:        #334155;
    --rr-btn-setujui-bg:    rgba(5,150,105,0.08);
    --rr-btn-setujui-text:  #059669;
    --rr-btn-setujui-border:rgba(5,150,105,0.20);
    --rr-btn-tolak-bg:      rgba(220,38,38,0.08);
    --rr-btn-tolak-text:    #dc2626;
    --rr-btn-tolak-border:  rgba(220,38,38,0.20);
    --rr-btn-done-bg:       rgba(13,148,136,0.08);
    --rr-btn-done-text:     #0d9488;
    --rr-btn-done-border:   rgba(13,148,136,0.20);
    --rr-btn-edit-bg:       rgba(217,119,6,0.08);
    --rr-btn-edit-text:     #b45309;
    --rr-btn-edit-border:   rgba(217,119,6,0.20);
    --rr-btn-del-bg:        rgba(220,38,38,0.08);
    --rr-btn-del-text:      #dc2626;
    --rr-btn-del-border:    rgba(220,38,38,0.20);
    --rr-modal-bg:          #ffffff;
    --rr-modal-input-bg:    #f8fafc;
    --rr-modal-border:      rgba(0,0,0,0.10);
    --rr-modal-title:       #0f172a;
    --rr-modal-label:       #64748b;
    --rr-modal-close:       #94a3b8;
    --rr-swal-bg:           #ffffff;
}

.rr-page-title      { color: var(--rr-text-primary); }
.rr-page-sub        { color: var(--rr-text-label); }
.rr-card            { background-color: var(--rr-bg-table); border-color: var(--rr-border-5); }
.rr-card-header     { border-color: var(--rr-border-5); }
.rr-card-title      { color: var(--rr-text-primary); }
.rr-card-sub        { color: var(--rr-text-muted); }
.rr-stat-card       { background-color: var(--rr-stat-bg); border-color: var(--rr-border-5); }
.rr-th              { color: var(--rr-th-color); border-color: var(--rr-border); }
.rr-tr              { border-color: var(--rr-border-5); }
.rr-tr:hover        { background-color: var(--rr-bg-row-hover); }
.rr-td-no           { color: var(--rr-text-muted); }
.rr-td-main         { color: var(--rr-text-primary); }
.rr-td-sub          { color: var(--rr-text-sub); }
.rr-td-sec          { color: var(--rr-text-secondary); }
.rr-td-muted        { color: var(--rr-text-muted); }
.rr-badge-tunggu    { background-color: var(--rr-badge-tunggu-bg); color: var(--rr-badge-tunggu-text); box-shadow: 0 0 0 1px var(--rr-badge-tunggu-ring); }
.rr-badge-diset     { background-color: var(--rr-badge-diset-bg);  color: var(--rr-badge-diset-text);  box-shadow: 0 0 0 1px var(--rr-badge-diset-ring); }
.rr-badge-tolak     { background-color: var(--rr-badge-tolak-bg);  color: var(--rr-badge-tolak-text);  box-shadow: 0 0 0 1px var(--rr-badge-tolak-ring); }
.rr-badge-seles     { background-color: var(--rr-badge-seles-bg);  color: var(--rr-badge-seles-text);  box-shadow: 0 0 0 1px var(--rr-badge-seles-ring); }
.rr-badge-live      { background-color: var(--rr-badge-live-bg);   color: var(--rr-badge-live-text); }
.rr-done-badge      { background-color: var(--rr-done-bg); color: var(--rr-done-text); box-shadow: 0 0 0 1px var(--rr-done-ring); white-space: nowrap; display: inline-flex; align-items: center; gap: 3px; }
.rr-belum-text      { color: var(--rr-belum-text); }
.rr-snack-badge     { background-color: var(--rr-snack-bg); color: var(--rr-snack-text); box-shadow: 0 0 0 1px var(--rr-snack-ring); }
.rr-jadwal-item     { background-color: var(--rr-jadwal-bg); border-color: var(--rr-jadwal-border); }
.rr-jadwal-live     { background-color: var(--rr-jadwal-live-bg); border-color: var(--rr-jadwal-live-border); }
.rr-jadwal-sep      { background-color: var(--rr-border); }
.rr-jadwal-time     { color: var(--rr-text-secondary); }
.rr-jadwal-time-muted { color: var(--rr-text-muted); }
.rr-jadwal-title    { color: var(--rr-text-primary); }
.rr-jadwal-meta     { color: var(--rr-text-label); }
.rr-status-item     { background-color: var(--rr-jadwal-bg); border-color: var(--rr-jadwal-border); }
.rr-status-name     { color: var(--rr-text-primary); }
.rr-status-sub      { color: var(--rr-text-muted); }
.rr-filter-input    { background-color: var(--rr-input-bg); border-color: var(--rr-input-border); color: var(--rr-input-text); }
.rr-filter-input::placeholder { color: var(--rr-placeholder); }
.rr-filter-input:focus { border-color: #3b82f6; }
.rr-filter-reset    { background-color: var(--rr-input-bg); border-color: var(--rr-input-border); color: var(--rr-text-label); }
.rr-filter-reset:hover { color: var(--rr-text-primary); background-color: var(--rr-bg-row-hover); }
.rr-btn-setujui     { background-color: var(--rr-btn-setujui-bg); color: var(--rr-btn-setujui-text); border-color: var(--rr-btn-setujui-border); }
.rr-btn-setujui:hover { opacity: .8; }
.rr-btn-tolak       { background-color: var(--rr-btn-tolak-bg);   color: var(--rr-btn-tolak-text);   border-color: var(--rr-btn-tolak-border); }
.rr-btn-tolak:hover { opacity: .8; }
.rr-btn-done        { background-color: var(--rr-btn-done-bg);    color: var(--rr-btn-done-text);    border-color: var(--rr-btn-done-border); }
.rr-btn-done:hover  { opacity: .8; }
.rr-btn-edit        { background-color: var(--rr-btn-edit-bg);    color: var(--rr-btn-edit-text);    border-color: var(--rr-btn-edit-border); }
.rr-btn-edit:hover  { opacity: .8; }
.rr-btn-del         { background-color: var(--rr-btn-del-bg);     color: var(--rr-btn-del-text);     border-color: var(--rr-btn-del-border); }
.rr-btn-del:hover   { opacity: .8; }
.rr-modal-bg        { background-color: var(--rr-modal-bg); border-color: var(--rr-modal-border); }
.rr-modal-header    { background-color: var(--rr-modal-bg); border-color: var(--rr-modal-border); }
.rr-modal-title     { color: var(--rr-modal-title); }
.rr-modal-close     { color: var(--rr-modal-close); }
.rr-modal-close:hover { color: var(--rr-modal-title); }
.rr-modal-input     { background-color: var(--rr-modal-input-bg); border-color: var(--rr-modal-border); color: var(--rr-modal-title); }
.rr-modal-input::placeholder { color: var(--rr-placeholder); }
.rr-modal-input:focus { border-color: #3b82f6; }
.rr-modal-label     { color: var(--rr-modal-label); }
.rr-modal-cancel    { border-color: var(--rr-modal-border); color: var(--rr-modal-label); }
.rr-modal-cancel:hover { background-color: var(--rr-bg-row-hover); }
.rr-modal-info      { background-color: var(--rr-jadwal-bg); border-color: var(--rr-jadwal-border); color: var(--rr-text-secondary); }
.rr-tab-wrap        { background-color: var(--rr-bg-table); border-color: var(--rr-border); }
.rr-tab-inactive    { color: var(--rr-text-label); }
.rr-tab-inactive:hover { color: var(--rr-text-primary); background-color: var(--rr-bg-row-hover); }
.swal2-popup        { background-color: var(--rr-swal-bg) !important; border: 1.5px solid var(--rr-border) !important; color: var(--rr-text-primary) !important; }
.swal2-title        { color: var(--rr-text-primary) !important; }
.swal2-html-container { color: var(--rr-text-secondary) !important; }
.rr-done-badge, .rr-belum-text { white-space: nowrap; }

/* ── PAGINATION ── */
.rr-pagination      { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px; }
.rr-pagination-info { color: var(--rr-text-muted); font-size:10px; }
.rr-page-btn        {
    display:inline-flex; align-items:center; justify-content:center;
    min-width:26px; height:26px; padding:0 6px;
    border-radius:6px; border:1px solid var(--rr-border);
    background-color: var(--rr-input-bg); color: var(--rr-text-label);
    font-size:10px; font-weight:600; cursor:pointer; transition:all .15s;
    text-decoration:none;
}
.rr-page-btn:hover  { color: var(--rr-text-primary); background-color: var(--rr-bg-row-hover); }
.rr-page-btn.active { background-color:#2563eb; color:#fff; border-color:#2563eb; }
.rr-page-btn:disabled, .rr-page-btn[disabled] { opacity:.4; cursor:default; pointer-events:none; }

/* ── DOK badge ── */
.rr-dok-badge       {
    display:inline-flex; align-items:center; gap:3px;
    padding:2px 6px; border-radius:20px; font-size:9.5px; font-weight:700;
    background:rgba(139,92,246,0.15); color:#a78bfa;
    box-shadow:0 0 0 1px rgba(139,92,246,0.25);
    cursor:pointer; transition:opacity .15s;
}
.rr-dok-badge:hover { opacity:.8; }
.rr-dok-none        { color: var(--rr-text-sub); font-size:10px; }

/* PDF Viewer Modal */
.rr-pdf-overlay {
    position:fixed; inset:0; background:rgba(0,0,0,.75);
    z-index:9999; display:none; align-items:center; justify-content:center; padding:16px;
}
.rr-pdf-overlay.open { display:flex; }
.rr-pdf-card {
    background: var(--rr-modal-bg);
    border:1px solid var(--rr-modal-border);
    border-radius:14px; width:100%; max-width:740px;
    max-height:90vh; display:flex; flex-direction:column; overflow:hidden;
    box-shadow:0 32px 80px rgba(0,0,0,.5);
}
.rr-pdf-header {
    display:flex; align-items:center; justify-content:space-between;
    padding:10px 14px; border-bottom:1px solid var(--rr-modal-border);
    flex-shrink:0;
}
.rr-pdf-title   { color: var(--rr-modal-title); font-size:12px; font-weight:700; }
.rr-pdf-actions { display:flex; align-items:center; gap:6px; }
.rr-pdf-body    { flex:1; overflow:hidden; }
.rr-pdf-body iframe { width:100%; height:100%; border:none; display:block; }
</style>


<div class="lg:pl-0" style="padding-left:0;">

<!-- PAGE HEADER + TAB SWITCHER -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
    <div>
        <h1 class="text-lg font-bold rr-page-title">Manajemen Peminjaman</h1>
        <p class="text-xs rr-page-sub mt-0.5">BPS Provinsi Riau – Kelola jadwal peminjaman fasilitas</p>
    </div>
    <div class="flex items-center gap-1 p-1 rr-tab-wrap rounded-xl border flex-wrap">
        <a href="<?= base_url('ruang_rapat?tab=ruangan') ?>"
           class="flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-semibold transition-all duration-200
                  <?= $active_tab==='ruangan' ? 'bg-blue-600 text-white shadow' : 'rr-tab-inactive' ?>">
            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            Ruang Rapat
        </a>
        <a href="<?= base_url('ruang_rapat?tab=kendaraan') ?>"
           class="flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-semibold transition-all duration-200
                  <?= $active_tab==='kendaraan' ? 'bg-blue-600 text-white shadow' : 'rr-tab-inactive' ?>">
            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
            </svg>
            Kendaraan
        </a>
        <a href="<?= base_url('ruang_rapat?tab=bmn') ?>"
           class="flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-semibold transition-all duration-200
                  <?= $active_tab==='bmn' ? 'bg-purple-600 text-white shadow' : 'rr-tab-inactive' ?>">
            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            BMN
        </a>
    </div>
</div>


<?php
/* ─── helper build pagination URL ─── */
function rrPageUrl($tab, $page, $per_page, $extra = []) {
    $params = array_merge(['tab' => $tab, 'page' => $page, 'per_page' => $per_page], $extra);
    return base_url('ruang_rapat') . '?' . http_build_query($params);
}
function rrPaginationHtml($tab, $current_page, $total_pages, $per_page, $extra = []) {
    $html = '';
    // prev
    $disabled = $current_page <= 1 ? ' disabled' : '';
    $url = $current_page > 1 ? rrPageUrl($tab, $current_page-1, $per_page, $extra) : '#';
    $html .= "<a href='$url' class='rr-page-btn'$disabled>‹</a>";
    // pages (show max 5 around current)
    $start = max(1, $current_page - 2);
    $end   = min($total_pages, $current_page + 2);
    if ($start > 1) { $html .= "<a href='".rrPageUrl($tab,1,$per_page,$extra)."' class='rr-page-btn'>1</a>"; if ($start > 2) $html .= "<span class='rr-page-btn' style='cursor:default'>…</span>"; }
    for ($i = $start; $i <= $end; $i++) {
        $active = $i === $current_page ? ' active' : '';
        $html .= "<a href='".rrPageUrl($tab,$i,$per_page,$extra)."' class='rr-page-btn$active'>$i</a>";
    }
    if ($end < $total_pages) { if ($end < $total_pages-1) $html .= "<span class='rr-page-btn' style='cursor:default'>…</span>"; $html .= "<a href='".rrPageUrl($tab,$total_pages,$per_page,$extra)."' class='rr-page-btn'>$total_pages</a>"; }
    // next
    $disabled = $current_page >= $total_pages ? ' disabled' : '';
    $url = $current_page < $total_pages ? rrPageUrl($tab, $current_page+1, $per_page, $extra) : '#';
    $html .= "<a href='$url' class='rr-page-btn'$disabled>›</a>";
    return $html;
}
?>


<?php if ($active_tab === 'ruangan'): ?>
<!-- ═══════════════════════════════════════════════════════
     TAB: RUANG RAPAT
     ═══════════════════════════════════════════════════════ -->

<?php
$rr_per_page     = (int)($this->input->get('per_page') ?: 10);
$rr_per_page     = in_array($rr_per_page, [10,20,50,100]) ? $rr_per_page : 10;
$rr_page         = max(1, (int)($this->input->get('page') ?: 1));

$rr_filter_search  = trim($this->input->get('search')  ?: '');
$rr_filter_status  = trim($this->input->get('status')  ?: '');
$rr_filter_tanggal = trim($this->input->get('tanggal') ?: '');

$rr_all = $peminjaman ?? [];

if ($rr_filter_search !== '') {
    $rr_all = array_filter($rr_all, function($p) use ($rr_filter_search) {
        return stripos(($p['keperluan']??'').' '.($p['nama_peminjam']??'').' '.($p['nama_ruangan']??''), $rr_filter_search) !== false;
    });
}
if ($rr_filter_status !== '') {
    $rr_all = array_filter($rr_all, function($p) use ($rr_filter_status) {
        return ($p['status'] ?? '') === $rr_filter_status;
    });
}
if ($rr_filter_tanggal !== '') {
    $rr_all = array_filter($rr_all, function($p) use ($rr_filter_tanggal) {
        return ($p['tanggal'] ?? '') === $rr_filter_tanggal;
    });
}

$rr_all          = array_values($rr_all);
$rr_total        = count($rr_all);
$rr_total_pages  = max(1, ceil($rr_total / $rr_per_page));
$rr_page         = min($rr_page, $rr_total_pages);
$rr_offset       = ($rr_page - 1) * $rr_per_page;
$rr_paged        = array_slice($rr_all, $rr_offset, $rr_per_page);
?>

<!-- STAT CARDS -->
<div class="grid grid-cols-5 gap-2 mb-3">
    <?php
    $stats = [
        ['label'=>'Total',    'val'=>$total??0,    'color'=>'blue',   'icon'=>'M8 7V3m8 4V3M3 11h18M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z'],
        ['label'=>'Menunggu', 'val'=>$menunggu??0, 'color'=>'yellow', 'icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['label'=>'Disetujui','val'=>$disetujui??0,'color'=>'emerald','icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['label'=>'Ditolak',  'val'=>$ditolak??0,  'color'=>'red',    'icon'=>'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['label'=>'Selesai',  'val'=>$selesai??0,  'color'=>'slate',  'icon'=>'M5 13l4 4L19 7'],
    ];
    foreach ($stats as $s): ?>
    <div class="rr-stat-card rounded-lg p-3 border transition">
        <div class="flex items-center justify-between mb-1.5">
            <div class="text-[10px] font-semibold uppercase tracking-wider" style="color:var(--rr-badge-<?= ['blue'=>'diset','yellow'=>'tunggu','emerald'=>'diset','red'=>'tolak','slate'=>'seles'][$s['color']] ?>-text)">
                <?= $s['label'] ?>
            </div>
            <div class="w-6 h-6 rounded-md flex items-center justify-center"
                 style="background-color:var(--rr-badge-<?= ['blue'=>'diset','yellow'=>'tunggu','emerald'=>'diset','red'=>'tolak','slate'=>'seles'][$s['color']] ?>-bg)">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     style="color:var(--rr-badge-<?= ['blue'=>'diset','yellow'=>'tunggu','emerald'=>'diset','red'=>'tolak','slate'=>'seles'][$s['color']] ?>-text)">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $s['icon'] ?>"/>
                </svg>
            </div>
        </div>
        <div class="text-xl font-bold rr-td-main"><?= $s['val'] ?></div>
    </div>
    <?php endforeach; ?>
</div>

<!-- ROW: JADWAL HARI INI + STATUS RUANGAN -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-3 mb-3">
    <div class="lg:col-span-2 rr-card rounded-lg border p-3.5">
        <div class="flex items-center justify-between mb-3">
            <div>
                <h2 class="text-xs font-bold rr-card-title">Jadwal Hari Ini</h2>
                <p class="text-[10px] rr-card-sub mt-0.5"><?= date('l, d F Y') ?></p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 rr-badge-diset text-[10px] font-semibold rounded-full">
                    <?= count($jadwal_hari_ini ?? []) ?> Kegiatan
                </span>
                <button onclick="openRuangModal('modalTambahRuang')"
                        class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-600 hover:bg-blue-500 text-white text-[10px] font-semibold rounded-lg transition">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah
                </button>
            </div>
        </div>
        <?php if (!empty($jadwal_hari_ini)): ?>
        <div class="space-y-1.5">
            <?php foreach ($jadwal_hari_ini as $j):
                $jam_mulai   = substr($j['jam_mulai'],0,5);
                $jam_selesai = substr($j['jam_selesai'],0,5);
                $now         = date('H:i');
                $is_ongoing  = $now >= $jam_mulai && $now <= $jam_selesai;
                $badge_cls   = $j['status']==='Disetujui' ? 'rr-badge-diset' : 'rr-badge-tunggu';
            ?>
            <div class="flex items-center gap-2.5 p-2.5 rounded-lg border transition
                        <?= $is_ongoing ? 'rr-jadwal-live' : 'rr-jadwal-item' ?>">
                <div class="text-center min-w-[44px]">
                    <div class="text-[10px] font-mono font-bold <?= $is_ongoing ? 'text-blue-400' : 'rr-jadwal-time' ?>"><?= $jam_mulai ?></div>
                    <div class="text-[9px] rr-jadwal-time-muted">–</div>
                    <div class="text-[10px] font-mono rr-jadwal-time-muted"><?= $jam_selesai ?></div>
                </div>
                <div class="w-px h-8 rr-jadwal-sep flex-shrink-0"></div>
                <div class="flex-1 min-w-0">
                    <div class="font-semibold rr-jadwal-title text-xs truncate"><?= htmlspecialchars($j['keperluan']) ?></div>
                    <div class="text-[10px] rr-jadwal-meta flex items-center gap-1 mt-0.5 flex-wrap">
                        🏢 <?= htmlspecialchars($j['nama_ruangan']) ?>
                        <span class="rr-td-muted">·</span>
                        <span class="truncate"><?= htmlspecialchars($j['nama_peminjam']) ?></span>
                        <?php if (!empty($j['jumlah_peserta'])): ?>
                        <span class="rr-td-muted">·</span><span><?= $j['jumlah_peserta'] ?> peserta</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="flex items-center gap-1.5 flex-shrink-0">
                    <?php if ($is_ongoing): ?>
                    <span class="rr-badge-live flex items-center gap-1 px-1.5 py-0.5 text-[9px] font-bold rounded-full">
                        <span class="w-1 h-1 rounded-full animate-pulse" style="background-color:var(--rr-badge-live-text)"></span> LIVE
                    </span>
                    <?php endif; ?>
                    <span class="px-1.5 py-0.5 text-[9px] font-semibold rounded-full <?= $badge_cls ?>"><?= $j['status'] ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="flex flex-col items-center justify-center py-6 text-center">
            <p class="rr-td-muted text-xs">Tidak ada kegiatan hari ini</p>
        </div>
        <?php endif; ?>
    </div>

    <div class="rr-card rounded-lg border p-3.5">
        <h2 class="text-xs font-bold rr-card-title mb-2.5">Status Ruangan</h2>
        <div class="space-y-1.5">
            <?php if (!empty($status_ruangan)):
                foreach ($status_ruangan as $r):
                $is_used = $r['sedang_dipakai'] ?? false;
            ?>
            <div class="flex items-center gap-2 p-2 rounded-lg rr-status-item border">
                <div class="w-2 h-2 rounded-full flex-shrink-0 <?= $is_used ? 'animate-pulse' : '' ?>"
                     style="background-color:<?= $is_used ? 'var(--rr-terpakai-dot)' : 'var(--rr-tersedia-dot)' ?>"></div>
                <div class="flex-1 min-w-0">
                    <div class="text-xs font-medium rr-status-name truncate"><?= htmlspecialchars($r['nama_ruangan']) ?></div>
                    <div class="text-[10px] rr-status-sub">Kap. <?= $r['kapasitas'] ?> · <?= $r['lokasi'] ?></div>
                </div>
                <span class="text-[9px] font-semibold px-1.5 py-0.5 rounded-full flex-shrink-0
                    <?= $is_used ? 'rr-badge-tolak' : 'rr-badge-diset' ?>">
                    <?= $is_used ? 'Terpakai' : 'Tersedia' ?>
                </span>
            </div>
            <?php endforeach;
            else: ?>
            <p class="rr-td-muted text-xs text-center py-4">Belum ada ruangan</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- FILTER + PER PAGE -->
<form method="GET" action="<?= base_url('ruang_rapat') ?>" id="formFilterRuangan">
    <input type="hidden" name="tab" value="ruangan">
    <input type="hidden" name="page" value="1">
    <input type="hidden" name="per_page" value="<?= $rr_per_page ?>">
    <div class="flex flex-wrap gap-2 mb-2.5 items-center">
        <input type="text" name="search" id="searchInput"
               value="<?= htmlspecialchars($rr_filter_search) ?>"
               placeholder="Cari kegiatan / peminjam / ruangan..."
               class="flex-1 min-w-[200px] px-2.5 py-1.5 rounded-lg border text-xs rr-filter-input
                      focus:outline-none focus:ring-2 focus:ring-blue-500">
        <select name="status" id="filterStatus"
                class="px-2.5 py-1.5 rounded-lg border text-xs rr-filter-input
                       focus:outline-none focus:ring-2 focus:ring-blue-500"
                onchange="document.getElementById('formFilterRuangan').submit()">
            <option value="">Semua Status</option>
            <option value="Menunggu"  <?= $rr_filter_status==='Menunggu'  ? 'selected' : '' ?>>Menunggu</option>
            <option value="Disetujui" <?= $rr_filter_status==='Disetujui' ? 'selected' : '' ?>>Disetujui</option>
            <option value="Ditolak"   <?= $rr_filter_status==='Ditolak'   ? 'selected' : '' ?>>Ditolak</option>
            <option value="Selesai"   <?= $rr_filter_status==='Selesai'   ? 'selected' : '' ?>>Selesai</option>
        </select>
        <input type="date" name="tanggal" id="filterTanggal"
               value="<?= htmlspecialchars($rr_filter_tanggal) ?>"
               class="px-2.5 py-1.5 rounded-lg border text-xs rr-filter-input
                      focus:outline-none focus:ring-2 focus:ring-blue-500"
               onchange="document.getElementById('formFilterRuangan').submit()">
        <!-- Per page selector -->
        <div class="flex items-center gap-1.5">
            <span class="text-[10px] rr-td-muted whitespace-nowrap">Tampilkan:</span>
            <?php foreach ([10,20,50,100] as $opt): ?>
            <a href="<?= rrPageUrl('ruangan', 1, $opt, ['search'=>$rr_filter_search,'status'=>$rr_filter_status,'tanggal'=>$rr_filter_tanggal]) ?>"
               class="rr-page-btn <?= $rr_per_page == $opt ? 'active' : '' ?>"><?= $opt ?></a>
            <?php endforeach; ?>
        </div>
        <button type="submit"
                class="px-2.5 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold transition">Cari</button>
        <a href="<?= base_url('ruang_rapat?tab=ruangan') ?>"
           class="px-2.5 py-1.5 rounded-lg border text-xs rr-filter-reset transition">Reset</a>
    </div>
</form>

<!-- TABLE RUANGAN -->
<div class="rr-card rounded-lg border overflow-hidden">
    <div class="px-4 py-2 rr-card-header border-b flex items-center justify-between">
        <h2 class="text-xs font-bold rr-card-title">Semua Peminjaman Ruangan</h2>
        <span id="jumlahTampil" class="text-[10px] rr-td-muted"></span>
    </div>
    <div class="overflow-x-auto w-full">
        <table class="w-full text-xs" style="min-width:960px" id="tabelRuang">
            <thead>
                <tr class="border-b rr-th text-[10px] uppercase tracking-wider">
                    <th class="px-3 py-2 text-left font-semibold">No</th>
                    <th class="px-3 py-2 text-left font-semibold">Kegiatan</th>
                    <th class="px-3 py-2 text-left font-semibold">Penanggung Jawab</th>
                    <th class="px-3 py-2 text-left font-semibold">Ruangan / Lokasi</th>
                    <th class="px-3 py-2 text-left font-semibold">Tanggal &amp; Waktu</th>
                    <th class="px-3 py-2 text-center font-semibold">Peserta</th>
                    <th class="px-3 py-2 text-center font-semibold">Snack</th>
                    <th class="px-3 py-2 text-center font-semibold">Dokumen</th>
                    <th class="px-3 py-2 text-left font-semibold">Status</th>
                    <th class="px-3 py-2 text-center font-semibold">Selesai</th>
                    <th class="px-3 py-2 text-center font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y rr-tr">
                <?php if (!empty($rr_paged)):
                    foreach ($rr_paged as $i => $p):
                    $badge_map = [
                        'Menunggu'  => 'rr-badge-tunggu',
                        'Disetujui' => 'rr-badge-diset',
                        'Ditolak'   => 'rr-badge-tolak',
                        'Selesai'   => 'rr-badge-seles',
                    ];
                    $cls = $badge_map[$p['status']] ?? 'rr-badge-seles';
                    $dok_url = !empty($p['dokumen_peserta'])
                        ? base_url('uploads/dokumen_peserta/'.$p['dokumen_peserta'])
                        : null;
                ?>
                <tr class="rr-tr transition row-data"
                    data-status="<?= $p['status'] ?>"
                    data-tanggal="<?= $p['tanggal'] ?>"
                    data-search="<?= strtolower($p['keperluan'].' '.$p['nama_peminjam'].' '.$p['nama_ruangan']) ?>">
                    <td class="px-3 py-3 rr-td-no"><?= $rr_offset + $i + 1 ?></td>
                    <td class="px-3 py-3 max-w-[160px]">
                        <div class="font-medium rr-td-main truncate"><?= htmlspecialchars($p['keperluan']) ?></div>
                        <?php if (!empty($p['catatan_peminjam'])): ?>
                        <div class="text-[10px] rr-td-sub truncate mt-0.5">📝 <?= htmlspecialchars($p['catatan_peminjam']) ?></div>
                        <?php endif; ?>
                    </td>
                    <td class="px-3 py-3">
                        <div class="rr-td-main"><?= htmlspecialchars($p['nama_peminjam']) ?></div>
                        <div class="text-[10px] rr-td-sub"><?= $p['unit_kerja'] ?? '-' ?></div>
                    </td>
                    <td class="px-3 py-3">
                        <div class="rr-td-sec"><?= htmlspecialchars($p['nama_ruangan']) ?></div>
                        <div class="text-[10px] rr-td-muted"><?= $p['lokasi'] ?? '' ?> · Kap. <?= $p['kapasitas'] ?? '-' ?></div>
                    </td>
                    <td class="px-3 py-3">
                        <div class="rr-td-sec">
                            <?= date('d M Y', strtotime($p['tanggal'])) ?>
                            <?php if ($p['tanggal']==date('Y-m-d')): ?>
                            <span class="ml-1 px-1 py-0.5 rr-badge-live text-[9px] font-bold rounded">HARI INI</span>
                            <?php endif; ?>
                        </div>
                        <div class="font-mono rr-td-muted text-[10px]"><?= substr($p['jam_mulai'],0,5) ?> – <?= substr($p['jam_selesai'],0,5) ?></div>
                    </td>
                    <td class="px-3 py-3 rr-td-muted text-center"><?= $p['jumlah_peserta'] ? $p['jumlah_peserta'].' org' : '-' ?></td>
                    <td class="px-3 py-3 text-center">
                        <?php if ($p['snack_corner']==='Ya'): ?>
                       <span class="rr-snack-badge px-1.5 py-0.5 text-[9px] font-semibold rounded-full whitespace-nowrap">
    Ya <?= $p['jumlah_snack']?'('.$p['jumlah_snack'].')':'' ?>
</span>
                        <?php else: ?><span class="rr-td-muted">–</span><?php endif; ?>
                    </td>
       <!-- KOLOM DOKUMEN -->
<td class="px-3 py-3 text-center">
    <?php
    // Ambil multi-dokumen untuk baris ini
    $multi_docs = $this->db
        ->select('id, jenis_dokumen, nama_file')
        ->where('peminjaman_id', $p['id'])
        ->order_by('created_at', 'ASC')
        ->get('dokumen_peminjaman_rapat')
        ->result_array();
    ?>
    <?php if (!empty($multi_docs)): ?>
    <div class="flex flex-col items-center gap-1">
        <?php foreach ($multi_docs as $dok):
            $dok_url_item = base_url('uploads/dokumen_peserta/'.$dok['nama_file']);
            $jenis_short = [
                'Undangan'      => 'Und',
                'Notulensi'     => 'Not',
                'Dokumentasi'   => 'Dok',
                'Daftar Hadir'  => 'Had',
                'Lampiran Lain' => 'Lmp',
            ][$dok['jenis_dokumen']] ?? substr($dok['jenis_dokumen'],0,3);
        ?>
        <div class="flex items-center gap-1">
            <button onclick="rrOpenPdf('<?= htmlspecialchars($dok_url_item) ?>', '<?= htmlspecialchars($dok['jenis_dokumen']) ?>')"
                    class="rr-dok-badge">
                <?= $jenis_short ?>
            </button>
            <a href="<?= base_url('ruang_rapat/hapus_dok_item/'.$dok['id']) ?>"
               onclick="return confirm('Hapus <?= htmlspecialchars($dok['jenis_dokumen']) ?>?')"
               class="text-[9px] rr-td-muted hover:text-red-400 transition" title="Hapus">✕</a>
        </div>
        <?php endforeach; ?>
        <button onclick="rrOpenUploadMulti(<?= $p['id'] ?>, '<?= htmlspecialchars($p['keperluan'], ENT_QUOTES) ?>')"
                class="rr-dok-none hover:text-purple-400 underline transition cursor-pointer text-[9px] mt-0.5">
            + Upload
        </button>
    </div>
    <?php else: ?>
    <button onclick="rrOpenUploadMulti(<?= $p['id'] ?>, '<?= htmlspecialchars($p['keperluan'], ENT_QUOTES) ?>')"
            class="rr-dok-none hover:text-purple-400 underline transition cursor-pointer">
        Upload
    </button>
    <?php endif; ?>
</td>
  
<td class="px-3 py-3">
    <?php $status_display = !empty($p['status']) ? $p['status'] : 'Menunggu'; ?>
    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold <?= $cls ?>">
        <?= $status_display ?>
    </span>
</td>
                    <td class="px-3 py-3 text-center">
                        <?php if (($p['selesai']??'Belum')==='Ya'): ?>
                        <span class="rr-done-badge px-2 py-0.5 text-[10px] font-semibold rounded-full">✓ Selesai</span>
                        <?php else: ?>
                        <span class="rr-belum-text text-[10px]">Belum</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-3 py-3">
                        <div class="flex items-center justify-center gap-1">
                            <?php if ($p['status']==='Menunggu'): ?>
                            <a href="<?= base_url('ruang_rapat/setujui/'.$p['id']) ?>"
                               onclick="return confirm('Setujui peminjaman ini?')"
                               class="rr-btn-setujui p-1 rounded-md border transition" title="Setujui">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </a>
                            <a href="<?= base_url('ruang_rapat/tolak/'.$p['id']) ?>"
                               onclick="return confirm('Tolak peminjaman ini?')"
                               class="rr-btn-tolak p-1 rounded-md border transition" title="Tolak">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </a>
                            <?php endif; ?>
                            <?php if ($p['status']==='Disetujui' && ($p['selesai']??'Belum')==='Belum'): ?>
                            <a href="<?= base_url('ruang_rapat/selesai/'.$p['id']) ?>"
                               onclick="return confirm('Tandai selesai?')"
                               class="rr-btn-done p-1 rounded-md border transition" title="Tandai Selesai">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            </a>
                            <?php endif; ?>
                            <button onclick='openRuangEdit(<?= json_encode($p) ?>)'
                                    class="rr-btn-edit p-1 rounded-md border transition" title="Edit">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <a href="<?= base_url('ruang_rapat/hapus/'.$p['id']) ?>"
                               onclick="return confirm('Hapus data ini?')"
                               class="rr-btn-del p-1 rounded-md border transition" title="Hapus">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach;
                else: ?>
                <tr>
                    <td colspan="11" class="px-5 py-12 text-center">
                        <p class="rr-td-muted text-xs">Belum ada data peminjaman ruang</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <!-- PAGINATION BAR -->
    <div class="px-4 py-3 rr-card-header border-t">
        <div class="rr-pagination">
            <span class="rr-pagination-info">
                Menampilkan <?= $rr_total > 0 ? $rr_offset+1 : 0 ?>–<?= min($rr_offset+$rr_per_page, $rr_total) ?>
                dari <strong><?= $rr_total ?></strong> data
            </span>
            <div class="flex items-center gap-1">
                <?= rrPaginationHtml('ruangan', $rr_page, $rr_total_pages, $rr_per_page, [
    'search'  => $rr_filter_search,
    'status'  => $rr_filter_status,
    'tanggal' => $rr_filter_tanggal,
]) ?>
            </div>
        </div>
    </div>
</div>


<?php elseif ($active_tab === 'kendaraan'): ?>
<!-- ═══════════════════════════════════════════════════════
     TAB: KENDARAAN
     ═══════════════════════════════════════════════════════ -->

<?php
$kend_data    = $kendaraan_peminjaman ?? [];
$kv_per_page  = (int)($this->input->get('per_page') ?: 10);
$kv_per_page  = in_array($kv_per_page, [10,20,50,100]) ? $kv_per_page : 10;
$kv_page      = max(1, (int)($this->input->get('page') ?: 1));
$kv_total     = count($kend_data);
$kv_total_pages = max(1, ceil($kv_total / $kv_per_page));
$kv_page      = min($kv_page, $kv_total_pages);
$kv_offset    = ($kv_page - 1) * $kv_per_page;
$kv_paged     = array_slice($kend_data, $kv_offset, $kv_per_page);

$kend_total  = count($kend_data);
$kend_tunggu = count(array_filter($kend_data, fn($k) => $k['status']==='Menunggu'));
$kend_diset  = count(array_filter($kend_data, fn($k) => $k['status']==='Disetujui'));
$kend_tolak  = count(array_filter($kend_data, fn($k) => $k['status']==='Ditolak'));
$kend_seles  = count(array_filter($kend_data, fn($k) => $k['status']==='Selesai'));
?>

<div class="grid grid-cols-5 gap-2 mb-3">
    <?php
    $kstats = [
        ['label'=>'Total',    'val'=>$kend_total,  'key'=>'diset'],
        ['label'=>'Menunggu', 'val'=>$kend_tunggu, 'key'=>'tunggu'],
        ['label'=>'Disetujui','val'=>$kend_diset,  'key'=>'diset'],
        ['label'=>'Ditolak',  'val'=>$kend_tolak,  'key'=>'tolak'],
        ['label'=>'Selesai',  'val'=>$kend_seles,  'key'=>'seles'],
    ];
    foreach ($kstats as $s): ?>
    <div class="rr-stat-card rounded-lg p-3 border transition">
        <div class="flex items-center justify-between mb-1.5">
            <div class="text-[10px] font-semibold uppercase tracking-wider"
                 style="color:var(--rr-badge-<?= $s['key'] ?>-text)"><?= $s['label'] ?></div>
            <div class="w-6 h-6 rounded-md flex items-center justify-center"
                 style="background-color:var(--rr-badge-<?= $s['key'] ?>-bg)">
                <span style="color:var(--rr-badge-<?= $s['key'] ?>-text)" class="text-[10px] font-bold">·</span>
            </div>
        </div>
        <div class="text-xl font-bold rr-td-main"><?= $s['val'] ?></div>
    </div>
    <?php endforeach; ?>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-3 mb-3">
    <div class="lg:col-span-2 rr-card rounded-lg border p-3.5">
        <div class="flex items-center justify-between mb-3">
            <div>
                <h2 class="text-xs font-bold rr-card-title">Peminjaman Kendaraan Hari Ini</h2>
                <p class="text-[10px] rr-card-sub mt-0.5"><?= date('l, d F Y') ?></p>
            </div>
            <div class="flex items-center gap-2">
                <?php $kend_hari_ini = array_filter($kend_data, fn($k)=>$k['tanggal_pinjam']==date('Y-m-d')); ?>
                <span class="px-2 py-0.5 rr-badge-diset text-[10px] font-semibold rounded-full">
                    <?= count($kend_hari_ini) ?> Peminjaman
                </span>
                <button onclick="openKendModal('modalTambahKend')"
                        class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-600 hover:bg-blue-500 text-white text-[10px] font-semibold rounded-lg transition">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah
                </button>
            </div>
        </div>
        <?php if (!empty($kend_hari_ini)): ?>
        <div class="space-y-1.5">
            <?php foreach ($kend_hari_ini as $kh):
                $kbcls = match($kh['status']) {
                    'Disetujui' => 'rr-badge-diset', 'Menunggu' => 'rr-badge-tunggu',
                    'Selesai'   => 'rr-badge-seles',  default => 'rr-badge-seles',
                };
            ?>
            <div class="flex items-center gap-2.5 p-2.5 rounded-lg rr-jadwal-item border">
                <div class="text-center min-w-[44px]">
                    <div class="text-[10px] font-mono font-bold rr-jadwal-time"><?= date('d M', strtotime($kh['tanggal_pinjam'])) ?></div>
                    <div class="text-[9px] rr-jadwal-time-muted">s/d</div>
                    <div class="text-[10px] font-mono rr-jadwal-time-muted"><?= date('d M', strtotime($kh['tanggal_kembali'])) ?></div>
                </div>
                <div class="w-px h-8 rr-jadwal-sep flex-shrink-0"></div>
                <div class="flex-1 min-w-0">
                    <div class="font-semibold rr-jadwal-title text-xs truncate"><?= htmlspecialchars($kh['keperluan']) ?></div>
                    <div class="text-[10px] rr-jadwal-meta flex items-center gap-1 mt-0.5">
                        🚗 <?= htmlspecialchars($kh['nama_kendaraan'] ?? '-') ?>
                        <span class="rr-td-muted">·</span>
                        <span class="truncate"><?= htmlspecialchars($kh['nama_peminjam']) ?></span>
                    </div>
                </div>
                <span class="px-1.5 py-0.5 text-[9px] font-semibold rounded-full <?= $kbcls ?>"><?= $kh['status'] ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="flex flex-col items-center justify-center py-6 text-center">
            <p class="rr-td-muted text-xs">Tidak ada peminjaman kendaraan hari ini</p>
        </div>
        <?php endif; ?>
    </div>

    <div class="rr-card rounded-lg border p-3.5">
        <h2 class="text-xs font-bold rr-card-title mb-2.5">Status Armada</h2>
        <div class="space-y-1.5">
            <?php if (!empty($status_kendaraan)):
                foreach ($status_kendaraan as $kv):
                $kicon = $kv['jenis']==='Mobil' ? '🚗' : '🏍️';
                $kst_cls = match($kv['status']) {
                    'Tersedia'        => 'rr-badge-diset',
                    'Tidak Tersedia'  => 'rr-badge-tolak',
                    'Dalam Perbaikan' => 'rr-badge-tunggu',
                    default           => 'rr-badge-seles',
                };
            ?>
            <div class="flex items-center gap-2 p-2 rounded-lg rr-status-item border">
                <span class="text-sm flex-shrink-0"><?= $kicon ?></span>
                <div class="flex-1 min-w-0">
                    <div class="text-xs font-medium rr-status-name truncate"><?= htmlspecialchars($kv['nama_kendaraan']) ?></div>
                    <div class="text-[10px] rr-status-sub font-mono"><?= $kv['nomor_polisi'] ?></div>
                </div>
                <span class="text-[9px] font-semibold px-1.5 py-0.5 rounded-full flex-shrink-0 <?= $kst_cls ?>">
                    <?= $kv['status'] ?>
                </span>
            </div>
            <?php endforeach;
            else: ?>
            <p class="rr-td-muted text-xs text-center py-4">Belum ada data armada</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- FILTER KENDARAAN + PER PAGE -->
<div class="flex flex-wrap gap-2 mb-2.5 items-center">
    <input type="text" id="searchKend" placeholder="Cari peminjam / kendaraan / keperluan..."
           class="flex-1 min-w-[200px] px-2.5 py-1.5 rounded-lg border text-xs rr-filter-input
                  focus:outline-none focus:ring-2 focus:ring-blue-500">
    <select id="filterStatusKend"
            class="px-2.5 py-1.5 rounded-lg border text-xs rr-filter-input
                   focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Semua Status</option>
        <option value="Menunggu">Menunggu</option>
        <option value="Disetujui">Disetujui</option>
        <option value="Ditolak">Ditolak</option>
        <option value="Selesai">Selesai</option>
    </select>
    <div class="flex items-center gap-1.5">
        <span class="text-[10px] rr-td-muted whitespace-nowrap">Tampilkan:</span>
        <?php foreach ([10,20,50,100] as $opt): ?>
        <a href="<?= rrPageUrl('kendaraan', 1, $opt) ?>"
           class="rr-page-btn <?= $kv_per_page == $opt ? 'active' : '' ?>"><?= $opt ?></a>
        <?php endforeach; ?>
    </div>
    <button onclick="resetFilterKend()"
            class="px-2.5 py-1.5 rounded-lg border text-xs rr-filter-reset transition">Reset</button>
</div>

<!-- TABLE KENDARAAN -->
<div class="rr-card rounded-lg border overflow-hidden">
    <div class="px-4 py-2 rr-card-header border-b flex items-center justify-between">
        <h2 class="text-xs font-bold rr-card-title">Semua Peminjaman Kendaraan</h2>
        <span id="jumlahTampilKend" class="text-[10px] rr-td-muted"></span>
    </div>
    <div class="overflow-x-auto w-full">
        <table class="w-full text-xs" style="min-width:900px">
            <thead>
                <tr class="border-b rr-th text-[10px] uppercase tracking-wider">
                    <th class="px-3 py-2 text-left font-semibold">No</th>
                    <th class="px-3 py-2 text-left font-semibold">Peminjam</th>
                    <th class="px-3 py-2 text-left font-semibold">Kendaraan</th>
                    <th class="px-3 py-2 text-left font-semibold">Keperluan / Tujuan</th>
                    <th class="px-3 py-2 text-left font-semibold">Tgl Pinjam</th>
                    <th class="px-3 py-2 text-left font-semibold">Tgl Kembali</th>
                    <th class="px-3 py-2 text-left font-semibold">Pengemudi</th>
                    <th class="px-3 py-2 text-left font-semibold">Status</th>
                    <th class="px-3 py-2 text-center font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y rr-tr">
                <?php if (!empty($kv_paged)):
                    foreach ($kv_paged as $i => $k):
                    $kbadge_map = [
                        'Menunggu'  => 'rr-badge-tunggu',
                        'Disetujui' => 'rr-badge-diset',
                        'Ditolak'   => 'rr-badge-tolak',
                        'Selesai'   => 'rr-badge-seles',
                    ];
                    $kcls = $kbadge_map[$k['status']] ?? 'rr-badge-seles';
                ?>
                <tr class="rr-tr transition row-kend"
                    data-status="<?= $k['status'] ?>"
                    data-search="<?= strtolower(($k['nama_peminjam']??'').' '.($k['nama_kendaraan']??'').' '.($k['keperluan']??'')) ?>">
                    <td class="px-3 py-3 rr-td-no"><?= $kv_offset + $i + 1 ?></td>
                    <td class="px-3 py-3">
                        <div class="rr-td-main"><?= htmlspecialchars($k['nama_peminjam']) ?></div>
                        <div class="text-[10px] rr-td-sub"><?= htmlspecialchars($k['unit_kerja'] ?? '-') ?></div>
                    </td>
                    <td class="px-3 py-3">
                        <div class="rr-td-sec"><?= ($k['jenis']==='Mobil'?'🚗':'🏍️') ?> <?= htmlspecialchars($k['nama_kendaraan'] ?? '-') ?></div>
                        <div class="text-[10px] rr-td-muted font-mono"><?= $k['nomor_polisi'] ?? '' ?></div>
                    </td>
                    <td class="px-3 py-3 max-w-[180px]">
                        <div class="rr-td-sec truncate"><?= htmlspecialchars($k['keperluan'] ?? '-') ?></div>
                        <?php if (!empty($k['tujuan'])): ?>
                        <div class="text-[10px] rr-td-sub truncate">📍 <?= htmlspecialchars($k['tujuan']) ?></div>
                        <?php endif; ?>
                    </td>
                    <td class="px-3 py-3 rr-td-sec">
                        <?= date('d M Y', strtotime($k['tanggal_pinjam'])) ?>
                        <?php if ($k['tanggal_pinjam']==date('Y-m-d')): ?>
                        <span class="ml-1 px-1 py-0.5 rr-badge-live text-[9px] font-bold rounded">HARI INI</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-3 py-3 rr-td-sec"><?= date('d M Y', strtotime($k['tanggal_kembali'])) ?></td>
                    <td class="px-3 py-3 rr-td-muted"><?= htmlspecialchars($k['pengemudi'] ?? 'Sendiri') ?></td>
                    <td class="px-3 py-3">
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold <?= $kcls ?>"><?= $k['status'] ?></span>
                    </td>
                    <td class="px-3 py-3">
                        <div class="flex items-center justify-center gap-1">
                            <?php if ($k['status']==='Menunggu'): ?>
                            <a href="<?= base_url('ruang_rapat/setujui_kendaraan/'.$k['id']) ?>" onclick="return confirm('Setujui?')"
                               class="rr-btn-setujui p-1 rounded-md border transition" title="Setujui">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </a>
                            <a href="<?= base_url('ruang_rapat/tolak_kendaraan/'.$k['id']) ?>" onclick="return confirm('Tolak?')"
                               class="rr-btn-tolak p-1 rounded-md border transition" title="Tolak">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </a>
                            <?php endif; ?>
                            <?php if ($k['status']==='Disetujui'): ?>
                            <a href="<?= base_url('ruang_rapat/selesai_kendaraan/'.$k['id']) ?>" onclick="return confirm('Tandai kembali?')"
                               class="rr-btn-done p-1 rounded-md border transition" title="Selesai">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            </a>
                            <?php endif; ?>
                            <button onclick='openKendEdit(<?= json_encode($k) ?>)'
                                    class="rr-btn-edit p-1 rounded-md border transition" title="Edit">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <a href="<?= base_url('ruang_rapat/hapus_kendaraan/'.$k['id']) ?>" onclick="return confirm('Hapus?')"
                               class="rr-btn-del p-1 rounded-md border transition" title="Hapus">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach;
                else: ?>
                <tr><td colspan="9" class="px-5 py-12 text-center"><p class="rr-td-muted text-xs">Belum ada data peminjaman kendaraan</p></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <!-- PAGINATION BAR KENDARAAN -->
    <div class="px-4 py-3 rr-card-header border-t">
        <div class="rr-pagination">
            <span class="rr-pagination-info">
                Menampilkan <?= $kv_total > 0 ? $kv_offset+1 : 0 ?>–<?= min($kv_offset+$kv_per_page, $kv_total) ?>
                dari <strong><?= $kv_total ?></strong> data
            </span>
            <div class="flex items-center gap-1">
                <?= rrPaginationHtml('kendaraan', $kv_page, $kv_total_pages, $kv_per_page) ?>
            </div>
        </div>
    </div>
</div>


<?php elseif ($active_tab === 'bmn'): ?>
<!-- ═══════════════════════════════════════════════════════
     TAB: BMN
     ═══════════════════════════════════════════════════════ -->

<?php
$bmn_all       = $peminjaman_bmn ?? [];
$bmn_pp        = (int)($this->input->get('per_page') ?: 10);
$bmn_pp        = in_array($bmn_pp, [10,20,50,100]) ? $bmn_pp : 10;
$bmn_pg        = max(1, (int)($this->input->get('page') ?: 1));
$bmn_count     = count($bmn_all);
$bmn_tp        = max(1, ceil($bmn_count / $bmn_pp));
$bmn_pg        = min($bmn_pg, $bmn_tp);
$bmn_off       = ($bmn_pg - 1) * $bmn_pp;
$bmn_paged     = array_slice($bmn_all, $bmn_off, $bmn_pp);
?>

<div class="grid grid-cols-4 gap-2 mb-3">
    <?php
    $bstats = [
        ['label'=>'Total',    'val'=>$bmn_total??0,    'key'=>'diset'],
        ['label'=>'Menunggu', 'val'=>$bmn_menunggu??0, 'key'=>'tunggu'],
        ['label'=>'Disetujui','val'=>$bmn_disetujui??0,'key'=>'diset'],
        ['label'=>'Selesai',  'val'=>$bmn_selesai??0,  'key'=>'seles'],
    ];
    foreach ($bstats as $s): ?>
    <div class="rr-stat-card rounded-lg p-3 border transition">
        <div class="flex items-center justify-between mb-1.5">
            <div class="text-[10px] font-semibold uppercase tracking-wider"
                 style="color:var(--rr-badge-<?= $s['key'] ?>-text)"><?= $s['label'] ?></div>
            <div class="w-6 h-6 rounded-md flex items-center justify-center"
                 style="background-color:var(--rr-badge-<?= $s['key'] ?>-bg)">
                <span style="color:var(--rr-badge-<?= $s['key'] ?>-text)" class="text-[10px] font-bold">·</span>
            </div>
        </div>
        <div class="text-xl font-bold rr-td-main"><?= $s['val'] ?></div>
    </div>
    <?php endforeach; ?>
</div>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3">
    <div>
        <h2 class="text-sm font-bold rr-card-title">Peminjaman BMN</h2>
        <p class="text-[10px] rr-card-sub mt-0.5">Barang Milik Negara yang sedang / pernah dipinjam</p>
    </div>
    <button onclick="bmnOpenModal('bmn_modalTambah')"
            class="inline-flex items-center gap-2 px-3 py-2 bg-purple-600 hover:bg-purple-500
                   text-white text-xs font-semibold rounded-lg transition">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Ajukan Peminjaman
    </button>
</div>

<!-- FILTER BMN + PER PAGE -->
<div class="flex flex-wrap gap-2 mb-2.5 items-center">
    <input type="text" id="bmnSearchInput" placeholder="Cari peminjam / barang / unit kerja..."
           class="flex-1 min-w-[200px] px-2.5 py-1.5 rounded-lg border text-xs rr-filter-input
                  focus:outline-none focus:ring-2 focus:ring-purple-500">
    <select id="bmnFilterStatus"
            class="px-2.5 py-1.5 rounded-lg border text-xs rr-filter-input
                   focus:outline-none focus:ring-2 focus:ring-purple-500">
        <option value="">Semua Status</option>
        <option value="Menunggu">Menunggu</option>
        <option value="Disetujui">Disetujui</option>
        <option value="Ditolak">Ditolak</option>
        <option value="Selesai">Selesai</option>
    </select>
    <div class="flex items-center gap-1.5">
        <span class="text-[10px] rr-td-muted whitespace-nowrap">Tampilkan:</span>
        <?php foreach ([10,20,50,100] as $opt): ?>
        <a href="<?= rrPageUrl('bmn', 1, $opt) ?>"
           class="rr-page-btn <?= $bmn_pp == $opt ? 'active' : '' ?>"><?= $opt ?></a>
        <?php endforeach; ?>
    </div>
</div>

<div class="rr-card rounded-lg border overflow-hidden">
    <div class="px-4 py-2 rr-card-header border-b flex items-center justify-between">
        <h2 class="text-xs font-bold rr-card-title">Semua Peminjaman BMN</h2>
        <span id="bmnJumlahTampil" class="text-[10px] rr-td-muted"></span>
    </div>
    <div class="overflow-x-auto w-full">
        <table class="w-full text-xs" style="min-width:860px">
            <thead>
                <tr class="border-b rr-th text-[10px] uppercase tracking-wider">
                    <th class="px-3 py-2 text-left font-semibold">No</th>
                    <th class="px-3 py-2 text-left font-semibold">Barang</th>
                    <th class="px-3 py-2 text-left font-semibold">Peminjam</th>
                    <th class="px-3 py-2 text-left font-semibold">Tujuan</th>
                    <th class="px-3 py-2 text-left font-semibold">Tgl Pinjam</th>
                    <th class="px-3 py-2 text-left font-semibold">Tgl Kembali</th>
                    <th class="px-3 py-2 text-left font-semibold">Status</th>
                    <th class="px-3 py-2 text-center font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody id="bmnTabelBody" class="divide-y rr-tr">
            <?php if (!empty($bmn_paged)):
                foreach ($bmn_paged as $i => $row):
                $stMap = [
                    'Menunggu'  => 'rr-badge-tunggu',
                    'Disetujui' => 'rr-badge-diset',
                    'Ditolak'   => 'rr-badge-tolak',
                    'Selesai'   => 'rr-badge-seles',
                ];
                $stCls = $stMap[$row['status']] ?? 'rr-badge-seles';
                $terlambat = ($row['status']==='Disetujui' && !empty($row['tanggal_kembali']) && $row['tanggal_kembali'] < date('Y-m-d'));
            ?>
            <tr class="rr-tr transition bmn-row"
                data-search="<?= strtolower(($row['nama_peminjam']??'').' '.($row['nama_barang']??'').' '.($row['unit_kerja']??'')) ?>"
                data-status="<?= htmlspecialchars($row['status']) ?>">
                <td class="px-3 py-3 rr-td-no"><?= $bmn_off + $i + 1 ?></td>
                <td class="px-3 py-3">
                    <div class="font-medium rr-td-main"><?= htmlspecialchars($row['nama_barang'] ?? '–') ?></div>
                    <div class="text-[10px] rr-td-sub"><?= htmlspecialchars($row['merek'] ?? '') ?></div>
                </td>
                <td class="px-3 py-3">
                    <div class="rr-td-main"><?= htmlspecialchars($row['nama_peminjam']) ?></div>
                    <div class="text-[10px] rr-td-sub"><?= htmlspecialchars($row['unit_kerja'] ?? '–') ?></div>
                </td>
                <td class="px-3 py-3 max-w-[160px]">
                    <div class="rr-td-sec truncate"><?= htmlspecialchars($row['tujuan_peminjaman']) ?></div>
                </td>
                <td class="px-3 py-3 rr-td-sec whitespace-nowrap"><?= date('d M Y', strtotime($row['tanggal_pinjam'])) ?></td>
                <td class="px-3 py-3 whitespace-nowrap">
                    <?php if ($terlambat): ?>
                    <span class="font-semibold" style="color:var(--rr-badge-tolak-text)"><?= date('d M Y', strtotime($row['tanggal_kembali'])) ?></span>
                    <div class="text-[10px]" style="color:var(--rr-badge-tolak-text)">⚠ Terlambat!</div>
                    <?php else: ?>
                    <span class="rr-td-sec"><?= date('d M Y', strtotime($row['tanggal_kembali'])) ?></span>
                    <?php if (!empty($row['tanggal_kembali_aktual'])): ?>
                    <div class="text-[10px]" style="color:var(--rr-badge-diset-text)">✓ <?= date('d M Y', strtotime($row['tanggal_kembali_aktual'])) ?></div>
                    <?php endif; ?>
                    <?php endif; ?>
                </td>
                <td class="px-3 py-3">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold <?= $stCls ?>"><?= $row['status'] ?></span>
                </td>
                <td class="px-3 py-3">
                    <div class="flex items-center justify-center gap-1">
                        <?php if ($row['status']==='Menunggu'): ?>
                        <a href="<?= base_url('ruang_rapat/setujui_bmn/'.$row['id']) ?>" onclick="return confirm('Setujui?')"
                           class="rr-btn-setujui p-1 rounded-md border transition" title="Setujui">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </a>
                        <button onclick="bmnTolak(<?= $row['id'] ?>)"
                                class="rr-btn-tolak p-1 rounded-md border transition" title="Tolak">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        <?php endif; ?>
                        <?php if ($row['status']==='Disetujui'): ?>
                        <a href="<?= base_url('ruang_rapat/selesai_bmn/'.$row['id']) ?>" onclick="return confirm('Tandai sudah dikembalikan?')"
                           class="rr-btn-done p-1 rounded-md border transition" title="Selesai">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </a>
                        <?php endif; ?>
                        <button onclick='bmnOpenEdit(<?= json_encode($row) ?>)'
                                class="rr-btn-edit p-1 rounded-md border transition" title="Edit">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <?php if (in_array($row['status'], ['Ditolak','Selesai'])): ?>
                        <button onclick="bmnHapus(<?= $row['id'] ?>, '<?= addslashes($row['nama_peminjam']) ?>')"
                                class="rr-btn-del p-1 rounded-md border transition" title="Hapus">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php endforeach;
            else: ?>
            <tr><td colspan="8" class="px-5 py-12 text-center"><p class="rr-td-muted text-xs">Belum ada data peminjaman BMN</p></td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <!-- PAGINATION BAR BMN -->
    <div class="px-4 py-3 rr-card-header border-t">
        <div class="rr-pagination">
            <span class="rr-pagination-info">
                Menampilkan <?= $bmn_count > 0 ? $bmn_off+1 : 0 ?>–<?= min($bmn_off+$bmn_pp, $bmn_count) ?>
                dari <strong><?= $bmn_count ?></strong> data
            </span>
            <div class="flex items-center gap-1">
                <?= rrPaginationHtml('bmn', $bmn_pg, $bmn_tp, $bmn_pp) ?>
            </div>
        </div>
    </div>
</div>

<?php endif; // end tab ?>
</div><!-- END WRAPPER -->


<!-- ═══ PDF VIEWER MODAL (admin bisa lihat/ganti dok peserta) ═══ -->
<div class="rr-pdf-overlay" id="rrPdfOverlay">
    <div class="rr-pdf-card">
        <div class="rr-pdf-header">
            <span class="rr-pdf-title" id="rrPdfTitle">Dokumen Peserta</span>
            <div class="rr-pdf-actions">
                <a id="rrPdfDownload" href="#" target="_blank"
                   class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-600 hover:bg-blue-500 text-white text-[10px] font-semibold rounded-lg transition">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Unduh
                </a>
                <button onclick="rrClosePdf()"
                        class="rr-modal-close transition p-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
        <div class="rr-pdf-body" style="height:75vh">
            <iframe id="rrPdfFrame" src="" title="Dokumen Peserta"></iframe>
        </div>
    </div>
</div>

<!-- ═══ MODAL UPLOAD MULTI-DOK ADMIN ═══ -->
<div id="rrUploadMultiModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/60">
    <div class="rr-modal-bg rounded-xl shadow-2xl w-full max-w-sm border" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between px-5 py-4 border-b rr-modal-header rounded-t-xl">
            <div>
                <div class="text-sm font-bold rr-modal-title">Upload Dokumen Rapat</div>
                <div id="rrMultiDokSub" class="text-[10px] rr-card-sub mt-0.5 truncate max-w-[200px]"></div>
            </div>
            <button onclick="rrCloseUploadMulti()"
                    class="rr-modal-close transition w-7 h-7 rounded-full flex items-center justify-center hover:opacity-70">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="rrMultiDokForm" method="post" enctype="multipart/form-data"
              action="<?= base_url('ruang_rapat/upload_dok_multi') ?>">
            <input type="hidden" name="peminjaman_id" id="rrMultiDokId">
            <div class="p-5 space-y-4">

                <!-- Pilih Jenis -->
                <div>
                    <label class="block text-[10px] font-semibold rr-modal-label mb-1.5">
                        Jenis Dokumen <span class="text-red-400">*</span>
                    </label>
                    <select name="jenis_dokumen" id="rrMultiDokJenis"
                            class="w-full h-10 rounded-lg px-3 border text-xs rr-modal-input
                                   focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">── Pilih jenis ──</option>
                        <option value="Undangan">Undangan</option>
                        <option value="Notulensi">Notulensi</option>
                        <option value="Dokumentasi">Dokumentasi</option>
                        <option value="Daftar Hadir">Daftar Hadir</option>
                        <option value="Lampiran Lain">Lampiran Lain</option>
                    </select>
                </div>

                <!-- Upload File -->
                <div>
                    <label class="block text-[10px] font-semibold rr-modal-label mb-1.5">
                        File PDF <span class="text-red-400">*</span>
                        <span class="font-normal rr-card-sub">(maks. 2 MB)</span>
                    </label>
                    <label for="rrMultiDokFile"
                           class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl
                                  border-2 border-dashed cursor-pointer transition"
                           style="border-color:rgba(139,92,246,0.4);background:rgba(139,92,246,0.05);"
                           onmouseover="this.style.borderColor='rgba(139,92,246,0.8)'"
                           onmouseout="this.style.borderColor='rgba(139,92,246,0.4)'">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5"
                             viewBox="0 0 24 24" style="color:rgba(139,92,246,0.6)">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="text-xs rr-modal-label" id="rrMultiDokLabel">Pilih file PDF</span>
                        <input type="file" id="rrMultiDokFile" name="dokumen_peserta"
                               accept=".pdf,application/pdf" class="sr-only"
                               onchange="rrMultiDokFileChange(this)">
                    </label>
                    <p id="rrMultiDokError" class="hidden mt-1 text-[10px] text-red-400 font-medium"></p>
                </div>

                <p class="text-[10px] rr-card-sub leading-relaxed">
                    Jika jenis dokumen yang sama sudah ada, file lama akan digantikan otomatis.
                </p>
            </div>
            <div class="flex gap-2 px-5 pb-5">
                <button type="button" onclick="rrCloseUploadMulti()"
                        class="flex-1 px-3 py-2 rounded-lg border text-xs rr-modal-cancel transition">Batal</button>
                <button type="submit" id="rrMultiDokSubmit" disabled
                        class="flex-1 px-3 py-2 rounded-lg text-white text-xs font-semibold transition
                               disabled:opacity-50 disabled:cursor-not-allowed"
                        style="background:#7c3aed;">
                    Unggah
                </button>
            </div>
        </form>
    </div>
</div>


<!-- ═══════════════════════════════════════════════════════
     MODALS RUANGAN (tidak berubah, keep as-is)
     ═══════════════════════════════════════════════════════ -->
<div id="modalTambahRuang" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/60">
    <div class="rr-modal-bg rounded-xl shadow-2xl w-full max-w-xl border max-h-[88vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between px-4 py-3 border-b rr-modal-header sticky top-0 rounded-t-xl z-10">
            <h3 class="text-sm font-bold rr-modal-title">Tambah Peminjaman Ruangan</h3>
            <button onclick="closeRuangModal('modalTambahRuang')" class="rr-modal-close transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="<?= base_url('ruang_rapat/simpan') ?>" method="POST">
            <div class="p-4 grid grid-cols-2 gap-3">
                <div class="col-span-2">
                    <label class="block text-[10px] font-semibold rr-modal-label mb-1">Kegiatan / Keperluan <span class="text-red-400">*</span></label>
                    <input type="text" name="keperluan" required placeholder="Contoh: Rapat Evaluasi Tim ZI"
                           class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-[10px] font-semibold rr-modal-label mb-1">Penanggung Jawab <span class="text-red-400">*</span></label>
                    <input type="text" name="nama_peminjam" required placeholder="Nama lengkap"
                           class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-[10px] font-semibold rr-modal-label mb-1">Unit Kerja / Bidang</label>
                    <input type="text" name="unit_kerja" placeholder="Contoh: TI dan Pengolahan"
                           class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-[10px] font-semibold rr-modal-label mb-1">Tanggal <span class="text-red-400">*</span></label>
                    <input type="date" name="tanggal" id="tambah_tanggal" required min="<?= date('Y-m-d') ?>" onchange="muatRuanganAdmin()"
                           class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-[10px] font-semibold rr-modal-label mb-1">Perkiraan Peserta</label>
                    <input type="number" name="jumlah_peserta" min="1" placeholder="0"
                           class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-[10px] font-semibold rr-modal-label mb-1">Waktu Mulai <span class="text-red-400">*</span></label>
                    <input type="time" name="jam_mulai" id="tambah_jam_mulai" required onchange="hitungJamSelesaiTambah()"
                           class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-[10px] font-semibold rr-modal-label mb-1">Durasi <span class="text-red-400">*</span></label>
                    <select name="durasi_rapat" id="tambah_durasi" required onchange="hitungJamSelesaiTambah()"
                            class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Durasi</option>
                        <option value="30">30 Menit</option><option value="60">1 Jam</option>
                        <option value="90">1,5 Jam</option><option value="120">2 Jam</option>
                        <option value="150">2,5 Jam</option><option value="180">3 Jam</option>
                        <option value="240">4 Jam</option><option value="300">5 Jam</option>
                        <option value="360">6 Jam</option><option value="480">8 Jam (Full Day)</option>
                    </select>
                </div>
                <input type="hidden" name="jam_selesai" id="tambah_jam_selesai">
                <div id="tambah_info_jam_selesai" class="col-span-2 hidden flex items-center gap-2 p-2 rounded-lg rr-modal-info border">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         style="color:var(--rr-badge-diset-text)">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-[10px]" style="color:var(--rr-badge-diset-text)">Jam selesai: <strong id="tambah_tampil_jam_selesai">--:--</strong> WIB</p>
                </div>
                <div class="col-span-2">
                    <label class="block text-[10px] font-semibold rr-modal-label mb-1">Ruangan / Lokasi <span class="text-red-400">*</span></label>
                    <select name="ruangan_id" id="tambah_ruangan_id" required
                            class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">── Pilih tanggal & jam dulu ──</option>
                    </select>
                    <div id="tambah_info_ruangan" class="hidden mt-1.5 p-2 rounded-lg border text-[10px] leading-relaxed rr-modal-info"
                         style="color:var(--rr-badge-live-text)"></div>
                    <div id="tambah_jadwal_ruangan" class="hidden mt-1.5 p-2 rounded-lg border text-[10px] rr-modal-info"
                         style="color:var(--rr-badge-tunggu-text)"></div>
                </div>
                <div>
                    <label class="block text-[10px] font-semibold rr-modal-label mb-1">Perlu Snack Corner?</label>
                    <select name="snack_corner" id="snack_corner_tambah" onchange="toggleSnack(this,'jumlah_snack_tambah')"
                            class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="Tidak">Tidak</option><option value="Ya">Ya</option>
                    </select>
                </div>
                <div id="jumlah_snack_tambah" class="hidden">
                    <label class="block text-[10px] font-semibold rr-modal-label mb-1">Jumlah Snack (porsi)</label>
                    <input type="number" name="jumlah_snack" min="1" placeholder="0"
                           class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="col-span-2">
                    <label class="block text-[10px] font-semibold rr-modal-label mb-1">Catatan</label>
                    <textarea name="catatan_peminjam" rows="2"
                              class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                </div>
            </div>
            <div class="flex gap-2 px-4 pb-4">
                <button type="button" onclick="closeRuangModal('modalTambahRuang')"
                        class="flex-1 px-3 py-2 border rounded-lg rr-modal-cancel transition text-xs">Batal</button>
                <button type="submit"
                        class="flex-1 px-3 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg transition font-semibold text-xs">Ajukan Peminjaman</button>
            </div>
        </form>
    </div>
</div>

<div id="modalEditRuang" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/60">
    <div class="rr-modal-bg rounded-xl shadow-2xl w-full max-w-xl border max-h-[88vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between px-4 py-3 border-b rr-modal-header sticky top-0 rounded-t-xl z-10">
            <h3 class="text-sm font-bold rr-modal-title">Edit Peminjaman Ruangan</h3>
            <button onclick="closeRuangModal('modalEditRuang')" class="rr-modal-close transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="<?= base_url('ruang_rapat/update') ?>" method="POST">
            <input type="hidden" name="id" id="edit_id">
            <div class="p-4 grid grid-cols-2 gap-3">
                <div class="col-span-2"><label class="block text-[10px] font-semibold rr-modal-label mb-1">Keperluan</label><input type="text" name="keperluan" id="edit_keperluan" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div><label class="block text-[10px] font-semibold rr-modal-label mb-1">Penanggung Jawab</label><input type="text" name="nama_peminjam" id="edit_nama" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div><label class="block text-[10px] font-semibold rr-modal-label mb-1">Unit Kerja</label><input type="text" name="unit_kerja" id="edit_unit_kerja" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div><label class="block text-[10px] font-semibold rr-modal-label mb-1">Tanggal</label><input type="date" name="tanggal" id="edit_tanggal" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div><label class="block text-[10px] font-semibold rr-modal-label mb-1">Peserta</label><input type="number" name="jumlah_peserta" id="edit_jumlah_peserta" min="1" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div><label class="block text-[10px] font-semibold rr-modal-label mb-1">Jam Mulai</label><input type="time" name="jam_mulai" id="edit_jam_mulai" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div><label class="block text-[10px] font-semibold rr-modal-label mb-1">Jam Selesai</label><input type="time" name="jam_selesai" id="edit_jam_selesai" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div><label class="block text-[10px] font-semibold rr-modal-label mb-1">Snack Corner</label><select name="snack_corner" id="edit_snack_corner" onchange="toggleSnack(this,'edit_jumlah_snack_wrap')" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500"><option value="Tidak">Tidak</option><option value="Ya">Ya</option></select></div>
                <div id="edit_jumlah_snack_wrap"><label class="block text-[10px] font-semibold rr-modal-label mb-1">Jumlah Snack</label><input type="number" name="jumlah_snack" id="edit_jumlah_snack" min="1" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div><label class="block text-[10px] font-semibold rr-modal-label mb-1">Status</label><select name="status" id="edit_status" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500"><option value="Menunggu">Menunggu</option><option value="Disetujui">Disetujui</option><option value="Ditolak">Ditolak</option><option value="Selesai">Selesai</option></select></div>
                <div><label class="block text-[10px] font-semibold rr-modal-label mb-1">Selesai</label><select name="selesai" id="edit_selesai" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500"><option value="Belum">Belum</option><option value="Ya">Ya</option></select></div>
                <div class="col-span-2"><label class="block text-[10px] font-semibold rr-modal-label mb-1">Catatan Peminjam</label><textarea name="catatan_peminjam" id="edit_catatan_peminjam" rows="2" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea></div>
                <div class="col-span-2"><label class="block text-[10px] font-semibold rr-modal-label mb-1">Catatan Admin</label><textarea name="catatan_admin" id="edit_catatan_admin" rows="2" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea></div>
            </div>
            <div class="flex gap-2 px-4 pb-4">
                <button type="button" onclick="closeRuangModal('modalEditRuang')"
                        class="flex-1 px-3 py-2 border rounded-lg rr-modal-cancel transition text-xs">Batal</button>
                <button type="submit"
                        class="flex-1 px-3 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg transition font-semibold text-xs">Simpan</button>
            </div>
        </form>
    </div>
</div>


<!-- MODALS KENDARAAN -->
<div id="modalTambahKend" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/60">
    <div class="rr-modal-bg rounded-xl shadow-2xl w-full max-w-xl border max-h-[88vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between px-4 py-3 border-b rr-modal-header sticky top-0 rounded-t-xl z-10">
            <h3 class="text-sm font-bold rr-modal-title">Tambah Peminjaman Kendaraan</h3>
            <button onclick="closeKendModal('modalTambahKend')" class="rr-modal-close transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="<?= base_url('ruang_rapat/simpan_kendaraan') ?>" method="POST">
            <div class="p-4 grid grid-cols-2 gap-3">
                <div><label class="block text-[10px] font-semibold rr-modal-label mb-1">Nama Peminjam <span class="text-red-400">*</span></label><input type="text" name="nama_peminjam" required class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div><label class="block text-[10px] font-semibold rr-modal-label mb-1">Unit Kerja</label><input type="text" name="unit_kerja" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div class="col-span-2">
                    <label class="block text-[10px] font-semibold rr-modal-label mb-1">Kendaraan <span class="text-red-400">*</span></label>
                    <select name="kendaraan_id" required class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">── Pilih Kendaraan ──</option>
                        <?php if (!empty($daftar_kendaraan)): foreach ($daftar_kendaraan as $kv): ?>
                        <option value="<?= $kv['id'] ?>" <?= $kv['status']!=='Tersedia'?'disabled':'' ?>>
                            <?= $kv['jenis']==='Mobil'?'🚗':'🏍️' ?> <?= htmlspecialchars($kv['nama_kendaraan']) ?> (<?= $kv['nomor_polisi'] ?>)<?= $kv['status']!=='Tersedia'?' — '.$kv['status']:'' ?>
                        </option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>
                <div class="col-span-2"><label class="block text-[10px] font-semibold rr-modal-label mb-1">Keperluan <span class="text-red-400">*</span></label><input type="text" name="keperluan" required class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div class="col-span-2"><label class="block text-[10px] font-semibold rr-modal-label mb-1">Tujuan (Lokasi)</label><input type="text" name="tujuan" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div><label class="block text-[10px] font-semibold rr-modal-label mb-1">Tgl Pinjam <span class="text-red-400">*</span></label><input type="date" name="tanggal_pinjam" required min="<?= date('Y-m-d') ?>" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div><label class="block text-[10px] font-semibold rr-modal-label mb-1">Tgl Kembali <span class="text-red-400">*</span></label><input type="date" name="tanggal_kembali" required min="<?= date('Y-m-d') ?>" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div><label class="block text-[10px] font-semibold rr-modal-label mb-1">Pengemudi</label><input type="text" name="pengemudi" placeholder="Nama / Sendiri" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div><label class="block text-[10px] font-semibold rr-modal-label mb-1">No. HP</label><input type="text" name="no_hp" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div class="col-span-2"><label class="block text-[10px] font-semibold rr-modal-label mb-1">Catatan</label><textarea name="catatan" rows="2" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea></div>
            </div>
            <div class="flex gap-2 px-4 pb-4">
                <button type="button" onclick="closeKendModal('modalTambahKend')"
                        class="flex-1 px-3 py-2 border rounded-lg rr-modal-cancel transition text-xs">Batal</button>
                <button type="submit"
                        class="flex-1 px-3 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg transition font-semibold text-xs">Ajukan</button>
            </div>
        </form>
    </div>
</div>

<div id="modalEditKend" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/60">
    <div class="rr-modal-bg rounded-xl shadow-2xl w-full max-w-xl border max-h-[88vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between px-4 py-3 border-b rr-modal-header sticky top-0 rounded-t-xl z-10">
            <h3 class="text-sm font-bold rr-modal-title">Edit Peminjaman Kendaraan</h3>
            <button onclick="closeKendModal('modalEditKend')" class="rr-modal-close transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="<?= base_url('ruang_rapat/update_kendaraan') ?>" method="POST">
            <input type="hidden" name="id" id="kend_edit_id">
            <div class="p-4 grid grid-cols-2 gap-3">
                <div><label class="block text-[10px] font-semibold rr-modal-label mb-1">Nama Peminjam</label><input type="text" name="nama_peminjam" id="kend_edit_nama" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div><label class="block text-[10px] font-semibold rr-modal-label mb-1">Unit Kerja</label><input type="text" name="unit_kerja" id="kend_edit_unit" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div class="col-span-2"><label class="block text-[10px] font-semibold rr-modal-label mb-1">Keperluan</label><input type="text" name="keperluan" id="kend_edit_keperluan" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div class="col-span-2"><label class="block text-[10px] font-semibold rr-modal-label mb-1">Tujuan</label><input type="text" name="tujuan" id="kend_edit_tujuan" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div><label class="block text-[10px] font-semibold rr-modal-label mb-1">Tgl Pinjam</label><input type="date" name="tanggal_pinjam" id="kend_edit_tgl_pinjam" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div><label class="block text-[10px] font-semibold rr-modal-label mb-1">Tgl Kembali</label><input type="date" name="tanggal_kembali" id="kend_edit_tgl_kembali" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div><label class="block text-[10px] font-semibold rr-modal-label mb-1">Pengemudi</label><input type="text" name="pengemudi" id="kend_edit_pengemudi" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div><label class="block text-[10px] font-semibold rr-modal-label mb-1">Status</label><select name="status" id="kend_edit_status" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500"><option value="Menunggu">Menunggu</option><option value="Disetujui">Disetujui</option><option value="Ditolak">Ditolak</option><option value="Selesai">Selesai</option></select></div>
                <div class="col-span-2"><label class="block text-[10px] font-semibold rr-modal-label mb-1">Catatan Admin</label><textarea name="catatan_admin" id="kend_edit_catatan" rows="2" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea></div>
            </div>
            <div class="flex gap-2 px-4 pb-4">
                <button type="button" onclick="closeKendModal('modalEditKend')"
                        class="flex-1 px-3 py-2 border rounded-lg rr-modal-cancel transition text-xs">Batal</button>
                <button type="submit"
                        class="flex-1 px-3 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg transition font-semibold text-xs">Simpan</button>
            </div>
        </form>
    </div>
</div>


<!-- MODALS BMN -->
<div id="bmn_modalTambah" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/60">
    <div class="rr-modal-bg rounded-xl shadow-2xl w-full max-w-md border max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between px-4 py-3 border-b rr-modal-header sticky top-0 rounded-t-xl z-10">
            <h3 class="text-sm font-bold rr-modal-title">Ajukan Peminjaman BMN</h3>
            <button onclick="bmnCloseModal('bmn_modalTambah')" class="rr-modal-close transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="<?= base_url('ruang_rapat/simpan_bmn') ?>" method="POST">
            <div class="p-4 space-y-3">
                <div>
                    <label class="block text-[10px] font-semibold rr-modal-label mb-1">Barang BMN <span class="text-red-400">*</span></label>
                    <select name="bmn_id" required class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">-- Pilih Barang --</option>
                        <?php if (!empty($daftar_bmn)): foreach ($daftar_bmn as $b): ?>
                        <option value="<?= $b['id'] ?>">
                            <?= htmlspecialchars($b['nama_barang']) ?>
                            <?= !empty($b['merek']) ? ' – ' . $b['merek'] : '' ?>
                            (<?= $b['kode_barang'] ?>/<?= $b['nup'] ?>) — <?= $b['kondisi'] ?>
                        </option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="block text-[10px] font-semibold rr-modal-label mb-1">Nama Peminjam <span class="text-red-400">*</span></label><input type="text" name="nama_peminjam" required class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-purple-500"></div>
                    <div><label class="block text-[10px] font-semibold rr-modal-label mb-1">Unit Kerja</label><input type="text" name="unit_kerja" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-purple-500"></div>
                </div>
                <div><label class="block text-[10px] font-semibold rr-modal-label mb-1">Tujuan Peminjaman <span class="text-red-400">*</span></label><textarea name="tujuan_peminjaman" required rows="2" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-purple-500 resize-none"></textarea></div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="block text-[10px] font-semibold rr-modal-label mb-1">Tgl Pinjam <span class="text-red-400">*</span></label><input type="date" name="tanggal_pinjam" required min="<?= date('Y-m-d') ?>" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-purple-500"></div>
                    <div><label class="block text-[10px] font-semibold rr-modal-label mb-1">Rencana Kembali <span class="text-red-400">*</span></label><input type="date" name="tanggal_kembali" required min="<?= date('Y-m-d') ?>" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-purple-500"></div>
                </div>
                <div><label class="block text-[10px] font-semibold rr-modal-label mb-1">Catatan</label><textarea name="catatan" rows="2" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-purple-500 resize-none"></textarea></div>
            </div>
            <div class="flex gap-2 px-4 pb-4">
                <button type="button" onclick="bmnCloseModal('bmn_modalTambah')"
                        class="flex-1 px-3 py-2 border rounded-lg rr-modal-cancel transition text-xs">Batal</button>
                <button type="submit"
                        class="flex-1 px-3 py-2 bg-purple-600 hover:bg-purple-500 text-white rounded-lg transition font-semibold text-xs">Ajukan</button>
            </div>
        </form>
    </div>
</div>

<div id="bmn_modalEdit" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/60">
    <div class="rr-modal-bg rounded-xl shadow-2xl w-full max-w-md border max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between px-4 py-3 border-b rr-modal-header sticky top-0 rounded-t-xl z-10">
            <h3 class="text-sm font-bold rr-modal-title">Edit Peminjaman BMN</h3>
            <button onclick="bmnCloseModal('bmn_modalEdit')" class="rr-modal-close transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="<?= base_url('ruang_rapat/update_bmn') ?>" method="POST">
            <input type="hidden" name="id" id="ebmn_id">
            <div class="p-4 space-y-3">
                <div class="rounded-lg px-3 py-2 border rr-modal-info">
                    <div class="text-[10px] rr-modal-label">Barang BMN</div>
                    <div id="ebmn_nama_barang" class="text-xs font-semibold rr-modal-title">–</div>
                    <div id="ebmn_merek_display" class="text-[10px] rr-card-sub"></div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="block text-[10px] font-semibold rr-modal-label mb-1">Nama Peminjam</label><input type="text" name="nama_peminjam" id="ebmn_nama_peminjam" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-purple-500"></div>
                    <div><label class="block text-[10px] font-semibold rr-modal-label mb-1">Unit Kerja</label><input type="text" name="unit_kerja" id="ebmn_unit_kerja" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-purple-500"></div>
                </div>
                <div><label class="block text-[10px] font-semibold rr-modal-label mb-1">Tujuan Peminjaman</label><textarea name="tujuan_peminjaman" id="ebmn_tujuan" rows="2" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-purple-500 resize-none"></textarea></div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="block text-[10px] font-semibold rr-modal-label mb-1">Tgl Pinjam</label><input type="date" name="tanggal_pinjam" id="ebmn_tgl_pinjam" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-purple-500"></div>
                    <div><label class="block text-[10px] font-semibold rr-modal-label mb-1">Rencana Kembali</label><input type="date" name="tanggal_kembali" id="ebmn_tgl_kembali" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-purple-500"></div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="block text-[10px] font-semibold rr-modal-label mb-1">Status</label><select name="status" id="ebmn_status" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-purple-500"><option value="Menunggu">Menunggu</option><option value="Disetujui">Disetujui</option><option value="Ditolak">Ditolak</option><option value="Selesai">Selesai</option></select></div>
                    <div><label class="block text-[10px] font-semibold rr-modal-label mb-1">Selesai?</label><select name="selesai" id="ebmn_selesai" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-purple-500"><option value="Belum">Belum</option><option value="Ya">Ya</option></select></div>
                </div>
                <div><label class="block text-[10px] font-semibold rr-modal-label mb-1">Catatan Peminjam</label><textarea name="catatan" id="ebmn_catatan" rows="2" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-purple-500 resize-none"></textarea></div>
                <div><label class="block text-[10px] font-semibold rr-modal-label mb-1">Catatan Admin</label><textarea name="catatan_admin" id="ebmn_catatan_admin" rows="2" class="w-full px-2.5 py-1.5 rounded-lg border text-xs rr-modal-input focus:outline-none focus:ring-2 focus:ring-purple-500 resize-none"></textarea></div>
            </div>
            <div class="flex gap-2 px-4 pb-4">
                <button type="button" onclick="bmnCloseModal('bmn_modalEdit')"
                        class="flex-1 px-3 py-2 border rounded-lg rr-modal-cancel transition text-xs">Batal</button>
                <button type="submit"
                        class="flex-1 px-3 py-2 bg-amber-600 hover:bg-amber-500 text-white rounded-lg transition font-semibold text-xs">Simpan</button>
            </div>
        </form>
    </div>
</div>


<!-- ═══════════════════════════════════════════════════════
     JAVASCRIPT
     ═══════════════════════════════════════════════════════ -->
<script>
/* ── MODAL HELPERS ── */
function openRuangModal(id)  { document.getElementById(id).classList.remove('hidden'); }
function closeRuangModal(id) { document.getElementById(id).classList.add('hidden'); }
function openKendModal(id)   { document.getElementById(id).classList.remove('hidden'); }
function closeKendModal(id)  { document.getElementById(id).classList.add('hidden'); }
function bmnOpenModal(id)    { document.getElementById(id).classList.remove('hidden'); }
function bmnCloseModal(id)   { document.getElementById(id).classList.add('hidden'); }

/* ── BACKDROP & ESC ── */
var allModals = ['modalTambahRuang','modalEditRuang','modalTambahKend','modalEditKend',
                 'bmn_modalTambah','bmn_modalEdit','rrUploadMultiModal'];
allModals.forEach(function(id) {
    var el = document.getElementById(id);
    if (el) el.addEventListener('click', function(e) { if (e.target === el) el.classList.add('hidden'); });
});
document.addEventListener('keydown', function(e) {
    if (e.key !== 'Escape') return;
    allModals.forEach(function(id) { var el = document.getElementById(id); if (el) el.classList.add('hidden'); });
    rrClosePdf();
});

/* ── PDF VIEWER ── */
function rrOpenPdf(url, title) {
    document.getElementById('rrPdfFrame').src   = url;
    document.getElementById('rrPdfDownload').href = url;
    document.getElementById('rrPdfTitle').textContent = '📄 ' + (title || 'Dokumen Peserta');
    document.getElementById('rrPdfOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function rrClosePdf() {
    document.getElementById('rrPdfOverlay').classList.remove('open');
    document.getElementById('rrPdfFrame').src = '';
    document.body.style.overflow = '';
}
document.getElementById('rrPdfOverlay').addEventListener('click', function(e) {
    if (e.target === this) rrClosePdf();
});


/* ── JAM SELESAI OTOMATIS ── */
function hitungJamSelesaiTambah() {
    var mulai  = document.getElementById('tambah_jam_mulai').value;
    var durasi = parseInt(document.getElementById('tambah_durasi').value);
    var info   = document.getElementById('tambah_info_jam_selesai');
    var hidden = document.getElementById('tambah_jam_selesai');
    var tampil = document.getElementById('tambah_tampil_jam_selesai');
    if (!mulai || !durasi) { info.classList.add('hidden'); hidden.value = ''; return; }
    var parts = mulai.split(':');
    var total = parseInt(parts[0]) * 60 + parseInt(parts[1]) + durasi;
    var hasil = String(Math.floor(total / 60) % 24).padStart(2, '0') + ':' + String(total % 60).padStart(2, '0');
    hidden.value = hasil; tampil.textContent = hasil; info.classList.remove('hidden');
    muatRuanganAdmin();
}

/* ── LOAD RUANGAN AJAX ── */
async function muatRuanganAdmin() {
    var tanggal    = document.getElementById('tambah_tanggal')?.value;
    var jamMulai   = document.getElementById('tambah_jam_mulai')?.value;
    var jamSelesai = document.getElementById('tambah_jam_selesai')?.value;
    var sel        = document.getElementById('tambah_ruangan_id');
    var infoEl     = document.getElementById('tambah_info_ruangan');
    var jadwalEl   = document.getElementById('tambah_jadwal_ruangan');
    if (!sel || !tanggal) return;
    sel.innerHTML = '<option value="">── Memuat... ──</option>';
    if (infoEl) infoEl.classList.add('hidden');
    if (jadwalEl) jadwalEl.classList.add('hidden');
    var url = '<?= base_url('user_ticket/get_ruangan_aktif') ?>?tanggal=' + encodeURIComponent(tanggal);
    if (jamMulai && jamSelesai) url += '&jam_mulai=' + encodeURIComponent(jamMulai) + '&jam_selesai=' + encodeURIComponent(jamSelesai);
    try {
        var res  = await fetch(url);
        var list = await res.json();
        if (!list.length) { sel.innerHTML = '<option value="">Tidak ada ruangan tersedia</option>'; return; }
        sel.innerHTML = '<option value="">── Pilih ruangan ──</option>';
        list.forEach(function(r) {
            var o = document.createElement('option');
            o.value = r.id; o.dataset.kapasitas = r.kapasitas;
            o.dataset.lokasi = r.lokasi || '-'; o.dataset.fasilitas = r.fasilitas || '';
            o.dataset.jadwal = JSON.stringify(r.jadwal_terpakai || []);
            o.textContent = (r.tersedia ? '✓ ' : '✗ ') + r.nama_ruangan + '  (Kap. ' + r.kapasitas + ' org)';
            if (!r.tersedia) { o.disabled = true; }
            sel.appendChild(o);
        });
    } catch(e) { sel.innerHTML = '<option value="">Gagal memuat</option>'; }
}
document.getElementById('tambah_ruangan_id')?.addEventListener('change', function() {
    var infoEl = document.getElementById('tambah_info_ruangan');
    var jadwalEl = document.getElementById('tambah_jadwal_ruangan');
    if (!this.value) { infoEl?.classList.add('hidden'); jadwalEl?.classList.add('hidden'); return; }
    var o = this.options[this.selectedIndex];
    if (infoEl) {
        infoEl.innerHTML = '📍 ' + o.dataset.lokasi + ' &nbsp;·&nbsp; 👥 Kap. ' + o.dataset.kapasitas + ' org' + (o.dataset.fasilitas ? '<br>🔧 ' + o.dataset.fasilitas : '');
        infoEl.classList.remove('hidden');
    }
    var jadwal = JSON.parse(o.dataset.jadwal || '[]');
    if (jadwalEl) {
        if (jadwal.length) {
            var h = '<strong class="block mb-1">📋 Jadwal:</strong>';
            jadwal.forEach(function(j) { h += '<span class="block">' + (j.status === 'Disetujui' ? '🔴' : '🟡') + ' ' + j.jam_mulai + '–' + j.jam_selesai + ' ' + j.keperluan + '</span>'; });
            jadwalEl.innerHTML = h; jadwalEl.classList.remove('hidden');
        } else { jadwalEl.classList.add('hidden'); }
    }
});

/* ── SNACK TOGGLE ── */
function toggleSnack(sel, wrapId) {
    var el = document.getElementById(wrapId);
    if (el) el.classList.toggle('hidden', sel.value !== 'Ya');
}

/* ── OPEN EDIT RUANGAN ── */
function openRuangEdit(p) {
    var sv = function(id, v) { var e = document.getElementById(id); if (e) e.value = v || ''; };
    sv('edit_id', p.id); sv('edit_keperluan', p.keperluan); sv('edit_nama', p.nama_peminjam);
    sv('edit_unit_kerja', p.unit_kerja); sv('edit_tanggal', p.tanggal);
    sv('edit_jam_mulai', p.jam_mulai); sv('edit_jam_selesai', p.jam_selesai);
    sv('edit_jumlah_peserta', p.jumlah_peserta); sv('edit_snack_corner', p.snack_corner);
    sv('edit_jumlah_snack', p.jumlah_snack); sv('edit_status', p.status);
    sv('edit_selesai', p.selesai); sv('edit_catatan_peminjam', p.catatan_peminjam);
    sv('edit_catatan_admin', p.catatan_admin);
    var sc = document.getElementById('edit_snack_corner'); if (sc) toggleSnack(sc, 'edit_jumlah_snack_wrap');
    openRuangModal('modalEditRuang');
}

/* ── OPEN EDIT KENDARAAN ── */
function openKendEdit(k) {
    var sv = function(id, v) { var e = document.getElementById(id); if (e) e.value = v || ''; };
    sv('kend_edit_id', k.id); sv('kend_edit_nama', k.nama_peminjam); sv('kend_edit_unit', k.unit_kerja);
    sv('kend_edit_keperluan', k.keperluan); sv('kend_edit_tujuan', k.tujuan);
    sv('kend_edit_tgl_pinjam', k.tanggal_pinjam); sv('kend_edit_tgl_kembali', k.tanggal_kembali);
    sv('kend_edit_pengemudi', k.pengemudi); sv('kend_edit_status', k.status);
    sv('kend_edit_catatan', k.catatan_admin);
    openKendModal('modalEditKend');
}

/* ── OPEN EDIT BMN ── */
function bmnOpenEdit(r) {
    var sv = function(id, v) { var e = document.getElementById(id); if (e) e.value = v || ''; };
    sv('ebmn_id', r.id); sv('ebmn_nama_peminjam', r.nama_peminjam); sv('ebmn_unit_kerja', r.unit_kerja);
    sv('ebmn_tujuan', r.tujuan_peminjaman); sv('ebmn_tgl_pinjam', r.tanggal_pinjam);
    sv('ebmn_tgl_kembali', r.tanggal_kembali); sv('ebmn_status', r.status);
    sv('ebmn_selesai', r.selesai); sv('ebmn_catatan', r.catatan);
    sv('ebmn_catatan_admin', r.catatan_admin);
    var nb = document.getElementById('ebmn_nama_barang'); if (nb) nb.textContent = r.nama_barang || '–';
    var mb = document.getElementById('ebmn_merek_display'); if (mb) mb.textContent = r.merek || '';
    bmnOpenModal('bmn_modalEdit');
}

/* ── TOLAK BMN ── */
function bmnTolak(id) {
    Swal.fire({
        title: 'Tolak Peminjaman?', input: 'textarea',
        inputLabel: 'Alasan penolakan (opsional)',
        showCancelButton: true, confirmButtonText: 'Ya, Tolak', cancelButtonText: 'Batal',
        confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280', reverseButtons: true
    }).then(function(res) {
        if (res.isConfirmed) {
            var url = '<?= base_url('ruang_rapat/tolak_bmn/') ?>' + id;
            if (res.value) url += '?catatan=' + encodeURIComponent(res.value);
            window.location.href = url;
        }
    });
}

/* ── HAPUS BMN ── */
function bmnHapus(id, nama) {
    Swal.fire({
        title: 'Hapus Data?', text: '"' + nama + '" akan dihapus permanen.', icon: 'warning',
        showCancelButton: true, confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal',
        confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280', reverseButtons: true
    }).then(function(r) { if (r.isConfirmed) window.location.href = '<?= base_url('ruang_rapat/hapus_bmn/') ?>' + id; });
}

/* ── CLIENT-SIDE FILTER (untuk search/status di halaman aktif) ── */

/* ── CLIENT-SIDE FILTER KENDARAAN ── */
function filterKend() {
    var s  = (document.getElementById('searchKend')?.value || '').toLowerCase();
    var st = document.getElementById('filterStatusKend')?.value || '';
    var v = 0, t = 0;
    document.querySelectorAll('.row-kend').forEach(function(row) {
        t++;
        var ok = row.dataset.search.includes(s) && (!st || row.dataset.status === st);
        row.style.display = ok ? '' : 'none'; if (ok) v++;
    });
    var el = document.getElementById('jumlahTampilKend');
    if (el) el.textContent = v < t ? 'Filter: ' + v + ' dari ' + t + ' data' : t + ' data';
}
function resetFilterKend() {
    ['searchKend','filterStatusKend'].forEach(function(id){ var e = document.getElementById(id); if (e) e.value = ''; });
    filterKend();
}
document.getElementById('searchKend')?.addEventListener('input', filterKend);
document.getElementById('filterStatusKend')?.addEventListener('change', filterKend);

/* ── CLIENT-SIDE FILTER BMN ── */
function filterBmn() {
    var s  = (document.getElementById('bmnSearchInput')?.value || '').toLowerCase();
    var st = document.getElementById('bmnFilterStatus')?.value || '';
    var v = 0, t = 0;
    document.querySelectorAll('.bmn-row').forEach(function(row) {
        t++;
        var ok = row.dataset.search.includes(s) && (!st || row.dataset.status === st);
        row.style.display = ok ? '' : 'none'; if (ok) v++;
    });
    var el = document.getElementById('bmnJumlahTampil');
    if (el) el.textContent = v < t ? 'Filter: ' + v + ' dari ' + t + ' data' : t + ' data';
}
document.getElementById('bmnSearchInput')?.addEventListener('input', filterBmn);
document.getElementById('bmnFilterStatus')?.addEventListener('change', filterBmn);

/* ── INIT COUNT ── */
window.addEventListener('DOMContentLoaded', function() {
    var r = document.querySelectorAll('.row-data').length;
    var k = document.querySelectorAll('.row-kend').length;
    var b = document.querySelectorAll('.bmn-row').length;
    if (document.getElementById('jumlahTampil'))     document.getElementById('jumlahTampil').textContent     = r + ' data di halaman ini';
    if (document.getElementById('jumlahTampilKend')) document.getElementById('jumlahTampilKend').textContent = k + ' data di halaman ini';
    if (document.getElementById('bmnJumlahTampil'))  document.getElementById('bmnJumlahTampil').textContent  = b + ' data di halaman ini';
});

/* ── UPLOAD MULTI-DOK ADMIN ── */
function rrOpenUploadMulti(id, nama) {
    document.getElementById('rrMultiDokId').value = id;
    document.getElementById('rrMultiDokSub').textContent = nama;
    document.getElementById('rrMultiDokJenis').value = '';
    document.getElementById('rrMultiDokLabel').textContent = 'Pilih file PDF';
    document.getElementById('rrMultiDokSubmit').disabled = true;
    document.getElementById('rrMultiDokFile').value = '';
    var err = document.getElementById('rrMultiDokError');
    err.textContent = ''; err.classList.add('hidden');
    document.getElementById('rrUploadMultiModal').classList.remove('hidden');
}
function rrCloseUploadMulti() {
    document.getElementById('rrUploadMultiModal').classList.add('hidden');
}
function rrMultiDokFileChange(input) {
    var err = document.getElementById('rrMultiDokError');
    var btn = document.getElementById('rrMultiDokSubmit');
    err.textContent = ''; err.classList.add('hidden');
    if (!input.files || !input.files[0]) { btn.disabled = true; return; }
    var file = input.files[0];
    if (file.type !== 'application/pdf' && !file.name.toLowerCase().endsWith('.pdf')) {
        err.textContent = 'Hanya file PDF yang diperbolehkan.';
        err.classList.remove('hidden'); btn.disabled = true; return;
    }
    if (file.size > 2 * 1024 * 1024) {
        err.textContent = 'Ukuran melebihi 2 MB (' + (file.size/1048576).toFixed(1) + ' MB).';
        err.classList.remove('hidden'); btn.disabled = true; return;
    }
    document.getElementById('rrMultiDokLabel').textContent = file.name;
    var jenis = document.getElementById('rrMultiDokJenis').value;
    btn.disabled = !jenis;
}
document.getElementById('rrMultiDokJenis')?.addEventListener('change', function() {
    var fileEl = document.getElementById('rrMultiDokFile');
    document.getElementById('rrMultiDokSubmit').disabled =
        !(this.value && fileEl.files && fileEl.files[0]);
});
document.getElementById('rrMultiDokForm')?.addEventListener('submit', function() {
    var btn = document.getElementById('rrMultiDokSubmit');
    btn.disabled = true; btn.textContent = '⏳ Mengunggah...';
});
// Tambah ke backdrop handler
document.getElementById('rrUploadMultiModal')?.addEventListener('click', function(e) {
    if (e.target === this) rrCloseUploadMulti();
});
/* ── Sync per_page hidden input saat klik tombol per-page ── */
document.querySelectorAll('.rr-page-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var url = new URL(this.href, window.location.origin);
        var pp = url.searchParams.get('per_page');
        var hidden = document.querySelector('#formFilterRuangan input[name="per_page"]');
        if (pp && hidden) hidden.value = pp;
    });
});
</script>