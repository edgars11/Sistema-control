<?php
// TODO: Llamando clases
require_once("../config/conexion.php");
require_once("../models/MovimientoLote.php");
// TODO: Inicializando clases
$movimientolote = new MovimientoLote();

switch ($_GET['op']) {
        // TODO: Guardar y editar registro
    case 'guardar':
        if (empty($_POST["mov_id"])) {
            $usu_id = $_SESSION["usu_id"];
            $mov_tipo = $_POST['mov_tipo'] == 'sum' ? '+' : '-';
            $movimientolote->insertarMovimientoLote($_POST['lote_idIng'], $_POST['mov_cant_ing'], $mov_tipo, $_POST['mov_motivo'], $usu_id, $_POST['suc_id'],$_POST['mov_fecha_ing']);
        } else {
            $movimientolote->updateLote("U", $_POST['suc_id'], $_POST['lote_descripcion'], $_POST['lote_capacidad_max'], $_POST['lote_id']);
        }
        break;
        // TODO: Listado de registro en format JSON para Datatable JS
    case 'listar':
        $datos = $movimientolote->getMovimientoLotePorSucursal($_POST['mov_fecha'],$_POST['mov_hasta'], $_POST['suc_id']);
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = '<span class="fw-medium link-primary">'.$row['lote_descripcion'].'</span>';
            $sub_array[] = $row['mov_fecha'];
            $sub_array[] = $row['mov_cantidad'];
            $sub_array[] = $row['mov_tipo'] === '+' ? '<span class="badge badge-soft-success text-uppercase fs-12">INGRESO</span>' : '<span class="badge badge-soft-danger text-uppercase fs-12">PÉRDIDA</span>';
            $sub_array[] = $row['usu_nom'];
            $sub_array[] = $row['mov_motivo'];
            $sub_array[] = '<ul class="list-inline hstack gap-2 mb-0">
                                    <li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title=""
                                        data-bs-original-title="Editar">
                                        <button type="button" onClick="editar(' . $row['mov_id'] . ')" id="' . $row['mov_id'] . '" class="btn btn-success btn-icon waves-effect waves-light"><i class="ri-pencil-fill fs-16"></i></button>
                                    </li>
                                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title=""
                                        data-bs-original-title="Eliminar">
                                        <button type="button" onClick="eliminar(' . $row['mov_id'] . ')" id="' . $row['mov_id'] . '" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>
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
        $datos = $movimientolote->getLotePorId("R", $_POST['lote_id']);
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $outout["lote_id"] = $row["lote_id"];
                $outout["suc_id"] = $row["suc_id"];
                $outout["lote_descripcion"] = $row["lote_descripcion"];
                $outout["lote_capacidad_max"] = $row["lote_capacidad_max"];
                $outout["lote_cant_actual"] = $row["lote_cant_actual"];
                $outout["lote_fecha_upd"] = $row["lote_fecha_upd"];
                $outout["lote_estado"] = $row["lote_estado"];
            }
            echo json_encode($outout);
        }
        break;
        // TODO: Eliminar registro por id
    case 'eliminar':
        $movimientolote->deleteLote("D", $_POST['lote_id'], $_POST['suc_id']);
        break;
        // TODO: Listar combo

}
