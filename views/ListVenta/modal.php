<!-- Default Modals -->
<div class="modal fade" id="modalDetalle" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lbTitulo">Detalle Venta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>

            <div class="modal-body">

                <div class="table-responsive ">
                    <div class="container">
                        <table id="detalle_data" class="table table-borderless text-center table-nowrap align-middle mb-0">
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
                            <tbody>

                            </tbody>
                        </table><!--end table-->
                    </div>
                </div>
                <div class="border-top border-top-dashed mt-2">
                    <table class="table table-borderless table-nowrap align-middle mb-0 ms-auto" style="width:250px">
                        <tbody>
                            <tr>
                                <td>Sub Total</td>
                                <td class="text-end" id="ven_subtotal">$699.96</td>
                            </tr>
                            <tr>
                                <td>Estimated Tax (12.5%)</td>
                                <td class="text-end" id="ven_iva">$44.99</td>
                            </tr>
                            <tr class="border-top border-top-dashed fs-15">
                                <th scope="row">Total Amount</th>
                                <th class="text-end" id="ven_total">$755.96</th>
                            </tr>
                        </tbody>
                    </table>
                    <!--end table-->
                </div>
            </div>

            <div class="modal-footer">
                <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->