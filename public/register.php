<?php
require_once '../includes/db-connect.php';
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $pass = $_POST['password'] ?? '';

    // Basic validation
    if ($name && filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($pass) >= 6) {

        // Check if email already exists
        $check = $pdo->prepare("SELECT user_id FROM user WHERE email = ? LIMIT 1");
        $check->execute([$email]);

        if ($check->fetch()) {
            $error = "This email is already registered. Please login instead.";
        } else {
            // Hash password
            $hash = password_hash($pass, PASSWORD_DEFAULT);

            // Insert new patient account
            $stmt = $pdo->prepare("INSERT INTO user (name, email, password_hash, role) 
                                   VALUES (?, ?, ?, 'patient')");
            $stmt->execute([$name, $email, $hash]);

            // Set session values
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['role'] = 'patient';
            $_SESSION['name'] = $name;

            // Redirect to login
            header('Location: login.php');
            exit;
        }
    } else {
        $error = "Invalid input. Password must be at least 6 characters.";
    }
}


?>
<!-- 
<link rel="stylesheet" href="../includes/headerstyle.css"> -->
<!-- <link rel="stylesheet" href="../css/register.css"> -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../includes/headerstyle.css">
<link rel="stylesheet" href="../css/register.css">
</head>
<body>
  
<main>
    <div class="register-box">
      <h2>Register (Patient)</h2>
      <form method="post">
        <label>Full Name</label>
        <input type="text" name="name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Create an account</button>
      </form>

      <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>
    </div>
</main>

  <footer>
    <?php include '../includes/footer.php'; ?>
  </footer>
</body>
</html>


