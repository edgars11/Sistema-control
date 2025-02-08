var suc_idx = $('#suc_idx').val();

var fromDate;
var toDate;

const data = {
    lote_id: null,
    fecha_desde: null,
    fecha_hasta: null,
    ali_tipo: 'TH',
    suc_id: suc_idx
}

function init() {
    $('#form_fecha').on("submit", function (e) {
        consultaPorFecha(e);
    });
    $('#form_ingreso_alimento').on("submit", function (e) {
        guardarYEditar(e);
    });
}

$(document).ready(function () {

    // Se obtienen los lotes disponibles
    $.post("../../controllers/loteController.php?op=combo", { suc_id: suc_idx }, function (data) {
        $('#lote_idIng').html(data);
    });

    var todayDate = getDate(new Date());
    var toDate = getDate(new Date());

    var fecha = new Date();
    var hoy = formatDate(fecha)
    document.getElementById("fecha_desde").value = hoy;
    document.getElementById("fecha_hasta").value = hoy;

    data.fecha_desde = todayDate;
    data.fecha_hasta = todayDate;

    cargarTabla(data);

});

function guardarYEditar(e) {

    e.preventDefault();

    var formData = new FormData($('#form_ingreso_alimento')[0]);
    formData.append('suc_id', $('#suc_idx').val());
    console.log(formData.get('lote_id'));
    console.log(formData.get('ali_fecha'));
    console.log(formData.get('ali_cantidad'));
    console.log(formData.get('ali_desc'));
    console.log(formData.get('ali_id'));

    $.ajax({
        url: "../../controllers/loteController.php?op=updConsumo",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (data) {
            data = JSON.parse(data);
            if(data.success){
                $('#table_data').DataTable().ajax.reload();
                $('#modalMantenimiento').modal('hide');
                swal.fire({
                    title: "Consumo Lote",
                    text: "Actualización exitosa!",
                    icon: "success"
                });
            } else {
                swal.fire({
                    title: "Consumo Lote",
                    text: "Error al actualizar registro!",
                    icon: "error"
                });
            }
        }
    });
}

$(document).on("click", "#btnFiltro", function () {
    fromDate = $('#fecha_desde').val();
    toDate = $('#fecha_hasta').val();
    lote_id = $('#lote_idIng').val();



    if (lote_id !== 'Seleccionar') {
        data.lote_id = lote_id;
    } else {
        data.lote_id = null;
    }

    data.fecha_desde = fromDate;
    data.fecha_hasta = toDate;
    data.ali_tipo = 'TF';

    cargarTabla(data);
});

function editar(ali_id) {

    // Se obtienen los lotes disponibles
    $.post("../../controllers/loteController.php?op=combo", { suc_id: suc_idx }, function (data) {
        $('#lote_id').html(data);
    });

    $.post("../../controllers/loteController.php?op=mostrarConsumoId", { ali_id: ali_id }, function (data) {
        data = JSON.parse(data);
        $('#lote_id').val(data.lote_id);
        $('#ali_cantidad_ing').val(data.ali_cantidad);
        $('#ali_fecha').val(data.ali_fecha);
        $('#ali_desc').val(data.ali_desc);
        $('#cant_consumo_act').val(data.total_consumo_lote);
        $('#ali_total_cant').val(data.total_consumo_lote);
        $('#ali_id').val(data.ali_id);
        $('#ali_cantidad').val('');
    })
    $('#lbTitulo').html('Editar Registro');
    $('#modalMantenimiento').modal('show');
}

function eliminar(ali_id) {
    console.log(ali_id);
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
            $.post("../../controllers/loteController.php?op=eliminarConsumo", { ali_id: ali_id }, function (data) {
                console.log(data);
            })

            // Recarga los datos de la tabla
            $('#table_data').DataTable().ajax.reload();

            // Muestra notificación de la eliminación
            swal.fire({
                title: "Consumo Lote",
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

function cargarTabla(data) {
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
            url: "../../controllers/loteController.php?op=listarAlimento",
            type: "post",
            data: data
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

function calcularTotalAlimento() {
    var canAct = $('#cant_consumo_act').val();
    var canIng = $('#ali_cantidad_ing').val();
    var cant = $('#ali_cantidad').val();

    var canTotal = 0;

    if (canIng.length > 0) {
        canTotal = (Number.parseInt(canAct) - Number.parseInt(canIng)) + Number.parseInt(cant);
        $('#ali_total_cant').val(canTotal);
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