<?php
include 'DBConnector.php';

$sql = "SELECT u.user_id, u.first_name, u.middle_initial, u.last_name, u.specialty, u.contact_number, u.email_address, l.username
        FROM users u
        JOIN login l ON u.user_id = l.user_id
        JOIN job j ON u.user_id = j.user_id
        WHERE j.role = 'Doctor'";

$result = $conn->query($sql);

$doctors = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $doctors[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($doctors);
$conn->close();
?>