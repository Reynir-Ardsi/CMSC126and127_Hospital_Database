<?php
include 'DBConnector.php';

$userName = $_POST["userName"];
$password = $_POST["password"];
$validUsername = false;
$validPassword = false;
$user_id = null;


$authenticateUsername = "SELECT user_id FROM login WHERE username = ?";
$stmt = $conn->prepare($authenticateUsername);
$stmt->bind_param("s", $userName);
$stmt->execute();
$userNameResult = $stmt->get_result();

if ($userNameResult->num_rows > 0) {
    $validUsername = true;
    $row = $userNameResult->fetch_assoc();
    $user_id = $row['user_id'];

    $authenticatePassword = "SELECT user_id FROM login WHERE user_id = ? AND password = ?";
    $stmt = $conn->prepare($authenticatePassword);
    $stmt->bind_param("is", $user_id, $password);
    $stmt->execute();
    $passwordResult = $stmt->get_result();

    if ($passwordResult->num_rows > 0) {
        $validPassword = true;
    }
}

if ($validUsername && $validPassword) {
    $findJob = "SELECT role FROM job WHERE user_id = ?";
    $stmt = $conn->prepare($findJob);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $job = $stmt->get_result();
    $jobRow = $job->fetch_assoc();

    echo "redirect:" . $jobRow['role'];
    exit;
} elseif (!$validUsername && $validPassword) {
    echo "Invalid Username";
} elseif ($validUsername && !$validPassword) {
    echo "Invalid Password";
} else {
    echo "Invalid Username and Password";
}
?>