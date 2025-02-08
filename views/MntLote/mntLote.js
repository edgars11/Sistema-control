var suc_idx = $('#suc_idx').val();
var fechaHoy = null;

function init() {
    $('#mantenimiento_form').on("submit", function (e) {
        guardarYEditar(e);
    });

    $('#mantenimiento_formIng').on("submit", function (e) {
        guardarMovimiento(e);
    });

    $('#form_ingreso_alimento').on("submit", function (e) {
        guardarAlimentoLote(e)
    });


}

function guardarYEditar(e) {

    e.preventDefault();

    var formData = new FormData($('#mantenimiento_form')[0]);
    formData.append('suc_id', suc_idx);

    $.ajax({
        url: "../../controllers/loteController.php?op=guardar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (data) {
            $('#table_data').DataTable().ajax.reload();
            $('#modalMantenimiento').modal('hide');
            swal.fire({
                title: "Ingreso Lote",
                text: "Ejecución exitosa!",
                icon: "success"
            });
        }
    });
}

function guardarMovimiento(e) {
    e.preventDefault();
    var formData = new FormData($('#mantenimiento_formIng')[0]);
    formData.append('suc_id', suc_idx);
    console.log(formData.get('lote_idIng'));
    console.log(formData.get('mov_tipo'));
    console.log(formData.get('mov_cant_ing'));
    console.log(formData.get('lote_can_total'));
    console.log(formData.get('mov_motivo'));
    console.log(formData.get('mov_fecha_ing'));

    $.ajax({
        url: "../../controllers/movimientoLoteController.php?op=guardar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (data) {
            $('#table_data').DataTable().ajax.reload();
            $('#modalMovLote').modal('hide');
            swal.fire({
                title: "Movimiento Lote",
                text: "Ejecución exitosa!",
                icon: "success"
            });
        }
    });

}

function guardarAlimentoLote(e) {
    e.preventDefault();
    var formData = new FormData($('#form_ingreso_alimento')[0]);
    formData.append('suc_id', suc_idx);
    console.log(formData.get('lote_idAli'));
    console.log(formData.get('ali_cantidad'));
    console.log(formData.get('ali_fecha'));
    console.log(formData.get('ali_desc'));

    $.ajax({
        url: "../../controllers/loteController.php?op=guardarConsumo",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (data) {
            data = JSON.parse(data);
            if(data.success){
                $('#table_data').DataTable().ajax.reload();
                $('#modalIngAli').modal('hide');
                swal.fire({
                    title: "Consumo Lote",
                    text: "Se ingresó correctamente!",
                    icon: "success"
                });
            }
            
        }
    });

}

$(document).ready(function () {
    // Se asigna la fecha actual al input
    var fecha = new Date();
    fechaHoy = formatDate(fecha);

    /* Event change cuando se selecciona un lote de la lista */
    $("#lote_idIng").change(function () {
        $("#lote_idIng").each(function () {
            lote_id = $(this).val();

            $.post("../../controllers/loteController.php?op=mostrar", { lote_id: lote_id }, function (data) {
                data = JSON.parse(data);
                $('#lote_capacidad_maxIng').val(data.lote_capacidad_max);
                $('#lote_cant_actual').val(data.lote_cant_actual);
            });
        });
    });

    /* Event change cuando se selecciona un lote de la lista */
    $("#lote_idAli").change(function () {
        $("#lote_idAli").each(function () {
            lote_id = $(this).val();

            $.post("../../controllers/loteController.php?op=mostrar", { lote_id: lote_id }, function (data) {
                data = JSON.parse(data);
                $('#lote_consumo_act').val(data.lote_consumo);
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
            url: "../../controllers/loteController.php?op=listar",
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

    $('#mov_cant_ing').keypress(function (e) {
        var keycode = (e.keyCode ? e.keyCode : e.which);
        if (keycode == '13') {
            calcularTotal();
            return false;
        }
    });

});

$(document).on("click", "#btn_nuevo", function () {
    // Limpiamos los campos del modal
    $('#lote_id').val('');
    $('#lote_descripcion').val('');
    $('#lbTitulo').html('Nuevo Registro');
    $('#mantenimiento_form')[0].reset();
    // Mostramos el modal
    $('#modalMantenimiento').modal('show');
});

$(document).on("click", "#btn_ingreso", function () {

    // Se obtienen los lotes disponibles
    $.post("../../controllers/loteController.php?op=combo", { suc_id: suc_idx }, function (data) {
        $('#lote_idIng').html(data);
    });
    // Limpiamos los campos del modal
    $('#mov_tipo').val('sum');
    $('#mov_fecha_ing').val(fechaHoy);
    $('#lote_capacidad_max').val('0.00');
    $('#lote_cant_actual').val('0.00');
    $('#mov_descr').html('INGRESO');
    $('#lbTituloIng').html('Nuevo Ingreso Lote');
    $('#mantenimiento_formIng')[0].reset();
    // Mostramos el modal
    $('#modalMovLote').modal('show');
});

$(document).on("click", "#btn_perdida", function () {

    // Se obtienen los lotes disponibles
    $.post("../../controllers/loteController.php?op=combo", { suc_id: suc_idx }, function (data) {
        $('#lote_idIng').html(data);
    });
    // Limpiamos los campos del modal
    $('#mov_tipo').val('res');
    $('#lote_capacidad_max').val('0.00');
    $('#lote_cant_actual').val('0.00');
    $('#mov_descr').html('PÉRDIDA');
    $('#lbTituloIng').html('Registro Pérdida Lote');
    $('#mantenimiento_formIng')[0].reset();
    // Mostramos el modal
    $('#modalMovLote').modal('show');
});

$(document).on("click", "#btn_alimento", function () {

    // Se obtienen los lotes disponibles
    $.post("../../controllers/loteController.php?op=combo", { suc_id: suc_idx }, function (data) {
        $('#lote_idAli').html(data);
    });
    // Limpiamos los campos del modal
    $('#lote_consumo_act').val('0');
    $('#mov_fecha_ing').val(fechaHoy);
    $('#nueva_cantidad').val('0.00');
    $('#lote_ali_total').html('0');
    $('#ali_desc').html('');
    $('#form_ingreso_alimento')[0].reset();
    // Mostramos el modal
    $('#modalIngAli').modal('show');
});

function editar(lote_id) {
    $.post("../../controllers/loteController.php?op=mostrar", { lote_id: lote_id }, function (data) {
        data = JSON.parse(data);
        $('#lote_id').val(data.lote_id);
        $('#lote_descripcion').val(data.lote_descripcion);
        $('#lote_capacidad_max').val(data.lote_capacidad_max);
        console.log(data);
    })
    $('#lbTitulo').html('Editar Registro');
    $('#modalMantenimiento').modal('show');
}

function eliminar(lote_id) {
    console.log(lote_id);
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
            $.post("../../controllers/loteController.php?op=eliminar", { lote_id: lote_id, suc_id: $('#suc_idx').val() }, function (data) {
                console.log(data);
            })

            // Recarga los datos de la tabla
            $('#table_data').DataTable().ajax.reload();

            // Muestra notificación de la eliminación
            swal.fire({
                title: "Lote",
                text: "Eliminado Correctamente!",
                icon: "success"
            });
        }
    });
}

function calcularTotal() {
    var capMax = $('#lote_capacidad_maxIng').val();
    var canAct = $('#lote_cant_actual').val();
    var canIng = $('#mov_cant_ing').val();
    var mov_tipo = $('#mov_tipo').val();
    var mostrar = true;

    var canTotal = 0;

    if (mov_tipo == 'sum') {
        canTotal = (Number.parseInt(canAct) + Number.parseInt(canIng));
        if (canTotal > Number.parseInt(capMax)) {
            $('#mov_cant_ing').val('');
            $('#lote_can_total').val('');
            swal.fire({
                title: "Calculo incorrecto",
                text: "La cantidad ingresada sobrepasa a la Capacidad máxima!",
                icon: "error"
            });
            mostrar = false;
        }
    } else {
        canTotal = (Number.parseInt(canAct) - Number.parseInt(canIng));
        if (canTotal < 0) {
            $('#mov_cant_ing').val('');
            $('#lote_can_total').val('');
            canTotal = '';
            swal.fire({
                title: "Calculo incorrecto",
                text: "La cantidad ingresada no puede ser menor a 0!",
                icon: "error"
            });
            mostrar = false;
        }
    }

    if (mostrar) $('#lote_can_total').val(canTotal);

}

function calcularTotalAlimento() {
    var canAct = $('#lote_consumo_act').val();
    var canIng = $('#ali_cantidad').val();
    var mostrar = true;
    var canTotal = 0;

    if (canIng.length > 0) {
        canTotal = (Number.parseInt(canAct) + Number.parseInt(canIng));
        if (mostrar) $('#lote_ali_total').val(canTotal);
    }
}

function formatDate(dateObject = new Date()) {
    var year = dateObject.getFullYear();
    var month = dateObject.getMonth() + 1;
    var month = month > 9 ? month : "0" + month;
    var day = dateObject.getDate() > 9 ? dateObject.getDate() : "0" + dateObject.getDate();
    return year + "-" + month + "-" + day;
}
init();