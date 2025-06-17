<?php
session_start();
class Conectar
{
    protected $dbh;
    protected function Conexion()
    {
        $server = 'DESKTOP-HDDG56J\SQLEXPRESS';
        $database = 'SistemaControl';
        $username = 'sa';
        $password = 'Passw0rd*';
        try {
            $conectar = $this->dbh = new PDO("sqlsrv:Server=$server;Database=$database", $username, $password);
            $conectar->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conectar;
        } catch (Exception $e) {
            print "Error Conexion BD " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public static function ruta()
    {
        return "http://localhost/Sistema-Control/";
    }
}

// class conecction
// {
//     protected function connect()
//     {
//         $serverName = "localhost";
//         $connectionInfo = array("Database" => "MiBaseDeDatos", "UID" => "usuario", "PWD" => "contraseña");
//         $conn = sqlsrv_connect($serverName, $connectionInfo);

//         if ($conn === false) {
//             die(print_r(sqlsrv_errors(), true));
//         } else {
//             return $conn;
//         }

//         // Consulta con un error intencional (columna inexistente)
//         // $sql = "SELECT ColumnaInvalida FROM MiTabla";
//         // $stmt = sqlsrv_query($conn, $sql);

//         // if ($stmt === false) {
//         //     if (($errors = sqlsrv_errors()) != null) {
//         //         foreach ($errors as $error) {
//         //             echo "SQLSTATE: " . $error['SQLSTATE'] . "<br />";
//         //             echo "Código: " . $error['code'] . "<br />";
//         //             echo "Mensaje: " . $error['message'] . "<br />";
//         //         }
//         //     }
//         // } else {
//         // }

//         // sqlsrv_close($conn);
//     }
// }
