@extends('layouts.layout')

@php
    $show_conyuge = $model->estado_civil == 2 || $model->estado_civil == 4;
@endphp

@section('content')

@if(session()->has('message_prestamo'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('message_prestamo') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
    <div class="wrapper wrapper-content">
        <div class="row animated fadeInRight">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-10">
                                <h5 class="m-0">
                                    Datos del cliente
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
                            @include('clientes.views_details._datos_generales', [ 'model' => $model])
                        </div>
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <a href="{{ route('prestamos.generar', $model->cliente_id) }}" class="btn btn-info"><i class="mdi mdi-account-cash"></i> Prestamo</a>
                            <a href="{{ route('clientes.historial', $model->cliente_id) }}" class="btn btn-info"><i class="mdi mdi-history"></i> Historial</a>
                            @if(Auth::user()->tiene_corte_abierto)
                                <a href="{{ route('clientes.pagos', $model->cliente_id) }}" class="btn btn-info"><i class="mdi mdi-cash-usd"></i> Pagos</a>
                                <a href="{{ route('prestamos.entregar', $model->cliente_id) }}" class="btn btn-info"><i class="mdi mdi-cash-refund"></i> Entregar prestamo</a>
                            @endif
                            <a href="#" class="btn btn-info" data-toggle="modal" data-target="#modalReportes"><i class="mdi mdi-file-pdf"></i> Reportes</a>
                            <a href="#" class="btn btn-info" data-toggle="modal" data-target="#modalLigas"><i class="mdi mdi-link"></i> Ligas</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 m-t-lg">
                <ul class="nav nav-tabs" id="tabInformacionCliente" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab-documentos" role="tab" aria-controls="tab-documentos" aria-selected="true">Documentos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab-historial" data-replace-div="divFormHistorial" role="tab" aria-controls="tab-historial" aria-selected="true">Historial</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab-informacion-contacto" data-replace-div="divFormInformacionContacto" role="tab" aria-controls="tab-telefonos" aria-selected="true">Informaci??n de contacto</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab-informacion-laboral" data-replace-div="divFormInformacionLaboral" role="tab" aria-controls="tab-informacion-laboral" aria-selected="true">Informaci??n laboral</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab-economia" data-replace-div="divFormEconomia" role="tab" aria-controls="tab-economia" aria-selected="true">Economia</a>
                    </li>
                    @if($show_conyuge)
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab-conyuge" data-replace-div="divFormConyuge" role="tab" aria-controls="tab-conyuge" aria-selected="true">Conyuge</a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab-referencias" role="tab" aria-controls="tab-referencias" aria-selected="true">Referencias</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div id="tab-documentos" class="tab-pane"></div>

                    <div id="tab-historial" class="tab-pane">
                        {{ Form::open([ 'route' => ['clientes.manage_historial' ], 'method' => 'POST', 'id' => 'frmHistorial' ]) }}
                        {{ Form::hidden('cliente_id', $model->cliente_id) }}
                        <div id="divFormHistorial">

                        </div>
                        {{ Form::close() }}
                    </div>

                    <div id="tab-informacion-contacto" class="tab-pane">
                        {{ Form::open([ 'route' => ['clientes.manage_informacion_contacto' ], 'method' => 'POST', 'id' => 'frmInformacionContacto' ]) }}
                        {{ Form::hidden('cliente_id', $model->cliente_id) }}
                        <div id="divFormInformacionContacto">

                        </div>
                        {{ Form::close() }}
                    </div>

                    <div id="tab-informacion-laboral" class="tab-pane">
                        {{ Form::open([ 'route' => ['clientes.manage_informacion_laboral' ], 'method' => 'POST', 'id' => 'frmInformacionLaboral' ]) }}
                        {{ Form::hidden('cliente_id', $model->cliente_id) }}
                        <div id="divFormInformacionLaboral">

                        </div>
                        {{ Form::close() }}
                    </div>

                    <div id="tab-economia" class="tab-pane">
                        {{ Form::open([ 'route' => ['clientes.manage_economia' ], 'method' => 'POST', 'id' => 'frmEconomia' ]) }}
                        {{ Form::hidden('cliente_id', $model->cliente_id) }}
                        <div id="divFormEconomia">

                        </div>
                        {{ Form::close() }}
                    </div>

                    @if($show_conyuge)
                        <div id="tab-conyuge" class="tab-pane">
                            {{ Form::open([ 'route' => ['clientes.manage_conyuge' ], 'method' => 'POST', 'id' => 'frmConyuge' ]) }}
                            {{ Form::hidden('cliente_id', $model->cliente_id) }}
                            <div id="divFormConyuge">

                            </div>
                            {{ Form::close() }}
                        </div>
                    @endif

                    <div id="tab-referencias" class="tab-pane"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal inmodal" id="modelLimiteCredito" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content animated bounceInUp">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">??</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">L??mite de cr??dito</h4>
                </div>

                <div class="modal-body">
                    {{ Form::open([ 'route' => ['clientes.edit_limite_credito' ], 'method' => 'POST', 'id' => 'frmEditLimiteCredito', 'onsubmit' => 'return false;' ]) }}
                        {{ Form::hidden('cliente_id', $model->cliente_id) }}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Estatus del cliente</label>
                                    {{ Form::select('estatus', $estatus, $model->estatus, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion']) }}
                                </div>
                            </div>

                            @if(auth()->user()->rol->rol == HelperCrediuno::$admin_gral_rol || auth()->user()->rol->rol == HelperCrediuno::$admin_rol)
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="checkbox checkbox-success">
                                        <input type="checkbox" id="mostrar_cobranza" class="check-box-value" {{ $model->mostrar_cobranza ? "checked" : "" }} value="1">
                                        {{ Form::label('mostrar_cobranza', 'Mostrar en cobranza') }}
                                    </div>
                                    {{ Form::hidden('mostrar_cobranza', $model->mostrar_cobranza) }}
                                </div>
                            </div>
                            @endif
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('limite_credito', 'L??mite de cr??dito') }}
                                    {{ Form::text('limite_credito', $model->limite_credito, [ 'class' => 'form-control just-decimal' ]) }}
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('nota', 'Agregar nota') }}
                                    {{ Form::textarea('nota', null, [ 'class' => 'form-control', 'rows' => 3 ]) }}
                                </div>
                            </div>

                            <div id="divNotas" class="col-md-12">
                                @include("clientes.views_details._ultimas_notas", ['notas' => null])
                            </div>

                        </div>
                    {{ Form::close() }}
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btnSaveLimiteCredito">Guardar</button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal inmodal" id="modalManageDocumentos" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content animated bounceInUp">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">??</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Administraci??n de documentos</h4>
                </div>
                {{ Form::open([ 'route' => ['clientes.manage_documentos' ], 'method' => 'POST', 'id' => 'frmManageDocumentos' ]) }}
                <div class="modal-body">

                    {{ Form::hidden('cliente_id', $model->cliente_id) }}
                    <div class="row" id="inputsDocumentos">

                        @include('clientes.views_details.documentos._form', [ 'model' => null ])

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

    <div class="modal inmodal" id="modalManageReferencias" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content animated bounceInUp">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">??</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Administraci??n de referencias</h4>
                </div>
                {{ Form::open([ 'route' => ['clientes.manage_referencias' ], 'method' => 'POST', 'id' => 'frmManageReferencias' ]) }}
                <div class="modal-body">

                    {{ Form::hidden('cliente_id', $model->cliente_id) }}
                    <div class="row" id="inputsReferencias">

                        @include('clientes.views_details.referencias._form', [ 'model' => null ])

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

    <div class="modal inmodal" id="modalVerReferencias" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content animated bounceInUp">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">??</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Detalles de la referencias</h4>
                </div>
                <div class="modal-body">

                    <div class="row" id="detailsReferencia">

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal inmodal" id="modalEdit" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-width: 1000px;">
            <div class="modal-content animated bounceInUp">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">??</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Editar informaci??n general</h4>
                </div>
                {{ Form::open([ 'route' => ['clientes.edit_datos_generales' ], 'method' => 'POST', 'id' => 'frmEditDatosGenerales', 'enctype' => 'multipart/form-data' ]) }}
                <div class="modal-body">
                    @include('clientes.views_details._edit_datos_generales', [ 'model' => $model ])
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>

    <div class="modal inmodal" id="modalReportes" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content animated bounceInUp">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">??</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Reportes</h4>
                </div>
                <div class="modal-body">

                    <div class="form-row">
                        <div class="col">
                            <a href="{{ route('clientes.ficha_socio_pdf', $model->cliente_id) }}" class="btn btn-info"><i class="mdi mdi-file-pdf"></i> Ficha de socio</a>
                            <a href="#" data-para-cliente="false" class="btn btn-info download-tabla-amortizacion"><i class="mdi mdi-file-pdf"></i> Tabla de amortizaci??n 1</a>
                            <a href="#" data-para-cliente="true" class="btn btn-info download-tabla-amortizacion"><i class="mdi mdi-file-pdf"></i> Tabla de amortizaci??n 2</a>
                        </div>
                    </div>
                    <div class="form-row mt-2">
                        <div class="col">
                            <a href="#" class="btn btn-info download-pagare"><i class="mdi mdi-file-pdf"></i> Pagare</a>
                            <a href="#" class="btn btn-info download-contrato"><i class="mdi mdi-file-pdf"></i> Contrato</a>
                            <a href="{{ route('clientes.carta_invitacion_pdf', $model->cliente_id) }}" class="btn btn-info"><i class="mdi mdi-file-pdf"></i> Carta de invitaci??n</a>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                {{ Form::label('prestamo_report', 'Seleccione su prestamo') }}
                                {{ Form::select('prestamo_report', $prestamos, null, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion']) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

<div class="modal inmodal" id="modalLigas" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated bounceInUp">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">??</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Ligas</h4>
            </div>
            <div class="modal-body">

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@if($notas_aviso && !$notas_aviso->isEmpty())
    <div class="modal inmodal" id="modalNotasAviso" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content animated bounceInUp">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">??</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Notas de aviso</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">

                            <ol>
                                @foreach ($notas_aviso as $item)
                                    <li>{{ $item->nota }}</li>
                                @endforeach
                            </ol>

                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="btnNotasAvisoVistas" class="btn btn-primary">Vistas</button>
                </div>
            </div>
        </div>
    </div>
@endif

@endsection



@section("scripts")
    @parent

    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    <script src="{{ asset('js/jquery/jquery.form.min.js')}}"></script>

    {!! JsValidator::formRequest('App\Http\Requests\LimiteCreditoUpdateRequest', '#frmEditLimiteCredito') !!}
    {!! JsValidator::formRequest('App\Http\Requests\DocumentosClienteRequest', '#frmManageDocumentos') !!}
    {!! JsValidator::formRequest('App\Http\Requests\ReferenciasClienteRequest', '#frmManageReferencias') !!}
    {!! JsValidator::formRequest('App\Http\Requests\ClientesRequest', '#frmEditDatosGenerales') !!}


    <script>

        //#region Documentos
        var options_document = {
            beforeSubmit: function (arr, $form, options) {
                var vSubmit = $("#frmManageDocumentos").valid();

                if (vSubmit) {
                    ShowLoading('Actualizando informaci??n...');
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
                MyToast('Notificaci??n', data.Message, vMessageClass);

                $('#frmManageDocumentos button[type=submit], #frmManageDocumentos input[type=submit]').prop('disabled', false);
            }, error: function (data)
            {
                console.log(data);
                MyToast("Notificaci??n", "Ocurrio un error", "error");
                $('#frmManageDocumentos button[type=submit], #frmManageDocumentos input[type=submit]').prop('disabled', false);
            }
        };

        $('#frmManageDocumentos').ajaxForm(options_document);

        $(document).on('click', '.edit-documento', function(event){
            event.preventDefault();

            var vDocumentoId = $(this).data('documento-id');

            $.ajax({
                url: "{{route('clientes.get_form_documento')}}",
                dataType: "html",
                type: "GET",
                data: {
                    documento_cliente_id: vDocumentoId
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

        $(document).on('click', '.delete-documento', function(event){
            event.preventDefault();

            var vDocumentoId = $(this).data('documento-id');

            Swal.fire({
                title: '??Desea continuar?',
                text: 'El registro quedara eliminado.',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#809fff',
                cancelButtonColor: '#d33',
                confirmButtonText: "Si, continuar",
                cancelButtonText: "No, cancelar",
            }).then((result) => {
                if (result.value) {
                    ShowLoading("Eliminando registro...");
                    $.ajax({
                        url: "{{route('clientes.delete_documento')}}",
                        dataType: "json",
                        type: "POST",
                        data: {
                            documento_cliente_id: vDocumentoId,
                            _token: "{{ csrf_token() }}"
                        },
                        cache: false,
                        success: function (data) {

                            if(data.Saved)
                            {
                                $('#tab-documentos').html(data.Html);
                            }

                            var vMessageClass = data.Saved ? 'success' : 'error';
                            MyToast('Notificaci??n', data.Message, vMessageClass);
                        },
                        error: function (error) {
                            console.log("error");
                            console.log(error.responseText);
                        }
                    });
                }
            });
        })

        $(document).on('click', '#agregarNuevoDocumento', function(event){
            event.preventDefault();

            $.ajax({
                url: "{{route('clientes.get_form_documento')}}",
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
                    ShowLoading('Actualizando informaci??n...');
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
                MyToast('Notificaci??n', data.Message, vMessageClass);

                $('#frmHistorial button[type=submit], #frmManageDocumentos input[type=submit]').prop('disabled', false);
            }, error: function (data)
            {
                console.log(data);
                MyToast("Notificaci??n", "Ocurrio un error", "error");
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
                    ShowLoading('Actualizando informaci??n...');
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
                MyToast('Notificaci??n', data.Message, vMessageClass);

                $('#frmInformacionContacto button[type=submit], #frmInformacionContacto input[type=submit]').prop('disabled', false);
            }, error: function (data)
            {
                console.log(data);
                MyToast("Notificaci??n", "Ocurrio un error", "error");
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
                    ShowLoading('Actualizando informaci??n...');
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
                MyToast('Notificaci??n', data.Message, vMessageClass);

                $('#frmInformacionLaboral button[type=submit], #frmInformacionLaboral input[type=submit]').prop('disabled', false);
            }, error: function (data)
            {
                console.log(data);
                MyToast("Notificaci??n", "Ocurrio un error", "error");
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
                    ShowLoading('Actualizando informaci??n...');
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
                MyToast('Notificaci??n', data.Message, vMessageClass);

                $('#frmEconomia button[type=submit], #frmEconomia input[type=submit]').prop('disabled', false);
            }, error: function (data)
            {
                console.log(data);
                MyToast("Notificaci??n", "Ocurrio un error", "error");
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
                    ShowLoading('Actualizando informaci??n...');
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
                MyToast('Notificaci??n', data.Message, vMessageClass);

                $('#frmConyuge button[type=submit], #frmConyuge input[type=submit]').prop('disabled', false);
            }, error: function (data)
            {
                console.log(data);
                MyToast("Notificaci??n", "Ocurrio un error", "error");
                $('#frmConyuge button[type=submit], #frmConyuge input[type=submit]').prop('disabled', false);
            }
        };

        $('#frmConyuge').ajaxForm(options_conyuge);
        //#endregion

        //#region Referencias
        var options_referencia = {
            beforeSubmit: function (arr, $form, options) {
                var vSubmit = $("#frmManageReferencias").valid();

                if (vSubmit) {
                    ShowLoading('Actualizando informaci??n...');
                    $('#frmManageReferencias button[type=submit], #frmManageReferencias input[type=submit]').prop('disabled', true);
                }

                return vSubmit;
            },
            success: function (data) {
                console.log(data);
                if(data.Saved)
                {
                    $('#tab-referencias').html(data.Html);
                    $('#modalManageReferencias').modal('hide');
                }

                var vMessageClass = data.Saved ? 'success' : 'error';
                MyToast('Notificaci??n', data.Message, vMessageClass);

                $('#frmManageReferencias button[type=submit], #frmManageReferencias input[type=submit]').prop('disabled', false);
            }, error: function (data)
            {
                console.log(data);
                MyToast("Notificaci??n", "Ocurrio un error", "error");
                $('#frmManageReferencias button[type=submit], #frmManageReferencias input[type=submit]').prop('disabled', false);
            }
        };

        $('#frmManageReferencias').ajaxForm(options_referencia);

        $(document).on('click', '.edit-referencia', function(event){
            event.preventDefault();

            var vReferenciaId = $(this).data('referencia-id');

            $.ajax({
                url: "{{route('clientes.get_form_referencia')}}",
                dataType: "html",
                type: "GET",
                data: {
                    referencia_cliente_id: vReferenciaId
                },
                cache: false,
                success: function (data) {

                    $('#inputsReferencias').html(data);
                    $("#modalManageReferencias").modal('show');
                    $('#modalManageReferencias #nombre').focus();
                },
                error: function (error) {
                    console.log("error");
                    console.log(error.responseText);
                }
            });
        })

        $(document).on('click', '.ver-referencia', function(event){
            event.preventDefault();

            var vReferenciaId = $(this).data('referencia-id');

            $.ajax({
                url: "{{route('clientes.get_form_referencia_details')}}",
                dataType: "html",
                type: "GET",
                data: {
                    referencia_cliente_id: vReferenciaId
                },
                cache: false,
                success: function (data) {

                    $('#detailsReferencia').html(data);
                    $("#modalVerReferencias").modal('show');
                },
                error: function (error) {
                    console.log("error");
                    console.log(error.responseText);
                }
            });
        })

        $(document).on('click', '.delete-referencia', function(event){
            event.preventDefault();

            var vReferenciaId = $(this).data('referencia-id');

            Swal.fire({
                title: '??Desea continuar?',
                text: 'El registro quedara eliminado.',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#809fff',
                cancelButtonColor: '#d33',
                confirmButtonText: "Si, continuar",
                cancelButtonText: "No, cancelar",
            }).then((result) => {
                if (result.value) {
                    ShowLoading("Eliminando registro...");
                    $.ajax({
                        url: "{{route('clientes.delete_referencia')}}",
                        dataType: "json",
                        type: "POST",
                        data: {
                            referencia_cliente_id: vReferenciaId,
                            _token: "{{ csrf_token() }}"
                        },
                        cache: false,
                        success: function (data) {

                            if(data.Saved)
                            {
                                $('#tab-referencias').html(data.Html);
                            }

                            var vMessageClass = data.Saved ? 'success' : 'error';
                            MyToast('Notificaci??n', data.Message, vMessageClass);
                        },
                        error: function (error) {
                            console.log("error");
                            console.log(error.responseText);
                        }
                    });
                }
            });
        })

        $(document).on('click', '#agregarNuevaReferencia', function(event){
            event.preventDefault();

            $.ajax({
                url: "{{route('clientes.get_form_referencia')}}",
                dataType: "html",
                type: "GET",
                data: {
                },
                cache: false,
                success: function (data) {
                    $('#inputsReferencias').html(data);
                    $("#modalManageReferencias").modal('show');
                    $('#modalManageReferencias #nombre').focus();
                },
                error: function (error) {
                    console.log("error");
                    console.log(error.responseText);
                }
            });


        })

        //#endregion

        $(document).on('click', '#tabInformacionCliente [data-toggle="tab"]', function(){
            var vTab = $(this).attr('href').substring(1);
            var vDivReplaceId = $(this).data('replace-div');
            vDivReplaceId = vDivReplaceId == undefined ? vTab : vDivReplaceId;


            ShowLoading('Cargando informaci??n...');
            $.ajax({
                url: "{{route('clientes.get_tab_information')}}",
                dataType: "html",
                type: "GET",
                data: {
                    cliente_id: '{{ $model->cliente_id }}',
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

        $(document).on('click', '#pLimiteCredito', function(){
            $.ajax({
                url: "{{route('clientes.get_notas')}}",
                dataType: "html",
                type: "GET",
                data: {
                    cliente_id: '{{ $model->cliente_id }}'
                },
                cache: false,
                success: function (data) {
                    $('#divNotas').html(data);
                    $('#modelLimiteCredito').modal('show');
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
                    ShowLoading('Actualizando informaci??n...');
                    $('#frmEditDatosGenerales button[type=submit], #frmManageDocumentos input[type=submit]').prop('disabled', true);
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
                MyToast('Notificaci??n', data.Message, vMessageClass);

                $('#frmEditDatosGenerales button[type=submit], #frmEditDatosGenerales input[type=submit]').prop('disabled', false);
            }, error: function (data)
            {
                console.log(data);
                MyToast("Notificaci??n", "Ocurrio un error", "error");
                $('#frmEditDatosGenerales button[type=submit], #frmEditDatosGenerales input[type=submit]').prop('disabled', false);
            }
        };

        $('#frmEditDatosGenerales').ajaxForm(options_datos_generales);

        $(function()
        {
            @if($notas_aviso && !$notas_aviso->isEmpty())
            $('#modalNotasAviso').modal('show');
            @endif

            $('#btnNotasAvisoVistas').click(function(){
                ShowLoading('Guardando informaci??n...');
                $.ajax({
                    url: "{{route('clientes.notas_aviso_vistas')}}",
                    dataType: "json",
                    type: "POST",
                    data: {
                        cliente_id: '{{ $model->cliente_id }}',
                        _token: "{{ csrf_token() }}"
                    },
                    cache: false,
                    success: function (data) {

                        if(data.Saved)
                        {
                            $('#modalNotasAviso').modal('hide');
                        }

                        var vMessageClass = data.Saved ? 'success' : 'error';
                        MyToast('Notificaci??n', data.Message, vMessageClass);
                        HideLoading();
                    },
                    error: function (error) {
                        console.log("error");
                        console.log(error.responseText);
                    }
                });
            })

            var LigasOpened = false;
            $('[data-target="#modalLigas"]').click(function () {
                if(!LigasOpened){
                    LigasOpened = true;
                    $.ajax({
                        url: "{{route('clientes.get_ligas')}}",
                        dataType: "html",
                        type: "GET",
                        data: {
                            cliente_id: '{{ $model->cliente_id }}',
                            full_name: '{{ $model->full_name }}',
                            aval_id: '{{ $model->aval_id }}'
                        },
                        cache: false,
                        success: function (data) {
                            $('#modalLigas .modal-body').html(data);
                        },
                        error: function (error) {
                            console.log("error");
                            console.log(error.responseText);
                        }
                    });
                }
            })

            $("#btnSaveLimiteCredito").click(function(){
                var vForm = $("#frmEditLimiteCredito");

                if(!vForm.valid())
                    return;

                ShowLoading('Actualizando informaci??n...');
                $.ajax({
                    url: "{{route('clientes.edit_limite_credito')}}",
                    dataType: "json",
                    type: "POST",
                    data: vForm.serialize(),
                    cache: false,
                    success: function (data) {

                        if(data.Saved)
                        {
                            $('#divDatosGenerales').html(data.Html);
                            $('#nota').val('');
                            $('#modelLimiteCredito').modal('hide');
                        }

                        var vMessageClass = data.Saved ? 'success' : 'error';
                        MyToast('Notificaci??n', data.Message, vMessageClass);
                        HideLoading();
                    },
                    error: function (error) {
                        console.log("error");
                        console.log(error.responseText);
                    }
                });
            })

            $('.download-tabla-amortizacion').click(function(event){
                event.preventDefault();
                var prestamoId = $('#prestamo_report').val();
                if(!prestamoId)
                {
                    MyToast('Notificaci??n', 'Seleccione un prestamo para generar el reporte.', 'warning');
                    return;
                }
                var paraCliente = $(this).data('para-cliente');
                location.href = '{{ route('clientes.tabla_amortizacion_pdf') }}?id=' + prestamoId + '&para_cliente=' + paraCliente;
            })

            $('.download-pagare').click(function(event){
                event.preventDefault();
                var prestamoId = $('#prestamo_report').val();
                if(!prestamoId)
                {
                    MyToast('Notificaci??n', 'Seleccione un prestamo para generar el reporte.', 'warning');
                    return;
                }
                location.href = '{{ route('clientes.pagare_pdf') }}?id=' + prestamoId;
            })

            $('.download-contrato').click(function(event){
                event.preventDefault();
                var prestamoId = $('#prestamo_report').val();
                if(!prestamoId)
                {
                    MyToast('Notificaci??n', 'Seleccione un prestamo para generar el reporte.', 'warning');
                    return;
                }
                location.href = '{{ route('clientes.contrato_pdf') }}?id=' + prestamoId;
            })
        })

    </script>
@endsection
