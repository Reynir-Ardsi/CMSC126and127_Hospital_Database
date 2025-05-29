<?php
include 'DBConnector.php'; // Adjust path as needed

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'];
$service = $data['service'];
$total_amount = $data['total_amount'];
$payment_status = $data['payment_status'];
$payment_date = $data['payment_date'];

// Update statement — only columns that exist in billing table
$sql = "UPDATE billing 
        SET service = ?, 
            total_amount = ?, 
            payment_status = ?, 
            payment_date = ? 
        WHERE patient_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sdssi", $service, $total_amount, $payment_status, $payment_date, $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$conn->close();
?>