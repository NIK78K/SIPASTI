<?php
// ============================================================
// PARSE DI VIEW — mendukung format lama & baru (multi-day)
// ============================================================
function parse_zoom_view($teks) {
    $r = [
        'detail_permintaan' => '',
        'jumlah_peserta'    => '',
        'jumlah_breakout'   => '',
        'penanggung_jawab'  => '',
        'host_cohost'       => '',
        'permintaan_lain'   => '',
        'periode'           => '',
        'tanggal_teks'      => '',
        'jam_per_hari'      => '',
        'durasi_per_hari'   => '',
        'total_durasi'      => '',
        'lokasi_unit'       => '',
    ];
    if (empty($teks)) return $r;

    $lines = explode("\n", $teks);
    $i = 0; $max = count($lines);

    while ($i < $max) {
        $line = trim($lines[$i]);
        if      (strpos($line, 'Detail Permintaan:')    === 0) $r['detail_permintaan'] = trim(substr($line, 18));
        elseif  (strpos($line, 'Periode:')              === 0) $r['periode']           = trim(substr($line, 8));
        elseif  (strpos($line, 'Tanggal:')              === 0) $r['tanggal_teks']      = trim(substr($line, 8));
        elseif  (strpos($line, 'Jam Mulai (per hari):') === 0) $r['jam_per_hari']      = trim(substr($line, 21));
        elseif  (strpos($line, 'Durasi (per hari):')    === 0) $r['durasi_per_hari']   = trim(substr($line, 18));
        elseif  (strpos($line, 'Total Durasi:')         === 0) $r['total_durasi']      = trim(substr($line, 13));
        elseif  (strpos($line, 'Jumlah Peserta:')       === 0) $r['jumlah_peserta']    = trim(str_replace([' orang','Jumlah Peserta:'], '', $line));
        elseif  (strpos($line, 'Jumlah Breakout Room:') === 0) $r['jumlah_breakout']   = trim(substr($line, 21));
        elseif  (strpos($line, 'Penanggung Jawab:')     === 0) $r['penanggung_jawab']  = trim(substr($line, 17));
        elseif  (strpos($line, 'Host/Co-Host:')         === 0) $r['host_cohost']       = trim(substr($line, 13));
        elseif  (strpos($line, 'Lokasi/Unit:')          === 0) $r['lokasi_unit']       = trim(substr($line, 12));
        elseif  (strpos($line, 'Permintaan Lainnya:')   === 0) {
            $buf = trim(substr($line, 19));
            $i++;
            while ($i < $max) {
                $next = trim($lines[$i]);
                if (preg_match('/^(Detail Permintaan|Periode|Tanggal|Jam Mulai|Durasi|Total Durasi|Jumlah Peserta|Jumlah Breakout|Penanggung Jawab|Host\/Co-Host|Lokasi\/Unit):/', $next)) break;
                $buf .= ($buf ? "\n" : '') . $next;
                $i++;
            }
            $r['permintaan_lain'] = $buf;
            continue;
        }
        $i++;
    }
    return $r;
}

/* Helper: apakah tiket multi-day */
function is_multiday($t) {
    return !empty($t['tanggal_selesai']);
}

/* Helper: label periode untuk ditampilkan */
function label_periode($t) {
    if (is_multiday($t)) {
        $tM = new DateTime($t['tanggal']);
        $tS = new DateTime($t['tanggal_selesai']);
        $hari = $tS->diff($tM)->days + 1;
        return date('d M', strtotime($t['tanggal']))
             . ' – ' . date('d M Y', strtotime($t['tanggal_selesai']))
             . ' (' . $hari . ' hari)';
    }
    return date('d M Y', strtotime($t['tanggal']));
}

$zoom1_groups = $zoom_grouped['zoom1'] ?? [];
$zoom2_groups = $zoom_grouped['zoom2'] ?? [];

$zoom1_flat = [];
$zoom2_flat = [];
foreach ($zoom1_groups as $grp) {
    foreach ($grp['tikets'] as $t) $zoom1_flat[] = array_merge($t, ['akun_zoom' => 1]);
}
foreach ($zoom2_groups as $grp) {
    foreach ($grp['tikets'] as $t) $zoom2_flat[] = array_merge($t, ['akun_zoom' => 2]);
}
?>

<!-- JUDUL -->
<h1 class="text-2xl font-semibold mb-6 header-title">Manajemen Zoom</h1>

<!-- ============================================================
     STAT CARDS
============================================================ -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">

    <div class="theme-card stat-card border border-blue-500/20 rounded-xl p-5 hover:scale-[1.02] transition">
        <div class="text-blue-400 text-sm mb-1 header-subtitle">Total Zoom</div>
        <div class="text-3xl font-semibold text-blue-300" id="stat-total"><?= $stat_hari_ini['total'] ?></div>
        <div class="text-xs text-gray-400 mt-1">Sesi hari ini</div>
    </div>

    <div class="theme-card stat-card border border-yellow-500/20 rounded-xl p-5 hover:scale-[1.02] transition">
        <div class="text-yellow-400 text-sm mb-1 header-subtitle">Menunggu</div>
        <div class="text-3xl font-semibold text-yellow-300" id="stat-menunggu"><?= $stat_hari_ini['menunggu'] ?></div>
        <div class="text-xs text-gray-400 mt-1">Belum diproses</div>
    </div>

    <div class="theme-card stat-card border border-cyan-500/20 rounded-xl p-5 hover:scale-[1.02] transition">
        <div class="text-cyan-400 text-sm mb-1 header-subtitle">On Progress</div>
        <div class="text-3xl font-semibold text-cyan-300" id="stat-progress"><?= $stat_hari_ini['on_progress'] ?></div>
        <div class="text-xs text-gray-400 mt-1">Sedang berjalan</div>
    </div>

    <div class="theme-card stat-card border border-green-500/20 rounded-xl p-5 hover:scale-[1.02] transition">
        <div class="text-green-400 text-sm mb-1 header-subtitle">Selesai</div>
        <div class="text-3xl font-semibold text-green-300" id="stat-selesai"><?= $stat_hari_ini['selesai'] ?></div>
        <div class="text-xs text-gray-400 mt-1">Sudah selesai</div>
    </div>

    <div class="theme-card stat-card border border-purple-500/20 rounded-xl p-5 hover:scale-[1.02] transition">
        <div class="text-purple-400 text-sm mb-1 header-subtitle">Peserta</div>
        <div class="text-3xl font-semibold text-purple-300" id="stat-peserta"><?= $stat_hari_ini['total_peserta'] ?></div>
        <div class="text-xs text-gray-400 mt-1">Total peserta</div>
    </div>

    <div class="theme-card stat-card border border-pink-500/20 rounded-xl p-5 hover:scale-[1.02] transition">
        <div class="text-pink-400 text-sm mb-1 header-subtitle">Breakout</div>
        <div class="text-3xl font-semibold text-pink-300" id="stat-breakout"><?= $stat_hari_ini['total_breakout'] ?></div>
        <div class="text-xs text-gray-400 mt-1">Total breakout room</div>
    </div>

</div>

<!-- ============================================================
     FILTER TANGGAL + LIVE INDICATOR
============================================================ -->
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">

    <div class="flex items-center gap-3">
        <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-emerald-500/10 border border-emerald-500/20">
            <span class="relative flex h-2.5 w-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
            </span>
            <span class="text-emerald-400 text-xs font-semibold">Live</span>
            <span class="text-xs header-subtitle">· Refresh otomatis tiap 30 detik</span>
        </div>

        <form method="post" action="<?= base_url('zoom_management/reassign') ?>">
            <input type="hidden" name="tanggal" value="<?= $tanggal ?>">
            <button type="submit"
                    class="inline-flex items-center gap-1.5 bg-slate-700/80 hover:bg-slate-600
                           text-white text-xs font-medium px-3 py-1.5 rounded-lg transition border border-slate-600/50"
                    title="Reset ulang pengelompokan">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Reset Grup
            </button>
        </form>
    </div>

    <form method="get" action="" class="flex items-center gap-2 flex-wrap">
        <span class="px-3 py-1.5 rounded-lg bg-slate-800/80 border border-slate-700/50 text-xs header-subtitle font-medium">
            📅 <?= date('l, d F Y', strtotime($tanggal)) ?>
        </span>
        <input type="date" name="tanggal" value="<?= htmlspecialchars($tanggal) ?>"
               class="bg-slate-800/80 border border-slate-700/50 rounded-lg px-3 py-2 text-sm header-title
                      focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 focus:outline-none">
        <button type="submit"
                class="inline-flex items-center gap-1.5 bg-cyan-600 hover:bg-cyan-500
                       text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
            </svg>
            Filter
        </button>
        <?php if ($tanggal !== date('Y-m-d')): ?>
        <a href="<?= base_url('zoom_management') ?>"
           class="inline-flex items-center bg-slate-700/80 hover:bg-slate-600 text-white
                  text-sm font-medium px-4 py-2 rounded-lg transition border border-slate-600/50">
            Hari Ini
        </a>
        <?php endif; ?>
    </form>

</div>


<!-- ============================================================
     KARTU STATUS 2 AKUN ZOOM
============================================================ -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

<?php
$akun_defs = [
    1 => ['groups' => $zoom1_groups, 'label' => 'Zoom 1', 'sub' => 'BPS Riau · Akun Utama'],
    2 => ['groups' => $zoom2_groups, 'label' => 'Zoom 2', 'sub' => 'BPS Riau · Akun Cadangan'],
];

foreach ($akun_defs as $num => $akun):
    $total_sesi  = count($akun['groups']);
    $terpakai    = $total_sesi > 0;
    $total_tiket = array_sum(array_map(fn($g) => count($g['tikets']), $akun['groups']));
    $total_br    = 0;
    foreach ($akun['groups'] as $g) {
        foreach ($g['tikets'] as $tz) {
            $dz = parse_zoom_view($tz['jenis_kerusakan']);
            $total_br += (int)($dz['jumlah_breakout'] ?? 0);
        }
    }
    $bdr   = $terpakai ? 'border-yellow-500/20' : 'border-green-500/20';
    $badge = $terpakai ? 'bg-yellow-500/15 text-yellow-400 ring-1 ring-yellow-500/30'
                       : 'bg-green-500/15 text-green-400 ring-1 ring-green-500/30';
    $icon_col = $terpakai ? 'text-yellow-400' : 'text-green-400';
?>
<div class="theme-card border <?= $bdr ?> rounded-xl p-5">

    <!-- Header akun -->
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-slate-800/60 rounded-xl flex items-center justify-center border border-slate-700/50">
                <svg class="w-5 h-5 <?= $icon_col ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <div class="font-semibold header-title text-sm"><?= $akun['label'] ?></div>
                <div class="text-xs header-subtitle"><?= $akun['sub'] ?></div>
            </div>
        </div>
        <span class="inline-flex items-center px-2.5 py-1 text-[10px] font-semibold rounded-lg <?= $badge ?>">
            <?= $terpakai ? '⚡ Digunakan' : '✅ Tersedia' ?>
        </span>
    </div>

    <!-- Mini stat -->
    <div class="grid grid-cols-3 gap-2 mb-4">
        <div class="bg-slate-900/40 rounded-lg p-2 text-center border border-slate-700/30">
            <div class="text-lg font-bold text-blue-400"><?= $total_sesi ?></div>
            <div class="text-[10px] header-subtitle">Sesi</div>
        </div>
        <div class="bg-slate-900/40 rounded-lg p-2 text-center border border-slate-700/30">
            <div class="text-lg font-bold text-purple-400"><?= $total_tiket ?></div>
            <div class="text-[10px] header-subtitle">Tiket</div>
        </div>
        <div class="bg-slate-900/40 rounded-lg p-2 text-center border border-slate-700/30">
            <div class="text-lg font-bold text-pink-400"><?= $total_br ?></div>
            <div class="text-[10px] header-subtitle">Breakout</div>
        </div>
    </div>

    <!-- List sesi -->
    <?php if (!empty($akun['groups'])): ?>
    <div class="space-y-3">
        <?php foreach ($akun['groups'] as $grp):
            $jumlah_br_grp     = 0;
            $nama_kegiatan_grp = '';
            foreach ($grp['tikets'] as $tz) {
                $dz = parse_zoom_view($tz['jenis_kerusakan']);
                $jumlah_br_grp += (int)($dz['jumlah_breakout'] ?? 0);
                if (empty($nama_kegiatan_grp) && !empty($dz['detail_permintaan'])) {
                    $nama_kegiatan_grp = $dz['detail_permintaan'];
                }
            }
        ?>
        <div class="rounded-lg border border-slate-700/30 bg-slate-900/40 overflow-hidden">

            <!-- Header sesi -->
            <div class="flex items-center gap-2 px-3 py-2 border-b border-slate-700/20 bg-slate-800/40">
                <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>

                <?php if (!empty($nama_kegiatan_grp)): ?>
                <span class="text-[10px] font-semibold text-cyan-300 truncate flex-1"
                      title="<?= htmlspecialchars($nama_kegiatan_grp) ?>">
                    <?= htmlspecialchars($nama_kegiatan_grp) ?>
                </span>
                <?php else: ?>
                <span class="text-[10px] font-semibold text-slate-400 flex-1">Sesi Tunggal</span>
                <?php endif; ?>

                <?php if ($jumlah_br_grp > 0): ?>
                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 text-[9px] font-bold flex-shrink-0
                             bg-pink-500/20 text-pink-400 border border-pink-500/30 rounded-md">
                    <?= $jumlah_br_grp ?> BR
                </span>
                <?php endif; ?>

                <div class="flex items-center gap-2 text-[10px] header-subtitle flex-shrink-0">
                    <?php if ($grp['jam']): ?>
                    <span>🕐 <?= $grp['jam'] ?> WIB</span>
                    <?php endif; ?>
                    <?php if ($grp['durasi']): ?>
                    <span>⏱ <?= $grp['durasi'] ?>j/hari</span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Tiket dalam sesi -->
            <div class="divide-y divide-slate-800/40">
            <?php foreach ($grp['tikets'] as $z):
                $dz   = parse_zoom_view($z['jenis_kerusakan']);
                $sc   = match($z['status']) {
                    'On Progress' => 'bg-cyan-500/15 text-cyan-400 ring-1 ring-cyan-500/30',
                    'Selesai'     => 'bg-green-500/15 text-green-400 ring-1 ring-green-500/30',
                    default       => 'bg-yellow-500/15 text-yellow-400 ring-1 ring-yellow-500/30',
                };
                $multiday = is_multiday($z);
            ?>
            <div class="flex items-center gap-2 px-3 py-2.5">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-1.5 flex-wrap mb-0.5">
                        <span class="font-mono text-[10px] text-blue-400"><?= $z['kode_tiket'] ?></span>
                        <span class="inline-flex px-1.5 py-0.5 text-[9px] font-semibold rounded-md <?= $sc ?>"><?= $z['status'] ?></span>
                        <?php if ($multiday): ?>
                        <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 text-[9px] font-bold
                                     bg-violet-500/20 text-violet-400 border border-violet-500/30 rounded-md">
                            📅 <?= label_periode($z) ?>
                        </span>
                        <?php endif; ?>
                    </div>
                    <div class="text-xs header-title font-medium truncate"><?= htmlspecialchars($z['pelapor']) ?></div>
                    <div class="text-[10px] header-subtitle mt-0.5 flex items-center gap-2 flex-wrap">
                        <?php if ($dz['jumlah_peserta']): ?>
                        <span>👥 <?= $dz['jumlah_peserta'] ?> org</span>
                        <?php endif; ?>
                        <?php if ($dz['jumlah_breakout']): ?>
                        <span class="text-pink-400">🔲 <?= $dz['jumlah_breakout'] ?> breakout</span>
                        <?php endif; ?>
                        <?php if ($dz['penanggung_jawab']): ?>
                        <span>👤 <?= htmlspecialchars($dz['penanggung_jawab']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <button onclick="openZoomDetail(<?= $z['id'] ?>)" title="Detail"
                        class="p-1.5 rounded-md bg-slate-600/20 hover:bg-slate-500/40
                               text-slate-400 hover:text-slate-200 transition-all
                               hover:scale-105 active:scale-95 flex-shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>
            <?php endforeach; ?>
            </div>

        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="text-center py-6">
        <div class="w-10 h-10 bg-slate-800/60 rounded-full flex items-center justify-center mx-auto mb-2 border border-slate-700/30">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
        </div>
        <p class="text-xs header-subtitle">Tidak ada sesi terjadwal</p>
    </div>
    <?php endif; ?>

</div>
<?php endforeach; ?>
</div>


<!-- ============================================================
     ALERT KONFLIK JADWAL
============================================================ -->
<?php if (!empty($jadwal_konflik)): ?>
<div class="theme-card border border-orange-500/30 rounded-xl p-4 mb-6 bg-orange-500/5">
    <div class="flex items-start gap-3">
        <svg class="w-5 h-5 text-orange-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <div class="flex-1">
            <div class="text-sm font-semibold text-orange-400 mb-1">
                ⚠️ Jadwal Bentrok — Butuh Akun Zoom Tambahan
            </div>
            <p class="text-xs header-subtitle mb-3">
                Tiket-tiket berikut overlap waktu padahal kedua akun Zoom sudah terpakai.
            </p>
            <?php
            $konflik_shown = [];
            foreach ($jadwal_konflik as $k):
                $key = min($k['tiket1']['id'], $k['tiket2']['id']) . '_' . max($k['tiket1']['id'], $k['tiket2']['id']);
                if (isset($konflik_shown[$key])) continue;
                $konflik_shown[$key] = true;
                $k1_dp = parse_zoom_view($k['tiket1']['jenis_kerusakan'] ?? '');
                $k2_dp = parse_zoom_view($k['tiket2']['jenis_kerusakan'] ?? '');
            ?>
            <div class="flex flex-col gap-1 bg-orange-500/10 border border-orange-500/20
                        rounded-lg px-3 py-2 mb-1 text-xs">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-orange-400 font-bold">Zoom <?= $k['akun'] ?>:</span>
                    <span class="font-mono text-orange-300"><?= $k['tiket1']['kode_tiket'] ?></span>
                    <?php if (!empty($k1_dp['detail_permintaan'])): ?>
                    <span class="text-orange-200/80 italic truncate max-w-[150px]">"<?= htmlspecialchars($k1_dp['detail_permintaan']) ?>"</span>
                    <?php endif; ?>
                    <span class="text-orange-500/70">bentrok dengan</span>
                    <span class="font-mono text-orange-300"><?= $k['tiket2']['kode_tiket'] ?></span>
                    <?php if (!empty($k2_dp['detail_permintaan'])): ?>
                    <span class="text-orange-200/80 italic truncate max-w-[150px]">"<?= htmlspecialchars($k2_dp['detail_permintaan']) ?>"</span>
                    <?php endif; ?>
                </div>
                <div class="text-orange-500/60 text-[10px]">
                    🕐 <?= $k['tiket1']['jam'] ?> vs <?= $k['tiket2']['jam'] ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>


<!-- ============================================================
     TABEL JADWAL
============================================================ -->
<div class="theme-card border rounded-lg overflow-hidden shadow-xl mb-6">

    <div class="px-4 py-3 border-b border-slate-700/50 bg-slate-900/50 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span class="font-semibold header-title text-sm">
                Jadwal Zoom — <?= date('d F Y', strtotime($tanggal)) ?>
            </span>
        </div>
        <div class="flex items-center gap-2">
            <?php if ($stat_hari_ini['total_multiday'] > 0): ?>
            <span class="inline-flex items-center px-2.5 py-1 text-[10px] font-semibold rounded-lg
                         bg-violet-500/15 text-violet-400 ring-1 ring-violet-500/30">
                📅 <?= $stat_hari_ini['total_multiday'] ?> multi-hari
            </span>
            <?php endif; ?>
            <span class="inline-flex items-center px-2.5 py-1 text-[10px] font-semibold rounded-lg
                         bg-blue-500/15 text-blue-400 ring-1 ring-blue-500/30">
                <?= count($zoom_hari_ini) ?> tiket
            </span>
        </div>
    </div>

    <?php if (empty($zoom_hari_ini)): ?>
    <div class="py-12 text-center">
        <div class="w-14 h-14 bg-slate-800/60 rounded-full flex items-center justify-center mx-auto mb-3 border border-slate-700/30">
            <svg class="w-7 h-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
        </div>
        <p class="text-gray-500 text-sm">Tidak ada jadwal Zoom pada tanggal ini</p>
    </div>
    <?php else: ?>
    <table class="w-full text-xs" style="table-layout:fixed">
        <thead class="bg-slate-900/90 text-gray-300 border-b border-slate-700/50">
            <tr>
                <th class="px-2 py-3 text-center font-semibold" style="width:28px">No</th>
                <th class="px-2 py-3 text-center font-semibold" style="width:38px">Akun</th>
                <th class="px-2 py-3 font-semibold" style="width:90px">Kode Tiket</th>
                <th class="px-2 py-3 font-semibold" style="width:160px">Kegiatan / Pelapor</th>
                <th class="px-2 py-3 font-semibold" style="width:110px">Periode</th>
                <th class="px-2 py-3 text-center font-semibold" style="width:55px">Jam</th>
                <th class="px-2 py-3 text-center font-semibold" style="width:45px">Dur/Hari</th>
                <th class="px-2 py-3 text-center font-semibold" style="width:56px">Peserta</th>
                <th class="px-2 py-3 text-center font-semibold" style="width:38px">BR</th>
                <th class="px-2 py-3 font-semibold" style="width:90px">PJ / Host</th>
                <th class="px-2 py-3 font-semibold" style="width:80px">Teknisi</th>
                <th class="px-2 py-3 text-center font-semibold" style="width:75px">Status</th>
                <th class="px-2 py-3 text-center font-semibold" style="width:70px">Zoom</th> 
                <th class="px-2 py-3 text-center font-semibold" style="width:38px">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-800/50">
        <?php
        $semua = array_merge($zoom1_flat, $zoom2_flat);
        usort($semua, fn($a, $b) => strcmp($a['jam'] ?? '', $b['jam'] ?? ''));
        $no = 1;
        foreach ($semua as $t):
            $dp       = parse_zoom_view($t['jenis_kerusakan']);
            $az       = (int)($t['akun_zoom'] ?? 1);
            $multiday = is_multiday($t);

            $sb = match($t['status']) {
                'Menunggu'    => 'bg-yellow-500/15 text-yellow-400 ring-1 ring-yellow-500/30',
                'On Progress' => 'bg-cyan-500/15 text-cyan-400 ring-1 ring-cyan-500/30',
                'Selesai'     => 'bg-green-500/15 text-green-400 ring-1 ring-green-500/30',
                default       => 'bg-slate-500/15 text-slate-400 ring-1 ring-slate-500/30',
            };
        ?>
        <tr class="hover:bg-slate-800/50 transition-all duration-200">

            <td class="px-2 py-3 text-center text-gray-500 font-medium"><?= $no++ ?></td>

            <td class="px-1 py-3 text-center">
                <div class="inline-flex items-center justify-center w-7 h-7
                            <?= $az == 1 ? 'bg-blue-500/20 border-blue-500/30' : 'bg-indigo-500/20 border-indigo-500/30' ?>
                            border rounded-md">
                    <span class="text-[9px] font-bold <?= $az == 1 ? 'text-blue-400' : 'text-indigo-400' ?>">
                        Z<?= $az ?>
                    </span>
                </div>
            </td>

            <td class="px-2 py-3 font-mono text-gray-300 truncate"><?= $t['kode_tiket'] ?></td>

            <!-- Kegiatan / Pelapor -->
            <td class="px-2 py-3">
                <?php if (!empty($dp['detail_permintaan'])): ?>
                <div class="text-gray-100 font-semibold text-[11px] truncate leading-tight"
                     title="<?= htmlspecialchars($dp['detail_permintaan']) ?>">
                    <?= htmlspecialchars($dp['detail_permintaan']) ?>
                </div>
                <div class="text-gray-500 text-[10px] truncate mt-0.5">
                    <?= htmlspecialchars($t['pelapor']) ?>
                </div>
                <?php else: ?>
                <div class="text-gray-300 text-xs truncate"><?= htmlspecialchars($t['pelapor']) ?></div>
                <?php endif; ?>
            </td>

            <!-- Periode (multi-day atau single) -->
            <td class="px-2 py-3">
                <?php if ($multiday): ?>
                    <?php
                    $tM    = new DateTime($t['tanggal']);
                    $tS    = new DateTime($t['tanggal_selesai']);
                    $nhari = $tS->diff($tM)->days + 1;
                    ?>
                    <div class="text-violet-400 text-[10px] font-semibold leading-tight">
                        <?= date('d M', strtotime($t['tanggal'])) ?> – <?= date('d M Y', strtotime($t['tanggal_selesai'])) ?>
                    </div>
                    <div class="text-violet-300/60 text-[9px] mt-0.5"><?= $nhari ?> hari</div>
                <?php else: ?>
                    <div class="text-gray-400 text-[10px]"><?= date('d M Y', strtotime($t['tanggal'])) ?></div>
                <?php endif; ?>
            </td>

            <td class="px-2 py-3 text-center text-gray-300 font-semibold text-[11px]"><?= $t['jam'] ?: '-' ?></td>
            <td class="px-2 py-3 text-center text-gray-400"><?= $t['durasi'] ? $t['durasi'].'j' : '-' ?></td>
            <td class="px-2 py-3 text-center text-gray-300"><?= $dp['jumlah_peserta'] ? $dp['jumlah_peserta'].' org' : '-' ?></td>

            <td class="px-2 py-3 text-center">
                <?php if (!empty($dp['jumlah_breakout']) && (int)$dp['jumlah_breakout'] > 0): ?>
                <span class="inline-flex items-center gap-0.5 text-[10px] font-semibold text-pink-400">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h8M4 18h8"/>
                    </svg>
                    <?= $dp['jumlah_breakout'] ?>
                </span>
                <?php else: ?>
                <span class="text-gray-600 text-[10px]">-</span>
                <?php endif; ?>
            </td>

            <td class="px-2 py-3 text-gray-300 truncate">
                <?= htmlspecialchars($dp['penanggung_jawab'] ?: '-') ?>
                <?php if (!empty($dp['host_cohost'])): ?>
                <div class="text-[10px] text-gray-500 truncate"><?= htmlspecialchars($dp['host_cohost']) ?></div>
                <?php endif; ?>
            </td>

            <td class="px-2 py-3 text-gray-300 truncate">
                <?= htmlspecialchars($t['nama_teknisi'] ?: 'Belum assign') ?>
            </td>

            <td class="px-2 py-3 text-center">
                <span class="inline-flex items-center justify-center px-2 py-0.5 text-[10px] font-semibold rounded-lg <?= $sb ?>">
                    <?= $t['status'] ?>
                </span>
            </td>

            <td class="px-2 py-3 text-center">
    <span class="inline-flex items-center justify-center px-2 py-0.5 text-[10px] font-bold rounded-lg
                 <?= $az == 1 
                     ? 'bg-blue-500/15 text-blue-400 ring-1 ring-blue-500/30' 
                     : 'bg-indigo-500/15 text-indigo-400 ring-1 ring-indigo-500/30' ?>">
        <?= $az == 1 ? 'Zoom 1' : 'Zoom 2' ?>
    </span>
</td>

            <td class="px-1 py-3 text-center">
                <button onclick="openZoomDetail(<?= $t['id'] ?>)" title="Detail"
                        class="p-1 rounded-md bg-slate-600/20 hover:bg-slate-500/40
                               text-slate-400 hover:text-slate-200 transition-all
                               hover:scale-105 active:scale-95">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </td>

        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

</div>


<!-- ============================================================
     TIMELINE VISUAL
============================================================ -->
<?php if (!empty($zoom_hari_ini)): ?>
<div class="theme-card border rounded-lg overflow-hidden shadow-xl mb-6">

    <div class="px-4 py-3 border-b border-slate-700/50 bg-slate-900/50 flex items-center gap-2">
        <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="font-semibold header-title text-sm">Timeline Penggunaan Zoom</span>
        <span class="text-[10px] header-subtitle ml-1">· Jam & durasi adalah nilai per hari</span>
    </div>

    <div class="p-5">
        <?php $jm = 7; $js = 22; $tj = $js - $jm; ?>

        <!-- Ruler jam -->
        <div class="flex mb-2 text-[10px] header-subtitle">
            <?php for ($h = $jm; $h <= $js; $h++): ?>
            <div style="width:<?= 100 / $tj ?>%" class="text-center border-l border-slate-700/30 pl-0.5">
                <?= sprintf('%02d', $h) ?>
            </div>
            <?php endfor; ?>
        </div>

        <?php
        function assign_timeline_lanes($tikets_list, $jm_ref, $tj_ref) {
            $lanes  = [];
            $result = [];
            foreach ($tikets_list as $z) {
                if (empty($z['jam']) || empty($z['durasi'])) {
                    $result[] = array_merge($z, ['_lane' => 0, '_sp' => 0, '_wp' => 0]);
                    continue;
                }
                $jf    = (float)substr($z['jam'], 0, 2) + ((float)substr($z['jam'], 3, 2) / 60);
                $end_f = $jf + (float)$z['durasi'];
                $sp    = max(0, min(100, (($jf - $jm_ref) / $tj_ref) * 100));
                $wp    = max(0.5, min(100 - $sp, ((float)$z['durasi'] / $tj_ref) * 100));

                $assigned_lane = -1;
                foreach ($lanes as $li => $occupied) {
                    $ok = true;
                    foreach ($occupied as $occ) {
                        if ($jf < $occ[1] && $end_f > $occ[0]) { $ok = false; break; }
                    }
                    if ($ok) { $assigned_lane = $li; break; }
                }
                if ($assigned_lane === -1) {
                    $assigned_lane = count($lanes);
                    $lanes[$assigned_lane] = [];
                }
                $lanes[$assigned_lane][] = [$jf, $end_f];
                $result[] = array_merge($z, ['_lane' => $assigned_lane, '_sp' => $sp, '_wp' => $wp]);
            }
            return ['items' => $result, 'num_lanes' => max(1, count($lanes))];
        }

        $row_h   = 38;
        $row_gap = 4;

        $tl1_tikets = [];
        $tl2_tikets = [];
        foreach ($zoom1_groups as $g) foreach ($g['tikets'] as $z) $tl1_tikets[] = $z;
        foreach ($zoom2_groups as $g) foreach ($g['tikets'] as $z) $tl2_tikets[] = $z;

        $tl1 = assign_timeline_lanes($tl1_tikets, $jm, $tj);
        $tl2 = assign_timeline_lanes($tl2_tikets, $jm, $tj);
        ?>

        <!-- Zoom 1 -->
        <div class="mb-5">
            <div class="flex items-center gap-2 mb-2">
                <div class="text-xs text-blue-400 font-bold">Zoom 1</div>
                <?php if (!empty($tl1_tikets)): ?>
                <span class="text-[10px] header-subtitle"><?= count($tl1_tikets) ?> sesi · <?= $tl1['num_lanes'] ?> baris</span>
                <?php endif; ?>
            </div>
            <?php if (empty($tl1_tikets)): ?>
            <div class="h-8 bg-slate-900/50 rounded-lg border border-slate-700/30 flex items-center justify-center">
                <span class="text-[10px] text-gray-600">Tidak ada sesi</span>
            </div>
            <?php else:
                $tl1_h = $tl1['num_lanes'] * $row_h + ($tl1['num_lanes'] - 1) * $row_gap + 4;
            ?>
            <div class="relative bg-slate-900/50 rounded-lg border border-slate-700/30 overflow-hidden"
                 style="height:<?= $tl1_h ?>px">
                <?php for ($h = $jm; $h <= $js; $h++): ?>
                <div class="absolute top-0 bottom-0 border-l border-slate-700/20 pointer-events-none"
                     style="left:<?= (($h - $jm) / $tj) * 100 ?>%"></div>
                <?php endfor; ?>

                <?php foreach ($tl1['items'] as $z):
                    if ($z['_wp'] <= 0) continue;
                    $top_px   = 2 + $z['_lane'] * ($row_h + $row_gap);
                    $dp_z     = parse_zoom_view($z['jenis_kerusakan']);
                    $br_num   = (int)($dp_z['jumlah_breakout'] ?? 0);
                    $label_bar = !empty($dp_z['detail_permintaan']) ? $dp_z['detail_permintaan'] : $z['pelapor'];
                    $is_md    = is_multiday($z);
                    $bc = match($z['status']) {
                        'Selesai'     => '#22c55e',
                        'On Progress' => '#06b6d4',
                        default       => '#3b82f6',
                    };
                    $tooltip = htmlspecialchars($z['kode_tiket'])
                        . ' · ' . htmlspecialchars($label_bar)
                        . ' · ' . htmlspecialchars($z['pelapor'])
                        . ' · ' . $z['jam'] . ' (' . $z['durasi'] . 'j/hari)'
                        . ($is_md ? ' · ' . label_periode($z) : '')
                        . ($br_num ? ' · ' . $br_num . ' BR' : '');
                ?>
                <div class="absolute rounded cursor-pointer transition-all duration-150
                            hover:brightness-110 hover:z-10 hover:shadow-lg"
                     style="left:calc(<?= $z['_sp'] ?>% + 2px);
                            width:calc(<?= $z['_wp'] ?>% - 4px);
                            top:<?= $top_px ?>px;
                            height:<?= $row_h - 2 ?>px;
                            background:<?= $bc ?>;
                            border-left: 3px solid rgba(255,255,255,0.4);
                            <?= $is_md ? 'border-top: 2px dashed rgba(167,139,250,0.7);' : '' ?>"
                     onclick="openZoomDetail(<?= $z['id'] ?>)"
                     title="<?= $tooltip ?>">
                    <div class="flex flex-col justify-center px-2 h-full overflow-hidden">
                        <div class="flex items-center gap-1">
                            <span class="text-white text-[10px] font-semibold truncate leading-tight flex-1">
                                <?= htmlspecialchars($label_bar) ?>
                            </span>
                            <?php if ($is_md): ?>
                            <span class="flex-shrink-0 text-[8px] bg-violet-500/50 text-white rounded px-1 font-bold">MD</span>
                            <?php endif; ?>
                        </div>
                        <div class="flex items-center gap-1 mt-0.5">
                            <?php if ($label_bar !== $z['pelapor']): ?>
                            <span class="text-white/60 text-[9px] truncate leading-tight">
                                <?= htmlspecialchars($z['pelapor']) ?>
                            </span>
                            <?php endif; ?>
                            <?php if ($br_num > 0): ?>
                            <span class="flex-shrink-0 text-[9px] bg-white/25 text-white rounded px-1 font-bold ml-auto">
                                <?= $br_num ?>BR
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Zoom 2 -->
        <div class="mb-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="text-xs text-indigo-400 font-bold">Zoom 2</div>
                <?php if (!empty($tl2_tikets)): ?>
                <span class="text-[10px] header-subtitle"><?= count($tl2_tikets) ?> sesi · <?= $tl2['num_lanes'] ?> baris</span>
                <?php endif; ?>
            </div>
            <?php if (empty($tl2_tikets)): ?>
            <div class="h-8 bg-slate-900/50 rounded-lg border border-slate-700/30 flex items-center justify-center">
                <span class="text-[10px] text-gray-600">Tidak ada sesi</span>
            </div>
            <?php else:
                $tl2_h = $tl2['num_lanes'] * $row_h + ($tl2['num_lanes'] - 1) * $row_gap + 4;
            ?>
            <div class="relative bg-slate-900/50 rounded-lg border border-slate-700/30 overflow-hidden"
                 style="height:<?= $tl2_h ?>px">
                <?php for ($h = $jm; $h <= $js; $h++): ?>
                <div class="absolute top-0 bottom-0 border-l border-slate-700/20 pointer-events-none"
                     style="left:<?= (($h - $jm) / $tj) * 100 ?>%"></div>
                <?php endfor; ?>

                <?php foreach ($tl2['items'] as $z):
                    if ($z['_wp'] <= 0) continue;
                    $top_px   = 2 + $z['_lane'] * ($row_h + $row_gap);
                    $dp_z     = parse_zoom_view($z['jenis_kerusakan']);
                    $br_num   = (int)($dp_z['jumlah_breakout'] ?? 0);
                    $label_bar = !empty($dp_z['detail_permintaan']) ? $dp_z['detail_permintaan'] : $z['pelapor'];
                    $is_md    = is_multiday($z);
                    $bc = match($z['status']) {
                        'Selesai'     => '#22c55e',
                        'On Progress' => '#06b6d4',
                        default       => '#6366f1',
                    };
                    $tooltip = htmlspecialchars($z['kode_tiket'])
                        . ' · ' . htmlspecialchars($label_bar)
                        . ' · ' . htmlspecialchars($z['pelapor'])
                        . ' · ' . $z['jam'] . ' (' . $z['durasi'] . 'j/hari)'
                        . ($is_md ? ' · ' . label_periode($z) : '')
                        . ($br_num ? ' · ' . $br_num . ' BR' : '');
                ?>
                <div class="absolute rounded cursor-pointer transition-all duration-150
                            hover:brightness-110 hover:z-10 hover:shadow-lg"
                     style="left:calc(<?= $z['_sp'] ?>% + 2px);
                            width:calc(<?= $z['_wp'] ?>% - 4px);
                            top:<?= $top_px ?>px;
                            height:<?= $row_h - 2 ?>px;
                            background:<?= $bc ?>;
                            border-left: 3px solid rgba(255,255,255,0.4);
                            <?= $is_md ? 'border-top: 2px dashed rgba(167,139,250,0.7);' : '' ?>"
                     onclick="openZoomDetail(<?= $z['id'] ?>)"
                     title="<?= $tooltip ?>">
                    <div class="flex flex-col justify-center px-2 h-full overflow-hidden">
                        <div class="flex items-center gap-1">
                            <span class="text-white text-[10px] font-semibold truncate leading-tight flex-1">
                                <?= htmlspecialchars($label_bar) ?>
                            </span>
                            <?php if ($is_md): ?>
                            <span class="flex-shrink-0 text-[8px] bg-violet-500/50 text-white rounded px-1 font-bold">MD</span>
                            <?php endif; ?>
                        </div>
                        <div class="flex items-center gap-1 mt-0.5">
                            <?php if ($label_bar !== $z['pelapor']): ?>
                            <span class="text-white/60 text-[9px] truncate leading-tight">
                                <?= htmlspecialchars($z['pelapor']) ?>
                            </span>
                            <?php endif; ?>
                            <?php if ($br_num > 0): ?>
                            <span class="flex-shrink-0 text-[9px] bg-white/25 text-white rounded px-1 font-bold ml-auto">
                                <?= $br_num ?>BR
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Legend -->
        <div class="flex items-center gap-4 text-xs header-subtitle flex-wrap pt-1">
            <div class="flex items-center gap-1.5">
                <span class="w-3 h-3 rounded-sm inline-block" style="background:#3b82f6"></span>Menunggu (Z1)
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-3 h-3 rounded-sm inline-block" style="background:#6366f1"></span>Menunggu (Z2)
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-3 h-3 rounded-sm inline-block" style="background:#06b6d4"></span>On Progress
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-3 h-3 rounded-sm inline-block" style="background:#22c55e"></span>Selesai
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-3 h-3 rounded-sm inline-block border-t-2 border-dashed border-violet-400" style="background:#6366f1"></span>Multi-hari (MD)
            </div>
            <div class="flex items-center gap-1.5 ml-auto">
                <span class="text-[10px] bg-white/10 text-gray-400 rounded px-1 font-bold">3BR</span>= Breakout Room
            </div>
        </div>
    </div>

</div>
<?php endif; ?>


<!-- ============================================================
     MODAL DETAIL
============================================================ -->
<div id="modal-zoom-detail"
     class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50">
    <div class="theme-card border rounded-xl w-full max-w-2xl max-h-[90vh] overflow-hidden">

        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-700/50 bg-slate-900/50">
            <div>
                <div class="font-semibold header-title">Detail Sesi Zoom</div>
                <div class="text-xs header-subtitle font-mono mt-0.5" id="zoom-modal-kode"></div>
            </div>
            <button onclick="closeZoomDetail()"
                    class="p-1.5 rounded-md bg-slate-600/20 hover:bg-slate-500/40
                           text-slate-400 hover:text-slate-200 transition-all hover:scale-105 active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div id="zoom-modal-body" class="p-5 overflow-y-auto" style="max-height:calc(90vh - 70px)">
            <div class="text-center py-8">
                <div class="animate-spin w-7 h-7 border-2 border-cyan-500 border-t-transparent rounded-full mx-auto"></div>
                <p class="text-xs header-subtitle mt-3">Memuat data...</p>
            </div>
        </div>

    </div>
</div>


<!-- ============================================================
     CSS LIGHT MODE
============================================================ -->
<style>
html.light tbody tr:hover{background:rgba(248,250,252,.6)!important}
html.light thead{background:rgba(241,245,249,.9)!important}
html.light thead th{color:var(--text-secondary)!important}
html.light .bg-slate-800\/80{background:rgba(255,255,255,.95)!important}
html.light .border-slate-700\/50{border-color:rgba(226,232,240,.8)!important}
html.light .bg-slate-900\/40{background:rgba(248,250,252,.8)!important}
html.light .bg-slate-900\/50{background:rgba(241,245,249,.9)!important}
html.light .text-gray-300{color:var(--text-primary)!important}
html.light .text-gray-400{color:var(--text-muted)!important}
html.light .text-gray-500{color:var(--text-secondary)!important}
html.light .text-gray-100{color:var(--text-primary)!important}
html.light .divide-slate-800\/50>:not([hidden])~:not([hidden]){border-color:rgba(226,232,240,.5)!important}
html.light input[type="date"]{background:rgba(255,255,255,.95)!important;color:var(--text-primary)!important;border-color:rgba(203,213,225,.8)!important}
</style>


<!-- ============================================================
     JAVASCRIPT
============================================================ -->
<script>
function openZoomDetail(id) {
    const modal = document.getElementById('modal-zoom-detail');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
    document.getElementById('zoom-modal-kode').textContent = '';
    document.getElementById('zoom-modal-body').innerHTML = `
        <div class="text-center py-8">
            <div class="animate-spin w-7 h-7 border-2 border-cyan-500 border-t-transparent rounded-full mx-auto"></div>
            <p class="text-xs text-gray-500 mt-3">Memuat data...</p>
        </div>`;

    fetch('<?= base_url("zoom_management/detail/") ?>' + id)
        .then(r => r.text())
        .then(html => { document.getElementById('zoom-modal-body').innerHTML = html; })
        .catch(() => {
            document.getElementById('zoom-modal-body').innerHTML =
                '<p class="text-red-400 text-sm p-5 text-center">Gagal memuat data.</p>';
        });
}

function closeZoomDetail() {
    const modal = document.getElementById('modal-zoom-detail');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = 'auto';
}

document.getElementById('modal-zoom-detail').addEventListener('click', function(e) {
    if (e.target === this) closeZoomDetail();
});

// Live refresh stat cards tiap 30 detik
setInterval(function() {
    fetch('<?= base_url("zoom_management/get_live_data") ?>?tanggal=<?= $tanggal ?>')
        .then(r => r.json())
        .then(function(data) {
            if (data.status !== 'success') return;
            var s = data.stat;
            document.getElementById('stat-total').textContent    = s.total;
            document.getElementById('stat-menunggu').textContent = s.menunggu;
            document.getElementById('stat-progress').textContent = s.on_progress;
            document.getElementById('stat-selesai').textContent  = s.selesai;
            document.getElementById('stat-peserta').textContent  = s.total_peserta;
            document.getElementById('stat-breakout').textContent = s.total_breakout;
        })
        .catch(function(){});
}, 30000);
</script>