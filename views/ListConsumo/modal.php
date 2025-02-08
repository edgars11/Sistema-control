<!-- Default Modals -->
<div class="modal fade" id="modalMantenimiento" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lbTituloIng">Ingreso Alimento Lote</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <form method="post" id="form_ingreso_alimento">
                <div class="modal-body">
                    <input type="hidden" name="ali_id" id="ali_id">
                    <input type="hidden" name="cant_consumo_act" id="cant_consumo_act">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div>
                                <label for="lote_id" class="form-label">Lote:</label>
                                <select type="text" class="form-control form-select" name="lote_id" id="lote_id" aria-label="Seleccionar">
                                    <option selected>Seleccionar Lote</option>
                                </select>
                            </div>

                        </div>
                        <div class="col-md-4">
                            <div>
                                <label for="ali_cantidad_ing" class="form-label">Cantidad Ingresada:</label>
                                <input type="number" class="form-control" id="ali_cantidad_ing" name="ali_cantidad_ing" placeholder="Seleccione Lote" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div>
                                <label for="ali_fecha"><b>Fecha</b></label>
                                <input type="date" name="ali_fecha" id="ali_fecha" class="form-control">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div>
                                <label for="ali_cantidad" class="form-label">Cantidad a modificar:</label>
                                <input type="number" class="form-control" id="ali_cantidad" min="0" max="1000" name="ali_cantidad" onblur="calcularTotalAlimento()" placeholder="Ingrese cantidad" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div>
                                <label for="ali_total_cant" class="form-label">Cantidad a Actualizar:</label>
                                <input type="number" class="form-control" id="ali_total_cant" name="ali_total_cant" placeholder="Calculo automático" readonly>
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
                    <button type="submit" name="action" value="add" class="btn btn-primary">Actualizar</button>
                </div>
            </form>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->