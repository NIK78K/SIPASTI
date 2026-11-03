<h1 class="text-2xl font-semibold tracking-tight mb-1 header-title">
    Laporan Bulanan Persediaan
</h1>
<p class="text-sm text-gray-400 mb-6 header-subtitle">
    Rekap stok awal, mutasi &amp; stok akhir semua barang per periode
</p>

<!-- ================= FILTER ================= -->
<form method="get" action="<?= base_url('laporan/bulanan_persediaan') ?>" class="flex flex-wrap items-end gap-3 mb-4 text-sm relative z-30">
    <div>
        <label class="block text-xs text-gray-400 mb-1">Bulan</label>
        <select name="bulan" class="bg-slate-800/80 border border-slate-700/50 rounded-lg px-3 py-2 text-gray-200 hover:border-cyan-500/50 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all">
            <?php $nama_bulan = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember']; ?>
            <?php foreach($nama_bulan as $num => $nama): ?>
                <option value="<?= $num ?>" class="bg-slate-800 text-white" <?= (int)($bulan ?? 0) === (int)$num ? 'selected' : '' ?>><?= $nama ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label class="block text-xs text-gray-400 mb-1">Tahun</label>
        <select name="tahun" class="bg-slate-800/80 border border-slate-700/50 rounded-lg px-3 py-2 text-gray-200 hover:border-cyan-500/50 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all">
            <?php foreach(($tahun_list ?? [(int)($tahun ?? date('Y'))]) as $thn): ?>
                <option value="<?= $thn ?>" class="bg-slate-800 text-white" <?= (string)($tahun ?? '') === (string)$thn ? 'selected' : '' ?>><?= htmlspecialchars($thn) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php if (!empty($kategori_list)): ?>
    <div>
        <label class="block text-xs text-gray-400 mb-1">Kategori</label>
        <div class="custom-dropdown-container relative z-30" id="katDropdownWrap">
            <input type="hidden" name="kategori_id" id="filterKategoriId" class="kategori-hidden-input" value="<?= htmlspecialchars($kategori_id ?? '') ?>">
            
            <!-- Trigger Button -->
            <button type="button" class="dropdown-trigger min-w-[240px] max-w-[320px] w-full rounded-lg bg-slate-800/80 border border-slate-700/50 px-3 py-2 text-sm text-left hover:border-cyan-500/50 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all flex items-center justify-between">
                <?php
                    $selected_label = '-- Semua Kategori --';
                    foreach (($kategori_list ?? []) as $kat) {
                        if ((string)($kategori_id ?? '') === (string)$kat->id) {
                            $selected_label = ($kat->kode ?? '-') . ' - ' . ($kat->nama ?? '-');
                            break;
                        }
                    }
                ?>
                <span class="dropdown-label truncate <?= empty($kategori_id) ? 'text-slate-400' : 'text-white font-medium' ?>">
                    <?= htmlspecialchars($selected_label) ?>
                </span>
                <svg class="dropdown-chevron w-4 h-4 text-slate-400 transition-transform duration-200 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <!-- Dropdown Panel -->
            <div class="dropdown-panel hidden absolute left-0 top-full mt-1.5 z-50 min-w-[280px] w-full max-w-[360px] rounded-2xl shadow-2xl border border-slate-700 bg-slate-800 p-3 space-y-2">
                <!-- Search Box (Q Search X) -->
                <div class="flex items-center bg-slate-900/80 border border-slate-700/70 rounded-xl px-3 py-2 focus-within:border-cyan-500 focus-within:ring-2 focus-within:ring-cyan-500/20 transition-all">
                    <svg class="w-4 h-4 text-slate-400 flex-shrink-0 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" class="dropdown-search w-full bg-transparent text-sm text-slate-200 placeholder-slate-500 focus:outline-none" placeholder="Search" autocomplete="off">
                    <button type="button" class="dropdown-clear hidden text-slate-400 hover:text-slate-200 p-0.5 ml-1 transition" title="Clear">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Options List -->
                <div class="dropdown-options max-h-56 overflow-y-auto space-y-0.5 pr-1 text-sm">
                    <div class="dropdown-opt px-3 py-2 rounded-xl text-slate-300 font-medium hover:bg-cyan-600/20 hover:text-cyan-300 cursor-pointer transition-colors flex items-center justify-between <?= empty($kategori_id) ? 'bg-cyan-600/20 text-cyan-300 font-semibold is-selected' : '' ?>"
                         data-value=""
                         data-label="-- Semua Kategori --">
                        <span class="truncate">-- Semua Kategori --</span>
                        <svg class="opt-check <?= empty($kategori_id) ? '' : 'hidden' ?> w-4 h-4 text-cyan-400 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <?php foreach(($kategori_list ?? []) as $kat): ?>
                        <?php 
                            $is_sel = (string)($kategori_id ?? '') === (string)$kat->id;
                            $kat_text = ($kat->kode ?? '-') . ' - ' . ($kat->nama ?? '-');
                        ?>
                        <div class="dropdown-opt px-3 py-2 rounded-xl text-slate-300 font-medium hover:bg-cyan-600/20 hover:text-cyan-300 cursor-pointer transition-colors flex items-center justify-between <?= $is_sel ? 'bg-cyan-600/20 text-cyan-300 font-semibold is-selected' : '' ?>"
                             data-value="<?= $kat->id ?>"
                             data-label="<?= htmlspecialchars($kat_text) ?>">
                            <span class="truncate"><?= htmlspecialchars($kat_text) ?></span>
                            <svg class="opt-check <?= $is_sel ? '' : 'hidden' ?> w-4 h-4 text-cyan-400 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    <?php endforeach; ?>
                    <div class="dropdown-empty hidden py-4 text-center text-xs text-slate-400 font-medium">
                        Kategori tidak ditemukan
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-semibold px-4 py-2 rounded-lg transition">
        Tampilkan
    </button>
    <?php $q_bul = http_build_query(['bulan' => (int)($bulan ?? date('n')), 'tahun' => (int)($tahun ?? date('Y')), 'kategori_id' => ($kategori_id ?? ''), 'kode' => ($kode ?? '')]); ?>
    <a href="<?= base_url('laporan/export_excel_bulanan?' . $q_bul) ?>" class="bg-green-600 hover:bg-green-500 text-white font-semibold px-4 py-2 rounded-lg transition">
        Excel
    </a>
    <a href="<?= base_url('laporan/export_pdf_bulanan?' . $q_bul) ?>" target="_blank" class="bg-red-600 hover:bg-red-500 text-white font-semibold px-4 py-2 rounded-lg transition">
        PDF
    </a>
</form>

<?php $nb = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember']; ?>
<p class="text-sm text-gray-400 mb-4">Periode: <span class="text-gray-200 font-medium"><?= htmlspecialchars(($nb[(int)$bulan] ?? $bulan) . ' ' . $tahun) ?></span></p>

<div class="header border rounded-xl theme-card p-4 mb-4 text-center relative z-10">
    <h2 class="header-title text-lg font-semibold">Laporan STOK &mdash; <?= htmlspecialchars(($nb[(int)$bulan] ?? $bulan) . ' ' . $tahun) ?></h2>
    <p class="header-subtitle text-sm mt-1">BPS Provinsi Riau</p>
    <p class="header-subtitle text-sm">Dicetak: <?= date('d M Y, H:i') ?></p>
</div>

<div class="overflow-x-auto border rounded-xl theme-card trx-table-wrap relative z-10">
    <table class="w-full text-sm">
        <thead class="bg-slate-900/90 text-gray-300 border-b border-slate-700/50">
            <tr>
                <th class="px-4 py-3 text-left" style="width:50px;">No</th>
                <th class="px-4 py-3 text-left">Kode Barang</th>
                <th class="px-4 py-3 text-left">Nama Barang</th>
                <th class="px-4 py-3 text-left" style="width:90px;">Satuan</th>
                <th class="px-4 py-3 text-right" style="width:100px;">Stok Awal</th>
                <th class="px-4 py-3 text-right" style="width:90px;">Masuk</th>
                <th class="px-4 py-3 text-right" style="width:90px;">Keluar</th>
                <th class="px-4 py-3 text-right" style="width:100px;">Stok Akhir</th>
                <th class="px-4 py-3 text-left" style="width:150px;">Keterangan</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-800/30">
            <?php if (empty($rows)): ?>
            <tr>
                <td colspan="9" class="px-6 py-10 text-center">
                    <div class="text-gray-500">Belum ada data barang pada periode ini.</div>
                </td>
            </tr>
            <?php else: ?>
            <?php $no = 1; $t_awal = 0; $t_masuk = 0; $t_keluar = 0; $t_akhir = 0; $ada_awal = false; ?>
            <?php foreach(($rows ?? []) as $r): ?>
            <?php if ($r->stok_awal !== null) { $t_awal += (int)$r->stok_awal; $ada_awal = true; } $t_masuk += (int)($r->masuk ?? 0); $t_keluar += (int)($r->keluar ?? 0); $t_akhir += (int)($r->stok_akhir ?? 0); ?>
            <tr class="hover:bg-slate-800/50 transition-colors duration-150">
                <td class="px-4 py-3 text-gray-500"><?= $no++ ?></td>
                <td class="px-4 py-3 font-mono text-cyan-400"><?= htmlspecialchars($r->kode_barang ?? '-') ?></td>
                <td class="px-4 py-3 text-gray-200"><?= htmlspecialchars($r->nama_barang ?? '-') ?></td>
                <td class="px-4 py-3 text-gray-400"><?= htmlspecialchars($r->satuan ?? '-') ?></td>
                <td class="px-4 py-3 text-right text-gray-200"><?= $r->stok_awal !== null ? number_format((int)$r->stok_awal) : '-' ?></td>
                <td class="px-4 py-3 text-right text-green-400 font-semibold"><?= number_format((int)($r->masuk ?? 0)) ?></td>
                <td class="px-4 py-3 text-right text-red-400 font-semibold"><?= number_format((int)($r->keluar ?? 0)) ?></td>
                <td class="px-4 py-3 text-right font-semibold <?= (int)($r->stok_akhir ?? 0) < 0 ? 'text-red-400 font-bold' : 'text-gray-200' ?>"><?= number_format((int)($r->stok_akhir ?? 0)) ?></td>
                <td class="px-4 py-3">
                    <?php if (!empty($r->keterangan)): ?>
                        <?php if ($r->keterangan === 'Stok Sudah Mencapai Batas Minimum' || $r->keterangan === 'Stok minimum tercapai'): ?>
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-500/15 text-amber-400 border border-amber-500/25 whitespace-nowrap">
                                <?= htmlspecialchars($r->keterangan) ?>
                            </span>
                        <?php else: ?>
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/15 text-red-400 border border-red-500/25 whitespace-nowrap">
                                <?= htmlspecialchars($r->keterangan) ?>
                            </span>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <tr class="bg-slate-800/40 font-semibold">
                <td class="px-4 py-3 text-gray-500" colspan="4">Total</td>
                <td class="px-4 py-3 text-right text-gray-200"><?= $ada_awal ? number_format($t_awal) : '-' ?></td>
                <td class="px-4 py-3 text-right text-green-400"><?= number_format($t_masuk) ?></td>
                <td class="px-4 py-3 text-right text-red-400"><?= number_format($t_keluar) ?></td>
                <td class="px-4 py-3 text-right text-gray-200"><?= number_format($t_akhir) ?></td>
                <td class="px-4 py-3 text-gray-400">-</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Theme-aware styles -->
<style>
html.light tbody tr:hover {
    background: rgba(248, 250, 252, 0.6) !important;
}
html.light thead {
    background: rgba(241, 245, 249, 0.9) !important;
}
html.light thead th {
    color: var(--text-secondary) !important;
}
html.light .bg-slate-800\/80 {
    background: rgba(255, 255, 255, 0.95) !important;
}
html.light .bg-slate-800\/40 {
    background: rgba(241, 245, 249, 0.9) !important;
}
html.light .border-slate-700\/50 {
    border-color: rgba(226, 232, 240, 0.8) !important;
}
html.light .border-slate-800\/30 {
    border-color: rgba(226, 232, 240, 0.5) !important;
}
html.light .divide-slate-800\/30 > :not([hidden]) ~ :not([hidden]) {
    border-color: rgba(226, 232, 240, 0.5) !important;
}
html.light input,
html.light select {
    background: rgba(255, 255, 255, 0.95) !important;
    color: var(--text-primary) !important;
    border-color: rgba(203, 213, 225, 0.8) !important;
}
html.light select option {
    background: #ffffff !important;
    color: #0f172a !important;
}
html.light .text-gray-200 {
    color: var(--text-primary) !important;
}
html.light .text-gray-300 {
    color: var(--text-secondary) !important;
}
html.light .text-gray-400 {
    color: var(--text-muted) !important;
}
html.light .text-gray-500 {
    color: var(--text-secondary) !important;
}
html.light .text-gray-600 {
    color: var(--text-muted) !important;
}
html.light .text-cyan-400 {
    color: #0e7490 !important;
}
html.light .text-green-400 {
    color: #15803d !important;
}
html.light .text-red-400 {
    color: #b91c1c !important;
}
html.light .trx-table-wrap {
    border-color: rgba(226, 232, 240, 0.8) !important;
}

.dropdown-options::-webkit-scrollbar {
    width: 6px;
}
.dropdown-options::-webkit-scrollbar-track {
    background: rgba(15, 23, 42, 0.4);
    border-radius: 9999px;
}
.dropdown-options::-webkit-scrollbar-thumb {
    background: #475569;
    border-radius: 9999px;
}
.dropdown-options::-webkit-scrollbar-thumb:hover {
    background: #64748b;
}
html.light #katDropdownWrap .dropdown-panel {
    background-color: #ffffff !important;
    border-color: #cbd5e1 !important;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
}
html.light #katDropdownWrap .dropdown-panel .bg-slate-900\/80 {
    background-color: #f8fafc !important;
    border-color: #cbd5e1 !important;
}
html.light #katDropdownWrap .dropdown-search {
    color: #0f172a !important;
}
html.light #katDropdownWrap .dropdown-opt {
    color: #334155 !important;
}
html.light #katDropdownWrap .dropdown-opt:hover {
    background-color: #ecfeff !important;
    color: #0891b2 !important;
}
html.light #katDropdownWrap .dropdown-opt.is-selected {
    background-color: #ecfeff !important;
    color: #0891b2 !important;
}
html.light #katDropdownWrap .dropdown-empty {
    color: #94a3b8 !important;
}
html.light #katDropdownWrap .dropdown-options::-webkit-scrollbar-track {
    background: #f1f5f9;
}
html.light #katDropdownWrap .dropdown-options::-webkit-scrollbar-thumb {
    background: #cbd5e1;
}
</style>

<script>
(function() {
    var container = document.getElementById('katDropdownWrap');
    if (!container) return;

    var hiddenInput = container.querySelector('.kategori-hidden-input');
    var trigger = container.querySelector('.dropdown-trigger');
    var label = container.querySelector('.dropdown-label');
    var chevron = container.querySelector('.dropdown-chevron');
    var panel = container.querySelector('.dropdown-panel');
    var search = container.querySelector('.dropdown-search');
    var clearBtn = container.querySelector('.dropdown-clear');
    var options = container.querySelectorAll('.dropdown-opt');
    var emptyMsg = container.querySelector('.dropdown-empty');

    function closeDropdown() {
        panel.classList.add('hidden');
        chevron.classList.remove('rotate-180');
        container.style.zIndex = '';
    }

    function openDropdown() {
        container.style.zIndex = '60';
        panel.classList.remove('hidden');
        chevron.classList.add('rotate-180');
        search.value = '';
        clearBtn.classList.add('hidden');
        filterOptions('');
        setTimeout(function() { search.focus(); }, 50);
    }

    trigger.addEventListener('click', function(e) {
        e.stopPropagation();
        if (panel.classList.contains('hidden')) {
            openDropdown();
        } else {
            closeDropdown();
        }
    });

    function filterOptions(q) {
        q = (q || '').trim().toLowerCase();
        var matchCount = 0;
        options.forEach(function(opt) {
            var text = (opt.getAttribute('data-label') || '').toLowerCase();
            if (!q || text.indexOf(q) !== -1) {
                opt.classList.remove('hidden');
                matchCount++;
            } else {
                opt.classList.add('hidden');
            }
        });
        if (matchCount === 0) {
            emptyMsg.classList.remove('hidden');
        } else {
            emptyMsg.classList.add('hidden');
        }
    }

    search.addEventListener('input', function() {
        var val = this.value;
        if (val.length > 0) {
            clearBtn.classList.remove('hidden');
        } else {
            clearBtn.classList.add('hidden');
        }
        filterOptions(val);
    });

    search.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    clearBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        search.value = '';
        clearBtn.classList.add('hidden');
        filterOptions('');
        search.focus();
    });

    options.forEach(function(opt) {
        opt.addEventListener('click', function(e) {
            e.stopPropagation();
            var val = opt.getAttribute('data-value');
            var txt = opt.getAttribute('data-label');

            hiddenInput.value = val;
            label.textContent = txt;
            if (val === '') {
                label.className = 'dropdown-label truncate text-slate-400';
            } else {
                label.className = 'dropdown-label truncate text-white font-medium';
            }

            options.forEach(function(o) {
                var check = o.querySelector('.opt-check');
                if (o === opt) {
                    o.classList.add('bg-cyan-600/20', 'text-cyan-300', 'font-semibold', 'is-selected');
                    if (check) check.classList.remove('hidden');
                } else {
                    o.classList.remove('bg-cyan-600/20', 'text-cyan-300', 'font-semibold', 'is-selected');
                    if (check) check.classList.add('hidden');
                }
            });

            closeDropdown();
        });
    });

    document.addEventListener('click', function(e) {
        if (!container.contains(e.target)) {
            closeDropdown();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDropdown();
        }
    });
})();
</script>
