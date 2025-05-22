<?php
header('Content-Type: application/json');
include 'DBConnector.php';

// Fetch all users who are doctors
$sql = "
  SELECT u.user_id,
         u.first_name,
         u.middle_initial,
         u.last_name
    FROM users u
    JOIN job   j ON j.user_id = u.user_id
   WHERE j.role = 'doctor'
";
$result = $conn->query($sql);

$doctors = [];
while ($row = $result->fetch_assoc()) {
    $doctors[] = [
        'id'   => $row['user_id'],
        'name' => trim($row['first_name'] . ' ' . $row['middle_initial'] . ' ' . $row['last_name'])
    ];
}

echo json_encode($doctors);
$conn->close();