<?php
include 'DBConnector.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data["nurseID"])) {
    $nurseID = $conn->real_escape_string($data["nurseID"]);

    $sql = "DELETE FROM users WHERE user_id = '$nurseID'";

    if ($conn->query($sql) === TRUE) {
        echo "Nurse deleted successfully.";
    } else {
        echo "Error deleting nurse: " . $conn->error;
    }

} else {
    echo "Invalid input.";
}

$conn->close();
?>