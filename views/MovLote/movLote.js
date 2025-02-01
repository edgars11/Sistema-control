var suc_idx = $('#suc_idx').val();

function consultaPorFecha(e) {
    e.preventDefault();
    var formData = new FormData($('#form_fecha')[0]);
    formData.append('suc_id', suc_idx);

    var todayDate = formData.get('mov_fecha_desde');
    var toDate = formData.get('mov_fecha_hasta');

    cargarTabla(todayDate, toDate, suc_idx);

}

function init() {
    $('#form_fecha').on("submit", function (e) {
        consultaPorFecha(e);
    });
}

$(document).ready(function () {

    var todayDate = getDate(new Date());
    var toDate = getDate(new Date());

    var fecha = new Date();
    var hoy = formatDate(fecha)
    document.getElementById("mov_fecha_desde").value = hoy;
    document.getElementById("mov_fecha_hasta").value = hoy;

    cargarTabla(todayDate, toDate, suc_idx);


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

function getDate(date) {
    var dateString = new Date(date.getTime() - (date.getTimezoneOffset() * 60000))
        .toISOString()
        .split("T")[0];

    return dateString;
}

function cargarTabla(todayDate, toDate, suc_idx) {
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
            url: "../../controllers/movimientoLoteController.php?op=listar",
            type: "post",
            data: { mov_fecha: todayDate, mov_hasta: toDate, suc_id: suc_idx }
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

function formatDate(dateObject = new Date()) {
    var year = dateObject.getFullYear();
    var month = dateObject.getMonth() + 1;
    var month = month > 9 ? month : "0" + month;
    var day = dateObject.getDate() > 9 ? dateObject.getDate() : "0" + dateObject.getDate();
    return year + "-" + month + "-" + day;
}
init();