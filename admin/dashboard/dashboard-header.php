<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../css/dashboard-header.css" />

  <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
      integrity="sha384-ZKCr1B1z5YbK45I+LiC24uDEmGnIl/GFq3D0fRIhiy+aM4Z3WpeG5ycW93MWI1jD"
      crossorigin="anonymous"
    />
  <title>Dashboard-Header</title>
  <style>
    /* Additional CSS for sticky header */
    .coffee-shop-header {
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 1000; 
      transition: transform 0.3s ease-out;
    }
    .container {
      padding: 10px 0; 
    }
    .coffee-shop-header.hidden {
      transform: translateY(-100%);
      transition: transform 0.8s ease-in-out; 
    }
  </style>
</head>
<body>
<header class="coffee-shop-header" id="header">
      <div class="container">
        <nav>
          <ul>
            <li>
              <a href="../../admin/employee/employee.php" onclick="setActive(this)">Employee</a>
            </li>
            <li>
              <a href="../../admin/dashboard/dashboard.php" onclick="setActive(this)">Dashboard</a>
            </li>
            <li>
              <a href="../../admin_pos/pos.php" onclick="setActive(this)">POS</a>
            </li>
            <img
              src="../../assets/images/SK-Logo1.png"
              alt="Street Kohi Logo"
              class="logo"
            />
            <li>
              <a href="../../admin/products/productsDashboard.php" onclick="setActive(this)">Products</a>
            </li>
            <li>
              <a href="../../admin/products/bookings.php" onclick="setActive(this)">Community</a>
            </li>

          </ul>
        </nav>
        <div class="user-actions">
          
          <?php
          $arr = explode("/", $_SERVER['REQUEST_URI']);
 
          if ($arr[3] == 'products') {

          }else if ($arr[3] == 'employee') {
            # code...
          }else{
          ?>
          <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle " type="button" id="togglenotif" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-bell" style="color: white; pointer-events: none;" aria-hidden="true"></i>
              <span class="badge badge-pill badge-danger notif" 
                    style="background-color: red; color:white"></span>
            </button>
            <div class="dropdown-menu dropdown-list" 
                  aria-labelledby="dropdownMenuButton"
                  style="width: 250px;overflow-x: scroll; height:400px; overflow-y: scroll;">
              
            </div>
          </div>
          <?php 
          }

          ?>


          <img
            src="../../assets/images/SK-Logout.png"
            alt="User Avatar"
            class="Logout-icon"
          />
          <span id="loginStatus" onclick="toggleLogout()">
           Logout
          </span>
        </div>
      </div>
    </header>
</body>
<script src="../../javascript/dashboard-header.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    var lastScrollTop = 0; // Variable to store the last scroll position
    var header = document.getElementById("header"); // Reference to the header
    var scrollTimeout; // Variable to store the timeout ID

    // Function to handle scroll events with debounce
    function debounce(func, wait) {
        var timeout;
        return function executedFunction() {
            var context = this;
            var args = arguments;
            var later = function () {
                timeout = null;
                func.apply(context, args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    var handleScroll = debounce(function () {
        var currentScroll = window.pageYOffset || document.documentElement.scrollTop;

        // If the current scroll position is greater than the last scroll position, the user is scrolling down
        if (currentScroll > lastScrollTop) {
            header.classList.add("hidden"); // Hide the header
        } else {
            header.classList.remove("hidden"); // Show the header
        }

        lastScrollTop = currentScroll; // Update the last scroll position
    }, 200); // Adjust the debounce delay as needed

    // Attach the debounced scroll event listener
    window.addEventListener("scroll", handleScroll);

    // Function to calculate and apply padding
    function adjustContentPadding() {
        var headerHeight = document.getElementById("header").offsetHeight;
        var mainContent = document.querySelector(".main-content-container"); // Adjust the selector as needed
        if (mainContent) {
            mainContent.style.paddingTop = headerHeight + "px";
        }
    }

    // Call the function on page load
    adjustContentPadding();

    // Call the function on window resize to adjust padding if the header height changes
    window.addEventListener("resize", adjustContentPadding);
});


</script>
</html> 