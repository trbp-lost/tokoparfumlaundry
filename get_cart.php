<?php
include('db.php'); 
session_start();

if (!isset($_SESSION['status_login'])) {
    echo json_encode(['success' => false, 'message' => 'User tidak login']);
    echo '<script>window.location="login.php"</script>';
}

$user_id = $_SESSION['id']; 

$query = "SELECT k.user_id, k.keranjang_id, k.product_id, p.product_id, p.product_name, p.product_price, k.jumlah 
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
        'id_user' => $row['user_id'],
        'id_product' => $row['product_id'],
        'name' => $row['product_name'],
        'price' => $row['product_price'],
        'quantity' => $row['jumlah']
    ];
}

header('Content-Type: application/json');
echo json_encode($cartItems);
?>
