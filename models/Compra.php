<?php
class Compra extends Conectar
{
    /* TODO: Insertar nuevo registro */
    public function insertCompra($i_operacion, $i_suc_id, $i_usu_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_compra @i_operacion=?, @i_suc_id=?, @i_usu_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_suc_id);
        $query->bindValue(3, $i_usu_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* TODO: Insertar nuevo registro */
    public function insertDetalleCompra($i_operacion, $i_comp_id, $i_prod_id, $i_prod_pcompra, $i_detc_cant)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_detalle_compra @i_operacion=?, @i_comp_id=?, @i_prod_id=?, @i_prod_pcompra=?, @i_detc_cant=?, @i_detc_total=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_comp_id);
        $query->bindValue(3, $i_prod_id);
        $query->bindValue(4, $i_prod_pcompra);
        $query->bindValue(5, $i_detc_cant);
        $query->bindValue(6, ($i_prod_pcompra * $i_detc_cant));
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* TODO: Insertar nuevo registro */
    public function getListadoCompra($i_operacion, $i_comp_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_detalle_compra @i_operacion=?, @i_comp_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_comp_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Insertar nuevo registro */
    public function getCompra($i_operacion, $i_comp_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_compra @i_operacion=?, @i_comp_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_comp_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Eliminar item registro */
    public function deleteItemCompra($i_operacion, $i_detc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_detalle_compra @i_operacion=?, @i_detc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_detc_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Actualiza datos de la compra */
    public function updateCompra($i_operacion, $i_pago_id, $i_prov_id, $comp_comment, $mon_id, $i_comp_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_compra @i_operacion=?, @i_pago_id=? , @i_prov_id=? , @i_comp_comment=? , @i_mon_id=?, @i_comp_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_pago_id);
        $query->bindValue(3, $i_prov_id);
        $query->bindValue(4, $comp_comment);
        $query->bindValue(5, $mon_id);
        $query->bindValue(6, $i_comp_id);
        $query->execute();
    }

    /* TODO: Insertar nuevo registro */
    public function getListadoCompraRegistradas($i_operacion, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_compra @i_operacion=?, @i_suc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_suc_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
