<?php
include 'DBConnector.php';

$userName = $_POST["userName"];
$password = $_POST["password"];
$response = [];

$authenticateUsername = "SELECT user_id FROM login WHERE username = ?";
$stmt = $conn->prepare($authenticateUsername);
$stmt->bind_param("s", $userName);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_id = $row['user_id'];

    $getPassword = "SELECT password FROM login WHERE user_id = ?";
    $stmt = $conn->prepare($getPassword);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $passwordResult = $stmt->get_result();

    if ($passwordResult->num_rows > 0) {
        $passRow = $passwordResult->fetch_assoc();
        $hashedPassword = $passRow['password'];

        if (password_verify($password, $hashedPassword)) {
            $findJob = "SELECT role FROM job WHERE user_id = ?";
            $stmt = $conn->prepare($findJob);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $job = $stmt->get_result();
            $jobRow = $job->fetch_assoc();

            $response = [
                "status" => "success",
                "user_id" => $user_id,
                "role" => $jobRow['role']
            ];
        } else {
            $response = [
                "status" => "error",
                "message" => "Invalid Password"
            ];
        }
    } else {
        $response = [
            "status" => "error",
            "message" => "Password not found"
        ];
    }
} else {
    $response = [
        "status" => "error",
        "message" => "Invalid Username"
    ];
}

header('Content-Type: application/json');
echo json_encode($response);
$conn->close();
?>
