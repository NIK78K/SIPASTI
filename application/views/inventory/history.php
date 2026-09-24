<div class="hst-wrap">
 
    <!-- HEADER INFO PERANGKAT -->
    <div class="hst-perangkat-info">
        <div class="hst-pinfo-label">Perangkat</div>
        <div class="hst-pinfo-nama"><?= htmlspecialchars($p['nama_barang']) ?> — <?= htmlspecialchars($p['merk']) ?></div>
        <div class="hst-pinfo-sub">
            Kode: <span class="font-mono"><?= $p['kode_barang'] ?></span>
            &nbsp;·&nbsp; NUP: <span class="font-mono"><?= $p['nup'] ?></span>
        </div>
    </div>
 
    <?php if (empty($history)): ?>
    <!-- EMPTY STATE -->
    <div class="hst-empty">
        <svg class="hst-empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div class="hst-empty-title">Belum ada riwayat perubahan</div>
        <div class="hst-empty-sub">Setiap perubahan data akan tercatat di sini secara otomatis</div>
    </div>
 
    <?php else: ?>
 
    <!-- SUMMARY BADGE -->
    <div class="hst-summary">
        <span class="hst-badge-count"><?= count($history) ?> perubahan tercatat</span>
    </div>
 
    <!-- TIMELINE -->
    <div class="hst-timeline">
        <?php
        // Group history by tanggal + diubah_oleh (supaya tampil per sesi edit)
        $grouped = [];
        foreach ($history as $h) {
            $key = date('Y-m-d H:i', strtotime($h['diubah_pada'])) . '|' . $h['diubah_oleh'];
            $grouped[$key][] = $h;
        }
 
        $colors = ['hst-dot-blue','hst-dot-green','hst-dot-purple','hst-dot-orange','hst-dot-red'];
        $colorIdx = 0;
        ?>
 
        <?php foreach ($grouped as $key => $items): ?>
            <?php
            [$waktu_str, $editor] = explode('|', $key, 2);
            $waktu_dt = new DateTime($items[0]['diubah_pada']);
            $tgl_indo = $waktu_dt->format('d') . ' ' .
                ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'][(int)$waktu_dt->format('m') - 1]
                . ' ' . $waktu_dt->format('Y') . ', ' . $waktu_dt->format('H:i');
            $dotClass = $colors[$colorIdx % count($colors)];
            $colorIdx++;
            ?>
 
            <div class="hst-group">
                <!-- WAKTU & EDITOR -->
                <div class="hst-group-header">
                    <span class="hst-dot <?= $dotClass ?>"></span>
                    <div class="hst-group-meta">
                        <span class="hst-group-time"><?= $tgl_indo ?></span>
                        <span class="hst-group-by">oleh <strong><?= htmlspecialchars($editor) ?></strong></span>
                    </div>
                    <span class="hst-badge-fields"><?= count($items) ?> field</span>
                </div>
 
                <!-- DAFTAR PERUBAHAN -->
                <div class="hst-items">
                    <?php foreach ($items as $item): ?>
                    <div class="hst-item">
                        <div class="hst-item-label"><?= htmlspecialchars($item['label_field']) ?></div>
                        <div class="hst-item-change">
                            <div class="hst-val hst-val-old">
                                <span class="hst-val-tag hst-tag-old">Sebelum</span>
                                <span class="hst-val-text">
                                    <?= ($item['nilai_lama'] !== '' && $item['nilai_lama'] !== null)
                                        ? htmlspecialchars($item['nilai_lama'])
                                        : '<em class="hst-empty-val">—kosong—</em>' ?>
                                </span>
                            </div>
                            <div class="hst-arrow">→</div>
                            <div class="hst-val hst-val-new">
                                <span class="hst-val-tag hst-tag-new">Sesudah</span>
                                <span class="hst-val-text">
                                    <?= ($item['nilai_baru'] !== '' && $item['nilai_baru'] !== null)
                                        ? htmlspecialchars($item['nilai_baru'])
                                        : '<em class="hst-empty-val">—kosong—</em>' ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach ?>
                </div>
            </div>
 
        <?php endforeach ?>
    </div>
    <?php endif ?>
 
</div>
 
<style>
/* ── DARK (default) ── */
.hst-wrap             { padding: 2px 0; }
.hst-perangkat-info   { background: rgba(30,41,59,0.8); border: 1px solid rgba(51,65,85,0.5);
                        border-radius: 8px; padding: 10px 14px; margin-bottom: 14px; }
.hst-pinfo-label      { font-size: 10px; color: #64748b; text-transform: uppercase; letter-spacing: .06em; margin-bottom: 2px; }
.hst-pinfo-nama       { font-size: 13px; font-weight: 600; color: #e2e8f0; }
.hst-pinfo-sub        { font-size: 10px; color: #94a3b8; margin-top: 2px; }
 
/* EMPTY */
.hst-empty            { text-align: center; padding: 40px 20px; }
.hst-empty-icon       { width: 48px; height: 48px; color: #4b5563; margin: 0 auto 10px; display: block; }
.hst-empty-title      { font-size: 13px; font-weight: 600; color: #6b7280; }
.hst-empty-sub        { font-size: 11px; color: #4b5563; margin-top: 4px; }
 
/* SUMMARY */
.hst-summary          { margin-bottom: 12px; }
.hst-badge-count      { font-size: 10px; background: rgba(59,130,246,0.12); color: #60a5fa;
                        border: 1px solid rgba(59,130,246,0.25); border-radius: 20px;
                        padding: 2px 10px; font-weight: 600; }
 
/* TIMELINE */
.hst-timeline         { display: flex; flex-direction: column; gap: 14px; }
 
/* GROUP */
.hst-group            { position: relative; }
.hst-group-header     { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; }
.hst-dot              { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; box-shadow: 0 0 6px currentColor; }
.hst-dot-blue         { background: #3b82f6; color: #3b82f6; }
.hst-dot-green        { background: #10b981; color: #10b981; }
.hst-dot-purple       { background: #8b5cf6; color: #8b5cf6; }
.hst-dot-orange       { background: #f59e0b; color: #f59e0b; }
.hst-dot-red          { background: #ef4444; color: #ef4444; }
 
.hst-group-meta       { flex: 1; display: flex; flex-wrap: wrap; gap: 6px; align-items: center; }
.hst-group-time       { font-size: 11px; font-weight: 600; color: #94a3b8; }
.hst-group-by         { font-size: 11px; color: #64748b; }
.hst-group-by strong  { color: #cbd5e1; }
.hst-badge-fields     { font-size: 9px; background: rgba(51,65,85,0.6); color: #94a3b8;
                        border-radius: 10px; padding: 1px 7px; border: 1px solid rgba(51,65,85,0.8); }
 
/* ITEMS */
.hst-items            { margin-left: 18px; border-left: 1px solid rgba(51,65,85,0.4);
                        padding-left: 14px; display: flex; flex-direction: column; gap: 6px; }
 
.hst-item             { background: rgba(15,23,42,0.5); border: 1px solid rgba(51,65,85,0.3);
                        border-radius: 6px; padding: 8px 10px; }
.hst-item-label       { font-size: 10px; font-weight: 700; color: #64748b;
                        text-transform: uppercase; letter-spacing: .05em; margin-bottom: 6px; }
.hst-item-change      { display: flex; align-items: flex-start; gap: 8px; flex-wrap: wrap; }
 
.hst-val              { flex: 1; min-width: 100px; }
.hst-val-tag          { display: inline-block; font-size: 9px; font-weight: 700;
                        border-radius: 4px; padding: 1px 6px; margin-bottom: 3px;
                        letter-spacing: .04em; text-transform: uppercase; }
.hst-tag-old          { background: rgba(239,68,68,0.12); color: #f87171;
                        border: 1px solid rgba(239,68,68,0.2); }
.hst-tag-new          { background: rgba(16,185,129,0.12); color: #34d399;
                        border: 1px solid rgba(16,185,129,0.2); }
 
.hst-val-text         { font-size: 11px; display: block; word-break: break-word;
                        line-height: 1.4; }
.hst-val-old .hst-val-text  { color: #94a3b8; text-decoration: line-through; }
.hst-val-new .hst-val-text  { color: #e2e8f0; font-weight: 500; }
.hst-empty-val        { color: #4b5563; font-style: italic; }
 
.hst-arrow            { font-size: 16px; color: #374151; flex-shrink: 0; padding-top: 14px; }
 
/* ── LIGHT OVERRIDES ── */
html.light .hst-perangkat-info { background: #f8fafc; border-color: #e2e8f0; }
html.light .hst-pinfo-label    { color: #94a3b8; }
html.light .hst-pinfo-nama     { color: #0f172a; }
html.light .hst-pinfo-sub      { color: #64748b; }
html.light .hst-empty-title    { color: #94a3b8; }
html.light .hst-empty-sub      { color: #cbd5e1; }
html.light .hst-empty-icon     { color: #cbd5e1; }
html.light .hst-group-time     { color: #475569; }
html.light .hst-group-by       { color: #94a3b8; }
html.light .hst-group-by strong { color: #1e293b; }
html.light .hst-badge-fields   { background: #f1f5f9; color: #94a3b8; border-color: #e2e8f0; }
html.light .hst-items          { border-color: #e2e8f0; }
html.light .hst-item           { background: #ffffff; border-color: #e2e8f0; }
html.light .hst-item-label     { color: #94a3b8; }
html.light .hst-val-old .hst-val-text { color: #94a3b8; }
html.light .hst-val-new .hst-val-text { color: #0f172a; }
html.light .hst-arrow          { color: #cbd5e1; }
html.light .hst-empty-val      { color: #cbd5e1; }
</style>