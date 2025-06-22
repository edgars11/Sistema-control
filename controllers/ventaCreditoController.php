<?php
// TODO: Llamando clases
require_once("../config/conexion.php");
require_once("../models/VentaCreditoModel.php");
// TODO: Inicializando clases
$ventasCred = new VentaCreditoModel();

switch ($_GET['op']) {
    // TODO: Listado de registro en format JSON para Datatable JS
    case 'listar':
        $datos = $ventasCred->getListadoVentasCred("L", $_POST['cta_id']);
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $output["rvc_id"] = $row["rvc_id"];
                $output["ven_id"] = $row["ven_id"];
                $output["rvc_monto"] = $row["rvc_monto"];
                $output["rvc_abonado"] = $row["rvc_abonado"];
                $output["rvc_est_cta"] = $row["rvc_est_cta"];
            }
        }

        echo json_encode($output);
        break;
    // TODO: Listar combo
    case 'combo':
        $datos = $ventasCred->getListadoVentasCred("L", $_POST['cta_id']);
        if (is_array($datos) == true and count($datos) > 0) {
            $html = "";
            $html .= '<option selected>Seleccione venta</option>';
            foreach ($datos as $row) {
                $saldo = $row['rvc_monto'] - $row['rvc_abonado'];
                $estado = $row['rvc_est_cta'] == 'A' ? 'Abonado: $' . $row['rvc_abonado'] . ' - Saldo: $' . number_format($saldo, 2, '.', '') : 'Pendiente : $' . $row['rvc_monto'];
                $html .= "<option value='" . $row['ven_id'] . "'> Venta #" . $row['ven_id'] . " [$ ". $row['rvc_monto']."] - " . $estado . "</option>";
            }
            echo $html;
        }
        break;
    case 'guardarPago':
        $usu_id = $_SESSION["usu_id"];
        $datos = $ventasCred->registrarPago($_POST['cta_id'], $_POST['pago_id'], $_POST['pagc_obs'], $usu_id, $_POST['pagc_monto'], $_POST['suc_id'], $_POST['ven_id'], $_POST['cli_id']);

        if (is_array($datos) == true and count($datos) > 0) {
            $saldo = $datos[0]["total"];
            $prox_recibo = intval($datos[0]["prox_recibo"]);
            $counter = 0;
            $outout["val".$counter] = $saldo;
            while($saldo > 0 and $prox_recibo > 0){
                $datos = $ventasCred->registrarPago(
                $_POST['cta_id'], 
                $_POST['pago_id'], 
                $_POST['pagc_obs'], 
                $usu_id, 
                $saldo, 
                $_POST['suc_id'], 
                $prox_recibo,
                $_POST['cli_id']);

                $saldo = $datos[0]["total"];
                $prox_recibo = intval($datos[0]["prox_recibo"]);

                $counter ++;
                $outout["val".$counter] = $saldo;
            }
        }

        $outout["success"] = true;

        echo json_encode($outout);

        break;
    // TODO: Listado de registro en format JSON para Datatable JS
    case 'getById':
        $datos = $ventasCred->getDatosVenta($_POST['ven_id']);
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $output["cli_id"] = $row["cli_id"];
                $output["pago_id"] = $row["pago_id"];
                $output["ven_subtotal"] = $row["ven_subtotal"];
                $output["ven_total"] = $row["ven_total"];
                $output["ven_fecha_crea"] = $row["ven_fecha_crea"];
                $output["ven_coment"] = $row["ven_coment"];
                $output["rvc_abonado"] = $row["rvc_abonado"];
                $output["rvc_est_cta"] = $row["rvc_est_cta"];
            }
        }

        echo json_encode($output);
        break;

}
