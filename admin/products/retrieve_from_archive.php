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

// Check if archevent_id is set in the POST request
if (isset($_POST['archevent_id'])) {
    $archevent_id = mysqli_real_escape_string($con, $_POST['archevent_id']);
    
    // Prepare the fetch query for the archive_event record
    $stmt_fetch = mysqli_prepare($con, "SELECT * FROM `archive_event` WHERE archevent_id = ?");
    mysqli_stmt_bind_param($stmt_fetch, "i", $archevent_id);
    mysqli_stmt_execute($stmt_fetch);
    $result_fetch = mysqli_stmt_get_result($stmt_fetch);
    
    // Check if the record exists
    if ($result_fetch && mysqli_num_rows($result_fetch) > 0) {
        $event_data = mysqli_fetch_assoc($result_fetch);
        
        // Prepare the insert query for the event table
        $stmt_insert = mysqli_prepare($con, "INSERT INTO `event` (
            event_id, event_name, event_description, event_date, event_time, event_price,
            event_image, event_venue, event_link, event_tag
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        // Bind the parameters
        mysqli_stmt_bind_param(
            $stmt_insert,
            "issssissss",
            $event_data['archevent_id'],
            $event_data['archevent_name'],
            $event_data['archevent_description'],
            $event_data['archevent_date'],
            $event_data['archevent_time'],
            $event_data['archevent_price'],
            $event_data['archevent_image'],
            $event_data['archevent_venue'],
            $event_data['archevent_link'],
            $event_data['archevent_tag']
        );
        
        // Execute the insert query
        if (mysqli_stmt_execute($stmt_insert)) {
            // Prepare the delete query for the archive_event table
            $stmt_delete = mysqli_prepare($con, "DELETE FROM `archive_event` WHERE archevent_id = ?");
            mysqli_stmt_bind_param($stmt_delete, "i", $archevent_id);
            mysqli_stmt_execute($stmt_delete);
            
            // Redirect to productsDashboard.php
            header("Location: productsDashboard.php");
            exit;
        } else {
            // Output error message if insert fails
            die("Failed to insert event data back to the event table: " . mysqli_error($con));
        }
    } else {
        // Output error message if the archive event record is not found
        die("Failed to find archive event data.");
    }
}

// Close the prepared statements
mysqli_stmt_close($stmt_fetch);
mysqli_stmt_close($stmt_insert);
mysqli_stmt_close($stmt_delete);
?>
