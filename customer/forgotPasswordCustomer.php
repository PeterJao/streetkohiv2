<?php
require '../vendor/autoload.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';
require '../vendor/phpmailer/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Initialize an array to store validation errors and success message
$errors = [];
$success = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate email
    $email = $_POST['email'];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    } else {
        // Connect to the database
        $mysqli = new mysqli("localhost", "root", "", "streetkohi");

        // Check connection
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Check if the email exists in the database
        $sql_check_email = "SELECT * FROM users_customer WHERE email = ?";
        $stmt = $mysqli->prepare($sql_check_email);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user) {
            // Generate reset password token
            $token = bin2hex(random_bytes(32));

            // Store token in the database
            $timestamp = time();
            $expiry = $timestamp + 3600; // Token expires in 1 hour
            $sql_insert_token = "INSERT INTO reset_tokens (user_id, token, expiry) VALUES (?, ?, ?)";
            $stmt = $mysqli->prepare($sql_insert_token);
            $stmt->bind_param("iss", $user['id'], $token, $expiry);
            $stmt->execute();
            $stmt->close();

            // Send reset password email
            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'streetkohiweb@gmail.com'; // SMTP username
                $mail->Password = 'gzkgydkrgoympdva'; // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                //Recipients
                $mail->setFrom('streetkohiweb@gmail.com', 'StreetKohi');
                $mail->addAddress($email); // User email

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Reset Your Password';
                $mail->Body = "Click the following link to reset your password: <a href='http://localhost/StreetKohi/customer/resetPasswordCustomer.php?token=$token'>Reset Password</a>";

                $mail->send();
                $success = "Reset password instructions have been sent to your email.";
            } catch (Exception $e) {
                $errors[] = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            $errors[] = "Email not found";
        }

        // Close connection
        $mysqli->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" type="text/css" href="../css/forgotPasswordCustomer.css">
    <link rel="icon" type="image/x-icon" href="../assets/images/SK-Icon.png">
</head>
<body>

<!-- Header -->
<div>
        <?php include "../header/header.php" ?>
    </div>
    
    <!-- Card -->
    <div class="main-content">

    <div class="card" id="forgotPopup">
    <h1>Forgot Password</h1>
    <h4>Please enter your email for password reset.</h4>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input class="email-field" type="email" name="email" required>
        <button class="submit-btn" type="submit">Submit</button>
    </form>
    <!-- Error messages -->
    <?php if (!empty($errors)) : ?>
        <?php foreach ($errors as $error) : ?>
            <p><?php echo $error; ?></p>
        <?php endforeach; ?>
    <?php endif; ?>
    <!-- Success message -->
    <?php if ($success) : ?>
        <p class="success-message"><?php echo $success; ?></p>
    <?php endif; ?>
</div>
    </div>

<script>
    // JavaScript to show the popup container
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("forgotPopup").style.display = "block";
    });
</script>

<div>
      <?php include "../footer/footer.php" ?>
</div>

</body>
</html>