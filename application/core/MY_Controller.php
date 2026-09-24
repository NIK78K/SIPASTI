<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        // Halaman publik yang tidak perlu login
        $controller = $this->router->fetch_class();
        $public_controllers = ['auth'];

        if (!in_array($controller, $public_controllers)) {
           if (!$this->session->userdata('logged_in')) {

                // Kalau AJAX, kembalikan JSON
                if ($this->input->is_ajax_request()) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'status'   => 'session_expired',
                        'message'  => 'Sesi habis, silakan login kembali',
                        'redirect' => base_url('/auth/login')
                    ]);
                    exit;
                }

                // Request biasa
                $this->session->set_flashdata('error', 'Sesi Anda telah habis. Silakan login kembali.');
                redirect('auth/login');
                exit;
            }
        }

        $this->load->helper('route');
        $this->_rewrite_url_from_encrypted();
    }

    private function _rewrite_url_from_encrypted()
    {
        $encrypted = $this->session->userdata('encrypted_url');

        if ($encrypted) {
            $this->session->unset_userdata('encrypted_url');

            $script = "
            <script>
            (function() {
                const encUrl = '{$encrypted}';
                if (encUrl) {
                    const newUrl = '" . base_url() . "' + encUrl;
                    window.history.replaceState({}, '', newUrl);
                }
            })();
            </script>";

            $this->output->append_output($script);
        }
    }
}