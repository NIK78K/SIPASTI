<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class R extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('route');
        
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }
    
    public function index($encrypted_path = '') {
        if (empty($encrypted_path)) {
            show_error('Invalid URL', 400);
            return;
        }
        
        // Dekripsi
        $original_path = decrypt_route($encrypted_path);
        
        if ($original_path === false || empty($original_path)) {
            log_message('error', 'Failed to decrypt: ' . $encrypted_path);
            show_error('Invalid or expired link', 403);
            return;
        }
        
        // ✅ Simpan encrypted URL di session
        $this->session->set_userdata('encrypted_url', 'r/' . $encrypted_path);
        
        // Redirect biasa
        redirect($original_path);
    }
}