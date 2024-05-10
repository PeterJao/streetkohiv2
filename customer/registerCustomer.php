<?php
require '../vendor/autoload.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';
require '../vendor/phpmailer/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Initialize an array to store validation errors
$errors = [];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate each form field
    if (empty($_POST['first_name'])) {
        $errors[] = "First Name is required";
    }
    if (empty($_POST['last_name'])) {
        $errors[] = "Last Name is required";
    }
    if (empty($_POST['street'])) {
        $errors[] = "Street is required";
    }
    if (empty($_POST['barangay'])) {
        $errors[] = "Barangay is required";
    }
    if (empty($_POST['city'])) {
        $errors[] = "City is required";
    }
    if (empty($_POST['zip_code'])) {
        $errors[] = "Zip Code is required";
    } elseif (!preg_match("/^\d+$/", $_POST['zip_code'])) {
        $errors[] = "Zip Code must contain only digits";
    }
    if (empty($_POST['contact_number'])) {
        $errors[] = "Contact Number is required";
    } elseif (!preg_match("/^09[0-9]{9}$/", $_POST['contact_number'])) {
        $errors[] = "Please enter a valid mobile number.";
    } else {
        // Check if the contact number already exists in the database
        $contact_number = $_POST['contact_number'];
        $mysqli = new mysqli("localhost", "root", "", "streetkohi");
        $sql_check_contact = "SELECT * FROM users_customer WHERE contact_number = '$contact_number'";
        $result = $mysqli->query($sql_check_contact);
        if ($result->num_rows > 0) {
            $errors[] = "This contact number is already registered!";
        }
    }
    if (empty($_POST['email'])) {
        $errors[] = "Email is required";
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    } else {
        // Check if the email already exists in the database
        $email = $_POST['email'];
        $mysqli = new mysqli("localhost", "root", "", "streetkohi");
        $sql_check_email = "SELECT * FROM users_customer WHERE email = '$email'";
        $result = $mysqli->query($sql_check_email);
        if ($result->num_rows > 0) {
            $errors[] = "An account with this email already exists!";
        }
    }
    if (empty($_POST['password'])) {
        $errors[] = "Password is required";
    } elseif (strlen($_POST['password']) < 8) {
        $errors[] = "Password must be at least 8 characters long";
    } elseif (!preg_match("/[a-z]/", $_POST['password']) || !preg_match("/[A-Z]/", $_POST['password']) || !preg_match("/\d/", $_POST['password']) || !preg_match("/[^a-zA-Z\d]/", $_POST['password'])) {
        $errors[] = "Password must contain at least one uppercase letter, one lowercase letter, one special character and one number";
    } elseif ($_POST['password'] !== $_POST['confirm_password']) {
        $errors[] = "Passwords do not match";
    }

    // If no validation errors, proceed with registration
    if (empty($errors)) {
        // Establish connection to MySQL
        $mysqli = new mysqli("localhost", "root", "", "streetkohi");

        // Check connection
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

    // Generate verification code and get the current timestamp
    $verification_code = bin2hex(random_bytes(16)); // Generate a random 32-character hexadecimal string
    $timestamp = time(); // Get the current Unix timestamp

        // Get form data
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $street = $_POST['street'];
        $barangay = $_POST['barangay'];
        $city = $_POST['city'];
        $zip_code = $_POST['zip_code'];
        $contact_number = $_POST['contact_number'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

        // Update verification code and timestamp in the database
        $sql_update_code = "UPDATE users_customer SET verification_code = '$verification_code', verification_timestamp = '$timestamp' WHERE email = '$email'";
        if ($mysqli->query($sql_update_code) === TRUE) {
            // Prepare SQL statement
            $sql = "INSERT INTO users_customer (first_name, last_name, street, barangay, city, zip_code, contact_number, email, password, verification_code, verified) 
                    VALUES ('$first_name', '$last_name', '$street', '$barangay', '$city', '$zip_code', '$contact_number', '$email', '$password', '$verification_code', 0)";

            // Execute SQL statement
            if ($mysqli->query($sql) === TRUE) {
                // Send verification email
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
                    $mail->addAddress($_POST['email']); // User email

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Email Verification';
                    $mail->Body = 'Please click the following link to verify your email: <a href="http://localhost/StreetKohi/customer/verify.php?code=' . $verification_code . '">Verify Email</a>';

                    $mail->send();
                    $registration_success = "Registration successful! Please check your email to verify your account.";
                } catch (Exception $e) {
                    $errors[] = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            } else {
                $errors[] = "Error: " . $sql . "<br>" . $mysqli->error;
            }
        } else {
            $errors[] = "Error updating verification code and timestamp: " . $mysqli->error;
        }

        // Close connection
        $mysqli->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Form</title>
    <link rel="stylesheet" type="text/css" href="../css/registerCustomer.css">
    <link rel="icon" type="image/x-icon" href="../assets/images/SK-Icon.png">
</head>
<body>

  <!-- Header -->
<div>
    <?php include "../header/header.php" ?>
</div>

<div class="main-content">
<div class="card" id="registrationPopup">

<h2>Register</h2>

    <!-- Registration form -->
    <form class="reg-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="text" name="first_name" placeholder="First Name" required>
        <input type="text" name="last_name" placeholder="Last Name" required>
        <input type="text" name="street" placeholder="Street" required>
        <input type="text" name="barangay" placeholder="Barangay" required>
        <input type="text" name="city" placeholder="City" required>
        <input type="text" name="zip_code" placeholder="Zip Code" required>
        <input type="text" name="contact_number" placeholder="Contact Number" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button type="submit">Register</button>
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
    <!-- Registration success message -->
    <?php
    if (isset($registration_success)) {
        echo "<p>$registration_success</p>";
    }
    ?>
     <p>Already have an account? <a href="loginCustomer.php" class="link" >Log in here</a></p>
</div>
</div>

<script>
    // JavaScript to show popup
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("registrationPopup").style.display = "block";
    });
</script>

<div>
      <?php include "../footer/footer.php" ?>
</div>

</body>
</html>