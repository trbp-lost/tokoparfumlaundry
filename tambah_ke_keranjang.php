<?php
include 'db.php';
session_start();

if (!isset($_SESSION['status_login'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    echo '<script>window.location="login.php"</script>';
    exit;
}

$user_id = $_SESSION['id'];

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['product_id']) || !isset($data['quantity']) || !isset($data['product_name']) || !isset($data['product_price'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    exit;
}

$product_id = $data['product_id'];
$product_name = $data['product_name'];
$product_price = $data['product_price'];
$quantity = $data['quantity'];

$query = "SELECT * FROM tb_keranjang WHERE user_id = ? AND product_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $query = "UPDATE tb_keranjang SET jumlah = jumlah + ? WHERE product_id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iii", $quantity, $product_id, $user_id);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Produk berhasil ditambahkan ke keranjang.']);
} else {
    $query = "INSERT INTO tb_keranjang (user_id, product_id, jumlah, harga) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiii", $user_id, $product_id, $quantity, $product_price);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Produk berhasil ditambahkan ke keranjang.']);

}

$stmt->close();
$conn->close();

?>
