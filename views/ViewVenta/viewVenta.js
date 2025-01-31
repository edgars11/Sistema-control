$(document).ready(function () {
    var ven_id = getUrlParameter('id');

    $.post("../../controllers/ventaController.php?op=listarVenta", { ven_id: ven_id }, function (data) {
        data = JSON.parse(data);
        console.log(data)
        $('#txtDireccion').html(data.emp_direccion);
        $('#txtRuc').html(data.emp_ruc);
        $('#txtEmail').html(data.emp_correo);
        $('#txtWebSite').html(data.emp_web);
        $('#txtTelefono').html(data.emp_telefono);

        $('#ven_id').html(data.ven_id);
        $('#ven_fecha_crea').html(data.ven_fecha_crea);
        $('#pago_nombre').html(data.pago_nom);
        $('#ven_total').html(data.ven_total);

        $('#ven_subtotal').html("$"+data.ven_subtotal);
        $('#ven_iva').html("$"+data.ven_iva);
        $('#ven_totalForm').html("$"+data.ven_total);
        
        $('#ven_comment').html(data.ven_coment);

        $('#cli_nombre').html(data.cli_nombre);
        $('#usu_nombre').html(data.usu_nom);
        $('#tipo_comp').html(data.tipo_comp);
        
    });
    
    $.post("../../controllers/ventaController.php?op=listaDetalleVenta", { ven_id: ven_id }, function (data) {
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