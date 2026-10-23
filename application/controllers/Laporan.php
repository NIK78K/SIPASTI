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

    public function kartu_persediaan(){
        $barang_id = $this->input->get('barang_id');
        $barang_search = trim($this->input->get('barang_search', true) ?: '');
        $bulan     = $this->input->get('bulan') ?: date('n');
        $tahun     = $this->input->get('tahun') ?: date('Y');

        $this->load->model(['Transaksi_persediaan_model', 'Barang_persediaan_model', 'Stok_awal_persediaan_model']);

        $barang_list = $this->Barang_persediaan_model->get_all_with_kategori();
        if ($barang_search !== '') {
            $barang_list = array_values(array_filter($barang_list, function ($b) use ($barang_search) {
                $hay = ($b->kode_barang ?? '') . ' ' . ($b->nama_barang ?? '');
                return stripos($hay, $barang_search) !== false;
            }));
        }

        $data = [
            'barang_id'   => $barang_id,
            'barang_search' => $barang_search,
            'bulan'       => (int) $bulan,
            'tahun'       => (int) $tahun,
            'barang_list' => $barang_list,
            'tahun_list'  => $this->Transaksi_persediaan_model->get_tahun_list(),
            'barang'      => !empty($barang_id) ? $this->Barang_persediaan_model->get_by_id($barang_id) : null,
            'stok_awal'   => 0,
            'total_masuk' => 0,
            'total_keluar' => 0,
            'rows'        => [],
            'saldo_akhir' => 0,
        ];

        if (empty($data['tahun_list']) || !in_array((int) $tahun, array_map('intval', $data['tahun_list']))) {
            $data['tahun_list'][] = (int) $tahun;
            rsort($data['tahun_list']);
        }

        if (!empty($barang_id)) {
            $sa = $this->db->where('barang_id', $barang_id)
                           ->where('bulan', (int) $bulan)
                           ->where('tahun', (int) $tahun)
                           ->get('stok_awal_persediaan')
                           ->row();
            $data['stok_awal'] = $sa ? (int) $sa->jumlah : 0;

            $trx = $this->Transaksi_persediaan_model->get_filtered(null, $barang_id, $bulan, $tahun);
            if (!is_array($trx)) $trx = [];
            usort($trx, function ($a, $b) {
                $ta = strtotime($a->tanggal ?? '') ?: 0;
                $tb = strtotime($b->tanggal ?? '') ?: 0;
                if ($ta === $tb) return ((int) ($a->id ?? 0)) - ((int) ($b->id ?? 0));
                return $ta - $tb;
            });

            $sisa = (int) $data['stok_awal'];
            $tot_masuk = 0; $tot_keluar = 0;
            foreach ($trx as $t) {
                $jml = (int) ($t->jumlah ?? 0);
                if (($t->jenis ?? '') === 'masuk') {
                    $sisa += $jml;
                    $tot_masuk += $jml;
                } else {
                    $sisa -= $jml;
                    $tot_keluar += $jml;
                }
                $t->sisa = $sisa;
            }
            $data['rows'] = $trx;
            $data['total_masuk'] = $tot_masuk;
            $data['total_keluar'] = $tot_keluar;
            $data['saldo_akhir'] = $sisa;
        }

        $this->load->view('layout/header');

        if ($this->session->userdata('role') === 'teknisi') {
            $this->load->view('layout/sidebar_teknisi');
        } else {
            $this->load->view('layout/sidebar');
        }

        $this->load->view('laporan/kartu_persediaan', $data);
        $this->load->view('layout/footer');
    }

    public function bulanan_persediaan(){
        $bulan       = $this->input->get('bulan') ?: date('n');
        $tahun       = $this->input->get('tahun') ?: date('Y');
        $kategori_id = $this->input->get('kategori_id');
        $kode        = $this->input->get('kode');

        $data = $this->_data_bulanan($bulan, $tahun, $kategori_id, $kode);

        $this->load->view('layout/header');

        if ($this->session->userdata('role') === 'teknisi') {
            $this->load->view('layout/sidebar_teknisi');
        } else {
            $this->load->view('layout/sidebar');
        }

        $this->load->view('laporan/bulanan_persediaan', $data);
        $this->load->view('layout/footer');
    }

    public function tahunan_persediaan(){
        $tahun = $this->input->get('tahun') ?: date('Y');

        $data = $this->_data_tahunan($tahun);

        $this->load->view('layout/header');

        if ($this->session->userdata('role') === 'teknisi') {
            $this->load->view('layout/sidebar_teknisi');
        } else {
            $this->load->view('layout/sidebar');
        }

        $this->load->view('laporan/tahunan_persediaan', $data);
        $this->load->view('layout/footer');
    }

    private function _data_bulanan($bulan, $tahun, $kategori_id = null, $kode = null){
        $this->load->model(['Transaksi_persediaan_model', 'Barang_persediaan_model', 'Stok_awal_persediaan_model']);

        $tahun_list = $this->Transaksi_persediaan_model->get_tahun_list();
        if (empty($tahun_list) || !in_array((int) $tahun, array_map('intval', $tahun_list))) {
            $tahun_list[] = (int) $tahun;
            rsort($tahun_list);
        }

        $sa_map = [];
        $sa_rows = $this->db->where('bulan', (int) $bulan)
                            ->where('tahun', (int) $tahun)
                            ->get('stok_awal_persediaan')
                            ->result();
        foreach ($sa_rows as $sa) {
            $sa_map[$sa->barang_id] = (int) ($sa->jumlah ?? 0);
        }

        $mut = [];
        $ag = $this->db->select('barang_id, jenis, SUM(jumlah) AS total', false)
                       ->from('transaksi_persediaan')
                       ->where('MONTH(tanggal)', (int) $bulan)
                       ->where('YEAR(tanggal)', (int) $tahun)
                       ->group_by(['barang_id', 'jenis'])
                       ->get()->result();
        foreach ($ag as $r) {
            if (($r->jenis ?? '') === 'masuk') {
                $mut[$r->barang_id]['masuk'] = (int) $r->total;
            } else {
                $mut[$r->barang_id]['keluar'] = (int) $r->total;
            }
        }

        $rows = [];
        foreach ($this->Barang_persediaan_model->get_all_with_kategori() as $b) {
            if (isset($b->status) && $b->status === 'Nonaktif') continue;
            if (!empty($kategori_id) && (string) ($b->kategori_id ?? '') !== (string) $kategori_id) continue;
            if (!empty($kode) && stripos($b->kode_barang ?? '', $kode) === false) continue;
            $awal   = $sa_map[$b->id] ?? 0;
            $masuk  = $mut[$b->id]['masuk'] ?? 0;
            $keluar = $mut[$b->id]['keluar'] ?? 0;
            $akhir  = $awal + $masuk - $keluar;
            $min    = (int) ($b->stok_minimum ?? 0);

            $ket = '';
            if ($min > 0) {
                if ($akhir === $min) {
                    $ket = 'Stok minimum tercapai';
                } elseif ($akhir < $min) {
                    $ket = 'Stok sudah melampaui stok minimum';
                }
            }

            $rows[] = (object) [
                'kode_barang'   => $b->kode_barang ?? '-',
                'nama_barang'   => $b->nama_barang ?? '-',
                'satuan'        => $b->satuan ?? '-',
                'stok_awal'     => $awal,
                'masuk'         => $masuk,
                'keluar'        => $keluar,
                'stok_akhir'    => $akhir,
                'stok_minimum'  => $min,
                'keterangan'    => $ket,
            ];
        }

        usort($rows, function ($a, $b) {
            return strnatcasecmp($a->kode_barang ?? '', $b->kode_barang ?? '');
        });

        return [
            'bulan'         => (int) $bulan,
            'tahun'         => (int) $tahun,
            'kategori_id'   => $kategori_id,
            'kode'          => $kode,
            'tahun_list'    => $tahun_list,
            'kategori_list' => $this->Barang_persediaan_model->get_kategori_grouped(),
            'rows'          => $rows,
        ];
    }

    private function _data_tahunan($tahun){
        $this->load->model(['Transaksi_persediaan_model', 'Barang_persediaan_model']);

        $tahun_list = $this->Transaksi_persediaan_model->get_tahun_list();
        if (empty($tahun_list) || !in_array((int) $tahun, array_map('intval', $tahun_list))) {
            $tahun_list[] = (int) $tahun;
            rsort($tahun_list);
        }

        $mut = [];
        $ag = $this->db->select('barang_id, MONTH(tanggal) AS bln, jenis, SUM(jumlah) AS total', false)
                       ->from('transaksi_persediaan')
                       ->where('YEAR(tanggal)', (int) $tahun)
                       ->group_by(['barang_id', 'bln', 'jenis'])
                       ->get()->result();
        foreach ($ag as $r) {
            $mut[$r->barang_id][(int) $r->bln][$r->jenis === 'masuk' ? 'm' : 'k'] = (int) $r->total;
        }

        $rows = [];
        foreach ($this->Barang_persediaan_model->get_all_with_kategori() as $b) {
            if (isset($b->status) && $b->status === 'Nonaktif') continue;
            $per = [];
            $tm = 0; $tk = 0;
            for ($m = 1; $m <= 12; $m++) {
                $mv = $mut[$b->id][$m]['m'] ?? 0;
                $kv = $mut[$b->id][$m]['k'] ?? 0;
                $per[$m] = ['m' => $mv, 'k' => $kv];
                $tm += $mv; $tk += $kv;
            }
            $rows[] = (object) [
                'kode_barang'   => $b->kode_barang ?? '-',
                'nama_barang'   => $b->nama_barang ?? '-',
                'satuan'        => $b->satuan ?? '-',
                'kategori_kode' => $b->kategori_kode ?? '-',
                'kategori_nama' => $b->kategori_nama ?? '-',
                'per_bulan'     => $per,
                'tot_m'         => $tm,
                'tot_k'         => $tk,
            ];
        }

        usort($rows, function ($a, $b) {
            return strnatcasecmp($a->kode_barang ?? '', $b->kode_barang ?? '');
        });

        return [
            'tahun'      => (int) $tahun,
            'tahun_list' => $tahun_list,
            'rows'       => $rows,
        ];
    }

    public function export_excel_bulanan(){
        $bulan       = $this->input->get('bulan') ?: date('n');
        $tahun       = $this->input->get('tahun') ?: date('Y');
        $kategori_id = $this->input->get('kategori_id');
        $kode        = $this->input->get('kode');

        $data = $this->_data_bulanan($bulan, $tahun, $kategori_id, $kode);

        require_once FCPATH . 'vendor/autoload.php';

        $nb = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
               'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $periode = ($nb[(int) $data['bulan']] ?? $data['bulan']) . ' ' . $data['tahun'];

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $ws = $spreadsheet->getActiveSheet();
        $ws->setTitle('Bulanan');

        $ws->mergeCells('A1:I1');
        $ws->setCellValue('A1', 'LAPORAN BULANAN PERSEDIAAN');
        $ws->mergeCells('A2:I2');
        $ws->setCellValue('A2', 'Periode: ' . $periode);
        $ws->getStyle('A1:A2')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 13],
            'alignment' => ['horizontal' => 'center'],
        ]);

        $headers = ['No', 'Kode Barang', 'Nama Barang', 'Satuan', 'Stok Awal', 'Masuk', 'Keluar', 'Stok Akhir', 'Keterangan'];
        foreach ($headers as $i => $h) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1);
            $ws->setCellValue($col . '3', $h);
        }
        $ws->getStyle('A3:I3')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => 'solid', 'startColor' => ['rgb' => '2F75B6']],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
        ]);

        $row = 4;
        $no = 1;
        $t_awal = 0; $t_masuk = 0; $t_keluar = 0; $t_akhir = 0;
        if (empty($data['rows'])) {
            $ws->mergeCells('A' . $row . ':I' . $row);
            $ws->setCellValue('A' . $row, 'Belum ada data barang pada periode ini.');
            $row++;
        } else {
            foreach ($data['rows'] as $r) {
                $t_awal += (int) ($r->stok_awal ?? 0);
                $t_masuk += (int) ($r->masuk ?? 0);
                $t_keluar += (int) ($r->keluar ?? 0);
                $t_akhir += (int) ($r->stok_akhir ?? 0);
                $ws->setCellValue('A' . $row, $no++);
                $ws->setCellValue('B' . $row, $r->kode_barang ?? '-');
                $ws->setCellValue('C' . $row, $r->nama_barang ?? '-');
                $ws->setCellValue('D' . $row, $r->satuan ?? '-');
                $ws->setCellValue('E' . $row, (int) ($r->stok_awal ?? 0));
                $ws->setCellValue('F' . $row, (int) ($r->masuk ?? 0));
                $ws->setCellValue('G' . $row, (int) ($r->keluar ?? 0));
                $ws->setCellValue('H' . $row, (int) ($r->stok_akhir ?? 0));
                $ws->setCellValue('I' . $row, $r->keterangan ?? '-');
                $row++;
            }
        }

        $ws->setCellValue('A' . $row, 'Total');
        $ws->mergeCells('A' . $row . ':D' . $row);
        $ws->setCellValue('E' . $row, $t_awal);
        $ws->setCellValue('F' . $row, $t_masuk);
        $ws->setCellValue('G' . $row, $t_keluar);
        $ws->setCellValue('H' . $row, $t_akhir);
        $ws->setCellValue('I' . $row, '-');
        $ws->getStyle('A' . $row . ':I' . $row)->applyFromArray(['font' => ['bold' => true]]);

        $ws->getStyle('E4:H' . $row)->getNumberFormat()->setFormatCode('#,##0');
        foreach (range('A', 'I') as $c) {
            $ws->getColumnDimension($c)->setAutoSize(true);
        }
        $ws->freezePane('A4');

        $fname = 'Laporan_Bulanan_Persediaan_' . (int) $data['bulan'] . '_' . (int) $data['tahun'] . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fname . '"');
        header('Cache-Control: max-age=0');
        header('Pragma: public');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function export_pdf_bulanan(){
        $bulan       = $this->input->get('bulan') ?: date('n');
        $tahun       = $this->input->get('tahun') ?: date('Y');
        $kategori_id = $this->input->get('kategori_id');
        $kode        = $this->input->get('kode');

        $data = $this->_data_bulanan($bulan, $tahun, $kategori_id, $kode);

        $nb = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
               'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $periode = ($nb[(int) $data['bulan']] ?? $data['bulan']) . ' ' . $data['tahun'];

        $html = '<html><head><meta charset="utf-8"><style>'
            . 'body{font-family:Helvetica,Arial,sans-serif;font-size:10pt;}'
            . 'h2{margin:0;}p{margin:4px 0 12px;}'
            . 'table{width:100%;border-collapse:collapse;}'
            . 'th,td{border:1px solid #555;padding:5px;}'
            . 'th{background:#eee;}td.num{text-align:right;}'
            . 'thead{display:table-header-group;}'
            . '</style></head><body>';
        $html .= '<h2>Laporan Bulanan Persediaan</h2>';
        $html .= '<p>Periode: ' . htmlspecialchars($periode) . ' &mdash; Dicetak: ' . htmlspecialchars(date('d M Y H:i')) . '</p>';
        $html .= '<table><thead><tr><th>No</th><th>Kode Barang</th><th>Nama Barang</th><th>Satuan</th>'
            . '<th>Stok Awal</th><th>Masuk</th><th>Keluar</th><th>Stok Akhir</th><th>Keterangan</th></tr></thead><tbody>';

        $no = 1;
        $t_awal = 0; $t_masuk = 0; $t_keluar = 0; $t_akhir = 0;
        if (empty($data['rows'])) {
            $html .= '<tr><td colspan="9" style="text-align:center;">Belum ada data barang pada periode ini.</td></tr>';
        } else {
            foreach ($data['rows'] as $r) {
                $t_awal += (int) ($r->stok_awal ?? 0);
                $t_masuk += (int) ($r->masuk ?? 0);
                $t_keluar += (int) ($r->keluar ?? 0);
                $t_akhir += (int) ($r->stok_akhir ?? 0);
                $html .= '<tr><td>' . ($no++) . '</td>'
                    . '<td>' . htmlspecialchars($r->kode_barang ?? '-') . '</td>'
                    . '<td>' . htmlspecialchars($r->nama_barang ?? '-') . '</td>'
                    . '<td>' . htmlspecialchars($r->satuan ?? '-') . '</td>'
                    . '<td class="num">' . number_format((int) ($r->stok_awal ?? 0)) . '</td>'
                    . '<td class="num">' . number_format((int) ($r->masuk ?? 0)) . '</td>'
                    . '<td class="num">' . number_format((int) ($r->keluar ?? 0)) . '</td>'
                    . '<td class="num">' . number_format((int) ($r->stok_akhir ?? 0)) . '</td>'
                    . '<td>' . htmlspecialchars($r->keterangan ?? '-') . '</td></tr>';
            }
        }
        $html .= '<tr><td colspan="4"><strong>Total</strong></td>'
            . '<td class="num"><strong>' . number_format($t_awal) . '</strong></td>'
            . '<td class="num"><strong>' . number_format($t_masuk) . '</strong></td>'
            . '<td class="num"><strong>' . number_format($t_keluar) . '</strong></td>'
            . '<td class="num"><strong>' . number_format($t_akhir) . '</strong></td>'
            . '<td>-</td></tr>';
        $html .= '</tbody></table></body></html>';

        $this->load->library('pdf');
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'landscape');
        $this->pdf->render();
        $this->pdf->stream('Laporan_Bulanan_Persediaan_' . (int) $data['bulan'] . '_' . (int) $data['tahun'] . '.pdf', ['Attachment' => 0]);
        exit;
    }

    public function export_excel_tahunan(){
        $tahun = $this->input->get('tahun') ?: date('Y');

        $data = $this->_data_tahunan($tahun);

        require_once FCPATH . 'vendor/autoload.php';

        $nama_bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $ws = $spreadsheet->getActiveSheet();
        $ws->setTitle('Tahunan');

        $ws->mergeCells('A1:AC1');
        $ws->setCellValue('A1', 'REKAP TAHUNAN PERSEDIAAN');
        $ws->mergeCells('A2:AC2');
        $ws->setCellValue('A2', 'Tahun: ' . $data['tahun']);
        $ws->getStyle('A1:A2')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 13],
            'alignment' => ['horizontal' => 'center'],
        ]);

        $ws->setCellValue('A3', 'No');
        $ws->setCellValue('B3', 'Barang');
        $ws->setCellValue('C3', 'Satuan');
        $c = 4;
        foreach ($nama_bulan as $nama) {
            $col1 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c);
            $col2 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c + 1);
            $ws->mergeCells($col1 . '3:' . $col2 . '3');
            $ws->setCellValue($col1 . '3', $nama);
            $ws->setCellValue($col1 . '4', 'M');
            $ws->setCellValue($col2 . '4', 'K');
            $c += 2;
        }
        $colT1 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c);
        $colT2 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c + 1);
        $ws->mergeCells($colT1 . '3:' . $colT2 . '3');
        $ws->setCellValue($colT1 . '3', 'Total');
        $ws->setCellValue($colT1 . '4', 'M');
        $ws->setCellValue($colT2 . '4', 'K');
        $ws->getStyle('A3:' . $colT2 . '4')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => 'solid', 'startColor' => ['rgb' => '2F75B6']],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
        ]);

        $row = 5;
        $no = 1;
        $t_bln = [];
        for ($i = 1; $i <= 12; $i++) $t_bln[$i] = ['m' => 0, 'k' => 0];
        $t_tm = 0; $t_tk = 0;
        if (empty($data['rows'])) {
            $ws->mergeCells('A' . $row . ':' . $colT2 . $row);
            $ws->setCellValue('A' . $row, 'Belum ada data barang pada tahun ini.');
            $row++;
        } else {
            foreach ($data['rows'] as $r) {
                $t_tm += (int) ($r->tot_m ?? 0);
                $t_tk += (int) ($r->tot_k ?? 0);
                $ws->setCellValue('A' . $row, $no++);
                $ws->setCellValue('B' . $row, ($r->nama_barang ?? '-') . ' [' . ($r->kode_barang ?? '-') . ']');
                $ws->setCellValue('C' . $row, $r->satuan ?? '-');
                $c = 4;
                for ($m = 1; $m <= 12; $m++) {
                    $mv = (int) ($r->per_bulan[$m]['m'] ?? 0);
                    $kv = (int) ($r->per_bulan[$m]['k'] ?? 0);
                    $t_bln[$m]['m'] += $mv;
                    $t_bln[$m]['k'] += $kv;
                    $ws->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c) . $row, $mv);
                    $ws->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c + 1) . $row, $kv);
                    $c += 2;
                }
                $ws->setCellValue($colT1 . $row, (int) ($r->tot_m ?? 0));
                $ws->setCellValue($colT2 . $row, (int) ($r->tot_k ?? 0));
                $row++;
            }
        }

        $ws->mergeCells('A' . $row . ':C' . $row);
        $ws->setCellValue('A' . $row, 'Total');
        $c = 4;
        for ($m = 1; $m <= 12; $m++) {
            $ws->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c) . $row, $t_bln[$m]['m']);
            $ws->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c + 1) . $row, $t_bln[$m]['k']);
            $c += 2;
        }
        $ws->setCellValue($colT1 . $row, $t_tm);
        $ws->setCellValue($colT2 . $row, $t_tk);
        $ws->getStyle('A' . $row . ':' . $colT2 . $row)->applyFromArray(['font' => ['bold' => true]]);

        $ws->getStyle('D5:' . $colT2 . $row)->getNumberFormat()->setFormatCode('#,##0');
        foreach (range(1, $c + 1) as $ci) {
            $ws->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($ci))->setAutoSize(true);
        }
        $ws->freezePane('D5');

        $fname = 'Rekap_Tahunan_Persediaan_' . (int) $data['tahun'] . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fname . '"');
        header('Cache-Control: max-age=0');
        header('Pragma: public');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function export_pdf_tahunan(){
        $tahun = $this->input->get('tahun') ?: date('Y');

        $data = $this->_data_tahunan($tahun);

        $nama_bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];

        $html = '<html><head><meta charset="utf-8"><style>'
            . 'body{font-family:Helvetica,Arial,sans-serif;font-size:7pt;}'
            . 'h2{margin:0;}p{margin:4px 0 12px;}'
            . 'table{width:100%;border-collapse:collapse;}'
            . 'th,td{border:1px solid #555;padding:3px;}'
            . 'th{background:#eee;}td.num{text-align:right;}'
            . 'thead{display:table-header-group;}'
            . '</style></head><body>';
        $html .= '<h2>Rekap Tahunan Persediaan</h2>';
        $html .= '<p>Tahun: ' . htmlspecialchars($data['tahun']) . ' &mdash; Dicetak: ' . htmlspecialchars(date('d M Y H:i')) . '</p>';
        $html .= '<table><thead><tr><th rowspan="2">No</th><th rowspan="2">Barang</th><th rowspan="2">Satuan</th>';
        foreach ($nama_bulan as $nama) {
            $html .= '<th colspan="2">' . $nama . '</th>';
        }
        $html .= '<th colspan="2">Total</th></tr><tr>';
        for ($i = 1; $i <= 12; $i++) {
            $html .= '<th>M</th><th>K</th>';
        }
        $html .= '<th>M</th><th>K</th></tr></thead><tbody>';

        $no = 1;
        $t_bln = [];
        for ($i = 1; $i <= 12; $i++) $t_bln[$i] = ['m' => 0, 'k' => 0];
        $t_tm = 0; $t_tk = 0;
        if (empty($data['rows'])) {
            $html .= '<tr><td colspan="29" style="text-align:center;">Belum ada data barang pada tahun ini.</td></tr>';
        } else {
            foreach ($data['rows'] as $r) {
                $t_tm += (int) ($r->tot_m ?? 0);
                $t_tk += (int) ($r->tot_k ?? 0);
                $html .= '<tr><td>' . ($no++) . '</td>'
                    . '<td>' . htmlspecialchars(($r->nama_barang ?? '-') . ' [' . ($r->kode_barang ?? '-') . ']') . '</td>'
                    . '<td>' . htmlspecialchars($r->satuan ?? '-') . '</td>';
                for ($m = 1; $m <= 12; $m++) {
                    $mv = (int) ($r->per_bulan[$m]['m'] ?? 0);
                    $kv = (int) ($r->per_bulan[$m]['k'] ?? 0);
                    $t_bln[$m]['m'] += $mv;
                    $t_bln[$m]['k'] += $kv;
                    $html .= '<td class="num">' . ($mv > 0 ? number_format($mv) : '-') . '</td>'
                        . '<td class="num">' . ($kv > 0 ? number_format($kv) : '-') . '</td>';
                }
                $html .= '<td class="num"><strong>' . number_format((int) ($r->tot_m ?? 0)) . '</strong></td>'
                    . '<td class="num"><strong>' . number_format((int) ($r->tot_k ?? 0)) . '</strong></td></tr>';
            }
        }
        $html .= '<tr><td colspan="3"><strong>Total</strong></td>';
        for ($m = 1; $m <= 12; $m++) {
            $html .= '<td class="num"><strong>' . number_format($t_bln[$m]['m']) . '</strong></td>'
                . '<td class="num"><strong>' . number_format($t_bln[$m]['k']) . '</strong></td>';
        }
        $html .= '<td class="num"><strong>' . number_format($t_tm) . '</strong></td>'
            . '<td class="num"><strong>' . number_format($t_tk) . '</strong></td></tr>';
        $html .= '</tbody></table></body></html>';

        $this->load->library('pdf');
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A3', 'landscape');
        $this->pdf->render();
        $this->pdf->stream('Rekap_Tahunan_Persediaan_' . (int) $data['tahun'] . '.pdf', ['Attachment' => 0]);
        exit;
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