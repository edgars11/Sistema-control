var w_emp_idx = $('#emp_idx').val();
var w_usu_idx = $('#usu_idx').val();
var w_suc_idx = $('#suc_idx').val();

$(document).ready(function () {

    // Se obtienen las Formas de pago
    $.post("../../controllers/pagoController.php?op=combo", function (data) {
        $('#pago_id').html(data);
    });

});

$(document).on("click", "#buscarCuenta", function () {

    console.log("Seleccionado")
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

    $.post("../../controllers/pagoController.php?op=guardarPago", {
        cta_id: cta_id,
        pago_id: pago_id,
        pagc_obs: pagc_obs,
        pagc_monto: pagc_monto,
        suc_id: w_suc_idx
    }, function (data) {
        data = JSON.parse(data);

        if (data.success == true) {
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
            swal.fire({
                title: "Cobro Cuenta",
                text: "El cobro de la cuenta se realizó correctamente!",
                icon: "success"
            });
        } else {
            swal.fire({
                title: "Cobro Cuenta",
                text: "Hubo un error al guardar el cobro de cuenta!",
                icon: "warning"
            });
        }
    })

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

    $.post("../../controllers/cuentasController.php?op=byCta", { cta_id: cta_id, suc_id: w_suc_idx }, function (data) {
        data = JSON.parse(data);
        $('#cta_id').val(data.cta_id);
        $('#cli_nombre').val(data.cli_nombre);
        $('#cli_telefono').val(data.cli_telefono);
        $('#cta_monto').val("$ " + data.cta_monto);
        $('#ult_fecha_sal').val(data.ult_fecha_sal);
        $('#ult_fecha_pago').val(data.ult_fecha_pago);
        $('#modalCuentas').modal('hide');
        console.log(data);
    })

}

function calcular() {

    var cta_monto = $('#cta_monto').val();
    var pagc_monto = $('#pagc_monto').val();

    if (cta_monto.length > 0 && pagc_monto.length > 0) {

        total = (parseFloat(cta_monto.substring(2)) - parseFloat(pagc_monto))
        if (total < 0) {
            swal.fire({
                title: "Monto Inválido",
                text: "El monto a cancelar es mayor al monto de la cuenta!",
                icon: "error"
            });
        } else {
            $('#pagc_nuevo_monto').val(String(total.toFixed(2)));

        }
    }

}