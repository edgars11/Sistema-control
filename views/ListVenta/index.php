<?php
require_once("../../config/conexion.php");
require_once("../../models/Menu.php");
$menu = new Menu();
$datos = $menu->validacionMenuRol($_SESSION["usu_id"], "listVenta");
if (isset($_SESSION["usu_id"])) {
    if ($datos[0]['validacion'] === 'S') {
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
                                        <h4 class="mb-sm-0">Listado Ventas</h4>

                                        <div class="page-title-right">
                                            <ol class="breadcrumb m-0">
                                                <li class="breadcrumb-item"><a href="javascript: void(0);">Listado</a></li>
                                                <li class="breadcrumb-item active">Ventas</li>
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
                                                            <button class="btn btn-soft-success" id="btnWhatsappCli" onclick="openWhatsappview()"><i class="ri-whatsapp-line align-bottom me-1"></i>Abrir whatsapp cliente</button>
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
                                                        <div class="col-xl-3">
                                                            <div class="row">
                                                                <div class="col-sm-9">
                                                                    <label for="cli_nombre" class="form-label">Cliente:</label>
                                                                    <input type="text" class="form-control" placeholder="Nombre Cliente" id="cli_nombre" readonly>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <label for="buscarCliente" class="form-label">Buscar</label>
                                                                    <button type="button" onclick="resetClient()" class="btn btn-primary w-100" id="buscarCliente"><i class="ri-user-search-line label-icon align-middle"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-3">
                                                            <label for="tipo_pago" class="form-label">Tipo Pago:</label>
                                                            <select type="text" class="form-control form-select" name="tipo_pago" id="tipo_pago" aria-label="Seleccionar">
                                                                <option selected>Seleccionar</option>
                                                                <option value='PV'>POLLO VIVO</option>
                                                                <option value='PF'>POLLO FAENADO</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-xl-6">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="fecha_ini"><b>Fecha inicio</b></label>
                                                                        <input type="date" name="fecha_ini" id="fecha_ini" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="fecha_hasta"><b>Fecha fin</b></label>
                                                                        <input type="date" name="fecha_hasta" id="fecha_hasta" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="btn_search"><b>&nbsp;</b></label> <br>
                                                                        <button id="btn_search" class="btn btn-primary w-100">Buscar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <table id="table_data" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>NRO</th>
                                                        <th>CLIENTE</th>
                                                        <th>IDENTIFICACION</th>
                                                        <th>FORMA PAGO</th>
                                                        <th>TOTAL</th>
                                                        <th>FECHA</th>
                                                        <th>USUARIO</th>
                                                        <th>OPCIONES</th>
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
            <?php require_once("modalClient.php") ?>
            <!-- Fin Modal -->

            <!-- Librerias js -->
            <?php require_once("../html/js.php"); ?>
            <script type="text/javascript" src="listVenta.js"></script>
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