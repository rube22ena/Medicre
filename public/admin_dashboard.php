<?php

require_once '../includes/db-connect.php';
require_once '../includes/auth.php';
requireRole('admin');

include '../includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="dashboard">

<h2>📊 Admin Dashboard</h2>
<p>Welcome, <?= htmlspecialchars($_SESSION['name']) ?>! You can view all appointments and manage users here.</p>
<ulclass="admin-links">
>
  <li><a href="http://localhost/MEDICRE%20PROJECT/Medicre/admin/manage-users.php">👥Manage Users</a></li>
 

  <li><a href="http://localhost/MEDICRE%20PROJECT/Medicre/admin/report.php">📑View Reports</a></li>
</ul>




<h3>User Management</h3>

<?php
// Load all users
$users = $pdo->query("SELECT user_id, name, role, email FROM user ORDER BY role, name")->fetchAll();
?>
<table border="1" cellpadding="6">
  <tr>
    <th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Action</th>
  </tr>
  <?php foreach($users as $u): ?>
    <tr>
      <td><?= $u['user_id'] ?></td>
      <td><?= htmlspecialchars($u['name']) ?></td>
      <td><?= htmlspecialchars($u['email']) ?></td>
      <td><?= $u['role'] ?></td>
      <td>
        <a href="delete-user.php?id=<?= $u['user_id'] ?>" 
           onclick="return confirm('Are you sure you want to delete this user?');">
             ❌Delete
        </a>
      </td>
    </tr>
  <?php endforeach; ?>
</table>

<?php include '../includes/footer.php'; ?>