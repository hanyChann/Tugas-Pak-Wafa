<?php 
// ==================================================
// 1. PENGATURAN HALAMAN & KONEKSI
// ==================================================
$page_title = 'Riwayat Pesanan';
include 'header.php'; 
// require_once 'koneksi.php'; // Tidak perlu jika fetch data via API, tapi boleh dipasang untuk jaga-jaga
?>

<div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-biru-teks tracking-tight">Riwayat Pesanan</h1>
            <p class="mt-2 text-gray-600">Daftar semua transaksi laundry Anda.</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="buat_pesanan.php" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-full text-white bg-biru-utama hover:bg-blue-700 shadow-lg hover:shadow-blue-500/30 transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Buat Pesanan Baru
            </a>
        </div>
    </div>

    <!-- Content Card -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        
        <!-- Loading State (Awal) -->
        <div id="loading-state" class="p-12 text-center">
            <svg class="animate-spin mx-auto h-10 w-10 text-biru-utama" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="mt-4 text-gray-500 font-medium">Memuat riwayat pesanan...</p>
        </div>

        <!-- Empty State (Jika tidak ada data) -->
        <div id="empty-state" class="hidden p-12 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-50 mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Riwayat</h3>
            <p class="text-gray-500 mb-8 max-w-md mx-auto">Anda belum pernah melakukan pemesanan laundry. Yuk, buat pesanan pertama Anda sekarang!</p>
            <a href="buat_pesanan.php" class="text-biru-utama font-semibold hover:text-blue-700 transition">
                Mulai Pesan &rarr;
            </a>
        </div>

        <!-- Table Data (Desktop) -->
        <div id="data-table-container" class="hidden overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">No. Invoice</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Layanan</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Total</th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="history-table-body" class="bg-white divide-y divide-gray-200">
                    <!-- Data Rows akan diinject via JS -->
                </tbody>
            </table>
        </div>

        <!-- Card List (Mobile) -->
        <div id="data-card-container" class="hidden md:hidden p-4 space-y-4 bg-gray-50">
            <!-- Data Cards akan diinject via JS -->
        </div>

    </div>
</div>

<!-- JAVASCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const loadingState = document.getElementById('loading-state');
    const emptyState = document.getElementById('empty-state');
    const tableContainer = document.getElementById('data-table-container');
    const cardContainer = document.getElementById('data-card-container');
    const tableBody = document.getElementById('history-table-body');

    // Format Rupiah
    const formatRupiah = (angka) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(angka);
    };

    // Badge Status Style
    const getStatusBadge = (status) => {
        let classes = 'px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ';
        let icon = '';
        
        switch(status) {
            case 'Selesai':
                classes += 'bg-green-100 text-green-800';
                icon = '<svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
                break;
            case 'Dibatalkan':
                classes += 'bg-red-100 text-red-800';
                icon = '<svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';
                break;
            case 'Sedang Diproses':
                classes += 'bg-yellow-100 text-yellow-800';
                icon = '<svg class="w-3 h-3 mr-1 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>';
                break;
            case 'Siap Diambil/Diantar':
                classes += 'bg-blue-100 text-blue-800';
                icon = '<svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>';
                break;
            default: // Menunggu Penjemputan
                classes += 'bg-gray-100 text-gray-600';
                icon = '<svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
        }
        return `<span class="${classes} flex items-center justify-center w-fit mx-auto md:mx-0">${icon}${status}</span>`;
    };

    // Fetch Data via API
    fetch('api/get_order_history.php')
        .then(response => response.json())
        .then(data => {
            loadingState.classList.add('hidden');

            if (data.success && data.history.length > 0) {
                // Tampilkan container
                tableContainer.classList.remove('hidden');
                cardContainer.classList.remove('hidden', 'md:hidden'); // Tetap hidden di md ke atas via CSS class

                // Loop Data
                data.history.forEach(order => {
                    const orderIdStr = `SL-${String(order.order_id).padStart(6, '0')}`;
                    
                    // 1. Render Table Row (Desktop)
                    const row = document.createElement('tr');
                    row.className = "hover:bg-gray-50 transition-colors";
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-biru-teks font-mono">${orderIdStr}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${order.tanggal_pesan}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${order.nama_layanan}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formatRupiah(order.total_harga)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">${getStatusBadge(order.status_pesanan)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="status_pesanan.php?order_id=${order.order_id}" class="text-biru-utama hover:text-blue-800 mr-3 font-semibold">Lacak</a>
                            <a href="invoice.php?order_id=${order.order_id}" class="text-gray-400 hover:text-gray-600" title="Lihat Invoice">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </a>
                        </td>
                    `;
                    tableBody.appendChild(row);

                    // 2. Render Card Item (Mobile)
                    const card = document.createElement('div');
                    card.className = "bg-white p-5 rounded-xl shadow-sm border border-gray-100";
                    card.innerHTML = `
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <span class="text-xs font-bold text-biru-teks bg-blue-50 px-2 py-1 rounded font-mono">${orderIdStr}</span>
                                <div class="text-xs text-gray-500 mt-1">${order.tanggal_pesan}</div>
                            </div>
                            <a href="invoice.php?order_id=${order.order_id}" class="text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </a>
                        </div>
                        <h4 class="font-bold text-gray-900 mb-1">${order.nama_layanan}</h4>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-sm text-gray-600">Total:</span>
                            <span class="text-base font-bold text-biru-utama">${formatRupiah(order.total_harga)}</span>
                        </div>
                        <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                            ${getStatusBadge(order.status_pesanan)}
                            <a href="status_pesanan.php?order_id=${order.order_id}" class="text-sm font-bold text-biru-utama hover:underline">Lacak &rarr;</a>
                        </div>
                    `;
                    cardContainer.appendChild(card);
                });

            } else {
                // Jika data kosong
                emptyState.classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            loadingState.innerHTML = `<p class="text-red-500 font-medium">Gagal memuat data. Silakan coba lagi nanti.</p>`;
        });
});
</script>

<?php 
include 'footer.php'; 
?>