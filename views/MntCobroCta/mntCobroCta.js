var w_emp_idx = $('#emp_idx').val();
var w_usu_idx = $('#usu_idx').val();
var w_suc_idx = $('#suc_idx').val();

var w_cli_id = 0;
var w_cta_id = 0;
var w_monto_cta = 0;

$(document).ready(function () {

    // Se obtienen las Formas de pago
    $.post("../../controllers/pagoController.php?op=combo", function (data) {
        $('#pago_id').html(data);
    });

    // Obtiene datos del producto seleccionado
    $("#tipo_compro").change(function () {
        $("#tipo_compro").each(function () {
            tipo_comprobante = $(this).val();

            if (tipo_comprobante == 'PE') {
                if (w_cli_id > 0) {
                    $.post("../../controllers/salidaLoteController.php?op=ListadoRecibosSP", { tipo_val: 'C', cli_id: w_cli_id, suc_id: w_suc_idx }, function (data) {
                        $('#recibo_id').html(data);
                    })
                }

                if (w_monto_cta == 0) {
                    $("#btnAddPago").prop('disabled', true);
                    swal.fire({
                        title: "Cuenta por cobrar",
                        text: "El valor de la cuenta es $0.00!",
                        icon: "warning"
                    });
                } else {
                    $("#btnAddPago").prop('disabled', false);
                }
            } else if (tipo_comprobante == 'VE') {
                $.post("../../controllers/ventaCreditoController.php?op=combo", { cta_id: w_cta_id }, function (data) {
                    $('#recibo_id').html(data);
                })
            }
            // Elimina la opción de crédito del combo de formas de pago
            $('#pago_id').children('option[value="5"]').remove();
            $('#pago_id').find('[value="5"]').remove();
        });
    });

});

$(document).on("click", "#buscarCuenta", function () {
    // Cargamos clientes
    cargarTablaCuentas(w_usu_idx);
    // Mostramos el modal
    $('#modalCuentas').modal('show');
});


$(document).on("click", "#btnAddPago", function () {
    var cta_id = $('#cta_id').val();
    var pago_id = $('#pago_id').val();
    var pagc_nuevo_monto = $('#pagc_nuevo_monto').val();
    var pagc_monto = $('#pagc_monto').val();
    var pagc_obs = $('#pagc_obs').val();
    var recibo_id = $('#recibo_id').val();
    var saldoRestante = $('#pagc_saldo_recibo').val();
    var tipoCobroCta = $('#tipo_compro').val();

    /* TODO: Validación de campos de ventas */
    if (cta_id.length == 0 || pagc_nuevo_monto.length == 0 || pago_id == 'Seleccionar') {
        // Muestra notificación de la eliminación
        swal.fire({
            title: "Pago",
            text: "Error! Campos incompletos",
            icon: "error"
        });
        return;
    }

    if (parseInt(recibo_id) < 0) {
        swal.fire({
            title: "Recibo de Pago",
            text: "Seleccione un recibo de pago válido!",
            icon: "warning"
        });
        return;
    }

    if (pago_id == 'Seleccionar') {
        swal.fire({
            title: "Tipo Pago",
            text: "Seleccione un tipo de pago!",
            icon: "warning"
        });
        return;
    }

    if (cta_id.length == 0) {
        swal.fire({
            title: "Cuenta Cliente",
            text: "Seleccione un cuenta de cliente!",
            icon: "warning"
        });
        return;
    }

    if (saldoRestante.length == 0) {
        saldoRestante = 0;
    }

    if (tipoCobroCta == 'PE') {
        $.post("../../controllers/pagoController.php?op=guardarPago", {
            cta_id: cta_id,
            pago_id: pago_id,
            pagc_obs: pagc_obs,
            pagc_monto: pagc_monto,
            suc_id: w_suc_idx,
            salida_id: recibo_id,
            saldo_recibo: saldoRestante,
            cli_id: w_cli_id
        }, function (data) {
            data = JSON.parse(data);

            if (data.success == true) {
                limpiarCampos();
                swal.fire({
                    title: "Cobro Exitoso",
                    text: "El cobro de la cuenta se realizó correctamente!",
                    icon: "success"
                });
            } else {
                swal.fire({
                    title: "Cobro Errado",
                    text: "Hubo un error al guardar el cobro de cuenta!",
                    icon: "warning"
                });
            }
        });
    } else {
        $.post("../../controllers/ventaCreditoController.php?op=guardarPago", {
            cta_id: cta_id,
            pago_id: pago_id,
            pagc_obs: pagc_obs,
            pagc_monto: pagc_monto,
            suc_id: w_suc_idx,
            ven_id: recibo_id,
            cli_id: w_cli_id
        }, function (data) {
            data = JSON.parse(data);

            if (data.success == true) {
                limpiarCampos();
                swal.fire({
                    title: "Cobro Exitoso",
                    text: "El cobro de la cuenta se realizó correctamente!",
                    icon: "success"
                });
            } else {
                swal.fire({
                    title: "Cobro Errado",
                    text: "Hubo un error al guardar el cobro de cuenta!",
                    icon: "warning"
                });
            }
        });
    }


});

function cargarTablaCuentas(suc_idx) {
    $('#tb_listadoCuentas').DataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
        ],
        "ajax": {
            url: "../../controllers/cuentasController.php?op=consultaModal",
            type: "post",
            data: { suc_id: suc_idx }
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

function selCuenta(cta_id) {
    w_cta_id = cta_id;

    $.post("../../controllers/cuentasController.php?op=byCta", { cta_id: cta_id, suc_id: w_suc_idx }, function (data) {
        data = JSON.parse(data);
        console.log(data);
        $('#cta_id').val(data.cta_id);
        $('#cli_nombre').val(data.cli_nombre);
        $('#cli_telefono').val(data.cli_telefono);
        $('#cta_monto').val("T: $ " + data.cta_monto + (parseInt(data.total_ventas) > 0 ? " - V: $" + data.total_ventas : " ") + (parseInt(data.total_pedidos) > 0 ? " - P: $" + data.total_pedidos : " "));
        $('#ult_fecha_sal').val(data.ult_fecha_sal);
        $('#ult_fecha_pago').val(data.ult_fecha_pago);
        $('#modalCuentas').modal('hide');
        w_cli_id = data.cli_id;
        w_monto_cta = data.cta_monto;
        $('#tipo_compro').val('Seleccionar');
        $('#recibo_id').html('<option selected>Seleccione un tipo comprobante</option>');
    })

}

function calcular() {

    var cta_monto = $('#cta_monto').val();
    var pagc_monto = $('#pagc_monto').val();
    var tipoCobroCta = $('#tipo_compro').val();

    if (cta_monto.length > 0 && pagc_monto.length > 0) {

        total = (parseFloat(cta_monto.substring(2, cta_monto.length).replace(',', '')) - parseFloat(pagc_monto.replace(',', '')))
        if (total < 0) {
            swal.fire({
                title: "Monto Inválido",
                text: "El monto a cancelar es mayor al monto de la cuenta!",
                icon: "error"
            });
        } else {
            $('#pagc_nuevo_monto').val(String(total.toFixed(2)));
            var idRecibo = $('#recibo_id').val();

            if (idRecibo !== null && parseInt(idRecibo) > 0) {
                if (tipoCobroCta == 'PE') {
                    $.post("../../controllers/salidaLoteController.php?op=mostrarByID", { salida_id: idRecibo }, function (data) {
                        data = JSON.parse(data);
                        var montoRecibo = data.salida_total - data.saldo;
                        var saldo = parseFloat(pagc_monto.replace(',', '')) - montoRecibo;
                        $('#pagc_saldo_recibo').val(parseFloat(saldo.toFixed(2)));
                    });
                } else {
                    $.post("../../controllers/ventaCreditoController.php?op=getById", { ven_id: idRecibo }, function (data) {
                        data = JSON.parse(data);
                        var montoRecibo = data.ven_total - data.rvc_abonado;
                        var saldo = parseFloat(pagc_monto.replace(',', '')) - montoRecibo;
                        $('#pagc_saldo_recibo').val(parseFloat(saldo.toFixed(2)));
                    });
                }

            } else {
                var saldo = 0;
                idRecibo = 0;
                $('#pagc_saldo_recibo').val(parseFloat(saldo.toFixed(2)));
            }

        }
    }

}

function limpiarCampos() {
    $('#cta_id').val('');
    $('#pago_id').val('Seleccionar');
    $('#pagc_nuevo_monto').val('');
    $('#pagc_monto').val('');
    $('#pagc_obs').val('');
    $('#cli_nombre').val('');
    $('#cli_telefono').val('');
    $('#cta_monto').val('');
    $('#ult_fecha_sal').val('');
    $('#ult_fecha_pago').val('');
    $('#recibo_id').html('<option selected>Seleccionar cuenta</option>');
}