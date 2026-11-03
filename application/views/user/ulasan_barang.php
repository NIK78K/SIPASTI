<?php
$barang       = $barang ?? (object)[];
$namaBarang   = $barang->nama_barang ?? '-';
$ratingAvg    = (float) ($rating_avg ?? 0);
$ratingCount  = (int) ($rating_count ?? 0);
$ulasanList   = $ulasan_list ?? [];
$katalogUrl   = base_url('user_ticket/permintaan_persediaan');
$detailBase   = base_url('user_ticket/ulasan_barang/');
$adaGambar    = !empty($barang->gambar) && file_exists(FCPATH . 'uploads/persediaan/' . $barang->gambar);
$kategoriNama = $barang->kategori_nama ?? ($barang->kategori_kode ?? '');
$satuan       = $barang->satuan ?? null;   // opsional: hapus baris ini jika tidak ada kolom satuan

// Bintang statis (n dari 5)
$renderStars = static function (int $n, string $class = 'text-base'): string {
    $out = '<span class="inline-flex gap-0.5 ' . $class . '" role="img" aria-label="' . $n . ' dari 5 bintang">';
    for ($i = 1; $i <= 5; $i++) {
        $out .= '<span aria-hidden="true" class="' . ($i <= $n ? 'text-amber-400' : 'text-slate-200') . '">★</span>';
    }
    return $out . '</span>';
};

// Info barang (pengganti blok "Shipping" di referensi)
$infoItems = [
    ['label' => 'Kode barang', 'value' => $barang->kode_barang ?? '-',
     'icon'  => 'M7 7h.01M7 3h5a2 2 0 011.41.59l7 7a2 2 0 010 2.82l-5 5a2 2 0 01-2.82 0l-7-7A2 2 0 015 10V5a2 2 0 012-2z'],
    ['label' => 'Kategori', 'value' => $kategoriNama !== '' ? $kategoriNama : '-',
     'icon'  => 'M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z'],
];
if (!empty($satuan)) {
    $infoItems[] = ['label' => 'Satuan', 'value' => $satuan,
     'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10L4 17V7'];
}
?>

<!-- Ganti warna utama di sini (mis. #111827 untuk hitam seperti referensi) -->
<div class="sp-detail max-w-6xl mx-auto mt-6 mb-24 px-4 space-y-16" style="--brand:#0B2E6B">

    <!-- ================= BREADCRUMB ================= -->
    <nav aria-label="Breadcrumb" class="flex items-center gap-3 text-xs text-slate-500">
        <a href="<?= $katalogUrl ?>" aria-label="Kembali ke katalog"
           class="inline-flex items-center justify-center w-8 h-8 rounded-full text-slate-700 hover:bg-slate-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-[var(--brand)] transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <ol class="flex items-center gap-2">
            <li><a href="<?= $katalogUrl ?>" class="hover:text-slate-800 transition">Katalog</a></li>
            <li aria-hidden="true" class="w-1 h-1 rounded-full bg-slate-300"></li>
            <li class="text-slate-700 font-medium" aria-current="page">Detail barang</li>
        </ol>
    </nav>

    <!-- ================= DETAIL: GAMBAR KIRI, INFO KANAN ================= -->
    <section class="grid gap-8 lg:grid-cols-2 lg:gap-10 items-start">

        <!-- Gambar -->
        <div class="rounded-[28px] bg-slate-100 overflow-hidden aspect-square lg:aspect-[4/4.2] flex items-center justify-center">
            <?php if ($adaGambar): ?>
                <img src="<?= base_url('uploads/persediaan/' . $barang->gambar) ?>"
                     alt="<?= htmlspecialchars($namaBarang) ?>"
                     class="w-full h-full object-contain p-8 md:p-12">
            <?php else: ?>
                <div class="flex flex-col items-center gap-3 text-slate-400">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10L4 17V7"/>
                    </svg>
                    <span class="text-sm">Foto belum tersedia</span>
                </div>
            <?php endif; ?>
        </div>

        <!-- Info -->
        <div class="space-y-5">
            <?php if ($kategoriNama !== ''): ?>
                <span class="inline-flex items-center px-4 py-1.5 rounded-full border border-slate-200 text-xs text-slate-700">
                    <?= htmlspecialchars($kategoriNama) ?>
                </span>
            <?php endif; ?>

            <div class="space-y-2">
                <h1 class="text-3xl md:text-4xl font-semibold tracking-tight text-slate-900 leading-tight">
                    <?= htmlspecialchars($namaBarang) ?>
                </h1>
                <div class="flex flex-wrap items-center gap-3 text-sm text-slate-600">
                    <span class="font-mono text-xs px-2.5 py-1 rounded-full bg-slate-100 text-slate-700">
                        <?= htmlspecialchars($barang->kode_barang ?? '-') ?>
                    </span>
                    <?php if ($ratingCount > 0): ?>
                        <a href="#ulasan" class="inline-flex items-center gap-1.5 hover:text-slate-900 transition">
                            <span class="text-amber-400" aria-hidden="true">★</span>
                            <span class="font-semibold text-slate-800"><?= number_format($ratingAvg, 1, ',', '.') ?></span>
                            <span class="text-slate-500">(<?= $ratingCount ?> penilaian)</span>
                        </a>
                    <?php else: ?>
                        <span class="text-slate-400">Belum ada penilaian</span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Tombol aksi -->
            <div id="detailCartControl">
                <button type="button" id="detailAddButton" onclick="cartAddFirst(<?= (int) ($barang->id ?? 0) ?>)"
                        class="flex items-center justify-center gap-2 w-full h-14 rounded-full bg-[var(--brand)] text-white text-sm font-semibold hover:opacity-90 active:scale-[.99] transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--brand)]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H5l1-8h12"/>
                    </svg>
                    <span>+ Keranjang</span>
                </button>
                <div id="detailStepper" class="detail-stepper" style="display:none">
                    <button type="button" onclick="detailStep(-1)" aria-label="Kurangi jumlah">−</button>
                    <input id="detailQty" type="number" value="1" min="1" onchange="detailInput(this.value)" aria-label="Jumlah barang">
                    <button type="button" onclick="detailStep(1)" aria-label="Tambah jumlah">+</button>
                </div>
            </div>

            <!-- Deskripsi -->
            <details open class="group rounded-2xl border border-slate-200 bg-white">
                <summary class="flex items-center justify-between cursor-pointer list-none px-5 py-4 text-base font-medium text-slate-900">
                    Deskripsi
                    <svg class="w-4 h-4 text-slate-500 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </summary>
                <div class="px-5 pb-5 -mt-1 text-sm text-slate-600 leading-relaxed">
                    <?php if (!empty($barang->kategori_deskripsi)): ?>
                        <?= htmlspecialchars($barang->kategori_deskripsi) ?>
                    <?php else: ?>
                        <span class="text-slate-400">Belum ada deskripsi untuk barang ini.</span>
                    <?php endif; ?>
                </div>
            </details>

            <!-- Informasi barang -->
            <details open class="group rounded-2xl border border-slate-200 bg-white">
                <summary class="flex items-center justify-between cursor-pointer list-none px-5 py-4 text-base font-medium text-slate-900">
                    Informasi barang
                    <svg class="w-4 h-4 text-slate-500 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </summary>
                <dl class="grid sm:grid-cols-2 gap-x-6 gap-y-5 px-5 pb-5">
                    <?php foreach ($infoItems as $it): ?>
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="<?= $it['icon'] ?>"/>
                                </svg>
                            </span>
                            <div class="min-w-0">
                                <dt class="text-[11px] text-slate-400"><?= $it['label'] ?></dt>
                                <dd class="text-sm font-medium text-slate-800 truncate"><?= htmlspecialchars((string) $it['value']) ?></dd>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </dl>
            </details>
        </div>
    </section>

    <!-- ================= RATING & ULASAN ================= -->
    <section id="ulasan" class="scroll-mt-24 space-y-8">
        <h2 class="text-2xl md:text-3xl font-semibold tracking-tight text-slate-900">Rating &amp; Ulasan</h2>

        <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_minmax(0,1.1fr)] items-center">

            <!-- Skor + distribusi -->
            <div class="flex flex-col sm:flex-row sm:items-center gap-6 sm:gap-8">
                <div class="flex-shrink-0">
                    <div class="flex items-end gap-2">
                        <span class="text-7xl md:text-8xl font-medium tracking-tighter text-slate-900 leading-none">
                            <?= number_format($ratingAvg, 1, ',', '.') ?>
                        </span>
                        <span class="text-xl text-slate-400 pb-2">/ 5</span>
                    </div>
                    <p class="mt-2 text-sm text-slate-500">
                        <?= $ratingCount ?> penilaian<?php if (!empty($ulasan_count)): ?> · <?= (int) $ulasan_count ?> ulasan<?php endif; ?>
                    </p>
                </div>

                <div class="flex-1 space-y-2.5">
                    <?php
                    $tot = max(1, $ratingCount);
                    for ($s = 5; $s >= 1; $s--):
                        $c   = (int) ($star_counts[$s] ?? 0);
                        $pct = round(($c / $tot) * 100);
                    ?>
                        <div class="flex items-center gap-3 text-sm">
                            <span class="w-9 flex items-center gap-1 font-medium text-slate-700">
                                <span class="text-amber-400" aria-hidden="true">★</span><?= $s ?>
                            </span>
                            <div class="flex-1 h-2 rounded-full bg-slate-100 overflow-hidden"
                                 role="img" aria-label="<?= $c ?> penilaian bintang <?= $s ?>">
                                <div class="h-full rounded-full <?= $c > 0 ? 'bg-[var(--brand)]' : 'bg-slate-300' ?>"
                                     style="width: <?= $pct ?>%; min-width: 8px"></div>
                            </div>
                            <span class="w-6 text-right text-slate-500 tabular-nums"><?= $c ?></span>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- Ulasan (carousel geser) -->
            <div class="relative">
                <?php if (empty($ulasanList)): ?>
                    <div class="rounded-2xl border border-slate-200 bg-white px-6 py-10 text-center space-y-2">
                        <p class="text-base font-semibold text-slate-800">Belum ada ulasan untuk barang ini</p>
                        <p class="text-sm text-slate-500 max-w-md mx-auto leading-relaxed">
                            Pengguna yang telah menerima barang ini dapat memberi penilaian di riwayat tiket setelah status tiket selesai.
                        </p>
                    </div>
                <?php else: ?>
                    <div id="ulasan-track"
                         class="flex gap-4 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-2 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                        <?php foreach ($ulasanList as $u): ?>
                            <?php
                            $rStar        = (int) ($u['rating'] ?? 0);
                            $namaPengulas = !empty($u['pelapor']) ? $u['pelapor'] : (!empty($u['user_name']) ? $u['user_name'] : 'Pengguna');
                            $initial      = strtoupper(substr($namaPengulas, 0, 1));
                            $komentar     = trim((string) ($u['komentar_item'] ?? ''));
                            ?>
                            <article class="snap-start flex-shrink-0 w-full rounded-2xl border border-slate-200 bg-white p-5 md:p-6 space-y-3 min-h-[11rem]">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="w-10 h-10 rounded-full bg-[var(--brand)] text-white text-sm font-bold flex items-center justify-center flex-shrink-0">
                                            <?= $initial ?>
                                        </span>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-slate-900 truncate"><?= htmlspecialchars($namaPengulas) ?></p>
                                            <?= $renderStars($rStar, 'text-sm') ?>
                                        </div>
                                    </div>
                                    <span class="text-xs text-slate-400 whitespace-nowrap">
                                        <?= !empty($u['created_at']) ? date('d M Y', strtotime($u['created_at'])) : '-' ?>
                                    </span>
                                </div>

                                <?php if ($komentar !== ''): ?>
                                    <p class="text-sm text-slate-600 leading-relaxed"><?= nl2br(htmlspecialchars($komentar)) ?></p>
                                <?php else: ?>
                                    <p class="text-sm text-slate-400">Hanya memberi penilaian, tanpa catatan ulasan.</p>
                                <?php endif; ?>

                                <?php if (!empty($u['kode_tiket'])): ?>
                                    <p class="font-mono text-[11px] text-slate-400"><?= htmlspecialchars($u['kode_tiket']) ?></p>
                                <?php endif; ?>
                            </article>
                        <?php endforeach; ?>
                    </div>

                    <?php if (count($ulasanList) > 1): ?>
                        <div class="flex items-center justify-between mt-3">
                            <span class="text-xs text-slate-400"><?= count($ulasanList) ?> ulasan</span>
                            <button type="button" id="ulasan-next" aria-label="Ulasan berikutnya"
                                    class="w-9 h-9 rounded-full border border-slate-200 bg-white text-slate-700 flex items-center justify-center hover:bg-slate-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-[var(--brand)] transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ================= BARANG TERKAIT (satu kategori) =================
         $barang_terkait dikirim controller: barang sekategori, selain barang ini,
         beserta rating_avg & rating_count hasil agregasi tabel ulasan_barang. -->
    <?php if (!empty($barang_terkait)): ?>
    <section class="space-y-8" aria-labelledby="judul-terkait">
        <div class="text-center space-y-3">
            <span class="block w-10 h-px bg-slate-300 mx-auto" aria-hidden="true"></span>
            <h2 id="judul-terkait" class="text-2xl md:text-3xl font-semibold tracking-tight text-slate-900">Barang terkait</h2>
            <?php if ($kategoriNama !== ''): ?>
                <p class="text-sm text-slate-500">Barang lain di kategori <?= htmlspecialchars($kategoriNama) ?></p>
            <?php endif; ?>
        </div>

        <div class="flex gap-4 overflow-x-auto snap-x snap-mandatory pb-2 md:grid md:grid-cols-4 md:gap-6 md:overflow-visible [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
            <?php foreach ($barang_terkait as $b): ?>
                <?php
                $bGambar = !empty($b->gambar) && file_exists(FCPATH . 'uploads/persediaan/' . $b->gambar);
                $bRating = (float) ($b->rating_avg ?? 0);
                $bCount  = (int) ($b->rating_count ?? 0);
                $bUrl    = $detailBase . ($b->id ?? '');
                ?>
                <a href="<?= $bUrl ?>"
                   class="group snap-start flex-shrink-0 w-[72%] sm:w-[44%] md:w-auto rounded-2xl border border-slate-200 bg-white overflow-hidden hover:border-slate-300 hover:shadow-md transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-[var(--brand)]">
                    <div class="aspect-[4/3] bg-slate-100 flex items-center justify-center">
                        <?php if ($bGambar): ?>
                            <img src="<?= base_url('uploads/persediaan/' . $b->gambar) ?>"
                                 alt="<?= htmlspecialchars($b->nama_barang ?? '') ?>"
                                 class="w-full h-full object-contain p-5 group-hover:scale-105 transition-transform duration-300">
                        <?php else: ?>
                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10L4 17V7"/>
                            </svg>
                        <?php endif; ?>
                    </div>
                    <div class="p-4 space-y-2">
                        <p class="text-sm font-medium text-slate-900 leading-snug line-clamp-2 min-h-[2.5rem]">
                            <?= htmlspecialchars($b->nama_barang ?? '-') ?>
                        </p>
                        <div class="flex items-center justify-between gap-2 text-xs">
                            <?php if ($bCount > 0): ?>
                                <span class="inline-flex items-center gap-1 text-slate-600">
                                    <span class="text-amber-400" aria-hidden="true">★</span>
                                    <span class="font-semibold text-slate-800"><?= number_format($bRating, 1, ',', '.') ?></span>
                                    <span class="text-slate-400">(<?= $bCount ?>)</span>
                                </span>
                            <?php else: ?>
                                <span class="text-slate-400">Belum ada ulasan</span>
                            <?php endif; ?>
                            <span class="font-mono text-slate-400"><?= htmlspecialchars($b->kode_barang ?? '') ?></span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

</div>

<div class="fixed bottom-6 right-6 z-40">
    <button id="cartBtn" type="button" onclick="toggleCart(true)" title="Buka Keranjang Permintaan" aria-label="Buka Keranjang Permintaan"
            class="relative flex h-14 w-14 items-center justify-center rounded-full border-2 border-white bg-gradient-to-tr from-[#0B2E6B] to-[#1d4ed8] text-white shadow-[0_10px_25px_rgba(11,46,107,0.4)] transition hover:scale-105 active:scale-95">
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
        <button type="button" onclick="toggleCart(false)" class="h-9 w-9 rounded-full bg-white/20 font-bold text-white">✕</button>
    </div>
    <div class="flex items-center justify-between border-b border-gray-100 px-6 py-3">
        <span id="cartTotalLabel" class="text-sm font-bold text-gray-700">0 jenis • 0 item</span>
        <button type="button" onclick="cartClear()" class="text-xs font-bold text-red-600">Kosongkan</button>
    </div>
    <div id="cartItems" class="flex-1 space-y-3 overflow-y-auto px-6 py-4"></div>
    <form method="post" action="<?= base_url('user_ticket/checkout_persediaan') ?>" onsubmit="return validCheckout()" class="space-y-3 border-t border-gray-100 bg-slate-50 px-6 py-4">
        <div>
            <label class="text-xs font-bold text-gray-700">Keperluan <span class="text-red-500">*</span></label>
            <input name="keperluan" id="fKeperluan" type="text" placeholder="cth: Kebutuhan ATK bagian umum" class="mt-1 h-12 w-full rounded-xl border-2 border-gray-200 px-4 text-sm font-medium text-gray-800">
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="text-xs font-bold text-gray-700">Unit kerja</label>
                <select name="unit_kerja"
                    class="mt-1 h-12 w-full rounded-xl border-2 border-gray-200 bg-white px-4 text-sm text-gray-800 font-medium transition-all">
                    <option value="">-- Pilih unit kerja --</option>
                    <?php foreach (($unit_kerja_list ?? []) as $uk): ?>
                        <option value="<?= htmlspecialchars($uk) ?>" <?= (string) ($unit_kerja_default ?? '') === (string) $uk ? 'selected' : '' ?>><?= htmlspecialchars($uk) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="text-xs font-bold text-gray-700">Tanggal permintaan</label>
                <input name="tanggal_permintaan" id="fTanggalButuh" type="date" min="<?= date('Y-m-d') ?>" class="mt-1 h-12 w-full rounded-xl border-2 border-gray-200 px-4 text-sm text-gray-800">
            </div>
        </div>
        <button type="submit" class="h-14 w-full rounded-xl bg-blue-900 text-sm font-extrabold text-white">Buat Permintaan • Tandai Kebutuhan</button>
        <button type="button" onclick="toggleCart(false)" class="h-12 w-full rounded-xl border-2 border-gray-200 bg-white text-sm font-bold text-slate-600">Batal</button>
    </form>
</aside>

<style>
#cartDrawer.open { display: flex; }
#cartOverlay.open { display: block; }
.detail-stepper { display: flex; align-items: center; width: 100%; height: 56px; overflow: hidden; border: 1px solid #bfdbfe; border-radius: 999px; background: #eff6ff; }
.detail-stepper button { width: 58px; height: 100%; flex: 0 0 58px; border: 0; background: transparent; color: #173f75; font-size: 22px; font-weight: 800; cursor: pointer; }
.detail-stepper button:hover { background: #dbeafe; }
.detail-stepper input { width: 100%; min-width: 0; height: 32px; border: 0; border-right: 1px solid #bfdbfe; border-left: 1px solid #bfdbfe; background: #fff; color: #173f75; font-size: 16px; font-weight: 850; text-align: center; }
.detail-stepper input::-webkit-outer-spin-button, .detail-stepper input::-webkit-inner-spin-button { appearance: none; margin: 0; }
.detail-stepper input[type=number] { appearance: textfield; }
</style>

<script>
(function () {
    var track = document.getElementById('ulasan-track');
    var next  = document.getElementById('ulasan-next');
    if (!track || !next) return;
    next.addEventListener('click', function () {
        var atEnd = track.scrollLeft + track.clientWidth >= track.scrollWidth - 4;
        track.scrollTo({ left: atEnd ? 0 : track.scrollLeft + track.clientWidth + 16, behavior: 'smooth' });
    });
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
            badge.textContent = total;
            badge.classList.toggle('hidden', total < 1);
            badge.classList.toggle('flex', total > 0);
            document.getElementById('cartTotalLabel').textContent = (data.count || 0) + ' jenis • ' + total + ' item';
            renderCartItems(data.items || []);
            syncDetailStepper(data.items || []);
        });
}

function renderCartItems(items) {
    var container = document.getElementById('cartItems');
    if (!items.length) {
        container.innerHTML = '<div class="py-10 text-center"><div class="mb-3 text-5xl">🛒</div><p class="font-bold text-gray-700">Keranjang masih kosong</p><p class="text-sm text-gray-500">Pilih barang lalu tekan + Keranjang.</p></div>';
        return;
    }
    container.innerHTML = items.map(function (item) {
        return '<div class="space-y-2 rounded-2xl border border-gray-100 p-4 shadow-sm">'
            + '<div class="flex items-start justify-between gap-2"><div><p class="font-mono text-[11px] text-slate-400">' + escapeHtml(item.kode) + '</p>'
            + '<p class="font-bold text-sm leading-snug text-gray-800">' + escapeHtml(item.nama || '') + '</p></div>'
            + '<button type="button" onclick="cartRemove(' + Number(item.barang_id) + ')" class="text-lg leading-none text-red-500">×</button></div>'
            + '<div class="flex items-center gap-2"><button type="button" onclick="cartSetQty(' + Number(item.barang_id) + ',' + (item.qty - 1) + ')" class="h-8 w-8 rounded-lg bg-slate-100 font-extrabold">−</button>'
            + '<span class="min-w-[3rem] text-center text-sm font-extrabold">' + Number(item.qty) + ' ' + escapeHtml(item.satuan || '') + '</span>'
            + '<button type="button" onclick="cartSetQty(' + Number(item.barang_id) + ',' + (item.qty + 1) + ')" class="h-8 w-8 rounded-lg bg-slate-100 font-extrabold">+</button></div></div>';
    }).join('');
}

function syncDetailStepper(items) {
    var quantity = 0;
    (items || []).forEach(function (item) {
        if (String(item.barang_id) === String(detailBarangId)) quantity = parseInt(item.qty, 10) || 0;
    });
    document.getElementById('detailAddButton').style.display = quantity > 0 ? 'none' : 'flex';
    document.getElementById('detailStepper').style.display = quantity > 0 ? 'flex' : 'none';
    document.getElementById('detailQty').value = quantity > 0 ? quantity : 1;
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
    var current = parseInt(document.getElementById('detailQty').value, 10) || 1;
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

refreshCart();
</script>