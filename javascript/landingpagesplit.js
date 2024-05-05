const imagesLeft = [
  "../assets/coffees/Mocha.png",
  "../assets/coffees/Matcha.png",
  "../assets/coffees/Americano.png",
  "../assets/coffees/Cappuccino.png",
  "../assets/coffees/CaramelMacchiato.png",
  "../assets/coffees/Poblacion.png",
];
const imagesRight = [
  "../assets/coffees/Mocha-Description.png",
  "../assets/coffees/Matcha-Description.png",
  "../assets/coffees/Americano-Description.png",
  "../assets/coffees/Cappuccino-Description.png",
  "../assets/coffees/CaramelMacchiato-Description.png",
  "../assets/coffees/Poblacion-Description.png",
];
let currentImageIndexLeft = 0;
let currentImageIndexRight = 0;

function loadImages(isNext) {
  // Determine the direction (next or previous)
  const direction = isNext ? 1 : -1;

  // Load image for the left side
  currentImageIndexLeft =
    (currentImageIndexLeft + direction + imagesLeft.length) % imagesLeft.length;
  const leftImageContainer = document.querySelector(".left-side-split");
  const nextLeftImage = document.createElement("img");

  nextLeftImage.src = imagesLeft[currentImageIndexLeft];
  nextLeftImage.alt = "Left Image " + (currentImageIndexLeft + 1);

  leftImageContainer.style.animation = "none";
  void leftImageContainer.offsetWidth;
  leftImageContainer.style.animation = "slideUp 0.5s ease-in-out";
  leftImageContainer.style.animationFillMode = "forwards";

  nextLeftImage.onload = () => {
    leftImageContainer.innerHTML = "";
    leftImageContainer.appendChild(nextLeftImage);
  };

  // Load image for the right side
  currentImageIndexRight =
    (currentImageIndexRight + direction + imagesRight.length) %
    imagesRight.length;
  const rightImageContainer = document.querySelector(".right-side-split");
  const nextRightImage = document.createElement("img");

  nextRightImage.src = imagesRight[currentImageIndexRight];
  nextRightImage.alt = "Right Image " + (currentImageIndexRight + 1);

  rightImageContainer.style.animation = "none";
  void rightImageContainer.offsetWidth;
  rightImageContainer.style.animation = "slideDown 0.5s ease-in-out";
  rightImageContainer.style.animationFillMode = "forwards";

  nextRightImage.onload = () => {
    rightImageContainer.innerHTML = "";
    rightImageContainer.appendChild(nextRightImage);
  };
}

function loadNextImages() {
  loadImages(true);
}

function loadPreviousImages() {
  loadImages(false);
}
