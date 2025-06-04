<?php
// TODO: Llamando clases
require_once("../config/conexion.php");
require_once("../models/Cuentas.php");
// TODO: Inicializando clases
$cuentas = new Cuentas();

switch ($_GET['op']) {
    // TODO: Listado de registro en format JSON para Datatable JS
    case 'listar':
        $datos = $cuentas->getCuentasPorSucursal($_POST['suc_id']);
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row['cta_id'];
            $sub_array[] = '<span class="fw-medium link-primary">' . $row['cli_nombre'] . '</span>';
            $sub_array[] = '<strong>$' . number_format($row['cta_monto'], 2, '.', ',')  . '</strong>';
            $sub_array[] = $row['cta_estado'] === '1' ? '<span class="badge badge-soft-success text-uppercase fs-12">Activo</span>' : '<span class="badge badge-soft-danger text-uppercase">Inactivo</span>';
            $sub_array[] = $row['cta_fecha_upd'];
            $sub_array[] = $row['cta_obs'];
            $sub_array[] = '<ul class="list-inline hstack gap-2 mb-0">
                                <li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Listado movimientos"
                                    data-bs-original-title="Listado movimientos">
                                    <button type="button" onClick="listadoMovimientos(' . $row['cta_id'] . ')" id="' . $row['cta_id'] . '" class="btn btn-warning btn-icon waves-effect waves-light"><i class="ri-file-list-3-line"></i></button>
                                </li>
                                <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Imprimir movimientos"
                                    data-bs-original-title="Imprimir movimientos">
                                    <button type="button" onClick="verReporte(' . $row['cta_id'] . ')" id="' . $row['cta_id'] . '" class="btn btn-primary btn-icon waves-effect waves-light"><i class="ri-printer-line"></i></button>
                                </li>
                            </ul>';
            $sub_array[] = '<ul class="list-inline hstack gap-2 mb-0">
                                <li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Editar"
                                    data-bs-original-title="Editar">
                                    <button type="button" onClick="editar(' . $row['cta_id'] . ')" id="' . $row['cta_id'] . '" class="btn btn-success btn-icon waves-effect waves-light"><i class="ri-pencil-fill fs-16"></i></button>
                                </li>
                                <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Eliminar"
                                    data-bs-original-title="Eliminar">
                                    <button type="button" onClick="eliminar(' . $row['cta_id'] . ')" id="' . $row['cta_id'] . '" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>
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
    // TODO: Listado de registro en format JSON para Datatable JS
    case 'movimientos':
        $datos = $cuentas->getMovCuentasPorSucursal($_POST['cta_id'], $_POST['suc_id']);
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row['movc_id'];
            $sub_array[] = $row['movc_tipo'] === '+' ? '<span class="badge badge-soft-success text-uppercase fs-12">AGREGADO</span>' : '<span class="badge badge-soft-danger text-uppercase fs-12">RESTADO</span>';
            $sub_array[] = $row['movc_val_actual'];
            $sub_array[] = $row['movc_tipo'] === '+' ? '<span class="badge badge-soft-success text-uppercase fs-14">+' . $row['movc_valor'] . '</span>' : '<span class="badge badge-soft-danger text-uppercase fs-14">-' . $row['movc_valor'] . '</span>';
            $sub_array[] = '<strong>$' . $row['movc_nuevo_val'] . '</strong>';
            $sub_array[] = '<span class="fw-medium link-primary">' . $row['movc_fecha'] . '</span>';
            $sub_array[] = $row['movc_obs'];
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

    // TODO: Listado de registro en format JSON para Datatable JS
    case 'consultaModal':
        $datos = $cuentas->getCuentasPorSucursal($_POST['suc_id']);
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = '<span class="fw-medium link-primary">' . $row['cli_nombre'] . '</span>';
            $sub_array[] = '<span class="fw-medium link-primary">' . $row['cli_ruc'] . '</span>';
            $sub_array[] = '<strong>$' . number_format($row['cta_monto'], 2, '.', ',') . '</strong>';
            $sub_array[] = $row['cta_estado'] === '1' ? '<span class="badge badge-soft-success text-uppercase fs-12">Activo</span>' : '<span class="badge badge-soft-danger text-uppercase">Inactivo</span>';
            $sub_array[] = '<button type="button" onClick="selCuenta(' . $row['cta_id'] . ')" id="' . $row['cta_id'] . '" class="btn btn-success btn-label waves-effect waves-light rounded-pill"><i class="ri-check-double-line label-icon align-middle rounded-pill fs-16 me-2"></i> Seleccionar Cuenta</button>';
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

    case 'byCta':
        $datos = $cuentas->datosCobroCuenta($_POST['cta_id'], $_POST['suc_id']);
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $outout["cta_id"] = $row["cta_id"];
                $outout["cli_nombre"] = $row["cli_nombre"];
                $outout["cli_telefono"] = $row["cli_telefono"];
                $outout["cta_monto"] = number_format($row['cta_monto'], 2, '.', ',');
                $outout["ult_fecha_sal"] = $row["ult_fecha_sal"];
                $outout["ult_fecha_pago"] = $row["ult_fecha_pago"];
                $outout["cli_id"] = $row["cli_id"];
            }
            echo json_encode($outout);
        }
        break;
    case 'guardar':
        $observaciones = $_POST['cta_obs'] == '' ? 'Ingreso cuenta' : $_POST['cta_obs'];
        $datos = $cuentas->createAccount($_POST['cli_id'], $_POST['cta_monto'], $_POST['cta_fecha'], $observaciones, 0, $_POST['suc_id'], $_POST['usu_id']);
        echo json_encode($datos);
        break;

    case 'update':
        $observaciones = $_POST['cta_obs'] == '' ? 'Actualizacióm cuenta' : $_POST['cta_obs'];
        $tipoMovimiento = $_POST['mov_tipo'] == 'AD' ? '+' : '-';
        $datos = $cuentas->updateAccount($_POST['cta_montoUpd'], $_POST['cta_fecha'], $observaciones, 0, $_POST['suc_id'], $_POST['usu_id'], $_POST['cta_id'], $tipoMovimiento);
        echo json_encode($datos);
        break;
    case 'delete':
        $datos = $cuentas->deleteAccount($_POST['cta_id'], $_POST['suc_id']);
        echo json_encode($datos);
        break;
    case 'byClient':
        $datos = $cuentas->datosCobroCuenta($_POST['cli_id'], $_POST['suc_id']);
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $outout["cta_id"] = $row["cta_id"];
                $outout["cli_nombre"] = $row["cli_nombre"];
                $outout["cli_telefono"] = $row["cli_telefono"];
                $outout["cta_monto"] = number_format($row['cta_monto'], 2, '.', ',');
                $outout["ult_fecha_sal"] = $row["ult_fecha_sal"];
                $outout["ult_fecha_pago"] = $row["ult_fecha_pago"];
                $outout["cli_id"] = $row["cli_id"];
            }
            echo json_encode($outout);
        }
        break;
}
