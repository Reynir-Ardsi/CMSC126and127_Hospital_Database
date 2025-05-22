<?php
include 'DBConnector.php';

$sql = "SELECT p.first_name, p.middle_initial, p.last_name
        FROM patients p";

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