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

// Check if arch_coffee_id is set in the POST request
if (isset($_POST['arch_coffee_id'])) {
    $arch_coffee_id = mysqli_real_escape_string($con, $_POST['arch_coffee_id']);
    
    // Prepare the fetch query for the archive_coffee record
    $stmt_fetch = mysqli_prepare($con, "SELECT * FROM `archive_coffee` WHERE arch_coffee_id = ?");
    mysqli_stmt_bind_param($stmt_fetch, "i", $arch_coffee_id);
    mysqli_stmt_execute($stmt_fetch);
    $result_fetch = mysqli_stmt_get_result($stmt_fetch);
    
    // Check if the record exists
    if ($result_fetch && mysqli_num_rows($result_fetch) > 0) {
        $coffee_data = mysqli_fetch_assoc($result_fetch);
        
        // Insert the record back into the coffee table
        $stmt_insert = mysqli_prepare($con, "INSERT INTO `coffee` (
            coffee_id, coffee_name, coffee_description, coffee_stock, coffee_price, coffee_image, coffee_tag
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?
        )");
        
        mysqli_stmt_bind_param(
            $stmt_insert,
            "issiiss",
            $coffee_data['arch_coffee_id'],
            $coffee_data['arch_coffee_name'],
            $coffee_data['arch_coffee_description'],
            $coffee_data['arch_coffee_stock'],
            $coffee_data['arch_coffee_price'],
            $coffee_data['arch_coffee_image'],
            $coffee_data['arch_coffee_tag']
        );
        
        // Execute the insert query
        if (mysqli_stmt_execute($stmt_insert)) {
            // Delete the record from archive_coffee
            $stmt_delete = mysqli_prepare($con, "DELETE FROM `archive_coffee` WHERE arch_coffee_id = ?");
            mysqli_stmt_bind_param($stmt_delete, "i", $arch_coffee_id);
            mysqli_stmt_execute($stmt_delete);
            
            // Redirect to productsDashboard.php
            header("Location: productsDashboard.php");
            exit;
        } else {
            // Output error message if insert fails
            die("Failed to insert coffee data back to the coffee table: " . mysqli_error($con));
        }
    } else {
        // Output error message if the archive coffee record is not found
        die("Failed to find archive coffee data.");
    }
}

// Close the prepared statements
mysqli_stmt_close($stmt_fetch);
mysqli_stmt_close($stmt_insert);
mysqli_stmt_close($stmt_delete);
?>
