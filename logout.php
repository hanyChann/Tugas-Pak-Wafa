<?php
// 1. Mulai sesi yang ada
session_start();

// 2. Hapus semua variabel di dalam sesi
session_unset();

// 3. Hancurkan sesi itu sendiri
session_destroy();

// 4. Langsung arahkan ke index.php (tanpa pesan)
header("Location: index.php");
exit;
?>