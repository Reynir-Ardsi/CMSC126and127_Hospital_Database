<?php
include 'DBConnector.php'; // Your DB connection file

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Fetch and sanitize inputs
    $id = intval($_POST['patient_id']);
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $date_of_birth = $_POST['date_of_birth'] ?? null;
    $sex = $_POST['sex'] ?? '';
    $contact_number = isset($_POST['contact_number']) ? intval($_POST['contact_number']) : null;
    $address = $_POST['address'] ?? '';
    // Optional or missing fields - set to NULL if not provided
    $medical_history = null; // You can extend your form to add this if needed
    $date_admitted = $_POST['date_admitted'] ?? null;
    $age = isset($_POST['age']) ? intval($_POST['age']) : null;
    $email_address = $_POST['email_address'] ?? '';
    $emergency_contact = $_POST['emergency_contact'] ?? '';
    $marital_status = $_POST['marital_status'] ?? '';
    $occupation = $_POST['occupation'] ?? '';
    $insurance_provider = $_POST['insurance_provider'] ?? '';
    $allergies = $_POST['allergies'] ?? '';
    $middle_initial = $_POST['middle_initial'] ?? '';
    $medical_history = $_POST['medical_history'] ?? null;
    $prescriptions = $_POST['prescriptions'] ?? null;
    $lifestyle = $_POST['lifestyle'] ?? null;


    // Prepare the SQL statement matching table column order
    $sql = "UPDATE patient SET 
        first_name = ?, 
        last_name = ?, 
        date_of_birth = ?, 
        sex = ?, 
        contact_number = ?, 
        address = ?, 
        medical_history = ?, 
        date_admitted = ?, 
        age = ?, 
        email_address = ?, 
        emergency_contact = ?, 
        marital_status = ?, 
        occupation = ?, 
        insurance_provider = ?, 
        allergies = ?, 
        prescriptions = ?, 
        lifestyle = ?, 
        middle_initial = ? 
    WHERE patient_id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param(
            "ssssisssisssssssssi",
            $first_name,
            $last_name,
            $date_of_birth,
            $sex,
            $contact_number,
            $address,
            $medical_history,
            $date_admitted,
            $age,
            $email_address,
            $emergency_contact,
            $marital_status,
            $occupation,
            $insurance_provider,
            $allergies,
            $prescriptions,
            $lifestyle,
            $middle_initial,
            $id
        );

        if ($stmt->execute()) {
            // Success: redirect or show a message
            header("Location: ../View Patients(Nurse Dashboard).html");
            exit();
        } else {
            echo "Error updating patient: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Prepare failed: " . $conn->error;
    }

    $conn->close();
}
?>