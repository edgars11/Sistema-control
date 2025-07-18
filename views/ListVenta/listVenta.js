var i_suc_id = $('#suc_idx').val();
var i_cli_id = 0;

const dataFiltro = {
    cli_id: null,
    tipo_pago: null,
    fecha_desde: null,
    fecha_hasta: null,
    suc_id: i_suc_id
}

var clientContact = "";
var clientName = "";

$(document).ready(function () {

    var today = getDate(new Date());
    $('#fecha_ini').val(today);
    $('#fecha_hasta').val(today);

    $('#btnWhatsappCli').hide();

    dataFiltro.fecha_desde = today;
    dataFiltro.fecha_hasta = today;
    cargarVentas(dataFiltro);

        // Se obtienen las Formas de pago
    $.post("../../controllers/pagoController.php?op=combo", function (data) {
        $('#tipo_pago').html(data);
    });
});

$(document).on("click", "#buscarCliente", function () {
    // Cargamos clientes
    cargarCliente();
    // Mostramos el modal
    $('#modalClientes').modal('show');
});

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

function ver(ven_id) {
    $('#detalle_data').DataTable({
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

    $.post("../../controllers/ventaController.php?op=listarVenta", { ven_id: ven_id }, function (data) {
        data = JSON.parse(data);

        $('#ven_subtotal').html("$" + data.ven_subtotal);
        $('#ven_iva').html("$" + data.ven_iva);
        $('#ven_total').html("$" + data.ven_total);

    });

    $('#modalDetalle').modal('show');
}

function getDate(date) {
    var dateString = new Date(date.getTime() - (date.getTimezoneOffset() * 60000))
        .toISOString()
        .split("T")[0];

    return dateString;
}

$(document).on("click", "#btn_search", function (e) {
    e.preventDefault();
    var fecha_ini = $('#fecha_ini').val();
    var fecha_hasta = $('#fecha_hasta').val();

    if (Date.parse(fecha_hasta) < Date.parse(fecha_ini)) {
        swal.fire({
            title: "Advertencia",
            text: "La fecha inicial no puede ser mayor a la final!",
            icon: "error"
        });
        return;
    }

    var idCliente = $('#cli_id').val();
    var tipoPago = $('#tipo_pago').val();
    if(idCliente.lenght == 0){
        idCliente = null;
    }

    if(tipoPago == 'Seleccionar'){
        tipoPago = null;
    }

    dataFiltro.fecha_desde = fecha_ini;
    dataFiltro.fecha_hasta = fecha_hasta;
    dataFiltro.cli_id = idCliente;
    dataFiltro.tipo_pago = tipoPago;

    cargarVentas(dataFiltro);
});

function cargarVentas(dataFiltro) {

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
            url: "../../controllers/ventaController.php?op=listarVentasReg",
            type: "post",
            data: { 
                suc_id: dataFiltro.suc_id, 
                fecha_ini: dataFiltro.fecha_desde, 
                fecha_hasta: dataFiltro.fecha_hasta,
                cli_id : dataFiltro.cli_id,
                tipo_pago : dataFiltro.tipo_pago
            }
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

function selectCliente(cli_id) {

    $.post("../../controllers/clienteController.php?op=byID", { cli_id: cli_id }, function (data) {
        if (data.length > 0) {
            data = JSON.parse(data);
            clientName = data.cli_nombre;
            clientContact = data.cli_telefono;
            i_cli_id = cli_id;
            $('#cli_nombre').val(clientName);
            $('#cli_id').val(data.cli_id);
            $('#btnWhatsappCli').show();
        } else {
            $('#cli_identificacion').val('');
            $('#btnWhatsappCli').hide();
            swal.fire({
                title: "Error consulta",
                text: "Identificación no registrada!",
                icon: "warning"
            });
        }
    })
    $('#modalClientes').modal('hide');
}

function resetClient() {
    $('#cli_nombre').val('');
    $('#cli_id').val('');
    $('#modalClientes').modal('hide');
    $('#btnWhatsappCli').hide();
}

function openWhatsappview() {
    var mensaje = encodeURIComponent("Hola "+ clientName+", se adjunta el reporte de listado de ventas a crédito realizadas. Gracias");
    var url = "https://wa.me/593" + clienteContacto + "?text=" + mensaje;
    window.open(url, "_blank");
}

function generarReporte() {

    if (i_cli_id > 0) {
        dataFiltro.cli_id = i_cli_id;
    }else{
        swal.fire({
            title: "Generación Reporte",
            text: "Seleccione un cliente para continuar con el reporte!",
            icon: "warning"
        });
        return;
    }

    swal.fire({
        title: "Confirmación!",
        text: "Desea descargar el pdf de registro de salida?",
        icon: "warning",
        confirmButtonText: "Si, descargar",
        showCancelButton: true,
        cancelButtonText: "Visualizar"
    }).then((result) => {
        if (result.isConfirmed) {
            var url = "http://localhost/Sistema-Control/controllers/generatePDFController.php?op=generateListadoVentasPdf&cli_id=" + dataFiltro.cli_id + "&tipo_pago=" + dataFiltro.tipo_pago + "&fecha_desde=" + dataFiltro.fecha_desde + "&fecha_hasta=" + dataFiltro.fecha_hasta + "&suc_id=" + i_suc_id + "&download=" + 1;
            window.open(url, "_blank");
        } else {
            var url = "http://localhost/Sistema-Control/controllers/generatePDFController.php?op=generateListadoVentasPdf&cli_id=" + dataFiltro.cli_id + "&tipo_pago=" + dataFiltro.tipo_pago + "&fecha_desde=" + dataFiltro.fecha_desde + "&fecha_hasta=" + dataFiltro.fecha_hasta + "&suc_id=" + i_suc_id + "&download=" + 0;
            window.open(url, "_blank");
        }
    });
}