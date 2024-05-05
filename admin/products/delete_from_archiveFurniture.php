<?php
// Include database connection
include('connect.php');

// Get the arch_furniture_id from the request
$arch_furniture_id = isset($_GET['arch_furniture_id']) ? intval($_GET['arch_furniture_id']) : 0;

if ($arch_furniture_id > 0) {
    // Prepare the delete query to delete the specific archive furniture
    $stmt_delete = mysqli_prepare($con, "DELETE FROM `archive_furniture` WHERE arch_furniture_id = ?");
    mysqli_stmt_bind_param($stmt_delete, "i", $arch_furniture_id);

    // Execute the delete query
    if (mysqli_stmt_execute($stmt_delete)) {
        // Optionally output a success message (not necessary since you're redirecting)
        // echo "Archive furniture deleted successfully.";
    } else {
        // Optionally output an error message if deletion fails (not necessary since you're redirecting)
        // echo "Failed to delete archive furniture: " . mysqli_error($con);
    }

    // Close the prepared statement
    mysqli_stmt_close($stmt_delete);
}

// Redirect to the archive page
header("Location: archiveFurniture.php");
exit;
?>
