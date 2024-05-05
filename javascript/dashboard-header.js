// script.js
function setActive(clickedElement) {
  // Remove the "active" class from all navigation items
  var navItems = document.querySelectorAll(".coffee-shop-header nav ul li a");
  navItems.forEach(function (item) {
    item.classList.remove("active");
  });

  // Add the "active" class to the clicked navigation item
  clickedElement.classList.add("active");
}

function toggleLogout() {
  window.location.href = "../../logout/logout.php";
}
