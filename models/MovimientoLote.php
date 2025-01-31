<?php
class MovimientoLote extends Conectar
{
    /* TODO: Listar registro por sucursal */
    public function getMovimientoLotePorSucursal($i_mov_fecha, $i_mov_hasta, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_movimiento_lote @i_operacion=?, @i_mov_fecha=?, @i_mov_hasta=?, @i_suc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'R');
        $query->bindValue(2, $i_mov_fecha);
        $query->bindValue(3, $i_mov_hasta);
        $query->bindValue(4, $i_suc_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Listar registro por id */
    public function getLotePorId($i_operacion, $i_lote_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_movimiento_lote @i_operacion=?,@i_tipo=?,@i_lote_id=?";
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
        $sql = "exec sp_crud_movimiento_lote @i_operacion=?, @i_lote_id=?, @i_suc_id= ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_lote_id);
        $query->bindValue(3, $i_suc_id);
        $query->execute();
    }
    /* TODO: Insertar nuevo registro */
    public function insertarMovimientoLote($i_lote_id, $i_mov_cantidad, $i_mov_tipo, $i_mov_motivo, $i_usu_id, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_movimiento_lote @i_operacion=?, @i_lote_id=?, @i_mov_cantidad=?, @i_mov_tipo=?, @i_mov_motivo=?, @i_usu_id=?, @i_suc_id=? ";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'C');
        $query->bindValue(2, $i_lote_id);
        $query->bindValue(3, $i_mov_cantidad);
        $query->bindValue(4, $i_mov_tipo);
        $query->bindValue(5, $i_mov_motivo);
        $query->bindValue(6, $i_usu_id);
        $query->bindValue(7, $i_suc_id);
        $query->execute();
    
    }
    /* TODO: Actualizar registro  */
    public function updateLote($i_operacion, $i_suc_id, $i_lote_descripcion, $i_lote_capacidad_max, $i_lote_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_movimiento_lote @i_operacion=?, @i_suc_id=?, @i_lote_descripcion=?, @i_lote_capacidad_max=?, @i_lote_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_suc_id);
        $query->bindValue(3, $i_lote_descripcion);
        $query->bindValue(4, $i_lote_capacidad_max);
        $query->bindValue(5, $i_lote_id);
        $query->execute();
    }
}
