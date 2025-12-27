<?php
require_once '../includes/db-connect.php';
require_once '../includes/auth.php';
requireRole('receptionist');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['appointment_id'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE appointments SET status = ? WHERE appointment_id = ?");
    $stmt->execute([$status, $id]);

    header("Location: receptionist_dashboard.php");
    exit;
}
?>