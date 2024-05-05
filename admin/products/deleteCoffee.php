<?php
include('connect.php');

// Start the session
session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['admin_user'])) {
    header("Location: ../../login/login.php");
    exit;
}

if (isset($_GET['deleteid'])) {
    $coffee_id = $_GET['deleteid'];

    // Prepare the SQL query to select the coffee item to be archived
    $sql_select_coffee = "SELECT * FROM coffee WHERE coffee_id = ?";
    $stmt_select_coffee = mysqli_prepare($con, $sql_select_coffee);
    
    if ($stmt_select_coffee) {
        // Bind parameter
        mysqli_stmt_bind_param($stmt_select_coffee, "i", $coffee_id);
        
        // Execute the statement
        mysqli_stmt_execute($stmt_select_coffee);
        
        // Get the result
        $result_select_coffee = mysqli_stmt_get_result($stmt_select_coffee);
        
        // Fetch the coffee item
        $coffee_item = mysqli_fetch_assoc($result_select_coffee);
        
        if ($coffee_item) {
            // Prepare the SQL query to insert the coffee item into the archive coffee table
            $sql_insert_archive_coffee = "INSERT INTO archive_coffee (arch_coffee_name, arch_coffee_description, arch_coffee_stock, arch_coffee_price, arch_coffee_image, arch_coffee_tag) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_insert_archive_coffee = mysqli_prepare($con, $sql_insert_archive_coffee);
            
            if ($stmt_insert_archive_coffee) {
                // Bind parameters
                mysqli_stmt_bind_param($stmt_insert_archive_coffee, "ssiiss", $coffee_item['coffee_name'], $coffee_item['coffee_description'], $coffee_item['coffee_stock'], $coffee_item['coffee_price'], $coffee_item['coffee_image'], $coffee_item['coffee_tag']);
                
                // Execute the statement
                $result_insert_archive_coffee = mysqli_stmt_execute($stmt_insert_archive_coffee);
                
                if ($result_insert_archive_coffee) {
                    // If insertion into archive coffee table is successful, proceed to delete from coffee table
                    
                    // Prepare the SQL query to delete the coffee item
                    $sql_delete_coffee = "DELETE FROM coffee WHERE coffee_id = ?";
                    $stmt_delete_coffee = mysqli_prepare($con, $sql_delete_coffee);
                    
                    if ($stmt_delete_coffee) {
                        // Bind parameter
                        mysqli_stmt_bind_param($stmt_delete_coffee, "i", $coffee_id);
                        
                        // Execute the statement
                        $result_delete_coffee = mysqli_stmt_execute($stmt_delete_coffee);
                        
                        if ($result_delete_coffee) {
                            header('location: productsDashboard.php');
                            exit; // Make sure to exit after redirecting
                        } else {
                            die("Error executing delete statement: " . mysqli_error($con));
                        }
                    } else {
                        die("Error preparing delete statement: " . mysqli_error($con));
                    }
                } else {
                    die("Error executing insert into archive coffee statement: " . mysqli_error($con));
                }
            } else {
                die("Error preparing insert into archive coffee statement: " . mysqli_error($con));
            }
        } else {
            die("coffee item not found.");
        }
    } else {
        die("Error preparing select coffee statement: " . mysqli_error($con));
    }
}
?>
