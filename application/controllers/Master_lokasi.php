<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_lokasi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Cek session login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        
        $this->load->model('Lokasi_model');
        $this->load->library('form_validation');
    }

    public function index() {
        $data['title'] = 'Master Lokasi';
        $data['lokasi'] = $this->Lokasi_model->get_all_lokasi();
        
        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar');
        $this->load->view('admin/master_lokasi/lokasi', $data);
        $this->load->view('layout/footer');
    }

    // ✅ TAMBAH LOKASI
    public function tambah() {
        header('Content-Type: application/json');
        
        $this->form_validation->set_rules('kode_lokasi', 'Kode Lokasi', 'required|trim');
        $this->form_validation->set_rules('nama_lokasi', 'Nama Lokasi', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'status' => 'error',
                'message' => strip_tags(validation_errors())
            ]);
            return;
        }
        
        $kode_lokasi = $this->input->post('kode_lokasi', true);
        $nama_lokasi = $this->input->post('nama_lokasi', true);
        $keterangan = $this->input->post('keterangan', true);
        $status = $this->input->post('status', true);
        
        // Cek apakah kode sudah ada
        $cek_kode = $this->Lokasi_model->get_by_kode($kode_lokasi);
        if ($cek_kode) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Kode lokasi sudah digunakan'
            ]);
            return;
        }
        
        $data = [
            'kode_lokasi' => $kode_lokasi,
            'nama_lokasi' => $nama_lokasi,
            'keterangan' => $keterangan ? $keterangan : null,
            'status' => $status ? $status : 'Aktif',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($this->Lokasi_model->insert_lokasi($data)) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Lokasi berhasil ditambahkan'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal menambahkan lokasi. Silakan coba lagi.'
            ]);
        }
    }

    // ✅ GET LOKASI BY ID
    public function get_lokasi($id) {
        header('Content-Type: application/json');
        
        if (!$id || !is_numeric($id)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'ID tidak valid'
            ]);
            return;
        }
        
        $data = $this->Lokasi_model->get_lokasi_by_id($id);
        
        if (!$data) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
            return;
        }
        
        // Return data langsung tanpa wrapper
        echo json_encode($data);
    }

    // ✅ EDIT LOKASI + AUTO SYNC + TIMESTAMP
    public function edit() {
        header('Content-Type: application/json');
        
        $id = $this->input->post('id', true);
        
        if (!$id) {
            echo json_encode([
                'status' => 'error',
                'message' => 'ID tidak valid'
            ]);
            return;
        }
        
        // Ambil data lama
        $lokasi_lama = $this->Lokasi_model->get_lokasi_by_id($id);
        if (!$lokasi_lama) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
            return;
        }
        
        // Validasi input
        $this->form_validation->set_rules('kode_lokasi', 'Kode Lokasi', 'required|trim');
        $this->form_validation->set_rules('nama_lokasi', 'Nama Lokasi', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'status' => 'error',
                'message' => strip_tags(validation_errors())
            ]);
            return;
        }
        
        $kode_baru = $this->input->post('kode_lokasi', true);
        $nama_baru = $this->input->post('nama_lokasi', true);
        
        // Cek unique kode (jika berubah)
        if ($lokasi_lama->kode_lokasi != $kode_baru) {
            $cek_kode = $this->Lokasi_model->get_by_kode($kode_baru);
            if ($cek_kode) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Kode lokasi sudah digunakan'
                ]);
                return;
            }
        }
        
        $data = [
            'kode_lokasi' => $kode_baru,
            'nama_lokasi' => $nama_baru,
            'keterangan' => $this->input->post('keterangan', true),
            'status' => $this->input->post('status', true) ? $this->input->post('status', true) : 'Aktif',
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Format untuk perangkat: "kode - nama"
        $old_lokasi_format = $lokasi_lama->kode_lokasi . ' - ' . $lokasi_lama->nama_lokasi;
        $new_lokasi_format = $kode_baru . ' - ' . $nama_baru;

        $this->db->trans_start();
        
        // Update master_lokasi
        $update = $this->Lokasi_model->update_lokasi($id, $data);
        
        // Sinkronisasi ke tabel perangkat (jika berubah)
        $affected_count = 0;
        if ($update && $old_lokasi_format !== $new_lokasi_format) {
            $affected_count = $this->Lokasi_model->count_perangkat_affected_by_lokasi($old_lokasi_format);
            $this->Lokasi_model->sync_perangkat_after_lokasi_update($old_lokasi_format, $new_lokasi_format);
        }
        
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal mengupdate lokasi. Silakan coba lagi.'
            ]);
        } else {
            $message = 'Lokasi berhasil diupdate!';
            if ($affected_count > 0) {
                $message .= " ($affected_count perangkat ikut terupdate)";
            }
            
            echo json_encode([
                'status' => 'success',
                'message' => $message,
                'affected_perangkat' => $affected_count
            ]);
        }
    }

    // ✅ HAPUS LOKASI
    public function hapus($id) {
        header('Content-Type: application/json');
        
        if (!$id) {
            echo json_encode([
                'status' => 'error',
                'message' => 'ID tidak valid'
            ]);
            return;
        }
        
        $lokasi = $this->Lokasi_model->get_lokasi_by_id($id);
        if (!$lokasi) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
            return;
        }
        
        if ($this->Lokasi_model->delete_lokasi($id)) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Lokasi berhasil dihapus'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal menghapus lokasi. Silakan coba lagi.'
            ]);
        }
    }

    // ✅ TOGGLE STATUS + TIMESTAMP
    public function toggle_status($id) {
        header('Content-Type: application/json');
        
        $lokasi = $this->Lokasi_model->get_lokasi_by_id($id);
        
        if (!$lokasi) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Data lokasi tidak ditemukan!'
            ]);
            return;
        }
        
        $new_status = ($lokasi->status == 'Aktif') ? 'Tidak Aktif' : 'Aktif';
        
        $update_data = [
            'status' => $new_status,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $update = $this->Lokasi_model->update_lokasi($id, $update_data);
        
        if ($update) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Status lokasi berhasil diubah menjadi ' . $new_status,
                'new_status' => $new_status
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal mengubah status lokasi!'
            ]);
        }
    }
}