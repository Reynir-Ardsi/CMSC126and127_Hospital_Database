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
$contact = $data['contact'];
$email = $data['email'];
$username = $data['username'];
$password = $data['password'];

$sql1 = "INSERT INTO users (user_id, first_name, middle_initial, last_name, contact_number, email)
         VALUES ('$nurseID', '$firstName', '$middleInitial', '$lastName', '$contact', '$email')";

$sql2 = "INSERT INTO login (user_id, username, password)
         VALUES ('$nurseID', '$username', '$password')";

$sql3 = "INSERT INTO job (user_id, role)
         VALUES ('$nurseID', 'Doctor')";

if ($conn->query($sql1) && $conn->query($sql2) && $conn->query($sql3)) {
    echo "Doctor added successfully!";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>