<!-- Default Modals -->
<div class="modal fade" id="modalMantenimiento" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lbTitulo">Modal Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <form method="post" id="mantenimiento_form">
                <div class="modal-body">
                    <input type="hidden" name="prod_id" id="prod_id">
                    <div class="row gy-2">
                        <div class="col-md-12">
                            <div>
                                <label for="cat_id" class="form-label">Categoria:</label>
                                <select type="text" class="form-control form-select" name="cat_id" id="cat_id" aria-label="Seleccionar">
                                    <option selected>Seleccionar</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-1 mt-1">
                        <div class="col-md-12">
                            <div>
                                <input type="checkbox" class="form-label" id="gen_codigo" name="gen_codigo" checked>
                                <label for="gen_codigo" class="form-label">Generar código automático</label>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-2">
                        <div class="col-md-12">
                            <div>
                                <label for="valueInput" class="form-label">Nombre:</label>
                                <input type="text" class="form-control" id="prod_nombre" name="prod_nombre" placeholder="Ingrese Nombre" required>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-2">
                        <div class="col-md-12">
                            <div>
                                <label for="valueInput" class="form-label">Description:</label>
                                <input type="text" class="form-control" id="prod_descripcion" name="prod_descripcion" placeholder="Ingrese Identificaión" required>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-2">
                        <div class="col-md-6">
                            <div>
                                <label for="valueInput" class="form-label">Precio Compra:</label>
                                <input type="text" class="form-control" id="prod_pcompra" name="prod_pcompra" placeholder="Ingrese Contacto" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div>
                                <label for="valueInput" class="form-label">Precio Venta:</label>
                                <input type="text" class="form-control" id="prod_pventa" name="prod_pventa" placeholder="Ingrese Contacto" required>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-2">
                        <div class="col-md-6">
                            <div>
                                <label for="valueInput" class="form-label">Stock:</label>
                                <input type="number" class="form-control" id="prod_stock" name="prod_stock" placeholder="Ingrese Contacto" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div>
                                <label for="prod_cod_barra" class="form-label">Codigo Barra:</label>
                                <input type="text" class="form-control" id="prod_cod_barra" name="prod_cod_barra" placeholder="Ingrese Contacto" required>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-2">
                        <div class="col-md-12">
                            <div>
                                <label for="valueInput" class="form-label">Fecha Vencimiento:</label>
                                <input type="date" class="form-control" id="prod_fechaven" name="prod_fechaven" placeholder="Ingrese Dirección">
                            </div>
                        </div>
                    </div>
                    <div class="row gy-2">
                        <div class="col-md-6">
                            <div>
                                <label for="unm_id" class="form-label">Unidad Medida:</label>
                                <select type="text" class="form-control form-select" name="unm_id" id="unm_id" aria-label="Seleccionar">
                                    <option selected>Seleccionar</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div>
                                <label for="prod_tipo_producto" class="form-label">Tipo Producto:</label>
                                <select type="text" class="form-control form-select" name="prod_tipo_producto" id="prod_tipo_producto" aria-label="Seleccionar">
                                    <option value="P">Producto</option>
                                    <option value="S">Servicio</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-2">
                        <div class="col-md-12">
                            <div>
                                <label for="valueInput" class="form-label">Imagen:</label>
                                <input type="file" class="form-control" id="prod_img" name="prod_img" placeholder="Seleccione una imagen">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row gy-2">
                        <div class="col-md-12">
                            <div class="text-center">
                                <a id="btnRemovePhoto" class="btn btn-danger btn-icon waves-effect waves-light btn-sm"><i class="ri-delete-bin-5-line"></i></a>
                                <span id="pre_image">
                                    
                                </span>
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