<?php
function getAvailableSlots(PDO $pdo, int $doctor_id, string $date, int $slotMinutes = 30): array {
    // 1) Block if on leave
    $leave = $pdo->prepare("SELECT 1 FROM doctor_leave WHERE doctor_id=? AND leave_date=? LIMIT 1");
    $leave->execute([$doctor_id, $date]);
    if ($leave->fetchColumn()) return []; // no slots on leave

    // 2) Get schedule for that weekday
    $dayOfWeek = date('l', strtotime($date)); // e.g., 'Monday'
    $stmt = $pdo->prepare("SELECT start_time, end_time, is_available
                           FROM doctor_schedule
                           WHERE doctor_id=? AND day_of_week=? LIMIT 1");
    $stmt->execute([$doctor_id, $dayOfWeek]);
    $sched = $stmt->fetch();
    if (!$sched || !$sched['is_available']) return [];

    // 3) Fetch booked slots
    $bookedStmt = $pdo->prepare("SELECT appointment_time
                                 FROM appointments
                                 WHERE doctor_id=? AND appointment_date=? AND status IN ('pending','confirmed')");
    $bookedStmt->execute([$doctor_id, $date]);
    $bookedTimes = array_map(fn($r) => $r['appointment_time'], $bookedStmt->fetchAll());

    // 4) Build slots in intervals
    $start = new DateTime($sched['start_time']);
    $end = new DateTime($sched['end_time']);
    $interval = new DateInterval('PT' . $slotMinutes . 'M');

    $slots = [];
    for ($t = clone $start; $t < $end; $t->add($interval)) {
        $slot = $t->format('H:i:s'); // match TIME column format
        if (!in_array($slot, $bookedTimes, true)) {
            // Optional: skip past times if date is today
            if ($date === date('Y-m-d')) {
                $now = new DateTime();
                if ($t <= $now) continue;
            }
            $slots[] = $slot;
        }
    }
    return $slots;
}