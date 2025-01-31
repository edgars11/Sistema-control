<?php
class Usuario extends Conectar
{
    /* TODO: Listar registro por sucursal */
    public function getUsuarioPorSucursal($i_operacion, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_usuario @i_operacion=?,@i_tipo=?,@i_suc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, 'S');
        $query->bindValue(3, $i_suc_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Listar registro por id */
    public function getUsuarioPorId($i_operacion, $i_usu_id, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_usuario @i_operacion=?,@i_tipo=?,@i_usu_id=?, @i_suc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, 'I');
        $query->bindValue(3, $i_usu_id);
        $query->bindValue(4, $i_suc_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Listar registro por nombre */
    public function getUsuarioPorNombre($i_operacion, $i_usu_nombre, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_usuario @i_operacion=?,@i_tipo=?,@i_usu_nombre=?, @i_suc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, 'I');
        $query->bindValue(3, $i_usu_nombre);
        $query->bindValue(4, $i_suc_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Listar registro por rol */
    public function getUsuarioPorRol($i_operacion, $i_rol_id, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_usuario @i_operacion=?,@i_tipo=?,@i_rol_id=?, @i_suc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, 'R');
        $query->bindValue(3, $i_rol_id);
        $query->bindValue(4, $i_suc_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    /* TODO: Acceso al sistema */
    public function loginUser()
    {
        $conectar = parent::Conexion();
        if(isset($_POST['enviar'])){
            $i_usu_correo = $_POST['usu_correo'];
            $i_usu_password = $_POST['usu_password']; 
            $i_suc_id = $_POST['suc_id'];
        }

        if(empty($i_usu_correo) and empty($i_usu_password) and empty($i_suc_id)){
            exit();
        }

        $sql = "exec sp_crud_usuario @i_operacion=?,@i_usu_correo=?,@i_usu_password=?, @i_suc_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, 'L');
        $query->bindValue(2, $i_usu_correo);
        $query->bindValue(3, $i_usu_password);
        $query->bindValue(4, $i_suc_id);
        $query->execute();
        $resultado =$query->fetch();
        if(is_array($resultado) and count($resultado)>0){
            $_SESSION["usu_id"] = $resultado['usu_id'];
            $_SESSION["suc_id"] = $resultado['suc_id'];
            $_SESSION["com_id"] = $resultado['com_id'];
            $_SESSION["emp_id"] = $resultado['emp_id'];
            $_SESSION["rol_id"] = $resultado['usu_rol_id'];
            $_SESSION["usu_nombre"] = $resultado['usu_nombre'];
            $_SESSION["usu_apellido"] = $resultado['usu_apellido'];
            $_SESSION["usu_correo"] = $resultado['usu_correo'];

            // Si el login es correcto re dirige a la paginaa de inicio
            header("Location:".Conectar::ruta()."views/home/");
        }else{
            exit();
        }

    }
    /* TODO: Listar registro por rol */
    public function updatePassword($i_operacion, $i_usu_password, $i_usu_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_usuario @i_operacion=?, @i_usu_password=?, @i_usu_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_usu_password);
        $query->bindValue(3, $i_usu_id);
        $query->execute();

    }
    /* TODO: Eliminar registro por id */
    public function deleteUsuario($i_operacion, $i_usu_id, $i_suc_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_usuario @i_operacion=?, @i_usu_id=?, @i_suc_id=?, @i_usu_estado=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_usu_id);
        $query->bindValue(3, $i_suc_id);
        $query->bindValue(4, 0);
        $query->execute();
    }
    /* TODO: Actualizar registro  */
    public function updateUsuario($i_operacion,$i_usu_id, $i_usu_nombre,$i_usu_apellido, $i_usu_correo, $i_usu_dni, $i_usu_telefono, $i_usu_password, $i_suc_id, $i_rol_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_usuario @i_operacion=?, @i_usu_id=?, @i_usu_nombre=?, @i_usu_apellido=?, @i_usu_correo=?, @i_usu_dni=?, @i_usu_telefono=?, @i_usu_password=?, @i_suc_id=?, @i_rol_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_usu_id);
        $query->bindValue(3, $i_usu_nombre);
        $query->bindValue(4, $i_usu_apellido);
        $query->bindValue(5, $i_usu_correo);
        $query->bindValue(6, $i_usu_dni);
        $query->bindValue(7, $i_usu_telefono);
        $query->bindValue(8, $i_usu_password);
        $query->bindValue(9, $i_suc_id);
        $query->bindValue(10, $i_rol_id);
        $query->execute();
    }
    /* TODO: Insertar nuevo registro */
    public function insertUsuario($i_operacion, $i_usu_nombre,$i_usu_apellido, $i_usu_correo, $i_usu_dni, $i_usu_telefono, $i_usu_password, $i_suc_id, $i_rol_id)
    {
        $conectar = parent::Conexion();
        $sql = "exec sp_crud_usuario @i_operacion=?, @i_usu_nombre=?, @i_usu_apellido=?, @i_usu_correo=?, @i_usu_dni=?, @i_usu_telefono=?, @i_usu_password=?, @i_suc_id=?, @i_rol_id=?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_operacion);
        $query->bindValue(2, $i_usu_nombre);
        $query->bindValue(3, $i_usu_apellido);
        $query->bindValue(4, $i_usu_correo);
        $query->bindValue(5, $i_usu_dni);
        $query->bindValue(6, $i_usu_telefono);
        $query->bindValue(7, $i_usu_password);
        $query->bindValue(8, $i_suc_id);
        $query->bindValue(9, $i_rol_id);
        $query->execute();
    }
}
