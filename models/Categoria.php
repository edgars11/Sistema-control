<?php
class Categoria extends Conectar
{
    /* TODO: Listar registro por sucursal */
    public function getCategoriaPorSucursal($i_operacion, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_categoria @i_operacion=?,@i_tipo=?,@i_suc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, 'S');
        $query->bindValue(3, $i_suc_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Listar registro por id */
    public function getCategoriaPorId($i_operacion, $i_cat_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_categoria @i_operacion=?,@i_tipo=?,@i_cat_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, 'I');
        $query->bindValue(3, $i_cat_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Eliminar registro por id */
    public function deleteCategoria($i_operacion, $i_cat_id, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_categoria @i_operacion=?, @i_cat_id=?, @i_cat_estado=?, @i_suc_id= ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_cat_id);
        $query->bindValue(3, 0);
        $query->bindValue(4, $i_suc_id);
        $query->execute();
    }
    /* TODO: Insertar nuevo registro */
    public function insertCategoria($i_operacion, $i_suc_id, $i_cat_nombre)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_categoria @i_operacion=?, @i_suc_id=?, @i_cat_nombre=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_suc_id);
        $query->bindValue(3, $i_cat_nombre);
        $query->execute();
    }
    /* TODO: Actualizar registro  */
    public function updateCategoria($i_operacion, $i_suc_id, $i_cat_nombre, $i_cat_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_categoria @i_operacion=?, @i_suc_id=?, @i_cat_nombre=?, @i_cat_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_suc_id);
        $query->bindValue(3, $i_cat_nombre);
        $query->bindValue(4, $i_cat_id);
        $query->execute();
    }
}
