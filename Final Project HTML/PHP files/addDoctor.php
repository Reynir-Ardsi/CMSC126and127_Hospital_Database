<?php
    include 'DBConnector.php';

    $doctorID = $_GET["doctorID"];
    $firstName = $_GET["firstName"];
    $lastName = $_GET["lastName"];
    $specialty = $_GET["specialty"];
    $contact = $_GET["contact"];
    $email = $_GET["email"];

    $sql = "INSERT INTO `employee` (`EmpID`, `EmpName`, `Age`, `Salary`, `HireDate`)
            VALUES (NULL, '$name', '$age', '$salary', '$HireDate')";

    if ($conn->query($sql) === TRUE) {
        $last_id = $conn->insert_id;

        $query = "INSERT INTO `work` (`EmpID`, `DeptID`, `Percent_Time`)
                VALUES ('$last_id', '$DeptID', '$Percent_Time')";
        $conn->query($query);

        if ($designation == "1") {
            $mgrUpdate = "UPDATE department SET MgrEmpID = '$last_id' WHERE DeptID = '$DeptID'";
            $conn->query($mgrUpdate);
        }

        header("Location: employees.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    >close();
?>