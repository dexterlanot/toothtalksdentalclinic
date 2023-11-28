<?php
include("db_config.php");

session_start();

// Check if the patient is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Retrieve and display patient information from the 'patient' and 'patientaccount' tables
$sql_patient_info = "SELECT * FROM patient WHERE PatientID=$user_id";
$result_patient_info = $db->query($sql_patient_info); // Change $conn to $db

if ($result_patient_info->num_rows > 0) {
    $row = $result_patient_info->fetch_assoc();
    // Display patient information here
    echo "Patient Name: " . $row["FirstName"]," " . $row["LastName"];
    // Add more details as needed
} else {
    // Patient not found
    echo "Patient not found.";
}

// Include a logout link
echo "<br><br><a href='logout.php'>Logout</a>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Dashboard</title>
</head>
<body>
    <!-- Patient dashboard content here -->
</body>
</html>
