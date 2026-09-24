<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_kendaraan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
        $this->load->model('Kendaraan_model');
    }

    // ── INDEX ──────────────────────────────────────────────

    public function index()
    {
        $data = [
            'title'      => 'Master Kendaraan',
            'kendaraan'  => $this->Kendaraan_model->get_all(),
        ];
        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar');
        $this->load->view('master_kendaraan/index', $data);
        $this->load->view('layout/footer');
    }

    // ── SIMPAN (tambah) ────────────────────────────────────

    public function simpan()
    {
        $fasilitas = $this->input->post('fasilitas');
        $fas_str   = !empty($fasilitas) ? implode(',', $fasilitas) : '';

        $data = [
            'nama_kendaraan' => $this->input->post('nama_kendaraan', true),
            'jenis'          => $this->input->post('jenis', true),          // Mobil / Motor
            'nomor_polisi'   => $this->input->post('nomor_polisi', true),
            'merek'          => $this->input->post('merek', true),
            'tahun'          => (int) $this->input->post('tahun'),
            'warna'          => $this->input->post('warna', true),
            'kapasitas_bahan_bakar' => (int) $this->input->post('kapasitas_bahan_bakar'),
            'fasilitas'      => $fas_str,
            'status'         => $this->input->post('status', true),         // Tersedia / Tidak Tersedia / Dalam Perbaikan
            'keterangan'     => $this->input->post('keterangan', true),
        ];

        if (empty($data['nama_kendaraan']) || empty($data['nomor_polisi'])) {
            $this->session->set_flashdata('error', 'Nama kendaraan dan nomor polisi wajib diisi.');
            redirect('master_kendaraan');
        }

        $this->Kendaraan_model->insert($data);
        $this->session->set_flashdata('success', 'Kendaraan berhasil ditambahkan.');
        redirect('master_kendaraan');
    }

    // ── UPDATE (edit) ──────────────────────────────────────

    public function update()
    {
        $id        = (int) $this->input->post('id');
        $fasilitas = $this->input->post('fasilitas');
        $fas_str   = !empty($fasilitas) ? implode(',', $fasilitas) : '';

        $data = [
            'nama_kendaraan' => $this->input->post('nama_kendaraan', true),
            'jenis'          => $this->input->post('jenis', true),
            'nomor_polisi'   => $this->input->post('nomor_polisi', true),
            'merek'          => $this->input->post('merek', true),
            'tahun'          => (int) $this->input->post('tahun'),
            'warna'          => $this->input->post('warna', true),
            'kapasitas_bahan_bakar' => (int) $this->input->post('kapasitas_bahan_bakar'),
            'fasilitas'      => $fas_str,
            'status'         => $this->input->post('status', true),
            'keterangan'     => $this->input->post('keterangan', true),
        ];

        $this->Kendaraan_model->update($id, $data);
        $this->session->set_flashdata('success', 'Data kendaraan berhasil diperbarui.');
        redirect('master_kendaraan');
    }

    // ── HAPUS ──────────────────────────────────────────────

    public function hapus($id)
    {
        $result = $this->Kendaraan_model->delete($id);
        if ($result === false) {
            $this->session->set_flashdata('error', 'Kendaraan tidak bisa dihapus karena masih ada peminjaman aktif.');
        } else {
            $this->session->set_flashdata('success', 'Kendaraan berhasil dihapus.');
        }
        redirect('master_kendaraan');
    }
}