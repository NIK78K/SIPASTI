<?php
// HITUNG DATA ROLE
$totalAdmin      = count($admins);
$adminCount      = count(array_filter($admins, fn($a) => $a['role'] === 'admin'));
$superAdminCount = count(array_filter($admins, fn($a) => $a['role'] === 'superadmin'));
?>

<h1 class="text-2xl font-bold mb-1 header-title">Manajemen Admin</h1>
<p class="text-gray-400 mb-8 header-subtitle">Manajemen data administrator sistem</p>

<!-- INFO CARD -->
<div class="grid grid-cols-3 gap-6 mb-10">

    <div class="theme-card stat-card rounded-xl p-6 border hover:shadow-xl transition-all duration-300">
        <p class="text-gray-400 text-sm mb-2 header-subtitle">Total Admin</p>
        <p class="text-4xl font-bold header-title"><?= $totalAdmin ?></p>
    </div>

    <div class="theme-card stat-card rounded-xl p-6 border hover:shadow-xl transition-all duration-300">
        <p class="text-gray-400 text-sm mb-2 header-subtitle">Admin</p>
        <p class="text-4xl font-bold header-title"><?= $adminCount ?></p>
    </div>

    <div class="theme-card stat-card rounded-xl p-6 border hover:shadow-xl transition-all duration-300">
        <p class="text-gray-400 text-sm mb-2 header-subtitle">Super Admin</p>
        <p class="text-4xl font-bold text-purple-500"><?= $superAdminCount ?></p>
    </div>

</div>

<!-- ACTION -->
<div class="flex justify-between items-center mb-4">
    <h2 class="text-lg font-semibold header-title">Daftar Admin</h2>

    <!-- MODAL CREATE -->
    <button onclick="openCreateAdminModal()"
        class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg text-sm font-semibold transition-all">
        + Tambah Admin
    </button>
</div>

<!-- TABLE -->
<div class="theme-card rounded-xl overflow-hidden shadow-xl border">
<table class="w-full text-sm">

<thead class="bg-slate-900/90 text-gray-400 uppercase text-xs tracking-wider">
<tr>
    <th class="px-6 py-4 text-left">Admin</th>
    <th class="px-6 py-4 text-center">Email</th>
    <th class="px-6 py-4 text-center">Role</th>
    <th class="px-6 py-4 text-center">Dibuat</th>
    <th class="px-6 py-4 text-center">Aksi</th>
</tr>
</thead>

<tbody>
<?php if (empty($admins)): ?>
<tr>
    <td colspan="5" class="text-center py-6 text-gray-500">
        Belum ada admin
    </td>
</tr>
<?php else: foreach ($admins as $a): ?>

<tr class="border-b border-slate-700/50 hover:bg-slate-800/40 transition-all">

    <!-- ADMIN -->
    <td class="px-6 py-4 flex items-center gap-4">
        <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center font-bold text-white">
            <?= strtoupper(substr($a['name'], 0, 1)) ?>
        </div>
        <div>
            <p class="font-semibold header-title"><?= $a['name'] ?></p>
            <p class="text-xs text-gray-400">ID #<?= $a['id'] ?></p>
        </div>
    </td>

    <!-- EMAIL -->
    <td class="px-6 py-4 text-center text-gray-300">
        <?= $a['email'] ?>
    </td>

    <!-- ROLE -->
    <td class="px-6 py-4 text-center">
        <span class="px-3 py-1 rounded-full text-xs border
            <?= $a['role'] === 'superadmin'
                ? 'bg-purple-600/20 text-purple-400 border-purple-500/30'
                : 'bg-blue-600/20 text-blue-400 border-blue-500/30' ?>">
            <?= ucfirst($a['role']) ?>
        </span>
    </td>

    <!-- CREATED -->
    <td class="px-6 py-4 text-center text-gray-400 text-xs">
        <?= date('d M Y', strtotime($a['created_at'])) ?>
    </td>

    <!-- AKSI -->
    <td class="px-6 py-4 text-center space-x-2">
        <!-- MODAL EDIT -->
        <button onclick="openEditAdminModal(<?= $a['id'] ?>)"
            class="px-3 py-1 text-xs rounded bg-yellow-500/20 text-yellow-400 hover:bg-yellow-500/30 transition-all">
            Edit
        </button>

        <a href="<?= base_url('admin/delete/'.$a['id']) ?>"
           onclick="return confirm('Hapus data?')"
           class="px-3 py-1 text-xs rounded bg-red-500/20 text-red-400 hover:bg-red-500/30 transition-all">
            Hapus
        </a>
    </td>

</tr>

<?php endforeach; endif ?>
</tbody>

</table>
</div>

<!-- ================= MODAL ================= -->
<div id="adminModal"
     class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50"
     onclick="handleModalBackdropClick(event)">
    <div class="theme-card rounded-xl w-full max-w-md p-6 border" 
         onclick="event.stopPropagation()">
        <div id="adminModalContent"></div>
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

html.light .text-gray-500 {
    color: var(--text-muted) !important;
}
</style>

<!-- ================= SCRIPT ================= -->
<script>
function openCreateAdminModal() {
    fetch("<?= base_url('admin/create') ?>")
        .then(res => res.text())
        .then(html => {
            document.getElementById('adminModalContent').innerHTML = html;
            openAdminModal();
        });
}

function openEditAdminModal(id) {
    fetch("<?= base_url('admin/edit/') ?>" + id)
        .then(res => res.text())
        .then(html => {
            document.getElementById('adminModalContent').innerHTML = html;
            openAdminModal();
            
            // PENTING: Setup event listener SETELAH HTML dimuat
            setupEditFormHandler();
        });
}

function openAdminModal() {
    const modal = document.getElementById('adminModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeAdminModal() {
    const modal = document.getElementById('adminModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.getElementById('adminModalContent').innerHTML = '';
}

// PERBAIKAN: Handle klik backdrop modal
function handleModalBackdropClick(event) {
    if (event.target.id === 'adminModal') {
        closeAdminModal();
    }
}

// FUNGSI BARU: Setup handler untuk form edit
function setupEditFormHandler() {
    const form = document.getElementById('editAdminForm');
    if (!form) return;
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('editSubmitBtn');
        const btnText = document.getElementById('editBtnText');
        const btnLoading = document.getElementById('editBtnLoading');
        const alertBox = document.getElementById('editAlert');
        
        // Show loading
        submitBtn.disabled = true;
        btnText.classList.add('hidden');
        btnLoading.classList.remove('hidden');
        
        // Submit form via AJAX
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(text => {
            // Try parse as JSON
            try {
                const data = JSON.parse(text);
                
                if (data.success) {
                    // Show success message
                    alertBox.className = 'rounded-lg p-4 mb-4 bg-green-500/10 border border-green-500/50 text-green-400';
                    alertBox.innerHTML = `
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>${data.message}</span>
                        </div>
                    `;
                    alertBox.classList.remove('hidden');
                    
                    // Reload halaman setelah 1 detik
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    // Show error message
                    alertBox.className = 'rounded-lg p-4 mb-4 bg-red-500/10 border border-red-500/50 text-red-400';
                    alertBox.innerHTML = `
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <span>${data.message}</span>
                        </div>
                    `;
                    alertBox.classList.remove('hidden');
                    
                    // Reset button
                    submitBtn.disabled = false;
                    btnText.classList.remove('hidden');
                    btnLoading.classList.add('hidden');
                }
            } catch (e) {
                console.error('Parse error:', e);
                console.log('Response:', text);
                
                alertBox.className = 'rounded-lg p-4 mb-4 bg-red-500/10 border border-red-500/50 text-red-400';
                alertBox.innerHTML = `
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Response bukan JSON! Cek console untuk detail.</span>
                    </div>
                `;
                alertBox.classList.remove('hidden');
                
                // Reset button
                submitBtn.disabled = false;
                btnText.classList.remove('hidden');
                btnLoading.classList.add('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alertBox.className = 'rounded-lg p-4 mb-4 bg-red-500/10 border border-red-500/50 text-red-400';
            alertBox.innerHTML = `
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Terjadi kesalahan sistem!</span>
                </div>
            `;
            alertBox.classList.remove('hidden');
            
            // Reset button
            submitBtn.disabled = false;
            btnText.classList.remove('hidden');
            btnLoading.classList.add('hidden');
        });
    });
}

// Function switchMode untuk toggle form
function switchMode(mode) {
    const btnNew = document.getElementById('btn-new');
    const btnPromote = document.getElementById('btn-promote');
    const formNew = document.getElementById('form-new');
    const formPromote = document.getElementById('form-promote');
    
    if (mode === 'new') {
        btnNew.classList.add('bg-blue-600', 'text-white');
        btnNew.classList.remove('text-gray-400');
        btnPromote.classList.remove('bg-blue-600', 'text-white');
        btnPromote.classList.add('text-gray-400');
        formNew.classList.remove('hidden');
        formPromote.classList.add('hidden');
    } else {
        btnPromote.classList.add('bg-blue-600', 'text-white');
        btnPromote.classList.remove('text-gray-400');
        btnNew.classList.remove('bg-blue-600', 'text-white');
        btnNew.classList.add('text-gray-400');
        formPromote.classList.remove('hidden');
        formNew.classList.add('hidden');
    }
}

// ESC key untuk tutup modal
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAdminModal();
    }
});
</script>