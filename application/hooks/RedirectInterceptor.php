<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RedirectInterceptor {
    
    public function override_redirect() {
        // Override global redirect function
        if (!function_exists('redirect')) {
            /**
             * Header Redirect with Encryption
             */
            function redirect($uri = '', $method = 'auto', $code = NULL) {
                // Load CI instance
                $CI =& get_instance();
                
                // Whitelist - URI yang tidak perlu dienkripsi
                $whitelist = ['auth/', 'login', 'logout', 'r/'];
                
                $should_encrypt = true;
                foreach ($whitelist as $allowed) {
                    if (strpos($uri, $allowed) !== false) {
                        $should_encrypt = false;
                        break;
                    }
                }
                
                // ✅ AUTO ENCRYPT
                if ($should_encrypt && isset($CI->session)) {
                    // Hapus 'index.php/' jika ada
                    $clean_uri = str_replace('index.php/', '', $uri);
                    
                    // Load helper jika belum
                    if (!function_exists('encrypt_route')) {
                        $CI->load->helper('route');
                    }
                    
                    // Simpan encrypted URL
                    $encrypted_url = 'r/' . encrypt_route($clean_uri);
                    $CI->session->set_userdata('encrypted_url', $encrypted_url);
                }
                
                // ✅ PANGGIL REDIRECT CI ASLI
                if ( ! preg_match('#^(\w+:)?//#i', $uri)) {
                    $uri = site_url($uri);
                }

                if ($method === 'auto') {
                    $code = 302;
                } elseif ($method === 'refresh') {
                    header('Refresh: 0; url=' . $uri);
                    return;
                }

                header('Location: ' . $uri, TRUE, $code);
                exit;
            }
        }
    }
}