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
        <tbody id="cartTableBody">
            <!-- Data keranjang akan dimasukkan di sini menggunakan JavaScript -->
        </tbody>
    </table>
    <br>
    <button onclick="redirectToPayment()">Bayar</button>

    <script>
        // Simulasi data keranjang belanja dari backend
        const cartItems = [
            { id: 1, name: 'Produk A', price: 50000, quantity: 2 },
            { id: 2, name: 'Produk B', price: 75000, quantity: 1 },
        ];

        // Fungsi untuk memperbarui tampilan tabel keranjang belanja
        function renderCart() {
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

        // Fungsi untuk memperbarui jumlah produk
        function updateQuantity(id, change, manualValue = null) {
            const item = cartItems.find(item => item.id === id);
            if (item) {
                if (manualValue !== null) {
                    item.quantity = parseInt(manualValue);
                } else {
                    item.quantity += change;
                    if (item.quantity < 1) item.quantity = 1; // Minimum jumlah adalah 1
                }
            }
            renderCart();
        }

        // Fungsi untuk menghapus item dari keranjang
        function removeItem(id) {
            const index = cartItems.findIndex(item => item.id === id);
            if (index > -1) {
                cartItems.splice(index, 1);
            }
            renderCart();
        }

        // Fungsi untuk mengarahkan ke halaman pembayaran
        function redirectToPayment() {
            // Redirect ke halaman pembayaran
            window.location.href = 'bayar.php';
        }

        // Render tabel keranjang belanja saat halaman dimuat
        renderCart();
    </script>
</body>
</html>
