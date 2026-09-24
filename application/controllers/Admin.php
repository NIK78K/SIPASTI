<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin_model');
    }

    // READ - Tampilkan halaman admin
    public function index()
    {
        $data['admins'] = $this->Admin_model->get_all();
        $data['users'] = $this->Admin_model->get_non_admin_users();

        $this->load->view('layout/header');
        $this->load->view('layout/sidebar');
        $this->load->view('admin/index', $data);
        $this->load->view('layout/footer');
    }

    // CREATE - Buat admin baru
    public function create()
    {
        if ($this->input->post()) {
            $this->Admin_model->insert([
                'name'        => $this->input->post('name'),
                'email'       => $this->input->post('email'),
                'password'    => md5($this->input->post('password')),
                'role'        => $this->input->post('role'),
                'is_verified' => 1
            ]);
            
            $this->session->set_flashdata('success', 'Admin berhasil ditambahkan!');
            redirect('index.php/admin');
        }

        // PENTING: Load view create dengan data users
        $data['users'] = $this->Admin_model->get_non_admin_users();
        $this->load->view('admin/create', $data);
    }

    // PROMOTE - Promosikan user jadi admin
    public function promote()
    {
        if ($this->input->post()) {
            $user_id = $this->input->post('user_id');
            $new_role = $this->input->post('role');
            
            $user = $this->Admin_model->get_by_id($user_id);
            
            if (!$user) {
                $this->session->set_flashdata('error', 'User tidak ditemukan!');
                redirect('index.php/admin');
            }
            
            $this->Admin_model->update($user_id, [
                'role' => $new_role
            ]);
            
            $this->session->set_flashdata('success', $user['name'] . ' berhasil dipromosikan menjadi ' . $new_role . '!');
            redirect('index.php/admin');
        }
    }

    // EDIT - Edit admin
    public function edit($id)
    {
        if ($this->input->post()) {
            $data = [
                'name'  => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'role'  => $this->input->post('role'),
            ];

            $password = $this->input->post('password');
            if ($password && !empty(trim($password))) {
                $data['password'] = md5($password);
            }

            $result = $this->Admin_model->update($id, $data);
            
            // CRITICAL: Clear all output buffers
            while (ob_get_level()) {
                ob_end_clean();
            }
            
            // Set header JSON
            header('Content-Type: application/json');
            
            // Output JSON dan LANGSUNG EXIT
            echo json_encode([
                'success' => $result ? true : false,
                'message' => $result ? 'Data admin berhasil diupdate!' : 'Gagal update data!'
            ]);
            exit;
        }

        // Load view untuk modal
        $data['admin'] = $this->Admin_model->get_by_id($id);
        
        if (!$data['admin']) {
            show_404();
        }
        
        $this->load->view('admin/edit', $data);
    }

    // DELETE - Hapus admin
    public function delete($id)
    {
        $this->Admin_model->delete($id);
        $this->session->set_flashdata('success', 'Admin berhasil dihapus!');
        redirect('index.php/admin');
    }
}