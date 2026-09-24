<?php
// application/views/master_ruang_rapat/index.php
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
    --mrr-bg-card:          #1a2233;
    --mrr-bg-input:         #0f1623;
    --mrr-bg-table:         #1e2a3a;
    --mrr-bg-table-hover:   #263548;
    --mrr-bg-row-hover:     rgba(255,255,255,0.05);
    --mrr-border:           rgba(255,255,255,0.10);
    --mrr-border-5:         rgba(255,255,255,0.05);
    --mrr-text-primary:     #ffffff;
    --mrr-text-secondary:   #d1d5db;
    --mrr-text-muted:       #6b7280;
    --mrr-text-label:       #9ca3af;
    --mrr-th-color:         #9ca3af;
    --mrr-placeholder:      #4b5563;
    /* Badge Aktif */
    --mrr-aktif-bg:         rgba(16,185,129,0.15);
    --mrr-aktif-text:       #34d399;
    --mrr-aktif-ring:       rgba(16,185,129,0.30);
    --mrr-aktif-dot:        #34d399;
    /* Badge Maintenance */
    --mrr-maint-bg:         rgba(234,179,8,0.15);
    --mrr-maint-text:       #fbbf24;
    --mrr-maint-ring:       rgba(234,179,8,0.30);
    --mrr-maint-dot:        #fbbf24;
    /* Badge Tidak Aktif */
    --mrr-off-bg:           rgba(100,116,139,0.15);
    --mrr-off-text:         #94a3b8;
    --mrr-off-ring:         rgba(100,116,139,0.30);
    /* Fasilitas pill */
    --mrr-fas-bg:           rgba(51,65,85,0.60);
    --mrr-fas-text:         #cbd5e1;
    --mrr-fas-border:       rgba(71,85,105,0.40);
    /* Icon ruangan */
    --mrr-icon-bg:          rgba(59,130,246,0.20);
    --mrr-icon-color:       #60a5fa;
    /* Tombol edit */
    --mrr-btn-edit-bg:      rgba(245,158,11,0.10);
    --mrr-btn-edit-text:    #fbbf24;
    --mrr-btn-edit-border:  rgba(245,158,11,0.20);
    --mrr-btn-edit-hover:   rgba(245,158,11,0.20);
    /* Tombol hapus */
    --mrr-btn-del-bg:       rgba(239,68,68,0.10);
    --mrr-btn-del-text:     #f87171;
    --mrr-btn-del-border:   rgba(239,68,68,0.20);
    --mrr-btn-del-hover:    rgba(239,68,68,0.20);
    /* Pagination */
    --mrr-pag-bg:           #1e2a3a;
    --mrr-pag-text:         #d1d5db;
    --mrr-pag-hover:        #263548;
    --mrr-pag-disabled:     #374151;
    /* Upload dropzone */
    --mrr-drop-border:      rgba(255,255,255,0.10);
    --mrr-drop-hover:       rgba(16,185,129,0.40);
}

/* --- Light mode --- */
html.light {
    --mrr-bg-card:          #ffffff;
    --mrr-bg-input:         #f8fafc;
    --mrr-bg-table:         #ffffff;
    --mrr-bg-table-hover:   #f1f5f9;
    --mrr-bg-row-hover:     rgba(0,0,0,0.025);
    --mrr-border:           rgba(0,0,0,0.10);
    --mrr-border-5:         rgba(0,0,0,0.05);
    --mrr-text-primary:     #0f172a;
    --mrr-text-secondary:   #334155;
    --mrr-text-muted:       #94a3b8;
    --mrr-text-label:       #64748b;
    --mrr-th-color:         #64748b;
    --mrr-placeholder:      #94a3b8;
    /* Badge Aktif */
    --mrr-aktif-bg:         rgba(16,185,129,0.12);
    --mrr-aktif-text:       #059669;
    --mrr-aktif-ring:       rgba(16,185,129,0.25);
    --mrr-aktif-dot:        #059669;
    /* Badge Maintenance */
    --mrr-maint-bg:         rgba(217,119,6,0.12);
    --mrr-maint-text:       #d97706;
    --mrr-maint-ring:       rgba(217,119,6,0.25);
    --mrr-maint-dot:        #d97706;
    /* Badge Tidak Aktif */
    --mrr-off-bg:           rgba(100,116,139,0.10);
    --mrr-off-text:         #475569;
    --mrr-off-ring:         rgba(100,116,139,0.20);
    /* Fasilitas pill */
    --mrr-fas-bg:           rgba(226,232,240,0.80);
    --mrr-fas-text:         #475569;
    --mrr-fas-border:       rgba(203,213,225,0.60);
    /* Icon ruangan */
    --mrr-icon-bg:          rgba(59,130,246,0.10);
    --mrr-icon-color:       #2563eb;
    /* Tombol edit */
    --mrr-btn-edit-bg:      rgba(245,158,11,0.08);
    --mrr-btn-edit-text:    #b45309;
    --mrr-btn-edit-border:  rgba(245,158,11,0.20);
    --mrr-btn-edit-hover:   rgba(245,158,11,0.15);
    /* Tombol hapus */
    --mrr-btn-del-bg:       rgba(239,68,68,0.08);
    --mrr-btn-del-text:     #dc2626;
    --mrr-btn-del-border:   rgba(239,68,68,0.20);
    --mrr-btn-del-hover:    rgba(239,68,68,0.15);
    /* Pagination */
    --mrr-pag-bg:           #ffffff;
    --mrr-pag-text:         #475569;
    --mrr-pag-hover:        #f1f5f9;
    --mrr-pag-disabled:     #e2e8f0;
    /* Upload dropzone */
    --mrr-drop-border:      rgba(0,0,0,0.10);
    --mrr-drop-hover:       rgba(16,185,129,0.30);
}

/* ── Apply ke elemen ── */
.mrr-page-title       { color: var(--mrr-text-primary); }
.mrr-page-sub         { color: var(--mrr-text-label); }

.mrr-card             { background-color: var(--mrr-bg-card); border-color: var(--mrr-border); }
.mrr-table-wrap       { background-color: var(--mrr-bg-table); border-color: var(--mrr-border-5); }
.mrr-th               { color: var(--mrr-th-color); border-color: var(--mrr-border); }
.mrr-tr               { border-color: var(--mrr-border-5); }
.mrr-tr:hover         { background-color: var(--mrr-bg-row-hover); }
.mrr-td-no            { color: var(--mrr-text-muted); }
.mrr-td-loc           { color: var(--mrr-text-secondary); }
.mrr-room-name        { color: var(--mrr-text-primary); }
.mrr-room-note        { color: var(--mrr-text-muted); }
.mrr-loc-icon         { color: var(--mrr-text-muted); stroke: var(--mrr-text-muted); }
.mrr-cap-num          { color: var(--mrr-text-secondary); }
.mrr-cap-unit         { color: var(--mrr-text-muted); }

.mrr-icon-wrap        { background-color: var(--mrr-icon-bg); }
.mrr-icon-wrap svg    { color: var(--mrr-icon-color); stroke: var(--mrr-icon-color); }

.mrr-fas-pill         { background-color: var(--mrr-fas-bg); color: var(--mrr-fas-text); border-color: var(--mrr-fas-border); }

.mrr-badge-aktif      { background-color: var(--mrr-aktif-bg); color: var(--mrr-aktif-text); box-shadow: 0 0 0 1px var(--mrr-aktif-ring); }
.mrr-badge-aktif .mrr-dot { background-color: var(--mrr-aktif-dot); }
.mrr-badge-maint      { background-color: var(--mrr-maint-bg); color: var(--mrr-maint-text); box-shadow: 0 0 0 1px var(--mrr-maint-ring); }
.mrr-badge-maint .mrr-dot { background-color: var(--mrr-maint-dot); }
.mrr-badge-off        { background-color: var(--mrr-off-bg);  color: var(--mrr-off-text);  box-shadow: 0 0 0 1px var(--mrr-off-ring); }

.mrr-btn-edit         { background-color: var(--mrr-btn-edit-bg); color: var(--mrr-btn-edit-text); border-color: var(--mrr-btn-edit-border); }
.mrr-btn-edit:hover   { background-color: var(--mrr-btn-edit-hover); }
.mrr-btn-edit svg     { stroke: var(--mrr-btn-edit-text); }
.mrr-btn-del          { background-color: var(--mrr-btn-del-bg); color: var(--mrr-btn-del-text); border-color: var(--mrr-btn-del-border); }
.mrr-btn-del:hover    { background-color: var(--mrr-btn-del-hover); }
.mrr-btn-del svg      { stroke: var(--mrr-btn-del-text); }

.mrr-input            { background-color: var(--mrr-bg-input); border-color: var(--mrr-border); color: var(--mrr-text-primary); }
.mrr-input::placeholder { color: var(--mrr-placeholder); }
.mrr-input:focus      { border-color: #3b82f6; }
.mrr-label            { color: var(--mrr-text-label); }
.mrr-modal-header     { background-color: var(--mrr-bg-card); border-color: var(--mrr-border); }
.mrr-modal-title      { color: var(--mrr-text-primary); }
.mrr-modal-close      { color: var(--mrr-text-label); }
.mrr-modal-close:hover{ color: var(--mrr-text-primary); }
.mrr-modal-body-bg    { background-color: var(--mrr-bg-card); border-color: var(--mrr-border); }
.mrr-btn-batal        { border-color: var(--mrr-border); color: var(--mrr-text-label); }
.mrr-btn-batal:hover  { background-color: var(--mrr-bg-row-hover); }
.mrr-cb               { border-color: var(--mrr-border); background-color: var(--mrr-bg-input); }
.mrr-cb-label         { color: var(--mrr-text-secondary); }

.mrr-pag-info         { color: var(--mrr-text-muted); }
.mrr-pag-btn          { background-color: var(--mrr-pag-bg); border-color: var(--mrr-border); color: var(--mrr-pag-text); }
.mrr-pag-btn:hover:not(:disabled) { background-color: var(--mrr-pag-hover); }
.mrr-pag-btn:disabled { color: var(--mrr-pag-disabled); opacity: .4; }
.mrr-pag-active       { background-color: #2563eb !important; border-color: #2563eb !important; color: #ffffff !important; }

/* SweetAlert2 ikut theme */
.swal2-popup          { background-color: var(--mrr-bg-card)    !important; border: 1.5px solid var(--mrr-border) !important; color: var(--mrr-text-primary) !important; }
.swal2-title          { color: var(--mrr-text-primary)    !important; }
.swal2-html-container { color: var(--mrr-text-secondary)  !important; }

/* Checkbox di light mode */
html.light .mrr-cb { border-color: #cbd5e1; background-color: #f8fafc; }
</style>

<!-- PAGE HEADER -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold mrr-page-title">Master Ruang Rapat</h1>
        <p class="text-sm mrr-page-sub mt-0.5">Kelola data ruang rapat yang tersedia</p>
    </div>
    <button onclick="mrrOpenModal('mrr_modalTambah')"
            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500
                   text-white text-sm font-semibold rounded-lg transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Ruangan
    </button>
</div>

<!-- FILTER BAR -->
<div class="flex flex-wrap gap-3 mb-4">
    <div class="relative flex-1 min-w-[200px]">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 pointer-events-none mrr-loc-icon"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
        </svg>
        <input type="text" id="mrrSearchInput" placeholder="Cari nama / lokasi ruangan..."
               class="w-full pl-9 pr-3 py-2 rounded-lg border text-sm mrr-input
                      focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    <select id="mrrFilterStatus"
            class="px-3 py-2 rounded-lg border text-sm mrr-input
                   focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Semua Status</option>
        <option value="Aktif">Aktif</option>
        <option value="Tidak Aktif">Tidak Aktif</option>
        <option value="Maintenance">Maintenance</option>
    </select>
</div>

<!-- TABLE CARD -->
<div class="mrr-table-wrap rounded-xl border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="tabelRuangan">
            <thead>
                <tr class="border-b mrr-th text-xs uppercase tracking-wider">
                    <th class="px-5 py-3 text-left font-semibold w-10">No</th>
                    <th class="px-5 py-3 text-left font-semibold">Nama Ruangan</th>
                    <th class="px-5 py-3 text-left font-semibold">Lokasi</th>
                    <th class="px-5 py-3 text-left font-semibold">Kapasitas</th>
                    <th class="px-5 py-3 text-left font-semibold">Fasilitas</th>
                    <th class="px-5 py-3 text-left font-semibold">Status</th>
                    <th class="px-5 py-3 text-center font-semibold w-24">Aksi</th>
                </tr>
            </thead>
            <tbody id="mrrTabelBody" class="divide-y mrr-tr">
                <?php if (!empty($ruangan)): ?>
                <?php foreach ($ruangan as $i => $r): ?>
                <tr class="mrr-tr transition row-data"
                    data-search="<?= strtolower($r['nama_ruangan'].' '.$r['lokasi']) ?>"
                    data-status="<?= htmlspecialchars($r['status']) ?>">
                    <td class="px-5 py-3.5 mrr-td-no text-xs row-no"><?= $i + 1 ?></td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg mrr-icon-wrap flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5
                                             M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div>
                                <div class="font-semibold mrr-room-name text-sm"><?= htmlspecialchars($r['nama_ruangan']) ?></div>
                                <?php if (!empty($r['keterangan'])): ?>
                                <div class="text-xs mrr-room-note truncate max-w-[160px]"><?= htmlspecialchars($r['keterangan']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3.5 mrr-td-loc text-sm">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 mrr-loc-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            <?= htmlspecialchars($r['lokasi'] ?? '-') ?>
                        </div>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 mrr-loc-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283
                                         -.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283
                                         .356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="font-semibold mrr-cap-num"><?= (int)$r['kapasitas'] ?></span>
                            <span class="mrr-cap-unit text-xs">orang</span>
                        </div>
                    </td>
                    <td class="px-5 py-3.5">
                        <?php
                        $fas = !empty($r['fasilitas']) ? array_map('trim', explode(',', $r['fasilitas'])) : [];
                        ?>
                        <div class="flex flex-wrap gap-1">
                            <?php foreach (array_slice($fas, 0, 3) as $f): ?>
                            <span class="mrr-fas-pill px-1.5 py-0.5 text-xs rounded border">
                                <?= htmlspecialchars($f) ?>
                            </span>
                            <?php endforeach; ?>
                            <?php if (count($fas) > 3): ?>
                            <span class="mrr-fas-pill px-1.5 py-0.5 text-xs rounded border opacity-60">
                                +<?= count($fas) - 3 ?>
                            </span>
                            <?php endif; ?>
                            <?php if (empty($fas)): ?>
                            <span class="text-xs mrr-room-note italic">–</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="px-5 py-3.5">
                        <?php
                        $badgeCls = [
                            'Aktif'       => 'mrr-badge-aktif',
                            'Tidak Aktif' => 'mrr-badge-off',
                            'Maintenance' => 'mrr-badge-maint',
                        ];
                        $cls = $badgeCls[$r['status']] ?? 'mrr-badge-off';
                        ?>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full <?= $cls ?>">
                            <?php if ($r['status'] === 'Aktif' || $r['status'] === 'Maintenance'): ?>
                                <span class="mrr-dot w-1.5 h-1.5 rounded-full"></span>
                            <?php endif; ?>
                            <?= htmlspecialchars($r['status']) ?>
                        </span>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-center gap-2">
                            <button type="button" onclick='mrrOpenEdit(<?= json_encode($r) ?>)'
                                    class="mrr-btn-edit p-1.5 rounded-lg border transition" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                                             m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button type="button" onclick="mrrHapus(<?= $r['id'] ?>, '<?= addslashes($r['nama_ruangan']) ?>')"
                                    class="mrr-btn-del p-1.5 rounded-lg border transition" title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <tr>
                    <td colspan="7" class="px-5 py-16 text-center">
                        <svg class="w-12 h-12 mx-auto mb-3 mrr-room-note opacity-40" fill="none"
                             stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5
                                     M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <p class="text-sm mrr-room-note">Belum ada data ruang rapat</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- PAGINATION BAR -->
<div class="flex flex-wrap items-center justify-between gap-3 mt-4 px-1">
    <p id="mrrPagInfo" class="text-xs mrr-pag-info"></p>
    <div id="mrrPagButtons" class="flex items-center gap-1 flex-wrap"></div>
</div>


<!-- ===== MODAL TAMBAH ===== -->
<div id="mrr_modalTambah" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/60">
    <div class="mrr-modal-body-bg rounded-xl shadow-2xl w-full max-w-md border
                max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between px-5 py-4 border-b mrr-modal-header
                    sticky top-0 rounded-t-xl">
            <h3 class="text-base font-bold mrr-modal-title">Tambah Ruang Rapat</h3>
            <button type="button" onclick="mrrCloseModal('mrr_modalTambah')" class="mrr-modal-close transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form action="<?= base_url('master_ruang_rapat/simpan') ?>" method="POST">
            <div class="p-5 space-y-4">
                <div>
                    <label class="block text-xs font-semibold mrr-label mb-1.5">
                        Nama Ruangan <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="nama_ruangan" required placeholder="contoh: Ruang Rapat Lantai 2"
                           class="w-full px-3 py-2 rounded-lg border text-sm mrr-input focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold mrr-label mb-1.5">
                            Kapasitas (orang) <span class="text-red-400">*</span>
                        </label>
                        <input type="number" name="kapasitas" required min="1" max="500"
                               class="w-full px-3 py-2 rounded-lg border text-sm mrr-input focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold mrr-label mb-1.5">Lantai / Lokasi</label>
                        <input type="text" name="lokasi" placeholder="contoh: Lantai 2"
                               class="w-full px-3 py-2 rounded-lg border text-sm mrr-input focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold mrr-label mb-2">Fasilitas</label>
                    <div class="grid grid-cols-2 gap-2">
                        <?php foreach(['AC','Proyektor','Whiteboard','WiFi','TV','Microphone','Soundsystem'] as $fas): ?>
                        <label class="flex items-center gap-2 text-sm mrr-cb-label cursor-pointer">
                            <input type="checkbox" name="fasilitas[]" value="<?= $fas ?>"
                                   class="mrr-cb rounded text-blue-500 focus:ring-blue-500 focus:ring-offset-0">
                            <?= $fas ?>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold mrr-label mb-1.5">Status</label>
                    <select name="status" class="w-full px-3 py-2 rounded-lg border text-sm mrr-input focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                        <option value="Maintenance">Maintenance</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold mrr-label mb-1.5">Keterangan</label>
                    <textarea name="keterangan" rows="2"
                              class="w-full px-3 py-2 rounded-lg border text-sm mrr-input focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                </div>
            </div>
            <div class="flex gap-3 px-5 pb-5">
                <button type="button" onclick="mrrCloseModal('mrr_modalTambah')"
                        class="flex-1 px-4 py-2.5 border rounded-lg mrr-btn-batal transition text-sm">Batal</button>
                <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-lg
                               transition font-semibold text-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== MODAL EDIT ===== -->
<div id="mrr_modalEdit" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/60">
    <div class="mrr-modal-body-bg rounded-xl shadow-2xl w-full max-w-md border
                max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between px-5 py-4 border-b mrr-modal-header
                    sticky top-0 rounded-t-xl">
            <h3 class="text-base font-bold mrr-modal-title">Edit Ruang Rapat</h3>
            <button type="button" onclick="mrrCloseModal('mrr_modalEdit')" class="mrr-modal-close transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form action="<?= base_url('master_ruang_rapat/update') ?>" method="POST">
            <input type="hidden" name="id" id="er_id">
            <div class="p-5 space-y-4">
                <div>
                    <label class="block text-xs font-semibold mrr-label mb-1.5">Nama Ruangan</label>
                    <input type="text" name="nama_ruangan" id="er_nama"
                           class="w-full px-3 py-2 rounded-lg border text-sm mrr-input focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold mrr-label mb-1.5">Kapasitas</label>
                        <input type="number" name="kapasitas" id="er_kapasitas" min="1"
                               class="w-full px-3 py-2 rounded-lg border text-sm mrr-input focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold mrr-label mb-1.5">Lokasi</label>
                        <input type="text" name="lokasi" id="er_lokasi"
                               class="w-full px-3 py-2 rounded-lg border text-sm mrr-input focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold mrr-label mb-2">Fasilitas</label>
                    <div class="grid grid-cols-2 gap-2">
                        <?php foreach(['AC','Proyektor','Whiteboard','WiFi','TV','Microphone','Soundsystem'] as $fas): ?>
                        <label class="flex items-center gap-2 text-sm mrr-cb-label cursor-pointer">
                            <input type="checkbox" name="fasilitas[]" value="<?= $fas ?>"
                                   class="mrr-fas-edit mrr-cb rounded text-blue-500 focus:ring-blue-500 focus:ring-offset-0">
                            <?= $fas ?>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold mrr-label mb-1.5">Status</label>
                    <select name="status" id="er_status"
                            class="w-full px-3 py-2 rounded-lg border text-sm mrr-input focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                        <option value="Maintenance">Maintenance</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold mrr-label mb-1.5">Keterangan</label>
                    <textarea name="keterangan" id="er_keterangan" rows="2"
                              class="w-full px-3 py-2 rounded-lg border text-sm mrr-input focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                </div>
            </div>
            <div class="flex gap-3 px-5 pb-5">
                <button type="button" onclick="mrrCloseModal('mrr_modalEdit')"
                        class="flex-1 px-4 py-2.5 border rounded-lg mrr-btn-batal transition text-sm">Batal</button>
                <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-amber-600 hover:bg-amber-500 text-white rounded-lg
                               transition font-semibold text-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== SCRIPTS ===== -->
<script>
function mrrOpenModal(id)  { document.getElementById(id).classList.remove('hidden'); }
function mrrCloseModal(id) { document.getElementById(id).classList.add('hidden'); }

document.addEventListener('DOMContentLoaded', function () {
    ['mrr_modalTambah', 'mrr_modalEdit'].forEach(function (id) {
        var el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('click', function (e) { if (e.target === el) mrrCloseModal(id); });
    });
    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        ['mrr_modalTambah', 'mrr_modalEdit'].forEach(function (id) {
            var el = document.getElementById(id);
            if (el && !el.classList.contains('hidden')) mrrCloseModal(id);
        });
    });
    mrrFilter();
});

// ── Filter + Search + Pagination ──────────────────────────
var MRR_PAGE_SIZE  = 10;
var mrrCurrentPage = 1;
var mrrVisibleRows = [];

function mrrFilter() {
    var keyword = (document.getElementById('mrrSearchInput').value || '').toLowerCase().trim();
    var status  = document.getElementById('mrrFilterStatus').value;
    var rows    = Array.from(document.querySelectorAll('#mrrTabelBody .row-data'));
    mrrVisibleRows = rows.filter(function (row) {
        var ok = (!keyword || row.dataset.search.indexOf(keyword) !== -1)
              && (!status  || row.dataset.status === status);
        row.style.display = 'none';
        return ok;
    });
    mrrCurrentPage = 1;
    mrrRenderPage();
}

function mrrRenderPage() {
    var total     = mrrVisibleRows.length;
    var totalPage = Math.max(1, Math.ceil(total / MRR_PAGE_SIZE));
    if (mrrCurrentPage > totalPage) mrrCurrentPage = totalPage;
    var start = (mrrCurrentPage - 1) * MRR_PAGE_SIZE;
    var end   = start + MRR_PAGE_SIZE;
    mrrVisibleRows.forEach(function (row, idx) {
        var show = idx >= start && idx < end;
        row.style.display = show ? '' : 'none';
        if (show) { var no = row.querySelector('.row-no'); if (no) no.textContent = start + idx + 1; }
    });
    var noResult = document.getElementById('mrrNoResult');
    if (total === 0) {
        if (!noResult) {
            var tr = document.createElement('tr');
            tr.id = 'mrrNoResult';
            tr.innerHTML = '<td colspan="7" class="px-5 py-12 text-center">'
                + '<svg class="w-10 h-10 mx-auto mb-3 mrr-room-note opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">'
                + '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>'
                + '</svg><p class="text-sm mrr-room-note">Tidak ada data yang cocok</p></td>';
            document.getElementById('mrrTabelBody').appendChild(tr);
        }
    } else { if (noResult) noResult.remove(); }
    mrrRenderPagInfo(total, start, Math.min(end, total));
    mrrRenderPagination(totalPage);
}

function mrrRenderPagInfo(total, start, end) {
    var el = document.getElementById('mrrPagInfo');
    if (el) el.textContent = total === 0 ? '' : 'Menampilkan ' + (start+1) + '\u2013' + end + ' dari ' + total + ' data';
}

function mrrRenderPagination(totalPage) {
    var c = document.getElementById('mrrPagButtons');
    if (!c) return;
    c.innerHTML = '';
    if (totalPage <= 1) return;
    c.appendChild(mrrPagBtn('\u2039 Prev', mrrCurrentPage === 1, function () { if (mrrCurrentPage > 1) { mrrCurrentPage--; mrrRenderPage(); } }));
    mrrPageRange(mrrCurrentPage, totalPage).forEach(function (p) {
        if (p === '...') {
            var d = document.createElement('span');
            d.textContent = '\u2026';
            d.className = 'px-2 py-1 text-sm select-none mrr-pag-info';
            c.appendChild(d);
        } else {
            var btn = mrrPagBtn(p, false, function (pg) { return function () { mrrCurrentPage = pg; mrrRenderPage(); }; }(p));
            if (p === mrrCurrentPage) btn.classList.add('mrr-pag-active');
            c.appendChild(btn);
        }
    });
    c.appendChild(mrrPagBtn('Next \u203a', mrrCurrentPage === totalPage, function () { if (mrrCurrentPage < totalPage) { mrrCurrentPage++; mrrRenderPage(); } }));
}

function mrrPagBtn(label, disabled, onClick) {
    var btn = document.createElement('button');
    btn.type = 'button';
    btn.textContent = label;
    btn.disabled = disabled;
    btn.className = 'mrr-pag-btn px-3 py-1.5 rounded-lg border text-sm transition';
    if (disabled) btn.classList.add('cursor-not-allowed');
    else { btn.classList.add('cursor-pointer'); btn.addEventListener('click', onClick); }
    return btn;
}

function mrrPageRange(current, total) {
    if (total <= 7) return Array.from({length: total}, function(_, i){ return i+1; });
    if (current <= 4)         return [1,2,3,4,5,'...',total];
    if (current >= total - 3) return [1,'...',total-4,total-3,total-2,total-1,total];
    return [1,'...',current-1,current,current+1,'...',total];
}

// ── Edit ──────────────────────────────────────────────────
function mrrOpenEdit(r) {
    document.getElementById('er_id').value         = r.id           || '';
    document.getElementById('er_nama').value       = r.nama_ruangan || '';
    document.getElementById('er_kapasitas').value  = r.kapasitas    || '';
    document.getElementById('er_lokasi').value     = r.lokasi       || '';
    document.getElementById('er_status').value     = r.status       || 'Aktif';
    document.getElementById('er_keterangan').value = r.keterangan   || '';
    var aktif = r.fasilitas ? r.fasilitas.split(',').map(function(f){ return f.trim(); }) : [];
    document.querySelectorAll('.mrr-fas-edit').forEach(function (cb) { cb.checked = aktif.includes(cb.value); });
    mrrOpenModal('mrr_modalEdit');
}

// ── Hapus ─────────────────────────────────────────────────
function mrrHapus(id, nama) {
    Swal.fire({
        title: 'Hapus Ruangan?',
        text: 'Yakin ingin menghapus "' + nama + '"?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        reverseButtons: true,
    }).then(function (r) {
        if (r.isConfirmed)
            window.location.href = '<?= base_url('master_ruang_rapat/hapus/') ?>' + id;
    });
}

document.getElementById('mrrSearchInput').addEventListener('input', mrrFilter);
document.getElementById('mrrFilterStatus').addEventListener('change', mrrFilter);
</script>