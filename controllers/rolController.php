<?php
// TODO: Llamando clases
require_once("../config/conexion.php");
require_once("../models/Rol.php");
// TODO: Inicializando clases
$rol = new Rol();

switch ($_GET['op']) {
        // TODO: Guardar y editar registro
    case 'guardar':
        if (empty($_POST["rol_id"])) {
            $rol->insertRol("C", $_POST['suc_id'], $_POST['rol_nombre']);
        } else {
            $rol->updateRol("U", $_POST['suc_id'], $_POST['rol_nombre'], $_POST['rol_id']);
        }
        break;
        // TODO: Listado de registro en format JSON para Datatable JS
    case 'listar':
        $datos = $rol->getRolPorSucursal("R", $_POST['suc_id']);
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row['rol_id'];
            $sub_array[] = $row['rol_nombre'];
            $sub_array[] = $row['rol_fecha_crea'];
            $sub_array[] = $row['rol_estado'] === '1' ? "Activo" : "Inactivo";
            $sub_array[] = '<button type="button" onClick="permisos(' . $row['rol_id'] . ')" id="' . $row['rol_id'] . '" class="btn btn-primary btn-icon waves-effect waves-light"><i class="ri-settings-2-line"></i></button>';
            $sub_array[] = '<button type="button" onClick="editar(' . $row['rol_id'] . ')" id="' . $row['rol_id'] . '" class="btn btn-success btn-icon waves-effect waves-light"><i class="ri-edit-2-line"></i></button>';
            $sub_array[] = '<button type="button" onClick="eliminar(' . $row['rol_id'] . ')" id="' . $row['rol_id'] . '" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>';
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
        $dato = $rol->getRolPorId("R", $_POST['rol_id'], $_POST['suc_id']);
        if (is_array($dato) == true and count($dato) > 0) {
            foreach ($dato as $row) {
                $outout["rol_id"] = $row["rol_id"];
                $outout["suc_id"] = $row["suc_id"];
                $outout["rol_nombre"] = $row["rol_nombre"];
                $outout["rol_fecha_crea"] = $row["rol_fecha_crea"];
                $outout["rol_estado"] = $row["rol_estado"];
            }
            echo json_encode($outout);
        }
        break;
        // TODO: Eliminar registro por id
    case 'eliminar':
        $rol->deleteRol("D", $_POST['rol_id'], $_POST['suc_id']);
        break;
        // TODO: Listar combo
    case 'combo':
        $datos = $rol->getRolPorSucursal("R", $_POST['suc_id']);
        if (is_array($datos) == true and count($datos) > 0) {
            $html = "";
            $html .= '<option selected>Seleccionar</option>';
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['rol_id'] . "'>" . $row['rol_nombre'] . "</option>";
            }
            echo $html;
        }
        break;
}
