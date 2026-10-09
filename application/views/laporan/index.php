<?php
// application/views/laporan/index.php
?>

<!-- ===== THEME VARIABLES ===== -->
<style>
/* --- Dark mode (default) --- */
:root,
html:not(.light) {
    --lp-bg-card:           #1a2233;
    --lp-bg-input:          #1e2a3a;
    --lp-bg-table:          #1e2a3a;
    --lp-bg-thead:          rgba(15,23,42,0.90);
    --lp-bg-row-hover:      rgba(255,255,255,0.05);
    --lp-bg-select:         rgba(30,42,58,0.80);
    --lp-border:            rgba(255,255,255,0.10);
    --lp-border-5:          rgba(255,255,255,0.05);
    --lp-border-card:       rgba(100,116,139,0.50);
    --lp-text-primary:      #ffffff;
    --lp-text-secondary:    #d1d5db;
    --lp-text-muted:        #94a3b8;
    --lp-text-label:        #94a3b8;
    --lp-placeholder:       #4b5563;
    --lp-divider:           rgba(30,41,59,0.60);

    /* Stat/KPI cards */
    --lp-kpi-bg:            linear-gradient(135deg,#1e2a3a,#0f172a);
    --lp-kpi-border:        rgba(100,116,139,0.50);
    --lp-kpi-num:           #ffffff;
    --lp-kpi-sub:           #94a3b8;
    --lp-kpi-overlay-op:    0.10;

    /* Badge kondisi */
    --lp-badge-baik-bg:     rgba(34,197,94,0.20);
    --lp-badge-baik-text:   #4ade80;
    --lp-badge-rusak-bg:    rgba(239,68,68,0.20);
    --lp-badge-rusak-text:  #f87171;

    /* Saran card border colors */
    --lp-saran-puas:        rgba(16,185,129,0.40);
    --lp-saran-cukup:       rgba(245,158,11,0.40);
    --lp-saran-kurang:      rgba(239,68,68,0.40);

    /* Teknisi bar bg */
    --lp-bar-bg:            #1e293b;

    /* Chart legend color */
    --lp-chart-legend:      #94a3b8;

    /* Input / select */
    --lp-input-bg:          rgba(30,42,58,0.80);
    --lp-input-border:      rgba(100,116,139,0.50);
    --lp-input-text:        #ffffff;
    --lp-input-ring:        #06b6d4;

    /* Pagination btn */
    --lp-pag-bg:            rgba(30,42,58,0.80);
    --lp-pag-hover:         #374151;
    --lp-pag-text:          #94a3b8;

    /* Export buttons */
    --lp-excel-bg:          #059669;
    --lp-excel-hover:       #10b981;
    --lp-pdf-bg:            #dc2626;
    --lp-pdf-hover:         #ef4444;

    /* Saran badge */
    --lp-badge-puas-bg:     rgba(16,185,129,0.20);
    --lp-badge-puas-text:   #6ee7b7;
    --lp-badge-cukup-bg:    rgba(245,158,11,0.20);
    --lp-badge-cukup-text:  #fcd34d;
    --lp-badge-kurang-bg:   rgba(239,68,68,0.20);
    --lp-badge-kurang-text: #fca5a5;

    /* Saran quote border */
    --lp-quote-border:      #475569;

    /* Teknisi rank no bg */
    --lp-rank-default:      linear-gradient(135deg,#475569,#334155);
}

/* --- Light mode --- */
html.light {
    --lp-bg-card:           #ffffff;
    --lp-bg-input:          #f8fafc;
    --lp-bg-table:          #ffffff;
    --lp-bg-thead:          #f1f5f9;
    --lp-bg-row-hover:      rgba(0,0,0,0.025);
    --lp-bg-select:         #ffffff;
    --lp-border:            rgba(0,0,0,0.10);
    --lp-border-5:          rgba(0,0,0,0.05);
    --lp-border-card:       rgba(203,213,225,0.80);
    --lp-text-primary:      #0f172a;
    --lp-text-secondary:    #334155;
    --lp-text-muted:        #64748b;
    --lp-text-label:        #64748b;
    --lp-placeholder:       #94a3b8;
    --lp-divider:           rgba(203,213,225,0.60);

    /* Stat/KPI cards */
    --lp-kpi-bg:            linear-gradient(135deg,#f8fafc,#f1f5f9);
    --lp-kpi-border:        rgba(203,213,225,0.80);
    --lp-kpi-num:           #0f172a;
    --lp-kpi-sub:           #64748b;
    --lp-kpi-overlay-op:    0.06;

    /* Badge kondisi */
    --lp-badge-baik-bg:     rgba(22,163,74,0.12);
    --lp-badge-baik-text:   #16a34a;
    --lp-badge-rusak-bg:    rgba(220,38,38,0.12);
    --lp-badge-rusak-text:  #dc2626;

    /* Saran card border */
    --lp-saran-puas:        rgba(16,185,129,0.35);
    --lp-saran-cukup:       rgba(217,119,6,0.35);
    --lp-saran-kurang:      rgba(220,38,38,0.35);

    /* Teknisi bar bg */
    --lp-bar-bg:            #e2e8f0;

    /* Chart legend */
    --lp-chart-legend:      #64748b;

    /* Input / select */
    --lp-input-bg:          #ffffff;
    --lp-input-border:      rgba(203,213,225,0.80);
    --lp-input-text:        #0f172a;
    --lp-input-ring:        #0891b2;

    /* Pagination btn */
    --lp-pag-bg:            #f1f5f9;
    --lp-pag-hover:         #e2e8f0;
    --lp-pag-text:          #475569;

    /* Export buttons — sama */
    --lp-excel-bg:          #059669;
    --lp-excel-hover:       #047857;
    --lp-pdf-bg:            #dc2626;
    --lp-pdf-hover:         #b91c1c;

    /* Saran badge */
    --lp-badge-puas-bg:     rgba(16,185,129,0.12);
    --lp-badge-puas-text:   #065f46;
    --lp-badge-cukup-bg:    rgba(217,119,6,0.12);
    --lp-badge-cukup-text:  #92400e;
    --lp-badge-kurang-bg:   rgba(220,38,38,0.12);
    --lp-badge-kurang-text: #991b1b;

    /* Saran quote border */
    --lp-quote-border:      #cbd5e1;

    /* Teknisi rank no bg */
    --lp-rank-default:      linear-gradient(135deg,#94a3b8,#64748b);
}

/* ── Apply ke elemen ── */
.lp-page-title   { color: var(--lp-text-primary); }
.lp-page-sub     { color: var(--lp-text-muted); }

/* Card / wrap */
.lp-card         { background-color: var(--lp-bg-card); border-color: var(--lp-border-card); }
.lp-card-inner   { background-color: var(--lp-bg-table); border-color: var(--lp-border-5); }

/* Table */
.lp-thead        { background-color: var(--lp-bg-thead); border-color: var(--lp-border-5); }
.lp-th           { color: var(--lp-text-muted); }
.lp-tr           { border-color: var(--lp-border-5); }
.lp-tr:hover     { background-color: var(--lp-bg-row-hover); }
.lp-td           { color: var(--lp-text-secondary); }
.lp-td-primary   { color: var(--lp-text-primary); }
.lp-td-muted     { color: var(--lp-text-muted); }

/* Form inputs */
.lp-input        { background-color: var(--lp-input-bg); border-color: var(--lp-input-border); color: var(--lp-input-text); }
.lp-input::placeholder { color: var(--lp-placeholder); }
.lp-input option { background-color: var(--lp-input-bg); color: var(--lp-input-text); }
.lp-label        { color: var(--lp-text-label); }
.lp-input:focus  { outline: none; box-shadow: 0 0 0 2px var(--lp-input-ring); }

/* Select dropdown (view switcher) */
.lp-select       { background-color: var(--lp-input-bg); border-color: var(--lp-input-border); color: var(--lp-input-text); }
.lp-select option { background-color: var(--lp-input-bg); color: var(--lp-input-text); }

/* Export buttons */
.lp-btn-excel    { background-color: var(--lp-excel-bg); }
.lp-btn-excel:hover { background-color: var(--lp-excel-hover); }
.lp-btn-pdf      { background-color: var(--lp-pdf-bg); }
.lp-btn-pdf:hover { background-color: var(--lp-pdf-hover); }

/* Badge kondisi */
.lp-badge-baik   { background-color: var(--lp-badge-baik-bg); color: var(--lp-badge-baik-text); }
.lp-badge-rusak  { background-color: var(--lp-badge-rusak-bg); color: var(--lp-badge-rusak-text); }

/* KPI Cards */
.lp-kpi-card     { background: var(--lp-kpi-bg); border-color: var(--lp-kpi-border); }
.lp-kpi-num      { color: var(--lp-kpi-num); }
.lp-kpi-sub      { color: var(--lp-kpi-sub); }

/* Pagination */
.lp-pag-btn      { background-color: var(--lp-pag-bg); color: var(--lp-pag-text); }
.lp-pag-btn:hover:not(:disabled) { background-color: var(--lp-pag-hover); }
.lp-pag-info     { color: var(--lp-text-muted); }

/* Saran */
.lp-saran-puas   { border-color: var(--lp-saran-puas); }
.lp-saran-cukup  { border-color: var(--lp-saran-cukup); }
.lp-saran-kurang { border-color: var(--lp-saran-kurang); }
.lp-badge-saran-puas   { background-color: var(--lp-badge-puas-bg); color: var(--lp-badge-puas-text); }
.lp-badge-saran-cukup  { background-color: var(--lp-badge-cukup-bg); color: var(--lp-badge-cukup-text); }
.lp-badge-saran-kurang { background-color: var(--lp-badge-kurang-bg); color: var(--lp-badge-kurang-text); }
.lp-quote-border { border-color: var(--lp-quote-border); }
.lp-saran-text   { color: var(--lp-text-secondary); }

/* Teknisi */
.lp-bar-bg-track { background-color: var(--lp-bar-bg); }
.lp-teknisi-divider { border-color: var(--lp-divider); }

/* KPI card — light mode override (warna teks di atas gradient) */
html.light .lp-kpi-card .lp-kpi-label-cyan  { color: #0891b2; }
html.light .lp-kpi-card .lp-kpi-label-blue  { color: #2563eb; }
html.light .lp-kpi-card .lp-kpi-label-yellow { color: #d97706; }
html.light .lp-kpi-card .lp-kpi-label-green { color: #059669; }

/* Chart legend warna di light mode */
html.light #chartTeknisi { filter: none; }

/* Animate KPI hover */
.lp-kpi-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
.lp-kpi-card:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(0,0,0,0.15); }
html.light .lp-kpi-card:hover { box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
</style>

<!-- PAGE HEADER -->
<div class="mb-6">
    <h1 class="text-3xl font-bold lp-page-title">Daftar &amp; Laporan</h1>
    <p class="lp-page-sub">Daftar tiket, inventaris, dan laporan sistem monitoring IT</p>
</div>

<!-- TOOLBAR: View Switcher + Export Buttons -->
<form method="get" class="flex flex-wrap items-center gap-3 mb-6">
    <select name="view"
            class="lp-select border px-3 py-2 rounded text-sm
                   focus:outline-none focus:ring-2 transition-all"
            style="--tw-ring-color: var(--lp-input-ring)"
            onchange="this.form.submit()">
        <option value="tiket"          <?= $view=='tiket'          ?'selected':'' ?>>Daftar Tiket</option>
        <option value="perangkat_it"   <?= $view=='perangkat_it'   ?'selected':'' ?>>Daftar Inventaris IT</option>
        <option value="perangkat_umum" <?= $view=='perangkat_umum' ?'selected':'' ?>>Daftar Inventaris Umum</option>
        <option value="teknisi"        <?= $view=='teknisi'        ?'selected':'' ?>>Laporan Performa Teknisi</option>
        <option value="saran"          <?= $view=='saran'          ?'selected':'' ?>>Daftar Saran &amp; Masukan</option>
    </select>

    <a id="btnExcel" href="#"
       class="lp-btn-excel inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium
              text-white transition-all transform hover:scale-105 active:scale-95 shadow-lg">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Ekspor Excel
    </a>

    <a id="btnPdf" href="#"
       class="lp-btn-pdf inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium
              text-white transition-all transform hover:scale-105 active:scale-95 shadow-lg">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
        </svg>
        Ekspor PDF
    </a>
</form>


<!-- ═══════════════════════════════════ REKAP TIKET ═══════════════════════════════════ -->
<?php if($view=='tiket'): ?>

<div class="lp-card rounded-lg p-4 mb-6 flex flex-wrap gap-4 items-center border">
    <div>
        <label class="block text-xs lp-label mb-1">Tanggal Mulai</label>
        <input type="text" id="start_date"
               class="lp-input border px-3 py-2 rounded text-sm
                      focus:ring-2 focus:outline-none transition-all">
    </div>
    <div>
        <label class="block text-xs lp-label mb-1">Tanggal Akhir</label>
        <input type="text" id="end_date"
               class="lp-input border px-3 py-2 rounded text-sm
                      focus:ring-2 focus:outline-none transition-all">
    </div>
</div>

<div class="lp-card rounded-lg overflow-hidden border">
    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="tabel-tiket">
            <thead class="lp-thead border-b">
                <tr class="lp-th text-xs uppercase tracking-wider">
                    <th class="px-4 py-3 text-left font-semibold">Kode Tiket</th>
                    <th class="px-4 py-3 text-left font-semibold">Kategori</th>
                    <th class="px-4 py-3 text-left font-semibold">Sub Kategori</th>
                    <th class="px-4 py-3 text-left font-semibold">Pelapor</th>
                    <th class="px-4 py-3 text-left font-semibold">Lokasi</th>
                    <th class="px-4 py-3 text-left font-semibold">Dibuat</th>
                    <th class="px-4 py-3 text-left font-semibold">Selesai</th>
                    <th class="px-4 py-3 text-left font-semibold">Penilaian</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="8" class="px-4 py-6 text-center lp-page-sub animate-pulse">
                        Memuat data...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
const startEl = document.getElementById('start_date')
const endEl   = document.getElementById('end_date')
const tbody   = document.querySelector('#tabel-tiket tbody')

function loadTiket() {
    if (!startEl.value || !endEl.value) return
    if (startEl.value > endEl.value) {
        tbody.innerHTML = '<tr><td colspan="8" class="px-4 py-6 text-center text-red-400">Tanggal akhir harus lebih besar dari tanggal mulai</td></tr>'
        return
    }
    tbody.innerHTML = '<tr><td colspan="8" class="px-4 py-6 text-center lp-page-sub animate-pulse">Memuat data...</td></tr>'

    fetch("<?= site_url('laporan/ajax_tiket_detail') ?>", {
        method : 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body   : 'start=' + startEl.value + '&end=' + endEl.value
    })
    .then(r => r.json())
    .then(data => {
        if (!data.length) {
            tbody.innerHTML = '<tr><td colspan="8" class="px-4 py-6 text-center lp-page-sub">Tidak ada tiket pada rentang waktu ini</td></tr>'
            return
        }
        tbody.innerHTML = ''
        data.forEach(t => {
            tbody.innerHTML += `
<tr class="lp-tr border-t hover:bg-[var(--lp-bg-row-hover)] transition-colors">
    <td class="px-4 py-2 font-mono text-blue-400">${t.kode_tiket}</td>
    <td class="px-4 py-2 lp-td-primary">${t.kategori ?? '-'}</td>
    <td class="px-4 py-2 lp-td-primary">${t.sub_kategori ?? '-'}</td>
    <td class="px-4 py-2 lp-td-primary">${t.pelapor ?? '-'}</td>
    <td class="px-4 py-2 lp-td-primary">${t.lokasi ?? '-'}</td>
    <td class="px-4 py-2 lp-td">${t.created_at}</td>
    <td class="px-4 py-2 text-emerald-500">${t.selesai_at}</td>
    <td class="px-4 py-2 lp-td-primary">${t.rating ?? '-'}</td>
</tr>`
        })
    })
}

startEl.addEventListener('change', loadTiket)
endEl.addEventListener('change', loadTiket)
loadTiket()
</script>

<script>
flatpickr("#start_date", {
    dateFormat: "Y-m-d", defaultDate: "<?= date('Y-m-01') ?>",
    allowInput: false, locale: { firstDayOfWeek: 1 }
})
flatpickr("#end_date", {
    dateFormat: "Y-m-d", defaultDate: "<?= date('Y-m-d') ?>",
    allowInput: false, locale: { firstDayOfWeek: 1 }
})
</script>

<?php endif; ?>


<!-- ══════════════════════════════════ PERANGKAT IT ══════════════════════════════════ -->
<?php if($view=='perangkat_it'): ?>

<div class="lp-card rounded-lg overflow-hidden border">
    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="table-perangkat">
            <thead class="lp-thead border-b">
                <tr class="lp-th text-xs uppercase tracking-wider">
                    <th class="px-4 py-3 text-left font-semibold">Kode Barang</th>
                    <th class="px-4 py-3 text-left font-semibold">NUP</th>
                    <th class="px-4 py-3 text-left font-semibold">Nama Barang</th>
                    <th class="px-4 py-3 text-left font-semibold">Merek</th>
                    <th class="px-4 py-3 text-left font-semibold">Kondisi</th>
                    <th class="px-4 py-3 text-left font-semibold">Lokasi</th>
                    <th class="px-4 py-3 text-left font-semibold">Terakhir Diperbarui</th>
                    <th class="px-4 py-3 text-left font-semibold">Pengguna</th>
                    <th class="px-4 py-3 text-left font-semibold">Tgl. Perolehan</th>
                </tr>
            </thead>
            <tbody class="divide-y" style="border-color: var(--lp-border-5)">
                <?php if(!empty($it)): ?>
                    <?php foreach($it as $row): ?>
                    <tr class="lp-tr hover:bg-[var(--lp-bg-row-hover)] transition-colors">
                        <td class="px-4 py-2 lp-td"><?= htmlspecialchars($row['kode_barang']) ?></td>
                        <td class="px-4 py-2 lp-td"><?= htmlspecialchars($row['nup']) ?></td>
                        <td class="px-4 py-2 font-medium lp-td-primary"><?= htmlspecialchars($row['nama_barang']) ?></td>
                        <td class="px-4 py-2 lp-td"><?= htmlspecialchars($row['merk']) ?></td>
                        <td class="px-4 py-2">
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs whitespace-nowrap font-semibold
                                <?= str_contains(strtolower($row['kondisi']), 'baik')
                                    ? 'lp-badge-baik'
                                    : 'lp-badge-rusak' ?>">
                                <?= htmlspecialchars($row['kondisi']) ?>
                            </span>
                        </td>
                        <td class="px-4 py-2 lp-td"><?= htmlspecialchars($row['lokasi']) ?></td>
                        <td class="px-4 py-2 lp-td-muted text-xs whitespace-nowrap">
                            <?php if (empty($row['updated_at']) || $row['updated_at'] === '0000-00-00 00:00:00'): ?>
                                <span class="italic" style="color:var(--lp-placeholder)">Belum diperbarui</span>
                            <?php else: ?>
                                <?= date('d M Y H:i', strtotime($row['updated_at'])) ?>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-2 lp-td"><?= htmlspecialchars($row['pengguna'] ?? '-') ?></td>
                        <td class="px-4 py-2 lp-td">
                            <?= date('d M Y', strtotime($row['tanggal_perolehan'])) ?>
                        </td>
                    </tr>
                    <?php endforeach ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="px-4 py-6 text-center lp-page-sub">
                            Tidak ada data perangkat
                        </td>
                    </tr>
                <?php endif ?>
            </tbody>
        </table>
    </div>

    <div class="flex justify-between items-center p-4 text-sm border-t lp-teknisi-divider">
        <span id="pageInfo" class="lp-pag-info text-xs"></span>
        <div class="flex gap-2">
            <button id="prevBtn"
                    class="lp-pag-btn px-3 py-1 rounded transition-all disabled:opacity-40">
                Sebelumnya
            </button>
            <button id="nextBtn"
                    class="lp-pag-btn px-3 py-1 rounded transition-all disabled:opacity-40">
                Berikutnya
            </button>
        </div>
    </div>
</div>

<?php endif; ?>


<!-- ═════════════════════════════════ PERANGKAT UMUM ═════════════════════════════════ -->
<?php if($view=='perangkat_umum'): ?>

<div class="lp-card rounded-lg overflow-hidden border">
    <div class="overflow-x-auto">
        <table class="w-full text-sm table-fixed">
            <thead class="lp-thead border-b">
                <tr class="lp-th text-xs uppercase tracking-wider">
                    <th class="px-4 py-3 w-[13%] text-left font-semibold">Kode Barang</th>
                    <th class="px-4 py-3 w-[8%]  text-left font-semibold">NUP</th>
                    <th class="px-4 py-3 w-[16%] text-left font-semibold">Nama Barang</th>
                    <th class="px-4 py-3 w-[10%] text-left font-semibold">Merek</th>
                    <th class="px-4 py-3 w-[10%] text-center font-semibold">Kondisi</th>
                    <th class="px-4 py-3 w-[23%] text-left font-semibold">Lokasi</th>
                    <th class="px-4 py-3 w-[10%] text-left font-semibold">Pengguna</th>
                    <th class="px-4 py-3 w-[10%] text-left font-semibold">Tgl. Perolehan</th>
                </tr>
            </thead>
            <tbody class="divide-y" style="border-color: var(--lp-border-5)">
                <?php if(!empty($umum)): ?>
                    <?php foreach($umum as $row): ?>
                    <tr class="lp-tr hover:bg-[var(--lp-bg-row-hover)] transition-colors">
                        <td class="px-4 py-2 font-mono text-blue-400 truncate"><?= htmlspecialchars($row['kode_barang'] ?? '-') ?></td>
                        <td class="px-4 py-2 lp-td"><?= htmlspecialchars($row['nup'] ?? '-') ?></td>
                        <td class="px-4 py-2 font-medium lp-td-primary truncate"><?= htmlspecialchars($row['nama_barang'] ?? '-') ?></td>
                        <td class="px-4 py-2 lp-td truncate"><?= htmlspecialchars($row['merk'] ?? '-') ?></td>
                        <td class="px-4 py-2">
                            <div class="flex justify-center">
                                <span class="inline-flex px-2 py-1 rounded text-xs font-semibold whitespace-nowrap
                                    <?= strtolower($row['kondisi'])=='baik'
                                        ? 'lp-badge-baik'
                                        : 'lp-badge-rusak' ?>">
                                    <?= htmlspecialchars($row['kondisi'] ?? '-') ?>
                                </span>
                            </div>
                        </td>
                        <td class="px-4 py-2 break-words lp-td">
                            <?= htmlspecialchars($row['lokasi'] ?? '-') ?>
                            <?= !empty($row['ruang']) ? ' — ' . htmlspecialchars($row['ruang']) : '' ?>
                        </td>
                        <td class="px-4 py-2 lp-td truncate"><?= htmlspecialchars($row['pengguna'] ?? '-') ?></td>
                        <td class="px-4 py-2 whitespace-nowrap lp-td-muted text-xs">
                            <?= !empty($row['tanggal_perolehan'])
                                ? date('d M Y', strtotime($row['tanggal_perolehan']))
                                : '-' ?>
                        </td>
                    </tr>
                    <?php endforeach ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center lp-page-sub">
                            Tidak ada data perangkat umum
                        </td>
                    </tr>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</div>

<?php endif; ?>


<!-- ══════════════════════════════════ PERFORMA TEKNISI ══════════════════════════════════ -->
<?php if($view=='teknisi'): ?>

<?php
$totalTiket   = array_sum(array_column($teknisi, 'total'));
$rated        = array_filter($teknisi, fn($t) => floatval($t['rating']) > 0);
$avgRating    = !empty($rated)
    ? round(array_sum(array_column($rated, 'rating')) / count($rated), 1)
    : 0;
$topPerformer = !empty($teknisi) ? $teknisi[0] : null;
$maxTotal     = !empty($teknisi) ? max(array_column($teknisi, 'total')) : 1;
?>

<!-- Header + Filter -->
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold lp-page-title flex items-center gap-2">
            🏆 Performa Teknisi
        </h2>
        <p class="lp-page-sub text-sm mt-1">
            Rekap kinerja tim teknisi tahun <strong class="text-cyan-500"><?= $tahun ?></strong>
        </p>
    </div>
    <div class="flex flex-wrap items-center gap-2">
        <div class="flex items-center gap-2">
            <label class="text-sm lp-label">Tahun:</label>
            <select id="filter_tahun_teknisi"
                    class="lp-select border px-3 py-2 rounded-lg text-sm focus:outline-none focus:ring-2 transition-all">
                <?php for($y = date('Y'); $y >= date('Y')-5; $y--): ?>
                    <option value="<?= $y ?>" <?= $tahun==$y?'selected':'' ?>><?= $y ?></option>
                <?php endfor ?>
            </select>
        </div>
        <div class="flex items-center gap-2">
            <label class="text-sm lp-label">Bulan:</label>
            <select id="filter_bulan_teknisi"
                    class="lp-select border px-3 py-2 rounded-lg text-sm focus:outline-none focus:ring-2 transition-all">
                <option value="">Semua Bulan</option>
                <?php
                $namaBulan = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                for($m=1;$m<=12;$m++): ?>
                    <option value="<?= $m ?>"><?= $namaBulan[$m] ?></option>
                <?php endfor ?>
            </select>
        </div>
        <div class="flex items-center gap-2">
            <label class="text-sm lp-label">Teknisi:</label>
            <select id="filter_nama_teknisi"
                    class="lp-select border px-3 py-2 rounded-lg text-sm focus:outline-none focus:ring-2 transition-all">
                <option value="">Semua Teknisi</option>
                <?php foreach($teknisi as $t): ?>
                    <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) ?></option>
                <?php endforeach ?>
            </select>
        </div>
    </div>
</div>

<!-- KPI Cards -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="lp-kpi-card relative overflow-hidden rounded-xl p-4 border">
        <div class="absolute inset-0 pointer-events-none"
             style="background:linear-gradient(135deg,#06b6d4,#0891b2);opacity:var(--lp-kpi-overlay-op)"></div>
        <p class="text-xs font-semibold uppercase tracking-widest text-cyan-500 mb-1 lp-kpi-label-cyan">Teknisi Aktif</p>
        <p class="text-4xl font-black lp-kpi-num" data-kpi="aktif"><?= count($teknisi) ?></p>
        <p class="text-xs lp-kpi-sub mt-1">orang</p>
        <div class="absolute top-3 right-3 text-4xl" style="opacity:0.15">👤</div>
    </div>
    <div class="lp-kpi-card relative overflow-hidden rounded-xl p-4 border">
        <div class="absolute inset-0 pointer-events-none"
             style="background:linear-gradient(135deg,#3b82f6,#2563eb);opacity:var(--lp-kpi-overlay-op)"></div>
        <p class="text-xs font-semibold uppercase tracking-widest text-blue-500 mb-1 lp-kpi-label-blue">Total Tiket Selesai</p>
        <p class="text-4xl font-black lp-kpi-num" data-kpi="total"><?= number_format($totalTiket) ?></p>
        <p class="text-xs lp-kpi-sub mt-1">tiket</p>
        <div class="absolute top-3 right-3 text-4xl" style="opacity:0.15">🎫</div>
    </div>
    <div class="lp-kpi-card relative overflow-hidden rounded-xl p-4 border">
        <div class="absolute inset-0 pointer-events-none"
             style="background:linear-gradient(135deg,#f59e0b,#d97706);opacity:var(--lp-kpi-overlay-op)"></div>
        <p class="text-xs font-semibold uppercase tracking-widest text-amber-500 mb-1 lp-kpi-label-yellow">Rata-rata Penilaian</p>
        <p class="text-4xl font-black lp-kpi-num" data-kpi="avg"><?= number_format($avgRating, 1) ?></p>
        <p class="text-xs lp-kpi-sub mt-1">dari 5.0</p>
        <div class="absolute top-3 right-3 text-4xl" style="opacity:0.15">⭐</div>
    </div>
    <div class="lp-kpi-card relative overflow-hidden rounded-xl p-4 border">
        <div class="absolute inset-0 pointer-events-none"
             style="background:linear-gradient(135deg,#10b981,#059669);opacity:var(--lp-kpi-overlay-op)"></div>
        <p class="text-xs font-semibold uppercase tracking-widest text-emerald-500 mb-1 lp-kpi-label-green">Teknisi Terbaik</p>
        <p class="text-lg font-black lp-kpi-num leading-tight truncate" data-kpi="top-name">
            <?= $topPerformer ? htmlspecialchars($topPerformer['name']) : '-' ?>
        </p>
        <p class="text-xs text-emerald-500 mt-1" data-kpi="top-info">
            <?= $topPerformer ? '⭐ '.$topPerformer['rating'].' · '.$topPerformer['total'].' tiket' : '-' ?>
        </p>
        <div class="absolute top-3 right-3 text-4xl" style="opacity:0.15">🏅</div>
    </div>
</div>

<!-- Grafik + Bar Chart -->
<div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-6">
    <!-- Donut -->
    <div class="lg:col-span-2 lp-card rounded-xl border p-5 flex flex-col">
        <h3 class="text-sm font-semibold lp-page-sub uppercase tracking-widest mb-4">
            📊 Distribusi Tiket
        </h3>
        <div class="flex-1 flex items-center justify-center">
            <canvas id="chartTeknisi" style="max-height:260px;"></canvas>
        </div>
    </div>

    <!-- Bar Chart -->
    <div class="lg:col-span-3 lp-card rounded-xl border p-5">
        <h3 class="text-sm font-semibold lp-page-sub uppercase tracking-widest mb-4">
            📈 Penilaian &amp; Volume Tiket
        </h3>
        <div class="space-y-4 overflow-y-auto" style="max-height:280px;" id="bar-teknisi">
            <?php foreach($teknisi as $idx => $t):
                $pct     = $maxTotal > 0 ? round(($t['total'] / $maxTotal) * 100) : 0;
                $rate    = floatval($t['rating']);
                $ratePct = ($rate / 5) * 100;
                $colors  = [
                    0 => ['bar'=>'from-amber-400 to-yellow-300', 'text'=>'text-amber-400'],
                    1 => ['bar'=>'from-slate-400 to-gray-300',   'text'=>'text-slate-400'],
                    2 => ['bar'=>'from-orange-600 to-amber-500', 'text'=>'text-orange-400'],
                ];
                $c = $colors[$idx] ?? ['bar'=>'from-cyan-500 to-blue-400', 'text'=>'text-cyan-400'];
            ?>
            <div class="group">
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center gap-2">
                        <span class="text-base"><?= $idx==0?'🥇':($idx==1?'🥈':($idx==2?'🥉':'#'.($idx+1))) ?></span>
                        <span class="text-sm font-semibold lp-td-primary"><?= htmlspecialchars($t['name']) ?></span>
                    </div>
                    <div class="flex items-center gap-3 text-xs">
                        <span class="lp-page-sub"><?= $t['total'] ?> tiket</span>
                        <span class="<?= $c['text'] ?> font-bold">★ <?= number_format($rate, 1) ?></span>
                    </div>
                </div>
                <div class="h-2 w-full lp-bar-bg-track rounded-full overflow-hidden mb-1">
                    <div class="h-full bg-gradient-to-r <?= $c['bar'] ?> rounded-full"
                         style="width:<?= $pct ?>%;transition:width 0.8s ease <?= $idx*0.1 ?>s"></div>
                </div>
                <div class="h-1.5 w-full lp-bar-bg-track rounded-full overflow-hidden opacity-60">
                    <div class="h-full bg-gradient-to-r from-yellow-400 to-amber-300 rounded-full"
                         style="width:<?= $ratePct ?>%;transition:width 0.8s ease <?= $idx*0.1+0.3 ?>s"></div>
                </div>
            </div>
            <?php endforeach ?>

            <?php if(empty($teknisi)): ?>
            <p class="text-center lp-page-sub text-sm py-8">Belum ada data</p>
            <?php endif ?>
        </div>
        <div class="flex gap-4 mt-4 pt-3 border-t lp-teknisi-divider text-xs lp-page-sub">
            <span class="flex items-center gap-1.5">
                <span class="w-3 h-2 rounded bg-gradient-to-r from-cyan-500 to-blue-400 inline-block"></span>
                Volume Tiket
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-3 h-1.5 rounded bg-gradient-to-r from-yellow-400 to-amber-300 inline-block opacity-60"></span>
                Penilaian
            </span>
        </div>
    </div>
</div>

<!-- Tabel Peringkat -->
<div class="lp-card rounded-xl border overflow-hidden">
    <div class="px-5 py-4 border-b lp-teknisi-divider flex items-center justify-between">
        <h3 class="text-sm font-semibold lp-page-sub uppercase tracking-widest">🏅 Papan Peringkat</h3>
        <span class="text-xs lp-page-sub"><?= count($teknisi) ?> teknisi terdaftar</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="lp-thead">
                <tr class="lp-th text-xs uppercase tracking-wider">
                    <th class="px-5 py-3 text-center w-12 font-semibold">Peringkat</th>
                    <th class="px-5 py-3 text-left font-semibold">Teknisi</th>
                    <th class="px-5 py-3 text-center font-semibold">Total Tiket</th>
                    <th class="px-5 py-3 text-center font-semibold">Nilai</th>
                    <th class="px-5 py-3 text-center font-semibold">Bintang</th>
                </tr>
            </thead>
            <tbody class="divide-y lp-tr" id="tbody-teknisi"
                   style="border-color:var(--lp-border-5)">
                <?php if(!empty($teknisi)): ?>
                    <?php foreach($teknisi as $idx => $t):
                        $rate    = floatval($t['rating']);
                        $medals  = ['🥇','🥈','🥉'];
                        $medal   = $medals[$idx] ?? '#'.($idx+1);
                        $fullStars = floor($rate);
                        $half      = ($rate - $fullStars) >= 0.5;
                        $valCls    = $rate>=4.5 ? 'text-amber-400'
                                   : ($rate>=4   ? 'text-blue-400'
                                   : ($rate>=3.5 ? 'text-emerald-400' : 'text-slate-400'));
                        $rowBg     = $idx==0 ? 'background:rgba(245,158,11,0.05)' : '';
                        $avatarCls = $idx==0 ? 'from-amber-400 to-yellow-500'
                                   : ($idx==1 ? 'from-slate-400 to-gray-500'
                                   : ($idx==2 ? 'from-orange-500 to-amber-600'
                                   : 'from-slate-500 to-slate-600'));
                    ?>
                    <tr class="hover:bg-[var(--lp-bg-row-hover)] transition-colors duration-150"
                        style="<?= $rowBg ?>">
                        <td class="px-5 py-3.5 text-center">
                            <span class="text-xl"><?= $medal ?></span>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center
                                            text-white text-xs font-black shrink-0
                                            bg-gradient-to-br <?= $avatarCls ?>">
                                    <?= strtoupper(substr($t['name'], 0, 2)) ?>
                                </div>
                                <span class="font-semibold lp-td-primary"><?= htmlspecialchars($t['name']) ?></span>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="font-bold lp-kpi-num text-base"><?= number_format($t['total']) ?></span>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="font-black text-lg <?= $valCls ?>"><?= number_format($rate, 1) ?></span>
                            <span class="text-xs lp-page-sub">/5</span>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <div class="flex justify-center gap-0.5">
                                <?php for($i=1;$i<=5;$i++): ?>
                                    <svg class="w-4 h-4 <?= $i<=$fullStars ? 'text-yellow-400' : ($i==$fullStars+1&&$half ? 'text-yellow-400 opacity-50' : 'text-slate-300') ?>"
                                         fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                <?php endfor ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-5 py-16 text-center lp-page-sub">
                            <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <p class="font-medium">Belum ada data teknisi untuk tahun <?= $tahun ?></p>
                            <p class="text-xs opacity-60 mt-1">Coba pilih tahun yang berbeda</p>
                        </td>
                    </tr>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
let chartTeknisiInstance = null

function loadTeknisi() {
    const tahun  = document.getElementById('filter_tahun_teknisi').value
    const bulan  = document.getElementById('filter_bulan_teknisi').value
    const userId = document.getElementById('filter_nama_teknisi').value

    fetch("<?= site_url('laporan/ajax_teknisi') ?>", {
        method : 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body   : 'tahun='+tahun+'&bulan='+bulan+'&user_id='+userId
    })
    .then(r => r.json())
    .then(data => { renderTeknisi(data) })
}

function renderTeknisi(teknisi) {
    const maxTotal = teknisi.length ? Math.max(...teknisi.map(t=>parseInt(t.total)||0)) : 1
    const isLight  = document.documentElement.classList.contains('light')

    // KPI
    const totalTiket = teknisi.reduce((s,t)=>s+(parseInt(t.total)||0),0)
    const rated      = teknisi.filter(t=>parseFloat(t.rating)>0)
    const avgRating  = rated.length ? (rated.reduce((s,t)=>s+(parseFloat(t.rating)||0),0)/rated.length).toFixed(1) : 0
    const top        = teknisi.length ? teknisi[0] : null

    document.querySelector('[data-kpi="aktif"]').textContent     = teknisi.length
    document.querySelector('[data-kpi="total"]').textContent     = totalTiket.toLocaleString()
    document.querySelector('[data-kpi="avg"]').textContent       = avgRating
    document.querySelector('[data-kpi="top-name"]').textContent  = top ? top.name : '-'
    document.querySelector('[data-kpi="top-info"]').textContent  = top ? '⭐ '+parseFloat(top.rating).toFixed(1)+' · '+top.total+' tiket' : '-'

    // Donut chart
    const names   = teknisi.map(t=>t.name)
    const totals  = teknisi.map(t=>parseInt(t.total)||0)
    const palette = ['#f59e0b','#64748b','#f97316','#22d3ee','#a78bfa','#34d399','#fb7185','#60a5fa']

    if (chartTeknisiInstance) {
        chartTeknisiInstance.data.labels                        = names
        chartTeknisiInstance.data.datasets[0].data             = totals
        chartTeknisiInstance.data.datasets[0].backgroundColor  = palette.slice(0, names.length)
        chartTeknisiInstance.options.plugins.legend.labels.color = isLight ? '#475569' : '#94a3b8'
        chartTeknisiInstance.update()
    }

    // Bar
    const barContainer = document.getElementById('bar-teknisi')
    const colors = [
        {bar:'from-amber-400 to-yellow-300', text:'text-amber-400'},
        {bar:'from-slate-400 to-gray-300',   text:'text-slate-400'},
        {bar:'from-orange-600 to-amber-500', text:'text-orange-400'},
    ]
    if (!teknisi.length) {
        barContainer.innerHTML = '<p class="text-center lp-page-sub text-sm py-8">Belum ada data</p>'
    } else {
        barContainer.innerHTML = teknisi.map((t,idx) => {
            const pct     = maxTotal > 0 ? Math.round((parseInt(t.total)||0)/maxTotal*100) : 0
            const rate    = parseFloat(t.rating)||0
            const ratePct = (rate/5)*100
            const c       = colors[idx] ?? {bar:'from-cyan-500 to-blue-400', text:'text-cyan-400'}
            const medal   = idx==0?'🥇':idx==1?'🥈':idx==2?'🥉':'#'+(idx+1)
            return `
<div class="group">
    <div class="flex items-center justify-between mb-1">
        <div class="flex items-center gap-2">
            <span class="text-base">${medal}</span>
            <span class="text-sm font-semibold lp-td-primary">${t.name}</span>
        </div>
        <div class="flex items-center gap-3 text-xs">
            <span class="lp-page-sub">${t.total} tiket</span>
            <span class="${c.text} font-bold">★ ${rate.toFixed(1)}</span>
        </div>
    </div>
    <div class="h-2 w-full lp-bar-bg-track rounded-full overflow-hidden mb-1">
        <div class="h-full bg-gradient-to-r ${c.bar} rounded-full" style="width:${pct}%"></div>
    </div>
    <div class="h-1.5 w-full lp-bar-bg-track rounded-full overflow-hidden opacity-60">
        <div class="h-full bg-gradient-to-r from-yellow-400 to-amber-300 rounded-full" style="width:${ratePct}%"></div>
    </div>
</div>`
        }).join('')
    }

    // Tabel peringkat
    const tbody = document.getElementById('tbody-teknisi')
    if (!teknisi.length) {
        tbody.innerHTML = `<tr><td colspan="5" class="px-5 py-16 text-center lp-page-sub">Belum ada data untuk filter ini</td></tr>`
        return
    }
    const medals = ['🥇','🥈','🥉']
    tbody.innerHTML = teknisi.map((t,idx) => {
        const rate      = parseFloat(t.rating)||0
        const medal     = medals[idx] ?? '#'+(idx+1)
        const fullStars = Math.floor(rate)
        const half      = (rate - fullStars) >= 0.5
        const avatarCls = idx==0?'from-amber-400 to-yellow-500':idx==1?'from-slate-400 to-gray-500':idx==2?'from-orange-500 to-amber-600':'from-slate-500 to-slate-600'
        const valCls    = rate>=4.5?'text-amber-400':rate>=4?'text-blue-400':rate>=3.5?'text-emerald-400':'text-slate-400'
        const rowStyle  = idx==0?'background:rgba(245,158,11,0.05)':''
        const stars = Array.from({length:5},(_,i)=>{
            const cls = i<fullStars ? 'text-yellow-400' : (i==fullStars&&half ? 'text-yellow-400 opacity-50' : 'text-slate-300')
            return `<svg class="w-4 h-4 ${cls}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>`
        }).join('')
        return `
<tr class="hover:bg-[var(--lp-bg-row-hover)] transition-colors duration-150" style="${rowStyle}">
    <td class="px-5 py-3.5 text-center"><span class="text-xl">${medal}</span></td>
    <td class="px-5 py-3.5">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-xs font-black shrink-0 bg-gradient-to-br ${avatarCls}">
                ${t.name.substring(0,2).toUpperCase()}
            </div>
            <span class="font-semibold lp-td-primary">${t.name}</span>
        </div>
    </td>
    <td class="px-5 py-3.5 text-center"><span class="font-bold lp-kpi-num text-base">${parseInt(t.total).toLocaleString()}</span></td>
    <td class="px-5 py-3.5 text-center">
        <span class="font-black text-lg ${valCls}">${rate.toFixed(1)}</span>
        <span class="text-xs lp-page-sub">/5</span>
    </td>
    <td class="px-5 py-3.5 text-center"><div class="flex justify-center gap-0.5">${stars}</div></td>
</tr>`
    }).join('')
}

document.getElementById('filter_tahun_teknisi').addEventListener('change', loadTeknisi)
document.getElementById('filter_bulan_teknisi').addEventListener('change', loadTeknisi)
document.getElementById('filter_nama_teknisi').addEventListener('change', loadTeknisi)
</script>

<script>
(function(){
    const ctx = document.getElementById('chartTeknisi')
    if (!ctx) return
    const isLight  = document.documentElement.classList.contains('light')
    const names    = <?= json_encode(array_column($teknisi, 'name')) ?>
    const totals   = <?= json_encode(array_column($teknisi, 'total')) ?>
    const palette  = ['#f59e0b','#64748b','#f97316','#22d3ee','#a78bfa','#34d399','#fb7185','#60a5fa']

    chartTeknisiInstance = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: names,
            datasets: [{
                data: totals,
                backgroundColor: palette.slice(0, names.length),
                borderColor: isLight ? 'rgba(241,245,249,0.8)' : 'rgba(15,23,42,0.8)',
                borderWidth: 3,
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: isLight ? '#475569' : '#94a3b8',
                        font: {size:11}, padding:12, boxWidth:12, boxHeight:12
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(ctx){
                            const total = ctx.dataset.data.reduce((a,b)=>a+b,0)
                            const pct   = total > 0 ? Math.round(ctx.raw/total*100) : 0
                            return ' '+ctx.label+': '+ctx.raw+' tiket ('+pct+'%)'
                        }
                    }
                }
            }
        }
    })
})()
</script>

<?php endif; ?>


<!-- ══════════════════════════ SARAN & MASUKAN ══════════════════════════ -->
<?php if($view=='saran'): ?>

<!-- KPI -->
<div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
    <div class="lp-kpi-card relative overflow-hidden rounded-xl p-4 border">
        <div class="absolute inset-0 pointer-events-none"
             style="background:linear-gradient(135deg,#06b6d4,#0891b2);opacity:var(--lp-kpi-overlay-op)"></div>
        <p class="text-xs font-semibold uppercase tracking-widest text-cyan-500 mb-1">Total Masukan</p>
        <p class="text-4xl font-black lp-kpi-num" id="kpi-total">-</p>
        <div class="absolute top-3 right-3 text-4xl" style="opacity:0.15">💬</div>
    </div>
    <div class="lp-kpi-card relative overflow-hidden rounded-xl p-4 border">
        <div class="absolute inset-0 pointer-events-none"
             style="background:linear-gradient(135deg,#f59e0b,#d97706);opacity:var(--lp-kpi-overlay-op)"></div>
        <p class="text-xs font-semibold uppercase tracking-widest text-amber-500 mb-1">Rata-rata Rating</p>
        <p class="text-4xl font-black lp-kpi-num" id="kpi-avg">-</p>
        <div class="absolute top-3 right-3 text-4xl" style="opacity:0.15">⭐</div>
    </div>
    <div class="lp-kpi-card relative overflow-hidden rounded-xl p-4 border">
        <div class="absolute inset-0 pointer-events-none"
             style="background:linear-gradient(135deg,#10b981,#059669);opacity:var(--lp-kpi-overlay-op)"></div>
        <p class="text-xs font-semibold uppercase tracking-widest text-emerald-500 mb-1">Puas (Rating ≥ 4)</p>
        <p class="text-4xl font-black lp-kpi-num" id="kpi-puas">-</p>
        <div class="absolute top-3 right-3 text-4xl" style="opacity:0.15">😊</div>
    </div>
</div>

<!-- Filter -->
<div class="lp-card p-4 rounded-lg mb-6 flex flex-wrap gap-4 items-end border">
    <div>
        <label class="block text-xs lp-label mb-1">Tanggal Mulai</label>
        <input type="text" id="saran_start"
               class="lp-input border px-3 py-2 rounded text-sm focus:ring-2 focus:outline-none transition-all">
    </div>
    <div>
        <label class="block text-xs lp-label mb-1">Tanggal Akhir</label>
        <input type="text" id="saran_end"
               class="lp-input border px-3 py-2 rounded text-sm focus:ring-2 focus:outline-none transition-all">
    </div>
    <div>
        <label class="block text-xs lp-label mb-1">Filter Rating</label>
        <select id="filter_rating"
                class="lp-select border px-3 py-2 rounded text-sm focus:ring-2 focus:outline-none transition-all">
            <option value="">Semua Rating</option>
            <option value="5">⭐⭐⭐⭐⭐ — Sangat Puas</option>
            <option value="4">⭐⭐⭐⭐ — Puas</option>
            <option value="3">⭐⭐⭐ — Cukup</option>
            <option value="2">⭐⭐ — Kurang</option>
            <option value="1">⭐ — Sangat Kurang</option>
        </select>
    </div>
</div>

<!-- Kartu Komentar -->
<div id="saran-container" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mb-4">
    <div class="col-span-3 text-center py-12 lp-page-sub animate-pulse">Memuat data...</div>
</div>

<script>
const saranStart  = document.getElementById('saran_start')
const saranEnd    = document.getElementById('saran_end')
const saranRating = document.getElementById('filter_rating')
const container   = document.getElementById('saran-container')

function starHtml(n) {
    n = parseInt(n) || 0
    return Array.from({length:5}, (_,i) =>
        `<svg class="w-4 h-4 ${i<n?'text-yellow-400':'text-slate-300'}" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
        </svg>`
    ).join('')
}

function loadSaran() {
    if (!saranStart.value || !saranEnd.value) return

    container.innerHTML = '<div class="col-span-3 text-center py-12 lp-page-sub animate-pulse">Memuat data...</div>'
    document.getElementById('kpi-total').textContent = '-'
    document.getElementById('kpi-avg').textContent   = '-'
    document.getElementById('kpi-puas').textContent  = '-'

    fetch("<?= site_url('laporan/ajax_saran') ?>", {
        method : 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body   : 'start='+saranStart.value+'&end='+saranEnd.value+'&rating='+saranRating.value
    })
    .then(r => r.json())
    .then(data => {
        if (!data.length) {
            container.innerHTML = `
<div class="col-span-3 text-center py-16 lp-page-sub">
    <div class="text-5xl mb-4">💬</div>
    <p class="font-medium">Tidak ada saran &amp; masukan pada periode ini</p>
</div>`
            return
        }

        const total = data.length
        const avg   = (data.reduce((s,d)=>s+(parseFloat(d.rating)||0),0)/total).toFixed(1)
        const puas  = data.filter(d=>(parseFloat(d.rating)||0)>=4).length
        document.getElementById('kpi-total').textContent = total
        document.getElementById('kpi-avg').textContent   = avg + ' / 5'
        document.getElementById('kpi-puas').textContent  = puas

        container.innerHTML = data.map(d => {
            const r = parseInt(d.rating) || 0
            const borderCls = r>=4 ? 'lp-saran-puas' : r>=3 ? 'lp-saran-cukup' : 'lp-saran-kurang'
            const badgeCls  = r>=4 ? 'lp-badge-saran-puas' : r>=3 ? 'lp-badge-saran-cukup' : 'lp-badge-saran-kurang'
            const label     = r>=4 ? 'Puas' : r>=3 ? 'Cukup' : 'Kurang'
            return `
<div class="lp-card rounded-xl border ${borderCls} p-4 flex flex-col gap-3 hover:scale-[1.01] transition-transform duration-200">
    <div class="flex items-start justify-between gap-2">
        <div>
            <span class="font-mono text-xs text-blue-400">${d.kode_tiket}</span>
            <p class="font-semibold lp-td-primary text-sm mt-0.5">${d.pelapor ?? '-'}</p>
            <p class="text-xs lp-page-sub">${d.lokasi ?? '-'} · ${d.kategori ?? '-'}</p>
        </div>
        <span class="shrink-0 text-xs font-semibold px-2 py-1 rounded-full ${badgeCls}">${label}</span>
    </div>
    <div class="flex gap-0.5">${starHtml(r)}</div>
    <p class="text-sm lp-saran-text leading-relaxed border-l-2 lp-quote-border pl-3 italic">
        "${d.komentar ?? 'Tidak ada komentar'}"
    </p>
    <p class="text-xs lp-page-sub text-right mt-auto">✅ ${d.selesai_at ?? '-'}</p>
</div>`
        }).join('')
    })
}

saranStart.addEventListener('change', loadSaran)
saranEnd.addEventListener('change', loadSaran)
saranRating.addEventListener('change', loadSaran)
</script>

<script>
flatpickr("#saran_start", { dateFormat:"Y-m-d", defaultDate:"<?= date('Y-m-01') ?>", allowInput:false, locale:{firstDayOfWeek:1} })
flatpickr("#saran_end",   { dateFormat:"Y-m-d", defaultDate:"<?= date('Y-m-d') ?>",  allowInput:false, locale:{firstDayOfWeek:1} })
setTimeout(loadSaran, 100)
</script>

<?php endif; ?>


<!-- ══════════════════════════════ EXPORT LINK HANDLER ══════════════════════════════ -->
<script>
(function () {
    var BASE_EXCEL = "<?= site_url('laporan/export_excel_range') ?>";
    var BASE_PDF   = "<?= site_url('laporan/export_pdf_range') ?>";
    var VIEW       = "<?= $view ?>";

    function buildUrl(base, extra) {
        var tahunEl = document.getElementById('filter_tahun_teknisi')
        var tahun   = tahunEl ? tahunEl.value : "<?= $tahun ?? date('Y') ?>"
        var params  = 'view=' + VIEW + '&tahun=' + tahun
        if (extra) params += '&' + extra
        return base + '?' + params
    }

    function updateExportLinks() {
        var extra = ''
        if (VIEW === 'tiket') {
            var s = document.getElementById('start_date')
            var e = document.getElementById('end_date')
            extra = 'start=' + (s&&s.value ? s.value : "<?= date('Y-m-01') ?>")
                  + '&end='  + (e&&e.value ? e.value : "<?= date('Y-m-d') ?>")
        } else if (VIEW === 'saran') {
            var s2 = document.getElementById('saran_start')
            var e2 = document.getElementById('saran_end')
            extra  = 'start=' + (s2&&s2.value ? s2.value : "<?= date('Y-m-01') ?>")
                   + '&end='  + (e2&&e2.value ? e2.value : "<?= date('Y-m-d') ?>")
        } else if (VIEW === 'teknisi') {
            var bulanEl = document.getElementById('filter_bulan_teknisi')
            var userEl  = document.getElementById('filter_nama_teknisi')
            var bulan   = bulanEl ? bulanEl.value : ''
            var userId  = userEl  ? userEl.value  : ''
            if (bulan)  extra += 'bulan=' + bulan
            if (userId) extra += (extra ? '&' : '') + 'user_id=' + userId
        }
        document.getElementById('btnExcel').href = buildUrl(BASE_EXCEL, extra)
        document.getElementById('btnPdf').href   = buildUrl(BASE_PDF,   extra)
    }

    var watchIds = [
        'start_date','end_date',
        'saran_start','saran_end',
        'filter_tahun_teknisi','filter_bulan_teknisi','filter_nama_teknisi'
    ]
    watchIds.forEach(function(id) {
        var el = document.getElementById(id)
        if (el) el.addEventListener('change', updateExportLinks)
    })
    updateExportLinks()
    window.updateExportLinks = updateExportLinks
})()
</script>