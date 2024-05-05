<?php
session_start();

include '../admin/products/connect.php';

// Check if no user is logged in, redirect to main login page
if (!isset($_SESSION['admin_user']) && !isset($_SESSION['employee_user'])) {
    header("Location: ../login/login.php");
    exit();
}

// Pagination setup
$items_per_page = 4; // Number of items per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Get the current page number
$offset = ($page - 1) * $items_per_page; // Calculate the offset

$sql = "SELECT * FROM process_order ORDER BY (customer_email IS NULL) ASC, checkout_id DESC LIMIT $items_per_page OFFSET $offset";
$result = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/invoice.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<title>Invoice List</title>
<link rel="icon" type="image/x-icon" href="../assets/images/SK-Icon.png">
</head>
<body>

<div>
<?php include "../pos_header/pos_header.php" ?>
</div>
<div class="main-content-container">
  <table class="table table-striped coffee-table">
    <thead>
      <tr>
        <th></th>
        <th>Customer Name</th>
        <th>Contact Number</th>
        <th>Email</th>
        <th>Order Details</th>
        <th>Street Name</th>
        <th>City</th>
        <th>Building</th>
        <th>Barangay</th>
        <th>Unit</th>
        <th>Gcash Image</th>
        <th></th>
        <th></th>  
      </tr>
    </thead>
    <tbody>
      <?php
      if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
          echo "<tr>";
          echo "<td>" . htmlspecialchars($row['checkout_id']) . "</td>";
          echo "<td>" . htmlspecialchars($row['customer_name']) . "</td>";
          echo "<td>" . htmlspecialchars($row['customer_cnum']) . "</td>";
          echo "<td>" . htmlspecialchars($row['customer_email']) . "</td>";
          echo "<td>" . htmlspecialchars($row['product_details']) . "</td>";
          echo "<td>" . htmlspecialchars($row['delivery_street']) . "</td>";
          echo "<td>" . htmlspecialchars($row['delivery_city']) . "</td>";
          echo "<td>" . htmlspecialchars($row['delivery_building']) . "</td>";
          echo "<td>" . htmlspecialchars($row['delivery_barangay']) . "</td>";
          echo "<td>" . htmlspecialchars($row['delivery_unit']) . "</td>";
          echo "<td><img src='" . htmlspecialchars($row['gcash_img']) . "' alt='Gcash Image' class='coffee-image'></td>";
          echo "<td>";
          
          if ($row['customer_email'] == NULL) {
              echo "<button class='btn btn-success m-2 text-dark' disabled>Completed</button>";
          } else {
              echo "<a href='ship-out.php?shipid=" . $row['checkout_id'] . "' class='btn btn-primary m-2 text-light btnShipOut' onclick=\"return confirm('Are you sure to ship out this order?')\">Ship Out</a>";
          }
          
          echo "</td>";
          echo "<td>";
          echo "<a href='delete-invoice.php?deleteid=" . $row['checkout_id'] . "' class='btn btn-danger m-2 text-light btnRemove' onclick='return confirm(\"Are you sure you want to Remove this order?\")'>Delete</a>";
          echo "</td>";
          echo "</tr>";
        }
      } else {
        echo '<tr><td colspan="6" class="text-center">No Orders found</td></tr>';
      }
      ?>
    </tbody>
  </table>
  <div class="pagination-container">
    <div class="pagination">
    <?php
      // Calculate total number of pages
      $sql_total = "SELECT COUNT(*) FROM `process_order`";
      $result_total = mysqli_query($con, $sql_total);
      $total_items = mysqli_fetch_row($result_total)[0];
      $total_pages = ceil($total_items / $items_per_page);

      // Check if there is only one page
      if ($total_pages == 1) {
          // Set the first page as active
          echo '<a class="page active" href="?page=1">1</a> ';
      } else {
          // Existing pagination logic
          if ($page > 1) {
              echo '<a class="page" href="?page=' . ($page - 1) . '">&lt;</a> ';
          }

          echo '<a class="page" href="?page=1">1</a> ';

          for ($i = max(2, $page - 1); $i <= min($total_pages, $page + 1); $i++) {
              echo '<a class="page" href="?page=' . $i . '">' . $i . '</a> ';
          }

          if ($page < $total_pages) {
              echo '<a class="page" href="?page=' . ($page + 1) . '">&gt;</a> ';
          }
      }
      ?>
    </div>
  </div>
</div>


<script>
    // Function to handle pagination link click
    function handlePaginationClick(event) {
        var clickedPage = event.target.textContent; // Get the page number from the clicked link
        sessionStorage.setItem("activePage", clickedPage); // Store the active page number
        window.location.href = event.target.href; // Navigate to the clicked link
    }

    // Attach the handlePaginationClick function to all pagination links
    var paginationLinks = document.querySelectorAll(".page");
    paginationLinks.forEach(function(link) {
        link.addEventListener("click", handlePaginationClick);
    });

    // Set "active" class based on sessionStorage on page load
    document.addEventListener('DOMContentLoaded', function() {
        var storedActivePage = sessionStorage.getItem("activePage");
        if (storedActivePage) {
            setActiveLink(storedActivePage);
        }
    });

    // Function to set the "active" class on the correct pagination link
    function setActiveLink(activePage) {
        var paginationLinks = document.querySelectorAll(".page");
        paginationLinks.forEach(function(link) {
            if (link.textContent === activePage) {
                link.classList.add("active");
            } else {
                link.classList.remove("active");
            }
        });
    }
</script>

</body>
</html>
