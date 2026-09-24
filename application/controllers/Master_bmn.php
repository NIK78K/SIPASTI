<?php
// application/controllers/Master_bmn.php

defined('BASEPATH') OR exit('No direct script access allowed');

class Master_bmn extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
        $this->load->model('Master_bmn_model');
    }

    // ══════════════════════════════════════════════════════
    //  INDEX
    // ══════════════════════════════════════════════════════
    public function index()
    {
        // Ambil semua item master BMN (join ke perangkat jika ada referensi)
        $daftar_bmn = $this->Master_bmn_model->get_all();

        // Ambil daftar perangkat IT untuk dropdown tambah
        $this->db->select('p.id, p.kode_barang, p.nup, p.nama_barang, p.merk, p.kondisi, p.lokasi, p.serial_number,
                           s.kategori, s.processor, s.ram_gb, s.storage_gb, s.storage_type, s.os');
        $this->db->from('perangkat p');
        $this->db->join('perangkat_spesifikasi s', 's.perangkat_id = p.id', 'left');
        $this->db->order_by('p.nama_barang', 'ASC');
        $daftar_perangkat = $this->db->get()->result_array();

        // Perangkat yang sudah terdaftar di master_bmn (by kode_barang+nup)
        $sudah_terdaftar = [];
        foreach ($daftar_bmn as $b) {
            if (!empty($b['kode_barang']) && !empty($b['nup'])) {
                $sudah_terdaftar[] = $b['kode_barang'] . '|' . $b['nup'];
            }
        }

        // Statistik
        $stat_tersedia  = count(array_filter($daftar_bmn, fn($b) => $b['status'] === 'Tersedia'));
        $stat_dipinjam  = count(array_filter($daftar_bmn, fn($b) => $b['status'] === 'Dipinjam'));
        $stat_perbaikan = count(array_filter($daftar_bmn, fn($b) => $b['status'] === 'Dalam Perbaikan'));

        $data = [
            'title'            => 'Master Peminjaman BMN',
            'daftar_bmn'       => $daftar_bmn,
            'daftar_perangkat' => $daftar_perangkat,
            'sudah_terdaftar'  => $sudah_terdaftar,
            'stat_tersedia'    => $stat_tersedia,
            'stat_dipinjam'    => $stat_dipinjam,
            'stat_perbaikan'   => $stat_perbaikan,
        ];

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar');
        $this->load->view('master_bmn/index', $data);
        $this->load->view('layout/footer');
    }

    // ══════════════════════════════════════════════════════
    //  SIMPAN — Dari Perangkat IT
    // ══════════════════════════════════════════════════════
    public function simpan()
    {
        $perangkat_id = (int) $this->input->post('perangkat_id');

        if (!$perangkat_id) {
            $this->session->set_flashdata('error', 'Pilih perangkat IT terlebih dahulu.');
            redirect('master_bmn');
        }

        // Ambil data perangkat
        $p = $this->db->select('p.*, s.kategori, s.processor, s.ram_gb, s.storage_gb, s.storage_type, s.os')
            ->from('perangkat p')
            ->join('perangkat_spesifikasi s', 's.perangkat_id = p.id', 'left')
            ->where('p.id', $perangkat_id)
            ->get()->row_array();

        if (!$p) {
            $this->session->set_flashdata('error', 'Data perangkat tidak ditemukan.');
            redirect('master_bmn');
        }

        // Cek sudah terdaftar?
        $exists = $this->db->get_where('master_bmn', [
            'kode_barang' => $p['kode_barang'],
            'nup'         => $p['nup'],
        ])->row();

        if ($exists) {
            $this->session->set_flashdata('error', 'Perangkat ini sudah terdaftar di Master BMN.');
            redirect('master_bmn');
        }

        // Bangun spesifikasi string
        $spek_parts = [];
        if (!empty($p['processor']))    $spek_parts[] = $p['processor'];
        if (!empty($p['ram_gb']))       $spek_parts[] = 'RAM ' . $p['ram_gb'] . ' GB';
        if (!empty($p['storage_gb']))   $spek_parts[] = $p['storage_gb'] . ' GB ' . ($p['storage_type'] ?? '');
        if (!empty($p['os']))           $spek_parts[] = $p['os'];
        $spesifikasi_str = implode(', ', array_filter($spek_parts));

        $data = [
            'kode_barang'  => $p['kode_barang']  ?? '',
            'nup'          => $p['nup']           ?? '',
            'nama_barang'  => $p['nama_barang']   ?? '',
            'merek'        => $p['merk']          ?? '',
            'spesifikasi'  => $this->input->post('spesifikasi') ?: $spesifikasi_str,
            'kondisi'      => $p['kondisi']        ?? 'Baik',
            'status'       => 'Tersedia',
            'lokasi'       => $p['lokasi']         ?? '',
            'keterangan'   => $this->input->post('keterangan', true),
            'perangkat_id' => $perangkat_id,
            'created_at'   => date('Y-m-d H:i:s'),
        ];

        $this->Master_bmn_model->insert($data);
        $this->session->set_flashdata('success', 'Perangkat IT berhasil ditambahkan ke Master BMN.');
        redirect('master_bmn');
    }

    // ══════════════════════════════════════════════════════
    //  UPDATE
    // ══════════════════════════════════════════════════════
    public function update()
    {
        $id   = (int) $this->input->post('id');
        $data = [
            'nama_barang'  => $this->input->post('nama_barang', true),
            'merek'        => $this->input->post('merek', true),
            'spesifikasi'  => $this->input->post('spesifikasi', true),
            'kondisi'      => $this->input->post('kondisi', true) ?: 'Baik',
            'status'       => $this->input->post('status', true) ?: 'Tersedia',
            'lokasi'       => $this->input->post('lokasi', true),
            'keterangan'   => $this->input->post('keterangan', true),
        ];

        $this->Master_bmn_model->update($id, $data);
        $this->session->set_flashdata('success', 'Data BMN berhasil diperbarui.');
        redirect('master_bmn');
    }

    // ══════════════════════════════════════════════════════
    //  HAPUS
    // ══════════════════════════════════════════════════════
    public function hapus($id)
    {
        $result = $this->Master_bmn_model->delete($id);
        if ($result === false) {
            $this->session->set_flashdata('error', 'Tidak dapat menghapus barang yang masih aktif dipinjam.');
        } else {
            $this->session->set_flashdata('success', 'Barang BMN berhasil dihapus dari daftar.');
        }
        redirect('master_bmn');
    }

    // ══════════════════════════════════════════════════════
    //  AJAX — Get perangkat detail untuk preview
    // ══════════════════════════════════════════════════════
    public function get_perangkat_detail()
    {
        header('Content-Type: application/json');
        $id = (int) $this->input->get('id');

        $p = $this->db->select('p.*, s.kategori, s.processor, s.ram_gb, s.storage_gb, s.storage_type, s.gpu, s.os')
            ->from('perangkat p')
            ->join('perangkat_spesifikasi s', 's.perangkat_id = p.id', 'left')
            ->where('p.id', $id)
            ->get()->row_array();

        if (!$p) {
            echo json_encode(['status' => 'error']);
            return;
        }

        $spek_parts = [];
        if (!empty($p['processor']))   $spek_parts[] = $p['processor'];
        if (!empty($p['ram_gb']))      $spek_parts[] = 'RAM ' . $p['ram_gb'] . ' GB';
        if (!empty($p['storage_gb'])) $spek_parts[] = $p['storage_gb'] . ' GB ' . ($p['storage_type'] ?? '');
        if (!empty($p['gpu']))        $spek_parts[] = 'GPU: ' . $p['gpu'];
        if (!empty($p['os']))         $spek_parts[] = $p['os'];

        echo json_encode([
            'status'       => 'success',
            'nama_barang'  => $p['nama_barang'] ?? '',
            'merek'        => $p['merk'] ?? '',
            'kondisi'      => $p['kondisi'] ?? 'Baik',
            'lokasi'       => $p['lokasi'] ?? '',
            'serial_number'=> $p['serial_number'] ?? '',
            'kode_barang'  => $p['kode_barang'] ?? '',
            'nup'          => $p['nup'] ?? '',
            'spesifikasi'  => implode(', ', array_filter($spek_parts)),
        ]);
    }
}