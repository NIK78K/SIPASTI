<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mx-auto px-4 py-6">
    
    <!-- BREADCRUMB -->
    <div class="mb-4 text-sm text-gray-400">
        <a href="<?= base_url('inventory') ?>" class="hover:text-blue-400">Inventory</a>
        <span class="mx-2">/</span>
        <span class="text-gray-300">Import Data</span>
    </div>

    <!-- CARD UTAMA - CENTERED -->
    <div class="max-w-4xl mx-auto">
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-2xl p-8 border border-gray-700">
            
            <!-- HEADER -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-full mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Import Data Excel BMN</h2>
                <p class="text-gray-400">Upload file Excel untuk import data perangkat secara massal</p>
            </div>

            <!-- DOWNLOAD TEMPLATE -->
            <div class="bg-blue-900/30 border border-blue-700/50 rounded-lg p-6 mb-6">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-white mb-1">Langkah 1: Download Template Excel</h3>
                        <p class="text-sm text-gray-400 mb-4">
                            Gunakan template ini untuk memastikan format data sesuai dengan sistem
                        </p>
                        <a href="<?= base_url('inventory/download_template') ?>" 
                           class="inline-flex items-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 transition font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Download Template Excel
                        </a>
                    </div>
                </div>
            </div>

            <!-- FORM UPLOAD -->
            <div class="bg-gray-800/50 border-2 border-dashed border-gray-600 rounded-lg p-8 mb-6 hover:border-blue-500 transition">
                <div class="flex items-start gap-4 mb-6">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-white mb-1">Langkah 2: Upload File Excel</h3>
                        <p class="text-sm text-gray-400">Pilih file Excel (.xlsx) yang sudah diisi</p>
                    </div>
                </div>
                
                <form method="post" action="<?= base_url('inventory/import_process') ?>" enctype="multipart/form-data" id="importForm">
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-300 mb-3">
                            Pilih File Excel (.xlsx)
                        </label>
                        <div class="relative">
                            <input type="file" 
                                   name="file_excel" 
                                   accept=".xlsx,.xls"
                                   required
                                   class="block w-full text-sm text-gray-300 border border-gray-600 rounded-lg cursor-pointer bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-3 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 file:cursor-pointer"
                                   id="file_input"
                                   onchange="showFileName(this)">
                        </div>
                        <p class="mt-3 text-xs text-gray-400 flex items-center gap-2" id="file-name">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            File yang dipilih akan muncul di sini
                        </p>
                    </div>

                    <div class="flex gap-3 justify-center">
                        <button type="submit" 
                                class="flex items-center gap-2 bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            Import Sekarang
                        </button>

                        <a href="<?= base_url('inventory') ?>" 
                           class="flex items-center gap-2 bg-gray-600 text-white px-8 py-3 rounded-lg hover:bg-gray-700 transition font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Batal
                        </a>
                    </div>
                </form>
            </div>

            <!-- PETUNJUK -->
            <div class="bg-amber-900/20 border border-amber-700/50 rounded-lg p-6">
                <h3 class="font-semibold text-amber-400 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Petunjuk Import:
                </h3>
                <ul class="text-sm text-gray-300 space-y-2">
                    <li class="flex items-start gap-2">
                        <span class="text-green-400 mt-0.5">✓</span>
                        <span>Gunakan template yang sudah disediakan</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-green-400 mt-0.5">✓</span>
                        <span>Kolom <strong class="text-white">Kode Barang</strong> dan <strong class="text-white">NUP</strong> wajib diisi</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-green-400 mt-0.5">✓</span>
                        <span>Format tanggal: <code class="bg-gray-700 px-2 py-0.5 rounded text-blue-400">YYYY-MM-DD</code> atau <code class="bg-gray-700 px-2 py-0.5 rounded text-blue-400">DD/MM/YYYY</code></span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-green-400 mt-0.5">✓</span>
                        <span>Kondisi harus: <strong class="text-white">Baik</strong>, <strong class="text-white">Rusak Ringan</strong>, atau <strong class="text-white">Rusak Berat</strong></span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-green-400 mt-0.5">✓</span>
                        <span>Data spesifikasi (kolom U-AB) bersifat opsional</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-amber-400 mt-0.5">⚠</span>
                        <span>Hapus baris contoh sebelum melakukan import</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-amber-400 mt-0.5">⚠</span>
                        <span>Data yang duplikat (Kode Barang + NUP sama) akan dilewati</span>
                    </li>
                </ul>
            </div>

        </div>
    </div>

</div>

<script>
// Show filename when selected
function showFileName(input) {
    const fileNameEl = document.getElementById('file-name');
    if (input.files && input.files[0]) {
        const fileName = input.files[0].name;
        const fileSize = (input.files[0].size / 1024).toFixed(2);
        fileNameEl.innerHTML = `
            <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-green-400 font-medium">${fileName}</span>
            <span class="text-gray-500">(${fileSize} KB)</span>
        `;
    }
}

// Form submit with SweetAlert loading
document.getElementById('importForm').addEventListener('submit', function(e) {
    Swal.fire({
        title: 'Memproses Import...',
        html: 'Mohon tunggu, sedang mengimport data Excel',
        allowOutsideClick: false,
        allowEscapeKey: false,
        background: '#1f2937',
        color: '#fff',
        didOpen: () => {
            Swal.showLoading();
        }
    });
});

// SweetAlert untuk flash message
<?php if ($this->session->flashdata('success')): ?>
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    html: '<?= $this->session->flashdata('success') ?>',
    background: '#1f2937',
    color: '#fff',
    confirmButtonColor: '#2563eb',
    confirmButtonText: 'OK'
});
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
Swal.fire({
    icon: 'error',
    title: 'Gagal!',
    html: '<?= $this->session->flashdata('error') ?>',
    background: '#1f2937',
    color: '#fff',
    confirmButtonColor: '#dc2626',
    confirmButtonText: 'OK'
});
<?php endif; ?>

<?php if ($this->session->flashdata('errors')): ?>
let errorHtml = '<div class="text-left"><p class="mb-2 font-semibold">Detail Error:</p><ul class="list-disc list-inside space-y-1 text-sm">';
<?php foreach ($this->session->flashdata('errors') as $error): ?>
errorHtml += '<li class="text-yellow-300"><?= addslashes(htmlspecialchars($error)) ?></li>';
<?php endforeach; ?>
errorHtml += '</ul></div>';

Swal.fire({
    icon: 'warning',
    title: 'Ada Kesalahan',
    html: errorHtml,
    background: '#1f2937',
    color: '#fff',
    confirmButtonColor: '#f59e0b',
    confirmButtonText: 'OK',
    width: '600px'
});
<?php endif; ?>
</script>