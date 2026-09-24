<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sub_kategori extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        if (
            !$this->session->userdata('logged_in') ||
            !in_array($this->session->userdata('role'), ['admin','superadmin'])
        ) {
            redirect('auth/login');
        }

        $this->load->database();
        $this->load->helper(['url','text']);
    }

    /* ================= INDEX ================= */
    public function index()
    {
        $this->load->view('layout/header');
        $this->load->view('layout/sidebar');
        $this->load->view('admin/master_kategori/sub_kategori');
        $this->load->view('layout/footer');
    }

    /* ================= FETCH ================= */
    public function fetch()
    {
        $data = $this->db
            ->select('
                s.id,
                s.kategori_id,
                s.nama_sub_kategori,
                s.slug,
                s.perlu_jadwal,
                k.nama_kategori
            ')
            ->from('master_sub_kategori s')
            ->join('master_kategori k', 'k.id = s.kategori_id')
            ->order_by('s.id','ASC')
            ->get()
            ->result();

        echo json_encode($data);
    }

    /* ================= STORE ================= */
    public function store()
    {
        $kategori_id = $this->input->post('kategori_id');
        $nama = trim($this->input->post('nama_sub_kategori'));
        $perlu_jadwal = $this->input->post('perlu_jadwal') ? 1 : 0; // ⬅️ TAMBAHAN

        if (!$kategori_id || $nama == '') {
            echo json_encode([
                'status'=>'error',
                'message'=>'Semua field wajib diisi'
            ]);
            return;
        }

        $this->db->insert('master_sub_kategori', [
            'kategori_id' => $kategori_id,
            'nama_sub_kategori' => $nama,
            'slug' => url_title($nama, '-', TRUE),
            'perlu_jadwal' => $perlu_jadwal, // ⬅️ TAMBAHAN
            'created_at' => date('Y-m-d H:i:s')
        ]);

        echo json_encode([
            'status'=>'success',
            'message'=>'Sub kategori berhasil ditambahkan'
        ]);
    }

    /* ================= UPDATE ================= */
    public function update($id)
    {
        $kategori_id = $this->input->post('kategori_id');
        $nama = trim($this->input->post('nama_sub_kategori'));
        $perlu_jadwal = $this->input->post('perlu_jadwal') ? 1 : 0; // ⬅️ TAMBAHAN

        if (!$kategori_id || $nama == '') {
            echo json_encode([
                'status'=>'error',
                'message'=>'Semua field wajib diisi'
            ]);
            return;
        }

        $this->db->where('id',$id)->update('master_sub_kategori', [
            'kategori_id' => $kategori_id,
            'nama_sub_kategori' => $nama,
            'slug' => url_title($nama, '-', TRUE),
            'perlu_jadwal' => $perlu_jadwal // ⬅️ TAMBAHAN
        ]);

        echo json_encode([
            'status'=>'success',
            'message'=>'Sub kategori berhasil diperbarui'
        ]);
    }

    /* ================= DELETE ================= */
    public function delete($id)
    {
        $this->db->where('id',$id)->delete('master_sub_kategori');

        echo json_encode([
            'status'=>'success',
            'message'=>'Sub kategori berhasil dihapus'
        ]);
    }
}