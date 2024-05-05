<?php
session_start();
include '../admin/products/connect.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/blogs.css" />
    <link rel="icon" type="image/x-icon" href="../assets/images/SK-Icon.png">
    <title>Blogs</title>
</head>
<body>
    <!-- Header -->
    <div>
        <?php include "../header/header.php" ?>
    </div>
<div class="main-content-container">
<!-- Hero Image -->
<div class="photocontainer">
            <img src="../assets/storeimg/Front2.jpg" alt="Street Kohi Front">
        </div>

    <!-- About Us -->
    <div class="row aboutus">
        <div class="column">
            <h1>About Us</h1>
        </div>
        <div class="column aboutustxt">
            <p>
                During the pandemic, online business was a trending business at that time. In the midst of the crisis, people always thought of how they could survive during this pandemic. People developed anxieties and depression during this time. Research says that coffee can decrease the feeling of anxiety and depression. Coffee can help you focus on the things that are present right now and not the things that make you lose control. Our founder saw an opportunity to start a Mobile cafe known as Street Kohi to help people gain focus and to promote a Filipino-Japanese theme cafe that you can find in the streets of Manila.
            </p>
            
            <div id="read-more" style="display: none;">
                <p>
                    As a startup business, risk is always there to remind us that we need to work and innovate to help small businesses and local farmers in the Philippines by buying and supporting their products. With this, we can help them grow and promote local products in the Philippines. With our startup business, we help create employment opportunities for those who lost their jobs in the midst of the pandemic.
                </p>
                <p>
                    After a year in the coffee industry, Street Kohi has made its name through numerous vlogs, blogs, and events. Good reviews are given to Street Kohi due to its good service and the quality of the product. By the end of 2022, we aim to get more corporate clients, events, and to have another mobile cafe touring around Manila so that people can taste and enjoy the coffee that we love.
                </p>
            </div>
            <button class="readmorebtn" onclick="toggleReadMore()">Read More</button>
        </div>
    </div>


    <!-- Image Grid -->
<div class="gallerycontainer">
    <div class="row">
        <div class="column">
            <div class="image-container">
                <div class="loader"></div>
                <img class="gallery-image" src="../assets/storeimg/Int1.jpg" alt="Image 7" loading="lazy">
            </div>
            <div class="image-container">
                <div class="loader"></div>
                <img class="gallery-image" src="../assets/storeimg/Ext1.jpg" alt="Image 8" >
            </div>
            <div class="image-container">
                <div class="loader"></div>
                <img class="gallery-image" src="../assets/storeimg/Int2.jpg" alt="Image 9" loading="lazy">
            </div>
        </div>
        <div class="column">
            <div class="image-container">
                <div class="loader"></div>
                <img class="gallery-image" src="../assets/storeimg/Ext2.jpg" alt="Image 4" loading="lazy">
            </div>
            <div class="image-container">
                <div class="loader"></div>
                <img class="gallery-image" src="../assets/storeimg/Int3.jpg" alt="Image 5" loading="lazy">
            </div>
            <div class="image-container">
                <div class="loader"></div>
                <img class="gallery-image" src="../assets/storeimg/Ext3.jpg" alt="Image 6" >
            </div>
        </div>
        <div class="column">
            <div class="image-container">
                <div class="loader"></div>
                <img class="gallery-image" src="../assets/storeimg/Int4.jpg" alt="Image 1" >
            </div>
            <div class="image-container">
                <div class="loader"></div>
                <img class="gallery-image" src="../assets/storeimg/Ext4.jpg" alt="Image 2" loading="lazy">
            </div>
            <div class="image-container">
                <div class="loader"></div>
                <img class="gallery-image" src="../assets/storeimg/Int5.jpg" alt="Image 3" loading="lazy">
            </div>
        </div>
    </div>
</div>


    <!-- Core Values -->
    <div class="valuescontainer">
        <div class="row values">
            <div class="column mission">
                <img src="../assets/images/drink1.png" alt="Icon" class="Drink Icon">
                <h2>Mission</h2>
                <p>Make the best coffee that everyone can buy and enjoy.</p>
            </div>
            <div class="column core">
                <img src="../assets/images/drink3.png" alt="Icon" class="Drink Icon">
                <h2>Core Values</h2>
                <p>To tour the streets of Manila so we can get the people to taste our coffee as well as to give people an opportunity to have jobs.</p>
            </div>
            <div class="column vision">
                <img src="../assets/images/drink2.png" alt="Icon" class="Drink Icon">
                <h2>Vision</h2>
                <p>To provide everyone's coffee craving and satisfaction</p>
            </div>
        </div>
    </div>


    <!-- Image Grid -->
    <div class="gallerycontainer">
        <div class="row">
            <div class="column">
                <div class="image-container">
                    <div class="loader"></div>
                    <img class="gallery-image" src="../assets/storeimg/MainFloor13.jpg" alt="Image 1" loading="lazy">
                </div>
                <div class="image-container">
                    <div class="loader"></div>
                    <img class="gallery-image" src="../assets/storeimg/2ndfloor3.jpg" alt="Image 2">
                </div>
                <div class="image-container">
                    <div class="loader"></div>
                    <img class="gallery-image" src="../assets/storeimg/MainFloor5.jpg" alt="Image 3" loading="lazy">
                </div>
            </div>
            <div class="column">
                <div class="image-container">
                    <div class="loader"></div>
                    <img class="gallery-image" src="../assets/storeimg/MainFloor6.jpg" alt="Image 4">
                </div>
                <div class="image-container">
                    <div class="loader"></div>
                    <img class="gallery-image" src="../assets/storeimg/MainFloor7.jpg" alt="Image 5" loading="lazy">
                </div>
                <div class="image-container">
                    <div class="loader"></div>
                    <img class="gallery-image" src="../assets/storeimg/MainFloor8.jpg" alt="Image 6">
                </div>
            </div>
            <div class="column">
                <div class="image-container">
                    <div class="loader"></div>
                    <img class="gallery-image" src="../assets/storeimg/MainFloor8.jpg" alt="Image 7" loading="lazy">
                </div>
                <div class="image-container">
                    <div class="loader"></div>
                    <img class="gallery-image" src="../assets/storeimg/MainFloor9.jpg" alt="Image 8" loading="lazy">
                </div>
                <div class="image-container">
                    <div class="loader"></div>
                    <img class="gallery-image" src="../assets/storeimg/MainFloor10.jpg" alt="Image 9" loading="lazy">
                </div>
            </div>
        </div>
    </div>

</div>
    
    <div>
        <?php include "../footer/footer.php" ?>
    </div>
</body>
<script>
    function toggleReadMore() {
        var moreText = document.getElementById("read-more");
        var button = document.querySelector('.readmorebtn');

        if (moreText.style.display === "none" || moreText.style.maxHeight === "0") {
            moreText.style.display = "block";
            moreText.classList.add("show-more");
            button.textContent = 'Show Less';
            // Add logic to show additional content if necessary
        } else {
            moreText.style.display = "none";
            moreText.classList.remove("show-more");
            button.textContent = 'Read More';
            // Add logic to hide additional content if necessary
        }
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const galleryImages = document.querySelectorAll('.gallery-image');

        galleryImages.forEach(image => {
            image.addEventListener('load', () => {
                image.classList.add('loaded');
                const loader = image.parentElement.querySelector('.loader');
                if (loader) {
                    loader.style.display = 'none';
                }
            });

            image.addEventListener('error', () => {
                console.error('Error loading image:', image.src);
                const loader = image.parentElement.querySelector('.loader');
                if (loader) {
                    loader.style.display = 'none';
                }
            });
        });
    });
</script>

<script src="../javascript/header.js"></script>
</html>
