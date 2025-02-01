var i_suc_id = $('#suc_idx').val();

var fromDate;
var toDate;

const data = {
    pago_id: null,
    cli_id: null,
    fecha_desde: null,
    fecha_hasta: null,
    suc_id: i_suc_id
}

$(document).ready(function () {

    fromDate = getDate(new Date());
    toDate = getDate(new Date());

    $('#total_cantidad').html('$0.00');
    $('#total_peso').html('$0.00');
    $('#total_vendido').html('$0.00');

    var fecha = new Date();
    var hoy = formatDate(fecha);
    document.getElementById("fecha_desde").value = hoy;
    document.getElementById("fecha_hasta").value = hoy;

    data.fecha_desde = fromDate;
    data.fecha_hasta = toDate;

    cargarTabla(data);

    // Se obtienen las Formas de pago
    $.post("../../controllers/pagoController.php?op=combo", function (data) {
        $('#pago_id').html(data);
    });


});

$(document).on("click", "#btnFiltro", function () {
    fromDate = $('#fecha_desde').val();
    toDate = $('#fecha_hasta').val();
    pago_id = $('#pago_id').val();
    cli_id = $('#cli_id').val();

    if (pago_id !== 'Seleccionar') {
        data.pago_id = pago_id;
    } else {
        data.pago_id = null;
    }

    if (cli_id.length > 0) {
        data.cli_id = cli_id;
    }

    data.fecha_desde = fromDate;
    data.fecha_hasta = toDate;

    cargarTabla(data);
});
$(document).on("click", "#buscarCliente", function () {
    // Cargamos clientes
    cargarCliente();
    // Mostramos el modal
    $('#modalClientes').modal('show');
});
function cargarTabla(data) {
    $('#tb_listadoSalida').DataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
        ],
        "ajax": {
            url: "../../controllers/pagoController.php?op=listadoPagos",
            type: "post",
            data: data
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
    //cargarTotables(data);
}
function cargarCliente() {
    $('#tb_listadoClientes').DataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
        ],
        "ajax": {
            url: "../../controllers/clienteController.php?op=listado",
            type: "post",
            data: { emp_id: $('#emp_idx').val() }
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
function cargarTotables(dataI) {
    $.post("../../controllers/salidaLoteController.php?op=totales", dataI, function (data) {
        data = JSON.parse(data);
        if (data.cantidad !== null) {
            $('#total_cantidad').html('<span class="counter-value" ># ' + data.cantidad + '</span>');
            $('#total_peso').html('<span class="counter-value" >' + data.peso_neto + ' Lbs</span>');
            $('#total_vendido').html('<span class="counter-value">$ ' + data.total + '</span>');
        }
    })
}
function getDate(date) {
    var dateString = new Date(date.getTime() - (date.getTimezoneOffset() * 60000))
        .toISOString()
        .split("T")[0];

    return dateString;
}
function selectCliente(cli_id) {

    $.post("../../controllers/clienteController.php?op=byID", { cli_id: cli_id }, function (data) {
        if (data.length > 0) {
            data = JSON.parse(data);
            $('#cli_nombre').val(data.cli_nombre);
            $('#cli_id').val(data.cli_id);
        } else {
            $('#cli_identificacion').val('');
            swal.fire({
                title: "Error consulta",
                text: "Identificación no registrada!",
                icon: "warning"
            });
        }
    })
    $('#modalClientes').modal('hide');
}
function eliminar(pagc_id) {
    console.log(pagc_id);
    swal.fire({
        title: "Confirmación!",
        text: "Desea eliminar el registro de salida?",
        icon: "warning",
        confirmButtonText: "Si",
        showCancelButton: true,
        cancelButtonText: "No"
    }).then((result) => {
        if (result.value) {
            // Elimina el registro
            $.post("../../controllers/pagoController.php?op=deleterPago",
                { pagc_id: pagc_id },
                function (data) {
                    data = JSON.parse(data);
                    console.log(data);
                    if (data.exec) {
                        // Recarga los datos de la tabla
                        $('#tb_listadoSalida').DataTable().ajax.reload();

                        // Muestra notificación de la eliminación
                        swal.fire({
                            title: "Pago Cuenta",
                            text: "Eliminado Correctamente!",
                            icon: "success"
                        });
                    } else {
                        swal.fire({
                            title: "Pago Cuenta",
                            text: "Error al eliminar movimiento!",
                            icon: "error"
                        });
                    }
                })
        }
    });
}
function formatDate(dateObject = new Date()) {
    var year = dateObject.getFullYear();
    var month = dateObject.getMonth() + 1;
    var month = month > 9 ? month : "0" + month;
    var day = dateObject.getDate() > 9 ? dateObject.getDate() : "0" + dateObject.getDate();
    return year + "-" + month + "-" + day;
}