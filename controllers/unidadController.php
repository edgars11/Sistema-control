<?php
// TODO: Llamando clases
require_once("../config/conexion.php");
require_once("../models/Unidad.php");
// TODO: Inicializando clases
$unidad = new Unidad();

switch ($_GET['op']) {
        // TODO: Guardar y editar registro
    case 'guardar':
        if (empty($_POST["unm_id"])) {
            $unidad->insertUnidad("C", $_POST['suc_id'], $_POST['unm_nombre']);
        } else {
            $unidad->updateUnidad("U", $_POST['suc_id'], $_POST['unm_nombre'], $_POST['unm_id']);
        }
        break;
        // TODO: Listado de registro en format JSON para Datatable JS
    case 'listar':
        $datos = $unidad->getUnidadPorSucursal("R", $_POST['suc_id']);
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row['unm_id'];
            $sub_array[] = $row['unm_nombre'];
            $sub_array[] = $row['unm_fecha_crea'];
            $sub_array[] = $row['unm_estado'] === '1' ? "Activo" : "Inactivo";
            $sub_array[] = '<button type="button" onClick="editar(' . $row['unm_id'] . ')" id="' . $row['unm_id'] . '" class="btn btn-success btn-icon waves-effect waves-light"><i class="ri-edit-2-line"></i></button>';
            $sub_array[] = '<button type="button" onClick="eliminar(' . $row['unm_id'] . ')" id="' . $row['unm_id'] . '" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>';
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
        $datos = $unidad->getUnidadPorId("R", $_POST['unm_id']);
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $outout["unm_id"] = $row["unm_id"];
                $outout["suc_id"] = $row["suc_id"];
                $outout["unm_nombre"] = $row["unm_nombre"];
                $outout["unm_fecha_crea"] = $row["unm_fecha_crea"];
                $outout["unm_estado"] = $row["unm_estado"];
            }
            echo json_encode($outout);
        }
        break;
        // TODO: Eliminar registro por id
    case 'eliminar':
        $unidad->deleteUnidad("D", $_POST['unm_id'], $_POST['suc_id']);
        break;
        // TODO: Listar combo
    case 'combo':
        $datos = $unidad->getUnidadPorSucursal("R", $_POST['suc_id']);
        if (is_array($datos) == true and count($datos) > 0) {
            $html = "";
            $html .= '<option selected>Seleccionar</option>';
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['unm_id'] . "'>" . $row['unm_nombre'] . "</option>";
            }
            echo $html;
        }
        break;
}
