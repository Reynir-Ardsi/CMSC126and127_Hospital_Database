<?php
include 'DBConnector.php';

$sql = "SELECT * FROM patient";

$result = $conn->query($sql);

$patients = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $patients[] = $row;
    }
    var_dump($patients);
}

header('Content-Type: application/json');
echo json_encode($patients);
$conn->close();
?>