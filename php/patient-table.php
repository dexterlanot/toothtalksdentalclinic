<?php
include("db_config.php");

session_start();

// Check if the dentist is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}


// Number of patients per page
$patientsPerPage = 10;

// Calculate total number of pages
$sqlCountPatients = "SELECT COUNT(*) as total FROM `patient`";
$resultCountPatients = $db->query($sqlCountPatients);
$rowCountPatients = $resultCountPatients->fetch_assoc();
$totalPatients = $rowCountPatients['total'];
$totalPagesPatients = ceil($totalPatients / $patientsPerPage);

// Current page (default to 1 if not set)
$pagePatients = isset($_GET['pagePatients']) ? $_GET['pagePatients'] : 1;

// Calculate the offset for the SQL query
$offsetPatients = ($pagePatients - 1) * $patientsPerPage;

$queryPatients = "SELECT * FROM `patient` ORDER BY RegisterDate DESC LIMIT $offsetPatients, $patientsPerPage";
$resultPatients = mysqli_query($db, $queryPatients);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient List</title>
    <link rel="stylesheet" href="./index.css">
    <link rel="icon" type="image/x-icon" href="../assets/client-logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <!-- Add these lines for the latest FullCalendar -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/main.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <?php include("dentist_sidebar.php") ?>
    <section class="patient-table">
        <h2> Patient List</h2>
        <div class="add-patient-form">

        </div>
        <div class="patients">

            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Date of Birth</th>
                        <th>Contact Number</th>
                        <th>Address</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                     while ($row = mysqli_fetch_assoc($resultPatients)) {
                        $fullName = $row['FirstName'] . ' ' . $row['LastName'];
                        $dateOfBirth = date('Y-m-d', strtotime($row['DateOfBirth']));
                        $PatientID = $row['PatientID'];
                        echo "<tr>";
                        echo "<td>$fullName</td>";
                        echo "<td>{$row['Age']}</td>";
                        echo "<td>$dateOfBirth</td>";
                        echo "<td>{$row['PhoneNumber']}</td>";
                        echo "<td>{$row['Address']}</td>";
                        echo "<td>
                        <a href='view_patient.php?id=$PatientID' class='view-button'>
                        <i class='uil uil-document-info'></i>View
                        </a>
                              </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
            <?php
    // Display pagination links
    echo "<div class='pagination-patient-table'>";
    for ($i = 1; $i <= $totalPagesPatients; $i++) {
        $activeClass = ($i == $pagePatients) ? 'active' : '';
        echo "<a href='?pagePatients=$i' class='$activeClass'>$i</a>";
    }
    echo "</div>";
    ?>
        </div>
    </section>
</body>

</html>