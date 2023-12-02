<?php
include("db_config.php");

session_start();

// Check if the dentist is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Handle Approve and Decline logic if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["appointmentID"]) && isset($_POST["status"])) {
        $appointmentID = $_POST["appointmentID"];
        $status = $_POST["status"];

        // Use prepared statement to prevent SQL injection
        $stmt = $db->prepare("UPDATE patientappointmentview SET Status = ? WHERE AppointmentID = ?");
        $stmt->bind_param("si", $status, $appointmentID);

        if ($stmt->execute()) {
            // Redirect to the same page after updating status
            header("Location: appointment.php");
            exit();
        } else {
            echo "Error updating status: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Number of appointments per page
$appointmentsPerPage = 10;

// Calculate total number of pages
$sqlCountAppointments = "SELECT COUNT(*) as total FROM `patientappointmentview`";
$resultCountAppointments = $db->query($sqlCountAppointments);
$rowCountAppointments = $resultCountAppointments->fetch_assoc();
$totalAppointments = $rowCountAppointments['total'];
$totalPagesAppointments = ceil($totalAppointments / $appointmentsPerPage);

// Current page (default to 1 if not set)
$pageAppointments = isset($_GET['pageAppointments']) ? $_GET['pageAppointments'] : 1;

// Calculate the offset for the SQL query
$offsetAppointments = ($pageAppointments - 1) * $appointmentsPerPage;

$queryAppointments = "SELECT
    `PatientID`,
    `PatientName`,
    `Age`,
    `DateOfBirth`,
    `PhoneNumber`,
    `PatientEmail`,
    `Gender`,
    `PatientAddress`,
    `RegisterDate`,
    `AppointmentID`,
    `DentistID`,
    `TreatmentType`,
    `AppointmentDate`,
    `AppointmentTime`,
    `Status`
FROM
    `patientappointmentview`
WHERE
    `Status` IN ('Pending', 'Approved', 'Completed')
ORDER BY
    `AppointmentDate` DESC,
    TIME_FORMAT(`AppointmentTime`, '%h:%i %p') DESC
LIMIT $offsetAppointments, $appointmentsPerPage";

$resultAppointments = mysqli_query($db, $queryAppointments);
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
        <h2> Appointment List</h2>
        <div class="patients">

            <table>
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Treatment Type</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($rowAppointment = mysqli_fetch_assoc($resultAppointments)) {
                        $patientName = $rowAppointment['PatientName'];
                        $treatmentType = $rowAppointment['TreatmentType'];
                        $appointmentDate = date("F j, Y", strtotime($rowAppointment["AppointmentDate"]));
                        $appointmentTime = date('h:i A', strtotime($rowAppointment['AppointmentTime']));
                        $status = $rowAppointment['Status'];
                        $appointmentID = $rowAppointment['AppointmentID'];

                        echo "<tr>";
                        echo "<td>$patientName</td>";
                        echo "<td>$treatmentType</td>";
                        echo "<td>$appointmentDate</td>";
                        echo "<td>$appointmentTime</td>";
                        echo "<td class='status'>$status</td>";
                        echo "<td>";

                        // Show buttons based on status
                        if ($status == 'Pending') {
                            echo "<form method='post' action='appointment.php' style='display: inline-block;'>";
                            echo "<input type='hidden' name='appointmentID' value='$appointmentID'>";
                            echo "<input type='hidden' name='status' value='Approved'>";
                            echo "<button type='submit' class='approve-button'><i class='uil uil-file-check-alt'></i>Approve</button>";
                            echo "</form>";

                            echo "<form method='post' action='appointment.php' style='display: inline-block;'>";
                            echo "<input type='hidden' name='appointmentID' value='$appointmentID'>";
                            echo "<input type='hidden' name='status' value='Cancelled'>";
                            echo "<button type='submit' class='decline-button'><i class='uil uil-times-square'></i>Decline</button>";
                            echo "</form>";
                        } elseif ($status == 'Approved') {
                            echo "<button class='make-payment-button' data-appointment-id='$appointmentID'><i class='uil uil-money-bill'></i></i>Make Payment Record</button>";
                        }

                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>

            <?php
            // Display pagination links
            echo "<div class='pagination-patient-table'>";
            for ($i = 1; $i <= $totalPagesAppointments; $i++) {
                $activeClass = ($i == $pageAppointments) ? 'active' : '';
                echo "<a href='?pageAppointments=$i' class='$activeClass'>$i</a>";
            }
            echo "</div>";
            ?>
        </div>
    </section>
</body>

</html>
<script>
    $(document).ready(function() {
        // Function to handle the "Approve" button click
        $('.approve-button').on('click', function(e) {
            e.preventDefault();
            var approveButton = $(this);
            var appointmentID = approveButton.siblings('input[name="appointmentID"]').val();

            // Send AJAX request to update appointment status to "Approved"
            $.ajax({
                url: 'update_status.php',
                method: 'POST',
                data: {
                    appointmentID: appointmentID,
                    status: 'Approved'
                },
                success: function(response) {
                    if (response === 'success') {
                        // Hide the buttons and show "Make Payment Record" button
                        approveButton.closest('td').find('.decline-button').hide();

                        // Check if the status is Approved, then show "Make Payment Record" button
                        if (approveButton.closest('td').find('.status:contains("Approved")').length > 0) {
                            approveButton.closest('td').append('<button class="make-payment-button" data-appointment-id="' + appointmentID + '">Make Payment Record</button>');
                        }

                        // Hide the form after successful submission
                        location.reload();
                        approveButton.closest('form').hide();
                    } else {
                        alert('Failed to update status. ' + response); // Display the error message
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error in AJAX request: ' + textStatus + ' - ' + errorThrown);
                }
            });
        });

        /// Function to handle the "Make Payment Record" button click
        $(document).on('click', '.make-payment-button', function(e) {
            e.preventDefault();
            var appointmentID = $(this).data('appointment-id');
            var makePaymentButton = $(this);

            // Send AJAX request to fetch necessary information
            $.ajax({
                url: 'fetch_appointment_info.php',
                method: 'POST',
                data: {
                    appointmentID: appointmentID
                },
                dataType: 'json', // Specify the expected response type
                success: function(response) {
                    // Check if the response has the 'error' key
                    if (response.error) {
                        alert('Error: ' + response.error);
                    } else {
                        // Present a form to the user to input AmountToBePaid, AmountPaid, PaymentMethod, ReferenceNumber
                        var formHtml = '<form id="paymentForm" action="" method="post">' +
                            '<input type="hidden" name="appointmentID" value="' + appointmentID + '">' +
                            '<label for="AmountToBePaid">Amount to be Paid:</label>' +
                            '<input type="text" id="AmountToBePaid" name="AmountToBePaid" required>' +
                            '<br>' +
                            '<label for="AmountPaid">Amount Paid:</label>' +
                            '<input type="text" id="AmountPaid" name="AmountPaid" required>' +
                            '<br>' +
                            '<label for="PaymentMethod">Payment Method:</label>' +
                            '<input type="text" id="PaymentMethod" name="PaymentMethod" required>' +
                            '<br>' +
                            '<label for="ReferenceNumber">Reference Number:</label>' +
                            '<input type="text" id="ReferenceNumber" name="ReferenceNumber" required>' +
                            '</form>';

                        Swal.fire({
                            title: 'Make Payment Record',
                            html: formHtml,
                            showCancelButton: true,
                            confirmButtonText: 'Submit',
                            customClass: {
                                container: 'swal-custom-container-class',
                                confirmButton: 'swal-confirm-button-class'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Send the form data to another PHP file for processing
                                var formData = $('#paymentForm').serialize();
                                $.ajax({
                                    url: 'process_payment_record.php',
                                    method: 'POST',
                                    data: formData,
                                    success: function(response) {
                                        if (response === 'success') {
                                            // Handle success (e.g., show a success message)
                                            alert('Payment record created successfully.');
                                            // Update appointment status to 'Completed'
                                            $.ajax({
                                                url: 'update_status.php',
                                                method: 'POST',
                                                data: {
                                                    appointmentID: appointmentID,
                                                    status: 'Completed'
                                                },
                                                success: function(response) {
                                                    if (response === 'success') {
                                                        // Update the status in the current row
                                                        makePaymentButton.closest('tr').find('.status').text('Completed');
                                                        makePaymentButton.attr('disabled', true);
                                                        location.reload();
                                                    } else {
                                                        alert('Failed to update appointment status to Completed. ' + response);
                                                    }
                                                },
                                                error: function(jqXHR, textStatus, errorThrown) {
                                                    alert('Error in AJAX request: ' + textStatus + ' - ' + errorThrown);
                                                }
                                            });

                                            // Disable the button after success
                                            makePaymentButton.attr('disabled', true);
                                        } else {
                                            alert('Failed to create payment record. ' + response);
                                        }
                                    },
                                });
                            }
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error in AJAX request: ' + textStatus + ' - ' + errorThrown);
                }
            });
        });



        // Function to handle the "Decline" button click
        $('.decline-button').on('click', function(e) {
            e.preventDefault(); // Prevent the default behavior of the button
            var declineButton = $(this);
            var appointmentID = declineButton.siblings('input[name="appointmentID"]').val();

            // Send AJAX request to update appointment status to "Cancelled"
            $.ajax({
                url: 'update_status.php',
                method: 'POST',
                data: {
                    appointmentID: appointmentID,
                    status: 'Cancelled'
                },
                success: function(response) {
                    if (response === 'success') {
                        // Handle success (e.g., refresh the page)
                        location.reload();
                    } else {
                        alert('Failed to update status. ' + response); // Display the error message
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error in AJAX request: ' + textStatus + ' - ' + errorThrown);
                }
            });
        });
    });
</script>