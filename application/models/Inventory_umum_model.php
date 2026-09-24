<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory_umum_model extends CI_Model
{
    protected $table = 'inventory_umum';

    /* =============================
       GET DATA + FILTER + PAGING
    ============================== */
    public function get_paginated($limit, $offset, $filters = [])
    {
        if (!empty($filters['keyword'])) {
            $this->db->group_start()
                ->like('nama_barang', $filters['keyword'])
                ->or_like('kode_barang', $filters['keyword'])
            ->group_end();
        }

        if (!empty($filters['kondisi'])) {
            $this->db->where('kondisi', $filters['kondisi']);
        }

       if (!empty($filters['nup'])) {
    $this->db->like('nup', $filters['nup']); 
}


        return $this->db
            ->order_by('id', 'DESC')
            ->limit($limit, $offset)
            ->get($this->table)
            ->result_array();
    }

    public function total_rows($filters = [])
    {
        if (!empty($filters['keyword'])) {
            $this->db->group_start()
                ->like('nama_barang', $filters['keyword'])
                ->or_like('kode_barang', $filters['keyword'])
            ->group_end();
        }

        if (!empty($filters['kondisi'])) {
            $this->db->where('kondisi', $filters['kondisi']);
        }
if (!empty($filters['nup'])) {
    $this->db->like('nup', $filters['nup']); // ✅ FIX
}


        return $this->db->count_all_results($this->table);
    }

    /* =============================
       COUNT PER KONDISI
    ============================== */
    public function count_kondisi($kondisi)
    {
        return $this->db
            ->where('kondisi', $kondisi)
            ->count_all_results($this->table);
    }

    /* =============================
       CRUD
    ============================== */
    public function insert_batch($data)
    {
        return $this->db->insert_batch($this->table, $data);
    }

    public function get_all()
    {
        return $this->db->order_by('id','DESC')->get($this->table)->result_array();
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)->delete($this->table);
    }

public function get_by_id($id)
{
    return $this->db
        ->where('id', $id)
        ->get($this->table)
        ->row_array();
}

/* =============================
   COUNT KONDISI DENGAN FILTER
============================= */
public function count_by_kondisi_filtered($filters = [])
{
    $this->db->select('kondisi, COUNT(*) as total');

    if (!empty($filters['keyword'])) {
        $this->db->group_start()
            ->like('nama_barang', $filters['keyword'])
            ->or_like('kode_barang', $filters['keyword'])
        ->group_end();
    }

    if (!empty($filters['kondisi'])) {
        $this->db->where('kondisi', $filters['kondisi']);
    }

    if (!empty($filters['satker'])) {
        $this->db->like('nama_satker', $filters['satker']);
    }

    $this->db->group_by('kondisi');

    $result = $this->db->get($this->table)->result_array();

    $data = [
        'Baik' => 0,
        'Rusak Ringan' => 0,
        'Rusak Berat' => 0,
    ];

    foreach ($result as $row) {
        $data[$row['kondisi']] = (int) $row['total'];
    }

    return $data;
}


}