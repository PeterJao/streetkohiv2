<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/header.css" />
  <title>Header</title>
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
      padding: 10px 0; /* Add padding to container to adjust for sticky header */
    }
    .coffee-shop-header.hidden {
      transform: translateY(-100%);
      transition: transform 0.8s ease-in-out; /* Smooth transition */
    }
  </style>
</head>
<body>
<header class="coffee-shop-header" id="header">
  <div class="container">
    <nav>
      <div class="logo-container">
        <a href="../landingpage/LandingPage.php" onclick="handleNavClick(event)">
          <img src="../assets/images/SK-Logo1.png" alt="Street Kohi Logo" class="StreetKohiLogo" />
        </a>
      </div>
      <div class="nav-links-container">
        <ul>
          <li><a href="../blogs/blogs.php" onclick="handleNavClick(event)" class="nav-links">Blogs</a></li>
          <li><a href="../community/community.php" onclick="handleNavClick(event)" class="nav-links">Community</a></li>
          <li><a href="../coffee/coffee.php" onclick="handleNavClick(event)" class="nav-links">Coffee</a></li>
          <li><a href="../events/events.php" onclick="handleNavClick(event)" class="nav-links">Events</a></li>
          <?php if (isset($_SESSION['customer_id'])): ?>
    <li><a href="../customer/logoutCustomer.php" class="nav-links">Logout</a></li>
  <?php else: ?>
    <li><a href="../customer/loginCustomer.php" class="nav-links">Login</a></li>
  <?php endif; ?>
        </ul>
      </div>
      <div class="hamburger"><button id="hamburger">&#9776;</button></div> <!-- Hamburger Icon -->
    </nav>
    <div class="menu"> <!-- Hidden Menu -->
      <ul>
        <li><a href="../blogs/blogs.php" onclick="handleNavClick(event)" class="nav-links">Blogs</a></li>
        <li><a href="../community/community.php" onclick="handleNavClick(event)" class="nav-links">Community</a></li>
        <li><a href="../coffee/coffee.php" onclick="handleNavClick(event)" class="nav-links">Coffee</a></li>
        <li><a href="../events/events.php" onclick="handleNavClick(event)" class="nav-links">Events</a></li>
        <?php if (isset($_SESSION['customer_id'])): ?>
          <li><a href="../customer/logoutCustomer.php" class="nav-links">Logout</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</header>

<script src="../javascript/header.js"></script>
<script>
   // Hamburger menu functionality
   document.getElementById("hamburger").addEventListener("click", function () {
    console.log("Hamburger icon clicked");
    var menu = document.querySelector(".menu");
    menu.classList.toggle("active");
    console.log(
      "Menu active class toggled:",
      menu.classList.contains("active")
    );
  });
  document.addEventListener("DOMContentLoaded", function () {
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
<script>
document.addEventListener("DOMContentLoaded", function () {
 var lastScrollTop = 0; // Variable to store the last scroll position
 var header = document.getElementById("header"); // Reference to the header

 // Function to handle scroll events
 function handleScroll() {
    var currentScroll = window.pageYOffset || document.documentElement.scrollTop;

    // If the current scroll position is greater than the last scroll position, the user is scrolling down
    if (currentScroll > lastScrollTop) {
      header.classList.add("hidden"); // Hide the header
    } else {
      header.classList.remove("hidden"); // Show the header
    }

    lastScrollTop = currentScroll; // Update the last scroll position
 }

 // Attach the scroll event listener
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

</body>
</html>