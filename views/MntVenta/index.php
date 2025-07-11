<?php
require_once("../../config/conexion.php");
require_once("../../models/Menu.php");
$menu = new Menu();
$datos = $menu->validacionMenuRol($_SESSION["usu_id"], "mntVenta");
if (isset($_SESSION["usu_id"])) {
    if ($datos[0]['validacion'] === 'S') {
?>
        <!doctype html>
        <html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">

        <head>
            <title>Fact-System | Venta</title>
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

                            <!-- TODO: Id de venta -->
                            <input type="hidden" name="ven_id" id="ven_id" />
                            <!-- start page title -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                        <h4 class="mb-sm-0">Nueva Venta</h4>

                                        <div class="page-title-right">
                                            <ol class="breadcrumb m-0">
                                                <li class="breadcrumb-item"><a href="javascript: void(0);">Mantenimiento</a></li>
                                                <li class="breadcrumb-item active">Venta</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- TODO: Detalle del Cliente -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header align-items-center d-flex">
                                            <h4 class="card-title mb-0-flex-grow-1">Datos del Cliente</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="live-preview">
                                                <div class="row align-items-center g-3">
                                                    <div class="col-lg-4">
                                                        <label for="cli_id" class="form-label">Cliente</label>
                                                        <select class="form-control form-select" id="cli_id" name="cli_id" aria-label="Seleccionar">
                                                            <option selected>Seleccionar</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-4 ">
                                                        <label for="cli_ruc" class="form-label">Cédula</label>
                                                        <input type="text" class="form-control" id="cli_ruc" name="cli_ruc" placeholder="Ruc">
                                                    </div>

                                                    <div class="col-lg-4 ">
                                                        <label for="cli_direccion" class="form-label">Dirección</label>
                                                        <input type="text" class="form-control" id="cli_direccion" name="cli_direccion" placeholder="Dirección" readonly>
                                                    </div>

                                                    <div class="col-lg-4 ">
                                                        <label for="cli_telefono" class="form-label">Teléfono</label>
                                                        <input type="text" class="form-control" id="cli_telefono" name="cli_telefono" placeholder="Teléfono" readonly>
                                                    </div>

                                                    <div class="col-lg-4">
                                                        <label for="cli_correo" class="form-label">Correo</label>
                                                        <input type="text" class="form-control" id="cli_correo" name="cli_correo" placeholder="Correo" readonly>
                                                    </div>

                                                    <!-- <div class="col-lg-4">
                                                        <label for="btn_updCli" class="form-label">Opciones</label>
                                                        <button type="button" id="btn_updCli" name="btn_updCli" class="form-control btn btn-primary btn-label waves-effect right waves-light">
                                                            <i class="ri-user-smile-line label-icon align-middle fs-16 ms-2"></i> Editar
                                                        </button>
                                                    </div> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- TODO: Detalle de Producto -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0-flex-grow-1">Agregar Producto</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="live-preview">
                                            <div class="row align-items-center g-3">
                                                <div class="col-lg-2">
                                                    <label for="cat_id" class="form-label">Categoria</label>
                                                    <select class="form-control form-select" id="cat_id" name="cat_id" aria-label="Seleccionar">
                                                        <option selected>Seleccionar</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="prod_id" class="form-label">Producto</label>
                                                    <select class="form-control form-select" id="prod_id" name="prod_id" aria-label="Seleccionar">
                                                        <option selected>Seleccionar</option>
                                                    </select>
                                                </div>

                                                <div class="col-lg-1">
                                                    <label for="prod_pventa" class="form-label">Precio</label>
                                                    <input type="number" class="form-control" id="prod_pventa" name="prod_pventa" placeholder="Costo">
                                                </div>

                                                <div class="col-lg-1">
                                                    <label for="prod_stock" class="form-label">Stock</label>
                                                    <input type="text" class="form-control" id="prod_stock" name="prod_stock" placeholder="Stock" readonly>
                                                </div>

                                                <div class="col-lg-2">
                                                    <label for="unm_nombre" class="form-label">Unidad Medida</label>
                                                    <input type="text" class="form-control" id="unm_nombre" name="unm_nombre" placeholder="Unid. Medida" readonly>
                                                </div>

                                                <div class="col-lg-2">
                                                    <label for="detv_cant" class="form-label">Cantidad</label>
                                                    <input type="number" class="form-control" id="detv_cant" name="detv_cant" placeholder="Cantidad">
                                                </div>

                                                <div class="col-lg-1">
                                                    <label for="btnAddProd" class="form-label">Opciones</label>
                                                    <button type="button" id="btnAddProd" name="btnAddProd" class="form-control btn btn-success  waves-effect waves-light"><i class="ri-add-fill"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- TODO: Detalle de Compra-->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0-flex-grow-1">Detalle de Venta</h4>
                                    </div>
                                    <div class="card-body">
                                        <table id="table_data" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Código</th>
                                                    <th>Producto</th>
                                                    <th>P.Venta</th>
                                                    <th>Cantidad</th>
                                                    <th>Total</th>
                                                    <th>Opciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>

                                        <table class="table table-borderless table-nowrap align-middle mb-0 ms-auto" style="width:250px">
                                            <tbody>
                                                <tr>
                                                    <td>Sub Total</td>
                                                    <td class="text-end" id="txtsubtotal">$0.00</td>
                                                </tr>
                                                <tr>
                                                    <td>Valor Iva (15%)</td>
                                                    <td class="text-end" id="txtiva">$0.00</td>
                                                </tr>
                                                <tr class="border-top border-top-dashed fs-15">
                                                    <th scope="row">Total A Pagar</th>
                                                    <th class="text-end" id="txttotal">$0.00</th>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <div class="mt-4">
                                            <label for="ven_coment" class="form-label text-muted text-uppercase fw-semibold">Comentario</label>
                                            <textarea class="form-control alert alert-info" id="ven_coment" name="ven_coment" placeholder="Agregar comentario" rows="2" required=""></textarea>
                                        </div>
                                        <!-- TODO: Forma de pago -->
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-header align-items-center d-flex">
                                                        <h4 class="card-title mb-0-flex-grow-1">Forma de Pago</h4>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="live-preview">
                                                            <div class="row align-items-center g-3">
                                                                <div class="col-lg-6">
                                                                    <label for="pago_id" class="form-label">Pago</label>
                                                                    <select class="form-control form-select" name="pago_id" id="pago_id" aria-label="Seleccionar">
                                                                        <option selected>Seleccionar</option>
                                                                    </select>
                                                                </div>

                                                                <div class="col-lg-6">
                                                                    <label for="tipo_venta" class="form-label">Tipo Venta</label>
                                                                    <select class="form-control form-select" name="tipo_venta" id="tipo_venta" aria-label="Seleccionar">
                                                                        <option selected>Seleccionar</option>
                                                                    </select>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div> <!-- Fin forma de pago -->

                                        <div class="hstack gap-2 justify-content-center d-print-none mt-4">
                                            <button type="submit" id="btnGuardar" name="btnGuardar" class="btn btn-success"><i class="ri-printer-line align-bottom me-1"></i> Guardar</button>
                                            <!-- <!-- <a href="javascript:void(0);" class="btn btn-primary"><i class="ri-download-2-line align-bottom me-1"></i> Download Invoice</a> -->
                                            <a href="javascript:void(0);" class="btn btn-warning"><i class="ri-send-plane-fill align-bottom me-1"></i> Limpiar</a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

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
            <script type="text/javascript" src="mntVenta.js"></script>
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