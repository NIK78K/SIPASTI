<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * ============================================================
 *  SIPASTI — Auth Controller (Queue Version)
 *  File   : application/controllers/Auth.php
 *
 *  Perubahan dari versi sebelumnya:
 *  - _send_email() push ke queue, tidak kirim langsung
 *  - Tidak ada lagi die() saat email gagal
 *  - Alur user tidak pernah terganggu masalah SMTP
 * ============================================================
 */

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->model('Email_queue_model');
        $this->load->helper('email');
    }

    /* ===================== HELPER EMAIL =====================
     * Push ke queue — tidak kirim langsung.
     * Return TRUE selalu karena hanya insert DB.
    ========================================================= */
    private function _send_email($to, $subject, $message)
    {
        return $this->Email_queue_model->push($to, $subject, $message);
    }

    /* ===================== LOGIN ===================== */
    public function login()
    {
        if ($this->session->userdata('logged_in')) {
            $this->_redirect_by_role();
            return;
        }

        if ($this->input->post()) {

            $email    = $this->input->post('email', TRUE);
            $password = md5($this->input->post('password'));

            $user = $this->Auth_model->check_login($email, $password);

            if (!$user) {
                $this->session->set_flashdata('error', 'Email atau password salah');
                redirect('index.php/auth/login');
                return;
            }

            if ($user['is_verified'] == 0) {
                $this->session->set_flashdata('error', 'Akun belum diverifikasi. Silakan cek email.');
                redirect('index.php/auth/login');
                return;
            }

            $this->session->set_userdata([
                'user_id'   => $user['id'],
                'name'      => $user['name'],
                'email'     => $user['email'],
                'role'      => $user['role'],
                'avatar'    => $user['avatar'],
                'logged_in' => TRUE
            ]);

            $this->_redirect_by_role();
            return;
        }

        $this->load->view('auth/login');
    }

    /* ===================== REGISTER ===================== */
    public function register()
    {
        if ($this->session->userdata('logged_in')) {
            $this->_redirect_by_role();
            return;
        }

        if ($this->input->post()) {

            if ($this->input->post('password') !== $this->input->post('password2')) {
                $this->session->set_flashdata('error', 'Password tidak sama');
                redirect('index.php/auth/register');
                return;
            }

            if (!preg_match('/^[0-9]{18}$/', $this->input->post('nip'))) {
                $this->session->set_flashdata('error', 'NIP harus 18 digit angka');
                redirect('index.php/auth/register');
                return;
            }

            if (!preg_match('/^08[0-9]{8,11}$/', $this->input->post('hp'))) {
                $this->session->set_flashdata('error', 'No WhatsApp tidak valid');
                redirect('index.php/auth/register');
                return;
            }

            $email = $this->input->post('email', TRUE);
            if ($this->db->where('email', $email)->get('users')->row()) {
                $this->session->set_flashdata('error', 'Email sudah terdaftar');
                redirect('index.php/auth/register');
                return;
            }

            $code    = rand(100000, 999999);
            $expired = date('Y-m-d H:i:s', strtotime('+24 hours'));

            $this->db->trans_begin();

            $this->db->insert('users', [
                'name'                 => $this->input->post('name', TRUE),
                'email'                => $email,
                'password'             => md5($this->input->post('password')),
                'role'                 => 'user',
                'avatar'               => 'default.png',
                'is_verified'          => 0,
                'verification_code'    => $code,
                'verification_expired' => $expired,
                'created_at'           => date('Y-m-d H:i:s')
            ]);

            $user_id = $this->db->insert_id();

            $this->db->insert('user_profiles', [
                'user_id'    => $user_id,
                'nip'        => $this->input->post('nip'),
                'phone'      => $this->input->post('hp'),
                'unit_kerja' => $this->input->post('unit_kerja', TRUE),
                'created_at' => date('Y-m-d H:i:s')
            ]);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error', 'Registrasi gagal, coba lagi');
                redirect('index.php/auth/register');
                return;
            }

            $this->db->trans_commit();

            $nama       = $this->input->post('name', TRUE);
            $nip        = $this->input->post('nip');
            $hp         = $this->input->post('hp');
            $unit       = $this->input->post('unit_kerja', TRUE);
            $tgl_daftar = date('d-m-Y H:i:s');

            // Push ke queue — dijamin tidak gagal di sini
            $this->_send_email(
                $email,
                'Kode Verifikasi SIPASTI BPS Riau',
                "
                    <p>Halo <b>$nama</b>,</p>
                    <p>Gunakan kode berikut untuk verifikasi akun SIPASTI Anda:</p>
                    <h2 style='letter-spacing:4px;color:#0B2E6B;'>$code</h2>
                    <p>Kode berlaku selama <b>10 menit</b>.</p>
                    <p>Jika Anda tidak merasa mendaftar, abaikan email ini.</p>
                    <br><small>SIPASTI - BPS Provinsi Riau</small>
                "
            );

            $this->_send_email(
                'ipds1400@bps.go.id',
                '[SIPASTI] Pendaftaran Akun Baru - ' . $nama,
                "
                    <h3 style='color:#0B2E6B;'>Pendaftaran Akun Baru - SIPASTI</h3>
                    <table border='1' cellpadding='8' cellspacing='0'
                           style='border-collapse:collapse;font-size:14px;width:100%;max-width:500px;'>
                        <tr><td style='background:#f0f4ff;width:140px;'><b>Nama</b></td><td>$nama</td></tr>
                        <tr><td style='background:#f0f4ff;'><b>NIP</b></td><td>$nip</td></tr>
                        <tr><td style='background:#f0f4ff;'><b>Email</b></td><td>$email</td></tr>
                        <tr><td style='background:#f0f4ff;'><b>No. WhatsApp</b></td><td>$hp</td></tr>
                        <tr><td style='background:#f0f4ff;'><b>Unit Kerja</b></td><td>$unit</td></tr>
                        <tr><td style='background:#f0f4ff;'><b>Tanggal Daftar</b></td><td>$tgl_daftar</td></tr>
                    </table>
                "
            );

            $this->session->set_userdata('verify_email', $email);
            $this->session->set_flashdata(
                'info',
                'Kode verifikasi sedang dikirim ke email Anda. Jika belum muncul dalam 2 menit, gunakan tombol Kirim Ulang.'
            );
            redirect('index.php/auth/verify_code');
            return;
        }

        $this->load->view('auth/register');
    }

    /* ===================== VERIFY CODE ===================== */
    public function verify_code()
    {
        if (!$this->session->userdata('verify_email')) {
            redirect('index.php/auth/register');
            return;
        }

        if ($this->input->post()) {

            $email = $this->session->userdata('verify_email');
            $code  = $this->input->post('code');

            $user = $this->db
                ->where('email', $email)
                ->where('verification_code', $code)
                ->where('verification_expired >=', date('Y-m-d H:i:s'))
                ->get('users')
                ->row();

            if (!$user) {
                $this->session->set_flashdata('error', 'Kode verifikasi salah atau kadaluarsa');
                redirect('index.php/auth/verify_code');
                return;
            }

            $this->db->where('id', $user->id)->update('users', [
                'is_verified'          => 1,
                'verification_code'    => NULL,
                'verification_expired' => NULL
            ]);

            $this->session->unset_userdata('verify_email');
            $this->session->set_flashdata('success', 'Akun berhasil diverifikasi. Silakan login.');
            redirect('index.php/auth/login');
            return;
        }

        $this->load->view('auth/verify_code');
    }

    /* ===================== RESEND VERIFY CODE ===================== */
    public function resend_verify_code()
    {
        if (!$this->session->userdata('verify_email')) {
            redirect('index.php/auth/register');
            return;
        }

        $email = $this->session->userdata('verify_email');
        $user  = $this->db->where('email', $email)->get('users')->row();

        if (!$user) {
            redirect('index.php/auth/register');
            return;
        }

        if ($user->verification_expired && strtotime($user->verification_expired) > time()) {
            $this->session->set_flashdata('error', 'Kode masih berlaku, silakan cek email atau folder Spam');
            redirect('index.php/auth/verify_code');
            return;
        }

        $code    = rand(100000, 999999);
        $expired = date('Y-m-d H:i:s', strtotime('+10 minutes'));

        $this->db->where('id', $user->id)->update('users', [
            'verification_code'    => $code,
            'verification_expired' => $expired
        ]);

        $this->_send_email(
            $email,
            'Kode Verifikasi Baru - SIPASTI',
            "
                <p>Halo,</p>
                <p>Kode verifikasi baru Anda:</p>
                <h2 style='letter-spacing:4px;color:#0B2E6B;'>$code</h2>
                <p>Berlaku <b>10 menit</b>.</p>
            "
        );

        $this->session->set_flashdata('success', 'Kode baru sedang dikirim ke email Anda');
        redirect('index.php/auth/verify_code');
    }

    /* ===================== VERIFY TOKEN (lama) ===================== */
    public function verify($token)
    {
        $user = $this->db->where('verification_token', $token)->get('users')->row();

        if (!$user) show_error('Token verifikasi tidak valid');

        $this->db->where('id', $user->id)->update('users', [
            'is_verified'        => 1,
            'verification_token' => NULL
        ]);

        $this->session->set_flashdata('success', 'Email berhasil diverifikasi. Silakan login.');
        redirect('index.php/auth/login');
    }

    /* ===================== LOGOUT ===================== */
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('index.php/auth/login');
        exit;
    }

    /* ===================== FORGOT PASSWORD ===================== */
    public function forgot_password()
    {
        $this->load->view('auth/forgot_password');
    }

    public function send_reset_code()
    {
        if (!$this->input->post()) {
            redirect('index.php/auth/forgot_password');
            return;
        }

        $email = $this->input->post('email', TRUE);
        $user  = $this->db->where('email', $email)->get('users')->row();

        if (!$user) {
            $this->session->set_flashdata('error', 'Akun tidak terdaftar. Silakan registrasi terlebih dahulu.');
            redirect('index.php/auth/login');
            return;
        }

        $code    = rand(100000, 999999);
        $expired = date('Y-m-d H:i:s', strtotime('+10 minutes'));

        $this->db->where('id', $user->id)->update('users', [
            'reset_code'    => $code,
            'reset_expired' => $expired
        ]);

        $this->_send_email(
            $email,
            'Kode Reset Password - SIPASTI',
            "
                <p>Halo,</p>
                <p>Kode reset password Anda:</p>
                <h2 style='letter-spacing:3px;color:#0B2E6B;'>$code</h2>
                <p>Kode berlaku <b>10 menit</b>.</p>
                <p>Jika Anda tidak meminta reset, abaikan email ini.</p>
            "
        );

        $this->session->set_userdata('reset_email', $email);
        $this->session->set_flashdata('info', 'Kode reset sedang dikirim ke email Anda.');
        redirect('index.php/auth/reset_password');
    }

    /* ===================== RESET PASSWORD ===================== */
    public function reset_password()
    {
        $this->load->view('auth/reset_password');
    }

    public function update_password()
    {
        if (!$this->session->userdata('reset_email')) {
            redirect('index.php/auth/forgot_password');
            return;
        }

        $email    = $this->session->userdata('reset_email');
        $code     = $this->input->post('code');
        $password = $this->input->post('password');

        $user = $this->db
            ->where('email', $email)
            ->where('reset_code', $code)
            ->where('reset_expired >=', date('Y-m-d H:i:s'))
            ->get('users')
            ->row();

        if (!$user) {
            $this->session->set_flashdata('error', 'Kode salah atau sudah kadaluarsa');
            redirect('index.php/auth/reset_password');
            return;
        }

        $this->db->where('id', $user->id)->update('users', [
            'password'      => md5($password),
            'reset_code'    => NULL,
            'reset_expired' => NULL
        ]);

        $this->session->unset_userdata('reset_email');
        $this->session->set_flashdata('success', 'Password berhasil direset. Silakan login.');
        redirect('index.php/auth/login');
    }

    public function resend_reset_code()
    {
        if (!$this->session->userdata('reset_email')) {
            redirect('index.php/auth/forgot_password');
            return;
        }

        $email = $this->session->userdata('reset_email');
        $user  = $this->db->where('email', $email)->get('users')->row();

        if (!$user) {
            redirect('index.php/auth/forgot_password');
            return;
        }

        if ($user->reset_expired && strtotime($user->reset_expired) > time()) {
            $this->session->set_flashdata('error', 'Kode masih berlaku, silakan cek email');
            redirect('index.php/auth/reset_password');
            return;
        }

        $code    = rand(100000, 999999);
        $expired = date('Y-m-d H:i:s', strtotime('+10 minutes'));

        $this->db->where('id', $user->id)->update('users', [
            'reset_code'    => $code,
            'reset_expired' => $expired
        ]);

        $this->_send_email(
            $email,
            'Kode Reset Password (Baru) - SIPASTI',
            "
                <p>Kode reset password baru Anda:</p>
                <h2 style='letter-spacing:3px;'>$code</h2>
                <p>Berlaku 10 menit.</p>
            "
        );

        $this->session->set_flashdata('success', 'Kode baru sedang dikirim ke email Anda');
        redirect('index.php/auth/reset_password');
    }

    /* ===================== REDIRECT ROLE ===================== */
private function _redirect_by_role()
{
    switch ($this->session->userdata('role')) {
        case 'superadmin':
        case 'admin':    redirect('index.php/dashboard');      break;
        case 'teknisi':  redirect('index.php/teknisi_panel');  break;
        default:         redirect('index.php/user/dashboard'); break;
    }
    exit;
}

   public function switch_role()
{
    if (!$this->session->userdata('logged_in')) {
        redirect('index.php/auth/login'); exit;
    }

    $target_role = $this->input->post('role');
    $user_id     = $this->session->userdata('user_id');

    $user = $this->db->where('id', $user_id)->get('users')->row_array();
    if (!$user) { redirect('index.php/auth/login'); exit; }

    // Kumpulkan semua role yang boleh dimiliki user ini
    $base_role   = $user['role'];           // role utama (e.g. 'admin')
    $extra_roles = [];

    if (!empty($user['extra_roles'])) {
        $decoded = json_decode($user['extra_roles'], true);
        if (is_array($decoded)) {
            $extra_roles = $decoded;
        }
    }

    // Gabungkan: role utama + extra roles
    $allowed = array_unique(array_merge([$base_role], $extra_roles));

    if (!in_array($target_role, $allowed)) {
        $this->session->set_flashdata('error', 'Role tidak valid.');
        $this->_redirect_by_role(); exit;
    }

    // Simpan role asli (bukan previous) supaya bisa balik ke role utama
    $this->session->set_userdata('original_role', $base_role);
    $this->session->set_userdata('previous_role', $this->session->userdata('role'));
    $this->session->set_userdata('role', $target_role);

    $this->_redirect_by_role();
    exit;
}

}