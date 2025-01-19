<?php
include 'db.php';
session_start();
header('Content-Type: application/json');
error_reporting(0);

echo json_encode($_POST['cartItems']);
date_default_timezone_set('Asia/Jakarta');
$user_id = $_SESSION['id']; 
$username = $_SESSION['username']; 
$targetDir = "bukti_bayar/";
$conn->begin_transaction();

if (!isset($_FILES['paymentProof']) || !isset($_POST['cartItems']) || !isset($_POST['totalAmount'])) {
    echo json_encode(['success' => false, 'message' => 'Data pembayaran tidak lengkap']);
    exit;
}

$cartItems = json_decode($_POST['cartItems'], true); 
$cartItemsJSON = json_encode($cartItems);;
// $cartItemsJSON = $_POST['cartItems'];
$totalAmount = $_POST['totalAmount'];

if (json_last_error() !== JSON_ERROR_NONE) {
    echo 'Kesalahan dalam parsing JSON: ' . json_last_error_msg();
}

try {
    $query = "INSERT INTO tb_pembayaran 
            (user_id, jumlah_harga, daftar_belanja, bukti_pembayaran, status_pembayaran, status_pesanan, tanggal) 
            VALUES (?, ?, ?, '', 'confirm', 'pending', NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iis", $user_id, $totalAmount, $cartItemsJSON);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Pembayaran berhasil disimpan ke database']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menyimpan pembayaran ke database']);
        exit;
    }

    $pembayaran_id = $conn->insert_id;
    
    $date = date('Ymd-His');
    $paymentProof = $_FILES['paymentProof']['name'];
    $fileName = "pembayaran_{$pembayaran_id}-{$username}-{$date}.jpg";
    $targetFilePath = $targetDir . $fileName;

    if (move_uploaded_file($_FILES['paymentProof']['tmp_name'], $targetFilePath)) {
        $updateQuery = "UPDATE tb_pembayaran SET bukti_pembayaran = ? WHERE pembayaran_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("si", $fileName, $pembayaran_id);
        $updateStmt->execute();

        echo json_encode(['success' => true, 'message' => 'Pembayaran berhasil disimpan']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menyimpan bukti pembayaran']);
        exit;
    }
    
    $payment_id = $conn->insert_id;
    $queryDelete = "DELETE FROM tb_keranjang WHERE user_id = ?";
    $stmtDelete = $conn->prepare($queryDelete);
    $stmtDelete->bind_param("i", $user_id);
    if ($stmtDelete->execute()) {
        echo json_encode(['success' => true, 'message' => 'Keranjang telah dihapus']);
    } else{
        echo json_encode(['success' => false, 'message' => 'Gagal menghapus keranjang']);
        exit;
    }
    echo json_encode($cartItemsJSON);

    $conn->commit();
    
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => "Terjadi kesalahan: " . $e->getMessage()]);
}
?>
