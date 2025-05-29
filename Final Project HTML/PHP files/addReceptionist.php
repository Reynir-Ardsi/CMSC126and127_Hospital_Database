<?php
include 'DBConnector.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo "Invalid input";
    exit;
}

$receptionistID = $data['receptionistID'];
$firstName = $data['firstName'];
$middleInitial = $data['middleInitial'];
$lastName = $data['lastName'];
$contact = $data['contact'];
$email = $data['email'];
$password = $data['password'];

// Check if receptionist already exists in users table
$checkStmt = $conn->prepare("SELECT user_id FROM users WHERE user_id = ?");
$checkStmt->bind_param("s", $receptionistID);
$checkStmt->execute();
$checkStmt->store_result();

$isExisting = $checkStmt->num_rows > 0;
$checkStmt->close();

if ($isExisting) {
    // UPDATE users info (without password)
    $stmt = $conn->prepare("UPDATE users SET first_name=?, middle_initial=?, last_name=?, contact_number=?, email=? WHERE user_id=?");
    $stmt->bind_param("ssssss", $firstName, $middleInitial, $lastName, $contact, $email, $receptionistID);
    $userUpdated = $stmt->execute();
    $stmt->close();

    // If password provided, update in login table
    if (!empty($password)) {
        // No hashing, store plain password
        // Check if user already has a login entry
        $loginCheck = $conn->prepare("SELECT user_id FROM login WHERE user_id = ?");
        $loginCheck->bind_param("s", $receptionistID);
        $loginCheck->execute();
        $loginCheck->store_result();
        $loginExists = $loginCheck->num_rows > 0;
        $loginCheck->close();

        if ($loginExists) {
            // UPDATE password in login table
            $loginUpdate = $conn->prepare("UPDATE login SET password=? WHERE user_id=?");
            $loginUpdate->bind_param("ss", $password, $receptionistID);
            $loginUpdated = $loginUpdate->execute();
            $loginUpdate->close();
        } else {
            // INSERT password in login table
            $username = $email;
            $loginInsert = $conn->prepare("INSERT INTO login (user_id, username, password) VALUES (?, ?, ?)");
            $loginInsert->bind_param("sss", $receptionistID, $username, $password);
            $loginInserted = $loginInsert->execute();
            $loginInsert->close();
        }
    }
    
    if ($userUpdated) {
        echo "Receptionist updated successfully.";
    } else {
        echo "Failed to update receptionist.";
    }

} else {
    // INSERT new receptionist (password required)
    if (empty($password)) {
        echo "Password is required for new receptionist.";
        exit;
    }

    // Insert into users table with role 'receptionist'
    $stmt = $conn->prepare("INSERT INTO users (user_id, first_name, middle_initial, last_name, contact_number, email, role) VALUES (?, ?, ?, ?, ?, ?, 'receptionist')");
    $stmt->bind_param("ssssss", $receptionistID, $firstName, $middleInitial, $lastName, $contact, $email);
    $userInserted = $stmt->execute();
    $stmt->close();

    if ($userInserted) {
        // Insert into login table
        $username = $email;

        $loginInsert = $conn->prepare("INSERT INTO login (user_id, username, password) VALUES (?, ?, ?)");
        $loginInsert->bind_param("sss", $receptionistID, $username, $password);
        $loginInserted = $loginInsert->execute();
        $loginInsert->close();

        if ($loginInserted) {
            echo "Receptionist added successfully.";
        } else {
            echo "Failed to add login info.";
        }
    } else {
        echo "Failed to add receptionist.";
    }
}

$conn->close();
?>