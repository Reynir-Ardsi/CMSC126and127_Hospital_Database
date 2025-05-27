<?php
include 'DBConnector.php';

// Generate a unique patient ID
function generatePatientId($conn) {
    do {
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
$firstName       = $_POST['firstName'] ?? '';
$middleInitial   = $_POST['middleInitial'] ?? '';
$lastName        = $_POST['lastName'] ?? '';
$dateOfBirth     = $_POST['dob'] ?? '';
$age             = $_POST['age'] ?? 0;
$sex             = isset($_POST['sex']) ? strtoupper($_POST['sex'][0]) : '';
$contactNumber   = $_POST['phone'] ?? '';
$address         = $_POST['address'] ?? '';
$medicalHistory  = $_POST['history'] ?? '';
$dateAdmitted    = date('Y-m-d');
$email           = $_POST['email'] ?? '';
$emergencyContact= $_POST['emergencyContact'] ?? '';
$maritalStatus   = $_POST['maritalStatus'] ?? '';
$occupation      = $_POST['occupation'] ?? '';
$insurance       = $_POST['insurance'] ?? '';
$allergies       = $_POST['allergies'] ?? '';
$medications     = $_POST['medication'] ?? '';
$lifestyle       = $_POST['lifestyle'] ?? '';

// Prepare SQL Insert
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
    echo "<script>
            alert('Patient successfully added!');
            window.location.href = '../Patient Registration.html';
          </script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>