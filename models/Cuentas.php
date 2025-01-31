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


}
