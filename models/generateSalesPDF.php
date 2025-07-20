<?php
require_once '../include/vendor/autoload.php';
require_once '../config/conexion.php';
require_once '../models/VentaCreditoModel.php';
require_once '../models/TipoComprabante.php';
require_once '../models/Empresa.php';
require_once '../models/Venta.php';
require_once '../models/Cliente.php';
require_once '../models/Cuentas.php';
require_once '../models/Utils.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class GenerateSalesPDF extends Conectar
{
    public function generate_pdf_listado_ventas_Credito($cli_id, $tipo_pago, $fecha_desde, $fecha_hasta, $suc_id, $download)
    {
        //DatosCliente;
        $tipoPago = new TipoComprobante();
        $ventasMod = new Venta();
        $rutaImg = $_SERVER['HTTP_HOST'];

        $descarga = $download === "1" ? true : false;

        $datosEmpresa = datosEmpresaBySucursal($suc_id);
        $datosCliente = buscarCliente2($cli_id);
        if ($tipo_pago != 'null') {
            $formaPago = $tipoPago->getFormaPagoByID($tipo_pago);
            $nombreFormaPago = $formaPago[0]['pago_nombre'];
        } else {
            $tipo_pago = null;
            $nombreFormaPago = 'OTRO';
        }

        if ($nombreFormaPago == 'CREDITO') {
            $detalle = $ventasMod->getListadoVentaRegistradas('P', $suc_id, $fecha_desde, $fecha_hasta, $cli_id, $tipo_pago);
        } else {
            $detalle = $ventasMod->getListadoVentaRegistradas('P', $suc_id, $fecha_desde, $fecha_hasta, $cli_id, $tipo_pago);
        }

        $subtotal = 0;
        $valorCredito = 0;
        $valorIva = 15;
        $totalVentas = 0;

        $content_css = file_get_contents('../assets/css/stylePDF.css');
        $tbody = "";
        foreach ($detalle as $row) {

            $subtotal = $subtotal + $row["ven_total"];
            if($row["pago_nombre"] == 'CREDITO'){
                $valorCredito += $row["ven_total"];
            }
            $tbody .= '
                    <tr>
                        <td class="service ts-13 text-fw-600"> Venta - #' . $row["ven_id"] . '</td>
                        <td class="desc">' . $row["ven_fecha_crea"] . '</td>
                        <td class="subtotal">$ ' . number_format($row["ven_total"], 2, '.', ',')  . '</td>
                        <td class="unit">' . $row["pago_nombre"] . '</td>
                        <td class="unit">' . $row['reg_estado'] . '</td>
                        <td class="qty">' . ($row['ven_coment'] == '' ? 'Sin comentario' : $row['ven_coment']). '</td>
                    </tr>
            ';
            $totalVentas = $totalVentas + 1;
        }

        $observacion = 'Total Ventas registradas: <span class="text-fw-600">' . $totalVentas . '</span>, Valor Total Ventas: <span class="text-fw-600">$ ' . number_format($subtotal,2) . '</span><br><span class="text-fw-600">Cuenta</span> - '.( $datosCliente['total_ventas'] > 0 ? 'Saldo Pendiente Ventas : <span class="text-fw-600">$ ' . number_format($datosCliente['total_ventas'], 2) . '</span>' : ' Cliente no tiene cuenta.');

        $html = '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="utf-8">
                <title>LISTADO DE VENTAS</title>
                <style>' . $content_css . '</style>
            </head>
            <body>
                <header class="clearfix">
                <div id="logo">
                    <img src="http://' . $rutaImg . '/Sistema-Control/assets/images/logo-lite.jpg" alt="Logo empresa" style="width: 100px"><br>
                </div>
                <h1>Listado de ventas de <span class="tc-yellow">' . $datosCliente['cli_nombre'] . '</span></h1>
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
                        <th class="service">NUM VENTA</th>
                        <th class="desc">FECHA</th>
                        <th>MONTO</th>
                        <th>FORMA PAGO</th>
                        <th>ESTADO</th>
                        <th>COMENTARIO</th>
                    </tr>
                    </thead>
                    <tbody>
                        ' . $tbody . '
                        <tr>
                            <td colspan="5" class="grand total totales">VALOR TOTAL</td>
                            <td class="grand ts-15 text-fw-600 totales tc-green">$ ' . number_format($subtotal, 2, '.', ',')  . '</td>
                        </tr>
                    </tbody>
                </table>
                <div id="notices">
                    <div>OBSERVACIONES:</div>
                    <div class="notice">' . $observacion . '</div>
                </div>
                </main>
                <footer>
                    <span class="text-bold">Granja LITE</span> le agradece por su compra.
                </footer>
            </body>
            </html>
        ';
        $nombreReporte = 'ReporteVentas-' . $datosCliente["cli_nombre"] . '-Desde-' . $fecha_desde . '-Hasta-' . $fecha_hasta . '.pdf';

        generarPDF2($html, $nombreReporte, $descarga, 'portrait');
    }

    public function generarListadoCuentasPorCobrar($suc_id, $download)
    {
        //DatosCliente;
        $cuentaModelo = new Cuentas();
        $rutaImg = $_SERVER['HTTP_HOST'];

        $descarga = $download === "1" ? true : false;

        $datosEmpresa = datosEmpresaBySucursal($suc_id);
        $datosCliente = buscarCliente2(0);

        $detalleCuentas = $cuentaModelo->getListadoCuentaPorCobrar($suc_id);

        $subtotal = 0;
        $totalVentas = 0;

        $content_css = file_get_contents('../assets/css/stylePDFLand.css');
        $tbody = "";
        foreach ($detalleCuentas as $row) {

            $subtotal = $subtotal + $row["cta_monto"];
            $tbody .= '
                    <tr>
                        <td class="service ts-13 text-fw-600"> Venta - #' . $row["cta_id"] . '</td>
                        <td class="desc tc-blue">' . strtoupper($row["cli_nombre"]) . '</td>
                        <td class="unit">' . $row["cta_fecha_ult"] . '</td>
                        <td class="subtotal text-fw-600 warning">$ ' . number_format($row["cta_monto"], 2, '.', ',')  . '</td>
                        <td class="unit">$ ' . number_format($row["monto_pedidos"], 2, '.', ',')  . '</td>
                        <td class="unit">$ ' . number_format($row["monto_ventas"], 2, '.', ',')  . '</td>
                        <td class="qty">' . ($row['cta_obs'] == '' ? 'Sin comentario' : $row['cta_obs']). '</td>
                    </tr>
            ';
            $totalVentas = $totalVentas + 1;
        }

        $observacion = 'Total Cuentas: <span class="text-fw-600"> #' . $totalVentas . '</span>, Valor Total Cuentas: <span class="text-fw-600">$ ' . number_format($subtotal,2) . '</span>';

        $html = '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="utf-8">
                <title>LISTADO DE CUENTAS POR COBRAR</title>
                <style>' . $content_css . '</style>
            </head>
            <body>
                <header class="clearfix">
                <div id="logo">
                    <img src="http://' . $rutaImg . '/Sistema-Control/assets/images/logo-lite.jpg" alt="Logo empresa" style="width: 100px"><br>
                </div>
                <h1>Listado de cuentas por cobrar</span></h1>
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
                        <th class="service"># CUENTA</th>
                        <th class="service">CLIENTE</th>
                        <th class="desc">FECHA ULT MOV</th>
                        <th>TOTAL</th>
                        <th>MONTO PEDIDO</th>
                        <th>MONTO VENTA</th>
                        <th>COMENTARIO</th>
                    </tr>
                    </thead>
                    <tbody>
                        ' . $tbody . '
                        <tr>
                            <td colspan="6" class="grand total totales">VALOR TOTAL</td>
                            <td class="grand ts-15 text-fw-600 totales tc-green">$ ' . number_format($subtotal, 2, '.', ',')  . '</td>
                        </tr>
                    </tbody>
                </table>
                <div id="notices">
                    <div>OBSERVACIONES:</div>
                    <div class="notice">' . $observacion . '</div>
                </div>
                </main>
                <footer>
                    <span class="text-bold">Granja LITE</span> le agradece por su compra.
                </footer>
            </body>
            </html>
        ';
        $nombreReporte = 'ListadoDeCuentaPorCobrar-'.date("Y-m-d").'.pdf';

        generarPDF2($html, $nombreReporte, $descarga, 'landscape');
    }

    // public function generate_pdf_listado_ventas($cli_id, $tipo_pago, $fecha_desde, $fecha_hasta, $suc_id, $download)
    // {
    //     //DatosCliente;
    //     $ventaCreditoModel = new VentaCreditoModel();

    //     $rutaImg = $_SERVER['HTTP_HOST'];

    //     $descarga = $download === "1" ? true : false;

    //     $nombreClienteReporte = '';
    //     $subtotal = 0;
    //     $valorIva = 15;
    //     $iva = 0;
    //     $colSpan = 0;
    //     $tbody = "";

    //     $content_css = file_get_contents('../assets/css/stylePDFLand.css');
    //     $cli_id = $cli_id === 'null' ? null : $cli_id;
    //     $tipo_pago = $tipo_pago === 'null' ? null : $tipo_pago;
    //     $listadoVentas = $ventaCreditoModel;
    //     $totalesConsulta = 0;

    //     $textoDescripcion = 'El reporte generado muestra el listado de registro desde la fecha: <span class="text-fw-600">' . $fecha_desde . ' </span> hasta la fecha: <span class="text-fw-600">' .  $fecha_hasta . '</span>.<br>Total cantidad entregada: <span class="text-fw-600">#' . $totalesConsulta[0]['cantidad'] . '</span> Total libras entregadas: <span class="text-fw-600">' . $totalesConsulta[0]['peso_neto'] . ' Lbs </span>';

    //     $columns = "";
    //     $textoCuenta = "";
    //     $columnasSaldosCliente = "";
    //     if (!empty($cli_id)) {

    //         $colSpan = 6;
    //         $columns = '
    //                     <th class="text-left"># RECIBO - ESTADO</th>
    //                     <th class="text-left">PRODUCTO</th>
    //                     <th class="wd-th-40">FECHA</th>
    //                     <th class="wd-th-30">CANTIDAD</th>
    //                     <th>P.NETO</th>
    //                     <th>PRECIO</th>
    //                     <th>TOTAL</th>
    //         ';

    //         $textoCuenta = '<br><span class="text-fw-600">Datos Cuenta Cliente:</span> Saldo abonado en el rango de fechas seleccionada: <span class="text-fw-600">$ ' . $totalesConsulta[0]['monto_abonado'] . '</span> - Saldo Total Pendiende Cuenta: <span class="text-fw-600">$ ' . $totalesConsulta[0]['saldo_total_cta'] . '</span>';

    //         $columnasSaldosCliente = '<tr>
    //                         <td colspan="' . $colSpan . '" class="totales">MONTO ABONADO:</td>
    //                         <td class="totales tc-green ts-15 text-fw-200">$ ' . $totalesConsulta[0]["monto_abonado"] . '</td>
    //                     </tr>
    //                     <tr>
    //                         <td colspan="' . $colSpan . '" class="totales">SALDO PENDIENTE:</td>
    //                         <td class="totales tc-red ts-15 text-fw-200">$ ' . $totalesConsulta[0]["saldo_total_cta"] . '</td>
    //                     </tr>';

    //         foreach ($detalle as $row) {
    //             $estadoRecibo = $row["salida_vpagado"];
    //             $colorTag = '';
    //             if ($estadoRecibo == 'N') {
    //                 $estadoRecibo = 'PENDIENTE';
    //                 $colorTag = 'danger';
    //             } else if ($estadoRecibo == 'A') {
    //                 $estadoRecibo = 'ABONADO';
    //                 $colorTag = 'warning';
    //             } else if ($estadoRecibo == 'C') {
    //                 $estadoRecibo = 'CANCELADO';
    //                 $colorTag = 'success';
    //             }

    //             $subtotal = $subtotal + $row["salida_total"];
    //             $tipoProd = $row["salida_tipo"] == 'PV' ? 'Pollo Vivo' : 'Pollo Faenado';
    //             $tbody .= '
    //                     <tr>
    //                         <td class="ts-13 text-left text-fw-500"> # ' . $row["salida_id"] . ' - <span class="' . $colorTag . '">' . $estadoRecibo . '</span> </td>
    //                         <td class="ts-13 text-left">' . $tipoProd . '</td>
    //                         <td class="ts-13 text-fw-500">' . $row["salida_fecha"] . '</td>
    //                         <td class="ts-13">' . number_format($row["salida_cantidad"], 0, '', ',')  . '</td>
    //                         <td class="ts-13 text-fw-500">' . number_format($row["salida_peso_neto"], 2, '.', ',') . ' Lbs</td>
    //                         <td class="ts-13">$' . number_format($row["salida_precio"], 2, '.', ',')  . '</td>
    //                         <td class="ts-13 text-fw-500">$' . number_format($row["salida_total"], 2, '.', ',') . '</td>
    //                     </tr>
    //             ';
    //         }
    //     } else {
    //         $colSpan = 7;
    //         $columns = '
    //                     <th class="service"># RECIBO - ESTADO</th>
    //                     <th class="service">CLIENTE</th>
    //                     <th class="service">PRODUCTO</th>
    //                     <th class="desc wd-th-50">FECHA</th>
    //                     <th class="wd-th-30">CANT.</th>
    //                     <th>P.NETO</th>
    //                     <th>PRECIO</th>
    //                     <th>TOTAL</th>
    //         ';

    //         foreach ($detalle as $row) {

    //             $estadoRecibo = $row["salida_vpagado"];
    //             $colorTag = '';
    //             if ($estadoRecibo == 'N') {
    //                 $estadoRecibo = 'PENDIENTE';
    //                 $colorTag = 'danger';
    //             } else if ($estadoRecibo == 'A') {
    //                 $estadoRecibo = 'ABONADO';
    //                 $colorTag = 'warning';
    //             } else if ($estadoRecibo == 'C') {
    //                 $estadoRecibo = 'CANCELADO';
    //                 $colorTag = 'success';
    //             }

    //             $subtotal = $subtotal + $row["salida_total"];
    //             $tipoProd = $row["salida_tipo"] == 'PV' ? 'Pollo Vivo' : 'Pollo Faenado';
    //             $tbody .= '
    //             <tr>
    //                 <td class="ts-12 text-left text-fw-500"> # ' . $row["salida_id"] . ' - <span class="' . $colorTag . '">' . $estadoRecibo . '</span> </td>
    //                 <td class="service text-fw-500">' . $row["cli_nombre"] . '</td>
    //                 <td class="service">' . $tipoProd . '</td>
    //                 <td class="ts-11 text-fw-500">' . $row["salida_fecha"] . '</td>
    //                 <td class="ts-12">#' . number_format($row["salida_cantidad"], 0, '', ',')  . '</td>
    //                 <td class="ts-12 text-fw-500">' . number_format($row["salida_peso_neto"], 2, '.', ',') . 'Lbs</td>
    //                 <td class="ts-12">$' . number_format($row["salida_precio"], 2, '.', ',')  . '</td>
    //                 <td class="ts-12 text-fw-500">$' . number_format($row["salida_total"], 2, '.', ',') . '</td>
    //             </tr>
    //             ';
    //         }
    //     }

    //     $encabezado = getEncabezadoReporte($lote_id, $salida_tipo, $cli_id, $emp_id, $com_id, $totalesConsulta, $fecha_desde, $fecha_hasta, $nombreClienteReporte);

    //     $html = '
    //         <!DOCTYPE html>
    //         <html lang="en">
    //         <head>
    //             <meta charset="utf-8">
    //             <title>Registro Listado de Salida </title>
    //             <style>' . $content_css . '</style>
    //         </head>
    //         <body>
    //             <header class="clearfix">
    //             <div id="logo">
    //                 <img src="http://' . $rutaImg . '/Sistema-Control/assets/images/logo-lite.jpg" alt="Logo empresa" style="width: 100px"><br>
    //             </div>
    //             <h1>REGISTRO LISTADO PEDIDOS</h1>
    //             ' . $encabezado . '
    //             </header>
    //             <main>
    //             <table>
    //                 <thead>
    //                 <tr>
    //                     ' . $columns . '
    //                 </tr>
    //                 </thead>
    //                 <tbody>
    //                     ' . $tbody . '
    //                     <tr>
    //                         <td colspan="' . $colSpan . '" class="grand totales">TOTAL REPORTE:</td>
    //                         <td class="grand totales tc-yellow ts-15 text-fw-200">$ ' . number_format($subtotal, 2, '.', ',')  . '</td>
    //                     </tr>
    //                     ' . $columnasSaldosCliente . '
    //                 </tbody>
    //             </table>
    //             <div id="notices">
    //                 <div>OBSERVACIONES:</div>
    //                 <div class="notice">' . $textoDescripcion . $textoCuenta . '</div>
    //             </div>
    //             </main>
    //             <footer>
    //                 <span class="text-bold">Granja LITE</span> le agradece por su compra.
    //             </footer>
    //         </body>
    //         </html>
    //     ';

    //     $nombreReporte = 'RegListadoPedido' . $nombreClienteReporte . '-Desde-' . $fecha_desde . '-Hasta-' . $fecha_hasta . '.pdf';

    //     generarPDF($html, $nombreReporte, $descarga, 'landscape');
    // }
}

function generarPDF2($html, $nombreReporte, $descarga, $typePaper)
{

    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isPhpEnabled', true);
    $options->set('isRemoteEnabled', true);

    $dompdf = new Dompdf($options);
    $dompdf->set_paper('A4',  $typePaper); //landscape - 'portrait'
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

function buscarCliente2($cli_id)
{
    $cliente = new Cliente();

    if($cli_id == 0){
        $utils = new Utils();
        $data = $utils->getParam('CLICF');
        if (is_array($data) == true and count($data) > 0) {
            foreach ($data as $row) {
                $cli_id = $row["par_int"];
            }
        }
    }
    $detalle = $cliente->getClientePorId($cli_id);
    foreach ($detalle as $row) {
        $datosCliente["cli_nombre"] = $row["cli_nombre"];
        $datosCliente["cli_ruc"] = $row["cli_ruc"];
        $datosCliente["cli_telefono"] = $row["cli_telefono"];
        $datosCliente["cli_direccion"] = $row["cli_direccion"];
        $datosCliente["cli_correo"] = $row["cli_correo"];
        $datosCliente["total_ventas"] = $row["total_ventas"];
    }

    return $datosCliente;
}

function datosEmpresaBySucursal($suc_id)
{
    $empresa = new Empresa();
    $detalleEmpresa = $empresa->getEmpresaBySucursal($suc_id);
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

// function getEncabezadoReporte($lote_id, $salida_tipo, $cli_id, $emp_id, $com_id, $totalesConsulta, $fecha_desde, $fecha_hasta, $nombreClienteReporte)
// {
//     $datosEmpresa = datosEmpresa($emp_id, $com_id);

//     if (!empty($salida_tipo)) {
//         $producto = $salida_tipo == 'PV' ? 'POLLO VIVO' : 'POLLO FAENADO';
//     } else {
//         $producto = "POLLO VIVO / POLLO FAENADO";
//     }

//     if (!empty($cli_id) && $lote_id == null) {
//         $datosCliente = buscarCliente($cli_id);
//         $nombreClienteReporte = '-' . $datosCliente["cli_nombre"] . '-';
//         $html = '
//         <div id="company" class="clearfix">
//                         <div class="margin-bottom-25"><span class="text-fw-600 margin-bottom-25 ts-15">DATOS EMPRESA</span></div>
//                         <div><span class="ts-13">' . $datosEmpresa["emp_nombre"] . '</span></div>
//                         <div><span class="ts-13">' . $datosEmpresa["emp_direccion"] . '</span></div>
//                         <div><span class="ts-13"><a href="mailto:' . $datosEmpresa["emp_correo"] . '">' . $datosEmpresa["emp_correo"] . '</a></span></div>
//                         <div><span class="ts-13">' . $datosEmpresa["emp_ruc"] . '</span></div>
//                         <div><span class="ts-13">' . $datosEmpresa["emp_telefono"] . '</span></div>
//                     </div>
//                     <div id="project">
//                         <div class="margin-bottom-25"><span class="text-fw-600  ts-15">REPORTE DE:</span></div>
//                         <div><span class="text-fw-600">CLIENTE:</span> <span class="ts-13">' . $datosCliente["cli_nombre"] . '</span> </div>
//                         <div><span class="text-fw-600">DIRECCIÓN:</span>  <span class="ts-13">' . $datosCliente["cli_direccion"] . ' </span></div>
//                         <div><span class="text-fw-600">CORREO:</span> <span class="ts-13"> <a href="' . $datosCliente["cli_correo"] . '">' . $datosCliente["cli_correo"] . '</a> </span></div>
//                         <div><span class="text-fw-600">RUC/CI:</span>  <span class="ts-13">' . $datosCliente["cli_ruc"] . ' </span></div>
//                         <div><span class="text-fw-600">CONTACTO:</span> <span class="ts-13"> ' . $datosCliente["cli_telefono"] . ' </span></div>
//                         <div><span class="text-fw-600">PRODUCTO:</span> <span class="ts-13"> ' . $producto . ' </span></div>
//                     </div>
//         ';
//     } else if ($lote_id == null && $cli_id == null) {

//         $infoLotesT = "";
//         $salidalote = new SalidaLote();
//         $detalleLote = $salidalote->getTotalesLotesByFecha($fecha_desde, $fecha_hasta);

//         foreach ($detalleLote as $row) {

//             $infoLotesT .= '
//             <div><span class="text-fw-600">LOTE:</span> <span class="ts-13"> ' . $row['lote_descripcion'] . ' </span></div>
//             <div><span class="text-fw-500">Cantidad:</span> <span class="ts-13"> #' . $row['cantidad'] . ' </span> | <span class="text-fw-500">Peso Neto:</span> <span class="ts-13"> ' . $row['peso_neto'] . ' Lbs</span> | <span class="text-fw-500">Total:</span> <span class="ts-13"> $' . $row['total'] . '</span></div>
//             ';
//         }

//         $html = '
//         <div id="company" class="clearfix">
//             <div class="margin-bottom-25"><span class="text-fw-600 margin-bottom-25 ts-15">DATOS EMPRESA</span></div>
//             <div><span class="ts-13">' . $datosEmpresa["emp_nombre"] . '</span></div>
//             <div><span class="ts-13">' . $datosEmpresa["emp_direccion"] . '</span></div>
//             <div><span class="ts-13"><a href="mailto:' . $datosEmpresa["emp_correo"] . '">' . $datosEmpresa["emp_correo"] . '</a></span></div>
//             <div><span class="ts-13">' . $datosEmpresa["emp_ruc"] . '</span></div>
//             <div><span class="ts-13">' . $datosEmpresa["emp_telefono"] . '</span></div>
//         </div>
//         <div id="project">
//             <div class="margin-bottom-25"><span class="text-fw-600  ts-15">REPORTE LOTES:</span></div>
//             ' . $infoLotesT . '
//             <div><span class="text-fw-600">PRODUCTO:</span> <span class="ts-13"> ' . $producto . ' </span></div>
//         </div>
//         ';
//     } else if (!empty($lote_id) && $cli_id == null) {

//         $lote = new Lote();

//         $infoLote = $lote->getLotePorId("R", $lote_id);

//         $html = '
//         <div id="company" class="clearfix">
//             <div class="margin-bottom-25"><span class="text-fw-600 margin-bottom-25 ts-15">DATOS EMPRESA</span></div>
//             <div><span class="ts-13">' . $datosEmpresa["emp_nombre"] . '</span></div>
//             <div><span class="ts-13">' . $datosEmpresa["emp_direccion"] . '</span></div>
//             <div><span class="ts-13"><a href="mailto:' . $datosEmpresa["emp_correo"] . '">' . $datosEmpresa["emp_correo"] . '</a></span></div>
//             <div><span class="ts-13">' . $datosEmpresa["emp_ruc"] . '</span></div>
//             <div><span class="ts-13">' . $datosEmpresa["emp_telefono"] . '</span></div>
//         </div>
//         <div id="project">
//             <div class="margin-bottom-25"><span class="text-fw-600  ts-15">DATOS LOTE SELECCIONADO:</span></div>
//             <div><span class="text-fw-600">LOTE:</span> <span class="ts-13">' . $infoLote[0]["lote_descripcion"] . '</span> </div>
//             <div><span class="text-fw-600">CANTIDAD VENDIDA:</span> #<span class="ts-13">' . $totalesConsulta[0]["cantidad"] . '</span> </div>
//             <div><span class="text-fw-600">TOTAL LIBRAS:</span> <span class="ts-13">' . $totalesConsulta[0]["peso_neto"] . ' Lbs</span> </div>
//             <div><span class="text-fw-600">TOTAL VALOR:</span> $<span class="ts-13">' . $totalesConsulta[0]["total"] . '</span> </div>
//             <div><span class="text-fw-600">PRODUCTO:</span> <span class="ts-13"> ' . $producto . ' </span></div>
//         </div>
//         ';
//     } else if (!empty($lote_id) && !empty($cli_id)) {

//         $lote = new Lote();

//         $infoLote = $lote->getLotePorId("R", $lote_id);
//         $datosCliente = buscarCliente($cli_id);
//         $html = '
//         <div id="company" class="clearfix">
//             <div class="margin-bottom-25"><span class="text-fw-600 margin-bottom-25 ts-15">DATOS EMPRESA</span></div>
//             <div><span class="ts-13">' . $datosEmpresa["emp_nombre"] . '</span></div>
//             <div><span class="ts-13">' . $datosEmpresa["emp_direccion"] . '</span></div>
//             <div><span class="ts-13"><a href="mailto:' . $datosEmpresa["emp_correo"] . '">' . $datosEmpresa["emp_correo"] . '</a></span></div>
//             <div><span class="ts-13">' . $datosEmpresa["emp_ruc"] . '</span></div>
//             <div><span class="ts-13">' . $datosEmpresa["emp_telefono"] . '</span></div>
//         </div>
//         <div id="project">
//             <div class="margin-bottom-25"><span class="text-fw-600  ts-15">DATOS LOTE SELECCIONADO:</span></div>
//             <div><span class="text-fw-600">LOTE:</span> <span class="ts-13">' . $infoLote[0]["lote_descripcion"] . '</span> </div>
//             <div><span class="text-fw-600">CANTIDAD VENDIDA:</span> #<span class="ts-13">' . $totalesConsulta[0]["cantidad"] . '</span> </div>
//             <div><span class="text-fw-600">TOTAL LIBRAS:</span> <span class="ts-13">' . $totalesConsulta[0]["peso_neto"] . ' Lbs</span> </div>
//             <div><span class="text-fw-600">TOTAL VALOR:</span> $<span class="ts-13">' . $totalesConsulta[0]["total"] . '</span> </div>
//             <br>
//             <div class="margin-bottom-25"><span class="text-fw-600  ts-15">DATOS CLIENTE SELECCIONADO:</span></div>
//             <div><span class="text-fw-600">CLIENTE:</span> <span class="ts-13">' . $datosCliente["cli_nombre"] . '</span> </div>
//             <div><span class="text-fw-600">RUC:</span> <span class="ts-13">' . $datosCliente["cli_ruc"] . '</span> </div>
//             <div><span class="text-fw-600">PRODUCTO:</span> <span class="ts-13"> ' . $producto . ' </span></div>
//         </div>
//         ';
//     }

//     return $html;
// }
