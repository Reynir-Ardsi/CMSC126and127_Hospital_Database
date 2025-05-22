<?php
include 'DBConnector.php';

// Read JSON input
$input = json_decode(file_get_contents('php://input'), true);
$userId = $input['user_id'] ?? null;

if (!$userId) {
    http_response_code(400);
    echo json_encode(["error" => "Missing user_id"]);
    exit;
}

// Now use the userId in the SQL query
$sql = "SELECT patient.first_name, patient.middle_initial, patient.last_name, appointment.time, appointment.room_number, appointment.appointment_date
        FROM appointment
        JOIN patient ON appointment.patient_id = patient.patient_id
        WHERE appointment.doctor_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userId);
$stmt->execute();
$result = $stmt->get_result();

$appointments = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($appointments);
$conn->close();
?>