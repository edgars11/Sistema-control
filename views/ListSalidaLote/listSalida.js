var i_suc_id = $('#suc_idx').val();
var emp_idx = $('#emp_idx').val();
var com_idx = $('#com_idx').val();

var fromDate;
var toDate;

const dataFiltro = {
    lote_id: null,
    cli_id: null,
    salida_tipo: null,
    fecha_desde: null,
    fecha_hasta: null,
    suc_id: i_suc_id
}

var clienteContacto = "";

function init() {
    $('#updateRegSalida').on("submit", function (e) {
        guardarMovimiento(e);
    });
}
function guardarMovimiento(e) {
    e.preventDefault();
    var formData = new FormData($('#updateRegSalida')[0]);
    formData.append('suc_id', i_suc_id);

    var idCliente = $('#cli_id').val();
    formData.append('cli_id', idCliente);

    if (formData.get('sal_tipo') === 'Seleccionar') {
        swal.fire({
            title: "Datos incompletos",
            text: "Seleccione un tipo de producto!",
            icon: "warning"
        });
        return;
    }

    if (formData.get('sal_total').length === 0 || formData.get('sal_cantidad').length === 0
        || formData.get('sal_peso').length === 0 || formData.get('sal_fecha').length === 0) {
        swal.fire({
            title: "Datos incompletos",
            text: "Complete todos los campos!",
            icon: "warning"
        });
        return;
    }

    // for (const value of formData.values()) {
    //     console.log(value);
    // }

    var salida_id = $('#salida_id').val();

    if (parseInt(salida_id) > 0) {
        swal.fire({
            title: "Confirmación!",
            text: "¿Desea modificar el registro de pedido?",
            icon: "warning",
            confirmButtonText: "Si",
            showCancelButton: true,
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "../../controllers/salidaLoteController.php?op=guardar",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        if (data.length === 0) {
                            // Recarga los datos de la tabla
                            cargarTabla(dataFiltro);
                            swal.fire({
                                title: "Registro Pedido",
                                text: "Modificación exitosa!",
                                icon: "success"
                            });
                            $('#modalEditSalida').modal('hide');
                        }
                    }
                });
            } else {
                $('#modalEditSalida').modal('hide');
            }
        });
    }
}
$(document).ready(function () {

    $('#btnWhatsappCli').hide();
    fromDate = getDate(new Date());
    toDate = getDate(new Date());

    $('#total_cantidad').html('$0.00');
    $('#total_peso').html('$0.00');
    $('#total_vendido').html('$0.00');

    var fecha = new Date();
    var hoy = formatDate(fecha);
    document.getElementById("fecha_desde").value = hoy;
    document.getElementById("fecha_hasta").value = hoy;

    dataFiltro.fecha_desde = fromDate;
    dataFiltro.fecha_hasta = toDate;

    cargarTabla(dataFiltro);

    // Se obtienen los lotes disponibles
    $.post("../../controllers/loteController.php?op=combo", { suc_id: i_suc_id }, function (data) {
        $('#lote_idIng').html(data);
    });


});
$(document).on("click", "#btnFiltro", function () {
    fromDate = $('#fecha_desde').val();
    toDate = $('#fecha_hasta').val();
    lote_id = $('#lote_idIng').val();
    cli_id = $('#cli_id').val();
    tipo_prod = $('#tipo_prod').val();

    if (lote_id !== '0') {
        dataFiltro.lote_id = lote_id;
    } else {
        dataFiltro.lote_id = null;
    }

    if (cli_id.length > 0) {
        dataFiltro.cli_id = cli_id;
    } else {
        dataFiltro.cli_id = null;
    }

    if (tipo_prod !== 'Seleccionar') {
        dataFiltro.salida_tipo = tipo_prod;
    } else {
        dataFiltro.salida_tipo = null;
    }

    dataFiltro.fecha_desde = fromDate;
    dataFiltro.fecha_hasta = toDate;

    cargarTabla(dataFiltro);
});
$(document).on("click", "#buscarCliente", function () {
    // Cargamos clientes
    cargarCliente();
    // Mostramos el modal
    $('#modalClientes').modal('show');
});
function resetClient() {
    $('#cli_nombre').val('');
    $('#cli_id').val('');
    $('#modalClientes').modal('hide');
    $('#btnWhatsappCli').hide();
}
function cargarTabla(dataFiltro) {
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
            url: "../../controllers/salidaLoteController.php?op=listadoSalida",
            type: "post",
            data: dataFiltro
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
    cargarTotables(dataFiltro);
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
        console.log(data);
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
function formatDate(dateObject = new Date()) {
    var year = dateObject.getFullYear();
    var month = dateObject.getMonth() + 1;
    var month = month > 9 ? month : "0" + month;
    var day = dateObject.getDate() > 9 ? dateObject.getDate() : "0" + dateObject.getDate();
    return year + "-" + month + "-" + day;
}
function selectCliente(cli_id) {

    $.post("../../controllers/clienteController.php?op=byID", { cli_id: cli_id }, function (data) {
        if (data.length > 0) {
            data = JSON.parse(data);
            $('#cli_nombre').val(data.cli_nombre);
            $('#cli_id').val(data.cli_id);
            clienteContacto = data.cli_telefono;
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
function generarReporte() {

    // if (cli_id.length > 0) {
    //     data.cli_id = cli_id;
    // }else{
    //     swal.fire({
    //         title: "Generación Reporte",
    //         text: "Seleccione un cliente para continuar con el reporte!",
    //         icon: "warning"
    //     });
    //     return;
    // }

    swal.fire({
        title: "Confirmación!",
        text: "Desea descargar el pdf de registro de salida?",
        icon: "warning",
        confirmButtonText: "Si, descargar",
        showCancelButton: true,
        cancelButtonText: "Visualizar"
    }).then((result) => {
        if (result.isConfirmed) {
            var url = "http://localhost/Sistema-Control/controllers/generatePDFController.php?op=generateListadoSalidaPdf&lote_id=" + dataFiltro.lote_id + "&cli_id=" + dataFiltro.cli_id + "&salida_tipo=" + dataFiltro.salida_tipo + "&fecha_desde=" + dataFiltro.fecha_desde + "&fecha_hasta=" + dataFiltro.fecha_hasta + "&suc_id=" + i_suc_id + "&emp_id=" + emp_idx + "&com_id=" + com_idx + "&download=" + 1;
            window.open(url, "_blank");
        } else {
            var url = "http://localhost/Sistema-Control/controllers/generatePDFController.php?op=generateListadoSalidaPdf&lote_id=" + dataFiltro.lote_id + "&cli_id=" + dataFiltro.cli_id + "&salida_tipo=" + dataFiltro.salida_tipo + "&fecha_desde=" + dataFiltro.fecha_desde + "&fecha_hasta=" + dataFiltro.fecha_hasta + "&suc_id=" + i_suc_id + "&emp_id=" + emp_idx + "&com_id=" + com_idx + "&download=" + 0;
            window.open(url, "_blank");
        }
    });
}
function eliminar(salida_id) {
    $.post("../../controllers/salidaLoteController.php?op=mostrarByID",
        { salida_id: salida_id },
        function (data) {
            data = JSON.parse(data);
            if (data.salida_vpagado === 'C' || data.salida_vpagado === 'A') {
                swal.fire({
                    title: "Movimiento con Pago",
                    text: "Error!! El recibo no se puede eliminar. Tiene registro de pago",
                    icon: "warning"
                });
            } else {
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
                        $.post("../../controllers/salidaLoteController.php?op=deleteout",
                            { salida_id: salida_id, suc_id: i_suc_id },
                            function (data) {
                                data = JSON.parse(data);
                                console.log(data);
                                if (data.exec) {
                                    // Recarga los datos de la tabla
                                    cargarTabla(dataFiltro);
                                    // Muestra notificación de la eliminación
                                    swal.fire({
                                        title: "Movimiento",
                                        text: "Eliminado Correctamente!",
                                        icon: "success"
                                    });
                                } else {
                                    cargarTabla(dataFiltro);
                                    swal.fire({
                                        title: "Movimiento",
                                        text: "Error al eliminar movimiento!",
                                        icon: "error"
                                    });
                                }
                            });
                    }
                });
            }
        })
}
function editar(salida_id) {
    $.post("../../controllers/salidaLoteController.php?op=mostrarByID",
        { salida_id },
        function (data) {
            data = JSON.parse(data);
            date = data.salida_fecha.split(" ");
            $('#cli_nombreM').val(data.cli_nombre);
            $('#salida_id').val(data.salida_id);
            $('#sal_tipo').val(data.salida_tipo);
            $('#sal_fecha').val(date[0]);
            $('#sal_cantidad').val(data.salida_cantidad);
            $('#sal_peso').val(data.salida_peso);
            $('#sal_tara').val(data.salida_tara);
            $('#sal_peso_neto').val(data.salida_peso_neto);
            $('#sal_precio').val(data.salida_precio);
            $('#sal_total').val(data.salida_total);
            $('#lote_desc').val(data.lote_descripcion);
            $('#lote_cant').val(data.lote_cant_actual);
            $('#lote_id').val(data.lote_id);
            $('#salida_vpagado').val(data.salida_vpagado);
            var estadoRecibo = data.salida_vpagado;
            var classEstado = '';
            if (estadoRecibo === 'C') {
                estadoRecibo = 'Cancelado';
                classEstado = 'badge badge-soft-success fs-20 form-control';
            } else if (estadoRecibo === 'A') {
                estadoRecibo = 'Abonado';
                classEstado = 'badge badge-soft-warning fs-20 form-control';
            } else if (estadoRecibo === 'N') {
                estadoRecibo = 'Pendiente';
                classEstado = 'badge badge-soft-danger fs-20 form-control';
            }
            // $('#salida_vpagado').val(estadoRecibo);
            $('#salida_estado').attr('class', classEstado);
            $('#salida_estado').html(estadoRecibo);

            if (data.salida_vpagado === 'C' || data.salida_vpagado === 'A') {
                $('#sal_cantidad').prop('readonly', true);
                $('#sal_peso').prop('readonly', true);
                $('#sal_tara').prop('readonly', true);
                $('#sal_peso_neto').prop('readonly', true);
                $('#sal_total').prop('readonly', true);
                $('#sal_precio').prop('readonly', true);
            } else {
                $('#sal_cantidad').prop('readonly', false);
                $('#sal_peso').prop('readonly', false);
                $('#sal_tara').prop('readonly', false);
                $('#sal_precio').prop('readonly', false);
            }
        });


    // Mostramos el modal
    $('#modalEditSalida').modal('show');
}
function validarCantidad(tipo) {
    var canAct = $('#lote_cant').val();
    var canSal = $('#sal_cantidad').val();
    var canPeso = $('#sal_peso').val();
    var canTara = $('#sal_tara').val();
    var canPrecio = $('#sal_precio').val();
    var canPesoNeto = $('#sal_peso_neto').val();

    var canActNumber = 0;
    var canSalNumber = 0;
    var pesoNeto = 0;
    var total = 0;

    if (tipo === 'C') {

        if (canSal !== "" && canAct !== "") {
            canActNumber = Number.parseInt(canAct);
            canSalNumber = Number.parseInt(canSal);

            if (canSalNumber > canActNumber) {
                $('#sal_cantidad').val('');
                swal.fire({
                    title: "Cantidad incorrecta",
                    text: "La cantidad ingresada sobrepasa a la Capacidad actual!",
                    icon: "error"
                });
            }
        } else {
            swal.fire({
                title: "Ingrese datos válidos",
                text: "Valide que los datos ingresados sean correctos!",
                icon: "error"
            });
        }
    }

    if (tipo === 'N') {
        if (canPeso.length > 0 && canTara.length > 0) {
            pesoNeto = (parseFloat(canPeso) - parseFloat(canTara))
            $('#sal_peso_neto').val(pesoNeto.toFixed(2));
        }
    }

    if (tipo === 'T') {
        if (canPrecio.length > 0 && canPesoNeto.length > 0) {
            total = (parseFloat(canPrecio) * parseFloat(canPesoNeto))
            $('#sal_total').val(total.toFixed(2));
        }
    }
}
function openWhatsappview() {
    var mensaje = encodeURIComponent("Hola, se adjunta el reporte de listado de pedidos realizados. Gracias");
    var url = "https://wa.me/593" + clienteContacto + "?text=" + mensaje;
    window.open(url, "_blank");

}

init();