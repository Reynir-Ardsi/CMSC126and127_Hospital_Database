
<?php
include 'DBConnector.php';

// SQL query to fetch all patients
$sql = "SELECT * FROM patient";
$result = $conn->query($sql);

$patients = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $patients[] = $row;
    }
    echo json_encode($patients);
} else {
    echo json_encode([]);
}

$conn->close();
?>