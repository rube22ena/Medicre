<?php
require_once '../includes/db-connect.php';
include '../includes/header.php';
include_once '../includes/auth.php';
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



    <link rel="stylesheet" href="../includes/headerstyle.css">
    <link rel="stylesheet" href="../css/register.css">

    <div class="register-box">
      <h2>Register (Patient)</h2>
      <form  id="registerForm"method="post">
        <label>Full Name</label><br>
<input type="text" id="name" name="name" 
       value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required><br>

<label>Email</label><br>
<input type="email" id="email" name="email" 
       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required><br>

<label>Password</label><br>
<input type="password" id="password" name="password" required><br>

        <button type="submit">Create an account</button>
      </form>
       <div id="errorBox"></div>

      <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>
    </div>
<script src="../js/register.js"></script>
</body>
</html>


<?php include '../includes/footer.php'; ?>

