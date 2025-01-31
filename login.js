$(document).ready(function(){

    var com_id = getUrlParameter('c');

    $.post("controllers/empresaController.php?op=combo",{com_id:com_id}, function(data){
        $("#emp_id").html(data);
    });

    $('#emp_id').change(function(){
        $('#emp_id').each(function(){
            emp_id = $(this).val();
            $.post("controllers/sucursalController.php?op=combo",{emp_id:emp_id}, function(data){
                $("#suc_id").html(data);
            });
        });
    });

});

// TODO: Obtener parámetro de URL
var getUrlParameter = function getUrlParameter(sParam){
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
    sURLVariables = sPageURL.split('&'),
    sParameterName,
    i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');
        if(sParameterName[0]=== sParam){
            return sParameterName[1]===undefined? true: sParameterName[1];
        }
    }

}