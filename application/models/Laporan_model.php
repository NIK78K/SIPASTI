<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_model extends CI_Model {

    private $table_ticket = 'tickets';
    private $table_it     = 'perangkat';
    private $table_umum   = 'inventory_umum';
    private $tanggal      = 'created_at';

    function total_masuk($tahun){
        return $this->db
            ->where("YEAR($this->tanggal)", $tahun)
            ->count_all_results($this->table_ticket);
    }

    function total_selesai($tahun){
        return $this->db
            ->where('status','Selesai')
            ->where("YEAR($this->tanggal)", $tahun)
            ->count_all_results($this->table_ticket);
    }

    function total_batal($tahun){
        return $this->db
            ->where('status','Dibatalkan')
            ->where("YEAR($this->tanggal)", $tahun)
            ->count_all_results($this->table_ticket);
    }
function performa_teknisi($tahun){
    return $this->db
        ->select('
            u.id,
            u.name,
            COUNT(t.id) AS total,
            COUNT(CASE WHEN t.rating IS NOT NULL AND t.rating > 0 THEN 1 END) AS total_rated,
            ROUND(AVG(CASE WHEN t.rating IS NOT NULL AND t.rating > 0 THEN t.rating END), 1) AS rating
        ')
        ->from('tickets t')
        ->join('teknisi tk', 'tk.id = t.teknisi_id', 'inner')
        ->join('users u', 'u.id = tk.user_id', 'inner')
        ->where('t.status', 'Selesai')
        ->where('t.teknisi_id IS NOT NULL', null, false)
        ->where("YEAR(t.created_at)", $tahun)
        ->where_not_in('u.id', [2, 9]) // exclude "Teknisi" dan "Teknisi IPDS"
        ->group_by('u.id')
        ->order_by('total', 'DESC')
        ->get()
        ->result_array();
}

public function performa_teknisi_filter($tahun, $bulan = null, $user_id = null)
{
    $this->db
        ->select('
            u.id,
            u.name,
            COUNT(t.id) AS total,
            ROUND(AVG(CASE WHEN t.rating IS NOT NULL AND t.rating > 0 THEN t.rating END), 1) AS rating
        ')
        ->from('tickets t')
        ->join('teknisi tk', 'tk.id = t.teknisi_id', 'inner')
        ->join('users u', 'u.id = tk.user_id', 'inner')
        ->where('t.status', 'Selesai')
        ->where('t.teknisi_id IS NOT NULL', null, false)
        ->where("YEAR(t.created_at)", $tahun)
        ->where_not_in('u.id', [2, 9]);

    if (!empty($bulan)) {
        $this->db->where("MONTH(t.created_at)", (int)$bulan);
    }

    if (!empty($user_id)) {
        $this->db->where('u.id', (int)$user_id);
    }

    return $this->db
        ->group_by('u.id')
        ->order_by('total', 'DESC')
        ->get()
        ->result_array();
}

    function get_perangkat_it(){
        return $this->db->get($this->table_it)->result_array();
    }

    function get_perangkat_umum(){
        return $this->db->get($this->table_umum)->result_array();
    }

    private function range_date($range, $date){
        if($range=='week'){
            return [
                'start'=>date('Y-m-d',strtotime('monday this week',strtotime($date))),
                'end'  =>date('Y-m-d',strtotime('sunday this week',strtotime($date)))
            ];
        }
        if($range=='month'){
            return [
                'start'=>date('Y-m-01',strtotime($date)),
                'end'  =>date('Y-m-t',strtotime($date))
            ];
        }
        return [
            'start'=>date('Y-01-01',strtotime($date)),
            'end'  =>date('Y-12-31',strtotime($date))
        ];
    }

    function tabel_harian($range, $date){
        $r = $this->range_date($range, $date);

        return $this->db
            ->select("DATE($this->tanggal) tanggal,
                      COUNT(*) total,
                      SUM(status='Selesai') selesai,
                      SUM(status='Dibatalkan') batal")
            ->from($this->table_ticket)
            ->where("$this->tanggal >=", $r['start'])
            ->where("$this->tanggal <=", $r['end'])
            ->group_by("DATE($this->tanggal)")
            ->order_by("tanggal","ASC")
            ->get()->result_array();
    }
function detail_tiket_range($range, $date){
    $r = $this->range_date($range, $date);

    return $this->db
        ->select("
            id,
            kode_tiket,
            kategori,
            pelapor,
            status,
            rating,
            lokasi,
            jenis_layanan,
            created_at,
            selesai_at
        ")
        ->from($this->table_ticket)
        ->where('status','Selesai') // 🔥 INI YANG WAJIB
        ->where("DATE($this->tanggal) >=", $r['start'])
        ->where("DATE($this->tanggal) <=", $r['end'])
        ->order_by($this->tanggal, 'DESC')
        ->get()
        ->result_array();
}

public function detail_tiket_range_manual($start, $end)
{
    return $this->db
        ->select('
            t.kode_tiket,
            mk.nama_kategori AS kategori,
            msk.nama_sub_kategori AS sub_kategori,
            t.pelapor,
            t.lokasi,
            t.created_at,
            t.selesai_at,
            t.rating
        ')
        ->from('tickets t')
        ->join('master_kategori mk', 'mk.id = t.kategori_id', 'left')
        ->join('master_sub_kategori msk', 'msk.id = t.sub_kategori_id', 'left')
        ->where('DATE(t.created_at) >=', $start)
        ->where('DATE(t.created_at) <=', $end)
        ->order_by('t.created_at', 'DESC')
        ->get()
        ->result_array();
}

public function tiket_range_export($start, $end)
{
    return $this->db
        ->select("
            t.kode_tiket,
            mk.nama_kategori AS kategori,
            msk.nama_sub_kategori AS sub_kategori,
            t.pelapor,
            t.lokasi,
            t.created_at,
            t.selesai_at,
            t.rating,
            t.status
        ")
        ->from('tickets t')
        ->join('master_kategori mk', 'mk.id = t.kategori_id', 'left')
        ->join('master_sub_kategori msk', 'msk.id = t.sub_kategori_id', 'left')
        ->where('DATE(t.created_at) >=', $start)
        ->where('DATE(t.created_at) <=', $end)
        ->order_by('t.created_at', 'ASC')
        ->get()
        ->result_array();
}

public function detail_tiket_range_by_date($start, $end)
{
    return $this->db
        ->select("
            t.kode_tiket,
            mk.nama_kategori AS kategori,
            msk.nama_sub_kategori AS sub_kategori,
            t.pelapor,
            t.lokasi,
            t.created_at,
            t.selesai_at,
            t.rating,
            t.status
        ")
        ->from($this->table_ticket . ' t')
        ->join('master_kategori mk', 'mk.id = t.kategori_id', 'left')
        ->join('master_sub_kategori msk', 'msk.id = t.sub_kategori_id', 'left')
        ->where('t.status', 'Selesai')
        ->where('DATE(t.created_at) >=', $start)
        ->where('DATE(t.created_at) <=', $end)
        ->order_by('t.created_at', 'DESC')
        ->get()
        ->result_array();
}

public function get_saran_masukan($start, $end, $rating = null)
{
    $this->db
        ->select('
            t.kode_tiket,
            mk.nama_kategori AS kategori,
            t.pelapor,
            t.lokasi,
            t.rating,
            t.komentar,
            t.selesai_at
        ')
        ->from('tickets t')
        ->join('master_kategori mk', 'mk.id = t.kategori_id', 'left')
        ->where('t.status', 'Selesai')
        ->where('t.komentar IS NOT NULL', null, false)
        ->where("t.komentar !=", '')
        ->where('DATE(t.selesai_at) >=', $start)
        ->where('DATE(t.selesai_at) <=', $end);

    if ($rating) {
        $this->db->where('t.rating', (int)$rating);
    }

    return $this->db
        ->order_by('t.selesai_at', 'DESC')
        ->get()
        ->result_array();
}
// Tambah di Laporan_model.php
public function get_detail_tiket_by_teknisi($teknisi_user_id, $tahun, $bulan = null)
{
    $this->db
        ->select('
            t.kode_tiket,
            mk.nama_kategori AS kategori,
            msk.nama_sub_kategori AS sub_kategori,
            t.pelapor,
            t.lokasi,
            t.created_at,
            t.selesai_at,
            t.rating,
            t.komentar
        ')
        ->from('tickets t')
        ->join('teknisi tek', 'tek.id = t.teknisi_id', 'inner')
        ->join('users u', 'u.id = tek.user_id', 'inner')
        ->join('master_kategori mk', 'mk.id = t.kategori_id', 'left')
        ->join('master_sub_kategori msk', 'msk.id = t.sub_kategori_id', 'left')
        ->where('t.status', 'Selesai')
        ->where('u.id', $teknisi_user_id)
        ->where("YEAR(t.created_at)", $tahun);

    if (!empty($bulan)) {
        $this->db->where("MONTH(t.created_at)", (int)$bulan);
    }

    return $this->db
        ->order_by('t.created_at', 'ASC')
        ->get()
        ->result_array();
}


}
