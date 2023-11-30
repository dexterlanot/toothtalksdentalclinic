<?php
include("db_config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dentistID = $_POST["dentistID"];

    $sql = "SELECT MONTH(Date) as month, COUNT(*) as completedCount FROM appointment WHERE DentistID = $dentistID AND Status = 'Completed' GROUP BY month";
    $result = $db->query($sql);

    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
}
?>
