<?php 
    require('./fpdf/fpdf.php');

    $pdf = new FPDF('P', 'mm', 'A4');

    $pdf->AddPage();

    //SetFont(Fontstyle, Font format, Font size) Format
    $pdf->SetFont('Arial', 'B', 20);

    $pdf->Cell(71, 10,'',0,0);
    $pdf->Cell(59 ,5, 'Invoice',0,0);
    $pdf->Cell(59 ,10,'', 0,1);

    $pdf->SetFont('Arial', 'B', 15);
    $pdf->Cell(71, 5,'STREET KOHI',0,0);
    $pdf->Cell(59, 5,'',0,0);
    $pdf->Cell(59, 5,'Details',0,1);

    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(130, 5,'testing',0,0);
    $pdf->Cell(25, 5,'Customer ID:',0,0);
    $pdf->Cell(34, 5,'0012',0,1);

    $pdf->Cell(130, 5,'test, 751001',0,0);
    $pdf->Cell(25, 5,'Invoice Date:',0,0);
    $pdf->Cell(34, 5,'24th Feb 2024',0,1);

    $pdf->Cell(130, 5,'',0,0);
    $pdf->Cell(25, 5,'Invoice No:',0,0);
    $pdf->Cell(34, 5,'ORD001',0,1);

    $pdf->SetFont('Arial', 'B', 15);
    $pdf->Cell(130, 5,'Bill To',0,0);
    $pdf->Cell(59, 5,'',0,0);
    $pdf->Cell(189 ,10,'',0,1);

    $pdf->Cell(50 ,10,'',0,1);

    $pdf->SetFont('Arial', 'B' ,10);
    //Heading of the table
    $pdf->Cell(10, 6,'Sl',1,0,'C');
    $pdf->Cell(80, 6,'Description',1,0,'C');
    $pdf->Cell(23, 6,'Qty',1,0,'C');
    $pdf->Cell(30, 6,'Unit Price',1,0,'C');
    $pdf->Cell(20, 6,'Sales Tax',1,0,'C');
    $pdf->Cell(25, 6,'Total',1,1,'C');
    //Heading of the table end

    $pdf->SetFont('Arial', '' ,10);
    for ($i =0; $i <= 10; $i++) {
        $pdf->Cell(10, 6,$i,1,0);
        $pdf->Cell(80, 6,'Matcha Latte',1,0);
        $pdf->Cell(23, 6,'1',1,0,'R');
        $pdf->Cell(30, 6,'160.00',1,0,'R');
        $pdf->Cell(20, 6,'100.00',1,0,'R');
        $pdf->Cell(25, 6,'170.00',1,1,'R');
    }

    $pdf->Output();
?>