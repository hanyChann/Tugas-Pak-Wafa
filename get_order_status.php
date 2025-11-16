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

// Ambil status, pastikan user_id sesuai untuk keamanan
$sql = "SELECT 
            order_id, 
            status_pesanan,
            DATE_FORMAT(tanggal_pesan, '%d %M %Y') as tanggal_pesan 
        FROM orders 
        WHERE order_id = ? AND user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $order = $result->fetch_assoc();
    echo json_encode(['success' => true, 'order' => $order]);
} else {
    echo json_encode(['success' => false, 'message' => 'Pesanan tidak ditemukan.']);
}

$stmt->close();
$conn->close();
?>