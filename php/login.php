<?php
include("db_config.php");

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check if it's a patient login
    $sql_patient = "SELECT * FROM patientaccount WHERE Email='$email'";
    $result_patient = $db->query($sql_patient);

    if ($result_patient->num_rows > 0) {
        $row = $result_patient->fetch_assoc();
        if (password_verify($password, $row["Password"])) {
            // Patient login successful, set session and redirect to patient dashboard
            session_start();
            $_SESSION["user_id"] = $row["PatientID"];
            header("Location: dashboard_patient.php");
            exit();
        } else {
            // Patient login failed
            echo "Invalid email or password for patient.";
        }
    }

    // Check if it's a dentist login
    $sql_dentist = "SELECT * FROM dentistprofile WHERE Email='$email'";
    $result_dentist = $db->query($sql_dentist);

    if ($result_dentist->num_rows > 0) {
        $row = $result_dentist->fetch_assoc();
        if (password_verify($password, $row["Password"])) {
            // Dentist login successful, set session and redirect to dentist dashboard
            session_start();
            $_SESSION["user_id"] = $row["DentistID"];
            header("Location: dashboard_dentist.php");
            exit();
        } else {
            // Dentist login failed
            echo "Invalid email or password for dentist.";
        }
    }

    // Email not found
    echo "User with this email does not exist.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Email: <input type="email" name="email" required><br>
        Password: <input type="password" name="password" required><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>
