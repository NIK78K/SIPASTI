<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stok_awal_persediaan_model extends CI_Model {

    private $table = 'stok_awal_persediaan';

    public function get_all_with_barang() {
        $this->db->select('s.*, b.kode_barang, b.nama_barang, b.satuan, k.kode AS kategori_kode, k.nama AS kategori_nama');
        $this->db->from($this->table . ' s');
        $this->db->join('master_barang_persediaan b', 'b.id = s.barang_id', 'left');
        $this->db->join('master_kategori_persediaan k', 'k.id = b.kategori_id', 'left');
        $this->db->order_by('k.kode', 'ASC');
        $this->db->order_by('b.nama_barang', 'ASC');
        return $this->db->get()->result();
    }

    public function get_by_id($id) {
        $this->db->select('s.*, b.kode_barang, b.nama_barang, b.satuan, k.kode AS kategori_kode, k.nama AS kategori_nama');
        $this->db->from($this->table . ' s');
        $this->db->join('master_barang_persediaan b', 'b.id = s.barang_id', 'left');
        $this->db->join('master_kategori_persediaan k', 'k.id = b.kategori_id', 'left');
        $this->db->where('s.id', $id);
        return $this->db->get()->row();
    }

    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data) {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete($id) {
        return $this->db->where('id', $id)->delete($this->table);
    }

    public function check_duplicate($barang_id, $bulan, $tahun) {
        $this->db->where('barang_id', $barang_id);
        $this->db->where('bulan', $bulan);
        $this->db->where('tahun', $tahun);
        return $this->db->get($this->table)->num_rows() > 0;
    }

    public function check_duplicate_except($barang_id, $bulan, $tahun, $id) {
        $this->db->where('barang_id', $barang_id);
        $this->db->where('bulan', $bulan);
        $this->db->where('tahun', $tahun);
        $this->db->where('id !=', $id);
        return $this->db->get($this->table)->num_rows() > 0;
    }
}
