<?php
// Start the session
session_start();

// Include database connection
include('connect.php');

// Check if the user is logged in
if (!isset($_SESSION['admin_user'])) {
    header("Location: ../../login/login.php");
    exit;
}

// Check if arch_id is set in the POST request
if (isset($_POST['arch_id'])) {
    $arch_id = mysqli_real_escape_string($con, $_POST['arch_id']);
    
    // Prepare the fetch query for the archive_user record
    $stmt_fetch = mysqli_prepare($con, "SELECT * FROM `archive_users` WHERE arch_id = ?");
    mysqli_stmt_bind_param($stmt_fetch, "i", $arch_id);
    mysqli_stmt_execute($stmt_fetch);
    $result_fetch = mysqli_stmt_get_result($stmt_fetch);
    
    // Check if the record exists
    if ($result_fetch && mysqli_num_rows($result_fetch) > 0) {
        $archUser_data = mysqli_fetch_assoc($result_fetch);
        
        // Insert the record back into the users table
        $stmt_insert = mysqli_prepare($con, "INSERT INTO `users` (username, password) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt_insert, "ss", $archUser_data['arch_username'], $archUser_data['arch_password']);
        
        // Execute the insert query
        if (mysqli_stmt_execute($stmt_insert)) {
            // Delete the record from archive_users
            $stmt_delete = mysqli_prepare($con, "DELETE FROM `archive_users` WHERE arch_id = ?");
            mysqli_stmt_bind_param($stmt_delete, "i", $arch_id);
            mysqli_stmt_execute($stmt_delete);
            
            // Redirect to employee.php
            header("Location: employee.php");
            exit;
        } else {
            // Output error message if insert fails
            die("Failed to insert employee data back to the employee table: " . mysqli_error($con));
        }
    } else {
        // Output error message if the archive user record is not found
        die("Failed to find archive employee data.");
    }
}

// Close the prepared statements
mysqli_stmt_close($stmt_fetch);
mysqli_stmt_close($stmt_insert);
mysqli_stmt_close($stmt_delete);
?>
