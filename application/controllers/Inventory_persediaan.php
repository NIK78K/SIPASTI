<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory_persediaan extends CI_Controller {

    public function index()
    {
        $this->load->view('layout/header');
        $this->load->view('layout/sidebar');
        $this->load->view('inventory_persediaan/index');
        $this->load->view('layout/footer');
    }
}
