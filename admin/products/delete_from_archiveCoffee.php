<?php
// Include database connection
include('connect.php');

// Get the arch_coffee_id from the request
$arch_coffee_id = isset($_GET['arch_coffee_id']) ? intval($_GET['arch_coffee_id']) : 0;

if ($arch_coffee_id > 0) {
    // Prepare the delete query to delete the specific archive coffee
    $stmt_delete = mysqli_prepare($con, "DELETE FROM `archive_coffee` WHERE arch_coffee_id = ?");
    mysqli_stmt_bind_param($stmt_delete, "i", $arch_coffee_id);

    // Execute the delete query
    if (mysqli_stmt_execute($stmt_delete)) {
        // Optionally output a success message (not necessary since you're redirecting)
        // echo "Archive coffee deleted successfully.";
    } else {
        // Optionally output an error message if deletion fails (not necessary since you're redirecting)
        // echo "Failed to delete archive coffee: " . mysqli_error($con);
    }

    // Close the prepared statement
    mysqli_stmt_close($stmt_delete);
}

// Redirect to the archive page
header("Location: archiveCoffee.php");
exit;
?>
