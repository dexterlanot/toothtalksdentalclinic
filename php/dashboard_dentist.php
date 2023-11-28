<?php
include("db_config.php");

session_start();



// Check if the dentist is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Retrieve and display dentist information from the 'dentistprofile' table
$sql_dentist_info = "SELECT * FROM dentistprofile WHERE DentistID=$user_id";
$result_dentist_info = $db->query($sql_dentist_info); // Change $conn to $db

if ($result_dentist_info->num_rows > 0) {
    $row = $result_dentist_info->fetch_assoc();
    // Display dentist information here
    echo "Dentist Name: " . $row["FirstName"] . " " . $row["LastName"];
    // Add more details as needed
} else {
    // Dentist not found
    echo "Dentist not found.";
}

// Include a logout link
echo "<br><br><a href='logout.php'>Logout</a>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dentist Dashboard</title>
</head>
<body>
    <!-- Dentist dashboard content here -->
</body>
</html>
