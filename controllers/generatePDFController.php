<?php
// TODO: Llamando clases
require_once("../config/conexion.php");
require_once("../models/generatePDF.php");
// TODO: Inicializando clases
$pdfPrint = new GeneratePDF();

switch ($_GET["op"]) {
    case "generatePdf":
        $pdfPrint->generate_pdf_salida($_GET["salida_id"], $_GET["emp_id"], $_GET["com_id"], $_GET['download']);
        break;
    case "generateListadoSalidaPdf":
        $lote_id = $_GET['lote_id'] == '' ? null : $_GET['lote_id'];
        $cli_id = $_GET['cli_id'] == '' ? null : $_GET['cli_id'];
        $salida_tipo = $_GET['salida_tipo'] == '' ? null : $_GET['salida_tipo'];
        $pdfPrint->generate_pdf_listado_salida($lote_id, $cli_id, $salida_tipo, $_GET['fecha_desde'], $_GET['fecha_hasta'], $_GET['suc_id'], $_GET['emp_id'], $_GET['com_id'], $_GET['download']);
        break;
}
