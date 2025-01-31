<?php
require_once("../../config/conexion.php");
require_once("../../models/Menu.php");
$menu = new Menu();
$datos = $menu->validacionMenuRol($_SESSION["usu_id"], "mntProveedor");
if (isset($_SESSION["usu_id"])) {
    if ($datos[0]['validacion'] === 'S') {
?>
    <!doctype html>
    <html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">

    <head>
        <title>Fact-System | Proveedor</title>
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
                                    <h4 class="mb-sm-0">Mantenimiento Proveedor</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Proveedor</a></li>
                                            <li class="breadcrumb-item active">Mantenimiento</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <!-- Datatables ini -->
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <button type="button" id="btn_nuevo" class="btn btn-primary btn-label waves-effect right waves-light">
                                            <i class="ri-user-smile-line label-icon align-middle fs-16 ms-2"></i> Nuevo Registro
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <table id="table_data" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nombre</th>
                                                    <th>Ruc</th>
                                                    <th>Teléfono</th>
                                                    <th>Dirección</th>
                                                    <th>Correo</th>
                                                    <th>Fecha Creación</th>
                                                    <th>Estado</th>
                                                    <th>Modificar</th>
                                                    <th>Eliminar</th>
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
        <!-- Fin Modal -->

        <!-- Librerias js -->
        <?php require_once("../html/js.php"); ?>
        <script type="text/javascript" src="mntProveedor.js"></script>
        <!-- Fin librerias js -->
    </body>

    </html>

<?php
    } 
    else {
        header("Location:" . Conectar::ruta() . "views/404/");
    }
} else {
    // No existe sesión
    header("Location:" . Conectar::ruta() . "views/404/");
}
?>