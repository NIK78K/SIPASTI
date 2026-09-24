<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class Rekap_inventory_excel {
    
    private $spreadsheet;
    
    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
    }
    
    /**
     * Generate Excel dengan Multiple Sheets
     */
    public function generate($summary, $all_data, $filter_kondisi = null, $filter_tahun = null)
    {
        // Sheet 1: Jenis BMN
        $sheet1 = $this->spreadsheet->getActiveSheet();
        $sheet1->setTitle('Jenis BMN');
        $this->create_sheet(
            $sheet1, 
            'JENIS BMN', 
            $all_data['jenis_bmn'], 
            ['No', 'Jenis BMN', 'Baik', 'Rusak Ringan', 'Rusak Berat', 'Total'],
            $summary, 
            $filter_kondisi, 
            $filter_tahun
        );
        
        // Sheet 2: Kondisi Barang
        $sheet2 = $this->spreadsheet->createSheet();
        $sheet2->setTitle('Kondisi Barang');
        $this->create_sheet(
            $sheet2, 
            'NAMA BARANG', 
            $all_data['kondisi_barang'], 
            ['No', 'Nama Barang', 'Baik', 'Rusak Ringan', 'Rusak Berat', 'Total'],
            $summary, 
            $filter_kondisi, 
            $filter_tahun
        );
        
        // Sheet 3: Ruangan
        $sheet3 = $this->spreadsheet->createSheet();
        $sheet3->setTitle('Ruangan');
        $this->create_sheet(
            $sheet3, 'RUANGAN', 
            $all_data['ruangan'], 
            ['No', 'Nama Ruangan', 'Baik', 'Rusak Ringan', 'Rusak Berat', 'Total'],
            $summary, 
            $filter_kondisi, 
            $filter_tahun
        );
        
        // Sheet 4: Tahun Perolehan
        $sheet4 = $this->spreadsheet->createSheet();
        $sheet4->setTitle('Tahun Perolehan');
        $this->create_sheet_tahun(
            $sheet4, 
            'TAHUN PEROLEHAN', 
            $all_data['tahun_perolehan'], 
            $summary, 
            $filter_kondisi, 
            $filter_tahun
        );
        
        $this->spreadsheet->setActiveSheetIndex(0);
        
        return $this->spreadsheet;
    }
    
    /**
     * Create Sheet Standard (Jenis BMN, Kondisi, Ruangan)
     */
    private function create_sheet($sheet, $title, $data, $headers, $summary, $kondisi, $tahun)
    {
        // === HEADER SECTION ===
        $sheet->setCellValue('A1', 'LAPORAN INVENTORY ASET TI');
        $sheet->mergeCells('A1:F1');
        $this->apply_title_style($sheet, 'A1:F1');
        
        $sheet->setCellValue('A2', 'BPS PROVINSI RIAU');
        $sheet->mergeCells('A2:F2');
        $this->apply_subtitle_style($sheet, 'A2:F2');
        
        $sheet->setCellValue('A3', 'BERDASARKAN ' . $title);
        $sheet->mergeCells('A3:F3');
        $this->apply_section_style($sheet, 'A3:F3');
        
        $row = 5;
        
        // === INFO FILTER ===
        $sheet->setCellValue('A'.$row, 'Tanggal Export');
        $sheet->setCellValue('B'.$row, ': ' . date('d F Y H:i:s'));
        $sheet->mergeCells('B'.$row.':F'.$row);
        $this->apply_label_style($sheet, 'A'.$row);
        $row++;
        
        if ($kondisi) {
            $sheet->setCellValue('A'.$row, 'Filter Kondisi');
            $sheet->setCellValue('B'.$row, ': ' . ucwords(str_replace('_', ' ', $kondisi)));
            $sheet->mergeCells('B'.$row.':F'.$row);
            $this->apply_label_style($sheet, 'A'.$row);
            $row++;
        }
        
        if ($tahun) {
            $sheet->setCellValue('A'.$row, 'Tahun Perolehan');
            $sheet->setCellValue('B'.$row, ': ' . $tahun);
            $sheet->mergeCells('B'.$row.':F'.$row);
            $this->apply_label_style($sheet, 'A'.$row);
            $row++;
        }
        
        $row += 1;
        
        // === SUMMARY SECTION ===
        $sheet->setCellValue('A'.$row, 'Total Aset');
        $sheet->setCellValue('B'.$row, $summary['total']);
        $sheet->setCellValue('C'.$row, '');
        $sheet->setCellValue('D'.$row, 'Kondisi Baik');
        $sheet->setCellValue('E'.$row, $summary['baik']);
        $this->apply_summary_style($sheet, 'A'.$row.':F'.$row);
        $row++;
        
        $sheet->setCellValue('A'.$row, 'Rusak Ringan');
        $sheet->setCellValue('B'.$row, $summary['rusak_ringan']);
        $sheet->setCellValue('C'.$row, '');
        $sheet->setCellValue('D'.$row, 'Rusak Berat');
        $sheet->setCellValue('E'.$row, $summary['rusak_berat']);
        $this->apply_summary_style($sheet, 'A'.$row.':F'.$row);
        $row += 2;
        
        // Table Headers
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $row, $header);
            $col++;
        }
        $this->apply_table_header($sheet, 'A'.$row.':F'.$row);
        $row++;
        
        // Table Data
        $no = 1;
        $start_data_row = $row;
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $item[array_key_first(array_slice($item, 0, 1, true))]);
            $sheet->setCellValue('C' . $row, $item['baik'] ?? 0);
            $sheet->setCellValue('D' . $row, $item['rusak_ringan'] ?? 0);
            $sheet->setCellValue('E' . $row, $item['rusak_berat'] ?? 0);
            $sheet->setCellValue('F' . $row, $item['total'] ?? 0);
            $row++;
        }
        
        $this->apply_table_data($sheet, 'A'.$start_data_row.':F'.($row-1), $start_data_row);
        
        // Column Widths - Kolom A diperlebar
        $sheet->getColumnDimension('A')->setWidth(22);
        $sheet->getColumnDimension('B')->setWidth(55);
        $sheet->getColumnDimension('C')->setWidth(18);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(15);
    }
    
    /**
     * Create Sheet untuk Tahun Perolehan
     */
    private function create_sheet_tahun($sheet, $title, $data, $summary, $kondisi, $tahun)
    {
        $sheet->setCellValue('A1', 'LAPORAN INVENTORY ASET TI');
        $sheet->mergeCells('A1:G1');
        $this->apply_title_style($sheet, 'A1:G1');
        
        $sheet->setCellValue('A2', 'BPS PROVINSI RIAU');
        $sheet->mergeCells('A2:G2');
        $this->apply_subtitle_style($sheet, 'A2:G2');
        
        $sheet->setCellValue('A3', 'BERDASARKAN ' . $title);
        $sheet->mergeCells('A3:G3');
        $this->apply_section_style($sheet, 'A3:G3');
        
        $row = 5;
        
        $sheet->setCellValue('A'.$row, 'Tanggal Export');
        $sheet->setCellValue('B'.$row, ': ' . date('d F Y H:i:s'));
        $sheet->mergeCells('B'.$row.':G'.$row);
        $this->apply_label_style($sheet, 'A'.$row);
        $row++;
        
        if ($kondisi) {
            $sheet->setCellValue('A'.$row, 'Filter Kondisi');
            $sheet->setCellValue('B'.$row, ': ' . ucwords(str_replace('_', ' ', $kondisi)));
            $sheet->mergeCells('B'.$row.':G'.$row);
            $this->apply_label_style($sheet, 'A'.$row);
            $row++;
        }
        
        if ($tahun) {
            $sheet->setCellValue('A'.$row, 'Tahun Perolehan');
            $sheet->setCellValue('B'.$row, ': ' . $tahun);
            $sheet->mergeCells('B'.$row.':G'.$row);
            $this->apply_label_style($sheet, 'A'.$row);
            $row++;
        }
        
        $row += 1;
        
        $sheet->setCellValue('A'.$row, 'Total Aset');
        $sheet->setCellValue('B'.$row, $summary['total']);
        $sheet->setCellValue('D'.$row, 'Kondisi Baik');
        $sheet->setCellValue('E'.$row, $summary['baik']);
        $this->apply_summary_style($sheet, 'A'.$row.':G'.$row);
        $row++;
        
        $sheet->setCellValue('A'.$row, 'Rusak Ringan');
        $sheet->setCellValue('B'.$row, $summary['rusak_ringan']);
        $sheet->setCellValue('D'.$row, 'Rusak Berat');
        $sheet->setCellValue('E'.$row, $summary['rusak_berat']);
        $this->apply_summary_style($sheet, 'A'.$row.':G'.$row);
        $row += 2;
        
        $headers = ['No', 'Nama Barang', 'Tahun', 'Baik', 'Rusak Ringan', 'Rusak Berat', 'Total'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $row, $header);
            $col++;
        }
        $this->apply_table_header($sheet, 'A'.$row.':G'.$row);
        $row++;
        
        $no = 1;
        $start_data_row = $row;
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $item['nama_barang']);
            $sheet->setCellValue('C' . $row, $item['tahun'] ?? '-');
            $sheet->setCellValue('D' . $row, $item['baik'] ?? 0);
            $sheet->setCellValue('E' . $row, $item['rusak_ringan'] ?? 0);
            $sheet->setCellValue('F' . $row, $item['rusak_berat'] ?? 0);
            $sheet->setCellValue('G' . $row, $item['total'] ?? 0);
            $row++;
        }
        
        $this->apply_table_data($sheet, 'A'.$start_data_row.':G'.($row-1), $start_data_row);
        
        $sheet->getColumnDimension('A')->setWidth(22);
        $sheet->getColumnDimension('B')->setWidth(50);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(18);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(15);
    }
    
    /**
     * Create Sheet untuk Pemeliharaan
     */
    private function create_sheet_pemeliharaan($sheet, $title, $data, $summary, $kondisi, $tahun)
    {
        $sheet->setCellValue('A1', 'LAPORAN INVENTORY ASET TI');
        $sheet->mergeCells('A1:E1');
        $this->apply_title_style($sheet, 'A1:E1');
        
        $sheet->setCellValue('A2', 'BPS PROVINSI RIAU');
        $sheet->mergeCells('A2:E2');
        $this->apply_subtitle_style($sheet, 'A2:E2');
        
        $sheet->setCellValue('A3', $title);
        $sheet->mergeCells('A3:E3');
        $this->apply_section_style($sheet, 'A3:E3');
        
        $row = 5;
        
        $sheet->setCellValue('A'.$row, 'Tanggal Export');
        $sheet->setCellValue('B'.$row, ': ' . date('d F Y H:i:s'));
        $sheet->mergeCells('B'.$row.':E'.$row);
        $this->apply_label_style($sheet, 'A'.$row);
        $row++;
        
        if ($tahun) {
            $sheet->setCellValue('A'.$row, 'Tahun Perolehan');
            $sheet->setCellValue('B'.$row, ': ' . $tahun);
            $sheet->mergeCells('B'.$row.':E'.$row);
            $this->apply_label_style($sheet, 'A'.$row);
            $row++;
        }
        
        $row += 1;
        
        $headers = ['No', 'Nama Barang', 'Jumlah Aset', 'Jumlah Pemeliharaan', 'Persentase'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $row, $header);
            $col++;
        }
        $this->apply_table_header($sheet, 'A'.$row.':E'.$row);
        $row++;
        
        $no = 1;
        $start_data_row = $row;
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
        
        $this->apply_table_data($sheet, 'A'.$start_data_row.':E'.($row-1), $start_data_row);
        
        $sheet->getColumnDimension('A')->setWidth(22);
        $sheet->getColumnDimension('B')->setWidth(55);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(28);
        $sheet->getColumnDimension('E')->setWidth(20);
    }
    
    // === STYLING METHODS - FORMAL (NO COLORS) ===
    
    private function apply_title_style($sheet, $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '000000']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
        ]);
        $sheet->getRowDimension(1)->setRowHeight(28);
    }
    
    private function apply_subtitle_style($sheet, $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '000000']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
        ]);
        $sheet->getRowDimension(2)->setRowHeight(24);
    }
    
    private function apply_section_style($sheet, $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '000000']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
        ]);
    }
    
    private function apply_label_style($sheet, $cell)
    {
        $sheet->getStyle($cell)->applyFromArray([
            'font' => ['bold' => true, 'size' => 10],
        ]);
    }
    
    private function apply_summary_header($sheet, $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '000000']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
        ]);
    }
    
    private function apply_summary_style($sheet, $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => ['size' => 10, 'bold' => true],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]]
        ]);
    }
    
    private function apply_table_header($sheet, $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '000000']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '000000']]]
        ]);
    }
    
    private function apply_table_data($sheet, $range, $start_row)
    {
        $sheet->getStyle($range)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
        ]);
        
        // Center align untuk kolom No dan Angka
        $rows = explode(':', $range);
        $end_col = substr($rows[1], 0, 1);
        $end_row = (int)filter_var($rows[1], FILTER_SANITIZE_NUMBER_INT);
        
        $sheet->getStyle('A'.$start_row.':A'.$end_row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C'.$start_row.':'.$end_col.$end_row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }
    
    /**
     * Save to output
     */
    public function output($filename)
    {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($this->spreadsheet);
        $writer->save('php://output');
        exit;
    }
}