<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/dashboard-header.css" />

  <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
      integrity="sha384-ZKCr1B1z5YbK45I+LiC24uDEmGnIl/GFq3D0fRIhiy+aM4Z3WpeG5ycW93MWI1jD"
      crossorigin="anonymous"
    />
  <title>Dashboard-Header</title>
</head>
<body>
<header class="coffee-shop-header">
      <div class="container">
        <nav>
          <ul>
            <li>
              <a href="../../admin/employee/employee.php" onclick="setActive(this)">Employee</a>
            </li>
            <li>
              <a href="../../admin/dashboard/dashboard.php" onclick="setActive(this)">Dashboard</a>
            </li>
            <img
              src="../assets/images/SK-Logo1.png"
              alt="Street Kohi Logo"
              class="logo"
            />
            <li>
              <a href="../../admin/products/productsDashboard.php" onclick="setActive(this)">Products</a>
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
            <i class="fas fa-user"></i> Logout
          </span>
        </div>
      </div>
    </header>
</body>
<script src="../../javascript/dashboard-header.js"></script>
</html> 