<?php
include("db_config.php");

session_start();



// Check if the dentist is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Function to count pending appointments
function countPendingAppointments($db, $user_id)
{
    $sql = "SELECT COUNT(*) AS count FROM appointment WHERE DentistID = $user_id AND Status = 'Pending'";
    $result = $db->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'];
}

// Function to count upcoming appointments
function countUpcomingAppointments($db, $user_id)
{
    $sql = "SELECT COUNT(*) AS count FROM appointment WHERE DentistID = $user_id AND Status = 'Approved'";
    $result = $db->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'];
}

// Function to count total patients for the current month
function countTotalPatients($db, $user_id)
{
    $currentMonth = date('m');
    $sql = "SELECT COUNT(DISTINCT PatientID) AS count FROM appointment WHERE DentistID = $user_id AND MONTH(Date) = $currentMonth";
    $result = $db->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'];
}

// Function to calculate total earnings for the current month
function calculateTotalEarnings($db, $user_id)
{
    $currentMonth = date('m');
    $sql = "SELECT SUM(AmountToBePaid) AS total FROM transaction WHERE MONTH(Date) = $currentMonth AND Status = 'Paid'";
    $result = $db->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}

// Retrieve counts and totals
$pendingAppointmentsCount = countPendingAppointments($db, $_SESSION["user_id"]);
$upcomingAppointmentsCount = countUpcomingAppointments($db, $_SESSION["user_id"]);
$totalPatientsCount = countTotalPatients($db, $_SESSION["user_id"]);
$totalEarnings = calculateTotalEarnings($db, $_SESSION["user_id"]);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dentist Dashboard</title>
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
    <!-- Dentist dashboard content here -->
    <section>
        <div class="main_content">
            <header>
                <h1>
                    Dashboard
                </h1>
                <div class="user-wrapper">
                    <i class="uil uil-user-circle"></i>
                    <div>
                        <h4> <?php
                                $user_id = $_SESSION["user_id"];

                                // Retrieve and display dentist information from the 'dentistprofile' table
                                $sql_dentist_info = "SELECT * FROM dentistprofile WHERE DentistID=$user_id";
                                $result_dentist_info = $db->query($sql_dentist_info); // Change $conn to $db

                                if ($result_dentist_info->num_rows > 0) {
                                    $row = $result_dentist_info->fetch_assoc();
                                    // Display dentist information here
                                    echo "Dr. " . $row["FirstName"] . " " . $row["LastName"];
                                    // Add more details as needed
                                } else {
                                    // Dentist not found
                                    echo "Dentist not found.";
                                }
                                ?> </h4>
                        <p> Dentist </p>
                    </div>
                </div>
            </header>
            <main>
                <div class="cards">
                    <div class="card-single box1">
                        <div>
                            <h1> <?php echo $pendingAppointmentsCount; ?> </h1>
                            <span>Pending Appointments</span>
                        </div>
                        <div>
                            <i class="uil uil-history-alt"></i>
                        </div>
                    </div>
                    <div class="card-single box2">
                        <div>
                            <h1> <?php echo $upcomingAppointmentsCount; ?> </h1>
                            <span>Upcoming Appointments</span>
                        </div>
                        <div>
                            <i class="uil uil-sort-amount-up"></i>
                        </div>
                    </div>
                    <div class="card-single box3">
                        <div>
                            <h1> <?php echo $totalPatientsCount; ?> </h1>
                            <span>Total Patients</span>
                        </div>
                        <div>
                            <i class="uil uil-users-alt"></i>
                        </div>
                    </div>
                    <div class="card-single box4">
                        <div>
                            <h1> ₱ <?php echo number_format($totalEarnings, 2); ?> </h1>
                            <span> Total Earnings </span>
                        </div>
                        <div>
                            <i class="uil uil-money-bill-stack"></i>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div class="dashboard-charts">
            <div class="chart">
                <canvas id="appointmentChart"></canvas>
            </div>
            <div class="chart">
                <canvas id="profitChart"></canvas>
            </div>
        </div>
        <div class="pending-table">
            <table>
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Treatment</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch and display appointment data
                    $user_id = $_SESSION["user_id"];

                    // Pagination variables
                    $page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number, default is 1
                    $recordsPerPage = 2;
                    $offset = ($page - 1) * $recordsPerPage;

                    $sql = "SELECT 
    CONCAT(patient.FirstName, ' ', patient.LastName) AS PatientName,
    appointment.TreatmentType AS Treatment,
    appointment.Date AS AppointmentDate,
    TIME_FORMAT(appointment.Time, '%h:%i %p') AS AppointmentTime,
    appointment.Status
FROM patient
JOIN appointment ON patient.PatientID = appointment.PatientID
WHERE appointment.DentistID = $user_id AND appointment.Status = 'Pending'
ORDER BY appointment.Date DESC, appointment.Time DESC
LIMIT $offset, $recordsPerPage";

                    $result = $db->query($sql);

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['PatientName'] . "</td>";
                        echo "<td>" . $row['Treatment'] . "</td>";
                        echo "<td>" . date("F j, Y", strtotime($row["AppointmentDate"])) . "</td>";
                        echo "<td>" . date("h:i A", strtotime($row["AppointmentTime"])) . "</td>";
                        $statusClass = strtolower($row['Status']);
                        echo "<td><span class='status-text $statusClass'>" . $row['Status'] . "</span></td>";
                        echo "</tr>";
                    }
                    ?>

                </tbody>
            </table>
            <div class="pagination-pending-table">
                <?php
                // Query to get the total count without LIMIT
$countQuery = "SELECT COUNT(*) AS total FROM appointment WHERE DentistID = $user_id AND appointment.Status = 'Pending'";
$countResult = $db->query($countQuery);
$countRow = $countResult->fetch_assoc();
$totalRows = $countRow['total'];

// Calculate the total number of pages
$totalPages = ceil($totalRows / $recordsPerPage);


                // Display page numbers
                for ($i = 1; $i <= $totalPages; $i++) {
                    echo "<a href='dashboard.php?page=$i'";
                    if ($i == $page) {
                        echo " class='active'";
                    }
                    echo ">$i</a>";
                }
                ?>
            </div>
        </div>

    </section>
</body>

</html>
<script>
    // Function to fetch data from the server
    function fetchData(dentistID, callback) {
        $.ajax({
            url: 'fetch_appointment_data.php',
            method: 'POST',
            data: {
                dentistID: dentistID
            },
            dataType: 'json',
            success: function(data) {
                callback(data);
            }
        });
    }

    // Function to create Chart.js chart for completed appointments
    function createAppointmentChart(months, completedCounts) {
        var ctx = document.getElementById('appointmentChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Completed Appointments',
                    data: completedCounts,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Function to fetch profit data from the server
    function fetchProfitData(dentistID, callback) {
        $.ajax({
            url: 'fetch_profit_data.php',
            method: 'POST',
            data: {
                dentistID: dentistID
            },
            dataType: 'json',
            success: function(data) {
                callback(data);
            }
        });
    }

    // Function to create Chart.js chart for total profit
    function createProfitChart(months, totalAmounts) {
        var ctx = document.getElementById('profitChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Total Profit',
                    data: totalAmounts,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Combined document ready function
    $(document).ready(function() {
        var dentistID = <?php echo $_SESSION["user_id"]; ?>;

        // Fetch and create chart for completed appointments
        fetchData(dentistID, function(data) {
            var months = data.map(function(item) {
                return item.month;
            });
            var completedCounts = data.map(function(item) {
                return item.completedCount;
            });
            createAppointmentChart(months, completedCounts);
        });

        // Fetch and create chart for total profit
        fetchProfitData(dentistID, function(data) {
            var months = data.map(function(item) {
                return item.month;
            });
            var totalAmounts = data.map(function(item) {
                return item.totalAmount;
            });
            createProfitChart(months, totalAmounts);
        });
    });
</script>