<?php
session_start();
include '../admin/products/connect.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../assets/images/SK-Icon.png"> 
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/events.css">
   
    <title>Events</title>
</head>

<body>
    <!-- Header -->
    <div>
        <?php include "../header/header.php"; ?>
    </div>

    <!-- Carousel -->
    <div class="main-content-container">
        <div class="carousel">
            <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="../assets/storeimg/MainFloor1.jpg" alt="First slide">
                    </div>
                    <div class="carousel-item">
                        <img src="../assets/storeimg/2ndfloor1.jpg" alt="Second slide">
                    </div>
                    <div class="carousel-item">
                        <img src="../assets/storeimg/Front1.jpg" alt="Third slide">
                    </div>
                </div>

                <!-- Carousel controls -->
                <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="container">
        <!-- Page title -->
        <h1 class="TitlePage">Street Kohi Events</h1>
      </div>

        <!-- Divider -->
        <hr class="divider" />

        <!-- Headings for sections -->
    <div class="text-center mb-4">
        <h2 class="Nameplate">Customer</h2>
    </div>

    <!-- Events section for Customers -->
    <div class="events">
        <div class="row">
            <?php
            include '../admin/products/connect.php';
            
           
           // Query for Customer events
$sql_customer = "SELECT * FROM `event` WHERE `event_tag` = 'Customer'";
$result_customer = mysqli_query($con, $sql_customer);

if ($result_customer && mysqli_num_rows($result_customer) > 0) {
    while ($row = mysqli_fetch_assoc($result_customer)) {
        // Display Customer events
        echo '<div class="col-md-4 event-card" data-tag="' . $row['event_tag'] . '">';
        echo '<div class="card shadow showevent" style="width: 18rem;"';
        echo 'data-event_name="' . $row['event_name'] . '"';
        echo 'data-event_description="' . $row['event_description'] . '"';
        echo 'data-event_date="' . date("F j, Y", strtotime($row['event_date'])) . '"';
        echo 'data-event_time="' . date("g:i a", strtotime($row['event_time'])) . '"';
        echo 'data-event_price="' . $row['event_price'] . '"';
        echo 'data-event_image="../admin/products/' . $row['event_image'] . '"';
        echo 'data-event_venue="' . $row['event_venue'] . '">';
        echo '<div class="card-header">';
        echo '<h5 class="card-title">' . $row['event_name'] . '</h5>';
        echo '</div>';
        echo '<img class="card-img-top" src="../admin/products/' . $row['event_image'] . '" alt="Event image">';
        
        // Truncate the event description to a specific length (e.g., 50 characters)
        $fullDescription = $row['event_description'];
        $truncatedDescription = strlen($fullDescription) > 150 ? substr($fullDescription, 0, 150) . '...' : $fullDescription;
        
        echo '<div class="card-body">';
        echo '<p class="card-text">' . date("F j, Y", strtotime($row['event_date'])) . ' ' . date("g:i a", strtotime($row['event_time'])) . '</p>';
        echo '<p class="card-text">' . $truncatedDescription . '</p>'; // Display truncated description
        echo '<p class="card-text">₱' . number_format($row['event_price'], 2) . '</p>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}
            ?>
        </div>
    </div>

    <!-- Headings for sections -->
    <div class="text-center mb-4">
        <h2 class="Nameplate">Seller</h2>
    </div>

    <!-- Events section for Sellers -->
    <div class="events">
        <div class="row">
            <?php
           
// Query for Seller events
$sql_seller = "SELECT * FROM `event` WHERE `event_tag` = 'Seller'";
$result_seller = mysqli_query($con, $sql_seller);

if ($result_seller && mysqli_num_rows($result_seller) > 0) {
    while ($row = mysqli_fetch_assoc($result_seller)) {
        // Display Seller events
        echo '<div class="col-md-4 event-card" data-tag="' . $row['event_tag'] . '">';
        echo '<div class="card shadow showevent" style="width: 18rem;"';
        echo 'data-event_name="' . $row['event_name'] . '"';
        echo 'data-event_description="' . $row['event_description'] . '"';
        echo 'data-event_date="' . date("F j, Y", strtotime($row['event_date'])) . '"';
        echo 'data-event_time="' . date("g:i a", strtotime($row['event_time'])) . '"';
        echo 'data-event_price="' . $row['event_price'] . '"';
        echo 'data-event_image="../admin/products/' . $row['event_image'] . '"';
        echo 'data-event_venue="' . $row['event_venue'] . '">';
        echo '<div class="card-header">';
        echo '<h5 class="card-title">' . $row['event_name'] . '</h5>';
        echo '</div>';
        
        // Display event image
        echo '<img class="card-img-top" src="../admin/products/' . $row['event_image'] . '" alt="Event image">';
        
        // Truncate the event description in the card body
        $fullDescription = $row['event_description'];
        $truncatedDescription = strlen($fullDescription) > 150 ? substr($fullDescription, 0, 150) . '...' : $fullDescription;
        
        // Display event information in the card body
        echo '<div class="card-body">';
        echo '<p class="card-text">' . date("F j, Y", strtotime($row['event_date'])) . ' ' . date("g:i a", strtotime($row['event_time'])) . '</p>';
        echo '<p class="card-text">' . $truncatedDescription . '</p>'; // Display truncated description in the card
        echo '<p class="card-text">₱' . number_format($row['event_price'], 2) . '</p>';
        echo '</div>';
        
        echo '</div>';
        echo '</div>';
    }
}
            ?>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card" style="width: 100%;">
                        <img src="..." class="card-img-top" alt="..." id="img-src">
                        <div class="card-body">
                            <h5 class="card-title" id="date-time"></h5>
                            <p class="card-text" id="desc"></p>
                            <p class="card-text" id="price"></p>
                            <p class="card-text" id="venue"></p>
                            <a href="event-register.php" class="btn btn-primary" id="link">Open Link</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Footer -->
<div>
        <?php include "../footer/footer.php"; ?>
    </div>

    </body>
<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../javascript/header.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
$(document).ready(function() {
    // Event listener for clicks on event cards
    $(document).on('click', '.event-card', function() {
        // Retrieve the clicked event card
        var eventCard = $(this);
        
        // Retrieve data attributes from the event card
        var eventTitle = eventCard.find('.card-title').text(); // Event title
        var eventImageSrc = eventCard.find('.card-img-top').attr('src'); // Event image source
        var eventDesc = eventCard.find('.card-text').eq(1).text(); // Event description
        
        // Update modal content
        $('#staticBackdropLabel').text(eventTitle); // Set modal title
        $('#img-src').attr('src', eventImageSrc).attr('alt', 'Event image'); // Set modal image source
        $('#desc').text(eventDesc); // Set modal description
        
        // Show the modal
        $('#staticBackdrop').modal('show');
    });
});
</script>


</html>

