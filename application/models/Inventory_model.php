<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory_model extends CI_Model
{
    protected $table = 'perangkat';

    /* =====================================================
       BASE QUERY (DIPAKAI ULANG)
    ====================================================== */
    private function base_query($filters = [])
    {
        $this->db->from('perangkat p');

        // 🔥 CEK APAKAH FILTER SPEK DIPAKAI
        $pakaiSpek =
            !empty($filters['kategori']) ||
            !empty($filters['ram_exact']) ||
            !empty($filters['storage_type']);

        // 🔥 JOIN DINAMIS
        $this->db->join(
            'perangkat_spesifikasi s',
            's.perangkat_id = p.id',
            $pakaiSpek ? 'inner' : 'left'
        );

        /* ================= FILTER UMUM ================= */
        if (!empty($filters['keyword'])) {
            $this->db->group_start()
                ->like('p.nama_barang', $filters['keyword'])
                ->or_like('p.kode_barang', $filters['keyword'])
                ->or_like('p.merk', $filters['keyword'])
            ->group_end();
        }
        if (!empty($filters['kategori_aset'])) {
    $this->db->where('kategori_aset', $filters['kategori_aset']);
}

        if (!empty($filters['nup'])) {
            $this->db->like('p.nup', $filters['nup']);
        }

        if (!empty($filters['kondisi'])) {
            $this->db->where('p.kondisi', $filters['kondisi']);
        }

        if (!empty($filters['satker'])) {
            $this->db->like('p.nama_satker', $filters['satker']);
        }

        // ✅ FILTER NAMA BARANG (TIPE PERANGKAT)
        if (!empty($filters['nama_barang'])) {
            $this->db->where('p.nama_barang', $filters['nama_barang']);
        }

        /* ================= FILTER SPESIFIKASI ================= */
        if (!empty($filters['kategori'])) {
            $this->db->where('s.kategori', $filters['kategori']);
        }

        if (!empty($filters['ram_exact'])) {
            $this->db->where('s.ram_gb', (int)$filters['ram_exact']);
        }

        if (!empty($filters['storage_type'])) {
            $this->db->where('s.storage_type', $filters['storage_type']);
        }
    }

    /* =====================================================
       GET ALL (EXPORT EXCEL)
    ====================================================== */
    public function get_all($filters = [])
    {
        $this->db->select('p.*, s.kategori, s.ram_gb, s.storage_type, s.processor, s.storage_gb, s.gpu, s.os');
        $this->base_query($filters);
        $this->db->order_by('p.id', 'ASC');
        return $this->db->get()->result_array();
    }

    /* =====================================================
       PAGINATION + FILTER
    ====================================================== */
    public function get_paginated($limit, $offset, $filters = [])
    {
        $this->db->select('p.*, s.kategori, s.ram_gb, s.storage_type, s.processor, s.storage_gb, s.gpu, s.os');
        $this->base_query($filters);
        $this->db->order_by('p.id', 'ASC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result_array();
    }

    /* =====================================================
       TOTAL ROWS (WAJIB SAMA LOGIKANYA)
    ====================================================== */
    public function total_rows($filters = [])
    {
        $this->db->select('COUNT(DISTINCT p.id) AS total', false);
        $this->base_query($filters);
        return (int)$this->db->get()->row()->total;
    }

    /* =====================================================
       DETAIL
    ====================================================== */
    public function getById($id)
    {
        return $this->db
            ->where('id', $id)
            ->get($this->table)
            ->row_array();
    }

    public function getSpesifikasi($perangkat_id)
    {
        return $this->db
            ->where('perangkat_id', $perangkat_id)
            ->get('perangkat_spesifikasi')
            ->row_array();
    }

    /* =====================================================
       COUNT PER KONDISI
    ====================================================== */
    public function count_kondisi($kondisi)
    {
        return $this->db
            ->where('kondisi', $kondisi)
            ->count_all_results($this->table);
    }

    /* =====================================================
       INSERT BATCH
    ====================================================== */
    public function insert_batch($data)
    {
        $now = date('Y-m-d H:i:s');

        foreach ($data as &$row) {
            if (!isset($row['created_at'])) {
                $row['created_at'] = $now;
            }
            $row['updated_at'] = $now;
        }

        return $this->db->insert_batch($this->table, $data);
    }

    /* =====================================================
       UPDATE DATA
    ====================================================== */
    public function update_data($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    /* =====================================================
       DELETE
    ====================================================== */
    public function delete($id)
    {
        $this->db->trans_start();
        
        // Hapus spesifikasi dulu (foreign key)
        $this->db->delete('perangkat_spesifikasi', ['perangkat_id' => $id]);
        
        // Hapus perangkat
        $this->db->delete($this->table, ['id' => $id]);
        
        $this->db->trans_complete();
        
        return $this->db->trans_status();
    }

    /* =====================================================
       GET UNIQUE NAMA BARANG (untuk dropdown dinamis)
    ====================================================== */
    public function get_unique_nama_barang()
    {
        $this->db->select('nama_barang');
        $this->db->from($this->table);
        $this->db->where('nama_barang IS NOT NULL');
        $this->db->where('nama_barang !=', '');
        $this->db->group_by('nama_barang');
        $this->db->order_by('nama_barang', 'ASC');
        
        $result = $this->db->get()->result_array();
        
        return array_column($result, 'nama_barang');
    }

public function upload_foto($field_name = 'foto')
{
    $config['upload_path']   = './uploads/perangkat/';
    $config['allowed_types'] = 'jpg|jpeg|png|gif';
    $config['max_size']      = 2048; // 2MB
    $config['encrypt_name']  = TRUE;
    
    // Buat folder jika belum ada
    if (!is_dir($config['upload_path'])) {
        mkdir($config['upload_path'], 0755, TRUE);
    }
    
    $this->load->library('upload', $config);
    
    if ($this->upload->do_upload($field_name)) {
        return $this->upload->data('file_name');
    }
    
    return FALSE;
}

/**
 * Hapus foto lama
 */
public function delete_foto($filename)
{
    if (empty($filename)) return TRUE;
    
    $filepath = './uploads/perangkat/' . $filename;
    
    if (file_exists($filepath)) {
        return unlink($filepath);
    }
    
    return TRUE;
}

private $field_labels = [
        'nama_barang'       => 'Nama Barang',
        'merk'              => 'Merk',
        'kondisi'           => 'Kondisi',
        'pengguna'          => 'Pengguna',
        'ruangan'           => 'Ruangan',
        'lokasi'            => 'Lokasi',
        'serial_number'     => 'Serial Number',
        'mac_address'       => 'MAC Address',
        'tanggal_perolehan' => 'Tanggal Perolehan',
        'nilai_perolehan'   => 'Nilai Perolehan',
        'keterangan'        => 'Keterangan',
        'henti_guna'        => 'Henti Guna',
        'usul_hapus'        => 'Usul Hapus',
        'transaksi_sakti'   => 'Transaksi SAKTI',
        'foto'              => 'Foto',
        'jenis_bmn'         => 'Jenis BMN',
        'kode_satker'       => 'Kode Satker',
        'nama_satker'       => 'Nama Satker',
        'kode_barang'       => 'Kode Barang',
        'nup'               => 'NUP',
    ];
 
    /* =====================================================
       REKAM HISTORY PERUBAHAN
       Panggil ini di controller Update sebelum db->update()
    ====================================================== */
    public function rekam_history($perangkat_id, $data_lama, $data_baru, $diubah_oleh = 'System')
    {
        // Field yang TIDAK perlu dilacak (internal/timestamp)
        $skip_fields = ['updated_at', 'created_at', 'id'];
 
        $batch = [];
        $now   = date('Y-m-d H:i:s');
 
        foreach ($data_baru as $field => $nilai_baru) {
            if (in_array($field, $skip_fields)) continue;
            if (!array_key_exists($field, $data_lama))  continue;
 
            $nilai_lama = $data_lama[$field];
 
            // Hanya rekam jika nilai berubah
            if ((string)$nilai_lama === (string)$nilai_baru) continue;
 
            $label = $this->field_labels[$field] ?? ucwords(str_replace('_', ' ', $field));
 
            $batch[] = [
                'perangkat_id' => (int)$perangkat_id,
                'field_name'   => $field,
                'label_field'  => $label,
                'nilai_lama'   => $nilai_lama,
                'nilai_baru'   => $nilai_baru,
                'diubah_oleh'  => $diubah_oleh,
                'diubah_pada'  => $now,
            ];
        }
 
        if (!empty($batch)) {
            $this->db->insert_batch('perangkat_history', $batch);
        }
 
        return count($batch); // jumlah field yang berubah
    }
 
    /* =====================================================
       REKAM HISTORY SPESIFIKASI
    ====================================================== */
    public function rekam_history_spek($perangkat_id, $spek_lama, $spek_baru, $diubah_oleh = 'System')
    {
        $spek_labels = [
            'kategori'     => 'Spek: Kategori',
            'processor'    => 'Spek: Processor',
            'ram_gb'       => 'Spek: RAM (GB)',
            'storage_gb'   => 'Spek: Storage (GB)',
            'storage_type' => 'Spek: Storage Type',
            'gpu'          => 'Spek: GPU',
            'os'           => 'Spek: OS',
        ];
 
        $batch = [];
        $now   = date('Y-m-d H:i:s');
 
        foreach ($spek_baru as $field => $nilai_baru) {
            if (!isset($spek_labels[$field])) continue;
 
            $nilai_lama = $spek_lama[$field] ?? '';
 
            if ((string)$nilai_lama === (string)$nilai_baru) continue;
 
            $batch[] = [
                'perangkat_id' => (int)$perangkat_id,
                'field_name'   => 'spek_' . $field,
                'label_field'  => $spek_labels[$field],
                'nilai_lama'   => $nilai_lama,
                'nilai_baru'   => $nilai_baru,
                'diubah_oleh'  => $diubah_oleh,
                'diubah_pada'  => $now,
            ];
        }
 
        if (!empty($batch)) {
            $this->db->insert_batch('perangkat_history', $batch);
        }
 
        return count($batch);
    }
 
    /* =====================================================
       GET HISTORY PERANGKAT (untuk modal)
    ====================================================== */
    public function get_history($perangkat_id, $limit = 100)
    {
        return $this->db
            ->where('perangkat_id', $perangkat_id)
            ->order_by('diubah_pada', 'DESC')
            ->limit($limit)
            ->get('perangkat_history')
            ->result_array();
    }
}