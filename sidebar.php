<?php
$username = $username ?? 'Guest';
$user_role = $user_role ?? 'user';
$current_page = $current_page ?? 'dashboard.php';
$is_pemesanan_active = in_array($current_page, ['buat_pesanan.php', 'status_pesanan.php', 'riwayat_pesanan.php']);
function get_nav_active_class($page, $current_page) {
    return ($current_page == $page) ? 'bg-biru-utama font-bold shadow-inner' : 'text-blue-100';
}

function get_submenu_active_class($page, $current_page) {
    return ($current_page == $page) ? 'bg-biru-utama/80 font-medium text-white' : 'text-blue-200 hover:text-white';
}
?>

<script>
    if (typeof tailwind !== 'undefined') {
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'biru-teks': '#1E3A8A', 
                        'biru-utama': '#2563EB',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    }
</script>

<aside class="w-64 min-h-screen bg-biru-teks text-white p-4 fixed top-0 left-0 shadow-2xl rounded-r-xl flex flex-col z-10">

    <!-- Bagian Info Pengguna -->
    <div class="text-center py-6 mb-4 border-b border-blue-600/50">
        <!-- Icon Pengguna -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto mb-2 text-blue-300" viewBox="0 0 24 24" fill="currentColor">
            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
        </svg>
        <h3 class="text-xl font-extrabold tracking-wide"><?php echo htmlspecialchars($username); ?></h3>
        <!-- Peningkatan: Warna role lebih menonjol -->
        <span class="text-sm text-blue-300 capitalize bg-blue-600/30 px-3 py-0.5 mt-1 inline-block rounded-full"><?php echo htmlspecialchars($user_role); ?></span>
    </div>

    <!-- Navigasi Utama -->
    <nav class="flex-grow">
        <ul class="space-y-2">

            <!-- Link Dashboard -->
            <li>
                <a href="dashboard.php" class="flex items-center space-x-3 py-3 px-4 rounded-lg transition duration-200 hover:bg-biru-utama hover:font-semibold <?php echo get_nav_active_class('dashboard.php', $current_page); ?>">
                    <!-- Icon Home -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    <span>Dashboard</span>
                </a>
            </li>

            <?php
            if ($user_role == 'admin'):
            ?>
                <li>
                    <a href="kelola_pengguna.php" class="flex items-center space-x-3 py-3 px-4 rounded-lg transition duration-200 hover:bg-biru-utama hover:font-semibold <?php echo get_nav_active_class('kelola_pengguna.php', $current_page); ?>">

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><path d="M17 11l-3 3 3 3"/></svg>
                        <span>Kelola Pengguna</span>
                    </a>
                </li>
            <?php
            elseif ($user_role == 'user'):
            ?>
                
                <li class="relative">
                    <a href="#" id="trigger-pesan" class="flex justify-between items-center py-3 px-4 rounded-lg transition duration-200 hover:bg-biru-utama hover:font-semibold <?php echo ($is_pemesanan_active) ? 'bg-biru-utama font-bold shadow-inner' : 'text-blue-100'; ?>">
                        <div class="flex items-center space-x-3">
                            <!-- Icon Clipboard/Order -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg>
                            <span>Pemesanan</span>
                        </div>
                        <!-- Panah (Rotate berdasarkan status aktif PHP) -->
                        <svg id="arrow-icon" class="w-4 h-4 transition-transform transform <?php echo ($is_pemesanan_active) ? 'rotate-90' : 'rotate-0'; ?>" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </a>

                    <!-- Submenu Pesanan -->
                    <ul id="submenu-pesan" class="<?php echo ($is_pemesanan_active) ? 'block' : 'hidden'; ?> mt-1 ml-6 space-y-1 border-l-2 border-blue-700/50 pl-2">
                        <li>
                            <!-- PERUBAHAN NAMA FILE -->
                            <a href="buat_pesanan.php" class="block py-2 px-3 rounded text-sm transition duration-200 hover:bg-biru-utama/80 <?php echo get_submenu_active_class('buat_pesanan.php', $current_page); ?>">
                                <span>- Buat Pesanan Baru</span>
                            </a>
                        </li>
                        <li>
                            <!-- PERUBAHAN NAMA FILE -->
                            <a href="status_pesanan.php" class="block py-2 px-3 rounded text-sm transition duration-200 hover:bg-biru-utama/80 <?php echo get_submenu_active_class('status_pesanan.php', $current_page); ?>">
                                <span>- Status Pesanan</span>
                            </a>
                        </li>
                        <li>
                            <!-- PERUBAHAN NAMA FILE -->
                            <a href="riwayat_pesanan.php" class="block py-2 px-3 rounded text-sm transition duration-200 hover:bg-biru-utama/80 <?php echo get_submenu_active_class('riwayat_pesanan.php', $current_page); ?>">
                                <span>- Riwayat Pesanan</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- DROPDOWN ENDS HERE -->
                
            <?php endif; ?>
        </ul>
    </nav>

    <!-- Bagian Paling Bawah: Logout -->
    <div class="mt-auto pt-6 border-t border-blue-600/50">

        <a href="index.php" class="flex items-center justify-center space-x-2 py-2 px-3 rounded-full bg-blue-600/50 text-white font-bold text-sm transition duration-300 hover:bg-biru-utama hover:shadow-lg mb-2">
            <!-- Icon "Kembali" (Arrow Left) -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            <span>Kembali ke Beranda</span>
        </a>
        <a href="logout.php" class="flex items-center justify-center space-x-2 py-2 px-3 rounded-full bg-red-600/70 text-white font-bold text-sm transition duration-300 hover:bg-red-600 hover:shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            <span>Logout</span>
        </a>
    </div>
</aside>

