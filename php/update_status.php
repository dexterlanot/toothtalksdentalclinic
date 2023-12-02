<?php
include("db_config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appointmentID = $_POST["appointmentID"];
    $status = $_POST["status"];

    // Use prepared statement to prevent SQL injection
    $stmt = $db->prepare("UPDATE appointment SET Status = ? WHERE AppointmentID = ?");
    $stmt->bind_param("si", $status, $appointmentID);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error: " . $stmt->error;
    }

    $stmt->close();
}
?>
