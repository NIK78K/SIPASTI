<?php
$kategori_slug = $ticket['kategori_slug'] ?? '';
$is_gangguan   = in_array($kategori_slug, ['gangguan', 'kerusakan']);
$is_permintaan = $kategori_slug === 'permintaan';

// ── Parse data tersimpan untuk prefill ──
$jenis_kerusakan_raw = $ticket['jenis_kerusakan'] ?? '';

// Untuk permintaan: ekstrak field-field yang diformat waktu create
$saved_detail       = '';
$saved_lokasi_unit  = $ticket['lokasi'] ?? '';
$saved_catatan      = '';
$saved_jumlah_peserta  = '';
$saved_jumlah_breakout = '';
$saved_penanggung_jawab = '';
$saved_host_cohost  = '';
$saved_permintaan_lain = '';

if ($is_permintaan) {
    $lines = explode("\n", $jenis_kerusakan_raw);
    $section = '';
    $desc_lines = [];

    foreach ($lines as $line) {
        $line = trim($line);
        if (str_starts_with($line, 'Detail Permintaan:')) {
            $saved_detail = trim(substr($line, strlen('Detail Permintaan:')));
        } elseif (str_starts_with($line, 'Lokasi/Unit:')) {
            $saved_lokasi_unit = trim(substr($line, strlen('Lokasi/Unit:')));
        } elseif (str_starts_with($line, 'Jumlah Peserta:')) {
            $saved_jumlah_peserta = trim(str_replace(' orang', '', substr($line, strlen('Jumlah Peserta:'))));
        } elseif (str_starts_with($line, 'Jumlah Breakout Room:')) {
            $saved_jumlah_breakout = trim(substr($line, strlen('Jumlah Breakout Room:')));
        } elseif (str_starts_with($line, 'Penanggung Jawab:')) {
            $saved_penanggung_jawab = trim(substr($line, strlen('Penanggung Jawab:')));
        } elseif (str_starts_with($line, 'Host/Co-Host:')) {
            $saved_host_cohost = trim(substr($line, strlen('Host/Co-Host:')));
        } elseif (str_starts_with($line, 'Catatan Tambahan:')) {
            $section = 'catatan';
        } elseif (str_starts_with($line, 'Permintaan Lainnya:')) {
            $section = 'permintaan_lain';
        } elseif ($section === 'catatan') {
            $saved_catatan .= $line . "\n";
        } elseif ($section === 'permintaan_lain') {
            $saved_permintaan_lain .= $line . "\n";
        }
    }
    $saved_catatan         = trim($saved_catatan);
    $saved_permintaan_lain = trim($saved_permintaan_lain);
}

// Tentukan apakah sub kategori yang dipilih perlu jadwal
$perlu_jadwal = false;
foreach ($sub_kategori as $sk) {
    if ($sk->id == $ticket['sub_kategori_id'] && $sk->perlu_jadwal == 1) {
        $perlu_jadwal = true;
        break;
    }
}

// Prefill jadwal
$saved_tanggal        = $ticket['tanggal']         ?? '';
$saved_tanggal_selesai= $ticket['tanggal_selesai'] ?? '';
$saved_jam            = $ticket['jam']             ?? '';
$saved_durasi         = $ticket['durasi']          ?? '';
?>

<div class="max-w-5xl mx-auto mt-12 mb-24 space-y-8 px-4">

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
    <div class="text-center space-y-3">
        <?php if ($is_gangguan): ?>
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-red-100 text-red-700 text-sm font-semibold">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            Edit Laporan Gangguan IT
        </div>
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-[#0B2E6B]">Edit Laporan Gangguan</h1>
        <?php else: ?>
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-100 text-blue-700 text-sm font-semibold">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Edit Permintaan Layanan IT
        </div>
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-[#0B2E6B]">Edit Permintaan Layanan IT</h1>
        <?php endif ?>

        <!-- Info kode tiket -->
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-100 text-slate-600 text-xs font-mono">
            <?= $ticket['kode_tiket'] ?>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold
                         <?= $ticket['status'] === 'Menunggu' ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700' ?>">
                <?= $ticket['status'] === 'On Progress' ? 'Diproses' : $ticket['status'] ?>
            </span>
        </div>
    </div>

    <!-- FORM -->
    <div class="bg-white rounded-3xl p-8 md:p-10 shadow-2xl border border-gray-100">
        <form id="editTicketForm" method="post" enctype="multipart/form-data"
              action="<?= base_url('user_ticket/update_ticket/'.$ticket['id']) ?>"
              class="space-y-6">

            <?php if ($is_gangguan): ?>
            <!-- ================================================================
                 FORM GANGGUAN — identik dengan create_gangguan.php
            ================================================================ -->
            <input type="hidden" name="perangkat_ids" id="perangkatIdsFinal"
                   value="<?= $ticket['perangkat_id'] ? json_encode([(int)$ticket['perangkat_id']]) : '[]' ?>">

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
                    <?php foreach ($sub_kategori_gangguan as $sk): ?>
                        <option value="<?= $sk->id ?>"
                            <?= ($ticket['sub_kategori_id'] == $sk->id) ? 'selected' : '' ?>>
                            <?= $sk->nama_sub_kategori ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>

            <!-- FORM DETAILS -->
            <div id="formDetails" class="<?= $ticket['sub_kategori_id'] ? '' : 'hidden' ?> space-y-6 pt-4 border-t-2 border-gray-100">

                <!-- STEP 2: LOKASI -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-bold text-gray-800">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-red-600 text-white text-xs">2</span>
                        Lokasi Gangguan
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="lokasi" required
                           value="<?= htmlspecialchars($ticket['lokasi'] ?? '') ?>"
                           placeholder="Contoh: Lantai 3 - Ruang Statistik Produksi"
                           class="w-full h-14 rounded-xl px-5 border-2 border-gray-200
                                  focus:border-red-500 focus:ring-4 focus:ring-red-100
                                  text-gray-800 transition-all placeholder:text-gray-400 hover:border-gray-300">
                </div>

                <!-- STEP 3: PERANGKAT (MULTIPLE) -->
                <div class="space-y-3">
                    <label class="flex items-center gap-2 text-sm font-bold text-gray-800">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-gray-400 text-white text-xs">3</span>
                        Perangkat Terkait
                        <span class="text-xs font-normal text-gray-500">(Opsional - Bisa pilih lebih dari 1)</span>
                    </label>

                    <div class="relative">
                        <input type="text" id="perangkatSearch" placeholder="🔍 Ketik kode barang atau NUP untuk mencari..."
                               autocomplete="off"
                               class="w-full h-14 rounded-xl px-5 pr-12 border-2 border-gray-200
                                      focus:border-blue-500 focus:ring-4 focus:ring-blue-100
                                      text-gray-800 transition-all placeholder:text-gray-400 hover:border-gray-300">
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

                    <div id="perangkatResult" class="hidden w-full bg-white border-2 border-blue-300 rounded-xl shadow-xl mt-2 max-h-64 overflow-y-auto"></div>

                    <div id="selectedPerangkat" class="min-h-[3rem] flex flex-wrap gap-2 p-4 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                        <!-- chips muncul di sini -->
                    </div>
                </div>

                <!-- STEP 4: DESKRIPSI -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-bold text-gray-800">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-red-600 text-white text-xs">4</span>
                        Deskripsi Gangguan
                        <span class="text-red-500">*</span>
                    </label>
                    <textarea name="jenis_kerusakan" rows="7" required
                              placeholder="Jelaskan gangguan secara detail:&#10;&#10;• Apa yang terjadi?&#10;• Sejak kapan masalah muncul?&#10;• Sudah dicoba apa saja?&#10;• Bagaimana kondisi perangkat saat ini?"
                              class="w-full rounded-xl px-5 py-4 border-2 border-gray-200
                                     focus:border-red-500 focus:ring-4 focus:ring-red-100
                                     text-gray-800 transition-all resize-none
                                     placeholder:text-gray-400 hover:border-gray-300"><?= htmlspecialchars($jenis_kerusakan_raw) ?></textarea>
                </div>

                <!-- STEP 5: FOTO -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-bold text-gray-800">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-gray-400 text-white text-xs">5</span>
                        Foto Pendukung
                        <span class="text-xs font-normal text-gray-500">(Opsional)</span>
                    </label>

                    <?php if (!empty($ticket['foto'])): ?>
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-200 mb-2">
                        <img src="<?= base_url('uploads/tiket/'.$ticket['foto']) ?>"
                             class="h-20 w-20 object-cover rounded-lg border border-gray-200 flex-shrink-0">
                        <div>
                            <p class="text-xs font-semibold text-gray-600 mb-1">Foto saat ini</p>
                            <a href="<?= base_url('uploads/tiket/'.$ticket['foto']) ?>" target="_blank"
                               class="text-xs text-blue-600 hover:underline">Lihat ukuran penuh</a>
                            <p class="text-xs text-gray-400 mt-1">Upload foto baru untuk mengganti</p>
                        </div>
                    </div>
                    <?php endif ?>

                    <input type="file" name="foto" accept="image/*"
                           class="w-full h-14 px-5 border-2 border-gray-200 rounded-xl
                                  file:mr-4 file:py-2.5 file:px-5 file:rounded-lg file:border-0
                                  file:bg-orange-500 file:text-white file:font-semibold
                                  file:cursor-pointer hover:file:bg-orange-600
                                  focus:border-orange-500 focus:ring-4 focus:ring-orange-100
                                  transition-all cursor-pointer hover:border-gray-300">
                    <p class="text-xs text-gray-500 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Upload foto gangguan untuk mempercepat proses analisis
                    </p>
                </div>

                <!-- TOMBOL -->
                <div class="flex gap-4 pt-6 border-t-2 border-gray-100">
                    <button type="button" onclick="confirmSimpan()"
                            class="flex-1 h-14 rounded-xl bg-gradient-to-r from-red-600 to-red-700
                                   hover:from-red-700 hover:to-red-800 text-white font-bold text-lg
                                   shadow-lg hover:shadow-xl transition-all duration-300
                                   flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                    <button type="button" onclick="confirmBatal()"
                            class="h-14 px-8 rounded-xl border-2 border-gray-300
                                   hover:bg-gray-100 hover:border-gray-400
                                   text-gray-700 font-semibold transition-all duration-300">
                        Batal
                    </button>
                </div>

            </div><!-- end formDetails gangguan -->


            <?php else: ?>
            <!-- ================================================================
                 FORM PERMINTAAN — identik dengan create_permintaan.php
            ================================================================ -->

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
                    <?php foreach ($sub_kategori_permintaan as $sk): ?>
                        <option value="<?= $sk->id ?>"
                                data-perlu-jadwal="<?= $sk->perlu_jadwal ?>"
                            <?= ($ticket['sub_kategori_id'] == $sk->id) ? 'selected' : '' ?>>
                            <?= $sk->nama_sub_kategori ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>

            <!-- FORM DETAILS PERMINTAAN -->
            <div id="formDetails" class="<?= $ticket['sub_kategori_id'] ? '' : 'hidden' ?> space-y-6 pt-4 border-t-2 border-gray-100">

                <!-- STEP 2: DETAIL PERMINTAAN -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-bold text-gray-800">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-600 text-white text-xs">2</span>
                        Detail Permintaan
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="jenis_layanan" id="jenisLayanan" required
                           value="<?= htmlspecialchars($saved_detail) ?>"
                           placeholder="Contoh: Permintaan Zoom Meeting untuk Rapat Bulanan"
                           class="w-full h-14 rounded-xl px-5 border-2 border-gray-200
                                  focus:border-blue-500 focus:ring-4 focus:ring-blue-100
                                  text-gray-800 transition-all placeholder:text-gray-400 hover:border-gray-300">
                    <div id="noteZoom" class="<?= (stripos($ticket['nama_sub_kategori'] ?? '', 'zoom') !== false || stripos($ticket['nama_sub_kategori'] ?? '', 'meet') !== false) ? '' : 'hidden' ?> mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-xs text-blue-700 leading-relaxed">
                            <strong>Batas maksimal peserta adalah 300 orang.</strong>
                            Jika jumlah peserta melebihi batas tersebut, silakan berkoordinasi dengan tim IPDS untuk penanganan lebih lanjut.
                        </p>
                    </div>
                </div>

                <!-- STEP 3: JADWAL (conditional) -->
                <div id="jadwalSection" class="<?= $perlu_jadwal ? '' : 'hidden' ?> space-y-4">
                    <label class="flex items-center gap-2 text-sm font-bold text-gray-800">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-600 text-white text-xs">3</span>
                        Jadwal Layanan
                        <span class="text-red-500">*</span>
                    </label>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Tanggal Mulai <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal" id="tanggal"
                                   value="<?= htmlspecialchars($saved_tanggal) ?>"
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
                                   value="<?= htmlspecialchars($saved_tanggal_selesai) ?>"
                                   class="w-full h-14 rounded-xl px-5 border-2 border-gray-200
                                          focus:border-blue-500 focus:ring-4 focus:ring-blue-100
                                          text-gray-800 transition-all hover:border-gray-300">
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Jam Mulai (per hari) <span class="text-red-500">*</span></label>
                            <input type="time" name="jam" id="jam"
                                   value="<?= htmlspecialchars($saved_jam) ?>"
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
                                <?php for($i = 1; $i <= 12; $i++): ?>
                                    <option value="<?= $i ?>" <?= ($saved_durasi == $i) ? 'selected' : '' ?>><?= $i ?> Jam</option>
                                <?php endfor ?>
                            </select>
                        </div>
                    </div>

                    <!-- Ringkasan jadwal -->
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

                <!-- STEP 4: INFORMASI DETAIL MEETING (dengan jadwal) -->
                <div id="catatanDenganJadwal" class="<?= $perlu_jadwal ? '' : 'hidden' ?> space-y-3">
                    <label class="flex items-center gap-2 text-sm font-bold text-gray-800">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-600 text-white text-xs">4</span>
                        Informasi Detail Meeting
                        <span class="text-red-500">*</span>
                    </label>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Jumlah Peserta <span class="text-red-500">*</span></label>
                            <input type="number" name="jumlah_peserta" id="jumlahPeserta" min="1"
                                   value="<?= htmlspecialchars($saved_jumlah_peserta) ?>"
                                   placeholder="Contoh: 50"
                                   class="w-full h-12 rounded-xl px-4 border-2 border-gray-200
                                          focus:border-blue-500 focus:ring-4 focus:ring-blue-100
                                          text-gray-800 transition-all hover:border-gray-300">
                        </div>
                        <div>
                            <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Jumlah Breakout Room</label>
                            <input type="number" name="jumlah_breakout" id="jumlahBreakout" min="0"
                                   value="<?= htmlspecialchars($saved_jumlah_breakout) ?>"
                                   placeholder="Contoh: 5 (isi 0 jika tidak perlu)"
                                   class="w-full h-12 rounded-xl px-4 border-2 border-gray-200
                                          focus:border-blue-500 focus:ring-4 focus:ring-blue-100
                                          text-gray-800 transition-all hover:border-gray-300">
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Penanggung Jawab</label>
                            <input type="text" name="penanggung_jawab"
                                   value="<?= htmlspecialchars($saved_penanggung_jawab) ?>"
                                   placeholder="Nama penanggung jawab acara"
                                   class="w-full h-12 rounded-xl px-4 border-2 border-gray-200
                                          focus:border-blue-500 focus:ring-4 focus:ring-blue-100
                                          text-gray-800 transition-all hover:border-gray-300">
                        </div>
                        <div>
                            <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Host / Co-Host</label>
                            <input type="text" name="host_cohost"
                                   value="<?= htmlspecialchars($saved_host_cohost) ?>"
                                   placeholder="Nama host atau co-host"
                                   class="w-full h-12 rounded-xl px-4 border-2 border-gray-200
                                          focus:border-blue-500 focus:ring-4 focus:ring-blue-100
                                          text-gray-800 transition-all hover:border-gray-300">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Permintaan Lainnya</label>
                        <textarea name="permintaan_lain_jadwal" rows="4"
                                  placeholder="Contoh:&#10;• Perlu rekaman meeting&#10;• Perlu fitur polling&#10;• Perlu waiting room"
                                  class="w-full rounded-xl px-4 py-3 border-2 border-gray-200
                                         focus:border-blue-500 focus:ring-4 focus:ring-blue-100
                                         text-gray-800 transition-all resize-none
                                         placeholder:text-gray-400 hover:border-gray-300"><?= htmlspecialchars($saved_permintaan_lain) ?></textarea>
                    </div>
                </div>

                <!-- STEP 3: INFORMASI TAMBAHAN (tanpa jadwal) -->
                <div id="catatanTanpaJadwal" class="<?= !$perlu_jadwal && $ticket['sub_kategori_id'] ? '' : 'hidden' ?> space-y-3">
                    <label class="flex items-center gap-2 text-sm font-bold text-gray-800">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-gray-400 text-white text-xs">3</span>
                        Informasi Tambahan
                        <span class="text-xs font-normal text-gray-500">(Opsional)</span>
                    </label>
                    <div>
                        <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Lokasi / Unit Kerja</label>
                        <input type="text" name="lokasi_tanpa_jadwal"
                               value="<?= htmlspecialchars($saved_lokasi_unit) ?>"
                               placeholder="Contoh: Ruang IT Lantai 2 / Bagian Keuangan"
                               class="w-full h-12 rounded-xl px-4 border-2 border-gray-200
                                      focus:border-blue-500 focus:ring-4 focus:ring-blue-100
                                      text-gray-800 transition-all hover:border-gray-300">
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 font-semibold mb-1.5 block">Catatan Tambahan</label>
                        <textarea name="permintaan_lain_tanpa_jadwal" rows="5"
                                  placeholder="Informasi tambahan yang perlu diketahui teknisi..."
                                  class="w-full rounded-xl px-4 py-3 border-2 border-gray-200
                                         focus:border-blue-500 focus:ring-4 focus:ring-blue-100
                                         text-gray-800 transition-all resize-none
                                         placeholder:text-gray-400 hover:border-gray-300"><?= htmlspecialchars($saved_catatan) ?></textarea>
                    </div>
                </div>

                <!-- TOMBOL -->
                <div class="flex gap-4 pt-6 border-t-2 border-gray-100">
                    <button type="button" onclick="confirmSimpan()"
                            class="flex-1 h-14 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700
                                   hover:from-blue-700 hover:to-blue-800 text-white font-bold text-lg
                                   shadow-lg hover:shadow-xl transition-all duration-300
                                   flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                    <button type="button" onclick="confirmBatal()"
                            class="h-14 px-8 rounded-xl border-2 border-gray-300
                                   hover:bg-gray-100 hover:border-gray-400
                                   text-gray-700 font-semibold transition-all duration-300">
                        Batal
                    </button>
                </div>

            </div><!-- end formDetails permintaan -->
            <?php endif ?>

        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function(){
    'use strict';

    const isGangguan  = <?= $is_gangguan ? 'true' : 'false' ?>;
    const perluJadwal = <?= $perlu_jadwal ? 'true' : 'false' ?>;

    /* ================================================================
       GANGGUAN: Search perangkat (identik dengan create_gangguan.php)
    ================================================================ */
    <?php if ($is_gangguan): ?>

    // Prefill perangkat chip jika ada
    window.editSelectedItems = [];
    <?php if (!empty($ticket['perangkat_id'])): ?>
    <?php
        $prefill = null;
        foreach($perangkat as $p) {
            if ($p['id'] == $ticket['perangkat_id']) { $prefill = $p; break; }
        }
    ?>
    <?php if ($prefill): ?>
    window.editSelectedItems = [{
        id: <?= (int)$prefill['id'] ?>,
        kode_barang: <?= json_encode($prefill['kode_barang']) ?>,
        nup: <?= json_encode($prefill['nup']) ?>,
        nama_barang: <?= json_encode($prefill['nama_barang']) ?>
    }];
    <?php endif ?>
    <?php endif ?>

    const perangkatSearch  = document.getElementById('perangkatSearch');
    const perangkatResult  = document.getElementById('perangkatResult');
    const perangkatIdsFinal = document.getElementById('perangkatIdsFinal');
    let timer = null;

    perangkatSearch.addEventListener('input', function() {
        clearTimeout(timer);
        const q = this.value.trim();
        if (q.length < 3) { perangkatResult.classList.add('hidden'); return; }
        timer = setTimeout(() => {
            fetch(`<?= base_url('user_ticket/search_perangkat') ?>?q=${encodeURIComponent(q)}`)
                .then(r => r.json())
                .then(data => {
                    perangkatResult.innerHTML = '';
                    if (!data.length) {
                        perangkatResult.innerHTML = `<div class="px-5 py-4 text-center text-gray-500 text-sm">Perangkat tidak ditemukan</div>`;
                        perangkatResult.classList.remove('hidden');
                        return;
                    }
                    data.forEach(item => {
                        if (window.editSelectedItems.find(s => s.id === item.id)) return;
                        const div = document.createElement('div');
                        div.className = 'px-5 py-3 hover:bg-blue-50 cursor-pointer border-b last:border-b-0 transition-colors';
                        div.innerHTML = `<div class="font-semibold text-gray-900">${item.kode_barang} | ${item.nup}</div><div class="text-sm text-gray-600 mt-0.5">${item.nama_barang}</div>`;
                        div.onclick = () => addPerangkat(item);
                        perangkatResult.appendChild(div);
                    });
                    perangkatResult.classList.remove('hidden');
                });
        }, 300);
    });

    function addPerangkat(item) {
        if (window.editSelectedItems.find(s => s.id === item.id)) return;
        window.editSelectedItems.push(item);
        renderChips();
        perangkatSearch.value = '';
        perangkatResult.classList.add('hidden');
        updateHidden();
    }

    window.removeEditPerangkat = function(id) {
        window.editSelectedItems = window.editSelectedItems.filter(i => parseInt(i.id) !== parseInt(id));
        renderChips();
        updateHidden();
    };

    function renderChips() {
        const container = document.getElementById('selectedPerangkat');
        if (!window.editSelectedItems.length) {
            container.innerHTML = `<p class="text-sm text-gray-400 w-full text-center py-2">Belum ada perangkat dipilih</p>`;
            return;
        }
        container.innerHTML = window.editSelectedItems.map(item => `
            <div class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg text-sm font-medium shadow-md">
                <span>${item.kode_barang} | ${item.nup}</span>
                <button type="button" onclick="window.removeEditPerangkat(${item.id})"
                        class="ml-1 text-white hover:text-red-200 font-bold text-xl leading-none">×</button>
            </div>
        `).join('');
    }

    function updateHidden() {
        perangkatIdsFinal.value = JSON.stringify(window.editSelectedItems.map(i => i.id));
    }

    document.addEventListener('click', function(e) {
        if (!perangkatSearch.contains(e.target) && !perangkatResult.contains(e.target)) {
            perangkatResult.classList.add('hidden');
        }
    });

    // Show form details jika sub kategori sudah terpilih
    const subKategoriGangguan = document.getElementById('subKategori');
    const formDetails = document.getElementById('formDetails');
    subKategoriGangguan.addEventListener('change', function() {
        formDetails.classList.toggle('hidden', !this.value);
        if (this.value) formDetails.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    });

    // Init chips dari prefill
    renderChips();
    updateHidden();

    <?php else: ?>
    /* ================================================================
       PERMINTAAN: Logic jadwal (identik dengan create_permintaan.php)
    ================================================================ */

    const subKategori         = document.getElementById('subKategori');
    const formDetails         = document.getElementById('formDetails');
    const jadwalSection       = document.getElementById('jadwalSection');
    const catatanDenganJadwal = document.getElementById('catatanDenganJadwal');
    const catatanTanpaJadwal  = document.getElementById('catatanTanpaJadwal');
    const tanggalInput        = document.getElementById('tanggal');
    const tanggalSelesaiInput = document.getElementById('tanggalSelesai');
    const jamInput            = document.getElementById('jam');
    const durasiInput         = document.getElementById('durasi');
    const jumlahPeserta       = document.getElementById('jumlahPeserta');
    const ringkasanJadwal     = document.getElementById('ringkasanJadwal');
    const ringkasanTeks       = document.getElementById('ringkasanTeks');

    let perluJadwalState = perluJadwal;

    function formatTanggal(dateStr) {
        return new Date(dateStr).toLocaleDateString('id-ID', { weekday:'long', day:'numeric', month:'long', year:'numeric' });
    }

    function hitungRingkasan() {
        const mulai   = tanggalInput.value;
        const selesai = tanggalSelesaiInput.value;
        if (!mulai) { ringkasanJadwal.classList.add('hidden'); return; }

        if (!selesai) {
            const jam = jamInput.value, durasi = durasiInput.value;
            let teks = '📅 ' + formatTanggal(mulai) + ' (1 hari)';
            if (jam)    teks += ' · Mulai pukul ' + jam + ' WIB';
            if (durasi) teks += ' · ' + durasi + ' jam';
            ringkasanTeks.textContent = teks;
            ringkasanTeks.className   = 'text-sm text-indigo-700 font-semibold';
            ringkasanJadwal.className  = 'p-3 rounded-lg border border-indigo-200 bg-indigo-50';
            ringkasanJadwal.classList.remove('hidden');
            return;
        }

        const tM = new Date(mulai), tS = new Date(selesai);
        if (tS <= tM) {
            ringkasanTeks.textContent = '⚠️ Tanggal selesai harus setelah tanggal mulai.';
            ringkasanTeks.className   = 'text-sm text-red-600 font-semibold';
            ringkasanJadwal.className  = 'p-3 rounded-lg border border-red-200 bg-red-50';
            ringkasanJadwal.classList.remove('hidden');
            return;
        }

        const jumlahHari = Math.round((tS - tM) / 86400000) + 1;
        const jam = jamInput.value, durasi = durasiInput.value;
        let teks = '📅 ' + jumlahHari + ' hari (' + formatTanggal(mulai) + ' s.d. ' + formatTanggal(selesai) + ')';
        if (jam)    teks += ' · Mulai pukul ' + jam + ' WIB/hari';
        if (durasi) teks += ' · ' + durasi + ' jam/hari';
        ringkasanTeks.textContent = teks;
        ringkasanTeks.className   = 'text-sm text-indigo-700 font-semibold';
        ringkasanJadwal.className  = 'p-3 rounded-lg border border-indigo-200 bg-indigo-50';
        ringkasanJadwal.classList.remove('hidden');
    }

    tanggalInput.addEventListener('change', function() {
        if (this.value) {
            const next = new Date(this.value);
            next.setDate(next.getDate() + 1);
            tanggalSelesaiInput.setAttribute('min', next.toISOString().split('T')[0]);
        }
        hitungRingkasan();
    });
    tanggalSelesaiInput.addEventListener('change', hitungRingkasan);
    jamInput.addEventListener('change', hitungRingkasan);
    durasiInput.addEventListener('change', hitungRingkasan);

    // Init ringkasan jika ada data prefill
    if (tanggalInput.value) hitungRingkasan();

    subKategori.addEventListener('change', function() {
        if (!this.value) { formDetails.classList.add('hidden'); return; }

        const opt = this.options[this.selectedIndex];
        perluJadwalState = opt.getAttribute('data-perlu-jadwal') == '1';

        // Note zoom
        const namaLayanan = opt.text.toLowerCase();
        const noteZoom = document.getElementById('noteZoom');
        noteZoom.classList.toggle('hidden', !(namaLayanan.includes('zoom') || namaLayanan.includes('meet')));

        formDetails.classList.remove('hidden');

        if (perluJadwalState) {
            jadwalSection.classList.remove('hidden');
            catatanDenganJadwal.classList.remove('hidden');
            catatanTanpaJadwal.classList.add('hidden');
            tanggalInput.setAttribute('required', 'required');
            jamInput.setAttribute('required', 'required');
            durasiInput.setAttribute('required', 'required');
            jumlahPeserta.setAttribute('required', 'required');
        } else {
            jadwalSection.classList.add('hidden');
            catatanDenganJadwal.classList.add('hidden');
            catatanTanpaJadwal.classList.remove('hidden');
            tanggalInput.removeAttribute('required');
            jamInput.removeAttribute('required');
            durasiInput.removeAttribute('required');
            jumlahPeserta.removeAttribute('required');
            ringkasanJadwal.classList.add('hidden');
        }

        formDetails.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    });
    <?php endif ?>

    /* ================================================================
       KONFIRMASI SIMPAN & BATAL (shared)
    ================================================================ */
    window.confirmSimpan = function() {
        Swal.fire({
            title: 'Simpan Perubahan?',
            text: 'Pastikan semua data sudah benar sebelum menyimpan.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Periksa Lagi',
            confirmButtonColor: isGangguan ? '#dc2626' : '#2563eb',
            cancelButtonColor: '#6b7280',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Menyimpan...',
                    html: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                setTimeout(() => { document.getElementById('editTicketForm').submit(); }, 600);
            }
        });
    };

    window.confirmBatal = function() {
        Swal.fire({
            title: 'Batalkan Perubahan?',
            text: 'Semua perubahan yang belum disimpan akan hilang.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Lanjutkan Edit',
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= base_url('user_ticket/history') ?>";
            }
        });
    };

})();
</script>