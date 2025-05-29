<?php
include 'DBConnector.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(['error' => 'Invalid request method']);
  exit;
}

$appointmentId = isset($_POST['appointmentId']) ? intval($_POST['appointmentId']) : 0;
$appointmentDate = $_POST['appointmentDate'] ?? null;
$appointmentTime = $_POST['appointmentTime'] ?? null;
$reason = $_POST['reason'] ?? null;

$sql = "UPDATE appointment SET appointment_date = ?, time = ?, reason = ? WHERE appointment_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $appointmentDate, $appointmentTime, $reason, $appointmentId);

if ($stmt->execute()) {
  echo json_encode(['success' => true]);
} else {
  echo json_encode(['error' => 'Failed to update appointment']);
}

$stmt->close();
$conn->close();
?>