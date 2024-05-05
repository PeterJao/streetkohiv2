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
    <title>Archived Furnitures</title>
    <link rel="icon" type="image/x-icon" href="../../assets/images/SK-Icon.png">
</head>

<body>
    <!-- Dashboard Header -->
    <div>
        <?php include "../dashboard/dashboard-header.php"; ?>
    </div>

    <!-- Archive Furniture Management Section -->
    <div class="content-section">
        <div class="header-container">
            <h2>Archived Furnitures</h2>
        </div>

        <div class="container">
            <!-- Archive Furniture Table -->
            <table class="table table-striped archive-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Link</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    // Include database connection
                    include('connect.php');

                    // Calculate the ID of the oldest entry to keep (current maximum ID - 60)
                    $stmt_get_max_id = mysqli_prepare($con, "SELECT MAX(arch_furniture_id) FROM `archive_furniture`");
                    mysqli_stmt_execute($stmt_get_max_id);
                    mysqli_stmt_bind_result($stmt_get_max_id, $max_id);
                    mysqli_stmt_fetch($stmt_get_max_id);
                    mysqli_stmt_close($stmt_get_max_id);

                    $id_to_keep = $max_id - 60;

                    // Prepare the delete query to delete archive furniture older than 60 IDs
                    $stmt_delete_old_id = mysqli_prepare($con, "DELETE FROM `archive_furniture` WHERE arch_furniture_id <= ?");
                    mysqli_stmt_bind_param($stmt_delete_old_id, "i", $id_to_keep);
                    mysqli_stmt_execute($stmt_delete_old_id);

                    // Close the prepared statement for deleting old archive furniture by ID
                    mysqli_stmt_close($stmt_delete_old_id);

                    // Prepare the fetch query for archive_furniture table
                    $stmt_fetch = mysqli_prepare($con, "SELECT * FROM `archive_furniture`");

                    // Execute the fetch query
                    mysqli_stmt_execute($stmt_fetch);

                    // Get the result of the fetch query
                    $result_fetch = mysqli_stmt_get_result($stmt_fetch);

                    // Check if the query was successful and the archive furniture data exists
                    if ($result_fetch && mysqli_num_rows($result_fetch) > 0) {
                        // Fetch all archive furniture data as an associative array
                        $archive_furniture = mysqli_fetch_all($result_fetch, MYSQLI_ASSOC);

                        // Iterate over each record and create a table row
                        foreach ($archive_furniture as $furniture) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($furniture['arch_furniture_id']) . '</td>';
                            echo '<td>' . htmlspecialchars($furniture['arch_furniture_name']) . '</td>';
                            echo '<td>' . htmlspecialchars($furniture['arch_furniture_description']) . '</td>';
                            echo '<td>' . htmlspecialchars($furniture['arch_furniture_link']) . '</td>';
                            echo '<td><img src="' . htmlspecialchars($furniture['arch_furniture_image']) . '" class="image-preview" style="max-width: 100px; max-height: 100px; cursor: pointer;"></td>';

                            // Add buttons for retrieve and delete actions
                            echo '<td>';
                            // Form to retrieve the furniture back
                            echo '<form action="retrieve_from_archiveFurniture.php" method="POST" style="display: inline;">';
                            echo '<input type="hidden" name="arch_furniture_id" value="' . htmlspecialchars($furniture['arch_furniture_id']) . '">';
                            echo '<input type="submit" class="retrieve-button" value="Retrieve">';
                            echo '</form>';

                            // Button to delete the archive furniture
                            echo '<button class="delete-button" onclick="showConfirmationModal(' . htmlspecialchars($furniture['arch_furniture_id']) . ')">Delete</button>';
                            echo '</td>';

                            echo '</tr>';
                        }
                    } else {
                        // Output an error message if no archive furniture data is found
                        echo '<tr><td colspan="10">No archive furniture found.</td></tr>';
                    }

                    // Close the prepared statement
                    mysqli_stmt_close($stmt_fetch);

                    // Close database connection
                    mysqli_close($con);
                    ?>

                </tbody>
            </table>
        </div>
    </div>

    <!-- Add JavaScript function to handle the deletion confirmation -->
    <script>
        function showConfirmationModal(arch_furniture_id) {
            // Show the confirmation dialog
            if (confirm("Are you sure you want to delete this furniture permanently?")) {
                // If the user confirms, redirect to the delete script
                window.location.href = 'delete_from_archiveFurniture.php?arch_furniture_id=' + arch_furniture_id;
            }
        }
    </script>

    <!-- JavaScript code -->
<script>
// Function to toggle the image preview
$('.image-preview').on('click', function() {
    var $preview = $(this).next('.full-size-preview');
    if ($preview.length) {
        $preview.toggle();
    } else {
        var imageUrl = $(this).attr('src');
        var $fullSizePreview = $('<div class="full-size-preview" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); display: flex; justify-content: center; align-items: center; z-index: 999;"><img src="' + imageUrl + '" style="max-width: 80%; max-height: 80%;"></div>');
        $('body').append($fullSizePreview);
        $fullSizePreview.on('click', function() {
            $(this).remove();
        });
    }
});
</script>

</body>

</html>
