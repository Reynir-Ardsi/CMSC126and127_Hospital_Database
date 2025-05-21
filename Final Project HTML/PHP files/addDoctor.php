<?php
    include 'DBConnector.php';

    $doctorID = $_GET["doctorID"];
    $firstName = $_GET["firstName"];
    $lastName = $_GET["lastName"];
    $specialty = $_GET["specialty"];
    $contact = $_GET["contact"];
    $email = $_GET["email"];
    $username = $_GET["userName"];
    $password = $_GET["password"];

    $sql = "INSERT INTO `users` (`user_id`, `first_name`, `middle_initial`, `last_name`, `specialty`, `contact_number`, `email`)
            VALUES ($doctorID, '$firstName', '$lastName', '$specialty', '$contact', '$email')";

    if ($conn->query($sql) === TRUE) {
        $last_id = $conn->insert_id;

        $query = "INSERT INTO `login` (`user_id`, `username`, `password`)
                VALUES ('$doctorID', '$username', '$password')";
        $conn->query($query);

        header("Location: doctorList.html");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
?>