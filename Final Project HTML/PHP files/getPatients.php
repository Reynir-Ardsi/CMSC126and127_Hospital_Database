<?php
header('Content-Type: application/json');
include 'DBConnector.php';

// Fetch all patients
$sql = "
  SELECT *
    FROM patient
";
$result = $conn->query($sql);

$patients = [];
while ($row = $result->fetch_assoc()) {
    $patients[] = [
        'id'   => $row['patient_id'],
        'name' => trim($row['first_name'] . ' ' . $row['middle_initial'] . ' ' . $row['last_name'])
    ];
}

echo json_encode($patients);
$conn->close();
