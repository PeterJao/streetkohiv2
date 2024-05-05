<?php
session_start();

if (!isset($_SESSION['customer_id'])) {
    header("Location: ../customer/loginCustomer.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout</title>
    <link rel="icon" type="image/x-icon" href="../assets/images/SK-Icon.png">
    <!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">


	<!-- Demo CSS (No need to include it into your project) -->
	<link rel="stylesheet" href="../css/checkout.css">
        <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
  </head>
  <body>
  
  <!-- NavBar -->
  <div>
    <?php include "../header/header.php";  ?>
  </div>

 <main>
     <!-- Cart Card -->
    <div class="main-content-container">
      <div class="row">
          <div class="col-md-4 order-md-2 mb-4">
              <h4 class="d-flex justify-content-between align-items-center mb-3">
                  <span class="text-muted">Your cart</span>
                  <span class="badge badge-secondary badge-pill">3</span>
              </h4>
            <ul class="list-group mb-3" id="list-item">
            </ul>
          </div>
        </div>
      </div>
    </div>

        

         <!-- start of delivery details -->
            <div class="col-md-8 order-md-1">
            <h4 class="mb-3">Delivery Details</h4>
            <form class="needs-validation" novalidate>
                <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="firstName">Full name</label>
                    <input type="text" class="form-control" id="fullName" placeholder="Juan Dela Cruz" value="" required>
                    <div class="invalid-feedback">
                    This is required.
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="lastName">Contact Number</label>
                    <input type="number" class="form-control" id="contactNumber" placeholder="09123456789" value="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required>
                    <div class="invalid-feedback">
                    Contact Number is required.
                    </div>
                </div>
                </div>

                <!-- Ito nag add ako ng incorrect email at correct email -->
                <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="firstName">Email</label>
                    <input type="text" class="form-control " id="email" placeholder="jdlcruz@gmail.com" value="" required>
                    <div class="invalid-feedback">
                    Incorrect email.
                    </div>
                    <div class="valid-feedback">
                    Correct email.
                    </div>
                </div>


                <div class="col-md-6 mb-3">
                    <label for="lastName">City</label>
                    <input type="text" class="form-control" id="City" placeholder="Mandaluyong" value="" required>
                    <div class="invalid-feedback">
                    Required.
                    </div>
                </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="firstName">Barangay</label>
                        <input type="text" class="form-control" id="brgy" placeholder="Barangay 143" value="" required>
                        <div class="invalid-feedback">
                        Required.
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lastName">Street Name</label>
                        <input type="text" class="form-control" id="streetNum" placeholder="Maginhawa Street" value="" required>
                        <div class="invalid-feedback">
                        Required.
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="firstName">Unit</label>
                        <input type="text" class="form-control" id="unit" placeholder="Put NA if not applicable" value="" required>
                        <div class="invalid-feedback">
                        Required.
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="lastName">Building</label>
                        <input type="text" class="form-control" id="bldg" placeholder="Put NA if not applicable" value="" required>
                        <div class="invalid-feedback">
                        Required.
                        </div>
                    </div>

                </div>

                <!-- start of qr card -->
                <div class="row">
                <div class="card mb-3">
                    <img src="../assets/images/qr.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5> Gcash Number: 09156702316</h5>
                        <p class="card-text">Reminder: Upload your proof of payment so we can process your order</p>
                        <input type="file" class="filepond-233" name="image233" data-allow-reorder="true" data-max-file-size="15MB" data-max-files="15" />
                        <br>
                        <br>
                    </div>    
                </div>
            </div>



                 <!--buttons-->
                <div class="my-unique-container">
                    <div class="container">
                        <div class="button">
                            <button type="button" class="btn btn-primary btn-lg btn-block" data-email="0" data-url="0" id="submit-checkout" >Submit</button>
                        </div>
                    </div>
                </div>


        </div>
        </div>
 </main>
 
  <footer> 
    <?php include "../footer/footer.php"; ?>
  </footer>
  
<!-- Bootstrap 5 JavaScript Bundle with Popper -->
   <!-- Include jQuery library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.min.js"></script>
    <script type="text/javascript" src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.min.js"></script>
    <script type="text/javascript" src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.min.js"></script>
    <script type="text/javascript" src="https://unpkg.com/filepond-plugin-image-crop/dist/filepond-plugin-image-crop.min.js"></script> 
    <script type="text/javascript" src="https://unpkg.com/filepond-plugin-image-resize/dist/filepond-plugin-image-resize.min.js"></script> 
    <script type="text/javascript" src="https://unpkg.com/filepond-plugin-image-transform/dist/filepond-plugin-image-transform.min.js"></script>
    <script type="text/javascript" src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>
    <script type="text/javascript" src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
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

              $("#list-item").empty();


              if (data.cart.length > 0) {

                let total = 0;

                $.each(data.cart, (i, e)=>{

                  $("#list-item").append(`
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <h6 class="my-0">${e.product_name} (x${e.product_quantity})</h6>
                        </div>
                        <span class="text-muted">₱${Number(e.product_price)*Number(e.product_quantity)}</span>
                    </li>
                  `);  
                    
                  total += Number(e.product_price)*Number(e.product_quantity);

                });

                $("#list-item").append(`
                    <li class="list-group-item d-flex justify-content-between">
                    <span>Total: </span>
                    <strong>₱${total}</strong>
                    </li>
                `); 
   

              }else{
                $("#list-item").append(`
                    <li class="list-group-item d-flex justify-content-between">
                    <span>Empty.</span>
                    </li>
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


            FilePond.registerPlugin(
              // encodes the file as base64 data
              FilePondPluginFileEncode,
              
              // validates files based on input type
              FilePondPluginFileValidateType,
              
              // corrects mobile image orientation
              FilePondPluginImageExifOrientation,
              
              // previews the image
              
              // crops the image to a certain aspect ratio
              FilePondPluginImageCrop,
              
              // resizes the image to fit a certain size
              FilePondPluginImageResize,
              
              // applies crop and resize information on the client
              FilePondPluginImageTransform
            );

                // Select the file input and use create() to turn it into a pond
            // var specupload = FilePond.create(
            //   document.querySelector('.filepond-233'),
            //   {
            //   labelIdle: `Drag & Drop your files or <span class="filepond--label-action">Browse</span> Only jpg and png are accepted`
            //   }
            // );

              // Select the file input and create FilePond instance
              var specupload = FilePond.create(
                  document.querySelector('.filepond-233'),
                  {
                      labelIdle: `Drag & Drop your files or <span class="filepond--label-action">Browse</span> Only jpg and png are accepted`,
                      allowFileTypeValidation: true, // Enable file type validation
                      acceptedFileTypes: ['image/jpeg', 'image/png', 'image/jpg'], // Specify accepted file types
                      fileValidateTypeDetectType: (source, type) =>
                            new Promise((resolve, reject) => {
                            // Do custom type detection here and return with promise
                            console.log(type);
                            resolve(type);
                      })
                  }
              );



             FilePond.setOptions({
              server: {
              url: "",
              timeout: 60000,
              process: {
                    url: '../api/index.php?action=uploadimg',
                    method: 'POST',
                    withCredentials: false,
                    onload: (response) => {

                    let obj = JSON.parse(response); 

                    $("#submit-checkout").attr("data-url", obj.dir);
                    
                    },
                },
                
            },
            });

            document.addEventListener('FilePond:processfiles', (e) => {

            

            }); 
  
  // merun din changes dito sa submit-checkout yang mga naka if else

    $(document).on("click", "#submit-checkout", (e)=>{

      if (e.target.dataset.url == 0) {

          Swal.fire({
            icon: 'error',
            title: 'Missing Details',
            text: 'Please upload an image.',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ok'
          });

      }else{

        if (e.target.dataset.email == 0) {
          Swal.fire({
            icon: 'error',
            title: 'Incorrect email format',
            text: 'Please fix this issue.',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ok'
          });
        }else{

        if ($("#fullName").val() == "" || $("#contactNumber").val() == "" || $("#email").val() == "" || $("#City").val() == "" || $("#brgy").val() == "" || $("#streetNum").val() == "") {
          Swal.fire({
            icon: 'error',
            title: 'Missing Details',
            text: 'Please fill in all required fields.',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ok'
          });
   
        }else{

        $.ajax({
          url:"../api/index.php?action=checkoutorder",
          type: "POST",
          data: {
            custid:custid,
            image:$("#submit-checkout").data("url"),
            fullName: $("#fullName").val(),
            contactNumber: $("#contactNumber").val(),
            email: $("#email").val(),
            city: $("#City").val(),
            brgy: $("#brgy").val(),
            streetNum: $("#streetNum").val(),
            unit: $("#unit").val(),
            bldg: $("#bldg").val()
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
              title: 'Successfully Checked out! You will receive an email verification of your order.',
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Ok'
          }).then((result) => {
              if (result.isConfirmed) {
               window.location.href = "../landingpage/LandingPage.php";
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

        }

      }

    });


    //itong mga functions sa baba ay mga bago 

    const validateEmail = (email) => {
      return email.match(
        /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
      );
    };

    const validate = () => {
      const $result = $('#result');
      const email = $('#email').val();
      $result.text('');

      if(validateEmail(email)){
        
        $('#email').addClass('is-valid').removeClass('is-invalid');
        $("#submit-checkout").attr("data-email", 1);
      } else{
        $('#email').addClass('is-invalid').removeClass('is-valid');
        $("#submit-checkout").attr("data-email", 0);
      }
      return false;
    }

    $('#email').on('input', validate);

   </script>
  </body>
</html>