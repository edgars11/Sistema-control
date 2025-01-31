<!-- Default Modals -->
<div class="modal fade" id="modalMantenimiento" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lbTitulo">Modal Categoria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <form method="post" id="mantenimiento_form">
                <div class="modal-body">
                    <input type="hidden" name="usu_id" id="usu_id">
                    <div class="row gy-2">
                        <div class="col-md-6">
                            <div>
                                <label for="valueInput" class="form-label">Nombre:</label>
                                <input type="text" class="form-control" id="usu_nombre" name="usu_nombre" placeholder="Ingrese Nombre" required>
                            </div>
                        </div>
                        <div class="col-md-6">    
                            <div>
                                <label for="valueInput" class="form-label">Apellido:</label>
                                <input type="text" class="form-control" id="usu_apellido" name="usu_apellido" placeholder="Ingrese Nombre" required>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-2">
                        <div class="col-md-12">
                            <div>
                                <label for="valueInput" class="form-label">Correo:</label>
                                <input type="text" class="form-control" id="usu_correo" name="usu_correo" placeholder="Ingrese Nombre" required>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-2">
                        <div class="col-md-6">
                            <div>
                                <label for="valueInput" class="form-label">Identificación:</label>
                                <input type="text" class="form-control" id="usu_dni" name="usu_dni" placeholder="Ingrese Nombre" required>
                            </div>
                        </div>
                        <div class="col-md-6">    
                            <div>
                                <label for="valueInput" class="form-label">Teléfono:</label>
                                <input type="text" class="form-control" id="usu_telefono" name="usu_telefono" placeholder="Ingrese Nombre" required>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-2">
                        <div class="col-md-12">
                            <div>
                                <label for="valueInput" class="form-label">Contraseña:</label>
                                <input type="password" class="form-control" id="usu_password" name="usu_password" placeholder="Ingrese Nombre" required>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-2">
                        <div class="col-md-12">
                            <div>
                                <label for="rol_id" class="form-label">Rol:</label>
                                <select type="text" class="form-control form-select" name="rol_id" id="rol_id" aria-label="Seleccionar">
                                    <option selected>Seleccionar</option>
                                </select>
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