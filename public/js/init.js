$.ajaxSetup({
    beforeSend: function () {
        ShowLoading();
    },
    complete: function () {
        HideLoading();
    }
});



$.extend($.validator.messages, {
    required: "Este campo es obligatorio.",
    remote: "Please fix this field.",
    email: "Formato de correo electrónico no valido",
    url: "Formato de url no valido",
    date: "Formato de fecha no valido",
    dateISO: "Please enter a valid date (ISO).",
    number: "Formato de número no valido",
    digits: "Please enter only digits.",
    creditcard: "Please enter a valid credit card number.",
    equalTo: "Please enter the same value again.",
    accept: "Please enter a value with a valid extension.",
    maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
    minlength: jQuery.validator.format("Please enter at least {0} characters."),
    rangelength: jQuery.validator.format("Please enter a value between {0} and {1} characters long."),
    range: jQuery.validator.format("Please enter a value between {0} and {1}."),
    max: jQuery.validator.format("Please enter a value less than or equal to {0}."),
    min: jQuery.validator.format("Please enter a value greater than or equal to {0}.")
});



$(function () {

    $('[data-fancybox="gallery"]').fancybox();

    $("#frmIndex").submit(function(event){
        event.preventDefault();
        SubmitFilterIndexs($(this).find('div').eq(0));
    })

    //Mostrar limpiar filtro cuando este lleno
    $(".clean-filter").each(function (index, item) {
        if ($(item).prev().val().length > 0) {
            $(this).css({ display: "table-cell" });
        }
    })

    //$('.selectpicker').selectpicker({ liveSearchNormalize: true });

    var myElem = document.getElementById('pagedList');
    if (myElem != null) {
        var str = document.getElementById("pagedList").innerHTML;
        var res = str.replace("Showing items", "Elementos del");
        res = res.replace("through", "al");
        res = res.replace("of", "de");
        document.getElementById("pagedList").innerHTML = res;
    }


    //$("#pagedList .pagination-container .pagination").addClass("pagination-sm");


    
    CreateAutocompletesBootstrap();//CreateAutocompletes();


    var getPage = function (event) {
        event.preventDefault();
        var $a = $(this)

        var options = {
            url: $a.attr("href"),
            data: $("form").serialize(),
            type: "get"
        };

        $.ajax(options).done(function (data) {
            var target = $a.parents("div.pagedList").attr("data-otf-target");
            $(target).replaceWith(data);
        });
        return false;
    };


    //$("body").on("click", ".pagedList a", getPage);
    //$(".pagedList a").click(getPage);
    $(document).on('click', '.pagedList a', getPage);



    SetPlaceholderInputs();

    $("#btnCleanFilter").click(function (event)
    {
        var vElement = this;
        event.preventDefault();
        $("#frmIndex input:not([type='checkbox'], [type='radio'])").val('');
        $("#frmIndex input[type='checkbox']").prop("checked", false);
        $("#frmIndex select").prop("selectedIndex", 0);
        $("#frmIndex #sOrder").val("");
        $('#frmIndex select.select2').select2("val", 0);
        ShowLoading();
        $("#frmIndex .clean-filter").css({ display: "none" });
        $("#frmIndex #iFiltros").val(1);

        SubmitFilterIndexs(vElement);

    })

    $(".change-checked").click(function()
    {
        var vElementId = $(this).attr("element-id");
        if($(this).hasClass("fa-square-o"))
        {
            $(this).removeClass("fa-square-o").addClass("fa-check-square-o");
            $("#" + vElementId).val(true);
        }
        else
        {
            $(this).removeClass("fa-check-square-o").addClass("fa-square-o");
            $("#" + vElementId).val(false);
        }
    })


    $(".filtrar").each(function (x, y) {
        var vParentElement = $(this).parent();
        if ($(this).val() == "") {
            vParentElement.addClass("noFiltering");
        }
    });

    FiltrarEvents();

    $(".clean-filter").click(function () {
        var $vElement = $(this).parent().find(".filtrar");
        $vElement.val("").trigger("change");
        if ($vElement.hasClass("select2")) {
            $vElement.select2("val", 0);
        }
    });

    MaskInputs();

    $("#pagedList a[href]").click(function()
    {
        ShowLoading();
    })

    $(".select2").select2({
        width: "100%",
        language: {
            noResults: function () {
                return "No se encontraron resultados.";
            }
        }
    });

});

var MaskInputs = function(){
    $('.phone-mask').mask('000-000-0000');
    $(".just-number").mask("#");
    $('.just-decimal').mask("##0.00", {reverse: true});

    $('.datepicker').datepicker({
        format: "dd/mm/yyyy",
        todayBtn: "linked",
        language: "es",
        autoclose: true,
        todayHighlight: true,
        clearBtn: true
    });
}

function ShowImage(imageId) {
    var vPreviewImgId = $(imageId).attr("preview-img-id");

    if (imageId.files && imageId.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#' + vPreviewImgId).attr('src', e.target.result);
        }

        reader.readAsDataURL(imageId.files[0]);
    }else{
        $('#' + vPreviewImgId).attr('src', '');
    }

}

$(document).on('change', '.validate-image', function() {
    if (this.files && this.files[0]) {
        var file = this.files[0];
        var isImage = file && file['type'].split('/')[0] === 'image';

        if(!isImage)
        {
            $(this).val("");
        }

    }

})

$(document).on('change', '.preview-image', function(){
    ShowImage(this);
})

var FiltrarEvents = function(){
    $(".filtrar")
        .click(function () {
            if(this.tagName.toLowerCase() == "button")
            {
                var vElement = $(this);
                ShowLoading();
                $(vElement).blur();
                setTimeout(function () {
                    //$(vElement).parents("form").submit();
                    SubmitFilterIndexs(vElement);
                }, 200);
            }
        })
        .change(function () {
            var vElement = $(this);
            if ($(vElement).val().length > 0) {
                $(vElement).siblings(".clean-filter").css({ display: "table-cell" }).parent().removeClass('noFiltering');       
            }
            else {
                $(vElement).siblings(".clean-filter").css({ display: "none" }).parent().addClass('noFiltering');
             
            }
            ShowLoading();
            $(vElement).blur();
            setTimeout(function () {
                //$(vElement).parents("form").submit();
                SubmitFilterIndexs(vElement);
            }, 200);
        })
        .keypress(function () {
            var vElement = $(this);
            if (event.charCode == 13) {
                //ShowLoading();
                ShowLoading();
                //$(vElement).parents("form").submit();
                SubmitFilterIndexs(vElement);
            }
        })
        .keyup(function () {
            if ($(this).val().length > 0) {
                $(this).siblings(".clean-filter").css({ display: "table-cell" }).parent().removeClass('noFiltering');
            }
            else {
                $(this).siblings(".clean-filter").css({ display: "none" }).parent().addClass('noFiltering');
            }
        });
}

function CreateAutocompletesBootstrap()
{
    $('[data-autocomple]').each(function ()
    {
        //var vUrl = $(this).attr("data-autocomple") + "?term=" + $(this).val();
        //$(this).typeahead({
        //    ajax: {
        //        url: vUrl
        //    }
        //});

       
        var myAccentMap = { "á": "a", "é": "e", "í": "i", "ó": "o", "ú": "u" };
        var vUrl = $(this).attr("data-autocomple");// + "?term=" + $(this).val();
        var vElement = $(this);
        $.get(vUrl, function (data) {
            $(vElement).typeahead({
                source: data,
                autoSelect: false,
                limit: 10
                //updater: function(data)
                //{
                //    return data;
                //}
            });
        }, 'json');
    })
}

function CreateAutocompletes()
{
    var createAutocomplete = function () {
        var $input = $(this);
        var options = {
            source: $input.attr("data-autocomple")
        };
        $input.autocomplete(options);
    };

    $("input[data-autocomple]").each(createAutocomplete)
}

function OrderBy(vElement)
{
    ShowLoading();
    var vOrder = $(vElement).attr("order");
    $(vElement).find("i").toggleClass("");
    $("#sOrder").val(vOrder);
    //$(vElement).parents("form").submit();
    SubmitFilterIndexs(vElement);
}


$(document).ajaxSuccess(function()
{
    //$("#pagedList .pagination-container .pagination").addClass("pagination-sm");
    //CreateAutocompletesBootstrap();//CreateAutocompletes();

    //$(".filtrar").change(function () {
    //    ShowLoading();
    //    $("#form0").submit();
    //})

    //$("#btnCleanFilter").click(function (event) {
    //    event.preventDefault();
    //    $("input").val("");
    //    $("select").val("");
    //    $("#sOrder").val("");
    //    ShowLoading();
    //    $("#form0").submit();
    //})

    $("#pagedList a[href]").click(function () {
        ShowLoading();
    })

    MaskInputs();
    FiltrarEvents();
    HideLoading();

    SetPlaceholderInputs();
})

function SetPlaceholderInputs()
{
    $("input, textarea").each(function(){
        var placeholder = $(this).parents(".form-group").find("label").text();
        $(this).attr("placeholder", placeholder);
    })

    setTimeout(function(){
        $('[data-placeholder]').each(function(){
            var vPlaceholder = $(this).data('placeholder');
            if(vPlaceholder != undefined)
                $(this).attr('placeholder', vPlaceholder);
        })
    },1)
}


function MyToast(vTitulo, vMensaje, vTipo, vTiempo)
{
    vTiempo = vTiempo == undefined ? "5000" : vTiempo;
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "progressBar": true,
        "preventDuplicates": true,
        "positionClass": "toast-top-center",//toast-top-right
        "onclick": null,
        "showDuration": "400",
        "hideDuration": "1000",
        "timeOut": vTiempo,
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "slideDown",
        "hideMethod": "slideUp"
    }

    toastr[vTipo](vMensaje, vTitulo);
}

var ShowLoading = function (text = "Cargando...") {

    if(!$('.loader').hasClass('showing_loader'))
    {

        $(".loader").find("small").html(text);
        $(".loader").removeClass('d-none');
        $(".loader-backdrop").removeClass('d-none');
        $(".loader").addClass('animated fadeIn');
        $(".loader").addClass('animated bounceInUp');
        $(".loader").addClass('showing_loader');
    }
};

var HideLoading = function () {
    setTimeout(function () {
        $(".loader").addClass('animated bounceOutDown');
        $(".loader-backdrop").addClass('animated faceOut');
        $(".loader").removeClass('showing_loader');
        setTimeout(function () {
            $(".loader").addClass('d-none');
            $(".loader-backdrop").addClass('d-none');
        }, 200);
    }, 400)
};

var SubmitFilterIndexs = function(vElement){
    var form = $(vElement).parents("form");

    var options = {
        url: form.attr("action"),
        data: form.serialize(),
        type: "get"
    };

    $.ajax(options).done(function (data) {
        $("#IndexList").replaceWith(data);
    });
}


$(document).on('click', '.check-box-value', function(){
    var vName = $(this).attr('id');
    var vChecked = $(this).prop('checked') ? 1 : 0;

    $('[name="' + vName + '"]').val(vChecked);
})

//#region bitacora

$(document).on('click', '#iActualizarBitacora', function () {
    
    $("#FiltrarBitacora").prop("selectedIndex", 0);
    ShowLoading();
    $("#frmBitacora").submit();
});


$(document).on('change', '#FiltrarBitacora', function () {
    ShowLoading();
    $("#frmBitacora").submit();
});



//#endregion



