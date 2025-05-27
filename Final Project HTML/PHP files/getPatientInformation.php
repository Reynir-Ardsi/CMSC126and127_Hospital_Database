<?php
include 'DBConnector.php';

// Get the patient ID from the URL query parameter
$patientID = $_GET['id'];  // This retrieves the 'id' from the URL

// SQL query to fetch details of the patient along with their room number from appointment
$sql = "
    SELECT p.*, a.room_number 
    FROM patient p 
    LEFT JOIN appointment a ON p.patient_id = a.patient_id 
    WHERE p.patient_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patientID);
$stmt->execute();
$result = $stmt->get_result();

// Check if a patient is found
if ($result->num_rows > 0) {
    // Fetch the patient details including room_number
    $patient = $result->fetch_assoc();
    echo json_encode($patient);
} else {
    echo json_encode(["error" => "Patient not found"]);
}

// Close the connection
$stmt->close();
$conn->close();
?>