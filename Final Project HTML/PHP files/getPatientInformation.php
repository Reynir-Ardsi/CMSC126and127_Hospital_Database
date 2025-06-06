<?php
include 'DBConnector.php';

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'No ID provided']);
    exit;
}

$patientId = intval($_GET['id']);  // Sanitize input
$sql = "SELECT * FROM patient 
        INNER JOIN medical_record on medical_record.patient_id = patient.patient_id
        WHERE patient.patient_id = $patientId

";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $patient = $result->fetch_assoc();
    echo json_encode($patient);
} else {
    echo json_encode(['error' => 'Patient not found']);
}

$conn->close();
?>