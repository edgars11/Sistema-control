<?php
    require_once("../../config/conexion.php");
    header("Location:".Conectar::ruta()."?c=".$_SESSION['com_id']);
    session_destroy();
    exit();
?>