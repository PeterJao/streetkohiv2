<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    />
    <link rel="stylesheet" href="experiment.css" />
    <link rel="stylesheet" href="testing.css" />

    <title>EXPERIMENT</title>
  </head>
  <body>
    <div class="container">
      <div class="category">
        <form>
          <h3>Category</h3>
          <div class="form-check">
            <input
              class="form-check-input"
              type="checkbox"
              value=""
              id="coffee"
            />
            <label class="form-check-label" for="coffee">Coffee</label>
          </div>
          <div class="form-check">
            <input
              class="form-check-input"
              type="checkbox"
              value=""
              id="iced"
            />
            <label class="form-check-label" for="iced">Iced</label>
          </div>
          <div class="form-check">
            <input
              class="form-check-input"
              type="checkbox"
              value=""
              id="non-caffeine"
            />
            <label class="form-check-label" for="non-caffeine"
              >Non-caffeine</label
            >
          </div>
          <div class="form-check">
            <input
              class="form-check-input"
              type="checkbox"
              value=""
              id="bread-pastries"
            />
            <label class="form-check-label" for="bread-pastries"
              >Bread and Pastries</label
            >
          </div>
        </form>
        <!-- category -->
      </div>
      <div class="main">
        <div class="div4">
            <div class="tablecards">
              <div class="card" onclick="showModal('Matcha', 'Php 180')">
                <img
                  src="./asset/img/matcha.jpg"
                  alt="matcha"
                  width="100%"
                  class="card-img"
                />
                <div class="card-txt">
                  <h3>Matcha</h3>
                  <p>Php 180</p>
                </div>
              </div>
              <div class="card" onclick="showModal('Espresso', 'Php 180')">
                <img
                  src="./asset/img/espresso.png"
                  alt="espresso"
                  width="100%"
                  class="card-img"
                />
                <div class="card-txt">
                  <h3>Espresso</h3>
                  <p>Php 180</p>
                </div>
              </div>
              <div class="card" onclick="showModal('Espresso', 'Php 180')">
                <img
                  src="./asset/img/espresso.png"
                  alt="espresso"
                  width="100%"
                  class="card-img"
                />
                <div class="card-txt">
                  <h3>Espresso</h3>
                  <p>Php 180</p>
                </div>
              </div>
              <div class="card" onclick="showModal('Espresso', 'Php 180')">
                <img
                  src="./asset/img/espresso.png"
                  alt="espresso"
                  width="100%"
                  class="card-img"
                />
                <div class="card-txt">
                  <h3>Espresso</h3>
                  <p>Php 180</p>
                </div>
              </div>
          </div>
          <div class="div5"></div>
        </div>
    
        <!-- Modal and script references -->
        <div id="modal" class="modal">
          <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <img id="modal-img" alt="modal-image" width="100%" class="card-img" />
            <div class="card-txt">
              <h3 id="modal-title"></h3>
              <p id="modal-description"></p>
              <p class="modal-description-extra">
                Lorem Ipsum has been the industry's standard dummy text ever since
                the 1500s, infused with coconut milk.
              </p>
    
              <div id="add-to-cart-container">
                <button id="add-to-cart-button" onclick="addToCart()">
                  Add to Cart
                </button>
    
                <div class="quantity-picker">
                  <label for="quantity">Quantity:</label>
                  <div class="quantity-controls">
                    <button onclick="decrementQuantity()">-</button>
                    <input
                      type="number"
                      id="quantity"
                      name="quantity"
                      min="1"
                      value="1"
                    />
                    <button onclick="incrementQuantity()">+</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    
        <script src="card.js"></script>
          </div>
          <div class="footer"></div>
        </div>
    
        <!-- Modal and script references -->
        <div id="modal" class="modal">
          <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <img id="modal-img" alt="modal-image" width="100%" class="card-img" />
            <div class="card-txt">
              <h3 id="modal-title"></h3>
              <p id="modal-description"></p>
              <p class="modal-description-extra">
                Lorem Ipsum has been the industry's standard dummy text ever since
                the 1500s, infused with coconut milk.
              </p>
    
              <div id="add-to-cart-container">
                <button id="add-to-cart-button" onclick="addToCart()">
                  Add to Cart
                </button>
    
                <div class="quantity-picker">
                  <label for="quantity">Quantity:</label>
                  <div class="quantity-controls">
                    <button onclick="decrementQuantity()">-</button>
                    <input
                      type="number"
                      id="quantity"
                      name="quantity"
                      min="1"
                      value="1"
                    />
                    <button onclick="incrementQuantity()">+</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <!-- main -->
      </div>
    </div>
  </body>
</html>
