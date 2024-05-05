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
    $furniture_id = $_GET['deleteid'];

    // Prepare the SQL query to select the furniture item to be archived
    $sql_select_furniture = "SELECT * FROM furniture WHERE furniture_id = ?";
    $stmt_select_furniture = mysqli_prepare($con, $sql_select_furniture);
    
    if ($stmt_select_furniture) {
        // Bind parameter
        mysqli_stmt_bind_param($stmt_select_furniture, "i", $furniture_id);
        
        // Execute the statement
        mysqli_stmt_execute($stmt_select_furniture);
        
        // Get the result
        $result_select_furniture = mysqli_stmt_get_result($stmt_select_furniture);
        
        // Fetch the furniture item
        $furniture_item = mysqli_fetch_assoc($result_select_furniture);
        
        if ($furniture_item) {
            // Prepare the SQL query to insert the furniture item into the archive furniture table
            $sql_insert_archive_furniture = "INSERT INTO archive_furniture (arch_furniture_name, arch_furniture_description, arch_furniture_link, arch_furniture_image) VALUES (?, ?, ?, ?)";
            $stmt_insert_archive_furniture = mysqli_prepare($con, $sql_insert_archive_furniture);
            
            if ($stmt_insert_archive_furniture) {
                // Bind parameters
                mysqli_stmt_bind_param($stmt_insert_archive_furniture, "ssss", $furniture_item['furniture_name'], $furniture_item['furniture_description'], $furniture_item['furniture_link'], $furniture_item['furniture_image']);
                
                // Execute the statement
                $result_insert_archive_furniture = mysqli_stmt_execute($stmt_insert_archive_furniture);
                
                if ($result_insert_archive_furniture) {
                    // If insertion into archive furniture table is successful, proceed to delete from furniture table
                    
                    // Prepare the SQL query to delete the furniture item
                    $sql_delete_furniture = "DELETE FROM furniture WHERE furniture_id = ?";
                    $stmt_delete_furniture = mysqli_prepare($con, $sql_delete_furniture);
                    
                    if ($stmt_delete_furniture) {
                        // Bind parameter
                        mysqli_stmt_bind_param($stmt_delete_furniture, "i", $furniture_id);
                        
                        // Execute the statement
                        $result_delete_furniture = mysqli_stmt_execute($stmt_delete_furniture);
                        
                        if ($result_delete_furniture) {
                            header('location: productsDashboard.php');
                            exit; // Make sure to exit after redirecting
                        } else {
                            die("Error executing delete statement: " . mysqli_error($con));
                        }
                    } else {
                        die("Error preparing delete statement: " . mysqli_error($con));
                    }
                } else {
                    die("Error executing insert into archive furniture statement: " . mysqli_error($con));
                }
            } else {
                die("Error preparing insert into archive furniture statement: " . mysqli_error($con));
            }
        } else {
            die("Furniture item not found.");
        }
    } else {
        die("Error preparing select furniture statement: " . mysqli_error($con));
    }
}
?>
