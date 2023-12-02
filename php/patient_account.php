<?php
include("db_config.php");

session_start();

// Check if the patient is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Handle appointment form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submitAppointment"])) {
    $user_id = $_SESSION["user_id"];
    $dentist_id = $_POST["dentist_id"];
    $treatment_type = $_POST["treatment_type"];
    $date = $_POST["date"];
    $time = $_POST["time"];

    // Insert appointment into the database
    $sql_insert_appointment = "INSERT INTO appointment (PatientID, DentistID, TreatmentType, Date, Time) 
                               VALUES ($user_id, $dentist_id, '$treatment_type', '$date', '$time')";

    if ($db->query($sql_insert_appointment)) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        // Error inserting appointment
        echo "Error: " . $db->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Patient Dashboard</title>
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
    <script>
        $(document).ready(function() {
            // AJAX request to cancel appointment
            $(document).ready(function() {
                // AJAX request to cancel appointment
                $(".delete-button").on("click", function() {
                    var appointmentId = $(this).data("appointment-id");

                    $.ajax({
                        type: "POST",
                        url: "cancel_appointment.php",
                        data: {
                            appointmentId: appointmentId
                        },
                        success: function(response) {
                            console.log(response);

                            if (response === "success") {
                                // Update the status in the table without refreshing
                                $("td[data-appointment-id='" + appointmentId + "']").filter(":nth-child(4)").text("Cancelled");

                                // Reload the page to see the updated status
                                location.reload();
                            } else {
                                alert("Failed to cancel appointment. Please try again.");
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            alert("An error occurred. Please check the console for details.");
                        }
                    });
                });
            });
        });
    </script>
</head>

<body>
    <?php include("sidebar.php") ?>
    <section>
        <div class="dash_content">
            <div class="title">
                <span class="text"> Hello, <?php
                                            $user_id = $_SESSION["user_id"];
                                            $sql_patient_info = "SELECT * FROM patient WHERE PatientID=$user_id";
                                            $result_patient_info = $db->query($sql_patient_info);
                                            if ($result_patient_info !== false && $result_patient_info->num_rows > 0) {
                                                $row = $result_patient_info->fetch_assoc();
                                                if ($row !== null) {
                                                    echo "" . $row["FirstName"];
                                                } else {
                                                    echo "Patient information is not available.";
                                                }
                                            } else {
                                                echo "Patient not found.";
                                            }
                                            ?>
                </span>
            </div>
            <div class="appointment_section">
                <div class="appointment_table ">
                    <div class="appointment_title">
                        <i class="uil uil-schedule"></i>
                        <span class="text"> Appointments </span>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Treatment Type</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // ... (previous PHP code)

                            // Number of appointments per page
                            $appointmentsPerPage = 5;

                            // Calculate total number of pages
                            $sqlCount = "SELECT COUNT(*) as total FROM appointment WHERE PatientID=$user_id";
                            $resultCount = $db->query($sqlCount);
                            $rowCount = $resultCount->fetch_assoc();
                            $totalAppointments = $rowCount['total'];
                            $totalPages = ceil($totalAppointments / $appointmentsPerPage);

                            // Current page (default to 1 if not set)
                            $page = isset($_GET['page']) ? $_GET['page'] : 1;

                            // Calculate the offset for the SQL query
                            $offset = ($page - 1) * $appointmentsPerPage;

                            // Fetch and display appointments for the current page
                            $sql_appointments = "SELECT * FROM appointment WHERE PatientID=$user_id ORDER BY Date DESC, TIME_FORMAT(Time, '%h:%i %p') DESC LIMIT $offset, $appointmentsPerPage";
                            $result_appointments = $db->query($sql_appointments);

                            if ($result_appointments !== false && $result_appointments->num_rows > 0) {
                                while ($row_appointment = $result_appointments->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row_appointment["TreatmentType"] . "</td>";
                                    echo "<td>" . date("F j, Y", strtotime($row_appointment["Date"])) . "</td>";
                                    echo "<td>" . date("h:i A", strtotime($row_appointment["Time"])) . "</td>";
                                    echo "<td>" . $row_appointment["Status"] . "</td>";
                                    echo "<td>";

                                    if ($row_appointment["Status"] !== "Cancelled" && $row_appointment["Status"] !== "Approved" && $row_appointment["Status"] !== "Completed") {
                                        // Only show buttons if the status is not "Cancelled"
                                        // Inside the while loop where you display appointments
                                        // echo "<button class='edit-button' data-appointment-id='" . $row_appointment["AppointmentID"] . "' data-treatment-type='" . $row_appointment["TreatmentType"] . "' data-date='" . $row_appointment["Date"] . "' data-time='" . $row_appointment["Time"] . "'>Edit</button>";
                                        echo "<button class='delete-button' data-appointment-id='" . $row_appointment["AppointmentID"] . "'>Cancel</button>";
                                    } else {
                                        // If status is "Cancelled," disable the button and add a class for styling
                                        echo "<button class='disabled' disabled>Cancel</button>";
                                    }

                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>No appointments found.</td></tr>";
                            }
                            ?>

                        </tbody>
                    </table>
                    <?php
                    echo "<div class='pagination'>";
                    for ($i = 1; $i <= $totalPages; $i++) {
                        $activeClass = ($i == $page) ? 'active' : '';
                        echo "<a href='?page=$i' class='$activeClass'>$i</a>";
                    }
                    echo "</div>"; ?>
                </div>

                <div class="form_section">
                    <div class="add_appointment_title">
                        <i class="uil uil-plus"></i>
                        <span class="text"> Add Appointment </span>
                    </div>
                    <form class="appointment-form" method="post" action="">
                        <!-- Dentist ID is set to 1 by default -->
                        <input type="hidden" name="dentist_id" value="1">

                        <div class="input-box">
                            <label for="treatment_type">Treatment Type:</label>
                            <select type="text" name="treatment_type">
                                <option value="" disabled selected>Select treatment</option>
                                <option value="Surgery">Surgery</option>
                                <option value="Bridge/Crown">Bridge/Crown</option>
                                <option value="Root Canal">Root Canal</option>
                                <option value="Ortho/Braces Monthly Adjust">
                                    Ortho/Braces Monthly Adjust
                                </option>
                                <option value="Ortho/Braces Installation">
                                    Ortho/Braces Installation
                                </option>
                                <option value="Cleaning">Cleaning</option>
                                <option value="Dentures">Dentures</option>
                                <option value="Wisdom Tooth Extraction">
                                    Wisdom Tooth Extraction
                                </option>
                                <option value="Tooth Extraction">Tooth Extraction</option>
                                <option value="Pasta/Restoration">Pasta/Restoration</option>
                                <option value="Check Up">Check Up</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>

                        <div class="date-time-container">
                            <div class="input-box">
                                <label for="date">Date:</label>
                                <input type="date" name="date" id="modalDate" required>
                            </div>
                            <div class="input-box">
                                <label for="time">Time:</label>
                                <input type="time" name="time" required>
                            </div>
                        </div>

                        <div class="button">
                            <input type="submit" name="submitAppointment" value="Add Appointment">
                        </div>
                    </form>
                </div>
            </div>
            <div class="transactions">

                <div class="transaction_table">
                    <div class="transaction_title">
                        <i class="uil uil-money-bill"></i>
                        <span class="text"> Transactions </span>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Treatment</th>
                                <th>Amount To Be Paid</th>
                                <th>Amount Paid</th>
                                <th>Payment Method</th>
                                <th>Reference Number</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $transactionsPerPage = 5;

                            // Calculate total number of pages for transactions
                            $sqlTransactionCount = "SELECT COUNT(*) as total FROM transaction WHERE PatientID=$user_id";
                            $resultTransactionCount = $db->query($sqlTransactionCount);
                            $rowTransactionCount = $resultTransactionCount->fetch_assoc();
                            $totalTransactions = $rowTransactionCount['total'];
                            $totalTransactionPages = ceil($totalTransactions / $transactionsPerPage);

                            // Current transaction page (default to 1 if not set)
                            $transactionPage = isset($_GET['transactionPage']) ? $_GET['transactionPage'] : 1;

                            // Calculate the offset for the SQL query for transactions
                            $transactionOffset = ($transactionPage - 1) * $transactionsPerPage;

                            // Fetch and display transactions for the patient
                            $sql_transactions = "SELECT * FROM transaction WHERE PatientID=$user_id";
                            $result_transactions = $db->query($sql_transactions);

                            if ($result_transactions !== false && $result_transactions->num_rows > 0) {
                                while ($row_transaction = $result_transactions->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row_transaction["Treatment"] . "</td>";
                                    echo "<td>" . $row_transaction["AmountToBePaid"] . "</td>";
                                    echo "<td>" . $row_transaction["AmountPaid"] . "</td>";
                                    echo "<td>" . $row_transaction["PaymentMethod"] . "</td>";
                                    echo "<td>" . $row_transaction["ReferenceNumber"] . "</td>";
                                    echo "<td>" . $row_transaction["Status"] . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>No transactions found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php
                    echo "<div class='pagination'>";
                    for ($i = 1; $i <= $totalTransactionPages; $i++) {
                        $activeClass = ($i == $transactionPage) ? 'active' : '';
                        echo "<a href='?transactionPage=$i' class='$activeClass'>$i</a>";
                    }
                    echo "</div>";
                    ?>
                </div>
            </div>
        </div>
    </section>
</body>

</html>