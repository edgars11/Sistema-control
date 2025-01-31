var suc_id = $('#suc_idx').val();

function init() {
    $('#mantenimiento_form').on("submit", function (e) {
        guardarYEditar(e);
    })
}

function guardarYEditar(e) {

    e.preventDefault();

    var formData = new FormData($('#mantenimiento_form')[0]);
    formData.append('suc_id', $('#suc_idx').val());
    formData.append('mon_id', 1);
    formData.append('prod_img', '');
    console.log($('#suc_idx').val());
    console.log(formData.get('prod_id'));
    console.log(formData.get('prod_nombre'));
    console.log(formData.get('cat_id'));
    console.log(formData.get('unm_id'));
    console.log(formData.get('prod_stock'));
    console.log(formData.get('prod_tipo_producto'));

    $.ajax({
        url: "../../controllers/productoController.php?op=guardar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (data) {
            console.log("guardado" + data);
            $('#table_data').DataTable().ajax.reload();
            $('#modalMantenimiento').modal('hide');
            swal.fire({
                title: "Producto",
                text: "Ejecución exitosa!",
                icon: "success"
            });
        }
    });
}

$(document).ready(function () {

    // Se obtienen las categorias
    $.post("../../controllers/categoriaController.php?op=combo", { suc_id: suc_id }, function (data) {
        $('#cat_id').html(data);
    });

    // Se obtienen las unidades de medida
    $.post("../../controllers/unidadController.php?op=combo", { suc_id: suc_id }, function (data) {
        $('#unm_id').html(data);
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
            url: "../../controllers/productoController.php?op=listar",
            type: "post",
            data: { suc_id: $('#suc_idx').val() }
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

function editar(prod_id) {
    $.post("../../controllers/productoController.php?op=mostrar", { prod_id: prod_id, suc_id: suc_id }, function (data) {
        data = JSON.parse(data);
        $('#prod_id').val(data.prod_id);
        $('#prod_nombre').val(data.prod_nombre);
        $('#prod_descripcion').val(data.prod_descripcion);
        $('#prod_pcompra').val(data.prod_pcompra);
        $('#prod_pventa').val(data.prod_pventa);
        $('#prod_pventa').val(data.prod_pventa);
        $('#prod_stock').val(data.prod_stock);
        $('#prod_cod_barra').val(data.prod_cod_barra);
        $('#prod_fechaven').val(data.prod_fechaven);
        $('#cat_id').val(data.cat_id).trigger('change');
        $('#unm_id').val(data.unm_id).trigger('change');
        $('#prod_tipo_producto').val(data.prod_tipo_producto).trigger('change');
        $('#pre_image').html(data.prod_img);
    })
    $('#lbTitulo').html('Editar Registro');
    $('#modalMantenimiento').modal('show');

}

function eliminar(prod_id) {
    console.log(prod_id);
    swal.fire({
        title: "Eliminar!",
        text: "¿Desea eliminar el registro?",
        icon: "warning",
        confirmButtonText: "Si",
        showCancelButton: true,
        cancelButtonText: "No"
    }).then((result) => {
        if (result.value) {
            // Elimina el registro
            $.post("../../controllers/productoController.php?op=eliminar", { prod_id: prod_id, suc_id: $('#suc_idx').val() }, function (data) {
                console.log(data);
            })

            // Recarga los datos de la tabla
            $('#table_data').DataTable().ajax.reload();

            // Muestra notificación de la eliminación
            swal.fire({
                title: "Producto",
                text: "Eliminado Correctamente!",
                icon: "success"
            });
        }
    });
}

$(document).on("click", "#btn_nuevo", function () {
    // Limpiamos los campos del modal
    $('#prod_id').val('');
    $('#prod_nombre').val('');
    $('#prod_descripcion').val('');
    $('#prod_pcompra').val('');
    $('#prod_pventa').val('');
    $('#prod_stock').val('');
    $('#lbTitulo').html('Nuevo Registro');
    $('#mantenimiento_form')[0].reset();
    $('#pre_image').html('<img src="../../assets/products/no_image.png" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="product-image"><input type="hidden" name="hidden_producto_img" value="" />');
    // Mostramos el modal
    $('#modalMantenimiento').modal('show');



});

$(document).on("click", "#btnRemovePhoto", function () {
    // Limpiamos los campos del modal
    $('#prod_img').val('');
    $('#pre_image').html('<img src="../../assets/products/no_image.png" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="product-image"><input type="hidden" name="hidden_producto_img" value="" />');
});

function filePreview(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#pre_image').html('<img src="' + e.target.result + '" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="product-image">')
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$(document).on('change', '#prod_img', function () {
    filePreview(this);
});

init();