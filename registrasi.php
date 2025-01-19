<?php
    session_start();
    if($_SESSION['status_login'] == true) {
        echo '<script>window.location="dashboard.php"</script>';
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrasi | TokoParfumLaundry</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
</head>
<body id="bg-login">
    <div class="box-login">
        <h2>Registrasi</h2>
        <form action="" method="POST">
            <input type="text" name="user" placeholder="Username" id="username" class="input-control"> 
            <button type="button" onclick="checkUsername()" class="input-control btn">Check Username</button>
            
            <input type="password" name="pass" placeholder="Password" id="password" class="input-control">
            <input type="password" name="confirm_pass" placeholder="Confirm Password" id="confirm_pass" class="input-control">
            <input type="text" name="fullname" placeholder="full name" id="fullname" class="input-control">
            <input type="number" name="telephone" placeholder="telephone" id="telp" class="input-control">
            <input type="email" name="email" placeholder="email" id="email" class="input-control">
            <textarea name="address" placeholder="address" id="address" class="input-control"></textarea>

            <button type="button" onclick="CreateAccount()" class="btn">Registrasi</button>
        </form>
    <script>
        
function checkUsername() {
    const cekUsername = document.getElementById('username').value;

    fetch('cekusername.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            cekUsername: cekUsername,
        }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Username tersedia.');
        } else {
            alert('Username tak tersedia.');
        }
    })
    .catch(error => alert('Terjadi kesalahan cek Usename: ' + error.message));
}

function CreateAccount() {
    const cekUsername = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const confirm_pass = document.getElementById('confirm_pass').value;
    const fullname = document.getElementById('fullname').value;
    const telp = document.getElementById('telp').value;
    const email = document.getElementById('email').value;
    const address = document.getElementById('address').value;
    
    fetch('regis.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            cekUsername: cekUsername,
            password: password,
            confirm_pass: confirm_pass,
            fullname: fullname,
            telp: telp,
            email: email,
            address: address,
        }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Akun Berhasil dibuat.');
            window.location.href = 'dashboard.php'; 
        } else {
            alert(data.message);
        }
    })
    .catch(error => alert('Terjadi kesalahan: ' + error.message));
}
</script>
</body>
</html>