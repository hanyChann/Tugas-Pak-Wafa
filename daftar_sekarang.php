<?php
session_start();

// 1. Jika pengguna sudah login, lempar ke dashboard
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === TRUE) {
    header("Location: dashboard.php");
    exit;
}

// 2. Ambil pesan error jika ada (dikirim dari proses_registrasi.php)
$register_error_message = '';
if (isset($_SESSION['register_error'])) {
    $register_error_message = $_SESSION['register_error'];
    unset($_SESSION['register_error']); // Hapus setelah ditampilkan
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Akun Baru - SuperLaundry</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      // Konfigurasi Tailwind agar warna sesuai dengan index.php
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
                Buat Akun Baru
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Sudah punya akun?
                <a href="login.php" class="font-medium text-biru-utama hover:text-blue-700">
                    Login di sini
                </a>
            </p>
        </div>

        <?php if ($register_error_message): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg my-6" role="alert">
                <p><?php echo $register_error_message; ?></p>
            </div>
        <?php endif; ?>

        <form class="mt-8 space-y-5" action="proses_registrasi.php" method="POST">
            
            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                <input id="nama" name="nama" type="text" required class="mt-1 appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Nama Lengkap Anda">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                <input id="email" name="email" type="email" autocomplete="email" required class="mt-1 appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="contoh@email.com">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Kata Sandi</label>
                <input id="password" name="password" type="password" autocomplete="new-password" required class="mt-1 appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Minimal 8 karakter">
            </div>

             <div>
                <label for="telepon" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                <input id="telepon" name="telepon" type="tel" required class="mt-1 appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="08123456789">
            </div>

            <div>
                <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                <textarea id="alamat" name="alamat" rows="3" required class="mt-1 appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Jalan, Nomor Rumah, Kota, dll."></textarea>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-biru-utama hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300">
                    Daftar Sekarang
                </button>
            </div>
            <div class="text-center pt-2">
                    <a href="login.php" class="font-medium text-gray-700 bg-white hover:bg-red-600 hover:text-white ...">
                      Keluar
                </a>
            </div>
        </form>
    </div>
</body>
</html>