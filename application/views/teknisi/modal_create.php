<div class="relative">
    <div class="tek-header border-b pb-3 mb-4">
        <h3 class="tek-modal-title text-lg font-bold">Tambah Teknisi</h3>
        <p class="tek-muted text-xs mt-1">Pilih user dan tentukan bidang keahlian</p>
    </div>

    <form method="post" action="<?= base_url('teknisi/create') ?>" class="space-y-3">

        <div>
            <label class="tek-label block text-sm mb-1.5">
                Pilih User <span class="text-red-500">*</span>
            </label>
            <div class="tek-select-wrap">
                <select name="user_id" required class="tek-select w-full">
                    <?php if (empty($users)): ?>
                        <option value="">Semua user sudah terdaftar sebagai teknisi</option>
                    <?php else: ?>
                        <option value="">-- Pilih User --</option>
                        <?php foreach ($users as $u): ?>
                            <option value="<?= $u['id'] ?>"><?= $u['name'] ?> (<?= $u['email'] ?>)</option>
                        <?php endforeach ?>
                    <?php endif ?>
                </select>
                <span class="tek-chevron">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </span>
            </div>
            <?php if (!empty($users)): ?>
                <p class="tek-muted text-xs mt-1.5">User yang dipilih akan menjadi teknisi</p>
            <?php endif ?>
        </div>

        <div>
            <label class="tek-label block text-sm mb-1.5">
                Bidang Keahlian <span class="text-red-500">*</span>
            </label>
            <input type="text" name="keahlian" required
                placeholder="Contoh: Hardware, Software, Networking"
                class="tek-input w-full px-3 py-2 rounded-lg text-sm focus:outline-none">
            <p class="tek-muted text-xs mt-1.5">Pisahkan dengan koma jika lebih dari satu bidang</p>
        </div>

        <div>
            <label class="tek-label block text-sm mb-1.5">Status Ketersediaan</label>
            <div class="grid grid-cols-3 gap-2">
                <label class="relative cursor-pointer">
                    <input type="radio" name="status" value="Available" checked class="peer sr-only">
                    <div class="tek-radio-opt px-3 py-2 rounded-lg border text-center text-sm transition-all peer-checked:border-green-500 peer-checked:bg-green-500/10 peer-checked:text-green-500">
                        <div class="flex items-center justify-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-green-500"></span>Available
                        </div>
                    </div>
                </label>
                <label class="relative cursor-pointer">
                    <input type="radio" name="status" value="Busy" class="peer sr-only">
                    <div class="tek-radio-opt px-3 py-2 rounded-lg border text-center text-sm transition-all peer-checked:border-yellow-500 peer-checked:bg-yellow-500/10 peer-checked:text-yellow-500">
                        <div class="flex items-center justify-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-yellow-500"></span>Busy
                        </div>
                    </div>
                </label>
                <label class="relative cursor-pointer">
                    <input type="radio" name="status" value="Offline" class="peer sr-only">
                    <div class="tek-radio-opt px-3 py-2 rounded-lg border text-center text-sm transition-all peer-checked:border-gray-500 peer-checked:bg-gray-500/10 peer-checked:text-gray-400">
                        <div class="flex items-center justify-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-gray-500"></span>Offline
                        </div>
                    </div>
                </label>
            </div>
        </div>

        <div class="tek-footer flex justify-end gap-2 pt-3 border-t">
            <button type="button" onclick="closeModal()"
                class="tek-btn-cancel px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                Batal
            </button>
            <button type="submit" <?= empty($users) ? 'disabled' : '' ?>
                class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                Simpan Teknisi
            </button>
        </div>
    </form>
</div>

<style>
html.light .tek-header   { border-color: #e2e8f0 !important; }
html.light .tek-modal-title { color: #0f172a !important; }
html.light .tek-label    { color: #374155 !important; }
html.light .tek-muted    { color: #94a3b8 !important; }
html.light .tek-footer   { border-color: #e2e8f0 !important; }
html.light .tek-radio-opt { border-color: #cbd5e1 !important; color: #475569 !important; }

/* dark default for radio */
.tek-radio-opt { border-color: #374151; color: #9ca3af; }
</style>