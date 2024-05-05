<?php
// Include database connection
include('connect.php');

// Get the arch_id from the request
$arch_id = isset($_GET['arch_id']) ? intval($_GET['arch_id']) : 0;

if ($arch_id > 0) {
    // Prepare the delete query to delete the specific archive employee
    $stmt_delete = mysqli_prepare($con, "DELETE FROM `archive_users` WHERE arch_id = ?");
    mysqli_stmt_bind_param($stmt_delete, "i", $arch_id);

    // Execute the delete query
    if (mysqli_stmt_execute($stmt_delete)) {
        // Optionally output a success message (not necessary since you're redirecting)
        // echo "Archive employee deleted successfully.";
    } else {
        // Optionally output an error message if deletion fails (not necessary since you're redirecting)
        // echo "Failed to delete archive employee: " . mysqli_error($con);
    }

    // Close the prepared statement
    mysqli_stmt_close($stmt_delete);
}

// Redirect to the archive page
header("Location: archiveEmployee.php");
exit;
?>
