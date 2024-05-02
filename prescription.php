<?php

// Check for actions
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["action"])) {
        if ($_GET["action"] == "getPrescriptions") {
            // Database connection
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "hospital";
            $conn = new mysqli($servername, $username, $password, $dbname);
            $sql = "SELECT * FROM Prescription";
            $result = $conn->query($sql);
            $prescriptions = array();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $prescriptions[] = $row;
                }
            }
            echo json_encode($prescriptions);
            $conn->close();
        }
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Add new prescription
    $data = json_decode(file_get_contents("php://input"), true);
    $treatmentID = $data["treatmentID"];
    $medication = $data["medication"];
    $dosage = $data["dosage"];
    
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hospital";
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check if the treatmentID exists in the Treatment table
    $checkTreatment = "SELECT * FROM Treatment WHERE TreatmentID = $treatmentID";
    $result = $conn->query($checkTreatment);
    if ($result->num_rows > 0) {
        // Insert prescription if the treatmentID exists
        $sql = "INSERT INTO Prescription (TreatmentID, Medication, Dosage) VALUES ($treatmentID, '$medication', '$dosage')";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(array("message" => "Prescription added successfully"));
        } else {
            echo json_encode(array("error" => "Error: " . $sql . "<br>" . $conn->error));
        }
    } else {
        echo json_encode(array("error" => "Treatment ID does not exist"));
    }

    $conn->close();
}

?>
