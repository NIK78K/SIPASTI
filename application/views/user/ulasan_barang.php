<?php
$barang      = $barang ?? (object)[];
$idBarang    = (int) ($barang->id ?? 0);
$namaBarang  = $barang->nama_barang ?? '-';
$has_foto    = !empty($barang->gambar) && file_exists(FCPATH . 'uploads/persediaan/' . $barang->gambar);
$foto_url    = $has_foto ? base_url('uploads/persediaan/' . $barang->gambar) : '';
$kategoriLbl = !empty($barang->kategori_nama) ? $barang->kategori_nama : ($barang->kategori_kode ?? '');

$detailUrl   = base_url('user_ticket/detail_barang/' . $idBarang);
$katalogUrl  = base_url('user_ticket/permintaan_persediaan');

$ratingAvg   = (float) ($rating_avg ?? 0);
$ratingCount = (int) ($rating_count ?? 0);
$semuaUlasan = $ulasan_list ?? [];
$totalUlasan = count($semuaUlasan);
$batch       = 10;   // jumlah ulasan yang tampil per klik "Muat lebih banyak"

// Urutan awal (terbaru) — dipakai juga jika JavaScript nonaktif
usort($semuaUlasan, static function ($a, $b) {
    return (strtotime($b['created_at'] ?? '') ?: 0) <=> (strtotime($a['created_at'] ?? '') ?: 0);
});

// Jumlah per bintang (dari controller; cadangan: hitung dari daftar ulasan)
$cnt = [];
for ($s = 1; $s <= 5; $s++) {
    $cnt[$s] = isset($star_counts[$s])
        ? (int) $star_counts[$s]
        : count(array_filter($semuaUlasan, static fn($u) => (int) ($u['rating'] ?? 0) === $s));
}

$bulanId = [1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
$fmtTgl  = static function (?string $d) use ($bulanId): string {
    $t = $d ? strtotime($d) : false;
    return $t ? date('j', $t) . ' ' . $bulanId[(int) date('n', $t)] . ' ' . date('Y', $t) . ' · ' . date('H.i', $t) : '-';
};
$bintangHtml = static function (int $n, string $cls = 'text-sm'): string {
    $o = '<span class="inline-flex gap-0.5 ' . $cls . '" role="img" aria-label="' . $n . ' dari 5 bintang">';
    for ($i = 1; $i <= 5; $i++) {
        $o .= '<span aria-hidden="true" class="' . ($i <= $n ? 'text-amber-400' : 'text-slate-200') . '">★</span>';
    }
    return $o . '</span>';
};
?>

<style>
    /* Status aktif untuk filter (diatur oleh JavaScript di bawah) */
    .ulasan-chip.is-active { background: var(--brand); border-color: var(--brand); color: #fff; }
    .ulasan-row.is-active  { background: #eff6ff; }
    .ulasan-opt .ck { display: none; }
    .ulasan-opt.is-active .ck { display: inline-flex; }

    #cartDrawer.open { display: flex !important; }
    #cartOverlay.open { display: block !important; }
    .detail-stepper { display: flex; align-items: center; }
</style>

<!-- Ganti warna utama di sini (selaras dengan halaman detail barang) -->
<div id="ulasan-app" data-batch="<?= (int) $batch ?>"
     class="max-w-6xl mx-auto py-6 px-4 sm:px-6 space-y-5" style="--brand:#0B2E6B">

    <!-- ================= NAV ATAS ================= -->
    <nav class="flex items-center justify-between text-sm" aria-label="Navigasi halaman">
        <a href="<?= $detailUrl ?>"
           class="inline-flex items-center gap-2 min-h-[44px] font-semibold text-[var(--brand)] hover:opacity-80 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Detail Barang
        </a>
        <a href="<?= $katalogUrl ?>" class="min-h-[44px] inline-flex items-center text-slate-500 hover:text-slate-800 transition">
            Katalog Permintaan &rarr;
        </a>
    </nav>

    <!-- ================= HEADER PRODUK ================= -->
    <header class="bg-white rounded-[20px] border border-slate-200 px-5 py-5 sm:px-6 flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-5">
        <div class="w-[72px] h-[72px] rounded-2xl bg-slate-100 flex items-center justify-center flex-shrink-0 overflow-hidden">
            <?php if ($has_foto): ?>
                <img src="<?= $foto_url ?>" alt="<?= htmlspecialchars($namaBarang) ?>" class="w-full h-full object-contain p-1.5">
            <?php else: ?>
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.4" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10L4 17V7"/>
                </svg>
            <?php endif; ?>
        </div>

        <div class="min-w-0 flex-1">
            <div class="flex flex-wrap items-center gap-2">
                <span class="font-mono text-xs font-semibold px-2.5 py-0.5 rounded-full bg-blue-50 text-[var(--brand)]">
                    <?= htmlspecialchars($barang->kode_barang ?? '-') ?>
                </span>
                <?php if ($kategoriLbl !== ''): ?>
                    <span class="text-xs font-medium px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600">
                        <?= htmlspecialchars($kategoriLbl) ?>
                    </span>
                <?php endif; ?>
                <?php $totalDisetujui = (int) ($total_disetujui ?? ($total_diminta ?? 0)); ?>
                <?php if ($totalDisetujui > 0): ?>
                    <span class="text-xs font-medium px-2.5 py-0.5 rounded-full bg-blue-50 text-[var(--brand)]">
                        Diminta <?= number_format($totalDisetujui, 0, ',', '.') ?>×
                    </span>
                <?php endif; ?>
            </div>
            <h1 class="mt-2 text-xl sm:text-2xl font-extrabold tracking-tight text-slate-900 leading-tight">
                <?= htmlspecialchars($namaBarang) ?>
            </h1>
            <?php if (!empty($barang->kategori_deskripsi)): ?>
                <p class="mt-0.5 text-sm text-slate-500"><?= htmlspecialchars($barang->kategori_deskripsi) ?></p>
            <?php endif; ?>
        </div>

        <!-- Aksi + Keranjang & Stepper -->
        <div id="detailCartControl" class="flex-shrink-0">
            <button type="button" id="detailAddButton" onclick="cartAddFirst(<?= (int) ($barang->id ?? 0) ?>)"
                    class="inline-flex items-center justify-center gap-2 h-12 px-6 rounded-full bg-[var(--brand)] text-white text-sm font-semibold hover:opacity-90 active:scale-[.98] transition whitespace-nowrap shadow-md shadow-blue-900/20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--brand)] cursor-pointer">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H5l1-8h12"/>
                </svg>
                <span>+ Keranjang</span>
            </button>
            <div id="detailStepper" class="detail-stepper h-12 border-2 border-blue-900 bg-blue-50/50 rounded-full overflow-hidden items-center" style="display:none">
                <button type="button" onclick="detailStep(-1)" class="w-10 h-full font-black text-blue-900 hover:bg-blue-100 transition flex items-center justify-center text-lg cursor-pointer" aria-label="Kurangi jumlah">−</button>
                <input id="detailQty" type="number" value="1" min="1" onchange="detailInput(this.value)" class="w-12 h-full text-center text-sm font-extrabold text-blue-950 bg-white border-x-2 border-blue-900 focus:outline-none" aria-label="Jumlah barang">
                <button type="button" onclick="detailStep(1)" class="w-10 h-full font-black text-blue-900 hover:bg-blue-100 transition flex items-center justify-center text-lg cursor-pointer" aria-label="Tambah jumlah">+</button>
            </div>
        </div>
    </header>

    <!-- ================= RINGKASAN + DAFTAR ================= -->
    <div class="grid gap-5 lg:grid-cols-[320px_minmax(0,1fr)] items-start">

        <!-- Ringkasan penilaian (sticky di desktop) -->
        <aside class="bg-white rounded-[20px] border border-slate-200 p-6 lg:sticky lg:top-4" aria-label="Ringkasan penilaian">
            <p class="text-xs font-bold uppercase tracking-[.12em] text-slate-500 mb-3">Ringkasan penilaian</p>

            <div class="flex items-end gap-2">
                <span class="text-6xl font-semibold tracking-tighter leading-none text-slate-900">
                    <?= number_format($ratingAvg, 1, ',', '.') ?>
                </span>
                <span class="text-lg text-slate-400 pb-1.5">/ 5</span>
            </div>
            <div class="mt-2"><?= $bintangHtml((int) round($ratingAvg), 'text-base tracking-wider') ?></div>
            <p class="mt-1.5 mb-4 text-sm text-slate-500">
                <?= $ratingCount ?> penilaian<?php if (!empty($ulasan_count)): ?> · <?= (int) $ulasan_count ?> ulasan<?php endif; ?>
            </p>

            <!-- Baris distribusi = filter bintang (klik lagi untuk menghapus filter) -->
            <div class="space-y-0.5">
                <?php for ($s = 5; $s >= 1; $s--):
                    $c   = $cnt[$s];
                    $pct = $ratingCount > 0 ? round(($c / $ratingCount) * 100) : 0;
                ?>
                    <button type="button" data-filter="<?= $s ?>" data-toggle="1" aria-pressed="false"
                            aria-label="Tampilkan ulasan bintang <?= $s ?> (<?= $c ?>)"
                            class="ulasan-row w-full flex items-center gap-2.5 min-h-[36px] px-1.5 rounded-lg text-[13px] font-medium text-left hover:bg-slate-50 transition disabled:opacity-60 disabled:cursor-default disabled:hover:bg-transparent focus-visible:outline focus-visible:outline-2 focus-visible:outline-[var(--brand)]"
                            <?= $c === 0 ? 'disabled' : '' ?>>
                        <span class="w-8 flex items-center gap-1 font-semibold text-slate-800">
                            <?= $s ?><span class="text-amber-400" aria-hidden="true">★</span>
                        </span>
                        <span class="flex-1 h-2 rounded-full bg-slate-100 overflow-hidden">
                            <span class="block h-full rounded-full bg-[var(--brand)] transition-all duration-300" style="width: <?= $pct ?>%"></span>
                        </span>
                        <span class="w-4 text-right text-slate-500 tabular-nums"><?= $c ?></span>
                    </button>
                <?php endfor; ?>
            </div>
        </aside>

        <!-- Daftar ulasan -->
        <section id="daftar-ulasan" class="min-w-0" aria-label="Daftar ulasan">

            <?php if ($totalUlasan === 0): ?>
                <!-- Kosong: belum ada ulasan sama sekali -->
                <div class="bg-white rounded-[20px] border border-slate-200 px-6 py-14 text-center space-y-2">
                    <div class="w-14 h-14 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a8 8 0 01-11.6 7.1L4 20l1-4.4A8 8 0 1121 12z"/>
                        </svg>
                    </div>
                    <p class="text-base font-bold text-slate-800">Belum ada ulasan untuk barang ini</p>
                    <p class="text-sm text-slate-500 max-w-sm mx-auto leading-relaxed">
                        Pengguna yang telah menerima barang ini dapat memberi ulasan setelah tiket selesai.
                    </p>
                </div>

            <?php else: ?>
                <!-- Toolbar: chip filter + urutkan -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                    <div class="flex flex-wrap items-center gap-1.5 min-w-0 flex-1">
                        <?php
                        $chips = [[0, 'Semua', $totalUlasan]];
                        for ($s = 5; $s >= 1; $s--) { $chips[] = [$s, $s . ' ★', $cnt[$s]]; }
                        foreach ($chips as [$val, $label, $jml]):
                            $nonaktif = ($jml === 0 && $val !== 0);
                        ?>
                            <button type="button" data-filter="<?= $val ?>" aria-pressed="<?= $val === 0 ? 'true' : 'false' ?>"
                                    class="ulasan-chip inline-flex items-center h-9 px-3 rounded-full border border-slate-300 bg-white text-xs font-semibold text-slate-800 hover:bg-slate-50 transition disabled:bg-slate-50 disabled:border-slate-200 disabled:text-slate-400 disabled:cursor-not-allowed focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--brand)] <?= $val === 0 ? 'is-active' : '' ?>"
                                    <?= $nonaktif ? 'disabled' : '' ?>>
                                <?= $label ?> (<?= $jml ?>)
                            </button>
                        <?php endforeach; ?>
                    </div>

                    <!-- Dropdown urutkan (lebar tetap dan konsisten) -->
                    <div id="ulasan-dd" class="relative flex-shrink-0">
                        <div class="inline-flex items-stretch rounded-2xl border border-slate-200/90 bg-white shadow-sm overflow-hidden text-xs sm:text-sm">
                            <span class="px-3.5 py-2 text-slate-800 font-medium border-r border-slate-200 flex items-center select-none bg-slate-50/40 whitespace-nowrap">
                                Urutkan:
                            </span>
                            <button type="button" id="ulasan-dd-btn" aria-haspopup="listbox" aria-expanded="false"
                                    class="w-[170px] sm:w-[175px] px-3.5 py-2 flex items-center justify-between font-medium text-slate-900 hover:bg-slate-50 transition cursor-pointer focus:outline-none">
                                <span id="ulasan-dd-label" class="font-medium text-slate-900 truncate text-left">Terbaru</span>
                                <svg class="dropdown-chevron w-4 h-4 text-slate-600 transition-transform duration-200 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                        </div>
                        <ul id="ulasan-dd-list" role="listbox" aria-label="Urutkan ulasan"
                            class="hidden absolute right-0 top-full mt-1.5 z-40 w-full min-w-[210px] p-1.5 m-0 list-none bg-white border border-slate-200 rounded-2xl shadow-xl space-y-0.5">
                            <?php foreach (['terbaru' => 'Terbaru', 'tertinggi' => 'Bintang tertinggi', 'terendah' => 'Bintang terendah'] as $k => $lbl): ?>
                                <li role="presentation">
                                    <button type="button" role="option" data-sort="<?= $k ?>" aria-selected="<?= $k === 'terbaru' ? 'true' : 'false' ?>"
                                            class="ulasan-opt w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-medium text-slate-900 text-left hover:bg-slate-50 transition cursor-pointer focus:outline-none <?= $k === 'terbaru' ? 'is-active' : '' ?>">
                                        <span><?= $lbl ?></span>
                                        <span class="ck text-slate-900 ml-3" aria-hidden="true">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </span>
                                    </button>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <!-- Semua ulasan dirender; JavaScript yang menyaring, mengurutkan, dan memotong per batch -->
                <div id="ulasan-list">
                    <?php foreach ($semuaUlasan as $u):
                        $rStar        = (int) ($u['rating'] ?? 0);
                        $namaPengulas = !empty($u['pelapor']) ? $u['pelapor'] : (!empty($u['user_name']) ? $u['user_name'] : 'Pengguna');
                        $initial      = strtoupper(mb_substr($namaPengulas, 0, 1));
                        $komentar     = trim((string) ($u['komentar_item'] ?? ''));
                        $ts           = strtotime($u['created_at'] ?? '') ?: 0;
                    ?>
                    <article class="ulasan-item bg-white rounded-[20px] border border-slate-200 p-5 sm:px-6 mb-3"
                             data-rating="<?= $rStar ?>" data-ts="<?= $ts ?>">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-full bg-[var(--brand)] text-white text-sm font-bold flex items-center justify-center flex-shrink-0">
                                <?= htmlspecialchars($initial) ?>
                            </span>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-slate-900 truncate"><?= htmlspecialchars($namaPengulas) ?></p>
                                <div class="mt-0.5"><?= $bintangHtml($rStar, 'text-[13px]') ?></div>
                            </div>
                            <span class="ml-auto text-xs text-slate-500 whitespace-nowrap"><?= $fmtTgl($u['created_at'] ?? null) ?></span>
                        </div>

                        <?php if ($komentar !== ''): ?>
                            <p class="mt-3 text-sm text-slate-700 leading-relaxed"><?= nl2br(htmlspecialchars($komentar)) ?></p>
                        <?php else: ?>
                            <p class="mt-3 text-[13px] text-slate-400">Hanya memberi penilaian, tanpa catatan ulasan.</p>
                        <?php endif; ?>

                        <?php if (!empty($u['kode_tiket'])): ?>
                            <span class="inline-flex mt-3.5 font-mono text-[11px] text-slate-500 bg-slate-100 rounded-md px-2 py-0.5">
                                <?= htmlspecialchars($u['kode_tiket']) ?>
                            </span>
                        <?php endif; ?>
                    </article>
                    <?php endforeach; ?>
                </div>

                <div class="flex items-center justify-between gap-3 mt-2 text-[13px] text-slate-500">
                    <span aria-live="polite">Menampilkan <span id="ulasan-shown"><?= min($batch, $totalUlasan) ?></span> dari <span id="ulasan-total"><?= $totalUlasan ?></span> ulasan</span>
                    <button type="button" id="ulasan-more"
                            class="h-11 px-5 rounded-xl border border-slate-300 bg-white text-[13px] font-semibold text-[var(--brand)] hover:bg-slate-50 disabled:text-slate-400 disabled:bg-slate-50 disabled:cursor-not-allowed transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-[var(--brand)]">
                        Muat lebih banyak
                    </button>
                </div>
            <?php endif; ?>
        </section>
    </div>
</div>

<!-- ================= FLOATING CART ================= -->
<div class="fixed bottom-6 right-6 z-40">
    <button id="cartBtn" type="button" onclick="toggleCart(true)" title="Buka Keranjang Permintaan" aria-label="Buka Keranjang Permintaan"
            class="relative flex h-14 w-14 items-center justify-center rounded-full border-2 border-white bg-gradient-to-tr from-[#0B2E6B] to-[#1d4ed8] text-white shadow-[0_10px_25px_rgba(11,46,107,0.4)] transition hover:scale-105 active:scale-95 cursor-pointer">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-2.3 4.6a1 1 0 00.9 1.4H19M9 22a1 1 0 100-2 1 1 0 000 2zm8 0a1 1 0 100-2 1 1 0 000 2z"/>
        </svg>
        <span id="cartBadge" class="absolute -right-1 -top-1 hidden min-w-[1.4rem] h-5 items-center justify-center rounded-full border-2 border-white bg-amber-400 px-1.5 text-[10px] font-black text-blue-950">0</span>
    </button>
</div>
<div id="cartOverlay" class="fixed inset-0 z-40 hidden bg-slate-900/50 backdrop-blur-sm" onclick="toggleCart(false)"></div>
<aside id="cartDrawer" class="fixed right-0 top-0 z-50 hidden h-full w-full max-w-md flex-col bg-white shadow-2xl">
    <div class="flex items-center justify-between border-b border-gray-100 bg-gradient-to-r from-blue-950 to-blue-800 px-6 py-4">
        <div><h2 class="text-lg font-extrabold text-white">Keranjang Kebutuhan</h2><p class="text-xs text-blue-100">Tanpa bayar — hanya menandai barang diminta</p></div>
        <button type="button" onclick="toggleCart(false)" class="h-9 w-9 rounded-full bg-white/20 font-bold text-white hover:bg-white/30 transition cursor-pointer">✕</button>
    </div>
    <div class="flex items-center justify-between border-b border-gray-100 px-6 py-3">
        <span id="cartTotalLabel" class="text-sm font-bold text-gray-700">0 jenis • 0 item</span>
        <button type="button" onclick="cartClear()" class="text-xs font-bold text-red-600 hover:text-red-700 transition cursor-pointer">Kosongkan</button>
    </div>
    <div id="cartItems" class="flex-1 space-y-3 overflow-y-auto px-6 py-4"></div>
    <form method="post" action="<?= base_url('user_ticket/checkout_persediaan') ?>" onsubmit="return validCheckout()" class="space-y-3 border-t border-gray-100 bg-slate-50 px-6 py-4">
        <div>
            <label class="text-xs font-bold text-gray-700">Keperluan <span class="text-red-500">*</span></label>
            <input name="keperluan" id="fKeperluan" type="text" placeholder="cth: Kebutuhan ATK bagian umum" class="mt-1 h-12 w-full rounded-xl border-2 border-gray-200 px-4 text-sm font-medium text-gray-800 focus:outline-none focus:border-blue-800 focus:ring-4 focus:ring-blue-100 transition-all">
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
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
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
                <input name="tanggal_permintaan" id="fTanggalButuh" type="date" min="<?= date('Y-m-d') ?>" class="mt-1 h-12 w-full rounded-xl border-2 border-gray-200 px-4 text-sm font-medium text-gray-800 focus:outline-none focus:border-blue-800 focus:ring-4 focus:ring-blue-100 transition-all">
            </div>
        </div>
        <button type="submit" class="h-14 w-full rounded-xl bg-blue-900 text-sm font-extrabold text-white hover:bg-blue-950 transition shadow-lg shadow-blue-900/25 active:scale-[.99]">
            Buat Permintaan • Tandai Kebutuhan
        </button>
        <button type="button" onclick="toggleCart(false)" class="h-12 w-full rounded-xl border-2 border-gray-200 bg-white text-sm font-bold text-slate-600 hover:border-gray-300 transition">Batal</button>
    </form>
</aside>

<script>
/* Interaksi halaman ulasan: filter bintang, urutkan, muat lebih banyak.
   State disimpan di URL (?bintang=5&urut=tertinggi) tanpa memuat ulang halaman. */
(function () {
    var app = document.getElementById('ulasan-app');
    var list = document.getElementById('ulasan-list');
    if (!app || !list) return;

    var BATCH   = parseInt(app.getAttribute('data-batch'), 10) || 10;
    var items   = Array.prototype.slice.call(list.querySelectorAll('.ulasan-item'));
    var filters = Array.prototype.slice.call(app.querySelectorAll('[data-filter]'));
    var dd      = document.getElementById('ulasan-dd');
    var ddBtn   = document.getElementById('ulasan-dd-btn');
    var ddList  = document.getElementById('ulasan-dd-list');
    var ddLabel = document.getElementById('ulasan-dd-label');
    var ddOpts  = ddList ? Array.prototype.slice.call(ddList.querySelectorAll('[data-sort]')) : [];
    var LABELS  = { terbaru: 'Terbaru', tertinggi: 'Bintang tertinggi', terendah: 'Bintang terendah' };
    var more    = document.getElementById('ulasan-more');
    var shownEl = document.getElementById('ulasan-shown');
    var totalEl = document.getElementById('ulasan-total');
    var SORTS   = ['terbaru', 'tertinggi', 'terendah'];

    // Keadaan awal dari URL
    var params = new URLSearchParams(window.location.search);
    var state = {
        bintang: Math.min(5, Math.max(0, parseInt(params.get('bintang'), 10) || 0)),
        urut: SORTS.indexOf(params.get('urut')) > -1 ? params.get('urut') : 'terbaru',
        limit: BATCH
    };
    // Abaikan filter bintang yang jumlahnya 0
    if (state.bintang > 0 && !items.some(function (el) { return +el.dataset.rating === state.bintang; })) {
        state.bintang = 0;
    }

    function num(el, key) { return parseInt(el.dataset[key], 10) || 0; }

    function sortFn(a, b) {
        if (state.urut === 'tertinggi') return (num(b, 'rating') - num(a, 'rating')) || (num(b, 'ts') - num(a, 'ts'));
        if (state.urut === 'terendah')  return (num(a, 'rating') - num(b, 'rating')) || (num(b, 'ts') - num(a, 'ts'));
        return num(b, 'ts') - num(a, 'ts');
    }

    function render() {
        var cocok = items.filter(function (el) { return state.bintang === 0 || num(el, 'rating') === state.bintang; });
        cocok.sort(sortFn);

        // Susun ulang DOM: ulasan yang cocok dulu (sesuai urutan), sisanya disembunyikan
        var frag = document.createDocumentFragment();
        cocok.forEach(function (el, i) {
            el.classList.toggle('hidden', i >= state.limit);
            frag.appendChild(el);
        });
        items.forEach(function (el) {
            if (cocok.indexOf(el) === -1) { el.classList.add('hidden'); frag.appendChild(el); }
        });
        list.appendChild(frag);

        var tampil = Math.min(state.limit, cocok.length);
        if (shownEl) shownEl.textContent = tampil;
        if (totalEl) totalEl.textContent = cocok.length;
        if (more) more.disabled = tampil >= cocok.length;
        if (ddLabel) ddLabel.textContent = LABELS[state.urut];
        ddOpts.forEach(function (o) {
            var on = o.getAttribute('data-sort') === state.urut;
            o.classList.toggle('is-active', on);
            o.setAttribute('aria-selected', on ? 'true' : 'false');
        });

        filters.forEach(function (btn) {
            var aktif = parseInt(btn.getAttribute('data-filter'), 10) === state.bintang;
            btn.classList.toggle('is-active', aktif);
            btn.setAttribute('aria-pressed', aktif ? 'true' : 'false');
        });

        // Sinkronkan URL tanpa reload
        var q = new URLSearchParams();
        if (state.bintang > 0)        q.set('bintang', state.bintang);
        if (state.urut !== 'terbaru') q.set('urut', state.urut);
        var qs = q.toString();
        try {
            history.replaceState(null, '', window.location.pathname + (qs ? '?' + qs : '') + window.location.hash);
        } catch (err) { /* abaikan: URL tidak wajib diperbarui */ }
    }

    filters.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var val = parseInt(btn.getAttribute('data-filter'), 10);
            // Baris distribusi bersifat toggle: klik lagi pada yang aktif = hapus filter
            state.bintang = (btn.hasAttribute('data-toggle') && state.bintang === val) ? 0 : val;
            state.limit = BATCH;
            render();
        });
    });

    // ----- Dropdown urutkan -----
    function setMenu(open, fokusTombol) {
        if (!ddBtn || !ddList) return;
        ddList.classList.toggle('hidden', !open);
        ddBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
        var chevron = dd.querySelector('.dropdown-chevron');
        if (chevron) chevron.classList.toggle('rotate-180', open);
        if (open) {
            var cur = ddOpts.filter(function (o) { return o.getAttribute('data-sort') === state.urut; })[0] || ddOpts[0];
            if (cur) cur.focus();
        } else if (fokusTombol) {
            ddBtn.focus();
        }
    }

    if (ddBtn && ddList) {
        ddBtn.addEventListener('click', function () {
            setMenu(ddList.classList.contains('hidden'));
        });

        ddOpts.forEach(function (opt) {
            opt.addEventListener('click', function () {
                state.urut = opt.getAttribute('data-sort');
                state.limit = BATCH;
                setMenu(false, true);
                render();
            });
        });

        // Klik di luar dropdown = tutup
        document.addEventListener('click', function (e) {
            if (dd && !dd.contains(e.target)) setMenu(false);
        });

        // Keyboard: panah atas/bawah pindah opsi, Esc/Tab menutup
        ddList.addEventListener('keydown', function (e) {
            var i = ddOpts.indexOf(document.activeElement);
            if (e.key === 'ArrowDown') { e.preventDefault(); ddOpts[(i + 1) % ddOpts.length].focus(); }
            else if (e.key === 'ArrowUp') { e.preventDefault(); ddOpts[(i - 1 + ddOpts.length) % ddOpts.length].focus(); }
            else if (e.key === 'Escape') { setMenu(false, true); }
            else if (e.key === 'Tab') { setMenu(false); }
        });
    }

    if (more) more.addEventListener('click', function () {
        state.limit += BATCH;
        render();
    });

    render();
})();

var cartData = { count: 0, total_qty: 0, items: [] };
var detailBarangId = <?= (int) ($barang->id ?? 0) ?>;

function postJSON(url, body) {
    var formData = new FormData();
    Object.keys(body).forEach(function (key) { formData.append(key, body[key]); });
    return fetch(url, { method: 'POST', body: formData }).then(function (response) { return response.json(); });
}

function escapeHtml(value) {
    return String(value || '').replace(/[&<>"']/g, function (char) {
        return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[char];
    });
}

function refreshCart() {
    fetch("<?= base_url('user_ticket/cart_count') ?>")
        .then(function (response) { return response.json(); })
        .then(function (data) {
            cartData = data;
            var badge = document.getElementById('cartBadge');
            var total = data.total_qty || 0;
            if (badge) {
                badge.textContent = total;
                badge.classList.toggle('hidden', total < 1);
                badge.classList.toggle('flex', total > 0);
            }
            var label = document.getElementById('cartTotalLabel');
            if (label) label.textContent = (data.count || 0) + ' jenis • ' + total + ' item';
            renderCartItems(data.items || []);
            syncDetailStepper(data.items || []);
        });
}

function renderCartItems(items) {
    var container = document.getElementById('cartItems');
    if (!container) return;
    if (!items.length) {
        container.innerHTML = '<div class="py-10 text-center"><div class="mb-3 text-5xl">🛒</div><p class="font-bold text-gray-700">Keranjang masih kosong</p><p class="text-sm text-gray-500">Pilih barang lalu tekan + Keranjang.</p></div>';
        return;
    }
    container.innerHTML = items.map(function (item) {
        return '<div class="space-y-2 rounded-2xl border border-gray-100 p-4 shadow-sm">'
            + '<div class="flex items-start justify-between gap-2"><div><p class="font-mono text-[11px] text-slate-400">' + escapeHtml(item.kode) + '</p>'
            + '<p class="font-bold text-sm leading-snug text-gray-800">' + escapeHtml(item.nama || '') + '</p></div>'
            + '<button type="button" onclick="cartRemove(' + Number(item.barang_id) + ')" class="text-lg leading-none text-red-500 hover:text-red-700 transition">×</button></div>'
            + '<div class="flex items-center gap-2"><button type="button" onclick="cartSetQty(' + Number(item.barang_id) + ',' + (item.qty - 1) + ')" class="h-8 w-8 rounded-lg bg-slate-100 hover:bg-slate-200 font-extrabold text-slate-700 transition">−</button>'
            + '<span class="min-w-[3rem] text-center text-sm font-extrabold text-slate-900">' + Number(item.qty) + ' ' + escapeHtml(item.satuan || '') + '</span>'
            + '<button type="button" onclick="cartSetQty(' + Number(item.barang_id) + ',' + (item.qty + 1) + ')" class="h-8 w-8 rounded-lg bg-slate-100 hover:bg-slate-200 font-extrabold text-slate-700 transition">+</button></div></div>';
    }).join('');
}

function syncDetailStepper(items) {
    var quantity = 0;
    (items || []).forEach(function (item) {
        if (String(item.barang_id) === String(detailBarangId)) quantity = parseInt(item.qty, 10) || 0;
    });
    var addBtn = document.getElementById('detailAddButton');
    var stepper = document.getElementById('detailStepper');
    var qtyInp = document.getElementById('detailQty');
    if (addBtn) addBtn.style.display = quantity > 0 ? 'none' : 'inline-flex';
    if (stepper) stepper.style.display = quantity > 0 ? 'flex' : 'none';
    if (qtyInp) qtyInp.value = quantity > 0 ? quantity : 1;
}

function cartAddFirst(id) {
    postJSON("<?= base_url('user_ticket/cart_add') ?>", { barang_id: id, jumlah: 1 })
        .then(function (response) {
            if (!response.success) throw new Error(response.message || 'Barang tidak dapat ditambahkan.');
            refreshCart();
            Swal.fire({ icon: 'success', title: 'Masuk keranjang', text: response.message, timer: 1400, showConfirmButton: false });
        })
        .catch(function (error) {
            Swal.fire({ icon: 'warning', title: 'Tidak bisa ditambah', text: error.message || 'Terjadi kesalahan.' });
        });
}

function detailStep(delta) {
    var input = document.getElementById('detailQty');
    var current = parseInt(input ? input.value : '1', 10) || 1;
    detailSetQty(current + delta);
}

function detailInput(value) {
    var quantity = parseInt(value, 10);
    detailSetQty(isNaN(quantity) ? 0 : quantity);
}

function detailSetQty(quantity) {
    if (quantity <= 0) {
        cartRemove(detailBarangId);
        return;
    }
    postJSON("<?= base_url('user_ticket/cart_update') ?>", { barang_id: detailBarangId, jumlah: quantity })
        .then(function (response) {
            if (!response.success) throw new Error(response.message || 'Jumlah tidak dapat diubah.');
            refreshCart();
        })
        .catch(function (error) {
            refreshCart();
            Swal.fire({ icon: 'warning', title: 'Tidak bisa diubah', text: error.message || 'Terjadi kesalahan.' });
        });
}

function cartSetQty(id, quantity) {
    if (quantity <= 0) return cartRemove(id);
    postJSON("<?= base_url('user_ticket/cart_update') ?>", { barang_id: id, jumlah: quantity }).then(function () { refreshCart(); });
}

function cartRemove(id) {
    postJSON("<?= base_url('user_ticket/cart_remove') ?>", { barang_id: id }).then(function () { refreshCart(); });
}

function cartClear() {
    if (!cartData.count) return;
    Swal.fire({ title: 'Kosongkan keranjang?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', confirmButtonText: 'Ya', cancelButtonText: 'Batal' })
        .then(function (result) {
            if (result.isConfirmed) postJSON("<?= base_url('user_ticket/cart_clear') ?>", {}).then(function () { refreshCart(); });
        });
}

function toggleCart(open) {
    var drawer = document.getElementById('cartDrawer');
    var overlay = document.getElementById('cartOverlay');
    if (drawer) drawer.classList.toggle('open', !!open);
    if (overlay) overlay.classList.toggle('open', !!open);
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
    var dateElement = document.getElementById('fTanggalButuh');
    if (dateElement && dateElement.value) {
        var now = new Date();
        var today = now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0') + '-' + String(now.getDate()).padStart(2, '0');
        if (dateElement.value < today) {
            Swal.fire({ icon: 'warning', title: 'Tanggal tidak valid', text: 'Tanggal permintaan tidak boleh mundur.' });
            return false;
        }
    }
    return true;
}

// Searchable Unit Kerja Dropdown inside cart
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
</script>