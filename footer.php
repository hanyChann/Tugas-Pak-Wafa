</div> <script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Ambil elemen-elemen
    const trigger = document.getElementById('trigger-pesan');
    const submenu = document.getElementById('submenu-pesan');
    const arrow = document.getElementById('arrow-icon');

    // Cek jika trigger-nya ada (hanya ada untuk role 'user')
    if (trigger) {
        // Tambahkan event klik
        trigger.addEventListener('click', function(e) {
            e.preventDefault(); // Mencegah pindah halaman
            // Toggle (sembunyikan/tampilkan) submenu
            submenu.classList.toggle('hidden');
            // Putar panah
            arrow.classList.toggle('rotate-90');
        });
    }

    // (Otomatis buka jika di halaman submenu)
    // $current_page diambil dari header.php
    const currentPage = "<?php echo $current_page; ?>"; 
    if (currentPage === 'order.php' || currentPage === 'riwayat.php') {
        if (submenu) submenu.classList.remove('hidden');
        if (arrow) arrow.classList.add('rotate-90');
    }
});
</script>
</body>
</html>