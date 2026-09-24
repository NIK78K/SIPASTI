<?php
// application/models/Peminjaman_bmn_model.php

defined('BASEPATH') OR exit('No direct script access allowed');

class Peminjaman_bmn_model extends CI_Model
{
    protected $table = 'peminjaman_bmn';

    public function __construct()
    {
        parent::__construct();
    }

    // ── GET ALL (join master_bmn) ──────────────────────────

    public function get_all()
    {
        return $this->db
            ->select('p.*, b.nama_barang, b.merek, b.kondisi, b.lokasi as lokasi_barang')
            ->from($this->table . ' p')
            ->join('master_bmn b', 'b.id = p.bmn_id', 'left')
            ->order_by('p.tanggal_pinjam', 'DESC')
            ->get()->result_array();
    }

    // ── GET BY ID ─────────────────────────────────────────

    public function get_by_id($id)
    {
        return $this->db
            ->select('p.*, b.nama_barang, b.merek, b.kondisi, b.lokasi as lokasi_barang')
            ->from($this->table . ' p')
            ->join('master_bmn b', 'b.id = p.bmn_id', 'left')
            ->where('p.id', $id)
            ->get()->row_array();
    }

    // ── INSERT ────────────────────────────────────────────

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    // ── UPDATE ────────────────────────────────────────────

    public function update($id, $data)
    {
        $this->db->where('id', $id)->update($this->table, $data);
        return $this->db->affected_rows();
    }

    // ── UPDATE STATUS ─────────────────────────────────────

    public function update_status($id, $status)
    {
        $data = ['status' => $status];

        if (in_array($status, ['Ditolak', 'Selesai'])) {
            $data['selesai']                 = 'Ya';
            $data['tanggal_kembali_aktual']  = date('Y-m-d');
        }

        $this->db->where('id', $id)->update($this->table, $data);
        return $this->db->affected_rows();
    }

    // ── MARK SELESAI ──────────────────────────────────────

    public function mark_selesai($id)
    {
        $this->db->where('id', $id)->update($this->table, [
            'status'                 => 'Selesai',
            'selesai'                => 'Ya',
            'tanggal_kembali_aktual' => date('Y-m-d'),
        ]);
        return $this->db->affected_rows();
    }

    // ── DELETE ────────────────────────────────────────────

    public function delete($id)
    {
        // Blok hapus jika masih aktif
        $row = $this->get_by_id($id);
        if ($row && in_array($row['status'], ['Menunggu', 'Disetujui'])) {
            return false;
        }
        $this->db->where('id', $id)->delete($this->table);
        return $this->db->affected_rows();
    }

    // ── STATISTIK ─────────────────────────────────────────

    public function count_total()
    {
        return $this->db->count_all($this->table);
    }

    public function count_by_status($status)
    {
        return $this->db
            ->where('status', $status)
            ->count_all_results($this->table);
    }

    // ── GET AKTIF HARI INI ────────────────────────────────

    public function get_aktif_hari_ini()
    {
        $today = date('Y-m-d');
        return $this->db
            ->select('p.*, b.nama_barang, b.merek')
            ->from($this->table . ' p')
            ->join('master_bmn b', 'b.id = p.bmn_id', 'left')
            ->where('p.tanggal_pinjam <=', $today)
            ->where('p.tanggal_kembali >=', $today)
            ->where_in('p.status', ['Menunggu', 'Disetujui'])
            ->order_by('p.tanggal_pinjam', 'ASC')
            ->get()->result_array();
    }
}