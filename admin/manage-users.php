<?php
require_once '../includes/db-connect.php'; 
require_once '../includes/auth.php'; 
requireRole('admin');
include '../includes/header.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $email = strtolower(trim($_POST['email'] ?? ''));
  $pass = $_POST['password'] ?? '';
  $role = $_POST['role'] ?? '';

  if ($name && filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($pass) >= 6 && in_array($role, ['admin','doctor','receptionist'])) {
    $hash = password_hash($pass, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO user (name,email,password_hash,role) VALUES (?,?,?,?)");
    try {
      $stmt->execute([$name,$email,$hash,$role]);
      $msg = "User created successfully!";
    } catch (PDOException $e) {
      $error = "Email already exists";
    }
  } else { $error = "Invalid input"; }
}

// Load all staff
$staff = $pdo->query("SELECT user_id, name, email, role FROM user WHERE role!='patient' ORDER BY role, name")->fetchAll();
?>
<link rel="stylesheet" href="../css/manage-user.css">
<link rel="stylesheet" href="../includes/headerstyle.css">
<h2>Manage Staff user</h2>
<form method="post">
  <label>Name</label><br><input name="name" required><br>
  <label>Email</label><br><input type="email" name="email" required><br>
  <label>Password</label><br><input type="password" name="password" required><br>
  <label>Role</label><br>
  <select name="role" required>
    <option value="">Select</option>
    <option value="admin">Admin</option>
    <option value="doctor">Doctor</option>
    <option value="receptionist">Receptionist</option>
  </select><br><br>
  <button type="submit">Create User</button>
</form>
<?php if(isset($msg)) echo "<p style='color:green;'>$msg</p>"; ?>
<?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

<h3>Existing Staff</h3>
<table border="1" cellpadding="6">
  <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th></tr>
  <?php foreach($staff as $s): ?>
    <tr>
      <td><?= $s['user_id'] ?></td>
      <td><?= htmlspecialchars($s['name']) ?></td>
      <td><?= htmlspecialchars($s['email']) ?></td>
      <td><?= $s['role'] ?></td>
    </tr>
  <?php endforeach; ?>
</table>
<?php include '../includes/footer.php'; ?>