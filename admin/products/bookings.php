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
    <title>Appointments</title>
    <link rel="icon" type="image/x-icon" href="../../assets/images/SK-Icon.png">
</head>


<body>
    <!-- Dashboard Header -->
    <div>
        <?php include "../dashboard/dashboard-header.php"; ?>
    </div>

    <!-- Archive Event Management Section -->
    <div class="content-section">
        <div class="header-container center-text">
            <h2>Appointments</h2>
        </div>

        <div class="container-flex">
            <!-- Archive Events Table -->
            <table class="table table-striped archive-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Contact Number</th>
                        <th>Email</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Include database connection
                    include('connect.php');

                    // Prepare the fetch query for archive_event table
                    $stmt_fetch = mysqli_prepare($con, "SELECT * FROM `booking`");

                    // Execute the fetch query
                    mysqli_stmt_execute($stmt_fetch);

                    // Get the result of the fetch query
                    $result_fetch = mysqli_stmt_get_result($stmt_fetch);

                    // Check if the query was successful and the archive event data exists
                    if ($result_fetch && mysqli_num_rows($result_fetch) > 0) {
                        // Fetch all archive event data as an associative array
                        $appointment = mysqli_fetch_all($result_fetch, MYSQLI_ASSOC);

                        // Iterate over each record and create a table row
                        foreach ($appointment as $apptmnt) {
                            
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($apptmnt['appointment_id']) . '</td>';
                            echo '<td>' . htmlspecialchars($apptmnt['customer_name']) . '</td>';
                            echo '<td>' . htmlspecialchars($apptmnt['customer_number']) . '</td>';
                            echo '<td>' . htmlspecialchars($apptmnt['customer_email']) . '</td>';
                            echo '<td>' . htmlspecialchars($apptmnt['appointment_date']) . '</td>';
                            echo '<td>' . htmlspecialchars($apptmnt['appointment_time']) . '</td>';

                            // Add buttons for retrieve and delete actions
                            echo '<td>';

                            // Button to delete the archive event
                            echo '<button class="delete-button" onclick="showConfirmationModal(' . htmlspecialchars($apptmnt['appointment_id']) . ')">Delete</button>';
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


        <!-- Event Registration Section -->
        <div class="content-section">
        <div class="header-container center-text">
            <h2>Registration</h2>
        </div>

        <div class="container-flex">
            <!-- Archive Events Table -->
            <table class="table table-striped archive-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>CP Number</th>
                        <th>Event Name</th>
                        <th>Event Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Include database connection
                    include('connect.php');

                    // Prepare the fetch query for archive_event table
                    $stmt_fetch = mysqli_prepare($con, "SELECT * FROM `event_registration`");

                    // Execute the fetch query
                    mysqli_stmt_execute($stmt_fetch);

                    // Get the result of the fetch query
                    $result_fetch = mysqli_stmt_get_result($stmt_fetch);

                    // Check if the query was successful and the archive event data exists
                    if ($result_fetch && mysqli_num_rows($result_fetch) > 0) {
                        // Fetch all archive event data as an associative array
                        $registration = mysqli_fetch_all($result_fetch, MYSQLI_ASSOC);

                        // Iterate over each record and create a table row
                        foreach ($registration as $reg) {
                            
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($reg['reg_id']) . '</td>';
                            echo '<td>' . htmlspecialchars($reg['name']) . '</td>';
                            echo '<td>' . htmlspecialchars($reg['email']) . '</td>';
                            echo '<td>' . htmlspecialchars($reg['cp_num']) . '</td>';
                            echo '<td>' . htmlspecialchars($reg['event_name']) . '</td>';
                            echo '<td>' . htmlspecialchars($reg['event_date']) . '</td>';

                            // Add buttons for retrieve and delete actions
                            echo '<td>';

                            // Button to delete the archive event
                            echo '<button class="delete-button" onclick="showConfirmationModal(' . htmlspecialchars($reg['reg_id']) . ')">Delete</button>';
                            echo '</td>';

                            echo '</tr>';
                        }
                    } else {
                        // Output an error message if no archive event data is found
                        echo '<tr><td colspan="10">No registration found.</td></tr>';
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
        function showConfirmationModal(appointment_id) {
            // Show the confirmation dialog
            if (confirm("Are you sure you want to delete this appointment permanently?")) {
                // If the user confirms, redirect to the delete script
                window.location.href = 'delete_from_bookings.php?appointment_id=' + appointment_id;
            }
        }
        function showConfirmationModal(reg_id) {
            // Show the confirmation dialog
            if (confirm("Are you sure you want to delete this registration permanently?")) {
                // If the user confirms, redirect to the delete script
                window.location.href = 'deletereg.php?reg_id=' + reg_id;
            }
        }
    </script>

</body>

</html>
