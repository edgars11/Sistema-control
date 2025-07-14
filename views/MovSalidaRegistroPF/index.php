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
            <title>Fact-System | Registro Pedido PF</title>
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
                                        <h4 class="mb-sm-0">Registro Pollo Faenado</h4>

                                        <div class="page-title-right">
                                            <ol class="breadcrumb m-0">
                                                <li class="breadcrumb-item"><a href="javascript: void(0);">Lote</a></li>
                                                <li class="breadcrumb-item active">Registro Pedido PF</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Formulario Salida -->
                            <form method="post" id="mantenimiento_formIng">
                                <input type="hidden" name="salida_id" id="salida_id">
                                <input type="hidden" name="cli_id" id="cli_id">
                                <input type="hidden" name="sal_tipo" id="sal_tipo" value="PF">
                                <div>
                                    <h5 class="fs-14 mb-3 text-muted">Datos Cliente</h5>
                                    <div class="row">
                                        <div class="col-xl-2">
                                            <div class="mb-3">
                                                <label for="buscarCliente"><b>&nbsp;</b></label> <br>
                                                <button type="button" id="buscarCliente" class="btn btn-primary btn-label waves-effect waves-light w-100"><i class="ri-user-search-line label-icon align-middle fs-16 me-2"></i>Buscar</button>
                                            </div>
                                        </div><!-- end col -->
                                        <div class="col-xl-3">
                                            <div class="mb-3">
                                                <label for="cli_identificacion" class="form-label">Buscar por cédula:</label>
                                                <input type="number" class="form-control" placeholder="Ingrese cédula cliente" onblur="buscarClienteCed();" name="cli_identificacion" id="cli_identificacion">
                                            </div>
                                        </div><!-- end col -->

                                        <div class="col-xl-3">
                                            <div class="mb-3">
                                                <label for="cli_nom" class="form-label">Nombre Cliente</label>
                                                <input type="text" class="form-control" placeholder="Seleccione un cliente" id="cli_nom" readonly>
                                            </div>
                                        </div><!-- end col -->

                                        <div class="col-xl-2">
                                            <div class="mb-3">
                                                <label for="cli_contacto" class="form-label">Contacto:</label>
                                                <input type="text" class="form-control" placeholder="Seleccione un cliente" id="cli_contacto" readonly>
                                            </div>
                                        </div><!-- end col -->

                                        <div class="col-xl-2">
                                            <div class="mb-3">
                                                <label for="cta_cli" class="form-label">Saldo Cuenta:</label>
                                                <input type="text" class="form-control" placeholder="Cuenta cliente" id="cta_cli" readonly>
                                            </div>
                                        </div><!-- end col -->
                                    </div><!-- end row -->
                                </div>

                                <div class="mt-2">
                                    <h5 class="fs-14 mb-3 text-muted">Lote</h5>
                                    <div class="row">
                                        <div class="col-xl-2">
                                            <div class="mb-3">
                                                <label for="sal_fecha"><b>Fecha</b></label>
                                                <input type="date" name="sal_fecha" id="sal_fecha" class="form-control" oninput="setCount()">
                                            </div>
                                        </div><!-- end col -->
                                        <div class="col-xl-2">
                                            <div class="mb-3">
                                                <label for="lote_cant_act" class="form-label">Cantidad Disponible Camal</label>
                                                <input type="number" class="form-control" placeholder="Cantidad Actual" name="lote_cant_act" id="lote_cant_act" readonly>
                                            </div>
                                        </div><!-- end col -->

                                        <div class="col-xl-2">
                                            <div class="mb-3">
                                                <label for="pago_id" class="form-label">Forma Pago</label>
                                                <select type="text" class="form-control form-select" name="pago_id" id="pago_id" aria-label="Seleccionar">
                                                    <option selected>Seleccionar</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-2">
                                            <div class="mb-3">
                                                <label for="sal_cantidad" class="form-label">Cantidad Salida</label>
                                                <input type="number" class="form-control" onblur="validarCantidad('C')" placeholder="Ingrese Cantidad" name="sal_cantidad" id="sal_cantidad">
                                            </div>
                                        </div><!-- end col -->

                                        <div class="col-xl-2">
                                            <div class="mb-3">
                                                <label for="sal_peso" class="form-label">Peso</label>
                                                <input type="number" class="form-control" placeholder="Ingrese Peso" name="sal_peso" id="sal_peso" min="0" max="10000" step="0.01" />
                                            </div>
                                        </div><!-- end col -->

                                        <div class="col-xl-2">
                                            <div class="mb-3">
                                                <label for="sal_tara" class="form-label">Tara(Descuento)</label>
                                                <input type="number" class="form-control" onblur="validarCantidad('N')" min="0" max="10000" step="0.01" placeholder="Ingrese tara" name="sal_tara" id="sal_tara">
                                            </div>
                                        </div><!-- end col -->
                                    </div><!-- end row -->
                                    <div class="row">
                                        <div class="col-xl-2">
                                            <div class="mb-3">
                                                <label for="sal_peso_neto" class="form-label">Peso Neto</label>
                                                <input type="text" class="form-control" placeholder="Peso Neto" name="sal_peso_neto" id="sal_peso_neto" readonly>
                                            </div>
                                        </div><!-- end col -->

                                        <div class="col-xl-2">
                                            <div class="mb-3">
                                                <label for="sal_precio" class="form-label">Precio Venta</label>
                                                <input type="text" class="form-control" onblur="validarCantidad('T')" min="0" max="10000" step="0.01" placeholder="Ingrese Precio" name="sal_precio" id="sal_precio">
                                            </div>
                                        </div><!-- end col -->

                                        <div class="col-xl-2">
                                            <div class="mb-3">
                                                <label for="sal_total" class="form-label">Precio total</label>
                                                <input type="number" class="form-control" placeholder="Total" name="sal_total" id="sal_total" readonly>
                                            </div>
                                        </div><!-- end col -->



                                        <div class="col-xl-2">
                                            <div class="mb-3">
                                                <label for="agregarSalida"><b>&nbsp;</b></label> <br>
                                                <button type="submit" name="action" value="add" id="agregarSalida" class="btn btn-success btn-label waves-effect waves-light w-100"><i class="ri-add-circle-line label-icon align-middle fs-16 me-2"></i>Agregar</button>
                                            </div>
                                        </div><!-- end col -->
                                    </div>
                                </div>
                            </form>
                            <!-- Fin formulario -->
                            <div class="mt-2">
                                <h5 class="fs-14 mb-3 text-muted">Detalle Salida Lote</h5>

                                <table id="table_data" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>NOMBRE CLIENTE</th>
                                            <th>FORMA PAGO</th>
                                            <th>CANT. SALIDA</th>
                                            <th>PESO</th>
                                            <th>TARA</th>
                                            <th>PESO NETO</th>
                                            <th>PRECIO</th>
                                            <th>TOTAL</th>
                                            <th>FECHA</th>
                                            <th>OPCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
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

                <!-- LLamado al Modal -->
                <?php require_once("modal.php") ?>
                <!-- Fin Modal -->
            </div>
            <!-- END layout-wrapper -->
            <!-- Librerias js -->
            <?php require_once("../html/js.php"); ?>
            <script type="text/javascript" src="mntSalida.js"></script>
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