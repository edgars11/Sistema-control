<!-- Default Modals -->
<div class="modal fade" id="modalListaClientes" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lbTitulo">CREACIÓN CUENTA CLIENTE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <p>El siguiente listado muestran los clientes que aún no tiene una cuenta registrada.</p>
                    <table id="tb_listadoClietes" class="table table-borderless text-center table-nowrap align-middle mb-0">
                        <thead>
                            <tr class="table-active">
                                <th scope="col">CLIENTE</th>
                                <th scope="col">RUC</th>
                                <th scope="col" class="text-end">DIRECCIÓN</th>
                                <th scope="col">OPCIÓN</th>
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