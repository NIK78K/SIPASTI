<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>PHP Test</h1>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Server: " . $_SERVER['SERVER_SOFTWARE'] . "<br>";

// Test database extensions
echo "<h2>Database Extensions:</h2>";
echo "PDO: " . (extension_loaded('pdo') ? 'OK' : 'MISSING') . "<br>";
echo "PDO MySQL: " . (extension_loaded('pdo_mysql') ? 'OK' : 'MISSING') . "<br>";
echo "PDO PostgreSQL: " . (extension_loaded('pdo_pgsql') ? 'OK' : 'MISSING') . "<br>";
echo "MySQLi: " . (extension_loaded('mysqli') ? 'OK' : 'MISSING') . "<br>";
echo "PostgreSQL: " . (extension_loaded('pgsql') ? 'OK' : 'MISSING') . "<br>";

// Test other extensions
echo "<h2>Other Extensions:</h2>";
echo "cURL: " . (extension_loaded('curl') ? 'OK' : 'MISSING') . "<br>";
echo "OpenSSL: " . (extension_loaded('openssl') ? 'OK' : 'MISSING') . "<br>";
echo "mbstring: " . (extension_loaded('mbstring') ? 'OK' : 'MISSING') . "<br>";

phpinfo();
?>