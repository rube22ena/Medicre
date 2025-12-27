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
 date_default_timezone_set('Asia/Kathmandu');

$time = strlen($time) === 5 ? $time . ':00' : $time; // normalize to HH:MM:SS
$selectedTs = strtotime($date . ' ' . $time);

if ($selectedTs <= time()) {
    header("Location: appointments.php?doctor_id=$doctor_id&appointment_date=$date&error=pasttime");
    exit;
}


        // 1️⃣ Check if doctor is on leave
        $leaveStmt = $pdo->prepare("SELECT 1 FROM doctor_leave WHERE doctor_id=? AND leave_date=? LIMIT 1");
        $leaveStmt->execute([$doctor_id, $date]);
        if ($leaveStmt->fetchColumn()) {
            header("Location: appointments.php?doctor_id=$doctor_id&appointment_date=$date&error=leave");
            exit;
        }

        // 2️⃣ Check if doctor has schedule for that day
        $dayOfWeek = date('l', strtotime($date));
        $schedStmt = $pdo->prepare("SELECT start_time, end_time, is_available 
                                    FROM doctor_schedule 
                                    WHERE doctor_id=? AND day_of_week=? LIMIT 1");
        $schedStmt->execute([$doctor_id, $dayOfWeek]);
        $sched = $schedStmt->fetch();
        if (!$sched || !$sched['is_available']) {
            header("Location: appointments.php?doctor_id=$doctor_id&appointment_date=$date&error=noschedule");
            exit;
        }

        // 3️⃣ Check if time is within schedule
        if ($time < $sched['start_time'] || $time >= $sched['end_time']) {
            header("Location: appointments.php?doctor_id=$doctor_id&appointment_date=$date&error=outofhours");
            exit;
        }

        // 4️⃣ Check for double booking
        $confStmt = $pdo->prepare("SELECT COUNT(*) FROM appointments 
                                   WHERE doctor_id=? AND appointment_date=? AND appointment_time=? 
                                   AND status IN ('pending','confirmed')");
        $confStmt->execute([$doctor_id, $date, $time]);
        if ($confStmt->fetchColumn() > 0) {
            header("Location: appointments.php?doctor_id=$doctor_id&appointment_date=$date&error=duplicate");
            exit;
        }

        // 5️⃣ Insert appointment
        $stmt = $pdo->prepare("INSERT INTO appointments
            (patient_id, doctor_id, department, appointment_date, appointment_time, status, created_at)
            VALUES (?, ?, ?, ?, ?, 'pending', NOW())");
        $stmt->execute([$patient_id, $doctor_id, $dept, $date, $time]);
        $appointment_id = $pdo->lastInsertId();

        // 6️⃣ Insert patient details
        $detailStmt = $pdo->prepare("INSERT INTO patient_details
            (appointment_id, name, gender, age, mobile, email, address)
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        $detailStmt->execute([$appointment_id, $name, $gender, $age, $mobile, $email, $address]);

        header("Location: appointments.php?success=1&doctor_id=$doctor_id&appointment_date=$date");
        exit;

    } else {
        header("Location: appointments.php?error=missing&doctor_id=$doctor_id");
        exit;
    }
}
?>