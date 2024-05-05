<?php
session_start();

// Redirect if the user is not redirected from login.php due to wrong password
if (!isset($_SESSION['wrong_password'])) {
    header("Location: ../login/login.php");
    exit();
}

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


// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $security_answer_1 = strtolower($_POST['security_answer_1']);
    $security_answer_2 = strtolower($_POST['security_answer_2']);
    $security_answer_3 = strtolower($_POST['security_answer_3']);

    // Prepare SQL query to retrieve security answers from the database
    $sql = "SELECT security_answer_1, security_answer_2, security_answer_3 FROM users WHERE username = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $username);
    $username = 'admin@streetkohi';
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Convert database answers to lowercase for consistent comparison
        $row['security_answer_1'] = strtolower($row['security_answer_1']);
        $row['security_answer_2'] = strtolower($row['security_answer_2']);
        $row['security_answer_3'] = strtolower($row['security_answer_3']);

        // Check if answers match, ignoring case
        if ($security_answer_1 === $row['security_answer_1'] && $security_answer_2 === $row['security_answer_2'] && $security_answer_3 === $row['security_answer_3']) {
            // Answers match, proceed with password reset
            $_SESSION['reset_username'] = 'admin@streetkohi';
            $_SESSION['security_questions_answered'] = true; // Set session variable indicating security questions were answered
            header("Location: ../resetpass/resetpass.php");
            exit();
        } else {
            // Answers do not match, redirect back to forgot password page with error message
            header("Location: forgotpass.php?error=1");
            exit();
        }
    } else {
        // No user found, redirect to login page
        header("Location: ../login/login.php");
        exit();
    }

    $stmt->close();
}

$mysqli->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/forgotpass.css">
    <title>Forgot Password</title>
    <link rel="icon" type="image/x-icon" href="../assets/images/SK-Icon.png">
</head>
<body>
    <div class="forgot-container">
        <div class="forgot-image">
            <img src="../assets/images/CUP4_CLR.png" alt="Street Kohi Cup">
        </div>
        <div class="forgot-form">
            <img src="../assets/images/SK w_ Type Landscape.png" alt="Forgot Password Label" class="forgot-label">
            <div class="input-container">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <p>Please answer the security questions below to proceed with the password reset:</p>

                    <label for="security_answer_1">What is the name of your first dog?</label>
                    <input type="text" id="security_answer_1" name="security_answer_1">

                    <label for="security_answer_2">What is your first specialty drink?</label>
                    <input type="text" id="security_answer_2" name="security_answer_2">

                    <label for="security_answer_3">Who are you?</label>
                    <input type="text" id="security_answer_3" name="security_answer_3">

                    <button type="submit" name="submit">Submit Answers</button>
                </form>
                <?php

                if (isset($_GET["error"]) && $_GET["error"] == 1) {
                    echo "<p class='error'>Incorrect security answers. Please try again.</p>";
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
