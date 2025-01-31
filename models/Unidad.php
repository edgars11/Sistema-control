<?php
class Unidad extends Conectar
{
    /* TODO: Listar registro por sucursal */
    public function getUnidadPorSucursal($i_operacion, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_umedida @i_operacion=?,@i_tipo=?,@i_suc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, 'S');
        $query->bindValue(3, $i_suc_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Listar registro por id */
    public function getUnidadPorId($i_operacion, $i_unm_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_umedida @i_operacion=?,@i_tipo=?,@i_unm_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, 'I');
        $query->bindValue(3, $i_unm_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Eliminar registro por id */
    public function deleteUnidad($i_operacion, $i_unm_id, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_umedida @i_operacion=?,@i_unm_id=?, @i_unm_estado= ?, @i_suc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_unm_id);
        $query->bindValue(3, 0);
        $query->bindValue(4, $i_suc_id);
        $query->execute();
    }
    /* TODO: Actualizar registro  */
    public function insertUnidad($i_operacion, $i_suc_id, $i_unm_nombre)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_umedida @i_operacion=?, @i_suc_id=?, @i_unm_nombre=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_suc_id);
        $query->bindValue(3, $i_unm_nombre);
        $query->execute();
    }
    /* TODO: Insertar nuevo registro */
    public function updateUnidad($i_operacion, $i_suc_id, $i_unm_nombre, $i_unm_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_umedida @i_operacion=?, @i_suc_id=?, @i_unm_nombre=?, @i_unm_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_suc_id);
        $query->bindValue(3, $i_unm_nombre);
        $query->bindValue(4, $i_unm_id);
        $query->execute();
    }
}
