<?php
include 'config.php';

// PENTING: Ambil user_id dari session
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Error: User tidak login.']);
    exit;
}
$user_id = $_SESSION['user_id'];
// $user_id = 1; // Hapus ini saat production

$order_id = $_GET['order_id'] ?? 0;

if ($order_id == 0) {
    echo json_encode(['success' => false, 'message' => 'ID Pesanan tidak valid.']);
    exit;
}

// Query JOIN untuk mengambil semua data invoice
$sql = "SELECT 
            o.order_id, 
            DATE_FORMAT(o.tanggal_pesan, '%d %M %Y - %H:%i') as tanggal_pesan, 
            o.total_harga,
            o.status_pesanan,
            u.nama as nama_pelanggan,
            u.email as email_pelanggan,
            u.alamat as alamat_pelanggan,
            s.nama_layanan,
            s.harga_per_unit,
            s.satuan,
            od.berat_qty,
            od.keterangan,
            p.metode_bayar,
            p.status_bayar
        FROM orders o
        JOIN users u ON o.user_id = u.user_id
        JOIN order_details od ON o.order_id = od.order_id
        JOIN services s ON od.service_id = s.service_id
        JOIN payments p ON o.order_id = p.order_id
        WHERE o.order_id = ? AND o.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $invoice = $result->fetch_assoc();
    // Buat Nomor Invoice (di SQL kamu tidak ada, jadi kita buat manual)
    $invoice['nomor_invoice'] = 'SL-' . str_pad($invoice['order_id'], 6, '0', STR_PAD_LEFT);
    echo json_encode(['success' => true, 'invoice' => $invoice]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invoice tidak ditemukan.']);
}

$stmt->close();
$conn->close();
?>