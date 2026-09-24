<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap_zoom extends CI_Controller {

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

    /* ===============================
       HALAMAN UTAMA
    =============================== */
public function index()
{
    $role = $this->session->userdata('role');
    $sidebar = in_array($role, ['admin', 'superadmin']) 
               ? 'layout/sidebar' 
               : 'layout/sidebar_teknisi';  // ← pakai sidebar_teknisi

    $this->load->view('layout/header');
    $this->load->view($sidebar);            // ← bukan 'layout/sidebar'
    $this->load->view('rekap/rekap_zoom');
    $this->load->view('layout/footer');
}

    /* ===============================
       AJAX: DATA TABEL + STAT
    =============================== */
  public function ajax_data()
{
    header('Content-Type: application/json');

    try {
        $jenis  = $this->input->get('jenis_periode') ?: 'bulanan';
        $bulan  = (int)($this->input->get('bulan')  ?: date('n'));
        $tahun  = (int)($this->input->get('tahun')  ?: date('Y'));

        list($start, $end, $periode) = $this->_get_range($jenis, $bulan, $tahun);

        $rows = $this->_get_rows($start, $end);
        $stat = $this->_build_stat($rows);

        echo json_encode([
            'status'  => 'success',
            'periode' => $periode,
            'stat'    => $stat,
            'rows'    => $rows,
        ]);

    } catch (Exception $e) {
        echo json_encode([
            'status'  => 'error',
            'message' => $e->getMessage() . ' — ' . $e->getFile() . ':' . $e->getLine(),
        ]);
    }
}

    /* ===============================
       GENERATE PDF
    =============================== */
    public function generate_pdf()
    {
        error_reporting(0);
        ini_set('display_errors', 0);

        $jenis  = $this->input->get('jenis_periode') ?: 'bulanan';
        $bulan  = (int)($this->input->get('bulan')  ?: date('n'));
        $tahun  = (int)($this->input->get('tahun')  ?: date('Y'));

        [$start, $end, $periode] = $this->_get_range($jenis, $bulan, $tahun);

        $rows = $this->_get_rows($start, $end);
        $stat = $this->_build_stat($rows);

        $bulan_id = [
            1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
            7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
        ];

        $data = [
            'jenis_periode' => $jenis,
            'periode'       => $periode,
            'rows'          => $rows,
            'stat'          => $stat,
            'tanggal_cetak' => $this->_fmt_tanggal(date('Y-m-d')),
        ];

        require_once APPPATH . '../vendor/autoload.php';

        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Times New Roman');
        $options->set('chroot', FCPATH);

        $dompdf = new \Dompdf\Dompdf($options);
        $html   = $this->load->view('rekap/rekap_zoom_pdf', $data, true);
        $dompdf->loadHtml($html);
        $dompdf->setPaper([0, 0, 595.28, 841.89], 'portrait');
        $dompdf->render();

        while (ob_get_level()) ob_end_clean();

        $label    = $jenis === 'tahunan' ? $tahun : ($bulan_id[$bulan] ?? $bulan) . '_' . $tahun;
        $filename = "Rekap_Zoom_{$label}.pdf";

        $dompdf->stream($filename, ['Attachment' => 0]);
        exit;
    }

    /* ─────────────────────────────────────────────────────────
       PRIVATE HELPERS
    ───────────────────────────────────────────────────────── */

    private function _get_range(string $jenis, int $bulan, int $tahun): array
    {
        $bulan_id = [
            1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
            7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
        ];

        if ($jenis === 'tahunan') {
            $start   = "{$tahun}-01-01";
            $end     = "{$tahun}-12-31";
            $periode = "Tahun {$tahun}";
        } else {
            $bulan_str = str_pad($bulan, 2, '0', STR_PAD_LEFT);
            $start     = "{$tahun}-{$bulan_str}-01";
            $end       = date('Y-m-t', strtotime($start));
            $periode   = ($bulan_id[$bulan] ?? $bulan) . ' ' . $tahun;
        }

        return [$start, $end, $periode];
    }

    private function _get_rows(string $start, string $end): array
    {
        $raw = $this->db
            ->select('
                t.id,
                t.kode_tiket,
                t.pelapor,
                t.jenis_kerusakan,
                t.tanggal,
                t.tanggal_selesai,
                t.jam,
                t.durasi,
                t.status,
                t.akun_zoom,
                t.created_at
            ')
            ->from('tickets t')
            ->where('t.sub_kategori_id', 12)
            ->where('t.tanggal >=', $start)
            ->where('t.tanggal <=', $end)
            ->order_by('t.tanggal', 'ASC')
            ->order_by('t.jam',     'ASC')
            ->get()
            ->result_array();

        $bulan_id = [
            1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'Mei',6=>'Jun',
            7=>'Jul',8=>'Agt',9=>'Sep',10=>'Okt',11=>'Nov',12=>'Des'
        ];

        foreach ($raw as &$r) {
            // Parse semua field dari jenis_kerusakan
            $parsed                 = $this->Zoom_model->parse_zoom_detail($r['jenis_kerusakan'] ?? '');
            $r['detail_permintaan'] = $parsed['detail_permintaan'] ?? '';
            $r['jumlah_peserta']    = $parsed['jumlah_peserta']    ?? '';
            $r['jumlah_breakout']   = $parsed['jumlah_breakout']   ?? '';   // ← tambahan

            // Format tanggal tampil
            if (!empty($r['tanggal'])) {
                $d = new DateTime($r['tanggal']);
                $r['tanggal_label'] = $d->format('d') . ' ' . ($bulan_id[(int)$d->format('n')] ?? '') . ' ' . $d->format('Y');

                if (!empty($r['tanggal_selesai'])) {
                    $ds = new DateTime($r['tanggal_selesai']);
                    $r['tanggal_label'] .= ' s.d. ' . $ds->format('d') . ' ' . ($bulan_id[(int)$ds->format('n')] ?? '') . ' ' . $ds->format('Y');
                }
            } else {
                $r['tanggal_label'] = '-';
            }
        }
        unset($r);

        return $raw;
    }

  private function _build_stat(array $rows): array
{
    $total = $selesai = $progress = $menunggu = $total_peserta = $zoom1 = $zoom2 = 0;
    $breakout_zoom1 = $breakout_zoom2 = 0;

    foreach ($rows as $r) {
        $total++;
        if ($r['status'] === 'Selesai')     $selesai++;
        if ($r['status'] === 'On Progress') $progress++;
        if ($r['status'] === 'Menunggu')    $menunggu++;
        $total_peserta += (int)($r['jumlah_peserta'] ?? 0);

        if (($r['akun_zoom'] ?? 0) == 1) {
            $zoom1++;
            $breakout_zoom1 += (int)($r['jumlah_breakout'] ?? 0);
        }
        if (($r['akun_zoom'] ?? 0) == 2) {
            $zoom2++;
            $breakout_zoom2 += (int)($r['jumlah_breakout'] ?? 0);
        }
    }

    return compact('total', 'selesai', 'progress', 'menunggu', 'total_peserta',
                   'zoom1', 'zoom2', 'breakout_zoom1', 'breakout_zoom2');
}

    private function _fmt_tanggal(string $date_str): string
    {
        $bulan_id = [
            1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
            7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember',
        ];
        $d = new DateTime($date_str);
        return $d->format('d') . ' ' . ($bulan_id[(int)$d->format('n')] ?? '') . ' ' . $d->format('Y');
    }

    
}