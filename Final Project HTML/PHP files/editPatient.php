<?php
include 'db_connection.php'; // Make sure this file connects to your DB

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['patient_id'];
    $first_name = $_POST['first_name'];
    $middle_initial = $_POST['middle_initial'];
    $last_name = $_POST['last_name'];
    $date_of_birth = $_POST['date_of_birth'];
    $sex = $_POST['sex'];
    $address = $_POST['address'];
    $contact_number = $_POST['contact_number'];
    $email_address = $_POST['email_address'];
    $emergency_contact = $_POST['emergency_contact'];
    $marital_status = $_POST['marital_status'];
    $occupation = $_POST['occupation'];
    $insurance_provider = $_POST['insurance_provider'];
    $allergies = $_POST['allergies'];
    $date_admitted = $_POST['date_admitted'];

    $sql = "UPDATE patient SET 
        first_name = ?, middle_initial = ?, last_name = ?, date_of_birth = ?, 
        sex = ?, address = ?, contact_number = ?, email_address = ?, 
        emergency_contact = ?, marital_status = ?, occupation = ?, 
        insurance_provider = ?, allergies = ?, date_admitted = ?
        WHERE patient_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssssssi", 
        $first_name, $middle_initial, $last_name, $date_of_birth, 
        $sex, $address, $contact_number, $email_address, 
        $emergency_contact, $marital_status, $occupation, 
        $insurance_provider, $allergies, $date_admitted, $id
    );

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>