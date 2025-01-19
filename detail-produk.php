<?php
    // error_reporting(0);
    include 'db.php';
    session_start();

    if (!isset($_SESSION['status_login'])) {
        $_SESSION['status_login'] = false;
    }

    $kontak = mysqli_query($conn, "SELECT admin_name, admin_telp, admin_email, admin_address FROM tb_admin WHERE admin_id = 1");
    $a = mysqli_fetch_object($kontak);

    $produk = mysqli_query($conn, "SELECT * FROM tb_product WHERE product_id = '".$_GET['id']."' ");
    $p = mysqli_fetch_object($produk);
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
        <h1><a href="index.php">Toko Parfum Laundry</a></h1>
        <?php
            if($_SESSION['status_login'] != true) {
                echo '<ul><li><a href="login.php">Login</a></li></ul>';
            } else{
                echo '<ul><li><a href="logout.php">Logout</a></li></ul>';
                echo '<ul><li><a href="keranjang.php">Keranjang</a></li></ul>';

                if($_SESSION['admin'] == true)  echo'<ul><li><a href="dashboard.php">Dashboard</a></li></ul>';
            }
        ?>
        <ul>
            <li><a href="produk.php">Produk</a></li>
        </ul>
        </div>
    </header>

    <!-- search -->
    <div class="search">
        <div class="container">
            <form action="produk.php">
                <input type="text" name="search" placeholder="Cari Produk"  ?>
                <input type="hidden" name="kat" ?>
                <input type="submit" name="cari" value="Cari Produk">
            </form>
        </div>
    </div>

    <!-- product detail -->
    <div class="section">
        <div class="container">
            <h3>Detail Produk</h3>
            <div class="box">
                <div class="col-2">
                    <img src="produk/<?php echo $p->product_image ?>">
                </div>
                <div class="col-2">
                    <h2><?php echo $p->product_name ?></h2>
                    <h3>Rp. <?php echo number_format($p->product_price) ?></h3>
                    <p>Deskripsi :<br>
                        <?php echo $p->product_description ?>
                    </p>
                    <p><a href="https://api.whatsapp.com/send?phone=<?php echo $a->admin_telp ?>&text=Hai, saya tertarik dengan produk anda." target="_blank">Hubungi Via WhatsApp
                    <p>
                        <img src="img/icon whatsapp.png" width="50px"></a>
                    </p>
                    
                    <p>
                        <div>
                            <button id="kurang">-</button>
                            <input type="number" name="qty" id="qty" value="1" min="1">
                            <button id="tambah">+</button>
                            <button id="addToCart">Tambah Keranjang</button>
                        </div>
                    </p>
    
                    <p><button>Pesan Sekarang</button></p>    
                    </p>
                </div>
            </div>  
        </div>
    </div>

    <!-- footer -->
    <div class="footer">
        <div class="container">
            <h4>Pemilik Usaha</h4>
            <p><?php echo $a->admin_name ?></p>

            <h4>Nomor Telpon</h4>
            <p><?php echo $a->admin_telp ?></p>

            <h4>Email</h4>
            <p><?php echo $a->admin_email ?></p>

            <h4>Alamat</h4>
            <p><?php echo $a->admin_address ?></p>
            <small>Copyright &copy; 2025 - TokoParfumLaundry.</small>
        </div>
    </div>

    <script>
    let quantity = 1;

    document.getElementById('tambah').addEventListener('click', function() {
        quantity++;
        document.getElementById('qty').value = quantity;
    });

    document.getElementById('kurang').addEventListener('click', function() {
        if (quantity > 1) {
            quantity--;
            document.getElementById('qty').value = quantity;
        }
    });

    document.getElementById('addToCart').addEventListener('click', function() {
        let productId = <?php echo $p->product_id; ?>;
        let productName = "<?php echo addslashes($p->product_name); ?>";
        let productPrice = <?php echo $p->product_price; ?>;
        let qty = document.getElementById('qty').value;

        let cartData = {
            product_id: productId,
            product_name: productName,
            product_price: productPrice,
            quantity: qty
        };
        
        fetch('tambah_ke_keranjang.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(cartData),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Produk berhasil ditambahkan ke keranjang!');
            } else {
                alert('Terjadi kesalahan saat menambahkan produk ke keranjang.');
            }
        });
    });
</script>    
</body>
</html>