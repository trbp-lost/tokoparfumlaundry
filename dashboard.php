<?php
    // error_reporting(0);

    include 'db.php';
    session_start();

    $cek2 = mysqli_query($conn, "SELECT * FROM tb_user WHERE user_id = '".$_SESSION['id']."'");
        if(mysqli_num_rows($cek2) > 0){
            echo '<script>window.location="index.php"</script>';
        }
    
    if ($_SESSION['id'] == null )echo '<script>window.location="/"</script>';
    if($_SESSION['status_login'] != true) {
        echo '<script>window.location="login.php"</script>';
        
    }
    
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TokoParfumLaundry</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
</head>
<body>
    <!-- header -->
    <header>
        <div class="container">
        <h1><a href="dashboard.php">Toko Parfum Laundry</a></h1>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="profil.php">Profil</a></li>
            <li><a href="data-kategori.php">Data Kategori</a></li>
            <li><a href="data-produk.php">Data Produk</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
        </div>
    </header>

    <!-- content -->
    <div class="section">
        <div class="container">
            <h3>Dashboard</h3>
            <div class="box">
                <h4>Selamat Datang <?php echo $_SESSION['a_global']->admin_name ?> Di Toko Parfum Laundry Online</h4>
            </div>
        </div>
    </div>

    <!-- footer -->
    <footer>
        <div class="container">
            <small>Copyright &copy; 2025 - TokoParfumLaundry.</small>
        </div>
    </footer>
</body>
</html>