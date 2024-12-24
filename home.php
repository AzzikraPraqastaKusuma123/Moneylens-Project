<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

require 'db.php';

// Menghitung total transaksi
$totalTransactions = $pdo->query("SELECT SUM(amount) AS total FROM transactions")->fetchColumn();

// Menghitung total anggaran
$totalBudgets = $pdo->query("SELECT SUM(budget_amount) AS total FROM budgets")->fetchColumn();

// Menghitung sisa anggaran
$remainingBudget = $totalBudgets - $totalTransactions;

// Ambil kategori transaksi dan jumlah totalnya untuk pie chart
$transactionStats = $pdo->query("SELECT transaction_type, SUM(amount) AS total FROM transactions GROUP BY transaction_type")->fetchAll(PDO::FETCH_ASSOC);

// Format data untuk Chart.js
$labels = json_encode(array_column($transactionStats, 'transaction_type'));
$data = json_encode(array_column($transactionStats, 'total'));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - MoneyLens</title>
    <link rel="stylesheet" href="css/home.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    <!-- Main Content -->
    <main>
        <div class="container">
            <div class="dashboard-header">
                <h1>Selamat Datang di MoneyLens</h1>
                <p>Kelola keuangan Anda dengan mudah dan efisien.</p>
                <p>Login sebagai: <strong><?= htmlspecialchars($_SESSION['email']) ?></strong></p>
            </div>

            <!-- Summary Cards -->
            <div class="summary-cards">
                <div class="card">
                    <h3>Total Anggaran</h3>
                    <p>Rp <?= number_format($totalBudgets, 2, ',', '.') ?></p>
                </div>
                <div class="card">
                    <h3>Total Transaksi</h3>
                    <p>Rp <?= number_format($totalTransactions, 2, ',', '.') ?></p>
                </div>
                <div class="card">
                    <h3>Sisa Anggaran</h3>
                    <p>Rp <?= number_format($remainingBudget, 2, ',', '.') ?></p>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="charts-container">
                <div class="chart">
                    <h3>Distribusi Transaksi (Pie Chart)</h3>
                    <canvas id="pieChart"></canvas>
                </div>
                <div class="chart">
                    <h3>Statistik Anggaran vs Transaksi (Bar Chart)</h3>
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>
    </main>

    <script>
    // Data untuk Pie Chart
    const pieData = {
        labels: ['Total Transaksi', 'Total Anggaran'],
        datasets: [{
            label: 'Perbandingan Transaksi dan Anggaran',
            data: [<?= $totalTransactions ?>, <?= $totalBudgets ?>],
            backgroundColor: ['#f44336', '#4caf50'], // Warna untuk masing-masing bagian
            hoverBackgroundColor: ['#e57373', '#81c784'], // Warna ketika dihover
            borderColor: ['#ffffff', '#ffffff'], // Garis pembatas
            borderWidth: 1 // Ketebalan garis pembatas
        }]
    };

    // Konfigurasi Pie Chart
    const pieConfig = {
        type: 'pie',
        data: pieData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top', // Posisi legend di atas
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const percentage = ((value / (<?= $totalTransactions + $totalBudgets ?>)) * 100)
                                .toFixed(2);
                            return `${label}: Rp ${value.toLocaleString()} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    };

    // Render Pie Chart
    const pieChart = new Chart(
        document.getElementById('pieChart'),
        pieConfig
    );

    // Data untuk Bar Chart
    const barData = {
        labels: ['Total Anggaran', 'Total Transaksi', 'Sisa Anggaran'],
        datasets: [{
            label: 'Jumlah (Rp)',
            data: [<?= $totalBudgets ?>, <?= $totalTransactions ?>, <?= $remainingBudget ?>],
            backgroundColor: ['#34568b', '#ff9800', '#4caf50'],
        }]
    };

    // Konfigurasi Bar Chart
    const barConfig = {
        type: 'bar',
        data: barData,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    };

    // Render Bar Chart
    const barChart = new Chart(
        document.getElementById('barChart'),
        barConfig
    );
    </script>
</body>

</html>