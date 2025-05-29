<?php
include 'DBConnector.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo "Invalid input";
    exit;
}

$nurseID = $data['nurseID'];
$firstName = $data['firstName'];
$middleInitial = $data['middleInitial'];
$lastName = $data['lastName'];
$contact = $data['contact'];
$email = $data['email'];
$username = $data['username'];
$password = $data['password'];
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$checkStmt = $conn->prepare("SELECT user_id FROM users WHERE user_id = ?");
$checkStmt->bind_param("s", $nurseID);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    echo "A nurse with this ID already exists.";
    $checkStmt->close();
    $conn->close();
    exit;
}
$checkStmt->close();

$stmt1 = $conn->prepare("INSERT INTO users (user_id, first_name, middle_initial, last_name, contact_number, email) VALUES (?, ?, ?, ?, ?, ?)");
$stmt1->bind_param("ssssss", $nurseID, $firstName, $middleInitial, $lastName, $contact, $email);

$stmt2 = $conn->prepare("INSERT INTO login (user_id, username, password) VALUES (?, ?, ?)");
$stmt2->bind_param("sss", $nurseID, $username, $hashedPassword);

$stmt3 = $conn->prepare("INSERT INTO job (user_id, role) VALUES (?, 'Nurse')");
$stmt3->bind_param("s", $nurseID);

if ($stmt1->execute() && $stmt2->execute() && $stmt3->execute()) {
    echo "Nurse added successfully!";
} else {
    echo "Error adding nurse: " . $conn->error;
}

$stmt1->close();
$stmt2->close();
$stmt3->close();
$conn->close();
?>
