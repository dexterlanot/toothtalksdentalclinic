<?php
include("db_config.php");

// Check if the patient ID is provided in the URL
if (isset($_GET['id'])) {
    $patientID = mysqli_real_escape_string($db, $_GET['id']);

    // Fetch patient data based on the provided ID
    $query = "SELECT * FROM `patient` WHERE `PatientID` = $patientID";
    $result = mysqli_query($db, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $patientData = mysqli_fetch_assoc($result);

        // Display patient details here
        ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Patient Information</title>
            <link rel="stylesheet" href="./index.css">
            <link rel="icon" type="image/x-icon" href="../assets/client-logo.png">
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
            <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
        <body>
            <?php include("dentist_sidebar.php") ?>
            <section class="patient-information">
                <h2>Patient Information</h2>
                <div class="profile-pic">
                <i class="uil uil-user"></i>
                </div>
                <div class="records">
                    <p>Name: <span> <?= $patientData['FirstName'] . ' ' . $patientData['LastName'] ?> </span></p>
                    <p>Age: <?= $patientData['Age'] ?></p>
                    <p>Date of Birth: <?= $patientData['DateOfBirth'] ?></p>
                    <p>Contact Number: <?= $patientData['PhoneNumber'] ?></p>
                    <p>Email: <?= $patientData['Email'] ?></p>
                    <p>Address: <?= $patientData['Address'] ?></p>
                    <p>Registration Date: <?= date("F j, Y", strtotime($patientData['RegisterDate'])) ?></p>
                </div>
            </section>
        </body>

        </html>
        <?php
    } else {
        echo "Patient not found.";
    }
} else {
    echo "Patient ID not provided.";
}
?>
