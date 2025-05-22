<?php
include 'DBConnector.php';

// Enable error reporting (optional during development)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Decode JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (
    isset($data["receptionistID"], $data["firstName"], $data["middleInitial"], $data["lastName"], $data["contact"], $data["email"], $data["username"], $data["password"])
) {
    $id = $conn->real_escape_string($data["receptionistID"]);
    $first = $conn->real_escape_string($data["firstName"]);
    $mi = $conn->real_escape_string($data["middleInitial"]);
    $last = $conn->real_escape_string($data["lastName"]);
    $contact = $conn->real_escape_string($data["contact"]);
    $email = $conn->real_escape_string($data["email"]);
    $username = $conn->real_escape_string($data["username"]);
    $password = $conn->real_escape_string($data["password"]);

    // Update users
    $sql_users = "UPDATE users SET
        first_name = '$first',
        middle_initial = '$mi',
        last_name = '$last',
        contact_number = '$contact',
        email = '$email'
        WHERE user_id = '$id'";

    // Update login
    $sql_login = "UPDATE login SET
        username = '$username',
        password = '$password'
        WHERE user_id = '$id'";

    if (
        $conn->query($sql_users) === TRUE &&
        $conn->query($sql_login) === TRUE
    ) {
        echo "Receptionist updated successfully.";
    } else {
        echo "Error updating receptionist: " . $conn->error;
    }

} else {
    echo "Invalid input.";
}

$conn->close();
?>
