<?php
// ============================
// HITUNG STATISTIK
// ============================
$all_tikets  = array_column($users, 'total_tiket');
$total_tiket = array_sum($all_tikets);
$max_tiket   = $total_user ? max($all_tikets) : 0;

$active_tikets = array_values(array_filter($all_tikets, function($v){ return $v > 0; }));
$total_active  = count($active_tikets);
$avg_active    = $total_active ? round($total_tiket / $total_active, 1) : 0;

$median = 0;
if ($total_active > 0) {
    $sorted = $active_tikets;
    sort($sorted);
    $mid    = (int)floor(($total_active - 1) / 2);
    $median = ($total_active % 2 === 0)
        ? round(($sorted[$mid] + $sorted[$mid + 1]) / 2, 1)
        : $sorted[$mid];
}

$total_unverified = count(array_filter($users, function($u){ return $u['is_verified'] == 0; }));

// ============================
// PAGINATION (server-side)
// Hanya dipakai saat TIDAK ada search
// Saat search aktif → semua data dikirim ke JS
// ============================
$current_page   = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$items_per_page = 10;
$total_pages    = max(1, ceil($total_user / $items_per_page));
if ($current_page > $total_pages) $current_page = $total_pages;
$offset          = ($current_page - 1) * $items_per_page;
$paginated_users = array_slice($users, $offset, $items_per_page);
?>

<?php if ($this->session->flashdata('error')): ?>
<div id="flash-error"
     class="mb-6 flex items-start gap-3 px-5 py-4 rounded-xl border bg-red-500/10 border-red-500/40 text-red-400"
     role="alert">
    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
    </svg>
    <span class="text-sm font-medium flex-1"><?= $this->session->flashdata('error') ?></span>
    <button onclick="this.closest('#flash-error').remove()" class="text-red-400 hover:text-red-300 flex-shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
</div>
<?php endif ?>

<?php if ($this->session->flashdata('success')): ?>
<div id="flash-success"
     class="mb-6 flex items-start gap-3 px-5 py-4 rounded-xl border bg-green-500/10 border-green-500/40 text-green-400"
     role="alert">
    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
    </svg>
    <span class="text-sm font-medium flex-1"><?= $this->session->flashdata('success') ?></span>
    <button onclick="this.closest('#flash-success').remove()" class="text-green-400 hover:text-green-300 flex-shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
</div>
<?php endif ?>

<h1 class="text-2xl font-bold mb-1 header-title">Manajemen Pengguna</h1>
<p class="text-gray-400 mb-8 header-subtitle">Pengguna yang terdaftar</p>

<!-- ============================
     INFO CARDS
     ============================ -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
    <div class="theme-card stat-card rounded-xl p-5 border hover:shadow-xl transition-all duration-300">
        <p class="text-xs mb-2 header-subtitle" style="color:#64748b">Total Pengguna</p>
        <p class="text-3xl font-bold header-title"><?= $total_user ?></p>
    </div>
    <div class="theme-card stat-card rounded-xl p-5 border hover:shadow-xl transition-all duration-300">
        <p class="text-xs mb-2 header-subtitle" style="color:#64748b">Total Aduan</p>
        <p class="text-3xl font-bold header-title"><?= $total_tiket ?></p>
    </div>
    <div class="theme-card stat-card rounded-xl p-5 border hover:shadow-xl transition-all duration-300 cursor-pointer"
         onclick="filterByStatus('unverified')" title="Klik untuk filter belum verifikasi">
        <p class="text-xs mb-2 header-subtitle" style="color:#64748b">Belum Verifikasi</p>
        <p class="text-3xl font-bold" style="color:#d97706"><?= $total_unverified ?></p>
        <p class="text-xs mt-1" style="color:#94a3b8">Klik untuk filter</p>
    </div>
    <div class="theme-card stat-card rounded-xl p-5 border hover:shadow-xl transition-all duration-300">
        <div class="flex items-start justify-between mb-2">
            <p class="text-xs header-subtitle" style="color:#64748b">Median Aduan / User</p>
            <div class="relative group">
                <svg class="w-4 h-4 cursor-help" style="color:#94a3b8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="absolute right-0 top-6 w-56 bg-slate-800 border border-slate-600 text-xs text-gray-300 rounded-lg p-3 shadow-xl z-50
                            opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 pointer-events-none">
                    <p class="font-semibold text-white mb-1">Hanya dari user aktif</p>
                    <p>User tanpa tiket dikeluarkan agar tidak bias ke 0.</p>
                </div>
            </div>
        </div>
        <p class="text-3xl font-bold header-title"><?= $median ?></p>
        <p class="text-xs mt-1" style="color:#94a3b8">Rata-rata aktif: <span style="color:#64748b"><?= $avg_active ?></span></p>
    </div>
</div>

<!-- ============================
     SEARCH & ACTION BAR
     ============================ -->
<div class="mb-6">
    <div class="flex flex-col md:flex-row gap-4 items-stretch md:items-center justify-between mb-4">
        <div class="flex-1 w-full md:max-w-md relative group">
            <div class="relative">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-5 h-5 pointer-events-none transition-colors search-icon"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="searchInput"
                       placeholder="Cari nama, NIP, atau email..."
                       class="w-full pl-11 pr-10 py-3 rounded-xl border-2 transition-all duration-200 search-input focus:outline-none shadow-sm">
                <button id="clearSearchBtn" onclick="clearSearchInput()"
                        class="absolute right-3 top-1/2 -translate-y-1/2 opacity-0 transition-all p-1 clear-btn">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        <button type="button" onclick="openUserModal(event)"
                class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800
                       px-6 py-3 rounded-xl text-sm font-semibold text-white
                       transition-all duration-200 flex items-center gap-2 flex-shrink-0
                       w-full md:w-auto justify-center shadow-md hover:shadow-lg active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Pengguna
        </button>
    </div>

    <!-- Info bar -->
    <div id="infoBar" class="text-sm info-text">
        Menampilkan <span id="infoShowing" class="font-semibold info-count"><?= count($paginated_users) ?></span>
        dari <span class="font-semibold info-count"><?= $total_user ?></span> pengguna
    </div>
</div>

<!-- ============================
     TABLE
     ============================ -->
<div class="theme-card rounded-xl overflow-hidden shadow-xl table-card-border">
<table class="w-full text-sm">
<thead>
<tr>
    <th class="px-5 py-4 text-left table-th">Pengguna</th>
    <th class="px-5 py-4 text-left table-th">NIP</th>
    <th class="px-5 py-4 text-center table-th">Status</th>
    <th class="px-5 py-4 text-center table-th">Aduan</th>
    <th class="px-5 py-4 text-center table-th">Aksi</th>
</tr>
</thead>
<tbody id="tableBody">
<?php foreach ($paginated_users as $u): ?>
<?php $is_active = $u['is_verified'] == 1; ?>
<tr class="table-row user-row"
    data-id="<?= $u['id'] ?>"
    data-nama="<?= strtolower(htmlspecialchars($u['name'])) ?>"
    data-email="<?= strtolower(htmlspecialchars($u['email'])) ?>"
    data-nip="<?= strtolower(htmlspecialchars($u['nip'] ?? '')) ?>"
    data-status="<?= $is_active ? 'active' : 'unverified' ?>">

    <td class="px-5 py-4">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center font-bold text-white text-sm flex-shrink-0 shadow-sm">
                <?= strtoupper(substr($u['name'], 0, 1)) ?>
            </div>
            <div>
                <p class="user-name text-sm font-semibold"><?= htmlspecialchars($u['name']) ?></p>
                <p class="user-email text-xs"><?= htmlspecialchars($u['email']) ?></p>
            </div>
        </div>
    </td>

    <td class="px-5 py-4">
        <?php if (!empty($u['nip'])): ?>
            <span class="nip-badge"><?= htmlspecialchars($u['nip']) ?></span>
        <?php else: ?>
            <span class="nip-empty">—</span>
        <?php endif ?>
    </td>

    <td class="px-5 py-4 text-center">
        <?php if ($is_active): ?>
            <span class="status-badge badge-aktif">
                <span class="badge-dot"></span>Aktif
            </span>
        <?php else: ?>
            <span class="status-badge badge-unverif">
                <span class="badge-dot"></span>Belum Verifikasi
            </span>
        <?php endif ?>
    </td>

    <td class="px-5 py-4 text-center">
        <button type="button"
                onclick="openTicketModal(<?= $u['id'] ?>, '<?= htmlspecialchars($u['name'], ENT_QUOTES) ?>')"
                class="btn-tiket px-3 py-1.5 rounded-full text-xs font-semibold transition-all cursor-pointer">
            <?= (int)$u['total_tiket'] ?> tiket
        </button>
    </td>

    <td class="px-5 py-4 text-center">
        <div class="flex justify-center gap-1">
            <button onclick="toggleStatus(<?= $u['id'] ?>, <?= $is_active ? 'true' : 'false' ?>, '<?= htmlspecialchars($u['name'], ENT_QUOTES) ?>')"
                    class="btn-action p-2 rounded-lg <?= $is_active ? 'btn-toggle-off' : 'btn-toggle-on' ?>"
                    title="<?= $is_active ? 'Nonaktifkan' : 'Aktifkan' ?> akun">
                <?php if ($is_active): ?>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
                <?php else: ?>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <?php endif ?>
            </button>

            <button onclick="openEditUserModal(<?= $u['id'] ?>)"
                    class="btn-action btn-edit p-2 rounded-lg"
                    title="Edit Pengguna">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </button>

            <form action="<?= site_url('index.php/user/delete/' . $u['id']) ?>"
                  method="post"
                  onsubmit="return confirmDelete(event, '<?= htmlspecialchars($u['name'], ENT_QUOTES) ?>')"
                  class="inline">
                <button type="submit"
                        class="btn-action btn-hapus p-2 rounded-lg"
                        title="Hapus Pengguna">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </form>
        </div>
    </td>
</tr>
<?php endforeach ?>
</tbody>
</table>
</div>

<!-- ============================
     PAGINATION (server-side, disembunyikan saat search aktif)
     ============================ -->
<div id="paginationServer" class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-6 <?= $total_pages <= 1 ? 'hidden' : '' ?>">
    <div class="text-sm info-text">
        Halaman <span class="font-bold info-count"><?= $current_page ?></span>
        dari <span class="font-bold info-count"><?= $total_pages ?></span>
        <span class="mx-2" style="color:#94a3b8">•</span><?= $total_user ?> pengguna
    </div>
    <div class="flex gap-2 flex-wrap items-center justify-center">
        <?php if ($current_page > 1): ?>
            <a href="?page=<?= $current_page - 1 ?>" class="page-btn px-3.5 py-2 rounded-lg border-2 text-sm font-semibold transition-all flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                Prev
            </a>
        <?php endif ?>
        <?php
        $start_p = max(1, $current_page - 2);
        $end_p   = min($total_pages, $current_page + 2);
        for ($i = $start_p; $i <= $end_p; $i++):
        ?>
            <?php if ($i === $current_page): ?>
                <span class="page-btn-active w-10 h-10 rounded-lg text-sm font-bold flex items-center justify-center"><?= $i ?></span>
            <?php else: ?>
                <a href="?page=<?= $i ?>" class="page-btn w-10 h-10 rounded-lg text-sm font-semibold border-2 transition-all flex items-center justify-center"><?= $i ?></a>
            <?php endif ?>
        <?php endfor ?>
        <?php if ($current_page < $total_pages): ?>
            <a href="?page=<?= $current_page + 1 ?>" class="page-btn px-3.5 py-2 rounded-lg border-2 text-sm font-semibold transition-all flex items-center gap-1.5">
                Next
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </a>
        <?php endif ?>
    </div>
</div>

<!-- Pagination JS (muncul saat search aktif) -->
<div id="paginationJS" class="hidden mt-8 flex flex-col sm:flex-row items-center justify-between gap-6">
    <div class="text-sm info-text" id="jsPageInfo"></div>
    <div class="flex gap-2 flex-wrap items-center justify-center" id="jsPageControls"></div>
</div>

<?php $this->load->view('user/modal_create'); ?>
<?php $this->load->view('user/modal_tickets'); ?>

<!-- MODAL EDIT -->
<div id="userEditModal"
     class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50"
     onclick="handleUserEditModalBackdropClick(event)">
    <div class="theme-card rounded-xl w-full max-w-md p-6 border shadow-2xl" onclick="event.stopPropagation()">
        <div id="userEditModalContent"></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- ============================
     ALL USERS DATA untuk JS search
     Dikirim semua dari server, search/paginate di JS
     ============================ -->
<script>
const ALL_USERS = <?= json_encode(array_map(function($u) {
    return [
        'id'          => $u['id'],
        'name'        => $u['name'],
        'email'       => $u['email'],
        'nip'         => $u['nip'] ?? '',
        'telegram_id' => $u['telegram_id'] ?? '',
        'is_verified' => (int)$u['is_verified'],
        'total_tiket' => (int)$u['total_tiket'],
        'unit_kerja'  => $u['unit_kerja'] ?? '',
    ];
}, $users), JSON_UNESCAPED_UNICODE) ?>;

const SITE_URL     = '<?= site_url() ?>';
const DELETE_URL   = '<?= site_url('index.php/user/delete/') ?>';
const TOGGLE_URL   = '<?= site_url('index.php/user/toggle_status/') ?>';
const TICKET_URL   = '<?= site_url('index.php/user/tickets/') ?>';
const EDIT_URL     = '<?= base_url('user/edit/') ?>';
const ITEMS_PER_PAGE = 10;
</script>

<style>
.swal2-container { z-index: 100000 !important; }

/* ============================
   SEARCH INPUT
   ============================ */
.search-input {
    font-size: 14px;
    transition: all 0.2s ease;
}
html.light .search-input {
    background: #ffffff;
    color: #0f172a;
    border-color: #cbd5e1;
}
html.light .search-input::placeholder { color: #94a3b8; }
html.light .search-input:hover  { border-color: #94a3b8; box-shadow: 0 2px 6px rgba(0,0,0,0.05); }
html.light .search-input:focus  { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.10); }
html.dark  .search-input {
    background: #1e293b;
    color: #f1f5f9;
    border-color: #334155;
}
html.dark .search-input::placeholder { color: #64748b; }
html.dark .search-input:hover  { border-color: #475569; }
html.dark .search-input:focus  { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.08); }

.search-icon { color: #94a3b8; }
html.light .clear-btn { color: #64748b; }
html.dark  .clear-btn { color: #64748b; }
html.light .clear-btn:hover { color: #0f172a; }
html.dark  .clear-btn:hover { color: #e2e8f0; }

/* ============================
   INFO TEXT
   ============================ */
html.light .info-text  { color: #475569; }
html.dark  .info-text  { color: #94a3b8; }
html.light .info-count { color: #0f172a; }
html.dark  .info-count { color: #f1f5f9; }

/* ============================
   TABLE
   ============================ */
html.light .table-card-border { border: 1px solid #e2e8f0; box-shadow: 0 1px 4px rgba(0,0,0,0.06); }
html.dark  .table-card-border { border: 1px solid rgba(51,65,85,0.5); box-shadow: 0 4px 24px rgba(0,0,0,0.3); }

html.light thead { background: #f1f5f9 !important; border-bottom: 2px solid #e2e8f0; }
html.dark  thead { background: rgba(15,23,42,0.95) !important; border-bottom: 1px solid #1e293b; }

html.light .table-th { color: #475569 !important; font-weight: 600; letter-spacing: 0.05em; font-size: 11px; }
html.dark  .table-th { color: #64748b !important; font-weight: 600; letter-spacing: 0.05em; font-size: 11px; }

html.light .table-row { border-bottom: 1px solid #f1f5f9; background: #ffffff; }
html.dark  .table-row { border-bottom: 1px solid #1e293b; }
html.light .table-row:hover { background: #f8fafc !important; }
html.dark  .table-row:hover { background: rgba(30,41,59,0.5) !important; }

/* ============================
   USER NAME & EMAIL
   ============================ */
html.light .user-name  { color: #0f172a; }
html.dark  .user-name  { color: #f1f5f9; }
html.light .user-email { color: #64748b; }
html.dark  .user-email { color: #94a3b8; }

/* ============================
   NIP BADGE
   ============================ */
html.light .nip-badge {
    background: #e2e8f0;
    color: #334155;
    border: 1px solid #cbd5e1;
    font-family: monospace;
    font-size: 11px;
    padding: 3px 8px;
    border-radius: 6px;
    display: inline-block;
    letter-spacing: 0.03em;
}
html.dark .nip-badge {
    background: rgba(15,23,42,0.7);
    color: #94a3b8;
    border: 1px solid #334155;
    font-family: monospace;
    font-size: 11px;
    padding: 3px 8px;
    border-radius: 6px;
    display: inline-block;
    letter-spacing: 0.03em;
}
html.light .nip-empty { color: #94a3b8; }
html.dark  .nip-empty { color: #475569; }

/* ============================
   STATUS BADGE
   ============================ */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
}
.badge-dot {
    width: 6px; height: 6px;
    border-radius: 50%;
    display: inline-block;
    flex-shrink: 0;
}

html.light .badge-aktif   { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }
html.light .badge-aktif   .badge-dot { background: #16a34a; }
html.dark  .badge-aktif   { background: rgba(34,197,94,0.15); color: #4ade80; border: 1px solid rgba(34,197,94,0.3); }
html.dark  .badge-aktif   .badge-dot { background: #4ade80; }

html.light .badge-unverif { background: #fef9c3; color: #92400e; border: 1px solid #fde047; }
html.light .badge-unverif .badge-dot { background: #d97706; }
html.dark  .badge-unverif { background: rgba(234,179,8,0.15); color: #facc15; border: 1px solid rgba(234,179,8,0.3); }
html.dark  .badge-unverif .badge-dot { background: #facc15; }

/* ============================
   TIKET BUTTON
   ============================ */
html.light .btn-tiket { background: #dbeafe; color: #1d4ed8; border: 1px solid #93c5fd; }
html.light .btn-tiket:hover { background: #bfdbfe; color: #1e40af; }
html.dark  .btn-tiket { background: rgba(37,99,235,0.2); color: #60a5fa; border: 1px solid rgba(37,99,235,0.3); }
html.dark  .btn-tiket:hover { background: rgba(37,99,235,0.3); }

/* ============================
   ACTION BUTTONS
   ============================ */
.btn-action { transition: all 0.15s; }
html.light .btn-action { color: #94a3b8; }
html.dark  .btn-action { color: #475569; }

html.light .btn-toggle-off        { color: #94a3b8; }
html.light .btn-toggle-off:hover  { color: #d97706; background: #fef3c7; }
html.light .btn-toggle-on         { color: #d97706; }
html.light .btn-toggle-on:hover   { color: #15803d; background: #dcfce7; }
html.light .btn-edit:hover        { color: #d97706; background: #fef3c7; }
html.light .btn-hapus:hover       { color: #dc2626; background: #fee2e2; }

html.dark  .btn-toggle-off        { color: #475569; }
html.dark  .btn-toggle-off:hover  { color: #fbbf24; background: rgba(251,191,36,0.1); }
html.dark  .btn-toggle-on         { color: #facc15; }
html.dark  .btn-toggle-on:hover   { color: #4ade80; background: rgba(74,222,128,0.1); }
html.dark  .btn-edit:hover        { color: #fbbf24; background: rgba(251,191,36,0.1); }
html.dark  .btn-hapus:hover       { color: #f87171; background: rgba(248,113,113,0.1); }

/* ============================
   STAT CARDS
   ============================ */
html.light .stat-card { background: #ffffff; border-color: #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.06); }
html.light .stat-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.10); }
html.dark  .stat-card { border-color: #1e293b; }

/* ============================
   PAGINATION
   ============================ */
html.light .page-btn {
    background: #ffffff; border-color: #cbd5e1; color: #334155;
}
html.light .page-btn:hover { background: #f1f5f9; border-color: #94a3b8; }
html.light .page-btn-active { background: #2563eb; border: 2px solid #2563eb; color: #ffffff; box-shadow: 0 2px 6px rgba(37,99,235,0.3); }

html.dark .page-btn {
    background: #1e293b; border-color: #334155; color: #94a3b8;
}
html.dark .page-btn:hover { background: #0f172a; border-color: #475569; color: #e2e8f0; }
html.dark .page-btn-active { background: #2563eb; border: 2px solid #2563eb; color: #ffffff; }

/* ============================
   EMPTY STATE
   ============================ */
html.light .empty-state-text { color: #94a3b8; }
html.dark  .empty-state-text { color: #475569; }

/* ============================
   FLASH ALERT
   ============================ */
#flash-error, #flash-success { animation: slideDown 0.3s ease; }
@keyframes slideDown {
    from { opacity: 0; transform: translateY(-8px); }
    to   { opacity: 1; transform: translateY(0); }
}

@media (max-width: 768px) {
    .search-input { font-size: 16px; }
    .grid { grid-template-columns: 1fr 1fr; }
}
</style>

<script>
// ============================
// THEME
// ============================
function isLight() { return document.documentElement.classList.contains('light'); }
function swalTheme() {
    return {
        background: isLight() ? '#ffffff' : '#0b1220',
        color:      isLight() ? '#0f172a'  : '#e5e7eb',
    };
}

// ============================
// STATE
// ============================
let currentQuery    = '';
let activeFilter    = null;  // null | 'unverified'
let jsCurrentPage   = 1;
let isSearchMode    = false;

const tableBody       = document.getElementById('tableBody');
const searchInput     = document.getElementById('searchInput');
const clearBtn        = document.getElementById('clearSearchBtn');
const infoBar         = document.getElementById('infoBar');
const infoShowing     = document.getElementById('infoShowing');
const paginationServer = document.getElementById('paginationServer');
const paginationJS    = document.getElementById('paginationJS');
const jsPageInfo      = document.getElementById('jsPageInfo');
const jsPageControls  = document.getElementById('jsPageControls');

// ============================
// RENDER ROW dari data JS
// ============================
function renderRow(u) {
    const isActive  = u.is_verified === 1;
    const initial   = u.name.charAt(0).toUpperCase();
    const nipHtml   = u.nip
        ? `<span class="nip-badge">${escHtml(u.nip)}</span>`
        : `<span class="nip-empty">—</span>`;
    const statusHtml = isActive
        ? `<span class="status-badge badge-aktif"><span class="badge-dot"></span>Aktif</span>`
        : `<span class="status-badge badge-unverif"><span class="badge-dot"></span>Belum Verifikasi</span>`;
    const toggleIcon = isActive
        ? `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>`
        : `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`;
    const toggleClass = isActive ? 'btn-toggle-off' : 'btn-toggle-on';
    const toggleTitle = isActive ? 'Nonaktifkan' : 'Aktifkan';
    const nameEsc     = escAttr(u.name);

    return `<tr class="table-row user-row"
                data-id="${u.id}"
                data-nama="${escAttr(u.name.toLowerCase())}"
                data-email="${escAttr(u.email.toLowerCase())}"
                data-nip="${escAttr((u.nip||'').toLowerCase())}"
                data-status="${isActive ? 'active' : 'unverified'}">
        <td class="px-5 py-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center font-bold text-white text-sm flex-shrink-0 shadow-sm">
                    ${initial}
                </div>
                <div>
                    <p class="user-name text-sm font-semibold">${escHtml(u.name)}</p>
                    <p class="user-email text-xs">${escHtml(u.email)}</p>
                </div>
            </div>
        </td>
        <td class="px-5 py-4">${nipHtml}</td>
        <td class="px-5 py-4 text-center">${statusHtml}</td>
        <td class="px-5 py-4 text-center">
            <button type="button" onclick="openTicketModal(${u.id}, '${nameEsc}')"
                    class="btn-tiket px-3 py-1.5 rounded-full text-xs font-semibold transition-all cursor-pointer">
                ${u.total_tiket} tiket
            </button>
        </td>
        <td class="px-5 py-4 text-center">
            <div class="flex justify-center gap-1">
                <button onclick="toggleStatus(${u.id}, ${isActive}, '${nameEsc}')"
                        class="btn-action p-2 rounded-lg ${toggleClass}" title="${toggleTitle} akun">
                    ${toggleIcon}
                </button>
                <button onclick="openEditUserModal(${u.id})"
                        class="btn-action btn-edit p-2 rounded-lg" title="Edit Pengguna">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </button>
                <button onclick="confirmDeleteJS(${u.id}, '${nameEsc}')"
                        class="btn-action btn-hapus p-2 rounded-lg" title="Hapus Pengguna">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
        </td>
    </tr>`;
}

// ============================
// FILTER + RENDER TABLE
// ============================
function getFiltered() {
    const q = currentQuery.toLowerCase().trim();
    return ALL_USERS.filter(u => {
        const matchQ = !q ||
            u.name.toLowerCase().includes(q) ||
            u.email.toLowerCase().includes(q) ||
            (u.nip || '').toLowerCase().includes(q);
        const matchS = !activeFilter || (activeFilter === 'unverified' && u.is_verified === 0);
        return matchQ && matchS;
    });
}

function renderTable() {
    const filtered   = getFiltered();
    const totalFil   = filtered.length;
    const totalPages = Math.max(1, Math.ceil(totalFil / ITEMS_PER_PAGE));
    if (jsCurrentPage > totalPages) jsCurrentPage = totalPages;

    const start  = (jsCurrentPage - 1) * ITEMS_PER_PAGE;
    const sliced = filtered.slice(start, start + ITEMS_PER_PAGE);

    if (sliced.length === 0) {
        tableBody.innerHTML = `
            <tr><td colspan="5" class="px-6 py-12 text-center">
                <svg class="w-12 h-12 mx-auto mb-3 empty-state-text opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <p class="text-sm empty-state-text">Tidak ada pengguna yang cocok</p>
            </td></tr>`;
    } else {
        tableBody.innerHTML = sliced.map(renderRow).join('');
    }

    // Info bar
    infoBar.innerHTML = isSearchMode
        ? `Ditemukan <span class="font-semibold info-count">${totalFil}</span> pengguna` +
          (activeFilter ? ` <span style="color:#94a3b8">(filter: belum verifikasi)</span>` : '') +
          ` &nbsp;<button onclick="clearAll()" class="text-xs underline" style="color:#ef4444">Hapus filter</button>`
        : `Menampilkan <span class="font-semibold info-count">${sliced.length}</span> dari <span class="font-semibold info-count">${ALL_USERS.length}</span> pengguna`;

    // Pagination
    if (isSearchMode) {
        paginationServer.classList.add('hidden');
        if (totalPages > 1) {
            paginationJS.classList.remove('hidden');
            paginationJS.classList.add('flex');
            renderJSPagination(jsCurrentPage, totalPages, totalFil);
        } else {
            paginationJS.classList.add('hidden');
            paginationJS.classList.remove('flex');
        }
    } else {
        paginationJS.classList.add('hidden');
        paginationJS.classList.remove('flex');
        // server pagination sudah di-render PHP
    }
}

function renderJSPagination(cur, total, totalFil) {
    jsPageInfo.innerHTML =
        `Halaman <span class="font-bold info-count">${cur}</span> dari <span class="font-bold info-count">${total}</span>
         <span class="mx-2" style="color:#94a3b8">•</span>${totalFil} pengguna`;

    let html = '';
    if (cur > 1) {
        html += `<button onclick="goPage(${cur-1})" class="page-btn px-3.5 py-2 rounded-lg border-2 text-sm font-semibold transition-all flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>Prev</button>`;
    }
    const s = Math.max(1, cur-2), e = Math.min(total, cur+2);
    for (let i = s; i <= e; i++) {
        if (i === cur) {
            html += `<span class="page-btn-active w-10 h-10 rounded-lg text-sm font-bold flex items-center justify-center">${i}</span>`;
        } else {
            html += `<button onclick="goPage(${i})" class="page-btn w-10 h-10 rounded-lg border-2 text-sm font-semibold transition-all flex items-center justify-center">${i}</button>`;
        }
    }
    if (cur < total) {
        html += `<button onclick="goPage(${cur+1})" class="page-btn px-3.5 py-2 rounded-lg border-2 text-sm font-semibold transition-all flex items-center gap-1.5">Next
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg></button>`;
    }
    jsPageControls.innerHTML = html;
}

function goPage(p) {
    jsCurrentPage = p;
    renderTable();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ============================
// SEARCH INPUT
// ============================
searchInput.addEventListener('input', function() {
    currentQuery  = this.value;
    isSearchMode  = currentQuery.trim() !== '' || activeFilter !== null;
    jsCurrentPage = 1;
    clearBtn.style.opacity = currentQuery ? '1' : '0';
    if (isSearchMode) {
        paginationServer.classList.add('hidden');
        renderTable();
    } else {
        // Kembali ke server-side — reload halaman 1
        window.location.href = window.location.pathname;
    }
});

function clearSearchInput() {
    searchInput.value = '';
    currentQuery  = '';
    isSearchMode  = activeFilter !== null;
    jsCurrentPage = 1;
    clearBtn.style.opacity = '0';
    if (!isSearchMode) {
        window.location.href = window.location.pathname;
    } else {
        renderTable();
    }
}

function clearAll() {
    searchInput.value = '';
    currentQuery  = '';
    activeFilter  = null;
    isSearchMode  = false;
    jsCurrentPage = 1;
    clearBtn.style.opacity = '0';
    window.location.href = window.location.pathname;
}

function filterByStatus(status) {
    activeFilter  = status;
    isSearchMode  = true;
    jsCurrentPage = 1;
    paginationServer.classList.add('hidden');
    renderTable();
    window.scrollTo({ top: document.getElementById('tableBody').offsetTop - 100, behavior: 'smooth' });
}

// ============================
// HELPER ESCAPE
// ============================
function escHtml(str) {
    return String(str)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;')
        .replace(/'/g,'&#039;');
}
function escAttr(str) {
    return String(str).replace(/'/g,"\\'").replace(/"/g,'&quot;');
}

// ============================
// TOGGLE STATUS
// ============================
function toggleStatus(id, isActive, name) {
    const action       = isActive ? 'nonaktifkan' : 'aktifkan';
    const confirmColor = isActive ? '#d97706' : '#16a34a';
    Swal.fire({
        ...swalTheme(),
        title: (isActive ? 'Nonaktifkan' : 'Aktifkan') + ' akun?',
        html: `Akun <strong>${name}</strong> akan di${action}.`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, ' + action,
        cancelButtonText: 'Batal',
        confirmButtonColor: confirmColor,
        cancelButtonColor: '#334155',
        reverseButtons: true,
    }).then(result => {
        if (!result.isConfirmed) return;
        fetch(TOGGLE_URL + id, { method: 'POST' })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                // Update data di ALL_USERS supaya re-render benar
                const idx = ALL_USERS.findIndex(u => u.id === id);
                if (idx !== -1) ALL_USERS[idx].is_verified = isActive ? 0 : 1;

                Swal.fire({
                    ...swalTheme(), icon:'success', title:'Berhasil!',
                    text: data.message, timer:1200, showConfirmButton:false
                }).then(() => {
                    if (isSearchMode) renderTable();
                    else location.reload();
                });
            } else {
                Swal.fire({ ...swalTheme(), icon:'error', title:'Gagal!', text:data.message });
            }
        })
        .catch(() => Swal.fire({ ...swalTheme(), icon:'error', title:'Kesalahan sistem!' }));
    });
}

// ============================
// CREATE USER MODAL
// ============================
function openUserModal(e) {
    if (e) { e.preventDefault(); e.stopPropagation(); }
    const m = document.getElementById('userModal');
    if (m) { m.classList.remove('hidden'); m.classList.add('flex'); }
}
function closeUserModal() {
    const m = document.getElementById('userModal');
    const f = document.getElementById('createUserForm');
    if (f) f.reset();
    if (m) { m.classList.add('hidden'); m.classList.remove('flex'); }
}

// ============================
// EDIT USER MODAL
// ============================
function openEditUserModal(id) {
    fetch(EDIT_URL + id)
        .then(r => r.text())
        .then(html => {
            document.getElementById('userEditModalContent').innerHTML = html;
            const m = document.getElementById('userEditModal');
            m.classList.remove('hidden'); m.classList.add('flex');
            setupEditUserFormHandler();
        })
        .catch(() => Swal.fire({ ...swalTheme(), icon:'error', title:'Gagal memuat form edit!' }));
}
function closeUserEditModal() {
    const m = document.getElementById('userEditModal');
    m.classList.add('hidden'); m.classList.remove('flex');
    document.getElementById('userEditModalContent').innerHTML = '';
}
function handleUserEditModalBackdropClick(e) {
    if (e.target.id === 'userEditModal') closeUserEditModal();
}
function setupEditUserFormHandler() {
    const form = document.getElementById('editUserForm');
    if (!form) return;
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const submitBtn  = document.getElementById('editUserSubmitBtn');
        const btnText    = document.getElementById('editUserBtnText');
        const btnLoading = document.getElementById('editUserBtnLoading');
        const alertBox   = document.getElementById('editUserAlert');
        submitBtn.disabled = true;
        btnText.classList.add('hidden');
        btnLoading.classList.remove('hidden');
        fetch(form.action, {
            method: 'POST', body: new FormData(form),
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.text())
        .then(text => {
            let data;
            try { data = JSON.parse(text); } catch(err) {
                showEditAlert(alertBox, false, 'Response error!');
                resetEditBtn(submitBtn, btnText, btnLoading); return;
            }
            if (data.success) {
                showEditAlert(alertBox, true, data.message);
                setTimeout(() => location.reload(), 1000);
            } else {
                showEditAlert(alertBox, false, data.message);
                resetEditBtn(submitBtn, btnText, btnLoading);
            }
        })
        .catch(() => { showEditAlert(alertBox, false, 'Kesalahan sistem!'); resetEditBtn(submitBtn, btnText, btnLoading); });
    });
}
function showEditAlert(box, ok, msg) {
    box.className = ok
        ? 'rounded-lg p-4 mb-4 bg-green-500/10 border border-green-500/50 text-green-400'
        : 'rounded-lg p-4 mb-4 bg-red-500/10 border border-red-500/50 text-red-400';
    const icon = ok
        ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>'
        : '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>';
    box.innerHTML = `<div class="flex items-center gap-2"><svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">${icon}</svg><span>${msg}</span></div>`;
    box.classList.remove('hidden');
}
function resetEditBtn(btn, text, loading) {
    btn.disabled = false; text.classList.remove('hidden'); loading.classList.add('hidden');
}

// ============================
// CONFIRM DELETE
// ============================
function confirmDelete(e, name) {
    e.preventDefault();
    const form = e.target;
    Swal.fire({
        ...swalTheme(), title:'Hapus pengguna?',
        html:`Akun <strong>${name}</strong> akan dihapus permanen.`,
        icon:'warning', showCancelButton:true,
        confirmButtonText:'Ya, Hapus', cancelButtonText:'Batal',
        confirmButtonColor:'#dc2626', cancelButtonColor:'#334155', reverseButtons:true,
    }).then(r => { if (r.isConfirmed) form.submit(); });
    return false;
}

// Delete via JS (dipakai saat search mode)
function confirmDeleteJS(id, name) {
    Swal.fire({
        ...swalTheme(), title:'Hapus pengguna?',
        html:`Akun <strong>${name}</strong> akan dihapus permanen.`,
        icon:'warning', showCancelButton:true,
        confirmButtonText:'Ya, Hapus', cancelButtonText:'Batal',
        confirmButtonColor:'#dc2626', cancelButtonColor:'#334155', reverseButtons:true,
    }).then(r => {
        if (r.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'post';
            form.action = DELETE_URL + id;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// ============================
// CREATE — SweetAlert confirm
// ============================
function confirmCancel() {
    const form = document.getElementById('createUserForm');
    if (!form) return;
    const hasData = Array.from(form.querySelectorAll('input')).some(i => i.value.trim() !== '');
    if (!hasData) { closeUserModal(); return; }
    Swal.fire({
        ...swalTheme(), title:'Batalkan perubahan?', text:'Data yang diisi akan hilang.',
        icon:'warning', showCancelButton:true, confirmButtonText:'Ya, Batalkan',
        cancelButtonText:'Tidak', reverseButtons:true,
        confirmButtonColor:'#dc2626', cancelButtonColor:'#334155',
    }).then(r => { if (r.isConfirmed) closeUserModal(); });
}
function confirmSubmit() {
    const form = document.getElementById('createUserForm');
    if (!form) return;
    if (!form.checkValidity()) { form.reportValidity(); return; }
    Swal.fire({
        ...swalTheme(), title:'Simpan pengguna?', text:'Pastikan data sudah benar.',
        icon:'question', showCancelButton:true, confirmButtonText:'Simpan',
        cancelButtonText:'Batal', confirmButtonColor:'#2563eb', cancelButtonColor:'#334155',
    }).then(r => {
        if (r.isConfirmed) {
            Swal.fire({ ...swalTheme(), title:'Menyimpan...', text:'Mohon tunggu', allowOutsideClick:false, didOpen:()=>Swal.showLoading() });
            form.submit();
        }
    });
}

// ============================
// TICKET MODAL
// ============================
function openTicketModal(userId, userName) {
    const modal   = document.getElementById('ticketModal');
    const content = document.getElementById('ticketContent');
    if (!modal) return;
    document.getElementById('ticketUserName').innerText = userName;
    modal.classList.remove('hidden'); modal.classList.add('flex');
    content.innerHTML = '<p class="text-sm" style="color:#94a3b8">Memuat data...</p>';
    fetch(TICKET_URL + userId)
        .then(r => r.json())
        .then(data => {
            if (!data || data.length === 0) {
                content.innerHTML = '<p class="text-sm text-center py-4" style="color:#94a3b8">Belum ada tiket.</p>';
                return;
            }
            content.innerHTML = data.map(t => `
                <div class="border border-slate-700/50 rounded-lg p-3 hover:bg-slate-800/40 transition-all">
                    <div class="flex justify-between items-center mb-1">
                        <p class="font-semibold text-blue-400 text-sm">${t.kode_tiket}</p>
                        <span class="text-xs px-2 py-0.5 rounded-full bg-slate-700 text-gray-300">${t.status}</span>
                    </div>
                    <p class="text-sm text-gray-300">${t.jenis_kerusakan}</p>
                    <p class="text-xs text-gray-500 mt-1">${t.created_at}</p>
                </div>
            `).join('');
        })
        .catch(() => { content.innerHTML = '<p class="text-red-400 text-sm">Gagal memuat tiket.</p>'; });
}
function closeTicketModal() {
    const m = document.getElementById('ticketModal');
    if (m) { m.classList.add('hidden'); m.classList.remove('flex'); }
}

// ============================
// ESC KEY
// ============================
document.addEventListener('keydown', function(e) {
    if (e.key !== 'Escape') return;
    const cm = document.getElementById('userModal');
    if (cm && !cm.classList.contains('hidden')) { confirmCancel(); return; }
    const em = document.getElementById('userEditModal');
    if (em && !em.classList.contains('hidden')) { closeUserEditModal(); return; }
    const tm = document.getElementById('ticketModal');
    if (tm && !tm.classList.contains('hidden')) closeTicketModal();
});

// ============================
// AUTO-DISMISS FLASH 5s
// ============================
document.addEventListener('DOMContentLoaded', function() {
    ['flash-error','flash-success'].forEach(id => {
        const el = document.getElementById(id);
        if (el) setTimeout(() => {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        }, 5000);
    });
});
</script>