<?php
session_start();

$logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === TRUE;
$nama_user = $logged_in ? htmlspecialchars($_SESSION['nama']) : 'Guest';

// Bagian ini akan mengambil pesan "Selamat datang" saat login,
// tapi tidak akan ada pesan saat logout (karena logout.php sudah diubah)
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
        <button id="close-menu" class="absolute top-4 right-4 text-white p-2">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <div class="flex flex-col space-y-4 pt-16 text-center">
            <a href="index.php" class="text-white text-2xl font-semibold hover:text-blue-200 py-2">Home</a>
            <a href="#layanan" class="text-blue-200 text-2xl font-semibold hover:text-white py-2">Layanan</a>
            <a href="#kontak" class="text-blue-200 text-2xl font-semibold hover:text-white py-2">Kontak</a>
            <?php if ($logged_in): ?>
                <a href="dashboard.php" class="text-white text-2xl font-semibold hover:text-blue-200 py-2">Pesan</a>
                <a href="logout.php" class="bg-red-500 text-white px-6 py-3 mt-4 rounded-full font-bold hover:bg-red-600 transition">Logout (<?php echo $nama_user; ?>)</a>
            <?php else: ?>
                <a href="login.php" class="bg-white text-blue-600 px-6 py-3 mt-4 rounded-full font-bold hover:bg-gray-100 transition">Masuk / Login</a>
            <?php endif; ?>
        </div>
    </div>

    <nav class="bg-white shadow-lg sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <div class="flex-shrink-0">
            <a href="index.php" class="text-2xl font-bold text-biru-utama"> 
              💧 SuperLaundry
            </a>
          </div>
          
          <div class="hidden md:flex md:space-x-8 items-center">
            <a href="index.php" class="text-gray-700 hover:text-biru-utama font-medium">Home</a>
            <a href="#layanan" class="text-gray-500 hover:text-biru-utama font-medium">Layanan</a>
            <a href="#kontak" class="text-gray-500 hover:text-biru-utama font-medium">Kontak</a>
            
            <?php if ($logged_in): ?>
                <a href="dashboard.php" class="text-gray-500 hover:text-biru-utama font-medium">Order</a>
                <span class="text-biru-teks font-bold">Halo, <?php echo $nama_user; ?></span>
                <a
                    href="logout.php"
                    class="bg-red-500 text-white ml-6 px-4 py-2 rounded-full font-semibold hover:bg-red-700 transition duration-300 shadow-md"
                >
                    Logout
                </a>
            <?php else: ?>
                <a
                    href="login.php"
                    class="bg-biru-utama text-white ml-6 px-4 py-2 rounded-full font-semibold hover:bg-blue-700 transition duration-300 shadow-md"
                >
                    Masuk / Login
                </a>
            <?php endif; ?> </div>
          
          <div class="md:hidden">
            <button id="open-menu" aria-label="Toggle Menu" class="text-gray-700 p-2 hover:text-blue-600 transition">
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </nav>
    
    <main>
    <?php if ($login_success_message): ?>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md" role="alert">
                <p class="font-bold">Informasi</p>
                <p><?php echo $login_success_message; ?></p>
            </div>
        </div>
    <?php endif; ?>

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
              href="<?php echo $logged_in ? 'dashboard.php' : 'login.php'; ?>"
              class="bg-white text-biru-utama font-bold py-3 px-8 rounded-full text-lg hover:bg-gray-100 transition duration-300 shadow-xl transform hover:scale-105"
            >
              Pesan Sekarang 🚀
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
            
          <!-- KARTU 1: Cuci Kering Reguler (Ada di DB) -->
          <div class="bg-white rounded-xl shadow-xl p-8 transform hover:-translate-y-2 transition duration-500 border-t-4 border-biru-utama">
            <div class="bg-blue-100 text-biru-utama rounded-full h-16 w-16 flex items-center justify-center mb-6">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.38 3.46 16 2a4 4 0 0 0-8 0L3.62 3.46a2 2 0 0 0-1.34 2.2l1.52 12.18A2 2 0 0 0 5.78 20h12.44a2 2 0 0 0 1.98-2.16l1.52-12.18a2 2 0 0 0-1.34-2.2zM8 2c0-1.1.9-2 2-2s2 .9 2 2L8 2z"/>
              </svg>
            </div>
            <h3 class="text-2xl font-semibold text-biru-teks mb-3">Cuci Kering Reguler</h3>
            <p class="text-gray-600 mb-4 h-20">Pakaian bersih, kering 100%, dan dilipat rapi. Pilihan praktis untuk pakaian harian (2 hari kerja).</p>
            <div class="flex justify-between items-center">
                <span class="text-lg font-bold text-biru-utama">Rp 8.000/kg</span>
                <a href="<?php echo $logged_in ? 'dashboard.php' : 'login.php'; ?>" class="bg-biru-utama text-white text-sm py-2 px-4 rounded-full font-semibold hover:bg-blue-700 transition">
                    Pesan
                </a>
            </div>
          </div>
          
          <!-- KARTU 2: Cuci Setrika (Ada di DB) -->
          <div class="bg-white rounded-xl shadow-xl p-8 transform hover:-translate-y-2 transition duration-500 border-t-4 border-biru-utama">
            <div class="bg-blue-100 text-biru-utama rounded-full h-16 w-16 flex items-center justify-center mb-6">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 2H4a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zM12 7v10m-4-5h8m-8-3h8"/>
                <path d="M16 2v4a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2V2"/>
              </svg>
            </div>
            <h3 class="text-2xl font-semibold text-biru-teks mb-3">Cuci Setrika</h3>
            <p class="text-gray-600 mb-4 h-20">Paket lengkap! Pakaian dicuci, dikeringkan, disetrika licin, dan diwangikan. Siap pakai!</p>
            <div class="flex justify-between items-center">
                <span class="text-lg font-bold text-biru-utama">Rp 12.000/kg</span>
                <a href="<?php echo $logged_in ? 'dashboard.php' : 'login.php'; ?>" class="bg-biru-utama text-white text-sm py-2 px-4 rounded-full font-semibold hover:bg-blue-700 transition">
                    Pesan
                </a>
            </div>
          </div>

          <!-- KARTU 3: Kilat 6 Jam (Ada di DB) -->
          <div class="bg-white rounded-xl shadow-xl p-8 transform hover:-translate-y-2 transition duration-500 border-t-4 border-biru-utama">
            <div class="bg-blue-100 text-biru-utama rounded-full h-16 w-16 flex items-center justify-center mb-6">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
              </svg>
            </div>
            <h3 class="text-2xl font-semibold text-biru-teks mb-3">Kilat 6 Jam</h3>
            <p class="text-gray-600 mb-4 h-20">Layanan cuci setrika super cepat. Pakaian Anda siap hanya dalam 6 jam.</p>
            <div class="flex justify-between items-center">
                <span class="text-lg font-bold text-biru-utama">Rp 20.000/kg</span>
                <a href="<?php echo $logged_in ? 'dashboard.php' : 'login.php'; ?>" class="bg-biru-utama text-white text-sm py-2 px-4 rounded-full font-semibold hover:bg-blue-700 transition">
                    Pesan
                </a>
            </div>
          </div>

          <!-- KARTU 4: Cuci Satuan Jas (Ada di DB) -->
          <div class="bg-white rounded-xl shadow-xl p-8 transform hover:-translate-y-2 transition duration-500 border-t-4 border-biru-utama">
            <div class="bg-blue-100 text-biru-utama rounded-full h-16 w-16 flex items-center justify-center mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 10V8.2A2 2 0 0 0 20.2 6H3.8A2 2 0 0 0 2 8.2V10m0 4v1.8A2 2 0 0 0 3.8 18h16.4a2 2 0 0 0 1.8-1.8V14"/>
                    <path d="M2 10v4h20v-4H2z"/>
                    <path d="M6 10v4m4-4v4m4-4v4m4-4v4"/>
                </svg>
            </div>
             <h3 class="text-2xl font-semibold text-biru-teks mb-3">Cuci Satuan Jas </h3>
            <p class="text-gray-600 mb-4 h-20">Perawatan khusus jas dan blazer</p>
            <div class="flex justify-between items-center">
                <span class="text-lg font-bold text-biru-utama">Rp 50.000/pcs</span>
                <a href="<?php echo $logged_in ? 'dashboard.php' : 'login.php'; ?>" class="bg-biru-utama text-white text-sm py-2 px-4 rounded-full font-semibold hover:bg-blue-700 transition">
                    Pesan
                </a>
            </div>
          </div>
          
          <!-- 
            KARTU UNTUK LAUNDRY SEPATU, HELM, BEDCOVER, TIKAR, DLL.
            TELAH DIHAPUS
          -->

        </div> 
      </div>
    </section>
        <section id="layanan" class="py-20 bg-blue sky-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
          <h2 class="text-3xl md:text-4xl font-bold text-biru-teks mb-4">
            🎒 Paket Hemat Mahasiswa
          </h2>
          <p class="text-lg text-gray-600 max-w-2xl mx-auto">
             Dirancang khusus untuk kebutuhan anak kos. Bersih, cepat, dan ramah di kantong!
          </p>
        </div>
        <div class="grid md:grid-cols-3 gap-8"></div>

        <div class="max-w-4xl mx-auto grid md:grid-cols-2 gap-8">
          <div class="bg-biru-muda rounded-xl shadow-lg overflow-hidden border-l-8 border-biru-utama">
            <div class="flex items-center p-6">
              <div class="flex-shrink-0 bg-biru-utama text-white rounded-full h-20 w-20 flex items-center justify-center shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>

              <div class="ml-6">

                <h3 class="text-2xl font-bold text-biru-teks">Paket Kebut 3 Hari</h3>

                <p class="text-gray-700 mt-1">Cuci + Setrika. Dijamin rapi untuk dipakai kuliah lagi.</p>

                <span class="text-xl font-extrabold text-biru-utama mt-2 block">Rp 10.000 / kg</span>

                <a href="<?php echo $logged_in ? 'dashboard.php' : 'login.php'; ?>" class="mt-3 inline-block bg-biru-utama text-white text-xs py-2 px-3 rounded-full font-semibold hover:bg-blue-700 transition">

                    Pesan Paket Ini

                </a>

              </div>

            </div>

          </div>

         

          <div class="bg-biru-muda rounded-xl shadow-lg overflow-hidden border-l-8 border-biru-utama">

            <div class="flex items-center p-6">

              <div class="flex-shrink-0 bg-biru-utama text-white rounded-full h-20 w-20 flex items-center justify-center shadow-md">

                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">

                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4M12 3v18M18 3v4m2-2h-4m-2 12v4m2-2h-4" />

                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 21V3a2 2 0 012-2h10a2 2 0 012 2v18a2 2 0 01-2 2H7a2 2 0 01-2-2z" />

                </svg>

              </div>

              <div class="ml-6">

                <h3 class="text-2xl font-bold text-biru-teks">Paket Bulanan (20kg)</h3>

                <p class="text-gray-700 mt-1">Bayar sekali untuk 20kg cucian (cuci setrika) selama sebulan.</p>

                <span class="text-xl font-extrabold text-biru-utama mt-2 block">Rp 200.000 / bulan</span>

                 <a href="<?php echo $logged_in ? 'dashboard.php' : 'login.php'; ?>" class="mt-3 inline-block bg-biru-utama text-white text-xs py-2 px-3 rounded-full font-semibold hover:bg-blue-700 transition">

                    Pesan Paket Ini
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    </main>

    <footer id="kontak" class="bg-biru-teks text-blue-100 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <h4 class="text-2xl font-bold text-white mb-4">💧 SuperLaundry</h4>
                    <p class="text-blue-200 text-sm">
                    Kami adalah layanan laundry profesional yang berkomitmen
                    memberikan hasil terbaik untuk pakaian Anda, hemat waktu, dan
                    bebas repot.
                    </p>
                </div>

                <div>
                    <h4 class="text-xl font-bold text-white mb-4">Navigasi Cepat</h4>
                    <ul class="space-y-2">
                    <li><a href="index.php" class="text-blue-200 hover:text-white transition">Home</a></li>
                    <li><a href="#layanan" class="text-blue-200 hover:text-white transition">Layanan</a></li>
                    <!-- Hapus link ke paket mahasiswa karena sectionnya dihapus -->
                    <li><a href="#kontak" class="text-blue-200 hover:text-white transition">Kontak</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xl font-bold text-white mb-4">Hubungi Kami</h4>
                    <ul class="space-y-2 text-blue-200">
                    <li>📍 Jl. Kebersihan No. 123, Jakarta</li>
                    <li>📞 **(021) 123-4567**</li>
                    <li>📧 halo@superlaundry.com</li>
                    <li>⏰ Buka: 08:00 - 20:00 WIB</li>
                    </ul>
                </div>

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

            <div class="border-t border-blue-700 mt-10 pt-8 text-center text-blue-300">
                <p>&copy; 2025 SuperLaundry. Dibuat dengan ❤ menggunakan Tailwind CSS.</p>
            </div>
        </div>
    </footer>
    
    <script>
        const openMenuBtn = document.getElementById('open-menu');
        const closeMenuBtn = document.getElementById('close-menu');
        const mobileMenu = document.getElementById('mobile-menu');
        const navLinks = mobileMenu.querySelectorAll('a');

        openMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; 
        });

        const closeMenu = () => {
            mobileMenu.classList.add('hidden');
            document.body.style.overflow = ''; 
        };

        closeMenuBtn.addEventListener('click', closeMenu);
        navLinks.forEach(link => {
            // Perbaikan kecil: tutup menu saat link di-klik
            if (link.href.includes('#')) {
                 link.addEventListener('click', closeMenu);
            }
        });
    </script>
  </body>
</html>