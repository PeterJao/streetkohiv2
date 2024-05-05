<?php
// Start the session
session_start();

// Include database connection
include 'connect.php';

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['admin_user'])) {
    header("Location: ../../login/login.php");
    exit;
}

// Check if the updateid parameter is set in the URL
if (isset($_GET['updateid'])) {
    // Sanitize the input to prevent SQL injection
    $employee_id = mysqli_real_escape_string($con, $_GET['updateid']);

    // Select the employee details based on the provided employee_id
    $sql = "SELECT * FROM `pos_system`.`users` WHERE id = $employee_id";
    $result = mysqli_query($con, $sql);

    // Check if the employee exists
    if (mysqli_num_rows($result) > 0) {
        // Fetch the employee details
        $row = mysqli_fetch_assoc($result);
        
        $employee_username = $row['username'];
        $employee_password = $row['password'];
    } else {
        // If the employee does not exist, redirect back to the employee list page
        header('Location: employee.php');
        exit;
    }
} else {
    // If the updateid parameter is not set, redirect back to the employee list page
    header('Location: employee.php');
    exit;
}

$validation_error = "";

// Handle form submission
if (isset($_POST['submit'])) {
    // Retrieve form data
    $employee_username = $_POST['employee_username'];
    $employee_password = $_POST['employee_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if password meets minimum requirements
    if (!validatePassword($employee_password)) {
        $validation_error = "Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
    }

    // Check if passwords match
    if ($employee_password !== $confirm_password) {
        $validation_error = "Passwords do not match.";
    }

     // Check if the new username is the same as an existing username
     $check_username_sql = "SELECT id FROM `pos_system`.`users` WHERE username = ? AND id != ?";
     $check_stmt = $con->prepare($check_username_sql);
     $check_stmt->bind_param("si", $employee_username, $employee_id);
     $check_stmt->execute();
     $check_stmt->store_result();
       
    // If the username already exists, display an error
    if ($check_stmt->num_rows > 0) {
        $validation_error = "Username already exists. Please choose a different username.";
    }

    // If no validation errors, proceed with updating the database
    if (empty($validation_error)) {
        // Update employee details in the database
        $sql = "UPDATE `pos_system`.`users` SET username='$employee_username', password='$employee_password' WHERE id=$employee_id";
        $result = mysqli_query($con, $sql);

        // Check if the update was successful
        if ($result) {
            // Redirect back to the employee list page
            header('Location: employee.php');
            exit;
        } else {
            // If the update failed, display the MySQL error
            die(mysqli_error($con));
        }
    }
}

function validatePassword($password) {
    $min_length = 8;

    $regex_uppercase = '/[A-Z]/'; 
    $regex_lowercase = '/[a-z]/';
    $regex_digit = '/[0-9]/';
    $regex_special = '/[^A-Za-z0-9]/';

    if (strlen($password) < $min_length ||
        !preg_match($regex_uppercase, $password) ||
        !preg_match($regex_lowercase, $password) ||
        !preg_match($regex_digit, $password) ||
        !preg_match($regex_special, $password)) {
        return false;
    }

    return true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Update Employee</title>
  <link rel="icon" type="image/x-icon" href="../../assets/images/SK-Icon.png">
  <link rel="stylesheet" href="../../css/updateEmployee.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
<div>
  <?php include "../dashboard/dashboard-header.php" ?>
</div>

<div class="main-content-container">
<div class="employee-form-container">
    <div class="employee-input-container">
        <h1 class="form-header">Update Employee</h1>
        <form action="" method="post">
            <div class="input-container">
                <input class="input" type="text" id="employee_username" name="employee_username" value="<?php echo htmlspecialchars($employee_username); ?>" required />
                <label class="label">Employee Username</label>
            </div>

            <div class="input-container">
                <input class="input" type="password" id="employee_password" name="employee_password" value="<?php echo htmlspecialchars($employee_password); ?>" required />
                <label class="label">Employee New Password</label>
            </div>

            <div class="input-container">
                <input class="input" type="password" id="confirmPassword" name="confirm_password" required />
                <label class="label">Confirm New Password</label>
            </div>

            <!-- Validation prompt -->
            <?php if (!empty($validation_error)) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $validation_error; ?>
                </div>
            <?php endif; ?>

            <div class="buttons-container">
                <input type="submit" class="btn btn-success" name="submit" value="Submit"></input>
                <button type="button" class="btn btn-danger" name="cancel" onclick="goBack()">Cancel</button>
            </div>
        </form>
    </div>
</div>
</div>


<!-- JavaScript for going back -->
<script>
    function goBack() {
        window.history.back();
    }
</script>

</body>
</html>
