<!-- Default Modals -->
<div class="modal fade" id="modalEditSalida" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lbTituloIng">Editar Pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <form method="post" id="updateRegSalida">
                <div class="modal-body">
                    <input type="hidden" name="lote_idIng" id="lote_idIng">
                    <input type="hidden" name="pago_id" id="pago_id">
                    <input type="hidden" name="salida_vpagado" id="salida_vpagado">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div>
                                <label for="cli_nombreM" class="form-label">Cliente:</label>
                                <input type="text" class="form-control" id="cli_nombreM" name="cli_nombreM" placeholder="Nombre cliente" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div>
                                <label for="salida_id" class="form-label">Número Recibo:</label>
                                <input type="number" class="form-control" id="salida_id" name="salida_id" placeholder="# 000" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div>
                                <label for="sal_tipo" class="form-label">Tipo Producto:</label>
                                <select type="text" class="form-control form-select" name="sal_tipo" id="sal_tipo" aria-label="Seleccionar">
                                    <option selected>Seleccionar</option>
                                    <option value='PV'>POLLO VIVO</option>
                                    <option value='PF'>POLLO FAENADO</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div>
                                <label for="sal_fecha" class="form-label">Fecha Pedido:</label>
                                <input type="date" name="sal_fecha" id="sal_fecha" class="form-control">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div>
                                <label for="salida_estado" class="form-label">Estado:</label>
                                <a href="javascript:void(0);" id="salida_estado" name="salida_estado" class="badge badge-soft-success fs-18"></a>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div>
                                <label for="lote_desc" class="form-label">Lote:</label>
                                <input type="text" class="form-control" id="lote_desc" name="lote_desc" placeholder="Seleccione Lote" required readonly>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div>
                                <label for="lote_cant" class="form-label">Disponible Lote:</label>
                                <input type="number" class="form-control" id="lote_cant" name="lote_cant" placeholder="Calculo automático" required readonly>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div>
                                <label for="sal_cantidad" class="form-label">Cantidad:</label>
                                <input type="number" class="form-control" id="sal_cantidad" name="sal_cantidad" onblur="validarCantidad('C')" placeholder="Seleccione Lote" min="0" max="10000" required>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div>
                                <label for="sal_peso" class="form-label">Peso:</label>
                                <input type="number" class="form-control" id="sal_peso" name="sal_peso" placeholder="Ingrese Cantidad" min="0" max="10000" step="0.01" required>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div>
                                <label for="sal_tara" class="form-label">Tara(Descuento):</label>
                                <input type="number" class="form-control" id="sal_tara" name="sal_tara" onblur="validarCantidad('N')" placeholder="Calculo automático" min="0" max="10000" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div>
                                <label for="sal_peso_neto" class="form-label">Peso Neto:</label>
                                <input type="number" class="form-control" id="sal_peso_neto" name="sal_peso_neto" placeholder="Calculo automático" min="0" max="10000" step="0.01" readonly required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div>
                                <label for="sal_precio" class="form-label">Precio Venta:</label>
                                <input type="number" class="form-control" onblur="validarCantidad('T')" min="0" max="10000" step="0.01" placeholder="Ingrese Precio" name="sal_precio" id="sal_precio" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div>
                                <label for="sal_total" class="form-label">Precio Total:</label>
                                <input type="number" class="form-control" placeholder="Total" name="sal_total" id="sal_total" min="0" max="10000" step="0.01" readonly required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" name="action" value="add" class="btn btn-success">Modificar</button>
                </div>
            </form>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->