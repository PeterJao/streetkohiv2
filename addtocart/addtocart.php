<?php
session_start();

if (!isset($_SESSION['customer_id'])) {
  header("Location: ../customer/loginCustomer.php");
  exit();
}

// Check if the request method is POST and required data is set
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['coffeeName']) && isset($_POST['coffeePrice']) && isset($_POST['quantity'])) {
   // Retrieve data from POST request
   $coffeeName = $_POST['coffeeName'];
   $coffeePrice = $_POST['coffeePrice'];
   $quantity = $_POST['quantity'];


   // Add item to cart
   $item = array(
       'coffeeName' => $coffeeName,
       'coffeePrice' => $coffeePrice,
       'quantity' => $quantity
   );


   // Initialize session cart if not already set
   if (!isset($_SESSION['cart'])) {
       $_SESSION['cart'] = array();
   }


   $_SESSION['cart'][] = $item;


   // Redirect back to coffee.php
   header("Location: ../coffee/coffee.php");
   exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="../css/header.css" />
   <link rel="stylesheet" href="../css/coffee.css" />
   <link rel="stylesheet" href="../css/footer.css" />
   <link rel="stylesheet" href="../css/addtocart.css" />
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
   <title>Cart</title>
   <link rel="icon" type="image/x-icon" href="../assets/images/SK-Icon.png">
</head>
<body>
   <?php include "../header/header.php"; ?>
   <div class="main-content-container">
   <div class="card-flex" style="max-height: 600px; overflow-y: auto;">
       <div class="d-flex justify-content-center row">
           <div class="col-md-10">
               <div class="p-2">
                   <h4>Cart</h4>
                   <div class="d-flex flex-row align-items-center"></div>
               </div>
      
                   <div >
                     
                   </div>
                    
                   <table class="table">
                     <thead>
                       <tr>
                         <th>Photo</th>
                         <th>Details</th>
                         <th>Price</th>
                         <th>Subtotal</th>
                         <th></th>
                       </tr>
                     </thead>
                     <tbody id="listcart">
                       
                     </tbody>
                   </table>

                   <div class="d-flex flex-row justify-content-between align-items-center p-2 bg-light mt-4 px-3 rounded">
                       <div class="text-grey">Subtotal</div>
                       <div>
                           <h5 class="text-grey" id="total"></h5>
                       </div>
                   </div>       
                   <br>
                   <div id="verification">
  
                   </div>       
                
           </div>
       </div>
   </div>
   
   </div>
   <?php include "../footer/footer.php"; ?>


   <!-- Include jQuery library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <script>
  var custid = '<?= $_SESSION['customer_id'] ?>'; 
  
  $.ajax({
    url:"../api/index.php?action=getcart",
    type: "POST",
    data: {
      custid:custid
    },
    dataType: "json",
    beforeSend: (e) => {
    Swal.fire({
      html: 'Loading...',
      didOpen: () => {
        Swal.showLoading()
      }
    })
    },
    success: (data) => { 

      Swal.close();

      $("#listcart").empty();

      if (data.cart.length > 0) {

        let total = 0;

        $.each(data.cart, (i, e)=>{

          $("#listcart").append(`

            <tr>
              <td><img class="rounded" src="../admin/products/${e.product_img}" width="70" alt="product-img"> </td>
              <td>
                <span class="font-weight-bold">${e.product_name}</span>
                <div class="d-flex flex-row product-desc">
                  Qty: ${e.product_quantity}
                </div>
              </td>
              <td> 
                <h5 class="text-grey">P${e.product_price}</h5>
              </td>
              <td> 
                <h5 class="text-grey">P${Number(e.product_price)*Number(e.product_quantity)}</h5>
              </td>
              <td>
                 <button class="btn btn-success edit-item" data-id="${e.id}" data-qty="${e.product_quantity}">Edit</button>
                 <button class="btn btn-danger remove-item" data-id="${e.id}">Remove</button>
              </td>
            </tr>
          `);

          total += Number(e.product_price)*Number(e.product_quantity);

        });


        $("#total").text("P"+total);

        $("#verification").empty().append(`
             <a href="../checkout/checkout.php">
                 <div class="d-flex flex-row align-items-center mt-3 p-2 bg-white rounded">
                    <button class="btn btn-warning btn-block btn-lg ml-2 pay-button" type="button">Proceed to Pay</button>
                 </div>
             </a> 
        `);

      }else{
        $("#listcart").append(`
          <div class="d-flex flex-row justify-content-between align-items-center p-2 bg-white mt-4 px-3 rounded">
            Empty Cart
          </div>
        `);

        $("#verification").empty().append(`
            <div class="alert alert-danger" role="alert">
             <center>
              Cart is empty! Cannot proceed to checkout.
             </center>
            </div>
        `);

      }


    },
    error: (xhr, ajaxOptions, thrownError) => {

        Swal.close(); 
      
        Swal.fire({
          icon: 'error',
          title: xhr.status,
          text: thrownError,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ok'
        }).then((result) => {
          if (result.isConfirmed) {
           
          }
        });

    }
   });

    $(document).on("click", ".edit-item", async (e)=>{

        const { value: qty } = await Swal.fire({
          title: "Edit quantity",
          input: "number",
          inputValue: e.target.dataset.qty,
          showCancelButton: true,
          confirmButtonText: "Update"
        });
        if (qty) {

          $.ajax({
            url:"../api/index.php?action=edititem",
            type: "POST",
            data: {
              id: e.target.dataset.id,
              newqty: qty
            },
            dataType: "json",
            beforeSend: (e) => {
            Swal.fire({
              html: 'Loading...',
              didOpen: () => {
                Swal.showLoading()
              }
            })
            },
            success: (data) => { 

              Swal.close();

              Swal.fire({
                  icon: 'success',
                  title: 'Updated!',
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Ok'
              }).then((result) => {
                  if (result.isConfirmed) {
                   location.reload();
                  } 
              });


            },
            error: (xhr, ajaxOptions, thrownError) => {

                Swal.close(); 
              
                Swal.fire({
                  icon: 'error',
                  title: xhr.status,
                  text: thrownError,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Ok'
                }).then((result) => {
                  if (result.isConfirmed) {
                   
                  }
                });

            }
           });

        }

    });

    $(document).on("click", ".remove-item", (e)=>{

      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, remove to cart"
      }).then((result) => {
        if (result.isConfirmed) { 

              $.ajax({
                url:"../api/index.php?action=removeitem",
                type: "POST",
                data: {
                  id: e.target.dataset.id
                },
                dataType: "json",
                beforeSend: (e) => {
                Swal.fire({
                  html: 'Loading...',
                  didOpen: () => {
                    Swal.showLoading()
                  }
                })
                },
                success: (data) => { 

                  Swal.close();

                  Swal.fire({
                      icon: 'success',
                      title: 'Removed!',
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      confirmButtonText: 'Ok'
                  }).then((result) => {
                      if (result.isConfirmed) {
                       location.reload();
                      } 
                  });


                },
                error: (xhr, ajaxOptions, thrownError) => {

                    Swal.close(); 
                  
                    Swal.fire({
                      icon: 'error',
                      title: xhr.status,
                      text: thrownError,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      confirmButtonText: 'Ok'
                    }).then((result) => {
                      if (result.isConfirmed) {
                       
                      }
                    });

                }
               });

        } 

      })


    });

   </script>
</body>
</html>
