<?php
// TODO: Llamando clases
require_once("../config/conexion.php");
require_once("../models/Empresa.php");
// TODO: Inicializando clases
$empresa = new Empresa();

switch ($_GET['op']) {
        // TODO: Guardar y editar registro
    case 'guardar':
        if (empty($_POST["emp_id"])) {
            $empresa->insertEmpresa("C", $_POST['com_id'], $_POST['emp_nombre'], $_POST['emp_ruc']);
        } else {
            $empresa->updateEmpresa("U", $_POST['emp_id'], $_POST['com_id'], $_POST['emp_nombre'], $_POST['emp_ruc']);
        }
        break;
        // TODO: Listado de registro en format JSON para Datatable JS
    case 'listar':
        $datos = $empresa->getEmpresaPorCompania("R", $_POST['com_id']);
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row['emp_id'];
            $sub_array[] = $row['emp_nombre'];
            $sub_array[] = $row['emp_ruc'];
            $sub_array[] = $row['emp_fecha_crea'];
            $sub_array[] = $row['emp_estado'] === '1' ? "Activo" : "Inactivo";
            $sub_array[] = '<button type="button" onClick="editar(' . $row['emp_id'] . ')" id="' . $row['emp_id'] . '" class="btn btn-success btn-icon waves-effect waves-light"><i class="ri-edit-2-line"></i></button>';
            $sub_array[] = '<button type="button" onClick="eliminar(' . $row['emp_id'] . ')" id="' . $row['emp_id'] . '" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>';
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
        $datos = $empresa->getEmpresaPorId("R", $_POST['emp_id'], $_POST['com_id']);
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $outout["emp_id"] = $row["emp_id"];
                $outout["emp_nombre"] = $row["emp_nombre"];
                $outout["emp_ruc"] = $row["emp_ruc"];
                $outout["emp_fecha_crea"] = $row["emp_fecha_crea"];
                $outout["emp_estado"] = $row["emp_estado"];
            }
            echo json_encode($outout);
        }
        break;
    // TODO: Eliminar registro por id
    case 'eliminar':
        $empresa->deleteEmpresa("D", $_POST['emp_id'], $_POST['com_id']);
        break;
    // TODO: Listar combo
    case 'combo':
        $datos = $empresa->getEmpresaPorCompania("R", $_POST['com_id']);
        if(is_array($datos)== true and count($datos)>0){
            $html="";
            $html.='<option selected>Seleccionar</option>';
            foreach($datos as $row){
                $html.= "<option value='".$row['emp_id']."'>".$row['emp_nombre']."</option>";
            }
            echo $html;
        }
        break;

}
