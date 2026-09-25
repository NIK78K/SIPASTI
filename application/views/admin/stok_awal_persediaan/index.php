<div class="container-fluid p-6">
    <div class="theme-card backdrop-blur-sm rounded-xl shadow-2xl p-6 mb-6 border">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold header-title">Master Stok Awal Persediaan</h1>
                <p class="text-sm header-subtitle mt-1">Stok awal barang persediaan per periode bulan & tahun</p>
            </div>
            <button onclick="tambahStok()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition shadow-lg font-medium transform hover:scale-105 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Input Stok Awal
            </button>
        </div>

        <!-- Tabel Stok Awal -->
        <div class="overflow-x-auto">
            <table id="tabelBarang" class="w-full" style="width: 100% !important;">
                <thead>
                    <tr class="bg-slate-900/90 header-subtitle border-b border-slate-800/50">
                        <th class="px-4 py-4 text-left text-sm font-semibold" style="width: 50px;">No</th>
                        <th class="px-4 py-4 text-left text-sm font-semibold" style="width: 140px;">Kode Barang</th>
                        <th class="px-4 py-4 text-left text-sm font-semibold">Nama Barang</th>
                        <th class="px-4 py-4 text-left text-sm font-semibold" style="width: 140px;">Kategori</th>
                        <th class="px-4 py-4 text-left text-sm font-semibold" style="width: 80px;">Bulan</th>
                        <th class="px-4 py-4 text-left text-sm font-semibold" style="width: 80px;">Tahun</th>
                        <th class="px-4 py-4 text-left text-sm font-semibold" style="width: 100px;">Jumlah</th>
                        <th class="px-4 py-4 text-left text-sm font-semibold">Keterangan</th>
                        <th class="px-4 py-4 text-left text-sm font-semibold" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($stok_awal as $st): ?>
                    <tr class="border-b border-slate-800/50 hover:bg-slate-800/50 transition-all duration-200">
                        <td class="px-4 py-4 header-subtitle"><?= $no++ ?></td>
                        <td class="px-4 py-4">
                            <span class="px-2.5 py-1 bg-slate-800/80 text-cyan-300 rounded text-xs font-mono border border-slate-700/50 whitespace-nowrap">
                                <?= htmlspecialchars($st->kode_barang ?? '-') ?>
                            </span>
                        </td>
                        <td class="px-4 py-4 header-title font-medium"><?= htmlspecialchars($st->nama_barang ?? '-') ?></td>
                        <td class="px-4 py-4">
                            <span class="px-2.5 py-1 bg-purple-900/40 text-purple-400 rounded-full text-xs font-medium border border-purple-800/40 shadow-sm whitespace-nowrap">
                                <?= htmlspecialchars($st->kategori_kode ?? '-') ?>
                            </span>
                            <div class="text-xs header-subtitle mt-1"><?= htmlspecialchars($st->kategori_nama ?? '') ?></div>
                        </td>
                        <td class="px-4 py-4 header-subtitle"><?= htmlspecialchars($st->bulan) ?></td>
                        <td class="px-4 py-4 header-subtitle"><?= htmlspecialchars($st->tahun) ?></td>
                        <td class="px-4 py-4">
                            <span class="px-2.5 py-1 bg-emerald-900/40 text-emerald-400 rounded text-xs font-medium border border-emerald-800/40 whitespace-nowrap">
                                <?= htmlspecialchars($st->jumlah) ?> <?= htmlspecialchars($st->satuan ?? '') ?>
                            </span>
                        </td>
                        <td class="px-4 py-4 header-subtitle"><?= htmlspecialchars($st->keterangan ?? '-') ?></td>
                        <td class="px-4 py-4">
                            <div class="flex gap-1.5">
                                <button onclick="editStok(<?= $st->id ?>)"
                                        class="p-2 bg-amber-900/40 hover:bg-amber-800/50 text-amber-400 rounded transition-all duration-200 shadow-sm border border-amber-800/30 transform hover:scale-110"
                                        title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button onclick="hapusStok(<?= $st->id ?>, '<?= addslashes($st->nama_barang ?? '') ?>')"
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

<!-- Modal Tambah/Edit Stok Awal -->
<div id="modalBarang" class="hidden fixed inset-0 bg-black/50 z-50 flex items-start justify-center p-4 pt-20">
    <div class="theme-card rounded-lg shadow-xl w-full max-w-lg border" onclick="event.stopPropagation()">
        <div class="bg-blue-600 px-5 py-4 flex items-center justify-between">
            <h3 id="modalTitle" class="text-lg font-semibold text-white">Tambah Stok Awal</h3>
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
                    Barang <span class="text-red-400">*</span>
                </label>
                <select id="stok_barang_id" name="barang_id" required
                        class="w-full px-3 py-2 bg-slate-800/80 border border-slate-700/50 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    <option value="" class="bg-slate-800 text-slate-400">-- Pilih Barang --</option>
                    <?php foreach($barang_list as $brg): ?>
                        <option value="<?= $brg->id ?>" class="bg-slate-800 text-white"><?= htmlspecialchars($brg->kode_barang . ' - ' . $brg->nama_barang) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium header-subtitle mb-1.5">
                        Bulan <span class="text-red-400">*</span>
                    </label>
                    <input type="number" id="bulan" name="bulan" required min="1" max="12" value="<?= date('n') ?>"
                           class="w-full px-3 py-2 bg-slate-800/80 border border-slate-700/50 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium header-subtitle mb-1.5">
                        Tahun <span class="text-red-400">*</span>
                    </label>
                    <input type="number" id="tahun" name="tahun" required min="2000" max="2100" value="<?= date('Y') ?>"
                           class="w-full px-3 py-2 bg-slate-800/80 border border-slate-700/50 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium header-subtitle mb-1.5">
                    Jumlah <span class="text-red-400">*</span>
                </label>
                <input type="number" id="jumlah" name="jumlah" required min="0" step="any"
                       class="w-full px-3 py-2 bg-slate-800/80 border border-slate-700/50 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-500 transition-all"
                       placeholder="Contoh: 100">
            </div>

            <div>
                <label class="block text-sm font-medium header-subtitle mb-1.5">Keterangan</label>
                <input type="text" id="keterangan" name="keterangan" maxlength="255"
                       class="w-full px-3 py-2 bg-slate-800/80 border border-slate-700/50 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-500 transition-all"
                       placeholder="Opsional">
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
        order: [[3, 'asc']],
        columnDefs: [
            { orderable: false, targets: 8 }
        ],
        autoWidth: false
    });
});

function tambahStok() {
    editMode = false;
    document.getElementById('modalTitle').textContent = 'Tambah Stok Awal';
    document.getElementById('formBarang').reset();
    document.getElementById('barang_id').value = '';
    document.getElementById('modalBarang').classList.remove('hidden');
}

function editStok(id) {
    fetch('<?= base_url("stok_awal_persediaan/get/") ?>' + id)
    .then(r => r.json())
    .then(d => {
        if (d.status === 'error') {
            const c = getSwalColors();
            return Swal.fire({ icon: 'error', title: 'Gagal!', text: d.message, ...c });
        }
        editMode = true;
        document.getElementById('modalTitle').textContent = 'Edit Stok Awal';
        document.getElementById('barang_id').value = d.id;
        document.getElementById('stok_barang_id').value = d.barang_id;
        document.getElementById('bulan').value = d.bulan;
        document.getElementById('tahun').value = d.tahun;
        document.getElementById('jumlah').value = d.jumlah;
        document.getElementById('keterangan').value = d.keterangan || '';
        document.getElementById('modalBarang').classList.remove('hidden');
    });
}

document.getElementById('formBarang').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const url = editMode ? '<?= base_url("stok_awal_persediaan/update") ?>' : '<?= base_url("stok_awal_persediaan/tambah") ?>';

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

function hapusStok(id, nama) {
    const colors = getSwalColors();
    Swal.fire({
        title: 'Hapus Stok Awal?',
        text: `Yakin ingin menghapus stok awal "${nama}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#ef4444',
        reverseButtons: true,
        ...colors
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('<?= base_url("stok_awal_persediaan/hapus/") ?>' + id, { method: 'POST' })
            .then(r => r.json())
            .then(data => {
                const c = getSwalColors();
                Swal.fire({ icon: data.status, title: data.status === 'success' ? 'Terhapus!' : 'Gagal!', text: data.message, ...c })
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
