<?php
session_start();
include '../admin/products/connect.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/x-icon" href="../assets/images/SK-Icon.png">
    <!-- Include CSS files -->
    <link rel="stylesheet" href="../css/community.css" /> 
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Community</title>
    <style>
        /* CSS styling */
        .container-flex {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
            
        }

        .col {
            width: 50%;
        }

        .right-text {
            padding: 150px;
            background-color: #f2f2f2;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            font-size: 28px;
        }

        /* Responsive styling */
        @media (max-width: 768px) {
            .container-flex {
                flex-direction: column;
            }
            .col {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <!-- Header -->
    <?php include "../header/header.php"; ?>

     <!-- Main Content Section -->
     <div class="main-content-container">
        <!-- add a carousell with a clickable button here -->
        <div class="container-flex">
            <div class="row">
                <div class="col">
                <img src = "../assets/storeimg/Ext8.jpg" height="100%" width="100%"> 
                </div>
                <div class="col">
                    <div class="right-text">
                        <p>
                            Transform your space into a haven of style and comfort with our exquisite selection of furniture. 
                            From sleek modern designs to timeless classics, our curated collection offers something for every taste and lifestyle. 
                            Elevate your home with quality craftsmanship and exceptional value.
                            Discover the perfect pieces to express your unique aesthetic and make every room a masterpiece.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <form action="booking.php" method="get">
            <button class="pushable" type="submit">
                <span class="shadow"></span>
                <span class="edge"></span>
                <span class="front">
                    Set an appointment with us!
                </span>
            </button>
        <hr class= "rounded">

        </div>
        <div class="content-wrapper">
        <div class="card-container">

        <?php include '../admin/products/connect.php';

            $items_per_page = 6; // Number of items per page
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Get the current page number
            $offset = ($page - 1) * $items_per_page; // Calculate the offset

            $sql = "SELECT * FROM `furniture` LIMIT $items_per_page OFFSET $offset";
            $result = mysqli_query($con, $sql);
            $check = mysqli_num_rows($result) > 0;

            if ($check) {
                echo '<div class="tablecards">'; // Start the container div
                $counter = 0; // Initialize a counter

                while ($row = mysqli_fetch_assoc($result)) {
                    ?>

                    <div class="card">
                        <img src="../admin/products/<?php echo htmlspecialchars($row['furniture_image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['furniture_name']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['furniture_name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($row['furniture_description']); ?></p>
                            <a href="<?php echo htmlspecialchars($row['furniture_link']); ?>" class="btn custom-btn">More Info</a>
                        </div>
                    </div>

                    <?php
                    $counter++; // Increment the counter
                }

                echo '</div>'; // Close the container div
            }

// Calculate total number of pages
$sql_total = "SELECT COUNT(*) FROM `furniture`";
$result_total = mysqli_query($con, $sql_total);
$total_items = mysqli_fetch_row($result_total)[0];
$total_pages = ceil($total_items / $items_per_page);

// Display navigation links
echo '<div class="pagination-container">';
echo '<div class="pagination">';

// Display previous page link
if ($page > 1) {
    echo '<a class="page" href="?page=' . ($page - 1) . '"><</a> ';
}

// Display first page link
echo '<a class="page" href="?page=1">1</a> ';

// Display links around the current page
for ($i = max(2, $page - 1); $i <= min($total_pages, $page + 1); $i++) {
    echo '<a class="page" href="?page=' . $i . '">' . $i . '</a> ';
}

// Display next page link
if ($page < $total_pages) {
    echo '<a class="page" href="?page=' . ($page + 1) . '">></a> ';
}

echo '</div>';
echo '</div>';
 ?>
        </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include "../footer/footer.php"; ?>
    <script>
    // Function to handle pagination link click
    function handlePaginationClick(event) {
        var clickedHref = event.target.href;
        sessionStorage.setItem("activeLink", clickedHref);
        window.location.href = clickedHref;
    }

    // Attach the handlePaginationClick function to all pagination links
    var paginationLinks = document.querySelectorAll(".page");
    paginationLinks.forEach(function(link) {
        link.addEventListener("click", handlePaginationClick);
    });

    // Set "active" class based on sessionStorage on page load
    document.addEventListener('DOMContentLoaded', function() {
        var storedActiveLink = sessionStorage.getItem("activeLink");
        if (storedActiveLink) {
            setActiveLink(storedActiveLink);
        }
    });

    // Function to set the "active" class on the correct pagination link
    function setActiveLink(activeLink) {
        var paginationLinks = document.querySelectorAll(".page");
        paginationLinks.forEach(function(link) {
            if (link.href === activeLink) {
                link.classList.add("active");
            } else {
                link.classList.remove("active");
            }
        });
    }
    </script>
    <!-- Include JavaScript files -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- JavaScript Files -->
    <!-- <script src="../javascript/furniture.js"></script>
    <script src="../javascript/header.js"></script> -->
</body>

</html>
