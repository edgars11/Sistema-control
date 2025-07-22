<?php
class Cuentas extends Conectar
{
    /* TODO: Listar registro por sucursal */
    public function getCuentasPorSucursal($i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_cuenta_cli @i_operacion=?,@i_suc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'R');
        $query->bindValue(2, $i_suc_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Listar registro por sucursal */
    public function getMovCuentasPorSucursal($i_cta_id, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_cuenta_cli @i_operacion=?, @i_cta_id=?, @i_suc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'M');
        $query->bindValue(2, $i_cta_id);
        $query->bindValue(3, $i_suc_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Listar registro por sucursal */
    public function datosCobroCuenta($i_cta_id, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_cuenta_cli @i_operacion=?, @i_cta_id=?, @i_suc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'W');
        $query->bindValue(2, $i_cta_id);
        $query->bindValue(3, $i_suc_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Crea una nueva cuenta */
    public function createAccount($i_cli_id, $i_cta_monto, $i_cta_fecha, $i_cta_obs, $i_salida_id, $i_suc_id, $i_usu_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_cuenta_cli @i_operacion=?, @i_cli_id=?, @i_cta_monto=?, @i_cta_fecha=?, @i_cta_obs=?, @i_salida_id=?, @i_suc_id=?, @i_usu_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'C');
        $query->bindValue(2, $i_cli_id);
        $query->bindValue(3, $i_cta_monto);
        $query->bindValue(4, $i_cta_fecha);
        $query->bindValue(5, $i_cta_obs);
        $query->bindValue(6, $i_salida_id);
        $query->bindValue(7, $i_suc_id);
        $query->bindValue(8, $i_usu_id);
        return $query->execute();
    }

    /* TODO: Actualizar una nueva cuenta */
    public function updateAccount($i_cta_monto, $i_cta_fecha, $i_cta_obs, $i_salida_id, $i_suc_id, $i_usu_id, $i_cta_id, $i_movc_tipo)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_cuenta_cli @i_operacion=?, @i_cta_monto=?, @i_cta_fecha=?, @i_cta_obs=?, @i_salida_id=?, @i_suc_id=?, @i_usu_id=?, @i_cta_id=?, @i_movc_tipo=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'U');
        $query->bindValue(2, $i_cta_monto);
        $query->bindValue(3, $i_cta_fecha);
        $query->bindValue(4, $i_cta_obs);
        $query->bindValue(5, $i_salida_id);
        $query->bindValue(6, $i_suc_id);
        $query->bindValue(7, $i_usu_id);
        $query->bindValue(8, $i_cta_id);
        $query->bindValue(9, $i_movc_tipo);
        return $query->execute();
    }
    /* TODO: Actualizar una nueva cuenta */
    public function deleteAccount($i_cta_id, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_cuenta_cli @i_operacion=?, @i_suc_id=?, @i_cta_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'D');
        $query->bindValue(2, $i_suc_id);
        $query->bindValue(3, $i_cta_id);
        return $query->execute();
    }
    /* TODO: Listar registro por sucursal */
    public function getCtaByClientId($i_cli_id, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_cuenta_cli @i_operacion=?, @i_cli_id=?, @i_suc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'B');
        $query->bindValue(2, $i_cli_id);
        $query->bindValue(3, $i_suc_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* TODO: Listar de cuentas por cobrar para reporte PDF */
    public function getListadoCuentaPorCobrar($i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_cuenta_cli @i_operacion =?, @i_suc_id = ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'L');
        $query->bindValue(2, $i_suc_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* TODO: Listar de movimientos de cuenta */
    public function getMovimientosCuentas($i_cta_id, $i_fecha_desde, $i_fecha_hasta, $i_tipo_mov)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_cuenta_cli @i_operacion = ?, @i_cta_id =?, @i_fecha_desde=?, @i_fecha_hasta=?, @i_movc_tipo=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'Z');
        $query->bindValue(2, $i_cta_id);
        $query->bindValue(3, $i_fecha_desde);
        $query->bindValue(4, $i_fecha_hasta);
        $query->bindValue(5, $i_tipo_mov);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }   
}
