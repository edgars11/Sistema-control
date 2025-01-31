<?php
// TODO: Llamando clases
require_once("../config/conexion.php");
require_once("../models/Usuario.php");
// TODO: Inicializando clases
$usuario = new Usuario();

switch ($_GET['op']) {
        // TODO: Guardar y editar registro
    case 'guardar':
        if (empty($_POST["usu_id"])) {
            $usuario->insertUsuario(
                "C",
                $_POST['usu_nombre'],
                $_POST['usu_apellido'],
                $_POST['usu_correo'],
                $_POST['usu_dni'],
                $_POST['usu_telefono'],
                $_POST['usu_password'],
                $_POST['suc_id'],
                $_POST['rol_id']
            );
        } else {
            $usuario->updateUsuario(
                "U",
                $_POST['usu_id'],
                $_POST['usu_nombre'],
                $_POST['usu_apellido'],
                $_POST['usu_correo'],
                $_POST['usu_dni'],
                $_POST['usu_telefono'],
                $_POST['usu_password'],
                $_POST['suc_id'],
                $_POST['rol_id']
            );
        }
        break;
        // TODO: Listado de registro en format JSON para Datatable JS
    case 'listar':
        $datos = $usuario->getUsuarioPorSucursal("R", $_POST['suc_id']);
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row['usu_correo'];
            $sub_array[] = $row['usu_nombre'];
            $sub_array[] = $row['usu_apellido'];
            $sub_array[] = $row['usu_dni'];
            $sub_array[] = $row['usu_telefono'];
            $sub_array[] = $row['usu_password'];
            $sub_array[] = $row['rol_nombre'];
            $sub_array[] = $row['usu_fecha_crea'];
            $sub_array[] = $row['usu_estado'] === '1' ? "Activo" : "Inactivo";
            $sub_array[] = '<button type="button" onClick="editar(' . $row['usu_id'] . ')" id="' . $row['usu_id'] . '" class="btn btn-success btn-icon waves-effect waves-light"><i class="ri-edit-2-line"></i></button>';
            $sub_array[] = '<button type="button" onClick="eliminar(' . $row['usu_id'] . ')" id="' . $row['usu_id'] . '" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>';
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
        // TODO: Listado de registro por nombre en format JSON para Datatable JS
    case 'nombre':
        $datos = $usuario->getUsuarioPorNombre("R", $_POST['suc_nombre'], $_POST['suc_id']);
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array = $row['usu_nombre'];
            $sub_array = $row['usu_apellido'];
            $sub_array = $row['usu_correo'];
            $sub_array = $row['usu_dni'];
            $sub_array = $row['usu_telefono'];
            $sub_array = $row['usu_password'];
            $sub_array = 'Editar';
            $sub_array = 'Eliminar';
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
        // TODO: Listado de usuarios por rol
    case 'rol':
        $datos = $usuario->getUsuarioPorRol("R", $_POST['rol_id'], $_POST['suc_id']);
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array = $row['usu_nombre'];
            $sub_array = $row['usu_apellido'];
            $sub_array = $row['usu_correo'];
            $sub_array = $row['usu_dni'];
            $sub_array = $row['usu_telefono'];
            $sub_array = $row['usu_password'];
            $sub_array = 'Editar';
            $sub_array = 'Eliminar';
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
        $datos = $usuario->getUsuarioPorId("R", $_POST['usu_id'], $_POST['suc_id']);
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $outout["usu_id"] = $row["usu_id"];
                $outout["suc_id"] = $row["suc_id"];
                $outout["rol_id"] = $row["rol_id"];
                $outout["rol_nombre"] = $row["rol_nombre"];
                $outout["usu_nombre"] = $row["usu_nombre"];
                $outout["usu_apellido"] = $row["usu_apellido"];
                $outout["usu_correo"] = $row["usu_correo"];
                $outout["usu_dni"] = $row["usu_dni"];
                $outout["usu_telefono"] = $row["usu_telefono"];
                $outout["usu_password"] = $row["usu_password"];
                $outout["usu_fecha_crea"] = $row["usu_fecha_crea"];
                $outout["usu_estado"] = $row["usu_estado"];
            }
            echo json_encode($outout);
        }
        break;
        // TODO: Eliminar registro por id
    case 'eliminar':
        $usuario->deleteUsuario("D", $_POST['usu_id'], $_POST['suc_id']);
        break;
        // TODO: Eliminar registro por id
    case 'password':
        $usuario->updatePassword("D", $_POST['usu_password'], $_POST['usu_id']);
        break;
}
