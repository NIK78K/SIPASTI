<div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2 text-[12px] prg-text">

    <div>
        <div class="text-[11px] prg-label">Nama Satker</div>
        <div class="font-medium"><?= $p['nama_satker'] ?></div>
    </div>

    <div>
        <div class="text-[11px] prg-label">Merk</div>
        <div><?= $p['merk'] ?: '-' ?></div>
    </div>

    <div>
        <div class="text-[11px] prg-label">Lokasi</div>
        <div><?= $p['lokasi'] ?: '-' ?></div>
    </div>

    <div>
        <div class="text-[11px] prg-label">Ruang</div>
        <div><?= $p['ruang'] ?: '-' ?></div>
    </div>

    <div>
        <div class="text-[11px] prg-label">Serial Number</div>
        <div class="font-mono break-all"><?= $p['serial_number'] ?: '-' ?></div>
    </div>

    <div>
        <div class="text-[11px] prg-label">MAC Address</div>
        <div class="font-mono break-all"><?= $p['mac_address'] ?: '-' ?></div>
    </div>

    <div>
        <div class="text-[11px] prg-label">Pengguna</div>
        <div><?= $p['pengguna'] ?: '-' ?></div>
    </div>

    <div>
        <div class="text-[11px] prg-label">Ruangan</div>
        <div><?= $p['ruangan'] ?: '-' ?></div>
    </div>

    <div>
        <div class="text-[11px] prg-label">Tanggal Hapus</div>
        <div><?= date('d-m-Y', strtotime($p['tanggal_hapus'])) ?></div>
    </div>

    <div>
        <div class="text-[11px] prg-label">Dipindahkan</div>
        <div><?= date('d-m-Y H:i', strtotime($p['dipindahkan_pada'])) ?></div>
    </div>

    <div>
        <div class="text-[11px] prg-label">Nilai</div>
        <div class="font-mono prg-nilai">
            Rp <?= number_format($p['nilai_perolehan'],0,',','.') ?>
        </div>
    </div>

    <div class="md:col-span-2 pt-1 prg-divider border-t">
        <div class="text-[11px] prg-label">Keterangan</div>
        <div class="whitespace-pre-line"><?= $p['keterangan'] ?: '-' ?></div>
    </div>

</div>

<style>
/* ── dark default ── */
.prg-text    { color: #e2e8f0; }
.prg-label   { color: #9ca3af; }
.prg-nilai   { color: #34d399; }
.prg-divider { border-color: #374151; }

/* ── light overrides ── */
html.light .prg-text    { color: #0f172a !important; }
html.light .prg-label   { color: #64748b !important; }
html.light .prg-nilai   { color: #059669 !important; }
html.light .prg-divider { border-color: #e2e8f0 !important; }
</style>