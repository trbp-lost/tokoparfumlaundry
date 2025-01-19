<?php
include 'db.php';
session_start();
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

$cekUsername =  $input['cekUsername'];
$password =  $input['password'];
$confirm_pass =  $input['confirm_pass'];
$fullname =  $input['fullname'];
$telp =  $input['telp'];
$email =  $input['email'];
$address =  $input['address'];

if (!$cekUsername || !$password || !$confirm_pass || !$fullname || !$telp || !$email || !$address) {
    echo json_encode(['success' => false, 'message' => 'Semua field harus diisi.']);
    exit;
}

if ($password !== $confirm_pass) {
    echo json_encode(['success' => false, 'message' => 'Password tidak cocok.']);
    exit;
}

$query = "SELECT * FROM tb_user WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $cekUsername);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Username tak tersedia.']);

} else {
    echo json_encode(['success' => true, 'message' => 'Username dapat digunakan.']);

    $query2 = "INSERT INTO tb_user 
            (username, password, name, user_telp, user_email, user_address) 
            VALUES ( ?, ?, ?, ?, ?, ?)";
    $stmt2 = $conn->prepare($query2);
    $hashed_password = md5($password);

    $stmt2->bind_param("ssssss", $cekUsername, $password, $fullname, $telp, $email, $address);

    if ($stmt2->execute()) {
        echo json_encode(['success' => true, 'message' => 'Pembuatan akun berhasil']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal dalam pembuatan akun']);
        exit;
    }
}
?>
