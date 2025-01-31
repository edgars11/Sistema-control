<?php
require_once("../../config/conexion.php");
if (isset($_SESSION["usu_id"])) {
?>
    <!doctype html>
    <html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">

    <head>
        <title>Fact-System | Roles</title>
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
                                    <h4 class="mb-sm-0">Mantenimiento Perfil</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Perfil</a></li>
                                            <li class="breadcrumb-item active">Mantenimiento</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header align-items-center d-flex">
                                            <h4 class="card-title mb-0 flex-grow-1">Cambio de Contraseña</h4>
                                        </div><!-- end card header -->
                                        <div class="card-body">
                                            <div class="live-preview">
                                                <div class="row gy-4">
                                                    <div class="col-xxl-3 col-md-6">
                                                        <div>
                                                            <label for="basiInput" class="form-label">Nueva Contraseña</label>
                                                            <input type="password" class="form-control" id="txtpass" required>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                    <div class="col-xxl-3 col-md-6">
                                                        <div>
                                                            <label for="labelInput" class="form-label">Confirmar Contraseña</label>
                                                            <input type="password" class="form-control" id="txtpassconfirm" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-xxl-3 col-md-6">
                                                        <div>
                                                            <label for="labelInput" class="form-label">&nbsp;</label>
                                                            <button type="button" id="btnupdpass" class="form-control btn btn-warning waves-effect waves-light">Actualizar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

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

        <!-- Librerias js -->
        <?php require_once("../html/js.php"); ?>
        <script type="text/javascript" src="mntPerfil.js"></script>
        <!-- Fin librerias js -->
    </body>

    </html>

<?php
} else {
    // No existe sesión
    header("Location:" . Conectar::ruta() . "views/404/");
}
?>