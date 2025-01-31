<?php
// TODO: Llamando clases
require_once("../config/conexion.php");
require_once("../models/Sucursal.php");
// TODO: Inicializando clases
$sucursal = new Sucursal();

switch ($_GET['op']) {
    // TODO: Guardar y editar registro
    case 'guardar':
        if (empty($_POST["suc_id"])) {
            $sucursal->insertSucursal("C", $_POST['emp_id'], $_POST['suc_nombre']);
        } else {
            $sucursal->updateSucursal("U", $_POST['emp_id'], $_POST['suc_nombre'], $_POST['suc_id']);
        }
        break;
    // TODO: Listado de registro en format JSON para Datatable JS
    case 'listar':
        $datos = $sucursal->getSucursalPorEmpresa("R", $_POST['emp_id']);
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row['suc_id'];
            $sub_array[] = $row['suc_nombre'];
            $sub_array[] = $row['suc_fecha_crea'];
            $sub_array[] = $row['suc_estado'] === '1' ? "Activo" : "Inactivo";
            $sub_array[] = '<button type="button" onClick="editar(' . $row['suc_id'] . ')" id="' . $row['suc_id'] . '" class="btn btn-success btn-icon waves-effect waves-light"><i class="ri-edit-2-line"></i></button>';
            $sub_array[] = '<button type="button" onClick="eliminar(' . $row['suc_id'] . ')" id="' . $row['suc_id'] . '" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>';
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
        $datos = $sucursal->getSucursalPorId("R", $_POST['suc_id']);
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $outout["suc_id"] = $row["suc_id"];
                $outout["emp_id"] = $row["emp_id"];
                $outout["suc_nombre"] = $row["suc_nombre"];
                $outout["suc_fecha_crea"] = $row["suc_fecha_crea"];
                $outout["suc_estado"] = $row["suc_estado"];
            }
            echo json_encode($outout);
        }
        break;
    // TODO: Eliminar registro por id
    case 'eliminar':
        $sucursal->deleteSucursal("D", $_POST['suc_id'], $_POST['emp_id']);
        break;
    // TODO: Listar combo
    case 'combo':
        $datos = $sucursal->getSucursalPorEmpresa("R", $_POST['emp_id']);
        if(is_array($datos)== true and count($datos)>0){
            $html="";
            $html.='<option selected>Seleccionar</option>';
            foreach($datos as $row){
                $html.= "<option value='".$row['suc_id']."'>".$row['suc_nombre']."</option>";
            }
            echo $html;
        }
        break;    
}
