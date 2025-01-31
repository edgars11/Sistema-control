const usu_idx = $('#usu_idx').val();

$(document).on('click', '#btnupdpass', function () {
    var pass = $('#txtpass').val();
    var confirm_pass = $('#txtpassconfirm').val();

    if (pass.length == 0 || confirm_pass.length == 0) {
        swal.fire({
            title: 'Advertencia',
            text: 'Los campos están vacios!',
            icon: 'warning'
        });
    } else {

        if (pass == confirm_pass) {
            $.post("../../controllers/usuarioController.php?op=password", { usu_id: usu_idx, usu_password: confirm_pass }, function (data) {
                console.log(data);
            });

            $('#txtpass').val('');
            $('#txtpassconfirm').val('');

            swal.fire({
                title: 'Success',
                text: 'La contraseña se actualizó correctamente!',
                icon: 'success'
            });


        } else {
            swal.fire({
                title: 'Error',
                text: 'Las contraseñas no coinciden!',
                icon: 'error'
            });
        }
    }
});