<?php
include 'db.php';
session_start();

$user_id = $_SESSION['id'];
$data = json_decode(file_get_contents('php://input'), true);

// Memeriksa apakah data pembayaran lengkap
if (isset($data['cartItems']) && isset($data['totalAmount'])) {
    $cartItems = $data['cartItems'];
    $totalAmount = $data['totalAmount'];

    // Menyimpan transaksi pembayaran
    $query = "INSERT INTO tb_pembayaran (user_id, total_amount, status, tanggal) VALUES (?, ?, 'pending', NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $totalAmount);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        $payment_id = $stmt->insert_id; // Ambil ID pembayaran yang baru saja disimpan

        // Mengupdate status keranjang menjadi "selesai" setelah pembayaran berhasil
        foreach ($cartItems as $item) {
            $cart_id = $item['id'];
            $query = "UPDATE tb_keranjang SET status = 'completed' WHERE keranjang_id = ? AND user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $cart_id, $user_id);
            $stmt->execute();
        }

        // Mengembalikan respons sukses
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal memproses pembayaran']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Data pembayaran tidak lengkap']);
}
?>
