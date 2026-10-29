<div class="mb-6">
    <div>
        <h1 class="text-2xl font-bold header-title">Dashboard Stok Persediaan</h1>
        <p class="text-sm header-subtitle mt-1">Monitoring stok berjalan periode <?= htmlspecialchars($periode) ?></p>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="theme-card border rounded-xl p-4">
        <p class="text-sm header-subtitle">Jenis Barang Aktif</p>
        <p class="text-2xl font-bold header-title mt-2"><?= (int) $summary['jenis'] ?></p>
    </div>
    <div class="theme-card border rounded-xl p-4">
        <p class="text-sm header-subtitle">Total Stok Berjalan</p>
        <p class="text-2xl font-bold text-blue-400 mt-2"><?= number_format((int) $summary['total_stok']) ?></p>
    </div>
    <div class="theme-card border rounded-xl p-4">
        <p class="text-sm header-subtitle">Stok Menipis</p>
        <p class="text-2xl font-bold text-amber-400 mt-2"><?= (int) $summary['menipis'] ?></p>
    </div>
    <div class="theme-card border rounded-xl p-4">
        <p class="text-sm header-subtitle">Stok Kosong</p>
        <p class="text-2xl font-bold text-red-400 mt-2"><?= (int) $summary['kosong'] ?></p>
    </div>
</div>

<form method="get" class="theme-card border rounded-xl p-4 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
        <input type="search" name="keyword" value="<?= htmlspecialchars($filters['keyword']) ?>" placeholder="Cari kode, nama, kategori..." class="rounded-lg bg-slate-800/80 border border-slate-700 px-3 py-2 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <select name="kategori_id" class="rounded-lg bg-slate-800/80 border border-slate-700 px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua kategori</option>
            <?php foreach ($kategori_list as $kategori): ?>
                <option value="<?= (int) $kategori->id ?>" <?= (string) $filters['kategori_id'] === (string) $kategori->id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($kategori->kode . ' - ' . $kategori->nama) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <select name="status" class="rounded-lg bg-slate-800/80 border border-slate-700 px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua status</option>
            <option value="aman" <?= $filters['status'] === 'aman' ? 'selected' : '' ?>>Aman</option>
            <option value="menipis" <?= $filters['status'] === 'menipis' ? 'selected' : '' ?>>Menipis</option>
            <option value="kosong" <?= $filters['status'] === 'kosong' ? 'selected' : '' ?>>Kosong</option>
        </select>
        <div class="flex gap-2">
            <button type="submit" class="flex-1 rounded-lg bg-slate-700 hover:bg-slate-600 px-4 py-2 text-sm font-semibold text-white">Filter</button>
            <a href="<?= base_url('inventory_persediaan') ?>" class="rounded-lg border border-slate-600 hover:bg-slate-700 px-4 py-2 text-sm font-semibold header-title">Reset</a>
        </div>
    </div>
</form>

<div class="theme-card border rounded-xl overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-800/50 flex items-center justify-between">
        <h2 class="font-semibold header-title">Daftar Stok</h2>
        <span class="text-xs header-subtitle"><?= count($rows) ?> data ditampilkan</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-900/70 header-subtitle">
                <tr>
                    <th class="text-left px-5 py-3">Barang</th>
                    <th class="text-left px-5 py-3">Kategori</th>
                    <th class="text-right px-5 py-3">Stok Awal</th>
                    <th class="text-right px-5 py-3">Masuk</th>
                    <th class="text-right px-5 py-3">Keluar</th>
                    <th class="text-right px-5 py-3">Stok Saat Ini</th>
                    <th class="text-center px-5 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/50">
                <?php if (empty($rows)): ?>
                    <tr><td colspan="7" class="px-5 py-10 text-center header-subtitle">Belum ada data yang sesuai filter.</td></tr>
                <?php else: ?>
                    <?php foreach ($rows as $row): ?>
                        <?php
                        $status_label = ['aman' => 'Aman', 'menipis' => 'Menipis', 'kosong' => 'Kosong'][$row->status_stok];
                        $status_class = ['aman' => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30', 'menipis' => 'bg-amber-500/15 text-amber-400 border-amber-500/30', 'kosong' => 'bg-red-500/15 text-red-400 border-red-500/30'][$row->status_stok];
                        ?>
                        <tr class="hover:bg-slate-800/40">
                            <td class="px-5 py-3">
                                <div class="font-medium header-title"><?= htmlspecialchars($row->nama_barang) ?></div>
                                <div class="text-xs header-subtitle"><?= htmlspecialchars($row->kode_barang ?: '-') ?> · <?= htmlspecialchars($row->satuan) ?></div>
                            </td>
                            <td class="px-5 py-3 header-subtitle"><?= htmlspecialchars($row->kategori_nama ?: '-') ?></td>
                            <td class="px-5 py-3 text-right header-title"><?= number_format((int) $row->stok_awal) ?></td>
                            <td class="px-5 py-3 text-right text-emerald-400"><?= number_format((int) $row->masuk) ?></td>
                            <td class="px-5 py-3 text-right text-red-400"><?= number_format((int) $row->keluar) ?></td>
                            <td class="px-5 py-3 text-right font-bold header-title"><?= number_format((int) $row->stok_akhir) ?></td>
                            <td class="px-5 py-3 text-center"><span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold <?= $status_class ?>"><?= $status_label ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
