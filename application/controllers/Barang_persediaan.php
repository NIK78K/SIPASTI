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
        $data['kategori'] = $this->Barang_persediaan_model->get_kategori_grouped();

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

    public function get_by_kategori($id) {
        header('Content-Type: application/json');
        if (empty($id) || !is_numeric($id)) {
            echo json_encode(['status' => 'error', 'message' => 'ID kategori tidak valid!']);
            return;
        }
        echo json_encode($this->Barang_persediaan_model->get_by_kategori($id));
    }

    public function next_kode($id = NULL) {
        header('Content-Type: application/json');
        if (empty($id) || !is_numeric($id)) {
            echo json_encode(['status' => 'error', 'message' => 'ID kategori tidak valid!']);
            return;
        }
        $kat = $this->Kategori_persediaan_model->get_by_id($id);
        if (!$kat) {
            echo json_encode(['status' => 'error', 'message' => 'Kategori tidak ditemukan!']);
            return;
        }
        $kode = $this->Barang_persediaan_model->next_kode($id);
        if ($kode === NULL) {
            echo json_encode(['status' => 'error', 'message' => 'Gagal membuat kode otomatis!']);
            return;
        }
        echo json_encode(['status' => 'success', 'kode' => $kode]);
    }

    public function tambah() {
        header('Content-Type: application/json; charset=utf-8');

        $this->form_validation->set_rules('kategori_id', 'Kategori', 'required|numeric');
        $this->form_validation->set_rules('kode_barang', 'Kode Barang', 'trim|max_length[50]');
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

        $auto = ($kode_barang === '' || $kode_barang === '-' || $kode_barang === '...');
        if ($auto) {
            $kode_barang = $this->Barang_persediaan_model->next_kode($kategori_id);
            if ($kode_barang === NULL) {
                echo json_encode(['status' => 'error', 'message' => 'Gagal membuat kode otomatis!']);
                return;
            }
            $tries = 0;
            while ($this->Barang_persediaan_model->is_kode_used($kode_barang) && $tries < 20) {
                $pos = strrpos($kode_barang, '-');
                $suf = ($pos === FALSE) ? '' : substr($kode_barang, $pos + 1);
                if ($suf === '' || !ctype_digit($suf)) break;
                $kode_barang = substr($kode_barang, 0, $pos + 1) . str_pad((string) ((int) $suf + 1), strlen($suf), '0', STR_PAD_LEFT);
                $tries++;
            }
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
            ? ['status' => 'success', 'message' => $auto ? 'Barang persediaan berhasil ditambahkan! (kode otomatis: ' . $kode_barang . ')' : 'Barang persediaan berhasil ditambahkan!']
            : ['status' => 'error', 'message' => 'Gagal menambahkan barang!']);
    }

    public function update() {
        header('Content-Type: application/json');

        $this->form_validation->set_rules('id', 'ID', 'required|numeric');
        $this->form_validation->set_rules('kategori_id', 'Kategori', 'required|numeric');
        $this->form_validation->set_rules('kode_barang', 'Kode Barang', 'trim|max_length[50]');
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

        $lama = $this->Barang_persediaan_model->get_by_id($id);
        if (!$lama) {
            echo json_encode(['status' => 'error', 'message' => 'Data barang tidak ditemukan!']);
            return;
        }
        if ($kode_barang === '' || $kode_barang === '-') $kode_barang = $lama->kode_barang;

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
