<!-- application/views/laporan/rekap.php -->

<!-- HEADER -->
<div class="mb-8">
    <h1 class="text-4xl font-bold header-title mb-2">Rekap Perbaikan</h1>
    <p class="header-subtitle text-sm">
        Ringkasan Tiket IT &bull; <span id="periodeText">-</span>
    </p>
</div>

<!-- FILTER CARD -->
<div class="theme-card rounded-2xl p-6 mb-8 border">
    <div class="flex items-center gap-2 mb-5">
        <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
        </svg>
        <h2 class="text-lg font-semibold header-title">Filter Periode</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-xs font-medium header-subtitle mb-2">Bulan</label>
            <select id="bulan" class="filter-select w-full bg-slate-800/80 border border-slate-700/50
                           px-4 py-2.5 rounded-lg text-sm text-white
                           focus:outline-none focus:ring-2 focus:ring-cyan-500 transition-all">
                <option value="all">Semua Bulan</option>
                <?php for($m=1;$m<=12;$m++): ?>
                <option value="<?= sprintf('%02d',$m) ?>" <?= $m == date('n') ? 'selected' : '' ?>>
                    <?= date('F', mktime(0,0,0,$m,1)) ?>
                </option>
                <?php endfor ?>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium header-subtitle mb-2">Tahun Dari</label>
            <input type="number" id="tahun_start" value="<?= date('Y') ?>" min="2000" max="2100"
                   class="filter-select w-full bg-slate-800/80 border border-slate-700/50
                          px-4 py-2.5 rounded-lg text-sm text-white
                          focus:outline-none focus:ring-2 focus:ring-cyan-500 transition-all">
        </div>
        <div>
            <label class="block text-xs font-medium header-subtitle mb-2">Tahun Sampai</label>
            <input type="number" id="tahun_end" value="<?= date('Y') ?>" min="2000" max="2100"
                   class="filter-select w-full bg-slate-800/80 border border-slate-700/50
                          px-4 py-2.5 rounded-lg text-sm text-white
                          focus:outline-none focus:ring-2 focus:ring-cyan-500 transition-all">
        </div>
        <div class="flex items-end">
            <button id="btnFilter"
                    class="w-full bg-gradient-to-r from-cyan-600 to-blue-600
                           hover:from-cyan-500 hover:to-blue-500
                           px-4 py-2.5 rounded-lg text-sm font-medium text-white
                           transition-all transform hover:scale-105 active:scale-95 shadow-lg">
                <span class="flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Tampilkan
                </span>
            </button>
        </div>
    </div>
</div>

<!-- SUMMARY CARDS -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <div class="stat-card bg-gradient-to-br from-cyan-600 via-blue-600 to-purple-700
                p-6 rounded-2xl shadow-lg relative overflow-hidden border border-cyan-500/20">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white opacity-5 rounded-full -mr-12 -mt-12"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-cyan-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z"/>
                </svg>
                <p class="text-xs font-medium text-cyan-100">Total Tiket</p>
            </div>
            <h2 id="cardTotal" class="text-4xl font-bold text-white mt-1">0</h2>
            <p class="text-cyan-100 text-xs mt-2 opacity-80">Semua Kategori</p>
        </div>
    </div>

    <div class="stat-card bg-gradient-to-br from-emerald-600 to-green-700
                p-6 rounded-2xl shadow-lg relative overflow-hidden border border-emerald-500/20">
        <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full -ml-10 -mb-10"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-emerald-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-xs font-medium text-emerald-100">Selesai</p>
            </div>
            <h2 id="cardSelesai" class="text-4xl font-bold text-white mt-1">0</h2>
            <p id="cardSelesaiPct" class="text-emerald-100 text-xs mt-2 opacity-80">0% dari total</p>
        </div>
    </div>

    <div class="stat-card bg-gradient-to-br from-yellow-500 to-orange-600
                p-6 rounded-2xl shadow-lg relative overflow-hidden border border-yellow-500/20">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white opacity-5 rounded-full -mr-12 -mt-12"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-yellow-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-xs font-medium text-yellow-100">On Progress</p>
            </div>
            <h2 id="cardProgress" class="text-4xl font-bold text-white mt-1">0</h2>
            <p id="cardProgressPct" class="text-yellow-100 text-xs mt-2 opacity-80">0% dari total</p>
        </div>
    </div>

    <div class="stat-card bg-gradient-to-br from-red-600 to-rose-700
                p-6 rounded-2xl shadow-lg relative overflow-hidden border border-red-500/20">
        <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full -ml-10 -mb-10"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-red-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                </svg>
                <p class="text-xs font-medium text-red-100">Menunggu</p>
            </div>
            <h2 id="cardMenunggu" class="text-4xl font-bold text-white mt-1">0</h2>
            <p id="cardMenungguPct" class="text-red-100 text-xs mt-2 opacity-80">0% dari total</p>
        </div>
    </div>

</div>

<!-- EXPORT BUTTONS -->
<div class="flex flex-wrap gap-3 mb-8">
    <a id="btnExcel" href="#"
       class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-500 px-5 py-2.5
              rounded-xl text-sm font-medium text-white transition-all hover:scale-105 active:scale-95 shadow-md">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Export Excel
    </a>
    <a id="btnPdf" href="#"
       class="flex items-center gap-2 bg-red-600 hover:bg-red-500 px-5 py-2.5
              rounded-xl text-sm font-medium text-white transition-all hover:scale-105 active:scale-95 shadow-md">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
        </svg>
        Export PDF
    </a>
    <button id="btnPrint"
            class="flex items-center gap-2 bg-slate-700 hover:bg-slate-600 px-5 py-2.5
                   rounded-xl text-sm font-medium text-white transition-all hover:scale-105 active:scale-95 shadow-md">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
        </svg>
        Print
    </button>
</div>

<!-- TABEL REKAP -->
<div id="tableRekap" class="theme-card rounded-2xl overflow-hidden border shadow-lg mb-6">
    <div class="px-6 py-4 border-b rekap-card-head flex items-center justify-between">
        <h3 class="font-semibold text-base header-title flex items-center gap-2">
            <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Rekap per Jenis &amp; Sub Layanan
        </h3>
        <span id="tableInfo" class="text-xs header-subtitle" style="display:none">
            <span id="tableInfoText"></span> baris
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="rekap-thead">
                <tr>
                    <th class="px-5 py-3 text-left font-semibold header-subtitle w-10">No</th>
                    <th class="px-5 py-3 text-left font-semibold header-subtitle">Jenis Layanan</th>
                    <th class="px-5 py-3 text-left font-semibold header-subtitle">Sub Layanan</th>
                    <th class="px-5 py-3 text-center font-semibold header-subtitle">Total</th>
                    <th class="px-5 py-3 text-center font-semibold header-subtitle">Selesai</th>
                    <th class="px-5 py-3 text-center font-semibold header-subtitle">On Progress</th>
                    <th class="px-5 py-3 text-center font-semibold header-subtitle">Menunggu</th>
                    <th class="px-5 py-3 text-center font-semibold header-subtitle">% Selesai</th>
                </tr>
            </thead>
            <tbody id="tbodyRekap">
                <tr>
                    <td colspan="8" class="px-5 py-14 text-center header-subtitle">
                        <svg class="w-10 h-10 mx-auto mb-3 opacity-25" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="text-sm">Pilih periode lalu klik <span class="font-medium">Tampilkan</span></p>
                    </td>
                </tr>
            </tbody>
            <tfoot id="tfootRekap" class="rekap-tfoot" style="display:none">
                <tr>
                    <td colspan="3" class="px-5 py-3 font-bold header-title text-sm">TOTAL KESELURUHAN</td>
                    <td class="px-5 py-3 text-center">
                        <span id="footTotal" class="inline-block bg-cyan-500/20 text-cyan-400 px-3 py-1 rounded-lg font-bold text-sm">0</span>
                    </td>
                    <td class="px-5 py-3 text-center">
                        <span id="footSelesai" class="inline-block bg-emerald-500/20 text-emerald-400 px-3 py-1 rounded-lg font-bold text-sm">0</span>
                    </td>
                    <td class="px-5 py-3 text-center">
                        <span id="footProgress" class="inline-block bg-yellow-500/20 text-yellow-400 px-3 py-1 rounded-lg font-bold text-sm">0</span>
                    </td>
                    <td class="px-5 py-3 text-center">
                        <span id="footMenunggu" class="inline-block bg-red-500/20 text-red-400 px-3 py-1 rounded-lg font-bold text-sm">0</span>
                    </td>
                    <td class="px-5 py-3 text-center">
                        <span id="footPct" class="inline-block px-3 py-1 rounded-lg font-bold text-sm">-</span>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- PAGINATION -->
<div id="paginationBox" class="mt-4 flex flex-col md:flex-row items-center justify-between
     theme-card rounded-2xl p-5 border gap-4" style="display:none">
    <span class="header-subtitle text-sm">
        Baris <span id="showingStart" class="header-title font-semibold">1</span>–<span
               id="showingEnd" class="header-title font-semibold">15</span>
        dari <span id="totalData" class="header-title font-semibold">0</span>
    </span>
    <div class="flex items-center gap-2 flex-wrap justify-center">
        <button id="btnFirst" class="pag-btn px-3 py-2 rounded-lg transition-all disabled:opacity-40 disabled:cursor-not-allowed">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/></svg>
        </button>
        <button id="btnPrev" class="pag-btn px-4 py-2 rounded-lg transition-all disabled:opacity-40 disabled:cursor-not-allowed flex items-center gap-1 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg> Prev
        </button>
        <div id="pageNumbers" class="flex items-center gap-1.5"></div>
        <button id="btnNext" class="pag-btn px-4 py-2 rounded-lg transition-all disabled:opacity-40 disabled:cursor-not-allowed flex items-center gap-1 text-sm font-medium">
            Next <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>
        <button id="btnLast" class="pag-btn px-3 py-2 rounded-lg transition-all disabled:opacity-40 disabled:cursor-not-allowed">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
        </button>
    </div>
</div>

<script>
let flatRows = [];
let curPage  = 1;
const PER    = 15;

function animCount(el, to, dur) {
    dur = dur || 700;
    var from = parseInt(el.innerText) || 0;
    if (from === to) return;
    var step = (to - from) / (dur / 16);
    var v = from;
    var t = setInterval(function() {
        v += step;
        var done = step > 0 ? v >= to : v <= to;
        el.innerText = done ? to : Math.floor(v);
        if (done) clearInterval(t);
    }, 16);
}

function pct(a, b)  { return b ? ((a/b)*100).toFixed(1)+'%' : '0%'; }
function pctN(a, b) { return b ? +((a/b)*100).toFixed(0) : 0; }

function badgeCls(p) {
    if (p >= 80) return 'bg-emerald-500/20 text-emerald-400';
    if (p >= 50) return 'bg-yellow-500/20 text-yellow-400';
    return 'bg-red-500/20 text-red-400';
}

function buildRows(kategori) {
    var rows = [];
    var no   = 1;
    Object.entries(kategori).forEach(function(entry) {
        var katNama = entry[0];
        var kat     = entry[1];
        var subs    = kat.sub || [];
        if (!subs.length) {
            rows.push({ rowNo: no++, katNama: katNama, sub: '—',
                        total: kat.total||0, selesai: kat.selesai||0,
                        progress: kat.progress||0, menunggu: kat.menunggu||0 });
            return;
        }
        subs.forEach(function(s, i) {
            rows.push({
                rowNo   : i === 0 ? no++ : null,
                katNama : i === 0 ? katNama : null,
                sub     : s.nama,
                total   : s.total    || 0,
                selesai : s.selesai  || 0,
                progress: s.progress || 0,
                menunggu: s.menunggu || 0
            });
        });
    });
    return rows;
}

function updateCards(total, selesai, progress, menunggu) {
    animCount(document.getElementById('cardTotal'),    total);
    animCount(document.getElementById('cardSelesai'),  selesai);
    animCount(document.getElementById('cardProgress'), progress);
    animCount(document.getElementById('cardMenunggu'), menunggu);
    var t = total || 1;
    document.getElementById('cardSelesaiPct').innerText   = pct(selesai, t)   + ' dari total';
    document.getElementById('cardProgressPct').innerText  = pct(progress, t)  + ' dari total';
    document.getElementById('cardMenungguPct').innerText  = pct(menunggu, t)  + ' dari total';
}

// Warna dot per kategori
var KAT_COLORS = ['bg-cyan-400','bg-violet-400','bg-orange-400','bg-pink-400','bg-teal-400','bg-lime-400'];
var katColorMap = {};
var katColorIdx = 0;
function katColor(name) {
    if (!name) return '';
    if (!katColorMap[name]) {
        katColorMap[name] = KAT_COLORS[katColorIdx % KAT_COLORS.length];
        katColorIdx++;
    }
    return katColorMap[name];
}

function renderTable() {
    var tbody = document.getElementById('tbodyRekap');
    var tfoot = document.getElementById('tfootRekap');
    var pBox  = document.getElementById('paginationBox');

    if (!flatRows.length) {
        tbody.innerHTML = '<tr><td colspan="8" class="px-5 py-14 text-center header-subtitle">' +
            '<svg class="w-10 h-10 mx-auto mb-3 opacity-25" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>' +
            '<p class="text-sm">Tidak ada data untuk periode ini</p></td></tr>';
        tfoot.style.display = 'none';
        pBox.style.display  = 'none';
        return;
    }

    var s    = (curPage - 1) * PER;
    var e    = Math.min(s + PER, flatRows.length);
    var page = flatRows.slice(s, e);

    var totT = 0, totS = 0, totP = 0, totM = 0;
    flatRows.forEach(function(r) { totT += r.total; totS += r.selesai; totP += r.progress; totM += r.menunggu; });

    var html = '';
    page.forEach(function(r) {
        var p      = pctN(r.selesai, r.total);
        var pBadge = '<span class="inline-block ' + badgeCls(p) + ' px-2.5 py-1 rounded-lg font-semibold text-xs">' + p + '%</span>';
        var dotCls = r.katNama ? katColor(r.katNama) : '';
        var katCell = r.katNama !== null
            ? '<span class="flex items-center gap-1.5 font-semibold header-title text-sm">' +
              '<span class="w-2 h-2 rounded-full flex-shrink-0 ' + dotCls + '"></span>' +
              r.katNama + '</span>'
            : '';

        html += '<tr class="rekap-row border-b rekap-border transition-colors">' +
            '<td class="px-5 py-3 header-subtitle text-xs align-middle">' + (r.rowNo !== null ? r.rowNo : '') + '</td>' +
            '<td class="px-5 py-3 align-middle">' + katCell + '</td>' +
            '<td class="px-5 py-3 header-subtitle text-sm align-middle">' + r.sub + '</td>' +
            '<td class="px-5 py-3 text-center align-middle"><span class="inline-block bg-cyan-500/20 text-cyan-400 px-2.5 py-1 rounded-lg font-semibold text-xs">' + r.total + '</span></td>' +
            '<td class="px-5 py-3 text-center align-middle"><span class="inline-block bg-emerald-500/20 text-emerald-400 px-2.5 py-1 rounded-lg font-medium text-xs">' + r.selesai + '</span></td>' +
            '<td class="px-5 py-3 text-center align-middle"><span class="inline-block bg-yellow-500/20 text-yellow-400 px-2.5 py-1 rounded-lg font-medium text-xs">' + r.progress + '</span></td>' +
            '<td class="px-5 py-3 text-center align-middle"><span class="inline-block bg-red-500/20 text-red-400 px-2.5 py-1 rounded-lg font-medium text-xs">' + r.menunggu + '</span></td>' +
            '<td class="px-5 py-3 text-center align-middle">' + pBadge + '</td>' +
            '</tr>';
    });
    tbody.innerHTML = html;

    var op = pctN(totS, totT);
    document.getElementById('footTotal').innerText    = totT;
    document.getElementById('footSelesai').innerText  = totS;
    document.getElementById('footProgress').innerText = totP;
    document.getElementById('footMenunggu').innerText = totM;
    var fp = document.getElementById('footPct');
    fp.innerText = op + '%';
    fp.className = 'inline-block ' + badgeCls(op) + ' px-3 py-1 rounded-lg font-bold text-sm';
    tfoot.style.display = '';

    document.getElementById('showingStart').innerText = s + 1;
    document.getElementById('showingEnd').innerText   = e;
    document.getElementById('totalData').innerText    = flatRows.length;
    pBox.style.display = flatRows.length > PER ? 'flex' : 'none';

    renderPageNums();
}

function renderPageNums() {
    var total = Math.ceil(flatRows.length / PER);
    document.getElementById('btnFirst').disabled = curPage === 1;
    document.getElementById('btnPrev').disabled  = curPage === 1;
    document.getElementById('btnNext').disabled  = curPage === total;
    document.getElementById('btnLast').disabled  = curPage === total;

    var pn = document.getElementById('pageNumbers');
    pn.innerHTML = '';
    var max = 5;
    var s   = Math.max(1, curPage - 2);
    var e   = Math.min(total, s + max - 1);
    if (e - s < max - 1) s = Math.max(1, e - max + 1);
    for (var i = s; i <= e; i++) {
        (function(pg) {
            var b = document.createElement('button');
            b.className = pg === curPage
                ? 'px-3.5 py-2 rounded-lg text-sm font-semibold bg-gradient-to-r from-cyan-600 to-blue-600 text-white'
                : 'pag-btn px-3.5 py-2 rounded-lg text-sm font-medium transition-all';
            b.innerText = pg;
            b.onclick   = function() { curPage = pg; renderTable(); window.scrollTo({top:0,behavior:'smooth'}); };
            pn.appendChild(b);
        })(i);
    }
}

document.getElementById('btnFirst').onclick = function() { curPage = 1; renderTable(); };
document.getElementById('btnPrev').onclick  = function() { if(curPage>1){curPage--;renderTable();} };
document.getElementById('btnNext').onclick  = function() { var t=Math.ceil(flatRows.length/PER); if(curPage<t){curPage++;renderTable();} };
document.getElementById('btnLast').onclick  = function() { curPage=Math.ceil(flatRows.length/PER); renderTable(); };

function loadRekap() {
    var bulan = document.getElementById('bulan').value;
    var ys    = parseInt(document.getElementById('tahun_start').value);
    var ye    = parseInt(document.getElementById('tahun_end').value);

    if (ys > ye) {
        document.getElementById('tbodyRekap').innerHTML =
            '<tr><td colspan="8" class="px-5 py-8 text-center text-red-400 text-sm font-medium">Tahun mulai tidak boleh lebih besar dari tahun akhir</td></tr>';
        return;
    }

    var bulanEl  = document.getElementById('bulan');
    var bulanTxt = bulanEl.options[bulanEl.selectedIndex].text;
    document.getElementById('periodeText').innerText =
        bulan === 'all'
            ? (ys === ye ? 'Tahun ' + ys : ys + ' \u2013 ' + ye)
            : bulanTxt + ' ' + ys;

    var qs = 'bulan=' + bulan + '&tahun_start=' + ys + '&tahun_end=' + ye;
    document.getElementById('btnExcel').href = "<?= site_url('rekap/export_excel') ?>?" + qs;
    document.getElementById('btnPdf').href   = "<?= site_url('rekap/export_pdf') ?>?"   + qs;

    document.getElementById('tbodyRekap').innerHTML =
        '<tr><td colspan="8" class="px-5 py-14 text-center header-subtitle">' +
        '<div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-slate-700 border-t-cyan-500 mb-3"></div>' +
        '<p class="text-sm">Memuat data...</p></td></tr>';
    document.getElementById('tfootRekap').style.display = 'none';
    document.getElementById('paginationBox').style.display = 'none';
    document.getElementById('tableInfo').style.display = 'none';

    fetch("<?= site_url('rekap/ajax_rekap') ?>", {
        method : 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body   : qs
    })
    .then(function(r) { return r.json(); })
    .then(function(res) {
        var kategori = res.kategori || {};
        var total    = res.total    || 0;

        var totS = 0, totP = 0, totM = 0;
        Object.values(kategori).forEach(function(k) {
            (k.sub || []).forEach(function(s) {
                totS += s.selesai  || 0;
                totP += s.progress || 0;
                totM += s.menunggu || 0;
            });
            if (!(k.sub && k.sub.length)) {
                totS += k.selesai  || 0;
                totP += k.progress || 0;
                totM += k.menunggu || 0;
            }
        });

        updateCards(total, totS, totP, totM);

        katColorMap = {}; katColorIdx = 0;
        flatRows = buildRows(kategori);
        curPage  = 1;
        renderTable();

        var infoEl = document.getElementById('tableInfo');
        document.getElementById('tableInfoText').innerText = flatRows.length;
        infoEl.style.display = '';
    })
    .catch(function(err) {
        console.error(err);
        document.getElementById('tbodyRekap').innerHTML =
            '<tr><td colspan="8" class="px-5 py-8 text-center text-red-400 text-sm">Gagal memuat data. Silakan coba lagi.</td></tr>';
    });
}

document.getElementById('btnFilter').onclick = loadRekap;
document.getElementById('btnPrint').onclick   = function() { window.print(); };

loadRekap();
</script>

<style>
@keyframes spin { to { transform: rotate(360deg); } }
.animate-spin { animation: spin 1s linear infinite; }

/* ── DARK (default) ── */
.rekap-card-head {
    background: rgba(15,23,42,0.9);
    border-color: rgba(51,65,85,0.5);
}
.rekap-thead {
    background: rgba(15,23,42,0.85);
    border-bottom: 1px solid rgba(51,65,85,0.5);
}
.rekap-tfoot tr {
    background: rgba(15,23,42,0.95);
    border-top: 2px solid rgba(6,182,212,0.3);
}
.rekap-border { border-color: rgba(51,65,85,0.35); }
.rekap-row:hover { background: rgba(51,65,85,0.35) !important; }
.pag-btn { background: rgba(30,41,59,0.8); color: #94a3b8; }
.pag-btn:hover:not(:disabled) { background: rgba(51,65,85,0.8); color: #f1f5f9; }

/* ── LIGHT ── */
html.light .stat-card {
    background: #ffffff !important;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
}
html.light .stat-card,
html.light .stat-card *,
html.light .stat-card p,
html.light .stat-card h2,
html.light .stat-card svg {
    color: #1e293b !important;
    opacity: 1 !important;
}
html.light .filter-select {
    background: #ffffff !important;
    color: #0f172a !important;
    border-color: #cbd5e1 !important;
}
html.light .rekap-card-head {
    background: #f1f5f9 !important;
    border-color: #e2e8f0 !important;
}
html.light .rekap-thead {
    background: #f8fafc !important;
    border-bottom-color: #e2e8f0 !important;
}
html.light .rekap-thead th { color: #475569 !important; }
html.light #tbodyRekap tr { background: #ffffff !important; }
html.light .rekap-row:hover { background: #f8fafc !important; }
html.light .rekap-border { border-color: #e2e8f0 !important; }
html.light .rekap-tfoot tr {
    background: #f1f5f9 !important;
    border-top-color: rgba(6,182,212,0.3) !important;
}
html.light .pag-btn {
    background: #ffffff !important;
    color: #475569 !important;
    border: 1px solid #e2e8f0 !important;
}
html.light .pag-btn:hover:not(:disabled) {
    background: #f1f5f9 !important;
    color: #0f172a !important;
}

@media print {
    body * { visibility: hidden; }
    #tableRekap, #tableRekap * { visibility: visible; }
    #tableRekap { position: absolute; left: 0; top: 0; width: 100%; }
}
</style>