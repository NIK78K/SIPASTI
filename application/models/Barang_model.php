<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang_model extends CI_Model {

    private $table = 'master_barang';
    private $table_merk = 'master_merk';

    // ========================================
    // MASTER BARANG
    // ========================================

    public function get_all_barang() {
        $this->db->select('mb.*, GROUP_CONCAT(mm.nama_merk SEPARATOR ", ") as merk');
        $this->db->from('master_barang mb');
        $this->db->join('master_merk mm', 'mb.id = mm.master_barang_id', 'left');
        $this->db->group_by('mb.id');
        $this->db->order_by('mb.kategori', 'ASC');
        $this->db->order_by('mb.nama_barang', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_barang_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table);
        return $query->row();
    }

    public function get_barang_by_kategori($kategori) {
        $this->db->where('kategori', $kategori);
        $this->db->order_by('nama_barang', 'ASC');
        $query = $this->db->get($this->table);
        return $query->result();
    }

    public function get_active_barang() {
        $this->db->where('status', 'Aktif');
        $this->db->order_by('kategori', 'ASC');
        $this->db->order_by('nama_barang', 'ASC');
        $query = $this->db->get($this->table);
        return $query->result();
    }

    public function insert_barang($data) {
        return $this->db->insert($this->table, $data);
    }

    public function get_last_insert_id() {
        return $this->db->insert_id();
    }

    public function update_barang($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete_barang($id) {
        $this->db->where('master_barang_id', $id);
        $this->db->delete($this->table_merk);
        
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    public function check_duplicate($kategori, $nama_barang) {
        $this->db->where('kategori', $kategori);
        $this->db->where('nama_barang', $nama_barang);
        $query = $this->db->get($this->table);
        return $query->num_rows() > 0;
    }

    public function check_duplicate_except($kategori, $nama_barang, $id) {
        $this->db->where('kategori', $kategori);
        $this->db->where('nama_barang', $nama_barang);
        $this->db->where('id !=', $id);
        $query = $this->db->get($this->table);
        return $query->num_rows() > 0;
    }

    // ========================================
    // MASTER MERK
    // ========================================

    public function get_merk_by_barang_id($master_barang_id) {
        $this->db->where('master_barang_id', $master_barang_id);
        $this->db->order_by('nama_merk', 'ASC');
        $query = $this->db->get($this->table_merk);
        return $query->result();
    }

    // ✅ GET MERK BY ID
    public function get_merk_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table_merk);
        return $query->row();
    }

    public function insert_merk($data) {
        return $this->db->insert($this->table_merk, $data);
    }

    public function update_merk($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table_merk, $data);
    }

    public function delete_merk($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table_merk);
    }

    public function delete_merk_by_barang_id($master_barang_id) {
        $this->db->where('master_barang_id', $master_barang_id);
        return $this->db->delete($this->table_merk);
    }

    public function check_merk_duplicate($master_barang_id, $nama_merk) {
        $this->db->where('master_barang_id', $master_barang_id);
        $this->db->where('nama_merk', $nama_merk);
        $query = $this->db->get($this->table_merk);
        return $query->num_rows() > 0;
    }

    // ========================================
    // KATEGORI
    // ========================================

    public function get_kategori_list() {
        $this->db->select('kategori');
        $this->db->distinct();
        $this->db->from($this->table);
        $this->db->where('kategori IS NOT NULL');
        $this->db->where('kategori !=', '');
        $this->db->order_by('kategori', 'ASC');
        $query = $this->db->get();
        
        $result = [];
        foreach ($query->result() as $row) {
            $result[] = $row->kategori;
        }
        return $result;
    }

    // ========================================
    // FUNGSI TAMBAHAN
    // ========================================

    public function count_all_barang() {
        return $this->db->count_all($this->table);
    }

    public function count_by_kategori($kategori) {
        $this->db->where('kategori', $kategori);
        return $this->db->count_all_results($this->table);
    }

    public function count_active_barang() {
        $this->db->where('status', 'Aktif');
        return $this->db->count_all_results($this->table);
    }

    public function search_barang($keyword) {
        $this->db->group_start();
        $this->db->like('kategori', $keyword);
        $this->db->or_like('nama_barang', $keyword);
        $this->db->group_end();
        $this->db->order_by('kategori', 'ASC');
        $query = $this->db->get($this->table);
        return $query->result();
    }

    public function get_kategori_statistics() {
        $this->db->select('kategori, COUNT(*) as jumlah');
        $this->db->from($this->table);
        $this->db->group_by('kategori');
        $this->db->order_by('jumlah', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // ========================================
// SINKRONISASI KE TABEL PERANGKAT
// ========================================

public function sync_perangkat_after_barang_update($old_nama, $new_nama) {
    $this->db->where('nama_barang', $old_nama);
    return $this->db->update('perangkat', ['nama_barang' => $new_nama]);
}

public function sync_perangkat_after_merk_update($old_merk, $new_merk) {
    $this->db->where('merk', $old_merk);
    return $this->db->update('perangkat', ['merk' => $new_merk]);
}

public function count_perangkat_affected($field, $value) {
    $this->db->where($field, $value);
    return $this->db->count_all_results('perangkat');
}
}