<?php

// Check for actions
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["action"])) {
        if ($_GET["action"] == "getTreatments") {
            // Database connection
            $servername = "localhost";
            $dbname = "hospital";
            $conn = new mysqli($servername, "root", "", $dbname);
            $sql = "SELECT * FROM Treatment";
            $result = $conn->query($sql);
            $treatments = array();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $treatments[] = $row;
                }
            }
            echo json_encode($treatments);
            $conn->close();
        }
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Add new treatment
    $data = json_decode(file_get_contents("php://input"), true);
    $appointmentID = $data["appointmentID"];
    $description = $data["description"];
    $date = $data["date"];
    
    // Database connection
    $servername = "localhost";
    $dbname = "hospital";
    $conn = new mysqli($servername, "root", "", $dbname);
    $sql = "INSERT INTO Treatment (AppointmentID, Description, Date) VALUES ($appointmentID, '$description', '$date')";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(array("message" => "Treatment added successfully"));
    } else {
        echo json_encode(array("error" => "Error: " . $sql . "<br>" . $conn->error));
    }
    $conn->close();
}

?>
