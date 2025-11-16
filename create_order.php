<?php
include 'config.php';

// Ambil data JSON yang dikirim dari JavaScript
$data = json_decode(file_get_contents('php://input'), true);

// PENTING: Ambil user_id dari session yang sedang login
// Untuk pengujian, kamu bisa hardcode: $user_id = 1;
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Error: User tidak login.']);
    exit;
}
$user_id = $_SESSION['user_id'];

// Data dari form
$service_id = $data['service_id'];
$estimasi_berat = (float)($data['estimasi_berat'] ?? 1.0); // Default 1 jika bukan kiloan
$metode_pengiriman = $data['metode_pengiriman'];
$alamat = $data['alamat'] ?? null;
$metode_pembayaran = $data['metode_pembayaran'];

// Mulai Transaksi Database
$conn->begin_transaction();

try {
    // 1. Ambil harga layanan dari tabel 'services'
    $stmt_price = $conn->prepare("SELECT harga_per_unit FROM services WHERE service_id = ?");
    $stmt_price->bind_param("i", $service_id);
    $stmt_price->execute();
    $result_price = $stmt_price->get_result();
    if ($result_price->num_rows == 0) {
        throw new Exception("Layanan tidak ditemukan.");
    }
    $service = $result_price->fetch_assoc();
    $harga_per_unit = (float)$service['harga_per_unit'];
    $stmt_price->close();

    // 2. Hitung harga subtotal/estimasi
    $harga_subtotal = $harga_per_unit * $estimasi_berat;
    $total_harga = $harga_subtotal; // Untuk saat ini, total harga = subtotal

    // 3. Masukkan ke tabel 'orders'
    // (Sesuai laundry.sql, status default adalah 'Menunggu Penjemputan')
    $stmt_order = $conn->prepare("INSERT INTO orders (user_id, total_harga) VALUES (?, ?)");
    $stmt_order->bind_param("id", $user_id, $total_harga);
    $stmt_order->execute();
    $order_id = $conn->insert_id; // Ambil ID pesanan baru
    $stmt_order->close();

    // 4. Masukkan ke tabel 'order_details'
    $keterangan = $metode_pengiriman . ($alamat ? " - " . $alamat : "");
    $stmt_details = $conn->prepare("INSERT INTO order_details (order_id, service_id, keterangan, berat_qty, harga_subtotal) VALUES (?, ?, ?, ?, ?)");
    $stmt_details->bind_param("iisdd", $order_id, $service_id, $keterangan, $estimasi_berat, $harga_subtotal);
    $stmt_details->execute();
    $stmt_details->close();

    // 5. Masukkan ke tabel 'payments'
    // (Sesuai laundry.sql, status default adalah 'Belum Lunas')
    $stmt_payment = $conn->prepare("INSERT INTO payments (order_id, jumlah_bayar, metode_bayar) VALUES (?, ?, ?)");
    $stmt_payment->bind_param("ids", $order_id, $total_harga, $metode_pembayaran);
    $stmt_payment->execute();
    $stmt_payment->close();

    // Jika semua berhasil, commit transaksi
    $conn->commit();
    
    // Kirim kembali ID pesanan baru agar bisa redirect ke invoice
    echo json_encode([
        'success' => true, 
        'message' => 'Pesanan berhasil dibuat!', 
        'order_id' => $order_id
    ]);

} catch (Exception $e) {
    // Jika ada error, rollback semua perubahan
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
?>