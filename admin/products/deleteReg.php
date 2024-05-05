<?php
// Include database connection
include('connect.php');

// Get the archevent_id from the request
$reg_id = isset($_GET['reg_id']) ? intval($_GET['reg_id']) : 0;

if ($reg_id > 0) {
    // Prepare the delete query to delete the specific archive event
    $stmt_delete = mysqli_prepare($con, "DELETE FROM `event_registration` WHERE reg_id = ?");
    mysqli_stmt_bind_param($stmt_delete, "i", $reg_id);

    // Execute the delete query
    if (mysqli_stmt_execute($stmt_delete)) {
        // Optionally output a success message (not necessary since you're redirecting)
        // echo "Archive event deleted successfully.";
    } else {
        // Optionally output an error message if deletion fails (not necessary since you're redirecting)
        // echo "Failed to delete archive event: " . mysqli_error($con);
    }

    // Close the prepared statement
    mysqli_stmt_close($stmt_delete);
}

// Redirect to the archive page
header("Location: bookings.php");
exit;
?>
