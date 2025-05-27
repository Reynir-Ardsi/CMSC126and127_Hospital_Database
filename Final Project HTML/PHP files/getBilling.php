<?php
include 'DBConnector.php';

$patientID = $_GET['id'];  // match JS parameter name

$sql = "SELECT * FROM billing WHERE patient_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patientID);
$stmt->execute();
$result = $stmt->get_result();

$billingRecords = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $billingRecords[] = $row;
    }
    echo json_encode($billingRecords);
} else {
    echo json_encode(["error" => "Billing not found"]);
}

$stmt->close();
$conn->close();
?>
