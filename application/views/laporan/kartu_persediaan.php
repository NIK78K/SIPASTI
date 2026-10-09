<h1 class="text-2xl font-semibold tracking-tight mb-1 header-title">
    Kartu Persediaan
</h1>
<p class="text-sm text-gray-400 mb-6 header-subtitle">
    Mutasi masuk &amp; keluar per barang per periode
</p>

<!-- ================= FILTER ================= -->
<form method="get" action="<?= base_url('laporan/kartu_persediaan') ?>" class="flex flex-wrap items-end gap-3 mb-4 text-sm">
    <div>
        <label class="block text-xs text-gray-400 mb-1">Barang</label>
        <input type="search" id="barangSearch" name="barang_search" placeholder="Cari kode / nama..."
               value="<?= htmlspecialchars($barang_search ?? '') ?>"
               class="bg-slate-800/80 border border-slate-700/50 rounded-lg px-3 py-2 text-gray-200 placeholder-slate-500 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all min-w-[260px] mb-1">
        <select name="barang_id" id="barangSelect" required class="bg-slate-800/80 border border-slate-700/50 rounded-lg px-3 py-2 text-gray-200 hover:border-cyan-500/50 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all min-w-[260px]">
            <option value="" class="bg-slate-800 text-slate-400">-- Pilih Barang --</option>
            <?php foreach(($barang_list ?? []) as $brg): ?>
                <option value="<?= $brg->id ?>" class="bg-slate-800 text-white" <?= (string)($barang_id ?? '') === (string)$brg->id ? 'selected' : '' ?>><?= htmlspecialchars(($brg->kode_barang ?? '-') . ' - ' . ($brg->nama_barang ?? '-')) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
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
    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-semibold px-4 py-2 rounded-lg transition">
        Tampilkan
    </button>
</form>

<?php if (empty($barang)): ?>
    <div class="border rounded-xl theme-card p-10 text-center">
        <div class="text-gray-500">Pilih barang untuk menampilkan kartu persediaan.</div>
    </div>
<?php else: ?>
    <?php $nb = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember']; ?>

    <!-- ================= KOP BARANG ================= -->
    <div class="border rounded-xl theme-card p-4 mb-4 text-sm grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
        <div>
            <div class="text-xs text-gray-500">Kode</div>
            <div class="font-mono text-cyan-400 font-semibold"><?= htmlspecialchars($barang->kode_barang ?? '-') ?></div>
        </div>
        <div>
            <div class="text-xs text-gray-500">Nama</div>
            <div class="font-medium text-gray-200"><?= htmlspecialchars($barang->nama_barang ?? '-') ?></div>
        </div>
        <div>
            <div class="text-xs text-gray-500">Satuan</div>
            <div class="text-gray-200"><?= htmlspecialchars($barang->satuan ?? '-') ?></div>
        </div>
        <div>
            <div class="text-xs text-gray-500">Kategori</div>
            <div class="text-gray-200"><?= htmlspecialchars(($barang->kategori_kode ?? '-') . ' - ' . ($barang->kategori_nama ?? '-')) ?></div>
        </div>
        <div>
            <div class="text-xs text-gray-500">Periode</div>
            <div class="text-gray-200"><?= htmlspecialchars(($nb[(int)$bulan] ?? $bulan) . ' ' . $tahun) ?></div>
        </div>
    </div>

    <!-- ================= RINGKASAN ================= -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-4 text-sm">
        <div class="theme-card stat-card border rounded-xl p-4">
            <div class="text-gray-400 header-subtitle">Stok Awal</div>
            <div class="mt-1 text-2xl font-semibold text-gray-200"><?= number_format((int)($stok_awal ?? 0)) ?></div>
            <div class="text-xs text-gray-500"><?= htmlspecialchars($barang->satuan ?? '') ?></div>
        </div>
        <div class="theme-card stat-card border rounded-xl p-4">
            <div class="text-gray-400 header-subtitle">Total Masuk</div>
            <div class="mt-1 text-2xl font-semibold text-green-400"><?= number_format((int)($total_masuk ?? 0)) ?></div>
            <div class="text-xs text-gray-500"><?= htmlspecialchars($barang->satuan ?? '') ?></div>
        </div>
        <div class="theme-card stat-card border rounded-xl p-4">
            <div class="text-gray-400 header-subtitle">Total Keluar</div>
            <div class="mt-1 text-2xl font-semibold text-red-400"><?= number_format((int)($total_keluar ?? 0)) ?></div>
            <div class="text-xs text-gray-500"><?= htmlspecialchars($barang->satuan ?? '') ?></div>
        </div>
        <div class="theme-card stat-card border rounded-xl p-4">
            <div class="text-gray-400 header-subtitle">Stok Akhir</div>
            <div class="mt-1 text-2xl font-semibold text-gray-200"><?= number_format((int)($saldo_akhir ?? 0)) ?></div>
            <div class="text-xs text-gray-500"><?= htmlspecialchars($barang->satuan ?? '') ?></div>
        </div>
    </div>

    <div class="overflow-x-auto border rounded-xl theme-card trx-table-wrap">
        <table class="w-full text-sm" style="table-layout:fixed;">
            <thead class="bg-slate-900/90 text-gray-300 border-b border-slate-700/50">
                <tr>
                    <th class="px-4 py-3 text-left" style="width:50px;">No</th>
                    <th class="px-4 py-3 text-left" style="width:120px;">Tanggal</th>
                    <th class="px-4 py-3 text-left">Uraian</th>
                    <th class="px-4 py-3 text-left" style="width:130px;">No. Dokumen</th>
                    <th class="px-4 py-3 text-right" style="width:90px;">Masuk</th>
                    <th class="px-4 py-3 text-right" style="width:90px;">Keluar</th>
                    <th class="px-4 py-3 text-right" style="width:90px;">Sisa</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/30">
                <tr class="bg-slate-800/40">
                    <td class="px-4 py-3 text-gray-500">-</td>
                    <td class="px-4 py-3 text-gray-400">-</td>
                    <td class="px-4 py-3 font-semibold text-gray-200">Saldo Awal</td>
                    <td class="px-4 py-3 text-gray-400">-</td>
                    <td class="px-4 py-3 text-right text-gray-400">-</td>
                    <td class="px-4 py-3 text-right text-gray-400">-</td>
                    <td class="px-4 py-3 text-right font-semibold text-gray-200"><?= number_format((int)($stok_awal ?? 0)) ?></td>
                </tr>
                <?php if (empty($rows)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center">
                        <div class="text-gray-500">Belum ada transaksi pada periode ini.</div>
                    </td>
                </tr>
                <?php else: ?>
                <?php $no = 1; foreach(($rows ?? []) as $t): ?>
                <tr class="hover:bg-slate-800/50 transition-colors duration-150">
                    <td class="px-4 py-3 text-gray-500"><?= $no++ ?></td>
                    <td class="px-4 py-3 text-gray-400 whitespace-nowrap"><?= htmlspecialchars($t->tanggal ?? '-') ?></td>
                    <td class="px-4 py-3 text-gray-200">
                        <?= htmlspecialchars($t->uraian ?? '-') ?>
                        <?php if (($t->jenis ?? '') === 'masuk'): ?>
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-gradient-to-r from-green-500/15 to-emerald-500/15 text-green-400 ring-1 ring-green-500/30">masuk</span>
                        <?php else: ?>
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-gradient-to-r from-red-500/15 to-rose-500/15 text-red-400 ring-1 ring-red-500/30">keluar</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 font-mono text-gray-400"><?= htmlspecialchars($t->no_dokumen ?? '-') ?></td>
                    <td class="px-4 py-3 text-right text-green-400 font-semibold"><?= ($t->jenis ?? '') === 'masuk' ? number_format((int)($t->jumlah ?? 0)) : '-' ?></td>
                    <td class="px-4 py-3 text-right text-red-400 font-semibold"><?= ($t->jenis ?? '') === 'keluar' ? number_format((int)($t->jumlah ?? 0)) : '-' ?></td>
                    <td class="px-4 py-3 text-right font-semibold text-gray-200"><?= number_format((int)($t->sisa ?? 0)) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
                <tr class="bg-slate-800/40">
                    <td class="px-4 py-3 text-gray-500">-</td>
                    <td class="px-4 py-3 text-gray-400">-</td>
                    <td class="px-4 py-3 font-semibold text-gray-200">Saldo Akhir</td>
                    <td class="px-4 py-3 text-gray-400">-</td>
                    <td class="px-4 py-3 text-right text-gray-400">-</td>
                    <td class="px-4 py-3 text-right text-gray-400">-</td>
                    <td class="px-4 py-3 text-right font-semibold text-gray-200"><?= number_format((int)($saldo_akhir ?? 0)) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<script>
(function () {
    var s = document.getElementById('barangSearch');
    var d = document.getElementById('barangSelect');
    if (!s || !d) return;
    s.addEventListener('input', function () {
        var q = s.value.toLowerCase();
        for (var i = 0; i < d.options.length; i++) {
            var t = (d.options[i].text || '').toLowerCase();
            d.options[i].hidden = q && t.indexOf(q) === -1 && d.options[i].value !== '';
        }
    });
})();
</script>

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
