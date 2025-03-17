<!-- Default Modals -->
<div class="modal fade" id="modalMantenimiento" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lbTitulo">Modal Lote</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <form method="post" id="mantenimiento_form">
                <div class="modal-body">
                    <input type="hidden" name="lote_id" id="lote_id">
                    <div class="row gy-2">
                        <div class="col-md-6">
                            <div>
                                <label for="lote_descripcion" class="form-label">Nombre:</label>
                                <input type="text" class="form-control" id="lote_descripcion" name="lote_descripcion" placeholder="Ingrese Nombre" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div>
                                <label for="lote_cap_maximaUpd" class="form-label">Capacidad Máxima:</label>
                                <input type="number" class="form-control" id="lote_cap_maximaUpd" name="lote_cap_maximaUpd" placeholder="Ingrese Capacidad Máxima" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div>
                                <label for="lote_cant_actualUpd" class="form-label">Cantidad Actual:</label>
                                <input type="number" class="form-control" id="lote_cant_actualUpd" name="lote_cant_actualUpd" placeholder="Ingrese Cantidad Actual" required>
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