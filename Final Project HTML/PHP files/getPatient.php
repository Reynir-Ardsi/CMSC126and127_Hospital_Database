<?php
header('Content-Type: application/json');
include 'DBConnector.php';

// Fetch all patients
$sql = "
  SELECT 
  appointment.*, 
  patient.patient_id,
  patient.first_name AS patient_first,
  patient.middle_initial AS patient_middle,
  patient.last_name AS patient_last,
  users.user_id AS doctor_id,
  users.first_name AS doctor_first, 
  users.middle_initial AS doctor_middle, 
  users.last_name AS doctor_last
FROM patient
INNER JOIN appointment ON appointment.patient_id = patient.patient_id
INNER JOIN users ON users.user_id = appointment.doctor_id
";
$result = $conn->query($sql);

$patients = [];
while ($row = $result->fetch_assoc()) {
    $patients[] = [
        'appointment_id' => $row['appointment_id'],
        'id'          => $row['patient_id'],
        'name'        => trim($row['patient_first'] . ' ' . $row['patient_middle'] . ' ' . $row['patient_last']),
        'doctor_name' => trim($row['doctor_first'] . ' ' . $row['doctor_middle'] . ' ' . $row['doctor_last']),
        'doctor_id'   => $row['doctor_id']
    ];
}

echo json_encode($patients);
$conn->close();