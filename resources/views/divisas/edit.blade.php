@extends('layouts.layout')

@section("title", "Editar divisas")


@section('content')

    {{ Form::open([ 'route' => ['divisas.edit_post' ], 'method' => 'POST', 'id' => 'frmEdit' ]) }}

    {{ Form::hidden('divisa_id', $model->divisa_id) }}

    <div class="card">
        <div class="card-header">
            <h5 class="m-0">
                Datos
            </h5>
        </div>
        <div class="card-body">


            <div class="form-row">
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('nombre', 'Nombre') }}
                        {{ Form::text('nombre', $model->divisa, [ 'class' => 'form-control', 'disabled' => true ]) }}
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('sucursal_id', 'Por sucursal') }}
                        {{ Form::select('sucursal_id', $sucursales, null, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion' ]) }}
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('ciudad_id', 'Por ciudad') }}
                        {{ Form::select('ciudad_id', $ciudades, null, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion' ]) }}
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('divisa_compra', 'Compra') }}
                        {{ Form::text('divisa_compra', null, [ 'class' => 'form-control just-decimal', 'autofocus' => true ]) }}
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('divisa_venta', 'Venta') }}
                        {{ Form::text('divisa_venta', null, [ 'class' => 'form-control just-decimal' ]) }}
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('iva_divisa', 'IVA divisa') }}
                        {{ Form::text('iva_divisa', null, [ 'class' => 'form-control just-decimal' ]) }}
                    </div>
                </div>

            </div>

            <div class="form-row">
                <div class="col-md-12 col-sm-12 col-12">
                    <div class="form-group text-right">
                        <a href="{{ route('divisas.index') }}" class="btn btn-sm btn-secondary">Regresar</a>
                        <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{ Form::close() }}

@endsection



@section("scripts")
    @parent

    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

    {!! JsValidator::formRequest('App\Http\Requests\DivisasRequest', '#frmEdit') !!}

    <script>

        $(function()
        {
            $("#frmEdit").submit(function(){
                var vSubmit = $(this).valid();

                if (vSubmit) {
                    $('button[type=submit], input[type=submit]').prop('disabled', true);
                    ShowLoading();
                }
                return vSubmit;
            })

        })
    </script>
@endsection