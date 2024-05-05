<?php
session_start();
include '../admin/products/connect.php';



if (isset($_POST['submit']) && isset($_POST['customer_name']) && isset($_POST['customer_number']) && isset($_POST['customer_email'])
        && isset($_POST['appointment_date']) && isset($_POST['appointment_time'])) {

    $customer_name = $_POST['customer_name'];
    $customer_number = $_POST['customer_number'];
    $customer_email = $_POST['customer_email'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];



    // Check if all required fields are provided
    if ($customer_name && $customer_number && $customer_email && $appointment_date && $appointment_time) {
        $sql = "INSERT INTO `booking` (customer_name, customer_number, customer_email, appointment_date, appointment_time)
        VALUES ('$customer_name', '$customer_number', '$customer_email', '$appointment_date', '$appointment_time')";     
           $result = mysqli_query($con, $sql);
           if($result) {
            header("Location: community.php");
            exit(); 
        }
    }
}
?>

<!Doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Appointment Scheduling</title>
  <link rel="icon" type="image/x-icon" href="../../assets/images/SK-Icon.png">
  <link rel="stylesheet" href="../css/addEvents.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <link rel="shortcut icon" href="../../assets/images/SK-Logo1.png">
</head>
<body>
<div>
<?php include "../header/header.php"; ?>
</div>

<div class="main-content-container">
    <div class="container">
        <div class="event-input-container">
            <h1 class="form-header">Appointment Scheduling</h1>
                <form action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                    <div class="input-container">
                        
                        <input type="text" class="input form-control" id="customerName" name="customer_name">
                        <label class="label">Customer Name</label>
                    </div>
                    <div class="input-container">
                        
                        <input class="input form-control" id="customerNumber" name="customer_number" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                        <label class="label">Customer Number</label>
                    </div>
                    <div class="input-container">
                        
                        <input class="input form-control" id="customerEmail" name="customer_email">
                        <label class="label">Customer Email</label>
                    </div>
                    <div class="input-container">
                        
                        <input class="input form-control" type="date" id="appointmentDate" name="appointment_date" />
                        <label class="label">Date</label>
                    </div>
                    <div class="input-container">
                        
                        <input class="input form-control" type="time" id="appointmentTime" name="appointment_time" />
                        <label class="label">Time</label>
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

function validateForm() {
    var customerName = document.getElementById('customerName').value;
    var customerNumber = document.getElementById('customerNumber').value;
    var customerEmail = document.getElementById('customerEmail').value;
    var appointmentDate = document.getElementById('appointmentDate').value;
    var appointmentTime = document.getElementById('appointmentTime').value;
    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Simple email validation
    var datePattern = /^\d{4}-\d{2}-\d{2}$/; // YYYY-MM-DD format
    var phoneNumberPattern = /^09\d{9}$/; // 09xxxxxxxxxx format

    if (
        customerName.trim() === '' ||
        customerNumber.trim() === '' ||
        customerEmail.trim() === '' ||
        appointmentDate.trim() === '' ||
        appointmentTime.trim() === ''
    ) {
        displayError('Please input all fields');
        return false;
    }

    if (!emailPattern.test(customerEmail)) {
        displayError('Please provide a valid email address.');
        return false;
    }

    if (!datePattern.test(appointmentDate)) {
        displayError('Date must be in the format YYYY-MM-DD.');
        return false;
    }

    // Validate the phone number format
    if (!phoneNumberPattern.test(customerNumber)) {
        displayError('Please provide a valid phone number in the format 09xxxxxxxxxx.');
        return false;
    }

    // Parse the selected date and time into a Date object
    var selectedDateTime = new Date(appointmentDate + 'T' + appointmentTime);

    // Get the current date and time
    var currentDateTime = new Date();

    // Check if the selected date and time are in the past or the current time
    if (selectedDateTime <= currentDateTime) {
        displayError('Please choose a future date and time for your appointment.');
        return false;
    }

    return true;
}


function displayError(message) {
    // Assuming you have an element with ID 'errorMessage' in your HTML
    var errorMessage = document.getElementById('errorMessage');
    errorMessage.textContent = message;
    // Assuming you have an element with ID 'errorPopup' in your HTML
    var popup = document.getElementById('errorPopup');
    popup.style.display = 'block';
}

function closePopup() {
    var popup = document.getElementById('errorPopup');
    popup.style.display = 'none';
}
function goBack() {
        window.location.href = 'community.php';
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
