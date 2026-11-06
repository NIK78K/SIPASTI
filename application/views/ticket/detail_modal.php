<?php
$ticket = $ticket ?? [];

// Deteksi tiket persediaan: slug/nama sub kategori mengandung 'persediaan'
// atau jenis_kerusakan mengandung marker [PERMINTAAN BARANG PERSEDIAAN].
$jk_deteksi = $ticket['jenis_kerusakan'] ?? '';
$sub_nama = '';
$sub_slug = '';
if (!empty($ticket['sub_kategori_id']) && isset($this->db)) {
    $has_slug = $this->db->field_exists('slug', 'master_sub_kategori');
    $this->db->select('nama_sub_kategori' . ($has_slug ? ', slug' : ''))
        ->where('id', (int)$ticket['sub_kategori_id']);
    $sub_row = $this->db->get('master_sub_kategori')->row_array();
    if (!empty($sub_row)) {
        $sub_nama = $sub_row['nama_sub_kategori'] ?? '';
        $sub_slug = $sub_row['slug'] ?? '';
    }
}
$is_persediaan = (stripos($sub_nama, 'persediaan') !== false)
    || (stripos($sub_slug, 'persediaan') !== false)
    || (strpos($jk_deteksi, '[PERMINTAAN BARANG PERSEDIAAN]') !== false);
$is_pengajuan = (stripos($sub_nama, 'pengajuan') !== false)
    || (stripos($sub_slug, 'pengajuan') !== false)
    || (strpos($jk_deteksi, '[PENGAJUAN BARANG PERSEDIAAN]') !== false);

$statusLabel = [
    'Menunggu'    => 'Menunggu',
    'On Progress' => 'Diproses',
    'Selesai'     => 'Selesai',
    'Dibatalkan'  => 'Dibatalkan'
];

$statusClass = [
    'Menunggu'    => 'bg-yellow-500/15 text-yellow-400 ring-1 ring-yellow-500/30',
    'On Progress' => 'bg-cyan-500/15 text-cyan-400 ring-1 ring-cyan-500/30',
    'Selesai'     => 'bg-green-500/15 text-green-400 ring-1 ring-green-500/30',
    'Dibatalkan'  => 'bg-red-500/15 text-red-400 ring-1 ring-red-500/30'
];

$status = $ticket['status'] ?? 'Menunggu';
?>

<!-- JUDUL -->
<h2 class="text-lg font-semibold dtck-text mb-1">
    Detail Tiket
</h2>
<p class="text-sm font-mono dtck-muted mb-5">
    <?= $ticket['kode_tiket'] ?? '-' ?>
</p>

<!-- GRID INFO -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">

    <div>
        <div class="text-xs dtck-label mb-1">Pelapor</div>
        <div class="dtck-text"><?= $ticket['pelapor'] ?? '-' ?></div>
    </div>

    <div>
        <div class="text-xs dtck-label mb-1">Teknisi</div>
        <div class="dtck-text"><?= $ticket['nama_teknisi'] ?? '-' ?></div>
    </div>

    <div>
        <div class="text-xs dtck-label mb-1">Perangkat</div>
        <div class="dtck-text"><?= $ticket['nama_perangkat'] ?? '-' ?></div>
    </div>

    <div>
        <div class="text-xs dtck-label mb-1">Status</div>
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                     <?= $statusClass[$status] ?? 'bg-gray-500/20 text-gray-400' ?>">
            <?= $statusLabel[$status] ?? $status ?>
        </span>
    </div>

    <div>
        <div class="text-xs dtck-label mb-1">Tanggal Dibuat</div>
        <div class="dtck-secondary">
            <?= !empty($ticket['created_at'])
                ? date('d M Y • H:i', strtotime($ticket['created_at']))
                : '-' ?>
        </div>
    </div>

</div>

<hr class="my-5 dtck-divider">

<!-- DESKRIPSI -->
<div class="mb-5">
    <div class="text-xs dtck-label mb-2">Deskripsi / Kebutuhan</div>
    <div class="dtck-text text-sm leading-relaxed">
        <?php 
            $jk_source = (string) ($ticket['jenis_kerusakan'] ?? '-');
            if ($is_pengajuan) {
                $jk_lines = [];
                foreach (explode("\n", $jk_source) as $jk_line) {
                    $jk_line = trim($jk_line);
                    if ($jk_line === '' || strpos($jk_line, '[PENGAJUAN BARANG PERSEDIAAN]') !== false
                        || strpos($jk_line, '[pengajuan_items_json:') === 0 || strpos($jk_line, '- ') === 0) {
                        continue;
                    }
                    $jk_lines[] = $jk_line;
                }
                $jk_clean = implode("\n", $jk_lines);
            } else {
                $jk_clean = (string) preg_replace('/\[pengajuan_items_json:[^\]]*\]/s', '', $jk_source);
            }
            $jk_html = htmlspecialchars(trim($jk_clean));
            $jk_html = preg_replace('/\[img:([a-zA-Z0-9_\-\.]+)\]/', '<a href="' . base_url('uploads/persediaan/$1') . '" target="_blank" class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-blue-500/15 text-blue-400 border border-blue-500/25 text-xs hover:bg-blue-500/25 transition">🖼️ Lihat Foto</a>', $jk_html);
            echo nl2br($jk_html);
        ?>
    </div>
</div>

<?php if (!$is_persediaan): ?>
<!-- FOTO -->
<div>
    <div class="text-xs dtck-label mb-2">Foto Pendukung</div>
    <?php if (!empty($ticket['foto'])): ?>
        <div class="dtck-photo-wrap rounded-lg border p-3">
            <img src="<?= base_url('uploads/tiket/'.$ticket['foto']) ?>"
                 class="rounded-md max-h-72 w-full object-contain"
                 alt="Foto Tiket">
        </div>
    <?php else: ?>
        <div class="text-sm italic dtck-muted">Tidak ada foto yang diunggah</div>
    <?php endif ?>
</div>
<?php endif ?>

<?php
// Items persediaan dibaca langsung di view (fragment dimuat via AJAX innerHTML,
// jadi sheet cetak di-render server-side agar selalu terisi walau JS gagal).
$persediaan_items_ticket = [];
if (!$is_pengajuan && $is_persediaan && isset($this->db) && $this->db->table_exists('ticket_persediaan_items')) {
    $hasAcc = $this->db->field_exists('jumlah_disetujui', 'ticket_persediaan_items');
    $hasKet = $this->db->field_exists('keterangan', 'ticket_persediaan_items');
    $persediaan_items_ticket = $this->db
        ->select('i.jumlah' . ($hasAcc ? ', i.jumlah_disetujui' : '') . ($hasKet ? ', i.keterangan' : '') . ', i.nama_barang_snapshot, b.nama_barang, b.kode_barang, i.satuan_snapshot, b.satuan')
        ->from('ticket_persediaan_items i')
        ->join('master_barang_persediaan b', 'b.id = i.barang_id', 'left')
        ->where('i.ticket_id', (int)($ticket['id'] ?? 0))
        ->order_by('i.id', 'ASC')
        ->get()
        ->result_array();
}
if (empty($persediaan_items_ticket) && $is_persediaan) {
    $jk_items_json = [];
    if ($is_pengajuan && preg_match('/\[pengajuan_items_json:\s*(.+?)\]/s', $ticket['jenis_kerusakan'] ?? '', $mj)) {
        $jk_items_json = json_decode($mj[1], true);
    }
    if ($is_pengajuan && is_array($jk_items_json)) {
        foreach ($jk_items_json as $item) {
            $kode = !empty($item['kode']) ? $item['kode'] : (!empty($item['kode_barang']) ? $item['kode_barang'] : '');
            if ((empty($kode) || $kode === '-') && !empty($item['nama']) && isset($this->db)) {
                $mb = $this->db->select('kode_barang')->where('LOWER(TRIM(nama_barang))', strtolower(trim($item['nama'])))->get('master_barang_persediaan')->row();
                if ($mb && !empty($mb->kode_barang)) {
                    $kode = $mb->kode_barang;
                }
            }
            $persediaan_items_ticket[] = [
                'kode_barang' => !empty($kode) ? $kode : '-',
                'nama_barang_snapshot' => $item['nama'] ?? '-',
                'nama_barang' => $item['nama'] ?? '-',
                'kategori_nama' => $item['kategori_nama'] ?? '-',
                'gambar' => $item['gambar'] ?? '',
                'keterangan' => $item['ket'] ?? '',
                'jumlah' => 0,
                'satuan_snapshot' => '',
                'satuan' => '',
            ];
        }
    } else {
        foreach (explode("\n", $ticket['jenis_kerusakan'] ?? '') as $pl) {
            $pl = trim($pl);
            if (strpos($pl, '- ') !== 0) continue;
            if ($is_pengajuan) {
                $raw = trim(substr($pl, 2));
                $kode = '';
                if (preg_match('/^\[([A-Za-z0-9\-]+)\]\s*(.*)$/', $raw, $kdm)) {
                    $kode = trim($kdm[1]);
                    $raw = trim($kdm[2]);
                }
                $category = '';
                $gambar = '';
                $spesifikasi = '';
                if (preg_match('/\[Kategori:\s*([^\]]+)\]/i', $raw, $cm)) {
                    $category = trim($cm[1]);
                    $raw = trim(preg_replace('/\s*\[Kategori:[^\]]+\]/i', '', $raw));
                }
                if (preg_match('/\[img:\s*([^\]]+)\]/i', $raw, $im)) {
                    $gambar = trim($im[1]);
                    $raw = trim(preg_replace('/\s*\[img:[^\]]+\]/i', '', $raw));
                }
                if (preg_match('/^(.*?)\s*\(([^()]*)\)\s*$/', $raw, $sm)) {
                    $raw = trim($sm[1]);
                    $spesifikasi = trim($sm[2]);
                }
                if ($raw !== '') {
                    if (empty($kode) && isset($this->db)) {
                        $mb = $this->db->select('kode_barang')->where('LOWER(TRIM(nama_barang))', strtolower(trim($raw)))->get('master_barang_persediaan')->row();
                        if ($mb && !empty($mb->kode_barang)) {
                            $kode = $mb->kode_barang;
                        }
                    }
                    $persediaan_items_ticket[] = [
                        'kode_barang' => !empty($kode) ? $kode : '-',
                        'nama_barang_snapshot' => $raw,
                        'nama_barang' => $raw,
                        'kategori_nama' => $category ?: '-',
                        'gambar' => $gambar,
                        'keterangan' => $spesifikasi,
                        'jumlah' => 0,
                        'satuan_snapshot' => '',
                        'satuan' => '',
                    ];
                }
            } elseif (preg_match('/^-\s*(.+?)\s*-\s*(.+?)\s*x\s*(\d+)\s*(.*)$/i', $pl, $mm)) {
                $persediaan_items_ticket[] = [
                    'kode_barang' => trim($mm[1]) ?: '-',
                    'nama_barang_snapshot' => trim($mm[2]), 'nama_barang' => trim($mm[2]),
                    'jumlah' => (int)$mm[3],
                    'satuan_snapshot' => trim($mm[4]), 'satuan' => trim($mm[4]),
                ];
            }
        }
    }
}
$jk_meta = $ticket['jenis_kerusakan'] ?? '';
$tckt_unit = '-'; $tckt_butuh = '-';
if (preg_match('/Unit Kerja:\s*(.+)/i', $jk_meta, $um)) $tckt_unit = trim($um[1]);
if (preg_match('/Tanggal (?:permintaan|dibutuhkan):\s*(.+)/i', $jk_meta, $bm)) $tckt_butuh = trim($bm[1]);
$tckt_faktur  = $ticket['kode_tiket'] ?? '-';
$tckt_tanggal = !empty($ticket['created_at']) ? date('d M Y • H:i', strtotime($ticket['created_at'])) : '-';
$tckt_pemohon = $ticket['pelapor'] ?? '-';
$tckt_teknisi = $ticket['nama_teknisi'] ?? '-';
// Bentuk normal untuk sheet cetak + fallback JSON (jumlah sudah "5 pcs").
$tckt_print_items = [];
foreach ($persediaan_items_ticket as $it) {
    $sat = $it['satuan_snapshot'] ?? ($it['satuan'] ?? '');
    $qty = (int)($it['jumlah'] ?? 0);
    $acc = (array_key_exists('jumlah_disetujui', $it) && $it['jumlah_disetujui'] !== null && $it['jumlah_disetujui'] !== '')
        ? (int)$it['jumlah_disetujui'] : null;
    $tckt_print_items[] = [
        'kode'       => ($it['kode_barang'] ?? '') !== '' ? $it['kode_barang'] : '-',
        'nama'       => ($it['nama_barang_snapshot'] ?? '') !== '' ? $it['nama_barang_snapshot'] : ($it['nama_barang'] ?? '-'),
        'kategori'   => ($it['kategori_nama'] ?? '') !== '' ? $it['kategori_nama'] : '-',
        'gambar'     => ($it['gambar'] ?? '') !== '' ? base_url('uploads/persediaan/' . $it['gambar']) : '',
        'gambar_nama'=> ($it['gambar'] ?? '') !== '' ? $it['gambar'] : '',
        'spesifikasi'=> ($it['keterangan'] ?? '') !== '' ? $it['keterangan'] : '-',
        'jumlah'     => $qty > 0 ? $qty . ($sat !== '' ? ' ' . $sat : '') : '',
        'disetujui'  => $acc !== null ? $acc . ($sat !== '' ? ' ' . $sat : '') : '-',
        'keterangan' => ($it['keterangan'] ?? '') !== '' ? $it['keterangan'] : '-',
    ];
}
?>

<?php if ($is_persediaan): ?>
<!-- DAFTAR BARANG PERSEDIAAN -->
<div class="mt-5">
    <div class="text-xs dtck-label mb-2">Daftar Barang Persediaan</div>
    <div class="dtck-photo-wrap rounded-lg border p-3 overflow-x-auto">
        <table class="w-full text-sm min-w-[700px]">
            <thead>
                <tr class="dtck-label text-[11px] uppercase tracking-wider text-left">
                    <th class="pb-2 pr-3 font-semibold w-10 text-center">No</th>
                    <th class="pb-2 pr-3 font-semibold">Kode</th>
                    <th class="pb-2 pr-3 font-semibold">Nama Barang</th>
                    <?php if ($is_pengajuan): ?>
                    <th class="pb-2 pr-3 font-semibold">Kategori</th>
                    <th class="pb-2 pr-3 font-semibold">Gambar</th>
                    <th class="pb-2 pr-3 font-semibold">Spesifikasi/Keterangan</th>
                    <?php else: ?>
                    <th class="pb-2 font-semibold text-right">Diminta</th>
                    <th class="pb-2 font-semibold text-right">Disetujui</th>
                    <th class="pb-2 pl-3 font-semibold text-left">Keterangan</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody id="tckt-persediaan-items" data-pengajuan="<?= $is_pengajuan ? '1' : '0' ?>">
                <?php if (empty($tckt_print_items)): ?>
                <tr><td colspan="<?= $is_pengajuan ? 6 : 6 ?>" class="text-xs text-center py-4 dtck-muted">Data barang tidak tersedia</td></tr>
                <?php else: foreach ($tckt_print_items as $idx => $pi): ?>
                <tr>
                    <td class="py-2 pr-3 text-center text-xs dtck-muted"><?= $idx + 1 ?></td>
                    <td class="py-2 pr-3 font-mono text-xs dtck-text"><?= htmlspecialchars($pi['kode']) ?></td>
                    <td class="py-2 pr-3 text-xs dtck-text"><?= htmlspecialchars($pi['nama']) ?></td>
                    <?php if ($is_pengajuan): ?>
                    <td class="py-2 pr-3 text-xs dtck-text"><?= htmlspecialchars($pi['kategori']) ?></td>
                    <td class="py-2 pr-3 text-xs">
                        <?php if ($pi['gambar']): ?>
                            <a href="<?= htmlspecialchars($pi['gambar']) ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-blue-400 hover:text-blue-300">
                                <img src="<?= htmlspecialchars($pi['gambar']) ?>" alt="Gambar <?= htmlspecialchars($pi['nama']) ?>" class="w-12 h-12 rounded object-cover border border-slate-600">
                                <span>Lihat</span>
                            </a>
                        <?php else: ?>
                            <span class="dtck-muted">-</span>
                        <?php endif; ?>
                    </td>
                    <td class="py-2 pr-3 text-xs dtck-muted"><?= htmlspecialchars($pi['spesifikasi']) ?></td>
                    <?php else: ?>
                    <td class="py-2 text-right text-xs dtck-text"><?= htmlspecialchars($pi['jumlah']) ?></td>
                    <td class="py-2 text-right text-xs dtck-text font-semibold"><?= htmlspecialchars($pi['disetujui']) ?></td>
                    <td class="py-2 pl-3 text-left text-xs dtck-muted"><?= htmlspecialchars($pi['keterangan']) ?></td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; endif ?>
            </tbody>
        </table>
    </div>
    <div class="flex justify-between mt-3 text-center">
        <div class="flex-1">
            <div class="text-xs dtck-label">Petugas / Teknisi</div>
            <div class="dtck-text text-xs font-semibold mt-8"><?= htmlspecialchars($tckt_teknisi ?: '-') ?></div>
        </div>
        <div class="flex-1">
            <div class="text-xs dtck-label">Pemohon</div>
            <div class="dtck-text text-xs font-semibold mt-8"><?= htmlspecialchars($tckt_pemohon ?: '-') ?></div>
        </div>
    </div>
    <?php if (!$is_pengajuan): ?>
    <button type="button" onclick="fillPersediaanPrintTicket();exportPersediaanPdfTicket()"
            class="mt-3 w-full px-4 py-2.5 rounded-xl text-sm font-semibold bg-emerald-600 hover:bg-emerald-500 text-white transition-all">
        Export PDF
    </button>
    <?php endif ?>
</div>

<?php if (!$is_pengajuan): ?>
<!-- meta on-screen dibaca fungsi cetak induk -->
<span id="tckt-faktur" class="hidden"><?= htmlspecialchars($tckt_faktur) ?></span>
<span id="tckt-tanggal" class="hidden"><?= htmlspecialchars($tckt_tanggal) ?></span>
<span id="tckt-unit" class="hidden"><?= htmlspecialchars($tckt_unit) ?></span>
<span id="tckt-butuh" class="hidden"><?= htmlspecialchars($tckt_butuh) ?></span>
<span id="tckt-pemohon" class="hidden"><?= htmlspecialchars($tckt_pemohon) ?></span>
<span id="tckt-teknisi" class="hidden"><?= htmlspecialchars($tckt_teknisi) ?></span>
<script type="application/json" id="tckt-persediaan-data"><?= json_encode($tckt_print_items, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?></script>
<!-- SHEET CETAK (server-rendered, min 6 baris) -->
<div id="pmPrintSheetTicket" aria-hidden="true">
    <div class="pm-print-kop">
        <img id="print_logo_bps_ticket" src="<?= base_url('assets/img/bps.png') ?>" alt="Logo BPS">
        <div class="pm-print-kop-text">
            <div style="font-size:14pt;font-weight:bold;text-transform:uppercase;"><i>BADAN PUSAT STATISTIK <br>PROVINSI RIAU</i></div>
            <div style="font-size:6pt;font-weight:normal;margin-top:8px;text-transform:none;">Jalan Pattimura No. 12, Pekanbaru 28131, Telepon:(0761) 23042 <br>Laman: riau.bps.go.id, Pos-el: riau@bps.go.id</div>
        </div>
    </div>
    <div class="pm-print-title">Form Persetujuan Permintaan Persediaan</div>
    <div class="pm-print-grid">
        <div><span class="label">No. Faktur</span><span class="sep">:</span><strong id="print_faktur_ticket"><?= htmlspecialchars($tckt_faktur) ?></strong></div>
        <div><span class="label">Tim Kerja</span><span class="sep">:</span><strong id="print_unit_ticket"><?= htmlspecialchars($tckt_unit) ?></strong></div>
        <div><span class="label">Tanggal</span><span class="sep">:</span><strong id="print_tanggal_ticket"><?= htmlspecialchars($tckt_tanggal) ?></strong></div>
        <div><span class="label">Tanggal Permintaan</span><span class="sep">:</span><strong id="print_butuh_ticket"><?= htmlspecialchars($tckt_butuh) ?></strong></div>
        <div><span class="label">Pemohon</span><span class="sep">:</span><strong id="print_pemohon_ticket"><?= htmlspecialchars($tckt_pemohon) ?></strong></div>
    </div>
    <table class="pm-print-table">
        <thead>
            <tr>
                <th>NO</th>
                <th>Kode barang</th>
                <th>NAMA BARANG</th>
                <th>Diminta</th>
                <th>Disetujui</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody id="print_items_ticket">
            <?php for ($i = 0; $i < max(count($tckt_print_items), 6); $i++):
                $pr = $tckt_print_items[$i] ?? ['kode' => '', 'nama' => '', 'jumlah' => '', 'disetujui' => '', 'keterangan' => '']; ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($pr['kode']) ?></td>
                <td><?= htmlspecialchars($pr['nama']) ?></td>
                <td><?= htmlspecialchars($pr['jumlah']) ?></td>
                <td><?= htmlspecialchars($pr['disetujui']) ?></td>
                <td><?= htmlspecialchars($pr['keterangan']) ?></td>
            </tr>
            <?php endfor ?>
        </tbody>
    </table>
    <div class="pm-print-signature">
        <div>
            <div>Petugas / Teknisi</div>
            <div class="space"></div>
            <strong id="print_teknisi_nama_ticket"><?= htmlspecialchars($tckt_teknisi ?: '-') ?></strong>
        </div>
        <div>
            <div>Pemohon</div>
            <div class="space"></div>
            <strong id="print_pemohon_nama_ticket"><?= htmlspecialchars($tckt_pemohon ?: '-') ?></strong>
        </div>
    </div>
</div>
<?php endif ?>
<?php endif ?>

<!-- ================= STYLES ================= -->
<style>
/* ── DARK default ── */
.dtck-text      { color: #e2e8f0; }
.dtck-secondary { color: #cbd5e1; }
.dtck-label     { color: #64748b; }
.dtck-muted     { color: #475569; }
.dtck-divider   { border-color: rgba(255,255,255,0.08); }
.dtck-photo-wrap{ background: rgba(0,0,0,0.3); border-color: rgba(255,255,255,0.08); }

/* ── LIGHT overrides ── */
html.light .dtck-text      { color: #0f172a !important; }
html.light .dtck-secondary { color: #334155 !important; }
html.light .dtck-label     { color: #64748b !important; }
html.light .dtck-muted     { color: #94a3b8 !important; }
html.light .dtck-divider   { border-color: #e2e8f0 !important; }
html.light .dtck-photo-wrap{ background: #f8fafc !important; border-color: #e2e8f0 !important; }
</style>