<?php
include 'DBConnector.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data["doctorID"])) {
    $doctorID = $conn->real_escape_string($data["doctorID"]);

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