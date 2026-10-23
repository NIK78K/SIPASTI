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
        <form method="post" action="<?= base_url('user_ticket/pengajuan_persediaan') ?>" onsubmit="return validPengajuan()" class="space-y-6">

            <!-- STEP 1: DAFTAR BARANG DIAJUKAN -->
            <div class="space-y-3">
                <label class="flex items-center gap-2 text-sm font-bold text-gray-800">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-900 text-white text-xs">1</span>
                    Barang yang Diajukan
                    <span class="text-red-500">*</span>
                </label>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Tulis barang yang <strong>belum ada</strong> di daftar/katalog persediaan.
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

<script>
function pengajuanAddRow() {
    var box = document.getElementById('pengajuanRows');
    var idx = box.querySelectorAll('.pengajuan-row').length + 1;
    var div = document.createElement('div');
    div.className = 'pengajuan-row rounded-2xl border-2 border-gray-200 p-4 space-y-3 bg-slate-50/50';
    div.innerHTML =
        '<div class="flex items-center justify-between">'
        + '<p class="text-xs font-extrabold tracking-wide text-blue-900">BARANG ' + idx + '</p>'
        + '<button type="button" onclick="pengajuanDelRow(this)" class="text-xs font-bold text-red-600 hover:text-red-700 transition">Hapus</button>'
        + '</div>'
        + '<input name="nama_barang[]" type="text" placeholder="Nama barang *  cth: Tinta printer Epson 003 hitam"'
        + ' class="w-full h-12 rounded-xl px-4 border-2 border-gray-200 focus:border-blue-800 focus:ring-4 focus:ring-blue-100 text-sm text-gray-800 font-medium transition-all bg-white">'
        + '<input name="keterangan[]" type="text" placeholder="Spesifikasi/keterangan (opsional)  cth: ukuran A4 80gr"'
        + ' class="w-full h-12 rounded-xl px-4 border-2 border-gray-200 focus:border-blue-800 focus:ring-4 focus:ring-blue-100 text-sm text-gray-800 font-medium transition-all bg-white">';
    box.appendChild(div);
}

function pengajuanDelRow(btn) {
    var box = document.getElementById('pengajuanRows');
    if (box.querySelectorAll('.pengajuan-row').length <= 1) {
        Swal.fire({ icon: 'warning', title: 'Minimal 1 barang', text: 'Pengajuan harus berisi minimal 1 barang.' });
        return;
    }
    btn.closest('.pengajuan-row').remove();
    box.querySelectorAll('.pengajuan-row').forEach(function (el, i) {
        el.querySelector('p').textContent = 'BARANG ' + (i + 1);
    });
}

function validPengajuan() {
    var adaNama = false;
    document.querySelectorAll('#pengajuanRows input[name="nama_barang[]"]').forEach(function (el) {
        if (el.value.trim() !== '') adaNama = true;
    });
    if (!adaNama) {
        Swal.fire({ icon: 'warning', title: 'Barang belum diisi', text: 'Isi minimal 1 nama barang yang diajukan.' });
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
    return true;
}

pengajuanAddRow();
</script>
