<?php
require_once '../includes/db-connect.php';
require_once '../includes/auth.php';
include '../includes/header.php';

// Get specialization from URL (default = all)
$specialization = $_GET['specialization'] ?? 'all';

// Always load all doctors first
if ($specialization === 'all') {
    $stmt = $pdo->prepare("SELECT user_id, name, specialization, photo 
                           FROM user WHERE role='doctor' ORDER BY name");
    $stmt->execute();
} else {
    $stmt = $pdo->prepare("SELECT user_id, name, specialization, photo 
                           FROM user WHERE role='doctor' AND specialization = ? ORDER BY name");
    $stmt->execute([$specialization]);
}

$doctors = $stmt->fetchAll();
?>

<h2>Our Doctors & Specialists</h2>

<!-- Filter menu -->
<div class="specialization-filter">
  <a href="doctor_list.php?specialization=all" class="<?= ($specialization==='all')?'active':'' ?>">All</a>
  <a href="doctor_list.php?specialization=Cardiology" class="<?= ($specialization==='Cardiology')?'active':'' ?>">Cardiology</a>
  <a href="doctor_list.php?specialization=Neurology" class="<?= ($specialization==='Neurology')?'active':'' ?>">Neurology</a>
  <a href="doctor_list.php?specialization=Orthopedics" class="<?= ($specialization==='Orthopedics')?'active':'' ?>">Orthopedics</a>
  <a href="doctor_list.php?specialization=General" class="<?= ($specialization==='General')?'active':'' ?>">General</a>
  <a href="doctor_list.php?specialization=Dermatology" class="<?= ($specialization==='Dermatology')?'active':'' ?>">Dermatology</a>
  <a href="doctor_list.php?specialization=Psychiatry" class="<?= ($specialization==='Psychiatry')?'active':'' ?>">Psychiatry</a>
  <a href="doctor_list.php?specialization=Dentist" class="<?= ($specialization==='Dentist')?'active':'' ?>">Dentist</a>
  <a href="doctor_list.php?specialization=Ophthalmology" class="<?= ($specialization==='Ophthalmology')?'active':'' ?>">Ophthalmology</a>
  <a href="doctor_list.php?specialization=Laboratory" class="<?= ($specialization==='Laboratory')?'active':'' ?>">Laboratory</a>
</div>

<link rel="stylesheet" href="../css/Doctor-grid.css">
<link rel="stylesheet" href="../includes/headerstyle.css">

<!-- Doctor profiles -->
<div class="doctor-grid">
  <?php if (empty($doctors)): ?>
    <p>No doctors found for this specialization.</p>
  <?php else: ?>
    <?php foreach($doctors as $d): ?>
      <div class="doctor-card">
        <img src="../uploads/<?= htmlspecialchars($d['photo'] ?? 'default.png') ?>" alt="Doctor Photo">
        <h3><?= htmlspecialchars($d['name']) ?></h3>
        <p><?= htmlspecialchars($d['specialization']) ?></p>
        <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'patient'): ?>
          <form method="get" action="appointments.php">
            <input type="hidden" name="doctor_id" value="<?= $d['user_id'] ?>">
            <button type="submit">Book Appointment</button>
          </form>
        <?php else: ?>
          <p><a href="login.php">Login</a> to book an appointment</p>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>