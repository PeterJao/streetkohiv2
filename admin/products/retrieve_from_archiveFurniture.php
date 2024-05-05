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

// Check if arch_furniture_id is set in the POST request
if (isset($_POST['arch_furniture_id'])) {
    $arch_furniture_id = mysqli_real_escape_string($con, $_POST['arch_furniture_id']);
    
    // Prepare the fetch query for the archive_furniture record
    $stmt_fetch = mysqli_prepare($con, "SELECT * FROM `archive_furniture` WHERE arch_furniture_id = ?");
    mysqli_stmt_bind_param($stmt_fetch, "i", $arch_furniture_id);
    mysqli_stmt_execute($stmt_fetch);
    $result_fetch = mysqli_stmt_get_result($stmt_fetch);
    
    // Check if the record exists
    if ($result_fetch && mysqli_num_rows($result_fetch) > 0) {
        $furniture_data = mysqli_fetch_assoc($result_fetch);
        
        // Insert the record back into the furniture table
        $stmt_insert = mysqli_prepare($con, "INSERT INTO `furniture` (
            furniture_id, furniture_name, furniture_description, furniture_link, furniture_image
        ) VALUES (
            ?, ?, ?, ?, ?
        )");
        
        mysqli_stmt_bind_param(
            $stmt_insert,
            "issss",
            $furniture_data['arch_furniture_id'],
            $furniture_data['arch_furniture_name'],
            $furniture_data['arch_furniture_description'],
            $furniture_data['arch_furniture_link'],
            $furniture_data['arch_furniture_image']
        );
        
        // Execute the insert query
        if (mysqli_stmt_execute($stmt_insert)) {
            // Delete the record from archive_furniture
            $stmt_delete = mysqli_prepare($con, "DELETE FROM `archive_furniture` WHERE arch_furniture_id = ?");
            mysqli_stmt_bind_param($stmt_delete, "i", $arch_furniture_id);
            mysqli_stmt_execute($stmt_delete);
            
            // Redirect to productsDashboard.php
            header("Location: productsDashboard.php");
            exit;
        } else {
            // Output error message if insert fails
            die("Failed to insert furniture data back to the furniture table: " . mysqli_error($con));
        }
    } else {
        // Output error message if the archive furniture record is not found
        die("Failed to find archive furniture data.");
    }
}

// Close the prepared statements
mysqli_stmt_close($stmt_fetch);
mysqli_stmt_close($stmt_insert);
mysqli_stmt_close($stmt_delete);
?>
