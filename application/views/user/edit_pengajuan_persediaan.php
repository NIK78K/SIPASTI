<div class="max-w-4xl mx-auto px-4 py-8 mb-24">
    <!-- TOMBOL KEMBALI -->
    <div class="mb-6">
        <a href="<?= base_url('user_ticket/history') ?>"
           class="inline-flex items-center gap-2 text-sm font-bold text-blue-900 hover:text-blue-950 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
            Kembali ke Riwayat
        </a>
    </div>

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-8 bg-white p-6 md:p-8 rounded-3xl shadow-sm border border-slate-200">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-amber-100 text-amber-800 border border-amber-200">
                    Mode Edit
                </span>
                <span class="text-xs font-mono font-bold text-slate-500">
                    <?= htmlspecialchars($ticket['kode_tiket'] ?? '-') ?>
                </span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Edit Pengajuan Barang</h1>
            <p class="text-xs md:text-sm text-slate-500 mt-1">Perbarui daftar barang yang diajukan, keperluan, atau unit kerja.</p>
        </div>
        <div class="flex-shrink-0">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                <?= htmlspecialchars($ticket['status'] ?? 'Menunggu') ?>
            </span>
        </div>
    </div>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-sm font-semibold flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <?= $this->session->flashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- FORM EDIT PENGAJUAN -->
    <div class="bg-white rounded-3xl p-6 md:p-10 shadow-xl border border-gray-100">
        <form method="post" action="<?= base_url('user_ticket/update_pengajuan_persediaan/' . (int) ($ticket['id'] ?? 0)) ?>"
              enctype="multipart/form-data" onsubmit="return validPengajuan()" class="space-y-6">

            <!-- STEP 1: DAFTAR BARANG DIAJUKAN -->
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm font-bold text-gray-800">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-900 text-white text-xs">1</span>
                        Barang yang Diajukan
                        <span class="text-red-500">*</span>
                    </label>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Ubah data barang yang diajukan atau tambahkan barang baru jika diperlukan.
                </p>

                <div id="pengajuanRows" class="space-y-4"></div>

                <button type="button" onclick="pengajuanAddRow()"
                    class="inline-flex items-center gap-2 h-11 px-5 rounded-xl border-2 border-dashed border-blue-800 text-blue-900 font-bold text-sm hover:bg-blue-50 transition-all">
                    + Tambah Barang Lain
                </button>
            </div>

            <!-- STEP 2: KEPERLUAN -->
            <div class="space-y-2 pt-5 border-t-2 border-gray-100">
                <label class="flex items-center gap-2 text-sm font-bold text-gray-800">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-900 text-white text-xs">2</span>
                    Keperluan
                    <span class="text-red-500">*</span>
                </label>
                <input name="keperluan" id="pKeperluan" type="text"
                    value="<?= htmlspecialchars($parsed['keperluan'] ?? '') ?>"
                    placeholder="cth: Kebutuhan ATK bagian umum"
                    class="w-full h-14 rounded-xl px-5 border-2 border-gray-200 focus:border-blue-800 focus:ring-4 focus:ring-blue-100 text-gray-800 font-medium transition-all hover:border-gray-300">
            </div>

            <!-- STEP 3: UNIT KERJA + TANGGAL -->
            <div class="grid md:grid-cols-2 gap-4 pt-5 border-t-2 border-gray-100">
                <div>
                    <label class="text-xs font-bold text-gray-700">Unit Kerja</label>
                    <select name="unit_kerja"
                        class="mt-1 w-full h-14 rounded-xl px-5 border-2 border-gray-200 focus:border-blue-800 focus:ring-4 focus:ring-blue-100 text-gray-800 font-medium transition-all bg-white hover:border-gray-300">
                        <option value="">-- Pilih unit kerja --</option>
                        <?php foreach (($unit_kerja_list ?? []) as $uk): ?>
                            <option value="<?= htmlspecialchars($uk) ?>" <?= (string)($unit_kerja_default ?? '') === (string)$uk ? 'selected' : '' ?>><?= htmlspecialchars($uk) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">Tanggal Permintaan</label>
                    <input name="tanggal_permintaan" id="pTanggalButuh" type="date"
                        value="<?= htmlspecialchars($parsed['tanggal_permintaan'] ?? '') ?>"
                        class="mt-1 w-full h-14 rounded-xl px-5 border-2 border-gray-200 focus:border-blue-800 focus:ring-4 focus:ring-blue-100 text-gray-800 font-medium transition-all hover:border-gray-300">
                </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="flex gap-4 pt-6 border-t-2 border-gray-100">
                <button type="submit"
                    class="flex-1 h-14 rounded-xl bg-blue-900 hover:bg-blue-950 text-white font-extrabold text-sm transition-all shadow-lg shadow-blue-900/25 active:scale-[.99]">
                    Simpan Perubahan
                </button>
                <button type="button" onclick="window.location.href='<?= base_url('user_ticket/history') ?>'"
                    class="h-14 px-8 rounded-xl border-2 border-gray-300 hover:bg-gray-100 hover:border-gray-400 text-gray-700 font-semibold transition-all">
                    Batal
                </button>
            </div>

        </form>
    </div>
</div>

<template id="pengajuanRowTemplate">
    <div class="pengajuan-row rounded-2xl border-2 border-gray-200 p-4 space-y-3 bg-slate-50/60 transition-all hover:border-slate-300">
        <input type="hidden" name="existing_barang_id[]" class="row-existing-bid" value="">
        <input type="hidden" name="existing_kode[]" class="row-existing-kode" value="">
        <input type="hidden" name="existing_gambar[]" class="row-existing-gambar" value="">

        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <p class="text-xs font-extrabold tracking-wide text-blue-900 row-label">BARANG</p>
                <span class="row-kode-badge hidden px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-blue-100 text-blue-800 border border-blue-200"></span>
            </div>
            <button type="button" onclick="pengajuanDelRow(this)" class="text-xs font-bold text-red-600 hover:text-red-700 transition">Hapus</button>
        </div>

        <div class="grid md:grid-cols-2 gap-3">
            <div>
                <label class="text-[11px] font-bold text-gray-600 mb-1 block">Nama Barang <span class="text-red-500">*</span></label>
                <input name="nama_barang[]" type="text" placeholder="cth: Tinta printer Epson 003 hitam"
                    class="row-nama-input w-full h-12 rounded-xl px-4 border-2 border-gray-200 focus:border-blue-800 focus:ring-4 focus:ring-blue-100 text-sm text-gray-800 font-medium transition-all bg-white">
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
                        <!-- Search Box -->
                        <div class="flex items-center bg-slate-50 border border-gray-200 rounded-xl px-3 py-2 focus-within:border-blue-600 focus-within:bg-white focus-within:ring-2 focus-within:ring-blue-100 transition-all">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" class="dropdown-search w-full bg-transparent text-sm text-gray-800 placeholder-gray-400 focus:outline-none" placeholder="Cari kategori..." autocomplete="off">
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
                class="row-ket-input w-full h-12 rounded-xl px-4 border-2 border-gray-200 focus:border-blue-800 focus:ring-4 focus:ring-blue-100 text-sm text-gray-800 font-medium transition-all bg-white">
        </div>

        <div>
            <label class="text-[11px] font-bold text-gray-600 mb-1 block">Foto / Gambar Barang (Opsional, JPG/PNG/WEBP, Maks 2MB)</label>
            <div class="flex items-center gap-3">
                <div class="row-img-preview-wrap w-14 h-14 rounded-xl border border-gray-300 bg-white overflow-hidden flex items-center justify-center flex-shrink-0 relative group">
                    <img class="row-img-preview w-full h-full object-cover hidden" src="" alt="Preview">
                    <span class="row-img-placeholder text-xl text-gray-400">📷</span>
                </div>
                <div class="flex-1">
                    <input name="gambar_item[]" type="file" accept="image/*" onchange="previewRowImage(this)"
                        class="w-full text-xs text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-100 file:text-blue-900 hover:file:bg-blue-200 cursor-pointer">
                    <p class="row-img-hint text-[10px] text-slate-400 mt-1 hidden">Biarkan kosong jika tidak ingin mengubah foto saat ini.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.dropdown-options::-webkit-scrollbar { width: 6px; }
.dropdown-options::-webkit-scrollbar-track { background: #f8fafc; border-radius: 9999px; }
.dropdown-options::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
.dropdown-options::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>

<script>
function initRowDropdown(row) {
    if (!row) return;
    var container = row.querySelector('.custom-dropdown-container');
    if (!container) return;

    var hiddenInput = container.querySelector('.kategori-hidden-input');
    var trigger     = container.querySelector('.dropdown-trigger');
    var label       = container.querySelector('.dropdown-label');
    var chevron     = container.querySelector('.dropdown-chevron');
    var panel       = container.querySelector('.dropdown-panel');
    var search      = container.querySelector('.dropdown-search');
    var clearBtn    = container.querySelector('.dropdown-clear');
    var options     = container.querySelectorAll('.dropdown-opt');
    var emptyMsg    = container.querySelector('.dropdown-empty');

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

    search.addEventListener('click', function(e) { e.stopPropagation(); });

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
            selectOption(opt);
            closeDropdown();
        });
    });

    function selectOption(opt) {
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
    }

    container._selectValue = function(val) {
        var found = null;
        options.forEach(function(o) {
            if (String(o.getAttribute('data-value')) === String(val)) {
                found = o;
            }
        });
        if (found) selectOption(found);
    };
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

function pengajuanAddRow(data) {
    data = data || {};
    var box = document.getElementById('pengajuanRows');
    var tpl = document.getElementById('pengajuanRowTemplate');
    var clone = tpl.content.cloneNode(true);
    box.appendChild(clone);
    var newRow = box.lastElementChild;
    initRowDropdown(newRow);

    // Pre-fill data jika ada
    if (data.nama) {
        var namaInp = newRow.querySelector('.row-nama-input');
        if (namaInp) namaInp.value = data.nama;
    }
    if (data.ket) {
        var ketInp = newRow.querySelector('.row-ket-input');
        if (ketInp) ketInp.value = data.ket;
    }
    if (data.barang_id) {
        var bidInp = newRow.querySelector('.row-existing-bid');
        if (bidInp) bidInp.value = data.barang_id;
    }
    if (data.kode) {
        var kodeInp = newRow.querySelector('.row-existing-kode');
        if (kodeInp) kodeInp.value = data.kode;
        var badge = newRow.querySelector('.row-kode-badge');
        if (badge) {
            badge.textContent = 'Kode: ' + data.kode;
            badge.classList.remove('hidden');
        }
    }
    if (data.gambar) {
        var gambarInp = newRow.querySelector('.row-existing-gambar');
        if (gambarInp) gambarInp.value = data.gambar;
        var preview = newRow.querySelector('.row-img-preview');
        var placeholder = newRow.querySelector('.row-img-placeholder');
        var hint = newRow.querySelector('.row-img-hint');
        if (preview) {
            preview.src = '<?= base_url('uploads/persediaan/') ?>' + data.gambar;
            preview.classList.remove('hidden');
        }
        if (placeholder) placeholder.classList.add('hidden');
        if (hint) hint.classList.remove('hidden');
    }

    if (data.kategori_id) {
        var container = newRow.querySelector('.custom-dropdown-container');
        if (container && typeof container._selectValue === 'function') {
            container._selectValue(data.kategori_id);
        }
    }

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
        var namaVal = namaInput ? namaInput.value.trim() : '';
        var katVal = katHidden ? katHidden.value.trim() : '';

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

// Inisialisasi baris barang dari data tiket
document.addEventListener('DOMContentLoaded', function() {
    var initialItems = <?= json_encode($parsed['items'] ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    if (Array.isArray(initialItems) && initialItems.length > 0) {
        initialItems.forEach(function(item) {
            pengajuanAddRow(item);
        });
    } else {
        pengajuanAddRow();
    }
});
</script>
