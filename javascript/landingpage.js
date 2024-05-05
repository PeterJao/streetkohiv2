document.addEventListener("DOMContentLoaded", function () {
  // Initialize the thumbnail carousel
  var thumbnailCarousel = new Splide("#thumbnail-carousel", {
    fixedWidth: 100,
    fixedHeight: 60,
    gap: 10,
    rewind: true,
    pagination: false,
    isNavigation: true,
    breakpoints: {
      600: {
        fixedWidth: 60,
        fixedHeight: 44,
      },
    },
    autoplay: true,
    interval: 5000,
    pauseOnHover: false,
  }).mount();

  // Get the selected image container
  var selectedImage = document.getElementById("selected-image");

  // Handle click event on thumbnail carousel
  thumbnailCarousel.on("click", function (slide) {
    // Go to the selected slide in the main carousel
    thumbnailCarousel.go(slide.index);

    // Get the URL of the selected image
    var selectedImageUrl = slide.slide.querySelector("img").getAttribute("src");

    // Fade out the current selected image
    selectedImage.style.opacity = 0;

    // Set the new image URL after a delay and fade it in
    setTimeout(function () {
      selectedImage.setAttribute("src", selectedImageUrl);
      selectedImage.style.opacity = 1;
    }, 300);

    // Restart autoplay for the thumbnail carousel
    thumbnailCarousel.autoplay.start();
  });

  // Handle move event on thumbnail carousel
  thumbnailCarousel.on("move", function (newIndex, oldIndex, destIndex) {
    // Get the URL of the selected image during movement
    var selectedImageUrl = thumbnailCarousel.Components.Elements.slides[
      destIndex
    ]
      .querySelector("img")
      .getAttribute("src");

    // Determine the direction of movement
    var direction = newIndex > oldIndex ? "left" : "right";

    // Slide out the current selected image
    selectedImage.style.transition = "transform 0.3s ease";
    selectedImage.style.transform =
      direction === "left" ? "translateX(-100%)" : "translateX(100%)";

    // Set the new image URL after a delay and slide it in
    setTimeout(function () {
      selectedImage.setAttribute("src", selectedImageUrl);
      selectedImage.style.transition = "transform 0s";
      selectedImage.style.transform =
        direction === "left" ? "translateX(100%)" : "translateX(-100%)";
      setTimeout(function () {
        selectedImage.style.transition = "transform 0.3s ease";
        selectedImage.style.transform = "translateX(0)";
      }, 50);
    }, 300);
  });

  // Prevent arrow keys from navigating the page
  window.addEventListener("keydown", function (e) {
    if (e.key === "ArrowLeft" || e.key === "ArrowRight") {
      e.preventDefault();
    }
  });

  // Initialize the coffee slider
  var coffeeSlider = new Splide("#coffee-slider", {
    type: "fade",
    heightRatio: 0.5,
  }).mount();

  // Handle click event on thumbnail container
  document
    .querySelector(".thumbnail-container")
    .addEventListener("click", function () {
      // Get data attributes for the new coffee
      var newImage = this.getAttribute("data-image");
      var newName = this.getAttribute("data-name");
      var newDetails = this.getAttribute("data-details");

      // Update the current slide with new content
      var currentSlide =
        coffeeSlider.Components.Elements.slides[coffeeSlider.index];
      currentSlide
        .querySelector(".left-side img")
        .setAttribute("src", newImage);
      currentSlide.querySelector(".right-side h2").textContent = newName;
      currentSlide.querySelector(".right-side p").textContent = newDetails;
    });
});
