<?php
// TODO: Llamando clases
require_once("../config/conexion.php");
require_once("../models/Lote.php");
// TODO: Inicializando clases
$lote = new Lote();

switch ($_GET['op']) {
        // TODO: Guardar y editar registro
    case 'guardar':
        if (empty($_POST["lote_id"])) {
            $lote->insertarLote("C", $_POST['suc_id'], $_POST['lote_descripcion'], $_POST['lote_capacidad_max']);
        } else {
            $lote->updateLote("U", $_POST['suc_id'], $_POST['lote_descripcion'], $_POST['lote_capacidad_max'], $_POST['lote_id']);
        }
        break;
        // TODO: Listado de registro en format JSON para Datatable JS
    case 'listar':
        $datos = $lote->getLotePorSucursal($_POST['suc_id']);
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row['lote_descripcion'];
            $sub_array[] = $row['lote_capacidad_max'];
            $sub_array[] = $row['lote_cant_actual'];
            $sub_array[] = $row['lote_cant_perdida'];
            $sub_array[] = $row['lote_fecha_upd'];
            $sub_array[] = $row['lote_estado'] === '1' ? '<span class="badge badge-soft-success text-uppercase fs-12">Activo</span>' : '<span class="badge badge-soft-danger text-uppercase fs-12">Inactivo</span>';
            $sub_array[] = '<button type="button" onClick="editar(' . $row['lote_id'] . ')" id="' . $row['lote_id'] . '" class="btn btn-warning btn-icon waves-effect waves-light"><i class="ri-file-list-3-line"></i></button>';
            $sub_array[] = '<ul class="list-inline hstack gap-2 mb-0">
                                <li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title=""
                                    data-bs-original-title="Editar">
                                    <button type="button" onClick="editar(' . $row['lote_id'] . ')" id="' . $row['lote_id'] . '" class="btn btn-success btn-icon waves-effect waves-light"><i class="ri-pencil-fill fs-16"></i></button>
                                </li>
                                <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title=""
                                    data-bs-original-title="Eliminar">
                                    <button type="button" onClick="eliminar(' . $row['lote_id'] . ')" id="' . $row['lote_id'] . '" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>
                                </li>
                            </ul>';
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
        $datos = $lote->getLotePorId("R", $_POST['lote_id']);
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $outout["lote_id"] = $row["lote_id"];
                $outout["suc_id"] = $row["suc_id"];
                $outout["lote_descripcion"] = $row["lote_descripcion"];
                $outout["lote_capacidad_max"] = $row["lote_capacidad_max"];
                $outout["lote_cant_actual"] = $row["lote_cant_actual"];
                $outout["lote_fecha_upd"] = $row["lote_fecha_upd"];
                $outout["lote_estado"] = $row["lote_estado"];
                $outout["lote_consumo"] = $row["lote_consumo"];
            }
            echo json_encode($outout);
        }
        break;
        // TODO: Eliminar registro por id
    case 'eliminar':
        $lote->deleteLote("D", $_POST['lote_id'], $_POST['suc_id']);
        break;
        // TODO: Listar combo
    case 'combo':
        $datos = $lote->getLotePorSucursal($_POST['suc_id']);
        if (is_array($datos) == true and count($datos) > 0) {
            $html = "";
            $html .= '<option selected>Seleccionar</option>';
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['lote_id'] . "'>" . $row['lote_descripcion'] . "</option>";
            }
            echo $html;
        }
        break;
}
