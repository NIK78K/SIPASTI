<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {

    public function get_all()
    {
        return $this->db
            ->where_in('role', ['admin', 'superadmin'])
            ->order_by('created_at', 'DESC')
            ->get('users')
            ->result_array();
    }

    // TAMBAHAN: Ambil user non-admin untuk dropdown promosi
    public function get_non_admin_users()
    {
        return $this->db
            ->where_not_in('role', ['admin', 'superadmin'])
            ->order_by('name', 'ASC')
            ->get('users')
            ->result(); // pakai result() bukan result_array() biar bisa akses $user->name
    }

public function get_by_id($id)
{
    return $this->db
        ->where('id', $id)
        ->get('users')
        ->row_array(); // UBAH ke row_array() agar konsisten dengan view
}

    public function insert($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('users', $data);
    }

    public function update($id, $data)
    {
        return $this->db
            ->where('id', $id)
            ->update('users', $data); // hapus where_in role biar bisa update semua user
    }

    public function delete($id)
    {
        return $this->db
            ->where('id', $id)
            ->where_in('role', ['admin', 'superadmin'])
            ->delete('users');
    }
}