<?php
include("db_config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appointmentID = $_POST["AppointmentID"];
    $amountToBePaid = $_POST["AmountToBePaid"];
    $amountPaid = $_POST["AmountPaid"];
    $paymentMethod = $_POST["PaymentMethod"];
    $referenceNumber = $_POST["ReferenceNumber"];

    // Calculate remaining balance
    $remainingBalance = $amountToBePaid - $amountPaid;

    // Set status based on remaining balance
    $status = ($remainingBalance == 0) ? 'Paid' : 'Pending';

    // Insert data into the transaction table
    $sql = "INSERT INTO `transaction` (`Date`, `PatientID`, `Treatment`, `AmountToBePaid`, `AmountPaid`, `RemainingBalance`, `PaymentMethod`, `ReferenceNumber`, `Status`)
            VALUES (NOW(), (SELECT `PatientID` FROM `patientappointmentview` WHERE `AppointmentID` = $appointmentID), 
                    (SELECT `TreatmentType` FROM `patientappointmentview` WHERE `AppointmentID` = $appointmentID), 
                    $amountToBePaid, $amountPaid, $remainingBalance, '$paymentMethod', '$referenceNumber', '$status')";

    if ($db->query($sql) === TRUE) {
        echo "success";
    } else {
        echo "Error inserting data into transaction table: " . $db->error;
    }
}
?>
