<div class="max-w-4xl mx-auto mt-16 mb-24 space-y-12">


    <!-- ================= JUDUL ================= -->
    <div class="relative">
        <div class="absolute -top-6 -left-6 w-24 h-24
                    bg-blue-100 rounded-full blur-3xl opacity-60"></div>

        <h1 class="relative text-3xl font-extrabold tracking-tight
                   text-[#0B2E6B]">
            Buat Tiket IT
        </h1>
        <p class="relative text-sm text-slate-600 mt-2 max-w-xl">
            Laporkan masalah atau kebutuhan IT Anda secara terstruktur,
            aman, dan terdokumentasi
        </p>
    </div>
<!-- ================= PANDUAN ================= -->
<div class="relative rounded-2xl
            bg-gradient-to-br from-white via-blue-50/50 to-white
            border border-blue-100
            p-7
            shadow-[0_10px_30px_rgba(13,110,253,0.12)]
            transition-all">

    <!-- header -->
    <div class="flex items-center gap-2 mb-6">
        <div class="w-2 h-2 rounded-full bg-[#0D6EFD]"></div>
        <h3 class="text-sm font-semibold text-[#1557C0]">
            Panduan Pembuatan Tiket
        </h3>
    </div>

    <div class="grid md:grid-cols-3 gap-6 text-sm text-slate-600">

        <!-- ===== KERUSAKAN ===== -->
        <div class="group p-4 rounded-xl
                    hover:bg-blue-50
                    transition-all">

            <div class="flex items-center gap-3 mb-2">

                <!-- icon kerusakan -->
                <div class="w-9 h-9 rounded-lg
                            bg-orange-100 text-orange-600
                            flex items-center justify-center
                            group-hover:scale-110 transition">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-5 h-5"
                         fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 9v3m0 3h.01M4.93 19h14.14c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.2 16c-.77 1.33.19 3 1.73 3z"/>
                    </svg>
                </div>

                <div class="font-semibold text-[#0B2E6B]">
                    Kerusakan Barang IT
                </div>
            </div>

            <p class="text-xs ml-12">
                PC, Laptop, Internet, Software
            </p>
        </div>

        <!-- ===== PENDAMPING ===== -->
        <div class="group p-4 rounded-xl
                    hover:bg-blue-50
                    transition-all">

            <div class="flex items-center gap-3 mb-2">

                <!-- icon pendamping -->
                <div class="w-9 h-9 rounded-lg
                            bg-blue-100 text-blue-600
                            flex items-center justify-center
                            group-hover:scale-110 transition">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-5 h-5"
                         fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 015.356-1.857
                                 M15 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>

                <div class="font-semibold text-[#0B2E6B]">
                    Pendamping IT
                </div>
            </div>

            <p class="text-xs ml-12">
                Seminar, Operator, Proyektor
            </p>
        </div>

        <!-- ===== LAYANAN ===== -->
        <div class="group p-4 rounded-xl
                    hover:bg-blue-50
                    transition-all">

            <div class="flex items-center gap-3 mb-2">

                <!-- icon layanan -->
                <div class="w-9 h-9 rounded-lg
                            bg-indigo-100 text-indigo-600
                            flex items-center justify-center
                            group-hover:scale-110 transition">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-5 h-5"
                         fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h5l5 5v9a2 2 0 01-2 2z"/>
                    </svg>
                </div>

                <div class="font-semibold text-[#0B2E6B]">
                    Layanan IT
                </div>
            </div>

            <p class="text-xs ml-12">
                Zoom, Akses Sistem, Akun IT
            </p>
        </div>

    </div>
</div>

<!-- ================= FORM CARD ================= -->
<div class="relative rounded-2xl
            bg-white
            border border-blue-100
            p-10
            shadow-[0_25px_45px_rgba(13,110,253,0.18)]
            transition-all">

    <!-- accent -->
    <div class="absolute top-0 left-0 w-full h-1.5
                bg-gradient-to-r from-[#0D6EFD] via-[#1557C0] to-[#0B2E6B]
                rounded-t-2xl"></div>

    <form method="post" enctype="multipart/form-data" class="space-y-10">
<!-- ================= KATEGORI ================= -->
<div class="space-y-2">
    <label class="text-sm font-semibold text-slate-700">
        Kategori Tiket <span class="text-red-500">*</span>
    </label>

    <select id="kategori" name="kategori" required
        class="w-full h-12 rounded-xl px-4
               bg-white text-sm
               border border-slate-300
               focus:outline-none
               focus:border-[#0D6EFD]
               focus:ring-4 focus:ring-[#0D6EFD]/20
               transition-all">
        <option value="">Pilih kategori tiket</option>
        <option value="kerusakan">Kerusakan Barang IT</option>
        <option value="pendamping">Pendamping IT</option>
        <option value="layanan">Layanan IT</option>
    </select>
</div>


            <!-- ================= KERUSAKAN ================= -->
      <div id="form-kerusakan" class="hidden space-y-6 pt-8">

    <!-- ===== SECTION HEADER ===== -->
    <div class="flex items-center justify-between">

        <div class="flex items-center gap-3">
            <!-- accent line -->
            <div class="h-1 w-10 rounded-full
                        bg-gradient-to-r from-[#F59E0B] to-[#F97316]"></div>

            <h4 class="text-lg font-bold tracking-tight text-[#0B2E6B]">
                Detail Kerusakan
            </h4>
        </div>

        <!-- badge -->
        <span class="text-[11px] font-semibold uppercase tracking-wider
                     px-3 py-1 rounded-full
                     bg-orange-50 text-orange-600">
            Wajib Diisi
        </span>

    </div>

    <!-- perangkat_id -->
    <div>
        <label class="text-sm">Perangkat *</label>
        <select name="perangkat_id" required
            class="w-full h-11 rounded-xl px-4 border">
            <option value="">Pilih perangkat</option>
            <?php foreach($perangkat as $p): ?>
                <option value="<?= $p['id'] ?>"><?= $p['nama_barang'] ?></option>
            <?php endforeach ?>
        </select>
    </div>

    <!-- lokasi -->
    <div>
        <label class="text-sm">Lokasi *</label>
        <input type="text" name="lokasi" required
               class="w-full h-11 rounded-xl px-4 border">
    </div>

    <!-- jenis_kerusakan -->
    <div>
        <label class="text-sm">Deskripsi Kerusakan *</label>
        <textarea name="jenis_kerusakan" rows="3" required
            class="w-full rounded-xl px-4 py-3 border"></textarea>
    </div>

    <!-- foto -->
    <div>
        <label class="text-sm">Foto (opsional)</label>
        <input type="file" name="foto"
               class="w-full h-11 rounded-xl px-4 border">
    </div>
</div>


<div id="form-pendamping" class="hidden space-y-6 pt-8">

    <!-- ===== SECTION HEADER ===== -->
    <div class="flex items-center justify-between">

        <div class="flex items-center gap-3">
            <!-- accent line -->
            <div class="h-1 w-10 rounded-full
                        bg-gradient-to-r from-[#0D6EFD] to-[#1557C0]"></div>

            <h4 class="text-lg font-bold tracking-tight text-[#0B2E6B]">
                Detail Pendamping
            </h4>
        </div>

        <!-- badge -->
        <span class="text-[11px] font-semibold uppercase tracking-wider
                     px-3 py-1 rounded-full
                     bg-blue-50 text-blue-600">
            Jadwal
        </span>

    </div>


    <div>
        <label class="text-sm">Tanggal *</label>
        <input type="text" name="tanggal" class="tanggal w-full h-11 rounded-xl px-4 border" required>
    </div>

    <div>
        <label class="text-sm">Jam *</label>
        <input type="text" name="jam" class="jam w-full h-11 rounded-xl px-4 border" required>
    </div>

    <div>
        <label class="text-sm">Lokasi *</label>
        <input type="text" name="lokasi" class="w-full h-11 rounded-xl px-4 border" required>
    </div>

    <div>
        <label class="text-sm">Kebutuhan *</label>
        <input type="text" name="kebutuhan" class="w-full h-11 rounded-xl px-4 border" required>
    </div>
</div>

            <!-- ================= LAYANAN ================= -->
           <div id="form-layanan" class="hidden space-y-6 pt-8">

    <!-- ===== SECTION HEADER ===== -->
    <div class="flex items-center justify-between">

        <div class="flex items-center gap-3">
            <!-- accent line -->
            <div class="h-1 w-10 rounded-full
                        bg-gradient-to-r from-[#6366F1] to-[#4F46E5]"></div>

            <h4 class="text-lg font-bold tracking-tight text-[#0B2E6B]">
                Detail Layanan
            </h4>
        </div>

        <!-- badge -->
        <span class="text-[11px] font-semibold uppercase tracking-wider
                     px-3 py-1 rounded-full
                     bg-indigo-50 text-indigo-600">
            Permintaan
        </span>

    </div>


    <div>
        <label class="text-sm">Tanggal *</label>
        <input type="text" name="tanggal" class="tanggal w-full h-11 rounded-xl px-4 border" required>
    </div>

    <div>
        <label class="text-sm">Jam *</label>
        <input type="text" name="jam" class="jam w-full h-11 rounded-xl px-4 border" required>
    </div>

    <div>
        <label class="text-sm">Jenis Layanan *</label>
        <input type="text" name="jenis_layanan" class="w-full h-11 rounded-xl px-4 border" required>
    </div>

    <div>
        <label class="text-sm">Catatan</label>
        <textarea name="layanan_catatan" rows="3"
            class="w-full rounded-xl px-4 py-3 border"></textarea>
    </div>
</div>

<!-- ================= BUTTON ================= -->
<div class="pt-6 flex gap-4">

    <!-- SUBMIT -->
    <button type="button"
        id="btnSubmit"
        class="flex-1 h-11 rounded-xl
               bg-gradient-to-r from-[#0D6EFD] to-[#1557C0]
               hover:from-[#0B5ED7] hover:to-[#0A58CA]
               text-white text-sm font-semibold
               shadow-lg shadow-blue-500/30
               transition-all duration-200
               active:scale-[0.98]
               flex items-center justify-center gap-2">

        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M5 12h14M12 5l7 7-7 7"/>
        </svg>

        Kirim Tiket
    </button>

    <!-- CANCEL -->
    <button type="button"
        id="btnCancel"
        class="h-11 px-6 rounded-xl
               border border-slate-300
               text-slate-600 text-sm font-medium
               hover:bg-slate-100
               hover:text-slate-800
               transition-all duration-200
               active:scale-[0.97]">
        Batal
    </button>

</div>


        </form>
    </div>
</div>




<!-- ================= JS ================= -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const form       = document.querySelector('form');
    const kategori   = document.getElementById('kategori');
    const btnSubmit  = document.getElementById('btnSubmit');
    const btnCancel  = document.getElementById('btnCancel');

    const forms = {
        kerusakan: document.getElementById('form-kerusakan'),
        pendamping: document.getElementById('form-pendamping'),
        layanan: document.getElementById('form-layanan')
    };

    /* ================= TOGGLE FORM ================= */
    kategori.addEventListener('change', () => {
        Object.values(forms).forEach(f => f.classList.add('hidden'));

        if (forms[kategori.value]) {
            forms[kategori.value].classList.remove('hidden');
        }
    });

    /* ================= SUBMIT ================= */
    btnSubmit.addEventListener('click', () => {

        if (!kategori.value) {
            Swal.fire('Oops', 'Pilih kategori tiket terlebih dahulu', 'warning');
            return;
        }

        const activeForm = forms[kategori.value];
        const inputs = activeForm.querySelectorAll('input, textarea, select');

        for (let input of inputs) {
            if (input.type !== 'file' && input.value.trim() === '') {
                Swal.fire(
                    'Lengkapi Data',
                    'Masih ada field yang wajib diisi',
                    'warning'
                );
                return;
            }
        }

        Swal.fire({
            title: 'Kirim tiket?',
            text: 'Pastikan data yang Anda isi sudah benar',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Kirim',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // ✅ MASUK CONTROLLER CI
            }
        });
    });

/* ================= CANCEL ================= */
btnCancel.addEventListener('click', () => {
    Swal.fire({
        title: 'Batalkan pembuatan tiket?',
        text: 'Data yang sudah diisi akan hilang',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Batalkan',
        cancelButtonText: 'Lanjutkan Mengisi',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {

            // reset form
            form.reset();

            // hide semua form kategori
            Object.values(forms).forEach(f => f.classList.add('hidden'));

            // reset kategori
            kategori.value = '';

            // 🔥 REDIRECT KE DASHBOARD USER
            window.location.href = "<?= base_url('user/dashboard') ?>";
        }
    });
});


    /* ================= FLATPICKR ================= */
    flatpickr(".tanggal", {
        dateFormat: "Y-m-d",
        minDate: "today"
    });

    flatpickr(".jam", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true
    });

});
</script>
