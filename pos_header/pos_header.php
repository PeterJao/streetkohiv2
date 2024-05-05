<?php
// Check if a session is not already active
if (session_status() === PHP_SESSION_NONE) {
    // Start the session
    session_start();
}

// Check if the admin user is logged in
if (isset($_SESSION['admin_user'])) {
    $isAdmin = true;
} else {
    $isAdmin = false;
}

// Check if the employee user is logged in
if (isset($_SESSION['employee_user'])) {
    $isEmployee = true;
} else {
    $isEmployee = false;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/pos_header.css" />
  <link rel="icon" type="image/x-icon" href="../../assets/images/SK-Icon.png">
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
      integrity="sha384-ZKCr1B1z5YbK45I+LiC24uDEmGnIl/GFq3D0fRIhiy+aM4Z3WpeG5ycW93MWI1jD"
      crossorigin="anonymous"
    />
  <title>POS-Header</title>
</head>
<body>
<header class="coffee-shop-header">
<div class="container">
        <nav>
          <ul>
            <img
              src="../assets/images/SK-Logo1.png"
              alt="Street Kohi Logo"
              class="logo"
            />
            <li>
              <a href="../invoice/invoice.php" onclick="setActive(this)">Online Orders</a>
            </li>
            <li>
              <a href="../admin_pos/pos.php" onclick="setActive(this)">POS</a>
            </li>
            <?php if ($isAdmin): ?>
            <li>
              <a href="../admin/products/productsDashboard.php" onclick="setActive(this)">Products</a>
            </li>
            <?php endif; ?>
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
            src="../assets/images/SK-Logout.png"
            alt="Logout"
            class="Logout-icon"
          />
          <span id="loginStatus" onclick="toggleLogout()">
          Logout
          </span>
        </div>
      </div>
    </header>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
   $(document).on("click", "#togglenotif", (e)=>{
            console.log("asdsa");
              $.ajax({
                url:"../api/index.php?action=notification",
                type: "POST",
                data: {
                  id:1
                },
                dataType: "json",
                success: (data) => { 

                 $(".dropdown-list").empty();

                 $.each(data.notif, (i, e)=>{

                  $(".dropdown-list").append(`
                  <a class="dropdown-item" href="#">
                  <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                  New Order from ${e.customer_name} <br>
                  <p style="font-size: 14px;"><i>${e.date_inserted}</i></p>
                  </a>
                  `);

                 });   

                 $(".notif").text(0);


                },
                error: (xhr, ajaxOptions, thrownError) => {

                    Swal.close(); 
                  
                    Swal.fire({
                      icon: 'error',
                      title: xhr.status,
                      text: thrownError,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      confirmButtonText: 'Ok'
                    }).then((result) => {
                      if (result.isConfirmed) {
                       
                      }
                    });

                }
               });
        });


        setInterval(function interval(){

              $.ajax({
                url:"../api/index.php?action=getnotif",
                type: "POST",
                data: {
                  id:1
                },
                dataType: "json",
                success: (data) => { 

                    $(".notif").text(data.notif);

                },
                error: (xhr, ajaxOptions, thrownError) => {

                    Swal.close(); 
                  
                    Swal.fire({
                      icon: 'error',
                      title: xhr.status,
                      text: thrownError,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      confirmButtonText: 'Ok'
                    }).then((result) => {
                      if (result.isConfirmed) {
                       
                      }
                    });

                }
               });

        }, 2000);
</script>
<script>
  // script.js
function setActive(clickedElement) {
  // Remove the "active" class from all navigation items
  var navItems = document.querySelectorAll(".coffee-shop-header nav ul li a");
  navItems.forEach(function (item) {
    item.classList.remove("active");
  });

  // Add the "active" class to the clicked navigation item
  clickedElement.classList.add("active");
}

function toggleLogout() {
  window.location.href = "../logout/logout.php";
}

</script>
</html>
