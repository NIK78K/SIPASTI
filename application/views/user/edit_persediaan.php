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
        elseif (stripos($ln, 'Tanggal dibutuhkan:') === 0) $tgl_saved = trim(substr($ln, strlen('Tanggal dibutuhkan:')));
    }
}
if ($judul_saved === '' && $keperluan_saved !== '') $judul_saved = $keperluan_saved;

// Normalisasi items existing: barang_id + jumlah + nama/satuan (snapshot dulu).
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

<style>
.edit-page { background: #f3f6f9; border-radius: 18px; padding-top: 14px; padding-bottom: 28px; color: #1e293b; }
.edit-header { display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
.edit-logo { width: 54px; height: 54px; display: grid; place-items: center; border-radius: 8px; background: linear-gradient(145deg, #dbeafe, #eff6ff); color: #2563eb; font-size: 34px; font-weight: 900; font-style: italic; flex-shrink: 0; }
.stat-card { position: relative; overflow: hidden; }
.stat-card::before { content: ""; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(to right, #172554, #1d4ed8); }
.edit-card { border-radius: 12px; box-shadow: 0 1px 4px rgba(15,23,42,.08); }
.edit-item { background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 4px rgba(15,23,42,.08); transition: box-shadow .15s; }
.edit-item:hover { box-shadow: 0 8px 18px rgba(15,23,42,.12); }
@media (max-width: 640px) { .edit-header { flex-direction: column; align-items: stretch; } }
</style>
<div class="edit-page max-w-5xl mx-auto mt-6 mb-24 space-y-5 px-4">

    <!-- BACK -->
    <div>
        <a href="<?= base_url('user_ticket/history') ?>"
           class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Riwayat
        </a>
    </div>

    <!-- HEADER -->
    <div class="edit-header rounded-xl border border-slate-200 bg-white px-5 py-4 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="edit-logo">P</div>
            <div class="text-left">
                <p class="text-xs font-bold uppercase tracking-[.18em] text-blue-900">SIPASTI</p>
                <h1 class="text-xl md:text-2xl font-extrabold tracking-tight text-slate-800">Edit Permintaan Barang Persediaan</h1>
                <p class="mt-1 text-xs text-slate-500">Atur jumlah, hapus, atau tambah barang. Perubahan tersimpan saat tombol Simpan ditekan.</p>
            </div>
        </div>
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-100 text-slate-600 text-xs font-mono flex-shrink-0">
            <?= htmlspecialchars($ticket['kode_tiket'] ?? '-') ?>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold
                         <?= ($ticket['status'] ?? '') === 'Menunggu' ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700' ?>">
                <?= ($ticket['status'] ?? '') === 'On Progress' ? 'Diproses' : htmlspecialchars($ticket['status'] ?? '-') ?>
            </span>
        </div>
    </div>

    <?php if ($this->session->flashdata('error')): ?>
    <div class="flex items-center gap-3 px-5 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm font-medium">
        <span><?= $this->session->flashdata('error') ?></span>
    </div>
    <?php endif ?>

    <!-- INFO RINGKAS -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
        <div class="stat-card bg-white rounded-xl border border-slate-200 shadow-sm p-4 text-center">
            <div class="text-2xl font-extrabold text-blue-900" id="infoJenis"><?= $n_jenis ?></div>
            <div class="text-xs text-slate-500 font-semibold">Jenis barang</div>
        </div>
        <div class="stat-card bg-white rounded-xl border border-slate-200 shadow-sm p-4 text-center">
            <div class="text-2xl font-extrabold text-blue-900" id="infoQty"><?= $n_qty ?></div>
            <div class="text-xs text-slate-500 font-semibold">Total item</div>
        </div>
        <div class="stat-card col-span-2 md:col-span-1 bg-white rounded-xl border border-slate-200 shadow-sm p-4 text-center">
            <div class="text-2xl font-extrabold text-blue-900"><?= count($katalog) ?></div>
            <div class="text-xs text-slate-500 font-semibold">Barang aktif di katalog</div>
        </div>
    </div>

    <!-- FORM -->
    <div class="edit-card bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="h-1.5 bg-gradient-to-r from-blue-950 to-blue-700"></div>
        <div class="p-6 md:p-8">
        <form id="editPersediaanForm" method="post" enctype="multipart/form-data"
              action="<?= base_url('user_ticket/update_persediaan/' . (int) ($ticket['id'] ?? 0)) ?>"
              class="space-y-6" onsubmit="return validEditPersediaan()">

            <!-- DAFTAR BARANG -->
            <div class="space-y-2">
                <label class="flex items-center gap-2 text-sm font-bold text-gray-800">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-900 text-white text-xs">1</span>
                    Daftar Barang
                    <span class="text-red-500">*</span>
                </label>
                <div id="editItems" class="space-y-3"></div>
                <p id="emptyItems" class="hidden text-sm text-slate-500 bg-slate-50 border border-dashed border-slate-200 rounded-xl px-4 py-6 text-center">
                    Belum ada barang. Tambah dari katalog di bawah.
                </p>
            </div>

            <!-- TAMBAH DARI KATALOG -->
            <div class="space-y-3 pt-4 border-t-2 border-gray-100">
                <label class="flex items-center gap-2 text-sm font-bold text-gray-800">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-900 text-white text-xs">2</span>
                    Tambah Barang dari Katalog
                </label>
                <div class="grid md:grid-cols-3 gap-3">
                    <div>
                        <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Kategori</label>
                        <select id="addKategori"
                                class="w-full h-12 rounded-xl px-4 border-2 border-gray-200 focus:border-blue-800 focus:ring-4 focus:ring-blue-100 text-gray-800 text-sm font-medium transition-all">
                            <option value="">── Semua kategori ──</option>
                            <?php foreach ((array) $kats as $k): ?>
                            <?php $kid = is_object($k) ? $k->id : ($k['id'] ?? ''); ?>
                            <?php $klabel = is_object($k) ? ($k->kode . ' - ' . $k->nama) : (($k['kode'] ?? '') . ' - ' . ($k['nama'] ?? '')); ?>
                            <option value="<?= htmlspecialchars($kid) ?>"><?= htmlspecialchars($klabel) ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Barang</label>
                        <select id="addBarang"
                                class="w-full h-12 rounded-xl px-4 border-2 border-gray-200 focus:border-blue-800 focus:ring-4 focus:ring-blue-100 text-gray-800 text-sm font-medium transition-all">
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
                        <p class="text-[11px] text-slate-500 mt-1">Angka sisa hanya info, semua barang bisa diminta.</p>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="flex items-center rounded-xl border-2 border-gray-200 overflow-hidden w-full sm:w-40">
                        <button type="button" onclick="stepAdd(-1)" class="w-10 h-12 font-extrabold text-slate-600 hover:bg-slate-100 transition">−</button>
                        <input id="addQty" type="number" value="1" min="1"
                               class="flex-1 h-12 text-center text-sm font-bold text-gray-800 border-x-2 border-gray-200 focus:outline-none">
                        <button type="button" onclick="stepAdd(1)" class="w-10 h-12 font-extrabold text-slate-600 hover:bg-slate-100 transition">+</button>
                    </div>
                    <button type="button" onclick="tambahDariKatalog()"
                            class="flex-1 h-12 rounded-xl bg-blue-900 hover:bg-blue-950 text-white font-bold text-sm transition-all shadow-lg shadow-blue-900/25">
                        + Tambah ke Daftar
                    </button>
                </div>
            </div>

            <!-- INFO KEBUTUHAN -->
            <div class="space-y-4 pt-4 border-t-2 border-gray-100">
                <label class="flex items-center gap-2 text-sm font-bold text-gray-800">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-900 text-white text-xs">3</span>
                    Info Kebutuhan
                </label>
                <div>
                    <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Judul / Keperluan <span class="text-red-500">*</span></label>
                    <input name="keperluan" id="fKeperluan" type="text" required
                           value="<?= htmlspecialchars($keperluan_saved !== '' ? $keperluan_saved : $judul_saved) ?>"
                           placeholder="cth: Kebutuhan ATK bagian umum"
                           class="w-full h-14 rounded-xl px-5 border-2 border-gray-200 focus:border-blue-800 focus:ring-4 focus:ring-blue-100 text-gray-800 transition-all placeholder:text-gray-400">
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Unit kerja</label>
                        <input name="unit_kerja" type="text" value="<?= htmlspecialchars($unit_saved) ?>" placeholder="cth: IPDS"
                               class="w-full h-12 rounded-xl px-4 border-2 border-gray-200 focus:border-blue-800 focus:ring-4 focus:ring-blue-100 text-gray-800 transition-all">
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Tanggal dibutuhkan</label>
                        <input name="tanggal_dibutuhkan" id="fTanggalButuh" type="date" min="<?= date('Y-m-d') ?>" value="<?= htmlspecialchars($tgl_saved) ?>"
                               class="w-full h-12 rounded-xl px-4 border-2 border-gray-200 focus:border-blue-800 focus:ring-4 focus:ring-blue-100 text-gray-800 transition-all">
                    </div>
                </div>
                <div>
                    <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Foto pendukung <span class="text-gray-400 font-normal">(opsional)</span></label>
                    <?php if (!empty($ticket['foto'])): ?>
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-200 mb-2">
                        <img src="<?= base_url('uploads/tiket/' . $ticket['foto']) ?>"
                             class="h-20 w-20 object-cover rounded-lg border border-gray-200 flex-shrink-0">
                        <div>
                            <p class="text-xs font-semibold text-gray-600 mb-1">Foto saat ini</p>
                            <a href="<?= base_url('uploads/tiket/' . $ticket['foto']) ?>" target="_blank"
                               class="text-xs text-blue-600 hover:underline">Lihat ukuran penuh</a>
                            <p class="text-xs text-gray-400 mt-1">Upload foto baru untuk mengganti</p>
                        </div>
                    </div>
                    <?php endif ?>
                    <input type="file" name="foto" accept="image/*"
                           class="w-full h-14 px-5 border-2 border-gray-200 rounded-xl
                                  file:mr-4 file:py-2.5 file:px-5 file:rounded-lg file:border-0
                                  file:bg-blue-900 file:text-white file:font-semibold
                                   file:cursor-pointer hover:file:bg-blue-950
                                  focus:border-blue-800 focus:ring-4 focus:ring-blue-100
                                  transition-all cursor-pointer">
                </div>
            </div>

            <!-- TOMBOL -->
            <div class="flex gap-4 pt-6 border-t-2 border-gray-100">
                <button type="button" onclick="confirmSimpanPersediaan()"
                        class="flex-1 h-14 rounded-xl bg-blue-900 hover:bg-blue-950 text-white font-bold text-sm
                               shadow-lg shadow-blue-900/25 hover:shadow-xl transition-all duration-300 active:scale-[.99]
                               flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </button>
                <button type="button" onclick="confirmBatalPersediaan()"
                        class="h-14 px-8 rounded-xl bg-white border-2 border-gray-200 hover:border-gray-300
                               text-slate-600 font-bold text-sm transition-all">
                    Batal
                </button>
            </div>

        </form>
        </div>
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
        document.getElementById('infoJenis').textContent = editItems.length;
        document.getElementById('infoQty').textContent = q;
        document.getElementById('emptyItems').classList.toggle('hidden', editItems.length > 0);
    }

    function render() {
        var box = document.getElementById('editItems');
        box.innerHTML = editItems.map(function (it, i) {
            return '<div class="edit-item overflow-hidden">'
                + '<div class="h-1.5 bg-gradient-to-r from-blue-950 to-blue-700"></div>'
                + '<div class="flex flex-col sm:flex-row sm:items-center gap-3 p-4">'
                + '<div class="flex-1 min-w-0"><p class="font-mono text-[11px] text-slate-500">' + esc(it.kode) + '</p>'
                + '<p class="font-bold text-sm text-gray-800 leading-snug">' + esc(it.nama) + '</p></div>'
                + '<div class="flex items-center gap-2">'
                + '<button type="button" onclick="editStep(' + i + ', -1)" class="w-9 h-10 rounded-lg bg-slate-100 hover:bg-slate-200 font-extrabold text-slate-600">−</button>'
                + '<input type="number" min="1" name="items[' + it.barang_id + ']" value="' + (parseInt(it.jumlah, 10) || 1) + '" onchange="editQty(' + i + ', this.value)"'
                + ' class="w-16 h-10 text-center text-sm font-extrabold text-gray-800 border-2 border-gray-200 rounded-lg focus:outline-none">'
                + '<span class="text-xs text-slate-500 w-10">' + esc(it.satuan) + '</span>'
                + '<button type="button" onclick="editStep(' + i + ', 1)" class="w-9 h-10 rounded-lg bg-slate-100 hover:bg-slate-200 font-extrabold text-slate-600">+</button>'
                + '<button type="button" onclick="editHapus(' + i + ')" class="w-10 h-10 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 font-bold" title="Hapus">×</button>'
                + '</div></div></div>';
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
    if (addKat) addKat.addEventListener('change', function () {
        var k = this.value;
        Array.prototype.forEach.call(addBrg.options, function (o) {
            if (!o.value) return;
            o.style.display = (!k || o.getAttribute('data-kat') === k) ? '' : 'none';
        });
        addBrg.value = '';
    });

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
        if (ada) ada.jumlah = (parseInt(ada.jumlah, 10) || 0) + q;
        else editItems.push({
            barang_id: bid,
            jumlah: q,
            nama: opt.getAttribute('data-nama') || opt.text,
            kode: opt.getAttribute('data-kode') || '-',
            satuan: opt.getAttribute('data-satuan') || 'pcs'
        });
        render();
    };

    window.validEditPersediaan = function () {
        if (!editItems.length) {
            Swal.fire({ icon: 'warning', title: 'Daftar barang kosong', text: 'Tambah dulu barang yang dibutuhkan.' });
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
                Swal.fire({ icon: 'warning', title: 'Tanggal tidak valid', text: 'Tanggal dibutuhkan tidak boleh mundur. Pilih hari ini atau tanggal ke depan.' });
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
