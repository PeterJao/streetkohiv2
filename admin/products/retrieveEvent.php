<?php
// Include the database connection
include 'connect.php';

// Check if archevent_id parameter is set in the URL
if (isset($_GET['archevent_id'])) {
    $archevent_id = intval($_GET['archevent_id']);

    // Retrieve the archived event data from the archevent table
    $sql_archevent = "SELECT * FROM archevent WHERE archevent_id = $archevent_id";
    $result_archevent = mysqli_query($con, $sql_archevent);

    if (mysqli_num_rows($result_archevent) > 0) {
        // Get the archived event data
        $archevent_data = mysqli_fetch_assoc($result_archevent);

        // Insert the archived event data back into the events table
        $sql_event = "INSERT INTO events (event_name, event_description, event_date, event_time, event_price, event_venue, event_link, event_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql_event);
        $stmt->bind_param(
            "sssdsdss",
            $archevent_data['archevent_name'],
            $archevent_data['archevent_description'],
            $archevent_data['archevent_date'],
            $archevent_data['archevent_time'],
            $archevent_data['archevent_price'],
            $archevent_data['archevent_venue'],
            $archevent_data['archevent_link'],
            $archevent_data['archevent_image']
        );
        $stmt->execute();

        // If insertion to events is successful, delete the archived event from the archevent table
        if ($stmt->affected_rows > 0) {
            $sql_delete_archevent = "DELETE FROM archevent WHERE archevent_id = $archevent_id";
            mysqli_query($con, $sql_delete_archevent);
        }

        // Redirect back to the Manage Archives page
        header("Location: archiveEvents.php");
        exit;
    } else {
        // Archived event not found
        echo "Archived event not found.";
    }
} else {
    // Invalid request
    echo "Invalid request.";
}
?>