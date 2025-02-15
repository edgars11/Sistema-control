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
                                            <button type="button" id="btn_nuevo" class="btn btn-primary btn-label waves-effect right waves-light">
                                                <i class="ri-user-smile-line label-icon align-middle fs-16 ms-2"></i> Nuevo Registro
                                            </button>
                                            <button type="button" id="btn_ingreso" class="btn btn-success btn-label waves-effect right waves-light">
                                                <i class="ri-add-circle-line label-icon align-middle fs-16 ms-2"></i> Ingresar Producto Lote
                                            </button>
                                            <button type="button" id="btn_perdida" class="btn btn-warning btn-label waves-effect right waves-light">
                                                <i class="ri-indeterminate-circle-line label-icon align-middle fs-16 ms-2"></i> Ingresar Perdida Lote
                                            </button>
                                            <button type="button" id="btn_alimento" class="btn btn-info btn-label waves-effect right waves-light">
                                                <i class="ri-add-circle-line label-icon align-middle fs-16 ms-2"></i> Ingresar Alimento Lote
                                            </button>

                                        </div>
                                        <div class="card-body">
                                            <table id="table_data" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>NOMBRE LOTE</th>
                                                        <th>CAPACIDAD MAX</th>
                                                        <th>CANT. ACTUAL</th>
                                                        <th>CANT. PERDIDA</th>
                                                        <th>CANT. ALIMENTO</th>
                                                        <th>CANT. VENDIDOS</th>
                                                        <th>FECHA ACTUALIZACION</th>
                                                        <th>ESTADO</th>
                                                        <th>FECHA ULT INGRESO</th>
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

                            <!-- Modal -->
                            <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="btn-close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mt-2 text-center">
                                                <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                                                <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                                                    <h4>Are you sure ?</h4>
                                                    <p class="text-muted mx-4 mb-0">Are you sure you want to remove this record ?</p>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                                <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn w-sm btn-danger" id="delete-record">Yes, Delete It!</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end modal -->

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
            <?php require_once("modalIngAlimento.php") ?>
            <!-- Fin Modal -->

            <!-- Librerias js -->
            <?php require_once("../html/js.php"); ?>
            <script type="text/javascript" src="mntLote.js"></script>
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