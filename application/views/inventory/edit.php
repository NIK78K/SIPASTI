<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mx-auto px-4 py-6">

    <!-- BREADCRUMB -->
    <div class="mb-4 text-sm header-subtitle">
        <a href="<?= base_url('inventory') ?>" class="hover:text-blue-400">Inventory</a>
        <span class="mx-2">/</span>
        <span class="header-subtitle">Edit Perangkat</span>
    </div>

    <!-- CARD UTAMA -->
    <div class="max-w-5xl mx-auto">
        <div class="theme-card backdrop-blur-sm rounded-xl shadow-2xl p-8 border">

            <!-- HEADER -->
            <div class="mb-8">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold header-title">Edit Perangkat</h2>
                        <p class="header-subtitle text-sm">Perbarui informasi perangkat inventory</p>
                    </div>
                </div>

                <!-- INFO PERANGKAT -->
                <div class="bg-blue-900/20 border border-blue-700/50 rounded-lg p-4">
                    <div class="grid grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="header-subtitle">Kode Barang:</span>
                            <span class="text-blue-300 font-semibold ml-2"><?= htmlspecialchars($item['kode_barang']) ?></span>
                        </div>
                        <div>
                            <span class="header-subtitle">NUP:</span>
                            <span class="text-blue-300 font-semibold ml-2"><?= htmlspecialchars($item['nup']) ?></span>
                        </div>
                        <div>
                            <span class="header-subtitle">Kondisi:</span>
                            <span class="ml-2 px-2 py-0.5 rounded text-xs font-semibold
                                <?php
                                    if($item['kondisi'] == 'Baik') echo 'bg-green-600 text-white';
                                    elseif($item['kondisi'] == 'Rusak Ringan') echo 'bg-yellow-600 text-white';
                                    else echo 'bg-red-600 text-white';
                                ?>">
                                <?= htmlspecialchars($item['kondisi']) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FORM EDIT -->
            <form action="<?= base_url('inventory/update/'.$item['id']) ?>"
                  method="post"
                  enctype="multipart/form-data"
                  id="editForm">

                <!-- ================= SECTION 1: DATA TETAP ================= -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold header-title mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 header-subtitle" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Data Tetap (Tidak Dapat Diubah)
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php
                        $readonly = [
                            'jenis_bmn'   => 'Jenis BMN',
                            'kode_satker' => 'Kode Satker',
                            'nama_satker' => 'Nama Satker',
                            'kode_barang' => 'Kode Barang',
                            'nup'         => 'NUP',
                        ];
                        ?>
                        <?php foreach ($readonly as $name => $label): ?>
                        <div>
                            <label class="text-xs header-subtitle mb-1.5 block font-medium flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <?= $label ?>
                            </label>
                            <input type="text"
                                   value="<?= htmlspecialchars($item[$name]) ?>"
                                   readonly
                                   class="w-full px-3 py-2.5 bg-gray-900/50 border border-gray-700/50
                                          rounded-lg header-subtitle cursor-not-allowed text-sm">
                        </div>
                        <?php endforeach ?>
                    </div>
                </div>

                <!-- ================= SECTION 2: INFORMASI PERANGKAT ================= -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold header-title mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Informasi Perangkat
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <!-- NAMA BARANG -->
                        <div>
                            <label class="text-xs header-subtitle mb-1.5 block font-medium">Nama Barang</label>
                            <input type="text"
                                   name="nama_barang"
                                   value="<?= htmlspecialchars($item['nama_barang']) ?>"
                                   class="w-full px-3 py-2.5 bg-slate-800/80 border border-slate-700/50
                                          rounded-lg header-title focus:outline-none focus:border-blue-500
                                          focus:ring-2 focus:ring-blue-500/20 transition text-sm">
                        </div>

                        <!-- MERK -->
                        <div>
                            <label class="text-xs header-subtitle mb-1.5 block font-medium">Merk</label>
                            <input type="text"
                                   name="merk"
                                   value="<?= htmlspecialchars($item['merk']) ?>"
                                   class="w-full px-3 py-2.5 bg-slate-800/80 border border-slate-700/50
                                          rounded-lg header-title focus:outline-none focus:border-blue-500
                                          focus:ring-2 focus:ring-blue-500/20 transition text-sm">
                        </div>

                        <!-- SERIAL NUMBER -->
                        <div>
                            <label class="text-xs header-subtitle mb-1.5 block font-medium">Serial Number</label>
                            <input type="text"
                                   name="serial_number"
                                   value="<?= htmlspecialchars($item['serial_number'] ?? '') ?>"
                                   class="w-full px-3 py-2.5 bg-slate-800/80 border border-slate-700/50
                                          rounded-lg header-title focus:outline-none focus:border-blue-500
                                          focus:ring-2 focus:ring-blue-500/20 transition text-sm">
                        </div>

                        <!-- MAC ADDRESS -->
                        <div>
                            <label class="text-xs header-subtitle mb-1.5 block font-medium">MAC Address</label>
                            <input type="text"
                                   name="mac_address"
                                   value="<?= htmlspecialchars($item['mac_address'] ?? '') ?>"
                                   class="w-full px-3 py-2.5 bg-slate-800/80 border border-slate-700/50
                                          rounded-lg header-title focus:outline-none focus:border-blue-500
                                          focus:ring-2 focus:ring-blue-500/20 transition text-sm">
                        </div>

                        <!-- KONDISI -->
                        <div>
                            <label class="text-xs header-subtitle mb-1.5 block font-medium">Kondisi</label>
                            <select name="kondisi"
                                    class="w-full px-3 py-2.5 bg-slate-800/80 border border-slate-700/50
                                           rounded-lg header-title focus:outline-none focus:border-blue-500
                                           focus:ring-2 focus:ring-blue-500/20 transition text-sm">
                                <option value="Baik"         <?= $item['kondisi'] == 'Baik'         ? 'selected' : '' ?>>✓ Baik</option>
                                <option value="Rusak Ringan" <?= $item['kondisi'] == 'Rusak Ringan' ? 'selected' : '' ?>>⚠ Rusak Ringan</option>
                                <option value="Rusak Berat"  <?= $item['kondisi'] == 'Rusak Berat'  ? 'selected' : '' ?>>✗ Rusak Berat</option>
                            </select>
                        </div>

                        <!-- KATEGORI ASET -->
                        <div>
                            <label class="text-xs header-subtitle mb-1.5 block font-medium flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                Kategori Aset
                            </label>
                            <select name="kategori_aset"
                                    class="w-full px-3 py-2.5 bg-slate-800/80 border border-slate-700/50
                                           rounded-lg header-title focus:outline-none focus:border-blue-500
                                           focus:ring-2 focus:ring-blue-500/20 transition text-sm">
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Notebook / Laptop"     <?= ($item['kategori_aset'] ?? '') === 'Notebook / Laptop'     ? 'selected' : '' ?>>💻 Notebook / Laptop</option>
                                <option value="Komputer Desktop / PC" <?= ($item['kategori_aset'] ?? '') === 'Komputer Desktop / PC' ? 'selected' : '' ?>>🖥️ Komputer Desktop / PC</option>
                                <option value="Komputer Server"       <?= ($item['kategori_aset'] ?? '') === 'Komputer Server'       ? 'selected' : '' ?>>🗄️ Komputer Server</option>
                                <option value="Perangkat Jaringan"    <?= ($item['kategori_aset'] ?? '') === 'Perangkat Jaringan'    ? 'selected' : '' ?>>🌐 Perangkat Jaringan</option>
                                <option value="Printer / Scanner"     <?= ($item['kategori_aset'] ?? '') === 'Printer / Scanner'     ? 'selected' : '' ?>>🖨️ Printer / Scanner</option>
                                <option value="Perangkat Lain"        <?= ($item['kategori_aset'] ?? '') === 'Perangkat Lain'        ? 'selected' : '' ?>>📦 Perangkat Lain</option>
                            </select>
                        </div>

                        <!-- STATUS ASET -->
                        <div>
                            <label class="text-xs header-subtitle mb-1.5 block font-medium flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                                Status Aset
                            </label>
                            <select name="status_aset"
                                    class="w-full px-3 py-2.5 bg-slate-800/80 border border-slate-700/50
                                           rounded-lg header-title focus:outline-none focus:border-green-500
                                           focus:ring-2 focus:ring-green-500/20 transition text-sm">
                                <option value="BMN"        <?= ($item['status_aset'] ?? 'BMN') === 'BMN'        ? 'selected' : '' ?>>🏛️ BMN</option>
                                <option value="Consumable" <?= ($item['status_aset'] ?? '') === 'Consumable' ? 'selected' : '' ?>>📦 Consumable</option>
                            </select>
                        </div>

                    </div>
                </div>

                <!-- ================= SECTION 2.5: SPESIFIKASI PERANGKAT ================= -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold header-title mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                        </svg>
                        Spesifikasi Teknis (Opsional)
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <!-- KATEGORI SPESIFIKASI -->
                        <div>
                            <label class="text-xs header-subtitle mb-1.5 block font-medium">Kategori</label>
                            <select name="spec_kategori"
                                    class="w-full px-3 py-2.5 bg-slate-800/80 border border-slate-700/50
                                           rounded-lg header-title focus:outline-none focus:border-cyan-500
                                           focus:ring-2 focus:ring-cyan-500/20 transition text-sm">
                                <option value="">Tidak Ada</option>
                                <option value="Berwujud"       <?= ($spesifikasi['kategori'] ?? '') == 'Berwujud'       ? 'selected' : '' ?>>Berwujud</option>
                                <option value="Tidak Berwujud" <?= ($spesifikasi['kategori'] ?? '') == 'Tidak Berwujud' ? 'selected' : '' ?>>Tidak Berwujud</option>
                            </select>
                        </div>

                        <!-- PROCESSOR -->
                        <div>
                            <label class="text-xs header-subtitle mb-1.5 block font-medium">Processor</label>
                            <input type="text"
                                   name="spec_processor"
                                   value="<?= htmlspecialchars($spesifikasi['processor'] ?? '') ?>"
                                   placeholder="Contoh: Intel Core i7-1165G7"
                                   class="w-full px-3 py-2.5 bg-slate-800/80 border border-slate-700/50
                                          rounded-lg header-title placeholder-gray-500
                                          focus:outline-none focus:border-cyan-500
                                          focus:ring-2 focus:ring-cyan-500/20 transition text-sm">
                        </div>

                        <!-- RAM -->
                        <div>
                            <label class="text-xs header-subtitle mb-1.5 block font-medium">RAM (GB)</label>
                            <input type="number"
                                   name="spec_ram_gb"
                                   value="<?= htmlspecialchars($spesifikasi['ram_gb'] ?? '') ?>"
                                   placeholder="Contoh: 16"
                                   class="w-full px-3 py-2.5 bg-slate-800/80 border border-slate-700/50
                                          rounded-lg header-title placeholder-gray-500
                                          focus:outline-none focus:border-cyan-500
                                          focus:ring-2 focus:ring-cyan-500/20 transition text-sm">
                        </div>

                        <!-- STORAGE -->
                        <div>
                            <label class="text-xs header-subtitle mb-1.5 block font-medium">Storage (GB)</label>
                            <input type="number"
                                   name="spec_storage_gb"
                                   value="<?= htmlspecialchars($spesifikasi['storage_gb'] ?? '') ?>"
                                   placeholder="Contoh: 512"
                                   class="w-full px-3 py-2.5 bg-slate-800/80 border border-slate-700/50
                                          rounded-lg header-title placeholder-gray-500
                                          focus:outline-none focus:border-cyan-500
                                          focus:ring-2 focus:ring-cyan-500/20 transition text-sm">
                        </div>

                        <!-- STORAGE TYPE -->
                        <div>
                            <label class="text-xs header-subtitle mb-1.5 block font-medium">Tipe Storage</label>
                            <select name="spec_storage_type"
                                    class="w-full px-3 py-2.5 bg-slate-800/80 border border-slate-700/50
                                           rounded-lg header-title focus:outline-none focus:border-cyan-500
                                           focus:ring-2 focus:ring-cyan-500/20 transition text-sm">
                                <option value="">Pilih Tipe</option>
                                <option value="SSD"  <?= ($spesifikasi['storage_type'] ?? '') == 'SSD'  ? 'selected' : '' ?>>SSD</option>
                                <option value="NVMe" <?= ($spesifikasi['storage_type'] ?? '') == 'NVMe' ? 'selected' : '' ?>>NVMe</option>
                                <option value="HDD"  <?= ($spesifikasi['storage_type'] ?? '') == 'HDD'  ? 'selected' : '' ?>>HDD</option>
                            </select>
                        </div>

                        <!-- GPU -->
                        <div>
                            <label class="text-xs header-subtitle mb-1.5 block font-medium">GPU</label>
                            <input type="text"
                                   name="spec_gpu"
                                   value="<?= htmlspecialchars($spesifikasi['gpu'] ?? '') ?>"
                                   placeholder="Contoh: NVIDIA GTX 1650"
                                   class="w-full px-3 py-2.5 bg-slate-800/80 border border-slate-700/50
                                          rounded-lg header-title placeholder-gray-500
                                          focus:outline-none focus:border-cyan-500
                                          focus:ring-2 focus:ring-cyan-500/20 transition text-sm">
                        </div>

                        <!-- OS -->
                        <div>
                            <label class="text-xs header-subtitle mb-1.5 block font-medium">Operating System</label>
                            <input type="text"
                                   name="spec_os"
                                   value="<?= htmlspecialchars($spesifikasi['os'] ?? '') ?>"
                                   placeholder="Contoh: Windows 11 Pro"
                                   class="w-full px-3 py-2.5 bg-slate-800/80 border border-slate-700/50
                                          rounded-lg header-title placeholder-gray-500
                                          focus:outline-none focus:border-cyan-500
                                          focus:ring-2 focus:ring-cyan-500/20 transition text-sm">
                        </div>

                    </div>
                </div>

                <!-- ================= SECTION 2.6: FOTO PERANGKAT ================= -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold header-title mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Foto Perangkat
                    </h3>

                    <?php if (!empty($item['foto'])): ?>
                    <div class="mb-4 p-4 bg-slate-800/50 border border-slate-700/50 rounded-lg">
                        <div class="flex items-start gap-4">
                            <img src="<?= base_url('uploads/perangkat/'.$item['foto']) ?>"
                                 alt="Foto Perangkat"
                                 class="w-48 h-48 object-cover rounded-lg border-2 border-gray-600">
                            <div class="flex-1">
                                <p class="text-sm header-subtitle mb-3">Foto saat ini:</p>
                                <p class="text-xs text-gray-500 font-mono mb-4"><?= htmlspecialchars($item['foto']) ?></p>
                                <label class="flex items-center gap-2 text-sm text-red-400 cursor-pointer hover:text-red-300">
                                    <input type="checkbox" name="hapus_foto" value="1" class="rounded">
                                    Hapus foto ini
                                </label>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="border-2 border-dashed border-slate-700/50 rounded-lg p-6 text-center">
                        <input type="file"
                               name="foto"
                               id="foto_edit"
                               accept="image/jpeg,image/jpg,image/png,image/gif"
                               class="hidden"
                               onchange="previewFotoEdit(event)">

                        <div id="preview-container-edit" class="hidden mb-4">
                            <img id="foto-preview-edit"
                                 class="max-h-64 mx-auto rounded-lg shadow-lg border-2 border-gray-600">
                            <button type="button"
                                    onclick="removeFotoEdit()"
                                    class="mt-3 text-red-400 hover:text-red-300 text-sm flex items-center gap-1 mx-auto">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Batalkan Upload
                            </button>
                        </div>

                        <label for="foto_edit" id="upload-label-edit" class="cursor-pointer">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 bg-slate-800/80 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 header-subtitle" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-cyan-400 hover:text-cyan-300 font-medium">
                                        <?= !empty($item['foto']) ? 'Upload Foto Baru' : 'Klik untuk upload' ?>
                                    </span>
                                    <p class="header-subtitle text-xs mt-1">JPG, PNG, GIF (maks 2MB)</p>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- ================= SECTION 3: LOKASI & PENGGUNA ================= -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold header-title mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Lokasi & Pengguna
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <!-- LOKASI (DROPDOWN DARI MASTER) -->
                        <div>
                            <label class="text-xs header-subtitle mb-1.5 block font-medium">Lokasi</label>
                            <select name="lokasi"
                                    class="w-full px-3 py-2.5 bg-slate-800/80 border border-slate-700/50
                                           rounded-lg header-title focus:outline-none focus:border-purple-500
                                           focus:ring-2 focus:ring-purple-500/20 transition text-sm">
                                <option value="">-- Pilih Lokasi --</option>
                                <?php foreach ($master_lokasi as $lok): ?>
                                <option value="<?= htmlspecialchars($lok['nama_lokasi']) ?>"
                                    <?= ($item['lokasi'] ?? '') === $lok['nama_lokasi'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($lok['nama_lokasi']) ?>
                                </option>
                                <?php endforeach; ?>
                                <?php
                                $lokasi_lama   = $item['lokasi'] ?? '';
                                $ada_di_master = false;
                                foreach ($master_lokasi as $lok) {
                                    if ($lok['nama_lokasi'] === $lokasi_lama) {
                                        $ada_di_master = true;
                                        break;
                                    }
                                }
                                if (!empty($lokasi_lama) && !$ada_di_master): ?>
                                <option value="<?= htmlspecialchars($lokasi_lama) ?>" selected>
                                    <?= htmlspecialchars($lokasi_lama) ?> (data lama)
                                </option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- RUANGAN -->
                        <div>
                            <label class="text-xs header-subtitle mb-1.5 block font-medium">Ruangan</label>
                            <input type="text"
                                   name="ruangan"
                                   value="<?= htmlspecialchars($item['ruangan'] ?? '') ?>"
                                   class="w-full px-3 py-2.5 bg-slate-800/80 border border-slate-700/50
                                          rounded-lg header-title focus:outline-none focus:border-purple-500
                                          focus:ring-2 focus:ring-purple-500/20 transition text-sm">
                        </div>

                        <!-- ✅ PENGGUNA - SEARCHABLE DROPDOWN (FIXED) -->
                        <div>
                            <label class="text-xs header-subtitle mb-1.5 block font-medium">Pengguna</label>
                            <div class="relative" id="pegawaiWrapper_edit">

                                <!-- ✅ Search input: hanya untuk tampilan/pencarian, TIDAK disubmit -->
                                <input type="text"
                                       id="pegawaiSearch_edit"
                                       placeholder="🔍 Cari nama pegawai..."
                                       autocomplete="off"
                                       class="w-full px-3 py-2.5 bg-slate-800/80 border border-slate-700/50
                                              rounded-lg header-title focus:outline-none focus:border-purple-500
                                              focus:ring-2 focus:ring-purple-500/20 transition text-sm"
                                       oninput="filterPegawai_edit(this.value)"
                                       onfocus="showDropdown_edit()">

                                <!-- ✅ Hidden input: yang benar-benar disubmit ke server -->
                                <input type="hidden"
                                       name="pengguna"
                                       id="pegawaiValue_edit"
                                       value="<?= htmlspecialchars($item['pengguna'] ?? '') ?>">

                                <!-- Dropdown list -->
                                <div id="pegawaiDropdown_edit"
                                     class="hidden absolute z-50 w-full mt-1 rounded-lg border shadow-xl overflow-y-auto"
                                     style="max-height:220px;background:#1e293b;border-color:#4b5563;"></div>
                            </div>
                            <p class="text-xs mt-1" style="color:#64748b;">
                                Biarkan kosong jika belum ada pengguna
                            </p>
                        </div>

                    </div>
                </div>

                <!-- ================= SECTION 4: INFORMASI FINANSIAL ================= -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold header-title mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Informasi Finansial
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <!-- TANGGAL PEROLEHAN -->
                        <div>
                            <label class="text-xs header-subtitle mb-1.5 block font-medium">Tanggal Perolehan</label>
                            <input type="date"
                                   name="tanggal_perolehan"
                                   value="<?= htmlspecialchars($item['tanggal_perolehan'] ?? '') ?>"
                                   class="w-full px-3 py-2.5 bg-slate-800/80 border border-slate-700/50
                                          rounded-lg header-title focus:outline-none focus:border-yellow-500
                                          focus:ring-2 focus:ring-yellow-500/20 transition text-sm">
                        </div>

                        <!-- NILAI PEROLEHAN -->
                        <div>
                            <label class="text-xs header-subtitle mb-1.5 block font-medium">Nilai Perolehan (Rp)</label>
                            <input type="number"
                                   name="nilai_perolehan"
                                   value="<?= htmlspecialchars($item['nilai_perolehan'] ?? '') ?>"
                                   class="w-full px-3 py-2.5 bg-slate-800/80 border border-slate-700/50
                                          rounded-lg header-title focus:outline-none focus:border-yellow-500
                                          focus:ring-2 focus:ring-yellow-500/20 transition text-sm">
                        </div>

                    </div>
                </div>

                <!-- ================= SECTION 5: KETERANGAN ================= -->
                <div class="mb-6">
                    <label class="text-xs header-subtitle mb-1.5 block font-medium">Keterangan</label>
                    <textarea name="keterangan"
                              rows="4"
                              placeholder="Tambahkan catatan atau keterangan tambahan..."
                              class="w-full px-3 py-2.5 bg-slate-800/80 border border-slate-700/50
                                     rounded-lg header-title focus:outline-none focus:border-blue-500
                                     focus:ring-2 focus:ring-blue-500/20 transition text-sm resize-none placeholder-gray-500"><?= htmlspecialchars($item['keterangan'] ?? '') ?></textarea>
                </div>

                <!-- ================= BUTTONS ================= -->
                <div class="flex gap-3 justify-end pt-4 border-t border-slate-700/50">
                    <a href="<?= base_url('inventory') ?>"
                       class="flex items-center gap-2 bg-gray-600 text-white px-6 py-2.5 rounded-lg hover:bg-gray-700 transition font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Batal
                    </a>
                    <button type="submit"
                            class="flex items-center gap-2 bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
/* =========================================================
   HELPERS
========================================================= */
function getSwalColors() {
    const isLight = document.documentElement.classList.contains('light');
    return {
        background: isLight ? '#ffffff' : '#1a1f2e',
        color: isLight ? '#0f172a' : '#f3f4f6'
    };
}

/* =========================================================
   FOTO PREVIEW
========================================================= */
function previewFotoEdit(event) {
    const file = event.target.files[0];
    if (!file) return;
    const colors = getSwalColors();
    if (file.size > 2097152) {
        Swal.fire({ icon: 'error', title: 'File Terlalu Besar', text: 'Ukuran file maksimal 2MB', confirmButtonColor: '#dc2626', ...colors });
        event.target.value = '';
        return;
    }
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    if (!allowedTypes.includes(file.type)) {
        Swal.fire({ icon: 'error', title: 'Format Tidak Valid', text: 'Hanya file JPG, PNG, dan GIF yang diperbolehkan', confirmButtonColor: '#dc2626', ...colors });
        event.target.value = '';
        return;
    }
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('foto-preview-edit').src = e.target.result;
        document.getElementById('preview-container-edit').classList.remove('hidden');
        document.getElementById('upload-label-edit').classList.add('hidden');
    };
    reader.readAsDataURL(file);
}

function removeFotoEdit() {
    document.getElementById('foto_edit').value = '';
    document.getElementById('preview-container-edit').classList.add('hidden');
    document.getElementById('upload-label-edit').classList.remove('hidden');
}

/* =========================================================
   FORM SUBMIT
   ✅ FIX: sync pegawai search box → hidden input
          SEBELUM konfirmasi dan submit
========================================================= */
document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // ✅ KUNCI FIX: apapun yang ada di search box → masuk ke hidden input
    // Ini menangkap kasus user mengetik langsung tanpa klik dropdown item
    const searchEl = document.getElementById('pegawaiSearch_edit');
    const valueEl  = document.getElementById('pegawaiValue_edit');
    if (searchEl && valueEl) {
        valueEl.value = searchEl.value.trim();
    }

    const colors = getSwalColors();
    Swal.fire({
        icon: 'question',
        title: 'Simpan Perubahan?',
        text: 'Pastikan data yang Anda ubah sudah benar',
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Simpan',
        cancelButtonText: 'Batal',
        ...colors
    }).then((result) => {
        if (result.isConfirmed) { submitForm(); }
    });
});

function submitForm() {
    const colors = getSwalColors();
    Swal.fire({
        title: 'Menyimpan...',
        html: 'Mohon tunggu, sedang memproses data',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => { Swal.showLoading(); },
        ...colors
    });
    document.getElementById('editForm').submit();
}

/* =========================================================
   SEARCHABLE PEGAWAI DROPDOWN (EDIT)
   ✅ FIX: ganti onmousedown → onclick agar lebih reliable
          dan tidak kalah dengan event blur
========================================================= */
let _pegawaiList_edit   = [];
let _pegawaiLoaded_edit = false;

async function loadPegawai_edit() {
    if (_pegawaiLoaded_edit) return;
    try {
        const res = await fetch('<?= base_url('inventory/get_pegawai_list') ?>');
        _pegawaiList_edit   = await res.json();
        _pegawaiLoaded_edit = true;
    } catch (e) {
        _pegawaiList_edit = [];
    }
}

function renderDropdown_edit(list) {
    const dd      = document.getElementById('pegawaiDropdown_edit');
    const isLight = document.documentElement.classList.contains('light');
    const clr     = isLight ? '#0f172a' : '#f1f5f9';
    const muted   = '#94a3b8';
    const sep     = isLight ? '#e2e8f0' : '#374151';
    const bg      = isLight ? '#f8fafc' : '#1e293b';
    const bgHover = isLight ? '#e2e8f0' : '#334155';

    if (!list.length) {
        dd.innerHTML = `<div style="padding:10px 12px;font-size:12px;color:${muted};">Tidak ada hasil</div>`;
        return;
    }

    // ✅ onclick (bukan onmousedown) — lebih reliable, tidak tabrakan dengan blur
    dd.innerHTML =
        `<div style="padding:6px 12px;font-size:11px;color:${muted};border-bottom:1px solid ${sep};">
            Pilih pegawai atau kosongkan
        </div>`
        + `<div style="padding:8px 12px;font-size:12px;cursor:pointer;color:#f87171;background:${bg};border-bottom:1px solid ${sep};"
                onclick="selectPegawai_edit('','')">✕ Kosongkan Pengguna</div>`
        + list.map(p =>
            `<div style="padding:8px 12px;font-size:12px;cursor:pointer;color:${clr};
                          background:${bg};border-bottom:1px solid ${sep};"
                  onmouseover="this.style.background='${bgHover}'"
                  onmouseout="this.style.background='${bg}'"
                  onclick="selectPegawai_edit('${p.nama.replace(/\\/g,'\\\\').replace(/'/g,"\\'")}','${p.nama.replace(/\\/g,'\\\\').replace(/'/g,"\\'")}')">
                <span style="font-weight:600;">${p.nama}</span>
                <span style="font-size:10px;color:${muted};display:block;">${p.jabatan || ''}</span>
             </div>`
          ).join('');
}

function filterPegawai_edit(q) {
    const dd = document.getElementById('pegawaiDropdown_edit');
    dd.classList.remove('hidden');
    const filtered = q.trim()
        ? _pegawaiList_edit.filter(p =>
            p.nama.toLowerCase().includes(q.toLowerCase()) ||
            (p.jabatan || '').toLowerCase().includes(q.toLowerCase()))
        : _pegawaiList_edit;
    renderDropdown_edit(filtered);
}

function showDropdown_edit() {
    loadPegawai_edit().then(() => {
        filterPegawai_edit(document.getElementById('pegawaiSearch_edit').value);
    });
}

function selectPegawai_edit(value, label) {
    document.getElementById('pegawaiValue_edit').value = value;   // ← nilai yg disubmit
    document.getElementById('pegawaiSearch_edit').value = label;  // ← tampilan
    document.getElementById('pegawaiDropdown_edit').classList.add('hidden');
}

// Tutup dropdown kalau klik di luar wrapper
document.addEventListener('click', function(e) {
    const wrapper = document.getElementById('pegawaiWrapper_edit');
    if (wrapper && !wrapper.contains(e.target)) {
        document.getElementById('pegawaiDropdown_edit').classList.add('hidden');
    }
});

// Set tampilan awal jika ada nilai existing dari DB
window.addEventListener('DOMContentLoaded', function() {
    const existing = document.getElementById('pegawaiValue_edit').value;
    if (existing) {
        document.getElementById('pegawaiSearch_edit').value = existing;
    }
});
</script>

<style>
html.light input,
html.light select,
html.light textarea {
    background: rgba(255, 255, 255, 0.95) !important;
    color: var(--text-primary) !important;
    border-color: rgba(203, 213, 225, 0.8) !important;
}
html.light input[readonly] {
    background: rgba(248, 250, 252, 0.8) !important;
    color: var(--text-secondary) !important;
}
html.light select option {
    background: white !important;
    color: var(--text-primary) !important;
}
/* Dropdown pegawai light mode */
html.light #pegawaiDropdown_edit {
    background: #f8fafc !important;
    border-color: #cbd5e1 !important;
}
</style>