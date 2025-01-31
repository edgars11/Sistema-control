$(document).ready(function () {
    var comp_id = getUrlParameter('id');

    $.post("../../controllers/compraController.php?op=listarCompra", { comp_id: comp_id }, function (data) {
        data = JSON.parse(data);
        console.log(data)
        $('#txtDireccion').html(data.emp_direccion);
        $('#txtRuc').html(data.emp_ruc);
        $('#txtEmail').html(data.emp_correo);
        $('#txtWebSite').html(data.emp_web);
        $('#txtTelefono').html(data.emp_telefono);

        $('#comp_id').html(data.comp_id);
        $('#comp_fecha_crea').html(data.comp_fecha_crea);
        $('#pago_nombre').html(data.pago_nom);
        $('#compr_total').html(data.comp_total);

        $('#comp_subtotal').html("$"+data.comp_subtotal);
        $('#comp_iva').html("$"+data.comp_iva);
        $('#comp_total').html("$"+data.comp_total);
        
        $('#comp_comment').html(data.comp_comment);

        $('#prov_nom').html(data.prov_nombre);
        $('#usu_nombre').html(data.usu_nom);
        $('#mon_nombre').html(data.mon_nombre);
        
    });
    
    $.post("../../controllers/compraController.php?op=listaDetalleTB", { comp_id: comp_id }, function (data) {
        $('#products-list').html(data);
    });

});

var getUrlParameter = function getUrlParameter(sParam) {
    var sPagueURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPagueURL.split('&'),
        sParameterName;

    for (let i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
}