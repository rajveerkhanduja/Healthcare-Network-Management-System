<?php

// Check for actions
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["action"])) {
        if ($_GET["action"] == "getAppointments") {
            // Database connection
            $servername = "localhost";
            $dbname = "hospital";
            $conn = new mysqli($servername, "root", "", $dbname);
            $sql = "SELECT * FROM Appointment";
            $result = $conn->query($sql);
            $appointments = array();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $appointments[] = $row;
                }
            }
            echo json_encode($appointments);
            $conn->close();
        }
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Schedule a new appointment
    $data = json_decode(file_get_contents("php://input"), true);
    $patientID = $data["patientID"];
    $doctorID = $data["doctorID"];
    $apptDate = $data["apptDate"];
    $apptTime = $data["apptTime"];
    $purpose = $data["purpose"];
    
    // Database connection
    $servername = "localhost";
    $dbname = "hospital";
    $conn = new mysqli($servername, "root", "", $dbname);
    $sql = "INSERT INTO Appointment (PatientID, DoctorID, ApptDate, ApptTime, Purpose) VALUES ('$patientID', '$doctorID', '$apptDate', '$apptTime', '$purpose')";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(array("message" => "Appointment scheduled successfully"));
    } else {
        echo json_encode(array("error" => "Error: " . $sql . "<br>" . $conn->error));
    }
    $conn->close();
}

?>
