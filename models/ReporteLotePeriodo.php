<?php
class ReporteLotePeriodo extends Conectar
{
    /* TODO: Listar registro por sucursal */
    public function getListadoPeriodos($i_tipo, $i_lote_id , $i_anio_periodo, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_reporte_periodo_lote @i_operacion=?, @i_tipo=?, @i_lote_id =?, @i_anio_periodo=?, @i_suc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'L');
        $query->bindValue(2, $i_tipo);
        $query->bindValue(3, $i_lote_id);
        $query->bindValue(4, $i_anio_periodo);
        $query->bindValue(5, $i_suc_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Listar registro por id */
    public function getFechasPeriodoLote($i_lote_id, $fecha_periodo)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_reporte_periodo_lote @i_operacion=?,@i_tipo=?,@i_lote_id=?, @i_fecha_periodo=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'L');
        $query->bindValue(2, 'F');
        $query->bindValue(3, $i_lote_id);
        $query->bindValue(4, $fecha_periodo);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Eliminar registro por id */
    public function getListadoReporte($i_lote_id, $i_fecha_periodo, $i_fecha_fin, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_reporte_periodo_lote @i_operacion=?, @i_lote_id =?, @i_fecha_periodo= ?, @i_fecha_fin= ?, @i_suc_id= ?";
        // $sql = "exec sp_reporte_periodo_lote @i_operacion='R', @i_lote_id =6, @i_fecha_periodo= '2025-01-18' , @i_fecha_fin= '2025-03-20', @i_suc_id= 1";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'R');
        $query->bindValue(2, $i_lote_id);
        $query->bindValue(3, $i_fecha_periodo);
        $query->bindValue(4, $i_fecha_fin);
        $query->bindValue(5, $i_suc_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Insertar nuevo registro */
    public function getTotalesReporte($i_lote_id, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_reporte_periodo_lote @i_operacion=?, @i_lote_id =?, @i_suc_id= ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'T');
        $query->bindValue(2, $i_lote_id);
        $query->bindValue(3, $i_suc_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

}
