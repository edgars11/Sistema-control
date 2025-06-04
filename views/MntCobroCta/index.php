<?php
require_once("../../config/conexion.php");
require_once("../../models/Menu.php");
$menu = new Menu();
$datos = $menu->validacionMenuRol($_SESSION["usu_id"], "mntCobroCta");
if (isset($_SESSION["usu_id"])) {
    if ($datos[0]['validacion'] === 'S') {
?>
        <!doctype html>
        <html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">

        <head>
            <title>Fact-System | Cobro Cuentas</title>
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

                            <!-- TODO: Id de cuenta -->
                            <input type="hidden" name="cta_id" id="cta_id" />
                            <input type="hidden" name="cli_id" id="cli_id" />
                            <!-- start page title -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                        <h4 class="mb-sm-0">Cobro Cuentas</h4>

                                        <div class="page-title-right">
                                            <ol class="breadcrumb m-0">
                                                <li class="breadcrumb-item"><a href="javascript: void(0);">Mantenimiento</a></li>
                                                <li class="breadcrumb-item active">Cobro</li>
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
                                            <h4 class="card-title mb-0-flex-grow-1">Cuenta Cliente</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="live-preview">
                                                <div class="row align-items-center g-3">
                                                    <div class="col-lg-2">
                                                        <label for="buscarCuenta"><b>&nbsp;</b></label> <br>
                                                        <button type="button" id="buscarCuenta" class="btn btn-primary w-100 btn-label waves-effect waves-light"><i class="ri-user-search-line label-icon align-middle fs-16 me-2"></i>Buscar Cuenta</button>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="cli_nombre" class="form-label">Cliente</label>
                                                        <input type="text" class="form-control" id="cli_nombre" name="cli_nombre" placeholder="Nombre Cliente" readonly>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <label for="cli_telefono" class="form-label">Contacto</label>
                                                        <input type="text" class="form-control" id="cli_telefono" name="cli_telefono" placeholder="Contacto" readonly>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <label for="cta_monto" class="form-label">Monto Cuenta</label>
                                                        <input type="text" class="form-control" id="cta_monto" name="cta_monto" placeholder="$ 0.00" readonly>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="recibo_id" class="form-label">Recibo a cancelar</label>
                                                        <select class="form-control form-select" id="recibo_id" name="recibo_id" aria-label="Seleccionar">
                                                            <option selected>Seleccionar</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-4 ">
                                                        <label for="ult_fecha_sal" class="form-label">Fecha Salida</label>
                                                        <input type="text" class="form-control" id="ult_fecha_sal" name="ult_fecha_sal" placeholder="Sin fecha" readonly>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="ult_fecha_pago" class="form-label">Fecha último Pago</label>
                                                        <input type="text" class="form-control" id="ult_fecha_pago" name="ult_fecha_pago" placeholder="Sin fecha" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- TODO: FORMA PAGO -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header align-items-center d-flex">
                                            <h4 class="card-title mb-0-flex-grow-1">Forma Pago</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="live-preview">
                                                <div class="row align-items-center g-3">
                                                    <div class="col-lg-2">
                                                        <label for="pago_id" class="form-label">Tipo Pago</label>
                                                        <select class="form-control form-select" id="pago_id" name="pago_id" aria-label="Seleccionar" required>
                                                            <option selected>Seleccionar</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-lg-2">
                                                        <label for="pagc_monto" class="form-label">Monto a cancelar</label>
                                                        <input type="number" class="form-control" onblur="calcular()" id="pagc_monto" min="0" max="10000" step="0.01" name="pagc_monto" placeholder="Monto">
                                                    </div>

                                                    <div class="col-lg-2">
                                                        <label for="pagc_saldo_recibo" class="form-label">Saldo a favor</label>
                                                        <input type="number" class="form-control" id="pagc_saldo_recibo" name="pagc_saldo_recibo" placeholder="0.00" readonly>
                                                    </div>

                                                    <div class="col-lg-2">
                                                        <label for="pagc_nuevo_monto" class="form-label">Nuevo Monto Total</label>
                                                        <input type="number" class="form-control" id="pagc_nuevo_monto" name="pagc_nuevo_monto" placeholder="0.00" readonly required>
                                                    </div>

                                                    <div class="col-lg-2">
                                                        <label for="pagc_obs" class="form-label">Observación</label>
                                                        <input type="text" class="form-control" id="pagc_obs" name="pagc_obs" placeholder="Observación">
                                                    </div>

                                                    <div class="col-lg-2">
                                                        <label for="btnAddPago" class="form-label">Opciones</label>
                                                        <button type="button" id="btnAddPago" class="btn btn-success btn-label waves-effect waves-light rounded-pill w-100"><i class="ri-check-double-line label-icon align-middle rounded-pill fs-16 me-2"></i> Registrar Pago</button>
                                                    </div>
                                                </div>
                                            </div>
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
            <!-- LLamado al Modal -->
            <?php require_once("modal.php") ?>
            <!-- Fin Modal -->

            <!-- Librerias js -->
            <?php require_once("../html/js.php"); ?>
            <script type="text/javascript" src="mntCobroCta.js"></script>
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