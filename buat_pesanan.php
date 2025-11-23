<?php 
// ==================================================
// 1. PENGATURAN HALAMAN & KONEKSI
// ==================================================
$page_title = 'Buat Pesanan Baru';
include 'header.php'; 
require_once 'koneksi.php';

// Ambil data layanan dari database
$services = [];
$sql_services = "SELECT service_id, nama_layanan, harga_per_unit, satuan FROM services ORDER BY nama_layanan ASC";
$result_services = $conn->query($sql_services);
if ($result_services && $result_services->num_rows > 0) {
    while($row = $result_services->fetch_assoc()) {
        $services[] = $row;
    }
}

$default_alamat = $_SESSION['alamat'] ?? ''; 
?>

<!-- Container Utama -->
<div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    
    <!-- Header Card -->
    <div class="bg-gradient-to-r from-biru-utama to-blue-600 rounded-2xl shadow-lg p-6 md:p-8 text-white mb-8 flex items-center justify-between relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-3xl font-extrabold tracking-tight">Buat Pesanan Baru</h1>
            <p class="mt-2 text-blue-100 text-sm md:text-base max-w-lg">
                Isi formulir di bawah untuk memesan layanan laundry kami. Cepat, mudah, dan terpercaya.
            </p>
        </div>
        <!-- Hiasan Background -->
        <div class="absolute right-0 top-0 h-full w-1/3 bg-white opacity-10 transform skew-x-12"></div>
        <div class="absolute -right-10 -bottom-10 h-40 w-40 bg-blue-400 rounded-full opacity-20 blur-2xl"></div>
        
        <!-- Icon Besar -->
        <div class="hidden md:block relative z-10 bg-white/20 p-3 rounded-xl backdrop-blur-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
        </div>
    </div>

    <!-- Formulir Card -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        
        <!-- Notifikasi -->
        <div id="notification" class="hidden p-4 bg-green-50 border-b border-green-100 text-green-700 text-sm flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span id="notif-message"></span>
        </div>

        <form id="order-form" class="p-6 md:p-8 space-y-8">
            
            <!-- SECTION 1: Layanan & Berat -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Pilih Layanan -->
                <div class="space-y-2">
                    <label for="service_id" class="block text-sm font-semibold text-gray-700">
                        Layanan <span class="text-red-500">*</span>
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 group-focus-within:text-biru-utama transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                        </div>
                        <select id="service_id" name="service_id" required class="block w-full pl-10 pr-10 py-3 text-gray-700 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-biru-utama focus:border-transparent focus:bg-white transition duration-200 appearance-none cursor-pointer">
                            <option value="" disabled selected>Pilih jenis layanan...</option>
                            <?php foreach ($services as $service): ?>
                                <option value="<?php echo $service['service_id']; ?>" 
                                        data-satuan="<?php echo $service['satuan']; ?>" 
                                        data-harga="<?php echo $service['harga_per_unit']; ?>">
                                    <?php echo htmlspecialchars($service['nama_layanan']); ?> 
                                    (Rp <?php echo number_format($service['harga_per_unit'], 0, ',', '.'); ?> / <?php echo htmlspecialchars($service['satuan']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-500">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </div>
                    </div>
                </div>

                <!-- Estimasi Berat -->
                <div class="space-y-2">
                    <label for="estimasi_berat" class="block text-sm font-semibold text-gray-700">
                        Estimasi Jumlah <span id="satuan-label" class="text-gray-400 font-normal text-xs ml-1">(Satuan)</span> <span class="text-red-500">*</span>
                    </label>
                    <div class="relative group">
                        <input type="number" step="0.1" min="0.1" name="estimasi_berat" id="estimasi_berat" required 
                            class="block w-full pl-4 pr-12 py-3 text-gray-700 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-biru-utama focus:border-transparent focus:bg-white transition duration-200 placeholder-gray-400" 
                            placeholder="0.0">
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                            <span class="text-gray-500 font-medium bg-gray-200 px-2 py-0.5 rounded text-xs" id="satuan-text">Unit</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-100"></div>

            <!-- SECTION 2: Metode Pengiriman -->
            <div class="space-y-4">
                <label class="block text-sm font-semibold text-gray-700">Metode Pengiriman <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Radio Card: Diantar -->
                    <label class="cursor-pointer group relative">
                        <input type="radio" name="metode_pengiriman" value="Diantar" class="peer sr-only" required>
                        <div class="p-4 bg-white border border-gray-200 rounded-xl hover:border-biru-utama/50 hover:bg-blue-50/30 peer-checked:border-biru-utama peer-checked:bg-blue-50 peer-checked:ring-1 peer-checked:ring-biru-utama transition-all duration-200 flex items-start">
                            <div class="flex-shrink-0 mt-1 mr-3 text-gray-400 peer-checked:text-biru-utama group-hover:text-biru-utama">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            </div>
                            <div class="flex-1">
                                <span class="block font-medium text-gray-900 peer-checked:text-biru-utama">Antar Jemput</span>
                                <span class="block text-xs text-gray-500 mt-1">Kurir kami menjemput & mengantar cucian Anda.</span>
                            </div>
                            <div class="w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-biru-utama peer-checked:bg-biru-utama flex items-center justify-center ml-2">
                                <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                            </div>
                        </div>
                    </label>

                    <!-- Radio Card: Ambil Sendiri -->
                    <label class="cursor-pointer group relative">
                        <input type="radio" name="metode_pengiriman" value="Ambil Sendiri" class="peer sr-only">
                        <div class="p-4 bg-white border border-gray-200 rounded-xl hover:border-biru-utama/50 hover:bg-blue-50/30 peer-checked:border-biru-utama peer-checked:bg-blue-50 peer-checked:ring-1 peer-checked:ring-biru-utama transition-all duration-200 flex items-start">
                            <div class="flex-shrink-0 mt-1 mr-3 text-gray-400 peer-checked:text-biru-utama group-hover:text-biru-utama">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                            </div>
                            <div class="flex-1">
                                <span class="block font-medium text-gray-900 peer-checked:text-biru-utama">Ambil Sendiri</span>
                                <span class="block text-xs text-gray-500 mt-1">Anda antar & ambil sendiri ke outlet kami.</span>
                            </div>
                            <div class="w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-biru-utama peer-checked:bg-biru-utama flex items-center justify-center ml-2">
                                <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Alamat (Animasi Slide) -->
            <div id="alamat-container" class="hidden transition-all duration-300 ease-in-out origin-top overflow-hidden">
                <div class="space-y-2 pt-2">
                    <label for="alamat" class="block text-sm font-semibold text-gray-700">Detail Lokasi Penjemputan <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <textarea id="alamat" name="alamat" rows="3" 
                            class="block w-full px-4 py-3 text-gray-700 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-biru-utama focus:border-transparent focus:bg-white transition duration-200 placeholder-gray-400 resize-none" 
                            placeholder="Jl. Merpati No. 12, RT 05/02 (Depan Masjid Al-Ikhlas)"><?php echo htmlspecialchars($default_alamat); ?></textarea>
                        <div class="absolute top-3 right-3 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: Pembayaran & Total -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                <!-- Metode Pembayaran -->
                <div class="space-y-2">
                    <label for="metode_pembayaran" class="block text-sm font-semibold text-gray-700">Pembayaran <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                        </div>
                        <select id="metode_pembayaran" name="metode_pembayaran" required class="block w-full pl-10 pr-10 py-3 text-gray-700 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-biru-utama focus:border-transparent focus:bg-white transition duration-200 appearance-none cursor-pointer">
                            <option value="Tunai">Tunai (Bayar di Tempat)</option>
                            <option value="Non-Tunai">Non-Tunai (Transfer/E-Wallet)</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-500">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </div>
                    </div>
                </div>

                <!-- Card Total Harga -->
                <div class="bg-gradient-to-br from-gray-50 to-blue-50 p-5 rounded-xl border border-blue-100 shadow-sm">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm font-medium text-gray-600">Estimasi Biaya</span>
                        <span class="text-xs bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full font-medium">Belum Ongkir</span>
                    </div>
                    <div class="flex justify-between items-end">
                        <div class="text-3xl font-bold text-biru-teks" id="total-harga-display">Rp 0</div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2 italic">*Harga final ditentukan setelah penimbangan di outlet.</p>
                </div>
            </div>

            <!-- Tombol Submit -->
            <div class="pt-6">
                <button type="submit" id="submit-btn" class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-base font-bold rounded-xl text-white bg-biru-utama hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 shadow-lg shadow-blue-500/30 transition-all duration-200 transform hover:-translate-y-1">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-blue-300 group-hover:text-blue-200 transition ease-in-out duration-150" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    Buat Pesanan Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<!-- JAVASCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. LOGIKA ALAMAT
    const radioButtons = document.querySelectorAll('input[name="metode_pengiriman"]');
    const alamatContainer = document.getElementById('alamat-container');
    const alamatInput = document.getElementById('alamat');

    function toggleAlamat() {
        let selectedValue = document.querySelector('input[name="metode_pengiriman"]:checked')?.value;
        if (selectedValue === 'Diantar') {
            alamatContainer.classList.remove('hidden');
            alamatInput.required = true;
            alamatInput.focus();
        } else {
            alamatContainer.classList.add('hidden');
            alamatInput.required = false;
        }
    }
    radioButtons.forEach(radio => radio.addEventListener('change', toggleAlamat));

    // 2. LOGIKA ESTIMASI HARGA
    const serviceSelect = document.getElementById('service_id');
    const beratInput = document.getElementById('estimasi_berat');
    const totalDisplay = document.getElementById('total-harga-display');
    const satuanLabel = document.getElementById('satuan-label');
    const satuanText = document.getElementById('satuan-text');

    function hitungTotal() {
        const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
        const hargaPerUnit = parseFloat(selectedOption.getAttribute('data-harga')) || 0;
        const satuan = selectedOption.getAttribute('data-satuan') || 'Satuan';
        const berat = parseFloat(beratInput.value) || 0;

        if (serviceSelect.value) {
            satuanLabel.textContent = `(${satuan})`;
            satuanText.textContent = satuan;
            
            // Auto placeholder
            beratInput.placeholder = satuan === 'KG' ? "3.5" : "2";
        } else {
            satuanLabel.textContent = "(Satuan)";
            satuanText.textContent = "Unit";
        }

        const total = hargaPerUnit * berat;
        
        // Format Rupiah dengan pemisah ribuan
        const formatter = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        });
        
        // Animasi angka sederhana (opsional, bisa dihapus jika berat)
        totalDisplay.textContent = formatter.format(total);
    }

    serviceSelect.addEventListener('change', hitungTotal);
    beratInput.addEventListener('input', hitungTotal);

    // 3. SUBMIT FORM
    const form = document.getElementById('order-form');
    const notification = document.getElementById('notification');
    const notifMessage = document.getElementById('notif-message');
    const submitBtn = document.getElementById('submit-btn');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        if (!document.querySelector('input[name="metode_pengiriman"]:checked')) {
            // Shake animation logic could be added here
            alert('Harap pilih metode pengiriman!');
            return;
        }

        // Loading State
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...`;
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-75', 'cursor-not-allowed');

        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        fetch('api/create_order.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                notification.className = "p-4 mb-6 bg-green-50 border-b border-green-100 text-green-700 text-sm flex items-center rounded-lg animate-fadeInUp";
                notification.innerHTML = `<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg> Pesanan berhasil! Mengalihkan...`;
                notification.classList.remove('hidden');
                
                // Scroll ke atas agar notifikasi terlihat
                window.scrollTo({ top: 0, behavior: 'smooth' });

                setTimeout(() => {
                    window.location.href = `invoice.php?order_id=${result.order_id}`;
                }, 1500);
            } else {
                throw new Error(result.message || 'Gagal membuat pesanan.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            notification.className = "p-4 mb-6 bg-red-50 border-b border-red-100 text-red-700 text-sm flex items-center rounded-lg animate-fadeInUp";
            notification.innerHTML = `<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg> ${error.message}`;
            notification.classList.remove('hidden');
            
            window.scrollTo({ top: 0, behavior: 'smooth' });
            
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
        });
    });
});
</script>

<?php 
include 'footer.php'; 
?>