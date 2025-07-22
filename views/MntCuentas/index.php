<?php
require_once("../../config/conexion.php");
require_once("../../models/Menu.php");
$menu = new Menu();
$datos = $menu->validacionMenuRol($_SESSION["usu_id"], "mntCuentas");
if (isset($_SESSION["usu_id"])) {
    if ($datos[0]['validacion'] === 'S') {
?>
        <!doctype html>
        <html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">

        <head>
            <title>Fact-System | Cuentas</title>
            <?php require_once("../html/head.php"); ?>
        </head>

        <body>

            <!-- Begin page -->
            <div id="layout-wrapper">

                <!-- === Header === -->
                <?php require_once("../html/header.php"); ?>

                <!-- ==== Menu ==== -->
                <?php require_once("../html/menu.php"); ?>

                <!-- ============================================================== -->
                <!-- Start right Content here -->
                <!-- ============================================================== -->
                <div class="main-content">

                    <div class="page-content">
                        <div class="container-fluid">
                            <!-- start page title -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                        <h4 class="mb-sm-0">Listado Cuentas</h4>

                                        <div class="page-title-right">
                                            <ol class="breadcrumb m-0">
                                                <li class="breadcrumb-item"><a href="javascript: void(0);">Cuenta</a></li>
                                                <li class="breadcrumb-item active">Listado</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                                <!-- Datatables ini -->
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-header border-bottom-dashed">
                                                <div class="row g-4 align-items-center">
                                                    <div class="col-sm">
                                                        <div>
                                                            <h5 class="card-title mb-0">Cuenta de Clientes</h5>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-auto">
                                                        <div>
                                                            <button type="button" onclick="addCuenta()" class="btn btn-soft-success"><i class="ri-add-circle-line align-middle me-1"></i> Agregar Cuenta</button>
                                                            <button type="button" onclick="generarReporte()" class="btn btn-outline-warning waves-effect waves-light"><i class="ri-file-download-line align-bottom me-1"></i> Descargar Reporte</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <table id="table_data" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>CLIENTE</th>
                                                        <th>MONTO</th>
                                                        <th>ESTADO</th>
                                                        <th>FECHA</th>
                                                        <th>OBSERVACIÓN</th>
                                                        <th>MOVIMIENTOS</th>
                                                        <th>OPCIONES</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div><!--end col-->

                            </div>
                            <!-- end page title -->
                            <!--end row-->
                            <!-- Datatables fin -->
                        </div>
                        <!-- container-fluid -->
                    </div>
                    <!-- End Page-content -->

                    <?php require_once("../html/footer.php"); ?>
                </div>
                <!-- end main content-->

            </div>
            <!-- END layout-wrapper -->
            <!-- LLamado al Modal -->
            <?php require_once("modal.php") ?>
            <?php require_once("crearCuentaModal.php") ?>
            <?php require_once("modalListaClientes.php") ?>
            <?php require_once("modalUpdate.php") ?>
            <?php require_once("filtroMovimientosModal.php") ?>
            <!-- Fin Modal -->

            <!-- Librerias js -->
            <?php require_once("../html/js.php"); ?>
            <script type="text/javascript" src="mntCuentas.js"></script>
            <!-- Fin librerias js -->
        </body>

        </html>

<?php
    } else {
        header("Location:" . Conectar::ruta() . "views/404/");
    }
} else {
    // No existe sesión
    header("Location:" . Conectar::ruta() . "views/404/");
}
?>