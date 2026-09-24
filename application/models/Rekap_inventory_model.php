<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap_inventory_model extends CI_Model {

    /**
     * Get Summary Stats
     */
    public function get_summary($kondisi = null, $tahun = null)
    {
        // Total Aset
        $this->db->select('COUNT(*) as total');
        $this->db->from('perangkat');
        $this->apply_filters($kondisi, $tahun);
        $total_aset = $this->db->get()->row()->total;
        
        // Kondisi Baik
        $this->db->select('COUNT(*) as total');
        $this->db->from('perangkat');
        $this->db->where('kondisi', 'Baik');
        $this->apply_filters($kondisi, $tahun);
        $total_baik = $this->db->get()->row()->total;
        
        // Rusak Ringan
        $this->db->select('COUNT(*) as total');
        $this->db->from('perangkat');
        $this->db->where('kondisi', 'Rusak Ringan');
        $this->apply_filters($kondisi, $tahun);
        $total_rusak_ringan = $this->db->get()->row()->total;
        
        // Rusak Berat
        $this->db->select('COUNT(*) as total');
        $this->db->from('perangkat');
        $this->db->where('kondisi', 'Rusak Berat');
        $this->apply_filters($kondisi, $tahun);
        $total_rusak_berat = $this->db->get()->row()->total;
        
        return [
            'total' => (int)$total_aset,
            'baik' => (int)$total_baik,
            'rusak_ringan' => (int)$total_rusak_ringan,
            'rusak_berat' => (int)$total_rusak_berat
        ];
    }

    /**
     * Get By Kondisi Barang
     */
    public function get_by_kondisi($kondisi = null, $tahun = null)
    {
        $this->db->select("
            nama_barang,
            SUM(CASE WHEN kondisi = 'Baik' THEN 1 ELSE 0 END) as baik,
            SUM(CASE WHEN kondisi = 'Rusak Ringan' THEN 1 ELSE 0 END) as rusak_ringan,
            SUM(CASE WHEN kondisi = 'Rusak Berat' THEN 1 ELSE 0 END) as rusak_berat,
            COUNT(*) as total
        ");
        $this->db->from('perangkat');
        $this->apply_filters($kondisi, $tahun);
        $this->db->group_by('nama_barang');
        $this->db->order_by('nama_barang', 'ASC');
        
        return $this->db->get()->result_array();
    }

    /**
     * Get By Jenis BMN
     */
    public function get_by_jenis_bmn($kondisi = null, $tahun = null)
    {
        $this->db->select("
            jenis_bmn,
            SUM(CASE WHEN kondisi = 'Baik' THEN 1 ELSE 0 END) as baik,
            SUM(CASE WHEN kondisi = 'Rusak Ringan' THEN 1 ELSE 0 END) as rusak_ringan,
            SUM(CASE WHEN kondisi = 'Rusak Berat' THEN 1 ELSE 0 END) as rusak_berat,
            COUNT(*) as total
        ");
        $this->db->from('perangkat');
        $this->apply_filters($kondisi, $tahun);
        $this->db->group_by('jenis_bmn');
        $this->db->order_by('jenis_bmn', 'ASC');
        
        return $this->db->get()->result_array();
    }

    /**
     * Get By Ruangan
     */
    public function get_by_ruangan($kondisi = null, $tahun = null)
    {
        $this->db->select("
            COALESCE(NULLIF(lokasi, ''), 'Belum Ditentukan') as nama_ruangan,
            SUM(CASE WHEN kondisi = 'Baik' THEN 1 ELSE 0 END) as baik,
            SUM(CASE WHEN kondisi = 'Rusak Ringan' THEN 1 ELSE 0 END) as rusak_ringan,
            SUM(CASE WHEN kondisi = 'Rusak Berat' THEN 1 ELSE 0 END) as rusak_berat,
            COUNT(*) as total
        ");
        $this->db->from('perangkat');
        $this->apply_filters($kondisi, $tahun);
        $this->db->group_by('lokasi');
        $this->db->order_by('lokasi', 'ASC');
        
        return $this->db->get()->result_array();
    }

    /**
     * Get By Tahun Perolehan
     */
    public function get_by_tahun_perolehan($kondisi = null, $tahun = null)
    {
        $this->db->select("
            nama_barang,
            YEAR(tanggal_perolehan) as tahun,
            SUM(CASE WHEN kondisi = 'Baik' THEN 1 ELSE 0 END) as baik,
            SUM(CASE WHEN kondisi = 'Rusak Ringan' THEN 1 ELSE 0 END) as rusak_ringan,
            SUM(CASE WHEN kondisi = 'Rusak Berat' THEN 1 ELSE 0 END) as rusak_berat,
            COUNT(*) as total
        ");
        $this->db->from('perangkat');
        $this->db->where('tanggal_perolehan IS NOT NULL');
        $this->apply_filters($kondisi, $tahun);
        $this->db->group_by(['nama_barang', 'YEAR(tanggal_perolehan)']);
        $this->db->order_by('YEAR(tanggal_perolehan)', 'DESC');
        $this->db->order_by('nama_barang', 'ASC');
        
        return $this->db->get()->result_array();
    }

    /**
     * Get Pemeliharaan Data
     * Menghitung jumlah aset yang pernah diperbaiki vs total aset
     */
public function get_pemeliharaan($tahun = null)
{
    $this->db->select("
        nama_barang,
        COUNT(*) as jumlah_aset,
        0 as jumlah_pemeliharaan
    ");
    $this->db->from('perangkat');
    
    if ($tahun && $tahun !== 'all') {
        $this->db->where('YEAR(tanggal_perolehan)', $tahun);
    }
    
    $this->db->group_by('nama_barang');
    $this->db->order_by('nama_barang', 'ASC');
    
    return $this->db->get()->result_array();
}

    /**
     * Apply Filters
     */
    private function apply_filters($kondisi = null, $tahun = null)
    {
        if ($kondisi && $kondisi !== 'all') {
            $kondisi_map = [
                'baik' => 'Baik',
                'rusak_ringan' => 'Rusak Ringan',
                'rusak_berat' => 'Rusak Berat'
            ];
            
            if (isset($kondisi_map[$kondisi])) {
                $this->db->where('kondisi', $kondisi_map[$kondisi]);
            }
        }
        
        if ($tahun && $tahun !== 'all') {
            $this->db->where('YEAR(tanggal_perolehan)', $tahun);
        }
    }

    /**
     * Get Years List (untuk dropdown filter)
     */
    public function get_years()
    {
        $this->db->select('DISTINCT YEAR(tanggal_perolehan) as tahun');
        $this->db->from('perangkat');
        $this->db->where('tanggal_perolehan IS NOT NULL');
        $this->db->order_by('tahun', 'DESC');
        
        $result = $this->db->get()->result_array();
        
        return array_column($result, 'tahun');
    }

    /**
     * Get Unique Nama Barang
     */
    public function get_unique_nama_barang()
    {
        $this->db->select('DISTINCT nama_barang');
        $this->db->from('perangkat');
        $this->db->where('nama_barang IS NOT NULL');
        $this->db->where("nama_barang != ''");
        $this->db->order_by('nama_barang', 'ASC');
        
        $result = $this->db->get()->result_array();
        
        return array_column($result, 'nama_barang');
    }

    /**
     * Get Unique Lokasi
     */
    public function get_unique_lokasi()
    {
        $this->db->select('DISTINCT lokasi');
        $this->db->from('perangkat');
        $this->db->where('lokasi IS NOT NULL');
        $this->db->where("lokasi != ''");
        $this->db->order_by('lokasi', 'ASC');
        
        $result = $this->db->get()->result_array();
        
        return array_column($result, 'lokasi');
    }
}