<!-- HEADER MODAL -->
<div class="bg-gradient-to-r from-blue-600 to-blue-700 p-5 -m-4 mb-5 rounded-t-lg">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
        </div>
        <div>
            <h2 class="text-lg font-bold text-white">Tambah Perangkat Baru</h2>
            <p class="text-blue-100 text-xs">Lengkapi form di bawah untuk menambah perangkat</p>
        </div>
    </div>
</div>

<!-- FORM CONTENT -->
<form id="formTambah" enctype="multipart/form-data" class="space-y-5">

    <!-- ================= SECTION 1: DATA WAJIB ================= -->
    <div>
        <div class="flex items-center gap-2 mb-3">
            <div class="w-7 h-7 bg-red-600 rounded-lg flex items-center justify-center flex-shrink-0">
                <span class="text-white text-xs font-bold">*</span>
            </div>
            <div>
                <h3 class="text-sm font-semibold fm-title">Data Wajib</h3>
                <p class="text-xs fm-hint">Field ini harus diisi</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 fm-section-bg border fm-section-border rounded-lg p-3">
            <?php
            $master = [
                'jenis_bmn'   => 'Jenis BMN',
                'kode_satker' => 'Kode Satker',
                'nama_satker' => 'Nama Satker',
                'kode_barang' => 'Kode Barang',
                'nup'         => 'NUP',
            ];
            ?>
            <?php foreach ($master as $name => $label): ?>
            <div>
                <label class="text-xs fm-label mb-1 block font-medium">
                    <span class="text-red-400">*</span> <?= $label ?>
                </label>
                <?php if ($name === 'kode_satker'): ?>
                    <input type="text" name="<?= $name ?>" required value="054010900428072000KD" readonly
                           class="fm-input-readonly w-full px-3 py-2 rounded-lg text-xs border cursor-not-allowed">
                <?php elseif ($name === 'nama_satker'): ?>
                    <input type="text" name="<?= $name ?>" required value="BPS PROV RIAU" readonly
                           class="fm-input-readonly w-full px-3 py-2 rounded-lg text-xs border cursor-not-allowed">
                <?php else: ?>
                    <input type="text" name="<?= $name ?>" required
                           placeholder="Masukkan <?= strtolower($label) ?>"
                           class="fm-input w-full px-3 py-2 rounded-lg text-xs border
                                  focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition">
                <?php endif; ?>
            </div>
            <?php endforeach ?>
        </div>
    </div>

    <!-- ================= SECTION 2: INFORMASI PERANGKAT ================= -->
    <div>
        <div class="flex items-center gap-2 mb-3">
            <div class="w-7 h-7 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold fm-title">Informasi Perangkat</h3>
                <p class="text-xs fm-hint">Detail dan kategori perangkat</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

            <!-- NAMA BARANG -->
            <div>
                <label class="text-xs fm-label mb-1 block font-medium">Nama Barang</label>
                <select name="nama_barang_select" id="nama_barang"
                        class="fm-input w-full px-3 py-2 rounded-lg text-xs border
                               focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                    <option value="">Pilih Nama Barang</option>
                </select>
                <input type="hidden" name="nama_barang" id="hidden_nama_barang">
            </div>

            <!-- KATEGORI ASET -->
            <div>
                <label class="text-xs fm-label mb-1 block font-medium flex items-center gap-1">
                    <svg class="w-3 h-3 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Kategori Aset
                </label>
                <select name="kategori_aset" id="kategori_aset"
                        class="fm-input w-full px-3 py-2 rounded-lg text-xs border
                               focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Notebook / Laptop">💻 Notebook / Laptop</option>
                    <option value="Komputer Desktop / PC">🖥️ Komputer Desktop / PC</option>
                    <option value="Komputer Server">🗄️ Komputer Server</option>
                    <option value="Perangkat Jaringan">🌐 Perangkat Jaringan</option>
                    <option value="Printer / Scanner">🖨️ Printer / Scanner</option>
                    <option value="Perangkat Lain">📦 Perangkat Lain</option>
                </select>
            </div>

            <!-- ✅ STATUS ASET -->
            <div>
                <label class="text-xs fm-label mb-1 block font-medium flex items-center gap-1">
                    <svg class="w-3 h-3 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                    Status Aset
                </label>
                <select name="status_aset"
                        class="fm-input w-full px-3 py-2 rounded-lg text-xs border
                               focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition">
                    <option value="BMN" selected>🏛️ BMN</option>
                    <option value="Consumable">📦 Consumable</option>
                </select>
            </div>

            <!-- MERK -->
            <div>
                <label class="text-xs fm-label mb-1 block font-medium">Merk</label>
                <select name="merk" id="merk"
                        class="fm-input w-full px-3 py-2 rounded-lg text-xs border
                               focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                    <option value="">Pilih Merk</option>
                </select>
            </div>

            <!-- SERIAL NUMBER -->
            <div>
                <label class="text-xs fm-label mb-1 block font-medium">Serial Number</label>
                <input type="text" name="serial_number" placeholder="Nomor seri perangkat"
                       class="fm-input w-full px-3 py-2 rounded-lg text-xs border
                              focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
            </div>

            <!-- MAC ADDRESS -->
            <div>
                <label class="text-xs fm-label mb-1 block font-medium">MAC Address</label>
                <input type="text" name="mac_address" placeholder="00-00-00-00-00-00"
                       class="fm-input w-full px-3 py-2 rounded-lg text-xs border
                              focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
            </div>

            <!-- KONDISI -->
            <div>
                <label class="text-xs fm-label mb-1 block font-medium">Kondisi</label>
                <select name="kondisi"
                        class="fm-input w-full px-3 py-2 rounded-lg text-xs border
                               focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                    <option value="Baik">✓ Baik</option>
                    <option value="Rusak Ringan">⚠ Rusak Ringan</option>
                    <option value="Rusak Berat">✗ Rusak Berat</option>
                </select>
            </div>

        </div>
    </div>

    <!-- ================= SECTION 2.5: FOTO PERANGKAT ================= -->
    <div>
        <div class="flex items-center gap-2 mb-3">
            <div class="w-7 h-7 bg-cyan-600 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold fm-title">Foto Perangkat</h3>
                <p class="text-xs fm-hint">Upload foto (opsional, maks 2MB)</p>
            </div>
        </div>

        <div class="border-2 border-dashed fm-upload-border rounded-lg p-5 text-center">
            <input type="file" name="foto" id="foto"
                   accept="image/jpeg,image/jpg,image/png,image/gif" class="hidden">

            <div id="preview-container-create" class="hidden mb-3">
                <img id="foto-preview-create"
                     class="max-h-48 mx-auto rounded-lg shadow-lg border-2 fm-preview-border">
                <button type="button" id="btnRemoveFoto"
                        class="mt-2 text-red-400 hover:text-red-300 text-xs flex items-center gap-1 mx-auto transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Hapus Foto
                </button>
            </div>

            <label for="foto" id="upload-label-create" class="cursor-pointer">
                <div class="flex flex-col items-center gap-2">
                    <div class="w-12 h-12 fm-upload-icon-bg rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 fm-upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-cyan-500 hover:text-cyan-400 font-medium text-sm">Klik untuk upload</span>
                        <p class="fm-hint text-xs mt-0.5">JPG, PNG, GIF (maks 2MB)</p>
                    </div>
                </div>
            </label>
        </div>
    </div>

    <!-- ================= SECTION 3: LOKASI & PENGGUNA ================= -->
    <div>
        <div class="flex items-center gap-2 mb-3">
            <div class="w-7 h-7 bg-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold fm-title">Lokasi &amp; Pengguna</h3>
                <p class="text-xs fm-hint">Penempatan dan penanggung jawab</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label class="text-xs fm-label mb-1 block font-medium">Lokasi</label>
                <select name="lokasi_select" id="lokasi"
                        class="fm-input w-full px-3 py-2 rounded-lg text-xs border
                               focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition">
                    <option value="">Pilih Lokasi</option>
                </select>
                <input type="hidden" name="lokasi" id="hidden_lokasi">
            </div>

           <!-- SESUDAH -->
<div>
    <label class="text-xs fm-label mb-1 block font-medium">Pengguna</label>
    <div class="relative" id="pegawaiWrapper_create">
        <input type="text" id="pegawaiSearch_create"
               placeholder="🔍 Cari nama pegawai..."
               autocomplete="off"
               class="fm-input w-full px-3 py-2 rounded-lg text-xs border
                      focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition"
               oninput="filterPegawai_create(this.value)"
               onfocus="showDropdown_create()">
        <input type="hidden" name="pengguna" id="pegawaiValue_create">
        <div id="pegawaiDropdown_create"
             class="hidden absolute z-50 w-full mt-1 rounded-lg border shadow-xl overflow-y-auto fm-input"
             style="max-height:200px;padding:0;"></div>
    </div>
    <p class="text-xs mt-1 fm-hint">Biarkan kosong jika belum ada pengguna</p>
</div>

            <input type="hidden" name="ruang" value="">
            <input type="hidden" name="ruangan" value="">
        </div>
    </div>

    <!-- ================= SECTION 4: INFORMASI FINANSIAL ================= -->
    <div>
        <div class="flex items-center gap-2 mb-3">
            <div class="w-7 h-7 bg-yellow-600 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold fm-title">Informasi Finansial</h3>
                <p class="text-xs fm-hint">Data keuangan perangkat</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <input type="hidden" name="henti_guna" value="Tidak">
            <input type="hidden" name="usul_hapus" value="Tidak">

            <div>
                <label class="text-xs fm-label mb-1 block font-medium">Tanggal Perolehan</label>
                <input type="date" name="tanggal_perolehan"
                       class="fm-input w-full px-3 py-2 rounded-lg text-xs border
                              focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition">
            </div>

            <div>
                <label class="text-xs fm-label mb-1 block font-medium">Nilai Perolehan (Rp)</label>
                <input type="number" name="nilai_perolehan" placeholder="0"
                       class="fm-input w-full px-3 py-2 rounded-lg text-xs border
                              focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition">
            </div>

            <div class="md:col-span-2">
                <label class="text-xs fm-label mb-1 block font-medium">Transaksi SAKTI</label>
                <input type="text" name="transaksi_sakti" placeholder="Opsional"
                       class="fm-input w-full px-3 py-2 rounded-lg text-xs border
                              focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition">
            </div>
        </div>
    </div>

    <!-- ================= SECTION 5: KETERANGAN ================= -->
    <div>
        <label class="text-xs fm-label mb-1 block font-medium">Keterangan</label>
        <textarea name="keterangan" rows="3"
                  placeholder="Tambahkan catatan atau keterangan tambahan..."
                  class="fm-input w-full px-3 py-2 rounded-lg text-xs border
                         focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition resize-none"></textarea>
    </div>

    <!-- ================= BUTTONS ================= -->
    <div class="flex gap-3 justify-end pt-4 border-t fm-divider">
        <button type="button" id="btnBatalModal"
                class="flex items-center gap-2 fm-btn-cancel px-5 py-2 rounded-lg text-sm font-medium transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Batal
        </button>
        <button type="submit"
                class="flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white
                       px-5 py-2 rounded-lg text-sm font-medium hover:from-blue-700 hover:to-blue-800
                       transition shadow-lg hover:shadow-xl hover:-translate-y-0.5 transform">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Simpan Perangkat
        </button>
    </div>

</form>

<style>
/* ── DARK (default) ── */
.fm-title             { color: #f1f5f9; }
.fm-label             { color: #cbd5e1; }
.fm-hint              { color: #64748b; }
.fm-section-bg        { background: rgba(30,41,59,0.5); }
.fm-section-border    { border-color: rgba(51,65,85,0.6); }
.fm-input {
    background: #1e293b;
    border-color: #4b5563;
    color: #f1f5f9;
}
.fm-input::placeholder { color: #475569; }
.fm-input:focus        { background: #1e293b; }
.fm-input-readonly {
    background: #374151;
    border-color: #4b5563;
    color: #94a3b8;
}
.fm-upload-border     { border-color: #374151; }
.fm-preview-border    { border-color: #4b5563; }
.fm-upload-icon-bg    { background: #1e293b; }
.fm-upload-icon       { color: #64748b; }
.fm-divider           { border-color: #374151; }
.fm-btn-cancel {
    background: #374151;
    color: #f1f5f9;
}
.fm-btn-cancel:hover  { background: #4b5563; }

/* ── LIGHT ── */
html.light .fm-title             { color: #0f172a; }
html.light .fm-label             { color: #334155; }
html.light .fm-hint              { color: #94a3b8; }
html.light .fm-section-bg        { background: #f8fafc !important; }
html.light .fm-section-border    { border-color: #e2e8f0 !important; }
html.light .fm-input {
    background: #f8fafc !important;
    border-color: #cbd5e1 !important;
    color: #0f172a !important;
}
html.light .fm-input::placeholder { color: #94a3b8 !important; }
html.light .fm-input:focus        { background: #ffffff !important; }
html.light .fm-input option       { background: #ffffff; color: #0f172a; }
html.light .fm-input-readonly {
    background: #e2e8f0 !important;
    border-color: #cbd5e1 !important;
    color: #64748b !important;
}
html.light .fm-upload-border     { border-color: #cbd5e1 !important; }
html.light .fm-preview-border    { border-color: #cbd5e1 !important; }
html.light .fm-upload-icon-bg    { background: #f1f5f9 !important; }
html.light .fm-upload-icon       { color: #94a3b8 !important; }
html.light .fm-divider           { border-color: #e2e8f0 !important; }
html.light .fm-btn-cancel {
    background: #e2e8f0 !important;
    color: #334155 !important;
}
html.light .fm-btn-cancel:hover  { background: #cbd5e1 !important; }
</style>

