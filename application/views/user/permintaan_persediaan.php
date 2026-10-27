<!-- ================= BACK ================= -->
<div class="max-w-7xl mx-auto mt-6 px-4">
    <a href="<?= base_url('user_ticket/create_persediaan') ?>"
       class="inline-flex items-center gap-2 text-sm text-blue-900 hover:text-blue-950 font-semibold transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Pilih layanan lain
    </a>
</div>

<div class="catalog-page max-w-7xl mx-auto mt-6 mb-24 space-y-6 px-4">

    <!-- ================= WEBSHOP HERO BANNER ================= -->
    <div class="webshop-hero-banner relative rounded-3xl overflow-hidden border border-slate-200/80 shadow-md min-h-[300px] lg:min-h-[340px] flex items-center bg-slate-900">
        
        <!-- FULL BACKGROUND IMAGE (BPS RIAU.JPG) -->
        <img src="<?= base_url('assets/img/bps_riau.jpg') ?>"
             alt="Background Gedung BPS Provinsi Riau"
             class="absolute inset-0 w-full h-full object-cover object-center filter brightness-95">

        <!-- GRADIENT OVERLAY FOR READABILITY & BRAND IDENTITY -->
        <div class="absolute inset-0 bg-gradient-to-r from-[#0B2E6B]/95 via-[#0A3D8F]/85 to-[#0B2E6B]/40"></div>
        <div class="absolute inset-0 bg-black/25"></div>

        <!-- CONTENT ON TOP OF BACKGROUND -->
        <div class="relative z-10 p-6 sm:p-10 lg:p-14 max-w-2xl space-y-4">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 backdrop-blur-md text-white text-[11px] font-extrabold tracking-wider uppercase border border-white/20">
                <span>BPS PROVINSI RIAU</span>
                <span>&bull;</span>
                <span>SIPASTI</span>
            </div>

            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight leading-[1.12] uppercase font-sans drop-shadow-sm">
                Katalog<br>Persediaan
            </h1>

            <p class="text-xs sm:text-sm text-blue-100 max-w-lg leading-relaxed font-medium drop-shadow-sm">
                Layanan permintaan barang operasional kantor dan administrasi harian BPS Provinsi Riau. Pilih kebutuhan barang persediaan langsung dari katalog digital.
            </p>

            <div class="pt-2 flex flex-wrap items-center gap-3">
                <button type="button" onclick="document.getElementById('katalogGrid').scrollIntoView({behavior:'smooth'})"
                    class="px-6 py-3.5 rounded-xl bg-white hover:bg-slate-100 text-slate-900 font-extrabold text-xs sm:text-sm shadow-lg hover:shadow-xl transition-all active:scale-95 inline-flex items-center gap-2">
                    <span>Lihat Daftar Barang</span>
                    <svg class="w-4 h-4 text-slate-900" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
            </div>
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
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span><?= $this->session->flashdata('success') ?></span>
    </div>
    <?php endif ?>

    <!-- ================= MAIN LAYOUT (SIDEBAR KATEGORI & KATALOG GRID) ================= -->
    <div class="flex flex-col lg:flex-row items-start gap-6">

        <!-- ================= SIDEBAR KATEGORI ================= -->
        <div class="category-sidebar w-full lg:w-64 flex-shrink-0 lg:sticky lg:top-6 z-20">
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
                <p class="text-[11px] font-extrabold tracking-wider text-slate-400 uppercase mb-3 px-3">KATEGORI</p>
                <div id="categoryList" class="category-list space-y-1">
                    <!-- Item Semua -->
                    <button type="button" data-kat="all" onclick="setKategori('all', this)"
                        class="kat-chip category-item category-item-active w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition" aria-label="Tampilkan semua kategori">
                        <span>Semua</span>
                        <span class="category-badge"><?= count($barang_list ?? []) ?></span>
                    </button>
                    <!-- Item per Kategori -->
                    <?php 
                    $kat_counts = [];
                    foreach (($barang_list ?? []) as $bi) {
                        $kid = (string)($bi['kategori_id'] ?? '');
                        $kat_counts[$kid] = ($kat_counts[$kid] ?? 0) + 1;
                    }
                    ?>
                    <?php foreach (($kategori_list ?? []) as $k): ?>
                    <button type="button" data-kat="<?= (int) $k->id ?>" onclick="setKategori('<?= (int) $k->id ?>', this)"
                        class="kat-chip category-item w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-medium transition" aria-label="Tampilkan kategori <?= htmlspecialchars($k->nama, ENT_QUOTES, 'UTF-8') ?>">
                        <span class="truncate pr-2"><?= htmlspecialchars($k->nama, ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="category-badge"><?= (int)($kat_counts[(string)$k->id] ?? 0) ?></span>
                    </button>
                    <?php endforeach ?>
                </div>
            </div>
        </div>

        <!-- ================= MAIN CONTENT ================= -->
        <div class="flex-1 min-w-0 space-y-4">

            <!-- SEARCH BAR & FILTER SORT -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <!-- Search Bar -->
                <div class="relative flex-1">
                    <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10 18a8 8 0 110-16 8 8 0 010 16z"/>
                    </svg>
                    <input id="searchInput" type="text" placeholder="Cari nama atau kode barang..."
                        class="w-full h-11 pl-10 pr-4 rounded-xl bg-white border border-slate-200/90 text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 shadow-sm transition">
                </div>

                <!-- Filter Sort Dropdown -->
                <div class="relative sm:w-48 flex-shrink-0">
                    <select id="sortFilter" onchange="applyFilter()"
                        class="w-full h-11 appearance-none bg-white border border-slate-200/90 rounded-xl px-4 pr-9 text-xs font-semibold text-slate-700 hover:border-slate-300 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 shadow-sm cursor-pointer transition">
                        <option value="name_asc" selected>Nama A-Z</option>
                        <option value="name_desc">Nama Z-A</option>
                        <option value="terlaris">Terlaris</option>
                        <option value="rating_highest">Rating Tertinggi</option>
                        <option value="rating_lowest">Rating Terburuk</option>
                    </select>
                    <svg class="w-4 h-4 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>

            <!-- SUBTITLE INFO -->
            <p id="katalogInfo" class="text-xs text-slate-500 font-medium px-1">
                Menampilkan <?= count($barang_list ?? []) ?> barang
            </p>

            <!-- ================= GRID KATALOG ================= -->
            <div id="emptyState" class="hidden bg-white rounded-2xl p-10 shadow-sm border border-slate-200 text-center">
                <div class="text-5xl mb-3">🔍</div>
                <p class="text-lg font-bold text-gray-800">Tidak ada barang yang cocok</p>
                <p class="text-sm text-gray-500">Coba kata kunci atau kategori lain.</p>
            </div>

            <div id="katalogGrid" class="catalog-products grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                <?php foreach (($barang_list ?? []) as $b): ?>
                <div class="barang-card group bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all overflow-hidden flex flex-col cursor-pointer"
                      onclick="openUlasanBarang(<?= (int) $b['id'] ?>, event)"
                      data-kat="<?= htmlspecialchars($b['kategori_id'] ?? '') ?>"
                      data-nama="<?= htmlspecialchars(strtolower($b['nama_barang'] ?? '')) ?>"
                      data-rating="<?= (float) ($b['rating_avg'] ?? 0) ?>"
                      data-terlaris="<?= (int) ($b['total_terlaris'] ?? 0) ?>"
                      data-search="<?= htmlspecialchars(strtolower(($b['kode_barang'] ?? '') . ' ' . ($b['nama_barang'] ?? ''))) ?>">
                    <?php if (!empty($b['gambar']) && file_exists(FCPATH . 'uploads/persediaan/' . $b['gambar'])): ?>
                        <div class="catalog-image-frame">
                            <img src="<?= base_url('uploads/persediaan/' . $b['gambar']) ?>" alt="<?= htmlspecialchars($b['nama_barang'] ?? '-') ?>" class="catalog-image group-hover:scale-105 transition-transform duration-300">
                        </div>
                    <?php else: ?>
                        <div class="catalog-image-frame catalog-image-fallback">
                            <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7v10l8 4m0-10v10"/>
                            </svg>
                        </div>
                    <?php endif; ?>
                    <div class="p-4 pt-3 flex flex-col gap-2 flex-1">
                        <div class="flex items-center justify-between gap-2">
                            <span class="font-mono text-[10px] tracking-wide text-slate-400 truncate"><?= htmlspecialchars($b['kode_barang'] ?? '-') ?></span>
                        </div>
                        <h3 class="font-bold text-gray-800 text-sm leading-snug line-clamp-2 min-h-[2.5rem] group-hover:text-blue-900 transition-colors"><?= htmlspecialchars($b['nama_barang'] ?? '-') ?></h3>
                        <p class="text-[11px] text-slate-500 truncate"><?= htmlspecialchars(($b['kategori_kode'] ?? '') . ($b['kategori_nama'] ? ' • ' . $b['kategori_nama'] : '')) ?> &bull; per <?= htmlspecialchars($b['satuan'] ?? 'pcs') ?></p>
                        <div class="flex items-center justify-between gap-1 text-[11px] pt-0.5">
                            <?php if (!empty($b['rating_count'])): ?>
                            <span class="font-semibold text-amber-600 flex items-center gap-1">
                                ★ <?= number_format((float) $b['rating_avg'], 1) ?> (<?= (int) $b['rating_count'] ?>)
                            </span>
                            <?php else: ?>
                            <span class="text-slate-400 text-[10px]">Belum ada rating</span>
                            <?php endif ?>
                            <span class="text-blue-800 hover:text-blue-950 font-bold text-[10px] flex items-center gap-0.5">
                                Ulasan &rarr;
                            </span>
                        </div>
                        <div class="mt-auto pt-3 flex items-center gap-2" onclick="event.stopPropagation()">
                            <div id="stepper-<?= (int) $b['id'] ?>" class="flex items-center rounded-xl border-2 border-gray-200 overflow-hidden" style="display:none" onclick="event.stopPropagation()">
                                <button type="button" onclick="event.stopPropagation();cardStep(<?= (int) $b['id'] ?>, -1)"
                                    class="w-8 h-9 font-extrabold text-blue-800 hover:bg-blue-50 transition">−</button>
                                <input id="qty-<?= (int) $b['id'] ?>" type="number" value="1" min="1" onclick="event.stopPropagation()" onchange="cardInput(<?= (int) $b['id'] ?>, this.value)"
                                    class="w-11 h-9 text-center text-sm font-bold text-gray-800 border-x-2 border-gray-200 focus:outline-none">
                                <button type="button" onclick="event.stopPropagation();cardStep(<?= (int) $b['id'] ?>, 1)"
                                    class="w-8 h-9 font-extrabold text-blue-800 hover:bg-blue-50 transition">+</button>
                            </div>
                            <button id="add-<?= (int) $b['id'] ?>" type="button" onclick="event.stopPropagation();cartAdd(<?= (int) $b['id'] ?>)"
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

    </div>

</div>

<!-- ================= FLOATING CART BUTTON (POJOK KANAN BAWAH) ================= -->
<div class="fixed bottom-6 right-6 z-40">
    <button id="cartBtn" type="button" onclick="toggleCart(true)" title="Buka Keranjang Permintaan" aria-label="Buka Keranjang Permintaan"
        class="relative w-14 h-14 rounded-full bg-gradient-to-tr from-[#0B2E6B] to-[#1d4ed8] hover:from-[#08224f] hover:to-[#1740b0] text-white flex items-center justify-center shadow-[0_10px_25px_rgba(11,46,107,0.4)] hover:shadow-[0_15px_30px_rgba(11,46,107,0.6)] hover:scale-110 active:scale-95 transition-all duration-200 border-2 border-white cursor-pointer">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.3 4.6a1 1 0 00.9 1.4H19M9 22a1 1 0 100-2 1 1 0 000 2zm8 0a1 1 0 100-2 1 1 0 000 2z"/>
        </svg>
        <span id="cartBadge" class="absolute -top-1 -right-1 min-w-[1.4rem] h-5 px-1.5 rounded-full bg-amber-400 text-blue-950 text-[10px] font-black hidden items-center justify-center border-2 border-white shadow-md">0</span>
    </button>
</div>

<!-- ================= DRAWER KERANJANG ================= -->
<div id="cartOverlay" onclick="toggleCart(false)" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40"></div>
<aside id="cartDrawer" class="hidden fixed top-0 right-0 h-full w-full max-w-md bg-white shadow-2xl z-50 flex-col">
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-950 to-blue-800">
        <div>
            <h2 class="text-white font-extrabold text-lg">Keranjang Kebutuhan</h2>
            <p class="text-blue-100 text-xs">Tanpa bayar &mdash; hanya menandai barang diminta</p>
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
                <select name="unit_kerja"
                    class="mt-1 w-full h-12 rounded-xl px-4 border-2 border-gray-200 focus:border-blue-800 focus:ring-4 focus:ring-blue-100 text-sm text-gray-800 font-medium transition-all bg-white">
                    <option value="">-- Pilih unit kerja --</option>
                    <?php foreach (($unit_kerja_list ?? []) as $uk): ?>
                        <option value="<?= htmlspecialchars($uk) ?>" <?= (string)($unit_kerja_default ?? '') === (string)$uk ? 'selected' : '' ?>><?= htmlspecialchars($uk) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="text-xs font-bold text-gray-700">Tanggal permintaan</label>
                <input name="tanggal_permintaan" id="fTanggalButuh" type="date" min="<?= date('Y-m-d') ?>"
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
}
.catalog-logo {
    width: 52px;
    height: 52px;
    display: grid;
    place-items: center;
    border-radius: 12px;
    background: linear-gradient(145deg, #dbeafe, #eff6ff);
    color: #2563eb;
    font-size: 32px;
    font-weight: 900;
    font-style: italic;
}
.category-item {
    background: transparent;
    color: #475569;
    font-weight: 500;
    border: none;
    cursor: pointer;
    text-align: left;
}
.category-item:hover {
    background: #f8fafc;
    color: #0f172a;
}
.category-item.category-item-active {
    background: #eef2ff !important;
    color: #3730a3 !important;
    font-weight: 700 !important;
}
.category-item .category-badge {
    color: #94a3b8;
    font-size: 11px;
    font-weight: 600;
    min-width: 20px;
    text-align: right;
}
.category-item.category-item-active .category-badge {
    color: #4f46e5 !important;
    font-weight: 700 !important;
}
@media (max-width: 1023px) {
    .category-list {
        display: flex;
        overflow-x: auto;
        gap: 6px;
        padding-bottom: 4px;
        scrollbar-width: thin;
    }
    .category-item {
        white-space: nowrap;
        flex-shrink: 0;
        width: auto !important;
    }
}
@media (min-width: 1024px) {
    .category-sidebar {
        position: sticky;
        top: 1.5rem;
        z-index: 20;
    }
    .category-list {
        display: block;
        max-height: calc(100vh - 140px);
        overflow-y: auto;
        padding-right: 4px;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }
}
.catalog-products {
    min-width: 0;
}
.catalog-alert {
    grid-column: 1 / -1;
}
.catalog-page .barang-card {
    border-radius: 16px;
    box-shadow: 0 1px 4px rgba(15, 23, 42, .08);
}
.catalog-page .barang-card:hover {
    box-shadow: 0 8px 18px rgba(15, 23, 42, .12);
}
.catalog-image-frame {
    height: 190px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    background: #fff;
    border-bottom: 1px solid #f1f5f9;
}
.catalog-image {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 14px;
}
.catalog-image-fallback {
    background: linear-gradient(145deg, #f8fafc, #eff6ff);
}
.catalog-page .barang-card .p-4 {
    padding: 12px;
}
.line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
#cartDrawer.open { display: flex !important; }
#cartOverlay.open { display: block !important; }
</style>

<script>
var activeKat = 'all';
var cartData = { count: 0, total_qty: 0, items: [] };

function openUlasanBarang(id, e) {
    if (e) {
        if (e.target.closest('#stepper-' + id) || e.target.closest('#add-' + id) || e.target.closest('button') || e.target.closest('input')) {
            return;
        }
    }
    window.location.href = '<?= base_url("user_ticket/ulasan_barang/") ?>' + id;
}

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
    var q = (document.getElementById('searchInput').value || '').toLowerCase().trim();
    var sortVal = document.getElementById('sortFilter') ? document.getElementById('sortFilter').value : 'name_asc';
    var out = [];
    document.querySelectorAll('.barang-card').forEach(function (el) {
        var okKat = (activeKat === 'all') || (el.dataset.kat === activeKat);
        var okQ = !q || (el.dataset.search || '').indexOf(q) !== -1;
        if (okKat && okQ) out.push(el);
    });

    if (sortVal === 'name_asc') {
        out.sort(function(a, b) {
            return (a.dataset.nama || '').localeCompare(b.dataset.nama || '');
        });
    } else if (sortVal === 'name_desc') {
        out.sort(function(a, b) {
            return (b.dataset.nama || '').localeCompare(a.dataset.nama || '');
        });
    } else if (sortVal === 'terlaris') {
        out.sort(function(a, b) {
            return (parseInt(b.dataset.terlaris || 0, 10)) - (parseInt(a.dataset.terlaris || 0, 10));
        });
    } else if (sortVal === 'rating_highest') {
        out.sort(function(a, b) {
            return (parseFloat(b.dataset.rating || 0)) - (parseFloat(a.dataset.rating || 0));
        });
    } else if (sortVal === 'rating_lowest') {
        out.sort(function(a, b) {
            return (parseFloat(a.dataset.rating || 0)) - (parseFloat(b.dataset.rating || 0));
        });
    }

    return out;
}

var currentPage = 1;
var pageSize = 12;

function renderPage() {
    var list = visibleCards();
    var grid = document.getElementById('katalogGrid');
    if (grid) {
        list.forEach(function(el) {
            grid.appendChild(el);
        });
    }

    var totalPages = Math.max(1, Math.ceil(list.length / pageSize));
    if (currentPage > totalPages) currentPage = totalPages;
    document.querySelectorAll('.barang-card').forEach(function (el) { el.style.display = 'none'; });
    list.forEach(function (el, i) {
        if (i >= (currentPage - 1) * pageSize && i < currentPage * pageSize) el.style.display = '';
    });
    document.getElementById('emptyState').classList.toggle('hidden', list.length > 0);

    // Info Subtitle
    var totalAll = document.querySelectorAll('.barang-card').length;
    var infoEl = document.getElementById('katalogInfo');
    if (infoEl) {
        infoEl.textContent = 'Menampilkan ' + list.length + ' dari ' + totalAll + ' barang';
    }

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

function cartQty(id) {
    var found = 0;
    (cartData.items || []).forEach(function (it) { if (String(it.barang_id) === String(id)) found = it.qty; });
    return found;
}

function cardStep(id, d) {
    var inp = document.getElementById('qty-' + id);
    if (!inp) return;
    var v = (parseInt(inp.value || '1', 10) || 1) + d;
    if (v < 1) v = 1;
    inp.value = v;
}

function cardInput(id, v) {
    var inp = document.getElementById('qty-' + id);
    if (!inp) return;
    var n = parseInt(v, 10) || 1;
    if (n < 1) n = 1;
    inp.value = n;
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
        Swal.fire({ icon: 'warning', title: 'Jumlah masih 0', text: 'Naikkan dulu jumlah barang yang diminta.' });
        return;
    }
    var isUpdate = cartQty(id) > 0;
    postJSON(isUpdate ? "<?= base_url('user_ticket/cart_update') ?>" : "<?= base_url('user_ticket/cart_add') ?>", { barang_id: id, jumlah: q })
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
            if (badge) {
                badge.textContent = res.total_qty || 0;
                badge.classList.toggle('hidden', !(res.total_qty > 0));
                badge.classList.toggle('inline-flex', res.total_qty > 0);
            }
            document.getElementById('cartTotalLabel').textContent = (res.count || 0) + ' jenis • ' + (res.total_qty || 0) + ' item';
            renderCartItems(res.items || []);
            syncCards(res.items || []);
        });
}

function syncCards(items) {
    var map = {};
    (items || []).forEach(function (it) { map[String(it.barang_id)] = it.qty; });
    document.querySelectorAll('[id^="stepper-"]').forEach(function (el) {
        var id = el.id.replace('stepper-', '');
        var has = (map[id] || 0) >= 1;
        el.style.display = has ? '' : 'none';
        var inp = document.getElementById('qty-' + id);
        if (inp) inp.value = has ? map[id] : 1;
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
        Swal.fire({ icon: 'warning', title: 'Keranjang kosong', text: 'Pilih dulu barang yang diminta.' });
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
            Swal.fire({ icon: 'warning', title: 'Tanggal tidak valid', text: 'Tanggal permintaan tidak boleh mundur. Pilih hari ini atau tanggal ke depan.' });
            return false;
        }
    }
    return true;
}

refreshCart();
renderPage();
</script>
