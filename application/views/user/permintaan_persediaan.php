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

<div class="catalog-page max-w-7xl mx-auto mt-0 mb-24 space-y-6 px-4">

    <!-- ================= WEBSHOP HERO BANNER ================= -->
    <div class="webshop-hero-banner relative rounded-3xl overflow-hidden border border-slate-200/80 shadow-md min-h-[300px] lg:min-h-[340px] flex items-center bg-slate-900">
        
        <!-- FULL BACKGROUND IMAGE (BPS.JPG) -->
        <img src="<?= base_url('assets/img/BPS.jpg') ?>"
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

            <!-- Kartu transparan bergaya marketplace: hanya gambar + teks, tanpa kotak kartu -->
            <div id="katalogGrid" class="catalog-products grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-3 sm:gap-4">
                <?php foreach (($barang_list ?? []) as $b): ?>
                <?php
                    $bId      = (int) $b['id'];
                    $bNama    = $b['nama_barang'] ?? '-';
                    $bGambar  = !empty($b['gambar']) && file_exists(FCPATH . 'uploads/persediaan/' . $b['gambar']);
                    $bRCount  = (int) ($b['rating_count'] ?? 0);
                    $bDiminta = (int) ($b['total_terlaris'] ?? 0);
                    $bInisial = strtoupper(mb_substr(trim($bNama), 0, 2));
                    // 5.0 -> "5", 3.5 -> "3,5"
                    $bAvg     = $bRCount > 0 ? rtrim(rtrim(number_format((float) ($b['rating_avg'] ?? 0), 1, ',', '.'), '0'), ',') : '';
                ?>
                <div class="barang-card group flex flex-col cursor-pointer"
                     onclick="openDetailBarang(<?= $bId ?>, event)"
                     data-kat="<?= htmlspecialchars($b['kategori_id'] ?? '') ?>"
                     data-nama="<?= htmlspecialchars(strtolower($b['nama_barang'] ?? '')) ?>"
                     data-rating="<?= (float) ($b['rating_avg'] ?? 0) ?>"
                     data-terlaris="<?= (int) ($b['total_terlaris'] ?? 0) ?>"
                     data-search="<?= htmlspecialchars(strtolower(($b['kode_barang'] ?? '') . ' ' . ($b['nama_barang'] ?? ''))) ?>">

                    <!-- Gambar persegi + label kategori di pojok kiri bawah -->
                    <div class="relative aspect-square rounded-xl bg-slate-100 overflow-hidden flex items-center justify-center transition-shadow duration-300 group-hover:shadow-lg">
                        <?php if ($bGambar): ?>
                            <img src="<?= base_url('uploads/persediaan/' . $b['gambar']) ?>"
                                 alt="<?= htmlspecialchars($bNama) ?>" loading="lazy"
                                 class="w-full h-full object-contain p-3 mix-blend-multiply transition-transform duration-300 group-hover:scale-105">
                        <?php else: ?>
                            <span class="text-3xl font-extrabold tracking-wide text-slate-300 select-none" aria-hidden="true">
                                <?= htmlspecialchars($bInisial) ?>
                            </span>
                        <?php endif; ?>

                        <?php if (!empty($b['kategori_nama'])): ?>
                            <span class="absolute left-0 bottom-0 max-w-[85%] truncate rounded-tr-lg bg-[#0B2E6B] px-2 py-1 text-[10px] font-bold text-white">
                                <?= htmlspecialchars($b['kategori_nama']) ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Info -->
                    <div class="flex flex-col gap-1 pt-2.5 flex-1">
                        <h3 class="text-[13px] font-medium leading-snug text-slate-800 line-clamp-2 min-h-[2.5rem] group-hover:text-[#0B2E6B] transition-colors">
                            <?= htmlspecialchars($bNama) ?>
                        </h3>

                        <!-- Satuan (menggantikan harga) -->
                        <p class="leading-tight text-slate-900">
                            <span class="text-[11px] font-medium text-slate-500">per</span>
                            <span class="text-base font-bold"><?= htmlspecialchars($b['satuan'] ?? 'pcs') ?></span>
                        </p>

                        <!-- Rating · Diminta (klik = halaman ulasan) -->
                        <a href="<?= base_url('user_ticket/ulasan_barang/' . $bId) ?>" onclick="event.stopPropagation()"
                           title="Lihat ulasan barang"
                           class="inline-flex items-center gap-1 text-[12px] text-slate-500 hover:text-[#0B2E6B] transition-colors">
                            <span class="<?= $bRCount > 0 ? 'text-amber-400' : 'text-slate-300' ?>" aria-hidden="true">★</span>
                            <?php if ($bRCount > 0): ?>
                                <span class="font-medium text-slate-700"><?= $bAvg ?></span>
                            <?php else: ?>
                                <span>Belum ada rating</span>
                            <?php endif; ?>
                            <?php if ($bDiminta > 0): ?>
                                <span aria-hidden="true">·</span>
                                <span>Diminta <?= $bDiminta ?>×</span>
                            <?php endif; ?>
                        </a>

                        <!-- Kode barang -->
                        <span class="inline-flex items-center gap-1.5 min-w-0 text-[12px] text-slate-500">
                            <span class="w-4 h-4 rounded-full bg-slate-100 text-slate-400 inline-flex items-center justify-center flex-shrink-0">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5a2 2 0 011.41.59l7 7a2 2 0 010 2.82l-5 5a2 2 0 01-2.82 0l-7-7A2 2 0 015 10V5a2 2 0 012-2z"/>
                                </svg>
                            </span>
                            <span class="font-mono truncate"><?= htmlspecialchars($b['kode_barang'] ?? '-') ?></span>
                        </span>

                        <!-- Aksi keranjang -->
                        <div class="mt-auto pt-2.5 w-full" onclick="event.stopPropagation()">
                            <div id="stepper-<?= $bId ?>" class="qty-stepper" style="display:none" onclick="event.stopPropagation()">
                                <button type="button" onclick="event.stopPropagation();cardStep(<?= $bId ?>, -1)"
                                    class="qty-stepper-button" aria-label="Kurangi jumlah">−</button>
                                <input id="qty-<?= $bId ?>" type="number" value="1" min="1" onclick="event.stopPropagation()" onchange="cardInput(<?= $bId ?>, this.value)"
                                    class="qty-stepper-input" aria-label="Jumlah barang">
                                <button type="button" onclick="event.stopPropagation();cardStep(<?= $bId ?>, 1)"
                                    class="qty-stepper-button" aria-label="Tambah jumlah">+</button>
                            </div>
                            <button id="add-<?= $bId ?>" type="button" onclick="event.stopPropagation();cartAddFirst(<?= $bId ?>)"
                                class="w-full h-[38px] rounded-xl border border-[#0B2E6B] bg-white text-[#0B2E6B] hover:bg-[#0B2E6B] hover:text-white text-xs font-bold transition-colors active:scale-95 flex items-center justify-center gap-1.5">
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
        <div class="grid grid-cols-2 gap-3 relative z-30">
            <div>
                <label class="text-xs font-bold text-gray-700">Unit kerja</label>
                <div class="custom-dropdown-container relative z-30" id="unitKerjaDropdownWrap">
                    <input type="hidden" name="unit_kerja" id="fUnitKerja" class="unit-kerja-hidden-input" value="<?= htmlspecialchars($unit_kerja_default ?? '') ?>">
                    
                    <!-- Trigger Button -->
                    <button type="button" class="dropdown-trigger mt-1 w-full h-12 rounded-xl px-4 border-2 border-gray-200 bg-white hover:border-gray-300 focus:border-blue-800 focus:ring-4 focus:ring-blue-100 text-sm font-medium transition-all flex items-center justify-between text-left">
                        <span class="dropdown-label truncate <?= empty($unit_kerja_default) ? 'text-gray-400 font-medium' : 'text-gray-800 font-semibold' ?>">
                            <?= htmlspecialchars(!empty($unit_kerja_default) ? $unit_kerja_default : '-- Pilih unit kerja --') ?>
                        </span>
                        <svg class="dropdown-chevron w-4 h-4 text-gray-500 transition-transform duration-200 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Dropdown Panel (drops up above the trigger) -->
                    <div class="dropdown-panel hidden absolute left-0 bottom-full mb-1.5 z-50 min-w-[280px] w-[320px] max-w-[85vw] rounded-2xl shadow-2xl border border-gray-200 bg-white p-3 space-y-2">
                        <!-- Search Box (Q Search X) -->
                        <div class="flex items-center bg-slate-50 border border-gray-200 rounded-xl px-3 py-2 focus-within:border-blue-600 focus-within:bg-white focus-within:ring-2 focus-within:ring-blue-100 transition-all">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" class="dropdown-search w-full bg-transparent text-sm text-gray-800 placeholder-gray-400 focus:outline-none" placeholder="Search" autocomplete="off">
                            <button type="button" class="dropdown-clear hidden text-gray-400 hover:text-gray-600 p-0.5 ml-1 transition" title="Clear">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Options List -->
                        <div class="dropdown-options max-h-52 overflow-y-auto space-y-0.5 pr-1 text-sm">
                            <div class="dropdown-opt px-3 py-2 rounded-xl text-gray-700 font-medium hover:bg-blue-50 hover:text-blue-900 cursor-pointer transition-colors flex items-center justify-between <?= empty($unit_kerja_default) ? 'bg-blue-50 text-blue-900 font-semibold is-selected' : '' ?>"
                                 data-value=""
                                 data-label="-- Pilih unit kerja --">
                                <span class="truncate">-- Pilih unit kerja --</span>
                                <svg class="opt-check <?= empty($unit_kerja_default) ? '' : 'hidden' ?> w-4 h-4 text-blue-900 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <?php foreach (($unit_kerja_list ?? []) as $uk): ?>
                                <?php $is_sel = (string)($unit_kerja_default ?? '') === (string)$uk; ?>
                                <div class="dropdown-opt px-3 py-2 rounded-xl text-gray-700 font-medium hover:bg-blue-50 hover:text-blue-900 cursor-pointer transition-colors flex items-center justify-between <?= $is_sel ? 'bg-blue-50 text-blue-900 font-semibold is-selected' : '' ?>"
                                     data-value="<?= htmlspecialchars($uk) ?>"
                                     data-label="<?= htmlspecialchars($uk) ?>">
                                    <span class="truncate"><?= htmlspecialchars($uk) ?></span>
                                    <svg class="opt-check <?= $is_sel ? '' : 'hidden' ?> w-4 h-4 text-blue-900 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            <?php endforeach; ?>
                            <div class="dropdown-empty hidden py-4 text-center text-xs text-gray-400 font-medium">
                                Unit kerja tidak ditemukan
                            </div>
                        </div>
                    </div>
                </div>
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
.qty-stepper {
    display: flex;
    align-items: center;
    width: 100%;
    height: 38px;
    overflow: hidden;
    border: 1px solid #bfdbfe;
    border-radius: 12px;
    background: #eff6ff;
    box-shadow: inset 0 0 0 1px rgba(255, 255, 255, .7);
}
.qty-stepper-button {
    display: grid;
    place-items: center;
    width: 42px;
    height: 100%;
    flex: 0 0 42px;
    border: 0;
    background: transparent;
    color: #1e3a8a;
    font-size: 18px;
    font-weight: 800;
    line-height: 1;
    cursor: pointer;
    transition: background-color .18s ease, color .18s ease, transform .18s ease;
}
.qty-stepper-button:hover {
    background: #dbeafe;
    color: #172554;
}
.qty-stepper-button:active {
    transform: scale(.88);
}
.qty-stepper-button:focus-visible,
.qty-stepper-input:focus-visible {
    outline: 2px solid #2563eb;
    outline-offset: -2px;
}
.qty-stepper-input {
    width: 100%;
    height: 24px;
    min-width: 0;
    border: 0;
    border-right: 1px solid #bfdbfe;
    border-left: 1px solid #bfdbfe;
    background: #fff;
    color: #172554;
    font-size: 14px;
    font-weight: 800;
    line-height: 1;
    text-align: center;
}
.qty-stepper-input:focus {
    outline: none;
}
.qty-stepper-input::-webkit-outer-spin-button,
.qty-stepper-input::-webkit-inner-spin-button {
    margin: 0;
    appearance: none;
}
.qty-stepper-input[type=number] {
    appearance: textfield;
}
.line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
#cartDrawer.open { display: flex !important; }
#cartOverlay.open { display: block !important; }

.dropdown-options::-webkit-scrollbar {
    width: 6px;
}
.dropdown-options::-webkit-scrollbar-track {
    background: #f8fafc;
    border-radius: 9999px;
}
.dropdown-options::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 9999px;
}
.dropdown-options::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

@keyframes cartShake {
    0%   { transform: scale(1) rotate(0deg); }
    15%  { transform: scale(1.15) rotate(-14deg); }
    30%  { transform: scale(1.15) rotate(14deg); }
    45%  { transform: scale(1.1) rotate(-10deg); }
    60%  { transform: scale(1.1) rotate(10deg); }
    75%  { transform: scale(1.05) rotate(-4deg); }
    90%  { transform: scale(1.02) rotate(4deg); }
    100% { transform: scale(1) rotate(0deg); }
}
.cart-shake {
    animation: cartShake 0.6s cubic-bezier(0.36, 0.07, 0.19, 0.97) both !important;
}
</style>

<script>
var activeKat = 'all';
var cartData = { count: 0, total_qty: 0, items: [] };

function openDetailBarang(id, e) {
    if (e) {
        if (e.target.closest('#stepper-' + id) || e.target.closest('#add-' + id) || e.target.closest('button') || e.target.closest('input') || e.target.closest('a')) {
            return;
        }
    }
    window.location.href = '<?= base_url("user_ticket/detail_barang/") ?>' + id;
}

function openUlasanBarang(id, e) {
    openDetailBarang(id, e);
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
    syncCards(cartData.items || []);
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

function triggerCartShake() {
    var btn = document.getElementById('cartBtn');
    if (!btn) return;
    btn.classList.remove('cart-shake');
    void btn.offsetWidth;
    btn.classList.add('cart-shake');
    setTimeout(function () {
        if (btn) btn.classList.remove('cart-shake');
    }, 650);
}

function cardStep(id, d) {
    var inp = document.getElementById('qty-' + id);
    var current = parseInt(inp ? inp.value : '1', 10) || 1;
    var next = current + d;

    triggerCartShake();

    if (next <= 0) {
        if (inp) inp.value = 1;
        var stepper = document.getElementById('stepper-' + id);
        var addBtn = document.getElementById('add-' + id);
        if (stepper) stepper.style.display = 'none';
        if (addBtn) addBtn.style.display = 'flex';
        cartRemove(id);
        return;
    }

    if (inp) inp.value = next;
    cartSetQty(id, next);
}

function cardInput(id, v) {
    var n = parseInt(v, 10);
    var inp = document.getElementById('qty-' + id);
    triggerCartShake();
    if (isNaN(n) || n <= 0) {
        if (inp) inp.value = 1;
        var stepper = document.getElementById('stepper-' + id);
        var addBtn = document.getElementById('add-' + id);
        if (stepper) stepper.style.display = 'none';
        if (addBtn) addBtn.style.display = 'flex';
        cartRemove(id);
    } else {
        if (inp) inp.value = n;
        cartSetQty(id, n);
    }
}

function postJSON(url, body) {
    var fd = new FormData();
    Object.keys(body).forEach(function (k) { fd.append(k, body[k]); });
    return fetch(url, { method: 'POST', body: fd }).then(function (r) { return r.json(); });
}

function cartAddFirst(id) {
    triggerCartShake();
    var inp = document.getElementById('qty-' + id);
    if (inp) inp.value = 1;
    var stepper = document.getElementById('stepper-' + id);
    var addBtn = document.getElementById('add-' + id);
    if (stepper) stepper.style.display = 'flex';
    if (addBtn) addBtn.style.display = 'none';

    postJSON("<?= base_url('user_ticket/cart_add') ?>", { barang_id: id, jumlah: 1 })
        .then(function (res) {
            refreshCart();
            if (res.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Masuk keranjang',
                    text: res.message,
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({ icon: 'warning', title: 'Tidak bisa ditambah', text: res.message });
            }
        })
        .catch(function () {
            refreshCart();
            Swal.fire({ icon: 'error', title: 'Gagal', text: 'Jaringan bermasalah. Coba lagi.' });
        });
}

function cartAdd(id) {
    cartAddFirst(id);
}

function cartSetQty(id, qty) {
    triggerCartShake();
    if (qty <= 0) {
        cartRemove(id);
        return;
    }
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
        var qty = map[id] || 0;
        var has = qty >= 1;
        el.style.display = has ? 'flex' : 'none';
        var addBtn = document.getElementById('add-' + id);
        if (addBtn) {
            addBtn.style.display = has ? 'none' : 'flex';
        }
        var inp = document.getElementById('qty-' + id);
        if (inp) inp.value = has ? qty : 1;
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

(function() {
    var container = document.getElementById('unitKerjaDropdownWrap');
    if (!container) return;

    var hiddenInput = container.querySelector('.unit-kerja-hidden-input');
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
                label.className = 'dropdown-label truncate text-gray-400 font-medium';
            } else {
                label.className = 'dropdown-label truncate text-gray-800 font-semibold';
            }

            options.forEach(function(o) {
                var check = o.querySelector('.opt-check');
                if (o === opt) {
                    o.classList.add('bg-blue-50', 'text-blue-900', 'font-semibold', 'is-selected');
                    if (check) check.classList.remove('hidden');
                } else {
                    o.classList.remove('bg-blue-50', 'text-blue-900', 'font-semibold', 'is-selected');
                    if (check) check.classList.add('hidden');
                }
            });

            closeDropdown();
        });
    });

    document.addEventListener('click', function(e) {
        if (!container.contains(e.target)) {
            closeDropdown();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDropdown();
        }
    });
})();

refreshCart();
renderPage();
</script>