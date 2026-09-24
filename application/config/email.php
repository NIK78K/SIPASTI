<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Push ke queue (dipanggil dari controller/model)
if (!function_exists('send_ticket_email')) {
    function send_ticket_email($to, $subject, $message) {
        $CI =& get_instance();
        $CI->load->model('Email_queue_model');
        return $CI->Email_queue_model->push($to, $subject, $message);
    }
}

// Kirim langsung via SMTP (dipanggil dari cron)
if (!function_exists('_do_send_smtp')) {
    function _do_send_smtp($to, $subject, $message) {
        $CI =& get_instance();
        $CI->load->library('email');
        $CI->email->initialize([
            'protocol'     => 'smtp',
            'smtp_host'    => 'smtp.bps.go.id',
            'smtp_user'    => 'ipds1400',
            'smtp_pass'    => '1pd51400',
            'smtp_port'    => 465,      // pakai 465
            'smtp_crypto'  => 'ssl',    // pakai ssl
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