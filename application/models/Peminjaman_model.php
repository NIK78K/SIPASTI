<?php
// application/models/Peminjaman_model.php

defined('BASEPATH') OR exit('No direct script access allowed');

class Peminjaman_model extends CI_Model
{
    protected $table = 'peminjaman_ruang_rapat';

    public function __construct()
    {
        parent::__construct();
    }

    // ── GET ALL (join dengan master_ruang_rapat) ───────────

    public function get_all()
    {
        return $this->db
            ->select('p.*, r.nama_ruangan, r.kapasitas, r.lokasi, r.fasilitas')
            ->from($this->table . ' p')
            ->join('master_ruang_rapat r', 'r.id = p.ruangan_id', 'left')
            ->order_by('p.tanggal', 'DESC')
            ->order_by('p.jam_mulai', 'ASC')
            ->get()->result_array();
    }

    // ── GET BY ID ─────────────────────────────────────────

    public function get_by_id($id)
    {
        return $this->db
            ->select('p.*, r.nama_ruangan, r.kapasitas, r.lokasi, r.fasilitas')
            ->from($this->table . ' p')
            ->join('master_ruang_rapat r', 'r.id = p.ruangan_id', 'left')
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

    // ── UPDATE STATUS + SELESAI ───────────────────────────

    public function update_status($id, $status)
    {
        $data = ['status' => $status];

        // Otomatis set selesai jika status Ditolak atau Selesai
        if (in_array($status, ['Ditolak', 'Selesai'])) {
            $data['selesai'] = 'Ya';
        }

        $this->db->where('id', $id)->update($this->table, $data);
        return $this->db->affected_rows();
    }

    // ── MARK SELESAI ──────────────────────────────────────

    public function mark_selesai($id)
    {
        $this->db->where('id', $id)->update($this->table, [
            'status'  => 'Selesai',
            'selesai' => 'Ya',
        ]);
        return $this->db->affected_rows();
    }

    // ── DELETE ────────────────────────────────────────────

    public function delete($id)
    {
        $this->db->where('id', $id)->delete($this->table);
        return $this->db->affected_rows();
    }

    // ── CEK KONFLIK JADWAL ────────────────────────────────
    // Menghindari double booking di ruangan & waktu yang sama

    public function is_conflict($ruangan_id, $tanggal, $jam_mulai, $jam_selesai, $exclude_id = null)
    {
        $this->db
            ->where('ruangan_id', $ruangan_id)
            ->where('tanggal', $tanggal)
            ->where_in('status', ['Menunggu', 'Disetujui'])
            ->group_start()
                ->where('jam_mulai <', $jam_selesai)
                ->where('jam_selesai >', $jam_mulai)
            ->group_end();

        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }

        return $this->db->count_all_results($this->table) > 0;
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

    // ── GET BY TANGGAL (untuk jadwal hari ini) ────────────

    public function get_by_tanggal($tanggal)
    {
        return $this->db
            ->select('p.*, r.nama_ruangan, r.kapasitas')
            ->from($this->table . ' p')
            ->join('master_ruang_rapat r', 'r.id = p.ruangan_id', 'left')
            ->where('p.tanggal', $tanggal)
            ->where_in('p.status', ['Menunggu', 'Disetujui'])
            ->order_by('p.jam_mulai', 'ASC')
            ->get()->result_array();
    }
}