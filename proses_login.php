<?php
session_start();
require 'koneksi.php';

// (Opsional tapi direkomendasikan)
// Cegah output apa pun sebelum header()
ob_start(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password']; // Password dari form (plain text)

    if (empty($email) || empty($password)) {
        $_SESSION['login_error'] = "Email dan Kata Sandi harus diisi.";
        header("Location: login.php");
        exit();
    }

    // =============================================================
    // PERBAIKAN: Ambil juga 'role' untuk dashboard Anda nanti
    // =============================================================
    $stmt = $conn->prepare("SELECT user_id, nama, password_hash, role FROM users WHERE email = ?");
    if (!$stmt) {
        die("Query prepare gagal: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $password_from_db = $user['password_hash']; 
        if (password_verify($password, $password_from_db)) {
            
            
            $_SESSION['logged_in'] = TRUE;
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['role'] = $user['role']; // Simpan role untuk dashboard
            $_SESSION['login_success'] = "Selamat datang kembali, " . $user['nama'] . "!";

            header("Location: index.php");
            exit();

        } 
        // 2. Jika GAGAL, coba cek sebagai PLAIN TEXT (Cara Lama & Tidak Aman)
        elseif ($password === $password_from_db) {
            
            // --- LOGIN BERHASIL (Password masih plain text) ---
            
            // 2a. Login-kan pengguna
            $_SESSION['logged_in'] = TRUE;
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['role'] = $user['role']; // Simpan role untuk dashboard
            $_SESSION['login_success'] = "Selamat datang kembali, " . $user['nama'] . "!";

            // 2b. SEKARANG, upgrade password mereka di database
            // Ini tidak akan memengaruhi database Anda, hanya 1 baris ini saja
            try {
                $new_hash = password_hash($password, PASSWORD_DEFAULT);
                $user_id = $user['user_id'];
                
                $update_stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
                $update_stmt->bind_param("si", $new_hash, $user_id);
                $update_stmt->execute();
                $update_stmt->close();
            } catch (Exception $e) {
                // Jika update gagal, tidak apa-apa, pengguna tetap bisa login
                // Anda bisa mencatat error ini di log
            }
            
            // 2c. Arahkan ke index.php
            header("Location: index.php");
            exit();

        } 
        // 3. Jika Keduanya Gagal
        else {
            // --- LOGIN GAGAL ---
            // Password salah
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
ob_end_flush(); // Kirim output (jika ada)
?>