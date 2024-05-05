<?php
// Start the session
session_start();

// Include database connection
include('connect.php');

// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION['admin_user'])) {
    header("Location: ../../login/login.php");
    exit;
}

// Check if the deleteid and page_event parameters are set in the URL
if (isset($_GET['deleteid']) && isset($_GET['page_event'])) {
    // Sanitize the input to prevent SQL injection
    $event_id = mysqli_real_escape_string($con, $_GET['deleteid']);
    $page_event = mysqli_real_escape_string($con, $_GET['page_event']);

    // Start a transaction
    mysqli_begin_transaction($con, MYSQLI_TRANS_START_READ_WRITE);
    
    // Prepare the fetch query
    $stmt_fetch = mysqli_prepare($con, "SELECT * FROM `event` WHERE event_id = ?");
    mysqli_stmt_bind_param($stmt_fetch, "i", $event_id);
    mysqli_stmt_execute($stmt_fetch);
    $result_fetch = mysqli_stmt_get_result($stmt_fetch);
    
    // Check if the query was successful and the event data exists
    if ($result_fetch && mysqli_num_rows($result_fetch) > 0) {
        // Fetch the event data as an associative array
        $event_data = mysqli_fetch_assoc($result_fetch);
        
        // Prepare the insert query for the archive_event table
        $stmt_archive = mysqli_prepare($con, "INSERT INTO `archive_event` (
            archevent_id,
            archevent_name,
            archevent_description,
            archevent_date,
            archevent_time,
            archevent_price,
            archevent_image,
            archevent_venue,
            archevent_link,
            archevent_tag
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        // Bind parameters for the archive_event insert query
        mysqli_stmt_bind_param($stmt_archive, "isssssssss",
            $event_id,
            $event_data['event_name'],
            $event_data['event_description'],
            $event_data['event_date'],
            $event_data['event_time'],
            $event_data['event_price'],
            $event_data['event_image'],
            $event_data['event_venue'],
            $event_data['event_link'],
            $event_data['event_tag'] // Add event_tag binding
        );
        
        // Execute the insert query for archive_event table
        if (mysqli_stmt_execute($stmt_archive)) {
            // Prepare the delete query for the event table
            $stmt_delete = mysqli_prepare($con, "DELETE FROM `event` WHERE event_id = ?");
            mysqli_stmt_bind_param($stmt_delete, "i", $event_id);
            
            // Execute the delete query for the event table
            if (mysqli_stmt_execute($stmt_delete)) {
                // Commit the transaction
                mysqli_commit($con);
                
                // Redirect back to the current events page and keep the page number
                header("Location: productsDashboard.php?page_event=" . $page_event);
                exit; // Make sure to exit after redirecting
            } else {
                // Rollback the transaction in case of failure
                mysqli_rollback($con);
                die("Failed to delete event data.");
            }
        } else {
            // Rollback the transaction in case of failure
            mysqli_rollback($con);
            die("Failed to archive event data.");
        }
    } else {
        // Rollback the transaction in case of failure
        mysqli_rollback($con);
        die("Failed to fetch event data or event not found.");
    }
    
    // Close the prepared statements
    mysqli_stmt_close($stmt_fetch);
    mysqli_stmt_close($stmt_archive);
    mysqli_stmt_close($stmt_delete);
} else {
    die("Event ID or page event number not specified.");
}

?>
