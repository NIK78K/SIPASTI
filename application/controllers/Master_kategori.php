<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_kategori extends CI_Controller {

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
        $this->load->helper(['text','url']);
    }

    /* ================= INDEX ================= */
    public function index()
    {
        $data['kategori'] = $this->db
            ->order_by('id','ASC')
            ->get('master_kategori')
            ->result();

        $this->load->view('layout/header');
        $this->load->view('layout/sidebar');
        $this->load->view('admin/master_kategori/index', $data);
        $this->load->view('layout/footer');
    }

    /* ================= FETCH (AJAX) ================= */
    public function fetch()
    {
        $data = $this->db
            ->order_by('id','ASC')
            ->get('master_kategori')
            ->result();

        echo json_encode($data);
    }

    /* ================= STORE (AJAX) ================= */
    public function store()
    {
        $nama = trim($this->input->post('nama_kategori', TRUE));

        if ($nama == '') {
            echo json_encode([
                'status' => 'error',
                'message' => 'Nama kategori wajib diisi'
            ]);
            return;
        }

        $this->db->insert('master_kategori', [
            'nama_kategori' => $nama,
            'slug' => url_title($nama, '-', TRUE)
        ]);

        echo json_encode([
            'status' => 'success',
            'message' => 'Kategori berhasil ditambahkan'
        ]);
    }

    /* ================= UPDATE (AJAX) ================= */
    public function update($id)
    {
        $nama = trim($this->input->post('nama_kategori', TRUE));

        if ($nama == '') {
            echo json_encode([
                'status' => 'error',
                'message' => 'Nama kategori tidak boleh kosong'
            ]);
            return;
        }

        $this->db->where('id', $id)->update('master_kategori', [
            'nama_kategori' => $nama,
            'slug' => url_title($nama, '-', TRUE)
        ]);

        echo json_encode([
            'status' => 'success',
            'message' => 'Kategori berhasil diperbarui'
        ]);
    }

    /* ================= DELETE (AJAX) ================= */
    public function delete($id)
    {
        // OPTIONAL: proteksi kalau sudah dipakai tiket
        $used = $this->db
            ->where('kategori_id', $id)
            ->count_all_results('tickets');

        if ($used > 0) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Kategori sudah digunakan pada tiket'
            ]);
            return;
        }

        $this->db->where('id', $id)->delete('master_kategori');

        echo json_encode([
            'status' => 'success',
            'message' => 'Kategori berhasil dihapus'
        ]);
    }
}
