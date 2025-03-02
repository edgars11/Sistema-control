<!-- Default Modals -->
<div class="modal fade" id="modalCrearCuenta" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom-dashed">
                <h5 class="modal-title" id="lbTituloIng">Crear Cuenta Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <form method="post" id="FormCreacionCtaCli">
                <div class="modal-body">
                    <input type="hidden" name="cli_id" id="cli_id">
                    <div class="row g-3">
                        <div class="col-md-1">
                            <div>
                                <label for="buscarCliente" class="form-label">Opción</label>
                                <button type="button" onclick="listarCliente()" class="btn btn-primary w-100" id="buscarCliente"><i class="ri-user-search-line label-icon align-middle"></i></button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div>
                                <label for="cli_nombreC" class="form-label">Nombre Cliente:</label>
                                <input type="text" class="form-control" id="cli_nombreC" name="cli_nombreC" placeholder="Seleccione un cliente" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div>
                                <label for="cta_monto" class="form-label">Monto Cuenta:</label>
                                <input type="number" class="form-control" id="cta_monto" name="cta_monto" placeholder="Seleccione Lote" min="0" max="10000" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div>
                                <label for="cta_obs" class="form-label">Observación:</label>
                                <input type="text" class="form-control" id="cta_obs" name="cta_obs" placeholder="Sin observación" >
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" name="action" value="add" class="btn btn-primary">Guardar</button>
                </div>
            </form>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->