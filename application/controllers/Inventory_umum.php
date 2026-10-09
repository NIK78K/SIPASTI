<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class Inventory_umum extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Inventory_umum_model');
    }

    /* =======================
       INDEX
    ======================== */
    public function index()
    {
        $limit = 20;
        $page  = (int) ($this->input->get('page') ?? 1);
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $limit;

$filters = [
    'keyword' => $this->input->get('keyword'),
    'kondisi' => $this->input->get('kondisi'),
    'satker'  => $this->input->get('satker'),
];

$kondisiCount = $this->Inventory_umum_model
    ->count_by_kondisi_filtered($filters);

$data['baik'] = $kondisiCount['Baik'];
$data['rusak_ringan'] = $kondisiCount['Rusak Ringan'];
$data['rusak_berat'] = $kondisiCount['Rusak Berat'];


        $total = $this->Inventory_umum_model->total_rows($filters);
        $data['perangkat'] = $this->Inventory_umum_model->get_paginated($limit, $offset, $filters);

        $data['from'] = $offset + 1;
        $data['to']   = min($offset + $limit, $total);
        $data['total'] = $total;

        $data['current_page'] = $page;
        $data['total_pages']  = ceil($total / $limit);

        $window = 3;
        $start = max(1, $page - 1);
        $end   = min($data['total_pages'], $start + $window - 1);
        $data['pages'] = range($start, $end);

        $this->load->view('layout/header');
        $this->load->view('layout/sidebar');
        $this->load->view('inventory_umum/index', $data);
        $this->load->view('layout/footer');
    }

    /* =======================
       DELETE
    ======================== */
    public function delete($id)
    {
        $this->Inventory_umum_model->delete($id);
        redirect('inventory_umum');
    }

    /* =======================
   DETAIL (AJAX MODAL)
======================= */
public function detail_ajax($id)
{
    $data['detail'] = $this->Inventory_umum_model->get_by_id($id);

    if (!$data['detail']) {
        echo '<div class="text-red-400 text-center">Data tidak ditemukan</div>';
        return;
    }

    $this->load->view('inventory_umum/detail_modal', $data);
}


}
