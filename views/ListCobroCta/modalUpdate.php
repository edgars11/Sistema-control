<!-- Default Modals -->
<div class="modal fade" id="modalUpdCobo" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lbTituloIng">Modifica Cobro Cuenta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <form method="post" id="mantenimiento_formIng">
                <div class="modal-body">
                    <input type="hidden" name="pagc_id" id="pagc_id">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div>
                                <label for="cli_nombreM" class="form-label">Cliente:</label>
                                <input type="text" class="form-control" id="cli_nombreM" name="cli_nombreM" placeholder="Cliente" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div>
                                <label for="pago_idM" class="form-label">Tipo Pago:</label>
                                <select type="text" class="form-control form-select" name="pago_idM" id="pago_idM" aria-label="Seleccionar">
                                    <option selected>Seleccionar</option>
                                </select>
                            </div>

                        </div>

                        <div class="col-md-3">
                            <div>
                                <label for="pagc_monto" class="form-label">Monto:</label>
                                <input type="number" class="form-control" id="pagc_monto" name="pagc_monto" placeholder="Monto cobro" min="0" max="1000" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div>
                                <label for="pagc_obs" class="form-label">Observación:</label>
                                <input type="text" class="form-control" id="pagc_obs" name="pagc_obs" placeholder="Seleccione Lote" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" name="action" value="add" class="btn btn-primary">Actualizar</button>
                </div>
            </form>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->