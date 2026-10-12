<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori_persediaan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        // ================= PROTEKSI ADMIN =================
        if (
            !$this->session->userdata('logged_in') ||
            !in_array($this->session->userdata('role'), ['admin','superadmin'])
        ) {
            redirect('auth/login');
        }

        $this->load->database();
        $this->load->model('Kategori_persediaan_model');
        $this->load->helper(['text','url']);
    }

    /* ================= INDEX ================= */
    public function index()
    {
        $data['kategori'] = $this->Kategori_persediaan_model->get_all();

        $this->load->view('layout/header');
        $this->load->view('layout/sidebar');
        $this->load->view('admin/kategori_persediaan/index', $data);
        $this->load->view('layout/footer');
    }

    /* ================= FETCH (AJAX) ================= */
    public function fetch()
    {
        echo json_encode($this->Kategori_persediaan_model->get_all());
    }

    /* ================= STORE (AJAX) ================= */
    public function store()
    {
        $kode = trim($this->input->post('kode', TRUE));
        $nama = trim($this->input->post('nama', TRUE));
        $deskripsi = trim($this->input->post('deskripsi', TRUE));

        if ($kode == '' || $nama == '') {
            echo json_encode([
                'status' => 'error',
                'message' => 'Kode dan nama kategori wajib diisi'
            ]);
            return;
        }

        if ($this->Kategori_persediaan_model->is_kode_used($kode)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Kode kategori sudah digunakan'
            ]);
            return;
        }

        $this->Kategori_persediaan_model->insert([
            'kode' => $kode,
            'nama' => $nama,
            'deskripsi' => $deskripsi !== '' ? $deskripsi : NULL
        ]);

        echo json_encode([
            'status' => 'success',
            'message' => 'Master kategori barang persediaan berhasil ditambahkan'
        ]);
    }

    /* ================= EDIT (STUB -> MODAL DI MASTER BARANG) ================= */
    public function edit($id)
    {
        redirect('kategori_persediaan');
    }

    /* ================= GET (AJAX MODAL) ================= */
    public function get($id) {
        header('Content-Type: application/json');
        $kategori = $this->Kategori_persediaan_model->get_by_id($id);
        if ($kategori) {
            echo json_encode($kategori);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Data kategori tidak ditemukan!']);
        }
    }

    /* ================= UPDATE (AJAX) ================= */
    public function update($id)
    {
        header('Content-Type: application/json');
        $kode = trim($this->input->post('kode', TRUE));
        $nama = trim($this->input->post('nama', TRUE));
        $deskripsi = trim($this->input->post('deskripsi', TRUE));

        if ($kode == '' || $nama == '') {
            echo json_encode([
                'status' => 'error',
                'message' => 'Kode dan nama kategori wajib diisi'
            ]);
            return;
        }

        if ($this->Kategori_persediaan_model->is_kode_used($kode, $id)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Kode kategori sudah digunakan'
            ]);
            return;
        }

        $this->Kategori_persediaan_model->update($id, [
            'kode' => $kode,
            'nama' => $nama,
            'deskripsi' => $deskripsi !== '' ? $deskripsi : NULL
        ]);

        echo json_encode([
            'status' => 'success',
            'message' => 'Master kategori barang persediaan berhasil diperbarui'
        ]);
    }

    /* ================= DELETE (AJAX) ================= */
    public function delete($id)
    {
        $used = $this->Kategori_persediaan_model->count_barang_usage($id);

        if ($used > 0) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Kategori sudah dipakai ' . $used . ' barang persediaan'
            ]);
            return;
        }

        $this->Kategori_persediaan_model->delete($id);

        echo json_encode([
            'status' => 'success',
            'message' => 'Master kategori barang persediaan berhasil dihapus'
        ]);
    }
}
