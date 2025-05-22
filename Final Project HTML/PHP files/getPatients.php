<?php
include 'DBConnector.php';

$sql = "SELECT first_name, middle_initial, last_name
        FROM patient";

$result = $conn->query($sql);

$patients = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $patients[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($patients);
$conn->close();
?>