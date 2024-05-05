<?php


session_start();

if (!isset($_SESSION['customer_id'])) {
    $_SESSION['customer_id'] = 0;
}
include '../admin/products/connect.php';



// Function to fetch coffee products based on category
function fetchCoffeeProducts($category) {
    global $con;
    $sql = "SELECT * FROM coffee";
    if (!empty($category)) {
        // Adjust the query to filter by coffee_tag column
        $sql .= " WHERE coffee_tag = '$category'";
    }
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Check if the product is sold out
            $out_of_stock_class = ($row['coffee_stock'] == 0) ? 'sold-out' : '';
            echo '<div class="card ' . $out_of_stock_class . '" onclick="showModal(this, \'' . $row['coffee_name'] . '\', \'' . $row['coffee_price'] . '\', \'' . $row['coffee_image'] . '\', \'' . $row['coffee_description'] . '\', \'' . $row['coffee_stock'] . '\', \'' . $row['coffee_id'] . '\', \'' . $_SESSION['customer_id'] . '\')">';
            echo '<img src="../admin/products/' . htmlspecialchars($row['coffee_image']) . '" alt="' . $row['coffee_name'] . '" class="card-img"/>';
            echo '<div class="card-txt">';
            echo '<h3 class="h3-coffee">' . $row['coffee_name'] . '</h3>';
            echo '<p class="p-price">Php ' . $row['coffee_price'] . '</p>';
            echo '</div>';
            if ($row['coffee_stock'] == 0) {
                echo '<span class="sold-out-label">Sold Out</span>';
            }
            echo '</div>';
        }
    } else {
        echo '<p>No coffee found</p>';
    }
    mysqli_close($con);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/x-icon" href="../assets/images/SK-Icon.png">
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/coffee.css" />
    <link rel="stylesheet" href="../css/footer.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Coffee</title>
</head>

<body>
<div id="modal-overlay" style="display: none;"></div>
<div id="overlay" style="display: none;"></div>
    <!-- Header -->
    <?php include "../header/header.php"; ?>

<!-- T&C Modal -->
<div id="termsModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <!-- Modal header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
    <h3>Terms and Conditions</h3>
    <p>Welcome to StreetKohi.com, our online store! Street Kohi and its associates provide their services to you subject to the following conditions. If you visit or shop on this website, you accept these conditions. Please read them carefully.</p>

    <h4>1. Privacy</h4>
    <p>Please review our Privacy Policy Notice, which also governs your visit to our website, to understand our practices. </p>
        <ul>
            <li>We are committed to protecting your privacy. We use the information we collect about you to process orders and to gain insights to better your experience. When you order, we need to know your name, e-mail address, and shipping address – payment is processed by sending a copy of your receipts once paid and uploaded to the form so we cannot access your payment details. This allows us to process and fulfill your orders and notify you of their status. Street Kohi does not and will not sell, trade, or rent your personal information to others. If you have any questions or queries, please get in touch via streetkohi@gmail.com. </li>
         </ul>

    <h4>2. Electronic Communications </h4>
    <p>When you visit StreetKohi.com or send e-mails, you communicate with us electronically. You consent to receive communications from us electronically. We will communicate with you by e-mail or by posting notices on this site. You agree that all agreements, notices, disclosures, and other communications that we provide to you electronically satisfy any legal requirement that such communications be in writing. </p>

    <h4>3. Conditions of use </h4>
    <p>By using this website, you certify that you have read and reviewed this Agreement and that you agree to comply with its terms. If you do not want to be bound by the terms of this Agreement, you are advised to stop using the website accordingly. Street Kohi only grants use and access to this website, its products, and its services to those who have accepted its terms.</p>

    <h4>4. Intellectual Property</h4>
    <p>You agree that all materials, products, and services provided on this website are the property of Street Kohi, its affiliates, directors, officers, employees, agents, suppliers, or licensors, including all copyrights, trade secrets, trademarks, patents, and other intellectual property. You also agree that you will not reproduce or redistribute Street Kohi's intellectual property in any way, including electronic, digital, or new trademark registrations. You grant Street Kohi a royalty-free and non-exclusive license to display, use, copy, transmit, and broadcast the content you upload and publish. For issues regarding intellectual property claims, you should contact the company in order to agree.</p>

    <h4>5. Indemnification</h4>
    <p>You agree to indemnify Street Kohi and its affiliates and hold Street Kohi harmless against legal claims and demands that may arise from your use or misuse of our services. We reserve the right to select our own legal counsel. </p>

    <h4>6. Limitation on Liability</h4>
    <p>Street Kohi is not liable for any damages that may occur to you as a result of your misuse of our website. Street Kohi reserves the right to edit, modify, and change this Agreement at any time. We shall let our users know of these changes through electronic mail. This Agreement is an understanding between Street Kohi and the user, and this supersedes and replaces all prior agreements regarding using this website.</p>

    <h4>7. VAT (Philippine Law)</h4>
<ul>
  <li>VAT Inclusive Pricing: All prices displayed on StreetKohi.com include Value Added Tax (VAT) as required by Philippine law unless explicitly stated otherwise.</li>
  <li>VAT Registration Information: Street Kohi is a registered taxpayer with the Bureau of Internal Revenue (BIR), and our VAT registration details are available upon request.</li>
  <li>Issuance of Official Receipts: An official receipt reflecting the VAT-inclusive amount will be issued for every sale made by Street Kohi.</li>
  <li>VAT Exemption: Certain products or transactions may be exempt from VAT as per the provisions of the Philippine tax code. Applicable exemptions will be clearly communicated.</li>
</ul>

<h4>8. Order and Payment Information</h4>
<ul>
  <li>Product Information: All product descriptions, prices, and availability are subject to change without notice. Street Kohi strives to provide accurate and up-to-date information.</li>
  <li>Placing an Order: Orders can be placed through the StreetKohi.com website or designated ordering platform.</li>
  <li>Payment: Full payment is required at the time of placing the order, and gcash as the payment method is accepted as indicated during the checkout process.</li>
  <li>Order Confirmation: Upon completing a purchase on StreetKohi.com, you will receive an email confirmation containing details of your order and an attached invoice.</li>
  <li>Payment Confirmation: Payment is processed securely, and the invoice will only contain order details. Payment information is not accessible through the invoice.</li>
  <li>Delivery: Customers are responsible for the cost of delivery, which will be clearly communicated during the checkout process.</li>
  <li>Pickup: Pickup options may be available at the shop, and customers choosing this option must adhere to the scheduled pickup times.</li>
  <li>Customer Initiated Cancellation: Orders can be canceled within a specified timeframe, as communicated during the ordering process. Refunds, if applicable, will follow our refund policy.</li>
  <li>Street Kohi's Right to Cancel: Street Kohi reserves the right to cancel an order for various reasons, such as product unavailability or suspicion of fraudulent activity. In such cases, customers will be notified, and a refund will be processed in accordance with our refund policy.</li>
</ul>


    <h4>9. Contact Us</h4>
    <p>If you have any questions about these terms and conditions, please contact us:</p>
    <ul>
        <li>Email: streetkohi@gmail.com</li>
        <li>Phone: +63 915 670 2316</li>
        <li>Address: 63 Maginhawa, Quezon City 24 Mayaman, Quezon City Mandaluyong</li>
    </ul>
</div>
        <!-- Modal footer -->
        <div class="modal-footer justify-content-center">

                <!-- Buttons for accepting or rejecting terms -->
                <button type="button" class="btn btn-primary" id="acceptBtn">Accept</button>
                <button type="button" class="btn btn-secondary" id="rejectBtn">Reject</button>
            </div>
        </div>
    </div>
</div>

    <!-- Main Content Section -->
    <div class="main-content-container">
    <div class="main">
        <!-- Header (Div 1 coffee-for-kohi and cart) -->
         <div class="header">
            <span class="coffee-for-kohi">Coffee for Kohi</span>
            <?php if (isset($_SESSION['customer_id']) && $_SESSION['customer_id'] !== 0): ?>
                <a href="../addtocart/addtocart.php" class="cart" id="cartButton">
                    <span id="cartcount" style="background-color: red; font-size: 20px; position: absolute; color: white; border-radius: 20px; width: 20px;"> 0 </span>
                    <img class="pic-cart" src="../assets/images/cart.svg">
                </a>
            <?php endif; ?>
        </div>
        
        <!-- Sidebar -->
        <div class="sidebar">
            <h1 class="heading">Categories</h1>
            <ul class="category-list">
                <li class="category-item">
                    <a href="coffee.php">All</a>
                </li>
                <li class="category-item">
                    <a href="coffee.php?category=hot">Hot</a>
                </li>
                <li class="category-item">
                    <a href="coffee.php?category=iced">Iced</a>
                </li>
                <li class="category-item">
                    <a href="coffee.php?category=non-caffeine">Non-Caffeine</a>
                </li>
                <li class="category-item">
                    <a href="coffee.php?category=bread-pastry">Bread and Pastry</a>
                </li>
            </ul>
        </div>

        <!-- Main Content Section for Cards and Modal -->
        <div class="main-content">
            <hr class="separator">
            <div class="div4">
                <h1 class="h1-Hot">
                    <?php
                    // Display category name if available in URL parameter
                    if (isset($_GET['category'])) {
                        echo ucfirst($_GET['category']);
                    } else {
                        echo 'All';
                    }
                    ?>
                </h1>
                    <div class="tablecards">
                        <?php
                        // Fetch coffee products based on category
                        if (isset($_GET['category'])) {
                            fetchCoffeeProducts($_GET['category']);
                        } else {
                            fetchCoffeeProducts('');
                        }
                        ?>
                    </div>
            </div>
        </div>
    </div>

    <!-- HTML for the modal -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <img id="modal-img" alt="modal-image" width="100%" class="card-img" />
            <div class="card-txt">
                <h3 id="modal-title"></h3>
                <p id="modal-description"></p>
                <div id="add-to-cart-container">
                    <button id="add-to-cart-button" onclick="addToCart()">Add to Cart</button>
                    <div class="quantity-picker">
                        <label for="quantity">Quantity:</label>
                        <div class="quantity-controls">
                            <button onclick="decrementQuantity()">-</button>
                            <input type="number" id="quantity" name="quantity" min="1" value="1" />
                            <button onclick="incrementQuantity()">+</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Login Prompt Modal -->
<div id="loginPromptModal" class="loginPromptModal">
    <div class="modal-content">
        <span class="close" onclick="closeLoginPromptModal()">&times;</span>
        <div class="modal-body">
            <h2>Login Required</h2>
            <p>You need to log in before accessing your cart.</p>
            <button id="login-customer-btn" onclick="redirectToLoginPage()">Log In</button>
        </div>
    </div>
</div>


    </div>
    <!-- Footer -->
    <?php include "../footer/footer.php"; ?>
    <script>
    var custid = '<?= $_SESSION['customer_id'] ?>';    
    var customerId = <?php echo isset($_SESSION['customer_id']) ? json_encode($_SESSION['customer_id']) : 'null'; ?>;
    function updateCartButtonVisibility() {
    var cartButton = document.getElementById('cartButton');
    var loginPromptModal = document.getElementById('loginPromptModal');
    var overlay = document.getElementById('overlay');

    if (customerId) {
        // If the user is logged in, allow access to the cart
        cartButton.style.display = 'block';
        overlay.style.display = 'none'; // Ensure the overlay is hidden
    } else {
        // If the user is not logged in, prevent access to the cart and show a prompt
        cartButton.style.display = 'block';
        cartButton.onclick = function(event) {
            event.preventDefault(); // Prevent the default action (navigating to the cart page)
            loginPromptModal.style.display = 'block'; // Show the login prompt modal
            overlay.style.display = 'block'; // Show the overlay
        };
    }
}

// Function to close the login prompt modal and hide the overlay
function closeLoginPromptModal() {
    document.getElementById('loginPromptModal').style.display = 'none';
    document.getElementById('overlay').style.display = 'none'; // Hide the overlay
}

// Call the function to update the cart button visibility
updateCartButtonVisibility();

    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- JavaScript Files -->
    <script src="../javascript/coffee.js"></script>
    <script src="../javascript/header.js"></script>
    <script>
// Function to handle accept action for T&C
function acceptTerms() {
    // You can perform any necessary action here
    // For now, just close the modal
    document.getElementById('termsModal').style.display = 'none';
    // Set a flag in session storage to indicate that T&C has been accepted
    sessionStorage.setItem('tncAccepted', 'true');
}

// Function to handle reject action for T&C
function rejectTerms() {
    // Redirect to LandingPage.php if terms are rejected
    window.location.href = '../landingpage/LandingPage.php';
}

// Show the terms modal when the page loads if T&C has not been accepted
window.onload = function() {
    var tncAccepted = sessionStorage.getItem('tncAccepted');
    if (!tncAccepted) {
        showTermsModal();
    }
};

// Function to show the T&C modal
function showTermsModal() {
    document.getElementById('termsModal').style.display = 'block';
}

// Attach click event listeners to accept and reject buttons
document.getElementById('acceptBtn').addEventListener('click', acceptTerms);
document.getElementById('rejectBtn').addEventListener('click', rejectTerms);

</script>

</body>

</html>