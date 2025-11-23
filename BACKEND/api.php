<?php
// 1. SET HEADER KE JSON
// Memberitahu klien bahwa ini adalah data JSON, bukan HTML
header('Content-Type: application/json');

// 2. PANGGIL KONEKSI DATABASE
// Menggunakan file koneksi.php Anda (MySQLi)
require_once 'koneksi.php'; 

// 3. MULAI SESSION
// Wajib untuk membaca $_SESSION['logged_in'], dll.
session_start();

// 4. SIAPKAN ARRAY RESPONSE DEFAULT
$response = [
    'status' => 'error',
    'message' => 'Aksi tidak valid atau tidak ditemukan.',
    'data' => null
];

// 5. ROUTING API (PENGATUR AKSI)
$action = $_GET['action'] ?? null;

// Kita pakai try-catch untuk menangani jika ada error
try {
    // $conn adalah variabel koneksi dari file 'koneksi.php' Anda
    
    switch ($action) {
        
        // --- ENDPOINT: Ambil Daftar Layanan ---
        case 'get_layanan':
            
            // --- GANTI DENGAN QUERY ASLI ANDA ---
            // $query = "SELECT nama_layanan, deskripsi FROM tabel_layanan";
            // $result = $conn->query($query);
            // if (!$result) { throw new Exception("Query Gagal: " . $conn->error); }
            // $data = [];
            // while ($row = $result->fetch_assoc()) {
            //     $data[] = $row;
            // }
            // --- AKHIR QUERY ASLI ---

            // --- SIMULASI DATA (Agar bisa dites sekarang) ---
            $data = [
                ['nama_layanan' => 'Cuci Kiloan Hemat', 'deskripsi' => 'Solusi hemat untuk pakaian harian.'],
                ['nama_layanan' => 'Cuci Satuan Premium', 'deskripsi' => 'Perawatan premium untuk jas, gaun, dll.'],
                ['nama_layanan' => 'Express Kilat (6 Jam)', 'deskripsi' => 'Selesai dalam hitungan jam.']
            ];
            // --- Akhir Simulasi Data ---

            if (!empty($data)) {
                $response['status'] = 'success';
                $response['message'] = 'Data layanan berhasil diambil.';
                $response['data'] = $data;
            } else {
                $response['message'] = 'Data layanan tidak ditemukan.';
            }
            break;

        // --- ENDPOINT: Ambil Daftar Harga ---
        case 'get_harga':
            
            // --- GANTI DENGAN QUERY ASLI ANDA ---
            // $query = "SELECT paket, harga, satuan, fitur FROM tabel_harga";
            // $result = $conn->query($query);
            // if (!$result) { throw new Exception("Query Gagal: " . $conn->error); }
            // $data = [];
            // while ($row = $result->fetch_assoc()) {
            //     $data[] = $row;
            // }
            // --- AKHIR QUERY ASLI ---

            // --- SIMULASI DATA (Sesuai index.php Anda) ---
             $data = [
                ['paket' => 'Reguler Kiloan', 'harga' => 8000, 'satuan' => '/kg'],
                ['paket' => 'Lengkap Setrika', 'harga' => 12000, 'satuan' => '/kg'],
                ['paket' => 'Satuan (per Pcs)', 'harga' => 20000, 'satuan' => 'Mulai dari']
            ];
            // --- Akhir Simulasi Data ---

            if (!empty($data)) {
                $response['status'] = 'success';
                $response['message'] = 'Data harga berhasil diambil.';
                $response['data'] = $data;
            } else {
                $response['message'] = 'Data harga tidak ditemukan.';
            }
            break;

        // --- ENDPOINT: Cek Status Login ---
        case 'cek_status_login':
            // Ini membaca session yang Anda atur di proses_login.php
            if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === TRUE) {
                
                $response['status'] = 'success';
                $response['message'] = 'Pengguna sudah login.';
                $response['data'] = [
                    'logged_in' => true,
                    'user_id' => $_SESSION['user_id'] ?? null, // **SAYA TAMBAHKAN INI**
                    'nama_user' => $_SESSION['nama'] ?? 'User' // Sudah cocok
                    // 'role' => $_SESSION['role'] ?? 'user' // (Jika nanti Anda tambahkan role)
                ];
            } else {
                $response['status'] = 'success'; 
                $response['message'] = 'Pengguna belum login.';
                $response['data'] = [
                    'logged_in' => false
                ];
            }
            break;

        // --- KASUS JIKA 'action' TIDAK DIKENALI ---
        default:
            http_response_code(400); // 400 Bad Request
            $response['message'] = 'Aksi tidak valid. Gunakan ?action=get_layanan, ?action=get_harga, atau ?action=cek_status_login';
            break;
    }

} catch (Exception $e) {
    // Menangkap error, misal jika query database gagal
    http_response_code(500); // 500 Internal Server Error
    $response['status'] = 'error';
    $response['message'] = 'Server Error: ' . $e->getMessage();
}

// 6. TUTUP KONEKSI DATABASE
$conn->close();

// 7. KEMBALIKAN RESPONSE SEBAGAI JSON
// JSON_PRETTY_PRINT agar rapi saat dibuka di browser
echo json_encode($response, JSON_PRETTY_PRINT);

exit;