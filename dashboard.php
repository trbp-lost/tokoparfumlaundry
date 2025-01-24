<?php
    // error_reporting(0);

    include 'db.php';
    session_start();

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
                <p><a href="tambah-produk.php">Tambah Data</a></p>
                <table border="1" cellspacing="0" class="table">
                    <thead>
                        <tr>
                            <th width="60px">No</th>
                            <th>Tanggal</th>
                            <th>Nama Pembeli</th>
                            <th>Alamat</th>
                            <th>No Telepon</th>
                            <th>Detail Barang</th>
                            <th>Harga</th>
                            <th>Bukti Pembayaran</th>
                            <th>Status</th>
                            <th width="150px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $no = 1;
                            $produk = mysqli_query($conn, "SELECT * FROM tb_pembayaran LEFT JOIN tb_user USING (user_id) ORDER BY pembayaran_id DESC");
                            if(mysqli_num_rows($produk) > 0){
                            while($row = mysqli_fetch_array($produk)){
                                $daftar_belanja = json_decode($row['daftar_belanja'], true);
                        ?>
                        <tr style="background-color: <?= ($row['status_pesanan'] == 'confirm') ? "green" : (($row['status_pesanan'] == 'decline') ? "red" : "") ?>;">
                            <td style="color: <?= ($row['status_pesanan'] == 'pending') ? "" : "white" ?>;"><?= $no++ ?></td>td
                            <td style="color: <?= ($row['status_pesanan'] == 'pending') ? "" : "white" ?>;"><?= $row['tanggal'] ?></td>
                            <td style="color: <?= ($row['status_pesanan'] == 'pending') ? "" : "white" ?>;"><?= $row['name'] ?></td>
                            <td style="color: <?= ($row['status_pesanan'] == 'pending') ? "" : "white" ?>;"><?= $row['user_address'] ?></td>
                            <td style="color: <?= ($row['status_pesanan'] == 'pending') ? "" : "white" ?>;"><?= $row['user_telp'] ?></td>
                            <td style="color: <?= ($row['status_pesanan'] == 'pending') ? "" : "white" ?>;">
                            <?php
                                if (is_array(value: $daftar_belanja)) {
                                    foreach ($daftar_belanja as $item) {
                                        echo "- " . htmlspecialchars($item['name']) . " (Jumlah: " . $item['quantity'] . ")<br>";
                                    }
                                } else {
                                    echo "Barang tidak valid";
                                }
                            ?>
                            </td>
                            <td style="color: <?= ($row['status_pesanan'] == 'pending') ? "" : "white" ?>;">Rp. <?php echo number_format($row['jumlah_harga']) ?></td>
                            <td style="color: <?= ($row['status_pesanan'] == 'pending') ? "" : "white" ?>;"><?php echo $row['status_pesanan'] ?></td>
                            <td style="color: <?= ($row['status_pesanan'] == 'pending') ? "" : "white" ?>;">
                            <?php
                                $file_path = 'bukti_bayar/' . $row['bukti_pembayaran'];
                                if (file_exists($file_path)) {
                                    echo "<a href='" . $file_path . "' target='_blank'>
                                            <img src='" . $file_path . "' alt='Bukti Pembayaran' style='width:100px;height:auto;'>
                                        </a>";
                                } else {
                                    echo "Bukti tidak tersedia";
                                }
                            ?>
                            </td>
                            <td style="color: <?= ($row['status_pesanan'] == 'pending') ? "" : "white" ?>;">
                                <?php 
                                if($row['status_pesanan'] == 'decline' || $row['status_pesanan'] == 'pending'){
                                    echo "<button style='background-color: green; color:white;'  class='update-status'  data-id='{$row['pembayaran_id']}' data-status='confirm'>Terima</button> ";
                                }
                                if($row['status_pesanan'] == 'confirm' || $row['status_pesanan'] == 'pending'){
                                    echo "<button style='background-color: red ; color:white;'  class='update-status' data-id='{$row['pembayaran_id']}' data-status='decline'>Batalkan</button>";
                                }
                                ?>
                            </td>
                        </tr>
                        <?php }}else{ ?>
                            <tr>
                                <td colspan="7">Tidak ada data</td>
                            </tr>

                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- footer -->
    <footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).on('click', '.update-status', function () {
            var id = $(this).data('id'); // Get the ID from the button
            var status = $(this).data('status'); // Get the new status from the button

            // Confirm action
            if (confirm('Are you sure you want to change the status to ' + status + '?')) {
                // AJAX request
                $.ajax({
                    url: 'update_status_pembayaran.php',
                    type: 'POST',
                    data: { id: id, status: status },
                    success: function (response) {
                        var result = JSON.parse(response);
                        if (result.success) {
                            alert(result.message);
                            location.reload(); // Reload the page to reflect the changes
                        } else {
                            alert(result.message);
                        }
                    },
                    error: function () {
                        alert('An error occurred. Please try again.');
                    }
                });
            }
        });
    </script>
        <div class="container">
            <small>Copyright &copy; 2025 - TokoParfumLaundry.</small>
        </div>
    </footer>
</body>
</html>