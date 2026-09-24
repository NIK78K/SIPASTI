<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class History_model extends CI_Model {

    /* ===============================
       BASE QUERY (BIAR GA NGULANG)
    =============================== */
private function base_query()
{
    $this->db->select('
        t.*,
        p.nama_barang AS nama_perangkat,
        u.name AS nama_teknisi,
        k.nama_kategori,
        sk.nama_sub_kategori
    ');
    $this->db->from('tickets t');
    $this->db->join('perangkat p',         'p.id = t.perangkat_id',  'left');
    $this->db->join('teknisi tek',         'tek.id = t.teknisi_id',  'left'); // ← tambah ini
    $this->db->join('users u',             'u.id = tek.user_id',     'left'); // ← ubah ini
    $this->db->join('master_kategori k',   'k.id = t.kategori_id',   'left');
    $this->db->join('master_sub_kategori sk', 'sk.id = t.sub_kategori_id', 'left');

    $this->db->where_in('t.status', ['Selesai', 'Dibatalkan']);
}

    /* ===============================
       APPLY FILTER
    =============================== */
    private function apply_filter($bulan, $teknisi, $status, $kategori, $sub_kategori)
    {
        if ($bulan) {
            $this->db->where('MONTH(t.selesai_at)', $bulan);
        }

        if ($teknisi) {
            $this->db->where('t.teknisi_id', $teknisi);
        }

        if ($status) {
            $this->db->where('t.status', $status);
        }

        if ($kategori) {
            $this->db->where('t.kategori_id', $kategori);
        }

        if ($sub_kategori) {
            $this->db->where('t.sub_kategori_id', $sub_kategori);
        }
    }

    /* ===============================
       GET PAGINATED
    =============================== */
    public function get_paginated(
        $limit,
        $offset,
        $bulan = null,
        $teknisi = null,
        $status = null,
        $kategori = null,
        $sub_kategori = null
    ) {
        $this->base_query();
        $this->apply_filter($bulan, $teknisi, $status, $kategori, $sub_kategori);

        $this->db->order_by('t.selesai_at', 'DESC');
        $this->db->limit($limit, $offset);

        return $this->db->get()->result_array();
    }

    /* ===============================
       COUNT FILTERED (PAGINATION)
    =============================== */
    public function count_all_filtered(
        $bulan = null,
        $teknisi = null,
        $status = null,
        $kategori = null,
        $sub_kategori = null
    ) {
        $this->db->from('tickets t');
        $this->db->where_in('t.status', ['Selesai', 'Dibatalkan']);

        if ($bulan) {
            $this->db->where('MONTH(t.selesai_at)', $bulan);
        }

        if ($teknisi) {
            $this->db->where('t.teknisi_id', $teknisi);
        }

        if ($status) {
            $this->db->where('t.status', $status);
        }

        if ($kategori) {
            $this->db->where('t.kategori_id', $kategori);
        }

        if ($sub_kategori) {
            $this->db->where('t.sub_kategori_id', $sub_kategori);
        }

        return $this->db->count_all_results();
    }

    /* ===============================
       STATISTIK
    =============================== */
    public function stats()
    {
        return [
            'total' => $this->db
                ->where_in('status', ['Selesai','Dibatalkan'])
                ->count_all_results('tickets'),

            'selesai' => $this->db
                ->where('status','Selesai')
                ->count_all_results('tickets'),

            'batal' => $this->db
                ->where('status','Dibatalkan')
                ->count_all_results('tickets'),

            'rating' => round(
                $this->db
                    ->select_avg('rating')
                    ->where_in('status',['Selesai','Dibatalkan'])
                    ->get('tickets')
                    ->row()->rating ?? 0,
                1
            )
        ];
    }

    /* ===============================
       DETAIL
    =============================== */
    public function get_by_id($id)
    {
        $this->base_query();
        $this->db->where('t.id', $id);

        return $this->db->get()->row_array();
    }
}
