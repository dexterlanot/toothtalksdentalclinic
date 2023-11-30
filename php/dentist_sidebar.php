<?php
//include("db_config.php");
//
//// session_start();
//
//// Check if the patient is logged in
//if (!isset($_SESSION["user_id"])) {
//    header("Location: login.php");
//    exit();
//}
//
//$user_id = $_SESSION["user_id"];
//
//// // Retrieve and display patient information from the 'patient' and 'patientaccount' tables
//$sql_dentist_info = "SELECT * FROM dentistprofile WHERE DentistID=$user_id";
//$result_dentist_info = $db->query($sql_dentist_info); // Change $conn to $db
//?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Side Bar</title>
    <link rel="stylesheet" href="./sidebar.css">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="icon" type="image/x-icon" href="../assets/client-logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <!-- <script src="https://kit.fontawesome.com/ea307fd923.js" crossorigin="anonymous"></script> -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
</head>

<body>
    <input type="checkbox" id="check">
    <label for="check">
        <i class="uil uil-bars" id="btn"></i>
        <i class="uil uil-times" id="cancel"></i>
    </label>
    <div class="sidebar">
        <header><img src="../assets/client-logo.png" alt="Tooth Talks Dental Clinic"></header>
        <ul>
            <li><a href="dashboard.php"><i class="uil uil-apps"></i></i> Overview </a></li>
            <li><a href="patient-table.php"><i class="uil uil-users-alt"></i> Patients </a></li>
            <li><a href="Calendar.php"><i class="uil uil-calendar-alt"></i> Calendar </a></li>
            <li><a href=""><i class="uil uil-list-ul"></i> Appointments </a></li>
            <li><a href=""><i class="uil uil-transaction"></i> Transactions </a></li>
            <!-- <li><a href=""><i class="uil uil-transaction"></i> Transaction </a></li> -->
            <li><a href="logout.php"><i class="uil uil-signout"></i> Logout </a></li>
        </ul>
    </div>
    <!-- <section>
        
    </section> -->
</body>

</html>