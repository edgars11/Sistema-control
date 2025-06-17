<?php
// TODO: Llamando clases
require_once("../config/conexion.php");
require_once("../models/Cliente.php");
// TODO: Inicializando clases
$cliente = new Cliente();

switch ($_GET['op']) {
    // TODO: Guardar y editar registro
    case 'guardar':
        if (empty($_POST["cli_id"])) {
            $cliente->insertCliente("C", $_POST['emp_id'], $_POST['cli_nombre'], $_POST['cli_ruc'], $_POST['cli_telefono'], $_POST['cli_direccion'], $_POST['cli_correo']);
        } else {
            $cliente->updateCliente("U", $_POST['emp_id'], $_POST['cli_nombre'], $_POST['cli_ruc'], $_POST['cli_telefono'], $_POST['cli_direccion'], $_POST['cli_correo'], $_POST['cli_id']);
        }
        break;
    // TODO: Listado de registro en format JSON para Datatable JS
    case 'listar':
        $datos = $cliente->getClientePorEmpresa("R", $_POST['emp_id']);
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row['cli_id'];
            $sub_array[] = $row['cli_nombre'];
            $sub_array[] = $row['cli_ruc'];
            $sub_array[] = $row['cli_telefono'];
            $sub_array[] = $row['cli_direccion'];
            $sub_array[] = $row['cli_correo'];
            $sub_array[] = $row['cli_fecha_crea'];
            $sub_array[] = $row['cli_estado'] === '1' ? "Activo" : "Inactivo";
            $sub_array[] = '<button type="button" onClick="editar(' . $row['cli_id'] . ')" id="' . $row['cli_id'] . '" class="btn btn-success btn-icon waves-effect waves-light"><i class="ri-edit-2-line"></i></button>';
            $sub_array[] = '<button type="button" onClick="eliminar(' . $row['cli_id'] . ')" id="' . $row['cli_id'] . '" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>';
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
        $datos = $cliente->getClientePorId($_POST['cli_id']);
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $outout["cli_id"] = $row["cli_id"];
                $outout["emp_id"] = $row["emp_id"];
                $outout["cli_nombre"] = $row["cli_nombre"];
                $outout["cli_ruc"] = $row["cli_ruc"];
                $outout["cli_telefono"] = $row["cli_telefono"];
                $outout["cli_direccion"] = $row["cli_direccion"];
                $outout["cli_correo"] = $row["cli_correo"];
                $outout["cli_fecha_crea"] = $row["cli_fecha_crea"];
                $outout["cli_estado"] = $row["cli_estado"];
            }
            echo json_encode($outout);
        }
        break;
    // TODO: Mostrar información del registro por identificacion
    case 'byCED':
        $datos = $cliente->getClientePorRuc("R", $_POST['cli_ruc'], $_POST['emp_id']);
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $outout["cli_id"] = $row["cli_id"];
                $outout["emp_id"] = $row["emp_id"];
                $outout["cli_nombre"] = $row["cli_nombre"];
                $outout["cli_ruc"] = $row["cli_ruc"];
                $outout["cli_telefono"] = $row["cli_telefono"];
                $outout["cli_direccion"] = $row["cli_direccion"];
                $outout["cli_correo"] = $row["cli_correo"];
                $outout["cli_fecha_crea"] = $row["cli_fecha_crea"];
                $outout["cli_estado"] = $row["cli_estado"];
            }
            echo json_encode($outout);
        }
        break;
    // TODO: Eliminar registro por id
    case 'eliminar':
        $cliente->deleteCliente("D", $_POST['cli_id'], $_POST['emp_id']);
        break;
    /* TODO: Listar combo */
    case 'combo':
        $datos = $cliente->getClientePorEmpresa("R", $_POST['emp_id']);
        if (is_array($datos) == true and count($datos) > 0) {
            $html = "";
            $html .= '<option selected>Seleccionar</option>';
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['cli_id'] . "'>" . $row['cli_nombre'] . "</option>";
            }
            echo $html;
        }
        break;
    case 'listado':
        $datos = $cliente->getClientePorEmpresa("R", $_POST['emp_id']);
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = '<button type="button" onClick="selectCliente(' . $row['cli_id'] . ')" id="' . $row['cli_id'] . '" class="btn btn-success btn-icon waves-effect waves-light"><i class="ri-edit-2-line"></i></button>';
            $sub_array[] = $row['cli_nombre'];
            $sub_array[] = $row['cli_ruc'];
            $sub_array[] = $row['cli_direccion'];
            $sub_array[] = $row['cli_telefono'];
            $sub_array[] = $row['cli_correo'];
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

    case 'byID':
        $datos = $cliente->getClientePorId($_POST['cli_id']);
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $outout["cli_id"] = $row["cli_id"];
                $outout["cli_nombre"] = $row["cli_nombre"];
                $outout["cli_ruc"] = $row["cli_ruc"];
                $outout["cli_telefono"] = $row["cli_telefono"];
                $outout["cli_direccion"] = $row["cli_direccion"];
                $outout["cli_correo"] = $row["cli_correo"];
                $outout["cli_fecha_crea"] = $row["cli_fecha_crea"];
                $outout["cli_estado"] = $row["cli_estado"];
                $outout["cta_monto"] = $row["cta_monto"];
            }
            echo json_encode($outout);
        }
        break;

    // TODO: Listado de registro en format JSON para Datatable JS
    case 'listarCSC':
        $datos = $cliente->getClienteSinCuenta( $_POST['emp_id']);
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row['cli_nombre'];
            $sub_array[] = $row['cli_ruc'];
            $sub_array[] = $row['cli_direccion'];
            $sub_array[] = '<button type="button" onClick="seleccionarCliente(' . $row['cli_id'] . ')" id="' . $row['cli_id'] . '" class="btn btn-success btn-icon waves-effect waves-light"><i class=" ri-checkbox-circle-fill"></i></button>';
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
