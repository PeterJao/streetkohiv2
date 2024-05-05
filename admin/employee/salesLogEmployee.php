<?php
session_start();
include 'connect.php'; // Make sure to include the database connection file

if (!isset($_SESSION['admin_user'])) {
    header("Location: ../../login/login.php");
    exit;
}

// Check if the user ID is provided in the URL parameter
if (!isset($_GET['userid'])) {
    echo "User ID not provided.";
    exit;
}

$userId = 1; // Set user_id to 1 for testing purposes

// $userId = $_GET['userid'];

// Query the sales log data for the specific user
$sql = "SELECT sales.id as order_id, sales_items.quantity, CONCAT(sales_items.quantity, 'x ', coffee.coffee_name) as coffee_name, sales_items.unit_price
        FROM pos_system.sales
        INNER JOIN pos_system.sales_items ON sales.id = sales_items.sales_id
        INNER JOIN streetkohi.coffee ON sales_items.product_id = coffee.coffee_id
        WHERE sales.user_id = $userId
        ORDER BY sales.id, sales_items.id"; // Order by order ID and sales item ID

$result = mysqli_query($con, $sql);

if (!$result) {
    echo "Error retrieving sales log data: " . mysqli_error($con);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Log</title>
    <link rel="icon" type="image/x-icon" href="../../assets/images/SK-Icon.png">
    <link rel="stylesheet" href="../../css/salesLogEmployee.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>

<div>
    <?php include "../dashboard/dashboard-header.php" ?>
</div>
<div class="main-content-container">
    <h2 class="sales-log-title">Sales Log</h2>

    <div class="sales-log-table-container">
        <table class="sales-log-table">
            <thead>
                <tr>
                    <th class="order-id-header">Order ID</th>
                    <th class="coffee-name-header">Product Details</th>
                    <th class="unit-price-header">Price (₱)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Initialize total variable
                $total = 0;

                // Display the sales log data in a table format
                $prevOrderId = null;
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($row['order_id'] !== $prevOrderId) {
                        // Display order ID only for the first row of each order
                        echo "<tr class='order-row'>";
                        echo "<td class='order-id'>" . htmlspecialchars($row['order_id']) . "</td>";
                    } else {
                        // For consecutive rows with the same order ID, display an empty cell and remove borders
                        echo "<tr class='order-row'>";
                        echo "<td class='order-id'></td>";
                    }
                    // Display coffee name, quantity, and unit price for each item
                    echo "<td class='coffee-name'>" . htmlspecialchars($row['coffee_name']) . "</td>";
                    echo "<td class='unit-price'>₱" . number_format($row['unit_price'] * $row['quantity'], 2) . "</td>";
                    echo "</tr>";

// Add unit price multiplied by quantity to total
$total += $row['unit_price'] * $row['quantity'];


                    $prevOrderId = $row['order_id'];
                }

                // Display total row
                echo "<tr class='total-row'>";
                echo "<td colspan='2' class='total-label'><strong>TOTAL:</strong></td>";
                echo "<td class='total-amount'>₱" . number_format($total, 2) . "</td>";
                echo "</tr>";
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
