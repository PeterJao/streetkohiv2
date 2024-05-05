<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/archiveEvent.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>Archived Events</title>
    <link rel="icon" type="image/x-icon" href="../../assets/images/SK-Icon.png">
</head>

<body>
    <!-- Dashboard Header -->
    <div>
        <?php include "../dashboard/dashboard-header.php"; ?>
    </div>

    <!-- Archive Event Management Section -->
    <div class="content-section">
        <div class="header-container">
            <h2>Archived Events</h2>
        </div>

        <div class="container">
            <!-- Archive Events Table -->
            <table class="table table-striped archive-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Price</th>
                        <th>Image</th>
                        <th>Venue</th>
                        <th>Link</th>
                        <th>Tag</th> <!-- Updated header to include Tag -->
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Include database connection
                    include('connect.php');

                    // Calculate the date 60 days ago
                    $date_60_days_ago = date('Y-m-d', strtotime('-60 days'));

                    // Prepare the delete query to delete archive events older than 60 days
                    $stmt_delete_old = mysqli_prepare($con, "DELETE FROM `archive_event` WHERE archevent_date < ?");
                    mysqli_stmt_bind_param($stmt_delete_old, "s", $date_60_days_ago);

                    // Execute the delete query for older archive events
                    if (mysqli_stmt_execute($stmt_delete_old)) {
                        // Optional: Output a message indicating successful deletion
                        // echo "Older archive events deleted successfully.";
                    } else {
                        // Optional: Output an error message if deletion fails
                        // echo "Failed to delete older archive events: " . mysqli_error($con);
                    }

                    // Close the prepared statement for deleting old archive events
                    mysqli_stmt_close($stmt_delete_old);

                    // Prepare the fetch query for archive_event table
                    $stmt_fetch = mysqli_prepare($con, "SELECT * FROM `archive_event`");

                    // Execute the fetch query
                    mysqli_stmt_execute($stmt_fetch);

                    // Get the result of the fetch query
                    $result_fetch = mysqli_stmt_get_result($stmt_fetch);

                    // Check if the query was successful and the archive event data exists
                    if ($result_fetch && mysqli_num_rows($result_fetch) > 0) {
                        // Fetch all archive event data as an associative array
                        $archive_events = mysqli_fetch_all($result_fetch, MYSQLI_ASSOC);

                        // Iterate over each record and create a table row
                        foreach ($archive_events as $event) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($event['archevent_id']) . '</td>';
                            echo '<td>' . htmlspecialchars($event['archevent_name']) . '</td>';
                            echo '<td>' . htmlspecialchars($event['archevent_description']) . '</td>';
                            echo '<td>' . htmlspecialchars($event['archevent_date']) . '</td>';
                            echo '<td>' . htmlspecialchars($event['archevent_time']) . '</td>';
                            echo '<td>' . htmlspecialchars($event['archevent_price']) . '</td>';

                            // Display the event image
                            echo '<td><img src="' . htmlspecialchars($event['archevent_image']) . '" alt="' . htmlspecialchars($event['archevent_name']) . '" width="100" height="100"></td>';

                            echo '<td>' . htmlspecialchars($event['archevent_venue']) . '</td>';
                            echo '<td>' . htmlspecialchars($event['archevent_link']) . '</td>';
                            echo '<td>' . htmlspecialchars($event['archevent_tag']) . '</td>'; // Display the event tag

                            // Add buttons for retrieve and delete actions
                            echo '<td>';
                            // Form to retrieve the event back to the event table
                            echo '<form action="retrieve_from_archive.php" method="POST" style="display: inline;">';
                            echo '<input type="hidden" name="archevent_id" value="' . htmlspecialchars($event['archevent_id']) . '">';
                            echo '<input type="submit" class="retrieve-button" value="Retrieve">';
                            echo '</form>';

                            // Button to delete the archive event
                            echo '<button class="delete-button" onclick="showConfirmationModal(' . htmlspecialchars($event['archevent_id']) . ')">Delete</button>';
                            echo '</td>';

                            echo '</tr>';
                        }
                    } else {
                        // Output an error message if no archive event data is found
                        echo '<tr><td colspan="10">No archive events found.</td></tr>';
                    }

                    // Close the prepared statement
                    mysqli_stmt_close($stmt_fetch);
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add JavaScript function to handle the deletion confirmation -->
    <script>
        function showConfirmationModal(archevent_id) {
            // Show the confirmation dialog
            if (confirm("Are you sure you want to delete this event permanently?")) {
                // If the user confirms, redirect to the delete script
                window.location.href = 'delete_from_archive.php?archevent_id=' + archevent_id;
            }
        }
    </script>

</body>

</html>
