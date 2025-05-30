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
    $date_admitted = $_POST['date_admitted'] ?? null;
    $age = isset($_POST['age']) ? intval($_POST['age']) : null;
    $email_address = $_POST['email_address'] ?? '';
    $emergency_contact = $_POST['emergency_contact'] ?? '';
    $marital_status = $_POST['marital_status'] ?? '';
    $occupation = $_POST['occupation'] ?? '';
    $middle_initial = $_POST['middle_initial'] ?? '';

    // Fields for medical_record table
    $medical_history = $_POST['medical_history'] ?? null;
    $insurance_provider = $_POST['insurance_provider'] ?? '';
    $allergies = $_POST['allergies'] ?? '';
    $prescriptions = $_POST['prescriptions'] ?? null;
    $lifestyle = $_POST['lifestyle'] ?? null;

    // Update patient table
    $sql1 = "UPDATE patient SET 
        first_name = ?, 
        last_name = ?, 
        date_of_birth = ?, 
        sex = ?, 
        contact_number = ?, 
        address = ?, 
        date_admitted = ?, 
        age = ?, 
        email_address = ?, 
        emergency_contact = ?, 
        marital_status = ?, 
        occupation = ?, 
        middle_initial = ? 
    WHERE patient_id = ?";

    if ($stmt = $conn->prepare($sql1)) {
        $stmt->bind_param(
            "ssssississsssi",
            $first_name,
            $last_name,
            $date_of_birth,
            $sex,
            $contact_number,
            $address,
            $date_admitted,
            $age,
            $email_address,
            $emergency_contact,
            $marital_status,
            $occupation,
            $middle_initial,
            $id
        );

        if ($stmt->execute()) {
            $stmt->close();

            // Update medical_record table
            $sql2 = "UPDATE medical_record SET 
                medical_history = ?, 
                insurance_provider = ?, 
                allergies = ?, 
                lifestyle = ?, 
                prescriptions = ? 
            WHERE patient_id = ?";

            if ($stmt2 = $conn->prepare($sql2)) {
                $stmt2->bind_param(
                    "sssssi",
                    $medical_history,
                    $insurance_provider,
                    $allergies,
                    $lifestyle,
                    $prescriptions,
                    $id
                );

                if (!$stmt2->execute()) {
                    echo "Error updating medical_record: " . $stmt2->error;
                }

                $stmt2->close();
            } else {
                echo "Prepare failed for medical_record update: " . $conn->error;
            }

            // Redirect after both updates
            header("Location: ../View Patients(Nurse Dashboard).html");
            exit();
        } else {
            echo "Error updating patient: " . $stmt->error;
            $stmt->close();
        }
    } else {
        echo "Prepare failed for patient update: " . $conn->error;
    }

    $conn->close();
}
?>