<?php
require 'check_login.php'; 
$page_title = "Dashboard"; 

require 'header.php'; 
require 'sidebar.php'; 
?>

<main class="flex-grow p-6 md:p-10 ml-64">
    
    <h1 class="text-3xl font-bold text-biru-teks mb-4">
        Selamat Datang, <?php echo htmlspecialchars($username); ?>!
    </h1>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <p class="text-gray-700">Anda login sebagai <strong class="capitalize"><?php echo htmlspecialchars($user_role); ?></strong>.</p>
    </div>

</main>
<?php
require 'footer.php'; 
?>