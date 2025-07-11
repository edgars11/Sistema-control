<?php
class TipoComprobante extends Conectar
{
    /* TODO: Listar registro por sucursal */
    public function getTipoComprobantes()
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_tipocomprobante @i_operacion=?,@i_tipo=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'R');
        $query->bindValue(2, 'T');
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* TODO: Obtener detalle de forma de pago por ID */
    public function getFormaPagoByID($i_pago_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_tipocomprobante @i_operacion=?,@i_pago_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'R');
        $query->bindValue(2, $i_pago_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
