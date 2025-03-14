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
            $sub_array[] = '<span class="fw-medium link-primary">' . $row['lote_descripcion'] . '</span>';
            $sub_array[] = number_format($row['lote_capacidad_max'], 0, '', ',');
            $sub_array[] = number_format($row['lote_cant_actual'], 0, '', ',');
            $sub_array[] = number_format($row['lote_cant_perdida'], 0, '', ',');
            $sub_array[] = number_format($row['lote_consumo'], 0, '', ',');
            $sub_array[] = number_format($row['lote_cant_vendidos'], 0, '', ',');
            $sub_array[] = $row['lote_fecha_upd'];
            $sub_array[] = $row['lote_estado'] === '1' ? '<span class="badge badge-soft-success text-uppercase fs-12">Activo</span>' : '<span class="badge badge-soft-danger text-uppercase fs-12">Inactivo</span>';
            $sub_array[] = $row['ult_fecha_ingre'];
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
                $outout["lote_capacidad_max"] = number_format($row["lote_capacidad_max"], 0, '', ',');
                $outout["lote_cant_actual"] = number_format($row["lote_cant_actual"], 0, '', ',');
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
            $html .= '<option value="0" selected>Seleccionar</option>';
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['lote_id'] . "'>" . $row['lote_descripcion'] . "</option>";
            }
            echo $html;
        }
        break;

    case 'guardarConsumo':
        $usu_id = $_SESSION["usu_id"];
        $datos = $lote->ingresoAlimento($_POST['lote_idAli'], $_POST['ali_cantidad'], $_POST['ali_fecha'], $usu_id, $_POST['ali_desc']);
        $outout["success"] = true;

        echo json_encode($outout);
        break;

    case 'listarAlimento':
        $lote_id = $_POST['lote_id'] == '' ? null : $_POST['lote_id'];
        $datos = $lote->getListadoAlimento($_POST['suc_id'], $_POST['ali_tipo'], $_POST['fecha_desde'], $_POST['fecha_hasta'], $lote_id);
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = '<span class="fw-medium link-primary">' . $row['lote_descripcion'] . '</span>';
            $sub_array[] = $row['ali_fecha'];
            $sub_array[] = '<span style="font-weight: 600;"># ' . number_format($row['ali_cantidad'],0,'',',')  . '</span>';
            $sub_array[] = '<span class="badge badge-soft-success text-uppercase fs-14">' . $row['usu_nombre'] . '</span>';
            $sub_array[] = $row['ali_desc'] == '' ? 'Sin observación' : $row['ali_desc'];
            $sub_array[] = $row['ali_hora'];
            $sub_array[] = '<ul class="list-inline hstack gap-2 mb-0">
                                    <li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title=""
                                        data-bs-original-title="Editar">
                                        <button type="button" onClick="editar(' . $row['ali_id'] . ')" id="' . $row['ali_id'] . '" class="btn btn-success btn-icon waves-effect waves-light"><i class="ri-pencil-fill fs-16"></i></button>
                                    </li>
                                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title=""
                                        data-bs-original-title="Eliminar">
                                        <button type="button" onClick="eliminar(' . $row['ali_id'] . ')" id="' . $row['ali_id'] . '" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>
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
    case 'mostrarConsumoId':
        $datos = $lote->getListadoAlimentoID($_POST['ali_id']);
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $outout["ali_id"] = $row["ali_id"];
                $outout["lote_id"] = $row["lote_id"];
                $outout["ali_cantidad"] = number_format($row["ali_cantidad"],0,'',',') ;
                $outout["ali_fecha"] = $row["ali_fecha"];
                $outout["ali_desc"] = $row["ali_desc"];
                $outout["total_consumo_lote"] = $row["total_consumo_lote"];
            }
            echo json_encode($outout);
        }
        break;
    case 'eliminarConsumo':
        $dato = $lote->deleteLoteConsumo($_POST['ali_id']);
        if ($dato) {
            $outout["exec"] = true;
        } else {
            $outout["exec"] = false;
        }
        echo json_encode($outout);
        break;
    case 'updConsumo':
        $usu_id = $_SESSION["usu_id"];
        $datos = $lote->updateRegAlimento($_POST['lote_id'], $_POST['ali_cantidad'], $_POST['ali_fecha'], $usu_id, $_POST['ali_desc'], $_POST['ali_id']);
        if ($datos === 'true') {
            $outout["success"] = true;
        } else {
            $outout["success"] = false;
        }
        $outout["success"] = $datos;

        echo json_encode($outout);
        break;
}
