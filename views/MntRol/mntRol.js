const suc_idx = $('#suc_idx').val();
var i_rol_id;

function init() {
    $('#mantenimiento_form').on("submit", function (e) {
        guardarYEditar(e);
    })
}

function guardarYEditar(e) {

    e.preventDefault();

    var formData = new FormData($('#mantenimiento_form')[0]);
    formData.append('suc_id', suc_idx);
    console.log(suc_idx);
    console.log(formData.get('rol_id'));
    console.log(formData.get('rol_nombre'));

    $.ajax({
        url: "../../controllers/rolController.php?op=guardar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (data) {
            console.log("guardado");
            $('#table_data').DataTable().ajax.reload();
            $('#modalMantenimiento').modal('hide');
            swal.fire({
                title: "Rol",
                text: "Ejecución exitosa!",
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
            url: "../../controllers/rolController.php?op=listar",
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

function editar(rol_id) {
    $.post("../../controllers/rolController.php?op=mostrar", { rol_id: rol_id, suc_id: suc_idx }, function (data) {
        data = JSON.parse(data);
        $('#rol_id').val(data.rol_id);
        $('#rol_nombre').val(data.rol_nombre);
        console.log(data);
    })
    $('#lbTitulo').html('Editar Registro');
    $('#modalMantenimiento').modal('show');
}

function permisos(rol_id) {
    i_rol_id = rol_id;

    $('#table_permiso').DataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
        ],
        "ajax": {
            url: "../../controllers/menuController.php?op=listar",
            type: "post",
            data: { rol_id: rol_id }
        },
        "bDestroy": true,
        "responsive": true,
        "bInfo": true,
        "iDisplayLength": 5,
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

    $('#modalPermiso').modal('show');
}

function eliminar(rol_id) {
    console.log(rol_id);
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
            $.post("../../controllers/rolController.php?op=eliminar", { rol_id: rol_id, suc_id: $('#suc_idx').val() }, function (data) {
                console.log(data);
            })

            // Recarga los datos de la tabla
            $('#table_data').DataTable().ajax.reload();

            // Muestra notificación de la eliminación
            swal.fire({
                title: "Rol",
                text: "Eliminado Correctamente!",
                icon: "success"
            });
        }
    });
}

function habilitar(mend_id) {
    console.log('ID: ' + mend_id);
    var habilitar = 'S';
    $.post("../../controllers/menuController.php?op=update", { rol_id: i_rol_id, mend_id: mend_id, menu_permi: habilitar }, function (data) {
        // Recarga los datos de la tabla
        $('#table_permiso').DataTable().ajax.reload();
    })
}

function deshabilitar(mend_id) {
    console.log(mend_id);
    var deshabilitar = 'N';
    $.post("../../controllers/menuController.php?op=update", { rol_id: i_rol_id, mend_id: mend_id, menu_permi: deshabilitar }, function (data) {
        // Recarga los datos de la tabla
        $('#table_permiso').DataTable().ajax.reload();
    });
}

$(document).on("click", "#btn_nuevo", function () {
    // Limpiamos los campos del modal
    $('#rol_id').val('');
    $('#rol_nombre').val('');
    $('#lbTitulo').html('Nuevo Registro');
    $('#mantenimiento_form')[0].reset();
    // Mostramos el modal
    $('#modalMantenimiento').modal('show');

});

init();