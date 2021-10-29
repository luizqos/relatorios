$(function () {
    // DATA
    var date = new Date();
    // ANO
    $('.year').text(date.getFullYear());

    // DATATABLE
    PT_BR = '/js/lib/plugins/datatables/jquery.datatables.pt-br.txt';

    $('.datatable').dataTable({
        "language": {
            "url": PT_BR
        }
    });


    // Spinner
    var opts = {
        lines: 11, length: 13, width: 9, radius: 39, scale: 1.25, corners: 0.4, color: '#000',
        opacity: 0.1, rotate: 19, direction: 1, speed: 0.9, trail: 64, fps: 20, zIndex: 2e9,
        className: 'spinner', top: '29%', left: '50%', shadow: false, hwaccel: false, position: 'absolute'
    };

    var target = document.getElementById('spinner');
    var spinner = new Spinner(opts).spin(target);

    // toastr
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-full-width",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    $.ajax({
        beforeSend: function () {
            $(target).show();
        },
        complete: function () {
            $(target).hide();
        },
        error: function (event, jqxhr, settings, thrownError) {
            console.log("Erro : " + thrownError);
        }
    });

    $(document).ajaxComplete(function (event, xhr, settings) {
        if (xhr.responseJSON != undefined && xhr.responseJSON.success != undefined && !xhr.responseJSON.success) {
            if (xhr.status == 401) {
                window.location = '/Admin/Login';
            }
             else {
                toastr.error("Erro : " + xhr.responseJSON.error);
            }
        }
    
    });

    $('body').on('click', '[data-toggle="collapse"]', function () {
        $('.collapse.in').collapse('hide');
        var collapsedId = $(this).attr('href');
        var isVisible = $(collapsedId + ' .panel-body').is(':visible');

        if (isVisible) {
            $(collapsedId).collapse('hide');
            return false;
        }
    })


});

// DATEPICKER
// https://eternicode.github.io/bootstrap-datepicker
var datepickerInit = function () {
    $('.input-group.date').datepicker({
        format: "dd/mm/yyyy",
        todayBtn: "linked",
        // clearBtn: true,
        language: "pt-BR",
        autoclose: true,
        todayHighlight: true
    });
}

$('body').on('click', '.input-group.date', function () {
    datepickerInit();
})


$(document).on('show.bs.modal', '.modal', function (event) {
    $('.input-group.date').on('show.bs.modal', function (event) {
        event.stopPropagation();
    });
    var zIndex = 1040 + (10 * $('.modal:visible').length);
    $(this).css('z-index', zIndex);
    setTimeout(function () {
        $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
    }, 0);
});

var select2 = function () {
    $("select[multiple='multiple']").select2({
        width: "100%",
        height: "10%"
    });

    $('.select2-selection').append('<i class="fa fa-angle-down"></i>');
}

$.fn.replaceTemplate = function ($template, data) {
    var source = $template.html();
    var template = Handlebars.compile(source);
    var html = template(data);

    this.html(html);
    this.trigger("render");
};

$.fn.setActiveMenu = function () {
    this.addClass("active");
};

$("#formAlteracaoSenha").submit(function (e) {
    e.preventDefault();

    var senhaAtual = $("#txt-senha-atual").val();
    var novaSenha = $("#txt-nova-senha").val();
    var confirmacaoSenha = $("#txt-confirma-senha").val();
    var $alert = $("#alteracao-senha-alert").hide();

    if (senhaAtual == "") {
        $alert.show().html("Informe a senha atual");
        return;
    }

    if (novaSenha == "") {
        $alert.show().html("Informe a nova senha");
        return;
    }

    if (confirmacaoSenha == "") {
        $alert.show().html("Informe a confirma��o de senha");
        return;
    }

    if (novaSenha != confirmacaoSenha) {
        $alert.show().html("As senhas n�o conferem");
        return;
    }

    var $btn = $("#btn-alterar-senha");
    $btn.button('loading');
    $.post($(this).prop("action"), { SenhaAtual: senhaAtual, NovaSenha: novaSenha, ConfirmacaoSenha: confirmacaoSenha }).success(function (result) {
        if (result.sucesso) {
            $("#txt-senha-atual, #txt-nova-senha, #txt-confirma-senha").val("");
            $("#modalAlteracaoSenha").modal("hide");
            toastr.success("Senha alterada com sucesso.");

            return;
        }

        toastr.error(result.mensagem);
        $btn.button("reset");
    });
});

$.fn.disableTab = function () {
    this.removeAttr("data-toggle").parent().addClass("disabled");
};

$.fn.enableTab = function (toogle) {
    this.attr("data-toggle", "tab").parent().removeClass("disabled");
    if (toogle == "dropdown") {
        this.attr("data-toggle", "dropdown").parent().removeClass("disabled");
    }
};

$.fn.serializeObject = function () {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function () {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

//Validar formul�rio: return true or false
isFormValid = function(form) {
	var result = true;
	$(form).validator('validate');
	$(form).find('.form-group').each(function () {
		if ($(this).hasClass('has-error')) {
			result = false;
			return false;
		}
	});
	return result;
}