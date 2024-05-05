<?php
session_start();

// Check if the user was redirected here from another page
if (isset($_SESSION['redirected']) && $_SESSION['redirected'] === true) {
    // Display the prompt
    $login_prompt = "Please log in to have administrator access.";
    // Unset the session variable to prevent the prompt from showing again on subsequent visits
    unset($_SESSION['redirected']);
} else {
    $login_prompt = ""; // No prompt
}

$login_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $mysqli = new mysqli('localhost', 'root', '', 'streetkohi');
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $username = $mysqli->real_escape_string($username);
    
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $mysqli->query($query);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {

            $_SESSION['admin_user'] = $row['id'];
            header("Location: ../admin/dashboard/dashboard.php"); 
            exit();
        } else {
            $login_error = "Invalid password.";
        }
    } else {
        $login_error = "Invalid username.";
    }

    $mysqli->close();
}

// Check if the user was redirected from another page and set the session variable
if(isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'dashboard.php') === false) {
    $_SESSION['redirected'] = true;
}
?>


<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/loginAdmin.css" />
    <title>Admin Login</title>
    <link rel="icon" type="image/x-icon" href="../assets/images/SK-Icon.png">
</head>
<body>
<div class="login-container">
    <div class="login-image">
        <img src="../assets/images/CUP4_CLR.png" alt="Street Kohi Cup" />
    </div>
    <div class="login-form">
        <img src="../assets/images/SK w_ Type Landscape.png" alt="Login Label" class="login-label" />
        <!-- Display login error message -->
        <?php if ($login_error): ?>
            <p class="error"><?php echo $login_error; ?></p>
        <?php endif; ?>
        <!-- Display login prompt if redirected from another page -->
        <?php if ($login_prompt): ?>
            <p class="prompt"><?php echo $login_prompt; ?></p>
        <?php endif; ?>
        <form method="POST">
            <div class="input-container">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" />

                <label for="password">Password</label>
                <input type="password" id="user_password" name="password" />

                <div class="forgot-password-container">
                    <a href="../forgotpass/forgotpass.php" class="forgot-password">Forgot Password?</a>
                </div>
            </div>

            <button name="Submit" type="submit">Log In</button>
        </form>
    </div>
</div>
</body>
</html>
