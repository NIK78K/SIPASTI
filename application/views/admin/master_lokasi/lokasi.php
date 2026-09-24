<div class="container-fluid">
    <div class="theme-card backdrop-blur-sm rounded-xl shadow-2xl p-6 mb-6 border">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold header-title">Master Lokasi</h1>
                <p class="text-sm header-subtitle mt-1">Pengelolaan lokasi/ruangan perangkat IT</p>
            </div>
            <button onclick="tambahLokasi()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition shadow-lg font-medium transform hover:scale-105 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Tambah Lokasi
            </button>
        </div>

        <!-- Tabel Lokasi -->
        <div class="overflow-x-auto">
            <table id="tabelLokasi" class="w-full" style="width: 100% !important;">
                <thead>
                    <tr class="bg-slate-900/90 header-subtitle border-b border-slate-800/50">
                        <th class="px-4 py-4 text-left text-sm font-semibold" style="width: 50px;">No</th>
                        <th class="px-4 py-4 text-left text-sm font-semibold" style="width: 120px;">Kode Lokasi</th>
                        <th class="px-4 py-4 text-left text-sm font-semibold">Nama Lokasi</th>
                        <th class="px-4 py-4 text-left text-sm font-semibold">Keterangan</th>
                        <th class="px-4 py-4 text-left text-sm font-semibold" style="width: 120px;">Status</th>
                        <th class="px-4 py-4 text-left text-sm font-semibold" style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($lokasi as $lok): ?>
                    <tr class="border-b border-slate-800/50 hover:bg-slate-800/50 transition-all duration-200">
                        <td class="px-4 py-4 header-subtitle"><?= $no++ ?></td>
                        <td class="px-4 py-4">
                            <span class="px-2.5 py-1 bg-blue-900/40 text-blue-400 rounded-full text-xs font-medium border border-blue-800/40 shadow-sm whitespace-nowrap">
                                <?= $lok->kode_lokasi ?>
                            </span>
                        </td>
                        <td class="px-4 py-4 header-title font-medium"><?= $lok->nama_lokasi ?></td>
                        <td class="px-4 py-4 header-subtitle"><?= $lok->keterangan ?? '-' ?></td>
                        <td class="px-4 py-4">
                            <?php if($lok->status == 'Aktif'): ?>
                                <button onclick="toggleStatus(<?= $lok->id ?>, '<?= $lok->status ?>')" 
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-emerald-500/10 text-emerald-400 text-xs font-medium rounded-full border border-emerald-500/30 hover:bg-emerald-500/20 transition-all duration-200 cursor-pointer whitespace-nowrap">
                                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span>
                                    Aktif
                                </button>
                            <?php else: ?>
                                <button onclick="toggleStatus(<?= $lok->id ?>, '<?= $lok->status ?>')" 
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-red-500/10 text-red-400 text-xs font-medium rounded-full border border-red-500/30 hover:bg-red-500/20 transition-all duration-200 cursor-pointer whitespace-nowrap">
                                    <span class="w-1.5 h-1.5 bg-red-400 rounded-full"></span>
                                    Tidak Aktif
                                </button>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex gap-1.5">
                                <button onclick="editLokasi(<?= $lok->id ?>)" 
                                        class="p-2 bg-amber-900/40 hover:bg-amber-800/50 text-amber-400 rounded transition-all duration-200 shadow-sm border border-amber-800/30 transform hover:scale-110" 
                                        title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button onclick="hapusLokasi(<?= $lok->id ?>, '<?= addslashes($lok->nama_lokasi) ?>')" 
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

<!-- Modal Tambah/Edit Lokasi -->
<div id="modalLokasi" class="hidden fixed inset-0 bg-black/50 z-50 flex items-start justify-center p-4 pt-20">
    <div class="theme-card rounded-lg shadow-xl w-full max-w-lg border" onclick="event.stopPropagation()">
        <!-- Header -->
        <div class="bg-blue-600 px-5 py-4 flex items-center justify-between">
            <h3 id="modalTitle" class="text-lg font-semibold text-white">Tambah Lokasi</h3>
            <button onclick="tutupModal()" class="text-white/80 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="formLokasi" class="p-5 space-y-4">
            <input type="hidden" id="lokasi_id" name="id">
            
            <!-- Kode Lokasi -->
            <div>
                <label class="block text-sm font-medium header-subtitle mb-1.5">
                    Kode Lokasi <span class="text-red-400">*</span>
                </label>
                <input type="text" id="kode_lokasi" name="kode_lokasi" required
                       class="w-full px-3 py-2 bg-slate-800/80 border border-slate-700/50 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-500 transition-all"
                       placeholder="105">
            </div>

            <!-- Nama Lokasi -->
            <div>
                <label class="block text-sm font-medium header-subtitle mb-1.5">
                    Nama Lokasi <span class="text-red-400">*</span>
                </label>
                <input type="text" id="nama_lokasi" name="nama_lokasi" required
                       class="w-full px-3 py-2 bg-slate-800/80 border border-slate-700/50 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-500 transition-all"
                       placeholder="Ruang Staf IPDS">
            </div>

            <!-- Keterangan -->
            <div>
                <label class="block text-sm font-medium header-subtitle mb-1.5">
                    Keterangan <span class="text-xs text-slate-500">(Opsional)</span>
                </label>
                <textarea id="keterangan" name="keterangan" rows="2"
                          class="w-full px-3 py-2 bg-slate-800/80 border border-slate-700/50 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-500 resize-none transition-all"
                          placeholder="Catatan tambahan..."></textarea>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium header-subtitle mb-1.5">Status</label>
                <select id="status" name="status" class="w-full px-3 py-2 bg-slate-800/80 border border-slate-700/50 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    <option value="Aktif">Aktif</option>
                    <option value="Tidak Aktif">Tidak Aktif</option>
                </select>
            </div>

            <!-- Action Buttons -->
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

<!-- Load jQuery & Dependencies -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<style>
/* DataTables Styling - Dark Theme */
#tabelLokasi_wrapper {
    color: #cbd5e1;
}

#tabelLokasi_wrapper .dataTables_length,
#tabelLokasi_wrapper .dataTables_filter {
    margin-bottom: 1.5rem;
}

#tabelLokasi_wrapper .dataTables_length select,
#tabelLokasi_wrapper .dataTables_filter input {
    background: rgba(30, 41, 59, 0.8);
    border: 1px solid rgba(71, 85, 105, 0.5);
    color: white;
    padding: 0.5rem 0.75rem;
    border-radius: 0.5rem;
    margin-left: 0.5rem;
    backdrop-filter: blur(4px);
}

#tabelLokasi_wrapper .dataTables_filter input {
    min-width: 250px;
}

#tabelLokasi_wrapper .dataTables_filter input:focus,
#tabelLokasi_wrapper .dataTables_length select:focus {
    outline: none;
    border-color: rgba(59, 130, 246, 0.5);
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
}

#tabelLokasi_wrapper .dataTables_length label,
#tabelLokasi_wrapper .dataTables_filter label {
    color: #94a3b8;
    font-size: 0.875rem;
}

#tabelLokasi_wrapper .dataTables_info {
    color: #64748b;
    padding-top: 1rem;
}

#tabelLokasi_wrapper .dataTables_paginate {
    padding-top: 1rem;
}

#tabelLokasi_wrapper .dataTables_paginate .paginate_button {
    color: #94a3b8 !important;
    background: rgba(30, 41, 59, 0.5) !important;
    border: 1px solid rgba(71, 85, 105, 0.4) !important;
    margin: 0 3px;
    padding: 0.5rem 0.875rem;
    border-radius: 0.5rem;
    transition: all 0.2s;
}

#tabelLokasi_wrapper .dataTables_paginate .paginate_button:hover {
    color: white !important;
    background: rgba(30, 58, 138, 0.6) !important;
    border-color: rgba(59, 130, 246, 0.5) !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
}

#tabelLokasi_wrapper .dataTables_paginate .paginate_button.current {
    color: white !important;
    background: #2563eb !important;
    border-color: #3b82f6 !important;
    box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.5);
}

#tabelLokasi_wrapper .dataTables_paginate .paginate_button.disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

table.dataTable tbody tr {
    background-color: transparent;
}

table.dataTable tbody tr:hover {
    background: rgba(30, 41, 59, 0.4) !important;
}

/* Light mode overrides */
html.light #tabelLokasi_wrapper .dataTables_length select,
html.light #tabelLokasi_wrapper .dataTables_filter input {
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
// Helper untuk mendapatkan warna SweetAlert berdasarkan theme
function getSwalColors() {
    const isLight = document.documentElement.classList.contains('light');
    return {
        background: isLight ? '#ffffff' : '#1a1f2e',
        color: isLight ? '#0f172a' : '#f3f4f6'
    };
}

// ✅ INISIALISASI DATATABLE
$(document).ready(function() {
    $('#tabelLokasi').DataTable({
        language: {
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
            { width: "120px", targets: 4 },
            { width: "100px", targets: 5 }
        ],
        autoWidth: false
    });
});

// ✅ TAMBAH LOKASI
function tambahLokasi() {
    document.getElementById('modalTitle').textContent = 'Tambah Lokasi';
    document.getElementById('formLokasi').reset();
    document.getElementById('lokasi_id').value = '';
    document.getElementById('status').value = 'Aktif';
    document.getElementById('modalLokasi').classList.remove('hidden');
}

// ✅ EDIT LOKASI
function editLokasi(id) {
    document.getElementById('modalTitle').textContent = 'Edit Lokasi';
    
    const colors = getSwalColors();
    Swal.fire({
        title: 'Memuat data...',
        text: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        },
        ...colors
    });
    
    fetch('<?= base_url("master_lokasi/get_lokasi/") ?>' + id)
        .then(response => {
            if (!response.ok) {
                throw new Error('HTTP Error: ' + response.status);
            }
            return response.text();
        })
        .then(text => {
            let data;
            try {
                data = JSON.parse(text);
            } catch (e) {
                throw new Error('Invalid JSON response: ' + e.message);
            }
            
            Swal.close();
            
            if (data.hasOwnProperty('status') && data.status === 'error') {
                const errorColors = getSwalColors();
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: data.message || 'Tidak dapat mengambil data lokasi',
                    confirmButtonColor: '#ef4444',
                    ...errorColors
                });
                return;
            }
            
            if (!data.id || !data.kode_lokasi || !data.nama_lokasi) {
                const errorColors = getSwalColors();
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Data lokasi tidak lengkap atau tidak valid',
                    confirmButtonColor: '#ef4444',
                    ...errorColors
                });
                return;
            }
            
            document.getElementById('lokasi_id').value = data.id;
            document.getElementById('kode_lokasi').value = data.kode_lokasi;
            document.getElementById('nama_lokasi').value = data.nama_lokasi;
            document.getElementById('keterangan').value = data.keterangan || '';
            document.getElementById('status').value = data.status || 'Aktif';
            document.getElementById('modalLokasi').classList.remove('hidden');
        })
        .catch(error => {
            Swal.close();
            const errorColors = getSwalColors();
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Tidak dapat mengambil data lokasi. Error: ' + error.message,
                confirmButtonColor: '#ef4444',
                ...errorColors
            });
        });
}

// ✅ FORM SUBMIT HANDLER - TAMBAH/EDIT
document.getElementById('formLokasi').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const id = document.getElementById('lokasi_id').value;
    
    const url = id ? '<?= base_url("master_lokasi/edit") ?>' : '<?= base_url("master_lokasi/tambah") ?>';
    
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
    
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(text => {
        let data;
        try {
            data = JSON.parse(text);
        } catch (e) {
            throw new Error('Invalid JSON: ' + text);
        }
        
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
                tutupModal();
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
        const errorColors = getSwalColors();
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Terjadi kesalahan: ' + error.message,
            confirmButtonColor: '#ef4444',
            ...errorColors
        });
    });
});

// ✅ TOGGLE STATUS
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
            fetch('<?= base_url("master_lokasi/toggle_status/") ?>' + id, {
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

// ✅ HAPUS LOKASI
function hapusLokasi(id, nama) {
    const colors = getSwalColors();
    Swal.fire({
        title: 'Hapus Lokasi?',
        text: `Yakin ingin menghapus lokasi "${nama}"?`,
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
            
            fetch('<?= base_url("master_lokasi/hapus/") ?>' + id, {
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
            })
            .catch(error => {
                const errorColors = getSwalColors();
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat menghapus data',
                    confirmButtonColor: '#ef4444',
                    ...errorColors
                });
            });
        }
    });
}

// ✅ TUTUP MODAL
function tutupModal() {
    document.getElementById('modalLokasi').classList.add('hidden');
    document.getElementById('formLokasi').reset();
}

// Close modal dengan tombol ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('modalLokasi');
        if (!modal.classList.contains('hidden')) {
            tutupModal();
        }
    }
});
</script>