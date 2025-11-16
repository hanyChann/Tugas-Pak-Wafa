<?php
session_start();
require 'koneksi.php'; // Menghubungkan ke database
ob_start(); // Mencegah error "header already sent"

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Ambil semua data dari form
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = $_POST['password']; // Password plain text
    $telepon = trim($_POST['telepon']);
    $alamat = trim($_POST['alamat']);

    // Validasi 1: Cek apakah ada yang kosong
    if (empty($nama) || empty($email) || empty($password) || empty($telepon) || empty($alamat)) {
        $_SESSION['register_error'] = "Semua kolom harus diisi.";
        $conn->close();
        ob_end_flush();
        header("Location: daftar_sekarang.php"); // Kembali ke form
        exit();
    }

    // Validasi 2: Cek panjang password
    if (strlen($password) < 8) {
        $_SESSION['register_error'] = "Password minimal harus 8 karakter.";
        $conn->close();
        ob_end_flush();
        header("Location: daftar_sekarang.php"); // Kembali ke form
        exit();
    }

    // Validasi 3: Cek apakah email sudah terdaftar
    $stmt_cek = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt_cek->bind_param("s", $email);
    $stmt_cek->execute();
    $result_cek = $stmt_cek->get_result();

    if ($result_cek->num_rows > 0) {
        // Email sudah ada
        $_SESSION['register_error'] = "Email ini sudah terdaftar.";
        $stmt_cek->close();
        $conn->close();
        ob_end_flush();
        header("Location: daftar_sekarang.php"); // Kembali ke form
        exit();
    }
    $stmt_cek->close(); // Tutup statement cek jika email aman

    // =============================================
    // BAGIAN PENTING: Keamanan & Pengaturan Role
    // =============================================
    
    // 1. Enkripsi password sebelum disimpan
    $hash_untuk_db = password_hash($password, PASSWORD_DEFAULT);
    
    // 2. Atur role default ke 'user' secara otomatis
    $role_default = 'user';

    // Siapkan query INSERT
    $stmt_insert = $conn->prepare(
        "INSERT INTO users (nama, email, password_hash, role, telepon, alamat) 
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    
    if (!$stmt_insert) {
        die("Query prepare gagal: " . $conn->error);
    }

    // Bind semua 6 parameter
    $stmt_insert->bind_param(
        "ssssss", 
        $nama, 
        $email, 
        $hash_untuk_db, // Simpan hash, BUKAN $password
        $role_default,  // Simpan role
        $telepon, 
        $alamat
    );

    // Eksekusi query
    if ($stmt_insert->execute()) {
        // Registrasi berhasil
        $_SESSION['login_success'] = "Registrasi berhasil! Silakan login dengan akun baru Anda.";
        $stmt_insert->close();
        $conn->close();
        ob_end_flush();
        header("Location: login.php"); // Arahkan ke halaman login
        exit();
    } else {
        // Registrasi gagal karena error database
        $_SESSION['register_error'] = "Registrasi gagal. Terjadi kesalahan pada database.";
        $stmt_insert->close();
        $conn->close();
        ob_end_flush();
        header("Location: daftar_sekarang.php"); // Kembali ke form
        exit();
    }

} else {
    // Jika file ini diakses langsung (bukan via POST)
    $_SESSION['register_error'] = "Akses tidak sah.";
    $conn->close();
    ob_end_flush();
    header("Location: daftar_sekarang.php"); // Kembali ke form
    exit();
}
?>