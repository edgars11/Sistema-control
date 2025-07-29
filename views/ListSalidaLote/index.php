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
            <title>Fact-System | Listado Pedidos</title>
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
                                        <h4 class="mb-sm-0">Listado Pedidos Lote</h4>

                                        <div class="page-title-right">
                                            <ol class="breadcrumb m-0">
                                                <li class="breadcrumb-item"><a href="javascript: void(0);">Listado</a></li>
                                                <li class="breadcrumb-item active">Pedidos</li>
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
                                                            <button class="btn btn-soft-success" id="btnWhatsappCli" onclick="openWhatsappview()"><i class="ri-whatsapp-line align-bottom me-1" ></i>Abrir whatsapp cliente</button>
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
                                                            <label for="lote_id" class="form-label">Lote:</label>
                                                            <select type="text" class="form-control form-select" name="lote_id" id="lote_id" aria-label="Seleccionar">
                                                                <option selected>Seleccionar Lote</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-xl-3">
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
                                                        <div class="col-xl-2">
                                                            <label for="tipo_prod" class="form-label">Tipo Producto:</label>
                                                            <select type="text" class="form-control form-select" name="tipo_prod" id="tipo_prod" aria-label="Seleccionar">
                                                                <option selected>Seleccionar</option>
                                                                <option value='PV'>POLLO VIVO</option>
                                                                <option value='PF'>POLLO FAENADO</option>
                                                            </select>
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
                                                        <th>LOTE</th>
                                                        <th>CLIENTE</th>
                                                        <th>PRODUCTO</th>
                                                        <th>FECHA</th>
                                                        <th># RECIBO</th>
                                                        <th>CANTIDAD</th>
                                                        <th>PESO NETO</th>
                                                        <th>PRECIO</th>
                                                        <th>TOTAL MONTO</th>
                                                        <th>USUARIO</th>
                                                        <th>HORA</th>
                                                        <th>OPCIONES</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th class="fs-14 text-white bg-dark">TOTAL:</th>
                                                    <th id="total_cantidad" class="fs-14 text-white bg-dark">CANTIDAD</th>
                                                    <th id="total_peso" class="fs-14 text-white bg-dark">PESO NETO</th>
                                                    <th></th>
                                                    <th id="total_vendido" class="fs-15 text-white bg-dark">TOTAL MONTO</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                </tfoot>
                                            </table>

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
            <?php require_once("modalEditSalida.php") ?>
            <!-- Fin Modal -->

            <!-- Librerias js -->
            <?php require_once("../html/js.php"); ?>
            <script type="text/javascript" src="listSalida.js"></script>
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