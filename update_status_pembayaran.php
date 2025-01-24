<?php
// Include database connection
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id']; // The ID of the order
    $status = $_POST['status']; // The new status

    // Validate inputs
    if (in_array($status, ['pending', 'confirm', 'decline'])) {
        // Update query
        $query = "UPDATE tb_pembayaran SET status_pesanan = ? WHERE pembayaran_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $status, $id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Status updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update status.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid status.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
