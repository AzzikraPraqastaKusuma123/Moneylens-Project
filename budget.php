<?php
require 'db.php';

// Menangani input anggaran
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['budget_category'])) {
    $budget_category = $_POST['budget_category'];
    $budget_amount = $_POST['budget_amount'];
    $budget_year = $_POST['budget_year'];

    // Validasi input
    if (empty($budget_category) || empty($budget_amount) || empty($budget_year)) {
        $error = "All fields are required.";
    } else {
        // Insert anggaran ke database
        $stmt = $pdo->prepare("INSERT INTO budgets (budget_category, budget_amount, budget_year) 
                               VALUES (:budget_category, :budget_amount, :budget_year)");
        $stmt->execute([
            'budget_category' => $budget_category,
            'budget_amount' => $budget_amount,
            'budget_year' => $budget_year
        ]);
    }
}

// Mengambil semua anggaran
$budgets = $pdo->query("SELECT * FROM budgets")->fetchAll();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Input Anggaran - MoneyLens</title>
    <link rel="stylesheet" href="css/budget.css">
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

    <header>Input Anggaran</header>

    <!-- Form Input Anggaran -->
    <form method="POST">
        <h2>Tambah Anggaran</h2>
        <label for="budget_category">Kategori Anggaran:</label>
        <input type="text" name="budget_category" id="budget_category" required>

        <label for="budget_amount">Jumlah Anggaran:</label>
        <input type="number" name="budget_amount" id="budget_amount" required>

        <label for="budget_year">Tahun Anggaran:</label>
        <input type="number" name="budget_year" id="budget_year" required>

        <button type="submit">Simpan Anggaran</button>
    </form>

    <?php if (isset($error)): ?>
    <p><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
</body>

</html>