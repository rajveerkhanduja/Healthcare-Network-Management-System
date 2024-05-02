<?php

// Check for actions
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["action"])) {
        if ($_GET["action"] == "getDoctors") {
            // Database connection
            $servername = "localhost";
            $dbname = "hospital";
            $conn = new mysqli($servername, "root", "", $dbname);
            $sql = "SELECT * FROM Doctor";
            $result = $conn->query($sql);
            $doctors = array();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $doctors[] = $row;
                }
            }
            echo json_encode($doctors);
            $conn->close();
        }
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Add new doctor
    $data = json_decode(file_get_contents("php://input"), true);
    $doctorName = $data["doctorName"];
    $specialty = $data["specialty"];
    $phone = $data["phone"];
    $email = $data["email"];
    
    // Database connection
    $servername = "localhost";
    $dbname = "hospital";
    $conn = new mysqli($servername, "root", "", $dbname);
    $sql = "INSERT INTO Doctor (Name, Specialty, Phone, Email) VALUES ('$doctorName', '$specialty', '$phone', '$email')";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(array("message" => "Doctor added successfully"));
    } else {
        echo json_encode(array("error" => "Error: " . $sql . "<br>" . $conn->error));
    }
    $conn->close();
}

?>
