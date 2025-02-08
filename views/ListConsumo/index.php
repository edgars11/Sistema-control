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
            <title>Fact-System | Listado Consumo</title>
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
                                        <h4 class="mb-sm-0">Consumo Lote</h4>

                                        <div class="page-title-right">
                                            <ol class="breadcrumb m-0">
                                                <li class="breadcrumb-item"><a href="javascript: void(0);">Lote</a></li>
                                                <li class="breadcrumb-item active">Consumo</li>
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
                                                            <h5 class="card-title mb-0">Filtro Búsqueda</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body border-bottom-dashed border-bottom">
                                                <!-- TODO: Id de compra -->
                                                <input type="hidden" name="cli_id" id="cli_id" />
                                                <form>
                                                    <div class="row g-3">
                                                        <div class="col-xl-4">
                                                            <label for="lote_idIng" class="form-label">Lote:</label>
                                                            <select type="text" class="form-control form-select" name="lote_idIng" id="lote_idIng" aria-label="Seleccionar">
                                                                <option selected>Seleccionar Lote</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-xl-6">
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <label for="fecha_desde" class="form-label">Desde:</label>
                                                                    <input type="date" name="fecha_desde" id="fecha_desde" class="form-control">
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <label for="fecha_hasta" class="form-label">Hasta:</label>
                                                                    <input type="date" name="fecha_hasta" id="fecha_hasta" class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-2">
                                                            <label for="btnFiltro" class="form-label">Opcion:</label>
                                                            <button type="button" class="btn btn-primary w-100" id="btnFiltro"> <i class="ri-equalizer-fill me-2 align-bottom"></i>Filters</button>
                                                        </div>
                                                        <!--end col-->
                                                    </div>
                                                    <!--end row-->
                                                </form>
                                            </div>

                                        </div>
                                        <div class="card-body">
                                            <table id="table_data" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>NOMBRE LOTE</th>
                                                        <th>FECHA MOVIMIENTO</th>
                                                        <th>CANTIDAD</th>
                                                        <th>USUARIO</th>
                                                        <th>OBSERVACIÓN</th>
                                                        <th>HORA REGISTRO</th>
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
            <!-- Fin Modal -->

            <!-- Librerias js -->
            <?php require_once("../html/js.php"); ?>
            <script type="text/javascript" src="listConsumo.js"></script>
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