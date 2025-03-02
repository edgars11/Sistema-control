<?php
require_once("../../config/conexion.php");
require_once("../../models/Menu.php");
$menu = new Menu();
$datos = $menu->validacionMenuRol($_SESSION["usu_id"], "listSalidaLote");
if (isset($_SESSION["usu_id"])) {
    if ($datos[0]['validacion'] === 'S') {
?>
        <!doctype html>
        <html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">

        <head>
            <title>Fact-System | Salida Lote</title>
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
                                        <h4 class="mb-sm-0">Listado Cobro Cuentas</h4>

                                        <div class="page-title-right">
                                            <ol class="breadcrumb m-0">
                                                <li class="breadcrumb-item"><a href="javascript: void(0);">Listado</a></li>
                                                <li class="breadcrumb-item active">Cobros</li>
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
                                                        <div class="col-xl-3">
                                                            <label for="pago_id" class="form-label">Tipo Pago:</label>
                                                            <select type="text" class="form-control form-select" name="pago_id" id="pago_id" aria-label="Seleccionar">
                                                                <option selected>Seleccionar</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-xl-4">
                                                            <div class="row">
                                                                <div class="col-sm-9">
                                                                    <label for="cli_nombre" class="form-label">Cliente:</label>
                                                                    <input type="text" class="form-control" placeholder="Nombre Cliente" id="cli_nombre" readonly>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <label for="buscarCliente" class="form-label">Buscar</label>
                                                                    <button type="button" class="btn btn-primary w-100" id="buscarCliente"><i class="ri-user-search-line label-icon align-middle"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-3">
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
                                            <table id="tb_listadoSalida" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>CUENTA #</th>
                                                        <th>CLIENTE</th>
                                                        <th>TIPO PAGO</th>
                                                        <th>MONTO</th>
                                                        <th>OBSERVACIÓN</th>
                                                        <th>FECHA</th>
                                                        <th>USUARIO</th>
                                                        <th>OPCIONES</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                    <th></th>
                                                    <th></th>
                                                    <th class="fs-14 text-white bg-dark">TOTAL:</th>
                                                    <th id="total_vendido" class="fs-15 text-white bg-dark">TOTAL MONTO</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                </tfoot>
                                            </table>
                                            <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                                                <!-- <a href="javascript:window.print()" class="btn btn-success"><i class="ri-printer-line align-bottom me-1"></i> Print</a>
                                                <a href="javascript:void(0);" class="btn btn-primary"><i class="ri-download-2-line align-bottom me-1"></i> Download</a> -->
                                                <button type="button" id="" class="btn btn-info btn-icon waves-effect waves-light"><i class="ri-eye-fill"></i>Generar Reporte</button>
                                            </div>
                                        </div>

                                    </div>
                                </div><!--end col-->
                                <!-- Datatables fin -->

                            </div>
                            <!-- end page title -->
                            <!--end row-->
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
            <?php require_once("modalUpdate.php") ?>
            <!-- Fin Modal -->

            <!-- Librerias js -->
            <?php require_once("../html/js.php"); ?>
            <script type="text/javascript" src="listCoboCta.js"></script>
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