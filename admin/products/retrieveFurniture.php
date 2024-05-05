<?php
// Include the database connection
include 'connect.php';

// Check if arch_furniture_id parameter is set in the URL
if (isset($_GET['arch_furniture_id'])) {
    $arch_furniture_id = intval($_GET['arch_furniture_id']);

    // Retrieve the archived furniture data from the archfurniture table
    $sql_archfurniture = "SELECT * FROM archfurniture WHERE arch_furniture_id = $arch_furniture_id";
    $result_archfurniture = mysqli_query($con, $sql_archfurniture);

    if (mysqli_num_rows($result_archfurniture) > 0) {
        // Get the archived furniture data
        $archfurniture_data = mysqli_fetch_assoc($result_archfurniture);

        // Insert the archived furniture data back into the furnitures table
        $sql_furniture = "INSERT INTO furnitures (furniture_name, furniture_description, furniture_link, furniture_image) VALUES (?, ?, ?, ?)";
        $stmt = $con->prepare($sql_furniture);
        $stmt->bind_param(
            "ssss",
            $archfurniture_data['arch_furniture_name'],
            $archfurniture_data['arch_furniture_description'],
            $archfurniture_data['arch_furniture_link'],
            $archfurniture_data['arch_furniture_image']
        );
        $stmt->execute();

        // If insertion to furnitures is successful, delete the archived furniture from the archfurniture table
        if ($stmt->affected_rows > 0) {
            $sql_delete_archfurniture = "DELETE FROM archfurniture WHERE arch_furniture_id = $arch_furniture_id";
            mysqli_query($con, $sql_delete_archfurniture);
        }

        // Redirect back to the Manage Archives page
        header("Location: archiveFurniture.php");
        exit;
    } else {
        // Archived furniture not found
        echo "Archived furniture not found.";
    }
} else {
    // Invalid request
    echo "Invalid request.";
}
?>