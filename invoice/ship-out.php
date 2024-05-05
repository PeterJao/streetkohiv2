<?php
include('../admin/products/connect.php');

session_start();

error_reporting(E_ALL); // Enable error reporting for debugging

if (isset($_GET['shipid'])) {
    $checkout_id = $_GET['shipid'];

    // Assuming $checkout_id is properly sanitized to prevent SQL injection
    $sql_update = "UPDATE process_order SET customer_name = NULL, customer_cnum = NULL, customer_email = NULL, delivery_street = NULL, delivery_city = NULL, delivery_building = NULL, delivery_barangay = NULL, delivery_unit = NULL WHERE checkout_id = $checkout_id";
    $result_update = mysqli_query($con, $sql_update);

    if ($result_update) {
        // Debugging statement: Print the value of $_SESSION['user_id']
        echo "User ID: " . $_SESSION['user_id'] . "<br>";
        
        // Redirect only if the user is not logged in
        if (!isset($_SESSION['employee_user'])) {
            header("Location:../login/login.php");
            exit;
        }
        header('location: invoice.php');
        exit;
    } else {
        die(mysqli_error($con));
    }
}
?>
