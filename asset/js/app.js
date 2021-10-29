$(function () {

    $('body').on('blur', '.confirmarSenha:visible', function () {

        var form = $(this).closest('form');
        var senha = $(form).find('.senha').val();
        var confirmarSenha = $(this).val();

        if (senha != confirmarSenha) {
            $(this).parent().addClass('has-error has-danger');
            toastr.error('O campo confirmação senha está diferente do campo senha.');
        } else {
            $(this).parent().removeClass('has-error has-danger');
        }
    })

    $('#btn-alterar-senha').on('click', function () {

        var idForm = $(this).closest('form').attr('id');
        $('#' + idForm).validator('validate');

        $.each($('#' + idForm), function (i) {
            if ($('.has-error').length != 0) {
                toastr.error('Por favor, preencha corretamente os campos em vermelho.');
                return false;
            } 
        })

        var dados = $('#' + idForm).serializeObject();
        $.post('/Admin/Usuario/AlterarSenha', dados).done(function (result) {
            if (result.sucesso == false) {
                toastr.error(result.mensagem);
            } else {
                toastr.success("Senha alterada com sucesso");                
                setTimeout(function () { location.reload(); }, 3000);
            }
            

        });
    })

})