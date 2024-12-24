<?php
require 'db.php';

// Menangani input transaksi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['transaction_type'])) {
    $transaction_type = $_POST['transaction_type'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $transaction_date = $_POST['transaction_date'];

    // Validasi input
    if (empty($amount) || empty($description) || empty($transaction_date)) {
        $error = "All fields are required.";
    } else {
        // Insert transaksi ke database
        $stmt = $pdo->prepare("INSERT INTO transactions (transaction_type, amount, description, transaction_date) 
                               VALUES (:transaction_type, :amount, :description, :transaction_date)");
        $stmt->execute([
            'transaction_type' => $transaction_type,
            'amount' => $amount,
            'description' => $description,
            'transaction_date' => $transaction_date
        ]);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Input Transaksi dan Anggaran - MoneyLens</title>
    <link rel="stylesheet" href="css/transactions.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <a href="home.php" class="nav-brand">MoneyLens</a>
            <ul class="nav-links">
                <li><a href="home.php">Home</a></li>
                <li><a href="transactions.php">Transaksi</a></li>
                <li><a href="budget.php">Anggaran</a></li>
                <li><a href="tables.php">Tabel</a></li>
                <li><a href="login.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <header>Input Transaksi dan Anggaran</header>
    <main>
        <!-- Form Input -->
        <form method="POST">
            <h2>Tambah Transaksi</h2>
            <label for="transaction_type">Jenis Transaksi:</label>
            <select name="transaction_type" id="transaction_type" required>
                <option value="Expense">Pembayaran</option>
                <option value="Expense">Pembelian</option>
                <option value="Expense">Perbaikan</option>
            </select>

            <label for="amount">Jumlah:</label>
            <input type="number" name="amount" id="amount" required>

            <label for="description">Deskripsi:</label>
            <textarea name="description" id="description" required></textarea>

            <label for="transaction_date">Tanggal Transaksi:</label>
            <input type="date" name="transaction_date" id="transaction_date" required>

            <button type="submit">Simpan Transaksi</button>
        </form>

        <?php if (isset($error)): ?>
        <p><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </main>
</body>

</html>