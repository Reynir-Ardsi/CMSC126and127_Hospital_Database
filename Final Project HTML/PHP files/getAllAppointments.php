<?php
include 'DBConnector.php';

$sql = "
    SELECT DISTINCT 
        appointment.appointment_id,
        users.first_name AS doctor_first_name,
        users.last_name AS doctor_last_name,
        patient.first_name AS patient_first_name,
        patient.middle_initial,
        patient.last_name AS patient_last_name,
        appointment.time,
        appointment.status,
        appointment.appointment_date,
        appointment.reason
    FROM appointment
    INNER JOIN patient ON appointment.patient_id = patient.patient_id
    INNER JOIN users ON appointment.doctor_id = users.user_id
";

$result = $conn->query($sql);

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