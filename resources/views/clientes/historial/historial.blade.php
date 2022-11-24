@extends('layouts.layout')


@section('content')

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
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="divDatosGenerales">
                            @include('clientes.views_details._datos_generales', [ 'model' => $model])
                        </div>
                        <a href="{{ route('clientes.details', $model->cliente_id) }}" class="btn btn-sm btn-secondary">Regresar</a>
                    </div>
                </div>
            </div>

            <div class="col-md-12 m-t-lg">
                <ul class="nav nav-tabs" id="tabInformacionCliente" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab-notas-cliente" role="tab" aria-controls="tab-notas-cliente" aria-selected="true">Notas del cliente</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab-notas-aviso" role="tab" aria-controls="tab-notas-aviso" aria-selected="true">Notas de aviso</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab-prestamos-vigentes" role="tab" aria-controls="tab-prestamos-vigentes" aria-selected="true">Prestamos vigentes</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab-prestamos-liquidados" role="tab" aria-controls="tab-prestamos-liquidados" aria-selected="true">Prestamos liquidados ({{ $total_prestamos_liquidados }})</a>
                    </li>

                </ul>

                <div class="tab-content">
                    <div id="tab-notas-cliente" class="tab-pane"></div>
                    <div id="tab-notas-aviso" class="tab-pane"></div>
                    <div id="tab-prestamos-vigentes" class="tab-pane"></div>
                    <div id="tab-prestamos-liquidados" class="tab-pane"></div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal inmodal" id="modalNuevaNotaCliente" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content animated bounceInUp">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Nueva nota de cliente</h4>
                </div>
                {{ Form::open([ 'route' => ['clientes.nueva_nota_cliente_post' ], 'method' => 'POST', 'id' => 'frmNuevaNotaCliente' ]) }}
                <div class="modal-body">

                    {{ Form::hidden('cliente_id', $model->cliente_id) }}
                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::label('nota', 'Nota') }}
                                {{ Form::textarea('nota', null, [ 'class' => 'form-control', 'rows' => 4 ]) }}
                            </div>
                        </div>


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

<div class="modal inmodal" id="modalNuevaNotaAviso" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content animated bounceInUp">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Nueva nota de aviso</h4>
                </div>
                {{ Form::open([ 'route' => ['clientes.nueva_nota_aviso_post' ], 'method' => 'POST', 'id' => 'frmNuevaNotaAviso' ]) }}
                <div class="modal-body">

                    {{ Form::hidden('cliente_id', $model->cliente_id) }}
                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::label('nota', 'Nota') }}
                                {{ Form::textarea('nota', null, [ 'class' => 'form-control', 'rows' => 4 ]) }}
                            </div>
                        </div>


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


@endsection



@section("scripts")
    @parent
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    <script src="{{ asset('js/jquery/jquery.form.min.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\NotaClienteRequest', '#frmNuevaNotaCliente') !!}
    {!! JsValidator::formRequest('App\Http\Requests\NotaAvisoRequest', '#frmNuevaNotaAviso') !!}
    <script>

        var options_nota_cliente = {
            beforeSubmit: function (arr, $form, options) {
                var vSubmit = $("#frmNuevaNotaCliente").valid();

                if (vSubmit) {
                    ShowLoading('Agregando nota...');
                    $('#frmNuevaNotaCliente button[type=submit], #frmNuevaNotaCliente input[type=submit]').prop('disabled', true);
                }

                return vSubmit;
            },
            success: function (data) {
                console.log(data);
                if(data.Saved)
                {
                    $('#modalNuevaNotaCliente').modal('hide');
                    $('#tab-notas-cliente').html(data.Html);
                    $('#frmNuevaNotaCliente #nota').val('');
                }

                var vMessageClass = data.Saved ? 'success' : 'error';
                MyToast('Notificación', data.Message, vMessageClass);

                $('#frmNuevaNotaCliente button[type=submit], #frmNuevaNotaCliente input[type=submit]').prop('disabled', false);
            }, error: function (data)
            {
                MyToast("Notificación", "Ocurrio un error", "error");
                $('#frmNuevaNotaCliente button[type=submit], #frmNuevaNotaCliente input[type=submit]').prop('disabled', false);
            }
        };

        $('#frmNuevaNotaCliente').ajaxForm(options_nota_cliente);


        var options_nota_aviso = {
            beforeSubmit: function (arr, $form, options) {
                var vSubmit = $("#frmNuevaNotaAviso").valid();

                if (vSubmit) {
                    ShowLoading('Agregando nota...');
                    $('#frmNuevaNotaAviso button[type=submit], #frmNuevaNotaAviso input[type=submit]').prop('disabled', true);
                }

                return vSubmit;
            },
            success: function (data) {
                console.log(data);
                if(data.Saved)
                {
                    $('#modalNuevaNotaAviso').modal('hide');
                    $('#tab-notas-aviso').html(data.Html);
                    $('#frmNuevaNotaAviso #nota').val('');
                }

                var vMessageClass = data.Saved ? 'success' : 'error';
                MyToast('Notificación', data.Message, vMessageClass);

                $('#frmNuevaNotaAviso button[type=submit], #frmNuevaNotaAviso input[type=submit]').prop('disabled', false);
            }, error: function (data)
            {
                MyToast("Notificación", "Ocurrio un error", "error");
                $('#frmNuevaNotaAviso button[type=submit], #frmNuevaNotaAviso input[type=submit]').prop('disabled', false);
            }
        };

        $('#frmNuevaNotaAviso').ajaxForm(options_nota_aviso);

        $(document).on('click', '#tabInformacionCliente [data-toggle="tab"]', function(){
            var vTab = $(this).attr('href').substring(1);
            var vDivReplaceId = $(this).data('replace-div');
            vDivReplaceId = vDivReplaceId == undefined ? vTab : vDivReplaceId;


            ShowLoading('Cargando información...');
            $.ajax({
                url: "{{route('clientes.get_tab_historial')}}",
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

        $(document).on('click', '#agregarNuevaNotaAviso', function(){
            $("#modalNuevaNotaAviso").modal('show');
        })

        $(document).on('click', '#agregarNuevaNotaCliente', function(){
            $("#modalNuevaNotaCliente").modal('show');
        })

        $(document).on('click', '.show-reestructuras', function(event){
            event.preventDefault();
            var prestamoId = $(this).data('prestamo-id');
            $('.childs-' + prestamoId).toggle();
            var buttonText = $(this).text() == 'Mostrar reestructuras' ? 'Ocultar reestructuras' : 'Mostrar reestructuras';
            $(this).text(buttonText);
        })

        $(function()
        {
        })

    </script>
@endsection
