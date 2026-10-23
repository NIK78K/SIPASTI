<h1 class="text-2xl font-semibold tracking-tight mb-1 header-title">
    Laporan Bulanan Persediaan
</h1>
<p class="text-sm text-gray-400 mb-6 header-subtitle">
    Rekap stok awal, mutasi &amp; stok akhir semua barang per periode
</p>

<!-- ================= FILTER ================= -->
<form method="get" action="<?= base_url('laporan/bulanan_persediaan') ?>" class="flex flex-wrap items-end gap-3 mb-4 text-sm">
    <div>
        <label class="block text-xs text-gray-400 mb-1">Bulan</label>
        <select name="bulan" class="bg-slate-800/80 border border-slate-700/50 rounded-lg px-3 py-2 text-gray-200 hover:border-cyan-500/50 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all">
            <?php $nama_bulan = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember']; ?>
            <?php foreach($nama_bulan as $num => $nama): ?>
                <option value="<?= $num ?>" class="bg-slate-800 text-white" <?= (int)($bulan ?? 0) === (int)$num ? 'selected' : '' ?>><?= $nama ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label class="block text-xs text-gray-400 mb-1">Tahun</label>
        <select name="tahun" class="bg-slate-800/80 border border-slate-700/50 rounded-lg px-3 py-2 text-gray-200 hover:border-cyan-500/50 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all">
            <?php foreach(($tahun_list ?? [(int)($tahun ?? date('Y'))]) as $thn): ?>
                <option value="<?= $thn ?>" class="bg-slate-800 text-white" <?= (string)($tahun ?? '') === (string)$thn ? 'selected' : '' ?>><?= htmlspecialchars($thn) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php if (!empty($kategori_list)): ?>
    <div>
        <label class="block text-xs text-gray-400 mb-1">Kategori</label>
        <select name="kategori_id" class="bg-slate-800/80 border border-slate-700/50 rounded-lg px-3 py-2 text-gray-200 hover:border-cyan-500/50 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all">
            <option value="" class="bg-slate-800 text-slate-400">-- Semua --</option>
            <?php foreach(($kategori_list ?? []) as $kat): ?>
                <option value="<?= $kat->id ?>" class="bg-slate-800 text-white" <?= (string)($kategori_id ?? '') === (string)$kat->id ? 'selected' : '' ?>><?= htmlspecialchars(($kat->kode ?? '-') . ' - ' . ($kat->nama ?? '-')) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php endif; ?>
    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-semibold px-4 py-2 rounded-lg transition">
        Tampilkan
    </button>
    <?php $q_bul = http_build_query(['bulan' => (int)($bulan ?? date('n')), 'tahun' => (int)($tahun ?? date('Y')), 'kategori_id' => ($kategori_id ?? ''), 'kode' => ($kode ?? '')]); ?>
    <a href="<?= base_url('laporan/export_excel_bulanan?' . $q_bul) ?>" class="bg-green-600 hover:bg-green-500 text-white font-semibold px-4 py-2 rounded-lg transition">
        Excel
    </a>
    <a href="<?= base_url('laporan/export_pdf_bulanan?' . $q_bul) ?>" target="_blank" class="bg-red-600 hover:bg-red-500 text-white font-semibold px-4 py-2 rounded-lg transition">
        PDF
    </a>
</form>

<?php $nb = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember']; ?>
<p class="text-sm text-gray-400 mb-4">Periode: <span class="text-gray-200 font-medium"><?= htmlspecialchars(($nb[(int)$bulan] ?? $bulan) . ' ' . $tahun) ?></span></p>

<div class="header border rounded-xl theme-card p-4 mb-4 text-center">
    <h2 class="header-title text-lg font-semibold">Laporan STOK &mdash; <?= htmlspecialchars(($nb[(int)$bulan] ?? $bulan) . ' ' . $tahun) ?></h2>
    <p class="header-subtitle text-sm mt-1">BPS Provinsi Riau</p>
    <p class="header-subtitle text-sm">Dicetak: <?= date('d M Y, H:i') ?></p>
</div>

<div class="overflow-x-auto border rounded-xl theme-card trx-table-wrap">
    <table class="w-full text-sm">
        <thead class="bg-slate-900/90 text-gray-300 border-b border-slate-700/50">
            <tr>
                <th class="px-4 py-3 text-left" style="width:50px;">No</th>
                <th class="px-4 py-3 text-left">Kode Barang</th>
                <th class="px-4 py-3 text-left">Nama Barang</th>
                <th class="px-4 py-3 text-left" style="width:90px;">Satuan</th>
                <th class="px-4 py-3 text-right" style="width:100px;">Stok Awal</th>
                <th class="px-4 py-3 text-right" style="width:90px;">Masuk</th>
                <th class="px-4 py-3 text-right" style="width:90px;">Keluar</th>
                <th class="px-4 py-3 text-right" style="width:100px;">Stok Akhir</th>
                <th class="px-4 py-3 text-left" style="width:150px;">Keterangan</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-800/30">
            <?php if (empty($rows)): ?>
            <tr>
                <td colspan="9" class="px-6 py-10 text-center">
                    <div class="text-gray-500">Belum ada data barang pada periode ini.</div>
                </td>
            </tr>
            <?php else: ?>
            <?php $no = 1; $t_awal = 0; $t_masuk = 0; $t_keluar = 0; $t_akhir = 0; ?>
            <?php foreach(($rows ?? []) as $r): ?>
            <?php $t_awal += (int)($r->stok_awal ?? 0); $t_masuk += (int)($r->masuk ?? 0); $t_keluar += (int)($r->keluar ?? 0); $t_akhir += (int)($r->stok_akhir ?? 0); ?>
            <tr class="hover:bg-slate-800/50 transition-colors duration-150">
                <td class="px-4 py-3 text-gray-500"><?= $no++ ?></td>
                <td class="px-4 py-3 font-mono text-cyan-400"><?= htmlspecialchars($r->kode_barang ?? '-') ?></td>
                <td class="px-4 py-3 text-gray-200"><?= htmlspecialchars($r->nama_barang ?? '-') ?></td>
                <td class="px-4 py-3 text-gray-400"><?= htmlspecialchars($r->satuan ?? '-') ?></td>
                <td class="px-4 py-3 text-right text-gray-200"><?= number_format((int)($r->stok_awal ?? 0)) ?></td>
                <td class="px-4 py-3 text-right text-green-400 font-semibold"><?= number_format((int)($r->masuk ?? 0)) ?></td>
                <td class="px-4 py-3 text-right text-red-400 font-semibold"><?= number_format((int)($r->keluar ?? 0)) ?></td>
                <td class="px-4 py-3 text-right font-semibold <?= (int)($r->stok_akhir ?? 0) < 0 ? 'text-red-400 font-bold' : 'text-gray-200' ?>"><?= number_format((int)($r->stok_akhir ?? 0)) ?></td>
                <td class="px-4 py-3">
                    <?php if (!empty($r->keterangan)): ?>
                        <?php if ($r->keterangan === 'Stok minimum tercapai'): ?>
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-500/15 text-amber-400 border border-amber-500/25 whitespace-nowrap">
                                <?= htmlspecialchars($r->keterangan) ?>
                            </span>
                        <?php else: ?>
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/15 text-red-400 border border-red-500/25 whitespace-nowrap">
                                <?= htmlspecialchars($r->keterangan) ?>
                            </span>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <tr class="bg-slate-800/40 font-semibold">
                <td class="px-4 py-3 text-gray-500" colspan="4">Total</td>
                <td class="px-4 py-3 text-right text-gray-200"><?= number_format($t_awal) ?></td>
                <td class="px-4 py-3 text-right text-green-400"><?= number_format($t_masuk) ?></td>
                <td class="px-4 py-3 text-right text-red-400"><?= number_format($t_keluar) ?></td>
                <td class="px-4 py-3 text-right text-gray-200"><?= number_format($t_akhir) ?></td>
                <td class="px-4 py-3 text-gray-400">-</td>
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
