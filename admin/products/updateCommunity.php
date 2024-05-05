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
    $furniture_id = mysqli_real_escape_string($con, $_GET['updateid']);

    // Select the product details based on the provided funiture_id
    $sql = "SELECT * FROM `furniture` WHERE furniture_id = $furniture_id";
    $result = mysqli_query($con, $sql);

    // Check if the product exists
    if (mysqli_num_rows($result) > 0) {
        // Fetch the product details
        $row = mysqli_fetch_assoc($result);
        
        $furniture_name = $row['furniture_name'];
        $furniture_description = $row['furniture_description'];
        $furniture_link = $row['furniture_link'];
        $furniture_image = $row['furniture_image'];
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
    $furniture_name = $_POST['furniture_name'];
    $furniture_description = $_POST['furniture_description'];
    $furniture_link = $_POST['furniture_link'];

    // Handle image upload
    if (isset($_FILES['furniture_image']) && $_FILES['furniture_image']['error'] == UPLOAD_ERR_OK) {
        $targetDirectory = 'images/';
        $targetFile = $targetDirectory . basename($_FILES['furniture_image']['name']);

        if (move_uploaded_file($_FILES['furniture_image']['tmp_name'], $targetFile)) {
            $furniture_image = $targetFile;
        } else {
            echo "Image upload failed.";
        }
    }

    // Update product details in the database
    $sql = "UPDATE `furniture` SET furniture_name='$furniture_name', furniture_description='$furniture_description', furniture_image='$furniture_image', furniture_link='$furniture_link' WHERE furniture_id=$furniture_id";
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
  <title>Update furniture</title>
  <link rel="icon" type="image/x-icon" href="../../assets/images/SK-Icon.png">
  <link rel="stylesheet" href="../../css/updateCoffee.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  
<div>
  <?php include "../dashboard/dashboard-header.php" ?>
</div>
<div class="main-content-container"><div class="coffee-form-container">
  <div class="coffee-input-container">
    <h1 class="form-header">Update Community</h1>
    <form action="#" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
      <div class="input-container">
        <input class="input" type="text" id="furniture_name" name="furniture_name" value="<?php echo htmlspecialchars($furniture_name); ?>">
        <label class="label">Furniture Name</label>
      </div>


      <div class="input-container">
        <input class="input" type="text" id="furniture_description" name="furniture_description" value="<?php echo htmlspecialchars($furniture_description); ?>">
        <label class="label">Furniture Description</label>
      </div>
      <div class="input-container">
        <input class="input" type="text" id="furniture_link" name="furniture_link" value="<?php echo htmlspecialchars($furniture_link); ?>">
        <label class="label">Furniture Link</label>
      </div>

      <div class="input-image-container">
        <label for="furniture_image" class="label">
          <button id="fileButton">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 3H12H8C6.34315 3 5 4.34315 5 6V18C5 19.6569 6.34315 21 8 21H11M13.5 3L19 8.625M13.5 3V7.625C13.5 8.17728 13.9477 8.625 14.5 8.625H19M19 8.625V11.8125" stroke="#fffffff" stroke-width="2"></path>
              <path d="M17 15V18M17 21V18M17 18H14M17 18H20" stroke="#fffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
            Change Image
          </button>
        </label>
        <input class="input" type="file" id="furniture_image" name="furniture_image" onchange="updateFileNameAndValidateFileType(this)">
      </div>

      <div class="buttons-container">
        <input type="submit" class="btn btn-success" name="submit" value="Update Furniture">
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
function validateForm() {
    var furnitureName = document.getElementById('furniture_name').value; // Corrected ID
    var furnitureDescription = document.getElementById('furniture_description').value; // Corrected ID
    var furnitureLink = document.getElementById('furniture_link').value; // Corrected ID

    if (furnitureName.trim() === '' || furnitureDescription.trim() === '' || furnitureLink.trim() === '') {
        displayError('Please input all fields');
        return false; 
    } else if (!isValidUrl(furnitureLink)) {
        displayError('Please enter a valid furniture link.');
        return false;
    }
    return true;
}


    function isValidUrl(string) {
        var urlPattern = new RegExp('^(https?:\\/\\/)?'+ // validate protocol
        '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name and extension
        '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
        '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
        '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
        '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
        return !!urlPattern.test(string);
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

<script>
    function goBack() {
        window.location.href = 'productsDashboard.php';
    }
</script>
<script>
  function updateFileNameAndValidateFileType(input) {
    if (validateFileType(input)) {
        updateFileName(input);
    }
  }
</script>
<script>
  function validateFileType(input) {
    const allowedExtensions = ['jpg', 'jpeg', 'png'];
    const file = input.files[0];
    const fileName = file.name.toLowerCase();
    const fileExtension = fileName.split('.').pop();

    if (!allowedExtensions.includes(fileExtension)) {
        input.value = '';
        displayError('Please select a valid image file (JPG, JPEG, or PNG)');
        return false;
    }
    return true;
}
</script>
<script>
  function updateFileName(input) {
    const fileName = input.files[0].name;
    const button = document.getElementById('fileButton');
    button.innerHTML = '...' + fileName; // Modified for brevity
}
</script>
<script>
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
