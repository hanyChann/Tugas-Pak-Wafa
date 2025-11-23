<?php
// 1. Panggil konfigurasi database
require_once 'config.php';

// Set header agar output selalu dianggap JSON
header('Content-Type: application/json');

// 2. CEK KEAMANAN (LOGIN)
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['success' => false, 'message' => 'Sesi habis. Silakan login kembali.']);
    exit;
}

$user_id = $_SESSION['user_id'];

// 3. AMBIL DATA DARI FRONTEND (JSON)
$input = json_decode(file_get_contents('php://input'), true);

// Ambil & Validasi Input
$service_id = isset($input['service_id']) ? intval($input['service_id']) : 0;
$berat_qty  = isset($input['estimasi_berat']) ? floatval($input['estimasi_berat']) : 0;
$pengiriman = isset($input['metode_pengiriman']) ? trim($input['metode_pengiriman']) : '';
$alamat     = isset($input['alamat']) ? trim($input['alamat']) : '';
$pembayaran = isset($input['metode_pembayaran']) ? trim($input['metode_pembayaran']) : 'Tunai';

// Cek Data Wajib
if ($service_id <= 0 || $berat_qty <= 0 || empty($pengiriman)) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap. Mohon isi semua kolom bertanda *.']);
    exit;
}

// Validasi Alamat jika Diantar
if ($pengiriman === 'Diantar' && empty($alamat)) {
    echo json_encode(['success' => false, 'message' => 'Alamat wajib diisi untuk metode Antar Jemput.']);
    exit;
}

// 4. MULAI TRANSAKSI DATABASE
$conn->begin_transaction();

try {
    // A. AMBIL HARGA LAYANAN DARI DB
    $stmt_harga = $conn->prepare("SELECT harga_per_unit, nama_layanan FROM services WHERE service_id = ?");
    $stmt_harga->bind_param("i", $service_id);
    $stmt_harga->execute();
    $res_harga = $stmt_harga->get_result();
    
    if ($res_harga->num_rows === 0) {
        throw new Exception("Layanan tidak ditemukan.");
    }
    
    $layanan = $res_harga->fetch_assoc();
    $harga_satuan = floatval($layanan['harga_per_unit']);
    $stmt_harga->close();

    // B. HITUNG TOTAL HARGA & ESTIMASI SELESAI
    $total_tagihan = $harga_satuan * $berat_qty;
    
    // --- PERBAIKAN UTAMA DI SINI ---
    // Menghitung 'tanggal_ambil' otomatis (Hari ini + 2 Hari)
    // Ini wajib karena database kamu menolak nilai NULL untuk kolom ini.
    $tgl_sekarang = new DateTime();
    $tgl_ambil = $tgl_sekarang->modify('+2 days')->format('Y-m-d'); 

    // C. INSERT KE TABEL 'orders'
    // Query ini sekarang menyertakan 'tanggal_ambil'
    $stmt_order = $conn->prepare("INSERT INTO orders (user_id, tanggal_pesan, tanggal_ambil, total_harga, status_pesanan) VALUES (?, NOW(), ?, ?, 'Menunggu Penjemputan')");
    
    // Bind param: i=integer, s=string, d=double/decimal
    $stmt_order->bind_param("isd", $user_id, $tgl_ambil, $total_tagihan);
    
    if (!$stmt_order->execute()) {
        throw new Exception("Gagal membuat pesanan: " . $stmt_order->error);
    }
    $order_id = $conn->insert_id; // ID Pesanan Baru
    $stmt_order->close();

    // D. INSERT KE TABEL 'order_details'
    // Gabungkan info pengiriman ke 'keterangan'
    $ket_db = "Metode: $pengiriman";
    if ($pengiriman === 'Diantar') $ket_db .= " - Alamat: $alamat";

    $stmt_detail = $conn->prepare("INSERT INTO order_details (order_id, service_id, berat_qty, harga_subtotal, keterangan) VALUES (?, ?, ?, ?, ?)");
    $stmt_detail->bind_param("iidds", $order_id, $service_id, $berat_qty, $total_tagihan, $ket_db);
    
    if (!$stmt_detail->execute()) {
        throw new Exception("Gagal menyimpan rincian pesanan.");
    }
    $stmt_detail->close();

    // E. INSERT KE TABEL 'payments'
    $stmt_pay = $conn->prepare("INSERT INTO payments (order_id, jumlah_bayar, metode_bayar, status_bayar) VALUES (?, ?, ?, 'Belum Lunas')");
    $stmt_pay->bind_param("ids", $order_id, $total_tagihan, $pembayaran);
    
    if (!$stmt_pay->execute()) {
        throw new Exception("Gagal menyimpan data pembayaran.");
    }
    $stmt_pay->close();

    // --- COMMIT TRANSAKSI (Simpan Permanen) ---
    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Pesanan berhasil dibuat!',
        'order_id' => $order_id
    ]);

} catch (Exception $e) {
    $conn->rollback(); // Batalkan jika error
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

$conn->close();
?>