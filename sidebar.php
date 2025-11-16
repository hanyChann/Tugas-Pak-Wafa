<?php
// $user_role, $username, $user_id sudah ada dari check_login.php
// $current_page sudah ada dari header.php
?>

<aside class="w-64 min-h-screen bg-biru-teks text-white p-4 fixed top-0 left-0">
    
    <div class="text-center py-4 border-b border-blue-700">
        <h3 class="text-xl font-bold"><?php echo htmlspecialchars($username); ?></h3>
        <span class="text-sm text-blue-300 capitalize"><?php echo htmlspecialchars($user_role); ?></span>
    </div>

    <nav class="mt-6">
        <ul class="space-y-2">
            
            <li>
                <a href="dashboard.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-biru-utama <?php echo ($current_page == 'dashboard.php') ? 'bg-biru-utama' : ''; ?>">
                    Dashboard
                </a>
            </li>
            
            <?php 
            // ===================================
            // == LOGIKA UNTUK ADMIN ==
            // ===================================
            if ($user_role == 'admin'): 
            ?>
                <li>
                    <a href="kelola_pengguna.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-biru-utama <?php echo ($current_page == 'kelola_pengguna.php') ? 'bg-biru-utama' : ''; ?>">
                        Kelola Pengguna
                    </a>
                </li>
                <?php 
            // =============================================
            // == LOGIKA UNTUK USER (DENGAN SUBMENU) ==
            // =============================================
            elseif ($user_role == 'user'): 
            ?>
                <li>
                    <a href="#" id="trigger-pesan" class="flex justify-between items-center py-2.5 px-4 rounded transition duration-200 hover:bg-biru-utama">
                        <span>Pesanan</span>
                        <svg id="arrow-icon" class="w-4 h-4 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                    
                    <ul id="submenu-pesan" class="hidden mt-1 ml-4 space-y-1">
                        <li>
                            <a href="order.php" class="block py-1.5 px-3 rounded text-sm text-blue-200 hover:text-white hover:bg-biru-utama <?php echo ($current_page == 'order.php') ? 'bg-biru-utama text-white' : ''; ?>">
                                Order
                            </a>
                        </li>
                        <li>
                            <a href="riwayat.php" class="block py-1.5 px-3 rounded text-sm text-blue-200 hover:text-white hover:bg-biru-utama <?php echo ($current_page == 'riwayat.php') ? 'bg-biru-utama text-white' : ''; ?>">
                                Riwayat
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li>
                    <a href="#" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-biru-utama">
                        Riwayat Transaksi
                    </a>
                </li>
            <?php endif; ?>
            
            <li>
                <a href="logout.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-red-600 mt-4">
                    Logout
                </a>
            </li>
        </ul>
    </nav>
</aside>