<div class="max-w-5xl mx-auto mt-12 mb-24 space-y-8">

    <!-- ================= HEADER ================= -->
    <div class="text-center space-y-3">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-red-100 text-red-700 text-sm font-semibold" id="headerBadge">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <span id="headerBadgeText">Laporan Gangguan</span>
        </div>
        <h1 id="headerJudul" class="text-4xl md:text-5xl font-extrabold tracking-tight text-[#0B2E6B]">
            Laporkan Gangguan
        </h1>
        <p id="headerSubjudul" class="text-base text-slate-600 max-w-2xl mx-auto">
            Laporkan gangguan IT maupun layanan umum agar dapat segera ditangani oleh tim kami
        </p>
    </div>

    <!-- ================= INFO CARDS ================= -->
    <div class="grid md:grid-cols-3 gap-5">
        <div class="group relative p-6 rounded-2xl bg-gradient-to-br from-red-50 to-red-100/50 border border-red-200 hover:shadow-lg transition-all">
            <div class="absolute top-4 right-4 text-4xl opacity-20 group-hover:opacity-30 transition">📌</div>
            <div class="text-red-700 font-bold mb-2">Gangguan IT</div>
            <p class="text-sm text-red-600/90 leading-relaxed">
                PC tidak menyala, komputer lambat, printer error, jaringan terputus, aplikasi bermasalah
            </p>
        </div>

        <div class="group relative p-6 rounded-2xl bg-gradient-to-br from-orange-50 to-orange-100/50 border border-orange-200 hover:shadow-lg transition-all">
            <div class="absolute top-4 right-4 text-4xl opacity-20 group-hover:opacity-30 transition">🏠</div>
            <div class="text-orange-700 font-bold mb-2">Gangguan Layanan Umum</div>
            <p class="text-sm text-orange-600/90 leading-relaxed">
                Lampu mati, AC rusak, kebocoran air, lift bermasalah, kebersihan kantor
            </p>
        </div>

        <div class="group relative p-6 rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100/50 border border-blue-200 hover:shadow-lg transition-all">
            <div class="absolute top-4 right-4 text-4xl opacity-20 group-hover:opacity-30 transition">⚡</div>
            <div class="text-blue-700 font-bold mb-2">Respon Cepat</div>
            <p class="text-sm text-blue-600/90 leading-relaxed">
                Tiket gangguan akan diprioritaskan untuk penanganan segera
            </p>
        </div>
    </div>

    <!-- ================= FORM ================= -->
    <div class="bg-white rounded-3xl p-8 md:p-10 shadow-2xl border border-gray-100">

        <form id="gangguanForm"
              action="<?= base_url('user_ticket/create_gangguan') ?>"
              method="post"
              enctype="multipart/form-data"
              class="space-y-6">

            <input type="hidden" name="kategori" value="gangguan">
            <input type="hidden" name="perangkat_ids" id="perangkatIdsFinal">

            <!-- STEP 1: SUB KATEGORI -->
            <div class="space-y-2">
                <label class="flex items-center gap-2 text-sm font-bold text-gray-800">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-red-600 text-white text-xs">1</span>
                    Pilih Jenis Gangguan
                    <span class="text-red-500">*</span>
                </label>
                <select name="sub_kategori_id" id="subKategori" required
                        class="w-full h-14 rounded-xl px-5 border-2 border-gray-200
                               focus:border-red-500 focus:ring-4 focus:ring-red-100
                               text-gray-800 font-medium transition-all hover:border-gray-300">
                    <option value="">── Pilih jenis gangguan yang dialami ──</option>

                    <!-- Gangguan IT -->
                    <optgroup label="Gangguan Layanan IT">
                        <?php foreach ($sub_kategori_it as $sk): ?>
                            <option value="<?= $sk->id ?>"
                                    data-slug="<?= $sk->slug ?>"
                                    data-tipe="it">
                                <?= $sk->nama_sub_kategori ?>
                            </option>
                        <?php endforeach ?>
                    </optgroup>

                    <!-- Gangguan Layanan Umum -->
                    <optgroup label="Gangguan Layanan Umum">
                        <option value="<?= $sub_kategori_umum->id ?>"
        data-slug="rumah-tangga"
        data-tipe="umum">
    Fasilitas Umum
</option>
                    </optgroup>

                </select>
            </div>

            <!-- FORM DETAILS (Hidden initially) -->
            <div id="formDetails" class="hidden space-y-6 pt-4 border-t-2 border-gray-100">

                <!-- STEP 2: LOKASI -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-bold text-gray-800">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-red-600 text-white text-xs">2</span>
                        Lokasi Gangguan
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="lokasi" id="lokasiInput" required
                           placeholder="Contoh: Lantai 3 - Ruang Statistik Produksi"
                           class="w-full h-14 rounded-xl px-5 border-2 border-gray-200
                                  focus:border-red-500 focus:ring-4 focus:ring-red-100
                                  text-gray-800 transition-all
                                  placeholder:text-gray-400
                                  hover:border-gray-300">
                </div>

                <!-- STEP 3: PERANGKAT (OPTIONAL, MULTIPLE) — Hanya tampil untuk IT -->
                <div id="sectionPerangkat" class="hidden space-y-3">
                    <label class="flex items-center gap-2 text-sm font-bold text-gray-800">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-gray-400 text-white text-xs">3</span>
                        Perangkat Terkait
                        <span class="text-xs font-normal text-gray-500">(Opsional - Bisa pilih lebih dari 1)</span>
                    </label>

                    <div class="relative">
                        <input type="text"
                               id="perangkatSearch"
                               placeholder="🔍 Ketik kode barang atau NUP untuk mencari..."
                               autocomplete="off"
                               class="w-full h-14 rounded-xl px-5 pr-12 border-2 border-gray-200
                                      focus:border-blue-500 focus:ring-4 focus:ring-blue-100
                                      text-gray-800 transition-all
                                      placeholder:text-gray-400
                                      hover:border-gray-300">
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>

                    <div class="flex items-start gap-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-xs text-blue-700 leading-relaxed">
                            <strong>Contoh pencarian:</strong>
                            <span class="inline-block bg-white px-2 py-0.5 rounded ml-1">3100102001</span>
                            <span class="inline-block bg-white px-2 py-0.5 rounded ml-1">75</span>
                            <span class="inline-block bg-white px-2 py-0.5 rounded ml-1">310010200175</span>
                        </p>
                    </div>

                    <!-- SEARCH RESULT -->
                    <div id="perangkatResult"
                         class="hidden w-full bg-white border-2 border-blue-300 rounded-xl shadow-xl mt-2 max-h-64 overflow-y-auto">
                    </div>

                    <!-- SELECTED PERANGKAT CHIPS -->
                    <div id="selectedPerangkat" class="min-h-[3rem] flex flex-wrap gap-2 p-4 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                    </div>
                </div>

                <!-- STEP 4 (atau 3 untuk umum): DESKRIPSI -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-bold text-gray-800">
                        <span id="stepDeskripsi" class="flex items-center justify-center w-6 h-6 rounded-full bg-red-600 text-white text-xs">4</span>
                        Deskripsi Gangguan
                        <span class="text-red-500">*</span>
                    </label>
                    <textarea name="jenis_kerusakan" id="deskripsiGangguan" rows="7" required
                              placeholder="Jelaskan gangguan secara detail:&#10;&#10;• Apa yang terjadi?&#10;• Sejak kapan masalah muncul?&#10;• Sudah dicoba apa saja?&#10;• Bagaimana kondisi saat ini?&#10;&#10;Semakin detail penjelasan Anda, semakin cepat tim dapat menangani."
                              class="w-full rounded-xl px-5 py-4 border-2 border-gray-200
                                     focus:border-red-500 focus:ring-4 focus:ring-red-100
                                     text-gray-800 transition-all resize-none
                                     placeholder:text-gray-400
                                     hover:border-gray-300"></textarea>
                </div>

                <!-- STEP 5 (atau 4 untuk umum): FOTO -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-bold text-gray-800">
                        <span id="stepFoto" class="flex items-center justify-center w-6 h-6 rounded-full bg-gray-400 text-white text-xs">5</span>
                        Foto Pendukung
                        <span class="text-xs font-normal text-gray-500">(Opsional)</span>
                    </label>
                    <div class="relative">
                        <input type="file" name="foto" accept="image/*" id="fotoInput"
                               class="w-full h-14 px-5 border-2 border-gray-200 rounded-xl
                                      file:mr-4 file:py-2.5 file:px-5 file:rounded-lg file:border-0
                                      file:bg-orange-500 file:text-white file:font-semibold
                                      file:cursor-pointer hover:file:bg-orange-600
                                      focus:border-orange-500 focus:ring-4 focus:ring-orange-100
                                      transition-all cursor-pointer
                                      hover:border-gray-300">
                    </div>
                    <p class="text-xs text-gray-500 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Upload foto gangguan untuk mempercepat proses analisis
                    </p>
                </div>

                <!-- ACTION BUTTONS -->
                <div class="flex gap-4 pt-6 border-t-2 border-gray-100">
                    <button type="button" id="btnSubmit"
                            class="flex-1 h-14 rounded-xl bg-gradient-to-r from-red-600 to-red-700 
                                   hover:from-red-700 hover:to-red-800
                                   text-white font-bold text-lg
                                   shadow-lg hover:shadow-xl
                                   transition-all duration-300
                                   flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Kirim Laporan Gangguan
                    </button>

                    <button type="button" id="btnCancel"
                            class="h-14 px-8 rounded-xl border-2 border-gray-300
                                   hover:bg-gray-100 hover:border-gray-400
                                   text-gray-700 font-semibold
                                   transition-all duration-300">
                        Batal
                    </button>
                </div>

            </div>

        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function() {
    'use strict';

    const form              = document.getElementById('gangguanForm');
    const subKategori       = document.getElementById('subKategori');
    const formDetails       = document.getElementById('formDetails');
    const sectionPerangkat  = document.getElementById('sectionPerangkat');
    const perangkatSearch   = document.getElementById('perangkatSearch');
    const perangkatResult   = document.getElementById('perangkatResult');
    const perangkatIdsFinal = document.getElementById('perangkatIdsFinal');
    const btnSubmit         = document.getElementById('btnSubmit');
    const btnCancel         = document.getElementById('btnCancel');
    const stepDeskripsi     = document.getElementById('stepDeskripsi');
    const stepFoto          = document.getElementById('stepFoto');

    let timer = null;
    let isUmum = false;

    window.gangguanSelectedItems = [];

    /* ── Update header sesuai tipe ── */
    function updateHeader(tipe) {
        const badge    = document.getElementById('headerBadgeText');
        const judul    = document.getElementById('headerJudul');
        const subjudul = document.getElementById('headerSubjudul');

        if (tipe === 'umum') {
            badge.textContent    = 'Laporan Gangguan Layanan Umum';
            judul.textContent    = 'Laporkan Gangguan Layanan Umum';
            subjudul.textContent = 'Laporkan gangguan fasilitas atau layanan umum kantor agar segera ditangani';
        } else if (tipe === 'it') {
            badge.textContent    = 'Laporan Gangguan IT';
            judul.textContent    = 'Laporkan Gangguan IT';
            subjudul.textContent = 'Laporkan gangguan atau kerusakan perangkat IT agar dapat segera ditangani oleh tim teknis kami';
        } else {
            badge.textContent    = 'Laporan Gangguan';
            judul.textContent    = 'Laporkan Gangguan';
            subjudul.textContent = 'Laporkan gangguan IT maupun layanan umum agar dapat segera ditangani oleh tim kami';
        }
    }

    /* ── Event: sub kategori berubah ── */
    subKategori.addEventListener('change', function() {
        if (!this.value) {
            formDetails.classList.add('hidden');
            updateHeader('');
            return;
        }

        const opt  = this.options[this.selectedIndex];
        const tipe = opt.getAttribute('data-tipe') || 'it';
        isUmum     = (tipe === 'umum');

        updateHeader(tipe);
        formDetails.classList.remove('hidden');

        if (isUmum) {
            // Sembunyikan section perangkat untuk layanan umum
            sectionPerangkat.classList.add('hidden');
            stepDeskripsi.textContent = '3';
            stepFoto.textContent      = '4';
            // Reset perangkat yang sudah dipilih
            window.gangguanSelectedItems = [];
            perangkatIdsFinal.value = '[]';
            renderSelectedPerangkat();
        } else {
            // Tampilkan section perangkat untuk IT
            sectionPerangkat.classList.remove('hidden');
            stepDeskripsi.textContent = '4';
            stepFoto.textContent      = '5';
        }

        formDetails.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    });

    /* ── Search Perangkat ── */
    perangkatSearch.addEventListener('input', function () {
        clearTimeout(timer);
        const q = this.value.trim();

        if (q.length < 3) {
            perangkatResult.classList.add('hidden');
            return;
        }

        timer = setTimeout(() => {
            fetch(`<?= base_url('user_ticket/search_perangkat') ?>?q=${encodeURIComponent(q)}`)
                .then(res => res.json())
                .then(data => {
                    perangkatResult.innerHTML = '';

                    if (!data.length) {
                        perangkatResult.innerHTML = `
                            <div class="px-5 py-4 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-sm">Perangkat tidak ditemukan</p>
                            </div>`;
                        perangkatResult.classList.remove('hidden');
                        return;
                    }

                    data.forEach(item => {
                        if (window.gangguanSelectedItems.find(s => s.id === item.id)) return;
                        const div = document.createElement('div');
                        div.className = 'px-5 py-3 hover:bg-blue-50 cursor-pointer border-b last:border-b-0 transition-colors';
                        div.innerHTML = `
                            <div class="font-semibold text-gray-900">${item.kode_barang} | ${item.nup}</div>
                            <div class="text-sm text-gray-600 mt-0.5">${item.nama_barang}</div>`;
                        div.onclick = () => addPerangkat(item);
                        perangkatResult.appendChild(div);
                    });

                    perangkatResult.classList.remove('hidden');
                });
        }, 300);
    });

    /* ── Add Perangkat ── */
    function addPerangkat(item) {
        if (window.gangguanSelectedItems.find(s => s.id === item.id)) return;
        window.gangguanSelectedItems.push(item);
        renderSelectedPerangkat();
        perangkatSearch.value = '';
        perangkatResult.classList.add('hidden');
        updateHiddenInput();
    }

    /* ── Remove Perangkat ── */
    window.removePerangkatGangguan = function(id) {
        const idToRemove = parseInt(id);
        window.gangguanSelectedItems = window.gangguanSelectedItems.filter(item => parseInt(item.id) !== idToRemove);
        renderSelectedPerangkat();
        updateHiddenInput();
    };

    /* ── Render Chips ── */
    function renderSelectedPerangkat() {
        const container = document.getElementById('selectedPerangkat');
        if (!container) return;

        if (window.gangguanSelectedItems.length === 0) {
            container.innerHTML = `
                <p class="text-sm text-gray-400 w-full text-center py-2">
                    <svg class="w-5 h-5 inline mr-1.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    Belum ada perangkat dipilih
                </p>`;
            return;
        }

        container.innerHTML = window.gangguanSelectedItems.map(item => `
            <div class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg text-sm font-medium shadow-md hover:shadow-lg transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>${item.kode_barang} | ${item.nup}</span>
                <button type="button"
                        onclick="window.removePerangkatGangguan(${item.id})"
                        class="ml-1 text-white hover:text-red-200 font-bold text-xl leading-none transition-colors"
                        style="cursor: pointer;">×</button>
            </div>`).join('');
    }

    /* ── Update Hidden Input ── */
    function updateHiddenInput() {
        perangkatIdsFinal.value = JSON.stringify(window.gangguanSelectedItems.map(item => item.id));
    }

    /* ── Submit ── */
    btnSubmit.addEventListener('click', () => {
        if (!subKategori.value) {
            Swal.fire({ icon: 'warning', title: 'Jenis Gangguan Wajib Dipilih',
                        text: 'Silakan pilih jenis gangguan terlebih dahulu', confirmButtonColor: '#dc2626' });
            return;
        }

        const lokasi = document.getElementById('lokasiInput');
        if (!lokasi.value.trim()) {
            Swal.fire({ icon: 'warning', title: 'Lokasi Wajib Diisi',
                        text: 'Silakan isi lokasi terjadinya gangguan', confirmButtonColor: '#dc2626' });
            return;
        }

        const deskripsi = document.getElementById('deskripsiGangguan');
        if (!deskripsi.value.trim()) {
            Swal.fire({ icon: 'warning', title: 'Deskripsi Wajib Diisi',
                        text: 'Silakan jelaskan gangguan yang terjadi secara detail', confirmButtonColor: '#dc2626' });
            return;
        }

        const jenisLabel = subKategori.options[subKategori.selectedIndex].text;
        const perangkatInfo = (!isUmum && window.gangguanSelectedItems.length > 0)
            ? window.gangguanSelectedItems.length + ' perangkat dipilih'
            : 'Tidak ada';

        Swal.fire({
            title: 'Kirim Laporan Gangguan?',
            html: `
                <div class="text-left space-y-2 mt-4 p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm"><strong>Jenis Gangguan:</strong><br>${jenisLabel}</p>
                    <p class="text-sm"><strong>Lokasi:</strong><br>${lokasi.value}</p>
                    ${!isUmum ? `<p class="text-sm"><strong>Perangkat Terkait:</strong><br>${perangkatInfo}</p>` : ''}
                </div>
                <p class="text-sm text-gray-600 mt-4">Pastikan semua data yang Anda isi sudah benar</p>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Kirim Laporan',
            cancelButtonText: 'Periksa Kembali',
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Mengirim laporan...',
                    html: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
                setTimeout(() => form.submit(), 800);
            }
        });
    });

    /* ── Batal ── */
    btnCancel.addEventListener('click', () => {
        Swal.fire({
            title: 'Batalkan Laporan?',
            text: 'Semua data yang sudah diisi akan hilang',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Lanjutkan Mengisi',
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= base_url('user/dashboard') ?>";
            }
        });
    });

    // Click outside to close search result
    document.addEventListener('click', function(e) {
        if (perangkatSearch && !perangkatSearch.contains(e.target) && !perangkatResult.contains(e.target)) {
            perangkatResult.classList.add('hidden');
        }
    });

    // Init
    renderSelectedPerangkat();
    updateHiddenInput();

})();
</script>