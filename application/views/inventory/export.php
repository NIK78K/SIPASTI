<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mx-auto px-4 py-6">
    
    <!-- BREADCRUMB -->
    <div class="mb-4 text-sm text-gray-400">
        <a href="<?= base_url('inventory') ?>" class="hover:text-blue-400">Inventory</a>
        <span class="mx-2">/</span>
        <span class="text-gray-300">Export Data</span>
    </div>

    <!-- CARD UTAMA - CENTERED -->
    <div class="max-w-5xl mx-auto">
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-2xl p-8 border border-gray-700">
            
            <!-- HEADER -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-600 rounded-full mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Export Data ke Excel</h2>
                <p class="text-gray-400">Pilih kolom yang ingin diexport ke file Excel</p>
            </div>

            <!-- FORM EXPORT -->
            <form method="post" 
                  action="<?= base_url('inventory/export_process') ?>"
                  id="exportForm">

                <!-- QUICK ACTIONS -->
                <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-4 mb-6">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-300 font-medium">Aksi Cepat:</span>
                        <div class="flex gap-2">
                            <button type="button" 
                                    onclick="selectAll()" 
                                    class="text-xs px-3 py-1.5 bg-blue-600 hover:bg-blue-700 rounded text-white transition">
                                ✓ Pilih Semua
                            </button>
                            <button type="button" 
                                    onclick="deselectAll()" 
                                    class="text-xs px-3 py-1.5 bg-gray-600 hover:bg-gray-700 rounded text-white transition">
                                ✗ Kosongkan
                            </button>
                            <button type="button" 
                                    onclick="selectEssential()" 
                                    class="text-xs px-3 py-1.5 bg-purple-600 hover:bg-purple-700 rounded text-white transition">
                                ⭐ Kolom Penting
                            </button>
                        </div>
                    </div>
                </div>

                <!-- COLUMN SELECTION -->
                <div class="bg-gray-800/30 border border-gray-700 rounded-lg p-6 mb-6">
                    <h3 class="font-semibold text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        Pilih Kolom Data
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        <?php
                        $columns = [
                            'jenis_bmn'          => ['Jenis BMN', 'essential'],
                            'kode_satker'        => ['Kode Satker', 'normal'],
                            'nama_satker'        => ['Nama Satker', 'normal'],
                            'kode_barang'        => ['Kode Barang', 'essential'],
                            'nup'                => ['NUP', 'essential'],
                            'barcode'            => ['Barcode', 'normal'],
                            'nama_barang'        => ['Nama Barang', 'essential'],
                            'merk'               => ['Merk', 'essential'],
                            'kondisi'            => ['Kondisi', 'essential'],
                            'henti_guna'         => ['Henti Guna', 'normal'],
                            'usul_hapus'         => ['Usul Hapus', 'normal'],
                            'tanggal_perolehan'  => ['Tanggal Perolehan', 'normal'],
                            'nilai_perolehan'    => ['Nilai Perolehan', 'normal'],
                            'lokasi'             => ['Lokasi', 'essential'],
                            'ruang'              => ['Ruang', 'essential'],
                            'serial_number'      => ['Serial Number', 'normal'],
                            'mac_address'        => ['MAC Address', 'normal'],
                            'pengguna'           => ['Pengguna', 'normal'],
                            'ruangan'            => ['Ruangan', 'normal'],
                            'transaksi_sakti'    => ['Transaksi SAKTI', 'normal'],
                            'keterangan'         => ['Keterangan', 'normal'],
                        ];
                        ?>

                        <?php foreach ($columns as $key => $data): ?>
                            <label class="flex items-center gap-3 p-3 bg-gray-700/30 hover:bg-gray-700/50 rounded-lg cursor-pointer transition group border border-gray-600/50 hover:border-emerald-500/50">
                                <input type="checkbox"
                                       name="columns[]"
                                       value="<?= $key ?>"
                                       data-type="<?= $data[1] ?>"
                                       checked
                                       class="w-4 h-4 accent-emerald-600 cursor-pointer">
                                <span class="text-sm text-gray-300 group-hover:text-white transition">
                                    <?= $data[0] ?>
                                    <?php if ($data[1] === 'essential'): ?>
                                        <span class="text-xs text-emerald-400">⭐</span>
                                    <?php endif; ?>
                                </span>
                            </label>
                        <?php endforeach ?>
                    </div>

                    <!-- COUNTER -->
                    <div class="mt-4 pt-4 border-t border-gray-700">
                        <p class="text-sm text-gray-400">
                            <span class="font-semibold text-emerald-400" id="selectedCount">21</span> kolom dipilih
                        </p>
                    </div>
                </div>

                <!-- INFO BOX -->
                <div class="bg-blue-900/20 border border-blue-700/50 rounded-lg p-4 mb-6">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-sm text-gray-300">
                            <p class="font-semibold text-blue-300 mb-1">Informasi Export:</p>
                            <ul class="space-y-1 text-gray-400">
                                <li>• File akan didownload dalam format .xlsx (Excel)</li>
                                <li>• Semua data inventory akan diexport sesuai kolom yang dipilih</li>
                                <li>• Kolom dengan tanda ⭐ adalah kolom penting yang direkomendasikan</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- BUTTONS -->
                <div class="flex gap-3 justify-center">
                    <button type="submit" 
                            class="flex items-center gap-2 bg-emerald-600 text-white px-8 py-3 rounded-lg hover:bg-emerald-700 transition font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Export Excel
                    </button>

                    <a href="<?= base_url('inventory') ?>" 
                       class="flex items-center gap-2 bg-gray-600 text-white px-8 py-3 rounded-lg hover:bg-gray-700 transition font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali
                    </a>
                </div>
            </form>

        </div>
    </div>

</div>

<script>
// Update counter
function updateCounter() {
    const checked = document.querySelectorAll('input[name="columns[]"]:checked').length;
    document.getElementById('selectedCount').textContent = checked;
}

// Select all
function selectAll() {
    document.querySelectorAll('input[name="columns[]"]').forEach(cb => {
        cb.checked = true;
    });
    updateCounter();
}

// Deselect all
function deselectAll() {
    document.querySelectorAll('input[name="columns[]"]').forEach(cb => {
        cb.checked = false;
    });
    updateCounter();
}

// Select essential columns only
function selectEssential() {
    document.querySelectorAll('input[name="columns[]"]').forEach(cb => {
        cb.checked = cb.dataset.type === 'essential';
    });
    updateCounter();
}

// Listen to checkbox changes
document.querySelectorAll('input[name="columns[]"]').forEach(cb => {
    cb.addEventListener('change', updateCounter);
});

// Form submit validation
document.getElementById('exportForm').addEventListener('submit', function(e) {
    const checked = document.querySelectorAll('input[name="columns[]"]:checked').length;
    
    if (checked === 0) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Pilih Minimal 1 Kolom',
            text: 'Anda harus memilih minimal 1 kolom untuk diexport',
            background: '#1f2937',
            color: '#fff',
            confirmButtonColor: '#f59e0b',
            confirmButtonText: 'OK'
        });
        return false;
    }

    // Show loading
    Swal.fire({
        title: 'Memproses Export...',
        html: `Sedang mengexport <b>${checked}</b> kolom data ke Excel`,
        allowOutsideClick: false,
        allowEscapeKey: false,
        background: '#1f2937',
        color: '#fff',
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Close after 2 seconds (file will download)
    setTimeout(() => {
        Swal.close();
        Swal.fire({
            icon: 'success',
            title: 'Export Berhasil!',
            text: 'File Excel sedang didownload',
            background: '#1f2937',
            color: '#fff',
            confirmButtonColor: '#10b981',
            confirmButtonText: 'OK',
            timer: 2000,
            timerProgressBar: true
        });
    }, 2000);
});

// Initial counter
updateCounter();
</script>

<style>
/* Custom checkbox hover effect */
label:has(input[type="checkbox"]) {
    transition: all 0.2s ease;
}

label:has(input[type="checkbox"]:checked) {
    background: rgba(16, 185, 129, 0.1);
    border-color: rgba(16, 185, 129, 0.3);
}
</style>