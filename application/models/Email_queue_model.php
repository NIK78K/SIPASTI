<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * ============================================================
 *  SIPASTI — Email_queue_model
 *  File   : application/models/Email_queue_model.php
 * ============================================================
 */

class Email_queue_model extends CI_Model {

    /* =========================================================
       TAMBAH EMAIL KE ANTRIAN
       Dipanggil dari helper send_ticket_email() dan Auth
    ========================================================= */
    public function push($to, $subject, $message)
    {
        return $this->db->insert('email_queue', [
            'to_email'    => $to,
            'subject'     => $subject,
            'message'     => $message,
            'status'      => 'pending',
            'attempts'    => 0,
            'created_at'  => date('Y-m-d H:i:s'),
            'next_try_at' => date('Y-m-d H:i:s'),  // langsung bisa diproses
        ]);
    }

    /* =========================================================
       AMBIL ANTRIAN YANG SIAP DIPROSES
       - status pending
       - next_try_at sudah lewat
       - attempts < max_attempts
    ========================================================= */
    public function get_pending($limit = 10)
    {
        return $this->db
            ->where('status', 'pending')
            ->where('next_try_at <=', date('Y-m-d H:i:s'))
            ->where('attempts < max_attempts')
            ->order_by('created_at', 'ASC')
            ->limit($limit)
            ->get('email_queue')
            ->result_array();
    }

    /* =========================================================
       TANDAI BERHASIL
    ========================================================= */
    public function mark_sent($id)
    {
        return $this->db->where('id', $id)->update('email_queue', [
            'status'  => 'sent',
            'sent_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /* =========================================================
       TANDAI GAGAL — naikan attempts, set next_try_at (backoff)
       Backoff: percobaan ke-1 → +2 menit
                percobaan ke-2 → +5 menit
                percobaan ke-3 → langsung failed
    ========================================================= */
    public function mark_failed($id, $attempts, $max_attempts, $error = '')
    {
        $next_attempts = $attempts + 1;

        if ($next_attempts >= $max_attempts) {
            // Sudah habis kesempatan → failed
            $this->db->where('id', $id)->update('email_queue', [
                'status'      => 'failed',
                'attempts'    => $next_attempts,
                'last_error'  => substr($error, 0, 1000),
            ]);
        } else {
            // Masih ada kesempatan → pending lagi, tapi tunggu dulu (backoff)
            $backoff_minutes = ($next_attempts === 1) ? 2 : 5;
            $next_try = date('Y-m-d H:i:s', strtotime("+{$backoff_minutes} minutes"));

            $this->db->where('id', $id)->update('email_queue', [
                'status'      => 'pending',
                'attempts'    => $next_attempts,
                'last_error'  => substr($error, 0, 1000),
                'next_try_at' => $next_try,
            ]);
        }
    }

    /* =========================================================
       STATISTIK (opsional, untuk dashboard admin)
    ========================================================= */
    public function stats()
    {
        $rows = $this->db
            ->select('status, COUNT(*) as total')
            ->group_by('status')
            ->get('email_queue')
            ->result_array();

        $stats = ['pending' => 0, 'sent' => 0, 'failed' => 0];
        foreach ($rows as $r) {
            $stats[$r['status']] = (int) $r['total'];
        }
        return $stats;
    }

    /* =========================================================
       RETRY MANUAL — reset email failed jadi pending lagi
    ========================================================= */
    public function retry_failed()
    {
        return $this->db
            ->where('status', 'failed')
            ->update('email_queue', [
                'status'      => 'pending',
                'attempts'    => 0,
                'next_try_at' => date('Y-m-d H:i:s'),
                'last_error'  => NULL,
            ]);
    }
}