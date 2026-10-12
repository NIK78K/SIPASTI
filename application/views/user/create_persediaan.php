<div class="catalog-page max-w-7xl mx-auto mt-6 mb-24 space-y-5 px-4">

    <!-- ================= HEADER ================= -->
    <div class="catalog-header rounded-xl border border-slate-200 bg-white px-5 py-4 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="catalog-logo">P</div>
            <div class="text-left">
                <p class="text-xs font-bold uppercase tracking-[.18em] text-blue-900">SIPASTI</p>
                <h1 class="text-xl md:text-2xl font-extrabold tracking-tight text-slate-800">Permintaan Barang Persediaan</h1>
                <p class="mt-1 text-xs text-slate-500">Pilih kebutuhan dari katalog dan masukkan ke keranjang.</p>
            </div>
        </div>
        <div class="hidden sm:flex items-center gap-2 text-xs text-slate-500">
            <span class="h-2 w-2 rounded-full bg-blue-800"></span>Katalog aktif
        </div>
    </div>

    <?php if ($this->session->flashdata('error')): ?>
    <div class="catalog-alert flex items-center gap-3 px-5 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm font-medium">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <span><?= $this->session->flashdata('error') ?></span>
    </div>
    <?php endif ?>
    <?php if ($this->session->flashdata('success')): ?>
    <div class="catalog-alert flex items-center gap-3 px-5 py-3 rounded-xl bg-blue-50 border border-blue-200 text-blue-900 text-sm font-medium">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span><?= $this->session->flashdata('success') ?></span>
    </div>
    <?php endif ?>

    <!-- ================= TOOLBAR ================= -->
    <div class="catalog-toolbar bg-white rounded-xl border border-slate-200 shadow-sm space-y-4">
        <div class="toolbar-main flex flex-col md:flex-row gap-3 md:items-center">
            <div class="relative flex-1">
                <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 110-16 8 8 0 010 16z"/>
                </svg>
                <input id="searchInput" type="text" placeholder="Cari nama atau kode barang..."
                    class="w-full h-14 rounded-xl pl-12 pr-5 border-2 border-gray-200 focus:border-blue-800 focus:ring-4 focus:ring-blue-100 text-gray-800 font-medium transition-all hover:border-gray-300">
            </div>
            <button id="cartBtn" type="button" onclick="toggleCart(true)" title="Keranjang" aria-label="Keranjang"
                class="relative inline-flex items-center justify-center h-14 w-14 rounded-xl bg-blue-900 hover:bg-blue-950 text-white font-bold text-sm transition-all shadow-lg shadow-blue-900/25">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.3 4.6a1 1 0 00.9 1.4H19M9 22a1 1 0 100-2 1 1 0 000 2zm8 0a1 1 0 100-2 1 1 0 000 2z"/>
                </svg>
                <span id="cartBadge" class="absolute -top-2 -right-2 min-w-[1.5rem] h-6 px-1.5 rounded-full bg-amber-400 text-blue-950 text-xs font-extrabold hidden items-center justify-center">0</span>
            </button>
        </div>
        <div class="category-panel rounded-xl border border-slate-200 overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-100">
                <p class="text-xs font-extrabold tracking-wide text-blue-900">KATEGORI</p>
            </div>
            <div id="chipRow" class="category-scroll flex overflow-x-auto">
                <button type="button" data-kat="all" onclick="setKategori('all', this)"
                    class="kat-chip category-item category-item-active" aria-label="Tampilkan semua kategori">
                    <span class="category-checkbox" aria-hidden="true"></span>
                    <span>Semua</span>
                </button>
                <?php foreach (($kategori_list ?? []) as $k): ?>
                <button type="button" data-kat="<?= (int) $k->id ?>" onclick="setKategori('<?= (int) $k->id ?>', this)"
                    class="kat-chip category-item" aria-label="Tampilkan kategori <?= htmlspecialchars($k->nama, ENT_QUOTES, 'UTF-8') ?>">
                    <span class="category-checkbox" aria-hidden="true"></span>
                    <span><?= htmlspecialchars($k->nama, ENT_QUOTES, 'UTF-8') ?></span>
                </button>
                <?php endforeach ?>
            </div>
        </div>
    </div>

    <!-- ================= GRID KATALOG ================= -->
    <div id="emptyState" class="hidden bg-white rounded-3xl p-10 shadow-2xl border border-gray-100 text-center">
        <div class="text-5xl mb-3">🔍</div>
        <p class="text-lg font-bold text-gray-800">Tidak ada barang yang cocok</p>
        <p class="text-sm text-gray-500">Coba kata kunci atau kategori lain.</p>
    </div>

    <div id="katalogGrid" class="catalog-products grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <?php foreach (($barang_list ?? []) as $b): ?>
        <div class="barang-card group bg-white rounded-2xl border border-gray-100 shadow hover:shadow-xl hover:-translate-y-1 transition-all overflow-hidden flex flex-col"
              data-kat="<?= htmlspecialchars($b['kategori_id'] ?? '') ?>"
              data-search="<?= htmlspecialchars(strtolower(($b['kode_barang'] ?? '') . ' ' . ($b['nama_barang'] ?? ''))) ?>">
            <div class="h-1.5 bg-gradient-to-r from-blue-950 to-blue-700"></div>
            <div class="p-4 flex flex-col gap-2 flex-1">
                <div class="flex items-center justify-between gap-2">
                    <span class="font-mono text-[11px] text-slate-500 truncate"><?= htmlspecialchars($b['kode_barang'] ?? '-') ?></span>
                </div>
                <h3 class="font-bold text-gray-800 text-sm leading-snug line-clamp-2 min-h-[2.5rem]"><?= htmlspecialchars($b['nama_barang'] ?? '-') ?></h3>
                <p class="text-[11px] text-slate-500"><?= htmlspecialchars(($b['kategori_kode'] ?? '') . ($b['kategori_nama'] ? ' • ' . $b['kategori_nama'] : '')) ?> &bull; per <?= htmlspecialchars($b['satuan'] ?? 'pcs') ?></p>
                <div class="mt-auto pt-2 flex items-center gap-2">
                    <div class="flex items-center rounded-xl border-2 border-gray-200 overflow-hidden">
                        <button type="button" onclick="stepQty(<?= (int) $b['id'] ?>, -1)"
                            class="w-8 h-9 font-extrabold text-slate-600 hover:bg-slate-100 transition">−</button>
                        <input id="qty-<?= (int) $b['id'] ?>" type="number" value="1" min="1"
                            class="w-11 h-9 text-center text-sm font-bold text-gray-800 border-x-2 border-gray-200 focus:outline-none">
                        <button type="button" onclick="stepQty(<?= (int) $b['id'] ?>, 1)"
                            class="w-8 h-9 font-extrabold text-slate-600 hover:bg-slate-100 transition">+</button>
                    </div>
                    <button type="button" onclick="cartAdd(<?= (int) $b['id'] ?>)"
                        class="flex-1 h-9 rounded-xl text-xs font-extrabold transition-all bg-blue-900 hover:bg-blue-950 text-white shadow-md shadow-blue-900/20 active:scale-95">
                        + Keranjang
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach ?>
    </div>

    <?php if (empty($barang_list)): ?>
    <div class="bg-white rounded-3xl p-10 shadow-2xl border border-gray-100 text-center">
        <div class="text-5xl mb-3">📦</div>
        <p class="text-lg font-bold text-gray-800">Katalog masih kosong</p>
        <p class="text-sm text-gray-500">Belum ada barang aktif bulan ini. Silakan kembali lagi nanti.</p>
        <a href="<?= base_url('user/dashboard') ?>" class="inline-block mt-6 px-6 py-3 rounded-xl bg-blue-900 hover:bg-blue-950 text-white font-semibold text-sm transition-all">Kembali ke Dashboard</a>
    </div>
    <?php endif ?>

    <div id="katalogPager" class="flex items-center justify-center gap-2 mt-4"></div>
</div>

<!-- ================= DRAWER KERANJANG ================= -->
<div id="cartOverlay" onclick="toggleCart(false)" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40"></div>
<aside id="cartDrawer" class="hidden fixed top-0 right-0 h-full w-full max-w-md bg-white shadow-2xl z-50 flex-col">
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-950 to-blue-800">
        <div>
            <h2 class="text-white font-extrabold text-lg">Keranjang Kebutuhan</h2>
            <p class="text-blue-100 text-xs">Tanpa bayar &mdash; hanya menandai barang dibutuhkan</p>
        </div>
        <button type="button" onclick="toggleCart(false)" class="w-9 h-9 rounded-full bg-white/20 hover:bg-white/30 text-white font-bold transition">✕</button>
    </div>

    <div class="px-6 py-3 border-b border-gray-100 flex items-center justify-between">
        <span id="cartTotalLabel" class="text-sm font-bold text-gray-700">0 jenis • 0 item</span>
        <button type="button" onclick="cartClear()" class="text-xs font-bold text-red-600 hover:text-red-700 transition">Kosongkan</button>
    </div>

    <div id="cartItems" class="flex-1 overflow-y-auto px-6 py-4 space-y-3"></div>

    <form method="post" action="<?= base_url('user_ticket/checkout_persediaan') ?>" onsubmit="return validCheckout()" class="border-t border-gray-100 px-6 py-4 space-y-3 bg-slate-50">
        <div>
            <label class="text-xs font-bold text-gray-700">Keperluan <span class="text-red-500">*</span></label>
            <input name="keperluan" id="fKeperluan" type="text" placeholder="cth: Kebutuhan ATK bagian umum"
                class="mt-1 w-full h-12 rounded-xl px-4 border-2 border-gray-200 focus:border-blue-800 focus:ring-4 focus:ring-blue-100 text-sm text-gray-800 font-medium transition-all">
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="text-xs font-bold text-gray-700">Unit kerja</label>
                <input name="unit_kerja" type="text" placeholder="cth: IPDS"
                    class="mt-1 w-full h-12 rounded-xl px-4 border-2 border-gray-200 focus:border-blue-800 focus:ring-4 focus:ring-blue-100 text-sm text-gray-800 font-medium transition-all">
            </div>
            <div>
                <label class="text-xs font-bold text-gray-700">Tanggal dibutuhkan</label>
                <input name="tanggal_dibutuhkan" id="fTanggalButuh" type="date" min="<?= date('Y-m-d') ?>"
                    class="mt-1 w-full h-12 rounded-xl px-4 border-2 border-gray-200 focus:border-blue-800 focus:ring-4 focus:ring-blue-100 text-sm text-gray-800 font-medium transition-all">
            </div>
        </div>
        <button type="submit" class="w-full h-14 rounded-xl bg-blue-900 hover:bg-blue-950 text-white font-extrabold text-sm transition-all shadow-lg shadow-blue-900/25 active:scale-[.99]">
            Buat Permintaan &bull; Tandai Kebutuhan
        </button>
        <button type="button" onclick="toggleCart(false)" class="w-full h-12 rounded-xl bg-white border-2 border-gray-200 hover:border-gray-300 text-slate-600 font-bold text-sm transition-all">Batal</button>
    </form>
</aside>

<style>
.catalog-page {
    color: #1e293b;
    background: #f3f6f9;
    border-radius: 18px;
    padding-top: 14px;
    padding-bottom: 28px;
}
.catalog-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.catalog-logo {
    width: 54px;
    height: 54px;
    display: grid;
    place-items: center;
    border-radius: 8px;
    background: linear-gradient(145deg, #dbeafe, #eff6ff);
    color: #2563eb;
    font-size: 34px;
    font-weight: 900;
    font-style: italic;
}
.catalog-toolbar {
    padding: 10px;
}
.catalog-products {
    min-width: 0;
}
.catalog-alert {
    grid-column: 1 / -1;
}
.catalog-page .barang-card {
    border-radius: 5px;
    box-shadow: 0 1px 4px rgba(15, 23, 42, .08);
}
.catalog-page .barang-card:hover {
    box-shadow: 0 8px 18px rgba(15, 23, 42, .12);
}
.catalog-page .barang-card .p-4 {
    padding: 12px;
}
.catalog-page .category-panel {
    background: #fff;
}
@media (min-width: 1024px) {
    .catalog-page {
        display: grid;
        grid-template-columns: 185px minmax(0, 1fr);
        grid-template-rows: auto auto 108px auto auto;
        grid-template-areas:
            "header header"
            "alerts alerts"
            "sidebar toolbar"
            "sidebar products"
            "sidebar pager";
        column-gap: 10px;
        align-items: start;
    }
    .catalog-page > .catalog-header {
        grid-area: header;
        margin-bottom: 14px;
    }
    .catalog-page > .catalog-alert {
        grid-area: alerts;
        margin-bottom: 0;
    }
    .catalog-page > .catalog-toolbar {
        display: contents;
    }
    .catalog-page > .catalog-toolbar > .toolbar-main {
        grid-area: toolbar;
        grid-column: 2;
        grid-row: 3;
        min-height: 108px;
        padding: 10px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 5px 5px 0 0;
    }
    .catalog-page > .catalog-toolbar > .category-panel {
        grid-area: sidebar;
        grid-column: 1;
        grid-row: 3;
        align-self: start;
        margin-top: 0;
        height: 430px;
        overflow-y: auto;
        border-radius: 5px;
    }
    .catalog-page > .catalog-toolbar > p {
        grid-area: toolbar;
        grid-column: 2;
        grid-row: 3;
        align-self: end;
        justify-self: end;
        margin: 0 14px 10px 0;
        z-index: 1;
    }
    .catalog-page > .catalog-products,
    .catalog-page > #emptyState,
    .catalog-page > #katalogPager {
        grid-column: 2;
        align-self: start;
        margin-top: 0;
    }
    .catalog-page > .catalog-products { grid-row: 4; }
    .catalog-page > #katalogPager { grid-area: pager; grid-row: 5; padding: 12px 0 4px; }
    .catalog-page > #emptyState { grid-area: products; grid-row: 4; }
    .catalog-page .category-scroll {
        display: block;
        overflow-x: hidden;
    }
    .catalog-page .category-item {
        width: 100%;
        min-width: 0;
        height: 54px;
        flex-direction: row;
        justify-content: flex-start;
        gap: 10px;
        padding: 8px 11px;
        border-right: 0;
        border-bottom: 1px solid #e2e8f0;
        text-align: left;
    }
    .catalog-page .category-checkbox {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }
    .catalog-page .category-item-active {
        box-shadow: inset 3px 0 0 #1e3a8a;
    }
}
@media (max-width: 1023px) {
    .catalog-header { display: block; }
    .catalog-toolbar { padding: 14px; }
}
.line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
#cartDrawer.open { display: flex !important; }
#cartOverlay.open { display: block !important; }
.category-scroll { scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent; }
.category-item {
    min-width: 80px; height: 108px; padding: 10px 8px;
    display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px;
    border-right: 1px solid #e2e8f0; color: #334155; font-size: 11px; font-weight: 600;
    line-height: 1.25; text-align: center; transition: background .15s, color .15s, box-shadow .15s;
}
.category-item:last-child { border-right: 0; }
.category-item:hover { background: #eff6ff; color: #1e3a8a; }
.category-item-active { background: #eff6ff; color: #1e3a8a; box-shadow: inset 0 -3px 0 #1e3a8a; }
.category-checkbox {
    width: 20px;
    height: 20px;
    display: inline-grid;
    place-items: center;
    border: 2px solid #94a3b8;
    border-radius: 4px;
    background: #fff;
    flex-shrink: 0;
}
.category-checkbox::after {
    content: "";
    width: 5px;
    height: 9px;
    border: solid #fff;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg) scale(0);
    transition: transform .12s ease;
}
.category-item-active .category-checkbox {
    border-color: #1e3a8a;
    background: #1e3a8a;
}
.category-item-active .category-checkbox::after {
    transform: rotate(45deg) scale(1);
}
</style>

<script>
var activeKat = 'all';
var cartData = { count: 0, total_qty: 0, items: [] };

function setKategori(id, btn) {
    activeKat = String(id);
    document.querySelectorAll('.kat-chip').forEach(function (c) {
        c.classList.remove('category-item-active');
    });
    if (btn) btn.classList.add('category-item-active');
    applyFilter();
}

document.getElementById('searchInput').addEventListener('input', applyFilter);

function applyFilter() {
    currentPage = 1;
    renderPage();
}

function visibleCards() {
    var q = document.getElementById('searchInput').value.toLowerCase().trim();
    var out = [];
    document.querySelectorAll('.barang-card').forEach(function (el) {
        var okKat = (activeKat === 'all') || (el.dataset.kat === activeKat);
        var okQ = !q || (el.dataset.search || '').indexOf(q) !== -1;
        if (okKat && okQ) out.push(el);
    });
    return out;
}

var currentPage = 1;
var pageSize = 12;

function renderPage() {
    var list = visibleCards();
    var totalPages = Math.max(1, Math.ceil(list.length / pageSize));
    if (currentPage > totalPages) currentPage = totalPages;
    document.querySelectorAll('.barang-card').forEach(function (el) { el.style.display = 'none'; });
    list.forEach(function (el, i) {
        if (i >= (currentPage - 1) * pageSize && i < currentPage * pageSize) el.style.display = '';
    });
    document.getElementById('emptyState').classList.toggle('hidden', list.length > 0);
    var pager = document.getElementById('katalogPager');
    if (!pager) return;
    if (totalPages <= 1 || !list.length) { pager.innerHTML = ''; return; }
    var btn = 'min-w-[2.5rem] h-10 px-3 rounded-xl border-2 text-sm font-bold transition ';
    var html = '<button type="button" onclick="goPage(' + (currentPage - 1) + ')" ' + (currentPage <= 1 ? 'disabled' : '')
        + ' class="' + btn + 'border-gray-200 bg-white text-slate-600 disabled:opacity-40 hover:border-blue-800">Prev</button>';
    var pages = [];
    if (totalPages <= 7) {
        for (var p = 1; p <= totalPages; p++) pages.push(p);
    } else if (currentPage <= 4) {
        pages = [1, 2, 3, 4, 5, '...', totalPages];
    } else if (currentPage >= totalPages - 3) {
        pages = [1, '...', totalPages - 4, totalPages - 3, totalPages - 2, totalPages - 1, totalPages];
    } else {
        pages = [1, '...', currentPage - 1, currentPage, currentPage + 1, '...', totalPages];
    }
    pages.forEach(function (p) {
        if (p === '...') {
            html += '<span class="px-1 text-sm font-bold text-slate-400">...</span>';
        } else if (p === currentPage) {
            html += '<button type="button" class="' + btn + 'border-blue-900 bg-blue-900 text-white">' + p + '</button>';
        } else {
            html += '<button type="button" onclick="goPage(' + p + ')" class="' + btn + 'border-gray-200 bg-white text-slate-600 hover:border-blue-800">' + p + '</button>';
        }
    });
    html += '<button type="button" onclick="goPage(' + (currentPage + 1) + ')" ' + (currentPage >= totalPages ? 'disabled' : '')
        + ' class="' + btn + 'border-gray-200 bg-white text-slate-600 disabled:opacity-40 hover:border-blue-800">Next</button>';
    pager.innerHTML = html;
}

function goPage(p) {
    currentPage = p;
    renderPage();
    document.getElementById('katalogGrid').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function stepQty(id, d) {
    var inp = document.getElementById('qty-' + id);
    if (!inp) return;
    var v = (parseInt(inp.value || '0', 10) || 0) + d;
    if (v < 0) v = 0;
    inp.value = v;
}

function postJSON(url, body) {
    var fd = new FormData();
    Object.keys(body).forEach(function (k) { fd.append(k, body[k]); });
    return fetch(url, { method: 'POST', body: fd }).then(function (r) { return r.json(); });
}

function cartAdd(id) {
    var inp = document.getElementById('qty-' + id);
    var q = inp ? (parseInt(inp.value || '0', 10) || 0) : 0;
    if (q < 1) {
        Swal.fire({ icon: 'warning', title: 'Jumlah masih 0', text: 'Naikkan dulu jumlah barang yang dibutuhkan.' });
        return;
    }
    postJSON("<?= base_url('user_ticket/cart_add') ?>", { barang_id: id, jumlah: q })
        .then(function (res) {
            refreshCart();
            if (res.success) Swal.fire({ icon: 'success', title: 'Masuk keranjang', text: res.message, timer: 1500, showConfirmButton: false });
            else Swal.fire({ icon: 'warning', title: 'Tidak bisa ditambah', text: res.message });
        })
        .catch(function () { Swal.fire({ icon: 'error', title: 'Gagal', text: 'Jaringan bermasalah. Coba lagi.' }); });
}

function cartSetQty(id, qty) {
    postJSON("<?= base_url('user_ticket/cart_update') ?>", { barang_id: id, jumlah: qty })
        .then(function (res) {
            refreshCart();
            if (!res.success) Swal.fire({ icon: 'warning', title: 'Tidak bisa diubah', text: res.message });
        });
}

function cartRemove(id) {
    postJSON("<?= base_url('user_ticket/cart_remove') ?>", { barang_id: id }).then(function () { refreshCart(); });
}

function cartClear() {
    if (!cartData.count) return;
    Swal.fire({ title: 'Kosongkan keranjang?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', confirmButtonText: 'Ya', cancelButtonText: 'Batal' })
        .then(function (r) {
            if (!r.isConfirmed) return;
            postJSON("<?= base_url('user_ticket/cart_clear') ?>", {}).then(function () { refreshCart(); });
        });
}

function refreshCart() {
    fetch("<?= base_url('user_ticket/cart_count') ?>")
        .then(function (r) { return r.json(); })
        .then(function (res) {
            cartData = res;
            var badge = document.getElementById('cartBadge');
            badge.textContent = res.total_qty || 0;
            badge.classList.toggle('hidden', !(res.total_qty > 0));
            badge.classList.toggle('inline-flex', res.total_qty > 0);
            document.getElementById('cartTotalLabel').textContent = (res.count || 0) + ' jenis • ' + (res.total_qty || 0) + ' item';
            renderCartItems(res.items || []);
        });
}

function renderCartItems(items) {
    var box = document.getElementById('cartItems');
    if (!items.length) {
        box.innerHTML = '<div class="text-center py-10"><div class="text-5xl mb-3">🛒</div><p class="font-bold text-gray-700">Keranjang masih kosong</p><p class="text-sm text-gray-500">Pilih barang di katalog lalu tekan + Keranjang.</p></div>';
        return;
    }
    box.innerHTML = items.map(function (it) {
        return '<div class="rounded-2xl border border-gray-100 shadow-sm p-4 space-y-2">'
            + '<div class="flex items-start justify-between gap-2">'
            + '<div><p class="font-mono text-[11px] text-slate-400">' + escapeHtml(it.kode || '') + '</p>'
            + '<p class="font-bold text-sm text-gray-800 leading-snug">' + escapeHtml(it.nama || '') + '</p></div>'
            + '<button type="button" onclick="cartRemove(' + it.barang_id + ')" class="text-red-500 hover:text-red-700 text-lg leading-none">×</button></div>'
            + '<div class="flex items-center gap-2">'
            + '<button type="button" onclick="cartSetQty(' + it.barang_id + ',' + (it.qty - 1) + ')" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 font-extrabold text-slate-600">−</button>'
            + '<span class="min-w-[3rem] text-center text-sm font-extrabold text-gray-800">' + it.qty + ' ' + escapeHtml(it.satuan || '') + '</span>'
            + '<button type="button" onclick="cartSetQty(' + it.barang_id + ',' + (it.qty + 1) + ')" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 font-extrabold text-slate-600">+</button>'
            + '</div></div>';
    }).join('');
}

function escapeHtml(s) {
    return String(s).replace(/[&<>"']/g, function (c) {
        return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
}

function toggleCart(open) {
    document.getElementById('cartDrawer').classList.toggle('open', !!open);
    document.getElementById('cartOverlay').classList.toggle('open', !!open);
    document.body.style.overflow = open ? 'hidden' : '';
    if (open) refreshCart();
}

function validCheckout() {
    if (!cartData.count) {
        Swal.fire({ icon: 'warning', title: 'Keranjang kosong', text: 'Pilih dulu barang yang dibutuhkan.' });
        return false;
    }
    if (!document.getElementById('fKeperluan').value.trim()) {
        Swal.fire({ icon: 'warning', title: 'Keperluan wajib diisi', text: 'Tulis keperluan permintaan barang.' });
        return false;
    }
    var tglEl = document.getElementById('fTanggalButuh');
    if (tglEl && tglEl.value) {
        var now = new Date();
        var today = now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0') + '-' + String(now.getDate()).padStart(2, '0');
        if (tglEl.value < today) {
            Swal.fire({ icon: 'warning', title: 'Tanggal tidak valid', text: 'Tanggal dibutuhkan tidak boleh mundur. Pilih hari ini atau tanggal ke depan.' });
            return false;
        }
    }
    return true;
}

refreshCart();
renderPage();
</script>
