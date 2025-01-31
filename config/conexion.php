<?php
    session_start();
    class Conectar {
        protected $dbh;
        protected function Conexion(){
            $server='DESKTOP-HDDG56J\SQLEXPRESS';
            $database='SistemaControl';
            $username='sa';
            $password='Passw0rd*';
            try {
                $conectar = $this->dbh = new PDO("sqlsrv:Server=$server;Database=$database", $username, $password);
                $conectar -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $conectar;
            } catch (Exception $e) {
                print "Error Conexion BD ". $e->getMessage() . "<br/>";
                die();
            }
        }

        public static function ruta(){
            return "http://localhost/Sistema-Control/";
        }
    }
?>
