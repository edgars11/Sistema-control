function init() {
    $('#mantenimiento_form').on("submit", function (e) {
        guardarYEditar(e);
    })
}

function guardarYEditar(e) {

    e.preventDefault();

    var formData = new FormData($('#mantenimiento_form')[0]);
    formData.append('emp_id', $('#emp_idx').val());
    console.log($('#emp_idx').val());
    console.log(formData.get('prov_id'));
    console.log(formData.get('prov_nombre'));

    $.ajax({
        url: "../../controllers/proveedorController.php?op=guardar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (data) {
            console.log("guardado" + data);
            $('#table_data').DataTable().ajax.reload();
            $('#modalMantenimiento').modal('hide');
            swal.fire({
                title: "Proveedor",
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
            url: "../../controllers/proveedorController.php?op=listar",
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
});

function editar(prov_id) {
    $.post("../../controllers/proveedorController.php?op=mostrar", { prov_id: prov_id }, function (data) {
        data = JSON.parse(data);
        $('#prov_id').val(data.prov_id);
        $('#prov_nombre').val(data.prov_nombre);
        $('#prov_direccion').val(data.prov_direccion);
        $('#prov_telefono').val(data.prov_telefono);
        $('#prov_correo').val(data.prov_correo);
        $('#prov_ruc').val(data.prov_ruc);
        console.log(data);
    })
    $('#lbTitulo').html('Editar Registro');
    $('#modalMantenimiento').modal('show');
}

function eliminar(prov_id) {
    console.log(prov_id);
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
            $.post("../../controllers/proveedorController.php?op=eliminar", { prov_id: prov_id, emp_id: $('#emp_idx').val() }, function (data) {
                console.log(data);
            })

            // Recarga los datos de la tabla
            $('#table_data').DataTable().ajax.reload();

            // Muestra notificación de la eliminación
            swal.fire({
                title: "Proveedor",
                text: "Eliminado Correctamente!",
                icon: "success"
            });
        }
    });
}

$(document).on("click", "#btn_nuevo", function () {
    // Limpiamos los campos del modal
    $('#prov_id').val('');
    $('#prov_nombre').val('');
    $('#prov_ruc').val('');
    $('#prov_telefono').val('');
    $('#prov_direccion').val('');
    $('#prov_correo').val('');
    $('#lbTitulo').html('Nuevo Registro');
    $('#mantenimiento_form')[0].reset();
    // Mostramos el modal
    $('#modalMantenimiento').modal('show');

});

init();