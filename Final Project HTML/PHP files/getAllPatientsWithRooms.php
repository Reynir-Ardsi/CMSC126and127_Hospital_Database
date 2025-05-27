<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'DBConnector.php';

$sql = "
    SELECT 
        p.patient_id, 
        p.first_name, 
        p.middle_initial, 
        p.last_name, 
        a.room_number 
    FROM patient p 
    LEFT JOIN appointment a ON p.patient_id = a.patient_id
";

$result = $conn->query($sql);

$patients = [];

while ($row = $result->fetch_assoc()) {
    // Concatenate full name from separate fields
    $row['full_name'] = trim($row['first_name'] . ' ' . $row['middle_initial'] . ' ' . $row['last_name']);
    $patients[] = $row;
}

header('Content-Type: application/json');
echo json_encode($patients);

$conn->close();