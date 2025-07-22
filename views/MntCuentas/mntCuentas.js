var suc_idx = $('#suc_idx').val();
var emp_idx = $('#emp_idx').val();
var usu_idx = $('#usu_idx').val();

var fecha = new Date();
var w_cliSelected = 0;

function init() {
    $('#FormUpdateCtaCli').on("submit", function (e) {
        updateCtaCli(e);
    })

    $('#FormCreacionCtaCli').on("submit", function (e) {
        guardarYEditar(e);
    })
}

function guardarYEditar(e) {

    e.preventDefault();

    var formData = new FormData($('#FormCreacionCtaCli')[0]);
    formData.append('suc_id', $('#suc_idx').val());
    formData.append('cta_fecha', formatDate(fecha));
    formData.append('usu_id', usu_idx);

    $.ajax({
        url: "../../controllers/cuentasController.php?op=guardar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (data) {
            data = JSON.parse(data);
            console.log(data);
            $('#table_data').DataTable().ajax.reload();
            $('#modalCrearCuenta').modal('hide');
            swal.fire({
                title: "Cuenta Cliente",
                text: "Se registró la cuenta correctamente!",
                icon: "success"
            });
        }
    });
}

function updateCtaCli(e) {

    e.preventDefault();
    var cta_id = $('#cta_id').val();
    // var pago_id = $('#pago_id').val();
    var pagc_monto = $('#cta_montoUpd').val();
    var pagc_obs = $('#cta_obs').val();
    var mov_tipo = $('#mov_tipo').val();

    /* TODO: Validación de campos de ventas */
    if (cta_id.length == 0 || pagc_monto.length == 0) {
        // Muestra notificación de la eliminación
        swal.fire({
            title: "Pago",
            text: "Error! Campos incompletos",
            icon: "error"
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

    if (mov_tipo == 'AD') {
        var formData = new FormData($('#FormUpdateCtaCli')[0]);
        formData.append('suc_id', $('#suc_idx').val());
        formData.append('cta_fecha', formatDate(fecha));
        formData.append('usu_id', usu_idx);

        $.ajax({
            url: "../../controllers/cuentasController.php?op=update",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (data) {
                data = JSON.parse(data);
                $('#table_data').DataTable().ajax.reload();
                $('#modalUpdateCuenta').modal('hide');
                swal.fire({
                    title: "Cuenta Cliente",
                    text: "Se registró la cuenta correctamente!",
                    icon: "success"
                });
            }
        });
    } else {
        $.post("../../controllers/pagoController.php?op=guardarPago", {
            cta_id: cta_id,
            pago_id: 1,
            pagc_obs: pagc_obs,
            pagc_monto: pagc_monto,
            suc_id: suc_idx
        }, function (data) {
            data = JSON.parse(data);

            if (data.success == true) {
                $('#cta_id').val('');
                $('#cta_montoUpd').val('');
                $('#cta_obs').val('');
                $('#table_data').DataTable().ajax.reload();
                $('#modalUpdateCuenta').modal('hide');
                swal.fire({
                    title: "Cuenta cliente",
                    text: "Actualización de la cuenta se realizó correctamente!",
                    icon: "success"
                });
            } else {
                swal.fire({
                    title: "Cuenta cliente",
                    text: "Hubo un error al guardar el cobro de cuenta!",
                    icon: "warning"
                });
            }
        })
    }


}

$(document).ready(function () {

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
            url: "../../controllers/cuentasController.php?op=listar",
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

    $("#mov_tipo").change(function () {
        $("#mov_tipo").each(function () {
            tipoMov = $(this).val();
            if (tipoMov === 'AD') {
                $('#tipoMovimiento').html('<span class="badge badge-soft-success text-uppercase fs-22">+</span>');
            } else {
                $('#tipoMovimiento').html('<span class="badge badge-soft-danger text-uppercase fs-22">-</span>');
            }
        });
    });
});

function editar(cta_id) {
    $.post("../../controllers/cuentasController.php?op=byCta", { cta_id: cta_id, suc_id: suc_idx }, function (data) {
        data = JSON.parse(data);
        $('#cta_id').val(data.cta_id);
        $('#cli_nombre').val(data.cli_nombre);
        $('#cta_montoAct').val(data.cta_monto);
        $('#cta_nuevo_val').val(data.cta_monto);
        console.log(data);
    })
    $('#cta_montoUpd').val('');
    $('#lbTitulo').html('Editar Registro');
    $('#modalUpdateCuenta').modal('show');
}

function eliminar(cta_id) {
    swal.fire({
        title: "Eliminar!",
        text: "Desea eliminar la cuenta del cliente?",
        icon: "warning",
        confirmButtonText: "Si",
        showCancelButton: true,
        cancelButtonText: "No"
    }).then((result) => {
        if (result.value) {
            // Elimina el registro
            $.post("../../controllers/cuentasController.php?op=delete", { cta_id: cta_id, suc_id: suc_idx }, function (data) {
                data = JSON.parse(data);
                if (data) {
                    // Recarga los datos de la tabla
                    $('#table_data').DataTable().ajax.reload();

                    // Muestra notificación de la eliminación
                    swal.fire({
                        title: "Cuenta Cliente",
                        text: "Eliminado Correctamente!",
                        icon: "success"
                    });
                } else {
                    swal.fire({
                        title: "Cliente Cliente",
                        text: "Hubo un error al eliminar la cuenta del cliente!",
                        icon: "error"
                    });
                }
            })


        }
    });
}

function listadoMovimientos(cta_id) {
    var nombreCliente = $('')
    cargarTablaMovimientos(cta_id);
    $('#lbTitulo').val()
    $('#modalMovimientos').modal('show');
}

function cargarTablaMovimientos(cta_id) {
    $('#tb_listadoMovimientos').DataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
        ],
        "ajax": {
            url: "../../controllers/cuentasController.php?op=movimientos",
            type: "post",
            data: { cta_id: cta_id, suc_id: suc_idx }
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

function addCuenta() {
    $('#cli_id').val('');
    $('#cli_nombre').val('');

    $('#modalCrearCuenta').modal('show');
}

function seleccionarCliente(cli_id) {

    $.post("../../controllers/clienteController.php?op=byID", { cli_id: cli_id }, function (data) {
        data = JSON.parse(data);
        w_cliSelected = cli_id;
        $('#cli_id').val(cli_id);
        $('#cli_nombreC').val(data.cli_nombre);
    })

    $('#modalListaClientes').modal('hide');
}

function listarCliente() {
    $('#tb_listadoClietes').DataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
        ],
        "ajax": {
            url: "../../controllers/clienteController.php?op=listarCSC",
            type: "post",
            data: { emp_id: emp_idx }
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
    $('#modalListaClientes').modal('show');
}

$(document).on("click", "#btn_nuevo", function () {
    // Limpiamos los campos del modal
    $('#cta_id').val('');
    $('#cta_nombre').val('');
    $('#lbTitulo').html('Nuevo Registro');
    $('#mantenimiento_form')[0].reset();
    // Mostramos el modal
    $('#modalMantenimiento').modal('show');

});

$(document).on("click", "#generarPDFMov", function () {
    // Limpiamos los campos del modal
    var cuentaID = $('#cta_idMov').val();
    var fechaDesde = $('#fecha_desde').val();
    var fechaHasta = $('#fecha_hasta').val();
    var tipoMov = $('#tipo_mov').val();
    var idCliente = $('#cli_id').val();
    console.log(cuentaID + " " + fechaDesde + " " + fechaHasta + " " + tipoMov + " " + idCliente);

    swal.fire({
        title: "Confirmación!",
        text: "¿Desea descargar el pdf de listado de movimientos?",
        icon: "warning",
        confirmButtonText: "Si, descargar",
        showCancelButton: true,
        cancelButtonText: "Visualizar"
    }).then((result) => {
        var tipoDownload = 0;
        if (result.isConfirmed) {
            tipoDownload = 1;
        } else {
            tipoDownload = 0;
        }
        var url = "http://localhost/Sistema-Control/controllers/generatePDFController.php?op=generarListadoMovCta&suc_id=" + suc_idx + "&download=" + tipoDownload + "&cli_id=" + w_cliSelected + "&fecha_desde=" + fechaDesde + "&fecha_hasta=" + fechaHasta + "&cta_id=" + cuentaID + "&tipo_mov=" + tipoMov;
        window.open(url, "_blank");

        $('#generarListadoMovPDF').modal('hide');
    });

});

function formatDate(dateObject = new Date()) {
    var year = dateObject.getFullYear();
    var month = dateObject.getMonth() + 1;
    var month = month > 9 ? month : "0" + month;
    var day = dateObject.getDate() > 9 ? dateObject.getDate() : "0" + dateObject.getDate();

    var hora = dateObject.getHours();
    var minutos = dateObject.getMinutes();
    var segundos = dateObject.getSeconds();
    return year + "-" + month + "-" + day + " " + hora + ":" + minutos + ":" + segundos;
}

function calcularValor() {
    var canAct = $('#cta_montoAct').val();
    var canMod = $('#cta_montoUpd').val();
    var tipo = $('#mov_tipo').val();

    var canActNumber = 0;
    var canModNumber = 0;
    var total = 0;

    if (canAct !== "" && canMod !== "") {
        canActNumber = parseFloat(canAct);
        canModNumber = parseFloat(canMod);

        if (canModNumber > canActNumber) {
            $('#cta_montoUpd').val('');
            swal.fire({
                title: "Cantidad incorrecta",
                text: "El monto ingresado sobrepasa a la monto actual!",
                icon: "error"
            });
        } else {
            if (tipo === 'AD') {
                total = canActNumber + canModNumber;
            } else {
                total = canActNumber - canModNumber;
            }
            $('#cta_nuevo_val').val(total);

        }
    }

}

function verReporte(cta_id, cli_id) {
    $('#cta_idMov').val(cta_id);
    let today = getDate(new Date());
    $('#fecha_desde').val(today);
    $('#fecha_hasta').val(today);
    w_cliSelected = cli_id;
    $('#generarListadoMovPDF').modal('show');
}

function generarReporte() {
    swal.fire({
        title: "Confirmación!",
        text: "¿Desea descargar el pdf de listado de cuentas?",
        icon: "warning",
        confirmButtonText: "Si, descargar",
        showCancelButton: true,
        cancelButtonText: "Visualizar"
    }).then((result) => {
        if (result.isConfirmed) {
            var url = "http://localhost/Sistema-Control/controllers/generatePDFController.php?op=generarListadoCPC&suc_id=" + suc_idx + "&download=" + 1;
            window.open(url, "_blank");
        } else {
            var url = "http://localhost/Sistema-Control/controllers/generatePDFController.php?op=generarListadoCPC&suc_id=" + suc_idx + "&download=" + 0;
            window.open(url, "_blank");
        }
    });
}

function getDate(date) {
    var dateString = new Date(date.getTime() - (date.getTimezoneOffset() * 60000))
        .toISOString()
        .split("T")[0];

    return dateString;
}

init();