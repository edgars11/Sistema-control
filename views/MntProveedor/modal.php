<!-- Default Modals -->
<div class="modal fade" id="modalMantenimiento" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lbTitulo">Modal Proveedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <form method="post" id="mantenimiento_form">
                <div class="modal-body">
                    <input type="hidden" name="prov_id" id="prov_id">
                    <div class="row gy-2">
                        <div class="col-md-12">
                            <div>
                                <label for="valueInput" class="form-label">Nombre:</label>
                                <input type="text" class="form-control" id="prov_nombre" name="prov_nombre" placeholder="Ingrese Nombre" required>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-2">
                        <div class="col-md-12">
                            <div>
                                <label for="valueInput" class="form-label">Identificaión:</label>
                                <input type="text" class="form-control" id="prov_ruc" name="prov_ruc" placeholder="Ingrese Identificaión" required>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-2">
                        <div class="col-md-12">
                            <div>
                                <label for="valueInput" class="form-label">Contacto:</label>
                                <input type="text" class="form-control" id="prov_telefono" name="prov_telefono" placeholder="Ingrese Contacto" required>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-2">
                        <div class="col-md-12">
                            <div>
                                <label for="valueInput" class="form-label">Dirección:</label>
                                <input type="text" class="form-control" id="prov_direccion" name="prov_direccion" placeholder="Ingrese Dirección" required>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-2">
                        <div class="col-md-12">
                            <div>
                                <label for="valueInput" class="form-label">Correo:</label>
                                <input type="text" class="form-control" id="prov_correo" name="prov_correo" placeholder="Ingrese Correo" required>
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