<!-- Default Modals -->
<div class="modal fade" id="modalMantenimiento" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lbTitulo">Modal Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <form method="post" id="mantenimiento_form">
                <div class="modal-body">
                    <input type="hidden" name="cli_id" id="cli_id">
                    <div class="row gy-2">
                        <div class="col-md-12">
                            <div>
                                <label for="valueInput" class="form-label">Nombre:</label>
                                <input type="text" class="form-control" id="cli_nombre" name="cli_nombre" placeholder="Ingrese Nombre" required>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-2">
                        <div class="col-md-12">
                            <div>
                                <label for="valueInput" class="form-label">Identificaión:</label>
                                <input type="text" class="form-control" id="cli_ruc" name="cli_ruc" placeholder="Ingrese Identificaión" required>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-2">
                        <div class="col-md-12">
                            <div>
                                <label for="valueInput" class="form-label">Contacto:</label>
                                <input type="text" class="form-control" id="cli_telefono" name="cli_telefono" placeholder="Ingrese Contacto" required>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-2">
                        <div class="col-md-12">
                            <div>
                                <label for="valueInput" class="form-label">Dirección:</label>
                                <input type="text" class="form-control" id="cli_direccion" name="cli_direccion" placeholder="Ingrese Dirección" required>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-2">
                        <div class="col-md-12">
                            <div>
                                <label for="valueInput" class="form-label">Correo:</label>
                                <input type="text" class="form-control" id="cli_correo" name="cli_correo" placeholder="Ingrese Correo">
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