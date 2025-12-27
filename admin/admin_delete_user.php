<?php
session_start();
require_once __DIR__ . '/../includes/db-connect.php';

// ✅ Allow only admin
if (($_SESSION['role'] ?? '') !== 'admin') {
  header('Location: ../public/admin_dashboard.php?error=forbidden');
  exit;
}

$user_id = (int)($_POST['user_id'] ?? 0);
$role = trim($_POST['role'] ?? '');

if ($user_id <= 0 || ($role !== 'doctor' && $role !== 'receptionist')) {
  header('Location: manage-user.php?error=invalid');
  exit;
}

// ✅ Prevent deleting yourself (safer UX)
if ((int)($_SESSION['user_id'] ?? 0) === $user_id) {
  header('Location: manage-user.php?error=selfdelete');
  exit;
}

// 🔒 Verify user exists and matches role
$u = $pdo->prepare("SELECT user_id, role, status FROM user WHERE user_id=? LIMIT 1");
$u->execute([$user_id]);
$user = $u->fetch();

if (!$user || $user['role'] !== $role) {
  header('Location: manage-user.php?error=notfound');
  exit;
}

// --- Option A: Soft delete (recommended) ---
$upd = $pdo->prepare("UPDATE user SET status='inactive' WHERE user_id=?");
$upd->execute([$user_id]);

// Optional: also mark related records inactive (doctor schedules, leaves)
if ($role === 'doctor') {
  // Example: disable schedules
  $pdo->prepare("UPDATE doctor_schedule SET is_available=0 WHERE doctor_id=?")->execute([$user_id]);
  // You can also prevent new appointments with this doctor by checking status in your booking flow
}

// --- Option B: Hard delete (use cautiously) ---
// Uncomment if you really want to remove rows. Ensure foreign keys/cascades are set.
// $pdo->prepare("DELETE FROM user WHERE user_id=?")->execute([$user_id]);
// $pdo->prepare("DELETE FROM doctor_schedule WHERE doctor_id=?")->execute([$user_id]);
// $pdo->prepare("DELETE FROM doctor_leave WHERE doctor_id=?")->execute([$user_id]);
// $pdo->prepare("UPDATE appointments SET status='cancelled', cancel_reason='Doctor deleted' WHERE doctor_id=? AND status IN ('pending','confirmed')")->execute([$user_id]);

header('Location: manage-user.php?success=deleted');
exit;