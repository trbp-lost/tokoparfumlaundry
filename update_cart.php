<?php
include 'db.php';
session_start();

$user_id = $_SESSION['id'];
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id']) && isset($data['quantity'])) {
    $cart_id = $data['id'];
    $quantity = $data['quantity'];

    // Update kuantitas produk di keranjang
    $query = "UPDATE tb_keranjang SET jumlah = ? WHERE keranjang_id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iii", $quantity, $cart_id, $user_id);
    $stmt->execute();

    // Mengambil data keranjang terbaru untuk dikembalikan ke klien
    if ($stmt->affected_rows > 0) {
        // Query untuk mendapatkan data terbaru
        $query = "SELECT k.keranjang_id, k.product_id, p.product_name, p.product_price, k.jumlah 
                  FROM tb_keranjang k
                  JOIN tb_product p ON k.product_id = p.product_id 
                  WHERE k.user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $cartItems = [];
        while ($row = $result->fetch_assoc()) {
            $cartItems[] = [
                'id' => $row['keranjang_id'],
                'name' => $row['product_name'],
                'price' => $row['product_price'],
                'quantity' => $row['jumlah']
            ];
        }

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'cartItems' => $cartItems]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal memperbarui jumlah']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
}
?>
