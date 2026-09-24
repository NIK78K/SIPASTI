<?php
// application/models/Pegawai_model.php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pegawai_model extends CI_Model
{
    protected $table = 'master_pegawai';

    // ── CRUD ──────────────────────────────────────────────

    public function get_all()
    {
        return $this->db
            ->order_by('nama', 'ASC')
            ->get($this->table)
            ->result_array();
    }

    public function get_aktif()
    {
        return $this->db
            ->where('status', 'PNS')
            ->order_by('nama', 'ASC')
            ->get($this->table)
            ->result_array();
    }

    public function get_by_id($id)
    {
        return $this->db
            ->where('id', $id)
            ->get($this->table)
            ->row_array();
    }

    /**
     * Cek apakah NIP Baru sudah dipakai pegawai lain.
     * $exclude_id dipakai saat edit agar pegawai itu sendiri tidak dianggap duplikat.
     */
    public function nip_exists($nip_baru, $exclude_id = null)
    {
        if ($exclude_id) {
            $this->db->where('id !=', (int) $exclude_id);
        }
        return $this->db
            ->where('nip_baru', $nip_baru)
            ->count_all_results($this->table) > 0;
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where('id', (int) $id)->update($this->table, $data);
        return $this->db->affected_rows();
    }

    public function delete($id)
    {
        $this->db->where('id', (int) $id)->delete($this->table);
        return $this->db->affected_rows();
    }

    // ── STATS ─────────────────────────────────────────────

    public function count_total()
    {
        return $this->db->count_all($this->table);
    }

    /** Hitung PNS aktif (bukan TB / PPPK / CPNS) */
    public function count_pns()
    {
        return $this->db
            ->where('status', 'PNS')
            ->count_all_results($this->table);
    }

    /** Hitung PPPK */
    public function count_pppk()
    {
        return $this->db
            ->where('status', 'PPPK')
            ->count_all_results($this->table);
    }

    /** Hitung CPNS */
    public function count_cpns()
    {
        return $this->db
            ->where('status', 'CPNS')
            ->count_all_results($this->table);
    }

    // ── EXPORT ────────────────────────────────────────────

    public function export_data()
    {
        return $this->db
            ->select('nip_lama, nip_baru, nama, kode_satker, jabatan, status')
            ->order_by('nama', 'ASC')
            ->get($this->table)
            ->result_array();
    }

    // ── IMPORT (bulk) ─────────────────────────────────────

    /**
     * Insert massal; skip baris yang NIP Baru-nya sudah ada.
     * Kembalikan jumlah baris yang benar-benar dimasukkan.
     */
    public function bulk_insert(array $rows)
    {
        if (empty($rows)) return 0;

        // Ambil semua NIP yang sudah ada sekaligus
        $existing = $this->db
            ->select('nip_baru')
            ->get($this->table)
            ->result_array();
        $existing_nips = array_column($existing, 'nip_baru');

        $filtered = array_filter($rows, function ($r) use ($existing_nips) {
            return !in_array($r['nip_baru'], $existing_nips, true);
        });

        if (empty($filtered)) return 0;

        $this->db->insert_batch($this->table, array_values($filtered));
        return $this->db->affected_rows();
    }
}