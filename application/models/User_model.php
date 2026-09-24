<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function get_users_with_ticket_count()
    {
        return $this->db
            ->select('u.id, u.name, u.email, u.telegram_id, u.is_verified,
                      COALESCE(up.nip, "") AS nip,
                      COALESCE(up.unit_kerja, "") AS unit_kerja,
                      COUNT(t.id) AS total_tiket')
            ->from('users u')
            ->join('user_profiles up', 'up.user_id = u.id', 'left')
            ->join('tickets t', 't.user_id = u.id', 'left')
            ->where('u.role', 'user')
            ->group_by('u.id, u.name, u.email, u.telegram_id, u.is_verified, up.nip, up.unit_kerja')
            ->order_by('u.name', 'ASC')
            ->get()
            ->result_array();
    }

    public function count_user()
    {
        return $this->db->where('role', 'user')->count_all_results('users');
    }

    public function insert_user($data)
    {
        return $this->db->insert('users', $data);
    }

    public function get_by_id($id)
    {
        return $this->db
            ->select('u.*, COALESCE(up.nip, "") AS nip,
                      COALESCE(up.unit_kerja, "") AS unit_kerja,
                      COALESCE(up.phone, "") AS phone')
            ->from('users u')
            ->join('user_profiles up', 'up.user_id = u.id', 'left')
            ->where('u.id', $id)
            ->get()
            ->row_array();
    }

    public function update_user($id, $data)
    {
        return $this->db->where('id', $id)->update('users', $data);
    }

    public function delete_user($id)
    {
        return $this->db->where('id', $id)->delete('users');
    }

    public function toggle_status($id)
    {
        $user = $this->db->select('is_verified')->where('id', $id)->get('users')->row_array();
        if (!$user) return false;

        $new_status = $user['is_verified'] == 1 ? 0 : 1;
        return $this->db->where('id', $id)->update('users', ['is_verified' => $new_status]);
    }

    public function get_tickets_by_user($user_id)
    {
        return $this->db
            ->select('id, kode_tiket, jenis_kerusakan, status, created_at')
            ->from('tickets')
            ->where('user_id', $user_id)
            ->order_by('created_at', 'DESC')
            ->get()
            ->result_array();
    }

    public function get_telegram_users()
    {
        return $this->db
            ->select('u.id, u.name, u.email')
            ->from('users u')
            ->where('u.role', 'user')
            ->where('u.id NOT IN (SELECT user_id FROM teknisi)', null, false)
            ->order_by('u.name', 'ASC')
            ->get()
            ->result_array();
    }
}