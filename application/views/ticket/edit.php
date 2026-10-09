<?php
$ticket = $ticket ?? [];

/* ── Deteksi tipe tiket ── */
$jk       = $ticket['jenis_kerusakan'] ?? '';
$katSlug  = strtolower($ticket['kategori_slug'] ?? $ticket['nama_kategori'] ?? '');
$subKatId = (int)($ticket['sub_kategori_id'] ?? 0);

$isPermintaan = $katSlug === 'permintaan';
$isGangguan   = !$isPermintaan;

$isRuangRapat = $isPermintaan && strpos($jk, '[ref:peminjaman_id=') !== false;
$isKendaraan  = $isPermintaan && strpos($jk, '[ref:peminjaman_kendaraan_id=') !== false;
$isBmn        = $isPermintaan && strpos($jk, '[ref:peminjaman_bmn_id=') !== false;

$sub_zoom_raw = $this->db->select('id')->where('perlu_jadwal', 1)
                         ->get('master_sub_kategori')->result_array();
$sub_zoom_ids = array_column($sub_zoom_raw, 'id');
$isZoom       = $isPermintaan && !$isRuangRapat && !$isKendaraan && !$isBmn
                && in_array($subKatId, $sub_zoom_ids);   
$subSlug = '';
if ($subKatId > 0) {
    $subSlug = strtolower($this->db->select('slug')->where('id', $subKatId)->get('master_sub_kategori')->row('slug') ?? '');
}
$isPersediaan = $isPermintaan && !$isRuangRapat && !$isKendaraan && !$isBmn && !$isZoom
                && (strpos($subSlug, 'persediaan') !== false || strpos($jk, '[PERMINTAAN BARANG PERSEDIAAN]') !== false);
$isPermintaanBiasa = $isPermintaan && !$isZoom && !$isRuangRapat && !$isKendaraan && !$isBmn && !$isPersediaan;

/* ── Helper: ambil nilai dari baris jenis_kerusakan ── */
function parseJkField($jk, $label) {
    preg_match('/' . preg_quote($label, '/') . '\s*(.+?)(?:\n|$)/u', $jk, $m);
    return trim($m[1] ?? '');
}

/* ── Label & aksen warna per tipe ── */
if ($isRuangRapat) {
    $tipeLabel  = '🏢 Permintaan Ruang Rapat';
    $focusColor = 'focus:ring-indigo-500';
    $accentCls  = 'text-indigo-400';
    $catatanPlh = 'Catatan persetujuan / penolakan ruang rapat...';
} elseif ($isKendaraan) {
    $tipeLabel  = '🚗 Permintaan Kendaraan Dinas';
    $focusColor = 'focus:ring-amber-500';
    $accentCls  = 'text-amber-400';
    $catatanPlh = 'Catatan persetujuan / penolakan kendaraan...';
} elseif ($isBmn) {
    $tipeLabel  = '📦 Permintaan Peminjaman BMN';
    $focusColor = 'focus:ring-purple-500';
    $accentCls  = 'text-purple-400';
    $catatanPlh = 'Catatan persetujuan / penolakan BMN...';
} elseif ($isPersediaan) {
    $tipeLabel  = '📦 Permintaan Barang Persediaan';
    $focusColor = 'focus:ring-teal-500';
    $accentCls  = 'text-teal-400';
    $catatanPlh = 'Catatan persetujuan / penolakan barang persediaan...';
} elseif ($isZoom) {
    $tipeLabel  = '📹 Permintaan Zoom / Meet';
    $focusColor = 'focus:ring-emerald-500';
    $accentCls  = 'text-emerald-400';
    $catatanPlh = 'Catatan untuk pemohon...';
} elseif ($isPermintaanBiasa) {
    $tipeLabel  = '🖥️ Permintaan Layanan IT';
    $focusColor = 'focus:ring-blue-500';
    $accentCls  = 'text-blue-400';
    $catatanPlh = 'Catatan untuk pemohon...';
} else {
    $tipeLabel  = '⚠️ Laporan Gangguan';
    $focusColor = 'focus:ring-red-500';
    $accentCls  = 'text-red-400';
    $catatanPlh = 'Catatan penanganan...';
}

/* ── Teknisi list ── */
$teknisi_list = $this->db
    ->select('tek.id, tek.status, u.name as nama')
    ->from('teknisi tek')
    ->join('users u', 'u.id = tek.user_id', 'left')
    ->order_by('u.name', 'ASC')
    ->get()->result_array();

$status = $ticket['status'] ?? 'Menunggu';

/* ── Data ruang rapat jika ada ── */
$peminjaman = $ticket['peminjaman'] ?? [];
if (empty($peminjaman) && $isRuangRapat) {
    preg_match('/\[ref:peminjaman_id=(\d+)\]/', $jk, $m);
    if (!empty($m[1])) {
        $peminjaman = $this->db->where('id', (int)$m[1])->get('peminjaman_ruang_rapat')->row_array() ?: [];
    }
}
$ruangan_list = [];
if ($isRuangRapat) {
    $ruangan_list = $this->db
        ->select('id, nama_ruangan, lokasi, kapasitas')
        ->where('status', 'Aktif')
        ->order_by('nama_ruangan', 'ASC')
        ->get('master_ruang_rapat')
        ->result_array();
}

/* ── Parse data kendaraan jika ada ── */
$pem_kend = [];
if ($isKendaraan) {
    preg_match('/\[ref:peminjaman_kendaraan_id=(\d+)\]/', $jk, $mk);
    if (!empty($mk[1])) {
        $pem_kend = $this->db->where('id', (int)$mk[1])
                              ->get('peminjaman_kendaraan')->row_array() ?: [];
    }
}

/* ── Parse data BMN jika ada ── */
$pem_bmn = [];
if ($isBmn) {
    preg_match('/\[ref:peminjaman_bmn_id=(\d+)\]/', $jk, $mb);
    if (!empty($mb[1])) {
        $pem_bmn = $this->db->where('id', (int)$mb[1])
                             ->get('peminjaman_bmn')->row_array() ?: [];
    }
}
?>

<!-- ================================================================
     EDIT TIKET ADMIN — modal komprehensif per tipe
================================================================ -->
<div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
<div class="etck-card border rounded-xl w-full max-w-2xl flex flex-col shadow-2xl"
     style="max-height:90vh;">

    <!-- ── HEADER ── -->
    <div class="px-6 py-4 border-b etck-border flex-shrink-0 flex items-start justify-between gap-4">
        <div>
            <div class="text-xs etck-muted mb-1"><?= $tipeLabel ?></div>
            <h2 class="text-base font-semibold etck-text">Edit Tiket</h2>
            <p class="text-xs font-mono <?= $accentCls ?> mt-0.5">
                <?= htmlspecialchars($ticket['kode_tiket'] ?? '-') ?>
            </p>
        </div>
        <button type="button" id="btnClose"
                class="etck-muted hover:text-red-400 transition-colors flex-shrink-0 mt-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- ── SCROLLABLE BODY ── -->
    <div class="flex-1 overflow-y-auto px-6 py-5 etck-scrollbar">
    <form id="editForm" 
      action="<?= base_url('ticket/update/'.($ticket['id'] ?? 0)) ?>" 
      class="space-y-5">

        <!-- INFO READONLY -->
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="text-xs etck-label block mb-1">Pelapor</label>
                <input type="text"
                       value="<?= htmlspecialchars($ticket['pelapor'] ?? '-') ?>"
                       readonly
                       class="etck-input-ro w-full border rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="text-xs etck-label block mb-1">Sub Kategori</label>
                <input type="text"
                       value="<?= htmlspecialchars($ticket['nama_sub_kategori'] ?? '-') ?>"
                       readonly
                       class="etck-input-ro w-full border rounded-lg px-3 py-2 text-sm">
            </div>
        </div>

        <!-- STATUS -->
        <div>
            <label class="text-xs etck-label block mb-1">
                Status Tiket <span class="text-red-500">*</span>
            </label>
            <div class="etck-sel-wrap">
                <select name="status" id="editStatus" required class="etck-sel w-full">
                    <option value="Menunggu"    <?= $status === 'Menunggu'    ? 'selected' : '' ?>>Menunggu</option>
                    <option value="On Progress" <?= $status === 'On Progress' ? 'selected' : '' ?>>Diproses</option>
                    <option value="Selesai"     <?= $status === 'Selesai'     ? 'selected' : '' ?>>Selesai</option>
                    <option value="Dibatalkan"  <?= $status === 'Dibatalkan'  ? 'selected' : '' ?>>Dibatalkan</option>
                </select>
                <span class="etck-chevron">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </span>
            </div>
        </div>

        <!-- TEKNISI -->
        <div>
            <label class="text-xs etck-label block mb-1">Teknisi yang Ditugaskan</label>
            <div class="etck-sel-wrap">
                <select name="teknisi_id" class="etck-sel w-full">
                    <option value="">— Belum Ditugaskan —</option>
                    <?php foreach ($teknisi_list as $tek): ?>
                    <option value="<?= $tek['id'] ?>"
                            <?= ($ticket['teknisi_id'] ?? '') == $tek['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($tek['nama']) ?>
                        (<?= ucfirst($tek['status']) ?>)
                    </option>
                    <?php endforeach ?>
                </select>
                <span class="etck-chevron">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </span>
            </div>
        </div>

        <!-- ============================================================
             FIELD KONTEKSTUAL PER TIPE
        ============================================================ -->

        <?php if ($isGangguan): ?>
        <!-- ─────────── GANGGUAN ─────────── -->
        <div class="space-y-4 pt-1 border-t etck-border">
            <p class="text-xs font-semibold <?= $accentCls ?> pt-3">Detail Gangguan</p>

            <div>
                <label class="text-xs etck-label block mb-1">Lokasi Gangguan</label>
                <input type="text" name="lokasi"
                       value="<?= htmlspecialchars($ticket['lokasi'] ?? '') ?>"
                       placeholder="Contoh: Lantai 3 – Ruang Statistik"
                       class="etck-input w-full border rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 <?= $focusColor ?>">
            </div>

            <div>
                <label class="text-xs etck-label block mb-1">Deskripsi Gangguan</label>
                <textarea name="jenis_kerusakan" rows="4"
                          class="etck-input w-full border rounded-lg px-3 py-2 text-sm
                                 focus:outline-none focus:ring-2 <?= $focusColor ?> resize-none"
                          placeholder="Jelaskan detail gangguan..."><?= htmlspecialchars(trim($jk)) ?></textarea>
            </div>

            <div>
                <label class="text-xs etck-label block mb-1">Catatan Penanganan</label>
                <textarea name="layanan_catatan" rows="3"
                          class="etck-input w-full border rounded-lg px-3 py-2 text-sm
                                 focus:outline-none focus:ring-2 <?= $focusColor ?> resize-none"
                          placeholder="<?= $catatanPlh ?>"><?= htmlspecialchars($ticket['layanan_catatan'] ?? '') ?></textarea>
            </div>
        </div>


        <?php elseif ($isZoom): ?>
        <!-- ─────────── ZOOM / MEET ─────────── -->
        <?php
            /* Bersihkan jk dari ref tag untuk ditampilkan */
            $jkBersih = preg_replace('/\[ref:[^\]]*\]/', '', $jk);
        ?>
        <div class="space-y-4 pt-1 border-t etck-border">
            <p class="text-xs font-semibold <?= $accentCls ?> pt-3">Detail Layanan Zoom / Meet</p>

            <div>
                <label class="text-xs etck-label block mb-1">Detail Permintaan</label>
                <textarea name="jenis_kerusakan" rows="4"
                          class="etck-input w-full border rounded-lg px-3 py-2 text-sm
                                 focus:outline-none focus:ring-2 <?= $focusColor ?> resize-none"
                          placeholder="Detail permintaan Zoom..."><?= htmlspecialchars(trim($jkBersih)) ?></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-xs etck-label block mb-1">Tanggal Mulai</label>
                    <input type="date" name="tanggal"
                           value="<?= htmlspecialchars($ticket['tanggal'] ?? '') ?>"
                           class="etck-input w-full border rounded-lg px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 <?= $focusColor ?>">
                </div>
                <div>
                    <label class="text-xs etck-label block mb-1">Tanggal Selesai <span class="etck-muted">(opsional)</span></label>
                    <input type="date" name="tanggal_selesai"
                           value="<?= htmlspecialchars($ticket['tanggal_selesai'] ?? '') ?>"
                           class="etck-input w-full border rounded-lg px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 <?= $focusColor ?>">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-xs etck-label block mb-1">Jam Mulai (per hari)</label>
                    <input type="time" name="jam"
                           value="<?= htmlspecialchars($ticket['jam'] ?? '') ?>"
                           class="etck-input w-full border rounded-lg px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 <?= $focusColor ?>">
                </div>
                <div>
                    <label class="text-xs etck-label block mb-1">Durasi (jam/hari)</label>
                    <div class="etck-sel-wrap">
                        <select name="durasi" class="etck-sel w-full">
                            <option value="">— Pilih —</option>
                            <?php for ($i = 1; $i <= 12; $i++): ?>
                            <option value="<?= $i ?>"
                                    <?= ($ticket['durasi'] ?? '') == $i ? 'selected' : '' ?>>
                                <?= $i ?> Jam
                            </option>
                            <?php endfor ?>
                        </select>
                        <span class="etck-chevron">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-xs etck-label block mb-1">Link Zoom / Meet</label>
                    <input type="url" name="layanan_link"
                           value="<?= htmlspecialchars($ticket['layanan_link'] ?? '') ?>"
                           placeholder="https://zoom.us/j/..."
                           class="etck-input w-full border rounded-lg px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 <?= $focusColor ?>">
                </div>
                <div>
                    <label class="text-xs etck-label block mb-1">Link Undangan <span class="etck-muted">(opsional)</span></label>
                    <input type="url" name="undangan_link"
                           value="<?= htmlspecialchars($ticket['undangan_link'] ?? '') ?>"
                           placeholder="https://..."
                           class="etck-input w-full border rounded-lg px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 <?= $focusColor ?>">
                </div>
            </div>

            <div>
                <label class="text-xs etck-label block mb-1">Akun Zoom</label>
                <div class="etck-sel-wrap">
                    <select name="akun_zoom" class="etck-sel w-full">
                        <option value="">— Pilih Akun —</option>
                        <option value="1" <?= ($ticket['akun_zoom'] ?? '') == 1 ? 'selected' : '' ?>>Zoom 1 (BPS Riau)</option>
                        <option value="2" <?= ($ticket['akun_zoom'] ?? '') == 2 ? 'selected' : '' ?>>Zoom 2 (BPS Riau)</option>
                    </select>
                    <span class="etck-chevron">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </span>
                </div>
            </div>

            <div>
                <label class="text-xs etck-label block mb-1">Catatan untuk Pemohon</label>
                <textarea name="layanan_catatan" rows="3"
                          class="etck-input w-full border rounded-lg px-3 py-2 text-sm
                                 focus:outline-none focus:ring-2 <?= $focusColor ?> resize-none"
                          placeholder="<?= $catatanPlh ?>"><?= htmlspecialchars($ticket['layanan_catatan'] ?? '') ?></textarea>
            </div>
        </div>


        <?php elseif ($isRuangRapat): ?>
        <!-- ─────────── RUANG RAPAT ─────────── -->
        <?php
            $pId       = $peminjaman['id']              ?? '';
            $pRuangan  = $peminjaman['ruangan_id']      ?? '';
            $pUnitKerja= $peminjaman['unit_kerja']       ?? '';
            $pTanggal  = $peminjaman['tanggal']          ?? '';
            $pJamMulai = $peminjaman['jam_mulai']        ?? '';
            /* strip seconds if present */
            if (strlen($pJamMulai) > 5) $pJamMulai = substr($pJamMulai, 0, 5);
            $pJamSelesai= $peminjaman['jam_selesai']     ?? '';
            if (strlen($pJamSelesai) > 5) $pJamSelesai = substr($pJamSelesai, 0, 5);
            $pKeperluan = $peminjaman['keperluan']       ?? '';
            $pPeserta   = $peminjaman['jumlah_peserta']  ?? '';
            $pSnack     = $peminjaman['snack_corner']    ?? 'Tidak';
            $pJmlSnack  = $peminjaman['jumlah_snack']   ?? '';
            $pCatatan   = $peminjaman['catatan_peminjam'] ?? '';
            $pCatatanAdmin = $peminjaman['catatan_admin'] ?? $ticket['layanan_catatan'] ?? '';
        ?>
        <div class="space-y-4 pt-1 border-t etck-border">
            <p class="text-xs font-semibold <?= $accentCls ?> pt-3">Detail Peminjaman Ruang Rapat</p>

            <div>
                <label class="text-xs etck-label block mb-1">Unit / Tim Kerja</label>
                <input type="text" name="unit_kerja"
                       value="<?= htmlspecialchars($pUnitKerja) ?>"
                       placeholder="Contoh: Bagian Keuangan / Tim IPDS"
                       class="etck-input w-full border rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 <?= $focusColor ?>">
            </div>

            <div>
                <label class="text-xs etck-label block mb-1">Tanggal Pemakaian</label>
                <input type="date" name="tanggal_rapat"
                       value="<?= htmlspecialchars($pTanggal) ?>"
                       class="etck-input w-full border rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 <?= $focusColor ?>">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-xs etck-label block mb-1">Jam Mulai</label>
                    <input type="time" name="jam_mulai"
                           value="<?= htmlspecialchars($pJamMulai) ?>"
                           class="etck-input w-full border rounded-lg px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 <?= $focusColor ?>"
                           onchange="hitungSelesaiEdit()">
                </div>
                <div>
                    <label class="text-xs etck-label block mb-1">Durasi</label>
                    <div class="etck-sel-wrap">
                        <select name="durasi_rapat" id="durasiRapatEdit" class="etck-sel w-full"
                                onchange="hitungSelesaiEdit()">
                            <option value="">— Pilih —</option>
                            <?php foreach ([60,120,180,240,300,360,420,480] as $dur): ?>
                            <?php $jm = $dur/60; $lbl = $jm == 8 ? '8 Jam (Full Day)' : $jm.' Jam'; ?>
                            <?php
                                /* Hitung durasi dari jam mulai & selesai yang tersimpan */
                                $storedDur = '';
                                if ($pJamMulai && $pJamSelesai) {
                                    [$hM, $mM] = explode(':', $pJamMulai);
                                    [$hS, $mS] = explode(':', $pJamSelesai);
                                    $storedDur = ((int)$hS*60+(int)$mS) - ((int)$hM*60+(int)$mM);
                                }
                            ?>
                            <option value="<?= $dur ?>" <?= $storedDur == $dur ? 'selected' : '' ?>>
                                <?= $lbl ?>
                            </option>
                            <?php endforeach ?>
                        </select>
                        <span class="etck-chevron">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>

            <input type="hidden" name="jam_selesai" id="editJamSelesai" value="<?= htmlspecialchars($pJamSelesai) ?>">
            <div id="editInfoSelesai" class="<?= $pJamSelesai ? '' : 'hidden' ?>
                                            flex items-center gap-2 p-2 bg-green-500/10 border border-green-500/25 rounded-lg text-xs text-green-400">
                ⏰ Jam selesai: <strong id="editTampilSelesai"><?= htmlspecialchars($pJamSelesai) ?></strong> WIB
            </div>

            <div>
                <label class="text-xs etck-label block mb-1">Pilih Ruangan</label>
                <div class="etck-sel-wrap">
                    <select name="ruangan_id" id="editRuanganId" class="etck-sel w-full">
                        <option value="">— Pilih Ruangan —</option>
                        <?php foreach ($ruangan_list as $r): ?>
                        <option value="<?= $r['id'] ?>"
                                <?= $pRuangan == $r['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($r['nama_ruangan']) ?>
                            (Kap. <?= $r['kapasitas'] ?> org — <?= $r['lokasi'] ?>)
                        </option>
                        <?php endforeach ?>
                    </select>
                    <span class="etck-chevron">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </span>
                </div>
            </div>

            <div>
                <label class="text-xs etck-label block mb-1">Keperluan / Agenda</label>
                <textarea name="keperluan" rows="2"
                          class="etck-input w-full border rounded-lg px-3 py-2 text-sm
                                 focus:outline-none focus:ring-2 <?= $focusColor ?> resize-none"
                          placeholder="Contoh: Rapat Koordinasi Tim Q2"><?= htmlspecialchars($pKeperluan) ?></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-xs etck-label block mb-1">Jumlah Peserta</label>
                    <input type="number" name="jumlah_peserta" min="1"
                           value="<?= htmlspecialchars($pPeserta) ?>"
                           placeholder="Contoh: 20"
                           class="etck-input w-full border rounded-lg px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 <?= $focusColor ?>">
                </div>
                <div>
                    <label class="text-xs etck-label block mb-1">Snack Corner</label>
                    <div class="etck-sel-wrap">
                        <select name="snack_corner" id="editSnackCorner"
                                class="etck-sel w-full"
                                onchange="toggleEditSnack(this.value)">
                            <option value="Tidak" <?= $pSnack !== 'Ya' ? 'selected' : '' ?>>Tidak Perlu</option>
                            <option value="Ya"    <?= $pSnack === 'Ya' ? 'selected' : '' ?>>Ya, Perlu Snack</option>
                        </select>
                        <span class="etck-chevron">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>

            <div id="editSnackDetail" class="<?= $pSnack === 'Ya' ? '' : 'hidden' ?>">
                <label class="text-xs etck-label block mb-1">Jumlah Porsi Snack</label>
                <input type="number" name="jumlah_snack" min="1"
                       value="<?= htmlspecialchars($pJmlSnack) ?>"
                       placeholder="Contoh: 25"
                       class="etck-input w-full border rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 <?= $focusColor ?>">
            </div>

            <div>
                <label class="text-xs etck-label block mb-1">Catatan Peminjam</label>
                <textarea name="catatan_peminjam" rows="2"
                          class="etck-input w-full border rounded-lg px-3 py-2 text-sm
                                 focus:outline-none focus:ring-2 <?= $focusColor ?> resize-none"
                          placeholder="Susunan ruangan, peralatan, dll..."><?= htmlspecialchars($pCatatan) ?></textarea>
            </div>

            <div>
                <label class="text-xs etck-label block mb-1">Catatan Admin</label>
                <textarea name="catatan_admin" rows="2"
                          class="etck-input w-full border rounded-lg px-3 py-2 text-sm
                                 focus:outline-none focus:ring-2 <?= $focusColor ?> resize-none"
                          placeholder="<?= $catatanPlh ?>"><?= htmlspecialchars($pCatatanAdmin) ?></textarea>
            </div>
        </div>


        <?php elseif ($isKendaraan): ?>
        <!-- ─────────── KENDARAAN ─────────── -->
        <?php
            $daftar_kendaraan = $this->db->get('kendaraan')->result_array();
            $kId    = $pem_kend['kendaraan_id']     ?? parseJkField($jk, 'Kendaraan:');
            $kUnit  = $pem_kend['unit_kerja']        ?? parseJkField($jk, 'Unit Kerja:');
            $kKep   = $pem_kend['keperluan']         ?? parseJkField($jk, 'Keperluan:');
            $kTuj   = $pem_kend['tujuan']            ?? parseJkField($jk, 'Tujuan:');
            $kTpStr = $pem_kend['tanggal_pinjam']    ?? parseJkField($jk, 'Tanggal Pinjam:');
            $kTkStr = $pem_kend['tanggal_kembali']   ?? parseJkField($jk, 'Est. Kembali:');
            $kJpStr = $pem_kend['jam_pinjam']        ?? parseJkField($jk, 'Jam Mulai:');
            $kJkStr = $pem_kend['jam_kembali']       ?? parseJkField($jk, 'Jam Kembali:');
            $kDrv   = $pem_kend['pengemudi']         ?? parseJkField($jk, 'Pengemudi:');
            $kNoHp  = $pem_kend['no_hp']             ?? parseJkField($jk, 'No. HP:');
            $kCat   = $pem_kend['catatan']           ?? parseJkField($jk, 'Catatan:');
        ?>
        <div class="space-y-4 pt-1 border-t etck-border">
            <p class="text-xs font-semibold <?= $accentCls ?> pt-3">Detail Peminjaman Kendaraan</p>

            <div>
                <label class="text-xs etck-label block mb-1">Kendaraan</label>
                <div class="etck-sel-wrap">
                    <select name="kend_kendaraan_id" class="etck-sel w-full">
                        <option value="">— Pilih Kendaraan —</option>
                        <?php foreach ($daftar_kendaraan as $kv): ?>
                        <option value="<?= $kv['id'] ?>"
                                <?= ($pem_kend['kendaraan_id'] ?? '') == $kv['id'] ? 'selected' : '' ?>>
                            <?= $kv['jenis'] === 'Mobil' ? '🚗' : '🏍️' ?>
                            <?= htmlspecialchars($kv['nama_kendaraan']) ?>
                            (<?= $kv['nomor_polisi'] ?>)
                        </option>
                        <?php endforeach ?>
                    </select>
                    <span class="etck-chevron">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </span>
                </div>
            </div>

            <div>
                <label class="text-xs etck-label block mb-1">Unit / Bidang Kerja</label>
                <input type="text" name="kend_unit_kerja"
                       value="<?= htmlspecialchars($kUnit) ?>"
                       placeholder="Contoh: Bagian Keuangan"
                       class="etck-input w-full border rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 <?= $focusColor ?>">
            </div>

            <div>
                <label class="text-xs etck-label block mb-1">Keperluan / Tujuan Perjalanan</label>
                <input type="text" name="kend_keperluan"
                       value="<?= htmlspecialchars($kKep) ?>"
                       placeholder="Contoh: Survey ke Kab. Kampar"
                       class="etck-input w-full border rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 <?= $focusColor ?>">
            </div>

            <div>
                <label class="text-xs etck-label block mb-1">Lokasi Tujuan</label>
                <input type="text" name="kend_tujuan"
                       value="<?= htmlspecialchars($kTuj) ?>"
                       placeholder="Contoh: Kab. Kampar"
                       class="etck-input w-full border rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 <?= $focusColor ?>">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-xs etck-label block mb-1">Tanggal Pinjam</label>
                    <input type="date" name="kend_tanggal_pinjam"
                           value="<?= htmlspecialchars($kTpStr) ?>"
                           class="etck-input w-full border rounded-lg px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 <?= $focusColor ?>">
                </div>
                <div>
                    <label class="text-xs etck-label block mb-1">Est. Tanggal Kembali</label>
                    <input type="date" name="kend_tanggal_kembali"
                           value="<?= htmlspecialchars($kTkStr) ?>"
                           class="etck-input w-full border rounded-lg px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 <?= $focusColor ?>">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-xs etck-label block mb-1">Jam Pinjam</label>
                    <input type="time" name="kend_jam_pinjam"
                           value="<?= htmlspecialchars($kJpStr) ?>"
                           class="etck-input w-full border rounded-lg px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 <?= $focusColor ?>">
                </div>
                <div>
                    <label class="text-xs etck-label block mb-1">Pengemudi</label>
                    <input type="text" name="kend_pengemudi"
                           value="<?= htmlspecialchars($kDrv) ?>"
                           placeholder="Kosongkan jika mengemudi sendiri"
                           class="etck-input w-full border rounded-lg px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 <?= $focusColor ?>">
                </div>
            </div>

            <div>
                <label class="text-xs etck-label block mb-1">No. HP Peminjam</label>
                <input type="text" name="kend_no_hp"
                       value="<?= htmlspecialchars($kNoHp) ?>"
                       placeholder="08xxxxxxxxxx"
                       class="etck-input w-full border rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 <?= $focusColor ?>">
            </div>

            <div>
                <label class="text-xs etck-label block mb-1">Catatan Admin</label>
                <textarea name="kend_catatan" rows="3"
                          class="etck-input w-full border rounded-lg px-3 py-2 text-sm
                                 focus:outline-none focus:ring-2 <?= $focusColor ?> resize-none"
                          placeholder="<?= $catatanPlh ?>"><?= htmlspecialchars($ticket['layanan_catatan'] ?? $kCat) ?></textarea>
            </div>
        </div>


        <?php elseif ($isBmn): ?>
        <!-- ─────────── BMN ─────────── -->
        <?php
            $daftar_bmn = $this->db->get('bmn')->result_array();
            $bId     = $pem_bmn['bmn_id']           ?? '';
            $bUnit   = $pem_bmn['unit_kerja']        ?? parseJkField($jk, 'Unit Kerja:');
            $bTujuan = $pem_bmn['tujuan']            ?? parseJkField($jk, 'Tujuan:');
            $bTp     = $pem_bmn['tanggal_pinjam']    ?? '';
            $bTk     = $pem_bmn['tanggal_kembali']   ?? '';
            $bCat    = $pem_bmn['catatan']           ?? parseJkField($jk, 'Catatan:');
        ?>
        <div class="space-y-4 pt-1 border-t etck-border">
            <p class="text-xs font-semibold <?= $accentCls ?> pt-3">Detail Peminjaman BMN</p>

            <div>
                <label class="text-xs etck-label block mb-1">Barang BMN</label>
                <div class="etck-sel-wrap">
                    <select name="bmn_id" class="etck-sel w-full">
                        <option value="">— Pilih Barang —</option>
                        <?php foreach ($daftar_bmn as $b): ?>
                        <option value="<?= $b['id'] ?>"
                                <?= ($pem_bmn['bmn_id'] ?? '') == $b['id'] ? 'selected' : '' ?>>
                            📦 <?= htmlspecialchars($b['nama_barang']) ?>
                            <?= !empty($b['merek']) ? ' – ' . $b['merek'] : '' ?>
                            (<?= $b['kode_barang'] ?>/<?= $b['nup'] ?>)
                        </option>
                        <?php endforeach ?>
                    </select>
                    <span class="etck-chevron">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </span>
                </div>
            </div>

            <div>
                <label class="text-xs etck-label block mb-1">Unit / Bidang Kerja</label>
                <input type="text" name="bmn_unit_kerja"
                       value="<?= htmlspecialchars($bUnit) ?>"
                       placeholder="Contoh: Bagian Keuangan"
                       class="etck-input w-full border rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 <?= $focusColor ?>">
            </div>

            <div>
                <label class="text-xs etck-label block mb-1">Tujuan Peminjaman</label>
                <textarea name="bmn_tujuan" rows="2"
                          class="etck-input w-full border rounded-lg px-3 py-2 text-sm
                                 focus:outline-none focus:ring-2 <?= $focusColor ?> resize-none"
                          placeholder="Contoh: Digunakan untuk kegiatan survei lapangan"><?= htmlspecialchars($bTujuan) ?></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-xs etck-label block mb-1">Tanggal Pinjam</label>
                    <input type="date" name="bmn_tanggal_pinjam"
                           value="<?= htmlspecialchars($bTp) ?>"
                           class="etck-input w-full border rounded-lg px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 <?= $focusColor ?>">
                </div>
                <div>
                    <label class="text-xs etck-label block mb-1">Rencana Tanggal Kembali</label>
                    <input type="date" name="bmn_tanggal_kembali"
                           value="<?= htmlspecialchars($bTk) ?>"
                           class="etck-input w-full border rounded-lg px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 <?= $focusColor ?>">
                </div>
            </div>

            <div>
                <label class="text-xs etck-label block mb-1">Catatan Admin</label>
                <textarea name="bmn_catatan" rows="2"
                          class="etck-input w-full border rounded-lg px-3 py-2 text-sm
                                 focus:outline-none focus:ring-2 <?= $focusColor ?> resize-none"
                          placeholder="<?= $catatanPlh ?>"><?= htmlspecialchars($ticket['layanan_catatan'] ?? $bCat) ?></textarea>
            </div>
        </div>


        <?php else: ?>
        <!-- ─────────── PERMINTAAN IT BIASA ─────────── -->
        <?php $jkBersih = preg_replace('/\[ref:[^\]]*\]/', '', $jk); ?>
        <div class="space-y-4 pt-1 border-t etck-border">
            <p class="text-xs font-semibold <?= $accentCls ?> pt-3">Detail Permintaan</p>

            <div>
                <label class="text-xs etck-label block mb-1">Detail Permintaan</label>
                <textarea name="jenis_kerusakan" rows="4"
                          class="etck-input w-full border rounded-lg px-3 py-2 text-sm
                                 focus:outline-none focus:ring-2 <?= $focusColor ?> resize-none"
                          placeholder="Detail permintaan layanan IT..."><?= htmlspecialchars(trim($jkBersih)) ?></textarea>
            </div>

            <?php if (!empty($ticket['lokasi'])): ?>
            <div>
                <label class="text-xs etck-label block mb-1">Lokasi / Unit Kerja</label>
                <input type="text" name="lokasi"
                       value="<?= htmlspecialchars($ticket['lokasi'] ?? '') ?>"
                       placeholder="Contoh: Lantai 2 – Ruang IT"
                       class="etck-input w-full border rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 <?= $focusColor ?>">
            </div>
            <?php endif ?>

            <div>
                <label class="text-xs etck-label block mb-1">Catatan Admin</label>
                <textarea name="layanan_catatan" rows="3"
                          class="etck-input w-full border rounded-lg px-3 py-2 text-sm
                                 focus:outline-none focus:ring-2 <?= $focusColor ?> resize-none"
                          placeholder="<?= $catatanPlh ?>"><?= htmlspecialchars($ticket['layanan_catatan'] ?? '') ?></textarea>
            </div>
        </div>
        <?php endif ?>

        <!-- ── TANGGAL SELESAI (muncul saat status = Selesai) ── -->
        <div id="wrapSelesaiAt" class="<?= $status === 'Selesai' ? '' : 'hidden' ?>">
            <label class="text-xs etck-label block mb-1">Tanggal Selesai</label>
            <input type="datetime-local" name="selesai_at"
                   value="<?= !empty($ticket['selesai_at'])
                               ? date('Y-m-d\TH:i', strtotime($ticket['selesai_at'])) : '' ?>"
                   class="etck-input w-full border rounded-lg px-3 py-2 text-sm
                          focus:outline-none focus:ring-2 focus:ring-cyan-500">
        </div>

    </form>
    </div><!-- end scrollable body -->

    <!-- ── FOOTER ── -->
    <div class="px-6 py-4 border-t etck-border flex-shrink-0 flex items-center justify-end gap-3">
        <button type="button" id="btnCancel"
                class="etck-btn-cancel px-5 py-2 border rounded-lg text-sm transition">
            Batal
        </button>
        <button type="button" id="btnSave"
                class="px-6 py-2 bg-cyan-600 hover:bg-cyan-500 text-white
                       font-semibold rounded-lg text-sm transition">
            Simpan Perubahan
        </button>
    </div>

</div>
</div>

<!-- ───────────────── STYLES ───────────────── -->
<style>
.etck-card     { background: #0d1117; border-color: rgba(55,65,81,.6); }
.etck-border   { border-color: rgba(55,65,81,.5); }
.etck-text     { color: #f1f5f9; }
.etck-label    { color: #94a3b8; }
.etck-muted    { color: #64748b; }
.etck-info-box { background: rgba(15,23,42,.5); border-color: rgba(55,65,81,.4); }

.etck-input {
    background: rgba(11,15,20,.9);
    border-color: rgba(55,65,81,.5);
    color: #f1f5f9;
}
.etck-input::placeholder { color: #475569; }

.etck-input-ro {
    background: rgba(30,41,59,.4);
    border-color: rgba(55,65,81,.35);
    color: #64748b;
    cursor: not-allowed;
}

.etck-sel-wrap { position: relative; }
.etck-sel-wrap select.etck-sel {
    all: unset; display: block; width: 100%; box-sizing: border-box;
    padding: .5rem 2.5rem .5rem .75rem; font-size: .875rem;
    border-radius: .5rem; border: 1px solid rgba(55,65,81,.5);
    background: rgba(11,15,20,.9); color: #f1f5f9; cursor: pointer;
}
.etck-sel-wrap select.etck-sel:focus {
    border-color: #06b6d4;
    box-shadow: 0 0 0 2px rgba(6,182,212,.25);
}
.etck-chevron {
    position: absolute; right: 10px; top: 50%;
    transform: translateY(-50%);
    pointer-events: none; color: #9ca3af;
    display: flex; align-items: center;
}
.etck-btn-cancel {
    background: transparent; border-color: rgba(55,65,81,.7); color: #9ca3af;
}
.etck-btn-cancel:hover { background: rgba(55,65,81,.4); color: #f1f5f9; }

.etck-scrollbar::-webkit-scrollbar { width: 5px; }
.etck-scrollbar::-webkit-scrollbar-track { background: transparent; }
.etck-scrollbar::-webkit-scrollbar-thumb { background: #374151; border-radius: 10px; }

/* ── LIGHT overrides ── */
html.light .etck-card     { background: #fff !important; border-color: #e2e8f0 !important; }
html.light .etck-border   { border-color: #e2e8f0 !important; }
html.light .etck-text     { color: #0f172a !important; }
html.light .etck-label    { color: #475569 !important; }
html.light .etck-muted    { color: #94a3b8 !important; }
html.light .etck-info-box { background: #f8fafc !important; border-color: #e2e8f0 !important; }
html.light .etck-input { background: #fff !important; border-color: #cbd5e1 !important; color: #0f172a !important; }
html.light .etck-input-ro { background: #f1f5f9 !important; border-color: #e2e8f0 !important; color: #94a3b8 !important; }
html.light .etck-sel-wrap select.etck-sel { background: #fff !important; border-color: #cbd5e1 !important; color: #0f172a !important; }
html.light .etck-chevron { color: #64748b !important; }
html.light .etck-btn-cancel { background: #f8fafc !important; border-color: #e2e8f0 !important; color: #334155 !important; }
html.light .etck-btn-cancel:hover { background: #e2e8f0 !important; }
html.light .etck-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; }
</style>

