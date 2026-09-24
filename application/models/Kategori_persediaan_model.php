<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori_persediaan_model extends CI_Model {

    private $table = 'master_kategori_persediaan';

    public function get_all() {
        return $this->db->order_by('id', 'ASC')->get($this->table)->result();
    }

    public function get_by_id($id) {
        return $this->db->where('id', $id)->get($this->table)->row();
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

    public function is_kode_used($kode, $except_id = NULL) {
        $this->db->where('kode', $kode);
        if ($except_id) $this->db->where('id !=', $except_id);
        return $this->db->count_all_results($this->table) > 0;
    }

    public function count_barang_usage($id) {
        return $this->db->where('kategori_id', $id)->count_all_results('master_barang_persediaan');
    }
}
