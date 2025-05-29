<?php
include 'DBConnector.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data["receptionistID"])) {
    $receptionistID = $conn->real_escape_string($data["receptionistID"]);

    $sql = "DELETE FROM users WHERE user_id = '$receptionistID'";

    if ($conn->query($sql) === TRUE) {
        echo "Receptionist deleted successfully.";
    } else {
        echo "Error deleting receptionist: " . $conn->error;
    }

} else {
    echo "Invalid input.";
}

$conn->close();
?>