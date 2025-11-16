<?php
include 'config.php';

$sql = "SELECT service_id, nama_layanan, harga_per_unit, satuan FROM services";
$result = $conn->query($sql);

$services = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}

echo json_encode(['success' => true, 'services' => $services]);

$conn->close();
?>