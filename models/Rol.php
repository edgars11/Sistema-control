<?php
class Rol extends Conectar
{
    /* TODO: Listar registro por sucursal */
    public function getRolPorSucursal($i_operacion, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_rol @i_operacion=?,@i_tipo=?,@i_suc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, 'S');
        $query->bindValue(3, $i_suc_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Listar registro por id */
    public function getRolPorId($i_operacion, $i_rol_id, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_rol @i_operacion=?,@i_tipo=?,@i_rol_id=?, @i_suc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, 'I');
        $query->bindValue(3, $i_rol_id);
        $query->bindValue(4, $i_suc_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Eliminar registro por id */
    public function deleteRol($i_operacion, $i_rol_id, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_rol @i_operacion=?, @i_rol_id=?, @i_rol_estado=?, @i_suc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_rol_id);
        $query->bindValue(3, 0);
        $query->bindValue(4, $i_suc_id);
        $query->execute();
    }
    /* TODO: Actualizar registro */
    public function updateRol($i_operacion, $i_suc_id, $i_rol_nombre, $i_rol_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_rol @i_operacion=?, @i_suc_id=?, @i_rol_nombre=?, @i_rol_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_suc_id);
        $query->bindValue(3, $i_rol_nombre);
        $query->bindValue(4, $i_rol_id);
        $query->execute();
    }
    /* TODO: Insertar nuevo registro  */
    public function insertRol($i_operacion, $i_suc_id, $i_rol_nombre)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_rol @i_operacion=?, @i_suc_id=?, @i_rol_nombre=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_suc_id);
        $query->bindValue(3, $i_rol_nombre);
        $query->execute();
    }
}
