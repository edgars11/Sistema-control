<?php
class Utils extends Conectar
{
    /* TODO: Listar registro por sucursal */
    public function getParam($i_nemonico)
    {
        $conectar = parent::Conexion();
        $sql = "select * from tm_parametros where par_nemonico = ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $i_nemonico);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

}
