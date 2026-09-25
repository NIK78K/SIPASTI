<!-- ================= JUDUL ================= -->
<h1 class="text-2xl font-semibold tracking-tight mb-1 header-title">
    Transaksi Barang Persediaan
</h1>
<p class="text-sm text-gray-400 mb-6 header-subtitle">
    Catat dan pantau barang masuk & keluar persediaan
</p>

<!-- ================= SUMMARY ================= -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6 text-sm">

    <div class="relative overflow-hidden theme-card stat-card border rounded-xl p-4 hover:border-cyan-500/30 transition-all group">
        <div class="absolute top-0 right-0 w-20 h-20 bg-cyan-500/5 rounded-full blur-xl group-hover:bg-cyan-500/10 transition-all"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-2">
                <div class="text-gray-400 header-subtitle">Total Transaksi</div>
                <div class="w-8 h-8 bg-cyan-500/10 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-1 text-2xl font-semibold text-white"><?= $total ?? 0 ?></div>
        </div>
    </div>

    <div class="relative overflow-hidden theme-card stat-card border rounded-xl p-4 hover:border-green-500/30 transition-all group">
        <div class="absolute top-0 right-0 w-20 h-20 bg-green-500/5 rounded-full blur-xl group-hover:bg-green-500/10 transition-all"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-2">
                <div class="text-gray-400 header-subtitle">Masuk</div>
                <div class="w-8 h-8 bg-green-500/10 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-1 text-2xl font-semibold text-green-400"><?= $masuk ?? 0 ?></div>
            <?php if (($total ?? 0) > 0): ?>
            <div class="text-xs text-gray-600 mt-1"><?= round((($masuk ?? 0) / ($total ?? 0)) * 100, 1) ?>%</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="relative overflow-hidden theme-card stat-card border rounded-xl p-4 hover:border-red-500/30 transition-all group">
        <div class="absolute top-0 right-0 w-20 h-20 bg-red-500/5 rounded-full blur-xl group-hover:bg-red-500/10 transition-all"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-2">
                <div class="text-gray-400 header-subtitle">Keluar</div>
                <div class="w-8 h-8 bg-red-500/10 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-1 text-2xl font-semibold text-red-400"><?= $keluar ?? 0 ?></div>
            <?php if (($total ?? 0) > 0): ?>
            <div class="text-xs text-gray-600 mt-1"><?= round((($keluar ?? 0) / ($total ?? 0)) * 100, 1) ?>%</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="relative overflow-hidden theme-card stat-card border rounded-xl p-4 hover:border-yellow-500/30 transition-all group">
        <div class="absolute top-0 right-0 w-20 h-20 bg-yellow-500/5 rounded-full blur-xl group-hover:bg-yellow-500/10 transition-all"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-2">
                <div class="text-gray-400 header-subtitle">Total Unit</div>
                <div class="w-8 h-8 bg-yellow-500/10 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>
            <div class="mt-1 text-2xl font-semibold text-yellow-400"><?= $total_unit ?? 0 ?></div>
        </div>
    </div>

</div>

<!-- ================= ACTION BAR ================= -->
<div class="flex justify-end gap-2 mb-4">
            <button onclick="tambahTransaksi('masuk')" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v12m0 0l-4-4m4 4l4-4M4 20h16"/>
                </svg>
                Barang Masuk
            </button>
            <button onclick="tambahTransaksi('keluar')" class="inline-flex items-center gap-2 bg-rose-600 hover:bg-rose-500 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20V8m0 0l-4 4m4-4l4 4M4 4h16"/>
                </svg>
                Barang Keluar
            </button>
</div>

<!-- ================= FILTER ================= -->
<form id="filterForm" onsubmit="return false;" class="relative flex flex-wrap gap-3 mb-4 text-sm">
            <span id="filterLoading" class="hidden absolute -top-5 right-0 items-center gap-1.5 text-[11px] text-cyan-300/80"><span class="trx-dot"></span>Memuat...</span>
            <select id="f_jenis" name="jenis" onchange="queueTransaksi()" class="bg-slate-800/80 border border-slate-700/50 rounded-lg px-3 py-2 text-gray-200 hover:border-cyan-500/50 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all">
                <option value="" class="bg-slate-800 text-slate-400">Semua</option>
                <option value="masuk" class="bg-slate-800 text-white" <?= ($filter['jenis'] ?? '') === 'masuk' ? 'selected' : '' ?>>masuk</option>
                <option value="keluar" class="bg-slate-800 text-white" <?= ($filter['jenis'] ?? '') === 'keluar' ? 'selected' : '' ?>>keluar</option>
            </select>
            <select id="f_barang" name="barang_id" onchange="queueTransaksi()" class="bg-slate-800/80 border border-slate-700/50 rounded-lg px-3 py-2 text-gray-200 hover:border-cyan-500/50 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all">
                <option value="" class="bg-slate-800 text-slate-400">Semua</option>
                <?php foreach($barang_list as $brg): ?>
                    <option value="<?= $brg->id ?>" class="bg-slate-800 text-white" <?= (string)($filter['barang_id'] ?? '') === (string)$brg->id ? 'selected' : '' ?>><?= htmlspecialchars($brg->kode_barang . ' - ' . $brg->nama_barang) ?></option>
                <?php endforeach; ?>
            </select>
            <select id="f_bulan" name="bulan" onchange="queueTransaksi()" class="bg-slate-800/80 border border-slate-700/50 rounded-lg px-3 py-2 text-gray-200 hover:border-cyan-500/50 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all">
                <option value="" class="bg-slate-800 text-slate-400">Semua</option>
                <?php $nama_bulan = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember']; ?>
                <?php foreach($nama_bulan as $num => $nama): ?>
                    <option value="<?= $num ?>" class="bg-slate-800 text-white" <?= (string)($filter['bulan'] ?? '') === (string)$num ? 'selected' : '' ?>><?= $nama ?></option>
                <?php endforeach; ?>
            </select>
            <select id="f_tahun" name="tahun" onchange="queueTransaksi()" class="bg-slate-800/80 border border-slate-700/50 rounded-lg px-3 py-2 text-gray-200 hover:border-cyan-500/50 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all">
                <option value="" class="bg-slate-800 text-slate-400">Semua</option>
                <?php foreach($tahun_list as $thn): ?>
                    <option value="<?= $thn ?>" class="bg-slate-800 text-white" <?= (string)($filter['tahun'] ?? '') === (string)$thn ? 'selected' : '' ?>><?= $thn ?></option>
                <?php endforeach; ?>
            </select>
</form>

        <div class="overflow-x-auto border rounded-xl theme-card trx-table-wrap">
            <table class="w-full text-sm" style="table-layout:fixed;">
                <thead class="bg-slate-900/90 text-gray-300 border-b border-slate-700/50">
                    <tr>
                        <th class="px-4 py-3 text-left">No</th>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-left">Jenis</th>
                        <th class="px-4 py-3 text-left">Kode</th>
                        <th class="px-4 py-3 text-left">Nama Barang</th>
                        <th class="px-4 py-3 text-left">Jumlah</th>
                        <th class="px-4 py-3 text-left">Uraian</th>
                        <th class="px-4 py-3 text-left">Penerima/Sumber</th>
                        <th class="px-4 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tbodyTransaksi" class="divide-y divide-slate-800/30">
                    <?php if (empty($transaksi)): ?>
                    <tr>
                        <td colspan="9" class="px-6 py-10 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-12 h-12 bg-gray-800/50 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div class="text-gray-500">Belum ada data transaksi</div>
                            </div>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php $no = 1; foreach($transaksi as $t): ?>
                    <tr class="hover:bg-slate-800/50 transition-colors duration-150 group">
                        <td class="px-4 py-3 text-gray-500"><?= $no++ ?></td>
                        <td class="px-4 py-3 text-gray-400">
                            <div class="flex items-center gap-1.5 whitespace-nowrap">
                                <svg class="w-3.5 h-3.5 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <?= htmlspecialchars($t->tanggal) ?>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <?php if ($t->jenis === 'masuk'): ?>
                                <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold rounded-full bg-gradient-to-r from-green-500/15 to-emerald-500/15 text-green-400 ring-1 ring-green-500/30 whitespace-nowrap">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    masuk
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold rounded-full bg-gradient-to-r from-red-500/15 to-rose-500/15 text-red-400 ring-1 ring-red-500/30 whitespace-nowrap">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    keluar
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-mono text-cyan-400 font-semibold group-hover:text-cyan-300 transition-colors whitespace-nowrap">
                                <?= htmlspecialchars($t->kode_barang ?? '-') ?>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-200"><?= htmlspecialchars($t->nama_barang ?? '-') ?></div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-1 bg-slate-500/10 rounded-lg ring-1 ring-slate-500/20 text-gray-200 font-semibold text-xs whitespace-nowrap">
                                <?= htmlspecialchars($t->jumlah) ?> <?= htmlspecialchars($t->satuan ?? '') ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-200"><?= htmlspecialchars($t->uraian ?? '-') ?></td>
                        <td class="px-4 py-3 text-gray-200"><?= htmlspecialchars($t->penerima_sumber ?? '-') ?></td>
                        <td class="px-4 py-3">
                            <div class="flex gap-1.5">
                                <button onclick="editTransaksi(<?= $t->id ?>)"
                                        class="p-2 bg-amber-900/40 hover:bg-amber-800/50 text-amber-400 rounded transition-all duration-200 shadow-sm border border-amber-800/30 transform hover:scale-110"
                                        title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button onclick="hapusTransaksi(<?= $t->id ?>, '<?= addslashes($t->nama_barang ?? '') ?>')"
                                        class="p-2 bg-red-900/40 hover:bg-red-800/50 text-red-400 rounded transition-all duration-200 shadow-sm border border-red-800/30 transform hover:scale-110"
                                        title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>

<!-- Modal Tambah/Edit Transaksi -->
<div id="modalBarang" class="hidden fixed inset-0 bg-black/50 z-50 flex items-start justify-center p-4 pt-20">
    <div class="theme-card rounded-lg shadow-xl w-full max-w-lg border" onclick="event.stopPropagation()">
        <div id="modalHeader" class="bg-blue-600 px-5 py-4 flex items-center justify-between rounded-t-lg transition-colors">
            <h3 id="modalTitle" class="text-lg font-semibold text-white">Tambah Transaksi</h3>
            <button onclick="tutupModal()" class="text-white/80 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="formBarang" class="p-5 space-y-4">
            <input type="hidden" id="barang_id" name="id">

            <div>
                <label class="block text-sm font-medium header-subtitle mb-1.5">
                    Barang <span class="text-red-400">*</span>
                </label>
                <select id="trx_barang_id" name="barang_id" required
                        class="w-full px-3 py-2 bg-slate-800/80 border border-slate-700/50 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    <option value="" class="bg-slate-800 text-slate-400">-- Pilih Barang --</option>
                    <?php foreach($barang_list as $brg): ?>
                        <option value="<?= $brg->id ?>" class="bg-slate-800 text-white"><?= htmlspecialchars($brg->kode_barang . ' - ' . $brg->nama_barang) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium header-subtitle mb-1.5">
                        Jenis <span id="jenisBadge" class="ml-1 px-2 py-0.5 rounded-full text-xs font-medium border align-middle bg-emerald-900/40 text-emerald-400 border-emerald-800/40">masuk</span>
                    </label>
                    <select id="jenis" name="jenis" required
                            class="w-full px-3 py-2 bg-slate-800/80 border border-slate-700/50 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all disabled:opacity-70">
                        <option value="masuk" class="bg-slate-800 text-white">masuk</option>
                        <option value="keluar" class="bg-slate-800 text-white">keluar</option>
                    </select>
                    <p id="jenisHint" class="text-xs header-subtitle mt-1 opacity-70">Jenis terkunci sesuai tombol yang diklik.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium header-subtitle mb-1.5">
                        Tanggal <span class="text-red-400">*</span>
                    </label>
                    <input type="date" id="tanggal" name="tanggal" required value="<?= date('Y-m-d') ?>"
                           class="w-full px-3 py-2 bg-slate-800/80 border border-slate-700/50 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium header-subtitle mb-1.5">
                    Jumlah <span class="text-red-400">*</span>
                </label>
                <input type="number" id="jumlah" name="jumlah" required min="0" step="any"
                       class="w-full px-3 py-2 bg-slate-800/80 border border-slate-700/50 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-500 transition-all"
                       placeholder="Contoh: 10">
            </div>

            <div>
                <label class="block text-sm font-medium header-subtitle mb-1.5">Uraian</label>
                <input type="text" id="uraian" name="uraian" maxlength="255"
                       class="w-full px-3 py-2 bg-slate-800/80 border border-slate-700/50 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-500 transition-all"
                       placeholder="Opsional">
            </div>

            <div>
                <label class="block text-sm font-medium header-subtitle mb-1.5">Penerima/Sumber</label>
                <input type="text" id="penerima_sumber" name="penerima_sumber" maxlength="100"
                       class="w-full px-3 py-2 bg-slate-800/80 border border-slate-700/50 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-500 transition-all"
                       placeholder="Opsional">
            </div>

            <div class="flex gap-2 pt-3 border-t border-slate-700">
                <button type="button" onclick="tutupModal()"
                        class="flex-1 px-4 py-2 border border-slate-600 text-slate-300 rounded-lg hover:bg-slate-800 transition">
                    Batal
                </button>
                <button type="submit" id="btnSimpan"
                        class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition transform hover:scale-105 active:scale-95">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
html.light input, html.light select {
    background: rgba(255, 255, 255, 0.95) !important;
    color: var(--text-primary) !important;
    border-color: rgba(203, 213, 225, 0.8) !important;
}
.trx-table-wrap { min-height: 320px; }
#tbodyTransaksi tr td { height: 53px; }
.trx-dot { width: 8px; height: 8px; border-radius: 9999px; border: 2px solid rgba(103,232,249,.3); border-top-color: #67e8f9; animation: trx-spin .7s linear infinite; display: inline-block; }
@keyframes trx-spin { to { transform: rotate(360deg); } }
.trx-shimmer { position: relative; overflow: hidden; background: rgba(148,163,184,.12); border-radius: 6px; }
.trx-shimmer::after { content: ""; position: absolute; inset: 0; background: linear-gradient(90deg, transparent, rgba(148,163,184,.25), transparent); animation: trx-shimmer 1.4s ease-in-out infinite; }
@keyframes trx-shimmer { from { transform: translateX(-100%); } to { transform: translateX(100%); } }
.trx-row { animation: trx-in .18s ease-out both; }
@keyframes trx-in { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: none; } }
@media (prefers-reduced-motion: reduce) { .trx-row, .trx-shimmer::after, .trx-dot { animation: none !important; } }
</style>

<script>
let editMode = false;

function getSwalColors() {
    const isLight = document.documentElement.classList.contains('light');
    return {
        background: isLight ? '#ffffff' : '#1a1f2e',
        color: isLight ? '#0f172a' : '#f3f4f6'
    };
}

function esc(s) {
    return String(s ?? '').replace(/[&<>"']/g, function (c) {
        return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
}

function emptyRow() {
    return '<tr><td colspan="9" class="px-6 py-10 text-center"><div class="flex flex-col items-center gap-3"><div class="w-12 h-12 bg-gray-800/50 rounded-full flex items-center justify-center"><svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div><div class="text-gray-500">Belum ada data transaksi</div></div></td></tr>';
}

function rowHtml(t, no) {
    var badge = t.jenis === 'masuk'
        ? '<span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold rounded-full bg-gradient-to-r from-green-500/15 to-emerald-500/15 text-green-400 ring-1 ring-green-500/30 whitespace-nowrap"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>masuk</span>'
        : '<span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold rounded-full bg-gradient-to-r from-red-500/15 to-rose-500/15 text-red-400 ring-1 ring-red-500/30 whitespace-nowrap"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>keluar</span>';
    var namaJs = String(t.nama_barang ?? '').replace(/\\/g, '\\\\').replace(/'/g, "\\'");
    var dly = Math.min(Math.max(no - 1, 0), 9) * 25;
    return '<tr class="hover:bg-slate-800/50 transition-colors duration-150 group trx-row" style="animation-delay:' + dly + 'ms">'
        + '<td class="px-4 py-3 text-gray-500">' + no + '</td>'
        + '<td class="px-4 py-3 text-gray-400"><div class="flex items-center gap-1.5 whitespace-nowrap"><svg class="w-3.5 h-3.5 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>' + esc(t.tanggal) + '</div></td>'
        + '<td class="px-4 py-3">' + badge + '</td>'
        + '<td class="px-4 py-3"><div class="font-mono text-cyan-400 font-semibold group-hover:text-cyan-300 transition-colors whitespace-nowrap">' + esc(t.kode_barang ?? '-') + '</div></td>'
        + '<td class="px-4 py-3"><div class="font-medium text-gray-200">' + esc(t.nama_barang ?? '-') + '</div></td>'
        + '<td class="px-4 py-3"><span class="inline-flex items-center px-2.5 py-1 bg-slate-500/10 rounded-lg ring-1 ring-slate-500/20 text-gray-200 font-semibold text-xs whitespace-nowrap">' + esc(t.jumlah) + ' ' + esc(t.satuan ?? '') + '</span></td>'
        + '<td class="px-4 py-3 text-gray-200">' + esc(t.uraian ?? '-') + '</td>'
        + '<td class="px-4 py-3 text-gray-200">' + esc(t.penerima_sumber ?? '-') + '</td>'
        + '<td class="px-4 py-3"><div class="flex gap-1.5">'
        + '<button onclick="editTransaksi(' + t.id + ')" class="p-2 bg-amber-900/40 hover:bg-amber-800/50 text-amber-400 rounded transition-all duration-200 shadow-sm border border-amber-800/30 transform hover:scale-110" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>'
        + '<button onclick="hapusTransaksi(' + t.id + ", '" + namaJs + '\')" class="p-2 bg-red-900/40 hover:bg-red-800/50 text-red-400 rounded transition-all duration-200 shadow-sm border border-red-800/30 transform hover:scale-110" title="Hapus"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>'
        + '</div></td></tr>';
}

let trxAbort = null, trxReqId = 0, trxTimer = null, trxLastGood = null;

function setFilterLoading(on) {
    var el = document.getElementById('filterLoading');
    if (!el) return;
    el.classList.toggle('hidden', !on);
    el.classList.toggle('flex', !!on);
}

function skeletonRows() {
    var cells = '';
    var widths = ['28px', '90px', '70px', '70px', '130px', '80px', '120px', '110px', '64px'];
    for (var c = 0; c < 9; c++) cells += '<td class="px-4 py-3"><div class="trx-shimmer" style="height:16px;width:' + widths[c] + ';max-width:100%"></div></td>';
    var html = '';
    for (var r = 0; r < 4; r++) html += '<tr>' + cells + '</tr>';
    return html;
}

function queueTransaksi() {
    clearTimeout(trxTimer);
    trxTimer = setTimeout(loadTransaksi, 220);
}

function loadTransaksi() {
    var tbody = document.getElementById('tbodyTransaksi');
    var params = new URLSearchParams({
        jenis: document.getElementById('f_jenis').value,
        barang_id: document.getElementById('f_barang').value,
        bulan: document.getElementById('f_bulan').value,
        tahun: document.getElementById('f_tahun').value
    });
    if (trxAbort) trxAbort.abort();
    trxAbort = new AbortController();
    var myId = ++trxReqId;
    var prev = tbody.innerHTML;
    if (trxLastGood === null && prev && prev.indexOf('trx-shimmer') === -1) trxLastGood = prev;
    tbody.innerHTML = skeletonRows();
    setFilterLoading(true);
    fetch('<?= base_url("transaksi_persediaan/list_ajax") ?>' + '?' + params.toString(), { signal: trxAbort.signal })
    .then(function (r) { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
    .then(function (data) {
        if (myId !== trxReqId) return;
        setFilterLoading(false);
        if (!data || !data.length) {
            tbody.innerHTML = emptyRow();
        } else {
            tbody.innerHTML = data.map(function (t, i) { return rowHtml(t, i + 1); }).join('');
        }
        trxLastGood = tbody.innerHTML;
    })
    .catch(function (e) {
        if (myId !== trxReqId) return;
        if (e && e.name === 'AbortError') return;
        setFilterLoading(false);
        tbody.innerHTML = trxLastGood || prev;
        if (window.Swal) {
            var c = getSwalColors();
            Swal.fire({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2200, icon: 'error', title: 'Gagal memuat data', background: c.background, color: c.color });
        }
    });
}

function setJenisMode(jenis, locked) {
    const sel = document.getElementById('jenis');
    const badge = document.getElementById('jenisBadge');
    const header = document.getElementById('modalHeader');
    const btn = document.getElementById('btnSimpan');
    sel.value = jenis;
    sel.disabled = !!locked;
    badge.textContent = jenis;
    badge.className = 'ml-1 px-2 py-0.5 rounded-full text-xs font-medium border align-middle ' + (jenis === 'masuk'
        ? 'bg-emerald-900/40 text-emerald-400 border-emerald-800/40'
        : 'bg-red-900/40 text-red-400 border-red-800/40');
    document.getElementById('jenisHint').style.display = locked ? '' : 'none';
    header.className = 'px-5 py-4 flex items-center justify-between rounded-t-lg transition-colors ' + (jenis === 'masuk' ? 'bg-emerald-600' : locked ? 'bg-rose-600' : 'bg-blue-600');
    btn.className = 'flex-1 px-4 py-2 text-white rounded-lg transition transform hover:scale-105 active:scale-95 ' + (jenis === 'masuk' ? 'bg-emerald-600 hover:bg-emerald-700' : locked ? 'bg-rose-600 hover:bg-rose-700' : 'bg-blue-600 hover:bg-blue-700');
}

function tambahTransaksi(jenis) {
    jenis = (jenis === 'keluar') ? 'keluar' : 'masuk';
    editMode = false;
    document.getElementById('modalTitle').textContent = jenis === 'masuk' ? 'Tambah Barang Masuk' : 'Tambah Barang Keluar';
    document.getElementById('formBarang').reset();
    document.getElementById('barang_id').value = '';
    document.getElementById('tanggal').value = '<?= date('Y-m-d') ?>';
    setJenisMode(jenis, true);
    document.getElementById('modalBarang').classList.remove('hidden');
}

function editTransaksi(id) {
    fetch('<?= base_url("transaksi_persediaan/get/") ?>' + id)
    .then(r => r.json())
    .then(d => {
        if (d.status === 'error') {
            const c = getSwalColors();
            return Swal.fire({ icon: 'error', title: 'Gagal!', text: d.message, ...c });
        }
        editMode = true;
        document.getElementById('modalTitle').textContent = 'Edit Transaksi';
        document.getElementById('barang_id').value = d.id;
        document.getElementById('trx_barang_id').value = d.barang_id;
        setJenisMode(d.jenis, false);
        document.getElementById('jenis').disabled = false;
        document.getElementById('tanggal').value = d.tanggal;
        document.getElementById('jumlah').value = d.jumlah;
        document.getElementById('uraian').value = d.uraian || '';
        document.getElementById('penerima_sumber').value = d.penerima_sumber || '';
        document.getElementById('modalBarang').classList.remove('hidden');
    });
}

document.getElementById('formBarang').addEventListener('submit', function(e) {
    e.preventDefault();
    const jenisSel = document.getElementById('jenis');
    const wasDisabled = jenisSel.disabled;
    jenisSel.disabled = false;
    const formData = new FormData(this);
    jenisSel.disabled = wasDisabled;
    const url = editMode ? '<?= base_url("transaksi_persediaan/update") ?>' : '<?= base_url("transaksi_persediaan/tambah") ?>';

    const colors = getSwalColors();
    Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, allowEscapeKey: false, didOpen: () => Swal.showLoading(), ...colors });

    fetch(url, { method: 'POST', body: formData })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            const c = getSwalColors();
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: data.message, timer: 1500, ...c })
            .then(() => { tutupModal(); loadTransaksi(); });
        } else {
            const c = getSwalColors();
            Swal.fire({ icon: 'error', title: 'Gagal!', text: data.message, ...c });
        }
    });
});

function hapusTransaksi(id, nama) {
    const colors = getSwalColors();
    Swal.fire({
        title: 'Hapus Transaksi?',
        text: `Yakin ingin menghapus transaksi "${nama}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#ef4444',
        reverseButtons: true,
        ...colors
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('<?= base_url("transaksi_persediaan/hapus/") ?>' + id, { method: 'POST' })
            .then(r => r.json())
            .then(data => {
                const c = getSwalColors();
                Swal.fire({ icon: data.status, title: data.status === 'success' ? 'Terhapus!' : 'Gagal!', text: data.message, ...c })
                .then(() => { if (data.status === 'success') { tutupModal(); loadTransaksi(); } });
            });
        }
    });
}

function tutupModal() {
    document.getElementById('modalBarang').classList.add('hidden');
}

document.getElementById('modalBarang').addEventListener('click', function(e) {
    if (e.target === this) tutupModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') tutupModal();
});
</script>
