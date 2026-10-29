<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory_persediaan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        $this->load->model(['Barang_persediaan_model', 'Kategori_persediaan_model']);
        $this->load->helper('url');
    }

    public function index()
    {
        $keyword = trim((string) $this->input->get('keyword', true));
        $kategori_id = $this->input->get('kategori_id', true);
        $kategori_id = is_numeric($kategori_id) && (int) $kategori_id > 0 ? (int) $kategori_id : null;
        $status_filter = $this->input->get('status', true);
        $status_filter = in_array($status_filter, ['aman', 'menipis', 'kosong'], true) ? $status_filter : '';
        $bulan = (int) date('n');
        $tahun = (int) date('Y');
        $rows = $this->Barang_persediaan_model->get_dashboard_stok($bulan, $tahun, $keyword, $kategori_id);

        $summary = [
            'jenis' => count($rows),
            'total_stok' => 0,
            'menipis' => 0,
            'kosong' => 0,
        ];
        foreach ($rows as $row) {
            $row->stok_akhir = (int) $row->stok_akhir;
            $row->stok_minimum = (int) $row->stok_minimum;
            $row->status_stok = $row->stok_akhir <= 0
                ? 'kosong'
                : ($row->stok_minimum > 0 && $row->stok_akhir <= $row->stok_minimum ? 'menipis' : 'aman');
            $summary['total_stok'] += $row->stok_akhir;
            if ($row->status_stok === 'menipis') $summary['menipis']++;
            if ($row->status_stok === 'kosong') $summary['kosong']++;
        }
        if ($status_filter !== '') {
            $rows = array_values(array_filter($rows, function ($row) use ($status_filter) {
                return $row->status_stok === $status_filter;
            }));
        }

        $data = [
            'rows' => $rows,
            'summary' => $summary,
            'kategori_list' => $this->Kategori_persediaan_model->get_all(),
            'filters' => [
                'keyword' => $keyword,
                'kategori_id' => $kategori_id,
                'status' => $status_filter,
            ],
            'periode' => date('F Y'),
        ];
        $this->load->view('layout/header');
        $this->load->view('layout/sidebar');
        $this->load->view('inventory_persediaan/index', $data);
        $this->load->view('layout/footer');
    }
}
