<?php
// TODO: Llamando clases
require_once("../config/conexion.php");
require_once("../models/Venta.php");
// TODO: Inicializando clases
$venta = new Venta();

switch ($_GET['op']) {
        /* TODO: Guardar y editar registro */
    case 'registrar':
        $datos = $venta->insertVenta("C", $_POST['suc_id'], $_POST['usu_id']);
        foreach ($datos as $row) {
            $output["ven_id"] = $row["ven_id"];
        }
        echo json_encode($output);

        break;
        // TODO: Guardar detalle de la venta
    case 'addDetalle':
        $datos = $venta->insertDetalleVenta("C", $_POST['ven_id'], $_POST['prod_id'], $_POST['prod_pventa'], $_POST['detv_cant']);
        foreach ($datos as $row) {
            $output["subtotal"] = $row["subtotal"];
            $output["iva"] = $row["iva"];
            $output["total"] = $row["total"];
        }

        echo json_encode($output);
        break;
        // TODO: Listado de registro en format JSON para Datatable JS
    case 'listaDetalleView':
        $datos = $venta->getListadoDetalleVenta("L", $_POST['ven_id']);
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row['detv_id'];
            $sub_array[] = $row['cat_nombre'];
            $sub_array[] = $row['prod_nombre'] . " " . $row['prod_descripcion'];
            $sub_array[] = $row['detv_precio'];
            $sub_array[] = $row['detv_cantidad'];
            $sub_array[] = $row['detv_total'];
            $sub_array[] = '<button type="button" onClick="deleteItem(' . $row['detv_id'] . ' , ' . $row['ven_id'] . ')" id="' . $row['detv_id'] . '" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>';
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
        // TODO: Muestra el detalle de la venta
    case 'listarVenta':
        $datos = $venta->getVenta("L", $_POST['ven_id']);
        foreach ($datos as $row) {
            $output['ven_id'] = $row['ven_id'];
            $output['pago_nom'] = $row['pago_nom'];
            $output['cli_nombre'] = $row['cli_nombre'];
            $output['ven_subtotal'] = $row['ven_subtotal'];
            $output['ven_iva'] = $row['ven_iva'];
            $output['ven_total'] = $row['ven_total'];
            $output['ven_coment'] = $row['ven_coment'];
            $output['ven_fecha_crea'] = $row['ven_fecha_crea'];
            $output['ven_estado'] = $row['ven_estado'];
            $output['usu_id'] = $row['usu_id'];
            $output['usu_nom'] = $row['usu_nom'];
            $output['suc_id'] = $row['suc_id'];
            $output['tipo_comp'] = $row['tipo_comp'];
            $output['suc_nombre'] = $row['suc_nombre'];
            $output['emp_nombre'] = $row['emp_nombre'];
            $output['emp_ruc'] = $row['emp_ruc'];
            $output['emp_correo'] = $row['emp_correo'];
            $output['emp_telefono'] = $row['emp_telefono'];
            $output['emp_web'] = $row['emp_web'];
            $output['emp_direccion'] = $row['emp_direccion'];
            $output['com_nombre'] = $row['com_nombre'];
        }
        echo json_encode($output);
        break;

        // TODO: Guardar detalle de la venta
    case 'deleteItem':
        $datos =  $venta->deleteItemVenta("D", $_POST['detv_id']);
        foreach ($datos as $row) {
            $output["subtotal"] = $row["subtotal"];
            $output["iva"] = $row["iva"];
            $output["total"] = $row["total"];
        }

        echo json_encode($output);
        break;
        // TODO: Guardar detalle de la venta
    case 'updateVenta':
        $venta->updateVenta("U", $_POST['pago_id'], $_POST['cli_id'], $_POST['ven_coment'], $_POST['tipo_venta'], $_POST['ven_id']);
        break;
    case 'listaDetalleVenta':
        $datos = $venta->getListadoDetalleVenta("L", $_POST['ven_id']);
        foreach ($datos as $row) {
            ?>
                <tr>
                    <th scope="row"> <?php echo $row['detv_id'] ?> </th>
                    <td><?php echo $row['cat_nombre'] ?></td>
                    <td class="text-start">
                        <span class="fw-medium"><?php echo $row['prod_nombre'] ?></span>
                        <p class="text-muted mb-0"><?php echo $row['prod_descripcion'] ?></p>
                    </td>
                    <td><?php echo $row['detv_precio'] ?></td>
                    <td><?php echo $row['detv_cantidad'] ?></td>
                    <td class="text-end"><?php echo $row['detv_total'] ?></td>
                </tr>
            <?php
        }
        break;
        // TODO: Listado de registro en format JSON para Datatable JS
    case 'listarVentasReg':
        $datos = $venta->getListadoVentaRegistradas("A", $_POST['suc_id']);
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array(); 
            $sub_array[] = "C-".$row['ven_id'];
            $sub_array[] = $row['cli_nombre'];
            $sub_array[] = $row['cli_ruc'];
            $sub_array[] = $row['pago_nom'];
            $sub_array[] = "$".$row['ven_total'];
            $sub_array[] = $row['ven_fecha_crea'];
            $sub_array[] = $row['usu_nom'];
            $sub_array[] = '<a href="../ViewVenta/?id='.$row['ven_id'].'" target="_blank" id="' . $row['ven_id'] . '" class="btn btn-primary btn-icon waves-effect waves-light"><i class="ri-printer-fill"></i></a>';
            $sub_array[] = '<button type="button" onClick="ver(' . $row['ven_id'] . ')" id="' . $row['ven_id'] . '" class="btn btn-success btn-icon waves-effect waves-light"><i class="ri-eye-fill"></i></button>';
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
