<?php
include("db_config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $transactionID = $_POST['transactionID'];

    $query = "SELECT * FROM `patient_transaction_view` WHERE TransactionID = $transactionID";
    $result = mysqli_query($db, $query);

    if ($result) {
        $data = mysqli_fetch_assoc($result);
        echo json_encode($data);
    } else {
        echo json_encode(['error' => 'Unable to fetch transaction data']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>
