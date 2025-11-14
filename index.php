<?php
session_start();

$logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === TRUE;
$nama_user = $logged_in ? htmlspecialchars($_SESSION['nama']) : 'Guest';

$login_success_message = '';
if (isset($_SESSION['login_success'])) {
    $login_success_message = $_SESSION['login_success'];
    unset($_SESSION['login_success']); 
}
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SuperLaundry - Layanan Laundry Cepat & Bersih</title>
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
            keyframes: {
              fadeInUp: {
                '0%': { opacity: '0', transform: 'translateY(20px)' },
                '100%': { opacity: '1', transform: 'translateY(0)' },
              },
            },
            animation: {
              fadeInUp: 'fadeInUp 0.6s ease-out',
            },
          },
        },
      };
    </script>
    <style>
      @media (prefers-reduced-motion: reduce) {
        .animated {
          animation: none !important;
        }
      }
    </style>
  </head>
  <body class="bg-gray-50 font-sans">
    
    <div id="mobile-menu" class="hidden md:hidden fixed inset-0 bg-blue-900/90 z-[60] p-4">
        <!-- ... (Isi menu mobile sama seperti sebelumnya) ... -->
        <button id="close-menu" class="absolute top-4 right-4 text-white p-2">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <div class="flex flex-col space-y-4 pt-16 text-center">
            <a href="index.php" class="text-white text-2xl font-semibold hover:text-blue-200 py-2">Home</a>
            <a href="#layanan" class="text-blue-200 text-2xl font-semibold hover:text-white py-2">Layanan</a>
            <a href="#harga" class="text-blue-200 text-2xl font-semibold hover:text-white py-2">Harga</a>
            <a href="#kontak" class="text-blue-200 text-2xl font-semibold hover:text-white py-2">Kontak</a>
            <?php if ($logged_in): ?>
                <a href="logout.php" class="bg-red-500 text-white px-6 py-3 mt-4 rounded-full font-bold hover:bg-red-600 transition">Logout (<?php echo $nama_user; ?>)</a>
            <?php else: ?>
                <a href="login.php" class="bg-white text-blue-600 px-6 py-3 mt-4 rounded-full font-bold hover:bg-gray-100 transition">Masuk / Login</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Perbaikan: Navbar -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <div class="flex-shrink-0">
            <a href="index.php" class="text-2xl font-bold text-biru-utama"> 
              💧 SuperLaundry
            </a>
          </div>
          <!-- Menu Desktop -->
          <div class="hidden md:flex md:space-x-8 items-center">
            <a href="index.php" class="text-gray-700 hover:text-biru-utama font-medium">Home</a>
            <a href="#layanan" class="text-gray-500 hover:text-biru-utama font-medium">Layanan</a>
            <a href="#harga" class="text-gray-500 hover:text-biru-utama font-medium">Harga</a>
            <a href="#kontak" class="text-gray-500 hover:text-biru-utama font-medium">Kontak</a>
            <!-- LOGIKA PHP PENTING DI SINI -->
            <?php if ($logged_in): ?>
                <!-- Jika sudah login, tampilkan nama pengguna dan tombol Logout -->
                <span class="text-biru-teks font-bold">Halo, <?php echo $nama_user; ?></span>
                <a
                  href="logout.php"
                  class="bg-red-500 text-white ml-6 px-4 py-2 rounded-full font-semibold hover:bg-red-700 transition duration-300 shadow-md"
                >
                  Logout
                </a>
            <?php else: ?>
                <!-- Jika belum login, tampilkan tombol Masuk/Login -->
                <a
                  href="login.php"
                  class="bg-biru-utama text-white ml-6 px-4 py-2 rounded-full font-semibold hover:bg-blue-700 transition duration-300 shadow-md"
                >
                  Masuk / Login
                </a>
            <?php endif; ?>
            <!-- AKHIR LOGIKA PHP -->
            
          </div>
          <!-- Tombol Menu Mobile -->
          <div class="md:hidden">
            <button id="open-menu" aria-label="Toggle Menu" class="text-gray-700 p-2 hover:text-blue-600 transition">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6h16M4 12h16m-7 6h7"
                />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </nav>
    
    <main>
    <!-- Tampilkan pesan sukses login/logout di bagian atas -->
    <?php if ($login_success_message): ?>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md" role="alert">
                <p class="font-bold">Informasi Sesi</p>
                <p><?php echo $login_success_message; ?></p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Header (Konten di bawah ini sama seperti sebelumnya) -->
    <header class="bg-biru-utama text-white animated fadeInUp">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-32">
        <div class="flex flex-col md:flex-row items-center">
          <div class="md:w-1/2 text-center md:text-left mb-10 md:mb-0">
            <h1 class="text-4xl md:text-6xl font-extrabold leading-tight mb-4">
              Pakaian Bersih Bebas Repot,
              <span class="block text-blue-200">Antar Jemput Gratis!</span>
            </h1>
            <p class="text-xl text-blue-100 mb-8">
              Biarkan kami yang urus cucian Anda. Cepat, bersih, wangi, dan
              profesional. Nikmati waktu luang Anda.
            </p>
            <a
              href="#harga"
              class="bg-white text-biru-utama font-bold py-3 px-8 rounded-full text-lg hover:bg-gray-100 transition duration-300 shadow-xl transform hover:scale-105"
            >
              Cek Harga Sekarang 🚀
            </a>
          </div>
          <div class="md:w-1/2 flex justify-center mt-10 md:mt-0">
            <img 
              src="asset/Mesincuci.png" 
              alt="Ilustrasi Mesin Cuci Modern" 
              class="w-full max-w-sm drop-shadow-2xl" 
            />
          </div>
        </div>
      </div>
    </header>

    <!-- Bagian Layanan, Harga, Cara Kerja, Testimoni, dan Footer -->
    
    <!-- (Konten Bagian Layanan - #layanan) -->
    <section id="layanan" class="py-20 bg-blue-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold text-biru-teks mb-4">
            Layanan Unggulan Kami ✨
          </h2>
          <p class="text-lg text-gray-600 max-w-2xl mx-auto">
            Kami menyediakan berbagai layanan untuk semua kebutuhan cucian Anda, dijamin memuaskan.
          </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
          <!-- Card Kiloan -->
          <div
            class="bg-white rounded-xl shadow-xl p-8 transform hover:-translate-y-2 transition duration-500 border-t-4 border-biru-utama"
          >
            <div
              class="bg-blue-100 text-biru-utama rounded-full h-16 w-16 flex items-center justify-center mb-6"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
              </svg>
            </div>
            <h3 class="text-2xl font-semibold text-biru-teks mb-3">
              Cuci Kiloan Hemat
            </h3>
            <p class="text-gray-600">
              Solusi hemat untuk pakaian harian Anda. Bersih, wangi, dan rapi.
              Selesai dalam **1-2 hari**.
            </p>
          </div>
          
          <!-- Card Satuan -->
          <div
            class="bg-white rounded-xl shadow-xl p-8 transform hover:-translate-y-2 transition duration-500 border-t-4 border-biru-utama"
          >
            <div
              class="bg-blue-100 text-biru-utama rounded-full h-16 w-16 flex items-center justify-center mb-6"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12 12 0 0012 21.642a12 12 0 008.618-3.04"/>
              </svg>
            </div>
            <h3 class="text-2xl font-semibold text-biru-teks mb-3">
              Cuci Satuan Premium
            </h3>
            <p class="text-gray-600">
              Perawatan premium untuk pakaian kesayangan Anda seperti **jas, gaun,
              atau kebaya**. Perawatan khusus tanpa dicampur.
            </p>
          </div>

          <!-- Card Express -->
          <div
            class="bg-white rounded-xl shadow-xl p-8 transform hover:-translate-y-2 transition duration-500 border-t-4 border-biru-utama"
          >
            <div
              class="bg-blue-100 text-biru-utama rounded-full h-16 w-16 flex items-center justify-center mb-6"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <h3 class="text-2xl font-semibold text-biru-teks mb-3">
              Express Kilat (6 Jam)
            </h3>
            <p class="text-gray-600">
              Butuh cepat? Layanan express kami siap membuat pakaian Anda bersih
              dan siap pakai dalam **hitungan jam**.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- (Konten Bagian Harga - #harga) -->
    <section id="harga" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-biru-teks mb-4">
                    Pilihan Harga Terbaik Kami 💰
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Kualitas premium tidak harus mahal. Pilih paket yang sesuai kebutuhan Anda.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Paket 1: Kiloan Reguler -->
                <div class="bg-white border border-gray-200 rounded-xl p-8 text-center shadow-lg transform hover:shadow-xl transition duration-300">
                    <h3 class="text-2xl font-bold text-biru-teks mb-2">Reguler Kiloan</h3>
                    <p class="text-sm text-gray-500 mb-6">Paling Populer</p>
                    <div class="text-4xl font-extrabold text-biru-utama mb-4">
                        Rp 8.000<span class="text-xl font-normal text-gray-500">/kg</span>
                    </div>
                    <ul class="text-left space-y-3 text-gray-600 mb-8">
                        <li class="flex items-center">✅ Cuci & Kering</li>
                        <li class="flex items-center">✅ Lipat Rapi</li>
                        <li class="flex items-center">✅ Pewangi Premium</li>
                        <li class="flex items-center">❌ Antar-Jemput (Min. 5kg)</li>
                        <li class="flex items-center">🕒 Selesai 2 Hari</li>
                    </ul>
                    <a href="#" class="block bg-biru-utama text-white py-3 rounded-full font-semibold hover:bg-blue-700 transition">Pesan Sekarang</a>
                </div>

                <!-- Paket 2: Kiloan Setrika -->
                <div class="bg-biru-utama border-4 border-blue-400 rounded-xl p-8 text-center shadow-2xl scale-105">
                    <h3 class="text-3xl font-bold text-white mb-2">Lengkap Setrika</h3>
                    <p class="text-sm text-blue-200 mb-6">Pilihan Terbaik Kami</p>
                    <div class="text-5xl font-extrabold text-yellow-300 mb-4">
                        Rp 12.000<span class="text-xl font-normal text-blue-200">/kg</span>
                    </div>
                    <ul class="text-left space-y-3 text-blue-100 mb-8">
                        <li class="flex items-center">✅ Cuci, Kering & **Setrika**</li>
                        <li class="flex items-center">✅ Lipat Rapi</li>
                        <li class="flex items-center">✅ Pewangi Premium</li>
                        <li class="flex items-center">✅ **Antar-Jemput Gratis**</li>
                        <li class="flex items-center">🕒 Selesai 24 Jam</li>
                    </ul>
                    <a href="#" class="block bg-white text-biru-utama py-3 rounded-full font-bold hover:bg-gray-100 transition shadow-lg">Ambil Paket Ini!</a>
                </div>

                <!-- Paket 3: Satuan Premium -->
                <div class="bg-white border border-gray-200 rounded-xl p-8 text-center shadow-lg transform hover:shadow-xl transition duration-300">
                    <h3 class="text-2xl font-bold text-biru-teks mb-2">Satuan (per Pcs)</h3>
                    <p class="text-sm text-gray-500 mb-6">Untuk Pakaian Khusus</p>
                    <div class="text-4xl font-extrabold text-biru-utama mb-4">
                        Mulai dari
                        <span class="text-xl font-normal text-gray-500">Rp 20.000</span>
                    </div>
                    <ul class="text-left space-y-3 text-gray-600 mb-8">
                        <li class="flex items-center">✅ Perawatan Khusus</li>
                        <li class="flex items-center">✅ Dry Cleaning (Opsional)</li>
                        <li class="flex items-center">✅ Pengemasan Eksklusif</li>
                        <li class="flex items-center">✅ Antar-Jemput</li>
                        <li class="flex items-center">🕒 Waktu Tergantung Jenis</li>
                    </ul>
                    <a href="#" class="block bg-gray-500 text-white py-3 rounded-full font-semibold hover:bg-gray-600 transition">Konsultasi Dulu</a>
                </div>
            </div>
        </div>
    </section>

    <!-- (Konten Bagian Cara Kerja - #cara-kerja) -->
    <section id="cara-kerja" class="py-20 bg-blue-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold text-biru-teks mb-4">
            3 Langkah Mudah & Cepat 💨
          </h2>
          <p class="text-lg text-gray-600 max-w-2xl mx-auto">
            Proses pemesanan sangat mudah dan cepat. Kami urus sisanya.
          </p>
        </div>

        <div
          class="flex flex-col md:flex-row justify-between space-y-10 md:space-y-0 md:space-x-8"
        >
          <!-- Langkah 1 -->
          <div class="flex flex-col items-center text-center">
            <div
              class="bg-biru-utama text-white rounded-full h-20 w-20 flex items-center justify-center text-3xl font-bold mb-4 shadow-lg"
            >
              1
            </div>
            <h3 class="text-xl font-semibold text-biru-teks mb-2">
              Pesan & Timbang
            </h3>
            <p class="text-gray-600 max-w-xs">
              Hubungi kami via WA atau web. Kami akan datang menjemput dan
              menimbang cucian Anda.
            </p>
          </div>
          <!-- Langkah 2 -->
          <div class="flex flex-col items-center text-center">
            <div
              class="bg-biru-utama text-white rounded-full h-20 w-20 flex items-center justify-center text-3xl font-bold mb-4 shadow-lg"
            >
              2
            </div>
            <h3 class="text-xl font-semibold text-biru-teks mb-2">
              Kami Proses Profesional
            </h3>
            <p class="text-gray-600 max-w-xs">
              Pakaian Anda dicuci terpisah, menggunakan deterjen berkualitas,
              dan disetrika dengan sempurna.
            </p>
          </div>
          <!-- Langkah 3 -->
          <div class="flex flex-col items-center text-center">
            <div
              class="bg-biru-utama text-white rounded-full h-20 w-20 flex items-center justify-center text-3xl font-bold mb-4 shadow-lg"
            >
              3
            </div>
            <h3 class="text-xl font-semibold text-biru-teks mb-2">
              Antar Kembali Rapi
            </h3>
            <p class="text-gray-600 max-w-xs">
              Kami antar kembali pakaian Anda dalam keadaan bersih, wangi, dan
              rapi. Siap pakai di depan pintu Anda!
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- (Konten Bagian Testimoni - #testimoni) -->
    <section id="testimoni" class="py-20 bg-white">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-biru-teks mb-8">
          Apa Kata Pelanggan Kami? ⭐⭐⭐⭐⭐
        </h2>

        <div class="bg-blue-50 rounded-xl shadow-2xl p-8 border-t-8 border-biru-utama">
          <img
            src="https://via.placeholder.com/100" 
            alt="Foto Pelanggan"
            class="w-24 h-24 rounded-full mx-auto mb-4 border-4 border-blue-400 object-cover"
          />
          <blockquote class="text-xl italic text-gray-700 mb-6">
            "SuperLaundry benar-benar penyelamat! Hasilnya selalu bersih, wangi,
            dan lipatannya rapi banget. Layanan antar jemputnya juga on-time.
            **Sangat direkomendasikan!**"
          </blockquote>
          <p class="font-bold text-biru-utama text-lg">Anya Geraldine</p>
          <p class="text-gray-500">Mahasiswa, Jakarta</p>
        </div>
      </div>
    </section>
    </main>

    <!-- (Konten Footer - #kontak) -->
    <footer id="kontak" class="bg-biru-teks text-blue-100 py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-4 gap-8">
          <!-- Kolom 1: Logo & Deskripsi -->
          <div>
            <h4 class="text-2xl font-bold text-white mb-4">💧 SuperLaundry</h4>
            <p class="text-blue-200 text-sm">
              Kami adalah layanan laundry profesional yang berkomitmen
              memberikan hasil terbaik untuk pakaian Anda, hemat waktu, dan
              bebas repot.
            </p>
          </div>

          <!-- Kolom 2: Navigasi -->
          <div>
            <h4 class="text-xl font-bold text-white mb-4">Navigasi Cepat</h4>
            <ul class="space-y-2">
              <li>
                <a href="index.php" class="text-blue-200 hover:text-white transition">Home</a>
              </li>
              <li>
                <a href="#layanan" class="text-blue-200 hover:text-white transition"
                  >Layanan</a
                >
              </li>
              <li>
                <a href="#harga" class="text-blue-200 hover:text-white transition"
                  >Harga</a
                >
              </li>
              <li>
                <a href="#kontak" class="text-blue-200 hover:text-white transition"
                  >Kontak</a
                >
              </li>
            </ul>
          </div>

          <!-- Kolom 3: Hubungi Kami -->
          <div>
            <h4 class="text-xl font-bold text-white mb-4">Hubungi Kami</h4>
            <ul class="space-y-2 text-blue-200">
              <li>📍 Jl. Kebersihan No. 123, Jakarta</li>
              <li>📞 **(021) 123-4567**</li>
              <li>📧 halo@superlaundry.com</li>
              <li>⏰ Buka: 08:00 - 20:00 WIB</li>
            </ul>
          </div>

          <!-- Kolom 4: Sosial Media -->
          <div>
            <h4 class="text-xl font-bold text-white mb-4">Ikuti Kami</h4>
            <div class="flex space-x-4">
                <a href="#" class="text-blue-200 hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.588-1.333h2.412v-3h-3.412c-4.145 0-5.588 2.333-5.588 5.667v1.333z"/></svg>
                </a>
                <a href="#" class="text-blue-200 hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.77 1.624 4.981 4.765.101 1.542.112 1.815.112 4.025s-.011 2.483-.112 4.025c-.21 3.14-.932 4.618-4.981 4.765-1.266.058-1.645.07-4.85.07s-3.585-.012-4.85-.07c-3.252-.148-4.77-1.624-4.981-4.765-.101-1.542-.112-1.815-.112-4.025s.011-2.483.112-4.025c.211-3.14 1.233-4.509 4.981-4.765 1.266-.057 1.645-.07 4.85-.07zm0 10.163c-2.076 0-3.754 1.678-3.754 3.754s1.678 3.754 3.754 3.754 3.754-1.678 3.754-3.754-1.678-3.754-3.754-3.754zm4.619-10.871c0 .542.44.982.982.982s.982-.44.982-.982-.44-.982-.982-.982-.982.44-.982.982z"/></svg>
                </a>
            </div>
          </div>
        </div>

        <div
          class="border-t border-blue-700 mt-10 pt-8 text-center text-blue-300"
        >
          <p>
            &copy; 2025 SuperLaundry. Dibuat dengan ❤ menggunakan Tailwind CSS.
          </p>
        </div>
      </div>
    </footer>
    
    <!-- Script untuk Menu Mobile (Tidak berubah) -->
    <script>
        const openMenuBtn = document.getElementById('open-menu');
        const closeMenuBtn = document.getElementById('close-menu');
        const mobileMenu = document.getElementById('mobile-menu');
        const navLinks = mobileMenu.querySelectorAll('a');

        // Fungsi untuk membuka menu
        openMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; 
        });

        // Fungsi untuk menutup menu
        const closeMenu = () => {
            mobileMenu.classList.add('hidden');
            document.body.style.overflow = ''; 
        };

        closeMenuBtn.addEventListener('click', closeMenu);

        // Menutup menu saat link di klik (untuk navigasi ke section)
        navLinks.forEach(link => {
            link.addEventListener('click', closeMenu);
        });
    </script>
  </body>
</html>