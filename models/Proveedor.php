<?php
class Proveedor extends Conectar
{
    /* TODO: Listar registro por empresa */
    public function getProveedorPorEmpresa($i_operacion, $i_emp_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_proveedor @i_operacion=?, @i_tipo=?, @i_emp_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, 'E');
        $query->bindValue(3, $i_emp_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Listar registro por id */
    public function getProveedorPorId($i_operacion, $i_prov_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_proveedor @i_operacion=?, @i_tipo=?, @i_prov_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, 'I');
        $query->bindValue(3, $i_prov_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Listar registro por ruc */
    public function getProveedorPorRuc($i_operacion, $i_prov_ruc, $i_emp_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_proveedor @i_operacion=?, @i_tipo=?, @i_prov_ruc=?, @i_emp_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, 'C');
        $query->bindValue(3, $i_prov_ruc);
        $query->bindValue(4, $i_emp_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Listar registro por nombre */
    public function getProveedorPorNombre($i_operacion, $i_prov_nombre, $i_emp_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_proveedor @i_operacion=?, @i_tipo=?, @i_prov_nombre=?, @i_emp_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, 'N');
        $query->bindValue(3, $i_prov_nombre);
        $query->bindValue(4, $i_emp_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Eliminar registro por id */
    public function deleteProveedor($i_operacion, $i_prov_id, $i_emp_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_proveedor @i_operacion=?, @i_prov_id=?, @i_prov_estado=?, @i_emp_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_prov_id);
        $query->bindValue(3, 0);
        $query->bindValue(4, $i_emp_id);
        $query->execute();
    }
    /* TODO: Actualizar registro  */
    public function updateProveedor($i_operacion, $i_emp_id, $i_prov_nombre, $i_prov_ruc, $i_prov_telefono, $i_prov_direccion, $i_prov_correo, $i_prov_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_proveedor @i_operacion=?, @i_emp_id=?, @i_prov_nombre=?, @i_prov_ruc=?, @i_prov_telefono=?, @i_prov_direccion=?, @i_prov_correo=?, @i_prov_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_emp_id);
        $query->bindValue(3, $i_prov_nombre);
        $query->bindValue(4, $i_prov_ruc);
        $query->bindValue(5, $i_prov_telefono);
        $query->bindValue(6, $i_prov_direccion);
        $query->bindValue(7, $i_prov_correo);
        $query->bindValue(8, $i_prov_id);
        $query->execute();
    }
    /* TODO: Insertar nuevo registro */
    public function insertProveedor($i_operacion, $i_emp_id, $i_prov_nombre, $i_prov_ruc, $i_prov_telefono, $i_prov_direccion, $i_prov_correo)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_proveedor @i_operacion=?, @i_emp_id=?, @i_prov_nombre=?, @i_prov_ruc=?, @i_prov_telefono=?, @i_prov_direccion=?, @i_prov_correo=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_emp_id);
        $query->bindValue(3, $i_prov_nombre);
        $query->bindValue(4, $i_prov_ruc);
        $query->bindValue(5, $i_prov_telefono);
        $query->bindValue(6, $i_prov_direccion);
        $query->bindValue(7, $i_prov_correo);
        $query->execute();
    }
}
