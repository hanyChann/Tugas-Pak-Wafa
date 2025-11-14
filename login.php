<?php
session_start();

$reg_success_message = null;
if (isset($_SESSION['reg_success'])) {
    $reg_success_message = $_SESSION['reg_success'];
    unset($_SESSION['reg_success']);
}

$login_error_message = null;
if (isset($_SESSION['login_error'])) {
    $login_error_message = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === TRUE) {
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SuperLaundry - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
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
  <body class="bg-blue-50 min-h-screen flex items-center justify-center font-sans p-4">
    <div class="w-full max-w-md">
      <div class="text-center mb-8">
        <a href="index.php" class="text-4xl font-extrabold text-biru-utama">💧 SuperLaundry</a>
        <h2 class="mt-4 text-2xl font-semibold text-biru-teks">Masuk ke Akun Anda</h2>
        
        <?php if ($reg_success_message): ?>
          <div class="text-green-700 font-medium bg-green-100 p-3 rounded-lg mt-4 border border-green-300 shadow-sm">
            <p class="font-semibold">✅ Registrasi Berhasil!</p>
            <p class="text-sm"><?= htmlspecialchars($reg_success_message); ?></p>
          </div>
        <?php endif; ?>

        <?php if ($login_error_message): ?>
          <div class="text-red-700 font-medium bg-red-100 p-3 rounded-lg mt-4 border border-red-300 shadow-sm">
            <p class="font-semibold">⚠️ Gagal Masuk</p>
            <p class="text-sm"><?= htmlspecialchars($login_error_message); ?></p>
          </div>
        <?php endif; ?>

        <p class="text-gray-500 mt-4">Masukkan email dan kata sandi Anda untuk melanjutkan.</p>
      </div>

      <div class="bg-white p-8 rounded-xl shadow-2xl border border-blue-100">
        <form action="proses_login.php" method="POST" class="space-y-6">
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input
              id="email"
              name="email"
              type="email"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-biru-utama focus:border-biru-utama"
              placeholder="contoh@mail.com"
            />
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi</label>
            <input
              id="password"
              name="password"
              type="password"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-biru-utama focus:border-biru-utama"
              placeholder="••••••••"
            />
          </div>

          <div>
            <button
              type="submit"
              class="w-full py-3 rounded-lg bg-biru-utama text-white font-semibold hover:bg-blue-700 transition duration-200 shadow-md"
            >
              Masuk
            </button>
          </div>
        </form>

        <div class="mt-6 text-center text-sm">
          <p class="text-gray-600">
            Belum punya akun?
     
            <a href="registrasi.php" class="font-medium text-biru-utama hover:text-blue-700 hover:underline transition">
              Daftar Sekarang
            </a>
          </p>
        </div>
      </div>
    </div>
  </body>
</html>