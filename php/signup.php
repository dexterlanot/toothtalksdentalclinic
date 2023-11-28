<?php
include("db_config.php");

if ($db->connect_error) {
  die("Connection failed: " . $db->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $fname = $_POST["fname"];
  $lname = $_POST["lname"];
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
  $sql_patient = "INSERT INTO patient (FirstName, LastName, Age, DateOfBirth, PhoneNumber, Email, Gender, Address, RegisterDate)
                    VALUES ('$fname', '$lname', $age, '$dob', '$phone', '$email', '$gender', '$address', CURDATE())";

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
      echo "Error: " . $sql_patient_account . "" . $db->error;
    }
  } else {
    // Handle registration error
    echo "Error: " . $sql_patient . "" . $db->error;
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Signup</title>
  <link rel="stylesheet" href="signup.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="icon" type="image/x-icon" href="../assets/client-logo.png">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/ea307fd923.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <?php include 'navbar.php'; ?>
</head>

<body>
  <div class="content-img">
  </div>
  <div class="sign-up-container">
    <div class="container">
      <h2>Signup</h2>
      <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="content">
          <div class="input-box">
            <label for="fname">First Name</label>
            <input type="text" name="fname" required>
          </div>
          <div class="input-box">
            <label for="lname">Last Name</label>
            <input type="text" name="lname" required>
          </div>
          <div class="input-box">
            <label for="age">Age</label>
            <input type="text" name="age">
          </div>
          <div class="input-box">
            <label for="dob"> Date of Birth </label>
            <input type="date" name="dob">
          </div>
          <div class="input-box">
            <label for="phone"> Phone </label>
            <input type="text" name="phone">
          </div>
          <div class="input-box">
            <label for=""> Gender </label>
            <div class="gender-option">
              <input type="radio" id="male" name="gender" value="Male">
              <label for="male"> Male</label>
              <input type="radio" id="female" name="gender" value="Female">
              <label for="female"> Female</label>
              <input type="radio" id="other" name="gender" value="Other">
              <label for="other"> Other</label>
            </div>

          </div>
          <div class="input-box">
            <label for="address"> Address </label>
            <input type="text" name="address">
          </div>

          <div class="input-box">
            <label for="email"> Email </label>
            <input type="email" name="email" required>
          </div>

          <div class="input-box">
            <label for="password"> Password </label>
            <input type="password" name="password" required>
          </div>
          <div class="input-box">
            <label for="confirm_password"> Conifrm Password </label>
            <input type="password" name="confirm_password" required>
          </div>
        </div>
        <div class="button">
          <input type="submit" value="Signup">
        </div>
        <div class="text">
          <p> Already have an account? <a href="./login.php"> Login </a> </p>
        </div>
      </form>
    </div>
  </div>
</body>

</html>