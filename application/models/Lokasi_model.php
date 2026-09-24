<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lokasi_model extends CI_Model {

    private $table = 'master_lokasi';

    public function get_all_lokasi() {
        $this->db->order_by('kode_lokasi', 'ASC');
        return $this->db->get($this->table)->result();
    }

    public function get_lokasi_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get($this->table)->row();
    }

    public function get_by_kode($kode_lokasi) {
        $this->db->where('kode_lokasi', $kode_lokasi);
        return $this->db->get($this->table)->row();
    }

    public function insert_lokasi($data) {
        $result = $this->db->insert($this->table, $data);
        if (!$result) {
            log_message('error', 'Insert gagal: ' . $this->db->error()['message']);
        }
        return $result;
    }

    public function update_lokasi($id, $data) {
        $this->db->where('id', $id);
        $result = $this->db->update($this->table, $data);
        if (!$result) {
            log_message('error', 'Update gagal: ' . $this->db->error()['message']);
        }
        return $result;
    }

    public function delete_lokasi($id) {
        $this->db->where('id', $id);
        $result = $this->db->delete($this->table);
        if (!$result) {
            log_message('error', 'Delete gagal: ' . $this->db->error()['message']);
        }
        return $result;
    }

    public function get_lokasi_aktif() {
        $this->db->where('status', 'Aktif');
        $this->db->order_by('kode_lokasi', 'ASC');
        return $this->db->get($this->table)->result();
    }

    // ========================================
    // SINKRONISASI KE TABEL PERANGKAT
    // ========================================

    /**
     * Sync lokasi ke tabel perangkat (kolom 'lokasi')
     * Format: "kode_lokasi - nama_lokasi"
     */
    public function sync_perangkat_after_lokasi_update($old_lokasi, $new_lokasi) {
        $this->db->where('lokasi', $old_lokasi);
        return $this->db->update('perangkat', ['lokasi' => $new_lokasi]);
    }

    /**
     * Hitung jumlah perangkat yang terpengaruh
     */
    public function count_perangkat_affected_by_lokasi($lokasi_value) {
        $this->db->where('lokasi', $lokasi_value);
        return $this->db->count_all_results('perangkat');
    }
}