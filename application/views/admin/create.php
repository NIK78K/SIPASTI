<!-- Form Tambah Admin (Konten yang akan dimuat ke dalam adminModalContent) -->

<div class="relative">
    <!-- Header -->
    <div class="border-b modal-divider pb-3 mb-4">
        <h3 class="text-lg font-bold modal-title">Tambah Admin</h3>
        <p class="text-xs modal-hint mt-1">Buat baru atau promosikan user existing</p>
    </div>

    <!-- Toggle Mode -->
    <div id="modeToggle" class="flex gap-2 mb-4 p-1 modal-toggle-wrap rounded-lg border modal-divider">
        <button type="button" onclick="switchMode('new')" id="btn-new"
                class="flex-1 px-3 py-2 rounded text-sm font-medium transition-all
                       bg-blue-600 text-white">
            Buat Baru
        </button>
        <button type="button" onclick="switchMode('promote')" id="btn-promote"
                class="flex-1 px-3 py-2 rounded text-sm font-medium transition-all
                       modal-toggle-inactive">
            Promosikan User
        </button>
    </div>

    <!-- Form Buat Baru -->
    <form method="post" action="<?= base_url('admin/create') ?>"
          id="form-new" class="space-y-3">

        <div>
            <label class="block text-sm modal-label mb-1.5">Nama Lengkap</label>
            <input type="text" name="name" required
                   placeholder="Masukkan nama"
                   class="modal-input w-full px-3 py-2 rounded-lg border text-sm transition-all
                          focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>

        <div>
            <label class="block text-sm modal-label mb-1.5">Email</label>
            <input type="email" name="email" required
                   placeholder="email@example.com"
                   class="modal-input w-full px-3 py-2 rounded-lg border text-sm transition-all
                          focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>

        <div>
            <label class="block text-sm modal-label mb-1.5">Password</label>
            <input type="password" name="password" required
                   placeholder="Min. 8 karakter"
                   class="modal-input w-full px-3 py-2 rounded-lg border text-sm transition-all
                          focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>

        <div>
            <label class="block text-sm modal-label mb-1.5">Role</label>
            <select name="role"
                    class="modal-input w-full px-3 py-2 rounded-lg border text-sm transition-all
                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="admin">Admin</option>
                <option value="superadmin">Super Admin</option>
            </select>
        </div>

        <div class="flex justify-end gap-2 pt-3 border-t modal-divider">
            <button type="button" onclick="closeAdminModal()"
                    class="px-4 py-2 rounded-lg modal-btn-cancel text-sm font-medium transition-colors">
                Batal
            </button>
            <button type="submit"
                    class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700
                           text-white text-sm font-semibold transition-colors">
                Simpan
            </button>
        </div>
    </form>

    <!-- Form Promosikan User -->
    <form method="post" action="<?= base_url('admin/promote') ?>"
          id="form-promote" class="space-y-3 hidden">

        <div>
            <label class="block text-sm modal-label mb-1.5">Pilih User</label>
            <select name="user_id" required
                    class="modal-input w-full px-3 py-2 rounded-lg border text-sm transition-all
                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">-- Pilih User --</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?= $user->id ?>">
                        <?= $user->name ?> (<?= $user->email ?>) - <?= ucfirst($user->role) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <p class="text-xs modal-hint mt-1.5">User yang dipilih akan dijadikan admin</p>
        </div>

        <div>
            <label class="block text-sm modal-label mb-1.5">Role Admin</label>
            <select name="role" required
                    class="modal-input w-full px-3 py-2 rounded-lg border text-sm transition-all
                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="admin">Admin</option>
                <option value="superadmin">Super Admin</option>
            </select>
        </div>

        <div class="flex justify-end gap-2 pt-3 border-t modal-divider">
            <button type="button" onclick="closeAdminModal()"
                    class="px-4 py-2 rounded-lg modal-btn-cancel text-sm font-medium transition-colors">
                Batal
            </button>
            <button type="submit"
                    class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700
                           text-white text-sm font-semibold transition-colors">
                Promosikan
            </button>
        </div>
    </form>
</div>

<style>
/* ── DARK (default) ── */
.modal-title           { color: #f1f5f9; }
.modal-label           { color: #cbd5e1; }
.modal-hint            { color: #64748b; }
.modal-divider         { border-color: #374151; }
.modal-toggle-wrap     { background: #0f172a; }
.modal-toggle-inactive { color: #94a3b8; }
.modal-toggle-inactive:hover { color: #f1f5f9; }
.modal-input {
    background: #0f172a;
    border-color: #374151;
    color: #f1f5f9;
}
.modal-input::placeholder { color: #4b5563; }
.modal-btn-cancel {
    background: #374151;
    color: #f1f5f9;
}
.modal-btn-cancel:hover { background: #4b5563; }

/* ── LIGHT ── */
html.light .modal-title           { color: #0f172a; }
html.light .modal-label           { color: #334155; }
html.light .modal-hint            { color: #94a3b8; }
html.light .modal-divider         { border-color: #e2e8f0 !important; }
html.light .modal-toggle-wrap     { background: #f1f5f9 !important; }
html.light .modal-toggle-inactive { color: #64748b !important; }
html.light .modal-toggle-inactive:hover { color: #0f172a !important; }
html.light .modal-input {
    background: #f8fafc !important;
    border-color: #cbd5e1 !important;
    color: #0f172a !important;
}
html.light .modal-input::placeholder { color: #94a3b8 !important; }
html.light .modal-input:focus { background: #ffffff !important; }
html.light .modal-input option { background: #ffffff; color: #0f172a; }
html.light .modal-btn-cancel {
    background: #e2e8f0 !important;
    color: #334155 !important;
}
html.light .modal-btn-cancel:hover { background: #cbd5e1 !important; }
</style>