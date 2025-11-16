<?php
// Ambil nama file saat ini untuk menandai link aktif
$current_page = basename($_SERVER['PHP_SELF']); 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $page_title ?? 'Dashboard'; ?> - SuperLaundry</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      // Salin config dari index.php Anda agar warnanya konsisten
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
<body class="bg-biru-muda"> <div class="flex">