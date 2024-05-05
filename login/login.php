<?php
session_start();

$login_error = "";
$login_prompt = "";
$warning_message = "";

// Redirect if user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: ../admin/dashboard/dashboard.php");
    exit();
}

// Automated unlocking mechanism for both admin and employee accounts
$mysqli_auto_unlock_admin = new mysqli('localhost', 'root', '', 'streetkohi');
$mysqli_auto_unlock_employee = new mysqli('localhost', 'root', '', 'pos_system');

if ($mysqli_auto_unlock_admin->connect_error || $mysqli_auto_unlock_employee->connect_error) {
    die("Connection failed: " . $mysqli_auto_unlock_admin->connect_error . " / " . $mysqli_auto_unlock_employee->connect_error);
}

$unlock_duration = 60; // 1 minute in seconds
$query_unlock_accounts_admin = "UPDATE streetkohi.users SET account_locked = 0 WHERE account_locked = 1 AND TIMESTAMPDIFF(SECOND, lockout_timestamp, NOW()) >= $unlock_duration";
$query_unlock_accounts_employee = "UPDATE pos_system.users SET account_locked = 0 WHERE account_locked = 1 AND TIMESTAMPDIFF(SECOND, lockout_timestamp, NOW()) >= $unlock_duration";

$mysqli_auto_unlock_admin->query($query_unlock_accounts_admin);
$mysqli_auto_unlock_employee->query($query_unlock_accounts_employee);

$mysqli_auto_unlock_admin->close();
$mysqli_auto_unlock_employee->close();

if (isset($_SESSION['redirected']) && $_SESSION['redirected'] === true) {
    $login_prompt = "Please log in to grant access.";
    unset($_SESSION['redirected']);
}

$max_login_attempts = 3;
$initial_lockout_duration = 60; // 1 minute in seconds
$lockout_duration_multiplier = 2;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $mysqli_admin = new mysqli('localhost', 'root', '', 'streetkohi');
    $mysqli_employee = new mysqli('localhost', 'root', '', 'pos_system');

    if ($mysqli_admin->connect_error || $mysqli_employee->connect_error) {
        die("Connection failed: " . $mysqli_admin->connect_error . " / " . $mysqli_employee->connect_error);
    }

    $username = $mysqli_admin->real_escape_string($username);

    if (!empty($username)) {
        // Check if the username is provided to avoid unnecessary login attempts
        $query_admin = "SELECT * FROM users WHERE username = '$username'";
        $result_admin = $mysqli_admin->query($query_admin);

        if ($result_admin->num_rows == 1) {
            $row = $result_admin->fetch_assoc();
            if ($row['account_locked'] == 1) {
                // Account is locked, display appropriate message
                $login_error = "Your account is locked. Please try again later.";
            } elseif (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['admin_user'] = true;
                // Reset login attempt counter on successful login
                $query_reset_attempts = "UPDATE users SET login_attempts = 0 WHERE username = '$username'";
                $mysqli_admin->query($query_reset_attempts);
                header("Location: ../admin/dashboard/dashboard.php");
                exit();
            } else {
                // Incorrect password, handle accordingly
                // Set session variable indicating wrong password
                $_SESSION['wrong_password'] = true;

                $query_update_attempts = "UPDATE users SET login_attempts = login_attempts + 1 WHERE username = '$username'";
                $mysqli_admin->query($query_update_attempts);

                // Check if maximum login attempts reached
                if ($row['login_attempts'] >= $max_login_attempts - 1) {
                    // Calculate lockout duration
                    $lockout_duration = $initial_lockout_duration * (pow($lockout_duration_multiplier, $row['login_attempts'] - ($max_login_attempts - 1)));

                    // Set account locked status
                    $query_lock_account = "UPDATE users SET account_locked = 1, lockout_timestamp = NOW() WHERE username = '$username'";
                    $mysqli_admin->query($query_lock_account);

                    // Redirect only admins to the "Forgot Password" page
                    if ($row['account_type'] == 'admin') {
                        $warning_message = "Access to your account has been restricted. Please try again later. <a href='../forgotpass/forgotpass.php' style='text-decoration: underline; font-weight: bold;'>Forgot Password?</a>";
                    } else {
                        // Prompt employees to contact the administrator
                        $warning_message = "Your account is locked. Please contact the administrator to regain access.";
                    }
                } elseif ($row['login_attempts'] == $max_login_attempts - 2) {
                    $warning_message = "One attempt remaining before account lock.";
                } else {
                    $login_error = "Invalid password.";
                }
            }
        } else {
            $query_employee = "SELECT * FROM users WHERE username = '$username'";
            $result_employee = $mysqli_employee->query($query_employee);
        
            if ($result_employee->num_rows == 1) {
                $row = $result_employee->fetch_assoc();
                if ($row['account_locked'] == 1) {
                    // Account is locked, display appropriate message
                    $login_error = "Your account is locked. Please try again later.";
                } elseif ($password === $row['password']) {
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['employee_user'] = true;
                    // Reset login attempt counter on successful login
                    $query_reset_attempts = "UPDATE users SET login_attempts = 0 WHERE username = '$username'";
                    $mysqli_employee->query($query_reset_attempts);
                    header("Location: ../admin_pos/pos.php");
                    exit();
                } else {
                    $query_update_attempts = "UPDATE users SET login_attempts = login_attempts + 1 WHERE username = '$username'";
                    $mysqli_employee->query($query_update_attempts);
                    // Check if maximum login attempts reached
                    if ($row['login_attempts'] >= $max_login_attempts - 1) {
                        // Calculate lockout duration
                        $lockout_duration = $initial_lockout_duration * (pow($lockout_duration_multiplier, $row['login_attempts'] - ($max_login_attempts - 1)));
                        // Set account locked status
                        $query_lock_account = "UPDATE users SET account_locked = 1, lockout_timestamp = NOW() WHERE username = '$username'";
                        $mysqli_employee->query($query_lock_account);
                        // Prompt employees to contact the administrator
                        $warning_message = "Your account is locked. Please try again later.";
                    } elseif ($row['login_attempts'] == $max_login_attempts - 2) {
                        $warning_message = "One attempt remaining before account lockout.";
                    } else {
                        $login_error = "Invalid password.";
                    }
                }
            } else {
                $login_error = "Invalid username.";
            }
        }
    } else {
        // Username field is empty, do nothing
    }
    $mysqli_admin->close();
    $mysqli_employee->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login.css">
    <title>Login Page</title>
    <link rel="icon" type="image/x-icon" href="../assets/images/SK-Icon.png">
    <style>
        .warning {
            color: red; /* Red color for the warning message */
            font-size: 14px; /* Adjust font size */
            margin-top: 5px; /* Add some margin from the elements above */
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-image">
            <img src="../assets/images/CUP4_CLR.png" alt="Street Kohi Cup">
        </div>
        <div class="login-form">
            <img src="../assets/images/SK w_ Type Landscape.png" alt="Login Label" class="login-label">
            <!-- Display login error message -->
            <?php if ($login_error): ?>
                <p class="error"><?php echo $login_error; ?></p>
            <?php endif; ?>
            <!-- Display login prompt if redirected from another page -->
            <?php if ($login_prompt): ?>
                <p class="prompt"><?php echo $login_prompt; ?></p>
            <?php endif; ?>
            <!-- Display warning message if login attempt count is at 4 -->
            <?php if ($warning_message): ?>
                <p class="warning"><?php echo $warning_message; ?></p>
            <?php endif; ?>
            <!-- Display password input fields if account is not locked -->
            <?php if (!$warning_message || (strpos($warning_message, "One attempt remaining before account lock.") !== false && isset($row) && $row['account_type'] == 'admin')): ?>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="input-container">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" required>

                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <button name="Submit" type="submit">Log In</button>
                </form>
            <?php else: ?>
                <!-- Only display the input fields for employees -->
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="input-container">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" required>

                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <button name="Submit" type="submit">Log In</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
