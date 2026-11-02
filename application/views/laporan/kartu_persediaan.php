<h1 class="text-2xl font-semibold tracking-tight mb-1 header-title">
    Kartu Persediaan
</h1>
<p class="text-sm text-gray-400 mb-6 header-subtitle">
    Mutasi masuk &amp; keluar per barang per periode
</p>

<!-- ================= FILTER ================= -->
<form method="get" action="<?= base_url('laporan/kartu_persediaan') ?>" class="flex flex-wrap items-end gap-3 mb-4 text-sm relative z-30" onsubmit="return validKartu()">
    <div>
        <label class="block text-xs text-gray-400 mb-1">Barang <span class="text-red-400">*</span></label>
        <div class="custom-dropdown-container relative z-30" id="barangDropdownWrap">
            <input type="hidden" name="barang_id" id="filterBarangId" class="barang-hidden-input" value="<?= htmlspecialchars($barang_id ?? '') ?>" required>
            
            <!-- Trigger Button -->
            <button type="button" class="dropdown-trigger min-w-[280px] max-w-[340px] w-full rounded-lg bg-slate-800/80 border border-slate-700/50 px-3 py-2 text-sm text-left hover:border-cyan-500/50 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all flex items-center justify-between">
                <?php
                    $selected_label = '-- Pilih Barang --';
                    foreach (($barang_list ?? []) as $brg) {
                        if ((string)($barang_id ?? '') === (string)$brg->id) {
                            $selected_label = ($brg->kode_barang ?? '-') . ' - ' . ($brg->nama_barang ?? '-');
                            break;
                        }
                    }
                ?>
                <span class="dropdown-label truncate <?= empty($barang_id) ? 'text-slate-400' : 'text-white font-medium' ?>">
                    <?= htmlspecialchars($selected_label) ?>
                </span>
                <svg class="dropdown-chevron w-4 h-4 text-slate-400 transition-transform duration-200 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <!-- Dropdown Panel -->
            <div class="dropdown-panel hidden absolute left-0 top-full mt-1.5 z-50 min-w-[300px] w-full max-w-[380px] rounded-2xl shadow-2xl border border-slate-700 bg-slate-800 p-3 space-y-2">
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
                    <?php foreach(($barang_list ?? []) as $brg): ?>
                        <?php 
                            $is_sel = (string)($barang_id ?? '') === (string)$brg->id;
                            $brg_text = ($brg->kode_barang ?? '-') . ' - ' . ($brg->nama_barang ?? '-');
                        ?>
                        <div class="dropdown-opt px-3 py-2 rounded-xl text-slate-300 font-medium hover:bg-cyan-600/20 hover:text-cyan-300 cursor-pointer transition-colors flex items-center justify-between <?= $is_sel ? 'bg-cyan-600/20 text-cyan-300 font-semibold is-selected' : '' ?>"
                             data-value="<?= $brg->id ?>"
                             data-label="<?= htmlspecialchars($brg_text) ?>">
                            <span class="truncate"><?= htmlspecialchars($brg_text) ?></span>
                            <svg class="opt-check <?= $is_sel ? '' : 'hidden' ?> w-4 h-4 text-cyan-400 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    <?php endforeach; ?>
                    <div class="dropdown-empty hidden py-4 text-center text-xs text-slate-400 font-medium">
                        Barang tidak ditemukan
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-semibold px-4 py-2 rounded-lg transition">
        Tampilkan
    </button>
</form>

<?php if (empty($barang)): ?>
    <div class="border rounded-xl theme-card p-10 text-center relative z-10">
        <div class="text-gray-500">Pilih barang untuk menampilkan kartu persediaan.</div>
    </div>
<?php else: ?>
    <?php $nb = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember']; ?>

    <!-- ================= KOP BARANG ================= -->
    <div class="border rounded-xl theme-card p-4 mb-4 text-sm grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 relative z-10">
        <div>
            <div class="text-xs text-gray-500">Kode</div>
            <div class="font-mono text-cyan-400 font-semibold"><?= htmlspecialchars($barang->kode_barang ?? '-') ?></div>
        </div>
        <div>
            <div class="text-xs text-gray-500">Nama</div>
            <div class="font-medium text-gray-200"><?= htmlspecialchars($barang->nama_barang ?? '-') ?></div>
        </div>
        <div>
            <div class="text-xs text-gray-500">Satuan</div>
            <div class="text-gray-200"><?= htmlspecialchars($barang->satuan ?? '-') ?></div>
        </div>
        <div>
            <div class="text-xs text-gray-500">Kategori</div>
            <div class="text-gray-200"><?= htmlspecialchars(($barang->kategori_kode ?? '-') . ' - ' . ($barang->kategori_nama ?? '-')) ?></div>
        </div>
        <div>
            <div class="text-xs text-gray-500">Periode</div>
            <div class="text-gray-200"><?= htmlspecialchars(($nb[(int)$bulan] ?? $bulan) . ' ' . $tahun) ?></div>
        </div>
    </div>

    <!-- ================= RINGKASAN ================= -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-4 text-sm">
        <div class="theme-card stat-card border rounded-xl p-4">
            <div class="text-gray-400 header-subtitle">Stok Awal</div>
            <div class="mt-1 text-2xl font-semibold text-gray-200"><?= number_format((int)($stok_awal ?? 0)) ?></div>
            <div class="text-xs text-gray-500"><?= htmlspecialchars($barang->satuan ?? '') ?></div>
        </div>
        <div class="theme-card stat-card border rounded-xl p-4">
            <div class="text-gray-400 header-subtitle">Total Masuk</div>
            <div class="mt-1 text-2xl font-semibold text-green-400"><?= number_format((int)($total_masuk ?? 0)) ?></div>
            <div class="text-xs text-gray-500"><?= htmlspecialchars($barang->satuan ?? '') ?></div>
        </div>
        <div class="theme-card stat-card border rounded-xl p-4">
            <div class="text-gray-400 header-subtitle">Total Keluar</div>
            <div class="mt-1 text-2xl font-semibold text-red-400"><?= number_format((int)($total_keluar ?? 0)) ?></div>
            <div class="text-xs text-gray-500"><?= htmlspecialchars($barang->satuan ?? '') ?></div>
        </div>
        <div class="theme-card stat-card border rounded-xl p-4">
            <div class="text-gray-400 header-subtitle">Stok Akhir</div>
            <div class="mt-1 text-2xl font-semibold text-gray-200"><?= number_format((int)($saldo_akhir ?? 0)) ?></div>
            <div class="text-xs text-gray-500"><?= htmlspecialchars($barang->satuan ?? '') ?></div>
        </div>
    </div>

    <div class="overflow-x-auto border rounded-xl theme-card trx-table-wrap">
        <table class="w-full text-sm" style="table-layout:fixed;">
            <thead class="bg-slate-900/90 text-gray-300 border-b border-slate-700/50">
                <tr>
                    <th class="px-4 py-3 text-left" style="width:50px;">No</th>
                    <th class="px-4 py-3 text-left" style="width:120px;">Tanggal</th>
                    <th class="px-4 py-3 text-left">Uraian</th>
                    <th class="px-4 py-3 text-left" style="width:130px;">No. Dokumen</th>
                    <th class="px-4 py-3 text-right" style="width:90px;">Masuk</th>
                    <th class="px-4 py-3 text-right" style="width:90px;">Keluar</th>
                    <th class="px-4 py-3 text-right" style="width:90px;">Sisa</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/30">
                <tr class="bg-slate-800/40">
                    <td class="px-4 py-3 text-gray-500">-</td>
                    <td class="px-4 py-3 text-gray-400">-</td>
                    <td class="px-4 py-3 font-semibold text-gray-200">Saldo Awal</td>
                    <td class="px-4 py-3 text-gray-400">-</td>
                    <td class="px-4 py-3 text-right text-gray-400">-</td>
                    <td class="px-4 py-3 text-right text-gray-400">-</td>
                    <td class="px-4 py-3 text-right font-semibold text-gray-200"><?= number_format((int)($stok_awal ?? 0)) ?></td>
                </tr>
                <?php if (empty($rows)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center">
                        <div class="text-gray-500">Belum ada transaksi pada periode ini.</div>
                    </td>
                </tr>
                <?php else: ?>
                <?php $no = 1; foreach(($rows ?? []) as $t): ?>
                <?php
                    $cleanUraian = $t->uraian ?? '-';
                    if (!empty($t->no_dokumen)) {
                        $cleanUraian = preg_replace('/^Penyerahan\s+' . preg_quote($t->no_dokumen, '/') . ':\s*/i', '', $cleanUraian);
                    }
                    $cleanUraian = preg_replace('/^Penyerahan\s+TKT-[^:]*:\s*/i', '', $cleanUraian);
                    if (trim((string)$cleanUraian) === '') {
                        $cleanUraian = '-';
                    }
                ?>
                <tr class="hover:bg-slate-800/50 transition-colors duration-150">
                    <td class="px-4 py-3 text-gray-500"><?= $no++ ?></td>
                    <td class="px-4 py-3 text-gray-400 whitespace-nowrap"><?= htmlspecialchars($t->tanggal ?? '-') ?></td>
                    <td class="px-4 py-3 text-gray-200">
                        <?= htmlspecialchars($cleanUraian) ?>
                        <?php if (($t->jenis ?? '') === 'masuk'): ?>
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-gradient-to-r from-green-500/15 to-emerald-500/15 text-green-400 ring-1 ring-green-500/30">masuk</span>
                        <?php else: ?>
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-gradient-to-r from-red-500/15 to-rose-500/15 text-red-400 ring-1 ring-red-500/30">keluar</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 font-mono text-gray-400"><?= htmlspecialchars($t->no_dokumen ?? '-') ?></td>
                    <td class="px-4 py-3 text-right text-green-400 font-semibold"><?= ($t->jenis ?? '') === 'masuk' ? number_format((int)($t->jumlah ?? 0)) : '-' ?></td>
                    <td class="px-4 py-3 text-right text-red-400 font-semibold"><?= ($t->jenis ?? '') === 'keluar' ? number_format((int)($t->jumlah ?? 0)) : '-' ?></td>
                    <td class="px-4 py-3 text-right font-semibold text-gray-200"><?= number_format((int)($t->sisa ?? 0)) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
                <tr class="bg-slate-800/40">
                    <td class="px-4 py-3 text-gray-500">-</td>
                    <td class="px-4 py-3 text-gray-400">-</td>
                    <td class="px-4 py-3 font-semibold text-gray-200">Saldo Akhir</td>
                    <td class="px-4 py-3 text-gray-400">-</td>
                    <td class="px-4 py-3 text-right text-gray-400">-</td>
                    <td class="px-4 py-3 text-right text-gray-400">-</td>
                    <td class="px-4 py-3 text-right font-semibold text-gray-200"><?= number_format((int)($saldo_akhir ?? 0)) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<script>
(function () {
    var s = document.getElementById('barangSearch');
    var d = document.getElementById('barangSelect');
    if (!s || !d) return;
    s.addEventListener('input', function () {
        var q = s.value.toLowerCase();
        for (var i = 0; i < d.options.length; i++) {
            var t = (d.options[i].text || '').toLowerCase();
            d.options[i].hidden = q && t.indexOf(q) === -1 && d.options[i].value !== '';
        }
    });
})();
</script>

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
html.light #barangDropdownWrap .dropdown-panel {
    background-color: #ffffff !important;
    border-color: #cbd5e1 !important;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
}
html.light #barangDropdownWrap .dropdown-panel .bg-slate-900\/80 {
    background-color: #f8fafc !important;
    border-color: #cbd5e1 !important;
}
html.light #barangDropdownWrap .dropdown-search {
    color: #0f172a !important;
}
html.light #barangDropdownWrap .dropdown-opt {
    color: #334155 !important;
}
html.light #barangDropdownWrap .dropdown-opt:hover {
    background-color: #ecfeff !important;
    color: #0891b2 !important;
}
html.light #barangDropdownWrap .dropdown-opt.is-selected {
    background-color: #ecfeff !important;
    color: #0891b2 !important;
}
html.light #barangDropdownWrap .dropdown-empty {
    color: #94a3b8 !important;
}
html.light #barangDropdownWrap .dropdown-options::-webkit-scrollbar-track {
    background: #f1f5f9;
}
html.light #barangDropdownWrap .dropdown-options::-webkit-scrollbar-thumb {
    background: #cbd5e1;
}
</style>

<script>
function validKartu() {
    var val = document.getElementById('filterBarangId').value;
    if (!val) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: 'warning', title: 'Pilih Barang', text: 'Silakan pilih barang persediaan terlebih dahulu.' });
        } else {
            alert('Silakan pilih barang persediaan terlebih dahulu.');
        }
        return false;
    }
    return true;
}

(function() {
    var container = document.getElementById('barangDropdownWrap');
    if (!container) return;

    var hiddenInput = container.querySelector('.barang-hidden-input');
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
