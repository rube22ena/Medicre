<?php
session_start();
require_once __DIR__ . '/../includes/db-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_SESSION['role'] ?? '') === 'patient') {
    $patient_id = (int)($_SESSION['user_id'] ?? 0);
    $doctor_id  = (int)($_POST['doctor_id'] ?? 0);
    $dept       = trim($_POST['department'] ?? '');
    $date       = $_POST['appointment_date'] ?? '';
    $time       = $_POST['appointment_time'] ?? '';

    $name    = trim($_POST['name'] ?? '');
    $gender  = $_POST['gender'] ?? '';
    $age     = (int)($_POST['age'] ?? 0);
    $mobile  = trim($_POST['mobile'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');

   if ($patient_id > 0 && $doctor_id > 0 && $date !== '' && $time !== '') {
    // Insert appointment
    $stmt = $pdo->prepare("INSERT INTO appointments
        (patient_id, doctor_id, department, appointment_date, appointment_time, status)
        VALUES (?, ?, ?, ?, ?, 'pending')");
    $stmt->execute([$patient_id, $doctor_id, $dept, $date, $time]);
    $appointment_id = $pdo->lastInsertId();

    $detailStmt = $pdo->prepare("INSERT INTO patient_details
        (appointment_id, name, gender, age, mobile, email, address)
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    $detailStmt->execute([$appointment_id, $name, $gender, $age, $mobile, $email, $address]);

    header('Location: appointments.php?success=1&doctor_id=' . $doctor_id);
    exit;
} else {
    header('Location: appointments.php?error=1&doctor_id=' . $doctor_id);
    exit;
}
}
?>