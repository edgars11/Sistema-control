<?php
// TODO: Llamando clases
require_once("../config/conexion.php");
require_once("../models/Compania.php");
// TODO: Inicializando clases
$compania = new Compania();

switch ($_GET['op']) {
        // TODO: Guardar y editar registro
    case 'guardar':
        if (empty($_POST["com_id"])) {
            $compania->insertCompania("I", $_POST['com_nombre']);
        } else {
            $compania->updateCompania("U", $_POST['com_nombre'], $_POST['com_id']);
        }
        break;
        // TODO: Mostrar información del registro por ID
    case 'mostrar':
        $dato = $compania->getCompaniaPorId("R", $_POST['com_id']);
        if (is_array($dato) == true and count($dato) > 0) {
            foreach ($datos as $row) {
                $outout["com_id"] = $row["com_id"];
                $outout["com_nombre"] = $row["com_nombre"];
                $outout["com_fecha_crea"] = $row["com_fecha_crea"];
                $outout["com_estado"] = $row["com_estado"];
            }
            echo json_encode($outout);
        }
        break;
        // TODO: Eliminar registro por id
    case 'eliminar':
        $compania->deleteCompania("D", $_POST['com_id']);
        break;
}
