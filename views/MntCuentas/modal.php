<!-- Default Modals -->
<div class="modal fade" id="modalMovimientos" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lbTitulo">MOVIMIENTO CUENTAS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="tb_listadoMovimientos" class="table table-borderless text-center table-nowrap align-middle mb-0">
                        <thead>
                            <tr class="table-active">
                                <th scope="col" style="width: 50px;">ID</th>
                                <th scope="col">TIPO</th>
                                <th scope="col">VALOR ACTUAL</th>
                                <th scope="col" class="text-end">VALOR TRANS</th>
                                <th scope="col">NUEVO VALOR</th>
                                <th scope="col">FECHA</th>
                                <th scope="col">OBSERVACIÓN</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table><!--end table-->
                </div>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->