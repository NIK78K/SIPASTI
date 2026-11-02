<div class="max-w-5xl mx-auto mt-12 mb-24 space-y-8 px-4">

    <!-- BACK -->
    <div>
        <a href="<?= base_url('user_ticket/create_persediaan') ?>"
           class="inline-flex items-center gap-2 text-sm text-blue-900 hover:text-blue-950 font-semibold transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Pilih layanan lain
        </a>
    </div>

    <!-- ================= HEADER ================= -->
    <div class="text-center space-y-3">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-100 text-amber-700 text-sm font-semibold">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <span>Pengajuan Barang Persediaan</span>
        </div>
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-[#0B2E6B]">
            Form Pengajuan Barang
        </h1>
        <p class="text-base text-slate-600 max-w-2xl mx-auto">
            Ajukan barang baru yang belum ada di daftar/katalog persediaan
        </p>
    </div>

    <?php if ($this->session->flashdata('error')): ?>
    <div class="flex items-center gap-3 px-5 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm font-medium">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <span><?= $this->session->flashdata('error') ?></span>
    </div>
    <?php endif ?>

    <!-- ================= FORM ================= -->
    <div class="bg-white rounded-3xl p-8 md:p-10 shadow-2xl border border-gray-100">
        <form method="post" action="<?= base_url('user_ticket/pengajuan_persediaan') ?>" enctype="multipart/form-data" onsubmit="return validPengajuan()" class="space-y-6">

            <!-- STEP 1: DAFTAR BARANG DIAJUKAN -->
            <div class="space-y-3">
                <label class="flex items-center gap-2 text-sm font-bold text-gray-800">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-900 text-white text-xs">1</span>
                    Barang yang Diajukan
                    <span class="text-red-500">*</span>
                </label>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Tulis barang yang <strong>belum ada</strong> di daftar/katalog persediaan beserta kategori yang sesuai.
                    Kalau barangnya sudah ada di katalog, gunakan layanan <a href="<?= base_url('user_ticket/permintaan_persediaan') ?>" class="text-blue-800 font-semibold hover:underline">Permintaan</a>.
                </p>
                <div id="pengajuanRows" class="space-y-3"></div>
                <button type="button" onclick="pengajuanAddRow()"
                    class="inline-flex items-center gap-2 h-11 px-5 rounded-xl border-2 border-dashed border-blue-800 text-blue-900 font-bold text-sm hover:bg-blue-50 transition-all">
                    + Tambah Barang
                </button>
            </div>

            <!-- STEP 2: KEPERLUAN -->
            <div class="space-y-2 pt-4 border-t-2 border-gray-100">
                <label class="flex items-center gap-2 text-sm font-bold text-gray-800">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-900 text-white text-xs">2</span>
                    Keperluan
                    <span class="text-red-500">*</span>
                </label>
                <input name="keperluan" id="pKeperluan" type="text" placeholder="cth: Kebutuhan ATK bagian umum"
                    class="w-full h-14 rounded-xl px-5 border-2 border-gray-200 focus:border-blue-800 focus:ring-4 focus:ring-blue-100 text-gray-800 font-medium transition-all hover:border-gray-300">
            </div>

            <!-- STEP 3: UNIT KERJA + TANGGAL -->
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-gray-700">Unit kerja</label>
                    <select name="unit_kerja"
                        class="mt-1 w-full h-14 rounded-xl px-5 border-2 border-gray-200 focus:border-blue-800 focus:ring-4 focus:ring-blue-100 text-gray-800 font-medium transition-all bg-white hover:border-gray-300">
                        <option value="">-- Pilih unit kerja --</option>
                        <?php foreach (($unit_kerja_list ?? []) as $uk): ?>
                            <option value="<?= htmlspecialchars($uk) ?>" <?= (string)($unit_kerja_default ?? '') === (string)$uk ? 'selected' : '' ?>><?= htmlspecialchars($uk) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">Tanggal permintaan</label>
                    <input name="tanggal_permintaan" id="pTanggalButuh" type="date" min="<?= date('Y-m-d') ?>"
                        class="mt-1 w-full h-14 rounded-xl px-5 border-2 border-gray-200 focus:border-blue-800 focus:ring-4 focus:ring-blue-100 text-gray-800 font-medium transition-all hover:border-gray-300">
                </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="flex gap-4 pt-6 border-t-2 border-gray-100">
                <button type="submit"
                    class="flex-1 h-14 rounded-xl bg-blue-900 hover:bg-blue-950 text-white font-extrabold text-sm transition-all shadow-lg shadow-blue-900/25 active:scale-[.99]">
                    Kirim Pengajuan
                </button>
                <button type="button" onclick="window.location.href='<?= base_url('user_ticket/create_persediaan') ?>'"
                    class="h-14 px-8 rounded-xl border-2 border-gray-300 hover:bg-gray-100 hover:border-gray-400 text-gray-700 font-semibold transition-all">
                    Batal
                </button>
            </div>

        </form>
    </div>
</div>

<template id="pengajuanRowTemplate">
    <div class="pengajuan-row rounded-2xl border-2 border-gray-200 p-4 space-y-3 bg-slate-50/50">
        <div class="flex items-center justify-between">
            <p class="text-xs font-extrabold tracking-wide text-blue-900 row-label">BARANG</p>
            <button type="button" onclick="pengajuanDelRow(this)" class="text-xs font-bold text-red-600 hover:text-red-700 transition">Hapus</button>
        </div>
        <div class="grid md:grid-cols-2 gap-3">
            <div>
                <label class="text-[11px] font-bold text-gray-600 mb-1 block">Nama Barang <span class="text-red-500">*</span></label>
                <input name="nama_barang[]" type="text" placeholder="cth: Tinta printer Epson 003 hitam"
                    class="w-full h-12 rounded-xl px-4 border-2 border-gray-200 focus:border-blue-800 focus:ring-4 focus:ring-blue-100 text-sm text-gray-800 font-medium transition-all bg-white">
            </div>
            <div>
                <label class="text-[11px] font-bold text-gray-600 mb-1 block">Kategori Barang <span class="text-red-500">*</span></label>
                
                <!-- Custom Searchable Dropdown -->
                <div class="custom-dropdown-container relative">
                    <input type="hidden" name="kategori_id[]" class="kategori-hidden-input" value="">
                    
                    <!-- Trigger Button -->
                    <button type="button" class="dropdown-trigger w-full h-12 rounded-xl px-4 border-2 border-gray-200 bg-white hover:border-gray-300 focus:border-blue-800 focus:ring-4 focus:ring-blue-100 text-sm font-medium transition-all flex items-center justify-between text-left">
                        <span class="dropdown-label text-gray-400 truncate">Pilih Kategori</span>
                        <svg class="dropdown-chevron w-4 h-4 text-gray-500 transition-transform duration-200 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Dropdown Panel -->
                    <div class="dropdown-panel hidden absolute left-0 right-0 top-full mt-1.5 z-40 bg-white rounded-2xl shadow-xl border border-gray-200 p-3 space-y-2">
                        <!-- Search Box (Q Search X) -->
                        <div class="flex items-center bg-slate-50 border border-gray-200 rounded-xl px-3 py-2 focus-within:border-blue-600 focus-within:bg-white focus-within:ring-2 focus-within:ring-blue-100 transition-all">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" class="dropdown-search w-full bg-transparent text-sm text-gray-800 placeholder-gray-400 focus:outline-none" placeholder="Search" autocomplete="off">
                            <button type="button" class="dropdown-clear hidden text-gray-400 hover:text-gray-600 p-0.5 ml-1 transition" title="Clear">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Options List -->
                        <div class="dropdown-options max-h-52 overflow-y-auto space-y-0.5 pr-1 text-sm">
                            <?php foreach (($kategori_persediaan ?? []) as $kp): ?>
                                <div class="dropdown-opt px-3 py-2.5 rounded-xl text-gray-700 font-medium hover:bg-blue-50 hover:text-blue-900 cursor-pointer transition-colors flex items-center justify-between"
                                     data-value="<?= (int) $kp['id'] ?>"
                                     data-label="<?= htmlspecialchars($kp['kode'] . ' - ' . $kp['nama']) ?>">
                                    <span class="truncate"><?= htmlspecialchars($kp['kode'] . ' - ' . $kp['nama']) ?></span>
                                    <svg class="opt-check hidden w-4 h-4 text-blue-900 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            <?php endforeach; ?>
                            <div class="dropdown-empty hidden py-4 text-center text-xs text-gray-400 font-medium">
                                Kategori tidak ditemukan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <label class="text-[11px] font-bold text-gray-600 mb-1 block">Spesifikasi / Keterangan (Opsional)</label>
            <input name="keterangan[]" type="text" placeholder="cth: warna hitam, botol 65ml"
                class="w-full h-12 rounded-xl px-4 border-2 border-gray-200 focus:border-blue-800 focus:ring-4 focus:ring-blue-100 text-sm text-gray-800 font-medium transition-all bg-white">
        </div>
        <div>
            <label class="text-[11px] font-bold text-gray-600 mb-1 block">Foto / Gambar Barang (Opsional, JPG/PNG/WEBP, Maks 2MB)</label>
            <div class="flex items-center gap-3">
                <div class="row-img-preview-wrap w-12 h-12 rounded-xl border border-gray-300 bg-white overflow-hidden flex items-center justify-center flex-shrink-0">
                    <img class="row-img-preview w-full h-full object-cover hidden" src="" alt="Preview">
                    <span class="row-img-placeholder text-lg text-gray-400">📷</span>
                </div>
                <input name="gambar_item[]" type="file" accept="image/*" onchange="previewRowImage(this)"
                    class="flex-1 text-xs text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-100 file:text-blue-900 hover:file:bg-blue-200 cursor-pointer">
            </div>
        </div>
    </div>
</template>

<style>
.dropdown-options::-webkit-scrollbar {
    width: 6px;
}
.dropdown-options::-webkit-scrollbar-track {
    background: #f8fafc;
    border-radius: 9999px;
}
.dropdown-options::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 9999px;
}
.dropdown-options::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>

<script>
function initRowDropdown(row) {
    if (!row) return;
    var container = row.querySelector('.custom-dropdown-container');
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
        if (row) row.style.zIndex = '';
    }

    function openDropdown() {
        document.querySelectorAll('.custom-dropdown-container .dropdown-panel').forEach(function(p) {
            if (p !== panel) {
                p.classList.add('hidden');
                var otherContainer = p.closest('.custom-dropdown-container');
                if (otherContainer) {
                    var otherChevron = otherContainer.querySelector('.dropdown-chevron');
                    if (otherChevron) otherChevron.classList.remove('rotate-180');
                    otherContainer.style.zIndex = '';
                    var otherRow = otherContainer.closest('.pengajuan-row');
                    if (otherRow) otherRow.style.zIndex = '';
                }
            }
        });

        if (row) {
            row.style.position = 'relative';
            row.style.zIndex = '30';
        }
        container.style.zIndex = '50';
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
            label.classList.remove('text-gray-400');
            label.classList.add('text-gray-800');

            options.forEach(function(o) {
                var check = o.querySelector('.opt-check');
                if (o === opt) {
                    o.classList.add('bg-blue-50', 'text-blue-900');
                    if (check) check.classList.remove('hidden');
                } else {
                    o.classList.remove('bg-blue-50', 'text-blue-900');
                    if (check) check.classList.add('hidden');
                }
            });

            closeDropdown();
        });
    });
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('.custom-dropdown-container')) {
        document.querySelectorAll('.custom-dropdown-container .dropdown-panel').forEach(function(p) {
            p.classList.add('hidden');
        });
        document.querySelectorAll('.custom-dropdown-container .dropdown-chevron').forEach(function(c) {
            c.classList.remove('rotate-180');
        });
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.custom-dropdown-container .dropdown-panel').forEach(function(p) {
            p.classList.add('hidden');
        });
        document.querySelectorAll('.custom-dropdown-container .dropdown-chevron').forEach(function(c) {
            c.classList.remove('rotate-180');
        });
    }
});

function pengajuanAddRow() {
    var box = document.getElementById('pengajuanRows');
    var tpl = document.getElementById('pengajuanRowTemplate');
    var clone = tpl.content.cloneNode(true);
    box.appendChild(clone);
    var newRow = box.lastElementChild;
    initRowDropdown(newRow);
    pengajuanReindex();
}

function previewRowImage(input) {
    var row = input.closest('.pengajuan-row');
    if (!row) return;
    var preview = row.querySelector('.row-img-preview');
    var placeholder = row.querySelector('.row-img-placeholder');
    var file = input.files && input.files[0];
    var maxBytes = 2 * 1024 * 1024; // 2MB

    if (file) {
        if (file.size > maxBytes) {
            Swal.fire({
                icon: 'warning',
                title: 'Ukuran Gambar Terlalu Besar',
                text: 'Ukuran file gambar maksimal 2 MB. Silakan pilih file yang lebih kecil.'
            });
            input.value = '';
            if (preview) {
                preview.src = '';
                preview.classList.add('hidden');
            }
            if (placeholder) placeholder.classList.remove('hidden');
            return;
        }

        var reader = new FileReader();
        reader.onload = function(e) {
            if (preview) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            }
            if (placeholder) placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        if (preview) {
            preview.src = '';
            preview.classList.add('hidden');
        }
        if (placeholder) placeholder.classList.remove('hidden');
    }
}

function pengajuanReindex() {
    var box = document.getElementById('pengajuanRows');
    box.querySelectorAll('.pengajuan-row').forEach(function (el, i) {
        var label = el.querySelector('.row-label');
        if (label) label.textContent = 'BARANG ' + (i + 1);
    });
}

function pengajuanDelRow(btn) {
    var box = document.getElementById('pengajuanRows');
    if (box.querySelectorAll('.pengajuan-row').length <= 1) {
        Swal.fire({ icon: 'warning', title: 'Minimal 1 barang', text: 'Pengajuan harus berisi minimal 1 barang.' });
        return;
    }
    btn.closest('.pengajuan-row').remove();
    pengajuanReindex();
}

function validPengajuan() {
    var rows = document.querySelectorAll('#pengajuanRows .pengajuan-row');
    var adaNama = false;
    var kategoriLengkap = true;
    var barisKurangKat = 0;

    rows.forEach(function (row, idx) {
        var namaInput = row.querySelector('input[name="nama_barang[]"]');
        var katHidden = row.querySelector('input[name="kategori_id[]"]');
        var katSelect = row.querySelector('select[name="kategori_id[]"]');
        var namaVal = namaInput ? namaInput.value.trim() : '';
        var katVal = katHidden ? katHidden.value.trim() : (katSelect ? katSelect.value.trim() : '');

        if (namaVal !== '') {
            adaNama = true;
            if (!katVal && kategoriLengkap) {
                kategoriLengkap = false;
                barisKurangKat = idx + 1;
            }
        }
    });

    if (!adaNama) {
        Swal.fire({ icon: 'warning', title: 'Barang belum diisi', text: 'Isi minimal 1 nama barang yang diajukan.' });
        return false;
    }
    if (!kategoriLengkap) {
        Swal.fire({ icon: 'warning', title: 'Kategori wajib dipilih', text: 'Pilih kategori persediaan untuk BARANG ' + barisKurangKat + '.' });
        return false;
    }
    if (!document.getElementById('pKeperluan').value.trim()) {
        Swal.fire({ icon: 'warning', title: 'Keperluan wajib diisi', text: 'Tulis keperluan pengajuan barang.' });
        return false;
    }
    var tglEl = document.getElementById('pTanggalButuh');
    if (tglEl && tglEl.value) {
        var now = new Date();
        var today = now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0') + '-' + String(now.getDate()).padStart(2, '0');
        if (tglEl.value < today) {
            Swal.fire({ icon: 'warning', title: 'Tanggal tidak valid', text: 'Tanggal permintaan tidak boleh mundur. Pilih hari ini atau tanggal ke depan.' });
            return false;
        }
    }

    var maxBytes = 2 * 1024 * 1024;
    var fileOver = false;
    document.querySelectorAll('#pengajuanRows input[name="gambar_item[]"]').forEach(function (inp) {
        if (inp.files && inp.files[0] && inp.files[0].size > maxBytes) {
            fileOver = true;
        }
    });
    if (fileOver) {
        Swal.fire({ icon: 'warning', title: 'Ukuran Gambar Terlalu Besar', text: 'Ukuran file gambar maksimal 2 MB.' });
        return false;
    }

    return true;
}

pengajuanAddRow();
</script>
