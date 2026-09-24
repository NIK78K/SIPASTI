<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang_persediaan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Barang_persediaan_model', 'Kategori_persediaan_model']);
        $this->load->helper('url');
        $this->load->library('form_validation');

        // Cek session login
        if(!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }

    public function index() {
        $data['barang'] = $this->Barang_persediaan_model->get_all_with_kategori();
        $data['kategori_list'] = $this->Kategori_persediaan_model->get_all();

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar');
        $this->load->view('admin/barang_persediaan/index', $data);
        $this->load->view('layout/footer');
    }

    public function get($id) {
        header('Content-Type: application/json');
        $barang = $this->Barang_persediaan_model->get_by_id($id);
        if ($barang) {
            echo json_encode($barang);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Data barang tidak ditemukan!']);
        }
    }

    public function tambah() {
        header('Content-Type: application/json; charset=utf-8');

        $this->form_validation->set_rules('kategori_id', 'Kategori', 'required|numeric');
        $this->form_validation->set_rules('kode_barang', 'Kode Barang', 'required|trim|max_length[50]');
        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required|trim');
        $this->form_validation->set_rules('satuan', 'Satuan', 'trim|max_length[50]');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => 'error', 'message' => strip_tags(validation_errors())]);
            return;
        }

        $kategori_id = $this->input->post('kategori_id', true);
        $kode_barang = trim($this->input->post('kode_barang', true));
        $nama_barang = trim($this->input->post('nama_barang', true));
        $satuan = trim($this->input->post('satuan', true)) ?: 'pcs';
        $status = $this->input->post('status', true) ?: 'Aktif';
        if (!in_array($status, ['Aktif', 'Nonaktif'])) $status = 'Aktif';

        if (!$this->Kategori_persediaan_model->get_by_id($kategori_id)) {
            echo json_encode(['status' => 'error', 'message' => 'Kategori tidak valid!']);
            return;
        }

        if ($this->Barang_persediaan_model->is_kode_used($kode_barang)) {
            echo json_encode(['status' => 'error', 'message' => 'Kode barang sudah dipakai!']);
            return;
        }

        if ($this->Barang_persediaan_model->check_duplicate($kategori_id, $nama_barang)) {
            echo json_encode(['status' => 'error', 'message' => 'Nama barang sudah ada dalam kategori ini!']);
            return;
        }

        $ok = $this->Barang_persediaan_model->insert([
            'kategori_id' => $kategori_id,
            'kode_barang' => $kode_barang,
            'nama_barang' => $nama_barang,
            'satuan' => $satuan,
            'status' => $status
        ]);

        echo json_encode($ok
            ? ['status' => 'success', 'message' => 'Barang persediaan berhasil ditambahkan!']
            : ['status' => 'error', 'message' => 'Gagal menambahkan barang!']);
    }

    public function update() {
        header('Content-Type: application/json');

        $this->form_validation->set_rules('id', 'ID', 'required|numeric');
        $this->form_validation->set_rules('kategori_id', 'Kategori', 'required|numeric');
        $this->form_validation->set_rules('kode_barang', 'Kode Barang', 'required|trim|max_length[50]');
        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required|trim');
        $this->form_validation->set_rules('satuan', 'Satuan', 'trim|max_length[50]');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => 'error', 'message' => strip_tags(validation_errors())]);
            return;
        }

        $id = $this->input->post('id', true);
        $kategori_id = $this->input->post('kategori_id', true);
        $kode_barang = trim($this->input->post('kode_barang', true));
        $nama_barang = trim($this->input->post('nama_barang', true));
        $satuan = trim($this->input->post('satuan', true)) ?: 'pcs';
        $status = $this->input->post('status', true) ?: 'Aktif';
        if (!in_array($status, ['Aktif', 'Nonaktif'])) $status = 'Aktif';

        if (!$this->Barang_persediaan_model->get_by_id($id)) {
            echo json_encode(['status' => 'error', 'message' => 'Data barang tidak ditemukan!']);
            return;
        }

        if ($this->Barang_persediaan_model->is_kode_used($kode_barang, $id)) {
            echo json_encode(['status' => 'error', 'message' => 'Kode barang sudah dipakai!']);
            return;
        }

        if ($this->Barang_persediaan_model->check_duplicate_except($kategori_id, $nama_barang, $id)) {
            echo json_encode(['status' => 'error', 'message' => 'Nama barang sudah ada dalam kategori ini!']);
            return;
        }

        $ok = $this->Barang_persediaan_model->update($id, [
            'kategori_id' => $kategori_id,
            'kode_barang' => $kode_barang,
            'nama_barang' => $nama_barang,
            'satuan' => $satuan,
            'status' => $status
        ]);

        echo json_encode($ok
            ? ['status' => 'success', 'message' => 'Barang persediaan berhasil diupdate!']
            : ['status' => 'error', 'message' => 'Gagal mengupdate barang!']);
    }

    public function hapus($id) {
        header('Content-Type: application/json');

        if (empty($id) || !is_numeric($id)) {
            echo json_encode(['status' => 'error', 'message' => 'ID tidak valid!']);
            return;
        }

        $ok = $this->Barang_persediaan_model->delete($id);

        echo json_encode($ok
            ? ['status' => 'success', 'message' => 'Barang persediaan berhasil dihapus!']
            : ['status' => 'error', 'message' => 'Gagal menghapus barang!']);
    }

    public function toggle_status($id) {
        header('Content-Type: application/json');

        $barang = $this->Barang_persediaan_model->get_by_id($id);
        if (!$barang) {
            echo json_encode(['status' => 'error', 'message' => 'Data barang tidak ditemukan!']);
            return;
        }

        $new_status = ($barang->status == 'Aktif') ? 'Nonaktif' : 'Aktif';
        $ok = $this->Barang_persediaan_model->update($id, ['status' => $new_status]);

        echo json_encode($ok
            ? ['status' => 'success', 'message' => 'Status diubah menjadi ' . $new_status, 'new_status' => $new_status]
            : ['status' => 'error', 'message' => 'Gagal mengubah status!']);
    }
}
