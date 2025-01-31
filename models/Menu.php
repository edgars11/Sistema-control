<?php
class Menu extends Conectar
{
    /* TODO: Listar registro por id */
    public function getMenuPorRol($i_operacion, $i_rol_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_rol @i_operacion=?,@i_rol_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_rol_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Listar registro por id */
    public function validaInsertMenuPorRol($i_rol_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_menu @i_operacion = ?, @i_rol_id = ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'I');
        $query->bindValue(2, $i_rol_id);
        $query->execute();
    }

    /* TODO: Actualizar permiso de menu */
    public function updateMenuPorRol($i_operacion, $i_rol_id, $i_mend_id, $i_menu_permi)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_rol @i_operacion=?,@i_rol_id=?, @i_mend_id=?, @i_menu_permi=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_rol_id);
        $query->bindValue(3, $i_mend_id);
        $query->bindValue(4, $i_menu_permi);
        $query->execute();
    }
    /* TODO: Listar registro por id */
    public function validacionMenuRol($i_usu_id, $i_men_identi)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_menu @i_operacion =?, @i_tipo = ? , @i_usu_id = ? , @i_men_identi = ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, "V");
        $query->bindValue(2, "I");
        $query->bindValue(3, $i_usu_id);
        $query->bindValue(4, $i_men_identi);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
