<div class="max-w-5xl mx-auto mt-16 mb-24 space-y-14">

    <!-- ================= HEADER ================= -->
    <div class="text-center">
        <h1 class="text-4xl font-extrabold tracking-tight text-[#0B2E6B]">
            Permasalahan IT Lainnya
        </h1>
        <p class="mt-3 text-slate-500 max-w-xl mx-auto">
            Ajukan tiket untuk permasalahan IT yang tidak termasuk kategori 
            permintaan atau gangguan
        </p>
    </div>

    <!-- ================= GUIDE ================= -->
    <div class="grid md:grid-cols-3 gap-6">

        <div class="p-6 rounded-2xl
                    bg-purple-50 border border-purple-200">
            <div class="text-purple-600 font-bold mb-2">📌 Contoh Kasus</div>
            <p class="text-sm text-purple-700">
                Konsultasi IT, pertanyaan teknis, bantuan konfigurasi,
                atau kendala khusus lainnya
            </p>
        </div>

        <div class="p-6 rounded-2xl
                    bg-indigo-50 border border-indigo-200">
            <div class="text-indigo-600 font-bold mb-2">🎯 Pilih Kategori</div>
            <p class="text-sm text-indigo-700">
                Pilih kategori yang sesuai dengan permasalahan Anda
            </p>
        </div>

        <div class="p-6 rounded-2xl
                    bg-blue-50 border border-blue-200">
            <div class="text-blue-600 font-bold mb-2">⚡ Respon Cepat</div>
            <p class="text-sm text-blue-700">
                Tim IT akan merespon tiket Anda sesuai prioritas
            </p>
        </div>

    </div>

    <!-- ================= FORM CARD ================= -->
    <div class="bg-white/90 backdrop-blur
                border border-slate-200
                rounded-3xl p-10
                shadow-[0_25px_50px_rgba(0,0,0,0.15)]">

        <form id="lainnyaForm"
              method="post"
              action="<?= base_url('user_ticket/store') ?>"
              enctype="multipart/form-data"
              class="space-y-7">

            <!-- KATEGORI -->
            <div>
                <label class="text-sm font-semibold text-slate-700">
                    Kategori <span class="text-red-500">*</span>
                </label>
              <select name="kategori" id="kategori" required
        class="mt-2 w-full h-12 rounded-xl px-4
               border border-slate-300
               focus:ring-4 focus:ring-purple-500/20
               focus:border-purple-600 transition">
    <option value="">Pilih kategori</option>
    <?php foreach ($kategori as $k): ?>
        <option value="<?= $k->slug ?>" data-id="<?= $k->id ?>">
            <?= $k->nama_kategori ?>
        </option>
    <?php endforeach ?>
</select>


            <!-- SUB KATEGORI -->
            <div id="subKategoriWrapper" style="display:none;">
                <label class="text-sm font-semibold text-slate-700">
                    Sub Kategori <span class="text-red-500">*</span>
                </label>
                <select name="sub_kategori_id" id="subKategori" required
                        class="mt-2 w-full h-12 rounded-xl px-4
                               border border-slate-300
                               focus:ring-4 focus:ring-purple-500/20
                               focus:border-purple-600 transition">
                    <option value="">Pilih sub kategori</option>
                </select>
            </div>

          <!-- PERANGKAT -->
<div>
    <label class="text-sm font-semibold text-slate-700">
        Perangkat (Opsional)
    </label>

    <input type="text"
           name="perangkat"
           placeholder="Contoh: PC Kantor, Printer Epson, AC Ruang TU"
           class="mt-2 w-full h-12 rounded-xl px-4
                  border border-slate-300
                  focus:ring-4 focus:ring-purple-500/20">
</div>


            <!-- LOKASI -->
            <div>
                <label class="text-sm font-semibold text-slate-700">
                    Lokasi <span class="text-red-500">*</span>
                </label>
                <input type="text" name="lokasi" required
                       placeholder="Contoh: Ruang TU, Lantai 2"
                       class="mt-2 w-full h-12 rounded-xl px-4
                              border border-slate-300
                              focus:ring-4 focus:ring-purple-500/20">
            </div>

            <!-- DESKRIPSI -->
            <div>
                <label class="text-sm font-semibold text-slate-700">
                    Deskripsi Permasalahan <span class="text-red-500">*</span>
                </label>
                <textarea name="jenis_kerusakan" rows="4" required
                          placeholder="Jelaskan permasalahan Anda secara detail"
                          class="mt-2 w-full rounded-xl px-4 py-3
                                 border border-slate-300
                                 focus:ring-4 focus:ring-purple-500/20"></textarea>
            </div>

            <!-- FOTO -->
            <div>
                <label class="text-sm font-semibold text-slate-700">
                    Foto Pendukung (Opsional)
                </label>
                <input type="file" name="foto"
                       class="mt-2 w-full h-12 rounded-xl px-4
                              border border-slate-300">
            </div>

            <!-- ACTION -->
            <div class="flex gap-4 pt-6">
                <button type="button" id="btnSubmit"
                        class="flex-1 h-12 rounded-xl
                               bg-gradient-to-r from-purple-600 to-purple-700
                               hover:from-purple-700 hover:to-purple-800
                               text-white font-semibold
                               shadow-lg shadow-purple-500/30
                               transition-all">
                    Kirim Tiket
                </button>

                <button type="button" id="btnCancel"
                        class="h-12 px-6 rounded-xl
                               border border-slate-300
                               text-slate-600 hover:bg-slate-100">
                    Batal
                </button>
            </div>

        </form>
    </div>
</div>

<!-- ================= SWEET ALERT ================= -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const form = document.getElementById('lainnyaForm');
const btnSubmit = document.getElementById('btnSubmit');
const btnCancel = document.getElementById('btnCancel');
const kategoriSelect = document.getElementById('kategori');
const subKategoriSelect = document.getElementById('subKategori');
const subKategoriWrapper = document.getElementById('subKategoriWrapper');

/* ================= LOAD SUB KATEGORI ================= */
kategoriSelect.addEventListener('change', function () {

    const selectedOption = this.options[this.selectedIndex];
    const kategoriId = selectedOption.dataset.id; // ⬅️ AMBIL ID
    const kategoriSlug = this.value;               // ⬅️ SLUG TETAP ADA

    if (!kategoriId) {
        subKategoriWrapper.style.display = 'none';
        subKategoriSelect.innerHTML = '<option value="">Pilih sub kategori</option>';
        subKategoriSelect.required = false;
        return;
    }

    fetch(`<?= base_url('user/get_sub_kategori/') ?>${kategoriId}`)
        .then(response => response.json())
        .then(data => {

            if (!data.length) {
                subKategoriWrapper.style.display = 'none';
                subKategoriSelect.required = false;
                return;
            }

            let options = '<option value="">Pilih sub kategori</option>';

            data.forEach(sub => {
                options += `<option value="${sub.id}">${sub.nama_sub_kategori}</option>`;
            });

            subKategoriSelect.innerHTML = options;
            subKategoriWrapper.style.display = 'block';
            subKategoriSelect.required = true;
        })
        .catch(() => {
            Swal.fire('Error', 'Gagal memuat sub kategori', 'error');
        });
});

/* ================= SUBMIT ================= */
btnSubmit.addEventListener('click', () => {

    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    Swal.fire({
        title: 'Kirim tiket?',
        text: 'Pastikan data yang Anda isi sudah benar',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Kirim',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#9333ea',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {

            Swal.fire({
                title: 'Mengirim tiket...',
                text: 'Mohon tunggu',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            setTimeout(() => {
                form.submit();
            }, 500);
        }
    });
});

/* ================= CANCEL ================= */
btnCancel.addEventListener('click', () => {
    Swal.fire({
        title: 'Batalkan tiket?',
        text: 'Data yang sudah diisi akan hilang',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Batalkan',
        cancelButtonText: 'Lanjutkan',
        confirmButtonColor: '#ef4444',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "<?= base_url('user/dashboard') ?>";
        }
    });
});
</script>
