<div id="userModal"
     class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-[9999]">

    <div class="usr-card rounded-xl w-full max-w-md p-5 shadow-2xl">

        <div class="usr-header border-b pb-2 mb-3">
            <h3 class="usr-title text-base font-bold">Tambah Pengguna</h3>
        </div>

        <!-- Alert inline (email duplikat dll) -->
        <div id="createUserAlert" class="hidden rounded-lg p-3 mb-3 text-sm"></div>

        <form id="createUserForm"
              action="<?= site_url('user/store') ?>"
              method="post"
              autocomplete="off"
              class="space-y-2.5">

            <div>
                <label class="usr-label block text-xs mb-1">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="create_name" autocomplete="off" required
                    placeholder="Masukkan nama lengkap"
                    class="usr-input w-full px-3 py-2 rounded-lg text-sm
                           focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="usr-label block text-xs mb-1">
                    Email <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="email" name="email" id="create_email"
                           autocomplete="new-email" required
                        placeholder="email@example.com"
                        class="usr-input w-full px-3 py-2 pr-9 rounded-lg text-sm
                               focus:outline-none focus:ring-2 focus:ring-blue-500"
                        oninput="resetEmailStatus()">
                    <!-- Status icon -->
                    <span id="emailStatusIcon"
                          class="absolute right-3 top-1/2 -translate-y-1/2 hidden pointer-events-none">
                    </span>
                </div>
                <p id="emailStatusMsg" class="text-xs mt-1 hidden"></p>
            </div>

            <div>
                <label class="usr-label block text-xs mb-1">
                    Password <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="password" name="password" id="create_password"
                           autocomplete="new-password" required
                        placeholder="Min. 8 karakter"
                        class="usr-input w-full px-3 py-2 pr-10 rounded-lg text-sm
                               focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="button" onclick="togglePassword()"
                        class="absolute right-3 top-1/2 -translate-y-1/2 usr-muted
                               hover:text-gray-300 transition-colors">
                        <svg id="eye-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943
                                     9.542 7-1.274 4.057-5.064 7-9.542 7-4.477
                                     0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="usr-divider text-center text-xs py-1">Data Telegram (Opsional)</div>

            <div>
                <label class="usr-label block text-xs mb-1">Telegram Username</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 usr-muted text-sm">@</span>
                    <input type="text" name="telegram_username" id="telegram_username"
                           autocomplete="off"
                        placeholder="username"
                        class="usr-input w-full pl-7 pr-3 py-2 rounded-lg text-sm
                               focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <p class="text-xs text-blue-400 mt-1">💡 Auto jadi password jika diisi</p>
            </div>

            <div>
                <label class="usr-label block text-xs mb-1">Telegram ID</label>
                <input type="text" name="telegram_id" autocomplete="off"
                    placeholder="123456789"
                    class="usr-input w-full px-3 py-2 rounded-lg text-sm
                           focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="usr-footer flex justify-end gap-2 pt-3 border-t">
                <button type="button" onclick="confirmCancel()"
                    class="usr-btn-cancel px-4 py-2 rounded-lg text-sm font-medium
                           transition-colors">
                    Batal
                </button>
                <button type="button" id="createSubmitBtn" onclick="confirmSubmit()"
                    class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white
                           text-sm font-semibold transition-colors flex items-center gap-2">
                    <span id="createBtnText">Simpan</span>
                </button>
            </div>

        </form>
    </div>
</div>

<style>
/* ── dark default ── */
.usr-card    { background: #0b1220; border: 1px solid #1f2937; }
.usr-header  { border-color: #374151; }
.usr-footer  { border-color: #374151; }
.usr-title   { color: #f1f5f9; }
.usr-label   { color: #d1d5db; }
.usr-muted   { color: #6b7280; }
.usr-divider { color: #6b7280; }

.usr-input {
    background: #0f172a;
    border: 1px solid #374151;
    color: #f1f5f9;
}
.usr-input::placeholder { color: #4b5563; }
.usr-input:focus        { border-color: #3b82f6; }
.usr-input.email-error  { border-color: #ef4444 !important; }
.usr-input.email-ok     { border-color: #22c55e !important; }

.usr-btn-cancel       { background: #374151; color: #d1d5db; }
.usr-btn-cancel:hover { background: #4b5563; color: #fff; }

/* ── light overrides ── */
html.light .usr-card    { background: #ffffff !important; border-color: #e2e8f0 !important; }
html.light .usr-header  { border-color: #e2e8f0 !important; }
html.light .usr-footer  { border-color: #e2e8f0 !important; }
html.light .usr-title   { color: #0f172a !important; }
html.light .usr-label   { color: #374155 !important; }
html.light .usr-muted   { color: #94a3b8 !important; }
html.light .usr-divider { color: #94a3b8 !important; }

html.light .usr-input {
    background: #f8fafc !important;
    border-color: #cbd5e1 !important;
    color: #0f172a !important;
}
html.light .usr-input::placeholder { color: #94a3b8 !important; }
html.light .usr-input:focus        { border-color: #3b82f6 !important; }

html.light .usr-btn-cancel       { background: #f1f5f9 !important; color: #374155 !important; }
html.light .usr-btn-cancel:hover { background: #e2e8f0 !important; color: #0f172a !important; }
</style>

<script>
// ============================
// EMAIL DUPLICATE CHECK (AJAX)
// ============================
let emailCheckTimer = null;
let emailValid = true;

function resetEmailStatus() {
    clearTimeout(emailCheckTimer);
    const input = document.getElementById('create_email');
    const icon  = document.getElementById('emailStatusIcon');
    const msg   = document.getElementById('emailStatusMsg');
    input.classList.remove('email-error', 'email-ok');
    icon.classList.add('hidden');
    msg.classList.add('hidden');
    emailValid = true;

    // Debounce: cek setelah user berhenti ngetik 600ms
    emailCheckTimer = setTimeout(() => {
        const email = input.value.trim();
        if (!email || !email.includes('@')) return;
        checkEmailAvailability(email);
    }, 600);
}

function checkEmailAvailability(email) {
    const input = document.getElementById('create_email');
    const icon  = document.getElementById('emailStatusIcon');
    const msg   = document.getElementById('emailStatusMsg');

    // Spinner
    icon.innerHTML = `<svg class="w-4 h-4 animate-spin text-gray-400" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
        <path class="opacity-75" fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962
                 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
    </svg>`;
    icon.classList.remove('hidden');

    fetch('<?= site_url('user/check_email') ?>?email=' + encodeURIComponent(email))
        .then(r => r.json())
        .then(data => {
            if (data.exists) {
                // ❌ Email sudah dipakai
                emailValid = false;
                input.classList.add('email-error');
                input.classList.remove('email-ok');
                icon.innerHTML = `<svg class="w-4 h-4 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                          d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0
                             00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414
                             1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414
                             10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                          clip-rule="evenodd"/>
                </svg>`;
                msg.className = 'text-xs mt-1 text-red-400';
                msg.textContent = 'Email sudah terdaftar, gunakan email lain.';
                msg.classList.remove('hidden');
            } else {
                // ✅ Email tersedia
                emailValid = true;
                input.classList.add('email-ok');
                input.classList.remove('email-error');
                icon.innerHTML = `<svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0
                             00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414
                             1.414l2 2a1 1 0 001.414 0l4-4z"
                          clip-rule="evenodd"/>
                </svg>`;
                msg.className = 'text-xs mt-1 text-green-400';
                msg.textContent = 'Email tersedia.';
                msg.classList.remove('hidden');
            }
        })
        .catch(() => {
            // Kalau AJAX gagal, biarkan server yang validasi
            emailValid = true;
            icon.classList.add('hidden');
        });
}

// ============================
// TOGGLE PASSWORD
// ============================
function togglePassword() {
    const p = document.getElementById('create_password');
    const e = document.getElementById('eye-icon');
    if (p.type === 'password') {
        p.type = 'text';
        e.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
    } else {
        p.type = 'password';
        e.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
    }
}

// ============================
// TELEGRAM → PASSWORD AUTO-FILL
// ============================
const tgInput   = document.getElementById('telegram_username');
const passInput = document.getElementById('create_password');
if (tgInput && passInput) {
    tgInput.addEventListener('input', function() {
        this.value = this.value.replace('@', '');
        if (passInput.value === '' || passInput.dataset.autoFilled === 'true') {
            passInput.value = this.value;
            passInput.dataset.autoFilled = 'true';
        }
    });
    passInput.addEventListener('input', function() {
        if (this.value !== tgInput.value) this.dataset.autoFilled = 'false';
    });
}

// ============================
// CONFIRM CANCEL
// ============================
function confirmCancel() {
    const form    = document.getElementById('createUserForm');
    const hasData = Array.from(form.querySelectorAll('input'))
                        .some(i => i.value.trim() !== '');

    if (!hasData) { closeUserModal(); return; }

    Swal.fire({
        background: document.documentElement.classList.contains('light') ? '#ffffff' : '#0b1220',
        color:      document.documentElement.classList.contains('light') ? '#0f172a'  : '#e5e7eb',
        title: 'Batalkan perubahan?',
        text:  'Data yang sudah diisi akan hilang.',
        icon:  'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Batalkan',
        cancelButtonText:  'Tidak',
        reverseButtons:     true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor:  '#334155',
    }).then(result => {
        if (result.isConfirmed) closeUserModal();
    });
}

// ============================
// CONFIRM SUBMIT
// ============================
function confirmSubmit() {
    const form = document.getElementById('createUserForm');
    if (!form.checkValidity()) { form.reportValidity(); return; }

    // Blokir jika email sudah terpakai
    if (!emailValid) {
        Swal.fire({
            background: document.documentElement.classList.contains('light') ? '#ffffff' : '#0b1220',
            color:      document.documentElement.classList.contains('light') ? '#0f172a'  : '#e5e7eb',
            icon:  'error',
            title: 'Email sudah terdaftar!',
            text:  'Gunakan email lain sebelum menyimpan.',
            confirmButtonColor: '#2563eb',
        });
        return;
    }

    Swal.fire({
        background: document.documentElement.classList.contains('light') ? '#ffffff' : '#0b1220',
        color:      document.documentElement.classList.contains('light') ? '#0f172a'  : '#e5e7eb',
        title: 'Simpan pengguna?',
        text:  'Pastikan data yang diisi sudah benar.',
        icon:  'question',
        showCancelButton: true,
        confirmButtonText: 'Simpan',
        cancelButtonText:  'Batal',
        confirmButtonColor: '#2563eb',
        cancelButtonColor:  '#334155',
    }).then(result => {
        if (result.isConfirmed) {
            Swal.fire({
                background: document.documentElement.classList.contains('light') ? '#ffffff' : '#0b1220',
                color:      document.documentElement.classList.contains('light') ? '#0f172a'  : '#e5e7eb',
                title: 'Menyimpan...',
                text:  'Mohon tunggu',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            form.submit();
        }
    });
}
</script>