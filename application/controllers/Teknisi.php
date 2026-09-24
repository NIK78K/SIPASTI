<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teknisi extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        // HARUS LOGIN
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }

        // HANYA ADMIN & SUPERADMIN
        if (!in_array($this->session->userdata('role'), ['admin','superadmin'])) {
            show_error('Akses ditolak', 403);
        }

        $this->load->model('Teknisi_model');
        $this->load->model('User_model');
    }

    public function index()
    {
        $data['teknisi']   = $this->Teknisi_model->get_all();
        $data['available'] = $this->Teknisi_model->count_by_status('available');
        $data['busy']      = $this->Teknisi_model->count_by_status('busy');
        $data['offline']   = $this->Teknisi_model->count_by_status('offline');

        $this->load->view('layout/header');
        $this->load->view('layout/sidebar');
        $this->load->view('teknisi/index', $data);
        $this->load->view('layout/footer');
    }

   public function create()
{
    if ($this->input->post()) {

        $user_id = $this->input->post('user_id');

        // 1️⃣ INSERT KE TABEL TEKNISI
        $this->Teknisi_model->insert([
            'user_id'  => $user_id,
            'keahlian' => $this->input->post('keahlian'),
            'status'   => $this->input->post('status') // available|busy|offline
        ]);

        // 2️⃣ UPDATE ROLE USER JADI TEKNISI
        $this->db
            ->where('id', $user_id)
            ->update('users', ['role' => 'teknisi']);

        redirect('index.php/teknisi');
    }

    $data['users'] = $this->User_model->get_telegram_users();
    $this->load->view('teknisi/modal_create', $data);
}


    public function edit($id)
    {
        $data['teknisi'] = $this->Teknisi_model->get_by_id($id);
        $this->load->view('teknisi/modal_edit', $data);
    }

public function update($id)
{
    $this->Teknisi_model->update($id, [
        'status' => strtolower($this->input->post('status'))
    ]);

    header('Content-Type: application/json');
    echo json_encode(['status' => 'success']);
    exit;
}




   public function delete($id)
{
    // ambil user_id dulu
    $teknisi = $this->Teknisi_model->get_by_id($id);

    if ($teknisi) {
        // balikin role jadi user
        $this->db
            ->where('id', $teknisi['user_id'])
            ->update('users', ['role' => 'user']);
    }

    $this->Teknisi_model->delete($id);
    redirect('index.php/teknisi');
}

public function tiket_aktif($teknisi_id)
{
    $this->load->model('Ticket_model');
    
    $data['teknisi'] = $this->Teknisi_model->get_by_id($teknisi_id);
    $data['tiket'] = $this->db
        ->select('
            t.id,
            t.kode_tiket,
            t.status,
            t.created_at,
            t.lokasi,
            t.jenis_kerusakan,
            u.name AS pelapor,
            mk.nama_kategori,
            mk.slug AS kategori_slug,      // ← TAMBAH slug
            sub.nama_sub_kategori
        ')
        ->from('tickets t')
        ->join('users u', 'u.id = t.user_id', 'left')
        ->join('master_kategori mk', 'mk.id = t.kategori_id', 'left')
        ->join('master_sub_kategori sub', 'sub.id = t.sub_kategori_id', 'left')
        ->where('t.teknisi_id', $teknisi_id)
        ->where('t.status', 'On Progress')
        ->order_by('t.created_at', 'DESC')
        ->get()
        ->result_array();

    // ← TAMBAH: inject judul_tiket ke setiap tiket
    $this->load->model('Teknisi_panel');  // tidak perlu load, pakai fungsi statis di bawah
    foreach ($data['tiket'] as &$t) {
        $t['judul_tiket'] = $this->_parse_ticket_title(
            $t['jenis_kerusakan'],
            $t['kategori_slug'] ?? ''
        );
    }
    unset($t);
    
    $this->load->view('teknisi/modal_tiket_aktif', $data);
}

// ← TAMBAH helper langsung di Teknisi.php juga
private function _parse_ticket_title($jenis_kerusakan, $kategori_slug)
{
    if (in_array(strtolower($kategori_slug), ['gangguan', 'kerusakan'])) {
        return null;
    }

    if (empty($jenis_kerusakan)) return null;

    $labels = [
        'Keperluan / Agenda:',
        'Keperluan:',
        'Tujuan Perjalanan:',
        'Tujuan Peminjaman:',
        'Tujuan:',
        'Detail Permintaan:',
    ];

    foreach (explode("\n", $jenis_kerusakan) as $line) {
        $line = trim($line);
        foreach ($labels as $label) {
            if (stripos($line, $label) === 0) {
                $judul = trim(substr($line, strlen($label)));
                if ($judul !== '') return $judul;
            }
        }
    }

    return null;
}


}
