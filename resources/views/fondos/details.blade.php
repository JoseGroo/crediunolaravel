@extends('layouts.layout')

@section("title", "Detalles del fondo")

@php
    use \App\Enums\tipo_fondo;
@endphp

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
                        {{ Form::label('fondo', 'Fondo') }}
                        <div class="form-control-plaintext">{{ $model->fondo }}</div>
                    </div>
            </div>

            <div class="col-md-4 col-sm-6 col-12">
                <div class="form-group">
                    {{ Form::label('tipo', 'Tipo') }}
                    <div class="form-control-plaintext">{{ tipo_fondo::getDescription($model->tipo) }}</div>
                </div>
            </div>

            @if($model->tipo == tipo_fondo::Bancario)
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('banco', 'Banco') }}
                        <div class="form-control-plaintext">{{ $model->banco }}</div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('cuenta', 'Cuenta') }}
                        <div class="form-control-plaintext">{{ $model->cuenta }}</div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('ultimo_cheque_girado', 'Ultimo cheque girado') }}
                        <div class="form-control-plaintext">{{ $model->ultimo_cheque_girado }}</div>
                    </div>
                </div>
            @endif
        </div>

        <div class="form-row">
            <div class="col-md-2 col-sm-6 col-12">
                <div class="form-group">
                    {{ Form::label('importe_pesos', 'Importe en pesos') }}
                    <div class="form-control-plaintext">{{ number_format($model->importe_pesos, 2) }}</div>
                </div>
            </div>

            <div class="col-md-2 col-sm-6 col-12">
                <div class="form-group">
                    {{ Form::label('importe_dolares', 'Importe en dolares') }}
                    <div class="form-control-plaintext">{{ number_format($model->importe_dolares, 2) }}</div>
                </div>
            </div>

            <div class="col-md-2 col-sm-6 col-12">
                <div class="form-group">
                    {{ Form::label('importe_dolares_moneda', 'Importe en dolares moneda') }}
                    <div class="form-control-plaintext">{{ number_format($model->importe_dolares_moneda, 2) }}</div>
                </div>
            </div>

            <div class="col-md-2 col-sm-6 col-12">
                <div class="form-group">
                    {{ Form::label('importe_euros', 'Importe en euros') }}
                    <div class="form-control-plaintext">{{ number_format($model->importe_euros, 2) }}</div>
                </div>
            </div>
            @php
                $icon_color = $model->activo ? "font-active-status" : "font-inactive-status";
            @endphp
            <div class="col-md-2 col-sm-6 col-12">
                <div class="form-group">
                    {{ Form::label('status', 'Estatus') }}
                    <div class="form-control-plaintext"><i title="" class="mdi mdi-circle mdi-24px {{ $icon_color }}"></i></div>
                </div>
            </div>
        </div>

            <div class="form-row">
                <div class="col-md-12 col-sm-12 col-12">
                    <div class="form-group text-right">
                        <a href="{{ route('fondos.index') }}" class="btn btn-sm btn-secondary">Regresar</a>
                        @if($model->activo)
                            <a href="{{ route('fondos.edit', $model->fondo_id) }}" class="btn btn-sm btn-success">Editar</a>
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection