<?php
include('connect.php');

// Start the session
session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['admin_user'])) {
    header("Location: ../../login/login.php");
    exit;
}

if (isset($_GET['deleteid'])) {
    $id = $_GET['deleteid'];

    // Retrieve employee data before deleting
    $sql_select_employee = "SELECT * FROM `pos_system`.`users` WHERE id = $id";
    $result_select_employee = mysqli_query($con, $sql_select_employee);
    
    if ($result_select_employee && mysqli_num_rows($result_select_employee) > 0) {
        // Fetch employee data
        $employee_data = mysqli_fetch_assoc($result_select_employee);
        
        // Insert the employee data into the archive table
        $stmt_insert_archive = mysqli_prepare($con, "INSERT INTO `archive_users` (arch_username, arch_password) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt_insert_archive, "ss", $employee_data['username'], $employee_data['password']);
        mysqli_stmt_execute($stmt_insert_archive);
        
        // Check if insertion into archive table was successful
        if (mysqli_stmt_affected_rows($stmt_insert_archive) > 0) {
            // Delete the employee record from the users table
            $sql_delete_employee = "DELETE FROM `pos_system`.`users` WHERE id = $id";
            $result_delete_employee = mysqli_query($con, $sql_delete_employee);
            
            if ($result_delete_employee) {
                header('location: employee.php');
                exit; // Make sure to exit after redirecting
            } else {
                die(mysqli_error($con));
            }
        } else {
            // Output error message if insertion into archive table fails
            die("Failed to archive employee data.");
        }
    } else {
        // Output error message if employee data not found
        die("Employee data not found.");
    }
}
?>
