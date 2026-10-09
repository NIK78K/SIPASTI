<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Inventory extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Inventory_model');
        $this->load->library('pagination');
    }

    /* =======================
       INDEX
    ======================== */
public function index()
{
    $limit = 20;
    $page  = (int) ($this->input->get('page') ?? 1);
    if ($page < 1) $page = 1;

    $offset = ($page - 1) * $limit;

    // ================= FILTER =================
    $filters = [
        'keyword'       => $this->input->get('keyword'),
        'nup'           => $this->input->get('nup'),
        'kondisi'       => $this->input->get('kondisi'),
        'nama_barang'   => $this->input->get('nama_barang'),
        'kategori_aset' => $this->input->get('kategori_aset'), // ✅ TAMBAHAN BARU
        'ram_exact'     => $this->input->get('ram'),
        'storage_type'  => $this->input->get('storage_type'),
    ];

    // ================= DATA UTAMA =================
    $total     = $this->Inventory_model->total_rows($filters);
    $perangkat = $this->Inventory_model->get_paginated($limit, $offset, $filters);

    // ================= PAGINATION =================
    $total_pages = ceil($total / $limit);
    $pages = range(
        max(1, $page - 1),
        min($total_pages, $page + 1)
    );

    // ================= INSIGHT MINI =================
    $insight = [];
    $insight[] = "🔍 Menampilkan <b>{$total}</b> data";

    if (!empty($filters['keyword'])) {
        $insight[] = "📦 Pencarian: <b>".htmlspecialchars($filters['keyword'])."</b>";
    }
    if (!empty($filters['nup'])) {
        $insight[] = "🔢 NUP: <b>".htmlspecialchars($filters['nup'])."</b>";
    }
    if (!empty($filters['kondisi'])) {
        $insight[] = "🟡 Kondisi: <b>{$filters['kondisi']}</b>";
    }
    if (!empty($filters['nama_barang'])) {
        $insight[] = "💻 Tipe: <b>{$filters['nama_barang']}</b>";
    }
    if (!empty($filters['kategori_aset'])) {  // ✅ TAMBAHAN BARU
        $insight[] = "🏷️ Kategori: <b>{$filters['kategori_aset']}</b>";
    }
    if (!empty($filters['ram_exact'])) {
        $insight[] = "💾 RAM: <b>{$filters['ram_exact']} GB</b>";
    }
    if (!empty($filters['storage_type'])) {
        $insight[] = "💿 Storage: <b>{$filters['storage_type']}</b>";
    }

    // ================= KIRIM KE VIEW =================
    $data = [
        'perangkat'          => $perangkat,
        'pages'              => $pages,
        'current_page'       => $page,
        'total_pages'        => $total_pages,
        'from'               => $offset + 1,
        'to'                 => min($offset + $limit, $total),
        'total'              => $total,
        'baik'               => $this->Inventory_model->count_kondisi('Baik'),
        'rusak_ringan'       => $this->Inventory_model->count_kondisi('Rusak Ringan'),
        'rusak_berat'        => $this->Inventory_model->count_kondisi('Rusak Berat'),
        'insight'            => implode(' &nbsp; | &nbsp; ', $insight),
        'unique_nama_barang' => $this->Inventory_model->get_unique_nama_barang(),
    ];

    $this->load->view('layout/header');
    $this->load->view('layout/sidebar');
    $this->load->view('inventory/index', $data);
    $this->load->view('layout/footer');
}

/**
 * Generate PDF Berita Acara Penghapusan
 * Tambahkan method ini di dalam class Inventory extends CI_Controller
 */
public function generate_pdf_penghapusan($nomor_sk = null)
{
    // ✅ MATIKAN SEMUA ERROR OUTPUT
    error_reporting(0);
    ini_set('display_errors', 0);
    
    // ✅ PRIORITAS 1: Ambil dari query string (GET)
    if (!$nomor_sk) {
        $nomor_sk = $this->input->get('sk');
    }
    
    if (!$nomor_sk) {
        show_error('Nomor SK tidak ditemukan', 404);
        return;
    }
    
    // ✅ DECODE BASE64 jika masih encoded
    if (base64_encode(base64_decode($nomor_sk, true)) === $nomor_sk) {
        $nomor_sk = base64_decode(strtr($nomor_sk, '-_', '+/'));
    }

    
    log_message('debug', '========== PDF GENERATION START ==========');
    log_message('debug', 'Nomor SK setelah decode: ' . $nomor_sk);
    
    // Query database
    $this->db->select('*');
    $this->db->from('perangkat_dihapus');
    $this->db->where('transaksi_sakti', $nomor_sk);
    $this->db->order_by('nama_barang', 'ASC');
    
    $perangkat_list = $this->db->get()->result_array();
    
    if (empty($perangkat_list)) {
        show_error('Data tidak ditemukan untuk SK: ' . htmlspecialchars($nomor_sk), 404);
        return;
    }
    
    // Persiapan tanggal
    $tanggal_hapus_obj = new DateTime($perangkat_list[0]['tanggal_hapus'] ?? 'now');
    
    $hari_indonesia = [
        'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
    ];
    
    $bulan_indonesia = [
        'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
        'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
        'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
        'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
    ];
    
    $hari = $hari_indonesia[$tanggal_hapus_obj->format('l')];
    $tanggal = $tanggal_hapus_obj->format('d');
    $bulan = $bulan_indonesia[$tanggal_hapus_obj->format('F')];
    $tahun = $tanggal_hapus_obj->format('Y');
    $tanggal_lengkap = $tanggal . ' ' . $bulan . ' ' . $tahun;
    $tanggal_ttd = $tanggal_hapus_obj->format('d') . ' ' . $bulan . ' ' . $tahun;
    
    $data = [
        'pengajuan' => [
            'nomor_sk' => $nomor_sk,
            'approved_by' => $perangkat_list[0]['dihapus_oleh'] ?? 'Administrator',
            'tanggal_approve' => $perangkat_list[0]['tanggal_hapus'] ?? date('Y-m-d')
        ],
        'perangkat' => $perangkat_list,
        'hari' => $hari,
        'tanggal_lengkap' => $tanggal_lengkap,
        'tanggal_ttd' => $tanggal_ttd
    ];
    
    require_once APPPATH . '../vendor/autoload.php';
    
    $options = new \Dompdf\Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $options->set('defaultFont', 'Times New Roman');
    
    $dompdf = new \Dompdf\Dompdf($options);
    
    $html = $this->load->view('inventory/pdf', $data, true);
    
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    
    $filename = 'BA_Penghapusan_' . str_replace(['/', ' ', ':', '\\'], '_', $nomor_sk) . '.pdf';
    
    // ✅ CLEAR OUTPUT BUFFER
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    $dompdf->stream($filename, ['Attachment' => 0]);
    
    exit; // ✅ PENTING: Stop execution
}

public function scan()
{
    $code = $this->input->post('code');
    $code = trim($code);
    $code = preg_replace('/\s+/', '', $code);

    if ($code === '') {
        echo json_encode(['status' => 'error']);
        return;
    }

    $this->db->select('*');
    $this->db->from('perangkat');
    $this->db->where("REPLACE(REPLACE(barcode, ' ', ''), '\n', '') =", $code, false);
    $data = $this->db->get()->row_array();

    if (!$data) {
        echo json_encode([
            'status' => 'not_found',
            'debug'  => [
                'qr' => $code,
                'db_example' => $this->db->select('barcode')->limit(1)->get('perangkat')->row_array()
            ]
        ]);
        return;
    }

    echo json_encode([
        'status' => 'success',
        'data'   => $data
    ]);
}

public function detail_ajax($id)
{
    $perangkat = $this->Inventory_model->getById($id);
    if (!$perangkat) {
        show_error('Data tidak ditemukan', 404);
    }

    $spesifikasi = $this->Inventory_model->getSpesifikasi($id);

    $data = [
        'p' => $perangkat,
        'spesifikasi' => $spesifikasi
    ];

    $this->load->view('inventory/detail', $data);
}

public function scan_camera()
{
    $this->load->view('layout/header');

    if ($this->session->userdata('role') === 'teknisi') {
        $this->load->view('layout/sidebar_teknisi');
    } else {
        $this->load->view('layout/sidebar');
    }

    $this->load->view('inventory/scan_camera');
    $this->load->view('layout/footer');
}

public function barcode_generator()
{
    $this->load->view('layout/header');
    $this->load->view('layout/sidebar');
    $this->load->view('inventory/barcode_generator');
    $this->load->view('layout/footer');
}

public function qrcode_test()
{
    $data['barcode'] = '3100101007|1';
    $this->load->view('layout/header');
    $this->load->view('layout/sidebar');
    $this->load->view('inventory/qrcode_generator', $data);
    $this->load->view('layout/footer');
}

    /* =======================
       CREATE AJAX (MODAL)
    ======================== */
    public function create_ajax()
    {
        $this->load->view('inventory/create');
    }

    /* =======================
       STORE (AJAX + NORMAL)
    ======================== */
public function store()
{
    header('Content-Type: application/json');
    
    log_message('debug', '========== STORE METHOD CALLED ==========');
    log_message('debug', 'POST data: ' . print_r($this->input->post(), true));
    log_message('debug', 'FILES data: ' . print_r($_FILES, true));
    
    // Validasi data wajib
    $required = ['jenis_bmn', 'kode_satker', 'nama_satker', 'kode_barang', 'nup'];
    foreach ($required as $field) {
        if (empty($this->input->post($field))) {
            log_message('error', "Missing required field: {$field}");
            echo json_encode([
                'status' => 'error',
                'message' => "Field {$field} wajib diisi",
                'field' => $field
            ]);
            return;
        }
    }
    
    $this->db->db_debug = FALSE;
    
    $data = $this->input->post();
    
    // Validasi nama_barang dan lokasi
    if (empty($data['nama_barang'])) {
        log_message('error', 'Nama Barang is empty');
        echo json_encode([
            'status' => 'error',
            'message' => 'Nama Barang wajib dipilih'
        ]);
        return;
    }
    
    if (empty($data['lokasi'])) {
        log_message('error', 'Lokasi is empty');
        echo json_encode([
            'status' => 'error',
            'message' => 'Lokasi wajib dipilih'
        ]);
        return;
    }
    
    // ✅ UPLOAD FOTO (OPSIONAL)
    $uploaded_foto = NULL;
    if (!empty($_FILES['foto']['name'])) {
        log_message('debug', 'Foto uploaded, processing...');
        
        $uploaded_foto = $this->Inventory_model->upload_foto('foto');
        
        if ($uploaded_foto === FALSE) {
            $upload_error = $this->upload->display_errors('', '');
            log_message('error', 'Upload foto failed: ' . $upload_error);
            
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal upload foto: ' . $upload_error
            ]);
            return;
        }
        
        log_message('debug', 'Foto uploaded successfully: ' . $uploaded_foto);
        $data['foto'] = $uploaded_foto;
    }
    
    // Hapus field yang tidak perlu
    unset($data['nama_barang_select']);
    unset($data['lokasi_select']);
    unset($data['ruang']);
    
    // Set timestamp
    $data['created_at'] = date('Y-m-d H:i:s');
    $data['updated_at'] = date('Y-m-d H:i:s');
    
    log_message('debug', 'Data to insert: ' . print_r($data, true));
    
    // Insert
    $insert_result = $this->db->insert('perangkat', $data);
    $error = $this->db->error();
    
    log_message('debug', 'Insert result: ' . ($insert_result ? 'TRUE' : 'FALSE'));
    log_message('debug', 'DB Error code: ' . $error['code']);
    
    if ($error['code'] != 0) {
        // Hapus foto jika insert gagal
        if ($uploaded_foto) {
            $this->Inventory_model->delete_foto($uploaded_foto);
        }
        
        log_message('error', 'DB Error: ' . print_r($error, true));
        
        if ($error['code'] == 1062) {
            echo json_encode([
                'status' => 'duplicate',
                'message' => 'Kode Barang dan NUP sudah terdaftar.',
                'error_code' => $error['code']
            ]);
            return;
        }
        
        echo json_encode([
            'status' => 'error',
            'message' => 'Database Error: ' . $error['message'],
            'error_code' => $error['code']
        ]);
        return;
    }
    
    $insert_id = $this->db->insert_id();
    log_message('debug', 'Insert successful! ID: ' . $insert_id);
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Data berhasil disimpan.',
        'id' => $insert_id
    ]);
}


   /* =======================
   EDIT - Updated with Spesifikasi
======================== */
public function edit($id)
{
    $item = $this->db->get_where('perangkat', ['id' => $id])->row_array();
    if (!$item) show_404();

    // Ambil data spesifikasi jika ada
    $spesifikasi = $this->db->get_where('perangkat_spesifikasi', ['perangkat_id' => $id])->row_array();
    if (!$spesifikasi) {
        $spesifikasi = [
            'kategori'     => '',
            'processor'    => '',
            'ram_gb'       => '',
            'storage_gb'   => '',
            'storage_type' => '',
            'gpu'          => '',
            'os'           => ''
        ];
    }

    // ✅ Ambil master lokasi untuk dropdown
    $this->db->select('id, kode_lokasi, nama_lokasi');
    $this->db->from('master_lokasi');
    $this->db->where('status', 'Aktif');
    $this->db->order_by('nama_lokasi', 'ASC');
    $master_lokasi = $this->db->get()->result_array();

    $this->load->view('layout/header');
    $this->load->view('layout/sidebar');
    $this->load->view('inventory/edit', [
        'item'          => $item,
        'spesifikasi'   => $spesifikasi,
        'master_lokasi' => $master_lokasi, // ✅ TAMBAHAN
    ]);
    $this->load->view('layout/footer');
}

/* =======================
   UPDATE - Updated with Spesifikasi
======================== */
public function update($id)
{
    $this->db->trans_start();
 
    $item = $this->db->get_where('perangkat', ['id' => $id])->row_array();
    if (!$item) show_404();
 
    // Simpan data LAMA dulu (untuk di-diff nanti)
    $data_lama = $item;
 
    $post = $this->input->post();
 
    // Hapus data yang tidak boleh diupdate
    unset(
        $post['jenis_bmn'],
        $post['kode_satker'],
        $post['nama_satker'],
        $post['kode_barang'],
        $post['nup']
    );
 
    // ✅ HANDLE UPLOAD FOTO
    $uploaded_foto = NULL;
    if (!empty($_FILES['foto']['name'])) {
        $uploaded_foto = $this->Inventory_model->upload_foto('foto');
 
        if ($uploaded_foto !== FALSE) {
            if (!empty($item['foto'])) {
                $this->Inventory_model->delete_foto($item['foto']);
            }
            $post['foto'] = $uploaded_foto;
        } else {
            $upload_error = $this->upload->display_errors('', '');
            $this->session->set_flashdata('error', 'Gagal upload foto: ' . $upload_error);
            redirect('inventory/edit/' . $id);
            return;
        }
    }
 
    // ✅ HANDLE HAPUS FOTO
    if (!empty($post['hapus_foto']) && !empty($item['foto'])) {
        $this->Inventory_model->delete_foto($item['foto']);
        $post['foto'] = NULL;
    }
    unset($post['hapus_foto']);
 
    // Pisahkan data spesifikasi
    $spec_data = [
        'kategori'     => $post['spec_kategori']    ?? '',
        'processor'    => $post['spec_processor']   ?? '',
        'ram_gb'       => !empty($post['spec_ram_gb'])     ? (int)$post['spec_ram_gb']     : null,
        'storage_gb'   => !empty($post['spec_storage_gb']) ? (int)$post['spec_storage_gb'] : null,
        'storage_type' => $post['spec_storage_type'] ?? '',
        'gpu'          => $post['spec_gpu']          ?? '',
        'os'           => $post['spec_os']           ?? '',
    ];
 
    unset(
        $post['spec_kategori'],
        $post['spec_processor'],
        $post['spec_ram_gb'],
        $post['spec_storage_gb'],
        $post['spec_storage_type'],
        $post['spec_gpu'],
        $post['spec_os']
    );
 
    // ✅ REKAM HISTORY SEBELUM UPDATE
    $diubah_oleh = $this->session->userdata('name') ?? 'Admin';
 
    // Ambil spesifikasi lama untuk di-diff
    $spek_lama = $this->db->get_where('perangkat_spesifikasi', ['perangkat_id' => $id])->row_array() ?? [];
 
    // Update data perangkat
    $post['updated_at'] = date('Y-m-d H:i:s');
    $this->db->where('id', $id)->update('perangkat', $post);
 
    // ✅ REKAM HISTORY PERANGKAT
    $this->Inventory_model->rekam_history($id, $data_lama, $post, $diubah_oleh);
 
    // Update atau insert spesifikasi
    $has_spec_data = false;
    foreach ($spec_data as $value) {
        if (!empty($value)) { $has_spec_data = true; break; }
    }
 
    if ($has_spec_data) {
        $existing_spec = $this->db->get_where('perangkat_spesifikasi', ['perangkat_id' => $id])->row();
 
        if ($existing_spec) {
            // ✅ REKAM HISTORY SPESIFIKASI
            $this->Inventory_model->rekam_history_spek($id, $spek_lama, $spec_data, $diubah_oleh);
            $this->db->where('perangkat_id', $id)->update('perangkat_spesifikasi', $spec_data);
        } else {
            $spec_data['perangkat_id'] = $id;
            $spec_data['created_at']   = date('Y-m-d H:i:s');
            $this->db->insert('perangkat_spesifikasi', $spec_data);
        }
    } else {
        $this->db->delete('perangkat_spesifikasi', ['perangkat_id' => $id]);
    }
 
    $this->db->trans_complete();
 
    $this->session->set_flashdata('success', 'Data perangkat berhasil diperbarui');
    redirect('inventory');
}

public function history_ajax($id)
{
    $perangkat   = $this->Inventory_model->getById($id);
    $history     = $this->Inventory_model->get_history($id);
 
    $data = [
        'p'       => $perangkat,
        'history' => $history,
    ];
 
    $this->load->view('inventory/history', $data);
}

public function update_data($id, $data)
{
    $data['updated_at'] = date('Y-m-d H:i:s');
    return $this->db->where('id', $id)->update($this->table, $data);
}

public function import_process()
{
    if (!isset($_FILES['file_excel'])) {
        $this->session->set_flashdata('error', 'File tidak ditemukan');
        redirect('inventory/import');
        return;
    }

    $file = $_FILES['file_excel']['tmp_name'];
    
    try {
        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        // Hapus header
        array_shift($data);
        
        // Hapus baris instruksi jika ada
        if (!empty($data) && isset($data[0][0]) && strpos($data[0][0], 'ISI DATA') !== false) {
            array_shift($data);
        }

        $success = 0;
        $failed = 0;
        $errors = [];

        $this->db->trans_start();

        foreach ($data as $index => $row) {
            $rowNum = $index + 2;

            if (empty(array_filter($row))) continue;

// DATA PERANGKAT
$perangkat = [
    'jenis_bmn'         => trim($row[0] ?? ''),
    'kode_satker'       => trim($row[1] ?? ''),
    'nama_satker'       => trim($row[2] ?? ''),
    'kode_barang'       => trim($row[3] ?? ''),
    'nup'               => trim($row[4] ?? ''),
    'nama_barang'       => trim($row[5] ?? ''),
    'merk'              => trim($row[6] ?? ''),
    'kondisi'           => trim($row[7] ?? 'Baik'),
    'henti_guna'        => trim($row[8] ?? 'Tidak'),
    'usul_hapus'        => trim($row[9] ?? 'Tidak'),
    'tanggal_perolehan' => $this->parseDate($row[10] ?? ''),
    'nilai_perolehan'   => $this->parseNumber($row[11] ?? 0),
    'lokasi'            => trim($row[12] ?? ''),
    'serial_number'     => trim($row[13] ?? ''),
    'mac_address'       => trim($row[14] ?? ''),
    'pengguna'          => trim($row[15] ?? ''),
    'ruangan'           => trim($row[16] ?? ''),
    'keterangan'        => trim($row[17] ?? ''),
    'created_at'        => date('Y-m-d H:i:s'),
    'updated_at'        => date('Y-m-d H:i:s')
];

            // Validasi
            if (empty($perangkat['kode_barang']) || empty($perangkat['nup'])) {
                $errors[] = "Baris {$rowNum}: Kode Barang dan NUP wajib diisi";
                $failed++;
                continue;
            }

            // Cek duplikasi
            $exists = $this->db->get_where('perangkat', [
                'kode_barang' => $perangkat['kode_barang'],
                'nup' => $perangkat['nup']
            ])->row();

            if ($exists) {
                $errors[] = "Baris {$rowNum}: Kode Barang {$perangkat['kode_barang']} - NUP {$perangkat['nup']} sudah ada";
                $failed++;
                continue;
            }

            // Insert perangkat
            $this->db->insert('perangkat', $perangkat);
            $perangkat_id = $this->db->insert_id();

            // DATA SPESIFIKASI - Index 20 adalah KATEGORI
            $kategori = trim($row[20] ?? '');
            
            if (!empty($kategori)) {
                $spesifikasi = [
                    'perangkat_id'  => $perangkat_id,
                    'kategori'      => $kategori,
                    'processor'     => trim($row[21] ?? ''),
                    'ram_gb'        => (int)($row[22] ?? 0),
                    'storage_gb'    => (int)($row[23] ?? 0),
                    'storage_type'  => trim($row[24] ?? ''),
                    'gpu'           => trim($row[25] ?? ''),
                    'os'            => trim($row[26] ?? ''),
                    'created_at'    => date('Y-m-d H:i:s')
                ];

                $this->db->insert('perangkat_spesifikasi', $spesifikasi);
            }

            $success++;
        }

        $this->db->trans_complete();

        if ($success > 0) {
            $msg = "✅ Berhasil import {$success} data";
            if ($failed > 0) {
                $msg .= " | ❌ Gagal {$failed} data";
            }
            $this->session->set_flashdata('success', $msg);
            
            if (!empty($errors)) {
                $this->session->set_flashdata('errors', $errors);
            }
        } else {
            $this->session->set_flashdata('error', 'Tidak ada data yang berhasil diimport');
            $this->session->set_flashdata('errors', $errors);
        }

    } catch (Exception $e) {
        $this->session->set_flashdata('error', 'Error: ' . $e->getMessage());
    }

    redirect('inventory/import');
}

private function parseDate($date)
{
    if (empty($date)) return null;
    
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        return $date;
    }
    
    if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $date)) {
        $parts = explode('/', $date);
        return sprintf('%04d-%02d-%02d', $parts[2], $parts[1], $parts[0]);
    }
    
    if (is_numeric($date)) {
        $unix = ($date - 25569) * 86400;
        return date('Y-m-d', $unix);
    }
    
    return null;
}

private function parseNumber($value)
{
    if (empty($value)) return 0;
    $value = str_replace(['.', ',', ' ', 'Rp'], '', $value);
    return (float)$value;
}
/* =======================
   DOWNLOAD TEMPLATE - SESUAI DATABASE ANDA
======================= */
public function download_template()
{
    $spreadsheet = new Spreadsheet();
    
    // ========================================
    // SHEET 1: DATA INPUT (Sheet Utama)
    // ========================================
    $sheet1 = $spreadsheet->getActiveSheet();
    $sheet1->setTitle('DATA INPUT');
    
    // Header dengan styling
   $headers = [
    'A1' => 'JENIS BMN',
    'B1' => 'KODE SATKER',
    'C1' => 'NAMA SATKER',
    'D1' => 'KODE BARANG (*)',
    'E1' => 'NUP (*)',
    'F1' => 'NAMA BARANG',
    'G1' => 'MERK',
    'H1' => 'KONDISI',
    'I1' => 'HENTI GUNA',
    'J1' => 'USUL HAPUS',
    'K1' => 'TANGGAL PEROLEHAN',
    'L1' => 'NILAI PEROLEHAN',
    'M1' => 'LOKASI',
    // 'N1' => 'RUANG',  // ❌ HAPUS BARIS INI
    'N1' => 'SERIAL NUMBER',     // ✅ Kolom bergeser
    'O1' => 'MAC ADDRESS',       // ✅ Kolom bergeser
    'P1' => 'PENGGUNA',          // ✅ Kolom bergeser
    'Q1' => 'RUANGAN',           // ✅ Kolom bergeser
    'R1' => 'KETERANGAN',        // ✅ Kolom bergeser
    'S1' => '=== SPESIFIKASI (Opsional) ===',  // ✅ Kolom bergeser
    'T1' => 'KATEGORI',          // ✅ Kolom bergeser
    'U1' => 'PROCESSOR',         // ✅ Kolom bergeser
    'V1' => 'RAM (GB)',          // ✅ Kolom bergeser
    'W1' => 'STORAGE (GB)',      // ✅ Kolom bergeser
    'X1' => 'STORAGE TYPE',      // ✅ Kolom bergeser
    'Y1' => 'GPU',               // ✅ Kolom bergeser
    'Z1' => 'OS'                 // ✅ Kolom bergeser
];
    
    foreach ($headers as $cell => $value) {
        $sheet1->setCellValue($cell, $value);
        $sheet1->getStyle($cell)->getFont()->setBold(true);
        $sheet1->getStyle($cell)->getFont()->setSize(11);
        $sheet1->getStyle($cell)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF4472C4');
        $sheet1->getStyle($cell)->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet1->getStyle($cell)->getAlignment()->setHorizontal('center');
        $sheet1->getStyle($cell)->getAlignment()->setVertical('center');
    }
    
    $sheet1->getRowDimension(1)->setRowHeight(30);
    
    foreach (range('A', 'AA') as $col) {
        $sheet1->getColumnDimension($col)->setAutoSize(true);
    }
    
    $sheet1->freezePane('A2');
    
    $sheet1->setCellValue('A2', '⬇️ ISI DATA MULAI DARI BARIS INI ⬇️');
    $sheet1->mergeCells('A2:AA2');
    $sheet1->getStyle('A2')->getFont()->setBold(true);
    $sheet1->getStyle('A2')->getFont()->setSize(12);
    $sheet1->getStyle('A2')->getAlignment()->setHorizontal('center');
    $sheet1->getStyle('A2')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFFFFF00');
    
    // ========================================
    // SHEET 2: CONTOH DATA
    // ========================================
    $sheet2 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'CONTOH DATA');
    $spreadsheet->addSheet($sheet2, 1);
    
    foreach ($headers as $cell => $value) {
        $sheet2->setCellValue($cell, $value);
        $sheet2->getStyle($cell)->getFont()->setBold(true);
        $sheet2->getStyle($cell)->getFont()->setSize(11);
        $sheet2->getStyle($cell)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF70AD47');
        $sheet2->getStyle($cell)->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet2->getStyle($cell)->getAlignment()->setHorizontal('center');
    }
    
    $sheet2->getRowDimension(1)->setRowHeight(30);
    
    // ===== DATA CONTOH 1 - BERWUJUD =====
    $example1 = [
        'MESIN PERALATAN KHUSUS TIK',
        '054010900428072000KD',
        'BPS PROV RIAU',
        '3100101007',
        '1',
        'PC Workstation',
        'HP Z4 G4 Workstation',
        'Baik',
        'Tidak',
        'Tidak',
        '2018-12-31',
        '52294822',
        '105 - Ruang Staf IPDS',
        '105 - Ruang Staf IPDS',
        '4CE8402QMK',
        '10-E7-C6-3F-AC-E5',
        'Afdi Rizal',
        'Ruang IPDS',
        'Perangkat dalam kondisi normal',
        '',                    // T (19) - SEPARATOR
        'Berwujud',           // U (20) - KATEGORI ✅
        'Intel Xeon W-2123',
        '16',
        '512',
        'SSD',
        'NVIDIA Quadro P400',
        'Windows 10 Pro'
    ];
    
    $col = 'A';
    foreach ($example1 as $val) {
        $sheet2->setCellValue($col.'2', $val);
        $col++;
    }
    
    // ===== DATA CONTOH 2 - BERWUJUD =====
    $example2 = [
        'MESIN PERALATAN KHUSUS TIK',
        '054010900428072000KD',
        'BPS PROV RIAU',
        '3100101008',
        '2',
        'Laptop',
        'Dell Latitude 7420',
        'Baik',
        'Tidak',
        'Tidak',
        '2021-06-15',
        '18500000',
        '201 - Ruang Kepala BPS',
        'Lantai 2',
        'ABC123XYZ',
        'A1-B2-C3-D4-E5-F6',
        'John Doe',
        'Ruang Kepala',
        '',
        '',
        'Berwujud',           // U (20) - KATEGORI ✅
        'Intel Core i7-1165G7',
        '16',
        '512',
        'NVMe',
        'Intel Iris Xe',
        'Windows 11 Pro'
    ];
    
    $col = 'A';
    foreach ($example2 as $val) {
        $sheet2->setCellValue($col.'3', $val);
        $col++;
    }
    
    // ===== DATA CONTOH 3 - TIDAK BERWUJUD =====
    $example3 = [
        'MESIN PERALATAN KHUSUS TIK',
        '054010900428072000KD',
        'BPS PROV RIAU',
        '3100101009',
        '3',
        'PC Desktop',
        'HP ProDesk 400 G4',
        'Rusak Berat',
        'Ya',
        'Ya',
        '2017-03-20',
        '8500000',
        '102 - Ruang Administrasi',
        'Lantai 1',
        'DEF456GHI',
        '12-34-56-78-90-AB',
        'Jane Smith',
        'Ruang Admin',
        'Motherboard rusak, tidak dapat diperbaiki',
        '',
        'Tidak Berwujud',     // U (20) - KATEGORI ✅
        'Intel Core i5-7500',
        '8',
        '256',
        'HDD',
        'Intel HD Graphics 630',
        'Windows 10 Pro'
    ];
    
    $col = 'A';
    foreach ($example3 as $val) {
        $sheet2->setCellValue($col.'4', $val);
        $col++;
    }
    
    $sheet2->getStyle('A2:AA4')->getAlignment()->setVertical('center');
    $sheet2->getStyle('A2:AA2')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFE2EFDA');
    $sheet2->getStyle('A3:AA3')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFDEEAF6');
    $sheet2->getStyle('A4:AA4')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFFCE4D6');
    
    foreach (range('A', 'AA') as $col) {
        $sheet2->getColumnDimension($col)->setAutoSize(true);
    }
    
    // ========================================
    // SHEET 3: PETUNJUK PENGISIAN
    // ========================================
    $sheet3 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'PETUNJUK');
    $spreadsheet->addSheet($sheet3, 2);
    
    $sheet3->setCellValue('A1', '📋 PETUNJUK IMPORT DATA BMN');
    $sheet3->mergeCells('A1:D1');
    $sheet3->getStyle('A1')->getFont()->setBold(true);
    $sheet3->getStyle('A1')->getFont()->setSize(16);
    $sheet3->getStyle('A1')->getAlignment()->setHorizontal('center');
    $sheet3->getRowDimension(1)->setRowHeight(30);
    
    $row = 3;
    $sheet3->setCellValue('A'.$row, '⚠️ INSTRUKSI UMUM:');
    $sheet3->getStyle('A'.$row)->getFont()->setBold(true);
    $sheet3->getStyle('A'.$row)->getFont()->setSize(12);
    $sheet3->getStyle('A'.$row)->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFF4B084');
    
    $row++;
    $instructions = [
        '1. Gunakan sheet "DATA INPUT" untuk mengisi data',
        '2. Lihat sheet "CONTOH DATA" sebagai referensi',
        '3. Jangan ubah nama kolom di baris header',
        '4. Kolom bertanda (*) wajib diisi',
        '5. Hapus baris instruksi kuning sebelum import',
        '6. Data duplikat (Kode Barang + NUP sama) akan dilewati',
        '7. Maksimal 1000 baris per file'
    ];
    
    foreach ($instructions as $inst) {
        $sheet3->setCellValue('A'.$row, $inst);
        $sheet3->getStyle('A'.$row)->getAlignment()->setWrapText(true);
        $row++;
    }
    
    $row++;
    $sheet3->setCellValue('A'.$row, '✅ FIELD WAJIB DIISI:');
    $sheet3->getStyle('A'.$row)->getFont()->setBold(true);
    $sheet3->getStyle('A'.$row)->getFont()->setSize(12);
    $sheet3->getStyle('A'.$row)->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFC6E0B4');
    
    $row++;
    $sheet3->setCellValue('A'.$row, 'Kolom');
    $sheet3->setCellValue('B'.$row, 'Keterangan');
    $sheet3->getStyle('A'.$row.':B'.$row)->getFont()->setBold(true);
    $sheet3->getStyle('A'.$row.':B'.$row)->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFD9D9D9');
    
    $row++;
    $required_fields = [
        ['KODE BARANG', 'Kode unik barang (wajib)'],
        ['NUP', 'Nomor Urut Pendaftaran (wajib)'],
        ['JENIS BMN', 'Jenis Barang Milik Negara'],
        ['KODE SATKER', 'Kode Satuan Kerja'],
        ['NAMA SATKER', 'Nama Satuan Kerja']
    ];
    
    foreach ($required_fields as $field) {
        $sheet3->setCellValue('A'.$row, $field[0]);
        $sheet3->setCellValue('B'.$row, $field[1]);
        $row++;
    }
    
    $row++;
    $sheet3->setCellValue('A'.$row, '📝 FORMAT DATA:');
    $sheet3->getStyle('A'.$row)->getFont()->setBold(true);
    $sheet3->getStyle('A'.$row)->getFont()->setSize(12);
    $sheet3->getStyle('A'.$row)->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFB4C7E7');
    
    $row++;
    $sheet3->setCellValue('A'.$row, 'Field');
    $sheet3->setCellValue('B'.$row, 'Format/Pilihan');
    $sheet3->setCellValue('C'.$row, 'Contoh');
    $sheet3->getStyle('A'.$row.':C'.$row)->getFont()->setBold(true);
    $sheet3->getStyle('A'.$row.':C'.$row)->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFD9D9D9');
    
    $row++;
    $formats = [
        ['TANGGAL PEROLEHAN', 'YYYY-MM-DD atau DD/MM/YYYY', '2024-12-31 atau 31/12/2024'],
        ['KONDISI', 'Baik / Rusak Ringan / Rusak Berat', 'Baik'],
        ['HENTI GUNA', 'Ya / Tidak', 'Tidak'],
        ['USUL HAPUS', 'Ya / Tidak', 'Tidak'],
        ['NILAI PEROLEHAN', 'Angka tanpa titik/koma', '52294822'],
        ['KATEGORI', 'Berwujud / Tidak Berwujud', 'Berwujud'],
        ['RAM (GB)', 'Angka saja', '16'],
        ['STORAGE (GB)', 'Angka saja', '512'],
        ['STORAGE TYPE', 'SSD / NVMe / HDD', 'SSD']
    ];
    
    foreach ($formats as $format) {
        $sheet3->setCellValue('A'.$row, $format[0]);
        $sheet3->setCellValue('B'.$row, $format[1]);
        $sheet3->setCellValue('C'.$row, $format[2]);
        $sheet3->getStyle('C'.$row)->getFont()->getColor()->setARGB('FF0070C0');
        $row++;
    }
    
    $row++;
    $sheet3->setCellValue('A'.$row, '⚠️ CATATAN PENTING:');
    $sheet3->getStyle('A'.$row)->getFont()->setBold(true);
    $sheet3->getStyle('A'.$row)->getFont()->setSize(12);
    $sheet3->getStyle('A'.$row)->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFFF6B6B');
    $sheet3->getStyle('A'.$row)->getFont()->getColor()->setARGB('FFFFFFFF');
    
    $row++;
    $notes = [
        '• Data spesifikasi (Kategori, Processor, RAM, dll) bersifat OPSIONAL',
        '• Jika tidak ada data spesifikasi, biarkan kolom tersebut kosong',
        '• Kombinasi KODE BARANG + NUP harus unik (tidak boleh duplikat)',
        '• Periksa kembali format tanggal sebelum import',
        '• Pastikan nilai kondisi persis seperti format (huruf besar/kecil)',
        '• Kolom T adalah separator, JANGAN diisi',
        '• Kategori yang valid: Berwujud atau Tidak Berwujud',
        '• Untuk bantuan lebih lanjut, hubungi administrator sistem'
    ];
    
    foreach ($notes as $note) {
        $sheet3->setCellValue('A'.$row, $note);
        $sheet3->getStyle('A'.$row)->getAlignment()->setWrapText(true);
        $row++;
    }
    
    $sheet3->getColumnDimension('A')->setWidth(30);
    $sheet3->getColumnDimension('B')->setWidth(40);
    $sheet3->getColumnDimension('C')->setWidth(30);
    $sheet3->getColumnDimension('D')->setWidth(20);
    
    // ========================================
    // OUTPUT FILE
    // ========================================
    $spreadsheet->setActiveSheetIndex(0);
    
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="Template_Import_BMN_'.date('Ymd_His').'.xlsx"');
    header('Cache-Control: max-age=0');
    
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

public function import()
{
    $this->load->view('layout/header');
    $this->load->view('layout/sidebar');
    $this->load->view('inventory/import');
    $this->load->view('layout/footer');
}

public function export()
{
    $this->load->view('layout/header');
    $this->load->view('layout/sidebar');
    $this->load->view('inventory/export');
    $this->load->view('layout/footer');
}

public function export_process()
{
    $columns = $this->input->post('columns');

    if (empty($columns)) {
        show_error('Tidak ada kolom dipilih');
    }

    $data = $this->Inventory_model->get_all();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $col = 'A';
    foreach ($columns as $field) {
        $sheet->setCellValue($col.'1', strtoupper(str_replace('_',' ',$field)));
        $col++;
    }

    $row = 2;
    foreach ($data as $item) {
        $col = 'A';
        foreach ($columns as $field) {
            $sheet->setCellValue($col.$row, $item[$field] ?? '');
            $col++;
        }
        $row++;
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="inventory.xlsx"');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

    /* =======================
       DELETE
    ======================== */
  public function delete($id)
{
    // Ambil data perangkat untuk hapus foto
    $item = $this->db->get_where('perangkat', ['id' => $id])->row_array();
    
    if ($item && !empty($item['foto'])) {
        $this->Inventory_model->delete_foto($item['foto']);
    }
    
    $this->Inventory_model->delete($id);
    redirect('inventory');
}

    // ===============================================
// TAMBAHKAN METHOD INI DI class Inventory
// ===============================================
/**
 * Get data perangkat rusak berat untuk pengajuan penghapusan
 */
public function get_rusak_berat()
{
    header('Content-Type: application/json');
    
    $this->db->select('id, kode_barang, nup, nama_barang, merk, lokasi, kondisi');
    $this->db->from('perangkat');
    $this->db->where('kondisi', 'Rusak Berat');
    $this->db->order_by('nama_barang', 'ASC');
    
    $result = $this->db->get()->result_array();
    
    echo json_encode($result);
}

/**
 * Halaman Pengajuan Penghapusan (VIEW ONLY - localStorage based)
 */
public function pengajuan()
{
    $this->load->view('layout/header');
    $this->load->view('layout/sidebar');
    $this->load->view('inventory/pengajuan');
    $this->load->view('layout/footer');
}

public function approve_pengajuan()
{
    header('Content-Type: application/json');
    
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    if (empty($data['pengajuan_id']) || empty($data['perangkat'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Data pengajuan tidak lengkap'
        ]);
        return;
    }
    
    $nomor_sk = $data['pengajuan_id'];
    $perangkat_list = $data['perangkat'];
    $catatan_admin = $data['catatan_admin'] ?? '';
    $approved_by = $this->session->userdata('name') ?? 'Admin';
    
    // ✅ MATIKAN AUTO ROLLBACK - KITA HANDLE MANUAL
    $this->db->db_debug = FALSE;
    
    $success_count = 0;
    $failed_count = 0;
    $error_details = [];
    
    // ✅ PROSES SATU-SATU TANPA TRANSACTION (AGAR TIDAK ROLLBACK SEMUA)
    foreach ($perangkat_list as $index => $p) {
        
        // ✅ LOG SETIAP PROSES
        log_message('debug', "========== Processing Item #{$index} ==========");
        log_message('debug', 'Perangkat ID: ' . ($p['id'] ?? 'NULL'));
        
        // ✅ AMBIL DATA PERANGKAT
        $perangkat = $this->db->get_where('perangkat', ['id' => $p['id']])->row_array();
        
        if (!$perangkat) {
            $failed_count++;
            $error_msg = "ID {$p['id']} tidak ditemukan di database";
            $error_details[] = $error_msg;
            log_message('error', $error_msg);
            continue;
        }
        
        // ✅ SIAPKAN DATA UNTUK INSERT
        $dataHapus = [
            'jenis_bmn'         => $perangkat['jenis_bmn'] ?? '',
            'kode_satker'       => $perangkat['kode_satker'] ?? '',
            'nama_satker'       => $perangkat['nama_satker'] ?? '',
            'kode_barang'       => $perangkat['kode_barang'] ?? '',
            'nup'               => $perangkat['nup'] ?? '',
            'nama_barang'       => $perangkat['nama_barang'] ?? '',
            'merk'              => $perangkat['merk'] ?? '',
            'kondisi'           => $perangkat['kondisi'] ?? 'Rusak Berat',
            'lokasi'            => $perangkat['lokasi'] ?? '',
            'serial_number'     => $perangkat['serial_number'] ?? '',
            'mac_address'       => $perangkat['mac_address'] ?? '',
            'pengguna'          => $perangkat['pengguna'] ?? '',
            'ruangan'           => $perangkat['ruangan'] ?? '',
            'nilai_perolehan'   => $perangkat['nilai_perolehan'] ?? 0,
            'keterangan'        => !empty($catatan_admin) ? $catatan_admin : ($perangkat['keterangan'] ?? ''),
            'transaksi_sakti'   => $nomor_sk,
            'tanggal_hapus'     => date('Y-m-d H:i:s'),
            'dipindahkan_pada'  => date('Y-m-d H:i:s'),
            'dihapus_oleh'      => $approved_by
        ];
        
        // ✅ LOG DATA YANG AKAN DIINSERT
        log_message('debug', 'Data to insert: ' . json_encode($dataHapus));
        
        // ✅ CEK DUPLIKASI (Kode Barang + NUP)
        $exists = $this->db->get_where('perangkat_dihapus', [
            'kode_barang' => $dataHapus['kode_barang'],
            'nup' => $dataHapus['nup']
        ])->row();
        
        if ($exists) {
            $failed_count++;
            $error_msg = "Kode {$dataHapus['kode_barang']} NUP {$dataHapus['nup']} sudah ada di perangkat_dihapus";
            $error_details[] = $error_msg;
            log_message('warning', $error_msg);
            continue; // ✅ SKIP, TAPI LANJUT KE DATA BERIKUTNYA
        }
        
        // ✅ INSERT KE TABEL perangkat_dihapus
        $insert = $this->db->insert('perangkat_dihapus', $dataHapus);
        
        // ✅ CEK ERROR
        $db_error = $this->db->error();
        
        if ($db_error['code'] != 0) {
            $failed_count++;
            $error_msg = "DB Error #{$index}: " . $db_error['message'];
            $error_details[] = $error_msg;
            log_message('error', $error_msg);
            log_message('error', 'Failed SQL: ' . $this->db->last_query());
            continue; // ✅ SKIP, TAPI LANJUT KE DATA BERIKUTNYA
        }
        
        if ($insert) {
            $insert_id = $this->db->insert_id();
            
            // ✅ HAPUS DARI TABEL perangkat
            $this->db->delete('perangkat', ['id' => $p['id']]);
            $this->db->delete('perangkat_spesifikasi', ['perangkat_id' => $p['id']]);
            
            $success_count++;
            log_message('debug', "✅ Success insert ID: {$insert_id} | SK: {$nomor_sk}");
        } else {
            $failed_count++;
            $error_msg = "Insert failed untuk ID {$p['id']} (unknown error)";
            $error_details[] = $error_msg;
            log_message('error', $error_msg);
        }
    }
    
    // ✅ LOG RINGKASAN
    log_message('info', "========== APPROVE SUMMARY ==========");
    log_message('info', "Nomor SK: {$nomor_sk}");
    log_message('info', "Total perangkat: " . count($perangkat_list));
    log_message('info', "Success: {$success_count}");
    log_message('info', "Failed: {$failed_count}");
    log_message('info', "Errors: " . implode(' | ', $error_details));
    
    // ✅ RESPONSE
    if ($success_count > 0) {
        $message = "✅ Berhasil approve {$success_count} dari " . count($perangkat_list) . " perangkat";
        
        if ($failed_count > 0) {
            $message .= " | ❌ Gagal {$failed_count} perangkat";
        }
        
        echo json_encode([
            'status' => 'success',
            'message' => $message,
            'details' => [
                'success' => $success_count,
                'failed' => $failed_count,
                'errors' => $error_details
            ]
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Semua data gagal diproses',
            'details' => [
                'errors' => $error_details
            ]
        ]);
    }
}

public function get_master_barang()
{
    $this->db->select('MIN(id) as id, nama_barang'); // ✅ ambil id terkecil per nama
    $this->db->from('master_barang');
    $this->db->where('status', 'Aktif');
    $this->db->group_by('nama_barang');              // ✅ deduplikasi by nama_barang
    $this->db->order_by('nama_barang', 'ASC');
    $result = $this->db->get()->result_array();

    header('Content-Type: application/json');
    echo json_encode($result);
}

public function get_master_merk($barang_id = null)
{
    $this->db->select('id, nama_merk');
    $this->db->from('master_merk');
    
    if ($barang_id && $barang_id != 'all') {
        $this->db->where('master_barang_id', $barang_id);
    }
    
    $this->db->where('status', 'Aktif');
    $this->db->order_by('nama_merk', 'ASC');
    $result = $this->db->get()->result_array();
    
    header('Content-Type: application/json');
    echo json_encode($result);
}

public function get_master_lokasi()
{
    $this->db->select('id, kode_lokasi, nama_lokasi');
    $this->db->from('master_lokasi');
    $this->db->where('status', 'Aktif');
    $this->db->order_by('nama_lokasi', 'ASC');
    $result = $this->db->get()->result_array();
    
    header('Content-Type: application/json');
    echo json_encode($result);
}

/* =======================
   GET USUL HAPUS (per user login)
======================== */
public function get_usul_hapus()
{
    header('Content-Type: application/json');
    $user_id = $this->session->userdata('user_id') ?? 0;

    $result = $this->db
        ->where('user_id', $user_id)
        ->order_by('created_at', 'ASC')
        ->get('tb_usul_hapus')
        ->result_array();

    echo json_encode($result);
}

/* =======================
   TAMBAH KE USUL HAPUS
======================== */
public function tambah_usul_hapus()
{
    header('Content-Type: application/json');
    $json = file_get_contents('php://input');
    $post = json_decode($json, true);

    $user_id     = $this->session->userdata('user_id') ?? 0;
    $perangkat_id = (int)($post['id'] ?? 0);

    if (!$perangkat_id) {
        echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
        return;
    }

    // Ambil data perangkat
    $p = $this->db->get_where('perangkat', ['id' => $perangkat_id])->row_array();
    if (!$p) {
        echo json_encode(['status' => 'error', 'message' => 'Perangkat tidak ditemukan']);
        return;
    }

    $data = [
        'user_id'      => $user_id,
        'perangkat_id' => $perangkat_id,
        'kode_barang'  => $p['kode_barang'],
        'nup'          => $p['nup'],
        'nama_barang'  => $p['nama_barang'],
        'merk'         => $p['merk'],
        'lokasi'       => $p['lokasi'],
        'kondisi'      => $p['kondisi'],
    ];

    // INSERT IGNORE (karena ada UNIQUE KEY)
    $this->db->query(
        "INSERT IGNORE INTO tb_usul_hapus 
         (user_id, perangkat_id, kode_barang, nup, nama_barang, merk, lokasi, kondisi)
         VALUES (?,?,?,?,?,?,?,?)",
        [
            $data['user_id'], $data['perangkat_id'], $data['kode_barang'],
            $data['nup'], $data['nama_barang'], $data['merk'],
            $data['lokasi'], $data['kondisi']
        ]
    );

    echo json_encode(['status' => 'success']);
}

/* =======================
   HAPUS DARI USUL HAPUS
======================== */
public function hapus_usul_hapus()
{
    header('Content-Type: application/json');
    $json = file_get_contents('php://input');
    $post = json_decode($json, true);

    $user_id      = $this->session->userdata('user_id') ?? 0;
    $perangkat_id = (int)($post['perangkat_id'] ?? 0);

    $this->db->delete('tb_usul_hapus', [
        'user_id'      => $user_id,
        'perangkat_id' => $perangkat_id,
    ]);

    echo json_encode(['status' => 'success']);
}

/* =======================
   KOSONGKAN USUL HAPUS (milik user ini)
======================== */
public function kosongkan_usul_hapus()
{
    header('Content-Type: application/json');
    $user_id = $this->session->userdata('user_id') ?? 0;
    $this->db->delete('tb_usul_hapus', ['user_id' => $user_id]);
    echo json_encode(['status' => 'success']);
}

/* =======================
   GET SEMUA PENGAJUAN (semua admin bisa lihat)
======================== */
public function get_pengajuan_list()
{
    header('Content-Type: application/json');

    $pengajuan = $this->db
        ->order_by('created_at', 'DESC')
        ->get('tb_pengajuan_hapus')
        ->result_array();

    foreach ($pengajuan as &$p) {
        $p['perangkat'] = $this->db
            ->where('pengajuan_id', $p['id'])
            ->get('tb_pengajuan_hapus_detail')
            ->result_array();
    }

    echo json_encode($pengajuan);
}

/* =======================
   BUAT PENGAJUAN BARU
======================== */
public function buat_pengajuan()
{
    header('Content-Type: application/json');
    $json = file_get_contents('php://input');
    $post = json_decode($json, true);

    $user_id   = $this->session->userdata('user_id') ?? 0;
    $nama      = $this->session->userdata('name')    ?? 'User';
    $role      = $this->session->userdata('role')    ?? 'user';
    $items     = $post['perangkat'] ?? [];
    $alasan    = $post['alasan']    ?? '-';

    if (empty($items)) {
        echo json_encode(['status' => 'error', 'message' => 'Tidak ada item']);
        return;
    }

    $nomor = 'TEMP-' . date('YmdHis') . '-' . $user_id;

    $this->db->trans_start();

    $this->db->insert('tb_pengajuan_hapus', [
        'nomor_pengajuan'  => $nomor,
        'tanggal'          => date('Y-m-d H:i:s'),
        'diajukan_oleh'    => $nama,
        'diajukan_oleh_id' => $user_id,
        'role_pengaju'     => $role,
        'jumlah_perangkat' => count($items),
        'alasan'           => $alasan,
        'status'           => 'pending',
    ]);

    $pengajuan_id = $this->db->insert_id();

    foreach ($items as $item) {
        $this->db->insert('tb_pengajuan_hapus_detail', [
            'pengajuan_id' => $pengajuan_id,
            'perangkat_id' => $item['perangkat_id'],
            'kode_barang'  => $item['kode_barang'],
            'nup'          => $item['nup'],
            'nama_barang'  => $item['nama_barang'],
            'merk'         => $item['merk'],
            'lokasi'       => $item['lokasi'],
            'kondisi'      => $item['kondisi'],
        ]);
    }

    // Hapus item yang sudah diajukan dari tb_usul_hapus
    $pid_list = array_column($items, 'perangkat_id');
    if (!empty($pid_list)) {
        $this->db->where('user_id', $user_id)
                 ->where_in('perangkat_id', $pid_list)
                 ->delete('tb_usul_hapus');
    }

    $this->db->trans_complete();

    if ($this->db->trans_status()) {
        echo json_encode(['status' => 'success', 'nomor' => $nomor, 'id' => $pengajuan_id]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan pengajuan']);
    }
}

/* =======================
   UPDATE STATUS PENGAJUAN (approve / reject)
======================== */
public function update_status_pengajuan()
{
    header('Content-Type: application/json');
    $json = file_get_contents('php://input');
    $post = json_decode($json, true);

    $pengajuan_id = (int)($post['pengajuan_id'] ?? 0);
    $action       = $post['action']  ?? '';      // 'approve' | 'reject'
    $nomor_sk     = $post['nomor_sk']    ?? null;
    $catatan      = $post['catatan']     ?? '';
    $alasan_reject = $post['alasan_reject'] ?? '';

    $pengajuan = $this->db
        ->get_where('tb_pengajuan_hapus', ['id' => $pengajuan_id])
        ->row_array();

    if (!$pengajuan) {
        echo json_encode(['status' => 'error', 'message' => 'Pengajuan tidak ditemukan']);
        return;
    }

    if ($action === 'approve') {
        // Jalankan logika approve (sama seperti approve_pengajuan yang sudah ada)
        $details = $this->db
            ->where('pengajuan_id', $pengajuan_id)
            ->get('tb_pengajuan_hapus_detail')
            ->result_array();

        $approved_by = $this->session->userdata('name') ?? 'Admin';
        $success = 0; $failed = 0; $errors = [];

        foreach ($details as $d) {
            $p = $this->db->get_where('perangkat', ['id' => $d['perangkat_id']])->row_array();
            if (!$p) { $failed++; $errors[] = "ID {$d['perangkat_id']} tidak ditemukan"; continue; }

            $exists = $this->db->get_where('perangkat_dihapus', [
                'kode_barang' => $d['kode_barang'], 'nup' => $d['nup']
            ])->row();
            if ($exists) { $failed++; $errors[] = "NUP {$d['nup']} sudah ada di arsip"; continue; }

            $ins = $this->db->insert('perangkat_dihapus', [
                'jenis_bmn'        => $p['jenis_bmn']      ?? '',
                'kode_satker'      => $p['kode_satker']    ?? '',
                'nama_satker'      => $p['nama_satker']    ?? '',
                'kode_barang'      => $p['kode_barang']    ?? '',
                'nup'              => $p['nup']            ?? '',
                'nama_barang'      => $p['nama_barang']    ?? '',
                'merk'             => $p['merk']           ?? '',
                'kondisi'          => $p['kondisi']        ?? 'Rusak Berat',
                'lokasi'           => $p['lokasi']         ?? '',
                'serial_number'    => $p['serial_number']  ?? '',
                'mac_address'      => $p['mac_address']    ?? '',
                'pengguna'         => $p['pengguna']       ?? '',
                'ruangan'          => $p['ruangan']        ?? '',
                'nilai_perolehan'  => $p['nilai_perolehan'] ?? 0,
                'keterangan'       => $catatan ?: ($p['keterangan'] ?? ''),
                'transaksi_sakti'  => $nomor_sk,
                'tanggal_hapus'    => date('Y-m-d H:i:s'),
                'dipindahkan_pada' => date('Y-m-d H:i:s'),
                'dihapus_oleh'     => $approved_by,
            ]);

            if ($ins) {
                $this->db->delete('perangkat',           ['id' => $d['perangkat_id']]);
                $this->db->delete('perangkat_spesifikasi',['perangkat_id' => $d['perangkat_id']]);
                $this->db->where('pengajuan_id', $pengajuan_id)
                         ->where('perangkat_id', $d['perangkat_id'])
                         ->update('tb_pengajuan_hapus_detail', ['status_item' => 'approved']);
                $success++;
            } else {
                $failed++;
            }
        }

        $this->db->where('id', $pengajuan_id)->update('tb_pengajuan_hapus', [
            'status'        => 'approved',
            'nomor_sk'      => $nomor_sk,
            'catatan_admin' => $catatan,
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        echo json_encode([
            'status'  => 'success',
            'message' => "Berhasil approve {$success} dari " . count($details) . " perangkat",
            'details' => ['success' => $success, 'failed' => $failed, 'errors' => $errors],
        ]);

    } elseif ($action === 'reject') {
        $this->db->where('id', $pengajuan_id)->update('tb_pengajuan_hapus', [
            'status'        => 'rejected',
            'alasan_reject' => $alasan_reject,
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);
        echo json_encode(['status' => 'success']);

    } elseif ($action === 'batalkan') {
        // Kembalikan item ke tb_usul_hapus pemilik pengajuan
        $details = $this->db
            ->where('pengajuan_id', $pengajuan_id)
            ->get('tb_pengajuan_hapus_detail')
            ->result_array();

        foreach ($details as $d) {
            $this->db->query(
                "INSERT IGNORE INTO tb_usul_hapus
                 (user_id, perangkat_id, kode_barang, nup, nama_barang, merk, lokasi, kondisi)
                 VALUES (?,?,?,?,?,?,?,?)",
                [
                    $pengajuan['diajukan_oleh_id'],
                    $d['perangkat_id'], $d['kode_barang'], $d['nup'],
                    $d['nama_barang'], $d['merk'], $d['lokasi'], $d['kondisi'],
                ]
            );
        }
        $this->db->delete('tb_pengajuan_hapus', ['id' => $pengajuan_id]);
        echo json_encode(['status' => 'success']);

    } elseif ($action === 'reject_item') {
        // Reject satu item saja dari pengajuan
        $item_id = (int)($post['item_id'] ?? 0);
        $this->db->where('id', $item_id)
                 ->where('pengajuan_id', $pengajuan_id)
                 ->update('tb_pengajuan_hapus_detail', ['status_item' => 'rejected']);

        // Update jumlah
        $sisa = $this->db->where('pengajuan_id', $pengajuan_id)
                         ->where('status_item !=', 'rejected')
                         ->count_all_results('tb_pengajuan_hapus_detail');

        $this->db->where('id', $pengajuan_id)->update('tb_pengajuan_hapus', [
            'jumlah_perangkat' => $sisa,
            'updated_at'       => date('Y-m-d H:i:s'),
            'status'           => $sisa === 0 ? 'rejected' : 'pending',
        ]);

        echo json_encode(['status' => 'success', 'sisa' => $sisa]);
    }
}
public function debug_session()
{
    header('Content-Type: application/json');
    echo json_encode([
        'user_id' => $this->session->userdata('user_id'),
        'name'    => $this->session->userdata('name'),
        'role'    => $this->session->userdata('role'),
        'all'     => $this->session->all_userdata(),
    ]);
}
/* =======================
   GET PEGAWAI LIST (AJAX untuk dropdown)
======================== */
public function get_pegawai_list()
{
    header('Content-Type: application/json');
    $this->load->model('Pegawai_model');
    $result = $this->db
        ->select('id, nama, jabatan, nip_baru, status')
        ->order_by('nama', 'ASC')
        ->get('master_pegawai')
        ->result_array();
    echo json_encode($result);
}
}