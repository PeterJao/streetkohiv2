<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['index'])) {
    $index = $_POST['index'];
    // Remove the item from the session cart
    unset($_SESSION['cart'][$index]);
}
?>
