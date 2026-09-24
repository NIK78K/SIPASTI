<div class="container-fluid p-6">
    <div class="theme-card backdrop-blur-sm rounded-xl shadow-2xl p-6 mb-6 border">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold header-title">Master Barang Persediaan</h1>
                <p class="text-sm header-subtitle mt-1">Pengelolaan barang persediaan per kategori (ATK & rumah tangga kantor)</p>
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
                        <th class="px-4 py-4 text-left text-sm font-semibold" style="width: 160px;">Kategori</th>
                        <th class="px-4 py-4 text-left text-sm font-semibold">Nama Barang</th>
                        <th class="px-4 py-4 text-left text-sm font-semibold" style="width: 100px;">Satuan</th>
                        <th class="px-4 py-4 text-left text-sm font-semibold" style="width: 120px;">Status</th>
                        <th class="px-4 py-4 text-left text-sm font-semibold" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($barang as $brg): ?>
                    <tr class="border-b border-slate-800/50 hover:bg-slate-800/50 transition-all duration-200">
                        <td class="px-4 py-4 header-subtitle"><?= $no++ ?></td>
                        <td class="px-4 py-4">
                            <span class="px-2.5 py-1 bg-purple-900/40 text-purple-400 rounded-full text-xs font-medium border border-purple-800/40 shadow-sm whitespace-nowrap">
                                <?= htmlspecialchars($brg->kategori_kode ?? '-') ?>
                            </span>
                            <div class="text-xs header-subtitle mt-1"><?= htmlspecialchars($brg->kategori_nama ?? '') ?></div>
                        </td>
                        <td class="px-4 py-4 header-title font-medium"><?= htmlspecialchars($brg->nama_barang) ?></td>
                        <td class="px-4 py-4 header-subtitle"><?= htmlspecialchars($brg->satuan) ?></td>
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
                                    Nonaktif
                                </button>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex gap-1.5">
                                <button onclick="editBarang(<?= $brg->id ?>)"
                                        class="p-2 bg-amber-900/40 hover:bg-amber-800/50 text-amber-400 rounded transition-all duration-200 shadow-sm border border-amber-800/30 transform hover:scale-110"
                                        title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
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

<!-- Modal Tambah/Edit Barang -->
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
                <select id="kategori_id" name="kategori_id" required
                        class="w-full px-3 py-2 bg-slate-800/80 border border-slate-700/50 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    <option value="" class="bg-slate-800 text-slate-400">-- Pilih Kategori --</option>
                    <?php foreach($kategori_list as $kat): ?>
                        <option value="<?= $kat->id ?>" class="bg-slate-800 text-white"><?= htmlspecialchars($kat->kode . ' - ' . $kat->nama) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium header-subtitle mb-1.5">
                    Nama Barang <span class="text-red-400">*</span>
                </label>
                <input type="text" id="nama_barang" name="nama_barang" required
                       class="w-full px-3 py-2 bg-slate-800/80 border border-slate-700/50 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-500 transition-all"
                       placeholder="Contoh: Kertas HVS A4 80gr">
            </div>

            <div>
                <label class="block text-sm font-medium header-subtitle mb-1.5">Satuan</label>
                <input type="text" id="satuan" name="satuan"
                       class="w-full px-3 py-2 bg-slate-800/80 border border-slate-700/50 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-500 transition-all"
                       placeholder="pcs" value="pcs">
            </div>

            <div>
                <label class="block text-sm font-medium header-subtitle mb-1.5">Status</label>
                <select id="status" name="status" class="w-full px-3 py-2 bg-slate-800/80 border border-slate-700/50 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    <option value="Aktif" class="bg-slate-800 text-white">Aktif</option>
                    <option value="Nonaktif" class="bg-slate-800 text-white">Nonaktif</option>
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

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
#tabelBarang_wrapper { color: #cbd5e1; }
#tabelBarang_wrapper .dataTables_length,
#tabelBarang_wrapper .dataTables_filter { margin-bottom: 1.5rem; }
#tabelBarang_wrapper .dataTables_length select,
#tabelBarang_wrapper .dataTables_filter input {
    background: rgba(30, 41, 59, 0.8);
    border: 1px solid rgba(71, 85, 105, 0.5);
    color: white;
    padding: 0.5rem 0.75rem;
    border-radius: 0.5rem;
    margin-left: 0.5rem;
}
#tabelBarang_wrapper .dataTables_filter input { min-width: 250px; }
#tabelBarang_wrapper .dataTables_length label,
#tabelBarang_wrapper .dataTables_filter label { color: #94a3b8; font-size: 0.875rem; }
#tabelBarang_wrapper .dataTables_info { color: #64748b; padding-top: 1rem; }
#tabelBarang_wrapper .dataTables_paginate { padding-top: 1rem; }
#tabelBarang_wrapper .dataTables_paginate .paginate_button {
    color: #94a3b8 !important;
    background: rgba(30, 41, 59, 0.5) !important;
    border: 1px solid rgba(71, 85, 105, 0.4) !important;
    margin: 0 3px;
    padding: 0.5rem 0.875rem;
    border-radius: 0.5rem;
}
#tabelBarang_wrapper .dataTables_paginate .paginate_button.current {
    color: white !important;
    background: #2563eb !important;
    border-color: #3b82f6 !important;
}
table.dataTable tbody tr { background-color: transparent; }
html.light input, html.light select {
    background: rgba(255, 255, 255, 0.95) !important;
    color: var(--text-primary) !important;
    border-color: rgba(203, 213, 225, 0.8) !important;
}
</style>

<script>
let editMode = false;

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
            paginate: { first: "Pertama", last: "Terakhir", next: "Selanjutnya", previous: "Sebelumnya" }
        },
        pageLength: 15,
        lengthMenu: [[15, 25, 50, 100, -1], [15, 25, 50, 100, "Semua"]],
        order: [[1, 'asc']],
        columnDefs: [
            { orderable: false, targets: 5 },
            { width: "50px", targets: 0 },
            { width: "160px", targets: 1 },
            { width: "100px", targets: 3 },
            { width: "120px", targets: 4 },
            { width: "140px", targets: 5 }
        ],
        autoWidth: false
    });
});

function tambahBarang() {
    editMode = false;
    document.getElementById('modalTitle').textContent = 'Tambah Barang';
    document.getElementById('formBarang').reset();
    document.getElementById('barang_id').value = '';
    document.getElementById('satuan').value = 'pcs';
    document.getElementById('status').value = 'Aktif';
    document.getElementById('modalBarang').classList.remove('hidden');
}

function editBarang(id) {
    fetch('<?= base_url("barang_persediaan/get/") ?>' + id)
    .then(r => r.json())
    .then(d => {
        if (d.status === 'error') {
            const c = getSwalColors();
            return Swal.fire({ icon: 'error', title: 'Gagal!', text: d.message, ...c });
        }
        editMode = true;
        document.getElementById('modalTitle').textContent = 'Edit Barang';
        document.getElementById('barang_id').value = d.id;
        document.getElementById('kategori_id').value = d.kategori_id;
        document.getElementById('nama_barang').value = d.nama_barang;
        document.getElementById('satuan').value = d.satuan;
        document.getElementById('status').value = d.status;
        document.getElementById('modalBarang').classList.remove('hidden');
    });
}

document.getElementById('formBarang').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const url = editMode ? '<?= base_url("barang_persediaan/update") ?>' : '<?= base_url("barang_persediaan/tambah") ?>';

    const colors = getSwalColors();
    Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, allowEscapeKey: false, didOpen: () => Swal.showLoading(), ...colors });

    fetch(url, { method: 'POST', body: formData })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            const c = getSwalColors();
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: data.message, timer: 1500, ...c })
            .then(() => location.reload());
        } else {
            const c = getSwalColors();
            Swal.fire({ icon: 'error', title: 'Gagal!', text: data.message, ...c });
        }
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
        reverseButtons: true,
        ...colors
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('<?= base_url("barang_persediaan/hapus/") ?>' + id, { method: 'POST' })
            .then(r => r.json())
            .then(data => {
                const c = getSwalColors();
                Swal.fire({ icon: data.status, title: data.status === 'success' ? 'Terhapus!' : 'Gagal!', text: data.message, ...c })
                .then(() => { if (data.status === 'success') location.reload(); });
            });
        }
    });
}

function toggleStatus(id, currentStatus) {
    const newStatus = currentStatus === 'Aktif' ? 'Nonaktif' : 'Aktif';
    const colors = getSwalColors();
    Swal.fire({
        title: 'Ubah Status?',
        text: `Ubah status menjadi ${newStatus}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Ubah',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#2563eb',
        reverseButtons: true,
        ...colors
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('<?= base_url("barang_persediaan/toggle_status/") ?>' + id, { method: 'POST' })
            .then(r => r.json())
            .then(data => {
                const c = getSwalColors();
                Swal.fire({ icon: data.status, title: data.status === 'success' ? 'Berhasil!' : 'Gagal!', text: data.message, timer: 1500, ...c })
                .then(() => { if (data.status === 'success') location.reload(); });
            });
        }
    });
}

function tutupModal() {
    document.getElementById('modalBarang').classList.add('hidden');
}

document.getElementById('modalBarang').addEventListener('click', function(e) {
    if (e.target === this) tutupModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') tutupModal();
});
</script>
