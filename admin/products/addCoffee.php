<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['admin_user'])) {
  header("Location: ../../login/login.php");
  exit;
}

if (isset($_POST['submit']) && isset($_POST['coffee_name'])) {
    $upload_image = '';

    $coffee_image = $_FILES['coffee_image'] ?? null;
    $coffee_name = $_POST['coffee_name'];
    $coffee_stock = $_POST['coffee_stock'];
    $coffee_price = $_POST['coffee_price'];
    $coffee_description = $_POST['coffee_description'];
    $coffee_tag = $_POST['coffee_tag']; // This is where we'll get the selected category

    if ($coffee_image && $coffee_image['error'] == 0) {
        $coffee_imagefilename = $coffee_image['name'];
        $coffee_imagefiletemp = $coffee_image['tmp_name'];

        $coffee_filename_separate = explode('.', $coffee_imagefilename);
        $file_extension = strtolower(end($coffee_filename_separate));
        $extension = ['jpeg', 'jpg', 'png'];

        if (in_array($file_extension, $extension)) {
            $upload_image = 'images/' . $coffee_imagefilename;
            if (!move_uploaded_file($coffee_imagefiletemp, $upload_image)) {
                // Handle file upload error
                echo "Failed to upload image.";
                exit;
            }
        }
    }

    if ($upload_image && $coffee_name && $coffee_stock && $coffee_price && $coffee_description && $coffee_tag) {
        // Using prepared statement to prevent SQL Injection
        $sql = "INSERT INTO `coffee` (coffee_name, coffee_stock, coffee_price, coffee_description, coffee_image, coffee_tag) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "sidsss", $coffee_name, $coffee_stock, $coffee_price, $coffee_description, $upload_image, $coffee_tag);
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
  <title>Add Coffee</title>
  <link rel="icon" type="image/x-icon" href="../../assets/images/SK-Icon.png">
  <link rel="stylesheet" href="../../css/addCoffee.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="stylesheet" href="styles.css"> <!-- Link to the separated CSS file -->
</head>
<body>

<?php include "../dashboard/dashboard-header.php" ?>
<div class="main-content-container"><div class="coffee-form-container">
  <div class="coffee-input-container">
    <h1 class="form-header">Add Coffee</h1>
    <form action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
      <div class="input-container">
        <input class="input" type="text" id="coffeeName" name="coffee_name"  />
        <label class="label">Coffee Name</label>
      </div>

      <div class="input-container">
        <input class="input" type="text" id="coffeeStock" name="coffee_stock" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" />
        <label class="label">Coffee Stock</label>
      </div>

      <div class="input-container">
        <input class="input" type="text" id="coffeePrice" name="coffee_price" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" />
        <label class="label">Coffee Price</label>
      </div>


      <div class="input-container">
        <input class="input" type="text" id="coffeeDescription" name="coffee_description"  />
        <label class="label">Coffee Description</label>
      </div>

      <div class="input-container">
        <select class="input" id="coffeeTag" name="coffee_tag">
          <option value="hot">Hot</option>
          <option value="iced">Iced</option>
          <option value="non-caffeine">Non-Caffeine</option>
          <option value="bread-pastry">Bread and Pastry</option>
        </select>
        <label class="label">Coffee Category</label>
      </div>

      <div class="input-image-container">
      <label for="coffeeImage" class="label">
        <button id="fileButton">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 3H12H8C6.34315 3 5 4.34315 5 6V18C5 19.6569 6.34315 21 8 21H11M13.5 3L19 8.625M13.5 3V7.625C13.5 8.17728 13.9477 8.625 14.5 8.625H19M19 8.625V11.8125" stroke="#fffffff" stroke-width="2"></path>
            <path d="M17 15V18M17 21V18M17 18H14M17 18H20" stroke="#fffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
          </svg>
          Add Image
        </button>
      </label>
      <input class="input" type="file" id="coffeeImage" name="coffee_image" onchange="updateFileNameAndValidateFileType(this)">
    </div>

      <div class="buttons-container">
        <input type="submit" class="btn btn-success" name="submit"></input>
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
        var coffeeName = document.getElementById('coffeeName').value;
        var coffeeStock = document.getElementById('coffeeStock').value;
        var coffeePrice = document.getElementById('coffeePrice').value;
        var coffeeDescription = document.getElementById('coffeeDescription').value;
        var coffeeImage = document.getElementById('coffeeImage').value;

        if (coffeeName.trim() === '' || coffeeStock.trim() === '' || coffeePrice.trim() === '' || coffeeDescription.trim() === '' || coffeeImage.trim() === '') {
            displayError('Please input all fields');
            return false; 
        }
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
        // Reset the file input value to clear the selected file
        input.value = '';
        // Display an error message
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
  button.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 3H12H8C6.34315 3 5 4.34315 5 6V18C5 19.6569 6.34315 21 8 21H11M13.5 3L19 8.625M13.5 3V7.625C13.5 8.17728 13.9477 8.625 14.5 8.625H19M19 8.625V11.8125" stroke="#fffffff" stroke-width="2"></path>
                        <path d="M17 15V18M17 21V18M17 18H14M17 18H20" stroke="#fffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      </svg> ${fileName}`;
}
</script>
</body>
</html>
