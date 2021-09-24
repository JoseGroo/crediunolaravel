@extends('layouts.layout')

@section("title", "Detalles de sucursal")


@section('content')

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
                        <div class="form-control-plaintext">{{ $model->sucursal }}</div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('estado', 'Estado') }}
                        <div class="form-control-plaintext">{{ $model->estado->estado }}</div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('ciudad', 'Ciudad') }}
                        <div class="form-control-plaintext">{{ $model->ciudad->ciudad }}</div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('numero_contrato', 'Número de contrato') }}
                        <div class="form-control-plaintext">{{ $model->numero_contrato }}</div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('telefono', 'Teléfono') }}
                        <div class="form-control-plaintext">{{ $model->telefono }}</div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('beneficiario', 'Beneficiario') }}
                        <div class="form-control-plaintext">{{ $model->beneficiario }}</div>
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-12">
                    <div class="form-group">
                        {{ Form::label('direccion', 'Dirección') }}
                        <div class="form-control-plaintext">{{ $model->direccion }}</div>
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
                        <div class="form-control-plaintext">${{ number_format($model->dolar_compra, 2) }}</div>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('dolar_venta', 'Dolar venta') }}
                        <div class="form-control-plaintext">${{ number_format($model->dolar_venta, 2) }}</div>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('euro_compra', 'Euro compra') }}
                        <div class="form-control-plaintext">${{ number_format($model->euro_compra, 2) }}</div>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('euro_venta', 'Euro venta') }}
                        <div class="form-control-plaintext">${{ number_format($model->euro_venta, 2) }}</div>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('dolar_moneda_compra', 'Dolar moneda compra') }}
                        <div class="form-control-plaintext">${{ number_format($model->dolar_moneda_compra, 2) }}</div>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('dolar_moneda_venta', 'Dolar moneda venta') }}
                        <div class="form-control-plaintext">${{ number_format($model->dolar_moneda_venta, 2) }}</div>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('iva_divisa', 'IVA divisa') }}
                        <div class="form-control-plaintext">${{ number_format($model->iva_divisa, 2) }}</div>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-12 col-sm-12 col-12">
                    <div class="form-group text-right">
                        <a href="{{ route('sucursales.index') }}" class="btn btn-sm btn-secondary">Regresar</a>
                        <a href="{{ route('sucursales.edit', $model->sucursal_id) }}" class="btn btn-sm btn-success">Editar</a>

                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection