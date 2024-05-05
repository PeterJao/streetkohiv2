<?php
// Start the session
session_start();

// Include database connection
include 'connect.php';

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['admin_user'])) {
    header("Location: ../../login/login.php");
    exit;
}

// Check if the updateid parameter is set in the URL
if (isset($_GET['updateid'])) {
    // Sanitize the input to prevent SQL injection
    $coffee_id = mysqli_real_escape_string($con, $_GET['updateid']);

    // Select the product details based on the provided coffee_id
    $sql = "SELECT * FROM `coffee` WHERE coffee_id = $coffee_id";
    $result = mysqli_query($con, $sql);

    // Check if the product exists
    if (mysqli_num_rows($result) > 0) {
        // Fetch the product details
        $row = mysqli_fetch_assoc($result);
        
        $coffee_name = $row['coffee_name'];
        $coffee_description = $row['coffee_description'];
        $coffee_stock = $row['coffee_stock'];
        $coffee_price = $row['coffee_price'];
        $coffee_image = $row['coffee_image'];
        $coffee_tag = $row['coffee_tag']; // Retrieve the category of the coffee
    } else {
        // If the product does not exist, redirect back to the products dashboard page
        header('Location: productsDashboard.php');
        exit;
    }
} else {
    // If the updateid parameter is not set, redirect back to the products dashboard page
    header('Location: productsDashboard.php');
    exit;
}

// Handle form submission
if (isset($_POST['submit'])) {
    // Retrieve form data
    $coffee_name = $_POST['coffee_name'];
    $coffee_description = $_POST['coffee_description'];
    $coffee_stock = $_POST['coffee_stock'];
    $coffee_price = $_POST['coffee_price'];
    $coffee_tag = $_POST['coffee_tag']; // Retrieve the updated category

    // Handle image upload
    if (isset($_FILES['coffee_image']) && $_FILES['coffee_image']['error'] == UPLOAD_ERR_OK) {
        $targetDirectory = 'images/';
        $targetFile = $targetDirectory . basename($_FILES['coffee_image']['name']);

        if (move_uploaded_file($_FILES['coffee_image']['tmp_name'], $targetFile)) {
            $coffee_image = $targetFile;
        } else {
            echo "Image upload failed.";
        }
    }

    // Update product details in the database
    $sql = "UPDATE `coffee` SET coffee_name='$coffee_name', coffee_description='$coffee_description', coffee_stock='$coffee_stock', coffee_price='$coffee_price', coffee_image='$coffee_image', coffee_tag='$coffee_tag' WHERE coffee_id=$coffee_id";
    $result = mysqli_query($con, $sql);

    // Check if the update was successful
    if ($result) {
        // Redirect back to the products dashboard page
        header('Location: productsDashboard.php');
        exit;
    } else {
        // If the update failed, display the MySQL error
        die(mysqli_error($con));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Update Coffee</title>
  <link rel="icon" type="image/x-icon" href="../../assets/images/SK-Icon.png">
  <link rel="stylesheet" href="../../css/updateCoffee.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  
<div>
  <?php include "../dashboard/dashboard-header.php" ?>
</div>

<div class="main-content-container"><div class="coffee-form-container">
  <div class="coffee-input-container">
    <h1 class="form-header">Update Coffee</h1>
    <form action="#" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
      <div class="input-container">
        <input class="input" type="text" id="coffee_name" name="coffee_name" value="<?php echo htmlspecialchars($coffee_name); ?>">
        <label class="label">Coffee Name</label>
      </div>

      <div class="input-container">
        <input class="input" type="text" id="coffee_stock" name="coffee_stock" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="<?php echo htmlspecialchars($coffee_stock); ?>">
        <label class="label">Coffee Stock</label>
      </div>

      <div class="input-container">
        <input class="input" type="text" id="coffee_price" name="coffee_price" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="<?php echo htmlspecialchars($coffee_price); ?>">
        <label class="label">Coffee Price</label>
      </div>

      <div class="input-container">
        <input class="input" type="text" id="coffee_description" name="coffee_description" value="<?php echo htmlspecialchars($coffee_description); ?>">
        <label class="label">Coffee Description</label>
      </div>

      <div class="input-container">
        <select class="input" id="coffee_tag" name="coffee_tag">
          <option value="hot" <?php if ($coffee_tag == 'hot') echo 'selected'; ?>>Hot</option>
          <option value="iced" <?php if ($coffee_tag == 'iced') echo 'selected'; ?>>Iced</option>
          <option value="non-caffeine" <?php if ($coffee_tag == 'non-caffeine') echo 'selected'; ?>>Non-Caffeine</option>
          <option value="bread-pastry" <?php if ($coffee_tag == 'bread-pastry') echo 'selected'; ?>>Bread and Pastry</option>
          <!-- Add more options for other categories as needed -->
        </select>
        <label class="label">Coffee Category</label>
      </div>

      <div class="input-image-container">
        <label for="coffee_image" class="label">
          <button id="fileButton">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 3H12H8C6.34315 3 5 4.34315 5 6V18C5 19.6569 6.34315 21 8 21H11M13.5 3L19 8.625M13.5 3V7.625C13.5 8.17728 13.9477 8.625 14.5 8.625H19M19 8.625V11.8125" stroke="#fffffff" stroke-width="2"></path>
              <path d="M17 15V18M17 21V18M17 18H14M17 18H20" stroke="#fffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
            Change Image
          </button>
        </label>
        <input class="input" type="file" id="coffee_image" name="coffee_image" onchange="updateFileNameAndValidateFileType(this)">
      </div>

      <div class="buttons-container">
        <input type="submit" class="btn btn-success" name="submit" value="Update Coffee">
        <button type="button" class="btn btn-danger" name="cancel" onclick="goBack()">Cancel</button>
      </div>
    </form>
  </div>
</div></div>


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
  function updateFileName(input) {
    const fileName = input.files[0].name;
    const button = document.getElementById('fileButton');
    button.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 3H12H8C6.34315 3 5 4.34315 5 6V18C5 19.6569 6.34315 21 8 21H11M13.5 3L19 8.625M13.5 3V7.625C13.5 8.17728 13.9477 8.625 14.5 8.625H19M19 8.625V11.8125" stroke="#fffffff" stroke-width="2"></path>
                        <path d="M17 15V18M17 21V18M17 18H14M17 18H20" stroke="#fffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      </svg> ${fileName}`;
  }

  // Add event listener for change event on coffee_image input element
document.getElementById('coffee_image').addEventListener('change', function() {
    var coffeeImage = this.value.trim().toLowerCase(); // Get the image file name and convert to lowercase

    // Validate image extension
    var validImageExtensions = ['jpg', 'jpeg', 'png'];
    var imageExtension = coffeeImage.substring(coffeeImage.lastIndexOf('.') + 1);
    if (!validImageExtensions.includes(imageExtension)) {
        displayError('Please upload a valid image (jpg, jpeg, or png).');
        this.value = ''; // Clear the file input field
    }
});

// Validate form function
function validateForm() {
    var coffeeName = document.getElementById('coffee_name').value.trim();
    var coffeePrice = document.getElementById('coffee_price').value.trim();
    var coffeeDescription = document.getElementById('coffee_description').value.trim();
    var coffeeImage = document.getElementById('coffee_image').value.trim().toLowerCase(); // Get the image file name and convert to lowercase

    // Check if any field is empty
    if (coffeeName === '' || coffeePrice === '' || coffeeDescription === '') {
        displayError('Please input all fields.');
        return false;
    }

    // Validate price as numeric
    if (isNaN(coffeePrice)) {
        displayError('Please enter a valid numerical value for the price.');
        return false;
    }

    return true;
    }

    function goBack() {
        window.location.href = 'productsDashboard.php';
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
</script>
</body>
</html>
