<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function index()
    {
        $data['users']      = $this->User_model->get_users_with_ticket_count();
        $data['total_user'] = $this->User_model->count_user();

        $this->load->view('layout/header');
        $this->load->view('layout/sidebar');
        $this->load->view('user/index', $data);
        $this->load->view('layout/footer');
    }

    public function dashboard()
    {
        $this->load->view('layout/header_user');
        $this->load->view('user/dashboard');
        $this->load->view('layout/footer_user');
    }

    public function create_ticket()
    {
        $this->load->view('layout/header_user');
        $this->load->view('user/create_ticket');
        $this->load->view('layout/footer_user');
    }

    public function history()
    {
        redirect('index.php/user_ticket/history');
        exit;
    }

    public function store()
    {
        $email = $this->input->post('email');

        $exists = $this->db->where('email', $email)->count_all_results('users');
        if ($exists > 0) {
            $this->session->set_flashdata('error', 'Email <strong>' . htmlspecialchars($email) . '</strong> sudah terdaftar!');
            redirect('index.php/user');
            exit;
        }

        $this->db->trans_begin();

        $user_data = [
            'name'        => $this->input->post('name'),
            'email'       => $email,
            'password'    => md5($this->input->post('password')),
            'telegram_id' => $this->input->post('telegram_id') ?: null,
            'role'        => 'user',
            'is_verified' => 1,
            'avatar'      => 'default.png',
            'created_at'  => date('Y-m-d H:i:s')
        ];

        $this->db->insert('users', $user_data);
        $user_id = $this->db->insert_id();

        // Simpan NIP ke user_profiles
        $nip = $this->input->post('nip');
        $existing_profile = $this->db->where('user_id', $user_id)->get('user_profiles')->row();
        if ($existing_profile) {
            $this->db->where('user_id', $user_id)->update('user_profiles', [
                'nip'        => $nip ?: null,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            $this->db->insert('user_profiles', [
                'user_id'    => $user_id,
                'nip'        => $nip ?: null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Gagal menyimpan data, coba lagi.');
            redirect('index.php/user');
            exit;
        }

        $this->db->trans_commit();

        $this->session->set_flashdata('success', 'Pengguna <strong>' . htmlspecialchars($user_data['name']) . '</strong> berhasil ditambahkan!');
        redirect('index.php/user');
        exit;
    }

    public function tickets($user_id)
    {
        $tickets = $this->User_model->get_tickets_by_user($user_id);
        header('Content-Type: application/json');
        echo json_encode($tickets);
        exit;
    }

    // Toggle aktif / nonaktif
    public function toggle_status($id)
    {
        $result = $this->User_model->toggle_status($id);

        header('Content-Type: application/json');
        echo json_encode([
            'success' => $result ? true : false,
            'message' => $result ? 'Status berhasil diubah.' : 'Gagal mengubah status.'
        ]);
        exit;
    }

    public function edit($id)
    {
        if ($this->input->post()) {

            $email = $this->input->post('email');

            $exists = $this->db
                ->where('email', $email)
                ->where('id !=', $id)
                ->count_all_results('users');

            if ($exists > 0) {
                while (ob_get_level()) ob_end_clean();
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Email sudah digunakan user lain!']);
                exit;
            }

            $data = [
                'name'  => $this->input->post('name'),
                'email' => $email,
            ];

            $password = $this->input->post('password');
            if ($password && !empty(trim($password))) {
                $data['password'] = md5($password);
            }

            $this->db->trans_begin();

            $this->User_model->update_user($id, $data);

            // Update NIP di user_profiles
            $nip = $this->input->post('nip');
            $existing_profile = $this->db->where('user_id', $id)->get('user_profiles')->row();
            if ($existing_profile) {
                $this->db->where('user_id', $id)->update('user_profiles', [
                    'nip'        => $nip ?: null,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            } else {
                $this->db->insert('user_profiles', [
                    'user_id'    => $id,
                    'nip'        => $nip ?: null,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                while (ob_get_level()) ob_end_clean();
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Gagal update data!']);
                exit;
            }

            $this->db->trans_commit();

            while (ob_get_level()) ob_end_clean();
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Data user berhasil diupdate!']);
            exit;
        }

        $data['user'] = $this->User_model->get_by_id($id);
        if (!$data['user']) show_404();

        $this->load->view('user/modal_edit', $data);
    }

    public function delete($id)
    {
        $this->db->where('user_id', $id)->delete('user_profiles');
        $this->db->where('id', $id)->delete('users');
        $this->session->set_flashdata('success', 'Pengguna berhasil dihapus.');
        redirect('index.php/user');
        exit;
    }

    public function check_email()
    {
        $email   = $this->input->get('email');
        $exclude = (int)($this->input->get('exclude_id') ?: 0);

        $exists = $this->db
            ->where('email', $email)
            ->where('id !=', $exclude)
            ->count_all_results('users');

        header('Content-Type: application/json');
        echo json_encode(['exists' => $exists > 0]);
        exit;
    }

    public function profile()
    {
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) { redirect('index.php/auth/login'); exit; }

        $user = $this->db->where('id', $user_id)->get('users')->row_array();
        if (!$user) {
            $this->session->set_flashdata('error', 'Data user tidak ditemukan');
            redirect('index.php/user/dashboard'); exit;
        }

        if ($this->input->post()) {
            $new_email = $this->input->post('email', true);
            $email_exists = $this->db->where('email', $new_email)->where('id !=', $user_id)->count_all_results('users');
            if ($email_exists > 0) {
                $this->session->set_flashdata('error', 'Email sudah digunakan akun lain!');
                redirect('index.php/user/profile'); exit;
            }

            $data = ['name' => $this->input->post('name', true), 'email' => $new_email];

            $old_password = $this->input->post('old_password');
            $new_password = $this->input->post('new_password');
            if ($new_password) {
                if (!$old_password) {
                    $this->session->set_flashdata('error', 'Password lama wajib diisi');
                    redirect('index.php/user/profile'); exit;
                }
                if (md5($old_password) !== $user['password']) {
                    $this->session->set_flashdata('error', 'Password lama salah');
                    redirect('index.php/user/profile'); exit;
                }
                $data['password'] = md5($new_password);
            }

            if (!empty($_FILES['avatar']['name'])) {
                $upload_path = FCPATH . 'uploads/avatar/';
                if (!is_dir($upload_path)) mkdir($upload_path, 0755, true);
                $this->load->library('upload', [
                    'upload_path'   => $upload_path,
                    'allowed_types' => 'jpg|jpeg|png',
                    'max_size'      => 2048,
                    'encrypt_name'  => true
                ]);
                if ($this->upload->do_upload('avatar')) {
                    $avatar = $this->upload->data('file_name');
                    $data['avatar'] = $avatar;
                    $this->session->set_userdata('avatar', $avatar);
                    if (!empty($user['avatar']) && $user['avatar'] !== 'default.png' && file_exists($upload_path . $user['avatar'])) {
                        @unlink($upload_path . $user['avatar']);
                    }
                } else {
                    $this->session->set_flashdata('error', 'Upload gagal: ' . $this->upload->display_errors('', ''));
                    redirect('index.php/user/profile'); exit;
                }
            }

            $this->db->where('id', $user_id)->update('users', $data);
            $this->session->set_userdata(['name' => $data['name'], 'email' => $data['email']]);
            $this->session->set_flashdata('success', 'Profil berhasil diperbarui');
            redirect('index.php/user/profile'); exit;
        }

        $data['user'] = $user;
        $this->load->view('layout/header_user');
        $this->load->view('user/profile', $data);
        $this->load->view('layout/footer_user');
    }

    public function create_lainnya()
    {
        $this->db->where_not_in('slug', ['permintaan', 'kerusakan', 'kerusakaan']);
        $data['kategori']  = $this->db->get('master_kategori')->result();
        $data['perangkat'] = [];
        $this->load->view('layout/header_user');
        $this->load->view('user/create_lain', $data);
        $this->load->view('layout/footer_user');
    }

    public function get_sub_kategori($kategori_id)
    {
        $sub = $this->db->where('kategori_id', $kategori_id)->get('master_sub_kategori')->result();
        header('Content-Type: application/json');
        echo json_encode($sub);
        exit;
    }

    public function logout()
    {
        $this->session->sess_destroy();
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Pragma: no-cache');
        redirect('index.php/auth/login');
        exit;
    }

    public function zoom_jadwal_ajax()
{
    header('Content-Type: application/json');
 
    if (!$this->session->userdata('logged_in')) {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
        return;
    }
 
    $tanggal = $this->input->get('tanggal') ?: date('Y-m-d');
 
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
        $tanggal = date('Y-m-d');
    }
 
    $this->load->model('Zoom_model');
 
    $raw  = $this->Zoom_model->get_all_zoom_today($tanggal);
    $rows = [];
 
    foreach ($raw as $r) {
        $parsed = $this->Zoom_model->parse_zoom_detail($r['jenis_kerusakan'] ?? '');
 
        // URL dokumen peserta (null jika belum ada)
        $dokumen_url = null;
        if (!empty($r['dokumen_peserta'])) {
            $dokumen_url = base_url('uploads/dokumen_peserta/' . $r['dokumen_peserta']);
        }
 
        $rows[] = [
            'id'                => $r['id'],
            'kode_tiket'        => $r['kode_tiket'],
            'pelapor'           => $r['pelapor'],
            'tanggal'           => $r['tanggal'],
            'tanggal_selesai'   => $r['tanggal_selesai'],
            'jam'               => $r['jam'],
            'durasi'            => $r['durasi'],
            'status'            => $r['status'],
            'akun_zoom'         => $r['akun_zoom'],
            'zoom_group_id'     => $r['zoom_group_id'],
            'layanan_link'      => $r['layanan_link'] ?? '',
            'detail_permintaan' => $parsed['detail_permintaan'],
            'jumlah_peserta'    => $parsed['jumlah_peserta'],
            'jumlah_breakout'   => $parsed['jumlah_breakout'],
            // 🆕 TAMBAHAN
            'dokumen_peserta'   => $r['dokumen_peserta'] ?? null,
            'dokumen_url'       => $dokumen_url,
        ];
    }
 
    echo json_encode(['status' => 'success', 'rows' => $rows]);
}
}