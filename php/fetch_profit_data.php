<?php
include("db_config.php");

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Function to fetch profit data
function fetchProfitData($db, $user_id)
{
    $sql = "SELECT DATE_FORMAT(Date, '%Y-%m') AS month, SUM(AmountToBePaid) AS totalAmount
            FROM transaction 
            WHERE Status = 'Paid'
            GROUP BY month";
    
    $result = $db->query($sql);

    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    return $data;
}

$user_id = $_SESSION["user_id"];
$profitData = fetchProfitData($db, $user_id);

// Return profit data as JSON
header('Content-Type: application/json');
echo json_encode($profitData);
?>
