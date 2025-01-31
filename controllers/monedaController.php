<?php
// TODO: Llamando clases
require_once("../config/conexion.php");
require_once("../models/Moneda.php");
// TODO: Inicializando clases
$moneda = new Moneda();

switch ($_GET['op']) {
    // TODO: Guardar y editar registro
    case 'guardar':
        if (empty($_POST["mon_id"])) {
            $moneda->insertMoneda("C", $_POST['suc_id'], $_POST['mon_nombre']);
        } else {
            $moneda->updateMoneda("U", $_POST['suc_id'], $_POST['mon_nombre'], $_POST['mon_id']);
        }
        break;
    // TODO: Listado de registro en format JSON para Datatable JS
    case 'listar':
        $datos = $moneda->getMonedaPorSucursal("R", $_POST['suc_id']);
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row['mon_id'];
            $sub_array[] = $row['mon_nombre'];
            $sub_array[] = $row['mon_fecha_crea'];
            $sub_array[] = $row['mon_estado'] === '1' ? "Activo" : "Inactivo";
            $sub_array[] = '<button type="button" onClick="editar(' . $row['mon_id'] . ')" id="' . $row['mon_id'] . '" class="btn btn-success btn-icon waves-effect waves-light"><i class="ri-edit-2-line"></i></button>';
            $sub_array[] = '<button type="button" onClick="eliminar(' . $row['mon_id'] . ')" id="' . $row['mon_id'] . '" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>';
            $data[] = $sub_array;
        }
        // Usado en el DataTable
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
        break;
    // TODO: Mostrar información del registro por ID
    case 'mostrar':
        $datos = $moneda->getMonedaPorId("R", $_POST['mon_id']);
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $outout["mon_id"] = $row["mon_id"];
                $outout["suc_id"] = $row["suc_id"];
                $outout["mon_nombre"] = $row["mon_nombre"];
                $outout["mon_fecha_crea"] = $row["mon_fecha_crea"];
                $outout["mon_estado"] = $row["mon_estado"];
            }
            echo json_encode($outout);
        }
        break;
    // TODO: Eliminar registro por id
    case 'eliminar':
        $moneda->deleteMoneda("D", $_POST['mon_id'], $_POST['suc_id']);
        break;
    // TODO: Listar combo
    case 'combo':
        $datos = $moneda->getMonedaPorSucursal("R", $_POST['suc_id']);
        if (is_array($datos) == true and count($datos) > 0) {
            $html = "";
            $html .= '<option selected>Seleccionar</option>';
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['mon_id'] . "'>" . $row['mon_nombre'] . "</option>";
            }
            echo $html;
        }
        break;    
}
