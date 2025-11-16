<?php
session_start();

// 1. Jika pengguna sudah login, lempar ke dashboard
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === TRUE) {
    header("Location: dashboard.php");
    exit;
}

// 2. Ambil pesan error jika ada (dari proses_login.php)
$login_error_message = '';
if (isset($_SESSION['login_error'])) {
    $login_error_message = $_SESSION['login_error'];
    unset($_SESSION['login_error']); // Hapus setelah ditampilkan
}

// 3. Ambil pesan sukses jika ada (dari proses_registrasi.php)
$login_success_message = '';
if (isset($_SESSION['login_success'])) {
    $login_success_message = $_SESSION['login_success'];
    unset($_SESSION['login_success']); // Hapus setelah ditampilkan
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - SuperLaundry</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      // Konfigurasi Tailwind agar warna sesuai
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              "biru-utama": "#0052cc", 
              "biru-muda": "#f0f5ff", 
              "biru-teks": "#003380", 
            },
          },
        },
      };
    </script>
</head>
<body class="bg-biru-muda min-h-screen flex items-center justify-center py-12 px-4">

    <div class="max-w-md w-full bg-white p-8 md:p-10 rounded-xl shadow-2xl">
        <div>
            <h2 class="text-center text-3xl font-extrabold text-biru-teks">
                Login Akun Anda
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Belum punya akun?
                <a href="daftar_sekarang.php" class="font-medium text-biru-utama hover:text-blue-700">
                    Daftar di sini
                </a>
            </p>
        </div>

        <?php if ($login_error_message): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg my-6" role="alert">
                <p><?php echo $login_error_message; ?></p>
            </div>
        <?php endif; ?>

        <?php if ($login_success_message): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg my-6" role="alert">
                <p><?php echo $login_success_message; ?></p>
            </div>
        <?php endif; ?>

        <form class="mt-8 space-y-6" action="proses_login.php" method="POST">
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                <input id="email" name="email" type="email" autocomplete="email" required class="mt-1 appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="contoh@email.com">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Kata Sandi</label>
                <input id="password" name="password" type="password" autocomplete="current-password" required class="mt-1 appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Kata Sandi Anda">
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-biru-utama hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300">
                    Masuk
                </button>
            </div>
        </form>
    </div>
</body>
</html>