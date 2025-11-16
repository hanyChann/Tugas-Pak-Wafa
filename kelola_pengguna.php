<?php
require 'check_login.php'; 
if ($user_role !== 'admin') {
    header("Location: dashboard.php");
    exit;
}
$page_title = "Kelola Pengguna"; 

require 'header.php'; 
require 'sidebar.php'; 
?>

<main class="flex-grow p-6 md:p-10 ml-64">
    <h1 class="text-3xl font-bold text-biru-teks mb-4">
        Kelola Pengguna
    </h1>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <p class="text-gray-700 text-red-500 font-bold">Halaman ini hanya bisa diakses oleh Admin.</p>
    </div>
</main>
<?php
require 'footer.php'; 
?>