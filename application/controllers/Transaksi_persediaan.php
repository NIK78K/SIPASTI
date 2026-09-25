<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi_persediaan extends CI_Controller {

    public function __construct() {
        parent::__construct();

        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }

        $role = $this->session->userdata('role');
        if (!in_array($role, ['teknisi', 'admin', 'superadmin'])) {
            show_error('Akses ditolak', 403);
        }

        $this->load->model(['Transaksi_persediaan_model', 'Barang_persediaan_model']);
        $this->load->helper('url');
        $this->load->library('form_validation');
    }

    public function index() {
        $jenis = $this->input->get('jenis', true);
        $barang_id = $this->input->get('barang_id', true);
        $bulan = $this->input->get('bulan', true);
        $tahun = $this->input->get('tahun', true);

        $jenis = in_array($jenis, ['masuk', 'keluar'], true) ? $jenis : null;
        $barang_id = (is_numeric($barang_id) && $barang_id > 0) ? $barang_id : null;
        $bulan = (is_numeric($bulan) && $bulan >= 1 && $bulan <= 12) ? (int) $bulan : null;
        $tahun = (is_numeric($tahun) && strlen((string) $tahun) === 4) ? (int) $tahun : null;

        $data['transaksi'] = $this->Transaksi_persediaan_model->get_filtered($jenis, $barang_id, $bulan, $tahun);
        $data['barang_list'] = $this->Barang_persediaan_model->get_all_with_kategori();
        $data['tahun_list'] = $this->Transaksi_persediaan_model->get_tahun_list();
        $stats = $this->Transaksi_persediaan_model->stats();
        $data['total'] = $stats['total'] ?? 0;
        $data['masuk'] = $stats['masuk'] ?? 0;
        $data['keluar'] = $stats['keluar'] ?? 0;
        $data['total_unit'] = $stats['total_unit'] ?? 0;
        $data['filter'] = [
            'jenis'     => $jenis ?: '',
            'barang_id' => $barang_id ?: '',
            'bulan'     => $bulan ?: '',
            'tahun'     => $tahun ?: '',
        ];

        $this->load->view('layout/header');
        $this->load->view('layout/sidebar_teknisi');
        $this->load->view('teknisi_panel/transaksi_persediaan/index', $data);
        $this->load->view('layout/footer');
    }

    public function list_ajax() {
        header('Content-Type: application/json; charset=utf-8');
        $jenis = $this->input->get('jenis', true);
        $barang_id = $this->input->get('barang_id', true);
        $bulan = $this->input->get('bulan', true);
        $tahun = $this->input->get('tahun', true);

        $jenis = in_array($jenis, ['masuk', 'keluar'], true) ? $jenis : null;
        $barang_id = (is_numeric($barang_id) && $barang_id > 0) ? $barang_id : null;
        $bulan = (is_numeric($bulan) && $bulan >= 1 && $bulan <= 12) ? (int) $bulan : null;
        $tahun = (is_numeric($tahun) && strlen((string) $tahun) === 4) ? (int) $tahun : null;

        echo json_encode($this->Transaksi_persediaan_model->get_filtered($jenis, $barang_id, $bulan, $tahun));
    }

    public function get($id) {
        header('Content-Type: application/json');
        $trx = $this->Transaksi_persediaan_model->get_by_id($id);
        if ($trx) {
            echo json_encode($trx);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Data transaksi tidak ditemukan!']);
        }
    }

    public function tambah() {
        header('Content-Type: application/json; charset=utf-8');

        $this->form_validation->set_rules('barang_id', 'Barang', 'required|numeric');
        $this->form_validation->set_rules('jenis', 'Jenis', 'required|in_list[masuk,keluar]');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required|callback_valid_date');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('uraian', 'Uraian', 'trim|max_length[255]');
        $this->form_validation->set_rules('penerima_sumber', 'Penerima/Sumber', 'trim|max_length[100]');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => 'error', 'message' => strip_tags(validation_errors())]);
            return;
        }

        $barang_id = $this->input->post('barang_id', true);
        if (!$this->Barang_persediaan_model->get_by_id($barang_id)) {
            echo json_encode(['status' => 'error', 'message' => 'Barang tidak valid!']);
            return;
        }

        $ok = $this->Transaksi_persediaan_model->insert([
            'barang_id'       => $barang_id,
            'jenis'           => $this->input->post('jenis', true),
            'tanggal'         => $this->input->post('tanggal', true),
            'jumlah'          => $this->input->post('jumlah', true),
            'uraian'          => trim($this->input->post('uraian', true) ?: '') ?: NULL,
            'penerima_sumber' => trim($this->input->post('penerima_sumber', true) ?: '') ?: NULL,
            'user_id'         => $this->session->userdata('user_id'),
        ]);

        echo json_encode($ok
            ? ['status' => 'success', 'message' => 'Transaksi berhasil ditambahkan!']
            : ['status' => 'error', 'message' => 'Gagal menambahkan transaksi!']);
    }

    public function update() {
        header('Content-Type: application/json; charset=utf-8');

        $this->form_validation->set_rules('id', 'ID', 'required|numeric');
        $this->form_validation->set_rules('barang_id', 'Barang', 'required|numeric');
        $this->form_validation->set_rules('jenis', 'Jenis', 'required|in_list[masuk,keluar]');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required|callback_valid_date');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('uraian', 'Uraian', 'trim|max_length[255]');
        $this->form_validation->set_rules('penerima_sumber', 'Penerima/Sumber', 'trim|max_length[100]');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => 'error', 'message' => strip_tags(validation_errors())]);
            return;
        }

        $id = $this->input->post('id', true);
        $barang_id = $this->input->post('barang_id', true);

        if (!$this->Transaksi_persediaan_model->get_by_id($id)) {
            echo json_encode(['status' => 'error', 'message' => 'Data transaksi tidak ditemukan!']);
            return;
        }

        if (!$this->Barang_persediaan_model->get_by_id($barang_id)) {
            echo json_encode(['status' => 'error', 'message' => 'Barang tidak valid!']);
            return;
        }

        $ok = $this->Transaksi_persediaan_model->update($id, [
            'barang_id'       => $barang_id,
            'jenis'           => $this->input->post('jenis', true),
            'tanggal'         => $this->input->post('tanggal', true),
            'jumlah'          => $this->input->post('jumlah', true),
            'uraian'          => trim($this->input->post('uraian', true) ?: '') ?: NULL,
            'penerima_sumber' => trim($this->input->post('penerima_sumber', true) ?: '') ?: NULL,
        ]);

        echo json_encode($ok
            ? ['status' => 'success', 'message' => 'Transaksi berhasil diupdate!']
            : ['status' => 'error', 'message' => 'Gagal mengupdate transaksi!']);
    }

    public function hapus($id) {
        header('Content-Type: application/json');

        if (empty($id) || !is_numeric($id)) {
            echo json_encode(['status' => 'error', 'message' => 'ID tidak valid!']);
            return;
        }

        $ok = $this->Transaksi_persediaan_model->delete($id);

        echo json_encode($ok
            ? ['status' => 'success', 'message' => 'Transaksi berhasil dihapus!']
            : ['status' => 'error', 'message' => 'Gagal menghapus transaksi!']);
    }

    public function valid_date($str) {
        $d = DateTime::createFromFormat('Y-m-d', $str);
        if ($d && $d->format('Y-m-d') === $str) {
            return TRUE;
        }
        $this->form_validation->set_message('valid_date', 'Tanggal tidak valid! Gunakan format YYYY-MM-DD.');
        return FALSE;
    }
}
