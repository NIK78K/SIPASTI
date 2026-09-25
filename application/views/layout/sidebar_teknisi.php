<?php $current = uri_string(); ?>

<!-- HEADER -->
<div class="fixed top-0 left-0 right-0 z-50 flex items-center gap-4 bg-gradient-to-r from-blue-950 to-blue-800 px-6 py-3 shadow-lg">    
    <img src="<?= base_url('assets/img/bps.png') ?>" 
         alt="Logo BPS" 
         class="w-14 h-14 object-contain">
    
    <div class="flex items-center gap-6">
        <div class="border-r border-white/30 pr-6">
            <h1 class="text-[17px] font-bold text-white tracking-wider italic uppercase" style="font-family: 'Arial Black', 'Arial Bold', sans-serif; letter-spacing: 0.5px;">
                BADAN PUSAT STATISTIK
            </h1>
            <p class="text-[13px] font-bold text-white tracking-wider italic uppercase" style="font-family: 'Arial Black', 'Arial Bold', sans-serif; letter-spacing: 0.5px;">
                PROVINSI RIAU
            </p>
        </div>
        
        <div>
            <h2 class="text-[18px] font-bold text-white tracking-wide" style="font-family: 'Arial Black', 'Arial Bold', sans-serif;">
                SIPASTI
            </h2>
            <p class="text-[11px] text-blue-200 mt-0.5">
                Sistem Informasi Pengelolaan Aset & Layanan Ter Intergrasi
            </p>
        </div>
    </div>

    <div class="ml-auto flex items-center gap-3">
        <!-- Theme Toggle Button -->
        <button onclick="toggleTheme()" 
                class="p-2.5 rounded-xl transition-all duration-300 group hover:scale-110 active:scale-95
                       bg-white/10 hover:bg-white/20 border border-white/20 hover:border-white/30"
                title="Toggle Dark/Light Mode">
            <svg class="w-5 h-5 text-white/90 hover:text-white transition-colors dark-mode-icon" 
                 fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
            </svg>
            <svg class="w-5 h-5 text-white/90 hover:text-white transition-colors light-mode-icon" 
                 fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
            </svg>
        </button>

        <!-- Hamburger Menu untuk Mobile -->
        <button onclick="toggleSidebar()" class="lg:hidden text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>
</div>

<!-- SIDEBAR -->
<aside id="sidebar"
 class="w-64 bg-[#0e141b] border-r border-gray-800
        flex flex-col fixed lg:sticky top-[70px] inset-y-0 left-0
        transform -translate-x-full lg:translate-x-0
        transition z-40"
 style="height: calc(100vh - 70px);">

    <div class="flex-1 overflow-y-auto px-4 py-6 min-h-0">

        <!-- LOGO -->
        <div class="flex items-center gap-2 text-blue-400 mb-6">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3L4.5 8.25v11.25h15V8.25L14.25 3z"/>
            </svg>
            <div>
                <div class="font-bold text-lg">SIPASTI</div>
                <div class="text-xs text-gray-400">Dashboard Teknisi</div>
            </div>
        </div>

        <!-- USER INFO -->
        <div class="mb-6 text-sm text-gray-400">
            Login sebagai:
            <div class="text-white font-semibold">
                <?= $this->session->userdata('name') ?>
                <span class="text-xs text-gray-500">(Teknisi)</span>
            </div>

            <?php
            $original_role = $this->session->userdata('original_role');
            if (in_array($original_role, ['admin', 'superadmin'])):
                $label_map = [
                    'admin'      => '🛡️ Admin',
                    'superadmin' => '⚡ Superadmin',
                ];
            ?>
            <form method="post" action="<?= base_url('auth/switch_role') ?>" class="mt-2">
                <input type="hidden" name="role" value="<?= $original_role ?>">
                <button type="submit"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg
                               bg-indigo-500/15 border border-indigo-500/30 text-indigo-300
                               text-xs font-semibold hover:bg-indigo-500/25 transition w-full justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    Kembali ke <?= $label_map[$original_role] ?>
                </button>
            </form>
            <?php endif; ?>
        </div>

        <!-- MENU -->
        <nav class="space-y-1 text-sm">

            <!-- DASHBOARD -->
            <a href="<?= base_url('teknisi_panel') ?>"
               class="flex items-center gap-3 px-3 py-2 rounded
               <?= $current == 'teknisi_panel' ? 'bg-[#111827] text-white' : 'text-gray-300 hover:bg-[#111827]' ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 3h7v7H3zM14 3h7v7h-7zM3 14h7v7H3zM14 14h7v7h-7z"/>
                </svg>
                Dashboard
            </a>

            <!-- TICKET -->
            <a href="<?= base_url('ticket') ?>"
               class="flex items-center gap-3 px-3 py-2 rounded
               <?= $current == 'ticket' ? 'bg-[#111827] text-white' : 'text-gray-300 hover:bg-[#111827]' ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3M3 11h18M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                </svg>
                Manajemen Ticket
            </a>

            <!-- HISTORY -->
            <a href="<?= base_url('history') ?>"
               class="flex items-center gap-3 px-3 py-2 rounded
               <?= $current == 'history' ? 'bg-[#111827] text-white' : 'text-gray-300 hover:bg-[#111827]' ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3M12 2a10 10 0 1010 10"/>
                </svg>
                History Perbaikan
            </a>

            <!-- ZOOM MANAGEMENT -->
            <a href="<?= base_url('zoom_management') ?>"
               class="flex items-center gap-3 px-3 py-2 rounded
               <?= $current == 'zoom_management' ? 'bg-[#111827] text-white' : 'text-gray-300 hover:bg-[#111827]' ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                </svg>
                Manajemen Zoom
                <?php
                $zoom_count_today = $this->db
                    ->where('sub_kategori_id', 2)
                    ->where('tanggal', date('Y-m-d'))
                    ->where_in('status', ['Menunggu', 'On Progress'])
                    ->count_all_results('tickets');
                if ($zoom_count_today > 0): ?>
                    <span class="ml-auto bg-blue-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                        <?= $zoom_count_today ?>
                    </span>
                <?php endif; ?>
            </a>

<!-- MANAJEMEN PINJAMAN -->
<a href="<?= base_url('ruang_rapat') ?>"
   class="flex items-center gap-3 px-3 py-2 rounded
   <?= $current == 'ruang_rapat' ? 'bg-[#111827] text-white' : 'text-gray-300 hover:bg-[#111827]' ?>">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
    </svg>
    Manajemen Pinjaman
</a>

<!-- TRANSAKSI PERSEDIAAN -->
<a href="<?= base_url('transaksi_persediaan') ?>"
   class="flex items-center gap-3 px-3 py-2 rounded
   <?= $current == 'transaksi_persediaan' ? 'bg-[#111827] text-white' : 'text-gray-300 hover:bg-[#111827]' ?>">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
    </svg>
    Transaksi Barang Persediaan
</a>
            <!-- REKAP & LAPORAN -->
            <?php
            $openLaporanMenu = in_array($current, ['laporan', 'rekap_zoom', 'rekap_bulanan']);
            $idL = 'laporan';
            ?>
            <div>
                <button onclick="toggleMenu('<?= $idL ?>')"
                        class="flex items-center justify-between w-full px-3 py-2 rounded
                               <?= $openLaporanMenu ? 'bg-[#111827] text-white' : 'text-gray-300 hover:bg-[#111827]' ?>">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 17v-6m4 6V7m4 10V11M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        Rekap & Laporan
                    </div>
                    <svg id="<?= $idL ?>Arrow" class="w-4 h-4 transition-transform duration-200 <?= $openLaporanMenu ? 'rotate-180' : '' ?>"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9l6 6 6-6"/>
                    </svg>
                </button>
                <div id="<?= $idL ?>Menu" class="<?= $openLaporanMenu ? '' : 'hidden' ?> ml-7 mt-1 space-y-1 bg-[#111827] rounded p-1">
                    <a href="<?= base_url('laporan') ?>"
                       class="flex items-center gap-2 px-3 py-1.5 rounded text-xs
                              <?= $current == 'laporan' ? 'text-white bg-[#1f2937]' : 'text-gray-400 hover:bg-[#1f2937] hover:text-white' ?>">
                        Rekap & Laporan
                    </a>
                    <a href="<?= base_url('rekap_zoom') ?>"
                       class="flex items-center gap-2 px-3 py-1.5 rounded text-xs
                              <?= $current == 'rekap_zoom' ? 'text-white bg-[#1f2937]' : 'text-gray-400 hover:bg-[#1f2937] hover:text-white' ?>">
                        Rekap Zoom
                    </a>
                    <a href="<?= base_url('rekap_bulanan') ?>"
                       class="flex items-center gap-2 px-3 py-1.5 rounded text-xs
                              <?= $current == 'rekap_bulanan' ? 'text-white bg-[#1f2937]' : 'text-gray-400 hover:bg-[#1f2937] hover:text-white' ?>">
                        Rekap Bulanan
                    </a>
                </div>
            </div>

            <!-- SCAN -->
            <a href="<?= base_url('inventory/scan_camera') ?>"
               class="flex items-center gap-3 px-3 py-2 rounded
               <?= $current == 'inventory/scan_camera' ? 'bg-[#111827] text-white' : 'text-gray-300 hover:bg-[#111827]' ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 7h4l2-3h6l2 3h4v10a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
                Scan Perangkat
            </a>

        </nav>
    </div><!-- end scrollable -->

    <!-- LOGOUT -->
    <div class="flex-shrink-0 px-4 py-4 border-t border-gray-800">
        <a href="javascript:void(0)"
           onclick="confirmLogout()"
           class="flex items-center gap-3 px-3 py-2 text-red-400 hover:bg-[#111827] rounded">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"/>
            </svg>
            Logout
        </a>
    </div>

</aside>

<main class="flex-1 p-6 mt-[70px] main-content">

<style>
.dark-mode-icon  { display: block; }
.light-mode-icon { display: none; }
html.light .dark-mode-icon  { display: none; }
html.light .light-mode-icon { display: block; }

/* ── Sidebar selalu dark (tidak ikut light mode) ── */
#sidebar,
html.light #sidebar {
    background: #0e141b !important;
    border-right-color: #1f2937 !important;
}

/* Link menu */
#sidebar a,
html.light #sidebar a {
    color: #d1d5db !important;
}
#sidebar a:hover,
html.light #sidebar a:hover {
    background: #111827 !important;
    color: #ffffff !important;
}

/* Link aktif */
#sidebar a.bg-\[#111827\],
html.light #sidebar a.bg-\[#111827\] {
    background: #111827 !important;
    color: #ffffff !important;
}

/* Submenu container */
#sidebar div[id$="Menu"],
html.light #sidebar div[id$="Menu"] {
    background: #111827 !important;
}

/* Submenu link */
#sidebar div[id$="Menu"] a,
html.light #sidebar div[id$="Menu"] a {
    color: #9ca3af !important;
}
#sidebar div[id$="Menu"] a:hover,
html.light #sidebar div[id$="Menu"] a:hover {
    background: #1f2937 !important;
    color: #ffffff !important;
}
#sidebar div[id$="Menu"] a.text-white,
html.light #sidebar div[id$="Menu"] a.text-white {
    color: #ffffff !important;
    background: #1f2937 !important;
}

/* Tombol toggle submenu (Rekap & Laporan) */
#sidebar button,
html.light #sidebar button {
    color: #d1d5db !important;
    background: transparent !important;
}
#sidebar button:hover,
html.light #sidebar button:hover {
    background: #111827 !important;
    color: #ffffff !important;
}
#sidebar button.bg-\[#111827\],
html.light #sidebar button.bg-\[#111827\] {
    background: #111827 !important;
    color: #ffffff !important;
}

/* Teks warna di sidebar */
#sidebar .text-gray-300,
html.light #sidebar .text-gray-300 { color: #d1d5db !important; }
#sidebar .text-gray-400,
html.light #sidebar .text-gray-400 { color: #9ca3af !important; }
#sidebar .text-gray-500,
html.light #sidebar .text-gray-500 { color: #6b7280 !important; }
#sidebar .text-white,
html.light #sidebar .text-white     { color: #ffffff !important; }
#sidebar .text-blue-400,
html.light #sidebar .text-blue-400  { color: #60a5fa !important; }
#sidebar .text-red-400,
html.light #sidebar .text-red-400   { color: #f87171 !important; }

/* Border */
#sidebar .border-gray-800,
html.light #sidebar .border-gray-800 { border-color: #1f2937 !important; }

/* Badge zoom count */
#sidebar .bg-blue-500,
html.light #sidebar .bg-blue-500 {
    background-color: #3b82f6 !important;
    color: #ffffff !important;
}

/* Tombol kembali ke admin (indigo) */
#sidebar .bg-indigo-500\/15,
html.light #sidebar .bg-indigo-500\/15 {
    background-color: rgba(99,102,241,0.15) !important;
}
#sidebar .text-indigo-300,
html.light #sidebar .text-indigo-300 { color: #a5b4fc !important; }
#sidebar .border-indigo-500\/30,
html.light #sidebar .border-indigo-500\/30 { border-color: rgba(99,102,241,0.30) !important; }
#sidebar .hover\:bg-indigo-500\/25:hover,
html.light #sidebar .hover\:bg-indigo-500\/25:hover {
    background-color: rgba(99,102,241,0.25) !important;
}

/* Arrow icon submenu */
#sidebar svg,
html.light #sidebar svg { color: inherit !important; }

/* Logout hover */
#sidebar a.text-red-400:hover,
html.light #sidebar a.text-red-400:hover {
    background: #111827 !important;
    color: #f87171 !important;
}

/* Main content area ikut light mode */
html.light .main-content {
    background-color: #f1f5f9;
    color: #0f172a;
}

/* Header tetap biru di light mode */
html.light .bg-gradient-to-r.from-blue-950.to-blue-800 {
    background: linear-gradient(to right, #1e3a5f, #1d4ed8) !important;
}
</style>

<script>
function toggleMenu(id) {
    document.getElementById(id + 'Menu').classList.toggle('hidden');
    document.getElementById(id + 'Arrow').classList.toggle('rotate-180');
}

function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('-translate-x-full');
}

function confirmLogout() {
    Swal.fire({
        title: 'Logout dari sistem?',
        text: 'Anda akan keluar dari sesi saat ini',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#374151',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Sedang logout...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => { Swal.showLoading() }
            });
            setTimeout(() => {
                window.location.href = "<?= base_url('auth/logout') ?>";
            }, 800);
        }
    });
}
</script>