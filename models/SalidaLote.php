<?php
class SalidaLote extends Conectar
{
    /* TODO: Listar registro por sucursal */
    public function getSalidaLotePorSucursal($i_sal_fecha, $i_suc_id, $i_usu_id, $i_salida_tipo)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_salida_lote @i_operacion=?, @i_suc_id=?, @i_usu_id=?, @i_salida_fecha=?, @i_salida_tipo=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'R');
        $query->bindValue(2, $i_suc_id);
        $query->bindValue(3, $i_usu_id);
        $query->bindValue(4, $i_sal_fecha);
        $query->bindValue(5, $i_salida_tipo);
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
    /* TODO: Eliminar registro por id */
    public function deleteSalida($i_salida_id, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_salida_lote @i_operacion=?, @i_salida_id= ?, @i_suc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'D');
        $query->bindValue(2, $i_salida_id);
        $query->bindValue(3, $i_suc_id);
        return $query->execute();
    }
    /* TODO: Insertar nuevo registro */
    public function insertarSalidaLote(
        $i_lote_id,
        $i_sal_fecha,
        $i_sal_cantidad,
        $i_sal_peso,
        $i_sal_tara,
        $i_sal_pesoneto,
        $i_sal_precio,
        $i_sal_total,
        $i_sal_tipo,
        $i_cli_id,
        $i_usu_id,
        $i_suc_id,
        $i_pago_id
    ) {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_salida_lote @i_operacion=?,@i_salida_fecha=?, @i_lote_id=?, @i_salida_cantidad=?, 	@i_salida_peso=?, @i_salida_tara=?, @i_salida_peso_neto=?, 	@i_salida_precio=?,	@i_salida_total=?, @i_salida_tipo=?, @i_cli_id=?, @i_usu_id=?, @i_suc_id=?, @i_pago_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'C');
        $query->bindValue(2, $i_sal_fecha);
        $query->bindValue(3, $i_lote_id);
        $query->bindValue(4, $i_sal_cantidad);
        $query->bindValue(5, $i_sal_peso);
        $query->bindValue(6, $i_sal_tara);
        $query->bindValue(7, $i_sal_pesoneto);
        $query->bindValue(8, $i_sal_precio);
        $query->bindValue(9, $i_sal_total);
        $query->bindValue(10, $i_sal_tipo);
        $query->bindValue(11, $i_cli_id);
        $query->bindValue(12, $i_usu_id);
        $query->bindValue(13, $i_suc_id);
        $query->bindValue(14, $i_pago_id);
        $query->execute();
    }
    /* TODO: Actualizar registro  */
    public function updateLote($i_lote_id, $i_sal_fecha, $i_sal_cantidad, $i_sal_peso, $i_sal_tara, $i_sal_pesoneto, $i_sal_precio,  $i_sal_total, $i_sal_tipo, $i_cli_id, $i_usu_id, $i_salida_id, $i_suc_id, $i_pago_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_salida_lote @i_operacion=?,@i_salida_fecha=?, @i_lote_id=?, @i_salida_cantidad=?, 	@i_salida_peso=?, @i_salida_tara=?, @i_salida_peso_neto=?, 	@i_salida_precio=?,	@i_salida_total=?, @i_salida_tipo=?, @i_cli_id=?, @i_usu_id=?, @i_salida_id=?, @i_suc_id=?, @i_pago_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'U');
        $query->bindValue(2, $i_sal_fecha);
        $query->bindValue(3, $i_lote_id);
        $query->bindValue(4, $i_sal_cantidad);
        $query->bindValue(5, $i_sal_peso);
        $query->bindValue(6, $i_sal_tara);
        $query->bindValue(7, $i_sal_pesoneto);
        $query->bindValue(8, $i_sal_precio);
        $query->bindValue(9, $i_sal_total);
        $query->bindValue(10, $i_sal_tipo);
        $query->bindValue(11, $i_cli_id);
        $query->bindValue(12, $i_usu_id);
        $query->bindValue(13, $i_salida_id);
        $query->bindValue(14, $i_suc_id);
        $query->bindValue(15, $i_pago_id);
        $query->execute();
    }
    /* TODO: Listar salida */
    public function getlistadoSalida($i_tipo, $i_lote_id, $i_cli_id, $i_salida_tipo, $i_fecha_desde, $i_fecha_hasta, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_salida_lote @i_operacion=?,@i_tipo=?,@i_lote_id=?, @i_cli_id=?, @i_salida_tipo=?, @i_fecha_desde=?, @i_fecha_hasta=?,@i_suc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'L');
        $query->bindValue(2, $i_tipo);
        $query->bindValue(3, $i_lote_id);
        $query->bindValue(4, $i_cli_id);
        $query->bindValue(5, $i_salida_tipo);
        $query->bindValue(6, $i_fecha_desde);
        $query->bindValue(7, $i_fecha_hasta);
        $query->bindValue(8, $i_suc_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Obtener registro de salida por ID */
    public function getSalidaById($i_salida_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_salida_lote @i_operacion=?,@i_tipo=?,@i_salida_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'L');
        $query->bindValue(2, 'I');
        $query->bindValue(3, $i_salida_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: REPORTE DE LOTES FILTRADO POR FECHAS */
    public function getTotalesLotesByFecha($i_fecha_desde, $i_fecha_hasta)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_salida_lote @i_operacion=?, @i_tipo=?, @i_fecha_desde=?, @i_fecha_hasta=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'L');
        $query->bindValue(2, 'A');
        $query->bindValue(3, $i_fecha_desde);
        $query->bindValue(4, $i_fecha_hasta);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* TODO: Listar salida */
    public function getDatosDashboard($i_tipo, $i_lote_id, $i_cli_id, $i_salida_tipo, $i_fecha_desde, $i_fecha_hasta, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_salida_lote @i_operacion=?,@i_tipo=?,@i_lote_id=?, @i_cli_id=?, @i_salida_tipo=?, @i_fecha_desde=?, @i_fecha_hasta=?,@i_suc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'L');
        $query->bindValue(2, $i_tipo);
        $query->bindValue(3, $i_lote_id);
        $query->bindValue(4, $i_cli_id);
        $query->bindValue(5, $i_salida_tipo);
        $query->bindValue(6, $i_fecha_desde);
        $query->bindValue(7, $i_fecha_hasta);
        $query->bindValue(8, $i_suc_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* TODO: Listar salida */
    public function getRecibosSinPagar($i_tipo, $i_cli_id, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_salida_lote @i_operacion=?,@i_tipo=?,@i_cli_id=?,@i_suc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'L');
        $query->bindValue(2, $i_tipo);
        $query->bindValue(3, $i_cli_id);
        $query->bindValue(4, $i_suc_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /* TODO: Obtener registro de salida por ID */
    public function getCamalCount($i_fecha)
    {
        $conectar = parent::Conexion();
        $sql = "select sum(cam_cantidad) as cantidadCamal, sum(cam_registros) as registrado, lote_id
            from tm_registro_camal c 
            where c.cam_fecha = ? and cam_estado = 1 and cam_registros < cam_cantidad
            group by lote_id
            ";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_fecha);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
