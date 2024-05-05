// Adapter function to convert parameters to match the original format
function showModalAdapter(id, title, price, image, description, stock) {
  // Call the original showModal function with appropriate arguments
  showModal(id, title, price, image, description, stock);
}

// Updated original showModal function with new logic
function showModal(card, title, price, image, description, stock, id, custid) {
  // Check if the card is sold out or out of stock
  if (card.classList.contains("sold-out") || stock === 0) {
    // If the item is sold out or out of stock, notify the user and return
    Swal.fire({
      icon: "info",
      title: "Out of Stock",
      text: "Sorry, this item is currently out of stock.",
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "OK",
    });
    return;
  }



  document.getElementById("modal-title").innerText = title;
  document.getElementById("modal-description").innerText = description;

  $("#modal-title").data("id", id);
  $("#modal-title").data("title", title);
  $("#modal-title").data("price", price);
  $("#modal-title").data("image", image);
  $("#modal-title").data("custid", custid);

  document.getElementById("modal-img").src = "../admin/products/" + image;
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
  const quantity = $("#quantity").val(); // Get the quantity of items being added to the cart

  if (customerId) {
    Swal.fire({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, add to cart",
    }).then((result) => {
      if (result.isConfirmed) {
        // Perform AJAX request to add items to the cart
        $.ajax({
          url: "../api/index.php?action=addtocart",
          type: "POST",
          data: {
            product_quantity: quantity,
            product_name: $("#modal-title").data("title"),
            product_id: $("#modal-title").data("id"),
            product_price: $("#modal-title").data("price"),
            custid: $("#modal-title").data("custid"),
            image: $("#modal-title").data("image"),
            type: 0,
          },
          dataType: "json",
          beforeSend: (e) => {
            Swal.fire({
              html: "Loading...",
              didOpen: () => {
                Swal.showLoading();
              },
            });
          },
          success: (data) => {
            Swal.close();

            if (data.response == 1) {

            Swal.fire({
              icon: "success",
              title: "Successfully added!",
              confirmButtonColor: "#3085d6",
              cancelButtonColor: "#d33",
              confirmButtonText: "Ok",
            }).then((result) => {
              if (result.isConfirmed) {
               
                location.reload(); // Reload the page
              }
            });

            }else{

            Swal.fire({
              icon: "error",
              title: "Not enough Stock, Available stock is "+data.stock,
              confirmButtonColor: "#3085d6",
              cancelButtonColor: "#d33",
              confirmButtonText: "Ok",
            }).then((result) => {
              if (result.isConfirmed) {
               
               
              }
            });

            }


          },
          error: (xhr, ajaxOptions, thrownError) => {
            Swal.close();
            Swal.fire({
              icon: "error",
              title: xhr.status,
              text: thrownError,
              confirmButtonColor: "#3085d6",
              cancelButtonColor: "#d33",
              confirmButtonText: "Ok",
            }).then((result) => {
              if (result.isConfirmed) {
                // Handle error
              }
            });
          },
        });
      }
    });
  } else {
    document.getElementById("loginPromptModal").style.display = "block";
    document.getElementById("modal-overlay").style.display = "block";
  }
}

function updateCoffeeStock(quantity) {
  // Perform AJAX request to update the coffee_stock
  $.ajax({
    url: "../api/index.php?action=updatestock",
    type: "POST",
    data: {
      quantity: quantity,
    },
    // Success and error handling...
  });
}

function closeLoginPromptModal() {
  document.getElementById("loginPromptModal").style.display = "none";
  document.getElementById("modal-overlay").style.display = "none";
  document.getElementById("overlay").style.display = "none";
}

// Function to redirect to login page
function redirectToLoginPage() {
  window.location.href = "../customer/loginCustomer.php";
}

document
  .getElementById("modal-overlay")
  .addEventListener("click", closeLoginPromptModal);
document
  .getElementById("overlay")
  .addEventListener("click", closeLoginPromptModal);

// Document ready function
$(document).ready(() => {
  fetch("https://api.ipify.org")
    .then((x) => x.text())
    .then((y) => {
      console.log(y);
      $("#modal-title").data("ip", y);
      console.log(y);
      $.ajax({
        url: "../api/index.php?action=getcart",
        type: "POST",
        data: {
          custid: custid,
        },
        dataType: "json",
        beforeSend: (e) => {
          Swal.fire({
            html: "Loading...",
            didOpen: () => {
              Swal.showLoading();
            },
          });
        },
        success: (data) => {
          Swal.close();
          $("#cartcount").text(data.cart.length);
        },
        error: (xhr, ajaxOptions, thrownError) => {
          Swal.close();
          Swal.fire({
            icon: "error",
            title: xhr.status,
            text: thrownError,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ok",
          }).then((result) => {
            if (result.isConfirmed) {
              // Handle error
            }
          });
        },
      });
    });
});

// Function to filter coffee items based on categories
function filterCoffeeItems() {
  var checkboxes = document.querySelectorAll(
    '.category-item input[type="checkbox"]'
  );
  var coffeeItems = document.querySelectorAll(".card");

  function toggleVisibility(category) {
    coffeeItems.forEach(function (item) {
      if (category === "all" || item.classList.contains(category)) {
        item.style.display = "block";
      } else {
        item.style.display = "none";
      }
    });
  }

  // Set default visibility
  toggleVisibility("all");

  // Check "All" checkbox by default
  document.getElementById("all-checkbox").checked = true;

  // Add event listeners to checkboxes
  checkboxes.forEach(function (checkbox) {
    checkbox.addEventListener("change", function () {
      var selectedCategories = []; // Array to store selected categories
      // Loop through each checkbox to check if it's checked
      checkboxes.forEach(function (cb) {
        if (cb.checked) {
          selectedCategories.push(cb.id); // Add checked category to array
        }
      });

      // If no categories are selected, show all coffee items
      if (selectedCategories.length === 0) {
        toggleVisibility("all");
      } else {
        // Hide all coffee items and then show only those matching selected categories
        coffeeItems.forEach(function (item) {
          item.style.display = "none";
        });
        selectedCategories.forEach(function (category) {
          toggleVisibility(category);
        });
      }
    });
  });
}

// Call the filterCoffeeItems function
filterCoffeeItems();
