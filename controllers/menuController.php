<?php
// TODO: Llamando clases
require_once("../config/conexion.php");
require_once("../models/Menu.php");
// TODO: Inicializando clases
$menu = new Menu();

switch ($_GET['op']) {
        // TODO: Actualizar registro
    case 'update':
        $menu->updateMenuPorRol("H", $_POST['rol_id'], $_POST['mend_id'], $_POST['menu_permi']);
        break;
        // TODO: Listado de registro en format JSON para Datatable JS
    case 'listar':
        $menu->validaInsertMenuPorRol($_POST['rol_id']);
        $datos = $menu->getMenuPorRol("M", $_POST['rol_id']);
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row['men_nombre'];
            if ($row['mend_permiso'] == 'S') {
                $sub_array[] = '<button type="button" onClick="deshabilitar(' . $row['mend_id'] . ')" id="' . $row['mend_id'] . '" class="btn btn-success btn-label waves-effect waves-light"><i class="ri-check-double-line label-icon align-middle fs-16 me-2 btn-sm"></i>SI</button>';
            } else {
                $sub_array[] = '<button type="button" onClick="habilitar(' . $row['mend_id'] . ')" id="' . $row['mend_id'] . '" class="btn btn-danger btn-label waves-effect waves-light"><i class=" ri-close-circle-line label-icon align-middle fs-16 me-2 btn-sm"></i>NO</button>';
            }

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
}
