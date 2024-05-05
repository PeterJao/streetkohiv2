document.addEventListener('DOMContentLoaded', function () {
  // Function to show modal with coffee details
  function showModal(title, description) {
      document.getElementById("modal-title").innerText = title;
      document.getElementById("modal-description").innerText = description;

      let imageFileName;
      switch (title.toLowerCase()) {
          case "matcha":
              imageFileName = "1";
              break;
          case "espresso":
              imageFileName = "2";
              break;
          case "latte":
              imageFileName = "3";
              break;
          case "mocha":
              imageFileName = "4";
              break;
          case "poblacion":
              imageFileName = "5";
              break;
          case "latte special":
              imageFileName = "6";
              break;
          case "macchiato":
              imageFileName = "7";
              break;
          case "cappuccino":
              imageFileName = "8";
              break;
          default:
              imageFileName = "default";
      }

      document.getElementById("modal-img").src = `../assets/coffees/${imageFileName}.png`;
      document.getElementById("modal").style.display = "flex";
  }

  // Function to close modal
  function closeModal() {
      document.getElementById("modal").style.display = "none";
  }

  // Function to increment quantity
  function incrementQuantity() {
      const quantityInput = document.getElementById("quantity");
      quantityInput.value = parseInt(quantityInput.value, 10) + 1;
  }

  // Function to decrement quantity
  function decrementQuantity() {
      const quantityInput = document.getElementById("quantity");
      const newValue = Math.max(parseInt(quantityInput.value, 10) - 1, 1);
      quantityInput.value = newValue;
  }

  // Function to add to cart
  function addToCart() {
      alert("Added to cart!");
  }

  // Event listener for close button in modal
  document.querySelector(".close").addEventListener("click", closeModal);

  // Event listener to close modal when clicking outside modal content
  document.getElementById("modal").addEventListener("click", function (event) {
      let modalContent = document.querySelector(".modal-content");
      if (!modalContent.contains(event.target)) {
          closeModal();
      }
  });

  // Function to filter coffee items based on categories
  function filterCoffeeItems() {
    var checkboxes = document.querySelectorAll('.category-item input[type="checkbox"]');
    var coffeeItems = document.querySelectorAll('.card');

    function toggleVisibility(category) {
        coffeeItems.forEach(function (item) {
            if (category === 'all' || item.classList.contains(category)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // Set default visibility
    toggleVisibility('all');

    // Check "All" checkbox by default
    document.getElementById('all-checkbox').checked = true;

    // Add event listeners to checkboxes
    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            // Checkbox change event handling code...
        });
    });
}

// Call the filterCoffeeItems function
filterCoffeeItems();

// Expose functions globally
window.showModal = showModal;
window.closeModal = closeModal;
window.incrementQuantity = incrementQuantity;
window.decrementQuantity = decrementQuantity;
window.addToCart = addToCart;
});