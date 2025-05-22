<?php
include 'DBConnector.php';

// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Get the POST JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data["doctorID"])) {
    $doctorID = $conn->real_escape_string($data["doctorID"]);

    // Delete from job table — assuming cascading deletes will handle users and login
    $sql = "DELETE FROM users WHERE user_id = '$doctorID'";

    if ($conn->query($sql) === TRUE) {
        echo "Doctor deleted successfully.";
    } else {
        echo "Error deleting doctor: " . $conn->error;
    }

} else {
    echo "Invalid input.";
}

$conn->close();
?>