<?php
class Venta extends Conectar
{
    /* TODO: Insertar nuevo registro */
    public function insertVenta($i_operacion, $i_suc_id, $i_usu_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_venta @i_operacion=?, @i_suc_id=?, @i_usu_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_suc_id);
        $query->bindValue(3, $i_usu_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* TODO: Insertar nuevo registro */
    public function insertDetalleVenta($i_operacion, $i_ven_id, $i_prod_id, $i_prod_pventa, $i_detv_cant)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_detalle_venta @i_operacion=?, @i_ven_id=?, @i_prod_id=?, @i_detv_precio=?, @i_detv_cant=?, @i_detv_total=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_ven_id);
        $query->bindValue(3, $i_prod_id);
        $query->bindValue(4, $i_prod_pventa);
        $query->bindValue(5, $i_detv_cant);
        $query->bindValue(6, ($i_prod_pventa * $i_detv_cant));
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* TODO: Insertar nuevo registro */
    public function getListadoDetalleVenta($i_operacion, $i_ven_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_detalle_venta @i_operacion=?, @i_ven_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_ven_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Insertar nuevo registro */
    public function getVenta($i_operacion, $i_ven_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_venta @i_operacion=?, @i_ven_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_ven_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Eliminar item registro */
    public function deleteItemVenta($i_operacion, $i_detc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_detalle_venta @i_operacion=?, @i_detc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_detc_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Actualiza datos de la venta */
    public function updateVenta($i_operacion, $i_pago_id, $i_cli_id, $ven_comment, $tipo_venta, $i_ven_id, $i_suc_id, $i_ven_total)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_venta @i_operacion=?, @i_pago_id=? , @i_cli_id=? , @i_ven_comment=? , @i_tc_id=?, @i_ven_id=?, @i_suc_id=?, @i_ven_total=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_pago_id);
        $query->bindValue(3, $i_cli_id);
        $query->bindValue(4, $ven_comment);
        $query->bindValue(5, $tipo_venta);
        $query->bindValue(6, $i_ven_id);
        $query->bindValue(7, $i_suc_id);
        $query->bindValue(8, $i_ven_total);
        $query->execute();
    }

    /* TODO: Insertar nuevo registro */
    public function getListadoVentaRegistradas($i_operacion, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_venta @i_operacion=?, @i_suc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_suc_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
