document.addEventListener("DOMContentLoaded", function () {
  // Function to set the "active" class to the appropriate link
  function setActiveLink(url) {
    // Remove the "active" class from all navigation items
    var navItems = document.querySelectorAll(
      ".coffee-shop-header nav ul li .nav-links"
    );
    navItems.forEach(function (item) {
      item.classList.remove("active");
    });

    // Find the link that corresponds to the current URL and add the "active" class
    var activeLink = document.querySelector(
      '.coffee-shop-header nav ul li .nav-links[href="' + url + '"]'
    );
    if (activeLink) {
      activeLink.classList.add("active");
    }
  }

  // Function to handle click events on navigation links
  function handleNavClick(event) {
    // Prevent the default behavior of the link
    event.preventDefault();

    // Get the clicked link's href attribute
    var clickedHref = event.target.getAttribute("href");

    // Set the "active" class for the clicked link
    setActiveLink(clickedHref);

    // Store the active link in sessionStorage
    sessionStorage.setItem("activeLink", clickedHref);

    // Manually navigate to the clicked page
    window.location.href = clickedHref;
  }

  // Attach the handleNavClick function to all navigation links
  var navLinks = document.querySelectorAll(".nav-links");
  navLinks.forEach(function (link) {
    link.addEventListener("click", handleNavClick);
  });

  // Set "active" class based on sessionStorage on page load
  var storedActiveLink = sessionStorage.getItem("activeLink");
  if (storedActiveLink) {
    setActiveLink(storedActiveLink);
  }
});
