<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_ticket_model extends CI_Model {

    /* =========================
       AMBIL ID KATEGORI DARI SLUG
    ========================= */
    public function get_kategori_id_by_slug($slug)
    {
        return $this->db
            ->select('id')
            ->from('master_kategori')
            ->where('slug', $slug)
            ->get()
            ->row('id');
    }

    /* =========================
       AMBIL SUB KATEGORI BERDASARKAN KATEGORI
    ========================= */
    public function get_sub_kategori_by_kategori($kategori_id)
    {
        return $this->db
          ->select('id, nama_sub_kategori, slug, perlu_jadwal')
            ->where('kategori_id', $kategori_id)
            ->order_by('created_at', 'ASC')
            ->get('master_sub_kategori')
            ->result();
    }

    public function get_sub_kategori($kategori_id)
    {
        return $this->get_sub_kategori_by_kategori($kategori_id);
    }

    /* =========================
       AMBIL DATA PERANGKAT
    ========================= */
    public function get_perangkat()
    {
        return $this->db
            ->order_by('nama_barang', 'ASC')
            ->get('perangkat')
            ->result_array();
    }

    /* =========================
       CREATE TIKET + NOTIFIKASI EMAIL LENGKAP
    ========================= */
public function create_ticket()
{
    $userId   = $this->session->userdata('user_id');
    $userName = $this->session->userdata('name');

    $kategori_slug   = $this->input->post('kategori');
    $kategori_id     = $this->get_kategori_id_by_slug($kategori_slug);
    $sub_kategori_id = $this->input->post('sub_kategori_id');

    $data = [
        'user_id'         => $userId,
        'pelapor'         => $userName,
        'kode_tiket'      => 'TKT-' . date('YmdHis'),
        'kategori_id'     => $kategori_id,
        'sub_kategori_id' => $sub_kategori_id ?: null,
        'status'          => 'Menunggu',
        'created_at'      => date('Y-m-d H:i:s')
    ];

    /* =========================================
       KERUSAKAN / GANGGUAN
    ========================================= */
    if (in_array($kategori_slug, ['kerusakan', 'gangguan'])) {
        $data['perangkat_id']    = $this->input->post('perangkat_id');
        $data['lokasi']          = $this->input->post('lokasi');
        $data['jenis_kerusakan'] = $this->input->post('jenis_kerusakan');
    }

    /* =========================================
       PERMINTAAN RUANG RAPAT
    ========================================= */
  elseif ($kategori_slug === 'permintaan' &&
        $this->input->post('is_ruang_rapat') == '1') {

    // ✅ FIX: query by slug
    $rr_sub = $this->db
        ->select('id')
        ->get_where('master_sub_kategori', ['slug' => 'permintaan-ruang-rapat'])
        ->row();
    $data['sub_kategori_id'] = $rr_sub ? $rr_sub->id : null;

    $ruangan_id     = (int) $this->input->post('ruangan_id');
        $tanggal        = $this->input->post('tanggal_rapat');
        $jam_mulai      = $this->input->post('jam_mulai');
        $jam_selesai    = $this->input->post('jam_selesai');
        $keperluan      = $this->input->post('keperluan');
        $jumlah_peserta = (int) $this->input->post('jumlah_peserta_rapat') ?: null;
        $snack_corner   = $this->input->post('snack_corner') ?: 'Tidak';
        $jumlah_snack   = ($snack_corner === 'Ya') ? (int) $this->input->post('jumlah_snack') : null;
        $catatan        = $this->input->post('catatan_peminjam');

        $conflict = $this->db
            ->where('ruangan_id', $ruangan_id)
            ->where('tanggal', $tanggal)
            ->where_in('status', ['Menunggu', 'Disetujui'])
            ->group_start()
                ->where('jam_mulai <', $jam_selesai)
                ->where('jam_selesai >', $jam_mulai)
            ->group_end()
            ->count_all_results('peminjaman_ruang_rapat');

        if ($conflict > 0) {
            $this->session->set_flashdata('error', 'Ruangan sudah dipesan pada waktu tersebut. Pilih waktu lain.');
            return false;
        }

        $ruangan = $this->db
            ->select('nama_ruangan, lokasi, kapasitas')
            ->where('id', $ruangan_id)
            ->get('master_ruang_rapat')
            ->row_array();

        $this->db->insert('peminjaman_ruang_rapat', [
            'ruangan_id'       => $ruangan_id,
            'nama_peminjam'    => $userName,
            'unit_kerja'       => $this->input->post('unit_kerja'),
            'tanggal'          => $tanggal,
            'jam_mulai'        => $jam_mulai,
            'jam_selesai'      => $jam_selesai,
            'keperluan'        => $keperluan,
            'jumlah_peserta'   => $jumlah_peserta,
            'snack_corner'     => $snack_corner,
            'jumlah_snack'     => $jumlah_snack,
            'catatan_peminjam' => $catatan,
            'status'           => 'Menunggu',
            'selesai'          => 'Belum',
            'created_by'       => $userId,
        ]);
        $peminjaman_id = $this->db->insert_id();

        $catatan_arr   = [];
        $catatan_arr[] = "Ruangan: "  . ($ruangan['nama_ruangan'] ?? '-');
        $catatan_arr[] = "Lokasi: "   . ($ruangan['lokasi']       ?? '-');
        $catatan_arr[] = "Tanggal: "  . date('d F Y', strtotime($tanggal));
        $catatan_arr[] = "Jam: "      . $jam_mulai . " s.d. " . $jam_selesai;
        $catatan_arr[] = "Keperluan: " . $keperluan;
        if ($jumlah_peserta) $catatan_arr[] = "Jumlah Peserta: {$jumlah_peserta} orang";
        if ($snack_corner === 'Ya')    $catatan_arr[] = "Snack Corner: Ya ({$jumlah_snack} porsi)";
        if ($catatan)                  $catatan_arr[] = "Catatan: {$catatan}";
        $catatan_arr[] = "[ref:peminjaman_id={$peminjaman_id}]";

        $data['jenis_kerusakan'] = implode("\n", $catatan_arr);
        $data['tanggal']         = $tanggal;
        $data['jam']             = $jam_mulai;
    }

    /* =========================================
       PERMINTAAN KENDARAAN DINAS  ← BLOK BARU
    ========================================= */
elseif ($kategori_slug === 'permintaan' &&
      $this->input->post('is_kendaraan') == '1') {

    // ✅ FIX: query by slug
    $kend_sub = $this->db
        ->select('id')
        ->get_where('master_sub_kategori', ['slug' => 'permintaan-kendaraan'])
        ->row();
    $data['sub_kategori_id'] = $kend_sub ? $kend_sub->id : null;

    $kendaraan_id    = (int) $this->input->post('kend_kendaraan_id');
    $tipe_pinjam     = $this->input->post('kend_tipe_pinjam') ?: 'harian';
    $tanggal_pinjam  = $this->input->post('kend_tanggal_pinjam');
    $tanggal_kembali = $this->input->post('kend_tanggal_kembali');
    $jam_pinjam      = ($tipe_pinjam === 'per_jam') ? $this->input->post('kend_jam_pinjam')  : null;
    $jam_kembali     = ($tipe_pinjam === 'per_jam') ? $this->input->post('kend_jam_kembali') : null;

    // Validasi per_jam
    if ($tipe_pinjam === 'per_jam') {
        if ($tanggal_pinjam !== $tanggal_kembali) {
            $this->session->set_flashdata('error', 'Peminjaman per jam hanya boleh 1 hari yang sama.');
            return false;
        }
        if (!$jam_pinjam || !$jam_kembali || $jam_pinjam >= $jam_kembali) {
            $this->session->set_flashdata('error', 'Jam kembali harus lebih dari jam pinjam.');
            return false;
        }
    }

    // Cek konflik
    $this->load->model('Peminjaman_kendaraan_model');
    $conflict = $this->Peminjaman_kendaraan_model->is_conflict(
        $kendaraan_id, $tanggal_pinjam, $tanggal_kembali, $jam_pinjam, $jam_kembali
    );
    if ($conflict) {
        $this->session->set_flashdata('error', 'Kendaraan sudah dipinjam pada waktu tersebut.');
        return false;
    }

    // Info kendaraan
    $kend = $this->db
        ->select('nama_kendaraan, nomor_polisi, jenis')
        ->where('id', $kendaraan_id)
        ->get('master_kendaraan')
        ->row_array();

    // Insert ke tabel peminjaman_kendaraan
    $this->db->insert('peminjaman_kendaraan', [
        'kendaraan_id'   => $kendaraan_id,
        'nama_peminjam'  => $userName,
        'unit_kerja'     => $this->input->post('kend_unit_kerja'),
        'keperluan'      => $this->input->post('kend_keperluan'),
        'tujuan'         => $this->input->post('kend_tujuan'),
        'tanggal_pinjam' => $tanggal_pinjam,
        'tanggal_kembali'=> $tanggal_kembali,
        'tipe_pinjam'    => $tipe_pinjam,
        'jam_pinjam'     => $jam_pinjam,
        'jam_kembali'    => $jam_kembali,
        'pengemudi'      => $this->input->post('kend_pengemudi') ?: 'Sendiri',
        'no_hp'          => $this->input->post('kend_no_hp'),
        'catatan'        => $this->input->post('kend_catatan'),
        'status'         => 'Menunggu',
        'created_by'     => $userId,
    ]);
    $pinjaman_id = $this->db->insert_id();

    // Ringkasan untuk kolom tiket
    $icon    = ($kend['jenis'] ?? '') === 'Mobil' ? '🚗' : '🏍️';
    $lines   = [];
    $lines[] = "Kendaraan: {$icon} " . ($kend['nama_kendaraan'] ?? '-')
             . " (" . ($kend['nomor_polisi'] ?? '-') . ")";
    $lines[] = "Keperluan: " . $this->input->post('kend_keperluan');

    if ($t = $this->input->post('kend_tujuan')) $lines[] = "Tujuan: {$t}";

    if ($tipe_pinjam === 'per_jam') {
        $lines[] = "Tanggal: " . date('d F Y', strtotime($tanggal_pinjam));
        $lines[] = "Jam: {$jam_pinjam} s.d. {$jam_kembali} WIB";
    } else {
        $lines[] = "Tanggal Pinjam: " . date('d F Y', strtotime($tanggal_pinjam));
        $lines[] = "Est. Kembali: "   . date('d F Y', strtotime($tanggal_kembali));
    }

    if ($p = $this->input->post('kend_pengemudi')) $lines[] = "Pengemudi: {$p}";
    if ($c = $this->input->post('kend_catatan'))   $lines[] = "Catatan: {$c}";
    $lines[] = "[ref:peminjaman_kendaraan_id={$pinjaman_id}]";

    $data['jenis_kerusakan'] = implode("\n", $lines);
    $data['tanggal']         = $tanggal_pinjam;
    $data['jam']             = $jam_pinjam;
}
/* =========================================
       PERMINTAAN BMN
    ========================================= */
   elseif ($kategori_slug === 'permintaan' &&
        $this->input->post('is_bmn') == '1') {

    // ✅ FIX: query by slug biar works di local & server
    $bmn_sub = $this->db
        ->select('id')
        ->get_where('master_sub_kategori', ['slug' => 'permintaan-peminjaman-bmn'])
        ->row();
    $data['sub_kategori_id'] = $bmn_sub ? $bmn_sub->id : null;

    $bmn_id          = (int) $this->input->post('bmn_id');
        $tanggal_pinjam  = $this->input->post('bmn_tanggal_pinjam');
        $tanggal_kembali = $this->input->post('bmn_tanggal_kembali');
        $tujuan          = $this->input->post('bmn_tujuan');
        $unit_kerja      = $this->input->post('bmn_unit_kerja');
        $catatan         = $this->input->post('bmn_catatan');

        // Validasi tanggal
        if ($tanggal_kembali < $tanggal_pinjam) {
            $this->session->set_flashdata('error', 'Tanggal kembali tidak boleh sebelum tanggal pinjam.');
            return false;
        }

        // Cek konflik
        $this->load->model('Master_bmn_model');
        if ($this->Master_bmn_model->is_conflict($bmn_id, $tanggal_pinjam, $tanggal_kembali)) {
            $this->session->set_flashdata('error', 'Barang sedang dipinjam pada tanggal tersebut. Pilih tanggal lain.');
            return false;
        }

        // Info barang
        $bmn = $this->db
            ->select('nama_barang, merek, kode_barang, nup')
            ->where('id', $bmn_id)
            ->get('master_bmn')
            ->row_array();

        // Insert ke peminjaman_bmn
        $this->db->insert('peminjaman_bmn', [
            'bmn_id'            => $bmn_id,
            'nama_peminjam'     => $userName,
            'unit_kerja'        => $unit_kerja,
            'tujuan_peminjaman' => $tujuan,
            'tanggal_pinjam'    => $tanggal_pinjam,
            'tanggal_kembali'   => $tanggal_kembali,
            'catatan'           => $catatan,
            'status'            => 'Menunggu',
            'selesai'           => 'Belum',
            'created_by'        => $userId,
        ]);
        $pinjaman_id = $this->db->insert_id();

        // Ringkasan untuk tiket
        $lines   = [];
        $lines[] = "Barang: " . ($bmn['nama_barang'] ?? '-');
        if (!empty($bmn['merek']))      $lines[] = "Merek: " . $bmn['merek'];
        if (!empty($bmn['kode_barang'])) $lines[] = "Kode: " . $bmn['kode_barang'] . " / NUP " . ($bmn['nup'] ?? '-');
        $lines[] = "Unit Kerja: " . $unit_kerja;
        $lines[] = "Tujuan: " . $tujuan;
        $lines[] = "Tgl Pinjam: " . date('d F Y', strtotime($tanggal_pinjam));
        $lines[] = "Rencana Kembali: " . date('d F Y', strtotime($tanggal_kembali));
        if ($catatan) $lines[] = "Catatan: " . $catatan;
        $lines[] = "[ref:peminjaman_bmn_id={$pinjaman_id}]";

        $data['jenis_kerusakan'] = implode("\n", $lines);
        $data['tanggal']         = $tanggal_pinjam;
    }
    /* =========================================
       PERMINTAAN / LAYANAN IT BIASA
    ========================================= */
    elseif ($kategori_slug === 'permintaan') {

        $jenis_layanan     = $this->input->post('jenis_layanan');
        $sub_kategori_data = $this->db
            ->get_where('master_sub_kategori', ['id' => $sub_kategori_id])
            ->row();
        $perlu_jadwal = $sub_kategori_data ? $sub_kategori_data->perlu_jadwal : 0;

        if ($perlu_jadwal == 1) {

            $tanggal_mulai   = $this->input->post('tanggal');
            $tanggal_selesai = $this->input->post('tanggal_selesai') ?: null;
            $jam             = $this->input->post('jam');
            $durasi          = $this->input->post('durasi');

            $data['tanggal']         = $tanggal_mulai;
            $data['tanggal_selesai'] = $tanggal_selesai;
            $data['jam']             = $jam;
            $data['durasi']          = $durasi;

            $jumlah_hari = 1;
            if ($tanggal_selesai) {
                $tM          = new DateTime($tanggal_mulai);
                $tS          = new DateTime($tanggal_selesai);
                $jumlah_hari = $tS->diff($tM)->days + 1;
            }

            $fmt_mulai = date('d F Y', strtotime($tanggal_mulai));
            $catatan   = [];
            $catatan[] = "Detail Permintaan: " . $jenis_layanan;

            if ($jumlah_hari > 1) {
                $fmt_selesai = date('d F Y', strtotime($tanggal_selesai));
                $catatan[]   = "Periode: {$fmt_mulai} s.d. {$fmt_selesai} ({$jumlah_hari} hari)";
            } else {
                $catatan[] = "Tanggal: " . $fmt_mulai;
            }

            $catatan[] = "Jam Mulai (per hari): " . $jam . " WIB";
            $catatan[] = "Durasi (per hari): "    . $durasi . " jam";

            if ($v = $this->input->post('jumlah_peserta'))         $catatan[] = "Jumlah Peserta: {$v} orang";
            if ($v = $this->input->post('jumlah_breakout'))        $catatan[] = "Jumlah Breakout Room: {$v}";
            if ($v = $this->input->post('penanggung_jawab'))       $catatan[] = "Penanggung Jawab: {$v}";
            if ($v = $this->input->post('host_cohost'))            $catatan[] = "Host/Co-Host: {$v}";
            if ($v = $this->input->post('permintaan_lain_jadwal')) $catatan[] = "Permintaan Lainnya:\n{$v}";

            $data['jenis_kerusakan'] = implode("\n", $catatan);

        } else {

            $catatan   = [];
            $catatan[] = "Detail Permintaan: " . $jenis_layanan;

            if ($lokasi = $this->input->post('lokasi_tanpa_jadwal')) {
                $catatan[]      = "Lokasi/Unit: " . $lokasi;
                $data['lokasi'] = $lokasi;
            }
            if ($v = $this->input->post('permintaan_lain_tanpa_jadwal')) {
                $catatan[] = "Catatan Tambahan:\n{$v}";
            }

            $data['jenis_kerusakan'] = implode("\n", $catatan);
        }
    }

    /* =========================================
       KATEGORI LAIN
    ========================================= */
    else {
        $perangkat_text = $this->input->post('perangkat');
        $deskripsi      = $this->input->post('jenis_kerusakan');

        if (!empty($perangkat_text)) {
            $deskripsi = "Perangkat: " . $perangkat_text . "\n\n" . $deskripsi;
        }

        $data['lokasi']          = $this->input->post('lokasi');
        $data['jenis_kerusakan'] = $deskripsi;
    }

    /* =========================================
       UPLOAD FOTO
    ========================================= */
    if (!empty($_FILES['foto']['name'])) {
        $config = [
            'upload_path'   => FCPATH . 'uploads/tiket/',
            'allowed_types' => 'jpg|jpeg|png',
            'encrypt_name'  => true,
            'max_size'      => 2048
        ];
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('foto')) {
            $data['foto'] = $this->upload->data('file_name');
        }
    }

    /* =========================================
       SIMPAN TIKET
    ========================================= */
    $this->db->insert('tickets', $data);

    /* =========================================
       KIRIM EMAIL
    ========================================= */
    $this->send_detailed_email_to_rizky(
        $data,
        $kategori_slug,
        $sub_kategori_data ?? null
    );

    return true;
}
    /* =========================
       KIRIM EMAIL LENGKAP KE RIZKY
    ========================= */
   private function send_detailed_email_to_rizky($ticket_data, $kategori_slug, $sub_kategori_data)
    {
        $rizky_email = 'ipds1400@bps.go.id';

        $kategori       = $this->db->get_where('master_kategori', ['id' => $ticket_data['kategori_id']])->row();
        $kategori_nama  = $kategori ? $kategori->nama_kategori : '-';
        $sub_kategori_nama = $sub_kategori_data ? $sub_kategori_data->nama_sub_kategori : '-';

        /* ---- Perangkat ---- */
        $perangkat_html = '';
        if (!empty($ticket_data['perangkat_id'])) {
            $perangkat = $this->db
                ->select('kode_barang, nup, nama_barang')
                ->where('id', $ticket_data['perangkat_id'])
                ->get('perangkat')
                ->row_array();

            if ($perangkat) {
                $perangkat_html = "
                <tr>
                    <td style='padding:10px;width:180px;vertical-align:top;color:#6b7280;'>
                        <strong>Perangkat</strong>
                    </td>
                    <td style='padding:10px;color:#1f2937;'>
                        <strong>{$perangkat['kode_barang']} | {$perangkat['nup']}</strong><br>
                        <span style='color:#6b7280;'>{$perangkat['nama_barang']}</span>
                    </td>
                </tr>";
            }
        }

        /* ---- Jadwal (dengan dukungan multi-day) ---- */
        $jadwal_html = '';
        if (!empty($ticket_data['tanggal'])) {

            $fmt_mulai   = date('d F Y', strtotime($ticket_data['tanggal']));
            $jam         = !empty($ticket_data['jam'])    ? $ticket_data['jam']    : '-';
            $durasi      = !empty($ticket_data['durasi']) ? $ticket_data['durasi'] . ' jam/hari' : '-';

            /* Multi-day */
            if (!empty($ticket_data['tanggal_selesai'])) {
                $tM          = new DateTime($ticket_data['tanggal']);
                $tS          = new DateTime($ticket_data['tanggal_selesai']);
                $jumlah_hari = $tS->diff($tM)->days + 1;
                $fmt_selesai = date('d F Y', strtotime($ticket_data['tanggal_selesai']));

                $jadwal_html = "
                <tr>
                    <td style='padding:10px;width:180px;vertical-align:top;color:#6b7280;'>
                        <strong>📅 Periode</strong>
                    </td>
                    <td style='padding:10px;color:#1f2937;'>
                        {$fmt_mulai} s.d. {$fmt_selesai}<br>
                        <span style='color:#6b7280;'>({$jumlah_hari} hari)</span>
                    </td>
                </tr>
                <tr>
                    <td style='padding:10px;width:180px;vertical-align:top;color:#6b7280;'>
                        <strong>🕐 Jam Mulai/Hari</strong>
                    </td>
                    <td style='padding:10px;color:#1f2937;'>
                        Pukul {$jam} WIB
                    </td>
                </tr>
                <tr>
                    <td style='padding:10px;width:180px;vertical-align:top;color:#6b7280;'>
                        <strong>⏱️ Durasi/Hari</strong>
                    </td>
                    <td style='padding:10px;color:#1f2937;'>
                        {$durasi}
                    </td>
                </tr>";

            /* Single-day */
            } else {
                $jadwal_html = "
                <tr>
                    <td style='padding:10px;width:180px;vertical-align:top;color:#6b7280;'>
                        <strong>📅 Tanggal</strong>
                    </td>
                    <td style='padding:10px;color:#1f2937;'>
                        {$fmt_mulai}<br>
                        <span style='color:#6b7280;'>Pukul {$jam} WIB</span>
                    </td>
                </tr>
                <tr>
                    <td style='padding:10px;width:180px;vertical-align:top;color:#6b7280;'>
                        <strong>⏱️ Durasi</strong>
                    </td>
                    <td style='padding:10px;color:#1f2937;'>
                        {$durasi}
                    </td>
                </tr>";
            }
        }

        /* ---- Lokasi ---- */
        $lokasi_html = '';
        if (!empty($ticket_data['lokasi'])) {
            $lokasi_html = "
            <tr>
                <td style='padding:10px;width:180px;vertical-align:top;color:#6b7280;'>
                    <strong>📍 Lokasi</strong>
                </td>
                <td style='padding:10px;color:#1f2937;'>
                    {$ticket_data['lokasi']}
                </td>
            </tr>";
        }

        /* ---- Foto ---- */
        $foto_html = '';
        if (!empty($ticket_data['foto'])) {
            $foto_url  = base_url('uploads/tiket/' . $ticket_data['foto']);
            $foto_html = "
            <tr>
                <td colspan='2' style='padding:20px 10px 10px 10px;'>
                    <strong style='color:#6b7280;'>📷 Foto Lampiran:</strong>
                </td>
            </tr>
            <tr>
                <td colspan='2' style='padding:0 10px 10px 10px;'>
                    <img src='{$foto_url}' alt='Foto Tiket'
                         style='max-width:100%;height:auto;border-radius:8px;border:1px solid #e5e7eb;'>
                </td>
            </tr>";
        }

        $deskripsi   = nl2br(htmlspecialchars($ticket_data['jenis_kerusakan']));
        $created_at  = date('d F Y, H:i', strtotime($ticket_data['created_at']));
        $subject     = "🎫 [{$ticket_data['kode_tiket']}] Tiket Baru - {$kategori_nama}";

        $message = "
        <!DOCTYPE html>
        <html>
        <head><meta charset='UTF-8'><meta name='viewport' content='width=device-width,initial-scale=1.0'></head>
        <body style='margin:0;padding:0;background-color:#f3f4f6;font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Roboto,\"Helvetica Neue\",Arial,sans-serif;'>
            <table width='100%' cellpadding='0' cellspacing='0' style='background-color:#f3f4f6;padding:40px 20px;'>
                <tr><td align='center'>
                    <table width='100%' style='max-width:600px;' cellpadding='0' cellspacing='0'>

                        <!-- HEADER -->
                        <tr>
                            <td style='background:linear-gradient(135deg,#dc2626 0%,#b91c1c 100%);padding:40px 30px;border-radius:12px 12px 0 0;'>
                                <div style='font-size:48px;margin-bottom:10px;'>🎫</div>
                                <h1 style='margin:0;color:#ffffff;font-size:28px;font-weight:bold;'>Tiket Baru Masuk!</h1>
                                <p style='margin:12px 0 0 0;color:#fecaca;font-size:15px;'>Ada tiket baru yang membutuhkan penanganan Anda</p>
                            </td>
                        </tr>

                        <!-- CONTENT -->
                        <tr>
                            <td style='background-color:#ffffff;'>

                                <!-- Kode Tiket -->
                                <table width='100%' cellpadding='0' cellspacing='0'
                                       style='background:linear-gradient(135deg,#fef3c7 0%,#fde68a 100%);padding:25px 30px;border-bottom:3px solid #f59e0b;'>
                                    <tr>
                                        <td>
                                            <h2 style='margin:0 0 8px 0;color:#92400e;font-size:24px;font-weight:bold;'>
                                                {$ticket_data['kode_tiket']}
                                            </h2>
                                            <span style='display:inline-block;padding:6px 16px;background:#dc2626;color:#ffffff;border-radius:20px;font-size:13px;font-weight:600;'>
                                                ⏳ MENUNGGU PENANGANAN
                                            </span>
                                        </td>
                                    </tr>
                                </table>

                                <!-- Detail -->
                                <table width='100%' cellpadding='0' cellspacing='0' style='padding:30px;'>
                                    <tr>
                                        <td style='padding:10px;width:180px;vertical-align:top;color:#6b7280;'><strong>👤 Pelapor</strong></td>
                                        <td style='padding:10px;color:#1f2937;'><strong>{$ticket_data['pelapor']}</strong></td>
                                    </tr>
                                    <tr>
                                        <td style='padding:10px;width:180px;vertical-align:top;color:#6b7280;'><strong>📂 Kategori</strong></td>
                                        <td style='padding:10px;color:#1f2937;'>{$kategori_nama}</td>
                                    </tr>
                                    <tr>
                                        <td style='padding:10px;width:180px;vertical-align:top;color:#6b7280;'><strong>🏷️ Sub Kategori</strong></td>
                                        <td style='padding:10px;color:#1f2937;'>{$sub_kategori_nama}</td>
                                    </tr>

                                    {$lokasi_html}
                                    {$perangkat_html}
                                    {$jadwal_html}

                                    <tr>
                                        <td style='padding:10px;width:180px;vertical-align:top;color:#6b7280;'><strong>🕐 Waktu Laporan</strong></td>
                                        <td style='padding:10px;color:#1f2937;'>{$created_at} WIB</td>
                                    </tr>

                                    <!-- Divider -->
                                    <tr><td colspan='2' style='padding:20px 10px;'>
                                        <div style='border-top:2px solid #e5e7eb;'></div>
                                    </td></tr>

                                    <!-- Deskripsi -->
                                    <tr>
                                        <td colspan='2' style='padding:10px;'>
                                            <strong style='color:#6b7280;font-size:14px;'>📝 DESKRIPSI / DETAIL:</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan='2' style='padding:10px;background:#f9fafb;border-left:4px solid #dc2626;border-radius:6px;'>
                                            <div style='color:#374151;line-height:1.7;font-size:14px;'>
                                                {$deskripsi}
                                            </div>
                                        </td>
                                    </tr>

                                    {$foto_html}
                                </table>

                                <!-- CTA -->
                                <table width='100%' cellpadding='0' cellspacing='0' style='padding:0 30px 30px 30px;'>
                                    <tr>
                                        <td align='center' style='padding:20px 0;'>
                                            <a href='" . base_url('/ticket') . "'
                                               style='display:inline-block;padding:16px 40px;background:linear-gradient(135deg,#dc2626 0%,#b91c1c 100%);color:#ffffff;text-decoration:none;border-radius:8px;font-weight:bold;font-size:16px;'>
                                                🚀 Lihat Tiket di Sistem
                                            </a>
                                        </td>
                                    </tr>
                                </table>

                            </td>
                        </tr>

                        <!-- FOOTER -->
                        <tr>
                            <td style='background-color:#1f2937;padding:25px 30px;border-radius:0 0 12px 12px;text-align:center;'>
                                <p style='margin:0;color:#9ca3af;font-size:13px;line-height:1.6;'>
                                    Email ini dikirim secara otomatis oleh sistem<br>
                                    <strong style='color:#ffffff;'>SIPASTI - Sistem Informasi Pengelolaan Aset dan Layanan TI</strong><br>
                                    Segera tangani tiket ini untuk memberikan pelayanan terbaik
                                </p>
                            </td>
                        </tr>

                    </table>
                </td></tr>
            </table>
        </body>
        </html>
        ";

        $this->load->helper('email');
        send_ticket_email($rizky_email, $subject, $message);
    }

    /* =========================
       HISTORY USER
    ========================= */
public function get_history_limit($user_id, $limit, $offset)
{
    $rows = $this->db
        ->select('
            t.*,
            mk.nama_kategori,
            mk.slug as kategori_slug,
            msk.nama_sub_kategori,
            msk.slug as sub_kategori_slug,
            msk.perlu_jadwal,
            p.nama_barang,
            u.name as nama_teknisi
        ')
        ->from('tickets t')
        ->join('master_kategori mk',      'mk.id = t.kategori_id',       'left')
        ->join('master_sub_kategori msk', 'msk.id = t.sub_kategori_id',  'left')
        ->join('perangkat p',             'p.id = t.perangkat_id',        'left')
        ->join('teknisi tek',             'tek.id = t.teknisi_id',        'left')
        ->join('users u',                 'u.id = tek.user_id',           'left')
        ->where('t.user_id', $user_id)
        ->order_by('t.created_at', 'DESC')
        ->limit($limit, $offset)
        ->get()
        ->result_array();

    // Lampirkan items persediaan per tiket (1 query batch, limit 10 jadi ringan).
    if (!empty($rows) && $this->db->table_exists('ticket_persediaan_items')) {
        $ids = array_values(array_unique(array_map(function ($r) {
            return (int) ($r['id'] ?? 0);
        }, $rows)));
        $ids = array_values(array_filter($ids));
        $map = [];
        if (!empty($ids)) {
            $hasAcc = $this->db->field_exists('jumlah_disetujui', 'ticket_persediaan_items');
            $hasKet = $this->db->field_exists('keterangan', 'ticket_persediaan_items');
            $hasRate = $this->db->field_exists('rating', 'ticket_persediaan_items');
            $hasKomen = $this->db->field_exists('komentar_item', 'ticket_persediaan_items');
            $items = $this->db
                ->select('i.ticket_id, i.barang_id, i.jumlah' . ($hasAcc ? ', i.jumlah_disetujui' : '') . ($hasKet ? ', i.keterangan' : '') . ($hasRate ? ', i.rating' : '') . ($hasKomen ? ', i.komentar_item' : '') . ', i.nama_barang_snapshot, i.satuan_snapshot, b.kode_barang, b.nama_barang, b.satuan')
                ->from('ticket_persediaan_items i')
                ->join('master_barang_persediaan b', 'b.id = i.barang_id', 'left')
                ->where_in('i.ticket_id', $ids)
                ->order_by('i.ticket_id', 'ASC')
                ->order_by('i.id', 'ASC')
                ->get()
                ->result_array();
            foreach ($items as $it) {
                $map[(int) $it['ticket_id']][] = $it;
            }
        }
        foreach ($rows as &$r) {
            $r['persediaan_items'] = $map[(int) ($r['id'] ?? 0)] ?? [];
        }
        unset($r);
    }

    return $rows;
}

    public function count_history($user_id)
    {
        return $this->db
            ->where('user_id', $user_id)
            ->count_all_results('tickets');
    }

    /* =========================
       RATING
       tickets.rating menyimpan rating layanan.
       Persediaan juga menyimpan rating per barang di ticket_persediaan_items.
    ========================= */
    public function update_rating($ticket_id, $user_id, $rating, $komentar = null)
    {
        $ticket = $this->db
            ->where('id', $ticket_id)
            ->where('user_id', $user_id)
            ->where('status', 'Selesai')
            ->get('tickets')
            ->row_array();

        if (!$ticket) {
            $this->session->set_flashdata('error', 'Tiket tidak valid atau belum selesai');
            return false;
        }

        $this->db->where('id', $ticket_id);
        $this->db->where('user_id', $user_id);
        $this->db->update('tickets', [
            'rating'   => (int) $rating,
            'komentar' => $komentar
        ]);

        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('success', 'Terima kasih atas rating Anda! ⭐');
            return true;
        }

        $this->session->set_flashdata('error', 'Gagal menyimpan rating');
        return false;
    }

    /* =========================
       RATING BARANG PERSEDIAAN PER BARANG (barang_id)
       Validasi: tiket milik user + Selesai, barang bagian tiket, nilai 1-5.
       Sekali per pasangan tiket-barang; update bila sudah ada.
    ========================= */
    public function save_rating_barang($ticket_id, $user_id, $ratings, $komentar = null, $rating_layanan = null, $ulasan_barang = [])
    {
        $ticket_id = (int) $ticket_id;
        $rating_layanan = ($rating_layanan === null || $rating_layanan === '')
            ? null : (int) $rating_layanan;
        if ($ticket_id <= 0 || !is_array($ratings)
            || ($rating_layanan !== null && ($rating_layanan < 1 || $rating_layanan > 5))) {
            $this->session->set_flashdata('error', 'Data rating barang tidak valid.');
            return false;
        }
        $ticket = $this->db
            ->where('id', $ticket_id)
            ->where('user_id', $user_id)
            ->where('status', 'Selesai')
            ->get('tickets')
            ->row_array();
        if (!$ticket) {
            $this->session->set_flashdata('error', 'Tiket tidak valid atau belum selesai');
            return false;
        }
        $clean = [];
        if (!empty($ratings)) {
            if (!$this->db->table_exists('ticket_persediaan_items')
                || !$this->db->field_exists('rating', 'ticket_persediaan_items')) {
                $this->session->set_flashdata('error', 'Fitur rating barang belum tersedia.');
                return false;
            }
            $hasKomen = $this->db->field_exists('komentar_item', 'ticket_persediaan_items');
            $rows = $this->db->where('ticket_id', $ticket_id)->get('ticket_persediaan_items')->result_array();
            $valid_ids = [];
            foreach ($rows as $r) $valid_ids[(int) $r['barang_id']] = true;
            foreach ($ratings as $bid => $val) {
                $bid = (int) $bid;
                $val = (int) $val;
                if ($bid <= 0 || $val < 1 || $val > 5) continue;
                if (!isset($valid_ids[$bid])) continue;
                $clean[$bid] = $val;
            }
        }
        if (empty($clean) && $rating_layanan === null) {
            $this->session->set_flashdata('error', 'Beri rating layanan atau minimal satu barang.');
            return false;
        }
        foreach ($clean as $bid => $val) {
            $upd = ['rating' => $val];
            if ($hasKomen) {
                if (is_array($ulasan_barang) && !empty($ulasan_barang[$bid]) && trim((string) $ulasan_barang[$bid]) !== '') {
                    $upd['komentar_item'] = substr(trim((string) $ulasan_barang[$bid]), 0, 1000);
                } elseif ($komentar !== null && $komentar !== '') {
                    $upd['komentar_item'] = substr((string) $komentar, 0, 1000);
                }
            }
            $this->db->where('ticket_id', $ticket_id)->where('barang_id', $bid)->update('ticket_persediaan_items', $upd);
        }
        $ticket_update = [];
        if ($rating_layanan !== null) $ticket_update['rating'] = $rating_layanan;
        if ($komentar !== null && $komentar !== '') {
            $ticket_update['komentar'] = substr((string) $komentar, 0, 1000);
        }
        if (!empty($ticket_update)) {
            $this->db->where('id', $ticket_id)->where('user_id', $user_id)->update('tickets', $ticket_update);
        }
        $this->session->set_flashdata('success', 'Terima kasih atas rating layanan dan ulasan barang Anda! ⭐');
        return true;
    }

    /* =========================
       GET ULASAN & PENILAIAN BARANG
    ========================= */
    public function get_ulasan_barang($barang_id)
    {
        $barang_id = (int) $barang_id;
        $out = [
            'rating_avg'   => 0,
            'rating_count' => 0,
            'ulasan_count' => 0,
            'star_counts'  => [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0],
            'ulasan_list'  => [],
        ];

        if (!$this->db->table_exists('ticket_persediaan_items') || !$this->db->field_exists('rating', 'ticket_persediaan_items')) {
            return $out;
        }

        $hasKomen = $this->db->field_exists('komentar_item', 'ticket_persediaan_items');

        $this->db->select('i.id, i.rating, ' . ($hasKomen ? 'i.komentar_item, ' : "'' AS komentar_item, ") . 'i.created_at, t.kode_tiket, t.pelapor, u.name as user_name');
        $this->db->from('ticket_persediaan_items i');
        $this->db->join('tickets t', 't.id = i.ticket_id', 'left');
        $this->db->join('users u', 'u.id = t.user_id', 'left');
        $this->db->where('i.barang_id', $barang_id);
        $this->db->where('i.rating >=', 1);
        $this->db->where('i.rating <=', 5);
        $this->db->order_by('i.id', 'DESC');
        $rows = $this->db->get()->result_array();

        if (empty($rows)) {
            return $out;
        }

        $total_stars  = 0;
        $rating_count = count($rows);
        $ulasan_count = 0;

        foreach ($rows as $r) {
            $star = (int) $r['rating'];
            $total_stars += $star;
            if (isset($out['star_counts'][$star])) {
                $out['star_counts'][$star]++;
            }
            $komentar = trim((string) ($r['komentar_item'] ?? ''));
            if ($komentar !== '') {
                $ulasan_count++;
            }
        }

        $out['rating_avg']   = round($total_stars / $rating_count, 1);
        $out['rating_count'] = $rating_count;
        $out['ulasan_count'] = $ulasan_count;
        $out['ulasan_list']  = $rows;

        return $out;
    }

    /* =========================
       SEARCH PERANGKAT
    ========================= */
    public function search_perangkat($q)
    {
        $q           = trim($q);
        $kode_barang = $q;
        $nup         = null;

        if (preg_match('/^(\d+?)(\d{1,4})$/', $q, $match)) {
            $kode_barang = $match[1];
            $nup         = ltrim($match[2], '0');
        }

        $this->db
            ->select('id, kode_barang, nup, nama_barang')
            ->from('perangkat')
            ->group_start()
                ->like('kode_barang', $q)
                ->or_like('nup', $q)
                ->or_where("CONCAT(kode_barang, nup) =", $q);

        if ($nup !== null) {
            $this->db->or_group_start()
                ->like('kode_barang', $kode_barang)
                ->where('nup', $nup)
            ->group_end();
        }

        $this->db
            ->group_end()
            ->limit(10)
            ->order_by('kode_barang', 'ASC');

        return $this->db->get()->result_array();
    }

    /* =========================
   AMBIL TIKET UNTUK EDIT (MILIK USER)
========================= */
public function get_ticket_for_edit($ticket_id, $user_id)
{
    return $this->db
        ->select('t.*, mk.slug as kategori_slug, msk.nama_sub_kategori')
        ->from('tickets t')
        ->join('master_kategori mk',      'mk.id = t.kategori_id',      'left')
        ->join('master_sub_kategori msk', 'msk.id = t.sub_kategori_id', 'left')
        ->where('t.id', $ticket_id)
        ->where('t.user_id', $user_id)
        ->where_in('t.status', ['Menunggu', 'On Progress'])
        ->get()
        ->row_array();
}

/* =========================
   UPDATE TIKET OLEH USER
========================= */
public function update_ticket_by_user($ticket_id, $user_id, $data)
{
    $this->db
        ->where('id', $ticket_id)
        ->where('user_id', $user_id)
        ->where_in('status', ['Menunggu', 'On Progress'])
        ->update('tickets', $data);

    return $this->db->affected_rows() > 0;
}

/* =========================
   ITEMS PERSEDIAAN UNTUK EDIT (MILIK USER)
========================= */
public function get_persediaan_items_for_edit($ticket_id, $user_id)
{
    if (!$this->db->table_exists('ticket_persediaan_items')) return [];

    $t = $this->db
        ->select('id')
        ->where('id', (int) $ticket_id)
        ->where('user_id', $user_id)
        ->where_in('status', ['Menunggu', 'On Progress'])
        ->get('tickets')
        ->row();
    if (!$t) return [];

    return $this->db
        ->select('i.barang_id, i.jumlah, i.nama_barang_snapshot, i.satuan_snapshot, b.kode_barang, b.nama_barang, b.satuan, b.status')
        ->from('ticket_persediaan_items i')
        ->join('master_barang_persediaan b', 'b.id = i.barang_id', 'left')
        ->where('i.ticket_id', (int) $ticket_id)
        ->order_by('i.id', 'ASC')
        ->get()
        ->result_array();
}

/* =========================
   UPDATE PERSEDIAAN OLEH USER (transaksional)
   Rebuild jenis_kerusakan format checkout agar parser teknisi/admin tetap jalan.
   ponytail: ringkasan tanpa baris Judul; judul hanya di kebutuhan. Tambah baris Judul bila parser dukung.
========================= */
public function update_persediaan_by_user($ticket_id, $user_id, $ticket_data, $items, $meta)
{
    $ringkas = ['[PERMINTAAN BARANG PERSEDIAAN]'];
    $total   = 0;
    foreach ($items as $s) {
        $ringkas[] = '- ' . ($s['kode'] ?? '-') . ' - ' . $s['nama'] . ' x ' . (int) $s['jumlah'] . ' ' . ($s['satuan'] ?? '');
        $total += (int) $s['jumlah'];
    }
    $ringkas[] = '';
    $ringkas[] = 'Keperluan: ' . ($meta['keperluan'] ?? '');
    if (!empty($meta['unit_kerja'])) $ringkas[] = 'Unit Kerja: ' . $meta['unit_kerja'];
    $tgl = !empty($meta['tanggal_permintaan']) ? $meta['tanggal_permintaan'] : ($meta['tanggal_dibutuhkan'] ?? '');
    if (!empty($tgl)) $ringkas[] = 'Tanggal permintaan: ' . $tgl;
    $ringkas[] = 'Total: ' . count($items) . ' jenis, ' . $total . ' item';
    $ringkas[] = 'Catatan: keranjang hanya menandai kebutuhan, stok berkurang saat teknisi menyetujui.';
    $ticket_data['jenis_kerusakan'] = implode("\n", $ringkas);

    $this->db->trans_start();
    $this->db
        ->where('id', (int) $ticket_id)
        ->where('user_id', $user_id)
        ->where_in('status', ['Menunggu', 'On Progress'])
        ->update('tickets', $ticket_data);
    $this->db->where('ticket_id', (int) $ticket_id)->delete('ticket_persediaan_items');
    foreach ($items as $s) {
        $this->db->insert('ticket_persediaan_items', [
            'ticket_id'            => (int) $ticket_id,
            'barang_id'            => (int) $s['barang_id'],
            'jumlah'               => (int) $s['jumlah'],
            'nama_barang_snapshot' => substr($s['nama'], 0, 150),
            'satuan_snapshot'      => substr($s['satuan'], 0, 50),
        ]);
    }
    $this->db->trans_complete();

    return $this->db->trans_status();
}

/* =========================
   AUTO RATING 5 BINTANG (24 JAM)
========================= */
public function auto_rate_expired_tickets($user_id)
{
    $tickets = $this->db
        ->where('user_id', $user_id)
        ->where('status', 'Selesai')
        ->where('rating IS NULL', null, false)
        ->where('selesai_at <= DATE_SUB(NOW(), INTERVAL 24 HOUR)', null, false)
        ->get('tickets')
        ->result_array();

    if (empty($tickets)) return 0;

    $this->db
        ->where('user_id', $user_id)
        ->where('status', 'Selesai')
        ->where('rating IS NULL', null, false)
        ->where('selesai_at <= DATE_SUB(NOW(), INTERVAL 24 HOUR)', null, false)
        ->update('tickets', [
            'rating'   => 5,
            'komentar' => 'Dinilai otomatis oleh sistem'
        ]);

    $n = $this->db->affected_rows();

    // Konsisten: rating item persediaan yang masih NULL ikut jadi 5.
    if ($n > 0 && $this->db->table_exists('ticket_persediaan_items')
        && $this->db->field_exists('rating', 'ticket_persediaan_items')) {
        $ids = array_column($tickets, 'id');
        $ids = array_values(array_filter(array_map('intval', $ids)));
        if (!empty($ids)) {
            // Hanya NULL -> 5, agar AVG konsisten dengan tickets.rating.
            $this->db->query('UPDATE ticket_persediaan_items SET rating = 5 WHERE ticket_id IN (' . implode(',', $ids) . ') AND rating IS NULL');
        }
    }

    return $n;
}

public function get_zoom_ticket_for_upload($ticket_id, $user_id)
{
    return $this->db
        ->select('t.id, t.dokumen_peserta, msk.perlu_jadwal, mk.slug as kategori_slug')
        ->from('tickets t')
        ->join('master_kategori mk',      'mk.id = t.kategori_id',      'left')
        ->join('master_sub_kategori msk', 'msk.id = t.sub_kategori_id', 'left')
        ->where('t.id',      $ticket_id)
        ->where('t.user_id', $user_id)
        ->where('mk.slug',   'permintaan')
        ->where('msk.perlu_jadwal', 1)
        ->get()
        ->row_array();
}

/* ===============================
   KATALOG PERSEDIAAN + SISA (user, bulan+tahun kini)
   Rumus: stok_awal + SUM(masuk) - SUM(keluar), default 0
================================ */
public function get_sisa_persediaan($barang_id, $bulan, $tahun)
{
    $barang_id = (int) $barang_id;
    $bulan     = (int) $bulan;
    $tahun     = (int) $tahun;

    if ($barang_id <= 0) return 0;

    $sa = $this->db
        ->where('barang_id', $barang_id)
        ->where('bulan', $bulan)
        ->where('tahun', $tahun)
        ->get('stok_awal_persediaan')
        ->row();
    $awal = $sa ? (int) $sa->jumlah : 0;

    $ag = $this->db
        ->select('jenis, COALESCE(SUM(jumlah),0) AS total', false)
        ->from('transaksi_persediaan')
        ->where('barang_id', $barang_id)
        ->where('MONTH(tanggal)', $bulan)
        ->where('YEAR(tanggal)', $tahun)
        ->group_by('jenis')
        ->get()
        ->result();

    $masuk = 0; $keluar = 0;
    foreach ($ag as $r) {
        if (($r->jenis ?? '') === 'masuk') $masuk = (int) $r->total;
        else $keluar += (int) $r->total;
    }

    return $awal + $masuk - $keluar;
}

public function get_katalog_persediaan_with_sisa($bulan = null, $tahun = null)
{
    $bulan = $bulan ?: (int) date('n');
    $tahun = $tahun ?: (int) date('Y');

    $rows = $this->db
        ->select('b.id, b.kode_barang, b.nama_barang, b.satuan, b.gambar, b.stok_minimum, b.status, b.kategori_id, k.kode AS kategori_kode, k.nama AS kategori_nama')
        ->from('master_barang_persediaan b')
        ->join('master_kategori_persediaan k', 'k.id = b.kategori_id', 'left')
        ->where('b.status', 'Aktif')
        ->get()
        ->result_array();

    usort($rows, function ($a, $b) {
        return strnatcasecmp($a['kode_barang'] ?? '', $b['kode_barang'] ?? '');
    });

    // Agregat rating per barang (hanya 1-5, abaikan NULL). Satu query.
    $agg = [];
    if ($this->db->table_exists('ticket_persediaan_items')
        && $this->db->field_exists('rating', 'ticket_persediaan_items')) {
        $q = $this->db->query('SELECT barang_id, AVG(rating) AS rating_avg, COUNT(rating) AS rating_count FROM ticket_persediaan_items WHERE rating BETWEEN 1 AND 5 GROUP BY barang_id');
        foreach ($q->result_array() as $r) $agg[(int) $r['barang_id']] = $r;
    }

    foreach ($rows as &$r) {
        $r['sisa'] = $this->get_sisa_persediaan($r['id'], $bulan, $tahun);
        $a = $agg[(int) $r['id']] ?? null;
        $r['rating_avg'] = $a ? round((float) $a['rating_avg'], 1) : null;
        $r['rating_count'] = $a ? (int) $a['rating_count'] : 0;
    }
    unset($r);

    return $rows;
}

/* ===============================
   SUB KATEGORI PERMINTAAN PERSEDIAAN (seed bila belum ada)
   Prefer slug 'permintaan-persediaan', fallback slug lama.
================================ */
public function ensure_persediaan_sub()
{
    $sub = $this->db
        ->get_where('master_sub_kategori', ['slug' => 'permintaan-persediaan'])
        ->row();
    if ($sub) return $sub;

    $old = $this->db
        ->get_where('master_sub_kategori', ['slug' => 'permintaan-barang-persediaan'])
        ->row();
    if ($old) return $old;

    $kat = $this->db
        ->get_where('master_kategori', ['slug' => 'permintaan'])
        ->row();
    if (!$kat) return null;

    $this->db->insert('master_sub_kategori', [
        'kategori_id'       => $kat->id,
        'nama_sub_kategori' => 'Permintaan Persediaan',
        'slug'              => 'permintaan-persediaan',
        'perlu_jadwal'      => 0,
        'created_at'        => date('Y-m-d H:i:s'),
    ]);

    return $this->db
        ->get_where('master_sub_kategori', ['slug' => 'permintaan-persediaan'])
        ->row();
}

/* ===============================
   SUB KATEGORI PENGAJUAN PERSEDIAAN (seed bila belum ada)
   Untuk barang yang BELUM ada di daftar/katalog persediaan.
================================ */
public function ensure_pengajuan_persediaan_sub()
{
    $sub = $this->db
        ->get_where('master_sub_kategori', ['slug' => 'pengajuan-persediaan'])
        ->row();
    if ($sub) return $sub;

    $kat = $this->db
        ->get_where('master_kategori', ['slug' => 'permintaan'])
        ->row();
    if (!$kat) return null;

    $this->db->insert('master_sub_kategori', [
        'kategori_id'       => $kat->id,
        'nama_sub_kategori' => 'Pengajuan Persediaan',
        'slug'              => 'pengajuan-persediaan',
        'perlu_jadwal'      => 0,
        'created_at'        => date('Y-m-d H:i:s'),
    ]);

    return $this->db
        ->get_where('master_sub_kategori', ['slug' => 'pengajuan-persediaan'])
        ->row();
}

/* ===============================
   GET RUANGAN AKTIF (AJAX)
=============================== */
public function get_ruangan_aktif()
{
    $this->load->model('Ruang_rapat_model');
    $ruangan = $this->Ruang_rapat_model->get_aktif();

    header('Content-Type: application/json');
    echo json_encode($ruangan);
}
/* ===============================
   CEK KONFLIK RUANG RAPAT
   (dipanggil sebelum insert tiket)
=============================== */
public function cek_konflik_ruang_rapat($ruangan_id, $tanggal, $jam_mulai, $jam_selesai)
{
    return $this->db
        ->where('ruangan_id', $ruangan_id)
        ->where('tanggal', $tanggal)
        ->where_in('status', ['Menunggu', 'Disetujui'])
        ->group_start()
            ->where('jam_mulai <', $jam_selesai)
            ->where('jam_selesai >', $jam_mulai)
        ->group_end()
        ->count_all_results('peminjaman_ruang_rapat') > 0;
}

/* ===============================
   CEK KONFLIK KENDARAAN
=============================== */
public function cek_konflik_kendaraan($kendaraan_id, $tgl_pinjam, $tgl_kembali, $jam_pinjam = null, $jam_kembali = null)
{
    if ($jam_pinjam && $jam_kembali && $tgl_pinjam === $tgl_kembali) {
        return $this->db
            ->where('kendaraan_id', $kendaraan_id)
            ->where('tipe_pinjam', 'per_jam')
            ->where('tanggal_pinjam', $tgl_pinjam)
            ->where_in('status', ['Menunggu', 'Disetujui'])
            ->group_start()
                ->where('jam_pinjam <', $jam_kembali)
                ->where('jam_kembali >', $jam_pinjam)
            ->group_end()
            ->count_all_results('peminjaman_kendaraan') > 0;
    }

    return $this->db
        ->where('kendaraan_id', $kendaraan_id)
        ->where_in('status', ['Menunggu', 'Disetujui'])
        ->where('tanggal_pinjam <=', $tgl_kembali)
        ->where('tanggal_kembali >=', $tgl_pinjam)
        ->count_all_results('peminjaman_kendaraan') > 0;
}

/* ===============================
   CEK KONFLIK BMN
=============================== */
public function cek_konflik_bmn($bmn_id, $tgl_pinjam, $tgl_kembali)
{
    return $this->db
        ->where('bmn_id', $bmn_id)
        ->where_in('status', ['Menunggu', 'Disetujui'])
        ->where('tanggal_pinjam <=', $tgl_kembali)
        ->where('tanggal_kembali >=', $tgl_pinjam)
        ->count_all_results('peminjaman_bmn') > 0;
}
}