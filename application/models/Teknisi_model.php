<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teknisi_model extends CI_Model {

    /* =========================================================
       ADMIN: LIST SEMUA TEKNISI
    ========================================================= */
public function get_all()
{
    return $this->db
        ->select('
            t.id AS id,
            u.name AS name,
            t.keahlian,
            t.status,
            t.alasan_offline,
            COUNT(k.id) AS tiket_aktif
        ')
        ->from('teknisi t')
        ->join('users u', 'u.id = t.user_id', 'left')
        ->join(
            'tickets k',
            "k.teknisi_id = t.id AND k.status = 'On Progress'",
            'left'
        )
        ->group_by('t.id')
        ->get()
        ->result_array();
}

public function get_by_id($id)
{
    return $this->db
        ->select('t.id, u.name, t.keahlian, t.status, t.alasan_offline')
        ->from('teknisi t')
        ->join('users u', 'u.id = t.user_id', 'left')
        ->where('t.id', $id)
        ->get()
        ->row_array();
}

    /* =========================================================
       TEKNISI LOGIN (DIRINYA SENDIRI)
    ========================================================= */
    public function get_by_user($user_id)
    {
        return $this->db
            ->select('
                t.id,
                u.name,
                t.keahlian,
                t.status
            ')
            ->from('teknisi t')
            ->join('users u', 'u.id = t.user_id', 'left')
            ->where('t.user_id', $user_id)
            ->get()
            ->row_array();
    }

    /* =========================================================
       LIST TEKNISI BERDASARKAN STATUS
    ========================================================= */
    public function get_by_status($status)
    {
        return $this->db
            ->select('t.id AS id, u.name AS name')
            ->from('teknisi t')
            ->join('users u', 'u.id = t.user_id', 'left')
            ->where('t.status', $status)
            ->get()
            ->result_array();
    }

    /* =========================================================
       CRUD TEKNISI
    ========================================================= */
    public function insert($data)
    {
        return $this->db->insert('teknisi', $data);
    }

    public function update($id, $data)
    {
        return $this->db
            ->where('id', $id)
            ->update('teknisi', $data);
    }

    public function update_by_user($user_id, $data)
    {
        return $this->db
            ->where('user_id', $user_id)
            ->update('teknisi', $data);
    }

    public function delete($id)
    {
        return $this->db
            ->where('id', $id)
            ->delete('teknisi');
    }

    /* =========================================================
       COUNT TEKNISI BERDASARKAN STATUS
    ========================================================= */
    public function count_by_status($status)
    {
        return $this->db
            ->where('status', $status)
            ->count_all_results('teknisi');
    }

    /* =========================================================
       TIKET MILIK TEKNISI (DASHBOARD TEKNISI)
       ✔ FIX NAMA TEKNISI (BUKAN ADMIN)
    ========================================================= */
public function get_ticket_teknisi($user_id)
{
   return $this->db
    ->select('
        t.id,
        t.kode_tiket,
        t.status,
        t.created_at,
        t.selesai_at,
        t.lokasi,
        t.layanan_link,
        t.undangan_link,
        t.jenis_kerusakan,
        t.foto,
        t.layanan_catatan,
        t.layanan_file,
        t.dokumen_peserta,
        t.tanggal,
        t.tanggal_selesai,
        t.jam,
        t.durasi,
        t.sub_kategori_id,
        t.kategori_id,
        t.akun_zoom,
        pelapor.name AS pelapor,
        pelapor.email AS email,
        up.phone AS no_hp,
        t.teknisi_id,
        teknisi_user.name AS nama_teknisi,
        mk.nama_kategori,
        sub.nama_sub_kategori
    ')
    ->from('tickets t')

        // pelapor tiket
        ->join('users pelapor', 'pelapor.id = t.user_id', 'left')
        ->join('user_profiles up', 'up.user_id = pelapor.id', 'left')

        // teknisi yang menangani
        ->join('teknisi tek', 'tek.id = t.teknisi_id', 'left')
        ->join('users teknisi_user', 'teknisi_user.id = tek.user_id', 'left')

        // kategori
        ->join('master_kategori mk', 'mk.id = t.kategori_id', 'left')
        
        // sub kategori
        ->join('master_sub_kategori sub', 'sub.id = t.sub_kategori_id', 'left')

        ->where('tek.user_id', $user_id)
        ->where_in('t.status', ['On Progress', 'Selesai'])
        ->order_by('t.created_at', 'DESC')
        ->get()
        ->result_array();
}


    /* =========================================================
       TIKET BELUM DIAMBIL TEKNISI
    ========================================================= */
    public function get_ticket_available()
    {
        return $this->db
            ->select('
                t.id,
                t.kode_tiket,
                t.lokasi,
                t.status,
                t.created_at,
                t.jenis_kerusakan,
                t.foto,
                t.tanggal,
                t.jam,
                u.name AS pelapor,
                u.email AS email,
                up.phone AS no_hp,
                mk.nama_kategori,
                sub.nama_sub_kategori
            ')
            ->select('NULL AS nama_teknisi', FALSE)
            ->from('tickets t')
            ->join('users u','u.id = t.user_id','left')
            ->join('user_profiles up', 'up.user_id = u.id', 'left')
            ->join('master_kategori mk', 'mk.id = t.kategori_id', 'left')
            ->join('master_sub_kategori sub', 'sub.id = t.sub_kategori_id', 'left')
            ->where('t.teknisi_id IS NULL', null, false)
            ->where('t.status','Menunggu')
            ->order_by('t.created_at','ASC')
            ->get()
            ->result_array();
    }

    /* =========================================================
       TIKET DITUGASKAN KE SAYA (VIRTUAL: Menunggu + teknisi_id=self)
    ========================================================= */
    public function get_ticket_assigned($user_id)
    {
        return $this->db
            ->select('
                t.id,
                t.kode_tiket,
                t.lokasi,
                t.status,
                t.created_at,
                t.jenis_kerusakan,
                t.foto,
                t.tanggal,
                t.jam,
                u.name AS pelapor,
                u.email AS email,
                up.phone AS no_hp,
                mk.nama_kategori,
                sub.nama_sub_kategori,
                t.teknisi_id,
                teknisi_user.name AS nama_teknisi
            ')
            ->from('tickets t')
            ->join('users u','u.id = t.user_id','left')
            ->join('user_profiles up', 'up.user_id = u.id', 'left')
            ->join('master_kategori mk', 'mk.id = t.kategori_id', 'left')
            ->join('master_sub_kategori sub', 'sub.id = t.sub_kategori_id', 'left')
            ->join('teknisi tek', 'tek.id = t.teknisi_id', 'left')
            ->join('users teknisi_user','teknisi_user.id = tek.user_id','left')
            ->where('tek.user_id', $user_id)
            ->where('t.status','Menunggu')
            ->order_by('t.created_at','ASC')
            ->get()
            ->result_array();
    }

/* =========================================================
   INFO STOK PERSEDIAAN SEBELUM AMBIL TIKET (NON-BLOCKING)
   return [] bila bukan persediaan; else deficient [{barang_id,nama,qty,sisa}]
========================================================= */
public function check_stok_ticket($ticket_id)
{
    $this->load->model('Transaksi_persediaan_model');
    $items = $this->Transaksi_persediaan_model->get_items((int) $ticket_id);
    if (empty($items)) return [];

    $this->load->model('User_ticket_model');
    $bulan = (int) date('n');
    $tahun = (int) date('Y');

    $deficient = [];
    foreach ($items as $it) {
        $qty  = (int) ($it['jumlah'] ?? 0);
        $sisa = $this->User_ticket_model->get_sisa_persediaan((int) $it['barang_id'], $bulan, $tahun);
        if ($sisa < $qty) {
            $nama = $it['nama_barang_snapshot'] ?: ($it['nama_barang'] ?: ('ID ' . $it['barang_id']));
            $deficient[] = [
                'barang_id' => (int) $it['barang_id'],
                'nama'      => $nama,
                'qty'       => $qty,
                'sisa'      => (int) $sisa,
            ];
        }
    }
    return $deficient;
}

public function stok_message($deficient)
{
    $parts = [];
    foreach ($deficient as $d) {
        $parts[] = $d['nama'] . ' x' . (int) $d['qty'] . ' (sisa ' . (int) $d['sisa'] . ')';
    }
    return 'Stok tidak mencukupi untuk tiket ini. Input barang masuk dulu: ' . implode(', ', $parts);
}

/* =========================================================
   TEKNISI AMBIL TIKET + KIRIM EMAIL
   Guard: teknisi_id IS NULL OR teknisi_id=self (virtual assign reuse)
========================================================= */
public function take_ticket($ticket_id, $user_id)
{
    $teknisi = $this->get_by_user($user_id);
    if (!$teknisi) return false;

    // Cek apakah tiket masih available (belum diambil ATAU virtual-assign ke diri sendiri)
    $ticket = $this->db
        ->where('id', $ticket_id)
        ->group_start()
            ->where('teknisi_id IS NULL', null, false)
            ->or_where('teknisi_id', $teknisi['id'])
        ->group_end()
        ->get('tickets')
        ->row_array();

    if (!$ticket) return false;

    // INFO stok: non-blocking, lanjut flip On Progress. deficient hanya warning.
    $deficient = $this->check_stok_ticket($ticket_id);

    // ✅ GUNAKAN Ticket_model->update() BIAR KIRIM EMAIL
    $this->load->model('Ticket_model');
    
    $this->Ticket_model->update($ticket_id, [
        'teknisi_id' => $teknisi['id'],
        'status'     => 'On Progress'  // Ini trigger email di Ticket_model
    ]);

    // Otomatis ubah teknisi jadi busy
    $this->db
        ->where('id', $teknisi['id'])
        ->update('teknisi', ['status' => 'busy']);

    // Tetap array ok + warning bila stok kurang.
    if (!empty($deficient)) {
        return [
            'ok'        => true,
            'warning'   => $this->stok_message($deficient),
            'deficient' => $deficient,
        ];
    }
    return true;
}

    /* =========================================================
       EMAIL TEKNISI YANG TERSEDIA
    ========================================================= */
    public function get_available_teknisi_emails()
    {
        return $this->db
            ->select('u.email')
            ->from('teknisi t')
            ->join('users u', 'u.id = t.user_id')
            ->where('t.status', 'available')
            ->where('u.email IS NOT NULL', null, false)
            ->get()
            ->result_array();
    }
/* =========================================================
   UPDATE CATATAN & LAMPIRAN TEKNISI
========================================================= */
public function update_layanan_catatan($ticket_id, $data)
{
    return $this->db
        ->where('id', $ticket_id)
        ->update('tickets', $data);
}
}