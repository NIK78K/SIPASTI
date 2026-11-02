<!-- PAGE TITLE -->
<div class="flex items-center justify-between mb-10">
    <div>
        <h1 class="text-3xl font-extrabold header-title tracking-tight">
            Master Stok Awal Persediaan
        </h1>
        <p class="text-sm header-subtitle mt-1">
            Stok awal barang persediaan per periode bulan & tahun
        </p>
    </div>

    <button onclick="tambahStok()"
        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl
               bg-blue-600 hover:bg-blue-700
               text-white text-sm font-semibold
               shadow-lg transition-all transform hover:scale-105 active:scale-95">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Input Stok Awal
    </button>
</div>

<!-- TABLE CARD -->
<div id="tabelStok" class="rounded-2xl border theme-card shadow-xl overflow-visible relative z-20">

    <!-- TOOLBAR -->
    <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center gap-3 justify-between border-b border-slate-800/50 relative z-30">
        <p id="stokCount" class="text-sm header-subtitle">
            <?= count($stok_awal) ?> data stok awal terdaftar
        </p>
        <div class="custom-dropdown-container relative z-30 w-full sm:max-w-xs" id="filterStokToolbarWrap">
            <input type="hidden" id="stokSearch" value="">
            
            <!-- Trigger Button -->
            <button type="button" class="dropdown-trigger w-full px-4 py-2.5 rounded-xl bg-slate-800/80 border border-slate-700/50 text-white text-sm hover:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all flex items-center justify-between text-left">
                <span class="dropdown-label truncate text-slate-300">Semua Barang</span>
                <svg class="dropdown-chevron w-4 h-4 text-slate-400 transition-transform duration-200 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <!-- Dropdown Panel -->
            <div class="dropdown-panel hidden absolute right-0 top-full mt-1.5 z-50 min-w-[280px] w-full max-w-[340px] rounded-2xl shadow-2xl border border-slate-700 bg-slate-800 p-3 space-y-2">
                <!-- Search Box (Q Search X) -->
                <div class="flex items-center bg-slate-900/80 border border-slate-700/70 rounded-xl px-3 py-2 focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-500/20 transition-all">
                    <svg class="w-4 h-4 text-slate-400 flex-shrink-0 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" class="dropdown-search w-full bg-transparent text-sm text-slate-200 placeholder-slate-500 focus:outline-none" placeholder="Search" autocomplete="off">
                    <button type="button" class="dropdown-clear hidden text-slate-400 hover:text-slate-200 p-0.5 ml-1 transition" title="Clear">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Options List -->
                <div class="dropdown-options max-h-56 overflow-y-auto space-y-0.5 pr-1 text-sm">
                    <div class="dropdown-opt px-3 py-2 rounded-xl text-slate-300 font-medium hover:bg-blue-600/20 hover:text-blue-300 cursor-pointer transition-colors flex items-center justify-between is-selected bg-blue-600/20 text-blue-300 font-semibold"
                         data-value=""
                         data-label="Semua Barang">
                        <span class="truncate">Semua Barang</span>
                        <svg class="opt-check w-4 h-4 text-blue-400 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <?php foreach(($barang_list ?? []) as $brg): ?>
                        <div class="dropdown-opt px-3 py-2 rounded-xl text-slate-300 font-medium hover:bg-blue-600/20 hover:text-blue-300 cursor-pointer transition-colors flex items-center justify-between"
                             data-value="<?= htmlspecialchars(strtolower($brg->kode_barang . ' ' . $brg->nama_barang)) ?>"
                             data-label="<?= htmlspecialchars($brg->kode_barang . ' - ' . $brg->nama_barang) ?>">
                            <span class="truncate"><?= htmlspecialchars($brg->kode_barang . ' - ' . $brg->nama_barang) ?></span>
                            <svg class="opt-check hidden w-4 h-4 text-blue-400 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    <?php endforeach; ?>
                    <div class="dropdown-empty hidden py-4 text-center text-xs text-slate-400 font-medium">
                        Barang tidak ditemukan
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLE RESPONSIVE WRAPPER -->
    <div class="overflow-x-auto">
        <div class="min-w-[960px]">
            <!-- HEADER -->
            <div class="grid grid-cols-[50px_130px_1fr_160px_110px_110px_1.2fr_100px]
                        px-6 py-4 text-sm header-subtitle
                        border-b border-slate-800/50 bg-slate-900/90">
                <div>No</div>
                <div>Kode</div>
                <div>Nama Barang</div>
                <div>Kategori</div>
                <div class="text-center">Periode</div>
                <div class="text-center">Jumlah</div>
                <div>Keterangan</div>
                <div class="text-right">Aksi</div>
            </div>

            <!-- BODY -->
            <div id="stokTable" class="divide-y divide-slate-800/50">
                <?php if (empty($stok_awal)): ?>
                    <div class="px-6 py-10 text-center header-subtitle">
                        Belum ada data stok awal.
                    </div>
                <?php else: ?>
                    <?php $no = 1; foreach($stok_awal as $st): ?>
                    <div class="stok-row grid grid-cols-[50px_130px_1fr_160px_110px_110px_1.2fr_100px]
                                px-6 py-4 items-center
                                hover:bg-slate-800/50 transition-colors"
                         data-search="<?= htmlspecialchars(strtolower(($st->kode_barang ?? '') . ' ' . ($st->nama_barang ?? '') . ' ' . ($st->kategori_kode ?? '') . ' ' . ($st->kategori_nama ?? '') . ' ' . ($st->bulan ?? '') . '/' . ($st->tahun ?? '') . ' ' . ($st->keterangan ?? ''))) ?>">
                        <div class="header-subtitle"><?= $no++ ?></div>

                        <div>
                            <span class="inline-flex px-3 py-1 rounded-full
                                         text-xs font-medium
                                         bg-indigo-500/15 text-indigo-400
                                         border border-indigo-500/25 whitespace-nowrap">
                                <?= htmlspecialchars($st->kode_barang ?? '-') ?>
                            </span>
                        </div>

                        <div class="font-medium header-title">
                            <?= htmlspecialchars($st->nama_barang ?? '-') ?>
                        </div>

                        <div>
                            <span class="inline-flex px-3 py-1 rounded-full
                                         text-xs font-medium
                                         bg-purple-500/15 text-purple-400
                                         border border-purple-500/25 whitespace-nowrap">
                                <?= htmlspecialchars($st->kategori_kode ?? '-') ?>
                            </span>
                            <div class="text-xs header-subtitle mt-1"><?= htmlspecialchars($st->kategori_nama ?? '') ?></div>
                        </div>

                        <div class="text-center header-subtitle text-sm whitespace-nowrap">
                            <?= htmlspecialchars($st->bulan ?? '-') ?>/<?= htmlspecialchars($st->tahun ?? '-') ?>
                        </div>

                        <div class="text-center">
                            <span class="inline-flex px-3 py-1 rounded-full
                                         text-xs font-medium
                                         bg-blue-500/15 text-blue-400
                                         border border-blue-500/25 whitespace-nowrap">
                                <?= htmlspecialchars($st->jumlah ?? 0) ?> <?= htmlspecialchars($st->satuan ?? '') ?>
                            </span>
                        </div>

                        <div class="header-subtitle text-sm">
                            <?= htmlspecialchars($st->keterangan ?? '-') ?>
                        </div>

                        <div class="flex justify-end gap-2">
                            <button onclick="editStok(<?= $st->id ?>)"
                                class="w-9 h-9 flex items-center justify-center
                                       rounded-lg bg-yellow-500/15
                                       text-yellow-400 hover:bg-yellow-500/25 transition-all transform hover:scale-110"
                                title="Edit Stok Awal">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                     stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                </svg>
                            </button>
                            <button onclick="hapusStok(<?= $st->id ?>, '<?= addslashes($st->nama_barang ?? '') ?>')"
                                class="w-9 h-9 flex items-center justify-center
                                       rounded-lg bg-red-500/15
                                       text-red-400 hover:bg-red-500/25 transition-all transform hover:scale-110"
                                title="Hapus Stok Awal">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                     stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <div id="stokEmpty" class="hidden px-6 py-10 text-center header-subtitle">
                    Data tidak ditemukan
                </div>
            </div>
        </div>
    </div>

    <!-- PAGINATION -->
    <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center gap-3 justify-between border-t border-slate-800/50">
        <p id="stokInfo" class="text-sm header-subtitle"></p>
        <div id="stokPagination" class="flex items-center gap-1.5"></div>
    </div>
</div>

<!-- Modal Tambah/Edit Stok Awal -->
<div id="modalBarang" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur flex items-center justify-center p-4">
    <div class="theme-card w-full max-w-2xl rounded-2xl border p-6 shadow-2xl flex flex-col max-h-[90vh] overflow-hidden" onclick="event.stopPropagation()">
        <h3 id="modalTitle" class="text-lg font-semibold header-title mb-1">Tambah Stok Awal</h3>
        <p id="modalSubtitle" class="text-sm header-subtitle mb-5">Isi stok awal barang per periode bulan dan tahun</p>

        <form id="formBarang" class="space-y-4 overflow-y-auto flex-1 min-h-0">
            <input type="hidden" id="barang_id" name="id">

            <div>
                <label for="stok_barang_id" class="block text-sm font-medium header-subtitle mb-1.5">
                    Barang <span class="text-red-400">*</span>
                </label>
                <div class="custom-dropdown-container relative z-30" id="stokBarangDropdownWrap">
                    <input type="hidden" id="stok_barang_id" name="barang_id" class="barang-hidden-input" required>
                    
                    <!-- Trigger Button -->
                    <button type="button" class="dropdown-trigger w-full px-4 py-3 rounded-xl mb-1 bg-slate-800/80 border border-slate-700/50 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all flex items-center justify-between text-left">
                        <span class="dropdown-label truncate text-slate-400 text-sm">-- Pilih Barang --</span>
                        <svg class="dropdown-chevron w-4 h-4 text-slate-400 transition-transform duration-200 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Dropdown Panel -->
                    <div class="dropdown-panel hidden absolute left-0 right-0 top-full mt-1.5 z-50 rounded-2xl shadow-2xl border border-slate-700 bg-slate-800 p-3 space-y-2">
                        <!-- Search Box (Q Search X) -->
                        <div class="flex items-center bg-slate-900/80 border border-slate-700/70 rounded-xl px-3 py-2 focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-500/20 transition-all">
                            <svg class="w-4 h-4 text-slate-400 flex-shrink-0 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" class="dropdown-search w-full bg-transparent text-sm text-slate-200 placeholder-slate-500 focus:outline-none" placeholder="Search" autocomplete="off">
                            <button type="button" class="dropdown-clear hidden text-slate-400 hover:text-slate-200 p-0.5 ml-1 transition" title="Clear">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Options List -->
                        <div class="dropdown-options max-h-52 overflow-y-auto space-y-0.5 pr-1 text-sm">
                            <?php foreach(($barang_list ?? []) as $brg): ?>
                                <div class="dropdown-opt px-3 py-2 rounded-xl text-slate-300 font-medium hover:bg-blue-600/20 hover:text-blue-300 cursor-pointer transition-colors flex items-center justify-between"
                                     data-value="<?= $brg->id ?>"
                                     data-label="<?= htmlspecialchars($brg->kode_barang . ' - ' . $brg->nama_barang) ?>">
                                    <span class="truncate"><?= htmlspecialchars($brg->kode_barang . ' - ' . $brg->nama_barang) ?></span>
                                    <svg class="opt-check hidden w-4 h-4 text-blue-400 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            <?php endforeach; ?>
                            <div class="dropdown-empty hidden py-4 text-center text-xs text-slate-400 font-medium">
                                Barang tidak ditemukan
                            </div>
                        </div>
                    </div>
                </div>
                <p class="text-xs header-subtitle">Pilih barang persediaan, wajib diisi.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="bulan" class="block text-sm font-medium header-subtitle mb-1.5">
                        Bulan <span class="text-red-400">*</span>
                    </label>
                    <input type="number" id="bulan" name="bulan" required min="1" max="12" value="<?= date('n') ?>"
                           placeholder="Contoh: 1"
                           class="w-full px-4 py-3 rounded-xl mb-1 bg-slate-800/80 border border-slate-700/50 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                    <p class="text-xs header-subtitle">Angka 1-12 sesuai bulan periode.</p>
                </div>
                <div>
                    <label for="tahun" class="block text-sm font-medium header-subtitle mb-1.5">
                        Tahun <span class="text-red-400">*</span>
                    </label>
                    <input type="number" id="tahun" name="tahun" required min="2000" max="2100" value="<?= date('Y') ?>"
                           placeholder="Contoh: 2026"
                           class="w-full px-4 py-3 rounded-xl mb-1 bg-slate-800/80 border border-slate-700/50 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                    <p class="text-xs header-subtitle">Tahun periode, contoh: 2026.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="jumlah" class="block text-sm font-medium header-subtitle mb-1.5">
                        Jumlah <span class="text-red-400">*</span>
                    </label>
                    <input type="number" id="jumlah" name="jumlah" required min="0" step="any"
                           class="w-full px-4 py-3 rounded-xl mb-1 bg-slate-800/80 border border-slate-700/50 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
                           placeholder="Contoh: 100">
                    <p class="text-xs header-subtitle">Jumlah awal minimal 0.</p>
                </div>

                <div>
                    <label for="keterangan" class="block text-sm font-medium header-subtitle mb-1.5">Keterangan <span class="header-subtitle font-normal">(opsional)</span></label>
                    <input type="text" id="keterangan" name="keterangan" maxlength="255"
                           class="w-full px-4 py-3 rounded-xl mb-1 bg-slate-800/80 border border-slate-700/50 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
                           placeholder="Contoh: Stok awal Januari gudang utama">
                    <p class="text-xs header-subtitle">Keterangan tambahan, boleh dikosongkan.</p>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="tutupModal()"
                        class="px-4 py-2 header-subtitle hover:text-white transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold transition-all transform hover:scale-105 active:scale-95">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
#stokPagination {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    justify-content: flex-end;
    align-items: center;
    max-width: 100%;
}
.stok-page-btn {
    width: 2.25rem;
    height: 2.25rem;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.625rem;
    font-size: 0.8125rem;
    border: 1px solid rgba(71, 85, 105, 0.4);
    background: rgba(30, 41, 59, 0.5);
    color: #94a3b8;
    transition: all 0.2s;
    flex-shrink: 0;
}
@media (max-width: 640px) {
    #stokPagination { justify-content: center; }
}
.stok-page-btn:hover { background: rgba(30, 41, 59, 0.9); color: #e2e8f0; }
.stok-page-btn.active { background: #2563eb; border-color: #3b82f6; color: #fff; font-weight: 600; }
.stok-page-btn:disabled { opacity: 0.4; cursor: not-allowed; }
html.light input, html.light select {
    background: rgba(255, 255, 255, 0.95) !important;
    color: var(--text-primary) !important;
    border-color: rgba(203, 213, 225, 0.8) !important;
}

html.light .hover\:bg-slate-800\/50:hover {
    background: rgba(248, 250, 252, 0.6) !important;
}

html.light #modalBarang {
    background: rgba(0, 0, 0, 0.4) !important;
}

html.light input::placeholder { color: #94a3b8 !important; }
</style>

<script>
let editMode = false;

const stokPerPage = 15;
let stokPage = 1;
let stokFiltered = [];

function esc(s) {
    return String(s ?? '-').replace(/[&<>"']/g, function(c) {
        return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];
    });
}

function getSwalColors() {
    const isLight = document.documentElement.classList.contains('light');
    return {
        background: isLight ? '#ffffff' : '#1a1f2e',
        color: isLight ? '#0f172a' : '#f3f4f6'
    };
}

function applyStokFilter(resetPage = true) {
    const q = (document.getElementById('stokSearch').value || '').toLowerCase().trim();
    const all = Array.from(document.querySelectorAll('.stok-row'));
    stokFiltered = !q ? all : all.filter(r => (r.dataset.search || '').includes(q));
    if (resetPage) stokPage = 1;
    renderStok();
}

function renderStok() {
    const all = Array.from(document.querySelectorAll('.stok-row'));
    const total = stokFiltered.length;
    const pages = Math.max(1, Math.ceil(total / stokPerPage));
    if (stokPage > pages) stokPage = pages;
    const start = total === 0 ? 0 : (stokPage - 1) * stokPerPage + 1;
    const end = Math.min(total, stokPage * stokPerPage);
    const visibleRows = new Set(stokFiltered.slice(start - 1, end));

    all.forEach((row) => {
        row.classList.toggle('hidden', !visibleRows.has(row));
        const rowNumber = stokFiltered.indexOf(row) + 1;
        if (row.firstElementChild) row.firstElementChild.textContent = rowNumber > 0 ? rowNumber : '';
    });

    document.getElementById('stokEmpty').classList.toggle('hidden', total !== 0);
    document.getElementById('stokInfo').textContent =
        total === 0 ? 'Menampilkan 0 data' : `Menampilkan ${start} sampai ${end} dari ${total} data`;

    const pag = document.getElementById('stokPagination');
    pag.innerHTML = '';
    const mkBtn = (label, page, disabled = false, active = false, title = '') => {
        const b = document.createElement('button');
        b.type = 'button';
        b.textContent = label;
        if (title) b.title = title;
        b.className = 'stok-page-btn' + (active ? ' active' : '');
        b.disabled = disabled;
        b.addEventListener('click', () => {
            if (disabled || page === stokPage) return;
            stokPage = page;
            renderStok();
        });
        return b;
    };
    pag.appendChild(mkBtn('‹', Math.max(1, stokPage - 1), stokPage === 1, false, 'Sebelumnya'));

    const pageItems = [];
    if (pages <= 7) {
        for (let p = 1; p <= pages; p++) pageItems.push(p);
    } else {
        pageItems.push(1);
        if (stokPage > 4) pageItems.push('ellipsis-left');
        for (let p = Math.max(2, stokPage - 1); p <= Math.min(pages - 1, stokPage + 1); p++) {
            pageItems.push(p);
        }
        if (stokPage < pages - 3) pageItems.push('ellipsis-right');
        pageItems.push(pages);
    }
    pageItems.forEach((item) => {
        if (typeof item === 'string') {
            const s = document.createElement('span');
            s.textContent = '…';
            s.className = 'px-1 header-subtitle';
            pag.appendChild(s);
            return;
        }
        pag.appendChild(mkBtn(String(item), item, false, item === stokPage));
    });

    pag.appendChild(mkBtn('›', Math.min(pages, stokPage + 1), stokPage === pages, false, 'Selanjutnya'));
}

(function() {
    var container = document.getElementById('filterStokToolbarWrap');
    if (!container) return;

    var hiddenInput = document.getElementById('stokSearch');
    var trigger = container.querySelector('.dropdown-trigger');
    var label = container.querySelector('.dropdown-label');
    var chevron = container.querySelector('.dropdown-chevron');
    var panel = container.querySelector('.dropdown-panel');
    var search = container.querySelector('.dropdown-search');
    var clearBtn = container.querySelector('.dropdown-clear');
    var options = container.querySelectorAll('.dropdown-opt');
    var emptyMsg = container.querySelector('.dropdown-empty');

    function closeDropdown() {
        panel.classList.add('hidden');
        chevron.classList.remove('rotate-180');
        container.style.zIndex = '';
    }

    function openDropdown() {
        container.style.zIndex = '60';
        panel.classList.remove('hidden');
        chevron.classList.add('rotate-180');
        search.value = '';
        clearBtn.classList.add('hidden');
        filterOptions('');
        setTimeout(function() { search.focus(); }, 50);
    }

    trigger.addEventListener('click', function(e) {
        e.stopPropagation();
        if (panel.classList.contains('hidden')) {
            openDropdown();
        } else {
            closeDropdown();
        }
    });

    function filterOptions(q) {
        q = (q || '').trim().toLowerCase();
        var matchCount = 0;
        options.forEach(function(opt) {
            var text = (opt.getAttribute('data-label') || '').toLowerCase();
            if (!q || text.indexOf(q) !== -1) {
                opt.classList.remove('hidden');
                matchCount++;
            } else {
                opt.classList.add('hidden');
            }
        });
        if (matchCount === 0) {
            emptyMsg.classList.remove('hidden');
        } else {
            emptyMsg.classList.add('hidden');
        }
    }

    search.addEventListener('input', function() {
        var val = this.value;
        if (val.length > 0) {
            clearBtn.classList.remove('hidden');
        } else {
            clearBtn.classList.add('hidden');
        }
        filterOptions(val);
    });

    search.addEventListener('click', function(e) { e.stopPropagation(); });

    clearBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        search.value = '';
        clearBtn.classList.add('hidden');
        filterOptions('');
        search.focus();
    });

    options.forEach(function(opt) {
        opt.addEventListener('click', function(e) {
            e.stopPropagation();
            var val = opt.getAttribute('data-value');
            var txt = opt.getAttribute('data-label');

            hiddenInput.value = val;
            label.textContent = txt;
            if (val === '') {
                label.className = 'dropdown-label truncate text-slate-300';
            } else {
                label.className = 'dropdown-label truncate text-white font-medium';
            }

            options.forEach(function(o) {
                var check = o.querySelector('.opt-check');
                if (o === opt) {
                    o.classList.add('bg-blue-600/20', 'text-blue-300', 'font-semibold', 'is-selected');
                    if (check) check.classList.remove('hidden');
                } else {
                    o.classList.remove('bg-blue-600/20', 'text-blue-300', 'font-semibold', 'is-selected');
                    if (check) check.classList.add('hidden');
                }
            });

            closeDropdown();
            applyStokFilter(true);
        });
    });

    document.addEventListener('click', function(e) {
        if (!container.contains(e.target)) {
            closeDropdown();
        }
    });
})();
stokFiltered = Array.from(document.querySelectorAll('.stok-row'));
renderStok();

function tambahStok() {
    editMode = false;
    document.getElementById('modalTitle').textContent = 'Tambah Stok Awal';
    document.getElementById('modalSubtitle').textContent = 'Isi stok awal barang per periode bulan dan tahun';
    document.getElementById('formBarang').reset();
    document.getElementById('barang_id').value = '';
    setStokBarangDropdownValue('');
    document.getElementById('modalBarang').classList.remove('hidden');
}

function editStok(id) {
    fetch('<?= base_url("stok_awal_persediaan/get/") ?>' + id)
    .then(r => r.json())
    .then(d => {
        if (d.status === 'error') {
            const c = getSwalColors();
            return Swal.fire({ icon: 'error', title: 'Gagal!', text: d.message, ...c });
        }
        editMode = true;
        document.getElementById('modalTitle').textContent = 'Edit Stok Awal';
        document.getElementById('modalSubtitle').textContent = 'Ubah stok awal barang lalu simpan';
        document.getElementById('barang_id').value = d.id;
        document.getElementById('stok_barang_id').value = d.barang_id;
        setStokBarangDropdownValue(d.barang_id);
        document.getElementById('bulan').value = d.bulan;
        document.getElementById('tahun').value = d.tahun;
        document.getElementById('jumlah').value = d.jumlah;
        document.getElementById('keterangan').value = d.keterangan || '';
        document.getElementById('modalBarang').classList.remove('hidden');
    });
}

document.getElementById('formBarang').addEventListener('submit', function(e) {
    e.preventDefault();

    const brgId = document.getElementById('stok_barang_id').value;
    if (!brgId) {
        const c = getSwalColors();
        Swal.fire({ icon: 'warning', title: 'Pilih Barang', text: 'Barang persediaan wajib dipilih.', ...c });
        return;
    }

    const formData = new FormData(this);
    const url = editMode ? '<?= base_url("stok_awal_persediaan/update") ?>' : '<?= base_url("stok_awal_persediaan/tambah") ?>';

    const colors = getSwalColors();
    Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, allowEscapeKey: false, didOpen: () => Swal.showLoading(), ...colors });

    fetch(url, { method: 'POST', body: formData })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            const c = getSwalColors();
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: data.message, timer: 1500, ...c })
            .then(() => location.reload());
        } else {
            const c = getSwalColors();
            Swal.fire({ icon: 'error', title: 'Gagal!', text: data.message, ...c });
        }
    });
});

function hapusStok(id, nama) {
    const colors = getSwalColors();
    Swal.fire({
        title: 'Hapus Stok Awal?',
        text: `Yakin ingin menghapus stok awal "${nama}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#ef4444',
        reverseButtons: true,
        ...colors
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('<?= base_url("stok_awal_persediaan/hapus/") ?>' + id, { method: 'POST' })
            .then(r => r.json())
            .then(data => {
                const c = getSwalColors();
                Swal.fire({ icon: data.status, title: data.status === 'success' ? 'Terhapus!' : 'Gagal!', text: data.message, ...c })
                .then(() => { if (data.status === 'success') location.reload(); });
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

function setStokBarangDropdownValue(val) {
    const wrap = document.getElementById('stokBarangDropdownWrap');
    if (!wrap) return;
    const hidden = wrap.querySelector('#stok_barang_id');
    const label = wrap.querySelector('.dropdown-label');
    const opts = wrap.querySelectorAll('.dropdown-opt');
    hidden.value = val || '';
    let found = false;
    opts.forEach(opt => {
        const check = opt.querySelector('.opt-check');
        if (val && String(opt.getAttribute('data-value')) === String(val)) {
            opt.classList.add('bg-blue-600/20', 'text-blue-300', 'font-semibold', 'is-selected');
            if (check) check.classList.remove('hidden');
            label.textContent = opt.getAttribute('data-label');
            label.className = 'dropdown-label truncate text-white text-sm font-medium';
            found = true;
        } else {
            opt.classList.remove('bg-blue-600/20', 'text-blue-300', 'font-semibold', 'is-selected');
            if (check) check.classList.add('hidden');
        }
    });
    if (!found) {
        label.textContent = '-- Pilih Barang --';
        label.className = 'dropdown-label truncate text-slate-400 text-sm';
    }
}

(function() {
    var container = document.getElementById('stokBarangDropdownWrap');
    if (!container) return;

    var hiddenInput = container.querySelector('#stok_barang_id');
    var trigger = container.querySelector('.dropdown-trigger');
    var chevron = container.querySelector('.dropdown-chevron');
    var panel = container.querySelector('.dropdown-panel');
    var search = container.querySelector('.dropdown-search');
    var clearBtn = container.querySelector('.dropdown-clear');
    var options = container.querySelectorAll('.dropdown-opt');
    var emptyMsg = container.querySelector('.dropdown-empty');

    function closeDropdown() {
        panel.classList.add('hidden');
        chevron.classList.remove('rotate-180');
        container.style.zIndex = '';
    }

    function openDropdown() {
        container.style.zIndex = '60';
        panel.classList.remove('hidden');
        chevron.classList.add('rotate-180');
        search.value = '';
        clearBtn.classList.add('hidden');
        filterOptions('');
        setTimeout(function() { search.focus(); }, 50);
    }

    trigger.addEventListener('click', function(e) {
        e.stopPropagation();
        if (panel.classList.contains('hidden')) {
            openDropdown();
        } else {
            closeDropdown();
        }
    });

    function filterOptions(q) {
        q = (q || '').trim().toLowerCase();
        var matchCount = 0;
        options.forEach(function(opt) {
            var text = (opt.getAttribute('data-label') || '').toLowerCase();
            if (!q || text.indexOf(q) !== -1) {
                opt.classList.remove('hidden');
                matchCount++;
            } else {
                opt.classList.add('hidden');
            }
        });
        if (matchCount === 0) {
            emptyMsg.classList.remove('hidden');
        } else {
            emptyMsg.classList.add('hidden');
        }
    }

    search.addEventListener('input', function() {
        var val = this.value;
        if (val.length > 0) {
            clearBtn.classList.remove('hidden');
        } else {
            clearBtn.classList.add('hidden');
        }
        filterOptions(val);
    });

    search.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    clearBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        search.value = '';
        clearBtn.classList.add('hidden');
        filterOptions('');
        search.focus();
    });

    options.forEach(function(opt) {
        opt.addEventListener('click', function(e) {
            e.stopPropagation();
            var val = opt.getAttribute('data-value');
            setStokBarangDropdownValue(val);
            closeDropdown();
            hiddenInput.dispatchEvent(new Event('change'));
        });
    });

    document.addEventListener('click', function(e) {
        if (!container.contains(e.target)) {
            closeDropdown();
        }
    });
})();
</script>

<style>
.dropdown-options::-webkit-scrollbar {
    width: 6px;
}
.dropdown-options::-webkit-scrollbar-track {
    background: rgba(15, 23, 42, 0.4);
    border-radius: 9999px;
}
.dropdown-options::-webkit-scrollbar-thumb {
    background: #475569;
    border-radius: 9999px;
}
.dropdown-options::-webkit-scrollbar-thumb:hover {
    background: #64748b;
}
html.light #stokBarangDropdownWrap .dropdown-panel,
html.light #filterStokToolbarWrap .dropdown-panel {
    background-color: #ffffff !important;
    border-color: #cbd5e1 !important;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
}
html.light #stokBarangDropdownWrap .dropdown-panel .bg-slate-900\/80,
html.light #filterStokToolbarWrap .dropdown-panel .bg-slate-900\/80 {
    background-color: #f8fafc !important;
    border-color: #cbd5e1 !important;
}
html.light #stokBarangDropdownWrap .dropdown-search,
html.light #filterStokToolbarWrap .dropdown-search {
    color: #0f172a !important;
}
html.light #stokBarangDropdownWrap .dropdown-opt,
html.light #filterStokToolbarWrap .dropdown-opt {
    color: #334155 !important;
}
html.light #stokBarangDropdownWrap .dropdown-opt:hover,
html.light #filterStokToolbarWrap .dropdown-opt:hover {
    background-color: #eff6ff !important;
    color: #1d4ed8 !important;
}
html.light #stokBarangDropdownWrap .dropdown-opt.is-selected,
html.light #filterStokToolbarWrap .dropdown-opt.is-selected {
    background-color: #eff6ff !important;
    color: #1d4ed8 !important;
}
html.light #stokBarangDropdownWrap .dropdown-empty,
html.light #filterStokToolbarWrap .dropdown-empty {
    color: #94a3b8 !important;
}
html.light #stokBarangDropdownWrap .dropdown-options::-webkit-scrollbar-track,
html.light #filterStokToolbarWrap .dropdown-options::-webkit-scrollbar-track {
    background: #f1f5f9;
}
html.light #stokBarangDropdownWrap .dropdown-options::-webkit-scrollbar-thumb,
html.light #filterStokToolbarWrap .dropdown-options::-webkit-scrollbar-thumb {
    background: #cbd5e1;
}
</style>
