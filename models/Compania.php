<?php
class Compania extends Conectar
{
    /* TODO: Listar registro por id */
    public function getCompaniaPorId($i_operacion, $i_com_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_compania @i_operacion=?,@i_tipo=?,@i_com_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, 'I');
        $query->bindValue(3, $i_com_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Eliminar registro por id */
    public function deleteCompania($i_operacion, $i_com_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_compania @i_operacion=?, @i_com_id=?, @i_com_estado=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_com_id);
        $query->bindValue(3, 0);
        $query->execute();
    }
    /* TODO: Insertar nuevo registro  */
    public function insertCompania($i_operacion, $i_com_nombre)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_compania @i_operacion=?, @i_com_nombre=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_com_nombre);
        $query->execute();
    }
    /* TODO: Actualizar registro */
    public function updateCompania($i_operacion, $i_com_nombre, $i_com_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_compania @i_operacion=?, @i_com_nombre=?, @i_com_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_com_nombre);
        $query->bindValue(3, $i_com_id);
        $query->execute();
    }
}
