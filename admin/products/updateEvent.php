<?php
session_start();
include 'connect.php';

// Check if user is authenticated
if (!isset($_SESSION['admin_user'])) {
    header("Location: ../../login/login.php");
    exit;
}

// Handle the update request
if (isset($_GET['updateid'])) {
    $event_id = mysqli_real_escape_string($con, $_GET['updateid']);
    $sql = "SELECT * FROM `event` WHERE event_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $event_name = $row['event_name'];
        $event_description = $row['event_description'];
        $event_date = $row['event_date'];
        $event_time = $row['event_time'];
        $event_price = $row['event_price'];
        $event_venue = $row['event_venue'];
        $event_link = $row['event_link'];
        $event_tag = $row['event_tag']; // Add event_tag
    } else {
        header('Location: productsDashboard.php');
        exit;
    }
    $stmt->close();
} else {
    header('Location: productsDashboard.php');
    exit;
}

// Handle form submission
$errors = [];

if (isset($_POST['submit'])) {
    $event_name = mysqli_real_escape_string($con, $_POST['event_name']);
    $event_description = mysqli_real_escape_string($con, $_POST['event_description']);
    $event_date = mysqli_real_escape_string($con, $_POST['event_date']);
    $event_time = mysqli_real_escape_string($con, $_POST['event_time']);
    $event_price = isset($_POST['event_price']) ? mysqli_real_escape_string($con, $_POST['event_price']) : null;
    $event_venue = mysqli_real_escape_string($con, $_POST['event_venue']);
    $event_link = mysqli_real_escape_string($con, $_POST['event_link']);
    $event_tag = mysqli_real_escape_string($con, $_POST['event_tag']); // Add event_tag handling

    // Handle file upload for event image if a new image is selected
    if (!empty($_FILES['event_image']['name'])) {
        $event_image = 'images/' . $_FILES['event_image']['name'];
        $event_image_tmp = $_FILES['event_image']['tmp_name'];

        move_uploaded_file($event_image_tmp, $event_image);
    } else {
        // Use existing image path if a new image is not provided
        $event_image = $row['event_image'];
    }

    // Validate required fields
    if (empty($event_name) || empty($event_description) || empty($event_date) || empty($event_time) || empty($event_venue) || empty($event_link) || empty($event_tag)) {
        $errors[] = "All fields are required.";
    }

    // Check if no validation errors occurred
    if (empty($errors)) {
        // Update the event data
        $sql = "UPDATE `event` SET event_name=?, event_description=?, event_date=?, event_time=?, event_price=?, event_venue=?, event_link=?, event_tag=?, event_image=? WHERE event_id=?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('sssssssssi', $event_name, $event_description, $event_date, $event_time, $event_price, $event_venue, $event_link, $event_tag, $event_image, $event_id);

        if ($stmt->execute()) {
            header('Location: productsDashboard.php?success=1');
            exit();
        } else {
            $errors[] = "Failed to update event: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link rel="icon" type="image/x-icon" href="../../assets/images/SK-Icon.png">
    <link rel="stylesheet" href="../../css/addEvents.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>

<div>
    <?php include "../dashboard/dashboard-header.php" ?>
</div>

<div class="main-content-container">
<div class="event-form-container">
    <div class="event-input-container">
        <h1 class="form-header">Edit Event</h1>
        <form action="#" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
            <div class="input-container">
                <input type="text" class="input form-control" id="eventName" name="event_name" value="<?php echo $event_name; ?>">
                <label class="label">Event Name</label>
            </div>
            <div class="input-container">
                <textarea class="input form-control" id="eventDescription" name="event_description" rows="3"><?php echo $event_description; ?></textarea>
                <label class="label">Event Description</label>
            </div>
            <div class="input-container">
                <input type="date" class="input form-control" id="eventDate" name="event_date" value="<?php echo $event_date; ?>">
                <label class="label">Event Date</label>
            </div>
            <div class="input-container">
                <input type="time" class="input form-control" id="eventTime" name="event_time" value="<?php echo $event_time; ?>">
                <label class="label">Event Time</label>
            </div>
            <div class="input-container">
                <input type="text" class="input form-control" id="eventPrice" name="event_price" value="<?php echo $event_price; ?>" oninput="validatePrice(this)">
                <label class="label">Event Price</label>
            </div>
            <div class="input-container">
                <input type="text" class="input form-control" id="eventVenue" name="event_venue" value="<?php echo $event_venue; ?>">
                <label class="label">Event Venue</label>
            </div>
            <div class="input-container">
                <input type="text" class="input form-control" id="eventLink" name="event_link" value="<?php echo $event_link; ?>">
                <label class="label">Event Link</label>
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
                <input type="submit" class="btn btn-success" name="submit" value="Update">
                <button type="button" class="btn btn-danger" name="cancel" onclick="goBack()">Cancel</button>
            </div>
        </form>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script>
// Function to validate form
function validateForm(event) {
    const eventName = document.getElementById('eventName').value.trim();
    const eventDescription = document.getElementById('eventDescription').value.trim();
    const eventDate = document.getElementById('eventDate').value.trim();
    const eventTime = document.getElementById('eventTime').value.trim();
    const eventPrice = document.getElementById('eventPrice').value.trim();
    const eventVenue = document.getElementById('eventVenue').value.trim();
    const eventLink = document.getElementById('eventLink').value.trim();
    const eventTag = document.getElementById('eventTag').value.trim();
    const eventImage = document.getElementById('eventImage').value;

    // Validate image format
    const fileName = eventImage.trim().toLowerCase();
    const validImageExtensions = ['jpg', 'jpeg', 'png'];
    const imageExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
    if (eventImage && !validImageExtensions.includes(imageExtension)) {
        displayError('Please upload a valid image (jpg, jpeg, or png).');
        return false;
    }

    // Validate other fields not empty
    if (eventName === '' || eventDescription === '' || eventDate === '' || eventTime === '' || eventVenue === '' || eventLink === '') {
        displayError('Please input all fields.');
        return false;
    }

    if (eventPrice.trim() !== '') {
        // Check if it's not empty, then validate the format
        if (!/^\d+(\.\d{1,2})?$/.test(eventPrice)) {
            displayError('Event price must be a valid format (e.g., 10.00).');
            return false;
        }
    }

    // Validate date format
    const datePattern = /^\d{4}-\d{2}-\d{2}$/; // YYYY-MM-DD format
    if (!datePattern.test(eventDate)) {
        displayError('Event date must be in the format YYYY-MM-DD.');
        return false;
    }

    return true;
}


// Function to go back
function goBack() {
    window.location.href = 'productsDashboard.php';
}

// Function to display error message
function displayError(message) {
    const errorMessage = document.getElementById('errorMessage');
    errorMessage.textContent = message;
    const popup = document.getElementById('errorPopup');
    popup.style.display = 'block';
}

// Function to close error popup
function closePopup() {
    const popup = document.getElementById('errorPopup');
    popup.style.display = 'none';
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
</body>
</html>
