<?php
// TODO: Llamando clases
require_once("../config/conexion.php");
require_once("../models/Producto.php");
// TODO: Inicializando clases
$producto = new Producto();

switch ($_GET['op']) {
        // TODO: Guardar y editar registro
    case 'guardar':
        if (empty($_POST["prod_id"])) {
            $producto->insertProducto("C", $_POST['suc_id'], $_POST['cat_id'], $_POST['prod_nombre'], $_POST['prod_descripcion'], $_POST['unm_id'], $_POST['mon_id'], $_POST['prod_pcompra'], $_POST['prod_pventa'], $_POST['prod_stock'], $_POST['prod_fechaven'], $_POST['prod_img'], $_POST['prod_cod_barra'], $_POST['prod_tipo_producto'], $_POST['cat_id']);
        } else {
            $producto->updateProducto("U", $_POST['prod_id'], $_POST['suc_id'], $_POST['cat_id'], $_POST['prod_nombre'], $_POST['prod_descripcion'], $_POST['unm_id'], $_POST['mon_id'], $_POST['prod_pcompra'], $_POST['prod_pventa'], $_POST['prod_stock'], $_POST['prod_fechaven'], $_POST['prod_img'], $_POST['prod_cod_barra'], $_POST['prod_tipo_producto']);
        }
        break;
        // TODO: Listado de registro en format JSON para Datatable JS
    case 'listar':
        $datos = $producto->getProductoPorSucursal("R", $_POST['suc_id']);
        $data = array();
        foreach ($datos as $row) {

            $sub_array = array();
            $sub_array[] = $row['prod_id'];
            $sub_array[] = $row['cat_nombre'];
            $sub_array[] = '<div class="d-flex align-items-center"><div class="flex-shrink-0 me-2"><img src="../../assets/products/' .  $row["prod_img"] . '" alt="" class="avatar-xs rounded-circle"></div><div class="flex-grow-1">' . $row['prod_nombre'] . '</div></div>';
            $sub_array[] = $row['prod_descripcion'];
            $sub_array[] = $row['prod_cod_barra'];
            $sub_array[] = $row['prod_pcompra'];
            $sub_array[] = $row['prod_pventa'];
            $sub_array[] = $row['prod_stock'];
            $sub_array[] = $row['prod_tipo_producto'] === 'P' ? 'Producto' : 'Servicio';
            $sub_array[] = empty($row['prod_fechaven']) ? 'No Ingresado' : $row['prod_fechaven'];
            $sub_array[] = $row['prod_estado'] === '1' ? "Activo" : "Inactivo";
            $sub_array[] = '<button type="button" onClick="editar(' . $row['prod_id'] . ')" id="' . $row['prod_id'] . '" class="btn btn-success btn-icon waves-effect waves-light"><i class="ri-edit-2-line"></i></button>';
            $sub_array[] = '<button type="button" onClick="eliminar(' . $row['prod_id'] . ')" id="' . $row['prod_id'] . '" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>';
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
    case 'nombre':
        $datos = $producto->getProductoPorNombre("R", $_POST['prod_nombre'], $_POST['suc_id']);
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array = $row['prod_nombre'];
            $sub_array = $row['prod_descripcion'];
            $sub_array = $row['unm_id'];
            $sub_array = $row['mon_id'];
            $sub_array = $row['prod_pcompra'];
            $sub_array = $row['prod_pventa'];
            $sub_array = $row['prod_stock'];
            $sub_array = $row['prod_fechaven'];
            $sub_array = $row['prod_img'];
            $sub_array = $row['prod_cod_barra'];
            $sub_array = $row['prod_tipo_producto'];
            $sub_array = 'Editar';
            $sub_array = 'Eliminar';
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
        $datos = $producto->getProductoPorId("R", $_POST['prod_id'], $_POST['suc_id']);
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $outout["suc_id"] = $row["suc_id"];
                $outout["prod_id"] = $row["prod_id"];
                $outout["prod_nombre"] = $row["prod_nombre"];
                $outout["cat_nombre"] = $row["cat_nombre"];
                $outout["cat_id"] = $row["cat_id"];
                $outout["prod_descripcion"] = $row["prod_descripcion"];
                $outout["unm_id"] = $row["unm_id"];
                $outout["unm_nombre"] = $row["unm_nombre"];
                $outout["mon_id"] = $row["mon_id"];
                $outout["mon_nombre"] = $row["mon_nombre"];
                $outout["prod_pcompra"] = $row["prod_pcompra"];
                $outout["prod_pventa"] = $row["prod_pventa"];
                $outout["prod_stock"] = $row["prod_stock"];
                $outout["prod_fechaven"] = $row["prod_fechaven"];
                $outout["prod_img"] = $row["prod_img"];
                $outout["prod_cod_barra"] = $row["prod_cod_barra"];
                $outout["prod_tipo_producto"] = $row["prod_tipo_producto"];
                $outout["prod_fechacrea"] = $row["prod_fechacrea"];
                $outout["prod_estado"] = $row["prod_estado"];
                if ($row["prod_img"] != '') {
                    $outout["prod_img"] = '<img src="../../assets/products/' . $row["prod_img"] . '" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="product-image"><input type="hidden" name="hidden_producto_img" value="' . $row["prod_img"] . '" />';
                } else {
                    $outout["prod_img"] = '<img src="../../assets/products/no_image.png" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="product-image"><input type="hidden" name="hidden_producto_img" value="" />';
                }
            }
            echo json_encode($outout);
        }
        break;
        // TODO: Mostrar información del registro por ID
    case 'cob_barra':
        $dato = $producto->getProductoPorCodigoBarra("R", $_POST['prod_cod_barra'], $_POST['suc_id']);
        if (is_array($dato) == true and count($dato) > 0) {
            foreach ($datos as $row) {
                $outout["prod_nombre"] = $row["prod_nombre"];
                $outout["prod_descripcion"] = $row["prod_descripcion"];
                $outout["unm_id"] = $row["unm_id"];
                $outout["mon_id"] = $row["mon_id"];
                $outout["prod_pcompra"] = $row["prod_pcompra"];
                $outout["prod_pventa"] = $row["prod_pventa"];
                $outout["prod_stock"] = $row["prod_stock"];
                $outout["prod_fechaven"] = $row["prod_fechaven"];
                $outout["prod_img"] = $row["prod_img"];
                $outout["prod_cod_barra"] = $row["prod_cod_barra"];
                $outout["prod_tipo_producto"] = $row["prod_tipo_producto"];
                $outout["prod_fecha_crea"] = $row["prod_fecha_crea"];
                $outout["prod_estado"] = $row["prod_estado"];
            }
            echo json_encode($outout);
        }
        break;
        // TODO: Eliminar registro por id
    case 'eliminar':
        $producto->deleteProducto("D", $_POST['prod_id'], $_POST['suc_id']);
        break;
        // TODO: Listar combo producto por categoria
    case 'cmbcate':
        $datos = $producto->getProductoPorCategoriaYSucursal($_POST['suc_id'], $_POST['cat_id']);
        if (is_array($datos) == true and count($datos) > 0) {
            $html = "";
            $html .= '<option selected>Seleccionar</option>';
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['prod_id'] . "'>" . $row['prod_nombre'] . " " . $row['prod_descripcion'] . "</option>";
            }
            echo $html;
        }
        break;
}
