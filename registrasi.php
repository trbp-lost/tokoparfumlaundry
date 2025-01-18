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
            <input type="text" name="user" placeholder="Username" class="input-control"> 
            <input type="submit" name="checkUsername" value="Check Username" class="input-control btn">
            
            <input type="text" name="name" placeholder="name" class="input-control">
            <input type="text" name="address" placeholder="address" class="input-control">
            <input type="password" name="pass" placeholder="Password" class="input-control">
            <input type="password" name="confirm_pass" placeholder="Confirm Password" class="input-control">
            <input type="submit" name="submit" value="Registrasi" class="btn">
        </form>
        <?php
            include 'db.php';
            if(isset($_POST['checkUsername'])){
                $user = mysqli_real_escape_string($conn, $_POST['user']);
                $cek = mysqli_query($conn, "SELECT username FROM tb_admin WHERE username = '$user'");

                if($cek && mysqli_num_rows($cek) > 0){
                    echo '<script>alert("Username atau password anda salah!")</script>';
                }else{
                    echo '<script>window.location="dashboard.php"</script>';
                }
            }

            if(isset($_POST['submit'])){
                session_start();

                $user = mysqli_real_escape_string($conn, $_POST['user']);
                $pass = mysqli_real_escape_string($conn, $_POST['pass']);
                $confirm_pass = mysqli_real_escape_string($conn, $_POST['confirm_pass']);

                $cek = mysqli_query($conn, "SELECT username FROM tb_admin WHERE username == $user");
                if(mysqli_num_rows($cek) > 0){
                    $d = mysqli_fetch_object($cek);
                    $_SESSION['status_login'] = true;
                    $_SESSION['a_global'] = $d;
                    $_SESSION['id'] = $d->admin_id;
                    echo '<script>window.location="dashboard.php"</script>';
                    echo '<script>alert("Username atau password anda salah!")</script>';

                }else{
                    echo '<script>alert("Username atau password anda salah!")</script>';
                }

            }
        ?>
    </div>
</body>
</html>