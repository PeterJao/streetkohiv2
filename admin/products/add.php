<?php
session_start();
include 'connect.php';

// Redirect to login page if user is not authenticated
if (!isset($_SESSION['admin_user'])) {
    header("Location: ../../login/login.php");
    exit;
}

// Handle form submission
if (isset($_POST['submit']) && isset($_POST['event_name'])) {
    $upload_image = '';

    // Get form data
    $event_image = $_FILES['event_image'] ?? null;
    $event_name = $_POST['event_name'];
    $event_description = $_POST['event_description'];
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $event_price = isset($_POST['event_price']) ? $_POST['event_price'] : null;
    $event_venue = $_POST['event_venue']; 
    $event_link = $_POST['event_link']; 
    $event_tag = $_POST['event_tag']; 

    // Handle image upload
    if ($event_image) {
        $event_imagefilename = $event_image['name'];
        $event_imagefiletemp = $event_image['tmp_name'];

        $event_filename_separate = explode('.', $event_imagefilename);
        $file_extension = strtolower(end($event_filename_separate));
        $allowed_extensions = ['jpeg', 'jpg', 'png'];

        if (in_array($file_extension, $allowed_extensions)) {
            $upload_image = 'images/' . $event_imagefilename;
            move_uploaded_file($event_imagefiletemp, $upload_image);
        }
    }

    // Prepare and execute the SQL query
    if ($upload_image && $event_name && $event_description && $event_date && $event_time && $event_venue && $event_link && $event_tag) {
        // Prepare the SQL statement to avoid SQL injection
        $stmt = $con->prepare("INSERT INTO `event` (event_name, event_description, event_date, event_time, event_price, event_image, event_venue, event_link, event_tag)
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        // Bind the user inputs to the prepared statement
        $stmt->bind_param("sssssssss", $event_name, $event_description, $event_date, $event_time, $event_price, $upload_image, $event_venue, $event_link, $event_tag);

        // Execute the prepared statement
        $result = $stmt->execute();

        // Handle the result
        if ($result) {
            header("Location: productsDashboard.php");
            exit();
        } else {
            // Log the error or display it for debugging
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    }
}

// Include the HTML form and JavaScript validation
?>

<!Doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Event Creation</title>
  <link rel="icon" type="image/x-icon" href="../../assets/images/SK-Icon.png">
  <link rel="stylesheet" href="../../css/addEvents.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <link rel="shortcut icon" href="../../assets/images/SK-Logo1.png">
</head>
<body>
<div>
    <?php include "../dashboard/dashboard-header.php" ?>
</div>
<div class="main-content-container">
    <div class="event-form-container">
        <div class="event-input-container">
            <h1 class="form-header">Add Event</h1>
                <form action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                    <div class="input-container">
                        <input type="text" class="input form-control" id="eventName" name="event_name">
                        <label class="label">Event Name</label>
                    </div>
                    <div class="input-container">
                        <textarea class="input form-control" id="eventDescription" rows="3" name="event_description"></textarea>
                        <label class="label">Event Description</label>
                    </div>
                    <div class="input-container">
                        <input class="input form-control" type="date" id="eventDate" name="event_date"  />
                        <label class="label">Event Date</label>
                    </div>
                    <div class="input-container">
                        <input class="input form-control" type="time" id="eventTime" name="event_time"  />
                        <label class="label">Event Time</label>
                    </div>
                    <div class="input-container">
                        <input class="input form-control" type="text" id="eventVenue" name="event_venue"  />
                        <label class="label">Event Venue</label>
                    </div>
                    <div class="input-container">
                        <input class="input form-control" type="text" id="eventLink" name="event_link"  />
                        <label class="label">Event Link</label>
                    </div>
                    <div class="input-container">
                        <input class="input form-control" type="text" id="eventPrice" name="event_price" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" />
                        <label class="label">Event Price</label>
                    </div>
                    <div class="input-container">
                   <select class="input form-control" id="eventTag" name="event_tag">
                  <option value="Customer">Customer</option>
                  <option value="Seller">Seller</option>
                  </select>
                  <label class="label">Event Tag</label>
                  </div>


                    <div class="input-image-container">
                    <label for="eventImage" class="label">
                    <button id="fileButton">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 3H12H8C6.34315 3 5 4.34315 5 6V18C5 19.6569 6.34315 21 8 21H11M13.5 3L19 8.625M13.5 3V7.625C13.5 8.17728 13.9477 8.625 14.5 8.625H19M19 8.625V11.8125" stroke="#fffffff" stroke-width="2"></path>
                        <path d="M17 15V18M17 21V18M17 18H14M17 18H20" stroke="#fffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    Add Image
                    </button>
                </label>
                <input class="input form-control" type="file" id="eventImage" name="event_image" accept="image/jpeg, image/jpg, image/png">
                </div>

                    <div class="buttons-container">
                        <input type="submit" class="btn btn-success" name="submit"></input>
                        <button type="button" class="btn btn-danger" name="cancel" onclick="goBack()">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="errorPopup" class="popup">
  <div class="popup-content">
    <span class="close" onclick="closePopup()">&times;</span>
    <p id="errorMessage"></p>
    <center>
      <dotlottie-player src="https://lottie.host/23175b72-edf2-4781-a175-7e7ca03fc3ab/t4kdPVrxsW.json" background="transparent" speed="1" style="width: 150px; height: 150px;" loop autoplay></dotlottie-player>
    </center>
  </div>
</div>


    
<script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script>

<script>
function validateForm(event) {

var eventName = document.getElementById('eventName').value;
var eventDescription = document.getElementById('eventDescription').value;
var eventDate = document.getElementById('eventDate').value;
var eventTime = document.getElementById('eventTime').value;
var eventPrice = document.getElementById('eventPrice').value;
var eventVenue = document.getElementById('eventVenue').value;
var eventLink = document.getElementById('eventLink').value;
var imageInput = document.getElementById('eventTag');
var imageInput = document.getElementById('eventImage');
var imageFile = imageInput.files[0];

if (
    eventName.trim() === '' ||
    eventDescription.trim() === '' ||
    eventDate.trim() === '' ||
    eventTime.trim() === '' ||
    eventVenue.trim() === '' ||
    eventTag.trim() === '' ||
    eventLink.trim() === '' ||
    !imageFile
) {
    displayError('Please input all fields');
    return false;
}

// Validate image format
var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
if (!allowedExtensions.exec(imageFile.name)) {
    displayError('Please upload an image with .jpg, .jpeg, or .png extension.');
    return false;
}

// Check if event price is provided and validate if it's a non-negative numerical value
if (eventPrice.trim() !== '') {
    if (isNaN(eventPrice) || parseFloat(eventPrice) < 0) { // Allow price to be 0 or more
        displayError('Event price must be a non-negative numerical value.');
        return false;
    }
}

// Validate date format
const datePattern = /^\d{4}-\d{2}-\d{2}$/; // YYYY-MM-DD format
if (!datePattern.test(eventDate)) {
    displayError('Event date must be in the format YYYY-MM-DD.');
    return false;
}

// If all validations pass, submit the form
return true;
}



function displayError(message) {
    var errorMessage = document.getElementById('errorMessage');
    errorMessage.textContent = message;
    var popup = document.getElementById('errorPopup');
    popup.style.display = 'block';
}

function closePopup() {
    var popup = document.getElementById('errorPopup');
    popup.style.display = 'none';
}
function goBack() {
        window.location.href = 'productsDashboard.php';
    }
</script>
<script>
    function validateForm(event) {
    var eventLink = document.getElementById('eventLink').value.trim();

    if (eventLink === '') {
        displayError('Please provide the event link.');
        return false;
    }

    // Validate event link
    if (!isValidURL(eventLink)) {
        displayError('Please provide a valid URL for the event link (starting with http:// or https://).');
        return false;
    }

    // If all validations pass, submit the form
    return true;
}

// Function to check if a string is a valid URL
function isValidURL(string) {
    var urlPattern = /^(https?:\/\/)/i;
    return urlPattern.test(string);
}
</script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
    integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
    crossorigin="anonymous"></script>
</body>
</html>
