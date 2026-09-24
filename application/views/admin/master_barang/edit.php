<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-slate-950">

<div class="container mx-auto p-6 max-w-4xl">
    <!-- Header -->
    <div class="mb-6">
        <a href="<?= base_url('barang') ?>" class="inline-flex items-center gap-2 text-blue-400 hover:text-blue-300 transition mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Master Barang
        </a>
        <h1 class="text-3xl font-bold text-white">Edit Barang</h1>
        <p class="text-sm text-slate-400 mt-1">Ubah data kategori, nama barang, dan kelola merk</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Form Edit Barang -->
        <div class="bg-slate-900/50 backdrop-blur-sm rounded-xl shadow-2xl p-6 border border-slate-800/50">
            <h2 class="text-xl font-bold text-white mb-4 pb-3 border-b border-slate-700">Data Barang</h2>
            <form id="formEditBarang" class="space-y-4">
                <input type="hidden" id="barang_id" name="id" value="<?= $barang->id ?>">
                
                <!-- Kategori -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Kategori <span class="text-red-400">*</span>
                    </label>
                    <select id="kategori" name="kategori" required
                            class="w-full px-4 py-3 bg-slate-800 border border-slate-700 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="" class="bg-slate-800 text-slate-400">-- Pilih Kategori --</option>
                        <?php foreach($kategori_list as $kat): ?>
                            <option value="<?= $kat ?>" 
                                    class="bg-slate-800 text-white"
                                    <?= ($kat == $barang->kategori) ? 'selected' : '' ?>>
                                <?= $kat ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Nama Barang -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Nama Barang <span class="text-red-400">*</span>
                    </label>
                    <input type="text" id="nama_barang" name="nama_barang" required
                           value="<?= $barang->nama_barang ?>"
                           class="w-full px-4 py-3 bg-slate-800 border border-slate-700 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-500 transition"
                           placeholder="Contoh: PC Workstation">
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Status</label>
                    <select id="status" name="status" 
                            class="w-full px-4 py-3 bg-slate-800 border border-slate-700 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="Aktif" 
                                class="bg-slate-800 text-white"
                                <?= ($barang->status == 'Aktif') ? 'selected' : '' ?>>
                            Aktif
                        </option>
                        <option value="Tidak Aktif" 
                                class="bg-slate-800 text-white"
                                <?= ($barang->status == 'Tidak Aktif') ? 'selected' : '' ?>>
                            Tidak Aktif
                        </option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-4">
                    <a href="<?= base_url('barang') ?>" 
                       class="flex-1 px-4 py-3 border border-slate-700 text-slate-300 rounded-lg hover:bg-slate-800/50 transition font-medium text-center">
                        Batal
                    </a>
                    <button type="submit" 
                            class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition shadow-lg font-medium">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>

        <!-- Kelola Merk -->
        <div class="bg-slate-900/50 backdrop-blur-sm rounded-xl shadow-2xl p-6 border border-slate-800/50">
            <h2 class="text-xl font-bold text-white mb-4 pb-3 border-b border-slate-700">Kelola Merk</h2>
            
            <!-- Form Tambah Merk -->
            <form id="formTambahMerk" class="mb-4">
                <input type="hidden" name="master_barang_id" value="<?= $barang->id ?>">
                <div class="flex gap-2">
                    <input type="text" name="nama_merk" id="nama_merk_input" required
                           class="flex-1 px-4 py-3 bg-slate-800 border border-slate-700 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-500 transition text-sm"
                           placeholder="Tambah merk baru...">
                    <button type="submit" 
                            class="px-5 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg transition font-medium text-sm whitespace-nowrap">
                        + Tambah
                    </button>
                </div>
            </form>

            <!-- List Merk -->
            <div id="listMerk" class="space-y-2 max-h-[400px] overflow-y-auto">
                <!-- Merk akan dimuat di sini -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Merk -->
<div id="modalEditMerk" class="hidden fixed inset-0 bg-slate-900/90 backdrop-blur-md z-50 flex items-center justify-center">
    <div class="bg-slate-900 backdrop-blur-sm rounded-xl shadow-2xl w-full max-w-md mx-4 border border-slate-700/50">
        <div class="flex justify-between items-center p-5 border-b border-slate-700/50 bg-slate-800/50">
            <h3 class="text-lg font-bold text-white">Edit Merk</h3>
            <button onclick="tutupModalEditMerk()" class="text-slate-400 hover:text-slate-300 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="formEditMerkSubmit" class="p-5">
            <input type="hidden" id="edit_merk_id" name="id">
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-300 mb-2">Nama Merk</label>
                <input type="text" id="edit_nama_merk" name="nama_merk" required
                       class="w-full px-4 py-3 bg-slate-800 border border-slate-700 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-500 transition"
                       placeholder="Masukkan nama merk">
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="tutupModalEditMerk()" 
                        class="flex-1 px-4 py-2.5 border border-slate-700 text-slate-300 rounded-lg hover:bg-slate-800/50 transition font-medium">
                    Batal
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-medium">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Load merk saat halaman dimuat
$(document).ready(function() {
    loadMerkList();
});

// Submit form edit barang
document.getElementById('formEditBarang').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    Swal.fire({
        title: 'Menyimpan...',
        text: 'Mohon tunggu sebentar',
        background: '#0f172a',
        color: '#fff',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    fetch('<?= base_url("barang/update") ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                background: '#0f172a',
                color: '#fff',
                confirmButtonColor: '#2563eb',
                timer: 1500
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: data.message,
                background: '#0f172a',
                color: '#fff',
                confirmButtonColor: '#ef4444'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Terjadi kesalahan saat menyimpan data',
            background: '#0f172a',
            color: '#fff',
            confirmButtonColor: '#ef4444'
        });
    });
});

// Load daftar merk
function loadMerkList() {
    fetch('<?= base_url("barang/get_merk_by_barang/") . $barang->id ?>')
        .then(response => response.json())
        .then(data => {
            const listMerk = document.getElementById('listMerk');
            
            if (data.length === 0) {
                listMerk.innerHTML = `
                    <div class="text-center text-slate-400 py-8 bg-slate-800/30 rounded-lg border border-slate-700/50">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        Belum ada merk
                    </div>
                `;
            } else {
                listMerk.innerHTML = data.map((merk, index) => `
                    <div class="flex items-center justify-between p-3 bg-slate-800/50 rounded-lg border border-slate-700/50 hover:bg-slate-800 transition group">
                        <div class="flex items-center gap-3 flex-1">
                            <span class="text-blue-400 font-semibold text-sm min-w-[24px]">${index + 1}.</span>
                            <span class="text-white font-medium text-sm">${merk.nama_merk}</span>
                        </div>
                        <div class="flex gap-1">
                            <button onclick="editMerk(${merk.id}, '${merk.nama_merk.replace(/'/g, "\\'")}')" 
                                    class="p-1.5 bg-amber-900/40 hover:bg-amber-800/50 text-amber-400 rounded transition-all duration-200 opacity-0 group-hover:opacity-100"
                                    title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button onclick="hapusMerk(${merk.id}, '${merk.nama_merk.replace(/'/g, "\\'")}')" 
                                    class="p-1.5 bg-red-900/40 hover:bg-red-800/50 text-red-400 rounded transition-all duration-200"
                                    title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                `).join('');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('listMerk').innerHTML = `
                <div class="text-center text-red-400 py-8 bg-red-900/20 rounded-lg border border-red-800/50">
                    Gagal memuat data merk
                </div>
            `;
        });
}

// Tambah merk
document.getElementById('formTambahMerk').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('<?= base_url("barang/tambah_merk") ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            document.getElementById('nama_merk_input').value = '';
            loadMerkList();
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                background: '#0f172a',
                color: '#fff',
                confirmButtonColor: '#2563eb',
                timer: 1500,
                showConfirmButton: false
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: data.message,
                background: '#0f172a',
                color: '#fff',
                confirmButtonColor: '#ef4444'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Terjadi kesalahan saat menambah merk',
            background: '#0f172a',
            color: '#fff',
            confirmButtonColor: '#ef4444'
        });
    });
});

// Edit merk
function editMerk(id, namaMerk) {
    document.getElementById('edit_merk_id').value = id;
    document.getElementById('edit_nama_merk').value = namaMerk;
    document.getElementById('modalEditMerk').classList.remove('hidden');
}

// Submit edit merk
document.getElementById('formEditMerkSubmit').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('<?= base_url("barang/update_merk") ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            tutupModalEditMerk();
            loadMerkList();
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                background: '#0f172a',
                color: '#fff',
                confirmButtonColor: '#2563eb',
                timer: 1500,
                showConfirmButton: false
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: data.message,
                background: '#0f172a',
                color: '#fff',
                confirmButtonColor: '#ef4444'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Terjadi kesalahan saat mengupdate merk',
            background: '#0f172a',
            color: '#fff',
            confirmButtonColor: '#ef4444'
        });
    });
});

// Hapus merk
function hapusMerk(id, namaMerk) {
    Swal.fire({
        title: 'Hapus Merk?',
        text: `Yakin ingin menghapus merk "${namaMerk}"?`,
        icon: 'warning',
        background: '#0f172a',
        color: '#fff',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('<?= base_url("barang/hapus_merk/") ?>' + id, {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    loadMerkList();
                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus!',
                        text: data.message,
                        background: '#0f172a',
                        color: '#fff',
                        confirmButtonColor: '#2563eb',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message,
                        background: '#0f172a',
                        color: '#fff',
                        confirmButtonColor: '#ef4444'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat menghapus merk',
                    background: '#0f172a',
                    color: '#fff',
                    confirmButtonColor: '#ef4444'
                });
            });
        }
    });
}

function tutupModalEditMerk() {
    document.getElementById('modalEditMerk').classList.add('hidden');
    document.getElementById('formEditMerkSubmit').reset();
}

// Close modal dengan click outside
document.getElementById('modalEditMerk').addEventListener('click', function(e) {
    if (e.target === this) {
        tutupModalEditMerk();
    }
});

// Close modal dengan ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('modalEditMerk');
        if (!modal.classList.contains('hidden')) {
            tutupModalEditMerk();
        }
    }
});
</script>

</body>
</html>