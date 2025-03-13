<?php
// TODO: Llamando clases
require_once("../config/conexion.php");
require_once("../models/ReporteLotePeriodo.php");
// TODO: Inicializando clases
$reporte = new ReporteLotePeriodo();

switch ($_GET['op']) {
    // TODO: Listado de registro en format JSON para Datatable JS
    case 'listadoPeriodo':
        $fechaPeriodo;
        $lote_id = $_POST['lote_id'] == '0' ? null : $_POST['lote_id'];
        $datos = $reporte->getListadoPeriodos($_POST['rep_periodo'], $lote_id, $_POST['rep_anio'], $_POST['suc_id']);
        $data = array();
        $search  = '-';
        $replace = '.';
        $count = 1;
        // $subject = '2021-40-30';
        //$subject = str_replace($search, $replace, $fechaPeriodo);
        foreach ($datos as $row) {
            $fechaPeriodo = $row['periodo'];
            $sub_array = array();
            $sub_array[] = $row['lote_descripcion'];
            $sub_array[] = $row['periodo'];
            $sub_array[] = '<button type="button" onClick="seleccionar(' . $row['lote_id'] . ', ' . $count . ')" fecha="' . $fechaPeriodo . '" class="btn btn-success w-100" id="BtnPeriodo' . $count . '"><i class="ri-checkbox-circle-line me-2 align-bottom"></i>Seleccionar</button>';
            // $sub_array[] = '<button type="button" onClick="seleccionar(' . $row['periodo'] . ', '.$row['lote_id'].')" id="' . $row['lote_id'] . '" class="btn btn-success btn-icon waves-effect waves-light"><i class="ri-edit-2-line"> Seleccionar</i></button>';
            $data[] = $sub_array;
            $count++;
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
    case 'periodosLote':
        $datos = $reporte->getFechasPeriodoLote($_POST['lote_id'], $_POST['rep_fecha']);
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $outout["rep_fecha_fin"] = $row["fecha_ini"];
                $outout["fecha_fin"] = $row["fecha_fin"];
            }
            echo json_encode($outout);
        }
        break;
    // TODO: Genera el listado en la tabla tm_reporte_lote y obbtiene el listado generado
    case 'reporteListado':
        $datos = $reporte->getListadoReporte($_POST['lote_id'], $_POST['fecha_periodo'], $_POST['fecha_fin'], $_POST['suc_id']);
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = '<span class="fw-medium fs-14 link-primary">' . $row['lote'] . '</span>';
            $sub_array[] = '<span class="fw-medium fs-14 link-secondary">' . $row['fecha'] . '</span>';
            $sub_array[] = '<span class="badge badge-soft-warning text-uppercase fs-14">+' . $row['cantidad'] . '</span>';
            $sub_array[] = '<div class="badge fw-medium badge-soft-secondary fs-14">' . $row['peso_neto'] . ' Lbs</div>';
            $sub_array[] = '<span class="badge badge-soft-success text-uppercase fs-14">' . "$ " . $row['totalMonto'] . '</span>';
            $sub_array[] = '<span class="badge badge-soft-primary text-uppercase fs-14">' . "$ " . $row['consumo'] . '</span>';
            $sub_array[] = '<span class="badge badge-soft-danger text-uppercase fs-14">- ' . $row['perdida'] . '</span>';
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
    // TODO: Obtiene el total de los campos para el reporte
    case 'obtenerTotales':
        $datos = $reporte->getTotalesReporte($_POST['lote_id'], $_POST['suc_id']);
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $outout["cantidad"] = $row["cantidad"];
                $outout["peso_neto"] = $row["peso_neto"];
                $outout["totalMonto"] = $row["totalMonto"];
                $outout["consumo"] = $row["consumo"];
                $outout["perdida"] = $row["perdida"];
            }
            echo json_encode($outout);
        }
        break;
}
