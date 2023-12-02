<?php
include("db_config.php");

session_start();

// Check if the dentist is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Number of transactions per page
$transactionsPerPage = 10;

// Calculate total number of pages
$sqlCountTransactions = "SELECT COUNT(*) as total FROM `patient_transaction_view`";
$resultCountTransactions = $db->query($sqlCountTransactions);
$rowCountTransactions = $resultCountTransactions->fetch_assoc();
$totalTransactions = $rowCountTransactions['total'];
$totalPagesTransactions = ceil($totalTransactions / $transactionsPerPage);

// Current page (default to 1 if not set)
$pageTransactions = isset($_GET['pageTransactions']) ? $_GET['pageTransactions'] : 1;

// Calculate the offset for the SQL query
$offsetTransactions = ($pageTransactions - 1) * $transactionsPerPage;

$queryTransactions = "SELECT * FROM `patient_transaction_view` ORDER BY Date DESC LIMIT $offsetTransactions, $transactionsPerPage";
$resultTransactions = mysqli_query($db, $queryTransactions);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction List</title>
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
        <h2> Transaction Records</h2>
        <div class="patients">
            <table>
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Date</th>
                        <th>Treatment</th>
                        <th>Service Amount</th>
                        <th>Amount Paid</th>
                        <th>Balance</th>
                        <th>MOD</th>
                        <th>Ref. Number</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($resultTransactions)) {
                        $transactionID = $row['TransactionID'];
                        $PatientName = $row['PatientName'];
                        $date = $row['Date'];
                        $treatment = $row['Treatment'];
                        $amountToBePaid = $row['AmountToBePaid'];
                        $amountPaid = $row['AmountPaid'];
                        $remainingBalance = $row['RemainingBalance'];
                        $paymentMethod = $row['PaymentMethod'];
                        $referenceNumber = $row['ReferenceNumber'];
                        $status = $row['Status'];

                        echo "<tr>";
                        echo "<td>$PatientName</td>";
                        echo "<td>$date</td>";
                        echo "<td>$treatment</td>";
                        echo "<td>$amountToBePaid</td>";
                        echo "<td>$amountPaid</td>";
                        echo "<td>$remainingBalance</td>";
                        echo "<td>$paymentMethod</td>";
                        echo "<td>$referenceNumber</td>";
                        echo "<td>$status</td>";
                        echo "<td>
                                <button class='update-button' data-id='$transactionID'>
                                    <i class='uil uil-pen'></i>Update
                                </button>
                              </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
            <?php
            // Display pagination links
            echo "<div class='pagination-transaction-table'>";
            for ($i = 1; $i <= $totalPagesTransactions; $i++) {
                $activeClass = ($i == $pageTransactions) ? 'active' : '';
                echo "<a href='transaction_table.php?pageTransactions=$i' class='$activeClass'>$i</a>";
            }
            echo "</div>";
            ?>
        </div>
    </section>

    <!-- SweetAlert Modal for Update -->
    <script>
        $(document).ready(function() {
            $('.update-button').click(function() {
                var transactionID = $(this).data('id');

                // Fetch transaction data using AJAX
                $.ajax({
                    url: 'fetch_transaction_data.php',
                    method: 'POST',
                    data: {
                        transactionID: transactionID
                    },
                    dataType: 'json',
                    success: function(data) {
                        // Populate form fields with transaction data
                        Swal.fire({
                            title: 'Update Transaction Information',
                            html: '<form id="updateForm">' +
                                '<label for="updateTreatment">Treatment</label>' +
                                '<input type="text" id="updateTreatment" name="updateTreatment" value="' + data.Treatment + '" required>' +
                                '<label for="updateAmountToBePaid">Service Amount</label>' +
                                '<input type="text" id="updateAmountToBePaid" name="updateAmountToBePaid" value="' + data.AmountToBePaid + '" required>' +
                                '<label for="updateAmountPaid">Amount Paid</label>' +
                                '<input type="text" id="updateAmountPaid" name="updateAmountPaid" value="' + data.AmountPaid + '" required>' +
                                '<label for="updateRemainingBalance">Balance</label>' +
                                '<input type="text" id="updateRemainingBalance" name="updateRemainingBalance" value="' + data.RemainingBalance + '" required>' +
                                '<label for="updatePaymentMethod">MOD</label>' +
                                '<input type="text" id="updatePaymentMethod" name="updatePaymentMethod" value="' + data.PaymentMethod + '" required>' +
                                '<label for="updateReferenceNumber">Ref. Number</label>' +
                                '<input type="text" id="updateReferenceNumber" name="updateReferenceNumber" value="' + data.ReferenceNumber + '" required>' +
                                '<label for="updateStatus">Status</label>' +
                                '<select id="updateStatus" name="updateStatus" required>' +
                                '<option value="Pending" ' + (data.Status === 'Pending' ? 'selected' : '') + '>Pending</option>' +
                                '<option value="Paid" ' + (data.Status === 'Paid' ? 'selected' : '') + '>Paid</option>' +
                                '</select>' +
                                '</form>',
                            showCancelButton: true,
                            confirmButtonText: 'Update',
                            preConfirm: () => {
                                // Submit the form using AJAX
                                $.ajax({
                                    url: 'update_transaction.php',
                                    method: 'POST',
                                    data: $('#updateForm').serialize() + '&transactionID=' + transactionID,
                                    success: function(response) {
                                        Swal.fire('Updated!', 'Transaction information has been updated.', 'success')
                                        .then((result) => {
                                            if (result.isConfirmed) {
                                                // Reload the page
                                                location.reload();
                                            }
                                        });
                                    },
                                    error: function() {
                                        Swal.fire('Error!', 'An error occurred while updating transaction information.', 'error');
                                    }
                                });
                            }
                        });
                    },
                    error: function() {
                        Swal.fire('Error!', 'An error occurred while fetching transaction information.', 'error');
                    }
                });
            });
        });
    </script>
</body>

</html>