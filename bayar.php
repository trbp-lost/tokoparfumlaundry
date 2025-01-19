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
    <form id="paymentForm" enctype="multipart/form-data">
        <label for="paymentProof">Unggah Bukti Pembayaran:</label>
        <input type="file" id="paymentProof" name="paymentProof" accept="image/*" required>
        <button type="button" onclick="processPayment()">Konfirmasi Pembayaran</button>
    </form>
</body>
</html>

<script>
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
    const paymentProofInput = document.getElementById('paymentProof');
    console.log(cartData);
    if (!paymentProofInput.files.length) {
        alert('Harap unggah bukti pembayaran!');
        return;
    }

    const formData = new FormData();
    formData.append('cartItems', JSON.stringify(cartData));
    formData.append('totalAmount', totalAmount);
    formData.append('paymentProof', paymentProofInput.files[0]);

    fetch('process_payment.php', {
        method: 'POST',
        // headers: {
        //     'Content-Type': 'application/json',
        // },
        body: formData,
        // body: JSON.stringify({
        //     cartItems: cartData,
        //     totalAmount: totalAmount,
        //     paymentProof: paymentProofInput.files[0],
        // }),
    })
    .then(response => response.json())
    .then(data => {
        console.log('Form data', formData);

        console.log('Data yang diterima:', data.jj);
        if (data.success) {
            alert('Pembayaran berhasil! Terima kasih.');
            localStorage.removeItem('cartData');
            window.location.href = 'thank_you.php'; 
        } else {
            alert('Gagal melakukan pembayaran: ' + data.message);
        }
    })
    .catch(error => alert('Terjadi kesalahan: ' + error.message));
}

displayCart();
</script>
