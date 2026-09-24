<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_encrypt extends CI_Controller {
    
    public function index() {
        $this->load->helper('route');
        
        echo "<h2>🔐 Test Enkripsi URL</h2>";
        
        $paths = [
            'dashboard',
            'inventory/pengajuan',
            'admin',
            'teknisi',
            'ticket'
        ];
        
        echo "<table border='1' cellpadding='10' style='border-collapse:collapse'>";
        echo "<tr style='background:#333;color:white'>
                <th>Original Path</th>
                <th>Encrypted URL</th>
                <th>Test Link</th>
              </tr>";
        
        foreach ($paths as $path) {
            $encrypted = encrypt_route($path);
            $url = secure_url($path);
            
            echo "<tr>";
            echo "<td><code>$path</code></td>";
            echo "<td><small>$encrypted</small></td>";
            echo "<td><a href='$url' target='_blank'>🔗 Test</a></td>";
            echo "</tr>";
        }
        
        echo "</table>";
        
        // Test enkripsi-dekripsi
        echo "<hr><h3>✅ Test Enkripsi/Dekripsi</h3>";
        $test = 'inventory/pengajuan';
        $enc = encrypt_route($test);
        $dec = decrypt_route($enc);
        
        echo "Original: <code>$test</code><br>";
        echo "Encrypted: <code>$enc</code><br>";
        echo "Decrypted: <code>$dec</code><br><br>";
        
        if ($dec === $test) {
            echo "<strong style='color:green;font-size:18px'>✓ SUCCESS!</strong>";
        } else {
            echo "<strong style='color:red;font-size:18px'>✗ FAILED!</strong>";
        }
    }
}