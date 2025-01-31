<?php
// TODO: Llamando clases
require_once("../config/conexion.php");
require_once("../models/Proveedor.php");
// TODO: Inicializando clases
$proveedor = new Proveedor();

switch ($_GET['op']) {
        // TODO: Guardar y editar registro
    case 'guardar':
        if (empty($_POST["prov_id"])) {
            $proveedor->insertProveedor("C", $_POST['emp_id'], $_POST['prov_nombre'], $_POST['prov_ruc'], $_POST['prov_telefono'], $_POST['prov_direccion'], $_POST['prov_correo']);
        } else {
            $proveedor->updateProveedor("U", $_POST['emp_id'], $_POST['prov_nombre'], $_POST['prov_ruc'], $_POST['prov_telefono'], $_POST['prov_direccion'], $_POST['prov_correo'], $_POST['prov_id']);
        }
        break;
        // TODO: Listado de registro en format JSON para Datatable JS
    case 'listar':
        $datos = $proveedor->getProveedorPorEmpresa("R", $_POST['emp_id']);
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row['prov_id'];
            $sub_array[] = $row['prov_nombre'];
            $sub_array[] = $row['prov_ruc'];
            $sub_array[] = $row['prov_telefono'];
            $sub_array[] = $row['prov_direccion'];
            $sub_array[] = $row['prov_correo'];
            $sub_array[] = $row['prov_fecha_crea'];
            $sub_array[] = $row['prov_estado'] === '1' ? "Activo" : "Inactivo";
            $sub_array[] = '<button type="button" onClick="editar(' . $row['prov_id'] . ')" id="' . $row['prov_id'] . '" class="btn btn-success btn-icon waves-effect waves-light"><i class="ri-edit-2-line"></i></button>';
            $sub_array[] = '<button type="button" onClick="eliminar(' . $row['prov_id'] . ')" id="' . $row['prov_id'] . '" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>';
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
        $datos = $proveedor->getProveedorPorId("R", $_POST['prov_id']);
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $outout["prov_id"] = $row["prov_id"];
                $outout["emp_id"] = $row["emp_id"];
                $outout["prov_nombre"] = $row["prov_nombre"];
                $outout["prov_ruc"] = $row["prov_ruc"];
                $outout["prov_telefono"] = $row["prov_telefono"];
                $outout["prov_direccion"] = $row["prov_direccion"];
                $outout["prov_correo"] = $row["prov_correo"];
                $outout["prov_fecha_crea"] = $row["prov_fecha_crea"];
                $outout["prov_estado"] = $row["prov_estado"];
            }
            echo json_encode($outout);
        }
        break;
        // TODO: Mostrar información del registro por identificacion
    case 'ruc':
        $dato = $proveedor->getProveedorPorRuc("R", $_POST['prov_ruc'], $_POST['emp_id']);
        if (is_array($dato) == true and count($dato) > 0) {
            foreach ($datos as $row) {
                $outout["prov_id"] = $row["prov_id"];
                $outout["emp_id"] = $row["emp_id"];
                $outout["prov_nombre"] = $row["prov_nombre"];
                $outout["prov_ruc"] = $row["prov_ruc"];
                $outout["prov_fecha_crea"] = $row["prov_fecha_crea"];
                $outout["prov_estado"] = $row["prov_estado"];
            }
            echo json_encode($outout);
        }
        break;
        // TODO: Mostrar información del registro por Nombre
    case 'Nombre':
        $dato = $proveedor->getProveedorPorNombre("R", $_POST['prov_nombre'], $_POST['emp_id']);
        if (is_array($dato) == true and count($dato) > 0) {
            foreach ($datos as $row) {
                $outout["prov_id"] = $row["prov_id"];
                $outout["emp_id"] = $row["emp_id"];
                $outout["prov_nombre"] = $row["prov_nombre"];
                $outout["prov_ruc"] = $row["prov_ruc"];
                $outout["prov_fecha_crea"] = $row["prov_fecha_crea"];
                $outout["prov_estado"] = $row["prov_estado"];
            }
            echo json_encode($outout);
        }
        break;
        // TODO: Eliminar registro por id
    case 'eliminar':
        $proveedor->deleteProveedor("D", $_POST['prov_id'], $_POST['emp_id']);
        break;
        // TODO: Listar combo
    case 'combo':
        $datos = $proveedor->getProveedorPorEmpresa("R", $_POST['emp_id']);
        if (is_array($datos) == true and count($datos) > 0) {
            $html = "";
            $html = '<option value="">Seleccione un proveedor</option>';
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['prov_id'] . "'>" . $row['prov_nombre'] . "</option>";
            }
            echo $html;
        }
        break;
}
