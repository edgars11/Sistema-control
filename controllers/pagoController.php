<?php
// TODO: Llamando clases
require_once("../config/conexion.php");
require_once("../models/Pago.php");
// TODO: Inicializando clases
$pago = new Pago();

switch ($_GET['op']) {
        // TODO: Listar combo
    case 'combo':
        $datos = $pago->getListadoPagos("R");
        if (is_array($datos) == true and count($datos) > 0) {
            $html = "";
            $html .= '<option selected>Seleccionar</option>';
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['pago_id'] . "'>" . $row['pago_nombre'] . "</option>";
            }
            echo $html;
        }
        break;

    case 'guardarPago':
        $usu_id = $_SESSION["usu_id"];
        $datos = $pago->registrarPago($_POST['cta_id'], $_POST['pago_id'], $_POST['pagc_obs'], $usu_id, $_POST['pagc_monto'], $_POST['suc_id']);

        if ($datos) {
            $outout["success"] = true;
        } else {
            $outout["success"] = false;
        }

        echo json_encode($outout);

        break;
    case 'deleterPago':
        $datos = $pago->eliminarPago($_POST['pagc_id']);
        $outout["exec"] = $datos;
        echo json_encode($outout);

        break;
    case 'listadoPagos':
        $pago_id = $_POST['pago_id'] == '' ? null : $_POST['pago_id'];
        $cli_id = $_POST['cli_id'] == '' ? null : $_POST['cli_id'];

        $datos = $pago->getListadoPagosFiltro($cli_id, $pago_id, $_POST['fecha_desde'], $_POST['fecha_hasta'], $_POST['suc_id']);
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = '<span style="font-weight: 600;"># ' . $row['cta_id'] . '</span>';
            $sub_array[] = '<span class="fw-medium link-primary fs-14">' . $row['cli_nombre'] . '</span>';
            $sub_array[] = '<div class="badge fw-medium badge-soft-secondary fs-14">' . $row['pago_nombre'] . '</div>';
            $sub_array[] = '<span class="badge badge-soft-success text-uppercase fs-14">' . "$ " . number_format($row['pagc_monto'] , 2 ,'.',','). '</span>';
            $sub_array[] = $row['pagc_obs'];
            $sub_array[] = $row['pagc_fecha'];
            $sub_array[] = $row['usu_nombre'];
            $sub_array[] = '<ul class="list-inline hstack gap-2 mb-0">
                        <li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Editar"
                            data-bs-original-title="Editar">
                            <button type="button" onClick="editar(' . $row['pagc_id'] . ')" id="' . $row['pagc_id'] . '" class="btn btn-success btn-icon waves-effect waves-light"><i class="ri-pencil-fill fs-16"></i></button>
                        </li>
                        <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Eliminar"
                            data-bs-original-title="Eliminar">
                            <button type="button" onClick="eliminar(' . $row['pagc_id'] . ')" id="' . $row['pagc_id'] . '" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>
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
    case 'getCobroId':
        $datos = $pago->getCobroId($_POST['pagc_id']);
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $outout["pagc_id"] = $row["pagc_id"];
                $outout["cta_id"] = $row["cta_id"];
                $outout["pago_id"] = $row["pago_id"];
                $outout["pagc_monto"] = number_format($row['pagc_monto'] , 2 ,'.',',');
                $outout["pagc_obs"] = $row["pagc_obs"];
                $outout["pagc_fecha"] = $row["pagc_fecha"];
                $outout["cli_nombre"] = $row["cli_nombre"];
            }
            echo json_encode($outout);
        }
        break;

    case 'updateCobroId':
        $datos = $pago->updateCobroById($_POST['pagc_monto'], $_POST['pagc_obs'], $_POST['pago_idM'], $_POST['pagc_id']);
        $outout["exec"] = $datos;

        echo json_encode($outout);

        break;
}
