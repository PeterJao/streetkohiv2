<?php
define("DOMPDF_ENABLE_REMOTE", false);

require 'vendor/autoload.php';


use Dompdf\Dompdf;


DB::$user = 'root';
DB::$password = '';
DB::$dbName = 'streetkohi';

// instantiate and use the dompdf class
$dompdf = new Dompdf(array('enable_remote' => true));

extract($_GET);

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




$dompdf->loadHtml('
	<div style="margin: auto; width: 50%; border: 3px dashed brown; padding: 20px;">
	<br>
	<br>
	<center>
		<img src="http://localhost/StreetKohi/assets/images/SK-Logo1.png" style="width: 200px; height: 100px;">
	</center>
	<h2> Sales per category as of '.$start.' to '.$end.' </h2>

	<table style="width: 100%; border-collapse: collapse; border: 1px solid;"> 
		<thead > 
			<tr>
				<th style="border: 1px solid;">Category</th>
				<th style="border: 1px solid;">Sales</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td style="border: 1px solid; text-align: center;">Hot</td>
				<td style="border: 1px solid; text-align: center;">'.$hot[0]['sum'].'</td>
			</tr>
			<tr>
				<td style="border: 1px solid; text-align: center;">Iced</td>
				<td style="border: 1px solid; text-align: center;">'.$iced[0]['sum'].'</td>
			</tr>
			<tr>
				<td style="border: 1px solid; text-align: center;">Non-caffeine</td>
				<td style="border: 1px solid; text-align: center;">'.$non_caffeine[0]['sum'].'</td>
			</tr>
			<tr>
				<td style="border: 1px solid; text-align: center;">Bread-pastry</td>
				<td style="border: 1px solid; text-align: center;">'.$bread[0]['sum'].'</td>
			</tr>
		</tbody>	
	</table>
	<br>
	<br>
	<h2> Best sellers per category </h2>
	<table style="width: 100%; border-collapse: collapse; border: 1px solid;"> 
		<thead > 
			<tr>
				<th style="border: 1px solid;">Category</th>
				<th style="border: 1px solid;">Product</th>
				<th style="border: 1px solid;">Sales</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td style="border: 1px solid; text-align: center;">Hot</td>
				<td style="border: 1px solid; text-align: center;">'.$seller_hot[0]['coffee_name'].'</td>
				<td style="border: 1px solid; text-align: center;">'.$seller_hot[0]['sum'].'</td>
			</tr>
			<tr>
				<td style="border: 1px solid; text-align: center;">Iced</td>
				<td style="border: 1px solid; text-align: center;">'.$seller_iced[0]['coffee_name'].'</td>
				<td style="border: 1px solid; text-align: center;">'.$seller_iced[0]['sum'].'</td>
			</tr>
			<tr>
				<td style="border: 1px solid; text-align: center;">Non-caffeine</td>
				<td style="border: 1px solid; text-align: center;">'.$seller_non_caffeine[0]['coffee_name'].'</td>
				<td style="border: 1px solid; text-align: center;">'.$seller_non_caffeine[0]['sum'].'</td>
			</tr>
			<tr>
				<td style="border: 1px solid; text-align: center;">Bread-pastry</td>
				<td style="border: 1px solid; text-align: center;">'.$seller_bread[0]['coffee_name'].'</td>
				<td style="border: 1px solid; text-align: center;">'.$seller_bread[0]['sum'].'</td>
			</tr>
		</tbody>	
	</table>
	</div>
	');

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream();

?>