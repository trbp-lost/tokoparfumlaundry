<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>
    <script src="script.js"></script>
</head>
<body>
    <h1>Pembayaran</h1>
    <table id="paymentTable">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody id="paymentTableBody"></tbody>
    </table>

    <div>
        <h3>Total Pembayaran: Rp <span id="totalAmount">0</span></h3>
        <button onclick="processPayment()">Konfirmasi Pembayaran</button>
    </div>
</body>
</html>

<script>
// Function to display cart items from localStorage on the payment page
function displayCart() {
    const cartData = JSON.parse(localStorage.getItem('cartData'));
    const paymentTableBody = document.getElementById('paymentTableBody');
    const totalAmountElement = document.getElementById('totalAmount');
    paymentTableBody.innerHTML = '';
    let totalAmount = 0;

    cartData.forEach(item => {
        const totalPrice = item.price * item.quantity;
        paymentTableBody.innerHTML += `
            <tr>
                <td>${item.name}</td>
                <td>Rp ${item.price.toLocaleString()}</td>
                <td>${item.quantity}</td>
                <td>Rp ${totalPrice.toLocaleString()}</td>
            </tr>
        `;
        totalAmount += totalPrice;
    });

    totalAmountElement.textContent = totalAmount.toLocaleString();
}

// Process the payment
function processPayment() {
    const cartData = JSON.parse(localStorage.getItem('cartData'));
    const totalAmount = cartData.reduce((total, item) => total + (item.price * item.quantity), 0);

    fetch('process_payment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            cartItems: cartData,
            totalAmount: totalAmount,
        }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Pembayaran berhasil! Terima kasih.');
            localStorage.removeItem('cartData'); // Clear cart data from localStorage
            window.location.href = 'thank_you.php'; // Redirect to thank you page
        } else {
            alert('Gagal melakukan pembayaran: ' + data.message);
        }
    })
    .catch(error => alert('Terjadi kesalahan: ' + error.message));
}

// Display cart items on page load
displayCart();
</script>
