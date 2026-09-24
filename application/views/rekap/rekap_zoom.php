<?php
// File: application/views/rekap/rekap_zoom.php
?>

<div class="mb-8">
    <div class="flex items-center gap-3 mb-2">
        <div class="w-1 h-8 bg-gradient-to-b from-cyan-400 to-blue-600 rounded-full"></div>
        <h1 class="text-3xl font-bold header-title">Rekap Layanan Zoom Meeting</h1>
    </div>
    <p class="header-subtitle ml-7">Generate laporan bulanan atau tahunan sesi Zoom BPS Provinsi Riau</p>
</div>

<!-- FILTER FORM -->
<div class="theme-card border rounded-2xl p-6 mb-8">
    <h2 class="text-base font-bold header-title mb-5 flex items-center gap-2">
        <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
        </svg>
        Filter Periode
    </h2>

    <form id="formFilterZoom" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
            <label class="text-xs text-gray-400 font-semibold mb-1.5 block">Tampilkan Per</label>
            <select id="jenis_periode" name="jenis_periode" onchange="togglePeriode()"
                    class="w-full bg-slate-800/80 border border-slate-600 rounded-xl px-4 py-2.5 text-sm header-title
                           focus:outline-none focus:ring-2 focus:ring-cyan-500/50 transition-all">
                <option value="bulanan">Per Bulan</option>
                <option value="tahunan">Per Tahun</option>
            </select>
        </div>
        <div id="wrapBulan">
            <label class="text-xs text-gray-400 font-semibold mb-1.5 block">Bulan</label>
            <select id="bulan" name="bulan"
                    class="w-full bg-slate-800/80 border border-slate-600 rounded-xl px-4 py-2.5 text-sm header-title
                           focus:outline-none focus:ring-2 focus:ring-cyan-500/50 transition-all">
                <?php
                $bulan_list = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
                               7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
                foreach ($bulan_list as $num => $nama):
                ?>
                <option value="<?= $num ?>" <?= $num == date('n') ? 'selected' : '' ?>><?= $nama ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="text-xs text-gray-400 font-semibold mb-1.5 block">Tahun</label>
            <select id="tahun" name="tahun"
                    class="w-full bg-slate-800/80 border border-slate-600 rounded-xl px-4 py-2.5 text-sm header-title
                           focus:outline-none focus:ring-2 focus:ring-cyan-500/50 transition-all">
                <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                <option value="<?= $y ?>" <?= $y == date('Y') ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
        </div>
    </form>

    <div class="flex gap-3 mt-5">
        <button onclick="loadRekapZoom()"
                class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-cyan-600 to-blue-600
                       hover:from-cyan-500 hover:to-blue-500 text-white text-sm font-semibold
                       shadow-lg shadow-cyan-500/20 transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Tampilkan Data
        </button>
        <button onclick="cetakPDF()" id="btnCetak" disabled
                class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-red-600 to-red-500
                       hover:from-red-500 hover:to-red-400 text-white text-sm font-semibold
                       shadow-lg shadow-red-500/20 transition-all flex items-center gap-2
                       disabled:opacity-40 disabled:cursor-not-allowed">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Cetak PDF
        </button>
    </div>
</div>

<!-- LOADING -->
<div id="loadingZoom" class="hidden text-center py-16">
    <div class="inline-flex items-center gap-3 text-cyan-400">
        <svg class="w-6 h-6 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
        </svg>
        <span class="text-sm font-medium">Memuat data...</span>
    </div>
</div>

<!-- HASIL REKAP -->
<div id="hasilRekap" class="hidden">

    <!-- INFO PERIODE -->
    <div class="theme-card border border-cyan-500/20 rounded-2xl p-4 mb-6 flex items-center gap-3">
        <div class="w-10 h-10 bg-cyan-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <div class="text-xs text-cyan-400 font-semibold uppercase tracking-wider">Periode</div>
            <div class="text-base font-bold header-title" id="labelPeriode">-</div>
        </div>
    </div>

    <!-- STAT CARDS -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="theme-card border border-blue-500/20 rounded-2xl p-4 text-center">
            <div class="text-xs text-blue-400 font-semibold uppercase mb-1">Total Sesi</div>
            <div class="text-3xl font-bold header-title" id="statTotal">0</div>
        </div>
        <div class="theme-card border border-green-500/20 rounded-2xl p-4 text-center">
            <div class="text-xs text-green-400 font-semibold uppercase mb-1">Selesai</div>
            <div class="text-3xl font-bold header-title" id="statSelesai">0</div>
        </div>
        <div class="theme-card border border-yellow-500/20 rounded-2xl p-4 text-center">
            <div class="text-xs text-yellow-400 font-semibold uppercase mb-1">On Progress</div>
            <div class="text-3xl font-bold header-title" id="statProgress">0</div>
        </div>
        <div class="theme-card border border-gray-500/20 rounded-2xl p-4 text-center">
            <div class="text-xs text-gray-400 font-semibold uppercase mb-1">Menunggu</div>
            <div class="text-3xl font-bold header-title" id="statMenunggu">0</div>
        </div>
    </div>

    <!-- TABEL DATA -->
    <div class="theme-card border rounded-2xl overflow-hidden mb-6">
        <div class="px-5 py-4 border-b flex items-center gap-2">
            <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            <span class="text-sm font-bold header-title" id="labelTabel">Detail Sesi Zoom</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full" id="mainTable">
                <thead>
                    <tr class="border-b" id="theadRow">
                        <!-- diisi JS -->
                    </tr>
                </thead>
                <tbody id="tabelZoom">
                    <tr><td colspan="10" class="text-center py-8 header-subtitle" style="font-size:11px;">Belum ada data</td></tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<style>
#mainTable {
    min-width: 600px;
    table-layout: fixed;
    font-size: 11px;
}

/* Mode detail (bulanan) — 10 kolom */
#mainTable.mode-detail th:nth-child(1)  { width: 28px; }
#mainTable.mode-detail th:nth-child(2)  { width: 90px; }
#mainTable.mode-detail th:nth-child(3)  { width: 180px; }
#mainTable.mode-detail th:nth-child(4)  { width: 70px; }
#mainTable.mode-detail th:nth-child(5)  { width: 90px; }
#mainTable.mode-detail th:nth-child(6)  { width: 46px; }
#mainTable.mode-detail th:nth-child(7)  { width: 46px; }
#mainTable.mode-detail th:nth-child(8)  { width: 52px; }
#mainTable.mode-detail th:nth-child(9)  { width: 40px; }
#mainTable.mode-detail th:nth-child(10) { width: 68px; }

/* Mode bulanan (tahunan) — 9 kolom */
#mainTable.mode-bulanan th:nth-child(1) { width: 32px; }
#mainTable.mode-bulanan th:nth-child(2) { width: 120px; }
#mainTable.mode-bulanan th:nth-child(3) { width: 80px; }
#mainTable.mode-bulanan th:nth-child(4) { width: 70px; }
#mainTable.mode-bulanan th:nth-child(5) { width: 80px; }
#mainTable.mode-bulanan th:nth-child(6) { width: 70px; }
#mainTable.mode-bulanan th:nth-child(7) { width: 90px; }
#mainTable.mode-bulanan th:nth-child(8) { width: 60px; }
#mainTable.mode-bulanan th:nth-child(9) { width: 60px; }

.badge-zoom, .badge-status {
    display: inline-block;
    white-space: nowrap;
    line-height: 1.4;
}
</style>

<script>
const BULAN_FULL  = ['Januari','Februari','Maret','April','Mei','Juni',
                     'Juli','Agustus','September','Oktober','November','Desember'];

function togglePeriode() {
    const jenis = document.getElementById('jenis_periode').value;
    document.getElementById('wrapBulan').style.display = jenis === 'bulanan' ? '' : 'none';
}

/* ═══════════════════════════════
   LOAD DATA UTAMA
═══════════════════════════════ */
function loadRekapZoom() {
    const jenis = document.getElementById('jenis_periode').value;
    const bulan = document.getElementById('bulan').value;
    const tahun = document.getElementById('tahun').value;

    document.getElementById('loadingZoom').classList.remove('hidden');
    document.getElementById('hasilRekap').classList.add('hidden');
    document.getElementById('btnCetak').disabled = true;

    const params = new URLSearchParams({ jenis_periode: jenis, bulan, tahun });

    fetch('<?= base_url("rekap_zoom/ajax_data") ?>?' + params.toString(), {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('loadingZoom').classList.add('hidden');

        if (data.status !== 'success') {
            Swal.fire('Error', data.message || 'Gagal memuat data', 'error');
            return;
        }

        document.getElementById('labelPeriode').textContent = data.periode;
        document.getElementById('statTotal').textContent    = data.stat.total;
        document.getElementById('statSelesai').textContent  = data.stat.selesai;
        document.getElementById('statProgress').textContent = data.stat.progress;
        document.getElementById('statMenunggu').textContent = data.stat.menunggu;

        const thead = document.getElementById('theadRow');
        const tbody = document.getElementById('tabelZoom');
        const table = document.getElementById('mainTable');

        if (!data.rows || data.rows.length === 0) {
            // Mode detail, tabel kosong
            table.className = 'w-full mode-detail';
            document.getElementById('labelTabel').textContent = 'Detail Sesi Zoom';
            renderHeaderDetail(thead);
            tbody.innerHTML = `<tr><td colspan="10" class="text-center py-8 header-subtitle" style="font-size:11px;">Tidak ada data pada periode ini</td></tr>`;
        } else if (jenis === 'tahunan') {
            table.className = 'w-full mode-bulanan';
            document.getElementById('labelTabel').textContent = 'Ringkasan Per Bulan — Tahun ' + tahun;
            renderHeaderBulanan(thead);
            renderRowsBulanan(tbody, data.rows);
        } else {
            table.className = 'w-full mode-detail';
            document.getElementById('labelTabel').textContent = 'Detail Sesi Zoom';
            renderHeaderDetail(thead);
            renderRowsDetail(tbody, data.rows);
        }

        document.getElementById('hasilRekap').classList.remove('hidden');
        document.getElementById('btnCetak').disabled = false;
    })
    .catch(() => {
        document.getElementById('loadingZoom').classList.add('hidden');
        Swal.fire('Error', 'Gagal menghubungi server', 'error');
    });
}

/* ═══════════════════════════════
   RENDER HEADER
═══════════════════════════════ */
function renderHeaderDetail(thead) {
    thead.innerHTML = `
        <th class="px-2 py-2 text-center font-semibold header-subtitle uppercase" style="font-size:10px;">No</th>
        <th class="px-2 py-2 text-left   font-semibold header-subtitle uppercase" style="font-size:10px;">Kode Tiket</th>
        <th class="px-2 py-2 text-left   font-semibold header-subtitle uppercase" style="font-size:10px;">Detail Kegiatan</th>
        <th class="px-2 py-2 text-left   font-semibold header-subtitle uppercase" style="font-size:10px;">Pelapor</th>
        <th class="px-2 py-2 text-center font-semibold header-subtitle uppercase" style="font-size:10px;">Tgl & Jam</th>
        <th class="px-2 py-2 text-center font-semibold header-subtitle uppercase" style="font-size:10px;">Durasi</th>
        <th class="px-2 py-2 text-center font-semibold header-subtitle uppercase" style="font-size:10px;">Peserta</th>
        <th class="px-2 py-2 text-center font-semibold header-subtitle uppercase" style="font-size:10px;">Breakout</th>
        <th class="px-2 py-2 text-center font-semibold header-subtitle uppercase" style="font-size:10px;">Akun</th>
        <th class="px-2 py-2 text-center font-semibold header-subtitle uppercase" style="font-size:10px;">Status</th>
    `;
}

function renderHeaderBulanan(thead) {
    thead.innerHTML = `
        <th class="px-2 py-2 text-center font-semibold header-subtitle uppercase" style="font-size:10px;">No</th>
        <th class="px-2 py-2 text-left   font-semibold header-subtitle uppercase" style="font-size:10px;">Bulan</th>
        <th class="px-2 py-2 text-center font-semibold header-subtitle uppercase" style="font-size:10px;">Total Sesi</th>
        <th class="px-2 py-2 text-center font-semibold header-subtitle uppercase" style="font-size:10px;">Selesai</th>
        <th class="px-2 py-2 text-center font-semibold header-subtitle uppercase" style="font-size:10px;">On Progress</th>
        <th class="px-2 py-2 text-center font-semibold header-subtitle uppercase" style="font-size:10px;">Menunggu</th>
        <th class="px-2 py-2 text-center font-semibold header-subtitle uppercase" style="font-size:10px;">Total Peserta</th>
        <th class="px-2 py-2 text-center font-semibold header-subtitle uppercase" style="font-size:10px;">Zoom 1</th>
        <th class="px-2 py-2 text-center font-semibold header-subtitle uppercase" style="font-size:10px;">Zoom 2</th>
    `;
}

/* ═══════════════════════════════
   RENDER ROWS — MODE DETAIL
═══════════════════════════════ */
function renderRowsDetail(tbody, rows) {
    tbody.innerHTML = rows.map((r, i) => {

        const statusCls = r.status === 'Selesai'
            ? 'bg-green-500/20 text-green-400 border border-green-500/30'
            : r.status === 'On Progress'
                ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30'
                : 'bg-gray-500/20 text-gray-400 border border-gray-500/30';

        const akunCls = (r.akun_zoom == 2)
            ? 'bg-purple-500/20 text-purple-300 border border-purple-500/30'
            : 'bg-indigo-500/20 text-indigo-300 border border-indigo-500/30';

        const tglJamHtml = (() => {
            const tgl = r.tanggal_label
                ? r.tanggal_label.replace(' s.d. ', '<br><span class="text-cyan-400" style="font-size:9px;">s.d.</span><br>')
                : '-';
            const jam = r.jam ? `<div class="text-cyan-400/70" style="font-size:10px;">${r.jam}</div>` : '';
            return tgl + jam;
        })();

        return `<tr class="border-b border-slate-800/50 hover:bg-slate-800/20 transition-colors">
            <td class="px-2 py-1.5 text-center header-subtitle">${i + 1}</td>
            <td class="px-2 py-1.5">
                <span class="font-mono text-cyan-400 break-all leading-snug" style="font-size:10px;">${r.kode_tiket}</span>
            </td>
            <td class="px-2 py-1.5 header-title">
                <div class="line-clamp-2" title="${r.detail_permintaan || ''}">${r.detail_permintaan || '-'}</div>
            </td>
            <td class="px-2 py-1.5 header-title">${r.pelapor || '-'}</td>
            <td class="px-2 py-1.5 text-center header-subtitle leading-snug">${tglJamHtml}</td>
            <td class="px-2 py-1.5 text-center header-subtitle" style="white-space:nowrap;">${r.durasi ? r.durasi + ' jam' : '-'}</td>
            <td class="px-2 py-1.5 text-center header-subtitle">${r.jumlah_peserta || '-'}</td>
            <td class="px-2 py-1.5 text-center header-subtitle">${r.jumlah_breakout ? r.jumlah_breakout + ' rm' : '-'}</td>
            <td class="px-2 py-1.5 text-center">
                <span class="badge-zoom px-1.5 py-0.5 rounded-lg font-semibold ${akunCls}" style="font-size:10px;">Z${r.akun_zoom || '-'}</span>
            </td>
            <td class="px-2 py-1.5 text-center">
                <span class="badge-status px-1.5 py-0.5 rounded-full font-semibold ${statusCls}" style="font-size:10px;">${r.status}</span>
            </td>
        </tr>`;
    }).join('');
}

/* ═══════════════════════════════
   RENDER ROWS — MODE BULANAN (TAHUNAN)
═══════════════════════════════ */
function renderRowsBulanan(tbody, rows) {
    // Inisialisasi 12 bulan kosong
    const map = {};
    for (let m = 1; m <= 12; m++) {
        map[m] = { total: 0, selesai: 0, progress: 0, menunggu: 0, peserta: 0, zoom1: 0, zoom2: 0 };
    }

    // Kelompokkan rows ke bulan berdasarkan t.tanggal
    rows.forEach(r => {
        if (!r.tanggal) return;
        const m = parseInt(r.tanggal.split('-')[1], 10);
        if (!map[m]) return;
        map[m].total++;
        if (r.status === 'Selesai')     map[m].selesai++;
        if (r.status === 'On Progress') map[m].progress++;
        if (r.status === 'Menunggu')    map[m].menunggu++;
        map[m].peserta += parseInt(r.jumlah_peserta || 0, 10);
        if (r.akun_zoom == 1) map[m].zoom1++;
        if (r.akun_zoom == 2) map[m].zoom2++;
    });

    let html  = '';
    let no    = 1;
    let total = 0, totSelesai = 0, totProgress = 0, totMenunggu = 0, totPeserta = 0, totZ1 = 0, totZ2 = 0;
    let adaData = false;

    for (let m = 1; m <= 12; m++) {
        const d = map[m];
        if (d.total === 0) continue;
        adaData = true;

        total      += d.total;
        totSelesai += d.selesai;
        totProgress+= d.progress;
        totMenunggu+= d.menunggu;
        totPeserta += d.peserta;
        totZ1      += d.zoom1;
        totZ2      += d.zoom2;

        html += `<tr class="border-b border-slate-800/50 hover:bg-slate-800/20 transition-colors">
            <td class="px-2 py-2.5 text-center header-subtitle">${no++}</td>
            <td class="px-2 py-2.5 header-title font-semibold">${BULAN_FULL[m - 1]}</td>
            <td class="px-2 py-2.5 text-center">
                <span class="font-bold text-cyan-400" style="font-size:14px;">${d.total}</span>
            </td>
            <td class="px-2 py-2.5 text-center">
                <span class="badge-status px-2 py-0.5 rounded-full font-semibold
                             bg-green-500/20 text-green-400 border border-green-500/30"
                      style="font-size:10px;">${d.selesai}</span>
            </td>
            <td class="px-2 py-2.5 text-center">
                <span class="badge-status px-2 py-0.5 rounded-full font-semibold
                             bg-yellow-500/20 text-yellow-400 border border-yellow-500/30"
                      style="font-size:10px;">${d.progress}</span>
            </td>
            <td class="px-2 py-2.5 text-center">
                <span class="badge-status px-2 py-0.5 rounded-full font-semibold
                             bg-gray-500/20 text-gray-400 border border-gray-500/30"
                      style="font-size:10px;">${d.menunggu}</span>
            </td>
            <td class="px-2 py-2.5 text-center header-subtitle">
                ${d.peserta > 0 ? d.peserta + ' org' : '-'}
            </td>
            <td class="px-2 py-2.5 text-center">
                <span class="badge-zoom px-1.5 py-0.5 rounded-lg font-semibold
                             bg-indigo-500/20 text-indigo-300 border border-indigo-500/30"
                      style="font-size:10px;">${d.zoom1} sesi</span>
            </td>
            <td class="px-2 py-2.5 text-center">
                <span class="badge-zoom px-1.5 py-0.5 rounded-lg font-semibold
                             bg-purple-500/20 text-purple-300 border border-purple-500/30"
                      style="font-size:10px;">${d.zoom2} sesi</span>
            </td>
        </tr>`;
    }

    // Baris total
    if (adaData) {
        html += `<tr class="border-t-2 border-cyan-500/30 bg-cyan-500/5">
            <td colspan="2" class="px-2 py-2.5 text-right font-bold header-title" style="font-size:11px;">TOTAL</td>
            <td class="px-2 py-2.5 text-center font-bold text-cyan-400" style="font-size:14px;">${total}</td>
            <td class="px-2 py-2.5 text-center font-bold text-green-400">${totSelesai}</td>
            <td class="px-2 py-2.5 text-center font-bold text-yellow-400">${totProgress}</td>
            <td class="px-2 py-2.5 text-center font-bold text-gray-400">${totMenunggu}</td>
            <td class="px-2 py-2.5 text-center font-bold header-title">${totPeserta > 0 ? totPeserta + ' org' : '-'}</td>
            <td class="px-2 py-2.5 text-center font-bold text-indigo-300">${totZ1} sesi</td>
            <td class="px-2 py-2.5 text-center font-bold text-purple-300">${totZ2} sesi</td>
        </tr>`;
    } else {
        html = `<tr><td colspan="9" class="text-center py-8 header-subtitle" style="font-size:11px;">Tidak ada data pada tahun ini</td></tr>`;
    }

    tbody.innerHTML = html;
}

/* ═══════════════════════════════
   CETAK PDF
═══════════════════════════════ */
function cetakPDF() {
    const jenis = document.getElementById('jenis_periode').value;
    const bulan = document.getElementById('bulan').value;
    const tahun = document.getElementById('tahun').value;
    window.open(
        '<?= base_url("rekap_zoom/generate_pdf") ?>?jenis_periode=' + jenis + '&bulan=' + bulan + '&tahun=' + tahun,
        '_blank'
    );
}

togglePeriode();
</script>