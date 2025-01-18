<?php
include('db.php');

session_start();
$user_id = $_SESSION['id'];
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'])) {
    $cart_id = $data['id'];

    $query = "DELETE FROM tb_keranjang WHERE keranjang_id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $cart_id, $user_id);
    $stmt->execute();

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Data tidak valid.']);
}
?>
