<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ========================================
 * ROUTE HELPER - VERSI TANPA ENCRYPTION
 * ========================================
 * 
 * Versi ini TIDAK menggunakan encryption.
 * Semua URL akan normal seperti: /index.php/dashboard
 * 
 * Gunakan ini jika:
 * - Encryption bermasalah di server tertentu
 * - Tidak perlu keamanan URL
 * - Ingin kompatibilitas maksimal
 */

if (!function_exists('encrypt_route')) {
    function encrypt_route($path) {
        // ✅ TIDAK ENCRYPT - Return path biasa saja
        return $path;
    }
}

if (!function_exists('decrypt_route')) {
    function decrypt_route($encrypted) {
        // ✅ TIDAK DECRYPT - Return path biasa saja
        return $encrypted;
    }
}

if (!function_exists('secure_url')) {
    function secure_url($path) {
        // ✅ LANGSUNG RETURN base_url + path (TANPA /r/)
        return base_url($path);
    }
}

if (!function_exists('secure_redirect')) {
    function secure_redirect($uri = '', $method = 'auto', $code = NULL) {
        // ✅ LANGSUNG REDIRECT BIASA (TANPA ENCRYPTION)
        redirect($uri, $method, $code);
    }
}