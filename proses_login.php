<?php
session_start();
require 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $_SESSION['login_error'] = "Email dan Kata Sandi harus diisi.";
        header("Location: login.php");
        exit();
    }

    // Ambil data user berdasarkan email
    $stmt = $conn->prepare("SELECT user_id, nama, password_hash FROM users WHERE email = ?");
    if (!$stmt) {
        die("Query prepare gagal: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Karena password belum di-hash, langsung bandingkan
        if ($password === $user['password_hash']) {
            $_SESSION['logged_in'] = TRUE;
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['nama'] = $user['nama'];

            // Tambahkan pesan sukses
            $_SESSION['login_success'] = "Selamat datang kembali, " . $user['nama'] . "!";

            header("Location: index.php");
            exit();
        } else {
            $_SESSION['login_error'] = "Kata sandi yang Anda masukkan salah.";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['login_error'] = "Email tidak ditemukan.";
        header("Location: login.php");
        exit();
    }

    $stmt->close();
} else {
    $_SESSION['login_error'] = "Akses tidak sah.";
    header("Location: login.php");
    exit();
}

$conn->close();
?>
