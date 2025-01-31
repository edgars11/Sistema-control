<?php
class Sucursal extends Conectar
{
    /* TODO: Listar registro por empresa */
    public function getSucursalPorEmpresa($i_operacion, $i_emp_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_sucursal @i_operacion=?, @i_tipo=?, @i_emp_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, 'T');
        $query->bindValue(3, $i_emp_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Listar registro por id */
    public function getSucursalPorId($i_operacion, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_sucursal @i_operacion=?, @i_tipo=?, @i_suc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, 'I');
        $query->bindValue(3, $i_suc_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Eliminar registro por id */
    public function deleteSucursal($i_operacion, $i_suc_id, $i_emp_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_sucursal @i_operacion=?, @i_suc_id=?, @i_suc_estado=?, @i_emp_id =?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_suc_id);
        $query->bindValue(3, 0);
        $query->bindValue(4, $i_emp_id);
        $query->execute();
    }
    /* TODO: Actualizar registro  */
    public function updateSucursal($i_operacion, $i_emp_id, $i_suc_nombre, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_sucursal @i_operacion=?, @i_suc_id=?, @i_suc_nombre=?, @i_emp_id =?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_suc_id);
        $query->bindValue(3, $i_suc_nombre);
        $query->bindValue(4, $i_emp_id);
        $query->execute();
    }
    /* TODO: Insertar nuevo registro */
    public function insertSucursal($i_operacion, $i_emp_id, $i_suc_nombre)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_sucursal @i_operacion=?, @i_emp_id=?, @i_suc_nombre=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_emp_id);
        $query->bindValue(3, $i_suc_nombre);
        $query->execute();
    }
}
