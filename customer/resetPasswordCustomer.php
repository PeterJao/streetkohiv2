<?php
session_start(); // Start the session (if not already started)

require '../vendor/autoload.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';
require '../vendor/phpmailer/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$errors = [];
$showLoginButton = false; // Initialize flag for login button visibility

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form fields
    if (empty($_POST['password'])) {
        $errors[] = "Password is required";
    } elseif (strlen($_POST['password']) < 8) {
        $errors[] = "Password must be at least 8 characters long";
    } elseif (!preg_match("/[a-z]/", $_POST['password']) || !preg_match("/[A-Z]/", $_POST['password']) || !preg_match("/\d/", $_POST['password']) || !preg_match("/[^a-zA-Z\d]/", $_POST['password'])) {
        $errors[] = "Password must contain at least one uppercase letter, one lowercase letter, one special character, and one number";
    } elseif ($_POST['password'] !== $_POST['confirm_password']) {
        $errors[] = "Passwords do not match";
    }

    if (empty($_GET['token'])) {
        $errors[] = "Please send a new forgot password request";
    }

    if (empty($errors)) {
        // Connect to the database
        $mysqli = new mysqli("localhost", "root", "", "streetkohi");

        // Check connection
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Check if the reset token exists in the database
        $token = $_GET['token'];
        $sql_check_token = "SELECT * FROM reset_tokens WHERE token = ?";
        $stmt = $mysqli->prepare($sql_check_token);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        $resetToken = $result->fetch_assoc();
        $stmt->close();

        if ($resetToken) {
            // Token is valid, update the user's password
            $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $userId = $resetToken['user_id'];
            $sql_update_password = "UPDATE users_customer SET password = ? WHERE id = ?";
            $stmt = $mysqli->prepare($sql_update_password);
            $stmt->bind_param("si", $newPassword, $userId);
            $stmt->execute();
            $stmt->close();

            // Remove the used reset token from the database
            $sql_delete_token = "DELETE FROM reset_tokens WHERE token = ?";
            $stmt = $mysqli->prepare($sql_delete_token);
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $stmt->close();

            // Reset login attempt count
            if (isset($_SESSION['login_attempts'])) {
                $_SESSION['login_attempts'] = 0;
            }

            // Set success message
            $success = "Password reset successful!";
            $showLoginButton = true; // Flag to show the login button
        } else {
            $errors[] = "Please send a new forgot password request";
        }

        // Close connection
        $mysqli->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="icon" type="image/x-icon" href="../assets/images/SK-Icon.png">
    <link rel="stylesheet" type="text/css" href="../css/resetPasswordCustomer.css">
</head>
<body>

<!-- Header -->
<div>
    <?php include "../header/header.php" ?>
</div>

<div class="main-content">
<?php if (!$showLoginButton): ?>
    <div class="card" id="resetPasswordCard">
        <!-- Reset password form -->
        <h1>Reset Password</h1>
        <form action="<?php echo $_SERVER['PHP_SELF'] . '?token=' . $_GET['token']; ?>" method="post">
            <input class="pw-field" type="password" name="password" placeholder="New Password" required>
            <input class="pw-field" type="password" name="confirm_password" placeholder="Confirm Password" required>
            <div class="form-group">
                <button class="submit-btn" type="submit">Reset Password</button>
            </div>
        </form>
        <!-- Error messages -->
        <?php
        if (!empty($errors)) {
            echo "<div class='error-messages'>";
            foreach ($errors as $error) {
                echo "<p>$error</p>";
            }
            echo "</div>";
        }
        ?>
    </div>
<?php endif; ?>

<!-- Success message and login button -->
<?php if (isset($success)): ?>
    <div class="card" id="loginCard">
        <h1>Password Reset</h1>
        <p><?php echo $success; ?></p>
        <div class="form-group">
            <a href='loginCustomer.php'><button class='submit-btn'>Proceed to Login</button></a>
        </div>
    </div>
<?php endif; ?>
</div>


<script>
    // JavaScript to show card
    document.addEventListener("DOMContentLoaded", function() {
        if (!<?php echo $showLoginButton ? 'true' : 'false'; ?>) {
            document.getElementById("resetPasswordCard").style.display = "block";
        } else {
            document.getElementById("loginCard").style.display = "block";
        }
    });
</script>

<!-- Footer -->
<div>
    <?php include "../footer/footer.php" ?>
</div>

</body>
</html>
