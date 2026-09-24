<div class="max-w-5xl mx-auto mt-12 mb-24 space-y-8">

    <!-- ================= HEADER ================= -->
    <div class="text-center space-y-3">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-100 text-blue-700 text-sm font-semibold">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span id="headerBadge">Permintaan Layanan IT</span>
        </div>
        <h1 id="headerJudul" class="text-4xl md:text-5xl font-extrabold tracking-tight text-[#0B2E6B]">
            Permintaan Layanan IT
        </h1>
        <p id="headerSubjudul" class="text-base text-slate-600 max-w-2xl mx-auto">
            Ajukan kebutuhan layanan IT secara terstruktur, aman, dan terdokumentasi
        </p>
    </div>

    <!-- ================= INFO CARDS ================= -->
    <div class="grid md:grid-cols-3 gap-5">
        <div class="group relative p-6 rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100/50 border border-blue-200 hover:shadow-lg transition-all">
            <div class="absolute top-4 right-4 text-4xl opacity-20 group-hover:opacity-30 transition">🧾</div>
            <div class="text-blue-700 font-bold mb-2">Contoh Permintaan</div>
            <p class="text-sm text-blue-600/90 leading-relaxed">
                Permintaan Zoom/Meet, upgrade RAM/SSD, instalasi aplikasi, permintaan akun atau akses sistem
            </p>
        </div>
        <div class="group relative p-6 rounded-2xl bg-gradient-to-br from-indigo-50 to-indigo-100/50 border border-indigo-200 hover:shadow-lg transition-all">
            <div class="absolute top-4 right-4 text-4xl opacity-20 group-hover:opacity-30 transition">📅</div>
            <div class="text-indigo-700 font-bold mb-2">Penjadwalan Fleksibel</div>
            <p class="text-sm text-indigo-600/90 leading-relaxed">
                Jadwal hanya diperlukan untuk layanan tertentu. Bisa multi-hari untuk acara yang berlangsung beberapa hari
            </p>
        </div>
        <div class="group relative p-6 rounded-2xl bg-gradient-to-br from-emerald-50 to-emerald-100/50 border border-emerald-200 hover:shadow-lg transition-all">
            <div class="absolute top-4 right-4 text-4xl opacity-20 group-hover:opacity-30 transition">📊</div>
            <div class="text-emerald-700 font-bold mb-2">Terdokumentasi</div>
            <p class="text-sm text-emerald-600/90 leading-relaxed">
                Setiap permintaan tercatat dan dapat dipantau melalui riwayat tiket Anda
            </p>
        </div>
    </div>

    <?php if ($this->session->flashdata('error')): ?>
    <div class="mb-4 flex items-center gap-3 px-5 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm font-medium">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <?= $this->session->flashdata('error') ?>
    </div>
    <?php endif ?>

    <!-- ================= FORM ================= -->
    <div class="bg-white rounded-3xl p-8 md:p-10 shadow-2xl border border-gray-100">

        <form id="permintaanForm" method="post" action="<?= base_url('user_ticket/create_permintaan') ?>" class="space-y-6">

            <input type="hidden" name="kategori"       value="permintaan">
            <input type="hidden" name="is_ruang_rapat" id="isRuangRapat" value="0">
            <input type="hidden" name="is_kendaraan"   id="isKendaraan"  value="0">
            <input type="hidden" name="is_bmn"         id="isBmn"        value="0">

            <!-- STEP 1: SUB KATEGORI -->
            <div class="space-y-2">
                <label class="flex items-center gap-2 text-sm font-bold text-gray-800">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-600 text-white text-xs">1</span>
                    Jenis Layanan
                    <span class="text-red-500">*</span>
                </label>
                <select name="sub_kategori_id" id="subKategori" required
                        class="w-full h-14 rounded-xl px-5 border-2 border-gray-200
                               focus:border-blue-500 focus:ring-4 focus:ring-blue-100
                               text-gray-800 font-medium transition-all hover:border-gray-300">
                    <option value="">── Pilih jenis layanan yang dibutuhkan ──</option>

                    <optgroup label="Permintaan Layanan IT">
                        <?php foreach ($sub_kategori as $sk): ?>
                            <?php if ($sk->slug !== 'permintaan-ruang-rapat' && $sk->slug !== 'permintaan-kendaraan' && $sk->slug !== 'permintaan-peminjaman-bmn'): ?>
                            <option value="<?= $sk->id ?>"
                                    data-perlu-jadwal="<?= $sk->perlu_jadwal ?>"
                                    data-slug="<?= $sk->slug ?>">
                                <?= $sk->nama_sub_kategori ?>
                            </option>
                            <?php endif ?>
                        <?php endforeach ?>
                    </optgroup>

                    <optgroup label="Permintaan Layanan Umum">
                        <?php foreach ($sub_kategori as $sk): ?>
                            <?php if ($sk->slug === 'permintaan-ruang-rapat'): ?>
                            <option value="<?= $sk->id ?>"
                                    data-perlu-jadwal="<?= $sk->perlu_jadwal ?>"
                                    data-slug="<?= $sk->slug ?>">
                                Permintaan Penggunaan Ruang Rapat
                            </option>
                            <option value="bmn-langsung"
                                    data-perlu-jadwal="0"
                                    data-slug="permintaan-bmn">
                                Permintaan Peminjaman Barang BMN
                            </option>
                            <?php endif ?>
                        <?php endforeach ?>

                        <?php foreach ($sub_kategori as $sk): ?>
                            <?php if ($sk->slug === 'permintaan-kendaraan'): ?>
                            <option value="<?= $sk->id ?>"
                                    data-perlu-jadwal="0"
                                    data-slug="permintaan-kendaraan">
                                Permintaan Kendaraan Dinas
                            </option>
                            <?php endif ?>
                        <?php endforeach ?>
                    </optgroup>

                </select>
            </div>

            <!-- WRAPPER DETAIL -->
            <div id="formDetails" class="hidden space-y-6 pt-4 border-t-2 border-gray-100">

                <!-- ================================================
                     BLOK A: FORM LAYANAN IT BIASA
                ================================================ -->
                <div id="blokLayananBiasa">

                    <div class="space-y-2 mb-6">
                        <label class="flex items-center gap-2 text-sm font-bold text-gray-800">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-600 text-white text-xs">2</span>
                            Detail Permintaan
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="jenis_layanan" id="jenisLayanan"
                               placeholder="Contoh: Rapat Tim Maret 2026"
                               class="w-full h-14 rounded-xl px-5 border-2 border-gray-200
                                      focus:border-blue-500 focus:ring-4 focus:ring-blue-100
                                      text-gray-800 transition-all placeholder:text-gray-400 hover:border-gray-300">
                        <div id="noteZoom" class="hidden mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-xs text-blue-700 leading-relaxed">
                                <strong>Batas maksimal peserta adalah 300 orang.</strong>
                                Jika jumlah peserta melebihi batas tersebut, silakan berkoordinasi dengan tim IPDS untuk penanganan lebih lanjut.
                            </p>
                        </div>
                    </div>

                    <!-- Jadwal (perlu_jadwal=1) -->
                    <div id="jadwalSection" class="hidden space-y-4 mb-6">
                        <label class="flex items-center gap-2 text-sm font-bold text-gray-800">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-600 text-white text-xs">3</span>
                            Jadwal Layanan
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Tanggal Mulai <span class="text-red-500">*</span></label>
                                <input type="date" name="tanggal" id="tanggal"
                                       class="w-full h-14 rounded-xl px-5 border-2 border-gray-200
                                              focus:border-blue-500 focus:ring-4 focus:ring-blue-100
                                              text-gray-800 transition-all hover:border-gray-300">
                            </div>
                            <div>
                                <label class="text-xs text-gray-600 font-semibold mb-1.5 block">
                                    Tanggal Selesai
                                    <span class="text-gray-400 font-normal">(opsional, isi jika lebih dari 1 hari)</span>
                                </label>
                                <input type="date" name="tanggal_selesai" id="tanggalSelesai"
                                       class="w-full h-14 rounded-xl px-5 border-2 border-gray-200
                                              focus:border-blue-500 focus:ring-4 focus:ring-blue-100
                                              text-gray-800 transition-all hover:border-gray-300">
                            </div>
                        </div>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Jam Mulai (per hari) <span class="text-red-500">*</span></label>
                                <input type="time" name="jam" id="jam"
                                       class="w-full h-14 rounded-xl px-5 border-2 border-gray-200
                                              focus:border-blue-500 focus:ring-4 focus:ring-blue-100
                                              text-gray-800 transition-all hover:border-gray-300">
                            </div>
                            <div>
                                <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Durasi per Hari <span class="text-red-500">*</span></label>
                                <select name="durasi" id="durasi"
                                        class="w-full h-14 rounded-xl px-5 border-2 border-gray-200
                                               focus:border-blue-500 focus:ring-4 focus:ring-blue-100
                                               text-gray-800 transition-all hover:border-gray-300">
                                    <option value="">Pilih Durasi</option>
                                    <?php for ($i = 1; $i <= 12; $i++): ?>
                                        <option value="<?= $i ?>"><?= $i ?> Jam</option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <div id="ringkasanJadwal" class="hidden p-3 rounded-lg border">
                            <p class="text-sm font-semibold" id="ringkasanTeks"></p>
                        </div>
                        <div class="flex items-start gap-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-xs text-blue-700 leading-relaxed">
                                <strong>Jam mulai dan durasi berlaku untuk setiap hari</strong> selama periode tersebut.
                                Jika hanya 1 hari, kosongkan saja tanggal selesai.
                            </p>
                        </div>
                    </div>

                    <!-- Info Meeting (dengan jadwal) -->
                    <div id="catatanDenganJadwal" class="hidden space-y-3 mb-6">
                        <label class="flex items-center gap-2 text-sm font-bold text-gray-800">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-600 text-white text-xs">4</span>
                            Informasi Detail Meeting
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Jumlah Peserta <span class="text-red-500">*</span></label>
                                <input type="number" name="jumlah_peserta" id="jumlahPeserta" min="1"
                                       placeholder="Contoh: 50"
                                       class="w-full h-12 rounded-xl px-4 border-2 border-gray-200
                                              focus:border-blue-500 focus:ring-4 focus:ring-blue-100
                                              text-gray-800 transition-all hover:border-gray-300">
                            </div>
                            <div>
                                <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Jumlah Breakout Room</label>
                                <input type="number" name="jumlah_breakout" id="jumlahBreakout" min="0"
                                       placeholder="Contoh: 5 (isi 0 jika tidak perlu)"
                                       class="w-full h-12 rounded-xl px-4 border-2 border-gray-200
                                              focus:border-blue-500 focus:ring-4 focus:ring-blue-100
                                              text-gray-800 transition-all hover:border-gray-300">
                            </div>
                        </div>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Penanggung Jawab</label>
                                <input type="text" name="penanggung_jawab" id="penanggungJawab"
                                       placeholder="Nama penanggung jawab acara"
                                       class="w-full h-12 rounded-xl px-4 border-2 border-gray-200
                                              focus:border-blue-500 focus:ring-4 focus:ring-blue-100
                                              text-gray-800 transition-all hover:border-gray-300">
                            </div>
                            <div>
                                <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Host / Co-Host</label>
                                <input type="text" name="host_cohost" id="hostCohost"
                                       placeholder="Nama host atau co-host"
                                       class="w-full h-12 rounded-xl px-4 border-2 border-gray-200
                                              focus:border-blue-500 focus:ring-4 focus:ring-blue-100
                                              text-gray-800 transition-all hover:border-gray-300">
                            </div>
                        </div>
                        <div>
                            <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Permintaan Lainnya</label>
                            <textarea name="permintaan_lain_jadwal" id="permintaanLainJadwal" rows="4"
                                      placeholder="Contoh:&#10;• Perlu rekaman meeting&#10;• Perlu fitur polling&#10;• Perlu waiting room&#10;• Dll"
                                      class="w-full rounded-xl px-4 py-3 border-2 border-gray-200
                                             focus:border-blue-500 focus:ring-4 focus:ring-blue-100
                                             text-gray-800 transition-all resize-none
                                             placeholder:text-gray-400 hover:border-gray-300"></textarea>
                        </div>
                    </div>

                    <!-- Tanpa jadwal -->
                    <div id="catatanTanpaJadwal" class="hidden space-y-3 mb-6">
                        <label class="flex items-center gap-2 text-sm font-bold text-gray-800">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-gray-400 text-white text-xs">3</span>
                            Informasi Tambahan
                            <span class="text-xs font-normal text-gray-500">(Opsional)</span>
                        </label>
                        <div>
                            <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Lokasi / Unit Kerja</label>
                            <input type="text" name="lokasi_tanpa_jadwal" id="lokasiTanpaJadwal"
                                   placeholder="Contoh: Ruang IT Lantai 2 / Bagian Keuangan"
                                   class="w-full h-12 rounded-xl px-4 border-2 border-gray-200
                                          focus:border-blue-500 focus:ring-4 focus:ring-blue-100
                                          text-gray-800 transition-all hover:border-gray-300">
                        </div>
                        <div>
                            <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Catatan Tambahan</label>
                            <textarea name="permintaan_lain_tanpa_jadwal" id="permintaanLainTanpaJadwal" rows="5"
                                      placeholder="Informasi tambahan yang perlu diketahui teknisi..."
                                      class="w-full rounded-xl px-4 py-3 border-2 border-gray-200
                                             focus:border-blue-500 focus:ring-4 focus:ring-blue-100
                                             text-gray-800 transition-all resize-none
                                             placeholder:text-gray-400 hover:border-gray-300"></textarea>
                        </div>
                    </div>

                </div><!-- end #blokLayananBiasa -->

                <!-- ================================================
                     BLOK B: FORM RUANG RAPAT
                ================================================ -->
                <div id="blokRuangRapat" class="hidden space-y-5">

                    <div class="flex items-center gap-3 pb-3 border-b-2 border-indigo-100">
                        <div class="p-2 rounded-xl bg-indigo-100">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800">Formulir Permintaan Penggunaan Ruang Rapat</p>
                            <p class="text-xs text-gray-500">Isi detail peminjaman di bawah ini</p>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Unit/Tim Kerja <span class="text-red-500">*</span></label>
                        <input type="text" name="unit_kerja" id="unitKerja"
                               placeholder="Contoh: Bagian Keuangan / Tim IPDS"
                               class="w-full h-14 rounded-xl px-5 border-2 border-gray-200
                                      focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100
                                      text-gray-800 transition-all hover:border-gray-300">
                    </div>

                    <div>
                        <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Tanggal Pemakaian <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_rapat" id="tanggalRapat"
                               class="w-full h-14 rounded-xl px-5 border-2 border-gray-200
                                      focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100
                                      text-gray-800 transition-all hover:border-gray-300">
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Jam Mulai <span class="text-red-500">*</span></label>
                            <input type="time" name="jam_mulai" id="jamMulai"
                                   min="07:00" max="16:00"
                                   class="w-full h-14 rounded-xl px-5 border-2 border-gray-200
                                          focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100
                                          text-gray-800 transition-all hover:border-gray-300">
                        </div>
                        <div>
                            <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Durasi <span class="text-red-500">*</span></label>
                            <select name="durasi_rapat" id="durasiRapat"
                                    class="w-full h-14 rounded-xl px-5 border-2 border-gray-200
                                           focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100
                                           text-gray-800 transition-all hover:border-gray-300">
                                <option value="">Pilih Durasi</option>
                                <option value="60">1 Jam</option>
                                <option value="120">2 Jam</option>
                                <option value="180">3 Jam</option>
                                <option value="240">4 Jam</option>
                                <option value="300">5 Jam</option>
                                <option value="360">6 Jam</option>
                                <option value="420">7 Jam</option>
                                <option value="480">8 Jam (Full Day)</option>
                            </select>
                        </div>
                    </div>

                    <input type="hidden" name="jam_selesai" id="jamSelesai">
                    <div id="infoJamSelesai" class="hidden flex items-center gap-2 p-3 bg-green-50 border border-green-200 rounded-lg">
                        <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-xs text-green-700">
                            Jam selesai: <strong id="tampilJamSelesai">--:--</strong> WIB &nbsp;·&nbsp; Berakhir otomatis berdasarkan durasi
                        </p>
                    </div>

                    <div>
                        <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Pilih Ruangan <span class="text-red-500">*</span></label>
                        <select name="ruangan_id" id="ruanganId"
                                class="w-full h-14 rounded-xl px-5 border-2 border-gray-200
                                       focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100
                                       text-gray-800 transition-all hover:border-gray-300">
                            <option value="">── Pilih tanggal & jam dulu ──</option>
                        </select>
                        <div id="infoRuangan" class="hidden mt-2 p-3 bg-indigo-50 border border-indigo-200 rounded-lg text-xs text-indigo-800 leading-relaxed"></div>
                        <div id="jadwalRuangan" class="hidden mt-2 p-3 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-800"></div>
                    </div>

                    <div>
                        <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Keperluan / Agenda <span class="text-red-500">*</span></label>
                        <textarea name="keperluan" id="keperluan" rows="3"
                                  placeholder="Contoh: Rapat Koordinasi Tim Keuangan Q1 2026"
                                  class="w-full rounded-xl px-4 py-3 border-2 border-gray-200
                                         focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100
                                         text-gray-800 transition-all resize-none
                                         placeholder:text-gray-400 hover:border-gray-300"></textarea>
                    </div>

                    <div>
                        <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Jumlah Peserta <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah_peserta_rapat" id="jumlahPesertaRapat" min="1"
                               placeholder="Contoh: 20"
                               class="w-full h-14 rounded-xl px-5 border-2 border-gray-200
                                      focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100
                                      text-gray-800 transition-all hover:border-gray-300">
                    </div>

                    <div class="space-y-3">
                        <label class="text-xs text-gray-600 font-semibold block">Snack Corner</label>
                        <div class="flex gap-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="snack_corner" id="snackYa" value="Ya"
                                       class="w-4 h-4 accent-indigo-600"
                                       onchange="document.getElementById('snackDetail').classList.remove('hidden')">
                                <span class="text-sm text-gray-700">Ya, perlu snack</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="snack_corner" id="snackTidak" value="Tidak" checked
                                       class="w-4 h-4 accent-indigo-600"
                                       onchange="document.getElementById('snackDetail').classList.add('hidden')">
                                <span class="text-sm text-gray-700">Tidak perlu</span>
                            </label>
                        </div>
                        <div id="snackDetail" class="hidden">
                            <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Jumlah Porsi Snack <span class="text-red-500">*</span></label>
                            <input type="number" name="jumlah_snack" id="jumlahSnack" min="1"
                                   placeholder="Contoh: 25"
                                   class="w-full h-14 rounded-xl px-5 border-2 border-gray-200
                                          focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100
                                          text-gray-800 transition-all hover:border-gray-300">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs text-gray-600 font-semibold mb-1.5 block">
                            Catatan dari Peminjam
                            <span class="text-gray-400 font-normal">(Opsional)</span>
                        </label>
                        <textarea name="catatan_peminjam" id="catatanPeminjam" rows="3"
                                  placeholder="Contoh:&#10;• Butuh proyektor&#10;• Meja theatre style&#10;• Microphone wireless"
                                  class="w-full rounded-xl px-4 py-3 border-2 border-gray-200
                                         focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100
                                         text-gray-800 transition-all resize-none
                                         placeholder:text-gray-400 hover:border-gray-300"></textarea>
                    </div>

                    <div class="flex items-start gap-2 p-3 bg-indigo-50 border border-indigo-200 rounded-lg">
                        <svg class="w-5 h-5 text-indigo-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-xs text-indigo-700 leading-relaxed">
                            Permintaan penggunaan ruang rapat memerlukan <strong>persetujuan admin</strong> terlebih dahulu.
                        </p>
                    </div>

                </div><!-- end #blokRuangRapat -->

                <!-- ================================================
                     BLOK C: FORM KENDARAAN DINAS
                     Urutan baru: Tipe → Tanggal → Pilih Kendaraan (AJAX) → Detail lainnya
                ================================================ -->
                <div id="blokKendaraan" class="hidden space-y-5">

                    <div class="flex items-center gap-3 pb-3 border-b-2 border-amber-100">
                        <div class="p-2 rounded-xl bg-amber-100">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800">Formulir Permintaan Kendaraan Dinas</p>
                            <p class="text-xs text-gray-500">Pilih tipe dan tanggal dulu, lalu pilih kendaraan yang tersedia</p>
                        </div>
                    </div>

                    <!-- LANGKAH 1: Tipe Peminjaman -->
                    <div>
                        <label class="text-xs text-gray-600 font-semibold mb-2 block">
                            Tipe Peminjaman <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <label id="lblHarian"
                                   class="flex items-center gap-3 p-4 rounded-xl border-2
                                          border-amber-400 bg-amber-50 cursor-pointer transition-all">
                                <input type="radio" name="kend_tipe_pinjam" value="harian" checked
                                       class="w-4 h-4 accent-amber-500">
                                <div>
                                    <p class="font-semibold text-gray-800 text-sm">📅 Harian</p>
                                    <p class="text-xs text-gray-500">Pinjam lintas hari</p>
                                </div>
                            </label>
                            <label id="lblPerJam"
                                   class="flex items-center gap-3 p-4 rounded-xl border-2
                                          border-gray-200 cursor-pointer transition-all">
                                <input type="radio" name="kend_tipe_pinjam" value="per_jam"
                                       class="w-4 h-4 accent-amber-500">
                                <div>
                                    <p class="font-semibold text-gray-800 text-sm">🕐 Per Jam</p>
                                    <p class="text-xs text-gray-500">Pinjam dalam 1 hari</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- LANGKAH 2a: Tanggal — HARIAN -->
                    <div id="kendSeksiHarian" class="space-y-4">
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs text-gray-600 font-semibold mb-1.5 block">
                                    Tanggal Pinjam <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="kend_tanggal_pinjam" id="kendTanggalPinjam"
                                       min="<?= date('Y-m-d') ?>"
                                       class="w-full h-14 rounded-xl px-5 border-2 border-gray-200
                                              focus:border-amber-500 focus:ring-4 focus:ring-amber-100
                                              text-gray-800 transition-all hover:border-gray-300">
                            </div>
                            <div>
                                <label class="text-xs text-gray-600 font-semibold mb-1.5 block">
                                    Estimasi Tanggal Kembali <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="kend_tanggal_kembali" id="kendTanggalKembali"
                                       min="<?= date('Y-m-d') ?>"
                                       class="w-full h-14 rounded-xl px-5 border-2 border-gray-200
                                              focus:border-amber-500 focus:ring-4 focus:ring-amber-100
                                              text-gray-800 transition-all hover:border-gray-300">
                            </div>
                        </div>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Jam Mulai <span class="text-red-500">*</span></label>
                                <input type="time" name="kend_jam_pinjam" id="kendJamMulaiHarian"
                                       class="w-full h-14 rounded-xl px-5 border-2 border-gray-200
                                              focus:border-amber-500 focus:ring-4 focus:ring-amber-100
                                              text-gray-800 transition-all hover:border-gray-300">
                            </div>
                            <div>
                                <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Estimasi Durasi</label>
                                <select name="kend_durasi_harian" id="kendDurasiHarian"
                                        class="w-full h-14 rounded-xl px-5 border-2 border-gray-200
                                               focus:border-amber-500 focus:ring-4 focus:ring-amber-100
                                               text-gray-800 transition-all hover:border-gray-300">
                                    <option value="">— Opsional —</option>
                                    <option value="60">1 jam</option>
                                    <option value="120">2 jam</option>
                                    <option value="180">3 jam</option>
                                    <option value="240">4 jam</option>
                                    <option value="300">5 jam</option>
                                    <option value="360">6 jam</option>
                                    <option value="420">7 jam</option>
                                    <option value="480">8 jam (full day)</option>
                                </select>
                            </div>
                        </div>
                        <div id="infoKembaliHarian" class="hidden flex items-center gap-2 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                            <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-xs text-amber-700">
                                Estimasi selesai hari pertama: <strong id="tampilKembaliHarian">--:--</strong> WIB
                            </p>
                        </div>
                    </div>

                    <!-- LANGKAH 2b: Tanggal — PER JAM -->
                    <div id="kendSeksiPerJam" class="hidden space-y-4">
                        <div>
                            <label class="text-xs text-gray-600 font-semibold mb-1.5 block">
                                Tanggal Pemakaian <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="kend_tanggal_perjam" id="kendTanggalPerJam"
                                   min="<?= date('Y-m-d') ?>"
                                   class="w-full h-14 rounded-xl px-5 border-2 border-gray-200
                                          focus:border-amber-500 focus:ring-4 focus:ring-amber-100
                                          text-gray-800 transition-all hover:border-gray-300">
                        </div>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Jam Mulai <span class="text-red-500">*</span></label>
                                <input type="time" name="kend_jam_pinjam" id="kendJamPinjam"
                                       class="w-full h-14 rounded-xl px-5 border-2 border-gray-200
                                              focus:border-amber-500 focus:ring-4 focus:ring-amber-100
                                              text-gray-800 transition-all hover:border-gray-300">
                            </div>
                            <div>
                                <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Durasi <span class="text-red-500">*</span></label>
                                <select name="kend_durasi_perjam" id="kendDurasiPerJam"
                                        class="w-full h-14 rounded-xl px-5 border-2 border-gray-200
                                               focus:border-amber-500 focus:ring-4 focus:ring-amber-100
                                               text-gray-800 transition-all hover:border-gray-300">
                                    <option value="">Pilih Durasi</option>
                                    <option value="30">30 Menit</option>
                                    <option value="60">1 Jam</option>
                                    <option value="90">1 Jam 30 Menit</option>
                                    <option value="120">2 Jam</option>
                                    <option value="150">2 Jam 30 Menit</option>
                                    <option value="180">3 Jam</option>
                                    <option value="240">4 Jam</option>
                                    <option value="300">5 Jam</option>
                                    <option value="360">6 Jam</option>
                                    <option value="420">7 Jam</option>
                                    <option value="480">8 Jam (Full Day)</option>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="kend_jam_kembali" id="kendJamKembali">
                        <div id="infoDurasiKend" class="hidden flex items-center gap-2 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                            <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-xs text-amber-700">
                                Selesai pukul: <strong id="tampilDurasiKend">--:--</strong> WIB &nbsp;·&nbsp; Dihitung otomatis
                            </p>
                        </div>
                    </div>

                    <!-- LANGKAH 3: Pilih Kendaraan (dimuat AJAX setelah tanggal diisi) -->
                    <div>
                        <label class="text-xs text-gray-600 font-semibold mb-1.5 block">
                            Pilih Kendaraan <span class="text-red-500">*</span>
                        </label>
                        <select name="kend_kendaraan_id" id="kendKendaraanId"
                                class="w-full h-14 rounded-xl px-5 border-2 border-gray-200
                                       focus:border-amber-500 focus:ring-4 focus:ring-amber-100
                                       text-gray-800 transition-all hover:border-gray-300">
                            <option value="">── Pilih tanggal dulu ──</option>
                        </select>
                        <div id="infoKendaraan" class="hidden mt-2 p-3 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-800 leading-relaxed"></div>
                    </div>

                    <!-- Detail lainnya -->
                    <div>
                        <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Unit/Bidang Kerja <span class="text-red-500">*</span></label>
                        <input type="text" name="kend_unit_kerja" id="kendUnitKerja"
                               placeholder="Contoh: Bagian Keuangan / Tim IPDS"
                               class="w-full h-14 rounded-xl px-5 border-2 border-gray-200
                                      focus:border-amber-500 focus:ring-4 focus:ring-amber-100
                                      text-gray-800 transition-all hover:border-gray-300">
                    </div>

                    <div>
                        <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Keperluan / Tujuan Perjalanan <span class="text-red-500">*</span></label>
                        <input type="text" name="kend_keperluan" id="kendKeperluan"
                               placeholder="Contoh: Survey ke Kab. Kampar"
                               class="w-full h-14 rounded-xl px-5 border-2 border-gray-200
                                      focus:border-amber-500 focus:ring-4 focus:ring-amber-100
                                      text-gray-800 transition-all hover:border-gray-300">
                    </div>

                    <div>
                        <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Lokasi Tujuan</label>
                        <input type="text" name="kend_tujuan" id="kendTujuan"
                               placeholder="Contoh: Kab. Kampar, Kantor BPS Pekanbaru"
                               class="w-full h-14 rounded-xl px-5 border-2 border-gray-200
                                      focus:border-amber-500 focus:ring-4 focus:ring-amber-100
                                      text-gray-800 transition-all hover:border-gray-300">
                    </div>

                    <div>
                        <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Pengemudi</label>
                        <input type="text" name="kend_pengemudi" id="kendPengemudi"
                               placeholder="Nama pengemudi, atau kosongkan jika mengemudi sendiri"
                               class="w-full h-14 rounded-xl px-5 border-2 border-gray-200
                                      focus:border-amber-500 focus:ring-4 focus:ring-amber-100
                                      text-gray-800 transition-all hover:border-gray-300">
                    </div>

                    <div>
                        <label class="text-xs text-gray-600 font-semibold mb-1.5 block">No. HP Peminjam</label>
                        <input type="text" name="kend_no_hp" id="kendNoHp"
                               placeholder="08xxxxxxxxxx"
                               class="w-full h-14 rounded-xl px-5 border-2 border-gray-200
                                      focus:border-amber-500 focus:ring-4 focus:ring-amber-100
                                      text-gray-800 transition-all hover:border-gray-300">
                    </div>

                    <div>
                        <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Catatan Tambahan <span class="text-gray-400 font-normal">(Opsional)</span></label>
                        <textarea name="kend_catatan" id="kendCatatan" rows="3"
                                  placeholder="Informasi tambahan yang perlu diketahui..."
                                  class="w-full rounded-xl px-4 py-3 border-2 border-gray-200
                                         focus:border-amber-500 focus:ring-4 focus:ring-amber-100
                                         text-gray-800 transition-all resize-none
                                         placeholder:text-gray-400 hover:border-gray-300"></textarea>
                    </div>

                    <div class="flex items-start gap-2 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                        <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-xs text-amber-700 leading-relaxed">
                            Peminjaman kendaraan memerlukan <strong>persetujuan admin</strong>.
                            Kendaraan yang sudah dipesan pada tanggal tersebut tidak bisa dipilih.
                        </p>
                    </div>

                </div><!-- end #blokKendaraan -->

                <!-- ================================================
                     BLOK D: FORM PEMINJAMAN BMN
                     Urutan baru: Tanggal → Pilih Barang (AJAX) → Detail lainnya
                ================================================ -->
                <div id="blokBmn" class="hidden space-y-5">

                    <div class="flex items-center gap-3 pb-3 border-b-2 border-purple-100">
                        <div class="p-2 rounded-xl bg-purple-100">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800">Formulir Peminjaman Barang BMN</p>
                            <p class="text-xs text-gray-500">Pilih tanggal dulu, lalu pilih barang yang tersedia</p>
                        </div>
                    </div>

                    <!-- LANGKAH 1: Tanggal dulu -->
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-gray-600 font-semibold mb-1.5 block">
                                Tanggal Pinjam <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="bmn_tanggal_pinjam" id="bmnTanggalPinjam"
                                   min="<?= date('Y-m-d') ?>"
                                   class="w-full h-14 rounded-xl px-5 border-2 border-gray-200
                                          focus:border-purple-500 focus:ring-4 focus:ring-purple-100
                                          text-gray-800 transition-all hover:border-gray-300">
                        </div>
                        <div>
                            <label class="text-xs text-gray-600 font-semibold mb-1.5 block">
                                Rencana Tanggal Kembali <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="bmn_tanggal_kembali" id="bmnTanggalKembali"
                                   min="<?= date('Y-m-d') ?>"
                                   class="w-full h-14 rounded-xl px-5 border-2 border-gray-200
                                          focus:border-purple-500 focus:ring-4 focus:ring-purple-100
                                          text-gray-800 transition-all hover:border-gray-300">
                        </div>
                    </div>

                    <!-- LANGKAH 2: Pilih Barang (dimuat AJAX setelah tanggal diisi) -->
                    <div>
                        <label class="text-xs text-gray-600 font-semibold mb-1.5 block">
                            Pilih Barang BMN <span class="text-red-500">*</span>
                        </label>
                        <select name="bmn_id" id="bmnId"
                                class="w-full h-14 rounded-xl px-5 border-2 border-gray-200
                                       focus:border-purple-500 focus:ring-4 focus:ring-purple-100
                                       text-gray-800 transition-all hover:border-gray-300">
                            <option value="">── Pilih tanggal pinjam dulu ──</option>
                        </select>
                        <div id="infoBmn" class="hidden mt-2 p-3 bg-purple-50 border border-purple-200 rounded-lg text-xs text-purple-800 leading-relaxed"></div>
                    </div>

                    <!-- Detail lainnya -->
                    <div>
                        <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Unit/Bidang Kerja <span class="text-red-500">*</span></label>
                        <input type="text" name="bmn_unit_kerja" id="bmnUnitKerja"
                               placeholder="Contoh: Bagian Keuangan / Tim IPDS"
                               class="w-full h-14 rounded-xl px-5 border-2 border-gray-200
                                      focus:border-purple-500 focus:ring-4 focus:ring-purple-100
                                      text-gray-800 transition-all hover:border-gray-300">
                    </div>

                    <div>
                        <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Tujuan Peminjaman <span class="text-red-500">*</span></label>
                        <textarea name="bmn_tujuan" id="bmnTujuan" rows="3"
                                  placeholder="Contoh: Digunakan untuk kegiatan survei lapangan"
                                  class="w-full rounded-xl px-4 py-3 border-2 border-gray-200
                                         focus:border-purple-500 focus:ring-4 focus:ring-purple-100
                                         text-gray-800 transition-all resize-none
                                         placeholder:text-gray-400 hover:border-gray-300"></textarea>
                    </div>

                    <div>
                        <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Catatan Tambahan <span class="text-gray-400 font-normal">(Opsional)</span></label>
                        <textarea name="bmn_catatan" id="bmnCatatan" rows="3"
                                  placeholder="Informasi tambahan..."
                                  class="w-full rounded-xl px-4 py-3 border-2 border-gray-200
                                         focus:border-purple-500 focus:ring-4 focus:ring-purple-100
                                         text-gray-800 transition-all resize-none
                                         placeholder:text-gray-400 hover:border-gray-300"></textarea>
                    </div>

                    <div class="flex items-start gap-2 p-3 bg-purple-50 border border-purple-200 rounded-lg">
                        <svg class="w-5 h-5 text-purple-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-xs text-purple-700 leading-relaxed">
                            Peminjaman BMN memerlukan <strong>persetujuan admin</strong>.
                            Barang yang sudah dipinjam pada tanggal tersebut tidak bisa dipilih.
                        </p>
                    </div>

                </div><!-- end #blokBmn -->

                <!-- ACTION BUTTONS -->
                <div class="flex gap-4 pt-6 border-t-2 border-gray-100">
                    <button type="button" id="btnSubmit"
                            class="flex-1 h-14 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700
                                   hover:from-blue-700 hover:to-blue-800 text-white font-bold text-lg
                                   shadow-lg hover:shadow-xl transition-all duration-300
                                   flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Kirim Permintaan
                    </button>
                    <button type="button" id="btnCancel"
                            class="h-14 px-8 rounded-xl border-2 border-gray-300
                                   hover:bg-gray-100 hover:border-gray-400
                                   text-gray-700 font-semibold transition-all duration-300">
                        Batal
                    </button>
                </div>

            </div><!-- end #formDetails -->

        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
/* ================================================================
   DOM REFS
================================================================ */
const form                = document.getElementById('permintaanForm');
const subKategori         = document.getElementById('subKategori');
const formDetails         = document.getElementById('formDetails');
const blokLayananBiasa    = document.getElementById('blokLayananBiasa');
const blokRuangRapat      = document.getElementById('blokRuangRapat');
const blokKendaraan       = document.getElementById('blokKendaraan');
const blokBmnEl           = document.getElementById('blokBmn');
const isRuangRapatInput   = document.getElementById('isRuangRapat');
const isKendaraanInput    = document.getElementById('isKendaraan');
const isBmnInput          = document.getElementById('isBmn');
const tanggalInput        = document.getElementById('tanggal');
const tanggalSelesaiInput = document.getElementById('tanggalSelesai');
const jamInput            = document.getElementById('jam');
const durasiInput         = document.getElementById('durasi');
const jenisLayanan        = document.getElementById('jenisLayanan');
const jumlahPeserta       = document.getElementById('jumlahPeserta');
const jadwalSection       = document.getElementById('jadwalSection');
const catatanDenganJadwal = document.getElementById('catatanDenganJadwal');
const catatanTanpaJadwal  = document.getElementById('catatanTanpaJadwal');
const ringkasanJadwal     = document.getElementById('ringkasanJadwal');
const ringkasanTeks       = document.getElementById('ringkasanTeks');
const headerBadge         = document.getElementById('headerBadge');
const headerJudul         = document.getElementById('headerJudul');
const headerSubjudul      = document.getElementById('headerSubjudul');

let perluJadwal  = false;
let isRuangRapat = false;
let isKendaraan  = false;
let isBmn        = false;

/* Min tanggal = hari ini */
const todayStr = new Date().toISOString().split('T')[0];
document.getElementById('tanggal').min      = todayStr;
document.getElementById('tanggalRapat').min = todayStr;

/* ================================================================
   UPDATE HEADER
================================================================ */
function updateHeader(slug) {
    if (slug === 'permintaan-ruang-rapat') {
        headerBadge.textContent    = 'Permintaan Layanan Umum';
        headerJudul.textContent    = 'Permintaan Layanan Umum';
        headerSubjudul.textContent = 'Ajukan permintaan penggunaan ruang rapat secara terstruktur dan terdokumentasi';
    } else if (slug === 'permintaan-kendaraan') {
        headerBadge.textContent    = 'Permintaan Layanan Umum';
        headerJudul.textContent    = 'Permintaan Kendaraan Dinas';
        headerSubjudul.textContent = 'Ajukan peminjaman kendaraan dinas secara terstruktur dan terdokumentasi';
    } else if (slug === 'permintaan-bmn') {
        headerBadge.textContent    = 'Permintaan Layanan Umum';
        headerJudul.textContent    = 'Peminjaman Barang BMN';
        headerSubjudul.textContent = 'Ajukan peminjaman barang milik negara secara terstruktur dan terdokumentasi';
    } else {
        headerBadge.textContent    = 'Permintaan Layanan IT';
        headerJudul.textContent    = 'Permintaan Layanan IT';
        headerSubjudul.textContent = 'Ajukan kebutuhan layanan IT secara terstruktur, aman, dan terdokumentasi';
    }
}

/* ================================================================
   RUANG RAPAT — JAM SELESAI OTOMATIS
================================================================ */
function hitungJamSelesai() {
    const jamMulai  = document.getElementById('jamMulai').value;
    const durasi    = parseInt(document.getElementById('durasiRapat').value);
    const infoBox   = document.getElementById('infoJamSelesai');
    const hiddenJam = document.getElementById('jamSelesai');
    const tampil    = document.getElementById('tampilJamSelesai');

    if (!jamMulai || !durasi) {
        infoBox.classList.add('hidden');
        hiddenJam.value = '';
        return;
    }

    const [h, m]       = jamMulai.split(':').map(Number);
    const mulaiMenit   = h * 60 + m;
    const selesaiMenit = mulaiMenit + durasi;

    if (mulaiMenit < 7 * 60) {
        Swal.fire({ icon:'warning', title:'Jam mulai terlalu awal',
                    text:'Jam mulai minimal pukul 07:00 WIB.', confirmButtonColor:'#4338ca' });
        document.getElementById('jamMulai').value = '';
        infoBox.classList.add('hidden');
        hiddenJam.value = '';
        return;
    }
    if (selesaiMenit > 17 * 60) {
        const maxJam = Math.floor((17 * 60 - mulaiMenit) / 60);
        Swal.fire({ icon:'warning', title:'Durasi melebihi jam kerja',
                    text:`Dengan jam mulai ${jamMulai}, durasi maksimal adalah ${maxJam} jam (selesai pukul 17:00 WIB).`,
                    confirmButtonColor:'#4338ca' });
        document.getElementById('durasiRapat').value = '';
        infoBox.classList.add('hidden');
        hiddenJam.value = '';
        return;
    }

    const hasil     = String(Math.floor(selesaiMenit / 60)).padStart(2, '0')
                    + ':' + String(selesaiMenit % 60).padStart(2, '0');
    hiddenJam.value = hasil;
    tampil.textContent = hasil;
    infoBox.classList.remove('hidden');
    muatRuangan();
}

document.getElementById('tanggalRapat').addEventListener('change', muatRuangan);
document.getElementById('jamMulai').addEventListener('change',    hitungJamSelesai);
document.getElementById('durasiRapat').addEventListener('change', hitungJamSelesai);

/* ================================================================
   RUANG RAPAT — LOAD RUANGAN (AJAX)
================================================================ */
async function muatRuangan() {
    const tanggal    = document.getElementById('tanggalRapat').value;
    const jamMulai   = document.getElementById('jamMulai').value;
    const jamSelesai = document.getElementById('jamSelesai').value;
    const sel        = document.getElementById('ruanganId');

    if (!tanggal) {
        sel.innerHTML = '<option value="">── Pilih tanggal dulu ──</option>';
        return;
    }

    sel.innerHTML = '<option value="">── Memuat ruangan... ──</option>';
    document.getElementById('infoRuangan').classList.add('hidden');
    document.getElementById('jadwalRuangan').classList.add('hidden');

    let url = '<?= base_url('user_ticket/get_ruangan_aktif') ?>?tanggal=' + encodeURIComponent(tanggal);
    if (jamMulai && jamSelesai) {
        url += '&jam_mulai=' + encodeURIComponent(jamMulai)
             + '&jam_selesai=' + encodeURIComponent(jamSelesai);
    }

    try {
        const res  = await fetch(url);
        const list = await res.json();

        if (!list.length) {
            sel.innerHTML = '<option value="">Tidak ada ruangan tersedia</option>';
            return;
        }

        sel.innerHTML = '<option value="">── Pilih ruangan ──</option>';
        list.forEach(r => {
            const o             = document.createElement('option');
            o.value             = r.id;
            o.dataset.kapasitas = r.kapasitas;
            o.dataset.lokasi    = r.lokasi    || '-';
            o.dataset.fasilitas = r.fasilitas || '';
            o.dataset.jadwal    = JSON.stringify(r.jadwal_terpakai || []);
            o.dataset.tersedia  = r.tersedia ? '1' : '0';

            if (r.tersedia) {
                o.textContent = '✓ ' + r.nama_ruangan + '  (Kap. ' + r.kapasitas + ' org)';
            } else {
                o.textContent = '✗ ' + r.nama_ruangan + '  (Sudah terpesan)';
                o.disabled    = true;
                o.style.color = '#9ca3af';
            }
            sel.appendChild(o);
        });
    } catch (e) {
        sel.innerHTML = '<option value="">Gagal memuat — coba refresh halaman</option>';
    }
}

document.getElementById('ruanganId').addEventListener('change', function () {
    const infoEl   = document.getElementById('infoRuangan');
    const jadwalEl = document.getElementById('jadwalRuangan');

    if (!this.value) { infoEl.classList.add('hidden'); jadwalEl.classList.add('hidden'); return; }

    const o      = this.options[this.selectedIndex];
    const jadwal = JSON.parse(o.dataset.jadwal || '[]');

    let html = '📍 ' + o.dataset.lokasi + ' &nbsp;·&nbsp; 👥 Kapasitas: ' + o.dataset.kapasitas + ' orang';
    if (o.dataset.fasilitas) html += '<br>🔧 Fasilitas: ' + o.dataset.fasilitas;
    infoEl.innerHTML = html;
    infoEl.classList.remove('hidden');

    if (jadwal.length) {
        let jHtml = '<strong class="block mb-1">📋 Jadwal pada tanggal ini:</strong>';
        jadwal.forEach(j => {
            const icon  = j.status === 'Disetujui' ? '🔴' : '🟡';
            const label = j.status === 'Disetujui' ? 'Dikonfirmasi' : 'Menunggu persetujuan (slot terpesan)';
            jHtml += `<span class="block">${icon} ${j.jam_mulai}–${j.jam_selesai} &nbsp; ${j.keperluan} <span class="text-gray-500">(${label})</span></span>`;
        });
        jadwalEl.innerHTML = jHtml;
        jadwalEl.classList.remove('hidden');
    } else {
        jadwalEl.classList.add('hidden');
    }
});

/* ================================================================
   KENDARAAN — LOAD KENDARAAN (AJAX) berdasarkan tanggal
================================================================ */
async function muatKendaraan() {
    const tipe       = document.querySelector('input[name="kend_tipe_pinjam"]:checked')?.value || 'harian';
    const tglPinjam  = tipe === 'per_jam'
        ? document.getElementById('kendTanggalPerJam').value
        : document.getElementById('kendTanggalPinjam').value;
    const tglKembali = tipe === 'per_jam'
        ? document.getElementById('kendTanggalPerJam').value
        : document.getElementById('kendTanggalKembali').value;

    const sel      = document.getElementById('kendKendaraanId');
    const infoBox  = document.getElementById('infoKendaraan');

    infoBox.classList.add('hidden');

    if (!tglPinjam) {
        sel.innerHTML = '<option value="">── Pilih tanggal dulu ──</option>';
        return;
    }

    sel.innerHTML = '<option value="">── Memuat kendaraan... ──</option>';

    try {
       const jamPinjam  = tipe === 'per_jam' ? document.getElementById('kendJamPinjam').value  : '';
        const jamKembali = tipe === 'per_jam' ? document.getElementById('kendJamKembali').value : '';

        let url = '<?= base_url('user_ticket/get_kendaraan_tersedia') ?>'
                + '?tgl_pinjam='  + encodeURIComponent(tglPinjam)
                + '&tgl_kembali=' + encodeURIComponent(tglKembali || tglPinjam);

        if (tipe === 'per_jam' && jamPinjam && jamKembali) {
            url += '&jam_pinjam='  + encodeURIComponent(jamPinjam)
                 + '&jam_kembali=' + encodeURIComponent(jamKembali);
        }

        const res  = await fetch(url);
        const list = await res.json();

        if (!list.length) {
            sel.innerHTML = '<option value="">Tidak ada kendaraan terdaftar</option>';
            return;
        }

        const adaTersedia = list.some(k => k.tersedia);
        sel.innerHTML = '<option value="">── Pilih Kendaraan ──</option>';

        list.forEach(k => {
            const o   = document.createElement('option');
            o.value   = k.id;
            o.dataset.jenis        = k.jenis;
            o.dataset.namaKendaraan= k.nama_kendaraan;
            o.dataset.nomorPolisi  = k.nomor_polisi;
            o.dataset.tersedia     = k.tersedia ? '1' : '0';

            const ikon = k.jenis === 'Mobil' ? '🚗' : '🏍️';
            if (k.tersedia) {
                o.textContent = ikon + ' ' + k.nama_kendaraan + ' (' + k.nomor_polisi + ')';
            } else {
                o.textContent = ikon + ' ' + k.nama_kendaraan + ' (' + k.nomor_polisi + ') — Sudah Dipesan';
                o.disabled    = true;
                o.style.color = '#9ca3af';
            }
            sel.appendChild(o);
        });

        if (!adaTersedia) {
            infoBox.innerHTML = '⚠️ Semua kendaraan sudah dipesan pada rentang tanggal tersebut. Silakan pilih tanggal lain.';
            infoBox.classList.remove('hidden');
        }
    } catch (e) {
        sel.innerHTML = '<option value="">Gagal memuat — coba refresh halaman</option>';
    }
}

/* Event listener tanggal kendaraan */
document.getElementById('kendTanggalPinjam').addEventListener('change', function () {
    /* Set min tanggal kembali = tanggal pinjam */
    document.getElementById('kendTanggalKembali').min = this.value;
    muatKendaraan();
});
document.getElementById('kendTanggalKembali').addEventListener('change', muatKendaraan);
document.getElementById('kendTanggalPerJam').addEventListener('change',  muatKendaraan);

/* Event listener tipe kendaraan */
document.querySelectorAll('input[name="kend_tipe_pinjam"]').forEach(r => {
    r.addEventListener('change', function () {
        toggleTipeKendaraan(this.value);
        muatKendaraan();
    });
});

/* Info kendaraan yang dipilih */
document.getElementById('kendKendaraanId').addEventListener('change', function () {
    const infoBox = document.getElementById('infoKendaraan');
    if (!this.value) { infoBox.classList.add('hidden'); return; }
    const o = this.options[this.selectedIndex];
    if (o.dataset.tersedia === '0') { infoBox.classList.add('hidden'); return; }
    infoBox.innerHTML  = (o.dataset.jenis === 'Mobil' ? '🚗' : '🏍️')
                       + ' <strong>' + o.dataset.namaKendaraan + '</strong>'
                       + ' &nbsp;·&nbsp; Plat: ' + o.dataset.nomorPolisi;
    infoBox.classList.remove('hidden');
});

/* ================================================================
   BMN — LOAD BARANG (AJAX) berdasarkan tanggal
================================================================ */
async function muatBmn() {
    const tglPinjam  = document.getElementById('bmnTanggalPinjam').value;
    const tglKembali = document.getElementById('bmnTanggalKembali').value;
    const sel        = document.getElementById('bmnId');
    const infoBox    = document.getElementById('infoBmn');

    infoBox.classList.add('hidden');

    if (!tglPinjam) {
        sel.innerHTML = '<option value="">── Pilih tanggal pinjam dulu ──</option>';
        return;
    }

    sel.innerHTML = '<option value="">── Memuat barang... ──</option>';

    try {
        const url  = '<?= base_url('user_ticket/get_bmn_tersedia') ?>'
                   + '?tgl_pinjam='  + encodeURIComponent(tglPinjam)
                   + '&tgl_kembali=' + encodeURIComponent(tglKembali || tglPinjam);
        const res  = await fetch(url);
        const list = await res.json();

        if (!list.length) {
            sel.innerHTML = '<option value="">Tidak ada barang BMN terdaftar</option>';
            return;
        }

        const adaTersedia = list.some(b => b.tersedia);
        sel.innerHTML = '<option value="">── Pilih Barang ──</option>';

        list.forEach(b => {
            const o   = document.createElement('option');
            o.value   = b.id;
            o.dataset.namaBmn  = b.nama_barang;
            o.dataset.kondisi  = b.kondisi || '';
            o.dataset.lokasi   = b.lokasi  || '';
            o.dataset.tersedia = b.tersedia ? '1' : '0';

            if (b.tersedia) {
                o.textContent = '📦 ' + b.nama_barang
                    + (b.merek ? ' – ' + b.merek : '')
                    + ' (' + b.kode_barang + '/' + b.nup + ')'
                    + (b.kondisi ? ' — ' + b.kondisi : '');
            } else {
                o.textContent = '📦 ' + b.nama_barang
                    + ' (' + b.kode_barang + '/' + b.nup + ') — Sedang Dipinjam';
                o.disabled    = true;
                o.style.color = '#9ca3af';
            }
            sel.appendChild(o);
        });

        if (!adaTersedia) {
            infoBox.innerHTML = '⚠️ Semua barang BMN sedang dipinjam pada rentang tanggal tersebut. Silakan pilih tanggal lain.';
            infoBox.classList.remove('hidden');
        }
    } catch (e) {
        sel.innerHTML = '<option value="">Gagal memuat — coba refresh halaman</option>';
    }
}

/* Event listener tanggal BMN */
document.getElementById('bmnTanggalPinjam').addEventListener('change', function () {
    document.getElementById('bmnTanggalKembali').min = this.value;
    /* Reset pilihan kembali jika lebih kecil dari pinjam */
    const tglK = document.getElementById('bmnTanggalKembali');
    if (tglK.value && tglK.value < this.value) tglK.value = '';
    muatBmn();
});
document.getElementById('bmnTanggalKembali').addEventListener('change', muatBmn);

/* Info barang yang dipilih */
document.getElementById('bmnId').addEventListener('change', function () {
    const infoBox = document.getElementById('infoBmn');
    if (!this.value) { infoBox.classList.add('hidden'); return; }
    const o = this.options[this.selectedIndex];
    if (o.dataset.tersedia === '0') { infoBox.classList.add('hidden'); return; }
    let html = '📦 <strong>' + o.dataset.namaBmn + '</strong>';
    if (o.dataset.kondisi) html += ' &nbsp;·&nbsp; Kondisi: ' + o.dataset.kondisi;
    if (o.dataset.lokasi)  html += ' &nbsp;·&nbsp; Lokasi: '  + o.dataset.lokasi;
    infoBox.innerHTML = html;
    infoBox.classList.remove('hidden');
});

/* ================================================================
   JADWAL IT — RINGKASAN
================================================================ */
function hitungRingkasan() {
    const mulai   = tanggalInput.value;
    const selesai = tanggalSelesaiInput.value;
    if (!mulai) { ringkasanJadwal.classList.add('hidden'); return; }

    let teks, isErr = false;
    if (!selesai) {
        teks = '📅 ' + formatTanggal(mulai) + ' (1 hari)';
        if (jamInput.value)    teks += ' · Mulai pukul ' + jamInput.value + ' WIB';
        if (durasiInput.value) teks += ' · ' + durasiInput.value + ' jam';
    } else {
        const tM = new Date(mulai), tS = new Date(selesai);
        if (tS <= tM) {
            teks  = '⚠️ Tanggal selesai harus setelah tanggal mulai.';
            isErr = true;
        } else {
            const h = Math.round((tS - tM) / 86400000) + 1;
            teks    = '📅 ' + h + ' hari (' + formatTanggal(mulai) + ' s.d. ' + formatTanggal(selesai) + ')';
            if (jamInput.value)    teks += ' · Mulai pukul ' + jamInput.value + ' WIB/hari';
            if (durasiInput.value) teks += ' · ' + durasiInput.value + ' jam/hari';
        }
    }
    ringkasanTeks.textContent = teks;
    ringkasanTeks.className   = isErr ? 'text-sm text-red-600 font-semibold' : 'text-sm text-indigo-700 font-semibold';
    ringkasanJadwal.className = isErr
        ? 'p-3 rounded-lg border border-red-200 bg-red-50'
        : 'p-3 rounded-lg border border-indigo-200 bg-indigo-50';
    ringkasanJadwal.classList.remove('hidden');
}

function formatTanggal(d) {
    return new Date(d + 'T00:00:00').toLocaleDateString('id-ID',
        { weekday:'long', day:'numeric', month:'long', year:'numeric' });
}

tanggalInput.addEventListener('change', function () {
    if (this.value) {
        const nx = new Date(this.value); nx.setDate(nx.getDate() + 1);
        tanggalSelesaiInput.min = nx.toISOString().split('T')[0];
    } else {
        tanggalSelesaiInput.removeAttribute('min');
        tanggalSelesaiInput.value = '';
    }
    hitungRingkasan();
});
tanggalSelesaiInput.addEventListener('change', hitungRingkasan);
durasiInput.addEventListener('change', hitungRingkasan);
jamInput.addEventListener('change', hitungRingkasan);

/* ================================================================
   TOGGLE TIPE KENDARAAN (Harian / Per Jam)
================================================================ */
function toggleTipeKendaraan(tipe) {
    const seksiHarian = document.getElementById('kendSeksiHarian');
    const seksiPerJam = document.getElementById('kendSeksiPerJam');
    const lblHarian   = document.getElementById('lblHarian');
    const lblPerJam   = document.getElementById('lblPerJam');

    if (tipe === 'per_jam') {
        seksiHarian.classList.add('hidden');
        seksiPerJam.classList.remove('hidden');
        lblHarian.classList.remove('border-amber-400', 'bg-amber-50');
        lblHarian.classList.add('border-gray-200');
        lblPerJam.classList.add('border-amber-400', 'bg-amber-50');
        lblPerJam.classList.remove('border-gray-200');
    } else {
        seksiHarian.classList.remove('hidden');
        seksiPerJam.classList.add('hidden');
        lblPerJam.classList.remove('border-amber-400', 'bg-amber-50');
        lblPerJam.classList.add('border-gray-200');
        lblHarian.classList.add('border-amber-400', 'bg-amber-50');
        lblHarian.classList.remove('border-gray-200');
    }
    /* Reset pilihan kendaraan saat tipe berubah */
    document.getElementById('kendKendaraanId').innerHTML = '<option value="">── Pilih tanggal dulu ──</option>';
    document.getElementById('infoKendaraan').classList.add('hidden');
}

/* ================================================================
   KENDARAAN — ESTIMASI JAM (helpers)
================================================================ */
function hitungKembaliHarian() {
    const jam    = document.getElementById('kendJamMulaiHarian').value;
    const durasi = parseInt(document.getElementById('kendDurasiHarian').value);
    const box    = document.getElementById('infoKembaliHarian');
    if (!jam || !durasi) { box.classList.add('hidden'); return; }
    const [h, m] = jam.split(':').map(Number);
    const total  = h * 60 + m + durasi;
    const hasil  = String(Math.floor(total / 60) % 24).padStart(2, '0') + ':' + String(total % 60).padStart(2, '0');
    document.getElementById('tampilKembaliHarian').textContent = hasil;
    box.classList.remove('hidden');
}

function hitungJamKembaliKend() {
    const jam    = document.getElementById('kendJamPinjam').value;
    const durasi = parseInt(document.getElementById('kendDurasiPerJam').value);
    const box    = document.getElementById('infoDurasiKend');
    const hidden = document.getElementById('kendJamKembali');
    if (!jam || !durasi) { box.classList.add('hidden'); hidden.value = ''; return; }
    const [h, m] = jam.split(':').map(Number);
    const total  = h * 60 + m + durasi;
    const hasil  = String(Math.floor(total / 60) % 24).padStart(2, '0') + ':' + String(total % 60).padStart(2, '0');
    hidden.value = hasil;
    document.getElementById('tampilDurasiKend').textContent = hasil;
    box.classList.remove('hidden');
}

document.getElementById('kendJamMulaiHarian').addEventListener('change', hitungKembaliHarian);
document.getElementById('kendDurasiHarian').addEventListener('change',   hitungKembaliHarian);
document.getElementById('kendJamPinjam').addEventListener('change',      hitungJamKembaliKend);
document.getElementById('kendDurasiPerJam').addEventListener('change',   hitungJamKembaliKend);
document.getElementById('kendJamPinjam').addEventListener('change', function () {
    if (document.querySelector('input[name="kend_tipe_pinjam"]:checked')?.value === 'per_jam') {
        setTimeout(muatKendaraan, 100);
    }
});
document.getElementById('kendDurasiPerJam').addEventListener('change', function () {
    if (document.querySelector('input[name="kend_tipe_pinjam"]:checked')?.value === 'per_jam') {
        setTimeout(muatKendaraan, 100);
    }
});

/* ================================================================
   RESET SEMUA FIELD
================================================================ */
function resetAll() {
    blokLayananBiasa.classList.remove('hidden');
    blokRuangRapat.classList.add('hidden');
    blokKendaraan.classList.add('hidden');
    blokBmnEl.classList.add('hidden');
    jadwalSection.classList.add('hidden');
    catatanDenganJadwal.classList.add('hidden');
    catatanTanpaJadwal.classList.add('hidden');
    document.getElementById('noteZoom').classList.add('hidden');
    ringkasanJadwal.classList.add('hidden');
    document.getElementById('infoJamSelesai').classList.add('hidden');
    document.getElementById('infoRuangan').classList.add('hidden');
    document.getElementById('jadwalRuangan').classList.add('hidden');
    document.getElementById('infoKendaraan').classList.add('hidden');
    document.getElementById('infoBmn').classList.add('hidden');

    perluJadwal = false; isRuangRapat = false; isKendaraan = false; isBmn = false;
    isRuangRapatInput.value = '0';
    isKendaraanInput.value  = '0';
    isBmnInput.value        = '0';

    ['tanggal','jam','durasi'].forEach(id => document.getElementById(id)?.removeAttribute('required'));
    jumlahPeserta?.removeAttribute('required');

    /* Reset semua input teks/tanggal/select */
    ['jenisLayanan','tanggal','tanggalSelesai','jam',
     'lokasiTanpaJadwal','permintaanLainTanpaJadwal',
     'jumlahPeserta','jumlahBreakout','penanggungJawab',
     'hostCohost','permintaanLainJadwal'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
    });
    durasiInput.value = '';

    ['ruanganId','unitKerja','tanggalRapat','jamMulai',
     'durasiRapat','jamSelesai','keperluan',
     'jumlahPesertaRapat','jumlahSnack','catatanPeminjam'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
    });
    document.getElementById('snackTidak').checked = true;
    document.getElementById('snackDetail').classList.add('hidden');
    document.getElementById('ruanganId').innerHTML = '<option value="">── Pilih tanggal & jam dulu ──</option>';

    /* Reset kendaraan */
    ['kendUnitKerja','kendKeperluan','kendTujuan',
     'kendTanggalPinjam','kendTanggalKembali','kendJamMulaiHarian',
     'kendPengemudi','kendNoHp','kendCatatan',
     'kendTanggalPerJam','kendJamPinjam','kendJamKembali'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
    });
    document.getElementById('kendDurasiPerJam').value = '';
    document.getElementById('kendDurasiHarian').value = '';
    document.getElementById('infoDurasiKend').classList.add('hidden');
    document.getElementById('infoKembaliHarian').classList.add('hidden');
    document.getElementById('kendKendaraanId').innerHTML = '<option value="">── Pilih tanggal dulu ──</option>';
    /* Reset tipe ke harian */
    const radioHarian = document.querySelector('input[name="kend_tipe_pinjam"][value="harian"]');
    if (radioHarian) { radioHarian.checked = true; toggleTipeKendaraan('harian'); }

    /* Reset BMN */
    ['bmnTanggalPinjam','bmnTanggalKembali','bmnUnitKerja','bmnCatatan'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
    });
    document.getElementById('bmnTujuan').value = '';
    document.getElementById('bmnId').innerHTML = '<option value="">── Pilih tanggal pinjam dulu ──</option>';
}

/* ================================================================
   EVENT: SUB KATEGORI BERUBAH
================================================================ */
subKategori.addEventListener('change', function () {
    const opt  = this.options[this.selectedIndex];
    const slug = opt.getAttribute('data-slug') || '';

    updateHeader(this.value ? slug : '');

    if (!this.value) {
        formDetails.classList.add('hidden');
        resetAll();
        return;
    }

    resetAll();
    formDetails.classList.remove('hidden');

    if (slug === 'permintaan-ruang-rapat') {
        isRuangRapat = true;
        isRuangRapatInput.value = '1';
        blokLayananBiasa.classList.add('hidden');
        blokRuangRapat.classList.remove('hidden');

    } else if (slug === 'permintaan-kendaraan') {
        isKendaraan = true;
        isKendaraanInput.value = '1';
        blokLayananBiasa.classList.add('hidden');
        blokKendaraan.classList.remove('hidden');

    } else if (slug === 'permintaan-bmn') {
        isBmn = true;
        isBmnInput.value = '1';
        blokLayananBiasa.classList.add('hidden');
        blokBmnEl.classList.remove('hidden');

    } else if (opt.getAttribute('data-perlu-jadwal') == '1') {
        perluJadwal = true;
        jadwalSection.classList.remove('hidden');
        catatanDenganJadwal.classList.remove('hidden');
        ['tanggal','jam','durasi'].forEach(id => document.getElementById(id)?.setAttribute('required','required'));
        jumlahPeserta?.setAttribute('required','required');
        const nama = opt.text.toLowerCase();
        if (nama.includes('zoom') || nama.includes('meet')) {
            document.getElementById('noteZoom').classList.remove('hidden');
        }

    } else {
        catatanTanpaJadwal.classList.remove('hidden');
    }

    formDetails.scrollIntoView({ behavior:'smooth', block:'nearest' });
});

/* ================================================================
   VALIDASI RUANG RAPAT
================================================================ */
function validasiRuangRapat() {
    const wajib = [
        ['ruanganId',          'Pilih ruangan terlebih dahulu.'],
        ['unitKerja',          'Unit kerja wajib diisi.'],
        ['tanggalRapat',       'Tanggal pemakaian wajib diisi.'],
        ['jamMulai',           'Jam mulai wajib diisi.'],
        ['durasiRapat',        'Durasi wajib dipilih.'],
        ['keperluan',          'Keperluan / agenda wajib diisi.'],
        ['jumlahPesertaRapat', 'Jumlah peserta wajib diisi.'],
    ];
    for (const [id, msg] of wajib) {
        if (!document.getElementById(id)?.value?.trim()) {
            Swal.fire({ icon:'warning', title:msg, confirmButtonColor:'#4338ca' });
            return false;
        }
    }
    if (!document.getElementById('jamSelesai').value) {
        Swal.fire({ icon:'warning', title:'Pilih jam mulai dan durasi terlebih dahulu.', confirmButtonColor:'#4338ca' });
        return false;
    }
    const sel = document.getElementById('ruanganId');
    const opt = sel.options[sel.selectedIndex];
    if (opt && opt.dataset.tersedia === '0') {
        Swal.fire({ icon:'error', title:'Ruangan sudah terpesan!',
                    text:'Pilih ruangan lain atau ubah waktu peminjaman.', confirmButtonColor:'#4338ca' });
        return false;
    }
    if (document.getElementById('snackYa').checked && !document.getElementById('jumlahSnack').value) {
        Swal.fire({ icon:'warning', title:'Jumlah porsi snack wajib diisi.', confirmButtonColor:'#4338ca' });
        return false;
    }
    return true;
}

/* ================================================================
   VALIDASI KENDARAAN
================================================================ */
function validasiKendaraan() {
    const tipe = document.querySelector('input[name="kend_tipe_pinjam"]:checked')?.value || 'harian';

    if (!document.getElementById('kendKendaraanId').value) {
        Swal.fire({ icon:'warning', title:'Pilih kendaraan terlebih dahulu.', confirmButtonColor:'#d97706' });
        return false;
    }
    /* Cek apakah option yang dipilih masih tersedia */
    const sel = document.getElementById('kendKendaraanId');
    const opt = sel.options[sel.selectedIndex];
    if (opt && opt.dataset.tersedia === '0') {
        Swal.fire({ icon:'error', title:'Kendaraan sudah dipesan!',
                    text:'Pilih kendaraan lain atau ubah tanggal.', confirmButtonColor:'#d97706' });
        return false;
    }

    const wajibUmum = [
        ['kendUnitKerja', 'Unit kerja wajib diisi.'],
        ['kendKeperluan', 'Keperluan / tujuan perjalanan wajib diisi.'],
    ];
    for (const [id, msg] of wajibUmum) {
        if (!document.getElementById(id)?.value?.trim()) {
            Swal.fire({ icon:'warning', title:msg, confirmButtonColor:'#d97706' });
            return false;
        }
    }

    if (tipe === 'per_jam') {
        if (!document.getElementById('kendTanggalPerJam').value) {
            Swal.fire({ icon:'warning', title:'Tanggal pemakaian wajib diisi.', confirmButtonColor:'#d97706' }); return false;
        }
        if (!document.getElementById('kendJamPinjam').value) {
            Swal.fire({ icon:'warning', title:'Jam mulai wajib diisi.', confirmButtonColor:'#d97706' }); return false;
        }
        if (!document.getElementById('kendDurasiPerJam').value) {
            Swal.fire({ icon:'warning', title:'Durasi wajib dipilih.', confirmButtonColor:'#d97706' }); return false;
        }
        if (!document.getElementById('kendJamKembali').value) {
            Swal.fire({ icon:'warning', title:'Terjadi kesalahan hitung jam kembali. Coba isi ulang.', confirmButtonColor:'#d97706' }); return false;
        }
        /* Sync ke field POST */
        document.getElementById('kendTanggalPinjam').value  = document.getElementById('kendTanggalPerJam').value;
        document.getElementById('kendTanggalKembali').value = document.getElementById('kendTanggalPerJam').value;
    } else {
        const tP = document.getElementById('kendTanggalPinjam').value;
        const tK = document.getElementById('kendTanggalKembali').value;
        if (!tP) { Swal.fire({ icon:'warning', title:'Tanggal pinjam wajib diisi.', confirmButtonColor:'#d97706' }); return false; }
        if (!tK) { Swal.fire({ icon:'warning', title:'Estimasi tanggal kembali wajib diisi.', confirmButtonColor:'#d97706' }); return false; }
        if (tK < tP) { Swal.fire({ icon:'warning', title:'Tanggal kembali tidak boleh sebelum tanggal pinjam.', confirmButtonColor:'#d97706' }); return false; }
    }
    return true;
}

/* ================================================================
   BUILD KONFIRMASI
================================================================ */
function buildConfirmRapat() {
    const sel     = document.getElementById('ruanganId');
    const ruang   = sel.options[sel.selectedIndex]?.text || '-';
    const tgl     = document.getElementById('tanggalRapat').value;
    const mulai   = document.getElementById('jamMulai').value;
    const selesai = document.getElementById('jamSelesai').value;
    const dur     = document.getElementById('durasiRapat');
    const durTeks = dur.options[dur.selectedIndex]?.text || '-';
    const kep     = document.getElementById('keperluan').value;
    const peserta = document.getElementById('jumlahPesertaRapat').value;
    const snack   = document.getElementById('snackYa').checked
                    ? 'Ya (' + (document.getElementById('jumlahSnack').value || '0') + ' porsi)' : 'Tidak';
    return `<div class="text-left space-y-2 mt-4 p-4 bg-gray-50 rounded-lg text-sm">
        <p><strong>Ruangan:</strong> ${ruang}</p>
        <p><strong>Tanggal:</strong> ${formatTanggal(tgl)}</p>
        <p><strong>Jam:</strong> ${mulai} – ${selesai} WIB</p>
        <p><strong>Durasi:</strong> ${durTeks}</p>
        <p><strong>Keperluan:</strong> ${kep}</p>
        <p><strong>Jumlah Peserta:</strong> ${peserta} orang</p>
        <p><strong>Snack Corner:</strong> ${snack}</p>
    </div><p class="text-sm text-gray-600 mt-4">Pastikan semua informasi sudah benar sebelum mengirim.</p>`;
}

function buildConfirmKendaraan() {
    const tipe = document.querySelector('input[name="kend_tipe_pinjam"]:checked')?.value || 'harian';
    const sel  = document.getElementById('kendKendaraanId');
    const kend = sel.options[sel.selectedIndex]?.text?.trim() || '-';
    const kep  = document.getElementById('kendKeperluan').value;
    const tuj  = document.getElementById('kendTujuan').value;
    const drv  = document.getElementById('kendPengemudi').value || 'Sendiri';
    let waktuHtml = '';
    if (tipe === 'per_jam') {
        const tgl    = document.getElementById('kendTanggalPerJam').value;
        const jp     = document.getElementById('kendJamPinjam').value;
        const jk     = document.getElementById('kendJamKembali').value;
        const durSel = document.getElementById('kendDurasiPerJam');
        const durTxt = durSel.options[durSel.selectedIndex]?.text || '-';
        waktuHtml = `<p><strong>Tanggal:</strong> ${formatTanggal(tgl)}</p>
                     <p><strong>Jam:</strong> ${jp} – ${jk} WIB (${durTxt})</p>`;
    } else {
        const tP = document.getElementById('kendTanggalPinjam').value;
        const tK = document.getElementById('kendTanggalKembali').value;
        waktuHtml = `<p><strong>Tanggal Pinjam:</strong> ${formatTanggal(tP)}</p>
                     <p><strong>Est. Kembali:</strong> ${formatTanggal(tK)}</p>`;
    }
    return `<div class="text-left space-y-2 mt-4 p-4 bg-gray-50 rounded-lg text-sm">
        <p><strong>Kendaraan:</strong> ${kend}</p>
        <p><strong>Keperluan:</strong> ${kep}</p>
        ${tuj ? `<p><strong>Tujuan:</strong> ${tuj}</p>` : ''}
        ${waktuHtml}
        <p><strong>Pengemudi:</strong> ${drv}</p>
    </div><p class="text-sm text-gray-600 mt-4">Pastikan semua informasi sudah benar sebelum mengirim.</p>`;
}

/* ================================================================
   SUBMIT
================================================================ */
document.getElementById('btnSubmit').addEventListener('click', () => {

    if (!subKategori.value) {
        Swal.fire({ icon:'warning', title:'Jenis Layanan Wajib Dipilih',
                    text:'Silakan pilih jenis layanan terlebih dahulu.', confirmButtonColor:'#2563eb' });
        return;
    }

    /* ── Ruang Rapat ── */
    if (isRuangRapat) {
        if (!validasiRuangRapat()) return;
        Swal.fire({
            title:'Ajukan Permintaan Ruang Rapat?', html:buildConfirmRapat(), icon:'question',
            showCancelButton:true, confirmButtonText:'Ya, Ajukan', cancelButtonText:'Periksa Kembali',
            confirmButtonColor:'#4338ca', cancelButtonColor:'#6b7280', reverseButtons:true
        }).then(r => {
            if (r.isConfirmed) {
                Swal.fire({ title:'Mengirim permintaan...', html:'Mohon tunggu sebentar',
                            allowOutsideClick:false, didOpen:() => Swal.showLoading() });
                setTimeout(() => form.submit(), 800);
            }
        });
        return;
    }

    /* ── Kendaraan ── */
    if (isKendaraan) {
        if (!validasiKendaraan()) return;
        Swal.fire({
            title:'Ajukan Peminjaman Kendaraan?', html:buildConfirmKendaraan(), icon:'question',
            showCancelButton:true, confirmButtonText:'Ya, Ajukan', cancelButtonText:'Periksa Kembali',
            confirmButtonColor:'#d97706', cancelButtonColor:'#6b7280', reverseButtons:true
        }).then(r => {
            if (r.isConfirmed) {
                Swal.fire({ title:'Mengirim permintaan...', html:'Mohon tunggu sebentar',
                            allowOutsideClick:false, didOpen:() => Swal.showLoading() });
                setTimeout(() => form.submit(), 800);
            }
        });
        return;
    }

    /* ── BMN ── */
    if (isBmn) {
        const bmn        = document.getElementById('bmnId').value;
        const unit       = document.getElementById('bmnUnitKerja').value.trim();
        const tujuan     = document.getElementById('bmnTujuan').value.trim();
        const tglPinjam  = document.getElementById('bmnTanggalPinjam').value;
        const tglKembali = document.getElementById('bmnTanggalKembali').value;

        if (!tglPinjam)  { Swal.fire({ icon:'warning', title:'Tanggal pinjam wajib diisi.', confirmButtonColor:'#7c3aed' }); return; }
        if (!tglKembali) { Swal.fire({ icon:'warning', title:'Tanggal kembali wajib diisi.', confirmButtonColor:'#7c3aed' }); return; }
        if (!bmn)        { Swal.fire({ icon:'warning', title:'Pilih barang BMN terlebih dahulu.', confirmButtonColor:'#7c3aed' }); return; }

        /* Cek tersedia */
        const bmnSel = document.getElementById('bmnId');
        const bmnOpt = bmnSel.options[bmnSel.selectedIndex];
        if (bmnOpt && bmnOpt.dataset.tersedia === '0') {
            Swal.fire({ icon:'error', title:'Barang sedang dipinjam!',
                        text:'Pilih barang lain atau ubah tanggal.', confirmButtonColor:'#7c3aed' }); return;
        }

        if (!unit)    { Swal.fire({ icon:'warning', title:'Unit kerja wajib diisi.', confirmButtonColor:'#7c3aed' }); return; }
        if (!tujuan)  { Swal.fire({ icon:'warning', title:'Tujuan peminjaman wajib diisi.', confirmButtonColor:'#7c3aed' }); return; }
        if (tglKembali < tglPinjam) {
            Swal.fire({ icon:'warning', title:'Tanggal kembali tidak boleh sebelum tanggal pinjam.', confirmButtonColor:'#7c3aed' }); return;
        }

        const bmnNama = bmnOpt?.text || '-';
        const html = `<div class="text-left space-y-2 mt-4 p-4 bg-gray-50 rounded-lg text-sm">
            <p><strong>Barang:</strong> ${bmnNama}</p>
            <p><strong>Unit Kerja:</strong> ${unit}</p>
            <p><strong>Tujuan:</strong> ${tujuan}</p>
            <p><strong>Tgl Pinjam:</strong> ${formatTanggal(tglPinjam)}</p>
            <p><strong>Rencana Kembali:</strong> ${formatTanggal(tglKembali)}</p>
        </div><p class="text-sm text-gray-600 mt-4">Pastikan semua informasi sudah benar sebelum mengirim.</p>`;

        Swal.fire({
            title:'Ajukan Peminjaman BMN?', html, icon:'question',
            showCancelButton:true, confirmButtonText:'Ya, Ajukan', cancelButtonText:'Periksa Kembali',
            confirmButtonColor:'#7c3aed', cancelButtonColor:'#6b7280', reverseButtons:true
        }).then(r => {
            if (r.isConfirmed) {
                Swal.fire({ title:'Mengirim permintaan...', html:'Mohon tunggu sebentar',
                            allowOutsideClick:false, didOpen:() => Swal.showLoading() });
                setTimeout(() => form.submit(), 800);
            }
        });
        return;
    }

    /* ── Layanan IT biasa ── */
    if (!jenisLayanan.value.trim()) {
        Swal.fire({ icon:'warning', title:'Detail Permintaan Wajib Diisi',
                    text:'Silakan jelaskan detail permintaan Anda.', confirmButtonColor:'#2563eb' });
        return;
    }

    if (perluJadwal) {
        if (!tanggalInput.value) { Swal.fire({ icon:'warning', title:'Tanggal Mulai Wajib Diisi', confirmButtonColor:'#2563eb' }); return; }
        if (tanggalSelesaiInput.value && new Date(tanggalSelesaiInput.value) <= new Date(tanggalInput.value)) {
            Swal.fire({ icon:'warning', title:'Tanggal Selesai Tidak Valid',
                        text:'Tanggal selesai harus lebih dari tanggal mulai.', confirmButtonColor:'#2563eb' }); return;
        }
        if (!jamInput.value)    { Swal.fire({ icon:'warning', title:'Jam Mulai Wajib Diisi', confirmButtonColor:'#2563eb' }); return; }
        if (!durasiInput.value) { Swal.fire({ icon:'warning', title:'Durasi Wajib Diisi', confirmButtonColor:'#2563eb' }); return; }
        if (!jumlahPeserta.value || parseInt(jumlahPeserta.value) < 1) {
            Swal.fire({ icon:'warning', title:'Jumlah Peserta Wajib Diisi', confirmButtonColor:'#2563eb' }); return;
        }
    }

    let html = `<div class="text-left space-y-2 mt-4 p-4 bg-gray-50 rounded-lg text-sm">
        <p><strong>Jenis Layanan:</strong><br>${subKategori.options[subKategori.selectedIndex].text}</p>
        <p><strong>Detail:</strong><br>${jenisLayanan.value}</p>`;
    if (perluJadwal && tanggalInput.value) {
        let jTeks = formatTanggal(tanggalInput.value);
        if (tanggalSelesaiInput.value) {
            const h = Math.round((new Date(tanggalSelesaiInput.value) - new Date(tanggalInput.value)) / 86400000) + 1;
            jTeks  += ' s.d. ' + formatTanggal(tanggalSelesaiInput.value) + ' <strong>(' + h + ' hari)</strong>';
        }
        html += `<p><strong>Tanggal:</strong><br>${jTeks}</p>
                 <p><strong>Jam Mulai/Hari:</strong> ${jamInput.value} WIB</p>
                 <p><strong>Durasi/Hari:</strong> ${durasiInput.value} Jam</p>
                 <p><strong>Jumlah Peserta:</strong> ${jumlahPeserta.value} orang</p>`;
    }
    html += `</div><p class="text-sm text-gray-600 mt-4">Pastikan semua informasi sudah benar.</p>`;

    Swal.fire({
        title:'Kirim Permintaan Layanan IT?', html, icon:'question', showCancelButton:true,
        confirmButtonText:'Ya, Kirim', cancelButtonText:'Periksa Kembali',
        confirmButtonColor:'#2563eb', cancelButtonColor:'#6b7280', reverseButtons:true
    }).then(r => {
        if (r.isConfirmed) {
            Swal.fire({ title:'Mengirim permintaan...', html:'Mohon tunggu sebentar',
                        allowOutsideClick:false, didOpen:() => Swal.showLoading() });
            setTimeout(() => form.submit(), 800);
        }
    });
});

/* ================================================================
   CANCEL
================================================================ */
document.getElementById('btnCancel').addEventListener('click', () => {
    Swal.fire({
        title:'Batalkan Permintaan?', text:'Semua data yang sudah diisi akan hilang',
        icon:'warning', showCancelButton:true,
        confirmButtonText:'Ya, Batalkan', cancelButtonText:'Lanjutkan Mengisi',
        confirmButtonColor:'#2563eb', cancelButtonColor:'#6b7280', reverseButtons:true
    }).then(r => {
        if (r.isConfirmed) window.location.href = "<?= base_url('user/dashboard') ?>";
    });
});
</script>