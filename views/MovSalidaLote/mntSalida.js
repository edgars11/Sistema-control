var suc_idx = $('#suc_idx').val();
var emp_idx = $('#emp_idx').val();

function init() {
    $('#mantenimiento_formIng').on("submit", function (e) {
        guardarMovimiento(e);
    });
}

function guardarMovimiento(e) {
    e.preventDefault();
    var formData = new FormData($('#mantenimiento_formIng')[0]);
    formData.append('suc_id', suc_idx);
    console.log(formData.get('sal_fecha'));
    console.log(formData.get('suc_id'));

    var idCliente = $('#cli_id').val();
    var idLote = $('#lote_idIng').val();
    var sal_total = $('#sal_total').val();
    var sal_cantidad = $('#sal_cantidad').val();
    var sal_peso = $('#sal_peso').val();
    var sal_fecha = $('#sal_fecha').val();
    var sal_tipo = $('#sal_tipo').val();

    if (idCliente.length == 0) {
        swal.fire({
            title: "Datos incompletos",
            text: "Seleccione un Cliente para continuar!",
            icon: "warning"
        });
        return;
    }

    if (idLote === 'Seleccionar') {
        swal.fire({
            title: "Datos incompletos",
            text: "Seleccione un Lote y complete los datos!",
            icon: "warning"
        });
        return;
    }

    if (sal_tipo === 'Seleccionar') {
        swal.fire({
            title: "Datos incompletos",
            text: "Seleccione un tipo de producto!",
            icon: "warning"
        });
        return;
    }

    if (sal_total.length === 0 || sal_cantidad.length === 0 || sal_peso.length === 0 || sal_fecha.length === 0) {
        swal.fire({
            title: "Datos incompletos",
            text: "Complete todos los campos!",
            icon: "warning"
        });
        return;
    }

    $.ajax({
        url: "../../controllers/salidaLoteController.php?op=guardar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (data) {
            if (data.length === 0) {
                limpiarCampos();
                // Recarga los datos de la tabla
                $('#table_data').DataTable().ajax.reload();
                swal.fire({
                    title: "Salida Lote",
                    text: "Ejecución exitosa!",
                    icon: "success"
                });
            }

        }
    });

}

$(document).ready(function () {

    var dateSalida = getDate(new Date());

    // Se obtienen los lotes disponibles
    $.post("../../controllers/loteController.php?op=combo", { suc_id: suc_idx }, function (data) {
        $('#lote_idIng').html(data);
    });

    /* Event change cuando se selecciona un lote de la lista */
    $("#lote_idIng").change(function () {
        $("#lote_idIng").each(function () {
            lote_id = $(this).val();

            $.post("../../controllers/loteController.php?op=mostrar", { lote_id: lote_id }, function (data) {
                data = JSON.parse(data);
                $('#lote_cant_act').val(data.lote_cant_actual);
            });
        });
    });

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
            url: "../../controllers/salidaLoteController.php?op=listar",
            type: "post",
            data: { salida_fecha: dateSalida, suc_id: suc_idx }
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

    $('#cli_identificacion').keypress(function (e) {
        var keycode = (e.keyCode ? e.keyCode : e.which);
        if (keycode == '13') {
            buscarClienteCed();
            return false;
        }
    });

});

$(document).on("click", "#buscarCliente", function () {
    // Cargamos clientes
    cargarCliente();
    // Mostramos el modal
    $('#modalClientes').modal('show');
});

function validarCantidad(tipo) {
    var canAct = $('#lote_cant_act').val();
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

function validaIsNumber(dato) {
    const regex = /^[0-9]*$/;
    const onlyNumbers = regex.test(dato); // true
    return onlyNumbers;
}

function buscarClienteCed() {
    var cedula = $('#cli_identificacion').val();
    if (cedula.length > 0) {
        // Elimina el registro
        $.post("../../controllers/clienteController.php?op=byCED", { cli_ruc: cedula, emp_id: $('#emp_idx').val() }, function (data) {
            if (data.length > 0) {
                data = JSON.parse(data);
                $('#cli_nom').val(data.cli_nombre);
                $('#cli_contacto').val(data.cli_telefono);
                $('#cta_cli').val('0.00');
                $('#cli_id').val(data.cli_id);
            } else {
                swal.fire({
                    title: "Error consulta",
                    text: "Identificación no registrada!",
                    icon: "warning"
                });
                $('#cli_identificacion').val('');
            }
        })
    }
}

function getDate(date) {
    var dateString = new Date(date.getTime() - (date.getTimezoneOffset() * 60000))
        .toISOString()
        .split("T")[0];

    return dateString;
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

function selectCliente(cli_id) {

    $.post("../../controllers/clienteController.php?op=byID", { cli_id: cli_id }, function (data) {
        if (data.length > 0) {
            data = JSON.parse(data);
            $('#cli_nom').val(data.cli_nombre);
            $('#cli_contacto').val(data.cli_telefono);
            $('#cta_cli').val(data.cta_monto);
            $('#cli_id').val(data.cli_id);
            $('#cli_identificacion').val(data.cli_ruc);
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
function limpiarCampos() {
    $('#lote_cant_act').val('');
    $('#sal_total').val('');
    $('#sal_cantidad').val('');
    $('#sal_peso').val('');
    $('#sal_peso_neto').val('');
}

function eliminar(salida_id) {
    console.log(salida_id);
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
                { salida_id: salida_id },
                function (data) {
                    data = JSON.parse(data);
                    console.log(data);
                    if (data.exec) {
                        // Recarga los datos de la tabla
                        $('#table_data').DataTable().ajax.reload();

                        // Muestra notificación de la eliminación
                        swal.fire({
                            title: "Movimiento",
                            text: "Eliminado Correctamente!",
                            icon: "success"
                        });
                    }else{
                        swal.fire({
                            title: "Movimiento",
                            text: "Error al eliminar movimiento!",
                            icon: "error"
                        });
                    }
                })


        }
    });
}
init();