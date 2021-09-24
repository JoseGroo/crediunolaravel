@extends('layouts.layout')

@section("title", "Detalles del descuento")

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
                        {{ Form::label('cliente_id', __('validation.attributes.cliente')) }}
                        <div class="form-control-plaintext">{{ $model->tbl_cliente->full_name }}</div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('importe', __('validation.attributes.importe')) }}
                        <div class="form-control-plaintext">@money_format($model->importe)</div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('importe_acreditado', __('validation.attributes.importe_acreditado')) }}
                        <div class="form-control-plaintext">@money_format($model->importe_acreditado)</div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('estatus', __('validation.attributes.estatus')) }}
                        <div class="form-control-plaintext">{{ \App\Enums\estatus_descuentos::getDescription($model->estatus) }}</div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('fecha_vigencia', __('validation.attributes.fecha_vigencia')) }}
                        <div class="form-control-plaintext">{{ $model->fecha_vigencia }}</div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('comentario', __('validation.attributes.comentario')) }}
                        <div class="form-control-plaintext">{{ $model->comentario }}</div>
                    </div>
                </div>

            </div>

            <div class="form-row">
                <div class="col-md-12 col-sm-12 col-12">
                    <div class="form-group text-right">
                        <a href="{{ route('descuentos.index') }}" class="btn btn-sm btn-secondary">Regresar</a>
                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection