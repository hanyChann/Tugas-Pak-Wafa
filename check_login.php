<?php
// 1. Mulai session HANYA JIKA belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Cek apakah user sudah login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== TRUE) {
    // Jika belum, lempar ke halaman login
    header("Location: login.php");
    exit;
}

// 3. Atur variabel untuk sidebar.php dan API
// Variabel ini DIASUMSIKAN sudah di-set saat proses_login.php
// Pastikan nama session ini (nama, role) sesuai dengan yang kamu simpan di proses_login.php
$user_id = $_SESSION['user_id'] ?? 0;
$username = $_SESSION['nama'] ?? 'User'; // Sesuai 'nama' dari database users kamu
$user_role = $_SESSION['role'] ?? 'user'; // Sesuai 'role' dari database users kamu

// Cek keamanan
if ($user_id === 0) {
    // Jika user_id tidak ada di session, paksa logout
    header("Location: logout.php");
    exit;
}
?>