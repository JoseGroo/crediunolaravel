@extends('layouts.layout')

@section("title", "Crear nueva sucursal")


@section('content')

    @include('general._errors')

    {{ Form::open([ 'route' => ['sucursales.create_post' ], 'method' => 'POST', 'id' => 'frmCrear' ]) }}

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
                            {{ Form::label('sucursal', 'Sucursal') }}
                            {{ Form::text('sucursal', null, [ 'class' => 'form-control', 'autofocus' => true ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('estado_id', 'Estado') }}
                            {{ Form::select('estado_id', $estados, null, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion']) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('ciudad_id', 'Ciudad') }}
                            {{ Form::select('ciudad_id', $ciudades, null, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion']) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('numero_contrato', 'Número de contrato') }}
                            {{ Form::text('numero_contrato', null, [ 'class' => 'form-control' ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('telefono', 'Teléfono') }}
                            {{ Form::text('telefono', null, [ 'class' => 'form-control phone-mask' ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('beneficiario', 'Beneficiario') }}
                            {{ Form::text('beneficiario', null, [ 'class' => 'form-control' ]) }}
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12 col-12">
                        <div class="form-group">
                            {{ Form::label('direccion', 'Dirección') }}
                            {{ Form::textarea('direccion', null, [ 'class' => 'form-control', 'rows' => 4 ]) }}
                        </div>
                    </div>
                </div>

            </div>

            <div class="card-header border-top">
                <h5 class="m-0">
                    Divisas
                </h5>
            </div>
            <div class="card-body">
                <div class="form-row">

                    <div class="col-md-2 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('dolar_compra', 'Dolar compra') }}
                            {{ Form::text('dolar_compra', null, [ 'class' => 'form-control just-decimal' ]) }}
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('dolar_venta', 'Dolar venta') }}
                            {{ Form::text('dolar_venta', null, [ 'class' => 'form-control just-decimal' ]) }}
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('euro_compra', 'Euro compra') }}
                            {{ Form::text('euro_compra', null, [ 'class' => 'form-control just-decimal' ]) }}
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('euro_venta', 'Euro venta') }}
                            {{ Form::text('euro_venta', null, [ 'class' => 'form-control just-decimal' ]) }}
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('dolar_moneda_compra', 'Dolar moneda compra') }}
                            {{ Form::text('dolar_moneda_compra', null, [ 'class' => 'form-control just-decimal' ]) }}
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('dolar_moneda_venta', 'Dolar moneda venta') }}
                            {{ Form::text('dolar_moneda_venta', null, [ 'class' => 'form-control just-decimal' ]) }}
                        </div>
                    </div>


                    <div class="col-md-12 col-sm-12 col-12">
                        <div class="form-group text-right">
                            <a href="{{ route('sucursales.index') }}" class="btn btn-sm btn-secondary">Regresar</a>
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

    {!! JsValidator::formRequest('App\Http\Requests\SucursalesRequest', '#frmCrear') !!}
    <script>
        $(function()
        {
            $("#frmCrear").submit(function(){
                var vSubmit = $(this).valid();

                if (vSubmit) {
                    $('button[type=submit], input[type=submit]').prop('disabled', true);
                    ShowLoading();
                }
                return vSubmit;
            })

            $('#estado_id').change(function(){
                var vEstadoId = $(this).val();
                $.ajax({
                    url: "{{route('sucursales.get_ciudades_by_estado_id')}}",
                    dataType: "json",
                    type: "GET",
                    data: {
                        estado_id: vEstadoId
                    },
                    cache: false,
                    success: function (data) {
                        $('#ciudad_id').empty().append('<option>Seleccionar opcion</option>');

                        if(data != null)
                        {
                            for(var item of data)
                            {
                                $('#ciudad_id').append('<option value="' + item.ciudad_id + '">' + item.ciudad + '</option>');
                            }
                        }
                    },
                    error: function (error) {
                        console.log("error");
                        console.log(error.responseText);
                    }
                });
            })
        })
    </script>
@endsection