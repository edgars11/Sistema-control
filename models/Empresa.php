<?php
class Empresa extends Conectar
{
    /* TODO: Listar registro por compania */
    public function getEmpresaPorCompania($i_operacion, $i_com_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_empresa @i_operacion=?, @i_tipo=?, @i_com_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, 'T');
        $query->bindValue(3, $i_com_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Listar registro por id */
    public function getEmpresaPorId($i_operacion, $i_emp_id, $i_com_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_empresa @i_operacion=?, @i_tipo=?, @i_emp_id=?, @i_com_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, 'I');
        $query->bindValue(3, $i_emp_id);
        $query->bindValue(4, $i_com_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Listar registro por ruc */
    public function getEmpresaPorRuc($i_operacion, $i_emp_ruc, $i_com_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_empresa @i_operacion=?, @i_tipo=?,@i_emp_ruc=?, @i_com_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, 'R');
        $query->bindValue(3, $i_emp_ruc);
        $query->bindValue(4, $i_com_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Eliminar registro por id */
    public function deleteEmpresa($i_operacion, $i_emp_id, $i_com_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_empresa @i_operacion=?, @i_emp_id=?, @i_emp_estado=?, @i_com_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_emp_id);
        $query->bindValue(3, 0);
        $query->bindValue(4, $i_com_id);
        $query->execute();
    }
    /* TODO: Actualizar registro  */
    public function updateEmpresa($i_operacion, $i_emp_id, $i_com_id, $i_emp_nombre, $i_emp_ruc)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_empresa @i_operacion=?, @i_com_id=?, @i_emp_nombre=?, @i_emp_ruc=?, @i_emp_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_com_id);
        $query->bindValue(3, $i_emp_nombre);
        $query->bindValue(4, $i_emp_ruc);
        $query->bindValue(5, $i_emp_id);
        $query->execute();
    }
    /* TODO: Insertar nuevo registro */
    public function insertEmpresa($i_operacion, $i_com_id, $i_emp_nombre, $i_emp_ruc)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_empresa @i_operacion=?, @i_com_id=?, @i_emp_nombre=?, @i_emp_ruc=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_com_id);
        $query->bindValue(3, $i_emp_nombre);
        $query->bindValue(4, $i_emp_ruc);
        $query->execute();
    }
}
