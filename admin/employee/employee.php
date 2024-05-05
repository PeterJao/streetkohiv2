<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['admin_user'])) {
    header("Location: ../../login/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/productDashboard.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>Employee List</title>
    <link rel="icon" type="image/x-icon" href="../../assets/images/SK-Icon.png">
</head>
<body>

<div>
    <?php include "../dashboard/dashboard-header.php" ?>
</div>
<div class="main-content-container">
<div class="header-container">
    <h2>Manage Employee</h2>
    <div class="btn-container">
        <a href="addEmployee.php" class="btn btn-primary">Add Employee</a>
        <!-- Archive Button -->
        <a href="archiveEmployee.php" class="btn btn-secondary">Archives</a>
    </div>
</div>
</div>

<table class="table table-striped coffee-table">
    <!-- Employee Table Header -->
    <thead>
    <tr>
        <th>Username</th>
        <th>Password</th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <!-- PHP Loop for Employee Table Data -->
    <?php
    $sql = "SELECT * FROM pos_system.users"; // Updated SQL query to fetch data from the users table
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['username']) . "</td>"; // Display username
            echo "<td>" . htmlspecialchars($row['password']) . "</td>"; // Display password
            echo "<td>";
            echo "<a href='updateEmployee.php?updateid=" . $row['id'] . "' class='btn btn-success m-2 text-light btnUpdate'>Update</a>";
            echo "</td>";
            echo "<td>";
            echo "<a href='salesLogEmployee.php?userid=" . $row['id'] . "' class='btn btn-info m-2 text-light'>Sales Log</a>";
            echo "</td>";
            echo "<td>";
            echo "<a href='deleteEmployee.php?deleteid=" . $row['id'] . "' class='btn btn-warning m-2 text-light btnArchive' onclick='return confirm(\"Are you sure you want to archive this employee?\")'>Archive</a>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo '<tr><td colspan="4" class="text-center">No Employees found</td></tr>';
    }
    ?>
    </tbody>
</table>

</body>
</html>
