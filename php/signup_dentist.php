<?php
include("db_config.php");

// Check the connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $contact_number = $_POST["contact_number"];
    $address = $_POST["address"];

    // Insert dentist data into 'dentistprofile' table
    $sql_dentist = "INSERT INTO dentistprofile (FirstName, LastName, Email, Password, ContactNumber, Address)
                    VALUES ('$first_name', '$last_name', '$email', '$password', '$contact_number', '$address')";

    if ($db->query($sql_dentist) === TRUE) {
        // Dentist registration successful
        header("Location: login.php");
        exit();
    } else {
        // Handle registration error
        echo "Error: " . $sql_dentist . "<br>" . $db->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dentist Signup</title>
</head>
<body>
    <h2>Dentist Signup</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        First Name: <input type="text" name="first_name" required><br>
        Last Name: <input type="text" name="last_name" required><br>
        Email: <input type="email" name="email" required><br>
        Password: <input type="password" name="password" required><br>
        Contact Number: <input type="text" name="contact_number"><br>
        Address: <input type="text" name="address"><br>
        <input type="submit" value="Signup">
    </form>
</body>
</html>
