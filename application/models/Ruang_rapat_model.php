<?php
// application/models/Ruang_rapat_model.php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ruang_rapat_model extends CI_Model
{
    protected $table = 'master_ruang_rapat';

    public function __construct()
    {
        parent::__construct();
    }

    // ── CRUD ──────────────────────────────────────────────

    public function get_all()
    {
        return $this->db
            ->order_by('nama_ruangan', 'ASC')
            ->get($this->table)
            ->result_array();
    }

    public function get_aktif()
    {
        return $this->db
            ->where('status', 'Aktif')
            ->order_by('nama_ruangan', 'ASC')
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

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id)->update($this->table, $data);
        return $this->db->affected_rows();
    }

    public function delete($id)
    {
        // Blok hapus jika ada peminjaman aktif (Menunggu atau Disetujui)
        $used = $this->db
            ->where('ruangan_id', $id)
            ->where_in('status', ['Menunggu', 'Disetujui'])
            ->count_all_results('peminjaman_ruang_rapat');
        if ($used > 0) {
            return false;
        }
        $this->db->where('id', $id)->delete($this->table);
        return $this->db->affected_rows();
    }

    // ── STATS ─────────────────────────────────────────────

    public function count_by_status($status)
    {
        return $this->db
            ->where('status', $status)
            ->count_all_results($this->table);
    }

    // ── KETERSEDIAAN (untuk user form & admin modal) ──────

    /**
     * Ambil semua ruangan aktif beserta info ketersediaan
     * pada tanggal + jam tertentu.
     *
     * @param string      $tanggal    Y-m-d
     * @param string|null $jam_mulai  H:i
     * @param string|null $jam_selesai H:i
     * @return array
     */
   public function get_aktif_dengan_ketersediaan($tanggal, $jam_mulai = null, $jam_selesai = null)
{
    $ruangan = $this->get_aktif();

    foreach ($ruangan as &$r) {
        $r['tersedia']        = true;
        $r['jadwal_terpakai'] = [];

        if ($tanggal) {
            // Info untuk display — tampilkan Menunggu & Disetujui saja (Selesai = tidak perlu ditampilkan sebagai "terpakai")
            $jadwal_display = $this->db
                ->select('jam_mulai, jam_selesai, keperluan, status')
                ->where('ruangan_id', $r['id'])
                ->where('tanggal', $tanggal)
                ->where_in('status', ['Menunggu', 'Disetujui'])  // ✅ Selesai tidak ditampilkan sebagai aktif
                ->order_by('jam_mulai', 'ASC')
                ->get('peminjaman_ruang_rapat')
                ->result_array();

            $r['jadwal_terpakai'] = $jadwal_display;

            // Cek konflik HANYA lawan Disetujui — Selesai tidak memblokir
            if ($jam_mulai && $jam_selesai) {
                $conflict = $this->db
                    ->where('ruangan_id', $r['id'])
                    ->where('tanggal', $tanggal)
                    ->where('status', 'Disetujui')   // ✅ HANYA Disetujui yang blokir
                    ->group_start()
                        ->where('jam_mulai <', $jam_selesai)
                        ->where('jam_selesai >', $jam_mulai)
                    ->group_end()
                    ->count_all_results('peminjaman_ruang_rapat');

                $r['tersedia'] = ($conflict === 0);
            }
        }
    }
    unset($r);

    return $ruangan;
}
}