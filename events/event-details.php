<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> 
    <link rel="icon" type="image/x-icon" href="../assets/images/SK-Icon.png"> 
    <link rel="stylesheet" type="text/css" href="event-details.css"> 
    <title>Event Details</title> 

</head> 
<body> 
    

    <!-- Header --> 
    <div> 
        <?php include "../header/header.php" ?> 
    </div>

    <!-- containers of event details -->
    <div class="main-content">
        <div class="event-details-container">
            <div class="event-details">
                
                <?php 
                // to get event name
                

                if (array_key_exists($eventNum, $eventDetails)) { 
                    echo '<div class="EventImageContainer">';
                    echo '<img class="event-image" src="' . $eventDetails[$eventNum]['Image'] . '" alt="' . $eventDetails[$eventNum]['Name'] . '">';// Image here 
                    echo '</div> ';
                    echo '<h2 class="event-title">' . $eventDetails[$eventNum]['Name'] . '</h2>'; // Title here 
                    echo '<p class="event-date">Date: ' . $eventDetails[$eventNum]['Date'] . '</p>'; // Date here 
                    echo '<p class="event-time">Time: '. $eventDetails[$eventNum]['Time'] . '.</p>'; // Time here 
                    echo '<p class="event-where">Venue: '. $eventDetails[$eventNum]['Venue'] . '</p>'; // Venue here 
                    echo '<p class="event-description">' . $eventDetails[$eventNum]['Description'] . '</p>'; // Description here 


                    // Button code naka if else para kung walang link for registration edi sasabihin no registration needed
                    if (isset($eventDetails[$eventNum]['Link'])) { 
                        echo '<a href="' . $eventDetails[$eventNum]['Link'] . '" class="btn regbtn">Register</a>';
                    } else { 
                        echo '<button onclick="alert(\'There\'s no registration needed.\')" class="btn regbtn">No Registration</button>'; 
                    } 
                } else { 
                    echo '<p>No details available for the selected event.</p>'; 
                } 
                ?> 
            </div>

            <!-- Side bar container -->
            <div class="image-link-container">
                <p>Events</p>
                <ul>
                    <?php
                    foreach ($eventDetails as $key => $value) {
                        if ($key !== $eventNum) {
                            echo '<li><a href="?event=' . $key . '"><img src="' . $value['Image'] . '" alt="' . $value['Title'] . '"></a></li>';
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>

    <div> 
        <?php include "../footer/footer.php" ?> 
    </div> 

    <!-- Bootstrap and jQuery Scripts --> 
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script> 
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> 
    <script src="../javascript/header.js"></script> 
</body> 
</html>

