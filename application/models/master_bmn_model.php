<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_bmn_model extends CI_Model
{
    protected $table = 'master_bmn';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all()
    {
        return $this->db
            ->select('m.*, p.serial_number as serial_number_it, p.nama_satker')
            ->from($this->table . ' m')
            ->join('perangkat p', 'p.id = m.perangkat_id', 'left')
            ->order_by('m.nama_barang', 'ASC')
            ->get()->result_array();
    }

   // GANTI DENGAN INI:
public function get_aktif()
{
    $today = date('Y-m-d');

    $semua = $this->db
        ->order_by('nama_barang', 'ASC')
        ->get($this->table)
        ->result_array();

    if (empty($semua)) return [];

    $dipinjam = $this->db
        ->select('bmn_id')
        ->from('peminjaman_bmn')
        ->where('tanggal_pinjam <=', $today)
        ->where('tanggal_kembali >=', $today)
        ->where_in('status', ['Menunggu', 'Disetujui'])
        ->get()
        ->result_array();

    $ids_dipinjam = array_flip(array_column($dipinjam, 'bmn_id'));

    foreach ($semua as &$b) {
        if (isset($ids_dipinjam[$b['id']])) {
            $b['status'] = 'Dipinjam';
        }
    }

    return $semua;
}

    public function get_by_id($id)
    {
        return $this->db
            ->where('id', $id)
            ->get($this->table)
            ->row_array();
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id)->update($this->table, $data);
        return $this->db->affected_rows();
    }

    public function delete($id)
    {
        $aktif = $this->db
            ->where('bmn_id', $id)
            ->where_in('status', ['Menunggu', 'Disetujui'])
            ->count_all_results('peminjaman_bmn');

        if ($aktif > 0) return false;

        $this->db->where('id', $id)->delete($this->table);
        return $this->db->affected_rows();
    }

    public function update_status($id, $status)
    {
        $this->db->where('id', $id)->update($this->table, ['status' => $status]);
        return $this->db->affected_rows();
    }

    public function is_conflict($bmn_id, $tanggal_pinjam, $tanggal_kembali, $exclude_id = null)
    {
        $this->db->from('peminjaman_bmn')
            ->where('bmn_id', $bmn_id)
            ->where('tanggal_pinjam <=', $tanggal_kembali)
            ->where('tanggal_kembali >=', $tanggal_pinjam)
            ->where_in('status', ['Menunggu', 'Disetujui']);

        if ($exclude_id) $this->db->where('id !=', $exclude_id);

        return $this->db->count_all_results() > 0;
    }
}