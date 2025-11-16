<?php
include 'config.php';

// PENTING: Ambil user_id dari session
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Error: User tidak login.']);
    exit;
}
$user_id = $_SESSION['user_id'];
// $user_id = 1; // Hapus ini saat production

$sql = "SELECT 
            o.order_id, 
            DATE_FORMAT(o.tanggal_pesan, '%d %M %Y') as tanggal_pesan, 
            o.total_harga, 
            o.status_pesanan,
            s.nama_layanan
        FROM orders o
        JOIN order_details od ON o.order_id = od.order_id
        JOIN services s ON od.service_id = s.service_id
        WHERE o.user_id = ?
        ORDER BY o.tanggal_pesan DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$history = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $history[] = $row;
    }
}

echo json_encode(['success' => true, 'history' => $history]);

$stmt->close();
$conn->close();
?>