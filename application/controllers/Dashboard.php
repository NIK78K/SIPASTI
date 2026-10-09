<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (
            !$this->session->userdata('logged_in') ||
            !in_array($this->session->userdata('role'), ['admin','teknisi'])
        ) {
            redirect('auth/login');
        }
        $this->load->helper('route');
        $this->load->model('Dashboard_model');
    }

  public function index()
{
    $it   = $this->Dashboard_model->kondisi_it();
    $umum = $this->Dashboard_model->kondisi_umum();

    // 🔥 TAMBAHAN
    $it_per_lokasi = $this->Dashboard_model->distribusi_it_per_lokasi();

    $data = [
        // ... semua yang sudah ada ...
        'total_admin'     => $this->Dashboard_model->total_admin(),
        'total_teknisi'   => $this->Dashboard_model->total_teknisi(),
        'total_user'      => $this->Dashboard_model->total_user(),
        'total_perangkat' => $this->Dashboard_model->total_perangkat(),
        'it_baik'         => $it['Baik'],
        'it_rusak_ringan' => $it['Rusak Ringan'],
        'it_rusak_berat'  => $it['Rusak Berat'],
        'umum_baik'         => $umum['Baik'],
        'umum_rusak_ringan' => $umum['Rusak Ringan'],
        'umum_rusak_berat'  => $umum['Rusak Berat'],
        'ticket_menunggu' => $this->Dashboard_model->ticket_menunggu(),
        'ticket_proses'   => $this->Dashboard_model->ticket_proses(),
        'ticket_selesai'  => $this->Dashboard_model->ticket_selesai(),
        'ticket_batal'    => $this->Dashboard_model->ticket_batal(),

        // 🔥 TAMBAHAN
        'it_lokasi_labels' => $it_per_lokasi['labels'],
        'it_lokasi_values' => $it_per_lokasi['values'],

        'last_update' => date('d/m/Y H:i')
    ];

    $this->load->view('layout/header');
    $this->load->view('layout/sidebar');
    $this->load->view('dashboard/index', $data);
    $this->load->view('layout/footer');
}
    /**
     * AJAX Endpoint untuk Live Data
     * Method ini dipanggil setiap 30 detik oleh JavaScript
     */
 public function get_live_data()
{
    header('Content-Type: application/json');

    $it   = $this->Dashboard_model->kondisi_it();
    $umum = $this->Dashboard_model->kondisi_umum();

    // 🔥 TAMBAHAN
    $it_per_lokasi = $this->Dashboard_model->distribusi_it_per_lokasi();

    $data = [
        'total_admin'     => $this->Dashboard_model->total_admin(),
        'total_teknisi'   => $this->Dashboard_model->total_teknisi(),
        'total_user'      => $this->Dashboard_model->total_user(),
        'total_perangkat' => $this->Dashboard_model->total_perangkat(),
        'it_baik'         => $it['Baik'],
        'it_rusak_ringan' => $it['Rusak Ringan'],
        'it_rusak_berat'  => $it['Rusak Berat'],
        'umum_baik'         => $umum['Baik'],
        'umum_rusak_ringan' => $umum['Rusak Ringan'],
        'umum_rusak_berat'  => $umum['Rusak Berat'],
        'ticket_menunggu' => $this->Dashboard_model->ticket_menunggu(),
        'ticket_proses'   => $this->Dashboard_model->ticket_proses(),
        'ticket_selesai'  => $this->Dashboard_model->ticket_selesai(),
        'ticket_batal'    => $this->Dashboard_model->ticket_batal(),

        // 🔥 TAMBAHAN
        'it_lokasi_labels' => $it_per_lokasi['labels'],
        'it_lokasi_values' => $it_per_lokasi['values'],

        'last_update' => date('d/m/Y H:i:s'),
        'timestamp'   => time(),
        'status'      => 'success'
    ];

    echo json_encode($data);
}
}