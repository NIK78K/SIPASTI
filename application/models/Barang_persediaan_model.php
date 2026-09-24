<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang_persediaan_model extends CI_Model {

    private $table = 'master_barang_persediaan';

    public function get_all_with_kategori() {
        $this->db->select('b.*, k.kode AS kategori_kode, k.nama AS kategori_nama');
        $this->db->from($this->table . ' b');
        $this->db->join('master_kategori_persediaan k', 'k.id = b.kategori_id', 'left');
        $this->db->order_by('k.kode', 'ASC');
        $this->db->order_by('b.nama_barang', 'ASC');
        return $this->db->get()->result();
    }

    public function get_by_id($id) {
        $this->db->select('b.*, k.kode AS kategori_kode, k.nama AS kategori_nama');
        $this->db->from($this->table . ' b');
        $this->db->join('master_kategori_persediaan k', 'k.id = b.kategori_id', 'left');
        $this->db->where('b.id', $id);
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

    public function check_duplicate($kategori_id, $nama_barang) {
        $this->db->where('kategori_id', $kategori_id);
        $this->db->where('nama_barang', $nama_barang);
        return $this->db->get($this->table)->num_rows() > 0;
    }

    public function check_duplicate_except($kategori_id, $nama_barang, $id) {
        $this->db->where('kategori_id', $kategori_id);
        $this->db->where('nama_barang', $nama_barang);
        $this->db->where('id !=', $id);
        return $this->db->get($this->table)->num_rows() > 0;
    }
}
