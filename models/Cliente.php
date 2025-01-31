<?php
class Cliente extends Conectar
{
    /* TODO: Listar registro por empresa */
    public function getClientePorEmpresa($i_operacion, $i_emp_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_cliente @i_operacion=?, @i_tipo=?, @i_emp_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, 'E');
        $query->bindValue(3, $i_emp_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Listar registro por id */
    public function getClientePorId($i_cli_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_cliente @i_operacion=?, @i_tipo=?, @i_cli_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'R');
        $query->bindValue(2, 'I');
        $query->bindValue(3, $i_cli_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Listar registro por ruc */
    public function getClientePorRuc($i_operacion, $i_cli_ruc, $i_emp_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_cliente @i_operacion=?, @i_tipo=?, @i_cli_ruc=?, @i_emp_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, 'C');
        $query->bindValue(3, $i_cli_ruc);
        $query->bindValue(4, $i_emp_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Listar registro por nombre */
    public function getClientePorNombre($i_operacion, $i_cli_nombre, $i_emp_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_cliente @i_operacion=?, @i_tipo=?, @i_cli_nombre=?, @i_emp_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, 'N');
        $query->bindValue(3, $i_cli_nombre);
        $query->bindValue(4, $i_emp_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Eliminar registro por id */
    public function deleteCliente($i_operacion, $i_cli_id, $i_emp_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_cliente @i_operacion=?, @i_cli_id=?, @i_cli_estado=?, @i_emp_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_cli_id);
        $query->bindValue(3, 0);
        $query->bindValue(4, $i_emp_id);
        $query->execute();
    }
    /* TODO: Actualizar registro  */
    public function updateCliente($i_operacion, $i_emp_id, $i_cli_nombre, $i_cli_ruc, $i_cli_telefono, $i_cli_direccion, $i_cli_correo, $i_cli_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_cliente @i_operacion=?, @i_emp_id=?, @i_cli_nombre=?, @i_cli_ruc=?, @i_cli_telefono=?, @i_cli_direccion=?, @i_cli_correo=?, @i_cli_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_emp_id);
        $query->bindValue(3, $i_cli_nombre);
        $query->bindValue(4, $i_cli_ruc);
        $query->bindValue(5, $i_cli_telefono);
        $query->bindValue(6, $i_cli_direccion);
        $query->bindValue(7, $i_cli_correo);
        $query->bindValue(8, $i_cli_id);
        $query->execute();
    }
    /* TODO: Insertar nuevo registro */
    public function insertCliente($i_operacion, $i_emp_id, $i_cli_nombre, $i_cli_ruc, $i_cli_telefono, $i_cli_direccion, $i_cli_correo)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_cliente @i_operacion=?, @i_emp_id=?, @i_cli_nombre=?, @i_cli_ruc=?, @i_cli_telefono=?, @i_cli_direccion=?, @i_cli_correo=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_emp_id);
        $query->bindValue(3, $i_cli_nombre);
        $query->bindValue(4, $i_cli_ruc);
        $query->bindValue(5, $i_cli_telefono);
        $query->bindValue(6, $i_cli_direccion);
        $query->bindValue(7, $i_cli_correo);
        $query->execute();
    }
}
