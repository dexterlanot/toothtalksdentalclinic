<?php
include("db_config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dentistID = $_POST["dentistID"];

    $sql = "SELECT MONTH(Date) as month, SUM(AmountToBePaid) as totalAmount
            FROM transaction
            WHERE DentistID = $dentistID AND Status = 'Paid'
            GROUP BY month";
    $result = $db->query($sql);

    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
}
?>
