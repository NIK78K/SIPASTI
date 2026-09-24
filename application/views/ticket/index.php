<?php
// ================= UTIL =================
function durasi_tiket($start, $end)
{
    if (empty($start) || empty($end)) return '-';
    $diff = strtotime($end) - strtotime($start);
    if ($diff <= 0) return '-';
    $jam   = floor($diff / 3600);
    $menit = floor(($diff % 3600) / 60);
    return $jam > 0 ? "$jam jam $menit menit" : "$menit menit";
}

$statusText = [
    'Menunggu'    => 'Menunggu',
    'On Progress' => 'Diproses',
    'Selesai'     => 'Selesai',
    'Dibatalkan'  => 'Dibatalkan'
];

$statusBadge = [
    'Menunggu'    => 'badge-menunggu',
    'On Progress' => 'badge-progress',
    'Selesai'     => 'badge-selesai',
    'Dibatalkan'  => 'badge-batal'
];

// Ambil semua sub_kategori_id yang perlu_jadwal = 1 (Zoom/Meet)
$sub_zoom_ids_raw = $this->db
    ->select('id')
    ->where('perlu_jadwal', 1)
    ->get('master_sub_kategori')
    ->result_array();
$sub_zoom_ids = array_column($sub_zoom_ids_raw, 'id');

$no = $page + 1;
?>

<h1 class="text-2xl font-semibold mb-6 header-title">Manajemen Tiket</h1>

<!-- ================= STATUS CARDS ================= -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="theme-card stat-card border border-yellow-500/20 rounded-xl p-5 hover:scale-[1.02] transition">
        <div class="text-yellow-400 text-sm mb-1 header-subtitle">Menunggu</div>
        <div class="text-3xl font-semibold text-yellow-300"><?= $menunggu ?></div>
        <div class="text-xs sc-muted mt-1">Tiket belum diproses</div>
    </div>
    <div class="theme-card stat-card border border-cyan-500/20 rounded-xl p-5 hover:scale-[1.02] transition">
        <div class="text-cyan-400 text-sm mb-1 header-subtitle">On Progress</div>
        <div class="text-3xl font-semibold text-cyan-300"><?= $progress ?></div>
        <div class="text-xs sc-muted mt-1">Sedang ditangani teknisi</div>
    </div>
    <div class="theme-card stat-card border border-green-500/20 rounded-xl p-5 hover:scale-[1.02] transition">
        <div class="text-green-400 text-sm mb-1 header-subtitle">Selesai</div>
        <div class="text-3xl font-semibold text-green-300"><?= $selesai ?></div>
        <div class="text-xs sc-muted mt-1">Tiket sudah diselesaikan</div>
    </div>
    <div class="theme-card stat-card border border-red-500/20 rounded-xl p-5 hover:scale-[1.02] transition">
        <div class="text-red-400 text-sm mb-1 header-subtitle">Dibatalkan</div>
        <div class="text-3xl font-semibold text-red-300"><?= $batal ?></div>
        <div class="text-xs sc-muted mt-1">Tiket dibatalkan</div>
    </div>
</div>

<!-- ================= FILTER BAR ================= -->
<form id="filterForm" method="get" class="flex flex-wrap gap-3 mb-4 items-center">

    <input type="text" name="q" value="<?= $this->input->get('q') ?>"
           placeholder="Cari kode / pelapor..."
           class="fltr-input">

    <div class="fltr-select-wrap">
        <select name="status" class="fltr-select" onchange="autoSubmit()">
            <option value="">Semua Status</option>
            <?php foreach ($statusText as $key => $label): ?>
                <option value="<?= $key ?>" <?= $this->input->get('status') === $key ? 'selected' : '' ?>>
                    <?= $label ?>
                </option>
            <?php endforeach ?>
        </select>
        <span class="fltr-chevron">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </span>
    </div>

    <div class="fltr-select-wrap">
        <select name="kategori_id" class="fltr-select" onchange="autoSubmit()">
            <option value="">Semua Kategori</option>
            <?php foreach ($master_kategori as $k): ?>
                <option value="<?= $k->id ?>" <?= $this->input->get('kategori_id') == $k->id ? 'selected' : '' ?>>
                    <?= $k->nama_kategori ?>
                </option>
            <?php endforeach ?>
        </select>
        <span class="fltr-chevron">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </span>
    </div>

    <div class="fltr-select-wrap">
        <select name="sub_kategori_id" class="fltr-select" onchange="autoSubmit()">
            <option value="">Semua Sub Kategori</option>
            <?php foreach ($master_sub_kategori as $sk): ?>
                <option value="<?= $sk->id ?>" <?= $this->input->get('sub_kategori_id') == $sk->id ? 'selected' : '' ?>>
                    <?= $sk->nama_sub_kategori ?>
                </option>
            <?php endforeach ?>
        </select>
        <span class="fltr-chevron">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </span>
    </div>

</form>

<!-- ================= ACTION BAR ================= -->
<div class="flex justify-end mb-4">
    <button onclick="openCreateModal()"
        class="inline-flex items-center gap-2 bg-cyan-600 hover:bg-cyan-500
               text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Tiket
    </button>
</div>

<!-- ================= TABLE ================= -->
<div class="theme-card border rounded-lg overflow-hidden shadow-xl">
    <table class="w-full text-xs table-fixed">
        <thead class="tbl-head border-b">
            <tr>
                <th class="px-2 py-3 text-center font-semibold" style="width:40px;">No</th>
                <th class="px-2 py-3 font-semibold" style="width:100px;">Kode Tiket</th>
                <th class="px-2 py-3 text-center font-semibold" style="width:90px;">Kategori</th>
                <th class="px-2 py-3 text-center font-semibold" style="width:120px;">Sub Kategori</th>
                <th class="px-2 py-3 font-semibold" style="width:90px;">Pelapor</th>
                <th class="px-2 py-3 font-semibold" style="width:90px;">Tanggal</th>
                <th class="px-2 py-3 text-center font-semibold" style="width:85px;">Status</th>
                <th class="px-2 py-3 font-semibold" style="width:155px;">Teknisi / Layanan</th>
                <th class="px-2 py-3 text-center font-semibold" style="width:75px;">Durasi</th>
                <th class="px-2 py-3 text-center font-semibold" style="width:80px;">Aksi</th>
            </tr>
        </thead>

        <tbody class="tbl-body">

            <?php if (empty($tickets)): ?>
            <tr>
                <td colspan="10" class="py-10 text-center sc-muted">Tidak ada data tiket</td>
            </tr>
            <?php endif ?>

            <?php foreach ($tickets as $t):
                $status        = $t['status'];
                $katSlug       = strtolower($t['nama_kategori'] ?? '');
                $subKatId      = (int)($t['sub_kategori_id'] ?? 0);
                $jk            = $t['jenis_kerusakan'] ?? '';

                // Deteksi jenis tiket
                $isPermintaan  = $katSlug === 'permintaan';
                $isRuangRapat  = $isPermintaan && strpos($jk, '[ref:peminjaman_id=') !== false;
                $isKendaraan   = $isPermintaan && strpos($jk, '[ref:peminjaman_kendaraan_id=') !== false;
                $isBmn         = $isPermintaan && strpos($jk, '[ref:peminjaman_bmn_id=') !== false;

                // Tiket Zoom = permintaan + perlu_jadwal=1, bukan ruang rapat/kendaraan/BMN
                $isZoom = $isPermintaan
                       && !$isRuangRapat
                       && !$isKendaraan
                       && !$isBmn
                       && in_array($subKatId, $sub_zoom_ids);

                // Tiket non-zoom = semua selain zoom
                $isNonZoomPermintaan = $isPermintaan && !$isZoom;
            ?>
            <tr class="tbl-row">

                <td class="px-2 py-3 text-center text-xs sc-muted font-medium"><?= $no++ ?></td>
                <td class="px-2 py-3 font-mono text-xs sc-text truncate"><?= $t['kode_tiket'] ?></td>

                <td class="px-2 py-3 text-center">
                    <span class="badge-kategori" title="<?= $t['nama_kategori'] ?? '-' ?>">
                        <?= $t['nama_kategori'] ?? '-' ?>
                    </span>
                </td>

                <td class="px-2 py-3 text-center">
                    <span class="badge-sub" title="<?= $t['nama_sub_kategori'] ?? '-' ?>">
                        <?= $t['nama_sub_kategori'] ?? '-' ?>
                    </span>
                </td>

                <td class="px-2 py-3 truncate text-xs sc-text"><?= $t['pelapor'] ?></td>
                <td class="px-2 py-3 sc-muted text-xs"><?= date('d M Y', strtotime($t['created_at'])) ?></td>

                <td class="px-2 py-3 text-center">
                    <span class="<?= $statusBadge[$status] ?? 'badge-menunggu' ?>" title="<?= $statusText[$status] ?? $status ?>">
                        <?= $statusText[$status] ?? $status ?>
                    </span>
                </td>

                <!-- ============================================================
                     KOLOM TEKNISI / LAYANAN — logika per jenis tiket
                ============================================================ -->
                <td class="px-2 py-3 text-xs">

                    <?php
                    /* ── CABANG 1: TIKET ZOOM (permintaan + perlu_jadwal=1) ── */
                    if ($isZoom):
                    ?>

                        <?php if (empty($t['layanan_link'])): ?>
                            <?php /* Status Menunggu / On Progress tanpa link — tampilkan form Zoom */ ?>
                            <?php if ($status === 'Menunggu' || $status === 'On Progress'): ?>
                            <form method="post" action="<?= base_url('ticket/set_layanan/'.$t['id']) ?>" class="space-y-1">
                                <input type="url" name="layanan_link" required placeholder="Link Zoom/Meet"
                                       class="tbl-input w-full rounded-md px-2 py-1 text-xs focus:ring-1 focus:ring-emerald-500 focus:outline-none">
                                <textarea name="layanan_catatan" rows="2" placeholder="Catatan (opsional)"
                                          class="tbl-input w-full rounded-md px-2 py-1 text-xs focus:ring-1 focus:ring-emerald-500 focus:outline-none"></textarea>
                                <button class="w-full bg-emerald-600 hover:bg-emerald-500 rounded-md py-1 text-xs font-semibold transition text-white">
                                    Simpan Link
                                </button>
                            </form>
                            <?php else: ?>
                            <span class="sc-muted italic">—</span>
                            <?php endif ?>

                        <?php else: ?>
                            <?php /* Sudah ada link Zoom — tampilkan link + nama teknisi */ ?>
                            <div class="space-y-1">
                                <a href="<?= htmlspecialchars($t['layanan_link']) ?>" target="_blank"
                                   class="flex items-center gap-1 text-emerald-400 hover:text-emerald-300 underline text-xs truncate transition">
                                    🔗 Link Zoom
                                </a>

                                <?php if (!empty($t['nama_teknisi'])): ?>
                                <div class="flex items-center gap-1 text-cyan-400 text-xs font-semibold">
                                    <span>👨‍🔧</span>
                                    <span class="truncate"><?= htmlspecialchars($t['nama_teknisi']) ?></span>
                                </div>
                                <?php elseif ($status === 'Menunggu' || $status === 'On Progress'): ?>
                                <form method="post" action="<?= base_url('ticket/assign_teknisi/'.$t['id']) ?>">
                                    <div class="tbl-select-wrap">
                                        <select name="teknisi_id" class="tbl-select w-full" onchange="this.form.submit()">
                                            <option value="">Pilih Teknisi</option>
                                            <?php foreach ($teknisi_available as $tek): ?>
                                                <option value="<?= $tek['id'] ?>"><?= htmlspecialchars($tek['name']) ?></option>
                                            <?php endforeach ?>
                                        </select>
                                        <span class="tbl-chevron">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </span>
                                    </div>
                                </form>
                                <?php endif ?>
                            </div>
                        <?php endif ?>

                    <?php
                    /* ── CABANG 2: TIKET NON-ZOOM (ruang rapat, kendaraan, BMN, permintaan biasa) ── */
                    elseif ($isNonZoomPermintaan):
                    ?>

                        <?php if (!empty($t['nama_teknisi'])): ?>
                            <?php /* Sudah ada teknisi — tampilkan nama saja */ ?>
                            <div class="flex items-center gap-1 text-cyan-400 text-xs font-semibold">
                                <span>👨‍🔧</span>
                                <span class="truncate"><?= htmlspecialchars($t['nama_teknisi']) ?></span>
                            </div>
                            <?php if ($isRuangRapat): ?>
                            <div class="mt-1 text-[10px] text-indigo-400 font-medium">🏢 Ruang Rapat</div>
                            <?php elseif ($isKendaraan): ?>
                            <div class="mt-1 text-[10px] text-amber-400 font-medium">🚗 Kendaraan</div>
                            <?php elseif ($isBmn): ?>
                            <div class="mt-1 text-[10px] text-orange-400 font-medium">📦 BMN</div>
                            <?php endif ?>

                        <?php elseif ($status === 'Menunggu' || $status === 'On Progress'): ?>
                            <?php /* Belum ada teknisi — dropdown assign */ ?>
                            <?php if ($isRuangRapat): ?>
                            <div class="text-[10px] text-indigo-400 font-medium mb-1">🏢 Ruang Rapat</div>
                            <?php elseif ($isKendaraan): ?>
                            <div class="text-[10px] text-amber-400 font-medium mb-1">🚗 Kendaraan</div>
                            <?php elseif ($isBmn): ?>
                            <div class="text-[10px] text-orange-400 font-medium mb-1">📦 BMN</div>
                            <?php endif ?>
                            <form method="post" action="<?= base_url('ticket/assign_teknisi/'.$t['id']) ?>">
                                <div class="tbl-select-wrap">
                                    <select name="teknisi_id" class="tbl-select w-full" onchange="this.form.submit()">
                                        <option value="">Pilih Teknisi</option>
                                        <?php foreach ($teknisi_available as $tek): ?>
                                            <option value="<?= $tek['id'] ?>"><?= htmlspecialchars($tek['name']) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                    <span class="tbl-chevron">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </span>
                                </div>
                            </form>

                        <?php else: ?>
                            <span class="sc-muted italic text-xs">—</span>
                        <?php endif ?>

                    <?php
                    /* ── CABANG 3: TIKET GANGGUAN / KERUSAKAN / LAINNYA ── */
                    else:
                    ?>

                        <?php if ($status === 'Menunggu'): ?>
                            <?php /* Belum ada teknisi — dropdown assign */ ?>
                            <form method="post" action="<?= base_url('ticket/assign_teknisi/'.$t['id']) ?>">
                                <div class="tbl-select-wrap">
                                    <select name="teknisi_id" class="tbl-select w-full" onchange="this.form.submit()">
                                        <option value="">Pilih Teknisi</option>
                                        <?php foreach ($teknisi_available as $tek): ?>
                                            <option value="<?= $tek['id'] ?>"><?= htmlspecialchars($tek['name']) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                    <span class="tbl-chevron">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </span>
                                </div>
                            </form>

                        <?php else: ?>
                            <?php /* On Progress / Selesai — tampilkan nama teknisi */ ?>
                            <div class="flex items-center gap-1 sc-text text-xs">
                                <span>👨‍🔧</span>
                                <span class="truncate"><?= htmlspecialchars($t['nama_teknisi'] ?? '-') ?></span>
                            </div>
                        <?php endif ?>

                    <?php endif /* akhir cabang */ ?>

                </td>
                <!-- ============================================================ -->

                <td class="px-2 py-3 text-center sc-muted text-xs font-medium">
                    <?= $status === 'Selesai' ? durasi_tiket($t['created_at'], $t['selesai_at']) : '-' ?>
                </td>

                <td class="px-1 py-3">
                    <div class="flex gap-0.5 items-center justify-center">
                        <button onclick="openDetailModal(<?= $t['id'] ?>)" title="Detail"
                                class="p-1 rounded-md bg-slate-600/20 hover:bg-slate-500/40
                                       text-slate-400 hover:text-slate-600 transition-all hover:scale-105 active:scale-95">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                        <button onclick="openEditModal(<?= $t['id'] ?>)" title="Edit"
                                class="p-1 rounded-md bg-amber-600/20 hover:bg-amber-500/40
                                       text-amber-500 hover:text-amber-600 transition-all hover:scale-105 active:scale-95">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <?php if ($status === 'On Progress'): ?>
                        <a href="<?= base_url('ticket/selesai/'.$t['id']) ?>" title="Tandai Selesai"
                           onclick="return confirm('Tandai tiket ini sebagai selesai?')"
                           class="p-1 rounded-md bg-green-600/20 hover:bg-green-500/40
                                  text-green-500 hover:text-green-600 transition-all hover:scale-105 active:scale-95">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </a>
                        <?php endif ?>
                        <?php if ($this->session->userdata('role') !== 'teknisi'): ?>
                        <a href="<?= base_url('ticket/delete/'.$t['id']) ?>" title="Hapus"
                           onclick="return confirm('Hapus tiket ini permanen?')"
                           class="p-1 rounded-md bg-red-600/20 hover:bg-red-500/40
                                  text-red-500 hover:text-red-600 transition-all hover:scale-105 active:scale-95">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </a>
                        <?php endif ?>
                    </div>
                </td>

            </tr>
            <?php endforeach ?>

            <?php if (!empty($pagination)): ?>
            <tr>
                <td colspan="10" class="px-4 py-5">
                    <div class="flex justify-center"><?= $pagination ?></div>
                </td>
            </tr>
            <?php endif ?>

        </tbody>
    </table>
</div>

<!-- ================= MODAL DETAIL ================= -->
<div id="modal" class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50">
    <div class="theme-card border rounded-xl w-full max-w-xl p-5">
        <div id="modal-content"></div>
        <button onclick="closeModal()" class="mt-5 w-full bg-gray-700/70 hover:bg-gray-600 py-2 rounded-md text-sm transition text-white">Tutup</button>
    </div>
</div>

<!-- ================= MODAL FORM ================= -->
<div id="form-modal" class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50">
    <div class="theme-card border rounded-xl w-full max-w-2xl p-5">
        <div id="form-modal-content"></div>
    </div>
</div>

<!-- ================= STYLES ================= -->
<style>

/* ══════════════════════════════════════
   SEMANTIC TOKENS — dark default
══════════════════════════════════════ */
.sc-text  { color: #e2e8f0; }
.sc-muted { color: #64748b; }

/* ── TABLE ── */
.tbl-head { background: rgba(15,23,42,0.9); color: #94a3b8; border-color: rgba(51,65,85,0.5); }
.tbl-body {}
.tbl-row  { border-bottom: 1px solid rgba(51,65,85,0.3); }
.tbl-row:hover { background: rgba(30,41,59,0.5); }

/* ── BADGES ── */
.badge-kategori,
.badge-sub,
.badge-menunggu,
.badge-progress,
.badge-selesai,
.badge-batal {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 2px 10px;
    font-size: 10px;
    font-weight: 500;
    border-radius: 8px;
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.badge-kategori { background: rgba(99,102,241,0.15); color: #a5b4fc; border: 1px solid rgba(99,102,241,0.3); }
.badge-sub      { background: rgba(6,182,212,0.15);  color: #67e8f9;  border: 1px solid rgba(6,182,212,0.3); }
.badge-menunggu { background: rgba(234,179,8,0.15);  color: #fcd34d;  border: 1px solid rgba(234,179,8,0.3); }
.badge-progress { background: rgba(6,182,212,0.15);  color: #67e8f9;  border: 1px solid rgba(6,182,212,0.3); }
.badge-selesai  { background: rgba(34,197,94,0.15);  color: #86efac;  border: 1px solid rgba(34,197,94,0.3); }
.badge-batal    { background: rgba(239,68,68,0.15);  color: #fca5a5;  border: 1px solid rgba(239,68,68,0.3); }

/* ── FILTER INPUT ── */
.fltr-input {
    all: unset;
    display: inline-block;
    box-sizing: border-box;
    background: rgba(30,41,59,0.8);
    border: 1px solid rgba(51,65,85,0.5);
    color: #f1f5f9;
    border-radius: 0.5rem;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    width: 14rem;
}
.fltr-input::placeholder { color: #475569; }
.fltr-input:focus { border-color: #06b6d4; box-shadow: 0 0 0 1px #06b6d4; }

/* ── FILTER SELECT ── */
.fltr-select-wrap {
    position: relative;
    display: inline-flex;
    align-items: center;
}
.fltr-select-wrap select.fltr-select {
    all: unset;
    display: block;
    box-sizing: border-box;
    background: rgba(30,41,59,0.8);
    border: 1px solid rgba(51,65,85,0.5);
    color: #f1f5f9;
    border-radius: 0.5rem;
    padding: 0.5rem 2rem 0.5rem 0.75rem;
    font-size: 0.875rem;
    cursor: pointer;
}
.fltr-select-wrap select.fltr-select:focus { border-color: #06b6d4; box-shadow: 0 0 0 1px #06b6d4; }
.fltr-chevron {
    position: absolute; right: 8px;
    pointer-events: none; color: #9ca3af;
    display: flex; align-items: center;
}

/* ── TABLE INLINE SELECT ── */
.tbl-select-wrap { position: relative; width: 100%; }
.tbl-select-wrap select.tbl-select {
    all: unset;
    display: block; width: 100%; box-sizing: border-box;
    background: rgba(30,41,59,0.8);
    border: 1px solid rgba(51,65,85,0.5);
    color: #f1f5f9;
    border-radius: 0.375rem;
    padding: 0.25rem 1.5rem 0.25rem 0.5rem;
    font-size: 0.75rem; cursor: pointer;
}
.tbl-select-wrap select.tbl-select:focus { border-color: #06b6d4; box-shadow: 0 0 0 1px #06b6d4; }
.tbl-chevron {
    position: absolute; right: 6px; top: 50%; transform: translateY(-50%);
    pointer-events: none; color: #9ca3af;
    display: flex; align-items: center;
}

/* ── TABLE INPUT ── */
.tbl-input {
    background: rgba(30,41,59,0.8);
    border: 1px solid rgba(51,65,85,0.5);
    color: #f1f5f9;
}
.tbl-input::placeholder { color: #475569; }

/* ── PAGINATION ── */
.pagination {
    display: flex; list-style: none; gap: 4px;
    background: rgba(15,23,42,0.8);
    border: 1px solid rgba(51,65,85,0.4);
    border-radius: 10px; padding: 6px;
}
.page-link, .page-active {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 34px; height: 34px; padding: 0 12px;
    font-size: 12px; border-radius: 8px;
    border: 1px solid transparent;
    transition: all .2s ease; text-decoration: none;
    color: #94a3b8;
}
.page-link:hover { background: rgba(56,189,248,.15); color: #38bdf8; border-color: rgba(56,189,248,.3); }
.page-active { background: rgba(56,189,248,.25); color: #38bdf8; border-color: rgba(56,189,248,.5); font-weight: 600; cursor: default; }

/* ══════════════════════════════════════
   LIGHT THEME OVERRIDES
══════════════════════════════════════ */
html.light .sc-text  { color: #1e293b !important; }
html.light .sc-muted { color: #64748b !important; }

html.light .tbl-head { background: #f1f5f9 !important; color: #475569 !important; border-color: #e2e8f0 !important; }
html.light .tbl-row  { border-color: #e2e8f0 !important; background: #ffffff !important; }
html.light .tbl-row:hover { background: #f8fafc !important; }

html.light .badge-kategori { background: rgba(99,102,241,0.1) !important; color: #4338ca !important; border-color: rgba(99,102,241,0.25) !important; }
html.light .badge-sub      { background: rgba(6,182,212,0.1)  !important; color: #0e7490 !important; border-color: rgba(6,182,212,0.25)  !important; }
html.light .badge-menunggu { background: rgba(234,179,8,0.12) !important; color: #92400e !important; border-color: rgba(234,179,8,0.3)   !important; }
html.light .badge-progress { background: rgba(6,182,212,0.1)  !important; color: #0e7490 !important; border-color: rgba(6,182,212,0.25)  !important; }
html.light .badge-selesai  { background: rgba(34,197,94,0.1)  !important; color: #15803d !important; border-color: rgba(34,197,94,0.25)  !important; }
html.light .badge-batal    { background: rgba(239,68,68,0.1)  !important; color: #b91c1c !important; border-color: rgba(239,68,68,0.25)  !important; }

html.light .fltr-input {
    background: #ffffff !important; border-color: #cbd5e1 !important; color: #0f172a !important;
}
html.light .fltr-input::placeholder { color: #94a3b8 !important; }
html.light .fltr-input:focus { border-color: #0891b2 !important; box-shadow: 0 0 0 1px #0891b2 !important; }

html.light .fltr-select-wrap select.fltr-select {
    background: #ffffff !important; border-color: #cbd5e1 !important; color: #0f172a !important;
}
html.light .fltr-chevron { color: #64748b !important; }

html.light .tbl-select-wrap select.tbl-select {
    background: #ffffff !important; border-color: #cbd5e1 !important; color: #0f172a !important;
}
html.light .tbl-chevron { color: #64748b !important; }

html.light .tbl-input {
    background: #ffffff !important; border-color: #cbd5e1 !important; color: #0f172a !important;
}
html.light .tbl-input::placeholder { color: #94a3b8 !important; }

html.light .pagination {
    background: #ffffff !important;
    border-color: #e2e8f0 !important;
}
html.light .page-link {
    color: #475569 !important;
    background: #f1f5f9 !important;
    border-color: #e2e8f0 !important;
}
html.light .page-link:hover {
    background: #e0f2fe !important;
    color: #0e7490 !important;
    border-color: #bae6fd !important;
}
html.light .page-active {
    background: #0e7490 !important;
    color: #ffffff !important;
    border-color: #0e7490 !important;
}

/* SweetAlert */
.swal2-popup { background-color: var(--bg-card) !important; border: 2px solid var(--border-color) !important; color: var(--text-primary) !important; }
html.light .swal2-popup { background-color: #ffffff !important; border-color: rgba(226,232,240,0.8) !important; }
.swal2-title { color: var(--text-primary) !important; }
.swal2-html-container { color: var(--text-secondary) !important; }

/* Misc */
body { opacity: 0; animation: fadeIn 0.3s ease-in forwards; }
@keyframes fadeIn { to { opacity: 1; } }
table.table-fixed { table-layout: fixed; }
table.table-fixed td { overflow: hidden; text-overflow: ellipsis; }
</style>

<!-- ================= SCRIPTS ================= -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function getSwalColors() {
    const isLight = document.documentElement.classList.contains('light');
    return { background: isLight ? '#ffffff' : '#0e141b', color: isLight ? '#0f172a' : '#fff' };
}

/* ── DETAIL MODAL ── */
function openDetailModal(id) {
    fetch("<?= base_url('ticket/detail/') ?>" + id)
        .then(r => r.text()).then(h => {
            document.getElementById('modal-content').innerHTML = h;
            document.getElementById('modal').classList.replace('hidden','flex');
        });
}
function closeModal() {
    document.getElementById('modal').classList.replace('flex','hidden');
}

/* ── FORM MODAL ── */
function closeFormModal() {
    document.getElementById('form-modal').classList.replace('flex','hidden');
    document.getElementById('form-modal-content').innerHTML = '';
}

function openEditModal(id) {
    fetch("<?= base_url('ticket/edit/') ?>" + id, {
        headers: {'X-Requested-With':'XMLHttpRequest'}
    })
    .then(r => r.text()).then(res => {
        if (res.trim().startsWith('{')) {
            try {
                const d = JSON.parse(res);
                Swal.fire({ icon: d.status||'error', title: d.title, text: d.text,
                            confirmButtonColor:'#ef4444', ...getSwalColors() });
                return;
            } catch(e){}
        }
        document.getElementById('form-modal-content').innerHTML = res;
        document.getElementById('form-modal').classList.replace('hidden','flex');
    });
}

function openCreateModal() {
    fetch("<?= base_url('ticket/create') ?>", {
        headers: {'X-Requested-With':'XMLHttpRequest'}
    })
    .then(r => r.text()).then(res => {
        if (res.trim().startsWith('{')) {
            try {
                const d = JSON.parse(res);
                Swal.fire({ icon: d.status||'error', title: d.title, text: d.text,
                            confirmButtonColor:'#ef4444', ...getSwalColors() });
                return;
            } catch(e){}
        }
        document.getElementById('form-modal-content').innerHTML = res;
        document.getElementById('form-modal').classList.replace('hidden','flex');
    });
}

/* ── FILTER ── */
let typingTimer;
function autoSubmit() { document.getElementById('filterForm').submit(); }
document.querySelector('input[name="q"]').addEventListener('keyup', function() {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(() => document.getElementById('filterForm').submit(), 500);
});

/* ── EVENT DELEGATION — semua tombol di dalam modal ── */
document.addEventListener('click', function(e) {
    const C = getSwalColors();

    /* ── Tombol X (close edit modal) ── */
    if (e.target.closest('#btnClose')) {
        closeFormModal();
        return;
    }

    /* ── Tombol Batal (edit) ── */
    if (e.target.closest('#btnCancel')) {
        e.preventDefault();
        Swal.fire({
            title: 'Batalkan perubahan?', text: 'Perubahan tidak akan disimpan',
            icon: 'warning', showCancelButton: true,
            confirmButtonText: 'Ya, batal', cancelButtonText: 'Lanjut edit',
            confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280',
            reverseButtons: true, ...C
        }).then(r => { if (r.isConfirmed) closeFormModal(); });
        return;
    }

    /* ── Tombol Simpan (edit) ── */
    if (e.target.closest('#btnSave')) {
        e.preventDefault();
        const form = document.getElementById('editForm');
        if (!form) return;
        Swal.fire({
            title: 'Simpan perubahan?', text: 'Status & data tiket akan diperbarui',
            icon: 'question', showCancelButton: true,
            confirmButtonText: 'Ya, simpan', cancelButtonText: 'Batal',
            confirmButtonColor: '#06b6d4', cancelButtonColor: '#6b7280',
            reverseButtons: true, ...C
        }).then(r => {
            if (!r.isConfirmed) return;
            Swal.fire({ title: 'Menyimpan...', html: 'Mohon tunggu sebentar',
                        allowOutsideClick: false, didOpen: () => Swal.showLoading(), ...C });
            fetch(form.action, { method: 'POST', body: new FormData(form) })
                .then(res => res.json().catch(() => ({})))
                .then(json => {
                    if (json.status === 'error') {
                        Swal.fire({ icon:'error', title:'Gagal',
                                    text: json.message || 'Terjadi kesalahan', ...C });
                    } else {
                        Swal.fire({
                            icon: 'success', title: 'Tersimpan!',
                            text: 'Tiket berhasil diperbarui.',
                            timer: 1500, showConfirmButton: false, ...C
                        }).then(() => { closeFormModal(); location.reload(); });
                    }
                })
                .catch(() => { closeFormModal(); location.reload(); });
        });
        return;
    }

    /* ── Tombol Batal (create) ── */
    if (e.target.closest('#btnCreateCancel')) {
        Swal.fire({
            title: 'Batalkan?', text: 'Data tiket tidak akan disimpan',
            icon: 'warning', showCancelButton: true,
            confirmButtonText: 'Ya', cancelButtonText: 'Tidak',
            confirmButtonColor: '#ef4444', ...C
        }).then(r => { if (r.isConfirmed) closeFormModal(); });
        return;
    }

    /* ── Tombol Simpan (create) ── */
    if (e.target.closest('#btnCreateSave')) {
        const form = document.getElementById('createForm');
        if (!form) return;
        if (!form.checkValidity()) { form.reportValidity(); return; }
        Swal.fire({
            title: 'Simpan tiket?', text: 'Tiket akan dibuat',
            icon: 'question', showCancelButton: true,
            confirmButtonText: 'Simpan', cancelButtonText: 'Batal',
            confirmButtonColor: '#06b6d4', ...C
        }).then(r => {
            if (!r.isConfirmed) return;
            Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false,
                        didOpen: () => Swal.showLoading(), ...C });
            fetch(form.action, { method: 'POST', body: new FormData(form) })
                .then(() => { closeFormModal(); location.reload(); });
        });
        return;
    }

    /* ── Klik backdrop form-modal (tutup modal) ── */
    if (e.target.id === 'form-modal') {
        closeFormModal();
        return;
    }
});

/* ── Toggle wrapSelesaiAt (status Selesai) — pakai delegation ── */
document.addEventListener('change', function(e) {
    /* Status select di edit modal */
    if (e.target.id === 'editStatus') {
        const wrap = document.getElementById('wrapSelesaiAt');
        if (wrap) wrap.classList.toggle('hidden', e.target.value !== 'Selesai');
        return;
    }

    /* Durasi rapat */
    if (e.target.id === 'durasiRapatEdit') {
        hitungSelesaiEdit();
        return;
    }

    /* Snack corner */
    if (e.target.id === 'editSnackCorner') {
        const detail = document.getElementById('editSnackDetail');
        if (detail) detail.classList.toggle('hidden', e.target.value !== 'Ya');
        return;
    }

    /* Filter kategori → sub kategori (create modal) */
    if (e.target.id === 'kategoriSelect') {
        const sub = document.getElementById('subKategoriSelect');
        if (!sub) return;
        sub.innerHTML = '<option value="">Sub Kategori</option>';
        if (!e.target.value) { sub.disabled = true; return; }
        const data = <?= json_encode($master_sub_kategori) ?>;
        data.filter(sk => sk.kategori_id == e.target.value).forEach(sk => {
            const o = document.createElement('option');
            o.value = sk.id; o.textContent = sk.nama_sub_kategori;
            sub.appendChild(o);
        });
        sub.disabled = false;
        return;
    }
});

/* ── hitungSelesaiEdit — global supaya bisa dipanggil onchange inline ── */
function hitungSelesaiEdit() {
    const jam    = document.querySelector('[name="jam_mulai"]')?.value;
    const durasi = parseInt(document.getElementById('durasiRapatEdit')?.value);
    const box    = document.getElementById('editInfoSelesai');
    const hidden = document.getElementById('editJamSelesai');
    const tampil = document.getElementById('editTampilSelesai');
    if (!jam || !durasi || !box || !hidden) return;
    const [h, m] = jam.split(':').map(Number);
    const total  = h * 60 + m + durasi;
    const C = getSwalColors();
    if (total > 17 * 60) {
        Swal.fire({ icon: 'warning', title: 'Melebihi jam kerja (17:00)',
                    confirmButtonColor: '#4338ca', ...C });
        document.getElementById('durasiRapatEdit').value = '';
        box.classList.add('hidden');
        hidden.value = '';
        return;
    }
    const hasil = String(Math.floor(total/60)).padStart(2,'0') + ':' + String(total%60).padStart(2,'0');
    hidden.value = hasil;
    tampil.textContent = hasil;
    box.classList.remove('hidden');
}

/* ── toggleEditSnack — global supaya bisa dipanggil onchange inline ── */
function toggleEditSnack(val) {
    const detail = document.getElementById('editSnackDetail');
    if (detail) detail.classList.toggle('hidden', val !== 'Ya');
}

/* ── Auto-show date/time picker ── */
document.addEventListener('focusin', function(e) {
    if ((e.target.type === 'date' || e.target.type === 'time') && e.target.showPicker) {
        e.target.showPicker();
    }
});
</script>

<?php if ($this->session->flashdata('swal')): $swal = $this->session->flashdata('swal'); ?>
<script>
Swal.fire({
    icon: '<?= $swal['icon'] ?>',
    title: '<?= $swal['title'] ?>',
    text: '<?= $swal['text'] ?>',
    confirmButtonColor: '#ef4444',
    ...getSwalColors()
});
</script>
<?php endif; ?>