<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

    public function check_login($email, $password)
    {
        return $this->db
            ->where('email', $email)
            ->where('password', $password)
            ->where('is_verified', 1) // 🔥 WAJIB
            ->get('users')
            ->row_array();
    }
}
