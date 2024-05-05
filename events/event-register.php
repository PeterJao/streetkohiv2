<?php
session_start();
include '../admin/products/connect.php';


if (isset($_POST['submit']) && isset($_POST['name'])) {
    
    $name = $_POST['name'];
    $email = $_POST['email'];
    $cp_num = $_POST['cp_num'];
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];

    if ($name && $email && $cp_num && $event_name && $event_date)  {
        $sql = "INSERT INTO event_registration (name, email, cp_num, event_name, event_date)
        VALUES ('$name', '$email', '$cp_num', '$event_name', '$event_date')";
        $result = mysqli_query($con, $sql);
        if ($result){
            header("Location: events.php");
            exit();
        } 
    }

}


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="../assets/images/SK-Icon.png"> 
    <title>Event Registration</title>
    <link rel="stylesheet" href="../css/event-register.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body>
    <!-- Header -->
    <div> 
        <?php include "../header/header.php" ?> 
    </div>
    
    <div class="form-container">
        <h1>Register for the Event</h1>
        <form id="eventForm" action="" method="post">
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name" required minlength="2" maxlength="50" title="Name must contain 2 to 50 characters"><br>
            
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Enter a valid email address"><br>
            
            <label for="cp_num">Phone:</label><br>
            <input type="text" id="cp_num" name="cp_num" required pattern="^\d{11}$" title="Enter a 11-digit phone number"><br>
            
            <label for="event_name">Event Name:</label><br>
            <input type="text" id="event_name" name="event_name" required><br>
            
            <label for="event_date">Event Date:</label><br>
            <input type="date" id="event_date" name="event_date" required><br>
            
            <button type="button" id="cancelButton" class="btn-cancel">Cancel</button>
            <input type="submit" value="Submit" name="submit">
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Get references to the form elements
    const form = document.getElementById('eventForm');
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const phoneInput = document.getElementById('cp_num');
    const eventNameInput = document.getElementById('event_name');
    const eventDateInput = document.getElementById('event_date');

    // Validation patterns
    const namePattern = /^[a-zA-Z\s]+$/;
    const emailPattern = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/;
    const phonePattern = /^(09|\+63)[0-9]{9}$/;
    
    // Function to validate inputs
    function validateInput(input, pattern, errorMessage) {
        if (!pattern.test(input.value)) {
            input.setCustomValidity(errorMessage);
        } else {
            input.setCustomValidity('');
        }
    }

    // Add event listeners to input fields for real-time validation
    nameInput.addEventListener('input', function() {
        validateInput(nameInput, namePattern, 'Name must contain only letters and spaces.');
    });

    emailInput.addEventListener('input', function() {
        validateInput(emailInput, emailPattern, 'Invalid email address.');
    });

    phoneInput.addEventListener('input', function() {
        validateInput(phoneInput, phonePattern, 'Phone number must be a valid Philippine phone number starting with +63 or 09.');
    });

    eventNameInput.addEventListener('input', function() {
        if (eventNameInput.value.length < 2) {
            eventNameInput.setCustomValidity('Event name must be at least 2 characters long.');
        } else {
            eventNameInput.setCustomValidity('');
        }
    });

    eventDateInput.addEventListener('input', function() {
        // Check if the event date is in the future
        const currentDate = new Date();
        const inputDate = new Date(eventDateInput.value);
        if (inputDate <= currentDate) {
            eventDateInput.setCustomValidity('Event date must be in the future.');
        } else {
            eventDateInput.setCustomValidity('');
        }
    });

    // Cancel button event listener
    const cancelButton = document.getElementById('cancelButton');
    cancelButton.addEventListener('click', function() {
        window.location.href = 'events.php';
    });
});


</script>
</body>
</html>
