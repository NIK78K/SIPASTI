<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi_persediaan_model extends CI_Model {

    private $table = 'transaksi_persediaan';

    public function get_all_with_barang() {
        $this->db->select('t.*, b.kode_barang, b.nama_barang, b.satuan, k.kode AS kategori_kode, k.nama AS kategori_nama');
        $this->db->from($this->table . ' t');
        $this->db->join('master_barang_persediaan b', 'b.id = t.barang_id', 'left');
        $this->db->join('master_kategori_persediaan k', 'k.id = b.kategori_id', 'left');
        $this->db->order_by('t.tanggal', 'DESC');
        $this->db->order_by('t.id', 'DESC');
        return $this->db->get()->result();
    }

    public function get_filtered($jenis = null, $barang_id = null, $bulan = null, $tahun = null) {
        $this->db->select('t.*, b.kode_barang, b.nama_barang, b.satuan, k.kode AS kategori_kode, k.nama AS kategori_nama');
        $this->db->from($this->table . ' t');
        $this->db->join('master_barang_persediaan b', 'b.id = t.barang_id', 'left');
        $this->db->join('master_kategori_persediaan k', 'k.id = b.kategori_id', 'left');
        if (!empty($jenis)) {
            $this->db->where('t.jenis', $jenis);
        }
        if (!empty($barang_id)) {
            $this->db->where('t.barang_id', $barang_id);
        }
        if (!empty($bulan)) {
            $this->db->where('MONTH(t.tanggal)', (int) $bulan);
        }
        if (!empty($tahun)) {
            $this->db->where('YEAR(t.tanggal)', (int) $tahun);
        }
        $this->db->order_by('t.tanggal', 'DESC');
        $this->db->order_by('t.id', 'DESC');
        return $this->db->get()->result();
    }

    public function get_tahun_list() {
        $this->db->select('DISTINCT YEAR(tanggal) AS tahun', false);
        $this->db->from($this->table);
        $this->db->order_by('tahun', 'DESC');
        return array_map(function ($r) { return $r->tahun; }, $this->db->get()->result());
    }

    public function stats() {
        return [
            'total' => $this->db->count_all_results($this->table),

            'masuk' => $this->db
                ->where('jenis', 'masuk')
                ->count_all_results($this->table),

            'keluar' => $this->db
                ->where('jenis', 'keluar')
                ->count_all_results($this->table),

            'total_unit' => $this->db
                ->select('COALESCE(SUM(jumlah), 0) AS total_unit', false)
                ->get($this->table)
                ->row()->total_unit ?? 0,
        ];
    }

    public function get_by_id($id) {
        $this->db->select('t.*, b.kode_barang, b.nama_barang, b.satuan, k.kode AS kategori_kode, k.nama AS kategori_nama');
        $this->db->from($this->table . ' t');
        $this->db->join('master_barang_persediaan b', 'b.id = t.barang_id', 'left');
        $this->db->join('master_kategori_persediaan k', 'k.id = b.kategori_id', 'left');
        $this->db->where('t.id', $id);
        return $this->db->get()->row();
    }

    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data) {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete($id) {
        return $this->db->where('id', $id)->delete($this->table);
    }

    /* ===============================
       PERMINTAAN PERSEDIAAN: items per tiket
    ================================ */
    public function has_items($ticket_id) {
        if (!$this->db->table_exists('ticket_persediaan_items')) return false;
        return $this->db->where('ticket_id', (int) $ticket_id)
            ->count_all_results('ticket_persediaan_items') > 0;
    }

    public function get_items($ticket_id) {
        if (!$this->db->table_exists('ticket_persediaan_items')) return [];
        // i.* sudah sertakan jumlah_disetujui (NULL=belum ACC).
        return $this->db
            ->select('i.*, b.kode_barang, b.nama_barang, b.satuan')
            ->from('ticket_persediaan_items i')
            ->join('master_barang_persediaan b', 'b.id = i.barang_id', 'left')
            ->where('i.ticket_id', (int) $ticket_id)
            ->order_by('i.id', 'ASC')
            ->get()
            ->result_array();
    }

    /* ===============================
       ACC TEKNISI: jumlah_disetujui & keterangan per item (0<=disetujui<=jumlah).
       NULL=belum ACC; default ikut jumlah saat ACC bila key absen.
    ================================ */
    public function save_acc_ticket($ticket_id, $teknisi_id, $items, $keterangan = []) {
        $ticket_id  = (int) $ticket_id;
        $teknisi_id = (int) $teknisi_id;
        $ticket = $this->db->where('id', $ticket_id)->get('tickets')->row_array();
        if (!$ticket) return ['ok' => false, 'msg' => 'Tiket tidak ditemukan.'];
        $owner = (int) ($ticket['teknisi_id'] ?? 0);
        if ($owner !== 0 && $owner !== $teknisi_id) return ['ok' => false, 'msg' => 'Tiket bukan milik Anda.'];
        if (!in_array($ticket['status'] ?? '', ['Menunggu', 'On Progress'])) return ['ok' => false, 'msg' => 'Tiket sudah diproses.'];
        if (!$this->db->field_exists('jumlah_disetujui', 'ticket_persediaan_items')) return ['ok' => false, 'msg' => 'Kolom jumlah_disetujui belum ada.'];
        if (!is_array($items) || empty($items)) return ['ok' => false, 'msg' => 'Items kosong.'];

        if ($this->db->table_exists('ticket_persediaan_items') && !$this->db->field_exists('keterangan', 'ticket_persediaan_items')) {
            $this->db->query("ALTER TABLE `ticket_persediaan_items` ADD COLUMN `keterangan` VARCHAR(255) NULL AFTER `jumlah_disetujui`");
        }

        $rows = $this->db->where('ticket_id', $ticket_id)->get('ticket_persediaan_items')->result_array();
        if (empty($rows)) return ['ok' => false, 'msg' => 'Bukan tiket persediaan.'];
        $max = [];
        foreach ($rows as $r) $max[(int) $r['barang_id']] = (int) $r['jumlah'];

        foreach ($items as $bid => $val) {
            $bid = (int) $bid;
            if (!isset($max[$bid])) return ['ok' => false, 'msg' => 'Barang ID ' . $bid . ' bukan bagian tiket.'];
            if ($val === '' || $val === null) $val = $max[$bid];
            if (!is_numeric($val)) return ['ok' => false, 'msg' => 'Jumlah ACC tidak valid (ID ' . $bid . ').'];
            $val = (int) $val;
            if ($val < 0 || $val > $max[$bid]) return ['ok' => false, 'msg' => 'ACC ID ' . $bid . ' harus 0-' . $max[$bid] . '.'];
            $upd = ['jumlah_disetujui' => $val];
            if (is_array($keterangan) && array_key_exists($bid, $keterangan)) {
                $upd['keterangan'] = trim((string)$keterangan[$bid]);
            }
            $this->db->where('ticket_id', $ticket_id)->where('barang_id', $bid)
                ->update('ticket_persediaan_items', $upd);
        }
        return ['ok' => true, 'msg' => 'Jumlah disetujui tersimpan.'];
    }

    /* ===============================
       SELESAI OTOMATIS: stock-out persediaan.
       Satu-satunya titik stok berkurang (tanpa insert saat checkout/assign/ambil).
       (1) guarded UPDATE Selesai WHERE On Progress;
       (2) belt no_dokumen; (3) insert keluar per item (saldo boleh minus);
       (4) teknisi available.
    ================================ */
    public function selesaikan_persediaan($ticket_id) {
        $ticket_id = (int) $ticket_id;
        $ticket = $this->db->where('id', $ticket_id)->get('tickets')->row_array();
        if (!$ticket) return ['ok' => false, 'msg' => 'Tiket tidak ditemukan.'];
        $items = $this->get_items($ticket_id);
        if (empty($items)) return ['ok' => false, 'msg' => 'Bukan tiket persediaan.'];

        $this->db->trans_begin();

        // (1) guarded flip: hanya dari On Progress
        $this->db->where('id', $ticket_id)->where('status', 'On Progress')
            ->update('tickets', ['status' => 'Selesai', 'selesai_at' => date('Y-m-d H:i:s')]);
        if ((int) $this->db->affected_rows() !== 1) {
            $this->db->trans_rollback();
            return ['ok' => false, 'msg' => 'Tiket sudah diproses.'];
        }

        // (2) belt idempoten: sudah ada keluar no_dokumen=kode_tiket -> skip insert
        $done = $this->db->where('no_dokumen', $ticket['kode_tiket'])->where('jenis', 'keluar')
            ->count_all_results($this->table) > 0;

        // (3) INSERT keluar per item pakai COALESCE(jumlah_disetujui,jumlah)
        if (!$done) {
            $has_ticket_col = $this->db->field_exists('ticket_id', $this->table);
            $tek = !empty($ticket['teknisi_id'])
                ? $this->db->where('id', $ticket['teknisi_id'])->get('teknisi')->row_array()
                : null;
            $this->load->model('User_ticket_model');
            $bulan = (int) date('n');
            $tahun = (int) date('Y');
            $keluar_count = 0;
            $kurang = [];
            foreach ($items as $it) {
                // COALESCE: NULL=belum ACC -> ikut jumlah diminta.
                $qty = (array_key_exists('jumlah_disetujui', $it) && $it['jumlah_disetujui'] !== null && $it['jumlah_disetujui'] !== '')
                    ? (int) $it['jumlah_disetujui'] : (int) $it['jumlah'];
                if ($qty <= 0) continue; // ACC 0 = tidak diserahkan
                // Recheck sisa sebagai info (non-blocking, saldo boleh minus).
                $sisa = $this->User_ticket_model->get_sisa_persediaan((int) $it['barang_id'], $bulan, $tahun);
                if ($sisa < $qty) $kurang[] = ($it['nama_barang'] ?: ('ID ' . $it['barang_id'])) . ' (sisa ' . (int) $sisa . ')';
                $row = [
                    'barang_id'       => (int) $it['barang_id'],
                    'jenis'           => 'keluar',
                    'tanggal'         => date('Y-m-d'),
                    'jumlah'          => $qty,
                    'uraian'          => substr('Penyerahan ' . $ticket['kode_tiket'] . ': ' . ($ticket['kebutuhan'] ?: $ticket['jenis_kerusakan']), 0, 255),
                    'penerima_sumber' => substr($ticket['pelapor'] ?: '-', 0, 100),
                    'no_dokumen'      => $ticket['kode_tiket'],
                    'user_id'         => !empty($tek['user_id']) ? $tek['user_id'] : null,
                ];
                if ($has_ticket_col) $row['ticket_id'] = $ticket_id;
                $this->db->insert($this->table, $row);
                $keluar_count++;
            }
            // Semua 0 -> tetap Selesai, 0 keluar + catatan.
            if ($keluar_count === 0) {
                $this->db->where('id', $ticket_id)->update('tickets', [
                    'layanan_catatan' => trim(($ticket['layanan_catatan'] ? $ticket['layanan_catatan'] . "\n" : '') . 'Tidak ada barang disetujui (0 diserahkan).'),
                ]);
            }
        }

        // (4) teknisi kembali available
        if (!empty($ticket['teknisi_id'])) {
            $this->db->where('id', $ticket['teknisi_id'])->update('teknisi', ['status' => 'available']);
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return ['ok' => false, 'msg' => 'Gagal menyelesaikan tiket.'];
        }
        $this->db->trans_commit();

        // Email Selesai ke user via path update (template + queue terpusat).
        $this->load->model('Ticket_model');
        $this->Ticket_model->update($ticket_id, ['status' => 'Selesai']);

        return ['ok' => true, 'msg' => 'Tiket persediaan selesai, stok berkurang.'];
    }
}
