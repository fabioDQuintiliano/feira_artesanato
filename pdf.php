<?php
include 'front_includes.php';

$html = preg_replace("|<!\-\-\[PDF\-OFF\-\->(.*?)<!\-\-PDF\-OFF\]\-\->|s",'',$_SESSION['save_pdf']);

$html;


include("mpdf/mpdf.php");
$mpdf=new mPDF(); 

$stylesheet = file_get_contents('css-pdf.css');
$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($html);
$mpdf->Output($_GET['nome'],'D');
exit;


?>