<?php
$current = uri_string();

$openUserMenu       = in_array($current, ['admin','teknisi','user']);
$openInventoryMenu  = in_array($current, ['inventory','inventory_umum']);
$openPengajuanMenu  = in_array($current, ['inventory/pengajuan','perangkat_dihapus']);
$openTicketMenu     = in_array($current, ['ticket','history','zoom_management','ruang_rapat']);
$openLaporanMenu    = in_array($current, ['laporan','rekap','rekap_inventory','rekap_bulanan','rekap_zoom']);
// Master Data sekarang include master_kendaraan
$openMasterMenu = in_array($current, [
    'master_kategori','kategori_persediaan','sub_kategori','master_lokasi','barang','barang_persediaan','stok_awal_persediaan',
    'master_ruang_rapat','master_pegawai','master_kendaraan','master_bmn'
]);
$openToolsMenu      = in_array($current, ['inventory/scan_camera']);
?>

<!-- HEADER -->
<div class="fixed top-0 left-0 right-0 z-50 flex items-center gap-4 bg-gradient-to-r from-blue-950 to-blue-800 px-6 py-3 shadow-lg">
    <img src="<?= base_url('assets/img/bps.png') ?>" alt="Logo BPS" class="w-14 h-14 object-contain">

    <div class="flex items-center gap-6">
        <div class="border-r border-white/30 pr-6">
            <h1 class="text-[17px] font-bold text-white tracking-wider italic uppercase" style="font-family:'Arial Black','Arial Bold',sans-serif;letter-spacing:0.5px;">
                BADAN PUSAT STATISTIK
            </h1>
            <p class="text-[13px] font-bold text-white tracking-wider italic uppercase" style="font-family:'Arial Black','Arial Bold',sans-serif;letter-spacing:0.5px;">
                PROVINSI RIAU
            </p>
        </div>
        <div>
            <h2 class="text-[18px] font-bold text-white tracking-wide" style="font-family:'Arial Black','Arial Bold',sans-serif;">SIPASTI</h2>
            <p class="text-[11px] text-blue-200 mt-0.5">Sistem Informasi Pengelolaan Aset &amp; Layanan Ter Intergrasi</p>
        </div>
    </div>

    <div class="ml-auto flex items-center gap-3">
        <button onclick="toggleTheme()"
                class="p-2.5 rounded-xl transition-all duration-300 hover:scale-110 active:scale-95
                       bg-white/10 hover:bg-white/20 border border-white/20 hover:border-white/30"
                title="Toggle Dark/Light Mode">
            <svg class="w-5 h-5 text-white/90 dark-mode-icon" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
            </svg>
            <svg class="w-5 h-5 text-white/90 light-mode-icon" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
            </svg>
        </button>

        <button onclick="toggleSidebar()" class="lg:hidden text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>
</div>

<!-- SIDEBAR -->
<aside id="sidebar"
       class="w-72 bg-[#0e141b] border-r border-gray-800
              flex flex-col fixed lg:sticky top-[70px] inset-y-0 left-0
              transform -translate-x-full lg:translate-x-0 transition z-40"
       style="height: calc(100vh - 70px);">

    <div class="flex-1 overflow-y-auto px-5 py-6 min-h-0">

        <!-- Login sebagai -->
        <div class="mb-8 text-sm text-gray-400">
            Login sebagai
            <div class="text-white font-semibold text-base">
                <?= $this->session->userdata('name') ?>
                <span class="text-xs text-gray-500">(<?= ucfirst($this->session->userdata('role')) ?>)</span>
            </div>

            <?php
            $current_user_id = $this->session->userdata('user_id');
            $current_role    = $this->session->userdata('role');

            $user_row = $this->db
                ->select('role, extra_roles')
                ->where('id', $current_user_id)
                ->get('users')->row_array();

            $base_role   = $user_row['role'] ?? $current_role;
            $extra_roles = [];
            if (!empty($user_row['extra_roles'])) {
                $decoded = json_decode($user_row['extra_roles'], true);
                if (is_array($decoded)) $extra_roles = $decoded;
            }

            $all_roles   = array_unique(array_merge([$base_role], $extra_roles));
            $other_roles = array_filter($all_roles, fn($r) => $r !== $current_role);

            $label_map = [
                'superadmin' => '⚡ Superadmin',
                'admin'      => '🛡️ Admin',
                'user'       => '👤 User',
                'teknisi'    => '🔧 Teknisi',
            ];

            if (!empty($other_roles)): ?>
            <div class="mt-2 flex flex-col gap-1.5">
                <?php foreach ($other_roles as $role): ?>
                <form method="post" action="<?= base_url('auth/switch_role') ?>">
                    <input type="hidden" name="role" value="<?= $role ?>">
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg
                                   bg-indigo-500/15 border border-indigo-500/30 text-indigo-300
                                   text-xs font-semibold hover:bg-indigo-500/25 transition w-full justify-center">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        Switch ke <?= $label_map[$role] ?? ucfirst($role) ?>
                    </button>
                </form>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <nav class="space-y-3 text-[15px]">

            <!-- DASHBOARD -->
            <a href="<?= base_url('dashboard') ?>"
               class="menu-link <?= $current=='dashboard'?'active':'' ?>">
                <svg class="icon" viewBox="0 0 24 24">
                    <path d="M3 3h7v7H3zM14 3h7v7h-7zM3 14h7v7H3zM14 14h7v7h-7z"/>
                </svg>
                Dashboard
            </a>

            <!-- MANAJEMEN USER -->
            <?php $id='user'; ?>
            <div>
                <button onclick="toggleMenu('<?= $id ?>')" class="menu-btn <?= $openUserMenu?'active':'' ?>">
                    <div class="flex items-center gap-3">
                        <svg class="icon" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                        </svg>
                        Manajemen User
                    </div>
                    <svg id="<?= $id ?>Arrow" class="arrow <?= $openUserMenu?'rotate':'' ?>" viewBox="0 0 24 24">
                        <path d="M6 9l6 6 6-6"/>
                    </svg>
                </button>
                <div id="<?= $id ?>Menu" class="submenu <?= $openUserMenu?'':'hidden' ?>">
                    <a class="<?= $current=='admin'?'sub-active':'' ?>" href="<?= base_url('admin') ?>">Admin</a>
                    <a class="<?= $current=='teknisi'?'sub-active':'' ?>" href="<?= base_url('teknisi') ?>">Teknisi</a>
                    <a class="<?= $current=='user'?'sub-active':'' ?>" href="<?= base_url('user') ?>">Pengguna</a>
                </div>
            </div>

            <!-- INVENTORY -->
            <?php $id='inventory'; ?>
            <div>
                <button onclick="toggleMenu('<?= $id ?>')" class="menu-btn <?= $openInventoryMenu?'active':'' ?>">
                    <div class="flex items-center gap-3">
                        <svg class="icon" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
                        </svg>
                        Data Inventory
                    </div>
                    <svg id="<?= $id ?>Arrow" class="arrow <?= $openInventoryMenu?'rotate':'' ?>" viewBox="0 0 24 24">
                        <path d="M6 9l6 6 6-6"/>
                    </svg>
                </button>
                <div id="<?= $id ?>Menu" class="submenu <?= $openInventoryMenu?'':'hidden' ?>">
                    <a class="<?= $current=='inventory'?'sub-active':'' ?>" href="<?= base_url('inventory') ?>">Inventory IT</a>
                    <a class="<?= $current=='inventory_umum'?'sub-active':'' ?>" href="<?= base_url('inventory_umum') ?>">Inventory Umum</a>
                </div>
            </div>

            <!-- PENGHAPUSAN -->
            <?php $id='pengajuan'; ?>
            <div>
                <button onclick="toggleMenu('<?= $id ?>')" class="menu-btn <?= $openPengajuanMenu?'active':'' ?>">
                    <div class="flex items-center gap-3">
                        <svg class="icon" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                        </svg>
                        Penghapusan
                    </div>
                    <svg id="<?= $id ?>Arrow" class="arrow <?= $openPengajuanMenu?'rotate':'' ?>" viewBox="0 0 24 24">
                        <path d="M6 9l6 6 6-6"/>
                    </svg>
                </button>
                <div id="<?= $id ?>Menu" class="submenu <?= $openPengajuanMenu?'':'hidden' ?>">
                    <a class="<?= $current=='inventory/pengajuan'?'sub-active':'' ?>" href="<?= base_url('inventory/pengajuan') ?>">Pengajuan Perangkat</a>
                    <a class="<?= $current=='perangkat_dihapus'?'sub-active':'' ?>" href="<?= base_url('perangkat_dihapus') ?>">Perangkat Dihapus</a>
                </div>
            </div>

            <!-- HELPDESK & HISTORY -->
            <?php $id='ticket'; ?>
            <div>
                <button onclick="toggleMenu('<?= $id ?>')" class="menu-btn <?= $openTicketMenu?'active':'' ?>">
                    <div class="flex items-center gap-3">
                        <svg class="icon" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z"/>
                        </svg>
                        Helpdesk &amp; History
                    </div>
                    <svg id="<?= $id ?>Arrow" class="arrow <?= $openTicketMenu?'rotate':'' ?>" viewBox="0 0 24 24">
                        <path d="M6 9l6 6 6-6"/>
                    </svg>
                </button>
                <div id="<?= $id ?>Menu" class="submenu <?= $openTicketMenu?'':'hidden' ?>">
                    <a class="<?= $current=='ticket'?'sub-active':'' ?>" href="<?= base_url('ticket') ?>">Manajemen Tiket</a>
                    <a class="<?= $current=='history'?'sub-active':'' ?>" href="<?= base_url('history') ?>">History Perbaikan</a>
                    <a class="<?= $current=='zoom_management'?'sub-active':'' ?>" href="<?= base_url('zoom_management') ?>">
                        Manajemen Zoom
                        <?php
                        $zoom_count_today = $this->db
                            ->where('sub_kategori_id', 2)
                            ->where('tanggal', date('Y-m-d'))
                            ->where_in('status', ['Menunggu', 'On Progress'])
                            ->count_all_results('tickets');
                        if ($zoom_count_today > 0): ?>
                            <span class="sub-badge"><?= $zoom_count_today ?></span>
                        <?php endif; ?>
                    </a>
                    <!-- ▼ Manajemen Peminjaman: 1 link, pakai ?tab= untuk switch di dalam halaman -->
                    <a class="<?= $current=='ruang_rapat'?'sub-active':'' ?>"
                       href="<?= base_url('ruang_rapat') ?>">
                        Manajemen Peminjaman
                    </a>
                </div>
            </div>

            <!-- LAPORAN -->
            <?php $id='laporan'; ?>
            <div>
                <button onclick="toggleMenu('<?= $id ?>')" class="menu-btn <?= $openLaporanMenu?'active':'' ?>">
                    <div class="flex items-center gap-3">
                        <svg class="icon" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
                        </svg>
                        Laporan
                    </div>
                    <svg id="<?= $id ?>Arrow" class="arrow <?= $openLaporanMenu?'rotate':'' ?>" viewBox="0 0 24 24">
                        <path d="M6 9l6 6 6-6"/>
                    </svg>
                </button>
                <div id="<?= $id ?>Menu" class="submenu <?= $openLaporanMenu?'':'hidden' ?>">
                    <a class="<?= $current=='laporan'?'sub-active':'' ?>" href="<?= base_url('laporan') ?>">Laporan Detail</a>
                    <a class="<?= $current=='rekap'?'sub-active':'' ?>" href="<?= base_url('rekap') ?>">Rekap Perbaikan</a>
                    <a class="<?= $current=='rekap_inventory'?'sub-active':'' ?>" href="<?= base_url('rekap_inventory') ?>">Rekap Inventory</a>
                    <a class="<?= $current=='rekap_bulanan'?'sub-active':'' ?>" href="<?= base_url('rekap_bulanan') ?>">Rekap Bulanan</a>
                    <a class="<?= $current=='rekap_zoom'?'sub-active':'' ?>" href="<?= base_url('rekap_zoom') ?>">Rekap Zoom</a>
                </div>
            </div>

            <!-- MASTER DATA — sekarang include Master Kendaraan -->
            <?php $id='master'; ?>
            <div>
                <button onclick="toggleMenu('<?= $id ?>')" class="menu-btn <?= $openMasterMenu?'active':'' ?>">
                    <div class="flex items-center gap-3">
                        <svg class="icon" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.737 5.1a3.375 3.375 0 012.7-1.35h7.126c1.062 0 2.062.5 2.7 1.35l2.587 3.45a4.5 4.5 0 01.9 2.7m0 0a3 3 0 01-3 3m0 3h.008v.008h-.008v-.008zm0-6h.008v.008h-.008v-.008zm-3 6h.008v.008h-.008v-.008zm0-6h.008v.008h-.008v-.008z"/>
                        </svg>
                        Master Data
                    </div>
                    <svg id="<?= $id ?>Arrow" class="arrow <?= $openMasterMenu?'rotate':'' ?>" viewBox="0 0 24 24">
                        <path d="M6 9l6 6 6-6"/>
                    </svg>
                </button>
                <div id="<?= $id ?>Menu" class="submenu <?= $openMasterMenu?'':'hidden' ?>">
                    <a class="<?= $current=='master_kategori'?'sub-active':'' ?>" href="<?= base_url('master_kategori') ?>">Master Kategori</a>
                    <a class="<?= $current=='kategori_persediaan'?'sub-active':'' ?>" href="<?= base_url('kategori_persediaan') ?>">Master Kategori Barang Persediaan</a>
                    <a class="<?= $current=='sub_kategori'?'sub-active':'' ?>" href="<?= base_url('sub_kategori') ?>">Master Sub Kategori</a>
                    <a class="<?= $current=='barang'?'sub-active':'' ?>" href="<?= base_url('barang') ?>">Master Barang</a>
                    <a class="<?= $current=='barang_persediaan'?'sub-active':'' ?>" href="<?= base_url('barang_persediaan') ?>">Master Barang Persediaan</a>
                    <a class="<?= $current=='stok_awal_persediaan'?'sub-active':'' ?>" href="<?= base_url('stok_awal_persediaan') ?>">Master Stok Awal Persediaan</a>
                    <a class="<?= $current=='master_lokasi'?'sub-active':'' ?>" href="<?= base_url('master_lokasi') ?>">Master Lokasi</a>
                    <a class="<?= $current=='master_ruang_rapat'?'sub-active':'' ?>" href="<?= base_url('master_ruang_rapat') ?>">Master Ruang Rapat</a>
                    <a class="<?= $current=='master_pegawai'?'sub-active':'' ?>" href="<?= base_url('master_pegawai') ?>">Master Pegawai</a>
                    <!-- ▼ BARU -->
                    <a class="<?= $current=='master_kendaraan'?'sub-active':'' ?>" href="<?= base_url('master_kendaraan') ?>">Master Kendaraan</a>
                <a class="<?= $current=='master_bmn'?'sub-active':'' ?>" href="<?= base_url('master_bmn') ?>">Master Peminjaman BMN</a>
                </div>
            </div>

            <!-- TOOLS -->
            <?php $id='tools'; ?>
            <div>
                <button onclick="toggleMenu('<?= $id ?>')" class="menu-btn <?= $openToolsMenu?'active':'' ?>">
                    <div class="flex items-center gap-3">
                        <svg class="icon" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z"/>
                        </svg>
                        Tools
                    </div>
                    <svg id="<?= $id ?>Arrow" class="arrow <?= $openToolsMenu?'rotate':'' ?>" viewBox="0 0 24 24">
                        <path d="M6 9l6 6 6-6"/>
                    </svg>
                </button>
                <div id="<?= $id ?>Menu" class="submenu <?= $openToolsMenu?'':'hidden' ?>">
                    <a class="<?= $current=='inventory/scan_camera'?'sub-active':'' ?>" href="<?= base_url('inventory/scan_camera') ?>">Scan Perangkat</a>
                </div>
            </div>

        </nav>
    </div><!-- end scrollable -->

    <!-- LOGOUT -->
    <div class="flex-shrink-0 px-5 py-4 border-t border-gray-800">
        <a href="javascript:void(0)" onclick="confirmLogout()"
           class="flex items-center gap-3 px-4 py-3 rounded-xl w-full
                  text-red-400 hover:text-red-300 hover:bg-red-500/10
                  font-medium text-[15px] transition-all duration-200">
            <svg class="icon flex-shrink-0" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
            </svg>
            Logout
        </a>
    </div>

</aside>

<style>
.menu-link {
    display:flex; align-items:center; gap:12px;
    padding:12px 16px; border-radius:10px;
    color:#d1d5db; font-weight:500; transition:all .2s ease;
}
.menu-link:hover, .menu-link.active { background:#1f2937; color:white; }

.menu-btn {
    width:100%; display:flex; justify-content:space-between;
    align-items:center; padding:12px 16px; border-radius:10px;
    color:#d1d5db; font-weight:500; transition:all .2s ease;
}
.menu-btn:hover, .menu-btn.active { background:#1f2937; color:white; }

.submenu {
    margin-left:1.75rem; margin-top:6px;
    background:#111827; border-radius:8px; padding:6px;
}
.submenu a {
    display:flex; align-items:center; justify-content:space-between;
    padding:8px 12px; border-radius:6px;
    color:#9ca3af; font-size:14px; transition:all .2s ease;
}
.submenu a:hover, .submenu a.sub-active { background:#1f2937; color:white; }

.sub-badge {
    background:#3b82f6; color:#fff;
    border-radius:9999px; padding:1px 7px;
    font-size:10px; font-weight:700; line-height:1.5;
}

.icon {
    width:22px; height:22px; fill:none;
    stroke:currentColor; stroke-width:1.75; flex-shrink:0;
}
.arrow {
    width:18px; height:18px; transition:transform .2s ease;
    fill:none; stroke:currentColor; stroke-width:2; flex-shrink:0;
}
.rotate { transform:rotate(180deg); }

.dark-mode-icon  { display:block; }
.light-mode-icon { display:none; }
html.light .dark-mode-icon  { display:none; }
html.light .light-mode-icon { display:block; }

/* Sidebar always dark */
#sidebar, html.light #sidebar { background:#0e141b !important; border-right-color:#1f2937 !important; }
#sidebar .menu-link, #sidebar .menu-btn,
html.light #sidebar .menu-link, html.light #sidebar .menu-btn { color:#d1d5db !important; }
#sidebar .menu-link:hover, #sidebar .menu-link.active,
#sidebar .menu-btn:hover, #sidebar .menu-btn.active,
html.light #sidebar .menu-link:hover, html.light #sidebar .menu-link.active,
html.light #sidebar .menu-btn:hover, html.light #sidebar .menu-btn.active { background:#1f2937 !important; color:#fff !important; }
#sidebar .submenu, html.light #sidebar .submenu { background:#111827 !important; }
#sidebar .submenu a, html.light #sidebar .submenu a { color:#9ca3af !important; }
#sidebar .submenu a:hover, #sidebar .submenu a.sub-active,
html.light #sidebar .submenu a:hover, html.light #sidebar .submenu a.sub-active { background:#1f2937 !important; color:#fff !important; }
#sidebar .text-gray-400, html.light #sidebar .text-gray-400 { color:#9ca3af !important; }
#sidebar .text-white, html.light #sidebar .text-white { color:#fff !important; }
#sidebar .text-gray-500, html.light #sidebar .text-gray-500 { color:#6b7280 !important; }
#sidebar .text-red-400, html.light #sidebar .text-red-400 { color:#f87171 !important; }
#sidebar .border-gray-800, html.light #sidebar .border-gray-800 { border-color:#1f2937 !important; }
</style>

<script>
function toggleMenu(id) {
    document.getElementById(id+'Menu').classList.toggle('hidden');
    document.getElementById(id+'Arrow').classList.toggle('rotate');
}
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('-translate-x-full');
}
</script>

<main class="flex-1 p-6 mt-[70px] main-content">

<script>
function confirmLogout() {
    Swal.fire({
        title:'Logout dari sistem?', text:'Anda akan keluar dari sesi saat ini',
        icon:'warning', showCancelButton:true,
        confirmButtonText:'Ya, Logout', cancelButtonText:'Batal',
        confirmButtonColor:'#ef4444', cancelButtonColor:'#374151', reverseButtons:true
    }).then(r => {
        if (r.isConfirmed) {
            Swal.fire({ title:'Sedang logout...', text:'Mohon tunggu sebentar',
                allowOutsideClick:false, allowEscapeKey:false,
                didOpen:() => Swal.showLoading() });
            setTimeout(() => { window.location.href = "<?= base_url('auth/logout') ?>"; }, 800);
        }
    });
}
</script>