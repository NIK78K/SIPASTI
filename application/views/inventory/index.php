<!-- ================= JUDUL ================= -->
<h1 class="text-2xl font-bold mb-1 header-title">Inventory Perangkat</h1>
<p class="text-sm mb-6 header-subtitle">Manajemen perangkat IT (BMN)</p>

<!-- ================= KARTU KONDISI ================= -->
<div class="grid grid-cols-3 gap-3 mb-5">

    <div class="inv-card border-l-4 border-green-500 p-3 rounded-lg
                hover:shadow-lg hover:shadow-green-500/10 transition-all">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-green-500/10 rounded-lg">
                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="text-xs header-subtitle">Baik</div>
                <div class="text-xl font-bold text-green-400"><?= $baik ?></div>
            </div>
        </div>
    </div>

    <div class="inv-card border-l-4 border-yellow-500 p-3 rounded-lg
                hover:shadow-lg hover:shadow-yellow-500/10 transition-all">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-yellow-500/10 rounded-lg">
                <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <div class="text-xs header-subtitle">Rusak Ringan</div>
                <div class="text-xl font-bold text-yellow-400"><?= $rusak_ringan ?></div>
            </div>
        </div>
    </div>

    <div class="inv-card border-l-4 border-red-500 p-3 rounded-lg
                hover:shadow-lg hover:shadow-red-500/10 transition-all">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-red-500/10 rounded-lg">
                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="text-xs header-subtitle">Rusak Berat</div>
                <div class="text-xl font-bold text-red-400"><?= $rusak_berat ?></div>
            </div>
        </div>
    </div>

</div>

<!-- ================= AKSI ================= -->
<div class="flex items-center gap-2 mb-3 flex-wrap">

    <button id="btnTambah"
        class="inline-flex items-center gap-1.5 h-9 px-4 text-white
               bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800
               rounded-lg text-xs font-medium shadow-lg shadow-blue-500/20 transition-all hover:-translate-y-0.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
        Tambah
    </button>

    <a href="<?= base_url('inventory/import') ?>"
       class="inline-flex items-center gap-1.5 h-9 px-4 text-white
              bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800
              rounded-lg text-xs font-medium shadow-lg shadow-green-500/20 transition-all hover:-translate-y-0.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
        </svg>
        Import Excel
    </a>

    <a href="<?= base_url('inventory/export')
        .'?keyword='.urlencode($this->input->get('keyword'))
        .'&kondisi='.urlencode($this->input->get('kondisi'))
        .'&satker='.urlencode($this->input->get('satker')) ?>"
       class="inline-flex items-center gap-1.5 h-9 px-4 text-white
              bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800
              rounded-lg text-xs font-medium shadow-lg shadow-emerald-500/20 transition-all hover:-translate-y-0.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Export Excel
    </a>

</div>

<!-- ================= FILTER ================= -->
<form method="get" class="inv-card p-3 rounded-lg border mb-3 space-y-3">

    <!-- QUICK FILTER -->
    <div class="grid grid-cols-4 gap-2">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                <svg class="w-4 h-4 inv-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input type="text" name="keyword" value="<?= $this->input->get('keyword') ?>"
                   placeholder="Cari nama / kode / merk"
                   class="inv-input pl-8 w-full px-3 py-2 rounded-lg text-xs border
                          focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
        </div>

        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                <svg class="w-4 h-4 inv-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                </svg>
            </div>
            <input type="text" name="nup" value="<?= $this->input->get('nup') ?>"
                   placeholder="NUP"
                   class="inv-input pl-8 w-full px-3 py-2 rounded-lg text-xs border
                          focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
        </div>

        <div class="col-span-2 flex gap-2 justify-end">
            <button type="submit"
                    class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-500 text-white
                           px-4 py-2 rounded-lg text-xs font-medium transition shadow-md">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                Filter
            </button>
            <a href="<?= base_url('inventory') ?>"
               class="inline-flex items-center gap-1.5 inv-btn-reset text-white
                      px-4 py-2 rounded-lg text-xs font-medium transition shadow-md">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Reset
            </a>
        </div>
    </div>

    <!-- ADVANCED FILTER -->
    <div class="grid grid-cols-5 gap-2 pt-2.5 border-t inv-divider">

        <div>
            <div class="text-xs inv-filter-label mb-1 font-medium flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Kondisi
            </div>
            <select name="kondisi"
                    class="inv-input w-full px-2.5 py-2 rounded-lg text-xs border
                           focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                <option value="">Semua</option>
                <option value="Baik"         <?= $this->input->get('kondisi')=='Baik'         ?'selected':'' ?>>Baik</option>
                <option value="Rusak Ringan" <?= $this->input->get('kondisi')=='Rusak Ringan' ?'selected':'' ?>>Rusak Ringan</option>
                <option value="Rusak Berat"  <?= $this->input->get('kondisi')=='Rusak Berat'  ?'selected':'' ?>>Rusak Berat</option>
            </select>
        </div>

        <div>
            <div class="text-xs inv-filter-label mb-1 font-medium flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                Kategori
            </div>
            <select name="kategori_aset"
                    class="inv-input w-full px-2.5 py-2 rounded-lg text-xs border
                           focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                <option value="">Semua</option>
                <option value="Notebook / Laptop"     <?= $this->input->get('kategori_aset')=='Notebook / Laptop'     ?'selected':'' ?>>💻 Laptop</option>
                <option value="Komputer Desktop / PC" <?= $this->input->get('kategori_aset')=='Komputer Desktop / PC' ?'selected':'' ?>>🖥️ Desktop</option>
                <option value="Komputer Server"       <?= $this->input->get('kategori_aset')=='Komputer Server'       ?'selected':'' ?>>🗄️ Server</option>
                <option value="Perangkat Jaringan"    <?= $this->input->get('kategori_aset')=='Perangkat Jaringan'    ?'selected':'' ?>>🌐 Jaringan</option>
                <option value="Printer / Scanner"     <?= $this->input->get('kategori_aset')=='Printer / Scanner'     ?'selected':'' ?>>🖨️ Printer</option>
                <option value="Perangkat Lain"        <?= $this->input->get('kategori_aset')=='Perangkat Lain'        ?'selected':'' ?>>📦 Lainnya</option>
            </select>
        </div>

        <div>
            <div class="text-xs inv-filter-label mb-1 font-medium flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Tipe
            </div>
            <select name="nama_barang"
                    class="inv-input w-full px-2.5 py-2 rounded-lg text-xs border
                           focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                <option value="">Semua</option>
                <?php
                $tipe_perangkat = $unique_nama_barang ?? ['PC Workstation','P.C Unit','Laptop','PC Desktop','Notebook','Server','Komputer'];
                foreach ($tipe_perangkat as $tipe):
                    $selected = $this->input->get('nama_barang') == $tipe ? 'selected' : '';
                ?>
                <option value="<?= htmlspecialchars($tipe) ?>" <?= $selected ?>><?= htmlspecialchars($tipe) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <div class="text-xs inv-filter-label mb-1 font-medium flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                </svg>
                RAM
            </div>
            <select name="ram"
                    class="inv-input w-full px-2.5 py-2 rounded-lg text-xs border
                           focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                <option value="">Semua</option>
                <option value="2"  <?= $this->input->get('ram')=='2'  ?'selected':'' ?>>2 GB</option>
                <option value="4"  <?= $this->input->get('ram')=='4'  ?'selected':'' ?>>4 GB</option>
                <option value="8"  <?= $this->input->get('ram')=='8'  ?'selected':'' ?>>8 GB</option>
                <option value="16" <?= $this->input->get('ram')=='16' ?'selected':'' ?>>16 GB</option>
                <option value="32" <?= $this->input->get('ram')=='32' ?'selected':'' ?>>32 GB</option>
                <option value="64" <?= $this->input->get('ram')=='64' ?'selected':'' ?>>64 GB</option>
            </select>
        </div>

        <div>
            <div class="text-xs inv-filter-label mb-1 font-medium flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                </svg>
                Storage
            </div>
            <select name="storage_type"
                    class="inv-input w-full px-2.5 py-2 rounded-lg text-xs border
                           focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                <option value="">Semua</option>
                <option value="SSD"  <?= $this->input->get('storage_type')=='SSD'  ?'selected':'' ?>>SSD</option>
                <option value="NVMe" <?= $this->input->get('storage_type')=='NVMe' ?'selected':'' ?>>NVMe</option>
                <option value="HDD"  <?= $this->input->get('storage_type')=='HDD'  ?'selected':'' ?>>HDD</option>
            </select>
        </div>

    </div>
</form>

<!-- ================= INSIGHT MINI ================= -->
<div class="mb-2 px-3 py-2 inv-card border rounded-lg text-xs header-subtitle flex items-center gap-2">
    <svg class="w-3.5 h-3.5 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span><?= $insight ?></span>
</div>

<!-- ================= TABEL ================= -->
<div class="overflow-x-auto border rounded-lg inv-card">
    <table class="w-full" style="font-size:11px;">
        <thead class="inv-thead sticky top-0">
            <tr>
                <th class="px-2 py-2 text-center w-7 inv-th">No</th>
                <th class="px-2 py-2 text-left inv-th">Jenis BMN</th>
                <th class="px-2 py-2 text-left inv-th">Kd Satker</th>
                <th class="px-2 py-2 text-left inv-th">Nama Satker</th>
                <th class="px-2 py-2 text-left inv-th">Kd Barang</th>
                <th class="px-2 py-2 text-left inv-th">NUP</th>
                <th class="px-2 py-2 text-left inv-th">Nama Barang</th>
                <th class="px-2 py-2 text-left inv-th">Kategori</th>
                <th class="px-2 py-2 text-left inv-th">Merk</th>
                <th class="px-2 py-2 text-center inv-th">Kondisi</th>
                <th class="px-2 py-2 text-center w-28 inv-th">Aksi</th>
            </tr>
        </thead>

        <tbody>
<?php if (!empty($perangkat)): ?>
<?php $no = $from; ?>
<?php foreach ($perangkat as $p): ?>

<tr class="inv-row border-b inv-border transition-colors">
    <td class="px-2 py-1.5 text-center inv-cell-muted"><?= $no++ ?></td>
    <td class="px-2 py-1.5 inv-cell"><?= $p['jenis_bmn'] ?></td>
    <td class="px-2 py-1.5 inv-cell"><?= $p['kode_satker'] ?></td>
    <td class="px-2 py-1.5 inv-cell max-w-[120px] truncate" title="<?= htmlspecialchars($p['nama_satker']) ?>"><?= $p['nama_satker'] ?></td>
    <td class="px-2 py-1.5 inv-cell"><?= $p['kode_barang'] ?></td>
    <td class="px-2 py-1.5 inv-cell"><?= $p['nup'] ?></td>
    <td class="px-2 py-1.5 inv-cell font-medium max-w-[110px] truncate" title="<?= htmlspecialchars($p['nama_barang']) ?>"><?= $p['nama_barang'] ?></td>

    <!-- KATEGORI ASET -->
    <td class="px-2 py-1.5">
        <?php
        $kat = $p['kategori_aset'] ?? '';
        $kat_badge = match($kat) {
            'Notebook / Laptop'       => 'bg-blue-500/10 text-blue-400 ring-1 ring-blue-500/20',
            'Komputer Desktop / PC'   => 'bg-indigo-500/10 text-indigo-400 ring-1 ring-indigo-500/20',
            'Komputer Server'         => 'bg-purple-500/10 text-purple-400 ring-1 ring-purple-500/20',
            'Perangkat Jaringan'      => 'bg-cyan-500/10 text-cyan-400 ring-1 ring-cyan-500/20',
            'Printer / Scanner'       => 'bg-orange-500/10 text-orange-400 ring-1 ring-orange-500/20',
            'Perangkat Lain'          => 'bg-gray-500/10 text-gray-400 ring-1 ring-gray-500/20',
            default                   => '',
        };
        $kat_icon = match($kat) {
            'Notebook / Laptop'       => '💻',
            'Komputer Desktop / PC'   => '🖥️',
            'Komputer Server'         => '🗄️',
            'Perangkat Jaringan'      => '🌐',
            'Printer / Scanner'       => '🖨️',
            'Perangkat Lain'          => '📦',
            default                   => '',
        };
        ?>
        <?php if ($kat): ?>
        <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-full whitespace-nowrap <?= $kat_badge ?>"
              style="font-size:10px;">
            <?= $kat_icon ?>
            <span class="hidden lg:inline"><?= htmlspecialchars($kat) ?></span>
        </span>
        <?php else: ?>
        <span class="inv-cell-muted">—</span>
        <?php endif; ?>
    </td>

    <td class="px-2 py-1.5 inv-cell max-w-[80px] truncate" title="<?= htmlspecialchars($p['merk']) ?>"><?= $p['merk'] ?></td>

    <td class="px-2 py-1.5 text-center">
        <?php
        $badge = match ($p['kondisi']) {
            'Baik'         => 'bg-green-600/15 text-green-400 ring-1 ring-green-600/30',
            'Rusak Ringan' => 'bg-yellow-500/15 text-yellow-400 ring-1 ring-yellow-500/30',
            'Rusak Berat'  => 'bg-red-600/15 text-red-400 ring-1 ring-red-600/30',
            default        => 'bg-gray-600/15 text-gray-400 ring-1 ring-gray-600/30',
        };
        ?>
        <span class="inline-flex items-center px-2 py-0.5 rounded-full whitespace-nowrap <?= $badge ?>"
              style="font-size:10px;font-weight:600;">
            <?= $p['kondisi'] ?>
        </span>
    </td>

    <!-- ================= KOLOM AKSI ================= -->
    <td class="px-2 py-1.5 text-center">
        <div class="inline-flex items-center gap-1">

            <!-- DETAIL -->
            <button type="button" title="Detail" data-id="<?= $p['id'] ?>"
                class="btnDetail h-6 w-6 rounded bg-indigo-500/10 hover:bg-indigo-500/20
                       text-indigo-400 hover:text-indigo-300 flex items-center justify-center
                       transition-all hover:scale-110 border border-indigo-500/20">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </button>

            <!-- EDIT -->
            <a href="<?= base_url('inventory/edit/'.$p['id']) ?>" title="Edit"
               class="h-6 w-6 rounded bg-blue-500/10 hover:bg-blue-500/20
                      text-blue-400 hover:text-blue-300 flex items-center justify-center
                      transition-all hover:scale-110 border border-blue-500/20">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </a>

            <!-- ✅ HISTORY (BARU) -->
            <button type="button" title="Riwayat Perubahan" data-id="<?= $p['id'] ?>"
                class="btnHistory h-6 w-6 rounded bg-violet-500/10 hover:bg-violet-500/20
                       text-violet-400 hover:text-violet-300 flex items-center justify-center
                       transition-all hover:scale-110 border border-violet-500/20">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </button>

            <!-- HAPUS -->
            <form id="deleteForm<?= $p['id'] ?>"
                  action="<?= base_url('inventory/delete/'.$p['id']) ?>" method="post"
                  style="display:inline">
                <button type="button"
                    onclick="confirmDelete(<?= $p['id'] ?>, '<?= htmlspecialchars(addslashes($p['nama_barang'])) ?>')"
                    title="Hapus"
                    class="h-6 w-6 rounded bg-red-500/10 hover:bg-red-500/20
                           text-red-400 hover:text-red-300 flex items-center justify-center
                           transition-all hover:scale-110 border border-red-500/20">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </form>

        </div>
    </td>
</tr>

<!-- ROW DETAIL (inline expand) -->
<tr id="detail-row-<?= $p['id'] ?>" class="hidden inv-detail-row">
    <td colspan="11" class="px-3 py-3">
        <div class="text-center inv-cell-muted text-xs flex items-center justify-center gap-2">
            <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Memuat detail...
        </div>
    </td>
</tr>

<?php endforeach ?>
<?php else: ?>
<tr>
    <td colspan="11" class="text-center py-10">
        <div class="flex flex-col items-center gap-2">
            <svg class="w-12 h-12 inv-cell-muted opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <span class="inv-cell-muted text-sm font-medium">Data tidak tersedia</span>
        </div>
    </td>
</tr>
<?php endif ?>
        </tbody>
    </table>
</div>

<!-- ================= MODAL TAMBAH ================= -->
<div id="modalTambah" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
    <div class="inv-modal w-full max-w-3xl max-h-[85vh] rounded-xl shadow-xl
                relative flex flex-col border">
        <div class="flex justify-between items-center px-4 py-3 border-b inv-modal-head shrink-0">
            <h3 class="font-semibold text-sm inv-modal-title">Tambah Perangkat</h3>
            <button id="closeModal" class="inv-modal-close p-1 rounded-lg transition-colors">✕</button>
        </div>
        <div id="modalContent" class="p-4 overflow-y-auto"></div>
    </div>
</div>

<!-- ================= MODAL HISTORY (BARU) ================= -->
<div id="modalHistory" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
    <div class="inv-modal w-full max-w-2xl max-h-[85vh] rounded-xl shadow-xl
                relative flex flex-col border">
        <div class="flex justify-between items-center px-4 py-3 border-b inv-modal-head shrink-0">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="font-semibold text-sm inv-modal-title">Riwayat Perubahan Perangkat</h3>
            </div>
            <button id="closeModalHistory" class="inv-modal-close p-1 rounded-lg transition-colors">✕</button>
        </div>
        <div id="modalHistoryContent" class="p-4 overflow-y-auto"></div>
    </div>
</div>

<!-- ================= PAGINATION ================= -->
<div class="flex justify-between items-center mt-3 text-xs">
    <div class="header-subtitle">
        Menampilkan <?= $from ?> – <?= $to ?> dari <?= $total ?> data
    </div>
    <div class="flex gap-1 items-center">
        <?php if ($current_page > 1): ?>
        <a href="<?= base_url('inventory?page='.($current_page-1)) ?>"
           class="px-2.5 py-1 inv-pag-btn rounded text-xs">«</a>
        <?php endif ?>
        <?php foreach ($pages as $p): ?>
        <a href="<?= base_url('inventory?page='.$p) ?>"
           class="px-2.5 py-1 rounded text-xs <?= $p == $current_page
               ? 'bg-blue-600 text-white'
               : 'inv-pag-btn' ?>">
            <?= $p ?>
        </a>
        <?php endforeach ?>
        <?php if ($current_page < $total_pages): ?>
        <a href="<?= base_url('inventory?page='.($current_page+1)) ?>"
           class="px-2.5 py-1 inv-pag-btn rounded text-xs">»</a>
        <?php endif ?>
    </div>
</div>

<!-- ================= STYLES ================= -->
<style>
/* ── DARK (default) ── */
.inv-card        { background: var(--bg-card, #1e293b); border-color: rgba(51,65,85,0.5); }
.inv-thead       { background: rgba(15,23,42,0.92); border-bottom: 1px solid rgba(51,65,85,0.5); }
.inv-th          { color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing:.03em; }
.inv-row:hover   { background: rgba(51,65,85,0.35); }
.inv-border      { border-color: rgba(51,65,85,0.35); }
.inv-cell        { color: #e2e8f0; }
.inv-cell-muted  { color: #64748b; }
.inv-filter-label{ color: #94a3b8; }
.inv-input       { background: rgba(15,23,42,0.8); border-color: rgba(51,65,85,0.5); color: #f1f5f9; }
.inv-input::placeholder { color: #475569; }
.inv-input-icon  { color: #64748b; }
.inv-divider     { border-color: rgba(51,65,85,0.4); }
.inv-btn-reset   { background: #374151; }
.inv-btn-reset:hover { background: #4b5563; }
.inv-pag-btn     { background: rgba(30,41,59,0.8); color: #94a3b8; }
.inv-pag-btn:hover { background: rgba(51,65,85,0.8); color: #f1f5f9; }
.inv-detail-row  { background: rgba(15,23,42,0.6); }
.inv-modal       { background: #1e293b; }
.inv-modal-head  { background: rgba(15,23,42,0.9); border-color: rgba(51,65,85,0.5); }
.inv-modal-title { color: #f1f5f9; }
.inv-modal-close { color: #94a3b8; }
.inv-modal-close:hover { color: #f1f5f9; background: rgba(51,65,85,0.6); }

/* ── LIGHT ── */
html.light .inv-card        { background: #ffffff; border-color: #e2e8f0; }
html.light .inv-thead       { background: #f8fafc !important; border-bottom-color: #e2e8f0 !important; }
html.light .inv-th          { color: #475569 !important; }
html.light .inv-row:hover   { background: #f1f5f9 !important; }
html.light .inv-border      { border-color: #e2e8f0 !important; }
html.light .inv-cell        { color: #1e293b !important; }
html.light .inv-cell-muted  { color: #94a3b8 !important; }
html.light .inv-filter-label{ color: #64748b !important; }
html.light .inv-input {
    background: #f8fafc !important;
    border-color: #cbd5e1 !important;
    color: #0f172a !important;
}
html.light .inv-input::placeholder { color: #94a3b8 !important; }
html.light .inv-input-icon  { color: #94a3b8 !important; }
html.light .inv-divider     { border-color: #e2e8f0 !important; }
html.light .inv-btn-reset   { background: #e2e8f0 !important; color: #334155 !important; }
html.light .inv-btn-reset:hover { background: #cbd5e1 !important; }
html.light .inv-pag-btn     { background: #ffffff !important; color: #475569 !important; border: 1px solid #e2e8f0; }
html.light .inv-pag-btn:hover { background: #f1f5f9 !important; color: #0f172a !important; }
html.light .inv-detail-row  { background: #f8fafc !important; }
html.light .inv-modal       { background: #ffffff !important; }
html.light .inv-modal-head  { background: #f1f5f9 !important; border-color: #e2e8f0 !important; }
html.light .inv-modal-title { color: #0f172a !important; }
html.light .inv-modal-close { color: #64748b !important; }
html.light .inv-modal-close:hover { color: #0f172a !important; background: #e2e8f0 !important; }

/* swal */
.swal2-popup {
    background-color: var(--bg-card) !important;
    border: 2px solid var(--border-color) !important;
    color: var(--text-primary) !important;
}
html.light .swal2-popup {
    background-color: #ffffff !important;
    border-color: #e2e8f0 !important;
}

@keyframes spin { to { transform: rotate(360deg); } }
.animate-spin { animation: spin 1s linear infinite; }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function getSwalColors() {
    const isLight = document.documentElement.classList.contains('light');
    return { background: isLight ? '#ffffff' : '#1a1f2e', color: isLight ? '#0f172a' : '#f3f4f6' };
}

function confirmDelete(id, namaBarang) {
    const nama   = namaBarang.replace(/&quot;/g,'"').replace(/&#039;/g,"'").replace(/&amp;/g,'&');
    const colors = getSwalColors();
    Swal.fire({
        title: '⚠️ Konfirmasi Hapus',
        html: `Anda yakin ingin menghapus perangkat:<br><br>"<b>${nama}</b>"?`,
        icon: 'warning', showCancelButton: true,
        confirmButtonText: '🗑️ Ya, Hapus!', cancelButtonText: '❌ Batalkan',
        reverseButtons: true, allowOutsideClick: false, ...colors
    }).then(r => {
        if (r.isConfirmed) {
            Swal.fire({ title: 'Menghapus...', allowOutsideClick: false,
                didOpen: () => Swal.showLoading(), ...colors });
            document.getElementById('deleteForm' + id).submit();
        }
    });
}

window.addEventListener('load', function() {
    const colors = getSwalColors();
    <?php if($this->session->flashdata('success')): ?>
    Swal.fire({ title:'✅ Berhasil!', text:'<?= $this->session->flashdata('success') ?>',
        icon:'success', confirmButtonText:'OK', confirmButtonColor:'#10b981', ...colors });
    <?php endif; ?>
    <?php if($this->session->flashdata('error')): ?>
    Swal.fire({ title:'❌ Error!', text:'<?= $this->session->flashdata('error') ?>',
        icon:'error', confirmButtonText:'OK', ...colors });
    <?php endif; ?>
});

// ── MODAL TAMBAH ──
const modal        = document.getElementById('modalTambah');
const modalContent = document.getElementById('modalContent');
const btnTambah    = document.getElementById('btnTambah');
const closeModalBtn= document.getElementById('closeModal');

/* ── Searchable Pegawai Dropdown (Create Modal) ── */
let _pegawaiList_create = [];
let _pegawaiLoaded_create = false;

async function loadPegawai_create() {
    if (_pegawaiLoaded_create) return;
    try {
        const res = await fetch('<?= base_url('inventory/get_pegawai_list') ?>');
        _pegawaiList_create = await res.json();
        _pegawaiLoaded_create = true;
    } catch(e) { _pegawaiList_create = []; }
}

function renderDropdown_create(list) {
    const dd = document.getElementById('pegawaiDropdown_create');
    if (!dd) return;
    const isLight = document.documentElement.classList.contains('light');
    const clr   = isLight ? '#0f172a' : '#f1f5f9';
    const muted = '#94a3b8';
    const sep   = isLight ? '#e2e8f0' : '#374151';
    const bg    = isLight ? '#f8fafc' : '#1e293b';

    if (!list.length) {
        dd.innerHTML = `<div style="padding:8px 12px;font-size:11px;color:${muted};">Tidak ada hasil</div>`;
        return;
    }
    dd.innerHTML =
        `<div style="padding:5px 12px;font-size:10px;color:${muted};border-bottom:1px solid ${sep};">Pilih pegawai</div>`
        + `<div style="padding:7px 12px;font-size:11px;cursor:pointer;color:#f87171;background:${bg};"
               onmousedown="selectPegawai_create('','')">✕ Kosongkan</div>`
        + list.map(p =>
            `<div style="padding:7px 12px;font-size:11px;cursor:pointer;color:${clr};
                          background:${bg};border-bottom:1px solid ${sep};"
                  onmousedown="selectPegawai_create('${p.nama.replace(/'/g,"\\'")}','${p.nama.replace(/'/g,"\\'")}')">
                <strong>${p.nama}</strong>
                <span style="font-size:10px;color:${muted};display:block;">${p.jabatan||''}</span>
             </div>`
          ).join('');
}

function filterPegawai_create(q) {
    const dd = document.getElementById('pegawaiDropdown_create');
    if (!dd) return;
    dd.classList.remove('hidden');
    const filtered = q.trim()
        ? _pegawaiList_create.filter(p => p.nama.toLowerCase().includes(q.toLowerCase()))
        : _pegawaiList_create;
    renderDropdown_create(filtered);
}

function showDropdown_create() {
    loadPegawai_create().then(() => {
        const inp = document.getElementById('pegawaiSearch_create');
        if (inp) filterPegawai_create(inp.value);
    });
}

function selectPegawai_create(value, label) {
    const inp = document.getElementById('pegawaiSearch_create');
    const val = document.getElementById('pegawaiValue_create');
    const dd  = document.getElementById('pegawaiDropdown_create');
    if (inp) inp.value = label;
    if (val) val.value = value;
    if (dd)  dd.classList.add('hidden');
}

function loadDropdownData() {
    const namaBarangSelect = document.getElementById('nama_barang');
    const merkSelect       = document.getElementById('merk');
    const lokasiSelect     = document.getElementById('lokasi');
    if (!namaBarangSelect) { setTimeout(loadDropdownData, 200); return; }

    fetch('<?= base_url('inventory/get_master_barang') ?>')
        .then(r=>r.json()).then(data=>{
            namaBarangSelect.innerHTML='<option value="">Pilih Nama Barang</option>';
            data.forEach(item=>{
                const o=document.createElement('option');
                o.value=item.id; o.textContent=item.nama_barang;
                o.setAttribute('data-nama',item.nama_barang);
                namaBarangSelect.appendChild(o);
            });
        });

    fetch('<?= base_url('inventory/get_master_lokasi') ?>')
        .then(r=>r.json()).then(data=>{
            lokasiSelect.innerHTML='<option value="">Pilih Lokasi</option>';
            data.forEach(item=>{
                const o=document.createElement('option');
                o.value=item.id;
                o.textContent=`${item.kode_lokasi} - ${item.nama_lokasi}`;
                o.setAttribute('data-nama',item.nama_lokasi);
                lokasiSelect.appendChild(o);
            });
        });

    namaBarangSelect.addEventListener('change',function(){
        const barangId=this.value;
        document.getElementById('hidden_nama_barang').value=this.options[this.selectedIndex].getAttribute('data-nama')||'';
        merkSelect.innerHTML='<option value="">Pilih Merk</option>';
        if(barangId){
            fetch('<?= base_url('inventory/get_master_merk/') ?>'+barangId)
                .then(r=>r.json()).then(merkData=>{
                    merkData.forEach(item=>{
                        const o=document.createElement('option');
                        o.value=item.nama_merk; o.textContent=item.nama_merk;
                        merkSelect.appendChild(o);
                    });
                });
        }
    });

    lokasiSelect.addEventListener('change',function(){
        document.getElementById('hidden_lokasi').value=this.options[this.selectedIndex].getAttribute('data-nama')||'';
    });
}

function setupFotoHandlers() {
    const fotoInput=document.getElementById('foto');
    if(!fotoInput){setTimeout(setupFotoHandlers,200);return;}
    fotoInput.addEventListener('change',function(event){
        const file=event.target.files[0]; if(!file) return;
        const colors=getSwalColors();
        if(file.size>2097152){
            Swal.fire({icon:'error',title:'File Terlalu Besar',text:'Ukuran file maksimal 2MB',confirmButtonColor:'#dc2626',...colors});
            event.target.value=''; return;
        }
        if(!['image/jpeg','image/jpg','image/png','image/gif'].includes(file.type)){
            Swal.fire({icon:'error',title:'Format Tidak Valid',text:'Hanya JPG, PNG, GIF yang diperbolehkan',confirmButtonColor:'#dc2626',...colors});
            event.target.value=''; return;
        }
        const reader=new FileReader();
        reader.onload=e=>{
            document.getElementById('foto-preview-create').src=e.target.result;
            document.getElementById('preview-container-create').classList.remove('hidden');
            document.getElementById('upload-label-create').classList.add('hidden');
        };
        reader.readAsDataURL(file);
    });
    const btnRemove=document.getElementById('btnRemoveFoto');
    if(btnRemove) btnRemove.addEventListener('click',function(){
        document.getElementById('foto').value='';
        document.getElementById('preview-container-create').classList.add('hidden');
        document.getElementById('upload-label-create').classList.remove('hidden');
    });
}

function setupBatalButton(){
    const btn=document.getElementById('btnBatalModal');
    if(btn){btn.addEventListener('click',closeModal);}
    else{setTimeout(setupBatalButton,200);}
}

function setupFormSubmit(){
    const form=document.getElementById('formTambah');
    if(!form){setTimeout(setupFormSubmit,200);return;}
    form.addEventListener('submit',function(e){
        e.preventDefault();
        const colors=getSwalColors();
        const hiddenNama  =document.getElementById('hidden_nama_barang');
        const hiddenLokasi=document.getElementById('hidden_lokasi');
        if(!hiddenNama||!hiddenNama.value){
            Swal.fire({icon:'warning',title:'Peringatan',text:'Silakan pilih Nama Barang',confirmButtonColor:'#f59e0b',...colors});return;
        }
        if(!hiddenLokasi||!hiddenLokasi.value){
            Swal.fire({icon:'warning',title:'Peringatan',text:'Silakan pilih Lokasi',confirmButtonColor:'#f59e0b',...colors});return;
        }
        const required=['jenis_bmn','kode_satker','nama_satker','kode_barang','nup'];
        for(const field of required){
            const input=form.querySelector(`[name="${field}"]`);
            if(!input||!input.value.trim()){
                Swal.fire({icon:'warning',title:'Peringatan',text:`Field ${field.replace(/_/g,' ')} wajib diisi`,confirmButtonColor:'#f59e0b',...colors});return;
            }
        }
        Swal.fire({title:'Menyimpan Data...',html:'Mohon tunggu',allowOutsideClick:false,allowEscapeKey:false,didOpen:()=>Swal.showLoading(),...colors});
        fetch('<?= base_url('inventory/store') ?>',{
            method:'POST',body:new FormData(form),headers:{'X-Requested-With':'XMLHttpRequest'}
        }).then(r=>r.text()).then(text=>{
            let data;
            try{data=JSON.parse(text);}catch(e){throw new Error('Invalid JSON');}
            Swal.close();
            if(data.status==='success'){
                Swal.fire({icon:'success',title:'Berhasil!',text:data.message,confirmButtonColor:'#2563eb',...colors})
                    .then(()=>window.location.reload());
            }else if(data.status==='duplicate'){
                Swal.fire({icon:'warning',title:'Data Duplikat',text:data.message,confirmButtonColor:'#f59e0b',...colors});
            }else{
                Swal.fire({icon:'error',title:'Gagal',text:data.message||'Terjadi kesalahan',confirmButtonColor:'#dc2626',...colors});
            }
        }).catch(()=>{
            Swal.close();
            Swal.fire({icon:'error',title:'Error',text:'Terjadi kesalahan koneksi',confirmButtonColor:'#dc2626',...colors});
        });
    });
}

btnTambah.addEventListener('click',()=>{
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    modalContent.innerHTML='<div class="text-center py-10 inv-cell-muted text-sm">Memuat form...</div>';
    fetch('<?= base_url("inventory/create_ajax") ?>')
        .then(r=>r.text())
        .then(html=>{
            modalContent.innerHTML=html;
            setTimeout(()=>{
                loadDropdownData();setupFotoHandlers();setupFormSubmit();setupBatalButton();
                const inp = document.getElementById('pegawaiSearch_create');
                const val = document.getElementById('pegawaiValue_create');
                const dd  = document.getElementById('pegawaiDropdown_create');
                if (inp) inp.value = '';
                if (val) val.value = '';
                if (dd)  dd.classList.add('hidden');
            },100);
        })
        .catch(()=>{modalContent.innerHTML='<div class="text-red-400 text-center py-6">Gagal memuat form</div>';});
});

closeModalBtn.addEventListener('click',closeModal);
modal.addEventListener('click',e=>{if(e.target===modal)closeModal();});

document.addEventListener('click', function(e) {
    const w = document.getElementById('pegawaiWrapper_create');
    if (w && !w.contains(e.target)) {
        const dd = document.getElementById('pegawaiDropdown_create');
        if (dd) dd.classList.add('hidden');
    }
});

function closeModal(){
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    modalContent.innerHTML='';
}

// ── DETAIL INLINE ──
document.addEventListener('click',function(e){
    const closeBtn=e.target.closest('.closeDetail');
    if(closeBtn){
        const dr=document.getElementById(closeBtn.dataset.rowId);
        if(dr){dr.classList.add('hidden');}
        return;
    }
    const btn=e.target.closest('.btnDetail');
    if(!btn)return;
    const id =btn.dataset.id;
    const row=document.getElementById('detail-row-'+id);

    document.querySelectorAll('[id^="detail-row-"]').forEach(el=>{if(el!==row)el.classList.add('hidden');});

    if(!row.classList.contains('hidden')){row.classList.add('hidden');return;}
    row.classList.remove('hidden');

    if(!row.dataset.loaded){
        fetch('<?= base_url("inventory/detail_ajax/") ?>'+id)
            .then(r=>r.text())
            .then(html=>{row.querySelector('td').innerHTML=html;row.dataset.loaded='true';})
            .catch(()=>{row.querySelector('td').innerHTML='<div class="text-red-400 text-xs p-2">Gagal memuat detail</div>';});
    }
});

// ── MODAL HISTORY ──
(function () {
    const modalH  = document.getElementById('modalHistory');
    const contentH= document.getElementById('modalHistoryContent');
    const closeBtnH= document.getElementById('closeModalHistory');

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btnHistory');
        if (!btn) return;

        const id = btn.dataset.id;

        modalH.classList.remove('hidden');
        modalH.classList.add('flex');
        contentH.innerHTML = `
            <div class="text-center py-10 inv-cell-muted text-sm flex flex-col items-center gap-3">
                <svg class="w-6 h-6 animate-spin text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Memuat riwayat perubahan...
            </div>`;

        fetch('<?= base_url("inventory/history_ajax/") ?>' + id)
            .then(r => r.text())
            .then(html => { contentH.innerHTML = html; })
            .catch(() => {
                contentH.innerHTML = '<div class="text-red-400 text-center py-6 text-sm">Gagal memuat riwayat perubahan</div>';
            });
    });

    function closeHistory() {
        modalH.classList.add('hidden');
        modalH.classList.remove('flex');
        contentH.innerHTML = '';
    }

    closeBtnH.addEventListener('click', closeHistory);
    modalH.addEventListener('click', function (e) {
        if (e.target === modalH) closeHistory();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !modalH.classList.contains('hidden')) closeHistory();
    });
})();
</script>