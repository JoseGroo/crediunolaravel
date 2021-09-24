@extends('layouts.layout')

@php
    $show_conyuge = $model->estado_civil == 2 || $model->estado_civil == 4;
@endphp

@section('content')

    <div class="wrapper wrapper-content">
        <div class="row animated fadeInRight">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-10">
                                <h5 class="m-0">

                                    Datos del aval
                                </h5>
                            </div>
                            <div class="col-md-2 text-right">

                                <button id="btnEdit" data-toggle="modal" data-target="#modalEdit" type="button" class="btn btn-circle btn-icon btn-info">
                                    <i class="mdi mdi-file-edit-outline"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="divDatosGenerales">
                            @include('avales.views_details._datos_generales', [ 'model' => $model])
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 m-t-lg">
                <ul class="nav nav-tabs" id="tabInformacionAval" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab-documentos" role="tab" aria-controls="tab-documentos" aria-selected="true">Documentos</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab-informacion-contacto" data-replace-div="divFormInformacionContacto" role="tab" aria-controls="tab-telefonos" aria-selected="true">Información de contacto</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab-informacion-laboral" data-replace-div="divFormInformacionLaboral" role="tab" aria-controls="tab-informacion-laboral" aria-selected="true">Información laboral</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab-economia" data-replace-div="divFormEconomia" role="tab" aria-controls="tab-economia" aria-selected="true">Economia</a>
                    </li>
                    @if($show_conyuge)
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab-conyuge" data-replace-div="divFormConyuge" role="tab" aria-controls="tab-conyuge" aria-selected="true">Conyuge</a>
                        </li>
                    @endif
                </ul>

                <div class="tab-content">
                    <div id="tab-documentos" class="tab-pane"></div>


                    <div id="tab-informacion-contacto" class="tab-pane">
                        {{ Form::open([ 'route' => ['avales.manage_informacion_contacto' ], 'method' => 'POST', 'id' => 'frmInformacionContacto' ]) }}
                        {{ Form::hidden('aval_id', $model->aval_id) }}
                        <div id="divFormInformacionContacto">

                        </div>
                        {{ Form::close() }}
                    </div>

                    <div id="tab-informacion-laboral" class="tab-pane">
                        {{ Form::open([ 'route' => ['avales.manage_informacion_laboral' ], 'method' => 'POST', 'id' => 'frmInformacionLaboral' ]) }}
                        {{ Form::hidden('aval_id', $model->aval_id) }}
                        <div id="divFormInformacionLaboral">

                        </div>
                        {{ Form::close() }}
                    </div>

                    <div id="tab-economia" class="tab-pane">
                        {{ Form::open([ 'route' => ['avales.manage_economia' ], 'method' => 'POST', 'id' => 'frmEconomia' ]) }}
                        {{ Form::hidden('aval_id', $model->aval_id) }}
                        <div id="divFormEconomia">

                        </div>
                        {{ Form::close() }}
                    </div>

                    @if($show_conyuge)
                        <div id="tab-conyuge" class="tab-pane">
                            {{ Form::open([ 'route' => ['avales.manage_conyuge' ], 'method' => 'POST', 'id' => 'frmConyuge' ]) }}
                            {{ Form::hidden('aval_id', $model->aval_id) }}
                            <div id="divFormConyuge">

                            </div>
                            {{ Form::close() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <div class="modal inmodal" id="modalManageDocumentos" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content animated bounceInUp">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Administración de documentos</h4>
                </div>
                {{ Form::open([ 'route' => ['avales.manage_documentos' ], 'method' => 'POST', 'id' => 'frmManageDocumentos' ]) }}
                <div class="modal-body">

                    {{ Form::hidden('aval_id', $model->aval_id) }}
                    <div class="row" id="inputsDocumentos">

                        @include('avales.views_details.documentos._form', [ 'model' => null ])

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>


    <div class="modal inmodal" id="modalEdit" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-width: 1000px;">
            <div class="modal-content animated bounceInUp">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Editar información general</h4>
                </div>
                {{ Form::open([ 'route' => ['avales.edit_datos_generales' ], 'method' => 'POST', 'id' => 'frmEditDatosGenerales', 'enctype' => 'multipart/form-data' ]) }}
                <div class="modal-body">
                    @include('avales.views_details._edit_datos_generales', [ 'model' => $model ])
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>

@endsection



@section("scripts")
    @parent

    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    <script src="{{ asset('js/jquery/jquery.form.min.js')}}"></script>

    {!! JsValidator::formRequest('App\Http\Requests\DocumentosAvalRequest', '#frmManageDocumentos') !!}
    {!! JsValidator::formRequest('App\Http\Requests\AvalesRequest', '#frmEditDatosGenerales') !!}


    <script>

        //#region Documentos
        var options_document = {
            beforeSubmit: function (arr, $form, options) {
                var vSubmit = $("#frmManageDocumentos").valid();

                if (vSubmit) {
                    ShowLoading('Actualizando información...');
                    $('#frmManageDocumentos button[type=submit], #frmManageDocumentos input[type=submit]').prop('disabled', true);
                }

                return vSubmit;
            },
            success: function (data) {
                console.log(data);
                if(data.Saved)
                {
                    $('#tab-documentos').html(data.Html);
                    $('#modalManageDocumentos').modal('hide');
                }

                var vMessageClass = data.Saved ? 'success' : 'error';
                MyToast('Notificación', data.Message, vMessageClass);

                $('#frmManageDocumentos button[type=submit], #frmManageDocumentos input[type=submit]').prop('disabled', false);
            }, error: function (data)
            {
                console.log(data);
                MyToast("Notificación", "Ocurrio un error", "error");
                $('#frmManageDocumentos button[type=submit], #frmManageDocumentos input[type=submit]').prop('disabled', false);
            }
        };

        $('#frmManageDocumentos').ajaxForm(options_document);

        $(document).on('click', '.edit-documento', function(event){
            event.preventDefault();

            var vDocumentoId = $(this).data('documento-id');

            $.ajax({
                url: "{{route('avales.get_form_documento')}}",
                dataType: "html",
                type: "GET",
                data: {
                    documento_aval_id: vDocumentoId
                },
                cache: false,
                success: function (data) {

                    $('#inputsDocumentos').html(data);
                    $("#modalManageDocumentos").modal('show');
                },
                error: function (error) {
                    console.log("error");
                    console.log(error.responseText);
                }
            });
        })


        $(document).on('click', '#agregarNuevoDocumento', function(event){
            event.preventDefault();

            $.ajax({
                url: "{{route('avales.get_form_documento')}}",
                dataType: "html",
                type: "GET",
                data: {
                },
                cache: false,
                success: function (data) {

                    $('#inputsDocumentos').html(data);
                    $("#modalManageDocumentos").modal('show');
                },
                error: function (error) {
                    console.log("error");
                    console.log(error.responseText);
                }
            });


        })

        //#endregion

        //#region Historial
        var options_historial = {
            beforeSubmit: function (arr, $form, options) {
                var vSubmit = $("#frmHistorial").valid();

                if (vSubmit) {
                    ShowLoading('Actualizando información...');
                    $('#frmHistorial button[type=submit], #frmHistorial input[type=submit]').prop('disabled', true);
                }

                return vSubmit;
            },
            success: function (data) {
                console.log(data);
                if(data.Saved)
                {
                    $('#divFormHistorial').html(data.Html);
                    $('#frmHistorial').modal('hide');
                }

                var vMessageClass = data.Saved ? 'success' : 'error';
                MyToast('Notificación', data.Message, vMessageClass);

                $('#frmHistorial button[type=submit], #frmHistorial input[type=submit]').prop('disabled', false);
            }, error: function (data)
            {
                console.log(data);
                MyToast("Notificación", "Ocurrio un error", "error");
                $('#frmHistorial button[type=submit], #frmHistorial input[type=submit]').prop('disabled', false);
            }
        };

        $('#frmHistorial').ajaxForm(options_historial);
        //#endregion

        //#region Informacion de contacto
        var options_informacion_contacto = {
            beforeSubmit: function (arr, $form, options) {
                var vSubmit = $("#frmInformacionContacto").valid();

                if (vSubmit) {
                    ShowLoading('Actualizando información...');
                    $('#frmInformacionContacto button[type=submit], #frmInformacionContacto input[type=submit]').prop('disabled', true);
                }

                return vSubmit;
            },
            success: function (data) {
                console.log(data);
                if(data.Saved)
                {
                    $('#divFormInformacionContacto').html(data.Html);
                }

                var vMessageClass = data.Saved ? 'success' : 'error';
                MyToast('Notificación', data.Message, vMessageClass);

                $('#frmInformacionContacto button[type=submit], #frmInformacionContacto input[type=submit]').prop('disabled', false);
            }, error: function (data)
            {
                console.log(data);
                MyToast("Notificación", "Ocurrio un error", "error");
                $('#frmInformacionContacto button[type=submit], #frmInformacionContacto input[type=submit]').prop('disabled', false);
            }
        };

        $('#frmInformacionContacto').ajaxForm(options_informacion_contacto);
        //#endregion

        //#region Informacion laboral
        var options_informacion_laboral = {
            beforeSubmit: function (arr, $form, options) {
                var vSubmit = $("#frmInformacionLaboral").valid();

                if (vSubmit) {
                    ShowLoading('Actualizando información...');
                    $('#frmInformacionLaboral button[type=submit], #frmInformacionLaboral input[type=submit]').prop('disabled', true);
                }

                return vSubmit;
            },
            success: function (data) {
                console.log(data);
                if(data.Saved)
                {
                    $('#divFormInformacionLaboral').html(data.Html);
                }

                var vMessageClass = data.Saved ? 'success' : 'error';
                MyToast('Notificación', data.Message, vMessageClass);

                $('#frmInformacionLaboral button[type=submit], #frmInformacionLaboral input[type=submit]').prop('disabled', false);
            }, error: function (data)
            {
                console.log(data);
                MyToast("Notificación", "Ocurrio un error", "error");
                $('#frmInformacionLaboral button[type=submit], #frmInformacionLaboral input[type=submit]').prop('disabled', false);
            }
        };

        $('#frmInformacionLaboral').ajaxForm(options_informacion_laboral);
        //#endregion

        //#region Economia
        var options_economia = {
            beforeSubmit: function (arr, $form, options) {
                var vSubmit = $("#frmEconomia").valid();

                if (vSubmit) {
                    ShowLoading('Actualizando información...');
                    $('#frmEconomia button[type=submit], #frmEconomia input[type=submit]').prop('disabled', true);
                }

                return vSubmit;
            },
            success: function (data) {
                console.log(data);
                if(data.Saved)
                {
                    $('#divFormEconomia').html(data.Html);
                }

                var vMessageClass = data.Saved ? 'success' : 'error';
                MyToast('Notificación', data.Message, vMessageClass);

                $('#frmEconomia button[type=submit], #frmEconomia input[type=submit]').prop('disabled', false);
            }, error: function (data)
            {
                console.log(data);
                MyToast("Notificación", "Ocurrio un error", "error");
                $('#frmEconomia button[type=submit], #frmEconomia input[type=submit]').prop('disabled', false);
            }
        };

        $('#frmEconomia').ajaxForm(options_economia);
        //#endregion

        //#region Conyuge
        var options_conyuge = {
            beforeSubmit: function (arr, $form, options) {
                var vSubmit = $("#frmConyuge").valid();

                if (vSubmit) {
                    ShowLoading('Actualizando información...');
                    $('#frmConyuge button[type=submit], #frmConyuge input[type=submit]').prop('disabled', true);
                }

                return vSubmit;
            },
            success: function (data) {
                console.log(data);
                if(data.Saved)
                {
                    $('#divFormConyuge').html(data.Html);
                }

                var vMessageClass = data.Saved ? 'success' : 'error';
                MyToast('Notificación', data.Message, vMessageClass);

                $('#frmConyuge button[type=submit], #frmConyuge input[type=submit]').prop('disabled', false);
            }, error: function (data)
            {
                console.log(data);
                MyToast("Notificación", "Ocurrio un error", "error");
                $('#frmConyuge button[type=submit], #frmConyuge input[type=submit]').prop('disabled', false);
            }
        };

        $('#frmConyuge').ajaxForm(options_conyuge);
        //#endregion


        $(document).on('click', '#tabInformacionAval [data-toggle="tab"]', function(){
            var vTab = $(this).attr('href').substring(1);
            var vDivReplaceId = $(this).data('replace-div');
            vDivReplaceId = vDivReplaceId == undefined ? vTab : vDivReplaceId;


            ShowLoading('Cargando información...');
            $.ajax({
                url: "{{route('avales.get_tab_information')}}",
                dataType: "html",
                type: "GET",
                data: {
                    aval_id: '{{ $model->aval_id }}',
                    tab: vTab
                },
                cache: false,
                success: function (data) {
                    $('#' + vDivReplaceId).html(data);

                },
                error: function (error) {
                    console.log("error");
                    console.log(error.responseText);
                }
            });
        })


        var options_datos_generales = {
            beforeSubmit: function (arr, $form, options) {
                var vSubmit = $("#frmEditDatosGenerales").valid();

                if (vSubmit) {
                    ShowLoading('Actualizando información...');
                    $('#frmEditDatosGenerales button[type=submit], #frmEditDatosGenerales input[type=submit]').prop('disabled', true);
                }

                return vSubmit;
            },
            success: function (data) {

                if(data.Saved)
                {
                    location.reload();
                    $('#divDatosGenerales').html(data.Html);
                    $('#modalEdit').modal('hide');
                }

                var vMessageClass = data.Saved ? 'success' : 'error';
                MyToast('Notificación', data.Message, vMessageClass);

                $('#frmEditDatosGenerales button[type=submit], #frmEditDatosGenerales input[type=submit]').prop('disabled', false);
            }, error: function (data)
            {
                console.log(data);
                MyToast("Notificación", "Ocurrio un error", "error");
                $('#frmEditDatosGenerales button[type=submit], #frmEditDatosGenerales input[type=submit]').prop('disabled', false);
            }
        };

        $('#frmEditDatosGenerales').ajaxForm(options_datos_generales);

        $(function()
        {

        })

    </script>
@endsection