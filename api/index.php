<?php
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use Dompdf\Dompdf;

DB::$user = 'root';
DB::$password = '';
DB::$dbName = 'streetkohi';


switch ($_GET['action']) {
  case "addtocart":
    addtocart();
    break;
  case "getcart":
    getcart();
    break;
  case "uploadimg":
    uploadimg();
    break;
  case "checkoutorder":
    checkoutorder();
    break; 
  case "removeitem":
    removeitem();
    break; 
  case "edititem":
    edititem();
    break; 
  case "generategraph":
    generategraph();
    break; 
  case "getnotif":
    getnotif();
    break; 
  case "notification":
    notification();
    break;
  case "updatestock": // Added case for updatestock action
      updateCoffeeStock();
      break;


  default:
    echo "sad";
}
function generateFinal()
{

  extract($_POST);

  $hot = DB::query("SELECT SUM(a.product_qty) as sum FROM order_trans as a
                    inner join coffee as b on(a.product_id = b.coffee_id) WHERE b.coffee_tag='hot'
                    AND a.date_inserted BETWEEN '$start' AND '$end'"); 
  $iced = DB::query("SELECT SUM(a.product_qty) as sum FROM order_trans as a
                    inner join coffee as b on(a.product_id = b.coffee_id) WHERE b.coffee_tag='iced'
                    AND a.date_inserted BETWEEN '$start' AND '$end'");
  $non_caffeine = DB::query("SELECT SUM(a.product_qty) as sum FROM order_trans as a
                    inner join coffee as b on(a.product_id = b.coffee_id) WHERE b.coffee_tag='non-caffeine'
                    AND a.date_inserted BETWEEN '$start' AND '$end'");
  $bread = DB::query("SELECT SUM(a.product_qty) as sum FROM order_trans as a
                    inner join coffee as b on(a.product_id = b.coffee_id) WHERE b.coffee_tag='bread-pastry'
                    AND a.date_inserted BETWEEN '$start' AND '$end'");

  $seller_hot = DB::query("SELECT SUM(a.product_qty) as sum, b.coffee_name FROM order_trans as a
                    inner join coffee as b on(a.product_id = b.coffee_id) WHERE b.coffee_tag='hot'
                    AND a.date_inserted BETWEEN '$start' AND '$end' group by a.product_id order by sum DESC"); 
                    
  $seller_iced = DB::query("SELECT SUM(a.product_qty) as sum, b.coffee_name FROM order_trans as a
                    inner join coffee as b on(a.product_id = b.coffee_id) WHERE b.coffee_tag='iced'
                    AND a.date_inserted BETWEEN '$start' AND '$end' group by a.product_id order by sum DESC"); 
  

  $seller_non_caffeine = DB::query("SELECT SUM(a.product_qty) as sum, b.coffee_name FROM order_trans as a
                    inner join coffee as b on(a.product_id = b.coffee_id) WHERE b.coffee_tag='non-caffeine'
                    AND a.date_inserted BETWEEN '$start' AND '$end' group by a.product_id order by sum DESC"); 


  $seller_bread = DB::query("SELECT SUM(a.product_qty) as sum, b.coffee_name FROM order_trans as a
                    inner join coffee as b on(a.product_id = b.coffee_id) WHERE b.coffee_tag='bread-pastry'
                    AND a.date_inserted BETWEEN '$start' AND '$end' group by a.product_id order by sum DESC"); 




  // echo json_encode([ 
  //                   "hot" => $hot[0]['sum'],
  //                   "seller_hot" => $seller_hot[0],
  //                   "iced" => $iced[0]['sum'],
  //                   "seller_iced" => $seller_iced[0],
  //                   "non_caffeine" => $non_caffeine[0]['sum'],
  //                   "seller_caffeine" => $seller_non_caffeine[0],
  //                   "bread" => $bread[0]['sum'],
  //                   "seller_bread" => $seller_bread[0]
  //                 ]);


}

function notification()
{


  $notif = DB::query("SELECT * FROM process_order ORDER BY checkout_id DESC LIMIT 20");

  DB::query("UPDATE process_order SET seen_status=%i WHERE seen_status=%i", 1, 0);

  echo json_encode(["notif"=>$notif]);

}

function getnotif()
{

  $notif = DB::query("SELECT count(*) as count FROM process_order WHERE seen_status='0'");

  echo json_encode(["notif"=>$notif[0]['count']]);

}

function generategraph()
{

	extract($_POST);

	$extractdates = DB::query("SELECT DISTINCT(date_inserted) FROM process_order 
					 WHERE date_inserted BETWEEN '$start' AND '$end'");

	$dates = [];
	$totals = [];

	foreach ($extractdates as $row) {
			
		$total_per_date = DB::query("SELECT sum(total) as total FROM process_order 
									 WHERE date_inserted='".$row['date_inserted']."'");


		array_push($dates, $row['date_inserted']);
		array_push($totals, $total_per_date[0]['total']);

	}


  $hot = DB::query("SELECT SUM(a.product_qty) as sum FROM order_trans as a
                    inner join coffee as b on(a.product_id = b.coffee_id) WHERE b.coffee_tag='hot'
                    AND a.date_inserted BETWEEN '$start' AND '$end'"); 
  $iced = DB::query("SELECT SUM(a.product_qty) as sum FROM order_trans as a
                    inner join coffee as b on(a.product_id = b.coffee_id) WHERE b.coffee_tag='iced'
                    AND a.date_inserted BETWEEN '$start' AND '$end'");
  $non_caffeine = DB::query("SELECT SUM(a.product_qty) as sum FROM order_trans as a
                    inner join coffee as b on(a.product_id = b.coffee_id) WHERE b.coffee_tag='non-caffeine'
                    AND a.date_inserted BETWEEN '$start' AND '$end'");
  $bread = DB::query("SELECT SUM(a.product_qty) as sum FROM order_trans as a
                    inner join coffee as b on(a.product_id = b.coffee_id) WHERE b.coffee_tag='bread-pastry'
                    AND a.date_inserted BETWEEN '$start' AND '$end'");

  $data_array = [];

  array_push($data_array, $iced[0]['sum']);
  array_push($data_array, $hot[0]['sum']);
  array_push($data_array, $bread[0]['sum']);
  array_push($data_array, $non_caffeine[0]['sum']);

	echo json_encode(["sales" => $data_array]);


}

function edititem()
{

	extract($_POST);

	DB::query("UPDATE cart_orders SET product_quantity=%i WHERE id=%i", $newqty, $id);

	echo json_encode(["response" => 1]);
}

function removeitem()
{

	extract($_POST);

	DB::query("DELETE FROM cart_orders WHERE id=%i", $id);

	echo json_encode(["response" => 1]);

}

// Function to handle the updatestock action
function updateCoffeeStock() {
  // Perform the SQL UPDATE query to update the coffee_stock in your database
  DB::query("UPDATE coffee AS c
            JOIN process_order AS po ON c.coffee_name = po.product_name
            SET c.coffee_stock = c.coffee_stock - po.product_quantity");

  // Optionally, you can send a response back to the client if needed
  // For example, you could echo a JSON response indicating success or failure
  echo json_encode(["success" => true]);
}







function checkoutorder()
{
	
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	extract($_POST);

	$cart = DB::query("SELECT * FROM cart_orders WHERE cust_id='$custid'");

	$orders = '';

	$total = 0;

	$text = '';

	foreach ($cart as $row) {

    DB::insert('order_trans', [
      'product_id' => $row['product_id'],
      'product_qty' => $row['product_quantity'],
      'product_total' => $row['product_price']*$row['product_quantity']
    ]);
		
		$orders .= ''.$row['product_name'].' (Qty: '.$row['product_quantity'].') (Total: '.$row['product_price'].'), ';

		$total += $row['product_price'];

		$text .= '
            <tr>
              <td style="color: #af0909;">'.$row['product_name'].' (Qty:'.$row['product_quantity'].') </td>
              <td style="color: #af0909;">₱'.number_format($row['product_price']*$row['product_quantity'], 2).'</td>
            </tr>

            ';

    $get_current_stock = DB::query("SELECT * FROM coffee WHERE coffee_id='".$row['product_id']."'");

    $new_stock = $get_current_stock[0]['coffee_stock']-$row['product_quantity'];

    DB::query("UPDATE coffee SET coffee_stock=%i WHERE coffee_id=%i", $new_stock, $row['product_id']);   


	}


	DB::insert('process_order', [
	  'customer_name' => $fullName,
	  'customer_cnum' => $contactNumber,
	  'customer_email' => $email,
	  'product_details' => $orders,
	  'total' => $total,
	  'delivery_street' => $streetNum,
	  'delivery_city' => $city,
	  'delivery_building' => $bldg,
	  'delivery_barangay' => $brgy,
	  'delivery_unit' => $unit,
	  'gcash_img' => $image
	]);


	DB::query("DELETE FROM cart_orders WHERE cust_id=%s", $custid);


	$mail = new PHPMailer(true);

    //Server settings
    // $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->SMTPOptions = array(
      'ssl' => array(
          'verify_peer' => false,
          'verify_peer_name' => false,
          'allow_self_signed' => true
      )
  );
    $mail->SMTPDebug = false; //Enable verbose debug output
    $mail->isSMTP(); //Send using SMTP
    $mail->Host = 'smtp.gmail.com'; //Set the SMTP server to send through
    $mail->SMTPAuth = true; //Enable SMTP authentication
    $mail->Username = 'plarnoza@gmail.com'; //SMTP username
    $mail->Password = 'vnvlilwpweckeyhl'; //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port = 587; //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above            
    //Recipients
    $mail->setFrom('plarnoza@gmail.com', 'Streetkohi Invoice');
    $mail->addAddress($email); //Add a recipient

    //Content
    $mail->isHTML(true); //Set email format to HTML
    $mail->Subject = 'Streetkohi Invoice';
    // $this->mail->Body = "<span style="width:50%; height:50%;"> <img src="img/logo2.png" style="width:50%; height:50%;"> </span> <h1>Click the link to verify account: <a href='http://localhost/batangas/api/user-verify/".$userid."' >Verify account</a></h1>";
    $mail->Body = '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
<!--[if gte mso 9]>
<xml>
  <o:OfficeDocumentSettings>
    <o:AllowPNG/>
    <o:PixelsPerInch>96</o:PixelsPerInch>
  </o:OfficeDocumentSettings>
</xml>
<![endif]-->
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="x-apple-disable-message-reformatting">
  <!--[if !mso]><!--><meta http-equiv="X-UA-Compatible" content="IE=edge"><!--<![endif]-->
  <title></title>
  
    <style type="text/css">
      @media only screen and (min-width: 520px) {
  .u-row {
    width: 500px !important;
  }
  .u-row .u-col {
    vertical-align: top;
  }

  .u-row .u-col-100 {
    width: 500px !important;
  }

}

@media (max-width: 520px) {
  .u-row-container {
    max-width: 100% !important;
    padding-left: 0px !important;
    padding-right: 0px !important;
  }
  .u-row .u-col {
    min-width: 320px !important;
    max-width: 100% !important;
    display: block !important;
  }
  .u-row {
    width: 100% !important;
  }
  .u-col {
    width: 100% !important;
  }
  .u-col > div {
    margin: 0 auto;
  }
}
body {
  margin: 0;
  padding: 0;
}

table,
tr,
td {
  vertical-align: top;
  border-collapse: collapse;
}

p {
  margin: 0;
}

.ie-container table,
.mso-container table {
  table-layout: fixed;
}

* {
  line-height: inherit;
}

a[x-apple-data-detectors="true"] {
  color: inherit !important;
  text-decoration: none !important;
}

table, td { color: #000000; } </style>
  
  

<!--[if !mso]><!--><link href="https://fonts.googleapis.com/css2?family=Caveat+Brush&display=swap" rel="stylesheet" type="text/css"><!--<![endif]-->

</head>

<body class="clean-body u_body" style="margin: 0;padding: 0;-webkit-text-size-adjust: 100%;background-color: #ffffff;color: #000000">
  <!--[if IE]><div class="ie-container"><![endif]-->
  <!--[if mso]><div class="mso-container"><![endif]-->
  <table style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;min-width: 320px;Margin: 0 auto;background-color: #ffffff;width:100%" cellpadding="0" cellspacing="0">
  <tbody>
  <tr style="vertical-align: top">
    <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top">
    <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" style="background-color: #ffffff;"><![endif]-->
    
  
  
<div class="u-row-container" style="padding: 0px;background-color: transparent">
  <div class="u-row" style="margin: 0 auto;min-width: 320px;max-width: 500px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;">
    <div style="border-collapse: collapse;display: table;width: 100%;height: 100%;background-color: transparent;">
      <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:500px;"><tr style="background-color: transparent;"><![endif]-->
      
<!--[if (mso)|(IE)]><td align="center" width="500" style="width: 500px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px;" valign="top"><![endif]-->
<div class="u-col u-col-100" style="max-width: 320px;min-width: 500px;display: table-cell;vertical-align: top;">
  <div style="height: 100%;width: 100% !important;border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px;">
  <!--[if (!mso)&(!IE)]><!--><div style="box-sizing: border-box; height: 100%; padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px;"><!--<![endif]-->
  
<table style="font-family:arial,helvetica,sans-serif;" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
  <tbody>
    <tr>
      <td style="overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;" align="left">
        
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td style="padding-right: 0px;padding-left: 0px;" align="center">
      
      <img align="center" border="0" src="https://scontent.fdgt1-1.fna.fbcdn.net/v/t39.30808-6/430892789_714916420768834_5212542867406494370_n.jpg?_nc_cat=100&ccb=1-7&_nc_sid=5f2048&_nc_eui2=AeGCX03lRwfY_ZA-b0kJN1KRuECfdDj7m2q4QJ90OPubaj1-iXxhKmtqLNI_GJjz56tWM6kcpbiy3HfzBusIHMyz&_nc_ohc=1cMKWc0Q2VAAX-vAme_&_nc_ht=scontent.fdgt1-1.fna&oh=00_AfAzr9NGNYJDw2hufLREvDmI_ceAS0-T2BUcYqEz82n_QA&oe=660E8517" alt="" title="" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: inline-block !important;border: none;height: auto;float: none;width: 41%;max-width: 196.8px;" width="196.8"/>
      
    </td>
  </tr>
</table>

      </td>
    </tr>
  </tbody>
</table>

<table style="font-family:arial,helvetica,sans-serif;" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
  <tbody>
    <tr>
      <td style="overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;" align="left">
        
  <!--[if mso]><table width="100%"><tr><td><![endif]-->
    <h1 style="margin: 0px; line-height: 140%; text-align: left; word-wrap: break-word; font-family: Caveat Brush; font-size: 36px; font-weight: 400;"><span><span><span>Hi! '.$fullName.'</span></span></span></h1>
  <!--[if mso]></td></tr></table><![endif]-->

      </td>
    </tr>
  </tbody>
</table>

<table style="font-family:arial,helvetica,sans-serif;" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
  <tbody>
    <tr>
      <td style="overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;" align="left">
        
  <div style="font-family: Caveat Brush; font-size: 18px; color: #3bc641; line-height: 140%; text-align: left; word-wrap: break-word;">
    <p style="line-height: 140%;">Your order has been successfully completed.</p>
  </div>

      </td>
    </tr>
  </tbody>
</table>

<table style="font-family:arial,helvetica,sans-serif;" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
  <tbody>
    <tr>
      <td style="overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;" align="left">
        
  <table height="0px" align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;border-top: 1px solid #BBBBBB;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
    <tbody>
      <tr style="vertical-align: top">
        <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;font-size: 0px;line-height: 0px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
          <span>&#160;</span>
        </td>
      </tr>
    </tbody>
  </table>

      </td>
    </tr>
  </tbody>
</table>

<table style="font-family:arial,helvetica,sans-serif;" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
  <tbody>
    <tr>
      <td style="overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;" align="left">
        
  <!--[if mso]><table width="100%"><tr><td><![endif]-->
    <h3 style="margin: 0px; color: #b30f0f; line-height: 140%; text-align: center; word-wrap: break-word; font-family: arial black,AvenirNext-Heavy,avant garde,arial; font-size: 18px; font-weight: 400;"><span><span><span>List of Orders</span></span></span></h3>
  <!--[if mso]></td></tr></table><![endif]-->

      </td>
    </tr>
  </tbody>
</table>

<table style="font-family:arial,helvetica,sans-serif;" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
  <tbody>

  '.$text.'

  </tbody>
</table>

<table style="font-family:arial,helvetica,sans-serif;" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
  <tbody>
    <tr>
      <td style="overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;" align="left">
        
  <table height="0px" align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;border-top: 1px solid #BBBBBB;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
    <tbody>
      <tr style="vertical-align: top">
        <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;font-size: 0px;line-height: 0px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
          <span>&#160;</span>
        </td>
      </tr>
    </tbody>
  </table>

      </td>
    </tr>
  </tbody>
</table>

<table style="font-family:arial,helvetica,sans-serif;" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
  <tbody>
    <tr>
      <td style="overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;" align="left">
        
  <!--[if mso]><table width="100%"><tr><td><![endif]-->
    <h1 style="margin: 0px; line-height: 140%; text-align: center; word-wrap: break-word; font-family: Caveat Brush; font-size: 32px; font-weight: 400;"><span><span><span>Thank you for your order.</span></span></span></h1>
  <!--[if mso]></td></tr></table><![endif]-->

      </td>
    </tr>
  </tbody>
</table>

<table style="font-family:arial,helvetica,sans-serif;" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
  <tbody>
    <tr>
      <td style="overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;" align="left">
        
  <div style="font-family: Caveat Brush; font-size: 18px; line-height: 140%; text-align: center; word-wrap: break-word;">
    <p style="line-height: 140%;">Your support to our small business is very appreciated.</p>
  </div>

      </td>
    </tr>
  </tbody>
</table>

  <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
  </div>
</div>
<!--[if (mso)|(IE)]></td><![endif]-->
      <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
    </div>
  </div>
  </div>
  


    <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
    </td>
  </tr>
  </tbody>
  </table>
  <!--[if mso]></div><![endif]-->
  <!--[if IE]></div><![endif]-->
</body>

</html>

    ';

    $mail->send();
    // echo 'Message has been sent';
 


	echo json_encode(["response" => 1]);

}

function uploadimg()
{

	$files = $_FILES['image233'];
	$file_path = $files['tmp_name'];
	$file_name = $files['name'];
	$file_size = $files['size'];
	$file_type = $files['type'];
	$directory = "../gcash-img";
	$path = $directory."/".$file_name;

	$newdir = "../gcash-img/".$file_name;

	if (!is_dir($directory)) {
	//Create our fam_monitor_directory(fam, dirname).
	mkdir($directory, 755, true);
	move_uploaded_file($file_path, $path);

	} else {

	move_uploaded_file($file_path, $path);

	}

	echo json_encode(["dir" => $newdir]);

}

function getcart()
{

	extract($_POST);

	$carts = DB::query("SELECT * FROM cart_orders WHERE cust_id='$custid' ");


	echo json_encode(["cart" => $carts]);

}

function addtocart()
{

	extract($_POST);

  $checking = DB::query("SELECT * FROM coffee WHERE coffee_id='$product_id'");

  if ($product_quantity <= $checking[0]['coffee_stock']) {

    DB::insert('cart_orders', [
      'cust_id' => $custid,
      'product_quantity' => $product_quantity,
      'product_name' => $product_name,
      'product_price' => $product_price,
      'product_id' => $product_id,
      // 'type' => $type,
      'product_img' => $image
    ]);

    echo json_encode(["response" => 1]);

  }else{

    echo json_encode(["response" => 0, "stock" =>$checking[0]['coffee_stock'] ]);
  }


	
}


?>