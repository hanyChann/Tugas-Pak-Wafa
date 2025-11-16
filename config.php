<?php
/*
 * KONEKSI DATABASE
 * Ganti nilai-nilai ini dengan kredensial database kamu.
 */
define('DB_HOST', '127.0.0.1'); // atau 'localhost'
define('DB_USERNAME', 'root');
define('DB_PASSWORD', ''); // Kosongkan jika tidak ada password
define('DB_NAME', 'laundry'); // Nama database kamu dari laundry.sql

// Membuat koneksi
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi Gagal: " . $conn->connect_error);
}

// Mengatur header sebagai JSON
header('Content-Type: application/json');

// Memulai session untuk mengambil data user yang login
// (PENTING: file check_login.php kamu harus sudah memanggil session_start())
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>