<?php
// Include the database connection
include 'connect.php';

// Check if arch_id parameter is set in the URL
if (isset($_GET['arch_id'])) {
    $arch_id = intval($_GET['arch_id']);

    // Retrieve the archived employee data from the archemployee table
    $sql_archuser = "SELECT * archuser WHERE arch_id = $arch_id";
    $result_archuser = mysqli_query($con, $sql_archuser);

    if (mysqli_num_rows($result_archuser) > 0) {
        // Get the archived employee data
        $archuser_data = mysqli_fetch_assoc($result_archuser);

        // Insert the archived employee data back into the employee table
        $sql_user = "INSERT INTO users (username, password) VALUES (?, ?, )";
        $stmt = $con->prepare($sql_user);
        $stmt->bind_param(
            "ss",
            $archuser_data['arch_username'],
            $archuser_data['arch_password'],
        );
        $stmt->execute();

        // If insertion to employee is successful, delete the archived employee from the archemployee table
        if ($stmt->affected_rows > 0) {
            $sql_delete_archuser = "DELETE FROM archuser WHERE arch_id = $arch_id";
            mysqli_query($con, $sql_delete_archuser);
        }

        // Redirect back to the Manage Archives page
        header("Location: archiveEmployee.php");
        exit;
    } else {
        // Archived event not found
        echo "Archived employee not found.";
    }
} else {
    // Invalid request
    echo "Invalid request.";
}
?>