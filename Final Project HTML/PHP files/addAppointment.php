<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'DBConnector.php';

try {
    // 1) Read & validate inputs
    $doctorId       = isset($_POST['doctorId'])      ? (int)$_POST['doctorId']      : 0;
    $patientId      = isset($_POST['patientId'])     ? (int)$_POST['patientId']     : 0;
    $appointmentDate= $_POST['appointmentDate']      ?? '';
    $rawTime        = $_POST['appointmentTime']      ?? '';
    $time           = preg_match('/^\d{2}:\d{2}$/', $rawTime) ? "$rawTime:00" : $rawTime;
    $reason         = trim($_POST['reason']          ?? '');
    $roomNumber     = $_POST['roomNumber']          ?? '';

    if (!$doctorId || !$patientId || !$appointmentDate || !$time) {
        http_response_code(400);
        throw new Exception('Missing required fields');
    }

    // 2) Generate a unique appointment_id
    function generateAppointmentId($conn) {
        do {
            $id = random_int(1000000000, 2147483647);
            $chk = $conn->prepare("SELECT 1 FROM appointment WHERE appointment_id = ?");
            $chk->bind_param("i", $id);
            $chk->execute();
            $chk->store_result();
            $exists = $chk->num_rows > 0;
            $chk->close();
        } while ($exists);
        return $id;
    }
    $appointmentId = generateAppointmentId($conn);

    // 3) Insert into appointment table (remove room_number)
    $stmt = $conn->prepare("
        INSERT INTO appointment
          (appointment_id, patient_id, doctor_id,
           appointment_date, time, status, reason, room_number)
        VALUES (?, ?, ?, ?, ?, 1, ?, ?)
    ");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param(
        "iiisssss",
        $appointmentId,
        $patientId,
        $doctorId,
        $appointmentDate,
        $time,
        $reason,
        $roomNumber
    );

    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    echo json_encode([
        'success'        => 'Appointment booked successfully',
        'appointment_id' => $appointmentId
    ]);

    $stmt->close();
    $conn->close();

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}