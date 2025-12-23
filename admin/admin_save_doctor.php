<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin']);
require_once __DIR__ . '/../includes/db-connect.php';

$name = trim($_POST['name'] ?? '');
$specialization = trim($_POST['specialization'] ?? '');
$errors = [];
if ($name === '') $errors[] = 'Name is required.';
if ($specialization === '') $errors[] = 'Specialization is required.';

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    $errors[] = 'Image upload failed.';
} else {
    $allowed_types = ['image/jpeg','image/png','image/webp'];
    if (!in_array($_FILES['image']['type'], $allowed_types)) {
        $errors[] = 'Only JPEG, PNG, or WEBP images are allowed.';
    }
    if ($_FILES['image']['size'] > 2 * 1024 * 1024) {
        $errors[] = 'Image must be under 2MB.';
    }
}
if ($errors) { echo implode('<br>', array_map('htmlspecialchars', $errors)); exit; }

$ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
$filename = 'doctor_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;

$uploadDir = __DIR__ . '/uploads/doctor_images/';
if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
$destPath = $uploadDir . $filename;
if (!move_uploaded_file($_FILES['image']['tmp_name'], $destPath)) {
    die('Failed to save image file.');
}

$stmt = mysqli_prepare($conn, "INSERT INTO user (name, role, specialization, image) VALUES (?, 'doctor', ?, ?)");
mysqli_stmt_bind_param($stmt, 'sss', $name, $specialization, $filename);
if (!mysqli_stmt_execute($stmt)) {
    @unlink($destPath);
    die('Failed to save doctor: ' . mysqli_error($conn));
}

header('Location: doctor_list.php?added=1');
exit;