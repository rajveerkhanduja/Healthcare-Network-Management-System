<?php

// Check for actions
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["action"])) {
        if ($_GET["action"] == "getPatients") {
            // Database connection
            $servername = "localhost";
            $dbname = "hospital";
            $conn = new mysqli($servername, "root", "", $dbname);
            $sql = "SELECT * FROM Patient";
            $result = $conn->query($sql);
            $patients = array();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $patients[] = $row;
                }
            }
            echo json_encode($patients);
            $conn->close();
        }
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Add new patient
    $data = json_decode(file_get_contents("php://input"), true);
    $patientName = $data["patientName"];
    $dob = $data["dob"];
    $gender = $data["gender"];
    $address = $data["address"];
    $phone = $data["phone"];
    
    // Database connection
    $servername = "localhost";
    $dbname = "hospital";
    $conn = new mysqli($servername, "root", "", $dbname);
    $sql = "INSERT INTO Patient (Name, DOB, Gender, Address, Phone) VALUES ('$patientName', '$dob', '$gender', '$address', '$phone')";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(array("message" => "Patient added successfully"));
    } else {
        echo json_encode(array("error" => "Error: " . $sql . "<br>" . $conn->error));
    }
    $conn->close();
}

?>
