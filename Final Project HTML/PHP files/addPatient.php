<?php
    include 'DBConnector.php';

    // Generate a random unique patient_id (20-digit max int, keeping well within PHP int range)
    function generatePatientId($conn) {
        do {
            // Generate a random 10-digit number as a string to avoid integer overflow
            $id = random_int(1, 2147483647);
            $sql = "SELECT 1 FROM patient WHERE patient_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $stmt->store_result();
            $exists = $stmt->num_rows > 0;
            $stmt->close();
        } while ($exists);
        return $id;
    }


    $patientId = generatePatientId($conn);

    // Sanitize and collect POST data
    $fullName = explode(" ", trim($_POST['fullName']), 3); // Assuming fullName = "First M. Last"
    $firstName = $fullName[0] ?? '';
    $middleInitial = isset($fullName[1]) && strlen($fullName[1]) <= 5 ? $fullName[1] : '';
    $lastName = $fullName[2] ?? ($middleInitial ? '' : ($fullName[1] ?? ''));

    $dateOfBirth = $_POST['dob'];
    $age = $_POST['age'];
    $sex = strtoupper($_POST['sex'][0]); // Convert "Male"/"Female" to "M"/"F"
    $contactNumber = $_POST['phone'];
    $address = $_POST['address'];
    $medicalHistory = $_POST['history'];
    $dateAdmitted = date('Y-m-d'); // Set admission date to today
    $email = $_POST['email'];
    $emergencyContact = $_POST['emergencyContact'];
    $maritalStatus = $_POST['maritalStatus'];
    $occupation = $_POST['occupation'];
    $insurance = $_POST['insurance'];
    $allergies = $_POST['allergies'];
    $medications = $_POST['medication'];
    $lifestyle = $_POST['lifestyle'];

    // Insert into the database
    $sql = "INSERT INTO patient (
    patient_id, first_name, last_name, middle_initial, date_of_birth, sex, contact_number,
    address, medical_history, date_admitted, age, email_address, emergency_contact,
    marital_status, occupation, insurance_provider, allergies, medications, lifestyle
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
    "isssssisssisissssss",
    $patientId,
    $firstName,
    $lastName,
    $middleInitial,
    $dateOfBirth,
    $sex,
    $contactNumber,
    $address,
    $medicalHistory,
    $dateAdmitted,
    $age,
    $email,
    $emergencyContact,
    $maritalStatus,
    $occupation,
    $insurance,
    $allergies,
    $medications,
    $lifestyle
    );

    if ($stmt->execute()) {
    // Redirect to Patient Registration page after successful insertion
        echo "<script>
                alert('Patient successfully added!');
                window.location.href = '../Patient%20Registration.html';
            </script>";
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
?>