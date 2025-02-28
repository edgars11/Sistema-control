<?php
// TODO: Llamando clases
require_once("../config/conexion.php");
require_once("../models/generatePDF.php");
// TODO: Inicializando clases
$pdfPrint = new GeneratePDF();

switch($_GET["op"]){
    case "generatePdf" :
        $pdfPrint->generate_pdf_salida($_GET["salida_id"],$_GET["emp_id"],$_GET["com_id"],$_GET['download']);
        break;

}