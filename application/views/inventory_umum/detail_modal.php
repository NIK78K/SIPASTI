<div class="grid grid-cols-2 gap-4 text-sm">

    <div>
        <div class="text-xs text-gray-400">Jenis BMN</div>
        <div class="font-medium"><?= $detail['jenis_bmn'] ?></div>
    </div>

    <div>
        <div class="text-xs text-gray-400">Kode Barang</div>
        <div class="font-medium"><?= $detail['kode_barang'] ?></div>
    </div>

    <div>
        <div class="text-xs text-gray-400">NUP</div>
        <div class="font-medium"><?= $detail['nup'] ?></div>
    </div>

    <div>
        <div class="text-xs text-gray-400">Nama Barang</div>
        <div class="font-medium"><?= $detail['nama_barang'] ?></div>
    </div>

    <div>
        <div class="text-xs text-gray-400">Merk</div>
        <div class="font-medium"><?= $detail['merk'] ?></div>
    </div>

    <div>
        <div class="text-xs text-gray-400">Kondisi</div>
        <?php
            $badge = match ($detail['kondisi']) {
                'Baik' => 'bg-green-500/15 text-green-400',
                'Rusak Ringan' => 'bg-yellow-500/15 text-yellow-400',
                'Rusak Berat' => 'bg-red-500/15 text-red-400',
                default => 'bg-gray-500/15 text-gray-400'
            };
        ?>
        <span class="inline-flex px-3 py-1 rounded-full text-xs <?= $badge ?>">
            <?= $detail['kondisi'] ?>
        </span>
    </div>

    <div>
        <div class="text-xs text-gray-400">Kode Satker</div>
        <div class="font-medium"><?= $detail['kode_satker'] ?></div>
    </div>

    <div>
        <div class="text-xs text-gray-400">Nama Satker</div>
        <div class="font-medium"><?= $detail['nama_satker'] ?></div>
    </div>

    <div>
        <div class="text-xs text-gray-400">Lokasi</div>
        <div class="font-medium"><?= $detail['lokasi'] ?></div>
    </div>

    <div>
        <div class="text-xs text-gray-400">Ruang</div>
        <div class="font-medium"><?= $detail['ruang'] ?></div>
    </div>

    <div>
        <div class="text-xs text-gray-400">Pengguna</div>
        <div class="font-medium"><?= $detail['pengguna'] ?></div>
    </div>

    <div>
        <div class="text-xs text-gray-400">Tanggal Perolehan</div>
        <div class="font-medium"><?= date('d M Y', strtotime($detail['tanggal_perolehan'])) ?></div>
    </div>

    <div>
        <div class="text-xs text-gray-400">Nilai Perolehan</div>
        <div class="font-medium">
            Rp <?= number_format($detail['nilai_perolehan'], 0, ',', '.') ?>
        </div>
    </div>

    <div>
        <div class="text-xs text-gray-400">Henti Guna</div>
        <div class="font-medium"><?= $detail['henti_guna'] ?></div>
    </div>

    <div>
        <div class="text-xs text-gray-400">Usul Hapus</div>
        <div class="font-medium"><?= $detail['usul_hapus'] ?></div>
    </div>

    <div class="col-span-2">
        <div class="text-xs text-gray-400">Keterangan</div>
        <div class="font-medium"><?= $detail['keterangan'] ?></div>
    </div>

</div>
