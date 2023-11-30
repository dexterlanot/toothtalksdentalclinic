<?php
// Include your database connection logic
include("db_config.php");

// Fetch appointment data from the database
$user_id = $_SESSION["user_id"];
$sql = "SELECT
            appointment.AppointmentID AS id,
            CONCAT(patient.FirstName, ' ', patient.LastName) AS title,
            appointment.Date AS start
        FROM patient
        JOIN appointment ON patient.PatientID = appointment.PatientID
        WHERE appointment.DentistID = $user_id";
$result = $db->query($sql);

// Format data for FullCalendar
$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'start' => $row['start']
    ];
}

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($events);
?>
