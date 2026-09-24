<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SIPASTI - BPS Provinsi Riau</title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/logo.png') ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="min-h-screen bg-[#F4F7FB] text-gray-800">

<!-- ================= HEADER ================= -->
<header class="bg-gradient-to-r from-blue-950 to-blue-800 shadow-lg relative z-50">

    <div class="max-w-7xl mx-auto px-6 py-3 flex justify-between items-center">

        <!-- LEFT BRAND -->
        <div class="flex items-center gap-4">

            <!-- LOGO -->
            <img src="<?= base_url('assets/img/bps.png') ?>"
                 alt="BPS Riau"
                 class="h-14 w-auto object-contain">

            <!-- TITLE -->
            <div class="leading-tight text-white">
                <div class="text-[17px] font-bold tracking-wider italic uppercase" style="font-family: 'Arial Black', 'Arial Bold', sans-serif; letter-spacing: 0.5px;">
                    BADAN PUSAT STATISTIK
                </div>
                <div class="text-[13px] font-bold tracking-wider italic uppercase" style="font-family: 'Arial Black', 'Arial Bold', sans-serif; letter-spacing: 0.5px;">
                    PROVINSI RIAU
                </div>
            </div>

            <!-- DIVIDER -->
            <div class="hidden sm:block h-10 w-px bg-white/30 mx-2"></div>

            <!-- SUB BRAND -->
            <div class="hidden sm:block text-white">
                <div class="text-[18px] font-bold tracking-wide" style="font-family: 'Arial Black', 'Arial Bold', sans-serif;">
                    SIPASTI
                </div>
                <div class="text-[11px] text-blue-200">
                    Sistem Informasi Pengelolaan Aset & Layanan Ter Intergrasi
                </div>
            </div>
        </div>

        <!-- USER MENU -->
        <div class="relative">
            <button id="userMenuBtn"
                class="flex items-center gap-2 px-3 py-1.5 rounded-lg
                       hover:bg-white/10 transition">

                <?php
                    $avatar = $this->session->userdata('avatar');
                    $name   = $this->session->userdata('name');
                    $role   = strtoupper($this->session->userdata('role'));
                ?>

                <!-- AVATAR -->
                <?php if ($avatar): ?>
                    <img src="<?= base_url('uploads/avatar/'.$avatar) ?>"
                         class="w-8 h-8 rounded-full object-cover border border-white/40">
                <?php else: ?>
                    <div class="w-8 h-8 rounded-full bg-white/20
                                flex items-center justify-center
                                text-xs font-bold text-white">
                        <?= strtoupper(substr($name, 0, 1)) ?>
                    </div>
                <?php endif; ?>

                <!-- NAME -->
                <div class="hidden sm:block text-left leading-tight">
                    <div class="text-xs font-semibold text-white">
                        <?= $name ?>
                    </div>
                    <div class="text-[10px] text-blue-200 tracking-wide">
                        <?= $role ?>
                    </div>
                </div>

                <!-- ARROW -->
                <svg id="userArrow"
                     class="w-4 h-4 text-blue-200 transition-transform"
                     fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
<!-- DROPDOWN -->
<div id="userMenu"
     class="hidden absolute right-0 mt-3 w-56
            bg-white rounded-xl shadow-2xl
            border border-blue-100 overflow-hidden">

    <div class="px-4 py-3 border-b border-blue-100">
        <div class="text-sm font-semibold text-gray-800">
            <?= $name ?>
        </div>
        <div class="text-xs text-gray-500">
            <?= $this->session->userdata('email') ?>
        </div>
    </div>

    <a href="<?= base_url('user/profile') ?>"
       class="block px-4 py-3 text-sm text-gray-600
              hover:bg-blue-50 transition">
        Edit Profil
    </a>

    <?php
    $original_role = $this->session->userdata('original_role');
    if (in_array($original_role, ['admin', 'superadmin'])):
        $label_map = [
            'admin'      => '🛡️ Admin',
            'superadmin' => '⚡ Superadmin',
        ];
    ?>
    <div class="border-t border-blue-100"></div>
    <form method="post" action="<?= base_url('auth/switch_role') ?>">
        <input type="hidden" name="role" value="<?= $original_role ?>">
        <button type="submit"
                class="w-full text-left px-4 py-3 text-sm
                       text-indigo-600 hover:bg-indigo-50 transition
                       flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
            </svg>
            Kembali ke <?= $label_map[$original_role] ?>
        </button>
    </form>
    <?php endif; ?>

    <div class="border-t border-blue-100"></div>

    <button onclick="confirmLogout()"
       class="w-full text-left px-4 py-3 text-sm
              text-red-600 hover:bg-red-50 transition">
        Logout
    </button>
</div>
        </div>
    </div>
</header>

<!-- ACCENT LINE -->
<div class="h-1 bg-gradient-to-r from-blue-400 via-cyan-400 to-blue-500"></div>

<!-- ================= SCRIPT ================= -->
<script>
const btn   = document.getElementById('userMenuBtn');
const menu  = document.getElementById('userMenu');
const arrow = document.getElementById('userArrow');

btn.addEventListener('click', (e) => {
    e.stopPropagation();
    menu.classList.toggle('hidden');
    arrow.classList.toggle('rotate-180');
});

document.addEventListener('click', () => {
    menu.classList.add('hidden');
    arrow.classList.remove('rotate-180');
});
</script>

<script>
function confirmLogout() {
    Swal.fire({
        title: 'Yakin ingin logout?',
        text: 'Anda akan keluar dari sistem SIPASTI.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "<?= base_url('auth/logout') ?>";
        }
    });
}
</script>