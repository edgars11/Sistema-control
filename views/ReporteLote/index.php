<?php
require_once("../../config/conexion.php");
require_once("../../models/Menu.php");
$menu = new Menu();
$datos = $menu->validacionMenuRol($_SESSION["usu_id"], "reporteLote");
if (isset($_SESSION["usu_id"])) {
    if ($datos[0]['validacion'] === 'S') {
?>
        <!doctype html>
        <html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">

        <head>
            <title>Fact-System | Reportes Lotes</title>
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
                                        <h4 class="mb-sm-0">Reporte Lote</h4>

                                        <div class="page-title-right">
                                            <ol class="breadcrumb m-0">
                                                <li class="breadcrumb-item"><a href="javascript: void(0);">Reporte</a></li>
                                                <li class="breadcrumb-item active">Lote</li>
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
                                                    <div class="col-sm-auto">

                                                        <div>
                                                            <button class="btn btn-soft-danger" onclick="resetClient()"><i class="ri-delete-bin-2-line align-bottom me-1"></i>Limpiar Cliente</button>
                                                            <button type="button" onclick="generarReporte()" class="btn btn-outline-warning waves-effect waves-light"><i class="ri-file-download-line align-bottom me-1"></i> Descargar Reporte</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body border-bottom-dashed border-bottom">
                                                <!-- TODO: Id de compra -->
                                                <input type="hidden" name="cli_id" id="cli_id" />
                                                <form>
                                                    <div class="row g-3">
                                                        <div class="col-xl-2">
                                                            <label for="rep_periodo" class="form-label">Periodo Ingreso:</label>
                                                            <select type="text" class="form-control form-select" name="rep_periodo" id="rep_periodo" aria-label="Seleccionar">
                                                                <option value='U' selected>Último Periodo</option>
                                                                <option value='L'>Todos los periodos</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-xl-2">
                                                            <label for="lote_idIng" class="form-label">Lote:</label>
                                                            <select type="text" class="form-control form-select" name="lote_idIng" id="lote_idIng" aria-label="Seleccionar">
                                                                <option selected>Seleccionar Lote</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-xl-2">
                                                            <label for="repor_year" class="form-label">Año:</label>
                                                            <select type="text" class="form-control form-select" name="repor_year" id="repor_year" aria-label="Seleccionar">
                                                                <?php
                                                                for ($year = 2020; $year <= date('Y'); $year++) {
                                                                    echo '<option value="' . $year . '">' . $year . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-xl-2">
                                                            <label for="list_periodos" class="form-label">Buscar</label>
                                                            <button type="button" class="btn btn-warning w-100" id="list_periodos"><i class="ri-calendar-check-fill me-2 align-bottom"></i>Listado</button>
                                                        </div>

                                                        <div class="col-xl-2">
                                                            <label for="rep_fecha" class="form-label">Fecha Inicio:</label>
                                                            <input type="text" class="form-control" id="rep_fecha" name="rep_fecha" placeholder="Seleccione" readonly>
                                                        </div>

                                                        <div class="col-xl-2">
                                                            <label for="rep_fecha_fin" class="form-label">Fecha Fin:</label>
                                                            <input type="text" class="form-control" id="rep_fecha_fin" name="c" placeholder="0000-00-00">
                                                        </div>

                                                        <div class="col-xl-2">
                                                            <label for="btnFiltro" class="form-label">Opcion:</label>
                                                            <button type="button" class="btn btn-primary w-100" id="btnFiltro"> <i class="ri-equalizer-fill me-2 align-bottom"></i>Generar Reporte</button>
                                                        </div>
                                                        <!--end col-->
                                                    </div>
                                                    <!--end row-->
                                                </form>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-xl-2 col-md-6">
                                                <!-- card -->
                                                <div class="card card-animate">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-grow-1 overflow-hidden">
                                                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Total Ventas</p>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-end justify-content-between mt-4">
                                                            <div>
                                                                <h4 class="fs-22 fw-semibold text-success ff-secondary mb-4">$<span id="total_ventas" class="counter-value" data-target="0">0</span></h4>
                                                                
                                                            </div>
                                                            <div class="avatar-sm flex-shrink-0">
                                                                <span class="avatar-title bg-soft-success rounded fs-3">
                                                                    <i class="bx bx-dollar-circle text-success"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div><!-- end card body -->
                                                </div><!-- end card -->
                                            </div><!-- end col -->

                                            <div class="col-xl-2 col-md-6">
                                                <!-- card -->
                                                <div class="card card-animate">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-grow-1 overflow-hidden">
                                                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">CANTIDAD VENDIDA</p>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-end justify-content-between mt-4">
                                                            <div>
                                                                <h4 class="fs-22 fw-semibold text-danger ff-secondary mb-4"><span id="cantidad_ventas" class="counter-value" data-target="0">0</span> Uni</h4>
                                                                
                                                            </div>
                                                            <div class="avatar-sm flex-shrink-0">
                                                                <span class="avatar-title bg-soft-info rounded fs-3">
                                                                    <i class="bx bx-shopping-bag text-info"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div><!-- end card body -->
                                                </div><!-- end card -->
                                            </div><!-- end col -->

                                            <div class="col-xl-2 col-md-6">
                                                <!-- card -->
                                                <div class="card card-animate">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-grow-1 overflow-hidden">
                                                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">PESO NETO</p>
                                                            </div>

                                                        </div>
                                                        <div class="d-flex align-items-end justify-content-between mt-4">
                                                            <div>
                                                                <h4 class="fs-22 fw-semibold text-primary ff-secondary mb-4"><span id="peso_neto" class="counter-value" data-target="0">0</span> Lbs</h4>
                                                                
                                                            </div>
                                                            <div class="avatar-sm flex-shrink-0">
                                                                <span class="avatar-title bg-soft-warning rounded fs-3">
                                                                    <i class="bx bx-user-circle text-warning"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div><!-- end card body -->
                                                </div><!-- end card -->
                                            </div><!-- end col -->

                                            <div class="col-xl-2 col-md-6">
                                                <!-- card -->
                                                <div class="card card-animate">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-grow-1 overflow-hidden">
                                                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> CONSUMO LOTE</p>
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="d-flex align-items-end justify-content-between mt-4">
                                                            <div>
                                                                <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span id="consumo_lote" class="counter-value" data-target="0">0</span> Sacos</h4>
                                                                
                                                            </div>
                                                            <div class="avatar-sm flex-shrink-0">
                                                                <span class="avatar-title bg-soft-primary rounded fs-3">
                                                                    <i class="bx bx-wallet text-primary"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div><!-- end card body -->
                                                </div><!-- end card -->
                                            </div><!-- end col -->

                                            <div class="col-xl-2 col-md-6">
                                                <!-- card -->
                                                <div class="card card-animate">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-grow-1 overflow-hidden">
                                                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> PÉRDIDA LOTE</p>
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="d-flex align-items-end justify-content-between mt-4">
                                                            <div>
                                                                <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span id="perdida_lote" class="counter-value" data-target="0">0</span> Uni</h4>
                                                                
                                                            </div>
                                                            <div class="avatar-sm flex-shrink-0">
                                                                <span class="avatar-title bg-soft-primary rounded fs-3">
                                                                    <i class="bx bx-wallet text-primary"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div><!-- end card body -->
                                                </div><!-- end card -->
                                            </div><!-- end col -->

                                            <div class="col-xl-2 col-md-6">
                                                <!-- card -->
                                                <div class="card card-animate">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-grow-1 overflow-hidden">
                                                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> INDICE DE CONVERSIÓN</p>
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="d-flex align-items-end justify-content-between mt-4">
                                                            <div>
                                                                <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="100">0</span> %</h4>
                                                                
                                                            </div>
                                                            <div class="avatar-sm flex-shrink-0">
                                                                <span class="avatar-title bg-soft-primary rounded fs-3">
                                                                    <i class="bx bx-wallet text-primary"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div><!-- end card body -->
                                                </div><!-- end card -->
                                            </div><!-- end col -->
                                        </div> <!-- end row-->

                                        <div class="card-body">
                                            <table id="tb_listadoReporte" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>LOTE</th>
                                                        <th>FECHA</th>
                                                        <th>CANTIDAD</th>
                                                        <th>PESO NETO</th>
                                                        <th>TOTAL MONTO</th>
                                                        <th>ALIMENTO</th>
                                                        <th>PÉRDIDA</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div><!--end col-->
                                <!-- Datatables fin -->
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
            <script type="text/javascript" src="reporteLote.js"></script>
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