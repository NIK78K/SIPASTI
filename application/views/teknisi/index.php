<h1 class="text-2xl font-bold mb-1 header-title">Manajemen Teknisi</h1>
<p class="text-gray-400 mb-8 header-subtitle">Pengelolaan data teknisi IT</p>

<!-- INFO CARD -->
<div class="grid grid-cols-4 gap-6 mb-10">
    <div class="theme-card stat-card rounded-xl p-6 border hover:shadow-xl transition-all duration-300">
        <p class="text-gray-400 text-sm mb-2 header-subtitle">Total Teknisi</p>
        <p class="text-4xl font-bold header-title"><?= count($teknisi) ?></p>
    </div>

    <div class="theme-card stat-card rounded-xl p-6 border hover:shadow-xl transition-all duration-300">
        <p class="text-gray-400 text-sm mb-2 header-subtitle">Tersedia</p>
        <p class="text-4xl font-bold text-green-500"><?= $available ?></p>
    </div>

    <div class="theme-card stat-card rounded-xl p-6 border hover:shadow-xl transition-all duration-300">
        <p class="text-gray-400 text-sm mb-2 header-subtitle">Sedang Bertugas</p>
        <p class="text-4xl font-bold text-yellow-500"><?= $busy ?></p>
    </div>

    <div class="theme-card stat-card rounded-xl p-6 border hover:shadow-xl transition-all duration-300">
        <p class="text-gray-400 text-sm mb-2 header-subtitle">Offline</p>
        <p class="text-4xl font-bold text-gray-500"><?= $offline ?></p>
    </div>
</div>

<!-- SEARCH & ACTION -->
<div class="flex justify-between items-center gap-4 mb-6">
    <div class="flex-1 relative group">
        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 transition-colors pointer-events-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        <input 
            type="text" 
            id="searchInput"
            placeholder="Cari nama atau keahlian teknisi..." 
            class="w-full pl-12 pr-4 py-3 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 border border-slate-200 dark:border-slate-700 focus:outline-none focus:border-blue-500 dark:focus:border-blue-400 transition-colors"
        >
        <button 
            id="clearSearch"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 opacity-0 transition-all"
            onclick="clearSearch()">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <button onclick="openCreateModal()"
        class="flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 px-5 py-3 rounded-lg text-sm font-semibold transition-all shadow-lg hover:shadow-xl active:scale-95">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Teknisi
    </button>
</div>

<!-- TABLE WRAPPER -->
<div class="theme-card rounded-xl overflow-hidden shadow-xl border">
<table class="w-full text-sm">
<thead class="bg-slate-900/90 dark:bg-slate-900/90 text-gray-400 uppercase text-xs tracking-wider">
<tr>
    <th class="px-6 py-4 text-left">Nama</th>
    <th class="px-6 py-4 text-left">Keahlian</th>
    <th class="px-6 py-4 text-center">Status</th>
    <th class="px-6 py-4 text-center">Tiket Aktif</th>
    <th class="px-6 py-4 text-center">Aksi</th>
</tr>
</thead>

<tbody id="tableBody">
<?php if (empty($teknisi)): ?>
<tr>
    <td colspan="5" class="text-center py-8 text-gray-500">
        <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
        </svg>
        Belum ada teknisi
    </td>
</tr>
<?php else: 
$perPage = 10;
$totalData = count($teknisi);
$currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$totalPages = ceil($totalData / $perPage);
$startIndex = ($currentPage - 1) * $perPage;
$displayData = array_slice($teknisi, $startIndex, $perPage);

foreach ($displayData as $t): 
?>
<?php
$status = strtolower($t['status']);
$statusColor = match ($status) {
    'available' => 'bg-green-600/20 text-green-400 border-green-500/30',
    'busy'      => 'bg-yellow-600/20 text-yellow-400 border-yellow-500/30',
    'offline'   => 'bg-gray-600/20 text-gray-400 border-gray-500/30',
    default     => 'bg-gray-600/20 text-gray-400 border-gray-500/30'
};
$statusIcon = match ($status) {
    'available' => '✓',
    'busy'      => '⚙',
    'offline'   => '⊗',
    default     => '◯'
};
?>
<tr class="border-b border-slate-700/50 hover:bg-slate-800/40 dark:hover:bg-slate-800/40 light:hover:bg-slate-100/40 transition-all duration-200 teknisi-row" data-nama="<?= strtolower($t['name']) ?>" data-keahlian="<?= strtolower($t['keahlian']) ?>">
    <td class="px-6 py-4 font-semibold header-title"><?= htmlspecialchars($t['name']) ?></td>
    <td class="px-6 py-4 text-gray-400 text-sm"><?= htmlspecialchars($t['keahlian']) ?></td>
    <td class="px-6 py-4 text-center">
        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs border <?= $statusColor ?>">
            <span><?= $statusIcon ?></span>
            <?= ucfirst($status) ?>
        </span>
    </td>
    <td class="px-6 py-4 text-center">
        <?php if ($t['tiket_aktif'] > 0): ?>
            <button 
                onclick="showTiketAktif(<?= $t['id'] ?>)"
                class="px-3 py-1 rounded bg-blue-500/20 text-blue-400 hover:bg-blue-500/30 transition-all text-sm font-semibold cursor-pointer">
                <?= $t['tiket_aktif'] ?> tiket
            </button>
        <?php else: ?>
            <span class="text-gray-500 text-sm">0 tiket</span>
        <?php endif; ?>
    </td>
    <td class="px-6 py-4 text-center">
        <div class="flex items-center justify-center gap-1">
            <?php if ($status === 'offline' && !empty($t['alasan_offline'])): ?>
                <button 
                    onclick="showAlasanModal('<?= htmlspecialchars($t['name'], ENT_QUOTES) ?>', '<?= htmlspecialchars($t['alasan_offline'], ENT_QUOTES) ?>')"
                    class="p-2 text-gray-500 hover:text-blue-400 hover:bg-blue-500/10 rounded-lg transition-all"
                    title="Lihat Alasan Offline">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </button>
            <?php endif; ?>
            
            <button onclick="openEditModal(<?= $t['id'] ?>)"
                class="p-2 text-gray-500 hover:text-yellow-400 hover:bg-yellow-500/10 rounded-lg transition-all"
                title="Edit Teknisi">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </button>
            
            <a href="<?= base_url('teknisi/delete/'.$t['id']) ?>"
               onclick="return confirm('Hapus teknisi ini? Aksi ini tidak dapat dibatalkan.')"
               class="p-2 text-gray-500 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-all"
               title="Hapus Teknisi">
               <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
               </svg>
            </a>
        </div>
    </td>
</tr>
<?php endforeach; endif ?>
</tbody>
</table>
</div>

<!-- PAGINATION -->
<?php if (isset($totalPages) && $totalPages > 1): ?>
<div class="flex items-center justify-between mt-6 px-2">
    <div class="text-sm text-gray-400">
        Menampilkan <?= $startIndex + 1 ?> - <?= min($startIndex + $perPage, $totalData) ?> dari <?= $totalData ?> teknisi
    </div>
    
    <div class="flex items-center gap-2">
        <?php if ($currentPage > 1): ?>
            <a href="?page=1" class="p-2 rounded-lg hover:bg-slate-700/50 dark:hover:bg-slate-700/50 light:hover:bg-slate-200/50 transition-all" title="Halaman Pertama">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <a href="?page=<?= $currentPage - 1 ?>" class="px-3 py-2 rounded-lg hover:bg-slate-700/50 dark:hover:bg-slate-700/50 light:hover:bg-slate-200/50 transition-all text-sm font-medium text-gray-400">
                Prev
            </a>
        <?php endif; ?>
        
        <div class="flex gap-1">
            <?php 
            $startPage = max(1, $currentPage - 2);
            $endPage = min($totalPages, $currentPage + 2);
            
            for ($i = $startPage; $i <= $endPage; $i++): ?>
                <a href="?page=<?= $i ?>" 
                   class="w-10 h-10 rounded-lg flex items-center justify-center text-sm font-medium transition-all <?= $i === $currentPage ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-slate-700/50 dark:hover:bg-slate-700/50 light:hover:bg-slate-200/50' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
        
        <?php if ($currentPage < $totalPages): ?>
            <a href="?page=<?= $currentPage + 1 ?>" class="px-3 py-2 rounded-lg hover:bg-slate-700/50 dark:hover:bg-slate-700/50 light:hover:bg-slate-200/50 transition-all text-sm font-medium text-gray-400">
                Next
            </a>
            <a href="?page=<?= $totalPages ?>" class="p-2 rounded-lg hover:bg-slate-700/50 dark:hover:bg-slate-700/50 light:hover:bg-slate-200/50 transition-all" title="Halaman Terakhir">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<!-- MODAL CONTAINER -->
<div id="modal"
     class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
    <div class="theme-card p-4 rounded w-full max-w-md border">
        <div id="modal-content"></div>
    </div>
</div>

<!-- MODAL ALASAN OFFLINE -->
<div id="modalAlasan"
     class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
    <div class="theme-card border-red-500/30 rounded-2xl p-6 w-full max-w-md border">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-red-500/20 flex items-center justify-center">
                <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold header-title">Alasan Tidak Tersedia</h3>
        </div>
        
        <div class="mb-4">
            <p class="text-xs text-gray-400 mb-2 uppercase tracking-wide">Teknisi</p>
            <p id="alasanNama" class="header-title font-semibold text-base"></p>
        </div>
        
        <div class="mb-6">
            <p class="text-xs text-gray-400 mb-2 uppercase tracking-wide">Alasan</p>
            <div class="theme-card border rounded-lg p-4 bg-slate-50/5 dark:bg-slate-900/30">
                <p id="alasanText" class="header-title text-sm leading-relaxed"></p>
            </div>
        </div>
        
        <button 
            onclick="closeAlasanModal()"
            class="w-full px-4 py-2.5 rounded-lg bg-gray-700 hover:bg-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600 text-white font-medium transition-all">
            Tutup
        </button>
    </div>
</div>

<!-- MODAL TIKET AKTIF -->
<div id="modalTiketAktif"
     class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
    <div class="theme-card border-blue-500/30 rounded-2xl p-6 w-full max-w-3xl max-h-[90vh] overflow-y-auto border">
        <div id="tiketAktifContent"></div>
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

html.light tbody td {
    color: var(--text-primary) !important;
}

html.light .text-gray-300 {
    color: var(--text-secondary) !important;
}

html.light .text-gray-400 {
    color: var(--text-muted) !important;
}

html.light .bg-gray-700 {
    background: #e2e8f0 !important;
    color: var(--text-primary) !important;
}

html.light .bg-gray-700:hover {
    background: #cbd5e1 !important;
}

html.light .bg-slate-100 {
    background: #f1f5f9 !important;
}

html.light .border-slate-200 {
    border-color: #e2e8f0 !important;
}

html.light .text-slate-900 {
    color: #0f172a !important;
}

html.light .placeholder-gray-500::placeholder {
    color: #6b7280 !important;
}

/* Search icon animation */
#searchInput:focus + #clearSearch,
#searchInput:not(:placeholder-shown) ~ #clearSearch {
    opacity: 1 !important;
}

/* Pagination responsive */
@media (max-width: 640px) {
    .flex.items-center.justify-between.mt-6 {
        flex-direction: column;
        gap: 1rem;
    }
}

/* Row animation on search */
.teknisi-row {
    animation: slideIn 0.2s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-5px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.teknisi-row.hidden {
    display: none;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const searchInput = document.getElementById('searchInput');
const clearButton = document.getElementById('clearSearch');
const tableBody = document.getElementById('tableBody');

// Search functionality
searchInput.addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase().trim();
    const rows = document.querySelectorAll('.teknisi-row');
    let visibleCount = 0;

    rows.forEach(row => {
        const nama = row.dataset.nama;
        const keahlian = row.dataset.keahlian;
        
        if (nama.includes(searchTerm) || keahlian.includes(searchTerm)) {
            row.classList.remove('hidden');
            visibleCount++;
        } else {
            row.classList.add('hidden');
        }
    });

    // Show/hide clear button
    clearButton.style.opacity = searchTerm ? '1' : '0';

    // Show empty state if no results
    if (visibleCount === 0) {
        if (!document.getElementById('emptySearch')) {
            const emptyRow = document.createElement('tr');
            emptyRow.id = 'emptySearch';
            emptyRow.innerHTML = `
                <td colspan="5" class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Tidak ada teknisi yang cocok dengan pencarian
                </td>
            `;
            tableBody.appendChild(emptyRow);
        }
    } else {
        const emptyRow = document.getElementById('emptySearch');
        if (emptyRow) emptyRow.remove();
    }
});

function clearSearch() {
    searchInput.value = '';
    searchInput.focus();
    const event = new Event('input', { bubbles: true });
    searchInput.dispatchEvent(event);
}

function openCreateModal() {
    fetch("<?= base_url('teknisi/create') ?>")
        .then(response => response.text())
        .then(html => {
            showModal(html);
        })
        .catch(err => {
            console.error(err);
            alert('Gagal membuka form tambah teknisi');
        });
}

function openEditModal(id) {
    fetch("<?= base_url('teknisi/edit/') ?>" + id)
        .then(response => response.text())
        .then(html => {
            showModal(html);
        })
        .catch(err => {
            console.error(err);
            alert('Gagal membuka form edit teknisi');
        });
}

function showModal(html) {
    document.getElementById('modal-content').innerHTML = html;

    const modal = document.getElementById('modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModal() {
    const modal = document.getElementById('modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');

    document.getElementById('modal-content').innerHTML = '';
}

// FUNGSI UNTUK MODAL ALASAN
function showAlasanModal(nama, alasan) {
    document.getElementById('alasanNama').textContent = nama;
    document.getElementById('alasanText').textContent = alasan;
    
    const modal = document.getElementById('modalAlasan');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeAlasanModal() {
    const modal = document.getElementById('modalAlasan');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// FUNGSI UNTUK MODAL TIKET AKTIF
function showTiketAktif(teknisiId) {
    fetch("<?= base_url('teknisi/tiket_aktif/') ?>" + teknisiId)
        .then(response => response.text())
        .then(html => {
            document.getElementById('tiketAktifContent').innerHTML = html;
            
            const modal = document.getElementById('modalTiketAktif');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        })
        .catch(err => {
            console.error(err);
            alert('Gagal memuat data tiket');
        });
}

function closeTiketAktifModal() {
    const modal = document.getElementById('modalTiketAktif');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.getElementById('tiketAktifContent').innerHTML = '';
}

// TUTUP MODAL SAAT KLIK DI LUAR
document.getElementById('modalAlasan')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeAlasanModal();
    }
});

document.getElementById('modalTiketAktif')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeTiketAktifModal();
    }
});

document.addEventListener('click', function (e) {
    if (e.target.classList.contains('btn-save-teknisi')) {
        const form = e.target.closest('.form-update-teknisi');
        const action = form.dataset.action;
        const data = new FormData(form);

        const isLight = document.documentElement.classList.contains('light');

        Swal.fire({
            title: 'Menyimpan...',
            allowOutsideClick: false,
            background: isLight ? '#ffffff' : '#0b1220',
            color: isLight ? '#0f172a' : '#e5e7eb',
            didOpen: () => Swal.showLoading()
        });

        fetch(action, {
            method: 'POST',
            body: data
        })
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Status teknisi diperbarui',
                    timer: 1200,
                    showConfirmButton: false,
                    background: isLight ? '#ffffff' : '#0b1220',
                    color: isLight ? '#0f172a' : '#e5e7eb'
                }).then(() => {
                    closeModal();
                    location.reload();
                });
            }
        })
        .catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Gagal menyimpan data',
                background: isLight ? '#ffffff' : '#0b1220',
                color: isLight ? '#0f172a' : '#e5e7eb'
            });
        });
    }
});

function toggleDetail(ticketId) {
    const detail = document.getElementById('detail-' + ticketId);
    const arrow = document.getElementById('arrow-' + ticketId);
    
    if (detail.classList.contains('hidden')) {
        detail.classList.remove('hidden');
        arrow.style.transform = 'rotate(180deg)';
    } else {
        detail.classList.add('hidden');
        arrow.style.transform = 'rotate(0deg)';
    }
}
</script>