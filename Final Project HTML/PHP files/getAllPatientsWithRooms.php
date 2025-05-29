<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'DBConnector.php';

$sql = "
    SELECT 
        patient_id, 
        first_name, 
        middle_initial, 
        last_name, 
        room_number 
    FROM patient
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