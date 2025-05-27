<?php
include 'DBConnector.php';

// Get the patient ID from the URL query parameter
$patientID = $_GET['id'];  // This retrieves the 'id' from the URL

// SQL query to fetch details of the patient
$sql = "SELECT first_name, middle_initial, last_name, date_of_birth, age, sex, address, contact_number, email_address, emergency_contact, marital_status, occupation, insurance_provider, allergies, medical_history, lifestyle, date_admitted, prescriptions FROM patient WHERE patient_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patientID);
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