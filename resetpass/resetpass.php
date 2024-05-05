<?php
session_start();

if (!isset($_SESSION['security_questions_answered'])) {
    // Redirect the user to the forgotpass.php page
    header("Location: ../forgotpass/forgotpass.php");
    exit();
}


// Include the database connection code here
$servername = "localhost";
$username = "root";
$password = "";
$database = "streetkohi";

// Create connection
$mysqli = new mysqli($servername, $username, $password, $database);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

function resetLoginAttempts($username, $mysqli) {
    $username = $mysqli->real_escape_string($username);
    $query_reset_attempts = "UPDATE users SET login_attempts = 0 WHERE username = '$username'";
    $mysqli->query($query_reset_attempts);
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $reset_error = "Passwords do not match.";
    } elseif (!validatePassword($new_password)) {
        $reset_error = "Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
    } else {
        $username = $_SESSION['reset_username'];
        
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        $sql = "UPDATE users SET password = ? WHERE username = ?";
        
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ss", $hashed_password, $username);
        
        if ($stmt->execute()) {
            $reset_success = "Password reset successful.";
            // Reset the login attempt counter for the user
            resetLoginAttempts($username, $mysqli);
        } else {
            $reset_error = "Error updating password: " . $mysqli->error;
        }
        
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/resetpass.css">
    <title>Password Reset</title>
    <link rel="icon" type="image/x-icon" href="../assets/images/SK-Icon.png">
</head>
<body>
    <div class="reset-container">
                <div class="reset-image">
            <img src="../assets/images/CUP4_CLR.png" alt="Street Kohi Cup">
        </div>
        <div class="reset-form">
        <img src="../assets/images/SK w_ Type Landscape.png" alt="Rest Password Label" class="reset-label">
            <div class="input-container">
            <p>Password Reset<p>
            <?php if (isset($reset_error)): ?>
                <p class="error"><?php echo $reset_error; ?></p>
                <button class="try-again-btn" onclick="window.location.href = 'resetpass.php'">Try Again</button>
            <?php elseif (isset($reset_success)): ?>
                <p class="success"><?php echo $reset_success; ?></p>
                <button class="back-to-login-btn" onclick="window.location.href='../login/login.php'">Back to Login</button>
            <?php else: ?>
                <form method="POST" action="">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password">

                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password">

                    <button type="submit" name="submit">Reset Password</button>
                </form>
            <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
