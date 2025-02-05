<!-- Default Modals -->
<div class="modal fade" id="modalIngAli" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lbTituloIng">Ingreso Alimento Lote</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <form method="post" id="form_ingreso_alimento">
                <div class="modal-body">
                    <input type="hidden" name="mov_id" id="mov_id">
                    <input type="hidden" name="mov_tipo" id="mov_tipo">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div>
                                <label for="lote_idAli" class="form-label">Lote:</label>
                                <select type="text" class="form-control form-select" name="lote_idAli" id="lote_idAli" aria-label="Seleccionar">
                                    <option selected>Seleccionar Lote</option>
                                </select>
                            </div>

                        </div>
                        <div class="col-md-4">
                            <div>
                                <label for="lote_consumo_act" class="form-label">Cantidad Actual:</label>
                                <input type="number" class="form-control" id="lote_consumo_act" name="lote_consumo_act" placeholder="Seleccione Lote" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div>
                                <label for="mov_fecha_ing"><b>Fecha</b></label>
                                <input type="date" name="mov_fecha_ing" id="mov_fecha_ing" class="form-control">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div>
                                <label for="lote_consumo" class="form-label">Cantidad a ingresar:</label>
                                <input type="number" class="form-control" id="lote_consumo" name="lote_consumo" onblur="calcularTotalAlimento()" placeholder="Ingrese cantidad" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div>
                                <label for="lote_ali_total" class="form-label">Cantidad a Actualizar:</label>
                                <input type="number" class="form-control" id="lote_ali_total" name="lote_ali_total" placeholder="Calculo automático" readonly>
                            </div>
                        </div>

                    </div>
                    <br>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div>
                                <label for="ali_desc" class="form-label">Agregar observación:</label>
                                <input type="text" class="form-control" id="ali_desc" name="ali_desc" placeholder="Ingrese una observación">
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