<?php
include("db_config.php");

session_start();

// Check if the patient is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Retrieve and display patient information
$user_id = $_SESSION["user_id"]; // Set $user_id here

$sql_patient_info = "SELECT * FROM patient WHERE PatientID=$user_id";
$result_patient_info = $db->query($sql_patient_info);

if ($result_patient_info !== false && $result_patient_info->num_rows > 0) {
    $row = $result_patient_info->fetch_assoc();
} else {
    // Handle the case where patient data is not found
    $row = array();
}

// Handle patient information update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submitUpdate"])) {
    $newFirstName = mysqli_real_escape_string($db, $_POST["newFirstName"]);
    $newLastName = mysqli_real_escape_string($db, $_POST["newLastName"]);
    $newAge = mysqli_real_escape_string($db, $_POST["newAge"]);
    $newDateOfBirth = mysqli_real_escape_string($db, $_POST["newDateOfBirth"]);
    $newPhoneNumber = mysqli_real_escape_string($db, $_POST["newPhoneNumber"]);
    $newEmail = mysqli_real_escape_string($db, $_POST["newEmail"]);
    $newGender = mysqli_real_escape_string($db, $_POST["newGender"]);
    $newAddress = mysqli_real_escape_string($db, $_POST["newAddress"]);
    // Add more fields as needed

    // Update patient information in the database
    $sql_update_patient = "UPDATE patient SET 
        FirstName='$newFirstName', 
        LastName='$newLastName', 
        Age=$newAge, 
        DateOfBirth='$newDateOfBirth', 
        PhoneNumber='$newPhoneNumber', 
        Email='$newEmail', 
        Gender='$newGender', 
        Address='$newAddress'
        WHERE PatientID=$user_id";

    if ($db->query($sql_update_patient)) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        // Error updating patient information
        echo "Error: " . $db->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Patient Dashboard</title>
    <link rel="stylesheet" href="./index.css">
    <link rel="icon" type="image/x-icon" href="../assets/client-logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
</head>

<body>
    <?php include("sidebar.php") ?>
    <section>
        <div class="profile">
            <div class="edit_patient_section">
                <div class="edit_patient_title">
                    <h2> Patient Information </h2>
                </div>
                <div class="profile-pic">
                <i class="uil uil-user"></i>
                </div>
                <form class="edit-patient-form" method="post" action="">
                    <div class="content">
                        <div class="input-box-profile">
                            <label for="newFirstName">First Name:</label>
                            <input type="text" name="newFirstName" value="<?= $row['FirstName'] ?>" required>
                        </div>
                        <div class="input-box-profile">
                            <label for="newLastName">Last Name:</label>
                            <input type="text" name="newLastName" value="<?= $row['LastName'] ?>" required>
                        </div>
                        <div class="input-box-profile">
                            <label for="newAge">Age:</label>
                            <input type="number" name="newAge" value="<?= $row['Age'] ?>" required>
                        </div>
                        <div class="input-box-profile">
                            <label for="newDateOfBirth">Date of Birth:</label>
                            <input type="date" name="newDateOfBirth" value="<?= $row['DateOfBirth'] ?>" required>
                        </div>
                        <div class="input-box-profile">
                            <label for="newPhoneNumber">Phone Number:</label>
                            <input type="tel" name="newPhoneNumber" value="<?= $row['PhoneNumber'] ?>" required>
                        </div>
                        <div class="input-box-profile">
                            <label for="newEmail">Email:</label>
                            <input type="email" name="newEmail" value="<?= $row['Email'] ?>" required>
                        </div>
                        <div class="input-box-profile">
                            <label>Gender:</label>
                            <div class="gender-option">
                                <input type="radio" id="male" name="newGender" value="Male" <?php echo ($row['Gender'] === 'Male') ? 'checked' : ''; ?>>
                                <label for="male">Male</label>

                                <input type="radio" id="female" name="newGender" value="Female" <?php echo ($row['Gender'] === 'Female') ? 'checked' : ''; ?>>
                                <label for="female">Female</label>

                                <input type="radio" id="other" name="newGender" value="Other" <?php echo ($row['Gender'] === 'Other') ? 'checked' : ''; ?>>
                                <label for="other">Other</label>
                            </div>
                        </div>
                        <div class="input-box-profile">
                            <label for="newAddress">Address:</label>
                            <input type="text" name="newAddress" value="<?= $row['Address'] ?>" required>
                        </div>

                    </div>
                    <div class="button">
                        <input type="submit" name="submitUpdate" value="Update Information">
                    </div>
                </form>
            </div>
            <!-- End of edit form -->
        </div>
    </section>
</body>

</html>

<script>
    // JavaScript to reload the page after form submission
    <?php
    // Check if the form was submitted and the update was successful
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submitUpdate"])) {
        echo 'window.location.reload();';
    }
    ?>
</script>