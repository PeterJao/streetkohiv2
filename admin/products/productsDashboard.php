<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['admin_user'])) {
    header("Location: ../../login/login.php");
    exit;
}

// Function to sanitize user input
function sanitizeInput($input) {
    global $con;
    return mysqli_real_escape_string($con, $input);
}

// Pagination settings
$items_per_page = 5;
$page_coffee = isset($_GET['page_coffee']) ? (int)$_GET['page_coffee'] : 1;
$page_furniture = isset($_GET['page_furniture']) ? (int)$_GET['page_furniture'] : 1;
$page_event = isset($_GET['page_event']) ? (int)$_GET['page_event'] : 1;

$offset_coffee = ($page_coffee - 1) * $items_per_page;
$offset_furniture = ($page_furniture - 1) * $items_per_page;
$offset_event = ($page_event - 1) * $items_per_page;

// Calculate total number of coffee items
$sql_total_coffee = "SELECT COUNT(*) FROM coffee";
$result_total_coffee = mysqli_query($con, $sql_total_coffee);
$total_items_coffee = mysqli_fetch_row($result_total_coffee)[0];
$total_pages_coffee = ceil($total_items_coffee / $items_per_page);

// If a coffee item is added, adjust the page number accordingly
if (isset($_GET['coffee_added'])) {
    $page_coffee = 1; // Set page to the first page
    $_SESSION['current_page_coffee'] = $page_coffee; // Reset the session variable
    // Set active link in session storage
    $_SESSION['active_link'] = "page_coffee";
}

// Calculate total number of furniture items
$sql_total_furniture = "SELECT COUNT(*) FROM furniture";
$result_total_furniture = mysqli_query($con, $sql_total_furniture);
$total_items_furniture = mysqli_fetch_row($result_total_furniture)[0];
$total_pages_furniture = ceil($total_items_furniture / $items_per_page);

// If a furniture item is added, adjust the page number accordingly
if (isset($_GET['furniture_added'])) {
    $page_furniture = 1; // Set page to the first page
    $_SESSION['current_page_furniture'] = $page_furniture; // Reset the session variable
    // Set active link in session storage
    $_SESSION['active_link'] = "page_furniture";
}

// Calculate total number of event items
$sql_total_event = "SELECT COUNT(*) FROM event";
$result_total_event = mysqli_query($con, $sql_total_event);
$total_items_event = mysqli_fetch_row($result_total_event)[0];
$total_pages_event = ceil($total_items_event / $items_per_page);

// If an event item is added, adjust the page number accordingly
if (isset($_GET['event_added'])) {
    $page_event = 1; // Set page to the first page
    $_SESSION['current_page_event'] = $page_event; // Reset the session variable
    // Set active link in session storage
    $_SESSION['active_link'] = "page_event";
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
    <title>Product List</title>
    <link rel="icon" type="image/x-icon" href="../../assets/images/SK-Icon.png">
</head>
<body>

<div>
    <?php include "../dashboard/dashboard-header.php" ?>
</div>
<div class="main-content-container">
<!-- Manage Coffee -->
<div class="header-container">
    <h2>Manage Coffee</h2>
    <div class="btn-container">
        <a href="../products/addCoffee.php?page_coffee=<?php echo $page_coffee; ?>" class="btn btn-primary">Add Coffee</a>
        <!-- Archive Button -->
        <a href="../products/archiveCoffee.php?page_archive=<?php echo $page_coffee; ?>" class="btn btn-secondary">Archives</a>
    </div>
</div></div>


<table class="table table-striped coffee-table">
    <!-- Coffee Table Header -->
    <thead>
    <tr>
        <th>Name</th>
        <th>Stock</th>
        <th>Price</th>
        <th>Description</th>
        <th>Image</th>
        <th>Category</th>
        <th></th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <!-- PHP Loop for Coffee Table Data -->
    <?php
    // Fetch coffee items ordered by ID in descending order
    $sql = "SELECT * FROM coffee ORDER BY coffee_id DESC LIMIT $items_per_page OFFSET $offset_coffee";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['coffee_name']) . "</td>";
            echo "<td>" . number_format(htmlspecialchars($row['coffee_stock'])) . "</td>";
            echo "<td>" . number_format(htmlspecialchars($row['coffee_price'])) . "</td>";
            echo "<td>" . htmlspecialchars($row['coffee_description']) . "</td>";
            echo "<td><img src='" . htmlspecialchars($row['coffee_image']) . "' alt='Coffee Image' class='coffee-image'></td>";
            echo "<td>" . htmlspecialchars($row['coffee_tag']) . "</td>"; // Display the category tag
            echo "<td>";
            echo "<a href='updateCoffee.php?updateid=" . $row['coffee_id'] . "&page_coffee=$page_coffee' class='btn btn-success m-2 text-light btnUpdate'>Update</a>";
            echo "</td>";
            echo "<td>";
            echo "<a href='deleteCoffee.php?deleteid=" . $row['coffee_id'] . "&page_coffee=$page_coffee' class='btn btn-warning m-2 text-light btnArchive' onclick='return confirm(\"Are you sure you want to archive this coffee?\")'>Archive</a>";
            echo "</td>";

            echo "</tr>";
        }
    } else {
        echo '<tr><td colspan="7" class="text-center">No Coffee found</td></tr>';
    }
    ?>
    </tbody>
</table>

<?php
// Calculate total number of coffee items
$sql_total_coffee = "SELECT COUNT(*) FROM coffee";
$result_total_coffee = mysqli_query($con, $sql_total_coffee);
$total_items_coffee = mysqli_fetch_row($result_total_coffee)[0];
$total_pages_coffee = ceil($total_items_coffee / $items_per_page);

// Display navigation links for Coffee
echo '<div class="pagination-container">';
echo '<div class="pagination">';
for ($i = 1; $i <= $total_pages_coffee; $i++) {
    echo '<a class="page-coffee';
    if ($i == $page_coffee) {
        echo ' active';
    }
    echo '" href="?page_coffee=' . $i . '">' . $i . '</a> '; 
}
echo '</div>';
echo '</div>'
?>

<!-- Manage Furniture -->
<div class="header-container">
    <h2>Manage Furniture</h2>
    <div class="btn-container">
        <a href="../products/addCommunity.php?page_furniture=<?php echo $page_furniture; ?>" class="btn btn-primary">Add Furniture</a>
        <!-- Archive Button -->
        <a href="../products/archiveFurniture.php?page_archive=<?php echo $page_furniture; ?>" class="btn btn-secondary">Archives</a>
    </div>
</div>

<table class="table table-striped furniture-table">
    <!-- Furniture Table Header -->
    <thead>
    <tr>
        <th>Name</th>
        <th>Description</th>
        <th>Link</th>
        <th>Image</th>
        <th></th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <!-- PHP Loop for Community Table Data -->
    <?php
    $sql = "SELECT * FROM furniture LIMIT $items_per_page OFFSET $offset_furniture";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['furniture_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['furniture_description']) . "</td>";
            echo "<td>" . htmlspecialchars($row['furniture_link']) . "</td>";
            echo "<td><img src='" . htmlspecialchars($row['furniture_image']) . "' alt='furniture Image' class='coffee-image'></td>";

            echo "<td>";
            echo "<a href='updateCommunity.php?updateid=" . $row['furniture_id'] . "&page_furniture=$page_furniture' class='btn btn-success m-2 text-light btnUpdate'>Update</a>";
            echo "</td>";
            echo "<td>";
            echo "<a href='deleteCommunity.php?deleteid=" . $row['furniture_id'] . "&page_furniture=$page_furniture' class='btn btn-warning m-2 text-light btnArchive' onclick='return confirm(\"Are you sure you want to archive this furniture?\")'>Archive</a>";
            echo "</td>";

            echo "</tr>";
        }
    } else {
        echo '<tr><td colspan="6" class="text-center">No furniture found</td></tr>';
    }
    ?>
    </tbody>
</table>

<?php
// furniture Table Pagination
$sql_total_furniture = "SELECT COUNT(*) FROM furniture";
$result_total_furniture = mysqli_query($con, $sql_total_furniture);
$total_items_furniture = mysqli_fetch_row($result_total_furniture)[0];
$total_pages_furniture = ceil($total_items_furniture / $items_per_page);

// Display navigation links for Community
echo '<div class="pagination-container">';
echo '<div class="pagination">';
for ($i = 1; $i <= $total_pages_furniture; $i++) {
    echo '<a class="page-furniture';
    if ($i == $page_furniture) {
        echo ' active';
    }
    echo '" href="?page_furniture=' . $i . '">' . $i . '</a> '; 
}
echo '</div>';
echo '</div>';

?>

<!-- Manage Events -->
<div class="header-container">
    <h2>Manage Events</h2>
    <div class="btn-container">
        <!-- Add Event Button -->
        <a href="../products/add.php?page_event=<?php echo $page_event; ?>" class="btn btn-primary">Add Event</a>
        <!-- Archive Button -->
        <a href="../products/archiveEvent.php?page_archive=<?php echo $page_event; ?>" class="btn btn-secondary">Archives</a>
    </div>
</div>

<table class="table table-striped event-table">
    <!-- Event Table Header -->
    <thead>
    <tr>
        <th>Name</th>
        <th>Description</th>
        <th>Date</th>
        <th>Time</th>
        <th>Price</th>
        <th>Venue</th>
        <th>Link</th>
        <th>Image</th>
        <th>Tag</th>
        <th></th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <!-- PHP Loop for Event Table Data -->
    <?php
    // Fetch event items ordered by ID in descending order
    $sql = "SELECT * FROM event ORDER BY event_id DESC LIMIT $items_per_page OFFSET $offset_event";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['event_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['event_description']) . "</td>";
            echo "<td>" . htmlspecialchars($row['event_date']) . "</td>";
            echo "<td>" . htmlspecialchars($row['event_time']) . "</td>";
            echo "<td>" . htmlspecialchars($row['event_price']) . "</td>";
            echo "<td>" . htmlspecialchars($row['event_venue']) . "</td>";
            echo "<td>" . htmlspecialchars($row['event_link']) . "</td>";
            echo "<td><img src='" . htmlspecialchars($row['event_image']) . "' alt='Event Image' class='coffee-image'></td>";
            echo "<td>" . htmlspecialchars($row['event_tag']) . "</td>";

            echo "<td>";
            echo "<a href='updateEvent.php?updateid=" . $row['event_id'] . "&page_event=$page_event' class='btn btn-success m-2 text-light btnUpdate'>Update</a>";
            echo "</td>";
            echo "<td>";
            echo "<a href='deleteEvent.php?deleteid=" . $row['event_id'] . "&page_event=$page_event' class='btn btn-warning m-2 text-light btnArchive' onclick='return confirm(\"Are you sure you want to archive this event?\")'>Archive</a>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo '<tr><td colspan="10" class="text-center">No Events found</td></tr>';
    }
    ?>
    </tbody>
</table>

<?php
// Display navigation links for Events
echo '<div class="pagination-container">';
echo '<div class="pagination">';
for ($i = 1; $i <= $total_pages_event; $i++) {
    echo '<a class="page-event';
    if ($i == $page_event) {
        echo ' active';
    }
    echo '" href="?page_event=' . $i . '">' . $i . '</a> '; 
}
echo '</div>';
echo '</div>';

?>
<script>
// Function to handle pagination link click for all tables
function handlePaginationClick(event) {
    var clickedHref = event.target.href;
    var tableType = event.target.classList.contains('page-coffee') ? 'Coffee' :
                    event.target.classList.contains('page-furniture') ? 'Furniture' :
                    event.target.classList.contains('page-event') ? 'Events' : null;
    if (tableType) {
        sessionStorage.setItem("active" + tableType + "Link", clickedHref);
        setActiveLink(tableType, clickedHref);
    }
}

// Function to set the "active" class on the correct pagination link
function setActiveLink(tableType, clickedHref) {
    var paginationLinks = document.querySelectorAll(".page-" + tableType.toLowerCase());
    paginationLinks.forEach(function(link) {
        if (link.href === clickedHref) {
            link.classList.add("active");
        } else {
            link.classList.remove("active");
        }
    });
}

// Function to set the "active" class on the correct pagination link on page load
function setActiveLinksOnLoad() {
    var activeCoffeeLink = sessionStorage.getItem("activeCoffeeLink");
    var activeFurnitureLink = sessionStorage.getItem("activeFurnitureLink");
    var activeEventsLink = sessionStorage.getItem("activeEventsLink");

    if (activeCoffeeLink) {
        setActiveLink("coffee", activeCoffeeLink);
    }
    if (activeFurnitureLink) {
        setActiveLink("furniture", activeFurnitureLink);
    }
    if (activeEventsLink) {
        setActiveLink("events", activeEventsLink);
    }
}

// Add event listener for pagination link clicks
document.addEventListener('DOMContentLoaded', function() {
    var paginationLinks = document.querySelectorAll('.page-coffee, .page-furniture, .page-event');
    paginationLinks.forEach(function(link) {
        link.addEventListener('click', handlePaginationClick);
    });

    // Set active links on page load
    setActiveLinksOnLoad();
});
</script>
</body>
</html>
