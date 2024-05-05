<?php
// Include database connection
include('connect.php');

// Start the session
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['admin_user'])) {
    header("Location: ../../login/login.php");
    exit;
}
?>

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
    <title>Archived Employees</title>
    <link rel="icon" type="image/x-icon" href="../../assets/images/SK-Icon.png">
</head>

<body>
    <!-- Dashboard Header -->
    <div>
        <?php include "../dashboard/dashboard-header.php"; ?>
    </div>

    <!-- Archive Employee Management Section -->
    <div class="content-section">
        <div class="header-container">
            <h2>Archived Employees</h2>
        </div>

        <div class="container">
            <!-- Archive Employees Table -->
            <table class="table table-striped archive-table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch archive employees data
                    $stmt_fetch = mysqli_prepare($con, "SELECT * FROM `archive_users`");
                    mysqli_stmt_execute($stmt_fetch);
                    $result_fetch = mysqli_stmt_get_result($stmt_fetch);

                    // Check if archive employees data exists
                    if ($result_fetch && mysqli_num_rows($result_fetch) > 0) {
                        $archive_events = mysqli_fetch_all($result_fetch, MYSQLI_ASSOC);

                        // Iterate over each archive employee and display in table row
                        foreach ($archive_events as $archUser) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($archUser['arch_id']) . '</td>';
                            echo '<td>' . htmlspecialchars($archUser['arch_username']) . '</td>';
                            echo '<td>' . htmlspecialchars($archUser['arch_password']) . '</td>';
                            echo '<td>';
                            echo '<form action="retrieve_from_arch_users.php" method="POST" style="display: inline;">';
                            echo '<input type="hidden" name="arch_id" value="' . htmlspecialchars($archUser['arch_id']) . '">';
                            echo '<input type="submit" class="retrieve-button" value="Retrieve">';
                            echo '</form>';
                            echo '<button class="delete-button" onclick="showConfirmationModal(' . htmlspecialchars($archUser['arch_id']) . ')">Delete</button>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    } else {
                        // Output message if no archive employee data found
                        echo '<tr><td colspan="4">No archived employee details found.</td></tr>';
                    }

                    // Close prepared statement
                    mysqli_stmt_close($stmt_fetch);
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- JavaScript function to handle deletion confirmation -->
    <script>
        function showConfirmationModal(arch_id) {
            // Show confirmation dialog
            if (confirm("Are you sure you want to delete this employee details permanently?")) {
                // If confirmed, redirect to delete script with archive ID
                window.location.href = 'delete_from_archUser.php?arch_id=' + arch_id;
            }
        }
    </script>

</body>

</html>
