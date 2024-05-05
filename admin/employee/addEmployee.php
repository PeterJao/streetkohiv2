<?php
session_start();
$con_streetkohi = new mysqli('localhost', 'root', '', 'streetkohi');
$con_pos_system = new mysqli('localhost', 'root', '', 'pos_system');

if ($con_streetkohi->connect_error || $con_pos_system->connect_error) {
    die("Connection failed: " . mysqli_connect_error());
}

$validation_error = "";

if (!isset($_SESSION['admin_user'])) {
    header("Location: ../../login/login.php");
    exit;
}

if (isset($_POST['submit']) && isset($_POST['employee_username']) && isset($_POST['employee_password']) && isset($_POST['confirm_password'])) {
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

    // // Hash the password
    // $hashed_password = password_hash($employee_password, PASSWORD_DEFAULT);

    // Check if username already exists
    $check_username_sql = "SELECT username FROM pos_system.users WHERE username = ?";
    $check_stmt = $con_pos_system->prepare($check_username_sql);
    $check_stmt->bind_param("s", $employee_username);
    $check_stmt->execute();
    $check_stmt->store_result();
    
    // If the username already exists, display an error
    if ($check_stmt->num_rows > 0) {
        $validation_error = "Username already exists. Please choose a different username.";
    } else {
        // If no validation errors, proceed with inserting into the database
        if (empty($validation_error)) {
            // Insert employee data into the "pos_system" database
            $sql = "INSERT INTO pos_system.users (username, password) VALUES (?, ?)";
            $stmt = $con_pos_system->prepare($sql);
            $stmt->bind_param("ss", $employee_username, $employee_password);
            $result = $stmt->execute();

            if ($result) {
                header("Location: employee.php");
                exit();
            } else {
                echo "Failed to insert data into database.";
            }
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
    <title>Add Employee</title>
    <link rel="icon" type="image/x-icon" href="../../assets/images/SK-Icon.png">
    <link rel="stylesheet" href="../../css/addEmployee.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>

<?php include "../dashboard/dashboard-header.php" ?>

<div class="main-content-container">
<div class="employee-form-container">
    <div class="employee-input-container">
        <h1 class="form-header">Add Employee</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="input-container">
                <input class="input" type="text" id="employeeUsername" name="employee_username" required />
                <label class="label">Employee Username</label>
            </div>

            <div class="input-container">
                <input class="input" type="password" id="employeePassword" name="employee_password" required />
                <label class="label">Employee Password</label>
            </div>

            <div class="input-container">
                <input class="input" type="password" id="confirmPassword" name="confirm_password" required />
                <label class="label">Confirm Password</label>
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
