<?php
include("db_config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $transactionID = $_POST['transactionID'];
    $updateTreatment = $_POST['updateTreatment'];
    $updateAmountToBePaid = $_POST['updateAmountToBePaid'];
    $updateAmountPaid = $_POST['updateAmountPaid'];
    $updateRemainingBalance = $_POST['updateRemainingBalance'];
    $updatePaymentMethod = $_POST['updatePaymentMethod'];
    $updateReferenceNumber = $_POST['updateReferenceNumber'];
    $updateStatus = $_POST['updateStatus'];

    $query = "UPDATE `transaction` SET 
              Treatment = '$updateTreatment', 
              AmountToBePaid = '$updateAmountToBePaid', 
              AmountPaid = '$updateAmountPaid', 
              RemainingBalance = '$updateRemainingBalance', 
              PaymentMethod = '$updatePaymentMethod', 
              ReferenceNumber = '$updateReferenceNumber', 
              Status = '$updateStatus' 
              WHERE TransactionID = $transactionID";

    $result = mysqli_query($db, $query);

    if ($result) {
        echo json_encode(['success' => 'Transaction updated successfully']);
    } else {
        echo json_encode(['error' => 'Unable to update transaction']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>
