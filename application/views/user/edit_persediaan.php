<?php
$ticket  = $ticket ?? [];
$items   = $items ?? [];
$katalog = $katalog ?? $barang_list ?? [];
$kats    = $kategori_list ?? [];

// Prefill dari $ticket (kolom kebutuhan) + fallback parse jenis_kerusakan pola checkout.
$judul_saved = trim((string) ($ticket['kebutuhan'] ?? ''));
$keperluan_saved = '';
$unit_saved = '';
$tgl_saved = '';
if (!empty($ticket['jenis_kerusakan'])) {
    foreach (explode("\n", $ticket['jenis_kerusakan']) as $ln) {
        $ln = trim($ln);
        if (stripos($ln, 'Keperluan:') === 0) $keperluan_saved = trim(substr($ln, strlen('Keperluan:')));
        elseif (stripos($ln, 'Unit Kerja:') === 0) $unit_saved = trim(substr($ln, strlen('Unit Kerja:')));
        elseif (stripos($ln, 'Tanggal permintaan:') === 0) $tgl_saved = trim(substr($ln, strlen('Tanggal permintaan:')));
        elseif (stripos($ln, 'Tanggal dibutuhkan:') === 0) $tgl_saved = trim(substr($ln, strlen('Tanggal dibutuhkan:')));
    }
}
if ($judul_saved === '' && $keperluan_saved !== '') $judul_saved = $keperluan_saved;

// Normalisasi items existing: barang_id + jumlah + nama/satuan.
$items_norm = [];
foreach ((array) $items as $it) {
    $bid = (int) ($it['barang_id'] ?? 0);
    if ($bid <= 0) continue;
    $items_norm[] = [
        'barang_id' => $bid,
        'jumlah'    => max(1, (int) ($it['jumlah'] ?? $it['qty'] ?? 1)),
        'nama'      => $it['nama_barang_snapshot'] ?? $it['nama_barang'] ?? $it['nama'] ?? ('Barang #' . $bid),
        'kode'      => $it['kode_barang'] ?? $it['kode'] ?? '-',
        'satuan'    => $it['satuan_snapshot'] ?? $it['satuan'] ?? 'pcs',
    ];
}
$n_jenis = count($items_norm);
$n_qty = 0;
foreach ($items_norm as $r) $n_qty += (int) $r['jumlah'];
?>

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
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Edit Permintaan Barang Persediaan</h1>
            <p class="text-xs md:text-sm text-slate-500 mt-1">Perbarui daftar barang yang diminta, keperluan, atau unit kerja.</p>
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

    <!-- FORM EDIT PERMINTAAN -->
    <div class="bg-white rounded-3xl p-6 md:p-10 shadow-xl border border-gray-100">
        <form id="editPersediaanForm" method="post" enctype="multipart/form-data"
              action="<?= base_url('user_ticket/update_persediaan/' . (int) ($ticket['id'] ?? 0)) ?>"
              class="space-y-6" onsubmit="return validEditPersediaan()">

            <!-- STEP 1: DAFTAR BARANG DIMINTA -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm font-bold text-gray-800">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-900 text-white text-xs">1</span>
                        Daftar Barang Diminta
                        <span class="text-red-500">*</span>
                    </label>
                    <span id="infoRingkasBarang" class="text-xs font-semibold text-slate-500 bg-slate-100 px-3 py-1 rounded-full border border-slate-200">
                        <span id="infoJenis" class="text-blue-900 font-extrabold"><?= $n_jenis ?></span> jenis &bull; <span id="infoQty" class="text-blue-900 font-extrabold"><?= $n_qty ?></span> item
                    </span>
                </div>

                <div id="editItems" class="space-y-3"></div>

                <p id="emptyItems" class="hidden text-sm text-slate-500 bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl px-4 py-8 text-center">
                    Belum ada barang di daftar. Tambahkan barang dari katalog di bawah.
                </p>

                <!-- TAMBAH BARANG DARI KATALOG -->
                <div class="rounded-2xl border-2 border-dashed border-blue-200 bg-blue-50/40 p-4 sm:p-5 space-y-3 mt-4">
                    <div class="flex items-center gap-2 text-xs font-bold text-blue-900 uppercase tracking-wide">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Tambah Barang dari Katalog</span>
                    </div>

                    <div class="grid md:grid-cols-3 gap-3">
                        <div>
                            <label class="text-[11px] font-bold text-gray-600 mb-1 block">Filter Kategori</label>
                            <select id="addKategori"
                                    class="w-full h-12 rounded-xl px-4 border-2 border-gray-200 focus:border-blue-800 focus:ring-4 focus:ring-blue-100 text-gray-800 text-sm font-medium transition-all bg-white">
                                <option value="">── Semua kategori ──</option>
                                <?php foreach ((array) $kats as $k): ?>
                                    <?php $kid = is_object($k) ? $k->id : ($k['id'] ?? ''); ?>
                                    <?php $klabel = is_object($k) ? ($k->kode . ' - ' . $k->nama) : (($k['kode'] ?? '') . ' - ' . ($k['nama'] ?? '')); ?>
                                    <option value="<?= htmlspecialchars($kid) ?>"><?= htmlspecialchars($klabel) ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-[11px] font-bold text-gray-600 mb-1 block">Pilih Barang</label>
                            <select id="addBarang"
                                    class="w-full h-12 rounded-xl px-4 border-2 border-gray-200 focus:border-blue-800 focus:ring-4 focus:ring-blue-100 text-gray-800 text-sm font-medium transition-all bg-white">
                                <option value="">── Pilih barang ──</option>
                                <?php foreach ((array) $katalog as $b): ?>
                                    <?php
                                    $bid = (int) ($b['id'] ?? 0);
                                    $bkat = $b['kategori_id'] ?? '';
                                    $bkode = $b['kode_barang'] ?? '-';
                                    $bnama = $b['nama_barang'] ?? '-';
                                    $bsat = $b['satuan'] ?? 'pcs';
                                    $bsisa = (int) ($b['sisa'] ?? 0);
                                    ?>
                                    <option value="<?= $bid ?>" data-kat="<?= htmlspecialchars($bkat) ?>"
                                            data-nama="<?= htmlspecialchars($bnama) ?>" data-kode="<?= htmlspecialchars($bkode) ?>"
                                            data-satuan="<?= htmlspecialchars($bsat) ?>">
                                        <?= htmlspecialchars($bkode . ' - ' . $bnama . ' (sisa ' . $bsisa . ')') ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                            <p class="text-[10px] text-slate-500 mt-1">Angka sisa hanya referensi stok, semua barang tetap dapat diminta.</p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 pt-1">
                        <div class="flex items-center rounded-xl border-2 border-gray-200 overflow-hidden w-full sm:w-40 bg-white">
                            <button type="button" onclick="stepAdd(-1)" class="w-10 h-11 font-extrabold text-slate-600 hover:bg-slate-100 transition flex items-center justify-center">−</button>
                            <input id="addQty" type="number" value="1" min="1"
                                   class="flex-1 h-11 text-center text-sm font-bold text-gray-800 border-x-2 border-gray-200 focus:outline-none">
                            <button type="button" onclick="stepAdd(1)" class="w-10 h-11 font-extrabold text-slate-600 hover:bg-slate-100 transition flex items-center justify-center">+</button>
                        </div>
                        <button type="button" onclick="tambahDariKatalog()"
                                class="flex-1 h-11 rounded-xl bg-blue-900 hover:bg-blue-950 text-white font-bold text-sm transition-all shadow-md shadow-blue-900/25 active:scale-[.99] flex items-center justify-center gap-2">
                            <span>+ Tambah ke Daftar</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- STEP 2: KEPERLUAN -->
            <div class="space-y-2 pt-5 border-t-2 border-gray-100">
                <label class="flex items-center gap-2 text-sm font-bold text-gray-800">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-900 text-white text-xs">2</span>
                    Keperluan
                    <span class="text-red-500">*</span>
                </label>
                <input name="keperluan" id="fKeperluan" type="text" required
                       value="<?= htmlspecialchars($keperluan_saved !== '' ? $keperluan_saved : $judul_saved) ?>"
                       placeholder="cth: Kebutuhan ATK bagian umum"
                       class="w-full h-14 rounded-xl px-5 border-2 border-gray-200 focus:border-blue-800 focus:ring-4 focus:ring-blue-100 text-gray-800 font-medium transition-all hover:border-gray-300">
            </div>

            <!-- STEP 3: UNIT KERJA + TANGGAL PERMINTAAN -->
            <div class="grid md:grid-cols-2 gap-4 pt-5 border-t-2 border-gray-100">
                <div>
                    <label class="text-xs font-bold text-gray-700">Unit Kerja</label>
                    <select name="unit_kerja"
                        class="mt-1 w-full h-14 rounded-xl px-5 border-2 border-gray-200 focus:border-blue-800 focus:ring-4 focus:ring-blue-100 text-gray-800 font-medium transition-all bg-white hover:border-gray-300">
                        <option value="">-- Pilih unit kerja --</option>
                        <?php foreach (($unit_kerja_list ?? []) as $uk): ?>
                            <option value="<?= htmlspecialchars($uk) ?>" <?= (string) ($unit_saved !== '' ? $unit_saved : ($unit_kerja_default ?? '')) === (string) $uk ? 'selected' : '' ?>><?= htmlspecialchars($uk) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700">Tanggal Permintaan</label>
                    <input name="tanggal_permintaan" id="fTanggalButuh" type="date" min="<?= date('Y-m-d') ?>"
                           value="<?= htmlspecialchars($tgl_saved) ?>"
                           class="mt-1 w-full h-14 rounded-xl px-5 border-2 border-gray-200 focus:border-blue-800 focus:ring-4 focus:ring-blue-100 text-gray-800 font-medium transition-all hover:border-gray-300">
                </div>
            </div>

            <!-- FOTO PENDUKUNG (OPSIONAL) -->
            <?php if (!empty($ticket['foto'])): ?>
            <div class="space-y-2 pt-5 border-t-2 border-gray-100">
                <label class="text-xs font-bold text-gray-700">Foto Pendukung (Opsional)</label>
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-2xl border border-gray-200">
                    <img src="<?= base_url('uploads/tiket/' . $ticket['foto']) ?>"
                         class="h-16 w-16 object-cover rounded-xl border border-gray-200 flex-shrink-0">
                    <div>
                        <p class="text-xs font-bold text-gray-700 mb-0.5">Foto terlampir saat ini</p>
                        <a href="<?= base_url('uploads/tiket/' . $ticket['foto']) ?>" target="_blank"
                           class="text-xs text-blue-600 hover:underline font-semibold">Lihat foto penuh &rarr;</a>
                        <p class="text-[11px] text-gray-400 mt-1">Upload foto baru di bawah jika ingin mengganti.</p>
                    </div>
                </div>
                <input type="file" name="foto" accept="image/*"
                       class="w-full text-xs text-gray-600 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-100 file:text-blue-900 hover:file:bg-blue-200 cursor-pointer pt-1">
            </div>
            <?php endif; ?>

            <!-- ACTION BUTTONS -->
            <div class="flex gap-4 pt-6 border-t-2 border-gray-100">
                <button type="button" onclick="confirmSimpanPersediaan()"
                        class="flex-1 h-14 rounded-xl bg-blue-900 hover:bg-blue-950 text-white font-extrabold text-sm transition-all shadow-lg shadow-blue-900/25 active:scale-[.99] flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </button>
                <button type="button" onclick="confirmBatalPersediaan()"
                        class="h-14 px-8 rounded-xl border-2 border-gray-300 hover:bg-gray-100 hover:border-gray-400 text-gray-700 font-semibold transition-all">
                    Batal
                </button>
            </div>

        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function () {
    'use strict';

    var editItems = <?= json_encode($items_norm, JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    if (!Array.isArray(editItems)) editItems = [];

    function esc(s) {
        return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    }

    function totals() {
        var q = 0;
        editItems.forEach(function (it) { q += parseInt(it.jumlah || 0, 10) || 0; });
        var elJenis = document.getElementById('infoJenis');
        var elQty   = document.getElementById('infoQty');
        var elEmpty = document.getElementById('emptyItems');
        if (elJenis) elJenis.textContent = editItems.length;
        if (elQty) elQty.textContent = q;
        if (elEmpty) elEmpty.classList.toggle('hidden', editItems.length > 0);
    }

    function render() {
        var box = document.getElementById('editItems');
        if (!box) return;
        box.innerHTML = editItems.map(function (it, i) {
            return '<div class="rounded-2xl border-2 border-gray-200 p-4 space-y-2 bg-slate-50/60 transition-all hover:border-slate-300">'
                + '<div class="flex items-start justify-between gap-2">'
                + '<div>'
                + '<span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-blue-100 text-blue-800 border border-blue-200">' + esc(it.kode) + '</span>'
                + '<p class="font-bold text-sm text-gray-800 leading-snug mt-1.5">' + esc(it.nama) + '</p>'
                + '</div>'
                + '<button type="button" onclick="editHapus(' + i + ')" class="text-xs font-bold text-red-600 hover:text-red-700 transition">Hapus</button>'
                + '</div>'
                + '<div class="flex items-center justify-between pt-1 border-t border-gray-200/60">'
                + '<span class="text-xs text-slate-500 font-medium">Satuan: <strong class="text-slate-700">' + esc(it.satuan) + '</strong></span>'
                + '<div class="flex items-center rounded-xl border-2 border-gray-200 bg-white overflow-hidden">'
                + '<button type="button" onclick="editStep(' + i + ', -1)" class="w-9 h-9 rounded-l-lg bg-slate-50 hover:bg-slate-100 font-extrabold text-slate-700 flex items-center justify-center transition">−</button>'
                + '<input type="number" min="1" name="items[' + it.barang_id + ']" value="' + (parseInt(it.jumlah, 10) || 1) + '" onchange="editQty(' + i + ', this.value)"'
                + ' class="w-14 h-9 text-center text-sm font-extrabold text-blue-900 border-x-2 border-gray-200 focus:outline-none">'
                + '<button type="button" onclick="editStep(' + i + ', 1)" class="w-9 h-9 rounded-r-lg bg-slate-50 hover:bg-slate-100 font-extrabold text-slate-700 flex items-center justify-center transition">+</button>'
                + '</div>'
                + '</div>'
                + '</div>';
        }).join('');
        totals();
    }

    window.editStep = function (i, d) {
        var v = (parseInt(editItems[i].jumlah, 10) || 1) + d;
        if (v < 1) v = 1;
        editItems[i].jumlah = v;
        render();
    };

    window.editQty = function (i, v) {
        v = parseInt(v, 10) || 1;
        if (v < 1) v = 1;
        editItems[i].jumlah = v;
        render();
    };

    window.editHapus = function (i) {
        if (editItems.length <= 1) {
            Swal.fire({ icon: 'warning', title: 'Minimal 1 barang', text: 'Daftar permintaan harus berisi minimal 1 barang.' });
            return;
        }
        editItems.splice(i, 1);
        render();
    };

    window.stepAdd = function (d) {
        var inp = document.getElementById('addQty');
        var v = (parseInt(inp.value || '1', 10) || 1) + d;
        if (v < 1) v = 1;
        inp.value = v;
    };

    var addKat = document.getElementById('addKategori');
    var addBrg = document.getElementById('addBarang');
    if (addKat && addBrg) {
        addKat.addEventListener('change', function () {
            var k = this.value;
            Array.prototype.forEach.call(addBrg.options, function (o) {
                if (!o.value) return;
                o.style.display = (!k || o.getAttribute('data-kat') === k) ? '' : 'none';
            });
            addBrg.value = '';
        });
    }

    window.tambahDariKatalog = function () {
        var opt = addBrg.options[addBrg.selectedIndex];
        if (!opt || !opt.value) {
            Swal.fire({ icon: 'warning', title: 'Pilih barang dulu', text: 'Pilih barang dari katalog sebelum menambah.' });
            return;
        }
        var bid = parseInt(opt.value, 10);
        var q = parseInt(document.getElementById('addQty').value || '1', 10) || 1;
        if (q < 1) q = 1;
        var ada = editItems.find(function (it) { return parseInt(it.barang_id, 10) === bid; });
        if (ada) {
            ada.jumlah = (parseInt(ada.jumlah, 10) || 0) + q;
        } else {
            editItems.push({
                barang_id: bid,
                jumlah: q,
                nama: opt.getAttribute('data-nama') || opt.text,
                kode: opt.getAttribute('data-kode') || '-',
                satuan: opt.getAttribute('data-satuan') || 'pcs'
            });
        }
        render();
    };

    window.validEditPersediaan = function () {
        if (!editItems.length) {
            Swal.fire({ icon: 'warning', title: 'Daftar barang kosong', text: 'Tambah dulu barang yang diminta.' });
            return false;
        }
        if (!document.getElementById('fKeperluan').value.trim()) {
            Swal.fire({ icon: 'warning', title: 'Keperluan wajib diisi', text: 'Tulis keperluan permintaan barang.' });
            return false;
        }
        var tglEl = document.getElementById('fTanggalButuh');
        if (tglEl && tglEl.value) {
            var now = new Date();
            var today = now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0') + '-' + String(now.getDate()).padStart(2, '0');
            if (tglEl.value < today) {
                Swal.fire({ icon: 'warning', title: 'Tanggal tidak valid', text: 'Tanggal permintaan tidak boleh mundur. Pilih hari ini atau tanggal ke depan.' });
                return false;
            }
        }
        return true;
    };

    window.confirmSimpanPersediaan = function () {
        if (!window.validEditPersediaan()) return;
        Swal.fire({
            title: 'Simpan Perubahan?',
            text: 'Pastikan daftar barang dan info kebutuhan sudah benar.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Periksa Lagi',
            confirmButtonColor: '#1e3a8a',
            cancelButtonColor: '#6b7280',
            reverseButtons: true
        }).then(function (r) {
            if (!r.isConfirmed) return;
            Swal.fire({ title: 'Menyimpan...', html: 'Mohon tunggu sebentar', allowOutsideClick: false, didOpen: function () { Swal.showLoading(); } });
            setTimeout(function () { document.getElementById('editPersediaanForm').submit(); }, 600);
        });
    };

    window.confirmBatalPersediaan = function () {
        Swal.fire({
            title: 'Batalkan Perubahan?',
            text: 'Perubahan yang belum disimpan akan hilang.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Lanjutkan Edit',
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            reverseButtons: true
        }).then(function (r) {
            if (r.isConfirmed) window.location.href = "<?= base_url('user_ticket/history') ?>";
        });
    };

    render();
})();
</script>
