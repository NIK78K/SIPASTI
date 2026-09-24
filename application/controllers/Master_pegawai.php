<?php
// application/controllers/Master_pegawai.php

defined('BASEPATH') OR exit('No direct script access allowed');

class Master_pegawai extends CI_Controller
{
    /** Status yang valid sesuai enum di tabel */
    const STATUS_LIST = [
        'PNS',
        'PPPK',
        'CPNS',
        'TB Dalam Negeri - Dibiayai di Universitas Gadjah Mada',
        'TB Dalam Negeri - Dibiayai di Universitas Indonesia',
    ];

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
        $this->load->model('Pegawai_model');
        $this->load->library('upload');
    }

    // ── INDEX ─────────────────────────────────────────────

    public function index()
    {
        $data = [
            'title'        => 'Master Pegawai',
            'pegawai'      => $this->Pegawai_model->get_all(),
            'total_pegawai'=> $this->Pegawai_model->count_total(),
            'total_pns'    => $this->Pegawai_model->count_pns(),
            'total_pppk'   => $this->Pegawai_model->count_pppk(),
            'total_cpns'   => $this->Pegawai_model->count_cpns(),
            'status_list'  => self::STATUS_LIST,
        ];
        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar');
        $this->load->view('master_pegawai/index', $data);
        $this->load->view('layout/footer');
    }

    // ── SIMPAN ────────────────────────────────────────────

    public function simpan()
    {
        $nip_baru = trim($this->input->post('nip_baru', true));

        if (empty($nip_baru)) {
            $this->session->set_flashdata('error', 'NIP Baru wajib diisi.');
            redirect('master_pegawai');
        }

        if ($this->Pegawai_model->nip_exists($nip_baru)) {
            $this->session->set_flashdata('error', 'NIP Baru ' . $nip_baru . ' sudah terdaftar.');
            redirect('master_pegawai');
        }

        $nama = trim($this->input->post('nama', true));
        if (empty($nama)) {
            $this->session->set_flashdata('error', 'Nama wajib diisi.');
            redirect('master_pegawai');
        }

        $status = $this->input->post('status', true);
        if (!in_array($status, self::STATUS_LIST, true)) {
            $status = 'PNS';
        }

        $data = [
            'nip_lama'    => trim($this->input->post('nip_lama', true)),
            'nip_baru'    => $nip_baru,
            'nama'        => $nama,
            'kode_satker' => trim($this->input->post('kode_satker', true)) ?: '1400',
            'jabatan'     => trim($this->input->post('jabatan', true)),
            'status'      => $status,
        ];

        $this->Pegawai_model->insert($data);
        $this->session->set_flashdata('success', 'Pegawai berhasil ditambahkan.');
        redirect('master_pegawai');
    }

    // ── UPDATE ────────────────────────────────────────────

    public function update()
    {
        $id       = (int) $this->input->post('id');
        $nip_baru = trim($this->input->post('nip_baru', true));

        if ($this->Pegawai_model->nip_exists($nip_baru, $id)) {
            $this->session->set_flashdata('error', 'NIP Baru ' . $nip_baru . ' sudah digunakan pegawai lain.');
            redirect('master_pegawai');
        }

        $status = $this->input->post('status', true);
        if (!in_array($status, self::STATUS_LIST, true)) {
            $status = 'PNS';
        }

        $data = [
            'nip_lama'    => trim($this->input->post('nip_lama', true)),
            'nip_baru'    => $nip_baru,
            'nama'        => trim($this->input->post('nama', true)),
            'kode_satker' => trim($this->input->post('kode_satker', true)) ?: '1400',
            'jabatan'     => trim($this->input->post('jabatan', true)),
            'status'      => $status,
        ];

        $this->Pegawai_model->update($id, $data);
        $this->session->set_flashdata('success', 'Data pegawai berhasil diperbarui.');
        redirect('master_pegawai');
    }

    // ── HAPUS ─────────────────────────────────────────────

    public function hapus($id)
    {
        $this->Pegawai_model->delete($id);
        $this->session->set_flashdata('success', 'Data pegawai berhasil dihapus.');
        redirect('master_pegawai');
    }

    // ── EXPORT CSV ────────────────────────────────────────

    public function export()
    {
        $this->load->helper('download');
        $rows = $this->Pegawai_model->export_data();

        $csv = "NIP Lama,NIP Baru,Nama,Kode Satker,Jabatan,Status\n";
        foreach ($rows as $r) {
            $csv .= implode(',', array_map(function ($v) {
                return '"' . str_replace('"', '""', $v ?? '') . '"';
            }, $r)) . "\n";
        }

        force_download('pegawai_' . date('Ymd') . '.csv', $csv);
    }

    // ── DOWNLOAD TEMPLATE ─────────────────────────────────

    public function template()
    {
        $this->load->helper('download');
        $csv  = "NIP Lama,NIP Baru,Nama,Kode Satker,Jabatan,Status\n";
        $csv .= "340012026,196701181989011001,Contoh Nama,1400,Statistisi Ahli Muda BPS Provinsi,PNS\n";
        force_download('template_pegawai.csv', $csv);
    }

    // ── IMPORT CSV / EXCEL ────────────────────────────────

    public function import()
    {
        $upload_path = FCPATH . 'uploads/temp/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }

        $this->upload->initialize([
            'upload_path'   => $upload_path,
            'allowed_types' => 'xlsx|xls|csv',
            'max_size'      => 5120,
        ]);

        if (!$this->upload->do_upload('file_excel')) {
            $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
            redirect('master_pegawai');
        }

        $file = $this->upload->data('full_path');
        $ext  = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $rows = [];

        if ($ext === 'csv') {
            $rows = $this->_parse_csv($file);
        } else {
            $rows = $this->_parse_excel($file, $ext);
        }

        @unlink($file);

        if ($rows === false) {
            // error sudah di-set di helper method
            redirect('master_pegawai');
        }

        if (empty($rows)) {
            $this->session->set_flashdata('error', 'Tidak ada data yang dapat diimport. Pastikan file tidak kosong dan formatnya sesuai template.');
            redirect('master_pegawai');
        }

        $inserted = $this->Pegawai_model->bulk_insert($rows);
        $this->session->set_flashdata('success', $inserted . ' data pegawai berhasil diimport.');
        redirect('master_pegawai');
    }

    // ── HELPER: parse CSV ─────────────────────────────────

    private function _parse_csv($file)
    {
        $rows = [];
        if (($h = fopen($file, 'r')) === false) {
            $this->session->set_flashdata('error', 'Gagal membuka file CSV.');
            return false;
        }

        // Deteksi dan buang BOM UTF-8 jika ada
        $bom = fread($h, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($h);
        }

        fgetcsv($h); // skip baris header

        while (($line = fgetcsv($h)) !== false) {
            if (count($line) < 3 || empty(trim($line[2]))) continue; // skip baris kosong
            $rows[] = $this->_map_row(
                trim($line[0] ?? ''),
                trim($line[1] ?? ''),
                trim($line[2] ?? ''),
                trim($line[3] ?? '1400'),
                trim($line[4] ?? ''),
                trim($line[5] ?? 'PNS')
            );
        }
        fclose($h);
        return $rows;
    }

    // ── HELPER: parse XLSX / XLS ──────────────────────────

    private function _parse_excel($file, $ext)
    {
        // Cari autoload PhpSpreadsheet (Composer)
        $autoloads = [
            FCPATH . 'vendor/autoload.php',
            APPPATH . '../vendor/autoload.php',
            dirname(APPPATH) . '/vendor/autoload.php',
        ];
        $loaded = false;
        foreach ($autoloads as $al) {
            if (file_exists($al)) {
                require_once $al;
                $loaded = true;
                break;
            }
        }

        if (!$loaded || !class_exists('\PhpOffice\PhpSpreadsheet\IOFactory')) {
            $this->session->set_flashdata('error',
                'PhpSpreadsheet belum terinstall. Jalankan: <code>composer require phpoffice/phpspreadsheet</code> di root project, atau gunakan format CSV.');
            return false;
        }

        try {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file);
            $sheet       = $spreadsheet->getActiveSheet();
            $data        = $sheet->toArray(null, true, true, false);
        } catch (\Exception $e) {
            $this->session->set_flashdata('error', 'Gagal membaca file Excel: ' . $e->getMessage());
            return false;
        }

        $rows = [];
        foreach ($data as $i => $line) {
            if ($i === 0) continue; // skip header (baris pertama, index 0)
            if (empty(trim((string)($line[2] ?? '')))) continue; // skip jika nama kosong

            $rows[] = $this->_map_row(
                trim((string)($line[0] ?? '')),
                trim((string)($line[1] ?? '')),
                trim((string)($line[2] ?? '')),
                trim((string)($line[3] ?? '1400')),
                trim((string)($line[4] ?? '')),
                trim((string)($line[5] ?? 'PNS'))
            );
        }
        return $rows;
    }

    // ── HELPER: normalisasi satu baris ke array DB ────────

    private function _map_row($nip_lama, $nip_baru, $nama, $kode_satker, $jabatan, $status)
    {
        if (!in_array($status, self::STATUS_LIST, true)) {
            $status = 'PNS';
        }
        return [
            'nip_lama'    => $nip_lama,
            'nip_baru'    => $nip_baru,
            'nama'        => $nama,
            'kode_satker' => $kode_satker ?: '1400',
            'jabatan'     => $jabatan,
            'status'      => $status,
        ];
    }
}