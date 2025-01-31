<?php
class Moneda extends Conectar
{
    /* TODO: Listar registro por sucursal */
    public function getMonedaPorSucursal($i_operacion, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_moneda @i_operacion=?,@i_tipo=?,@i_suc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, 'S');
        $query->bindValue(3, $i_suc_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Listar registro por id */
    public function getMonedaPorId($i_operacion, $i_mon_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_moneda @i_operacion=?,@i_tipo=?,@i_mon_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, 'I');
        $query->bindValue(3, $i_mon_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Eliminar registro por id */
    public function deleteMoneda($i_operacion, $i_mon_id, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_moneda @i_operacion=?,@i_mon_id=?, @i_mon_estado=?, @i_suc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_mon_id);
        $query->bindValue(3, 0);
        $query->bindValue(4, $i_suc_id);
        $query->execute();
    }
    /* TODO: Actualizar registro  */
    public function insertMoneda($i_operacion, $i_suc_id, $i_mon_nombre)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_moneda @i_operacion=?, @i_suc_id=?, @i_mon_nombre=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_suc_id);
        $query->bindValue(3, $i_mon_nombre);
        $query->execute();
    }
    /* TODO: Insertar nuevo registro */
    public function updateMoneda($i_operacion, $i_suc_id, $i_mon_nombre, $i_mon_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_moneda @i_operacion=?, @i_suc_id=?, @i_mon_nombre=?, @i_mon_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_suc_id);
        $query->bindValue(3, $i_mon_nombre);
        $query->bindValue(4, $i_mon_id);
        $query->execute();
    }
}
