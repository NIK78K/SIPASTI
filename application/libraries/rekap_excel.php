<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class Rekap_excel {
    
    private $spreadsheet;
    
    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
    }
    
    /**
     * Generate Excel - SMART VERSION
     * Otomatis detect apakah data monthly atau single period
     */
    public function generate($data_rekap, $data_pemeliharaan, $periode, $is_all_months = false)
    {
        // SHEET 1: Rekap Perbaikan
        $sheet1 = $this->spreadsheet->getActiveSheet();
        $sheet1->setTitle('Rekap Perbaikan');
        
        if ($is_all_months) {
            $this->create_sheet_rekap_summary($sheet1, $data_rekap, $periode);
        } else {
            $this->create_sheet_rekap_detail($sheet1, $data_rekap, $periode);
        }
        
        // SHEET 2: Pemeliharaan Perangkat
        $sheet2 = $this->spreadsheet->createSheet();
        $sheet2->setTitle('Pemeliharaan Perangkat');
        
        if ($is_all_months) {
            $this->create_sheet_pemeliharaan_summary($sheet2, $data_pemeliharaan, $periode);
        } else {
            $this->create_sheet_pemeliharaan_detail($sheet2, $data_pemeliharaan, $periode);
        }
        
        $this->spreadsheet->setActiveSheetIndex(0);
        
        return $this->spreadsheet;
    }
    
    /**
     * SHEET 1A: Rekap Perbaikan - SUMMARY TABLE (untuk banyak bulan)
     */
    private function create_sheet_rekap_summary($sheet, $data_monthly, $periode)
    {
        // HEADER
        $sheet->setCellValue('A1', 'REKAP PERBAIKAN TIKET IT');
        $sheet->mergeCells('A1:F1');
        $this->apply_title_style($sheet, 'A1:F1');
        
        $sheet->setCellValue('A2', 'Periode: ' . $periode);
        $sheet->mergeCells('A2:F2');
        $this->apply_subtitle_style($sheet, 'A2:F2');
        
        $row = 4;
        
        // Dapatkan semua kategori
        $all_categories = [];
        foreach ($data_monthly as $bulan_info) {
            foreach ($bulan_info['data']['kategori'] as $kat_name => $kat_data) {
                if (!in_array($kat_name, $all_categories)) {
                    $all_categories[] = $kat_name;
                }
            }
        }
        
        // TABLE HEADER
        $col = 'A';
        $sheet->setCellValue($col.$row, 'No');
        $col++;
        $sheet->setCellValue($col.$row, 'Periode');
        $col++;
        
        foreach ($all_categories as $cat) {
            $sheet->setCellValue($col.$row, strtoupper($cat));
            $col++;
        }
        
        $sheet->setCellValue($col.$row, 'TOTAL');
        $last_col = $col;
        
        $this->apply_table_header($sheet, 'A'.$row.':'.$last_col.$row);
        $row++;
        
        // DATA
        $no = 1;
        $grand_total = 0;
        $category_totals = array_fill_keys($all_categories, 0);
        $start_data = $row;
        
        foreach ($data_monthly as $bulan_info) {
            $bulan_nama = $bulan_info['bulan_nama'];
            $data = $bulan_info['data'];
            
            $col = 'A';
            $sheet->setCellValue($col.$row, $no++);
            $col++;
            $sheet->setCellValue($col.$row, $bulan_nama);
            $col++;
            
            foreach ($all_categories as $cat) {
                $cat_total = isset($data['kategori'][$cat]) ? $data['kategori'][$cat]['total'] : 0;
                $category_totals[$cat] += $cat_total;
                $sheet->setCellValue($col.$row, $cat_total > 0 ? $cat_total : '');
                $col++;
            }
            
            $sheet->setCellValue($col.$row, $data['total']);
            $grand_total += $data['total'];
            
            $row++;
        }
        
        $this->apply_table_data($sheet, 'A'.$start_data.':'.$last_col.($row-1));
        
        // TOTAL ROW
        $col = 'A';
        $sheet->setCellValue($col.$row, '');
        $col++;
        $sheet->setCellValue($col.$row, 'TOTAL KESELURUHAN');
        $col++;
        
        foreach ($all_categories as $cat) {
            $sheet->setCellValue($col.$row, $category_totals[$cat]);
            $col++;
        }
        
        $sheet->setCellValue($col.$row, $grand_total);
        
        $this->apply_grand_total_style($sheet, 'A'.$row.':'.$last_col.$row);
        
        // Column Width
        $sheet->getColumnDimension('A')->setWidth(6);
        $sheet->getColumnDimension('B')->setWidth(20);
        for ($i = 'C'; $i <= $last_col; $i++) {
            $sheet->getColumnDimension($i)->setWidth(15);
        }
    }
    
    /**
     * SHEET 1B: Rekap Perbaikan - DETAIL (untuk 1 bulan)
     */
    private function create_sheet_rekap_detail($sheet, $data, $periode)
    {
        // HEADER
        $sheet->setCellValue('A1', 'REKAP PERBAIKAN TIKET IT');
        $sheet->mergeCells('A1:C1');
        $this->apply_title_style($sheet, 'A1:C1');
        
        $sheet->setCellValue('A2', 'Periode: ' . $periode);
        $sheet->mergeCells('A2:C2');
        $this->apply_subtitle_style($sheet, 'A2:C2');
        
        $row = 4;
        
        // TABLE HEADER
        $sheet->setCellValue('A'.$row, 'Kategori');
        $sheet->setCellValue('B'.$row, 'Sub Kategori');
        $sheet->setCellValue('C'.$row, 'Jumlah Tiket');
        $this->apply_table_header($sheet, 'A'.$row.':C'.$row);
        $row++;
        
        // DATA
        $start_data = $row;
        
        if (!empty($data['kategori'])) {
            foreach ($data['kategori'] as $namaKat => $k) {
                // Baris Kategori
                $sheet->setCellValue('A'.$row, strtoupper($namaKat));
                $sheet->mergeCells('A'.$row.':C'.$row);
                $this->apply_kategori_style($sheet, 'A'.$row.':C'.$row);
                $row++;
                
                // Sub Kategori
                foreach ($k['sub'] as $s) {
                    $sheet->setCellValue('A'.$row, '');
                    $sheet->setCellValue('B'.$row, $s['nama']);
                    $sheet->setCellValue('C'.$row, $s['total']);
                    $row++;
                }
                
                // Total per Kategori
                $sheet->setCellValue('A'.$row, '');
                $sheet->setCellValue('B'.$row, 'Total ' . ucfirst(strtolower($namaKat)));
                $sheet->setCellValue('C'.$row, $k['total']);
                $this->apply_total_style($sheet, 'B'.$row.':C'.$row);
                $row++;
            }
            
            $this->apply_table_data($sheet, 'A'.$start_data.':C'.($row-1));
        } else {
            $sheet->setCellValue('A'.$row, 'Tidak ada data');
            $sheet->mergeCells('A'.$row.':C'.$row);
            $sheet->getStyle('A'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $row++;
        }
        
        $row++;
        
        // TOTAL KESELURUHAN
        $sheet->setCellValue('A'.$row, 'TOTAL TIKET SELESAI');
        $sheet->setCellValue('C'.$row, $data['total']);
        $this->apply_grand_total_style($sheet, 'A'.$row.':C'.$row);
        
        // Column Width
        $sheet->getColumnDimension('A')->setWidth(35);
        $sheet->getColumnDimension('B')->setWidth(45);
        $sheet->getColumnDimension('C')->setWidth(20);
    }
    
    /**
     * SHEET 2A: Pemeliharaan - 2 TABLES (Summary + Detail)
     */
    private function create_sheet_pemeliharaan_summary($sheet, $data_monthly, $periode)
    {
        // HEADER
        $sheet->setCellValue('A1', 'REKAP PEMELIHARAAN PERANGKAT');
        $sheet->mergeCells('A1:C1');
        $this->apply_title_style($sheet, 'A1:C1');
        
        $sheet->setCellValue('A2', 'Periode: ' . $periode);
        $sheet->mergeCells('A2:C2');
        $this->apply_subtitle_style($sheet, 'A2:C2');
        
        $row = 4;
        
        // === TABEL 1: RINGKASAN PER BULAN ===
        $sheet->setCellValue('A'.$row, 'Ringkasan Per Bulan');
        $sheet->mergeCells('A'.$row.':C'.$row);
        $this->apply_section_header($sheet, 'A'.$row.':C'.$row);
        $row++;
        
        // TABLE HEADER
        $sheet->setCellValue('A'.$row, 'No');
        $sheet->setCellValue('B'.$row, 'Periode');
        $sheet->setCellValue('C'.$row, 'Jumlah Perbaikan');
        $this->apply_table_header($sheet, 'A'.$row.':C'.$row);
        $row++;
        
        // DATA
        $no = 1;
        $grand_total = 0;
        $start_data = $row;
        
        foreach ($data_monthly as $bulan_info) {
            $sheet->setCellValue('A'.$row, $no++);
            $sheet->setCellValue('B'.$row, $bulan_info['bulan_nama']);
            $sheet->setCellValue('C'.$row, $bulan_info['data']['total_tiket']);
            
            $sheet->getStyle('C'.$row)->getFont()->setBold(true);
            
            $grand_total += $bulan_info['data']['total_tiket'];
            $row++;
        }
        
        $this->apply_table_data($sheet, 'A'.$start_data.':C'.($row-1));
        
        // TOTAL ROW
        $sheet->setCellValue('A'.$row, '');
        $sheet->setCellValue('B'.$row, 'TOTAL KESELURUHAN');
        $sheet->setCellValue('C'.$row, $grand_total);
        $this->apply_grand_total_style($sheet, 'A'.$row.':C'.$row);
        
        $row += 3;
        
        // === TABEL 2: DETAIL PERANGKAT PER BULAN ===
        $sheet->setCellValue('A'.$row, 'Detail Perangkat Per Bulan');
        $col_count = count($data_monthly) + 3; // No, Nama, bulan-bulan, Total
        $last_col = chr(65 + $col_count - 1); // A=65
        $sheet->mergeCells('A'.$row.':'.$last_col.$row);
        $this->apply_section_header($sheet, 'A'.$row.':'.$last_col.$row);
        $row++;
        
        // Kumpulkan data
        $all_devices = [];
        foreach ($data_monthly as $bulan_info) {
            foreach ($bulan_info['data']['data'] as $item) {
                $nama = $item['nama_barang'];
                if (!isset($all_devices[$nama])) {
                    $all_devices[$nama] = [];
                }
                $all_devices[$nama][$bulan_info['bulan_nama']] = $item['jumlah_perbaikan'];
            }
        }
        
        // Sort by total
        $device_totals = [];
        foreach ($all_devices as $nama => $months) {
            $device_totals[$nama] = array_sum($months);
        }
        arsort($device_totals);
        
        // TABLE HEADER
        $col = 'A';
        $sheet->setCellValue($col.$row, 'No');
        $col++;
        $sheet->setCellValue($col.$row, 'Nama Perangkat');
        $col++;
        
        foreach ($data_monthly as $bulan_info) {
            $bulan_parts = explode(' ', $bulan_info['bulan_nama']);
            $bulan_short = $bulan_parts[0]; // Hanya nama bulan
            $sheet->setCellValue($col.$row, $bulan_short);
            $col++;
        }
        
        $sheet->setCellValue($col.$row, 'TOTAL');
        $last_col = $col;
        
        $this->apply_table_header($sheet, 'A'.$row.':'.$last_col.$row);
        $row++;
        
        // DATA
        $no = 1;
        $start_data = $row;
        
        foreach ($device_totals as $nama => $total) {
            $col = 'A';
            $sheet->setCellValue($col.$row, $no++);
            $col++;
            $sheet->setCellValue($col.$row, $nama);
            $col++;
            
            foreach ($data_monthly as $bulan_info) {
                $bulan_nama = $bulan_info['bulan_nama'];
                $jumlah = isset($all_devices[$nama][$bulan_nama]) ? $all_devices[$nama][$bulan_nama] : 0;
                $sheet->setCellValue($col.$row, $jumlah > 0 ? $jumlah : '');
                $col++;
            }
            
            $sheet->setCellValue($col.$row, $total);
            $sheet->getStyle($col.$row)->getFont()->setBold(true);
            
            $row++;
        }
        
        $this->apply_table_data($sheet, 'A'.$start_data.':'.$last_col.($row-1));
        
        // TOTAL PER BULAN ROW
        $col = 'A';
        $sheet->setCellValue($col.$row, '');
        $col++;
        $sheet->setCellValue($col.$row, 'TOTAL PER BULAN');
        $col++;
        
        foreach ($data_monthly as $bulan_info) {
            $sheet->setCellValue($col.$row, $bulan_info['data']['total_tiket']);
            $col++;
        }
        
        $sheet->setCellValue($col.$row, $grand_total);
        
        $this->apply_grand_total_style($sheet, 'A'.$row.':'.$last_col.$row);
        
        // Column Width
        $sheet->getColumnDimension('A')->setWidth(6);
        $sheet->getColumnDimension('B')->setWidth(40);
        for ($i = 'C'; $i <= $last_col; $i++) {
            $sheet->getColumnDimension($i)->setWidth(12);
        }
    }
    
    /**
     * SHEET 2B: Pemeliharaan - DETAIL (untuk 1 bulan)
     */
    private function create_sheet_pemeliharaan_detail($sheet, $data, $periode)
    {
        // HEADER
        $sheet->setCellValue('A1', 'REKAP PEMELIHARAAN PERANGKAT');
        $sheet->mergeCells('A1:C1');
        $this->apply_title_style($sheet, 'A1:C1');
        
        $sheet->setCellValue('A2', 'Periode: ' . $periode);
        $sheet->mergeCells('A2:C2');
        $this->apply_subtitle_style($sheet, 'A2:C2');
        
        $row = 4;
        
        // SUMMARY
        $sheet->setCellValue('A'.$row, 'Total Jenis Perangkat');
        $sheet->setCellValue('B'.$row, $data['total_perangkat']);
        $this->apply_summary_style($sheet, 'A'.$row.':B'.$row);
        $row++;
        
        $sheet->setCellValue('A'.$row, 'Total Tiket Perbaikan');
        $sheet->setCellValue('B'.$row, $data['total_tiket']);
        $this->apply_summary_style($sheet, 'A'.$row.':B'.$row);
        $row += 2;
        
        // TABLE HEADER
        $sheet->setCellValue('A'.$row, 'No');
        $sheet->setCellValue('B'.$row, 'Nama Perangkat');
        $sheet->setCellValue('C'.$row, 'Jumlah Perbaikan');
        $this->apply_table_header($sheet, 'A'.$row.':C'.$row);
        $row++;
        
        // DATA
        $start_data = $row;
        
        if (!empty($data['data'])) {
            $no = 1;
            foreach ($data['data'] as $item) {
                $sheet->setCellValue('A'.$row, $no++);
                $sheet->setCellValue('B'.$row, $item['nama_barang']);
                $sheet->setCellValue('C'.$row, $item['jumlah_perbaikan']);
                $row++;
            }
            
            // LAIN-LAIN
            if (!empty($data['total_lainlain'])) {
                $sheet->setCellValue('A'.$row, '');
                $sheet->setCellValue('B'.$row, 'LAIN-LAIN (Tanpa Perangkat)');
                $sheet->setCellValue('C'.$row, $data['total_lainlain']);
                $this->apply_kategori_style($sheet, 'B'.$row.':C'.$row);
                $row++;
            }
            
            $this->apply_table_data($sheet, 'A'.$start_data.':C'.($row-1));
        } else {
            $sheet->setCellValue('A'.$row, 'Tidak ada data');
            $sheet->mergeCells('A'.$row.':C'.$row);
            $sheet->getStyle('A'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $row++;
        }
        
        // Column Width
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(65);
        $sheet->getColumnDimension('C')->setWidth(25);
    }
    
    // === STYLING METHODS ===
    
    private function apply_title_style($sheet, $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => ['bold' => true, 'size' => 18, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0891B2']]
        ]);
        $row_num = (int)filter_var($range, FILTER_SANITIZE_NUMBER_INT);
        $sheet->getRowDimension($row_num)->setRowHeight(32);
    }
    
    private function apply_subtitle_style($sheet, $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E0F2FE']]
        ]);
    }
    
    private function apply_month_header($sheet, $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => ['bold' => true, 'size' => 13, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0D9488']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_MEDIUM]]
        ]);
    }
    
    private function apply_section_header($sheet, $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => ['bold' => true, 'size' => 13, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0D9488']]
        ]);
    }
    
    private function apply_table_header($sheet, $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E6E6E6']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_MEDIUM]]
        ]);
    }
    
    private function apply_kategori_style($sheet, $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FAFAFA']]
        ]);
    }
    
    private function apply_total_style($sheet, $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F0F0F0']]
        ]);
    }
    
    private function apply_grand_total_style($sheet, $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0891B2']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_MEDIUM]]
        ]);
    }
    
    private function apply_summary_style($sheet, $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => ['bold' => true],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);
    }
    
    private function apply_table_data($sheet, $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER]
        ]);
        
        // Center align kolom angka
        $rows = explode(':', $range);
        $start = preg_replace('/[^0-9]/', '', $rows[0]);
        $end = preg_replace('/[^0-9]/', '', $rows[1]);
        
        // Cari kolom terakhir untuk align center
        preg_match('/([A-Z]+)/', $rows[1], $matches);
        $last_col = $matches[1];
        
        $sheet->getStyle($last_col.$start.':'.$last_col.$end)
              ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }
    
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