<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{
    /* =====================
       TOTAL USER
    ===================== */
    public function total_admin()
    {
        return $this->db->where('role', 'admin')->count_all_results('users');
    }

    public function total_teknisi()
    {
        return $this->db->where('role', 'teknisi')->count_all_results('users');
    }

    public function total_user()
    {
        return $this->db->where('role', 'user')->count_all_results('users');
    }

    public function total_perangkat()
    {
        return $this->db->count_all('perangkat');
    }

    /* =====================
       KONDISI PERANGKAT IT
    ===================== */
    public function kondisi_it()
    {
        return $this->group_kondisi('perangkat');
    }

    /* =====================
       KONDISI PERANGKAT UMUM
    ===================== */
    public function kondisi_umum()
    {
        return $this->group_kondisi('inventory_umum');
    }

    /* =====================
       HELPER GROUP KONDISI
    ===================== */
    private function group_kondisi($table)
    {
        $result = $this->db
            ->select('kondisi, COUNT(*) as total')
            ->from($table)
            ->group_by('kondisi')
            ->get()
            ->result_array();

        $data = [
            'Baik' => 0,
            'Rusak Ringan' => 0,
            'Rusak Berat' => 0,
        ];

        foreach ($result as $row) {
            if (isset($data[$row['kondisi']])) {
                $data[$row['kondisi']] = (int) $row['total'];
            }
        }

        return $data;
    }

    /* =====================
       TICKET
    ===================== */
    public function ticket_menunggu()
    {
        return $this->db
            ->where('status', 'Menunggu')
            ->count_all_results('tickets');
    }

    public function ticket_proses()
    {
        return $this->db
            ->where('status', 'On Progress')
            ->count_all_results('tickets');
    }

    public function ticket_selesai()
    {
        return $this->db
            ->where('status', 'Selesai')
            ->count_all_results('tickets');
    }

    public function ticket_batal()
    {
        return $this->db
            ->where('status', 'Dibatalkan')
            ->count_all_results('tickets');
    }
    /* =====================
   DISTRIBUSI IT PER LOKASI
===================== */
public function distribusi_it_per_lokasi()
{
    $result = $this->db
        ->select('lokasi, COUNT(*) as total')
        ->from('perangkat')
        ->where('lokasi IS NOT NULL', null, false)
        ->where('lokasi !=', '')
        ->group_by('lokasi')
        ->order_by('total', 'DESC')
        ->limit(10) // ambil top 10 lokasi
        ->get()
        ->result_array();

    $labels = [];
    $values = [];

    foreach ($result as $row) {
        $labels[] = $row['lokasi'];
        $values[] = (int) $row['total'];
    }

    return [
        'labels' => $labels,
        'values' => $values,
    ];
}
}