<?php
include 'DBConnector.php';

// Get the billing ID from the URL query parameter
$patientID = $_GET['patientID'];  // This retrieves the 'id' from the URL

// SQL query to fetch details of the patient
$sql = "SELECT total_amount, payment_status, payment_date FROM billing WHERE patient_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $billingID);
$stmt->execute();
$result = $stmt->get_result();

// Check if a patient is found
if ($result->num_rows > 0) {
    // Fetch the patient details
    $bill = $result->fetch_assoc();
    echo json_encode($bill);
} else {
    echo json_encode(["error" => "Billing not found"]);
}

// Close the connection
$stmt->close();
$conn->close();
?>