<?php
session_start();
require_once __DIR__ . '/../includes/db-connect.php';
require_once __DIR__ . '/../includes/auth.php';
requireRole(['patient']); // only patients can cancel

$appointment_id = (int)($_POST['appointment_id'] ?? 0);
$patient_id = (int)($_SESSION['user_id'] ?? 0);

if ($appointment_id > 0 && $patient_id > 0) {
    $stmt = $pdo->prepare("UPDATE appointments 
                           SET status = 'cancelled' 
                           WHERE appointment_id = ? AND patient_id = ?");
    $stmt->execute([$appointment_id, $patient_id]);
}
?>