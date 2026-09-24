<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Registrasi | SIPASTI</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex items-center justify-center
             bg-gradient-to-br from-[#0B2E6B] via-[#0A3D8F] to-[#1557C0]">

<!-- Card -->
<div class="w-full max-w-2xl bg-white/95 backdrop-blur
            rounded-3xl p-8 shadow-[0_25px_60px_rgba(0,0,0,0.25)]
            animate-[fadeIn_0.6s_ease-out]">

    <!-- Header -->
    <div class="text-center mb-6">
        <h1 class="text-2xl font-extrabold text-[#0B2E6B] tracking-tight">
            Registrasi Akun
        </h1>
        <p class="text-gray-600 text-sm mt-2 leading-relaxed">
            Sistem Informasi Pengelolaan Aset dan Layanan TI<br>
            <span class="font-medium">BPS Provinsi Riau</span>
        </p>
    </div>

    <!-- Error Backend -->
    <?php if($this->session->flashdata('error')): ?>
        <div class="mb-5 rounded-xl
                    bg-red-100 border border-red-300
                    px-4 py-3 text-sm text-red-700">
            <?= $this->session->flashdata('error') ?>
        </div>
    <?php endif ?>

    <!-- Form -->
    <form method="post" id="registerForm"
          class="grid grid-cols-1 md:grid-cols-2 gap-5">

        <!-- Nama -->
        <div>
            <label class="label">Nama Lengkap</label>
            <input type="text" name="name" required
                   placeholder="Nama lengkap sesuai identitas"
                   class="input">
        </div>

        <!-- NIP -->
        <div>
            <label class="label">NIP (18 digit)</label>
            <input type="text" name="nip" id="nip"
                   placeholder="18 digit angka"
                   class="input">
            <p class="error hidden" id="nipError">
                NIP harus 18 digit angka
            </p>
        </div>

        <!-- Email -->
        <div>
            <label class="label">Email</label>
            <input type="email" name="email" required
                   placeholder="contoh@bps.go.id"
                   class="input">
        </div>

        <!-- HP -->
        <div>
            <label class="label">No. WhatsApp</label>
            <input type="text" name="hp" id="hp"
                   placeholder="08xxxxxxxxxx"
                   class="input">
            <p class="error hidden" id="hpError">
                Nomor WhatsApp tidak valid
            </p>
        </div>

        <!-- Unit -->
        <div class="md:col-span-2">
            <label class="label">Unit Kerja</label>
            <input type="text" name="unit_kerja"
                   placeholder="Bidang / Subbag / Seksi"
                   class="input">
        </div>

        <!-- Password -->
        <div class="relative">
            <label class="label">Password</label>
            <input type="password" name="password" id="password"
                   placeholder="Minimal 6 karakter"
                   class="input pr-10">
            <button type="button"
                    onclick="togglePassword('password')"
                    class="eye-btn">👁️</button>
        </div>

        <!-- Konfirmasi -->
        <div class="relative">
            <label class="label">Konfirmasi Password</label>
            <input type="password" name="password2" id="password2"
                   placeholder="Ulangi password"
                   class="input pr-10">
            <button type="button"
                    onclick="togglePassword('password2')"
                    class="eye-btn">👁️</button>
            <p class="error hidden" id="passError">
                Password tidak sama / kurang dari 6 karakter
            </p>
        </div>

        <!-- Button -->
        <div class="md:col-span-2 pt-2">
            <button id="submitBtn"
                class="w-full py-3 rounded-2xl font-bold text-white
                       bg-gradient-to-r from-[#F97316] to-[#FB923C]
                       hover:from-[#EA580C] hover:to-[#F97316]
                       transition-all duration-300
                       shadow-lg active:scale-[0.97]
                       disabled:opacity-40"
                disabled>
                Daftar Akun
            </button>
        </div>
    </form>

    <p class="text-sm text-gray-600 mt-6 text-center">
        Sudah punya akun?
        <a href="<?= base_url('auth/login') ?>" 
           class="text-[#0B2E6B] font-semibold hover:underline">
            Login
        </a>
    </p>
</div>

<!-- ================= SCRIPT ================= -->
<script>
const nip = document.getElementById('nip');
const hp = document.getElementById('hp');
const pass = document.getElementById('password');
const pass2 = document.getElementById('password2');
const btn = document.getElementById('submitBtn');

const nipError = document.getElementById('nipError');
const hpError = document.getElementById('hpError');
const passError = document.getElementById('passError');

function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
}

function validateForm() {
    let valid = true;

    if (!/^[0-9]{18}$/.test(nip.value)) {
        nipError.classList.remove('hidden'); valid = false;
    } else nipError.classList.add('hidden');

    if (!/^08[0-9]{8,11}$/.test(hp.value)) {
        hpError.classList.remove('hidden'); valid = false;
    } else hpError.classList.add('hidden');

    if (pass.value !== pass2.value || pass.value.length < 6) {
        passError.classList.remove('hidden'); valid = false;
    } else passError.classList.add('hidden');

    btn.disabled = !valid;
}

[nip, hp, pass, pass2].forEach(el =>
    el.addEventListener('input', validateForm)
);
</script>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0); }
}

.label {
    font-size: 0.8rem;
    font-weight: 600;
    color: #1f2937;
}

.input {
    width: 100%;
    background: #ffffff;
    border: 1px solid #d1d5db;
    border-radius: 0.9rem;
    padding: 0.6rem 1rem;
    margin-top: 0.25rem;
    font-size: 0.9rem;
}
.input:focus {
    outline: none;
    border-color: #0B2E6B;
    box-shadow: 0 0 0 2px rgba(11,46,107,.25);
}

.error {
    font-size: 0.7rem;
    color: #dc2626;
    margin-top: 0.2rem;
}

.eye-btn {
    position: absolute;
    right: 0.8rem;
    top: 2.35rem;
    font-size: 0.85rem;
    color: #6b7280;
}
</style>

</body>
</html>
