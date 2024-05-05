<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <link rel="icon" type="image/x-icon" href="../assets/images/SK-Icon.png">
    <link rel="stylesheet" type="text/css" href="../css/verify.css">
</head>
<body>

<!-- Header -->
<div>
    <?php include "../header/header.php" ?>
</div>

<div class="main-content">
    <div class="card">
        <?php
        require '../vendor/autoload.php';
        require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
        require '../vendor/phpmailer/phpmailer/src/SMTP.php';
        require '../vendor/phpmailer/phpmailer/src/Exception.php';

        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\SMTP;
        use PHPMailer\PHPMailer\Exception;

        // Establish connection to MySQL
        $mysqli = new mysqli("localhost", "root", "", "streetkohi");

        // Check connection
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Check if verification code is provided in the URL
        if (isset($_GET['code'])) {
            $verification_code = $_GET['code'];

            // Get the current timestamp
            $current_timestamp = time();

            // Define expiration period (24 hours)
            $expiration_period = 24 * 60 * 60; // 24 hours in seconds

            // Calculate the timestamp 24 hours ago
            $expiration_timestamp = $current_timestamp - $expiration_period;

            // Check if the verification code is still valid (within the 24-hour window)
            $sql_check_code = "SELECT * FROM users_customer WHERE verification_code = '$verification_code' AND verification_timestamp >= FROM_UNIXTIME($expiration_timestamp)";
            $result = $mysqli->query($sql_check_code);

            if ($result->num_rows > 0) {
                // Verification code is valid within the expiration period, update 'verified' column in the database
                $sql_update_verified = "UPDATE users_customer SET verified = 1 WHERE verification_code = '$verification_code'";
                if ($mysqli->query($sql_update_verified) === TRUE) {
                    echo "<h1>Email verified successfully.</h1>";
                    echo '<a href="loginCustomer.php" class="login-button">Login</a>';
                } else {
                    echo "<h2>Error updating record: " . $mysqli->error . "</h2>";
                }
            } else {
                echo "<h2>Verification code expired or invalid.</h2>";
            }
        } else {
            echo "<h2>Verification code not provided.</h2>";
        }

        // Close connection
        $mysqli->close();
        ?>
    </div>
</div>

<!-- Footer -->
<div>
    <?php include "../footer/footer.php" ?>
</div>

</body>
</html>
