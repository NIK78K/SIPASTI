<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Barang_model');
        $this->load->helper('url');
        $this->load->library('form_validation');
        
        // Cek session login
        if(!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }

    public function index() {
        $data['master_barang'] = $this->Barang_model->get_all_barang();
        $data['kategori_list'] = $this->Barang_model->get_kategori_list();
        
        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar');
        $this->load->view('admin/master_barang/index', $data);
        $this->load->view('layout/footer');
    }

    public function tambah() {
        header('Content-Type: application/json; charset=utf-8');
        
        $this->form_validation->set_rules('kategori', 'Kategori', 'required|trim');
        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required|trim');
        $this->form_validation->set_rules('merk', 'Merk', 'required|trim');
        
        if ($this->form_validation->run() == FALSE) {
            $response = array(
                'status' => 'error',
                'message' => strip_tags(validation_errors())
            );
            echo json_encode($response);
            return;
        }
        
        $kategori = $this->input->post('kategori', true);
        $nama_barang = $this->input->post('nama_barang', true);
        $merk = $this->input->post('merk', true);
        $status = $this->input->post('status', true) ?? 'Aktif';
        
        // Cek duplikasi nama barang dalam kategori yang sama
        if ($this->Barang_model->check_duplicate($kategori, $nama_barang)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Nama barang sudah ada dalam kategori ini!'
            ]);
            return;
        }
        
        $this->db->trans_start();
        
        $data_barang = [
            'kategori' => $kategori,
            'nama_barang' => $nama_barang,
            'status' => $status
        ];
        
        $insert_barang = $this->Barang_model->insert_barang($data_barang);
        
        if ($insert_barang) {
            $master_barang_id = $this->Barang_model->get_last_insert_id();
            
            $data_merk = [
                'master_barang_id' => $master_barang_id,
                'nama_merk' => $merk,
                'status' => 'Aktif'
            ];
            
            $this->Barang_model->insert_merk($data_merk);
        }
        
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal menambahkan data barang!'
            ]);
        } else {
            echo json_encode([
                'status' => 'success',
                'message' => 'Data barang berhasil ditambahkan!'
            ]);
        }
    }

    // ✅ HALAMAN EDIT TERPISAH
    public function edit($id) {
        $data['barang'] = $this->Barang_model->get_barang_by_id($id);
        
        if (!$data['barang']) {
            show_404();
        }
        
        $data['kategori_list'] = $this->Barang_model->get_kategori_list();
        
        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar');
        $this->load->view('admin/master_barang/edit', $data);
        $this->load->view('layout/footer');
    }

    // ✅ PROSES UPDATE (DIPANGGIL DARI FORM EDIT)
   public function update() {
        header('Content-Type: application/json');
        
        $this->form_validation->set_rules('id', 'ID', 'required|numeric');
        $this->form_validation->set_rules('kategori', 'Kategori', 'required|trim');
        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required|trim');
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'status' => 'error',
                'message' => validation_errors()
            ]);
            return;
        }
        
        $id = $this->input->post('id', true);
        $kategori = $this->input->post('kategori', true);
        $nama_barang = $this->input->post('nama_barang', true);
        $status = $this->input->post('status', true) ?? 'Aktif';
        
        // Cek duplikasi
        if ($this->Barang_model->check_duplicate_except($kategori, $nama_barang, $id)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Nama barang sudah ada dalam kategori ini!'
            ]);
            return;
        }
        
        // ✅ AMBIL DATA LAMA UNTUK SINKRONISASI
        $old_barang = $this->Barang_model->get_barang_by_id($id);
        
        if (!$old_barang) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Data barang tidak ditemukan!'
            ]);
            return;
        }
        
        $data_barang = [
            'kategori' => $kategori,
            'nama_barang' => $nama_barang,
            'status' => $status
        ];
        
        $this->db->trans_start();
        
        // Update master_barang
        $update = $this->Barang_model->update_barang($id, $data_barang);
        
        // ✅ SINKRONISASI KE TABEL PERANGKAT (jika nama berubah)
        $affected_count = 0;
        if ($update && $old_barang->nama_barang !== $nama_barang) {
            $affected_count = $this->Barang_model->count_perangkat_affected('nama_barang', $old_barang->nama_barang);
            $this->Barang_model->sync_perangkat_after_barang_update($old_barang->nama_barang, $nama_barang);
        }
        
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal mengupdate data barang!'
            ]);
        } else {
            $message = 'Data barang berhasil diupdate!';
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

    public function get_barang($id) {
        header('Content-Type: application/json');
        
        $barang = $this->Barang_model->get_barang_by_id($id);
        
        if ($barang) {
            $merk_list = $this->Barang_model->get_merk_by_barang_id($id);
            $barang->merk_list = $merk_list;
            
            echo json_encode($barang);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Data barang tidak ditemukan!'
            ]);
        }
    }

    public function hapus($id) {
        header('Content-Type: application/json');
        
        if (empty($id) || !is_numeric($id)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'ID tidak valid!'
            ]);
            return;
        }
        
        $delete = $this->Barang_model->delete_barang($id);
        
        if ($delete) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Data barang berhasil dihapus!'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal menghapus data barang!'
            ]);
        }
    }

    public function toggle_status($id) {
        header('Content-Type: application/json');
        
        $barang = $this->Barang_model->get_barang_by_id($id);
        
        if (!$barang) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Data barang tidak ditemukan!'
            ]);
            return;
        }
        
        $new_status = ($barang->status == 'Aktif') ? 'Tidak Aktif' : 'Aktif';
        
        $update = $this->Barang_model->update_barang($id, ['status' => $new_status]);
        
        if ($update) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Status barang berhasil diubah menjadi ' . $new_status,
                'new_status' => $new_status
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal mengubah status barang!'
            ]);
        }
    }

    // MERK MANAGEMENT
    public function tambah_merk() {
        header('Content-Type: application/json');
        
        $this->form_validation->set_rules('master_barang_id', 'Barang', 'required|numeric');
        $this->form_validation->set_rules('nama_merk', 'Merk', 'required|trim');
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'status' => 'error',
                'message' => validation_errors()
            ]);
            return;
        }
        
        $master_barang_id = $this->input->post('master_barang_id', true);
        $nama_merk = $this->input->post('nama_merk', true);
        
        if ($this->Barang_model->check_merk_duplicate($master_barang_id, $nama_merk)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Merk sudah ada untuk barang ini!'
            ]);
            return;
        }
        
        $data = [
            'master_barang_id' => $master_barang_id,
            'nama_merk' => $nama_merk,
            'status' => 'Aktif'
        ];
        
        $insert = $this->Barang_model->insert_merk($data);
        
        if ($insert) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Merk berhasil ditambahkan!'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal menambahkan merk!'
            ]);
        }
    }

    public function get_merk_by_barang($master_barang_id) {
        header('Content-Type: application/json');
        
        $merk_list = $this->Barang_model->get_merk_by_barang_id($master_barang_id);
        echo json_encode($merk_list);
    }

    public function hapus_merk($id) {
        header('Content-Type: application/json');
        
        $delete = $this->Barang_model->delete_merk($id);
        
        if ($delete) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Merk berhasil dihapus!'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal menghapus merk!'
            ]);
        }
    }

    public function update_merk() {
        header('Content-Type: application/json');
        
        $this->form_validation->set_rules('id', 'ID', 'required|numeric');
        $this->form_validation->set_rules('nama_merk', 'Nama Merk', 'required|trim');
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'status' => 'error',
                'message' => strip_tags(validation_errors())
            ]);
            return;
        }
        
        $id = $this->input->post('id', true);
        $nama_merk = $this->input->post('nama_merk', true);
        
        // ✅ AMBIL DATA LAMA UNTUK SINKRONISASI
        $old_merk = $this->Barang_model->get_merk_by_id($id);
        
        if (!$old_merk) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Data merk tidak ditemukan!'
            ]);
            return;
        }
        
        // Cek duplikasi (kecuali ID yang sedang diedit)
        $check_duplicate = $this->db
            ->where('master_barang_id', $old_merk->master_barang_id)
            ->where('nama_merk', $nama_merk)
            ->where('id !=', $id)
            ->get('master_merk')
            ->num_rows();
        
        if ($check_duplicate > 0) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Nama merk sudah ada untuk barang ini!'
            ]);
            return;
        }
        
        $this->db->trans_start();
        
        // Update master_merk
        $data = ['nama_merk' => $nama_merk];
        $update = $this->Barang_model->update_merk($id, $data);
        
        // ✅ SINKRONISASI KE TABEL PERANGKAT (jika nama merk berubah)
        $affected_count = 0;
        if ($update && $old_merk->nama_merk !== $nama_merk) {
            $affected_count = $this->Barang_model->count_perangkat_affected('merk', $old_merk->nama_merk);
            $this->Barang_model->sync_perangkat_after_merk_update($old_merk->nama_merk, $nama_merk);
        }
        
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal mengupdate merk!'
            ]);
        } else {
            $message = 'Merk berhasil diupdate!';
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
}