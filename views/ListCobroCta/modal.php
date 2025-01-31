<!-- Default Modals -->
<div class="modal fade" id="modalClientes" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lbTitulo">LISTADO DE CLIENTES ACTIVOS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="tb_listadoClientes" class="table table-borderless text-center table-nowrap align-middle mb-0">
                        <thead>
                            <tr class="table-active">
                                <th scope="col" style="width: 50px;">NOMBRE</th>
                                <th scope="col">IDENTIFICACIÓN</th>
                                <th scope="col">DIRECCIÓN</th>
                                <th scope="col" class="text-end">TELÉFONO</th>
                                <th scope="col">EMAIL</th>
                                <th scope="col">OPCIONES</th>
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