<?php
class Producto extends Conectar
{
    /* TODO: Listar registro por sucursal */
    public function getProductoPorSucursal($i_operacion, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_producto @i_operacion=?,@i_tipo=?,@i_suc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, 'S');
        $query->bindValue(3, $i_suc_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Listar registro por id */
    public function getProductoPorId($i_operacion, $i_prod_id, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_producto @i_operacion=?,@i_tipo=?,@i_prod_id=?, @i_suc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, 'I');
        $query->bindValue(3, $i_prod_id);
        $query->bindValue(4, $i_suc_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Listar registro por nombre */
    public function getProductoPorNombre($i_operacion, $i_prod_nombre, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_producto @i_operacion=?,@i_tipo=?,@i_prod_nombre=?, @i_suc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, 'N');
        $query->bindValue(3, $i_prod_nombre);
        $query->bindValue(4, $i_suc_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Listar registro por codigo barra */
    public function getProductoPorCodigoBarra($i_operacion, $i_prod_cod_barra, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_producto @i_operacion=?,@i_tipo=?,@i_prod_cod_barra=?, @i_suc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, 'C');
        $query->bindValue(3, $i_prod_cod_barra);
        $query->bindValue(4, $i_suc_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Eliminar registro por id */
    public function deleteProducto($i_operacion, $i_prod_id, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_producto @i_operacion=?, @i_prod_id=?, @i_prod_estado=?, @i_suc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_prod_id);
        $query->bindValue(3, 0);
        $query->bindValue(4, $i_suc_id);
        $query->execute();
    }
    /* TODO: Actualizar registro  */
    public function updateProducto($i_operacion, $i_prod_id, $i_suc_id, $i_cat_id, $i_prod_nombre, $i_prod_descripcion, $i_unm_id, $i_mon_id, $i_prod_pcompra, $i_prod_pventa, $i_prod_stock, $i_prod_fechaven, $i_prod_img, $i_prod_cod_barra, $i_prod_tipo)
    {
        $conectar = parent::Conexion();

        require_once("Producto.php");
        $prod = new Producto();
        $prod_img = '';
        if ($_FILES["prod_img"]['name'] != '') {
            $prod_img = $prod->upload_image();
        } else {
            $prod_img = "no_image.png";
            $prod_img = $_POST["hidden_producto_img"];
        }

        $sql = "exec sp_crud_producto @i_operacion=?, @i_prod_id=?,@i_suc_id=?,@i_cat_id=?,@i_prod_nombre=?,@i_prod_descripcion=?,@i_unm_id=?,@i_mon_id=?,@i_prod_pcompra=?,@i_prod_pventa=?,@i_prod_stock=?,@i_prod_fechaven=?,@i_prod_img=?,@i_prod_cod_barra=?,@i_prod_tipo=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_prod_id);
        $query->bindValue(3, $i_suc_id);
        $query->bindValue(4, $i_cat_id);
        $query->bindValue(5, $i_prod_nombre);
        $query->bindValue(6, $i_prod_descripcion);
        $query->bindValue(7, $i_unm_id);
        $query->bindValue(8, $i_mon_id);
        $query->bindValue(9, $i_prod_pcompra);
        $query->bindValue(10, $i_prod_pventa);
        $query->bindValue(11, $i_prod_stock);
        $query->bindValue(12, $i_prod_fechaven);
        $query->bindValue(13, $prod_img);
        $query->bindValue(14, $i_prod_cod_barra);
        $query->bindValue(15, $i_prod_tipo);
        $query->execute();
    }
    /* TODO: Insertar nuevo registro */
    public function insertProducto($i_operacion, $i_suc_id, $i_cat_id, $i_prod_nombre, $i_prod_descripcion, $i_unm_id, $i_mon_id, $i_prod_pcompra, $i_prod_pventa, $i_prod_stock, $i_prod_fechaven, $i_prod_img, $i_prod_cod_barra, $i_prod_tipo)
    {
        $conectar = parent::Conexion();

        require_once("Producto.php");
        $prod = new Producto();
        $prod_img = '';
        if ($_FILES["prod_img"]['name'] != '') {
            $prod_img = $prod->upload_image();
        } else {
            $prod_img = "no_image.png";
        }

        $sql = "exec sp_crud_producto @i_operacion=?,@i_suc_id=?,@i_cat_id=?,@i_prod_nombre=?,@i_prod_descripcion=?,@i_unm_id=?,@i_mon_id=?,@i_prod_pcompra=?,@i_prod_pventa=?,@i_prod_stock=?,@i_prod_fechaven=?,@i_prod_img=?,@i_prod_cod_barra=?,@i_prod_tipo=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_suc_id);
        $query->bindValue(3, $i_cat_id);
        $query->bindValue(4, $i_prod_nombre);
        $query->bindValue(5, $i_prod_descripcion);
        $query->bindValue(6, $i_unm_id);
        $query->bindValue(7, $i_mon_id);
        $query->bindValue(8, $i_prod_pcompra);
        $query->bindValue(9, $i_prod_pventa);
        $query->bindValue(10, $i_prod_stock);
        $query->bindValue(11, $i_prod_fechaven);
        $query->bindValue(12, $prod_img);
        $query->bindValue(13, $i_prod_cod_barra);
        $query->bindValue(14, $i_prod_tipo);
        $query->execute();
    }
    /* TODO: Listar productos por categoria y sucursal */
    public function getProductoPorCategoriaYSucursal($i_suc_id, $i_cat_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_producto @i_operacion=?,@i_suc_id=?,@i_cat_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, "S");
        $query->bindValue(2, $i_suc_id);
        $query->bindValue(3, $i_cat_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function upload_image()
    {
        if (isset($_FILES["prod_img"])) {
            $extension = explode('.', $_FILES['prod_img']['name']);
            $new_name = rand() . '.' . $extension[1];
            $destination = '../assets/products/' . $new_name;
            move_uploaded_file($_FILES['prod_img']['tmp_name'], $destination);
            return $new_name;
        }
    }
}
