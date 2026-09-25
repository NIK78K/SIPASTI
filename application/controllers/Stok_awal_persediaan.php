<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stok_awal_persediaan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Stok_awal_persediaan_model', 'Barang_persediaan_model']);
        $this->load->helper('url');
        $this->load->library('form_validation');

        // Cek session login
        if(!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }

    public function index() {
        $data['stok_awal'] = $this->Stok_awal_persediaan_model->get_all_with_barang();
        $data['barang_list'] = $this->Barang_persediaan_model->get_all_with_kategori();

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar');
        $this->load->view('admin/stok_awal_persediaan/index', $data);
        $this->load->view('layout/footer');
    }

    public function get($id) {
        header('Content-Type: application/json');
        $stok = $this->Stok_awal_persediaan_model->get_by_id($id);
        if ($stok) {
            echo json_encode($stok);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Data stok awal tidak ditemukan!']);
        }
    }

    public function tambah() {
        header('Content-Type: application/json; charset=utf-8');

        $this->form_validation->set_rules('barang_id', 'Barang', 'required|numeric');
        $this->form_validation->set_rules('bulan', 'Bulan', 'required|numeric|greater_than[0]|less_than[13]');
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|numeric|exact_length[4]');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|numeric');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|max_length[255]');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => 'error', 'message' => strip_tags(validation_errors())]);
            return;
        }

        $barang_id = $this->input->post('barang_id', true);
        $bulan = (int) $this->input->post('bulan', true);
        $tahun = (int) $this->input->post('tahun', true);
        $jumlah = $this->input->post('jumlah', true);
        $keterangan = trim($this->input->post('keterangan', true) ?: '');

        if ($bulan < 1 || $bulan > 12) {
            echo json_encode(['status' => 'error', 'message' => 'Bulan harus 1-12!']);
            return;
        }

        if (!$this->Barang_persediaan_model->get_by_id($barang_id)) {
            echo json_encode(['status' => 'error', 'message' => 'Barang tidak valid!']);
            return;
        }

        if ($this->Stok_awal_persediaan_model->check_duplicate($barang_id, $bulan, $tahun)) {
            echo json_encode(['status' => 'error', 'message' => 'Stok awal barang ini untuk periode tersebut sudah ada!']);
            return;
        }

        $ok = $this->Stok_awal_persediaan_model->insert([
            'barang_id' => $barang_id,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'jumlah' => $jumlah,
            'keterangan' => $keterangan ?: NULL
        ]);

        echo json_encode($ok
            ? ['status' => 'success', 'message' => 'Stok awal berhasil ditambahkan!']
            : ['status' => 'error', 'message' => 'Gagal menambahkan stok awal!']);
    }

    public function update() {
        header('Content-Type: application/json');

        $this->form_validation->set_rules('id', 'ID', 'required|numeric');
        $this->form_validation->set_rules('barang_id', 'Barang', 'required|numeric');
        $this->form_validation->set_rules('bulan', 'Bulan', 'required|numeric|greater_than[0]|less_than[13]');
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|numeric|exact_length[4]');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|numeric');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|max_length[255]');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => 'error', 'message' => strip_tags(validation_errors())]);
            return;
        }

        $id = $this->input->post('id', true);
        $barang_id = $this->input->post('barang_id', true);
        $bulan = (int) $this->input->post('bulan', true);
        $tahun = (int) $this->input->post('tahun', true);
        $jumlah = $this->input->post('jumlah', true);
        $keterangan = trim($this->input->post('keterangan', true) ?: '');

        if ($bulan < 1 || $bulan > 12) {
            echo json_encode(['status' => 'error', 'message' => 'Bulan harus 1-12!']);
            return;
        }

        if (!$this->Stok_awal_persediaan_model->get_by_id($id)) {
            echo json_encode(['status' => 'error', 'message' => 'Data stok awal tidak ditemukan!']);
            return;
        }

        if (!$this->Barang_persediaan_model->get_by_id($barang_id)) {
            echo json_encode(['status' => 'error', 'message' => 'Barang tidak valid!']);
            return;
        }

        if ($this->Stok_awal_persediaan_model->check_duplicate_except($barang_id, $bulan, $tahun, $id)) {
            echo json_encode(['status' => 'error', 'message' => 'Stok awal barang ini untuk periode tersebut sudah ada!']);
            return;
        }

        $ok = $this->Stok_awal_persediaan_model->update($id, [
            'barang_id' => $barang_id,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'jumlah' => $jumlah,
            'keterangan' => $keterangan ?: NULL
        ]);

        echo json_encode($ok
            ? ['status' => 'success', 'message' => 'Stok awal berhasil diupdate!']
            : ['status' => 'error', 'message' => 'Gagal mengupdate stok awal!']);
    }

    public function hapus($id) {
        header('Content-Type: application/json');

        if (empty($id) || !is_numeric($id)) {
            echo json_encode(['status' => 'error', 'message' => 'ID tidak valid!']);
            return;
        }

        $ok = $this->Stok_awal_persediaan_model->delete($id);

        echo json_encode($ok
            ? ['status' => 'success', 'message' => 'Stok awal berhasil dihapus!']
            : ['status' => 'error', 'message' => 'Gagal menghapus stok awal!']);
    }
}
