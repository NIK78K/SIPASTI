<!-- application/views/rekap/rekap_bulanan_form.php -->

<!-- HEADER -->
<div class="mb-8">
    <h1 class="text-4xl font-bold header-title mb-2">Rekap Bulanan TI</h1>
    <p class="header-subtitle text-sm">
        Laporan Aset &amp; Layanan TI &bull; <span id="periodeText">Pilih Periode</span>
    </p>
</div>

<!-- FILTER CARD -->
<div class="theme-card rounded-2xl p-6 mb-8 border">
    <div class="flex items-center gap-2 mb-5">
        <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
        </svg>
        <h2 class="text-lg font-semibold header-title">Pilih Periode</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- BULAN -->
        <div>
            <label class="block text-xs font-medium header-subtitle mb-2">Bulan</label>
            <div class="rkp-select-wrap">
                <select id="bulan" class="rkp-select w-full">
                    <?php foreach ($bulan_list as $num => $nama): ?>
                    <option value="<?= $num ?>" <?= $num == date('n') ? 'selected' : '' ?>><?= $nama ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="rkp-chevron">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </span>
            </div>
        </div>

        <!-- TAHUN -->
        <div>
            <label class="block text-xs font-medium header-subtitle mb-2">Tahun</label>
            <div class="rkp-select-wrap">
                <select id="tahun" class="rkp-select w-full">
                    <?php foreach ($tahun_list as $y): ?>
                    <option value="<?= $y ?>" <?= $y == date('Y') ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="rkp-chevron">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </span>
            </div>
        </div>

        <!-- SATKER -->
        <div>
            <label class="block text-xs font-medium header-subtitle mb-2">Satker</label>
            <div class="rkp-select-wrap">
                <select id="satker" class="rkp-select w-full">
                    <option value="">Semua Satker</option>
                </select>
                <span class="rkp-chevron">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </span>
            </div>
        </div>

        <!-- TOMBOL -->
        <div class="flex items-end gap-2">
            <button id="btnLoad"
                    class="flex-1 bg-gradient-to-r from-cyan-600 to-blue-600
                           hover:from-cyan-500 hover:to-blue-500
                           px-4 py-2.5 rounded-lg text-sm font-medium text-white
                           transition-all transform hover:scale-105 active:scale-95 shadow-lg">
                <span class="flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Tampilkan
                </span>
            </button>
        </div>
    </div>
</div>

<!-- SUMMARY CARDS -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <!-- TOTAL ASET -->
    <div class="stat-card stat-card-cyan p-6 rounded-2xl shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white opacity-10 rounded-full -mr-12 -mt-12"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 stat-icon-cyan" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <p class="text-xs font-medium stat-label-cyan">Total Aset</p>
            </div>
            <h2 id="cardTotal" class="text-4xl font-bold stat-value mt-1">0</h2>
            <p class="stat-label-cyan text-xs mt-2 opacity-80">Unit Perangkat</p>
        </div>
    </div>

    <!-- KONDISI BAIK -->
    <div class="stat-card stat-card-green p-6 rounded-2xl shadow-lg relative overflow-hidden">
        <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-10 rounded-full -ml-10 -mb-10"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 stat-icon-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-xs font-medium stat-label-green">Kondisi Baik</p>
            </div>
            <h2 id="cardBaik" class="text-4xl font-bold stat-value mt-1">0</h2>
            <p id="cardBaikPct" class="stat-label-green text-xs mt-2 opacity-80">0% dari total</p>
        </div>
    </div>

    <!-- RUSAK RINGAN -->
    <div class="stat-card stat-card-yellow p-6 rounded-2xl shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white opacity-10 rounded-full -mr-12 -mt-12"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 stat-icon-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <p class="text-xs font-medium stat-label-yellow">Rusak Ringan</p>
            </div>
            <h2 id="cardRR" class="text-4xl font-bold stat-value mt-1">0</h2>
            <p id="cardRRPct" class="stat-label-yellow text-xs mt-2 opacity-80">0% dari total</p>
        </div>
    </div>

    <!-- RUSAK BERAT -->
    <div class="stat-card stat-card-red p-6 rounded-2xl shadow-lg relative overflow-hidden">
        <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-10 rounded-full -ml-10 -mb-10"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 stat-icon-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-xs font-medium stat-label-red">Rusak Berat</p>
            </div>
            <h2 id="cardRB" class="text-4xl font-bold stat-value mt-1">0</h2>
            <p id="cardRBPct" class="stat-label-red text-xs mt-2 opacity-80">0% dari total</p>
        </div>
    </div>
</div>

<!-- ACTION BUTTONS -->
<div class="flex flex-wrap gap-3 mb-8">
    <button id="btnGenerate"
            class="flex items-center gap-3 bg-red-600 hover:bg-red-500 px-6 py-3 rounded-xl text-sm font-medium text-white transition-all transform hover:scale-105 active:scale-95 shadow-lg">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
        </svg>
        Generate PDF
    </button>
    <button id="btnPrint"
            class="flex items-center gap-3 rkp-btn-print px-6 py-3 rounded-xl text-sm font-medium transition-all transform hover:scale-105 active:scale-95 shadow-lg">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
        </svg>
        Print
    </button>
</div>

<!-- TABEL I - ASET TI PER KATEGORI -->
<div id="tableAset" class="theme-card rounded-2xl overflow-hidden border shadow-lg mb-6">
    <div class="rkp-table-header px-6 py-5 border-b">
        <h3 class="header-title font-semibold text-lg flex items-center gap-2">
            <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
            </svg>
            I. Aset TI per Kategori
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="thead-table">
                <tr class="header-subtitle">
                    <th class="px-6 py-3 text-left font-medium">No</th>
                    <th class="px-6 py-3 text-left font-medium">Kategori Aset</th>
                    <th class="px-6 py-3 text-center font-medium">Jumlah</th>
                    <th class="px-6 py-3 text-center font-medium">Baik</th>
                    <th class="px-6 py-3 text-center font-medium">Rusak Ringan</th>
                    <th class="px-6 py-3 text-center font-medium">Rusak Berat</th>
                    <th class="px-6 py-3 text-center font-medium">% Baik</th>
                </tr>
            </thead>
            <tbody id="tbodyAset" class="divide-y divide-slate-800/50">
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center header-subtitle">
                        <svg class="w-10 h-10 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        Pilih periode lalu klik Tampilkan
                    </td>
                </tr>
            </tbody>
            <tfoot id="tfootAset" class="tfoot-table" style="display:none">
                <tr>
                    <td colspan="2" class="px-6 py-4 font-bold header-title text-sm">TOTAL</td>
                    <td class="px-6 py-4 text-center">
                        <span id="footTotal" class="inline-block bg-cyan-500/20 text-cyan-400 px-3 py-1 rounded-lg font-bold">0</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span id="footBaik" class="inline-block bg-green-500/20 text-green-400 px-3 py-1 rounded-lg font-bold">0</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span id="footRR" class="inline-block bg-yellow-500/20 text-yellow-400 px-3 py-1 rounded-lg font-bold">0</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span id="footRB" class="inline-block bg-red-500/20 text-red-400 px-3 py-1 rounded-lg font-bold">0</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span id="footPct" class="inline-block px-3 py-1 rounded-lg font-bold">-</span>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- TABEL II - LAYANAN TI -->
<div id="tableLayanan" class="theme-card rounded-2xl overflow-hidden border shadow-lg mb-8">
    <div class="rkp-table-header px-6 py-5 border-b">
        <h3 class="header-title font-semibold text-lg flex items-center gap-2">
            <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z"/>
            </svg>
            II. Layanan TI (Tiket)
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="thead-table">
                <tr class="header-subtitle">
                    <th class="px-6 py-3 text-left font-medium">No</th>
                    <th class="px-6 py-3 text-left font-medium">Jenis Layanan</th>
                    <th class="px-6 py-3 text-center font-medium">Masuk</th>
                    <th class="px-6 py-3 text-center font-medium">Ditanggapi</th>
                    <th class="px-6 py-3 text-center font-medium">Diselesaikan</th>
                    <th class="px-6 py-3 text-center font-medium">% Selesai</th>
                </tr>
            </thead>
            <tbody id="tbodyLayanan" class="divide-y divide-slate-800/50">
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center header-subtitle">
                        <svg class="w-10 h-10 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        Pilih periode lalu klik Tampilkan
                    </td>
                </tr>
            </tbody>
            <tfoot id="tfootLayanan" class="tfoot-table" style="display:none">
                <tr>
                    <td colspan="2" class="px-6 py-4 font-bold header-title text-sm">TOTAL</td>
                    <td class="px-6 py-4 text-center">
                        <span id="layFootMasuk" class="inline-block bg-blue-500/20 text-blue-400 px-3 py-1 rounded-lg font-bold">0</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span id="layFootTanggap" class="inline-block bg-purple-500/20 text-purple-400 px-3 py-1 rounded-lg font-bold">0</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span id="layFootSelesai" class="inline-block bg-green-500/20 text-green-400 px-3 py-1 rounded-lg font-bold">0</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span id="layFootPct" class="inline-block px-3 py-1 rounded-lg font-bold">-</span>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<script>
const LAYANAN_LABEL = {
    permintaan: 'Permintaan (upgrade, pengembangan, penambahan jaringan, dll)',
    gangguan:   'Gangguan (perangkat mati, koneksi putus, UPS rusak, dll)'
};

function animateValue(el, end, duration = 600) {
    const start = parseInt(el.innerText) || 0;
    const range = end - start;
    if (range === 0) return;
    const step = range / (duration / 16);
    let current = start;
    const timer = setInterval(() => {
        current += step;
        if ((step > 0 && current >= end) || (step < 0 && current <= end)) {
            el.innerText = end;
            clearInterval(timer);
        } else {
            el.innerText = Math.floor(current);
        }
    }, 16);
}

function pct(a, b) {
    if (!b || b === 0) return '0.0%';
    return ((a / b) * 100).toFixed(1) + '%';
}

// Returns Tailwind-compatible classes based on percentage value
function pctBadgeClass(numPct) {
    if (numPct >= 80) return 'bg-green-500/20 text-green-400';
    if (numPct >= 50) return 'bg-yellow-500/20 text-yellow-400';
    return 'bg-red-500/20 text-red-400';
}

function updateSummaryCards(data) {
    const s = data.summary_aset || {};
    animateValue(document.getElementById('cardTotal'), s.total || 0);
    animateValue(document.getElementById('cardBaik'), s.baik || 0);
    animateValue(document.getElementById('cardRR'), s.rusak_ringan || 0);
    animateValue(document.getElementById('cardRB'), s.rusak_berat || 0);

    const t = s.total || 1;
    document.getElementById('cardBaikPct').innerText = pct(s.baik || 0, t) + ' dari total';
    document.getElementById('cardRRPct').innerText   = pct(s.rusak_ringan || 0, t) + ' dari total';
    document.getElementById('cardRBPct').innerText   = pct(s.rusak_berat || 0, t) + ' dari total';
}

function renderTableAset(rows) {
    const tbody = document.getElementById('tbodyAset');
    const tfoot = document.getElementById('tfootAset');

    if (!rows || rows.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="px-6 py-12 text-center header-subtitle">
                    <svg class="w-10 h-10 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    Tidak ada data aset
                </td>
            </tr>`;
        tfoot.style.display = 'none';
        return;
    }

    let html = '';
    let totJ = 0, totB = 0, totRR = 0, totRB = 0;

    rows.forEach((row, idx) => {
        const j  = parseInt(row.jumlah || 0);
        const b  = parseInt(row.baik || 0);
        const rr = parseInt(row.rusak_ringan || 0);
        const rb = parseInt(row.rusak_berat || 0);
        totJ += j; totB += b; totRR += rr; totRB += rb;

        const baikPct   = j > 0 ? ((b / j) * 100).toFixed(0) : 0;
        const baikColor = pctBadgeClass(parseFloat(baikPct));

        html += `
        <tr class="table-row-hover transition-colors">
            <td class="px-6 py-4 header-subtitle text-sm">${idx + 1}</td>
            <td class="px-6 py-4 header-title font-medium text-sm">${row.kategori}</td>
            <td class="px-6 py-4 text-center">
                <span class="inline-block bg-cyan-500/20 text-cyan-400 px-3 py-1 rounded-lg font-semibold text-sm">${j || '-'}</span>
            </td>
            <td class="px-6 py-4 text-center">
                <span class="inline-block bg-green-500/20 text-green-400 px-3 py-1 rounded-lg font-medium text-sm">${b || '-'}</span>
            </td>
            <td class="px-6 py-4 text-center">
                <span class="inline-block bg-yellow-500/20 text-yellow-400 px-3 py-1 rounded-lg font-medium text-sm">${rr || '-'}</span>
            </td>
            <td class="px-6 py-4 text-center">
                <span class="inline-block bg-red-500/20 text-red-400 px-3 py-1 rounded-lg font-medium text-sm">${rb || '-'}</span>
            </td>
            <td class="px-6 py-4 text-center">
                <span class="inline-block ${baikColor} px-3 py-1 rounded-lg font-medium text-sm">${baikPct}%</span>
            </td>
        </tr>`;
    });

    tbody.innerHTML = html;

    // Footer totals
    const overallNum = totJ > 0 ? parseFloat(((totB / totJ) * 100).toFixed(1)) : 0;
    const overallPct = totJ > 0 ? overallNum.toFixed(1) + '%' : '-';

    document.getElementById('footTotal').innerText = totJ;
    document.getElementById('footBaik').innerText  = totB;
    document.getElementById('footRR').innerText    = totRR;
    document.getElementById('footRB').innerText    = totRB;

    const footPctEl = document.getElementById('footPct');
    footPctEl.innerText = overallPct;
    footPctEl.className = 'inline-block px-3 py-1 rounded-lg font-bold ' + pctBadgeClass(overallNum);

    tfoot.style.display = '';
}

function renderTableLayanan(layanan) {
    const tbody = document.getElementById('tbodyLayanan');
    const tfoot = document.getElementById('tfootLayanan');

    if (!layanan) {
        tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-12 text-center header-subtitle">Tidak ada data layanan</td></tr>`;
        tfoot.style.display = 'none';
        return;
    }

    const types = ['permintaan', 'gangguan'];
    let html = '';
    let totM = 0, totT = 0, totS = 0;

    types.forEach((key, idx) => {
        const d = layanan[key] || {};
        const m = parseInt(d.masuk || 0);
        const t = parseInt(d.ditanggapi || 0);
        const s = parseInt(d.selesai || 0);
        totM += m; totT += t; totS += s;

        const selesaiPct = m > 0 ? ((s / m) * 100).toFixed(0) : 0;
        const pctColor   = pctBadgeClass(parseFloat(selesaiPct));

        html += `
        <tr class="table-row-hover transition-colors">
            <td class="px-6 py-4 header-subtitle text-sm">${idx + 1}</td>
            <td class="px-6 py-4 header-title text-sm" style="max-width:360px">${LAYANAN_LABEL[key]}</td>
            <td class="px-6 py-4 text-center">
                <span class="inline-block bg-blue-500/20 text-blue-400 px-3 py-1 rounded-lg font-medium text-sm">${m || '-'}</span>
            </td>
            <td class="px-6 py-4 text-center">
                <span class="inline-block bg-purple-500/20 text-purple-400 px-3 py-1 rounded-lg font-medium text-sm">${t || '-'}</span>
            </td>
            <td class="px-6 py-4 text-center">
                <span class="inline-block bg-green-500/20 text-green-400 px-3 py-1 rounded-lg font-medium text-sm">${s || '-'}</span>
            </td>
            <td class="px-6 py-4 text-center">
                <span class="inline-block ${pctColor} px-3 py-1 rounded-lg font-medium text-sm">${selesaiPct}%</span>
            </td>
        </tr>`;
    });

    tbody.innerHTML = html;

    const totNum = totM > 0 ? parseFloat(((totS / totM) * 100).toFixed(1)) : 0;
    const totPct = totM > 0 ? totNum.toFixed(1) + '%' : '-';

    document.getElementById('layFootMasuk').innerText   = totM;
    document.getElementById('layFootTanggap').innerText = totT;
    document.getElementById('layFootSelesai').innerText = totS;

    const layFootPctEl = document.getElementById('layFootPct');
    layFootPctEl.innerText = totPct;
    layFootPctEl.className = 'inline-block px-3 py-1 rounded-lg font-bold ' + pctBadgeClass(totNum);

    tfoot.style.display = '';
}

function setLoading(show) {
    const loadingHtml = `
        <tr>
            <td colspan="7" class="px-6 py-12 text-center header-subtitle">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-gray-700 border-t-cyan-500 mb-3"></div>
                <p>Memuat data...</p>
            </td>
        </tr>`;
    if (show) {
        document.getElementById('tbodyAset').innerHTML = loadingHtml;
        document.getElementById('tbodyLayanan').innerHTML = loadingHtml.replace('colspan="7"', 'colspan="6"');
        document.getElementById('tfootAset').style.display = 'none';
        document.getElementById('tfootLayanan').style.display = 'none';
    }
}

function loadData() {
    const bulan     = document.getElementById('bulan').value;
    const tahun     = document.getElementById('tahun').value;
    const namaBulan = document.getElementById('bulan').options[document.getElementById('bulan').selectedIndex].text;

    document.getElementById('periodeText').innerText = namaBulan + ' ' + tahun;
    setLoading(true);

    fetch("<?= site_url('rekap_bulanan/ajax_rekap') ?>", {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `bulan=${bulan}&tahun=${tahun}`
    })
    .then(r => r.json())
    .then(res => {
        updateSummaryCards(res);
        renderTableAset(res.aset_kategori || []);
        renderTableLayanan(res.layanan || null);
    })
    .catch(err => {
        console.error(err);
        document.getElementById('tbodyAset').innerHTML    = `<tr><td colspan="7" class="px-6 py-8 text-center text-red-400">Gagal memuat data. Coba lagi.</td></tr>`;
        document.getElementById('tbodyLayanan').innerHTML = `<tr><td colspan="6" class="px-6 py-8 text-center text-red-400">Gagal memuat data. Coba lagi.</td></tr>`;
    });
}

document.getElementById('btnLoad').addEventListener('click', loadData);

document.getElementById('btnGenerate').addEventListener('click', function () {
    const bulan = document.getElementById('bulan').value;
    const tahun = document.getElementById('tahun').value;
    const btn   = this;

    btn.innerHTML = `
        <span class="flex items-center gap-2">
            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            Membuat PDF...
        </span>`;
    btn.disabled = true;

    window.open('<?= base_url('rekap_bulanan/generate_pdf') ?>?bulan=' + bulan + '&tahun=' + tahun, '_blank');

    setTimeout(() => {
        btn.innerHTML = `
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            Generate PDF`;
        btn.disabled = false;
    }, 2500);
});

document.getElementById('btnPrint').addEventListener('click', () => window.print());

loadData();
</script>

<style>
@keyframes spin { to { transform: rotate(360deg); } }
.animate-spin { animation: spin 1s linear infinite; }

/* ══ SELECT WRAPPER (sama pattern dengan halaman lain) ══ */
.rkp-select-wrap { position: relative; }
.rkp-select-wrap select.rkp-select {
    all: unset;
    display: block; width: 100%; box-sizing: border-box;
    background: rgba(30,41,59,0.8);
    border: 1px solid rgba(51,65,85,0.5);
    color: #f1f5f9;
    border-radius: 0.5rem;
    padding: 0.625rem 2.25rem 0.625rem 1rem;
    font-size: 0.875rem;
    cursor: pointer;
    transition: border-color .2s, box-shadow .2s;
}
.rkp-select-wrap select.rkp-select:focus {
    border-color: #06b6d4;
    box-shadow: 0 0 0 2px rgba(6,182,212,0.2);
}
.rkp-chevron {
    position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
    pointer-events: none; color: #9ca3af;
    display: flex; align-items: center;
}

/* ══ PRINT BUTTON ══ */
.rkp-btn-print { background: #374151; color: #ffffff; }
.rkp-btn-print:hover { background: #4b5563; }

/* ══ TABLE HEADER / FOOTER ══ */
.rkp-table-header {
    background: rgba(15,23,42,0.9);
    border-bottom-color: rgba(51,65,85,0.5);
}
.thead-table {
    background: rgba(15,23,42,0.9);
    border-bottom: 1px solid rgba(51,65,85,0.5);
}
.tfoot-table tr {
    background: rgba(15,23,42,0.95);
    border-top: 2px solid rgba(6,182,212,0.3);
}
.table-row-hover:hover { background: rgba(51,65,85,0.4); }

/* ══ STAT CARDS — dark default ══ */
.stat-card-cyan   { background: linear-gradient(135deg, #0891b2, #2563eb, #7c3aed); border: 1px solid rgba(6,182,212,0.2); }
.stat-card-green  { background: linear-gradient(135deg, #059669, #16a34a); border: 1px solid rgba(34,197,94,0.2); }
.stat-card-yellow { background: linear-gradient(135deg, #d97706, #ea580c); border: 1px solid rgba(234,179,8,0.2); }
.stat-card-red    { background: linear-gradient(135deg, #dc2626, #e11d48); border: 1px solid rgba(239,68,68,0.2); }

.stat-value        { color: #ffffff; }
.stat-icon-cyan    { color: #a5f3fc; }
.stat-label-cyan   { color: #a5f3fc; }
.stat-icon-green   { color: #a7f3d0; }
.stat-label-green  { color: #a7f3d0; }
.stat-icon-yellow  { color: #fde68a; }
.stat-label-yellow { color: #fde68a; }
.stat-icon-red     { color: #fecaca; }
.stat-label-red    { color: #fecaca; }

/* ══════════════════════════════════════
   LIGHT MODE OVERRIDES
══════════════════════════════════════ */

/* Select */
html.light .rkp-select-wrap select.rkp-select {
    background: #ffffff !important;
    border-color: #cbd5e1 !important;
    color: #0f172a !important;
}
html.light .rkp-select-wrap select.rkp-select:focus {
    border-color: #0891b2 !important;
    box-shadow: 0 0 0 2px rgba(8,145,178,0.15) !important;
}
html.light .rkp-chevron { color: #64748b !important; }

/* Print button */
html.light .rkp-btn-print { background: #e2e8f0 !important; color: #334155 !important; }
html.light .rkp-btn-print:hover { background: #cbd5e1 !important; }

/* Table header bars */
html.light .rkp-table-header {
    background: #f1f5f9 !important;
    border-bottom-color: #e2e8f0 !important;
}
html.light .thead-table {
    background: #f8fafc !important;
    border-bottom-color: #e2e8f0 !important;
}
html.light .thead-table th { color: #475569 !important; }

/* Table footer */
html.light .tfoot-table tr {
    background: #f1f5f9 !important;
    border-top: 2px solid rgba(6,182,212,0.35) !important;
}

/* Table rows */
html.light #tbodyAset tr,
html.light #tbodyLayanan tr { background: #ffffff !important; }
html.light .table-row-hover:hover { background: #f1f5f9 !important; }

/* Stat cards — light mode: putih bersih */
html.light .stat-card-cyan,
html.light .stat-card-green,
html.light .stat-card-yellow,
html.light .stat-card-red {
    background: #ffffff !important;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06) !important;
}
html.light .stat-value { color: #0f172a !important; }

html.light .stat-icon-cyan,
html.light .stat-label-cyan   { color: #0891b2 !important; opacity: 1 !important; }
html.light .stat-icon-green,
html.light .stat-label-green  { color: #059669 !important; opacity: 1 !important; }
html.light .stat-icon-yellow,
html.light .stat-label-yellow { color: #d97706 !important; opacity: 1 !important; }
html.light .stat-icon-red,
html.light .stat-label-red    { color: #dc2626 !important; opacity: 1 !important; }

/* Badge % di tfoot — green/yellow/red tetap kontras di light */
html.light .bg-green-500\/20  { background: rgba(34,197,94,0.12)  !important; }
html.light .text-green-400    { color: #15803d !important; }
html.light .bg-yellow-500\/20 { background: rgba(234,179,8,0.12)  !important; }
html.light .text-yellow-400   { color: #92400e !important; }
html.light .bg-red-500\/20    { background: rgba(239,68,68,0.12)  !important; }
html.light .text-red-400      { color: #b91c1c !important; }
html.light .bg-cyan-500\/20   { background: rgba(6,182,212,0.12)  !important; }
html.light .text-cyan-400     { color: #0e7490 !important; }
html.light .bg-blue-500\/20   { background: rgba(59,130,246,0.12) !important; }
html.light .text-blue-400     { color: #1d4ed8 !important; }
html.light .bg-purple-500\/20 { background: rgba(168,85,247,0.12) !important; }
html.light .text-purple-400   { color: #7e22ce !important; }

@media print {
    body * { visibility: hidden; }
    #tableAset, #tableAset *, #tableLayanan, #tableLayanan * { visibility: visible; }
    #tableAset { position: absolute; left: 0; top: 0; width: 100%; }
}
</style>