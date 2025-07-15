<?php
// TODO: Llamando clases
require_once("../config/conexion.php");
require_once("../models/SalidaLote.php");
require_once("../models/Pago.php");
// TODO: Inicializando clases
$salidalote = new SalidaLote();
$modelPago = new Pago();

switch ($_GET['op']) {
    // TODO: Guardar y editar registro
    case 'guardar':
        $usu_id = $_SESSION["usu_id"];
        if (empty($_POST["salida_id"])) {
            $salidalote->insertarSalidaLote($_POST['lote_idIng'], $_POST['sal_fecha'], $_POST['sal_cantidad'], $_POST['sal_peso'], $_POST['sal_tara'], $_POST['sal_peso_neto'], $_POST['sal_precio'], $_POST['sal_total'], $_POST['sal_tipo'], $_POST['cli_id'], $usu_id, $_POST['suc_id'], $_POST['pago_id']);
        } else {
            $salidalote->updateLote($_POST['lote_id'], $_POST['sal_fecha'], $_POST['sal_cantidad'], $_POST['sal_peso'], $_POST['sal_tara'], $_POST['sal_peso_neto'], $_POST['sal_precio'], $_POST['sal_total'], $_POST['sal_tipo'], $_POST['cli_id'], $usu_id, $_POST['salida_id'], $_POST['suc_id'], $_POST['pago_id']);
        }
        break;
    // TODO: Listado de registro en format JSON para Datatable JS
    case 'listar':
        $usu_id = $_SESSION["usu_id"];
        $salidaTipo = $_POST['salida_tipo'];
        $datos = $salidalote->getSalidaLotePorSucursal($_POST['salida_fecha'], $_POST['suc_id'], $usu_id, $salidaTipo);
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $etiquetaFormaPago = $row['pago_nombre'] == 'CREDITO' ? 'badge-soft-danger' : 'badge-soft-primary';
            if ($salidaTipo == 'PV') {
                $sub_array[] = '<span class="fw-medium link-primary">' . $row['cli_nombre'] . '</span>';
                $sub_array[] = $row['lote_descripcion'];
                $sub_array[] = '<span class="badge ' . $etiquetaFormaPago . ' text-uppercase fs-12">' . $row['pago_nombre'] . '</span>';
                $sub_array[] = '<span style="font-weight: 600;"># ' . $row['salida_cantidad'] . '</span>';
                $sub_array[] = number_format($row['salida_peso'], 2, '.', ',');
                $sub_array[] = $row['salida_tara'];
                $sub_array[] = '<span style="font-weight: 600;">' . $row['salida_peso_neto'] . ' lbs</span>';
                $sub_array[] = number_format($row['salida_precio'], 2, '.', ',');
                $sub_array[] = '<span class="badge badge-soft-success text-uppercase fs-14">' . "$ " . number_format($row['salida_total'], 2, '.', ',') . '</span>';
                $sub_array[] = $row['salida_fecha'];
            } else {
                $sub_array[] = '<span class="fw-medium link-primary">' . $row['cli_nombre'] . '</span>';
                $sub_array[] = '<span class="badge ' . $etiquetaFormaPago . ' text-uppercase fs-12">' . $row['pago_nombre'] . '</span>';
                $sub_array[] = '<span style="font-weight: 600;"># ' . $row['salida_cantidad'] . '</span>';
                $sub_array[] = number_format($row['salida_peso'], 2, '.', ',');
                $sub_array[] = $row['salida_tara'];
                $sub_array[] = '<span style="font-weight: 600;">' . $row['salida_peso_neto'] . ' lbs</span>';
                $sub_array[] = number_format($row['salida_precio'], 2, '.', ',');
                $sub_array[] = '<span class="badge badge-soft-success text-uppercase fs-14">' . "$ " . number_format($row['salida_total'], 2, '.', ',') . '</span>';
                $sub_array[] = $row['salida_fecha'];
            }

            $sub_array[] = '<ul class="list-inline hstack gap-2 mb-0 w-100">
                                    <li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title=""
                                        data-bs-original-title="Editar">
                                        <button type="button" onClick="editar(' . $row['salida_id'] . ')" id="' . $row['salida_id'] . '" class="btn btn-warning btn-icon waves-effect waves-light"><i class="ri-pencil-fill fs-16"></i></button>
                                    </li>
                                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title=""
                                        data-bs-original-title="Eliminar">
                                        <button type="button" onClick="eliminar(' . $row['salida_id'] . ')" id="' . $row['salida_id'] . '" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>
                                    </li>
                                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title=""
                                        data-bs-original-title="Visualizar">
                                        <button type="button" onClick="verSalida(' . $row['salida_id'] . ')" id="' . $row['salida_id'] . '" class="btn btn-primary btn-icon waves-effect waves-light"><i class="ri-printer-line"></i></button>
                                    </li>
                                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title=""
                                        data-bs-original-title="Whatsapp">
                                        <a href="https://wa.me/593' . $row['cli_telefono'] . '?text=Reporte%20pedido%20para%20la%20fecha:%20' . $row['salida_fecha'] . '" target="_blank" class="btn btn-success btn-icon waves-effect waves-light"><i class="ri-whatsapp-line"></i></a>
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
            $estadoRecibo = $row["salida_vpagado"];
            $colorTag = '';
            if ($estadoRecibo == 'N') {
                $estadoRecibo = 'Pendiente';
                $colorTag = 'danger';
            } else if ($estadoRecibo == 'A') {
                $estadoRecibo = 'Abonado';
                $colorTag = 'warning';
            } else if ($estadoRecibo == 'C') {
                $estadoRecibo = 'Cancelado';
                $colorTag = 'success';
            }
            $sub_array = array();
            $sub_array[] = '<span class="fw-medium link-primary">' . $row['lote_descripcion'] . '</span>';
            $sub_array[] = $row['cli_nombre'];
            $sub_array[] = $row['salida_tipo'] === 'PV' ? '<span class="badge badge-soft-warning text-uppercase fs-12">POLLO VIVO</span>' : '<span class="badge badge-soft-primary text-uppercase fs-12">POLLO FAENADO</span>';
            $sub_array[] = $row['salida_fecha'];
            $sub_array[] = '<span class="badge badge-soft-' . $colorTag . ' text-uppercase fs-14">' . "# " . $row['salida_id'] . ' - ' . $estadoRecibo . '</span>';
            $sub_array[] = '<div class="badge fw-medium badge-soft-secondary fs-14">' . $row['salida_cantidad'] . '</div>';
            $sub_array[] = $row['salida_peso_neto'] . " Lbs";
            $sub_array[] = "$ " . $row['salida_precio'];
            $sub_array[] = '<span class="badge badge-soft-success text-uppercase fs-14">' . "$ " . $row['salida_total'] . '</span>';
            $sub_array[] = $row['usu_nombre'];
            $sub_array[] = $row['salida_hora'];
            $sub_array[] = '<ul class="list-inline hstack gap-2 mb-0 w-100">
                                    <li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title=""
                                        data-bs-original-title="Editar">
                                        <button type="button" onClick="editar(' . $row['salida_id'] . ')" id="' . $row['salida_id'] . '" class="btn btn-warning btn-icon waves-effect waves-light"><i class="ri-pencil-fill fs-16"></i></button>
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

    case 'totales':
        $lote_id = $_POST['lote_id'] == '' ? null : $_POST['lote_id'];
        $cli_id = $_POST['cli_id'] == '' ? null : $_POST['cli_id'];
        $salida_tipo = $_POST['salida_tipo'] == '' ? null : $_POST['salida_tipo'];

        $datos = $salidalote->getlistadoSalida('T', $lote_id, $cli_id, $salida_tipo, $_POST['fecha_desde'], $_POST['fecha_hasta'], $_POST['suc_id']);
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $outout["cantidad"] = number_format($row["cantidad"], 0, '', ',');
                $outout["peso_neto"] = number_format($row["peso_neto"], 2, '.', ',');
                $outout["total"] = number_format($row["total"], 2, '.', ',');
                $outout["monto_abonado"] = number_format($row["monto_abonado"], 2, '.', ',');
                $outout["saldo_total_cta"] = number_format($row["saldo_total_cta"], 2, '.', ',');
            }
            echo json_encode($outout);
        }
        break;
    case 'deleteout':
        $datos =  $salidalote->deleteSalida($_POST['salida_id'], $_POST['suc_id']);
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
                $outout["cli_direccion"] = $row["cli_direccion"];
                $outout["cli_correo"] = $row["cli_correo"];
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
                $outout["saldo"] = $row["saldo"];
                $outout["salida_vpagado"] = $row["salida_vpagado"];
                $outout["pago_id"] = $row["pago_id"];
            }
            echo json_encode($outout);
        }
        break;
    case 'ListadoRecibosSP':
        $cli_id = $_POST['cli_id'] == '' ? null : $_POST['cli_id'];

        $datos = $salidalote->getRecibosSinPagar($_POST['tipo_val'], $cli_id, $_POST['suc_id']);
        if (is_array($datos) == true and count($datos) > 0) {
            $html = "";
            $valSaldoPendiente = $modelPago->validaSaldoPendiente($cli_id);
            if ($valSaldoPendiente["valida"] == false) {
                $html .= '<option value="0" selected>Cuenta Pendiente($' . $valSaldoPendiente["SaldoPendienteTotal"] . ') - Abonado: $' . $valSaldoPendiente["saldoCancelado"] . ' - Saldo : $' . $valSaldoPendiente["saldoPendiente"] . '</option>';
            } else {
                $html .= '<option value="-1" selected>Seleccione un registro</option>';
            }

            foreach ($datos as $row) {
                $estado = $row['salida_vpagado'] == 'N' ? 'Pendiente' : 'Abonado: $ ' . $row['saldo'] . ' - Saldo : $ ' . ($row['salida_total'] - $row['saldo']);
                $html .= "<option value='" . $row['salida_id'] . "'> # " . $row['salida_id'] . " - $ " . ($row['salida_total']) . " - " . $estado . "</option>";
            }
            echo $html;
        } else {
            $valSaldoPendiente = $modelPago->validaSaldoPendiente($cli_id);
            if ($valSaldoPendiente["valida"] == false) {
                $html .= '<option value="0" selected>Cuenta Pendiente($' . $valSaldoPendiente["SaldoPendienteTotal"] . ') - Abonado: $' . $valSaldoPendiente["saldoCancelado"] . ' - Saldo : $' . $valSaldoPendiente["saldoPendiente"] . '</option>';
            } else {
                $html .= '<option value="-1" selected>No hay registros</option>';
            }
            echo $html;
            // echo json_encode($valSaldoPendiente);
        }
        break;
    // RETORNA EL TOTAL DE POLLOS REGISTRADO AL CAMAL DISPONIBLES    
    case 'AmountCamal':
        $fechaRegistro = $_POST['fecha_registro'];

        $datos = $salidalote->getCamalCount($fechaRegistro);
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $outout["cantidadCamal"] = $row["cantidadCamal"];
                $outout["registrado"] = $row["registrado"];
                $outout["lote_id"] = $row["lote_id"];
            }
            echo json_encode($outout);
        } else {
            $outout["info"] = 'No hay info';
            echo json_encode($datos);
        }
        break;
}
