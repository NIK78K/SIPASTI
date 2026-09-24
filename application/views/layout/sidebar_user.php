<aside class="w-64 bg-[#0e141b] border-r border-white/10 p-5 flex flex-col">

    <!-- LOGO -->
    <div class="flex items-center gap-3 text-blue-400 mb-10">
        <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-blue-600/20">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9.75 3L4.5 8.25v11.25h15V8.25L14.25 3z" />
            </svg>
        </div>
        <div>
            <div class="font-bold text-lg text-white">IT Monitoring</div>
            <div class="text-xs text-gray-400">User Dashboard</div>
        </div>
    </div>

    <!-- USER INFO -->
    <div class="mb-8 text-sm text-gray-400">
        Login sebagai
        <div class="text-white font-semibold leading-tight">
            <?= $this->session->userdata('name') ?><br>
            <span class="text-xs text-gray-500">
                <?= ucfirst($this->session->userdata('role')) ?>
            </span>
        </div>
    </div>

    <!-- MENU -->
    <nav class="flex-1 space-y-1 text-sm">
        <div class="text-gray-500 uppercase text-xs mb-3 tracking-wider">
            Menu
        </div>

        <!-- DASHBOARD -->
        <a href="<?= base_url('user/dashboard') ?>"
           class="flex items-center gap-3 px-4 py-2 rounded-lg transition
           <?= uri_string()=='user/dashboard'
                ? 'bg-blue-600/20 text-white'
                : 'text-gray-300 hover:bg-white/5' ?>">
            🏠 <span>Dashboard</span>
        </a>

        <!-- BUAT TIKET -->
        <a href="<?= base_url('user_ticket/create') ?>"
           class="flex items-center gap-3 px-4 py-2 rounded-lg transition
           <?= uri_string()=='user_ticket/create'
                ? 'bg-blue-600/20 text-white'
                : 'text-gray-300 hover:bg-white/5' ?>">
            ➕ <span>Buat Tiket</span>
        </a>

        <!-- HISTORY -->
        <a href="<?= base_url('user_ticket/history') ?>"
           class="flex items-center gap-3 px-4 py-2 rounded-lg transition
           <?= uri_string()=='user_ticket/history'
                ? 'bg-blue-600/20 text-white'
                : 'text-gray-300 hover:bg-white/5' ?>">
            🕘 <span>Riwayat Tiket</span>
        </a>
    </nav>

    <!-- LOGOUT -->
    <div class="pt-6 border-t border-white/10">
        <a href="<?= base_url('auth/logout') ?>"
           onclick="return confirm('Yakin ingin logout?')"
           class="flex items-center gap-3 px-4 py-2 rounded-lg text-red-400 hover:bg-red-500/10 transition">
            🚪 <span>Logout</span>
        </a>
    </div>

</aside>

<main class="flex-1 p-8">
