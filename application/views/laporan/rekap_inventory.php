<!-- application/views/laporan/rekap_inventory.php -->

<!-- HEADER -->
<div class="mb-8">
    <h1 class="text-4xl font-bold header-title mb-2">Rekap Inventory</h1>
    <p class="header-subtitle text-sm">
        Ringkasan Aset TI &bull; <span id="periodeText">Real-time</span>
    </p>
</div>

<!-- FILTER CARD -->
<div class="theme-card rounded-2xl p-6 mb-8 border">
    <div class="flex items-center gap-2 mb-5">
        <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
        </svg>
        <h2 class="text-lg font-semibold header-title">Filter &amp; Tampilan</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- JENIS LAPORAN -->
        <div>
            <label class="block text-xs font-medium header-subtitle mb-2">Jenis Laporan</label>
            <select id="jenisLaporan" class="filter-select w-full bg-slate-800/80 border border-slate-700/50
                           px-4 py-2.5 rounded-lg text-sm text-white
                           focus:outline-none focus:ring-2 focus:ring-cyan-500 transition-all">
                <option value="kondisi_barang">Berdasarkan Kondisi Barang</option>
                <option value="jenis_bmn">Berdasarkan Jenis BMN</option>
                <option value="ruangan">Berdasarkan Ruangan</option>
                <option value="tahun_perolehan">Berdasarkan Tahun Perolehan</option>
                <option value="pemeliharaan">Pemeliharaan Aset</option>
            </select>
        </div>

        <!-- KONDISI -->
        <div>
            <label class="block text-xs font-medium header-subtitle mb-2">Filter Kondisi</label>
            <select id="filterKondisi" class="filter-select w-full bg-slate-800/80 border border-slate-700/50
                           px-4 py-2.5 rounded-lg text-sm text-white
                           focus:outline-none focus:ring-2 focus:ring-cyan-500 transition-all">
                <option value="all">Semua Kondisi</option>
                <option value="baik">Baik</option>
                <option value="rusak_ringan">Rusak Ringan</option>
                <option value="rusak_berat">Rusak Berat</option>
            </select>
        </div>

        <!-- TAHUN -->
        <div>
            <label class="block text-xs font-medium header-subtitle mb-2">Tahun Perolehan</label>
            <select id="filterTahun" class="filter-select w-full bg-slate-800/80 border border-slate-700/50
                           px-4 py-2.5 rounded-lg text-sm text-white
                           focus:outline-none focus:ring-2 focus:ring-cyan-500 transition-all">
                <option value="all">Semua Tahun</option>
                <option value="2024">2024</option>
                <option value="2023">2023</option>
                <option value="2022">2022</option>
                <option value="2021">2021</option>
                <option value="2020">2020</option>
            </select>
        </div>

        <!-- TOMBOL FILTER -->
        <div class="flex items-end">
            <button id="btnFilter"
                    class="w-full bg-gradient-to-r from-cyan-600 to-blue-600
                           hover:from-cyan-500 hover:to-blue-500
                           px-4 py-2.5 rounded-lg text-sm font-medium text-white
                           transition-all transform hover:scale-105 active:scale-95 shadow-lg">
                <span class="flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Refresh Data
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
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <p class="text-xs font-medium text-cyan-100">Total Aset</p>
            </div>
            <h2 id="totalAset" class="text-4xl font-bold text-white mt-1">0</h2>
            <p class="text-cyan-100 text-xs mt-2 opacity-80">Item Inventaris</p>
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
                <p class="text-xs font-medium text-emerald-100">Kondisi Baik</p>
            </div>
            <h2 id="totalBaik" class="text-4xl font-bold text-white mt-1">0</h2>
            <p id="persenBaik" class="text-emerald-100 text-xs mt-2 opacity-80">0% dari total</p>
        </div>
    </div>

    <div class="stat-card bg-gradient-to-br from-yellow-500 to-orange-600
                p-6 rounded-2xl shadow-lg relative overflow-hidden border border-yellow-500/20">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white opacity-5 rounded-full -mr-12 -mt-12"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-yellow-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <p class="text-xs font-medium text-yellow-100">Rusak Ringan</p>
            </div>
            <h2 id="totalRusakRingan" class="text-4xl font-bold text-white mt-1">0</h2>
            <p id="persenRusakRingan" class="text-yellow-100 text-xs mt-2 opacity-80">0% dari total</p>
        </div>
    </div>

    <div class="stat-card bg-gradient-to-br from-red-600 to-rose-700
                p-6 rounded-2xl shadow-lg relative overflow-hidden border border-red-500/20">
        <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full -ml-10 -mb-10"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-red-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-xs font-medium text-red-100">Rusak Berat</p>
            </div>
            <h2 id="totalRusakBerat" class="text-4xl font-bold text-white mt-1">0</h2>
            <p id="persenRusakBerat" class="text-red-100 text-xs mt-2 opacity-80">0% dari total</p>
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

<!-- RESULT TABLE -->
<div id="rekapBox" class="theme-card rounded-2xl overflow-hidden border shadow-lg mb-6">

    <!-- Table header (judul dinamis diisi JS) -->
    <div id="rekapBoxHead" class="px-6 py-4 border-b rekap-card-head flex items-center justify-between">
        <h3 id="rekapBoxTitle" class="font-semibold text-base header-title flex items-center gap-2">
            <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Rekap Inventory
        </h3>
        <span id="tableInfo" class="text-xs header-subtitle" style="display:none">
            <span id="tableInfoText"></span> baris
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead id="rekapThead" class="rekap-thead"></thead>
            <tbody id="rekapTbody">
                <tr>
                    <td colspan="7" class="px-5 py-14 text-center header-subtitle">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-slate-700 border-t-cyan-500 mb-3"></div>
                        <p class="text-sm">Memuat data...</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- PAGINATION -->
<div id="paginationBox" class="mt-4 flex flex-col md:flex-row items-center justify-between
     theme-card rounded-2xl p-5 border gap-4" style="display:none">
    <span class="header-subtitle text-sm">
        Baris <span id="showingStart" class="header-title font-semibold">1</span>–<span
               id="showingEnd" class="header-title font-semibold">10</span>
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
var allData      = [];
var currentPage  = 1;
var itemsPerPage = 10;
var currentJenis = 'kondisi_barang';

/* ── Judul tabel per jenis ── */
var JENIS_TITLE = {
    kondisi_barang  : 'Aset Berdasarkan Kondisi Barang',
    jenis_bmn       : 'Aset Berdasarkan Jenis BMN',
    ruangan         : 'Aset Berdasarkan Ruangan',
    tahun_perolehan : 'Aset Berdasarkan Tahun Perolehan',
    pemeliharaan    : 'Pemeliharaan Aset TI'
};

/* ── Animasi angka ── */
function animateValue(el, start, end, dur) {
    var range = end - start;
    var step  = range / (dur / 16);
    var cur   = start;
    var t = setInterval(function() {
        cur += step;
        var done = step >= 0 ? cur >= end : cur <= end;
        el.innerText = done ? end : Math.floor(cur);
        if (done) clearInterval(t);
    }, 16);
}

/* ── Header kolom per jenis ── */
function theadHtml(jenis) {
    var cols = {
        kondisi_barang  : ['No','Nama Barang','Baik','Rusak Ringan','Rusak Berat','Total'],
        jenis_bmn       : ['No','Jenis BMN','Baik','Rusak Ringan','Rusak Berat','Total'],
        ruangan         : ['No','Nama Ruangan','Baik','Rusak Ringan','Rusak Berat','Total'],
        tahun_perolehan : ['No','Nama Barang','Tahun','Baik','Rusak Ringan','Rusak Berat','Total'],
        pemeliharaan    : ['No','Nama Barang','Jumlah Aset','Jumlah Pemeliharaan','Persentase']
    };
    var th = (cols[jenis] || cols['kondisi_barang']).map(function(c, i) {
        var align = i <= 1 ? 'text-left' : 'text-center';
        return '<th class="px-5 py-3 ' + align + ' font-semibold header-subtitle">' + c + '</th>';
    }).join('');
    return '<tr>' + th + '</tr>';
}

/* ── Baris tabel ── */
function tbodyHtml(jenis, data) {
    if (!data.length) {
        return '<tr><td colspan="7" class="px-5 py-14 text-center header-subtitle">' +
            '<svg class="w-10 h-10 mx-auto mb-3 opacity-25" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>' +
            '<p class="text-sm">Tidak ada data untuk filter ini</p></td></tr>';
    }

    var startNum = (currentPage - 1) * itemsPerPage;
    return data.map(function(item, idx) {
        var no = startNum + idx + 1;
        var badge = function(val, cls) {
            return '<span class="inline-block ' + cls + ' px-2.5 py-1 rounded-lg font-medium text-xs">' + (val || 0) + '</span>';
        };

        if (jenis === 'pemeliharaan') {
            var persen = item.jumlah_aset > 0
                ? ((item.jumlah_pemeliharaan / item.jumlah_aset) * 100).toFixed(1)
                : 0;
            return '<tr class="rekap-row border-b rekap-border transition-colors">' +
                '<td class="px-5 py-3 header-subtitle text-xs align-middle">' + no + '</td>' +
                '<td class="px-5 py-3 header-title font-medium align-middle">' + item.nama_barang + '</td>' +
                '<td class="px-5 py-3 text-center align-middle">' + badge(item.jumlah_aset, 'bg-blue-500/20 text-blue-400') + '</td>' +
                '<td class="px-5 py-3 text-center align-middle">' + badge(item.jumlah_pemeliharaan, 'bg-purple-500/20 text-purple-400') + '</td>' +
                '<td class="px-5 py-3 text-center align-middle">' + badge(persen + '%', 'bg-cyan-500/20 text-cyan-400') + '</td>' +
                '</tr>';
        }

        var label = jenis === 'jenis_bmn'  ? item.jenis_bmn
                  : jenis === 'ruangan'    ? item.nama_ruangan
                  : item.nama_barang;

        var tahunCell = jenis === 'tahun_perolehan'
            ? '<td class="px-5 py-3 text-center header-subtitle text-sm align-middle">' + (item.tahun || '-') + '</td>'
            : '';

        return '<tr class="rekap-row border-b rekap-border transition-colors">' +
            '<td class="px-5 py-3 header-subtitle text-xs align-middle">' + no + '</td>' +
            '<td class="px-5 py-3 header-title font-medium align-middle">' + label + '</td>' +
            tahunCell +
            '<td class="px-5 py-3 text-center align-middle">' + badge(item.baik,         'bg-emerald-500/20 text-emerald-400') + '</td>' +
            '<td class="px-5 py-3 text-center align-middle">' + badge(item.rusak_ringan,  'bg-yellow-500/20 text-yellow-400')  + '</td>' +
            '<td class="px-5 py-3 text-center align-middle">' + badge(item.rusak_berat,   'bg-red-500/20 text-red-400')        + '</td>' +
            '<td class="px-5 py-3 text-center align-middle">' + badge(item.total,         'bg-cyan-500/20 text-cyan-400 font-semibold') + '</td>' +
            '</tr>';
    }).join('');
}

/* ── Render halaman ── */
function renderPage() {
    var s    = (currentPage - 1) * itemsPerPage;
    var e    = Math.min(s + itemsPerPage, allData.length);
    var page = allData.slice(s, e);

    document.getElementById('rekapBoxTitle').innerHTML =
        '<svg class="w-5 h-5 text-cyan-400 inline-block mr-1.5 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>' +
        (JENIS_TITLE[currentJenis] || 'Rekap Inventory');

    document.getElementById('rekapThead').innerHTML = theadHtml(currentJenis);
    document.getElementById('rekapTbody').innerHTML = tbodyHtml(currentJenis, page);

    /* pagination info */
    document.getElementById('showingStart').innerText = allData.length ? s + 1 : 0;
    document.getElementById('showingEnd').innerText   = e;
    document.getElementById('totalData').innerText    = allData.length;

    var pBox = document.getElementById('paginationBox');
    pBox.style.display = allData.length > itemsPerPage ? 'flex' : 'none';

    renderPageNums();

    /* table info badge */
    var infoEl = document.getElementById('tableInfo');
    document.getElementById('tableInfoText').innerText = allData.length;
    infoEl.style.display = allData.length ? '' : 'none';
}

/* ── Pagination numbers ── */
function renderPageNums() {
    var total = Math.ceil(allData.length / itemsPerPage);
    document.getElementById('btnFirst').disabled = currentPage === 1;
    document.getElementById('btnPrev').disabled  = currentPage === 1;
    document.getElementById('btnNext').disabled  = currentPage === total;
    document.getElementById('btnLast').disabled  = currentPage === total;

    var pn  = document.getElementById('pageNumbers');
    pn.innerHTML = '';
    var max = 5;
    var s   = Math.max(1, currentPage - 2);
    var e   = Math.min(total, s + max - 1);
    if (e - s < max - 1) s = Math.max(1, e - max + 1);

    for (var i = s; i <= e; i++) {
        (function(pg) {
            var b = document.createElement('button');
            b.className = pg === currentPage
                ? 'px-3.5 py-2 rounded-lg text-sm font-semibold bg-gradient-to-r from-cyan-600 to-blue-600 text-white'
                : 'pag-btn px-3.5 py-2 rounded-lg text-sm font-medium transition-all';
            b.innerText = pg;
            b.onclick   = function() { currentPage = pg; renderPage(); window.scrollTo({top:0,behavior:'smooth'}); };
            pn.appendChild(b);
        })(i);
    }
}

document.getElementById('btnFirst').onclick = function() { currentPage = 1; renderPage(); };
document.getElementById('btnPrev').onclick  = function() { if (currentPage > 1) { currentPage--; renderPage(); } };
document.getElementById('btnNext').onclick  = function() {
    var t = Math.ceil(allData.length / itemsPerPage);
    if (currentPage < t) { currentPage++; renderPage(); }
};
document.getElementById('btnLast').onclick  = function() {
    currentPage = Math.ceil(allData.length / itemsPerPage);
    renderPage();
};

/* ── Load data dari server ── */
function loadRekap() {
    var jenis   = document.getElementById('jenisLaporan').value;
    var kondisi = document.getElementById('filterKondisi').value;
    var tahun   = document.getElementById('filterTahun').value;

    currentJenis = jenis;
    currentPage  = 1;

    var qs = 'jenis=' + jenis + '&kondisi=' + kondisi + '&tahun=' + tahun;

    document.getElementById('btnExcel').href = "<?= site_url('rekap_inventory/export_excel') ?>?" + qs;
    document.getElementById('btnPdf').href   = "<?= site_url('rekap_inventory/export_pdf') ?>?"   + qs;

    /* loading state */
    document.getElementById('rekapTbody').innerHTML =
        '<tr><td colspan="7" class="px-5 py-14 text-center header-subtitle">' +
        '<div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-slate-700 border-t-cyan-500 mb-3"></div>' +
        '<p class="text-sm">Memuat data...</p></td></tr>';
    document.getElementById('paginationBox').style.display = 'none';
    document.getElementById('tableInfo').style.display     = 'none';

    fetch("<?= site_url('rekap_inventory/ajax_rekap') ?>", {
        method : 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body   : qs
    })
    .then(function(r) { return r.json(); })
    .then(function(res) {
        var s = res.summary || {};
        var tot = s.total || 0;

        animateValue(document.getElementById('totalAset'),       0, tot,                800);
        animateValue(document.getElementById('totalBaik'),       0, s.baik        || 0, 800);
        animateValue(document.getElementById('totalRusakRingan'),0, s.rusak_ringan|| 0, 800);
        animateValue(document.getElementById('totalRusakBerat'), 0, s.rusak_berat || 0, 800);

        var t = tot || 1;
        document.getElementById('persenBaik').innerText        = ((s.baik         || 0) / t * 100).toFixed(1) + '% dari total';
        document.getElementById('persenRusakRingan').innerText = ((s.rusak_ringan || 0) / t * 100).toFixed(1) + '% dari total';
        document.getElementById('persenRusakBerat').innerText  = ((s.rusak_berat  || 0) / t * 100).toFixed(1) + '% dari total';

        document.getElementById('periodeText').innerText = res.periode || 'Real-time';

        allData = res.data || [];
        renderPage();
    })
    .catch(function(err) {
        console.error(err);
        document.getElementById('rekapTbody').innerHTML =
            '<tr><td colspan="7" class="px-5 py-8 text-center text-red-400 text-sm">Gagal memuat data. Silakan coba lagi.</td></tr>';
    });
}

document.getElementById('btnFilter').onclick = loadRekap;
document.getElementById('btnPrint').onclick  = function() { window.print(); };

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
html.light #rekapTbody tr { background: #ffffff !important; }
html.light .rekap-row:hover { background: #f8fafc !important; }
html.light .rekap-border { border-color: #e2e8f0 !important; }
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
    #rekapBox, #rekapBox * { visibility: visible; }
    #rekapBox { position: absolute; left: 0; top: 0; width: 100%; }
}
</style>