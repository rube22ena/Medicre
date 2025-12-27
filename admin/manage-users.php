<?php
require_once '../includes/db-connect.php';  
require_once '../includes/auth.php';  
requireRole('admin');  
include '../includes/header.php';

// ✅ Handle user creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_user'])) {
    $name           = trim($_POST['name'] ?? '');
    $email          = strtolower(trim($_POST['email'] ?? ''));
    $pass           = $_POST['password'] ?? '';
    $role           = $_POST['role'] ?? '';
    $specialization = $_POST['specialization'] ?? '';
    $photoPath      = null;

    if ($name && filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($pass) >= 6 
        && in_array($role, ['admin','doctor','receptionist'])) {

        // Doctors must have specialization
        if ($role === 'doctor' && !$specialization) {
            $error = "Doctor must have a specialization";
        } else {
            // ✅ Handle photo upload only for doctors
            if ($role === 'doctor' && isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
             $targetDir = "../uploads/";
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                $photoFile = time() . "_" . basename($_FILES['photo']['name']);
                move_uploaded_file($_FILES['photo']['tmp_name'], $targetDir . $photoFile);
                $photoPath = $photoFile;
            }

            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO user (name,email,password_hash,role,specialization,photo) VALUES (?,?,?,?,?,?)");
            try {
                $stmt->execute([$name,$email,$hash,$role,$specialization,$photoPath]);
                $msg = "User created successfully!";
            } catch (PDOException $e) {
                $error = "Email already exists";
            }
        }
    } else {  
        $error = "Invalid input";  
    }
}

// ✅ Handle user deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = (int)$_POST['delete_id'];
    if ($delete_id > 0) {
        $stmt = $pdo->prepare("DELETE FROM user WHERE user_id=?");
        $stmt->execute([$delete_id]);
        $msg = "User deleted successfully!";
    }
}

// Load all staff (excluding patients)
$staff = $pdo->query("SELECT user_id, name, email, role, specialization, photo 
                      FROM user 
                      WHERE role!='patient' 
                      ORDER BY role, name")->fetchAll();
?>
<link rel="stylesheet" href="../css/manage-user.css">
<link rel="stylesheet" href="../includes/headerstyle.css">

<h2>Manage Staff Users</h2>

<form method="post" enctype="multipart/form-data">
  <input type="hidden" name="create_user" value="1">

  <label>Name</label><br>
  <input name="name" required><br>

  <label>Email</label><br>
  <input type="email" name="email" required><br>

  <label>Password</label><br>
  <input type="password" name="password" required><br>

  <label>Role</label><br>
  <select name="role" id="roleSelect" required onchange="toggleSpecializationAndPhoto()">
    <option value="">Select</option>
    <option value="admin">Admin</option>
    <option value="doctor">Doctor</option>
    <option value="receptionist">Receptionist</option>
  </select><br><br>

  <!-- Specialization field only for doctors -->
  <div id="specializationField" style="display:none;">
    <label>Specialization</label><br>
    <select name="specialization" id="specializationSelect">
      <option value="">Select Specialization</option>
      <option value="Cardiology">Cardiology</option>
      <option value="Neurology">Neurology</option>
      <option value="Orthopedics">Orthopedics</option>
      <option value="General">General</option>
    </select><br><br>
  </div>

  <!-- Photo upload only for doctors -->
  <div id="photoField" style="display:none;">
    <label>Photo</label><br>
    <input type="file" name="photo" accept="image/*"><br><br>
  </div>

  <button type="submit">Create User</button>
</form>

<script>
function toggleSpecializationAndPhoto() {
  const role = document.getElementById('roleSelect').value;
  const specField = document.getElementById('specializationField');
  const specSelect = document.getElementById('specializationSelect');
  const photoField = document.getElementById('photoField');

  if (role === 'doctor') {
    specField.style.display = 'block';
    photoField.style.display = 'block';
    specSelect.setAttribute('required','required');
  } else {
    specField.style.display = 'none';
    photoField.style.display = 'none';
    specSelect.removeAttribute('required');
    specSelect.value = '';
  }
}
</script>

<?php if(isset($msg))   echo "<p style='color:green;'>$msg</p>"; ?>
<?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

<h3>Existing Staff</h3>
<table border="1" cellpadding="6">
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Role</th>
    <th>Specialization</th>
    <th>Photo</th>
    <th>Actions</th>
  </tr>
  <?php foreach($staff as $s): ?>
    <tr>
      <td><?= $s['user_id'] ?></td>
      <td><?= htmlspecialchars($s['name']) ?></td>
      <td><?= htmlspecialchars($s['email']) ?></td>
      <td><?= $s['role'] ?></td>
      <td><?= htmlspecialchars($s['specialization']) ?></td>
      <td>
        <?php if($s['photo']): ?>
          <img src="../uploads/<?= htmlspecialchars($s['photo']) ?>" 
               alt="Doctor Photo" style="width:80px;height:80px;border-radius:50%;">
        <?php else: ?>
          
        <?php endif; ?>
      </td>
      <td>
        <!-- Delete button posts back to this same page -->
        <form method="post" style="display:inline;" 
              onsubmit="return confirm('Delete this user?');">
          <input type="hidden" name="delete_id" value="<?= $s['user_id'] ?>">
          <button type="submit" style="background:#c62828;color:#fff;border:0;padding:5px 10px;border-radius:4px;">
            Delete
          </button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
</table>

<?php include '../includes/footer.php'; ?>