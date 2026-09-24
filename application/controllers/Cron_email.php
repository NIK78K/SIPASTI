<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * ============================================================
 *  SIPASTI — Cron_email Controller
 *  File   : application/controllers/Cron_email.php
 *
 *  Endpoint ini dipanggil otomatis oleh cron job server
 *  setiap 2 menit. Jangan diakses manual dari browser
 *  kecuali untuk testing (pakai CRON_SECRET).
 *
 *  URL   : /index.php/cron_email/process
 *  Cron  : lihat bagian bawah file ini
 * ============================================================
 */

class Cron_email extends CI_Controller {

    // Ganti string ini dengan string acak yang hanya kamu tahu
    // Dipakai untuk proteksi endpoint dari akses luar
    const CRON_SECRET = 'SIPASTI_CRON_R1AU_2025';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Email_queue_model');
        $this->load->helper('email');
    }

    /* =========================================================
       MAIN PROCESS — dipanggil cron job
    ========================================================= */
    public function process()
    {
        // ── Proteksi: hanya boleh dari CLI atau dengan secret key ──
        $secret = $this->input->get('secret') ?: $this->input->post('secret');

        $is_cli    = (php_sapi_name() === 'cli');
        $is_authed = ($secret === self::CRON_SECRET);

        if (!$is_cli && !$is_authed) {
            show_error('Forbidden', 403);
            return;
        }

        // ── Ambil maks 10 email pending per run ──────────────────
        $queue = $this->Email_queue_model->get_pending(10);

        if (empty($queue)) {
            $this->_output('Tidak ada email pending.');
            return;
        }

        $berhasil = 0;
        $gagal    = 0;

        foreach ($queue as $email) {

            $result = _do_send_smtp(
                $email['to_email'],
                $email['subject'],
                $email['message']
            );

            if ($result['success']) {
                $this->Email_queue_model->mark_sent($email['id']);
                $berhasil++;

                log_message('info',
                    '[CRON-EMAIL] TERKIRIM → ' . $email['to_email'] .
                    ' | Subject: ' . $email['subject']
                );

            } else {
                $this->Email_queue_model->mark_failed(
                    $email['id'],
                    (int) $email['attempts'],
                    (int) $email['max_attempts'],
                    $result['error']
                );
                $gagal++;

                log_message('error',
                    '[CRON-EMAIL] GAGAL (percobaan ' . ($email['attempts'] + 1) . '/' . $email['max_attempts'] . ')' .
                    ' → ' . $email['to_email'] .
                    ' | Error: ' . substr($result['error'], 0, 300)
                );
            }
        }

        $this->_output("Selesai. Terkirim: {$berhasil} | Gagal: {$gagal}");
    }

    /* =========================================================
       RETRY MANUAL — reset semua email failed jadi pending lagi
       URL: /index.php/cron_email/retry?secret=SIPASTI_CRON_R1AU_2025
    ========================================================= */
    public function retry()
    {
        $secret = $this->input->get('secret');
        if ($secret !== self::CRON_SECRET) {
            show_error('Forbidden', 403);
            return;
        }

        $this->Email_queue_model->retry_failed();
        $this->_output('Semua email failed sudah di-reset ke pending.');
    }

    /* =========================================================
       STATUS — lihat statistik antrian
       URL: /index.php/cron_email/status?secret=SIPASTI_CRON_R1AU_2025
    ========================================================= */
    public function status()
    {
        $secret = $this->input->get('secret');
        if ($secret !== self::CRON_SECRET) {
            show_error('Forbidden', 403);
            return;
        }

        $stats = $this->Email_queue_model->stats();
        $this->_output(
            "Status Antrian Email:\n" .
            "  Pending : {$stats['pending']}\n" .
            "  Sent    : {$stats['sent']}\n" .
            "  Failed  : {$stats['failed']}"
        );
    }

    /* =========================================================
       HELPER OUTPUT
    ========================================================= */
    private function _output($msg)
    {
        $timestamp = date('Y-m-d H:i:s');
        if (php_sapi_name() === 'cli') {
            echo "[{$timestamp}] {$msg}\n";
        } else {
            echo "<pre>[{$timestamp}] {$msg}</pre>";
        }
    }
}

/*
 * ============================================================
 *  SETUP CRON JOB DI SERVER
 *  Jalankan: crontab -e
 *  Lalu tambahkan salah satu baris di bawah ini:
 *
 *  --- OPSI A: via CLI PHP (lebih stabil, direkomendasikan) ---
 *  Jalankan tiap 2 menit:
 *
 *  * /2 * * * * php /var/www/html/sipasti/index.php cron_email process >> /var/log/sipasti_email.log 2>&1
 *
 *  Ganti /var/www/html/sipasti/ dengan path project kamu.
 *
 *  --- OPSI B: via wget/curl (kalau tidak bisa akses CLI PHP) ---
 *
 *  * /2 * * * * wget -q -O /dev/null "http://localhost/sipasti/index.php/cron_email/process?secret=SIPASTI_CRON_R1AU_2025"
 *
 *  atau:
 *
 *  * /2 * * * * curl -s "http://localhost/sipasti/index.php/cron_email/process?secret=SIPASTI_CRON_R1AU_2025" > /dev/null
 *
 *  --- CEK LOG ---
 *  tail -f /var/log/sipasti_email.log
 *
 * ============================================================
 */