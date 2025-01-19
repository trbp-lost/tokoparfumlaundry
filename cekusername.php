<?php
include 'db.php';
session_start();
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$cekUsername =  $input['cekUsername'];

$query = "SELECT * FROM tb_user WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $cekUsername);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Username tak tersedia.']);
} else {
    echo json_encode(['success' => true, 'message' => 'Username dapat digunakan.']);
    exit;
}
?>
