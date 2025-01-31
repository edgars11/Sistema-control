<?php
// TODO: Llamando clases
require_once("../config/conexion.php");
require_once("../models/Compra.php");
// TODO: Inicializando clases
$compra = new Compra();

switch ($_GET['op']) {
        // TODO: Guardar y editar registro
    case 'registrar':
        $datos = $compra->insertCompra("C", $_POST['suc_id'], $_POST['usu_id']);
        foreach ($datos as $row) {
            $output["comp_id"] = $row["comp_id"];
        }
        echo json_encode($output);

        break;
        // TODO: Guardar detalle de la compra
    case 'addDetalle':
        $datos = $compra->insertDetalleCompra("C", $_POST['comp_id'], $_POST['prod_id'], $_POST['prod_pcompra'], $_POST['detc_cant']);
        foreach ($datos as $row) {
            $output["subtotal"] = $row["subtotal"];
            $output["iva"] = $row["iva"];
            $output["total"] = $row["total"];
        }

        echo json_encode($output);
        break;
        // TODO: Listado de registro en format JSON para Datatable JS
    case 'listarDetalle':
        $datos = $compra->getListadoCompra("L", $_POST['comp_id']);
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row['detc_id'];
            $sub_array[] = $row['cat_nombre'];
            $sub_array[] = $row['prod_nombre'] . " " . $row['prod_descripcion'];
            $sub_array[] = $row['prod_pcompra'];
            $sub_array[] = $row['detc_cant'];
            $sub_array[] = $row['detc_total'];
            $sub_array[] = '<button type="button" onClick="deleteItem(' . $row['detc_id'] . ' , ' . $row['comp_id'] . ')" id="' . $row['detc_id'] . '" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>';
            $sub_array[] = $row['prod_id'];
            $sub_array[] = $row['cat_id'];
            $sub_array[] = $row['unm_nombre'];
            $sub_array[] = $row['prod_stock'];
            $sub_array[] = $row['detc_fecha_crea'];
            $sub_array[] = '<button type="button" onClick="editar(' . $row['detc_id'] . ' , ' . $row['comp_id'] . ')" id="' . $row['detc_id'] . '" class="btn btn-success btn-icon waves-effect waves-light"><i class="ri-edit-2-line"></i></button>';
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
        // TODO: Muestra el detalle de la compra
    case 'listarCompra':
        $datos = $compra->getCompra("L", $_POST['comp_id']);
        foreach ($datos as $row) {
            $output['comp_id'] = $row['comp_id'];
            $output['pago_nom'] = $row['pago_nom'];
            $output['prov_nombre'] = $row['prov_nombre'];
            $output['comp_subtotal'] = $row['comp_subtotal'];
            $output['comp_iva'] = $row['comp_iva'];
            $output['comp_total'] = $row['comp_total'];
            $output['comp_comment'] = $row['comp_comment'];
            $output['comp_fecha_crea'] = $row['comp_fecha_crea'];
            $output['comp_estado'] = $row['comp_estado'];
            $output['usu_id'] = $row['usu_id'];
            $output['usu_nom'] = $row['usu_nom'];
            $output['suc_id'] = $row['suc_id'];
            $output['mon_nombre'] = $row['mon_nombre'];
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

        // TODO: Guardar detalle de la compra
    case 'deleteItem':
        $datos =  $compra->deleteItemCompra("D", $_POST['detc_id']);
        foreach ($datos as $row) {
            $output["subtotal"] = $row["subtotal"];
            $output["iva"] = $row["iva"];
            $output["total"] = $row["total"];
        }

        echo json_encode($output);
        break;
        // TODO: Guardar detalle de la compra
    case 'updateCompra':
        $compra->updateCompra("U", $_POST['pago_id'], $_POST['prov_id'], $_POST['comp_comment'], $_POST['mon_id'], $_POST['comp_id']);
        break;
    case 'listaDetalleTB':
        $datos = $compra->getListadoCompra("L", $_POST['comp_id']);
        foreach ($datos as $row) {
            ?>
                <tr>
                    <th scope="row"> <?php echo $row['detc_id'] ?> </th>
                    <td><?php echo $row['cat_nombre'] ?></td>
                    <td class="text-start">
                        <span class="fw-medium"><?php echo $row['prod_nombre'] ?></span>
                        <p class="text-muted mb-0"><?php echo $row['prod_descripcion'] ?></p>
                    </td>
                    <td><?php echo $row['prod_pcompra'] ?></td>
                    <td><?php echo $row['detc_cant'] ?></td>
                    <td class="text-end"><?php echo $row['detc_total'] ?></td>
                </tr>
            <?php
        }
        break;
        // TODO: Listado de registro en format JSON para Datatable JS
    case 'listarComprasReg':
        $datos = $compra->getListadoCompraRegistradas("A", $_POST['suc_id']);
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = "C-".$row['comp_id'];
            $sub_array[] = $row['prov_nombre'];
            $sub_array[] = $row['prov_ruc'];
            $sub_array[] = $row['pago_nom'];
            $sub_array[] = "$".$row['comp_total'];
            $sub_array[] = $row['comp_fecha_crea'];
            $sub_array[] = $row['usu_nom'];
            $sub_array[] = '<a href="../ViewCompra/?id='.$row['comp_id'].'" target="_blank" id="' . $row['comp_id'] . '" class="btn btn-primary btn-icon waves-effect waves-light"><i class="ri-printer-fill"></i></a>';
            $sub_array[] = '<button type="button" onClick="ver(' . $row['comp_id'] . ')" id="' . $row['comp_id'] . '" class="btn btn-success btn-icon waves-effect waves-light"><i class="ri-eye-fill"></i></button>';
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
