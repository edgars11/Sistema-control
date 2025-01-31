<?php
// TODO: Llamando clases
require_once("../config/conexion.php");
require_once("../models/TipoComprabante.php");
// TODO: Inicializando clases
$tipocomprobante = new TipoComprobante();

switch ($_GET['op']) {
    /* TODO: Listar combo */
    case 'combo':
        $datos = $tipocomprobante->getTipoComprobantes();
        if (is_array($datos) == true and count($datos) > 0) {
            $html = "";
            $html .= '<option selected>Seleccionar</option>';
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['tc_id'] . "'>" . $row['tc_codigo']  . "-" . $row['tc_descripcion'] . "</option>";
            }
            echo $html;
        }
        break;
}
