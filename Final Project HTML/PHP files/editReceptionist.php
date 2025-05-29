<?php
include 'DBConnector.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

$data = json_decode(file_get_contents("php://input"), true);

if (
    isset($data["receptionistID"], $data["firstName"], $data["middleInitial"], $data["lastName"],
    $data["contact"], $data["email"], $data["username"])
) {
    $id = $conn->real_escape_string($data["receptionistID"]);
    $first = $conn->real_escape_string($data["firstName"]);
    $mi = $conn->real_escape_string($data["middleInitial"]);
    $last = $conn->real_escape_string($data["lastName"]);
    $contact = $conn->real_escape_string($data["contact"]);
    $email = $conn->real_escape_string($data["email"]);
    $username = $conn->real_escape_string($data["username"]);

    $stmt_users = $conn->prepare("UPDATE users SET first_name = ?, middle_initial = ?, last_name = ?, contact_number = ?, email = ? WHERE user_id = ?");
    $stmt_users->bind_param("ssssss", $first, $mi, $last, $contact, $email, $id);

    if (isset($data["password"]) && !empty($data["password"])) {
        $password = $data["password"];
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt_login = $conn->prepare("UPDATE login SET username = ?, password = ? WHERE user_id = ?");
        $stmt_login->bind_param("sss", $username, $hashedPassword, $id);
    } else {
        $stmt_login = $conn->prepare("UPDATE login SET username = ? WHERE user_id = ?");
        $stmt_login->bind_param("ss", $username, $id);
    }

    if ($stmt_users->execute() && $stmt_login->execute()) {
        echo "Receptionist updated successfully.";
    } else {
        echo "Error updating receptionist: " . $conn->error;
    }

    $stmt_users->close();
    $stmt_login->close();

} else {
    echo "Invalid input.";
}

$conn->close();
?>
