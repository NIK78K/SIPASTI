<h1 class="text-2xl font-semibold tracking-tight mb-1 header-title">
    Rekap Tahunan Persediaan
</h1>
<p class="text-sm text-gray-400 mb-6 header-subtitle">
    Ringkasan transaksi per barang selama satu tahun (Jan–Des)
</p>

<!-- ================= FILTER ================= -->
<form method="get" action="<?= base_url('laporan/tahunan_persediaan') ?>" class="flex flex-wrap items-end gap-3 mb-4 text-sm">
    <div>
        <label class="block text-xs text-gray-400 mb-1">Tahun</label>
        <select name="tahun" class="bg-slate-800/80 border border-slate-700/50 rounded-lg px-3 py-2 text-gray-200 hover:border-cyan-500/50 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all">
            <?php foreach(($tahun_list ?? [(int)($tahun ?? date('Y'))]) as $thn): ?>
                <option value="<?= $thn ?>" class="bg-slate-800 text-white" <?= (string)($tahun ?? '') === (string)$thn ? 'selected' : '' ?>><?= htmlspecialchars($thn) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-semibold px-4 py-2 rounded-lg transition">
        Tampilkan
    </button>
    <?php $q_thn = http_build_query(['tahun' => (int)($tahun ?? date('Y'))]); ?>
    <a href="<?= base_url('laporan/export_excel_tahunan?' . $q_thn) ?>" class="bg-green-600 hover:bg-green-500 text-white font-semibold px-4 py-2 rounded-lg transition">
        Excel
    </a>
    <a href="<?= base_url('laporan/export_pdf_tahunan?' . $q_thn) ?>" target="_blank" class="bg-red-600 hover:bg-red-500 text-white font-semibold px-4 py-2 rounded-lg transition">
        PDF
    </a>
</form>

<p class="text-sm text-gray-400 mb-4">Tahun: <span class="text-gray-200 font-medium"><?= htmlspecialchars($tahun) ?></span></p>

<div class="header border rounded-xl theme-card p-4 mb-4 text-center">
    <h2 class="header-title text-lg font-semibold">Rekap Transaksi Tahunan &mdash; BPS Provinsi Riau</h2>
    <p class="header-subtitle text-sm mt-1">Tahun <?= htmlspecialchars($tahun) ?></p>
    <p class="header-subtitle text-sm">Dicetak: <?= date('d M Y, H:i') ?></p>
</div>

<div class="overflow-x-auto border rounded-xl theme-card trx-table-wrap">
    <table class="w-full text-sm">
        <thead class="bg-slate-900/90 text-gray-300 border-b border-slate-700/50 sticky top-0">
            <tr>
                <th class="px-4 py-3 text-left" rowspan="2" style="width:50px;">No</th>
                <th class="px-4 py-3 text-left" rowspan="2">Barang</th>
                <th class="px-4 py-3 text-left" rowspan="2" style="width:80px;">Satuan</th>
                <?php $nama_bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des']; ?>
                <?php foreach($nama_bulan as $nama): ?>
                <th class="px-2 py-3 text-center border-l border-slate-700/50" colspan="2"><?= $nama ?></th>
                <?php endforeach; ?>
                <th class="px-2 py-3 text-center border-l border-slate-700/50" colspan="2">Total</th>
            </tr>
            <tr>
                <?php for($i = 1; $i <= 12; $i++): ?>
                <th class="px-1 py-1.5 text-center text-xs text-green-400 border-l border-slate-700/50 whitespace-nowrap" style="width:36px;min-width:36px;">M</th>
                <th class="px-1 py-1.5 text-center text-xs text-red-400 whitespace-nowrap" style="width:36px;min-width:36px;">K</th>
                <?php endfor; ?>
                <th class="px-1 py-1.5 text-center text-xs text-green-400 border-l border-slate-700/50 whitespace-nowrap" style="width:44px;min-width:44px;">M</th>
                <th class="px-1 py-1.5 text-center text-xs text-red-400 whitespace-nowrap" style="width:44px;min-width:44px;">K</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-800/30">
            <?php if (empty($rows)): ?>
            <tr>
                <td colspan="29" class="px-6 py-10 text-center">
                    <div class="text-gray-500">Belum ada data barang pada tahun ini.</div>
                </td>
            </tr>
            <?php else: ?>
            <?php $no = 1; $t_bln = []; for($i = 1; $i <= 12; $i++) $t_bln[$i] = ['m' => 0, 'k' => 0]; $t_tm = 0; $t_tk = 0; ?>
            <?php foreach(($rows ?? []) as $r): ?>
            <?php $t_tm += (int)($r->tot_m ?? 0); $t_tk += (int)($r->tot_k ?? 0); ?>
            <tr class="hover:bg-slate-800/50 transition-colors duration-150">
                <td class="px-4 py-3 text-gray-500"><?= $no++ ?></td>
                <td class="px-4 py-3">
                    <div class="font-medium text-gray-200"><?= htmlspecialchars($r->nama_barang ?? '-') ?></div>
                    <div class="font-mono text-xs text-cyan-400"><?= htmlspecialchars($r->kode_barang ?? '-') ?></div>
                    <div class="text-xs text-gray-500"><?= htmlspecialchars(($r->kategori_kode ?? '-') . ' - ' . ($r->kategori_nama ?? '-')) ?></div>
                </td>
                <td class="px-4 py-3 text-gray-400"><?= htmlspecialchars($r->satuan ?? '-') ?></td>
                <?php for($m = 1; $m <= 12; $m++): ?>
                <?php $mv = (int)(($r->per_bulan[$m]['m'] ?? 0)); $kv = (int)(($r->per_bulan[$m]['k'] ?? 0)); $t_bln[$m]['m'] += $mv; $t_bln[$m]['k'] += $kv; ?>
                <td class="px-1 py-2 text-right text-xs whitespace-nowrap border-l border-slate-800/30 <?= $mv > 0 ? 'text-gray-200' : 'text-gray-600' ?>"><?= $mv > 0 ? number_format($mv) : '-' ?></td>
                <td class="px-1 py-2 text-right text-xs whitespace-nowrap <?= $kv > 0 ? 'text-gray-200' : 'text-gray-600' ?>"><?= $kv > 0 ? number_format($kv) : '-' ?></td>
                <?php endfor; ?>
                <td class="px-1 py-2 text-right text-xs whitespace-nowrap border-l border-slate-800/30 text-green-400 font-semibold"><?= number_format((int)($r->tot_m ?? 0)) ?></td>
                <td class="px-1 py-2 text-right text-xs whitespace-nowrap text-red-400 font-semibold"><?= number_format((int)($r->tot_k ?? 0)) ?></td>
            </tr>
            <?php endforeach; ?>
            <tr class="bg-slate-800/40 font-semibold">
                <td class="px-4 py-3 text-gray-200" colspan="27">Total</td>
                <td class="px-1 py-2 text-right text-xs whitespace-nowrap border-l border-slate-800/30 text-green-400"><?= number_format($t_tm) ?></td>
                <td class="px-1 py-2 text-right text-xs whitespace-nowrap text-red-400"><?= number_format($t_tk) ?></td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

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
html.light .bg-slate-800\/40 {
    background: rgba(241, 245, 249, 0.9) !important;
}
html.light .border-slate-700\/50 {
    border-color: rgba(226, 232, 240, 0.8) !important;
}
html.light .border-slate-800\/30 {
    border-color: rgba(226, 232, 240, 0.5) !important;
}
html.light .divide-slate-800\/30 > :not([hidden]) ~ :not([hidden]) {
    border-color: rgba(226, 232, 240, 0.5) !important;
}
html.light input,
html.light select {
    background: rgba(255, 255, 255, 0.95) !important;
    color: var(--text-primary) !important;
    border-color: rgba(203, 213, 225, 0.8) !important;
}
html.light select option {
    background: #ffffff !important;
    color: #0f172a !important;
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
html.light .text-gray-500 {
    color: var(--text-secondary) !important;
}
html.light .text-gray-600 {
    color: var(--text-muted) !important;
}
html.light .text-cyan-400 {
    color: #0e7490 !important;
}
html.light .text-green-400 {
    color: #15803d !important;
}
html.light .text-red-400 {
    color: #b91c1c !important;
}
html.light .trx-table-wrap {
    border-color: rgba(226, 232, 240, 0.8) !important;
}
</style>
