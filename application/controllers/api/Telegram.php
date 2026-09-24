<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Telegram extends CI_Controller {

    public function register()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        show_404();
    }

    $telegram_id = $this->input->post('telegram_id');
    $name        = $this->input->post('name');
    $username    = $this->input->post('username');

    if (!$telegram_id) {
        echo json_encode([
            'status' => false,
            'message' => 'telegram_id kosong'
        ]);
        return;
    }

    // cek user sudah ada
    $cek = $this->db->get_where('users', [
        'telegram_id' => $telegram_id
    ])->row();

    if ($cek) {
        echo json_encode([
            'status' => true,
            'message' => 'User sudah terdaftar'
        ]);
        return;
    }

    $this->db->insert('users', [
        'telegram_id' => $telegram_id,
        'name' => $name,
        'telegram_username' => $username,
        'role' => 'user',
        'created_at' => date('Y-m-d H:i:s')
    ]);

    echo json_encode([
        'status' => true,
        'message' => 'Registrasi berhasil'
    ]);
}

public function register_full()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') show_404();

    $telegram_id = $this->input->post('telegram_id');

    // 🔒 CEK TELEGRAM_ID DULU
    $existing = $this->db
        ->where('telegram_id', $telegram_id)
        ->get('users')
        ->row();

    if ($existing) {
        echo json_encode([
            'status' => true,
            'msg' => 'Akun Telegram sudah terdaftar'
        ]);
        return;
    }

    // === VALIDASI SINGKAT (SAMA KAYA AUTH) ===
    if (!filter_var($this->input->post('email'), FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status'=>false,'msg'=>'Email tidak valid']); return;
    }

    // === INSERT USERS ===
    $this->db->insert('users', [
        'telegram_id' => $telegram_id,
        'name'        => $this->input->post('name'),
        'telegram_username' => $this->input->post('username'),
        'email'       => $this->input->post('email'),
        'password'    => md5($this->input->post('password')),
        'role'        => 'user',
        'avatar'      => 'default.png',
        'is_verified' => 1,
        'created_at'  => date('Y-m-d H:i:s')
    ]);

    $user_id = $this->db->insert_id();

    // === INSERT PROFILE ===
    $this->db->insert('user_profiles', [
        'user_id'    => $user_id,
        'nip'        => $this->input->post('nip'),
        'phone'      => $this->input->post('hp'),
        'unit_kerja' => $this->input->post('unit_kerja'),
        'alamat'     => $this->input->post('alamat'),
        'created_at' => date('Y-m-d H:i:s')
    ]);

    echo json_encode([
        'status' => true,
        'msg' => 'Registrasi berhasil'
    ]);
}

public function create_ticket()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') show_404();

    $telegram_id = $this->input->post('telegram_id');
    $kategori    = $this->input->post('kategori');

    $user = $this->db->where('telegram_id',$telegram_id)->get('users')->row();
    if (!$user) {
        echo json_encode(['status'=>false,'msg'=>'User tidak ditemukan']);
        return;
    }

    // === GENERATE KODE TIKET ===
    $kode = 'TKT-' . date('YmdHis');

    $data = [
        'user_id'    => $user->id,
        'kode_tiket' => $kode,
        'kategori'   => $kategori,
        'pelapor'    => $user->name,
        'status'     => 'Menunggu',
        'created_at' => date('Y-m-d H:i:s')
    ];

    // === KATEGORI LOGIC ===
    if ($kategori === 'kerusakan') {
        $data += [
            'perangkat_id'    => $this->input->post('perangkat_id'),
            'jenis_kerusakan' => $this->input->post('jenis_kerusakan'),
            'lokasi'          => $this->input->post('lokasi')
        ];
    }

    if ($kategori === 'pendamping') {
        $data += [
            'lokasi'    => $this->input->post('lokasi'),
            'tanggal'   => $this->input->post('tanggal'),
            'jam'       => $this->input->post('jam'),
            'kebutuhan' => $this->input->post('kebutuhan')
        ];
    }

    if ($kategori === 'layanan') {
        $data += [
            'tanggal'         => $this->input->post('tanggal'),
            'jam'             => $this->input->post('jam'),
            'kebutuhan'       => $this->input->post('kebutuhan'),
            'jenis_layanan'   => $this->input->post('jenis_layanan'),
            'layanan_link'    => $this->input->post('layanan_link'),
            'layanan_catatan' => $this->input->post('layanan_catatan')
        ];
    }

    $this->db->insert('tickets', $data);

    echo json_encode([
        'status' => true,
        'msg'    => 'Tiket berhasil dibuat',
        'kode'   => $kode
    ]);
}


public function check_user()
{
    $telegram_id = $this->input->get('telegram_id');

    $user = $this->db
        ->where('telegram_id', $telegram_id)
        ->get('users')
        ->row();

    if ($user) {
        echo json_encode([
            'status' => true,
            'name' => $user->name
        ]);
    } else {
        echo json_encode(['status' => false]);
    }
}

}
