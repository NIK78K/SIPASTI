<!-- ================= JUDUL ================= -->
<h1 class="text-2xl font-semibold tracking-tight mb-1 header-title">
    History Perbaikan
</h1>
<p class="text-sm text-gray-400 mb-6 header-subtitle">
    Riwayat tiket yang telah selesai atau dibatalkan
</p>

<!-- ================= SUMMARY ================= -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6 text-sm">

    <div class="relative overflow-hidden theme-card stat-card border rounded-xl p-4 hover:border-cyan-500/30 transition-all group">
        <div class="absolute top-0 right-0 w-20 h-20 bg-cyan-500/5 rounded-full blur-xl group-hover:bg-cyan-500/10 transition-all"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-2">
                <div class="text-gray-400 header-subtitle">Total History</div>
                <div class="w-8 h-8 bg-cyan-500/10 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-1 text-2xl font-semibold text-white"><?= $total ?></div>
        </div>
    </div>

    <div class="relative overflow-hidden theme-card stat-card border rounded-xl p-4 hover:border-green-500/30 transition-all group">
        <div class="absolute top-0 right-0 w-20 h-20 bg-green-500/5 rounded-full blur-xl group-hover:bg-green-500/10 transition-all"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-2">
                <div class="text-gray-400 header-subtitle">Selesai</div>
                <div class="w-8 h-8 bg-green-500/10 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-1 text-2xl font-semibold text-green-400"><?= $selesai ?></div>
            <?php if($total > 0): ?>
            <div class="text-xs text-gray-600 mt-1"><?= round(($selesai/$total)*100, 1) ?>%</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="relative overflow-hidden theme-card stat-card border rounded-xl p-4 hover:border-red-500/30 transition-all group">
        <div class="absolute top-0 right-0 w-20 h-20 bg-red-500/5 rounded-full blur-xl group-hover:bg-red-500/10 transition-all"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-2">
                <div class="text-gray-400 header-subtitle">Dibatalkan</div>
                <div class="w-8 h-8 bg-red-500/10 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-1 text-2xl font-semibold text-red-400"><?= $batal ?></div>
            <?php if($total > 0): ?>
            <div class="text-xs text-gray-600 mt-1"><?= round(($batal/$total)*100, 1) ?>%</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="relative overflow-hidden theme-card stat-card border rounded-xl p-4 hover:border-yellow-500/30 transition-all group">
        <div class="absolute top-0 right-0 w-20 h-20 bg-yellow-500/5 rounded-full blur-xl group-hover:bg-yellow-500/10 transition-all"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-2">
                <div class="text-gray-400 header-subtitle">Rating Rata-rata</div>
                <div class="w-8 h-8 bg-yellow-500/10 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-1 text-2xl font-semibold text-yellow-400">
                <?= number_format($rating, 1) ?> <span class="text-sm">★</span>
            </div>
        </div>
    </div>

</div>

<!-- ================= FILTER ================= -->
<form method="get" class="flex flex-wrap gap-3 mb-4 text-sm">

    <!-- KATEGORI -->
    <select name="kategori" onchange="this.form.submit()"
            class="bg-slate-800/80 border border-slate-700/50 rounded-lg px-3 py-2 text-gray-200 hover:border-cyan-500/50 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all">
        <option value="">Semua Kategori</option>
        <?php foreach ($kategori_master as $k): ?>
            <option value="<?= $k['id'] ?>"
                <?= $this->input->get('kategori') == $k['id'] ? 'selected' : '' ?>>
                <?= $k['nama_kategori'] ?>
            </option>
        <?php endforeach ?>
    </select>

    <!-- SUB KATEGORI -->
    <select name="sub_kategori" onchange="this.form.submit()"
            class="bg-slate-800/80 border border-slate-700/50 rounded-lg px-3 py-2 text-gray-200 hover:border-cyan-500/50 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all">
        <option value="">Semua Sub Kategori</option>
        <?php foreach ($sub_kategori_master as $sk): ?>
            <option value="<?= $sk['id'] ?>"
                <?= $this->input->get('sub_kategori') == $sk['id'] ? 'selected' : '' ?>>
                <?= $sk['nama_sub_kategori'] ?>
            </option>
        <?php endforeach ?>
    </select>

    <!-- STATUS -->
    <select name="status" onchange="this.form.submit()"
            class="bg-slate-800/80 border border-slate-700/50 rounded-lg px-3 py-2 text-gray-200 hover:border-cyan-500/50 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all">
        <option value="">Semua Status</option>
        <option value="Selesai" <?= $this->input->get('status')==='Selesai'?'selected':'' ?>>
            ✓ Selesai
        </option>
        <option value="Dibatalkan" <?= $this->input->get('status')==='Dibatalkan'?'selected':'' ?>>
            ✕ Dibatalkan
        </option>
    </select>

    <!-- TEKNISI -->
    <select name="teknisi" onchange="this.form.submit()"
            class="bg-slate-800/80 border border-slate-700/50 rounded-lg px-3 py-2 text-gray-200 hover:border-cyan-500/50 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all">
        <option value="">Semua Teknisi</option>
        <?php foreach ($teknisi as $t): ?>
            <option value="<?= $t['id'] ?>"
                <?= $this->input->get('teknisi') == $t['id'] ? 'selected' : '' ?>>
                <?= $t['name'] ?>
            </option>
        <?php endforeach ?>
    </select>

    <!-- BULAN -->
    <select name="bulan" onchange="this.form.submit()"
            class="bg-slate-800/80 border border-slate-700/50 rounded-lg px-3 py-2 text-gray-200 hover:border-cyan-500/50 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all">
        <option value="">Semua Bulan</option>
        <?php for ($i=1;$i<=12;$i++): ?>
            <option value="<?= $i ?>"
                <?= $this->input->get('bulan') == $i ? 'selected' : '' ?>>
                <?= date('F', mktime(0,0,0,$i,1)) ?>
            </option>
        <?php endfor ?>
    </select>

</form>

<!-- ================= TABEL ================= -->
<div class="overflow-x-auto border rounded-xl theme-card">

<table class="w-full text-sm">
<thead class="bg-slate-900/90 text-gray-300 border-b border-slate-700/50">
<tr>
    <th class="px-4 py-3 text-left">
        <div class="flex items-center gap-2">
            <svg class="w-3.5 h-3.5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
            </svg>
            Kode Tiket
        </div>
    </th>
    <th class="px-4 py-3 text-left">Pelapor</th>
    <th class="px-4 py-3 text-left">Perangkat</th>
    <th class="px-4 py-3 text-left">Kategori</th>
    <th class="px-4 py-3 text-left">Teknisi</th>
    <th class="px-4 py-3 text-left">Tanggal Selesai</th>
    <th class="px-4 py-3 text-center">Status</th>
    <th class="px-4 py-3 text-center">Rating</th>
</tr>
</thead>

<tbody class="divide-y divide-slate-800/30">

<?php if (empty($history)): ?>
<tr>
    <td colspan="8" class="px-6 py-10 text-center">
        <div class="flex flex-col items-center gap-3">
            <div class="w-12 h-12 bg-gray-800/50 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="text-gray-500">Belum ada data history</div>
        </div>
    </td>
</tr>
<?php else: foreach ($history as $h): ?>

<tr class="hover:bg-slate-800/50 transition-colors duration-150 group">
    <td class="px-4 py-3">
        <div class="font-mono text-cyan-400 font-semibold group-hover:text-cyan-300 transition-colors">
            <?= $h['kode_tiket'] ?>
        </div>
    </td>
    
    <td class="px-4 py-3">
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 bg-gradient-to-br from-cyan-500/20 to-blue-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                <span class="text-cyan-400 font-semibold text-xs">
                    <?= strtoupper(substr($h['pelapor'] ?: 'N', 0, 1)) ?>
                </span>
            </div>
            <span class="text-gray-200"><?= $h['pelapor'] ?: '-' ?></span>
        </div>
    </td>
    
    <td class="px-4 py-3">
        <div class="flex items-center gap-2">
            <svg class="w-3.5 h-3.5 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <span class="text-gray-200"><?= $h['nama_perangkat'] ?? '-' ?></span>
        </div>
    </td>

    <!-- KATEGORI + SUB -->
    <td class="px-4 py-3">
        <div class="font-medium text-gray-200 flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 bg-cyan-400 rounded-full flex-shrink-0"></span>
            <?= $h['nama_kategori'] ?? '-' ?>
        </div>
        <div class="text-xs text-gray-500 pl-3">
            <?= $h['nama_sub_kategori'] ?? '-' ?>
        </div>
    </td>

    <td class="px-4 py-3">
        <div class="flex items-center gap-2">
            <div class="w-6 h-6 bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-md flex items-center justify-center flex-shrink-0">
                <svg class="w-3 h-3 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <span class="text-gray-200"><?= $h['nama_teknisi'] ?? '-' ?></span>
        </div>
    </td>

    <td class="px-4 py-3 text-gray-400">
        <div class="flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <?= $h['selesai_at'] ? date('d M Y H:i', strtotime($h['selesai_at'])) : '-' ?>
        </div>
    </td>

    <td class="px-4 py-3 text-center">
        <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold rounded-full
            <?= $h['status']==='Selesai'
                ? 'bg-gradient-to-r from-green-500/15 to-emerald-500/15 text-green-400 ring-1 ring-green-500/30'
                : 'bg-gradient-to-r from-red-500/15 to-rose-500/15 text-red-400 ring-1 ring-red-500/30' ?>">
            <?php if($h['status']==='Selesai'): ?>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            <?php else: ?>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            <?php endif; ?>
            <?= $h['status'] ?>
        </span>
    </td>

    <td class="px-4 py-3 text-center">
        <?php if($h['rating']): ?>
        <div class="inline-flex items-center gap-1 px-2.5 py-1 bg-yellow-500/10 rounded-lg ring-1 ring-yellow-500/20">
            <span class="text-yellow-400 font-semibold text-xs"><?= number_format($h['rating'], 1) ?></span>
            <span class="text-yellow-400 text-xs">★</span>
        </div>
        <?php else: ?>
        <span class="text-gray-600 text-xs">-</span>
        <?php endif; ?>
    </td>
</tr>

<?php endforeach; endif; ?>

</tbody>
</table>
</div>

<!-- ================= PAGINATION ================= -->
<?php if (!empty($pagination)): ?>
<div class="flex justify-center mt-6">
    <?= $pagination ?>
</div>
<?php endif; ?>

<!-- Theme-aware styles -->
<style>
html.light tbody tr:hover {
    background: rgba(248, 250, 252, 0.6) !important;
}

html.light thead {
    background: rgba(241, 245, 249, 0.9) !important;
}

html.light thead th {
    color: var(--text-secondary) !important;
}

html.light .bg-slate-800\/80 {
    background: rgba(255, 255, 255, 0.95) !important;
}

html.light .border-slate-700\/50 {
    border-color: rgba(226, 232, 240, 0.8) !important;
}

html.light select {
    background: rgba(255, 255, 255, 0.95) !important;
    color: var(--text-primary) !important;
    border-color: rgba(203, 213, 225, 0.8) !important;
}

html.light .text-gray-200 {
    color: var(--text-primary) !important;
}

html.light .text-gray-300 {
    color: var(--text-secondary) !important;
}

html.light .text-gray-400 {
    color: var(--text-muted) !important;
}

html.light .divide-slate-800\/30 > :not([hidden]) ~ :not([hidden]) {
    border-color: rgba(226, 232, 240, 0.5) !important;
}

/* Pagination styling */
.pagination a,
.pagination span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 2.25rem;
    height: 2.25rem;
    padding: 0 0.75rem;
    font-size: 0.875rem;
    font-weight: 500;
    border-radius: 0.5rem;
    transition: all 0.2s;
}

.pagination a {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    color: #9CA3AF;
}

.pagination a:hover {
    background: rgba(34, 211, 238, 0.1);
    border-color: rgba(34, 211, 238, 0.5);
    color: #22D3EE;
    transform: translateY(-1px);
}

.pagination span {
    background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%);
    border: 1px solid rgba(34, 211, 238, 0.3);
    color: white;
    box-shadow: 0 0 15px rgba(34, 211, 238, 0.15);
}

html.light .pagination a {
    background: rgba(255, 255, 255, 0.95);
    border-color: rgba(203, 213, 225, 0.8);
}

html.light .pagination a:hover {
    background: rgba(14, 116, 144, 0.1);
}
</style>