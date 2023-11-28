<?php
$username = "";
$email    = "";
$errors = array(); 

// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'ttdc_db');

// Check the connection
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
