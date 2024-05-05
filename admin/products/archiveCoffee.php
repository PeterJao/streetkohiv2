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
    <title>Archived Coffee</title>
    <link rel="icon" type="image/x-icon" href="../../assets/images/SK-Icon.png">
</head>

<body>
    <!-- Dashboard Header -->
    <div>
        <?php include "../dashboard/dashboard-header.php"; ?>
    </div>

    <!-- Archive Coffee Management Section -->
    <div class="content-section">
        <div class="header-container">
            <h2>Archived Coffee</h2>
        </div>

        <div class="container">
            <!-- Archive Coffee Table -->
            <table class="table table-striped archive-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Stock</th>
                        <th>Price</th>
                        <th>Image</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    // Include database connection
                    include('connect.php');

                    // Calculate the ID of the oldest entry to keep (current maximum ID - 60)
                    $stmt_get_max_id = mysqli_prepare($con, "SELECT MAX(arch_coffee_id) FROM `archive_coffee`");
                    mysqli_stmt_execute($stmt_get_max_id);
                    mysqli_stmt_bind_result($stmt_get_max_id, $max_id);
                    mysqli_stmt_fetch($stmt_get_max_id);
                    mysqli_stmt_close($stmt_get_max_id);

                    $id_to_keep = $max_id - 60;

                    // Prepare the delete query to delete archive coffee older than 60 IDs
                    $stmt_delete_old_id = mysqli_prepare($con, "DELETE FROM `archive_coffee` WHERE arch_coffee_id <= ?");
                    mysqli_stmt_bind_param($stmt_delete_old_id, "i", $id_to_keep);
                    mysqli_stmt_execute($stmt_delete_old_id);

                    // Close the prepared statement for deleting old archive coffee by ID
                    mysqli_stmt_close($stmt_delete_old_id);

                    // Prepare the fetch query for archive_coffee table
                    $stmt_fetch = mysqli_prepare($con, "SELECT * FROM `archive_coffee`");

                    // Execute the fetch query
                    mysqli_stmt_execute($stmt_fetch);

                    // Get the result of the fetch query
                    $result_fetch = mysqli_stmt_get_result($stmt_fetch);

                    // Check if the query was successful and the archive coffee data exists
                    if ($result_fetch && mysqli_num_rows($result_fetch) > 0) {
                        // Fetch all archive furniture data as an associative array
                        $archive_coffee = mysqli_fetch_all($result_fetch, MYSQLI_ASSOC);

                        // Iterate over each record and create a table row
                        foreach ($archive_coffee as $coffee) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($coffee['arch_coffee_id']) . '</td>';
                            echo '<td>' . htmlspecialchars($coffee['arch_coffee_name']) . '</td>';
                            echo '<td>' . htmlspecialchars($coffee['arch_coffee_description']) . '</td>';
                            echo '<td>' . htmlspecialchars($coffee['arch_coffee_stock']) . '</td>';
                            echo '<td>' . htmlspecialchars($coffee['arch_coffee_price']) . '</td>';
                            echo '<td><img src="' . htmlspecialchars($coffee['arch_coffee_image']) . '" class="image-preview" style="max-width: 100px; max-height: 100px; cursor: pointer;"></td>';
                            echo '<td>' . htmlspecialchars($coffee['arch_coffee_tag']) . '</td>';
                        
                            // Add buttons for retrieve and delete actions
                            echo '<td>';
                            // Form to retrieve the coffee back
                            echo '<form action="retrieve_from_archiveCoffee.php" method="POST" style="display: inline;">';
                            echo '<input type="hidden" name="arch_coffee_id" value="' . htmlspecialchars($coffee['arch_coffee_id']) . '">';
                            echo '<input type="submit" class="retrieve-button" value="Retrieve">';
                            echo '</form>';
                        
                            // Button to delete the archive coffee
                            echo '<button class="delete-button" onclick="showConfirmationModal(' . htmlspecialchars($coffee['arch_coffee_id']) . ')">Delete</button>';
                            echo '</td>';
                        
                            echo '</tr>';
                        }
                        
                    } else {
                        // Output an error message if no archive coffee data is found
                        echo '<tr><td colspan="10">No archive coffee found.</td></tr>';
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
        function showConfirmationModal(arch_coffee_id) {
            // Show the confirmation dialog
            if (confirm("Are you sure you want to delete this coffee permanently?")) {
                // If the user confirms, redirect to the delete script
                window.location.href = 'delete_from_archiveCoffee.php?arch_coffee_id=' + arch_coffee_id;
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
