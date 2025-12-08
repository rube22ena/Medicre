<?php
session_start();
require_once '../includes/db-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['role'] === 'patient') {
    $patient_id = $_SESSION['user_id'];
    $doctor_id  = $_POST['doctor_id'];
    $date       = $_POST['appointment_date'];
    $time       = $_POST['appointment_time'];
    $dept       = $_POST['department'];

    $stmt = $pdo->prepare("INSERT INTO appointments (patient_id, doctor_id, department, appointment_date, appointment_time, status)
                           VALUES (?, ?, ?, ?, ?, 'pending')");
    $stmt->execute([$patient_id, $doctor_id, $dept, $date, $time]);

    header('Location: appointment.php?success=1');
    exit;
}