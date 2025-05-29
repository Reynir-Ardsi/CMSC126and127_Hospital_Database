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
$password = $data['password'];

// Check if nurse already exists in users table
$checkStmt = $conn->prepare("SELECT user_id FROM users WHERE user_id = ?");
$checkStmt->bind_param("s", $nurseID);
$checkStmt->execute();
$checkStmt->store_result();

$isExisting = $checkStmt->num_rows > 0;
$checkStmt->close();

if ($isExisting) {
    // UPDATE users info (without password)
    $stmt = $conn->prepare("UPDATE users SET first_name=?, middle_initial=?, last_name=?, contact_number=?, email=? WHERE user_id=?");
    $stmt->bind_param("ssssss", $firstName, $middleInitial, $lastName, $contact, $email, $nurseID);
    $userUpdated = $stmt->execute();
    $stmt->close();

    // If password provided, update in login table
    if (!empty($password)) {
        // No hashing, store plain password
        // Check if user already has a login entry
        $loginCheck = $conn->prepare("SELECT user_id FROM login WHERE user_id = ?");
        $loginCheck->bind_param("s", $nurseID);
        $loginCheck->execute();
        $loginCheck->store_result();
        $loginExists = $loginCheck->num_rows > 0;
        $loginCheck->close();

        if ($loginExists) {
            // UPDATE password in login table
            $loginUpdate = $conn->prepare("UPDATE login SET password=? WHERE user_id=?");
            $loginUpdate->bind_param("ss", $password, $nurseID);
            $loginUpdated = $loginUpdate->execute();
            $loginUpdate->close();
        } else {
            // INSERT password in login table
            $username = $email;
            $loginInsert = $conn->prepare("INSERT INTO login (user_id, username, password) VALUES (?, ?, ?)");
            $loginInsert->bind_param("sss", $nurseID, $username, $password);
            $loginInserted = $loginInsert->execute();
            $loginInsert->close();
        }
    }
    
    if ($userUpdated) {
        echo "Nurse updated successfully.";
    } else {
        echo "Failed to update nurse.";
    }

} else {
    // INSERT new nurse (password required)
    if (empty($password)) {
        echo "Password is required for new nurse.";
        exit;
    }

    // Insert into users table
    $stmt = $conn->prepare("INSERT INTO users (user_id, first_name, middle_initial, last_name, contact_number, email, role) VALUES (?, ?, ?, ?, ?, ?, 'nurse')");
    $stmt->bind_param("ssssss", $nurseID, $firstName, $middleInitial, $lastName, $contact, $email);
    $userInserted = $stmt->execute();
    $stmt->close();

    if ($userInserted) {
        // Insert into login table
        $username = $email;

        $loginInsert = $conn->prepare("INSERT INTO login (user_id, username, password) VALUES (?, ?, ?)");
        $loginInsert->bind_param("sss", $nurseID, $username, $password);
        $loginInserted = $loginInsert->execute();
        $loginInsert->close();

        if ($loginInserted) {
            echo "Nurse added successfully.";
        } else {
            echo "Failed to add login info.";
        }
    } else {
        echo "Failed to add nurse.";
    }
}

$conn->close();
?>