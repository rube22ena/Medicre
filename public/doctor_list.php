<?php
require_once '../includes/db-connect.php';
require_once '../includes/auth.php';
requireRole(['patient']); // only patients can view and book
include '../includes/header.php';

// Load all doctors
$doctors = $pdo->query("SELECT user_id, name, specialization, photo 
                        FROM user WHERE role='doctor' ORDER BY name ")->fetchAll();
?>

<link rel="stylesheet" href="../css/Doctor-grid.css">
<link rel="stylesheet" href="../includes/headerstyle.css">
<h2>Select Your Doctor</h2>
<div class="doctor-grid">
  <?php foreach($doctors as $d): ?>
    <div class="doctor-card">
      <?php if(!empty($d['photo'])): ?>
        <img src="../uploads/<?= htmlspecialchars($d['photo']) ?>" alt="Doctor Photo">
      <?php else: ?>
        <img src="../uploads/default.png" alt="Default Photo">
      <?php endif; ?>
      <h3><?= htmlspecialchars($d['name']) ?></h3>
      <p><?= htmlspecialchars($d['specialization']) ?></p>
      <form method="get" action="appointments.php">
        <input type="hidden" name="doctor_id" value="<?= $d['user_id'] ?>">
        <button type="submit">Select</button>
      </form>
    </div>
  <?php endforeach; ?>
</div>
<?php include '../includes/footer.php'; ?>
