<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Laporan_model');
    }

    public function index(){
        $tahun = $this->input->get('tahun') ?? date('Y');
        $view  = $this->input->get('view') ?? 'tiket';

        $masuk   = $this->Laporan_model->total_masuk($tahun);
        $selesai = $this->Laporan_model->total_selesai($tahun);

        $data = [
            'tahun'   => $tahun,
            'view'    => $view,
            'masuk'   => $masuk,
            'selesai' => $selesai,
            'batal'   => $this->Laporan_model->total_batal($tahun),
            'success' => $masuk > 0 ? round(($selesai / $masuk) * 100, 1) : 0,
            'it'      => $this->Laporan_model->get_perangkat_it(),
            'umum'    => $this->Laporan_model->get_perangkat_umum(),
            'teknisi' => $this->Laporan_model->performa_teknisi($tahun),
        ];

        $this->load->view('layout/header');

        if ($this->session->userdata('role') === 'teknisi') {
            $this->load->view('layout/sidebar_teknisi');
        } else {
            $this->load->view('layout/sidebar');
        }

        $this->load->view('laporan/index', $data);
        $this->load->view('layout/footer');
    }

    public function ajax_teknisi()
    {
        $tahun   = $this->input->post('tahun') ?? date('Y');
        $bulan   = $this->input->post('bulan');
        $user_id = $this->input->post('user_id');

        $data = $this->Laporan_model->performa_teknisi_filter($tahun, $bulan, $user_id);

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function ajax_range(){
        $range = $this->input->post('range');
        $date  = $this->input->post('date');

        if (!$range || !$date) {
            header('Content-Type: application/json');
            echo json_encode([]);
            return;
        }

        $data = $this->Laporan_model->tabel_harian($range, $date);

        foreach ($data as &$d) {
            $d['tanggal'] = date('d M Y', strtotime($d['tanggal']));
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function ajax_tiket_detail(){
        $start = $this->input->post('start');
        $end   = $this->input->post('end');

        if (!$start || !$end) {
            echo json_encode([]);
            return;
        }

        $data = $this->Laporan_model->detail_tiket_range_manual($start, $end);

        foreach ($data as &$d) {
            $d['created_at'] = date('d M Y H:i', strtotime($d['created_at']));
            $d['selesai_at'] = $d['selesai_at']
                ? date('d M Y H:i', strtotime($d['selesai_at']))
                : '-';
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function ajax_saran()
    {
        $start  = $this->input->post('start');
        $end    = $this->input->post('end');
        $rating = $this->input->post('rating');

        if (!$start || !$end) { echo json_encode([]); return; }

        $data = $this->Laporan_model->get_saran_masukan($start, $end, $rating);

        foreach ($data as &$d) {
            $d['selesai_at'] = $d['selesai_at']
                ? date('d M Y H:i', strtotime($d['selesai_at']))
                : '-';
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    // ─────────────────────────────────────────────────────────────
    // EXPORT EXCEL
    // ─────────────────────────────────────────────────────────────
    public function export_excel_range()
    {
        $view  = $this->input->get('view');
        $tahun = $this->input->get('tahun') ?? date('Y');

        switch ($view) {

            case 'tiket':
                $start = $this->input->get('start');
                $end   = $this->input->get('end');
                if (!$start || !$end) show_error('Range tanggal tidak valid');
                $data = [
                    'view'    => 'tiket',
                    'start'   => $start,
                    'end'     => $end,
                    'tickets' => $this->Laporan_model->detail_tiket_range_by_date($start, $end)
                ];
                break;

            case 'perangkat_it':
                $data = [
                    'view'      => 'perangkat_it',
                    'perangkat' => $this->db->order_by('kode_barang', 'ASC')
                                            ->get('perangkat')
                                            ->result_array()
                ];
                break;

            case 'perangkat_umum':
                $data = [
                    'view' => 'perangkat_umum',
                    'umum' => $this->db->order_by('kode_barang', 'ASC')
                                       ->get('inventory_umum')
                                       ->result_array()
                ];
                break;

            // ── TEKNISI: multi-sheet PhpSpreadsheet ──────────────
            case 'teknisi':
                $bulan   = $this->input->get('bulan');
                $user_id = $this->input->get('user_id');
                $rows    = $this->Laporan_model->performa_teknisi_filter($tahun, $bulan, $user_id);

                // Kumpulkan detail tiket per teknisi
                $detail_per_teknisi = [];
                foreach ($rows as $t) {
                    $detail_per_teknisi[$t['id']] = [
                        'name'  => $t['name'],
                        'tiket' => $this->Laporan_model->get_detail_tiket_by_teknisi(
                                       $t['id'], $tahun, $bulan
                                   )
                    ];
                }

                $this->_export_excel_teknisi_multisheet([
                    'tahun'              => $tahun,
                    'bulan'              => $bulan,
                    'user_id'            => $user_id,
                    'teknisi'            => $rows,
                    'detail_per_teknisi' => $detail_per_teknisi,
                ]);
                return; // stop — header & exit sudah di dalam method

            case 'saran':
                $start = $this->input->get('start');
                $end   = $this->input->get('end');
                $data  = [
                    'view'  => 'saran',
                    'start' => $start,
                    'end'   => $end,
                    'rows'  => $this->Laporan_model->get_saran_masukan($start, $end, null)
                ];
                break;

            default:
                show_error('View tidak valid');
        }

        // Untuk view selain teknisi → pakai view HTML lama
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=laporan_{$view}.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $this->load->view('laporan/export_excel_range', $data);
    }

    // ─────────────────────────────────────────────────────────────
    // PRIVATE: Generate Excel multi-sheet untuk teknisi
    // ─────────────────────────────────────────────────────────────
    private function _export_excel_teknisi_multisheet($data)
    {
        require_once FCPATH . 'vendor/autoload.php';

        $nb = [
            '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        $periodeLabel = 'Tahun ' . $data['tahun']
            . (!empty($data['bulan']) ? ' / ' . $nb[(int)$data['bulan']] : ' (Semua Bulan)');

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);

        // ══════════════════════════════════════════════════════════
        // SHEET 1 — REKAP PERFORMA
        // ══════════════════════════════════════════════════════════
        $ws = $spreadsheet->createSheet();
        $ws->setTitle('Rekap Performa');

        // -- Judul --
        $ws->mergeCells('A1:E1');
        $ws->setCellValue('A1', 'LAPORAN PERFORMA TEKNISI — ' . strtoupper($periodeLabel));
        $ws->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 13, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => 'solid', 'startColor' => ['rgb' => '4472C4']],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
        ]);
        $ws->getRowDimension(1)->setRowHeight(24);

        // -- Header kolom --
        $headers = ['Rank', 'Nama Teknisi', 'Total Tiket', 'Rating', 'Performa'];
        foreach ($headers as $i => $h) {
            $col = chr(65 + $i); // A, B, C, D, E
            $ws->setCellValue($col . '2', $h);
            $ws->getStyle($col . '2')->applyFromArray([
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['rgb' => '2F75B6']],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            ]);
        }
        $ws->getRowDimension(2)->setRowHeight(16);

        // -- Data teknisi --
        $medals = ['🥇', '🥈', '🥉'];
        $row    = 3;

        foreach ($data['teknisi'] as $idx => $t) {
            $rating  = floatval($t['rating']);
            $label   = $rating >= 4.5 ? 'Terbaik' : ($rating >= 4.0 ? 'Bagus' : 'Cukup');
            $medal   = $medals[$idx] ?? ('#' . ($idx + 1));
            $bgColor = $rating >= 4.5 ? 'DCFCE7' : ($rating >= 4.0 ? 'DBEAFE' : 'FEF9C3');
            $fgColor = $rating >= 4.5 ? '166534' : ($rating >= 4.0 ? '1E40AF' : '854D0E');

            $ws->setCellValue('A' . $row, $medal . ' ' . ($idx + 1));
            $ws->setCellValue('B' . $row, $t['name']);
            $ws->setCellValue('C' . $row, (int)$t['total']);
            $ws->setCellValue('D' . $row, number_format($rating, 1) . ' / 5.0');
            $ws->setCellValue('E' . $row, $label);

            $ws->getStyle('A' . $row . ':E' . $row)->applyFromArray([
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => $bgColor]],
                'font' => ['color' => ['rgb' => $fgColor]],
            ]);
            $ws->getStyle('A' . $row)->getAlignment()->setHorizontal('center');
            $ws->getStyle('C' . $row)->getAlignment()->setHorizontal('center');
            $ws->getStyle('D' . $row)->getAlignment()->setHorizontal('center');
            $ws->getStyle('E' . $row)->getAlignment()->setHorizontal('center');

            $row++;
        }

        // -- Baris summary --
        $totalTiket = array_sum(array_column($data['teknisi'], 'total'));
        $rated      = array_filter($data['teknisi'], fn($t) => floatval($t['rating']) > 0);
        $avgRating  = !empty($rated)
            ? array_sum(array_column($rated, 'rating')) / count($rated)
            : 0;

        $row++; // baris kosong

        // Total tiket
        $ws->mergeCells('A' . $row . ':B' . $row);
        $ws->setCellValue('A' . $row, 'TOTAL TIKET DISELESAIKAN');
        $ws->setCellValue('C' . $row, $totalTiket . ' tiket');
        $ws->getStyle('A' . $row . ':E' . $row)->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'F0F0F0']],
        ]);
        $ws->getStyle('A' . $row)->getAlignment()->setHorizontal('right');
        $ws->getStyle('C' . $row)->getAlignment()->setHorizontal('center');
        $row++;

        // Rata-rata rating
        $ws->mergeCells('A' . $row . ':B' . $row);
        $ws->setCellValue('A' . $row, 'RATING RATA-RATA');
        $ws->setCellValue('C' . $row, number_format($avgRating, 2) . ' / 5.0');
        $ws->getStyle('A' . $row . ':E' . $row)->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'F0F0F0']],
        ]);
        $ws->getStyle('A' . $row)->getAlignment()->setHorizontal('right');
        $ws->getStyle('C' . $row)->getAlignment()->setHorizontal('center');
        $row++;

        // Top performer (hanya kalau lebih dari 1 teknisi)
        if (count($data['teknisi']) > 1) {
            $ws->mergeCells('A' . $row . ':B' . $row);
            $ws->setCellValue('A' . $row, 'TOP PERFORMER');
            $ws->setCellValue('C' . $row,
                $data['teknisi'][0]['name']
                . ' (' . number_format(floatval($data['teknisi'][0]['rating']), 1) . ')');
            $ws->getStyle('A' . $row . ':E' . $row)->applyFromArray([
                'font' => ['bold' => true],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'DCFCE7']],
            ]);
            $ws->getStyle('A' . $row)->getAlignment()->setHorizontal('right');
            $ws->getStyle('C' . $row)->getAlignment()->setHorizontal('center');
            $row++;
        }

        // -- Border seluruh tabel rekap --
        if ($row > 3) {
            $ws->getStyle('A2:E' . ($row - 1))->applyFromArray([
                'borders' => ['allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color'       => ['rgb' => 'AAAAAA'],
                ]],
            ]);
        }

        // -- Lebar kolom --
        $ws->getColumnDimension('A')->setWidth(10);
        $ws->getColumnDimension('B')->setWidth(30);
        $ws->getColumnDimension('C')->setWidth(16);
        $ws->getColumnDimension('D')->setWidth(14);
        $ws->getColumnDimension('E')->setWidth(14);

        // ══════════════════════════════════════════════════════════
        // SHEET PER TEKNISI — DETAIL TIKET
        // ══════════════════════════════════════════════════════════
        foreach ($data['detail_per_teknisi'] as $uid => $info) {
            // Nama sheet: max 31 karakter, buang karakter illegal Excel
            $sheetName = preg_replace('/[\/\\\?\*\[\]:\']/', '', $info['name']);
            $sheetName = trim(substr($sheetName, 0, 31));

            $ws2 = $spreadsheet->createSheet();
            $ws2->setTitle($sheetName);

            // -- Judul sheet --
            $ws2->mergeCells('A1:I1');
            $ws2->setCellValue('A1',
                'DETAIL TIKET — ' . strtoupper($info['name']) . ' — ' . strtoupper($periodeLabel));
            $ws2->getStyle('A1')->applyFromArray([
                'font'      => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['rgb' => '166534']],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            ]);
            $ws2->getRowDimension(1)->setRowHeight(22);

            // -- Header kolom --
            $hdrs2 = ['No', 'Kode Tiket', 'Kategori', 'Sub Kategori',
                      'Pelapor', 'Lokasi', 'Tgl Dibuat', 'Tgl Selesai', 'Rating'];
            foreach ($hdrs2 as $i => $h) {
                $col = chr(65 + $i);
                $ws2->setCellValue($col . '2', $h);
                $ws2->getStyle($col . '2')->applyFromArray([
                    'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill'      => ['fillType' => 'solid', 'startColor' => ['rgb' => '2F75B6']],
                    'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
                ]);
            }
            $ws2->getRowDimension(2)->setRowHeight(16);

            if (empty($info['tiket'])) {
                $ws2->mergeCells('A3:I3');
                $ws2->setCellValue('A3', 'Tidak ada data tiket untuk periode ini');
                $ws2->getStyle('A3')->getAlignment()->setHorizontal('center');
                $ws2->getStyle('A3')->getFont()->setItalic(true);
            } else {
                $r2 = 3;
                foreach ($info['tiket'] as $no => $tk) {
                    $rating = intval($tk['rating']);
                    $bgRow  = $r2 % 2 === 0 ? 'F1F5F9' : 'FFFFFF';

                    $ws2->setCellValue('A' . $r2, $no + 1);
                    $ws2->setCellValue('B' . $r2, $tk['kode_tiket'] ?? '');
                    $ws2->setCellValue('C' . $r2, $tk['kategori'] ?? '-');
                    $ws2->setCellValue('D' . $r2, $tk['sub_kategori'] ?? '-');
                    $ws2->setCellValue('E' . $r2, $tk['pelapor'] ?? '-');
                    $ws2->setCellValue('F' . $r2, $tk['lokasi'] ?? '-');
                    $ws2->setCellValue('G' . $r2, $tk['created_at'] ?? '-');
                    $ws2->setCellValue('H' . $r2, $tk['selesai_at'] ?? '-');
                    $ws2->setCellValue('I' . $r2, $rating > 0 ? $rating : '-');

                    // Warna zebra
                    $ws2->getStyle('A' . $r2 . ':I' . $r2)->getFill()
                        ->setFillType('solid')->getStartColor()->setRGB($bgRow);

                    // Kolom rata tengah
                    $ws2->getStyle('A' . $r2)->getAlignment()->setHorizontal('center');
                    $ws2->getStyle('I' . $r2)->getAlignment()->setHorizontal('center');

                    // Highlight rating tinggi
                    if ($rating >= 4) {
                        $ws2->getStyle('I' . $r2)->applyFromArray([
                            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'DCFCE7']],
                            'font' => ['color' => ['rgb' => '166534'], 'bold' => true],
                        ]);
                    } elseif ($rating > 0 && $rating <= 2) {
                        $ws2->getStyle('I' . $r2)->applyFromArray([
                            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'FEE2E2']],
                            'font' => ['color' => ['rgb' => '991B1B'], 'bold' => true],
                        ]);
                    }

                    $r2++;
                }

                // -- Baris total --
                $totalBaris = count($info['tiket']);
                $sumRating  = array_sum(array_column($info['tiket'], 'rating'));
                $avgR       = $totalBaris > 0 ? $sumRating / $totalBaris : 0;

                $r2++; // baris kosong
                $ws2->mergeCells('A' . $r2 . ':H' . $r2);
                $ws2->setCellValue('A' . $r2, 'TOTAL: ' . $totalBaris . ' tiket dikerjakan');
                $ws2->setCellValue('I' . $r2, 'Avg: ' . number_format($avgR, 1));
                $ws2->getStyle('A' . $r2 . ':I' . $r2)->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'F0F0F0']],
                ]);
                $ws2->getStyle('A' . $r2)->getAlignment()->setHorizontal('center');
                $ws2->getStyle('I' . $r2)->getAlignment()->setHorizontal('center');

                // -- Border --
                $ws2->getStyle('A2:I' . $r2)->applyFromArray([
                    'borders' => ['allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color'       => ['rgb' => 'CCCCCC'],
                    ]],
                ]);

                // -- Auto filter --
                $ws2->setAutoFilter('A2:I2');
            }

            // -- Lebar kolom --
            $ws2->getColumnDimension('A')->setWidth(5);
            $ws2->getColumnDimension('B')->setWidth(18);
            $ws2->getColumnDimension('C')->setWidth(22);
            $ws2->getColumnDimension('D')->setWidth(24);
            $ws2->getColumnDimension('E')->setWidth(22);
            $ws2->getColumnDimension('F')->setWidth(22);
            $ws2->getColumnDimension('G')->setWidth(18);
            $ws2->getColumnDimension('H')->setWidth(18);
            $ws2->getColumnDimension('I')->setWidth(8);
        }

        // Aktifkan sheet pertama
        $spreadsheet->setActiveSheetIndex(0);

        // -- Nama file output --
        $nb2    = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                   'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        $suffix = !empty($data['bulan']) ? '_' . $nb2[(int)$data['bulan']] : '';
        $fname  = 'Laporan_Teknisi_' . $data['tahun'] . $suffix . '.xlsx';

        // -- Output ke browser --
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fname . '"');
        header('Cache-Control: max-age=0');
        header('Pragma: public');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // ─────────────────────────────────────────────────────────────
    // EXPORT PDF
    // ─────────────────────────────────────────────────────────────
    public function export_pdf_range()
    {
        $view  = $this->input->get('view');
        $tahun = $this->input->get('tahun') ?? date('Y');

        $namaBulan = [
            '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        switch ($view) {

            case 'tiket':
                $start = $this->input->get('start');
                $end   = $this->input->get('end');
                if (!$start || !$end) show_error('Range tanggal tidak valid');
                $data = [
                    'judul'   => 'Laporan Tiket',
                    'periode' => "{$start} s/d {$end}",
                    'rows'    => $this->Laporan_model->detail_tiket_range_by_date($start, $end),
                    'view'    => 'tiket'
                ];
                break;

            case 'perangkat_it':
                $data = [
                    'judul'   => 'Laporan Perangkat IT',
                    'periode' => 'Semua Data',
                    'rows'    => $this->db->order_by('kode_barang', 'ASC')
                                          ->get('perangkat')
                                          ->result_array(),
                    'view'    => 'perangkat_it'
                ];
                break;

            case 'perangkat_umum':
                $data = [
                    'judul'   => 'Laporan Perangkat Umum',
                    'periode' => 'Semua Data',
                    'rows'    => $this->db->order_by('kode_barang', 'ASC')
                                          ->get('inventory_umum')
                                          ->result_array(),
                    'view'    => 'perangkat_umum'
                ];
                break;

            case 'teknisi':
                $bulan   = $this->input->get('bulan');
                $user_id = $this->input->get('user_id');
                $rows    = $this->Laporan_model->performa_teknisi_filter($tahun, $bulan, $user_id);

                if (!empty($user_id) && !empty($rows)) {
                    $namaTeknisi  = $rows[0]['name'];
                    $periodeLabel = $namaTeknisi . ' — Tahun ' . $tahun
                        . (!empty($bulan) ? ' / ' . $namaBulan[(int)$bulan] : '');
                } elseif (!empty($user_id)) {
                    $periodeLabel = 'Teknisi #' . $user_id . ' — Tahun ' . $tahun
                        . (!empty($bulan) ? ' / ' . $namaBulan[(int)$bulan] : '');
                } else {
                    $periodeLabel = 'Tahun ' . $tahun
                        . (!empty($bulan) ? ' / ' . $namaBulan[(int)$bulan] : ' (Semua Bulan)');
                }

                $data = [
                    'judul'   => 'Laporan Performa Teknisi',
                    'periode' => $periodeLabel,
                    'rows'    => $rows,
                    'view'    => 'teknisi'
                ];
                break;

            case 'saran':
                $start = $this->input->get('start');
                $end   = $this->input->get('end');
                $data  = [
                    'judul'   => 'Daftar Saran & Masukan',
                    'periode' => "{$start} s/d {$end}",
                    'rows'    => $this->Laporan_model->get_saran_masukan($start, $end, null),
                    'view'    => 'saran'
                ];
                break;

            default:
                show_error('View tidak valid');
        }

        $html = $this->load->view('laporan/export_pdf_range', $data, true);

        $this->load->library('pdf'); // Dompdf

        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'landscape');
        $this->pdf->render();

        $this->pdf->stream("laporan_{$view}.pdf", ['Attachment' => true]);
        exit;
    }
}