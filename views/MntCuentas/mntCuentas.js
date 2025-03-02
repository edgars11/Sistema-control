var suc_idx = $('#suc_idx').val();
var emp_idx = $('#emp_idx').val();
var usu_idx = $('#usu_idx').val();

function init() {
    $('#mantenimiento_form').on("submit", function (e) {
        guardarYEditar(e);
    })

    $('#FormCreacionCtaCli').on("submit", function (e) {
        guardarYEditar(e);
    })
}

function guardarYEditar(e) {

    e.preventDefault();

    var fecha = new Date();

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
});

function editar(cta_id) {
    $.post("../../controllers/categoriaController.php?op=mostrar", { cta_id: cta_id }, function (data) {
        data = JSON.parse(data);
        $('#cta_id').val(data.cta_id);
        $('#cta_nombre').val(data.cta_nombre);
        console.log(data);
    })
    $('#lbTitulo').html('Editar Registro');
    $('#modalMantenimiento').modal('show');
}

function eliminar(cta_id) {
    console.log(cta_id);
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
            $.post("../../controllers/categoriaController.php?op=eliminar", { cta_id: cta_id, suc_id: $('#suc_idx').val() }, function (data) {
                console.log(data);
            })

            // Recarga los datos de la tabla
            $('#table_data').DataTable().ajax.reload();

            // Muestra notificación de la eliminación
            swal.fire({
                title: "Categoria",
                text: "Eliminado Correctamente!",
                icon: "success"
            });
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
        $('#cli_id').val(cli_id);
        $('#cli_nombre').val(data.cli_nombre);
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

init();