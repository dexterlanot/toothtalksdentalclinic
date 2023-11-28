<?php
include("db_config.php");

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $age = $_POST["age"];
    $dob = $_POST["dob"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $gender = $_POST["gender"];
    $address = $_POST["address"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Password validation
    if ($password !== $confirm_password) {
        echo "Error: Passwords do not match.";
        exit();
    }

    // Check if the email already exists in the 'patientaccount' table
    $check_email_query = "SELECT * FROM patientaccount WHERE Email = '$email'";
    $check_email_result = $db->query($check_email_query);

    if ($check_email_result->num_rows > 0) {
        // Email already registered
        echo "Error: This email is already registered. Please use a different email.";
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert patient data into 'patient' table
    $sql_patient = "INSERT INTO patient (Name, Age, DateOfBirth, PhoneNumber, Email, Gender, Address, RegisterDate)
                    VALUES ('$name', $age, '$dob', '$phone', '$email', '$gender', '$address', CURDATE())";

    if ($db->query($sql_patient) === TRUE) {
        // Get the last inserted patient ID
        $last_patient_id = $db->insert_id;

        // Insert patient login credentials into 'patientaccount' table
        $sql_patient_account = "INSERT INTO patientaccount (PatientID, Username, Password, Email)
                               VALUES ($last_patient_id, '$email', '$hashed_password', '$email')";

        if ($db->query($sql_patient_account) === TRUE) {
            // Patient registration successful
            header("Location: login.php");
            exit();
        } else {
            // Handle registration error
            echo "Error: " . $sql_patient_account . "<br>" . $db->error;
        }
    } else {
        // Handle registration error
        echo "Error: " . $sql_patient . "<br>" . $db->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Signup</title>
</head>
<body>
    <h2>Patient Signup</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Name: <input type="text" name="name" required><br>
        Age: <input type="text" name="age"><br>
        Date of Birth: <input type="date" name="dob"><br>
        Phone: <input type="text" name="phone"><br>
        Email: <input type="email" name="email" required><br>
        Gender: <input type="text" name="gender"><br>
        Address: <input type="text" name="address"><br>
        Password: <input type="password" name="password" required><br>
        Confirm Password: <input type="password" name="confirm_password" required><br>
        <input type="submit" value="Signup">
    </form>
</body>
</html>
