<?php
include("db_config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["appointmentId"])) {
    $appointmentId = $_POST["appointmentId"];

    // Update appointment status to "Cancelled" in the database
    $sql_update_status = "UPDATE appointment SET Status='Cancelled' WHERE AppointmentID=$appointmentId";

    if ($db->query($sql_update_status)) {
        echo "success";
    } else {
        echo "error";
    }
}
?>
