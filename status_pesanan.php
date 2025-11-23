<?php 
$page_title = 'Status Pesanan';
include 'header.php'; 
require_once 'koneksi.php'; // Memuat koneksi database

// Ambil ID Pesanan dari URL (contoh: status_pesanan.php?order_id=123)
// Jika tidak ada di URL, coba ambil pesanan TERAKHIR user ini
$order_id = $_GET['order_id'] ?? 0;

// Jika order_id belum ada, cari pesanan terakhir user ini
if ($order_id == 0 && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql_last = "SELECT order_id FROM orders WHERE user_id = $user_id ORDER BY tanggal_pesan DESC LIMIT 1";
    $res_last = $conn->query($sql_last);
    if ($res_last && $res_last->num_rows > 0) {
        $row = $res_last->fetch_assoc();
        $order_id = $row['order_id'];
    }
}
?>

<div class="max-w-4xl mx-auto py-8 px-4">
    
    <!-- Header / Judul -->
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-biru-teks">Lacak Status Pesanan</h1>
        <p class="text-gray-600 mt-2">Pantau proses laundry Anda secara real-time.</p>
    </div>

    <?php if ($order_id > 0): ?>
        <!-- Kartu Info Pesanan -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 border border-gray-100 relative overflow-hidden">
            <div class="absolute top-0 right-0 bg-blue-500 text-white text-xs font-bold px-3 py-1 rounded-bl-lg">
                Order #<?php echo str_pad($order_id, 6, '0', STR_PAD_LEFT); ?>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Tanggal Pesanan</p>
                    <p class="font-semibold text-gray-800" id="tgl-pesan">Memuat...</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Estimasi Selesai</p>
                    <p class="font-semibold text-biru-utama" id="estimasi-selesai">Memuat...</p>
                </div>
            </div>
        </div>

        <!-- Timeline Status -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 relative">
            <!-- Garis Vertikal -->
            <div class="absolute left-8 top-8 bottom-8 w-0.5 bg-gray-200"></div>

            <!-- Container Timeline (Diiisi JavaScript) -->
            <div class="space-y-8 relative" id="timeline-container">
                <!-- Loading Skeleton -->
                <div class="ml-10 animate-pulse">
                    <div class="h-4 bg-gray-200 rounded w-1/4 mb-2"></div>
                    <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                </div>
            </div>
        </div>

    <?php else: ?>
        <!-- Tampilan Jika Belum Ada Pesanan -->
        <div class="bg-white rounded-2xl shadow p-10 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900">Belum Ada Pesanan Aktif</h3>
            <p class="text-gray-500 mt-2">Anda belum melakukan pemesanan laundry.</p>
            <a href="buat_pesanan.php" class="mt-6 inline-block bg-biru-utama text-white px-6 py-2 rounded-full hover:bg-blue-700 transition">
                Buat Pesanan Sekarang
            </a>
        </div>
    <?php endif; ?>

    <!-- Tombol Contact Person (Floating Bar) -->
    <a href="contact_person.php" class="group block w-full bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl shadow-lg p-4 mt-6 transform hover:scale-[1.02] transition duration-300">
        <div class="flex items-center justify-between text-white">
            <div class="flex items-center space-x-3">
                <div class="bg-white/20 p-2 rounded-full">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-3.138-5.442-2.166-12.285 2.314-16.726C6.59.91 9.656-.001 12.838-.001c4.206 0 8.163 2.236 10.458 5.913 4.29 6.875.342 15.87-6.942 15.87-2.87 0-5.556-1.23-7.468-3.265l-8.829 5.482zM12.838 1.836c-2.79 0-5.48 1.35-7.07 3.906-3.75 6.017-.384 13.96 6.655 13.96 2.49 0 4.826-1.218 6.256-3.28 3.333-4.805 1.882-11.36-3.822-13.546-1.21-.464-2.48-.7-3.76-.7.082-.283 5.84-1.963 6.426-2.153.586-.19 1.266-.283 1.878.015.61.297 3.71 1.817 4.243 2.13.533.31.887.465 1.013.676.126.21.126 1.19-.33 3.677-.455 2.485-1.345 3.56-1.99 4.033-.644.474-1.43.53-1.72.546-.29.016-1.074.076-4.16-1.15-3.833-1.522-6.323-5.433-6.513-5.69-.19-.257-1.56-2.078-1.56-3.964 0-1.887.98-2.813 1.327-3.19.346-.377.753-.474 1.004-.474.25 0 .5.007.717.007.23 0 .54-.087.846.648.318.76 1.086 2.664 1.18 2.856.092.193.153.42.03.673-.12.252-.185.47-.365.68-.182.21-.382.465-.545.628-.18.18-.37.375-.16.736.212.362.944 1.552 2.03 2.52 1.394 1.242 2.567 1.624 2.933 1.777.365.152.578.13.79-.115.213-.245.913-1.064 1.156-1.43.243-.364.484-.304.808-.184.324.12 2.043.96 2.393 1.135.348.175.58.26.665.406.086.146.086.85-.33 2.03-.416 1.18-2.464 1.65-3.385 1.698z"/></svg>
                </div>
                <div>
                    <span class="block font-bold text-lg">Butuh Bantuan?</span>
                    <span class="text-green-100 text-sm">Hubungi Admin via WhatsApp</span>
                </div>
            </div>
            <svg class="w-6 h-6 transform group-hover:translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </div>
    </a>

</div>

<?php
// --- JAVASCRIPT UNTUK MENGAMBIL DATA STATUS ---
ob_start();
$js_order_id = json_encode($order_id);
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const orderId = <?php echo $js_order_id; ?>;
    const timelineContainer = document.getElementById('timeline-container');
    const tglPesan = document.getElementById('tgl-pesan');
    const estSelesai = document.getElementById('estimasi-selesai');

    if (orderId > 0) {
        fetch(`api/get_order_status.php?order_id=${orderId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const order = data.order;
                    tglPesan.textContent = order.tanggal_pesan;
                    // Estimasi sederhana: +2 hari dari tanggal pesan
                    estSelesai.textContent = "Dalam 2-3 Hari"; 

                    const currentStatus = order.status_pesanan;
                    
                    // Daftar Status (Berurutan)
                    const statuses = [
                        { key: 'Menunggu Penjemputan', label: 'Pesanan Diterima', desc: 'Pesanan Anda telah masuk sistem.' },
                        { key: 'Sedang Diproses', label: 'Sedang Diproses', desc: 'Pakaian Anda sedang dicuci/disetrika.' },
                        { key: 'Siap Diambil/Diantar', label: 'Siap Diantar', desc: 'Laundry selesai dan siap dikirim.' },
                        { key: 'Selesai', label: 'Selesai', desc: 'Pesanan telah diterima pelanggan.' }
                    ];

                    // Cari index status saat ini
                    let currentIndex = statuses.findIndex(s => s.key === currentStatus);
                    // Jika status 'Dibatalkan', anggap index -1 atau handle khusus
                    if (currentStatus === 'Dibatalkan') currentIndex = -1;

                    let html = '';

                    if (currentStatus === 'Dibatalkan') {
                        html = `
                        <div class="ml-10 relative">
                            <span class="absolute -left-[3.25rem] flex items-center justify-center w-10 h-10 bg-red-100 rounded-full ring-4 ring-white">
                                <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </span>
                            <h3 class="font-bold text-lg text-red-600">Pesanan Dibatalkan</h3>
                            <p class="text-gray-500">Pesanan ini telah dibatalkan oleh admin atau sistem.</p>
                        </div>`;
                    } else {
                        statuses.forEach((status, index) => {
                            let colorClass = 'bg-gray-100 text-gray-400'; // Default (Belum)
                            let icon = ''; 
                            let textClass = 'text-gray-500';
                            let lineClass = 'border-gray-200';

                            if (index < currentIndex) {
                                // SUDAH LEWAT (Centang Hijau)
                                colorClass = 'bg-green-100 text-green-600';
                                textClass = 'text-gray-900';
                                icon = `<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`;
                            } else if (index === currentIndex) {
                                // SAAT INI (Biru Loading/Aktif)
                                colorClass = 'bg-blue-100 text-biru-utama ring-blue-50';
                                textClass = 'text-biru-utama font-bold';
                                icon = `<svg class="w-6 h-6 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="12" r="10" stroke-width="2"></circle><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2"/></svg>`;
                            } else {
                                // BELUM (Abu-abu)
                                icon = `<div class="w-3 h-3 bg-gray-300 rounded-full"></div>`;
                            }

                            html += `
                            <div class="ml-10 relative group">
                                <!-- Icon Bulat -->
                                <span class="absolute -left-[3.25rem] flex items-center justify-center w-10 h-10 ${colorClass} rounded-full ring-4 ring-white transition-all duration-300 group-hover:scale-110 shadow-sm">
                                    ${icon}
                                </span>
                                <!-- Konten Teks -->
                                <div class="pb-2">
                                    <h3 class="text-lg ${textClass} mb-1 transition-colors">${status.label}</h3>
                                    <p class="text-sm text-gray-500">${status.desc}</p>
                                </div>
                            </div>`;
                        });
                    }
                    
                    timelineContainer.innerHTML = html;
                } else {
                    timelineContainer.innerHTML = `<div class="ml-10 text-red-500">Gagal memuat data status.</div>`;
                }
            })
            .catch(err => console.error(err));
    }
});
</script>
<?php 
$page_script = ob_get_clean();
include 'footer.php'; 
?>