<?php
$barang = $barang ?? (object)[];
?>
<div class="max-w-5xl mx-auto mt-8 mb-24 space-y-6 px-4">

    <!-- BACK TO CATALOG -->
    <div>
        <a href="<?= base_url('user_ticket/permintaan_persediaan') ?>"
           class="inline-flex items-center gap-2 text-sm text-blue-900 hover:text-blue-950 font-bold transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Katalog Permintaan
        </a>
    </div>

    <!-- HEADER / DETAIL BARANG CARD -->
    <div class="bg-white rounded-3xl p-6 md:p-8 shadow-xl border border-gray-100 flex flex-col md:flex-row gap-6 items-start md:items-center justify-between">
        <div class="space-y-3 flex-1">
            <div class="flex flex-wrap items-center gap-2">
                <span class="font-mono text-xs font-bold px-3 py-1 rounded-full bg-blue-100 text-blue-900">
                    <?= htmlspecialchars($barang->kode_barang ?? '-') ?>
                </span>
                <span class="text-xs font-semibold px-3 py-1 rounded-full bg-slate-100 text-slate-700">
                    <?= htmlspecialchars(($barang->kategori_kode ?? '') . (!empty($barang->kategori_nama) ? ' • ' . $barang->kategori_nama : '')) ?>
                </span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-[#0B2E6B] tracking-tight">
                <?= htmlspecialchars($barang->nama_barang ?? '-') ?>
            </h1>
            <?php if (!empty($barang->kategori_deskripsi)): ?>
                <p class="text-xs text-slate-500 leading-relaxed"><?= htmlspecialchars($barang->kategori_deskripsi) ?></p>
            <?php endif; ?>
        </div>

        <div class="flex flex-col gap-2 w-full md:w-auto flex-shrink-0">
            <a href="<?= base_url('user_ticket/permintaan_persediaan') ?>"
               class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-blue-900 hover:bg-blue-950 text-white font-extrabold text-sm transition-all shadow-lg shadow-blue-900/20 active:scale-95 text-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                Pesan di Katalog
            </a>
        </div>
    </div>

    <!-- RATING OVERVIEW & REVIEW BREAKDOWN -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- SCORE SUMMARY -->
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-xl border border-gray-100 flex flex-col items-center justify-center text-center">
            <p class="text-xs uppercase font-extrabold tracking-wider text-slate-500 mb-2">Penilaian Barang</p>
            <div class="text-5xl md:text-6xl font-black text-amber-500 mb-2">
                <?= number_format((float) ($rating_avg ?? 0), 1) ?>
            </div>
            <div class="flex gap-1 text-2xl text-amber-400 mb-2">
                <?php 
                $avgRound = round((float) ($rating_avg ?? 0));
                for ($i = 1; $i <= 5; $i++): 
                ?>
                    <span class="<?= $i <= $avgRound ? 'text-amber-400' : 'text-slate-200' ?>">★</span>
                <?php endfor; ?>
            </div>
            <p class="text-xs font-semibold text-slate-600">
                Dari <strong class="text-slate-800"><?= (int) ($rating_count ?? 0) ?></strong> penilaian
                <?php if (!empty($ulasan_count)): ?>
                    &bull; <strong class="text-slate-800"><?= (int) $ulasan_count ?></strong> ulasan
                <?php endif; ?>
            </p>
        </div>

        <!-- STAR DISTRIBUTION BARS -->
        <div class="md:col-span-2 bg-white rounded-3xl p-6 md:p-8 shadow-xl border border-gray-100 flex flex-col justify-center space-y-3">
            <p class="text-xs uppercase font-extrabold tracking-wider text-slate-500 mb-1">Distribusi Bintang</p>
            <?php 
            $tot = max(1, (int) ($rating_count ?? 0));
            for ($s = 5; $s >= 1; $s--): 
                $c = (int) ($star_counts[$s] ?? 0);
                $pct = round(($c / $tot) * 100);
            ?>
            <div class="flex items-center gap-3 text-xs font-medium">
                <span class="w-12 font-bold text-slate-700 flex items-center gap-1">
                    <?= $s ?> <span class="text-amber-400 text-sm">★</span>
                </span>
                <div class="flex-1 h-3 rounded-full bg-slate-100 overflow-hidden">
                    <div class="h-full bg-amber-400 rounded-full transition-all duration-500" style="width: <?= $pct ?>%"></div>
                </div>
                <span class="w-12 text-right text-slate-500 font-semibold"><?= $c ?></span>
            </div>
            <?php endfor; ?>
        </div>
    </div>

    <!-- LIST OF REVIEWS -->
    <div class="bg-white rounded-3xl p-6 md:p-8 shadow-xl border border-gray-100 space-y-6">
        <div class="flex items-center justify-between border-b border-gray-100 pb-4">
            <div class="flex items-center gap-2">
                <h2 class="text-lg font-bold text-slate-800">Daftar Ulasan &amp; Penilaian</h2>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-blue-100 text-blue-900">
                    <?= count($ulasan_list ?? []) ?>
                </span>
            </div>
        </div>

        <?php if (empty($ulasan_list)): ?>
        <div class="py-12 text-center space-y-3">
            <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto text-2xl text-slate-400">
                💬
            </div>
            <p class="text-base font-bold text-slate-700">Belum ada ulasan untuk barang ini</p>
            <p class="text-xs text-slate-500 max-w-md mx-auto leading-relaxed">
                Pengguna yang telah menerima barang ini dapat memberikan penilaian dan ulasan di riwayat tiket mereka setelah status tiket selesai.
            </p>
        </div>
        <?php else: ?>
        <div class="space-y-4">
            <?php foreach (($ulasan_list ?? []) as $u): ?>
            <?php 
                $rStar = (int) ($u['rating'] ?? 0);
                $namaPengulas = !empty($u['pelapor']) ? $u['pelapor'] : (!empty($u['user_name']) ? $u['user_name'] : 'Pengguna');
                $initial = strtoupper(substr($namaPengulas, 0, 1));
                $komentar = trim((string) ($u['komentar_item'] ?? ''));
            ?>
            <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-5 space-y-3 hover:bg-slate-50 transition">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-900 text-white font-extrabold text-sm flex items-center justify-center flex-shrink-0">
                            <?= $initial ?>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800 leading-tight"><?= htmlspecialchars($namaPengulas) ?></p>
                            <p class="text-[11px] text-slate-400 mt-0.5">
                                <?= !empty($u['created_at']) ? date('d M Y • H:i', strtotime($u['created_at'])) : '-' ?>
                                <?php if (!empty($u['kode_tiket'])): ?>
                                    &bull; <span class="font-mono text-slate-500"><?= htmlspecialchars($u['kode_tiket']) ?></span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-0.5 text-base text-amber-400 flex-shrink-0">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span class="<?= $i <= $rStar ? 'text-amber-400' : 'text-slate-200' ?>">★</span>
                        <?php endfor; ?>
                    </div>
                </div>

                <?php if ($komentar !== ''): ?>
                <div class="bg-white rounded-xl p-3.5 border border-slate-200 text-xs md:text-sm text-slate-700 leading-relaxed font-normal">
                    <?= nl2br(htmlspecialchars($komentar)) ?>
                </div>
                <?php else: ?>
                <p class="text-xs italic text-slate-400">Pengguna memberikan penilaian <?= $rStar ?> bintang tanpa catatan ulasan.</p>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

</div>
