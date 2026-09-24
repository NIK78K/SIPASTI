<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Barang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-slate-950">

<div class="container-fluid p-6">
    <div class="theme-card backdrop-blur-sm rounded-xl shadow-2xl p-6 mb-6 border">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold header-title">Master Barang</h1>
                <p class="text-sm header-subtitle mt-1">Pengelolaan kategori, nama barang, dan merk perangkat IT</p>
            </div>
            <button onclick="tambahBarang()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition shadow-lg font-medium transform hover:scale-105 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Tambah Barang
            </button>
        </div>

        <!-- Tabel Barang -->
        <div class="overflow-x-auto">
            <table id="tabelBarang" class="w-full" style="width: 100% !important;">
                <thead>
                    <tr class="bg-slate-900/90 header-subtitle border-b border-slate-800/50">
                        <th class="px-4 py-4 text-left text-sm font-semibold" style="width: 50px;">No</th>
                        <th class="px-4 py-4 text-left text-sm font-semibold" style="width: 120px;">Kategori</th>
                        <th class="px-4 py-4 text-left text-sm font-semibold" style="width: 180px;">Nama Barang</th>
                        <th class="px-4 py-4 text-left text-sm font-semibold">Merk</th>
                        <th class="px-4 py-4 text-left text-sm font-semibold" style="width: 120px;">Status</th>
                        <th class="px-4 py-4 text-left text-sm font-semibold" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($master_barang as $brg): ?>
                    <tr class="border-b border-slate-800/50 hover:bg-slate-800/50 transition-all duration-200">
                        <td class="px-4 py-4 header-subtitle"><?= $no++ ?></td>
                        <td class="px-4 py-4">
                            <span class="px-2.5 py-1 bg-purple-900/40 text-purple-400 rounded-full text-xs font-medium border border-purple-800/40 shadow-sm whitespace-nowrap">
                                <?= $brg->kategori ?>
                            </span>
                        </td>
                        <td class="px-4 py-4 header-title font-medium"><?= $brg->nama_barang ?></td>
                        <td class="px-4 py-4">
                            <?php 
                            if (!empty($brg->merk)) {
                                $merks = explode(', ', $brg->merk);
                                if (count($merks) > 1) {
                                    echo '<div class="space-y-1">';
                                    foreach ($merks as $idx => $m) {
                                        echo '<div class="header-subtitle text-sm flex items-start gap-2">';
                                        echo '<span class="text-blue-400 font-semibold min-w-[20px]">' . ($idx + 1) . '.</span>';
                                        echo '<span>' . htmlspecialchars($m) . '</span>';
                                        echo '</div>';
                                    }
                                    echo '</div>';
                                } else {
                                    echo '<div class="header-subtitle">' . htmlspecialchars($merks[0]) . '</div>';
                                }
                            } else {
                                echo '<span class="text-slate-500">-</span>';
                            }
                            ?>
                        </td>
                        <td class="px-4 py-4">
                            <?php if($brg->status == 'Aktif'): ?>
                                <button onclick="toggleStatus(<?= $brg->id ?>, '<?= $brg->status ?>')" 
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-emerald-500/10 text-emerald-400 text-xs font-medium rounded-full border border-emerald-500/30 hover:bg-emerald-500/20 transition-all duration-200 cursor-pointer whitespace-nowrap">
                                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span>
                                    Aktif
                                </button>
                            <?php else: ?>
                                <button onclick="toggleStatus(<?= $brg->id ?>, '<?= $brg->status ?>')" 
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-red-500/10 text-red-400 text-xs font-medium rounded-full border border-red-500/30 hover:bg-red-500/20 transition-all duration-200 cursor-pointer whitespace-nowrap">
                                    <span class="w-1.5 h-1.5 bg-red-400 rounded-full"></span>
                                    Tidak Aktif
                                </button>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex gap-1.5">
                                <button onclick="lihatMerk(<?= $brg->id ?>, '<?= addslashes($brg->nama_barang) ?>')" 
                                        class="p-2 bg-blue-900/40 hover:bg-blue-800/50 text-blue-400 rounded transition-all duration-200 shadow-sm border border-blue-800/30 transform hover:scale-110" 
                                        title="Kelola Merk">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                    </svg>
                                </button>
                                <a href="<?= base_url('barang/edit/' . $brg->id) ?>" 
                                   class="p-2 bg-amber-900/40 hover:bg-amber-800/50 text-amber-400 rounded transition-all duration-200 shadow-sm border border-amber-800/30 transform hover:scale-110" 
                                   title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <button onclick="hapusBarang(<?= $brg->id ?>, '<?= addslashes($brg->nama_barang) ?>')" 
                                        class="p-2 bg-red-900/40 hover:bg-red-800/50 text-red-400 rounded transition-all duration-200 shadow-sm border border-red-800/30 transform hover:scale-110" 
                                        title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Barang -->
<div id="modalBarang" class="hidden fixed inset-0 bg-black/50 z-50 flex items-start justify-center p-4 pt-20">
    <div class="theme-card rounded-lg shadow-xl w-full max-w-lg border" onclick="event.stopPropagation()">
        <div class="bg-blue-600 px-5 py-4 flex items-center justify-between">
            <h3 id="modalTitle" class="text-lg font-semibold text-white">Tambah Barang</h3>
            <button onclick="tutupModal()" class="text-white/80 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="formBarang" class="p-5 space-y-4">
            <input type="hidden" id="barang_id" name="id">
            
            <div>
                <label class="block text-sm font-medium header-subtitle mb-1.5">
                    Kategori <span class="text-red-400">*</span>
                </label>
                <select id="kategori" name="kategori" required
                        class="w-full px-3 py-2 bg-slate-800/80 border border-slate-700/50 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    <option value="" class="bg-slate-800 text-slate-400">-- Pilih Kategori --</option>
                    <?php foreach($kategori_list as $kat): ?>
                        <option value="<?= $kat ?>" class="bg-slate-800 text-white"><?= $kat ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium header-subtitle mb-1.5">
                    Nama Barang <span class="text-red-400">*</span>
                </label>
                <input type="text" id="nama_barang" name="nama_barang" required
                       class="w-full px-3 py-2 bg-slate-800/80 border border-slate-700/50 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-500 transition-all"
                       placeholder="Contoh: PC Workstation">
            </div>

            <div>
                <label class="block text-sm font-medium header-subtitle mb-1.5">
                    Merk <span class="text-red-400">*</span>
                </label>
                <input type="text" id="merk" name="merk" required
                       class="w-full px-3 py-2 bg-slate-800/80 border border-slate-700/50 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-500 transition-all"
                       placeholder="Contoh: HP Z4 G4 Workstation">
            </div>

            <div>
                <label class="block text-sm font-medium header-subtitle mb-1.5">Status</label>
                <select id="status" name="status" class="w-full px-3 py-2 bg-slate-800/80 border border-slate-700/50 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    <option value="Aktif" class="bg-slate-800 text-white">Aktif</option>
                    <option value="Tidak Aktif" class="bg-slate-800 text-white">Tidak Aktif</option>
                </select>
            </div>

            <div class="flex gap-2 pt-3 border-t border-slate-700">
                <button type="button" onclick="tutupModal()" 
                        class="flex-1 px-4 py-2 border border-slate-600 text-slate-300 rounded-lg hover:bg-slate-800 transition">
                    Batal
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition transform hover:scale-105 active:scale-95">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Kelola Merk -->
<div id="modalMerk" class="hidden fixed inset-0 bg-black/50 z-50 flex items-start justify-center p-4 pt-20">
    <div class="theme-card rounded-lg shadow-xl w-full max-w-2xl border max-h-[90vh] flex flex-col" onclick="event.stopPropagation()">
        <div class="bg-blue-600 px-5 py-4 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-white">Kelola Merk</h3>
                <p id="namaBarangMerk" class="text-sm text-blue-100 mt-1"></p>
            </div>
            <button onclick="tutupModalMerk()" class="text-white/80 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="p-5 overflow-y-auto flex-1">
            <div class="mb-4">
                <form id="formTambahMerk" class="flex gap-3">
                    <input type="hidden" id="master_barang_id_merk" name="master_barang_id">
                    <input type="text" id="nama_merk_baru" name="nama_merk" required
                           class="flex-1 px-3 py-2 bg-slate-800/80 border border-slate-700/50 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-500 transition-all"
                           placeholder="Masukkan nama merk baru...">
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition transform hover:scale-105 active:scale-95 whitespace-nowrap">
                        Tambah Merk
                    </button>
                </form>
            </div>

            <div id="listMerk" class="space-y-2">
                <!-- List merk akan dimuat di sini -->
            </div>
        </div>
    </div>
</div>

<style>
/* DataTables Styling - Dark Theme */
#tabelBarang_wrapper {
    color: #cbd5e1;
}

#tabelBarang_wrapper .dataTables_length,
#tabelBarang_wrapper .dataTables_filter {
    margin-bottom: 1.5rem;
}

#tabelBarang_wrapper .dataTables_length select,
#tabelBarang_wrapper .dataTables_filter input {
    background: rgba(30, 41, 59, 0.8);
    border: 1px solid rgba(71, 85, 105, 0.5);
    color: white;
    padding: 0.5rem 0.75rem;
    border-radius: 0.5rem;
    margin-left: 0.5rem;
    backdrop-filter: blur(4px);
}

#tabelBarang_wrapper .dataTables_filter input {
    min-width: 250px;
}

#tabelBarang_wrapper .dataTables_filter input:focus,
#tabelBarang_wrapper .dataTables_length select:focus {
    outline: none;
    border-color: rgba(59, 130, 246, 0.5);
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
}

#tabelBarang_wrapper .dataTables_length label,
#tabelBarang_wrapper .dataTables_filter label {
    color: #94a3b8;
    font-size: 0.875rem;
}

#tabelBarang_wrapper .dataTables_info {
    color: #64748b;
    padding-top: 1rem;
}

#tabelBarang_wrapper .dataTables_paginate {
    padding-top: 1rem;
}

#tabelBarang_wrapper .dataTables_paginate .paginate_button {
    color: #94a3b8 !important;
    background: rgba(30, 41, 59, 0.5) !important;
    border: 1px solid rgba(71, 85, 105, 0.4) !important;
    margin: 0 3px;
    padding: 0.5rem 0.875rem;
    border-radius: 0.5rem;
    transition: all 0.2s;
}

#tabelBarang_wrapper .dataTables_paginate .paginate_button:hover {
    color: white !important;
    background: rgba(30, 58, 138, 0.6) !important;
    border-color: rgba(59, 130, 246, 0.5) !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
}

#tabelBarang_wrapper .dataTables_paginate .paginate_button.current {
    color: white !important;
    background: #2563eb !important;
    border-color: #3b82f6 !important;
    box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.5);
}

#tabelBarang_wrapper .dataTables_paginate .paginate_button.disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

table.dataTable tbody tr {
    background-color: transparent;
}

table.dataTable tbody tr:hover {
    background: rgba(30, 41, 59, 0.4) !important;
}

select option {
    background-color: #1e293b;
    color: white;
}

/* Pastikan tabel responsif dengan lebar kolom yang fixed */
#tabelBarang {
    table-layout: auto;
}

#tabelBarang td {
    white-space: normal;
    vertical-align: middle;
}

/* Light mode overrides */
html.light #tabelBarang_wrapper .dataTables_length select,
html.light #tabelBarang_wrapper .dataTables_filter input {
    background: rgba(255, 255, 255, 0.95) !important;
    color: var(--text-primary) !important;
    border-color: rgba(203, 213, 225, 0.8) !important;
}

html.light table.dataTable tbody tr:hover {
    background: rgba(248, 250, 252, 0.6) !important;
}

html.light input,
html.light select,
html.light textarea {
    background: rgba(255, 255, 255, 0.95) !important;
    color: var(--text-primary) !important;
    border-color: rgba(203, 213, 225, 0.8) !important;
}
</style>

<script>
let currentBarangId = null;

// Helper untuk mendapatkan warna SweetAlert berdasarkan theme
function getSwalColors() {
    const isLight = document.documentElement.classList.contains('light');
    return {
        background: isLight ? '#ffffff' : '#1a1f2e',
        color: isLight ? '#0f172a' : '#f3f4f6'
    };
}

$(document).ready(function() {
    $('#tabelBarang').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            zeroRecords: "Data tidak ditemukan",
            emptyTable: "Tidak ada data tersedia",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        },
        pageLength: 15,
        lengthMenu: [[15, 25, 50, 100, -1], [15, 25, 50, 100, "Semua"]],
        order: [[1, 'asc']],
        columnDefs: [
            { orderable: false, targets: 5 },
            { width: "50px", targets: 0 },
            { width: "120px", targets: 1 },
            { width: "180px", targets: 2 },
            { width: "120px", targets: 4 },
            { width: "140px", targets: 5 }
        ],
        autoWidth: false
    });
});

function tambahBarang() {
    document.getElementById('modalTitle').textContent = 'Tambah Barang';
    document.getElementById('formBarang').reset();
    document.getElementById('barang_id').value = '';
    document.getElementById('status').value = 'Aktif';
    document.getElementById('modalBarang').classList.remove('hidden');
}

document.getElementById('formBarang').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    const colors = getSwalColors();
    Swal.fire({
        title: 'Menyimpan...',
        text: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        },
        ...colors
    });
    
    fetch('<?= base_url("barang/tambah") ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            const successColors = getSwalColors();
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                confirmButtonColor: '#2563eb',
                timer: 1500,
                ...successColors
            }).then(() => {
                location.reload();
            });
        } else {
            const errorColors = getSwalColors();
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: data.message,
                confirmButtonColor: '#ef4444',
                ...errorColors
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const errorColors = getSwalColors();
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Terjadi kesalahan saat menyimpan data',
            confirmButtonColor: '#ef4444',
            ...errorColors
        });
    });
});

function hapusBarang(id, nama) {
    const colors = getSwalColors();
    Swal.fire({
        title: 'Hapus Barang?',
        text: `Yakin ingin menghapus "${nama}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        reverseButtons: true,
        ...colors
    }).then((result) => {
        if (result.isConfirmed) {
            const loadColors = getSwalColors();
            Swal.fire({
                title: 'Menghapus...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                },
                ...loadColors
            });
            
            fetch('<?= base_url("barang/hapus/") ?>' + id, {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    const successColors = getSwalColors();
                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus!',
                        text: data.message,
                        confirmButtonColor: '#2563eb',
                        ...successColors
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    const errorColors = getSwalColors();
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message,
                        confirmButtonColor: '#ef4444',
                        ...errorColors
                    });
                }
            });
        }
    });
}

function toggleStatus(id, currentStatus) {
    const newStatus = currentStatus === 'Aktif' ? 'Tidak Aktif' : 'Aktif';
    
    const colors = getSwalColors();
    Swal.fire({
        title: 'Ubah Status?',
        text: `Ubah status menjadi ${newStatus}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Ubah',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#6b7280',
        reverseButtons: true,
        ...colors
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('<?= base_url("barang/toggle_status/") ?>' + id, {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    const successColors = getSwalColors();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        confirmButtonColor: '#2563eb',
                        timer: 1500,
                        ...successColors
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    const errorColors = getSwalColors();
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message,
                        confirmButtonColor: '#ef4444',
                        ...errorColors
                    });
                }
            });
        }
    });
}

function lihatMerk(id, namaBarang) {
    currentBarangId = id;
    document.getElementById('master_barang_id_merk').value = id;
    document.getElementById('namaBarangMerk').textContent = namaBarang;
    document.getElementById('modalMerk').classList.remove('hidden');
    loadMerkList(id);
}

function loadMerkList(id) {
    fetch('<?= base_url("barang/get_merk_by_barang/") ?>' + id)
        .then(response => response.json())
        .then(data => {
            const listMerk = document.getElementById('listMerk');
            
            if (data.length === 0) {
                listMerk.innerHTML = '<div class="text-center header-subtitle py-8 bg-slate-800/30 rounded-lg border border-slate-700/50">Belum ada merk untuk barang ini</div>';
            } else {
                listMerk.innerHTML = data.map((merk, index) => `
                    <div class="flex items-center justify-between p-4 bg-slate-800/50 rounded-lg border border-slate-700/50 hover:bg-slate-800 transition">
                        <div class="flex items-center gap-3">
                            <span class="text-blue-400 font-bold text-lg min-w-[30px]">${index + 1}.</span>
                            <span class="header-title font-medium">${merk.nama_merk}</span>
                        </div>
                        <button onclick="hapusMerk(${merk.id})" 
                                class="p-2 bg-red-900/40 hover:bg-red-800/50 text-red-400 rounded transition-all duration-200 shadow-sm border border-red-800/30 transform hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                `).join('');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('listMerk').innerHTML = '<div class="text-center text-red-400 py-8 bg-red-900/20 rounded-lg border border-red-800/50">Gagal memuat data merk</div>';
        });
}

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
            document.getElementById('nama_merk_baru').value = '';
            loadMerkList(currentBarangId);
            const successColors = getSwalColors();
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                confirmButtonColor: '#2563eb',
                timer: 1500,
                showConfirmButton: false,
                ...successColors
            });
        } else {
            const errorColors = getSwalColors();
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: data.message,
                confirmButtonColor: '#ef4444',
                ...errorColors
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const errorColors = getSwalColors();
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Terjadi kesalahan saat menambah merk',
            confirmButtonColor: '#ef4444',
            ...errorColors
        });
    });
});

function hapusMerk(id) {
    const colors = getSwalColors();
    Swal.fire({
        title: 'Hapus Merk?',
        text: 'Yakin ingin menghapus merk ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        reverseButtons: true,
        ...colors
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('<?= base_url("barang/hapus_merk/") ?>' + id, {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    loadMerkList(currentBarangId);
                    const successColors = getSwalColors();
                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus!',
                        text: data.message,
                        confirmButtonColor: '#2563eb',
                        timer: 1500,
                        showConfirmButton: false,
                        ...successColors
                    }).then(() => {
                        setTimeout(() => location.reload(), 500);
                    });
                } else {
                    const errorColors = getSwalColors();
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message,
                        confirmButtonColor: '#ef4444',
                        ...errorColors
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const errorColors = getSwalColors();
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat menghapus merk',
                    confirmButtonColor: '#ef4444',
                    ...errorColors
                });
            });
        }
    });
}

function tutupModal() {
    document.getElementById('modalBarang').classList.add('hidden');
}

function tutupModalMerk() {
    document.getElementById('modalMerk').classList.add('hidden');
    location.reload();
}

document.getElementById('modalBarang').addEventListener('click', function(e) {
    if (e.target === this) {
        tutupModal();
    }
});

document.getElementById('modalMerk').addEventListener('click', function(e) {
    if (e.target === this) {
        tutupModalMerk();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modalBarang = document.getElementById('modalBarang');
        const modalMerk = document.getElementById('modalMerk');
        
        if (!modalBarang.classList.contains('hidden')) {
            tutupModal();
        }
        if (!modalMerk.classList.contains('hidden')) {
            tutupModalMerk();
        }
    }
});
</script>

</body>
</html>