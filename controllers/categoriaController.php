<?php
// TODO: Llamando clases
require_once("../config/conexion.php");
require_once("../models/Categoria.php");
// TODO: Inicializando clases
$categoria = new Categoria();

switch ($_GET['op']) {
    // TODO: Guardar y editar registro
    case 'guardar':
        if (empty($_POST["cat_id"])) {
            $categoria->insertCategoria("C", $_POST['suc_id'], $_POST['cat_nombre']);
        } else {
            $categoria->updateCategoria("U", $_POST['suc_id'], $_POST['cat_nombre'], $_POST['cat_id']);
        }
        break;
    // TODO: Listado de registro en format JSON para Datatable JS
    case 'listar':
        $datos = $categoria->getCategoriaPorSucursal("R", $_POST['suc_id']);
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row['cat_id'];
            $sub_array[] = $row['cat_nombre'];
            $sub_array[] = $row['cat_fecha_crea'];
            $sub_array[] = $row['cat_estado'] === '1' ? "Activo" : "Inactivo";
            $sub_array[] = '<button type="button" onClick="editar(' . $row['cat_id'] . ')" id="' . $row['cat_id'] . '" class="btn btn-success btn-icon waves-effect waves-light"><i class="ri-edit-2-line"></i></button>';
            $sub_array[] = '<button type="button" onClick="eliminar(' . $row['cat_id'] . ')" id="' . $row['cat_id'] . '" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>';
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
        $datos = $categoria->getCategoriaPorId("R", $_POST['cat_id']);
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $outout["cat_id"] = $row["cat_id"];
                $outout["suc_id"] = $row["suc_id"];
                $outout["cat_nombre"] = $row["cat_nombre"];
                $outout["cat_fecha_crea"] = $row["cat_fecha_crea"];
                $outout["cat_estado"] = $row["cat_estado"];
            }
            echo json_encode($outout);
        }
        break;
        // TODO: Eliminar registro por id
    case 'eliminar':
        $categoria->deleteCategoria("D", $_POST['cat_id'], $_POST['suc_id']);
        break;
    // TODO: Listar combo
    case 'combo':
        $datos = $categoria->getCategoriaPorSucursal("R", $_POST['suc_id']);
        if (is_array($datos) == true and count($datos) > 0) {
            $html = "";
            $html .= '<option selected>Seleccionar</option>';
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['cat_id'] . "'>" . $row['cat_nombre'] . "</option>";
            }
            echo $html;
        }
        break;
}
