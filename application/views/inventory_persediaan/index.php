<div class="mb-6">
    <div>
        <h1 class="text-2xl font-bold header-title">Dashboard Stok Persediaan</h1>
        <p class="text-sm header-subtitle mt-1">Monitoring stok berjalan periode <?= htmlspecialchars($periode) ?></p>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="theme-card border rounded-xl p-4">
        <p class="text-sm header-subtitle">Jenis Barang Aktif</p>
        <p class="text-2xl font-bold header-title mt-2"><?= (int) $summary['jenis'] ?></p>
    </div>
    <div class="theme-card border rounded-xl p-4">
        <p class="text-sm header-subtitle">Total Stok Berjalan</p>
        <p class="text-2xl font-bold text-blue-400 mt-2"><?= number_format((int) $summary['total_stok']) ?></p>
    </div>
    <div class="theme-card border rounded-xl p-4">
        <p class="text-sm header-subtitle">Stok Menipis</p>
        <p class="text-2xl font-bold text-amber-400 mt-2"><?= (int) $summary['menipis'] ?></p>
    </div>
    <div class="theme-card border rounded-xl p-4">
        <p class="text-sm header-subtitle">Stok Kosong</p>
        <p class="text-2xl font-bold text-red-400 mt-2"><?= (int) $summary['kosong'] ?></p>
    </div>
</div>

<form method="get" class="theme-card border rounded-xl p-4 mb-6 relative z-30">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
        <input type="search" name="keyword" value="<?= htmlspecialchars($filters['keyword']) ?>" placeholder="Cari kode, nama, kategori..." class="rounded-lg bg-slate-800/80 border border-slate-700 px-3 py-2 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <!-- Custom Searchable Dropdown for Kategori -->
        <div class="custom-dropdown-container relative z-30" id="katDropdownWrap">
            <input type="hidden" name="kategori_id" id="filterKategoriId" class="kategori-hidden-input" value="<?= htmlspecialchars($filters['kategori_id'] ?? '') ?>">
            
            <!-- Trigger Button -->
            <button type="button" class="dropdown-trigger w-full rounded-lg bg-slate-800/80 border border-slate-700 px-3 py-2 text-sm text-left hover:border-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all flex items-center justify-between">
                <?php
                    $selected_label = 'Semua kategori';
                    foreach ($kategori_list as $kategori) {
                        if ((string) $filters['kategori_id'] === (string) $kategori->id) {
                            $selected_label = $kategori->kode . ' - ' . $kategori->nama;
                            break;
                        }
                    }
                ?>
                <span class="dropdown-label truncate <?= empty($filters['kategori_id']) ? 'header-subtitle' : 'header-title font-medium' ?>">
                    <?= htmlspecialchars($selected_label) ?>
                </span>
                <svg class="dropdown-chevron w-4 h-4 header-subtitle transition-transform duration-200 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <!-- Dropdown Panel -->
            <div class="dropdown-panel hidden absolute left-0 top-full mt-1.5 z-50 min-w-[280px] w-full max-w-[360px] rounded-2xl shadow-2xl border border-slate-700 bg-slate-800 p-3 space-y-2">
                <!-- Search Box (Q Search X) -->
                <div class="flex items-center bg-slate-900/80 border border-slate-700/70 rounded-xl px-3 py-2 focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-500/20 transition-all">
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
                <div class="dropdown-options max-h-52 overflow-y-auto space-y-0.5 pr-1 text-sm">
                    <div class="dropdown-opt px-3 py-2 rounded-xl text-slate-300 font-medium hover:bg-blue-600/20 hover:text-blue-300 cursor-pointer transition-colors flex items-center justify-between <?= empty($filters['kategori_id']) ? 'bg-blue-600/20 text-blue-300 font-semibold' : '' ?>"
                         data-value=""
                         data-label="Semua kategori">
                        <span class="truncate">Semua kategori</span>
                        <svg class="opt-check <?= empty($filters['kategori_id']) ? '' : 'hidden' ?> w-4 h-4 text-blue-400 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <?php foreach ($kategori_list as $kategori): ?>
                        <?php $is_sel = (string) $filters['kategori_id'] === (string) $kategori->id; ?>
                        <div class="dropdown-opt px-3 py-2 rounded-xl text-slate-300 font-medium hover:bg-blue-600/20 hover:text-blue-300 cursor-pointer transition-colors flex items-center justify-between <?= $is_sel ? 'bg-blue-600/20 text-blue-300 font-semibold' : '' ?>"
                             data-value="<?= (int) $kategori->id ?>"
                             data-label="<?= htmlspecialchars($kategori->kode . ' - ' . $kategori->nama) ?>">
                            <span class="truncate"><?= htmlspecialchars($kategori->kode . ' - ' . $kategori->nama) ?></span>
                            <svg class="opt-check <?= $is_sel ? '' : 'hidden' ?> w-4 h-4 text-blue-400 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        <select name="status" class="rounded-lg bg-slate-800/80 border border-slate-700 px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua status</option>
            <option value="aman" <?= $filters['status'] === 'aman' ? 'selected' : '' ?>>Aman</option>
            <option value="menipis" <?= $filters['status'] === 'menipis' ? 'selected' : '' ?>>Menipis</option>
            <option value="kosong" <?= $filters['status'] === 'kosong' ? 'selected' : '' ?>>Kosong</option>
        </select>
        <div class="flex gap-2">
            <button type="submit" class="flex-1 rounded-lg bg-slate-700 hover:bg-slate-600 px-4 py-2 text-sm font-semibold text-white">Filter</button>
            <a href="<?= base_url('inventory_persediaan') ?>" class="rounded-lg border border-slate-600 hover:bg-slate-700 px-4 py-2 text-sm font-semibold header-title">Reset</a>
        </div>
    </div>
</form>

<div class="theme-card border rounded-xl overflow-hidden relative z-10">
    <div class="px-5 py-4 border-b border-slate-800/50 flex items-center justify-between">
        <h2 class="font-semibold header-title">Daftar Stok</h2>
        <span class="text-xs header-subtitle"><?= count($rows) ?> data ditampilkan</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-900/70 header-subtitle">
                <tr>
                    <th class="text-center px-4 py-3 w-16">Foto</th>
                    <th class="text-left px-5 py-3">Barang</th>
                    <th class="text-left px-5 py-3">Kategori</th>
                    <th class="text-right px-5 py-3">Stok Awal</th>
                    <th class="text-right px-5 py-3">Masuk</th>
                    <th class="text-right px-5 py-3">Keluar</th>
                    <th class="text-right px-5 py-3">Stok Saat Ini</th>
                    <th class="text-center px-5 py-3">Status</th>
                    <th class="text-center px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/50">
                <?php if (empty($rows)): ?>
                    <tr><td colspan="9" class="px-5 py-10 text-center header-subtitle">Belum ada data yang sesuai filter.</td></tr>
                <?php else: ?>
                    <?php foreach ($rows as $row): ?>
                        <?php
                        $status_label = ['aman' => 'Aman', 'menipis' => 'Menipis', 'kosong' => 'Kosong'][$row->status_stok];
                        $status_class = ['aman' => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30', 'menipis' => 'bg-amber-500/15 text-amber-400 border-amber-500/30', 'kosong' => 'bg-red-500/15 text-red-400 border-red-500/30'][$row->status_stok];
                        $has_foto = !empty($row->gambar) && file_exists(FCPATH . 'uploads/persediaan/' . $row->gambar);
                        $foto_url = $has_foto ? base_url('uploads/persediaan/' . $row->gambar) : '';
                        ?>
                        <tr class="hover:bg-slate-800/40">
                            <td class="px-4 py-2.5 text-center">
                                <?php if ($has_foto): ?>
                                    <img src="<?= $foto_url ?>"
                                         alt="<?= htmlspecialchars($row->nama_barang) ?>"
                                         class="w-10 h-10 object-cover rounded-lg border border-slate-700/60 mx-auto cursor-pointer hover:scale-110 transition-transform shadow"
                                         onclick="previewGambarModal('<?= $foto_url ?>', '<?= htmlspecialchars(addslashes($row->nama_barang)) ?>')"
                                         title="Klik untuk perbesar">
                                <?php else: ?>
                                    <div class="w-10 h-10 rounded-lg bg-slate-800/80 border border-slate-700/50 flex items-center justify-center text-sm text-slate-500 mx-auto" title="Tidak ada foto">
                                        📦
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-5 py-3">
                                <div class="font-medium header-title"><?= htmlspecialchars($row->nama_barang) ?></div>
                                <div class="text-xs header-subtitle"><?= htmlspecialchars($row->kode_barang ?: '-') ?> · <?= htmlspecialchars($row->satuan) ?></div>
                            </td>
                            <td class="px-5 py-3 header-subtitle"><?= htmlspecialchars($row->kategori_nama ?: '-') ?></td>
                            <td class="px-5 py-3 text-right header-title"><?= number_format((int) $row->stok_awal) ?></td>
                            <td class="px-5 py-3 text-right text-emerald-400"><?= number_format((int) $row->masuk) ?></td>
                            <td class="px-5 py-3 text-right text-red-400"><?= number_format((int) $row->keluar) ?></td>
                            <td class="px-5 py-3 text-right font-bold header-title"><?= number_format((int) $row->stok_akhir) ?></td>
                            <td class="px-5 py-3 text-center"><span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold <?= $status_class ?>"><?= $status_label ?></span></td>
                            <td class="px-4 py-3 text-center">
                                <a href="<?= base_url('laporan/kartu_persediaan?barang_id=' . (int) $row->id) ?>" class="inline-flex items-center gap-1 px-2.5 py-1 rounded bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 border border-blue-500/25 text-xs font-medium transition whitespace-nowrap" title="Lihat Kartu Stok">
                                    Kartu Stok
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
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
    background-color: #eff6ff !important;
    color: #1d4ed8 !important;
}
html.light #katDropdownWrap .dropdown-opt.is-selected {
    background-color: #eff6ff !important;
    color: #1d4ed8 !important;
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
                label.className = 'dropdown-label truncate header-subtitle';
            } else {
                label.className = 'dropdown-label truncate header-title font-medium';
            }

            options.forEach(function(o) {
                var check = o.querySelector('.opt-check');
                if (o === opt) {
                    o.classList.add('bg-blue-600/20', 'text-blue-300', 'font-semibold', 'is-selected');
                    if (check) check.classList.remove('hidden');
                } else {
                    o.classList.remove('bg-blue-600/20', 'text-blue-300', 'font-semibold', 'is-selected');
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

function getSwalColors() {
    const isLight = document.documentElement.classList.contains('light');
    return {
        background: isLight ? '#ffffff' : '#1a1f2e',
        color: isLight ? '#0f172a' : '#f3f4f6'
    };
}

function previewGambarModal(url, title) {
    if (typeof Swal === 'undefined') return;
    const c = getSwalColors();
    Swal.fire({
        title: title || 'Foto Barang',
        imageUrl: url,
        imageAlt: title,
        imageWidth: 400,
        imageHeight: 300,
        showConfirmButton: false,
        showCloseButton: true,
        ...c
    });
}
</script>
