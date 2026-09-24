<!-- ================= JUDUL ================= -->
<h1 class="text-2xl font-bold mb-1 header-title">Manajemen Perangkat Dihapus</h1>
<p class="text-gray-400 mb-6 header-subtitle">Perangkat yang telah diproses Transaksi SAKTI</p>

<!-- ================= KARTU KONDISI ================= -->
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="border-l-4 border-green-500 theme-card stat-card p-4 rounded-lg hover:shadow-lg hover:shadow-green-500/10 transition-all">
        <div class="flex items-center gap-3">
            <div class="p-3 bg-green-500/10 rounded-lg">
                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="text-gray-400 text-sm header-subtitle">Baik</div>
                <div class="text-2xl font-bold text-green-400"><?= $baik ?></div>
            </div>
        </div>
    </div>
    
    <div class="border-l-4 border-yellow-500 theme-card stat-card p-4 rounded-lg hover:shadow-lg hover:shadow-yellow-500/10 transition-all">
        <div class="flex items-center gap-3">
            <div class="p-3 bg-yellow-500/10 rounded-lg">
                <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <div class="text-gray-400 text-sm header-subtitle">Rusak Ringan</div>
                <div class="text-2xl font-bold text-yellow-400"><?= $rusak_ringan ?></div>
            </div>
        </div>
    </div>
    
    <div class="border-l-4 border-red-500 theme-card stat-card p-4 rounded-lg hover:shadow-lg hover:shadow-red-500/10 transition-all">
        <div class="flex items-center gap-3">
            <div class="p-3 bg-red-500/10 rounded-lg">
                <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="text-gray-400 text-sm header-subtitle">Rusak Berat</div>
                <div class="text-2xl font-bold text-red-400"><?= $rusak_berat ?></div>
            </div>
        </div>
    </div>
</div>

<!-- ================= FILTER ================= -->
<form method="get"
      class="theme-card p-4 rounded-lg border mb-4 space-y-4">

    <!-- ================= QUICK FILTER ================= -->
    <div class="grid grid-cols-4 gap-3">

        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input type="text"
                   name="keyword"
                   value="<?= $this->input->get('keyword') ?>"
                   placeholder="Cari nama / kode barang"
                   class="pl-10 w-full px-3 py-2.5 bg-slate-800/80 border border-slate-700/50 rounded-lg text-sm
                          focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
        </div>

        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                </svg>
            </div>
            <input type="text"
                   name="nup"
                   value="<?= $this->input->get('nup') ?>"
                   placeholder="NUP"
                   class="pl-10 w-full px-3 py-2.5 bg-slate-800/80 border border-slate-700/50 rounded-lg text-sm
                          focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
        </div>

        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <select name="kondisi"
                    class="pl-10 w-full px-3 py-2.5 bg-slate-800/80 border border-slate-700/50 rounded-lg text-sm
                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition appearance-none">
                <option value="">Semua Kondisi</option>
                <option value="Baik" <?= $this->input->get('kondisi')=='Baik'?'selected':'' ?>>Baik</option>
                <option value="Rusak Ringan" <?= $this->input->get('kondisi')=='Rusak Ringan'?'selected':'' ?>>Rusak Ringan</option>
                <option value="Rusak Berat" <?= $this->input->get('kondisi')=='Rusak Berat'?'selected':'' ?>>Rusak Berat</option>
            </select>
        </div>

        <div class="flex gap-2 justify-end">
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-500 px-5 py-2.5 rounded-lg text-sm font-medium transition shadow-lg hover:shadow-xl">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                Filter
            </button>
            <a href="<?= base_url('perangkat_dihapus') ?>"
               class="inline-flex items-center gap-2 bg-gray-700 hover:bg-gray-600 px-5 py-2.5 rounded-lg text-sm font-medium transition shadow-lg hover:shadow-xl">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Reset
            </a>
        </div>

    </div>
</form>

<!-- ================= INSIGHT MINI ================= -->
<div class="mb-3 px-4 py-2.5
            theme-card
            border
            rounded-lg
            text-sm text-gray-300
            flex items-center gap-2">
    <svg class="w-4 h-4 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span>🔍 Menampilkan <b><?= $total ?></b> perangkat yang telah dihapus</span>
</div>

<!-- ================= TABEL (SCROLLABLE) ================= -->
<div class="overflow-x-auto border rounded-lg theme-card">
    <table class="w-full text-[13px]">

        <thead class="sticky top-0 bg-slate-900/90 text-gray-400 border-b border-slate-700/50">
            <tr>
                <th class="px-3 py-2 text-center">No</th>
                <th class="px-2 py-1.5">Jenis BMN</th>
                <th class="px-2 py-1.5">Kode Satker</th>
                <th class="px-2 py-1.5">Nama Satker</th>
                <th class="px-2 py-1.5">Kode Barang</th>
                <th class="px-2 py-1.5">NUP</th>
                <th class="px-2 py-1.5">Nama Barang</th>
                <th class="px-2 py-1.5 text-center">Kondisi</th>
                <th class="px-2 py-1.5">Transaksi SAKTI</th>
                <th class="px-3 py-2 text-center w-32">Aksi</th>
            </tr>
        </thead>

        <tbody>
        <?php if (!empty($perangkat)): ?>
        <?php $no = $from; foreach ($perangkat as $p): ?>

        <tr class="border-b border-slate-800/50 hover:bg-slate-800/50 transition">
            <td class="px-3 py-2 text-center"><?= $no++ ?></td>
            <td class="px-2 py-1.5"><?= $p['jenis_bmn'] ?></td>
            <td class="px-2 py-1.5"><?= $p['kode_satker'] ?></td>
            <td class="px-2 py-1.5"><?= $p['nama_satker'] ?></td>
            <td class="px-2 py-1.5"><?= $p['kode_barang'] ?></td>
            <td class="px-2 py-1.5"><?= $p['nup'] ?></td>
            <td class="px-2 py-1.5 truncate max-w-[260px]" title="<?= $p['nama_barang'] ?>">
                <?= $p['nama_barang'] ?>
            </td>

            <td class="px-3 py-2 text-center">
                <?php
                $badge = match ($p['kondisi']) {
                    'Baik' => 'bg-green-600/15 text-green-400 ring-1 ring-green-600/30',
                    'Rusak Ringan' => 'bg-yellow-500/15 text-yellow-400 ring-1 ring-yellow-500/30',
                    'Rusak Berat' => 'bg-red-600/15 text-red-400 ring-1 ring-red-600/30',
                    default => 'bg-gray-600/15 text-gray-400 ring-1 ring-gray-600/30',
                };
                ?>
                <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full whitespace-nowrap <?= $badge ?>">
                    <?= $p['kondisi'] ?>
                </span>
            </td>

            <td class="px-2 py-1.5">
                <?php if (!empty($p['transaksi_sakti'])): ?>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-500/10 text-blue-400 text-xs rounded-md border border-blue-500/30">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <?= $p['transaksi_sakti'] ?>
                    </span>
                <?php else: ?>
                    <span class="text-gray-500 text-xs">-</span>
                <?php endif; ?>
            </td>

            <!-- AKSI -->
            <td class="px-2 py-1.5 text-center">
                <div class="inline-flex items-center gap-1.5">

                    <!-- DETAIL -->
                    <button type="button"
                        title="Detail"
                        data-id="<?= $p['id'] ?>"
                        class="btnDetail
                               h-8 w-8
                               rounded-lg
                               bg-indigo-500/10 hover:bg-indigo-500/20
                               text-indigo-400 hover:text-indigo-300
                               flex items-center justify-center
                               transition-all transform hover:scale-110
                               border border-indigo-500/20 hover:border-indigo-500/40">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>

                    <!-- HAPUS PERMANEN -->
                    <button type="button"
                        onclick="confirmDeletePermanent(<?= $p['id'] ?>, '<?= addslashes($p['nama_barang']) ?>')"
                        title="Hapus Permanen"
                        class="h-8 w-8
                               rounded-lg
                               bg-red-500/10 hover:bg-red-500/20
                               text-red-400 hover:text-red-300
                               flex items-center justify-center
                               transition-all transform hover:scale-110
                               border border-red-500/20 hover:border-red-500/40">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>

                    <!-- FORM HIDDEN UNTUK SUBMIT -->
                    <form id="deleteForm<?= $p['id'] ?>" 
                          action="<?= base_url('perangkat_dihapus/destroy/'.$p['id']) ?>"
                          method="post" 
                          style="display: none;">
                    </form>
                </div>
            </td>
        </tr>

        <?php endforeach; else: ?>
        <tr>
            <td colspan="10" class="text-center py-8">
                <div class="flex flex-col items-center gap-3">
                    <svg class="w-16 h-16 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <span class="text-gray-500 font-medium">Data perangkat dihapus tidak tersedia</span>
                </div>
            </td>
        </tr>
        <?php endif; ?>
        </tbody>

    </table>
</div>

<!-- ================= PAGINATION ================= -->
<div class="flex justify-between items-center mt-4 text-sm">
    <div class="text-gray-400">
        Menampilkan <?= $from ?> - <?= $to ?> dari <?= $total ?> data
    </div>

    <div class="flex gap-1 items-center">
        <?php if ($current_page > 1): ?>
        <a href="<?= base_url('perangkat_dihapus?page='.($current_page-1)) ?>"
            class="px-3 py-1 bg-gray-700 rounded hover:bg-gray-600 transition">«</a>
        <?php endif ?>

        <?php foreach ($pages as $p): ?>
        <a href="<?= base_url('perangkat_dihapus?page='.$p) ?>" 
           class="px-3 py-1 rounded transition
               <?= $p == $current_page
                    ? 'bg-blue-600 text-white'
                    : 'bg-gray-700 hover:bg-gray-600 text-gray-200' ?>">
            <?= $p ?>
        </a>
        <?php endforeach ?>

        <?php if ($current_page < $total_pages): ?>
        <a href="<?= base_url('perangkat_dihapus?page='.($current_page+1)) ?>"
            class="px-3 py-1 bg-gray-700 rounded hover:bg-gray-600 transition">»</a>
        <?php endif ?>
    </div>
</div>

<!-- ================= MODAL DETAIL ================= -->
<div id="modalDetail"
     class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">

    <div class="theme-card w-full max-w-2xl max-h-[85vh] rounded-lg shadow-lg flex flex-col border">
        <div class="flex justify-between items-center
                    px-5 py-4 border-b border-slate-700/50 shrink-0">
            <h3 class="font-semibold text-lg flex items-center gap-2 header-title">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Detail Perangkat Dihapus
            </h3>
            <button id="closeDetailModal"
                    class="text-gray-400 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div id="modalDetailContent"
             class="p-5 overflow-y-auto flex-1">
            <div class="text-center py-10 text-gray-400 flex flex-col items-center gap-3">
                <svg class="w-8 h-8 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Memuat detail...
            </div>
        </div>
    </div>
</div>

<!-- Theme-aware styles -->
<style>
html.light tbody tr:hover {
    background: rgba(248, 250, 252, 0.6) !important;
}

html.light thead {
    background: rgba(241, 245, 249, 0.9) !important;
}

html.light thead th {
    color: var(--text-secondary) !important;
}

html.light .bg-slate-800\/80,
html.light .bg-slate-800\/50 {
    background: rgba(248, 250, 252, 0.8) !important;
}

html.light .border-slate-700\/50,
html.light .border-slate-800\/50 {
    border-color: rgba(226, 232, 240, 0.8) !important;
}

html.light input,
html.light select {
    background: rgba(255, 255, 255, 0.95) !important;
    color: var(--text-primary) !important;
    border-color: rgba(203, 213, 225, 0.8) !important;
}

.swal2-popup {
    background-color: var(--bg-card) !important;
    border: 2px solid var(--border-color) !important;
    color: var(--text-primary) !important;
}

html.light .swal2-popup {
    background-color: #ffffff !important;
    border-color: rgba(226, 232, 240, 0.8) !important;
}

.swal2-title {
    color: var(--text-primary) !important;
}

.swal2-html-container {
    color: var(--text-secondary) !important;
}
</style>

<!-- ================= SWEETALERT2 CDN ================= -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- ================= JS SWEET ALERT HAPUS PERMANEN ================= -->
<script>
function getSwalColors() {
    const isLight = document.documentElement.classList.contains('light');
    return {
        background: isLight ? '#ffffff' : '#1a1f2e',
        color: isLight ? '#0f172a' : '#f3f4f6'
    };
}

function confirmDeletePermanent(id, namaBarang) {
    const nama = namaBarang.replace(/&quot;/g, '"').replace(/&#039;/g, "'").replace(/&amp;/g, '&');
    const colors = getSwalColors();
    
    Swal.fire({
        title: '⚠️ PERINGATAN!',
        html: `Anda akan menghapus <b>PERMANEN</b>:<br><br>
               "<i>${nama}</i>"<br><br>
               🚨 Data yang dihapus <b>TIDAK DAPAT DIKEMBALIKAN!</b><br>
               Apakah Anda benar-benar yakin?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '🗑️ Ya, Hapus Permanen!',
        cancelButtonText: '❌ Batalkan',
        reverseButtons: true,
        allowOutsideClick: false,
        allowEscapeKey: false,
        ...colors
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Konfirmasi Terakhir!',
                html: `Ini adalah <b>kesempatan terakhir</b> Anda untuk membatalkan.<br><br>
                       Data akan dihapus <b>SELAMANYA</b> dari database!`,
                icon: 'error',
                showCancelButton: true,
                confirmButtonText: 'HAPUS SEKARANG!',
                cancelButtonText: 'Batalkan',
                reverseButtons: true,
                ...colors
            }).then((result2) => {
                if (result2.isConfirmed) {
                    Swal.fire({
                        title: 'Menghapus Data...',
                        html: 'Mohon tunggu, data sedang dihapus permanen',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => Swal.showLoading(),
                        ...colors
                    });
                    document.getElementById('deleteForm' + id).submit();
                }
            });
        }
    });
}

window.addEventListener('load', function() {
    const colors = getSwalColors();
    
    <?php if($this->session->flashdata('success')): ?>
    Swal.fire({
        title: '✅ Berhasil!',
        text: '<?= $this->session->flashdata('success') ?>',
        icon: 'success',
        confirmButtonText: 'OK',
        confirmButtonColor: '#10b981',
        ...colors
    });
    <?php endif; ?>

    <?php if($this->session->flashdata('error')): ?>
    Swal.fire({
        title: '❌ Error!',
        text: '<?= $this->session->flashdata('error') ?>',
        icon: 'error',
        confirmButtonText: 'OK',
        ...colors
    });
    <?php endif; ?>
});
</script>

<!-- ================= JS MODAL DETAIL ================= -->
<script>
const modal = document.getElementById('modalDetail');
const modalContent = document.getElementById('modalDetailContent');
const closeBtn = document.getElementById('closeDetailModal');

document.addEventListener('click', function(e){
    const btn = e.target.closest('.btnDetail');
    if (!btn) return;

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    modalContent.innerHTML = `
        <div class="text-center py-10 text-gray-400 flex flex-col items-center gap-3">
            <svg class="w-8 h-8 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Memuat detail...
        </div>
    `;

    fetch('<?= base_url("perangkat_dihapus/detail_ajax/") ?>' + btn.dataset.id)
        .then(res => res.text())
        .then(html => modalContent.innerHTML = html)
        .catch(() => modalContent.innerHTML =
            '<div class="text-red-400 text-center py-6">Gagal memuat detail</div>');
});

closeBtn.addEventListener('click', closeModal);
modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });

function closeModal(){
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    modalContent.innerHTML = '';
}
</script>