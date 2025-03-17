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
    public function updateLote($i_operacion, $i_suc_id, $i_lote_descripcion, $i_lote_capacidad_max, $i_lote_id, $i_lote_cant_actual)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_lote @i_operacion=?, @i_suc_id=?, @i_lote_descripcion=?, @i_lote_capacidad_max=?, @i_lote_id=?, @i_lote_cant_actual=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_suc_id);
        $query->bindValue(3, $i_lote_descripcion);
        $query->bindValue(4, $i_lote_capacidad_max);
        $query->bindValue(5, $i_lote_id);
        $query->bindValue(6, $i_lote_cant_actual);
        $query->execute();
    }
    /* TODO: Ingreso alimento lote */
    public function ingresoAlimento($i_lote_id, $i_ali_cantidad, $i_ali_fecha, $i_user_id, $i_ali_desc)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_alimento_lote @i_operacion=?, @i_lote_id=?, @i_ali_cantidad=?, @i_ali_fecha=?, @i_user_id=?, @i_ali_desc=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'C');
        $query->bindValue(2, $i_lote_id);
        $query->bindValue(3, $i_ali_cantidad);
        $query->bindValue(4, $i_ali_fecha);
        $query->bindValue(5, $i_user_id);
        $query->bindValue(6, $i_ali_desc);
        $query->execute();
    }
    /* TODO: Listar alimento lote */
    public function getListadoAlimento($i_suc_id, $i_tipo, $i_fecha_desde, $i_fecha_hasta, $i_lote_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_alimento_lote @i_operacion=?, @i_tipo=?, @i_suc_id=?, @i_fecha_desde=?, @i_fecha_hasta=?,@i_lote_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'R');
        $query->bindValue(2, $i_tipo);
        $query->bindValue(3, $i_suc_id);
        $query->bindValue(4, $i_fecha_desde);
        $query->bindValue(5, $i_fecha_hasta);
        $query->bindValue(6, $i_lote_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* TODO: Listar alimento lote */
    public function getListadoAlimentoID($i_ali_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_alimento_lote @i_operacion=?, @i_tipo=?, @i_ali_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'R');
        $query->bindValue(2, 'TI');
        $query->bindValue(3, $i_ali_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* TODO: Eliminar registro por id */
    public function deleteLoteConsumo($i_ali_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_alimento_lote @i_operacion=?, @i_ali_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'D');
        $query->bindValue(2, $i_ali_id);
        return $query->execute();
    }

    /* TODO: Ingreso alimento lote */
    public function updateRegAlimento($i_lote_id, $i_ali_cantidad, $i_ali_fecha, $i_user_id, $i_ali_desc, $i_ali_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_alimento_lote @i_operacion=?, @i_lote_id=?, @i_ali_cantidad=?, @i_ali_fecha=?, @i_user_id=?, @i_ali_desc=?, @i_ali_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'U');
        $query->bindValue(2, $i_lote_id);
        $query->bindValue(3, $i_ali_cantidad);
        $query->bindValue(4, $i_ali_fecha);
        $query->bindValue(5, $i_user_id);
        $query->bindValue(6, $i_ali_desc);
        $query->bindValue(7, $i_ali_id);
        return $query->execute();
    }
}
