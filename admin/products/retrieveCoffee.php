<?php
// Include the database connection
include 'connect.php';

// Check if arch_coffee_id parameter is set in the URL
if (isset($_GET['arch_coffee_id'])) {
    $arch_coffee_id = intval($_GET['arch_coffee_id']);

    // Retrieve the archived coffee data from the archcoffee table
    $sql_archcoffee = "SELECT * FROM archcoffee WHERE arch_coffee_id = $arch_coffee_id";
    $result_archcoffee = mysqli_query($con, $sql_archcoffee);

    if (mysqli_num_rows($result_archcoffee) > 0) {
        // Get the archived coffee data
        $archcoffee_data = mysqli_fetch_assoc($result_archcoffee);

        // Insert the archived coffee data back into the coffees table
        $sql_coffee = "INSERT INTO coffees (coffee_name, coffee_description, coffee_stock, coffee_price, coffee_image, coffee_tag) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql_coffee);
        $stmt->bind_param(
            "ssiiss",
            $archcoffee_data['arch_coffee_name'],
            $archcoffee_data['arch_coffee_description'],
            $archcoffee_data['arch_coffee_stock'],
            $archcoffee_data['arch_coffee_price'],
            $archcoffee_data['arch_coffee_image'],
            $archcoffee_data['arch_coffee_tag']
        );
        $stmt->execute();

        // If insertion to coffees is successful, delete the archived coffee from the archcoffee table
        if ($stmt->affected_rows > 0) {
            $sql_delete_archcoffee = "DELETE FROM archcoffee WHERE arch_coffee_id = $arch_coffee_id";
            mysqli_query($con, $sql_delete_archcoffee);
        }

        // Redirect back to the Manage Archives page
        header("Location: archiveCoffee.php");
        exit;
    } else {
        // Archived coffee not found
        echo "Archived coffee not found.";
    }
} else {
    // Invalid request
    echo "Invalid request.";
}
?>