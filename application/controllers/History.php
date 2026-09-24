<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class History extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        // 🔐 AMANKAN AKSES
        if (!$this->session->userdata('logged_in')) {
            redirect('index.php/auth/login');
            exit;
        }

        $this->load->model('History_model');
        $this->load->model('Teknisi_model');
        $this->load->library('pagination');
    }

    public function index()
    {
        /* ===============================
           PAGINATION
        =============================== */
        $limit  = 10;
        $offset = max((int) $this->input->get('page'), 0);

        /* ===============================
           FILTER
        =============================== */
        $bulan        = $this->input->get('bulan');
        $teknisi      = $this->input->get('teknisi');
        $status       = $this->input->get('status');
        $kategori     = $this->input->get('kategori');      // kategori_id
        $sub_kategori = $this->input->get('sub_kategori');  // sub_kategori_id

        /* ===============================
           TOTAL ROWS
        =============================== */
        $total_rows = $this->History_model->count_all_filtered(
            $bulan, $teknisi, $status, $kategori, $sub_kategori
        );

        /* ===============================
           PAGINATION CONFIG
        =============================== */
        $config['base_url']             = base_url('history/index');
        $config['total_rows']           = $total_rows;
        $config['per_page']             = $limit;
        $config['page_query_string']    = true;
        $config['query_string_segment'] = 'page';
        $config['reuse_query_string']   = true;

        $config['full_tag_open']  = '<div class="flex justify-center gap-1 mt-6">';
        $config['full_tag_close'] = '</div>';

        $config['cur_tag_open']  =
            '<span class="px-3 py-1 rounded bg-cyan-600 text-white">';
        $config['cur_tag_close'] = '</span>';

        $config['num_tag_open']  =
            '<span class="px-3 py-1 rounded bg-gray-700 text-gray-300">';
        $config['num_tag_close'] = '</span>';

        $config['prev_link'] = '‹';
        $config['next_link'] = '›';

        $this->pagination->initialize($config);

        /* ===============================
           DATA LIST
        =============================== */
        $data['history'] = $this->History_model->get_paginated(
            $limit, $offset,
            $bulan, $teknisi, $status,
            $kategori, $sub_kategori
        );

        /* ===============================
           STATISTIK
        =============================== */
        $stats = $this->History_model->stats();

        $data['total']   = $stats['total']   ?? 0;
        $data['selesai'] = $stats['selesai'] ?? 0;
        $data['batal']   = $stats['batal']   ?? 0;
        $data['rating']  = $stats['rating']  ?? 0;

        /* ===============================
           MASTER KATEGORI (LANGSUNG DB)
        =============================== */
        $data['kategori_master'] = $this->db
            ->order_by('nama_kategori', 'ASC')
            ->get('master_kategori')
            ->result_array();

        $data['sub_kategori_master'] = $this->db
            ->order_by('nama_sub_kategori', 'ASC')
            ->get('master_sub_kategori')
            ->result_array();

        /* ===============================
           DATA TAMBAHAN
        =============================== */
        $data['teknisi']    = $this->Teknisi_model->get_all();
        $data['pagination'] = $this->pagination->create_links();
        $data['page']       = $offset;

        /* ===============================
           VIEW
        =============================== */
        $this->load->view('layout/header');

        if ($this->session->userdata('role') === 'teknisi') {
            $this->load->view('layout/sidebar_teknisi');
        } else {
            $this->load->view('layout/sidebar');
        }

        $this->load->view('history/index', $data);
        $this->load->view('layout/footer');
    }

    public function detail($id)
    {
        $data['ticket'] = $this->History_model->get_by_id($id);

        if (!$data['ticket']) {
            show_404();
        }

        $this->load->view('history/detail_modal', $data);
    }
}
