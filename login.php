<?php
require 'auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (login($email, $password)) {
        $_SESSION['logged_in'] = true;
        $_SESSION['email'] = $email; // Simpan email untuk referensi user
        header("Location: home.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login - MoneyLens</title>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <div class="login-container">
        <h1>Login ke MoneyLens</h1>
        <form method="POST">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">Login</button>
        </form>
        <?php if (isset($error)): ?>
        <p><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <a href="register.php" class="register-link">Belum punya akun? Daftar di sini</a>
    </div>
</body>

</html>