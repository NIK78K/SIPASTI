<?php
// application/controllers/Master_ruang_rapat.php

defined('BASEPATH') OR exit('No direct script access allowed');

class Master_ruang_rapat extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Proteksi halaman — sesuaikan dengan helper auth proyek
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
        $this->load->model('Ruang_rapat_model');
    }

    // ── INDEX ─────────────────────────────────────────────

    public function index()
    {
        $data = [
            'title'   => 'Master Ruang Rapat',
            'ruangan' => $this->Ruang_rapat_model->get_all(),
        ];
        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar');
        $this->load->view('master_ruang_rapat/index', $data);
        $this->load->view('layout/footer');
    }

    // ── SIMPAN (tambah) ───────────────────────────────────

    public function simpan()
    {
        $fasilitas = $this->input->post('fasilitas');
        $fas_str   = !empty($fasilitas) ? implode(',', $fasilitas) : '';

        $data = [
            'nama_ruangan' => $this->input->post('nama_ruangan', true),
            'kapasitas'    => (int) $this->input->post('kapasitas'),
            'lokasi'       => $this->input->post('lokasi', true),
            'fasilitas'    => $fas_str,
            'status'       => $this->input->post('status', true),
            'keterangan'   => $this->input->post('keterangan', true),
        ];

        if (empty($data['nama_ruangan']) || $data['kapasitas'] < 1) {
            $this->session->set_flashdata('error', 'Nama ruangan dan kapasitas wajib diisi.');
            redirect('master_ruang_rapat');
        }

        $this->Ruang_rapat_model->insert($data);
        $this->session->set_flashdata('success', 'Ruangan berhasil ditambahkan.');
        redirect('master_ruang_rapat');
    }

    // ── UPDATE (edit) ─────────────────────────────────────

    public function update()
    {
        $id        = (int) $this->input->post('id');
        $fasilitas = $this->input->post('fasilitas');
        $fas_str   = !empty($fasilitas) ? implode(',', $fasilitas) : '';

        $data = [
            'nama_ruangan' => $this->input->post('nama_ruangan', true),
            'kapasitas'    => (int) $this->input->post('kapasitas'),
            'lokasi'       => $this->input->post('lokasi', true),
            'fasilitas'    => $fas_str,
            'status'       => $this->input->post('status', true),
            'keterangan'   => $this->input->post('keterangan', true),
        ];

        $this->Ruang_rapat_model->update($id, $data);
        $this->session->set_flashdata('success', 'Data ruangan berhasil diperbarui.');
        redirect('master_ruang_rapat');
    }

    // ── HAPUS ─────────────────────────────────────────────

    public function hapus($id)
    {
        $result = $this->Ruang_rapat_model->delete($id);
        if ($result === false) {
            $this->session->set_flashdata('error', 'Ruangan tidak bisa dihapus karena masih ada peminjaman aktif.');
        } else {
            $this->session->set_flashdata('success', 'Ruangan berhasil dihapus.');
        }
        redirect('master_ruang_rapat');
    }
}