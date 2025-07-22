<!-- Default Modals -->
<div class="modal fade" id="generarListadoMovPDF" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom-dashed">
                <h5 class="modal-title" id="lbTituloConMov">Consulta Movimientos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <form method="post" id="FormCreacionCtaCli">
                <div class="modal-body">
                    <input type="hidden" name="cta_idMov" id="cta_idMov">
                    <div class="row g-3">

                        <div class="col-md-4">
                            <div>
                                <label for="tipo_mov" class="form-label">Tipo Movimiento:</label>
                                <select type="text" class="form-control form-select" name="tipo_mov" id="tipo_mov" aria-label="Seleccionar">
                                    <option value="A" selected>Ambos</option>
                                    <option value="S">Ingresos</option>
                                    <option value="R">Pagos</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div>
                                <label for="fecha_desde" class="form-label">Desde:</label>
                                <input type="date" name="fecha_desde" id="fecha_desde" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div>
                                <label for="fecha_hasta" class="form-label">Hasta:</label>
                                <input type="date" name="fecha_hasta" id="fecha_hasta" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div>
                                <label for="generarPDFMov" class="form-label">Opción</label>
                                <button type="button" class="btn btn-primary w-100" id="generarPDFMov"><i class="bx bx-search-alt label-icon align-middle"></i></button>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </form>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->