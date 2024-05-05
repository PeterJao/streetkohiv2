<?php
include('../admin/products/connect.php');

// Start the session
session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['employee_user'])) {
    header("Location:../login/login.php");
    exit;
}

if (isset($_GET['deleteid'])) {
    $checkout_id = $_GET['deleteid'];

    // Assuming $checkout_id is properly sanitized to prevent SQL injection
    $sql = "DELETE FROM `process_order` WHERE checkout_id = $checkout_id";
    $result = mysqli_query($con, $sql);

    if ($result) {
        header('location: invoice.php');
        exit; // Make sure to exit after redirecting
    } else {
        die(mysqli_error($con));
    }
}
?>