<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perangkat_dihapus extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /* =======================
       INDEX + FILTER + PAGINATION
    ======================= */
    public function index()
    {
        $limit = 20;
        $page  = (int) ($this->input->get('page') ?? 1);
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $limit;

        /* ================= FILTER ================= */
        $filters = [
            'keyword' => $this->input->get('keyword'),
            'kondisi' => $this->input->get('kondisi'),
            'nup'     => $this->input->get('nup'),
        ];

        // Apply filters
        if (!empty($filters['keyword'])) {
            $this->db->group_start()
                ->like('nama_barang', $filters['keyword'])
                ->or_like('kode_barang', $filters['keyword'])
                ->or_like('merk', $filters['keyword'])
            ->group_end();
        }

        if (!empty($filters['kondisi'])) {
            $this->db->where('kondisi', $filters['kondisi']);
        }

        if (!empty($filters['nup'])) {
            $this->db->like('nup', $filters['nup']);
        }

        /* ================= TOTAL ================= */
        $total = $this->db->count_all_results('perangkat_dihapus', FALSE);

        /* ================= DATA ================= */
        $perangkat = $this->db
            ->order_by('tanggal_hapus', 'DESC')
            ->limit($limit, $offset)
            ->get()
            ->result_array();

        // 🔥 RESET QUERY BUILDER
        $this->db->reset_query();

        /* ================= PAGINATION ================= */
        $total_pages = ceil($total / $limit);
        $pages = range(
            max(1, $page - 1),
            min($total_pages, $page + 1)
        );

        /* ================= KARTU KONDISI ================= */
        $baik = $this->db->where('kondisi', 'Baik')
            ->count_all_results('perangkat_dihapus');

        $rusak_ringan = $this->db->where('kondisi', 'Rusak Ringan')
            ->count_all_results('perangkat_dihapus');

        $rusak_berat = $this->db->where('kondisi', 'Rusak Berat')
            ->count_all_results('perangkat_dihapus');

        /* ================= INSIGHT MINI ================= */
        $insight = [];
        $insight[] = "🔍 Menampilkan <b>{$total}</b> perangkat yang telah dihapus";

        if (!empty($filters['keyword'])) {
            $insight[] = "📦 Pencarian: <b>".htmlspecialchars($filters['keyword'])."</b>";
        }

        if (!empty($filters['nup'])) {
            $insight[] = "🔢 NUP: <b>".htmlspecialchars($filters['nup'])."</b>";
        }

        if (!empty($filters['kondisi'])) {
            $insight[] = "🟡 Kondisi: <b>{$filters['kondisi']}</b>";
        }

        /* ================= KIRIM KE VIEW ================= */
        $data = [
            'perangkat'    => $perangkat,
            'baik'         => $baik,
            'rusak_ringan' => $rusak_ringan,
            'rusak_berat'  => $rusak_berat,

            'current_page' => $page,
            'total_pages'  => $total_pages,
            'pages'        => $pages,

            'from'  => $total ? $offset + 1 : 0,
            'to'    => min($offset + $limit, $total),
            'total' => $total,

            'insight' => implode(' &nbsp; | &nbsp; ', $insight),
        ];

        $this->load->view('layout/header');
        $this->load->view('layout/sidebar');
        $this->load->view('perangkat_dihapus/index', $data);
        $this->load->view('layout/footer');
    }

    /* =======================
       HAPUS PERMANEN
    ======================= */
    public function destroy($id)
    {
        $this->db->trans_start();
        
        // Cek apakah data ada
        $exists = $this->db->get_where('perangkat_dihapus', ['id' => $id])->row();
        
        if ($exists) {
            // Hapus permanen
            $this->db->delete('perangkat_dihapus', ['id' => $id]);
            
            $this->db->trans_complete();
            
            if ($this->db->trans_status()) {
                $this->session->set_flashdata('success', 'Data berhasil dihapus permanen');
            } else {
                $this->session->set_flashdata('error', 'Gagal menghapus data');
            }
        } else {
            $this->session->set_flashdata('error', 'Data tidak ditemukan');
        }
        
        redirect('perangkat_dihapus');
    }

    /* =======================
       DETAIL AJAX
    ======================= */
    public function detail_ajax($id)
    {
        $data['p'] = $this->db
            ->get_where('perangkat_dihapus', ['id' => $id])
            ->row_array();

        if (!$data['p']) {
            show_404();
        }

        $this->load->view('perangkat_dihapus/detail_ajax', $data);
    }
}