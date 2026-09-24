<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('send_ticket_email'))
{
    function send_ticket_email($to, $subject, $message)
    {
        $CI =& get_instance();
        $CI->load->model('Email_queue_model');
        return $CI->Email_queue_model->push($to, $subject, $message);
    }
}


if (!function_exists('_do_send_smtp'))
{
    function _do_send_smtp($to, $subject, $message)
    {
        $CI =& get_instance();
        $CI->load->library('email');

        $CI->email->initialize([
            'protocol'     => 'smtp',
            'smtp_host'    => 'smtp.bps.go.id',
            'smtp_user'    => 'ipds1400',
            'smtp_pass'    => '1pd51400',
            'smtp_port'    => 587,
            'smtp_crypto'  => 'tls',
            'mailtype'     => 'html',
            'charset'      => 'utf-8',
            'newline'      => "\r\n",
            'crlf'         => "\r\n",
            'smtp_timeout' => 15,
            'wordwrap'     => false,
        ]);

        $CI->email->clear();
        $CI->email->from('ipds1400@bps.go.id', 'SIPASTI - Layanan TI');
        $CI->email->reply_to('ipds1400@bps.go.id', 'Noreply SIPASTI');
        $CI->email->to($to);
        $CI->email->subject($subject);
        $CI->email->message($message);

        $result = $CI->email->send(false);

        if (!$result) {
            return [
                'success' => false,
                'error'   => $CI->email->print_debugger(['headers', 'subject', 'body'])
            ];
        }

        return ['success' => true, 'error' => ''];
    }
}



if (!function_exists('send_ticket_email'))
{
    /**
     * Kirim email via SMTP BPS.
     *
     * @param  string       $to       Alamat tujuan
     * @param  string       $subject  Subject email
     * @param  string       $message  Body HTML
     * @return bool
     */
    function send_ticket_email($to, $subject, $message)
    {
        $CI =& get_instance();
        $CI->load->library('email');

        // ── Re-initialize setiap panggilan ──────────────────────
        // Wajib! CI email library bersifat stateful; tanpa ini
        // state (recipient, subject, dll) dari request sebelumnya
        // bisa ikut terbawa.
        $CI->email->initialize([
            'protocol'     => 'smtp',
            'smtp_host'    => 'smtp.bps.go.id',
            'smtp_user'    => 'ipds1400',
            'smtp_pass'    => '1pd51400',
            'smtp_port'    => 587,          // konsisten pakai 587
            'smtp_crypto'  => 'tls',        // konsisten pakai tls
            'mailtype'     => 'html',
            'charset'      => 'utf-8',
            'newline'       => "\r\n",
            'crlf'          => "\r\n",
            'smtp_timeout' => 15,           // jangan terlalu lama
            'wordwrap'     => false,        // matikan wordwrap agar HTML tidak rusak
        ]);

 
        $CI->email->clear();

        $CI->email->from('ipds1400@bps.go.id', 'SIPASTI - Layanan TI');
        $CI->email->reply_to('ipds1400@bps.go.id', 'Noreply SIPASTI');
        $CI->email->to($to);
        $CI->email->subject($subject);
        $CI->email->message($message);

        $result = $CI->email->send(false); // false = jangan throw exception

        // ── Log kalau gagal ─────────────────────────────────────
        if (!$result) {
            log_message('error',
                '[SIPASTI-EMAIL] GAGAL kirim ke: ' . $to .
                ' | Subject: ' . $subject .
                ' | Debug: ' . $CI->email->print_debugger(['headers', 'subject', 'body'])
            );
        }

        return $result;
    }
}