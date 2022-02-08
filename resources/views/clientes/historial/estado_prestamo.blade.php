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
                                    Estado del prestamo
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-12 col-12">
                                @php
                                    $foto_perfil =  Storage::exists($model->foto) ? $model->foto : "public/user_profile/default.png";
                                @endphp
                                <div class="ibox-content no-padding text-center">
                                    <img alt="image" style="width: 163px; height: 163px;" class="img-fluid img-circle" src="{{ Storage::url($foto_perfil) }}">
                                    <h5 class="text-center">
                                        <strong>#{{ $model->cliente_id }} - {{ $model->nombre }} {{ $model->apellido_paterno }} {{ $model->apellido_materno }}</strong>
                                        <span id="spanEstatus">({{ \App\Enums\estatus_cliente::getDescription($model->estatus) }})</span>
                                    </h5>
                                </div>
                            </div>

                            <div class="col-md-9 col-sm-12 col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="m-0">
                                            Datos del prestamo
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3 col-sm-6 col-12">
                                                <div class="form-group">
                                                    <label>Folio:</label>
                                                    <div class="form-control-plaintext">{{ $prestamo->folio }}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-12">
                                                <div class="form-group">
                                                    <label>Estatus:</label>
                                                    <div class="form-control-plaintext">{{ \App\Enums\estatus_prestamo::getDescription($prestamo->estatus) }}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-12">
                                                <div class="form-group">
                                                    <label>Capital solicitado:</label>
                                                    <div class="form-control-plaintext">@money_format($prestamo->capital)</div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-12">
                                                @php
                                                    $total_adeudo = $prestamo->total_cargos + $prestamo->total_recibos;
                                                @endphp
                                                <div class="form-group">
                                                    <label>Adeudo total:</label>
                                                    <div class="form-control-plaintext">@money_format($total_adeudo)</div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-12">
                                                <div class="form-group">
                                                    <label>Número de pagos:</label>
                                                    <div class="form-control-plaintext">{{ $prestamo->numero_recibos }}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-12">
                                                <div class="form-group">
                                                    <label>Cargos generados:</label>
                                                    <div class="form-control-plaintext">{{ $prestamo->numero_cargos }}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-12">
                                                <div class="form-group">
                                                    <label>Adeudo recibos:</label>
                                                    <div class="form-control-plaintext">@money_format($prestamo->total_recibos)</div>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-sm-6 col-12">
                                                <div class="form-group">
                                                    <label>Fecha de entraga:</label>
                                                    @if($prestamo->fecha_entrega)
                                                        <div class="form-control-plaintext">{{ \Carbon\Carbon::parse($prestamo->fecha_entrega)->format('d/m/Y') }}</div>
                                                    @else
                                                        <div class="form-control-plaintext">Sin fecha</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h6 class="m-0">
                                            Datos del aval
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">

                                            <div class="col-md-12 col-sm-12 col-12">
                                                <div class="form-group">
                                                    @if($prestamo->aval_id)
                                                        <label>Nombre:</label>
                                                        <div class="form-control-plaintext">{{ $prestamo->tbl_aval->full_name }}</div>
                                                    @else
                                                        <label>&nbsp;</label>
                                                        <div class="form-control-plaintext">No cuenta con aval</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h6 class="m-0">
                                            Datos de la garantía
                                            @if($prestamo->garantia_id)
                                                {{ Str::lower(\App\Enums\tipos_garantia::getDescription($prestamo->tbl_garantia->tipo)) }}
                                            @endif
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">

                                            @if($prestamo->garantia_id)

                                                <div class="col-md-8 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <label>Descripción:</label>
                                                        <div class="form-control-plaintext">{{ $prestamo->tbl_garantia->descripcion }}</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <label>Valor:</label>
                                                        <div class="form-control-plaintext">@money_format($prestamo->tbl_garantia->valor)</div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label>&nbsp;</label>
                                                        <div class="form-control-plaintext">No cuenta con garantía</div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h6 class="m-0">
                                            Pagos del prestamo
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">


                                            <div class="col-md-2 col-sm-4 col-12">
                                                <div class="card">
                                                    <div class="card-body text-center">
                                                        <h5 class="card-title">Total</h5>
                                                        <hr>
                                                        <p class="card-text text-monospace">@money_format($prestamo->total_pagado_recibos + $prestamo->total_pagado_cargos)</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-2 col-sm-4 col-12">
                                                <div class="card">
                                                    <div class="card-body text-center">
                                                        <h5 class="card-title">Recibos</h5>
                                                        <hr>
                                                        <p class="card-text text-monospace">@money_format($prestamo->total_pagado_recibos)</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-2 col-sm-4 col-12">
                                                <div class="card">
                                                    <div class="card-body text-center">
                                                        <h5 class="card-title">Cargos</h5>
                                                        <hr>
                                                        <p class="card-text text-monospace">@money_format($prestamo->total_pagado_cargos)</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-sm-4 col-12">
                                                <div class="card">
                                                    <div class="card-body text-center">
                                                        <h5 class="card-title">Descuento</h5>
                                                        <hr>
                                                        <p class="card-text text-monospace">({{ $prestamo->total_pagos_con_descuento }}) @money_format($prestamo->total_pagado_con_descuento)</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-sm-4 col-12">
                                                <div class="card">
                                                    <div class="card-body text-center">
                                                        <h5 class="card-title">Refinanciamiento</h5>
                                                        <hr>
                                                        <p class="card-text text-monospace">({{ $prestamo->total_pagos_refinanciamiento }}) @money_format($prestamo->total_pagado_refinanciamiento)</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label class="d-block">&nbsp;</label>
                                <a href="{{ route('clientes.historial', $model->cliente_id) }}" class="btn btn-sm btn-secondary">Regresar</a>
                                <a href="#" class="btn btn-sm btn-primary">Generar reporte</a>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-6">
                                <h5 class="mb-2">Recibos pendientes</h5>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Día de pago</th>
                                            <th>Fecha limite</th>
                                            <th>Importe</th>
                                            <th>Tipo</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($prestamo->tbl_adeudos as $item)

                                            <tr>

                                                <td>{{ $item->numero_pago }}</td>
                                                <td>{{ \App\Helpers\HelperCrediuno::$nombres_dias[\Carbon\Carbon::parse($item->fecha_limite_pago)->format('l')] }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->fecha_limite_pago)->format('d/m/Y') }}</td>
                                                <td>@money_format($item->importe_total)</td>
                                                <td>Recibo</td>
                                            </tr>

                                            @if($item->tbl_cargo)
                                                <tr class="text-danger tr-cargo" style="font-weight: bolder;">

                                                    <td>{{ $item->numero_pago }}</td>
                                                    <td>{{ \App\Helpers\HelperCrediuno::$nombres_dias[\Carbon\Carbon::parse($item->fecha_limite_pago)->format('l')] }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($item->fecha_limite_pago)->format('d/m/Y') }}</td>
                                                    <td>@money_format($item->tbl_cargo->importe_total)</td>
                                                    <td>Cargo</td>
                                                </tr>
                                            @endif

                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-6">
                                <h5 class="mb-2">Recibos pagados</h5>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Día de pago</th>
                                            <th>Fecha pago</th>
                                            <th>Tipo</th>
                                            <th>Forma de pago</th>
                                            <th>Importe</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($prestamo->tbl_adeudos as $item)

                                            @foreach($item->tbl_pagos as $pago)
                                                <tr>
                                                    <td>{{ $item->numero_pago }}</td>
                                                    <td>{{ \App\Helpers\HelperCrediuno::$nombres_dias[\Carbon\Carbon::parse($pago->fecha_creacion)->format('l')] }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($pago->fecha_creacion)->format('d/m/Y') }}</td>
                                                    <td>Recibo</td>
                                                    <td>{{ \App\Enums\formas_pago::getDescription($pago->metodo_pago) }}</td>
                                                    <td>@money_format($pago->importe)</td>
                                                </tr>
                                            @endforeach

                                            @if($item->tbl_cargo)
                                                @foreach($item->tbl_cargo->tbl_pagos as $pago)
                                                    <tr class="text-danger tr-cargo" style="font-weight: bolder;">
                                                        <td>{{ $item->numero_pago }}</td>
                                                        <td>{{ \App\Helpers\HelperCrediuno::$nombres_dias[\Carbon\Carbon::parse($pago->fecha_creacion)->format('l')] }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($pago->fecha_creacion)->format('d/m/Y') }}</td>
                                                        <td>Cargo</td>
                                                        <td>{{ \App\Enums\formas_pago::getDescription($pago->metodo_pago) }}</td>
                                                        <td>@money_format($pago->importe)</td>
                                                    </tr>
                                                @endforeach
                                            @endif

                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>

                    </div>
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

        $(function()
        {

        })

    </script>
@endsection
