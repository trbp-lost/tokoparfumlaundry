<?php
    session_start();
    // Cek data yang diterima di server


    if (!isset($_SESSION['status_login'])) {
        $_SESSION['status_login'] = false;
        echo '<script>window.location="login.php"</script>';
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        .quantity-control {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .quantity-control button {
            width: 30px;
            height: 30px;
            font-size: 18px;
        }
        .quantity-control input {
            width: 50px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Keranjang Belanja</h1>
    <table>
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="cartTableBody"></tbody>
    </table>
    <br>
    <button onclick="redirectToPayment()">Bayar</button>

    <script>
        let cartItems = [];

        function fetchCart() {
            fetch('get_cart.php')
                .then(response => response.json())
                .then(data => {
                    console.log('Data fetched from server:', data);
                    if (data && Array.isArray(data)) {
                        cartItems = data;
                        renderCart(cartItems);
                    } else {
                        alert('Data keranjang tidak valid');
                    }
                })
                .catch(error => alert('Gagal memuat data keranjang: ' + error.message));
        }

        function renderCart(cartItems) {
            const cartTableBody = document.getElementById('cartTableBody');
            cartTableBody.innerHTML = '';

            cartItems.forEach(item => {
                const totalPrice = item.price * item.quantity;
                cartTableBody.innerHTML += `
                    <tr>
                        <td>${item.name}</td>
                        <td>Rp ${item.price.toLocaleString()}</td>
                        <td>
                            <div class="quantity-control">
                                <button onclick="updateQuantity(${item.id}, -1)">-</button>
                                <input type="number" value="${item.quantity}" min="1" onchange="updateQuantity(${item.id}, 0, this.value)">
                                <button onclick="updateQuantity(${item.id}, 1)">+</button>
                            </div>
                        </td>
                        <td>Rp ${totalPrice.toLocaleString()}</td>
                        <td><button onclick="removeItem(${item.id})">Hapus</button></td>
                    </tr>
                `;
            });
        }

        function updateQuantity(id, change, manualValue = null) {
            const item = cartItems.find(item => item.id === id);
            if (!item) {
                console.error('Item tidak ditemukan untuk ID:', id);
                fetchCart();
                return;
            }

            let newQuantity = item.quantity;
            if (manualValue !== null) {
                newQuantity = parseInt(manualValue) || 1; 
            } else {
                newQuantity += change; 
                if (newQuantity < 1) newQuantity = 1;
            }

            item.quantity = newQuantity;

            console.log('Updated item ID:', id, 'New Quantity:', newQuantity);

            fetch('update_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ 
                    id: id, 
                    quantity: newQuantity 
                }),
            })
            .then(response => response.json())
            .then(data => {
                console.log('Update response:', data);
                if (data.success) {
                    fetchCart();
                } else {
                    alert('Gagal memperbarui jumlah produk: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => alert('Terjadi kesalahan: ' + error.message));
        }

        function removeItem(id) {
            if (confirm('Anda yakin ingin menghapus produk ini dari keranjang?')) {
                fetch('remove_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        fetchCart();
                    } else {
                        alert('Gagal menghapus produk: ' + data.message);
                    }
                })
                .catch(error => alert('Terjadi kesalahan: ' + error.message));
            }
        }

        function redirectToPayment() {
            window.location.href = 'bayar.php';
        }

        fetchCart();
    </script>
</body>
</html>
