<?php
class VentaCreditoModel extends Conectar
{
    /* TODO: Listar registro ventas credito */
    public function getListadoVentasCred($i_operacion, $i_cta_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_venta_credito @i_operacion=?, @i_cta_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_cta_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Busqueda individual  */
    public function getDatosVenta($i_ven_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_venta_credito @i_operacion=?, @i_ven_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, "I");
        $query->bindValue(2, $i_ven_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }    
    /* TODO: Actualizar registro  */
    public function registrarPago(int $i_cta_id, int $i_pago_id, string $i_pagc_obs, int $i_usu_id, float $i_pagc_monto, int $i_suc_id, int $i_ven_id, 
        int $i_cli_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_venta_credito @i_operacion=?, @i_cta_id=?, @i_pago_id=?, @i_pago_obs=?, @i_usu_id=?, @i_monto=?, @i_suc_id=?, @i_ven_id=?, @i_cli_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'P');
        $query->bindValue(2, $i_cta_id);
        $query->bindValue(3, $i_pago_id);
        $query->bindValue(4, $i_pagc_obs);
        $query->bindValue(5, $i_usu_id);
        $query->bindValue(6, $i_pagc_monto);
        $query->bindValue(7, $i_suc_id);
        $query->bindValue(8, $i_ven_id);
        $query->bindValue(9, $i_cli_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
