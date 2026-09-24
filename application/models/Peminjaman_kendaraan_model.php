<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Peminjaman_kendaraan_model extends CI_Model
{
    private $table = 'peminjaman_kendaraan';

    public function get_all()
    {
        return $this->db
            ->select('pk.*, mk.nama_kendaraan, mk.nomor_polisi, mk.jenis')
            ->from($this->table . ' pk')
            ->join('master_kendaraan mk', 'mk.id = pk.kendaraan_id', 'left')
            ->order_by('pk.id', 'DESC')
            ->get()
            ->result_array();
    }

    public function get_by_id($id)
    {
        return $this->db
            ->select('pk.*, mk.nama_kendaraan, mk.nomor_polisi, mk.jenis')
            ->from($this->table . ' pk')
            ->join('master_kendaraan mk', 'mk.id = pk.kendaraan_id', 'left')
            ->where('pk.id', $id)
            ->get()
            ->row_array();
    }

    /**
     * Cek konflik peminjaman kendaraan.
     * - harian : overlap rentang tanggal
     * - per_jam: tanggal sama + overlap jam
     */
    public function is_conflict(
        $kendaraan_id,
        $tgl_pinjam,
        $tgl_kembali,
        $jam_pinjam  = null,
        $jam_kembali = null,
        $exclude_id  = null
    ) {
        // Mode per_jam: hanya cek tanggal yang sama + overlap jam
        if ($jam_pinjam && $jam_kembali && $tgl_pinjam === $tgl_kembali) {
            $this->db
                ->where('kendaraan_id', $kendaraan_id)
                ->where('tipe_pinjam', 'per_jam')
                ->where('tanggal_pinjam', $tgl_pinjam)
                ->where_in('status', ['Menunggu', 'Disetujui'])
                ->group_start()
                    ->where('jam_pinjam <',  $jam_kembali)
                    ->where('jam_kembali >', $jam_pinjam)
                ->group_end();

            if ($exclude_id) $this->db->where('id !=', $exclude_id);
            return $this->db->count_all_results($this->table) > 0;
        }

        // Mode harian: overlap rentang tanggal (termasuk konflik dengan per_jam di tanggal yang sama)
        $this->db
            ->where('kendaraan_id', $kendaraan_id)
            ->where_in('status', ['Menunggu', 'Disetujui'])
            ->where('tanggal_pinjam <=', $tgl_kembali)
            ->where('tanggal_kembali >=', $tgl_pinjam);

        if ($exclude_id) $this->db->where('id !=', $exclude_id);
        return $this->db->count_all_results($this->table) > 0;
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function update_status($id, $status)
    {
        return $this->db->where('id', $id)->update($this->table, ['status' => $status]);
    }

    public function mark_selesai($id)
    {
        return $this->db->where('id', $id)->update($this->table, [
            'status'                 => 'Selesai',
            'tanggal_kembali_aktual' => date('Y-m-d'),
        ]);
    }

    public function delete($id)
    {
        $aktif = $this->db
            ->where('id', $id)
            ->where_in('status', ['Menunggu', 'Disetujui'])
            ->count_all_results($this->table);
        if ($aktif > 0) return false;
        return $this->db->where('id', $id)->delete($this->table);
    }

    /** Untuk widget dashboard — peminjaman aktif pada tanggal tertentu */
    public function get_by_tanggal($tanggal)
    {
        return $this->db
            ->select('pk.*, mk.nama_kendaraan, mk.nomor_polisi, mk.jenis')
            ->from($this->table . ' pk')
            ->join('master_kendaraan mk', 'mk.id = pk.kendaraan_id', 'left')
            ->where('pk.tanggal_pinjam <=', $tanggal)
            ->where('pk.tanggal_kembali >=', $tanggal)
            ->where_in('pk.status', ['Menunggu', 'Disetujui'])
            ->order_by('pk.tanggal_pinjam', 'ASC')
            ->get()
            ->result_array();
    }

    public function count_by_status($status)
    {
        return $this->db
            ->where('status', $status)
            ->count_all_results($this->table);
    }
}