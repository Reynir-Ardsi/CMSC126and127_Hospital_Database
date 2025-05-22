<?php
include 'DBConnector.php';

$sql = "SELECT u.user_id, u.first_name, u.middle_initial, u.last_name, u.contact_number, u.email, l.username, l.password
        FROM users u
        JOIN login l ON u.user_id = l.user_id
        JOIN job j ON u.user_id = j.user_id
        WHERE j.role = 'Receptionist'";

$result = $conn->query($sql);

$receptionist = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $receptionist[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($receptionist);
$conn->close();
?>