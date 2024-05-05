function showModal(title, description) {
  document.getElementById("modal-title").innerText = title;
  document.getElementById("modal-description").innerText = description;
  document.getElementById(
    "modal-img"
  ).src = `./asset/img/${title.toLowerCase()}.png`;
  document.getElementById("modal").style.display = "flex";
}

function closeModal() {
  document.getElementById("modal").style.display = "none";
}

function incrementQuantity() {
  const quantityInput = document.getElementById("quantity");
  quantityInput.value = parseInt(quantityInput.value, 10) + 1;
}

function decrementQuantity() {
  const quantityInput = document.getElementById("quantity");
  const newValue = Math.max(parseInt(quantityInput.value, 10) - 1, 1);
  quantityInput.value = newValue;
}
