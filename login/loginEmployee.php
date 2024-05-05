<?php
session_start();

// Check if the user was redirected here from another page
if (isset($_SESSION['redirected']) && $_SESSION['redirected'] === true) {
    // Display the prompt
    $login_prompt = "Please log in to have employee access.";
    // Unset the session variable to prevent the prompt from showing again on subsequent visits
    unset($_SESSION['redirected']);
} else {
    $login_prompt = ""; // No prompt
}

$login_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $mysqli = new mysqli('localhost', 'root', '', 'pos_system');
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $username = $mysqli->real_escape_string($username);
    
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $mysqli->query($query);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $_SESSION['employee_user'] = $row['id'];
        header("Location: ../admin_pos/pos.php"); 
        exit();
    } else {
        $login_error = "Invalid username or password.";
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
    <link rel="stylesheet" href="../css/loginEmployee.css" />
    <title>Employee Login</title>
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
            </div>

            <button name="Submit" type="submit">Log In</button>
        </form>
    </div>
</div>
</body>
</html>
