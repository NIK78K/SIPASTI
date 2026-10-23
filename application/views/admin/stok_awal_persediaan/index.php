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
<div id="tabelStok" class="rounded-2xl border theme-card shadow-xl overflow-hidden">

    <!-- TOOLBAR -->
    <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center gap-3 justify-between border-b border-slate-800/50">
        <p id="stokCount" class="text-sm header-subtitle">
            <?= count($stok_awal) ?> data stok awal terdaftar
        </p>
        <div class="relative w-full sm:max-w-xs">
            <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 header-subtitle pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10 18a8 8 0 110-16 8 8 0 010 16z"/>
            </svg>
            <input type="text" id="stokSearch" placeholder="Cari kode, nama, kategori, periode..."
                   class="w-full pl-10 pr-4 py-2.5 rounded-xl text-sm
                          bg-slate-800/80 border border-slate-700/50
                          text-white placeholder-slate-500 focus:outline-none
                          focus:ring-2 focus:ring-blue-500 transition-all">
        </div>
    </div>

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
                <select id="stok_barang_id" name="barang_id" required
                        class="w-full px-4 py-3 rounded-xl mb-1 bg-slate-800/80 border border-slate-700/50 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                    <option value="" class="bg-slate-800 text-slate-400">-- Pilih Barang --</option>
                    <?php foreach($barang_list as $brg): ?>
                        <option value="<?= $brg->id ?>" class="bg-slate-800 text-white"><?= htmlspecialchars($brg->kode_barang . ' - ' . $brg->nama_barang) ?></option>
                    <?php endforeach; ?>
                </select>
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

document.getElementById('stokSearch').addEventListener('input', () => applyStokFilter(true));
stokFiltered = Array.from(document.querySelectorAll('.stok-row'));
renderStok();

function tambahStok() {
    editMode = false;
    document.getElementById('modalTitle').textContent = 'Tambah Stok Awal';
    document.getElementById('modalSubtitle').textContent = 'Isi stok awal barang per periode bulan dan tahun';
    document.getElementById('formBarang').reset();
    document.getElementById('barang_id').value = '';
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
        document.getElementById('bulan').value = d.bulan;
        document.getElementById('tahun').value = d.tahun;
        document.getElementById('jumlah').value = d.jumlah;
        document.getElementById('keterangan').value = d.keterangan || '';
        document.getElementById('modalBarang').classList.remove('hidden');
    });
}

document.getElementById('formBarang').addEventListener('submit', function(e) {
    e.preventDefault();
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
</script>
