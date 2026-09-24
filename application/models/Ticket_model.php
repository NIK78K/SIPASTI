<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ticket_model extends CI_Model {

    /* ===============================
       FILTER & LISTING
    =============================== */
    public function filter($q = null, $status = null)
    {
        $this->db->select('t.*, p.nama_barang AS nama_perangkat, u.name AS nama_teknisi');
        $this->db->from('tickets t');
        $this->db->join('perangkat p', 'p.id = t.perangkat_id', 'left');
        $this->db->join('teknisi tek', 'tek.id = t.teknisi_id', 'left');
        $this->db->join('users u',     'u.id = tek.user_id',    'left');

        if ($q) {
            $this->db->group_start()
                     ->like('t.kode_tiket', $q)
                     ->or_like('t.pelapor', $q)
                     ->group_end();
        }
        if ($status) $this->db->where('t.status', $status);

        return $this->db->get()->result_array();
    }

    public function count_status($status)
    {
        return $this->db->where('status', $status)->count_all_results('tickets');
    }

    public function get_by_id($id)
    {
        return $this->db
            ->select('t.*, p.nama_barang AS nama_perangkat, ut.name AS nama_teknisi')
            ->from('tickets t')
            ->join('perangkat p',  'p.id = t.perangkat_id',  'left')
            ->join('teknisi tek',  'tek.id = t.teknisi_id',  'left')
            ->join('users ut',     'ut.id = tek.user_id',    'left')
            ->where('t.id', $id)
            ->get()
            ->row_array();
    }

    /* ===============================
       UPDATE TIKET + EMAIL
    =============================== */
    public function update($id, $data)
{
    $ticket = $this->db
        ->select('
            t.kode_tiket, t.jenis_kerusakan, t.kategori_id, t.sub_kategori_id,
            t.layanan_link, t.layanan_catatan, t.tanggal, t.tanggal_selesai,
            t.jam, t.durasi, t.status, t.teknisi_id, t.akun_zoom,
            u.name, u.email,
            tek_user.name AS nama_teknisi
        ')
        ->from('tickets t')
        ->join('users u', 'u.id = t.user_id')
        ->join('teknisi tek', 'tek.id = t.teknisi_id', 'left')
        ->join('users tek_user', 'tek_user.id = tek.user_id', 'left')
        ->where('t.id', $id)
        ->get()->row_array();

    if (!$ticket) return false;

    if (isset($data['status'])) {
        $map = [
            'Diproses'    => 'On Progress',
            'Menunggu'    => 'Menunggu',
            'On Progress' => 'On Progress',
            'Selesai'     => 'Selesai',
            'Dibatalkan'  => 'Dibatalkan',
        ];
        $data['status'] = $map[$data['status']] ?? 'Menunggu';
    }

    $this->db->where('id', $id)->update('tickets', $data);
    $ticket = array_merge($ticket, $data);

    $this->load->helper('email');

    // Deteksi ruang rapat
    $isRuangRapat = strpos($ticket['jenis_kerusakan'] ?? '', '[ref:peminjaman_id=') !== false;

    /* ── EMAIL ON PROGRESS ── */
    if (($data['status'] ?? '') === 'On Progress') {

        $kategori_slug = null;
        if (!empty($ticket['kategori_id'])) {
            $kat = $this->db->select('slug')->where('id', $ticket['kategori_id'])
                            ->get('master_kategori')->row_array();
            if ($kat) $kategori_slug = $kat['slug'];
        }

  if ($isRuangRapat) {

    // ★ Kunci ruangan: update status peminjaman ke Disetujui
    if (preg_match('/\[ref:peminjaman_id=(\d+)\]/', $ticket['jenis_kerusakan'], $m_ref)) {
        $this->db->where('id', (int)$m_ref[1])
                 ->update('peminjaman_ruang_rapat', ['status' => 'Disetujui']);
    }

    send_ticket_email(
        $ticket['email'],
        "[SIPASTI] {$ticket['kode_tiket']} - Peminjaman Ruang Rapat Dikonfirmasi",
        $this->email_template_on_progress_ruang_rapat($ticket)
    );
    $this->_auto_cancel_conflicting_ruang_rapat($id, $ticket['jenis_kerusakan'], $ticket);
} elseif ($kategori_slug === 'permintaan' || $kategori_slug === 'layanan') {
            if (!empty($ticket['layanan_link'])) {
                send_ticket_email(
                    $ticket['email'],
                    "[SIPASTI] {$ticket['kode_tiket']} - Layanan IT Dimulai",
                    $this->email_template_on_progress_layanan($ticket)
                );
            } else {
                send_ticket_email(
                    $ticket['email'],
                    "[SIPASTI] {$ticket['kode_tiket']} - Sedang Diproses",
                    $this->email_template_on_progress($ticket)
                );
            }
        } else {
            send_ticket_email(
                $ticket['email'],
                "[SIPASTI] {$ticket['kode_tiket']} - Sedang Diproses",
                $this->email_template_on_progress($ticket)
            );
        }
    }

    /* ── EMAIL SELESAI ── */
    if (($data['status'] ?? '') === 'Selesai') {
        if ($isRuangRapat) {
    if (preg_match('/\[ref:peminjaman_id=(\d+)\]/', $ticket['jenis_kerusakan'], $m_ref)) {
        $this->db->where('id', (int)$m_ref[1])
                 ->update('peminjaman_ruang_rapat', [
                     'status'  => 'Selesai',
                     'selesai' => 'Ya',
                 ]);
    }
}
        send_ticket_email(
            $ticket['email'],
            "[SIPASTI] {$ticket['kode_tiket']} - Telah Diselesaikan",
            $this->email_template_selesai($ticket)
        );
    }

    return true;
}
    /* ===============================
       HELPER: PARSE ZOOM DETAIL dari jenis_kerusakan
    =============================== */
    private function _parse_zoom_detail($teks)
    {
        $r = [
            'detail_permintaan' => '',
            'jumlah_peserta'    => '',
            'jumlah_breakout'   => '',
            'penanggung_jawab'  => '',
            'host_cohost'       => '',
            'permintaan_lain'   => '',
            'periode'           => '',
            'tanggal_teks'      => '',
            'jam_per_hari'      => '',
            'durasi_per_hari'   => '',
            'total_durasi'      => '',
            'lokasi_unit'       => '',
        ];
        if (empty($teks)) return $r;

        $lines = explode("\n", $teks);
        $i = 0; $max = count($lines);

        while ($i < $max) {
            $line = trim($lines[$i]);
            if      (strpos($line, 'Detail Permintaan:')    === 0) $r['detail_permintaan'] = trim(substr($line, 18));
            elseif  (strpos($line, 'Periode:')              === 0) $r['periode']           = trim(substr($line, 8));
            elseif  (strpos($line, 'Tanggal:')              === 0) $r['tanggal_teks']      = trim(substr($line, 8));
            elseif  (strpos($line, 'Jam Mulai (per hari):') === 0) $r['jam_per_hari']      = trim(substr($line, 21));
            elseif  (strpos($line, 'Durasi (per hari):')    === 0) $r['durasi_per_hari']   = trim(substr($line, 18));
            elseif  (strpos($line, 'Total Durasi:')         === 0) $r['total_durasi']      = trim(substr($line, 13));
            elseif  (strpos($line, 'Jumlah Peserta:')       === 0) $r['jumlah_peserta']    = trim(str_replace([' orang', 'Jumlah Peserta:'], '', $line));
            elseif  (strpos($line, 'Jumlah Breakout Room:') === 0) $r['jumlah_breakout']   = trim(substr($line, 21));
            elseif  (strpos($line, 'Penanggung Jawab:')     === 0) $r['penanggung_jawab']  = trim(substr($line, 17));
            elseif  (strpos($line, 'Host/Co-Host:')         === 0) $r['host_cohost']       = trim(substr($line, 13));
            elseif  (strpos($line, 'Lokasi/Unit:')          === 0) $r['lokasi_unit']       = trim(substr($line, 12));
            elseif  (strpos($line, 'Permintaan Lainnya:')   === 0) {
                $buf = trim(substr($line, 19));
                $i++;
                while ($i < $max) {
                    $next = trim($lines[$i]);
                    if (preg_match('/^(Detail Permintaan|Periode|Tanggal|Jam Mulai|Durasi|Total Durasi|Jumlah Peserta|Jumlah Breakout|Penanggung Jawab|Host\/Co-Host|Lokasi\/Unit):/', $next)) break;
                    $buf .= ($buf ? "\n" : '') . $next;
                    $i++;
                }
                $r['permintaan_lain'] = $buf;
                continue;
            }
            $i++;
        }
        return $r;
    }

    /* ===============================
       HELPER: Hitung jam masuk (15 menit sebelum)
    =============================== */
    private function _jam_masuk_15_menit_sebelum($jam_str)
    {
        if (empty($jam_str)) return null;
        try {
            $dt = new DateTime($jam_str);
            $dt->modify('-15 minutes');
            return $dt->format('H:i');
        } catch (Exception $e) {
            return null;
        }
    }

    /* ===============================
       EMAIL TEMPLATE — ON PROGRESS (UMUM / NON-LAYANAN)
    =============================== */
    private function email_template_on_progress($ticket)
    {
        $kategori     = $this->db->get_where('master_kategori',     ['id' => $ticket['kategori_id']])->row();
        $sub_kategori = $this->db->get_where('master_sub_kategori', ['id' => $ticket['sub_kategori_id']])->row();

        $kategori_nama     = $kategori     ? $kategori->nama_kategori         : '-';
        $sub_kategori_nama = $sub_kategori ? $sub_kategori->nama_sub_kategori : '-';
        $deskripsi         = nl2br(htmlspecialchars($ticket['jenis_kerusakan']));

        return '
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"></head>
<body style="margin:0;padding:0;background-color:#f3f4f6;font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,\'Helvetica Neue\',Arial,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f4f6;padding:40px 20px;">
        <tr><td align="center">
            <table width="100%" style="max-width:600px;" cellpadding="0" cellspacing="0">

                <!-- HEADER -->
                <tr>
                    <td style="background:linear-gradient(135deg,#0891b2 0%,#0e7490 100%);padding:40px 30px;border-radius:12px 12px 0 0;">
                        <div style="font-size:48px;margin-bottom:10px;">⚙️</div>
                        <h1 style="margin:0;color:#ffffff;font-size:28px;font-weight:bold;">Tiket Sedang Diproses</h1>
                        <p style="margin:12px 0 0 0;color:#cffafe;font-size:15px;">Tim teknis kami sedang menangani laporan Anda</p>
                    </td>
                </tr>

                <!-- CONTENT -->
                <tr>
                    <td style="background-color:#ffffff;">
                        <table width="100%" cellpadding="0" cellspacing="0" style="padding:30px 30px 20px 30px;">
                            <tr>
                                <td style="color:#1f2937;font-size:15px;line-height:1.6;">
                                    <p style="margin:0 0 16px 0;">Yth. <strong>' . $ticket['name'] . '</strong>,</p>
                                    <p style="margin:0;">Tiket Anda dengan kode <strong style="color:#0891b2;">' . $ticket['kode_tiket'] . '</strong> sedang dalam proses penanganan oleh tim IT kami.</p>
                                </td>
                            </tr>
                        </table>

                        <table width="100%" cellpadding="0" cellspacing="0" style="background:linear-gradient(135deg,#dbeafe 0%,#bfdbfe 100%);padding:20px 30px;border-top:3px solid #0891b2;border-bottom:3px solid #0891b2;">
                            <tr><td align="center">
                                <span style="display:inline-block;padding:10px 24px;background:#0891b2;color:#ffffff;border-radius:25px;font-size:14px;font-weight:700;">⚙️ SEDANG DIPROSES</span>
                            </td></tr>
                        </table>

                        <table width="100%" cellpadding="0" cellspacing="0" style="padding:30px;">
                            <tr><td colspan="2" style="padding-bottom:20px;">
                                <h3 style="margin:0;color:#0891b2;font-size:18px;border-bottom:2px solid #e5e7eb;padding-bottom:10px;">📋 Detail Tiket</h3>
                            </td></tr>
                            <tr>
                                <td style="padding:10px;width:160px;vertical-align:top;color:#6b7280;"><strong>🎫 Kode Tiket</strong></td>
                                <td style="padding:10px;color:#1f2937;"><strong style="color:#0891b2;font-size:16px;">' . $ticket['kode_tiket'] . '</strong></td>
                            </tr>
                            <tr>
                                <td style="padding:10px;width:160px;vertical-align:top;color:#6b7280;"><strong>📂 Kategori</strong></td>
                                <td style="padding:10px;color:#1f2937;">' . $kategori_nama . '</td>
                            </tr>
                            <tr>
                                <td style="padding:10px;width:160px;vertical-align:top;color:#6b7280;"><strong>🏷️ Sub Kategori</strong></td>
                                <td style="padding:10px;color:#1f2937;">' . $sub_kategori_nama . '</td>
                            </tr>
                            <tr><td colspan="2" style="padding:20px 10px;"><div style="border-top:2px solid #e5e7eb;"></div></td></tr>
                            <tr><td colspan="2" style="padding:10px;">
                                <strong style="color:#6b7280;font-size:14px;">📝 DESKRIPSI MASALAH:</strong>
                            </td></tr>
                            <tr>
                                <td colspan="2" style="padding:10px;background:#f9fafb;border-left:4px solid #0891b2;border-radius:6px;">
                                    <div style="color:#374151;line-height:1.7;font-size:14px;">' . $deskripsi . '</div>
                                </td>
                            </tr>
                            <tr><td colspan="2" style="padding:20px 10px;"><div style="border-top:2px solid #e5e7eb;"></div></td></tr>
                            <tr>
                                <td colspan="2" style="padding:10px;">
                                    <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:16px;">
                                        <p style="margin:0 0 8px 0;color:#1e40af;font-size:14px;font-weight:600;">ℹ️ Informasi Penting:</p>
                                        <ul style="margin:0;padding-left:20px;color:#1f2937;font-size:13px;line-height:1.8;">
                                            <li>Tim teknis kami sedang menangani tiket Anda</li>
                                            <li>Anda akan mendapat notifikasi saat tiket selesai ditangani</li>
                                            <li>Jika ada pertanyaan, hubungi helpdesk kami</li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- FOOTER -->
                <tr>
                    <td style="background-color:#1f2937;padding:30px;border-radius:0 0 12px 12px;text-align:center;">
                        <p style="margin:0 0 8px 0;color:#ffffff;font-size:15px;font-weight:bold;">SIPASTI</p>
                        <p style="margin:0 0 15px 0;color:#9ca3af;font-size:13px;line-height:1.6;">
                            Sistem Informasi Pengelolaan Aset dan Layanan TI<br>
                            <strong style="color:#cbd5e1;">BPS Provinsi Riau</strong>
                        </p>
                        <div style="border-top:1px solid #374151;padding-top:15px;margin-top:15px;">
                            <p style="margin:0;color:#9ca3af;font-size:12px;">Email ini dikirim secara otomatis oleh sistem.<br>Mohon tidak membalas email ini.</p>
                        </div>
                    </td>
                </tr>

            </table>
        </td></tr>
    </table>
</body>
</html>';
    }

    /* ===============================
       EMAIL TEMPLATE — ON PROGRESS LAYANAN (ZOOM/MEET)
       Versi diperkaya: info host/cohost, 15 menit sebelum, multi-day
    =============================== */
    private function email_template_on_progress_layanan($ticket)
    {
        $kategori     = $this->db->get_where('master_kategori',     ['id' => $ticket['kategori_id']])->row();
        $sub_kategori = $this->db->get_where('master_sub_kategori', ['id' => $ticket['sub_kategori_id']])->row();

        $kategori_nama     = $kategori     ? $kategori->nama_kategori         : '-';
        $sub_kategori_nama = $sub_kategori ? $sub_kategori->nama_sub_kategori : '-';

        /* ── Parse detail zoom dari jenis_kerusakan ── */
        $zoom = $this->_parse_zoom_detail($ticket['jenis_kerusakan'] ?? '');

        /* ── Jam layanan & 15 menit sebelumnya ── */
        $jam_layanan = $ticket['jam'] ?? ($zoom['jam_per_hari'] ?? '');
        $jam_masuk   = $this->_jam_masuk_15_menit_sebelum($jam_layanan);

        /* ── Label periode ── */
        $periode_label = '';
        if (!empty($ticket['tanggal'])) {
            if (!empty($ticket['tanggal_selesai'])) {
                $tM            = new DateTime($ticket['tanggal']);
                $tS            = new DateTime($ticket['tanggal_selesai']);
                $jumlah_hari   = $tS->diff($tM)->days + 1;
                $periode_label = date('d F Y', strtotime($ticket['tanggal']))
                    . ' s.d. ' . date('d F Y', strtotime($ticket['tanggal_selesai']))
                    . ' (' . $jumlah_hari . ' hari)';
            } else {
                $periode_label = date('d F Y', strtotime($ticket['tanggal']));
            }
        } elseif (!empty($zoom['periode'])) {
            $periode_label = $zoom['periode'];
        } elseif (!empty($zoom['tanggal_teks'])) {
            $periode_label = $zoom['tanggal_teks'];
        }

        /* ── Nama teknisi ── */
        $nama_teknisi = !empty($ticket['nama_teknisi']) ? $ticket['nama_teknisi'] : 'Tim IT';

        /* ── Akun Zoom ── */
        $akun_zoom_label = !empty($ticket['akun_zoom']) ? 'Zoom ' . $ticket['akun_zoom'] . ' (BPS Riau)' : '';

        /* ── Blok host/cohost ── */
        $host_html = '';
        if (!empty($zoom['host_cohost'])) {
            $host_html = '
                <tr>
                    <td style="padding:8px 0;width:180px;color:#6b7280;font-size:14px;"><strong>👤 Host / Co-Host:</strong></td>
                    <td style="padding:8px 0;color:#1f2937;font-size:14px;"><strong>' . htmlspecialchars($zoom['host_cohost']) . '</strong></td>
                </tr>';
        }

        /* ── Blok penanggung jawab ── */
        $pj_html = '';
        if (!empty($zoom['penanggung_jawab'])) {
            $pj_html = '
                <tr>
                    <td style="padding:8px 0;width:180px;color:#6b7280;font-size:14px;"><strong>👔 Penanggung Jawab:</strong></td>
                    <td style="padding:8px 0;color:#1f2937;font-size:14px;">' . htmlspecialchars($zoom['penanggung_jawab']) . '</td>
                </tr>';
        }

        /* ── Blok jumlah peserta & breakout ── */
        $peserta_html = '';
        if (!empty($zoom['jumlah_peserta'])) {
            $peserta_html .= '
                <tr>
                    <td style="padding:8px 0;width:180px;color:#6b7280;font-size:14px;"><strong>👥 Jumlah Peserta:</strong></td>
                    <td style="padding:8px 0;color:#1f2937;font-size:14px;">' . htmlspecialchars($zoom['jumlah_peserta']) . ' orang</td>
                </tr>';
        }
        if (!empty($zoom['jumlah_breakout']) && (int)$zoom['jumlah_breakout'] > 0) {
            $peserta_html .= '
                <tr>
                    <td style="padding:8px 0;width:180px;color:#6b7280;font-size:14px;"><strong>🔲 Breakout Room:</strong></td>
                    <td style="padding:8px 0;color:#1f2937;font-size:14px;">' . htmlspecialchars($zoom['jumlah_breakout']) . ' ruang</td>
                </tr>';
        }

        /* ── Blok permintaan lainnya ── */
        $permintaan_lain_html = '';
        if (!empty($zoom['permintaan_lain'])) {
            $permintaan_lain_html = '
                <tr>
                    <td colspan="2" style="padding:12px 0 0 0;">
                        <div style="background:#f0fdf4;border-left:4px solid #22c55e;border-radius:6px;padding:12px;">
                            <p style="margin:0 0 6px 0;color:#166534;font-size:13px;font-weight:600;">📋 Permintaan Tambahan:</p>
                            <p style="margin:0;color:#374151;font-size:13px;line-height:1.7;white-space:pre-line;">' . htmlspecialchars($zoom['permintaan_lain']) . '</p>
                        </div>
                    </td>
                </tr>';
        }

        /* ── Blok akun zoom ── */
        $akun_zoom_html = '';
        if ($akun_zoom_label) {
            $akun_zoom_html = '
                <tr>
                    <td style="padding:8px 0;width:180px;color:#6b7280;font-size:14px;"><strong>📹 Akun Zoom:</strong></td>
                    <td style="padding:8px 0;color:#1f2937;font-size:14px;">' . $akun_zoom_label . '</td>
                </tr>';
        }

        /* ── Blok catatan teknisi ── */
        $catatan_html = '';
        if (!empty($ticket['layanan_catatan'])) {
            $catatan_html = '
                <tr>
                    <td colspan="2" style="padding:12px 0 0 0;">
                        <div style="background:#fef3c7;border-left:4px solid #f59e0b;border-radius:6px;padding:16px;">
                            <p style="margin:0 0 8px 0;color:#92400e;font-size:14px;font-weight:600;">💬 Catatan dari Teknisi:</p>
                            <p style="margin:0;color:#1f2937;font-size:14px;line-height:1.6;">' . nl2br(htmlspecialchars($ticket['layanan_catatan'])) . '</p>
                        </div>
                    </td>
                </tr>';
        }

        /* ── Blok peringatan 15 menit (host/cohost) ── */
        $peringatan_15_html = '';
        if ($jam_masuk && !empty($periode_label)) {
            $target_masuk = !empty($zoom['host_cohost']) ? htmlspecialchars($zoom['host_cohost']) : 'Host / Co-Host';

            $peringatan_15_html = '
                <tr>
                    <td colspan="2" style="padding:16px 0 0 0;">
                        <div style="background:linear-gradient(135deg,#fef9c3 0%,#fef08a 30%,#fde68a 100%);
                                    border:2px solid #f59e0b;border-radius:10px;padding:18px 20px;">
                            <p style="margin:0 0 10px 0;color:#92400e;font-size:15px;font-weight:700;">
                                ⏰ Pengingat Penting untuk Host / Co-Host
                            </p>
                            <p style="margin:0 0 10px 0;color:#78350f;font-size:14px;line-height:1.7;">
                                <strong>' . $target_masuk . '</strong> diwajibkan untuk <strong>masuk ke dalam Zoom lebih awal</strong>
                                agar sesi dapat dimulai tepat waktu dan peserta dapat diterima masuk.
                            </p>
                            <table width="100%" cellpadding="0" cellspacing="0"
                                   style="background:rgba(255,255,255,0.6);border-radius:8px;padding:12px;margin-top:4px;">
                                <tr>
                                    <td style="padding:4px 12px;color:#92400e;font-size:13px;">📅 Tanggal Acara</td>
                                    <td style="padding:4px 12px;color:#1f2937;font-size:13px;font-weight:600;">' . $periode_label . '</td>
                                </tr>
                                <tr>
                                    <td style="padding:4px 12px;color:#92400e;font-size:13px;">🕐 Jam Mulai Acara</td>
                                    <td style="padding:4px 12px;color:#1f2937;font-size:13px;font-weight:600;">' . $jam_layanan . ' WIB</td>
                                </tr>
                                <tr style="background:rgba(239,68,68,0.1);border-radius:6px;">
                                    <td style="padding:8px 12px;color:#b91c1c;font-size:14px;font-weight:700;">⚠️ Host Masuk Paling Lambat</td>
                                    <td style="padding:8px 12px;color:#b91c1c;font-size:16px;font-weight:800;">' . $jam_masuk . ' WIB</td>
                                </tr>
                            </table>
                            <p style="margin:12px 0 0 0;color:#92400e;font-size:12px;line-height:1.6;font-style:italic;">
                                * Masuk 15 menit lebih awal diperlukan untuk mengaktifkan sesi, mengatur breakout room (jika ada),
                                dan memastikan seluruh pengaturan Zoom sudah siap sebelum peserta bergabung.
                            </p>
                        </div>
                    </td>
                </tr>';
        }

        $html = '
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"></head>
<body style="margin:0;padding:0;background-color:#f3f4f6;font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,\'Helvetica Neue\',Arial,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f4f6;padding:40px 20px;">
        <tr><td align="center">
            <table width="100%" style="max-width:620px;" cellpadding="0" cellspacing="0">

                <!-- HEADER -->
                <tr>
                    <td style="background:linear-gradient(135deg,#0d9488 0%,#0f766e 100%);padding:40px 30px;border-radius:12px 12px 0 0;">
                        <div style="font-size:48px;margin-bottom:10px;">🎥</div>
                        <h1 style="margin:0;color:#ffffff;font-size:28px;font-weight:bold;">Layanan Zoom Siap Digunakan</h1>
                        <p style="margin:12px 0 0 0;color:#ccfbf1;font-size:15px;">Detail sesi Zoom Anda sudah dikonfigurasi oleh tim IT</p>
                    </td>
                </tr>

                <!-- CONTENT -->
                <tr>
                    <td style="background-color:#ffffff;">

                        <table width="100%" cellpadding="0" cellspacing="0" style="padding:30px 30px 20px 30px;">
                            <tr>
                                <td style="color:#1f2937;font-size:15px;line-height:1.6;">
                                    <p style="margin:0 0 16px 0;">Yth. <strong>' . $ticket['name'] . '</strong>,</p>
                                    <p style="margin:0;">Layanan Zoom untuk tiket <strong style="color:#0d9488;">' . $ticket['kode_tiket'] . '</strong>
                                    telah disiapkan oleh tim IT. Silakan cek detail sesi di bawah ini dan bergabung melalui link yang tersedia.</p>
                                </td>
                            </tr>
                        </table>

                        <table width="100%" cellpadding="0" cellspacing="0" style="background:linear-gradient(135deg,#ccfbf1 0%,#99f6e4 100%);padding:20px 30px;border-top:3px solid #0d9488;border-bottom:3px solid #0d9488;">
                            <tr><td align="center">
                                <span style="display:inline-block;padding:10px 24px;background:#0d9488;color:#ffffff;border-radius:25px;font-size:14px;font-weight:700;letter-spacing:0.5px;">
                                    🎥 SESI ZOOM AKTIF
                                </span>
                            </td></tr>
                        </table>

                        <table width="100%" cellpadding="0" cellspacing="0" style="padding:30px;">

                            <!-- Link Zoom -->
                            <tr>
                                <td style="padding-bottom:20px;">
                                    <div style="background:linear-gradient(135deg,#f0fdfa 0%,#ccfbf1 100%);border:2px solid #5eead4;padding:20px;border-radius:10px;text-align:center;">
                                        <p style="margin:0 0 12px 0;color:#0f766e;font-size:14px;font-weight:600;">🔗 Klik tombol berikut untuk bergabung:</p>
                                        <a href="' . htmlspecialchars($ticket['layanan_link']) . '" target="_blank"
                                           style="display:inline-block;padding:14px 32px;background:#0d9488;color:#ffffff;text-decoration:none;border-radius:8px;font-weight:bold;font-size:15px;box-shadow:0 4px 6px rgba(13,148,136,0.3);">
                                            🚀 Gabung Zoom Sekarang
                                        </a>
                                        <p style="margin:16px 0 0 0;color:#6b7280;font-size:11px;">atau salin link ini:<br>
                                            <span style="color:#0f766e;word-break:break-all;">' . htmlspecialchars($ticket['layanan_link']) . '</span>
                                        </p>
                                    </div>
                                </td>
                            </tr>

                            ' . $peringatan_15_html . '

                            <tr><td style="padding:20px 0;"><div style="border-top:2px solid #e5e7eb;"></div></td></tr>

                            <tr><td><h3 style="margin:0 0 16px 0;color:#0d9488;font-size:16px;">📋 Detail Sesi Zoom</h3></td></tr>

                            <tr>
                                <td>
                                    <table width="100%" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td style="padding:8px 0;width:180px;color:#6b7280;font-size:14px;"><strong>🎫 Kode Tiket:</strong></td>
                                            <td style="padding:8px 0;color:#1f2937;font-size:14px;"><strong style="color:#0d9488;">' . $ticket['kode_tiket'] . '</strong></td>
                                        </tr>
                                        <tr>
                                            <td style="padding:8px 0;width:180px;color:#6b7280;font-size:14px;"><strong>📂 Kategori:</strong></td>
                                            <td style="padding:8px 0;color:#1f2937;font-size:14px;">' . $kategori_nama . '</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:8px 0;width:180px;color:#6b7280;font-size:14px;"><strong>🏷️ Sub Kategori:</strong></td>
                                            <td style="padding:8px 0;color:#1f2937;font-size:14px;">' . $sub_kategori_nama . '</td>
                                        </tr>';

        if ($periode_label) {
            $html .= '
                                        <tr>
                                            <td style="padding:8px 0;width:180px;color:#6b7280;font-size:14px;"><strong>📅 Periode:</strong></td>
                                            <td style="padding:8px 0;color:#1f2937;font-size:14px;font-weight:600;">' . $periode_label . '</td>
                                        </tr>';
        }

        if ($jam_layanan) {
            $html .= '
                                        <tr>
                                            <td style="padding:8px 0;width:180px;color:#6b7280;font-size:14px;"><strong>🕐 Jam Mulai:</strong></td>
                                            <td style="padding:8px 0;color:#1f2937;font-size:14px;font-weight:600;">' . $jam_layanan . ' WIB</td>
                                        </tr>';
        }

        if (!empty($ticket['durasi']) || !empty($zoom['durasi_per_hari'])) {
            $dur = $ticket['durasi'] ?: $zoom['durasi_per_hari'];
            $html .= '
                                        <tr>
                                            <td style="padding:8px 0;width:180px;color:#6b7280;font-size:14px;"><strong>⏱️ Durasi:</strong></td>
                                            <td style="padding:8px 0;color:#1f2937;font-size:14px;">' . $dur . ' jam/hari</td>
                                        </tr>';
        }

        $html .= $akun_zoom_html . $peserta_html . $host_html . $pj_html;

        $html .= '
                                    </table>
                                </td>
                            </tr>

                            ' . $permintaan_lain_html . '

                            <tr>
                                <td style="padding:20px 0 10px 0;">
                                    <div style="background:#f0f9ff;border-left:4px solid #0891b2;padding:16px;border-radius:6px;">
                                        <p style="margin:0;color:#0e7490;font-size:14px;">
                                            👨‍💻 Dikonfigurasi oleh: <strong>' . $nama_teknisi . '</strong>
                                        </p>
                                    </div>
                                </td>
                            </tr>

                            ' . $catatan_html . '

                            <tr><td style="padding:20px 0;"><div style="border-top:2px solid #e5e7eb;"></div></td></tr>

                            <tr>
                                <td>
                                    <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:16px;">
                                        <p style="margin:0 0 8px 0;color:#1e40af;font-size:14px;font-weight:600;">💡 Tips Mengikuti Sesi Zoom:</p>
                                        <ul style="margin:0;padding-left:20px;color:#1f2937;font-size:13px;line-height:1.9;">
                                            <li>Pastikan koneksi internet Anda stabil</li>
                                            <li>Gunakan perangkat dengan audio dan kamera yang baik</li>
                                            <li><strong>Host / Co-Host wajib masuk <u>' . ($jam_masuk ? $jam_masuk . ' WIB' : '15 menit lebih awal') . '</u></strong> untuk membuka sesi</li>
                                            <li>Peserta lain dapat bergabung sesuai jam jadwal yang telah ditentukan</li>
                                            <li>Siapkan semua materi atau dokumen yang diperlukan sebelum sesi dimulai</li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>

                <!-- FOOTER -->
                <tr>
                    <td style="background-color:#1f2937;padding:30px;border-radius:0 0 12px 12px;text-align:center;">
                        <p style="margin:0 0 8px 0;color:#ffffff;font-size:15px;font-weight:bold;">SIPASTI</p>
                        <p style="margin:0 0 15px 0;color:#9ca3af;font-size:13px;line-height:1.6;">
                            Sistem Informasi Pengelolaan Aset dan Layanan TI<br>
                            <strong style="color:#cbd5e1;">BPS Provinsi Riau</strong>
                        </p>
                        <div style="border-top:1px solid #374151;padding-top:15px;margin-top:15px;">
                            <p style="margin:0;color:#9ca3af;font-size:12px;">Email ini dikirim secara otomatis oleh sistem.<br>Mohon tidak membalas email ini.</p>
                        </div>
                    </td>
                </tr>

            </table>
        </td></tr>
    </table>
</body>
</html>';

        return $html;
    }

    /* ===============================
       EMAIL TEMPLATE — SELESAI
    =============================== */
    private function email_template_selesai($ticket)
    {
        $kategori     = $this->db->get_where('master_kategori',     ['id' => $ticket['kategori_id']])->row();
        $sub_kategori = $this->db->get_where('master_sub_kategori', ['id' => $ticket['sub_kategori_id']])->row();

        $kategori_nama     = $kategori     ? $kategori->nama_kategori         : '-';
        $sub_kategori_nama = $sub_kategori ? $sub_kategori->nama_sub_kategori : '-';
        $deskripsi         = nl2br(htmlspecialchars($ticket['jenis_kerusakan']));

        $link_html = '';
        if (!empty($ticket['layanan_link'])) {
            $link_html = '
                <tr>
                    <td colspan="2" style="padding:20px 0;">
                        <div style="background:#f0fdfa;border:1px solid #5eead4;padding:16px;border-radius:8px;">
                            <p style="margin:0 0 8px 0;color:#0f766e;font-size:14px;font-weight:600;">🔗 Link Layanan yang Telah Digunakan:</p>
                            <p style="margin:0;">
                                <a href="' . htmlspecialchars($ticket['layanan_link']) . '" target="_blank"
                                   style="color:#0d9488;text-decoration:none;word-break:break-all;font-size:13px;">
                                    ' . htmlspecialchars($ticket['layanan_link']) . '
                                </a>
                            </p>
                        </div>
                    </td>
                </tr>';
        }

        return '
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"></head>
<body style="margin:0;padding:0;background-color:#f3f4f6;font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,\'Helvetica Neue\',Arial,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f4f6;padding:40px 20px;">
        <tr><td align="center">
            <table width="100%" style="max-width:600px;" cellpadding="0" cellspacing="0">

                <tr>
                    <td style="background:linear-gradient(135deg,#16a34a 0%,#15803d 100%);padding:40px 30px;border-radius:12px 12px 0 0;">
                        <div style="font-size:48px;margin-bottom:10px;">✅</div>
                        <h1 style="margin:0;color:#ffffff;font-size:28px;font-weight:bold;">Tiket Telah Diselesaikan</h1>
                        <p style="margin:12px 0 0 0;color:#d1fae5;font-size:15px;">Terima kasih telah menggunakan layanan kami</p>
                    </td>
                </tr>

                <tr>
                    <td style="background-color:#ffffff;">

                        <table width="100%" cellpadding="0" cellspacing="0" style="padding:30px 30px 20px 30px;">
                            <tr>
                                <td style="color:#1f2937;font-size:15px;line-height:1.6;">
                                    <p style="margin:0 0 16px 0;">Yth. <strong>' . $ticket['name'] . '</strong>,</p>
                                    <p style="margin:0;">Tiket Anda dengan kode <strong style="color:#16a34a;">' . $ticket['kode_tiket'] . '</strong> telah <strong>selesai ditangani</strong> oleh tim IT kami.</p>
                                </td>
                            </tr>
                        </table>

                        <table width="100%" cellpadding="0" cellspacing="0" style="background:linear-gradient(135deg,#dcfce7 0%,#bbf7d0 100%);padding:20px 30px;border-top:3px solid #16a34a;border-bottom:3px solid #16a34a;">
                            <tr><td align="center">
                                <span style="display:inline-block;padding:10px 24px;background:#16a34a;color:#ffffff;border-radius:25px;font-size:14px;font-weight:700;">✅ SELESAI</span>
                            </td></tr>
                        </table>

                        <table width="100%" cellpadding="0" cellspacing="0" style="padding:30px;">
                            <tr><td colspan="2" style="padding-bottom:20px;">
                                <h3 style="margin:0;color:#16a34a;font-size:18px;border-bottom:2px solid #e5e7eb;padding-bottom:10px;">📋 Ringkasan Tiket</h3>
                            </td></tr>
                            <tr>
                                <td style="padding:10px;width:160px;vertical-align:top;color:#6b7280;"><strong>🎫 Kode Tiket</strong></td>
                                <td style="padding:10px;color:#1f2937;"><strong style="color:#16a34a;font-size:16px;">' . $ticket['kode_tiket'] . '</strong></td>
                            </tr>
                            <tr>
                                <td style="padding:10px;width:160px;vertical-align:top;color:#6b7280;"><strong>📂 Kategori</strong></td>
                                <td style="padding:10px;color:#1f2937;">' . $kategori_nama . '</td>
                            </tr>
                            <tr>
                                <td style="padding:10px;width:160px;vertical-align:top;color:#6b7280;"><strong>🏷️ Sub Kategori</strong></td>
                                <td style="padding:10px;color:#1f2937;">' . $sub_kategori_nama . '</td>
                            </tr>
                            <tr><td colspan="2" style="padding:20px 10px;"><div style="border-top:2px solid #e5e7eb;"></div></td></tr>
                            <tr><td colspan="2" style="padding:10px;">
                                <strong style="color:#6b7280;font-size:14px;">📝 DESKRIPSI YANG TELAH DITANGANI:</strong>
                            </td></tr>
                            <tr>
                                <td colspan="2" style="padding:10px;background:#f9fafb;border-left:4px solid #16a34a;border-radius:6px;">
                                    <div style="color:#374151;line-height:1.7;font-size:14px;">' . $deskripsi . '</div>
                                </td>
                            </tr>

                            ' . $link_html . '

                            <tr><td colspan="2" style="padding:20px 10px;"><div style="border-top:2px solid #e5e7eb;"></div></td></tr>

                            <tr>
                                <td colspan="2" style="padding:10px;">
                                    <div style="background:#fef3c7;border:1px solid #fde047;border-radius:8px;padding:20px;text-align:center;">
                                        <p style="margin:0 0 12px 0;color:#92400e;font-size:16px;font-weight:600;">⭐ Bagaimana Pengalaman Anda?</p>
                                        <p style="margin:0 0 16px 0;color:#1f2937;font-size:14px;">Silakan berikan penilaian Anda untuk membantu kami meningkatkan kualitas layanan</p>
                                        <a href="' . base_url('/user_ticket/history') . '"
                                           style="display:inline-block;padding:12px 28px;background:#16a34a;color:#ffffff;text-decoration:none;border-radius:8px;font-weight:bold;font-size:14px;box-shadow:0 4px 6px rgba(22,163,74,0.3);">
                                            💬 Berikan Rating
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <tr><td colspan="2" style="padding:20px 10px;"><div style="border-top:2px solid #e5e7eb;"></div></td></tr>

                            <tr>
                                <td colspan="2" style="padding:10px;">
                                    <div style="background:#eff6ff;border-left:4px solid #3b82f6;padding:16px;border-radius:6px;">
                                        <p style="margin:0;color:#1f2937;font-size:14px;line-height:1.7;">
                                            <strong style="color:#1e40af;">🙏 Terima kasih</strong> telah mempercayakan kebutuhan IT Anda kepada kami.
                                            Jika Anda memerlukan bantuan lain, jangan ragu untuk menghubungi kami kembali.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="background-color:#1f2937;padding:30px;border-radius:0 0 12px 12px;text-align:center;">
                        <p style="margin:0 0 8px 0;color:#ffffff;font-size:15px;font-weight:bold;">SIPASTI</p>
                        <p style="margin:0 0 15px 0;color:#9ca3af;font-size:13px;line-height:1.6;">
                            Sistem Informasi Pengelolaan Aset dan Layanan TI<br>
                            <strong style="color:#cbd5e1;">BPS Provinsi Riau</strong>
                        </p>
                        <div style="border-top:1px solid #374151;padding-top:15px;margin-top:15px;">
                            <p style="margin:0;color:#9ca3af;font-size:12px;">Email ini dikirim secara otomatis oleh sistem.<br>Mohon tidak membalas email ini.</p>
                        </div>
                    </td>
                </tr>

            </table>
        </td></tr>
    </table>
</body>
</html>';
    }

    /* ===============================
       UTIL
    =============================== */
    public function hitung_durasi($start, $end)
    {
        if (!$start || !$end) return null;
        $start = new DateTime($start);
        $end   = new DateTime($end);
        $diff  = $start->diff($end);
        if ($diff->d > 0) return $diff->d . ' hari ' . $diff->h . ' jam';
        if ($diff->h > 0) return $diff->h . ' jam ' . $diff->i . ' menit';
        return $diff->i . ' menit';
    }

    public function insert($data)
    {
        return $this->db->insert('tickets', $data);
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)->delete('tickets');
    }

    /* ===============================
       PAGINATION
    =============================== */
    public function get_paginated_v2($limit, $offset, $q = null, $status = null, $kategori_id = null, $sub_kategori_id = null)
    {
        $this->db->select('t.*, p.nama_barang AS nama_perangkat, ut.name AS nama_teknisi, mk.nama_kategori, msk.nama_sub_kategori');
        $this->db->from('tickets t');
        $this->db->join('perangkat p',           'p.id = t.perangkat_id',        'left');
        $this->db->join('teknisi tek',            'tek.id = t.teknisi_id',        'left');
        $this->db->join('users ut',               'ut.id = tek.user_id',          'left');
        $this->db->join('master_kategori mk',     'mk.id = t.kategori_id',        'left');
        $this->db->join('master_sub_kategori msk','msk.id = t.sub_kategori_id',   'left');

        if ($q)              $this->db->group_start()->like('t.kode_tiket', $q)->or_like('t.pelapor', $q)->group_end();
        if ($status)         $this->db->where('t.status', $status);
        if ($kategori_id)    $this->db->where('t.kategori_id', $kategori_id);
        if ($sub_kategori_id)$this->db->where('t.sub_kategori_id', $sub_kategori_id);

        $this->db->order_by('t.created_at', 'DESC');
        $this->db->limit($limit, $offset);

        return $this->db->get()->result_array();
    }

    public function count_all_filtered_v2($q = null, $status = null, $kategori_id = null, $sub_kategori_id = null)
    {
        $this->db->from('tickets t');
        if ($q)              $this->db->group_start()->like('t.kode_tiket', $q)->or_like('t.pelapor', $q)->group_end();
        if ($status)         $this->db->where('t.status', $status);
        if ($kategori_id)    $this->db->where('t.kategori_id', $kategori_id);
        if ($sub_kategori_id)$this->db->where('t.sub_kategori_id', $sub_kategori_id);
        return $this->db->count_all_results();
    }

    /* Kept for backward compat */
    public function get_paginated($limit, $offset, $q = null, $status = null, $kategori = null)
    {
        $this->db->select('t.*, p.nama_barang AS nama_perangkat, u.name AS nama_teknisi');
        $this->db->from('tickets t');
        $this->db->join('perangkat p', 'p.id = t.perangkat_id', 'left');
        $this->db->join('teknisi tek', 'tek.id = t.teknisi_id', 'left');
        $this->db->join('users u',     'u.id = tek.user_id',   'left');
        if ($q)       $this->db->group_start()->like('t.kode_tiket', $q)->or_like('t.pelapor', $q)->group_end();
        if ($status)  $this->db->where('t.status', $status);
        if ($kategori)$this->db->where('t.kategori', $kategori);
        $this->db->order_by('t.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result_array();
    }

    public function count_all_filtered($q = null, $status = null, $kategori = null)
    {
        $this->db->from('tickets');
        if ($q)       $this->db->group_start()->like('kode_tiket', $q)->or_like('pelapor', $q)->group_end();
        if ($status)  $this->db->where('status', $status);
        if ($kategori)$this->db->where('kategori', $kategori);
        return $this->db->count_all_results();
    }

    public function get_kategori_label($kategori_id)
    {
        return $this->db->select('nama_kategori')->where('id', $kategori_id)->get('master_kategori')->row('nama_kategori');
    }

    public function get_sub_kategori_label($sub_id)
    {
        return $this->db->select('nama_sub_kategori')->where('id', $sub_id)->get('master_sub_kategori')->row('nama_sub_kategori');
    }
    private function _auto_cancel_conflicting_ruang_rapat($current_ticket_id, $jenis_kerusakan, $ticket_teknisi)
{
    // Ekstrak peminjaman_id dari jenis_kerusakan
    if (!preg_match('/\[ref:peminjaman_id=(\d+)\]/', $jenis_kerusakan, $m)) return;
    $peminjaman_id = (int)$m[1];

    // Ambil data peminjaman yang sudah dikonfirmasi
    $pem = $this->db->where('id', $peminjaman_id)->get('peminjaman_ruang_rapat')->row_array();
    if (!$pem) return;

    $ruangan_id  = $pem['ruangan_id'];
    $tanggal     = $pem['tanggal'];
    $jam_mulai   = $pem['jam_mulai'];
    $jam_selesai = $pem['jam_selesai'];

    // Cari semua peminjaman lain yang konflik
    $konflik = $this->db
        ->where('ruangan_id',   $ruangan_id)
        ->where('tanggal',      $tanggal)
        ->where('id !=',        $peminjaman_id)
        ->where_in('status',    ['Menunggu', 'Disetujui'])
        ->group_start()
            ->where('jam_mulai <',  $jam_selesai)
            ->where('jam_selesai >', $jam_mulai)
        ->group_end()
        ->get('peminjaman_ruang_rapat')
        ->result_array();

    if (empty($konflik)) return;

    $this->load->helper('email');

    // Ambil info ruangan
    $ruangan = $this->db->select('nama_ruangan, lokasi')
        ->where('id', $ruangan_id)
        ->get('master_ruang_rapat')->row_array();
    $nama_ruangan = $ruangan['nama_ruangan'] ?? 'ruangan tersebut';

    $fmt_tanggal  = date('d F Y', strtotime($tanggal));
    $fmt_jam      = substr($jam_mulai, 0, 5) . ' s.d. ' . substr($jam_selesai, 0, 5);
    $catatan_batal = "Dibatalkan otomatis: ruangan {$nama_ruangan} sudah digunakan pada {$fmt_tanggal} pukul {$fmt_jam} WIB.";

    foreach ($konflik as $k) {
        // Update status peminjaman
        $this->db->where('id', $k['id'])->update('peminjaman_ruang_rapat', [
            'status'         => 'Ditolak',
            'keterangan'     => $catatan_batal,
        ]);

        // Cari tiket yang mereferensi peminjaman ini
        $tiket_konflik = $this->db
            ->where('jenis_kerusakan LIKE', "%[ref:peminjaman_id={$k['id']}]%")
            ->where_in('status', ['Menunggu', 'On Progress'])
            ->get('tickets')->result_array();

        foreach ($tiket_konflik as $tk) {
            // Cancel tiket
            $this->db->where('id', $tk['id'])->update('tickets', [
                'status'         => 'Dibatalkan',
                'layanan_catatan' => $catatan_batal,
            ]);

            // Kirim email pembatalan ke user
            $user = $this->db->where('id', $tk['user_id'])->get('users')->row_array();
            if ($user && !empty($user['email'])) {
                send_ticket_email(
                    $user['email'],
                    "[SIPASTI] {$tk['kode_tiket']} - Peminjaman Ruang Rapat Dibatalkan",
                    $this->email_template_batal_ruang_rapat($tk, $k, $ruangan, $catatan_batal)
                );
            }
        }
    }
}

/* ===============================
   EMAIL TEMPLATE — ON PROGRESS RUANG RAPAT
=============================== */
private function email_template_on_progress_ruang_rapat($ticket)
{
    // Parse detail dari jenis_kerusakan
    $lines   = explode("\n", $ticket['jenis_kerusakan'] ?? '');
    $detail  = [];
    foreach ($lines as $line) {
        $line = trim($line);
        if (!$line || strpos($line, '[ref:') === 0) continue;
        $detail[] = $line;
    }

    $rows_html = '';
    foreach ($detail as $baris) {
        if (strpos($baris, ':') !== false) {
            [$label, $val] = explode(':', $baris, 2);
            $rows_html .= "
            <tr>
                <td style='padding:10px;width:160px;vertical-align:top;color:#6b7280;font-size:14px;'>
                    <strong>" . htmlspecialchars(trim($label)) . "</strong>
                </td>
                <td style='padding:10px;color:#1f2937;font-size:14px;'>
                    " . htmlspecialchars(trim($val)) . "
                </td>
            </tr>";
        }
    }

    $nama_teknisi = !empty($ticket['nama_teknisi']) ? $ticket['nama_teknisi'] : 'Tim IT';

    return '
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0"></head>
<body style="margin:0;padding:0;background-color:#f3f4f6;font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f3f4f6;padding:40px 20px;">
<tr><td align="center">
<table width="100%" style="max-width:600px;" cellpadding="0" cellspacing="0">

    <!-- HEADER -->
    <tr>
        <td style="background:linear-gradient(135deg,#4f46e5 0%,#4338ca 100%);padding:40px 30px;border-radius:12px 12px 0 0;">
            <div style="font-size:48px;margin-bottom:10px;">🏢</div>
            <h1 style="margin:0;color:#fff;font-size:26px;font-weight:bold;">Peminjaman Ruang Rapat Dikonfirmasi</h1>
            <p style="margin:12px 0 0 0;color:#c7d2fe;font-size:15px;">Permintaan peminjaman ruangan Anda sedang ditangani</p>
        </td>
    </tr>

    <!-- CONTENT -->
    <tr>
        <td style="background:#fff;">

            <table width="100%" cellpadding="0" cellspacing="0" style="padding:30px 30px 20px 30px;">
                <tr>
                    <td style="color:#1f2937;font-size:15px;line-height:1.7;">
                        <p style="margin:0 0 16px 0;">Yth. <strong>' . htmlspecialchars($ticket['name']) . '</strong>,</p>
                        <p style="margin:0;">Permintaan peminjaman ruang rapat Anda dengan kode tiket
                        <strong style="color:#4f46e5;">' . $ticket['kode_tiket'] . '</strong>
                        telah <strong>diterima dan sedang diproses</strong> oleh tim kami.</p>
                    </td>
                </tr>
            </table>

            <table width="100%" cellpadding="0" cellspacing="0"
                   style="background:linear-gradient(135deg,#e0e7ff,#c7d2fe);padding:20px 30px;
                          border-top:3px solid #4f46e5;border-bottom:3px solid #4f46e5;">
                <tr><td align="center">
                    <span style="display:inline-block;padding:10px 24px;background:#4f46e5;
                                 color:#fff;border-radius:25px;font-size:14px;font-weight:700;">
                        🏢 SEDANG DIPROSES
                    </span>
                </td></tr>
            </table>

            <table width="100%" cellpadding="0" cellspacing="0" style="padding:30px;">
                <tr><td colspan="2" style="padding-bottom:16px;">
                    <h3 style="margin:0;color:#4f46e5;font-size:17px;
                               border-bottom:2px solid #e5e7eb;padding-bottom:10px;">
                        📋 Detail Peminjaman
                    </h3>
                </td></tr>

                <tr>
                    <td style="padding:10px;width:160px;vertical-align:top;color:#6b7280;font-size:14px;">
                        <strong>🎫 Kode Tiket</strong>
                    </td>
                    <td style="padding:10px;color:#4f46e5;font-size:15px;font-weight:700;">
                        ' . $ticket['kode_tiket'] . '
                    </td>
                </tr>

                ' . $rows_html . '

                <tr><td colspan="2" style="padding:20px 0;">
                    <div style="border-top:2px solid #e5e7eb;"></div>
                </td></tr>

                <tr>
                    <td colspan="2" style="padding:0 0 16px 0;">
                        <div style="background:#eef2ff;border:1px solid #a5b4fc;border-radius:8px;padding:16px;">
                            <p style="margin:0 0 8px 0;color:#3730a3;font-size:14px;font-weight:600;">
                                ℹ️ Informasi Penting
                            </p>
                            <ul style="margin:0;padding-left:20px;color:#1f2937;font-size:13px;line-height:1.9;">
                                <li>Permintaan ruang rapat Anda sedang dalam proses persetujuan</li>
                                <li>Tim kami akan menyiapkan ruangan sesuai jadwal yang diminta</li>
                                <li>Anda akan mendapat notifikasi setelah proses selesai</li>
                                <li>Jika ada pertanyaan, silakan hubungi tim IT</li>
                            </ul>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <div style="background:#f0f9ff;border-left:4px solid #0891b2;
                                    padding:14px;border-radius:6px;">
                            <p style="margin:0;color:#0e7490;font-size:14px;">
                                👨‍💻 Ditangani oleh: <strong>' . htmlspecialchars($nama_teknisi) . '</strong>
                            </p>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- FOOTER -->
    <tr>
        <td style="background:#1f2937;padding:25px 30px;border-radius:0 0 12px 12px;text-align:center;">
            <p style="margin:0 0 6px 0;color:#fff;font-size:15px;font-weight:bold;">SIPASTI</p>
            <p style="margin:0;color:#9ca3af;font-size:12px;line-height:1.7;">
                Sistem Informasi Pengelolaan Aset dan Layanan TI<br>
                <strong style="color:#cbd5e1;">BPS Provinsi Riau</strong><br>
                Email ini dikirim otomatis. Mohon tidak membalas email ini.
            </p>
        </td>
    </tr>

</table>
</td></tr>
</table>
</body>
</html>';
}

/* ===============================
   EMAIL TEMPLATE — PEMBATALAN RUANG RAPAT (KONFLIK)
=============================== */
private function email_template_batal_ruang_rapat($ticket, $peminjaman, $ruangan, $alasan)
{
    $nama_ruangan = $ruangan['nama_ruangan'] ?? '-';
    $lokasi       = $ruangan['lokasi']       ?? '-';
    $tanggal_fmt  = date('d F Y', strtotime($peminjaman['tanggal']));
    $jam_fmt      = substr($peminjaman['jam_mulai'], 0, 5) . ' s.d. ' . substr($peminjaman['jam_selesai'], 0, 5);

    $user = $this->db->where('id', $ticket['user_id'])->get('users')->row_array();
    $nama = $user['name'] ?? 'Pengguna';

    return '
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0"></head>
<body style="margin:0;padding:0;background-color:#f3f4f6;font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f3f4f6;padding:40px 20px;">
<tr><td align="center">
<table width="100%" style="max-width:600px;" cellpadding="0" cellspacing="0">

    <!-- HEADER -->
    <tr>
        <td style="background:linear-gradient(135deg,#dc2626,#b91c1c);padding:40px 30px;border-radius:12px 12px 0 0;">
            <div style="font-size:48px;margin-bottom:10px;">❌</div>
            <h1 style="margin:0;color:#fff;font-size:26px;font-weight:bold;">Peminjaman Ruang Rapat Dibatalkan</h1>
            <p style="margin:12px 0 0 0;color:#fecaca;font-size:15px;">Permintaan Anda tidak dapat dipenuhi karena konflik jadwal</p>
        </td>
    </tr>

    <!-- CONTENT -->
    <tr>
        <td style="background:#fff;">
            <table width="100%" cellpadding="0" cellspacing="0" style="padding:30px 30px 20px 30px;">
                <tr>
                    <td style="color:#1f2937;font-size:15px;line-height:1.7;">
                        <p style="margin:0 0 16px 0;">Yth. <strong>' . htmlspecialchars($nama) . '</strong>,</p>
                        <p style="margin:0;">Mohon maaf, permintaan peminjaman ruang rapat Anda dengan kode
                        <strong style="color:#dc2626;">' . htmlspecialchars($ticket['kode_tiket']) . '</strong>
                        <strong>tidak dapat dipenuhi</strong> karena ruangan sudah digunakan oleh pihak lain pada waktu yang sama.</p>
                    </td>
                </tr>
            </table>

            <table width="100%" cellpadding="0" cellspacing="0"
                   style="background:#fef2f2;padding:20px 30px;
                          border-top:3px solid #dc2626;border-bottom:3px solid #dc2626;">
                <tr><td align="center">
                    <span style="display:inline-block;padding:10px 24px;background:#dc2626;
                                 color:#fff;border-radius:25px;font-size:14px;font-weight:700;">
                        ❌ DIBATALKAN
                    </span>
                </td></tr>
            </table>

            <table width="100%" cellpadding="0" cellspacing="0" style="padding:30px;">
                <tr><td colspan="2" style="padding-bottom:16px;">
                    <h3 style="margin:0;color:#dc2626;font-size:17px;
                               border-bottom:2px solid #e5e7eb;padding-bottom:10px;">
                        📋 Detail Permintaan yang Dibatalkan
                    </h3>
                </td></tr>
                <tr>
                    <td style="padding:10px;width:160px;color:#6b7280;font-size:14px;"><strong>🎫 Kode Tiket</strong></td>
                    <td style="padding:10px;color:#dc2626;font-size:15px;font-weight:700;">' . htmlspecialchars($ticket['kode_tiket']) . '</td>
                </tr>
                <tr>
                    <td style="padding:10px;width:160px;color:#6b7280;font-size:14px;"><strong>🏢 Ruangan</strong></td>
                    <td style="padding:10px;color:#1f2937;font-size:14px;">' . htmlspecialchars($nama_ruangan) . '</td>
                </tr>
                <tr>
                    <td style="padding:10px;width:160px;color:#6b7280;font-size:14px;"><strong>📍 Lokasi</strong></td>
                    <td style="padding:10px;color:#1f2937;font-size:14px;">' . htmlspecialchars($lokasi) . '</td>
                </tr>
                <tr>
                    <td style="padding:10px;width:160px;color:#6b7280;font-size:14px;"><strong>📅 Tanggal</strong></td>
                    <td style="padding:10px;color:#1f2937;font-size:14px;">' . $tanggal_fmt . '</td>
                </tr>
                <tr>
                    <td style="padding:10px;width:160px;color:#6b7280;font-size:14px;"><strong>🕐 Waktu</strong></td>
                    <td style="padding:10px;color:#1f2937;font-size:14px;">' . $jam_fmt . ' WIB</td>
                </tr>

                <tr><td colspan="2" style="padding:20px 0;">
                    <div style="border-top:2px solid #e5e7eb;"></div>
                </td></tr>

                <tr>
                    <td colspan="2" style="padding:0 0 16px 0;">
                        <div style="background:#fef2f2;border:2px solid #fca5a5;border-radius:8px;padding:16px;">
                            <p style="margin:0 0 8px 0;color:#991b1b;font-size:14px;font-weight:700;">
                                ⚠️ Alasan Pembatalan
                            </p>
                            <p style="margin:0;color:#1f2937;font-size:14px;line-height:1.7;">
                                ' . htmlspecialchars($alasan) . '
                            </p>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td colspan="2" style="padding:16px 0 0 0;">
                        <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:16px;">
                            <p style="margin:0 0 8px 0;color:#1e40af;font-size:14px;font-weight:600;">
                                💡 Apa yang bisa Anda lakukan?
                            </p>
                            <ul style="margin:0;padding-left:20px;color:#1f2937;font-size:13px;line-height:1.9;">
                                <li>Ajukan permintaan baru dengan tanggal atau jam yang berbeda</li>
                                <li>Pilih ruangan lain yang tersedia pada waktu tersebut</li>
                                <li>Hubungi tim IT jika membutuhkan bantuan penentuan jadwal</li>
                            </ul>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td colspan="2" style="padding:20px 0 0 0;text-align:center;">
                        <a href="' . base_url('/user_ticket/create_permintaan') . '"
                           style="display:inline-block;padding:12px 28px;background:#4f46e5;color:#fff;
                                  text-decoration:none;border-radius:8px;font-weight:bold;font-size:14px;">
                            📝 Ajukan Permintaan Baru
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- FOOTER -->
    <tr>
        <td style="background:#1f2937;padding:25px 30px;border-radius:0 0 12px 12px;text-align:center;">
            <p style="margin:0 0 6px 0;color:#fff;font-size:15px;font-weight:bold;">SIPASTI</p>
            <p style="margin:0;color:#9ca3af;font-size:12px;line-height:1.7;">
                Sistem Informasi Pengelolaan Aset dan Layanan TI<br>
                <strong style="color:#cbd5e1;">BPS Provinsi Riau</strong><br>
                Email ini dikirim otomatis. Mohon tidak membalas email ini.
            </p>
        </td>
    </tr>

</table>
</td></tr>
</table>
</body>
</html>';
}
}