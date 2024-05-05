<?php
session_start();

// Check if the admin user is not logged in, redirect to login page
if (!isset($_SESSION['admin_user']) && !isset($_SESSION['employee_user'])) {
    header("Location: ../../login/login.php");
    exit;
}

include("product.php");
$products = getProducts();

include '../admin/products/connect.php';

// Fetch the username for employees
$employee_username = '';
if (isset($_SESSION['employee_user'])) {
    $employee_id = $_SESSION['employee_user'];
    $query = "SELECT username FROM pos_system.users WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $employee_username = $row['username'];
    }
    $stmt->close();
}

// Fetch the username for admin
$admin_username = '';
if (isset($_SESSION['admin_user'])) {
    $admin_id = $_SESSION['admin_user'];
    $query = "SELECT username FROM streetkohi.users WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $admin_username = $row['username'];
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="pos.css?v=<?= time() ?>">
    <!-- Bootstrap Dialog -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.35.4/css/bootstrap-dialog.min.css" integrity="sha512-PvZCtvQ6xGBLWHcXnyHD67NTP+a+bNrToMsIdX/NUqhw+npjLDhlMZ/PhSHZN4s9NdmuumcxKHQqbHlGVqc8ow==" crossorigin="anonymous" />
    <script src="https://use.fontawesome.com/0c7a3095b5.js"></script>
    <link rel="icon" type="image/x-icon" href="../assets/images/SK-Icon.png">
</head>
<body>
    <div>
        <?php include "../pos_header/pos_header.php" ?>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-8">
                <!-- Product items -->
                <div class="searchInputContainer">
                    <input type="text" id="searchInput" placeholder="Search product...">
                    <div id="searchResultContainerMain">
                    </div>
                </div>

                <div class="row">
                    <?php foreach ($products as $product): ?>
                        <div class="col-md-4">
                            <div class="searchResultContainer">
                                <div class="productColContainer" data-pid="<?= $product['coffee_id'] ?>">
                                    <div class="productResultContainer">
                                    <div class="productImageContainer">
                                        <img src="../admin/products/<?= htmlspecialchars($product['coffee_image']) ?>" class="coffeeImage" alt="<?= htmlspecialchars($product['coffee_name']) ?>">
                                    </div>
                                        <div class="productInforContainer">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <p class="coffeeName"><?= $product['coffee_name'] ?></p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="coffeePrice">₱ <?= $product['coffee_price'] ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-4 posOrderContainer">
                <!-- POS Order Container -->
                <div class="pos_header">
                    <center>
                        <p class="logo">Street Kohi</p>
                    </center>
                    <p class="timeAndDate">XXX X, XXXX XX:XX:XX XX</p>
                    <?php if(!empty($employee_username)): ?>
                        <p class="username">Hi <?php echo $employee_username; ?>!</p>
                    <?php elseif(!empty($admin_username)): ?>
                        <p class="username">Hi <?php echo $admin_username; ?>!</p>
                    <?php endif; ?>
                </div>
                <div class="pos_items_container">
                    <div class="pos_items">
                        <p class="itemNoData">No Data</p>
                    </div>
                <div class="item_total_container">
                    <p class="item_total">
                        <span class="item_total--label">TOTAL</span>
                        <span class="item_total--value">₱ 0.00</span>
                    </p>
                </div>
                <div class="checkoutBtnContainer">
                    <a href="javascript:void(0);" class="checkoutBtn"> CHECKOUT </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Create a global js variable to hold products -->
    <script>
        let productsJson = <?=json_encode($products) ?>;
        var products = {};

        //Loop through products
        productsJson.forEach((product) => {
            products[product.coffee_id] = {
                name: product.coffee_name,
                price: product.coffee_price
            }
        });

        // Live search feature

            var typingTimer; // This is the timer identifier
            var doneTypingInterval = 500; // Time in ms (500 milliseconds interval / a delay after event is triggered)

            // Once the user presses a keyboard key, this will run
            document.addEventListener('keyup', function(ev) {
                let el = ev.target;

                // If searchInput is the element
                if (el.id === 'searchInput') {
                    // Get the value
                    let searchTerm = el.value;

                    // Use clearTimeout to stop running setTimeout
                    // This will clear the timeout, to avoid calling/searching the database every time we type
                    clearTimeout(typingTimer);

                    // Set timeout
                    // This is the function that calls the searchDb, which pulls the search in the database
                    //After 500 milliseconds, it will be triggered.
                    typingTimer = setTimeout(function() {
                        //Call the function, and pass the searchTerm as parameter
                        searchDb(searchTerm);
                    }, doneTypingInterval);
                }
            });

            function searchDb(searchTerm) {
                let searchResult = document.getElementById('searchResultContainerMain');
                //Check if searchterm is not empty
                //If not empty, trigger this function
                if(searchTerm.length) {
                    //Set container of result to block
                    searchResult.style.display = 'block';
                    $.ajax({
                    type: 'GET',
                    data: {search_term: searchTerm},
                    url: 'live-search.php',
                    success: function(response) {
                        //If there is no length, we show no data found
                        if(response.length === 0){
                        searchResult.innerHTML = '<p class="nodatafound">no data found</p>';
                        }else {
                        let html = '';
                        let searchResults = response.data;

                        searchResults.forEach((row) => {
                            html += `
                                <div class="row searchResultEntry" data-pid=${row['coffee_id']}>
                                    <div class="col-3">
                                    <img class="searchResultImg" src="../admin/products/${row['coffee_image']}" alt="${row['coffee_name']}">
                                    </div>
                                    <div class="col-6">
                                        <p class="searchResultProductName">${row['coffee_name']}</p>
                                        <p class = "searchResultProductPrice">₱ ${row['coffee_price']}</p>
                                    </div>
                                </div>`;
                        });
                        searchResult.innerHTML = html;

                        // Add click event listener to each searchResultEntry div
                        let searchResultEntries = document.querySelectorAll('.searchResultEntry');
                        searchResultEntries.forEach((entry) => {
                            entry.addEventListener('click', (e) => {
                            // Get the product id clicked.
                            let pid = entry.dataset.pid;
                            let productInfo = loadScript.products[pid];

                            let dialogForm =
                                "<h6 class='dialogProductName'>" +
                                productInfo["name"] +
                                "<span class='floatRight'>₱ " +
                                productInfo["price"] +
                                "</span></h6>" +
                                "<input type='number' id='orderQty' class='form-control' placeholder='Enter quantity...' min='1' />";

                            BootstrapDialog.confirm({
                                title: "Add to Order",
                                type: BootstrapDialog.TYPE_DEFAULT,
                                message: dialogForm,
                                callback: function (addOrder) {
                                if (addOrder) {
                                    let orderQty = parseInt(
                                    document.getElementById("orderQty").value
                                    );
                                    //If user did not input quantity
                                    if (isNaN(orderQty)) {
                                    BootstrapDialog.alert({
                                        title: "<strong>Error</strong>",
                                        type: BootstrapDialog.TYPE_DANGER,
                                        message: "Please input order quantity.",
                                    });
                                    //Prevent dialog closing
                                    return false;
                                    }
                                    loadScript.addToOrder(productInfo, pid, orderQty);
                                }
                                },
                            });
                            });
                        });
                        }
                    },
                    dataType: 'json'
                    })
                } else { //Display set to none - hide searchresult containner
                    searchResult.style.display = 'none';
                }
                }
    </script>


    <script src="script.js?v=<?= time() ?>"></script>
    <!-- Jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- Latest compiled and minified css -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <!-- Bootstrap -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.35.4/js/bootstrap-dialog.js" crossorigin="anonymous"></script>
</body>
</html>
