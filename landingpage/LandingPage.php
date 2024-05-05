<?php
session_start();
include '../admin/products/connect.php';

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Street Kohi</title>
    <link rel="icon" type="image/x-icon" href="../assets/images/SK-Icon.png">
    <link rel="stylesheet" href="../css/LandingPage.css" />
    <link
      href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css"
      rel="stylesheet"
    />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  </head>

  <body>
  <!-- Header -->
  <div>
        <?php include "../header/header.php" ?>
    </div>

<div class="main-content-container">
<div class="main-carousel">
      <div id="selected-image-container">
        <img id="selected-image" src="../assets/storeimg/2ndfloor1.jpg" alt="Selected Image" />
      </div>

      <section id="thumbnail-carousel" class="splide">
        <div class="splide__track">
          <ul class="splide__list">
            <li class="splide__slide">
              <img src="../assets/storeimg/2ndfloor1.jpg" alt="" loading="lazy" />
            </li>
            <li class="splide__slide">
              <img src="../assets/storeimg/2ndfloor2.jpg" alt="" loading="lazy" />
            </li>
            <li class="splide__slide">
              <img src="../assets/storeimg/2ndfloor3.jpg" alt="" loading="lazy" />
            </li>
            <li class="splide__slide">
              <img src="../assets/storeimg/Front1.jpg" alt="" loading="lazy" />
            </li>
            <li class="splide__slide">
              <img src="../assets/storeimg/MainFloor1.jpg" alt="" loading="lazy" />
            </li>
            <li class="splide__slide">
              <img src="../assets/storeimg/MainFloor2.jpg" alt="" loading="lazy" />
            </li>
            <li class="splide__slide">
              <img src="../assets/storeimg/MainFloor3.jpg" alt="" loading="lazy" />
            </li>
            <li class="splide__slide">
              <img src="../assets/storeimg/MainFloor4.jpg" alt="" loading="lazy" />
            </li>
            <li class="splide__slide">
              <img src="../assets/storeimg/MainFloor5.jpg" alt="" loading="lazy" />
            </li>
            <li class="splide__slide">
              <img src="../assets/storeimg/MainFloor6.jpg" alt="" loading="lazy" />
            </li>
            <li class="splide__slide">
              <img src="../assets/storeimg/MainFloor7.jpg" alt="" loading="lazy" />
            </li>
            <li class="splide__slide">
              <img src="../assets/storeimg/MainFloor8.jpg" alt="" loading="lazy" />
            </li>
            <li class="splide__slide">
              <img src="../assets/storeimg/MainFloor9.jpg" alt="" loading="lazy" />
            </li>
            <li class="splide__slide">
              <img src="../assets/storeimg/MainFloor10.jpg" alt="" loading="lazy" />
            </li>
            <li class="splide__slide">
              <img src="../assets/storeimg/MainFloor11.jpg" alt="" loading="lazy" />
            </li>
            <li class="splide__slide">
              <img src="../assets/storeimg/MainFloor12.jpg" alt="" loading="lazy" />
            </li>
            <li class="splide__slide">
              <img src="../assets/storeimg/MainFloor13.jpg" alt="" loading="lazy" />
            </li>
            <li class="splide__slide">
              <img src="../assets/storeimg/MainFloor14.jpg" alt="" loading="lazy" />
            </li>
            <li class="splide__slide">
              <img src="../assets/storeimg/MainFloor15.jpg" alt="" loading="lazy"/>
            </li>
          </ul>
        </div>
      </section>
      </div>

    <main>
      <div class="events-title">
        <h1>Upcoming Events</h1>
      </div>
      <div class="events-section">

        <?php

        include '../admin/products/connect.php';
        $sql = "SELECT * FROM `event` LIMIT 3";
        $result = mysqli_query($con, $sql);
        $check=mysqli_num_rows($result)>0;

        while ($row = mysqli_fetch_assoc($result)) {
          ?>

        <div class="event-container showevent" 
           data-event_name="<?= $row['event_name'] ?>" 
           data-event_description="<?= $row['event_description'] ?>" 
           data-event_date="<?= date("F j, Y", strtotime($row['event_date'])) ?>" 
           data-event_time="<?= date("g:i a", strtotime($row['event_time'])) ?>"
           data-event_price="<?= $row['event_price'] ?>"
           data-event_image="http://localhost/Streetkohi/admin/products/<?= $row['event_image'] ?>"
           data-event_venue="<?= $row['event_venue'] ?>"
           data-event_link="<?= $row['event_link'] ?>"
           >
          <div class="image-wrapper" style="pointer-events: none;">
            <img style="pointer-events: none;" src="http://localhost/Streetkohi/admin/products/<?= $row['event_image'] ?>" alt="Event 1" />
          </div>
          <div style="pointer-events: none;" class="event-name"><?= $row['event_name'] ?></div>
          <div style="pointer-events: none;" class="event-date"><?= date("F j, Y", strtotime($row['event_date'])) ?></div>
        </div>

          <?php
        }  

        ?>

      </div>
    </main>

    <div id="split-image-container">
      <div class="split-container">
        <div class="left-side-split">
          <img id="left-image" src="../assets/coffees/Cover.png" alt="Left Image" />
        </div>
    
        <button id="next-button-previous" onclick="loadPreviousImages()">
          <img src="../assets/images/drink1.png" alt="Previous" />
        </button>
    
        <button id="next-button-next" onclick="loadNextImages()">
          <img src="../assets/images/drink2.png" alt="Next" />
        </button>
    
        <div class="right-side-split">
          <img id="right-image" src="../assets/coffees/Covername.png" alt="Right Image" />
        </div>
      </div>
    </div>
</div>
</div>


    <div>
      <?php include "../footer/footer.php" ?>
    </div>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          
        <div class="card" style="width: 100%;">
          <img src="..." class="card-img-top" alt="..." id="img-src">
          <div class="card-body">
            <h5 class="card-title" id="date-time"></h5>
            <p class="card-text" id="desc"></p>
            <p class="card-text" id="price"></p>
            <p class="card-text" id="venue"></p>
            <a href="#" class="btn btn-primary" id="link">Open Link</a>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
<script src="../javascript/landingpage.js"></script>
<script src="../javascript/landingpagesplit.js"></script>
<script src="../javascript/header.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" ></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script type="text/javascript">
    
    $(document).on("click", ".showevent", (e)=>{
      $("#staticBackdrop").modal("show");
      console.log(e.target.dataset);
      $("#staticBackdropLabel").text(e.target.dataset.event_name);
      $("#img-src").attr("src", e.target.dataset.event_image);
      $("#link").attr("href", e.target.dataset.event_link);
      $("#date-time").text(e.target.dataset.event_date+" "+e.target.dataset.event_time);
      $("#desc").text(e.target.dataset.event_description);
      $("#price").text("Price: "+e.target.dataset.event_price);
      $("#venue").text("Venue: "+e.target.dataset.event_venue);
    });

  </script>
  
  </body>
</html>
