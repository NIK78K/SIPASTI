<!-- ================= JUDUL ================= -->
<div class="mb-8">
    <h1 class="text-3xl font-bold mb-2 header-title">Pengajuan Penghapusan Perangkat</h1>
    <p class="text-sm flex items-center gap-2 header-subtitle">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
        </svg>
        Kelola pengajuan penghapusan perangkat rusak berat
    </p>
</div>

<!-- ================= TABS ================= -->
<div class="flex gap-2 mb-8 border-b pgj-tab-border overflow-x-auto">

    <button onclick="switchTab('daftar')" id="tabDaftar"
            class="tab-btn active px-6 py-3.5 font-semibold transition-all rounded-t-xl
                   flex items-center gap-2 whitespace-nowrap">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Daftar Rusak Berat
    </button>

    <button onclick="switchTab('usulan')" id="tabUsulan"
            class="tab-btn px-6 py-3.5 font-semibold transition-all rounded-t-xl
                   flex items-center gap-2 relative whitespace-nowrap">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
        </svg>
        Daftar Usul Hapus
        <span id="badgeUsulan"
              class="hidden absolute top-0 -right-2 bg-amber-600 text-white text-xs
                     w-5 h-5 rounded-full flex items-center justify-center shadow-lg">0</span>
    </button>

    <button onclick="switchTab('pengajuan')" id="tabPengajuan"
            class="tab-btn px-6 py-3.5 font-semibold transition-all rounded-t-xl
                   flex items-center gap-2 relative whitespace-nowrap">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Pengajuan Penghapusan
        <span id="badgePengajuan"
              class="hidden absolute top-0 -right-2 bg-blue-600 text-white text-xs
                     w-5 h-5 rounded-full flex items-center justify-center shadow-lg">0</span>
    </button>

</div>

<!-- ================= TAB 1: DAFTAR RUSAK BERAT ================= -->
<div id="contentDaftar" class="tab-content">

    <div class="theme-card border border-red-700/40 rounded-xl p-5 mb-5">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-red-500/20 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                          d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-red-300 mb-1">⚠️ Perangkat Rusak Berat</p>
                <p class="text-red-200/80 text-sm">
                    Klik <strong>"Usul Hapus"</strong> untuk menambahkan ke daftar usulan.
                </p>
            </div>
        </div>
    </div>

    <!-- Search -->
    <div class="mb-4 flex gap-3 flex-wrap">
        <input type="text" id="searchKodeBarang" oninput="renderRusakBerat()"
               placeholder="🔍 Cari Kode Barang..."
               class="pgj-input px-4 py-2.5 border rounded-xl text-sm
                      focus:ring-2 focus:ring-cyan-500/50 focus:outline-none flex-1 min-w-[200px]">
        <input type="text" id="searchNUP" oninput="renderRusakBerat()"
               placeholder="🔍 Cari NUP..."
               class="pgj-input px-4 py-2.5 border rounded-xl text-sm
                      focus:ring-2 focus:ring-cyan-500/50 focus:outline-none flex-1 min-w-[200px]">
    </div>

    <!-- Tabel -->
    <div class="theme-card border rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="pgj-thead border-b pgj-border">
                    <tr>
                        <th class="px-4 py-4 text-center w-12">
                            <input type="checkbox" id="selectAllRusakBerat" class="rounded w-4 h-4">
                        </th>
                        <th class="px-4 py-4 text-left pgj-th">Kode Barang</th>
                        <th class="px-4 py-4 text-left pgj-th">NUP</th>
                        <th class="px-4 py-4 text-left pgj-th">Nama Barang</th>
                        <th class="px-4 py-4 text-left pgj-th">Merk</th>
                        <th class="px-4 py-4 text-left pgj-th">Lokasi</th>
                        <th class="px-4 py-4 text-center pgj-th">Aksi</th>
                    </tr>
                </thead>
                <tbody id="rusakBeratTableBody" class="pgj-tbody-divider"></tbody>
            </table>
        </div>
    </div>

    <!-- Bulk action bar -->
    <div id="rusakBeratActions"
         class="hidden mt-4 theme-card p-5 rounded-xl border border-cyan-700/40">
        <div class="flex justify-between items-center flex-wrap gap-3">
            <div class="text-sm header-subtitle">
                <span id="selectedRusakBeratCount" class="header-title font-bold">0</span> dipilih
            </div>
            <button id="btnTambahKeUsulBulk" onclick="tambahKeUsulBulk()"
                    class="px-6 py-2.5 bg-gradient-to-r from-cyan-600 to-blue-600
                           hover:from-cyan-500 hover:to-blue-500 text-white rounded-lg font-bold transition-all">
                Tambah ke Usul Hapus
            </button>
        </div>
    </div>

    <!-- Pagination -->
    <div id="paginationRusakBerat" class="mt-6 flex justify-between items-center">
        <div class="text-sm header-subtitle">
            Menampilkan
            <span id="showingStart" class="header-title font-semibold">0</span>–<span id="showingEnd" class="header-title font-semibold">0</span>
            dari <span id="totalData" class="header-title font-semibold">0</span> data
        </div>
        <div class="flex gap-2">
            <button onclick="prevPage()" id="btnPrev"
                    class="pgj-pag-btn px-4 py-2 rounded-lg text-sm
                           disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                ← Prev
            </button>
            <span class="theme-card border px-4 py-2 rounded-lg text-sm header-title">
                Hal <span id="currentPage">1</span> dari <span id="totalPages">1</span>
            </span>
            <button onclick="nextPage()" id="btnNext"
                    class="pgj-pag-btn px-4 py-2 rounded-lg text-sm
                           disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                Next →
            </button>
        </div>
    </div>
</div>

<!-- ================= TAB 2: DAFTAR USUL HAPUS ================= -->
<div id="contentUsulan" class="tab-content hidden">

    <div class="theme-card border border-amber-700/40 rounded-xl p-5 mb-5">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-amber-500/20 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                          d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-amber-300 mb-1">📝 Daftar Usul Sementara</p>
                <p class="text-amber-200/80 text-sm">
                    Centang perangkat → Klik <strong>"Kirim Pengajuan"</strong>.
                </p>
            </div>
        </div>
    </div>

    <div class="theme-card border rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="pgj-thead border-b pgj-border">
                    <tr>
                        <th class="px-4 py-4 text-center w-12">
                            <input type="checkbox" id="selectAllUsulan" class="rounded w-4 h-4">
                        </th>
                        <th class="px-4 py-4 text-left pgj-th">Kode Barang</th>
                        <th class="px-4 py-4 text-left pgj-th">NUP</th>
                        <th class="px-4 py-4 text-left pgj-th">Nama Barang</th>
                        <th class="px-4 py-4 text-left pgj-th">Merk</th>
                        <th class="px-4 py-4 text-left pgj-th">Lokasi</th>
                        <th class="px-4 py-4 text-center pgj-th">Aksi</th>
                    </tr>
                </thead>
                <tbody id="usulTableBody" class="pgj-tbody-divider"></tbody>
            </table>
        </div>
    </div>

    <div id="usulActions" class="hidden mt-6 theme-card p-5 rounded-xl border border-purple-700/40">
        <div class="flex justify-between items-center flex-wrap gap-3">
            <div class="text-sm header-subtitle">
                <span id="selectedUsulCount" class="header-title font-bold">0</span> dipilih |
                Total: <span id="totalUsulan" class="header-title font-bold">0</span>
            </div>
            <div class="flex gap-3">
                <button id="btnBatalkanTerpilih" onclick="batalkanTerpilih()"
                        class="hidden pgj-btn-secondary px-4 py-2.5 rounded-lg transition-all">
                    Batalkan Terpilih
                </button>
                <button onclick="kosongkanUsulan()"
                        class="px-4 py-2.5 bg-red-700 hover:bg-red-600 text-white rounded-lg transition-all">
                    Kosongkan Semua
                </button>
                <button id="btnKirimPengajuan" onclick="kirimPengajuan()"
                        class="hidden px-6 py-2.5 bg-gradient-to-r from-blue-600 to-cyan-600
                               hover:from-blue-500 hover:to-cyan-500 text-white rounded-lg font-bold transition-all">
                    Kirim Pengajuan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ================= TAB 3: PENGAJUAN PENGHAPUSAN ================= -->
<div id="contentPengajuan" class="tab-content hidden">

    <div class="theme-card border border-blue-700/40 rounded-xl p-5 mb-5">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                          d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-blue-300 mb-1">📋 Pengajuan Review</p>
                <p class="text-blue-200/80 text-sm" id="infoRoleSpecific"></p>
            </div>
        </div>
    </div>

    <div class="theme-card border rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="pgj-thead border-b pgj-border">
                    <tr>
                        <th class="px-4 py-4 text-left pgj-th">No. Pengajuan</th>
                        <th class="px-4 py-4 text-left pgj-th">Risalah Lelang</th>
                        <th class="px-4 py-4 text-left pgj-th">Tanggal</th>
                        <th class="px-4 py-4 text-left pgj-th">Pengaju</th>
                        <th class="px-4 py-4 text-center pgj-th">Jumlah</th>
                        <th class="px-4 py-4 text-center pgj-th">Status</th>
                        <th class="px-4 py-4 text-center pgj-th">Aksi</th>
                    </tr>
                </thead>
                <tbody id="pengajuanTableBody" class="pgj-tbody-divider"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- ================= STYLES ================= -->
<style>
/* ── Tab system ── */
.tab-btn {
    color: #9ca3af;
    border-bottom: 3px solid transparent;
    background: transparent;
}
.tab-btn.active {
    color: var(--text-primary, #f1f5f9);
    border-bottom-color: #3b82f6;
    background: linear-gradient(180deg, rgba(59,130,246,0.15) 0%, rgba(59,130,246,0.05) 100%);
}

/* ══ DARK default ══ */
.pgj-tab-border  { border-color: rgba(55,65,81,0.5); }

.pgj-thead       { background: rgba(15,23,42,0.92); }
.pgj-th          { color: #94a3b8; font-weight: 600; }
.pgj-border      { border-color: rgba(51,65,85,0.5); }

.pgj-tbody-divider > tr          { border-bottom: 1px solid rgba(55,65,81,0.3); }
.pgj-tbody-divider tr:hover      { background: rgba(51,65,85,0.35); }

/* semua td dalam tbody otomatis dapat warna */
.pgj-tbody-divider td            { color: #e2e8f0; }
.pgj-cell                        { color: #e2e8f0; }
.pgj-cell-muted                  { color: #94a3b8; }

.pgj-input {
    background: rgba(15,23,42,0.8);
    border-color: rgba(51,65,85,0.5);
    color: #f1f5f9;
}
.pgj-input::placeholder          { color: #475569; }

.pgj-pag-btn {
    background: rgba(55,65,81,0.5);
    color: #e2e8f0;
}
.pgj-pag-btn:hover:not(:disabled) { background: rgba(75,85,99,0.8); }

.pgj-btn-secondary               { background: #374151; color: #f1f5f9; }
.pgj-btn-secondary:hover         { background: #4b5563; }

/* ══ LIGHT overrides ══ */
html.light .tab-btn              { color: #64748b; }
html.light .tab-btn.active       { color: #0f172a; }

html.light .pgj-tab-border       { border-color: #e2e8f0 !important; }

html.light .pgj-thead            { background: #f1f5f9 !important; }
html.light .pgj-th               { color: #475569 !important; }
html.light .pgj-border           { border-color: #e2e8f0 !important; }

html.light .pgj-tbody-divider > tr       { border-bottom-color: #e2e8f0 !important; }
html.light .pgj-tbody-divider tr:hover   { background: #f8fafc !important; }

/* ← INI KUNCINYA: semua td dalam tbody jadi gelap di light mode */
html.light .pgj-tbody-divider td         { color: #1e293b !important; }
html.light .pgj-cell                     { color: #1e293b !important; }
html.light .pgj-cell-muted               { color: #64748b !important; }

/* badge kode barang tetap biru tapi lebih gelap di light */
html.light .pgj-tbody-divider td .text-blue-400 { color: #2563eb !important; }

html.light .pgj-input {
    background: #ffffff !important;
    border-color: #cbd5e1 !important;
    color: #0f172a !important;
}
html.light .pgj-input::placeholder      { color: #94a3b8 !important; }

html.light .pgj-pag-btn {
    background: #ffffff !important;
    color: #334155 !important;
    border: 1px solid #e2e8f0 !important;
}
html.light .pgj-pag-btn:hover:not(:disabled) { background: #f1f5f9 !important; }

html.light .pgj-btn-secondary           { background: #e2e8f0 !important; color: #334155 !important; }
html.light .pgj-btn-secondary:hover     { background: #cbd5e1 !important; }

/* info banner teks di light mode */
html.light .text-red-200\/80   { color: #991b1b !important; }
html.light .text-amber-200\/80 { color: #92400e !important; }
html.light .text-blue-200\/80  { color: #1e40af !important; }
html.light .text-red-300       { color: #b91c1c !important; }
html.light .text-amber-300     { color: #b45309 !important; }
html.light .text-blue-300      { color: #1d4ed8 !important; }

/* SweetAlert2 */
.swal2-popup {
    background-color: var(--bg-card) !important;
    border: 2px solid var(--border-color) !important;
    color: var(--text-primary) !important;
}
html.light .swal2-popup {
    background-color: #ffffff !important;
    border-color: #e2e8f0 !important;
}
.swal2-title          { color: var(--text-primary) !important; }
.swal2-html-container { color: var(--text-secondary) !important; }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
/* ══ Helpers ══════════════════════════════════════════════════════ */
function cellMuted(v)  { return `<span class="pgj-cell-muted">${v||'-'}</span>`; }
function cellMain(v)   { return `<span class="pgj-cell font-semibold">${v||'-'}</span>`; }
function cellSecond(v) { return `<span class="pgj-cell">${v||'-'}</span>`; }

function getSwalColors() {
    const isLight = document.documentElement.classList.contains('light');
    return { background: isLight ? '#ffffff' : '#0e141b', color: isLight ? '#0f172a' : '#fff' };
}

/* ══ PHP session info ═════════════════════════════════════════════ */
const userInfo = {
    name    : "<?= $this->session->userdata('name')    ?? 'Guest' ?>",
    role    : "<?= $this->session->userdata('role')    ?? 'user'  ?>",
    user_id : "<?= $this->session->userdata('user_id') ?? '0'     ?>"
};

/* ══ Base URL helpers ═════════════════════════════════════════════ */
const BASE = '<?= base_url("inventory/") ?>';

/* ══ State (in-memory, di-refresh dari API) ═══════════════════════ */
let rusakBeratData  = [];
let usulHapus       = [];
let pengajuanList   = [];
let currentPageNumber = 1;
const itemsPerPage  = 10;

/* ══════════════════════════════════════════════════════════════════
   API CALLS — menggantikan semua localStorage
══════════════════════════════════════════════════════════════════ */
async function api(endpoint, method = 'GET', body = null) {
    const opts = { method, headers: { 'Content-Type': 'application/json' } };
    if (body) opts.body = JSON.stringify(body);
    const res = await fetch(BASE + endpoint, opts);
    return res.json();
}

async function loadUsulHapus() {
    const data = await api('get_usul_hapus');
    // Normalisasi: tambahkan field `id` sebagai alias perangkat_id
    usulHapus = (data || []).map(item => ({ ...item, id: item.perangkat_id }));
    updateBadgeUsulan();
}

async function loadPengajuan() {
    const data = await api('get_pengajuan_list');
    pengajuanList = data || [];
    updateBadgePengajuan();
}

/* ══════════════════════════════════════════════════════════════════
   TAB SYSTEM
══════════════════════════════════════════════════════════════════ */
function switchTab(tab) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
    if (tab === 'daftar') {
        document.getElementById('tabDaftar').classList.add('active');
        document.getElementById('contentDaftar').classList.remove('hidden');
        renderRusakBerat();
    } else if (tab === 'usulan') {
        document.getElementById('tabUsulan').classList.add('active');
        document.getElementById('contentUsulan').classList.remove('hidden');
        renderUsulHapus();
    } else if (tab === 'pengajuan') {
        document.getElementById('tabPengajuan').classList.add('active');
        document.getElementById('contentPengajuan').classList.remove('hidden');
        renderPengajuan();
    }
}

/* ══════════════════════════════════════════════════════════════════
   TAB 1 — DAFTAR RUSAK BERAT
══════════════════════════════════════════════════════════════════ */
function renderRusakBerat() {
    const tbody = document.getElementById('rusakBeratTableBody');
    const kodeQ = document.getElementById('searchKodeBarang').value.toLowerCase();
    const nupQ  = document.getElementById('searchNUP').value.toLowerCase();
    let filtered = rusakBeratData.filter(item =>
        (!kodeQ || item.kode_barang?.toLowerCase().includes(kodeQ)) &&
        (!nupQ  || item.nup?.toLowerCase().includes(nupQ))
    );
    const totalData  = filtered.length;
    const totalPages = Math.ceil(totalData / itemsPerPage);
    const start      = (currentPageNumber - 1) * itemsPerPage;
    const pageData   = filtered.slice(start, start + itemsPerPage);

    if (!pageData.length) {
        tbody.innerHTML = `<tr><td colspan="7" class="py-16 text-center pgj-cell-muted text-sm">Tidak ada data</td></tr>`;
    } else {
        tbody.innerHTML = pageData.map(item => {
            const diusulkan = usulHapus.some(u => u.perangkat_id == item.id || u.id == item.id);
            return `
            <tr class="transition-colors">
                <td class="px-4 py-3.5 text-center align-middle">
                    ${diusulkan
                        ? '<span class="text-amber-400 text-sm">✓</span>'
                        : `<input type="checkbox" class="checkbox-rusak-berat rounded w-4 h-4" value="${item.id}">`}
                </td>
                <td class="px-4 py-3.5 align-middle">
                    <span class="px-2.5 py-1 bg-blue-500/15 text-blue-400 rounded-lg text-xs font-mono">${item.kode_barang||'-'}</span>
                </td>
                <td class="px-4 py-3.5 align-middle">${cellMuted(item.nup)}</td>
                <td class="px-4 py-3.5 align-middle">${cellMain(item.nama_barang)}</td>
                <td class="px-4 py-3.5 align-middle">${cellSecond(item.merk)}</td>
                <td class="px-4 py-3.5 align-middle">${cellMuted(item.lokasi)}</td>
                <td class="px-4 py-3.5 text-center align-middle">
                    ${diusulkan
                        ? `<span class="inline-flex items-center gap-1 px-3 py-1.5 bg-amber-500/20 text-amber-300 rounded-lg text-xs font-medium">
                               <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                               Diusulkan</span>`
                        : `<button onclick="tambahKeUsul(${item.id})"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-cyan-600/20 hover:bg-cyan-600 text-cyan-400 hover:text-white rounded-lg text-xs font-semibold transition-all">
                               <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                               Usul Hapus</button>`}
                </td>
            </tr>`;
        }).join('');

        document.querySelectorAll('.checkbox-rusak-berat').forEach(cb =>
            cb.addEventListener('change', updateRusakBeratCheckboxActions));
        const selAll = document.getElementById('selectAllRusakBerat');
        if (selAll) {
            selAll.checked = false;
            selAll.onchange = function() {
                document.querySelectorAll('.checkbox-rusak-berat').forEach(cb => cb.checked = this.checked);
                updateRusakBeratCheckboxActions();
            };
        }
    }
    document.getElementById('showingStart').textContent = totalData > 0 ? start + 1 : 0;
    document.getElementById('showingEnd').textContent   = Math.min(start + itemsPerPage, totalData);
    document.getElementById('totalData').textContent    = totalData;
    document.getElementById('currentPage').textContent  = currentPageNumber;
    document.getElementById('totalPages').textContent   = totalPages || 1;
    document.getElementById('btnPrev').disabled = currentPageNumber === 1;
    document.getElementById('btnNext').disabled = currentPageNumber >= totalPages;
}

function updateRusakBeratCheckboxActions() {
    const n = document.querySelectorAll('.checkbox-rusak-berat:checked').length;
    document.getElementById('selectedRusakBeratCount').textContent = n;
    document.getElementById('rusakBeratActions').classList.toggle('hidden', n === 0);
}

async function tambahKeUsulBulk() {
    const cbs    = document.querySelectorAll('.checkbox-rusak-berat:checked');
    const colors = getSwalColors();
    if (!cbs.length) { Swal.fire({ icon:'warning', title:'Pilih minimal 1 perangkat', timer:2000, ...colors }); return; }

    let added = 0;
    for (const cb of Array.from(cbs)) {
        const id   = parseInt(cb.value);
        const item = rusakBeratData.find(x => x.id == id);
        if (!item || usulHapus.some(u => u.perangkat_id == id || u.id == id)) continue;
        await api('tambah_usul_hapus', 'POST', { id });
        added++;
    }

    if (added > 0) {
        await loadUsulHapus();
        renderRusakBerat();
        Swal.fire({ icon:'success', title:'Berhasil!', html:`<b>${added} perangkat</b> ditambahkan`, timer:2000, showConfirmButton:false, ...colors });
    } else {
        Swal.fire({ icon:'info', title:'Semua sudah diusulkan', timer:2000, ...colors });
    }
}

function prevPage() { if (currentPageNumber > 1) { currentPageNumber--; renderRusakBerat(); } }
function nextPage() {
    const kodeQ = document.getElementById('searchKodeBarang').value.toLowerCase();
    const nupQ  = document.getElementById('searchNUP').value.toLowerCase();
    const total = Math.ceil(rusakBeratData.filter(i =>
        (!kodeQ || i.kode_barang?.toLowerCase().includes(kodeQ)) &&
        (!nupQ  || i.nup?.toLowerCase().includes(nupQ))
    ).length / itemsPerPage);
    if (currentPageNumber < total) { currentPageNumber++; renderRusakBerat(); }
}

async function tambahKeUsul(id) {
    const item   = rusakBeratData.find(x => x.id == id);
    const colors = getSwalColors();
    if (!item) return;
    if (usulHapus.some(u => u.perangkat_id == id || u.id == id)) {
        Swal.fire({ icon:'info', title:'Sudah diusulkan', timer:2000, ...colors }); return;
    }
    await api('tambah_usul_hapus', 'POST', { id });
    await loadUsulHapus();
    renderRusakBerat();
    Swal.fire({ icon:'success', title:'Ditambahkan!', html:`<b>${item.nama_barang}</b><p class="text-xs mt-1">Lihat tab "Daftar Usul Hapus"</p>`, timer:2000, showConfirmButton:false, ...colors });
}

async function loadRusakBerat() {
    try {
        const r = await fetch(BASE + 'get_rusak_berat');
        rusakBeratData = await r.json();
        currentPageNumber = 1;
        renderRusakBerat();
    } catch(e) {
        Swal.fire({ icon:'error', title:'Gagal memuat data', timer:2000, ...getSwalColors() });
    }
}

/* ══════════════════════════════════════════════════════════════════
   TAB 2 — DAFTAR USUL HAPUS
══════════════════════════════════════════════════════════════════ */
function renderUsulHapus() {
    const tbody   = document.getElementById('usulTableBody');
    const actions = document.getElementById('usulActions');
    if (!usulHapus.length) {
        tbody.innerHTML = `<tr><td colspan="7" class="py-16 text-center pgj-cell-muted text-sm">Daftar kosong</td></tr>`;
        actions.classList.add('hidden'); return;
    }
    tbody.innerHTML = usulHapus.map((item, idx) => `
        <tr class="transition-colors">
            <td class="px-4 py-3.5 text-center"><input type="checkbox" class="checkbox-usul rounded w-4 h-4" value="${idx}"></td>
            <td class="px-4 py-3.5"><span class="px-2.5 py-1 bg-blue-500/15 text-blue-400 rounded-lg text-xs font-mono">${item.kode_barang}</span></td>
            <td class="px-4 py-3.5">${cellMuted(item.nup)}</td>
            <td class="px-4 py-3.5">${cellMain(item.nama_barang)}</td>
            <td class="px-4 py-3.5">${cellSecond(item.merk)}</td>
            <td class="px-4 py-3.5">${cellMuted(item.lokasi)}</td>
            <td class="px-4 py-3.5 text-center">
                <button onclick="hapusDariUsul(${idx})"
                        class="px-3 py-1.5 bg-red-600/20 hover:bg-red-600 text-red-400 hover:text-white rounded-lg text-xs transition-all">Batalkan</button>
            </td>
        </tr>`).join('');

    actions.classList.remove('hidden');
    document.getElementById('totalUsulan').textContent = usulHapus.length;
    document.querySelectorAll('.checkbox-usul').forEach(cb =>
        cb.addEventListener('change', updateUsulCheckboxActions));
    document.getElementById('selectAllUsulan').onchange = function() {
        document.querySelectorAll('.checkbox-usul').forEach(cb => cb.checked = this.checked);
        updateUsulCheckboxActions();
    };
}

function updateUsulCheckboxActions() {
    const n = document.querySelectorAll('.checkbox-usul:checked').length;
    document.getElementById('selectedUsulCount').textContent = n;
    document.getElementById('btnBatalkanTerpilih').classList.toggle('hidden', n === 0);
    document.getElementById('btnKirimPengajuan').classList.toggle('hidden', n === 0);
}

async function kirimPengajuan() {
    const cbs    = document.querySelectorAll('.checkbox-usul:checked');
    const colors = getSwalColors();
    if (!cbs.length) { Swal.fire({ icon:'warning', title:'Pilih minimal 1 perangkat', timer:2000, ...colors }); return; }
    const items  = Array.from(cbs).map(cb => usulHapus[parseInt(cb.value)]);

    const isLight = document.documentElement.classList.contains('light');
    const result = await Swal.fire({
        title: 'Kirim Pengajuan Penghapusan',
        html: `<div class="text-left space-y-3">
            <div class="p-3 rounded-xl bg-blue-900/30">
                <p class="text-sm">Pengaju: <b>${userInfo.name}</b></p>
                <p class="text-xs text-blue-400">${userInfo.role}</p>
            </div>
            <div class="p-3 rounded-xl bg-purple-900/30">
                <p class="text-sm">Jumlah: <b>${items.length} perangkat</b></p>
            </div>
            <textarea id="alasanPengajuan" rows="3"
                      style="width:100%;padding:10px;border-radius:10px;border:1px solid ${isLight?'#cbd5e1':'rgba(51,65,85,0.5)'};background:${isLight?'#f8fafc':'rgba(15,23,42,0.8)'};color:${isLight?'#0f172a':'#fff'};font-size:13px;"
                      placeholder="Alasan pengajuan (opsional)"></textarea>
        </div>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '✓ Kirim',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#3b82f6',
        ...colors,
        preConfirm: () => ({ alasan: document.getElementById('alasanPengajuan').value.trim() || '-' })
    });

    if (!result.isConfirmed) return;

    // Normalisasi field agar cocok dengan schema DB
    const payload = items.map(i => ({
        perangkat_id : i.perangkat_id ?? i.id,
        kode_barang  : i.kode_barang,
        nup          : i.nup,
        nama_barang  : i.nama_barang,
        merk         : i.merk,
        lokasi       : i.lokasi,
        kondisi      : i.kondisi,
    }));

    const res = await api('buat_pengajuan', 'POST', {
        perangkat: payload,
        alasan   : result.value.alasan
    });

    if (res.status === 'success') {
        await Promise.all([loadUsulHapus(), loadPengajuan()]);
        Swal.fire({ icon:'success', title:'Terkirim!', html:`<p>${items.length} perangkat diajukan untuk review</p>`, timer:2500, showConfirmButton:false, ...colors });
        setTimeout(() => switchTab('pengajuan'), 2500);
    } else {
        Swal.fire({ icon:'error', title:'Gagal', text: res.message, ...colors });
    }
}

async function batalkanTerpilih() {
    const cbs = document.querySelectorAll('.checkbox-usul:checked');
    const colors = getSwalColors();
    if (!cbs.length) return;
    const { isConfirmed } = await Swal.fire({
        title:'Batalkan?', text:`${cbs.length} item`, icon:'warning',
        showCancelButton:true, confirmButtonText:'Ya', confirmButtonColor:'#ef4444', ...colors
    });
    if (!isConfirmed) return;
    for (const cb of Array.from(cbs)) {
        const item = usulHapus[parseInt(cb.value)];
        await api('hapus_usul_hapus', 'POST', { perangkat_id: item.perangkat_id ?? item.id });
    }
    await loadUsulHapus();
    renderUsulHapus();
    renderRusakBerat();
}

async function kosongkanUsulan() {
    if (!usulHapus.length) return;
    const colors = getSwalColors();
    const { isConfirmed } = await Swal.fire({
        title:'Kosongkan Semua?', text:`${usulHapus.length} item`, icon:'warning',
        showCancelButton:true, confirmButtonText:'Ya', confirmButtonColor:'#ef4444', ...colors
    });
    if (!isConfirmed) return;
    await api('kosongkan_usul_hapus', 'POST');
    await loadUsulHapus();
    renderUsulHapus();
    renderRusakBerat();
}

async function hapusDariUsul(idx) {
    const item   = usulHapus[idx];
    const colors = getSwalColors();
    const { isConfirmed } = await Swal.fire({
        title: 'Batalkan Usulan?',
        html: `<p class="text-sm mb-2">Item akan dikembalikan ke daftar rusak berat:</p>
               <p class="font-semibold">${item.nama_barang}</p>
               <p class="text-xs mt-1">${item.nup}</p>`,
        icon: 'question', showCancelButton:true,
        confirmButtonText:'Ya, Batalkan', confirmButtonColor:'#ef4444', ...colors
    });
    if (!isConfirmed) return;
    await api('hapus_usul_hapus', 'POST', { perangkat_id: item.perangkat_id ?? item.id });
    await loadUsulHapus();
    renderUsulHapus();
    renderRusakBerat();
}

/* ══════════════════════════════════════════════════════════════════
   TAB 3 — PENGAJUAN PENGHAPUSAN
══════════════════════════════════════════════════════════════════ */
function renderPengajuan() {
    const tbody    = document.getElementById('pengajuanTableBody');
    const infoRole = document.getElementById('infoRoleSpecific');
    infoRole.innerHTML = userInfo.role === 'admin'
        ? 'Admin akan input <b>Risalah Lelang</b> saat disetujui.'
        : 'Anda dapat membatalkan pengajuan pending milik Anda.';

    if (!pengajuanList.length) {
        tbody.innerHTML = `<tr><td colspan="7" class="py-16 text-center pgj-cell-muted text-sm">Belum ada pengajuan</td></tr>`;
        return;
    }

    const statusBadge = {
        pending  : '<span class="px-3 py-1.5 bg-amber-500/20 text-amber-300 rounded-lg text-xs">⏳ Pending</span>',
        approved : '<span class="px-3 py-1.5 bg-green-500/20 text-green-300 rounded-lg text-xs">✓ Approved</span>',
        rejected : '<span class="px-3 py-1.5 bg-red-500/20 text-red-300 rounded-lg text-xs">✗ Rejected</span>'
    };

    tbody.innerHTML = pengajuanList.map((p, idx) => {
        const isAdmin    = userInfo.role === 'admin';
        const isPending  = p.status === 'pending';
        const isApproved = p.status === 'approved';
        const isOwner    = String(p.diajukan_oleh_id) === String(userInfo.user_id);
        return `
        <tr class="transition-colors">
            <td class="px-4 py-3.5"><span class="px-2 py-1 bg-cyan-500/15 text-cyan-400 rounded-lg text-xs font-mono">${p.nomor_pengajuan}</span></td>
            <td class="px-4 py-3.5">
                ${p.nomor_sk
                    ? `<span class="px-2.5 py-1 bg-green-500/15 text-green-300 rounded-lg text-xs font-mono">${p.nomor_sk}</span>`
                    : `<span class="pgj-cell-muted text-xs">-</span>`}
            </td>
            <td class="px-4 py-3.5 pgj-cell-muted text-xs">${new Date(p.tanggal??p.created_at).toLocaleDateString('id-ID')}</td>
            <td class="px-4 py-3.5">
                <div class="pgj-cell font-semibold text-sm">${p.diajukan_oleh}</div>
                <div class="text-xs text-blue-400">${p.role_pengaju||''}</div>
            </td>
            <td class="px-4 py-3.5 text-center">
                <span class="px-3 py-1.5 bg-purple-500/20 text-purple-300 rounded-lg text-sm font-bold">${p.jumlah_perangkat}</span>
            </td>
            <td class="px-4 py-3.5 text-center whitespace-nowrap">${statusBadge[p.status]||''}</td>
            <td class="px-4 py-3.5">
                <div class="flex gap-2 justify-center flex-wrap">
                    <button onclick="lihatDetailPengajuan(${idx})"
                            class="px-3 py-1.5 bg-blue-600/20 hover:bg-blue-600 text-blue-400 hover:text-white rounded-lg text-xs transition-all">Detail</button>
                    ${isAdmin && isPending ? `
                        <button onclick="approvePengajuan(${idx})"
                                class="px-3 py-1.5 bg-green-600/20 hover:bg-green-600 text-green-400 hover:text-white rounded-lg text-xs transition-all">Approve</button>
                        <button onclick="rejectPengajuan(${idx})"
                                class="px-3 py-1.5 bg-red-600/20 hover:bg-red-600 text-red-400 hover:text-white rounded-lg text-xs transition-all">Reject</button>` : ''}
                    ${!isAdmin && isOwner && isPending ? `
                        <button onclick="batalkanPengajuan(${idx})"
                                class="px-3 py-1.5 bg-red-600/20 hover:bg-red-600 text-red-400 hover:text-white rounded-lg text-xs transition-all">Batalkan</button>` : ''}
                    ${isApproved && p.nomor_sk ? `
                        <button onclick="downloadPDF('${p.nomor_sk.replace(/'/g,"\\'")}',${idx})"
                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-600/20 hover:bg-red-600 text-red-400 hover:text-white rounded-lg text-xs transition-all">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            PDF</button>` : ''}
                </div>
            </td>
        </tr>`;
    }).join('');
    updateBadgePengajuan();
}

function lihatDetailPengajuan(idx) {
    const p       = pengajuanList[idx];
    const isAdmin = userInfo.role === 'admin';
    const isPending = p.status === 'pending';
    const colors  = getSwalColors();
    const isLight = document.documentElement.classList.contains('light');
    const thStyle = `style="color:${isLight?'#475569':'#94a3b8'};font-size:11px;padding:8px 12px;text-align:left;"`;

    const rows = (p.perangkat||[])
        .filter(item => item.status_item !== 'rejected')
        .map((item, i) => `
            <tr style="border-bottom:1px solid ${isLight?'#e2e8f0':'rgba(55,65,81,0.3)'}">
                <td class="px-3 py-2 text-center pgj-cell-muted">${i+1}</td>
                <td class="px-3 py-2 text-blue-400 font-mono text-xs">${item.kode_barang}</td>
                <td class="px-3 py-2 pgj-cell-muted text-xs">${item.nup}</td>
                <td class="px-3 py-2 pgj-cell text-xs">${item.nama_barang}</td>
                ${isAdmin && isPending ? `<td class="px-3 py-2 text-center">
                    <button onclick="rejectSatuItem(${idx},${item.id})"
                            class="px-2 py-1 bg-red-600/20 hover:bg-red-600 text-red-400 hover:text-white rounded text-xs transition-all">Batalkan</button>
                </td>` : ''}
            </tr>`).join('');

    Swal.fire({
        title: 'Detail Pengajuan',
        html: `<div class="text-left">
            <div style="padding:12px;border-radius:10px;background:${isLight?'#eff6ff':'rgba(6,182,212,0.1)'};margin-bottom:12px;">
                <p class="text-sm pgj-cell">No: <b class="text-cyan-400">${p.nomor_pengajuan}</b></p>
                ${p.nomor_sk ? `<p class="text-sm pgj-cell mt-1">No. SK: <b class="text-green-400">${p.nomor_sk}</b></p>` : ''}
                <p class="text-xs pgj-cell-muted mt-1">Status: ${p.status}</p>
            </div>
            <div style="border-radius:10px;border:1px solid ${isLight?'#e2e8f0':'rgba(51,65,85,0.5)'};overflow:hidden;">
                <div style="padding:10px 16px;background:${isLight?'#f1f5f9':'rgba(15,23,42,0.6)'};">
                    <p class="font-semibold pgj-cell text-sm">Perangkat (${p.jumlah_perangkat})</p>
                </div>
                <div style="overflow-x:auto;max-height:320px;">
                    <table style="width:100%;">
                        <thead style="background:${isLight?'#f8fafc':'rgba(15,23,42,0.5)'};">
                            <tr>
                                <th ${thStyle}>No</th>
                                <th ${thStyle}>Kode</th>
                                <th ${thStyle}>NUP</th>
                                <th ${thStyle}>Nama</th>
                                ${isAdmin && isPending ? `<th ${thStyle}>Aksi</th>` : ''}
                            </tr>
                        </thead>
                        <tbody>${rows}</tbody>
                    </table>
                </div>
            </div>
        </div>`,
        width: '760px', showCloseButton:true, showConfirmButton:false, ...colors
    });
}

async function rejectSatuItem(pengajuanIdx, itemId) {
    const p      = pengajuanList[pengajuanIdx];
    const item   = (p.perangkat||[]).find(x => x.id == itemId);
    const colors = getSwalColors();
    const isLight = document.documentElement.classList.contains('light');
    const inputStyle = `width:100%;padding:10px;border-radius:10px;border:1px solid ${isLight?'#cbd5e1':'rgba(51,65,85,0.5)'};background:${isLight?'#f8fafc':'rgba(15,23,42,0.8)'};color:${isLight?'#0f172a':'#fff'};font-size:13px;`;

    const { value: alasan } = await Swal.fire({
        title: 'Reject Item Ini?',
        html: `<div class="text-left">
            <div style="padding:12px;border-radius:10px;background:rgba(239,68,68,0.1);margin-bottom:12px;">
                <p class="text-sm font-semibold">${item?.nama_barang||''}</p>
                <p class="text-xs pgj-cell-muted">NUP: ${item?.nup||''}</p>
            </div>
            <textarea id="alasanRejectItem" rows="3" placeholder="Alasan penolakan *" style="${inputStyle}border-radius:10px;"></textarea>
        </div>`,
        icon:'warning', showCancelButton:true,
        confirmButtonText:'Reject Item', confirmButtonColor:'#ef4444', ...colors,
        preConfirm: () => {
            const v = document.getElementById('alasanRejectItem').value;
            if (!v.trim()) { Swal.showValidationMessage('Alasan harus diisi'); return false; }
            return v;
        }
    });
    if (!alasan) return;

    const res = await api('update_status_pengajuan', 'POST', {
        pengajuan_id : p.id,
        action       : 'reject_item',
        item_id      : itemId,
        alasan_reject: alasan,
    });

    if (res.status === 'success') {
        await loadPengajuan();
        renderPengajuan();
        Swal.fire({ icon:'success', title:'Item Ditolak', timer:2000, showConfirmButton:false, ...colors })
            .then(() => lihatDetailPengajuan(pengajuanIdx));
    }
}

async function approvePengajuan(idx) {
    const p      = pengajuanList[idx];
    const colors = getSwalColors();
    const isLight = document.documentElement.classList.contains('light');
    const inputStyle = `width:100%;padding:10px;border-radius:10px;border:1px solid ${isLight?'#cbd5e1':'rgba(51,65,85,0.5)'};background:${isLight?'#f8fafc':'rgba(15,23,42,0.8)'};color:${isLight?'#0f172a':'#fff'};font-size:13px;`;

    const { value: formData } = await Swal.fire({
        title: 'Approve Pengajuan',
        html: `<div class="text-left space-y-3">
            <div style="padding:12px;border-radius:10px;background:rgba(16,185,129,0.1);">
                <p class="text-sm pgj-cell">No: <b>${p.nomor_pengajuan}</b> (${p.jumlah_perangkat} item)</p>
            </div>
            <div style="padding:12px;border-radius:10px;border:1px solid rgba(239,68,68,0.4);background:rgba(239,68,68,0.05);">
                <label style="display:block;font-size:12px;font-weight:600;color:#f87171;margin-bottom:8px;">📋 Risalah Lelang / No. SK *</label>
                <input type="text" id="nomorSKApprove" placeholder="Contoh: SK/001/2025" style="${inputStyle}">
                <p style="font-size:11px;color:#94a3b8;margin-top:6px;">* Wajib diisi</p>
            </div>
            <textarea id="catatanApprove" rows="2" placeholder="Catatan admin (opsional)" style="${inputStyle}border-radius:10px;"></textarea>
        </div>`,
        icon:'question', showCancelButton:true,
        confirmButtonText:'✓ Approve', confirmButtonColor:'#10b981', ...colors,
        preConfirm: () => {
            const nomorSK = document.getElementById('nomorSKApprove').value.trim();
            if (!nomorSK) { Swal.showValidationMessage('Risalah Lelang wajib diisi!'); return false; }
            return { nomorSK, catatan: document.getElementById('catatanApprove').value.trim() };
        }
    });
    if (!formData) return;

    const res = await api('update_status_pengajuan', 'POST', {
        pengajuan_id : p.id,
        action       : 'approve',
        nomor_sk     : formData.nomorSK,
        catatan      : formData.catatan,
    });

    if (res.status === 'success') {
        await loadPengajuan();
        renderPengajuan();
        Swal.fire({ icon:'success', title:'Disetujui!', html:`<p>Nomor SK: <b>${formData.nomorSK}</b></p><p class="text-sm mt-1">${p.jumlah_perangkat} perangkat dipindahkan</p>`, timer:3000, showConfirmButton:false, ...colors });
    } else {
        Swal.fire({ icon:'error', title:'Gagal', html: res.message + (res.details?.errors?.length ? '<br><small>' + res.details.errors.join('<br>') + '</small>' : ''), ...colors });
    }
}

async function rejectPengajuan(idx) {
    const p      = pengajuanList[idx];
    const colors = getSwalColors();
    const isLight = document.documentElement.classList.contains('light');
    const inputStyle = `width:100%;padding:10px;border-radius:10px;border:1px solid ${isLight?'#cbd5e1':'rgba(51,65,85,0.5)'};background:${isLight?'#f8fafc':'rgba(15,23,42,0.8)'};color:${isLight?'#0f172a':'#fff'};font-size:13px;`;

    const { value: alasan } = await Swal.fire({
        title: 'Reject Pengajuan?',
        html: `<div class="text-left">
            <div style="padding:12px;border-radius:10px;background:rgba(239,68,68,0.1);margin-bottom:12px;">
                <p class="text-sm pgj-cell">No: <b>${p.nomor_pengajuan}</b></p>
            </div>
            <textarea id="alasanReject" rows="3" placeholder="Alasan penolakan *" style="${inputStyle}border-radius:10px;"></textarea>
        </div>`,
        icon:'warning', showCancelButton:true,
        confirmButtonText:'✗ Reject', confirmButtonColor:'#ef4444', ...colors,
        preConfirm: () => {
            const v = document.getElementById('alasanReject').value;
            if (!v.trim()) { Swal.showValidationMessage('Alasan harus diisi'); return false; }
            return v;
        }
    });
    if (!alasan) return;

    await api('update_status_pengajuan', 'POST', {
        pengajuan_id : p.id,
        action       : 'reject',
        alasan_reject: alasan,
    });
    await loadPengajuan();
    renderPengajuan();
    Swal.fire({ icon:'info', title:'Ditolak', timer:2000, showConfirmButton:false, ...colors });
}

async function batalkanPengajuan(idx) {
    const p      = pengajuanList[idx];
    const colors = getSwalColors();
    const { isConfirmed } = await Swal.fire({
        title:'Batalkan Pengajuan?', text:`No: ${p.nomor_pengajuan}`, icon:'question',
        showCancelButton:true, confirmButtonText:'Ya', confirmButtonColor:'#ef4444', ...colors
    });
    if (!isConfirmed) return;

    await api('update_status_pengajuan', 'POST', {
        pengajuan_id: p.id,
        action      : 'batalkan',
    });
    await Promise.all([loadUsulHapus(), loadPengajuan()]);
    renderPengajuan();
    renderUsulHapus();
    Swal.fire({ icon:'success', title:'Dibatalkan', text:'Perangkat dikembalikan ke daftar usul hapus', timer:2000, showConfirmButton:false, ...colors });
}

/* ══ Utilities ════════════════════════════════════════════════════ */
function updateBadgeUsulan() {
    const b = document.getElementById('badgeUsulan');
    b.textContent = usulHapus.length;
    b.classList.toggle('hidden', usulHapus.length === 0);
}
function updateBadgePengajuan() {
    const n = pengajuanList.filter(p => p.status === 'pending').length;
    const b = document.getElementById('badgePengajuan');
    b.textContent = n;
    b.classList.toggle('hidden', n === 0);
}

function downloadPDF(nomorSK) {
    const colors = getSwalColors();
    if (!nomorSK || nomorSK === 'null') {
        Swal.fire({ icon:'error', title:'Error', text:'Nomor SK tidak ditemukan', ...colors }); return;
    }
    const encoded = btoa(nomorSK).replace(/\+/g,'-').replace(/\//g,'_').replace(/=+$/,'');
    const url     = '<?= base_url("inventory/generate_pdf_penghapusan/") ?>' + encoded;
    Swal.fire({ title:'Generating PDF...', html:'Mohon tunggu', allowOutsideClick:false, didOpen:()=>Swal.showLoading(), ...colors });
    const win = window.open(url, '_blank');
    setTimeout(() => {
        Swal.close();
        if (!win || win.closed) Swal.fire({ icon:'warning', title:'Pop-up Blocked', text:'Izinkan pop-up untuk download PDF', ...colors });
    }, 2000);
}

/* ══ Init ═════════════════════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', async function() {
    await Promise.all([loadRusakBerat(), loadUsulHapus(), loadPengajuan()]);
    switchTab('daftar');
});
</script>