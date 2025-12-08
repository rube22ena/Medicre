<?php
require_once '../includes/db-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $pass  = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM user WHERE email=? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($pass, $user['password_hash'])) {
    session_start();
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['role']    = $user['role'];
    $_SESSION['name']    = $user['name'];

    // Redirect based on role
    if ($user['role'] === 'doctor') {
        header('Location: doctor_dashboard.php');
    } elseif ($user['role'] === 'receptionist') {
        header('Location: receptionist_dashboard.php');
    } elseif ($user['role'] === 'admin') {
        header('Location: admin_dashboard.php');
    } elseif ($user['role'] === 'patient') {
        header('Location: appointments.php');   // 👈 put it here
        exit;
    }
}
       
    } else {
        $error = "Invalid email or password.";
    }

include '../includes/header.php';
?>

<h2>Login</h2>
<form method="post">
  <input type="email" name="email" placeholder="Email" required><br>
  <input type="password" name="password" placeholder="Password" required><br>
  <button type="submit">Login</button>
</form>
<?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<?php include '../includes/footer.php'; ?>