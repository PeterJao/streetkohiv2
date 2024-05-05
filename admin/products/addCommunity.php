<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['admin_user'])) {
  header("Location: ../../login/login.php");
  exit;
}

if (isset($_POST['submit']) && isset($_POST['furniture_name'])) {
    $upload_image = '';

    $furniture_image = $_FILES['furniture_image'] ?? null;
    $furniture_name = $_POST['furniture_name'];
    $furniture_description = $_POST['furniture_description'];
    $furniture_link = $_POST['furniture_link'];
   

    if ($furniture_image && $furniture_image['error'] == 0) {
        $furniture_imagefilename = $furniture_image['name'];
        $furniture_imagefiletemp = $furniture_image['tmp_name'];

        $furniture_filename_separate = explode('.', $furniture_imagefilename);
        $file_extension = strtolower(end($furniture_filename_separate));
        $extension = ['jpeg', 'jpg', 'png'];

        if (in_array($file_extension, $extension)) {
            $upload_image = 'images/' . $furniture_imagefilename;
            if (!move_uploaded_file($furniture_imagefiletemp, $upload_image)) {
                // Handle file upload error
                echo "Failed to upload image.";
                exit;
            }
        }
    }

    if ($upload_image && $furniture_name && $furniture_description) {
        // Using prepared statement to prevent SQL Injection
        $sql = "INSERT INTO `furniture` (furniture_name, furniture_description, furniture_image, furniture_link) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", $furniture_name, $furniture_description, $upload_image, $furniture_link);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            header("Location: productsDashboard.php");
            exit();
        } else {
            echo "Failed to insert data into database.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add furniture</title>
  <link rel="icon" type="image/x-icon" href="../../assets/images/SK-Icon.png">
  <link rel="stylesheet" href="../../css/addCoffee.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="stylesheet" href="styles.css"> <!-- Link to the separated CSS file -->
</head>
<body>

<?php include "../dashboard/dashboard-header.php" ?>

<div class="main-content-container"><center>
<div class="furniture-form-container">
  <div class="coffee-input-container">
    <h1 class="form-header">Add Community</h1>
    <form action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
      <div class="input-container">
        <input class="input" type="text" id="furnitureName" name="furniture_name"  />
        <label class="label">Furniture Name</label>
      </div>

      <div class="input-container">
        <input class="input" type="text" id="furnitureDescription" name="furniture_description">
        <label class="label">Furniture Description</label>
      </div>

      <div class="input-container">
        <input class="input" type="text" id="furnitureLink" name="furniture_link">
        <label class="label">Furniture Link</label>
      </div>

      <div class="input-image-container">
      <label for="furnitureImage" class="label">
        <button id="fileButton">
          <!-- SVG icon for button omitted for brevity -->
          Add Image
        </button>
      </label>
      <input class="input" type="file" id="furnitureImage" name="furniture_image" onchange="updateFileNameAndValidateFileType(this)">
    </div>

      <div class="buttons-container">
        <input type="submit" class="btn btn-success" name="submit"></input>
        <button type="button" class="btn btn-danger" name="cancel" onclick="goBack()">Cancel</button>
      </div>

    </form>
  </div>
</div>
</center></div>



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
        var furnitureName = document.getElementById('furnitureName').value;
        var furnitureDescription = document.getElementById('furnitureDescription').value;
        var furnitureImage = document.getElementById('furnitureImage').value;
        var furnitureLink = document.getElementById('furnitureLink').value;

        if (furnitureName.trim() === '' || furnitureDescription.trim() === '' || furnitureImage.trim() === '' || furnitureLink.trim() === '') {
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

