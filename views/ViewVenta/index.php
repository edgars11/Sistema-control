<?php
require_once("../../config/conexion.php");
if (isset($_SESSION["usu_id"])) {
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
                                    <h4 class="mb-sm-0">Vista Detalle Venta</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Venta</a></li>
                                            <li class="breadcrumb-item active">Vista</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <div class="row justify-content-center">
                                <div class="col-xxl-9">
                                    <div class="card" id="demo">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="card-header border-bottom-dashed p-4">
                                                    <div class="d-flex">
                                                        <div class="flex-grow-1">
                                                            <img src="../../assets/images/logo-dark.png" class="card-logo card-logo-dark" alt="logo dark" height="17">
                                                            <img src="../../assets/images/logo-light.png" class="card-logo card-logo-light" alt="logo light" height="17">
                                                            <div class="mt-sm-5 mt-4">
                                                                <h6 class="text-muted text-uppercase fw-semibold">Dirección</h6>
                                                                <p class="text-muted mb-1" id="txtDireccion"></p>
                                                            </div>
                                                        </div>
                                                        <div class="flex-shrink-0 mt-sm-0 mt-3">
                                                            <h6><span class="text-muted fw-normal">RUC: </span><span id="txtRuc"></span></h6>
                                                            <h6><span class="text-muted fw-normal">Email: </span><span id="txtEmail"></span></h6>
                                                            <h6><span class="text-muted fw-normal">Website: </span> <a href="https://themesbrand.com/" class="link-primary" target="_blank" id="txtWebSite"></h6>
                                                            <h6 class="mb-0"><span class="text-muted fw-normal">Contactos: </span><span id="txtTelefono"></span></h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--end card-header-->
                                            </div><!--end col-->
                                            <div class="col-lg-12">
                                                <div class="card-body p-4">
                                                    <div class="row g-3">
                                                        <div class="col-lg-3 col-6">
                                                            <p class="text-muted mb-2 text-uppercase fw-semibold">Nro de Venta:</p>
                                                            <h5 class="fs-14 mb-0">#C-<span id="ven_id"></span></h5>
                                                        </div>
                                                        <!--end col-->
                                                        <div class="col-lg-3 col-6">
                                                            <p class="text-muted mb-2 text-uppercase fw-semibold">Fecha de venta</p>
                                                            <h5 class="fs-14 mb-0"><span id="ven_fecha_crea"></span></h5>
                                                        </div>
                                                        <!--end col-->
                                                        <div class="col-lg-3 col-6">
                                                            <p class="text-muted mb-2 text-uppercase fw-semibold">Tipo Pago</p>
                                                            <span class="badge badge-soft-success fs-11" id="pago_nombre"></span>
                                                        </div>
                                                        <!--end col-->
                                                        <div class="col-lg-3 col-6">
                                                            <p class="text-muted mb-2 text-uppercase fw-semibold">Total</p>
                                                            <h5 class="fs-14 mb-0">$<span id="ven_total"></span></h5>
                                                        </div>
                                                        <!--end col-->
                                                    </div>
                                                    <!--end row-->
                                                </div>
                                                <!--end card-body-->
                                            </div><!--end col-->

                                            <div class="col-lg-12">
                                                <div class="card-body p-4 border-top border-top-dashed">
                                                    <div class="row g-3">
                                                        <div class="col-4">
                                                            <h6 class="text-muted text-uppercase fw-semibold mb-3">Nombre Cliente</h6>
                                                            <p class="fw-medium mb-2" id="cli_nombre"></p>
                                                        </div>
                                                        <!--end col-->
                                                        <div class="col-4">
                                                            <h6 class="text-muted text-uppercase fw-semibold mb-3">Nombre Usuario</h6>
                                                            <p class="fw-medium mb-2" id="usu_nombre"></p>
                                                        </div>
                                                        <!--end col-->
                                                        <div class="col-4">
                                                            <h6 class="text-muted text-uppercase fw-semibold mb-3">Tipo Comprobante</h6>
                                                            <p class="fw-medium mb-2" id="tipo_comp"></p>
                                                        </div>
                                                        <!--end col-->
                                                    </div>
                                                    <!--end row-->
                                                </div>
                                                <!--end card-body-->
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="card-body p-4">
                                                    <div class="table-responsive">
                                                        <table class="table table-borderless text-center table-nowrap align-middle mb-0">
                                                            <thead>
                                                                <tr class="table-active">
                                                                    <th scope="col" style="width: 50px;">ID</th>
                                                                    <th scope="col">Categoria</th>
                                                                    <th scope="col">Producto</th>
                                                                    <th scope="col">P.Venta</th>
                                                                    <th scope="col">Cantidad</th>
                                                                    <th scope="col" class="text-end">Total</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="products-list">
                                                                
                                                            </tbody>
                                                        </table><!--end table-->
                                                    </div>
                                                    <div class="border-top border-top-dashed mt-2">
                                                        <table class="table table-borderless table-nowrap align-middle mb-0 ms-auto" style="width:250px">
                                                            <tbody>
                                                                <tr>
                                                                    <td>Sub Total</td>
                                                                    <td class="text-end" id="ven_subtotal">$699.96</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Valor Iva (15%)</td>
                                                                    <td class="text-end" id="ven_iva">$44.99</td>
                                                                </tr>
                                                                <tr class="border-top border-top-dashed fs-15">
                                                                    <th scope="row">Total</th>
                                                                    <th class="text-end" id="ven_totalForm">$755.96</th>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <!--end table-->
                                                    </div>

                                                    <div class="mt-4">
                                                        <div class="alert alert-info">
                                                            <p class="mb-0"><span class="fw-semibold">NOTES:</span>
                                                                <span id="ven_comment">
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                                                        <a href="javascript:window.print()" class="btn btn-success"><i class="ri-printer-line align-bottom me-1"></i> Print</a>
                                                        <a href="javascript:void(0);" class="btn btn-primary"><i class="ri-download-2-line align-bottom me-1"></i> Download</a>
                                                    </div>
                                                </div>
                                                <!--end card-body-->
                                            </div><!--end col-->
                                        </div><!--end row-->
                                    </div>
                                    <!--end card-->
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->

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

        <!-- Librerias js -->
        <?php require_once("../html/js.php"); ?>
        <script type="text/javascript" src="viewVenta.js"></script>
        <!-- Fin librerias js -->
    </body>

    </html>

<?php
} else {
    // No existe sesión
    header("Location:" . Conectar::ruta() . "views/404/");
}
?>