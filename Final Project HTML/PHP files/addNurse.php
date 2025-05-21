<?php
include 'DBConnector.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nurseID = $_POST["nurseID"];
    $firstName = $_POST["firstName"];
    $middleInitial = $_POST["middleInitial"];
    $lastName = $_POST["lastName"];
    $contact = $_POST["contact"];
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Insert into users
    $sql1 = "INSERT INTO users (user_id, first_name, middle_initial, last_name, contact_number, email)
             VALUES ('$doctorID', '$firstName', '$middleInitial', '$lastName', '$contact', '$email')";

    // Insert into login
    $sql2 = "INSERT INTO login (user_id, username, password)
             VALUES ('$nurseID', '$username', '$password')";

    // Insert into job
    $sql3 = "INSERT INTO job (user_id, role)
             VALUES ('$nurseID', 'Nurse')";

    // Correct order: users → login → job
    if ($conn->query($sql1) === TRUE && $conn->query($sql2) === TRUE && $conn->query($sql3) === TRUE) {
        header("Location: /CMSC126and127_Hospital_Database/Final%20Project%20HTML/nurseList.html");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>