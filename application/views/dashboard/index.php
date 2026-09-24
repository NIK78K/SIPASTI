<!-- PASTIKAN DI HEAD SUDAH ADA INI -->
<!-- 
<link rel="stylesheet" href="<?= base_url('assets/css/thema.css') ?>">
<script src="<?= base_url('assets/js/theme-toggle.js') ?>"></script>
-->

<!-- MAIN CONTENT dengan class main-content untuk theme -->
<main class="flex-1 px-6 pb-6 pt-20 main-content">

<!-- NOTIFICATION BANNER -->
<?php if ($ticket_menunggu > 0): ?>
<div class="mb-8 animate-fade-in notification-banner" data-ticket-count="<?= $ticket_menunggu ?>">
    <a href="<?= base_url('ticket?status=Menunggu') ?>"
       class="group block relative overflow-hidden theme-card rounded-3xl p-6
              hover:shadow-2xl transition-all duration-500">
        
        <div class="absolute inset-0 opacity-5">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, rgba(251, 191, 36, 0.15) 1px, transparent 0); background-size: 32px 32px;"></div>
        </div>
        
        <div class="relative flex items-center gap-5">
            <div class="relative flex-shrink-0">
                <div class="absolute inset-0 bg-amber-500/30 rounded-2xl blur-xl animate-pulse"></div>
                <div class="relative h-16 w-16 flex items-center justify-center
                            rounded-2xl bg-gradient-to-br from-amber-400 via-orange-500 to-amber-600
                            shadow-xl shadow-amber-500/50">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" 
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>

            <div class="flex-1">
                <div class="font-bold text-amber-600 text-xl mb-1 notification-title">
                    Perhatian: Ticket Memerlukan Tindakan
                </div>
                <div class="text-gray-600 flex items-center gap-2 notification-text">
                    <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <span>Terdapat <span class="font-bold text-amber-600 text-lg" data-stat="ticket_menunggu"><?= $ticket_menunggu ?></span> ticket yang menunggu teknisi</span>
                </div>
            </div>

            <div class="flex items-center gap-3 px-6 py-3 rounded-xl
                        bg-amber-500/20 border border-amber-400/30
                        text-amber-600 font-semibold notification-cta
                        group-hover:bg-amber-500/30 group-hover:translate-x-2
                        transition-all duration-300">
                <span>Lihat Sekarang</span>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </div>
        </div>
    </a>
</div>
<?php endif; ?>

<!-- HEADER -->
<div class="flex justify-between items-center mb-10">
    <div>
        <h1 class="text-5xl font-black tracking-tight mb-3">
            <span class="bg-gradient-to-r from-blue-400 via-indigo-500 to-purple-600 bg-clip-text text-transparent gradient-text">
                System Dashboard
            </span>
        </h1>
        <p class="text-gray-600 text-base header-subtitle">
            Sistem Informasi Pengelolaan Aset & Layanan Ter Intergrasi BPS PROVINSI RIAU
        </p>
    </div>

    <div class="flex items-center gap-4">
        <!-- THEME TOGGLE BUTTON -->
        <button onclick="toggleTheme()" 
                class="theme-toggle-btn p-2.5 rounded-xl transition-all duration-300 group
                       hover:scale-110 active:scale-95">
            <!-- Icon Moon (Dark Mode) -->
            <svg class="w-5 h-5 transition-colors dark-mode-icon" 
                 fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
            </svg>
            <!-- Icon Sun (Light Mode) -->
            <svg class="w-5 h-5 transition-colors light-mode-icon" 
                 fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
            </svg>
        </button>

        <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-500/10 border border-emerald-500/30 live-update-indicator">
            <span class="relative flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
            </span>
            <span class="text-emerald-500 text-sm font-semibold" data-status="live-text">Live</span>
        </div>
        
        <div class="text-right theme-card px-5 py-3 rounded-xl shadow-xl">
            <div class="flex items-center gap-2 text-xs text-gray-500 uppercase tracking-wider mb-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Last Update
            </div>
            <div class="text-sm font-bold header-title" data-update="last_update"><?= $last_update ?></div>
        </div>
    </div>
</div>

<!-- QUICK ACTIONS -->
<div class="relative theme-card rounded-3xl p-8 mb-10 shadow-2xl overflow-hidden">
    <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/5 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-purple-500/5 rounded-full blur-3xl"></div>
    
    <div class="relative">
        <div class="flex items-center gap-3 mb-6">
            <div class="p-2.5 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-xl card-title">Quick Actions</h3>
                <p class="text-xs text-gray-500">Akses cepat ke fitur utama sistem</p>
            </div>
        </div>
        
        <div class="grid grid-cols-4 gap-5">
            <?php
            $actions = [
                ['url'=>'inventory', 'label'=>'Inventory IT', 'desc'=>'Kelola aset IT', 'gradient'=>'from-blue-600 via-blue-500 to-cyan-500', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>'],
                ['url'=>'ticket', 'label'=>'Ticket System', 'desc'=>'Manajemen ticket', 'gradient'=>'from-purple-600 via-purple-500 to-pink-500', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>'],
                ['url'=>'inventory/scan_camera', 'label'=>'QR Scanner', 'desc'=>'Scan perangkat', 'gradient'=>'from-green-600 via-emerald-500 to-teal-500', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>'],
                ['url'=>'laporan', 'label'=>'Laporan', 'desc'=>'Analytics & reports', 'gradient'=>'from-orange-600 via-red-500 to-pink-500', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>'],
            ];
            foreach ($actions as $a):
            ?>
            <a href="<?= base_url(''.$a['url']) ?>"
               class="group relative p-6 rounded-2xl bg-gradient-to-br <?= $a['gradient'] ?> 
                      hover:scale-105 active:scale-95 transition-all duration-300 
                      shadow-lg hover:shadow-2xl overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent 
                            translate-x-[-200%] group-hover:translate-x-[200%] transition-transform duration-1000"></div>
                <div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>
                <div class="relative">
                    <div class="flex items-center justify-center h-14 w-14 mb-4 mx-auto
                                rounded-xl bg-white/20 backdrop-blur-sm
                                group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <?= $a['icon'] ?>
                        </svg>
                    </div>
                    <div class="text-center">
                        <div class="text-white font-bold text-base mb-1"><?= $a['label'] ?></div>
                        <div class="text-white/70 text-xs"><?= $a['desc'] ?></div>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- STAT CARDS -->
<div class="grid grid-cols-4 gap-6 mb-10">
<?php
$stats = [
    ['label'=>'Administrator', 'value'=>$total_admin, 'key'=>'total_admin', 'gradient'=>'from-blue-600 to-blue-400', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>', 'badge'=>'Aktif', 'trend'=>'+12%'],
    ['label'=>'Teknisi', 'value'=>$total_teknisi, 'key'=>'total_teknisi', 'gradient'=>'from-indigo-600 to-purple-400', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>', 'badge'=>'Standby', 'trend'=>'+5%'],
    ['label'=>'Total User', 'value'=>$total_user, 'key'=>'total_user', 'gradient'=>'from-slate-600 to-slate-400', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>', 'badge'=>'Terdaftar', 'trend'=>'+8%'],
    ['label'=>'Total Perangkat', 'value'=>$total_perangkat, 'key'=>'total_perangkat', 'gradient'=>'from-emerald-600 to-green-400', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>', 'badge'=>'Assets', 'trend'=>'+15%'],
];
foreach ($stats as $s):
?>
<div class="group relative stat-card theme-card rounded-3xl p-6 overflow-hidden
            hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br <?= $s['gradient'] ?> 
                opacity-10 rounded-full -mr-16 -mt-16 group-hover:scale-150 
                transition-transform duration-700 ease-out blur-2xl"></div>
    <div class="relative">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-gradient-to-br <?= $s['gradient'] ?> rounded-xl shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <?= $s['icon'] ?>
                </svg>
            </div>
            <div class="px-2.5 py-1 rounded-lg bg-emerald-500/10 border border-emerald-500/30">
                <span class="text-emerald-500 text-xs font-bold"><?= $s['trend'] ?></span>
            </div>
        </div>
        <div class="mb-2">
            <div class="text-xs text-gray-500 uppercase tracking-wider font-medium mb-1"><?= $s['label'] ?></div>
            <div class="flex items-baseline gap-2">
                <div class="text-4xl font-black bg-gradient-to-r <?= $s['gradient'] ?> bg-clip-text text-transparent" 
                     data-stat="<?= $s['key'] ?>"><?= $s['value'] ?></div>
                <span class="text-xs text-gray-500 font-medium"><?= $s['badge'] ?></span>
            </div>
        </div>
        <div class="mt-4 h-1.5 progress-bg rounded-full overflow-hidden">
            <div class="h-full bg-gradient-to-r <?= $s['gradient'] ?> rounded-full transition-all duration-1000" 
                 style="width: <?= min(($s['value'] / 100) * 100, 100) ?>%"></div>
        </div>
    </div>
</div>
<?php endforeach; ?>
</div>

<!-- SYSTEM HEALTH -->
<?php
$total_it = $it_baik + $it_rusak_ringan + $it_rusak_berat;
$persen_sehat = $total_it > 0 ? round(($it_baik / $total_it) * 100) : 0;
$insight_color = $persen_sehat >= 80 ? 'emerald' : ($persen_sehat >= 60 ? 'yellow' : 'red');
$insight_status = $persen_sehat >= 80 ? 'Excellent' : ($persen_sehat >= 60 ? 'Good' : 'Critical');
?>
<div class="relative health-card theme-card rounded-3xl p-8 mb-10 shadow-2xl overflow-hidden">
    <div class="absolute top-0 left-0 w-72 h-72 bg-blue-500/5 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 right-0 w-72 h-72 bg-purple-500/5 rounded-full blur-3xl"></div>
    
    <div class="relative">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-2xl card-title">System Health Overview</h3>
                    <p class="text-sm text-gray-500">Monitoring kondisi perangkat secara real-time</p>
                </div>
            </div>
            <div class="px-4 py-2 rounded-xl bg-<?= $insight_color ?>-500/20 border border-<?= $insight_color ?>-500/40">
                <span class="text-<?= $insight_color ?>-500 font-bold text-sm"><?= $insight_status ?></span>
            </div>
        </div>
        
        <div class="grid grid-cols-3 gap-6">
            <div class="group relative health-box backdrop-blur-sm rounded-2xl p-6 
                        border border-<?= $insight_color ?>-500/30
                        hover:border-<?= $insight_color ?>-500/50 hover:shadow-xl hover:shadow-<?= $insight_color ?>-500/10
                        transition-all duration-300">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2.5 bg-<?= $insight_color ?>-500/20 rounded-xl">
                        <svg class="w-6 h-6 text-<?= $insight_color ?>-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="text-sm text-gray-500 font-semibold">System Health</div>
                </div>
                <div class="flex items-baseline gap-2 mb-3">
                    <span class="text-5xl font-black text-<?= $insight_color ?>-500" data-stat="system_health"><?= $persen_sehat ?></span>
                    <span class="text-2xl text-<?= $insight_color ?>-500">%</span>
                </div>
                <div class="text-xs text-gray-500 mb-4">Perangkat dalam kondisi optimal</div>
                <div class="relative h-2.5 progress-bg rounded-full overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-<?= $insight_color ?>-600/20 to-transparent"></div>
                    <div class="h-full bg-gradient-to-r from-<?= $insight_color ?>-500 to-<?= $insight_color ?>-400 rounded-full transition-all duration-1000 shadow-lg shadow-<?= $insight_color ?>-500/50"
                         data-progress="system_health" style="width: <?= $persen_sehat ?>%"></div>
                </div>
            </div>
            
            <div class="group relative health-box backdrop-blur-sm rounded-2xl p-6 
                        border border-red-500/30 hover:border-red-500/50 hover:shadow-xl hover:shadow-red-500/10 transition-all duration-300">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2.5 bg-red-500/20 rounded-xl">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="text-sm text-gray-500 font-semibold">Critical Issues</div>
                </div>
                <div class="flex items-baseline gap-3 mb-3">
                    <span class="text-5xl font-black text-red-500" data-stat="it_rusak_berat"><?= $it_rusak_berat ?></span>
                    <span class="text-lg text-gray-500 font-medium">units</span>
                </div>
                <div class="text-xs text-gray-500 mb-4">Memerlukan perbaikan segera</div>
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-red-500/10 border border-red-500/30">
                    <svg class="w-3.5 h-3.5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-red-500 text-xs font-semibold">High Priority</span>
                </div>
            </div>
            
            <div class="group relative health-box backdrop-blur-sm rounded-2xl p-6 
                        border border-amber-500/30 hover:border-amber-500/50 hover:shadow-xl hover:shadow-amber-500/10 transition-all duration-300">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2.5 bg-amber-500/20 rounded-xl">
                        <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="text-sm text-gray-500 font-semibold">Pending Tasks</div>
                </div>
                <div class="flex items-baseline gap-3 mb-3">
                    <span class="text-5xl font-black text-amber-500" data-stat="ticket_menunggu"><?= $ticket_menunggu ?></span>
                    <span class="text-lg text-gray-500 font-medium">tickets</span>
                </div>
                <div class="text-xs text-gray-500 mb-4">Menunggu penanganan teknisi</div>
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-amber-500/10 border border-amber-500/30">
                    <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-amber-500 text-xs font-semibold">Action Required</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- KONDISI PERANGKAT -->
<div class="grid grid-cols-2 gap-8 mb-10">
    <div class="relative device-card theme-card rounded-3xl p-8 shadow-2xl overflow-hidden">
        <div class="absolute top-0 right-0 w-48 h-48 bg-blue-500/5 rounded-full blur-3xl"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-xl card-title">Perangkat IT</h3>
                        <p class="text-xs text-gray-500">Status perangkat teknologi</p>
                    </div>
                </div>
                <div class="text-2xl font-black text-blue-500" data-stat="total_it"><?= $total_it ?></div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <?php
                $it_conditions = [
                    ['label'=>'Baik', 'value'=>$it_baik, 'key'=>'it_baik', 'color'=>'green', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                    ['label'=>'Rusak Ringan', 'value'=>$it_rusak_ringan, 'key'=>'it_rusak_ringan', 'color'=>'yellow', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>'],
                    ['label'=>'Rusak Berat', 'value'=>$it_rusak_berat, 'key'=>'it_rusak_berat', 'color'=>'red', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                ];
                foreach ($it_conditions as $c):
                ?>
                <div class="group relative bg-<?= $c['color'] ?>-500/10 backdrop-blur-sm
                            border border-<?= $c['color'] ?>-500/30 rounded-2xl p-5 text-center condition-badge
                            hover:bg-<?= $c['color'] ?>-500/20 hover:border-<?= $c['color'] ?>-500/50
                            hover:shadow-xl hover:shadow-<?= $c['color'] ?>-500/10 hover:scale-105 transition-all duration-300">
                    <div class="flex justify-center mb-3">
                        <div class="p-2 bg-<?= $c['color'] ?>-500/20 rounded-lg">
                            <svg class="w-5 h-5 text-<?= $c['color'] ?>-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <?= $c['icon'] ?>
                            </svg>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500 uppercase tracking-wider font-medium mb-2"><?= $c['label'] ?></div>
                    <div class="text-4xl font-black text-<?= $c['color'] ?>-500" data-stat="<?= $c['key'] ?>"><?= $c['value'] ?></div>
                    <div class="mt-3 text-[10px] text-gray-500"><?= $total_it > 0 ? round(($c['value'] / $total_it) * 100, 1) : 0 ?>% dari total</div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <?php $total_umum = $umum_baik + $umum_rusak_ringan + $umum_rusak_berat; ?>
    <div class="relative device-card theme-card rounded-3xl p-8 shadow-2xl overflow-hidden">
        <div class="absolute top-0 right-0 w-48 h-48 bg-purple-500/5 rounded-full blur-3xl"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-xl card-title">Perangkat Umum</h3>
                        <p class="text-xs text-gray-500">Status perangkat kantor</p>
                    </div>
                </div>
                <div class="text-2xl font-black text-purple-500" data-stat="total_umum"><?= $total_umum ?></div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <?php
                $umum_conditions = [
                    ['label'=>'Baik', 'value'=>$umum_baik, 'key'=>'umum_baik', 'color'=>'green', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                    ['label'=>'Rusak Ringan', 'value'=>$umum_rusak_ringan, 'key'=>'umum_rusak_ringan', 'color'=>'yellow', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>'],
                    ['label'=>'Rusak Berat', 'value'=>$umum_rusak_berat, 'key'=>'umum_rusak_berat', 'color'=>'red', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                ];
                foreach ($umum_conditions as $c):
                ?>
                <div class="group relative bg-<?= $c['color'] ?>-500/10 backdrop-blur-sm
                            border border-<?= $c['color'] ?>-500/30 rounded-2xl p-5 text-center condition-badge
                            hover:bg-<?= $c['color'] ?>-500/20 hover:border-<?= $c['color'] ?>-500/50
                            hover:shadow-xl hover:shadow-<?= $c['color'] ?>-500/10 hover:scale-105 transition-all duration-300">
                    <div class="flex justify-center mb-3">
                        <div class="p-2 bg-<?= $c['color'] ?>-500/20 rounded-lg">
                            <svg class="w-5 h-5 text-<?= $c['color'] ?>-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <?= $c['icon'] ?>
                            </svg>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500 uppercase tracking-wider font-medium mb-2"><?= $c['label'] ?></div>
                    <div class="text-4xl font-black text-<?= $c['color'] ?>-500" data-stat="<?= $c['key'] ?>"><?= $c['value'] ?></div>
                    <div class="mt-3 text-[10px] text-gray-500"><?= $total_umum > 0 ? round(($c['value'] / $total_umum) * 100, 1) : 0 ?>% dari total</div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- CHARTS -->
<div class="grid grid-cols-2 gap-8 mb-10">

    <!-- CHART KIRI: Distribusi IT per Lokasi (Horizontal Bar Chart) -->
    <div class="relative chart-container theme-card rounded-3xl p-8 shadow-2xl overflow-hidden">
        <div class="absolute top-0 left-0 w-48 h-48 bg-blue-500/5 rounded-full blur-3xl"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-500/20 rounded-lg">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg card-title">Distribusi Perangkat IT per Lokasi</h3>
                </div>
            </div>
            <canvas id="chartIT" height="280"></canvas>
        </div>
    </div>

    <!-- CHART KANAN: Distribusi Perangkat Umum (Doughnut) -->
    <div class="relative chart-container theme-card rounded-3xl p-8 shadow-2xl overflow-hidden">
        <div class="absolute top-0 right-0 w-48 h-48 bg-purple-500/5 rounded-full blur-3xl"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-purple-500/20 rounded-lg">
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg card-title">Distribusi Perangkat Umum</h3>
                </div>
            </div>
            <canvas id="chartUmum" height="220"></canvas>
        </div>
    </div>
</div>

<!-- TICKET STATUS -->
<div class="relative ticket-card theme-card rounded-3xl p-8 shadow-2xl overflow-hidden">
    <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-500/5 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-purple-500/5 rounded-full blur-3xl"></div>
    <div class="relative">
        <div class="flex items-center gap-3 mb-6">
            <div class="p-3 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-2xl card-title">Ticket Management</h3>
                <p class="text-sm text-gray-500">Status dan distribusi ticket support</p>
            </div>
        </div>
        <div class="grid grid-cols-4 gap-5">
            <?php
            $tickets = [
                ['label'=>'Menunggu', 'value'=>$ticket_menunggu, 'key'=>'ticket_menunggu', 'color'=>'amber', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                ['label'=>'On Progress', 'value'=>$ticket_proses, 'key'=>'ticket_proses', 'color'=>'blue', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>'],
                ['label'=>'Selesai', 'value'=>$ticket_selesai, 'key'=>'ticket_selesai', 'color'=>'green', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                ['label'=>'Dibatalkan', 'value'=>$ticket_batal, 'key'=>'ticket_batal', 'color'=>'red', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
            ];
            foreach ($tickets as $t):
            ?>
            <div class="group relative bg-<?= $t['color'] ?>-500/10 backdrop-blur-sm
                        border border-<?= $t['color'] ?>-500/30 rounded-2xl p-6 text-center condition-badge
                        hover:bg-<?= $t['color'] ?>-500/20 hover:border-<?= $t['color'] ?>-500/50
                        hover:shadow-2xl hover:shadow-<?= $t['color'] ?>-500/10 hover:scale-105 transition-all duration-300">
                <div class="flex justify-center mb-4">
                    <div class="p-3 bg-<?= $t['color'] ?>-500/20 rounded-xl">
                        <svg class="w-7 h-7 text-<?= $t['color'] ?>-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <?= $t['icon'] ?>
                        </svg>
                    </div>
                </div>
                <div class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-3"><?= $t['label'] ?></div>
                <div class="text-5xl font-black text-<?= $t['color'] ?>-500 mb-2" data-stat="<?= $t['key'] ?>"><?= $t['value'] ?></div>
                <div class="flex justify-center">
                    <div class="w-16 h-1 bg-<?= $t['color'] ?>-500/30 rounded-full overflow-hidden">
                        <div class="h-full bg-<?= $t['color'] ?>-500 rounded-full" style="width: 60%"></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

</main>

<!-- CHART.JS & LIVE UPDATE SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// =============================================
// THEME HELPER FUNCTIONS
// =============================================
const getChartColor = () => {
    return document.documentElement.classList.contains('light') ? '#64748b' : '#94a3b8';
};

const getTooltipBg = () => {
    return document.documentElement.classList.contains('light')
        ? 'rgba(255, 255, 255, 0.98)'
        : 'rgba(15, 23, 42, 0.95)';
};

const getTooltipBorder = () => {
    return document.documentElement.classList.contains('light')
        ? 'rgba(226, 232, 240, 0.8)'
        : 'rgba(148, 163, 184, 0.2)';
};

const getTitleColor = () => {
    return document.documentElement.classList.contains('light') ? '#0f172a' : '#f1f5f9';
};

const getBodyColor = () => {
    return document.documentElement.classList.contains('light') ? '#475569' : '#cbd5e1';
};

const getGridColor = () => {
    return document.documentElement.classList.contains('light')
        ? 'rgba(0,0,0,0.06)'
        : 'rgba(255,255,255,0.06)';
};

Chart.defaults.color = getChartColor();
Chart.defaults.font.family = "'Inter', 'system-ui', '-apple-system', 'sans-serif'";
Chart.defaults.font.size = 12;

// =============================================
// CENTER TEXT PLUGIN (untuk doughnut chartUmum)
// =============================================
const centerTextPlugin = {
    id: 'centerText',
    beforeDraw: (chart) => {
        if (chart.config.options.elements?.center) {
            const ctx = chart.ctx;
            const centerConfig = chart.config.options.elements.center;
            const fontStyle = centerConfig.fontStyle || 'bold';
            const txt = centerConfig.text;
            const color = centerConfig.color || getTitleColor();
            const maxFontSize = centerConfig.maxFontSize || 40;
            const sidePadding = centerConfig.sidePadding || 20;
            const sidePaddingCalculated = (sidePadding / 100) * (chart.innerRadius * 2);

            ctx.font = fontStyle + ' ' + maxFontSize + 'px Inter';
            const stringWidth = ctx.measureText(txt).width;
            const elementWidth = (chart.innerRadius * 2) - sidePaddingCalculated;

            const widthRatio = elementWidth / stringWidth;
            const newFontSize = Math.floor(30 * widthRatio);
            const elementHeight = (chart.innerRadius * 2);
            const fontSizeToUse = Math.min(newFontSize, elementHeight, maxFontSize);

            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            const centerX = ((chart.chartArea.left + chart.chartArea.right) / 2);
            const centerY = ((chart.chartArea.top + chart.chartArea.bottom) / 2);
            ctx.font = fontStyle + ' ' + fontSizeToUse + 'px Inter';
            ctx.fillStyle = color;
            ctx.fillText(txt, centerX, centerY);
        }
    }
};

Chart.register(centerTextPlugin);

// =============================================
// DOUGHNUT BASE CONFIG (untuk chartUmum)
// =============================================
const doughnutConfig = {
    type: 'doughnut',
    options: {
        responsive: true,
        maintainAspectRatio: true,
        cutout: '75%',
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
                labels: {
                    padding: 20,
                    font: { size: 11, weight: '600' },
                    usePointStyle: true,
                    pointStyle: 'circle',
                    color: getChartColor()
                }
            },
            tooltip: {
                enabled: true,
                backgroundColor: getTooltipBg(),
                padding: 16,
                borderColor: getTooltipBorder(),
                borderWidth: 1,
                titleColor: getTitleColor(),
                titleFont: { size: 13, weight: 'bold' },
                bodyColor: getBodyColor(),
                bodyFont: { size: 12 },
                cornerRadius: 12,
                displayColors: true,
                boxPadding: 6,
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.parsed || 0;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((value / total) * 100).toFixed(1);
                        return ' ' + label + ': ' + value + ' (' + percentage + '%)';
                    }
                }
            }
        },
        animation: {
            animateRotate: true,
            animateScale: true,
            duration: 1500,
            easing: 'easeInOutQuart'
        }
    }
};

const labels = ['Baik', 'Rusak Ringan', 'Rusak Berat'];
const colors = ['#22c55e', '#eab308', '#ef4444'];
const borderColors = ['#16a34a', '#ca8a04', '#dc2626'];

// =============================================
// CHART IT: Horizontal Bar Chart per Lokasi
// =============================================

// Data lokasi dari PHP
const lokasiLabels = <?= json_encode($it_lokasi_labels) ?>;
const lokasiValues = <?= json_encode($it_lokasi_values) ?>;

// Warna gradasi biru untuk tiap bar
const barColors = lokasiValues.map((_, i) => {
    const alpha = 1 - (i / Math.max(lokasiValues.length, 1)) * 0.5;
    return `rgba(59, 130, 246, ${alpha})`;
});
const barBorderColors = lokasiValues.map((_, i) => {
    const alpha = 1 - (i / Math.max(lokasiValues.length, 1)) * 0.3;
    return `rgba(37, 99, 235, ${alpha})`;
});

const chartIT = new Chart(document.getElementById('chartIT'), {
    type: 'bar',
    data: {
        labels: lokasiLabels,
        datasets: [{
            label: 'Jumlah Perangkat',
            data: lokasiValues,
            backgroundColor: barColors,
            borderColor: barBorderColors,
            borderWidth: 2,
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        indexAxis: 'y', // horizontal bar
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: getTooltipBg(),
                padding: 14,
                borderColor: getTooltipBorder(),
                borderWidth: 1,
                titleColor: getTitleColor(),
                titleFont: { size: 13, weight: 'bold' },
                bodyColor: getBodyColor(),
                bodyFont: { size: 12 },
                cornerRadius: 10,
                callbacks: {
                    label: ctx => ` ${ctx.parsed.x} perangkat`
                }
            }
        },
        scales: {
            x: {
                beginAtZero: true,
                ticks: {
                    precision: 0,
                    color: getChartColor(),
                    font: { size: 11 }
                },
                grid: {
                    color: getGridColor()
                }
            },
            y: {
                ticks: {
                    color: getChartColor(),
                    font: { size: 11 },
                    // Potong label panjang
                    callback: function(val) {
                        const label = this.getLabelForValue(val);
                        return label.length > 20 ? label.substring(0, 20) + '…' : label;
                    }
                },
                grid: { display: false }
            }
        },
        animation: {
            duration: 1200,
            easing: 'easeInOutQuart'
        }
    }
});

// =============================================
// CHART UMUM: Doughnut
// =============================================
const totalUmum = <?= $umum_baik + $umum_rusak_ringan + $umum_rusak_berat ?>;
const chartUmum = new Chart(document.getElementById('chartUmum'), {
    ...doughnutConfig,
    data: {
        labels,
        datasets: [{
            data: [<?= $umum_baik ?>, <?= $umum_rusak_ringan ?>, <?= $umum_rusak_berat ?>],
            backgroundColor: colors,
            borderColor: borderColors,
            borderWidth: 2,
            hoverOffset: 15,
            hoverBorderWidth: 3
        }]
    },
    options: {
        ...doughnutConfig.options,
        elements: {
            center: {
                text: totalUmum + '',
                color: getTitleColor(),
                fontStyle: 'bold',
                sidePadding: 20,
                maxFontSize: 40
            }
        }
    }
});

// =============================================
// UPDATE CHART COLORS SAAT THEME BERUBAH
// =============================================
if (window.toggleTheme) {
    const originalToggle = window.toggleTheme;
    window.toggleTheme = function() {
        originalToggle();
        setTimeout(() => {
            // Update defaults
            Chart.defaults.color = getChartColor();

            // Update chartIT (bar)
            chartIT.options.plugins.tooltip.backgroundColor = getTooltipBg();
            chartIT.options.plugins.tooltip.borderColor = getTooltipBorder();
            chartIT.options.plugins.tooltip.titleColor = getTitleColor();
            chartIT.options.plugins.tooltip.bodyColor = getBodyColor();
            chartIT.options.scales.x.ticks.color = getChartColor();
            chartIT.options.scales.x.grid.color = getGridColor();
            chartIT.options.scales.y.ticks.color = getChartColor();
            chartIT.update();

            // Update chartUmum (doughnut)
            chartUmum.options.plugins.legend.labels.color = getChartColor();
            chartUmum.options.elements.center.color = getTitleColor();
            chartUmum.update();
        }, 100);
    };
}

// =============================================
// LIVE UPDATE SYSTEM
// =============================================
const LIVE_UPDATE_CONFIG = {
    interval: 30000,
    endpoint: '<?= base_url("dashboard/get_live_data") ?>',
    animationDuration: 600
};

let updateInterval = null;
let isUpdating = false;
let updateCount = 0;

const animateValue = (element, start, end, duration = 800) => {
    if (!element) return;
    const range = end - start;
    const increment = range / (duration / 16);
    let current = start;
    const timer = setInterval(() => {
        current += increment;
        if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
            current = end;
            clearInterval(timer);
        }
        element.textContent = Math.round(current);
    }, 16);
};

const showUpdateIndicator = () => {
    const indicator = document.querySelector('.live-update-indicator');
    if (indicator) {
        indicator.classList.add('updating');
        setTimeout(() => indicator.classList.remove('updating'), 1000);
    }
};

const updateElement = (selector, value, animate = true) => {
    const element = document.querySelector(selector);
    if (!element) return;
    const oldValue = parseInt(element.textContent) || 0;
    if (animate && oldValue !== value) {
        element.style.transition = 'transform 0.3s ease';
        element.style.transform = 'scale(1.1)';
        setTimeout(() => {
            animateValue(element, oldValue, value);
            setTimeout(() => {
                element.style.transform = 'scale(1)';
            }, 100);
        }, 100);
    } else {
        element.textContent = value;
    }
};

const updateProgressBar = (selector, percentage) => {
    const bar = document.querySelector(selector);
    if (bar) {
        bar.style.width = `${Math.min(percentage, 100)}%`;
    }
};

const showNotification = (type, message) => {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-xl shadow-2xl 
                             backdrop-blur-xl border transition-all duration-500 transform translate-x-full`;
    const colorMap = {
        success: 'bg-emerald-500/10 border-emerald-500/30 text-emerald-500',
        warning: 'bg-amber-500/10 border-amber-500/30 text-amber-500',
        error: 'bg-red-500/10 border-red-500/30 text-red-500'
    };
    notification.className += ` ${colorMap[type] || colorMap.success}`;
    const iconPath = type === 'success'
        ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
        : type === 'error'
        ? 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
        : 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z';
    notification.innerHTML = `
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${iconPath}"/>
            </svg>
            <span class="font-semibold">${message}</span>
        </div>
    `;
    document.body.appendChild(notification);
    setTimeout(() => notification.classList.remove('translate-x-full'), 100);
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => notification.remove(), 500);
    }, 3000);
};

const fetchLiveData = async () => {
    if (isUpdating) return;
    isUpdating = true;
    showUpdateIndicator();
    try {
        const response = await fetch(LIVE_UPDATE_CONFIG.endpoint, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        const data = await response.json();
        if (data.status !== 'success') throw new Error('Invalid response status');

        // Update stat cards
        updateElement('[data-stat="total_admin"]', data.total_admin);
        updateElement('[data-stat="total_teknisi"]', data.total_teknisi);
        updateElement('[data-stat="total_user"]', data.total_user);
        updateElement('[data-stat="total_perangkat"]', data.total_perangkat);

        // Update perangkat IT
        updateElement('[data-stat="it_baik"]', data.it_baik);
        updateElement('[data-stat="it_rusak_ringan"]', data.it_rusak_ringan);
        updateElement('[data-stat="it_rusak_berat"]', data.it_rusak_berat);
        const totalIT = data.it_baik + data.it_rusak_ringan + data.it_rusak_berat;
        updateElement('[data-stat="total_it"]', totalIT);

        // Update perangkat umum
        updateElement('[data-stat="umum_baik"]', data.umum_baik);
        updateElement('[data-stat="umum_rusak_ringan"]', data.umum_rusak_ringan);
        updateElement('[data-stat="umum_rusak_berat"]', data.umum_rusak_berat);
        const totalUmumLive = data.umum_baik + data.umum_rusak_ringan + data.umum_rusak_berat;
        updateElement('[data-stat="total_umum"]', totalUmumLive);

        // Update tickets
        updateElement('[data-stat="ticket_menunggu"]', data.ticket_menunggu);
        updateElement('[data-stat="ticket_proses"]', data.ticket_proses);
        updateElement('[data-stat="ticket_selesai"]', data.ticket_selesai);
        updateElement('[data-stat="ticket_batal"]', data.ticket_batal);

        // Update system health
        const persenSehat = totalIT > 0 ? Math.round((data.it_baik / totalIT) * 100) : 0;
        updateElement('[data-stat="system_health"]', persenSehat);
        updateProgressBar('[data-progress="system_health"]', persenSehat);

        // =============================================
        // UPDATE CHART IT (Horizontal Bar - per lokasi)
        // =============================================
        if (data.it_lokasi_labels && data.it_lokasi_values) {
            chartIT.data.labels = data.it_lokasi_labels;
            chartIT.data.datasets[0].data = data.it_lokasi_values;

            // Regenerate warna gradasi sesuai jumlah data terbaru
            const newBarColors = data.it_lokasi_values.map((_, i) => {
                const alpha = 1 - (i / Math.max(data.it_lokasi_values.length, 1)) * 0.5;
                return `rgba(59, 130, 246, ${alpha})`;
            });
            const newBarBorderColors = data.it_lokasi_values.map((_, i) => {
                const alpha = 1 - (i / Math.max(data.it_lokasi_values.length, 1)) * 0.3;
                return `rgba(37, 99, 235, ${alpha})`;
            });
            chartIT.data.datasets[0].backgroundColor = newBarColors;
            chartIT.data.datasets[0].borderColor = newBarBorderColors;
            chartIT.update('active');
        }

        // =============================================
        // UPDATE CHART UMUM (Doughnut)
        // =============================================
        chartUmum.data.datasets[0].data = [data.umum_baik, data.umum_rusak_ringan, data.umum_rusak_berat];
        // Update center text totalUmum
        chartUmum.options.elements.center.text = String(totalUmumLive);
        chartUmum.update('active');

        // Update last update timestamp
        const lastUpdateEl = document.querySelector('[data-update="last_update"]');
        if (lastUpdateEl) lastUpdateEl.textContent = data.last_update;

        // Notifikasi tiket baru
        const notifBanner = document.querySelector('[data-ticket-count]');
        if (notifBanner) {
            const currentCount = parseInt(notifBanner.dataset.ticketCount) || 0;
            if (data.ticket_menunggu > currentCount && data.ticket_menunggu > 0) {
                showNotification('warning', `Ticket baru menunggu: ${data.ticket_menunggu}`);
            }
            notifBanner.dataset.ticketCount = data.ticket_menunggu;
        }

        updateCount++;
        console.log(`✅ Dashboard updated successfully (${updateCount} times)`);
    } catch (error) {
        console.error('❌ Live update error:', error);
        showNotification('error', 'Gagal memperbarui data dashboard');
    } finally {
        isUpdating = false;
    }
};

const startLiveUpdates = () => {
    if (updateInterval) return;
    console.log('🚀 Starting live dashboard updates...');
    fetchLiveData();
    updateInterval = setInterval(fetchLiveData, LIVE_UPDATE_CONFIG.interval);
    const statusText = document.querySelector('[data-status="live-text"]');
    if (statusText) {
        statusText.textContent = 'Live';
        statusText.parentElement.classList.remove('opacity-50');
    }
};

const stopLiveUpdates = () => {
    if (updateInterval) {
        clearInterval(updateInterval);
        updateInterval = null;
        console.log('⏸️ Live updates stopped');
        const statusText = document.querySelector('[data-status="live-text"]');
        if (statusText) {
            statusText.textContent = 'Paused';
            statusText.parentElement.classList.add('opacity-50');
        }
    }
};

document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
        console.log('🔕 Tab hidden - pausing updates');
        stopLiveUpdates();
    } else {
        console.log('🔔 Tab visible - resuming updates');
        startLiveUpdates();
    }
});

document.addEventListener('DOMContentLoaded', () => {
    console.log('📊 Dashboard initialized');
    startLiveUpdates();
});

window.addEventListener('beforeunload', () => {
    stopLiveUpdates();
});
</script>

<!-- ADDITIONAL THEME STYLES -->
<style>
/* Theme Toggle Button Styles */
.theme-toggle-btn {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
}
.theme-toggle-btn:hover {
    border-color: var(--border-hover);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}
.theme-toggle-btn svg {
    color: var(--text-secondary);
}
.theme-toggle-btn:hover svg {
    color: var(--text-primary);
}
html.light .theme-toggle-btn {
    background: rgba(241, 245, 249, 0.9);
    border-color: rgba(203, 213, 225, 0.8);
}
html.light .theme-toggle-btn:hover {
    background: rgba(226, 232, 240, 0.95);
    border-color: rgba(148, 163, 184, 0.9);
}

/* Helper Classes */
.card-title { color: var(--text-primary); }
.header-title { color: var(--text-primary); }
.header-subtitle { color: var(--text-secondary); }
.progress-bg { background: var(--bg-secondary); }
.health-box { background: var(--bg-card); }
html.light .health-box { background: rgba(248, 250, 252, 0.8); }
.condition-badge { backdrop-filter: blur(4px); }

/* Notification Banner Light Mode Overrides */
html.light .notification-title { color: #92400e !important; }
html.light .notification-text { color: #78350f !important; }
html.light .notification-cta { color: #92400e !important; }

/* Animations */
@keyframes fade-in {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
    animation: fade-in 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}
.live-update-indicator.updating {
    animation: pulse-indicator 1s ease-in-out;
}
@keyframes pulse-indicator {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.7; transform: scale(1.1); }
}
[data-stat] {
    transition: all 0.3s ease;
}
</style>