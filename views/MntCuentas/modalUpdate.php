<!-- Default Modals -->
<div class="modal fade" id="modalUpdateCuenta" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom-dashed">
                <h5 class="modal-title" id="lbTituloIng">Actualizar Cuenta Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <form method="post" id="FormUpdateCtaCli">
                <div class="modal-body">
                    <input type="hidden" name="cta_id" id="cta_id">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div>
                                <label for="cli_nombre" class="form-label">Nombre Cliente:</label>
                                <input type="text" class="form-control" id="cli_nombre" name="cli_nombre" placeholder="Seleccione un cliente" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div>
                                <label for="cta_montoAct" class="form-label">Valor Actual:</label>
                                <input type="number" class="form-control" id="cta_montoAct" name="cta_montoAct" placeholder="Seleccione Lote" min="0" max="10000" step="0.01" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div>
                                <label for="mov_tipo" class="form-label">Movimiento:</label>
                                <select type="text" class="form-control form-select" name="mov_tipo" id="mov_tipo" aria-label="Seleccionar">
                                    <option value="AD">AGREGAR SALDO</span></option>
                                    <option value="RE">RESTAR SALDO</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div>
                                <label for="tipoMovimiento" class="form-label">Tipo:</label>
                                <div id="tipoMovimiento" class="alig-items-center"><span class="badge badge-soft-success text-uppercase fs-22">+</span></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div>
                                <label for="cta_montoUpd" class="form-label">Valor Modificar:</label>
                                <input type="number" class="form-control" onblur="calcularValor()" id="cta_montoUpd" name="cta_montoUpd" placeholder="0.00" min="0" max="10000" step="0.01">
                            </div>
                        </div>

                    </div>
                    <br>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div>
                                <label for="cta_nuevo_val" class="form-label">Nuevo Valor:</label>
                                <input type="number" class="form-control" id="cta_nuevo_val" name="cta_nuevo_val" placeholder="$0.00" readonly>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div>
                                <label for="cta_obs" class="form-label">Observación:</label>
                                <input type="text" class="form-control" id="cta_obs" name="cta_obs" placeholder="Ingrese una observación">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" name="action" value="add" class="btn btn-success">Actualizar</button>
                </div>
            </form>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->