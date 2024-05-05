<?php
session_start();

//paki save

if(!isset($_SESSION['admin_user'])) {
    header("Location: ../../login/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="shortcut icon" href="../assets/images/SK-CoffeeIcon1.png" />
    <!-- Include Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    <link rel="stylesheet" href="../../css/dashboard.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Admin Dashboard</title>
    <link rel="icon" type="image/x-icon" href="../../assets/images/SK-Icon.png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.49.0/apexcharts.min.css" integrity="sha512-qc0GepkUB5ugt8LevOF/K2h2lLGIloDBcWX8yawu/5V8FXSxZLn3NVMZskeEyOhlc6RxKiEj6QpSrlAoL1D3TA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  </head>
  <body>
    <?php include "dashboard-header.php" ?>
    <div class="main-content-container">
    <center>
      <h1 class="fw-bolder pt-3">Admin Dashboard</h1>
      <a class="btn btn-primary" id="reportpdf" href="http://localhost/StreetKohi/generate.php">Download PDF Report</a>
      <button class="btn btn-primary" onclick="generateChartPdf()">Download Chart as PDF</button>


    </center>

    <div class="container">
      <div class="row" style="width: 100%;">
        <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col-md-4">
            <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                <i class="fa fa-calendar"></i>&nbsp;
                <span></span> <i class="fa fa-caret-down"></i>
            </div>                
              </div>
            </div>
          </div>
          <div class="card-body">
<!-- 
            <canvas id="chartroom" ></canvas> -->
          <div id="chart">
          </div>
          </div>
        </div>
        </div>
      </div>
    </div>
    </div>

<!-- Modal -->
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

    <!-- Add Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js" integrity="sha512-t2JWqzirxOmR9MZKu+BMz0TNHe55G5BZ/tfTmXMlxpUY8tsTo3QMD27QGoYKZKFAraIPDhFv56HLdN11ctmiTQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js" integrity="sha384-NaWTHo/8YCBYJ59830LTz/P4aQZK1sS0SneOgAvhsIl3zBu8r9RevNg5lHCHAuQ/" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.49.0/apexcharts.min.js" integrity="sha512-NpRqjS1hba1uc6270PmwsKwQti3CSCDkZD9/dlen3+ytOUb/azIyuaGtyewUkjazLMSdl7Zy2CVWMvGxR6vFWg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>

        $(document).on("click", "#togglenotif", (e)=>{
            console.log("asdsa");
              $.ajax({
                url:"../../api/index.php?action=notification",
                type: "POST",
                data: {
                  id:1
                },
                dataType: "json",
                success: (data) => { 

                 $(".dropdown-list").empty();

                 $.each(data.notif, (i, e)=>{

                  $(".dropdown-list").append(`
                  <a class="dropdown-item" href="#">
                  <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                  New Order from ${e.customer_name} <br>
                  <p style="font-size: 14px;"><i>${e.date_inserted}</i></p>
                  </a>
                  `);

                 });   

                 $(".notif").text(0);


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
        });


        setInterval(function interval(){

              $.ajax({
                url:"../../api/index.php?action=getnotif",
                type: "POST",
                data: {
                  id:1
                },
                dataType: "json",
                success: (data) => { 

                    $(".notif").text(data.notif);

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

        }, 2000);

       var chart; 
       var start;
       var end;

      $(function() {

          start = moment().subtract(29, 'days');
          end = moment();

          function cb(start, end) {
              $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

              $("#reportpdf").attr("href", "http://localhost/StreetKohi/generate.php?start="+start.format('YYYY-MM-DD')+"&end="+end.format('YYYY-MM-DD'));

              $.ajax({
                url:"../../api/index.php?action=generategraph",
                type: "POST",
                data: {
                  start: start.format('YYYY-MM-DD'),
                  end: end.format('YYYY-MM-DD')
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

                  // const plugin = {
                  //   id: 'customCanvasBackgroundColor',
                  //   beforeDraw: (chart, args, options) => {
                  //     const {ctx} = chart;
                  //     ctx.save();
                  //     ctx.globalCompositeOperation = 'destination-over';
                  //     ctx.fillStyle = options.color || '#99ffff';
                  //     ctx.fillRect(0, 0, chart.width, chart.height);
                  //     ctx.restore();
                  //   }
                  // };

                  // var myChart = new Chart(document.getElementById("chartroom").getContext('2d'), {
                  //     type: 'bar',
                  //     data: {
                  //         labels: ["Iced", "Hot", "Bread-pastry", "Non-caffiene"],
                  //         datasets: [{
                  //             label: 'Order Sales', // Name the series
                  //             data: [20, 34, 12, 67], // Specify the data values array
                  //             fill: true,
                  //             borderColor: [
                  //               'rgba(75, 192, 192, 0.2)',
                  //               'rgba(54, 162, 235, 0.2)',
                  //               'rgba(153, 102, 255, 0.2)',
                  //               'rgba(201, 203, 207, 0.2)'
                  //             ], // Add custom color border (Line)
                  //             backgroundColor: [
                  //               'rgb(75, 192, 192)',
                  //               'rgb(54, 162, 235)',
                  //               'rgb(153, 102, 255)',
                  //               'rgb(201, 203, 207)'
                  //             ], // Add custom color background (Points and Fill)
                  //             borderWidth: 1, // Specify bar border width
                  //         }]},
                  //     options: {

                  //     },
                  // });


      
                  var options = {
                    series: [{
                    name: 'Sales Per Categories',
                    data: data.sales
                  }],
                    chart: {
                    height: 350,
                    type: 'bar',
                  },
                  plotOptions: {
                    bar: {
                      borderRadius: 10,
                      dataLabels: {
                        position: 'top', // top, center, bottom
                      },
                    }
                  },
                  dataLabels: {
                    enabled: true,
                    offsetY: -20,
                    style: {
                      fontSize: '12px',
                      colors: ["#304758"]
                    }
                  },
                  
                  xaxis: {
                    categories: ["Iced", "Hot", "Bread-pastry", "Non-caffiene"],
                    position: 'top',
                    axisBorder: {
                      show: false
                    },
                    axisTicks: {
                      show: false
                    },
                    crosshairs: {
                      fill: {
                        type: 'gradient',
                        gradient: {
                          colorFrom: '#D8E3F0',
                          colorTo: '#BED1E6',
                          stops: [0, 100],
                          opacityFrom: 0.4,
                          opacityTo: 0.5,
                        }
                      }
                    },
                    tooltip: {
                      enabled: true,
                    }
                  },
                  yaxis: {
                    axisBorder: {
                      show: false
                    },
                    axisTicks: {
                      show: false,
                    },
                    labels: {
                      show: false,

                    }
                  
                  },
                  title: {
                    text: 'Sales per categories',
                    floating: true,
                    offsetY: 330,
                    align: 'center',
                    style: {
                      color: '#444'
                    }
                  }
                  };

                  $("#chart").empty();

                  chart = new ApexCharts(document.querySelector("#chart"), options);
                  chart.render();


                  $("#total").text("₱"+data.total);
                  $("#orders").text(data.orders);

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

          $('#reportrange').daterangepicker({
              startDate: start,
              endDate: end,
              ranges: {
                 'Today': [moment(), moment()],
                 'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                 'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                 'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                 'This Month': [moment().startOf('month'), moment().endOf('month')],
                 'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
              }
          }, cb);

          cb(start, end);

      });
  

      function generateChartPdf()
      {

        chart.dataURI().then(({ imgURI, blob }) => { //Here shows error
        var pdf = new jsPDF('landscape');
        pdf.addImage(imgURI, 'PNG', 15, 15, 260, 150);
        pdf.text(15, 15, $('#reportrange span').text());
        pdf.save("report.pdf");
        })

      }



      function generatePDFFinal()
      {

        $.ajax({
          url:"../../api/index.php?action=generateFinal",
          type: "POST",
          data: {
              start: start.format('YYYY-MM-DD'),
              end: end.format('YYYY-MM-DD')
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

            $("#exampleModalLong").modal("show");


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

    function generatePDF() {
  // Fetch the HTML you want to turn into a PDF, e.g., the contents of a specific div
  const reportHTML = document.querySelector('.main-content-container').innerHTML; // Adjust this if you want a different part of the page

  // Send this data to a server-side script (e.g., 'generate_pdf.php') using AJAX
  $.ajax({
    type: 'POST',
    url: '../../vendor/composer/generate_pdf.php', // Adjust the path if necessary
    data: { html: reportHTML },
    xhrFields: {
        responseType: 'blob'  // to handle binary data
    },
    success: function(response) {
      // Create a Blob from the PDF Stream
      var blob = new Blob([response], { type: 'application/pdf' });
      // Build a URL from the file
      var url = window.URL.createObjectURL(blob);
      // Open the URL on new Window
      window.open(url);
    },
    error: function(xhr, status, error) {
      console.error("There was an issue generating the PDF: ", error);
    }
  });
}

function downloadChartAsImage() {
    const chartElement = document.getElementById('chartroom'); // Adjust this if using a different canvas
    const imageUrl = chartElement.toDataURL('image/png'); // Converts the canvas to a data URL

    // Create a form and submit it to send the image data to the server
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '../../vendor/composer/generate_pdf_chart.php'; // Modify this if your path differs
    form.target = '_blank'; // To open PDF in a new window

    const hiddenField = document.createElement('input');
    hiddenField.type = 'hidden';
    hiddenField.name = 'imageData';
    hiddenField.value = imageUrl;
    form.appendChild(hiddenField);

    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}


    </script>

  </body>
</html>
