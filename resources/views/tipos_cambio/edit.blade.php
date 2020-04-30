@extends('layouts.layout')

@section("title", "Editar sucursal")


@section('content')

    <div class="row wrapper page-heading">
        <div class="col-sm-8">
            <h2>@yield("title")</h2>
        </div>
    </div>

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">

            <div class="row">
                <div class="col-12 m-b-sm">
                    <strong style="font-size: 16px;">Ocurrio un error al intentar guardar. Detalles:</strong>
                </div>

                <div class="col-12">
                    {{ session('error') }}
                </div>
            </div>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{ Form::open([ 'route' => ['sucursales.edit_post' ], 'method' => 'POST', 'id' => 'frmEdit' ]) }}

    {{ Form::hidden('sucursal_id', $model->sucursal_id) }}

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
                        {{ Form::text('sucursal', $model->sucursal, [ 'class' => 'form-control', 'autofocus' => true ]) }}
                        <span id="sucursal_validate"></span>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('estado_id', 'Estado') }}
                        {{ Form::select('estado_id', $estados, $model->estado_id, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion']) }}
                        <span id="estado_id_validate"></span>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('ciudad_id', 'Ciudad') }}
                        {{ Form::select('ciudad_id', $ciudades, $model->ciudad_id, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion']) }}
                        <span id="ciudad_id_validate"></span>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('numero_contrato', 'Número de contrato') }}
                        {{ Form::text('numero_contrato', $model->numero_contrato, [ 'class' => 'form-control' ]) }}
                        <span id="numero_contrato_validate"></span>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('telefono', 'Teléfono') }}
                        {{ Form::text('telefono', $model->telefono, [ 'class' => 'form-control phone-mask' ]) }}
                        <span id="telefono_validate"></span>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('beneficiario', 'Beneficiario') }}
                        {{ Form::text('beneficiario', $model->beneficiario, [ 'class' => 'form-control' ]) }}
                        <span id="beneficiario_validate"></span>
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-12">
                    <div class="form-group">
                        {{ Form::label('direccion', 'Dirección') }}
                        {{ Form::textarea('direccion', $model->direccion, [ 'class' => 'form-control', 'rows' => 4 ]) }}
                        <span id="direccion_validate"></span>
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
                        {{ Form::text('dolar_compra', $model->dolar_compra, [ 'class' => 'form-control just-decimal' ]) }}
                        <span id="dolar_compra_validate"></span>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('dolar_venta', 'Dolar venta') }}
                        {{ Form::text('dolar_venta', $model->dolar_venta, [ 'class' => 'form-control just-decimal' ]) }}
                        <span id="dolar_venta_validate"></span>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('euro_compra', 'Euro compra') }}
                        {{ Form::text('euro_compra', $model->euro_compra, [ 'class' => 'form-control just-decimal' ]) }}
                        <span id="euro_compra_validate"></span>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('euro_venta', 'Euro venta') }}
                        {{ Form::text('euro_venta', $model->euro_venta, [ 'class' => 'form-control just-decimal' ]) }}
                        <span id="euro_venta_validate"></span>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('dolar_moneda_compra', 'Dolar moneda compra') }}
                        {{ Form::text('dolar_moneda_compra', $model->dolar_moneda_compra, [ 'class' => 'form-control just-decimal' ]) }}
                        <span id="dolar_moneda_compra_validate"></span>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('dolar_moneda_venta', 'Dolar moneda venta') }}
                        {{ Form::text('dolar_moneda_venta', $model->dolar_moneda_venta, [ 'class' => 'form-control just-decimal' ]) }}
                        <span id="dolar_moneda_venta_validate"></span>
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

    <script>
        $(function()
        {
            $("#frmEdit").validate({
                rules: {
                    sucursal: "required",
                    estado_id: "required",
                    ciudad_id: "required",
                    beneficiario: "required",
                    dolar_compra: "required",
                    dolar_venta: "required",
                    euro_compra: "required",
                    euro_venta: "required",
                    dolar_moneda_compra: "required",
                    dolar_moneda_venta: "required",
                },
                errorPlacement: function (error, element) {
                    var name = $(element).attr("name");
                    error.appendTo($("#" + name + "_validate"));
                },
            })

            $("#frmEdit").submit(function(){
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