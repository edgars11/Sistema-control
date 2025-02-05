<!-- Default Modals -->
<div class="modal fade" id="modalMovLote" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lbTituloIng">Modal Lote Ingreso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <form method="post" id="mantenimiento_formIng">
                <div class="modal-body">
                    <input type="hidden" name="mov_id" id="mov_id">
                    <input type="hidden" name="mov_tipo" id="mov_tipo">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div>
                                <label for="lote_idIng" class="form-label">Lote:</label>
                                <select type="text" class="form-control form-select" name="lote_idIng" id="lote_idIng" aria-label="Seleccionar">
                                    <option selected>Seleccionar Lote</option>
                                </select>
                            </div>

                        </div>
                        <div class="col-md-3">
                            <div>
                                <label for="mov_descr" class="form-label">Tipo Movimiento:</label>
                                <a href="javascript:void(0);" id="mov_descr" class="badge badge-soft-warning fs-18"></a>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div>
                                <label for="lote_capacidad_maxIng" class="form-label">Capacidad Máxima:</label>
                                <input type="number" class="form-control" id="lote_capacidad_maxIng" name="lote_capacidad_maxIng" placeholder="Seleccione Lote" readonly>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div>
                                <label for="lote_cant_actual" class="form-label">Cantidad Actual:</label>
                                <input type="number" class="form-control" id="lote_cant_actual" name="lote_cant_actual" placeholder="Seleccione Lote" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div>
                                <label for="mov_cant_ing" class="form-label">Cantidad a Registrar:</label>
                                <input type="number" class="form-control" id="mov_cant_ing" name="mov_cant_ing" onblur="calcularTotal()" placeholder="Ingrese Cantidad" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div>
                                <label for="lote_can_total" class="form-label">Cantidad a Actualizar:</label>
                                <input type="number" class="form-control" id="lote_can_total" name="lote_can_total" placeholder="Calculo automático" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div>
                                <label for="mov_fecha_ing"><b>Fecha</b></label>
                                <input type="date" name="mov_fecha_ing" id="mov_fecha_ing" class="form-control">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div>
                                <label for="mov_motivo" class="form-label">Agregar observación:</label>
                                <input type="text" class="form-control" id="mov_motivo" name="mov_motivo" placeholder="Ingrese una observación">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" name="action" value="add" class="btn btn-primary">Guardar</button>
                </div>
            </form>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->