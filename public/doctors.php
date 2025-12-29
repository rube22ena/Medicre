<?php
require_once '../includes/db-connect.php';
require_once '../includes/auth.php';
requireRole(['patient']); // only patients can view and book
include '../includes/header.php';

$doctor_id = (int)($_GET['doctor_id'] ?? 0);
$docStmt = $pdo->prepare("SELECT user_id, name, specialization, image, description 
                          FROM user 
                          WHERE user_id=? AND role='doctor'");
$docStmt->execute([$doctor_id]);
$doctor = $docStmt->fetch();

if (!$doctor) {
  echo "<p>Doctor not found.</p>";
  include '../includes/footer.php';
  exit;
}
?>

<link rel="stylesheet" href="../css/doctor-profile.css">
<link rel="stylesheet" href="../includes/headerstyle.css">
<div class="doctor-profile">
  <div class="doctor-photo">
    <img src="../uploads/doctor_images/<?= htmlspecialchars($doctor['image'] ?? 'default.png') ?>" alt="Doctor">
  </div>

  <div class="doctor-info">
    <h2><?= htmlspecialchars($doctor['name']) ?></h2>
    <p><strong>Department:</strong> <?= htmlspecialchars($doctor['specialization']) ?></p>
    <p><?= nl2br(htmlspecialchars($doctor['description'] ?? 'No description available.')) ?></p>

    <a class="appointments-btn" href="appointments.php?doctor_id=<?= (int)$doctor['user_id'] ?>">
      🗓️ Book Consultation
    </a>
  </div>
</div>
a
<?php include '../includes/footer.php'; ?>