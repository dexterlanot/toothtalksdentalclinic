<?php
include("db_config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["appointmentID"])) {
    $appointmentID = $_POST["appointmentID"];

    // Fetch appointment information based on the appointmentID
    $queryAppointmentInfo = "SELECT * FROM `patientappointmentview` WHERE `AppointmentID` = ?";
    $stmtAppointmentInfo = $db->prepare($queryAppointmentInfo);
    $stmtAppointmentInfo->bind_param("i", $appointmentID);

    if ($stmtAppointmentInfo->execute()) {
        $resultAppointmentInfo = $stmtAppointmentInfo->get_result();
        $appointmentInfo = $resultAppointmentInfo->fetch_assoc();

        // Output appointment information as JSON
        header('Content-Type: application/json');
        echo json_encode($appointmentInfo);
    } else {
        // Handle errors, e.g., log or return an error response
        echo json_encode(['error' => 'Failed to fetch appointment information']);
    }

    $stmtAppointmentInfo->close();
} else {
    // Handle cases where appointmentID is not provided
    echo json_encode(['error' => 'Invalid request']);
}

?>
