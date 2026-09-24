<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kendaraan_model extends CI_Model
{
    private $table = 'master_kendaraan';

 // GANTI DENGAN INI:
public function get_all()
{
    $today = date('Y-m-d');

    $kendaraan = $this->db
        ->order_by('id', 'DESC')
        ->get($this->table)
        ->result_array();

    if (empty($kendaraan)) return [];

    $dipinjam = $this->db
        ->select('kendaraan_id')
        ->from('peminjaman_kendaraan')
        ->where('tanggal_pinjam <=', $today)
        ->where('tanggal_kembali >=', $today)
        ->where_in('status', ['Menunggu', 'Disetujui'])
        ->get()
        ->result_array();

    $ids_dipinjam = array_flip(array_column($dipinjam, 'kendaraan_id'));

    foreach ($kendaraan as &$k) {
        if (isset($ids_dipinjam[$k['id']])) {
            $k['status'] = 'Dipinjam';
        }
    }

    return $kendaraan;
}

    public function get_by_id($id)
    {
        return $this->db->where('id', $id)->get($this->table)->row_array();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        // Cek apakah masih ada peminjaman aktif
        $aktif = $this->db
            ->where('kendaraan_id', $id)
            ->where_in('status', ['Menunggu', 'Disetujui', 'Sedang Digunakan'])
            ->count_all_results('peminjaman_kendaraan');

        if ($aktif > 0) return false;

        return $this->db->where('id', $id)->delete($this->table);
    }
}