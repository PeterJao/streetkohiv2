<?php
// Load Composer's autoloader
require '../../autoload.php';

// Import Dompdf
use Dompdf\Dompdf;

$html = '';

if (isset($_POST['html'])) {
    $html = $_POST['html'];
}

// Initialize Dompdf
$dompdf = new Dompdf();

// Load HTML content
$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF (1 = download and 0 = preview)
$dompdf->stream("report.pdf", array("Attachment" => 1));
