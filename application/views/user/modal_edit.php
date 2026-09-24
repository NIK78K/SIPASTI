<div id="editUserAlert" class="hidden rounded-lg p-4 mb-4"></div>

<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-blue-600/20 flex items-center justify-center">
            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
        </div>
        <h3 class="usr-title text-xl font-semibold">Edit User</h3>
    </div>
    <button type="button" onclick="closeUserEditModal()" class="usr-muted hover:text-red-400 transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>

<form id="editUserForm" method="post" action="<?= base_url('user/edit/'.$user['id']) ?>" class="space-y-5">

    <div class="space-y-2">
        <label class="usr-label block text-sm font-medium">
            Nama Lengkap <span class="text-red-400">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 usr-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required
                class="usr-input w-full pl-10 pr-4 py-2.5 rounded-lg focus:ring-2 focus:ring-blue-500/20 transition-all"
                placeholder="Masukkan nama lengkap">
        </div>
    </div>

    <div class="space-y-2">
        <label class="usr-label block text-sm font-medium">
            Email <span class="text-red-400">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 usr-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required
                class="usr-input w-full pl-10 pr-4 py-2.5 rounded-lg focus:ring-2 focus:ring-blue-500/20 transition-all"
                placeholder="user@example.com">
        </div>
    </div>

    <div class="space-y-2">
        <label class="usr-label block text-sm font-medium">
            Password Baru <span class="usr-muted text-xs ml-2">(Kosongkan jika tidak ingin mengubah)</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 usr-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <input type="password" name="password"
                class="usr-input w-full pl-10 pr-4 py-2.5 rounded-lg focus:ring-2 focus:ring-blue-500/20 transition-all"
                placeholder="Masukkan password baru">
        </div>
    </div>

    <div class="usr-footer flex items-center gap-3 pt-4 border-t">
        <button type="button" onclick="closeUserEditModal()"
            class="usr-btn-cancel flex-1 px-4 py-2.5 rounded-lg font-medium transition-colors">
            Batal
        </button>
        <button type="submit" id="editUserSubmitBtn"
            class="flex-1 px-4 py-2.5 rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold shadow-lg shadow-blue-500/30 transition-all flex items-center justify-center gap-2">
            <span id="editUserBtnText" class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Perubahan
            </span>
            <span id="editUserBtnLoading" class="hidden">
                <svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
            </span>
        </button>
    </div>
</form>

<style>
/* Note: .usr-* classes sudah didefinisikan di create_user_modal.php */
/* Tambahkan ini jika edit modal dimuat terpisah tanpa create modal */

/* ── dark default ── */
.usr-title  { color: #f1f5f9; }
.usr-label  { color: #d1d5db; }
.usr-muted  { color: #6b7280; }
.usr-footer { border-color: #374151; }

.usr-input {
    background: #0f172a;
    border: 1px solid #374151;
    color: #f1f5f9;
    font-size: 0.875rem;
}
.usr-input::placeholder { color: #4b5563; }
.usr-input:focus { border-color: #3b82f6; }

.usr-btn-cancel { background: #374151; color: #d1d5db; }
.usr-btn-cancel:hover { background: #4b5563; color: #fff; }

/* ── light overrides ── */
html.light .usr-title   { color: #0f172a !important; }
html.light .usr-label   { color: #374155 !important; }
html.light .usr-muted   { color: #94a3b8 !important; }
html.light .usr-footer  { border-color: #e2e8f0 !important; }

html.light .usr-input {
    background: #f8fafc !important;
    border-color: #cbd5e1 !important;
    color: #0f172a !important;
}
html.light .usr-input::placeholder { color: #94a3b8 !important; }
html.light .usr-input:focus { border-color: #3b82f6 !important; }

html.light .usr-btn-cancel { background: #f1f5f9 !important; color: #374155 !important; }
html.light .usr-btn-cancel:hover { background: #e2e8f0 !important; color: #0f172a !important; }
</style>