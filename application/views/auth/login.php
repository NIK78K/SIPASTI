<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login | SIPASTI - BPS Provinsi Riau</title>
<link rel="icon" type="image/png" href="<?= base_url('assets/img/logo.png') ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="min-h-screen flex items-center justify-center
             bg-gradient-to-br from-[#0B2E6B] via-[#0A3D8F] to-[#1557C0]">

<!-- ===== LOGIN CARD ===== -->
<div class="w-full max-w-md
            bg-white/95 backdrop-blur
            rounded-3xl p-10
            shadow-[0_25px_60px_rgba(0,0,0,0.25)]
            animate-[fadeIn_0.6s_ease-out]">

    <!-- HEADER -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-extrabold text-[#0B2E6B]">
            SIPASTI
        </h1>
        <p class="text-gray-600 text-sm mt-3">
            Sistem Informasi Pengelolaan Aset dan Layanan TI<br>
            <b>BPS Provinsi Riau</b>
        </p>
    </div>

    <!-- FORM -->
    <form method="post" id="loginForm" class="space-y-5">

        <!-- EMAIL -->
        <div>
            <label class="label">Email</label>
            <input type="email" name="email" required
                   class="input"
                   placeholder="contoh@bps.go.id">
        </div>

        <!-- PASSWORD -->
        <div class="relative">
            <label class="label">Password</label>
            <input type="password" name="password" id="password" required
                   class="input pr-12"
                   placeholder="Masukkan password">

            <!-- TOGGLE PASSWORD -->
            <button type="button" onclick="togglePassword()"
                class="absolute right-4 top-[38px] text-gray-500 hover:text-gray-800">
                <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-width="2"
                          d="M1.5 12s4-7.5 10.5-7.5S22.5 12 22.5 12s-4 7.5-10.5 7.5S1.5 12 1.5 12z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
                <svg id="eyeClose" xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-width="2"
                          d="M3 3l18 18M10.7 10.7a3 3 0 004.2 4.2"/>
                </svg>
            </button>
        </div>

        <!-- LUPA PASSWORD -->
        <div class="text-right -mt-2">
            <a href="<?= base_url('auth/forgot_password') ?>"
               class="text-sm text-[#0B2E6B] font-semibold hover:underline">
                Lupa password?
            </a>
        </div>

        <!-- BUTTON LOGIN -->
        <button id="loginBtn"
            class="w-full py-3 rounded-2xl font-bold text-white
                   bg-gradient-to-r from-[#F97316] to-[#FB923C]
                   hover:from-[#EA580C] hover:to-[#F97316]
                   transition shadow-lg">
            Login
        </button>

        <!-- REGISTER -->
        <p class="text-center text-sm text-gray-600">
            Belum punya akun?
            <a href="<?= base_url('auth/register') ?>"
               class="font-semibold text-[#0B2E6B] hover:underline">
                Daftar
            </a>
        </p>

        <!-- ===== JADWAL TV ===== -->
        <a href="<?= base_url('tv') ?>"
           target="_blank"
           class="w-full flex items-center justify-center gap-2
                  text-sm font-semibold text-white
                  bg-gradient-to-r from-[#0B2E6B] to-[#1557C0]
                  hover:from-[#0a2760] hover:to-[#1045a0]
                  rounded-2xl px-4 py-2.5 transition shadow-md">
            <!-- TV Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <rect x="2" y="3" width="20" height="14" rx="2"/>
                <path d="M8 21h8M12 17v4"/>
            </svg>
            Jadwal Harian SIPASTI (TV Display)
        </a>

        <!-- ===== PANDUAN PENGGUNAAN ===== -->
        <button type="button"
            onclick="bukaModalPanduan()"
            class="w-full flex items-center justify-center gap-2
                   text-sm font-semibold text-[#0B2E6B]
                   border border-dashed border-[#0B2E6B]/40
                   bg-blue-50 hover:bg-blue-100
                   rounded-2xl px-4 py-2.5 transition">
            <!-- Play Icon -->
            <span class="w-6 h-6 bg-[#0B2E6B] rounded-full flex items-center
                         justify-content-center flex-shrink-0"
                  style="display:flex;align-items:center;justify-content:center;">
                <svg class="w-3 h-3 ml-0.5" viewBox="0 0 10 10" fill="white">
                    <polygon points="2,1 9,5 2,9"/>
                </svg>
            </span>
            Panduan Penggunaan SIPASTI
        </button>

    </form>
</div>


<!-- ===== MODAL PANDUAN ===== -->
<div id="modalPanduan"
     class="hidden fixed inset-0 z-50 bg-black/60
            flex items-center justify-content-center p-4"
     style="display:none;align-items:center;justify-content:center;">

    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg
                mx-auto animate-[fadeIn_0.3s_ease-out]"
         style="padding:1.75rem;">

        <!-- MODAL HEADER -->
        <div class="flex justify-between items-start mb-4">
            <div>
                <h2 class="text-lg font-extrabold text-[#0B2E6B] leading-tight">
                    Panduan Penggunaan SIPASTI
                </h2>
                <p class="text-xs text-gray-500 mt-1">
                    Sistem Informasi Pengelolaan Aset &amp; Layanan TI &mdash; BPS Provinsi Riau
                </p>
            </div>
            <button onclick="tutupModalPanduan()"
                class="ml-4 w-8 h-8 flex items-center justify-center
                       text-gray-400 hover:text-gray-700
                       bg-gray-100 hover:bg-gray-200
                       rounded-full text-lg leading-none transition flex-shrink-0">
                &times;
            </button>
        </div>

        <!-- DESKRIPSI -->
        <div class="text-sm text-gray-600 bg-blue-50
                    border-l-4 border-[#0B2E6B]
                    rounded-r-xl px-4 py-3 mb-4 leading-relaxed">
            Tonton video panduan berikut untuk memahami cara melakukan
            <strong>permintaan layanan IT</strong> seperti perangkat baru, upgrade, dan instalasi aplikasi,
            serta cara <strong>melaporkan gangguan IT</strong> seperti PC mati, printer error,
            dan internet putus melalui SIPASTI.
            Durasi sekitar ±5 menit.
        </div>

        <!-- VIDEO AREA -->
        <div id="videoPanduan"
             onclick="putarVideo()"
             class="relative bg-[#0f172a] rounded-2xl overflow-hidden cursor-pointer"
             style="aspect-ratio:16/9;">

            <!-- THUMBNAIL / PLAY BUTTON STATE -->
            <div id="thumbPanduan"
                 class="absolute inset-0 flex flex-col items-center justify-center gap-3">

                <!-- Decorative gradient overlay -->
                <div class="absolute inset-0 opacity-30"
                     style="background:radial-gradient(circle at 50% 40%,#1557C0,transparent 70%);"></div>

                <!-- Play Button -->
                <div class="relative z-10 w-16 h-16 rounded-full flex items-center justify-center
                            shadow-lg transition hover:scale-110"
                     style="background:linear-gradient(135deg,#F97316,#FB923C);">
                    <svg class="w-7 h-7 ml-1" viewBox="0 0 24 24" fill="white">
                        <polygon points="5,3 22,12 5,21"/>
                    </svg>
                </div>

                <p class="relative z-10 text-xs text-slate-300 tracking-wide">
                    Klik untuk memutar video panduan
                </p>
            </div>

            <!-- IFRAME (hidden until clicked) -->
            <iframe id="iframePanduan"
                class="hidden w-full h-full border-0"
                allow="autoplay; encrypted-media"
                allowfullscreen>
            </iframe>
        </div>

        <!-- FOOTER NOTE -->
        <p class="text-center text-xs text-gray-400 mt-3">
            Pastikan koneksi internet stabil untuk pengalaman terbaik.
        </p>

    </div>
</div>
<!-- ===== END MODAL PANDUAN ===== -->


<!-- ===== SCRIPTS ===== -->
<script>
/* ---------- Toggle Password ---------- */
function togglePassword() {
    const input = document.getElementById('password');
    document.getElementById('eyeOpen').classList.toggle('hidden');
    document.getElementById('eyeClose').classList.toggle('hidden');
    input.type = input.type === 'password' ? 'text' : 'password';
}

/* ---------- Login Loading State ---------- */
document.getElementById('loginForm').addEventListener('submit', () => {
    const btn = document.getElementById('loginBtn');
    btn.innerHTML = `
        <span class="flex items-center justify-center gap-2">
            <svg class="w-5 h-5 animate-spin" fill="none"
                 viewBox="0 0 24 24" stroke="white">
                <circle cx="12" cy="12" r="10" opacity="0.3"
                        stroke="white" stroke-width="4"/>
                <path d="M12 2a10 10 0 0110 10"
                      stroke="white" stroke-width="4"
                      stroke-linecap="round"/>
            </svg>
            Memproses...
        </span>`;
    btn.disabled = true;
});

/* ---------- Modal Panduan ---------- */
function bukaModalPanduan() {
    const modal = document.getElementById('modalPanduan');
    modal.style.display = 'flex';
    modal.classList.remove('hidden');
}

function tutupModalPanduan() {
    const modal  = document.getElementById('modalPanduan');
    const iframe = document.getElementById('iframePanduan');
    const thumb  = document.getElementById('thumbPanduan');

    modal.style.display = 'none';
    modal.classList.add('hidden');

    // Stop video & reset
    iframe.src = '';
    iframe.classList.add('hidden');
    thumb.classList.remove('hidden');
}

function putarVideo() {
    const iframe = document.getElementById('iframePanduan');
    const thumb  = document.getElementById('thumbPanduan');

    iframe.src = 'https://drive.google.com/file/d/1bdFmI61JmPUf6ielIssvhelOYXjk22VS/preview';
    iframe.classList.remove('hidden');
    thumb.classList.add('hidden');
}

/* ---------- Tutup modal klik di luar ---------- */
document.getElementById('modalPanduan').addEventListener('click', function(e) {
    if (e.target === this) tutupModalPanduan();
});
</script>

<!-- ===== SWEET ALERT ERROR ===== -->
<?php if ($this->session->flashdata('error')): ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Login Gagal',
    text: '<?= $this->session->flashdata('error') ?>',
    confirmButtonColor: '#ef4444'
});
</script>
<?php endif; ?>

<!-- ===== STYLES ===== -->
<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: none; }
}
.label {
    display: block;
    font-size: .8rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 4px;
}
.input {
    width: 100%;
    border: 1px solid #d1d5db;
    border-radius: 0.9rem;
    padding: .65rem 1rem;
    font-size: .9rem;
    color: #111827;
    transition: border-color .2s, box-shadow .2s;
}
.input:focus {
    outline: none;
    border-color: #0B2E6B;
    box-shadow: 0 0 0 3px rgba(11,46,107,.15);
}
</style>

</body>
</html>