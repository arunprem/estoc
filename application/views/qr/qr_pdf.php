<?php
if(isset($q)){
tcpdf();
//$obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator('Kerala Police');
$pdf->SetAuthor('Kerala Police');
$pdf->SetTitle('Covid-19 Tracing');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 050', PDF_HEADER_STRING);

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
    require_once(dirname(__FILE__) . '/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// NOTE: 2D barcode algorithms must be implemented on 2dbarcode.php class file.



$y = 30;
$x = 1;

foreach ($q as $i) {
    if($x==1){
        $pdf->AddPage();
        $y=30;
    }
    if($x==2){ 
        $y=160;
        
    }

    // set font
    $pdf->SetFont('helvetica', '', 11);
    // set style for barcode
    $style = array(
        'border' => true,
        'vpadding' => 'auto',
        'hpadding' => 'auto',
        'fgcolor' => array(0, 0, 0),
        'bgcolor' => false, //array(255,255,255)
        'module_width' => 1, // width of a single module in points
        'module_height' => 1 // height of a single module in points
    );

    // add a page
    

    $pdf->SetFont('dejavusans', '', 40, '', true);
    // QRCODE,H : QR-CODE Best error correction
    $pdf->write2DBarcode($i->random_qr, 'QRCODE,H', 20, $y, 80, 80, $style, 'N');
    $pdf->Text(110, $y, $i->id);


    $pdf->SetFont('dejavusans', '', 12, '', true);
    $qstart_end= "Quarentine from $i->q_start to $i->q_end";
    $pdf->Text(110, $y+15, $qstart_end);

    

    // set color for background
    $pdf->SetFillColor(255, 255, 215);

    // set font
    $pdf->SetFont('helvetica', '', 11);
    $pdf->SetFillColor(255, 255, 255);

    // set cell padding
    //$pdf->setCellPaddings(2, 4, 6, 8);

    $txt = $i->address ;
    

    $pdf->MultiCell(80, 30, $txt, 0, 'L', 1, 2, 111, $y + 30, true);

    
    
    $pdf->Image('public/images/logo.jpg', 111, $y + 50, 25, 27, 'JPG', '', '', true, 150, '', false, false, 0, false, false, false);

    $pdf->SetFont('helvetica', '', 20);
    $txt = "KERALA POLICE";

    $pdf->MultiCell(80, 30, $txt, 0, 'L', 1, 2, 136, $y + 60, true);


    $pdf->SetFont('helvetica', '', 14);
    $txt = "Covid -19 Quarentine area";

    $pdf->MultiCell(80, 30, $txt, 0, 'L', 1, 2, 136, $y + 68, true);
    //Close and output PDF document
    if($x==2){ 
        $x=0;
    }
    $x++;


}
$pdf->Output('QR.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
}