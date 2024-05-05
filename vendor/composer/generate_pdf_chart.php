<?php
require '../autoload.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();

// Receive the image data from POST
$imageData = $_POST['imageData'];

// Generate HTML content for PDF
$html = '<html><body>';
$html .= '<img src="' . $imageData . '">';
$html .= '</body></html>';


$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

// Output the generated PDF
$dompdf->stream("sales_report.pdf", array("Attachment" => true));
?>
