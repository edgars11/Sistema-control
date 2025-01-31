var emp_idx = $('#emp_idx').val();
var usu_idx = $('#usu_idx').val();
var suc_idx = $('#suc_idx').val();

// Variable local para obtener totales
var totalCompra = 0;

$(document).ready(function () {

    // Se crea el registro de la compra
    $.post("../../controllers/compraController.php?op=registrar", { suc_id: suc_idx, usu_id: usu_idx }, function (data) {
        data = JSON.parse(data);
        $('#comp_id').val(data.comp_id);
        //console.log(data);
    });

    $('#prov_id').select2();
    $('#cat_id').select2();
    $('#prod_id').select2();
    $('#pago_id').select2();
    $('#mon_id').select2();

    // Carga el catálogo de proveedores
    $.post("../../controllers/proveedorController.php?op=combo", { emp_id: emp_idx }, function (data) {
        $('#prov_id').html(data);
    });

    // Se obtienen las categorias
    $.post("../../controllers/categoriaController.php?op=combo", { suc_id: suc_idx }, function (data) {
        $('#cat_id').html(data);
    });

    // Se obtienen las Formas de pago
    $.post("../../controllers/pagoController.php?op=combo", function (data) {
        $('#pago_id').html(data);
    });

    // Se obtienen las Monedas
    $.post("../../controllers/monedaController.php?op=combo", { suc_id: suc_idx }, function (data) {
        $('#mon_id').html(data);
    });

    // Obtiene datos del proveedor seleccionado
    $("#prov_id").change(function () {
        $("#prov_id").each(function () {
            prov_id_i = $(this).val();

            $.post("../../controllers/proveedorController.php?op=mostrar", { prov_id: prov_id_i }, function (data) {
                data = JSON.parse(data);
                $('#prov_ruc').val(data.prov_ruc);
                $('#prov_correo').val(data.prov_correo);
                $('#prov_telefono').val(data.prov_telefono);
                $('#prov_direccion').val(data.prov_direccion);
            });
        });
    });

    // Obtiene datos de la categoria seleccionada
    $("#cat_id").change(function () {
        $("#cat_id").each(function () {
            cat_id_i = $(this).val();

            $.post("../../controllers/productoController.php?op=cmbcate", { suc_id: suc_idx, cat_id: cat_id_i }, function (data) {
                $('#prod_id').html(data);
            });
        });
    });

    // Obtiene datos del producto seleccionado
    $("#prod_id").change(function () {
        $("#prod_id").each(function () {
            prod_id_i = $(this).val();

            $.post("../../controllers/productoController.php?op=mostrar", { suc_id: suc_idx, prod_id: prod_id_i }, function (data) {
                data = JSON.parse(data);
                $('#prod_pcompra').val(data.prod_pcompra);
                $('#unm_nombre').val(data.unm_nombre);
                $('#prod_stock').val(data.prod_stock);
            });
        });
    });

    $(document).on("click", "#btnAddProd", function () {
        var comp_id = $('#comp_id').val();
        var prod_id = $('#prod_id').val();
        var prod_pcompra = $('#prod_pcompra').val();
        var detc_cant = $('#detc_cant').val();

        if (prod_id.length == 0 || prod_pcompra.length == 0 || detc_cant.length == 0) {
            // Muestra notificación de la eliminación
            swal.fire({
                title: "Compra",
                text: "Error! Campos incompletos",
                icon: "error"
            });
        } else {
            // Se obtienen las Formas de pago
            $.post("../../controllers/compraController.php?op=addDetalle", { comp_id: comp_id, prod_id: prod_id, prod_pcompra: prod_pcompra, detc_cant: detc_cant }, function (data) {
                data = JSON.parse(data);
                console.log(data);
                $('#txtsubtotal').html('$' + data.subtotal);
                $('#txtiva').html('$' + (data.iva < 1 ? "0" + data.iva : data.iva));
                $('#txttotal').html('$' + data.total);
                totalCompra = data.total;
            });
            cargarDetalle(comp_id);
        }

    });
});

$(document).on("click", "#btnGuardar", function () {
    var pago_id = $('#pago_id').val();
    var prov_id = $('#prov_id').val();
    var comp_coment = $('#comp_coment').val();
    var mon_id = $('#mon_id').val();
    var comp_id = $('#comp_id').val();

    /* TODO: Validación de campos de compras */
    if (prov_id.length == 0 || mon_id.length == 0 || pago_id.length == 0 || mon_id == 'Seleccionar' || pago_id == 'Seleccionar') {
        // Muestra notificación de la eliminación
        swal.fire({
            title: "Compra",
            text: "Error! Campos incompletos",
            icon: "error"
        });
    } else {
        /* TODO: Valida que haya registro de productos en la compra */
        if (totalCompra <= 0) {
            swal.fire({
                title: "Compra",
                text: "Agrege productos a la compra para continuar!",
                icon: "warning"
            });
        } else {
            // Se obtienen las Formas de pago
            $.post("../../controllers/compraController.php?op=updateCompra",
                {
                    pago_id: pago_id,
                    prov_id: prov_id,
                    comp_comment: comp_coment,
                    mon_id: mon_id,
                    comp_id: comp_id
                },
                function (data) {

                    swal.fire({
                        title: "Compra",
                        text: "Compra # C-"+ comp_id+" guardada exitosamente!",
                        icon: "success",
                        footer: '<a href="../ViewCompra/?id='+comp_id+'" target="_blank">¿Desea imprimir el comprobante?</a>'
                    });
                });
        }

        // cargarDetalle(comp_id);
    }

});

function cargarDetalle(comp_id) {
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
            url: "../../controllers/compraController.php?op=listarDetalle",
            type: "post",
            data: { comp_id: comp_id }
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

function deleteItem(detc_id, comp_id) {
    console.log(cat_id);
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
            $.post("../../controllers/compraController.php?op=deleteItem", { detc_id: detc_id }, function (data) {
                data = JSON.parse(data);
                console.log(data);
                var iva = "";
                if(data.iva == null ){
                    iva = "0.00";
                }else{
                    iva = data.iva < 1 ? "0" + data.iva : data.iva;
                }
                $('#txtsubtotal').html('$' + (data.subtotal === null ? "0.00" : data.subtotal));
                $('#txtiva').html('$' + iva);
                $('#txttotal').html('$' + (data.total === null ? "0.00" : data.total));
            })

            // Recarga los datos de la tabla
            cargarDetalle(comp_id);

            // Muestra notificación de la eliminación
            swal.fire({
                title: "Detalle Compra",
                text: "Eliminado Correctamente!",
                icon: "success"
            });
        }
    });
}
