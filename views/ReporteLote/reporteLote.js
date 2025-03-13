var suc_idx = $('#suc_idx').val();

// Variables para consulta reporte
var lote_id,
    rep_anio,
    rep_periodo;

function init() {
    $('#mantenimiento_form').on("submit", function (e) {
        guardarYEditar(e);
    })
}

function guardarYEditar(e) {

    e.preventDefault();

    var formData = new FormData($('#mantenimiento_form')[0]);
    formData.append('suc_id', $('#suc_idx').val());
    console.log($('#suc_idx').val());
    console.log(formData.get('cat_id'));
    console.log(formData.get('cat_nombre'));

    $.ajax({
        url: "../../controllers/categoriaController.php?op=guardar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (data) {
            console.log("guardado");
            $('#table_data').DataTable().ajax.reload();
            $('#modalMantenimiento').modal('hide');
            swal.fire({
                title: "Categoria",
                text: "Ejecución exitosa!",
                icon: "success"
            });
        }
    });
}

$(document).ready(function () {

    var fecha = new Date();
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
            url: "../../controllers/categoriaController.php?op=listar",
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

    $("#repor_year").val(fecha.getFullYear());

    // Se obtienen los lotes disponibles
    $.post("../../controllers/loteController.php?op=combo", { suc_id: suc_idx }, function (data) {
        $('#lote_idIng').html(data);
    });

    $('#lote_idIng').attr('Seleccionar', 'nuevoValor');

});

function editar(cat_id) {
    $.post("../../controllers/categoriaController.php?op=mostrar", { cat_id: cat_id }, function (data) {
        data = JSON.parse(data);
        $('#cat_id').val(data.cat_id);
        $('#cat_nombre').val(data.cat_nombre);
        console.log(data);
    })
    $('#lbTitulo').html('Editar Registro');
    $('#modalMantenimiento').modal('show');
}

function seleccionar(lote_id, row) {
    console.log(lote_id);

    var rep_fecha = $('#BtnPeriodo' + row).attr('fecha');
    $('#rep_fecha').val(rep_fecha);
    $('#lote_idIng').val(lote_id);
    rep_periodo = $('#rep_periodo').val();

    if (rep_periodo == 'U') {
        $('#rep_fecha_fin').val(formatDate());
    } else {
        $.post("../../controllers/repLotePeriodoController.php?op=periodosLote", { lote_id, rep_fecha }, function (data) {
            data = JSON.parse(data);
            $('#rep_fecha_fin').val(data.fecha_fin);
        })
    }

    // Ocultar el modal
    $('#modalListadoPeriodos').modal('hide');
}

$(document).on("click", "#list_periodos", function () {
    // Limpiamos los campos del modal

    lote_id = $('#lote_idIng').val();
    rep_anio = $('#repor_year').val();
    rep_periodo = $('#rep_periodo').val();


    $('#tb_listadoPeriodos').DataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
        ],
        "ajax": {
            url: "../../controllers/repLotePeriodoController.php?op=listadoPeriodo",
            type: "post",
            data: {
                suc_id: suc_idx,
                lote_id,
                rep_anio,
                rep_periodo
            }
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

    // Mostramos el modal
    $('#modalListadoPeriodos').modal('show');

});

$(document).on("click", "#btnFiltro", function () {
    // Limpiamos los campos del modal

    lote_id = $('#lote_idIng').val();
    fecha_periodo = $('#rep_fecha').val();
    fecha_fin = $('#rep_fecha_fin').val();

    $('#tb_listadoReporte').DataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
        ],
        "ajax": {
            url: "../../controllers/repLotePeriodoController.php?op=reporteListado",
            type: "post",
            data: {
                suc_id: suc_idx,
                lote_id,
                fecha_periodo,
                fecha_fin
            }
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

    $.post("../../controllers/repLotePeriodoController.php?op=obtenerTotales", { lote_id, suc_id: suc_idx }, function (data) {
        data = JSON.parse(data);
        console.log(data)
        $('#total_ventas').html(data.totalMonto);
        $('#cantidad_ventas').html(data.cantidad);
        $('#peso_neto').html(data.peso_neto);
        $('#consumo_lote').html(data.consumo);
        $('#perdida_lote').html(data.perdida);
    })

});

function formatDate(dateObject = new Date()) {
    var year = dateObject.getFullYear();
    var month = dateObject.getMonth() + 1;
    var month = month > 9 ? month : "0" + month;
    var day = dateObject.getDate() > 9 ? dateObject.getDate() : "0" + dateObject.getDate();
    return year + "-" + month + "-" + day;
}

init();