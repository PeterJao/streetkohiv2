<?php
// Include the database connection
include 'connect.php';

// Check if archevent_id parameter is set in the URL
if (isset($_GET['archevent_id'])) {
    $archevent_id = intval($_GET['archevent_id']);

    // Delete the archived event from the archevent table
    $sql_delete_archevent = "DELETE FROM archevent WHERE archevent_id = $archevent_id";
    mysqli_query($con, $sql_delete_archevent);

    // Redirect back to the Manage Archives page
    header("Location: archiveEvents.php");
    exit;
} else {
    // Invalid request
    echo "Invalid request.";
}
?>
