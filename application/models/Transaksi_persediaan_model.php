<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi_persediaan_model extends CI_Model {

    private $table = 'transaksi_persediaan';

    public function get_all_with_barang() {
        $this->db->select('t.*, b.kode_barang, b.nama_barang, b.satuan, k.kode AS kategori_kode, k.nama AS kategori_nama');
        $this->db->from($this->table . ' t');
        $this->db->join('master_barang_persediaan b', 'b.id = t.barang_id', 'left');
        $this->db->join('master_kategori_persediaan k', 'k.id = b.kategori_id', 'left');
        $this->db->order_by('t.tanggal', 'DESC');
        $this->db->order_by('t.id', 'DESC');
        return $this->db->get()->result();
    }

    public function get_filtered($jenis = null, $barang_id = null, $bulan = null, $tahun = null) {
        $this->db->select('t.*, b.kode_barang, b.nama_barang, b.satuan, k.kode AS kategori_kode, k.nama AS kategori_nama');
        $this->db->from($this->table . ' t');
        $this->db->join('master_barang_persediaan b', 'b.id = t.barang_id', 'left');
        $this->db->join('master_kategori_persediaan k', 'k.id = b.kategori_id', 'left');
        if (!empty($jenis)) {
            $this->db->where('t.jenis', $jenis);
        }
        if (!empty($barang_id)) {
            $this->db->where('t.barang_id', $barang_id);
        }
        if (!empty($bulan)) {
            $this->db->where('MONTH(t.tanggal)', (int) $bulan);
        }
        if (!empty($tahun)) {
            $this->db->where('YEAR(t.tanggal)', (int) $tahun);
        }
        $this->db->order_by('t.tanggal', 'DESC');
        $this->db->order_by('t.id', 'DESC');
        return $this->db->get()->result();
    }

    public function get_tahun_list() {
        $this->db->select('DISTINCT YEAR(tanggal) AS tahun', false);
        $this->db->from($this->table);
        $this->db->order_by('tahun', 'DESC');
        return array_map(function ($r) { return $r->tahun; }, $this->db->get()->result());
    }

    public function stats() {
        return [
            'total' => $this->db->count_all_results($this->table),

            'masuk' => $this->db
                ->where('jenis', 'masuk')
                ->count_all_results($this->table),

            'keluar' => $this->db
                ->where('jenis', 'keluar')
                ->count_all_results($this->table),

            'total_unit' => $this->db
                ->select('COALESCE(SUM(jumlah), 0) AS total_unit', false)
                ->get($this->table)
                ->row()->total_unit ?? 0,
        ];
    }

    public function get_by_id($id) {
        $this->db->select('t.*, b.kode_barang, b.nama_barang, b.satuan, k.kode AS kategori_kode, k.nama AS kategori_nama');
        $this->db->from($this->table . ' t');
        $this->db->join('master_barang_persediaan b', 'b.id = t.barang_id', 'left');
        $this->db->join('master_kategori_persediaan k', 'k.id = b.kategori_id', 'left');
        $this->db->where('t.id', $id);
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
}
