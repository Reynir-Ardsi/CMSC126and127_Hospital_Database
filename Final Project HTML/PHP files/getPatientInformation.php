<?php
include 'DBConnector.php';

// SQL query to fetch details of the patient
$sql = "SELECT * FROM patients WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patientId);
$stmt->execute();
$result = $stmt->get_result();

// Check if a patient is found
if ($result->num_rows > 0) {
    // Fetch the patient details
    $patient = $result->fetch_assoc();
    echo json_encode($patient);
} else {
    echo json_encode(["error" => "Patient not found"]);
}

// Close the connection
$stmt->close();
$conn->close();
?>