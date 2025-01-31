<?php
class Lote extends Conectar
{
    /* TODO: Listar registro por sucursal */
    public function getLotePorSucursal($i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_lote @i_operacion=?,@i_tipo=?,@i_suc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'R');
        $query->bindValue(2, 'S');
        $query->bindValue(3, $i_suc_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Listar registro por id */
    public function getLotePorId($i_operacion, $i_lote_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_lote @i_operacion=?,@i_tipo=?,@i_lote_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, 'I');
        $query->bindValue(3, $i_lote_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Eliminar registro por id */
    public function deleteLote($i_operacion, $i_lote_id, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_lote @i_operacion=?, @i_lote_id=?, @i_suc_id= ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_lote_id);
        $query->bindValue(3, $i_suc_id);
        $query->execute();
    }
    /* TODO: Insertar nuevo registro */
    public function insertarLote($i_operacion, $i_suc_id, $i_lote_descripcion, $i_lote_capacidad_max)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_lote @i_operacion=?, @i_suc_id=?, @i_lote_descripcion=?, @i_lote_capacidad_max=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_suc_id);
        $query->bindValue(3, $i_lote_descripcion);
        $query->bindValue(4, $i_lote_capacidad_max);
        $query->execute();
    
    }
    /* TODO: Actualizar registro  */
    public function updateLote($i_operacion, $i_suc_id, $i_lote_descripcion, $i_lote_capacidad_max, $i_lote_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_lote @i_operacion=?, @i_suc_id=?, @i_lote_descripcion=?, @i_lote_capacidad_max=?, @i_lote_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_suc_id);
        $query->bindValue(3, $i_lote_descripcion);
        $query->bindValue(4, $i_lote_capacidad_max);
        $query->bindValue(5, $i_lote_id);
        $query->execute();
    }
}
