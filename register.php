<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'] ?? 'manager'; // Default role is 'manager'

    // Validasi input
    if (empty($name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } else {
        try {
            // Periksa apakah email sudah ada di database
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $emailExists = $stmt->fetchColumn();

            if ($emailExists) {
                $error = "Email already registered. Please use a different email.";
            } else {
                // Hash password
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                // Insert user into the database
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)");
                $result = $stmt->execute([
                    'name' => $name,
                    'email' => $email,
                    'password' => $hashedPassword,
                    'role' => $role
                ]);

                if ($result) {
                    header("Location: login.php"); // Redirect to login after successful registration
                    exit();
                } else {
                    $error = "Something went wrong. Please try again.";
                }
            }
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - MoneyLens</title>
    <link rel="stylesheet" href="css/register.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="form-container">
        <h1 align="center">Register</h1><br>
        <form method="POST">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <label for="role">Role:</label>
            <select name="role" id="role">
                <option value="manager">Manager</option>
                <option value="admin">Admin</option>
                <option value="auditor">Auditor</option>
            </select>

            <button type="submit">Register</button>
        </form>

        <!-- Menampilkan pesan error jika ada -->
        <?php if (isset($error)): ?>
        <p style="color: red; text-align: center;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <!-- Link untuk menuju halaman login -->
        <p style="text-align: center; margin-top: 15px;">
            Sudah Punya Akun? <a href="login.php" style="color: #3498db; font-weight: bold;">Login Disini</a>
        </p>
    </div>
</body>

</html>