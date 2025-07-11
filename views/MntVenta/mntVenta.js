var w_emp_idx = $('#emp_idx').val();
var w_usu_idx = $('#usu_idx').val();
var w_suc_idx = $('#suc_idx').val();

var w_valTotalVenta = 0;
$(document).ready(function () {

    // Se crea el registro de la venta
    nuevaVenta();



    /* Evente change cuando se selecciona un cliente de la lista */
    $("#cli_id").change(function () {
        $("#cli_id").each(function () {
            cli_id = $(this).val();

            $.post("../../controllers/clienteController.php?op=mostrar", { cli_id: cli_id }, function (data) {
                data = JSON.parse(data);
                $('#cli_ruc').val(data.cli_ruc);
                $('#cli_correo').val(data.cli_correo);
                $('#cli_telefono').val(data.cli_telefono);
                $('#cli_direccion').val(data.cli_direccion);
            });
        });
    });

    // Obtiene datos de la categoria seleccionada
    $("#cat_id").change(function () {
        $("#cat_id").each(function () {
            cat_id_i = $(this).val();

            $.post("../../controllers/productoController.php?op=cmbcate", { suc_id: w_suc_idx, cat_id: cat_id_i }, function (data) {
                $('#prod_id').html(data);
            });
        });
    });

    // Obtiene datos del producto seleccionado
    $("#prod_id").change(function () {
        $("#prod_id").each(function () {
            prod_id_i = $(this).val();

            $.post("../../controllers/productoController.php?op=mostrar", { suc_id: w_suc_idx, prod_id: prod_id_i }, function (data) {
                data = JSON.parse(data);
                $('#prod_pventa').val(data.prod_pventa);
                $('#unm_nombre').val(data.unm_nombre);
                $('#prod_stock').val(data.prod_stock);
            });
        });
    });

    $(document).on("click", "#btnAddProd", function () {
        var ven_id = $('#ven_id').val();
        var prod_id = $('#prod_id').val();
        var prod_pventa = $('#prod_pventa').val();
        var detv_cant = $('#detv_cant').val();

        if (prod_id.length == 0 || prod_pventa.length == 0 || detv_cant.length == 0) {
            // Muestra notificación de la eliminación
            swal.fire({
                title: "Venta",
                text: "Error! Campos incompletos",
                icon: "error"
            });
        } else {
            // Se obtienen las Formas de pago
            $.post("../../controllers/ventaController.php?op=addDetalle", { ven_id: ven_id, prod_id: prod_id, prod_pventa: prod_pventa, detv_cant: detv_cant }, function (data) {
                data = JSON.parse(data);
                $('#txtsubtotal').html('$' + (data.subtotal < 1 ? "0" + data.subtotal : data.subtotal));
                $('#txtiva').html('$' + (data.iva < 1 ? "0" + data.iva : data.iva));
                $('#txttotal').html('$' + (data.total < 1 ? "0" + data.total : data.total));
                w_valTotalVenta = data.total;
            });
            cargarDetalle(ven_id);
        }

    });

});

$(document).on("click", "#btnGuardar", function () {
    var pago_id = $('#pago_id').val();
    var cli_id = $('#cli_id').val();
    var ven_coment = $('#ven_coment').val();
    var tipo_venta = $('#tipo_venta').val();
    var ven_id = $('#ven_id').val();

    /* TODO: Validación de campos de ventas */
    if (cli_id.length == 0 || tipo_venta.length == 0 || pago_id.length == 0 || tipo_venta == 'Seleccionar' || pago_id == 'Seleccionar') {
        // Muestra notificación de la eliminación
        swal.fire({
            title: "Venta",
            text: "Error! Campos incompletos",
            icon: "error"
        });
    } else {
        /* TODO: Valida que haya registro de productos en la venta */
        if (w_valTotalVenta <= 0) {
            swal.fire({
                title: "Venta",
                text: "Agregue productos a la venta para continuar!",
                icon: "warning"
            });
        } else {
            // Se obtienen las Formas de pago
            $.post("../../controllers/ventaController.php?op=updateVenta",
                {
                    pago_id: pago_id,
                    cli_id: cli_id,
                    ven_coment: ven_coment,
                    tipo_venta: tipo_venta,
                    ven_id: ven_id,
                    suc_id: w_usu_idx,
                    ven_total: w_valTotalVenta
                },
                function (data) {

                    swal.fire({
                        title: "Venta",
                        text: "Venta # C-" + ven_id + " guardada exitosamente!",
                        icon: "success",
                        footer: '<a href="../ViewVenta/?id=' + ven_id + '" target="_blank">¿Desea imprimir el comprobante?</a>'
                    });

                    nuevaVenta();

                });
        }

        // cargarDetalle(ven_id);
    }

});

function cargarDetalle(ven_id) {
    $('#table_data').DataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
        ],
        "ajax": {
            url: "../../controllers/ventaController.php?op=listaDetalleView",
            type: "post",
            data: { ven_id: ven_id }
        },
        "bDestroy": true,
        "responsive": true,
        "bInfo": true,
        "iDisplayLength": 10,
        "order": [[0, "desc"]],
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningun dato disponible en la tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "sPaginate": {
                "sFirts": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior",
            },
            "oAria": {
                "sSortAscending": "Activar para ordenar la columna de manera ascendente",
                "sSortDescending": "Activar para ordenar la columna de manera descendente"
            }
        },
    });
}

function deleteItem(detv_id, ven_id) {
    //console.log(cat_id);
    swal.fire({
        title: "Eliminar!",
        text: "Desea eliminar el registro?",
        icon: "warning",
        confirmButtonText: "Si",
        showCancelButton: true,
        cancelButtonText: "No"
    }).then((result) => {
        if (result.value) {
            // Elimina el registro
            $.post("../../controllers/ventaController.php?op=deleteItem", { detv_id: detv_id }, function (data) {
                data = JSON.parse(data);
                console.log(data);
                var iva = "";
                if (data.iva == null) {
                    iva = "0.00";
                } else {
                    iva = data.iva < 1 ? "0" + data.iva : data.iva;
                }
                $('#txtsubtotal').html('$' + (data.subtotal === null ? "0.00" : data.subtotal));
                $('#txtiva').html('$' + iva);
                $('#txttotal').html('$' + (data.total === null ? "0.00" : data.total));
            })

            // Recarga los datos de la tabla
            cargarDetalle(ven_id);

            // Muestra notificación de la eliminación
            swal.fire({
                title: "Detalle Venta",
                text: "Eliminado Correctamente!",
                icon: "success"
            });
        }
    });
}

function nuevaVenta() {
    // Se crea el registro de la venta
    $.post("../../controllers/ventaController.php?op=registrar", { suc_id: w_suc_idx, usu_id: w_usu_idx }, function (data) {
        data = JSON.parse(data);
        console.log(data.ven_id);
        $('#ven_id').val(data.ven_id);
    });

    cargarDetalle(0);
    $('#cli_id').select2();
    $('#cat_id').select2();
    $('#prod_id').select2();
    $('#pago_id').select2();
    $('#tipo_venta').select2();

    // Se crea el registro de la venta
    $.post("../../controllers/clienteController.php?op=combo", { emp_id: w_emp_idx }, function (data) {
        $('#cli_id').html(data);
    });

    // Se obtienen las categorias
    $.post("../../controllers/categoriaController.php?op=combo", { suc_id: w_suc_idx }, function (data) {
        $('#cat_id').html(data);
    });
    // Se obtienen las Formas de pago
    $.post("../../controllers/pagoController.php?op=combo", function (data) {
        $('#pago_id').html(data);
    });
    // Se obtienen los tipos de comprobantes
    $.post("../../controllers/tipoComprobanteController.php?op=combo", function (data) {
        $('#tipo_venta').html(data);
    });

    $('#prod_id').html('<option>Seleccione categoria</option>');
    $('#cli_ruc').val("");
    $('#cli_correo').val("");
    $('#cli_telefono').val("");
    $('#cli_direccion').val("");

    $('#prod_pventa').val("");
    $('#unm_nombre').val("");
    $('#prod_stock').val("");
    $('#detv_cant').val("");

    $('#txtsubtotal').html('$0.00');
    $('#txtiva').html('$0.00' );
    $('#txttotal').html('$0.00');

}