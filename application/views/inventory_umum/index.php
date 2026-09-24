<!-- ================= JUDUL ================= -->
<h1 class="text-2xl font-bold mb-1 header-title">Inventory Umum</h1>
<p class="text-gray-400 mb-6 header-subtitle">Manajemen inventaris umum</p>

<!-- ================= KARTU KONDISI ================= -->
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="border-l-4 border-green-500 theme-card stat-card p-4 rounded-lg hover:shadow-lg transition-all">
        <div class="header-subtitle text-sm mb-1">Baik</div>
        <div class="header-title text-2xl font-bold"><?= $baik ?></div>
    </div>
    <div class="border-l-4 border-yellow-500 theme-card stat-card p-4 rounded-lg hover:shadow-lg transition-all">
        <div class="header-subtitle text-sm mb-1">Rusak Ringan</div>
        <div class="header-title text-2xl font-bold"><?= $rusak_ringan ?></div>
    </div>
    <div class="border-l-4 border-red-500 theme-card stat-card p-4 rounded-lg hover:shadow-lg transition-all">
        <div class="header-subtitle text-sm mb-1">Rusak Berat</div>
        <div class="header-title text-2xl font-bold"><?= $rusak_berat ?></div>
    </div>
</div>

<!-- ================= FILTER ================= -->
<form method="get"
      class="theme-card p-4 rounded-lg border space-y-4">

    <div class="grid grid-cols-4 gap-3">
        <input type="text"
               name="keyword"
               value="<?= $this->input->get('keyword') ?>"
               placeholder="Cari nama / kode barang"
               class="px-3 py-2 bg-slate-800/80 border border-slate-700/50 rounded text-sm
                      focus:outline-none focus:ring-2 focus:ring-blue-500 transition">

        <input type="text"
               name="nup"
               value="<?= $this->input->get('nup') ?>"
               placeholder="NUP"
               class="px-3 py-2 bg-slate-800/80 border border-slate-700/50 rounded text-sm
                      focus:outline-none focus:ring-2 focus:ring-blue-500 transition">

        <div class="col-span-2 flex gap-2 justify-end">
            <button class="bg-blue-600 hover:bg-blue-500 px-4 py-2 rounded text-sm transition">
                Filter
            </button>
            <a href="<?= base_url('inventory_umum') ?>"
               class="bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded text-sm transition">
                Reset
            </a>
        </div>
    </div>

    <div class="grid grid-cols-4 gap-3 pt-3 border-t border-slate-700/50">
        <div>
            <div class="text-xs text-gray-400 mb-1">Kondisi</div>
            <select name="kondisi"
                    class="w-full px-3 py-2 bg-slate-800/80 border border-slate-700/50 rounded text-sm
                           focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                <option value="">Semua</option>
                <option value="Baik" <?= $this->input->get('kondisi')=='Baik'?'selected':'' ?>>Baik</option>
                <option value="Rusak Ringan" <?= $this->input->get('kondisi')=='Rusak Ringan'?'selected':'' ?>>Rusak Ringan</option>
                <option value="Rusak Berat" <?= $this->input->get('kondisi')=='Rusak Berat'?'selected':'' ?>>Rusak Berat</option>
            </select>
        </div>
    </div>
</form>

<!-- ================= INSIGHT ================= -->
<div class="mb-4 px-4 py-2
            theme-card
            border
            rounded-md
            text-sm text-gray-300">

    Menampilkan <b><?= $total ?></b> inventaris ·
    <span class="text-green-400"><?= $baik ?> baik</span>,
    <span class="text-yellow-400"><?= $rusak_ringan ?> rusak ringan</span>,
    <span class="text-red-400"><?= $rusak_berat ?> rusak berat</span>

</div>

<!-- ================= TABEL ================= -->
<div class="overflow-x-auto border rounded theme-card">
<table class="w-full text-[13px]">

<thead class="sticky top-0 bg-slate-900/90 text-gray-400 border-b border-slate-700/50">
<tr>
    <th class="px-3 py-2 text-center">No</th>
    <th class="px-2 py-1.5">Jenis BMN</th>
    <th class="px-2 py-1.5">Kode Satker</th>
    <th class="px-2 py-1.5">Nama Satker</th>
    <th class="px-2 py-1.5">Kode Barang</th>
    <th class="px-2 py-1.5">NUP</th>
    <th class="px-2 py-1.5">Nama Barang</th>
    <th class="px-2 py-1.5">Merk</th>
    <th class="px-2 py-1.5">Kondisi</th>
    <th class="px-3 py-2 text-center w-28">Aksi</th>
</tr>
</thead>

<tbody>
<?php if (!empty($perangkat)): ?>
<?php $no = $from; ?>
<?php foreach ($perangkat as $p): ?>

<tr class="border-b border-slate-800/50 hover:bg-slate-800/50 transition">
    <td class="px-3 py-2 text-center"><?= $no++ ?></td>
    <td class="px-2 py-1.5"><?= $p['jenis_bmn'] ?></td>
    <td class="px-2 py-1.5"><?= $p['kode_satker'] ?></td>
    <td class="px-2 py-1.5"><?= $p['nama_satker'] ?></td>
    <td class="px-2 py-1.5"><?= $p['kode_barang'] ?></td>
    <td class="px-2 py-1.5"><?= $p['nup'] ?></td>
    <td class="px-2 py-1.5"><?= $p['nama_barang'] ?></td>
    <td class="px-2 py-1.5"><?= $p['merk'] ?></td>

    <!-- KONDISI -->
    <td class="px-3 py-2 text-center">
        <?php
            $badge = match ($p['kondisi']) {
                'Baik' => 'bg-green-500/15 text-green-400 ring-1 ring-green-500/30',
                'Rusak Ringan' => 'bg-yellow-500/15 text-yellow-400 ring-1 ring-yellow-500/30',
                'Rusak Berat' => 'bg-red-500/15 text-red-400 ring-1 ring-red-500/30',
                default => 'bg-gray-500/15 text-gray-400 ring-1 ring-gray-500/30'
            };
        ?>
        <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full <?= $badge ?>">
            <?= $p['kondisi'] ?>
        </span>
    </td>

    <!-- AKSI -->
    <td class="px-3 py-2 text-center">
        <div class="inline-flex gap-1">

            <!-- DETAIL -->
            <button class="btnDetail h-8 w-8 rounded-md
                           bg-slate-700/40 hover:bg-slate-600/70
                           flex items-center justify-center transition"
                    data-id="<?= $p['id'] ?>"
                    title="Detail">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-width="2"
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-width="2"
                          d="M2.458 12C3.732 7.943 7.523 5 12 5
                             c4.478 0 8.268 2.943 9.542 7
                             -1.274 4.057-5.064 7-9.542 7
                             -4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </button>

            <!-- EDIT -->
            <a href="<?= base_url('inventory_umum/edit/'.$p['id']) ?>"
               class="h-8 w-8 rounded-md bg-blue-600/20 hover:bg-blue-600
                      flex items-center justify-center transition"
               title="Edit">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-width="2"
                          d="M11 5h2m-1-1v2M4 21l4-1 10-10-3-3L5 17l-1 4z"/>
                </svg>
            </a>

            <!-- HAPUS -->
            <form action="<?= base_url('inventory_umum/delete/'.$p['id']) ?>"
                  method="post"
                  onsubmit="return confirm('Yakin hapus data?')">
                <button class="h-8 w-8 rounded-md bg-red-600/20 hover:bg-red-600
                               flex items-center justify-center transition"
                        title="Hapus">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21
                                 H7.862a2 2 0 01-1.995-1.858L5 7"/>
                    </svg>
                </button>
            </form>

        </div>
    </td>

</tr>

<?php endforeach ?>
<?php else: ?>
<tr>
    <td colspan="10" class="text-center py-6 text-gray-500">
        Data tidak tersedia
    </td>
</tr>
<?php endif ?>
</tbody>

</table>
</div>

<!-- ================= MODAL DETAIL ================= -->
<div id="modalDetail"
     class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">

    <div class="theme-card
                w-full max-w-xl
                max-h-[75vh]
                rounded-xl shadow-xl
                flex flex-col
                border">

        <!-- HEADER -->
        <div class="flex justify-between items-center
                    px-4 py-3
                    border-b border-slate-700/50">
            <h3 class="font-semibold text-sm tracking-wide header-title">
                Detail Inventaris
            </h3>
            <button id="closeDetail"
                    class="h-7 w-7 rounded-md
                           flex items-center justify-center
                           hover:bg-gray-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-4 h-4"
                     fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- BODY -->
        <div id="modalDetailContent"
             class="p-4 overflow-y-auto text-sm">
            <div class="text-center text-gray-400 py-10">
                Memuat detail...
            </div>
        </div>

    </div>
</div>

<!-- Theme-aware styles -->
<style>
html.light tbody tr:hover {
    background: rgba(248, 250, 252, 0.6) !important;
}

html.light thead {
    background: rgba(241, 245, 249, 0.9) !important;
}

html.light thead th {
    color: var(--text-secondary) !important;
}

html.light .bg-slate-800\/80,
html.light .bg-slate-800\/50 {
    background: rgba(248, 250, 252, 0.8) !important;
}

html.light .bg-slate-700\/40 {
    background: rgba(226, 232, 240, 0.6) !important;
}

html.light .bg-slate-700\/40:hover {
    background: rgba(203, 213, 225, 0.8) !important;
}

html.light .border-slate-700\/50,
html.light .border-slate-800\/50 {
    border-color: rgba(226, 232, 240, 0.8) !important;
}

html.light input,
html.light select {
    background: rgba(255, 255, 255, 0.95) !important;
    color: var(--text-primary) !important;
    border-color: rgba(203, 213, 225, 0.8) !important;
}

html.light .text-gray-300 {
    color: var(--text-secondary) !important;
}

html.light .text-gray-400 {
    color: var(--text-muted) !important;
}

html.light .text-gray-500 {
    color: #64748b !important;
}
</style>

<script>
const modal = document.getElementById('modalDetail');
const modalContent = document.getElementById('modalDetailContent');
const closeBtn = document.getElementById('closeDetail');

document.addEventListener('click', e => {
    const btn = e.target.closest('.btnDetail');
    if (!btn) return;

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    modalContent.innerHTML =
        '<div class="text-center text-gray-400 py-10">Memuat detail...</div>';

    fetch('<?= base_url("inventory_umum/detail_ajax/") ?>' + btn.dataset.id)
        .then(r => r.text())
        .then(html => modalContent.innerHTML = html)
        .catch(() => {
            modalContent.innerHTML =
                '<div class="text-red-400 text-center py-6">Gagal memuat detail</div>';
        });
});

// ❌ HANYA BISA TUTUP VIA TOMBOL X
closeBtn.addEventListener('click', () => {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    modalContent.innerHTML = '';
});

// ❌ KLIK BACKDROP TIDAK MENUTUP
modal.addEventListener('click', e => {
    e.stopPropagation();
});
</script>