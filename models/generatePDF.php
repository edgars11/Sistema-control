<?php
require_once '../include/vendor/autoload.php';
require_once '../config/conexion.php';
require_once '../models/SalidaLote.php';
require_once '../models/Empresa.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class GeneratePDF extends Conectar
{
    public function generate_pdf_salida($salida_id, $emp_id, $com_id)
    {
        //DatosCliente;
        $salidalote = new SalidaLote();
        $empresa = new Empresa();

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);

        $detalleEmpresa = $empresa->getEmpresaPorId("R",$emp_id, $com_id);
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
                        <td class="service">' . $tipoProd . '</td>
                        <td class="desc">' . $row["salida_fecha"] . '</td>
                        <td class="unit"># ' . $row["salida_cantidad"] . '</td>
                        <td class="qty">' . $row["salida_peso_neto"] . ' Lbs</td>
                        <td class="total">$ ' . $row["salida_precio"] . '</td>
                        <td class="total">$ ' . $row["salida_total"] . '</td>
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
                    <img src="logo.png">
                </div>
                <h1>REGISTRO SALIDA #' . $salida_id . '</h1>
                <div id="company" class="clearfix">
                    <div>'.$datosEmpresa["emp_nombre"].'</div>
                    <div>'.$datosEmpresa["emp_direccion"].'</div>
                    <div>'.$datosEmpresa["emp_telefono"].'</div>
                    <div>'.$datosEmpresa["emp_ruc"].'</div>
                    <div><a href="mailto:'.$datosEmpresa["emp_correo"].'">'.$datosEmpresa["emp_correo"].'</a></div>
                </div>
                <div id="project">
                    <div><span>PROJECT</span>Control Salida Mercaderia</div>
                    <div><span>CLIENTE:</span> '.$datosCliente["cli_nombre"].'</div>
                    <div><span>DIRECCIÓN:</span> '.$datosCliente["cli_direccion"].'</div>
                    <div><span>CORREO:</span> <a href="'.$datosCliente["cli_correo"].'">'.$datosCliente["cli_correo"].'</a></div>
                    <div><span>RUC/CI:</span> '.$datosCliente["cli_ruc"].'</div>
                    <div><span>CONTACTO:</span> '.$datosCliente["cli_telefono"].'</div>
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
                            <td colspan="5" class="grand total">SUBTOTAL</td>
                            <td class="grand total">$ '.$subtotal.'</td>
                        </tr>
                        <tr>
                            <td colspan="5">IVA '.$valorIva.'%</td>
                            <td class="total">$ 0.00</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="strong" >VALOR TOTAL</td>
                            <td class="total">$ '.$subtotal.'</td>
                        </tr>
                    </tbody>
                </table>
                <div id="notices">
                    <div>NOTICE:</div>
                    <div class="notice">A finance charge of 1.5% will be made on unpaid balances after 30 days.</div>
                </div>
                </main>
                <footer>
                Invoice was created on a computer and is valid without the signature and seal.
                </footer>
            </body>
            </html>
        ';

        $dompdf->loadHtml($html);
        $dompdf->render();
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename= nombre-del-archivo.pdf');
        header('Cache-Control: public, must-revalidate, max-age=0');
        header('Pragma: public');

        $dompdf->stream();
        // $dompdf->stream('Reg_NombreCliente_' . $salida_id . '.pdf');

        $fileLocation = '../assets/pdf/salidas/Reg_salida_' . $salida_id . '.pdf';
        $dompdf->output();
        file_put_contents($fileLocation, $dompdf->output());

        exit();
    }
}
