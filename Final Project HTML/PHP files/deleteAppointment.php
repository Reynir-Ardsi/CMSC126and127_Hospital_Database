<?php
include 'DBConnector.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(['error' => 'Invalid request method']);
  exit;
}

if (!isset($_POST['appointmentId'])) {
  echo json_encode(['error' => 'Missing appointmentId']);
  exit;
}

$appointmentId = intval($_POST['appointmentId']);

$stmt = $conn->prepare("DELETE FROM appointment WHERE appointment_id = ?");
$stmt->bind_param('i', $appointmentId);

if ($stmt->execute()) {
  echo json_encode(['success' => 'Appointment deleted']);
} else {
  echo json_encode(['error' => 'Failed to delete appointment']);
}

$stmt->close();
$conn->close();
?>