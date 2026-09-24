<div class="flex items-center justify-between mb-6">
    <div>
        <h3 class="text-xl font-bold text-white mb-1">Tiket Aktif</h3>
        <p class="text-gray-400 text-sm">Teknisi: <span class="text-blue-400 font-semibold"><?= $teknisi['name'] ?></span></p>
    </div>
    <button 
        onclick="closeTiketAktifModal()"
        class="text-gray-400 hover:text-white transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>

<?php if (empty($tiket)): ?>
    <div class="text-center py-12">
        <svg class="w-16 h-16 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <p class="text-gray-500">Tidak ada tiket aktif saat ini</p>
    </div>
<?php else: ?>
    <div class="space-y-3 max-h-[60vh] overflow-y-auto pr-2">
        <?php foreach ($tiket as $t): ?>
        <div class="bg-[#0b1220] border border-gray-800 rounded-lg overflow-hidden">
            <!-- HEADER CARD (CLICKABLE) -->
            <div onclick="toggleDetail(<?= $t['id'] ?>)" 
                 class="p-4 hover:bg-[#0f172a] transition-all cursor-pointer">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h4 class="font-bold text-white mb-1"><?= $t['kode_tiket'] ?></h4>
                        <p class="text-sm text-gray-400"><?= $t['pelapor'] ?></p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 rounded-full text-xs bg-yellow-600/20 text-yellow-400">
                            <?= $t['status'] ?>
                        </span>
                        <svg id="arrow-<?= $t['id'] ?>" class="w-5 h-5 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <p class="text-gray-500 mb-1">Kategori</p>
                        <p class="text-white"><?= $t['nama_kategori'] ?></p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">Lokasi</p>
                        <p class="text-white"><?= $t['lokasi'] ?: '-' ?></p>
                    </div>
                </div>
            </div>

            <!-- DETAIL (HIDDEN BY DEFAULT) -->
            <div id="detail-<?= $t['id'] ?>" class="hidden border-t border-gray-800 bg-[#0a0e14] p-4 space-y-4">
                
                <!-- SUB KATEGORI -->
                <div>
                    <p class="text-gray-500 text-sm mb-1">Sub Kategori</p>
                    <p class="text-white"><?= $t['nama_sub_kategori'] ?></p>
                </div>

                <!-- JENIS KERUSAKAN -->
                <div>
                    <p class="text-gray-500 text-sm mb-1">Jenis Kerusakan</p>
                    <div class="bg-[#0b1220] border border-gray-800 rounded-lg p-3">
                        <p class="text-white text-sm leading-relaxed"><?= nl2br(htmlspecialchars($t['jenis_kerusakan'])) ?></p>
                    </div>
                </div>

                <!-- WAKTU -->
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <p class="text-gray-500 mb-1">Dibuat</p>
                        <p class="text-white"><?= date('d M Y', strtotime($t['created_at'])) ?></p>
                        <p class="text-gray-400 text-xs"><?= date('H:i', strtotime($t['created_at'])) ?> WIB</p>
                    </div>
                    <?php if (!empty($t['selesai_at'])): ?>
                    <div>
                        <p class="text-gray-500 mb-1">Selesai</p>
                        <p class="text-white"><?= date('d M Y', strtotime($t['selesai_at'])) ?></p>
                        <p class="text-gray-400 text-xs"><?= date('H:i', strtotime($t['selesai_at'])) ?> WIB</p>
                    </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>