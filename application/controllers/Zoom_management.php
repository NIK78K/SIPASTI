<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Zoom_management extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }

        if (!in_array($this->session->userdata('role'), ['admin', 'superadmin', 'teknisi'])) {
            show_error('Akses ditolak', 403);
        }

        $this->load->model('Zoom_model');
    }

    public function index()
    {
        $tanggal = $this->input->get('tanggal') ?: date('Y-m-d');

        $this->Zoom_model->assign_zoom_groups($tanggal);

        $data['tanggal']        = $tanggal;
        $data['zoom_hari_ini']  = $this->Zoom_model->get_zoom_by_date($tanggal);
        $data['stat_hari_ini']  = $this->Zoom_model->get_stat_by_date($tanggal);
        $data['jadwal_konflik'] = $this->Zoom_model->get_konflik_jadwal($tanggal);
        $data['all_zoom_today'] = $this->Zoom_model->get_all_zoom_today($tanggal);
        $data['zoom_grouped']   = $this->Zoom_model->get_zoom_grouped_from_db($tanggal);

        $this->load->view('layout/header');

        if ($this->session->userdata('role') === 'teknisi') {
            $this->load->view('layout/sidebar_teknisi');
        } else {
            $this->load->view('layout/sidebar');
        }

        $this->load->view('zoom_management/index', $data);
        $this->load->view('layout/footer');
    }

    public function get_live_data()
    {
        header('Content-Type: application/json');

        $tanggal   = $this->input->get('tanggal') ?: date('Y-m-d');
        $zoom_list = $this->Zoom_model->get_zoom_by_date($tanggal);
        $stat      = $this->Zoom_model->get_stat_by_date($tanggal);

        echo json_encode([
            'status'    => 'success',
            'stat'      => $stat,
            'zoom_list' => $zoom_list,
            'timestamp' => date('H:i:s'),
        ]);
    }

    public function detail($ticket_id)
    {
        $data['ticket'] = $this->Zoom_model->get_detail($ticket_id);
        if (!$data['ticket']) show_404();

        $this->load->view('zoom_management/detail_modal', $data);
    }

    public function reassign()
    {
        $tanggal = $this->input->post('tanggal') ?: date('Y-m-d');

        $this->Zoom_model->reset_zoom_groups($tanggal);
        $this->Zoom_model->assign_zoom_groups($tanggal);

        redirect('zoom_management?tanggal=' . $tanggal);
    }

    public function pindah_akun()
    {
        header('Content-Type: application/json');

        $ticket_id = (int)$this->input->post('ticket_id');
        $akun      = (int)$this->input->post('akun_zoom');

        if (!$ticket_id || !in_array($akun, [1, 2])) {
            echo json_encode(['status' => 'error', 'message' => 'Parameter tidak valid']);
            return;
        }

        $this->db->where('id', $ticket_id)->update('tickets', [
            'akun_zoom'          => $akun,
            'zoom_group_id'      => null,
            'zoom_breakout_slot' => null,
        ]);

        $tiket = $this->db->select('tanggal')->where('id', $ticket_id)->get('tickets')->row_array();
        if (!empty($tiket['tanggal'])) {
            $this->Zoom_model->assign_zoom_groups($tiket['tanggal']);
        }

        echo json_encode(['status' => 'success', 'message' => 'Akun berhasil dipindah']);
    }
}