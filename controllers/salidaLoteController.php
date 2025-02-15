<?php
// TODO: Llamando clases
require_once("../config/conexion.php");
require_once("../models/SalidaLote.php");
// TODO: Inicializando clases
$salidalote = new SalidaLote();

switch ($_GET['op']) {
        // TODO: Guardar y editar registro
    case 'guardar':
        $usu_id = $_SESSION["usu_id"];
        if (empty($_POST["salida_id"])) {
            $salidalote->insertarSalidaLote($_POST['lote_idIng'], $_POST['sal_fecha'], $_POST['sal_cantidad'], $_POST['sal_peso'], $_POST['sal_tara'], $_POST['sal_peso_neto'], $_POST['sal_precio'], $_POST['sal_total'], $_POST['sal_tipo'], $_POST['cli_id'], $usu_id, $_POST['suc_id']);
        } else {
            $salidalote->updateLote($_POST['lote_id'], $_POST['sal_fecha'], $_POST['sal_cantidad'], $_POST['sal_peso'], $_POST['sal_tara'], $_POST['sal_peso_neto'], $_POST['sal_precio'], $_POST['sal_total'], $_POST['sal_tipo'], $_POST['cli_id'], $usu_id, $_POST['salida_id'], $_POST['suc_id']);
        }
        break;
        // TODO: Listado de registro en format JSON para Datatable JS
    case 'listar':
        $usu_id = $_SESSION["usu_id"];
        $datos = $salidalote->getSalidaLotePorSucursal($_POST['salida_fecha'], $_POST['suc_id'], $usu_id);
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = '<span class="fw-medium link-primary">' . $row['cli_nombre'] . '</span>';
            $sub_array[] = $row['lote_descripcion'];
            $sub_array[] = $row['salida_tipo'] === 'PV' ? '<span class="badge badge-soft-success text-uppercase fs-12">POLLO VIVO</span>' : '<span class="badge badge-soft-danger text-uppercase fs-12">POLLO FAENADO</span>';
            $sub_array[] = '<span style="font-weight: 600;"># ' . $row['salida_cantidad'] . '</span>';
            $sub_array[] = $row['salida_peso'];
            $sub_array[] = $row['salida_tara'];
            $sub_array[] = '<span style="font-weight: 600;">' . $row['salida_peso_neto'] . ' lbs</span>';
            $sub_array[] = $row['salida_precio'];
            $sub_array[] = '<span class="badge badge-soft-success text-uppercase fs-14">' . "$ " . $row['salida_total'] . '</span>';
            $sub_array[] = $row['salida_fecha'];
            $sub_array[] = '<ul class="list-inline hstack gap-2 mb-0">
                                    <li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title=""
                                        data-bs-original-title="Editar">
                                        <button type="button" onClick="editar(' . $row['salida_id'] . ')" id="' . $row['salida_id'] . '" class="btn btn-success btn-icon waves-effect waves-light"><i class="ri-pencil-fill fs-16"></i></button>
                                    </li>
                                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title=""
                                        data-bs-original-title="Eliminar">
                                        <button type="button" onClick="eliminar(' . $row['salida_id'] . ')" id="' . $row['salida_id'] . '" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>
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
        $datos = $salidalote->getLotePorId("R", $_POST['lote_id']);
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
        $salidalote->deleteLote("D", $_POST['lote_id'], $_POST['suc_id']);
        break;
        // TODO: Listar combo
    case 'listadoSalida':
        $lote_id = $_POST['lote_id'] == '' ? null : $_POST['lote_id'];
        $cli_id = $_POST['cli_id'] == '' ? null : $_POST['cli_id'];
        $salida_tipo = $_POST['salida_tipo'] == '' ? null : $_POST['salida_tipo'];

        $datos = $salidalote->getlistadoSalida('L', $lote_id, $cli_id, $salida_tipo, $_POST['fecha_desde'], $_POST['fecha_hasta'], $_POST['suc_id']);
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = '<span class="fw-medium link-primary">' . $row['lote_descripcion'] . '</span>';
            $sub_array[] = $row['cli_nombre'];
            $sub_array[] = $row['salida_tipo'] === 'PV' ? '<span class="badge badge-soft-warning text-uppercase fs-12">POLLO VIVO</span>' : '<span class="badge badge-soft-danger text-uppercase fs-12">POLLO FAENADO</span>';
            $sub_array[] = $row['salida_fecha'];
            $sub_array[] = '<div class="badge fw-medium badge-soft-secondary fs-14">' . $row['salida_cantidad'] . '</div>';
            $sub_array[] = $row['salida_peso_neto'] . " Lbs";
            $sub_array[] = "$ " . $row['salida_precio'];
            $sub_array[] = '<span class="badge badge-soft-success text-uppercase fs-14">' . "$ " . $row['salida_total'] . '</span>';
            $sub_array[] = $row['usu_nombre'];
            $sub_array[] = $row['salida_hora'];
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

    case 'totales':
        $lote_id = $_POST['lote_id'] == '' ? null : $_POST['lote_id'];
        $cli_id = $_POST['cli_id'] == '' ? null : $_POST['cli_id'];
        $salida_tipo = $_POST['salida_tipo'] == '' ? null : $_POST['salida_tipo'];

        $datos = $salidalote->getlistadoSalida('T', $lote_id, $cli_id, $salida_tipo, $_POST['fecha_desde'], $_POST['fecha_hasta'], $_POST['suc_id']);
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $outout["cantidad"] = $row["cantidad"];
                $outout["peso_neto"] = $row["peso_neto"];
                $outout["total"] = $row["total"];
            }
            echo json_encode($outout);
        }
        break;
    case 'deleteout':
        $datos =  $salidalote->deleteSalida($_POST['salida_id']);
        $outout["exec"] = $datos;

        echo json_encode($outout);
        break;

    case 'mostrarByID':
        $datos = $salidalote->getSalidaById($_POST['salida_id']);
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $outout["cli_nombre"] = $row["cli_nombre"];
                $outout["cli_ruc"] = $row["cli_ruc"];
                $outout["cli_telefono"] = $row["cli_telefono"];
                $outout["cta_monto"] = $row["cta_monto"];
                $outout["lote_descripcion"] = $row["lote_descripcion"];
                $outout["salida_tipo"] = $row["salida_tipo"];
                $outout["salida_cantidad"] = $row["salida_cantidad"];
                $outout["salida_peso"] = $row["salida_peso"];
                $outout["salida_tara"] = $row["salida_tara"];
                $outout["salida_peso_neto"] = $row["salida_peso_neto"];
                $outout["salida_precio"] = $row["salida_precio"];
                $outout["salida_total"] = $row["salida_total"];
                $outout["salida_fecha"] = $row["salida_fecha"];
                $outout["salida_id"] = $row["salida_id"];
                $outout["cli_id"] = $row["cli_id"];
                $outout["lote_id"] = $row["lote_id"];
                $outout["lote_cant_actual"] = $row["lote_cant_actual"];
            }
            echo json_encode($outout);
        }
        break;
}
