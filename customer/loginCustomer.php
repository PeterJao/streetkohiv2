<?php
session_start(); // Start the session (if not already started)

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Connect to your database
    $mysqli = new mysqli("localhost", "root", "", "streetkohi");

    // Check connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Prepare SQL statement
    $sql = "SELECT * FROM users_customer WHERE email = '$email'";

    // Execute SQL statement
    $result = $mysqli->query($sql);

    // Check if user exists
    if ($result->num_rows > 0) {
        // User exists, verify password
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Check if user is verified
            if ($user['verified'] == 1) {
                // Password is correct and user is verified, log the user in
                $_SESSION['customer_id'] = $user['id']; // Store customer ID in session (you may store more user information)
                $_SESSION['login_attempts'] = 0; // Reset login attempt counter
                header("Location: ../landingpage/Landingpage.php"); // Redirect user to dashboard or any other page
                exit();
            } else {
                // User is not verified
                $login_error = "Your account is not verified. Please check your email for verification instructions.";
            }
        } else {
            // Password is incorrect
            $login_error = "Invalid email or password";
        }
    } else {
        // User does not exist
        $login_error = "Invalid email or password";
    }

    // Increment login attempt counter
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 1;
    } else {
        $_SESSION['login_attempts']++;
    }

    // Check if maximum login attempts reached
    if ($_SESSION['login_attempts'] >= 5) {
        header("Location: forgotPasswordCustomer.php");
        exit();
    } elseif ($_SESSION['login_attempts'] == 4) { // Add warning message for the 4th attempt
        $login_warning = "One attempt remaining before password reset.";
    }

    // Close connection
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="../css/loginCustomer.css">
    <link rel="icon" type="image/x-icon" href="../assets/images/SK-Icon.png">
</head>
<body>

  <!-- Header -->
  <div>
        <?php include "../header/header.php" ?>
    </div>
    
<div class="main-content">
<div class="card" id="loginPopup">
    <!-- Login form -->
    <h2>Login</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
        <a href="forgotPasswordCustomer.php" class="link">Forgot Password?</a>
        <div>
            Don't have an account? <a href="registerCustomer.php" class="link">Sign up</a>
        </div>
    </form>
    <!-- Error message -->
    <?php if (isset($login_error)): ?>
        <p class="error"><?php echo $login_error; ?></p>
    <?php endif; ?>
    <!-- Warning message -->
    <?php if (isset($login_warning)): ?>
        <p class="warning"><?php echo $login_warning; ?></p>
    <?php endif; ?>
</div>
</div>

<script>
    // JavaScript to show popup
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("loginPopup").style.display = "block";
    });
</script>

<div>
      <?php include "../footer/footer.php" ?>
</div>

</body>
</html>