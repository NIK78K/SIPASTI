<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class Rekap_inventory extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        if (!$this->session->userdata('logged_in')) {
            redirect('index.php/auth/login');
            exit;
        }
        
        $this->load->model('Rekap_inventory_model');
    }

    public function index()
    {
        $this->load->view('layout/header');
        
        if ($this->session->userdata('role') === 'teknisi') {
            $this->load->view('layout/sidebar_teknisi');
        } else {
            $this->load->view('layout/sidebar');
        }
        
        $this->load->view('laporan/rekap_inventory');
        $this->load->view('layout/footer');
    }

    public function ajax_rekap()
    {
        header('Content-Type: application/json');
        
        $jenis = $this->input->post('jenis');
        $kondisi = $this->input->post('kondisi');
        $tahun = $this->input->post('tahun');
        
        $filter_kondisi = ($kondisi === 'all') ? null : $kondisi;
        $filter_tahun = ($tahun === 'all') ? null : $tahun;
        
        $summary = $this->Rekap_inventory_model->get_summary($filter_kondisi, $filter_tahun);
        
        $data = [];
        
        switch ($jenis) {
            case 'kondisi_barang':
                $data = $this->Rekap_inventory_model->get_by_kondisi($filter_kondisi, $filter_tahun);
                break;
            case 'jenis_bmn':
                $data = $this->Rekap_inventory_model->get_by_jenis_bmn($filter_kondisi, $filter_tahun);
                break;
            case 'ruangan':
                $data = $this->Rekap_inventory_model->get_by_ruangan($filter_kondisi, $filter_tahun);
                break;
            case 'tahun_perolehan':
                $data = $this->Rekap_inventory_model->get_by_tahun_perolehan($filter_kondisi, $filter_tahun);
                break;
            case 'pemeliharaan':
                $data = $this->Rekap_inventory_model->get_pemeliharaan($filter_tahun);
                break;
            default:
                $data = [];
        }
        
        $periode = 'Real-time';
        if ($filter_kondisi) {
            $periode = 'Kondisi: ' . ucwords(str_replace('_', ' ', $filter_kondisi));
        }
        if ($filter_tahun) {
            $periode .= ($periode !== 'Real-time' ? ' | ' : '') . 'Tahun: ' . $filter_tahun;
        }
        
        echo json_encode([
            'summary' => $summary,
            'data' => $data,
            'periode' => $periode
        ]);
    }

    /**
     * Export Excel - Multiple Sheets
     */
public function export_excel()
{
    $kondisi = $this->input->get('kondisi');
    $tahun = $this->input->get('tahun');
    
    $filter_kondisi = ($kondisi === 'all') ? null : $kondisi;
    $filter_tahun = ($tahun === 'all') ? null : $tahun;
    
    // Get all data
    $summary = $this->Rekap_inventory_model->get_summary($filter_kondisi, $filter_tahun);
    
    $all_data = [
        'jenis_bmn' => $this->Rekap_inventory_model->get_by_jenis_bmn($filter_kondisi, $filter_tahun),
        'kondisi_barang' => $this->Rekap_inventory_model->get_by_kondisi($filter_kondisi, $filter_tahun),
        'ruangan' => $this->Rekap_inventory_model->get_by_ruangan($filter_kondisi, $filter_tahun),
        'tahun_perolehan' => $this->Rekap_inventory_model->get_by_tahun_perolehan($filter_kondisi, $filter_tahun),
        // HAPUS BARIS INI:
        // 'pemeliharaan' => $this->Rekap_inventory_model->get_pemeliharaan($filter_tahun)
    ];
    
    // Load Excel Helper
    require_once APPPATH . 'views/laporan/Rekap_inventory_excel.php';
    
    $excel = new Rekap_inventory_excel();
    $excel->generate($summary, $all_data, $filter_kondisi, $filter_tahun);
    
    $filename = 'Rekap_Inventory_Lengkap_' . date('Ymd_His') . '.xlsx';
    $excel->output($filename);
}

    private function create_sheet($sheet, $title, $data, $type, $kondisi, $tahun, $summary)
    {
        $sheet->setCellValue('A1', 'LAPORAN INVENTORY ASET TI');
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet->setCellValue('A2', 'BPS PROVINSI RIAU');
        $sheet->mergeCells('A2:G2');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet->setCellValue('A3', 'Berdasarkan ' . $title);
        $sheet->mergeCells('A3:G3');
        $sheet->getStyle('A3')->getFont()->setSize(12);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $row = 5;
        
        if ($kondisi) {
            $sheet->setCellValue('A'.$row, 'Filter Kondisi:');
            $sheet->setCellValue('B'.$row, ucwords(str_replace('_', ' ', $kondisi)));
            $row++;
        }
        if ($tahun) {
            $sheet->setCellValue('A'.$row, 'Tahun:');
            $sheet->setCellValue('B'.$row, $tahun);
            $row++;
        }
        $sheet->setCellValue('A'.$row, 'Tanggal Export:');
        $sheet->setCellValue('B'.$row, date('d F Y H:i:s'));
        $row += 2;
        
        $sheet->setCellValue('A'.$row, 'RINGKASAN');
        $sheet->getStyle('A'.$row)->getFont()->setBold(true)->setSize(12);
        $row++;
        
        $sheet->setCellValue('A'.$row, 'Total Aset');
        $sheet->setCellValue('B'.$row, $summary['total']);
        $sheet->setCellValue('D'.$row, 'Kondisi Baik');
        $sheet->setCellValue('E'.$row, $summary['baik']);
        $row++;
        
        $sheet->setCellValue('A'.$row, 'Rusak Ringan');
        $sheet->setCellValue('B'.$row, $summary['rusak_ringan']);
        $sheet->setCellValue('D'.$row, 'Rusak Berat');
        $sheet->setCellValue('E'.$row, $summary['rusak_berat']);
        $row += 2;
        
        switch($type) {
            case 'jenis_bmn':
                $this->export_jenis_bmn($sheet, $data, $row);
                break;
            case 'kondisi_barang':
                $this->export_kondisi($sheet, $data, $row);
                break;
            case 'ruangan':
                $this->export_ruangan($sheet, $data, $row);
                break;
            case 'tahun_perolehan':
                $this->export_tahun_perolehan($sheet, $data, $row);
                break;
            case 'pemeliharaan':
                $this->export_pemeliharaan($sheet, $data, $row);
                break;
        }
        
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    /**
     * Export PDF
     */
    public function export_pdf()
    {
        $kondisi = $this->input->get('kondisi');
        $tahun = $this->input->get('tahun');
        
        $filter_kondisi = ($kondisi === 'all') ? null : $kondisi;
        $filter_tahun = ($tahun === 'all') ? null : $tahun;
        
        $summary = $this->Rekap_inventory_model->get_summary($filter_kondisi, $filter_tahun);
        $data_jenis = $this->Rekap_inventory_model->get_by_jenis_bmn($filter_kondisi, $filter_tahun);
        $data_kondisi = $this->Rekap_inventory_model->get_by_kondisi($filter_kondisi, $filter_tahun);
        $data_ruangan = $this->Rekap_inventory_model->get_by_ruangan($filter_kondisi, $filter_tahun);
        $data_tahun = $this->Rekap_inventory_model->get_by_tahun_perolehan($filter_kondisi, $filter_tahun);
        $data_pemeliharaan = $this->Rekap_inventory_model->get_pemeliharaan($filter_tahun);
        
        $pdf_data = [
            'kondisi' => $filter_kondisi,
            'tahun' => $filter_tahun,
            'summary' => $summary,
            'data_jenis' => $data_jenis,
            'data_kondisi' => $data_kondisi,
            'data_ruangan' => $data_ruangan,
            'data_tahun' => $data_tahun,
            'data_pemeliharaan' => $data_pemeliharaan,
            'tanggal' => date('d F Y H:i:s')
        ];
        
        require_once APPPATH . '../vendor/autoload.php';
        
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Arial');
        
        $dompdf = new \Dompdf\Dompdf($options);
        $html = $this->load->view('laporan/rekap_inventory_pdf', $pdf_data, true);
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        
        $filename = 'Rekap_Inventory_Lengkap_' . date('Ymd_His') . '.pdf';
        
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        $dompdf->stream($filename, ['Attachment' => 1]);
        exit;
    }

    // Export helpers
    private function export_kondisi($sheet, $data, $start_row)
    {
        $headers = ['No', 'Nama Barang', 'Baik', 'Rusak Ringan', 'Rusak Berat', 'Total'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $start_row, $header);
            $sheet->getStyle($col . $start_row)->applyFromArray($this->get_header_style());
            $col++;
        }
        
        $row = $start_row + 1;
        $no = 1;
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $item['nama_barang']);
            $sheet->setCellValue('C' . $row, $item['baik'] ?? 0);
            $sheet->setCellValue('D' . $row, $item['rusak_ringan'] ?? 0);
            $sheet->setCellValue('E' . $row, $item['rusak_berat'] ?? 0);
            $sheet->setCellValue('F' . $row, $item['total'] ?? 0);
            $row++;
        }
    }
    
    private function export_jenis_bmn($sheet, $data, $start_row)
    {
        $headers = ['No', 'Jenis BMN', 'Baik', 'Rusak Ringan', 'Rusak Berat', 'Total'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $start_row, $header);
            $sheet->getStyle($col . $start_row)->applyFromArray($this->get_header_style());
            $col++;
        }
        
        $row = $start_row + 1;
        $no = 1;
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $item['jenis_bmn']);
            $sheet->setCellValue('C' . $row, $item['baik'] ?? 0);
            $sheet->setCellValue('D' . $row, $item['rusak_ringan'] ?? 0);
            $sheet->setCellValue('E' . $row, $item['rusak_berat'] ?? 0);
            $sheet->setCellValue('F' . $row, $item['total'] ?? 0);
            $row++;
        }
    }
    
    private function export_ruangan($sheet, $data, $start_row)
    {
        $headers = ['No', 'Nama Ruangan', 'Baik', 'Rusak Ringan', 'Rusak Berat', 'Total'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $start_row, $header);
            $sheet->getStyle($col . $start_row)->applyFromArray($this->get_header_style());
            $col++;
        }
        
        $row = $start_row + 1;
        $no = 1;
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $item['nama_ruangan']);
            $sheet->setCellValue('C' . $row, $item['baik'] ?? 0);
            $sheet->setCellValue('D' . $row, $item['rusak_ringan'] ?? 0);
            $sheet->setCellValue('E' . $row, $item['rusak_berat'] ?? 0);
            $sheet->setCellValue('F' . $row, $item['total'] ?? 0);
            $row++;
        }
    }
    
    private function export_tahun_perolehan($sheet, $data, $start_row)
    {
        $headers = ['No', 'Nama Barang', 'Tahun', 'Baik', 'Rusak Ringan', 'Rusak Berat', 'Total'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $start_row, $header);
            $sheet->getStyle($col . $start_row)->applyFromArray($this->get_header_style());
            $col++;
        }
        
        $row = $start_row + 1;
        $no = 1;
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $item['nama_barang']);
            $sheet->setCellValue('C' . $row, $item['tahun']);
            $sheet->setCellValue('D' . $row, $item['baik'] ?? 0);
            $sheet->setCellValue('E' . $row, $item['rusak_ringan'] ?? 0);
            $sheet->setCellValue('F' . $row, $item['rusak_berat'] ?? 0);
            $sheet->setCellValue('G' . $row, $item['total'] ?? 0);
            $row++;
        }
    }
    
    private function export_pemeliharaan($sheet, $data, $start_row)
    {
        $headers = ['No', 'Nama Barang', 'Jumlah Aset', 'Jumlah Pemeliharaan', 'Persentase'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $start_row, $header);
            $sheet->getStyle($col . $start_row)->applyFromArray($this->get_header_style());
            $col++;
        }
        
        $row = $start_row + 1;
        $no = 1;
        foreach ($data as $item) {
            $persen = $item['jumlah_aset'] > 0 
                ? round(($item['jumlah_pemeliharaan'] / $item['jumlah_aset']) * 100, 1) 
                : 0;
            
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $item['nama_barang']);
            $sheet->setCellValue('C' . $row, $item['jumlah_aset'] ?? 0);
            $sheet->setCellValue('D' . $row, $item['jumlah_pemeliharaan'] ?? 0);
            $sheet->setCellValue('E' . $row, $persen . '%');
            $row++;
        }
    }
    
    private function get_header_style()
    {
        return [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
        ];
    }
}