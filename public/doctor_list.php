<?php
require_once '../includes/db-connect.php';
require_once '../includes/auth.php';
requireRole(['patient']); // only patients can view and book
include '../includes/header.php';

// Load six doctors
$doctors = $pdo->query("SELECT user_id, name, specialization, photo 
                        FROM user WHERE role='doctor' ORDER BY name LIMIT 6")->fetchAll();
?>

<link rel="stylesheet" href="../css/doctor-grid.css">

<h2>Select Your Doctor</h2>
<div style="display:flex; flex-wrap:wrap; gap:20px;">
  <?php foreach($doctors as $d): ?>
    <div style="border:1px solid #ccc; padding:10px; width:200px; text-align:center;">
      <?php if(!empty($d['photo'])): ?>
        <img src="../uploads/<?= htmlspecialchars($d['photo']) ?>" alt="Doctor Photo" width="120" height="120" style="object-fit:cover;">
      <?php else: ?>
        <img src="../uploads/default.png" alt="Default Photo" width="120" height="120" style="object-fit:cover;">
      <?php endif; ?>
      <h3><?= htmlspecialchars($d['name']) ?></h3>
      <p><?= htmlspecialchars($d['specialization']) ?></p>
      <form method="post" action="appointment.php">
        <input type="hidden" name="doctor_id" value="<?= $d['user_id'] ?>">
        <button type="submit">Select</button>
      </form>
    </div>
  <?php endforeach; ?>
</div>

<?php include '../includes/footer.php'; ?>