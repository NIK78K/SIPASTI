<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Rekap_model');

        if (!in_array($this->session->userdata('role'), ['admin','superadmin','pimpinan'])) {
            redirect('index.php/dashboard');
            exit;
        }
    }

    public function index()
    {
        $this->load->view('layout/header');
        $this->load->view('layout/sidebar');
        $this->load->view('laporan/rekap');
        $this->load->view('layout/footer');
    }

    public function ajax_rekap()
    {
        $bulan        = $this->input->post('bulan') ?? 'all';
        $tahun_start  = (int)($this->input->post('tahun_start') ?? date('Y'));
        $tahun_end    = (int)($this->input->post('tahun_end') ?? $tahun_start);

        $tahun_start = max(2000, min(2100, $tahun_start));
        $tahun_end   = max(2000, min(2100, $tahun_end));

        if ($tahun_start > $tahun_end) {
            $tahun_end = $tahun_start;
        }

        $data = $this->Rekap_model->get_rekap($bulan, $tahun_start, $tahun_end);

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    /**
     * EXPORT EXCEL - VERSI BARU (SIMPLE TABLE FORMAT)
     */
    public function export_excel()
    {
        $bulan        = $this->input->get('bulan') ?? 'all';
        $tahun_start  = (int)($this->input->get('tahun_start') ?? date('Y'));
        $tahun_end    = (int)($this->input->get('tahun_end') ?? $tahun_start);

        $tahun_start = max(2000, min(2100, $tahun_start));
        $tahun_end   = max(2000, min(2100, $tahun_end));

        if ($tahun_start > $tahun_end) {
            $tahun_end = $tahun_start;
        }

        $is_all_months = ($bulan === 'all');
        
        if ($is_all_months) {
            // MODE: Rekap Per Bulan (TABEL RINGKAS)
            $data_rekap = $this->Rekap_model->get_rekap_per_bulan($tahun_start, $tahun_end);
            $data_pemeliharaan = $this->Rekap_model->get_rekap_pemeliharaan_per_bulan($tahun_start, $tahun_end);
            $periode = ($tahun_start == $tahun_end)
                ? "Tahun $tahun_start"
                : "Tahun $tahun_start - $tahun_end";
        } else {
            // MODE: Bulan Tertentu (DETAIL)
            $data_rekap = $this->Rekap_model->get_rekap($bulan, $tahun_start, $tahun_end);
            $data_pemeliharaan = $this->Rekap_model->get_rekap_pemeliharaan($bulan, $tahun_start, $tahun_end);
            $nama_bulan = date('F', mktime(0,0,0,$bulan,1));
            $periode = "$nama_bulan $tahun_start";
        }

        // Load library
        $this->load->library('Rekap_excel');

        // Generate Excel
        $this->rekap_excel->generate($data_rekap, $data_pemeliharaan, $periode, $is_all_months);

        // Nama File
        if ($bulan === 'all') {
            $filename = ($tahun_start == $tahun_end)
                ? "Rekap_Perbaikan_Tahun_{$tahun_start}.xlsx"
                : "Rekap_Perbaikan_Tahun_{$tahun_start}_{$tahun_end}.xlsx";
        } else {
            $nama_bulan = date('F', mktime(0,0,0,$bulan,1));
            $filename = "Rekap_Perbaikan_{$nama_bulan}_{$tahun_start}.xlsx";
        }

        // Output
        $this->rekap_excel->output($filename);
    }

    /**
     * EXPORT PDF - VERSI BARU (SIMPLE TABLE FORMAT)
     */
    public function export_pdf()
    {
        $bulan        = $this->input->get('bulan') ?? 'all';
        $tahun_start  = (int)($this->input->get('tahun_start') ?? date('Y'));
        $tahun_end    = (int)($this->input->get('tahun_end') ?? $tahun_start);

        $tahun_start = max(2000, min(2100, $tahun_start));
        $tahun_end   = max(2000, min(2100, $tahun_end));

        if ($tahun_start > $tahun_end) {
            $tahun_end = $tahun_start;
        }

        $is_all_months = ($bulan === 'all');
        
        $data = [];
        
        if ($is_all_months) {
            // MODE: Rekap Per Bulan (TABEL RINGKAS)
            $data['data_monthly'] = $this->Rekap_model->get_rekap_per_bulan($tahun_start, $tahun_end);
            $data['data_pemeliharaan_monthly'] = $this->Rekap_model->get_rekap_pemeliharaan_per_bulan($tahun_start, $tahun_end);
            $data['periode'] = ($tahun_start == $tahun_end)
                ? "Tahun $tahun_start"
                : "Tahun $tahun_start - $tahun_end";
        } else {
            // MODE: Bulan Tertentu (DETAIL)
            $rekap = $this->Rekap_model->get_rekap($bulan, $tahun_start, $tahun_end);
            $data['kategori'] = $rekap['kategori'];
            $data['total'] = $rekap['total'];
            $data['data_pemeliharaan'] = $this->Rekap_model->get_rekap_pemeliharaan($bulan, $tahun_start, $tahun_end);
            $nama_bulan = date('F', mktime(0,0,0,$bulan,1));
            $data['periode'] = "$nama_bulan $tahun_start";
        }

        // Generate PDF
        $html = $this->load->view('laporan/rekap_pdf', $data, true);

        $this->load->library('wkhtmltopdf');
        
        $filename = ($is_all_months)
            ? (($tahun_start == $tahun_end) 
                ? "Rekap_Perbaikan_Tahun_{$tahun_start}.pdf"
                : "Rekap_Perbaikan_Tahun_{$tahun_start}_{$tahun_end}.pdf")
            : "Rekap_Perbaikan_" . date('F_Y', mktime(0,0,0,$bulan,1,$tahun_start)) . ".pdf";
        
        $this->wkhtmltopdf->generate(
            $html,
            $filename,
            [
                'orientation' => 'Landscape',  // LANDSCAPE biar muat tabel lebar
                'page-size'   => 'A4'
            ]
        );
    }
}