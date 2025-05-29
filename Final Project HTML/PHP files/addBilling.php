<?php
include 'DBConnector.php';

// Get JSON input from request body
$data = json_decode(file_get_contents("php://input"), true);

// Validate JSON decoding
if (!$data) {
    echo json_encode(['success' => false, 'error' => 'Invalid JSON input']);
    exit;
}

// Validate and sanitize input
$patientId      = $data['patient_id'] ?? null;
$service        = trim($data['service'] ?? '');
$totalAmount    = $data['total_amount'] ?? null;
$paymentStatus  = trim($data['payment_status'] ?? '');
$paymentDate    = $data['payment_date'] ?? null;
$receptionistId = $data['receptionist_id'] ?? null;

// Check for required fields and correct types
if (
    !$patientId || !$service || $totalAmount === null || !is_numeric($totalAmount) ||
    !$paymentStatus || !$paymentDate || !$receptionistId
) {
    echo json_encode(['success' => false, 'error' => 'Missing or invalid required fields']);
    exit;
}

// Generate a random billing_id (e.g., a UUID or a random hex string)
function generateBillingId() {
    return bin2hex(random_bytes(8)); // 16-character hex string
}

$billingId = generateBillingId();

// Prepare and execute insert statement
try {
    $stmt = $conn->prepare("
        INSERT INTO billing 
            (billing_id, patient_id, service, total_amount, payment_status, payment_date, receptionist_id) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $billingId,
        $patientId,
        $service,
        $totalAmount,
        $paymentStatus,
        $paymentDate,
        $receptionistId
    ]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Database insert failed: ' . $e->getMessage()
    ]);
}
?>