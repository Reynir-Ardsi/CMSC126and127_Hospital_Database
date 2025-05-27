<?php
include 'DBConnector.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo "Invalid input";
    exit;
}

$doctorID = $data['doctorID'];
$firstName = $data['firstName'];
$middleInitial = $data['middleInitial'];
$lastName = $data['lastName'];
$specialty = $data['specialty'];
$contact = $data['contact'];
$email = $data['email'];
$username = $data['username'];
$password = $data['password'];
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$checkStmt = $conn->prepare("SELECT user_id FROM users WHERE user_id = ?");
$checkStmt->bind_param("s", $doctorID);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    echo "A doctor with this ID already exists.";
    $checkStmt->close();
    $conn->close();
    exit;
}
$checkStmt->close();

$stmt1 = $conn->prepare("INSERT INTO users (user_id, first_name, middle_initial, last_name, specialty, contact_number, email) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt1->bind_param("sssssss", $doctorID, $firstName, $middleInitial, $lastName, $specialty, $contact, $email);

$stmt2 = $conn->prepare("INSERT INTO login (user_id, username, password) VALUES (?, ?, ?)");
$stmt2->bind_param("sss", $doctorID, $username, $hashedPassword);

$stmt3 = $conn->prepare("INSERT INTO job (user_id, role) VALUES (?, 'Doctor')");
$stmt3->bind_param("s", $doctorID);

if ($stmt1->execute() && $stmt2->execute() && $stmt3->execute()) {
    echo "Doctor added successfully!";
} else {
    echo "Error adding doctor: " . $conn->error;
}

$stmt1->close();
$stmt2->close();
$stmt3->close();
$conn->close();
?>