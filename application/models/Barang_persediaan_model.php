<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang_persediaan_model extends CI_Model {

    private $table = 'master_barang_persediaan';

    public function get_all_with_kategori() {
        $this->db->select('b.*, k.kode AS kategori_kode, k.nama AS kategori_nama, k.deskripsi AS kategori_deskripsi');
        $this->db->from($this->table . ' b');
        $this->db->join('master_kategori_persediaan k', 'k.id = b.kategori_id', 'left');
        $this->db->order_by('k.kode', 'ASC');
        $this->db->order_by('b.nama_barang', 'ASC');
        return $this->db->get()->result();
    }

    public function get_by_id($id) {
        $this->db->select('b.*, k.kode AS kategori_kode, k.nama AS kategori_nama, k.deskripsi AS kategori_deskripsi');
        $this->db->from($this->table . ' b');
        $this->db->join('master_kategori_persediaan k', 'k.id = b.kategori_id', 'left');
        $this->db->where('b.id', $id);
        return $this->db->get()->row();
    }

    public function get_kategori_grouped() {
        $this->db->select('k.id, k.kode, k.nama, k.deskripsi, COUNT(b.id) AS jumlah_barang');
        $this->db->from('master_kategori_persediaan k');
        $this->db->join('master_barang_persediaan b', 'b.kategori_id = k.id', 'left');
        $this->db->group_by('k.id');
        $this->db->order_by('k.kode', 'ASC');
        return $this->db->get()->result();
    }

    public function get_by_kategori($kategori_id) {
        $this->db->select('b.*, k.kode AS kategori_kode, k.nama AS kategori_nama, k.deskripsi AS kategori_deskripsi');
        $this->db->from($this->table . ' b');
        $this->db->join('master_kategori_persediaan k', 'k.id = b.kategori_id', 'left');
        $this->db->where('b.kategori_id', $kategori_id);
        $this->db->order_by('b.nama_barang', 'ASC');
        return $this->db->get()->result();
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

    public function is_kode_used($kode, $except_id = NULL) {
        $this->db->where('kode_barang', $kode);
        if ($except_id) $this->db->where('id !=', $except_id);
        return $this->db->get($this->table)->num_rows() > 0;
    }

    public function next_kode($kategori_id, $except_id = NULL) {
        $this->db->select('k.kode AS kategori_kode');
        $this->db->from('master_kategori_persediaan k');
        $this->db->where('k.id', $kategori_id);
        $kat = $this->db->get()->row();
        if (!$kat || empty($kat->kategori_kode)) return NULL;
        $prefix = trim($kat->kategori_kode) . '-';

        $this->db->select('b.kode_barang');
        $this->db->from($this->table . ' b');
        $this->db->join('master_kategori_persediaan k', 'k.id = b.kategori_id', 'left');
        $this->db->where('b.kategori_id', $kategori_id);
        $this->db->like('b.kode_barang', $prefix, 'after');
        if ($except_id) $this->db->where('b.id !=', $except_id);
        $rows = $this->db->get()->result();

        $max = 0;
        $lengths = [];
        foreach ($rows as $r) {
            $kb = isset($r->kode_barang) ? $r->kode_barang : '';
            if (strpos($kb, $prefix) !== 0) continue;
            $suffix = substr($kb, strlen($prefix));
            if ($suffix === '' || !ctype_digit($suffix)) continue;
            $n = (int) $suffix;
            if ($n > $max) $max = $n;
            $lengths[] = strlen($suffix);
        }

        $next = $max + 1;
        if (!empty($lengths) && count(array_unique($lengths)) === 1) {
            $len = $lengths[0];
            $num = str_pad((string) $next, $len, '0', STR_PAD_LEFT);
        } else {
            $num = (string) $next;
        }
        return $prefix . $num;
    }
}
