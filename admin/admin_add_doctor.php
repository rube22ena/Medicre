<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin']);
include __DIR__ . '/../includes/header.php';
?>
<h2>Add doctor</h2>
<form action="admin_save_doctor.php" method="POST" enctype="multipart/form-data" style="max-width:480px;">
  <label><strong>Name:</strong></label>
  <input type="text" name="name" required>

  <label><strong>Specialization:</strong></label>
  <input type="text" name="specialization" required>

  <label><strong>Profile image:</strong></label>
  <input type="file" name="image" accept="image/jpeg,image/png,image/webp" required>

  <button type="submit">Save doctor</button>
</form>
<?php include __DIR__ . '/../includes/footer.php'; ?>