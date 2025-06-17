<?php
class Pago extends Conectar
{
    /* TODO: Listar registro por sucursal */
    public function getListadoPagos($i_operacion)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_pago @i_operacion=?, @i_tipo=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, 'T');
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Listar registro por id */
    public function getPagoPorId($i_operacion, $i_cat_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_pago @i_operacion=?,@i_tipo=?,@i_cat_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, 'I');
        $query->bindValue(3, $i_cat_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Eliminar registro por id */
    public function deletePago($i_operacion, $i_cat_id, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_pago @i_operacion=?, @i_cat_id=?, @i_cat_estado=?, @i_suc_id= ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_cat_id);
        $query->bindValue(3, 0);
        $query->bindValue(4, $i_suc_id);
        $query->execute();
    }
    /* TODO: Insertar nuevo registro */
    public function insertPago($i_operacion, $i_suc_id, $i_cat_nombre)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_pago @i_operacion=?, @i_suc_id=?, @i_cat_nombre=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_suc_id);
        $query->bindValue(3, $i_cat_nombre);
        $query->execute();
    }
    /* TODO: Actualizar registro  */
    public function updatePago($i_operacion, $i_suc_id, $i_cat_nombre, $i_cat_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_pago @i_operacion=?, @i_suc_id=?, @i_cat_nombre=?, @i_cat_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_suc_id);
        $query->bindValue(3, $i_cat_nombre);
        $query->bindValue(4, $i_cat_id);
        $query->execute();
    }
    /* TODO: Actualizar registro  */
    public function registrarPago(int $i_cta_id, int $i_pago_id, string $i_pagc_obs, int $i_usu_id, float $i_pagc_monto, int $i_suc_id, int $i_salida_id, float $i_saldo_recibo, int $i_cli_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_pago @i_operacion=?, @i_cta_id=?, @i_pago_id=?, @i_pagc_obs=?, @i_usu_id=?, @i_pagc_monto=?, @i_suc_id=?, @i_salida_id=?, @i_saldo_recibo=?, @i_cli_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'P');
        $query->bindValue(2, $i_cta_id);
        $query->bindValue(3, $i_pago_id);
        $query->bindValue(4, $i_pagc_obs);
        $query->bindValue(5, $i_usu_id);
        $query->bindValue(6, $i_pagc_monto);
        $query->bindValue(7, $i_suc_id);
        $query->bindValue(8, $i_salida_id);
        $query->bindValue(9, $i_saldo_recibo);
        $query->bindValue(10, $i_cli_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Eliminar registro  */
    public function eliminarPago($i_pagc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_pago @i_operacion=?, @i_pagc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'D');
        $query->bindValue(2, $i_pagc_id);
        return $query->execute();
    }
    /* TODO: Listado de Pagos  */
    public function getListadoPagosFiltro($i_cli_id, $i_pago_id, $i_fecha_desde, $i_fecha_hasta, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_pago @i_operacion=?, @i_cli_id=?, @i_pago_id=?, @i_fecha_desde=?, @i_fecha_hasta=?, @i_suc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'L');
        $query->bindValue(2, $i_cli_id);
        $query->bindValue(3, $i_pago_id);
        $query->bindValue(4, $i_fecha_desde);
        $query->bindValue(5, $i_fecha_hasta);
        $query->bindValue(6, $i_suc_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Listado de Pagos  */
    public function getCobroId($i_pago_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_pago @i_operacion=?,@i_tipo =?, @i_pago_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'R');
        $query->bindValue(2, 'C');
        $query->bindValue(3, $i_pago_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Listado de Pagos  */
    public function updateCobroById($i_pagc_monto, $i_pagc_obs, $i_pago_id, $i_pagc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_pago @i_operacion=?,@i_pagc_monto=?,@i_pagc_obs=?, @i_pago_id=?,@i_pagc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'U');
        $query->bindValue(2, $i_pagc_monto);
        $query->bindValue(3, $i_pagc_obs);
        $query->bindValue(4, $i_pago_id);
        $query->bindValue(5, $i_pagc_id);
        return $query->execute();
    }

    /* TODO: Listado de Pagos  */
    public function getValSaldoPendiente($i_cli_id)
    {
        $conectar = parent::Conexion();
        $sql = "select SUM(movc_valor) as valor, movc_tipo 
                from tm_movimiento_cuenta mc
                inner join tm_cuenta_cliente cc on cc.cta_id = mc.cta_id
                where cc.cli_id = ? and salida_id = 0 and movc_estado = 1 and cc.cta_estado = 1 group by movc_tipo";
        $query = $conectar->prepare($sql);
        $query->bindParam(1, $i_cli_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);

        $query->close();
    }

    public function validaSaldoPendiente($i_cli_id)
    {

        $valTotal = 0;
        $valCancelado = 0;
        $pendiente = 0;
        $valida = false;
        $valSaldoPen = $this->getValSaldoPendiente($i_cli_id);
        if (is_array($valSaldoPen) and count($valSaldoPen) > 0) {
            foreach ($valSaldoPen as $row) {
                $tipoMov = $row['movc_tipo'];
                if ($tipoMov == '+') {
                    $valTotal = $row['valor'];
                } else {
                    $valCancelado = $row['valor'];
                }
            }
            $pendiente = $valTotal - $valCancelado;
            $valida = $pendiente == 0 ? true : false;
        } else {
            $valTotal = 0;
            $valCancelado = 0;
            $pendiente = 0;
            $valida = true;
        }

        $outout["SaldoPendienteTotal"] = $valTotal;
        $outout["saldoCancelado"] = $valCancelado;
        $outout["saldoPendiente"] = $pendiente;
        $outout["valida"] = $valida;

        return $outout;
    }
}
