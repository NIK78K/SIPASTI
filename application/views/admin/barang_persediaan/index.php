<!-- PAGE TITLE -->
<div class="flex items-center justify-between mb-10">
    <div>
        <h1 class="text-3xl font-extrabold header-title tracking-tight">
            Master Barang Persediaan
        </h1>
        <p class="text-sm header-subtitle mt-1">
            Pengelolaan barang persediaan per kategori (ATK & rumah tangga kantor)
        </p>
    </div>

    <button onclick="tambahBarang()"
        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl
               bg-blue-600 hover:bg-blue-700
               text-white text-sm font-semibold
               shadow-lg transition-all transform hover:scale-105 active:scale-95">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Tambah Barang
    </button>
</div>

<!-- TABLE CARD -->
<div id="tabelBarang" class="rounded-2xl border theme-card shadow-xl overflow-hidden">

    <!-- TOOLBAR -->
    <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center gap-3 justify-between border-b border-slate-800/50">
        <p id="barangCount" class="text-sm header-subtitle">
            <?= count($kategori) ?> kategori terdaftar
        </p>
        <div class="relative w-full sm:max-w-xs">
            <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 header-subtitle pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10 18a8 8 0 110-16 8 8 0 010 16z"/>
            </svg>
            <input type="text" id="barangSearch" placeholder="Cari kode, kategori, deskripsi..."
                   class="w-full pl-10 pr-4 py-2.5 rounded-xl text-sm
                          bg-slate-800/80 border border-slate-700/50
                          text-white placeholder-slate-500 focus:outline-none
                          focus:ring-2 focus:ring-blue-500 transition-all">
        </div>
    </div>

    <!-- HEADER -->
    <div class="grid grid-cols-[60px_130px_1fr_1.4fr_90px_140px]
                px-6 py-4 text-sm header-subtitle
                border-b border-slate-800/50 bg-slate-900/90">
        <div>No</div>
        <div>Kode</div>
        <div>Kategori</div>
        <div>Deskripsi</div>
        <div class="text-center">Jumlah</div>
        <div class="text-right">Aksi</div>
    </div>

    <!-- BODY -->
    <div id="kategoriTable" class="divide-y divide-slate-800/50">
        <?php if (empty($kategori)): ?>
            <div class="px-6 py-10 text-center header-subtitle">
                Belum ada barang di kategori ini.
            </div>
        <?php else: ?>
            <?php $no = 1; foreach($kategori as $kat): ?>
            <div class="barang-row grid grid-cols-[60px_130px_1fr_1.4fr_90px_140px]
                        px-6 py-4 items-center
                        hover:bg-slate-800/50 transition-colors"
                 data-search="<?= htmlspecialchars(strtolower(($kat->kode ?? '') . ' ' . ($kat->nama ?? '') . ' ' . ($kat->deskripsi ?? ''))) ?>">
                <div class="header-subtitle"><?= $no++ ?></div>

                <div>
                    <span class="inline-flex px-3 py-1 rounded-full
                                 text-xs font-medium
                                 bg-indigo-500/15 text-indigo-400
                                 border border-indigo-500/25 whitespace-nowrap">
                        <?= htmlspecialchars($kat->kode ?? '-') ?>
                    </span>
                </div>

                <div class="font-medium header-title">
                    <?= htmlspecialchars($kat->nama ?? '-') ?>
                </div>

                <div class="header-subtitle text-sm">
                    <?= htmlspecialchars($kat->deskripsi ?? '-') ?>
                </div>

                <div class="text-center">
                    <span class="inline-flex px-3 py-1 rounded-full
                                 text-xs font-medium
                                 bg-blue-500/15 text-blue-400
                                 border border-blue-500/25 whitespace-nowrap">
                        <?= (int)($kat->jumlah_barang ?? 0) ?>
                    </span>
                </div>

                <div class="flex justify-end gap-2">
                    <button onclick="lihatKategori(<?= $kat->id ?>)"
                        class="w-9 h-9 flex items-center justify-center
                               rounded-lg bg-blue-500/15
                               text-blue-400 hover:bg-blue-500/25 transition-all transform hover:scale-110"
                        title="Lihat">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                             stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                    <button type="button" onclick="openKategoriEdit(<?= $kat->id ?>)"
                        class="w-9 h-9 flex items-center justify-center
                               rounded-lg bg-yellow-500/15
                               text-yellow-400 hover:bg-yellow-500/25 transition-all transform hover:scale-110"
                        title="Edit Kategori">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                             stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                        </svg>
                    </button>
                    <button onclick="hapusKategori(<?= $kat->id ?>, '<?= addslashes($kat->nama) ?>')"
                        class="w-9 h-9 flex items-center justify-center
                               rounded-lg bg-red-500/15
                               text-red-400 hover:bg-red-500/25 transition-all transform hover:scale-110"
                        title="Hapus Kategori">
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
        <div id="barangEmpty" class="hidden px-6 py-10 text-center header-subtitle">
            Data tidak ditemukan
        </div>
    </div>

    <!-- PAGINATION -->
    <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center gap-3 justify-between border-t border-slate-800/50">
        <p id="barangInfo" class="text-sm header-subtitle"></p>
        <div id="barangPagination" class="flex items-center gap-1.5"></div>
    </div>
</div>

<!-- Modal Tambah/Edit Barang -->
<div id="modalBarang" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur flex items-center justify-center p-4">
    <div class="theme-card w-full max-w-2xl rounded-2xl border p-6 shadow-2xl flex flex-col max-h-[90vh] overflow-hidden" onclick="event.stopPropagation()">
        <h3 id="modalTitle" class="text-lg font-semibold header-title mb-1">Tambah Barang</h3>
        <p class="text-sm header-subtitle mb-5">Lengkapi data barang lalu simpan</p>

        <form id="formBarang" class="space-y-4 overflow-y-auto flex-1 min-h-0">
            <input type="hidden" id="barang_id" name="id">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="kategori_id" class="block text-sm font-medium header-subtitle mb-1.5">
                    Kategori <span class="text-red-400">*</span>
                </label>
                <select id="kategori_id" name="kategori_id" required
                        class="w-full px-4 py-3 rounded-xl mb-1 bg-slate-800/80 border border-slate-700/50 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                    <option value="" class="bg-slate-800 text-slate-400">-- Pilih Kategori --</option>
                    <?php foreach($kategori_list as $kat): ?>
                        <option value="<?= $kat->id ?>" class="bg-slate-800 text-white"><?= htmlspecialchars($kat->kode . ' - ' . $kat->nama) ?></option>
                    <?php endforeach; ?>
                </select>
                <p class="text-xs header-subtitle">Pilih kategori induk barang, wajib diisi.</p>
            </div>

            <div>
                <label for="kode_barang" class="block text-sm font-medium header-subtitle mb-1.5">
                    Kode Barang <span class="text-red-400">*</span>
                </label>
                <input type="text" id="kode_barang" name="kode_barang" readonly maxlength="50"
                       class="w-full px-4 py-3 rounded-xl mb-1 bg-slate-800/80 border border-slate-700/50 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all font-mono"
                       placeholder="Otomatis dari kategori">
                <p class="text-xs header-subtitle">Terisi otomatis saat kategori dipilih.</p>
            </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="nama_barang" class="block text-sm font-medium header-subtitle mb-1.5">
                    Nama Barang <span class="text-red-400">*</span>
                </label>
                <input type="text" id="nama_barang" name="nama_barang" required
                       class="w-full px-4 py-3 rounded-xl mb-1 bg-slate-800/80 border border-slate-700/50 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
                       placeholder="Contoh: Kertas HVS A4 80gr">
                <p class="text-xs header-subtitle">Nama lengkap barang, contoh: Kertas HVS A4 80gr.</p>
            </div>

            <div>
                <label for="satuan" class="block text-sm font-medium header-subtitle mb-1.5">Satuan</label>
                <input type="text" id="satuan" name="satuan"
                       class="w-full px-4 py-3 rounded-xl mb-1 bg-slate-800/80 border border-slate-700/50 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
                       placeholder="pcs" value="pcs">
                <p class="text-xs header-subtitle">Satuan jumlah barang, contoh: pcs, rim, atau box.</p>
            </div>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium header-subtitle mb-1.5">Status</label>
                <select id="status" name="status" class="w-full px-4 py-3 rounded-xl mb-1 bg-slate-800/80 border border-slate-700/50 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                    <option value="Aktif" class="bg-slate-800 text-white">Aktif</option>
                    <option value="Nonaktif" class="bg-slate-800 text-white">Nonaktif</option>
                </select>
                <p class="text-xs header-subtitle">Aktif bisa dipakai transaksi, Nonaktif disembunyikan.</p>
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

<!-- Modal Lihat Barang per Kategori -->
<div id="modalLihat" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur flex items-center justify-center p-4">
    <div class="theme-card w-full max-w-3xl rounded-2xl border p-6 shadow-2xl flex flex-col max-h-[85vh] overflow-hidden" onclick="event.stopPropagation()">
        <h3 id="lihatJudul" class="text-lg font-semibold header-title mb-1">Detail Kategori</h3>
        <p id="lihatSubdeskripsi" class="text-sm header-subtitle mb-5"></p>

        <div class="overflow-y-auto flex-1 min-h-0">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="sticky top-0 bg-slate-900/95 z-10">
                        <tr class="header-subtitle border-b border-slate-800/50 text-left">
                            <th class="px-3 py-3 font-semibold">Nama Barang</th>
                            <th class="px-3 py-3 font-semibold" style="width: 140px;">Kode</th>
                            <th class="px-3 py-3 font-semibold" style="width: 90px;">Satuan</th>
                            <th class="px-3 py-3 font-semibold" style="width: 120px;">Status</th>
                            <th class="px-3 py-3 font-semibold" style="width: 110px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="lihatRows">
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-6 flex-shrink-0">
            <button type="button" onclick="tutupLihat()"
                    class="px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold transition-all transform hover:scale-105 active:scale-95">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Modal Edit Kategori -->
<div id="modalEditKategori" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur flex items-center justify-center p-4">
    <div class="theme-card w-full max-w-2xl rounded-2xl border p-6 shadow-2xl flex flex-col max-h-[90vh] overflow-hidden" onclick="event.stopPropagation()">
        <h3 class="text-lg font-semibold header-title mb-1">Edit Kategori</h3>
        <p class="text-sm header-subtitle mb-5">Ubah data kategori lalu simpan</p>

        <form id="formEditKategori" class="space-y-4 overflow-y-auto flex-1 min-h-0" onsubmit="event.preventDefault(); submitKategoriEdit();">
            <input type="hidden" id="edit_kategori_id" name="id">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="edit_kategori_kode" class="block text-sm font-medium header-subtitle mb-1.5">
                    Kode Kategori <span class="text-red-400">*</span>
                </label>
                <input type="text" id="edit_kategori_kode" name="kode" required maxlength="50"
                       class="w-full px-4 py-3 rounded-xl mb-1 bg-slate-800/80 border border-slate-700/50 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all font-mono"
                       placeholder="Contoh: ATK-01">
                <p class="text-xs header-subtitle">Kode unik kategori, wajib diisi.</p>
            </div>

            <div>
                <label for="edit_kategori_nama" class="block text-sm font-medium header-subtitle mb-1.5">
                    Nama Kategori <span class="text-red-400">*</span>
                </label>
                <input type="text" id="edit_kategori_nama" name="nama" required
                       class="w-full px-4 py-3 rounded-xl mb-1 bg-slate-800/80 border border-slate-700/50 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
                       placeholder="Contoh: Alat Tulis Kantor">
                <p class="text-xs header-subtitle">Nama lengkap kategori, wajib diisi.</p>
            </div>
            </div>

            <div>
                <label for="edit_kategori_deskripsi" class="block text-sm font-medium header-subtitle mb-1.5">Deskripsi</label>
                <textarea id="edit_kategori_deskripsi" name="deskripsi" rows="3"
                       class="w-full px-4 py-3 rounded-xl mb-1 bg-slate-800/80 border border-slate-700/50 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
                       placeholder="Contoh: Kategori ATK & kebutuhan rumah tangga kantor"></textarea>
                <p class="text-xs header-subtitle">Keterangan tambahan kategori, boleh kosong.</p>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="tutupKategoriEdit()"
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

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
.barang-page-btn {
    min-width: 2.25rem;
    padding: 0.5rem 0.625rem;
    border-radius: 0.625rem;
    font-size: 0.8125rem;
    border: 1px solid rgba(71, 85, 105, 0.4);
    background: rgba(30, 41, 59, 0.5);
    color: #94a3b8;
    transition: all 0.2s;
}
.barang-page-btn:hover { background: rgba(30, 41, 59, 0.9); color: #e2e8f0; }
.barang-page-btn.active { background: #2563eb; border-color: #3b82f6; color: #fff; font-weight: 600; }
.barang-page-btn:disabled { opacity: 0.4; cursor: not-allowed; }
html.light input, html.light select, html.light textarea {
    background: rgba(255, 255, 255, 0.95) !important;
    color: var(--text-primary) !important;
    border-color: rgba(203, 213, 225, 0.8) !important;
}

html.light .hover\:bg-slate-800\/50:hover {
    background: rgba(248, 250, 252, 0.6) !important;
}

html.light #modalBarang,
html.light #modalLihat,
html.light #modalEditKategori {
    background: rgba(0, 0, 0, 0.4) !important;
}

html.light input::placeholder, html.light textarea::placeholder { color: #94a3b8 !important; }
</style>

<script>
let editMode = false;
let lihatKategoriId = null;
let lihatItems = [];

const barangPerPage = 15;
let barangPage = 1;
let barangFiltered = [];

function esc(s) {
    return String(s ?? '-').replace(/[&<>"']/g, function(c) {
        return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];
    });
}

function refreshLihatIfOpen() {
    if (lihatKategoriId && !document.getElementById('modalLihat').classList.contains('hidden')) {
        lihatKategori(lihatKategoriId);
        return true;
    }
    return false;
}

function getSwalColors() {
    const isLight = document.documentElement.classList.contains('light');
    return {
        background: isLight ? '#ffffff' : '#1a1f2e',
        color: isLight ? '#0f172a' : '#f3f4f6'
    };
}

function applyBarangFilter(resetPage = true) {
    const q = (document.getElementById('barangSearch').value || '').toLowerCase().trim();
    const all = Array.from(document.querySelectorAll('.barang-row'));
    barangFiltered = !q ? all : all.filter(r => (r.dataset.search || '').includes(q));
    if (resetPage) barangPage = 1;
    renderBarang();
}

function renderBarang() {
    const all = Array.from(document.querySelectorAll('.barang-row'));
    if (!barangFiltered.length && !document.getElementById('barangSearch').value) {
        barangFiltered = all;
    }
    const total = barangFiltered.length;
    const pages = Math.max(1, Math.ceil(total / barangPerPage));
    if (barangPage > pages) barangPage = pages;
    const start = total === 0 ? 0 : (barangPage - 1) * barangPerPage + 1;
    const end = Math.min(total, barangPage * barangPerPage);

    all.forEach(r => r.classList.add('hidden'));
    barangFiltered.slice(start - 1, end).forEach(r => r.classList.remove('hidden'));

    document.getElementById('barangEmpty').classList.toggle('hidden', total !== 0);
    document.getElementById('barangInfo').textContent =
        total === 0 ? 'Menampilkan 0 data' : `Menampilkan ${start} sampai ${end} dari ${total} data`;

    const pag = document.getElementById('barangPagination');
    pag.innerHTML = '';
    const mkBtn = (label, page, disabled = false, active = false, title = '') => {
        const b = document.createElement('button');
        b.type = 'button';
        b.textContent = label;
        if (title) b.title = title;
        b.className = 'barang-page-btn' + (active ? ' active' : '');
        b.disabled = disabled;
        b.addEventListener('click', () => { barangPage = page; renderBarang(); });
        return b;
    };
    pag.appendChild(mkBtn('‹', Math.max(1, barangPage - 1), barangPage === 1, false, 'Sebelumnya'));
    for (let p = 1; p <= pages; p++) {
        if (pages > 7 && Math.abs(p - barangPage) > 2 && p !== 1 && p !== pages) {
            if (!pag.querySelector('[data-ellipsis="' + p + '"]')) {
                const s = document.createElement('span');
                s.textContent = '…';
                s.className = 'px-1 header-subtitle';
                s.dataset.ellipsis = p;
                pag.appendChild(s);
            }
            continue;
        }
        pag.appendChild(mkBtn(String(p), p, false, p === barangPage));
    }
    pag.appendChild(mkBtn('›', Math.min(pages, barangPage + 1), barangPage === pages, false, 'Selanjutnya'));
}

document.getElementById('barangSearch').addEventListener('input', () => applyBarangFilter(true));
barangFiltered = Array.from(document.querySelectorAll('.barang-row'));
renderBarang();

function tambahBarang() {
    editMode = false;
    document.getElementById('modalTitle').textContent = 'Tambah Barang';
    document.getElementById('formBarang').reset();
    document.getElementById('barang_id').value = '';
    document.getElementById('kode_barang').value = '';
    document.getElementById('satuan').value = 'pcs';
    document.getElementById('status').value = 'Aktif';
    document.getElementById('modalBarang').classList.remove('hidden');
}

document.getElementById('kategori_id').addEventListener('change', function() {
    if (editMode) return;
    var kodeEl = document.getElementById('kode_barang');
    var kid = this.value;
    if (!kid) { kodeEl.value = ''; return; }
    kodeEl.value = '...';
    fetch('<?= base_url("barang_persediaan/next_kode/") ?>' + kid)
    .then(r => r.json())
    .then(d => { kodeEl.value = (d && d.status === 'success' && d.kode) ? d.kode : ''; })
    .catch(() => { kodeEl.value = ''; });
});

function editBarang(id) {
    fetch('<?= base_url("barang_persediaan/get/") ?>' + id)
    .then(r => r.json())
    .then(d => {
        if (d.status === 'error') {
            const c = getSwalColors();
            return Swal.fire({ icon: 'error', title: 'Gagal!', text: d.message, ...c });
        }
        tutupLihat();
        editMode = true;
        document.getElementById('modalTitle').textContent = 'Edit Barang';
        document.getElementById('barang_id').value = d.id;
        document.getElementById('kategori_id').value = d.kategori_id;
        document.getElementById('kode_barang').value = d.kode_barang || '';
        document.getElementById('nama_barang').value = d.nama_barang;
        document.getElementById('satuan').value = d.satuan;
        document.getElementById('status').value = d.status;
        document.getElementById('modalBarang').classList.remove('hidden');
    });
}

document.getElementById('formBarang').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const url = editMode ? '<?= base_url("barang_persediaan/update") ?>' : '<?= base_url("barang_persediaan/tambah") ?>';

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

function hapusBarang(id, nama) {
    const colors = getSwalColors();
    Swal.fire({
        title: 'Hapus Barang?',
        text: `Yakin ingin menghapus "${nama}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#ef4444',
        reverseButtons: true,
        ...colors
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('<?= base_url("barang_persediaan/hapus/") ?>' + id, { method: 'POST' })
            .then(r => r.json())
            .then(data => {
                const c = getSwalColors();
                Swal.fire({ icon: data.status, title: data.status === 'success' ? 'Terhapus!' : 'Gagal!', text: data.message, ...c })
                .then(() => { if (data.status === 'success' && !refreshLihatIfOpen()) location.reload(); });
            });
        }
    });
}

function hapusKategori(id, nama) {
    const colors = getSwalColors();
    Swal.fire({
        title: 'Hapus Kategori?',
        text: `Yakin hapus kategori "${nama}"? Kategori berisi barang tidak bisa dihapus.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#ef4444',
        reverseButtons: true,
        ...colors
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('<?= base_url("kategori_persediaan/delete/") ?>' + id)
            .then(r => r.json())
            .then(data => {
                const c = getSwalColors();
                Swal.fire({ icon: data.status, title: data.status === 'success' ? 'Terhapus!' : 'Gagal!', text: data.message, ...c })
                .then(() => { if (data.status === 'success') location.reload(); });
            })
            .catch(() => {
                const c = getSwalColors();
                Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Tidak dapat menghapus kategori.', ...c });
            });
        }
    });
}

function toggleStatus(id, currentStatus) {
    const newStatus = currentStatus === 'Aktif' ? 'Nonaktif' : 'Aktif';
    const colors = getSwalColors();
    Swal.fire({
        title: 'Ubah Status?',
        text: `Ubah status menjadi ${newStatus}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Ubah',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#2563eb',
        reverseButtons: true,
        ...colors
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('<?= base_url("barang_persediaan/toggle_status/") ?>' + id, { method: 'POST' })
            .then(r => r.json())
            .then(data => {
                const c = getSwalColors();
                Swal.fire({ icon: data.status, title: data.status === 'success' ? 'Berhasil!' : 'Gagal!', text: data.message, timer: 1500, ...c })
                .then(() => { if (data.status === 'success' && !refreshLihatIfOpen()) location.reload(); });
            });
        }
    });
}

function tutupModal() {
    document.getElementById('modalBarang').classList.add('hidden');
}

function lihatKategori(kategoriId) {
    lihatKategoriId = kategoriId;
    fetch('<?= base_url("barang_persediaan/get_by_kategori/") ?>' + kategoriId)
    .then(r => r.json())
    .then(d => {
        if (d && d.status === 'error') {
            const c = getSwalColors();
            return Swal.fire({ icon: 'error', title: 'Gagal!', text: d.message, ...c });
        }
        const rows = Array.isArray(d) ? d : [];
        lihatItems = rows;
        const first = rows[0] || {};
        document.getElementById('lihatJudul').textContent = ((first.kategori_kode || '') + (first.kategori_nama ? ' - ' + first.kategori_nama : '')).trim() || 'Detail Kategori';
        document.getElementById('lihatSubdeskripsi').textContent = first.kategori_deskripsi || '';
        const tbody = document.getElementById('lihatRows');
        if (!rows.length) {
            tbody.innerHTML = '<tr><td colspan="5" class="px-3 py-6 text-center header-subtitle">Belum ada barang di kategori ini.</td></tr>';
        } else {
            tbody.innerHTML = rows.map(function(b) {
                const aktif = (b.status === 'Aktif');
                const badgeCls = aktif
                    ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30 hover:bg-emerald-500/20'
                    : 'bg-red-500/10 text-red-400 border-red-500/30 hover:bg-red-500/20';
                const dotCls = aktif ? 'bg-emerald-400' : 'bg-red-400';
                return '<tr class="border-b border-slate-800/50 hover:bg-slate-800/50 transition-all duration-200">'
                    + '<td class="px-3 py-3 header-title font-medium">' + esc(b.nama_barang) + '</td>'
                    + '<td class="px-3 py-3"><span class="px-2.5 py-1 bg-slate-800/80 text-cyan-300 rounded text-xs font-mono border border-slate-700/50 whitespace-nowrap">' + esc(b.kode_barang) + '</span></td>'
                    + '<td class="px-3 py-3 header-subtitle">' + esc(b.satuan) + '</td>'
                    + '<td class="px-3 py-3"><button type="button" onclick="toggleStatus(' + b.id + ', \'' + esc(b.status) + '\')" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 text-xs font-medium rounded-full border transition-all duration-200 cursor-pointer whitespace-nowrap ' + badgeCls + '"><span class="w-1.5 h-1.5 ' + dotCls + ' rounded-full"></span>' + esc(b.status) + '</button></td>'
                    + '<td class="px-3 py-3"><div class="flex gap-1.5">'
                    + '<button type="button" onclick="editBarang(' + b.id + ')" class="p-2 bg-amber-900/40 hover:bg-amber-800/50 text-amber-400 rounded transition-all duration-200 shadow-sm border border-amber-800/30 transform hover:scale-110" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>'
                    + '<button type="button" data-id="' + b.id + '" data-nama="' + esc(b.nama_barang).replace(/"/g, '&quot;') + '" onclick="hapusBarang(this.dataset.id, this.dataset.nama)" class="p-2 bg-red-900/40 hover:bg-red-800/50 text-red-400 rounded transition-all duration-200 shadow-sm border border-red-800/30 transform hover:scale-110" title="Hapus"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>'
                    + '</div></td></tr>';
            }).join('');
        }
        document.getElementById('modalLihat').classList.remove('hidden');
    })
    .catch(() => {
        const c = getSwalColors();
        Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Tidak dapat memuat daftar barang.', ...c });
    });
}

function tutupLihat() {
    document.getElementById('modalLihat').classList.add('hidden');
    lihatKategoriId = null;
}

function openKategoriEdit(id) {
    fetch('<?= base_url("kategori_persediaan/get/") ?>' + id)
    .then(r => r.json())
    .then(d => {
        if (d && d.status === 'error') {
            const c = getSwalColors();
            return Swal.fire({ icon: 'error', title: 'Gagal!', text: d.message, ...c });
        }
        document.getElementById('edit_kategori_id').value = d.id;
        document.getElementById('edit_kategori_kode').value = d.kode || '';
        document.getElementById('edit_kategori_nama').value = d.nama || '';
        document.getElementById('edit_kategori_deskripsi').value = d.deskripsi || '';
        document.getElementById('modalEditKategori').classList.remove('hidden');
    })
    .catch(() => {
        const c = getSwalColors();
        Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Tidak dapat memuat data kategori.', ...c });
    });
}

function submitKategoriEdit() {
    const id = document.getElementById('edit_kategori_id').value;
    const formData = new FormData(document.getElementById('formEditKategori'));
    const colors = getSwalColors();
    Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, allowEscapeKey: false, didOpen: () => Swal.showLoading(), ...colors });

    fetch('<?= base_url("kategori_persediaan/update/") ?>' + id, { method: 'POST', body: formData })
    .then(r => r.json())
    .then(data => {
        const c = getSwalColors();
        if (data.status === 'success') {
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: data.message, timer: 1500, ...c })
            .then(() => location.reload());
        } else {
            Swal.fire({ icon: 'error', title: 'Gagal!', text: data.message, ...c });
        }
    })
    .catch(() => {
        const c = getSwalColors();
        Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Tidak dapat menyimpan kategori.', ...c });
    });
}

function tutupKategoriEdit() {
    document.getElementById('modalEditKategori').classList.add('hidden');
}

document.getElementById('modalBarang').addEventListener('click', function(e) {
    if (e.target === this) tutupModal();
});

document.getElementById('modalLihat').addEventListener('click', function(e) {
    if (e.target === this) tutupLihat();
});

document.getElementById('modalEditKategori').addEventListener('click', function(e) {
    if (e.target === this) tutupKategoriEdit();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') { tutupModal(); tutupLihat(); tutupKategoriEdit(); }
});
</script>
