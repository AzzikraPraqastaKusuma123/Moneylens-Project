<?php
require 'db.php';

// Mengambil semua transaksi
$transactions = $pdo->query("SELECT * FROM transactions")->fetchAll();

// Mengambil semua anggaran
$budgets = $pdo->query("SELECT * FROM budgets")->fetchAll();

// Menghitung total transaksi
$totalTransactions = $pdo->query("SELECT SUM(amount) AS total FROM transactions")->fetchColumn();

// Menghitung total anggaran
$totalBudgets = $pdo->query("SELECT SUM(budget_amount) AS total FROM budgets")->fetchColumn();

// Menghitung sisa anggaran
$remainingBudget = $totalBudgets - $totalTransactions;
?>

<!DOCTYPE html>
<html>

<head>
    <title>Tabel Transaksi dan Anggaran - MoneyLens</title>
    <link rel="stylesheet" href="css/tables.css">
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

    <header>Tabel Transaksi dan Anggaran</header>
    <main>
        <div class="container">
            <!-- Tabel Transaksi -->
            <h2>Daftar Transaksi</h2>
            <table>
                <tr>
                    <th>Jenis Transaksi</th>
                    <th>Jumlah</th>
                    <th>Deskripsi</th>
                    <th>Tanggal Transaksi</th>
                </tr>
                <?php foreach ($transactions as $transaction): ?>
                <tr>
                    <td><?= htmlspecialchars($transaction['transaction_type']) ?></td>
                    <td><?= htmlspecialchars($transaction['amount']) ?></td>
                    <td><?= htmlspecialchars($transaction['description']) ?></td>
                    <td><?= htmlspecialchars($transaction['transaction_date']) ?></td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3"><strong>Total Transaksi</strong></td>
                    <td><strong><?= htmlspecialchars(number_format($totalTransactions, 2)) ?></strong></td>
                </tr>
            </table>

            <!-- Tabel Anggaran -->
            <h2>Daftar Anggaran</h2>
            <table>
                <tr>
                    <th>Kategori Anggaran</th>
                    <th>Jumlah Anggaran</th>
                    <th>Tahun Anggaran</th>
                </tr>
                <?php foreach ($budgets as $budget): ?>
                <tr>
                    <td><?= htmlspecialchars($budget['budget_category']) ?></td>
                    <td><?= htmlspecialchars($budget['budget_amount']) ?></td>
                    <td><?= htmlspecialchars($budget['budget_year']) ?></td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="2"><strong>Total Anggaran</strong></td>
                    <td><strong><?= htmlspecialchars(number_format($totalBudgets, 2)) ?></strong></td>
                </tr>
            </table>

            <!-- Tabel Sisa Anggaran -->
            <h2>Ringkasan Keuangan</h2>
            <table>
                <tr>
                    <th>Total Anggaran</th>
                    <th>Total Transaksi</th>
                    <th>Sisa Anggaran</th>
                </tr>
                <tr>
                    <td><strong><?= htmlspecialchars(number_format($totalBudgets, 2)) ?></strong></td>
                    <td><strong><?= htmlspecialchars(number_format($totalTransactions, 2)) ?></strong></td>
                    <td><strong><?= htmlspecialchars(number_format($remainingBudget, 2)) ?></strong></td>
                </tr>
            </table>
        </div>
    </main>
</body>

</html>