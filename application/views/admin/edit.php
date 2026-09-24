<!-- Form Edit Admin (Konten yang akan dimuat ke dalam adminModalContent) -->

<!-- Alert Success/Error -->
<div id="editAlert" class="hidden rounded-lg p-4 mb-4"></div>

<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-yellow-600/20 flex items-center justify-center">
            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
        </div>
        <h3 class="text-xl font-semibold modal-title">Edit Admin</h3>
    </div>
    <button type="button" onclick="closeAdminModal()"
            class="modal-close-btn p-1.5 rounded-lg transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>

<form id="editAdminForm" method="post"
      action="<?= base_url('admin/edit/'.$admin['id']) ?>"
      class="space-y-5">

    <!-- Nama Field -->
    <div class="space-y-2">
        <label class="block text-sm font-medium modal-label">
            Nama Lengkap <span class="text-red-400">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 modal-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <input type="text" name="name"
                   value="<?= htmlspecialchars($admin['name']) ?>" required
                   class="modal-input w-full pl-10 pr-4 py-2.5 rounded-lg border transition-all
                          focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                   placeholder="Masukkan nama lengkap">
        </div>
    </div>

    <!-- Email Field -->
    <div class="space-y-2">
        <label class="block text-sm font-medium modal-label">
            Email <span class="text-red-400">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 modal-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <input type="email" name="email"
                   value="<?= htmlspecialchars($admin['email']) ?>" required
                   class="modal-input w-full pl-10 pr-4 py-2.5 rounded-lg border transition-all
                          focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                   placeholder="admin@example.com">
        </div>
    </div>

    <!-- Password Field -->
    <div class="space-y-2">
        <label class="block text-sm font-medium modal-label">
            Password Baru
            <span class="text-xs modal-hint ml-2">(Kosongkan jika tidak ingin mengubah)</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 modal-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <input type="password" name="password"
                   class="modal-input w-full pl-10 pr-4 py-2.5 rounded-lg border transition-all
                          focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                   placeholder="Masukkan password baru">
        </div>
    </div>

    <!-- Role Field -->
    <div class="space-y-2">
        <label class="block text-sm font-medium modal-label">
            Role <span class="text-red-400">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 modal-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <select name="role" required
                    class="modal-input w-full pl-10 pr-10 py-2.5 rounded-lg border transition-all
                           focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500
                           appearance-none cursor-pointer">
                <option value="admin"      <?= $admin['role']=='admin'      ?'selected':'' ?>>Admin</option>
                <option value="superadmin" <?= $admin['role']=='superadmin' ?'selected':'' ?>>Super Admin</option>
            </select>
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 modal-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Footer Buttons -->
    <div class="flex items-center gap-3 pt-4 border-t modal-divider">
        <button type="button" onclick="closeAdminModal()"
                class="flex-1 px-4 py-2.5 rounded-lg modal-btn-cancel font-medium transition-colors">
            Batal
        </button>
        <button type="submit" id="editSubmitBtn"
                class="flex-1 px-4 py-2.5 rounded-lg bg-gradient-to-r from-yellow-600 to-yellow-700
                       hover:from-yellow-700 hover:to-yellow-800
                       text-white font-semibold shadow-lg shadow-yellow-500/20
                       transition-all flex items-center justify-center gap-2">
            <span id="editBtnText" class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Perubahan
            </span>
            <span id="editBtnLoading" class="hidden">
                <svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg"
                     fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10"
                            stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
            </span>
        </button>
    </div>
</form>

<style>
/* ── DARK (default) ── */
.modal-title      { color: #f1f5f9; }
.modal-label      { color: #cbd5e1; }
.modal-hint       { color: #64748b; }
.modal-icon       { color: #64748b; }
.modal-close-btn  { color: #94a3b8; background: transparent; }
.modal-close-btn:hover { color: #f1f5f9; background: rgba(51,65,85,0.6); }
.modal-input {
    background: #0f172a;
    border-color: #374151;
    color: #f1f5f9;
}
.modal-input::placeholder { color: #4b5563; }
.modal-input:focus { background: #0f172a; }
.modal-divider  { border-color: #374151; }
.modal-btn-cancel {
    background: #374151;
    color: #f1f5f9;
}
.modal-btn-cancel:hover { background: #4b5563; }

/* ── LIGHT ── */
html.light .modal-title     { color: #0f172a; }
html.light .modal-label     { color: #334155; }
html.light .modal-hint      { color: #94a3b8; }
html.light .modal-icon      { color: #94a3b8; }
html.light .modal-close-btn { color: #64748b; }
html.light .modal-close-btn:hover { color: #0f172a; background: #f1f5f9; }
html.light .modal-input {
    background: #f8fafc !important;
    border-color: #cbd5e1 !important;
    color: #0f172a !important;
}
html.light .modal-input::placeholder { color: #94a3b8 !important; }
html.light .modal-input:focus { background: #ffffff !important; }
html.light .modal-divider  { border-color: #e2e8f0 !important; }
html.light .modal-btn-cancel {
    background: #e2e8f0 !important;
    color: #334155 !important;
}
html.light .modal-btn-cancel:hover { background: #cbd5e1 !important; }
</style>