<?php
require_once '../include/vendor/autoload.php';
require_once '../config/conexion.php';
require_once '../models/SalidaLote.php';
require_once '../models/Empresa.php';
require_once '../models/Cliente.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class GeneratePDF extends Conectar
{
    public function generate_pdf_salida($salida_id, $emp_id, $com_id, $download)
    {
        //DatosCliente;
        $salidalote = new SalidaLote();
        $empresa = new Empresa();
        $rutaImg = $_SERVER['HTTP_HOST'];

        $descarga = $download === "1" ? true : false;

        $detalleEmpresa = $empresa->getEmpresaPorId("R", $emp_id, $com_id);
        $subtotal = 0;
        $valorIva = 15;
        $iva = 0;

        foreach ($detalleEmpresa as $row) {
            $datosEmpresa["emp_id"] = $row["emp_id"];
            $datosEmpresa["emp_nombre"] = $row["emp_nombre"];
            $datosEmpresa["emp_ruc"] = $row["emp_ruc"];
            $datosEmpresa["emp_telefono"] = $row["emp_telefono"];
            $datosEmpresa["emp_estado"] = $row["emp_estado"];
            $datosEmpresa["emp_direccion"] = $row["emp_direccion"];
            $datosEmpresa["emp_correo"] = $row["emp_correo"];
        }

        $content_css = file_get_contents('../assets/css/stylePDF.css');
        $detalle = $salidalote->getSalidaById($salida_id);
        $tbody = "";
        foreach ($detalle as $row) {
            $datosCliente["cli_nombre"] = $row["cli_nombre"];
            $datosCliente["cli_ruc"] = $row["cli_ruc"];
            $datosCliente["cli_telefono"] = $row["cli_telefono"];
            $datosCliente["cli_direccion"] = $row["cli_direccion"];
            $datosCliente["cli_correo"] = $row["cli_correo"];
            $subtotal = $subtotal + $row["salida_total"];
            $tipoProd = $row["salida_tipo"] == 'PV' ? 'POLLO VIVO' : ' POLLO FAENADO';
            $tbody .= '
                    <tr>
                        <td class="service text-fw-600">' . $tipoProd . '</td>
                        <td class="desc">' . $row["salida_fecha"] . '</td>
                        <td class="unit"># ' . $row["salida_cantidad"] . '</td>
                        <td class="qty">' . $row["salida_peso_neto"] . ' Lbs</td>
                        <td class="subtotal">$ ' . $row["salida_precio"] . '</td>
                        <td class="subtotal">$ ' . $row["salida_total"] . '</td>
                    </tr>
            ';
        }

        $html = '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="utf-8">
                <title>Registro Salida #' . $salida_id . '</title>
                <style>' . $content_css . '</style>
            </head>
            <body>
                <header class="clearfix">
                <div id="logo">
                    <img src="http://' . $rutaImg . '/Sistema-Control/assets/images/logo-lite.jpg" alt="Logo empresa" style="width: 100px"><br>
                </div>
                <h1>REGISTRO SALIDA <span class="tc-yellow"># ' . $salida_id . '</span></h1>
                <div id="company" class="clearfix">
                    <div class="margin-bottom-25"><span class="text-fw-600 margin-bottom-25 ts-15">DATOS EMPRESA</span></div>
                    <div><span class="ts-13">' . $datosEmpresa["emp_nombre"] . '</span></div>
                    <div><span class="ts-13">' . $datosEmpresa["emp_direccion"] . '</span></div>
                    <div><span class="ts-13"><a href="mailto:' . $datosEmpresa["emp_correo"] . '">' . $datosEmpresa["emp_correo"] . '</a></span></div>
                    <div><span class="ts-13">' . $datosEmpresa["emp_ruc"] . '</span></div>
                    <div><span class="ts-13">' . $datosEmpresa["emp_telefono"] . '</span></div>
                </div>
                <div id="project">
                    <div class="margin-bottom-25"><span class="text-fw-600  ts-15">COMPROBANTE PARA:</span></div>
                    <div><span class="text-fw-600">CLIENTE:</span> <span class="ts-13">' . $datosCliente["cli_nombre"] . '</span> </div>
                    <div><span class="text-fw-600">DIRECCIÓN:</span>  <span class="ts-13">' . $datosCliente["cli_direccion"] . ' </span></div>
                    <div><span class="text-fw-600">CORREO:</span> <span class="ts-13"> <a href="' . $datosCliente["cli_correo"] . '">' . $datosCliente["cli_correo"] . '</a> </span></div>
                    <div><span class="text-fw-600">RUC/CI:</span>  <span class="ts-13">' . $datosCliente["cli_ruc"] . ' </span></div>
                    <div><span class="text-fw-600">CONTACTO:</span> <span class="ts-13"> ' . $datosCliente["cli_telefono"] . ' </span></div>
                </div>
                </header>
                <main>
                <table>
                    <thead>
                    <tr>
                        <th class="service">PRODUCTO</th>
                        <th class="desc">FECHA</th>
                        <th>CANTIDAD</th>
                        <th>P.NETO</th>
                        <th>PRECIO</th>
                        <th>TOTAL</th>
                    </tr>
                    </thead>
                    <tbody>
                        ' . $tbody . '
                        <tr>
                            <td colspan="5" class="totales">SUBTOTAL</td>
                            <td class="subtotal totales">$ ' . $subtotal . '</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="totales">IVA ' . $valorIva . '%</td>
                            <td class="subtotal totales">$ 0.00</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="grand total totales">VALOR TOTAL</td>
                            <td class="grand total totales tc-green">$ ' . $subtotal . '</td>
                        </tr>
                    </tbody>
                </table>
                <div id="notices">
                    <div>OBSERVACIONES:</div>
                    <div class="notice"></div>
                </div>
                </main>
                <footer>
                    <span class="text-bold">Granja LITE</span> le agradece por su compra.
                </footer>
            </body>
            </html>
        ';
        $nombreReporte = 'Reg_' . $datosCliente["cli_nombre"] . '_#' . $salida_id . '.pdf';


        generarPDF($html, $nombreReporte, $descarga);
    }

    public function generate_pdf_listado_salida($lote_id, $cli_id, $salida_tipo, $fecha_desde, $fecha_hasta, $suc_id, $emp_id, $com_id, $download)
    {
        //DatosCliente;
        $salidalote = new SalidaLote();

        $rutaImg = $_SERVER['HTTP_HOST'];

        $descarga = $download === "1" ? true : false;

        $subtotal = 0;
        $valorIva = 15;
        $iva = 0;
        $tbody = "";

        $textoDescripcion = "El reporte generado muestra el listado de registro desde la fecha: " . $fecha_desde . " hasta la fecha: " .  $fecha_hasta;

        $content_css = file_get_contents('../assets/css/stylePDF.css');
        $lote_id = $lote_id === 'null' ? null : $lote_id;
        $cli_id = $cli_id === 'null' ? null : $cli_id;
        $salida_tipo = $salida_tipo === 'null' ? null : $salida_tipo;
        $detalle = $salidalote->getlistadoSalida('L', $lote_id, $cli_id, $salida_tipo, $fecha_desde, $fecha_hasta, $suc_id);
        foreach ($detalle as $row) {

            $subtotal = $subtotal + $row["salida_total"];
            $tipoProd = $row["salida_tipo"] == 'PV' ? 'POLLO VIVO' : ' POLLO FAENADO';
            $tbody .= '
                    <tr>
                        <td class="service text-fw-600">' . $tipoProd . '</td>
                        <td class="desc">' . $row["salida_fecha"] . '</td>
                        <td class="unit"># ' . $row["salida_cantidad"] . '</td>
                        <td class="qty">' . $row["salida_peso_neto"] . ' Lbs</td>
                        <td class="subtotal">$ ' . $row["salida_precio"] . '</td>
                        <td class="subtotal">$ ' . $row["salida_total"] . '</td>
                    </tr>
            ';
        }

        $datosEmpresa = datosEmpresa($emp_id, $com_id);

        if (!empty($cli_id)) {
            $datosCliente = buscarCliente($cli_id);
        }

        $html = '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="utf-8">
                <title>Registro Listado de Salida </title>
                <style>' . $content_css . '</style>
            </head>
            <body>
                <header class="clearfix">
                <div id="logo">
                    <img src="http://' . $rutaImg . '/Sistema-Control/assets/images/logo-lite.jpg" alt="Logo empresa" style="width: 100px"><br>
                </div>
                <h1>REGISTRO LISTADO SALIDA </h1>
                <div id="company" class="clearfix">
                    <div class="margin-bottom-25"><span class="text-fw-600 margin-bottom-25 ts-15">DATOS EMPRESA</span></div>
                    <div><span class="ts-13">' . $datosEmpresa["emp_nombre"] . '</span></div>
                    <div><span class="ts-13">' . $datosEmpresa["emp_direccion"] . '</span></div>
                    <div><span class="ts-13"><a href="mailto:' . $datosEmpresa["emp_correo"] . '">' . $datosEmpresa["emp_correo"] . '</a></span></div>
                    <div><span class="ts-13">' . $datosEmpresa["emp_ruc"] . '</span></div>
                    <div><span class="ts-13">' . $datosEmpresa["emp_telefono"] . '</span></div>
                </div>
                <div id="project">
                    <div class="margin-bottom-25"><span class="text-fw-600  ts-15">COMPROBANTE PARA:</span></div>
                    <div><span class="text-fw-600">CLIENTE:</span> <span class="ts-13">' . $datosCliente["cli_nombre"] . '</span> </div>
                    <div><span class="text-fw-600">DIRECCIÓN:</span>  <span class="ts-13">' . $datosCliente["cli_direccion"] . ' </span></div>
                    <div><span class="text-fw-600">CORREO:</span> <span class="ts-13"> <a href="' . $datosCliente["cli_correo"] . '">' . $datosCliente["cli_correo"] . '</a> </span></div>
                    <div><span class="text-fw-600">RUC/CI:</span>  <span class="ts-13">' . $datosCliente["cli_ruc"] . ' </span></div>
                    <div><span class="text-fw-600">CONTACTO:</span> <span class="ts-13"> ' . $datosCliente["cli_telefono"] . ' </span></div>
                </div>
                </header>
                <main>
                <table>
                    <thead>
                    <tr>
                        <th class="service">PRODUCTO</th>
                        <th class="desc">FECHA</th>
                        <th>CANTIDAD</th>
                        <th>P.NETO</th>
                        <th>PRECIO</th>
                        <th>TOTAL</th>
                    </tr>
                    </thead>
                    <tbody>
                        ' . $tbody . '
                        <tr>
                            <td colspan="5" class="grand total totales">VALOR TOTAL</td>
                            <td class="grand total totales tc-green">$ ' . $subtotal . '</td>
                        </tr>
                    </tbody>
                </table>
                <div id="notices">
                    <div>OBSERVACIONES:</div>
                    <div class="notice">' . $textoDescripcion . '</div>
                </div>
                </main>
                <footer>
                    <span class="text-bold">Granja LITE</span> le agradece por su compra.
                </footer>
            </body>
            </html>
        ';

        $nombreReporte = 'RegListadoSalida-' . $datosCliente["cli_nombre"] . '_' . $fecha_desde . '.pdf';

        generarPDF($html, $nombreReporte, $descarga);
    }
}

function generarPDF($html, $nombreReporte, $descarga)
{

    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isPhpEnabled', true);
    $options->set('isRemoteEnabled', true);

    $dompdf = new Dompdf($options);

    $dompdf->loadHtml($html);
    $dompdf->render();
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename= nombre-del-archivo.pdf');
    header('Cache-Control: public, must-revalidate, max-age=0');
    header('Pragma: public');

    // $dompdf->stream();
    $dompdf->stream($nombreReporte, array('Attachment' => $descarga));

    // $fileLocation = '../assets/pdf/salidas/Reg_salida_' . $salida_id . '.pdf';
    $dompdf->output();
    // file_put_contents($fileLocation, $dompdf->output());

    exit();
}

function buscarCliente($cli_id)
{

    $cliente = new Cliente();
    $detalle = $cliente->getClientePorId($cli_id);
    foreach ($detalle as $row) {
        $datosCliente["cli_nombre"] = $row["cli_nombre"];
        $datosCliente["cli_ruc"] = $row["cli_ruc"];
        $datosCliente["cli_telefono"] = $row["cli_telefono"];
        $datosCliente["cli_direccion"] = $row["cli_direccion"];
        $datosCliente["cli_correo"] = $row["cli_correo"];
    }

    return $datosCliente;
}

function datosEmpresa($emp_id, $com_id)
{
    $empresa = new Empresa();
    $detalleEmpresa = $empresa->getEmpresaPorId("R", $emp_id, $com_id);
    foreach ($detalleEmpresa as $row) {
        $datosEmpresa["emp_id"] = $row["emp_id"];
        $datosEmpresa["emp_nombre"] = $row["emp_nombre"];
        $datosEmpresa["emp_ruc"] = $row["emp_ruc"];
        $datosEmpresa["emp_telefono"] = $row["emp_telefono"];
        $datosEmpresa["emp_estado"] = $row["emp_estado"];
        $datosEmpresa["emp_direccion"] = $row["emp_direccion"];
        $datosEmpresa["emp_correo"] = $row["emp_correo"];
    }

    return $datosEmpresa;
}
