<?php
require_once("../../config/conexion.php");
require_once("../../models/Menu.php");
$menu = new Menu();
$datos = $menu->validacionMenuRol($_SESSION["usu_id"], "mntLote");
if (isset($_SESSION["usu_id"])) {
    if ($datos[0]['validacion'] === 'S') {
?>
        <!doctype html>
        <html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">

        <head>
            <title>Fact-System | Unidad Medida</title>
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
                                        <h4 class="mb-sm-0">Mantenimiento Lotes</h4>

                                        <div class="page-title-right">
                                            <ol class="breadcrumb m-0">
                                                <li class="breadcrumb-item"><a href="javascript: void(0);">Mantenimiento</a></li>
                                                <li class="breadcrumb-item active">Lotes</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>

                                <!-- Datatables ini -->
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="col-sm-8">
                                                <div class="">
                                                    <!-- <input type="text" class="form-control" id="datepicker-range" data-provider="flatpickr" data-date-format="d M, Y" data-range-date="true" placeholder="Seleccionar fecha"> -->
                                                    <form id="form_fecha" method="post">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="mov_fecha_desde"><b>Del día</b></label>
                                                                    <input type="date" name="mov_fecha_desde" id="mov_fecha_desde" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="mov_fecha_hasta"><b>Hasta el día</b></label>
                                                                    <input type="date" name="mov_fecha_hasta" id="mov_fecha_hasta" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="mov_fecha_hasta"><b>&nbsp;</b></label> <br>
                                                                    <button type="submit" class="btn btn-primary">Buscar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>

                                        </div>
                                        <div class="card-body">
                                            <table id="table_data" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>NOMBRE LOTE</th>
                                                        <th>FECHA MOVIMIENTO</th>
                                                        <th>CANTIDAD</th>
                                                        <th>TIPO MOVIMIENTO</th>
                                                        <th>USUARIO</th>
                                                        <th>OBSERVACIÓN</th>
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
            <?php require_once("modalIngreso.php") ?>
            <!-- Fin Modal -->

            <!-- Librerias js -->
            <?php require_once("../html/js.php"); ?>
            <script type="text/javascript" src="movLote.js"></script>
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